<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['Order_model', 'Cart_model', 'User_model']);
        $this->load->library('session');
    }

    // Helper untuk Cek Login Wajib
    private function _check_login()
    {
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'Silakan login untuk melihat riwayat pesanan Anda.');
            redirect('auth');
            return FALSE;
        }
        return TRUE;
    }

    // Histori Pesanan (READ LIST)
    public function index()
    {
        if (!$this->_check_login()) return;
        
        $user_id = $this->session->userdata('id');
        $data['orders'] = $this->Order_model->get_orders_by_user($user_id);
        
        
        $data['page_title'] = 'Riwayat Pesanan';
        $data['cart_total_items'] = $this->Cart_model->get_total_items_count($user_id);

        $this->load->view('frontend/templates/header', $data);
        $this->load->view('frontend/order/history_view', $data);
        $this->load->view('frontend/templates/footer');
    }

    // Tampilkan Invoice/Detail Pesanan (READ DETAIL)
    public function invoice($order_id)
    {
        if (!$this->_check_login()) return;
        
        // Ambil detail order dan item, difilter oleh user_id (KEAMANAN)
        $order = $this->Order_model->get_order_detail_by_user($order_id, $this->session->userdata('id'));

        if (!$order) {
            show_404();
            return;
        }

        $data['order'] = $order;
        $data['items'] = $this->Order_model->get_order_items($order_id);
        $data['page_title'] = 'Invoice #' . $order_id;
        $data['cart_total_items'] = $this->Cart_model->get_total_items_count($this->session->userdata('id'));
        
        $this->load->view('frontend/templates/header', $data);
        $this->load->view('frontend/order/invoice_view', $data);
        $this->load->view('frontend/templates/footer');
    }

    /**
     * Tampilkan halaman checkout untuk pesanan 'draft' yang dibuat dari alur "Beli Sekarang".
     *
     * @param int $order_id ID pesanan draft.
     */
    public function draft($order_id)
    {
        if (!$this->_check_login()) return;

        $user_id = $this->session->userdata('id');
        
        // Ambil detail pesanan draft dan item, pastikan statusnya 'draft'
        $order = $this->Order_model->get_draft_order_by_user($order_id, $user_id);

        if (!$order || $order->order_status !== 'draft') {
            $this->session->set_flashdata('error', 'Pesanan tidak ditemukan atau tidak valid.');
            redirect('cart');
            return;
        }
        
        // Ambil data pendukung lainnya yang dibutuhkan halaman checkout
        $data['order'] = $order;
        $data['items'] = $this->Order_model->get_order_items($order_id);
        $data['addresses'] = $this->User_model->get_user_addresses($user_id);
        $data['couriers'] = ['JNE', 'TIKI', 'POS', 'SiCepat']; // Placeholder
        $data['payment_methods'] = ['Transfer Bank Manual', 'Payment Gateway (Midtrans/Xendit)']; // Placeholder
        $data['page_title'] = 'Selesaikan Pembelian';
        $data['cart_total_items'] = $this->Cart_model->get_total_items_count($user_id);

        $this->load->view('frontend/templates/header', $data);
        $this->load->view('frontend/checkout/checkout_view', $data);
        $this->load->view('frontend/templates/footer');
    }
}