<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // --- PERBAIKAN KRITIS: Tambahkan 'Cart_model' di sini ---
        $this->load->model(['Product_model', 'Category_model', 'Cart_model']); 
        $this->load->library('session');
    }

   // Path File: application/controllers/Home.php (Di dalam fungsi index())

    // Path File: application/controllers/Home.php (Di dalam fungsi index())

public function index()
{
    $user_id = $this->session->userdata('id');

    $data['page_title'] = 'Belanja Mudah dan Aman - Toko MVP';

    // Data untuk Konten Utama
    $data['top_categories'] = $this->Category_model->get_top_categories(8); // Untuk menu icon
    $data['recommended_products'] = $this->Product_model->get_top_recommended(12); 
    
    // --- TAMBAHAN KRITIS: Ambil SEMUA produk ---
    $data['all_products'] = $this->Product_model->get_all_active_products(); // Panggil method baru
    


    
    // Flag untuk mengaktifkan Hero Banner dan Menu Cepat di header.php
    $data['is_home_page'] = TRUE; // <--- PASTIKAN INI ADA
    
    // Data untuk Konten Utama
    $data['top_categories'] = $this->Category_model->get_top_categories(8); // Untuk menu icon
    $data['recommended_products'] = $this->Product_model->get_top_recommended(12); 
    
    // Data untuk Navbar
    $data['categories'] = $this->Category_model->get_all_categories();
    $data['cart_total_items'] = $this->Cart_model->get_total_items_count($user_id ?? 0);
    
    // Load Template
    $this->load->view('frontend/templates/header', $data); 
    $this->load->view('frontend/home/home_content', $data); 
    $this->load->view('frontend/templates/footer');
}

    // Path File: application/controllers/Home.php (Tambahkan method baru)

// Path File: application/controllers/Home.php (Fungsi search())

// Path File: application/controllers/Home.php (Fungsi search())

public function search()
{
    // Ambil query dari input (metode GET)
    $query = $this->input->get('q', TRUE);
    
    if (empty($query)) {
        // Jika query kosong, redirect ke halaman utama
        redirect('home');
        return;
    }

    // 1. Ambil Data Produk Hasil Pencarian
    // Asumsi: Model mengembalikan produk yang sudah diurutkan berdasarkan relevansi
    $data['products'] = $this->Product_model->search_products($query); 
    
    // 2. Data Pendukung Template
    $data['search_query'] = $query;
    $data['page_title'] = 'Hasil Pencarian: ' . html_escape($query);
    
    // Data untuk Navbar
    $data['categories'] = $this->Category_model->get_all_categories();
    $data['cart_total_items'] = $this->Cart_model->get_total_items_count($this->session->userdata('id') ?? 0);
    
    // 3. Load Template Baru
    $this->load->view('frontend/templates/header', $data);
    $this->load->view('frontend/home/search_results_view', $data); // <-- View Baru
    $this->load->view('frontend/templates/footer');
}

// Path File: application/controllers/Home.php (Tambahkan method baru)

public function category($slug)
{
    // 1. Dapatkan detail kategori berdasarkan slug
    $category_detail = $this->Category_model->get_category_by_slug($slug);
    
    if (!$category_detail) {
        $this->session->set_flashdata('error', 'Kategori tidak ditemukan.');
        redirect('home');
    }

    $category_id = $category_detail->id;
    
    // 2. Ambil produk yang difilter oleh ID kategori
    $data['products'] = $this->Product_model->get_active_products_by_category($category_id); 
    
    // 3. Data Pendukung Template
    $data['page_title'] = 'Katalog: ' . html_escape($category_detail->name);
    $data['current_category'] = $category_detail; // Kirim detail kategori ke view
    
    $data['categories'] = $this->Category_model->get_all_categories(); // Untuk navigasi sidebar
    $data['cart_total_items'] = $this->Cart_model->get_total_items_count($this->session->userdata('id') ?? 0);

    // 4. Load View (Menggunakan view katalog yang sama)
    $this->load->view('frontend/templates/header', $data);
    $this->load->view('frontend/home/catalog_view', $data); // <-- View baru untuk katalog
    $this->load->view('frontend/templates/footer');
}
}