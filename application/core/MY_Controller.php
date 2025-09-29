<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
    // Base controller
}

// Controller Khusus untuk Admin (Hanya yang is_admin=1 yang bisa akses)
class Admin_Controller extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        // KEAMANAN VITAL: Cek Login dan Role
        if (!$this->session->userdata('logged_in') || $this->session->userdata('is_admin') != 1) {
            
            // Hancurkan session yang mencoba akses ilegal
            $this->session->sess_destroy();
            $this->session->set_flashdata('error', 'Akses ditolak! Anda harus login sebagai Admin.');
            redirect('auth'); 
        }
    }
}