<?php
// ===============================
//  LOAD NAVBAR / HEADER
// ===============================
$this->load->view('frontend/templates/header');
?>

<style>
.bg-section {
  background-image: url('<?= base_url("assets/shopping-bags-discount-online-sales.jpg"); ?>');
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
}
</style>

<section class="container mx-auto px-4 mt-10 mb-16">

  <div class="w-full rounded-2xl overflow-hidden bg-section">

    <!-- Overlay -->
    <div class="w-full h-full bg-black/40 p-10 flex justify-center">

      <!-- CARD -->
      <div class="w-full max-w-md bg-white/40 backdrop-blur-md rounded-2xl shadow-xl 
                  border border-white/30 p-8">

        <!-- Title -->
        <h1 class="text-3xl font-extrabold text-center text-white drop-shadow mb-2">
          Reset Password
        </h1>

        <p class="text-center text-gray-200 drop-shadow mb-6 text-sm">
          Buat password baru untuk akun Anda.
        </p>

        <!-- Alert -->
        <?php if ($this->session->flashdata('error')): ?>
        <div class="p-3 mb-4 text-sm text-red-700 bg-red-100 rounded-lg">
          <?= $this->session->flashdata('error'); ?>
        </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('success')): ?>
        <div class="p-3 mb-4 text-sm text-green-700 bg-green-100 rounded-lg">
          <?= $this->session->flashdata('success'); ?>
        </div>
        <?php endif; ?>

        <!-- FORM -->
        <?= form_open('auth/reset_password/' . $token, 'class="space-y-4"'); ?>

        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>"
          value="<?= $this->security->get_csrf_hash(); ?>">

        <!-- Password Baru -->
        <div>
          <label class="block text-sm font-medium text-white">Password Baru</label>
          <input type="password" name="password" required class="mt-1 block w-full px-3 py-2 rounded-md border border-gray-300
                        focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
          <?= form_error('password', '<p class="text-xs text-red-300 mt-1">', '</p>'); ?>
        </div>

        <!-- Konfirmasi Password -->
        <div>
          <label class="block text-sm font-medium text-white">Konfirmasi Password</label>
          <input type="password" name="passconf" required class="mt-1 block w-full px-3 py-2 rounded-md border border-gray-300
                        focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
          <?= form_error('passconf', '<p class="text-xs text-red-300 mt-1">', '</p>'); ?>
        </div>

        <!-- Submit -->
        <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-2.5 rounded-lg
                       hover:bg-indigo-700 transition duration-200 shadow-md">
          Ganti Password
        </button>

        <?= form_close(); ?>

        <!-- Back to Login -->
        <p class="mt-6 text-center text-sm text-gray-200 drop-shadow">
          <a href="<?= site_url('auth'); ?>" class="font-medium text-white underline hover:text-indigo-200">
            Kembali ke Login
          </a>
        </p>

      </div>

    </div>

  </div>

</section>

<?php
// ===============================
//  FOOTER
// ===============================
$this->load->view('frontend/templates/footer');
?>