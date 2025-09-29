<?php
// ... (Bagian atas)

class Dashboard extends Admin_Controller { 
    // ...
    public function index()
    {
        $data['page_title'] = 'Ringkasan Dashboard';
        $data['content_view'] = 'admin/dashboard_content'; 
        
        // Memuat template master admin_templates.php
        $this->load->view('admin/templates/admin_templates', $data);
    }
}