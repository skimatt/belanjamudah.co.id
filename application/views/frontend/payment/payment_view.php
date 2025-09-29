<div class="container mx-auto px-4 py-8">
  <div class="max-w-xl mx-auto bg-white p-8 rounded-xl shadow-lg">

    <h1 class="text-3xl font-extrabold text-gray-800 mb-6 text-center">Selesaikan Pembayaran</h1>
    <p class="text-center text-gray-600 mb-8">Order ID: <span
        class="font-bold text-indigo-600">#<?= $order->id; ?></span></p>

    <div class="border border-gray-300 p-4 rounded-lg mb-6">
      <div class="flex justify-between items-center">
        <span class="text-lg text-gray-700">Total Tagihan:</span>
        <span class="text-3xl font-extrabold text-red-600"><?= format_rupiah($order->total_amount); ?></span>
      </div>
      <p class="text-xs text-gray-500 mt-2">Metode Pembayaran: <?= html_escape($order->payment_method); ?></p>
    </div>

    <?php if ($order->payment_method === 'COD'): ?>
    <div class="p-6 bg-green-50 border-l-4 border-green-500 rounded-lg">
      <h2 class="text-2xl font-bold text-green-700 mb-3">✅ Pesanan Dikonfirmasi (COD)</h2>
      <p class="text-green-700 mb-4">
        Pesanan Anda telah berhasil dibuat. Anda memilih **Bayar di Tempat (Cash on Delivery)**.
      </p>
      <ul class="list-disc list-inside text-sm text-gray-700 space-y-1 ml-4">
        <li>Mohon siapkan uang tunai sebesar **<?= format_rupiah($order->total_amount); ?>**.</li>
        <li>Kami akan memproses pesanan Anda dan mengirimkannya dalam 1-2 hari kerja.</li>
        <li>Status pesanan akan berubah menjadi 'Paid' setelah kurir mengkonfirmasi penerimaan pembayaran.</li>
      </ul>
      <div class="text-center mt-6">
        <a href="<?= site_url('order/invoice/' . $order->id); ?>"
          class="bg-indigo-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-indigo-700">Lihat Detail
          Pesanan</a>
      </div>
    </div>

    <?php elseif ($order->payment_method === 'TRANSFER_MANUAL'): ?>
    <div class="p-6 bg-yellow-50 border-l-4 border-yellow-500 rounded-lg">
      <h2 class="text-2xl font-bold text-yellow-700 mb-3">⏳ Instruksi Transfer Manual</h2>
      <p class="text-gray-700 mb-4">
        Segera transfer sebesar **<?= format_rupiah($order->total_amount); ?>** ke salah satu rekening berikut:
      </p>
      <div class="space-y-3 font-mono text-sm bg-white p-3 rounded-lg border">
        <p>Bank BCA: **1234567890** (a/n Toko MVP)</p>
        <p>Bank Mandiri: **0987654321** (a/n Toko MVP)</p>
      </div>

      <h3 class="font-semibold text-gray-800 mt-5 mb-2">Konfirmasi Pembayaran</h3>
      <p class="text-sm text-gray-700 mb-3">Setelah transfer, mohon konfirmasi dengan mengirimkan bukti transfer:</p>

      <form action="<?= site_url('payment/confirm_transfer/' . $order->id); ?>" method="POST"
        enctype="multipart/form-data">
        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>"
          value="<?= $this->security->get_csrf_hash(); ?>">
        <div class="mb-3">
          <label for="proof" class="block text-sm font-medium text-gray-700">Upload Bukti Transfer</label>
          <input type="file" name="proof" id="proof"
            class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
            required>
        </div>
        <button type="submit" class="w-full bg-indigo-600 text-white font-semibold py-2 rounded-lg hover:bg-indigo-700">
          Konfirmasi Pembayaran
        </button>
      </form>
    </div>
    <?php else: ?>
    <div class="p-6 bg-blue-50 border-l-4 border-blue-500 rounded-lg">
      <h2 class="text-2xl font-bold text-blue-700 mb-3">💳 Proses Pembayaran Gateway</h2>
      <p class="text-gray-700">Anda akan diarahkan ke halaman Payment Gateway (Midtrans/Xendit) untuk menyelesaikan
        transaksi.</p>
      <button class="w-full bg-blue-600 text-white font-semibold py-2 rounded-lg mt-4">Lanjut ke Midtrans</button>
    </div>
    <?php endif; ?>
  </div>
</div>