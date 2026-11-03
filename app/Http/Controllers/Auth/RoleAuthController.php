<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RoleAuthController extends Controller
{
    // HALAMAN LOGIN UTAMA
    public function showPortal()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user()->role);
        }

        return view('auth.login');
    }

    // HALAMAN LOGIN PELANGGAN
    public function showLoginPelanggan()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user()->role);
        }

        return view('auth.login');
    }

    // HALAMAN LOGIN ADMIN
    public function showLoginAdmin()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user()->role);
        }

        return view('auth.login');
    }

    // HALAMAN LOGIN OWNER
    public function showLoginOwner()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user()->role);
        }

        return view('auth.login');
    }

    // PROSES LOGIN
    public function processLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'Email belum terdaftar.'
                ]);
        }

        if ($user->status !== 'aktif') {
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'Akun kamu tidak aktif.'
                ]);
        }

        if (!Hash::check($request->password, $user->password)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'password' => 'Password salah.'
                ]);
        }

        if (!in_array($user->role, ['pelanggan', 'admin', 'owner'])) {
            return back()
                ->withErrors([
                    'email' => 'Role akun tidak dikenali.'
                ]);
        }

        // LOGIN PENGGUNA
        Auth::login($user);

        // MEMPERBARUI SESSION
        $request->session()->regenerate();

        // ARAHKAN SESUAI ROLE
        return $this->redirectBasedOnRole(Auth::user()->role)
    ->with('login_berhasil', 'Selamat datang di ArsyClean!');
    }

    // HALAMAN REGISTER
    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user()->role);
        }

        return view('auth.register');
    }

    // PROSES REGISTER PELANGGAN
    public function processRegister(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:50',
            'email' => 'required|email|max:50|unique:user,email',
            'no_hp' => 'required|string|max:15',
            'alamat' => 'required|string',
            'password' => 'required|string|min:6',
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'no_hp.required' => 'Nomor HP wajib diisi.',
            'alamat.required' => 'Alamat wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
        ]);

        $nextId = (int) User::max('id_user') + 1;

        $kodeUser = 'U' . str_pad(
            $nextId,
            3,
            '0',
            STR_PAD_LEFT
        );

        $user = User::create([
            'kode_user' => $kodeUser,
            'nama_user' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'role' => 'pelanggan',
            'status' => 'aktif',
        ]);

        Auth::login($user);

        $request->session()->regenerate();

return redirect()->route('pelanggan.dashboard')
    ->with('login_berhasil', 'Pendaftaran berhasil. Selamat datang di ArsyClean!');
    }
    // LOGOUT
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
        ->with('success', 'Kamu berhasil logout.');
    }

    // PENGALIHAN DASHBOARD BERDASARKAN ROLE
    private function redirectBasedOnRole(string $role)
    {
        return match ($role) {
            'pelanggan' => redirect()->route('pelanggan.dashboard'),
            'admin' => redirect()->route('admin.dashboard'),
            'owner' => redirect()->route('owner.dashboard'),
            default => redirect()->route('login'),
        };
    }
}
