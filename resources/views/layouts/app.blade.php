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
        :root { --navy-dark:#091733; --navy-base:#0f2a5c; --navy-soft:#173b7d; --primary-blue:#2563eb; --sidebar-width:280px; }
        * { box-sizing:border-box; }
        body { font-family:'Plus Jakarta Sans',system-ui,-apple-system,sans-serif; background:#f5f7fb; color:#1e293b; font-size:14px; line-height:1.6; }
        body.sidebar-open { overflow:hidden; }
        .sidebar-container { position:fixed !important; top:0; left:0; bottom:0; z-index:60; width:var(--sidebar-width); min-width:var(--sidebar-width); height:100vh; height:100dvh; background:linear-gradient(180deg,#091733 0%,#0f2a5c 100%); transform:translateX(-100%); visibility:hidden; pointer-events:none; transition:transform .28s ease,visibility .28s ease; overflow-y:auto; overflow-x:hidden; box-shadow:12px 0 35px rgba(15,23,42,.16); }
        body.sidebar-open .sidebar-container { transform:translateX(0); visibility:visible; pointer-events:auto; }
        #sidebarOverlay { position:fixed; inset:0; z-index:55; background:rgba(9,23,51,.55); backdrop-filter:blur(2px); opacity:0; visibility:hidden; pointer-events:none; transition:opacity .28s ease,visibility .28s ease; }
        body.sidebar-open #sidebarOverlay { opacity:1; visibility:visible; pointer-events:auto; }
        .app-content { width:100%; min-width:0; min-height:100vh; margin-left:0 !important; display:flex; flex-direction:column; }
        .nav-item { transition:background-color .2s ease,color .2s ease,transform .2s ease; }
        .nav-item:hover { background:rgba(255,255,255,.10); color:#fff; transform:translateX(3px); }
        .nav-item.active { background:linear-gradient(135deg,#2563eb 0%,#1d4ed8 100%); color:#fff; font-weight:800; box-shadow:0 4px 15px rgba(37,99,235,.25); }
        button:focus-visible,a:focus-visible { outline:3px solid rgba(59,130,246,.45); outline-offset:3px; }
        @media(max-width:640px) { :root { --sidebar-width:min(86vw,300px); } }
        @media(prefers-reduced-motion:reduce) { .sidebar-container,#sidebarOverlay,.nav-item { transition:none !important; } }
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

    <div id="appShell" class="flex-1 flex overflow-hidden">
        <!-- ================= SIDEBAR UTAMA ================= -->
        <aside id="mainSidebar" class="sidebar-container flex flex-col justify-between text-white p-5 shadow-2xl shrink-0">
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

                        <!-- 2. Pesanan Masuk -->
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

                        <!-- 3. Pengerjaan Laundry -->
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
                        <!-- KHUSUS ROLE OWNER (Ukuran teks & padding diperbesar) -->
                        <a href="{{ route('owner.dashboard') }}"
                           class="nav-item flex items-center justify-between px-3.5 py-3.5 rounded-2xl text-base font-bold text-slate-200 {{ request()->routeIs('owner.dashboard') ? 'active' : '' }}">
                            <div class="flex items-center gap-3">
                                <span class="text-xl">📈</span>
                                <span>Dashboard Eksekutif</span>
                            </div>
                        </a>

                        <a href="{{ route('owner.laporan') }}"
                           class="nav-item flex items-center justify-between px-3.5 py-3.5 rounded-2xl text-base font-bold text-slate-200 {{ request()->routeIs('owner.laporan') ? 'active' : '' }}">
                            <div class="flex items-center gap-3">
                                <span class="text-xl">📑</span>
                                <span>Laporan Keuangan</span>
                            </div>
                        </a>

                        <a href="{{ route('owner.admin.index') }}"
                           class="nav-item flex items-center justify-between px-3.5 py-3.5 rounded-2xl text-base font-bold text-slate-200 {{ request()->routeIs('owner.admin.index') ? 'active' : '' }}">
                            <div class="flex items-center gap-3">
                                <span class="text-xl">👥</span>
                                <span>Kelola Admin</span>
                            </div>
                        </a>

                        <a href="{{ route('owner.layanan') }}"
                           class="nav-item flex items-center justify-between px-3.5 py-3.5 rounded-2xl text-base font-bold text-slate-200 {{ request()->routeIs('owner.layanan') ? 'active' : '' }}">
                            <div class="flex items-center gap-3">
                                <span class="text-xl">🧺</span>
                                <span>Katalog Tarif</span>
                            </div>
                        </a>

                    @endif
                </nav>
            </div>

            <!-- User Info Footer di Sidebar & Tombol Logout Bawaan -->
            <div class="pt-5 border-t border-white/10 space-y-3">
                <div class="flex items-center justify-between gap-2 p-2.5 rounded-2xl bg-white/5 border border-white/5">
                    <a href="{{ route('profile') }}" class="flex items-center gap-3 overflow-hidden group" title="Klik untuk membuka profil">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-500 to-sky-400 text-white font-extrabold flex items-center justify-center shrink-0">
                            {{ strtoupper(mb_substr($user->nama ?? 'O', 0, 1)) }}
                        </div>
                        <div class="overflow-hidden">
                            <div class="font-bold text-sm text-white truncate">{{ $user->nama ?? 'Owner' }}</div>
                            <div class="text-[11px] text-sky-200 font-semibold truncate">{{ ucfirst($role) }}</div>
                        </div>
                    </a>

                    <form action="{{ route('logout') }}" method="POST" class="shrink-0">
                        @csrf
                        <button type="submit"
                                onclick="return confirm('Apakah Anda yakin ingin keluar dari akun ArsyClean?')"
                                class="py-2 px-3 rounded-xl bg-rose-500/15 hover:bg-rose-500 text-rose-300 hover:text-white font-bold text-xs transition-all flex items-center gap-1.5 border border-rose-500/20">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Overlay untuk Mobile -->
        <div id="sidebarOverlay" onclick="toggleSidebar(false)" aria-hidden="true"></div>

        <!-- ================= AREA KONTEN UTAMA ================= -->
        <div class="app-content flex-1 flex flex-col min-w-0 overflow-y-auto">
            <!-- Top Navbar Header -->
            <header class="sticky top-0 z-30 bg-white border-b border-slate-200/80 px-4 sm:px-8 py-3.5 flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-3">
                    <button
                    type="button"
                    id="sidebarToggle"
                    onclick="toggleSidebar()"
                    aria-label="Buka atau tutup sidebar"
                    aria-controls="mainSidebar"
                    aria-expanded="false"
                    class="p-2.5 rounded-xl border border-slate-200
                        text-slate-700 bg-white
                        hover:bg-blue-50 hover:text-blue-700
                        hover:border-blue-200
                        active:scale-95
                        transition-all duration-200"
                    >
                    <svg
                        class="w-6 h-6"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16"
                        /></svg>
                    </button>
                    <div>
                        <h2 class="text-base sm:text-lg font-black text-slate-800">
                            @yield('page_title', 'ArsyClean Laundry')
                        </h2>
                    </div>
                </div>

                <!-- Bagian Kanan Header -->
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

                    <!-- HANYA TAMPILKAN TOMBOL LOGOUT ATAS APABILA BUKAN OWNER -->
                    @if($role !== 'owner')
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                                onclick="return confirm('Apakah Anda yakin ingin keluar?')"
                                class="px-3.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-xl font-bold text-xs transition-all flex items-center gap-1.5 shadow-2xs">
                            <span>✕</span>
                            <span class="hidden sm:inline">Logout</span>
                        </button>
                    </form>
                    @endif
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

    <!-- Script Toggle Sidebar -->
    <script>
        (function () {
            const sidebar = document.getElementById('mainSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const toggleButton = document.getElementById('sidebarToggle');
            if (!sidebar || !overlay || !toggleButton) return;

            function setSidebarState(open) {
                document.body.classList.toggle('sidebar-open', open);
                overlay.setAttribute('aria-hidden', String(!open));
                sidebar.setAttribute('aria-hidden', String(!open));
                toggleButton.setAttribute('aria-expanded', String(open));
            }

            window.toggleSidebar = function (forceState = null) {
                const isOpen = document.body.classList.contains('sidebar-open');
                const shouldOpen = typeof forceState === 'boolean' ? forceState : !isOpen;
                setSidebarState(shouldOpen);
            };

            overlay.addEventListener('click', function () { setSidebarState(false); });
            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') setSidebarState(false);
            });
            sidebar.querySelectorAll('nav a').forEach(function (link) {
                link.addEventListener('click', function () { setSidebarState(false); });
            });
            setSidebarState(false);
        })();
    </script>
    @stack('scripts')
</body>
</html>