<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Dashboard /</span> Analisis Toko
</h4>

<div class="row g-4 mb-4">
  <div class="col-sm-6 col-xl-3">
    <div class="card shadow-sm">
      <div class="card-body">
        <div class="d-flex align-items-start justify-content-between">
          <div>
            <span class="text-heading fw-medium">Total Pendapatan</span>
            <h4 class="mb-0 mt-1 text-success fw-bold"><?= format_rupiah($summary->total_revenue ?? 0); ?></h4>
            <small class="text-muted">30 Hari Terakhir</small>
          </div>
          <div class="avatar">
            <span class="avatar-initial rounded bg-label-success"><i class="ti ti-currency-dollar ti-26px"></i></span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-xl-3">
    <div class="card shadow-sm">
      <div class="card-body">
        <div class="d-flex align-items-start justify-content-between">
          <div>
            <span class="text-heading fw-medium">Total Pesanan</span>
            <h4 class="mb-0 mt-1 text-primary fw-bold"><?= number_format($summary->total_orders ?? 0); ?></h4>
            <small class="text-muted">30 Hari Terakhir</small>
          </div>
          <div class="avatar">
            <span class="avatar-initial rounded bg-label-primary"><i class="ti ti-receipt ti-26px"></i></span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-xl-3">
    <div class="card shadow-sm">
      <div class="card-body">
        <div class="d-flex align-items-start justify-content-between">
          <div>
            <span class="text-heading fw-medium">Produk Stok Menipis</span>
            <h4 class="mb-0 mt-1 text-danger fw-bold"><?= number_format($low_stock_count ?? 0); ?></h4>
            <small class="text-muted">Unit di bawah 10</small>
          </div>
          <div class="avatar">
            <span class="avatar-initial rounded bg-label-danger"><i class="ti ti-alert-triangle ti-26px"></i></span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-xl-3">
    <div class="card shadow-sm">
      <div class="card-body">
        <div class="d-flex align-items-start justify-content-between">
          <div>
            <span class="text-heading fw-medium">Pelanggan Aktif</span>
            <h4 class="mb-0 mt-1 text-info fw-bold"><?= number_format($total_customers ?? 0); ?></h4>
            <small class="text-muted">Total terdaftar</small>
          </div>
          <div class="avatar">
            <span class="avatar-initial rounded bg-label-info"><i class="ti ti-users ti-26px"></i></span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<div class="row g-4">
  <div class="col-lg-8">
    <div class="card shadow-sm">
      <div class="card-header border-bottom">
        <h5 class="card-title mb-0">Pendapatan Harian (30 Hari)</h5>
      </div>
      <div class="card-body">
        <canvas id="revenueLineChart" height="150"></canvas>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="card shadow-sm h-100">
      <div class="card-header border-bottom">
        <h5 class="card-title mb-0">Aksi Cepat Admin</h5>
      </div>
      <div class="card-body">
        <div class="list-group list-group-flush">
          <a href="<?= site_url('admin/order'); ?>" class="list-group-item list-group-item-action"><i
              class="ti ti-receipt me-3"></i> **Kelola Pesanan Baru**</a>
          <a href="<?= site_url('admin/product/create'); ?>" class="list-group-item list-group-item-action"><i
              class="ti ti-plus me-3"></i> Tambah Produk Baru</a>
          <a href="<?= site_url('admin/voucher'); ?>" class="list-group-item list-group-item-action"><i
              class="ti ti-gift me-3"></i> Kelola Voucher & Promo</a>
          <a href="<?= site_url('admin/report'); ?>" class="list-group-item list-group-item-action"><i
              class="ti ti-file-text me-3"></i> Lihat Laporan Keuangan</a>
          <a href="<?= site_url('admin/user'); ?>" class="list-group-item list-group-item-action"><i
              class="ti ti-users me-3"></i> Manajemen Pengguna</a>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="card shadow-sm mt-4">
  <h5 class="card-header">3 Pesanan Terbaru</h5>
  <div class="table-responsive text-nowrap">
    <table class="table table-hover">
      <thead>
        <tr>
          <th>ID</th>
          <th>Pelanggan</th>
          <th>Total</th>
          <th>Status</th>
          <th>Tanggal Order</th>
          <th>Detail</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($recent_orders)): ?>
        <?php foreach ($recent_orders as $order): ?>
        <tr>
          <td><a href="<?= site_url('admin/order/detail/' . $order->id); ?>">#<?= $order->id; ?></a></td>
          <td><?= html_escape($order->full_name); ?></td>
          <td><?= format_rupiah($order->total_amount); ?></td>
          <td><?= order_status_badge($order->order_status); ?></td>
          <td><?= date('d M H:i', strtotime($order->created_at)); ?></td>
          <td>
            <a href="<?= site_url('admin/order/detail/' . $order->id); ?>" class="btn btn-sm btn-label-primary">Lihat
              Detail</a>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php else: ?>
        <tr>
          <td colspan="6" class="text-center">Belum ada pesanan terbaru.</td>
        </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
// Pastikan Chart.js CDN dimuat di admin_templates.php!
document.addEventListener('DOMContentLoaded', function() {
  const ctx = document.getElementById('revenueLineChart');
  if (ctx) {
    // Data diencode dari Controller
    // Jika data kosong, ini akan menjadi array kosong
    const labels = <?= $chart_labels; ?>;
    const dataValues = <?= $chart_values; ?>;

    new Chart(ctx, {
      type: 'line',
      data: {
        labels: labels,
        datasets: [{
          label: 'Pendapatan (Rp)',
          data: dataValues,
          borderColor: 'rgba(102, 108, 255, 1)',
          backgroundColor: 'rgba(102, 108, 255, 0.2)',
          fill: true,
          tension: 0.3,
          borderWidth: 2,
          pointRadius: 3
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              callback: function(value) {
                // Format sumbu Y (misal: Rp 50K)
                return 'Rp ' + (value / 1000).toLocaleString('id-ID') + 'K';
              }
            }
          }
        },
        plugins: {
          legend: {
            display: false
          },
          tooltip: {
            callbacks: {
              label: function(context) {
                // Menggunakan formatRupiah yang didefinisikan secara global
                if (typeof formatRupiah === 'function') {
                  return 'Total: ' + formatRupiah(context.raw);
                }
                return 'Total: Rp ' + context.raw.toLocaleString('id-ID');
              }
            }
          }
        }
      }
    });
  }

  // Fungsi formatRupiah untuk Tooltip (diulang jika tidak ada di helper global)
  function formatRupiah(angka) {
    var prefix = 'Rp ';
    var number_string = (angka || 0).toString().replace(/[^,\d]/g, ''),
      split = number_string.split(','),
      sisa = split[0].length % 3,
      rupiah = split[0].substr(0, sisa),
      ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
      separator = sisa ? '.' : '';
      rupiah += separator + ribuan.join('.');
    }
    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    return prefix + rupiah;
  }

});
</script>