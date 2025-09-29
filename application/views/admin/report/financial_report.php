<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Transaksi /</span> Laporan Keuangan Dasar
</h4>

<div class="card mb-4">
  <div class="card-header">
    <h5 class="card-title mb-0">Filter Periode Laporan</h5>
  </div>
  <div class="card-body">
    <?= form_open('admin/report', 'method="post" class="row g-3"'); ?>
    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>"
      value="<?= $this->security->get_csrf_hash(); ?>">
    <input type="hidden" name="filter" value="1">

    <div class="col-md-4">
      <label for="start_date" class="form-label">Tanggal Mulai</label>
      <input type="date" id="start_date" name="start_date" class="form-control" value="<?= $start_date; ?>" required>
    </div>

    <div class="col-md-4">
      <label for="end_date" class="form-label">Tanggal Akhir</label>
      <input type="date" id="end_date" name="end_date" class="form-control" value="<?= $end_date; ?>" required>
    </div>

    <div class="col-md-4 d-flex align-items-end">
      <button type="submit" class="btn btn-primary me-2">Tampilkan Laporan</button>
      <a href="<?= site_url('admin/report/export?start=' . $start_date . '&end=' . $end_date); ?>"
        class="btn btn-label-success">
        <i class="ti ti-file-export me-1"></i> Export
      </a>
    </div>
    <?= form_close(); ?>
  </div>
</div>

<div class="row g-4 mb-4">
  <div class="col-md-4">
    <div class="card">
      <div class="card-body">
        <span class="d-block mb-1">Total Pendapatan (Paid)</span>
        <h3 class="card-title text-success mb-2"><?= 'Rp ' . number_format($summary->total_revenue, 0, ',', '.'); ?>
        </h3>
        <small class="text-muted">Dalam periode <?= date('d M', strtotime($start_date)); ?> -
          <?= date('d M Y', strtotime($end_date)); ?></small>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card">
      <div class="card-body">
        <span class="d-block mb-1">Total Pesanan Selesai</span>
        <h3 class="card-title mb-2"><?= $summary->total_orders; ?> Pesanan</h3>
        <small class="text-muted">Total Paid Orders</small>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card">
      <div class="card-body">
        <span class="d-block mb-1">Pendapatan Selesai (Completed)</span>
        <h3 class="card-title text-primary mb-2"><?= 'Rp ' . number_format($summary->completed_revenue, 0, ',', '.'); ?>
        </h3>
        <small class="text-muted">Pesanan yang benar-benar Completed</small>
      </div>
    </div>
  </div>
</div>

<div class="card">
  <h5 class="card-header">Detail Transaksi Harian (Paid)</h5>
  <div class="table-responsive">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Tanggal</th>
          <th>Jumlah Pesanan</th>
          <th>Pendapatan Harian</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($daily_data)): ?>
        <?php foreach ($daily_data as $day): ?>
        <tr>
          <td><strong><?= date('d F Y', strtotime($day->date)); ?></strong></td>
          <td><?= $day->orders_count; ?></td>
          <td><span class="text-success fw-bold"><?= 'Rp ' . number_format($day->daily_revenue, 0, ',', '.'); ?></span>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php else: ?>
        <tr>
          <td colspan="3" class="text-center">Tidak ada data transaksi Paid dalam periode ini.</td>
        </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>