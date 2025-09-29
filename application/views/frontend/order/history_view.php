<div class="container mx-auto px-4 py-8">
  <h1 class="text-3xl font-bold text-gray-800 mb-6">Riwayat Pesanan Anda</h1>

  <div class="bg-white p-6 rounded-xl shadow-lg border-t-4 border-indigo-600">
    <div class="overflow-x-auto">
      <table class="min-w-full table-auto">
        <thead class="bg-gray-50 border-b">
          <tr>
            <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">ID Pesanan</th>
            <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Total Tagihan</th>
            <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Kurir & ETD</th>
            <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Status Order</th>
            <th class="px-4 py-3 text-center text-sm font-medium text-gray-500">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($orders)): ?>
          <?php foreach ($orders as $order): ?>
          <tr class="border-b hover:bg-gray-50">

            <td class="px-4 py-3">
              <p class="font-semibold text-indigo-600">#<?= $order->id; ?></p>
              <p class="text-xs text-gray-500">Tgl: <?= date('d M Y', strtotime($order->created_at)); ?></p>
            </td>

            <td class="px-4 py-3">
              <p class="font-bold text-red-600"><?= format_rupiah($order->total_amount); ?></p>
              <p class="text-xs"><?= payment_status_badge($order->payment_status); ?></p>
            </td>

            <td class="px-4 py-3">
              <p class="text-sm font-medium"><?= html_escape($order->shipping_courier); ?></p>
              <p class="text-xs text-gray-500">Tiba:
                <span class="font-semibold">
                  <?= calculate_etd($order->shipping_courier, $order->order_status); ?>
                </span>
              </p>
            </td>

            <td class="px-4 py-3">
              <?= order_status_badge($order->order_status); ?>
            </td>

            <td class="px-4 py-3 text-center space-y-1">
              <a href="<?= site_url('order/invoice/' . $order->id); ?>"
                class="text-indigo-600 hover:text-indigo-800 font-medium text-sm block">Lihat Invoice</a>

              <?php if (in_array($order->order_status, ['packing', 'shipped'])): ?>
              <a href="<?= site_url('payment/tracking/' . $order->id); ?>"
                class="bg-teal-500 text-white hover:bg-teal-600 px-3 py-1 rounded-lg text-xs font-semibold inline-block">
                <i class="ti ti-track"></i> Tracking
              </a>
              <?php endif; ?>
            </td>
          </tr>
          <?php endforeach; ?>
          <?php else: ?>
          <tr>
            <td colspan="5" class="px-4 py-4 text-center text-gray-500">Anda belum memiliki riwayat pesanan.</td>
          </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>