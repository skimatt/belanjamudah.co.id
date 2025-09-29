// Tambahkan definisi variabel awal
<?php
$is_edit = isset($product);
$action_url = $is_edit ? 'admin/product/update/' . $product->id : 'admin/product/store';

// Default value
$product_name = $is_edit ? $product->name : set_value('name');
$product_slug = $is_edit ? $product->slug : set_value('slug');
$product_price = $is_edit ? $product->price : set_value('price');
$product_desc = $is_edit ? $product->description : set_value('description');
$product_cat_id = $is_edit ? $product->category_id : set_value('category_id');
?>

<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Produk /</span> <?= $page_title; ?>
</h4>

<div class="card">
  <h5 class="card-header">Detail Produk</h5>
  <div class="card-body">

    <?= form_open_multipart($action_url, 'id="formProduct"'); ?>
    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>"
      value="<?= $this->security->get_csrf_hash(); ?>">

    <div class="row g-3 mb-4">
      <div class="col-md-6">
        <label class="form-label" for="name">Nama Produk <span class="text-danger">*</span></label>
        <input type="text" id="name" name="name" class="form-control" value="<?= $product_name; ?>" required />
        <?= form_error('name', '<div class="text-danger mt-1 text-xs">', '</div>'); ?>
      </div>
      <div class="col-md-6">
        <label class="form-label" for="slug">Slug (URL) <span class="text-danger">*</span></label>
        <input type="text" id="slug" name="slug" class="form-control" value="<?= $product_slug; ?>" required />
        <?= form_error('slug', '<div class="text-danger mt-1 text-xs">', '</div>'); ?>
      </div>
      <div class="col-md-4">
        <label class="form-label" for="price">Harga Dasar (Rp) <span class="text-danger">*</span></label>
        <input type="number" id="price" name="price" class="form-control" value="<?= $product_price; ?>" step="0.01"
          min="0" required />
        <?= form_error('price', '<div class="text-danger mt-1 text-xs">', '</div>'); ?>
      </div>
      <div class="col-md-4">
        <label class="form-label" for="category_id">Kategori <span class="text-danger">*</span></label>
        <select id="category_id" name="category_id" class="form-select" required>
          <option value="">Pilih Kategori</option>
          <?php foreach ($categories as $cat): ?>
          <option value="<?= $cat->id; ?>"
            <?= ($product_cat_id == $cat->id) ? 'selected' : set_select('category_id', $cat->id); ?>>
            <?= html_escape($cat->name); ?></option>
          <?php endforeach; ?>
        </select>
        <?= form_error('category_id', '<div class="text-danger mt-1 text-xs">', '</div>'); ?>
      </div>
      <div class="col-md-4">
        <label class="form-label" for="status">Status</label>
        <select id="status" name="status" class="form-select">
          <option value="active"
            <?= ($is_edit && $product->status == 'active') ? 'selected' : set_select('status', 'active', TRUE); ?>>
            Active</option>
          <option value="draft"
            <?= ($is_edit && $product->status == 'draft') ? 'selected' : set_select('status', 'draft'); ?>>Draft
          </option>
          <option value="inactive"
            <?= ($is_edit && $product->status == 'inactive') ? 'selected' : set_select('status', 'inactive'); ?>>
            Inactive</option>
        </select>
      </div>
      <div class="col-md-12">
        <label class="form-label" for="description">Deskripsi Produk <span class="text-danger">*</span></label>
        <textarea id="description" name="description" class="form-control" rows="5"><?= $product_desc; ?></textarea>
        <?= form_error('description', '<div class="text-danger mt-1 text-xs">', '</div>'); ?>
      </div>
    </div>

    <hr class="my-4">

    <h5 class="mb-3">Varian Produk (Ukuran/Warna)</h5>
    <div id="variants-container" class="space-y-3 mb-4">
      <?php if ($is_edit && !empty($variants)): ?>
      <?php $v_index = 0; ?>
      <?php foreach ($variants as $variant): ?>
      <div class="row g-3 variant-row border p-3 rounded-md">
        <input type="hidden" name="variants[<?= $v_index; ?>][id]" value="<?= $variant->id; ?>">
        <div class="col-md-4">
          <label class="form-label">Nama Varian</label>
          <input type="text" name="variants[<?= $v_index; ?>][name]" class="form-control"
            value="<?= html_escape($variant->name); ?>" />
        </div>
        <div class="col-md-3">
          <label class="form-label">SKU</label>
          <input type="text" name="variants[<?= $v_index; ?>][sku]" class="form-control"
            value="<?= html_escape($variant->sku); ?>" />
        </div>
        <div class="col-md-2">
          <label class="form-label">Stok</label>
          <input type="number" name="variants[<?= $v_index; ?>][stock]" class="form-control"
            value="<?= $variant->stock; ?>" min="0" />
        </div>
        <div class="col-md-2">
          <label class="form-label">Modifier Harga (+/-)</label>
          <input type="number" name="variants[<?= $v_index; ?>][price_modifier]" class="form-control"
            value="<?= $variant->price_modifier; ?>" step="0.01" />
        </div>
        <div class="col-md-1 d-flex align-items-end">
          <button type="button" class="btn btn-danger btn-icon remove-variant"><i class="ti ti-trash"></i></button>
        </div>
      </div>
      <?php $v_index++; ?>
      <?php endforeach; ?>
      <?php else: ?>
      <div class="row g-3 variant-row border p-3 rounded-md">
      </div>
      <?php endif; ?>
    </div>
    <button type="button" id="add-variant" class="btn btn-secondary btn-sm mb-4">
      <i class="ti ti-plus me-1"></i> Tambah Varian
    </button>

    <hr class="my-4">

    <h5 class="mb-3">Gambar Produk (Max 2MB per file)</h5>
    <?php if ($is_edit && !empty($images)): ?>
    <div class="row mb-3" id="current-images-container">
      <input type="hidden" id="deleted-images-input" name="deleted_images" value="" />
      <?php foreach ($images as $img): ?>
      <div class="col-md-2 col-sm-3 mb-2 image-item-<?= $img->id; ?>">
        <img src="<?= base_url($img->image_path); ?>" class="img-fluid rounded border mb-1" alt="Product Image">
        <button type="button" class="btn btn-xs btn-danger w-100 delete-old-image"
          data-id="<?= $img->id; ?>">Hapus</button>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <div class="mb-4">
      <input class="form-control" type="file" id="images" name="images[]" multiple accept="image/*" />
      <small class="text-muted">Pilih gambar baru. Gambar yang sudah ada dapat dihapus di atas.</small>
    </div>


    <button type="submit" class="btn btn-success me-2">Simpan Perubahan</button>
    <a href="<?= site_url('admin/product'); ?>" class="btn btn-label-secondary">Kembali</a>

    <?= form_close(); ?>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  let variantIndex = <?= $is_edit && !empty($variants) ? count($variants) : 1; ?>;

  // Logika Hapus Gambar Lama
  document.querySelectorAll('.delete-old-image').forEach(button => {
    button.addEventListener('click', function() {
      const imageId = this.dataset.id;
      if (confirm('Yakin ingin menghapus gambar ini secara permanen?')) {

        // 1. Ambil input tersembunyi
        const deletedInput = document.getElementById('deleted-images-input');

        // 2. Tambahkan ID ke string yang dipisahkan koma
        // Jika kosong, langsung isi. Jika ada, tambahkan koma dan ID.
        if (deletedInput.value === "") {
          deletedInput.value = imageId;
        } else {
          deletedInput.value = deletedInput.value + ',' + imageId;
        }

        // 3. Sembunyikan dari tampilan
        document.querySelector('.image-item-' + imageId).remove();

        alert('Gambar akan dihapus setelah Anda mengklik "Simpan Perubahan".');
      }
    });
  });
  // Logika Tambah Varian (sama seperti sebelumnya, hanya update variantIndex awal)
  document.getElementById('add-variant').addEventListener('click', function() {
    const container = document.getElementById('variants-container');
    const newRow = document.createElement('div');
    // ... (HTML Varian Baru sama seperti sebelumnya, menggunakan variantIndex)
    newRow.className = 'row g-3 variant-row border p-3 rounded-md mt-2';
    newRow.innerHTML = `
                <div class="col-md-4"><label class="form-label">Nama Varian</label><input type="text" name="variants[${variantIndex}][name]" class="form-control" /></div>
                <div class="col-md-3"><label class="form-label">SKU</label><input type="text" name="variants[${variantIndex}][sku]" class="form-control" /></div>
                <div class="col-md-2"><label class="form-label">Stok</label><input type="number" name="variants[${variantIndex}][stock]" class="form-control" value="0" min="0" /></div>
                <div class="col-md-2"><label class="form-label">Modifier Harga (+/-)</label><input type="number" name="variants[${variantIndex}][price_modifier]" class="form-control" value="0" step="0.01" /></div>
                <div class="col-md-1 d-flex align-items-end"><button type="button" class="btn btn-danger btn-icon remove-variant"><i class="ti ti-trash"></i></button></div>
            `;
    container.appendChild(newRow);
    variantIndex++;
  });

  // Event listener untuk menghapus baris varian (sama)
  document.getElementById('variants-container').addEventListener('click', function(e) {
    if (e.target.closest('.remove-variant')) {
      if (document.querySelectorAll('.variant-row').length > 1) {
        e.target.closest('.variant-row').remove();
      } else {
        alert('Minimal harus ada satu varian produk.');
      }
    }
  });
});
</script>