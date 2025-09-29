<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Akun Baru</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 flex items-center justify-center min-h-screen">
  <div class="w-full max-w-md p-8 bg-white rounded-xl shadow-lg">
    <h1 class="text-3xl font-bold text-center text-gray-800 mb-8">Daftar Akun</h1>

    <?php if ($this->session->flashdata('error')): ?>
    <div class="p-3 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
      <?= $this->session->flashdata('error'); ?>
    </div>
    <?php endif; ?>

    <?= form_open('auth/register', 'class="space-y-4"'); ?>

    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>"
      value="<?= $this->security->get_csrf_hash(); ?>">

    <div>
      <label for="full_name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
      <input type="text" name="full_name" value="<?= set_value('full_name'); ?>" required
        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
      <?= form_error('full_name', '<p class="text-xs text-red-500 mt-1">', '</p>'); ?>
    </div>

    <div>
      <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
      <input type="email" name="email" value="<?= set_value('email'); ?>" required
        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
      <?= form_error('email', '<p class="text-xs text-red-500 mt-1">', '</p>'); ?>
    </div>

    <div>
      <label for="password" class="block text-sm font-medium text-gray-700">Password (min 8 karakter)</label>
      <input type="password" name="password" required
        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
      <?= form_error('password', '<p class="text-xs text-red-500 mt-1">', '</p>'); ?>
    </div>

    <div>
      <label for="passconf" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
      <input type="password" name="passconf" required
        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
      <?= form_error('passconf', '<p class="text-xs text-red-500 mt-1">', '</p>'); ?>
    </div>

    <button type="submit"
      class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
      Daftar Akun
    </button>
    <?= form_close(); ?>

    <div class="mt-6 relative">
      <div class="absolute inset-0 flex items-center">
        <div class="w-full border-t border-gray-300"></div>
      </div>
      <div class="relative flex justify-center text-sm">
        <span class="px-2 bg-white text-gray-500">Atau daftar dengan</span>
      </div>
    </div>

    <div class="mt-6">
      <a href="<?= site_url('auth/google_login'); ?>"
        class="w-full flex items-center justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
        <img class="w-4 h-4 mr-2" src="https://upload.wikimedia.org/wikipedia/commons/5/53/Google_%22G%22_Logo.svg"
          alt="Google Logo">
        Daftar dengan Google
      </a>
    </div>

    <p class="mt-6 text-center text-sm text-gray-600">
      Sudah punya akun?
      <a href="<?= site_url('auth'); ?>" class="font-medium text-indigo-600 hover:text-indigo-500">
        Login di sini
      </a>
    </p>
  </div>
</body>

</html>