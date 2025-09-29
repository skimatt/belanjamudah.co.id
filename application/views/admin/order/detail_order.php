<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Transaksi / Detail Pesanan /</span> #<?= $order->id; ?>
</h4>

<?php if ($this->session->flashdata('success')): ?>
<div class="alert alert-success alert-dismissible" role="alert"><?= $this->session->flashdata('success'); ?><button
    type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
<div class="alert alert-danger alert-dismissible" role="alert"><?= $this->session->flashdata('error'); ?><button
    type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>
<?php endif; ?>

<div class="row">
  <div class="col-lg-8">
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title m-0">Ringkasan Transaksi</h5>
        <a href="javascript:void(0);" class="btn btn-sm btn-label-secondary"><i class="ti ti-printer me-1"></i> Cetak
          Invoice</a>
      </div>
      <div class="card-body">
        <p class="mb-4">
          **Order ID:** #<?= $order->id; ?><br>
          **Tanggal:** <?= date('d M Y H:i:s', strtotime($order->created_at)); ?><br>
          **Pelanggan:** <?= html_escape($order->full_name); ?> (<?= html_escape($order->email); ?>)
        </p>
        <div class="d-flex justify-content-between flex-wrap gap-2">
          <div class="badge bg-label-secondary p-3">
            Status Saat Ini:<br>
            <span class="fw-bold fs-5 text-uppercase"><?= $order->order_status; ?></span>
          </div>
          <div class="badge bg-label-info p-3">
            Total Pembayaran:<br>
            <span class="fw-bold fs-5"><?= 'Rp ' . number_format($order->total_amount, 0, ',', '.'); ?></span>
          </div>
          <div class="badge bg-label-warning p-3">
            Status Pembayaran:<br>
            <span class="fw-bold fs-5 text-uppercase"><?= $order->payment_status; ?></span>
          </div>
        </div>
      </div>
    </div>

    <div class="card mb-4">
      <h5 class="card-header">Item Dalam Pesanan</h5>
      <div class="table-responsive text-nowrap">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Produk</th>
              <th>Varian</th>
              <th>Harga Satuan</th>
              <th>Qty</th>
              <th>Subtotal</th>
            </tr>
          </thead>
          <tbody>
            <?php $total_qty = 0; foreach ($items as $item): $total_qty += $item->quantity; ?>
            <tr>
              <td><?= html_escape($item->product_name); ?></td>
              <td><span class="badge bg-label-info"><?= html_escape($item->product_variant_name); ?></span></td>
              <td><?= 'Rp ' . number_format($item->unit_price, 0, ',', '.'); ?></td>
              <td><?= $item->quantity; ?></td>
              <td><?= 'Rp ' . number_format($item->total_price, 0, ',', '.'); ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="3" class="text-end fw-bold">Total Item:</td>
              <td class="fw-bold"><?= $total_qty; ?></td>
              <td class="fw-bold"><?= 'Rp ' . number_format($order->total_amount, 0, ',', '.'); ?></td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>

    <div class="card">
      <h5 class="card-header">Informasi Pengiriman</h5>
      <div class="card-body">
        <p>
          **Kurir:** <span class="badge bg-label-success me-2"><?= html_escape($order->shipping_courier); ?></span><br>
          **Nomor Resi:** <?php if (!empty($order->tracking_number)): ?>
          <span class="fw-bold text-primary"><?= html_escape($order->tracking_number); ?></span>
          <?php else: ?>
          <span class="text-danger">Belum ada resi.</span>
          <?php endif; ?>
        </p>
        <h6 class="mt-3">Alamat Pengiriman:</h6>
        <div class="border p-3 rounded bg-light">
          <?= nl2br(html_escape($order->shipping_address)); ?>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="card mb-4">
      <h5 class="card-header">Update Status Pesanan</h5>
      <div class="card-body">
        <?= form_open('admin/order/update_status/' . $order->id); ?>
        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>"
          value="<?= $this->security->get_csrf_hash(); ?>">

        <div class="mb-3">
          <label for="new_status" class="form-label">Ganti Status Menjadi</label>
          <select id="new_status" name="new_status" class="form-select" required>
            <option value="">Pilih Status Baru</option>
            <?php foreach ($status_list as $status): ?>
            <option value="<?= $status; ?>" <?= ($order->order_status == $status) ? 'selected disabled' : ''; ?>>
              <?= strtoupper($status); ?>
              <?= ($order->order_status == $status) ? '(Saat Ini)' : ''; ?>
            </option>
            <?php endforeach; ?>
          </select>
          <?= form_error('new_status', '<div class="text-danger mt-1 text-xs">', '</div>'); ?>
        </div>

        <div class="mb-3" id="tracking-input-group">
          <label for="tracking_number" class="form-label">Nomor Resi (Wajib saat Shipped)</label>
          <input type="text" id="tracking_number" name="tracking_number" class="form-control"
            value="<?= html_escape($order->tracking_number); ?>" placeholder="Masukkan Nomor Resi">
          <?= form_error('tracking_number', '<div class="text-danger mt-1 text-xs">', '</div>'); ?>
        </div>

        <button type="submit" class="btn btn-primary w-100 mt-2">Update Status</button>

        <?= form_close(); ?>
      </div>
    </div>

    <div class="card">
      <h5 class="card-header">Riwayat Status</h5>
      <div class="card-body">
        <p class="text-muted">Fitur Audit Log (Riwayat perubahan status) dapat ditambahkan di sini dengan tabel
          terpisah.</p>
        <ul class="timeline">
          <li class="timeline-item timeline-item-primary">
            <div class="timeline-dot"></div>
            <div class="timeline-content">
              <p class="mb-0">Dibuat: <?= $order->created_at; ?></p>
              <span class="text-muted">Oleh: Pelanggan</span>
            </div>
          </li>
          <li class="timeline-item timeline-item-secondary">
            <div class="timeline-dot"></div>
            <div class="timeline-content">
              <p class="mb-0">Status Saat Ini: <?= strtoupper($order->order_status); ?></p>
              <span class="text-muted">Terakhir diubah: <?= $order->updated_at; ?></span>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const statusSelect = document.getElementById('new_status');
  const trackingInput = document.getElementById('tracking_number');
  const trackingGroup = document.getElementById('tracking-input-group');

  // Fungsi untuk mengaktifkan/menonaktifkan input resi
  function toggleTrackingInput() {
    const selectedStatus = statusSelect.value;
    // Jika status yang dipilih adalah 'shipped' atau sudah 'shipped'
    if (selectedStatus === 'shipped') {
      trackingGroup.style.display = 'block';
      trackingInput.required = true;
    } else {
      trackingGroup.style.display = 'none';
      trackingInput.required = false;
    }
  }

  // Jalankan saat halaman dimuat
  toggleTrackingInput();

  // Jalankan saat status berubah
  statusSelect.addEventListener('change', toggleTrackingInput);
});
</script>