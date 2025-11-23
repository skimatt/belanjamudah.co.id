<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
  rel="stylesheet">

<div class="bg-slate-50 min-h-screen pb-20 font-sans text-slate-800">

  <div class="container mx-auto px-4 sm:px-6 lg:px-12 max-w-[1600px] py-8">

    <div class="mb-8">
      <nav class="flex text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">
        <a href="<?= site_url(); ?>" class="hover:text-indigo-600 transition-colors">Beranda</a>
        <span class="mx-2">/</span>
        <span class="text-indigo-600">Kategori</span>
      </nav>

      <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
          <h1 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight mb-2">
            <?= html_escape($current_category->name ?? 'Semua Produk'); ?>
          </h1>
          <p class="text-slate-500 font-medium">
            Menampilkan <span class="text-slate-900 font-bold"><?= count($products); ?></span> produk pilihan untukmu.
          </p>
        </div>

        <div class="flex items-center gap-2">
          <span class="text-sm font-bold text-slate-500">Urutkan:</span>
          <select
            class="bg-white border border-slate-200 text-slate-700 text-sm font-bold rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 cursor-pointer hover:border-indigo-300 transition-colors">
            <option>Paling Sesuai</option>
            <option>Terbaru</option>
            <option>Harga Terendah</option>
            <option>Harga Tertinggi</option>
          </select>
        </div>
      </div>
    </div>

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
        <?php if (!empty($products)): ?>

        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-4 md:gap-6">
          <?php foreach ($products as $product): ?>

          <div
            class="group bg-white rounded-2xl border border-slate-100 p-2.5 hover:shadow-xl hover:shadow-indigo-50/50 hover:border-indigo-200 transition-all duration-300 relative flex flex-col h-full">

            <a href="<?= site_url('product/' . $product->slug); ?>" class="block flex-1 flex flex-col">
              <div class="relative aspect-square bg-slate-50 rounded-xl overflow-hidden mb-3">
                <img src="<?= base_url($product->main_image_path ?: 'assets/img/placeholder.png'); ?>"
                  alt="<?= html_escape($product->name); ?>"
                  class="w-full h-full object-cover object-center transition-transform duration-500 group-hover:scale-110 mix-blend-multiply" />

                <div
                  class="absolute bottom-2 right-2 translate-y-8 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300">
                  <button
                    class="w-8 h-8 bg-white text-indigo-600 rounded-full shadow-md flex items-center justify-center hover:bg-indigo-600 hover:text-white text-sm transition-colors">
                    <i class="ti ti-plus"></i>
                  </button>
                </div>
              </div>

              <div class="flex-1 flex flex-col px-1">
                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-0.5 truncate">
                  <?= html_escape($current_category->name ?? 'Umum'); ?>
                </div>

                <h3
                  class="text-sm font-bold text-slate-800 leading-snug line-clamp-2 mb-2 group-hover:text-indigo-600 transition-colors min-h-[2.5rem]">
                  <?= html_escape($product->name); ?>
                </h3>

                <div class="mt-auto">
                  <span class="text-base md:text-lg font-black text-slate-900 block mb-1">
                    <?= format_rupiah($product->price); ?>
                  </span>

                  <div class="flex items-center gap-2 text-[10px] text-slate-500 pt-2 border-t border-slate-50">
                    <span class="flex items-center text-yellow-500 font-bold">
                      <i class="ti ti-star-filled mr-0.5"></i> <?= $product->rating ?? '4.8'; ?>
                    </span>
                    <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                    <span><?= $product->sold_count ?? 0; ?> Terjual</span>
                  </div>
                </div>
              </div>
            </a>
          </div>

          <?php endforeach; ?>
        </div>

        <div class="mt-12 flex justify-center">
          <button
            class="px-8 py-3 bg-white border-2 border-slate-200 text-slate-600 font-bold rounded-full hover:border-indigo-600 hover:text-indigo-600 hover:bg-indigo-50 transition-all text-sm shadow-sm">
            Muat Lebih Banyak
          </button>
        </div>

        <?php else: ?>

        <div class="col-span-full py-20 text-center bg-white rounded-[2rem] border border-dashed border-slate-300">
          <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="ti ti-package-off text-4xl text-slate-300"></i>
          </div>
          <h3 class="text-xl font-bold text-slate-800 mb-2">Kategori Kosong</h3>
          <p class="text-slate-500 text-sm mb-6">Belum ada produk yang tersedia di kategori ini.</p>
          <a href="<?= site_url(); ?>"
            class="inline-flex items-center px-6 py-2.5 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200">
            Kembali ke Beranda
          </a>
        </div>

        <?php endif; ?>
      </div>

    </div>
  </div>
</div>