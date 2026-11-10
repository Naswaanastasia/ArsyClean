<?php

use App\Http\Controllers\MidtransWebhookController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RoleAuthController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OwnerController;
use App\Http\Middleware\CheckRole;

// ==========================================
// 1. MIDTRANS WEBHOOK NOTIFICATION
// ==========================================
Route::post('/midtrans/notification', [
    MidtransWebhookController::class,
    'handle'
])->name('midtrans.notification');

// ==========================================
// 2. LANDING PAGE / HOMEPAGE
// ==========================================
Route::get('/', function () {
    $layanans = \App\Models\Layanan::where('status', 'tersedia')->get();
    $ulasans = \App\Models\Ulasan::with('pesanan.user')->latest('id_ulasan')->take(6)->get();
    return view('landing', compact('layanans', 'ulasans'));
})->name('home');

// ==========================================
// 3. AUTENTIKASI (KHUSUS GUEST / BELUM LOGIN)
// ==========================================
Route::middleware('guest')->group(function () {
    // Form Login Utama
    Route::get('/login', [RoleAuthController::class, 'showPortal'])
        ->name('login');

    // Proses Login
    Route::post('/login', [RoleAuthController::class, 'processLogin'])
        ->name('login.process');

    // Shortcut Halaman Login Per Role
    Route::get('/login/pelanggan', [RoleAuthController::class, 'showLoginPelanggan'])
        ->name('login.pelanggan');

    Route::get('/login/admin', [RoleAuthController::class, 'showLoginAdmin'])
        ->name('login.admin');

    Route::get('/login/owner', [RoleAuthController::class, 'showLoginOwner'])
        ->name('login.owner');

    // Registrasi Akun Pelanggan
    Route::get('/register', [RoleAuthController::class, 'showRegister'])
        ->name('register');

    Route::post('/register', [RoleAuthController::class, 'processRegister'])
        ->name('register.process');
});

// ==========================================
// 4. USER YANG SUDAH LOGIN (AUTH SEMUA ROLE)
// ==========================================
Route::middleware('auth')->group(function () {
    // Logout Akun
    Route::match(['get', 'post'], '/logout', [RoleAuthController::class, 'logout'])
        ->name('logout');

    // Lihat & Edit Profil Pengguna (Bisa diklik dari navbar/sidebar)
    Route::get('/profile', [RoleAuthController::class, 'showProfile'])
        ->name('profile');

    Route::post('/profile', [RoleAuthController::class, 'updateProfile'])
        ->name('profile.update');
});

// ==========================================
// 5. ROLE PELANGGAN
// ==========================================
Route::middleware(['auth', CheckRole::class . ':pelanggan'])
    ->prefix('pelanggan')
    ->name('pelanggan.')
    ->group(function () {
        Route::get('/dashboard', [PelangganController::class, 'dashboard'])
            ->name('dashboard');

        // Katalog Layanan (Halaman Khusus)
        Route::get('/layanan', [PelangganController::class, 'layanan'])
            ->name('layanan');

        // Riwayat Pesanan (Halaman Khusus)
        Route::get('/riwayat', [PelangganController::class, 'riwayat'])
            ->name('riwayat');

        // Pemesanan Laundry
        Route::post('/pesanan', [PelangganController::class, 'storePesanan'])
            ->name('pesanan.store');

        // Cek status pesanan realtime berdasarkan kode pesanan
        Route::get('/pesanan/{kode}/status', [PelangganController::class, 'statusPesanan'])
            ->name('pesanan.status');

        // Modal detail pesanan interaktif & aksi
        Route::get('/pesanan/{id}/detail', [PelangganController::class, 'detailPesanan'])
            ->name('pesanan.detail');

        // Cetak struk/nota pesanan PDF kapan saja
        Route::get('/pesanan/{id}/nota', [PelangganController::class, 'cetakNotaPdf'])
            ->name('pesanan.nota');

        // Upload bukti pembayaran transfer manual / QR manual
        Route::post('/pesanan/{id}/upload-bukti', [PelangganController::class, 'uploadBuktiPembayaran'])
            ->name('pembayaran.upload');

        // SVG QRIS Manual ArsyClean jika Midtrans belum acc
        Route::get('/qris-manual/{kode}', [PelangganController::class, 'generateQrisManual'])
            ->name('qris.manual');

        // Kirim Ulasan Layanan
        Route::post('/ulasan/{idPesanan}', [PelangganController::class, 'storeUlasan'])
            ->name('ulasan.store');
    });

