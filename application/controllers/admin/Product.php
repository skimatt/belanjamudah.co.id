<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Wajib extend Admin_Controller untuk proteksi akses
class Product extends Admin_Controller { 

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['Product_model', 'Category_model', 'Variant_model', 'Image_model']);
        $this->load->library(['pagination', 'upload', 'form_validation']);
        $this->load->helper(array('form', 'url', 'file')); // Load 'file' helper untuk delete
    }

    // Fungsi Index: Menampilkan Daftar Produk
    public function index()
    {
        $products = $this->Product_model->get_all_products();
        $data['products'] = $products;
        $data['page_title'] = 'Manajemen Produk';
        $data['content_view'] = 'admin/product/list_product'; 
        $this->load->view('admin/templates/admin_templates', $data);
    }
    
    // Fungsi Tambah Produk
    public function create()
    {
        $data['page_title'] = 'Tambah Produk Baru';
        $data['categories'] = $this->Category_model->get_all_categories();
        $data['content_view'] = 'admin/product/form_product';
        $this->load->view('admin/templates/admin_templates', $data);
    }

    // --- PROSES SIMPAN PRODUK BARU ---
    public function store()
    {
        // 1. Validasi Input Produk Utama
        $this->form_validation->set_rules('name', 'Nama Produk', 'trim|required|min_length[3]|xss_clean');
        $this->form_validation->set_rules('price', 'Harga Dasar', 'trim|required|numeric|greater_than[0]');
        $this->form_validation->set_rules('description', 'Deskripsi', 'trim|required');
        $this->form_validation->set_rules('category_id', 'Kategori', 'required|numeric');
        $this->form_validation->set_rules('slug', 'Slug', 'trim|required|is_unique[products.slug]|alpha_dash|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            $this->create();
            return;
        }

        $product_data = [
            'name' => $this->input->post('name', TRUE),
            'slug' => url_title($this->input->post('slug', TRUE), 'dash', TRUE),
            'description' => $this->input->post('description'),
            'price' => $this->input->post('price', TRUE),
            'category_id' => $this->input->post('category_id', TRUE),
            'status' => $this->input->post('status', TRUE)
        ];
        
        $this->db->trans_start();
        $product_id = $this->Product_model->create_product($product_data);

        if ($product_id) {
            $upload_paths = $this->_do_upload_images($product_id);
            if (!empty($upload_paths)) {
                $this->Image_model->save_images($product_id, $upload_paths);
            }
            $variants = $this->input->post('variants');
            if (!empty($variants)) {
                $this->Variant_model->save_variants($product_id, $variants);
            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('error', 'Gagal menyimpan produk dan relasi data.');
            redirect('admin/product/create');
        } else {
            $this->session->set_flashdata('success', 'Produk baru berhasil ditambahkan!');
            redirect('admin/product');
        }
    }
    
    // --- TAMPILKAN FORM EDIT PRODUK ---
    public function edit($id)
    {
        $product = $this->Product_model->get_product_detail($id);

        if (!$product) {
            $this->session->set_flashdata('error', 'Produk tidak ditemukan.');
            redirect('admin/product');
        }

        $data['product'] = $product;
        $data['variants'] = $this->Variant_model->get_variants_by_product($id);
        $data['images'] = $this->Image_model->get_images_by_product($id);
        $data['categories'] = $this->Category_model->get_all_categories();

        $data['page_title'] = 'Edit Produk: ' . $product->name;
        $data['content_view'] = 'admin/product/form_product';
        $this->load->view('admin/templates/admin_templates', $data);
    }

    // --- PROSES UPDATE PRODUK ---
    public function update($id)
    {
        $current_product = $this->Product_model->get_product_detail($id);
        if (!$current_product) {
            $this->session->set_flashdata('error', 'Produk tidak ditemukan.');
            redirect('admin/product');
        }

        $is_unique_slug = ($this->input->post('slug') == $current_product->slug) ? '' : '|is_unique[products.slug]';
        
        $this->form_validation->set_rules('name', 'Nama Produk', 'trim|required|min_length[3]|xss_clean');
        $this->form_validation->set_rules('price', 'Harga Dasar', 'trim|required|numeric|greater_than[0]');
        $this->form_validation->set_rules('description', 'Deskripsi', 'trim|required');
        $this->form_validation->set_rules('category_id', 'Kategori', 'required|numeric');
        $this->form_validation->set_rules('slug', 'Slug', 'trim|required|alpha_dash|xss_clean' . $is_unique_slug);

        if ($this->form_validation->run() == FALSE) {
            $this->edit($id);
            return;
        }

        $product_data = [
            'name' => $this->input->post('name', TRUE),
            'slug' => url_title($this->input->post('slug', TRUE), 'dash', TRUE),
            'description' => $this->input->post('description'),
            'price' => $this->input->post('price', TRUE),
            'category_id' => $this->input->post('category_id', TRUE),
            'status' => $this->input->post('status', TRUE),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        $this->db->trans_start();

        // 3. Update Produk Utama
        $this->Product_model->update_product($id, $product_data);
        
        // 4. Update Gambar
        $deleted_images_string = $this->input->post('deleted_images', TRUE);
        if (!empty($deleted_images_string)) {
            $deleted_images = array_filter(explode(',', $deleted_images_string));
            foreach ($deleted_images as $image_id) {
                $image_row = $this->db->get_where('product_images', ['id' => $image_id])->row();
                if ($image_row) {
                    $file_path = FCPATH . $image_row->image_path; 
                    if (file_exists($file_path)) {
                        unlink($file_path);
                    }
                    $this->db->delete('product_images', ['id' => $image_id]);
                }
            }
        }
        $new_upload_paths = $this->_do_upload_images($id);
        if (!empty($new_upload_paths)) {
            $this->Image_model->save_images($id, $new_upload_paths);
        }

        // 5. Update Varian Produk
        $this->Variant_model->delete_by_product($id); // Hapus semua varian lama
        $variants = $this->input->post('variants');
        if (!empty($variants)) {
            $this->Variant_model->save_variants($id, $variants); // Insert semua varian baru
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('error', 'Gagal memperbarui produk dan relasi data.');
            redirect('admin/product/edit/' . $id);
        } else {
            $this->session->set_flashdata('success', 'Produk berhasil diperbarui!');
            redirect('admin/product');
        }
    }

    // --- PROSES HAPUS PRODUK ---
    public function delete($id)
    {
        $product = $this->Product_model->get_product_detail($id);
        if (!$product) {
            $this->session->set_flashdata('error', 'Produk tidak ditemukan.');
            redirect('admin/product');
        }

        $this->db->trans_start();

        // 1. Hapus Folder Upload Fisik
        $upload_path = './uploads/products/' . $id;
        if (is_dir($upload_path)) {
            delete_files($upload_path, TRUE);
            rmdir($upload_path);
        }

        // 2. Hapus Produk dari DB (CASCADE akan menghapus images dan variants terkait)
        $this->Product_model->delete_product($id);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('error', 'Gagal menghapus produk.');
        } else {
            $this->session->set_flashdata('success', 'Produk berhasil dihapus!');
        }
        
        redirect('admin/product');
    }

    // --- PRIVATE METHOD: UPLOAD GAMBAR ---
    private function _do_upload_images($product_id)
    {
        $config['upload_path'] = './uploads/products/' . $product_id . '/';
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size'] = 2048;
        $config['encrypt_name'] = TRUE;

        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, TRUE); 
        }

        $this->upload->initialize($config);

        $uploaded_paths = [];
        $files = $_FILES['images'];

        if (empty($files['name'][0])) {
             return $uploaded_paths;
        }

        $count = count($files['name']);
        for($i=0; $i<$count; $i++)
        {
            $_FILES['image']['name'] = $files['name'][$i];
            $_FILES['image']['type'] = $files['type'][$i];
            $_FILES['image']['tmp_name'] = $files['tmp_name'][$i];
            $_FILES['image']['error'] = $files['error'][$i];
            $_FILES['image']['size'] = $files['size'][$i];
            
            if ($this->upload->do_upload('image')) {
                $data = $this->upload->data();
                $uploaded_paths[] = 'uploads/products/' . $product_id . '/' . $data['file_name'];
            } else {
                log_message('error', 'Product Image Upload Error: ' . $this->upload->display_errors());
            }
        }
        return $uploaded_paths;
    }
}