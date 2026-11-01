<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthApiController;
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

    // Logout
    Route::post('/logout', [AuthApiController::class, 'logout']);

});