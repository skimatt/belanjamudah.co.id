<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category_model extends CI_Model {

    // Mendapatkan semua kategori, diurutkan berdasarkan nama
    public function get_all_categories()
    {
        // Mendapatkan semua field dan mengurutkan
        $this->db->order_by('name', 'ASC');
        $query = $this->db->get('categories');
        return $query->result();
    }

    // Mendapatkan kategori tunggal berdasarkan ID
    public function get_category($id)
    {
        return $this->db->get_where('categories', array('id' => $id))->row();
    }
    
    // Membuat kategori baru
    public function create_category($data)
    {
        return $this->db->insert('categories', $data);
    }

    // Memperbarui kategori
    public function update_category($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('categories', $data);
    }
    
    // Menghapus kategori
    public function delete_category($id)
    {
        // Jika ada produk terkait, mereka akan di-SET NULL (sesuai skema FOREIGN KEY CASCADE/SET NULL)
        $this->db->where('id', $id);
        return $this->db->delete('categories');
    }

    public function get_top_categories($limit = 4)
{
    // Mengambil N kategori utama untuk ditampilkan di banner/pilihan
    $this->db->select('name, slug');
    $this->db->limit($limit);
    // Asumsi: Kita hanya ambil kategori utama (parent_id IS NULL)
    $this->db->where('parent_id IS NULL'); 
    $this->db->order_by('name', 'ASC'); 
    return $this->db->get('categories')->result();
}

// Path File: application/models/Category_model.php

public function get_category_by_slug($slug)
{
    // Cari kategori berdasarkan slug
    return $this->db->get_where('categories', ['slug' => $slug])->row();
}
}