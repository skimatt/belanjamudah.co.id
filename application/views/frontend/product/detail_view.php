<div class="bg-slate-50 min-h-screen pb-12">
  <div class="container mx-auto px-4 lg:px-8 max-w-7xl py-8">

    <nav class="flex text-sm text-gray-500 mb-6 animate__animated animate__fadeInDown">
      <span class="hover:text-indigo-600 cursor-pointer">Beranda</span>
      <span class="mx-2">/</span>
      <span class="hover:text-indigo-600 cursor-pointer"><?= html_escape($product->category_name); ?></span>
      <span class="mx-2">/</span>
      <span class="text-indigo-600 font-medium truncate"><?= html_escape($product->name); ?></span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

      <div class="lg:col-span-4 animate__animated animate__fadeInLeft">
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-indigo-50 sticky top-24">
          <div id="main-image-container"
            class="relative group rounded-xl overflow-hidden bg-gray-100 mb-4 border border-gray-100"
            style="aspect-ratio: 1/1;">
            <img id="main-product-image" src="<?= base_url($images[0]->image_path ?? 'assets/img/placeholder.png'); ?>"
              alt="<?= html_escape($product->name); ?>"
              class="w-full h-full object-contain object-center transition-transform duration-500 group-hover:scale-110 cursor-zoom-in mix-blend-multiply">

            <div class="absolute top-3 left-3 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded shadow-lg z-20">
              TERLARIS
            </div>
          </div>

          <div class="flex gap-3 overflow-x-auto pb-2 scrollbar-hide justify-center">
            <?php foreach ($images as $key => $img): ?>
            <div class="relative group-thumb">
              <img src="<?= base_url($img->image_path); ?>"
                class="w-16 h-16 object-cover rounded-lg border-2 border-transparent cursor-pointer hover:border-indigo-500 transition-all duration-200 thumbnail-image bg-gray-50"
                data-src="<?= base_url($img->image_path); ?>">
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>

      <div class="lg:col-span-5 animate__animated animate__fadeInUp">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-indigo-50 h-full relative overflow-hidden">
          <div
            class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-blue-50 to-transparent rounded-bl-full opacity-50 pointer-events-none">
          </div>

          <h1 class="text-2xl md:text-3xl font-bold text-slate-800 mb-2 leading-tight">
            <?= html_escape($product->name); ?>
          </h1>

          <div class="flex items-center gap-4 text-sm text-gray-500 mb-6 border-b border-gray-100 pb-4">
            <div class="flex items-center text-yellow-500">
              <i class="ti ti-star-filled text-lg"></i>
              <span class="ml-1 font-bold text-slate-700"><?= $product->rating ?? '4.8'; ?></span>
              <span class="text-gray-400 ml-1 font-normal">(Ulasan)</span>
            </div>
            <div class="w-px h-4 bg-gray-300"></div>
            <div>
              Terjual <span class="font-semibold text-slate-700"><?= $product->sold_count ?? 0; ?></span>
            </div>
            <div class="w-px h-4 bg-gray-300"></div>
            <div class="text-indigo-600 font-medium">
              <?= html_escape($product->category_name); ?>
            </div>
          </div>

          <div class="bg-gradient-to-r from-indigo-50 to-white p-5 rounded-xl border border-indigo-100/50 mb-6">
            <p class="text-sm text-gray-500 font-medium mb-1">Harga Spesial</p>
            <div class="flex items-end gap-3">
              <h2 class="text-4xl font-extrabold text-red-600" id="product-price-display">
                <?= format_rupiah($product->product_base_price); ?>
              </h2>
            </div>
          </div>

          <div class="mb-6">
            <h3 class="text-lg font-bold text-slate-800 mb-3 flex items-center">
              <i class="ti ti-list-details text-indigo-500 mr-2"></i> Detail Singkat
            </h3>
            <div class="bg-slate-50 rounded-lg p-4 border border-slate-100">
              <ul class="space-y-3 text-sm text-slate-600">
                <li class="flex justify-between border-b border-slate-200 border-dashed pb-2">
                  <span>Kondisi</span>
                  <span class="font-semibold text-slate-800">Baru</span>
                </li>
                <li class="flex justify-between border-b border-slate-200 border-dashed pb-2">
                  <span>Berat Satuan</span>
                  <span class="font-semibold text-slate-800"><?= number_format($product->weight, 0, '.', ','); ?>
                    gram</span>
                </li>
                <li class="flex justify-between">
                  <span>Kategori</span>
                  <span
                    class="text-indigo-600 font-medium cursor-pointer hover:underline"><?= html_escape($product->category_name); ?></span>
                </li>
              </ul>
            </div>
          </div>

          <div class="flex items-start gap-3 text-sm text-gray-600 bg-green-50 p-3 rounded-lg border border-green-100">
            <i class="ti ti-truck text-green-600 text-lg mt-0.5"></i>
            <div>
              <span class="font-bold text-green-700 block">Pengiriman Tersedia</span>
              <span>Dikirim dari <span
                  class="font-semibold"><?= html_escape($product->ship_from ?? 'Gudang Pusat'); ?></span>. Estimasi tiba
                2-3 hari.</span>
            </div>
          </div>
        </div>
      </div>

      <div class="lg:col-span-3 animate__animated animate__fadeInRight">
        <div class="bg-white rounded-2xl shadow-lg border border-indigo-100 sticky top-24 overflow-hidden">
          <div class="bg-gray-50 px-6 py-4 border-b border-gray-100">
            <h3 class="font-bold text-lg text-slate-800">Atur Pembelian</h3>
          </div>

          <div class="p-6">
            <input type="hidden" id="product-id-input" value="<?= $product->id; ?>">
            <input type="hidden" id="selected-variant-id" value="">
            <input type="hidden" id="csrf-token" name="<?= $this->security->get_csrf_token_name(); ?>"
              value="<?= $this->security->get_csrf_hash(); ?>">

            <?php if (!empty($variants)): ?>
            <div class="mb-5">
              <label class="block text-sm font-bold text-slate-700 mb-3">Pilih Varian:</label>
              <div class="flex flex-wrap gap-2" id="variant-selection-container">
                <?php foreach ($variants as $variant): ?>
                <button type="button" data-id="<?= $variant->id; ?>" data-price="<?= $variant->calculated_price; ?>"
                  data-stock="<?= $variant->stock; ?>"
                  class="variant-option px-4 py-2 text-sm rounded-lg border border-gray-200 bg-white text-slate-600 hover:border-indigo-500 hover:text-indigo-600 hover:bg-indigo-50 transition-all duration-200 focus:outline-none shadow-sm">
                  <?= html_escape($variant->name); ?>
                </button>
                <?php endforeach; ?>
              </div>
              <p id="variant-error-message"
                class="text-red-500 text-xs mt-2 hidden flex items-center animate__animated animate__shakeX">
                <i class="ti ti-alert-circle mr-1"></i> Mohon pilih varian dahulu
              </p>
            </div>
            <?php endif; ?>

            <div class="mb-6">
              <div class="flex justify-between items-center mb-2">
                <label class="text-sm font-bold text-slate-700">Jumlah:</label>
                <span class="text-xs text-gray-500">Stok: <span id="available-stock-text"
                    class="font-bold text-slate-800"><?= number_format($product->stock_sum, 0, '.', ','); ?></span></span>
              </div>

              <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden w-full">
                <button type="button" id="qty-minus"
                  class="w-10 h-10 flex items-center justify-center bg-gray-50 text-gray-600 hover:bg-gray-100 transition disabled:opacity-50">
                  <i class="ti ti-minus"></i>
                </button>
                <input type="number" id="quantity" value="1" min="1" max="<?= $product->stock_sum; ?>"
                  class="flex-1 h-10 text-center border-none focus:ring-0 text-slate-800 font-semibold">
                <button type="button" id="qty-plus"
                  class="w-10 h-10 flex items-center justify-center bg-gray-50 text-gray-600 hover:bg-gray-100 transition disabled:opacity-50">
                  <i class="ti ti-plus"></i>
                </button>
              </div>
              <p id="stock-error-message" class="text-red-500 text-xs mt-2 hidden"></p>
            </div>

            <div class="space-y-3">
              <button type="button" id="add-to-cart-button" disabled
                class="w-full py-3 px-4 rounded-xl border-2 border-indigo-600 text-indigo-600 font-bold hover:bg-indigo-50 transition-all duration-300 flex justify-center items-center disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-transparent">
                <i class="ti ti-shopping-cart mr-2 text-xl"></i> + Keranjang
              </button>

              <button type="button" id="buy-now-button" disabled
                class="w-full py-3 px-4 rounded-xl bg-indigo-600 text-white font-bold shadow-lg shadow-indigo-200 hover:bg-indigo-700 hover:shadow-indigo-300 transition-all duration-300 flex justify-center items-center disabled:opacity-50 disabled:cursor-not-allowed">
                Beli Langsung
              </button>
            </div>

            <div class="mt-6 pt-4 border-t border-gray-100 flex justify-center space-x-4 text-gray-400">
              <div class="flex flex-col items-center text-[10px]">
                <i class="ti ti-shield-check text-xl mb-1"></i>
                <span>Aman</span>
              </div>
              <div class="flex flex-col items-center text-[10px]">
                <i class="ti ti-award text-xl mb-1"></i>
                <span>Original</span>
              </div>
              <div class="flex flex-col items-center text-[10px]">
                <i class="ti ti-clock text-xl mb-1"></i>
                <span>Cepat</span>
              </div>
            </div>

          </div>
        </div>
      </div>

    </div>
  </div>

  <section class="bg-white py-12 border-t border-slate-200 mt-8">
    <div class="container mx-auto px-4 lg:px-8 max-w-7xl">
      <div class="flex items-center justify-between mb-8">
        <h2 class="text-2xl font-bold text-slate-800">
          Pilihan Lain di <span class="text-indigo-600"><?= html_escape($product->category_name); ?></span>
        </h2>
        <a href="<?= site_url('category/' . $product->category_slug); ?>"
          class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 flex items-center">
          Lihat Semua <i class="ti ti-arrow-right ml-1"></i>
        </a>
      </div>

      <?php if (!empty($similar_products)): ?>
      <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
        <?php foreach ($similar_products as $s_product): ?>
        <div
          class="group bg-white border border-gray-100 rounded-xl p-3 hover:shadow-xl hover:border-indigo-200 transition-all duration-300 relative">
          <a href="<?= site_url('product/' . $s_product->slug); ?>" class="block">
            <div class="rounded-lg overflow-hidden mb-3 relative aspect-square bg-gray-50">
              <img src="<?= base_url($s_product->main_image_path ?: 'assets/img/placeholder.png'); ?>"
                alt="<?= html_escape($s_product->name); ?>"
                class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-500">
            </div>
            <h3
              class="text-sm font-medium text-slate-700 line-clamp-2 h-10 mb-1 group-hover:text-indigo-600 transition-colors">
              <?= html_escape($s_product->name); ?>
            </h3>
            <p class="text-lg font-bold text-slate-900">
              <?= format_rupiah($s_product->price); ?>
            </p>
          </a>
        </div>
        <?php endforeach; ?>
      </div>
      <?php else: ?>
      <div class="text-center py-10 bg-slate-50 rounded-xl border border-dashed border-slate-300">
        <p class="text-gray-500">Belum ada produk serupa.</p>
      </div>
      <?php endif; ?>
    </div>
  </section>
