@extends('layouts.app')

@section('title', 'Dashboard Pelanggan - Laravel ArsyClean')

@section('content')
<div class="space-y-6">
    <!-- Welcome Hero Banner -->
    <div class="bg-gradient-to-r from-sky-500 to-blue-600 rounded-3xl p-6 sm:p-8 text-white shadow-lg flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="space-y-2">
            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-white/20 backdrop-blur-md">
                Selamat Datang, {{ Auth::user()->nama }}!
            </span>
            <h1 class="text-2xl sm:text-3xl font-black">Layanan Laundry Praktis, Wangi & Bersih</h1>
            <p class="text-xs text-sky-100 max-w-xl leading-relaxed">
                Pesan cuci kiloan reguler atau koin self-service. Pantau status pencucian secara langsung dari rumah Anda.
            </p>
        </div>
        <div class="w-24 h-24 rounded-2xl bg-white/20 p-2 backdrop-blur-md shrink-0 hidden sm:block">
            <img src="/src/assets/images/arsy_clean_mascot_1793340487175.jpg" alt="Mascot" class="w-full h-full object-cover rounded-xl">
        </div>
    </div>

    <!-- Active Laundry Progress Tracker -->
    @if($activePesanan)
    <div class="bg-white rounded-3xl p-6 border border-sky-200 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-4">
            <div>
                <span class="text-[11px] font-bold text-sky-600 uppercase tracking-wider">Cucian Sedang Berlangsung</span>
                <h3 class="text-lg font-black text-slate-800">Pesanan #{{ $activePesanan->kode_pesanan }}</h3>
            </div>
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-sky-100 text-sky-800">
                <span>Status: {{ $activePesanan->status_laundry }}</span>
            </div>
        </div>

        <!-- 5-Step Progress Bar -->
        <div class="grid grid-cols-5 gap-2 text-center text-[11px] font-semibold text-slate-600 my-4">
            @php
                $stages = ['Menunggu Antrean', 'Pencucian', 'Pengeringan', 'Setrika', 'Siap Diambil'];
                $currentIndex = array_search($activePesanan->status_laundry, $stages);
                if ($currentIndex === false) $currentIndex = 0;
            @endphp
            @foreach($stages as $idx => $stage)
                <div class="flex flex-col items-center">
                    <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold mb-1 {{ $idx <= $currentIndex ? 'bg-sky-500 text-white' : 'bg-slate-200 text-slate-400' }}">
                        {{ $idx + 1 }}
                    </div>
                    <span class="text-[10px] {{ $idx <= $currentIndex ? 'text-sky-600 font-bold' : 'text-slate-400' }}">{{ $stage }}</span>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Catalog of Services -->
    <div>
        <h2 class="text-xl font-bold text-slate-800 mb-4">Katalog Layanan ArsyClean</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($layanans as $layanan)
            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-sky-100 text-sky-800 uppercase">
                            {{ $layanan->jenis_layanan }}
                        </span>
                        <span class="text-xs font-bold text-slate-500">⏱️ {{ $layanan->durasi }}</span>
                    </div>
                    <h4 class="font-bold text-slate-900 text-sm mb-1">{{ $layanan->nama_layanan }}</h4>
                    <p class="text-xs text-slate-500 leading-relaxed mb-4">{{ $layanan->deskripsi }}</p>
                </div>
                <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                    <div>
                        <div class="text-[10px] text-slate-400">Tarif</div>
                        <div class="text-base font-black text-sky-600">
                            Rp {{ number_format($layanan->harga, 0, ',', '.') }} <span class="text-xs font-normal text-slate-500">/ {{ $layanan->satuan }}</span>
                        </div>
                    </div>
                    <button onclick="openOrderModal({{ $layanan->id_layanan }}, '{{ $layanan->nama_layanan }}', {{ $layanan->harga }})" class="px-3.5 py-1.5 bg-sky-500 hover:bg-sky-600 text-white rounded-xl text-xs font-bold shadow-xs">
                        Pesan
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- My Orders Table -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-xs">
        <h3 class="text-lg font-bold text-slate-800 mb-4">Riwayat Pesanan Saya</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="p-3">Kode</th>
                        <th class="p-3">Tanggal</th>
                        <th class="p-3">Layanan</th>
                        <th class="p-3">Total</th>
                        <th class="p-3">Status Laundry</th>
                        <th class="p-3">Pembayaran</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pesanans as $p)
                    <tr class="hover:bg-slate-50">
                        <td class="p-3 font-mono font-bold text-slate-900">{{ $p->kode_pesanan }}</td>
                        <td class="p-3 text-slate-600">{{ $p->tanggal_pesanan->format('d M Y H:i') }}</td>
                        <td class="p-3 text-slate-800">
                            {{ $p->layanan->first()->nama_layanan ?? 'Layanan' }}
                        </td>
                        <td class="p-3 font-bold text-slate-900">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</td>
                        <td class="p-3">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-sky-50 text-sky-700 border border-sky-200">
                                {{ $p->status_laundry }}
                            </span>
                        </td>
                        <td class="p-3">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ ($p->pembayaran->status_pembayaran ?? '') === 'Lunas' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                {{ $p->pembayaran->status_pembayaran ?? 'Pending' }} ({{ $p->metode_pembayaran }})
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-4 text-center text-slate-400">Belum ada riwayat pesanan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Pesan Cepat -->
<div id="orderModal" class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl">
        <h3 class="text-lg font-bold text-slate-800 mb-1" id="modalLayananNama">Form Pemesanan</h3>
        <p class="text-xs text-slate-500 mb-4">Lengkapi detail penjemputan cucian Anda</p>

        <form action="{{ route('pelanggan.pesanan.store') }}" method="POST" class="space-y-3 text-xs">
            @csrf
            <input type="hidden" name="id_layanan" id="modalLayananId">

            <div>
                <label class="block font-semibold text-slate-600 mb-1">Perkiraan Jumlah / Berat</label>
                <input type="number" name="jumlah" value="5" min="1" step="0.5" required class="w-full px-3 py-2 bg-slate-50 border rounded-xl">
            </div>

            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block font-semibold text-slate-600 mb-1">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required class="w-full px-3 py-2 bg-slate-50 border rounded-xl">
                </div>
                <div>
                    <label class="block font-semibold text-slate-600 mb-1">Waktu</label>
                    <input type="time" name="waktu" value="09:00" required class="w-full px-3 py-2 bg-slate-50 border rounded-xl">
                </div>
            </div>

            <div>
                <label class="block font-semibold text-slate-600 mb-1">Metode Pembayaran</label>
                <select name="metode_pembayaran" class="w-full px-3 py-2 bg-slate-50 border rounded-xl">
                    <option value="QRIS">QRIS Instan</option>
                    <option value="Tunai">Tunai / Cash saat Antar</option>
                    <option value="Transfer Bank">Transfer Bank Mandiri / BCA</option>
                    <option value="Koin">Koin Mesin Laundry</option>
                </select>
            </div>

            <div>
                <label class="block font-semibold text-slate-600 mb-1">Alamat Penjemputan</label>
                <textarea name="alamat" rows="2" required class="w-full px-3 py-2 bg-slate-50 border rounded-xl">{{ Auth::user()->alamat }}</textarea>
            </div>

            <div class="flex gap-2 pt-2">
                <button type="button" onclick="closeOrderModal()" class="flex-1 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl">
                    Batal
                </button>
                <button type="submit" class="flex-1 py-2.5 bg-sky-500 hover:bg-sky-600 text-white font-bold rounded-xl shadow-md">
                    Konfirmasi Pesanan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openOrderModal(id, nama, harga) {
        document.getElementById('modalLayananId').value = id;
        document.getElementById('modalLayananNama').innerText = 'Pesan ' + nama;
        document.getElementById('orderModal').classList.remove('hidden');
    }
    function closeOrderModal() {
        document.getElementById('orderModal').classList.add('hidden');
    }
</script>
@endsection