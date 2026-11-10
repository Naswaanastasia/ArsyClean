<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RoleAuthController extends Controller
{
    // ATURAN FORMAT PASSWORD
    // 6 angka diikuti 2 huruf kapital
    private const PASSWORD_RULE = 'regex:/^[0-9]{6}[A-Z]{2}$/';

    private const PASSWORD_MESSAGE =
        'Password harus terdiri dari 6 angka diikuti 2 huruf kapital. Contoh: 123456NA.';

    // HALAMAN LOGIN UTAMA
    public function showPortal()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user()->role);
        }

        return view('auth.login');
    }

    // HALAMAN LOGIN PER ROLE
    public function showLoginPelanggan()
    {
        return $this->showPortal();
    }

    public function showLoginAdmin()
    {
        return $this->showPortal();
    }

    public function showLoginOwner()
    {
        return $this->showPortal();
    }

    // PROSES LOGIN
    public function processLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => [
                'required',
                'string',
                self::PASSWORD_RULE,
            ],
        ], [
            'email.required' => 'Silakan masukkan alamat email.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Silakan masukkan kata sandi Anda.',
            'password.regex' => self::PASSWORD_MESSAGE,
        ]);

        // CARI AKUN BERDASARKAN EMAIL
        $user = User::where(
            'email',
            strtolower(trim($request->email))
        )->first();

        if (!$user) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'Email belum terdaftar di sistem ArsyClean.',
                ]);
        }

        // CEK STATUS AKUN
        if ($user->status !== 'aktif') {
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'Akun Anda dinonaktifkan. Silakan hubungi pengelola ArsyClean.',
                ]);
        }

        // CEK PASSWORD
        if (!Hash::check($request->password, $user->password)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'password' => 'Kata sandi yang Anda masukkan salah.',
                ]);
        }

        // CEK ROLE
        if (!in_array($user->role, ['pelanggan', 'admin', 'owner'])) {
            return back()
                ->withErrors([
                    'email' => 'Peran akun tidak dikenali.',
                ]);
        }

        // LOGIN
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return $this->redirectBasedOnRole($user->role)
            ->with('login_berhasil', "Selamat datang kembali, {$user->nama}!");
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
        // RAPINKAN INPUT SEBELUM VALIDASI
        $request->merge([
            'nama' => trim($request->nama ?? ''),
            'email' => strtolower(trim($request->email ?? '')),
            'no_hp' => trim($request->no_hp ?? ''),
            'alamat' => trim($request->alamat ?? ''),
        ]);

        // VALIDASI DATA REGISTER
        $request->validate([
            'nama' => [
                'required',
                'string',
                'min:3',
                'max:100',
                'regex:/^[\pL\s.\'-]+$/u',
            ],

            'email' => 'required|email|max:100|unique:users,email',

            'no_hp' => [
                'required',
                'regex:/^[0-9]{10,15}$/',
            ],

            'alamat' => 'required|string|min:10|max:500',

            'password' => [
                'required',
                'string',
                self::PASSWORD_RULE,
            ],
        ], [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'nama.min' => 'Nama minimal 3 karakter.',
            'nama.max' => 'Nama maksimal 100 karakter.',
            'nama.regex' => 'Nama hanya boleh berisi huruf, spasi, titik, apostrof, dan tanda hubung.',

            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar. Silakan gunakan email lain.',

            'no_hp.required' => 'Nomor WhatsApp wajib diisi.',
            'no_hp.regex' => 'Nomor WhatsApp harus berupa angka saja, 10–15 digit.',

            'alamat.required' => 'Alamat penjemputan wajib diisi.',
            'alamat.min' => 'Alamat minimal 10 karakter.',
            'alamat.max' => 'Alamat maksimal 500 karakter.',

            'password.required' => 'Kata sandi wajib diisi.',
            'password.regex' => self::PASSWORD_MESSAGE,
        ]);

        // BUAT KODE USER UNIK
        $nextId = (int) (User::max('id_user') ?? 0) + 1;
        $kodeUser = 'U' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        // SIMPAN AKUN PELANGGAN
        $user = User::create([
            'kode_user' => $kodeUser,
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'role' => 'pelanggan',
            'status' => 'aktif',
        ]);

        // LOGIN OTOMATIS SETELAH REGISTER
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('pelanggan.dashboard')
            ->with('login_berhasil', 'Pendaftaran berhasil. Selamat datang di ArsyClean Laundry!');
    }

    // HALAMAN LIHAT & EDIT PROFIL
    public function showProfile()
    {
        $user = Auth::user();

        return view('profile', compact('user'));
    }

    // UPDATE PROFIL
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nama' => 'required|string|max:100',

            'email' => 'required|email|max:100|unique:users,email,' . $user->id_user . ',id_user',

            'no_hp' => 'required|numeric|digits_between:10,15',

            'alamat' => 'required|string|max:500',

            'password' => [
                'nullable',
                'string',
                self::PASSWORD_RULE,
            ],
        ], [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Alamat email sudah digunakan oleh pengguna lain.',

            'no_hp.required' => 'Nomor WhatsApp wajib diisi.',
            'no_hp.numeric' => 'Nomor WhatsApp harus berupa angka numerik.',
            'no_hp.digits_between' => 'Nomor WhatsApp harus antara 10 hingga 15 digit angka.',

            'alamat.required' => 'Alamat lengkap wajib diisi.',

            'password.regex' => self::PASSWORD_MESSAGE,
        ]);

        $user->nama = trim($request->nama);
        $user->email = strtolower(trim($request->email));
        $user->no_hp = trim($request->no_hp);
        $user->alamat = trim($request->alamat);

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'Profil akun Anda berhasil diperbarui.');
    }

    // LOGOUT
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Anda telah berhasil keluar dari akun.');
    }

    // PENGALIHAN DASHBOARD BERDASARKAN ROLE
    public function redirectBasedOnRole(string $role)
    {
        return match (strtolower(trim($role))) {
            'pelanggan' => redirect()->route('pelanggan.dashboard'),
            'admin' => redirect()->route('admin.dashboard'),
            'owner' => redirect()->route('owner.dashboard'),
            default => redirect()->route('login'),
        };
    }
}
