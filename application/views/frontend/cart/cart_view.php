<main class="container mx-auto px-4 py-8">
  <h1 class="text-3xl font-bold text-gray-800 mb-6">Keranjang Belanja</h1>

  <?php if ($this->session->flashdata('error')): ?>
  <div class="p-3 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
    <?= $this->session->flashdata('error'); ?>
  </div>
  <?php endif; ?>

  <?php if (!empty($cart_data->items)): ?>
  <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

    <div class="lg:col-span-8 space-y-4" id="cart-items-container">
      <?php foreach ($cart_data->items as $item): ?>
      <div class="bg-white p-4 rounded-xl shadow-lg border border-gray-100 flex items-start space-x-4 cart-item-row"
        data-item-id="<?= $item->id; ?>">

        <img src="<?= base_url($item->image_path ?: 'assets/img/placeholder.png'); ?>"
          alt="<?= html_escape($item->product_name); ?>" class="w-24 h-24 object-cover rounded-lg border flex-shrink-0">

        <div class="flex-1 min-w-0 pt-1">
          <a href="<?= site_url('product/' . $item->product_slug); ?>"
            class="font-semibold text-lg text-gray-900 hover:text-indigo-600 truncate block">
            <?= html_escape($item->product_name); ?>
          </a>
          <p class="text-sm text-gray-500 mb-1">Varian: <?= html_escape($item->variant_name); ?></p>

          <p class="text-sm text-gray-600">
            Harga Satuan: <span class="font-bold text-indigo-700"><?= format_rupiah($item->price_at_add); ?></span>
          </p>
        </div>

        <div class="flex flex-col items-end space-y-3 flex-shrink-0 w-36">

          <input type="number" value="<?= $item->quantity; ?>" min="1" max="<?= $item->current_stock; ?>"
            data-item-id="<?= $item->id; ?>"
            class="update-qty-input w-20 text-center border border-indigo-300 rounded-lg py-1 text-sm focus:border-indigo-500 transition duration-150">

          <p class="font-extrabold text-xl text-red-600 item-subtotal-display" data-item-id="<?= $item->id; ?>">
            <?= format_rupiah($item->subtotal); ?>
          </p>

          <button type="button" data-item-id="<?= $item->id; ?>"
            class="remove-item-btn text-gray-500 hover:text-red-600 text-xs transition duration-150 flex items-center space-x-1"
            title="Hapus Item">
            <i class="ti ti-trash w-4 h-4"></i> <span>Hapus</span>
          </button>
          <p class="text-xs text-red-500">Stok: <?= $item->current_stock; ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <div class="lg:col-span-4">
      <div class="bg-white p-6 rounded-xl shadow-2xl sticky top-20 border-t-4 border-teal-500">
        <h3 class="text-xl font-bold mb-4 border-b pb-2">Ringkasan Belanja</h3>

        <div class="mt-4 border-b pb-4">
          <h4 class="font-semibold mb-2 text-gray-700">Punya Voucher?</h4>
          <div class="flex space-x-2">
            <input type="text" placeholder="Masukkan Kode" class="flex-1 border-gray-300 rounded-lg py-2 px-3 text-sm">
            <button
              class="bg-indigo-100 text-indigo-700 text-sm py-2 px-4 rounded-lg hover:bg-indigo-200 transition duration-150">Terapkan</button>
          </div>
        </div>

        <div class="flex justify-between py-3">
          <span class="text-gray-600">Subtotal Barang (<?= count($cart_data->items); ?> Item)</span>
          <span class="font-semibold text-gray-900" id="cart-subtotal-display"
            data-total-amount="<?= $cart_data->total; ?>">
            <?= format_rupiah($cart_data->total); ?>
          </span>
        </div>

        <div class="flex justify-between pb-3 border-b">
          <span class="text-gray-600">Total Diskon</span>
          <span class="font-semibold text-green-600">- Rp 0</span>
        </div>

        <div class="flex justify-between mt-4 pt-4 border-t-2 border-teal-500">
          <span class="text-xl font-extrabold text-gray-900">Total Bayar</span>
          <span class="text-3xl font-extrabold text-red-600" id="cart-grandtotal-display">
            <?= format_rupiah($cart_data->total); ?>
          </span>
        </div>

        <a href="<?= site_url('checkout'); ?>"
          class="w-full block text-center mt-6 bg-red-600 text-white font-bold py-3 rounded-lg hover:bg-red-700 transition duration-200 shadow-md shadow-red-300">
          Lanjut ke Checkout (<?= count($cart_data->items); ?> Item)
        </a>
      </div>
    </div>
  </div>
  <?php else: ?>
  <div class="text-center bg-white p-12 rounded-xl shadow-lg border-t-4 border-indigo-600">
    <p class="text-2xl text-gray-500 mb-4"><i class="ti ti-shopping-cart-off mr-2"></i> Keranjang Anda kosong!</p>
    <a href="<?= site_url(); ?>" class="text-indigo-600 hover:text-indigo-700 font-semibold text-lg">Mulai belanja
      sekarang</a>
  </div>
  <?php endif; ?>
