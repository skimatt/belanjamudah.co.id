<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends Admin_Controller { 

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library('form_validation');
    }

    // Fungsi Index: Menampilkan Daftar Pengguna (READ LIST)
    public function index()
    {
        // Ambil semua pengguna kecuali Admin yang sedang login
        $data['users'] = $this->User_model->get_all_users_for_admin();
        $data['page_title'] = 'Manajemen Pengguna';
        $data['user_status_list'] = ['active', 'inactive', 'blocked'];
        
        $data['content_view'] = 'admin/user/list_user'; 
        $this->load->view('admin/templates/admin_templates', $data);
    }
    
    // Fungsi Detail/Edit: Menampilkan Detail Pengguna (READ DETAIL)
    public function detail($user_id)
    {
        // Cek Keamanan: Admin tidak boleh mengedit diri sendiri melalui fungsi ini
        if ($user_id == $this->session->userdata('id')) {
            $this->session->set_flashdata('error', 'Anda tidak dapat mengedit profil sendiri di sini.');
            redirect('admin/user');
        }

        $user = $this->User_model->get_user_by_id($user_id);
        
        if (!$user) {
            $this->session->set_flashdata('error', 'Pengguna tidak ditemukan.');
            redirect('admin/user');
        }

        $data['user'] = $user;
        $data['page_title'] = 'Detail Pengguna: ' . $user->full_name;
        $data['user_status_list'] = ['active', 'inactive', 'blocked'];
        
        $data['content_view'] = 'admin/user/detail_user'; 
        $this->load->view('admin/templates/admin_templates', $data);
    }
    
    // --- PROSES UPDATE STATUS/ROLE ---
    public function update($user_id)
    {
        // Cek Keamanan: Admin tidak boleh mengedit diri sendiri
        if ($user_id == $this->session->userdata('id')) {
            $this->session->set_flashdata('error', 'Aksi dibatalkan. Anda tidak dapat mengubah peran atau status diri sendiri.');
            redirect('admin/user/detail/' . $user_id);
            return;
        }

        $this->form_validation->set_rules('status', 'Status', 'trim|required|xss_clean');
        $this->form_validation->set_rules('is_admin', 'Peran', 'trim|required|numeric');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
        } else {
            $update_data = [
                'status'    => $this->input->post('status', TRUE),
                'is_admin'  => $this->input->post('is_admin', TRUE),
            ];

            if ($this->User_model->update_user($user_id, $update_data)) {
                $this->session->set_flashdata('success', 'Data pengguna berhasil diperbarui.');
            } else {
                $this->session->set_flashdata('error', 'Gagal memperbarui data pengguna.');
            }
        }
        
        redirect('admin/user/detail/' . $user_id);
    }
}