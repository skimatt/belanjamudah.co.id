<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

<div class="bg-slate-50 min-h-screen pb-20 font-sans text-slate-800">

  <div class="mx-auto px-6 lg:px-12 max-w-[1440px] py-10">

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">

      <aside class="lg:col-span-3 sticky top-24">

        <div
          class="bg-gradient-to-br from-indigo-600 to-blue-600 rounded-3xl p-6 text-white shadow-xl shadow-indigo-200 mb-6 relative overflow-hidden">
          <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-10 -mt-10 blur-2xl"></div>
          <div class="absolute bottom-0 left-0 w-24 h-24 bg-indigo-900 opacity-20 rounded-full -ml-10 -mb-10 blur-xl">
          </div>

          <div class="relative z-10 flex flex-col items-center text-center">
            <?php 
                            $user_name = $this->session->userdata('full_name') ?? 'Nama Pengguna';
                            $initial = substr($user_name, 0, 1);
                        ?>

            <div class="w-20 h-20 bg-white/20 backdrop-blur-sm p-1 rounded-full mb-4 border border-white/30">
              <div
                class="w-full h-full bg-white rounded-full flex items-center justify-center text-indigo-600 text-2xl font-black uppercase">
                <?= $initial; ?>
              </div>
            </div>

            <h3 class="text-xl font-bold tracking-wide truncate w-full"><?= html_escape($user_name); ?></h3>
            <p class="text-indigo-100 text-sm mt-1 font-medium">Member Platinum</p>

            <div
              class="mt-6 w-full bg-white/10 rounded-xl p-3 flex justify-between items-center backdrop-blur-sm border border-white/10">
              <div class="text-left">
                <p class="text-[10px] uppercase opacity-70 font-bold">Bergabung</p>
                <p class="text-sm font-bold"><?= date('Y'); ?></p>
              </div>
              <i class="ti ti-award text-2xl text-yellow-300"></i>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-3xl shadow-lg shadow-slate-200/50 border border-white p-4">
          <ul class="space-y-2">
            <?php 
                            $segment = $this->uri->segment(2);
                            $menu = [
                                'Dashboard' => [
                                    'url' => site_url('account'), 
                                    'icon' => 'ti ti-layout-grid', 
                                    'desc' => 'Ringkasan akun',
                                    'active_check' => ($segment == '' || $segment == 'index')
                                ],
                                'Pesanan Saya' => [
                                    'url' => site_url('order'), 
                                    'icon' => 'ti ti-shopping-bag', 
                                    'desc' => 'Status & riwayat',
                                    'active_check' => ($segment == 'order' || $this->uri->segment(1) == 'order')
                                ],
                                'Alamat Pengiriman' => [
                                    'url' => site_url('account/addresses'), 
                                    'icon' => 'ti ti-map-pin', 
                                    'desc' => 'Atur lokasi tujuan',
                                    'active_check' => in_array($segment, ['addresses', 'form_address'])
                                ],
                                
                            ];
                        ?>

            <?php foreach ($menu as $name => $item): ?>
            <li>
              <a href="<?= $item['url']; ?>" class="group flex items-center px-5 py-4 rounded-2xl transition-all duration-300
                               <?= $item['active_check'] 
                                    ? 'bg-slate-800 text-white shadow-lg shadow-slate-300 scale-[1.02]' 
                                    : 'text-slate-500 hover:bg-indigo-50 hover:text-indigo-600'; ?>">

                <div
                  class="w-10 h-10 rounded-xl flex items-center justify-center mr-4 transition-colors
                                    <?= $item['active_check'] ? 'bg-white/10 text-white' : 'bg-slate-100 text-slate-500 group-hover:bg-indigo-100 group-hover:text-indigo-600'; ?>">
                  <i class="<?= $item['icon']; ?> text-xl"></i>
                </div>

                <div>
                  <span class="block font-bold text-base"><?= $name; ?></span>
                  <span
                    class="text-xs opacity-70 font-normal <?= $item['active_check'] ? 'text-slate-300' : 'text-slate-400'; ?>">
                    <?= $item['desc']; ?>
                  </span>
                </div>

                <?php if($item['active_check']): ?>
                <i class="ti ti-chevron-right ml-auto"></i>
                <?php endif; ?>
              </a>
            </li>
            <?php endforeach; ?>
          </ul>

          <div class="mt-6 pt-6 border-t border-dashed border-gray-200 px-2">
            <a href="<?= site_url('auth/logout'); ?>"
              class="flex items-center justify-center w-full py-3 px-4 rounded-xl border-2 border-red-100 text-red-600 font-bold hover:bg-red-50 hover:border-red-200 transition-all">
              <i class="ti ti-logout mr-2"></i> Keluar
            </a>
          </div>
        </div>

      </aside>

      <main class="lg:col-span-9 animate__animated animate__fadeIn">
        <div class="mb-6 flex items-center justify-between">
          <div>
            <h1 class="text-3xl font-black text-slate-800">
              <?php 
                                // Logika judul dinamis sederhana
                                if($segment == '' || $segment == 'index') echo 'Dashboard Overview';
                                elseif($segment == 'order' || $this->uri->segment(1) == 'order') echo 'Riwayat Pesanan';
                                elseif(in_array($segment, ['addresses', 'form_address'])) echo 'Buku Alamat';
                                else echo 'Pengaturan Akun';
                            ?>
            </h1>
            <p class="text-slate-500 mt-1">Kelola aktivitas dan data akun Anda di sini.</p>
          </div>
          <div class="hidden md:block text-right">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-widest"><?= date('l'); ?></span>
            <p class="text-lg font-bold text-indigo-600"><?= date('d F Y'); ?></p>
          </div>
        </div>

        <div
          class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/60 border border-white p-8 md:p-12 min-h-[600px]">
          <?php $this->load->view($content_view); ?>
        </div>
      </main>

    </div>
  </div>
</div>