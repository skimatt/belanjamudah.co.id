<div class="space-y-8">

  <div
    class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-dashed border-gray-200 pb-8">
    <div>
      <h2 class="text-2xl font-black text-slate-800 tracking-tight flex items-center">
        <i class="ti ti-address-book text-indigo-500 mr-3 text-3xl"></i> Buku Alamat
      </h2>
      <p class="text-slate-500 text-sm mt-1">
        Kelola alamat pengiriman untuk mempercepat proses checkout.
      </p>
    </div>

    <a href="<?= site_url('account/form_address'); ?>"
      class="inline-flex items-center px-6 py-3 bg-slate-900 text-white font-bold rounded-xl hover:bg-indigo-600 hover:shadow-lg hover:shadow-indigo-200 transition-all transform hover:-translate-y-0.5">
      <i class="ti ti-plus mr-2"></i> Tambah Alamat
    </a>
  </div>

  <?php if ($this->session->flashdata('success')): ?>
  <div
    class="flex items-center p-4 bg-green-50 rounded-2xl border border-green-100 text-green-700 shadow-sm animate__animated animate__fadeIn">
    <i class="ti ti-circle-check-filled text-xl mr-3"></i>
    <span class="font-bold text-sm"><?= $this->session->flashdata('success'); ?></span>
  </div>
  <?php endif; ?>

  <?php if ($this->session->flashdata('error')): ?>
  <div
    class="flex items-center p-4 bg-red-50 rounded-2xl border border-red-100 text-red-700 shadow-sm animate__animated animate__fadeIn">
    <i class="ti ti-alert-circle-filled text-xl mr-3"></i>
    <span class="font-bold text-sm"><?= $this->session->flashdata('error'); ?></span>
  </div>
  <?php endif; ?>

  <?php if (!empty($addresses)): ?>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <?php foreach ($addresses as $addr): ?>

    <div class="group relative flex flex-col justify-between p-6 rounded-3xl border transition-all duration-300
            <?= $addr->is_main 
                ? 'bg-indigo-50/40 border-indigo-500 shadow-md shadow-indigo-100 ring-1 ring-indigo-500' 
                : 'bg-white border-slate-200 hover:border-indigo-300 hover:shadow-lg'; ?>">

      <div>
        <div class="flex justify-between items-start mb-4">
          <div class="flex items-center gap-2">
            <span
              class="inline-flex items-center justify-center w-8 h-8 rounded-full <?= $addr->is_main ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-500'; ?>">
              <i class="ti ti-home text-sm"></i>
            </span>
            <div>
              <h4 class="font-bold text-slate-800 text-base leading-tight">
                <?= html_escape($addr->label); ?>
              </h4>
              <?php if ($addr->is_main): ?>
              <span class="text-[10px] font-black text-indigo-600 uppercase tracking-widest">Utama</span>
              <?php endif; ?>
            </div>
          </div>

          <div class="flex items-center gap-1">
            <a href="<?= site_url('account/form_address/' . $addr->id); ?>"
              class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors"
              title="Edit">
              <i class="ti ti-pencil"></i>
            </a>
            <?php if (!$addr->is_main): ?>
            <a href="<?= site_url('account/delete_address/' . $addr->id); ?>"
              onclick="return confirm('Yakin ingin menghapus alamat ini?')"
              class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
              <i class="ti ti-trash"></i>
            </a>
            <?php endif; ?>
          </div>
        </div>

        <div class="mb-4">
          <p class="font-bold text-slate-800 text-lg flex items-center gap-2">
            <?= html_escape($addr->recipient_name); ?>
          </p>
          <p class="text-slate-500 text-sm font-medium flex items-center gap-2 mt-1">
            <i class="ti ti-phone text-xs"></i> <?= html_escape($addr->phone_number); ?>
          </p>
        </div>

        <div
          class="p-3 rounded-xl bg-white/60 border border-slate-100/50 text-sm text-slate-600 leading-relaxed mb-4 min-h-[80px]">
          <?= nl2br(html_escape($addr->address_line_1)); ?>, <br>
          <span class="font-semibold text-slate-800">
            <?= html_escape($addr->city); ?> <?= html_escape($addr->postal_code); ?>
          </span>
        </div>
      </div>

      <div
        class="mt-auto pt-4 border-t border-dashed <?= $addr->is_main ? 'border-indigo-200' : 'border-slate-200'; ?>">
        <?php if (!$addr->is_main): ?>
        <a href="<?= site_url('account/set_main_address/' . $addr->id); ?>"
          class="flex items-center justify-center w-full py-2.5 rounded-xl border border-slate-300 text-slate-600 font-bold text-xs hover:bg-slate-800 hover:text-white hover:border-slate-800 transition-all">
          <i class="ti ti-check mr-2"></i> Jadikan Alamat Utama
        </a>
        <?php else: ?>
        <div
          class="flex items-center justify-center w-full py-2.5 rounded-xl bg-indigo-600 text-white font-bold text-xs shadow-sm">
          <i class="ti ti-star-filled mr-2"></i> Alamat Utama Aktif
        </div>
        <?php endif; ?>
      </div>

    </div>
    <?php endforeach; ?>
  </div>

  <?php else: ?>
  <div
    class="flex flex-col items-center justify-center py-16 bg-slate-50 rounded-[2.5rem] border border-dashed border-slate-300 text-center">
    <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-sm mb-4">
      <i class="ti ti-map-off text-3xl text-slate-300"></i>
    </div>
    <h3 class="text-lg font-bold text-slate-800 mb-2">Belum ada alamat</h3>
    <p class="text-slate-500 text-sm max-w-xs mb-6">Tambahkan alamat pengiriman agar proses checkout Anda lebih cepat.
    </p>
    <a href="<?= site_url('account/form_address'); ?>"
      class="px-6 py-3 bg-indigo-600 text-white rounded-xl font-bold shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition-all">
      + Tambah Alamat Sekarang
    </a>
  </div>
  <?php endif; ?>

</div>