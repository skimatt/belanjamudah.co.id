<div class="container mx-auto px-4 py-8">
  <h1 class="text-3xl font-bold text-gray-800 mb-2">
    Katalog: <?= html_escape($current_category->name ?? 'Semua Kategori'); ?>
  </h1>
  <p class="text-gray-600 mb-6">
    Menampilkan <strong><?= count($products); ?></strong> produk dalam kategori ini.
  </p>


  <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

    <aside class="lg:col-span-3 bg-white p-4 rounded-xl shadow-md h-min sticky top-20">
      <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Filter Kategori</h3>

      <ul class="space-y-1">
        <?php foreach ($categories as $cat): ?>
        <li
          class="<?= (isset($current_category) && $current_category->id == $cat->id) ? 'font-bold text-indigo-600' : 'text-gray-600'; ?>">
          <a href="<?= site_url('category/' . $cat->slug); ?>" class="hover:text-indigo-600 text-sm block py-1">
            <?= html_escape($cat->name); ?>
          </a>
        </li>
        <?php endforeach; ?>
      </ul>
    </aside>

    <div class="lg:col-span-9">
      <?php if (!empty($products)): ?>
      <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
        <?php foreach ($products as $product): ?>
        <div class="bg-white rounded-lg overflow-hidden card-shadow transition duration-300 ease-in-out">
          <a href="<?= site_url('product/' . $product->slug); ?>">
            <img src="<?= base_url($product->main_image_path ?: 'assets/img/placeholder.png'); ?>"
              alt="<?= html_escape($product->name); ?>" class="w-full h-40 object-cover object-center">
          </a>
          <div class="p-3">
            <h3 class="text-sm font-semibold h-10 overflow-hidden mb-1">
              <a href="<?= site_url('product/' . $product->slug); ?>" class="hover:text-indigo-600">
                <?= html_escape($product->name); ?>
              </a>
            </h3>
            <p class="text-lg font-bold text-red-600">
              <?= format_rupiah($product->price); ?>
            </p>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <?php else: ?>
      <p class="text-center text-gray-500 p-10 bg-white rounded-lg shadow-md">Maaf, belum ada produk aktif di kategori
        ini.</p>
      <?php endif; ?>
    </div>
  </div>
</div>