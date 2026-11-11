@extends('layouts.app')

@section('title', 'Dashboard Operasional Admin - ArsyClean')

@section('content')
<div class="space-y-7">

    @if(isset($errors) && $errors->any())
        <div class="p-4 rounded-2xl bg-red-50 border border-red-200 text-red-900 text-sm font-semibold space-y-1 shadow-xs">
            <div class="font-black flex items-center gap-2">
                <svg class="w-5 h-5 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <span>Mohon periksa kembali input formulir:</span>
            </div>
            <ul class="list-disc list-inside text-xs pl-2 space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- HEADER DASHBOARD OPERASIONAL ADMIN --}}
    <div class="bg-gradient-to-r from-[#0f2a5c] via-[#163a78] to-[#0a1e42] rounded-3xl p-6 sm:p-8 text-white shadow-xl flex flex-col md:flex-row items-start md:items-center justify-between gap-6 border border-blue-900/40">
        <div>
            <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-white/15 text-sky-200 mb-2 border border-white/20">
                <svg class="w-4 h-4 text-sky-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                Panel Operasional &amp; Kasir
            </div>
            <h1 class="text-2xl sm:text-3xl font-black tracking-tight">Dashboard Operasional Laundry</h1>
            <p class="text-sm text-blue-100 max-w-xl mt-1 leading-relaxed font-medium">
                Kelola pesanan masuk, perbarui alur pengerjaan laundry bertahap, jadwalkan kurir penjemputan/pengantaran, dan pantau stok bahan baku.
            </p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 w-full md:w-auto shrink-0">
            <div class="bg-white/10 p-4 rounded-2xl border border-white/20 text-center">
                <div class="text-2xl sm:text-3xl font-black text-amber-300">{{ $pesananMasuk->count() }}</div>
                <div class="text-xs text-slate-200 font-bold uppercase tracking-wider mt-0.5">Pesanan Masuk</div>
            </div>

            <div class="bg-white/10 p-4 rounded-2xl border border-white/20 text-center">
                <div class="text-2xl sm:text-3xl font-black text-sky-300">{{ $pesananDiproses->count() }}</div>
                <div class="text-xs text-slate-200 font-bold uppercase tracking-wider mt-0.5">Dalam Proses</div>
            </div>

            <div class="bg-white/10 p-4 rounded-2xl border border-white/20 text-center col-span-2 sm:col-span-1">
                <div class="text-2xl sm:text-3xl font-black text-emerald-300">
                    {{ $pembayarans->where('status_pembayaran', 'Pending')->count() }}
                </div>
                <div class="text-xs text-slate-200 font-bold uppercase tracking-wider mt-0.5">Bayar Pending</div>
            </div>
        </div>
    </div>

    {{-- ================= SECTION 1: PESANAN MASUK (MENUNGGU KONFIRMASI) ================= --}}
    <div id="pesanan-masuk" class="scroll-mt-6 bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100">
            <div>
                <h2 class="text-xl font-black text-slate-900 flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                    </span>
                    Pesanan Masuk (Menunggu Verifikasi &amp; Konfirmasi)
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Pesanan baru yang dikirim pelanggan. Periksa jadwal penjemputan, rincian pakaian, dan catatan khusus.
                </p>
            </div>
            <span class="px-4 py-1.5 rounded-full text-xs font-black bg-amber-50 text-amber-800 border border-amber-300 self-start sm:self-auto">
                {{ $pesananMasuk->count() }} Pesanan Baru
            </span>
        </div>

        <div class="space-y-4">
            @forelse($pesananMasuk as $p)
                <div class="p-5 bg-slate-50 hover:bg-slate-50/80 border border-slate-200 rounded-3xl transition-all">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                        <div class="space-y-2 flex-1">
                            <div class="flex items-center gap-3 flex-wrap">
                                <span class="font-mono text-base font-black text-blue-950">#{{ $p->kode_pesanan }}</span>
                                <span class="px-3 py-0.5 rounded-full text-xs font-black bg-amber-100 text-amber-900 border border-amber-300">
                                    {{ $p->status_pesanan }}
                                </span>
                                <span class="text-xs font-bold text-slate-500">
                                    {{ $p->tanggal_pesanan ? $p->tanggal_pesanan->format('d M Y') : '-' }}
                                </span>
                            </div>

                            <div class="text-sm font-bold text-slate-800">
                                Pelanggan: <span class="text-blue-700 font-black">{{ $p->user->nama ?? 'Pelanggan' }}</span>
                                &bull; <span class="text-slate-600 font-semibold">{{ $p->user->no_hp ?? '-' }}</span>
                            </div>

                            <div class="text-xs text-slate-600 font-medium">
                                <b>Layanan:</b> {{ $p->layanan->first()->nama_layanan ?? '-' }}
                                @if($p->layanan->first())
                                    ({{ $p->layanan->first()->pivot->jumlah }} {{ $p->layanan->first()->satuan }})
                                @endif
                                &bull; <b>Total Tagihan:</b> <span class="text-slate-900 font-black">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</span>
                                &bull; <b>Metode:</b> <span class="font-bold text-blue-800">{{ $p->metode_pembayaran }}</span>
                            </div>

                            <div class="text-xs text-slate-600 font-medium">
                                <b>Alamat Penjemputan:</b> {{ $p->alamat_penjemputan ?? '-' }}
                            </div>

                            {{-- CATATAN PELANGGAN --}}
                            @if(!empty($p->catatan))
                                <div class="mt-2 p-3.5 bg-amber-50/80 border border-amber-200 rounded-2xl text-xs text-amber-950">
                                    <div class="font-black flex items-center gap-1.5 mb-1 text-amber-900">
                                        <svg class="w-4 h-4 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        <span>Catatan Dari Pelanggan:</span>
                                    </div>
                                    <div class="whitespace-pre-line text-slate-700 font-semibold pl-5 leading-relaxed">{{ $p->catatan }}</div>
                                </div>
                            @endif
                        </div>

                        <!-- Tombol Aksi Terima & Tolak -->
                        <div class="flex items-center gap-2 pt-2 lg:pt-0 shrink-0">
                            <form action="{{ route('admin.pesanan.konfirmasi', $p->id_pesanan) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white rounded-xl font-black text-xs shadow-md shadow-emerald-600/25 transition-all flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                    <span>Terima Pesanan</span>
                                </button>
                            </form>

                            <button type="button"
                                    onclick="openTolakModal('{{ $p->id_pesanan }}', '{{ $p->kode_pesanan }}')"
                                    class="px-4 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-xl font-bold text-xs transition-all">
                                Tolak
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                    <p class="text-sm font-semibold text-slate-500">Tidak ada pesanan masuk yang menunggu konfirmasi saat ini.</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- ================= SECTION 2: DATA PESANAN DALAM PROSES (OPERASIONAL) ================= --}}
    <div id="dalam-proses" class="scroll-mt-6 bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100">
            <div>
                <h2 class="text-xl font-black text-slate-900 flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-xl bg-sky-100 text-sky-700 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </span>
                    Data Pesanan Dalam Proses (Pengerjaan Laundry)
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Pesanan yang sedang dikerjakan. Perbarui tahap pencucian sesuai alur operasional.
                </p>
            </div>
            <span class="px-4 py-1.5 rounded-full text-xs font-black bg-sky-50 text-sky-800 border border-sky-300 self-start sm:self-auto">
                {{ $pesananDiproses->count() }} Sedang Dikerjakan
            </span>
        </div>

        <div class="space-y-5">
            @forelse($pesananDiproses as $p)
                @php
                    $statusLaundry = $p->status_laundry ?? 'Menunggu Antrean';
                    $badgeWarna = match ($statusLaundry) {
                        'Menunggu Antrean' => 'bg-amber-100 text-amber-950 border-2 border-amber-300 font-bold',
                        'Pencucian'        => 'bg-yellow-100 text-yellow-950 border-2 border-yellow-400 font-black',
                        'Pengeringan'      => 'bg-sky-100 text-sky-950 border-2 border-sky-400 font-black',
                        'Setrika'          => 'bg-rose-100 text-rose-950 border-2 border-rose-400 font-black',
                        'Siap Diambil'     => 'bg-purple-100 text-purple-950 border-2 border-purple-400 font-black',
                        'Siap Dikirim'     => 'bg-orange-100 text-orange-950 border-2 border-orange-400 font-black',
                        'Dalam Pengiriman' => 'bg-blue-600 text-white border-2 border-blue-700 font-black',
                        'Selesai'          => 'bg-emerald-600 text-white border-2 border-emerald-700 font-black',
                        default            => 'bg-slate-100 text-slate-800 border-2 border-slate-300 font-bold',
                    };
                @endphp

                <div class="p-6 bg-slate-50 border border-slate-200 rounded-3xl space-y-4">
                    <!-- Ringkasan Info Pesanan -->
                    <div class="flex flex-col md:flex-row justify-between gap-4">
                        <div class="space-y-1.5">
                            <div class="flex items-center gap-3 flex-wrap">
                                <h3 class="font-mono text-lg font-black text-slate-900">#{{ $p->kode_pesanan }}</h3>
                                <span class="px-3.5 py-1 rounded-full text-xs shadow-xs {{ $badgeWarna }}">
                                    Tahap: {{ $statusLaundry }}
                                </span>
                            </div>

                            <p class="text-sm font-bold text-slate-800">
                                Pelanggan: <span class="text-blue-700 font-black">{{ $p->user->nama ?? 'Pelanggan' }}</span>
                                <span class="text-slate-500 font-medium">({{ $p->user->no_hp ?? '-' }})</span>
                            </p>

                            <p class="text-xs text-slate-600 font-medium">
                                Layanan: <b>{{ $p->layanan->first()->nama_layanan ?? '-' }}</b>
                                @if($p->layanan->first())
                                    ({{ $p->layanan->first()->pivot->jumlah }} {{ $p->layanan->first()->satuan }})
                                @endif
                                &bull; Alamat: {{ $p->alamat_penjemputan ?? '-' }}
                            </p>
                        </div>

                        <div class="text-left md:text-right">
                            <div class="text-xs text-slate-400 font-bold uppercase">Total Tagihan</div>
                            <div class="text-2xl font-black text-slate-900">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</div>
                            <div class="mt-1">
                                <span class="px-3 py-1 rounded-full text-xs font-black
                                    {{ ($p->pembayaran?->status_pembayaran ?? 'Pending') === 'Lunas' ? 'bg-emerald-100 text-emerald-900 border border-emerald-300' : 'bg-amber-100 text-amber-900 border border-amber-300' }}">
                                    Bayar: {{ $p->pembayaran?->status_pembayaran ?? 'Pending' }} ({{ $p->metode_pembayaran }})
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- CATATAN PELANGGAN --}}
                    @if(!empty($p->catatan))
                        <div class="p-3.5 bg-white border border-slate-200 rounded-2xl text-xs text-slate-800 font-medium">
                            <div class="font-black text-slate-900 flex items-center gap-1.5 mb-1">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                <span>Catatan Pelanggan:</span>
                            </div>
                            <div class="whitespace-pre-line pl-5 leading-relaxed">{{ $p->catatan }}</div>
                        </div>
                    @endif

                    <!-- Form Update Status Laundry -->
                    <div class="pt-4 border-t border-slate-200 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">
                        <form action="{{ route('admin.pesanan.status', $p->id_pesanan) }}"
                              method="POST"
                              class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5 flex-1">
                            @csrf
                            <label class="text-xs font-black uppercase text-slate-700 shrink-0">
                                Update Status Pengerjaan <span class="text-red-500 font-bold">*</span>:
                            </label>

                            <select name="status_laundry"
                                    required
                                    class="px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-black text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 flex-1">
                                @foreach([
                                    'Menunggu Antrean',
                                    'Pencucian',
                                    'Pengeringan',
                                    'Setrika',
                                    'Siap Diambil',
                                    'Siap Dikirim',
                                    'Dalam Pengiriman',
                                    'Selesai'
                                ] as $st)
                                    <option value="{{ $st }}" {{ $p->status_laundry === $st ? 'selected' : '' }}>
                                        {{ $st }}
                                    </option>
                                @endforeach
                            </select>

                            <button type="submit"
                                    class="px-5 py-2.5 bg-slate-900 hover:bg-black active:scale-95 text-white rounded-xl text-xs font-black shadow-xs transition-all flex items-center justify-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span>Simpan Status</span>
                            </button>
                        </form>

                        <!-- Shortcut Penugasan Kurir Pengiriman -->
                        @if(!$p->pengiriman)
                            <button type="button"
                                    onclick="openKurirModal('{{ $p->id_pesanan }}', '{{ $p->kode_pesanan }}', '{{ addslashes($p->alamat_penjemputan ?? '') }}')"
                                    class="px-4 py-2.5 bg-sky-50 hover:bg-sky-100 text-sky-800 border border-sky-300 rounded-xl text-xs font-black transition-all shrink-0 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-sky-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path></svg>
                                <span>Tugaskan Kurir</span>
                            </button>
                        @else
                            <div class="text-xs text-slate-700 shrink-0 bg-white px-3.5 py-2 rounded-xl border border-slate-200 font-semibold">
                                Kurir: <b>{{ $p->pengiriman->nama_kurir }}</b> ({{ $p->pengiriman->status_pengiriman }})
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-8 text-center bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                    <p class="text-sm font-semibold text-slate-500">Tidak ada pesanan yang sedang dalam proses pengerjaan.</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- ================= SECTION 3: VERIFIKASI PEMBAYARAN ================= --}}
    <div id="verifikasi-bayar" class="scroll-mt-6 bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100">
            <div>
                <h2 class="text-xl font-black text-slate-900 flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    </span>
                    Verifikasi Pembayaran Pelanggan
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Validasi pembayaran manual / QRIS by_onest dan pembayaran tunai saat kurir atau kasir menerima pembayaran.
                </p>
            </div>
            <span class="px-4 py-1.5 rounded-full text-xs font-black bg-emerald-50 text-emerald-800 border border-emerald-300 self-start sm:self-auto">
                Total Transaksi: {{ $pembayarans->count() }}
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-600 uppercase text-xs border-b border-slate-200 font-black">
                    <tr>
                        <th class="p-3.5">Kode Bayar</th>
                        <th class="p-3.5">Pesanan</th>
                        <th class="p-3.5">Pelanggan</th>
                        <th class="p-3.5">Metode</th>
                        <th class="p-3.5">Nominal</th>
                        <th class="p-3.5">Bukti Transfer</th>
                        <th class="p-3.5">Status</th>
                        <th class="p-3.5 text-center">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-xs">
                    @forelse($pembayarans as $byr)
                        @php
                            $buktiImg = $byr->bukti_transfer ?? $byr->bukti_pembayaran;
                            $buktiUrl = $buktiImg ? (str_starts_with($buktiImg, 'http') ? $buktiImg : asset('storage/' . $buktiImg)) : null;
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="p-3.5 font-mono font-black text-slate-900">#{{ $byr->kode_pembayaran }}</td>
                            <td class="p-3.5 font-bold text-blue-900">#{{ $byr->pesanan->kode_pesanan ?? '-' }}</td>
                            <td class="p-3.5">
                                <div class="font-bold text-slate-900">{{ $byr->pesanan->user->nama ?? 'Pelanggan' }}</div>
                                <div class="text-[11px] text-slate-400 font-normal">{{ $byr->pesanan->user->no_hp ?? '-' }}</div>
                            </td>
                            <td class="p-3.5 font-bold text-slate-700">{{ $byr->metode_pembayaran }}</td>
                            <td class="p-3.5 font-black text-slate-900 text-sm">Rp {{ number_format($byr->jumlah_bayar, 0, ',', '.') }}</td>
                            <td class="p-3.5">
                                @if(!empty($buktiUrl))
                                    <button type="button"
                                            onclick="previewBuktiAdmin('{{ $buktiUrl }}', '{{ $byr->kode_pembayaran }}', '{{ $byr->pesanan->kode_pesanan ?? '' }}', '{{ number_format($byr->jumlah_bayar, 0, ',', '.') }}')"
                                            class="px-3 py-1.5 rounded-xl bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-200 text-xs font-bold inline-flex items-center gap-1.5 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <span>Lihat Foto</span>
                                    </button>
                                @else
                                    <span class="text-xs text-slate-400 italic">
                                        {{ $byr->metode_pembayaran === 'Tunai' ? 'Bayar di tempat' : 'Belum diunggah' }}
                                    </span>
                                @endif
                            </td>
                            <td class="p-3.5">
                                <span class="px-3 py-1 rounded-full text-xs font-black
                                    {{ $byr->status_pembayaran === 'Lunas' ? 'bg-emerald-100 text-emerald-900 border border-emerald-300' : ($byr->status_pembayaran === 'Pending' ? 'bg-amber-100 text-amber-900 border border-amber-300' : 'bg-red-100 text-red-900 border border-red-300') }}">
                                    {{ $byr->status_pembayaran }}
                                </span>
                            </td>
                            <td class="p-3.5 text-center">
                                @if($byr->status_pembayaran === 'Lunas')
                                    <span class="text-xs font-black text-emerald-700">✓ Sudah Lunas</span>
                                @else
                                    <form action="{{ route('admin.pembayaran.verifikasi', $byr->id_pembayaran) }}"
                                          method="POST"
                                          onsubmit="return confirm('Konfirmasi bahwa pembayaran #{{ $byr->kode_pembayaran }} sebesar Rp {{ number_format($byr->jumlah_bayar, 0, ',', '.') }} telah diterima valid?')">
                                        @csrf
                                        <button type="submit"
                                                class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white rounded-xl font-black text-xs shadow-xs transition-all">
                                            Verifikasi Lunas
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-8 text-center text-slate-400 font-semibold">Belum ada transaksi pembayaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ================= SECTION 4: MANAJEMEN STOK BAHAN BAKU ================= --}}
    <div id="stok-bahan" class="scroll-mt-6 bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100">
            <div>
                <h2 class="text-xl font-black text-slate-900 flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-xl bg-purple-100 text-purple-800 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </span>
                    Manajemen Stok Bahan Baku Laundry
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Pantau ketersediaan deterjen, pewangi, plastik, dan perbarui jumlah stok riil harian.
                </p>
            </div>
            <span class="px-4 py-1.5 rounded-full text-xs font-black bg-purple-50 text-purple-800 border border-purple-300 self-start sm:self-auto">
                Total Bahan: {{ $stoks->count() }} Jenis
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse($stoks as $stk)
                <div class="p-5 bg-slate-50 rounded-2xl border border-slate-200 space-y-3">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <h4 class="font-bold text-base text-slate-900">{{ $stk->nama_barang }}</h4>
                            <p class="text-xs text-slate-500 font-medium">Batas Minimum: {{ $stk->batas_minimum }} {{ $stk->satuan }}</p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-black
                            {{ $stk->status === 'Aman' ? 'bg-emerald-100 text-emerald-900 border border-emerald-300' : ($stk->status === 'Menipis' ? 'bg-amber-100 text-amber-900 border border-amber-300' : 'bg-red-100 text-red-900 border border-red-300') }}">
                            {{ $stk->status }}
                        </span>
                    </div>

                    <form action="{{ route('admin.stok.update', $stk->id_stok) }}" method="POST" class="pt-2">
                        @csrf
                        <label class="block text-xs font-black text-slate-700 mb-1">
                            Jumlah Tersedia ({{ $stk->satuan }}) <span class="text-red-500 font-bold">*</span>
                        </label>
                        <div class="flex gap-2">
                            <input type="number"
                                   name="jumlah"
                                   value="{{ $stk->jumlah }}"
                                   min="0"
                                   step="0.5"
                                   required
                                   class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-black text-slate-900 focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <button type="submit"
                                    class="px-5 py-2.5 bg-slate-900 hover:bg-black text-white rounded-xl text-xs font-black transition-all shrink-0">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            @empty
                <div class="col-span-full p-8 text-center text-slate-400 font-semibold">
                    Belum ada data stok bahan baku.
                </div>
            @endforelse
        </div>
    </div>

