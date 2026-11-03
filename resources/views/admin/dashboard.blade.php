
@extends('layouts.app')

@section('title', 'Dashboard Admin - ArsyClean')

@section('content')

@php
    // Menghitung pesanan baru
    $pesananBaru = $pesanans->filter(function ($p) {
        return !in_array($p->status_pesanan, [
            'Diproses',
            'Selesai',
            'Dibatalkan'
        ]);
    });

    // Menghitung pesanan aktif
    $aktif = $pesanans->where('status_pesanan', 'Diproses');
@endphp

<div class="space-y-6">

    {{-- NOTIFIKASI --}}
    @if(session('success'))
        <div class="p-4 rounded-xl bg-green-100 text-green-800 text-sm font-semibold">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 rounded-xl bg-red-100 text-red-800 text-sm font-semibold">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-xl bg-red-100 text-red-800 text-sm">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif


    {{-- HEADER DASHBOARD --}}
    <div class="bg-gradient-to-r from-slate-800 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-lg">

        <div class="flex flex-col md:flex-row justify-between gap-6">

            <div>
                <span class="px-3 py-1 rounded-full text-xs font-bold bg-white/20">
                    PANEL ADMIN
                </span>

                <h1 class="text-2xl sm:text-3xl font-black mt-3">
                    Dashboard Operasional Laundry
                </h1>

                <p class="text-sm text-slate-300 mt-2">
                    Kelola seluruh operasional Laundry Reguler.
                </p>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">

                <div class="bg-white/10 p-4 rounded-2xl text-center">
                    <div class="text-2xl font-black">
                        {{ $pesananBaru->count() }}
                    </div>
                    <div class="text-xs text-slate-300">
                        Pesanan Baru
                    </div>
                </div>

                <div class="bg-white/10 p-4 rounded-2xl text-center">
                    <div class="text-2xl font-black">
                        {{ $aktif->count() }}
                    </div>
                    <div class="text-xs text-slate-300">
                        Pesanan Aktif
                    </div>
                </div>

                <div class="bg-white/10 p-4 rounded-2xl text-center">
                    <div class="text-2xl font-black">
                        {{ $pembayarans->where('status_pembayaran', 'Pending')->count() }}
                    </div>
                    <div class="text-xs text-slate-300">
                        Pembayaran Pending
                    </div>
                </div>

            </div>
        </div>
    </div>


    {{-- PESANAN BARU --}}
    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">

        <div class="mb-5">
            <h2 class="text-lg font-bold text-slate-800">
                Pesanan Masuk
            </h2>

            <p class="text-xs text-slate-500 mt-1">
                Periksa data pelanggan sebelum menerima atau menolak pesanan.
            </p>
        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-xs text-left">

                <thead class="bg-slate-50 text-slate-500">
                    <tr>
                        <th class="p-3">Kode Pesanan</th>
                        <th class="p-3">Pelanggan</th>
                        <th class="p-3">Layanan</th>
                        <th class="p-3">Total</th>
                        <th class="p-3">Status</th>
                        <th class="p-3">Tindakan</th>
                    </tr>
                </thead>

                <tbody class="divide-y">

                    @forelse($pesananBaru as $p)

                        <tr>
                            <td class="p-3 font-bold">
                                {{ $p->kode_pesanan }}
                            </td>

                            <td class="p-3">
                                {{ $p->user->nama ?? 'Pelanggan' }}
                                <div class="text-slate-400">
                                    {{ $p->user->no_hp ?? '-' }}
                                </div>
                            </td>

                            <td class="p-3">
                                {{ $p->layanan->first()->nama_layanan ?? '-' }}
                            </td>

                            <td class="p-3 font-semibold">
                                Rp {{ number_format($p->total_harga, 0, ',', '.') }}
                            </td>

                            <td class="p-3">
                                <span class="px-2 py-1 rounded bg-yellow-100 text-yellow-800">
                                    {{ $p->status_pesanan }}
                                </span>
                            </td>

                            <td class="p-3">
                                <div class="flex flex-wrap gap-2">

                                    <form action="{{ route('admin.pesanan.konfirmasi', $p->id_pesanan) }}"
                                          method="POST">
                                        @csrf

                                        <button type="submit"
                                            class="px-3 py-2 bg-green-600 text-white rounded-lg font-bold">
                                            Terima
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.pesanan.tolak', $p->id_pesanan) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin ingin menolak pesanan ini?')">

                                        @csrf

                                        <input type="hidden"
                                               name="alasan"
                                               value="Pesanan ditolak oleh Admin">

                                        <button type="submit"
                                            class="px-3 py-2 bg-red-600 text-white rounded-lg font-bold">
                                            Tolak
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="p-5 text-center text-slate-400">
                                Tidak ada pesanan baru.
                            </td>
                        </tr>

                    @endforelse

                </tbody>
            </table>
        </div>
    </div>


    {{-- PESANAN LAUNDRY REGULER --}}
    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">

        <div class="mb-5">
            <h2 class="text-lg font-bold text-slate-800">
                Laundry Reguler
            </h2>

            <p class="text-xs text-slate-500 mt-1">
                Total {{ $pesanans->count() }} pesanan.
            </p>
        </div>

        <div class="space-y-4">

            @forelse($pesanans as $p)

                <div class="border border-slate-200 rounded-2xl p-4">

                    {{-- INFORMASI PESANAN --}}
                    <div class="flex flex-col md:flex-row justify-between gap-3">

                        <div>
                            <h3 class="font-bold text-slate-800">
                                {{ $p->kode_pesanan }}
                            </h3>

                            <p class="text-xs text-slate-500 mt-1">
                                {{ $p->user->nama ?? 'Pelanggan' }}
                                • {{ $p->user->no_hp ?? '-' }}
                            </p>

                            <p class="text-xs text-slate-500 mt-1">
                                {{ $p->layanan->first()->nama_layanan ?? '-' }}
                            </p>
                        </div>

                        <div class="text-left md:text-right">

                            <p class="font-bold text-slate-800">
                                Rp {{ number_format($p->total_harga, 0, ',', '.') }}
                            </p>

                            <span class="inline-block mt-2 px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-bold">
                                {{ $p->status_pesanan }}
                            </span>

                            <p class="text-xs text-slate-500 mt-2">
                                {{ $p->status_laundry ?? 'Belum Diproses' }}
                            </p>

                        </div>
                    </div>


                    {{-- PROSES LAUNDRY REGULER --}}
                    @if($p->status_pesanan === 'Diproses')

                        <div class="mt-5 pt-4 border-t">

                            <h4 class="font-bold text-sm text-slate-700 mb-3">
                                Perbarui Status Pengerjaan
                            </h4>

                            <form action="{{ route('admin.pesanan.status', $p->id_pesanan) }}"
                                  method="POST"
                                  class="flex flex-col sm:flex-row gap-2">

                                @csrf

                                <select name="status_laundry"
                                        class="border rounded-lg px-3 py-2 text-sm flex-1">

                                    @foreach([
                                        'Menunggu Antrean',
                                        'Pencucian',
                                        'Pengeringan',
                                        'Setrika',
                                        'Siap Diambil',
                                        'Selesai'
                                    ] as $status)

                                        <option value="{{ $status }}"
                                            {{ $p->status_laundry === $status ? 'selected' : '' }}>
                                            {{ $status }}
                                        </option>

                                    @endforeach

                                </select>

                                <button type="submit"
                                        class="px-4 py-2 bg-slate-800 text-white rounded-lg font-bold">
                                    Update Status
                                </button>

                            </form>

                        </div>

                    @endif

                </div>

            @empty

                <div class="p-5 text-center text-slate-400 text-sm">
                    Belum ada pesanan Laundry Reguler.
                </div>

            @endforelse

        </div>
    </div>


    {{-- VERIFIKASI PEMBAYARAN --}}
    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">

        <h2 class="text-lg font-bold text-slate-800 mb-4">
            Verifikasi Pembayaran
        </h2>

        <div class="space-y-3">

            @forelse($pembayarans as $byr)

                <div class="flex flex-col sm:flex-row justify-between gap-3 p-4 bg-slate-50 border rounded-xl">

                    <div>

                        <p class="font-bold text-slate-800">
                            {{ $byr->kode_pembayaran }}
                        </p>

                        <p class="text-xs text-slate-500 mt-1">
                            {{ $byr->user->nama ?? 'Pelanggan' }}
                        </p>

                        <p class="text-xs text-slate-500">
                            {{ $byr->metode_pembayaran }}
                        </p>

                        <p class="font-bold text-slate-800 mt-2">
                            Rp {{ number_format($byr->jumlah_bayar, 0, ',', '.') }}
                        </p>

                    </div>

                    <div class="flex items-center">

                        @if($byr->status_pembayaran === 'Lunas')

                            <span class="px-3 py-2 bg-green-100 text-green-700 rounded-lg font-bold text-xs">
                                LUNAS
                            </span>

                        @else

                            <form action="{{ route('admin.pembayaran.verifikasi', $byr->id_pembayaran) }}"
                                  method="POST"
                                  onsubmit="return confirm('Verifikasi pembayaran ini?')">

                                @csrf

                                <button type="submit"
                                        class="px-4 py-2 bg-green-600 text-white rounded-lg font-bold text-xs">
                                    Verifikasi Pembayaran
                                </button>

                            </form>

                        @endif

                    </div>

                </div>

            @empty

                <p class="text-center text-sm text-slate-400">
                    Belum ada transaksi pembayaran.
                </p>

            @endforelse

        </div>
    </div>


    {{-- MANAJEMEN STOK BAHAN BAKU --}}
    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">

        <h2 class="text-lg font-bold text-slate-800 mb-4">
            Manajemen Stok Bahan Baku
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            @forelse($stoks as $stk)

                <div class="p-4 bg-slate-50 rounded-xl border">

                    <div class="flex justify-between mb-3">

                        <div>
                            <p class="font-bold text-slate-800 text-sm">
                                {{ $stk->nama_barang }}
                            </p>

                            <p class="text-xs text-slate-500">
                                Batas minimum: {{ $stk->batas_minimum }} {{ $stk->satuan }}
                            </p>
                        </div>

                        <span class="text-xs font-bold
                            {{ $stk->status === 'Aman' ? 'text-green-600' : 'text-red-600' }}">
                            {{ $stk->status }}
                        </span>

                    </div>

                    <form action="{{ route('admin.stok.update', $stk->id_stok) }}"
                          method="POST"
                          class="flex gap-2">

                        @csrf

                        <input type="number"
                               name="jumlah"
                               value="{{ $stk->jumlah }}"
                               min="0"
                               step="0.5"
                               required
                               class="border rounded-lg px-3 py-2 text-sm w-full">

                        <button type="submit"
                                class="px-4 py-2 bg-slate-800 text-white rounded-lg font-bold text-xs">
                            Simpan
                        </button>

                    </form>

                </div>

            @empty

                <p class="text-sm text-slate-400">
                    Belum ada data stok bahan baku.
                </p>

            @endforelse

        </div>
    </div>

</div>

@endsection