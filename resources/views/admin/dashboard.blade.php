@extends('layouts.app')

@section('title', 'Dashboard Operasional Admin - Laravel ArsyClean')

@section('content')
<div class="space-y-6">
    <!-- Top Summary Banner -->
    <div class="bg-gradient-to-r from-slate-800 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-lg flex flex-col md:flex-row items-center justify-between gap-6">
        <div>
            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-white/20 uppercase tracking-wider mb-2 text-slate-200">
                Panel Staf Operasional & Kasir
            </span>
            <h1 class="text-2xl sm:text-3xl font-black">Kelola Antrean, Stok & Transaksi Kasir</h1>
            <p class="text-xs text-slate-300 max-w-xl leading-relaxed mt-1">
                Sistem alur bisnis terkontrol: Menunggu Antrean &rarr; Pencucian &rarr; Pengeringan &rarr; Setrika &rarr; Siap Diambil &rarr; Pengiriman.
            </p>
        </div>
        <div class="flex gap-4">
            <div class="bg-white/10 p-3 rounded-2xl text-center border border-white/10 min-w-24">
                <div class="text-2xl font-black text-sky-400">{{ $pesanans->where('status_laundry', '!=', 'Selesai')->count() }}</div>
                <div class="text-[10px] text-slate-300 font-semibold">Aktif Dicuci</div>
            </div>
            <div class="bg-white/10 p-3 rounded-2xl text-center border border-white/10 min-w-24">
                <div class="text-2xl font-black text-emerald-400">{{ $pembayarans->where('status_pembayaran', 'Pending')->count() }}</div>
                <div class="text-[10px] text-slate-300 font-semibold">Kasir Pending</div>
            </div>
        </div>
    </div>

    <!-- Active Laundry Orders Management -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-xs">
        <h3 class="text-lg font-bold text-slate-800 mb-4">Antrean Proses Cucian Masuk (Status Laundry)</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="p-3">Kode</th>
                        <th class="p-3">Pelanggan</th>
                        <th class="p-3">Layanan</th>
                        <th class="p-3">Status Saat Ini</th>
                        <th class="p-3">Ubah Status Bertahap</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($pesanans as $p)
                    <tr class="hover:bg-slate-50">
                        <td class="p-3 font-mono font-bold text-slate-900">{{ $p->kode_pesanan }}</td>
                        <td class="p-3 font-semibold text-slate-800">
                            {{ $p->user->nama ?? 'Pelanggan' }}
                            <div class="text-[10px] text-slate-400">{{ $p->user->no_hp ?? '-' }}</div>
                        </td>
                        <td class="p-3 text-slate-700">
                            {{ $p->layanan->first()->nama_layanan ?? 'Reguler' }}
                        </td>
                        <td class="p-3">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-sky-50 text-sky-700 border border-sky-200">
                                {{ $p->status_laundry }}
                            </span>
                        </td>
                        <td class="p-3">
                            <form action="{{ route('admin.pesanan.status', $p->id_pesanan) }}" method="POST" class="flex items-center gap-2">
                                @csrf
                                <select name="status_laundry" class="px-2.5 py-1 bg-slate-50 border rounded-lg text-xs">
                                    <option value="Menunggu Antrean" {{ $p->status_laundry === 'Menunggu Antrean' ? 'selected' : '' }}>Menunggu Antrean</option>
                                    <option value="Pencucian" {{ $p->status_laundry === 'Pencucian' ? 'selected' : '' }}>Pencucian</option>
                                    <option value="Pengeringan" {{ $p->status_laundry === 'Pengeringan' ? 'selected' : '' }}>Pengeringan</option>
                                    <option value="Setrika" {{ $p->status_laundry === 'Setrika' ? 'selected' : '' }}>Setrika</option>
                                    <option value="Siap Diambil" {{ $p->status_laundry === 'Siap Diambil' ? 'selected' : '' }}>Siap Diambil</option>
                                    <option value="Dalam Pengiriman" {{ $p->status_laundry === 'Dalam Pengiriman' ? 'selected' : '' }}>Dalam Pengiriman</option>
                                    <option value="Selesai" {{ $p->status_laundry === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                </select>
                                <button type="submit" class="px-2.5 py-1 bg-slate-800 hover:bg-slate-900 text-white font-bold rounded-lg text-[11px]">
                                    Update
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Stock and Cashier Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Stock Management -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-xs">
            <h3 class="text-base font-bold text-slate-800 mb-3">Data Stok Bahan Baku & Koin Token</h3>
            <div class="space-y-3">
                @foreach($stoks as $stk)
                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-slate-200 text-xs">
                    <div>
                        <div class="font-bold text-slate-800">{{ $stk->nama_barang }}</div>
                        <div class="text-[10px] text-slate-400">Min: {{ $stk->batas_minimum }} {{ $stk->satuan }}</div>
                    </div>
                    <form action="{{ route('admin.stok.update', $stk->id_stok) }}" method="POST" class="flex items-center gap-1.5">
                        @csrf
                        <input type="number" name="jumlah" value="{{ $stk->jumlah }}" step="0.5" class="w-16 px-2 py-1 bg-white border rounded text-right font-bold">
                        <span class="text-[10px] text-slate-500">{{ $stk->satuan }}</span>
                        <button type="submit" class="px-2 py-1 bg-slate-800 text-white rounded font-bold text-[10px]">
                            Simpan
                        </button>
                    </form>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Cashier Verification -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-xs">
            <h3 class="text-base font-bold text-slate-800 mb-3">Verifikasi Kasir & Pembayaran Masuk</h3>
            <div class="space-y-3">
                @foreach($pembayarans->take(5) as $byr)
                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-slate-200 text-xs">
                    <div>
                        <div class="font-bold text-slate-900">#{{ $byr->kode_pembayaran }} - Rp {{ number_format($byr->jumlah_bayar, 0, ',', '.') }}</div>
                        <div class="text-[10px] text-slate-400">{{ $byr->metode_pembayaran }} &bull; {{ $byr->user->nama ?? 'Pelanggan' }}</div>
                    </div>
                    <div>
                        @if($byr->status_pembayaran === 'Lunas')
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">Lunas</span>
                        @else
                            <form action="{{ route('admin.pembayaran.verifikasi', $byr->id_pembayaran) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded font-bold text-[10px]">
                                    Verifikasi Lunas
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
