<div class="bg-white p-6 rounded-xl shadow-md">
  <h2 class="text-2xl font-bold text-gray-800 mb-4 border-b pb-2 flex justify-between items-center">
    Daftar Alamat Pengiriman
    <a href="<?= site_url('account/form_address'); ?>"
      class="btn bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 text-sm">
      <i class="ti ti-plus me-1"></i> Tambah Alamat Baru
    </a>
  </h2>

  <?php if ($this->session->flashdata('success')): ?>
  <div class="p-3 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
    <?= $this->session->flashdata('success'); ?></div>
  <?php endif; ?>
  <?php if ($this->session->flashdata('error')): ?>
  <div class="p-3 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
    <?= $this->session->flashdata('error'); ?></div>
  <?php endif; ?>

  <div class="space-y-6 mt-4">
    <?php if (!empty($addresses)): ?>
    <?php foreach ($addresses as $addr): ?>
    <div
      class="border p-4 rounded-lg <?= $addr->is_main ? 'border-indigo-600 bg-indigo-50 shadow' : 'border-gray-200'; ?>">
      <div class="flex justify-between items-start mb-2">
        <h4 class="font-bold text-lg text-gray-800">
          <?= html_escape($addr->label); ?>
          <?php if ($addr->is_main): ?>
          <span class="badge bg-indigo-600 text-white text-xs px-2 py-1 ml-2 rounded-full">UTAMA</span>
          <?php endif; ?>
        </h4>
        <div class="flex space-x-2 text-sm">
          <a href="<?= site_url('account/form_address/' . $addr->id); ?>"
            class="text-indigo-600 hover:text-indigo-800">Edit</a>
          <?php if (!$addr->is_main): ?>
          <a href="<?= site_url('account/delete_address/' . $addr->id); ?>"
            onclick="return confirm('Yakin ingin menghapus alamat ini?')"
            class="text-red-500 hover:text-red-700">Hapus</a>
          <?php endif; ?>
        </div>
      </div>

      <p class="font-medium text-gray-700"><?= html_escape($addr->recipient_name); ?>
        (<?= html_escape($addr->phone_number); ?>)</p>
      <p class="text-gray-600 text-sm mt-1"><?= nl2br(html_escape($addr->address_line_1)); ?>,
        <?= html_escape($addr->city); ?> <?= html_escape($addr->postal_code); ?></p>

      <?php if (!$addr->is_main): ?>
      <div class="mt-3">
        <a href="<?= site_url('account/set_main_address/' . $addr->id); ?>"
          class="btn bg-gray-200 text-gray-700 px-3 py-1 rounded-lg text-xs hover:bg-gray-300">
          Tetapkan Sebagai Utama
        </a>
      </div>
      <?php endif; ?>
    </div>
    <?php endforeach; ?>
    <?php else: ?>
    <p class="text-center text-gray-500 py-6">Anda belum memiliki alamat tersimpan.</p>
    <?php endif; ?>
  </div>
</div>