@extends('layouts.pelanggan')

@section('title', 'Katalog Layanan Laundry - ArsyClean')

@section('content')
<div class="space-y-8">

    <!-- Header Katalog -->
    <div class="bg-gradient-to-r from-blue-900 via-[#0f2a5c] to-slate-900 rounded-3xl p-6 sm:p-10 text-white shadow-xl flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div>
            <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-white/20 text-sky-200 mb-3 border border-white/10">
                🧺 Pilihan Paket Laundry
            </div>
            <h1 class="text-2xl sm:text-4xl font-black tracking-tight">Katalog Layanan ArsyClean</h1>
            <p class="text-sm sm:text-base text-slate-300 max-w-2xl mt-2 leading-relaxed">
                Pilih paket laundry sesuai kebutuhan Anda. Pengerjaan higienis dengan deterjen premium, pewangi tahan lama, serta garansi bersih &amp; rapi.
            </p>
        </div>

        <div class="bg-white/10 backdrop-blur-md px-5 py-4 rounded-2xl border border-white/15 text-center shrink-0">
            <div class="text-xs text-sky-200 font-bold uppercase tracking-wider">Total Layanan Aktif</div>
            <div class="text-3xl font-black text-white mt-1">{{ $layanans->count() }} Paket</div>
        </div>
    </div>

    <!-- Grid Kartu Layanan -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($layanans as $layanan)
            @php
                $isPickup = str_contains(strtolower($layanan->nama_layanan . $layanan->jenis_layanan), 'antar') || str_contains(strtolower($layanan->nama_layanan . $layanan->jenis_layanan), 'pickup');
                $isKoin = strtolower($layanan->jenis_layanan) === 'koin';
                $icon = match(true) {
                    $isPickup => '🛵',
                    $isKoin   => '🪙',
                    str_contains(strtolower($layanan->nama_layanan), 'setrika') => '👔',
                    str_contains(strtolower($layanan->nama_layanan), 'bedcover') => '🛏️',
                    str_contains(strtolower($layanan->nama_layanan), 'sepatu') => '👟',
                    default => '🧺'
                };
            @endphp

            <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200 hover:border-blue-300 shadow-sm hover:shadow-xl transition-all duration-200 flex flex-col justify-between group">
                <div class="space-y-4">
                    <div class="flex items-center justify-between gap-2">
                        <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-3xl shadow-inner group-hover:scale-110 transition-transform">
                            {{ $icon }}
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider
                            {{ $isPickup ? 'bg-orange-100 text-orange-800 border border-orange-200' : ($isKoin ? 'bg-purple-100 text-purple-800 border border-purple-200' : 'bg-sky-100 text-sky-800 border border-sky-200') }}">
                            {{ $layanan->jenis_layanan }}
                        </span>
                    </div>

                    <div>
                        <h3 class="text-xl font-black text-slate-900 group-hover:text-blue-600 transition-colors">
                            {{ $layanan->nama_layanan }}
                        </h3>
                        <p class="text-xs text-slate-500 mt-1.5 leading-relaxed line-clamp-2">
                            {{ $layanan->deskripsi ?? 'Layanan pencucian profesional dengan deterjen ramah serat kain dan hasil setrika rapi.' }}
                        </p>
                    </div>

                    <div class="pt-4 border-t border-slate-100">
                        <div class="text-xs font-bold text-slate-400">Harga Paket</div>
                        <div class="flex items-baseline gap-1 mt-0.5">
                            <span class="text-2xl font-black text-slate-900">
                                Rp {{ number_format($layanan->harga, 0, ',', '.') }}
                            </span>
                            <span class="text-xs font-bold text-slate-500">/ {{ $layanan->satuan }}</span>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-4">
                    @if(!$isKoin)
                        <button type="button"
                                onclick="pilihLayanan('{{ $layanan->id_layanan }}', '{{ addslashes($layanan->nama_layanan) }}', '{{ $layanan->harga }}', '{{ $layanan->satuan }}')"
                                class="w-full py-3.5 px-4 rounded-2xl bg-blue-600 hover:bg-blue-700 active:scale-95 text-white font-extrabold text-sm shadow-md shadow-blue-600/25 transition-all flex items-center justify-center gap-2">
                            <span>Pesan Layanan Ini</span>
                            <span>&rarr;</span>
                        </button>
                    @else
                        <div class="w-full py-3 px-4 rounded-2xl bg-slate-100 text-slate-600 font-bold text-xs text-center border border-slate-200">
                            Tersedia Langsung di Outlet (Self-Service)
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

</div>

