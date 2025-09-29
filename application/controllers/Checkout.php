<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Checkout extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // --- PERBAIKAN KRITIS: Tambahkan 'Voucher_model' di sini ---
        $this->load->model(['Cart_model', 'User_model', 'Order_model', 'Variant_model', 'Voucher_model']); 
        $this->load->library('form_validation');
        $this->load->helper('toko');
    }

   public function index()
{
    if (!$this->session->userdata('logged_in')) {
        $this->session->set_flashdata('error', 'Silakan login atau daftar untuk melanjutkan checkout.');
        redirect('auth');
        return;
    }

    $user_id = $this->session->userdata('id');
    $data['cart_data'] = $this->Cart_model->get_user_cart_data($user_id);

    if (empty($data['cart_data']->items)) {
        $this->session->set_flashdata('error', 'Keranjang belanja Anda kosong.');
        redirect('cart');
        return;
    }

    // Alamat user
    $data['addresses'] = $this->User_model->get_user_addresses($user_id);

    // Data kurir & metode pembayaran
    $data['couriers'] = [
        'jne'    => 'JNE',
        'tiki'   => 'TIKI',
        'pos'    => 'POS Indonesia',
        'sicepat'=> 'SiCepat',
        'cod'    => 'COD (Cash On Delivery)'
    ];
    $data['payment_methods'] = [
        'cod'            => 'Bayar di Tempat (COD)',
        'bank_transfer'  => 'Transfer Bank',
        'va'             => 'Virtual Account',
        'ewallet'        => 'E-Wallet (OVO, GoPay, Dana)',
    ];

    $data['page_title'] = 'Proses Checkout';
    $data['cart_total_items'] = $this->Cart_model->get_total_items_count($user_id);

    $this->load->view('frontend/templates/header', $data);
    $this->load->view('frontend/checkout/checkout_view', $data);
    $this->load->view('frontend/templates/footer');
}


  // Path File: application/controllers/Checkout.php (Di dalam class Checkout)

