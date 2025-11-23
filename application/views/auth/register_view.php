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

      <!-- CARD REGISTER -->
      <div class="w-full max-w-md bg-white/40 backdrop-blur-md rounded-2xl shadow-xl 
                  border border-white/30 p-8">

        <!-- Judul -->
        <h1 class="text-3xl font-extrabold text-center text-white drop-shadow mb-1">
          Daftar Akun
        </h1>
        <p class="text-sm text-center text-gray-200 drop-shadow mb-6">
          Buat akun baru dan mulai belanja dengan mudah.
        </p>

        <!-- Alert -->
        <?php if ($this->session->flashdata('error')): ?>
        <div class="p-3 mb-4 text-sm text-red-700 bg-red-100 rounded-lg">
          <?= $this->session->flashdata('error'); ?>
        </div>
        <?php endif; ?>

        <!-- FORM REGISTER -->
        <?= form_open('auth/register', 'class="space-y-4"'); ?>

        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>"
          value="<?= $this->security->get_csrf_hash(); ?>">

        <!-- Nama Lengkap -->
        <div>
          <label class="block text-sm font-medium text-white">Nama Lengkap</label>
          <input type="text" name="full_name" value="<?= set_value('full_name'); ?>" required class="mt-1 block w-full px-3 py-2 rounded-md border border-gray-300
                        focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
          <?= form_error('full_name', '<p class="text-xs text-red-300 mt-1">', '</p>'); ?>
        </div>

        <!-- Email -->
        <div>
          <label class="block text-sm font-medium text-white">Email</label>
          <input type="email" name="email" value="<?= set_value('email'); ?>" required class="mt-1 block w-full px-3 py-2 rounded-md border border-gray-300
                        focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
          <?= form_error('email', '<p class="text-xs text-red-300 mt-1">', '</p>'); ?>
        </div>

        <!-- Password -->
        <div>
          <label class="block text-sm font-medium text-white">Password</label>
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

        <!-- Tombol Daftar -->
        <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-2.5 rounded-lg
                       hover:bg-indigo-700 transition duration-200 shadow-md">
          Daftar Akun
        </button>

        <?= form_close(); ?>

        <!-- Divider -->
        <div class="relative flex justify-center my-6">
          <span class="px-2 bg-white/50 text-gray-700 text-xs rounded-md backdrop-blur">
            atau
          </span>
        </div>

        <!-- Daftar dengan Google -->
        <a href="<?= site_url('auth/google_login'); ?>" class="w-full flex items-center justify-center border-2 border-gray-300
                  bg-white/70 backdrop-blur text-gray-700 py-2.5 rounded-lg
                  hover:bg-white transition duration-200">

          <img src="<?= base_url('assets/icons8-google-50.png'); ?>" class="w-5 h-5 mr-2" alt="Google">

          <span class="font-medium">Daftar dengan Google</span>
        </a>

        <!-- Sudah punya akun -->
        <p class="mt-6 text-center text-sm text-gray-200 drop-shadow">
          Sudah punya akun?
          <a href="<?= site_url('auth'); ?>" class="font-medium text-white underline hover:text-indigo-200">
            Masuk di sini
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