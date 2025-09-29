<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Toko MVP</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 flex items-center justify-center min-h-screen">
  <div class="w-full max-w-sm p-6 bg-white rounded-xl shadow-lg">
    <h1 class="text-3xl font-bold text-center text-gray-800 mb-6">Login</h1>

    <?php if ($this->session->flashdata('error')): ?>
    <div class="p-3 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
      <?= $this->session->flashdata('error'); ?>
    </div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('success')): ?>
    <div class="p-3 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
      <?= $this->session->flashdata('success'); ?>
    </div>
    <?php endif; ?>

    <?= form_open('auth/process_login', 'class="space-y-4"'); ?>
    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>"
      value="<?= $this->security->get_csrf_hash(); ?>">

    <div>
      <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
      <input type="email" name="email" value="<?= set_value('email'); ?>" required
        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
      <?= form_error('email', '<p class="text-xs text-red-500 mt-1">', '</p>'); ?>
    </div>

    <div>
      <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
      <input type="password" name="password" required
        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
      <?= form_error('password', '<p class="text-xs text-red-500 mt-1">', '</p>'); ?>
    </div>

    <div class="flex items-center justify-between">
      <a href="<?= site_url('auth/forgot_password'); ?>" class="text-sm text-indigo-600 hover:text-indigo-500">
        Lupa Password?
      </a>
    </div>

    <button type="submit"
      class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
      Login
    </button>
    <?= form_close(); ?>

    <div class="mt-6">
      <a href="<?= $google_login_url; ?>"
        class="w-full flex items-center justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
        <img class="w-4 h-4 mr-2" src="https://upload.wikimedia.org/wikipedia/commons/5/53/Google_%22G%22_Logo.svg"
          alt="Google Logo">
        Login dengan Google
      </a>
    </div>

    <p class="mt-6 text-center text-sm text-gray-600">
      Belum punya akun?
      <a href="<?= site_url('auth/register'); ?>" class="font-medium text-indigo-600 hover:text-indigo-500">
        Daftar di sini
      </a>
    </p>
  </div>
</body>

</html>