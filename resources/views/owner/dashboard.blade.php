@extends('layouts.app')

@section('title', 'Dashboard Eksekutif Owner - Laravel ArsyClean')

@section('content')
<div class="space-y-6">

    @if(session('success'))
    <div class="p-4 bg-emerald-100 border border-emerald-300 text-emerald-800 rounded-2xl text-xs font-bold">
        {{ session('success') }}
    </div>
    @endif

    <!-- Executive Omzet Banner (Langkah 2: Melihat Ringkasan) -->
    <div class="bg-gradient-to-r from-amber-600 to-amber-700 rounded-3xl p-6 sm:p-8 text-white shadow-lg flex flex-col md:flex-row items-center justify-between gap-6">
        <div>
            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-white/20 uppercase tracking-wider mb-2 text-amber-100">
                👑 Panel Pemilik Usaha (Owner)
            </span>
            <h1 class="text-2xl sm:text-3xl font-black">Laporan Omzet & Keuangan Laundry</h1>
            <p class="text-xs text-amber-100 max-w-xl leading-relaxed mt-1">
                Pantau kesehatan arus kas, omzet bulanan riil, volume transaksi, dan kelola operasional usaha.
            </p>
        </div>
        <div class="bg-white/10 p-5 rounded-2xl border border-white/20 text-right min-w-56">
            <div class="text-xs text-amber-200 uppercase font-semibold">Total Omzet Masuk</div>
            <div class="text-3xl font-black text-white mt-1">Rp {{ number_format($totalOmzet, 0, ',', '.') }}</div>
            <div class="text-[10px] text-amber-200 mt-1">Performa Bisnis Sehat &amp; Bertumbuh</div>
        </div>
    </div>

    <!-- LANGKAH 4: Memeriksa Laporan (Filter Periode & Cetak PDF) -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-xs">
        <h3 class="text-sm font-bold text-slate-800 mb-3 flex items-center gap-2">
            <span>📅</span> Filter Laporan Periode Omzet & Cetak Laporan
        </h3>
        <form action="{{ route('owner.dashboard') }}" method="GET" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1">Dari Tanggal</label>
                <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-amber-500">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1">Sampai Tanggal</label>
                <input type="date" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-amber-500">
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-xl font-bold text-xs shadow-xs transition-all">
                    Terapkan Filter
                </button>

                <a href="{{ route('owner.cetak.laporan', ['tanggal_mulai' => request('tanggal_mulai'), 'tanggal_selesai' => request('tanggal_selesai')]) }}" target="_blank" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-xl font-bold text-xs shadow-xs transition-all flex items-center gap-1">
                    <span>🖨️</span> Cetak PDF / Laporan
                </a>

                @if(request('tanggal_mulai') || request('tanggal_selesai'))
                    <a href="{{ route('owner.dashboard') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl font-bold text-xs transition-all">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- LANGKAH 3: Memantau Layanan (Aktivitas Pickup, Reguler, dan Koin) -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-xs">
        <h3 class="text-sm font-bold text-slate-800 mb-3 flex items-center gap-2">
            <span>📊</span> Pemantauan Aktivitas Jenis Layanan
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-amber-50 border border-amber-200 p-4 rounded-2xl">
                <div class="text-xs text-amber-700 font-bold uppercase">Layanan Pickup</div>
                <div class="text-2xl font-black text-amber-900 mt-1">{{ $jumlahPickup }} Pesanan</div>
                <div class="text-[10px] text-amber-600 mt-1">Antar-Jemput Pelanggan</div>
            </div>
            <div class="bg-blue-50 border border-blue-200 p-4 rounded-2xl">
                <div class="text-xs text-blue-700 font-bold uppercase">Layanan Reguler</div>
                <div class="text-2xl font-black text-blue-900 mt-1">{{ $jumlahReguler }} Pesanan</div>
                <div class="text-[10px] text-blue-600 mt-1">Cuci Lipat Standar</div>
            </div>
            <div class="bg-emerald-50 border border-emerald-200 p-4 rounded-2xl">
                <div class="text-xs text-emerald-700 font-bold uppercase">Layanan Koin / Self-Service</div>
                <div class="text-2xl font-black text-emerald-900 mt-1">{{ $jumlahKoin }} Pesanan</div>
                <div class="text-[10px] text-emerald-600 mt-1">Cuci Mandiri Mesin</div>
            </div>
        </div>
    </div>

    <!-- KPI Metric Cards (Langkah 2: Ringkasan Usaha) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <div class="text-xs text-slate-400 font-semibold">Total Pelanggan Terdaftar</div>
            <div class="text-2xl font-black text-slate-900 mt-1">{{ $totalPelanggan }} Pengguna</div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <div class="text-xs text-slate-400 font-semibold">Transaksi Selesai</div>
            <div class="text-2xl font-black text-slate-900 mt-1">{{ $totalTransaksi }} Pesanan</div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <div class="text-xs text-slate-400 font-semibold">Total Akun Admin Aktif</div>
            <div class="text-2xl font-black text-amber-600 mt-1">{{ $totalAdmin }} Admin</div>
        </div>
    </div>

    <!-- LANGKAH 5: Mengatur Layanan (Tarif & Status Operasional) -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-xs">
        <h3 class="text-lg font-bold text-slate-800 mb-4">Pengaturan Tarif Layanan & Status Operasional</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="p-3">Kode</th>
                        <th class="p-3">Nama Layanan</th>
                        <th class="p-3">Jenis</th>
                        <th class="p-3">Tarif Satuan (Rp)</th>
                        <th class="p-3">Status</th>
                        <th class="p-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($layanans as $layanan)
                    <tr class="hover:bg-slate-50">
                        <td class="p-3 font-mono font-bold text-slate-900">{{ $layanan->kode_layanan }}</td>
                        <td class="p-3 font-semibold text-slate-800">{{ $layanan->nama_layanan }}</td>
                        <td class="p-3">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700">
                                {{ $layanan->jenis_layanan }}
                            </span>
                        </td>
                        <form action="{{ route('owner.layanan.update', $layanan->id_layanan) }}" method="POST">
                            @csrf
                            <td class="p-3">
                                <div class="flex items-center gap-1">
                                    <span class="text-slate-400 font-bold">Rp</span>
                                    <input type="number" name="harga" value="{{ $layanan->harga }}" class="w-24 px-2 py-1 bg-white border rounded font-bold">
                                    <span class="text-[10px] text-slate-400">/ {{ $layanan->satuan }}</span>
                                </div>
                            </td>
                            <td class="p-3">
                                <select name="status" class="px-2 py-1 bg-white border rounded text-xs">
                                    <option value="tersedia" {{ $layanan->status === 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                    <option value="tidak_tersedia" {{ $layanan->status === 'tidak_tersedia' ? 'selected' : '' }}>Nonaktif</option>
                                </select>
                            </td>
                            <td class="p-3">
                                <button type="submit" class="px-3 py-1 bg-amber-600 hover:bg-amber-700 text-white rounded-lg font-bold text-xs shadow-xs">
                                    Simpan Perubahan
                                </button>
                            </td>
                        </form>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- LANGKAH 6: Mengelola Admin (Tambah, Ubah, & Hapus Akun Admin) -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-xs">
        <h3 class="text-lg font-bold text-slate-800 mb-4">Pengelolaan Akun Admin Laundry</h3>

        <!-- Form Tambah Admin -->
        <form action="{{ route('owner.admin.store') }}" method="POST" class="mb-6 p-4 bg-slate-50 border border-slate-200 rounded-2xl">
            @csrf
            <h4 class="text-xs font-bold text-slate-600 uppercase mb-3">+ Tambah Admin Baru</h4>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <input type="text" name="name" placeholder="Nama Admin" required class="px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:ring-2 focus:ring-amber-500">
                <input type="email" name="email" placeholder="Email Admin" required class="px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:ring-2 focus:ring-amber-500">
                <input type="password" name="password" placeholder="Password (Min. 6 Karakter)" required class="px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:ring-2 focus:ring-amber-500">
            </div>
            <button type="submit" class="mt-3 px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-xl font-bold text-xs shadow-xs transition-all">
                Buat Akun Admin
            </button>
        </form>

        <!-- Daftar Admin -->
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="p-3">ID</th>
                        <th class="p-3">Nama Admin</th>
                        <th class="p-3">Email</th>
                        <th class="p-3">Role</th>
                        <th class="p-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($admins as $admin)
                    <tr class="hover:bg-slate-50">
                        <td class="p-3 font-mono font-bold text-slate-900">#{{ $admin->id_user ?? $admin->id }}</td>
                        <td class="p-3 font-semibold text-slate-800">{{ $admin->name }}</td>
                        <td class="p-3 text-slate-600">{{ $admin->email }}</td>
                        <td class="p-3">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800 uppercase">
                                {{ $admin->role }}
                            </span>
                        </td>
                        <td class="p-3">
                            <form action="{{ route('owner.admin.destroy', $admin->id_user ?? $admin->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus akun admin {{ $admin->name }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded-lg font-bold text-xs shadow-xs">
                                    Hapus / Nonaktifkan
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection