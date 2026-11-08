@extends('layouts.app')

@section('title', 'Dashboard Operasional Admin - ArsyClean')

@section('content')
<div class="space-y-7">

    {{-- NOTIFIKASI --}}
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-bold flex items-center gap-3 shadow-xs">
            <span class="text-lg">✓</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 rounded-2xl bg-red-50 border border-red-200 text-red-800 text-sm font-bold flex items-center gap-3 shadow-xs">
            <span class="text-lg">✕</span>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if(isset($errors) && $errors->any())
        <div class="p-4 rounded-2xl bg-red-50 border border-red-200 text-red-800 text-sm font-semibold space-y-1 shadow-xs">
            <div class="font-bold flex items-center gap-2">
                <span>⚠️</span> Mohon periksa kembali input formulir:
            </div>
            <ul class="list-disc list-inside text-xs pl-2 space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- HEADER DASHBOARD OPERASIONAL ADMIN --}}
    <div class="bg-gradient-to-r from-slate-900 via-[#0f2a5c] to-blue-950 rounded-3xl p-6 sm:p-8 text-white shadow-xl flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-white/20 text-sky-200 mb-2">
                🧺 Panel Operasional &amp; Kasir
            </div>
            <h1 class="text-2xl sm:text-3xl font-black tracking-tight">Dashboard Operasional Laundry</h1>
            <p class="text-sm text-slate-300 max-w-xl mt-1 leading-relaxed">
                Kelola pesanan masuk, pantau status pengerjaan laundry bertahap, jadwalkan kurir pengiriman, dan perbarui stok bahan baku.
            </p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 w-full md:w-auto shrink-0">
            <div class="bg-white/10 p-4 rounded-2xl border border-white/10 text-center">
                <div class="text-2xl sm:text-3xl font-black text-amber-300">{{ $pesananMasuk->count() }}</div>
                <div class="text-xs text-slate-300 font-semibold mt-0.5">Pesanan Masuk</div>
            </div>

            <div class="bg-white/10 p-4 rounded-2xl border border-white/10 text-center">
                <div class="text-2xl sm:text-3xl font-black text-sky-300">{{ $pesananDiproses->count() }}</div>
                <div class="text-xs text-slate-300 font-semibold mt-0.5">Dalam Proses</div>
            </div>

            <div class="bg-white/10 p-4 rounded-2xl border border-white/10 text-center col-span-2 sm:col-span-1">
                <div class="text-2xl sm:text-3xl font-black text-emerald-300">
                    {{ $pembayarans->where('status_pembayaran', 'Pending')->count() }}
                </div>
                <div class="text-xs text-slate-300 font-semibold mt-0.5">Bayar Pending</div>
            </div>
        </div>
    </div>

    {{-- ================= SECTION 1: PESANAN MASUK (MENUNGGU KONFIRMASI) ================= --}}
    <div id="pesanan-masuk" class="scroll-mt-6 bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6 pb-4 border-b border-slate-100">
            <div>
                <h2 class="text-xl font-black text-slate-800 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center text-sm font-black">📥</span>
                    Pesanan Masuk (Menunggu Verifikasi &amp; Konfirmasi)
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Pesanan yang baru masuk dari pelanggan. Periksa alamat penjemputan dan catatan khusus sebelum menerima atau menolak.
                </p>
            </div>
            <span class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200 self-start sm:self-auto">
                {{ $pesananMasuk->count() }} Pesanan Baru
            </span>
        </div>

        <div class="space-y-4">
            @forelse($pesananMasuk as $p)
                <div class="p-5 bg-slate-50 hover:bg-slate-50/80 border border-slate-200 rounded-2xl transition-all">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                        <div class="space-y-1.5 flex-1">
                            <div class="flex items-center gap-2.5 flex-wrap">
                                <span class="font-mono text-base font-black text-blue-900">#{{ $p->kode_pesanan }}</span>
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                                    {{ $p->status_pesanan }}
                                </span>
                                <span class="text-xs font-semibold text-slate-400">
                                    {{ $p->tanggal_pesanan ? $p->tanggal_pesanan->format('d M Y') : '-' }}
                                </span>
                            </div>

                            <div class="text-sm font-bold text-slate-800">
                                Pelanggan: <span class="text-blue-700">{{ $p->user->nama ?? 'Pelanggan' }}</span>
                                &bull; <span class="text-slate-600 font-normal">📞 {{ $p->user->no_hp ?? '-' }}</span>
                            </div>

                            <div class="text-xs text-slate-600">
                                <b>Layanan:</b> {{ $p->layanan->first()->nama_layanan ?? '-' }}
                                @if($p->layanan->first())
                                    ({{ $p->layanan->first()->pivot->jumlah }} {{ $p->layanan->first()->satuan }})
                                @endif
                                &bull; <b>Total Tagihan:</b> <span class="text-slate-900 font-black">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</span>
                                &bull; <b>Metode:</b> {{ $p->metode_pembayaran }}
                            </div>

                            <div class="text-xs text-slate-600">
                                <b>Alamat Penjemputan:</b> {{ $p->alamat_penjemputan ?? '-' }}
                            </div>

                            {{-- CATATAN DARI PELANGGAN DITAMPILKAN JELAS --}}
                            @if(!empty($p->catatan))
                                <div class="mt-2 p-3 bg-amber-50/80 border border-amber-200/80 rounded-xl text-xs text-amber-900">
                                    <div class="font-bold flex items-center gap-1.5 mb-1 text-amber-800">
                                        <span>📝</span> Catatan Dari Pelanggan:
                                    </div>
                                    <div class="whitespace-pre-line text-slate-700 font-medium pl-5">{{ $p->catatan }}</div>
                                </div>
                            @endif
                        </div>

                        <!-- Tombol Aksi Terima & Tolak -->
                        <div class="flex items-center gap-2 pt-2 lg:pt-0 shrink-0">
                            <form action="{{ route('admin.pesanan.konfirmasi', $p->id_pesanan) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs shadow-md shadow-emerald-600/25 transition-all flex items-center gap-1.5">
                                    <span>✓</span> Terima Pesanan
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
    <div id="dalam-proses" class="scroll-mt-6 bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6 pb-4 border-b border-slate-100">
            <div>
                <h2 class="text-xl font-black text-slate-800 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-xl bg-sky-100 text-sky-700 flex items-center justify-center text-sm font-black">⚙️</span>
                    Data Pesanan Dalam Proses (Pengerjaan Laundry)
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Pesanan yang telah diterima dan sedang diproses. Perbarui tahap pengerjaan laundry secara berurutan.
                </p>
            </div>
            <span class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-sky-50 text-sky-700 border border-sky-200 self-start sm:self-auto">
                {{ $pesananDiproses->count() }} Sedang Dikerjakan
            </span>
        </div>

        <div class="space-y-5">
            @forelse($pesananDiproses as $p)
                @php
                    $statusLaundry = $p->status_laundry ?? 'Menunggu Antrean';
                    // Status pengerjaan diberi warna tegas & informatif
                    $badgeWarna = match ($statusLaundry) {
                        'Menunggu Antrean' => 'bg-amber-100 text-amber-800 border-amber-300',
                        'Pencucian'        => 'bg-sky-100 text-sky-800 border-sky-300',
                        'Pengeringan'      => 'bg-indigo-100 text-indigo-800 border-indigo-300',
                        'Setrika'          => 'bg-purple-100 text-purple-800 border-purple-300',
                        'Siap Diambil'     => 'bg-teal-100 text-teal-800 border-teal-300',
                        'Siap Dikirim',
                        'Dalam Pengiriman' => 'bg-orange-100 text-orange-800 border-orange-300',
                        'Selesai'          => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                        default            => 'bg-slate-100 text-slate-700 border-slate-300',
                    };
                @endphp

                <div class="p-6 bg-slate-50 border border-slate-200 rounded-2xl space-y-4">
                    <!-- Ringkasan Info Pesanan -->
                    <div class="flex flex-col md:flex-row justify-between gap-4">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h3 class="font-mono text-lg font-black text-slate-900">#{{ $p->kode_pesanan }}</h3>
                                <!-- Status Pengerjaan Diberi Warna -->
                                <span class="px-3 py-1 rounded-full text-xs font-black border {{ $badgeWarna }}">
                                    Tahap: {{ $statusLaundry }}
                                </span>
                            </div>

                            <p class="text-sm font-bold text-slate-700">
                                Pelanggan: <span class="text-blue-700">{{ $p->user->nama ?? 'Pelanggan' }}</span>
                                ({{ $p->user->no_hp ?? '-' }})
                            </p>

                            <p class="text-xs text-slate-600">
                                Layanan: <b>{{ $p->layanan->first()->nama_layanan ?? '-' }}</b>
                                @if($p->layanan->first())
                                    ({{ $p->layanan->first()->pivot->jumlah }} {{ $p->layanan->first()->satuan }})
                                @endif
                                &bull; Alamat: {{ $p->alamat_penjemputan ?? '-' }}
                            </p>
                        </div>

                        <div class="text-left md:text-right">
                            <div class="text-xs text-slate-400 font-semibold">Total Tagihan</div>
                            <div class="text-xl font-black text-slate-900">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</div>
                            <div class="mt-1">
                                <span class="px-2.5 py-0.5 rounded text-[11px] font-bold
                                    {{ ($p->pembayaran?->status_pembayaran ?? 'Pending') === 'Lunas' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                    Bayar: {{ $p->pembayaran?->status_pembayaran ?? 'Pending' }} ({{ $p->metode_pembayaran }})
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- CATATAN INPUT DARI PELANGGAN DITAMPILKAN JELAS DI ADMIN --}}
                    @if(!empty($p->catatan))
                        <div class="p-3 bg-white border border-slate-200 rounded-xl text-xs text-slate-700">
                            <div class="font-bold text-slate-800 flex items-center gap-1.5 mb-1">
                                <span>📋</span> Catatan Pelanggan:
                            </div>
                            <div class="whitespace-pre-line pl-5">{{ $p->catatan }}</div>
                        </div>
                    @endif

                    <!-- Form Update Status Laundry & Tambah Siap Dikirim -->
                    <div class="pt-4 border-t border-slate-200/80 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">
                        <form action="{{ route('admin.pesanan.status', $p->id_pesanan) }}"
                              method="POST"
                              class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5 flex-1">
                            @csrf
                            <label class="text-xs font-bold text-slate-700 shrink-0">
                                Update Status Pengerjaan <span class="text-red-500 font-bold">*</span>:
                            </label>

                            {{-- Pilihan Layanan Siap Dikirim Ditambahkan di Dropdown --}}
                            <select name="status_laundry"
                                    required
                                    class="px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500 flex-1">
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
                                    class="px-4 py-2 bg-slate-900 hover:bg-black text-white rounded-xl text-xs font-bold shadow-xs transition-all">
                                Simpan Status
                            </button>
                        </form>

                        <!-- Shortcut Penugasan Kurir Pengiriman -->
                        @if(!$p->pengiriman)
                            <button type="button"
                                    onclick="openKurirModal('{{ $p->id_pesanan }}', '{{ $p->kode_pesanan }}', '{{ addslashes($p->alamat_penjemputan ?? '') }}')"
                                    class="px-4 py-2 bg-sky-50 hover:bg-sky-100 text-sky-700 border border-sky-200 rounded-xl text-xs font-bold transition-all shrink-0">
                                🚚 Tugaskan Kurir
                            </button>
                        @else
                            <div class="text-xs text-slate-500 shrink-0 bg-white px-3 py-1.5 rounded-xl border border-slate-200">
                                🚚 Kurir: <b>{{ $p->pengiriman->nama_kurir }}</b> ({{ $p->pengiriman->status_pengiriman }})
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
    <div id="verifikasi-bayar" class="scroll-mt-6 bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6 pb-4 border-b border-slate-100">
            <div>
                <h2 class="text-xl font-black text-slate-800 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-sm font-black">💳</span>
                    Verifikasi Pembayaran Pelanggan
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Validasi pembayaran manual / transfer bank dan periksa bukti transfer yang diunggah.
                </p>
            </div>
            <span class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 self-start sm:self-auto">
                Total Transaksi: {{ $pembayarans->count() }}
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-500 uppercase text-xs border-b border-slate-200">
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
                <tbody class="divide-y divide-slate-100">
                    @forelse($pembayarans as $byr)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="p-3.5 font-mono font-bold text-slate-800">{{ $byr->kode_pembayaran }}</td>
                        <td class="p-3.5 font-bold text-slate-700">#{{ $byr->pesanan->kode_pesanan ?? '-' }}</td>
                        <td class="p-3.5">
                            <div class="font-bold text-slate-800">{{ $byr->pesanan->user->nama ?? 'Pelanggan' }}</div>
                            <div class="text-xs text-slate-400">{{ $byr->pesanan->user->no_hp ?? '-' }}</div>
                        </td>
                        <td class="p-3.5 text-xs font-semibold text-slate-600">{{ $byr->metode_pembayaran }}</td>
                        <td class="p-3.5 font-black text-slate-900">Rp {{ number_format($byr->jumlah_bayar, 0, ',', '.') }}</td>
                        <td class="p-3.5">
                            @if($byr->bukti_transfer)
                                <a href="{{ asset('storage/' . $byr->bukti_transfer) }}" target="_blank" class="px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 hover:underline text-xs font-bold inline-flex items-center gap-1">
                                    <span>🔍</span> Lihat Bukti
                                </a>
                            @else
                                <span class="text-xs text-slate-400 italic">Tidak ada file</span>
                            @endif
                        </td>
                        <td class="p-3.5">
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold
                                {{ $byr->status_pembayaran === 'Lunas' ? 'bg-emerald-100 text-emerald-800' : ($byr->status_pembayaran === 'Pending' ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-800') }}">
                                {{ $byr->status_pembayaran }}
                            </span>
                        </td>
                        <td class="p-3.5 text-center">
                            @if($byr->status_pembayaran === 'Lunas')
                                <span class="text-xs font-bold text-emerald-600">✓ Sudah Lunas</span>
                            @else
                                <form action="{{ route('admin.pembayaran.verifikasi', $byr->id_pembayaran) }}"
                                      method="POST"
                                      onsubmit="return confirm('Konfirmasi bahwa pembayaran #{{ $byr->kode_pembayaran }} sebesar Rp {{ number_format($byr->jumlah_bayar, 0, ',', '.') }} telah diterima secara valid?')">
                                    @csrf
                                    <button type="submit"
                                            class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs shadow-xs transition-all">
                                        Verifikasi Lunas
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="p-8 text-center text-slate-400">Belum ada transaksi pembayaran.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ================= SECTION 4: MANAJEMEN STOK BAHAN BAKU ================= --}}
    <div id="stok-bahan" class="scroll-mt-6 bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6 pb-4 border-b border-slate-100">
            <div>
                <h2 class="text-xl font-black text-slate-800 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-sm font-black">📦</span>
                    Manajemen Stok Bahan Baku Laundry
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Pantau ketersediaan deterjen, pewangi, plastik, dan perbarui jumlah stok riil harian.
                </p>
            </div>
            <span class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200 self-start sm:self-auto">
                Total Bahan: {{ $stoks->count() }} Jenis
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse($stoks as $stk)
                <div class="p-5 bg-slate-50 rounded-2xl border border-slate-200 space-y-3">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <h4 class="font-bold text-base text-slate-800">{{ $stk->nama_barang }}</h4>
                            <p class="text-xs text-slate-500">Batas Minimum: {{ $stk->batas_minimum }} {{ $stk->satuan }}</p>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold
                            {{ $stk->status === 'Aman' ? 'bg-emerald-100 text-emerald-800' : ($stk->status === 'Menipis' ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-800') }}">
                            {{ $stk->status }}
                        </span>
                    </div>

                    <form action="{{ route('admin.stok.update', $stk->id_stok) }}" method="POST" class="pt-2">
                        @csrf
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Jumlah Tersedia ({{ $stk->satuan }}) <span class="text-red-500 font-bold">*</span>
                        </label>
                        <div class="flex gap-2">
                            <input type="number"
                                   name="jumlah"
                                   value="{{ $stk->jumlah }}"
                                   min="0"
                                   step="0.5"
                                   required
                                   class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <button type="submit"
                                    class="px-4 py-2 bg-slate-900 hover:bg-black text-white rounded-xl text-xs font-bold transition-all shrink-0">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            @empty
                <div class="col-span-full p-8 text-center text-slate-400">
                    Belum ada data stok bahan baku.
                </div>
            @endforelse
        </div>
    </div>

</div>

{{-- MODAL TOLAK PESANAN --}}
<div id="modalTolak" class="hidden fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <h3 class="text-lg font-bold text-slate-800">Tolak Pesanan #<span id="tolakKodePesanan"></span></h3>
            <button type="button" onclick="closeTolakModal()" class="text-slate-400 hover:text-slate-700 text-xl font-bold">✕</button>
        </div>
        <form id="formTolak" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">
                    Alasan Penolakan <span class="text-red-500 font-bold">*</span>
                </label>
                <textarea name="alasan"
                          rows="3"
                          required
                          placeholder="Contoh: Alamat di luar jangkauan atau antrean laundry sedang penuh..."
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
<div id="modalKurir" class="hidden fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-7 shadow-2xl space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <h3 class="text-lg font-bold text-slate-800">Tugaskan Kurir untuk Pesanan #<span id="kurirKodePesanan"></span></h3>
            <button type="button" onclick="closeKurirModal()" class="text-slate-400 hover:text-slate-700 text-xl font-bold">✕</button>
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
                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-sky-500">
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
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        Kendaraan <span class="text-red-500 font-bold">*</span>
                    </label>
                    <input type="text"
                           name="kendaraan"
                           required
                           placeholder="Motor Honda Beat (D 1234 ABC)"
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-sky-500">
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
                          class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-sky-500"></textarea>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="closeKurirModal()" class="px-4 py-2.5 rounded-xl bg-slate-100 text-slate-700 font-bold text-xs">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs shadow-md shadow-sky-600/30">
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
</script>
@endpush

@endsection