// --- PROSES PEMBUATAN ORDER (TRANSACTIONAL) ---
public function process()
{
    // 1. Cek Login (Kritis)
    $user_id = $this->session->userdata('id');
    if (!$user_id) {
        $this->session->set_flashdata('error', 'Sesi berakhir. Mohon login ulang.');
        redirect('auth');
        return;
    }

    // 2. Validasi Input Checkout (Rule validation tetap sama)
    $this->form_validation->set_rules('address_id', 'Alamat Pengiriman', 'required|integer');
    $this->form_validation->set_rules('shipping_service', 'Layanan Pengiriman', 'required|xss_clean');
    $this->form_validation->set_rules('payment_method', 'Metode Pembayaran', 'required|xss_clean');
    $this->form_validation->set_rules('shipping_cost', 'Biaya Kirim', 'required|numeric|greater_than_equal_to[0]'); 
    
    // 3. AMBIL SEMUA DATA SEBELUM VALIDASI RUN
    $address_id = $this->input->post('address_id', TRUE);
    $shipping_courier = $this->input->post('shipping_service', TRUE);
    $payment_method = $this->input->post('payment_method', TRUE);
    $shipping_cost = (float)$this->input->post('shipping_cost', TRUE);
    $seller_notes = $this->input->post('seller_notes', TRUE);
    
    // --- AMBIL DATA VOUCHER DAN DISKON DARI HIDDEN FIELD ---
    $discount_amount = (float)$this->input->post('discount_amount', TRUE); 
    $voucher_code = $this->input->post('voucher_code_used', TRUE);
    $voucher_id = $this->input->post('voucher_id_used', TRUE); // Ambil ID Voucher
    
    if ($this->form_validation->run() == FALSE) {
        $this->index(); 
        return;
    }

    // 4. AMBIL DATA KRITIS DARI MODEL
    $cart_data = $this->Cart_model->get_user_cart_data($user_id);
    $address_detail = $this->User_model->get_address_by_id($address_id, $user_id); 

    if (empty($cart_data->items) || !$address_detail) {
        $this->session->set_flashdata('error', 'Keranjang kosong atau alamat tidak valid.');
        redirect('checkout');
        return;
    }

    // 5. LOGIKA STATUS & TOTAL
    $subtotal = $cart_data->total;
    $total_amount = $subtotal + $shipping_cost - $discount_amount;
    
    $payment_status = 'pending';
    $order_status = 'pending';
    $redirect_to_gateway = FALSE;
    
    if ($payment_method === 'COD') {
        $payment_status = 'paid';
        $order_status = 'packing'; 
    } elseif (in_array($payment_method, ['DANA', 'XENDIT', 'TRANSFER_MANUAL'])) {
        $redirect_to_gateway = TRUE;
    }

    // 6. MULAI TRANSAKSI DATABASE
    $this->db->trans_start();

        // 6a. Buat Entri Order Utama
        $order_data = [
            'user_id' => $user_id,
            'total_amount' => $total_amount,
            'shipping_cost' => $shipping_cost,
            'discount_amount' => $discount_amount, 
            'voucher_code' => $voucher_code, 
            'payment_method' => $payment_method,
            'shipping_courier' => $shipping_courier,
            'shipping_address' => $this->_format_address($address_detail),
            'order_status' => $order_status,
            'payment_status' => $payment_status,
            'notes' => $seller_notes
        ];
        
        $order_id = $this->Order_model->create_order_from_cart($order_data, $cart_data);

        // 6b. [KRITIS] Pengurangan Stok
        $inventory_ok = $this->Cart_model->decrement_inventory($cart_data->items); 
        
        // 6c. Pengosongan Keranjang
        $this->Cart_model->clear_user_cart($user_id); 

        // 6d. INTEGRASI PENCATATAN VOUCHER
        if ($discount_amount > 0 && !empty($voucher_id) && $order_id) {
            $usage_recorded = $this->Voucher_model->record_voucher_usage($voucher_id, $user_id, $order_id);
            
            if (!$usage_recorded) {
                // Jika pencatatan gagal, kita Rollback semua transaksi
                $inventory_ok = FALSE; // Paksa rollback jika voucher gagal dicatat
            }
        }
        
        // 6e. CEK STATUS INVENTORY FINAL
        if (!$inventory_ok) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', 'Gagal memproses pesanan: Stok produk tidak mencukupi atau masalah pencatatan voucher.');
            redirect('checkout');
            return;
        }

    $this->db->trans_complete();

    // 7. Cek Status Transaksi (untuk memastikan tidak ada kesalahan SQL umum)
    if ($this->db->trans_status() === FALSE) {
        $this->session->set_flashdata('error', 'Gagal memproses pesanan akibat error database. Silakan coba lagi.');
        redirect('checkout');
        return;
    }

    // 8. Sukses: Redirect Sesuai Metode
    if ($redirect_to_gateway) {
        $this->session->set_flashdata('success', 'Pesanan berhasil dibuat. Lanjut ke pembayaran.');
        redirect('payment/process_gateway/' . $order_id); 
    } else {
        $this->session->set_flashdata('success', 'Pesanan COD Anda berhasil dibuat dan siap dikemas.');
        redirect('order/invoice/' . $order_id); 
    }
}


