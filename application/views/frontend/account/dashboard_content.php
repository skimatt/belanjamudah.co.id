<div class="bg-white p-6 rounded-xl shadow-lg">
  <h2 class="text-2xl font-bold text-gray-800 mb-4 border-b pb-2">
    Ringkasan Akun
  </h2>

  <p class="mb-6 text-gray-700">
    Selamat datang kembali, **<?= html_escape($user->full_name); ?>**! Pantau aktivitas pesanan Anda di sini.
  </p>

  <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <div class="p-4 bg-yellow-50 rounded-lg border-l-4 border-yellow-500">
      <p class="font-medium text-yellow-700 text-sm">Menunggu Bayar</p>
      <p class="text-3xl font-extrabold text-yellow-800 mt-1"><?= $summary->pending_payment_count ?? 0; ?></p>
    </div>
    <div class="p-4 bg-indigo-50 rounded-lg border-l-4 border-indigo-500">
      <p class="font-medium text-indigo-700 text-sm">Sedang Diproses</p>
      <p class="text-3xl font-extrabold text-indigo-800 mt-1">
        <?= ($summary->shipped_count ?? 0) + ($summary->pending_payment_count ?? 0); ?></p>
    </div>
    <div class="p-4 bg-blue-50 rounded-lg border-l-4 border-blue-500">
      <p class="font-medium text-blue-700 text-sm">Sudah Dikirim</p>
      <p class="text-3xl font-extrabold text-blue-800 mt-1"><?= $summary->shipped_count ?? 0; ?></p>
    </div>
    <div class="p-4 bg-green-50 rounded-lg border-l-4 border-green-500">
      <p class="font-medium text-green-700 text-sm">Pesanan Selesai</p>
      <p class="text-3xl font-extrabold text-green-800 mt-1"><?= $summary->completed_count ?? 0; ?></p>
    </div>
  </div>

  <h3 class="text-xl font-bold text-gray-800 mb-3 border-b pt-4 pb-2">5 Pesanan Terbaru</h3>

  <div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
        </tr>
      </thead>
      <tbody class="bg-white divide-y divide-gray-200">
        <?php if (!empty($recent_orders)): ?>
        <?php foreach ($recent_orders as $order): ?>
        <tr>
          <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#<?= $order->id; ?></td>
          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
            <?= date('d M Y', strtotime($order->created_at)); ?></td>
          <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-red-600">
            <?= format_rupiah($order->total_amount); ?></td>
          <td class="px-6 py-4 whitespace-nowrap text-sm">
            <?= order_status_badge($order->order_status); ?>
          </td>
          <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
            <a href="<?= site_url('order/invoice/' . $order->id); ?>"
              class="text-indigo-600 hover:text-indigo-900">Lihat Detail</a>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php else: ?>
        <tr>
          <td colspan="5" class="px-6 py-4 text-center text-gray-500">Anda belum memiliki pesanan.</td>
        </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <div class="mt-6 text-right">
    <a href="<?= site_url('order'); ?>" class="text-indigo-600 hover:text-indigo-800 font-medium">Lihat Semua Pesanan
      &rarr;</a>
  </div>
</div>