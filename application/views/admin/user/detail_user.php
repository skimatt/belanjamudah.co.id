<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Pengguna / Detail /</span> <?= html_escape($user->full_name); ?>
</h4>

<?php if ($this->session->flashdata('success')): ?>
<div class="alert alert-success alert-dismissible" role="alert"><?= $this->session->flashdata('success'); ?><button
    type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
<div class="alert alert-danger alert-dismissible" role="alert"><?= $this->session->flashdata('error'); ?><button
    type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>
<?php endif; ?>

<div class="row">
  <div class="col-lg-8">
    <div class="card mb-4">
      <h5 class="card-header">Informasi Dasar Pengguna</h5>
      <div class="card-body">
        <table class="table table-borderless table-striped">
          <tr>
            <td style="width: 30%;">Nama Lengkap</td>
            <td>: <strong><?= html_escape($user->full_name); ?></strong></td>
          </tr>
          <tr>
            <td>Email</td>
            <td>: <?= html_escape($user->email); ?></td>
          </tr>
          <tr>
            <td>Nomor HP</td>
            <td>: <?= html_escape($user->phone_number) ?: '-'; ?></td>
          </tr>
          <tr>
            <td>Terdaftar Sejak</td>
            <td>: <?= date('d F Y H:i', strtotime($user->created_at)); ?></td>
          </tr>
          <tr>
            <td>Terakhir Login</td>
            <td>: - (Perlu implementasi tracking)</td>
          </tr>
        </table>
        <a href="<?= site_url('admin/user'); ?>" class="btn btn-label-secondary mt-4">Kembali ke Daftar</a>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="card">
      <h5 class="card-header">Ubah Status & Peran</h5>
      <div class="card-body">
        <?= form_open('admin/user/update/' . $user->id); ?>
        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>"
          value="<?= $this->security->get_csrf_hash(); ?>">

        <div class="mb-3">
          <label for="status" class="form-label">Status Akun</label>
          <select id="status" name="status" class="form-select" required>
            <?php foreach ($user_status_list as $status): ?>
            <option value="<?= $status; ?>" <?= ($user->status == $status) ? 'selected' : ''; ?>>
              <?= ucfirst($status); ?>
            </option>
            <?php endforeach; ?>
          </select>
          <small class="text-danger">Status 'Blocked' akan mencegah user login!</small>
          <?= form_error('status', '<div class="text-danger mt-1 text-xs">', '</div>'); ?>
        </div>

        <div class="mb-3">
          <label for="is_admin" class="form-label">Peran Pengguna</label>
          <select id="is_admin" name="is_admin" class="form-select" required>
            <option value="0" <?= ($user->is_admin == 0) ? 'selected' : ''; ?>>Customer Biasa</option>
            <option value="1" <?= ($user->is_admin == 1) ? 'selected' : ''; ?>>Administrator</option>
          </select>
          <?= form_error('is_admin', '<div class="text-danger mt-1 text-xs">', '</div>'); ?>
        </div>

        <button type="submit" class="btn btn-primary w-100 mt-2">Simpan Perubahan</button>
        <?= form_close(); ?>

        <hr class="my-4">
        <small class="text-muted">Untuk Reset Password, Anda dapat menggunakan fitur "Lupa Password" di halaman login
          atau implementasi fitur internal reset password Admin.</small>
      </div>
    </div>
  </div>
</div>