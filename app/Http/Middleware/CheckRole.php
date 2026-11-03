<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     * Enforce role-based access control (Pelanggan, Admin, Owner)
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')
            ->with('error', 'Silakan masuk terlebih dahulu.');
        }

        $user = Auth::user();

        // Cek apakah role user ada dalam daftar role yang diperbolehkan
        if (!in_array($user->role, $roles)) {
            // Redirect ke dashboard masing-masing jika salah masuk portal
            if ($user->role === 'pelanggan') {
                return redirect()->route('pelanggan.dashboard')->with('error', 'Akses ditolak: Anda tidak memiliki wewenang untuk halaman ini.');
            } elseif ($user->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak: Anda tidak memiliki wewenang untuk halaman ini.');
            } elseif ($user->role === 'owner') {
                return redirect()->route('owner.dashboard')->with('error', 'Akses ditolak: Anda tidak memiliki wewenang untuk halaman ini.');
            }

            abort(403, 'Akses Terlarang: Anda tidak memiliki izin untuk halaman ini.');
        }

        return $next($request);
    }
}
