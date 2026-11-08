@extends('layouts.app')

@section('title', 'Dashboard Eksekutif Owner - ArsyClean')

@section('content')
<div class="space-y-6">

    <!-- Notifikasi -->
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

    <!-- Executive Omzet Banner -->
    <div class="bg-gradient-to-r from-amber-600 to-amber-700 rounded-3xl p-6 sm:p-8 text-white shadow-lg flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-white/20 text-amber-100 mb-2">
                👑 Panel Pemilik Usaha (Owner)
            </div>
            <h1 class="text-2xl sm:text-3xl font-black tracking-tight">Laporan Omzet & Keuangan Laundry</h1>
            <p class="text-sm text-amber-100 max-w-xl leading-relaxed mt-1">
                Pantau kesehatan arus kas, pergerakan omzet riil, volume order antar-jemput, reguler, koin, dan kelola tarif layanan.
            </p>
        </div>
        <div class="bg-white/10 p-5 rounded-2xl border border-white/20 text-right min-w-56 shrink-0">
            <div class="text-xs text-amber-200 uppercase font-bold tracking-wider">Total Omzet Lunas</div>
            <div class="text-3xl sm:text-4xl font-black text-white mt-1">
                Rp {{ number_format($totalOmzet, 0, ',', '.') }}
            </div>
            <div class="text-xs text-amber-200 font-semibold mt-1">Arus Kas Masuk Bersih</div>
        </div>
    </div>

    <!-- Filter Periode Laporan & Tombol Cetak PDF -->
    <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
            <h3 class="text-base sm:text-lg font-black text-slate-800 flex items-center gap-2">
                <span>📅</span> Filter Periode Laporan Omzet &amp; Cetak
            </h3>
            <span class="text-xs text-slate-400">PDF dapat dicetak kapan saja dengan atau tanpa rentang tanggal.</span>
        </div>

        <form action="{{ route('owner.dashboard') }}" method="GET" class="flex flex-wrap items-end gap-3.5">
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">Dari Tanggal</label>
                <input type="date"
                       name="tanggal_mulai"
                       value="{{ request('tanggal_mulai') }}"
                       class="px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-700 focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">Sampai Tanggal</label>
                <input type="date"
                       name="tanggal_selesai"
                       value="{{ request('tanggal_selesai') }}"
                       class="px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-700 focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500">
            </div>

            <div class="flex items-center gap-2 flex-wrap">
                <button type="submit" class="px-5 py-2.5 bg-amber-600 hover:bg-amber-700 text-white rounded-xl font-bold text-sm shadow-xs transition-all">
                    Terapkan Filter
                </button>

                <a href="{{ route('owner.cetak.laporan', ['tanggal_mulai' => request('tanggal_mulai'), 'tanggal_selesai' => request('tanggal_selesai')]) }}"
                   target="_blank"
                   class="px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white rounded-xl font-bold text-sm shadow-xs transition-all flex items-center gap-2">
                    <span>🖨️</span> Cetak Laporan PDF
                </a>

                @if(request('tanggal_mulai') || request('tanggal_selesai'))
                    <a href="{{ route('owner.dashboard') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl font-bold text-sm transition-all">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Aktivitas Jenis Layanan: Pickup, Reguler, Koin -->
    <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200 shadow-sm">
        <h3 class="text-base sm:text-lg font-black text-slate-800 mb-4 flex items-center gap-2">
            <span>📊</span> Pemantauan Aktivitas Jenis Layanan
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-amber-50 border border-amber-200 p-5 rounded-2xl">
                <div class="text-xs text-amber-700 font-bold uppercase tracking-wider">Layanan Pickup (Antar-Jemput)</div>
                <div class="text-3xl font-black text-amber-900 mt-1">{{ $jumlahPickup }} Pesanan</div>
                <div class="text-xs text-amber-600 mt-1">Layanan kurir jemput langsung</div>
            </div>
            <div class="bg-blue-50 border border-blue-200 p-5 rounded-2xl">
                <div class="text-xs text-blue-700 font-bold uppercase tracking-wider">Layanan Reguler (Kiloan & Satuan)</div>
                <div class="text-3xl font-black text-blue-900 mt-1">{{ $jumlahReguler }} Pesanan</div>
                <div class="text-xs text-blue-600 mt-1">Cuci lipat & komplit standar</div>
            </div>
            <div class="bg-emerald-50 border border-emerald-200 p-5 rounded-2xl">
                <div class="text-xs text-emerald-700 font-bold uppercase tracking-wider">Layanan Koin / Self-Service</div>
                <div class="text-3xl font-black text-emerald-900 mt-1">{{ $jumlahKoin }} Pesanan</div>
                <div class="text-xs text-emerald-600 mt-1">Cuci mandiri mesin koin</div>
            </div>
        </div>
    </div>

    <!-- KPI Metric Cards & Shortcut Kelola Admin -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200 shadow-sm">
            <div class="text-xs text-slate-500 font-bold uppercase tracking-wider">Total Pelanggan Terdaftar</div>
            <div class="text-3xl font-black text-slate-900 mt-1">{{ $totalPelanggan }} Orang</div>
            <div class="text-xs text-slate-400 mt-1">Pengguna aktif ArsyClean</div>
        </div>
        <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200 shadow-sm">
            <div class="text-xs text-slate-500 font-bold uppercase tracking-wider">Total Transaksi Selesai</div>
            <div class="text-3xl font-black text-slate-900 mt-1">{{ $totalTransaksi }} Pesanan</div>
            <div class="text-xs text-slate-400 mt-1">Dari total {{ $totalPesanan }} pesanan</div>
        </div>
        <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
            <div>
                <div class="text-xs text-amber-700 font-bold uppercase tracking-wider">Staf Admin Aktif</div>
                <div class="text-3xl font-black text-amber-700 mt-1">{{ $totalAdmin }} Admin</div>
            </div>
            <a href="{{ route('owner.admin.index') }}" class="mt-3 inline-flex items-center gap-1.5 text-xs font-bold text-amber-700 hover:text-amber-800 hover:underline">
                Kelola Staf Admin &rarr;
            </a>
        </div>
    </div>

    <!-- Pengaturan Tarif Layanan & Status Operasional -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
            <div>
                <h3 class="text-lg sm:text-xl font-black text-slate-800">Pengaturan Tarif Layanan &amp; Status Operasional</h3>
                <p class="text-sm text-slate-500 mt-0.5">Ubah tarif harga dan ketersediaan layanan secara langsung.</p>
            </div>
            <span class="text-xs font-bold px-3 py-1 bg-slate-100 rounded-xl text-slate-600 self-start sm:self-auto">
                Total: {{ $layanans->count() }} Layanan
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-500 uppercase text-xs border-b border-slate-200">
                    <tr>
                        <th class="p-3.5">Kode</th>
                        <th class="p-3.5">Nama Layanan</th>
                        <th class="p-3.5">Jenis</th>
                        <th class="p-3.5">Tarif Satuan (Rp) <span class="text-red-500 font-bold">*</span></th>
                        <th class="p-3.5">Status Operasional <span class="text-red-500 font-bold">*</span></th>
                        <th class="p-3.5 text-center">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($layanans as $layanan)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="p-3.5 font-mono font-bold text-slate-800">{{ $layanan->kode_layanan }}</td>
                        <td class="p-3.5">
                            <div class="font-bold text-slate-800">{{ $layanan->nama_layanan }}</div>
                            <div class="text-xs text-slate-400">Durasi: {{ $layanan->durasi }}</div>
                        </td>
                        <td class="p-3.5">
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700">
                                {{ $layanan->jenis_layanan }}
                            </span>
                        </td>
                        <form action="{{ route('owner.layanan.update', $layanan->id_layanan) }}" method="POST">
                            @csrf
                            <td class="p-3.5">
                                <div class="flex items-center gap-1.5">
                                    <span class="text-xs text-slate-400 font-bold">Rp</span>
                                    <input type="number"
                                           name="harga"
                                           value="{{ (int) $layanan->harga }}"
                                           min="0"
                                           required
                                           class="w-28 px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500">
                                    <span class="text-xs text-slate-500">/ {{ $layanan->satuan }}</span>
                                </div>
                            </td>
                            <td class="p-3.5">
                                <select name="status" class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500">
                                    <option value="tersedia" {{ $layanan->status === 'tersedia' ? 'selected' : '' }}>Tersedia (Aktif)</option>
                                    <option value="tidak_tersedia" {{ $layanan->status === 'tidak_tersedia' ? 'selected' : '' }}>Nonaktif (Tutup)</option>
                                </select>
                            </td>
                            <td class="p-3.5 text-center">
                                <button type="submit" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-xl font-bold text-xs shadow-xs transition-all">
                                    Simpan
                                </button>
                            </td>
                        </form>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Riwayat Transaksi Pembayaran Terbaru -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
            <div>
                <h3 class="text-lg sm:text-xl font-black text-slate-800">Riwayat Transaksi Masuk Terkini</h3>
                <p class="text-sm text-slate-500 mt-0.5">Semua data transaksi pembayaran yang tercatat dalam sistem.</p>
            </div>
            <a href="{{ route('owner.cetak.laporan') }}" target="_blank" class="text-xs font-bold text-amber-700 hover:underline">
                Buka Versi Cetak Laporan &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-500 uppercase text-xs border-b border-slate-200">
                    <tr>
                        <th class="p-3.5">Kode Bayar</th>
                        <th class="p-3.5">Pesanan</th>
                        <th class="p-3.5">Pelanggan</th>
                        <th class="p-3.5">Metode</th>
                        <th class="p-3.5">Nominal (Rp)</th>
                        <th class="p-3.5">Status</th>
                        <th class="p-3.5">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pembayarans->take(10) as $byr)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="p-3.5 font-mono font-bold text-slate-800">
                            {{ $byr->kode_pembayaran }}
                        </td>
                        <td class="p-3.5 font-bold text-slate-700">
                            #{{ $byr->pesanan->kode_pesanan ?? '-' }}
                        </td>
                        <td class="p-3.5">
                            <span class="font-bold text-slate-800">{{ $byr->pesanan->user->nama ?? 'Pelanggan' }}</span>
                        </td>
                        <td class="p-3.5 text-xs font-semibold text-slate-600">
                            {{ $byr->metode_pembayaran }}
                        </td>
                        <td class="p-3.5 font-black text-slate-900">
                            Rp {{ number_format($byr->jumlah_bayar, 0, ',', '.') }}
                        </td>
                        <td class="p-3.5">
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold
                                {{ $byr->status_pembayaran === 'Lunas' ? 'bg-emerald-100 text-emerald-800' : ($byr->status_pembayaran === 'Pending' ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-800') }}">
                                {{ $byr->status_pembayaran }}
                            </span>
                        </td>
                        <td class="p-3.5 text-xs text-slate-400">
                            {{ $byr->created_at ? $byr->created_at->format('d/m/Y H:i') : '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-slate-400">
                            Belum ada riwayat transaksi.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