</main>

<input type="hidden" id="csrf-token" name="<?= $this->security->get_csrf_token_name(); ?>"
  value="<?= $this->security->get_csrf_hash(); ?>">

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
// Fungsi Format Rupiah (diambil dari helper)
function formatRupiah(number) {
  if (isNaN(number) || number === null) return 'Rp 0';
  return 'Rp ' + new Intl.NumberFormat('id-ID', {
    minimumFractionDigits: 0
  }).format(number);
}

$(document).ready(function() {
  const csrfName = "<?= $this->security->get_csrf_token_name(); ?>";
  let csrfToken = $('#csrf-token').val();

  // --- UPDATE TOTAL UI (Fungsi untuk merapikan kode) ---
  function updateCartTotals(subTotal, grandTotal) {
    $('#cart-subtotal-display').text(formatRupiah(subTotal));
    $('#cart-grandtotal-display').text(formatRupiah(grandTotal));
  }

  // --- UPDATE QTY (AJAX) ---
  $(document).on('change', '.update-qty-input', function() {
    const input = $(this);
    const itemId = input.data('item-id');
    let qty = parseInt(input.val());
    const maxStock = parseInt(input.attr('max'));

    // Validasi Klien
    if (isNaN(qty) || qty < 1) qty = 1;
    if (qty > maxStock) qty = maxStock;
    input.val(qty);

    $.ajax({
      url: "<?= site_url('cart/update_qty'); ?>",
      method: "POST",
      dataType: "json",
      data: {
        item_id: itemId,
        qty: qty,
        [csrfName]: csrfToken
      },
      success: function(res) {
        if (res.csrf_hash) {
          csrfToken = res.csrf_hash;
          $('#csrf-token').val(res.csrf_hash);
        }

        if (res.status === 'success') {
          // Update subtotal item dan total keranjang
          $('.item-subtotal-display[data-item-id="' + itemId + '"]').text(formatRupiah(res
            .item_subtotal));
          updateCartTotals(res.grand_total, res
            .grand_total); // Asumsi grandTotal = subTotal sebelum ongkir
        } else {
          alert(res.message);
          // Jika gagal (misal stok tidak cukup), reload untuk sinkronisasi
          location.reload();
        }
      },
      error: function() {
        alert('Terjadi kesalahan server saat update jumlah.');
        location.reload();
      }
    });
  });

  // --- REMOVE ITEM (AJAX) ---
  $(document).on('click', '.remove-item-btn', function() {
    const itemId = $(this).data('item-id');

    if (!confirm('Yakin ingin menghapus item ini dari keranjang?')) return;

    $.ajax({
      url: "<?= site_url('cart/remove_item'); ?>",
      method: "POST",
      dataType: "json",
      data: {
        item_id: itemId, // ← tambahkan ini
        [csrfName]: csrfToken
      },
      success: function(res) {
        if (res.csrf_hash) {
          csrfToken = res.csrf_hash;
          $('#csrf-token').val(res.csrf_hash);
        }

        if (res.status === 'success') {
          // Hapus row item dari DOM
          $('.cart-item-row[data-item-id="' + itemId + '"]').fadeOut(300, function() {
            $(this).remove();
          });

          // Update total keranjang
          updateCartTotals(res.grand_total, res.grand_total);

          // Redirect jika keranjang kosong
          if (res.total_items == 0) {
            location.reload();
          }
        } else {
          alert(res.message);
        }
      },
      error: function() {
        alert('Terjadi kesalahan saat menghapus item.');
      }
    });
  });
});
</script>