<!-- MODAL FORM PEMESANAN DARI KATALOG -->
<div id="orderModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4 hidden">
    <div class="bg-white w-full max-w-xl rounded-3xl shadow-2xl p-6 sm:p-8 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div>
                <h3 class="text-xl font-black text-slate-900">Form Pemesanan Laundry</h3>
                <p class="text-xs text-slate-500 mt-0.5">Satu formulir untuk satu kode pesanan unik.</p>
            </div>
            <button type="button" onclick="closeOrderModal()" class="w-9 h-9 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center font-black text-sm">
                ✕
            </button>
        </div>

        <form action="{{ route('pelanggan.pesanan.store') }}" method="POST" class="space-y-5 mt-5">
            @csrf

            <!-- Layanan Terpilih -->
            <input type="hidden" name="id_layanan" id="modalIdLayanan" value="">

            <div class="p-4 rounded-2xl bg-blue-50 border border-blue-200 flex items-center justify-between">
                <div>
                    <div class="text-xs text-blue-700 font-bold">Layanan Yang Dipilih:</div>
                    <div class="text-base font-black text-blue-950 mt-0.5" id="modalNamaLayanan">-</div>
                </div>
                <div class="text-right">
                    <div class="text-xs text-blue-700 font-bold">Tarif:</div>
                    <div class="text-sm font-black text-blue-950 mt-0.5" id="modalHargaLayanan">Rp 0</div>
                </div>
            </div>

            <!-- Estimasi Jumlah / Berat -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                    Estimasi Jumlah / Berat (<span id="modalSatuanText">kg</span>) <span class="text-rose-500 font-bold">*</span>
                </label>
                <input type="number" step="0.1" min="0.1" name="jumlah" id="modalInputJumlah" value="1" required
                       oninput="hitungTotalHarga()"
                       class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 font-bold text-slate-900 text-sm">
            </div>

            <!-- Tanggal & Jam Penjemputan -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        Tanggal Penjemputan <span class="text-rose-500 font-bold">*</span>
                    </label>
                    <input type="date" name="tanggal" min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" required
                           class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 font-bold text-slate-900 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        Jam Penjemputan <span class="text-rose-500 font-bold">*</span>
                    </label>
                    <input type="time" name="waktu" value="09:00" required
                           class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 font-bold text-slate-900 text-sm">
                </div>
            </div>

            <!-- Alamat Penjemputan -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                    Alamat Lengkap Penjemputan <span class="text-rose-500 font-bold">*</span>
                </label>
                <textarea name="alamat" rows="2" required placeholder="Contoh: Jl. Mawar No. 12, RT 02/05, Kelurahan..."
                          class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 font-medium text-slate-900 text-sm leading-relaxed">{{ Auth::user()->alamat ?? '' }}</textarea>
            </div>

            <!-- Catatan Khusus -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                    Catatan Khusus Untuk Petugas (Opsional)
                </label>
                <textarea name="catatan" rows="2" placeholder="Contoh: Tolong pisahkan pakaian luntur, deterjen khusus bayi, atau setrika ekstra licin..."
                          class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 font-medium text-slate-900 text-sm"></textarea>
            </div>

            <!-- Pilihan Metode Pembayaran -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2">
                    Metode Pembayaran <span class="text-rose-500 font-bold">*</span>
                </label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="p-3.5 rounded-2xl border-2 border-blue-500 bg-blue-50/50 flex items-center gap-3 cursor-pointer">
                        <input type="radio" name="metode_pembayaran" value="QRIS" checked class="text-blue-600">
                        <div>
                            <div class="text-xs font-extrabold text-slate-900">QRIS / E-Wallet</div>
                            <div class="text-[10px] text-slate-500 font-semibold">Otomatis / QR Manual</div>
                        </div>
                    </label>
                    <label class="p-3.5 rounded-2xl border border-slate-200 hover:border-slate-300 flex items-center gap-3 cursor-pointer">
                        <input type="radio" name="metode_pembayaran" value="TUNAI" class="text-blue-600">
                        <div>
                            <div class="text-xs font-extrabold text-slate-900">Bayar Tunai</div>
                            <div class="text-[10px] text-slate-500 font-semibold">Saat kurir tiba</div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Total Estimasi Biaya -->
            <div class="p-4 rounded-2xl bg-slate-900 text-white flex items-center justify-between">
                <div>
                    <div class="text-xs text-slate-400 font-semibold">Estimasi Total Tagihan:</div>
                    <div class="text-2xl font-black text-amber-300" id="modalTotalHargaText">Rp 0</div>
                </div>
                <button type="submit"
                        class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-500 active:scale-95 text-white font-extrabold text-sm shadow-md transition-all">
                    Konfirmasi Pesan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    let hargaPerUnit = 0;

    function pilihLayanan(id, nama, harga, satuan) {
        hargaPerUnit = Number(harga);
        document.getElementById('modalIdLayanan').value = id;
        document.getElementById('modalNamaLayanan').textContent = nama;
        document.getElementById('modalHargaLayanan').textContent = 'Rp ' + Number(harga).toLocaleString('id-ID') + ' / ' + satuan;
        document.getElementById('modalSatuanText').textContent = satuan;
        hitungTotalHarga();
        document.getElementById('orderModal').classList.remove('hidden');
    }

    function closeOrderModal() {
        document.getElementById('orderModal').classList.add('hidden');
    }

    function hitungTotalHarga() {
        const jumlah = parseFloat(document.getElementById('modalInputJumlah').value) || 0;
        const total = jumlah * hargaPerUnit;
        document.getElementById('modalTotalHargaText').textContent = 'Rp ' + Math.round(total).toLocaleString('id-ID');
    }
</script>
@endpush
@endsection
