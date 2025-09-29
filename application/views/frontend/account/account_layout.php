<div class="container mx-auto px-4 py-8">
  <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

    <aside class="lg:col-span-3 bg-white p-6 rounded-xl shadow-lg h-min sticky top-20">
      <?php $user = $this->session->userdata('full_name'); ?>
      <div class="mb-4 text-center border-b pb-4">
        <i class="ti ti-user-circle text-6xl text-indigo-600"></i>
        <h3 class="text-xl font-bold text-gray-800 mt-2"><?= html_escape($user); ?></h3>
        <small class="text-gray-500">Pelanggan Sejak <?= date('Y'); ?></small>
      </div>

      <ul class="space-y-2">
        <?php $segment = $this->uri->segment(2); ?>

        <?php 
                $menu = [
                    'Dashboard' => ['url' => site_url('account'), 'icon' => 'ti ti-smart-home', 'segments' => ['', 'index']],
                    'Pesanan Saya' => ['url' => site_url('order'), 'icon' => 'ti ti-receipt', 'segments' => ['order']],
                    'Daftar Alamat' => ['url' => site_url('account/addresses'), 'icon' => 'ti ti-map-pin', 'segments' => ['addresses', 'form_address']],
                    // Tambahkan menu lain di sini, misal: 'Profil'
                ];
                
                foreach ($menu as $name => $item):
                    $is_active = in_array($segment, $item['segments']);
                ?>
        <li
          class="<?= $is_active ? 'bg-indigo-50 border-indigo-600' : 'hover:bg-gray-50 border-transparent'; ?> p-2 rounded-lg border-l-4 transition duration-150">
          <a href="<?= $item['url']; ?>" class="text-gray-800 font-medium flex items-center space-x-3">
            <i class="<?= $item['icon']; ?> text-xl"></i>
            <span><?= $name; ?></span>
          </a>
        </li>
        <?php endforeach; ?>

        <li class="p-2 rounded-lg hover:bg-gray-50 border-l-4 border-transparent mt-4 pt-4 border-t border-gray-200">
          <a href="<?= site_url('auth/logout'); ?>" class="text-red-500 font-medium flex items-center space-x-3">
            <i class="ti ti-logout text-xl"></i>
            <span>Logout</span>
          </a>
        </li>
      </ul>
    </aside>

    <div class="lg:col-span-9">
      <?php $this->load->view($content_view); ?>
    </div>
  </div>
</div>