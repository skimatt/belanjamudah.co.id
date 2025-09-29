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

    // --- 1. PROSES REDIRECT GATEWAY (DANA/XENDIT) ---
    public function process_gateway($order_id)
    {
        $data = $this->_setup_data($order_id);
        if ($data === FALSE) return;
        $order = $data['order'];

        // Cek Status Pembayaran
        if ($order->payment_status !== 'pending') {
            $this->session->set_flashdata('info', 'Pembayaran sudah ' . strtoupper($order->payment_status) . '.');
            redirect('order/invoice/' . $order_id);
            return;
        }

        $method = $order->payment_method;
        $data['page_title'] = 'Proses Pembayaran ' . $method;

        if ($method == 'DANA' || $method == 'XENDIT') {
             // Arahkan ke halaman instruksi (simulasi Payment Gateway)
             $this->load->view('frontend/templates/header', $data);
             $this->load->view('frontend/payment/dana_instructions_view', $data);
             $this->load->view('frontend/templates/footer');
        } elseif ($method == 'TRANSFER_MANUAL') {
             // Arahkan ke halaman transfer manual (jika ada view terpisah)
             $this->load->view('frontend/templates/header', $data);
             $this->load->view('frontend/payment/transfer_manual_view', $data);
             $this->load->view('frontend/templates/footer');
        } else {
            $this->session->set_flashdata('error', 'Metode pembayaran tidak didukung.');
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
}