// ==========================================
// 6. ROLE ADMIN
// ==========================================
Route::middleware(['auth', CheckRole::class . ':admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])
            ->name('dashboard');

        // Halaman Khusus Menu Sidebar Admin:
        Route::get('/pesanan-masuk', [AdminController::class, 'pesananMasuk'])
            ->name('pesanan_masuk');

        Route::get('/pengerjaan', [AdminController::class, 'pengerjaan'])
            ->name('pengerjaan');

        Route::get('/pembayaran', [AdminController::class, 'pembayaran'])
            ->name('pembayaran');

        Route::get('/pengiriman', [AdminController::class, 'pengiriman'])
            ->name('pengiriman');

        Route::get('/stok', [AdminController::class, 'stok'])
            ->name('stok');

        // Pengelolaan Pesanan Aksi
        Route::post('/pesanan/{idPesanan}/terima', [AdminController::class, 'konfirmasiPesanan'])
            ->name('pesanan.konfirmasi');

        Route::post('/pesanan/{idPesanan}/tolak', [AdminController::class, 'tolakPesanan'])
            ->name('pesanan.tolak');

        Route::post('/pesanan/{idPesanan}/status', [AdminController::class, 'updateStatusLaundry'])
            ->name('pesanan.status');

        // Verifikasi Pembayaran Pelanggan
        Route::post('/pembayaran/{idPembayaran}/verifikasi', [AdminController::class, 'verifikasiPembayaran'])
            ->name('pembayaran.verifikasi');

        // Manajemen Stok Bahan Baku
        Route::post('/stok/{idStok}', [AdminController::class, 'updateStok'])
            ->name('stok.update');

        // Pengiriman Kurir
        Route::post('/pengiriman', [AdminController::class, 'simpanPengiriman'])
            ->name('pengiriman.store');

        Route::post('/pengiriman/{idPengiriman}/status', [AdminController::class, 'updateStatusPengiriman'])
            ->name('pengiriman.status');
    });

// ==========================================
// 7. ROLE OWNER
// ==========================================
Route::middleware(['auth', CheckRole::class . ':owner'])
    ->prefix('owner')
    ->name('owner.')
    ->group(function () {
        Route::get('/dashboard', [OwnerController::class, 'dashboard'])
            ->name('dashboard');

        Route::get('/laporan', [OwnerController::class, 'laporan'])
            ->name('laporan');

        Route::get('/admin', [OwnerController::class, 'adminIndex'])
            ->name('admin.index');

        Route::post('/admin', [OwnerController::class, 'storeAdmin'])
            ->name('admin.store');

        Route::delete('/admin/{idAdmin}', [OwnerController::class, 'destroyAdmin'])
            ->name('admin.destroy');

        // KATALOG LAYANAN OWNER
        Route::get('/layanan', [OwnerController::class, 'layanan'])
            ->name('layanan');

        // TAMBAHKAN BARIS INI (ROUTE UNTUK TAMBAH LAYANAN BARU)
        Route::post('/layanan', [OwnerController::class, 'storeLayanan'])
            ->name('layanan.store');

        Route::post('/layanan/{idLayanan}', [OwnerController::class, 'updateLayanan'])
            ->name('layanan.update');

        Route::get('/cetak-laporan', [OwnerController::class, 'cetakLaporanPdf'])
            ->name('cetak.laporan');
    });