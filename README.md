# Presensi App (Laravel)

Struktur folder & skeleton kode untuk Aplikasi Presensi berbasis Laravel dengan fitur:
- Autentikasi multi-role (Admin & Pegawai)
- Manajemen Jabatan, Lokasi Presensi, Pegawai
- Presensi harian (clock in/out) dengan foto selfie + geofencing radius
- Pengajuan & approval ketidakhadiran (izin/sakit/cuti)
- Rekap & export laporan presensi ke PDF (Dompdf)

## Cara pakai
1. Salin seluruh folder ini ke dalam project Laravel baru (`laravel new presensi-app`), timpa file yang sama.
2. Install dependency:
   ```
   composer require barryvdh/laravel-dompdf
   npm install
   ```
3. Daftarkan middleware `admin` dan `pegawai` di `bootstrap/app.php` (Laravel 11) atau `app/Http/Kernel.php` (Laravel 10):
   ```php
   'admin' => \App\Http\Middleware\AdminMiddleware::class,
   'pegawai' => \App\Http\Middleware\PegawaiMiddleware::class,
   ```
4. Jalankan migrasi & seeder:
   ```
   php artisan migrate --seed
   php artisan storage:link
   ```
5. Build asset:
   ```
   npm run dev
   ```
6. Login admin default (dari seeder): `admin` / `password`

## Catatan
- Kolom `jabatan` dan `lokasi_presensi` pada tabel `pegawai` disimpan sebagai varchar (bukan foreign key id), mengikuti desain ERD asli.
- Semua controller & view ini adalah skeleton fungsional dasar — silakan sesuaikan validasi, styling, dan penanganan error sesuai kebutuhan lanjutan.
