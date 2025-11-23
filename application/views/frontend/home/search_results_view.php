<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
  rel="stylesheet">

<div class="bg-slate-50 min-h-screen pb-20 font-sans text-slate-800">

  <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-[1600px] py-8">

    <!-- <div class="mb-8">
      <nav class="flex text-sm text-slate-500 mb-4">
        <a href="<?= site_url(); ?>" class="hover:text-indigo-600 transition-colors">Beranda</a>
        <span class="mx-2">/</span>
        <span class="text-slate-800 font-bold">Pencarian</span>
      </nav>

      <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
          <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-1">Hasil Pencarian</p>
          <h1 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight">
            "<?= html_escape($search_query); ?>"
          </h1>
        </div>
        <div class="text-slate-500 font-medium">
          Ditemukan <span class="font-bold text-indigo-600"><?= count($products); ?></span> produk
        </div>
      </div>
    </div> -->

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">


      <aside class="hidden lg:block lg:col-span-3 xl:col-span-2 sticky top-24">
        <div class="bg-white rounded-[1.5rem] shadow-sm border border-slate-200 p-5">
          <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest mb-4 flex items-center">
            <i class="ti ti-category mr-2 text-indigo-600"></i> Kategori
          </h3>

          <ul class="space-y-1">
            <?php foreach ($categories as $cat): 
                            $isActive = (isset($current_category) && $current_category->id == $cat->id);
                        ?>
            <li>
              <a href="<?= site_url('category/' . $cat->slug); ?>" class="flex items-center justify-between px-4 py-3 rounded-xl text-sm font-bold transition-all duration-200 group
                               <?= $isActive 
                                    ? 'bg-indigo-600 text-white shadow-md shadow-indigo-200' 
                                    : 'text-slate-500 hover:bg-indigo-50 hover:text-indigo-600'; ?>">

                <span><?= html_escape($cat->name); ?></span>

                <?php if ($isActive): ?>
                <i class="ti ti-check"></i>
                <?php else: ?>
                <i class="ti ti-chevron-right opacity-0 group-hover:opacity-100 transition-opacity text-xs"></i>
                <?php endif; ?>
              </a>
            </li>
            <?php endforeach; ?>
          </ul>

          <div
            class="mt-6 bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl p-5 text-white relative overflow-hidden group">
            <div class="relative z-10">
              <p class="text-[10px] font-bold text-yellow-400 uppercase mb-1">Member Only</p>
              <h4 class="font-bold text-lg leading-tight mb-3">Diskon Ekstra <br>Minggu Ini</h4>
              <button
                class="text-xs font-bold bg-white/20 hover:bg-white hover:text-slate-900 px-3 py-1.5 rounded-lg transition-colors backdrop-blur-sm">Cek
                Promo</button>
            </div>
            <i
              class="ti ti-gift text-6xl absolute -bottom-2 -right-2 text-white/10 rotate-12 group-hover:scale-110 transition-transform"></i>
          </div>
        </div>
      </aside>


      <div class="lg:col-span-9 xl:col-span-10">

        <div
          class="flex flex-wrap items-center justify-between mb-6 gap-4 bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
          <div class="flex items-center gap-2">
            <span class="text-sm font-bold text-slate-500">Urutkan:</span>
            <select
              class="border-none text-sm font-bold text-slate-800 focus:ring-0 bg-slate-50 rounded-lg py-1.5 pl-3 pr-8 cursor-pointer hover:bg-indigo-50 transition-colors">
              <option>Paling Sesuai</option>
              <option>Terbaru</option>
              <option>Harga Terendah</option>
              <option>Harga Tertinggi</option>
            </select>
          </div>
          <div class="flex items-center gap-2">
            <button class="p-2 rounded-lg bg-indigo-600 text-white shadow-md shadow-indigo-200"><i
                class="ti ti-layout-grid"></i></button>
            <button class="p-2 rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-colors"><i
                class="ti ti-list"></i></button>
          </div>
        </div>

        <?php if (!empty($products)): ?>
        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-6">
          <?php foreach ($products as $prod): ?>

          <div
            class="group bg-white rounded-[1.5rem] border border-slate-100 p-3 hover:border-indigo-200 hover:shadow-xl hover:shadow-indigo-100/50 transition-all duration-300 relative flex flex-col h-full">

            <a href="<?= site_url('product/' . $prod->slug); ?>" class="block flex-1 flex flex-col">
              <div class="relative aspect-square bg-slate-50 rounded-2xl overflow-hidden mb-3">
                <img src="<?= base_url($prod->main_image_path ?: 'assets/img/placeholder.png'); ?>"
                  alt="<?= html_escape($prod->name); ?>"
                  class="w-full h-full object-cover object-center transition-transform duration-500 group-hover:scale-110 mix-blend-multiply" />

                <div
                  class="absolute bottom-0 left-0 w-full p-3 translate-y-full group-hover:translate-y-0 transition-transform duration-300 flex justify-center">
                  <button
                    class="bg-white/90 backdrop-blur-sm text-indigo-600 font-bold text-xs py-2 px-4 rounded-full shadow-lg hover:bg-indigo-600 hover:text-white transition-colors">
                    Lihat Detail
                  </button>
                </div>
              </div>

              <div class="flex-1 flex flex-col px-1">
                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1 truncate">
                  <?= $prod->category_name ?? 'Kategori'; ?>
                </div>

                <h3
                  class="text-sm font-bold text-slate-800 leading-snug line-clamp-2 mb-3 group-hover:text-indigo-600 transition-colors min-h-[2.5rem]">
                  <?= html_escape($prod->name); ?>
                </h3>

                <div class="mt-auto pt-3 border-t border-dashed border-slate-100">
                  <div class="flex items-center justify-between mb-1">
                    <span class="text-lg font-black text-slate-900"><?= format_rupiah($prod->price); ?></span>
                  </div>

                  <div class="flex items-center justify-between text-xs">
                    <div class="flex items-center text-yellow-500 font-bold">
                      <i class="ti ti-star-filled text-[10px] mr-1"></i> 4.8
                    </div>
                    <span class="text-slate-400"><?= $prod->sold_count ?? 0; ?> Terjual</span>
                  </div>
                </div>
              </div>
            </a>
          </div>

          <?php endforeach; ?>
        </div>

        <div class="mt-12 flex justify-center">
          <nav class="flex items-center gap-2 bg-white p-2 rounded-full shadow-sm border border-slate-200">
            <a href="#"
              class="w-10 h-10 flex items-center justify-center rounded-full bg-slate-50 text-slate-400 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
              <i class="ti ti-chevron-left"></i>
            </a>
            <a href="#"
              class="w-10 h-10 flex items-center justify-center rounded-full bg-indigo-600 text-white font-bold shadow-md shadow-indigo-200">1</a>
            <a href="#"
              class="w-10 h-10 flex items-center justify-center rounded-full text-slate-600 font-bold hover:bg-slate-50 transition-colors">2</a>
            <span class="w-10 h-10 flex items-center justify-center text-slate-300">...</span>
            <a href="#"
              class="w-10 h-10 flex items-center justify-center rounded-full text-slate-600 font-bold hover:bg-slate-50 transition-colors">9</a>
            <a href="#"
              class="w-10 h-10 flex items-center justify-center rounded-full bg-slate-50 text-slate-600 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
              <i class="ti ti-chevron-right"></i>
            </a>
          </nav>
        </div>

        <?php else: ?>

        <div class="bg-white rounded-[2.5rem] border border-slate-200 p-12 text-center shadow-sm">
          <div class="w-32 h-32 bg-indigo-50 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="ti ti-search-off text-6xl text-indigo-300"></i>
          </div>
          <h2 class="text-2xl font-black text-slate-800 mb-2">Ups, Produk Tidak Ditemukan</h2>
          <p class="text-slate-500 mb-8 max-w-md mx-auto">
            Kami tidak dapat menemukan produk dengan kata kunci "<strong><?= html_escape($search_query); ?></strong>".
            Coba kata kunci lain atau jelajahi kategori.
          </p>
          <a href="<?= site_url(); ?>"
            class="inline-flex items-center px-8 py-3 bg-slate-900 text-white font-bold rounded-xl hover:bg-indigo-600 transition-all shadow-lg shadow-slate-200">
            Lihat Semua Produk
          </a>
        </div>

        <?php endif; ?>

      </div>
    </div>
  </div>
</div>