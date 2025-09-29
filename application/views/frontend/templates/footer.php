<footer class="mt-12 py-10 bg-white border-t border-gray-200 shadow-md">
  <div
    class="container mx-auto px-4 flex flex-col md:flex-row justify-between items-center text-sm text-gray-500 space-y-6 md:space-y-0">

    <div class="flex flex-col items-center md:items-start space-y-4">
      <a href="<?= site_url(); ?>" class="flex items-center space-x-2 text-2xl font-bold text-blue-600">
        <img src="<?= base_url('assets/img/logo.png'); ?>" alt="Logo Toko" class="h-8">
        <span>RahmatMulia</span>
      </a>

      <nav class="space-x-6 text-base font-medium">
        <a href="#" class="text-gray-600 hover:text-blue-600 transition-colors">Tentang Kami</a>
        <a href="#" class="text-gray-600 hover:text-blue-600 transition-colors">Bantuan</a>
        <a href="#" class="text-gray-600 hover:text-blue-600 transition-colors">Syarat & Ketentuan</a>
      </nav>
    </div>

    <div class="text-center md:text-right space-y-2">
      <p class="text-sm font-semibold text-gray-700">Metode Pembayaran Aman:</p>
      <div class="flex justify-center md:justify-end space-x-3 text-2xl text-blue-600">
        <i class="ti ti-credit-card"></i>
        <i class="ti ti-currency-dollar"></i>
        <i class="ti ti-brand-visa"></i>
      </div>

      <p class="text-xs text-gray-500 pt-2">
        &copy; <?= date('Y'); ?> BelanjaMudah.id. Hak Cipta Dilindungi.
      </p>
      <p class="text-xs text-gray-400">
        Versi Produksi: <?= CI_VERSION; ?> | PHP/MySQL
      </p>
    </div>
  </div>
</footer>

<script>
// --- FUNGSI AUTOSLIDE HERO BANNER (2 detik sekali) ---
$(document).ready(function() {
  const sliderTrack = $('#slider-track');
  if (sliderTrack.length) {
    const totalSlides = sliderTrack.children().length;
    let currentSlide = 0;
    const slideInterval = setInterval(() => {
      const slideWidth = sliderTrack.parent().width();
      currentSlide = (currentSlide + 1) % totalSlides;
      const offset = -currentSlide * slideWidth;
      sliderTrack.css('transform', `translateX(${offset}px)`);
    }, 2000); // 2000 ms = 2 detik
  }

  // --- Tambahkan logika AJAX Keranjang di sini jika perlu ---
});
</script>