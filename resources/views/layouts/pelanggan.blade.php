<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Pelanggan - ArsyClean Laundry')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        :root {
            --navy-dark: #091733;
            --navy-base: #0f2a5c;
            --navy-soft: #173b7d;
            --blue: #2563eb;
            --bg: #f8fafc;
            --sidebar-w: 275px;
        }
        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            background: var(--bg);
            color: #0f172a;
            font-size: 15px;
        }

        /* ===== SIDEBAR ===== */
        .ac-sidebar {
            position: fixed; top: 0; bottom: 0; left: 0;
            width: var(--sidebar-w);
            background: linear-gradient(180deg, #091733 0%, #0f2a5c 100%);
            color: #fff;
            display: flex; flex-direction: column;
            padding: 22px 16px;
            z-index: 50;
            transition: transform .25s cubic-bezier(0.4, 0, 0.2, 1);
            box-sizing: border-box;
            box-shadow: 4px 0 24px rgba(0,0,0,0.15);
        }
        .ac-brand {
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 4px 18px; border-bottom: 1px solid rgba(255,255,255,0.1); margin-bottom: 16px;
        }
        .ac-brand-logo {
            width: 42px; height: 42px; border-radius: 14px;
            background: linear-gradient(135deg, #38bdf8, #2563eb); color: #fff;
            display: grid; place-items: center;
            font-weight: 900; font-size: 20px;
            box-shadow: 0 4px 12px rgba(37,99,235,0.3);
        }
        .ac-brand-name { font-weight: 800; font-size: 19px; letter-spacing: -0.5px; }

        .ac-nav { display: flex; flex-direction: column; gap: 6px; flex: 1; overflow-y: auto; }
        .ac-link {
            display: flex; align-items: center; gap: 12px;
            width: 100%; padding: 12px 14px; border-radius: 14px;
            border: 0; background: transparent; cursor: pointer;
            color: #cbd5e1; font: inherit; font-weight: 700; font-size: 14px;
            text-align: left; text-decoration: none;
            transition: all .2s;
        }
        .ac-link svg { width: 20px; height: 20px; flex-shrink: 0; }
        .ac-link:hover { background: rgba(255,255,255,0.1); color: #fff; transform: translateX(3px); }
        .ac-link.active { background: #2563eb; color: #fff; box-shadow: 0 4px 14px rgba(37,99,235,0.35); font-weight: 800; }
        .ac-badge {
            margin-left: auto; min-width: 22px; padding: 2px 8px;
            border-radius: 999px; background: #f59e0b; color: #fff;
            font-size: 11px; font-weight: 800; text-align: center;
        }

        .ac-foot { border-top: 1px solid rgba(255,255,255,.14); padding-top: 16px; }
        .ac-user {
            display: flex; align-items: center; gap: 12px; padding: 10px 12px;
            border-radius: 14px; background: rgba(255,255,255,0.06); margin-bottom: 10px;
            text-decoration: none; color: inherit; transition: background .15s;
        }
        .ac-user:hover { background: rgba(255,255,255,0.12); }
        .ac-avatar {
            width: 40px; height: 40px; border-radius: 12px;
            background: linear-gradient(135deg, #0284c7, #2563eb); color: #fff;
            display: grid; place-items: center; font-weight: 800; font-size: 16px;
        }
        .ac-user-name { font-weight: 800; font-size: 14px; line-height: 1.2; color: #fff; }
        .ac-user-role { font-size: 11px; color: #94a3b8; font-weight: 600; }
        .ac-logout {
            color: #fca5a5; background: rgba(239,68,68,0.15); justify-content: center;
            border-radius: 12px; font-size: 13px; font-weight: 700; border: 1px solid rgba(239,68,68,0.25);
        }
        .ac-logout:hover { background: #ef4444; color: #fff; }

        /* ===== KONTEN ===== */
        .ac-main { margin-left: var(--sidebar-w); padding: 24px 32px 64px; transition: margin-left .25s ease; }
        .ac-container { max-width: 1240px; margin: 0 auto; }
        .ac-topbar {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 24px; padding: 12px 20px; background: #fff;
            border-radius: 20px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        .ac-overlay { display: none; }

        body.ac-collapsed .ac-sidebar { transform: translateX(-100%); }
        body.ac-collapsed .ac-main { margin-left: 0; }

        @media (max-width: 1023px) {
            .ac-sidebar { transform: translateX(-100%); }
            body.ac-open .ac-sidebar { transform: translateX(0); }
            body.ac-open .ac-overlay {
                display: block; position: fixed; inset: 0;
                background: rgba(10,20,45,.6); z-index: 45;
            }
            .ac-main { margin-left: 0; padding: 16px 16px 40px; }
        }
    </style>
    @stack('styles')
</head>
<body>

@php
    $namaUser = Auth::user()->nama ?? 'Pelanggan';
    $namaSingkat = explode(' ', $namaUser)[0];

    $jumlahBelumBayar = 0;
    if (isset($pesanans)) {
        $jumlahBelumBayar = $pesanans->filter(function ($p) {
            return $p->status_pesanan === 'Menunggu Pembayaran'
                && ($p->pembayaran?->status_pembayaran ?? 'Pending') === 'Pending';
        })->count();
    }
@endphp

    {{-- ================= SIDEBAR PELANGGAN ================= --}}
    <aside class="ac-sidebar" id="acSidebar">
        <!-- Sidebar Header with Brand & Hamburger Button -->
        <div class="ac-brand">
            <div class="flex items-center gap-3">
                <div class="ac-brand-logo">A</div>
                <div>
                    <span class="ac-brand-name">ArsyClean</span>
                    <span class="block text-[10px] font-bold text-sky-300 uppercase tracking-wider">Portal Pelanggan</span>
                </div>
            </div>

            <!-- Tombol Garis 3 di Sidebar untuk Menutup -->
            <button type="button" onclick="togglePelangganSidebar()"
                    class="p-2 rounded-xl bg-white/10 hover:bg-white/20 text-white transition-all flex items-center justify-center"
                    title="Tutup / Collapse Sidebar">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>

        <nav class="ac-nav">
            <!-- 1. Dashboard -->
            <a href="{{ route('pelanggan.dashboard') }}"
               class="ac-link {{ request()->routeIs('pelanggan.dashboard') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span>Dashboard</span>
            </a>

            <!-- 2. Katalog Layanan -->
            <a href="{{ route('pelanggan.layanan') }}"
               class="ac-link {{ request()->routeIs('pelanggan.layanan') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                <span>Katalog Layanan</span>
            </a>

            <!-- 3. Riwayat Pesanan -->
            <a href="{{ route('pelanggan.riwayat') }}"
               class="ac-link {{ request()->routeIs('pelanggan.riwayat') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                <span>Riwayat Pesanan</span>
                @if($jumlahBelumBayar > 0)
                    <span class="ac-badge" title="Menunggu pembayaran">{{ $jumlahBelumBayar }}</span>
                @endif
            </a>

            <!-- 4. Profil Saya -->
            <a href="{{ route('profile') }}" class="ac-link {{ request()->routeIs('profile') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <span>Profil Saya</span>
            </a>

            <!-- 5. Ke Beranda Publik -->
            <a href="{{ route('home') }}" class="ac-link">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Halaman Utama</span>
            </a>
        </nav>

        <!-- Footer Sidebar: User Profile & Tombol Logout (HANYA ADA DI SIDEBAR) -->
        <div class="ac-foot">
            <a href="{{ route('profile') }}" class="ac-user" title="Klik untuk melihat & mengedit profil">
                <div class="ac-avatar">{{ strtoupper(mb_substr($namaUser, 0, 1)) }}</div>
                <div class="overflow-hidden">
                    <div class="ac-user-name truncate">{{ $namaUser }}</div>
                    <div class="ac-user-role">Edit Profil &rarr;</div>
                </div>
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        onclick="return confirm('Apakah Anda yakin ingin keluar dari sistem ArsyClean?')"
                        class="ac-link ac-logout">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    <span>Keluar Akun</span>
                </button>
            </form>
        </div>
    </aside>

    <div class="ac-overlay" id="acOverlay" onclick="acTutupMenu()"></div>

    {{-- ================= ISI HALAMAN ================= --}}
    <main class="ac-main">
        <div class="ac-container">
            <!-- Top Navbar Header (Diselaraskan Warnanya) -->
            <div class="ac-topbar">
                <div class="flex items-center gap-3">
                    <!-- Tombol Garis 3 untuk Menutup / Membuka Sidebar -->
                    <button type="button" class="w-10 h-10 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 flex items-center justify-center text-slate-800 transition"
                            aria-label="Buka / Tutup Sidebar" onclick="togglePelangganSidebar()" title="Buka / Tutup Sidebar">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-blue-600 text-white font-black grid place-items-center text-sm shadow-xs">A</div>
                        <span class="text-base font-extrabold text-slate-900">ArsyClean Portal</span>
                    </div>
                </div>

                <!-- Bagian Kanan Header: Profil Avatar Saja (TIDAK ADA TOMBOL LOGOUT DI HEADER) -->
                <a href="{{ route('profile') }}" class="flex items-center gap-2.5 p-1.5 pr-3 rounded-xl hover:bg-slate-50 transition border border-transparent hover:border-slate-200" title="Profil Saya">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-blue-600 to-sky-400 text-white font-black text-xs grid place-items-center shadow-xs">
                        {{ strtoupper(mb_substr($namaUser, 0, 1)) }}
                    </div>
                    <span class="text-sm font-bold text-slate-800 hidden sm:inline">{{ $namaSingkat }}</span>
                </a>
            </div>

            <!-- Global Alert Notifikasi Pelanggan (SVG ICONS) -->
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
        </div>
    </main>

    <script>
        function togglePelangganSidebar() {
            if (window.innerWidth < 1024) {
                document.body.classList.toggle('ac-open');
            } else {
                document.body.classList.toggle('ac-collapsed');
            }
        }
        function acTutupMenu() {
            document.body.classList.remove('ac-open');
        }
    </script>
    @stack('scripts')
</body>
</html>
