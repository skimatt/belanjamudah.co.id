<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends Admin_Controller { 

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['Order_model', 'Cart_model']); // Pastikan Cart_model dimuat untuk helper
        $this->load->library('form_validation');
        $this->load->helper('toko'); 
    }

    // Fungsi Index: Menampilkan Daftar Pesanan (READ LIST)
    public function index()
    {
        $data['orders'] = $this->Order_model->get_all_orders();
        $data['page_title'] = 'Manajemen Pesanan';
        $data['content_view'] = 'admin/order/list_order'; 
        $this->load->view('admin/templates/admin_templates', $data);
    }
    
    public function detail($order_id)
    {
        // PERBAIKAN KRITIS: Memanggil method yang benar di Model
        $order = $this->Order_model->get_order_detail($order_id); 
        
        if (!$order) {
            $this->session->set_flashdata('error', 'Pesanan tidak ditemukan.');
            redirect('admin/order');
        }

        $data['order'] = $order;
        $data['items'] = $this->Order_model->get_order_items($order_id);
        $data['status_list'] = $this->Order_model->status_list;
        $data['page_title'] = 'Detail Pesanan #' . $order_id;
        $data['content_view'] = 'admin/order/detail_order'; 
        $this->load->view('admin/templates/admin_templates', $data);
    }
    
    // --- PROSES UPDATE STATUS (Update Status & Tracking) ---
    public function update_status($order_id)
    {
        $this->form_validation->set_rules('new_status', 'Status Baru', 'trim|required|xss_clean');
        $this->form_validation->set_rules('tracking_number', 'Nomor Resi', 'trim|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
        } else {
            $new_status = $this->input->post('new_status', TRUE);
            $tracking_number = $this->input->post('tracking_number', TRUE);

            if ($this->Order_model->update_order_status($order_id, $new_status, $tracking_number)) {
                $this->session->set_flashdata('success', 'Status pesanan #' . $order_id . ' berhasil diperbarui menjadi: ' . strtoupper($new_status));
                
                // TIPS: Di sini tempat Anda mengirim Notifikasi/Email ke customer!
                // $this->_send_notification($order_id, $new_status); 

            } else {
                $this->session->set_flashdata('error', 'Gagal memperbarui status pesanan. Cek log.');
            }
        }
        
        redirect('admin/order/detail/' . $order_id);
    }

    // ... Tambahkan fungsi cetak_invoice() nanti jika diperlukan ...
}