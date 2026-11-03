<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\PelangganApiController;
use App\Http\Controllers\Api\AdminApiController;
use App\Http\Controllers\Api\OwnerApiController;
use Illuminate\Http\Request;


// Register
Route::post('/register', [AuthApiController::class, 'register']);

// Login
Route::post('/login', [AuthApiController::class, 'login']);

// Route yang membutuhkan token login
Route::middleware('auth:sanctum')->group(function () {

    // Mendapatkan data user yang sedang login
    Route::get('/user', function (Request $request) {
        return response()->json([
            'success' => true,
            'user' => $request->user(),
        ]);
    });

    // ROUTE API PELANGGAN 
     Route::prefix('pelanggan')->group(function () {
        Route::get('/layanan', [PelangganApiController::class, 'getLayanan']);
        Route::post('/pesanan', [PelangganApiController::class, 'createPesanan']);
        Route::get('/pesanan/riwayat/{userId}', [PelangganApiController::class, 'getRiwayatPesanan']);
        Route::post('/pembayaran', [PelangganApiController::class, 'uploadPembayaran']);
        Route::post('/ulasan', [PelangganApiController::class, 'kirimUlasan']);
    });
    // --- API ROLE ADMIN ---
    Route::prefix('admin')->group(function () {
        Route::get('/pesanan', [AdminApiController::class, 'getSemuaPesanan']);
        Route::post('/pesanan/{id}/status', [AdminApiController::class, 'updateStatusPesanan']);
        Route::post('/pembayaran/{id}/verifikasi', [AdminApiController::class, 'verifikasiPembayaran']);
        Route::post('/pengiriman/{id}/status', [AdminApiController::class, 'updateStatusPengiriman']);
    });
    // --- API ROLE OWNER ---
    Route::prefix('owner')->group(function () {
        Route::get('/laporan', [OwnerApiController::class, 'getLaporanRingkasan']);
        Route::post('/layanan', [OwnerApiController::class, 'simpanLayanan']);
        Route::put('/layanan/{id}', [OwnerApiController::class, 'updateLayanan']);
    });

    // Logout
    Route::post('/logout', [AuthApiController::class, 'logout']);

});