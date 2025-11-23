<div align="center">
  <a href="https://github.com/username/belanjamudah">
    <img src="https://cdn-icons-png.flaticon.com/512/3081/3081559.png" alt="Logo BelanjaMudah" width="120" height="120">
  </a>

  <h1 align="center">BelanjaMudah.id 🛒</h1>

  <p align="center">
    <strong>Platform E-Commerce Modern, Cepat, dan Terpercaya.</strong><br>
    Dibangun dengan performa tinggi dan antarmuka yang memukau untuk pengalaman belanja terbaik.
  </p>

  <p align="center">
    <a href="#">
      <img src="https://img.shields.io/badge/PHP-7.4%2B-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
    </a>
    <a href="#">
      <img src="https://img.shields.io/badge/CodeIgniter-3-EF4223?style=for-the-badge&logo=codeigniter&logoColor=white" alt="CodeIgniter">
    </a>
    <a href="#">
      <img src="https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind">
    </a>
    <a href="#">
      <img src="https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
    </a>
    <a href="#">
      <img src="https://img.shields.io/badge/Midtrans-Payment-blue?style=for-the-badge&logo=contactless-payment&logoColor=white" alt="Midtrans">
    </a>
  </p>
  
  <br>
</div>

<p align="center">
  <img src="https://images.unsplash.com/photo-1556742049-0cfed4f7a07d?q=80&w=1000&auto=format&fit=crop" alt="App Preview" width="100%" style="border-radius: 10px;">
</p>

---

## 📖 Tentang Proyek

**BelanjaMudah.id** adalah solusi e-commerce *end-to-end* yang dirancang untuk memudahkan proses jual beli online. Proyek ini tidak hanya berfokus pada fungsionalitas backend yang kuat menggunakan CodeIgniter 3, tetapi juga memprioritaskan **User Interface (UI)** yang modern, *clean*, dan responsif menggunakan Tailwind CSS.

Fitur unggulan mencakup sistem pelacakan paket real-time (Live Tracking) dengan peta interaktif dan integrasi pembayaran otomatis (Payment Gateway).

## ✨ Fitur Utama

Berikut adalah fitur-fitur canggih yang disematkan dalam aplikasi ini:

<table>
  <tr>
    <td width="50%">
      <h3>🛍️ Pengalaman Belanja (Frontend)</h3>
      <ul>
        <li><strong>Desain UI Modern & Responsif</strong> (Mobile-First).</li>
        <li><strong>Pencarian Produk Canggih</strong> dengan filter kategori & harga.</li>
        <li><strong>Keranjang Belanja Dinamis</strong> (AJAX based).</li>
        <li><strong>Checkout Multi-step</strong> yang aman dan ringkas.</li>
        <li><strong>Live Tracking Pesanan</strong> menggunakan Peta Interaktif (Leaflet.js).</li>
        <li><strong>User Dashboard</strong> (Riwayat pesanan, alamat, profil).</li>
      </ul>
    </td>
    <td width="50%">
      <h3>⚙️ Manajemen Sistem (Backend)</h3>
      <ul>
        <li><strong>Dashboard Admin Informatif</strong> dengan grafik penjualan.</li>
        <li><strong>Manajemen Produk</strong> (Varian, Stok, Gambar Galeri).</li>
        <li><strong>Manajemen Pesanan</strong> (Update status, Input Resi).</li>
        <li><strong>Laporan Keuangan</strong> otomatis.</li>
        <li><strong>Manajemen Pengguna & Hak Akses</strong>.</li>
        <li><strong>Integrasi Payment Gateway</strong> (Midtrans Snap).</li>
      </ul>
    </td>
  </tr>
</table>

---

## 📸 Galeri Antarmuka

> *Tampilan antarmuka yang dirancang dengan prinsip estetika modern.*

| Homepage Premium | Detail Produk |
| :---: | :---: |
| <img src="https://placehold.co/600x400/4f46e5/ffffff?text=Screenshot+Homepage" alt="Home" width="100%"> | <img src="https://placehold.co/600x400/e5e7eb/1f2937?text=Screenshot+Detail+Produk" alt="Product" width="100%"> |

| Tracking Pesanan | Checkout Page |
| :---: | :---: |
| <img src="https://placehold.co/600x400/0d9488/ffffff?text=Screenshot+Live+Tracking" alt="Tracking" width="100%"> | <img src="https://placehold.co/600x400/4338ca/ffffff?text=Screenshot+Checkout" alt="Checkout" width="100%"> |

---

## 🛠️ Teknologi yang Digunakan

Proyek ini dibangun menggunakan teknologi yang stabil dan handal:

* **Bahasa Pemrograman:** PHP 7.4 / 8.0
* **Framework:** CodeIgniter 3
* **Database:** MySQL / MariaDB
* **Frontend:** HTML5, CSS3, Tailwind CSS, Bootstrap (Components)
* **Scripting:** jQuery, AJAX, SweetAlert2, Leaflet.js (Maps), Chart.js
* **Payment Gateway:** Midtrans API
* **Icons:** Tabler Icons

---

## 🚀 Cara Instalasi

Ikuti langkah-langkah berikut untuk menjalankan proyek ini di komputer lokal Anda (Localhost):

<details>
<summary><strong>Klik untuk melihat panduan instalasi</strong></summary>

1.  **Clone Repositori**
    ```bash
    git clone [https://github.com/skimatt/belanjamudah.git](https://github.com/username/belanjamudah.git)
    ```

2.  **Konfigurasi Database**
    * Buat database baru di phpMyAdmin bernama `belanjamudah_db`.
    * Import file `database.sql` yang ada di folder `/db` ke dalam database tersebut.
		* kalau tidak menemukan nya dm ig : @skimatt_

3.  **Konfigurasi Proyek**
    * Buka `application/config/database.php` dan sesuaikan username/password database.
    * Buka `application/config/config.php` dan atur `base_url`.

    ```php
    $config['base_url'] = 'http://localhost/belanjamudah/';
    ```

4.  **Jalankan**
    * Pindahkan folder proyek ke `htdocs` (XAMPP) atau `www` (Laragon).
    * Akses melalui browser: `http://localhost/belanjamudah`

</details>

---

## 🤝 Kontribusi

Kontribusi sangat dipersilakan! Jika Anda ingin meningkatkan fitur atau memperbaiki bug:

1.  **Fork** repositori ini.
2.  Buat **Branch** baru (`git checkout -b fitur-keren`).
3.  **Commit** perubahan Anda (`git commit -m 'Menambahkan fitur keren'`).
4.  **Push** ke Branch (`git push origin fitur-keren`).
5.  Buat **Pull Request**.

---

## 📄 Lisensi

Didistribusikan di bawah Lisensi MIT. Lihat `LICENSE` untuk informasi lebih lanjut.

---

<div align="center">
  <p>Dibuat dengan ❤️ dan ☕ oleh <strong>Rahmat Mulia Pengembang BelanjaMudah</strong></p>
  
  <a href="https://github.com/skimatt">
    <img src="https://img.shields.io/github/followers/skimatt?label=Follow&style=social" alt="GitHub Followers">
  </a>
</div>
