<?php
$is_edit = isset($voucher) && $voucher !== NULL;
$action_url = $is_edit ? 'admin/voucher/form/' . $voucher->id : 'admin/voucher/form';

// Helper function to extract date part
function get_date_only($datetime_str) {
    return date('Y-m-d', strtotime($datetime_str));
}

// Nilai default untuk Edit/Create dengan preferensi set_value()
$v_code = set_value('code', $is_edit ? $voucher->code : '');
$v_type = set_value('type', $is_edit ? $voucher->type : 'percent');
$v_value = set_value('value', $is_edit ? $voucher->value : '');
$v_min_amount = set_value('min_order_amount', $is_edit ? $voucher->min_order_amount : 0);
$v_max_usage = set_value('max_usage', $is_edit ? $voucher->max_usage : 100);
$v_valid_until = set_value('valid_until', $is_edit ? get_date_only($voucher->valid_until) : date('Y-m-d', strtotime('+1 month')));
$v_active = set_value('is_active', $is_edit ? $voucher->is_active : 1);
$v_shipping = set_value('is_shipping_discount', $is_edit ? $voucher->is_shipping_discount : 0);
?>

<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Promosi / Voucher /</span> <?= $page_title; ?>
</h4>

<div class="card">
  <h5 class="card-header"><i class="ti ti-pencil me-2"></i> <?= $page_title; ?></h5>
  <div class="card-body">

    <?= form_open($action_url); ?>
    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>"
      value="<?= $this->security->get_csrf_hash(); ?>">

    <div class="row g-3">

      <div class="col-md-6">
        <label class="form-label" for="code">Kode Voucher <span class="text-danger">*</span></label>
        <input type="text" id="code" name="code" class="form-control text-uppercase" value="<?= $v_code; ?>"
          placeholder="MISAL: DISKON10" required />
        <?= form_error('code', '<div class="text-danger mt-1 text-xs">', '</div>'); ?>
      </div>

      <div class="col-md-6">
        <label class="form-label" for="valid_until">Tanggal Kadaluarsa <span class="text-danger">*</span></label>
        <input type="date" id="valid_until" name="valid_until" class="form-control" value="<?= $v_valid_until; ?>"
          required />
        <?= form_error('valid_until', '<div class="text-danger mt-1 text-xs">', '</div>'); ?>
      </div>

      <div class="col-md-3">
        <label class="form-label" for="type">Tipe Diskon <span class="text-danger">*</span></label>
        <select id="type" name="type" class="form-select" required>
          <option value="percent" <?= ($v_type == 'percent') ? 'selected' : ''; ?>>Persentase (%)</option>
          <option value="fixed" <?= ($v_type == 'fixed') ? 'selected' : ''; ?>>Fixed Amount (Rp)</option>
        </select>
        <?= form_error('type', '<div class="text-danger mt-1 text-xs">', '</div>'); ?>
      </div>

      <div class="col-md-3">
        <label class="form-label" for="value">Nilai Diskon <span class="text-danger">*</span></label>
        <input type="number" id="value" name="value" class="form-control" value="<?= $v_value; ?>"
          placeholder="10 atau 50000" required min="1" />
        <?= form_error('value', '<div class="text-danger mt-1 text-xs">', '</div>'); ?>
      </div>

      <div class="col-md-3">
        <label class="form-label" for="min_order_amount">Min. Belanja (Rp)</label>
        <input type="number" id="min_order_amount" name="min_order_amount" class="form-control"
          value="<?= $v_min_amount; ?>" placeholder="50000" min="0" />
        <?= form_error('min_order_amount', '<div class="text-danger mt-1 text-xs">', '</div>'); ?>
      </div>

      <div class="col-md-3">
        <label class="form-label" for="max_usage">Maks. Pemakaian Global</label>
        <input type="number" id="max_usage" name="max_usage" class="form-control" value="<?= $v_max_usage; ?>"
          placeholder="100" required min="1" />
        <?= form_error('max_usage', '<div class="text-danger mt-1 text-xs">', '</div>'); ?>
      </div>

      <div class="col-md-6">
        <div class="form-check form-switch mt-4">
          <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
            <?= ($v_active == 1) ? 'checked' : ''; ?>>
          <label class="form-check-label" for="is_active">Aktifkan Voucher</label>
        </div>
      </div>

      <div class="col-md-6">
        <div class="form-check form-switch mt-4">
          <input class="form-check-input" type="checkbox" id="is_shipping_discount" name="is_shipping_discount"
            value="1" <?= ($v_shipping == 1) ? 'checked' : ''; ?>>
          <label class="form-check-label" for="is_shipping_discount">Diskon Khusus Biaya Pengiriman</label>
        </div>
      </div>

    </div>

    <div class="mt-4">
      <button type="submit" class="btn btn-success me-2"><i class="ti ti-save me-1"></i>
        <?= $is_edit ? 'Simpan Perubahan' : 'Buat Voucher'; ?></button>
      <a href="<?= site_url('admin/voucher'); ?>" class="btn btn-label-secondary">Batal</a>
    </div>

    <?= form_close(); ?>
  </div>
</div>