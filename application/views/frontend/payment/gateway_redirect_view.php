<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pembayaran - <?= $order->payment_method; ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
  body {
    background-color: #f7fafc;
  }
  </style>
</head>

<body class="flex items-center justify-center min-h-screen">
  <div class="w-full max-w-md bg-white p-8 rounded-xl shadow-2xl text-center">
    <h1 class="text-3xl font-extrabold text-gray-800 mb-4">Pembayaran <?= strtoupper($order->payment_method); ?></h1>

    <p class="text-gray-600 mb-6">Total yang harus dibayar:</p>
    <div class="bg-indigo-50 p-4 rounded-lg mb-6">
      <span class="text-4xl font-extrabold text-red-600"><?= format_rupiah($order->total_amount); ?></span>
    </div>

    <p class="text-sm text-gray-700 mb-8">Anda akan diarahkan ke aplikasi/halaman pembayaran
      <?= strtoupper($order->payment_method); ?>. (Simulasi)</p>

    <a href="<?= site_url('payment/gateway_callback/' . $order->id); ?>"
      class="w-full inline-block bg-green-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-green-700 transition duration-200">
      KLIK UNTUK SIMULASI PEMBAYARAN SUKSES
    </a>

    <a href="<?= site_url('order/invoice/' . $order->id); ?>"
      class="text-sm text-gray-500 hover:text-gray-700 mt-4 block">Kembali ke Invoice</a>
  </div>
</body>

</html>