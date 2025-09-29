<!DOCTYPE html>
<html lang="id">

<head>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 flex items-center justify-center min-h-screen">
  <div class="w-full max-w-sm p-6 bg-white rounded-xl shadow-lg">
    <h1 class="text-3xl font-bold text-center text-gray-800 mb-6">Reset Password</h1>

    <?= form_open('auth/reset_password/' . $token, 'class="space-y-4"'); ?>
    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>"
      value="<?= $this->security->get_csrf_hash(); ?>">

    <div>
      <label for="password" class="block text-sm font-medium text-gray-700">Password Baru</label>
      <input type="password" name="password" required
        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
      <?= form_error('password', '<p class="text-xs text-red-500 mt-1">', '</p>'); ?>
    </div>

    <div>
      <label for="passconf" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
      <input type="password" name="passconf" required
        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
      <?= form_error('passconf', '<p class="text-xs text-red-500 mt-1">', '</p>'); ?>
    </div>

    <button type="submit"
      class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
      Ganti Password
    </button>
    <?= form_close(); ?>
  </div>
</body>

</html>