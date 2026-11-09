@extends('layouts.app')

@section('title', 'Dashboard Eksekutif - ArsyClean Owner')
@section('page_title', 'Dashboard Eksekutif & Statistik Bisnis')

@section('content')
<div class="space-y-7">

    <!-- Header Banner Dashboard Eksekutif -->
    <div class="bg-gradient-to-r from-[#0f2a5c] via-[#163a78] to-[#0a1e42] rounded-3xl p-6 sm:p-9 text-white shadow-xl flex flex-col md:flex-row items-start md:items-center justify-between gap-6 border border-blue-900/40">
        <div>
            <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-amber-400 text-amber-950 mb-3 shadow-xs">
                <svg class="w-4 h-4 text-amber-950" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1.323l3.954 1.582 1.599-.8a1 1 0 011.237 1.488l-1.077 1.8A6.002 6.002 0 0117 11v1a1 1 0 01-1 1H4a1 1 0 01-1-1v-1a6.002 6.002 0 01.287-2.607l-1.077-1.8a1 1 0 011.237-1.488l1.599.8L9 4.323V3a1 1 0 011-1zm-5 13a1 1 0 011-1h8a1 1 0 011 1v1a2 2 0 01-2 2H7a2 2 0 01-2-2v-1z" clip-rule="evenodd"></path></svg>
                Panel Pemilik Bisnis
            </div>
            <h1 class="text-2xl sm:text-4xl font-black tracking-tight">Ringkasan Eksekutif ArsyClean</h1>
            <p class="text-sm text-blue-100 max-w-2xl mt-1.5 leading-relaxed font-medium">
                Pantau pertumbuhan omzet, volume operasional laundry, performa layanan antar-jemput vs reguler vs koin, dan efektivitas tim operasional.
            </p>
        </div>

        <div class="flex items-center gap-3 shrink-0 flex-wrap">
            <a href="{{ route('owner.laporan') }}"
               class="px-5 py-3 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/20 text-white font-black text-xs transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span>Laporan Lengkap</span>
            </a>
            <a href="{{ route('owner.cetak.laporan') }}" target="_blank"
               class="px-5 py-3 rounded-2xl bg-amber-400 hover:bg-amber-300 text-amber-950 font-black text-xs shadow-md shadow-amber-400/25 transition-all flex items-center gap-2">
                <svg class="w-4 h-4 text-amber-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                <span>Cetak PDF</span>
            </a>
        </div>
    </div>

    <!-- ================= 4 KARTU STATISTIK UTAMA ================= -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- 1. Total Omzet -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200 hover:border-emerald-300 shadow-xs hover:shadow-md transition-all space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-xs font-black text-slate-500 uppercase tracking-wider">Total Omzet Lunas</span>
                <span class="w-10 h-10 rounded-2xl bg-emerald-100 text-emerald-800 flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </span>
            </div>
            <div>
                <div class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                    Rp {{ number_format($totalOmzet, 0, ',', '.') }}
                </div>
                <div class="text-xs font-bold text-emerald-700 mt-1 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                    <span>Terverifikasi dari QRIS &amp; Kasir</span>
                </div>
            </div>
        </div>

        <!-- 2. Pesanan Selesai -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200 hover:border-blue-300 shadow-xs hover:shadow-md transition-all space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-xs font-black text-slate-500 uppercase tracking-wider">Pesanan Selesai</span>
                <span class="w-10 h-10 rounded-2xl bg-blue-100 text-blue-800 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </span>
            </div>
            <div>
                <div class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                    {{ $pesananSelesai }}
                </div>
                <div class="text-xs font-semibold text-slate-500 mt-1">
                    Dari total {{ $totalPesanan }} pesanan masuk
                </div>
            </div>
        </div>

        <!-- 3. Pelanggan Terdaftar -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200 hover:border-sky-300 shadow-xs hover:shadow-md transition-all space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-xs font-black text-slate-500 uppercase tracking-wider">Pelanggan Aktif</span>
                <span class="w-10 h-10 rounded-2xl bg-sky-100 text-sky-800 flex items-center justify-center">
                    <svg class="w-5 h-5 text-sky-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </span>
            </div>
            <div>
                <div class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                    {{ $totalPelanggan }}
                </div>
                <div class="text-xs font-bold text-sky-700 mt-1">
                    Pengguna laundry terdaftar
                </div>
            </div>
        </div>

        <!-- 4. Petugas Admin -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200 hover:border-purple-300 shadow-xs hover:shadow-md transition-all space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-xs font-black text-slate-500 uppercase tracking-wider">Petugas Admin</span>
                <span class="w-10 h-10 rounded-2xl bg-purple-100 text-purple-800 flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </span>
            </div>
            <div>
                <div class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                    {{ $totalAdmin }}
                </div>
                <div class="text-xs font-bold text-purple-700 mt-1">
                    <a href="{{ route('owner.admin.index') }}" class="underline hover:text-purple-900">Kelola Akun Admin &rarr;</a>
                </div>
            </div>
        </div>
    </div>

    <!-- ================= STATISTIK VISUAL: BREAKDOWN LAYANAN & PERFORMA ================= -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @php
            $sumLayanan = max(1, $jumlahPickup + $jumlahReguler + $jumlahKoin);
            $pctPickup = round(($jumlahPickup / $sumLayanan) * 100);
            $pctReguler = round(($jumlahReguler / $sumLayanan) * 100);
            $pctKoin = round(($jumlahKoin / $sumLayanan) * 100);

            $sumStatus = max(1, $pesananSelesai + $pesananDiproses + $pesananDibatalkan);
            $pctSelesai = round(($pesananSelesai / $sumStatus) * 100);
            $pctProses = round(($pesananDiproses / $sumStatus) * 100);
            $pctBatal = round(($pesananDibatalkan / $sumStatus) * 100);
        @endphp

        <!-- 1. Distribusi Jenis Layanan -->
        <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200 shadow-xs space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-base font-black text-slate-900">Distribusi Peminat Layanan</h3>
                    <p class="text-xs text-slate-500">Perbandingan volume transaksi per kategori laundry.</p>
                </div>
                <span class="text-xs font-black text-blue-700 bg-blue-50 px-3 py-1 rounded-full border border-blue-200">
                    {{ $totalPesanan }} Total
                </span>
            </div>

            <div class="space-y-4">
                <!-- Antar Jemput -->
                <div class="space-y-1.5">
                    <div class="flex items-center justify-between text-xs font-bold">
                        <span class="text-slate-800 flex items-center gap-1.5 font-bold">
                            <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path></svg>
                            <span>Antar Jemput (Pickup Express)</span>
                        </span>
                        <span class="text-slate-900 font-black">{{ $jumlahPickup }} Pesanan ({{ $pctPickup }}%)</span>
                    </div>
                    <div class="w-full h-3 rounded-full bg-slate-100 overflow-hidden">
                        <div class="h-full rounded-full bg-gradient-to-r from-orange-400 to-orange-500 transition-all duration-500" style="width: {{ $pctPickup }}%"></div>
                    </div>
                </div>

                <!-- Reguler -->
                <div class="space-y-1.5">
                    <div class="flex items-center justify-between text-xs font-bold">
                        <span class="text-slate-800 flex items-center gap-1.5 font-bold">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            <span>Cuci Reguler &amp; Setrika</span>
                        </span>
                        <span class="text-slate-900 font-black">{{ $jumlahReguler }} Pesanan ({{ $pctReguler }}%)</span>
                    </div>
                    <div class="w-full h-3 rounded-full bg-slate-100 overflow-hidden">
                        <div class="h-full rounded-full bg-gradient-to-r from-blue-500 to-sky-400 transition-all duration-500" style="width: {{ $pctReguler }}%"></div>
                    </div>
                </div>

                <!-- Koin Self Service -->
                <div class="space-y-1.5">
                    <div class="flex items-center justify-between text-xs font-bold">
                        <span class="text-slate-800 flex items-center gap-1.5 font-bold">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>Self-Service Koin Mandiri</span>
                        </span>
                        <span class="text-slate-900 font-black">{{ $jumlahKoin }} Transaksi ({{ $pctKoin }}%)</span>
                    </div>
                    <div class="w-full h-3 rounded-full bg-slate-100 overflow-hidden">
                        <div class="h-full rounded-full bg-gradient-to-r from-purple-500 to-indigo-400 transition-all duration-500" style="width: {{ $pctKoin }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Rasio Efektivitas Pesanan -->
        <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200 shadow-xs space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-base font-black text-slate-900">Rasio Penyelesaian Laundry</h3>
                    <p class="text-xs text-slate-500">Tingkat efektivitas penyelesaian pesanan laundry.</p>
                </div>
                <span class="text-xs font-black text-emerald-700 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-200">
                    {{ $pctSelesai }}% Sukses
                </span>
            </div>

            <div class="space-y-4">
                <!-- Selesai -->
                <div class="space-y-1.5">
                    <div class="flex items-center justify-between text-xs font-bold">
                        <span class="text-slate-800 flex items-center gap-1.5 font-bold">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            <span>Berhasil Diselesaikan</span>
                        </span>
                        <span class="text-emerald-700 font-black">{{ $pesananSelesai }} ({{ $pctSelesai }}%)</span>
                    </div>
                    <div class="w-full h-3 rounded-full bg-slate-100 overflow-hidden">
                        <div class="h-full rounded-full bg-emerald-500 transition-all duration-500" style="width: {{ $pctSelesai }}%"></div>
                    </div>
                </div>

                <!-- Diproses -->
                <div class="space-y-1.5">
                    <div class="flex items-center justify-between text-xs font-bold">
                        <span class="text-slate-800 flex items-center gap-1.5 font-bold">
                            <svg class="w-4 h-4 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path></svg>
                            <span>Sedang Dikerjakan</span>
                        </span>
                        <span class="text-sky-700 font-black">{{ $pesananDiproses }} ({{ $pctProses }}%)</span>
                    </div>
                    <div class="w-full h-3 rounded-full bg-slate-100 overflow-hidden">
                        <div class="h-full rounded-full bg-sky-500 transition-all duration-500" style="width: {{ $pctProses }}%"></div>
                    </div>
                </div>

                <!-- Dibatalkan -->
                <div class="space-y-1.5">
                    <div class="flex items-center justify-between text-xs font-bold">
                        <span class="text-slate-800 flex items-center gap-1.5 font-bold">
                            <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                            <span>Dibatalkan / Ditolak</span>
                        </span>
                        <span class="text-rose-700 font-black">{{ $pesananDibatalkan }} ({{ $pctBatal }}%)</span>
                    </div>
                    <div class="w-full h-3 rounded-full bg-slate-100 overflow-hidden">
                        <div class="h-full rounded-full bg-rose-400 transition-all duration-500" style="width: {{ $pctBatal }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ================= TRANSAKSI TERBARU & LINK LAPORAN ================= -->
    <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200 shadow-xs space-y-5">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100">
            <div>
                <h3 class="text-lg font-black text-slate-900">Transaksi Pembayaran Terbaru</h3>
                <p class="text-xs text-slate-500">Daftar transaksi kasir yang masuk ke dalam buku kas.</p>
            </div>
            <a href="{{ route('owner.laporan') }}"
               class="text-xs font-black text-blue-700 hover:text-blue-900 flex items-center gap-1">
                <span>Lihat Semua Transaksi &amp; Filter Tanggal</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-xs font-black uppercase text-slate-600 tracking-wider">
                        <th class="py-3.5 px-4">Kode Bayar</th>
                        <th class="py-3.5 px-4">Pesanan</th>
                        <th class="py-3.5 px-4">Pelanggan</th>
                        <th class="py-3.5 px-4">Tanggal</th>
                        <th class="py-3.5 px-4">Nominal</th>
                        <th class="py-3.5 px-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-medium">
                    @forelse($pembayarans->take(8) as $b)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-3.5 px-4 font-mono font-black text-slate-900">#{{ $b->kode_pembayaran }}</td>
                            <td class="py-3.5 px-4 font-bold text-blue-900">#{{ $b->pesanan->kode_pesanan ?? '-' }}</td>
                            <td class="py-3.5 px-4 font-bold text-slate-800">{{ $b->pesanan->user->nama ?? '-' }}</td>
                            <td class="py-3.5 px-4 text-slate-500 font-semibold">{{ $b->created_at ? $b->created_at->format('d M Y, H:i') : '-' }}</td>
                            <td class="py-3.5 px-4 font-black text-slate-900 text-sm">Rp {{ number_format($b->jumlah_bayar, 0, ',', '.') }}</td>
                            <td class="py-3.5 px-4">
                                <span class="px-3 py-1 rounded-full text-xs font-black {{ $b->status_pembayaran === 'Lunas' ? 'bg-emerald-100 text-emerald-900 border border-emerald-300' : 'bg-amber-100 text-amber-900 border border-amber-300' }}">
                                    {{ $b->status_pembayaran }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400 font-semibold">Belum ada data transaksi pembayaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
