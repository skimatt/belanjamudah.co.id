<div class="container mx-auto px-4 py-8">
  <h1 class="text-3xl font-bold text-gray-800 mb-6">Proses Checkout</h1>

  <?php if ($this->session->flashdata('error')): ?>
  <div class="p-3 mb-4 text-sm text-red-700 bg-red-100 rounded-lg"><?= $this->session->flashdata('error'); ?></div>
  <?php endif; ?>

  <?= form_open('checkout/process', 'id="checkout-form"'); ?>
  <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>"
    value="<?= $this->security->get_csrf_hash(); ?>">
  <input type="hidden" id="shipping-cost-input" name="shipping_cost" value="0">
  <input type="hidden" id="total-weight-input" name="total_weight" value="<?= $total_weight ?? 0; ?>">

  <input type="hidden" id="discount-amount-input" name="discount_amount" value="0">
  <input type="hidden" id="voucher-code-input" name="voucher_code_used" value="">
  <input type="hidden" id="voucher-id-input" name="voucher_id_used" value="">
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <div class="lg:col-span-2 space-y-6">

      <div class="bg-white p-6 rounded-xl shadow-lg border-t-4 border-indigo-600">
        <h2 class="text-xl font-bold mb-4 flex items-center"><i class="ti ti-map-pin mr-2"></i> 1. Pilih Alamat
          Pengiriman</h2>
        <div class="space-y-4">
          <?php if (!empty($addresses)): ?>
          <?php foreach ($addresses as $addr): ?>
          <label
            class="block border p-4 rounded-lg hover:bg-gray-100 cursor-pointer <?= $addr->is_main ? 'border-indigo-600 bg-indigo-50' : ''; ?>">
            <input type="radio" name="address_id" value="<?= $addr->id; ?>" <?= $addr->is_main ? 'checked' : ''; ?>
              required class="mr-2 address-radio-btn">
            <span class="font-semibold"><?= html_escape($addr->label); ?></span> |
            <span class="text-sm text-gray-700"><?= html_escape($addr->recipient_name); ?>
              (<?= $addr->phone_number; ?>)</span>
            <?php if ($addr->is_main): ?><span
              class="badge bg-indigo-600 text-white text-xs px-2 py-1 ml-2 rounded-full">UTAMA</span><?php endif; ?><br>
            <span class="text-xs text-gray-600 ml-6 truncate block"><?= html_escape($addr->address_line_1); ?>,
              <?= html_escape($addr->city); ?> <?= $addr->postal_code; ?></span>
          </label>
          <?php endforeach; ?>
          <a href="<?= site_url('account/form_address'); ?>"
            class="text-sm text-indigo-600 hover:underline block mt-2">Tambah Alamat Baru</a>
          <?php else: ?>
          <div class="p-4 bg-red-100 text-red-700 rounded-lg">Mohon tambahkan alamat pengiriman!</div>
          <?php endif; ?>
        </div>

        <div class="mt-4 pt-3 border-t border-gray-200">
          <label for="seller_notes" class="block text-sm font-medium text-gray-700">Catatan untuk Penjual
            (Opsional)</label>
          <textarea id="seller_notes" name="seller_notes" rows="2"
            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 text-sm"
            placeholder="Contoh: Tolong bungkus dengan bubble wrap tebal..."></textarea>
        </div>
      </div>

      <div class="bg-white p-6 rounded-xl shadow-md">
        <h2 class="text-xl font-bold mb-4 flex items-center"><i class="ti ti-truck mr-2"></i> 2. Pilihan Pengiriman</h2>
        <p class="text-sm text-gray-600 mb-4">Total Berat Pesanan: **<?= $total_weight ?? 0; ?>** gram</p>

        <div class="space-y-4">
          <?php 
                        $couriers_data = ['JNE' => 15000, 'J&T' => 17000, 'SiCepat' => 14000];
                        $service_options = ['Reguler' => '2-4 Hari', 'Express' => '1-2 Hari'];
                        
                        foreach ($couriers_data as $name => $cost): ?>
          <?php foreach ($service_options as $service => $etd): ?>
          <label class="block border p-3 rounded-lg flex justify-between items-center hover:bg-gray-50 cursor-pointer">
            <span class="flex items-center">
              <input type="radio" name="shipping_service" value="<?= $name . '-' . $service; ?>" required
                class="mr-3 courier-radio-btn" data-base-cost="<?= $cost; ?>">
              <div>
                <span class="font-semibold"><?= $name; ?> (<?= $service; ?>)</span>
                <p class="text-xs text-gray-500">Estimasi Tiba: <?= $etd; ?></p>
              </div>
            </span>
            <span class="text-sm text-gray-700 courier-cost" data-base-cost="<?= $cost; ?>">
              <span id="cost-<?= $name . '-' . $service; ?>">Rp 0</span>
            </span>
          </label>
          <?php endforeach; ?>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="bg-white p-6 rounded-xl shadow-md">
        <h2 class="text-xl font-bold mb-4 flex items-center"><i class="ti ti-credit-card mr-2"></i> 3. Metode Pembayaran
        </h2>
        <div class="space-y-3">
          <?php 
                        $payment_methods_final = [
                            'COD' => 'Bayar di Tempat (COD)',
                            'DANA' => 'E-Wallet DANA',
                            'XENDIT' => 'Kartu Kredit / VA Lain (Xendit)',
                            'TRANSFER_MANUAL' => 'Transfer Bank Manual'
                        ];
                        
                        foreach ($payment_methods_final as $code => $name): ?>
          <label class="block border p-3 rounded-lg hover:bg-gray-50 cursor-pointer payment-method-label"
            data-code="<?= $code; ?>">
            <input type="radio" name="payment_method" value="<?= $code; ?>" required class="mr-2 payment-method-radio">
            <span class="font-semibold"><?= $name; ?></span>
            <?php if ($code === 'COD'): ?>
            <span class="text-xs text-red-500 ml-2">(Maksimal Total Order Rp 500.000)</span>
            <?php endif; ?>
          </label>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="bg-white p-6 rounded-xl shadow-md">
        <h2 class="text-xl font-bold mb-4">4. Detail Produk</h2>
        <div class="divide-y divide-gray-200">
          <?php foreach ($cart_data->items as $item): ?>
          <div class="flex items-start justify-between py-3">
            <div class="flex items-center space-x-4">
              <img src="<?= base_url($item->image_path); ?>" alt="<?= html_escape($item->product_name); ?>"
                class="w-16 h-16 object-cover rounded">
              <div>
                <p class="font-semibold"><?= html_escape($item->product_name); ?></p>
                <p class="text-sm text-gray-500">Varian: <?= html_escape($item->variant_name); ?></p>
                <p class="text-xs text-gray-600">Qty: <?= $item->quantity; ?> x
                  <?= format_rupiah($item->price_at_add); ?></p>
              </div>
            </div>
            <div class="font-bold text-indigo-600">
              <?= format_rupiah($item->subtotal); ?>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <div class="lg:col-span-1">
      <div class="bg-white p-6 rounded-xl shadow-lg sticky top-20">
        <h3 class="text-xl font-bold mb-4 border-b pb-2">Ringkasan Tagihan</h3>

        <div class="flex space-x-2 mb-4">
          <input type="text" id="voucher-input" placeholder="Kode Voucher"
            class="flex-1 border-gray-300 rounded-lg py-2 px-3 text-sm">
          <button type="button" id="apply-voucher-btn"
            class="bg-indigo-600 text-white text-sm py-2 px-4 rounded-lg hover:bg-indigo-700 transition duration-150">Terapkan</button>
        </div>

        <div class="space-y-2 border-b pb-3">
          <div class="flex justify-between text-gray-600">
            <span>Subtotal Barang (<?= count($cart_data->items); ?> item)</span>
            <span><?= format_rupiah($cart_data->total); ?></span>
          </div>
          <div class="flex justify-between text-gray-600">
            <span>Biaya Pengiriman</span>
            <span id="shipping-cost-display" class="font-semibold text-orange-500">Rp 0</span>
          </div>
          <div class="flex justify-between text-gray-600">
            <span>Diskon Voucher</span>
            <span id="discount-display" class="font-semibold text-green-600">- Rp 0</span>
          </div>
        </div>

        <div class="flex justify-between mt-4 pt-4 border-t-2 border-indigo-600">
          <span class="text-lg font-bold text-gray-900">Total Tagihan</span>
          <span class="text-2xl font-extrabold text-red-600"
            id="total-tagihan-display"><?= format_rupiah($cart_data->total); ?></span>
        </div>

        <div class="flex items-start mt-4 pt-2 border-t border-gray-200">
          <input type="checkbox" id="agree_terms" required
            class="mt-1 mr-2 h-4 w-4 text-indigo-600 border-gray-300 rounded">
          <label for="agree_terms" class="text-sm text-gray-600">
            Saya telah membaca & setuju dengan <a href="#" class="text-indigo-600 hover:underline">Syarat &
              Ketentuan</a>.
          </label>
        </div>

        <p class="text-xs text-red-500 mt-2 hidden" id="error-message">Harap lengkapi semua langkah dan setujui S&K!</p>
        <p class="text-xs text-red-700 mt-2 hidden" id="cod-error-message-dynamic">Maaf, COD hanya berlaku untuk total
          tagihan di bawah Rp 500.000.</p>

        <button type="submit" id="pay-button"
          class="w-full block text-center mt-6 bg-indigo-600 text-white font-bold py-3 rounded-lg hover:bg-indigo-700 transition duration-200"
          disabled>
          BUAT PESANAN DAN BAYAR
        </button>
      </div>
    </div>
  </div>
  <?= form_close(); ?>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
