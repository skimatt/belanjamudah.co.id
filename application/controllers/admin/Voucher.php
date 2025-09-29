<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Voucher extends Admin_Controller { 

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Voucher_model');
        $this->load->library('form_validation');
    }

    // List Voucher (READ)
    public function index()
    {
        $data['vouchers'] = $this->Voucher_model->get_all_vouchers();
        $data['page_title'] = 'Manajemen Voucher Diskon';
        $data['content_view'] = 'admin/voucher/list_voucher'; 
        $this->load->view('admin/templates/admin_templates', $data);
    }
    
    // Tambah/Edit Voucher (Form & Process)
    public function form($id = NULL)
    {
        $data['page_title'] = $id ? 'Edit Voucher' : 'Buat Voucher Baru';
        $data['voucher'] = NULL;
        
        if ($id) {
            $data['voucher'] = $this->Voucher_model->get_voucher($id);
            if (!$data['voucher']) {
                $this->session->set_flashdata('error', 'Voucher tidak ditemukan.');
                redirect('admin/voucher');
            }
        }

        if ($this->input->post()) {
            
            // Cek keunikan kode
            $is_unique = ($id && $data['voucher']->code == $this->input->post('code')) ? '' : '|is_unique[vouchers.code]';
            
            $this->form_validation->set_rules('code', 'Kode Voucher', 'trim|required|xss_clean|alpha_dash' . $is_unique);
            $this->form_validation->set_rules('type', 'Tipe Diskon', 'trim|required|in_list[percent,fixed]');
            $this->form_validation->set_rules('value', 'Nilai Diskon', 'trim|required|numeric|greater_than[0]');
            $this->form_validation->set_rules('max_usage', 'Maks. Pemakaian', 'trim|required|integer|greater_than_equal_to[1]');
            $this->form_validation->set_rules('valid_until', 'Tanggal Kadaluarsa', 'trim|required');

            if ($this->form_validation->run() == FALSE) {
                // Gagal, load ulang form dengan error
            } else {
                
                $valid_until = $this->input->post('valid_until', TRUE);
                
                $save_data = [
                    'code'               => strtoupper($this->input->post('code', TRUE)),
                    'type'               => $this->input->post('type', TRUE),
                    'value'              => $this->input->post('value', TRUE),
                    'valid_from'         => date('Y-m-d H:i:s'), // Langsung aktif saat dibuat
                    'valid_until'        => $valid_until . ' 23:59:59', // Berakhir di akhir hari
                    'min_order_amount'   => $this->input->post('min_order_amount', TRUE) ?: 0,
                    'max_usage'          => $this->input->post('max_usage', TRUE),
                    'is_active'          => $this->input->post('is_active', TRUE) ?: 0,
                    'is_shipping_discount' => $this->input->post('is_shipping_discount', TRUE) ?: 0,
                ];

                if ($id) {
                    $this->Voucher_model->update_voucher($id, $save_data);
                    $this->session->set_flashdata('success', 'Voucher berhasil diperbarui!');
                } else {
                    $this->Voucher_model->create_voucher($save_data);
                    $this->session->set_flashdata('success', 'Voucher baru berhasil dibuat!');
                }
                redirect('admin/voucher');
                return;
            }
        }
        
        $data['content_view'] = 'admin/voucher/form_voucher';
        $this->load->view('admin/templates/admin_templates', $data);
    }

    // Hapus Voucher
    public function delete($id)
    {
        if ($this->Voucher_model->delete_voucher($id)) {
            $this->session->set_flashdata('success', 'Voucher berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus voucher.');
        }
        redirect('admin/voucher');
    }
}