// Helper untuk Simulasi Gateway (Hanya untuk MVP)
private function _generate_payment_gateway_url($order_id, $amount, $method)
{
    // Dalam produksi, ini akan memanggil API Midtrans/Xendit dan mendapatkan URL redirect.
    // Di MVP, kita hanya simulasikan URL konfirmasi pembayaran.
    return site_url('payment/gateway_redirect/' . $order_id . '?method=' . $method);
}

     // ... (Fungsi construct, index, dan helper _format_address tetap sama) ...
    // Pastikan _format_address ada dan terlihat seperti ini:
    private function _format_address($addr)
    {
        if (!$addr) return "Data alamat tidak valid.";
        return $addr->recipient_name . " (" . $addr->phone_number . ")\n" . 
               $addr->address_line_1 . "\n" . 
               $addr->city . ", " . $addr->postal_code;
    }

    // --- PROSES BELI LANGSUNG (BUY NOW) ---
    public function buy_now()
    {
        // Pastikan ini adalah permintaan AJAX
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        // Cek login
        if (!$this->session->userdata('logged_in')) {
            echo json_encode(['status' => 'redirect', 'url' => site_url('auth')]);
            return;
        }

        // Validasi input
        $this->form_validation->set_rules('variant_id', 'Varian Produk', 'required|integer');
        $this->form_validation->set_rules('qty', 'Kuantitas', 'required|integer|greater_than[0]');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(['status' => 'error', 'message' => validation_errors(), 'csrf_hash' => $this->security->get_csrf_hash()]);
            return;
        }

        $user_id = $this->session->userdata('id');
        $variant_id = $this->input->post('variant_id', TRUE);
        $qty = $this->input->post('qty', TRUE);

        // Mulai transaksi untuk memastikan operasi atomik
        $this->db->trans_start(); 

        // Cek stok dengan locking menggunakan FOR UPDATE
        $variant = $this->Variant_model->get_variant_detail_for_buy_now($variant_id, $qty);

        if (!$variant) {
            $this->db->trans_rollback(); // Batalkan transaksi
            echo json_encode(['status' => 'error', 'message' => 'Varian produk tidak valid atau stok tidak mencukupi.', 'csrf_hash' => $this->security->get_csrf_hash()]);
            return;
        }
        
        // Buat order draft (sederhana) untuk alur buy now
        $order_data = [
            'user_id' => $user_id,
            'total_amount' => $variant->price * $qty,
            'order_status' => 'draft',
            'payment_status' => 'pending'
        ];

        $items_data = [
            [
                'product_id' => $variant->product_id,
                'variant_id' => $variant_id,
                'quantity' => $qty,
                'price' => $variant->price
            ]
        ];
        
        $order_id = $this->Order_model->create_draft_order($order_data, $items_data);

        if ($order_id) {
            $this->db->trans_commit(); // Komit transaksi
            echo json_encode(['status' => 'success', 'message' => 'Langsung ke Checkout.', 'url' => site_url('checkout/draft/' . $order_id), 'csrf_hash' => $this->security->get_csrf_hash()]);
        } else {
            $this->db->trans_rollback(); // Batalkan transaksi
            echo json_encode(['status' => 'error', 'message' => 'Gagal memproses item. Stok mungkin kurang.', 'csrf_hash' => $this->security->get_csrf_hash()]);
        }
        
        // Hapus baris di bawah ini. Kode ini tidak diperlukan dan menyebabkan masalah AJAX.
        // redirect('checkout');
    }

    // Path File: application/controllers/Checkout.php

// ... (Metode lainnya) ...

/**
 * Memvalidasi kode voucher via AJAX dan mengembalikan nilai diskon.
 * Dipanggil dari checkout_view.js.
 */
public function validate_voucher_ajax()
{
    // WAJIB: Hanya izinkan request AJAX
    if (!$this->input->is_ajax_request()) {
        exit('Akses ditolak.');
    }
    
    // 1. Cek Login (Kritis)
    $user_id = $this->session->userdata('id');
    if (!$user_id) {
        echo json_encode(['status' => 'redirect', 'message' => 'Anda harus login untuk menggunakan voucher.', 'url' => site_url('auth')]);
        return;
    }

    // 2. Set Rules Validasi
    // Catatan: Pastikan View mengirim 'subtotal_amount'
    $this->form_validation->set_rules('voucher_code', 'Kode Voucher', 'trim|required|xss_clean');
    $this->form_validation->set_rules('subtotal_amount', 'Subtotal', 'required|numeric'); 

    if ($this->form_validation->run() == FALSE) {
        // Validation failed, kirim error dari CI Form Validation
        echo json_encode(['status' => 'error', 'message' => validation_errors()]);
        return;
    }
    
    // 3. Ambil Data (Setelah Validasi POST Sukses)
    $code = $this->input->post('voucher_code', TRUE);
    $subtotal_amount = (float)$this->input->post('subtotal_amount'); 
    
    // 4. Panggil Model Validasi
    $result = $this->Voucher_model->validate_voucher($code, $user_id, $subtotal_amount);
    
    // 5. Kirim Respon Final
    if ($result->status === 'success') {
        echo json_encode([
            'status'        => 'success',
            'message'       => 'Voucher berhasil diterapkan!',
            // --- KUNCI VOUCHER ---
            'discount_amount' => $result->discount_amount, // Nilai Diskon
            'voucher_id'    => $result->voucher_id,      // ID Voucher (Kritis untuk pencatatan di DB)
            'code'          => $result->code,
            // ---------------------
            'csrf_hash'     => $this->security->get_csrf_hash() // Wajib
        ]);
    } else {
        // Gagal validasi (misal: kadaluarsa, minimum order, sudah pernah pakai)
        echo json_encode([
            'status'    => 'error',
            'message'   => $result->message,
            'csrf_hash' => $this->security->get_csrf_hash()
        ]);
    }
}
}