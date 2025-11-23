<div class="space-y-10">

  <div
    class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-dashed border-gray-200 pb-8">
    <div>
      <h2 class="text-3xl font-black text-slate-800 tracking-tight mb-2">Ringkasan Akun</h2>
      <p class="text-slate-500 text-lg">
        Selamat datang kembali, <span class="text-indigo-600 font-bold"><?= html_escape($user->full_name); ?></span>! 👋
      </p>
    </div>
    <a href="<?= site_url('home'); ?>"
      class="inline-flex items-center justify-center px-6 py-3 bg-slate-900 text-white font-bold rounded-xl hover:bg-indigo-600 transition-all shadow-lg hover:shadow-indigo-200">
      <i class="ti ti-shopping-cart-plus mr-2"></i> Belanja Lagi
    </a>
  </div>

  <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">

    <div
      class="group bg-slate-50 rounded-3xl p-6 border border-slate-100 hover:bg-white hover:border-yellow-200 hover:shadow-xl hover:shadow-yellow-100/50 transition-all duration-300">
      <div class="flex justify-between items-start mb-4">
        <div
          class="w-12 h-12 rounded-2xl bg-yellow-100 text-yellow-600 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
          <i class="ti ti-wallet"></i>
        </div>
        <span class="text-xs font-bold text-yellow-600 bg-yellow-50 px-2 py-1 rounded-lg">Action</span>
      </div>
      <p class="text-slate-500 font-medium text-sm">Menunggu Bayar</p>
      <h3 class="text-4xl font-black text-slate-800 mt-1"><?= $summary->pending_payment_count ?? 0; ?></h3>
    </div>

    <div
      class="group bg-slate-50 rounded-3xl p-6 border border-slate-100 hover:bg-white hover:border-indigo-200 hover:shadow-xl hover:shadow-indigo-100/50 transition-all duration-300">
      <div class="flex justify-between items-start mb-4">
        <div
          class="w-12 h-12 rounded-2xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
          <i class="ti ti-package"></i>
        </div>
      </div>
      <p class="text-slate-500 font-medium text-sm">Sedang Diproses</p>
      <h3 class="text-4xl font-black text-slate-800 mt-1">
        <?= ($summary->shipped_count ?? 0) + ($summary->pending_payment_count ?? 0); ?>
      </h3>
    </div>

    <div
      class="group bg-slate-50 rounded-3xl p-6 border border-slate-100 hover:bg-white hover:border-blue-200 hover:shadow-xl hover:shadow-blue-100/50 transition-all duration-300">
      <div class="flex justify-between items-start mb-4">
        <div
          class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
          <i class="ti ti-truck-delivery"></i>
        </div>
      </div>
      <p class="text-slate-500 font-medium text-sm">Sedang Dikirim</p>
      <h3 class="text-4xl font-black text-slate-800 mt-1"><?= $summary->shipped_count ?? 0; ?></h3>
    </div>

    <div
      class="group bg-slate-50 rounded-3xl p-6 border border-slate-100 hover:bg-white hover:border-green-200 hover:shadow-xl hover:shadow-green-100/50 transition-all duration-300">
      <div class="flex justify-between items-start mb-4">
        <div
          class="w-12 h-12 rounded-2xl bg-green-100 text-green-600 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
          <i class="ti ti-circle-check"></i>
        </div>
      </div>
      <p class="text-slate-500 font-medium text-sm">Pesanan Selesai</p>
      <h3 class="text-4xl font-black text-slate-800 mt-1"><?= $summary->completed_count ?? 0; ?></h3>
    </div>
  </div>

  <div>
    <div class="flex items-center justify-between mb-6">
      <h3 class="text-xl font-bold text-slate-800 flex items-center">
        <span class="w-2 h-8 bg-indigo-500 rounded-full mr-3"></span>
        Pesanan Terbaru
      </h3>
      <a href="<?= site_url('order'); ?>"
        class="text-sm font-bold text-indigo-600 hover:text-indigo-800 hover:underline flex items-center">
        Lihat Semua <i class="ti ti-arrow-right ml-1"></i>
      </a>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-slate-50 border-b border-slate-200">
              <th class="px-6 py-5 text-xs font-extrabold text-slate-500 uppercase tracking-wider">ID Order</th>
              <th class="px-6 py-5 text-xs font-extrabold text-slate-500 uppercase tracking-wider">Tanggal</th>
              <th class="px-6 py-5 text-xs font-extrabold text-slate-500 uppercase tracking-wider">Total</th>
              <th class="px-6 py-5 text-xs font-extrabold text-slate-500 uppercase tracking-wider">Status</th>
              <th class="px-6 py-5 text-xs font-extrabold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
            <?php if (!empty($recent_orders)): ?>
            <?php foreach ($recent_orders as $order): ?>
            <tr class="group hover:bg-indigo-50/30 transition-colors">
              <td class="px-6 py-5 whitespace-nowrap">
                <span class="font-mono font-bold text-indigo-600">#<?= $order->id; ?></span>
              </td>
              <td class="px-6 py-5 whitespace-nowrap">
                <span
                  class="text-sm font-semibold text-slate-600"><?= date('d M Y', strtotime($order->created_at)); ?></span>
                <span class="block text-xs text-slate-400"><?= date('H:i', strtotime($order->created_at)); ?> WIB</span>
              </td>
              <td class="px-6 py-5 whitespace-nowrap">
                <span class="text-sm font-black text-slate-800"><?= format_rupiah($order->total_amount); ?></span>
              </td>
              <td class="px-6 py-5 whitespace-nowrap">
                <div class="transform scale-90 origin-left">
                  <?= order_status_badge($order->order_status); ?>
                </div>
              </td>
              <td class="px-6 py-5 whitespace-nowrap text-right">
                <a href="<?= site_url('order/invoice/' . $order->id); ?>"
                  class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-bold bg-white text-slate-600 border border-slate-200 hover:bg-indigo-600 hover:text-white hover:border-indigo-600 transition-all shadow-sm group-hover:shadow-md">
                  Detail <i class="ti ti-chevron-right ml-1"></i>
                </a>
              </td>
            </tr>
            <?php endforeach; ?>
            <?php else: ?>
            <tr>
              <td colspan="5" class="px-6 py-12 text-center">
                <div class="flex flex-col items-center justify-center">
                  <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                    <i class="ti ti-shopping-cart-off text-2xl text-slate-400"></i>
                  </div>
                  <h3 class="text-slate-800 font-bold text-lg">Belum ada pesanan</h3>
                  <p class="text-slate-500 text-sm mb-4">Yuk, mulai belanja dan penuhi kebutuhanmu!</p>
                  <a href="<?= site_url(); ?>"
                    class="px-5 py-2 bg-indigo-600 text-white rounded-lg font-bold text-sm hover:bg-indigo-700">Mulai
                    Belanja</a>
                </div>
              </td>
            </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>