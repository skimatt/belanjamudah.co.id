<?php
$is_edit = isset($category) && $category !== NULL;
$action_url = $is_edit ? 'admin/category/form/' . $category->id : 'admin/category/form';

$cat_name = set_value('name', $is_edit ? $category->name : '');
$cat_desc = set_value('description', $is_edit ? $category->description : '');
$cat_parent = set_value('parent_id', $is_edit ? $category->parent_id : '');
?>

<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Katalog / Kategori /</span> <?= $page_title; ?>
</h4>

<div class="card">
  <h5 class="card-header"><i class="ti ti-pencil me-2"></i> <?= $page_title; ?></h5>
  <div class="card-body">

    <?= form_open($action_url); ?>
    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>"
      value="<?= $this->security->get_csrf_hash(); ?>">

    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label" for="name">Nama Kategori <span class="text-danger">*</span></label>
        <input type="text" id="name" name="name" class="form-control" value="<?= $cat_name; ?>" required />
        <?= form_error('name', '<div class="text-danger mt-1 text-xs">', '</div>'); ?>
      </div>

      <div class="col-md-6">
        <label class="form-label" for="parent_id">Kategori Induk (Opsional)</label>
        <select id="parent_id" name="parent_id" class="form-select">
          <option value="">— Tidak Ada Induk (Kategori Utama) —</option>
          <?php foreach ($categories as $cat): ?>
          <?php 
                                // Cegah kategori menjadi induk dari dirinya sendiri saat Edit
                                $is_self = $is_edit && $cat->id == $category->id; 
                                if (!$is_self): 
                            ?>
          <option value="<?= $cat->id; ?>" <?= ($cat_parent == $cat->id) ? 'selected' : ''; ?>>
            <?= html_escape($cat->name); ?>
          </option>
          <?php endif; ?>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-md-12">
        <label class="form-label" for="description">Deskripsi (Opsional)</label>
        <textarea id="description" name="description" class="form-control" rows="3"><?= $cat_desc; ?></textarea>
      </div>
    </div>

    <div class="mt-4">
      <button type="submit" class="btn btn-success me-2"><i class="ti ti-save me-1"></i>
        <?= $is_edit ? 'Simpan Perubahan' : 'Tambah Kategori'; ?></button>
      <a href="<?= site_url('admin/category'); ?>" class="btn btn-label-secondary">Batal</a>
    </div>

    <?= form_close(); ?>
  </div>
</div>