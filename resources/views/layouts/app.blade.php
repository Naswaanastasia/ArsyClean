<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ArsyClean Laundry System')</title>

    <!-- Google Fonts & Tailwind CSS CDN -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        :root {
            --navy-dark: #091733;
            --navy-base: #0f2a5c;
            --navy-soft: #173b7d;
            --primary-blue: #2563eb;
            --sidebar-width: 275px;
        }
        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            background-color: #f8fafc;
            color: #0f172a;
            font-size: 15px;
        }
       .sidebar-container {
    width: var(--sidebar-width);
    min-width: var(--sidebar-width);
    background: linear-gradient(180deg, #091733 0%, #0f2a5c 100%);
    transition: width 0.25s ease, min-width 0.25s ease,
                transform 0.25s ease, padding 0.25s ease;
}

/* Sidebar saat ditutup di tampilan desktop */
.sidebar-container.sidebar-collapsed {
    width: 76px;
    min-width: 76px;
    padding-left: 12px;
    padding-right: 12px;
    overflow: hidden;
}
        .nav-item {
            transition: all 0.2s ease-in-out;
        }
        .nav-item:hover {
            background-color: rgba(255, 255, 255, 0.12);
            color: #ffffff;
            transform: translateX(4px);
        }
        .nav-item.active {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: #ffffff;
            font-weight: 800;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.35);
        }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen flex flex-col antialiased">

@php
    $user = Auth::user();
    $role = $user->role ?? 'admin';
    $namaSingkat = explode(' ', $user->nama ?? 'User')[0];
    
    // Hitung badge notifikasi untuk Admin
    $badgeMasuk = 0;
    $badgePendingBayar = 0;
    if ($role === 'admin') {
        $badgeMasuk = \App\Models\Pesanan::whereIn('status_pesanan', ['Menunggu Pembayaran', 'Menunggu Verifikasi', 'Menunggu Konfirmasi', 'Pending', 'Baru'])->count();
        $badgePendingBayar = \App\Models\Pembayaran::where('status_pembayaran', 'Pending')->count();
    }
