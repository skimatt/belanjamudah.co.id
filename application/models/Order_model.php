<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order_model extends CI_Model {
    
    // Status Order yang valid sesuai skema ENUM di DB
    public $status_list = [
        'pending', 
        'paid', 
        'packing', 
        'shipped', 
        'delivered', 
        'completed', 
        'cancelled'
    ];

    // =======================================================
    // MARKAS: FUNGSI FRONTEND (TRANSAKSIONAL)
    // =======================================================

    /**
     * Metode untuk membuat pesanan dan item pesanan dari keranjang.
     * Asumsi: Transaksi START/COMPLETE dilakukan di Controller Checkout.php.
     * @param array $order_data Data pesanan utama.
     * @param object $cart_data Objek keranjang dengan item.
     * @return int|bool ID pesanan yang baru dibuat atau FALSE jika gagal.
     */
    public function create_order_from_cart($order_data, $cart_data)
    {
        // 1. Masukkan data pesanan utama ke tabel 'orders'
        $this->db->insert('orders', $order_data);
        $order_id = $this->db->insert_id();

        if (!$order_id) {
            return FALSE; 
        }

        // 2. Siapkan data item untuk 'order_items'
        $batch_items = [];
        
        foreach ($cart_data->items as $item) {
            // Menggunakan properti yang disinkronkan dari Cart_model JOIN
            $batch_items[] = [
                'order_id'             => $order_id,
                'product_variant_id'   => $item->product_variant_id, 
                'product_name'         => $item->product_name, 
                'product_variant_name' => $item->variant_name, 
                'quantity'             => $item->quantity,
                'unit_price'           => $item->price_at_add,
                'total_price'          => $item->quantity * $item->price_at_add
            ];
            // Catatan: Pengurangan stok ditangani di Cart_model atau Controller.
        }

        // 3. Masukkan data item ke tabel 'order_items'
        $this->db->insert_batch('order_items', $batch_items);
        
        return $order_id;
    }

    // =======================================================
    // MARKAS: FUNGSI USER (FRONTEND LIST & DETAIL)
    // =======================================================

    /**
     * Mengambil semua pesanan milik user tertentu (Untuk Histori Pesanan).
     */
    public function get_orders_by_user($user_id, $limit = 0)
    {
        // PERBAIKAN: Memastikan semua kolom penting untuk Histori/ETD terambil
        $this->db->select('
            id, 
            created_at, 
            total_amount, 
            order_status, 
            payment_status,
            shipping_courier,       
            shipping_cost           
        ');
        $this->db->where('user_id', $user_id);
        $this->db->order_by('created_at', 'DESC');
        
        if ($limit > 0) {
            $this->db->limit($limit);
        }
        
        return $this->db->get('orders')->result();
    }

    /**
     * Mengambil detail pesanan tunggal, difilter oleh user_id (Keamanan).
     * Dipanggil oleh Order/invoice dan Payment/process_gateway.
     */
    public function get_order_detail_by_user($order_id, $user_id)
    {
        $this->db->select('*');
        $this->db->from('orders');
        $this->db->where('id', $order_id);
        $this->db->where('user_id', $user_id); // Filter keamanan wajib
        return $this->db->get()->row();
    }
    
    // Mengambil item-item dalam pesanan
    public function get_order_items($order_id)
    {
        $this->db->where('order_id', $order_id);
        return $this->db->get('order_items')->result();
    }
    
    // Fungsi untuk memperbarui status pembayaran (dipanggil oleh Payment Gateway Callback)
    public function update_payment_status($order_id, $new_status, $user_id = NULL)
    {
        $data = [
            'payment_status' => $new_status,
            'updated_at'     => date('Y-m-d H:i:s')
        ];
        
        $this->db->where('id', $order_id);
        if ($user_id) $this->db->where('user_id', $user_id); // Keamanan
        return $this->db->update('orders', $data);
    }

    // =======================================================
    // MARKAS: FUNGSI ADMIN (BACKEND)
    // =======================================================

/**
 * Mengambil daftar pesanan (untuk List Admin) dengan JOIN data pelanggan.
 */
public function get_all_orders($limit = 0)
{
    // PERBAIKAN KRITIS: Tambahkan u.email dan u.full_name ke SELECT
    $this->db->select('o.*, u.full_name, u.email'); 
    $this->db->from('orders o');
    // Wajib ada JOIN ke tabel users
    $this->db->join('users u', 'u.id = o.user_id', 'left'); 
    $this->db->order_by('o.created_at', 'DESC');
    
    if ($limit > 0) {
        $this->db->limit($limit);
    }
    
    $query = $this->db->get();
    return $query->result();
}

    // Fungsi untuk memperbarui status pesanan (dipanggil oleh Admin)
    public function update_order_status($order_id, $new_status, $tracking_number = NULL)
    {
        if (!in_array($new_status, $this->status_list)) {
            log_message('error', 'Attempted to set invalid order status: ' . $new_status);
            return FALSE;
        }

        $data = [
            'order_status' => $new_status,
            'updated_at'   => date('Y-m-d H:i:s')
        ];

        if ($new_status == 'shipped' && !empty($tracking_number)) {
            $data['tracking_number'] = $tracking_number;
        }
        
        $this->db->where('id', $order_id);
        return $this->db->update('orders', $data);
    }

    // --- FUNGSI UNTUK PELAPORAN ---

    /**
     * Mengambil ringkasan total pendapatan berdasarkan rentang waktu dan status Paid.
     * @param string $start_date format 'Y-m-d'
     * @param string $end_date format 'Y-m-d'
     * @return object
     */
    public function get_revenue_summary($start_date, $end_date)
    {
        // Hanya hitung pesanan dengan status PAID atau COMPLETED
        $this->db->select('
            COUNT(id) as total_orders,
            SUM(total_amount) as total_revenue,
            SUM(CASE WHEN order_status = "completed" THEN total_amount ELSE 0 END) as completed_revenue
        ');
        $this->db->where('payment_status', 'paid');
        $this->db->where('created_at >=', $start_date . ' 00:00:00');
        $this->db->where('created_at <=', $end_date . ' 23:59:59');
        
        return $this->db->get('orders')->row();
    }

    /**
     * Mengambil detail transaksi per hari dalam rentang waktu tertentu.
     * @param string $start_date
     * @param string $end_date
     * @return array
     */
    public function get_daily_transactions($start_date, $end_date)
    {
        // Grouping berdasarkan tanggal untuk laporan harian
        $this->db->select('
            DATE(created_at) as date,
            COUNT(id) as orders_count,
            SUM(total_amount) as daily_revenue
        ');
        $this->db->where('payment_status', 'paid');
        $this->db->where('created_at >=', $start_date . ' 00:00:00');
        $this->db->where('created_at <=', $end_date . ' 23:59:59');
        $this->db->group_by('DATE(created_at)');
        $this->db->order_by('date', 'ASC');

        return $this->db->get('orders')->result();
    }

    /**
     * Mengambil detail pesanan yang sudah dibayar (Paid) untuk kebutuhan export laporan.
     */
    public function get_detailed_orders_for_report($start_date, $end_date)
    {
        $this->db->select('
            o.id, 
            o.created_at, 
            o.order_status, 
            o.payment_status,
            o.shipping_cost,
            o.total_amount,
            o.payment_method,
            o.tracking_number,
            o.shipping_address,
            u.full_name, 
            u.email
        ');
        $this->db->from('orders o');
        $this->db->join('users u', 'u.id = o.user_id');
        // Hanya ambil pesanan yang sudah dibayar
        $this->db->where('o.payment_status', 'paid'); 
        $this->db->where('o.created_at >=', $start_date . ' 00:00:00');
        $this->db->where('o.created_at <=', $end_date . ' 23:59:59');
        $this->db->order_by('o.created_at', 'ASC');

        return $this->db->get()->result();
    }

    // Path File: application/models/Order_model.php (Harus ada di dalam class Order_model)

// Mengambil detail pesanan tunggal (Dipanggil Admin)
public function get_order_detail($order_id)
{
    $this->db->select('o.*, u.full_name, u.email');
    $this->db->from('orders o');
    $this->db->join('users u', 'u.id = o.user_id');
    $this->db->where('o.id', $order_id);
    return $this->db->get()->row();
}


}