</div>

<div id="notification" class="fixed top-6 right-6 z-50 hidden transition-all duration-300">
  <div
    class="flex items-center p-4 rounded-xl shadow-2xl bg-white border-l-4 min-w-[300px] animate__animated animate__fadeInRight"
    id="notif-container">
    <div class="p-2 rounded-full mr-3 bg-gray-100 text-xl" id="notif-icon-bg">
      <i class="ti" id="notif-icon"></i>
    </div>
    <div>
      <h4 class="font-bold text-sm text-gray-800" id="notif-title">Notifikasi</h4>
      <p class="text-xs text-gray-500" id="notification-message">Pesan disini.</p>
    </div>
  </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

<style>
/* Custom Scrollbar Hide */
.scrollbar-hide::-webkit-scrollbar {
  display: none;
}

.scrollbar-hide {
  -ms-overflow-style: none;
  scrollbar-width: none;
}

/* Active State untuk Varian */
.variant-option.active-variant {
  background-color: #eff6ff;
  /* bg-indigo-50 */
  border-color: #4f46e5;
  /* border-indigo-600 */
  color: #4338ca;
  /* text-indigo-700 */
  font-weight: 600;
  box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.1);
}

/* Input Number Arrows Removal */
input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
</style>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
// ------------------------------------------------------
// LOGIKA TIDAK DIUBAH, HANYA PENYESUAIAN CLASS UI SAJA
// ------------------------------------------------------

