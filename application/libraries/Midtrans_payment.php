<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// WAJIB: Memuat library Midtrans dari Composer
require_once FCPATH . 'vendor/autoload.php'; 

use Midtrans\Config;
use Midtrans\Snap;

class Midtrans_payment {

    protected $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
        
        // Load Konfigurasi Midtrans dari file midtrans.php
        $this->CI->config->load('midtrans');
        
        // Set Konfigurasi Midtrans PHP Library
        Config::$serverKey    = $this->CI->config->item('midtrans_server_key');
        Config::$isProduction = $this->CI->config->item('midtrans_is_production');
        Config::$isSanitized  = $this->CI->config->item('midtrans_is_sanitized');
        Config::$is3ds        = $this->CI->config->item('midtrans_is_3ds');
    }

    /**
 * Membuat Transaksi Midtrans Snap
 */
public function create_snap_transaction($order_id, $gross_amount, $order_data, $cart_items)
{
    // WAJIB: Gunakan total_amount akhir untuk gross_amount
    $transaction_details = [
        'order_id'      => $order_id,
        'gross_amount'  => round($gross_amount) 
    ];
    
    $item_details = [];
    
    // 1. MAPPING ITEM PRODUK (FIX KRITIS)
    if (!empty($cart_items)) {
        foreach ($cart_items as $item) {
            $item_details[] = [
                'id'       => $item->product_variant_id,
                // KRITIS: Gunakan price_at_add (Harga Satuan + Modifier)
                'price'    => round($item->price_at_add), 
                'quantity' => $item->quantity,
                'name'     => $item->product_name . ' (' . $item->variant_name . ')'
            ];
        }
    }
    
    // 2. BIAYA PENGIRIMAN
    $item_details[] = [
        'id'       => 'SHIPPING',
        'price'    => round($order_data['shipping_cost']),
        'quantity' => 1,
        'name'     => 'Biaya Kirim (' . $order_data['shipping_courier'] . ')'
    ];
    
    // 3. DISKON VOUCHER
    if (isset($order_data['discount_amount']) && $order_data['discount_amount'] > 0) {
        $item_details[] = [
            'id'       => 'DISCOUNT',
            'price'    => -round($order_data['discount_amount']), // HARUS NEGATIF
            'quantity' => 1,
            'name'     => 'Diskon Voucher (' . ($order_data['voucher_code'] ?? 'N/A') . ')'
        ];
    }

    // Informasi Pelanggan (diasumsikan order_data['address_detail'] memiliki detail alamat)
    $customer_details = [
        'first_name' => $this->CI->session->userdata('full_name'),
        'email'      => $this->CI->session->userdata('email'),
        'phone'      => $order_data['address_detail']->phone_number ?? '081234567890',
        'billing_address' => ['address' => $order_data['shipping_address']]
    ];

    $params = [
        'transaction_details' => $transaction_details,
        'item_details'        => $item_details,
        'customer_details'    => $customer_details,
        'credit_card'         => ['secure' => true]
    ];
    
    $snapResponse = Snap::createTransaction($params);
    return $snapResponse->token;
}
}