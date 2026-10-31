# Project Laravel ArsyClean Laundry (Multi-Role Authentication)
Sistem Informasi Pengelolaan Laundry Self-Service Koin & Reguler berdasarkan dokumen **SRS & SDD Kelompok 7 (APSI)**.

---

## 🚀 Fitur Unggulan Arsitektur Laravel
1. **Multi-Role Authentication Terpisah (Pelanggan, Admin, Owner)**
   - Proteksi ketat menggunakan `App\Http\Middleware\CheckRole`.
   - Gerbang login per role dengan validasi penolakan jika salah portal.
   - 1-Klik Akun Demo untuk mempermudah evaluasi dosen penguji.
2. **10 Tabel Eloquent ORM & Migrations**
   - Normalisasi 2NF & Physical Design Bab IV SDD: `users`, `layanan`, `pesanan`, `pesanan_layanan`, `pembayaran`, `mesin`, `penggunaan_mesin`, `stok`, `pengiriman`, `ulasan`.
3. **Seeder Otomatis (`DatabaseSeeder.php`)**
   - Langsung menginjeksi 5 akun pengguna, 6 tarif layanan, 5 mesin laundry, dan 5 stok bahan baku & koin tanpa perlu input manual satu per satu.

---

## 🛠️ Panduan Instalasi & Menjalankan di XAMPP / Komputer Lokal

### Langkah 1: Persiapan Database di XAMPP
1. Buka **XAMPP Control Panel**, lalu klik **Start** pada modul **Apache** dan **MySQL**.
2. Buka phpMyAdmin di browser Anda (`http://localhost/phpmyadmin` atau `http://localhost:3308/phpmyadmin`).
3. Buat database baru bernama:
   ```sql
   CREATE DATABASE arsyclean_laundry;
   ```

### Langkah 2: Konfigurasi File `.env`
1. Salin file `.env.example` menjadi `.env`:
   ```bash
   cp .env.example .env
   ```
2. Pastikan port database MySQL sesuai dengan XAMPP Anda:
   ```ini
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3308   # Ganti 3306 atau 3308 sesuai konfigurasi MySQL XAMPP Anda
   DB_DATABASE=arsyclean_laundry
   DB_USERNAME=root
   DB_PASSWORD=
   ```

### Langkah 3: Install Dependensi Composer & Generate Key
Buka terminal / Command Prompt di dalam folder proyek ini:
```bash
composer install
php artisan key:generate
```

### Langkah 4: Jalankan Migrasi & Seeder Database (Otomatis)
Cukup jalankan satu baris perintah ini:
```bash
php artisan migrate --seed
```
*Perintah di atas akan langsung membuat seluruh 10 tabel dan mengisi data awal (users, layanan, mesin, stok, pesanan) secara otomatis!*

### Langkah 5: Jalankan Server Lokal Laravel
```bash
php artisan serve
```
Buka browser Anda dan akses:
👉 **`http://localhost:8000`**

---

## 🔑 Kredensial Login Bawaan (Dokumen SDD Bab IV)

| Peran (Role) | Email | Password | Hak Akses Utama |
| :--- | :--- | :--- | :--- |
| **👤 Pelanggan** | `andi@arsyclean.com` | `password123` | Buat pesanan, pantau mesin cuci, bayar digital, struk, rating ulasan |
| **👤 Pelanggan 2** | `siti@arsyclean.com` | `password123` | Akun pelanggan alternatif |
| **🛡️ Admin** | `admin@arsyclean.com` | `admin123` | Kelola antrean cucian, stok deterjen/koin, kurir, verifikasi kasir |
| **🛡️ Admin 2** | `budi_admin@arsyclean.com` | `admin123` | Staf operasional & kasir |
| **👑 Owner** | `owner@arsyclean.com` | `owner123` | Pantau omzet bulanan (Rp 12.375.899+), laporan keuangan, ubah tarif |

---

## 📁 Struktur Direktori Laravel
- `app/Http/Controllers/Auth/RoleAuthController.php`: Pengatur login per role dan registrasi
- `app/Http/Middleware/CheckRole.php`: Middleware penjaga akses per role
- `app/Http/Controllers/`: `PelangganController.php`, `AdminController.php`, `OwnerController.php`
- `app/Models/`: 10 model Eloquent (`User.php`, `Layanan.php`, `Pesanan.php`, dll.)
- `database/migrations/`: Migrasi 10 tabel MySQL
- `database/seeders/DatabaseSeeder.php`: Injeksi data demo SDD
- `resources/views/`: Blade templates (`auth/`, `pelanggan/`, `admin/`, `owner/`, `layouts/app.blade.php`)
- `routes/web.php`: Rute dengan prefix dan middleware peran