</div>

{{-- MODAL PREVIEW BUKTI TRANSFER --}}
<div id="modalPreviewBuktiAdmin" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/70 backdrop-blur-sm p-4 hidden">
    <div class="bg-white w-full max-w-lg rounded-3xl shadow-2xl p-6 border border-slate-200">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
            <div>
                <h3 class="text-lg font-black text-slate-900" id="previewAdminTitle">Bukti Pembayaran</h3>
                <p class="text-xs text-slate-500 mt-0.5" id="previewAdminSubtitle">-</p>
            </div>
            <button type="button" onclick="closePreviewBuktiAdmin()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 font-black text-sm">✕</button>
        </div>
        <div class="p-2 bg-slate-50 rounded-2xl border border-slate-200 max-h-[65vh] overflow-auto text-center">
            <img id="previewAdminImage" src="" alt="Bukti Pembayaran" class="mx-auto rounded-xl object-contain max-h-[60vh] shadow-sm">
        </div>
        <div class="mt-4 flex justify-end">
            <button type="button" onclick="closePreviewBuktiAdmin()" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl">
                Tutup
            </button>
        </div>
    </div>
</div>

{{-- MODAL TOLAK PESANAN --}}
<div id="modalTolak" class="hidden fixed inset-0 z-50 bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-7 shadow-2xl space-y-4 border border-slate-200">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <h3 class="text-lg font-black text-slate-900">Tolak Pesanan #<span id="tolakKodePesanan"></span></h3>
            <button type="button" onclick="closeTolakModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 font-black text-sm">✕</button>
        </div>
        <form id="formTolak" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-bold text-slate-800 mb-1.5">
                    Alasan Penolakan <span class="text-red-500 font-bold">*</span>
                </label>
                <textarea name="alasan"
                          rows="3"
                          required
                          placeholder="Contoh: Alamat di luar jangkauan kurir atau antrean cucian penuh..."
                          class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500"></textarea>
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="closeTolakModal()" class="px-4 py-2.5 rounded-xl bg-slate-100 text-slate-700 font-bold text-xs">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs shadow-md shadow-rose-600/30">
                    Konfirmasi Tolak Pesanan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL PENUGASAN KURIR PENGIRIMAN --}}
