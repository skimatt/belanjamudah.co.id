<?php
$is_edit = isset($address) && $address !== NULL;
$action_url = $is_edit ? 'account/form_address/' . $address->id : 'account/form_address';

$a_label = set_value('label', $is_edit ? $address->label : '');
$a_recipient = set_value('recipient_name', $is_edit ? $address->recipient_name : '');
$a_phone = set_value('phone_number', $is_edit ? $address->phone_number : '');
$a_addr1 = set_value('address_line_1', $is_edit ? $address->address_line_1 : '');
$a_addr2 = set_value('address_line_2', $is_edit ? $address->address_line_2 : '');
$a_city = set_value('city', $is_edit ? $address->city : '');
$a_postal = set_value('postal_code', $is_edit ? $address->postal_code : '');
$a_main = set_value('is_main', $is_edit ? $address->is_main : 0);
?>

<div class="bg-white p-6 rounded-xl shadow-md">
  <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-2">
    <?= $is_edit ? 'Edit Alamat' : 'Tambah Alamat Baru'; ?>
  </h2>

  <?= form_open($action_url, 'class="space-y-4"'); ?>
  <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>"
    value="<?= $this->security->get_csrf_hash(); ?>">

  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
      <label class="block text-sm font-medium text-gray-700">Label Alamat (Contoh: Rumah, Kantor)</label>
      <input type="text" name="label" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
        value="<?= $a_label; ?>" required>
      <?= form_error('label', '<p class="text-xs text-red-500 mt-1">', '</p>'); ?>
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700">Nama Penerima</label>
      <input type="text" name="recipient_name" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
        value="<?= $a_recipient; ?>" required>
      <?= form_error('recipient_name', '<p class="text-xs text-red-500 mt-1">', '</p>'); ?>
    </div>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
      <label class="block text-sm font-medium text-gray-700">Nomor Handphone</label>
      <input type="tel" name="phone_number" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
        value="<?= $a_phone; ?>" required>
      <?= form_error('phone_number', '<p class="text-xs text-red-500 mt-1">', '</p>'); ?>
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700">Kota</label>
      <input type="text" name="city" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
        value="<?= $a_city; ?>" required>
      <?= form_error('city', '<p class="text-xs text-red-500 mt-1">', '</p>'); ?>
    </div>
  </div>

  <div>
    <label class="block text-sm font-medium text-gray-700">Alamat Lengkap (Jalan, RT/RW)</label>
    <textarea name="address_line_1" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
      required><?= $a_addr1; ?></textarea>
    <?= form_error('address_line_1', '<p class="text-xs text-red-500 mt-1">', '</p>'); ?>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
      <label class="block text-sm font-medium text-gray-700">Kode Pos</label>
      <input type="text" name="postal_code" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
        value="<?= $a_postal; ?>" required>
      <?= form_error('postal_code', '<p class="text-xs text-red-500 mt-1">', '</p>'); ?>
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700">Patokan/Detail Tambahan (Opsional)</label>
      <input type="text" name="address_line_2" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
        value="<?= $a_addr2; ?>">
    </div>
  </div>

  <div class="flex items-center pt-2">
    <input id="is_main" name="is_main" type="checkbox" value="1" <?= $a_main == 1 ? 'checked' : ''; ?>
      class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
    <label for="is_main" class="ml-2 block text-sm font-medium text-gray-700">
      Jadikan sebagai Alamat Utama
    </label>
  </div>

  <div class="pt-4 flex space-x-3">
    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 font-medium">
      <?= $is_edit ? 'Simpan Perubahan' : 'Tambah Alamat'; ?>
    </button>
    <a href="<?= site_url('account/addresses'); ?>"
      class="text-gray-700 border px-4 py-2 rounded-lg hover:bg-gray-100">Batal</a>
  </div>

  <?= form_close(); ?>
</div>