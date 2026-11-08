@extends('layouts.app')

@section('title', 'Dashboard Eksekutif - ArsyClean Owner')
@section('page_title', 'Dashboard Eksekutif & Statistik Bisnis')

@section('content')
<div class="space-y-7">

    <!-- Header Banner Dashboard Eksekutif -->
    <div class="bg-gradient-to-r from-slate-900 via-[#0f2a5c] to-blue-950 rounded-3xl p-6 sm:p-9 text-white shadow-xl flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div>
            <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-amber-400 text-amber-950 mb-3 shadow-xs">
                👑 Panel Pemilik Bisnis
            </div>
            <h1 class="text-2xl sm:text-4xl font-black tracking-tight">Ringkasan Eksekutif ArsyClean</h1>
            <p class="text-sm text-slate-300 max-w-2xl mt-1.5 leading-relaxed">
                Pantau pertumbuhan omzet, volume operasional laundry, performa layanan antar-jemput vs reguler vs koin, dan efektivitas tim operasional.
            </p>
        </div>

        <div class="flex items-center gap-3 shrink-0 flex-wrap">
            <a href="{{ route('owner.laporan') }}"
               class="px-5 py-3 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/20 text-white font-extrabold text-xs transition-all flex items-center gap-2">
                <span>📑</span> Laporan Lengkap
            </a>
            <a href="{{ route('owner.cetak.laporan') }}" target="_blank"
               class="px-5 py-3 rounded-2xl bg-amber-400 hover:bg-amber-300 text-amber-950 font-black text-xs shadow-md shadow-amber-400/25 transition-all flex items-center gap-2">
                <span>🖨️</span> Cetak PDF
            </a>
        </div>
    </div>

    <!-- ================= 4 KARTU STATISTIK UTAMA ================= -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- 1. Total Omzet -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200 hover:border-emerald-300 shadow-xs hover:shadow-md transition-all space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-xs font-extrabold text-slate-400 uppercase tracking-wider">Total Omzet Lunas</span>
                <span class="w-10 h-10 rounded-2xl bg-emerald-100 text-emerald-800 flex items-center justify-center text-xl">💰</span>
            </div>
            <div>
                <div class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                    Rp {{ number_format($totalOmzet, 0, ',', '.') }}
                </div>
                <div class="text-[11px] font-bold text-emerald-700 mt-1 flex items-center gap-1">
                    <span>✓</span> Terverifikasi dari kasir &amp; Midtrans
                </div>
            </div>
        </div>

        <!-- 2. Pesanan Selesai -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200 hover:border-blue-300 shadow-xs hover:shadow-md transition-all space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-xs font-extrabold text-slate-400 uppercase tracking-wider">Pesanan Selesai</span>
                <span class="w-10 h-10 rounded-2xl bg-blue-100 text-blue-800 flex items-center justify-center text-xl">✅</span>
            </div>
            <div>
                <div class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                    {{ $pesananSelesai }}
                </div>
                <div class="text-[11px] font-bold text-slate-500 mt-1">
                    Dari total {{ $totalPesanan }} pesanan masuk
                </div>
            </div>
        </div>

        <!-- 3. Pelanggan Terdaftar -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200 hover:border-sky-300 shadow-xs hover:shadow-md transition-all space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-xs font-extrabold text-slate-400 uppercase tracking-wider">Pelanggan Aktif</span>
                <span class="w-10 h-10 rounded-2xl bg-sky-100 text-sky-800 flex items-center justify-center text-xl">👥</span>
            </div>
            <div>
                <div class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                    {{ $totalPelanggan }}
                </div>
                <div class="text-[11px] font-bold text-sky-700 mt-1">
                    Pengguna laundry terdaftar
                </div>
            </div>
        </div>

        <!-- 4. Petugas Admin -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200 hover:border-purple-300 shadow-xs hover:shadow-md transition-all space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-xs font-extrabold text-slate-400 uppercase tracking-wider">Petugas Admin</span>
                <span class="w-10 h-10 rounded-2xl bg-purple-100 text-purple-800 flex items-center justify-center text-xl">🧺</span>
            </div>
            <div>
                <div class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                    {{ $totalAdmin }}
                </div>
                <div class="text-[11px] font-bold text-purple-700 mt-1">
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
                <span class="text-xs font-black text-blue-600 bg-blue-50 px-3 py-1 rounded-full border border-blue-100">
                    {{ $totalPesanan }} Total
                </span>
            </div>

            <div class="space-y-4">
                <!-- Antar Jemput -->
                <div class="space-y-1.5">
                    <div class="flex items-center justify-between text-xs font-bold">
                        <span class="text-slate-700 flex items-center gap-1.5"><span>🛵</span> Antar Jemput (Pickup Express)</span>
                        <span class="text-slate-900 font-black">{{ $jumlahPickup }} Pesanan ({{ $pctPickup }}%)</span>
                    </div>
                    <div class="w-full h-3 rounded-full bg-slate-100 overflow-hidden">
                        <div class="h-full rounded-full bg-gradient-to-r from-orange-400 to-orange-500 transition-all duration-500" style="width: {{ $pctPickup }}%"></div>
                    </div>
                </div>

                <!-- Reguler -->
                <div class="space-y-1.5">
                    <div class="flex items-center justify-between text-xs font-bold">
                        <span class="text-slate-700 flex items-center gap-1.5"><span>🧺</span> Cuci Reguler &amp; Setrika</span>
                        <span class="text-slate-900 font-black">{{ $jumlahReguler }} Pesanan ({{ $pctReguler }}%)</span>
                    </div>
                    <div class="w-full h-3 rounded-full bg-slate-100 overflow-hidden">
                        <div class="h-full rounded-full bg-gradient-to-r from-blue-500 to-sky-400 transition-all duration-500" style="width: {{ $pctReguler }}%"></div>
                    </div>
                </div>

                <!-- Koin Self Service -->
                <div class="space-y-1.5">
                    <div class="flex items-center justify-between text-xs font-bold">
                        <span class="text-slate-700 flex items-center gap-1.5"><span>🪙</span> Self-Service Koin Mandiri</span>
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
                    <p class="text-xs text-slate-500">Tingkat keberhasilan penyelesaian pesanan laundry.</p>
                </div>
                <span class="text-xs font-black text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-100">
                    {{ $pctSelesai }}% Sukses
                </span>
            </div>

            <div class="space-y-4">
                <!-- Selesai -->
                <div class="space-y-1.5">
                    <div class="flex items-center justify-between text-xs font-bold">
                        <span class="text-slate-700 flex items-center gap-1.5"><span>✅</span> Berhasil Diselesaikan</span>
                        <span class="text-emerald-700 font-black">{{ $pesananSelesai }} ({{ $pctSelesai }}%)</span>
                    </div>
                    <div class="w-full h-3 rounded-full bg-slate-100 overflow-hidden">
                        <div class="h-full rounded-full bg-emerald-500 transition-all duration-500" style="width: {{ $pctSelesai }}%"></div>
                    </div>
                </div>

                <!-- Diproses -->
                <div class="space-y-1.5">
                    <div class="flex items-center justify-between text-xs font-bold">
                        <span class="text-slate-700 flex items-center gap-1.5"><span>⚙️</span> Sedang Dikerjakan</span>
                        <span class="text-sky-700 font-black">{{ $pesananDiproses }} ({{ $pctProses }}%)</span>
                    </div>
                    <div class="w-full h-3 rounded-full bg-slate-100 overflow-hidden">
                        <div class="h-full rounded-full bg-sky-500 transition-all duration-500" style="width: {{ $pctProses }}%"></div>
                    </div>
                </div>

                <!-- Dibatalkan -->
                <div class="space-y-1.5">
                    <div class="flex items-center justify-between text-xs font-bold">
                        <span class="text-slate-700 flex items-center gap-1.5"><span>✕</span> Dibatalkan / Ditolak</span>
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
               class="text-xs font-black text-blue-600 hover:text-blue-800 flex items-center gap-1">
                <span>Lihat Semua Transaksi &amp; Filter Tanggal</span>
                <span>&rarr;</span>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-black uppercase text-slate-500 tracking-wider">
                        <th class="py-3 px-4">Kode Bayar</th>
                        <th class="py-3 px-4">Pesanan</th>
                        <th class="py-3 px-4">Pelanggan</th>
                        <th class="py-3 px-4">Tanggal</th>
                        <th class="py-3 px-4">Nominal</th>
                        <th class="py-3 px-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($pembayarans->take(8) as $b)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-3.5 px-4 font-mono font-bold text-slate-900">#{{ $b->kode_pembayaran }}</td>
                            <td class="py-3.5 px-4 font-bold text-blue-800">#{{ $b->pesanan->kode_pesanan ?? '-' }}</td>
                            <td class="py-3.5 px-4 font-bold text-slate-800">{{ $b->pesanan->user->nama ?? '-' }}</td>
                            <td class="py-3.5 px-4 text-slate-500">{{ $b->created_at ? $b->created_at->format('d M Y, H:i') : '-' }}</td>
                            <td class="py-3.5 px-4 font-black text-slate-900">Rp {{ number_format($b->jumlah_bayar, 0, ',', '.') }}</td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ $b->status_pembayaran === 'Lunas' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ $b->status_pembayaran }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-slate-400 font-semibold">Belum ada data pembayaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
