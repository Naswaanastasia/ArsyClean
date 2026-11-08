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
            --navy-dark: #0a1733;
            --navy-base: #0f2a5c;
            --navy-soft: #173b7d;
            --primary-blue: #2563eb;
            --sidebar-width: 270px;
        }
        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            background-color: #f8fafc;
            color: #0f172a;
            font-size: 15px;
        }
        .sidebar-container {
            width: var(--sidebar-width);
            background: linear-gradient(180deg, #091733 0%, #0f2a5c 100%);
        }
        .nav-item {
            transition: all 0.2s ease-in-out;
        }
        .nav-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            transform: translateX(3px);
        }
        .nav-item.active {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: #ffffff;
            font-weight: 800;
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.35);
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
        <aside id="mainSidebar" class="sidebar-container fixed lg:static inset-y-0 left-0 z-50 flex flex-col justify-between text-white p-5 -translate-x-full lg:translate-x-0 transition-transform duration-300 shadow-2xl shrink-0">
            <div>
                <!-- Brand Header -->
                <div class="flex items-center gap-3.5 pb-6 mb-6 border-b border-white/10">
                    <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-sky-400 to-blue-600 flex items-center justify-center font-black text-2xl text-white shadow-lg shadow-sky-500/25">
                        A
                    </div>
                    <div>
                        <div class="font-black text-xl tracking-tight leading-tight flex items-center gap-2">
                            <span>ArsyClean</span>
                        </div>
                        <span class="inline-block mt-0.5 px-2.5 py-0.5 rounded-full text-[11px] font-black uppercase tracking-wider
                            {{ $role === 'owner' ? 'bg-amber-400/20 text-amber-300 border border-amber-400/30' : 'bg-sky-400/20 text-sky-200 border border-sky-400/30' }}">
                            {{ $role === 'owner' ? '👑 Owner Eksekutif' : '🧺 Petugas Admin' }}
                        </span>
                    </div>
                </div>

                <!-- Navigation Links -->
                <nav class="space-y-1.5">
                    @if($role === 'admin')
                        <!-- 1. Dashboard Admin -->
                        <a href="{{ route('admin.dashboard') }}"
                           class="nav-item flex items-center justify-between px-3.5 py-3 rounded-2xl text-sm font-bold text-slate-200 {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <div class="flex items-center gap-3">
                                <span class="text-lg">📊</span>
                                <span>Dashboard</span>
                            </div>
                        </a>

                        <!-- 2. Pesanan Masuk (Khusus Konfirmasi) -->
                        <a href="{{ route('admin.pesanan_masuk') }}"
                           class="nav-item flex items-center justify-between px-3.5 py-3 rounded-2xl text-sm font-bold text-slate-200 {{ request()->routeIs('admin.pesanan_masuk') ? 'active' : '' }}">
                            <div class="flex items-center gap-3">
                                <span class="text-lg">📥</span>
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
                                <span class="text-lg">⚙️</span>
                                <span>Pengerjaan Laundry</span>
                            </div>
                        </a>

                        <!-- 4. Verifikasi Pembayaran -->
                        <a href="{{ route('admin.pembayaran') }}"
                           class="nav-item flex items-center justify-between px-3.5 py-3 rounded-2xl text-sm font-bold text-slate-200 {{ request()->routeIs('admin.pembayaran') ? 'active' : '' }}">
                            <div class="flex items-center gap-3">
                                <span class="text-lg">💳</span>
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
                                <span class="text-lg">🛵</span>
                                <span>Pengiriman Kurir</span>
                            </div>
                        </a>

                        <!-- 6. Stok Bahan Baku -->
                        <a href="{{ route('admin.stok') }}"
                           class="nav-item flex items-center justify-between px-3.5 py-3 rounded-2xl text-sm font-bold text-slate-200 {{ request()->routeIs('admin.stok') ? 'active' : '' }}">
                            <div class="flex items-center gap-3">
                                <span class="text-lg">📦</span>
                                <span>Stok Bahan Baku</span>
                            </div>
                        </a>

                    @elseif($role === 'owner')
                        <!-- 1. Dashboard Owner -->
                        <a href="{{ route('owner.dashboard') }}"
                           class="nav-item flex items-center justify-between px-3.5 py-3 rounded-2xl text-sm font-bold text-slate-200 {{ request()->routeIs('owner.dashboard') ? 'active' : '' }}">
                            <div class="flex items-center gap-3">
                                <span class="text-lg">📈</span>
                                <span>Dashboard Eksekutif</span>
                            </div>
                        </a>

                        <!-- 2. Laporan Keuangan & Transaksi -->
                        <a href="{{ route('owner.laporan') }}"
                           class="nav-item flex items-center justify-between px-3.5 py-3 rounded-2xl text-sm font-bold text-slate-200 {{ request()->routeIs('owner.laporan') ? 'active' : '' }}">
                            <div class="flex items-center gap-3">
                                <span class="text-lg">📑</span>
                                <span>Laporan Keuangan</span>
                            </div>
                        </a>

                        <!-- 3. Kelola Akun Admin (Menu Dipisahkan) -->
                        <a href="{{ route('owner.admin.index') }}"
                           class="nav-item flex items-center justify-between px-3.5 py-3 rounded-2xl text-sm font-bold text-slate-200 {{ request()->routeIs('owner.admin.index') ? 'active' : '' }}">
                            <div class="flex items-center gap-3">
                                <span class="text-lg">👥</span>
                                <span>Kelola Admin</span>
                            </div>
                        </a>

                        <!-- 4. Daftar & Tarif Layanan -->
                        <a href="{{ route('owner.layanan') }}"
                           class="nav-item flex items-center justify-between px-3.5 py-3 rounded-2xl text-sm font-bold text-slate-200 {{ request()->routeIs('owner.layanan') ? 'active' : '' }}">
                            <div class="flex items-center gap-3">
                                <span class="text-lg">🧺</span>
                                <span>Katalog Tarif</span>
                            </div>
                        </a>
                    @endif

                    <div class="pt-3 border-t border-white/10 mt-3">
                        <!-- Profil Pengguna -->
                        <a href="{{ route('profile') }}"
                           class="nav-item flex items-center justify-between px-3.5 py-3 rounded-2xl text-sm font-bold text-slate-200 {{ request()->routeIs('profile') ? 'active' : '' }}">
                            <div class="flex items-center gap-3">
                                <span class="text-lg">👤</span>
                                <span>Profil Saya</span>
                            </div>
                        </a>

                        <!-- Kunjungi Beranda Publik -->
                        <a href="{{ route('home') }}"
                           class="nav-item flex items-center justify-between px-3.5 py-3 rounded-2xl text-sm font-bold text-slate-200">
                            <div class="flex items-center gap-3">
                                <span class="text-lg">🏠</span>
                                <span>Halaman Utama</span>
                            </div>
                        </a>
                    </div>
                </nav>
            </div>

            <!-- User Info Footer di Sidebar & Tombol Logout -->
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
                            <span>Edit Profil</span>
                            <span>&rarr;</span>
                        </div>
                    </div>
                </a>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                            onclick="return confirm('Apakah Anda yakin ingin keluar dari akun ArsyClean?')"
                            class="w-full py-2.5 px-4 rounded-xl bg-rose-500/15 hover:bg-rose-500 text-rose-300 hover:text-white font-bold text-xs transition-all flex items-center justify-center gap-2 border border-rose-500/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        <span>Keluar Akun</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Overlay untuk Mobile -->
        <div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/60 z-40 hidden lg:hidden" onclick="toggleSidebar()"></div>

        <!-- ================= AREA KONTEN UTAMA ================= -->
        <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">
            <!-- Top Navbar Header -->
            <header class="sticky top-0 z-30 bg-white border-b border-slate-200/80 px-4 sm:px-8 py-3.5 flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-3">
                    <button type="button" onclick="toggleSidebar()" class="lg:hidden p-2 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <div>
                        <h2 class="text-base sm:text-lg font-black text-slate-800">
                            @yield('page_title', 'ArsyClean Laundry')
                        </h2>
                    </div>
                </div>

                <!-- Bagian Kanan Header: Profil Avatar + Standalone Logout -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('profile') }}" class="flex items-center gap-2 px-3 py-1.5 rounded-xl hover:bg-slate-100 transition-all text-slate-700">
                        <div class="w-8 h-8 rounded-lg bg-blue-600 text-white font-black text-xs flex items-center justify-center">
                            {{ strtoupper(mb_substr($user->nama ?? 'A', 0, 1)) }}
                        </div>
                        <div class="hidden sm:block text-left text-xs">
                            <span class="font-extrabold text-slate-900 block leading-tight">{{ $namaSingkat }}</span>
                            <span class="text-[10px] text-slate-400 font-semibold capitalize">{{ $role }}</span>
                        </div>
                    </a>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                                onclick="return confirm('Apakah Anda yakin ingin keluar?')"
                                class="px-3.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-xl font-bold text-xs transition-all flex items-center gap-1.5 shadow-2xs">
                            <span>✕</span>
                            <span class="hidden sm:inline">Logout</span>
                        </button>
                    </form>
                </div>
            </header>

            <!-- Isi Halaman Konten -->
            <main class="flex-1 p-4 sm:p-7 max-w-7xl w-full mx-auto">
                <!-- ================= GLOBAL ALERT MESSAGES ================= -->
                @if(session('success'))
                    <div class="mb-6 p-4 sm:p-5 rounded-2xl bg-emerald-50 border-2 border-emerald-300 text-emerald-900 font-bold flex items-start gap-3.5 shadow-sm">
                        <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0 text-base font-black">✓</div>
                        <div class="flex-1">
                            <div class="font-extrabold text-emerald-950 text-sm">Berhasil!</div>
                            <div class="text-xs text-emerald-800 font-semibold mt-0.5 leading-relaxed">{{ session('success') }}</div>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 sm:p-5 rounded-2xl bg-rose-50 border-2 border-rose-300 text-rose-900 font-bold flex items-start gap-3.5 shadow-sm">
                        <div class="w-8 h-8 rounded-xl bg-rose-600 text-white flex items-center justify-center shrink-0 text-base font-black">✕</div>
                        <div class="flex-1">
                            <div class="font-extrabold text-rose-950 text-sm">Perhatian: Terjadi Kesalahan!</div>
                            <div class="text-xs text-rose-800 font-semibold mt-0.5 leading-relaxed">{{ session('error') }}</div>
                        </div>
                    </div>
                @endif

                @if(isset($errors) && $errors->any())
                    <div class="mb-6 p-4 sm:p-5 rounded-2xl bg-rose-50 border-2 border-rose-400 text-rose-950 shadow-sm">
                        <div class="flex items-center gap-2.5 font-black text-rose-900 text-sm mb-2">
                            <span class="w-7 h-7 rounded-lg bg-rose-600 text-white flex items-center justify-center text-xs font-black">⚠️</span>
                            <span>Mohon Perbaiki Kesalahan Input Data Berikut ({{ $errors->count() }} Poin):</span>
                        </div>
                        <ul class="list-disc list-inside text-xs font-bold text-rose-800 space-y-1 pl-2">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-slate-200/80 py-4 px-6 text-center text-xs text-slate-400 font-semibold">
                &copy; {{ date('Y') }} ArsyClean Laundry System &bull; Seluruh Hak Cipta Dilindungi
            </footer>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('mainSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
    </script>
    @stack('scripts')
</body>
</html>
