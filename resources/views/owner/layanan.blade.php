@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Katalog Tarif & Layanan</h1>
            <p class="text-sm text-gray-500">Kelola daftar harga, tarif, dan operasional layanan laundry</p>
        </div>
        <!-- Tombol Tambah Layanan -->
        <button onclick="document.getElementById('modalTambahLayanan').classList.remove('hidden')" 
                class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg text-sm flex items-center gap-2 shadow">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Layanan Baru
        </button>
    </div>

    <!-- Alert Notifikasi -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-sm">
            {{ session('error') }}
        </div>
    @endif

    <!-- Tabel Layanan -->
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Layanan</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Satuan</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tarif Saat Ini</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Perbarui Tarif</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi Hapus</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white text-sm">
                @foreach($layanans as $item)
                <tr>
                    <td class="px-4 py-4 font-bold text-gray-700">{{ $item->kode_layanan ?? 'L'.str_pad($item->id_layanan, 3, '0', STR_PAD_LEFT) }}</td>
                    <td class="px-4 py-4 font-medium text-gray-900">{{ $item->nama_layanan }}</td>
                    <td class="px-4 py-4 text-gray-500">
                        <span class="px-2.5 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-semibold">
                            {{ $item->jenis_layanan }}
                        </span>
                    </td>
                    <td class="px-4 py-4 text-gray-500">{{ $item->satuan }}</td>
                    <td class="px-4 py-4 font-bold text-gray-800">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                    <td class="px-4 py-4">
                        @if($item->status == 'tersedia')
                            <span class="px-2.5 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">Tersedia</span>
                        @else
                            <span class="px-2.5 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">Tidak Tersedia</span>
                        @endif
                    </td>
                    <td class="px-4 py-4">
                        <!-- Form Edit Tarif & Status -->
                        <form action="{{ route('owner.layanan.update', $item->id_layanan) }}" method="POST" class="flex items-center gap-2">
                            @csrf
                            <input type="number" name="harga" value="{{ $item->harga }}" class="w-24 border rounded px-2 py-1 text-sm focus:ring-1 focus:ring-blue-500">
                            <select name="status" class="border rounded px-2 py-1 text-sm focus:ring-1 focus:ring-blue-500">
                                <option value="tersedia" {{ $item->status == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                <option value="tidak_tersedia" {{ $item->status == 'tidak_tersedia' ? 'selected' : '' }}>Tidak Tersedia</option>
                            </select>
                            <button type="submit" class="bg-gray-800 hover:bg-black text-white px-3 py-1 rounded text-xs font-semibold">Simpan</button>
                        </form>
                    </td>
                    <td class="px-4 py-4 text-center">
                        <!-- Form Hapus Layanan -->
                        <form action="{{ route('owner.layanan.destroy', $item->id_layanan) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus layanan {{ $item->nama_layanan }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs font-semibold">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Layanan Baru -->
<div id="modalTambahLayanan" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-800">Tambah Layanan Baru</h3>
            <button onclick="document.getElementById('modalTambahLayanan').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">&times;</button>
        </div>
        <form action="{{ route('owner.layanan.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Layanan</label>
                    <input type="text" name="nama_layanan" required placeholder="Contoh: Cuci Karpet Express" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori / Jenis</label>
                    <select name="jenis_layanan" required class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                        <option value="Reguler">Reguler</option>
                        <option value="Koin">Koin</option>
                        <option value="Antar Jemput">Antar Jemput</option>
                        <option value="Dry Clean">Dry Clean</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp)</label>
                        <input type="number" name="harga" required placeholder="15000" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Satuan</label>
                        <input type="text" name="satuan" required placeholder="Kg / Pcs / Koin" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status Awal</label>
                    <select name="status" required class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                        <option value="tersedia">Tersedia</option>
                        <option value="tidak_tersedia">Tidak Tersedia</option>
                    </select>
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('modalTambahLayanan').classList.add('hidden')" class="px-4 py-2 bg-gray-200 text-gray-700 text-sm rounded-lg hover:bg-gray-300">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 font-medium">Simpan Layanan</button>
            </div>
        </form>
    </div>
</div>
@endsection