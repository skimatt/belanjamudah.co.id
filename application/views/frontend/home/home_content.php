<div class="container mx-auto px-4">

  <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

    <!-- Top Up & Tagihan -->
    <div class="lg:col-span-3 space-y-6">
      <section
        class="bg-gradient-to-br from-indigo-50 to-blue-50 p-5 rounded-2xl shadow-md hover:shadow-2xl transform transition-all duration-300 hover:scale-[1.02] relative overflow-hidden min-h-[340px] flex flex-col justify-between">

        <h3 class="font-bold text-gray-800 mb-5 border-b pb-2 flex items-center gap-2">
          <i class="ti ti-wallet text-indigo-600 text-lg"></i>
          Top Up & Tagihan
        </h3>
        <div class="space-y-3 flex-1">
          <div class="relative">
            <i class="ti ti-device-mobile absolute left-3 top-2.5 text-gray-400 text-sm"></i>
            <input type="text" placeholder="Nomor Telepon/ID Pelanggan"
              class="w-full pl-10 border-gray-300 rounded-lg py-2 px-3 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300 hover:shadow-md focus:shadow-lg">
          </div>
          <div class="relative">
            <i class="ti ti-currency-rupiah absolute left-3 top-2.5 text-gray-400 text-sm"></i>
            <select
              class="w-full pl-10 border-gray-300 rounded-lg py-2 px-3 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300 hover:shadow-md focus:shadow-lg">
              <option>Pilih Nominal</option>
              <option>Rp 5.000</option>
            </select>
          </div>
          <button
            class="w-full bg-gradient-to-r from-green-600 to-green-700 text-white font-semibold py-2.5 rounded-lg shadow-md hover:from-green-700 hover:to-green-800 hover:shadow-xl transition-all duration-300 focus:outline-none">
            Beli Sekarang
          </button>
        </div>
      </section>
    </div>

    <!-- Rekomendasi Produk -->
    <div class="lg:col-span-9">
      <section
        class="bg-white p-4 rounded-xl shadow-md transform transition-all duration-300 hover:shadow-2xl hover:bg-gradient-to-br hover:from-gray-50 hover:to-blue-50 relative z-10">
        <h3 class="font-bold text-gray-800 mb-4 border-b pb-2 relative overflow-hidden">
          🔥 Rekomendasi Produk Pilihan
          <span class="absolute bottom-0 left-0 w-24 h-1 bg-red-500 transition-all duration-500 hover:w-full"></span>
        </h3>

        <div class="flex space-x-4 overflow-x-auto pb-2 scrollbar-hide scroll-smooth snap-x snap-mandatory">
          <?php if (!empty($recommended_products)): ?>
          <?php foreach ($recommended_products as $prod): ?>
          <div
            class="min-w-[150px] bg-white rounded-lg shadow p-3 flex-shrink-0 transform transition-all duration-300 hover:scale-110 hover:shadow-2xl hover:bg-gradient-to-t hover:from-indigo-50 hover:to-white relative z-20 overflow-hidden snap-start">
            <a href="<?= site_url('product/' . $prod->slug) ?>" class="block relative z-30">
              <img src="<?= base_url($prod->main_image_path ?: 'assets/img/placeholder.png'); ?>"
                alt="<?= html_escape($prod->name) ?>"
                class="w-full h-32 object-cover rounded transition-transform duration-500 hover:scale-105" />
              <p
                class="mt-2 text-sm font-medium text-gray-700 h-10 overflow-hidden transition-colors duration-300 hover:text-indigo-600">
                <?= html_escape($prod->name) ?></p>
              <p class="text-red-600 font-bold text-lg transition-transform duration-300 hover:scale-105">
                <?= format_rupiah($prod->price) ?></p>
            </a>

            <div class="flex items-center justify-between text-xs text-gray-500 mt-1">
              <div class="flex items-center text-yellow-500">
                <i class="ti ti-star-filled text-xs"></i>
                <span class="ml-1 font-semibold text-gray-800">4.5</span>
              </div>
              <span class="text-xs">
                Terjual <strong class="text-green-600"><?= $prod->sold_count ?? 0; ?></strong>
              </span>
            </div>

            <div
              class="absolute inset-0 border-2 border-transparent hover:border-indigo-300 transition-all duration-300 rounded-lg pointer-events-none">
            </div>
          </div>
          <?php endforeach; ?>
          <?php else: ?>
          <p class="text-sm text-gray-500">Belum ada rekomendasi.</p>
          <?php endif; ?>
        </div>
        <p class="text-xs text-gray-500 mt-2 animate-pulse">Geser ke samping untuk melihat lebih banyak.</p>
      </section>
    </div>
  </div>

  <!-- Semua Produk -->
  <div class="mt-6">
    <section
      class="bg-white p-6 rounded-xl shadow-md transform transition-all duration-300 hover:shadow-2xl hover:bg-gradient-to-br hover:from-gray-50 hover:to-blue-50 relative z-10">
      <h3 class="font-bold text-xl text-gray-800 mb-4 border-b pb-2 relative overflow-hidden">
        Semua Produk (Jelajahi)
        <span class="absolute bottom-0 left-0 w-32 h-1 bg-indigo-500 transition-all duration-500 hover:w-full"></span>
      </h3>

      <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6">
        <?php if (!empty($all_products)): ?>
        <?php foreach ($all_products as $prod): ?>
        <div
          class="bg-white rounded-lg p-3 transform transition-all duration-300 hover:scale-105 hover:shadow-xl hover:bg-gradient-to-t hover:from-indigo-50 hover:to-white relative z-20 overflow-hidden">
          <a href="<?= site_url('product/' . $prod->slug) ?>" class="block relative z-30">
            <img src="<?= base_url($prod->main_image_path ?: 'assets/img/placeholder.png') ?>"
              alt="<?= html_escape($prod->name) ?>"
              class="w-full h-32 object-cover rounded transition-transform duration-500 hover:scale-105" />
            <p
              class="mt-2 text-sm font-medium text-gray-700 h-10 overflow-hidden transition-colors duration-300 hover:text-indigo-600">
              <?= html_escape($prod->name) ?></p>
            <p class="text-red-600 font-bold transition-transform duration-300 hover:scale-105">
              <?= format_rupiah($prod->price) ?></p>
          </a>

          <div class="flex items-center justify-between text-xs text-gray-500 mt-1">
            <div class="flex items-center text-yellow-500">
              <i class="ti ti-star-filled text-xs"></i>
              <span class="ml-1 font-semibold text-gray-800">4.5</span>
            </div>
            <span class="text-xs">
              Terjual <strong class="text-green-600"><?= $prod->sold_count ?? 0; ?></strong>
            </span>
          </div>

          <div
            class="absolute inset-0 border-2 border-transparent hover:border-indigo-300 transition-all duration-300 rounded-lg pointer-events-none">
          </div>
        </div>
        <?php endforeach; ?>
        <?php else: ?>
        <p class="text-sm text-gray-500">Belum ada produk untuk ditampilkan.</p>
        <?php endif; ?>
      </div>
    </section>
  </div>
</div>

<style>
/* Hide scrollbar */
.scrollbar-hide::-webkit-scrollbar {
  display: none;
}

.scrollbar-hide {
  -ms-overflow-style: none;
  scrollbar-width: none;
}

/* Shadow halus */
.hover\:shadow-2xl {
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

/* Animasi pulse */
@keyframes pulse {

  0%,
  100% {
    opacity: 1;
  }

  50% {
    opacity: 0.7;
  }
}

.animate-pulse {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* Interaktif tetap klikable */
button,
input,
select,
a {
  position: relative;
  pointer-events: auto;
}

/* Optimalkan transform */
.transform {
  will-change: transform;
}
</style>