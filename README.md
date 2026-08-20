# 💰 Sistem Kas Sederhana Multi-User (Laravel 12 & TailAdmin)

[![Laravel Version](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-v4.0-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![Alpine.js](https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?style=for-the-badge&logo=alpinedotjs&logoColor=white)](https://alpinejs.dev)
[![PHP Version](https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Pest Testing](https://img.shields.io/badge/Pest_PHP-100%25_Pass-845EEE?style=for-the-badge&logo=php&logoColor=white)](https://pestphp.com)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg?style=for-the-badge)](https://opensource.org/licenses/MIT)

**Sistem Kas Sederhana Multi-User** adalah aplikasi pencatatan keuangan dan monitoring arus kas (*cash flow*) modern yang dirancang untuk skala bisnis UMKM, toko, organisasi, hingga perusahaan multi-cabang. Dibangun di atas **Laravel 12**, **Tailwind CSS**, **Alpine.js**, dan template dashboard **TailAdmin** dengan menerapkan kaidah **Clean Architecture** (*Separation of Concerns*).

![Sistem Kas Multi-User Preview](./public/images/logo/logo-icon.svg)

---

## ✨ Fitur Utama (Key Features)

- 🔐 **Autentikasi & Multi-User Role**:
  - Hak akses berbasis peran (**Administrator** dan **Staff Kasir**).
  - Manajemen status akun pengguna (*Active / Inactive*) dengan middleware proteksi.
  - Pengaturan profil pengguna dilengkapi upload foto dengan **Live Image Preview**.
- 📥 **Pencatatan Mutasi Kas Masuk & Keluar**:
  - Nomor transaksi otomatis berformat `TRX-YYYYMMDD-XXXX`.
  - Dukungan nomor referensi, nota, atau invoice.
  - Audit trail lengkap (mencatat siapa yang membuat dan mengedit transaksi).
- 🏷️ **Manajemen Kategori Kas Fleksibel**:
  - Kategori khusus Pemasukan (*Income*), Pengeluaran (*Expense*), maupun Keduanya (*Both*).
  - Proteksi integritas data (*soft delete*) agar kategori yang memiliki riwayat transaksi tidak dapat terhapus secara tidak sengaja.
- 📊 **Dashboard Finansial Real-Time**:
  - Kartu statistik total saldo kas, pemasukan bulan ini, pengeluaran bulan ini, dan selisih bersih.
  - Grafik visual tren arus kas (6 bulan terakhir) dan distribusi pengeluaran per kategori.
  - Daftar transaksi kas terkini dengan navigasi cepat.
- 📑 **Laporan Kas & Buku Besar Siap Cetak**:
  - Filter rentang tanggal kustom dan preset cepat (*Hari Ini, Bulan Ini, Bulan Lalu, Tahun Ini*).
  - Perhitungan otomatis **Saldo Awal**, mutasi periode, dan **Saldo Akhir**.
  - Kolom **Saldo Berjalan** (*running balance*) per transaksi.
  - Tampilan cetak / PDF (*Print-Ready View*) lengkap dengan format tanda tangan pengesahan.
- 🎨 **Desain UI/UX Modern (TailAdmin)**:
  - Dukungan penuh mode gelap/terang (**Dark & Light Mode**).
  - Form input dengan *icon group* dan *placeholder* kontekstual.
  - Penomoran otomatis baris tabel (`#`) yang sinkron dengan pagination.
- 🧪 **Pengujian Otomatis (100% Test Coverage)**:
  - Dilengkapi 42 Feature & Unit Test (Pest PHP) dengan 110 assertions tanpa galat.

---

## 📋 Prasyarat Sistem (Requirements)

Sebelum menjalankan aplikasi, pastikan komputer/server Anda memenuhi spesifikasi berikut:

- 🐘 **PHP**: Versi **8.2** atau **8.3+**
- 📦 **Composer**: Versi **2.7+**
- 🗄️ **Database**: **MySQL 8.0+** atau **MariaDB 10.4+**
- 🟢 **Node.js & NPM**: Node.js **v18+ / v20+ LTS** dan NPM **9+**
- 🧩 **Ekstensi PHP Wajib**:
  `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `fileinfo`, `gd`, `zip`, `curl`, `intl`.

---

## 🚀 Cara Instalasi (Installation Guide)

Ikuti langkah-langkah berikut untuk menyiapkan aplikasi di lingkungan lokal:

### 1. Clone Repository
```bash
git clone https://github.com/hndko/app_kasmultiuser_laravel12.git
cd app_kasmultiuser_laravel12
```

### 2. Install Dependensi PHP & Frontend
```bash
# Install package PHP via Composer
composer install

# Install package Node.js via NPM
npm install
```

### 3. Konfigurasi Environment (`.env`)
Salin file konfigurasi contoh:
```bash
# Windows:
copy .env.example .env

# Linux / macOS:
cp .env.example .env
```

### 4. Generate Application Key
```bash
php artisan key:generate
```

### 5. Konfigurasi Koneksi Database
Buka file `.env` dan sesuaikan kredensial database MySQL Anda:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=app_kasmultiuser_laravel12
DB_USERNAME=root
DB_PASSWORD=
```

### 6. Jalankan Migrasi & Database Seeder
Jalankan perintah berikut untuk membuat seluruh tabel dan mengisikan data bawaan (kategori & akun pengguna):
```bash
php artisan migrate --seed
```

### 7. Buat Symlink Storage & Kompilasi Aset
```bash
# Membuat symlink untuk file avatar dan upload
php artisan storage:link

# Kompilasi aset CSS & JS untuk produksi
npm run build
```

---

## 💻 Cara Penggunaan (Usage)

### Menjalankan Server Pengembangan (Local Dev)
Jalankan Laravel artisan server:
```bash
php artisan serve
```
Buka browser Anda dan akses: **`http://127.0.0.1:8000`**

> **Tip:** Jika sedang memodifikasi tampilan Blade/Tailwind, jalankan `npm run dev` di tab terminal terpisah untuk mengaktifkan Vite Hot Module Replacement (HMR).

---

### 🔑 Kredensial Akun Bawaan (Pengujian / Seeder)

| Peran (Role) | Nama | Alamat Email | Kata Sandi | Status Akun |
|---|---|---|---|---|
| **Administrator** | Administrator | `admin@example.com` | `password` | **Aktif** |
| **Staff Kasir** | Staff Kasir | `user@example.com` | `password` | **Aktif** |
| **User Nonaktif** | Inactive User | `inactive@example.com` | `password` | **Nonaktif (Terkunci)** |

---

### 🧪 Menjalankan Automated Tests
Jalankan pengujian seluruh alur otorisasi, CRUD transaksi, laporan kas, dan profil pengguna dengan Pest:
```bash
php artisan test
```

---

## 📚 Dokumentasi Lengkap (Documentation Links)

Seluruh panduan teknis dan deployment terpisah tersedia pada folder [`docs/`](./docs/):

- 📖 [**01. PRD — Sistem Kas Sederhana Multi-User**](./docs/01.%20PRD%20%E2%80%94%20Sistem%20Kas%20Sederhana%20Multi-User.md) — Dokumen spesifikasi kebutuhan produk.
- 💻 [**02. Deployment Guide — Local Development**](./docs/02.%20Deployment%20Guide%20%E2%80%94%20Local%20Development.md) — Panduan instalasi di komputer lokal (Windows/macOS/Linux).
- 🌐 [**03. Deployment Guide — Shared Hosting (cPanel)**](./docs/03.%20Deployment%20Guide%20%E2%80%94%20Shared%20Hosting%20%28cPanel%29.md) — Panduan aman pemisahan folder di cPanel hosting.
- 🐧 [**04. Deployment Guide — VPS (Ubuntu, Nginx, PHP-FPM, SSL)**](./docs/04.%20Deployment%20Guide%20%E2%80%94%20VPS%20%28Ubuntu,%20Nginx,%20PHP-FPM,%20SSL%29.md) — Provisioning server VPS produksi dari nol.
- 🎛️ [**05. Deployment Guide — VPS with aaPanel**](./docs/05.%20Deployment%20Guide%20%E2%80%94%20VPS%20with%20aaPanel.md) — Panduan deployment praktis via kontrol panel aaPanel.
- 🖥️ [**06. Hardware & Server Specification Guide**](./docs/06.%20Hardware%20&%20Server%20Specification%20Guide.md) — Panduan rekomendasi spek server & standar metrik kapan harus upgrade.

---

## 🤝 Panduan Kontribusi (Contributing)

Kami menyambut baik kontribusi dari komunitas! Untuk menjaga kerapian repositori, ikuti aturan berikut:

1. **Fork** repositori ini ke akun GitHub Anda.
2. Buat branch fitur baru:
   ```bash
   git checkout -b feat/nama-fitur-baru
   ```
3. Lakukan perubahan kode dengan mematuhi aturan arsitektur pada [`AGENTS.md`](./AGENTS.md).
4. Pastikan seluruh pengujian lokal lulus:
   ```bash
   php artisan test
   ```
5. Gunakan format **Conventional Commits** saat commit:
   ```bash
   git commit -m "feat(module): deskripsi singkat perubahan"
   ```
6. Push branch Anda dan buat **Pull Request (PR)** baru ke branch `main`.

---

## 📜 Lisensi (License)

Proyek ini dirilis di bawah lisensi terbuka [MIT License](https://opensource.org/licenses/MIT). Anda bebas menggunakan, memodifikasi, dan mendistribusikan kode ini untuk keperluan komersial maupun non-komersial.

---

<p align="center">
  Dibuat dengan ❤️ untuk kemudahan pengelolaan pembukuan kas UMKM dan organisasi.
</p>
