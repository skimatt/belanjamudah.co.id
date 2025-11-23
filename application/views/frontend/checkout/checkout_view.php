<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container mx-auto px-4">

  <div class="w-full px-4 sm:px-6 lg:px-12 py-10">





    <!-- <div class="flex items-center justify-between mb-6 border-b border-gray-200 pb-4">
      <h1 class="text-2xl font-bold text-slate-800 flex items-center">
        <span class="bg-indigo-600 text-white p-1.5 rounded-lg mr-3 shadow-md shadow-indigo-200">
          <i class="ti ti-lock text-lg"></i>
        </span>
        Checkout
      </h1>
      <div
        class="hidden md:flex text-xs font-bold text-gray-400 bg-white px-4 py-2 rounded-full border border-gray-100 shadow-sm">
        <span class="text-indigo-600">1. Keranjang</span>
        <span class="mx-2 text-gray-300">/</span>
        <span class="text-slate-800">2. Pengiriman</span>
        <span class="mx-2 text-gray-300">/</span>
        <span>3. Selesai</span>
      </div>
    </div> -->

    <?php if ($this->session->flashdata('error')): ?>
    <div
      class="flex items-center p-4 mb-6 text-sm text-red-800 bg-white rounded-2xl border-l-4 border-red-500 shadow-sm animate__animated animate__headShake"
      role="alert">
      <i class="ti ti-alert-circle text-xl mr-3 text-red-600"></i>
      <span class="font-semibold"><?= $this->session->flashdata('error'); ?></span>
    </div>
    <?php endif; ?>

    <?= form_open('checkout/process', 'id="checkout-form"'); ?>

    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>"
      value="<?= $this->security->get_csrf_hash(); ?>">
    <input type="hidden" id="shipping-cost-input" name="shipping_cost" value="0">
    <input type="hidden" id="total-weight-input" name="total_weight" value="<?= $total_weight ?? 0; ?>">
    <input type="hidden" id="discount-amount-input" name="discount_amount" value="0">
    <input type="hidden" id="voucher-code-input" name="voucher_code_used" value="">
    <input type="hidden" id="voucher-id-input" name="voucher_id_used" value="">

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

      <div class="lg:col-span-8 space-y-6 animate__animated animate__fadeInLeft">

        <div class="bg-white p-6 rounded-[1.5rem] shadow-sm border border-indigo-50/50 relative overflow-hidden">
          <div class="flex justify-between items-center mb-4 relative z-10">
            <h2 class="text-lg font-bold text-slate-800 flex items-center">
              <i class="ti ti-map-pin-filled text-indigo-500 mr-2 text-xl"></i> Alamat Pengiriman
            </h2>
            <a href="<?= site_url('account/form_address'); ?>"
              class="text-xs font-bold text-indigo-600 bg-indigo-50 px-3 py-1.5 rounded-full hover:bg-indigo-100 transition-colors">
              + Alamat Baru
            </a>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-3 relative z-10">
            <?php if (!empty($addresses)): ?>
            <?php foreach ($addresses as $addr): ?>
            <label class="cursor-pointer relative">
              <input type="radio" name="address_id" value="<?= $addr->id; ?>" <?= $addr->is_main ? 'checked' : ''; ?>
                class="peer sr-only">

              <div
                class="h-full p-4 rounded-2xl border border-gray-200 bg-white hover:border-indigo-300 transition-all duration-200 peer-checked:border-indigo-600 peer-checked:bg-indigo-50/20 peer-checked:shadow-sm">
                <div class="flex justify-between items-start mb-1">
                  <span
                    class="font-bold text-sm text-slate-800 line-clamp-1"><?= html_escape($addr->recipient_name); ?></span>
                  <?php if ($addr->is_main): ?>
                  <span class="text-[10px] font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded">UTAMA</span>
                  <?php endif; ?>
                </div>
                <p class="text-xs text-slate-500 leading-relaxed mb-2 line-clamp-2">
                  <?= html_escape($addr->address_line_1); ?>, <?= html_escape($addr->city); ?>
                </p>
                <div class="text-xs font-semibold text-slate-600">
                  <i class="ti ti-phone text-[10px] mr-1"></i><?= $addr->phone_number; ?>
                </div>

                <div
                  class="absolute bottom-3 right-3 text-indigo-600 opacity-0 peer-checked:opacity-100 transition-opacity scale-0 peer-checked:scale-100">
                  <i class="ti ti-circle-check-filled text-lg"></i>
                </div>
              </div>
            </label>
            <?php endforeach; ?>
            <?php else: ?>
            <div
              class="col-span-full p-4 bg-red-50 text-red-600 text-sm rounded-xl border border-red-100 flex items-center justify-center">
              <i class="ti ti-alert-triangle mr-2"></i> Wajib tambah alamat.
            </div>
            <?php endif; ?>
          </div>

          <div class="mt-4 pt-3 border-t border-gray-100">
            <div class="relative">
              <i class="ti ti-pencil absolute left-3 top-2.5 text-gray-400 text-sm"></i>
              <input type="text" id="seller_notes" name="seller_notes"
                class="w-full pl-9 pr-3 py-2 bg-slate-50 border-transparent rounded-xl text-xs focus:bg-white focus:ring-2 focus:ring-indigo-500/20 transition-all"
                placeholder="Catatan (Opsional)">
            </div>
          </div>
        </div>

        <div class="bg-white p-6 rounded-[1.5rem] shadow-sm border border-indigo-50/50">
          <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center">
            <i class="ti ti-package text-indigo-500 mr-2 text-xl"></i> Produk
          </h2>
          <div class="space-y-3">
            <?php foreach ($cart_data->items as $item): ?>
            <div
              class="flex items-center p-3 bg-slate-50/50 rounded-2xl border border-gray-100 hover:bg-white transition-colors">
              <div class="w-14 h-14 bg-white rounded-xl overflow-hidden border border-gray-200 flex-shrink-0">
                <img src="<?= base_url($item->image_path); ?>" class="w-full h-full object-cover">
              </div>
              <div class="ml-4 flex-1 min-w-0">
                <h3 class="font-bold text-slate-800 text-sm truncate"><?= html_escape($item->product_name); ?></h3>
                <div class="text-xs text-slate-500 mt-0.5">
                  <?= html_escape($item->variant_name); ?> • <span
                    class="text-slate-700 font-medium"><?= $item->quantity; ?> pcs</span>
                </div>
              </div>
              <div class="text-right pl-3">
                <span class="text-sm font-bold text-indigo-600"><?= format_rupiah($item->subtotal); ?></span>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
          <div class="bg-white p-6 rounded-[1.5rem] shadow-sm border border-indigo-50/50">
            <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center">
              <i class="ti ti-truck text-orange-500 mr-2 text-xl"></i> Kurir
            </h2>
            <div class="space-y-2 h-48 overflow-y-auto pr-1 scrollbar-thin">
              <?php 
                            $couriers_data = ['JNE' => 15000, 'J&T' => 17000, 'SiCepat' => 14000];
                            $service_options = ['Reguler' => '2-4 Hari', 'Express' => '1-2 Hari'];
                            foreach ($couriers_data as $name => $cost): 
                                foreach ($service_options as $service => $etd): ?>
              <label class="cursor-pointer relative block">
                <input type="radio" name="shipping_service" value="<?= $name . '-' . $service; ?>" class="peer sr-only"
                  data-base-cost="<?= $cost; ?>">
                <div
                  class="p-3 rounded-xl border border-gray-200 hover:bg-orange-50 transition-all peer-checked:border-orange-500 peer-checked:bg-orange-50 peer-checked:shadow-sm flex justify-between items-center">
                  <div class="flex items-center gap-2">
                    <div
                      class="w-4 h-4 rounded-full border border-gray-300 peer-checked:border-orange-500 peer-checked:bg-orange-500 flex items-center justify-center">
                      <div class="w-1.5 h-1.5 bg-white rounded-full opacity-0 peer-checked:opacity-100"></div>
                    </div>
                    <div>
                      <span class="font-bold text-xs text-slate-800 block"><?= $name; ?> - <?= $service; ?></span>
                      <span class="text-[10px] text-green-600">Est: <?= $etd; ?></span>
                    </div>
                  </div>
                  <span class="font-bold text-xs text-slate-700 courier-cost">
                    <span id="cost-<?= $name . '-' . $service; ?>">Rp 0</span>
                  </span>
                </div>
              </label>
              <?php endforeach; endforeach; ?>
            </div>
          </div>

          <div class="bg-white p-6 rounded-[1.5rem] shadow-sm border border-indigo-50/50">
            <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center">
              <i class="ti ti-wallet text-blue-500 mr-2 text-xl"></i> Pembayaran
            </h2>
            <div class="space-y-2">
              <label class="cursor-pointer relative block">
                <input type="radio" name="payment_method" value="MIDTRANS_GATEWAY" class="peer sr-only">
                <div
                  class="p-3 rounded-xl border border-gray-200 hover:bg-blue-50 transition-all peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:shadow-sm flex items-center gap-3">
                  <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center text-blue-600 shadow-sm"><i
                      class="ti ti-building-bank"></i></div>
                  <div class="flex-1">
                    <span class="font-bold text-xs text-slate-800 block">Transfer / QRIS</span>
                    <span class="text-[10px] text-gray-500">Midtrans Auto</span>
                  </div>
                  <div
                    class="w-4 h-4 rounded-full border border-gray-300 peer-checked:border-blue-500 peer-checked:bg-blue-500">
                  </div>
                </div>
              </label>

              <label class="cursor-pointer relative block">
                <input type="radio" name="payment_method" value="COD" class="peer sr-only">
                <div
                  class="p-3 rounded-xl border border-gray-200 hover:bg-blue-50 transition-all peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:shadow-sm flex items-center gap-3">
                  <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center text-green-600 shadow-sm"><i
                      class="ti ti-cash"></i></div>
                  <div class="flex-1">
                    <span class="font-bold text-xs text-slate-800 block">COD</span>
                    <span class="text-[10px] text-red-500 bg-red-50 px-1 rounded">Maks 500rb</span>
                  </div>
                  <div
                    class="w-4 h-4 rounded-full border border-gray-300 peer-checked:border-blue-500 peer-checked:bg-blue-500">
                  </div>
                </div>
              </label>
            </div>
          </div>
        </div>

      </div>

      <div class="lg:col-span-4 animate__animated animate__fadeInRight">
        <div class="bg-white p-6 rounded-[1.5rem] shadow-lg border border-indigo-100 sticky top-20">

          <h3 class="text-lg font-bold text-slate-800 mb-5 border-b border-dashed border-gray-200 pb-3">
            Ringkasan
          </h3>

          <div class="mb-5">
            <div class="flex gap-2">
              <div class="relative flex-1">
                <i class="ti ti-ticket absolute left-3 top-2.5 text-gray-400"></i>
                <input type="text" id="voucher-input" placeholder="Kode Promo"
                  class="w-full pl-9 pr-3 py-2 bg-slate-50 border-transparent rounded-xl text-xs font-bold focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
              </div>
              <button type="button" id="apply-voucher-btn"
                class="bg-slate-800 text-white px-3 py-2 rounded-xl text-xs font-bold hover:bg-indigo-600 transition-colors">
                Pakai
              </button>
            </div>
          </div>

          <div class="space-y-3 mb-5 text-sm">
            <div class="flex justify-between text-slate-500">
              <span>Total Barang</span>
              <span class="text-slate-800 font-bold"><?= format_rupiah($cart_data->total); ?></span>
            </div>
            <div class="flex justify-between text-slate-500">
              <span>Ongkir</span>
              <span id="shipping-cost-display" class="text-slate-800 font-bold">Rp 0</span>
            </div>
            <div class="flex justify-between text-green-600 text-xs">
              <span>Diskon</span>
              <span id="discount-display" class="font-bold">- Rp 0</span>
            </div>
          </div>

          <div class="flex justify-between items-center pt-4 border-t border-gray-200 mb-5">
            <span class="text-slate-800 font-bold text-sm">Total Bayar</span>
            <span id="total-tagihan-display" class="text-2xl font-black text-indigo-600">
              <?= format_rupiah($cart_data->total); ?>
            </span>
          </div>

          <label class="flex items-start gap-2 mb-5 cursor-pointer">
            <input type="checkbox" id="agree_terms"
              class="mt-0.5 w-3.5 h-3.5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
            <span class="text-[11px] text-slate-500 leading-tight">
              Saya setuju dengan <a href="#" class="text-indigo-600 underline">S&K</a>.
            </span>
          </label>

          <div id="error-message"
            class="hidden p-2 mb-3 bg-red-50 text-red-600 text-[10px] rounded-lg border border-red-100 text-center font-bold">
          </div>
          <div id="cod-error-message-dynamic"
            class="hidden p-2 mb-3 bg-orange-50 text-orange-700 text-[10px] rounded-lg border border-orange-100 text-center font-bold">
          </div>

          <button type="submit" id="pay-button" disabled
            class="w-full bg-slate-900 text-white font-bold text-sm py-3.5 rounded-xl shadow-lg hover:bg-indigo-600 transition-all transform hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
            Buat Pesanan
          </button>

          <div class="mt-4 text-center">
            <p class="text-[10px] text-gray-400 flex items-center justify-center gap-1">
              <i class="ti ti-lock"></i> Pembayaran Aman
            </p>
          </div>

        </div>
      </div>

    </div>
    <?= form_close(); ?>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<style>
