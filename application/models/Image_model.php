<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Image_model extends CI_Model {

    // Menyimpan banyak path gambar
    public function save_images($product_id, $image_paths)
    {
        $batch_data = [];
        $is_main_set = FALSE;
        foreach ($image_paths as $path) {
            $is_main = FALSE;
            // Jadikan gambar pertama sebagai gambar utama (is_main = 1)
            if (!$is_main_set) {
                $is_main = TRUE;
                $is_main_set = TRUE;
            }
            $batch_data[] = [
                'product_id' => $product_id,
                'image_path' => html_escape($path),
                'is_main' => $is_main ? 1 : 0
            ];
        }

        if (!empty($batch_data)) {
            return $this->db->insert_batch('product_images', $batch_data);
        }
        return FALSE;
    }

    // Mendapatkan semua gambar produk
    public function get_images_by_product($product_id)
    {
        $this->db->order_by('is_main', 'DESC');
        return $this->db->get_where('product_images', ['product_id' => $product_id])->result();
    }
    
    // Menghapus gambar berdasarkan ID produk (Foreign Key CASCADE juga akan membantu)
    public function delete_by_product($product_id)
    {
        return $this->db->delete('product_images', ['product_id' => $product_id]);
    }
}