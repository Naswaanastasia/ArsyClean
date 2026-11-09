@extends('layouts.pelanggan')

@section('title', 'Katalog Layanan Laundry - ArsyClean')

@section('content')
<div class="space-y-8">

    <!-- Header Katalog -->
    <div class="bg-gradient-to-r from-[#0f2a5c] via-[#163a78] to-[#0a1e42] rounded-3xl p-6 sm:p-10 text-white shadow-xl flex flex-col md:flex-row items-start md:items-center justify-between gap-6 border border-blue-900/40">
        <div>
            <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-white/15 text-sky-200 mb-3 border border-white/20">
                <svg class="w-4 h-4 text-sky-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                Pilihan Paket Laundry Terbaik
            </div>
            <h1 class="text-2xl sm:text-4xl font-black tracking-tight">Katalog Layanan ArsyClean</h1>
            <p class="text-sm sm:text-base text-blue-100 max-w-2xl mt-2 leading-relaxed font-medium">
                Pilih paket laundry sesuai kebutuhan Anda. Proses pengerjaan higienis, menggunakan deterjen ramah serat kain, pewangi tahan lama, serta garansi bersih, rapi, dan wangi.
            </p>
        </div>

        <div class="bg-white/10 backdrop-blur-md px-6 py-4 rounded-2xl border border-white/20 text-center shrink-0">
            <div class="text-xs text-sky-200 font-bold uppercase tracking-wider">Total Paket Aktif</div>
            <div class="text-3xl font-black text-white mt-1">{{ $layanans->count() }} Paket</div>
        </div>
    </div>

    <!-- Grid Kartu Layanan -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($layanans as $layanan)
            @php
                $isPickup = str_contains(strtolower($layanan->nama_layanan . $layanan->jenis_layanan), 'antar') || str_contains(strtolower($layanan->nama_layanan . $layanan->jenis_layanan), 'pickup');
                $isKoin = strtolower($layanan->jenis_layanan) === 'koin';
                $isSatuan = strtolower($layanan->satuan) !== 'kg';
            @endphp

            <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200 hover:border-blue-400 shadow-sm hover:shadow-xl transition-all duration-200 flex flex-col justify-between group">
                <div class="space-y-4">
                    <div class="flex items-center justify-between gap-2">
                        <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-700 flex items-center justify-center border border-blue-200 group-hover:scale-105 transition-transform">
                            @if($isPickup)
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path></svg>
                            @elseif($isKoin)
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            @else
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            @endif
                        </div>
                        <span class="px-3.5 py-1 rounded-full text-xs font-black uppercase tracking-wider
                            {{ $isPickup ? 'bg-orange-100 text-orange-950 border border-orange-200' : ($isKoin ? 'bg-purple-100 text-purple-950 border border-purple-200' : 'bg-sky-100 text-sky-950 border border-sky-200') }}">
                            {{ $layanan->jenis_layanan }}
                        </span>
                    </div>

                    <div>
                        <h3 class="text-xl font-black text-slate-900 group-hover:text-blue-700 transition-colors">
                            {{ $layanan->nama_layanan }}
                        </h3>
                        <p class="text-sm text-slate-600 mt-2 leading-relaxed line-clamp-2 font-medium">
                            {{ $layanan->deskripsi ?? 'Layanan pencucian profesional dengan deterjen ramah serat kain dan hasil setrika rapi wangi.' }}
                        </p>
                    </div>

                    <div class="pt-4 border-t border-slate-100">
                        <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Tarif Layanan</div>
                        <div class="flex items-baseline gap-1.5 mt-0.5">
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
                                onclick="pilihLayanan('{{ $layanan->id_layanan }}', '{{ addslashes($layanan->nama_layanan) }}', '{{ $layanan->harga }}', '{{ strtolower($layanan->satuan) }}')"
                                class="w-full py-3.5 px-4 rounded-2xl bg-blue-600 hover:bg-blue-700 active:scale-95 text-white font-black text-sm shadow-md shadow-blue-600/25 transition-all flex items-center justify-center gap-2">
                            <span>Pesan Layanan Ini</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                        </button>
                    @else
                        <div class="w-full py-3.5 px-4 rounded-2xl bg-slate-100 text-slate-600 font-bold text-xs text-center border border-slate-200">
                            Tersedia Langsung di Outlet (Self-Service)
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

</div>

