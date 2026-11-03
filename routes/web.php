<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RoleAuthController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OwnerController;
use App\Http\Middleware\CheckRole;

// ==========================================
// 1. LOGIN DAN REGISTRASI
// ==========================================

// Halaman utama Landing Page.
Route::get('/', function () {
    return view('landing');
})->name('home');

// Halaman login.
Route::get('/login', [RoleAuthController::class, 'showPortal'])
    ->name('login');

// Proses login tanpa memilih role.
Route::post('/login', [RoleAuthController::class, 'processLogin'])
    ->name('login.process');

// Redirect halaman login lama ke halaman utama.
Route::get('/login/pelanggan', [RoleAuthController::class, 'showLoginPelanggan'])
    ->name('login.pelanggan');

Route::get('/login/admin', [RoleAuthController::class, 'showLoginAdmin'])
    ->name('login.admin');

Route::get('/login/owner', [RoleAuthController::class, 'showLoginOwner'])
    ->name('login.owner');

// Registrasi pelanggan.
Route::get('/register', [RoleAuthController::class, 'showRegister'])
    ->name('register');

Route::post('/register', [RoleAuthController::class, 'processRegister'])
    ->name('register.process');

// Logout.
Route::post('/logout', [RoleAuthController::class, 'logout'])
    ->name('logout');

Route::get('/logout', [RoleAuthController::class, 'logout']);

// ==========================================
// 2. DASHBOARD DAN FITUR PELANGGAN
// ==========================================

Route::middleware(['auth', CheckRole::class . ':pelanggan'])
    ->prefix('pelanggan')
    ->name('pelanggan.')
    ->group(function () {

        Route::get('/dashboard', [PelangganController::class, 'dashboard'])
            ->name('dashboard');

        Route::post('/pesanan', [PelangganController::class, 'storePesanan'])
            ->name('pesanan.store');

        Route::post('/ulasan/{idPesanan}', [PelangganController::class, 'storeUlasan'])
            ->name('ulasan.store');
    });

// ==========================================
// 3. DASHBOARD DAN FITUR ADMIN
// ==========================================

Route::middleware(['auth', CheckRole::class . ':admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminController::class, 'dashboard'])
            ->name('dashboard');

        Route::post('/pesanan/{idPesanan}/status', [AdminController::class, 'updateStatusLaundry'])
            ->name('pesanan.status');

        Route::post('/pembayaran/{idPembayaran}/verifikasi', [AdminController::class, 'verifikasiPembayaran'])
            ->name('pembayaran.verifikasi');

        Route::post('/stok/{idStok}', [AdminController::class, 'updateStok'])
            ->name('stok.update');

        Route::post('/pengiriman', [AdminController::class, 'simpanPengiriman'])
            ->name('pengiriman.store');
    });

// ==========================================
// 4. DASHBOARD DAN FITUR OWNER
// ==========================================

Route::middleware(['auth', CheckRole::class . ':owner'])
    ->prefix('owner')
    ->name('owner.')
    ->group(function () {

        // 1 & 2. Dashboard, Ringkasan, & Memantau Aktivitas Layanan
        Route::get('/dashboard', [OwnerController::class, 'dashboard'])
            ->name('dashboard');

        // 5. Mengatur Tarif & Status Layanan
        Route::post('/layanan/{idLayanan}', [OwnerController::class, 'updateLayanan'])
            ->name('layanan.update');

        // 4. Memeriksa & Cetak Laporan PDF
        Route::get('/cetak-laporan', [OwnerController::class, 'cetakLaporanPdf'])
            ->name('cetak.laporan');

        // 6. Mengelola Akun Admin (Tambah & Hapus/Nonaktifkan)
        Route::post('/admin', [OwnerController::class, 'storeAdmin'])
            ->name('admin.store');

        Route::delete('/admin/{idAdmin}', [OwnerController::class, 'destroyAdmin'])
            ->name('admin.destroy');
    });