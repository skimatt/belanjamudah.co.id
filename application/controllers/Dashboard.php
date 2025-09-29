<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // Load helper (form, url) jika belum di-autoload
    }

    public function index()
    {
        // 1. KEAMANAN: Cek apakah pengguna sudah login
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'Silakan login untuk mengakses Dashboard.');
            redirect('auth'); // Arahkan kembali ke Login jika belum login
            return;
        }

        // 2. Cek Role dan Arahkan (Delegasi)
        if ($this->session->userdata('is_admin') == 1) {
            // Pengguna adalah Admin, arahkan ke Admin Panel
            redirect('admin/dashboard'); 
        } else {
            // Pengguna adalah Customer/Pengguna Biasa, arahkan ke Home/Dashboard Customer
            redirect('home'); 
        }
    }
}