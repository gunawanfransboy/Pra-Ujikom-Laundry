# Web Gunawan Laundry 

Aplikasi Manajemen Laundry (Point of Sales) yang dibangun menggunakan framework **Laravel**. Aplikasi ini dirancang untuk mempermudah operasional bisnis laundry mulai dari pencatatan pesanan (transaksi), hingga manajemen hak akses pengguna (pegawai).

## Fitur Utama
- **Autentikasi Pengguna**: Sistem Login yang aman untuk pegawai.
- **Manajemen Hak Akses (Level/Role)**: Pembedaan akses aplikasi berdasarkan level pengguna (contoh: Admin, Kasir, Owner).
- **Dashboard**: Ringkasan data operasional laundry.
- **Manajemen Transaksi (Order)**: Pencatatan, pembuatan pesanan cucian baru, dan manajemen detail transaksi pelanggan.

## Persyaratan Sistem
- PHP >= 8.1
- Composer
- Node.js & NPM
- Database MySQL/MariaDB

## Instalasi & Persiapan

1. Buka terminal/command prompt dan arahkan ke direktori project.
2. **Install Dependensi PHP & Frontend**:
   ```bash
   composer install
   npm install
   npm run build
   ```
3. **Konfigurasi Environment**:
   Salin file `.env.example` ke `.env` (Jika belum ada).
   ```bash
   cp .env.example .env
   ```
   Atur koneksi database Anda di dalam file `.env` (Sesuaikan `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).
4. **Generate Application Key**:
   ```bash
   php artisan key:generate
   ```
5. **Migrasi Database & Seeding**:
   Jalankan perintah berikut untuk membuat struktur tabel (termasuk tabel `levels`, `trans_orders`, dll) beserta data awal (seperti akun default):
   ```bash
   php artisan migrate --seed
   ```

## Alur Penggunaan Aplikasi (Cara Pakai)

1. **Jalankan Aplikasi:**
   Buka terminal di folder project, dan jalankan local server:
   ```bash
   php artisan serve
   ```
2. **Akses Aplikasi:**
   Buka web browser dan kunjungi `http://localhost:8000`.
3. **Login Sistem:**
   Aplikasi akan menampilkan halaman **Login**. Silakan masuk menggunakan kredensial akun default (biasanya akan dibuat saat Anda menjalankan proses seeding `LevelSeeder` dan user seeder).
4. **Dashboard:**
   Setelah login berhasil, Anda akan masuk ke halaman **Dashboard** untuk memantau ringkasan singkat tentang sistem.
5. **Mencatat Pesanan Baru:**
   - Navigasi ke menu **Order / Transaksi**.
   - Pilih opsi **Tambah Pesanan (Create Order)**.
   - Isi form detail pencucian (jenis barang, berat/jumlah, serta harga).
   - Simpan data pesanan tersebut ke dalam sistem.
6. **Memproses Pesanan:**
   Admin atau kasir yang bertugas akan mengupdate pesanan seiring proses pencucian selesai dilakukan. Pesanan dapat dikelola melalui daftar Transaksi Order.
7. **Logout:**
   Pastikan menekan tombol Keluar/Logout di menu utama saat shift telah usai demi keamanan operasional.
