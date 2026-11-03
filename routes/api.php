<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\PelangganApiController; // <-- TAMBAHKAN BARIS INI
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

    // Logout
    Route::post('/logout', [AuthApiController::class, 'logout']);

});