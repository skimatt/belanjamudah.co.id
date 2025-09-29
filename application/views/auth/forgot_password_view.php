<!DOCTYPE html>
<html lang="id">

<head>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 flex items-center justify-center min-h-screen">
  <div class="w-full max-w-sm p-6 bg-white rounded-xl shadow-lg">
    <h1 class="text-3xl font-bold text-center text-gray-800 mb-6">Lupa Password</h1>

    <p class="mb-4 text-gray-600 text-center">Masukkan email Anda untuk menerima link reset password.</p>

    <?= form_open('auth/forgot_password', 'class="space-y-4"'); ?>
    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>"
      value="<?= $this->security->get_csrf_hash(); ?>">

    <div>
      <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
      <input type="email" name="email" value="<?= set_value('email'); ?>" required
        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
      <?= form_error('email', '<p class="text-xs text-red-500 mt-1">', '</p>'); ?>
    </div>

    <button type="submit"
      class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
      Kirim Link Reset
    </button>
    <?= form_close(); ?>

    <p class="mt-6 text-center text-sm text-gray-600"><a href="<?= site_url('auth'); ?>"
        class="font-medium text-indigo-600 hover:text-indigo-500">Kembali ke Login</a></p>
  </div>
</body>

</html>