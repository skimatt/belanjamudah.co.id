<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['Product_model', 'Image_model', 'Variant_model', 'Cart_model']);
        $this->load->library('session');
        // Pastikan helper toko dimuat untuk format_rupiah
        $this->load->helper('toko');
    }

    // Fungsi Utama: Menampilkan Detail Produk berdasarkan Slug
    public function detail($slug)
    {
        // 1. Ambil Data Utama
        $product = $this->Product_model->get_product_for_detail($slug);
        
        if (!$product) {
            show_404(); 
            return;
        }

        // 2. Persiapan Data untuk View
        $data['product'] = $product;
        $data['images'] = $this->Image_model->get_images_by_product($product->id);
        $data['variants'] = $this->Variant_model->get_variants_by_product($product->id);
        $data['page_title'] = $product->name;
        
        // 3. Logika Produk Serupa
        $data['similar_products'] = $this->Product_model->get_similar_products($product->category_id, $product->id, 6);

        // 4. Data Pendukung Template
        $user_id = $this->session->userdata('id');
        $data['cart_total_items'] = $this->Cart_model->get_total_items_count($user_id ?? 0);
        $data['is_logged_in'] = (bool)$user_id;

        // --- LOAD TEMPLATE ---
        $this->load->view('frontend/templates/header', $data); 
        $this->load->view('frontend/product/detail_view', $data); 
        $this->load->view('frontend/templates/footer'); 
    }
}