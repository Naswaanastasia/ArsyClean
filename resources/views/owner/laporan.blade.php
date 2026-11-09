@extends('layouts.app')

@section('title', 'Laporan Keuangan - ArsyClean Owner')
@section('page_title', 'Laporan Keuangan & Rekap Transaksi')

@section('content')
<div class="space-y-7">

    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-[#0f2a5c] via-[#163a78] to-[#0a1e42] rounded-3xl p-6 sm:p-8 text-white shadow-xl flex flex-col md:flex-row items-start md:items-center justify-between gap-4 border border-blue-900/40">
        <div>
            <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-amber-400 text-amber-950 mb-2 shadow-xs">
                <svg class="w-4 h-4 text-amber-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Buku Kas &amp; Omzet
            </div>
            <h1 class="text-2xl sm:text-3xl font-black tracking-tight">Laporan Keuangan &amp; Transaksi</h1>
            <p class="text-xs sm:text-sm text-blue-100 mt-1 max-w-xl font-medium">
                Filter transaksi berdasarkan rentang tanggal dan cetak dokumen laporan resmi berformat PDF untuk arsip pembukuan.
            </p>
        </div>

        <a href="{{ route('owner.cetak.laporan', ['tanggal_mulai' => request('tanggal_mulai'), 'tanggal_selesai' => request('tanggal_selesai')]) }}"
           target="_blank"
           class="px-5 py-3 rounded-2xl bg-amber-400 hover:bg-amber-300 text-amber-950 font-black text-xs shadow-md shadow-amber-400/25 transition-all flex items-center gap-2 shrink-0">
            <svg class="w-4 h-4 text-amber-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            <span>Cetak Dokumen PDF</span>
        </a>
    </div>

    <!-- Filter Rentang Tanggal & Ringkasan Omzet -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-xs">
        <form method="GET" action="{{ route('owner.laporan') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-end">
            <div class="sm:col-span-4">
                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                    Dari Tanggal:
                </label>
                <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}"
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-800 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="sm:col-span-4">
                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                    Sampai Tanggal:
                </label>
                <input type="date" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}"
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-800 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="sm:col-span-4 flex items-center gap-2">
                <button type="submit"
                        class="flex-1 py-2.5 px-4 bg-blue-600 hover:bg-blue-700 active:scale-95 text-white font-black text-xs rounded-xl shadow-xs transition-all">
                    Terapkan Filter
                </button>
                <a href="{{ route('owner.laporan') }}"
                   class="py-2.5 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition-all">
                    Reset
                </a>
            </div>
        </form>

        <div class="mt-6 pt-5 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="text-xs text-slate-500 font-bold">
                Periode: <b class="text-slate-800">{{ request('tanggal_mulai') ? request('tanggal_mulai') . ' s/d ' . request('tanggal_selesai') : 'Semua Waktu' }}</b>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs text-slate-400 font-bold uppercase">Total Omzet Lunas Periode Ini:</span>
                <span class="text-xl sm:text-2xl font-black text-emerald-700">Rp {{ number_format($totalOmzet, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <!-- Tabel Rincian Transaksi -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-xs font-black uppercase text-slate-600 tracking-wider">
                        <th class="py-4 px-5">Kode Bayar</th>
                        <th class="py-4 px-5">Pesanan</th>
                        <th class="py-4 px-5">Pelanggan</th>
                        <th class="py-4 px-5">Layanan</th>
                        <th class="py-4 px-5">Tanggal Transaksi</th>
                        <th class="py-4 px-5">Nominal</th>
                        <th class="py-4 px-5">Status</th>
                        <th class="py-4 px-5">Verifikator</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-medium">
                    @forelse($pembayarans as $bayar)
                        @php
                            $isLunas = $bayar->status_pembayaran === 'Lunas';
                        @endphp
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-4 px-5 font-mono font-bold text-slate-900">
                                #{{ $bayar->kode_pembayaran }}
                            </td>
                            <td class="py-4 px-5 font-mono font-bold text-blue-900">
                                #{{ $bayar->pesanan->kode_pesanan ?? '-' }}
                            </td>
                            <td class="py-4 px-5 font-bold text-slate-800">
                                {{ $bayar->pesanan->user->nama ?? '-' }}
                            </td>
                            <td class="py-4 px-5 text-slate-600">
                                {{ $bayar->pesanan?->layanan->pluck('nama_layanan')->implode(', ') ?? '-' }}
                            </td>
                            <td class="py-4 px-5 text-slate-500 font-semibold">
                                {{ $bayar->created_at ? $bayar->created_at->format('d M Y, H:i') : '-' }}
                            </td>
                            <td class="py-4 px-5 font-black text-slate-900 text-sm">
                                Rp {{ number_format($bayar->jumlah_bayar, 0, ',', '.') }}
                            </td>
                            <td class="py-4 px-5">
                                <span class="px-3 py-1 rounded-full text-xs font-black border {{ $isLunas ? 'bg-emerald-100 text-emerald-950 border-emerald-300' : 'bg-amber-100 text-amber-950 border-amber-300' }}">
                                    {{ $bayar->status_pembayaran }}
                                </span>
                            </td>
                            <td class="py-4 px-5 text-slate-600 font-semibold">
                                {{ $bayar->verifikator ?? 'Sistem' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400 font-semibold">
                                Tidak ada transaksi yang ditemukan untuk rentang tanggal ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
