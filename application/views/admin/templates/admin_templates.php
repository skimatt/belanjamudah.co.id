<!doctype html>
<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact" dir="ltr"
  data-theme="theme-default" data-assets-path="<?= base_url('assets/'); ?>"
  data-template="vertical-menu-template-starter" data-style="light">

<head>
  <meta charset="utf-8" />
  <meta name="viewport"
    content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
  <title><?= $page_title ?? 'Dashboard'; ?> | Admin Toko MVP</title>
  <meta name="description" content="Admin Panel E-Commerce MVP CodeIgniter 3" />

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="<?= base_url('assets/img/favicon/favicon.ico'); ?>" />

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap"
    rel="stylesheet" />

  <!-- Styles -->
  <link rel="stylesheet" href="<?= base_url('assets/vendor/fonts/tabler-icons.css'); ?>" />
  <link rel="stylesheet" href="<?= base_url('assets/vendor/css/rtl/core.css'); ?>"
    class="template-customizer-core-css" />
  <link rel="stylesheet" href="<?= base_url('assets/vendor/css/rtl/theme-default.css'); ?>"
    class="template-customizer-theme-css" />
  <link rel="stylesheet" href="<?= base_url('assets/css/demo.css'); ?>" />
  <link rel="stylesheet" href="<?= base_url('assets/vendor/libs/node-waves/node-waves.css'); ?>" />
  <link rel="stylesheet" href="<?= base_url('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css'); ?>" />

  <!-- Scripts -->
  <script src="<?= base_url('assets/vendor/js/helpers.js'); ?>"></script>
  <script src="<?= base_url('assets/vendor/js/template-customizer.js'); ?>"></script>
  <script src="<?= base_url('assets/js/config.js'); ?>"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
  <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">

      <!-- Sidebar -->
      <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
        <div class="app-brand demo">
          <a href="<?= site_url('dashboard'); ?>" class="app-brand-link flex items-center space-x-2">

            <!-- LOGO SVG -->
            <span class="app-brand-logo demo">
              <svg width="40" height="40" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg"
                class="rounded-md">

                <defs>
                  <linearGradient id="bgGrad" x1="0" y1="0" x2="1" y2="1">
                    <stop offset="0%" stop-color="#2563EB" />
                    <stop offset="100%" stop-color="#1E3A8A" />
                  </linearGradient>

                  <linearGradient id="bagGrad" x1="0" y1="0" x2="1" y2="1">
                    <stop offset="0%" stop-color="#FFFFFF" stop-opacity="0.98" />
                    <stop offset="100%" stop-color="#E2E8F0" stop-opacity="0.98" />
                  </linearGradient>
                </defs>

                <rect width="200" height="200" rx="28" fill="url(#bgGrad)" />

                <g transform="translate(40,35)">
                  <rect x="10" y="40" width="100" height="90" rx="14" fill="url(#bagGrad)" stroke="#E0E7FF"
                    stroke-width="3" />

                  <path d="M35 40 C35 10, 85 10, 85 40" stroke="white" stroke-width="8" stroke-linecap="round" />

                  <path d="M35 75 L50 95 L65 75 L80 95 L95 75" stroke="#1E40AF" stroke-width="7" stroke-linecap="round"
                    stroke-linejoin="round" />
                </g>
              </svg>
            </span>

            <!-- BRAND TEXT -->
            <span class="app-brand-text demo menu-text fw-bold text-primary"
              style="font-size: 1.35rem; letter-spacing: .3px;">
              BelanjaMudah
            </span>

          </a>

          <!-- TOGGLE BUTTON MOBILE -->
          <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-xl-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2">
              <line x1="18" y1="6" x2="6" y2="18" />
              <line x1="6" y1="6" x2="18" y2="18" />
            </svg>
          </a>
        </div>



        <div class="menu-inner-shadow"></div>

        <?php $active = $this->uri->segment(2); ?>
        <ul class="menu-inner py-1">

          <!-- Dashboard -->
          <li class="menu-item <?= ($active == 'dashboard') ? 'active' : ''; ?>">
            <a href="<?= site_url('admin/dashboard'); ?>" class="menu-link">
              <i class="menu-icon tf-icons ti ti-smart-home"></i>
              <div data-i18n="Dashboard">Dashboard</div>
            </a>
          </li>

          <!-- Produk -->
          <li class="menu-header small text-uppercase"><span class="menu-header-text">Katalog & Stok</span></li>
          <li class="menu-item <?= ($active == 'product' || $active == 'category') ? 'open active' : ''; ?>">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
              <i class="menu-icon tf-icons ti ti-box"></i>
              <div data-i18n="Produk & Varian">Produk & Varian</div>
            </a>
            <ul class="menu-sub">
              <li class="menu-item <?= ($active == 'product') ? 'active' : ''; ?>">
                <a href="<?= site_url('admin/product'); ?>" class="menu-link">
                  <div data-i18n="Manajemen Produk">Manajemen Produk (CRUD)</div>
                </a>
              </li>
              <li class="menu-item <?= ($active == 'category') ? 'active' : ''; ?>">
                <a href="<?= site_url('admin/category'); ?>" class="menu-link">
                  <div data-i18n="Kategori Produk">Kategori Produk</div>
                </a>
              </li>
            </ul>
          </li>

          <!-- Transaksi -->
          <li class="menu-header small text-uppercase"><span class="menu-header-text">Transaksi</span></li>
          <li class="menu-item <?= ($active == 'order') ? 'active' : ''; ?>">
            <a href="<?= site_url('admin/order'); ?>" class="menu-link">
              <i class="menu-icon tf-icons ti ti-receipt-2"></i>
              <div data-i18n="Pesanan">Manajemen Pesanan</div>
            </a>
          </li>
          <li class="menu-item <?= ($active == 'report') ? 'active' : ''; ?>">
            <a href="<?= site_url('admin/report'); ?>" class="menu-link">
              <i class="menu-icon tf-icons ti ti-report-money"></i>
              <div data-i18n="Laporan">Laporan Keuangan</div>
            </a>
          </li>

          <li class="menu-item <?= ($active == 'voucher') ? 'active' : ''; ?>">
            <a href="<?= site_url('admin/voucher'); ?>" class="menu-link">
              <i class="menu-icon tf-icons ti ti-report-money"></i>
              <div data-i18n="Laporan">voucher Promo</div>
            </a>
          </li>

          <!-- Pengaturan -->
          <li class="menu-header small text-uppercase"><span class="menu-header-text">Pengaturan</span></li>
          <li class="menu-item <?= ($active == 'user') ? 'active' : ''; ?>">
            <a href="<?= site_url('admin/user'); ?>" class="menu-link">
              <i class="menu-icon tf-icons ti ti-users"></i>
              <div data-i18n="Pengguna">Manajemen Pengguna</div>
            </a>
          </li>
        </ul>
      </aside>
      <!-- /Sidebar -->

      <!-- Main Layout -->
      <div class="layout-page">

        <!-- Navbar -->
        <nav
          class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
          id="layout-navbar">

          <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
            <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
              <i class="ti ti-menu-2 ti-md"></i>
            </a>
          </div>

          <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

            <!-- Mode -->
            <div class="navbar-nav align-items-center">
              <div class="nav-item dropdown-style-switcher dropdown">
                <a class="nav-link btn btn-text-secondary btn-icon rounded-pill dropdown-toggle hide-arrow"
                  href="javascript:void(0);" data-bs-toggle="dropdown">
                  <i class="ti ti-md"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-start dropdown-styles">
                  <li><a class="dropdown-item" href="javascript:void(0);" data-theme="light">
                      <span class="align-middle"><i class="ti ti-sun me-3"></i>Light</span></a></li>
                  <li><a class="dropdown-item" href="javascript:void(0);" data-theme="dark">
                      <span class="align-middle"><i class="ti ti-moon-stars me-3"></i>Dark</span></a></li>
                  <li><a class="dropdown-item" href="javascript:void(0);" data-theme="system">
                      <span class="align-middle"><i class="ti ti-device-desktop-analytics me-3"></i>System</span></a>
                  </li>
                </ul>
              </div>
            </div>

            <!-- User -->
            <ul class="navbar-nav flex-row align-items-center ms-auto">
              <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
                  <div class="avatar avatar-online">
                    <img src="<?= base_url('assets/img/avatars/1.png'); ?>" alt class="rounded-circle" />
                  </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end">
                  <div class="px-3 py-2">
                    <h6 class="mb-0"><?= $this->session->userdata('full_name') ?? 'Admin'; ?></h6>
                    <small class="text-muted">Administrator</small>
                  </div>
                  <div class="dropdown-divider my-1 mx-n2"></div>
                  <div class="d-grid px-2 pt-2 pb-1">
                    <a class="btn btn-sm btn-danger d-flex" href="<?= site_url('auth/logout'); ?>">
                      <small class="align-middle">Logout</small>
                      <i class="ti ti-logout ms-2 ti-14px"></i>
                    </a>
                  </div>
                </div>
              </li>
            </ul>
            <!-- /User -->

          </div>
        </nav>
        <!-- /Navbar -->

        <!-- Content -->
        <div class="content-wrapper">
          <div class="container-xxl flex-grow-1 container-p-y">
            <?php $this->load->view($content_view); ?>
          </div>

          <!-- Footer -->
          <footer class="content-footer footer bg-footer-theme">
            <div class="container-xxl">
              <div
                class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
                <div class="text-body">
                  © <?= date('Y'); ?>, Dibuat dengan ❤️ oleh Toko MVP
                </div>
                <div class="d-none d-lg-inline-block"></div>
              </div>
            </div>
          </footer>
          <!-- /Footer -->

          <div class="content-backdrop fade"></div>
        </div>
        <!-- /Content -->

      </div>
    </div>
  </div>

  <div class="layout-overlay layout-menu-toggle"></div>
  <div class="drag-target"></div>

  <!-- Core JS -->
  <script src="<?= base_url('assets/vendor/libs/jquery/jquery.js'); ?>"></script>
  <script src="<?= base_url('assets/vendor/libs/popper/popper.js'); ?>"></script>
  <script src="<?= base_url('assets/vendor/js/bootstrap.js'); ?>"></script>
  <script src="<?= base_url('assets/vendor/libs/node-waves/node-waves.js'); ?>"></script>
  <script src="<?= base_url('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js'); ?>"></script>
  <script src="<?= base_url('assets/vendor/libs/hammer/hammer.js'); ?>"></script>
  <script src="<?= base_url('assets/vendor/js/menu.js'); ?>"></script>
  <script src="<?= base_url('assets/js/main.js'); ?>"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</body>

</html>