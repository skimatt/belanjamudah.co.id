<div class="mx-auto px-4 sm:px-6 lg:px-14 max-w-[1600px] py-10">

  <main class="mx-auto px-4 lg:px-10 py-8 max-w-[1500px]">


    <div class="flex items-center justify-between mb-8">
      <h1 class="text-2xl md:text-3xl font-bold text-slate-800 flex items-center">
        <i class="ti ti-shopping-cart mr-3 text-indigo-600"></i> Keranjang Belanja
      </h1>
      <?php if (!empty($cart_data->items)): ?>
      <span
        class="text-sm font-semibold text-slate-500 bg-white px-4 py-2 rounded-full border border-gray-200 shadow-sm">
        <?= count($cart_data->items); ?> Item Tersedia
      </span>
      <?php endif; ?>
    </div>

    <?php if ($this->session->flashdata('error')): ?>
    <div
      class="flex items-center p-4 mb-6 text-sm text-red-700 bg-red-50 rounded-xl border border-red-100 shadow-sm animate__animated animate__fadeIn"
      role="alert">
      <i class="ti ti-alert-circle text-xl mr-3"></i>
      <span class="font-medium"><?= $this->session->flashdata('error'); ?></span>
    </div>
    <?php endif; ?>

    <?php if (!empty($cart_data->items)): ?>
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

      <div class="lg:col-span-8 space-y-4" id="cart-items-container">

        <div
          class="bg-white p-4 rounded-t-xl border-b border-gray-100 hidden lg:flex justify-between text-sm font-bold text-gray-500">
          <span class="w-1/2">Produk</span>
          <div class="w-1/2 flex justify-between px-4">
            <span>Harga Satuan</span>
            <span>Kuantitas</span>
            <span>Total</span>
          </div>
        </div>

        <?php foreach ($cart_data->items as $item): ?>
        <div
          class="group bg-white p-5 rounded-2xl shadow-sm border border-gray-100 hover:border-indigo-300 hover:shadow-md transition-all duration-300 cart-item-row relative overflow-hidden"
          data-item-id="<?= $item->id; ?>">

          <div
            class="absolute top-0 left-0 w-1 h-full bg-indigo-600 transform -translate-x-full group-hover:translate-x-0 transition-transform duration-300">
          </div>

          <div class="flex flex-col sm:flex-row items-start gap-5">
            <div class="w-full sm:w-28 h-28 flex-shrink-0 bg-gray-50 rounded-xl overflow-hidden border border-gray-200">
              <img src="<?= base_url($item->image_path ?: 'assets/img/placeholder.png'); ?>"
                alt="<?= html_escape($item->product_name); ?>"
                class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-500">
            </div>

            <div class="flex-1 w-full flex flex-col sm:flex-row sm:items-center justify-between gap-4">

              <div class="flex-1 min-w-0">
                <a href="<?= site_url('product/' . $item->product_slug); ?>"
                  class="text-lg font-bold text-slate-800 hover:text-indigo-600 transition-colors line-clamp-2 mb-1">
                  <?= html_escape($item->product_name); ?>
                </a>

                <div class="flex flex-wrap gap-2 mb-2">
                  <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-100">
                    Varian: <?= html_escape($item->variant_name); ?>
                  </span>
                  <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-gray-50 text-gray-600 border border-gray-100">
                    Sisa Stok: <?= $item->current_stock; ?>
                  </span>
                </div>

                <p class="text-sm text-gray-500 sm:hidden">Harga Satuan:</p>
                <p class="font-medium text-slate-600 block sm:hidden">
                  <?= format_rupiah($item->price_at_add); ?>
                </p>
              </div>

              <div class="hidden sm:block text-right w-24">
                <p class="text-sm font-semibold text-slate-600">
                  <?= format_rupiah($item->price_at_add); ?>
                </p>
              </div>

              <div
                class="flex items-center justify-between sm:justify-center w-full sm:w-auto bg-gray-50 rounded-lg p-1 border border-gray-200">
                <input type="number" value="<?= $item->quantity; ?>" min="1" max="<?= $item->current_stock; ?>"
                  data-item-id="<?= $item->id; ?>"
                  class="update-qty-input w-16 text-center bg-transparent border-none focus:ring-0 text-sm font-bold text-slate-800 p-1"
                  placeholder="1">
                <span class="text-xs text-gray-400 px-2">Pcs</span>
              </div>

              <div
                class="flex flex-row sm:flex-col items-center sm:items-end justify-between sm:justify-center w-full sm:w-32 gap-2 border-t sm:border-t-0 border-dashed border-gray-200 pt-3 sm:pt-0 mt-2 sm:mt-0">
                <div>
                  <p class="text-xs text-gray-400 sm:hidden">Total:</p>
                  <p class="text-lg font-extrabold text-indigo-600 item-subtotal-display"
                    data-item-id="<?= $item->id; ?>">
                    <?= format_rupiah($item->subtotal); ?>
                  </p>
                </div>

                <button type="button" data-item-id="<?= $item->id; ?>"
                  class="remove-item-btn text-gray-400 hover:text-red-500 text-xs font-medium flex items-center gap-1 transition-colors group-delete p-2 rounded hover:bg-red-50"
                  title="Hapus Item">
                  <i class="ti ti-trash text-lg group-delete-hover:scale-110"></i>
                  <span>Hapus</span>
                </button>
              </div>

            </div>
          </div>
        </div>
        <?php endforeach; ?>

        <div class="mt-6 flex justify-start">
          <a href="<?= site_url(); ?>"
            class="text-indigo-600 hover:text-indigo-800 font-medium text-sm flex items-center gap-2 transition-all hover:-translate-x-1">
            <i class="ti ti-arrow-left"></i> Lanjut Belanja
          </a>
        </div>
      </div>

      <div class="lg:col-span-4">
        <div class="bg-white p-6 rounded-2xl shadow-lg border border-indigo-50 sticky top-24">
          <h3
            class="text-lg font-bold text-slate-800 mb-6 pb-4 border-b border-gray-100 flex items-center justify-between">
            Ringkasan Belanja
            <i class="ti ti-receipt-2 text-gray-400"></i>
          </h3>

          <div class="mb-6">
            <label class="text-xs font-bold text-gray-500 mb-2 block uppercase tracking-wide">Kode Promo</label>
            <div class="flex rounded-lg shadow-sm">
              <div class="relative flex-grow focus-within:z-10">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <i class="ti ti-ticket text-gray-400"></i>
                </div>
                <input type="text"
                  class="focus:ring-indigo-500 focus:border-indigo-500 block w-full rounded-none rounded-l-lg pl-10 sm:text-sm border-gray-300 py-2.5"
                  placeholder="Punya kode voucher?">
              </div>
              <button type="button"
                class="-ml-px relative inline-flex items-center space-x-2 px-4 py-2 border border-gray-300 text-sm font-medium rounded-r-lg text-gray-700 bg-gray-50 hover:bg-gray-100 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                Terapkan
              </button>
            </div>
          </div>

          <div class="space-y-3 text-sm mb-6 bg-slate-50 p-4 rounded-xl border border-slate-100">
            <div class="flex justify-between text-gray-600">
              <span>Total Harga (<?= count($cart_data->items); ?> item)</span>
              <span class="font-semibold text-gray-800" id="cart-subtotal-display"
                data-total-amount="<?= $cart_data->total; ?>">
                <?= format_rupiah($cart_data->total); ?>
              </span>
            </div>
            <div class="flex justify-between text-green-600">
              <span>Total Diskon</span>
              <span class="font-medium">- Rp 0</span>
            </div>
            <div class="border-t border-gray-200 my-2"></div>
            <div class="flex justify-between items-end">
              <span class="font-bold text-gray-800 text-base">Total Tagihan</span>
              <span class="text-2xl font-extrabold text-indigo-600" id="cart-grandtotal-display">
                <?= format_rupiah($cart_data->total); ?>
              </span>
            </div>
          </div>

          <a href="<?= site_url('checkout'); ?>"
            class="w-full group flex items-center justify-center bg-indigo-600 text-white font-bold py-4 rounded-xl hover:bg-indigo-700 transition-all duration-300 shadow-lg shadow-indigo-200 hover:shadow-indigo-300 transform hover:-translate-y-0.5">
            Checkout Sekarang
            <i class="ti ti-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
          </a>

          <div
            class="mt-6 flex justify-center items-center gap-4 text-gray-400 text-[10px] uppercase tracking-widest font-semibold">
            <span class="flex items-center"><i class="ti ti-shield-check mr-1 text-base"></i> Aman</span>
            <span class="flex items-center"><i class="ti ti-lock mr-1 text-base"></i> Terenkripsi</span>
          </div>
        </div>
      </div>
    </div>

    <?php else: ?>

    <div
      class="flex flex-col items-center justify-center py-20 bg-white rounded-3xl shadow-lg border border-dashed border-gray-300 text-center animate__animated animate__fadeInUp">
      <div class="bg-indigo-50 p-6 rounded-full mb-6 animate-bounce-slow">
        <i class="ti ti-shopping-cart-off text-6xl text-indigo-400"></i>
      </div>
      <h2 class="text-2xl font-bold text-slate-800 mb-2">Keranjang Anda Kosong</h2>
      <p class="text-gray-500 mb-8 max-w-md mx-auto">Sepertinya Anda belum menambahkan apapun. Yuk, cari produk impianmu
        sekarang!</p>
      <a href="<?= site_url(); ?>"
        class="bg-indigo-600 text-white px-8 py-3 rounded-full font-bold hover:bg-indigo-700 transition shadow-lg hover:shadow-xl transform hover:-translate-y-1">
        Mulai Belanja
      </a>
    </div>

    <?php endif; ?>
  </main>
