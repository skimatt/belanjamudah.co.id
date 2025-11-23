<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

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

<div class="max-w-3xl mx-auto">
  <div class="flex items-center justify-between mb-8">
    <div>
      <h2 class="text-2xl font-black text-slate-800 tracking-tight flex items-center">
        <span class="bg-indigo-600 text-white p-2 rounded-xl mr-3 shadow-lg shadow-indigo-200">
          <i class="<?= $is_edit ? 'ti ti-pencil' : 'ti ti-map-pin-plus'; ?>"></i>
        </span>
        <?= $is_edit ? 'Edit Alamat' : 'Alamat Baru'; ?>
      </h2>
      <p class="text-slate-500 text-sm mt-1 ml-14">Pastikan alamat yang Anda masukkan valid dan lengkap.</p>
    </div>
    <a href="<?= site_url('account/addresses'); ?>"
      class="hidden md:flex items-center text-sm font-bold text-slate-500 hover:text-indigo-600 transition-colors">
      <i class="ti ti-arrow-left mr-1"></i> Kembali
    </a>
  </div>

  <?= form_open($action_url, 'class="space-y-6"'); ?>
  <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>"
    value="<?= $this->security->get_csrf_hash(); ?>">

  <div class="bg-white p-8 rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div class="group">
        <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Label Alamat</label>
        <div class="relative">
          <div
            class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-500 transition-colors">
            <i class="ti ti-tag"></i>
          </div>
          <input type="text" name="label"
            class="block w-full pl-11 pr-4 py-3 bg-slate-50 border-transparent rounded-xl text-sm font-medium focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all placeholder-slate-400"
            placeholder="Contoh: Rumah, Kantor, Apartemen" value="<?= $a_label; ?>" required>
        </div>
        <?= form_error('label', '<p class="text-xs font-bold text-red-500 mt-1 flex items-center"><i class="ti ti-alert-circle mr-1"></i>', '</p>'); ?>
      </div>

      <div class="group">
        <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Nama Penerima</label>
        <div class="relative">
          <div
            class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-500 transition-colors">
            <i class="ti ti-user"></i>
          </div>
          <input type="text" name="recipient_name"
            class="block w-full pl-11 pr-4 py-3 bg-slate-50 border-transparent rounded-xl text-sm font-medium focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all placeholder-slate-400"
            placeholder="Nama Lengkap Penerima" value="<?= $a_recipient; ?>" required>
        </div>
        <?= form_error('recipient_name', '<p class="text-xs font-bold text-red-500 mt-1 flex items-center"><i class="ti ti-alert-circle mr-1"></i>', '</p>'); ?>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
      <div class="group">
        <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Nomor Handphone</label>
        <div class="relative">
          <div
            class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-500 transition-colors">
            <i class="ti ti-phone"></i>
          </div>
          <input type="tel" name="phone_number"
            class="block w-full pl-11 pr-4 py-3 bg-slate-50 border-transparent rounded-xl text-sm font-medium focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all placeholder-slate-400"
            placeholder="08xxxxxxxxxx" value="<?= $a_phone; ?>" required>
        </div>
        <?= form_error('phone_number', '<p class="text-xs font-bold text-red-500 mt-1 flex items-center"><i class="ti ti-alert-circle mr-1"></i>', '</p>'); ?>
      </div>

      <div class="group">
        <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Kota / Kabupaten</label>
        <div class="relative">
          <div
            class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-500 transition-colors">
            <i class="ti ti-building-skyscraper"></i>
          </div>
          <input type="text" name="city"
            class="block w-full pl-11 pr-4 py-3 bg-slate-50 border-transparent rounded-xl text-sm font-medium focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all placeholder-slate-400"
            placeholder="Nama Kota" value="<?= $a_city; ?>" required>
        </div>
        <?= form_error('city', '<p class="text-xs font-bold text-red-500 mt-1 flex items-center"><i class="ti ti-alert-circle mr-1"></i>', '</p>'); ?>
      </div>
    </div>

    <div class="mt-6 group">
      <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Alamat Lengkap</label>
      <div class="relative">
        <div
          class="absolute top-3.5 left-4 pointer-events-none text-slate-400 group-focus-within:text-indigo-500 transition-colors">
          <i class="ti ti-map-pin"></i>
        </div>
        <textarea name="address_line_1" rows="3"
          class="block w-full pl-11 pr-4 py-3 bg-slate-50 border-transparent rounded-xl text-sm font-medium focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all placeholder-slate-400 resize-none"
          placeholder="Nama Jalan, No. Rumah, RT/RW, Kelurahan, Kecamatan" required><?= $a_addr1; ?></textarea>
      </div>
      <?= form_error('address_line_1', '<p class="text-xs font-bold text-red-500 mt-1 flex items-center"><i class="ti ti-alert-circle mr-1"></i>', '</p>'); ?>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
      <div class="group">
        <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Kode Pos</label>
        <div class="relative">
          <div
            class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-500 transition-colors">
            <i class="ti ti-mailbox"></i>
          </div>
          <input type="text" name="postal_code"
            class="block w-full pl-11 pr-4 py-3 bg-slate-50 border-transparent rounded-xl text-sm font-medium focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all placeholder-slate-400"
            placeholder="5 Digit Kode Pos" value="<?= $a_postal; ?>" required>
        </div>
        <?= form_error('postal_code', '<p class="text-xs font-bold text-red-500 mt-1 flex items-center"><i class="ti ti-alert-circle mr-1"></i>', '</p>'); ?>
      </div>

      <div class="group">
        <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Detail Tambahan <span
            class="text-slate-300 normal-case ml-1">(Opsional)</span></label>
        <div class="relative">
          <div
            class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-500 transition-colors">
            <i class="ti ti-info-circle"></i>
          </div>
          <input type="text" name="address_line_2"
            class="block w-full pl-11 pr-4 py-3 bg-slate-50 border-transparent rounded-xl text-sm font-medium focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all placeholder-slate-400"
            placeholder="Contoh: Pagar Hitam, Dekat Masjid" value="<?= $a_addr2; ?>">
        </div>
      </div>
    </div>

    <div class="mt-8 pt-6 border-t border-dashed border-gray-200">
      <label class="inline-flex items-center group cursor-pointer">
        <div class="relative flex items-center">
          <input id="is_main" name="is_main" type="checkbox" value="1" <?= $a_main == 1 ? 'checked' : ''; ?>
            class="peer h-5 w-5 cursor-pointer appearance-none rounded-md border-2 border-slate-300 transition-all checked:border-indigo-600 checked:bg-indigo-600 hover:border-indigo-400">
          <i
            class="ti ti-check absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 text-white opacity-0 peer-checked:opacity-100 text-xs font-bold pointer-events-none"></i>
        </div>
        <span class="ml-3 text-sm font-bold text-slate-700 group-hover:text-indigo-600 transition-colors select-none">
          Jadikan sebagai Alamat Utama
        </span>
      </label>
    </div>

  </div>

  <div class="flex items-center justify-end gap-4 mt-6">
    <a href="<?= site_url('account/addresses'); ?>"
      class="px-6 py-3 rounded-xl font-bold text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition-all">
      Batal
    </a>
    <button type="submit"
      class="px-8 py-3 bg-indigo-600 text-white font-bold rounded-xl shadow-lg shadow-indigo-200 hover:bg-indigo-700 hover:shadow-indigo-300 hover:-translate-y-0.5 transition-all flex items-center">
      <i class="ti ti-device-floppy mr-2"></i> <?= $is_edit ? 'Simpan Perubahan' : 'Simpan Alamat'; ?>
    </button>
  </div>

  <?= form_close(); ?>
</div>