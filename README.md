# SIJAK Command Center Tomohon (Laravel 12)

Sistem Informasi Jadwal dan Agenda Kegiatan Command Center — Dinas Komunikasi dan Informatika Kota Tomohon.

Teknologi: PHP 8.2+, Laravel 12, MySQL, Blade, CSS, JavaScript. Tidak memakai Vite/npm, jadi **tidak perlu menjalankan `npm`**.

## Cara menjalankan (Windows, PowerShell/CMD)

Prasyarat (sudah terpasang): PHP, Composer, XAMPP (MySQL), VS Code.

1. **Ekstrak ZIP**, lalu buka terminal di folder `sijak-laravel` (folder yang berisi file `artisan`).
   ```
   cd C:\lokasi\sijak-laravel
   dir artisan
   ```
2. **Pasang dependensi** (pilih salah satu):
   - Cara cepat: salin folder `vendor` dari proyek lama Anda ke folder ini (isinya Laravel 12.70 yang sama).
   - Atau jalankan: `composer install`
3. **Buat file `.env` dan kunci aplikasi**:
   ```
   copy .env.example .env
   php artisan key:generate
   ```
4. **Nyalakan MySQL** di XAMPP Control Panel, lalu buat database di `http://localhost/phpmyadmin`:
   nama `sijak_tomohon`, collation `utf8mb4_unicode_ci`.
   (Jika user `root` Anda memakai password, isi `DB_PASSWORD` di file `.env`.)
5. **Buat tabel dan data awal**:
   ```
   php artisan migrate --seed
   ```
6. **Jalankan website**:
   ```
   php artisan serve
   ```
   Buka `http://127.0.0.1:8000`

## Login admin

Alamat: `http://127.0.0.1:8000/login`

| Username | Password |
|---|---|
| `admin` | `Sijak@2026` |

**Segera ganti password** lewat menu *Akun Admin → Edit* setelah login pertama.

## Fitur

- **Publik:** beranda, jadwal (pencarian + filter), kalender agenda, detail kegiatan, dokumentasi.
- **Admin (wajib login):** dashboard, kegiatan (tambah/edit/hapus), kalender, kategori, lokasi, dokumentasi (upload foto/PDF/DOC/DOCX maks. 5 MB), laporan (filter, cetak, unduh CSV), akun admin.
- Kegiatan bisa disembunyikan dari publik dengan menghilangkan centang *Tampilkan di halaman publik*.

## Struktur folder

| Lokasi | Isi |
|---|---|
| `routes/web.php` | Semua route (publik, login, admin) |
| `app/Http/Controllers/` | `PublikController`, `Auth/LoginController`, `Admin/*` |
| `app/Models/` | `Kegiatan`, `Kategori`, `Lokasi`, `Dokumentasi`, `User` |
| `app/Http/Requests/KegiatanRequest.php` | Aturan validasi formulir kegiatan |
| `database/migrations/` | Struktur tabel MySQL |
| `database/seeders/` | Admin awal, kategori, lokasi, dan data contoh |
| `resources/views/layouts/` | Kerangka halaman `publik` dan `admin` (dipakai ulang) |
| `resources/views/publik/`, `auth/`, `admin/`, `partials/` | Halaman Blade |
| `public/assets/css/style.css` | Desain asli SIJAK (dipertahankan) |
| `public/assets/css/admin.css` | Tampilan area admin |
| `public/assets/images/` | Logo dan foto Command Center |
| `public/uploads/dokumentasi/` | Hasil upload dokumentasi |

## Perawatan

- **Ganti logo:** timpa `public/assets/images/logo-kota-tomohon.png` dengan file logo resmi (PNG transparan, rasio mendekati kotak).
- **Hapus data contoh:** hapus baris `ContohDataSeeder::class` di `database/seeders/DatabaseSeeder.php` **sebelum** menjalankan `migrate --seed`, atau hapus data lewat menu admin.
- **Reset database ke kondisi awal:** `php artisan migrate:fresh --seed` — **PERINGATAN: menghapus semua data.**
- **Cadangan data:** ekspor database `sijak_tomohon` lewat phpMyAdmin, dan salin folder `public/uploads/dokumentasi/`.
- **Jalankan tes otomatis:** `php artisan test`

## Jika ada masalah

| Pesan | Penyebab / solusi |
|---|---|
| `Could not open input file: artisan` | Anda belum berada di folder `sijak-laravel` yang berisi `artisan`. |
| `could not find driver` | Aktifkan `extension=pdo_mysql` di `php.ini`. Cek PHP mana yang dipakai: `php --ini`. |
| `SQLSTATE[HY000] [2002]` | MySQL belum menyala di XAMPP. |
| `[1049] Unknown database` | Database `sijak_tomohon` belum dibuat (langkah 4). |
| `[1045] Access denied` | Username/password MySQL di `.env` tidak sesuai. Setelah mengubah `.env`: `php artisan config:clear`. |
| `No application encryption key` | Jalankan `php artisan key:generate`. |
| `419 Page Expired` | Muat ulang halaman login lalu coba lagi. |
| Port 8000 dipakai | `php artisan serve --port=8001` |
| Upload gagal | Pastikan `extension=fileinfo` aktif dan ukuran berkas maks. 5 MB. |
