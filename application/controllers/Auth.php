<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        // Load Helper & Library eksplisit jika belum di-autoload (Baik untuk kejelasan)
        $this->load->helper(['form', 'url']); 
        $this->load->library('form_validation'); 
        
        // Load Autoloader Composer (Google Client Library)
        require_once FCPATH . 'vendor/autoload.php';
    }
    
    // --- PRIVATE: INISIALISASI GOOGLE CLIENT ---
    private function _init_google_client()
    {
        $client = new Google_Client();
        $client->setClientId($this->config->item('google_client_id'));
        $client->setClientSecret($this->config->item('google_client_secret'));
        $client->setRedirectUri($this->config->item('google_redirect_uri'));
        $client->setScopes($this->config->item('google_scopes'));
        $client->setAccessType('online');
        return $client;
    }

    // --- VIEW LOGIN (Landing page Auth) ---
    public function index()
    {
        // Jika sudah login, redirect ke Dashboard Universal
        if ($this->session->userdata('logged_in')) {
            redirect('dashboard'); 
        }
        $data['google_login_url'] = $this->_init_google_client()->createAuthUrl();
        $this->load->view('auth/login_view', $data);
    }

    // --- PROSES LOGIN STANDARD ---
    public function process_login()
    {
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
        $this->form_validation->set_rules('password', 'Password', 'required|xss_clean');
        
        if ($this->form_validation->run() == FALSE) {
            $this->index(); 
            return;
        }

        $email = $this->input->post('email', TRUE);
        $password = $this->input->post('password', TRUE);
        $user = $this->User_model->get_user_by_email($email);

        if ($user && $user->password !== NULL && $user->status === 'active') {
            if (password_verify($password, $user->password)) {
                $sess_array = array(
                    'id'        => $user->id,
                    'email'     => $user->email,
                    'full_name' => $user->full_name,
                    'is_admin'  => $user->is_admin,
                    'logged_in' => TRUE 
                );
                $this->session->set_userdata($sess_array);

                // REDIRECT UNIVERSAL
                redirect('dashboard'); 
            }
        }
        
        $this->session->set_flashdata('error', 'Email atau Password salah atau akun tidak aktif.');
        redirect('auth');
    }

    // --- VIEW REGISTRASI & PROSES ---
    public function register()
    {
        $this->form_validation->set_rules('full_name', 'Nama Lengkap', 'trim|required|min_length[3]|xss_clean');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[users.email]|xss_clean');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[8]');
        $this->form_validation->set_rules('passconf', 'Konfirmasi Password', 'required|matches[password]');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('auth/register_view');
        } else {
            $data = array(
                'full_name' => $this->input->post('full_name', TRUE),
                'email' => $this->input->post('email', TRUE),
                'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                'is_admin' => 0,
                'status' => 'active'
            );
            
            if ($this->User_model->register_new_user($data)) {
                $this->session->set_flashdata('success', 'Pendaftaran berhasil. Silakan Login.');
                redirect('auth');
            } else {
                $this->session->set_flashdata('error', 'Pendaftaran gagal.');
                redirect('auth/register');
            }
        }
    }
    
    // --- GOOGLE CALLBACK HANDLER ---
    public function google_callback()
    {
        $client = $this->_init_google_client();
        
        if (isset($_GET['code'])) {
            try {
                // ... (Logika otentikasi Google) ...
                $client->authenticate($_GET['code']);
                $client->setAccessToken($client->getAccessToken());

                $google_service = new Google_Service_Oauth2($client);
                $google_user = $google_service->userinfo->get();
                
                $email = $google_user->email;
                $name = $google_user->name;

                $user = $this->User_model->get_user_by_email($email);

                // Cek User Lama/Baru dan Update Status
                if ($user) {
                    if ($user->status !== 'active') {
                        $this->session->set_flashdata('error', 'Akun diblokir.');
                        redirect('auth');
                        return;
                    }
                    $user_id = $user->id;
                    $is_admin = $user->is_admin;
                } else {
                    // Registrasi Otomatis
                    $data = ['full_name' => $name, 'email' => $email, 'password' => NULL, 'is_admin' => 0, 'status' => 'active'];
                    $this->User_model->register_new_user($data);
                    $user_id = $this->db->insert_id();
                    $is_admin = 0;
                }

                // Buat Session
                $sess_array = ['id' => $user_id, 'email' => $email, 'full_name' => $name, 'is_admin' => $is_admin, 'logged_in' => TRUE];
                $this->session->set_userdata($sess_array);

                // REDIRECT UNIVERSAL
                redirect('dashboard');

            } catch (Exception $e) {
                log_message('error', 'Google OAuth Error: ' . $e->getMessage()); 
                $this->session->set_flashdata('error', 'Gagal login dengan Google. Silakan coba lagi.');
                redirect('auth');
            }
        } else {
            $this->session->set_flashdata('error', 'Login Google dibatalkan.');
            redirect('auth');
        }
    }
    
    // --- LUPA PASSWORD (Form Input Email) ---
    public function forgot_password()
    {
        // ... (Logika forgot_password tetap sama) ...
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('auth/forgot_password_view'); // Tampilkan form
        } else {
            $email = $this->input->post('email', TRUE);
            $user = $this->User_model->get_user_by_email($email);

            if ($user) {
                $token = bin2hex(random_bytes(32)); 
                $expiry = date('Y-m-d H:i:s', time() + (60 * 30)); 
                $this->User_model->update_user($user->id, ['reset_token' => $token, 'token_expiry' => $expiry]);
                
                $reset_link = site_url('auth/reset_password/' . $token);
                $subject = 'Permintaan Reset Password Toko MVP';
                $message = 'Halo ' . $user->full_name . ',<br><br> Silakan klik link berikut (berlaku 30 menit):<br>'
                         . '<a href="' . $reset_link . '">RESET PASSWORD</a><br><br>'
                         . 'Jika Anda tidak meminta ini, abaikan email ini.';

                $this->load->library('email');
                $this->email->from($this->config->item('smtp_user'), 'Toko MVP Support');
                $this->email->to($user->email);
                $this->email->subject($subject);
                $this->email->message($message);

                if ($this->email->send()) {
                    $this->session->set_flashdata('success', 'Link reset password telah dikirim ke email Anda.');
                } else {
                    $this->session->set_flashdata('error', 'Gagal mengirim email reset: ' . $this->email->print_debugger());
                    log_message('error', 'Email Debugger: ' . $this->email->print_debugger());
                }
            } else {
                $this->session->set_flashdata('success', 'Jika email terdaftar, link reset akan dikirim.'); 
            }
            redirect('auth');
        }
    }

    // --- RESET PASSWORD (Token Validation & Update) ---
    public function reset_password($token = NULL)
    {
        // ... (Logika reset_password tetap sama) ...
        if (!$token) {
            redirect('auth');
        }
    
        $user = $this->db->get_where('users', ['reset_token' => $token])->row();
    
        if (!$user || $user->token_expiry < date('Y-m-d H:i:s')) {
            $this->session->set_flashdata('error', 'Token tidak valid atau sudah kadaluarsa.');
            redirect('auth');
        }
        
        $this->form_validation->set_rules('password', 'Password Baru', 'required|min_length[8]');
        $this->form_validation->set_rules('passconf', 'Konfirmasi Password', 'required|matches[password]');
    
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('auth/reset_password_view', ['token' => $token]);
        } else {
            $new_password = $this->input->post('password');
            
            $update_data = [
                'password' => password_hash($new_password, PASSWORD_DEFAULT),
                'reset_token' => NULL,
                'token_expiry' => NULL
            ];
            
            $this->User_model->update_user($user->id, $update_data);
            
            $this->session->set_flashdata('success', 'Password Anda berhasil direset. Silakan login.');
            redirect('auth');
        }
    }

    // --- LOGOUT ---
    public function logout()
    {
        $this->session->sess_destroy();
        redirect('auth');
    }
}