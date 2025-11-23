<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

<!-- WRAPPER (lebih lebar) -->
<div class="mx-auto max-w-[1450px] px-4 sm:px-6 lg:px-12 space-y-6">

  <!-- HEADER -->
  <div
    class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-dashed border-gray-200 pb-6">

    <div>
      <h2 class="text-2xl font-black text-slate-800 tracking-tight flex items-center">
        <i class="ti ti-history text-indigo-500 mr-3 text-3xl"></i> Riwayat Pesanan
      </h2>
      <p class="text-slate-500 text-sm mt-1">
        Lacak status pengiriman dan riwayat belanja Anda.
      </p>
    </div>

    <div class="relative w-full md:w-64">
      <i class="ti ti-search absolute left-3 top-3 text-gray-400"></i>
      <input type="text" placeholder="Cari Order ID..."
        class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:bg-white transition-all">
    </div>
  </div>

  <!-- CARD LIST -->
  <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">

    <div class="overflow-x-auto">
      <table class="w-full text-left border-collapse">

        <!-- TABLE HEADER -->
        <thead>
          <tr
            class="bg-slate-50 border-b border-slate-200 text-xs font-extrabold text-slate-500 uppercase tracking-wider">
            <th class="px-6 py-5">Detail Order</th>
            <th class="px-6 py-5">Total & Pembayaran</th>
            <th class="px-6 py-5">Pengiriman</th>
            <th class="px-6 py-5">Status</th>
            <th class="px-6 py-5 text-right">Aksi</th>
          </tr>
        </thead>

        <!-- TABLE BODY -->
        <tbody class="divide-y divide-slate-100">

          <?php if (!empty($orders)): ?>
          <?php foreach ($orders as $order): ?>
          <tr class="group hover:bg-indigo-50/30 transition-colors duration-200">

            <!-- DETAIL ORDER -->
            <td class="px-6 py-5 align-top">
              <div class="flex items-start gap-3">
                <div class="p-2 bg-indigo-50 rounded-lg text-indigo-600">
                  <i class="ti ti-package"></i>
                </div>
                <div>
                  <span class="block font-mono font-bold text-indigo-600 text-base">#<?= $order->id; ?></span>
                  <span class="text-xs text-slate-500 font-medium flex items-center mt-1">
                    <i class="ti ti-calendar-event mr-1"></i>
                    <?= date('d M Y', strtotime($order->created_at)); ?>
                  </span>
                </div>
              </div>
            </td>

            <!-- TOTAL & PEMBAYARAN -->
            <td class="px-6 py-5 align-top">
              <p class="font-black text-slate-800 text-base mb-2">
                <?= format_rupiah($order->total_amount); ?>
              </p>
              <div class="transform scale-90 origin-left">
                <?= payment_status_badge($order->payment_status); ?>
              </div>
            </td>

            <!-- PENGIRIMAN -->
            <td class="px-6 py-5 align-top">
              <div class="mb-1 font-bold text-slate-700 text-sm">
                <i class="ti ti-truck-delivery mr-1 text-slate-400"></i>
                <?= html_escape($order->shipping_courier); ?>
              </div>
              <p class="text-xs text-slate-500 bg-slate-50 inline-block px-2 py-1 rounded border border-slate-100">
                Est:
                <span class="font-semibold text-slate-700">
                  <?= calculate_etd($order->shipping_courier, $order->order_status); ?>
                </span>
              </p>
            </td>

            <!-- STATUS (lebih ter-highlight) -->
            <td class="px-6 py-5 align-top">

              <div class="
                inline-flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-bold
                shadow-sm transition-all transform scale-95 origin-left
                border bg-white group-hover:scale-100 group-hover:shadow-md
              ">
                <?= order_status_badge($order->order_status); ?>
              </div>

            </td>

            <!-- ACTION BUTTONS -->
            <td class="px-6 py-5 text-right align-middle">
              <div class="flex flex-col items-end gap-2">

                <a href="<?= site_url('order/invoice/' . $order->id); ?>"
                  class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-bold bg-white text-slate-600 border border-slate-200 hover:border-indigo-500 hover:text-indigo-600 hover:shadow-md transition-all">
                  <i class="ti ti-file-invoice mr-1.5"></i> Invoice
                </a>

                <?php if (in_array($order->order_status, ['packing', 'shipped'])): ?>
                <a href="<?= site_url('payment/tracking/' . $order->id); ?>"
                  class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-bold bg-slate-800 text-white hover:bg-slate-900 shadow-md transition-all">
                  <i class="ti ti-radar mr-1.5"></i> Lacak
                </a>
                <?php endif; ?>

              </div>
            </td>

          </tr>
          <?php endforeach; ?>

          <?php else: ?>

          <!-- EMPTY STATE -->
          <tr>
            <td colspan="5" class="px-6 py-16 text-center">
              <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4 shadow-inner">
                  <i class="ti ti-shopping-cart-x text-3xl text-slate-300"></i>
                </div>
                <h3 class="text-slate-800 font-bold text-lg mb-2">Belum Ada Riwayat</h3>
                <p class="text-slate-500 text-sm mb-6">
                  Anda belum pernah melakukan pemesanan.
                </p>
                <a href="<?= site_url(); ?>"
                  class="px-6 py-3 bg-indigo-600 text-white rounded-xl font-bold text-sm hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition-all">
                  Mulai Belanja Sekarang
                </a>
              </div>
            </td>
          </tr>

          <?php endif; ?>

        </tbody>
      </table>
    </div>

  </div>

</div>