# Kasir Pintar

Implementasi awal **Sistem Informasi Kasir Berbasis Web untuk Prediksi Penjualan dan Rekomendasi Restock**, berdasarkan PRD v2.0 dan Design Brief yang diberikan.

## Stack
- Laravel 12
- Blade
- Tailwind CSS 4 + CSS design tokens
- JavaScript ringan
- MySQL
- Forecast service modular (baseline Moving Average 30 Hari untuk implementasi awal)

## Fitur yang sudah diimplementasikan
### Owner
- Dashboard
- CRUD kategori
- CRUD produk + pencarian/filter + status
- Stok: status, Stock In, Stock Out
- Riwayat stok
- Riwayat transaksi + detail
- Laporan penjualan
- Laporan keuntungan
- Analitik produk
- Prediksi/forecast
- Impor data historis (alur dasar upload/validasi)
- Rekomendasi restock + salin daftar belanja
- Manajemen karyawan
- Activity Log

### Karyawan
- Dashboard
- POS keyboard-first
- Cari produk / barcode text
- Keranjang + quantity
- Pembayaran tunai + kembalian
- Transaksi atomik + pengurangan stok
- Info stok
- Penjualan hari ini

## Akun demo
- Owner: `owner` / `password`
- Karyawan: `kasir` / `password`

## Instalasi
1. Buat database MySQL bernama `kasir_pintar`.
2. Salin `.env.example` menjadi `.env`.
3. Isi konfigurasi DB.
4. Jalankan:
   ```bash
   composer install
   php artisan key:generate
   php artisan migrate --seed
   npm install
   npm run build
   php artisan serve
   ```
5. Buka `http://127.0.0.1:8000`.

## Catatan skripsi
PRD tidak mengunci algoritma prediksi. Implementasi ini menggunakan baseline **Moving Average 30 Hari** sebagai placeholder yang sengaja dipisahkan di `app/Services/ForecastService.php`. Setelah dataset penelitian tersedia, service tersebut dapat diganti/diintegrasikan dengan metode yang disetujui pembimbing tanpa mengubah UI utama.

## Struktur penting
- `app/Services/ForecastService.php` — modul forecast
- `app/Services/ActivityLogger.php` — audit log
- `app/Http/Controllers/TransactionController.php` — POS
- `resources/views/employee/pos.blade.php` — workspace POS
- `resources/css/app.css` — design tokens dan UI
- `resources/js/app.js` — interaksi POS, drawer, keyboard shortcut

## Catatan implementasi
Folder `vendor/` dan `node_modules/` tidak disertakan agar paket hasil tetap ringan. Jalankan `composer install` dan `npm install` setelah mengekstrak.


## Revisi Skripsi v2

Implementasi setelah bimbingan menambahkan:
- role Owner, Admin, dan Pegawai/Kasir;
- dashboard owner mobile-first dan API owner;
- modul pemasukan/pengeluaran dan ringkasan arus kas;
- integrasi transaksi penjualan sebagai pemasukan otomatis;
- API mobile owner dengan token;
- starter app Flutter pada `mobile-owner/`.

Akun seed:
- owner / password
- admin / password
- kasir / password

Untuk Flutter Android Emulator, API default menggunakan `http://10.0.2.2:8000/api/mobile`. Untuk perangkat fisik, build dengan `--dart-define=API_BASE=http://IP-KOMPUTER:8000/api/mobile`.
