<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<div class="bg-slate-50 min-h-screen pb-20 font-sans text-slate-800">

  <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl py-8">

    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
      <div>
        <div class="flex items-center gap-3 mb-1">
          <a href="<?= site_url('order'); ?>"
            class="p-2 rounded-full bg-white border border-slate-200 text-slate-500 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
            <i class="ti ti-arrow-left"></i>
          </a>
          <h1 class="text-2xl font-black text-slate-900 tracking-tight">Lacak Pengiriman</h1>
        </div>
        <p class="text-sm text-slate-500 ml-12">
          Order ID: <span class="font-mono font-bold text-indigo-600">#<?= $order->id; ?></span> •
          <?= date('d F Y', strtotime($order->created_at)); ?>
        </p>
      </div>

      <div class="flex items-center gap-3 bg-white px-5 py-3 rounded-2xl shadow-sm border border-slate-200">
        <div class="text-right">
          <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Estimasi Tiba</p>
          <p class="text-lg font-black text-teal-600">
            <?= calculate_etd($order->shipping_courier, $order->order_status); ?>
          </p>
        </div>
        <div class="p-2 bg-teal-50 rounded-xl text-teal-600">
          <i class="ti ti-calendar-time text-2xl"></i>
        </div>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

      <div class="lg:col-span-8">
        <div
          class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-white overflow-hidden relative group h-[600px]">

          <div id="map" class="absolute inset-0 w-full h-full z-0 bg-slate-100"></div>

          <div
            class="absolute top-6 left-6 z-[400] bg-white/90 backdrop-blur-md p-4 rounded-2xl shadow-lg border border-white/50 max-w-xs animate__animated animate__fadeInDown">
            <div class="flex items-center gap-3 mb-3">
              <div
                class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 text-xl">
                <i class="ti ti-truck-delivery"></i>
              </div>
              <div>
                <p class="text-xs font-bold text-slate-400 uppercase">Kurir</p>
                <p class="font-bold text-slate-800"><?= html_escape($order->shipping_courier); ?></p>
              </div>
            </div>
            <div class="border-t border-dashed border-slate-200 pt-3">
              <p class="text-xs font-bold text-slate-400 uppercase mb-1">Nomor Resi</p>
              <div class="flex items-center justify-between bg-slate-50 px-3 py-2 rounded-lg border border-slate-100">
                <span class="font-mono font-bold text-slate-700 tracking-wide text-sm">
                  <?= html_escape($order->tracking_number) ?: 'BELUM ADA'; ?>
                </span>
                <button class="text-indigo-600 hover:text-indigo-800" title="Copy">
                  <i class="ti ti-copy"></i>
                </button>
              </div>
            </div>
          </div>

          <div class="absolute bottom-6 left-6 right-6 z-[400]">
            <div
              class="bg-slate-900/90 backdrop-blur-md text-white p-5 rounded-2xl shadow-2xl flex items-center justify-between">
              <div>
                <p class="text-xs font-bold text-slate-400 uppercase mb-1">Status Terkini</p>
                <p class="font-bold text-lg flex items-center gap-2">
                  <span class="relative flex h-3 w-3">
                    <span
                      class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                  </span>
                  <?= ucfirst($order->order_status); ?>
                </p>
              </div>
              <div class="hidden sm:block text-right">
                <p class="text-xs text-slate-400">Lokasi Paket:</p>
                <p class="font-bold text-sm" id="current-location-text">Sedang dalam perjalanan...</p>
              </div>
            </div>
          </div>

        </div>
      </div>

      <div class="lg:col-span-4">
        <div
          class="bg-white rounded-[2rem] shadow-lg border border-slate-100 p-8 h-full max-h-[600px] overflow-y-auto relative">

          <h3
            class="text-lg font-black text-slate-800 mb-6 flex items-center sticky top-0 bg-white z-10 pb-2 border-b border-slate-50">
            <i class="ti ti-list-details mr-2 text-indigo-500"></i> Riwayat Perjalanan
          </h3>

          <div class="relative border-l-2 border-slate-100 ml-3 space-y-8 pb-4">
            <?php 
                            $status_sequence = ['pending','paid','packing','shipped','delivered','completed'];
                            $status_data = [
                                'pending'   => ['label' => 'Pesanan Dibuat', 'desc' => 'Menunggu pembayaran Anda.', 'icon' => 'ti-file-invoice'],
                                'paid'      => ['label' => 'Pembayaran Diterima', 'desc' => 'Pembayaran berhasil diverifikasi.', 'icon' => 'ti-credit-card'],
                                'packing'   => ['label' => 'Sedang Dikemas', 'desc' => 'Penjual sedang menyiapkan paket.', 'icon' => 'ti-box'],
                                'shipped'   => ['label' => 'Dalam Pengiriman', 'desc' => 'Paket diserahkan ke kurir.', 'icon' => 'ti-truck'],
                                'delivered' => ['label' => 'Paket Tiba', 'desc' => 'Paket telah sampai di alamat tujuan.', 'icon' => 'ti-home-check'],
                                'completed' => ['label' => 'Selesai', 'desc' => 'Transaksi selesai.', 'icon' => 'ti-check']
                            ];
                            
                            $current_status = $order->order_status;
                            $current_index = array_search($current_status, $status_sequence);
                            
                            // Reverse loop agar status terbaru di atas (Opsional, Amazon biasanya terbaru di atas)
                            // Disini kita pakai urutan normal (atas ke bawah)
                            for ($i = 0; $i < count($status_sequence); $i++):
                                $status_key = $status_sequence[$i];
                                $info = $status_data[$status_key];
                                $is_passed = $i <= $current_index;
                                $is_current = $i == $current_index;
                        ?>

            <div class="ml-8 relative group">
              <span class="absolute -left-[2.65rem] flex h-8 w-8 items-center justify-center rounded-full ring-4 ring-white 
                                <?= $is_passed 
                                    ? ($is_current ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-300 scale-110' : 'bg-green-500 text-white') 
                                    : 'bg-slate-100 text-slate-300'; ?> transition-all duration-300">
                <i class="ti <?= $info['icon']; ?> text-sm"></i>
              </span>

              <div
                class="<?= $is_current ? 'bg-indigo-50/50 p-4 rounded-2xl border border-indigo-100' : ''; ?> transition-all">
                <h4 class="font-bold text-sm <?= $is_passed ? 'text-slate-800' : 'text-slate-400'; ?>">
                  <?= $info['label']; ?>
                </h4>
                <p class="text-xs mt-1 leading-relaxed <?= $is_passed ? 'text-slate-600' : 'text-slate-400'; ?>">
                  <?= $info['desc']; ?>
                </p>

                <?php if ($is_passed): ?>
                <span class="text-[10px] font-bold text-slate-400 mt-2 block">
                  <?= ($is_current) ? date('d M Y, H:i') : ''; // Simulasi tanggal ?>
                </span>
                <?php endif; ?>
              </div>
            </div>
            <?php endfor; ?>
          </div>

          <div class="mt-8 pt-6 border-t border-dashed border-slate-200 text-center">
            <p class="text-xs text-slate-400 mb-3">Butuh bantuan terkait pengiriman?</p>
            <a href="#"
              class="inline-flex items-center justify-center px-4 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:border-indigo-600 hover:text-indigo-600 transition-all">
              <i class="ti ti-headset mr-2"></i> Hubungi CS
            </a>
          </div>

        </div>
      </div>

    </div>
  </div>
</div>

<script>
// Data Rute Simulasi (Sumatera to Java)
const route = [
  [5.548290, 95.323753], // Banda Aceh (Start)
  [3.5952, 98.6722], // Medan
  [0.507068, 101.447777], // Pekanbaru
  [-2.990934, 104.756554], // Palembang
  [-5.450000, 105.266666], // Lampung
  [-6.200000, 106.816666] // Jakarta (End)
];

// Inisialisasi Map
// Set view agak zoom out agar terlihat rutenya
const map = L.map('map', {
  zoomControl: false, // Kita buat zoom control custom nanti atau hilangkan agar bersih
  attributionControl: false
}).setView([0, 100], 5);

// Tile Layer Clean (CartoDB Positron - Tampilan Peta Putih Bersih/Profesional)
L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
  maxZoom: 19
}).addTo(map);

