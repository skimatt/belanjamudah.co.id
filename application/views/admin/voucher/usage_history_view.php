<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Promosi / Riwayat /</span> <?= html_escape($voucher->code); ?>
</h4>

<div class="card">
  <h5 class="card-header">Penggunaan Voucher: <?= html_escape($voucher->code); ?> (Terpakai:
    <?= count($usage_history); ?>/<?= $voucher->max_usage; ?>)</h5>

  <div class="table-responsive text-nowrap">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>#Order ID</th>
          <th>Pengguna</th>
          <th>Total Belanja</th>
          <th>Tanggal Digunakan</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($usage_history)): ?>
        <?php foreach ($usage_history as $history): ?>
        <tr>
          <td><span class="fw-bold">#<?= $history->order_id; ?></span></td>
          <td><?= html_escape($history->full_name); ?></td>
          <td><?= format_rupiah($history->total_amount); ?></td>
          <td><?= date('d M Y H:i', strtotime($history->order_date)); ?></td>
          <td>
            <a href="<?= site_url('admin/order/detail/' . $history->order_id); ?>"
              class="btn btn-sm btn-label-info">Lihat Pesanan</a>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php else: ?>
        <tr>
          <td colspan="5" class="text-center">Voucher ini belum pernah digunakan.</td>
        </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>