</div>

<input type="hidden" id="csrf-token" name="<?= $this->security->get_csrf_token_name(); ?>"
  value="<?= $this->security->get_csrf_hash(); ?>">

<style>
/* Hapus spinner pada input number */
input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

@keyframes bounce-slow {

  0%,
  100% {
    transform: translateY(-5%);
  }

  50% {
    transform: translateY(5%);
  }
}

.animate-bounce-slow {
  animation: bounce-slow 3s infinite ease-in-out;
}
</style>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

<script>
function formatRupiah(number) {
  if (isNaN(number) || number === null) return 'Rp 0';
  return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
}

$(function() {
  const csrfName = "<?= $this->security->get_csrf_token_name(); ?>";
  let csrfToken = $('#csrf-token').val();

  function updateCartTotals(subTotal, grandTotal) {
    $('#cart-subtotal-display').text(formatRupiah(subTotal));
    $('#cart-grandtotal-display').text(formatRupiah(grandTotal));
  }

  // Update Qty
  $(document).on('change', '.update-qty-input', function() {
    const input = $(this);
    const itemId = input.data('item-id');
    let qty = parseInt(input.val());
    const maxStock = parseInt(input.attr('max'));

    if (isNaN(qty) || qty < 1) qty = 1;
    if (qty > maxStock) qty = maxStock;
    input.val(qty);

    $.post("<?= site_url('cart/update_qty'); ?>", {
      item_id: itemId,
      qty: qty,
      [csrfName]: csrfToken
    }, function(res) {
      if (res.csrf_hash) {
        csrfToken = res.csrf_hash;
        $('#csrf-token').val(csrfToken);
      }
      if (res.status === 'success') {
        $('.item-subtotal-display[data-item-id="' + itemId + '"]').text(formatRupiah(res.item_subtotal));
        updateCartTotals(res.grand_total, res.grand_total);
      } else {
        alert(res.message);
        location.reload();
      }
    }, 'json').fail(() => {
      alert('Error server');
      location.reload();
    });
  });

  // Remove Item
  $(document).on('click', '.remove-item-btn', function() {
    const itemId = $(this).data('item-id');
    if (!confirm('Hapus item ini dari keranjang?')) return;
    $.post("<?= site_url('cart/remove_item'); ?>", {
      item_id: itemId,
      [csrfName]: csrfToken
    }, function(res) {
      if (res.csrf_hash) {
        csrfToken = res.csrf_hash;
        $('#csrf-token').val(csrfToken);
      }
      if (res.status === 'success') {
        $('.cart-item-row[data-item-id="' + itemId + '"]').fadeOut(300, function() {
          $(this).remove();
        });
        updateCartTotals(res.grand_total, res.grand_total);
        if (res.total_items == 0) location.reload();
      } else {
        alert(res.message);
      }
    }, 'json').fail(() => {
      alert('Error server');
    });
  });
});
</script>