<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cart_model extends CI_Model {

    // --- Ambil atau buat keranjang ---
    private function _get_or_create_cart($user_id)
    {
        $cart = $this->db->get_where('carts', ['user_id' => $user_id])->row();
        
        if (!$cart) {
            $this->db->insert('carts', [
                'user_id' => $user_id,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            $cart_id = $this->db->insert_id();
            return $this->db->get_where('carts', ['id' => $cart_id])->row();
        }
        return $cart;
    }

    // --- Tambah/Update Item ---
    public function add_update_item($user_id, $variant, $qty)
    {
        $cart = $this->_get_or_create_cart($user_id);
        $product = $this->Product_model->get_product_detail($variant->product_id);

        $existing_item = $this->db->get_where('cart_items', [
            'cart_id' => $cart->id,
            'product_variant_id' => $variant->id
        ])->row();
        
        $price_at_add = $product->price + $variant->price_modifier;

        if ($existing_item) {
            $new_qty = $existing_item->quantity + $qty;
            if ($new_qty > $variant->stock) {
                return FALSE;
            }
            
            $this->db->where('id', $existing_item->id);
            $update = $this->db->update('cart_items', [
                'quantity' => $new_qty,
                'price_at_add' => $price_at_add 
            ]);
        } else {
            $insert_data = [
                'cart_id' => $cart->id,
                'product_variant_id' => $variant->id,
                'quantity' => $qty,
                'price_at_add' => $price_at_add
            ];
            $update = $this->db->insert('cart_items', $insert_data);
        }
        
        $this->db->update('carts', ['updated_at' => date('Y-m-d H:i:s')], ['id' => $cart->id]);

        return $update;
    }

    // --- Ambil keranjang user ---
    public function get_user_cart_data()
    {
        $user_id = $this->session->userdata('id');
        if (!$user_id) {
            return (object)['items' => [], 'total' => 0];
        }

        $cart = $this->_get_or_create_cart($user_id);

        $this->db->select('ci.*, pv.sku, pv.name as variant_name, pv.stock as current_stock, 
                           p.name as product_name, p.slug as product_slug, 
                           (SELECT pi.image_path FROM product_images pi WHERE pi.product_id = p.id 
                            AND pi.is_main = 1 LIMIT 1) as image_path');
        $this->db->from('cart_items ci');
        $this->db->join('product_variants pv', 'pv.id = ci.product_variant_id');
        $this->db->join('products p', 'p.id = pv.product_id');
        $this->db->where('ci.cart_id', $cart->id);
        
        $items = $this->db->get()->result();

        $total_amount = 0;
        foreach ($items as $item) {
            $item->subtotal = $item->quantity * $item->price_at_add;
            $total_amount += $item->subtotal;
        }

        return (object)['items' => $items, 'total' => $total_amount];
    }

    // --- Hapus item ---
    public function remove_item($item_id, $user_id)
    {
        $cart = $this->db->get_where('carts', ['user_id' => $user_id])->row();
        if (!$cart) return FALSE;

        return $this->db->delete('cart_items', ['id' => $item_id, 'cart_id' => $cart->id]);
    }

    // --- Total item di navbar ---
    public function get_total_items_count($user_id)
    {
        $cart = $this->db->get_where('carts', ['user_id' => $user_id])->row();
        if (!$cart) return 0;
        
        $this->db->select_sum('quantity');
        $this->db->where('cart_id', $cart->id);
        $query = $this->db->get('cart_items')->row();
        
        return $query->quantity ?: 0;
    }

    // --- Kosongkan keranjang ---
    public function clear_user_cart($user_id)
    {
        $cart = $this->db->get_where('carts', ['user_id' => $user_id])->row();
        if ($cart) {
            return $this->db->delete('cart_items', ['cart_id' => $cart->id]);
        }
        return FALSE;
    }

    // --- Update kuantitas item ---
    public function update_item_quantity($item_id, $user_id, $new_qty)
    {
        $cart = $this->db->get_where('carts', ['user_id' => $user_id])->row();
        if (!$cart) {
            return ['status' => 'error', 'message' => 'Keranjang tidak ditemukan.'];
        }
        
        $this->db->select('ci.*, pv.stock');
        $this->db->from('cart_items ci');
        $this->db->join('product_variants pv', 'pv.id = ci.product_variant_id');
        $this->db->where('ci.id', $item_id);
        $item = $this->db->get()->row();
        
        if (!$item || $item->cart_id != $cart->id) {
            return ['status' => 'error', 'message' => 'Item tidak valid.'];
        }

        if ($new_qty > $item->stock) {
            return ['status' => 'error', 'message' => 'Stok hanya tersedia ' . $item->stock . ' unit.'];
        }

        $this->db->where('id', $item_id);
        $this->db->update('cart_items', ['quantity' => $new_qty]);

        $updated_cart = $this->get_user_cart_data();

        // Ambil kembali item yang diupdate
        $updated_item = array_filter($updated_cart->items, function($i) use ($item_id) {
            return $i->id == $item_id;
        });

        $updated_item = reset($updated_item); // ambil objek pertama

        return [
            'status' => 'success',
            'item_subtotal' => $updated_item->subtotal ?? 0,
            'grand_total' => $updated_cart->total,
        ];
    }

    // Path File: application/models/Cart_model.php (Tambahkan method baru)

/**
 * Menghitung total berat semua item di keranjang user yang sedang aktif.
 * Berat diambil dari tabel products.weight.
 * @param int $user_id
 * @return int Total berat dalam gram
 */
public function get_total_weight($user_id)
{
    $cart = $this->db->get_where('carts', ['user_id' => $user_id])->row();
    if (!$cart) {
        return 0;
    }

    $this->db->select('SUM(ci.quantity * p.weight) AS total_weight');
    $this->db->from('cart_items ci');
    $this->db->join('product_variants pv', 'pv.id = ci.product_variant_id');
    $this->db->join('products p', 'p.id = pv.product_id'); // Join ke products untuk mengambil weight
    $this->db->where('ci.cart_id', $cart->id);
    
    $query = $this->db->get()->row();
    
    return (int) ($query->total_weight ?? 0);
}

// Path File: application/models/Cart_model.php

/**
 * Mengurangi stok varian berdasarkan item keranjang.
 * Dipanggil di Controller Checkout, di dalam transaksi.
 * @param array $cart_items Daftar objek item keranjang.
 * @return bool
 */
public function decrement_inventory($cart_items)
{
    $success = TRUE;
    
    foreach ($cart_items as $item) {
        // PERBAIKAN KRITIS: Kurangi stok varian
        $this->db->set('stock', 'stock - ' . (int)$item->quantity, FALSE);
        $this->db->where('id', $item->product_variant_id); // Filter berdasarkan ID Varian
        
        // Memastikan stok tidak menjadi negatif (Mencegah over-selling)
        $this->db->where('stock >=', (int)$item->quantity); 
        
        $this->db->update('product_variants');

        if ($this->db->affected_rows() !== 1) {
            // Jika baris yang terpengaruh bukan 1 (gagal update karena stok 0), 
            // set success = FALSE. Ini akan memicu rollback di Controller.
            $success = FALSE;
            // Catat log error jika diperlukan
            break; 
        }
    }
    return $success;
}
}