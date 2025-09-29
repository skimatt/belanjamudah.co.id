<?php
if ( ! function_exists('format_rupiah'))
{
    function format_rupiah($angka, $prefix = 'Rp ')
    {
        // Pastikan angka adalah numerik
        if (!is_numeric($angka)) return $prefix . '0';
        return $prefix . number_format($angka, 0, ',', '.');
    }
}

if ( ! function_exists('order_status_badge'))
{
    /**
     * Menghasilkan HTML badge (Bootstrap) untuk status pesanan.
     */
    function order_status_badge($status)
    {
        $status = strtolower($status);
        $badge_class = 'bg-secondary';
        
        if ($status == 'pending') $badge_class = 'bg-warning';
        else if (in_array($status, ['paid', 'packing'])) $badge_class = 'bg-primary';
        else if ($status == 'shipped') $badge_class = 'bg-info';
        else if ($status == 'delivered' || $status == 'completed') $badge_class = 'bg-success';
        else if ($status == 'cancelled') $badge_class = 'bg-danger';

        return '<span class="badge ' . $badge_class . ' text-uppercase">' . $status . '</span>';
    }
}

if ( ! function_exists('payment_status_badge'))
{
    /**
     * Menghasilkan HTML badge (Bootstrap) untuk status pembayaran.
     */
    function payment_status_badge($status)
    {
        $status = strtolower($status);
        if ($status == 'paid') return '<span class="badge bg-success text-uppercase">' . $status . '</span>';
        if ($status == 'pending') return '<span class="badge bg-warning text-uppercase">' . $status . '</span>';
        return '<span class="badge bg-danger text-uppercase">' . $status . '</span>';
    }
}

// Path File: application/helpers/toko_helper.php (Tambahkan function ini)

if ( ! function_exists('calculate_etd'))
{
    /**
     * Menghitung estimasi tanggal tiba berdasarkan layanan dan order_status.
     */
    function calculate_etd($shipping_courier, $order_status)
    {
        // Jika sudah delivered atau completed, tidak perlu ETD
        if (in_array($order_status, ['delivered', 'completed'])) {
            return 'Sudah Diterima';
        }
        
        // Asumsi: Waktu proses admin (packing/paid) = 1 hari
        $base_timestamp = strtotime('+1 day'); 
        
        // Tentukan hari pengiriman berdasarkan layanan
        $shipping_days = 3; // Default Reguler

        if (stripos($shipping_courier, 'Express') !== FALSE) {
            $shipping_days = 2;
        } elseif (stripos($shipping_courier, 'Same Day') !== FALSE) {
            $shipping_days = 1;
        }

        $etd_timestamp = strtotime("+$shipping_days days", $base_timestamp);
        
        return date('d M', $etd_timestamp) . ' - ' . date('d M Y', strtotime('+1 day', $etd_timestamp));
    }
}