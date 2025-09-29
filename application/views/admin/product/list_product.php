<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Katalog /</span> Manajemen Produk
</h4>

<?php if ($this->session->flashdata('success')): ?>
<div class="alert alert-success alert-dismissible" role="alert"><?= $this->session->flashdata('success'); ?><button
    type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>
<?php endif; ?>

<div class="card">
  <h5 class="card-header d-flex justify-content-between align-items-center">
    Daftar Semua Produk
    <a href="<?= site_url('admin/product/create'); ?>" class="btn btn-primary">

      <i class="ti ti-plus me-1"></i> Tambah Produk
    </a>
  </h5>

  <!-- Tombol Search -->
  <button type="button" class="btn btn-primary mb-3" id="btnSearchFocus">
    <i class="ti ti-search me-1"></i> Cari Produk
  </button>


  <div class="table-responsive text-nowrap">
    <table class="table table-hover">
      <thead>
        <tr>
          <th>#ID</th>
          <th>Gambar</th>
          <th>Nama Produk</th>
          <th>Kategori</th>
          <th>Harga Dasar</th>
          <th>Stok Total</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        <?php if (!empty($products)): ?>
        <?php foreach ($products as $product): ?>
        <tr>
          <td>#<?= $product->id; ?></td>
          <td>
            <?php if (!empty($product->main_image_path)): ?>
            <img src="<?= base_url($product->main_image_path); ?>" alt="<?= html_escape($product->name); ?>"
              style="width: 50px; height: 50px; object-fit: cover;" class="rounded">
            <?php else: ?>
            <i class="ti ti-photo text-muted" title="Tidak Ada Gambar"></i>
            <?php endif; ?>
          </td>
          <td><strong><?= html_escape($product->name); ?></strong></td>
          <td><?= html_escape($product->category_name); ?></td>
          <td><?= 'Rp ' . number_format($product->price, 0, ',', '.'); ?></td>
          <td>
            <?php 
                                    $stock = (int)$product->total_stock;
                                    $stock_class = ($stock <= 5 && $stock > 0) ? 'text-warning' : ($stock == 0 ? 'text-danger' : 'text-success');
                                ?>
            <strong class="<?= $stock_class; ?>">
              <?= $stock > 0 ? $stock : 'HABIS'; ?>
            </strong>
          </td>
          <td>
            <?php 
                                    $badge_class = ($product->status == 'active') ? 'bg-label-success' : (($product->status == 'draft') ? 'bg-label-warning' : 'bg-label-secondary');
                                ?>
            <span class="badge <?= $badge_class; ?> me-1"><?= ucfirst($product->status); ?></span>
          </td>
          <td>
            <div class="dropdown">
              <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i
                  class="ti ti-dots-vertical"></i></button>
              <div class="dropdown-menu">
                <a class="dropdown-item" href="<?= site_url('admin/product/edit/' . $product->id); ?>"><i
                    class="ti ti-pencil me-1"></i> Edit</a>
                <a class="dropdown-item" href="javascript:void(0);" onclick="confirmDelete(<?= $product->id; ?>)"><i
                    class="ti ti-trash me-1"></i> Hapus</a>
              </div>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php else: ?>
        <tr>
          <td colspan="8" class="text-center">Belum ada data produk. Silakan tambahkan produk baru.</td>
        </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
function confirmDelete(productId) {
  if (confirm("Apakah Anda yakin ingin menghapus produk ini? Aksi ini tidak dapat dibatalkan.")) {
    window.location.href = "<?= site_url('admin/product/delete/'); ?>" + productId;
  }
}

// --- Tombol Search: fokus ke kolom Nama Produk --- 
document.getElementById('btnSearchFocus').addEventListener('click', function() {
  const nameInput = document.getElementById('name');

  // Scroll halus ke posisi input
  nameInput.scrollIntoView({
    behavior: 'smooth',
    block: 'center'
  });

  // Setelah animasi scroll selesai, set fokus
  setTimeout(() => nameInput.focus(), 400); // 400 ms ≈ durasi scroll
});
</script>