<div id="modalKurir" class="hidden fixed inset-0 z-50 bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-7 shadow-2xl space-y-4 border border-slate-200">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <h3 class="text-lg font-black text-slate-900">Tugaskan Kurir Pesanan #<span id="kurirKodePesanan"></span></h3>
            <button type="button" onclick="closeKurirModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 font-black text-sm">✕</button>
        </div>
        <form action="{{ route('admin.pengiriman.store') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="id_pesanan" id="kurirIdPesanan">

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">
                    Nama Kurir <span class="text-red-500 font-bold">*</span>
                </label>
                <input type="text"
                       name="nama_kurir"
                       required
                       placeholder="Contoh: Ahmad Faisal"
                       class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-sky-500">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        No. HP / WhatsApp Kurir <span class="text-red-500 font-bold">*</span>
                    </label>
                    <input type="tel"
                           inputmode="numeric"
                           pattern="[0-9]+"
                           name="no_hp_kurir"
                           required
                           placeholder="08xxxxxxxxxx"
                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        Kendaraan <span class="text-red-500 font-bold">*</span>
                    </label>
                    <input type="text"
                           name="kendaraan"
                           required
                           placeholder="Motor Honda Beat (D 1234 ABC)"
                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">
                    Alamat Tujuan Penjemputan / Pengantaran <span class="text-red-500 font-bold">*</span>
                </label>
                <textarea name="alamat_tujuan"
                          id="kurirAlamat"
                          rows="2"
                          required
                          class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-sky-500"></textarea>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="closeKurirModal()" class="px-5 py-2.5 rounded-xl bg-slate-100 text-slate-700 font-bold text-xs">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-sky-600 hover:bg-sky-700 text-white font-black text-xs shadow-md shadow-sky-600/30">
                    Jadwalkan Pengiriman
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openTolakModal(id, kode) {
        document.getElementById('tolakKodePesanan').textContent = kode;
        document.getElementById('formTolak').action = '{{ url("/admin/pesanan") }}/' + id + '/tolak';
        document.getElementById('modalTolak').classList.remove('hidden');
    }
    function closeTolakModal() {
        document.getElementById('modalTolak').classList.add('hidden');
    }

    function openKurirModal(id, kode, alamat) {
        document.getElementById('kurirIdPesanan').value = id;
        document.getElementById('kurirKodePesanan').textContent = kode;
        document.getElementById('kurirAlamat').value = alamat;
        document.getElementById('modalKurir').classList.remove('hidden');
    }
    function closeKurirModal() {
        document.getElementById('modalKurir').classList.add('hidden');
    }

    function previewBuktiAdmin(url, kodeBayar, kodePesanan, nominal) {
        document.getElementById('previewAdminTitle').textContent = 'Bukti Pembayaran #' + kodeBayar;
        document.getElementById('previewAdminSubtitle').textContent = 'Pesanan #' + kodePesanan + ' • Total Tagihan: Rp ' + nominal;
        document.getElementById('previewAdminImage').src = url;
        document.getElementById('modalPreviewBuktiAdmin').classList.remove('hidden');
    }
    function closePreviewBuktiAdmin() {
        document.getElementById('modalPreviewBuktiAdmin').classList.add('hidden');
    }
</script>
@endpush

@endsection
