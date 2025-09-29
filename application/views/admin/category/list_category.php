<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Katalog /</span> Manajemen Kategori
</h4>

<?php if ($this->session->flashdata('success')): ?>
<div class="alert alert-success alert-dismissible" role="alert"><?= $this->session->flashdata('success'); ?><button
    type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
<div class="alert alert-danger alert-dismissible" role="alert"><?= $this->session->flashdata('error'); ?><button
    type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>
<?php endif; ?>

<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="card-title mb-0">Daftar Kategori Produk</h5>
    <a href="<?= site_url('admin/category/form'); ?>" class="btn btn-primary">
      <i class="ti ti-plus me-1"></i> Tambah Kategori
    </a>
  </div>

  <div class="table-responsive text-nowrap">
    <table class="table table-hover table-striped">
      <thead>
        <tr>
          <th style="width: 5%;">#ID</th>
          <th style="width: 30%;">Nama Kategori</th>
          <th style="width: 25%;">Slug</th>
          <th style="width: 25%;">Kategori Induk</th>
          <th style="width: 15%;">Aksi</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        <?php 
                    // Konversi list menjadi map untuk lookup nama parent yang cepat
                    $category_map = array_column($categories, 'name', 'id');
                    if (!empty($categories)):
                    foreach ($categories as $cat): 
                ?>
        <tr>
          <td><?= $cat->id; ?></td>
          <td><i class="ti ti-folder me-2 text-info"></i> <strong><?= html_escape($cat->name); ?></strong></td>
          <td><span class="badge bg-label-secondary"><?= html_escape($cat->slug); ?></span></td>
          <td>
            <?php if ($cat->parent_id): ?>
            <span class="badge bg-label-primary"><?= $category_map[$cat->parent_id] ?? 'ID Tidak Ditemukan'; ?></span>
            <?php else: ?>
            <span class="text-muted">— UTAMA —</span>
            <?php endif; ?>
          </td>
          <td>
            <div class="d-inline-block text-nowrap">
              <a href="<?= site_url('admin/category/form/' . $cat->id); ?>"
                class="btn btn-sm btn-icon btn-text-secondary" title="Edit"><i class="ti ti-edit"></i></a>
              <button type="button" class="btn btn-sm btn-icon btn-text-secondary"
                onclick="confirmDelete(<?= $cat->id; ?>)" title="Hapus">
                <i class="ti ti-trash"></i>
              </button>
            </div>
          </td>
        </tr>
        <?php 
                    endforeach;
                    else: 
                ?>
        <tr>
          <td colspan="5" class="text-center">Belum ada data kategori.</td>
        </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
function confirmDelete(categoryId) {
  if (confirm("Apakah Anda yakin ingin menghapus kategori ini? Produk yang terkait akan kehilangan kategori!")) {
    window.location.href = "<?= site_url('admin/category/delete/'); ?>" + categoryId;
  }
}
</script>