/* Scrollbar Halus untuk List Kurir */
.scrollbar-thin::-webkit-scrollbar {
  width: 4px;
}

.scrollbar-thin::-webkit-scrollbar-track {
  background: #f1f5f9;
}

.scrollbar-thin::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 4px;
}

.scrollbar-thin::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}
</style>

<script>
function formatRupiah(number) {
  if (isNaN(number) || number === null) return 'Rp 0';
  return 'Rp ' + number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

$(document).ready(function() {
  const subtotal = parseFloat('<?= $cart_data->total; ?>') || 0;
  const totalWeight = <?= $total_weight ?? 0; ?>;
  const maxCodAmount = 500000;

  const shippingCostInput = $('#shipping-cost-input');
  const totalTagihanDisplay = $('#total-tagihan-display');
  const payButton = $('#pay-button');
  const agreeTermsCheckbox = $('#agree_terms');
  const codErrorMessage = $('#cod-error-message-dynamic');
  const errorMessage = $('#error-message');
  const discountDisplay = $('#discount-display');
  const voucherInput = $('#voucher-input');

  let calculatedShippingCost = 0;
  let currentDiscount = 0;
  const csrfName = "<?= $this->security->get_csrf_token_name(); ?>";
  let csrfToken = "<?= $this->security->get_csrf_hash(); ?>";

  function updateSummary() {
    const addressSelected = $('input[name="address_id"]:checked').length > 0;
    const courierSelected = $('input[name="shipping_service"]:checked').length > 0;
    const paymentSelected = $('input[name="payment_method"]:checked').length > 0;
    const selectedPaymentCode = $('input[name="payment_method"]:checked').val();

    const totalAfterShipping = subtotal + calculatedShippingCost;
    const finalTotal = totalAfterShipping - currentDiscount;

    $('#shipping-cost-display').text(formatRupiah(calculatedShippingCost));
    shippingCostInput.val(calculatedShippingCost);
    totalTagihanDisplay.text(formatRupiah(finalTotal));

    discountDisplay.text('- ' + formatRupiah(currentDiscount));
    $('#discount-amount-input').val(currentDiscount);

    let isCodValid = true;
    codErrorMessage.addClass('hidden');

    if (selectedPaymentCode === 'COD' && finalTotal > maxCodAmount) {
      isCodValid = false;
      codErrorMessage.removeClass('hidden').text('COD maks 500rb');
    }

    const isReadyToPay = addressSelected && courierSelected && paymentSelected && isCodValid && agreeTermsCheckbox
      .is(':checked');

    payButton.prop('disabled', !isReadyToPay);

    if (!isReadyToPay && !isCodValid) {} else if (!isReadyToPay && !agreeTermsCheckbox.is(':checked')) {
      errorMessage.removeClass('hidden').text('Setujui S&K.');
    } else if (!isReadyToPay) {
      errorMessage.removeClass('hidden').text('Lengkapi Data.');
    } else {
      errorMessage.addClass('hidden');
    }
  }

  function calculateShippingCost() {
    const selectedCourierRadio = $('input[name="shipping_service"]:checked');
    if (selectedCourierRadio.length) {
      const baseCost = parseFloat(selectedCourierRadio.data('base-cost'));
      const weightFactor = Math.ceil(totalWeight / 1000);
      calculatedShippingCost = baseCost * Math.max(1, weightFactor);
    } else {
      calculatedShippingCost = 0;
    }
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

  // --- SWEETALERT POPUP PROMO ---
  $('#apply-voucher-btn').on('click', function() {
    const voucherCode = voucherInput.val();
    if (!voucherCode) {
      Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'warning',
        title: 'Masukkan kode promo!',
        showConfirmButton: false,
        timer: 2000
      });
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
        $('#apply-voucher-btn').prop('disabled', true).text('...');
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
          currentDiscount = parseFloat(response.discount_amount);
          $('#voucher-code-input').val(response.code);
          $('#voucher-id-input').val(response.voucher_id);

          // ANIMASI INDAH DISINI
          Swal.fire({
            title: 'Hore! Hemat ' + formatRupiah(currentDiscount),
            text: 'Kode voucher berhasil dipakai.',
            imageUrl: 'https://cdn-icons-png.flaticon.com/512/726/726476.png',
            imageWidth: 100,
            imageHeight: 100,
            imageAlt: 'Discount',
            confirmButtonText: 'Mantap',
            confirmButtonColor: '#4f46e5',
            background: '#fff',
            backdrop: `rgba(79, 70, 229, 0.2) left top no-repeat`,
            showClass: {
              popup: 'animate__animated animate__bounceIn'
            },
            hideClass: {
              popup: 'animate__animated animate__fadeOutUp'
            }
          });

        } else {
          currentDiscount = 0;
          $('#voucher-code-input, #voucher-id-input').val('');
          Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: response.message,
            confirmButtonColor: '#ef4444'
          });
        }
        updateSummary();
      },
      error: function() {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Server Error'
        });
      },
      complete: function() {
        $('#apply-voucher-btn').prop('disabled', false).text('Pakai');
      }
    });
  });

  $('input[name="address_id"]').on('change', calculateShippingCost);
  $('input[name="shipping_service"]').on('change', calculateShippingCost);
  $('input[name="payment_method"]').on('change', updateSummary);
  agreeTermsCheckbox.on('change', updateSummary);

  calculateShippingCost();
});
</script>