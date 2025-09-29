<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends Admin_Controller { 

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Order_model'); // Gunakan Order_model untuk laporan
        $this->load->library('form_validation');
        $this->load->helper('date'); // Helper untuk format tanggal
    }

    public function index()
    {
        $data['page_title'] = 'Laporan Keuangan Dasar';
        $data['content_view'] = 'admin/report/financial_report';

        // 1. Tentukan Rentang Waktu Default (Contoh: 30 hari terakhir)
        $end_date = date('Y-m-d');
        $start_date = date('Y-m-d', strtotime('-30 days'));

        // 2. Ambil Input Filter dari POST
        if ($this->input->post('filter', TRUE)) {
            $start_date_input = $this->input->post('start_date', TRUE);
            $end_date_input = $this->input->post('end_date', TRUE);

            if (!empty($start_date_input) && !empty($end_date_input)) {
                $start_date = $start_date_input;
                $end_date = $end_date_input;
            }
        }
        
        // 3. Ambil Data dari Model
        $data['summary'] = $this->Order_model->get_revenue_summary($start_date, $end_date);
        $data['daily_data'] = $this->Order_model->get_daily_transactions($start_date, $end_date);

        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;

        // 4. Proses data harian untuk Grafik (Jika ingin menggunakan Chart.js/ApexCharts)
        $chart_labels = [];
        $chart_values = [];
        foreach ($data['daily_data'] as $day) {
            $chart_labels[] = date('d M', strtotime($day->date));
            $chart_values[] = $day->daily_revenue;
        }
        $data['chart_labels'] = json_encode($chart_labels);
        $data['chart_values'] = json_encode($chart_values);


        $this->load->view('admin/templates/admin_templates', $data);
    }
    
    // Path File: application/controllers/admin/Report.php (Ganti fungsi export)

// ... (Kode construct dan index tetap sama) ...

    // Fungsi Export CSV/Excel
    public function export()
    {
        // 1. Ambil Parameter Tanggal dari GET request
        $start_date = $this->input->get('start', TRUE);
        $end_date = $this->input->get('end', TRUE);

        if (empty($start_date) || empty($end_date)) {
            $this->session->set_flashdata('error', 'Rentang tanggal tidak valid untuk export.');
            redirect('admin/report');
            return;
        }

        // 2. Ambil Data Detail Pesanan (Hanya yang Paid)
        $orders = $this->Order_model->get_detailed_orders_for_report($start_date, $end_date);
        
        if (empty($orders)) {
            $this->session->set_flashdata('error', 'Tidak ada data pesanan Paid dalam rentang waktu tersebut.');
            redirect('admin/report');
            return;
        }

        // 3. Persiapkan Header dan Output CSV
        $filename = 'Laporan_Penjualan_' . $start_date . '_sd_' . $end_date . '.csv';

        // Set Headers untuk memaksa download file
        header('Content-Description: File Transfer');
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        $output = fopen('php://output', 'w');

        // Header CSV (Menggunakan titik koma (;) sebagai delimiter untuk kompatibilitas Excel Indonesia)
        $header = [
            'ID Pesanan', 'Tanggal Pesanan', 'Status Pesanan', 'Status Pembayaran', 
            'Nama Pelanggan', 'Email Pelanggan', 'Total Jumlah (Rp)', 'Biaya Kirim (Rp)', 
            'Metode Pembayaran', 'Nomor Resi', 'Alamat Pengiriman Lengkap'
        ];
        fputcsv($output, $header, ';');

        // Data Baris CSV
        foreach ($orders as $o) {
            $row = [
                $o->id,
                $o->created_at,
                strtoupper($o->order_status),
                strtoupper($o->payment_status),
                $o->full_name,
                $o->email,
                number_format($o->total_amount, 0, ',', '.'), // Format Rupiah (tanpa Rp)
                number_format($o->shipping_cost, 0, ',', '.'),
                $o->payment_method,
                $o->tracking_number,
                str_replace(["\r", "\n"], ", ", $o->shipping_address) // Bersihkan alamat dari enter
            ];
            fputcsv($output, $row, ';');
        }

        fclose($output);
        exit; // Penting: Hentikan eksekusi setelah output CSV
    }
}