<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

<div class="bg-slate-50 min-h-screen pb-20 font-sans text-slate-800">

  <div class="container mx-auto px-6 sm:px-6 lg:px-12 max-w-[1600px] py-10">


    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 mb-12">

      <div class="lg:col-span-4 xl:col-span-3">
        <div
          class="bg-white rounded-[2rem] shadow-xl shadow-indigo-100/50 border border-white relative overflow-hidden h-full flex flex-col group">

          <div
            class="absolute top-0 left-0 w-full h-32 bg-gradient-to-br from-indigo-600 to-blue-500 rounded-b-[50%] scale-150 -translate-y-16 transition-transform duration-700 group-hover:scale-175">
          </div>

          <div class="relative z-10 p-6 flex flex-col h-full">
            <div class="flex items-center gap-3 mb-6 text-white">
              <div class="p-2 bg-white/20 backdrop-blur-sm rounded-xl">
                <i class="ti ti-wallet text-2xl"></i>
              </div>
              <h3 class="font-bold text-lg tracking-wide">Top Up & Tagihan</h3>
            </div>

            <div
              class="bg-white rounded-2xl p-5 shadow-lg border border-slate-100 flex-1 flex flex-col justify-center space-y-4">
              <div>
                <label class="text-[10px] font-bold text-slate-500 uppercase ml-1 mb-1 block">Nomor Tujuan</label>
                <div class="relative">
                  <i class="ti ti-device-mobile absolute left-4 top-3.5 text-indigo-500 text-lg"></i>
                  <input type="text" placeholder="08xx-xxxx-xxxx"
                    class="w-full pl-12 pr-4 py-3 bg-slate-50 border-transparent rounded-xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all placeholder-slate-400">
                </div>
              </div>

              <div>
                <label class="text-[10px] font-bold text-slate-500 uppercase ml-1 mb-1 block">Nominal</label>
                <div class="relative">
                  <i class="ti ti-coin absolute left-4 top-3.5 text-indigo-500 text-lg"></i>
                  <select
                    class="w-full pl-12 pr-4 py-3 bg-slate-50 border-transparent rounded-xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all text-slate-700 cursor-pointer appearance-none">
                    <option>Pilih Nominal</option>
                    <option>Rp 5.000</option>
                    <option>Rp 10.000</option>
                    <option>Rp 20.000</option>
                    <option>Rp 50.000</option>
                  </select>
                  <i class="ti ti-chevron-down absolute right-4 top-3.5 text-slate-400 text-sm pointer-events-none"></i>
                </div>
              </div>

              <button
                class="w-full bg-slate-900 text-white font-bold py-3.5 rounded-xl shadow-lg hover:bg-indigo-600 hover:shadow-indigo-300 transition-all duration-300 transform hover:-translate-y-1 mt-auto">
                Bayar
              </button>
            </div>
          </div>
        </div>
      </div>

      <div class="lg:col-span-8 xl:col-span-9">
        <div
          class="bg-gradient-to-r from-indigo-50 via-white to-white p-6 rounded-[2rem] shadow-sm border border-indigo-50 h-full flex flex-col">

          <div class="flex items-center justify-between mb-6">
            <h3 class="font-black text-xl text-slate-800 flex items-center">
              <span class="mr-2 text-2xl">🔥</span> Pilihan Terlaris
            </h3>
            <div class="hidden md:flex gap-2">
              <button
                class="p-1.5 rounded-full bg-white border border-slate-200 text-slate-500 hover:text-indigo-600 hover:border-indigo-600 transition-colors"><i
                  class="ti ti-chevron-left"></i></button>
              <button
                class="p-1.5 rounded-full bg-white border border-slate-200 text-slate-500 hover:text-indigo-600 hover:border-indigo-600 transition-colors"><i
                  class="ti ti-chevron-right"></i></button>
            </div>
          </div>

          <div
            class="flex gap-4 overflow-x-auto pb-6 -mx-2 px-2 scrollbar-hide scroll-smooth snap-x snap-mandatory flex-1 items-center">
            <?php if (!empty($recommended_products)): ?>
            <?php foreach ($recommended_products as $prod): ?>

            <div
              class="min-w-[160px] md:min-w-[180px] bg-white rounded-2xl shadow-sm border border-slate-100 p-2.5 snap-start transform transition-all duration-300 hover:-translate-y-1 hover:shadow-md hover:border-indigo-200 group">
              <a href="<?= site_url('product/' . $prod->slug) ?>" class="block h-full flex flex-col">
                <div class="relative mb-3 overflow-hidden rounded-xl bg-slate-50 aspect-square">
                  <img src="<?= base_url($prod->main_image_path ?: 'assets/img/placeholder.png'); ?>"
                    alt="<?= html_escape($prod->name) ?>"
                    class="w-full h-full object-cover object-center transition-transform duration-700 group-hover:scale-110 mix-blend-multiply" />

                  <div
                    class="absolute top-2 right-2 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded shadow-sm">
                    Here</div>
                </div>

                <div class="mt-auto">
                  <h4
                    class="text-sm font-bold text-slate-800 line-clamp-2 mb-1 group-hover:text-indigo-600 transition-colors leading-tight h-9">
                    <?= html_escape($prod->name) ?>
                  </h4>

                  <div class="flex flex-col mt-1">
                    <span class="text-[10px] text-slate-400 line-through">Rp
                      <?= number_format($prod->price * 1.1, 0, ',', '.'); ?></span>
                    <span class="text-base font-black text-indigo-600"><?= format_rupiah($prod->price) ?></span>
                  </div>

                  <div class="flex items-center justify-between mt-2 pt-2 border-t border-dashed border-slate-100">
                    <div class="flex items-center text-yellow-500 text-xs font-bold">
                      <i class="ti ti-star-filled mr-0.5"></i> 4.9
                    </div>
                    <div class="text-[10px] font-bold text-slate-400">
                      <?= $prod->sold_count ?? 0; ?> Terjual
                    </div>
                  </div>
                </div>
              </a>
            </div>

            <?php endforeach; ?>
            <?php else: ?>
            <div class="w-full text-center py-8 text-slate-400">
              Belum ada rekomendasi.
            </div>
            <?php endif; ?>
          </div>

        </div>
      </div>
    </div>

    <div class="mt-12">
      <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
          <div class="bg-slate-900 text-white p-2 rounded-lg shadow-md">
            <i class="ti ti-layout-grid text-lg"></i>
          </div>
          <h2 class="text-xl md:text-2xl font-black text-slate-800">
            Jelajahi Produk
          </h2>
        </div>
      </div>

      <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 md:gap-6">
        <?php if (!empty($all_products)): ?>
        <?php foreach ($all_products as $prod): ?>

        <div
          class="group bg-white rounded-2xl border border-slate-100 p-2.5 hover:shadow-xl hover:shadow-indigo-50/50 hover:border-indigo-200 transition-all duration-300 relative flex flex-col h-full">

          <a href="<?= site_url('product/' . $prod->slug) ?>" class="block flex-1 flex flex-col">
            <div class="relative aspect-square bg-slate-50 rounded-xl overflow-hidden mb-3">
              <img src="<?= base_url($prod->main_image_path ?: 'assets/img/placeholder.png') ?>"
                alt="<?= html_escape($prod->name) ?>"
                class="w-full h-full object-cover object-center transition-transform duration-500 group-hover:scale-110 mix-blend-multiply" />

              <div
                class="absolute bottom-2 right-2 translate-y-8 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300">
                <button
                  class="w-7 h-7 bg-white text-indigo-600 rounded-full shadow-sm flex items-center justify-center hover:bg-indigo-600 hover:text-white text-sm">
                  <i class="ti ti-plus"></i>
                </button>
              </div>
            </div>

            <div class="flex-1 flex flex-col">
              <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-0.5 truncate">
                <?= $prod->category_name ?? 'UMUM'; ?>
              </div>

              <h3
                class="text-sm font-bold text-slate-800 leading-tight line-clamp-2 mb-2 group-hover:text-indigo-600 transition-colors min-h-[2.5rem]">
                <?= html_escape($prod->name) ?>
              </h3>

              <div class="mt-auto">
                <span class="text-base md:text-lg font-black text-slate-900"><?= format_rupiah($prod->price) ?></span>

                <div class="flex items-center gap-2 text-[10px] text-slate-500 pt-2 border-t border-slate-50 mt-1">
                  <span class="flex items-center text-yellow-500 font-bold">
                    <i class="ti ti-star-filled mr-0.5"></i> 4.8
                  </span>
                  <span>• <?= $prod->sold_count ?? 0; ?> Terjual</span>
                </div>
              </div>
            </div>
          </a>
        </div>

        <?php endforeach; ?>
        <?php else: ?>
        <div class="col-span-full py-16 text-center bg-slate-50 rounded-3xl border border-dashed border-slate-300">
          <i class="ti ti-search text-3xl text-slate-300 mb-2"></i>
          <p class="text-slate-500 font-medium text-sm">Tidak ada produk.</p>
        </div>
        <?php endif; ?>
      </div>

      <div class="mt-10 text-center">
        <a href="<?= site_url('home/search?q=d'); ?>" class="px-6 py-2.5 bg-white border-2 border-slate-200 text-slate-600 font-bold rounded-full 
          hover:border-indigo-600 hover:text-indigo-600 hover:bg-indigo-50 transition-all text-sm inline-block">
          Muat Lebih Banyak
        </a>

      </div>
    </div>

  </div>
</div>

<style>
.scrollbar-hide::-webkit-scrollbar {
  display: none;
}

.scrollbar-hide {
  -ms-overflow-style: none;
  scrollbar-width: none;
}
</style>