const variants = <?= json_encode($variants); ?>;
let selectedVariant = null;

function formatRupiah(number) {
  return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
}

function updateUI() {
  const qty = parseInt($('#quantity').val());
  // Bersihkan titik dari format rupiah/angka untuk kalkulasi
  const stockText = $('#available-stock-text').text().replace(/\./g, '');
  const stock = parseInt(stockText);

  const hasVariant = variants.length === 0 || selectedVariant !== null;
  const validQty = qty > 0 && qty <= stock;

  $('#buy-now-button, #add-to-cart-button').prop('disabled', !(hasVariant && validQty));
  $('#qty-plus').prop('disabled', qty >= stock);
  $('#qty-minus').prop('disabled', qty <= 1);

  if (!validQty && stock > 0) {
    $('#stock-error-message').removeClass('hidden').text(`Maksimal pembelian ${stock} unit`);
  } else {
    $('#stock-error-message').addClass('hidden');
  }
}

// Thumbnail Logic
$('.thumbnail-image').on('click', function() {
  const src = $(this).data('src');
  // Efek visual ganti gambar
  $('#main-product-image').fadeOut(100, function() {
    $(this).attr('src', src).fadeIn(200);
  });

  // Highlight active thumbnail
  $('.thumbnail-image').removeClass('border-indigo-500 ring-2 ring-indigo-200');
  $(this).addClass('border-indigo-500 ring-2 ring-indigo-200');
});