<!-- MODAL FORM PEMESANAN DARI KATALOG (LENGKAP DENGAN RINCIAN PAKAIAN & AKUMULASI) -->
<div id="orderModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/70 backdrop-blur-sm p-4 hidden">
    <div class="bg-white w-full max-w-2xl rounded-3xl shadow-2xl p-6 sm:p-8 max-h-[92vh] overflow-y-auto border border-slate-200">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div>
                <h3 class="text-2xl font-black text-slate-900 tracking-tight">Form Pemesanan Laundry</h3>
                <p class="text-sm text-slate-500 mt-0.5">Satu formulir untuk satu pesanan penjemputan cucian unik.</p>
            </div>
            <button type="button" onclick="closeOrderModal()" class="w-9 h-9 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center font-black text-base">
                ✕
            </button>
        </div>

        <form action="{{ route('pelanggan.pesanan.store') }}" method="POST" class="space-y-5 mt-5 text-sm">
            @csrf

            <!-- Layanan Terpilih -->
            <input type="hidden" name="id_layanan" id="modalIdLayanan" value="">

            <div class="p-4 rounded-2xl bg-blue-50 border border-blue-200 flex items-center justify-between">
                <div>
                    <div class="text-xs text-blue-800 font-black uppercase tracking-wider">Layanan Yang Dipilih:</div>
                    <div class="text-base font-black text-blue-950 mt-0.5" id="modalNamaLayanan">-</div>
                </div>
                <div class="text-right">
                    <div class="text-xs text-blue-800 font-black uppercase tracking-wider">Tarif:</div>
                    <div class="text-base font-black text-blue-950 mt-0.5" id="modalHargaLayanan">Rp 0</div>
                </div>
            </div>

            <!-- Rincian Pakaian Sesuai Jenis & Akumulasi (Kiloan) -->
            <div id="modalClothingSection" class="space-y-3 p-5 bg-slate-50 border border-slate-200 rounded-3xl">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-2 border-b border-slate-200">
                    <div>
                        <span class="font-black text-sm text-slate-900 uppercase tracking-wider block">
                            Rincian Pakaian Sesuai Jenis
                        </span>
                        <span class="text-xs text-slate-500 font-medium">Input jumlah pakaian untuk diakumulasikan ke total baju &amp; berat.</span>
                    </div>
                    <div class="bg-blue-600 text-white px-3.5 py-1.5 rounded-xl text-xs font-black shrink-0 shadow-sm" id="modalBadgeTotalBaju">
                        Total Pakaian: 0 Potong
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 pt-1 max-h-52 overflow-y-auto pr-1">
                    @php
                        $daftarPakaianModal = [
                            'kaos'           => ['label' => 'Kaos / T-Shirt', 'gram' => 120],
                            'kemeja'         => ['label' => 'Kemeja', 'gram' => 150],
                            'celana_panjang' => ['label' => 'Celana Panjang/Jeans', 'gram' => 300],
                            'celana_pendek'  => ['label' => 'Celana Pendek', 'gram' => 150],
                            'jaket'          => ['label' => 'Jaket / Hoodie', 'gram' => 450],
                            'pakaian_dalam'  => ['label' => 'Pakaian Dalam / Kaus Kaki', 'gram' => 50],
                            'baju_muslim'    => ['label' => 'Gamis / Baju Muslim', 'gram' => 300],
                            'sprei'          => ['label' => 'Sprei / Sarung Bantal', 'gram' => 400],
                            'lainnya'        => ['label' => 'Pakaian Lainnya', 'gram' => 150],
                        ];
                    @endphp

                    @foreach($daftarPakaianModal as $key => $prop)
                        <div class="bg-white p-3 rounded-2xl border border-slate-200 shadow-xs">
                            <label class="block text-xs font-bold text-slate-800 truncate">{{ $prop['label'] }}</label>
                            <input type="number"
                                   min="0"
                                   name="pakaian[{{ $key }}]"
                                   data-gram="{{ $prop['gram'] }}"
                                   placeholder="0"
                                   class="modal-clothing-input w-full mt-1.5 px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm font-black text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   oninput="hitungTotalKatalog()">
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Bagian Satuan (Untuk paket non-kg) -->
            <div id="modalUnitSection" class="hidden space-y-2 p-5 bg-slate-50 border border-slate-200 rounded-3xl">
                <label class="block text-sm font-bold text-slate-800">
                    Jumlah Barang (<span id="modalSatuanText">pcs</span>) <span class="text-rose-500 font-bold">*</span>
                </label>
                <input type="number" min="1" id="modalInputSatuan" value="1"
                       oninput="hitungTotalKatalog()"
                       class="w-full px-4 py-3 rounded-2xl border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 font-black text-slate-900 text-sm">
            </div>

            <input type="hidden" name="jumlah" id="modalInputJumlah" value="1">

            <!-- Tanggal & Jam Penjemputan -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-slate-800 mb-1.5">
                        Tanggal Penjemputan <span class="text-rose-500 font-bold">*</span>
                    </label>
                    <input type="date" name="tanggal" min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" required
                           class="w-full px-4 py-3 rounded-2xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 font-bold text-slate-900 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-800 mb-1.5">
                        Jam Penjemputan <span class="text-rose-500 font-bold">*</span>
                    </label>
                    <input type="time" name="waktu" value="09:00" required
                           class="w-full px-4 py-3 rounded-2xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 font-bold text-slate-900 text-sm">
                </div>
            </div>

            <!-- Alamat Penjemputan -->
            <div>
                <label class="block text-sm font-bold text-slate-800 mb-1.5">
                    Alamat Lengkap Penjemputan <span class="text-rose-500 font-bold">*</span>
                </label>
                <textarea name="alamat" rows="2" required placeholder="Contoh: Jl. Mawar No. 12, RT 02/05, Kelurahan..."
                          class="w-full px-4 py-3 rounded-2xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 font-medium text-slate-900 text-sm leading-relaxed">{{ Auth::user()->alamat ?? '' }}</textarea>
            </div>

            <!-- Catatan Khusus -->
            <div>
                <label class="block text-sm font-bold text-slate-800 mb-1.5">
                    Catatan Khusus Untuk Petugas / Kurir (Opsional)
                </label>
                <textarea name="catatan" rows="2" placeholder="Contoh: Tolong pisahkan pakaian luntur, deterjen khusus bayi, atau setrika ekstra licin..."
                          class="w-full px-4 py-3 rounded-2xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 font-medium text-slate-900 text-sm"></textarea>
            </div>

            <!-- Pilihan Metode Pembayaran -->
            <div>
                <label class="block text-sm font-bold text-slate-800 mb-2">
                    Metode Pembayaran <span class="text-rose-500 font-bold">*</span>
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <label class="p-4 rounded-2xl border-2 border-slate-200 hover:border-blue-500 bg-slate-50 flex items-center gap-3 cursor-pointer transition-colors">
                        <input type="radio" name="metode_pembayaran" value="QRIS" checked class="w-4 h-4 text-blue-600">
                        <div>
                            <div class="text-sm font-black text-slate-900">QRIS Manual (by_onest)</div>
                            <div class="text-xs text-slate-500 font-medium">BCA, Mandiri, GoPay, OVO, Dana</div>
                        </div>
                    </label>
                    <label class="p-4 rounded-2xl border-2 border-slate-200 hover:border-blue-500 bg-slate-50 flex items-center gap-3 cursor-pointer transition-colors">
                        <input type="radio" name="metode_pembayaran" value="TUNAI" class="w-4 h-4 text-blue-600">
                        <div>
                            <div class="text-sm font-black text-slate-900">Tunai (Bayar di Tempat)</div>
                            <div class="text-xs text-slate-500 font-medium">Bayar saat kurir menjemput/mengantar</div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Total Estimasi Biaya -->
            <div class="p-5 rounded-2xl bg-slate-900 text-white flex items-center justify-between shadow-lg">
                <div>
                    <div class="text-xs text-slate-400 font-bold uppercase tracking-wider">Estimasi Total Tagihan:</div>
                    <div class="text-2xl font-black text-amber-300 tracking-tight" id="modalTotalHargaText">Rp 0</div>
                </div>
                <button type="submit"
                        class="px-7 py-3.5 rounded-2xl bg-blue-600 hover:bg-blue-500 active:scale-95 text-white font-black text-sm shadow-md transition-all flex items-center gap-2">
                    <span>Konfirmasi Pesan</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    let hargaPerUnit = 0;
    let currentSatuan = 'kg';

    function pilihLayanan(id, nama, harga, satuan) {
        hargaPerUnit = Number(harga);
        currentSatuan = satuan;
        document.getElementById('modalIdLayanan').value = id;
        document.getElementById('modalNamaLayanan').textContent = nama;
        document.getElementById('modalHargaLayanan').textContent = 'Rp ' + Number(harga).toLocaleString('id-ID') + ' / ' + satuan;

        const clothingSec = document.getElementById('modalClothingSection');
        const unitSec = document.getElementById('modalUnitSection');

        if (satuan === 'kg') {
            clothingSec.classList.remove('hidden');
            unitSec.classList.add('hidden');
        } else {
            clothingSec.classList.add('hidden');
            unitSec.classList.remove('hidden');
            document.getElementById('modalSatuanText').textContent = satuan;
        }

        hitungTotalKatalog();
        document.getElementById('orderModal').classList.remove('hidden');
    }

    function closeOrderModal() {
        document.getElementById('orderModal').classList.add('hidden');
    }

    function hitungTotalKatalog() {
        let totalPotong = 0;
        let totalGram = 0;

        document.querySelectorAll('.modal-clothing-input').forEach(inp => {
            const qty = parseInt(inp.value) || 0;
            const gram = parseInt(inp.dataset.gram) || 150;
            if (qty > 0) {
                totalPotong += qty;
                totalGram += (qty * gram);
            }
        });

        document.getElementById('modalBadgeTotalBaju').innerText = 'Total Pakaian: ' + totalPotong + ' Potong';

        let jumlahUnit = 0;
        if (currentSatuan === 'kg') {
            if (totalPotong > 0) {
                jumlahUnit = Math.max(1.0, Math.round((totalGram / 1000) * 10) / 10);
            } else {
                jumlahUnit = 1.0;
            }
        } else {
            jumlahUnit = parseInt(document.getElementById('modalInputSatuan').value) || 1;
        }

        const totalHarga = Math.round(jumlahUnit * hargaPerUnit);
        document.getElementById('modalInputJumlah').value = jumlahUnit;
        document.getElementById('modalTotalHargaText').textContent = 'Rp ' + totalHarga.toLocaleString('id-ID') + (currentSatuan === 'kg' ? ' (' + jumlahUnit.toFixed(1) + ' kg)' : '');
    }
</script>
@endpush
@endsection
