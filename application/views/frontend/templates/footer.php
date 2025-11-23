<footer class="mt-12 py-10 bg-[#0046A8] border-t border-blue-800 shadow-xl">
  <div
    class="container mx-auto px-4 flex flex-col md:flex-row justify-between items-center text-sm text-blue-100 space-y-6 md:space-y-0">

    <!-- KIRI -->
    <div class="flex flex-col items-center md:items-start space-y-4">

      <a href="<?= site_url(); ?>" class="flex items-center space-x-2 text-2xl font-bold text-white">
        <img src="<?= base_url('assets/img/logo.png'); ?>" alt="Logo Toko" class="h-8 drop-shadow">
        <span>Rahmat Mulia</span>
      </a>

      <nav class="flex space-x-6 text-base font-medium">
        <a href="#" class="text-blue-100 hover:text-white transition-colors">Tentang Kami</a>
        <a href="#" class="text-blue-100 hover:text-white transition-colors">Bantuan</a>
        <a href="#" class="text-blue-100 hover:text-white transition-colors">Syarat & Ketentuan</a>
      </nav>
    </div>

    <!-- KANAN -->
    <div class="text-center md:text-right space-y-2">

      <p class="text-sm font-semibold text-white">Metode Pembayaran Aman:</p>

      <div class="flex justify-center md:justify-end space-x-4 text-3xl text-white">
        <i class="ti ti-credit-card"></i>
        <i class="ti ti-brand-mastercard"></i>
        <i class="ti ti-brand-visa"></i>
      </div>

      <p class="text-xs text-blue-100 pt-2">
        &copy; <?= date('Y'); ?> BelanjaMudah.co.id — Semua Hak Dilindungi.
      </p>

      <p class="text-xs text-blue-200 opacity-80">
        Versi Produksi: <?= CI_VERSION; ?> | PHP/MySQL
      </p>
    </div>
  </div>
</footer>