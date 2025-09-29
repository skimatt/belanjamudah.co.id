<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Admin /</span> Manajemen Pengguna
</h4>

<?php if ($this->session->flashdata('success')): ?>
<div class="alert alert-success alert-dismissible" role="alert"><?= $this->session->flashdata('success'); ?><button
    type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>
<?php endif; ?>

<div class="card">
  <h5 class="card-header">Daftar Semua Pelanggan & Admin Lain</h5>

  <div class="table-responsive text-nowrap">
    <table class="table table-hover table-striped">
      <thead>
        <tr>
          <th>#ID</th>
          <th>Nama & Kontak</th>
          <th>Peran</th>
          <th>Status</th>
          <th>Terdaftar Sejak</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        <?php if (!empty($users)): ?>
        <?php foreach ($users as $user): ?>
        <tr>
          <td><?= $user->id; ?></td>
          <td>
            <strong><?= html_escape($user->full_name); ?></strong><br>
            <small class="text-muted"><?= html_escape($user->email); ?></small>
          </td>
          <td>
            <?php if ($user->is_admin == 1): ?>
            <span class="badge bg-label-danger"><i class="ti ti-crown me-1"></i> ADMIN</span>
            <?php else: ?>
            <span class="badge bg-label-info">Customer</span>
            <?php endif; ?>
          </td>
          <td>
            <?php 
                                    $badge_class = ($user->status == 'active') ? 'bg-label-success' : (($user->status == 'blocked') ? 'bg-label-danger' : 'bg-label-secondary');
                                ?>
            <span class="badge <?= $badge_class; ?>"><?= ucfirst($user->status); ?></span>
          </td>
          <td><?= date('d M Y', strtotime($user->created_at)); ?></td>
          <td>
            <a href="<?= site_url('admin/user/detail/' . $user->id); ?>" class="btn btn-sm btn-icon btn-text-info"
              title="Lihat & Edit">
              <i class="ti ti-eye"></i>
            </a>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php else: ?>
        <tr>
          <td colspan="6" class="text-center">Tidak ada pengguna lain di sistem.</td>
        </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>