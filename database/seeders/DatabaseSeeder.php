<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with SDD Bab IV Data.
     */
    public function run(): void
    {
        // 1. DATA PENGGUNA (Tabel 4.4.1 SDD)
        DB::table('users')->insert([
            [
                'id_user' => 1,
                'kode_user' => 'U001',
                'nama' => 'Andi Pratama',
                'email' => 'andi@arsyclean.com',
                'password' => Hash::make('password123'),
                'no_hp' => '081234567890',
                'alamat' => 'Jl. Merdeka No. 10, Jakarta Selatan',
                'role' => 'pelanggan',
                'status' => 'aktif',
                'foto' => 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=150',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 2,
                'kode_user' => 'U002',
                'nama' => 'Siti Aisyah',
                'email' => 'siti@arsyclean.com',
                'password' => Hash::make('password123'),
                'no_hp' => '081298765432',
                'alamat' => 'Jl. Sudirman No. 45, Jakarta Pusat',
                'role' => 'pelanggan',
                'status' => 'aktif',
                'foto' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=150',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 3,
                'kode_user' => 'U003',
                'nama' => 'Budi Santoso',
                'email' => 'budi_admin@arsyclean.com',
                'password' => Hash::make('admin123'),
                'no_hp' => '085612349876',
                'alamat' => 'Jl. Kebon Jeruk No. 12, Jakarta Barat',
                'role' => 'admin',
                'status' => 'aktif',
                'foto' => 'https://images.unsplash.com/photo-1570295999919-56ceb5ecca61?w=150',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 4,
                'kode_user' => 'U004',
                'nama' => 'Sulistiawati',
                'email' => 'admin@arsyclean.com',
                'password' => Hash::make('admin123'),
                'no_hp' => '087812345678',
                'alamat' => 'Jl. Tebet Raya No. 88, Jakarta Selatan',
                'role' => 'admin',
                'status' => 'aktif',
                'foto' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?w=150',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 5,
                'kode_user' => 'U005',
                'nama' => 'Haji Arsyad',
                'email' => 'owner@arsyclean.com',
                'password' => Hash::make('owner123'),
                'no_hp' => '081199887766',
                'alamat' => 'Jl. Boulevard Raya No. 1, Kelapa Gading',
                'role' => 'owner',
                'status' => 'aktif',
                'foto' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // 2. DATA LAYANAN (Tabel 4.4.2 SDD)
        DB::table('layanan')->insert([
            [
                'id_layanan' => 1,
                'kode_layanan' => 'L001',
                'nama_layanan' => 'Cuci Kering Reguler (2 Hari)',
                'jenis_layanan' => 'Reguler',
                'harga' => 8000.00,
                'satuan' => 'Kg',
                'durasi' => '2 Hari',
                'deskripsi' => 'Pencucian pakaian reguler higienis termasuk deterjen premium dan pelipatan rapi.',
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_layanan' => 2,
                'kode_layanan' => 'L002',
                'nama_layanan' => 'Cuci Koin Self-Service (Mesin Cuci)',
                'jenis_layanan' => 'Koin',
                'harga' => 15000.00,
                'satuan' => 'Koin',
                'durasi' => '30 Menit',
                'deskripsi' => '1 Koin token untuk mengoperasikan mesin cuci front-loading 7 Kg.',
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_layanan' => 3,
                'kode_layanan' => 'L003',
                'nama_layanan' => 'Kering Koin Self-Service (Mesin Pengering)',
                'jenis_layanan' => 'Koin',
                'harga' => 15000.00,
                'satuan' => 'Koin',
                'durasi' => '30 Menit',
                'deskripsi' => '1 Koin token untuk mengoperasikan mesin pengering panas gas (dryer).',
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_layanan' => 4,
                'kode_layanan' => 'L004',
                'nama_layanan' => 'Cuci Komplit + Antar Jemput Express',
                'jenis_layanan' => 'Antar Jemput',
                'harga' => 12000.00,
                'satuan' => 'Kg',
                'durasi' => '1 Hari',
                'deskripsi' => 'Layanan cuci, kering, setrika wangi dengan kurir jemput ke lokasi.',
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_layanan' => 5,
                'kode_layanan' => 'L005',
                'nama_layanan' => 'Dry Clean Jas / Blazer',
                'jenis_layanan' => 'Dry Clean',
                'harga' => 25000.00,
                'satuan' => 'Pcs',
                'durasi' => '3 Hari',
                'deskripsi' => 'Perawatan khusus pakaian berbahan wol, sutra, jas formal anti susut.',
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_layanan' => 6,
                'kode_layanan' => 'L006',
                'nama_layanan' => 'Cuci Bed Cover / Selimut Besar',
                'jenis_layanan' => 'Express',
                'harga' => 30000.00,
                'satuan' => 'Pcs',
                'durasi' => '2 Hari',
                'deskripsi' => 'Pencucian bed cover king size dengan mesin kapasitas besar anti debu tungau.',
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // 3. DATA MESIN (Tabel 4.4.5 SDD)
        DB::table('mesin')->insert([
            ['id_mesin' => 1, 'kode_mesin' => 'MC01', 'nama_mesin' => 'LG Commercial Front Load A', 'jenis' => 'Cuci', 'kapasitas_kg' => 7.0, 'status' => 'Digunakan', 'terakhir_servis' => '2026-10-01', 'created_at' => now(), 'updated_at' => now()],
            ['id_mesin' => 2, 'kode_mesin' => 'MC02', 'nama_mesin' => 'LG Commercial Front Load B', 'jenis' => 'Cuci', 'kapasitas_kg' => 7.0, 'status' => 'Tersedia', 'terakhir_servis' => '2026-10-05', 'created_at' => now(), 'updated_at' => now()],
            ['id_mesin' => 3, 'kode_mesin' => 'MD01', 'nama_mesin' => 'SpeedQueen Stack Dryer A', 'jenis' => 'Kering', 'kapasitas_kg' => 10.5, 'status' => 'Digunakan', 'terakhir_servis' => '2026-09-20', 'created_at' => now(), 'updated_at' => now()],
            ['id_mesin' => 4, 'kode_mesin' => 'MD02', 'nama_mesin' => 'SpeedQueen Stack Dryer B', 'jenis' => 'Kering', 'kapasitas_kg' => 10.5, 'status' => 'Tersedia', 'terakhir_servis' => '2026-09-25', 'created_at' => now(), 'updated_at' => now()],
            ['id_mesin' => 5, 'kode_mesin' => 'MC03', 'nama_mesin' => 'Maytag Heavy Duty Washer', 'jenis' => 'Cuci', 'kapasitas_kg' => 15.0, 'status' => 'Tersedia', 'terakhir_servis' => '2026-10-10', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 4. DATA STOK (Tabel 4.4.7 SDD)
        DB::table('stok')->insert([
            ['id_stok' => 1, 'kode_stok' => 'STK-001', 'nama_barang' => 'Deterjen Cair Konsentrat BlueWave', 'kategori' => 'Deterjen', 'jumlah' => 42.5, 'satuan' => 'Liter', 'batas_minimum' => 15.0, 'status' => 'Aman', 'created_at' => now(), 'updated_at' => now()],
            ['id_stok' => 2, 'kode_stok' => 'STK-002', 'nama_barang' => 'Pewangi & Pelembut Downy Floral Fresh', 'kategori' => 'Pewangi', 'jumlah' => 28.0, 'satuan' => 'Liter', 'batas_minimum' => 10.0, 'status' => 'Aman', 'created_at' => now(), 'updated_at' => now()],
            ['id_stok' => 3, 'kode_stok' => 'STK-003', 'nama_barang' => 'Cairan Pemutih Pakaian OxyBleach', 'kategori' => 'Pemutih', 'jumlah' => 4.5, 'satuan' => 'Liter', 'batas_minimum' => 5.0, 'status' => 'Menipis', 'created_at' => now(), 'updated_at' => now()],
            ['id_stok' => 4, 'kode_stok' => 'STK-004', 'nama_barang' => 'Plastik Kemasan Laundry Jinjing 5Kg', 'kategori' => 'Plastik', 'jumlah' => 210.0, 'satuan' => 'Pcs', 'batas_minimum' => 50.0, 'status' => 'Aman', 'created_at' => now(), 'updated_at' => now()],
            ['id_stok' => 5, 'kode_stok' => 'STK-005', 'nama_barang' => 'Koin Token Kuningan Mesin Self-Service', 'kategori' => 'Koin', 'jumlah' => 88.0, 'satuan' => 'Koin', 'batas_minimum' => 30.0, 'status' => 'Aman', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 5. DATA PESANAN CONTOH (Tabel 4.4.3 SDD)
        DB::table('pesanan')->insert([
            [
                'id_pesanan' => 1,
                'kode_pesanan' => 'P001',
                'id_user' => 1,
                'tanggal_pesanan' => '2026-10-28 09:30:00',
                'total_harga' => 40000.00,
                'status_pesanan' => 'Diproses',
                'status_laundry' => 'Pengeringan',
                'metode_pembayaran' => 'QRIS',
                'alamat_penjemputan' => 'Jl. Merdeka No. 10, Jakarta Selatan',
                'catatan' => 'Pakaian kerja harap dipisah, jangan dicampur luntur',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_pesanan' => 2,
                'kode_pesanan' => 'P002',
                'id_user' => 1,
                'tanggal_pesanan' => '2026-10-29 11:00:00',
                'total_harga' => 30000.00,
                'status_pesanan' => 'Diproses',
                'status_laundry' => 'Pencucian',
                'metode_pembayaran' => 'Koin',
                'alamat_penjemputan' => 'Gerai ArsyClean Cabang 1',
                'catatan' => 'Cuci mandiri koin 2 slot',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_pesanan' => 3,
                'kode_pesanan' => 'P003',
                'id_user' => 2,
                'tanggal_pesanan' => '2026-10-27 14:00:00',
                'total_harga' => 50000.00,
                'status_pesanan' => 'Selesai',
                'status_laundry' => 'Selesai',
                'metode_pembayaran' => 'Tunai',
                'alamat_penjemputan' => 'Jl. Sudirman No. 45, Jakarta Pusat',
                'catatan' => 'Setrika uap licin dan wangi lavender',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // 6. DETAIL PESANAN_LAYANAN
        DB::table('pesanan_layanan')->insert([
            ['id_detail' => 1, 'id_pesanan' => 1, 'id_layanan' => 1, 'jumlah' => 5.0, 'harga_satuan' => 8000.00, 'subtotal' => 40000.00, 'created_at' => now(), 'updated_at' => now()],
            ['id_detail' => 2, 'id_pesanan' => 2, 'id_layanan' => 2, 'jumlah' => 2.0, 'harga_satuan' => 15000.00, 'subtotal' => 30000.00, 'created_at' => now(), 'updated_at' => now()],
            ['id_detail' => 3, 'id_pesanan' => 3, 'id_layanan' => 5, 'jumlah' => 2.0, 'harga_satuan' => 25000.00, 'subtotal' => 50000.00, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 7. DATA PEMBAYARAN
        DB::table('pembayaran')->insert([
            ['id_pembayaran' => 1, 'kode_pembayaran' => 'BY001', 'id_pesanan' => 1, 'id_user' => 1, 'jumlah_bayar' => 40000.00, 'metode_pembayaran' => 'QRIS', 'status_pembayaran' => 'Lunas', 'tanggal_bayar' => '2026-10-28 09:35:00', 'verifikator' => 'Sulistiawati', 'created_at' => now(), 'updated_at' => now()],
            ['id_pembayaran' => 2, 'kode_pembayaran' => 'BY002', 'id_pesanan' => 2, 'id_user' => 1, 'jumlah_bayar' => 30000.00, 'metode_pembayaran' => 'Koin', 'status_pembayaran' => 'Lunas', 'tanggal_bayar' => '2026-10-29 11:02:00', 'verifikator' => 'Sistem Koin Otomatis', 'created_at' => now(), 'updated_at' => now()],
            ['id_pembayaran' => 3, 'kode_pembayaran' => 'BY003', 'id_pesanan' => 3, 'id_user' => 2, 'jumlah_bayar' => 50000.00, 'metode_pembayaran' => 'Tunai', 'status_pembayaran' => 'Lunas', 'tanggal_bayar' => '2026-10-27 14:05:00', 'verifikator' => 'Budi Santoso', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
