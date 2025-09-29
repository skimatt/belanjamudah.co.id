<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $page_title ?? 'Toko MVP'; ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@2.47.0/tabler-icons.min.css">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

  <style>
  /* Base Styles & Utilities */
  body {
    margin: 0;
    padding: 0;
  }

  .container {
    max-width: 1280px;
  }

  /* Dropdown Menu */
  .dropdown-menu {
    display: none;
    opacity: 0;
    transform: translateY(-10px);
    transition: opacity 0.2s ease-out, transform 0.2s ease-out;
    z-index: 60;
  }

  .dropdown:hover .dropdown-menu {
    display: block;
    opacity: 1;
    transform: translateY(0);
  }

  /* Mobile Menu Overlay */
  .mobile-nav-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: white;
    z-index: 40;
    overflow-y: auto;
    transform: translateX(-100%);
    transition: transform 0.3s ease-in-out;
  }

  .mobile-nav-overlay.open {
    transform: translateX(0);
  }
  </style>
</head>

<body class="bg-gray-100 font-sans antialiased">

  <?php $cart_count = $cart_total_items ?? 0; ?>
  <?php $categories_list = $categories ?? []; ?>

  <div id="header-container" class="sticky top-0 z-50">
    <header class="bg-white shadow-md">
      <div class="container mx-auto px-4 py-3 flex items-center justify-between space-x-4">

        <div class="flex items-center space-x-3 md:space-x-4">
          <button id="mobile-menu-toggle" class="lg:hidden text-gray-600 hover:text-blue-600 focus:outline-none">
            <i class="ti ti-menu-2 text-2xl"></i>
          </button>
          <a href="<?= site_url(); ?>" class="flex-shrink-0">
            <img src="<?= base_url('assets/img/logo.png'); ?>" alt="Logo Toko" class="h-8 md:h-10">
          </a>
        </div>

        <div class="flex-1 hidden md:flex items-center space-x-4">
          <div class="relative dropdown hidden lg:block">
            <button
              class="flex items-center text-gray-700 hover:text-blue-600 font-semibold text-sm transition-colors py-2 px-3 rounded-lg">
              <i class="ti ti-category-2 text-xl mr-2"></i> Kategori <i class="ti ti-chevron-down w-4 h-4 ml-1"></i>
            </button>
            <div
              class="dropdown-menu absolute left-0 mt-2 w-56 bg-white border border-gray-200 rounded-lg shadow-xl py-2">
              <?php if (!empty($categories_list)): ?>
              <?php foreach ($categories_list as $cat): ?>
              <a href="<?= site_url('category/' . $cat->slug); ?>"
                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                <?= html_escape($cat->name); ?>
              </a>
              <?php endforeach; ?>
              <?php else: ?>
              <span class="block px-4 py-2 text-sm text-gray-500">Kategori belum tersedia.</span>
              <?php endif; ?>
            </div>
          </div>

          <?= form_open('home/search', 'method="get" class="flex-1"'); ?>
          <div class="relative">
            <input type="text" name="q" placeholder="Cari tecno camon 40 pro 5 g..." value="<?= $search_query ?? ''; ?>"
              class="w-full border border-gray-300 rounded-lg py-2 pl-5 pr-12 focus:ring-blue-500 focus:border-blue-500 shadow-sm text-sm text-gray-800 transition-colors">
            <button type="submit"
              class="absolute inset-y-0 right-0 px-4 flex items-center text-gray-500 hover:text-blue-600 transition-colors">
              <i class="ti ti-search w-5 h-5"></i>
            </button>
          </div>
          <?= form_close(); ?>
        </div>

        <nav class="flex items-center space-x-2 md:space-x-4">
          <a href="<?= site_url('cart'); ?>" class="text-gray-500 hover:text-blue-600 relative p-2 md:p-0">
            <i class="ti ti-shopping-cart text-2xl md:text-3xl"></i>
            <?php if ($cart_count > 0): ?>
            <span
              class="absolute -top-1 -right-1 bg-red-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold border-2 border-white">
              <?= $cart_count > 9 ? '9+' : $cart_count; ?>
            </span>
            <?php endif; ?>
          </a>

          <?php if ($this->session->userdata('logged_in')): ?>
          <a href="<?= site_url('account'); ?>"
            class="hidden lg:block text-sm font-medium text-blue-600 hover:text-blue-800">Akun Saya</a>
          <a href="<?= site_url('auth/logout'); ?>"
            class="hidden lg:block text-sm text-red-500 hover:text-red-700">Logout</a>
          <?php else: ?>
          <a href="<?= site_url('auth/process_login'); ?>"
            class="hidden md:block text-sm font-semibold text-blue-600 hover:bg-blue-50 px-5 py-2 rounded-lg transition-colors">Masuk</a>
          <a href="<?= site_url('auth/register'); ?>"
            class="hidden md:block text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 px-5 py-2 rounded-lg shadow-sm transition-colors">Daftar</a>
          <?php endif; ?>
        </nav>
      </div>

      <div class="container mx-auto px-4 py-2 border-t border-gray-100 hidden md:flex items-center justify-between">
        <div class="flex items-center space-x-4 text-xs text-gray-500">
          <span class="font-semibold">Cari:</span>
          <a href="#" class="hover:text-blue-600 transition-colors">Rize x Corkcicle</a>
          <a href="#" class="hover:text-blue-600 transition-colors">Kaleela 50%</a>
          <a href="#" class="hover:text-blue-600 transition-colors">Hijab Cashback 75rb</a>
        </div>
        <div
          class="hidden lg:flex items-center text-gray-600 text-xs hover:bg-gray-100 p-2 rounded-lg transition-colors cursor-pointer">
          <i class="ti ti-map-pin text-lg mr-2"></i> Tambah alamat, biar belanja lebih asyik <i
            class="ti ti-chevron-down ml-2"></i>
        </div>
      </div>
    </header>


    <script>
    $(document).ready(function() {
      $('#menu-toggle').on('click', function() {
        $('#mobile-search-nav').slideToggle(200);
      });
    });
    </script>

    <?php if (isset($is_home_page) && $is_home_page): ?>
    <main class="container mx-auto px-4 py-6">

      <section class="mb-6 h-80 rounded-xl overflow-hidden shadow-lg relative" id="hero-slider">
        <div class="flex transition-transform duration-500 ease-in-out h-full" id="slider-track">
          <div class="w-full flex-shrink-0">
            <img src="<?= base_url('assets/img/slide_01.jpg'); ?>" class="w-full h-full object-cover" alt="Promo 1">
          </div>
          <div class="w-full flex-shrink-0">
            <img src="<?= base_url('assets/img/slide_02.jpg'); ?>" class="w-full h-full object-cover" alt="Promo 2">
          </div>
          <div class="w-full flex-shrink-0">
            <img src="<?= base_url('assets/img/slide_03.jpg'); ?>" class="w-full h-full object-cover" alt="Promo 3">
          </div>
        </div>
      </section>

      <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
        <!-- Kolom Kategori -->
        <div class="lg:col-span-8">
          <section class="bg-white p-6 rounded-xl shadow-md h-full">
            <h3 class="font-bold text-gray-800 mb-6 border-b pb-3 text-center text-lg">
              Kategori & Layanan Cepat
            </h3>

            <div class="flex flex-wrap justify-center gap-4 text-center">
              <?php 
        $icons = ['ti ti-shirt', 'ti ti-device-gamepad', 'ti ti-shoe', 'ti ti-devices', 'ti ti-wallet', 'ti ti-truck', 'ti ti-cut', 'ti ti-plant'];
        $default_categories = ['Pakaian', 'Aksesoris', 'Elektronik', 'Top Up', 'Logistik', 'Grooming', 'Tanaman', 'Semua'];
        $i = 0;
        foreach ($default_categories as $cat_name): ?>
              <a href="<?= site_url('category/semua'); ?>"
                class="flex flex-col items-center w-20 py-3 hover:bg-gray-50 rounded-xl transition">
                <div class="w-12 h-12 flex items-center justify-center rounded-full bg-indigo-100 mb-2">
                  <i class="<?= $icons[$i++ % count($icons)]; ?> text-indigo-600 text-xl"></i>
                </div>
                <span class="text-xs font-normal"><?= $cat_name; ?></span>
              </a>
              <?php endforeach; ?>
            </div>
          </section>
        </div>

        <!-- Kolom Animasi -->
        <div class="lg:col-span-4 flex justify-center items-center">
          <div id="anim-container" class="w-48 h-48"></div>
        </div>
      </div>

      <!-- Tambahkan Lottie.js -->
      <script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.12.0/lottie.min.js"></script>
      <script>
      lottie.loadAnimation({
        container: document.getElementById('anim-container'),
        renderer: 'svg',
        loop: true,
        autoplay: true,
        path: 'https://assets5.lottiefiles.com/packages/lf20_5ngs2ksb.json' // contoh animasi
      });
      </script>

    </main>
    <?php endif; ?>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
      const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
      const mobileNav = document.getElementById('mobile-nav');
      const closeMenuToggle = document.getElementById('close-menu-toggle');

      function toggleMenu() {
        mobileNav.classList.toggle('open');
      }

      mobileMenuToggle.addEventListener('click', toggleMenu);
      closeMenuToggle.addEventListener('click', toggleMenu);
    });
    </script>
</body>

</html>