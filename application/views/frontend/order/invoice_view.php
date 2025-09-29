<div class="container mx-auto px-4 py-8">
  <div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-lg border-t-8 border-indigo-600">

    <div class="flex justify-between items-center border-b pb-4 mb-6">
      <h1 class="text-3xl font-extrabold text-indigo-600">INVOICE</h1>
      <div class="text-right">
        <p class="font-semibold text-xl">#<?= $order->id; ?></p>
        <p class="text-sm text-gray-500">Tanggal Order: <?= date('d F Y', strtotime($order->created_at)); ?></p>
      </div>
    </div>

    <div class="grid grid-cols-3 gap-4 mb-8 text-center">
      <div class="p-4 bg-gray-100 rounded-lg">
        <p class="text-sm text-gray-600">Status Pembayaran</p>
        <p class="text-xl font-bold"><?= payment_status_badge($order->payment_status); ?></p>
      </div>
      <div class="p-4 bg-gray-100 rounded-lg">
        <p class="text-sm text-gray-600">Status Pesanan</p>
        <p class="text-xl font-bold"><?= order_status_badge($order->order_status); ?></p>
      </div>
      <div class="p-4 bg-gray-100 rounded-lg">
        <p class="text-sm text-gray-600">Total Tagihan</p>
        <p class="text-xl font-bold text-red-600"><?= format_rupiah($order->total_amount); ?></p>
      </div>
    </div>

    <div class="grid grid-cols-2 gap-8 mb-8">
      <div>
        <h3 class="font-bold text-lg mb-2 border-b">Alamat & Pengiriman</h3>
        <div class="text-sm text-gray-700 leading-relaxed">
          <?= nl2br(html_escape($order->shipping_address)); ?><br>
          Kurir: <span class="font-semibold"><?= html_escape($order->shipping_courier); ?></span><br>
          Resi: <span
            class="font-semibold text-indigo-600"><?= html_escape($order->tracking_number) ?: 'Menunggu input resi Admin'; ?></span>
        </div>
        <?php if (!empty($order->notes)): ?>
        <div class="mt-3 p-2 bg-gray-50 border rounded-lg text-xs text-gray-600">
          Catatan: <?= html_escape($order->notes); ?>
        </div>
        <?php endif; ?>
      </div>
      <div>
        <h3 class="font-bold text-lg mb-2 border-b">Metode Pembayaran</h3>
        <div class="text-sm text-gray-700 leading-relaxed">
          Metode: <span class="font-semibold"><?= html_escape($order->payment_method); ?></span><br>
          <?php if ($order->payment_status !== 'paid'): ?>
          <p class="text-red-600 mt-2 font-semibold">Status: Pembayaran Belum Diterima.</p>
          <a href="<?= site_url('payment/index/' . $order->id); ?>"
            class="text-indigo-600 font-medium hover:underline text-xs block mt-1">Lanjut ke Instruksi Pembayaran
            &rarr;</a>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <h3 class="font-bold text-lg mb-2 border-b pb-2">Rincian Belanja</h3>
    <table class="min-w-full table-auto mb-8">
      <thead class="border-b-2">
        <tr>
          <th class="px-4 py-2 text-left text-sm font-medium">Item</th>
          <th class="px-4 py-2 text-left text-sm font-medium">Harga Satuan</th>
          <th class="px-4 py-2 text-left text-sm font-medium">Qty</th>
          <th class="px-4 py-2 text-right text-sm font-medium">Subtotal</th>
        </tr>
      </thead>
      <tbody>
        <?php $subtotal_barang = 0; foreach ($items as $item): $subtotal_barang += $item->total_price; ?>
        <tr class="border-b">
          <td class="px-4 py-3">
            <p class="font-semibold"><?= html_escape($item->product_name); ?></p>
            <p class="text-xs text-gray-500">Varian: <?= html_escape($item->product_variant_name); ?></p>
          </td>
          <td class="px-4 py-3 text-sm"><?= format_rupiah($item->unit_price); ?></td>
          <td class="px-4 py-3 text-sm"><?= $item->quantity; ?></td>
          <td class="px-4 py-3 text-right text-sm"><?= format_rupiah($item->total_price); ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="3" class="px-4 py-2 text-right font-semibold">Subtotal Barang</td>
          <td class="px-4 py-2 text-right font-bold"><?= format_rupiah($subtotal_barang); ?></td>
        </tr>
        <tr>
          <td colspan="3" class="px-4 py-2 text-right font-semibold">Biaya Kirim</td>
          <td class="px-4 py-2 text-right"><?= format_rupiah($order->shipping_cost); ?></td>
        </tr>

        <?php if ($order->discount_amount > 0): ?>
        <tr>
          <td colspan="3" class="px-4 py-2 text-right font-semibold text-green-600">
            Diskon Voucher (<?= $order->voucher_code ?? 'Voucher'; ?>)
          </td>
          <td class="px-4 py-2 text-right font-bold text-green-600">
            - <?= format_rupiah($order->discount_amount); ?>
          </td>
        </tr>
        <?php endif; ?>
        <tr>
          <td colspan="3" class="px-4 py-2 text-right font-extrabold text-xl">TOTAL TAGIHAN</td>
          <td class="px-4 py-2 text-right font-extrabold text-red-600 text-xl">
            <?= format_rupiah($order->total_amount); ?>
          </td>
        </tr>
      </tfoot>
    </table>

    <div class="mt-8 text-center space-x-4">
      <a href="<?= site_url('order'); ?>"
        class="btn bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 font-medium">Riwayat Pesanan</a>
      <button onclick="window.print()"
        class="btn bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 font-medium">Cetak Invoice</button>
    </div>
  </div>
</div>