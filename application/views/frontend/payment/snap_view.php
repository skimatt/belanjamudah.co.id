<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

<div class="bg-slate-50 min-h-screen flex flex-col items-center justify-center p-6 font-sans text-slate-800">

  <div
    class="w-full max-w-md bg-white p-10 rounded-[2.5rem] shadow-2xl shadow-indigo-100/50 border border-white relative overflow-hidden text-center animate__animated animate__zoomIn">

    <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-indigo-500 to-blue-500"></div>

    <div class="mb-8 relative inline-block">
      <div class="absolute inset-0 bg-indigo-100 rounded-full animate-ping opacity-75"></div>
      <div class="relative bg-indigo-50 p-6 rounded-full text-indigo-600 border border-indigo-100">
        <i class="ti ti-loader-2 animate-spin text-5xl"></i>
      </div>
    </div>

    <h1 class="text-2xl font-black text-slate-900 mb-2 tracking-tight">
      Menghubungkan...
    </h1>
    <p class="text-slate-500 text-sm mb-8 px-4 leading-relaxed">
      Mohon tunggu, kami sedang membuka gerbang pembayaran aman untuk pesanan Anda.
    </p>

    <div
      class="inline-flex items-center justify-center bg-slate-100 rounded-full px-5 py-2 mb-8 border border-slate-200 border-dashed">
      <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mr-2">Order ID</span>
      <span class="text-sm font-mono font-bold text-slate-800 select-all"><?= $order_id; ?></span>
    </div>

    <button id="pay-button"
      class="w-full group relative overflow-hidden bg-indigo-600 text-white font-bold py-3.5 rounded-2xl shadow-lg shadow-indigo-200 hover:bg-indigo-700 hover:shadow-indigo-300 transition-all duration-300 transform hover:-translate-y-0.5 active:scale-95 focus:outline-none focus:ring-4 focus:ring-indigo-500/30">
      <span class="relative z-10 flex items-center justify-center gap-2">
        <i class="ti ti-credit-card"></i> Bayar Sekarang
      </span>
      <div
        class="absolute inset-0 h-full w-full bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:animate-[shimmer_1.5s_infinite]">
      </div>
    </button>

    <div class="mt-8 flex flex-col items-center gap-3">
      <p class="text-[10px] text-gray-400 uppercase tracking-widest font-semibold flex items-center gap-1">
        <i class="ti ti-lock"></i> Secured by Midtrans
      </p>
      <div class="flex gap-3 opacity-40 grayscale transition-all hover:grayscale-0 hover:opacity-100">
        <img src="https://upload.wikimedia.org/wikipedia/commons/8/86/Midtrans.png" class="h-4 object-contain"
          alt="Midtrans">
        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/f/fa/Visa_2021.svg/1200px-Visa_2021.svg.png"
          class="h-3 object-contain" alt="Visa">
        <img
          src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2a/Mastercard-logo.svg/1280px-Mastercard-logo.svg.png"
          class="h-4 object-contain" alt="Mastercard">
      </div>
    </div>
  </div>

  <p class="mt-6 text-xs text-gray-400 text-center max-w-xs leading-normal">
    Jika popup pembayaran tidak muncul otomatis, silakan klik tombol <strong>"Bayar Sekarang"</strong> di atas.
  </p>
</div>

<style>
@keyframes shimmer {
  100% {
    transform: translateX(100%);
  }
}
</style>

<script src="https://app.sandbox.midtrans.com/snap/snap.js"  
  data-client-key="<?= $this->config->item('midtrans_client_key'); ?>"></script>

<script type="text/javascript">
const snapToken = <?= json_encode($snap_token); ?>;

// Logika tombol tetap sama
document.getElementById('pay-button').onclick = function() {
  snap.pay(snapToken, {
    onSuccess: function(result) {
      console.log('Success:', result);
      // Sedikit modifikasi alert agar lebih modern (opsional, tapi logic redirect tetap)
      alert("Pembayaran Berhasil!");
      window.location.href = '<?= site_url('order/invoice/' . $order_id); ?>';
    },
    onPending: function(result) {
      console.log('Pending:', result);
      alert("Pembayaran Tertunda. Silakan selesaikan pembayaran Anda.");
      window.location.href = '<?= site_url('order/invoice/' . $order_id); ?>';
    },
    onError: function(result) {
      console.log('Error:', result);
      alert("Pembayaran Gagal. Silakan coba lagi.");
      window.location.href = '<?= site_url('order/invoice/' . $order_id); ?>';
    },
    onClose: function() {
      alert('Anda menutup jendela pembayaran tanpa menyelesaikan transaksi.');
    }
  });
};

// Auto trigger modal Snap tetap berjalan
document.addEventListener('DOMContentLoaded', function() {
  // Memberi delay sedikit (500ms) agar user sempat melihat UI loading yang cantik sebelum tertutup popup
  setTimeout(function() {
    document.getElementById('pay-button').click();
  }, 800);
});
</script>