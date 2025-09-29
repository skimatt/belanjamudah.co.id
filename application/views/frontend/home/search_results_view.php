<div class="container mx-auto px-4 py-6">
  <div class="grid grid-cols-12 gap-6">

    <!-- Sidebar Filter -->
    <aside class="hidden lg:block lg:col-span-3 space-y-4">
      <div
        class="bg-white p-4 rounded-xl shadow-md transform transition-all duration-300 hover:shadow-2xl hover:bg-gradient-to-br hover:from-gray-50 hover:to-blue-50 relative z-10">
        <h3 class="font-bold text-gray-800 mb-2 relative overflow-hidden">
          Jenis Toko
          <span class="absolute bottom-0 left-0 w-16 h-1 bg-indigo-500 transition-all duration-500 hover:w-full"></span>
        </h3>
        <ul class="space-y-2 text-sm">
          <li>
            <label class="flex items-center cursor-pointer transition-all duration-300 hover:text-indigo-600">
              <input type="checkbox"
                class="mr-2 h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded z-20">
              Mall
            </label>
          </li>
          <li>
            <label class="flex items-center cursor-pointer transition-all duration-300 hover:text-indigo-600">
              <input type="checkbox"
                class="mr-2 h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded z-20">
              Power Shop
            </label>
          </li>
        </ul>
      </div>

      <div
        class="bg-white p-4 rounded-xl shadow-md transform transition-all duration-300 hover:shadow-2xl hover:bg-gradient-to-br hover:from-gray-50 hover:to-blue-50 relative z-10">
        <h3 class="font-bold text-gray-800 mb-2 relative overflow-hidden">
          Lokasi
          <span class="absolute bottom-0 left-0 w-16 h-1 bg-indigo-500 transition-all duration-500 hover:w-full"></span>
        </h3>
        <ul class="space-y-2 text-sm">
          <li>
            <label class="flex items-center cursor-pointer transition-all duration-300 hover:text-indigo-600">
              <input type="checkbox"
                class="mr-2 h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded z-20">
              Jakarta
            </label>
          </li>
          <li>
            <label class="flex items-center cursor-pointer transition-all duration-300 hover:text-indigo-600">
              <input type="checkbox"
                class="mr-2 h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded z-20">
              Bandung
            </label>
          </li>
          <li>
            <label class="flex items-center cursor-pointer transition-all duration-300 hover:text-indigo-600">
              <input type="checkbox"
                class="mr-2 h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded z-20">
              Surabaya
            </label>
          </li>
        </ul>
      </div>
    </aside>

    <!-- Konten Produk -->
    <main class="col-span-12 lg:col-span-9">
      <h1 class="text-2xl font-bold text-gray-800 mb-4 relative overflow-hidden">
        Hasil Pencarian
        <span class="absolute bottom-0 left-0 w-24 h-1 bg-indigo-500 transition-all duration-500 hover:w-full"></span>
      </h1>

      <div
        class="bg-white p-4 rounded-xl shadow-md mb-6 transform transition-all duration-300 hover:shadow-2xl hover:bg-gradient-to-br hover:from-gray-50 hover:to-blue-50 relative z-10">
        <p class="text-sm text-gray-600">
          Menampilkan <span class="font-bold"><?= count($products); ?></span> produk untuk kata kunci:
          <span
            class="text-indigo-600 font-extrabold transform transition-transform duration-300 hover:scale-105">"<?= html_escape($search_query); ?>"</span>
        </p>
      </div>

      <section>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
          <?php if (!empty($products)): ?>
          <?php foreach ($products as $product): ?>
          <div
            class="bg-white rounded-lg overflow-hidden border transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:bg-gradient-to-t hover:from-indigo-50 hover:to-white relative z-20">
            <a href="<?= site_url('product/' . $product->slug); ?>" class="block relative z-30">
              <img src="<?= base_url($product->main_image_path ?: 'assets/img/placeholder.png'); ?>"
                alt="<?= html_escape($product->name); ?>"
                class="w-full h-36 object-cover object-center transition-transform duration-500 hover:scale-110">
              <div
                class="absolute inset-0 border-2 border-transparent hover:border-indigo-300 transition-all duration-300 rounded-lg pointer-events-none">
              </div>
            </a>
            <div class="p-2">
              <h3 class="text-xs font-semibold line-clamp-2 h-8 mb-1">
                <a href="<?= site_url('product/' . $product->slug); ?>"
                  class="hover:text-indigo-600 transition-colors duration-300">
                  <?= html_escape($product->name); ?>
                </a>
              </h3>
              <p class="text-sm font-bold text-red-600 transform transition-transform duration-300 hover:scale-105">
                <?= format_rupiah($product->price); ?>
              </p>

              <!-- tampilkan rating jika ada -->
              <?php if (!empty($product->rating)): ?>
              <p class="text-[11px] text-gray-500 flex items-center">
                <span class="text-yellow-500 mr-1">⭐</span> <?= $product->rating; ?>
              </p>
              <?php endif; ?>

              <!-- tampilkan jumlah terjual jika ada -->
              <?php if (!empty($product->sold_count)): ?>
              <p class="text-[11px] text-gray-400 animate-pulse"><?= $product->sold_count; ?> terjual</p>
              <?php endif; ?>
            </div>
          </div>
          <?php endforeach; ?>
          <?php else: ?>
          <p
            class="text-center col-span-5 text-red-500 font-medium p-6 bg-white rounded-lg transform transition-all duration-300 hover:shadow-md">
            Tidak ada produk ditemukan.
          </p>
          <?php endif; ?>
        </div>
      </section>
    </main>

  </div>
</div>

<style>
/* Menyembunyikan scrollbar untuk pengalaman pengguna yang lebih baik */
.scrollbar-hide::-webkit-scrollbar {
  display: none;
}

.scrollbar-hide {
  -ms-overflow-style: none;
  scrollbar-width: none;
}

/* Penambahan CSS untuk shadow yang lebih halus */
.hover\:shadow-2xl {
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

/* Animasi pulse untuk teks kecil */
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

/* Pastikan elemen interaktif tetap bisa diklik */
button,
input,
select,
a,
label {
  position: relative;
  pointer-events: auto;
}

/* Mengatasi masalah overlap pada transform */
.transform {
  will-change: transform;
}
</style>