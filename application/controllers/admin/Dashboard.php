<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Admin_Controller { 

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['Product_model', 'Order_model', 'User_model']);
        $this->load->helper('toko'); // Untuk format_rupiah
    }

    public function index()
    {
        $end_date = date('Y-m-d');
        $start_date = date('Y-m-d', strtotime('-30 days')); // Ambil data 30 hari terakhir

        // 1. Ambil Data Ringkasan (Total Pendapatan, Orders)
        $data['summary'] = $this->Order_model->get_revenue_summary($start_date, $end_date);
        
       // 2. Ambil Data Transaksi Terbaru (Limit 3)
    // Asumsi Order_model::get_all_orders(limit) mendukung pengambilan limit
    $data['recent_orders'] = $this->Order_model->get_all_orders(3); // <<< KRITIS: Batasi hanya 3

    // 3. Data untuk Line Chart (Harian)
    $end_date = date('Y-m-d');
    $start_date = date('Y-m-d', strtotime('-30 days'));
    $daily_data = $this->Order_model->get_daily_transactions($start_date, $end_date);
    
    $chart_labels = [];
    $chart_values = [];
    foreach ($daily_data as $day) {
        $chart_labels[] = date('d M', strtotime($day->date));
        $chart_values[] = $day->daily_revenue;
    }
    $data['chart_labels'] = json_encode($chart_labels);
    $data['chart_values'] = json_encode($chart_values);
    
    // ... (Final Load tetap sama) ...
    $data['page_title'] = 'Dashboard Utama';
    $data['content_view'] = 'admin/dashboard_content';
    $this->load->view('admin/templates/admin_templates', $data);
}
}