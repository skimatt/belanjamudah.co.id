<div class="container mx-auto px-4 lg:px-8 max-w-7xl py-8">
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Kolom 1: Gambar Produk -->
    <div
      class="lg:col-span-1 bg-white p-6 rounded-2xl shadow-lg transform transition-all duration-300 hover:shadow-xl hover:bg-gradient-to-br hover:from-gray-50 hover:to-blue-50 relative z-10">
      <div id="main-image-container" class="mb-4 rounded-lg overflow-hidden border border-gray-200 relative group">
        <img id="main-product-image" src="<?= base_url($images[0]->image_path ?? 'assets/img/placeholder.png'); ?>"
          alt="<?= html_escape($product->name); ?>"
          class="w-full h-96 object-contain transition-transform duration-500 group-hover:scale-110 cursor-zoom-in z-20">
        <div
          class="absolute inset-0 border-2 border-transparent group-hover:border-indigo-300 transition-all duration-300 rounded-lg pointer-events-none">
        </div>
      </div>
      <div class="flex space-x-2 overflow-x-auto pb-2 justify-center scrollbar-hide">
        <?php foreach ($images as $img): ?>
        <img src="<?= base_url($img->image_path); ?>"
          class="w-20 h-20 object-cover object-center rounded-md border cursor-pointer hover:border-indigo-500 hover:shadow-lg transition-all duration-300 hover:scale-105 z-20 thumbnail-image"
          data-src="<?= base_url($img->image_path); ?>">
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Kolom 2: Info Produk -->
    <div
      class="lg:col-span-1 bg-white p-6 rounded-2xl shadow-lg transform transition-all duration-300 hover:shadow-xl hover:bg-gradient-to-br hover:from-gray-50 hover:to-blue-50 relative z-10">
      <h1 class="text-3xl font-extrabold text-gray-900 mb-3 relative overflow-hidden">
        <?= html_escape($product->name); ?>
        <span class="absolute bottom-0 left-0 w-32 h-1 bg-indigo-600 transition-all duration-500 hover:w-full"></span>
      </h1>
      <p class="text-sm text-gray-500 mb-4">
        Kategori:
        <a href="<?= site_url('category/' . $product->category_slug); ?>"
          class="text-indigo-600 hover:text-indigo-800 hover:underline transition-all duration-300">
          <?= html_escape($product->category_name); ?>
        </a>
      </p>

      <div class="flex items-center space-x-4 border-b border-gray-200 pb-4 mb-4">
        <span class="text-sm text-gray-600 flex items-center">
          <i class="ti ti-basket mr-1 text-gray-500"></i> Terjual:
          <span class="font-semibold ml-1"><?= $product->sold_count ?? 0; ?></span>
        </span>
        <span class="text-sm text-gray-400">|</span>
        <span class="text-sm text-gray-600 flex items-center">
          <i class="ti ti-truck mr-1 text-gray-500"></i> Dikirim dari:
          <?= html_escape($product->ship_from ?? 'Jakarta'); ?>
        </span>
      </div>

      <div class="bg-indigo-50 p-4 rounded-lg mb-6 transition-all duration-300 hover:bg-indigo-100 hover:shadow-md">
        <p class="text-lg text-gray-600 font-medium">Harga:</p>
        <p class="text-4xl font-extrabold text-red-600 mt-1 transform transition-transform duration-300 hover:scale-105"
          id="product-price-display">
          <?= format_rupiah($product->product_base_price); ?>
        </p>
      </div>

      <div class="text-gray-700 space-y-3 mb-6">
        <h3 class="font-bold text-lg text-gray-800 relative overflow-hidden">
          Spesifikasi Singkat:
          <span class="absolute bottom-0 left-0 w-24 h-1 bg-indigo-600 transition-all duration-500 hover:w-full"></span>
        </h3>
        <ul class="list-disc list-inside text-sm ml-2 space-y-1">
          <li class="flex items-center">
            <i class="ti ti-package mr-2 text-gray-500"></i> Stok:
            <span id="current-stock-display"
              class="font-semibold text-green-600 ml-1"><?= number_format($product->stock_sum, 0, '.', ','); ?></span>
            unit
          </li>
          <li class="flex items-center">
            <i class="ti ti-weight mr-2 text-gray-500"></i> Berat:
            <span class="font-semibold ml-1"><?= number_format($product->weight, 0, '.', ','); ?> gram</span>
          </li>
        </ul>
      </div>

      <div class="mt-4">
        <h3 class="text-xl font-bold text-gray-800 mb-2 border-b border-gray-200 pb-2 relative overflow-hidden">
          Deskripsi Produk
          <span class="absolute bottom-0 left-0 w-24 h-1 bg-indigo-600 transition-all duration-500 hover:w-full"></span>
        </h3>
        <div class="prose max-w-none text-gray-700 text-sm leading-relaxed">
          <?= $product->description; ?>
        </div>
      </div>
    </div>

    <!-- Kolom 3: Atur Pembelian -->
    <div class="lg:col-span-1">
      <div
        class="bg-white p-6 rounded-2xl shadow-lg sticky lg:top-24 transform transition-all duration-300 hover:shadow-xl hover:bg-gradient-to-br hover:from-gray-50 hover:to-blue-50 relative z-10">
        <h3 class="text-xl font-bold text-gray-800 mb-4 border-b border-gray-200 pb-2 relative overflow-hidden">
          Atur Pembelian
          <span class="absolute bottom-0 left-0 w-24 h-1 bg-indigo-600 transition-all duration-500 hover:w-full"></span>
        </h3>

        <input type="hidden" id="product-id-input" value="<?= $product->id; ?>">
        <input type="hidden" id="selected-variant-id" value="">
        <input type="hidden" id="csrf-token" name="<?= $this->security->get_csrf_token_name(); ?>"
          value="<?= $this->security->get_csrf_hash(); ?>">

        <?php if (!empty($variants)): ?>
        <div class="mb-6">
          <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Varian</label>
          <div class="flex flex-wrap gap-2" id="variant-selection-container">
            <?php foreach ($variants as $variant): ?>
            <button type="button" data-id="<?= $variant->id; ?>" data-price="<?= $variant->calculated_price; ?>"
              data-stock="<?= $variant->stock; ?>"
              class="variant-option border border-gray-300 rounded-full px-4 py-1.5 text-sm bg-gray-50 hover:bg-indigo-100 hover:border-indigo-600 hover:text-indigo-700 transition-all duration-300 hover:scale-105 focus:outline-none z-20">
              <?= html_escape($variant->name); ?>
            </button>
            <?php endforeach; ?>
          </div>
          <p id="variant-error-message" class="text-red-500 text-xs mt-2 hidden">Pilih varian produk.</p>
        </div>
        <?php endif; ?>

        <div class="mb-6">
          <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">Jumlah</label>
          <div class="flex items-center space-x-3">
            <button type="button" id="qty-minus"
              class="border w-10 h-10 rounded-full text-lg bg-gray-50 hover:bg-indigo-100 hover:border-indigo-600 transition-all duration-300 disabled:opacity-50 focus:outline-none z-20">-</button>
            <input type="number" id="quantity" value="1" min="1" max="<?= $product->stock_sum; ?>"
              class="w-20 text-center border-gray-300 rounded-lg py-2 focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 transition-all duration-300 hover:shadow-md z-20">
            <button type="button" id="qty-plus"
              class="border w-10 h-10 rounded-full text-lg bg-gray-50 hover:bg-indigo-100 hover:border-indigo-600 transition-all duration-300 disabled:opacity-50 focus:outline-none z-20">+</button>
            <span class="text-sm text-gray-600 ml-2">Stok: <span id="available-stock-text"
                class="font-semibold"><?= number_format($product->stock_sum, 0, '.', ','); ?></span></span>
          </div>
          <p id="stock-error-message" class="text-red-500 text-xs mt-2 hidden"></p>
        </div>

        <div class="flex flex-col space-y-3">
          <button type="button" id="buy-now-button"
            class="w-full bg-red-600 text-white font-bold py-3 rounded-lg hover:bg-red-700 transition-all duration-300 hover:scale-105 hover:shadow-lg hover:bg-gradient-to-r hover:from-red-600 hover:to-red-800 focus:outline-none z-20"
            disabled>
            <i class="ti ti-shopping-bag mr-2"></i> Beli Sekarang
          </button>
          <button type="button" id="add-to-cart-button"
            class="w-full bg-indigo-600 text-white font-bold py-3 rounded-lg hover:bg-indigo-700 transition-all duration-300 hover:scale-105 hover:shadow-lg hover:bg-gradient-to-r hover:from-indigo-600 hover:to-indigo-800 focus:outline-none z-20"
            disabled>
            <i class="ti ti-shopping-cart mr-2"></i> Tambah ke Keranjang
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Produk Serupa -->
  <section class="mt-12 mb-10">
    <h2 class="text-2xl font-bold text-gray-800 mb-4 border-b border-gray-200 pb-2 relative overflow-hidden">
      Produk Serupa di Kategori <?= html_escape($product->category_name); ?>
      <span class="absolute bottom-0 left-0 w-32 h-1 bg-indigo-600 transition-all duration-500 hover:w-full"></span>
    </h2>
    <?php if (!empty($similar_products)): ?>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
      <?php foreach ($similar_products as $s_product): ?>
      <div
        class="bg-white rounded-lg overflow-hidden shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:bg-gradient-to-t hover:from-indigo-50 hover:to-white relative z-20">
        <a href="<?= site_url('product/' . $s_product->slug); ?>" class="block relative z-30">
          <img src="<?= base_url($s_product->main_image_path ?: 'assets/img/placeholder.png'); ?>"
            alt="<?= html_escape($s_product->name); ?>"
            class="w-full h-40 object-cover object-center transition-transform duration-500 hover:scale-110">
          <div
            class="absolute inset-0 border-2 border-transparent hover:border-indigo-300 transition-all duration-300 rounded-lg pointer-events-none">
          </div>
        </a>
        <div class="p-4">
          <h3 class="text-sm font-semibold line-clamp-2 h-10 mb-1">
            <a href="<?= site_url('product/' . $s_product->slug); ?>"
              class="hover:text-indigo-600 transition-colors duration-300">
              <?= html_escape($s_product->name); ?>
            </a>
          </h3>
          <p class="text-base font-bold text-red-600 transform transition-transform duration-300 hover:scale-105">
            <?= format_rupiah($s_product->price); ?>
          </p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <p
      class="text-center text-gray-600 p-6 bg-white rounded-lg shadow-md transform transition-all duration-300 hover:shadow-lg animate-pulse">
      Tidak ada produk serupa saat ini.
    </p>
    <?php endif; ?>
  </section>

  <!-- Notifikasi Animasi -->
  <div id="notification"
    class="fixed top-4 right-4 z-50 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg hidden flex items-center space-x-2">
    <i class="ti ti-check text-xl"></i>
    <span id="notification-message"></span>
  </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

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

