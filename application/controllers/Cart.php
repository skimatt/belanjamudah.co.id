<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cart extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['Cart_model', 'Product_model', 'Variant_model']);
        $this->load->library('form_validation');
        $this->load->helper('toko'); // Wajib ada helper format_rupiah
    }

    // Index: Menampilkan Isi Keranjang
    public function index()
    {
        $data['cart_data'] = $this->Cart_model->get_user_cart_data(); 
        $data['page_title'] = 'Keranjang Belanja Anda';
        
        $user_id = $this->session->userdata('id');
        $data['cart_total_items'] = $user_id ? $this->Cart_model->get_total_items_count($user_id) : 0; 

        $this->load->view('frontend/templates/header', $data);
        $this->load->view('frontend/cart/cart_view', $data);
        $this->load->view('frontend/templates/footer'); 
    }

    // --- PROSES AJAX: TAMBAH KE KERANJANG ---
    public function add_to_cart()
    {
        if (!$this->input->is_ajax_request()) {
            redirect('home');
        }

        $this->form_validation->set_rules('variant_id', 'Varian Produk', 'required|integer');
        $this->form_validation->set_rules('qty', 'Kuantitas', 'required|integer|greater_than[0]');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode([
                'status' => 'error',
                'message' => validation_errors(),
                'csrf_hash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $user_id = $this->session->userdata('id');
        $variant_id = $this->input->post('variant_id', TRUE);
        $qty = $this->input->post('qty', TRUE);
        $redirect_to_checkout = $this->input->post('redirect_to_checkout', TRUE);

        // 1. Cek Login
        if (!$user_id) {
            echo json_encode([
                'status' => 'redirect',
                'message' => 'Anda harus login untuk menyimpan keranjang.',
                'url' => site_url('auth'),
                'csrf_hash' => $this->security->get_csrf_hash()
            ]);
            return;
        }
        
        // 2. Cek Stok & Varian
        $variant = $this->Variant_model->get_variant_detail($variant_id);
        if (!$variant || $variant->stock < $qty) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Stok tidak mencukupi untuk varian ini. Stok tersedia: ' . ($variant->stock ?? 0),
                'csrf_hash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        // 3. Simpan/Update Item di DB
        if ($this->Cart_model->add_update_item($user_id, $variant, $qty)) {
            $response = [
                'status' => 'success',
                'message' => 'Produk berhasil ditambahkan ke keranjang!',
                'total_items' => $this->Cart_model->get_total_items_count($user_id),
                'csrf_hash' => $this->security->get_csrf_hash()
            ];
            
            // Jika request datang dari tombol 'Beli Sekarang'
            if ($redirect_to_checkout) {
                $response['redirect_url'] = site_url('checkout');
            }
            
            echo json_encode($response);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Gagal menyimpan item ke database.',
                'csrf_hash' => $this->security->get_csrf_hash()
            ]);
        }
    }

    public function remove_item()
{
    if (!$this->input->is_ajax_request() || !$this->session->userdata('logged_in')) {
        exit('Akses ditolak.');
    }

    $this->form_validation->set_rules('item_id', 'ID Item', 'required|integer');

    if ($this->form_validation->run() == FALSE) {
        echo json_encode([
            'status' => 'error',
            'message' => validation_errors(),
            'csrf_hash' => $this->security->get_csrf_hash()
        ]);
        return;
    }

    $item_id = $this->input->post('item_id', TRUE);

    if ($this->Cart_model->remove_item($item_id, $this->session->userdata('id'))) {
        $cart_data = $this->Cart_model->get_user_cart_data();
        echo json_encode([
            'status' => 'success',
            'message' => 'Item berhasil dihapus.',
            'total_items' => $cart_data->total_items ?? 0,
            'grand_total' => $cart_data->total_amount ?? 0,
            'csrf_hash' => $this->security->get_csrf_hash()
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Gagal menghapus item atau item tidak ditemukan.',
            'csrf_hash' => $this->security->get_csrf_hash()
        ]);
    }
}

    
public function update_qty()
{
    $item_id = $this->input->post('item_id');
    $qty     = (int) $this->input->post('qty');
    $user_id = $this->session->userdata('id');

    if (!$item_id || !$qty || !$user_id) {
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => 'error',
                'message' => 'Data tidak valid',
                'csrf_hash' => $this->security->get_csrf_hash()
            ]));
    }

    // Gunakan model yang sudah benar
    $update = $this->Cart_model->update_item_quantity($item_id, $user_id, $qty);

    if ($update['status'] === 'success') {
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => 'success',
                'item_subtotal' => $update['item_subtotal'],
                'grand_total'   => $update['grand_total'],
                'csrf_hash'     => $this->security->get_csrf_hash()
            ]));
    } else {
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => 'error',
                'message' => $update['message'],
                'csrf_hash' => $this->security->get_csrf_hash()
            ]));
    }
}



}