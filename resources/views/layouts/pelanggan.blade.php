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
            --navy: #0f2a5c;
            --navy-soft: #1b3a78;
            --blue: #2563eb;
            --bg: #f8fafc;
            --sidebar-w: 270px;
        }
        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            background: var(--bg);
            color: #1e293b;
            font-size: 15px;
        }

        /* ===== SIDEBAR ===== */
        .ac-sidebar {
            position: fixed; top: 0; bottom: 0; left: 0;
            width: var(--sidebar-w);
            background: var(--navy);
            color: #fff;
            display: flex; flex-direction: column;
            padding: 24px 16px;
            z-index: 40;
            transition: transform .25s ease;
            box-sizing: border-box;
            box-shadow: 4px 0 24px rgba(0,0,0,0.1);
        }
        .ac-brand { display: flex; align-items: center; gap: 12px; padding: 0 8px 24px; border-bottom: 1px solid rgba(255,255,255,0.1); margin-bottom: 16px; }
        .ac-brand-logo {
            width: 42px; height: 42px; border-radius: 14px;
            background: linear-gradient(135deg, #38bdf8, #2563eb); color: #fff;
            display: grid; place-items: center;
            font-weight: 900; font-size: 20px;
            box-shadow: 0 4px 12px rgba(37,99,235,0.3);
        }
        .ac-brand-name { font-weight: 800; font-size: 20px; letter-spacing: -0.5px; }

        .ac-nav { display: flex; flex-direction: column; gap: 6px; flex: 1; overflow-y: auto; }
        .ac-link {
            display: flex; align-items: center; gap: 12px;
            width: 100%; padding: 12px 14px; border-radius: 14px;
            border: 0; background: transparent; cursor: pointer;
            color: #cbd5e1; font: inherit; font-weight: 700; font-size: 14px;
            text-align: left; text-decoration: none;
            transition: background .15s, color .15s;
        }
        .ac-link svg { width: 20px; height: 20px; flex-shrink: 0; }
        .ac-link:hover { background: var(--navy-soft); color: #fff; }
        .ac-link.active { background: #2563eb; color: #fff; box-shadow: 0 4px 14px rgba(37,99,235,0.35); }
        .ac-badge {
            margin-left: auto; min-width: 22px; padding: 2px 8px;
            border-radius: 999px; background: #f59e0b; color: #fff;
            font-size: 11px; font-weight: 800; text-align: center;
        }

        .ac-foot { border-top: 1px solid rgba(255,255,255,.14); padding-top: 16px; }
        .ac-user {
            display: flex; align-items: center; gap: 12px; padding: 10px 12px;
            border-radius: 14px; background: rgba(255,255,255,0.06); margin-bottom: 12px;
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
            color: #fca5a5; background: rgba(239,68,68,0.12); justify-content: center;
            border-radius: 12px; font-size: 13px; font-weight: 700;
        }
        .ac-logout:hover { background: #ef4444; color: #fff; }

        /* ===== KONTEN ===== */
        .ac-main { margin-left: var(--sidebar-w); padding: 32px 36px 64px; }
        .ac-container { max-width: 1200px; margin: 0 auto; }
        .ac-mobilebar { display: none; }
        .ac-overlay { display: none; }

        @media (max-width: 1023px) {
            .ac-sidebar { transform: translateX(-100%); }
            body.ac-open .ac-sidebar { transform: translateX(0); }
            body.ac-open .ac-overlay {
                display: block; position: fixed; inset: 0;
                background: rgba(10,20,45,.5); z-index: 30;
            }
            .ac-main { margin-left: 0; padding: 16px 16px 40px; }
            .ac-mobilebar { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
            .ac-menubtn {
                width: 42px; height: 42px; border-radius: 12px;
                border: 1px solid #e2e8f0; background: #fff; cursor: pointer;
                display: grid; place-items: center;
            }
            .ac-menubtn svg { width: 22px; height: 22px; }
        }
    </style>
    @stack('styles')
</head>
<body>

@php
    $namaUser = Auth::user()->nama ?? 'Pelanggan';

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
            <div class="ac-brand-logo">A</div>
            <div>
                <span class="ac-brand-name">ArsyClean</span>
                <span class="block text-[10px] font-bold text-sky-300 uppercase tracking-wider">Pelanggan</span>
            </div>
        </div>

        <nav class="ac-nav">
            <a href="{{ route('pelanggan.dashboard') }}"
               class="ac-link {{ request()->routeIs('pelanggan.dashboard') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="9" rx="1"/><rect x="14" y="3" width="7" height="5" rx="1"/><rect x="14" y="12" width="7" height="9" rx="1"/><rect x="3" y="16" width="7" height="5" rx="1"/></svg>
                Dashboard
            </a>

            <!-- Tombol Pesan Laundry -->
            <button type="button" class="ac-link" onclick="acTutupMenu(); if (typeof openOrderModal === 'function') openOrderModal();">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 3h15v13H1z"/><path d="M16 8h4l3 3v5h-7z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                Pesan Laundry
            </button>

            <!-- Lacak Cucian Aktif -->
            @if(isset($activePesanans) && $activePesanans->isNotEmpty())
                <a href="{{ route('pelanggan.dashboard') }}#pesanan-aktif" class="ac-link" onclick="acTutupMenu()">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><path d="M3.3 7L12 12l8.7-5M12 22V12"/></svg>
                    Lacak Cucian
                    <span class="ac-badge" style="background:#2563eb">{{ $activePesanans->count() }}</span>
                </a>
            @endif

            <a href="{{ route('pelanggan.dashboard') }}#riwayat" class="ac-link" onclick="acTutupMenu()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
                Riwayat Pesanan
                @if($jumlahBelumBayar > 0)
                    <span class="ac-badge" title="Menunggu pembayaran">{{ $jumlahBelumBayar }}</span>
                @endif
            </a>

            <!-- Profil Saya -->
            <a href="{{ route('profile') }}" class="ac-link {{ request()->routeIs('profile') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                Profil Saya
            </a>

            <!-- Ke Beranda -->
            <a href="{{ route('home') }}" class="ac-link">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                Halaman Utama
            </a>
        </nav>

        <!-- Footer Sidebar: User Profile yang bisa diklik + Tombol Logout Terpisah -->
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
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="M16 17l5-5-5-5"/><path d="M21 12H9"/></svg>
                    Keluar Akun
                </button>
            </form>
        </div>
    </aside>
    <div class="ac-overlay" id="acOverlay" onclick="acTutupMenu()"></div>

    {{-- ================= ISI HALAMAN ================= --}}
    <main class="ac-main">
        <div class="ac-container">
            <div class="ac-mobilebar">
                <button type="button" class="ac-menubtn" aria-label="Buka menu" onclick="document.body.classList.add('ac-open')">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-blue-600 text-white font-black grid place-items-center text-sm">A</div>
                    <b class="text-lg font-black text-slate-800">ArsyClean</b>
                </div>
                <a href="{{ route('profile') }}" class="w-9 h-9 rounded-full bg-blue-100 text-blue-700 font-bold grid place-items-center text-xs">
                    {{ strtoupper(mb_substr($namaUser, 0, 1)) }}
                </a>
            </div>

            @yield('content')
        </div>
    </main>

    <script>
        function acTutupMenu() { document.body.classList.remove('ac-open'); }
    </script>
    @stack('scripts')
</body>
</html>
