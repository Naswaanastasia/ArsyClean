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
            --navy: #102653;
            --blue: #2563eb;
            --bg: #f7faff;
            --sidebar-width: 265px;
            --topbar-height: 72px;
        }

        * { box-sizing: border-box; }

        html, body { margin: 0; min-height: 100%; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: #0f172a;
            font-size: 14px;
        }

        /* ================= SIDEBAR (penuh dari atas sampai bawah) ================= */

        .ac-sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            width: var(--sidebar-width);
            z-index: 70;

            display: flex;
            flex-direction: column;
            padding: 18px 14px;

            background: #ffffff;
            border-right: 1px solid #e3ecfb;
            overflow: hidden;

            transition: width .25s ease, padding .25s ease, transform .25s ease;
        }

        .ac-brand {
            display: flex;
            align-items: center;
            gap: 12px;

            height: calc(var(--topbar-height) - 18px);
            padding: 0 6px 14px;
            margin-bottom: 14px;
            border-bottom: 1px solid #edf2fb;
            white-space: nowrap;
        }

        .ac-logo {
            width: 42px;
            height: 42px;
            flex-shrink: 0;
            display: grid;
            place-items: center;

            border-radius: 13px;
            background: linear-gradient(135deg, #38bdf8, #2563eb);
            color: #fff;
            font-size: 20px;
            font-weight: 900;
            box-shadow: 0 4px 12px rgba(37, 99, 235, .18);
        }

        .ac-brand-name {
            font-size: 16px;
            font-weight: 900;
            color: var(--navy);
            letter-spacing: -.4px;
            line-height: 1.2;
        }

        .ac-brand-subtitle {
            display: block;
            margin-top: 2px;
            font-size: 9.5px;
            font-weight: 800;
            color: var(--blue);
            letter-spacing: .5px;
            text-transform: uppercase;
        }

        /* ---- Menu ---- */

        .ac-nav {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 6px;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .ac-nav::-webkit-scrollbar { width: 4px; }
        .ac-nav::-webkit-scrollbar-thumb { background: #cbdcf5; border-radius: 10px; }

        .ac-link {
            display: flex;
            align-items: center;
            gap: 13px;

            width: 100%;
            min-height: 48px;
            padding: 12px 14px;

            border-radius: 12px;
            color: #334b73;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            white-space: nowrap;

            transition: background .2s, color .2s;
        }

        .ac-link svg { width: 20px; height: 20px; flex-shrink: 0; }

        .ac-link:hover { background: #eaf2ff; color: #1d4ed8; }

        .ac-link.active {
            background: linear-gradient(110deg, #2563eb, #3b82f6);
            color: #fff;
            font-weight: 800;
            box-shadow: 0 5px 12px rgba(37, 99, 235, .2);
        }

        .ac-badge {
            margin-left: auto;
            min-width: 21px;
            padding: 3px 7px;
            border-radius: 999px;
            background: #f59e0b;
            color: #fff;
            font-size: 11px;
            font-weight: 900;
            text-align: center;
        }

        /* ---- Logout ---- */

        .ac-foot {
            flex-shrink: 0;
            padding-top: 14px;
            border-top: 1px solid #edf2fb;
        }

        .ac-logout {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            padding: 12px;

            border: 1px solid #fecdd3;
            border-radius: 12px;
            background: #fff1f3;
            color: #dc2626;

            font-family: inherit;
            font-size: 13px;
            font-weight: 800;
            white-space: nowrap;
            cursor: pointer;
            transition: .2s;
        }

        .ac-logout:hover { background: #ef4444; border-color: #ef4444; color: #fff; }

        /* ================= TOPBAR (geser mengikuti sidebar) ================= */

        .ac-topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            height: var(--topbar-height);
            z-index: 60;

            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;

            background: rgba(247, 250, 255, .92);
            backdrop-filter: blur(8px);
            border-bottom: 1px solid #e3ecfb;

            transition: left .25s ease;
        }

        .ac-topbar-left { display: flex; align-items: center; gap: 14px; }

        .ac-toggle {
            width: 44px;
            height: 44px;
            display: grid;
            place-items: center;

            border: 1px solid #cfe0ff;
            border-radius: 13px;
            background: #fff;
            color: var(--blue);
            cursor: pointer;
            transition: .2s;
        }

        .ac-toggle:hover { background: #dbeafe; border-color: #93c5fd; }

        /* logo kecil: hanya muncul kalau sidebar ditutup / di HP */
        .ac-mini-brand { display: none; align-items: center; gap: 10px; }
        .ac-mini-brand .ac-logo { width: 38px; height: 38px; font-size: 18px; border-radius: 12px; }
        .ac-mini-title { font-size: 15px; font-weight: 900; color: var(--navy); letter-spacing: -.4px; }

        .ac-top-profile {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 6px 14px 6px 8px;

            border: 1px solid #d5e3fb;
            border-radius: 999px;
            background: #fff;
            color: var(--navy);
            text-decoration: none;
            transition: .2s;
        }

        .ac-top-profile:hover { border-color: #93c5fd; box-shadow: 0 4px 12px rgba(37, 99, 235, .08); }

        .ac-top-avatar {
            width: 36px;
            height: 36px;
            display: grid;
            place-items: center;
            border-radius: 50%;
            background: linear-gradient(135deg, #38bdf8, #2563eb);
            color: #fff;
            font-size: 14px;
            font-weight: 900;
        }

        /* ================= KONTEN ================= */

        .ac-main {
            min-height: 100vh;
            margin-left: var(--sidebar-width);
            padding: calc(var(--topbar-height) + 24px) 30px 35px;
            transition: margin-left .25s ease;
        }

        .ac-container { width: 100%; max-width: 1500px; margin: 0 auto; }

        /* ---- Sidebar ditutup (desktop) ---- */

        body.ac-collapsed .ac-sidebar { width: 0; padding-left: 0; padding-right: 0; border-right: 0; }
        body.ac-collapsed .ac-topbar { left: 0; }
        body.ac-collapsed .ac-main { margin-left: 0; }
        body.ac-collapsed .ac-mini-brand { display: flex; }

        .ac-overlay { display: none; }

        /* ================= HP / TABLET ================= */

        @media (max-width: 1023px) {
            .ac-sidebar { width: var(--sidebar-width); transform: translateX(-100%); }
            body.ac-open .ac-sidebar { transform: translateX(0); box-shadow: 8px 0 30px rgba(16, 38, 83, .15); }

            body.ac-open .ac-overlay {
                display: block;
                position: fixed;
                inset: 0;
                background: rgba(10, 20, 45, .45);
                z-index: 65;
            }

            .ac-topbar { left: 0; padding: 0 16px; }
            .ac-main { margin-left: 0; padding: calc(var(--topbar-height) + 18px) 16px 30px; }
            .ac-mini-brand { display: flex; }
        }

        @media (max-width: 480px) {
            :root { --topbar-height: 66px; }
            .ac-top-profile { padding: 5px; }
            .ac-main { padding-left: 12px; padding-right: 12px; }
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

{{-- ================= SIDEBAR ================= --}}

<aside class="ac-sidebar" id="acSidebar">

    <div class="ac-brand">
        <div class="ac-logo">A</div>
        <div>
            <div class="ac-brand-name">ArsyClean</div>
            <span class="ac-brand-subtitle">Portal Pelanggan</span>
        </div>
    </div>

    <nav class="ac-nav">

        <a href="{{ route('pelanggan.dashboard') }}"
           class="ac-link {{ request()->routeIs('pelanggan.dashboard') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('pelanggan.layanan') }}"
           class="ac-link {{ request()->routeIs('pelanggan.layanan') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
            <span>Katalog Layanan</span>
        </a>

        <a href="{{ route('pelanggan.riwayat') }}"
           class="ac-link {{ request()->routeIs('pelanggan.riwayat') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
            </svg>
            <span>Riwayat Pesanan</span>
            @if($jumlahBelumBayar > 0)
                <span class="ac-badge">{{ $jumlahBelumBayar }}</span>
            @endif
        </a>

        <a href="{{ route('profile') }}"
           class="ac-link {{ request()->routeIs('profile') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <span>Profil Saya</span>
        </a>

    </nav>

    <div class="ac-foot">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    onclick="return confirm('Apakah Anda yakin ingin keluar dari sistem ArsyClean?')"
                    class="ac-logout">
                <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <span>Keluar Akun</span>
            </button>
        </form>
    </div>

</aside>

{{-- ================= TOPBAR ================= --}}

<header class="ac-topbar">

    <div class="ac-topbar-left">

        <button type="button"
                class="ac-toggle"
                onclick="togglePelangganSidebar()"
                aria-label="Buka atau tutup sidebar"
                title="Buka / Tutup Menu">
            <svg width="21" height="21" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        <div class="ac-mini-brand">
            <div class="ac-logo">A</div>
            <div class="ac-mini-title">ArsyClean</div>
        </div>

    </div>

    <a href="{{ route('profile') }}" class="ac-top-profile">
        <div class="ac-top-avatar">{{ strtoupper(mb_substr($namaUser, 0, 1)) }}</div>
        <div class="hidden sm:block">
            <div class="font-extrabold text-sm leading-tight">{{ $namaSingkat }}</div>
            <div class="text-[10px] text-blue-600 font-semibold">Pelanggan</div>
        </div>
    </a>

</header>

{{-- OVERLAY MOBILE --}}

<div class="ac-overlay" id="acOverlay" onclick="acTutupMenu()"></div>

{{-- ================= KONTEN HALAMAN ================= --}}

<main class="ac-main">

    <div class="ac-container">

        @if(session('success'))
            <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 flex items-start gap-3 shadow-sm">
                <div class="w-7 h-7 rounded-lg bg-emerald-600 text-white flex items-center justify-center shrink-0">✓</div>
                <div class="flex-1">
                    <div class="font-extrabold text-sm">Berhasil!</div>
                    <div class="text-sm mt-1">{{ session('success') }}</div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 flex items-start gap-3 shadow-sm">
                <div class="w-7 h-7 rounded-lg bg-rose-600 text-white flex items-center justify-center shrink-0">×</div>
                <div class="flex-1">
                    <div class="font-extrabold text-sm">Terjadi Kesalahan!</div>
                    <div class="text-sm mt-1">{{ session('error') }}</div>
                </div>
            </div>
        @endif

        @if(isset($errors) && $errors->any())
            <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-300 text-rose-900 shadow-sm">
                <div class="font-extrabold text-sm mb-2">
                    Mohon periksa kesalahan formulir ({{ $errors->count() }}):
                </div>
                <ul class="list-disc list-inside text-sm space-y-1">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')

    </div>

</main>

{{-- ================= JAVASCRIPT SIDEBAR ================= --}}

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

    window.addEventListener('resize', function () {
        if (window.innerWidth >= 1024) {
            document.body.classList.remove('ac-open');
        } else {
            document.body.classList.remove('ac-collapsed');
        }
    });

    document.querySelectorAll('.ac-nav a').forEach(function (link) {
        link.addEventListener('click', function () {
            if (window.innerWidth < 1024) {
                acTutupMenu();
            }
        });
    });
</script>

@stack('scripts')

</body>
</html>