<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

<?php $subtotal_barang = 0; foreach ($items as $i) { $subtotal_barang += $i->total_price; } ?>

<div class="bg-slate-50 min-h-screen pb-20 font-sans text-slate-800 print:bg-white print:pb-0">

  <div class="mx-auto px-4 sm:px-6 lg:px-16 max-w-[1400px] py-10 print:max-w-full print:p-0">

    <div
      class="bg-white p-8 md:p-10 rounded-[2rem] shadow-xl border border-indigo-50 relative overflow-hidden print:shadow-none print:border-none print:rounded-none">

      <div class="flex flex-col md:flex-row justify-between border-b border-dashed border-slate-200 pb-8 mb-10">
        <div>
          <div class="flex items-center gap-4 mb-2">
            <div class="bg-indigo-600 text-white p-3 rounded-2xl shadow-lg shadow-indigo-200 print:hidden">
              <i class="ti ti-receipt-2 text-3xl"></i>
            </div>
            <h1 class="text-4xl font-black text-slate-900 tracking-tight">Invoice</h1>
          </div>
          <p class="text-sm text-slate-500 font-medium ml-1">Terima kasih telah berbelanja di toko kami.</p>
        </div>

        <div class="text-left md:text-right mt-8 md:mt-0">
          <div class="mb-4">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Order ID</p>
            <p class="text-2xl font-mono font-bold text-indigo-600">#<?= $order->id; ?></p>
          </div>

          <div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tanggal Pesanan</p>
            <p class="text-base font-bold text-slate-700">
              <?= date('d F Y, H:i', strtotime($order->created_at)); ?> WIB
            </p>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12 print:gap-4">

        <div
          class="p-5 text-center bg-slate-50 rounded-2xl border border-slate-100 flex flex-col items-center justify-center">
          <p class="text-[10px] font-bold text-slate-400 uppercase mb-2 tracking-wide">Status Pembayaran</p>
          <div class="transform scale-110">
            <?= payment_status_badge($order->payment_status); ?>
          </div>
        </div>

        <div
          class="p-5 text-center bg-slate-50 rounded-2xl border border-slate-100 flex flex-col items-center justify-center">
          <p class="text-[10px] font-bold text-slate-400 uppercase mb-2 tracking-wide">Status Pesanan</p>
          <div class="transform scale-110">
            <?= order_status_badge($order->order_status); ?>
          </div>
        </div>

        <div
          class="p-5 text-center bg-indigo-50/50 rounded-2xl border border-indigo-100 flex flex-col items-center justify-center print:bg-white print:border-slate-200">
          <p class="text-[10px] font-bold text-indigo-400 uppercase mb-1 tracking-wide">Total Tagihan</p>
          <p class="text-2xl font-black text-indigo-700"><?= format_rupiah($order->total_amount); ?></p>
        </div>

      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 mb-8 print:block">

        <div class="space-y-10 lg:col-span-1 print:mb-8">

          <div>
            <h3
              class="text-sm font-extrabold text-slate-800 border-b-2 border-slate-100 pb-3 mb-5 uppercase flex items-center tracking-wider">
              <i class="ti ti-truck-delivery mr-2 text-slate-400 text-lg"></i> Pengiriman
            </h3>

            <div class="space-y-5 text-sm">
              <div>
                <p class="text-xs font-bold text-slate-400 uppercase mb-2">Alamat Tujuan</p>
                <div
                  class="bg-slate-50 p-4 rounded-2xl border border-slate-100 font-medium text-slate-700 leading-relaxed print:border-none print:p-0 print:bg-transparent">
                  <?= html_escape($order->shipping_address); ?>
                </div>
              </div>

              <div class="flex justify-between items-center border-b border-dashed border-slate-200 pb-2">
                <span class="text-slate-500">Layanan Kurir</span>
                <span class="font-bold text-slate-800"><?= $order->shipping_courier; ?></span>
              </div>

              <div class="flex justify-between items-center">
                <span class="text-slate-500">Nomor Resi</span>
                <?php if ($order->tracking_number): ?>
                <span
                  class="font-mono font-bold text-indigo-600 bg-indigo-50 px-3 py-1 rounded-lg text-xs border border-indigo-100">
                  <?= $order->tracking_number; ?>
                </span>
                <?php else: ?>
                <span class="text-slate-400 italic text-xs">Menunggu input</span>
                <?php endif; ?>
              </div>
            </div>
          </div>

          <div>
            <h3
              class="text-sm font-extrabold text-slate-800 border-b-2 border-slate-100 pb-3 mb-5 uppercase flex items-center tracking-wider">
              <i class="ti ti-credit-card mr-2 text-slate-400 text-lg"></i> Pembayaran
            </h3>

            <div class="space-y-5 text-sm">
              <div class="flex justify-between items-center border-b border-dashed border-slate-200 pb-2">
                <span class="text-slate-500">Metode Bayar</span>
                <span class="font-bold text-slate-800"><?= $order->payment_method; ?></span>
              </div>

              <?php if ($order->payment_status === 'pending'): ?>
              <div class="bg-red-50 p-5 rounded-2xl border border-red-100 text-center print:hidden">
                <p class="text-red-600 text-xs font-bold mb-3 flex items-center justify-center gap-1">
                  <i class="ti ti-alert-circle text-lg"></i> Menunggu Pembayaran
                </p>
                <a href="<?= site_url('payment/process_gateway/' . $order->id); ?>"
                  class="block w-full bg-indigo-600 text-white font-bold py-3 rounded-xl shadow-lg shadow-indigo-200 hover:bg-indigo-700 hover:shadow-indigo-300 transition-all transform hover:-translate-y-0.5 text-sm">
                  Bayar Sekarang
                </a>
              </div>
              <?php else: ?>
              <div
                class="flex items-center gap-3 text-green-700 bg-green-50 p-4 rounded-2xl border border-green-100 print:border-none print:p-0 print:bg-transparent">
                <div class="bg-white p-1 rounded-full text-green-600"><i class="ti ti-check text-lg"></i></div>
                <span class="font-bold">Pembayaran Lunas</span>
              </div>
              <?php endif; ?>
            </div>
          </div>

        </div>

        <div class="lg:col-span-2 flex flex-col h-full">

          <div class="rounded-2xl border border-slate-200 overflow-hidden mb-8 flex-1">
            <table class="w-full text-sm text-left">
              <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-xs tracking-wider">
                <tr>
                  <th class="px-6 py-4 w-1/2">Produk</th>
                  <th class="px-4 py-4 text-center">Qty</th>
                  <th class="px-4 py-4 text-right">Harga</th>
                  <th class="px-6 py-4 text-right">Total</th>
                </tr>
              </thead>

              <tbody class="divide-y divide-slate-100 bg-white">
                <?php foreach ($items as $item): ?>
                <tr class="hover:bg-slate-50/80 transition-colors">
                  <td class="px-6 py-4">
                    <p class="font-bold text-slate-800 text-base"><?= html_escape($item->product_name); ?></p>
                    <p
                      class="text-xs text-slate-500 mt-1 bg-slate-100 inline-block px-2 py-0.5 rounded border border-slate-200">
                      <?= html_escape($item->product_variant_name); ?>
                    </p>
                  </td>
                  <td class="px-4 py-4 text-center font-medium text-slate-600">x<?= $item->quantity; ?></td>
                  <td class="px-4 py-4 text-right text-slate-600"><?= format_rupiah($item->unit_price); ?></td>
                  <td class="px-6 py-4 text-right font-bold text-slate-800"><?= format_rupiah($item->total_price); ?>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>

          <div
            class="w-full lg:w-2/3 ml-auto bg-slate-50/50 p-6 rounded-[1.5rem] border border-slate-100 print:w-full print:bg-transparent print:border-none">

            <div class="space-y-3 text-sm mb-6 border-b border-dashed border-slate-200 pb-6">
              <div class="flex justify-between text-slate-500">
                <span>Subtotal Produk</span>
                <span class="font-bold text-slate-700"><?= format_rupiah($subtotal_barang); ?></span>
              </div>

              <div class="flex justify-between text-slate-500">
                <span>Ongkos Kirim</span>
                <span class="font-bold text-slate-700"><?= format_rupiah($order->shipping_cost); ?></span>
              </div>

              <?php if ($order->discount_amount > 0): ?>
              <div class="flex justify-between text-green-600 bg-green-50 p-3 rounded-xl border border-green-100">
                <span class="font-bold text-xs flex items-center"><i class="ti ti-ticket mr-2"></i> Diskon
                  (<?= $order->voucher_code ?? 'Promo'; ?>)</span>
                <span class="font-bold">- <?= format_rupiah($order->discount_amount); ?></span>
              </div>
              <?php endif; ?>
            </div>

            <div class="flex justify-between items-end">
              <span class="text-base font-bold text-slate-800">Grand Total</span>
              <span
                class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-blue-600">
                <?= format_rupiah($order->total_amount); ?>
              </span>
            </div>
          </div>

        </div>

      </div>

      <div class="flex justify-center gap-4 print:hidden mt-8 border-t border-dashed border-slate-200 pt-8">
        <a href="<?= site_url('order'); ?>"
          class="px-6 py-3 rounded-xl border border-slate-300 text-slate-600 font-bold hover:bg-slate-50 transition-all flex items-center">
          <i class="ti ti-arrow-left mr-2"></i> Kembali
        </a>
        <button onclick="window.print()"
          class="px-6 py-3 rounded-xl bg-slate-800 text-white font-bold hover:bg-slate-900 shadow-lg hover:shadow-xl transition-all flex items-center">
          <i class="ti ti-printer mr-2"></i> Cetak Invoice
        </button>
      </div>

      <div class="hidden print:block text-center text-[10px] text-slate-400 mt-8">
        Invoice #<?= $order->id; ?> - Dicetak pada <?= date('d/m/Y H:i'); ?> - <?= base_url(); ?>
      </div>

    </div>
  </div>
</div>

<style>
@media print {
  @page {
    margin: 0.5cm;
    size: auto;
  }

  body {
    background-color: white;
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
  }

  .container {
    max-width: 100% !important;
    padding: 0 !important;
  }

  .shadow-xl,
  .shadow-lg {
    box-shadow: none !important;
  }

  .rounded-[2rem],
  .rounded-2xl,
  .rounded-xl {
    border-radius: 0 !important;
  }

  .bg-slate-50,
  .bg-indigo-50,
  .bg-green-50,
  .bg-red-50 {
    background-color: transparent !important;
    border: 1px solid #eee !important;
  }

  .text-white {
    color: black !important;
  }

  .bg-indigo-600 {
    background-color: transparent !important;
    color: black !important;
  }
}
</style>