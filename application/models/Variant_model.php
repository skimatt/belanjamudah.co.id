<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Variant_model extends CI_Model {

    // Menyimpan banyak varian sekaligus (untuk Create/Update Produk)
    public function save_variants($product_id, $variants)
    {
        $batch_data = [];
        foreach ($variants as $v) {
            // Pastikan SKU unik (validasi sebaiknya di Controller juga)
            if (!empty($v['sku'])) {
                $batch_data[] = [
                    'product_id'     => $product_id,
                    'sku'            => html_escape($v['sku']),
                    'name'           => html_escape($v['name']),
                    'stock'          => (int)$v['stock'],
                    'price_modifier' => (float)$v['price_modifier'],
                ];
            }
        }
        if (!empty($batch_data)) {
            return $this->db->insert_batch('product_variants', $batch_data);
        }
        return TRUE;
    }

    // Ambil varian dengan harga hasil perhitungan
    public function get_variants_by_product($product_id)
    {
        $this->db->select('
            pv.*, 
            (pv.price_modifier + p.price) AS calculated_price 
        ');
        $this->db->from('product_variants pv');
        $this->db->join('products p', 'p.id = pv.product_id');
        $this->db->where('pv.product_id', $product_id);
        return $this->db->get()->result();
    }

    /**
     * Hapus semua varian berdasarkan ID Produk
     * Pendekatan sederhana untuk update: hapus semua, lalu insert baru.
     */
    public function delete_by_product($product_id)
    {
        // Menggunakan Query Builder CI3 untuk menghapus data berdasarkan product_id
        return $this->db->delete('product_variants', ['product_id' => $product_id]);
    }

    /**
     * Mengambil detail varian dan memverifikasi stok secara atomik.
     * Digunakan khusus untuk alur "Beli Sekarang".
     *
     * @param int $variant_id ID varian produk.
     * @param int $qty Kuantitas yang diminta.
     * @return object|null Objek varian jika stok mencukupi, atau null jika tidak.
     */
    public function get_variant_detail_for_buy_now($variant_id, $qty)
    {
        // PENTING: Gunakan kueri SQL manual untuk "FOR UPDATE"
        $sql = "
            SELECT v.*, p.id as product_id, p.name as product_name, p.price
            FROM product_variants v
            JOIN products p ON v.product_id = p.id
            WHERE v.id = ? AND v.stock >= ? FOR UPDATE
        ";
        
        $query = $this->db->query($sql, [$variant_id, $qty]);

        if ($query->num_rows() > 0) {
            return $query->row();
        }

        return null;
    }

    /**
     * Mengurangi stok varian setelah order berhasil.
     *
     * @param int $variant_id ID varian produk.
     * @param int $qty Kuantitas yang dipesan.
     * @return bool
     */
    public function decrement_stock($variant_id, $qty)
    {
        $this->db->set('stock', 'stock - ' . (int)$qty, FALSE);
        $this->db->where('id', $variant_id);
        $this->db->where('stock >=', (int)$qty); // Pastikan stok tidak menjadi negatif
        $this->db->update('product_variants');
        return $this->db->affected_rows() > 0;
    }

    public function get_variant_detail($variant_id)
    {
        // Mendapatkan detail varian, termasuk harga dasar dari produk terkait
        $this->db->select('pv.*, p.price, p.id as product_id');
        $this->db->from('product_variants pv');
        $this->db->join('products p', 'p.id = pv.product_id');
        $this->db->where('pv.id', $variant_id);
        return $this->db->get()->row();
    }
}