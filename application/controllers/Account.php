<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // --- PERBAIKAN KRITIS: Tambahkan 'Order_model' di sini ---
        $this->load->model(['User_model', 'Order_model']); 
        $this->load->library('form_validation');
        $this->load->helper('toko'); 
        
        // Wajib Login untuk semua fungsi di Controller ini
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'Anda harus login untuk mengakses halaman akun.');
            redirect('auth');
            exit;
        }
    }

   // Dashboard Utama Customer (Redirect dari /dashboard)
public function index()
{
    $user_id = $this->session->userdata('id');

    // 1. Ambil Ringkasan Order dari Model
    $summary = $this->User_model->get_order_summary($user_id); // Method baru
    $recent_orders = $this->Order_model->get_orders_by_user($user_id, 5); // 5 pesanan terakhir
    
    $data['page_title'] = 'Dashboard Akun';
    $data['user'] = $this->User_model->get_user_by_id($user_id);
    $data['summary'] = $summary;
    $data['recent_orders'] = $recent_orders;
    $data['content_view'] = 'frontend/account/dashboard_content'; 

    $this->load->view('frontend/templates/header', $data);
    $this->load->view('frontend/account/account_layout', $data); 
    $this->load->view('frontend/templates/footer');
}


    // --- MANAJEMEN ALAMAT (CRUD) ---
    public function addresses()
    {
        $user_id = $this->session->userdata('id');
        $data['page_title'] = 'Manajemen Alamat';
        $data['addresses'] = $this->User_model->get_user_addresses($user_id);
        $data['content_view'] = 'frontend/account/addresses_list'; 
        
        $this->load->view('frontend/templates/header', $data);
        $this->load->view('frontend/account/account_layout', $data); // Layout Sidebar Akun
        $this->load->view('frontend/templates/footer');
    }

    // Tambah/Edit Alamat
    public function form_address($address_id = NULL)
    {
        $user_id = $this->session->userdata('id');
        $data['address'] = NULL;

        if ($address_id) {
            $data['address'] = $this->User_model->get_address_by_id($address_id, $user_id);
            if (!$data['address']) {
                $this->session->set_flashdata('error', 'Alamat tidak ditemukan.');
                redirect('account/addresses');
            }
        }

        if ($this->input->post()) {
            $this->form_validation->set_rules('label', 'Label', 'trim|required|xss_clean');
            $this->form_validation->set_rules('recipient_name', 'Nama Penerima', 'trim|required|xss_clean');
            $this->form_validation->set_rules('phone_number', 'Nomor HP', 'trim|required|numeric|min_length[8]|xss_clean');
            $this->form_validation->set_rules('address_line_1', 'Alamat Lengkap', 'trim|required');
            $this->form_validation->set_rules('city', 'Kota', 'trim|required|xss_clean');
            $this->form_validation->set_rules('postal_code', 'Kode Pos', 'trim|required|numeric');

            if ($this->form_validation->run() == FALSE) {
                // Gagal validasi, tampilkan form kembali
            } else {
                $save_data = [
                    'user_id' => $user_id,
                    'label' => $this->input->post('label', TRUE),
                    'recipient_name' => $this->input->post('recipient_name', TRUE),
                    'phone_number' => $this->input->post('phone_number', TRUE),
                    'address_line_1' => $this->input->post('address_line_1'),
                    'address_line_2' => $this->input->post('address_line_2'),
                    'city' => $this->input->post('city', TRUE),
                    'postal_code' => $this->input->post('postal_code', TRUE),
                    'is_main' => $this->input->post('is_main') ? 1 : 0
                ];

                if ($address_id) {
                    $this->User_model->update_address($address_id, $save_data);
                    $this->session->set_flashdata('success', 'Alamat berhasil diperbarui.');
                } else {
                    $this->User_model->create_address($save_data);
                    $this->session->set_flashdata('success', 'Alamat baru berhasil ditambahkan.');
                }
                redirect('account/addresses');
                return;
            }
        }

        $data['page_title'] = $address_id ? 'Edit Alamat' : 'Tambah Alamat Baru';
        $data['content_view'] = 'frontend/account/address_form'; 
        
        $this->load->view('frontend/templates/header', $data);
        $this->load->view('frontend/account/account_layout', $data);
        $this->load->view('frontend/templates/footer');
    }
    
    // Set Alamat Utama
    public function set_main_address($address_id)
    {
        $user_id = $this->session->userdata('id');
        if ($this->User_model->set_main_address($address_id, $user_id)) {
            $this->session->set_flashdata('success', 'Alamat utama berhasil diubah.');
        } else {
            $this->session->set_flashdata('error', 'Gagal mengubah alamat utama.');
        }
        redirect('account/addresses');
    }

    // Hapus Alamat
    public function delete_address($address_id)
    {
        $user_id = $this->session->userdata('id');
        if ($this->User_model->delete_address($address_id, $user_id)) {
            $this->session->set_flashdata('success', 'Alamat berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus alamat.');
        }
        redirect('account/addresses');
    }
}