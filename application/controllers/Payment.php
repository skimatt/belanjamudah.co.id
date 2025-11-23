<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // Memuat semua model yang mungkin dibutuhkan (termasuk Cart_model untuk hitungan navbar)
        $this->load->model(['Order_model', 'Cart_model']); 
        $this->load->library('session');
    }
    
    // Helper untuk Memastikan Pengguna Login & Mengambil Data Pendukung
    private function _setup_data($order_id = NULL)
    {
        $user_id = $this->session->userdata('id');
        if (!$user_id) {
            redirect('auth');
            return FALSE;
        }
        
        $data = [];
        $data['user_id'] = $user_id;
        $data['cart_total_items'] = $this->Cart_model->get_total_items_count($user_id);
        
        if ($order_id) {
            // PERBAIKAN KRITIS: Menggunakan get_order_detail_by_user() yang sudah terdefinisi dan aman
            $data['order'] = $this->Order_model->get_order_detail_by_user($order_id, $user_id);
            if (!$data['order']) {
                 show_404();
                 return FALSE;
            }
        }
        return $data;
    }

    // --- FUNGSI REDIRECT GATEWAY UTAMA ---
public function process_gateway($order_id)
{
    // 1. Setup Data dan Validasi Order
    $data = $this->_setup_data($order_id);
    if ($data === FALSE) return;
    $order = $data['order'];
    $user_id = $data['user_id']; // Ambil user_id dari helper setup
    
    // Asumsi: Ambil cart data lagi untuk mendapatkan item detail lengkap
    $cart_data = $this->Cart_model->get_user_cart_data($user_id);
    
    // PENTING: Jika sudah PAUSED, jangan proses lagi
    if ($order->payment_status !== 'pending') {
        $this->session->set_flashdata('info', 'Pesanan sudah lunas.');
        redirect('order/invoice/' . $order_id);
        return;
    }

    // 2. Load Midtrans Library (Wajib di load di Controller jika digunakan di method ini)
    $this->load->library('midtrans_payment'); 

    // 3. Panggil Midtrans Snap untuk mendapatkan Token
    try {
        // Ambil data yang dibutuhkan untuk Midtrans
        // Catatan: Gross amount harus float/integer
        $gross_amount = (float)$order->total_amount; 
        
        $snap_token = $this->midtrans_payment->create_snap_transaction(
            $order_id, 
            $gross_amount, 
            (array)$order, // Kirim data order untuk detail customer
            $cart_data->items // Kirim item detail
        );

        // 4. Simpan token di sesi dan redirect ke halaman eksekusi Snap
        $this->session->set_userdata('snap_token', $snap_token);
        $this->session->set_userdata('current_order_id', $order_id); 
        
        // REDIRECT FINAL KE HALAMAN YANG MENGANDUNG SCRIPT JAVASCRIPT SNAP
        redirect('payment/snap_execute'); 

    } catch (Exception $e) {
        // Log dan tangani error jika Midtrans API gagal (misalnya, kunci salah, order_id duplikat)
        log_message('error', 'Midtrans Snap Failed for Order #' . $order_id . ': ' . $e->getMessage());
        $this->session->set_flashdata('error', 'Gagal membuat pembayaran: ' . $e->getMessage());
        redirect('order/invoice/' . $order_id);
    }
}
    
    // --- 2. FUNGSI TRACKING PESANAN (FRONTEND) ---
    public function tracking($order_id)
    {
        $data = $this->_setup_data($order_id);
        if ($data === FALSE) return;
        
        $data['page_title'] = 'Lacak Pesanan #' . $order_id;
        
        $this->load->view('frontend/templates/header', $data);
        $this->load->view('frontend/payment/tracking_view', $data); // Load View Timeline
        $this->load->view('frontend/templates/footer');
    }

    // --- 3. WEBHOOK ENDPOINT (BACKEND KRITIS) ---
    // Endpoint ini harus dikecualikan dari CSRF di config/config.php
    public function webhook()
    {
        // WAJIB: Atur headers dan dapatkan raw input
        header('Content-Type: application/json');

        $raw_notification = $this->input->raw_input_stream;
        $data = json_decode($raw_notification, TRUE); 

        // 1. Validasi & Verifikasi Signature (TIDAK ADA DALAM KODE INI)

        // 2. Logika Update Status Order
        if (isset($data['order_id']) || isset($data['external_id'])) {
            $order_id = $data['order_id'] ?? $data['external_id'];
            $status_from_gateway = $data['status'] ?? 'UNKNOWN';

            // Logika: Jika status dari gateway adalah PAID/SUCCESS
            if ($status_from_gateway == 'PAID' || $status_from_gateway == 'SUCCESS') {
                
                // 3. Update Status Pembayaran & Order
                $this->Order_model->update_payment_status($order_id, 'paid');
                $this->Order_model->update_order_status($order_id, 'packing'); // Langsung packing
                
                // TODO: Kirim notifikasi ke Admin
            }
        }
        
        // WAJIB: Beri respons 200 OK ke Payment Gateway
        echo json_encode(['status' => 'success', 'message' => 'Notification processed.']);
        http_response_code(200);
        exit();
    }


    // Path File: application/controllers/Payment.php

// ... (Metode lainnya) ...

// --- FUNGSI BARU: MENAMPILKAN MODAL SNAP ---
public function snap_execute()
{
    $snap_token = $this->session->userdata('snap_token');
    $order_id = $this->session->userdata('current_order_id');

    if (empty($snap_token) || empty($order_id)) {
        $this->session->set_flashdata('error', 'Token pembayaran tidak ditemukan.');
        redirect('account');
    }

    $data['snap_token'] = $snap_token;
    $data['order_id'] = $order_id;
    $data['page_title'] = 'Selesaikan Pembayaran';

    $this->load->view('frontend/templates/header', $data);
    $this->load->view('frontend/payment/snap_view', $data); // View untuk eksekusi JS Snap
    $this->load->view('frontend/templates/footer');
}

// ... (Tambahkan method webhook untuk update status dari Midtrans) ...
}