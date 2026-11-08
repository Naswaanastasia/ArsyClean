<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthApiController extends Controller
{
    /**
     * API Registrasi Akun Pelanggan
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'nama'                  => 'required|string|max:100',
            'email'                 => 'required|email|max:100|unique:users,email',
            'password'              => 'required|string|min:6|confirmed',
            'no_hp'                 => 'required|numeric|digits_between:10,15',
            'alamat'                => 'required|string|max:500',
        ], [
            'nama.required'         => 'Nama lengkap wajib diisi.',
            'email.required'        => 'Alamat email wajib diisi.',
            'email.email'           => 'Format email tidak valid.',
            'email.unique'          => 'Alamat email ini sudah terdaftar.',
            'password.required'     => 'Kata sandi wajib diisi.',
            'password.min'          => 'Kata sandi minimal 6 karakter.',
            'password.confirmed'    => 'Konfirmasi kata sandi tidak cocok.',
            'no_hp.required'        => 'Nomor WhatsApp wajib diisi.',
            'no_hp.numeric'         => 'Nomor WhatsApp harus berupa angka numerik.',
            'no_hp.digits_between'  => 'Nomor WhatsApp harus antara 10 hingga 15 digit angka.',
            'alamat.required'       => 'Alamat penjemputan wajib diisi.',
        ]);

        $nextId = (int) (User::max('id_user') ?? 0) + 1;
        $kodeUser = 'U' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        $user = User::create([
            'kode_user' => $kodeUser,
            'nama'      => trim($validated['nama']),
            'email'     => strtolower(trim($validated['email'])),
            'password'  => Hash::make($validated['password']),
            'no_hp'     => trim($validated['no_hp']),
            'alamat'    => trim($validated['alamat']),
            'role'      => 'pelanggan',
            'status'    => 'aktif',
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Registrasi akun pelanggan berhasil.',
            'user'    => $user,
            'token'   => $token,
        ], 201);
    }

    /**
     * API Login Pengguna
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string|min:6',
        ], [
            'email.required'    => 'Alamat email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min'      => 'Kata sandi minimal 6 karakter.',
        ]);

        $user = User::where('email', strtolower(trim($validated['email'])))->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Alamat email atau kata sandi tidak valid.',
            ], 401);
        }

        if ($user->status !== 'aktif') {
            return response()->json([
                'success' => false,
                'message' => 'Akun dinonaktifkan. Silakan hubungi pengelola ArsyClean.',
            ], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil. Selamat datang di ArsyClean.',
            'user'    => $user,
            'token'   => $token,
        ], 200);
    }

    /**
     * API Logout Pengguna
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Anda telah berhasil logout dari API session.',
        ], 200);
    }
}