@endphp

    <div class="flex-1 flex overflow-hidden">
        <!-- ================= SIDEBAR UTAMA ================= -->
        <aside id="mainSidebar" class="sidebar-container fixed lg:static inset-y-0 left-0 z-50 flex flex-col justify-between text-white p-5 -translate-x-full lg:translate-x-0 shadow-2xl shrink-0">
            <div>
                <!-- Brand Header with Hamburger Toggle Inside Sidebar -->
                <div class="flex items-center justify-between pb-5 mb-5 border-b border-white/10">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-sky-400 to-blue-600 flex items-center justify-center font-black text-xl text-white shadow-md shadow-sky-500/25">
                            A
                        </div>
                        <div>
                            <span class="font-extrabold text-lg tracking-tight block text-white">ArsyClean</span>
                            <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider
                                {{ $role === 'owner' ? 'bg-amber-400/20 text-amber-300 border border-amber-400/30' : 'bg-sky-400/20 text-sky-200 border border-sky-400/30' }}">
                                {{ $role === 'owner' ? 'Owner Eksekutif' : 'Petugas Admin' }}
                            </span>
                        </div>
                    </div>

                    <!-- Tombol Garis 3 di Sidebar untuk Menutup / Collapse -->
                    <button type="button" onclick="toggleSidebarDesktopMobile()"
                            class="p-2 rounded-xl bg-white/10 hover:bg-white/20 text-white transition-all flex items-center justify-center"
                            title="Tutup / Collapse Sidebar">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>

                <!-- Navigation Links with SVG Icons (NO EMOJIS) -->
                <nav class="space-y-1.5">
                    @if($role === 'admin')
                        <!-- 1. Dashboard Admin -->
                        <a href="{{ route('admin.dashboard') }}"
                           class="nav-item flex items-center justify-between px-3.5 py-3 rounded-2xl text-sm font-bold text-slate-200 {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                <span>Dashboard</span>
                            </div>
                        </a>

                        <!-- 2. Pesanan Masuk (Khusus Konfirmasi) -->
                        <a href="{{ route('admin.pesanan_masuk') }}"
                           class="nav-item flex items-center justify-between px-3.5 py-3 rounded-2xl text-sm font-bold text-slate-200 {{ request()->routeIs('admin.pesanan_masuk') ? 'active' : '' }}">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                <span>Pesanan Masuk</span>
                            </div>
                            @if($badgeMasuk > 0)
                                <span class="px-2 py-0.5 rounded-full text-xs font-black bg-amber-400 text-amber-950 shadow-sm animate-pulse">
                                    {{ $badgeMasuk }}
                                </span>
                            @endif
                        </a>

                        <!-- 3. Pengerjaan Laundry Bertahap -->
                        <a href="{{ route('admin.pengerjaan') }}"
                           class="nav-item flex items-center justify-between px-3.5 py-3 rounded-2xl text-sm font-bold text-slate-200 {{ request()->routeIs('admin.pengerjaan') ? 'active' : '' }}">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                <span>Pengerjaan Laundry</span>
                            </div>
                        </a>

                        <!-- 4. Verifikasi Pembayaran -->
                        <a href="{{ route('admin.pembayaran') }}"
                           class="nav-item flex items-center justify-between px-3.5 py-3 rounded-2xl text-sm font-bold text-slate-200 {{ request()->routeIs('admin.pembayaran') ? 'active' : '' }}">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                <span>Verifikasi Bayar</span>
                            </div>
                            @if($badgePendingBayar > 0)
                                <span class="px-2 py-0.5 rounded-full text-xs font-black bg-rose-500 text-white shadow-sm">
                                    {{ $badgePendingBayar }}
                                </span>
                            @endif
                        </a>

                        <!-- 5. Kurir & Pengiriman -->
                        <a href="{{ route('admin.pengiriman') }}"
                           class="nav-item flex items-center justify-between px-3.5 py-3 rounded-2xl text-sm font-bold text-slate-200 {{ request()->routeIs('admin.pengiriman') ? 'active' : '' }}">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                                <span>Pengiriman Kurir</span>
                            </div>
                        </a>

                        <!-- 6. Stok Bahan Baku -->
                        <a href="{{ route('admin.stok') }}"
                           class="nav-item flex items-center justify-between px-3.5 py-3 rounded-2xl text-sm font-bold text-slate-200 {{ request()->routeIs('admin.stok') ? 'active' : '' }}">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                <span>Stok Bahan Baku</span>
                            </div>
                        </a>

                    @elseif($role === 'owner')
                        <!-- 1. Dashboard Owner -->
                        <a href="{{ route('owner.dashboard') }}"
                           class="nav-item flex items-center justify-between px-3.5 py-3 rounded-2xl text-sm font-bold text-slate-200 {{ request()->routeIs('owner.dashboard') ? 'active' : '' }}">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                <span>Dashboard Eksekutif</span>
                            </div>
                        </a>

                        <!-- 2. Laporan Keuangan & Transaksi -->
                        <a href="{{ route('owner.laporan') }}"
                           class="nav-item flex items-center justify-between px-3.5 py-3 rounded-2xl text-sm font-bold text-slate-200 {{ request()->routeIs('owner.laporan') ? 'active' : '' }}">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <span>Laporan Keuangan</span>
                            </div>
                        </a>

                        <!-- 3. Kelola Akun Admin -->
                        <a href="{{ route('owner.admin.index') }}"
                           class="nav-item flex items-center justify-between px-3.5 py-3 rounded-2xl text-sm font-bold text-slate-200 {{ request()->routeIs('owner.admin.index') ? 'active' : '' }}">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                <span>Kelola Admin</span>
                            </div>
                        </a>

                        <!-- 4. Daftar & Tarif Layanan -->
                        <a href="{{ route('owner.layanan') }}"
                           class="nav-item flex items-center justify-between px-3.5 py-3 rounded-2xl text-sm font-bold text-slate-200 {{ request()->routeIs('owner.layanan') ? 'active' : '' }}">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                <span>Katalog Tarif</span>
                            </div>
                        </a>
                    @endif

                    <div class="pt-3 border-t border-white/10 mt-3">
                        <!-- Profil Pengguna -->
                        <a href="{{ route('profile') }}"
                           class="nav-item flex items-center justify-between px-3.5 py-3 rounded-2xl text-sm font-bold text-slate-200 {{ request()->routeIs('profile') ? 'active' : '' }}">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                <span>Profil Saya</span>
                            </div>
                        </a>

                        <!-- Kunjungi Beranda Publik -->
                        <a href="{{ route('home') }}"
                           class="nav-item flex items-center justify-between px-3.5 py-3 rounded-2xl text-sm font-bold text-slate-200">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                                <span>Halaman Utama</span>
                            </div>
                        </a>
                    </div>
                </nav>
            </div>

            <!-- User Info Footer di Sidebar & Tombol Logout (HANYA ADA DI SINI) -->
            <div class="pt-5 border-t border-white/10 space-y-3">
                <a href="{{ route('profile') }}"
                   class="flex items-center gap-3 p-2.5 rounded-2xl bg-white/5 hover:bg-white/10 transition-all border border-white/5"
                   title="Klik untuk membuka profil">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-500 to-sky-400 text-white font-extrabold flex items-center justify-center shrink-0">
                        {{ strtoupper(mb_substr($user->nama ?? 'A', 0, 1)) }}
                    </div>
                    <div class="overflow-hidden">
                        <div class="font-bold text-sm text-white truncate">{{ $user->nama ?? 'User' }}</div>
                        <div class="text-[11px] text-sky-200 font-semibold flex items-center gap-1">
                            <span>Edit Profil &rarr;</span>
                        </div>
                    </div>
                </a>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                            onclick="return confirm('Apakah Anda yakin ingin keluar dari akun ArsyClean?')"
                            class="w-full py-2.5 px-4 rounded-xl bg-rose-500/20 hover:bg-rose-500 text-rose-300 hover:text-white font-bold text-xs transition-all flex items-center justify-center gap-2 border border-rose-500/30">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        <span>Keluar Akun</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Overlay untuk Mobile -->
        <div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/60 z-40 hidden lg:hidden" onclick="toggleSidebarDesktopMobile()"></div>

        <!-- ================= AREA KONTEN UTAMA ================= -->
        <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">
            <!-- Top Navbar Header (Diselaraskan Warnanya) -->
          <header class="sticky top-0 z-30 px-4 sm:px-8 py-3.5 flex items-center justify-between shadow-md"
        style="background: linear-gradient(90deg, #091733 0%, #0f2a5c 100%); border-bottom: 1px solid rgba(255,255,255,0.1);">
                    <!-- Button Garis 3 untuk Menutup / Membuka Sidebar -->
                    <button type="button" onclick="toggleSidebarDesktopMobile()"
                            class="p-2.5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-800 transition-all flex items-center justify-center font-black"
                            title="Buka / Tutup Sidebar">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <div>
                        <h2 class="text-base sm:text-lg font-black text-slate-800">
                            @yield('page_title', 'ArsyClean Laundry')
                        </h2>
                    </div>
                </div>

                <!-- Bagian Kanan Header: Profil Avatar Saja (TIDAK ADA TOMBOL LOGOUT DI SINI) -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('profile') }}" class="flex items-center gap-2 px-3 py-1.5 rounded-xl hover:bg-slate-100 transition-all text-slate-700">
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-blue-600 to-sky-500 text-white font-black text-sm flex items-center justify-center shadow-xs">
                            {{ strtoupper(mb_substr($user->nama ?? 'A', 0, 1)) }}
                        </div>
                        <div class="hidden sm:block text-left text-xs">
                            <span class="font-extrabold text-slate-900 block leading-tight">{{ $namaSingkat }}</span>
                            <span class="text-[11px] text-slate-400 font-semibold capitalize">{{ $role }}</span>
                        </div>
                    </a>
                </div>
            </header>

            <!-- Isi Halaman Konten -->
            <main class="flex-1 p-4 sm:p-7 max-w-7xl w-full mx-auto">
                <!-- ================= GLOBAL ALERT MESSAGES (DENGAN SVG ICONS) ================= -->
                @if(session('success'))
                    <div class="mb-6 p-4 sm:p-5 rounded-2xl bg-emerald-50 border-2 border-emerald-300 text-emerald-900 font-bold flex items-start gap-3.5 shadow-sm">
                        <div class="w-7 h-7 rounded-lg bg-emerald-600 text-white flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div class="flex-1">
                            <div class="font-extrabold text-emerald-950 text-sm">Berhasil!</div>
                            <div class="text-sm text-emerald-800 font-semibold mt-0.5 leading-relaxed">{{ session('success') }}</div>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 sm:p-5 rounded-2xl bg-rose-50 border-2 border-rose-300 text-rose-900 font-bold flex items-start gap-3.5 shadow-sm">
                        <div class="w-7 h-7 rounded-lg bg-rose-600 text-white flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                        </div>
                        <div class="flex-1">
                            <div class="font-extrabold text-rose-950 text-sm">Perhatian: Terjadi Kesalahan!</div>
                            <div class="text-sm text-rose-800 font-semibold mt-0.5 leading-relaxed">{{ session('error') }}</div>
                        </div>
                    </div>
                @endif

                @if(isset($errors) && $errors->any())
                    <div class="mb-6 p-4 sm:p-5 rounded-2xl bg-rose-50 border-2 border-rose-400 text-rose-950 shadow-sm">
                        <div class="flex items-center gap-2.5 font-black text-rose-900 text-sm mb-2">
                            <div class="w-6 h-6 rounded-md bg-rose-600 text-white flex items-center justify-center">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            </div>
                            <span>Mohon Periksa Kesalahan Formulir Berikut ({{ $errors->count() }} Poin):</span>
                        </div>
                        <ul class="list-disc list-inside text-sm font-bold text-rose-800 space-y-1 pl-2">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-slate-200 py-4 px-6 text-center text-xs text-slate-500 font-semibold">
                &copy; {{ date('Y') }} ArsyClean Laundry System &bull; Seluruh Hak Cipta Dilindungi
            </footer>
        </div>
    </div>

    <script>
        function toggleSidebarDesktopMobile() {
    const sidebar = document.getElementById('mainSidebar');
    const overlay = document.getElementById('sidebarOverlay');

    if (window.innerWidth < 1024) {
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
    } else {
        sidebar.classList.toggle('sidebar-collapsed');
    }
}

window.addEventListener('resize', function () {
    const sidebar = document.getElementById('mainSidebar');
    const overlay = document.getElementById('sidebarOverlay');

    if (window.innerWidth >= 1024) {
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.add('hidden');
    } else {
        sidebar.classList.remove('sidebar-collapsed');
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
    }
});
    </script>
    @stack('scripts')
</body>
</html>