// Gambar Jalur (Dotted Line Amazon Style)
const polyline = L.polyline(route, {
  color: '#4f46e5', // Indigo-600
  weight: 4,
  opacity: 0.6,
  dashArray: '10, 10', // Garis putus-putus
  lineCap: 'round'
}).addTo(map);

// Fit Bounds (Agar semua rute masuk layar otomatis)
map.fitBounds(polyline.getBounds(), {
  padding: [50, 50]
});

// Custom Truck Icon (Lebih Bagus)
const truckIcon = L.icon({
  iconUrl: 'https://cdn-icons-png.flaticon.com/512/2636/2636397.png', // Ikon truk 3D
  iconSize: [48, 48],
  iconAnchor: [24, 24],
  popupAnchor: [0, -24],
  className: 'truck-icon-anim' // Class untuk CSS animasi
});

// Marker Truk
const marker = L.marker(route[0], {
  icon: truckIcon
}).addTo(map);

// Popup Info di Truk
marker.bindPopup(`
    <div class="text-center p-1">
        <p class="text-xs font-bold text-slate-500 uppercase mb-1">Posisi Paket</p>
        <p class="text-sm font-bold text-indigo-600">Sedang Bergerak</p>
    </div>
`, {
  closeButton: false,
  className: 'custom-popup'
}).openPopup();

// Simulasi Gerakan Halus
let step = 0;
const totalSteps = route.length;
const speed = 2000; // ms per hop

function animateTruck() {
  // Pindahkan marker ke koordinat berikutnya
  marker.setLatLng(route[step]);

  // Update teks lokasi (Simulasi)
  const cityNames = ["Banda Aceh", "Medan", "Pekanbaru", "Palembang", "Lampung", "Jakarta"];
  document.getElementById('current-location-text').innerText = "Transit di " + cityNames[step];

  step++;

  // Reset loop jika sudah sampai
  if (step >= totalSteps) {
    step = 0;
  }

  setTimeout(animateTruck, speed);
}

// Mulai animasi
setTimeout(animateTruck, 1000);
</script>

<style>
/* Animasi Ikon Truk (Sedikit bouncing) */
.truck-icon-anim {
  transition: all 2s ease-in-out;
  /* Smooth movement handled by leaflet mostly, this helps CSS props */
  filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.3));
}

/* Custom Popup Leaflet agar Rounded */
.leaflet-popup-content-wrapper {
  border-radius: 12px;
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
  font-family: 'Plus Jakarta Sans', sans-serif;
}

.leaflet-popup-tip {
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}
</style>