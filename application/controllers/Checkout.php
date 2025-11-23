<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Checkout extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // Memuat semua model, library, dan helper yang diperlukan
        $this->load->model(['Cart_model', 'User_model', 'Order_model', 'Variant_model', 'Voucher_model']); 
        $this->load->library('form_validation');
        $this->load->helper('toko');
        $this->load->library('midtrans_payment'); 
    }

    // =======================================================
    // MARKAS: FUNGSI UTAMA (INDEX)
    // =======================================================
    public function index()
    {
        $user_id = $this->session->userdata('id');
        if (!$user_id) {
            $this->session->set_flashdata('error', 'Silakan login atau daftar untuk melanjutkan checkout.');
            redirect('auth');
            return;
        }

        $data['cart_data'] = $this->Cart_model->get_user_cart_data($user_id);

        if (empty($data['cart_data']->items)) {
            $this->session->set_flashdata('error', 'Keranjang belanja Anda kosong.');
            redirect('cart');
            return;
        }

        // Ambil data pendukung
        $data['total_weight'] = $this->Cart_model->get_total_weight($user_id);
        $data['addresses'] = $this->User_model->get_user_addresses($user_id);
        $data['page_title'] = 'Proses Checkout';
        $data['cart_total_items'] = $this->Cart_model->get_total_items_count($user_id);
        
        // Data kurir & metode pembayaran (digunakan di View)
        $data['couriers'] = ['jne', 'j&t', 'sicepat']; 
        $data['payment_methods'] = ['cod', 'dana', 'xendit', 'transfer_manual'];

        // Load Template
        $this->load->view('frontend/templates/header', $data);
        $this->load->view('frontend/checkout/checkout_view', $data);
        $this->load->view('frontend/templates/footer');
    }

    // =======================================================
    // MARKAS: FUNGSI TRANSAKSIONAL (CREATE ORDER)
    // =======================================================

    // --- PROSES PEMBUATAN ORDER (TRANSACTIONAL) ---
    public function process()
    {
        $user_id = $this->session->userdata('id');
        if (!$user_id) {
            $this->session->set_flashdata('error', 'Sesi berakhir. Mohon login ulang.');
            redirect('auth');
            return;
        }

        // 1. Validasi Input Checkout
        $this->form_validation->set_rules('address_id', 'Alamat Pengiriman', 'required|integer');
        $this->form_validation->set_rules('shipping_service', 'Layanan Pengiriman', 'required|xss_clean');
        $this->form_validation->set_rules('payment_method', 'Metode Pembayaran', 'required|xss_clean');
        $this->form_validation->set_rules('shipping_cost', 'Biaya Kirim', 'required|numeric|greater_than_equal_to[0]'); 
        
        if ($this->form_validation->run() == FALSE) {
            $this->index(); 
            return;
        }

        // 2. AMBIL DATA KRITIS DARI POST
        $address_id = $this->input->post('address_id', TRUE);
        $shipping_courier = $this->input->post('shipping_service', TRUE);
        $payment_method = $this->input->post('payment_method', TRUE);
        $shipping_cost = (float)$this->input->post('shipping_cost', TRUE);
        $seller_notes = $this->input->post('seller_notes', TRUE);
        
        // --- AMBIL DATA VOUCHER DAN DISKON ---
        $discount_amount = (float)$this->input->post('discount_amount', TRUE); 
        $voucher_code = $this->input->post('voucher_code_used', TRUE);
        $voucher_id = $this->input->post('voucher_id_used', TRUE); 

        // 3. AMBIL DATA KRITIS DARI MODEL
        $cart_data = $this->Cart_model->get_user_cart_data($user_id);
        $address_detail = $this->User_model->get_address_by_id($address_id, $user_id); 

        if (empty($cart_data->items) || !$address_detail) {
            $this->session->set_flashdata('error', 'Keranjang kosong atau alamat tidak valid.');
            redirect('checkout');
            return;
        }

        // 4. LOGIKA STATUS & TOTAL
        $subtotal = $cart_data->total;
        $total_amount = $subtotal + $shipping_cost - $discount_amount;
        
        $payment_status = ($payment_method === 'COD') ? 'paid' : 'pending';
        $order_status = ($payment_method === 'COD') ? 'packing' : 'pending';
        $redirect_to_gateway = ($payment_method !== 'COD'); 

        // 5. MULAI TRANSAKSI DATABASE
        $this->db->trans_start();

            // 5a. Buat Entri Order Utama
            $order_data = [
                'user_id' => $user_id, 'total_amount' => $total_amount, 'shipping_cost' => $shipping_cost,
                'discount_amount' => $discount_amount, 'voucher_code' => $voucher_code, 'payment_method' => $payment_method,
                'shipping_courier' => $shipping_courier, 'shipping_address' => $this->_format_address($address_detail),
                'order_status' => $order_status, 'payment_status' => $payment_status, 'notes' => $seller_notes
            ];
            
            $order_id = $this->Order_model->create_order_from_cart($order_data, $cart_data);

            // 5b. Pengurangan Stok, Pengosongan Keranjang, Pencatatan Voucher
            $inventory_ok = $this->Cart_model->decrement_inventory($cart_data->items); 
            $this->Cart_model->clear_user_cart($user_id); 

            if ($discount_amount > 0 && !empty($voucher_id) && $order_id) {
                $usage_recorded = $this->Voucher_model->record_voucher_usage($voucher_id, $user_id, $order_id);
                if (!$usage_recorded) {
                    $inventory_ok = FALSE; // Paksa rollback jika voucher gagal dicatat
                }
            }
            
            // 5c. CEK STATUS INVENTORY FINAL
            if (!$inventory_ok || $this->db->trans_status() === FALSE) { 
                $this->db->trans_rollback();
                $this->session->set_flashdata('error', 'Gagal memproses pesanan: Stok produk tidak mencukupi atau masalah pencatatan voucher.');
                redirect('checkout');
                return;
            }

        $this->db->trans_complete();

        // 6. Sukses: Redirect Sesuai Metode
        if ($redirect_to_gateway) {
            // MIDTRANS / GATEWAY FLOW: Kita harus mendapatkan SNAP TOKEN
            try {
                $snap_token = $this->midtrans_payment->create_snap_transaction(
                    $order_id, $total_amount, (array)$order_data, $cart_data->items 
                );
                
                $this->session->set_userdata('snap_token', $snap_token);
                $this->session->set_userdata('current_order_id', $order_id); 
                
                $this->session->set_flashdata('success', 'Pesanan berhasil dibuat. Lanjut ke pembayaran Midtrans.');
                redirect('payment/snap_execute'); // <-- REDIRECT KE HALAMAN EKSEKUSI SNAP

            } catch (Exception $e) {
                log_message('error', 'Midtrans Snap Gagal: ' . $e->getMessage());
                $this->session->set_flashdata('error', 'Pembayaran Midtrans gagal diproses. Silakan bayar melalui Transfer Manual atau hubungi CS.');
                redirect('order/invoice/' . $order_id); 
            }
        } else {
            // COD: Langsung ke Invoice
            $this->session->set_flashdata('success', 'Pesanan COD Anda berhasil dibuat dan siap dikemas.');
            redirect('order/invoice/' . $order_id); 
        }
    }

    // =======================================================
    // MARKAS: FUNGSI AJAX & HELPER
    // =======================================================
    
    // Fungsi AJAX Validasi Voucher (Tetap sama)
    public function validate_voucher_ajax()
    {
        if (!$this->input->is_ajax_request()) { exit('Akses ditolak.'); }
        
        $user_id = $this->session->userdata('id');
        if (!$user_id) {
            echo json_encode(['status' => 'redirect', 'message' => 'Anda harus login untuk menggunakan voucher.', 'url' => site_url('auth')]);
            return;
        }

        $this->form_validation->set_rules('voucher_code', 'Kode Voucher', 'trim|required|xss_clean');
        $this->form_validation->set_rules('subtotal_amount', 'Subtotal', 'required|numeric'); 

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(['status' => 'error', 'message' => validation_errors()]);
            return;
        }
        
        $code = $this->input->post('voucher_code', TRUE);
        $subtotal_amount = (float)$this->input->post('subtotal_amount'); 
        
        $result = $this->Voucher_model->validate_voucher($code, $user_id, $subtotal_amount);
        
        if ($result->status === 'success') {
            echo json_encode([
                'status'        => 'success',
                'message'       => 'Voucher berhasil diterapkan!',
                'discount_amount' => $result->discount_amount, 
                'voucher_id'    => $result->voucher_id,      
                'code'          => $result->code,
                'csrf_hash'     => $this->security->get_csrf_hash() 
            ]);
        } else {
            echo json_encode([
                'status'    => 'error',
                'message'   => $result->message,
                'csrf_hash' => $this->security->get_csrf_hash()
            ]);
        }
    }

    // Helper untuk format alamat
    private function _format_address($addr)
    {
        if (!$addr) return "Data alamat tidak valid.";
        return $addr->recipient_name . " (" . $addr->phone_number . ")\n" . 
               $addr->address_line_1 . "\n" . 
               $addr->city . ", " . $addr->postal_code;
    }
}