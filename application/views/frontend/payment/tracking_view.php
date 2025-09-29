<div class="container mx-auto px-4 py-8">
  <div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-lg border-t-8 border-teal-500">

    <h1 class="text-3xl font-bold text-gray-800 mb-2">Lacak Pesanan Anda</h1>
    <p class="text-lg text-gray-600 mb-6">Order ID: <span
        class="font-extrabold text-indigo-600">#<?= $order->id; ?></span></p>

    <div class="mb-8 p-4 bg-gray-50 rounded-lg border">
      <div class="flex justify-between items-center text-sm font-medium">
        <span>Kurir: <span class="font-bold"><?= html_escape($order->shipping_courier); ?></span></span>
        <span>Tanggal Order: <?= date('d M Y', strtotime($order->created_at)); ?></span>
      </div>
      <p class="mt-2">Nomor Resi:
        <span class="font-semibold text-indigo-600">
          <?= html_escape($order->tracking_number) ?: 'Menunggu input resi dari Admin'; ?>
        </span>
      </p>
    </div>

    <h3 class="text-xl font-bold text-gray-800 mb-6 border-b pb-2">Riwayat Perjalanan Barang</h3>

    <ol class="relative border-l border-gray-300 ml-4">
      <?php 
                $status_sequence = ['pending', 'paid', 'packing', 'shipped', 'delivered', 'completed'];
                $current_status = $order->order_status;
                $current_index = array_search($current_status, $status_sequence);
                
                // Urutan ditampilkan terbalik (status terbaru di atas)
                for ($i = count($status_sequence) - 1; $i >= 0; $i--) { // Menggunakan syntax {}
                    $status = $status_sequence[$i];
                    $is_done = $i <= $current_index;
            ?>
      <li class="mb-10 ml-6">
        <span
          class="absolute flex items-center justify-center w-6 h-6 rounded-full -left-3 ring-4 ring-white <?= $is_done ? 'bg-green-600' : 'bg-gray-300'; ?>">
          <i class="ti ti-check w-4 h-4 text-white"></i>
        </span>

        <h3 class="flex items-center mb-1 text-lg font-bold text-gray-900">
          <?= strtoupper($status); ?>
          <?php if ($i == $current_index): ?>
          <span class="bg-indigo-100 text-indigo-800 text-sm font-medium mr-2 px-2.5 py-0.5 rounded ml-3">STATUS SAAT
            INI</span>
          <?php endif; ?>
        </h3>

        <time class="block mb-2 text-sm font-normal leading-none text-gray-400">
          <?php 
                        // Simulasi waktu: Gunakan waktu order dibuat jika status awal
                        echo $is_done ? date('H:i, d M Y', strtotime($order->created_at)) : 'Menunggu update...';
                    ?>
        </time>

        <p class="text-base font-normal text-gray-700">
          <?php
                        if ($status == 'completed') echo 'Pesanan telah berhasil diselesaikan dan diterima oleh pembeli.';
                        elseif ($status == 'delivered') echo 'Kurir telah mengirimkan barang ke alamat Anda.';
                        elseif ($status == 'shipped') echo 'Pesanan telah diserahkan ke kurir.';
                        elseif ($status == 'packing') echo 'Admin sedang menyiapkan pesanan di gudang.';
                        elseif ($status == 'paid') echo 'Pembayaran telah dikonfirmasi dan diterima.';
                        elseif ($status == 'pending') echo 'Menunggu pembayaran dikonfirmasi.';
                    ?>
        </p>
      </li>
      <?php } // Penutup loop 'for' yang benar ?>
    </ol>

    <div class="mt-10 text-center border-t pt-6">
      <h3 class="font-bold text-lg mb-2">Estimasi Tiba (ETD)</h3>
      <p class="text-xl font-extrabold text-red-600">
        <?= calculate_etd($order->shipping_courier, $order->order_status); ?>
      </p>
      <a href="<?= site_url('order/invoice/' . $order->id); ?>"
        class="text-indigo-600 font-medium hover:underline mt-4 block">Lihat Invoice Lengkap</a>
    </div>
  </div>
</div>