// Variant Selection
$('.variant-option').on('click', function() {
  // UI Change using Class
  $('.variant-option').removeClass('active-variant');
  $(this).addClass('active-variant');

  const variantId = $(this).data('id');
  selectedVariant = variants.find(v => v.id == variantId);

  $('#selected-variant-id').val(variantId);
  $('#product-price-display').text(formatRupiah($(this).data('price')));
  $('#available-stock-text').text(selectedVariant.stock.toLocaleString('id-ID'));

  // Reset quantity ke 1 saat ganti varian
  $('#quantity').val(1).prop('max', selectedVariant.stock);
  $('#variant-error-message').addClass('hidden');

  updateUI();
});

// Quantity Logic
$('#quantity').on('change keyup', updateUI);
$('#qty-plus').on('click', () => {
  $('#quantity').val(+$('#quantity').val() + 1).trigger('change');
});
$('#qty-minus').on('click', () => {
  $('#quantity').val(+$('#quantity').val() - 1).trigger('change');
});

// Data Handling
let csrfName = '<?= $this->security->get_csrf_token_name(); ?>';
let csrfHash = '<?= $this->security->get_csrf_hash(); ?>';

function getOrderData(extra = {}) {
  let data = {
    product_id: $('#product-id-input').val(),
    variant_id: $('#selected-variant-id').val(),
    qty: $('#quantity').val()
  };
  data[csrfName] = csrfHash;
  return {
    ...data,
    ...extra
  };
}

// Notification System (Dipercantik Logic UI-nya)
function showNotification(message, type = 'success') {
  const notif = $('#notification');
  const container = $('#notif-container');
  const iconBg = $('#notif-icon-bg');
  const icon = $('#notif-icon');
  const title = $('#notif-title');

  $('#notification-message').text(message);
  notif.removeClass('hidden');

  if (type === 'success') {
    container.removeClass('border-red-500').addClass('border-green-500');
    iconBg.removeClass('bg-red-100 text-red-600').addClass('bg-green-100 text-green-600');
    icon.removeClass('ti-alert-circle').addClass('ti-check');
    title.text('Berhasil');
  } else {
    container.removeClass('border-green-500').addClass('border-red-500');
    iconBg.removeClass('bg-green-100 text-green-600').addClass('bg-red-100 text-red-600');
    icon.removeClass('ti-check').addClass('ti-alert-circle');
    title.text('Gagal');
  }

  // Auto hide
  setTimeout(() => {
    container.removeClass('animate__fadeInRight').addClass('animate__fadeOutRight');
    setTimeout(() => {
      notif.addClass('hidden');
      container.removeClass('animate__fadeOutRight').addClass('animate__fadeInRight');
    }, 500);
  }, 3000);
}

// Button Actions
$('#add-to-cart-button').on('click', function() {
  // Cek varian jika ada
  if (variants.length > 0 && !selectedVariant) {
    $('#variant-error-message').removeClass('hidden');
    return;
  }

  const btn = $(this);
  const originalText = btn.html();
  btn.prop('disabled', true).html('<i class="ti ti-loader animate-spin mr-2"></i> Loading...');

  $.ajax({
    url: "<?= site_url('cart/add_to_cart'); ?>",
    method: "POST",
    data: getOrderData(),
    dataType: "json",
    success: function(res) {
      if (res.status === 'success') {
        showNotification(res.message, 'success');
        csrfHash = res.csrf_hash;
      } else if (res.status === 'redirect') {
        window.location.href = res.url;
      } else {
        showNotification(res.message, 'error');
      }
      btn.prop('disabled', false).html(originalText);
      updateUI(); // Re-check disabled state logic
    },
    error: function() {
      btn.prop('disabled', false).html(originalText);
      showNotification('Terjadi kesalahan jaringan', 'error');
      updateUI();
    }
  });
});

$('#buy-now-button').on('click', function() {
  if (variants.length > 0 && !selectedVariant) {
    $('#variant-error-message').removeClass('hidden');
    return;
  }

  const btn = $(this);
  btn.prop('disabled', true).text('Memproses...');

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
        btn.prop('disabled', false).text('Beli Langsung');
        csrfHash = res.csrf_hash;
      }
    }
  });
});

// Initialize
updateUI();
</script>