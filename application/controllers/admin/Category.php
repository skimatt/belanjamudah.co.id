<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends Admin_Controller { 

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Category_model');
        $this->load->library('form_validation');
        // Pastikan url helper di-load di autoload.php
    }

    // List Kategori (READ)
    public function index()
    {
        $data['categories'] = $this->Category_model->get_all_categories();
        $data['page_title'] = 'Manajemen Kategori';
        $data['content_view'] = 'admin/category/list_category'; 
        $this->load->view('admin/templates/admin_templates', $data);
    }
    
    // Tambah/Edit Kategori (Form & Process)
    public function form($id = NULL)
    {
        // 1. Persiapan Data Awal
        $data['page_title'] = $id ? 'Edit Kategori' : 'Tambah Kategori Baru';
        $data['categories'] = $this->Category_model->get_all_categories(); 
        $data['category'] = NULL;
        
        if ($id) {
            $data['category'] = $this->Category_model->get_category($id);
            if (!$data['category']) {
                $this->session->set_flashdata('error', 'Kategori tidak ditemukan.');
                redirect('admin/category');
            }
        }

        // 2. Proses Form Submission
        if ($this->input->post()) {
            
            $name = $this->input->post('name', TRUE);
            // Hitung slug DARI NAMA sebelum validasi
            $slug = url_title($name, 'dash', TRUE);
            
            // Simpan slug yang dihitung ke POST untuk divalidasi oleh CI3
            $_POST['slug'] = $slug; 

            // Validasi: Cek keunikan slug. Cek $data['category'] untuk mode EDIT.
            $is_unique = ($id && isset($data['category']) && $data['category']->slug == $slug) ? '' : '|is_unique[categories.slug]';

            // 3. Set Rules Validasi
            $this->form_validation->set_rules('name', 'Nama Kategori', 'trim|required|xss_clean');
            $this->form_validation->set_rules('slug', 'Slug', 'trim|required|alpha_dash' . $is_unique,
                [
                    'is_unique' => 'Slug ini sudah digunakan oleh kategori lain.',
                    'alpha_dash' => 'Slug hanya boleh berisi huruf, angka, dan tanda hubung/garis bawah.'
                ]); 

            if ($this->form_validation->run() == FALSE) {
                // *** DEBUGGING ***
                log_message('error', 'Category Form Validation Failed. Errors: ' . validation_errors());
                // Validasi Gagal: Form akan dimuat ulang dengan error
            } else {
                // Validasi Sukses: Proses Simpan
                $parent_id = $this->input->post('parent_id', TRUE);
                
                $save_data = [
                    'name'          => $name,
                    'slug'          => $slug,
                    'description'   => $this->input->post('description'),
                    'parent_id'     => empty($parent_id) ? NULL : $parent_id 
                ];

                if ($id) {
                    $this->Category_model->update_category($id, $save_data);
                    $this->session->set_flashdata('success', 'Kategori berhasil diperbarui!');
                } else {
                    $this->Category_model->create_category($save_data);
                    $this->session->set_flashdata('success', 'Kategori baru berhasil ditambahkan!');
                }
                redirect('admin/category');
                return;
            }
        }
        
        // 4. Tampilkan View
        $data['content_view'] = 'admin/category/form_category';
        $this->load->view('admin/templates/admin_templates', $data);
    }
    
    // Hapus Kategori
    public function delete($id)
    {
        if ($this->Category_model->delete_category($id)) {
            $this->session->set_flashdata('success', 'Kategori berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus kategori. Pastikan tidak ada produk yang masih terkunci padanya.');
        }
        redirect('admin/category');
    }
}