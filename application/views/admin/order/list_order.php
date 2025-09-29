<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Transaksi /</span> Manajemen Pesanan
</h4>

<?php if ($this->session->flashdata('success')): ?>
<div class="alert alert-success alert-dismissible" role="alert"><?= $this->session->flashdata('success'); ?><button
    type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>
<?php endif; ?>

<div class="card">
  <h5 class="card-header">Daftar Pesanan Terbaru</h5>

  <div class="table-responsive text-nowrap">
    <table class="table table-hover table-striped">
      <thead>
        <tr>
          <th>ID</th>
          <th>Tanggal</th>
          <th>Pelanggan</th>
          <th>Total</th>
          <th>Pembayaran</th>
          <th>Status Pesanan</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        <?php if (!empty($orders)): ?>
        <?php foreach ($orders as $order): ?>
        <tr>
          <td>#<?= $order->id; ?></td>
          <td><?= date('d M Y H:i', strtotime($order->created_at)); ?></td>
          <td>
            <strong><?= html_escape($order->full_name); ?></strong><br>
            <small class="text-muted"><?= html_escape($order->email); ?></small>
          </td>
          <td><?= 'Rp ' . number_format($order->total_amount, 0, ',', '.'); ?></td>
          <td>
            <span
              class="badge <?= ($order->payment_status == 'paid') ? 'bg-success' : (($order->payment_status == 'pending') ? 'bg-warning' : 'bg-danger'); ?> me-1">
              <?= ucfirst($order->payment_status); ?>
            </span>
          </td>
          <td>
            <?php 
                                    $badge_class = 'bg-secondary';
                                    if ($order->order_status == 'pending') $badge_class = 'bg-warning';
                                    else if (in_array($order->order_status, ['paid', 'packing'])) $badge_class = 'bg-primary';
                                    else if ($order->order_status == 'shipped') $badge_class = 'bg-info';
                                    else if ($order->order_status == 'completed') $badge_class = 'bg-success';
                                ?>
            <span class="badge <?= $badge_class; ?>"><?= strtoupper($order->order_status); ?></span>
          </td>
          <td>
            <a href="<?= site_url('admin/order/detail/' . $order->id); ?>" class="btn btn-sm btn-icon btn-text-info"
              title="Lihat Detail">
              <i class="ti ti-eye"></i>
            </a>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php else: ?>
        <tr>
          <td colspan="7" class="text-center">Belum ada pesanan masuk.</td>
        </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>