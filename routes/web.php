<?php

use App\Http\Controllers\MidtransWebhookController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RoleAuthController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OwnerController;
use App\Http\Middleware\CheckRole;

Route::post('/midtrans/notification', [
    MidtransWebhookController::class,
    'handle'
])->name('midtrans.notification');
// ==========================================
// 1. LANDING PAGE
// ==========================================
Route::get('/', function () {
    return view('landing');
})->name('home');


// ==========================================
// 2. KHUSUS GUEST (BELUM LOGIN)
// ==========================================
Route::middleware('guest')->group(function () {

    // Form Login Utama
    Route::get('/login', [RoleAuthController::class, 'showPortal'])
        ->name('login');

    // Proses Login
    Route::post('/login', [RoleAuthController::class, 'processLogin'])
        ->name('login.process');

    // Halaman Login Per Role
    Route::get('/login/pelanggan', [RoleAuthController::class, 'showLoginPelanggan'])
        ->name('login.pelanggan');

    Route::get('/login/admin', [RoleAuthController::class, 'showLoginAdmin'])
        ->name('login.admin');

    Route::get('/login/owner', [RoleAuthController::class, 'showLoginOwner'])
        ->name('login.owner');

    // Registrasi Pelanggan
    Route::get('/register', [RoleAuthController::class, 'showRegister'])
        ->name('register');

    Route::post('/register', [RoleAuthController::class, 'processRegister'])
        ->name('register.process');
});


// ==========================================
// 3. LOGOUT (KHUSUS PENGGUNA YANG SUDAH LOGIN)
// ==========================================
Route::middleware('auth')->group(function () {

    Route::post('/logout', [RoleAuthController::class, 'logout'])
        ->name('logout');

    Route::get('/logout', [RoleAuthController::class, 'logout']);
});


// ==========================================
// 4. DASHBOARD DAN FITUR PELANGGAN
// ==========================================
Route::middleware(['auth', CheckRole::class . ':pelanggan'])
    ->prefix('pelanggan')
    ->name('pelanggan.')
    ->group(function () {

        Route::get('/dashboard', [PelangganController::class, 'dashboard'])
            ->name('dashboard');

        Route::post('/pesanan', [PelangganController::class, 'storePesanan'])
            ->name('pesanan.store');

        // BARU: cek status pembayaran otomatis
        Route::get('/pesanan/{kode}/status', [PelangganController::class, 'statusPesanan'])
            ->name('pesanan.status');

        Route::post('/ulasan/{idPesanan}', [PelangganController::class, 'storeUlasan'])
            ->name('ulasan.store');
    });
// ==========================================
// 5. DASHBOARD DAN FITUR ADMIN
// ==========================================
Route::middleware(['auth', CheckRole::class . ':admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard Admin
        Route::get('/dashboard', [AdminController::class, 'dashboard'])
            ->name('dashboard');


        // ----------------------------------
        // A. PENGELOLAAN PESANAN
        // ----------------------------------

        // Menerima pesanan
        Route::post('/pesanan/{idPesanan}/terima', [AdminController::class, 'konfirmasiPesanan'])
            ->name('pesanan.konfirmasi');

        // Menolak pesanan
        Route::post('/pesanan/{idPesanan}/tolak', [AdminController::class, 'tolakPesanan'])
            ->name('pesanan.tolak');

        // Memperbarui status laundry
        Route::post('/pesanan/{idPesanan}/status', [AdminController::class, 'updateStatusLaundry'])
            ->name('pesanan.status');


        // ----------------------------------
        // B. PEMBAYARAN
        // ----------------------------------
        Route::post('/pembayaran/{idPembayaran}/verifikasi', [AdminController::class, 'verifikasiPembayaran'])
            ->name('pembayaran.verifikasi');


        // ----------------------------------
        // C. PENGELOLAAN STOK
        // ----------------------------------
        Route::post('/stok/{idStok}', [AdminController::class, 'updateStok'])
            ->name('stok.update');


        // ----------------------------------
        // D. PENGIRIMAN DAN KURIR
        // ----------------------------------

        // Menambahkan penugasan kurir
        Route::post('/pengiriman', [AdminController::class, 'simpanPengiriman'])
            ->name('pengiriman.store');

        // Memperbarui status pengiriman
        Route::post('/pengiriman/{idPengiriman}/status', [AdminController::class, 'updateStatusPengiriman'])
            ->name('pengiriman.status');
    });



// ==========================================
// 6. DASHBOARD DAN FITUR OWNER
// ==========================================
Route::middleware(['auth', CheckRole::class . ':owner'])
    ->prefix('owner')
    ->name('owner.')
    ->group(function () {

        // Dashboard Owner
        Route::get('/dashboard', [OwnerController::class, 'dashboard'])
            ->name('dashboard');

        // Cetak laporan
        Route::get('/cetak-laporan', [OwnerController::class, 'cetakLaporanPdf'])
            ->name('cetak.laporan');

        // Mengatur tarif dan status layanan
        Route::post('/layanan/{idLayanan}', [OwnerController::class, 'updateLayanan'])
            ->name('layanan.update');

        // Menambahkan akun Admin
        Route::post('/admin', [OwnerController::class, 'storeAdmin'])
            ->name('admin.store');

        // Menghapus akun Admin
        Route::delete('/admin/{idAdmin}', [OwnerController::class, 'destroyAdmin'])
            ->name('admin.destroy');
    });
