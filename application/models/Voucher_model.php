<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Voucher_model extends CI_Model {

    // Path File: application/models/Voucher_model.php

public function get_all_vouchers()
{
    // --- PERBAIKAN DI SINI: Sertakan semua kolom yang dibutuhkan View ---
    $this->db->select('id, code, type, value, min_order_amount, max_usage, usage_count, valid_until, is_active');
    
    $this->db->order_by('id', 'DESC');
    return $this->db->get('vouchers')->result();
}

    public function get_voucher($id)
    {
        return $this->db->get_where('vouchers', array('id' => $id))->row();
    }

    // Path File: application/models/Voucher_model.php

public function create_voucher($data)
{
    // Pastikan $data berisi: code, type, value, valid_from, valid_until, min_order_amount, max_usage, is_active, is_shipping_discount
    return $this->db->insert('vouchers', $data);
}

    public function update_voucher($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('vouchers', $data);
    }

    public function delete_voucher($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('vouchers');
    }


public function validate_voucher($code, $user_id, $subtotal_amount)
{
    $now = date('Y-m-d H:i:s');
    
    // --- PERBAIKAN A: Pastikan semua field kalkulasi diambil ---
    $this->db->select('id, code, type, value, min_order_amount, max_usage, usage_count, valid_from, valid_until, is_active, max_discount_amount');
    
    $this->db->where('code', strtoupper($code));
    $this->db->where('valid_from <=', $now);
    $this->db->where('valid_until >=', $now);
    $this->db->where('is_active', 1);
    
    $voucher = $this->db->get('vouchers')->row();

    if (!$voucher) {
        return (object)['status' => 'error', 'message' => 'Kode voucher tidak valid atau sudah kadaluarsa.'];
    }
    
    // 1. Cek Batas Penggunaan Global (Logika ini tetap benar)
    if ($voucher->usage_count >= $voucher->max_usage) {
        return (object)['status' => 'error', 'message' => 'Voucher sudah mencapai batas maksimal penggunaan.'];
    }
    
    // 2. Cek Minimum Pembelian (Logika ini tetap benar)
    if ($subtotal_amount < $voucher->min_order_amount) {
        return (object)['status' => 'error', 'message' => 'Minimum pembelian untuk voucher ini adalah ' . format_rupiah($voucher->min_order_amount) . '.'];
    }
    
    // 3. Cek Penggunaan per User (Logika ini tetap benar)
    $used_count = $this->db->get_where('used_vouchers', [
        'user_id' => $user_id,
        'voucher_id' => $voucher->id
    ])->num_rows();
    
    if ($used_count > 0) { 
        return (object)['status' => 'error', 'message' => 'Voucher ini sudah pernah Anda gunakan.'];
    }
    
    // 4. Hitung Nilai Diskon
    $discount_value = 0;
    
    if ($voucher->type === 'percent') {
        $discount_value = $subtotal_amount * ($voucher->value / 100);
        
        // --- PERBAIKAN B: Memastikan max_discount_amount tidak NULL ---
        $max_discount = $voucher->max_discount_amount ? $voucher->max_discount_amount : 0;
        
        if ($max_discount > 0 && $discount_value > $max_discount) {
            $discount_value = $max_discount;
        }
        
    } else { // Fixed amount
        $discount_value = $voucher->value;
    }
    
    // Batasi diskon agar tidak melebihi subtotal
    $discount_value = min($discount_value, $subtotal_amount);

    return (object)[
        'status' => 'success',
        'discount_amount' => round($discount_value), // <-- Mengembalikan nilai yang benar
        'voucher_id' => $voucher->id,
        'code' => $voucher->code
    ];
}
/**
 * Mencatat penggunaan voucher oleh user tertentu dan menambah hitungan global.
 * @param int $voucher_id ID voucher
 * @param int $user_id ID pengguna
 * @param int $order_id ID pesanan yang baru dibuat
 * @return bool
 */
public function record_voucher_usage($voucher_id, $user_id, $order_id)
{
    $this->db->trans_start();

    // 1. Tambah hitungan penggunaan global (usage_count)
    $this->db->set('usage_count', 'usage_count + 1', FALSE);
    $this->db->where('id', $voucher_id);
    $this->db->update('vouchers');

    // 2. Catat riwayat penggunaan di tabel used_vouchers
    $used_data = [
        'voucher_id' => $voucher_id,
        'order_id' => $order_id,
        'user_id' => $user_id
    ];
    $this->db->insert('used_vouchers', $used_data);

    $this->db->trans_complete();
    return $this->db->trans_status();
}

// Path File: application/models/Voucher_model.php (Tambahkan method baru)

public function get_usage_history($voucher_id)
{
    $this->db->select('uv.*, o.id as order_id, o.total_amount, u.full_name, o.created_at as order_date');
    $this->db->from('used_vouchers uv');
    $this->db->join('orders o', 'o.id = uv.order_id');
    $this->db->join('users u', 'u.id = uv.user_id');
    $this->db->where('uv.voucher_id', $voucher_id);
    $this->db->order_by('uv.created_at', 'DESC');
    return $this->db->get()->result();
}
}