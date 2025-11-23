<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_model extends CI_Model {

    // Helper untuk menghitung Sold Count (jumlah terjual)
    private function _get_sold_count_subquery()
    {
        // Menggunakan subquery untuk menghitung total kuantitas dari order_items
        return "(SELECT SUM(oi.quantity) FROM order_items oi 
                 JOIN product_variants pv ON pv.id = oi.product_variant_id 
                 WHERE pv.product_id = p.id) AS sold_count";
    }

    // =======================================================
    // MARKAS: FUNGSI ADMIN (CRUD)
    // =======================================================

    /**
     * Fungsi untuk mendapatkan daftar produk (dengan JOIN ke kategori dan menghitung Total Stok).
     * Dipanggil oleh Admin/Product/index.
     */
    public function get_all_products()
    {
        $this->db->select("
            p.id, 
            p.name, 
            p.price, 
            p.status, 
            p.slug,
            c.name as category_name,
            SUM(pv.stock) as total_stock,
            (SELECT pi.image_path FROM product_images pi WHERE pi.product_id = p.id AND pi.is_main = 1 LIMIT 1) as main_image_path,
            {$this->_get_sold_count_subquery()}
        ", FALSE); // FALSE untuk subquery sold_count
        
        $this->db->from('products p');
        $this->db->join('categories c', 'c.id = p.category_id', 'left');
        $this->db->join('product_variants pv', 'pv.product_id = p.id', 'left');
        
        $this->db->group_by('p.id'); 
        $this->db->order_by('p.created_at', 'DESC');
        return $this->db->get()->result();
    }

    // Fungsi untuk mendapatkan detail produk tunggal
    public function get_product_detail($id)
    {
        return $this->db->get_where('products', array('id' => $id))->row();
    }

    // Fungsi untuk membuat produk baru
    public function create_product($data)
    {
        $this->db->insert('products', $data);
        return $this->db->insert_id(); 
    }

    // Fungsi untuk update produk
    public function update_product($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('products', $data);
    }
    
    // Fungsi untuk menghapus produk
    public function delete_product($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('products');
    }
    
    // =======================================================
    // MARKAS: FUNGSI FRONTEND (CATALOG & DETAIL)
    // =======================================================

    /**
     * Mengambil semua produk dengan status 'active' untuk frontend.
     */
    public function get_all_active_products()
    {
        $this->db->select("
            p.id, 
            p.name, 
            p.slug,
            p.price, 
            c.name as category_name,
            (SELECT pi.image_path FROM product_images pi WHERE pi.product_id = p.id AND pi.is_main = 1 LIMIT 1) as main_image_path,
            {$this->_get_sold_count_subquery()} /* <-- Tambahkan Sold Count */
        ", FALSE);
        
        $this->db->from('products p');
        $this->db->join('categories c', 'c.id = p.category_id', 'left');
        $this->db->where('p.status', 'active');
        $this->db->group_by('p.id'); // Wajib Group By
        $this->db->order_by('p.created_at', 'DESC');
        return $this->db->get()->result();
    }

    /**
     * Mendapatkan detail produk untuk halaman produk tunggal.
     */
    public function get_product_for_detail($slug)
    {
        $this->db->select("
            p.*, 
            c.name as category_name,
            c.slug as category_slug, 
            p.price as product_base_price, 
            (SELECT SUM(pv.stock) FROM product_variants pv WHERE pv.product_id = p.id) as stock_sum,
            {$this->_get_sold_count_subquery()} /* <-- Tambahkan Sold Count */
        ", FALSE);
        
        $this->db->from('products p');
        $this->db->join('categories c', 'c.id = p.category_id', 'left');
        $this->db->where('p.slug', $slug);
        $this->db->where('p.status', 'active');
        $this->db->group_by('p.id'); // Wajib Group By
        return $this->db->get()->row();
    }

    /**
     * Mengambil produk rekomendasi (terbaru) untuk Home Page.
     */
    public function get_top_recommended($limit = 12)
    {
        $this->db->select("
            p.name, p.slug, p.price, 
            (SELECT pi.image_path FROM product_images pi WHERE pi.product_id = p.id AND pi.is_main = 1 LIMIT 1) as main_image_path,
            {$this->_get_sold_count_subquery()} /* <-- Tambahkan Sold Count */
        ", FALSE);
        
        $this->db->from('products p');
        $this->db->where('p.status', 'active');
        $this->db->order_by('p.created_at', 'DESC');
        $this->db->limit($limit);
        $this->db->group_by('p.id'); // Wajib Group By
        return $this->db->get()->result();
    }

    /**
     * Mengambil produk serupa.
     */
    public function get_similar_products($category_id, $current_product_id, $limit = 6)
    {
        $this->db->select("
            p.name, p.slug, p.price, 
            (SELECT pi.image_path FROM product_images pi WHERE pi.product_id = p.id AND pi.is_main = 1 LIMIT 1) as main_image_path,
            {$this->_get_sold_count_subquery()} /* <-- Tambahkan Sold Count */
        ", FALSE);
        
        $this->db->from('products p');
        $this->db->where('p.status', 'active');
        $this->db->where('p.category_id', $category_id); 
        $this->db->where('p.id !=', $current_product_id); 
        $this->db->order_by('RAND()'); 
        $this->db->limit($limit);
        $this->db->group_by('p.id'); // Wajib Group By
        
        return $this->db->get()->result();
    }

    /**
     * Mencari produk aktif dan memberikan prioritas (skor) berdasarkan relevansi.
     */
    public function search_products($query)
    {
        $clean_query = $this->db->escape_like_str($query); 
        
        // Logika Scoring
        $this->db->select("
            p.id, 
            p.name, 
            p.slug,
            p.price, 
            (SELECT pi.image_path FROM product_images pi WHERE pi.product_id = p.id AND pi.is_main = 1 LIMIT 1) as main_image_path,
            {$this->_get_sold_count_subquery()}, /* <-- Tambahkan Sold Count */
            (
                CASE WHEN p.name LIKE '%{$clean_query}%' THEN 30 ELSE 0 END + 
                CASE WHEN p.description LIKE '%{$clean_query}%' THEN 10 ELSE 0 END
            ) AS relevance_score
        ", FALSE);
        
        $this->db->from('products p');
        $this->db->where('p.status', 'active');
        
        $this->db->group_start();
        $this->db->like('p.name', $query);
        $this->db->or_like('p.description', $query);
        $this->db->group_end();
        
        $this->db->group_by('p.id'); // Wajib Group By
        $this->db->order_by('relevance_score', 'DESC'); 
        $this->db->order_by('p.name', 'ASC'); 
        
        return $this->db->get()->result();
    }

    /**
     * Mengambil produk aktif berdasarkan ID kategori.
     */
    public function get_active_products_by_category($category_id)
    {
        $this->db->select("
            p.id, 
            p.name, 
            p.slug,
            p.price, 
            (SELECT pi.image_path FROM product_images pi WHERE pi.product_id = p.id AND pi.is_main = 1 LIMIT 1) as main_image_path,
            {$this->_get_sold_count_subquery()} /* <-- Tambahkan Sold Count */
        ", FALSE);
        
        $this->db->from('products p');
        $this->db->where('p.status', 'active');
        $this->db->where('p.category_id', $category_id); 
        $this->db->order_by('p.name', 'ASC');
        $this->db->group_by('p.id'); // Wajib Group By
        
        return $this->db->get()->result();
    }
    // Path File: application/models/Product_model.php

public function get_low_stock_count($threshold = 10)
{
    // Hitung varian di mana stok kurang dari threshold
    $this->db->select('COUNT(id) as low_count');
    $this->db->where('stock <=', $threshold);
    $this->db->where('stock >', 0);
    return $this->db->get('product_variants')->row()->low_count ?? 0;
}
}