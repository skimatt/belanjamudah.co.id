<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Promosi /</span> Manajemen Voucher
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
    <h5 class="card-title mb-0">Daftar Kode Voucher</h5>
    <a href="<?= site_url('admin/voucher/form'); ?>" class="btn btn-primary">
      <i class="ti ti-gift me-1"></i> Buat Voucher Baru
    </a>
  </div>

  <div class="table-responsive text-nowrap">
    <table class="table table-hover table-striped">
      <thead>
        <tr>
          <th>Kode</th>
          <th>Tipe</th>
          <th>Nilai</th>
          <th>Min. Belanja</th>
          <th>Pemakaian (Used/Max)</th>
          <th>Kadaluarsa</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        <?php if (!empty($vouchers)): ?>
        <?php foreach ($vouchers as $v): ?>
        <tr>
          <td><span class="fw-bold text-primary"><?= html_escape($v->code); ?></span></td>
          <td><?= ($v->type == 'percent') ? 'Persentase (%)' : 'Fixed (Rp)'; ?></td>
          <td>
            <?php if ($v->type == 'percent'): ?>
            <span class="badge bg-label-info"><?= $v->value; ?>%</span>
            <?php else: ?>
            <span class="badge bg-label-warning"><?= 'Rp ' . number_format($v->value, 0, ',', '.'); ?></span>
            <?php endif; ?>
          </td>
          <td><?= 'Rp ' . number_format($v->min_order_amount, 0, ',', '.'); ?></td>

          <td>
            <span class="fw-bold me-1 <?= ($v->usage_count >= $v->max_usage) ? 'text-danger' : 'text-success'; ?>">
              <?= $v->usage_count; ?>
            </span>
            / <?= $v->max_usage; ?>
          </td>

          <td><?= date('d M Y', strtotime($v->valid_until)); ?></td>
          <td>
            <?php 
                            $is_expired = strtotime($v->valid_until) < time();
                            if ($is_expired) {
                                echo '<span class="badge bg-danger">KADALUARSA</span>';
                            } elseif ($v->is_active == 1) {
                                echo '<span class="badge bg-success">Aktif</span>';
                            } else {
                                echo '<span class="badge bg-secondary">Nonaktif</span>';
                            }
                        ?>
          </td>

          <td>
            <div class="dropdown">
              <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i
                  class="ti ti-dots-vertical"></i></button>
              <div class="dropdown-menu">
                <a class="dropdown-item" href="<?= site_url('admin/voucher/form/' . $v->id); ?>"><i
                    class="ti ti-pencil me-1"></i> Edit</a>

                <?php if ($v->usage_count > 0): ?>
                <a class="dropdown-item" href="<?= site_url('admin/voucher/usage/' . $v->id); ?>">
                  <i class="ti ti-history me-1"></i> Riwayat Penggunaan
                </a>
                <div class="dropdown-divider"></div>
                <?php endif; ?>

                <a class="dropdown-item text-danger" href="javascript:void(0);" onclick="confirmDelete(<?= $v->id; ?>)">
                  <i class="ti ti-trash me-1"></i> Hapus
                </a>
              </div>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php else: ?>
        <tr>
          <td colspan="8" class="text-center">Belum ada data voucher.</td>
        </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
// Fungsi JavaScript untuk konfirmasi hapus
function confirmDelete(voucherId) {
  if (confirm("Apakah Anda yakin ingin menghapus voucher ini?")) {
    // Redirect ke endpoint delete di Controller
    window.location.href = "<?= site_url('admin/voucher/delete/'); ?>" + voucherId;
  }
}
</script>