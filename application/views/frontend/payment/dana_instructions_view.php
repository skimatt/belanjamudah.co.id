<div class="container mx-auto px-4 py-8">
  <div class="max-w-xl mx-auto bg-white p-8 rounded-xl shadow-2xl border-t-8 border-indigo-600">

    <h1 class="text-3xl font-extrabold text-gray-800 mb-2 text-center">
      Pembayaran Berhasil Dibuat
    </h1>
    <p class="text-lg text-gray-600 mb-6 text-center">Order ID: <span
        class="font-extrabold text-indigo-600">#<?= $order->id; ?></span></p>

    <div class="border border-indigo-200 bg-indigo-50 p-4 rounded-lg mb-6">
      <div class="flex justify-between items-center">
        <span class="text-xl text-gray-700 font-semibold">Total Tagihan:</span>
        <span class="text-3xl font-extrabold text-red-600"><?= format_rupiah($order->total_amount); ?></span>
      </div>
      <p class="text-xs text-gray-500 mt-2">Metode: <?= html_escape($order->payment_method); ?></p>
    </div>

    <div class="p-6 bg-blue-50 border-l-4 border-blue-500 rounded-lg">
      <h2 class="text-2xl font-bold text-blue-700 mb-3">
        💳 Segera Selesaikan Pembayaran <?= strtoupper($order->payment_method); ?>
      </h2>

      <p class="text-gray-700 mb-4">
        Pesanan Anda telah dibuat, namun pembayaran masih **PENDING**. Lakukan pembayaran melalui instruksi di bawah:
      </p>

      <ul class="list-disc list-inside text-sm text-gray-800 space-y-2 ml-4">
        <li>Batas Waktu Pembayaran: <span class="font-bold text-red-600">30 Menit</span> (Simulasi)</li>
        <li>Nomor Virtual Account/QR Code: <span class="font-bold">LIHAT DI APLIKASI ANDA</span></li>
      </ul>

      <h3 class="font-semibold text-gray-800 mt-5 mb-2">Langkah Selanjutnya:</h3>
      <ol class="list-decimal list-inside text-sm text-gray-700 space-y-2 ml-4">
        <li>Anda dapat memindai **QR Code** yang ditampilkan atau memasukkan **Virtual Account** ini di aplikasi
          <?= strtoupper($order->payment_method); ?>.</li>
        <li>Setelah pembayaran sukses, status pesanan akan **otomatis terverifikasi** (via *webhook*).</li>
        <li>Anda akan menerima notifikasi email setelah status berubah menjadi PAID.</li>
      </ol>

      <div class="text-center mt-8">
        <a href="<?= site_url('payment/gateway_callback/' . $order->id); ?>"
          class="bg-green-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-700 shadow-md transition duration-200">
          KLIK UNTUK SIMULASI PEMBAYARAN SUKSES
        </a>
      </div>
    </div>

    <div class="mt-8 text-center border-t pt-4">
      <a href="<?= site_url('order/invoice/' . $order->id); ?>"
        class="text-indigo-600 hover:text-indigo-800 font-medium">Lihat Detail Pesanan (Invoice)</a>
    </div>
  </div>
</div>