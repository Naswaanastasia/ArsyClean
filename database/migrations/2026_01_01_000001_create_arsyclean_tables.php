<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Sesuai Dokumen SRS & SDD Bab IV: Skema Database Normalisasi 2NF & Physical Design
     */
    public function up(): void
    {
        // 1. TABEL USERS (Pelanggan, Admin, Owner)
        Schema::create('users', function (Blueprint $table) {
            $table->id('id_user');
            $table->string('kode_user', 20)->unique();
            $table->string('nama', 100);
            $table->string('email', 100)->unique();
            $table->string('password');
            $table->string('no_hp', 20);
            $table->text('alamat');
            $table->enum('role', ['pelanggan', 'admin', 'owner'])->default('pelanggan');
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->string('foto')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        // 2. TABEL LAYANAN
        Schema::create('layanan', function (Blueprint $table) {
            $table->id('id_layanan');
            $table->string('kode_layanan', 20)->unique();
            $table->string('nama_layanan', 100);
            $table->enum('jenis_layanan', ['Reguler', 'Koin', 'Antar Jemput', 'Dry Clean', 'Express']);
            $table->decimal('harga', 12, 2);
            $table->string('satuan', 20)->default('Kg'); // 'Kg', 'Koin', 'Pcs'
            $table->string('durasi', 50)->default('2 Hari');
            $table->text('deskripsi')->nullable();
            $table->enum('status', ['tersedia', 'tidak_tersedia'])->default('tersedia');
            $table->string('icon')->nullable();
            $table->timestamps();
        });

        // 3. TABEL PESANAN
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id('id_pesanan');
            $table->string('kode_pesanan', 30)->unique();
            $table->unsignedBigInteger('id_user');
            $table->dateTime('tanggal_pesanan');
            $table->decimal('total_harga', 12, 2);
            $table->enum('status_pesanan', [
                'Menunggu Pembayaran',
                'Menunggu Verifikasi',
                'Diproses',
                'Selesai',
                'Dibatalkan'
            ])->default('Menunggu Pembayaran');
            $table->enum('status_laundry', [
                'Menunggu Antrean',
                'Pencucian',
                'Pengeringan',
                'Setrika',
                'Siap Diambil',
                'Dalam Pengiriman',
                'Selesai'
            ])->default('Menunggu Antrean');
            $table->enum('metode_pembayaran', ['Tunai', 'QRIS', 'Transfer Bank', 'Koin'])->default('Tunai');
            $table->text('alamat_penjemputan')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
        });

        // 4. TABEL PESANAN_LAYANAN (Relasi Banyak-ke-Banyak)
        Schema::create('pesanan_layanan', function (Blueprint $table) {
            $table->id('id_detail');
            $table->unsignedBigInteger('id_pesanan');
            $table->unsignedBigInteger('id_layanan');
            $table->decimal('jumlah', 8, 2);
            $table->decimal('harga_satuan', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();

            $table->foreign('id_pesanan')->references('id_pesanan')->on('pesanan')->onDelete('cascade');
            $table->foreign('id_layanan')->references('id_layanan')->on('layanan')->onDelete('cascade');
        });

        // 5. TABEL PEMBAYARAN
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id('id_pembayaran');
            $table->string('kode_pembayaran', 30)->unique();
            $table->unsignedBigInteger('id_pesanan');
            $table->unsignedBigInteger('id_user');
            $table->decimal('jumlah_bayar', 12, 2);
            $table->enum('metode_pembayaran', ['Tunai', 'QRIS', 'Transfer Bank', 'Koin']);
            $table->enum('status_pembayaran', ['Pending', 'Lunas', 'Gagal', 'Dibatalkan'])->default('Pending');
            $table->dateTime('tanggal_bayar');
            $table->string('bukti_transfer')->nullable();
            $table->string('verifikator', 100)->nullable();
            $table->timestamps();

            $table->foreign('id_pesanan')->references('id_pesanan')->on('pesanan')->onDelete('cascade');
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
        });

        // 6. TABEL MESIN
        Schema::create('mesin', function (Blueprint $table) {
            $table->id('id_mesin');
            $table->string('kode_mesin', 20)->unique();
            $table->string('nama_mesin', 100);
            $table->enum('jenis', ['Cuci', 'Kering']);
            $table->decimal('kapasitas_kg', 5, 2);
            $table->enum('status', ['Tersedia', 'Digunakan', 'Rusak', 'Perawatan'])->default('Tersedia');
            $table->date('terakhir_servis')->nullable();
            $table->timestamps();
        });

        // 7. TABEL PENGGUNAAN_MESIN
        Schema::create('penggunaan_mesin', function (Blueprint $table) {
            $table->id('id_penggunaan');
            $table->unsignedBigInteger('id_mesin');
            $table->unsignedBigInteger('id_pesanan')->nullable();
            $table->dateTime('waktu_mulai');
            $table->dateTime('waktu_selesai')->nullable();
            $table->enum('status', ['Berjalan', 'Selesai', 'Terhenti'])->default('Berjalan');
            $table->integer('durasi_menit')->default(30);
            $table->timestamps();

            $table->foreign('id_mesin')->references('id_mesin')->on('mesin')->onDelete('cascade');
            $table->foreign('id_pesanan')->references('id_pesanan')->on('pesanan')->onDelete('set null');
        });

        // 8. TABEL STOK (Bahan Baku & Koin)
        Schema::create('stok', function (Blueprint $table) {
            $table->id('id_stok');
            $table->string('kode_stok', 20)->unique();
            $table->string('nama_barang', 100);
            $table->enum('kategori', ['Deterjen', 'Pewangi', 'Pemutih', 'Plastik', 'Koin']);
            $table->decimal('jumlah', 10, 2);
            $table->string('satuan', 20); // 'Liter', 'Koin', 'Pcs'
            $table->decimal('batas_minimum', 10, 2);
            $table->enum('status', ['Aman', 'Menipis', 'Habis'])->default('Aman');
            $table->timestamps();
        });

        // 9. TABEL PENGIRIMAN
        Schema::create('pengiriman', function (Blueprint $table) {
            $table->id('id_pengiriman');
            $table->string('kode_pengiriman', 30)->unique();
            $table->unsignedBigInteger('id_pesanan');
            $table->string('nama_kurir', 100);
            $table->string('no_hp_kurir', 20);
            $table->string('kendaraan', 50)->nullable();
            $table->string('no_resi', 50)->nullable();
            $table->text('alamat_tujuan');
            $table->enum('status_pengiriman', ['Dijadwalkan', 'Dalam Perjalanan', 'Terkirim', 'Gagal'])->default('Dijadwalkan');
            $table->dateTime('tanggal_kirim');
            $table->timestamps();

            $table->foreign('id_pesanan')->references('id_pesanan')->on('pesanan')->onDelete('cascade');
        });

        // 10. TABEL ULASAN
        Schema::create('ulasan', function (Blueprint $table) {
            $table->id('id_ulasan');
            $table->string('kode_ulasan', 30)->unique();
            $table->unsignedBigInteger('id_pesanan');
            $table->unsignedBigInteger('id_user');
            $table->integer('rating'); // 1 s/d 5
            $table->text('komentar')->nullable();
            $table->dateTime('tanggal_ulasan');
            $table->timestamps();

            $table->foreign('id_pesanan')->references('id_pesanan')->on('pesanan')->onDelete('cascade');
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ulasan');
        Schema::dropIfExists('pengiriman');
        Schema::dropIfExists('stok');
        Schema::dropIfExists('penggunaan_mesin');
        Schema::dropIfExists('mesin');
        Schema::dropIfExists('pembayaran');
        Schema::dropIfExists('pesanan_layanan');
        Schema::dropIfExists('pesanan');
        Schema::dropIfExists('layanan');
        Schema::dropIfExists('users');
    }
};