// Logic Sederhana Format Rupiah
function formatRupiah(number) {
  if (isNaN(number) || number === null) return 'Rp 0';
  return 'Rp ' + number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

$(document).ready(function() {
  // --- Variabel Konstan dari PHP (Perbaikan Kritis di Sini!) ---
  // Pastikan subtotal diinisialisasi sebagai float, dengan fallback aman ke 0
  const subtotal = parseFloat('<?= $cart_data->total; ?>') || 0;
  const totalWeight = <?= $total_weight ?? 0; ?>;
  const maxCodAmount = 500000;

  // --- Elements ---
  const shippingCostInput = $('#shipping-cost-input');
  const totalTagihanDisplay = $('#total-tagihan-display');
  const payButton = $('#pay-button');
  const agreeTermsCheckbox = $('#agree_terms');
  const codErrorMessage = $('#cod-error-message-dynamic');
  const errorMessage = $('#error-message');
  const discountDisplay = $('#discount-display');
  const voucherInput = $('#voucher-input');

  let calculatedShippingCost = 0;
  let currentDiscount = 0; // State diskon saat ini
  const csrfName = "<?= $this->security->get_csrf_token_name(); ?>";
  let csrfToken = "<?= $this->security->get_csrf_hash(); ?>";


  // FUNGSI UTAMA: UPDATE SUMMARY & VALIDASI
  function updateSummary() {
    const addressSelected = $('input[name="address_id"]:checked').length > 0;
    const courierSelected = $('input[name="shipping_service"]:checked').length > 0;
    const paymentSelected = $('input[name="payment_method"]:checked').length > 0;
    const selectedPaymentCode = $('input[name="payment_method"]:checked').val();

    // PASTIKAN SEMUA NILAI ADALAH NUMERIK
    const finalTotal = subtotal + calculatedShippingCost - currentDiscount;

    // 1. Update Tampilan
    $('#shipping-cost-display').text(formatRupiah(calculatedShippingCost));
    shippingCostInput.val(calculatedShippingCost);
    totalTagihanDisplay.text(formatRupiah(finalTotal));

    // Update display diskon
    discountDisplay.text('- ' + formatRupiah(currentDiscount));
    $('#discount-amount-input').val(currentDiscount);

    // 2. Validasi COD Limit
    let isCodValid = true;
    codErrorMessage.addClass('hidden');

    if (selectedPaymentCode === 'COD' && finalTotal > maxCodAmount) {
      isCodValid = false;
      codErrorMessage.removeClass('hidden').text(`Maaf, COD melebihi batas (${formatRupiah(maxCodAmount)}).`);
    }

    // 3. Aktivasi Tombol
    const isReadyToPay = addressSelected && courierSelected && paymentSelected && isCodValid && agreeTermsCheckbox
      .is(':checked');

    payButton.prop('disabled', !isReadyToPay);

    // 4. Tampilkan Pesan Error
    if (!isReadyToPay && !isCodValid) {
      // Error COD tampil
    } else if (!isReadyToPay && !agreeTermsCheckbox.is(':checked')) {
      errorMessage.removeClass('hidden').text('Mohon setujui Syarat & Ketentuan.');
    } else if (!isReadyToPay) {
      errorMessage.removeClass('hidden').text('Harap lengkapi semua pilihan di atas.');
    } else {
      errorMessage.addClass('hidden');
    }
  }

  // FUNGSI SIMULASI ONGKIR (Menggunakan Total Berat)
  function calculateShippingCost() {
    const selectedCourierRadio = $('input[name="shipping_service"]:checked');

    if (selectedCourierRadio.length) {
      // Ambil Biaya Dasar dari data-base-cost pada elemen radio button
      const baseCost = parseFloat(selectedCourierRadio.data('base-cost'));
      const weightFactor = Math.ceil(totalWeight / 1000);

      calculatedShippingCost = baseCost * Math.max(1, weightFactor);
    } else {
      calculatedShippingCost = 0;
    }

    // Perbarui Tampilan Harga Semua Layanan (PENTING untuk UX)
    $('input[name="shipping_service"]').each(function() {
      const radio = $(this);
      const costContainer = radio.closest('label').find('.courier-cost span');
      const baseCost = parseFloat(radio.data('base-cost'));

      const weightFactor = Math.ceil(totalWeight / 1000);
      const finalCost = baseCost * Math.max(1, weightFactor);

      costContainer.text(formatRupiah(finalCost));
    });

    updateSummary();
  }

  // --- FUNGSI VOUCHER AJAX (Tetap Sama) ---
  $('#apply-voucher-btn').on('click', function() {
    // ... (Logika AJAX Voucher) ...
    const voucherCode = voucherInput.val();

    if (!voucherCode) {
      alert('Masukkan kode voucher.');
      return;
    }

    const subtotalForValidation = subtotal + calculatedShippingCost;

    let postData = {
      voucher_code: voucherCode,
      subtotal_amount: subtotalForValidation
    };
    postData[csrfName] = csrfToken;

    $.ajax({
      url: "<?= site_url('checkout/validate_voucher_ajax'); ?>",
      method: 'POST',
      dataType: 'json',
      data: postData,
      beforeSend: function() {
        $('#apply-voucher-btn').prop('disabled', true).text('Memproses...');
        // Clear previous messages
        discountDisplay.text('- Rp 0');
        currentDiscount = 0;
        updateSummary();
      },
      success: function(response) {
        if (response.csrf_hash) {
          csrfToken = response.csrf_hash;
          $('#csrf-token').val(csrfToken);
        }

        if (response.status === 'success') {
          // MENGAMBIL NILAI DISKON DARI RESPONSE
          currentDiscount = parseFloat(response.discount_amount);
          $('#voucher-code-input').val(response.code);
          $('#voucher-id-input').val(response.voucher_id);
          alert('Voucher berhasil diterapkan! Diskon: ' + formatRupiah(currentDiscount));
        } else {
          currentDiscount = 0;
          $('#voucher-code-input, #voucher-id-input').val('');
          alert('Gagal: ' + response.message);
        }

        updateSummary();
      },
      error: function(xhr) {
        alert('Terjadi kesalahan server saat validasi voucher. Mohon coba lagi.');
      },
      complete: function() {
        $('#apply-voucher-btn').prop('disabled', false).text('Terapkan');
      }
    });
  });

  // EVENT LISTENERS
  $('input[name="address_id"]').on('change', calculateShippingCost);
  $('input[name="shipping_service"]').on('change', calculateShippingCost);
  $('input[name="payment_method"]').on('change', updateSummary);
  agreeTermsCheckbox.on('change', updateSummary);

  // Inisialisasi awal
  calculateShippingCost();
});
</script>