.hover\:shadow-xl {
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
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
a {
  position: relative;
  pointer-events: auto;
}

/* Mengatasi masalah overlap pada transform */
.transform {
  will-change: transform;
}

/* Efek group-hover untuk container gambar */
.group:hover .group-hover\:scale-110 {
  transform: scale(1.1);
}
</style>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
const variants = <?= json_encode($variants); ?>;
let selectedVariant = null;

// Format angka ke Rupiah
function formatRupiah(number) {
  return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
}

// Update UI saat ada perubahan
function updateUI() {
  const qty = parseInt($('#quantity').val());
  const stock = parseInt($('#available-stock-text').text().replace(/\./g, ''));
  const hasVariant = variants.length === 0 || selectedVariant !== null;
  const validQty = qty > 0 && qty <= stock;

  $('#buy-now-button, #add-to-cart-button').prop('disabled', !(hasVariant && validQty));
  $('#qty-plus').prop('disabled', qty >= stock);
  $('#qty-minus').prop('disabled', qty <= 1);

  if (!validQty) {
    $('#stock-error-message').removeClass('hidden').text(`Stok maksimal ${stock}`);
  } else {
    $('#stock-error-message').addClass('hidden');
  }
}

// Thumbnail klik → ganti gambar utama
$('.thumbnail-image').on('click', function() {
  $('#main-product-image').attr('src', $(this).data('src'));
});

// Pilih varian
$('.variant-option').on('click', function() {
  $('.variant-option').removeClass('bg-indigo-600 text-white border-indigo-500');
  $(this).addClass('bg-indigo-600 text-white border-indigo-500');

  const variantId = $(this).data('id');
  selectedVariant = variants.find(v => v.id == variantId);

  $('#selected-variant-id').val(variantId);
  $('#product-price-display').text(formatRupiah($(this).data('price')));
  $('#available-stock-text, #current-stock-display').text(selectedVariant.stock.toLocaleString('id-ID'));
  $('#quantity').val(1).prop('max', selectedVariant.stock);

  updateUI();
});

// Quantity logic
$('#quantity').on('change keyup', updateUI);
$('#qty-plus').on('click', () => {
  $('#quantity').val(+$('#quantity').val() + 1).trigger('change');
});
$('#qty-minus').on('click', () => {
  $('#quantity').val(+$('#quantity').val() - 1).trigger('change');
});

let csrfName = '<?= $this->security->get_csrf_token_name(); ?>';
let csrfHash = '<?= $this->security->get_csrf_hash(); ?>';

function getOrderData(extra = {}) {
  let data = {
    product_id: $('#product-id-input').val(),
    variant_id: $('#selected-variant-id').val(),
    qty: $('#quantity').val()
  };
  data[csrfName] = csrfHash; // wajib
  return {
    ...data,
    ...extra
  };
}

function showNotification(message, type = 'success') {
  const notification = $('#notification');
  const icon = notification.find('i');
  $('#notification-message').text(message);

  if (type === 'success') {
    notification.removeClass('bg-red-500').addClass('bg-green-500');
    icon.removeClass('ti ti-alert-triangle').addClass('ti ti-check');
  } else {
    notification.removeClass('bg-green-500').addClass('bg-red-500');
    icon.removeClass('ti ti-check').addClass('ti ti-alert-triangle');
  }

  notification.removeClass('hidden animate__animated animate__fadeOutRight')
    .addClass('animate__animated animate__fadeInRight');

  setTimeout(() => {
    notification.removeClass('animate__fadeInRight')
      .addClass('animate__fadeOutRight');
    setTimeout(() => {
      notification.addClass('hidden');
    }, 1000);
  }, 3000);
}

// Tambah ke Keranjang
$('#add-to-cart-button').on('click', function() {
  $.ajax({
    url: "<?= site_url('cart/add_to_cart'); ?>",
    method: "POST",
    data: getOrderData(),
    dataType: "json",
    success: function(res) {
      if (res.status === 'success') {
        showNotification(res.message, 'success');
        csrfHash = res.csrf_hash; // update token baru
      } else if (res.status === 'redirect') {
        window.location.href = res.url;
      } else {
        showNotification(res.message, 'error');
      }
    }
  });
});

// Beli Sekarang
$('#buy-now-button').on('click', function() {
  $.ajax({
    url: "<?= site_url('cart/add_to_cart'); ?>",
    method: "POST",
    data: getOrderData({
      redirect_to_checkout: 1
    }),
    dataType: "json",
    success: function(res) {
      if (res.status === 'success' && res.redirect_url) {
        window.location.href = res.redirect_url;
      } else if (res.status === 'redirect') {
        window.location.href = res.url;
      } else {
        showNotification(res.message, 'error');
      }
      csrfHash = res.csrf_hash; // update token baru
    }
  });
});

// Inisialisasi
updateUI();
</script>