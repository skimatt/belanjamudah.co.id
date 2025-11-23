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
    $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');

    if ($this->form_validation->run() == FALSE) {
        $this->load->view('auth/forgot_password_view');

    } else {

        $email = $this->input->post('email', TRUE);
        $user  = $this->User_model->get_user_by_email($email);

        if ($user) {

            // Generate token + expiry
            $token  = bin2hex(random_bytes(32));
            $expiry = date('Y-m-d H:i:s', time() + (60 * 30)); // 30 menit

            // Save token
            $this->User_model->update_user($user->id, [
                'reset_token'  => $token,
                'token_expiry' => $expiry
            ]);

            // Reset link
            $reset_link = site_url('auth/reset_password/' . $token);

            // SUBJECT
            $subject = 'Reset Password - BelanjaMudah.co.id';

            // MESSAGE (HTML PREMIUM)
            $message = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reset Password - BelanjaMudah.co.id</title>
</head>

<body style="margin:0; padding:0; background:#f3f6fb; font-family:Arial, sans-serif;">

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background:#f3f6fb; padding:20px 0;">
    <tr>
        <td align="center">

            <!-- WRAPPER -->
            <table role="presentation" width="600" cellspacing="0" cellpadding="0" border="0" 
                   style="background:white; border-radius:12px; overflow:hidden; box-shadow:0 4px 20px rgba(0,0,0,0.08);">

                <!-- HEADER -->
                <tr>
                    <td style="background:#1e40af; padding:25px 20px; text-align:center;">
                        <h1 style="margin:0; font-size:26px; color:white; letter-spacing:0.5px;">
                            BelanjaMudah.co.id
                        </h1>
                        <p style="margin:5px 0 0; font-size:13px; color:#c7d2fe;">
                            Kemudahan Belanja Dalam Genggaman
                        </p>
                    </td>
                </tr>

                <!-- BODY -->
                <tr>
                    <td style="padding:30px 35px; color:#111827; font-size:15px; line-height:1.7;">

                        <p>Halo <strong>' . $user->full_name . '</strong>,</p>

                        <p>
                            Kami menerima permintaan untuk mereset password akun Anda di 
                            <strong>BelanjaMudah.co.id</strong>.
                        </p>

                        <p>
                            Silakan klik tombol di bawah ini untuk membuat password baru.<br>
                            <small>(Link hanya berlaku <strong>30 menit</strong>)</small>
                        </p>

                        <!-- BUTTON -->
                        <div style="text-align:center; margin:30px 0;">
                            <a href="' . $reset_link . '" 
                               style="background:#2563eb; color:white; padding:14px 30px; 
                                      text-decoration:none; font-size:15px; font-weight:bold;
                                      border-radius:8px; display:inline-block;">
                                Reset Password
                            </a>
                        </div>

                        <p>Atau gunakan link berikut:</p>
                        <p style="word-break:break-all; color:#2563eb;">
                            <a href="' . $reset_link . '" style="color:#2563eb;">' . $reset_link . '</a>
                        </p>

                        <p style="margin-top:25px;">
                            Jika Anda tidak meminta reset password, abaikan email ini. 
                            Akun Anda akan tetap aman.
                        </p>

                        <p style="margin-top:30px; color:#6b7280; font-size:13px;">
                            Hormat kami,<br>
                            <strong>Tim Support BelanjaMudah.co.id</strong>
                        </p>

                    </td>
                </tr>

                <!-- FOOTER -->
                <tr>
                    <td style="background:#f1f5f9; padding:15px; text-align:center; color:#6b7280; font-size:12px;">
                        © ' . date("Y") . ' BelanjaMudah.co.id — Semua Hak Dilindungi
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>

</body>
</html>
            ';

            // Load Email Config
            $this->load->config('email');
            $this->load->library('email');

            $this->email->from('rahmatzkk10@gmail.com', 'BelanjaMudah.co.id Support');
            $this->email->to($user->email);
            $this->email->subject($subject);
            $this->email->message($message);

            // Send Email
            if ($this->email->send()) {

                $this->session->set_flashdata('success', 'Link reset password telah dikirim ke email Anda.');

            } else {
                $dbg = $this->email->print_debugger();
                $this->session->set_flashdata('error', 'Gagal mengirim email reset.');
                log_message('error', 'EMAIL DEBUGGER: ' . $dbg);
            }

        } else {
            $this->session->set_flashdata(
                'success',
                'Jika email terdaftar, link reset akan dikirim.'
            );
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