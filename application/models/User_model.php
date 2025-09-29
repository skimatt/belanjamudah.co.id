<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    // Ambil data user berdasarkan email
    public function get_user_by_email($email)
    {
        $this->db->where('email', $email);
        $query = $this->db->get('users');
        return $query->row();
    }

    // Registrasi/Insert user baru
    public function register_new_user($data)
    {
        // Jika user Google, password berupa hash acak
        return $this->db->insert('users', $data);
    }

    // Update data user (digunakan saat login Google, jika perlu update nama, dll.)
    public function update_user($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('users', $data);
    }

    // Path File: application/models/User_model.php (Tambahkan method baru)

// Mengambil semua pengguna kecuali Admin yang sedang login
public function get_all_users_for_admin()
{
    // Admin tidak perlu melihat diri sendiri di list manajemen user
    $current_admin_id = $this->session->userdata('id'); 
    
    $this->db->select('id, full_name, email, phone_number, is_admin, status, created_at');
    $this->db->where('id !=', $current_admin_id);
    $this->db->order_by('created_at', 'DESC');
    $query = $this->db->get('users');
    return $query->result();
}

// Mendapatkan pengguna berdasarkan ID (digunakan di Controller User/detail)
public function get_user_by_id($id)
{
    $this->db->select('id, full_name, email, phone_number, is_admin, status, created_at');
    return $this->db->get_where('users', ['id' => $id])->row();
}

// Path File: application/models/User_model.php (Tambahkan method baru)

// Mengambil semua alamat user
public function get_user_addresses($user_id)
{
    $this->db->where('user_id', $user_id);
    $this->db->order_by('is_main', 'DESC');
    return $this->db->get('user_addresses')->result();
}

// Mengambil detail alamat berdasarkan ID
public function get_address_by_id($address_id, $user_id = NULL)
{
    $this->db->where('id', $address_id);
    if ($user_id) {
        $this->db->where('user_id', $user_id); // Keamanan: Pastikan user_id match!
    }
    return $this->db->get('user_addresses')->row();
}
// Path File: application/models/User_model.php (Tambahkan method baru)

// --- CRUD ALAMAT ---
public function create_address($data)
{
    // Jika diset sebagai utama, set is_main semua alamat lain menjadi 0
    if ($data['is_main'] == 1) {
        $this->db->update('user_addresses', ['is_main' => 0], ['user_id' => $data['user_id']]);
    }
    return $this->db->insert('user_addresses', $data);
}

public function update_address($address_id, $data)
{
    // Jika diset sebagai utama, set is_main semua alamat lain menjadi 0
    if ($data['is_main'] == 1) {
        $this->db->update('user_addresses', ['is_main' => 0], ['user_id' => $data['user_id']]);
    }
    $this->db->where('id', $address_id);
    $this->db->where('user_id', $data['user_id']); // Keamanan
    return $this->db->update('user_addresses', $data);
}

public function set_main_address($address_id, $user_id)
{
    $this->db->trans_start();
    // 1. Set semua alamat user menjadi non-utama
    $this->db->update('user_addresses', ['is_main' => 0], ['user_id' => $user_id]);
    // 2. Set alamat yang dipilih menjadi utama
    $this->db->update('user_addresses', ['is_main' => 1], ['id' => $address_id, 'user_id' => $user_id]);
    $this->db->trans_complete();
    return $this->db->trans_status();
}

// Path File: application/models/User_model.php

public function get_order_summary($user_id)
{
    $this->db->select('
        COUNT(CASE WHEN payment_status = "pending" THEN 1 END) AS pending_payment_count,
        COUNT(CASE WHEN order_status = "shipped" THEN 1 END) AS shipped_count,
        COUNT(CASE WHEN order_status = "completed" THEN 1 END) AS completed_count,
        COUNT(id) AS total_orders
    ');
    $this->db->where('user_id', $user_id);
    return $this->db->get('orders')->row();
}
public function delete_address($address_id, $user_id)
{
    $this->db->where('id', $address_id);
    $this->db->where('user_id', $user_id); // Keamanan
    return $this->db->delete('user_addresses');
}
}