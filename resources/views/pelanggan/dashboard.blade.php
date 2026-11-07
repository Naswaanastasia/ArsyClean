@extends('layouts.app')

@section('title', 'Dashboard Pelanggan - Laravel ArsyClean')

@section('content')

    
@if (session('success') || session('error'))
    <div id="notificationModal"
         class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/50 p-4 overflow-y-auto">

        <div class="w-full max-w-sm rounded-2xl bg-white p-7 text-center shadow-2xl my-6">

            @if(session('qr_code_url'))
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-sky-100">
                    <span class="text-3xl">📱</span>
                </div>

                <h3 class="mb-2 text-xl font-bold text-slate-800">
                    Pesanan Berhasil Dibuat!
                </h3>

                <p class="mb-4 text-sm text-slate-500">
                    Silakan scan QRIS berikut untuk membayar.
                </p>

                <div class="rounded-xl border border-slate-200 p-3 mb-4">
                    <img src="{{ session('qr_code_url') }}"
                         alt="QRIS Pembayaran"
                         class="mx-auto w-full max-w-[240px]">

                    <p class="mt-3 text-sm font-bold text-slate-800">
                        Kode Pesanan
                    </p>

                    <p class="text-sm text-sky-600 font-mono">
                        {{ session('kode_pesanan') }}
                    </p>

                    <p class="mt-3 text-sm text-slate-500">
                        Total Pembayaran
                    </p>

                    <p class="text-xl font-black text-slate-800">
                        Rp{{ number_format(session('total_bayar'), 0, ',', '.') }}
                    </p>
                </div>

                <p id="qrSessionStatus" class="mb-5 text-xs text-slate-500">
                    Menunggu pembayaran... status akan diperbarui otomatis.
                </p>
            @else
                <h3 class="mb-2 text-xl font-bold text-slate-800">
                    {{ session('success') ? 'Pesanan Berhasil!' : 'Pesanan Gagal!' }}
                </h3>

                <p class="mb-6 text-sm text-slate-500">
                    {{ session('success') ? session('success') : session('error') }}
                </p>
            @endif

            <button type="button"
                    onclick="hentikanCekPembayaran(); document.getElementById('notificationModal').remove()"
                    class="w-full rounded-xl bg-sky-500 px-5 py-3 text-sm font-bold text-white hover:bg-sky-600">
                Tutup
            </button>
        </div>
    </div>
@endif

    <!-- Welcome Hero Banner -->
    <div class="bg-gradient-to-r from-sky-500 to-blue-600 rounded-3xl p-6 sm:p-8 text-white shadow-lg flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="space-y-2">
            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-white/20">
                Selamat Datang, {{ Auth::user()->nama }}!
            </span>

            <h1 class="text-2xl sm:text-3xl font-black">
                Layanan Laundry Praktis, Wangi & Bersih
            </h1>

            <p class="text-xs text-sky-100 max-w-xl leading-relaxed">
                Pesan layanan pick up laundry dengan mudah. Kurir kami siap menjemput cucian langsung ke rumahmu.
            </p>
        </div>

        <div class="w-24 h-24 rounded-2xl bg-white/20 p-2 shrink-0 hidden sm:block">
            <img src="/src/assets/images/arsy_clean_mascot_1793340487175.jpg"
                 alt="Mascot ArsyClean"
                 class="w-full h-full object-cover rounded-xl">
        </div>
    </div>

    <!-- Active Laundry Progress Tracker -->
    @if($activePesanan)
        <div class="bg-white rounded-3xl p-6 border border-sky-200 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-4">
                <div>
                    <span class="text-[11px] font-bold text-sky-600 uppercase tracking-wider">
                        Cucian Sedang Berlangsung
                    </span>

                    <h3 class="text-lg font-black text-slate-800">
                        Pesanan #{{ $activePesanan->kode_pesanan }}
                    </h3>
                </div>

                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-sky-100 text-sky-800">
                    Status: {{ $activePesanan->status_laundry }}
                </div>
            </div>

            @php
                $stages = [
                    'Menunggu Antrean',
                    'Pencucian',
                    'Pengeringan',
                    'Setrika',
                    'Siap Diambil',
                    'Dalam Pengiriman',
                    'Selesai'
                ];

                $currentIndex = array_search(
                    $activePesanan->status_laundry,
                    $stages
                );

                if ($currentIndex === false) {
                    $currentIndex = 0;
                }
            @endphp

            <div class="grid grid-cols-4 sm:grid-cols-7 gap-3 text-center my-4">
                @foreach($stages as $idx => $stage)
                    <div class="flex flex-col items-center">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold mb-1
                            {{ $idx <= $currentIndex ? 'bg-sky-500 text-white' : 'bg-slate-200 text-slate-400' }}">
                            {{ $idx + 1 }}
                        </div>

                        <span class="text-[10px]
                            {{ $idx <= $currentIndex ? 'text-sky-600 font-bold' : 'text-slate-400' }}">
                            {{ $stage }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Katalog Pick Up Laundry -->
    <div>
        <div class="mb-5">
            <h2 class="text-xl font-bold text-slate-800">
                Katalog Layanan ArsyClean
            </h2>

            <p class="text-sm text-slate-500 mt-1">
                Pilih layanan laundry sesuai kebutuhanmu.
            </p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
            <div class="flex items-center gap-4 mb-5">
                <div class="w-14 h-14 rounded-2xl bg-sky-100 flex items-center justify-center text-2xl">
                    🚚
                </div>

                <div>
                    <h3 class="text-lg font-bold text-slate-800">
                        Pesanan Pick Up Laundry
                    </h3>

                    <p class="text-xs text-slate-500 mt-1">
                        Kurir ArsyClean siap menjemput cucian ke rumahmu.
                    </p>
                </div>
            </div>

            <button type="button"
                    onclick="openOrderModal()"
                    class="w-full bg-sky-500 hover:bg-sky-600 text-white font-bold text-sm py-3 rounded-xl transition">
                Pesan Sekarang
            </button>
        </div>
    </div>

    <!-- Riwayat Pesanan -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
        <h3 class="text-lg font-bold text-slate-800 mb-4">
            Riwayat Pesanan Saya
        </h3>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="p-3">Kode</th>
                        <th class="p-3">Tanggal</th>
                        <th class="p-3">Layanan</th>
                        <th class="p-3">Jumlah</th>
                        <th class="p-3">Total</th>
                        <th class="p-3">Status</th>
                        <th class="p-3">Pembayaran</th>
                        <th class="p-3">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse($pesanans as $p)
                        <tr class="hover:bg-slate-50">
                            <td class="p-3 font-mono font-bold text-slate-900">
                                {{ $p->kode_pesanan }}
                            </td>

                            <td class="p-3 text-slate-600">
                                {{ $p->tanggal_pesanan
                                    ? $p->tanggal_pesanan->format('d M Y')
                                    : '-' }}
                            </td>

                            <td class="p-3 text-slate-800">
                                {{ $p->layanan->first()->nama_layanan ?? 'Layanan' }}
                            </td>

                            <td class="p-3 text-slate-600">
                                @if($p->layanan->first())
                                    {{ $p->layanan->first()->pivot->jumlah }}
                                    {{ $p->layanan->first()->satuan }}
                                @else
                                    -
                                @endif
                            </td>

                            <td class="p-3 font-bold text-slate-900">
                                Rp {{ number_format($p->total_harga, 0, ',', '.') }}
                            </td>

                            <td class="p-3">
                                @php
                                    $statusPesanan = $p->status_pesanan;
                                    $warnaStatus = match ($statusPesanan) {
                                        'Selesai'    => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'Dibatalkan' => 'bg-red-50 text-red-700 border-red-200',
                                        'Diproses'   => 'bg-sky-50 text-sky-700 border-sky-200',
                                        default      => 'bg-amber-50 text-amber-700 border-amber-200',
                                    };
                                @endphp

                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $warnaStatus }}">
                                    {{ $statusPesanan === 'Diproses' ? $p->status_laundry : $statusPesanan }}
                                </span>

                                @if($p->pengiriman)
                                    <div class="mt-1 text-[10px] text-slate-500">
                                        Kurir: {{ $p->pengiriman->nama_kurir }}
                                        ({{ $p->pengiriman->status_pengiriman }})
                                    </div>
                                @endif
                            </td>

                            <td class="p-3">
                                @php
                                    $statusBayar = $p->pembayaran?->status_pembayaran ?? 'Pending';
                                    $warnaBayar = match ($statusBayar) {
                                        'Lunas'                => 'bg-emerald-100 text-emerald-800',
                                        'Gagal', 'Dibatalkan'  => 'bg-red-100 text-red-800',
                                        default                => 'bg-amber-100 text-amber-800',
                                    };
                                @endphp

                                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $warnaBayar }}">
                                    {{ $statusBayar }} ({{ $p->metode_pembayaran }})
                                </span>
                            </td>

                            <td class="p-3">
                                @php
                                    $bisaBayar = $p->status_pesanan === 'Menunggu Pembayaran'
                                        && $statusBayar === 'Pending'
                                        && !empty($p->pembayaran?->qr_code_url);
                                @endphp

                                @if($bisaBayar)
                                    <button type="button"
                                            data-kode="{{ $p->kode_pesanan }}"
                                            data-qr="{{ $p->pembayaran->qr_code_url }}"
                                            data-total="{{ (int) round($p->total_harga) }}"
                                            onclick="openQrModal(this)"
                                            class="px-3 py-1.5 bg-sky-500 hover:bg-sky-600 text-white font-bold rounded-lg text-[11px]">
                                        Bayar
                                    </button>
                                @elseif($p->ulasan)
                                    <span class="text-amber-500 font-bold">
                                        {{ str_repeat('★', (int) $p->ulasan->rating) }}{{ str_repeat('☆', 5 - (int) $p->ulasan->rating) }}
                                    </span>
                                @elseif($p->status_pesanan === 'Selesai')
                                    <button type="button"
                                            data-id="{{ $p->id_pesanan }}"
                                            data-kode="{{ $p->kode_pesanan }}"
                                            onclick="openReviewModal(this)"
                                            class="px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-lg text-[11px]">
                                        Beri Ulasan
                                    </button>
                                @else
                                    <span class="text-slate-300">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-4 text-center text-slate-400">
                                Belum ada riwayat pesanan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Modal Pemesanan Pick Up Laundry -->
<div id="orderModal"
     class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">

    <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl max-h-[90vh] overflow-y-auto">

        <div class="flex justify-between items-start mb-4">
            <div>
                <h3 class="text-lg font-bold text-slate-800">
                    Pesanan Pick Up Laundry
                </h3>

                <p class="text-xs text-slate-500 mt-1">
                    Lengkapi detail cucian yang ingin dijemput.
                </p>
            </div>

            <button type="button"
                    onclick="closeOrderModal()"
                    class="text-slate-400 hover:text-slate-700 text-xl">
                ✕
            </button>
        </div>

        <form action="{{ route('pelanggan.pesanan.store') }}"
        method="POST"
        id="orderForm"
        novalidate
        class="space-y-4 text-sm">
            @csrf

            <!-- Pilihan Layanan -->
            <div>
                <label class="block font-semibold text-slate-700 mb-2">
                    Jenis Layanan
                </label>

                <select name="id_layanan"
                        id="serviceSelect"
                        required
                        onchange="changeService()"
                        class="w-full border border-slate-200 rounded-xl px-3 py-3 bg-slate-50 focus:ring-2 focus:ring-sky-400 outline-none">

                    <option value="">Pilih jenis layanan</option>

                    @foreach([
                        'L001' => 'Cuci Kering Reguler',
                        'L004' => 'Cuci Komplit + Antar Jemput',
                        'L005' => 'Dry Clean Jas / Blazer',
                        'L006' => 'Cuci Bed Cover / Selimut Besar'
                    ] as $kode => $nama)

                        @php
                            $layanan = $layanans->firstWhere('kode_layanan', $kode);
                        @endphp

                        @if($layanan)
                            <option value="{{ $layanan->id_layanan }}"
                                    data-kode="{{ $kode }}"
                                    data-nama="{{ $nama }}"
                                    data-harga="{{ $layanan->harga }}"
                                    data-satuan="{{ $layanan->satuan }}">
                                {{ $nama }} -
                                Rp{{ number_format($layanan->harga, 0, ',', '.') }}/{{ $layanan->satuan }}
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>

            <!-- Bagian Pakaian Kiloan -->
            <div id="kiloanSection" class="hidden space-y-3">

                <div>
                    <h4 class="font-bold text-slate-800">
                        Detail Jenis Pakaian
                    </h4>

                    <p class="text-xs text-slate-500 mt-1">
                        Masukkan jumlah setiap jenis pakaian dalam satuan pcs.
                        Berat yang ditampilkan merupakan estimasi.
                    </p>
                </div>

                <div id="clothingList" class="space-y-3">

                    @php
                        $clothingTypes = [
                            ['key' => 'kaos', 'name' => 'Kaos', 'weight' => 100],
                            ['key' => 'kemeja', 'name' => 'Kemeja', 'weight' => 111],
                            ['key' => 'celana_bahan', 'name' => 'Celana Bahan', 'weight' => 167],
                            ['key' => 'celana_jeans', 'name' => 'Celana Jeans', 'weight' => 250],
                            ['key' => 'celana_pendek', 'name' => 'Celana Pendek', 'weight' => 118],
                            ['key' => 'jaket_tipis', 'name' => 'Jaket Tipis', 'weight' => 333],
                            ['key' => 'jaket_tebal', 'name' => 'Jaket Tebal', 'weight' => 400],
                            ['key' => 'hoodie', 'name' => 'Hoodie', 'weight' => 400],
                            ['key' => 'sweater', 'name' => 'Sweater', 'weight' => 250],
                            ['key' => 'rok_tipis', 'name' => 'Rok Tipis', 'weight' => 154],
                            ['key' => 'rok_tebal', 'name' => 'Rok Tebal', 'weight' => 250],
                            ['key' => 'pakaian_anak', 'name' => 'Pakaian Anak-anak', 'weight' => 63],
                            ['key' => 'pakaian_dalam', 'name' => 'Pakaian Dalam', 'weight' => 50],
                            ['key' => 'handuk_kecil', 'name' => 'Handuk Kecil', 'weight' => 154],
                            ['key' => 'handuk_besar', 'name' => 'Handuk Besar', 'weight' => 333],
                            ['key' => 'baju_tidur', 'name' => 'Baju Tidur', 'weight' => 154],
                        ];
                    @endphp

                    @foreach($clothingTypes as $item)
                        <div class="flex items-center justify-between gap-3 border border-slate-200 rounded-xl p-3">
                            <div>
                                <p class="text-xs font-semibold text-slate-700">
                                    {{ $item['name'] }}
                                </p>

                                <p class="text-[10px] text-slate-400">
                                    ± {{ $item['weight'] }} gram/pcs
                                </p>
                            </div>

                            <input type="number"
                                   name="pakaian[{{ $item['key'] }}]"
                                   min="0"
                                   step="1"
                                   value="0"
                                   data-weight="{{ $item['weight'] }}"
                                   data-name="{{ $item['name'] }}"
                                   class="clothing-quantity w-20 border border-slate-200 rounded-lg px-2 py-2 text-center text-sm"
                                   oninput="calculateOrder()">
                        </div>
                    @endforeach

                </div>
            </div>

            <!-- Bagian Laundry Satuan -->
            <div id="satuanSection" class="hidden space-y-3">

                <div>
                    <h4 class="font-bold text-slate-800">
                        Detail Barang
                    </h4>

                    <p id="satuanDescription" class="text-xs text-slate-500 mt-1">
                        Masukkan jumlah barang yang akan dicuci.
                    </p>
                </div>

                <div>
                    <label id="satuanItemLabel"
                           class="block font-semibold text-slate-700 mb-2">
                        Jumlah barang (pcs)
                    </label>

                    <input type="number"
                           id="satuanQuantity"
                           min="1"
                           step="1"
                           value="1"
                           oninput="calculateOrder()"
                           class="w-full border border-slate-200 rounded-xl px-3 py-3 bg-slate-50">
                </div>
            </div>

            <!-- Jumlah yang dikirim ke Controller -->
            <input type="hidden"
                   name="jumlah"
                   id="jumlahInput"
                   value="">

            <!-- Ringkasan Harga -->
            <div id="priceSummary"
                 class="hidden bg-sky-50 border border-sky-100 rounded-2xl p-4 space-y-2">

                <h4 class="font-bold text-slate-800 mb-2">
                    Ringkasan Pesanan
                </h4>

                <div class="flex justify-between text-xs">
                    <span class="text-slate-500">Jenis layanan</span>
                    <span id="summaryService" class="font-semibold text-slate-700">-</span>
                </div>

                <div class="flex justify-between text-xs">
                    <span class="text-slate-500">Jumlah pakaian/barang</span>
                    <span id="summaryQuantity" class="font-semibold text-slate-700">0 pcs</span>
                </div>

                <div id="weightSummary" class="flex justify-between text-xs">
                    <span class="text-slate-500">Estimasi berat</span>
                    <span id="summaryWeight" class="font-semibold text-slate-700">0 kg</span>
                </div>

                <div class="flex justify-between text-xs">
                    <span class="text-slate-500">Tarif layanan</span>
                    <span id="summaryRate" class="font-semibold text-slate-700">Rp0</span>
                </div>

                <div class="border-t border-sky-200 pt-3 flex justify-between items-center">
                    <span class="font-bold text-slate-800">Total Harga</span>
                    <span id="summaryTotal" class="text-lg font-black text-sky-600">Rp0</span>
                </div>
            </div>

            <!-- Tanggal dan Waktu -->
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-semibold text-slate-700 mb-2">
                        Tanggal Penjemputan
                    </label>

                    <input type="date"
                           name="tanggal"
                           min="{{ date('Y-m-d') }}"
                           value="{{ date('Y-m-d') }}"
                           required
                           class="w-full border border-slate-200 rounded-xl px-3 py-3 bg-slate-50 text-xs">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-2">
                        Waktu Penjemputan
                    </label>

                    <input type="time"
                           name="waktu"
                           value="09:00"
                           required
                           class="w-full border border-slate-200 rounded-xl px-3 py-3 bg-slate-50 text-xs">
                </div>
            </div>

            
<!-- Metode Pembayaran -->
<div>
    <label class="block font-semibold text-slate-700 mb-2">
        Metode Pembayaran
    </label>

    <div class="w-full border border-sky-200 rounded-xl px-4 py-3 bg-sky-50">
        <div class="flex items-center gap-3">
            <input type="hidden" name="metode_pembayaran" value="QRIS">

            <span class="text-2xl">📱</span>

            <div>
                <p class="font-bold text-slate-800">QRIS</p>
                <p class="text-xs text-slate-500">
                    Bayar menggunakan aplikasi mobile banking atau e-wallet.
                </p>
            </div>
        </div>
    </div>
</div>

                
            <!-- Alamat -->
            <div>
                <label class="block font-semibold text-slate-700 mb-2">
                    Alamat Penjemputan
                </label>

                <textarea name="alamat"
                          rows="3"
                          required
                          class="w-full border border-slate-200 rounded-xl px-3 py-3 bg-slate-50">{{ Auth::user()->alamat }}</textarea>
            </div>

            <!-- Catatan -->
            <div>
                <label class="block font-semibold text-slate-700 mb-2">
                    Catatan (Opsional)
                </label>

                <textarea name="catatan"
                          id="customerNote"
                          rows="2"
                          class="w-full border border-slate-200 rounded-xl px-3 py-3 bg-slate-50"
                          placeholder="Tambahkan catatan untuk kurir atau laundry..."></textarea>
            </div>

            <!-- Tombol -->
            <div class="flex gap-2 pt-2">
                <button type="button"
                        onclick="closeOrderModal()"
                        class="flex-1 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl">
                    Batal
                </button>

                <button type="submit"
                        id="submitOrderButton"
                        class="flex-1 py-3 bg-sky-500 hover:bg-sky-600 text-white font-bold rounded-xl">
                    Konfirmasi Pesanan
                </button>
            </div>

        </form>
    </div>
</div>
<!-- Modal QR Pembayaran (untuk melanjutkan pembayaran yang tertunda) -->
<div id="qrModal"
     class="hidden fixed inset-0 z-[80] bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">

    <div class="bg-white rounded-3xl max-w-sm w-full p-7 text-center shadow-2xl max-h-[90vh] overflow-y-auto">
        <h3 class="mb-1 text-xl font-bold text-slate-800">Bayar Pesanan</h3>
        <p class="mb-4 text-sm text-slate-500">Scan QRIS berikut untuk membayar.</p>

        <div class="rounded-xl border border-slate-200 p-3 mb-4">
            <img id="qrModalImage" src="" alt="QRIS Pembayaran" class="mx-auto w-full max-w-[240px]">

            <p class="mt-3 text-sm font-bold text-slate-800">Kode Pesanan</p>
            <p id="qrModalKode" class="text-sm text-sky-600 font-mono"></p>

            <p class="mt-3 text-sm text-slate-500">Total Pembayaran</p>
            <p id="qrModalTotal" class="text-xl font-black text-slate-800"></p>
        </div>

        <p id="qrModalStatus" class="mb-5 text-xs text-slate-500">
            Menunggu pembayaran... status akan diperbarui otomatis.
        </p>

        <button type="button"
                onclick="closeQrModal()"
                class="w-full rounded-xl bg-sky-500 px-5 py-3 text-sm font-bold text-white hover:bg-sky-600">
            Tutup
        </button>
    </div>
</div>

<!-- Modal Ulasan -->
<div id="reviewModal"
     class="hidden fixed inset-0 z-[80] bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">

    <div class="bg-white rounded-3xl max-w-sm w-full p-7 shadow-2xl">
        <h3 class="text-xl font-bold text-slate-800 text-center mb-1">Beri Ulasan</h3>
        <p id="reviewKode" class="text-center text-xs text-slate-500 mb-4 font-mono"></p>

        <form id="reviewForm" method="POST" action="" class="space-y-4 text-sm">
            @csrf
            <input type="hidden" name="rating" id="reviewRating" value="">

            <div id="reviewStars" class="flex justify-center gap-1">
                @for($i = 1; $i <= 5; $i++)
                    <button type="button"
                            data-star="{{ $i }}"
                            onclick="setRating({{ $i }})"
                            class="text-4xl text-slate-300 transition">★</button>
                @endfor
            </div>

            <p id="reviewError" class="hidden text-center text-xs text-red-600">
                Pilih rating bintang 1 sampai 5.
            </p>

            <textarea name="komentar"
                      rows="3"
                      placeholder="Ceritakan pengalamanmu (opsional)"
                      class="w-full border border-slate-200 rounded-xl px-3 py-2 bg-slate-50 focus:ring-2 focus:ring-sky-400 outline-none"></textarea>

            <div class="flex gap-3">
                <button type="button"
                        onclick="closeReviewModal()"
                        class="flex-1 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl">
                    Batal
                </button>

                <button type="submit"
                        class="flex-1 py-3 bg-sky-500 hover:bg-sky-600 text-white font-bold rounded-xl">
                    Kirim
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Peringatan Validasi -->
<div id="alertModal"
     class="hidden fixed inset-0 z-[70] bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">

    <div class="bg-white rounded-3xl max-w-sm w-full p-7 text-center shadow-2xl">
        <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-amber-100">
            <span class="text-3xl">⚠️</span>
        </div>

        <h3 id="alertTitle" class="mb-2 text-xl font-bold text-slate-800">
            Perhatian
        </h3>

        <p id="alertMessage" class="mb-6 text-sm text-slate-500"></p>

        <button type="button"
                onclick="closeAlert()"
                class="w-full rounded-xl bg-sky-500 px-5 py-3 text-sm font-bold text-white hover:bg-sky-600">
            Mengerti
        </button>
    </div>
</div>

<script>
    const clothingInputs = document.querySelectorAll('.clothing-quantity');

    function openOrderModal() {
        document.getElementById('orderModal').classList.remove('hidden');
    }
function showAlert(message, title = 'Perhatian') {
    document.getElementById('alertTitle').innerText = title;
    document.getElementById('alertMessage').innerText = message;
    document.getElementById('alertModal').classList.remove('hidden');
}

function closeAlert() {
    document.getElementById('alertModal').classList.add('hidden');
}
    function closeOrderModal() {
        document.getElementById('orderModal').classList.add('hidden');
    }

    function changeService() {
        const select = document.getElementById('serviceSelect');
        const option = select.options[select.selectedIndex];

        const kiloanSection = document.getElementById('kiloanSection');
        const satuanSection = document.getElementById('satuanSection');
        const priceSummary = document.getElementById('priceSummary');

        kiloanSection.classList.add('hidden');
        satuanSection.classList.add('hidden');
        priceSummary.classList.add('hidden');

        document.getElementById('jumlahInput').value = '';

        if (!select.value) {
            return;
        }

        const kode = option.dataset.kode;

        if (kode === 'L001' || kode === 'L004') {
            kiloanSection.classList.remove('hidden');

            clothingInputs.forEach(input => {
                input.value = 0;
            });

        } else {
            satuanSection.classList.remove('hidden');

            const label = document.getElementById('satuanItemLabel');
            const description = document.getElementById('satuanDescription');

            if (kode === 'L005') {
                label.innerText = 'Jumlah Jas / Blazer (pcs)';
                description.innerText =
                    'Masukkan jumlah jas atau blazer yang akan di-dry clean.';
            } else {
                label.innerText = 'Jumlah Bed Cover / Selimut (pcs)';
                description.innerText =
                    'Masukkan jumlah bed cover atau selimut yang akan dicuci.';
            }

            document.getElementById('satuanQuantity').value = 1;
        }

        calculateOrder();
    }

    function calculateOrder() {
        const select = document.getElementById('serviceSelect');

        if (!select.value) {
            return;
        }

        const option = select.options[select.selectedIndex];

        const kode = option.dataset.kode;
        const nama = option.dataset.nama;
        const harga = parseFloat(option.dataset.harga);

        let jumlahPcs = 0;
        let beratGram = 0;
        let jumlahHitung = 0;
        let totalHarga = 0;
        let beratKg = 0;

        if (kode === 'L001' || kode === 'L004') {

            clothingInputs.forEach(input => {
                const qty = parseInt(input.value) || 0;
                const weight = parseFloat(input.dataset.weight);

                jumlahPcs += qty;
                beratGram += qty * weight;
            });

            if (jumlahPcs === 0) {
                document.getElementById('priceSummary').classList.add('hidden');
                document.getElementById('jumlahInput').value = '';
                return;
            }

            // Pembulatan ke atas setiap 0,1 kg.
            beratKg = Math.ceil((beratGram / 1000) * 10) / 10;

            jumlahHitung = beratKg;
            totalHarga = beratKg * harga;

            document.getElementById('weightSummary').classList.remove('hidden');

        } else {

            jumlahPcs = parseInt(
                document.getElementById('satuanQuantity').value
            ) || 0;

            if (jumlahPcs < 1) {
                document.getElementById('priceSummary').classList.add('hidden');
                document.getElementById('jumlahInput').value = '';
                return;
            }

            jumlahHitung = jumlahPcs;
            totalHarga = jumlahPcs * harga;

            document.getElementById('weightSummary').classList.add('hidden');
        }

        // Kirim nilai jumlah ke Controller.
        document.getElementById('jumlahInput').value = jumlahHitung;

        document.getElementById('priceSummary').classList.remove('hidden');

        document.getElementById('summaryService').innerText = nama;

        document.getElementById('summaryQuantity').innerText =
            jumlahPcs + ' pcs';

        document.getElementById('summaryWeight').innerText =
            beratKg.toFixed(1) + ' kg';

        document.getElementById('summaryRate').innerText =
            'Rp' + harga.toLocaleString('id-ID') +
            (kode === 'L001' || kode === 'L004' ? '/kg' : '/pcs');

        document.getElementById('summaryTotal').innerText =
            'Rp' + Math.round(totalHarga).toLocaleString('id-ID');
    }

    // Validasi sebelum form dikirim.
    document.getElementById('orderForm').addEventListener('submit', function(event) {

        const select = document.getElementById('serviceSelect');

        if (!select.value) {
            event.preventDefault();
           showAlert('Silakan pilih jenis layanan terlebih dahulu.');
            return;
        }

        const jumlah = document.getElementById('jumlahInput').value;

        if (!jumlah || parseFloat(jumlah) <= 0) {
            event.preventDefault();
            showAlert('Silakan isi jumlah pakaian atau barang terlebih dahulu.');
            return;
        }

        const option = select.options[select.selectedIndex];
        const kode = option.dataset.kode;

        // Pastikan layanan kiloan memiliki minimal satu pakaian.
        if (kode === 'L001' || kode === 'L004') {
            let totalPcs = 0;

            clothingInputs.forEach(input => {
                totalPcs += parseInt(input.value) || 0;
            });

            if (totalPcs < 1) {
                event.preventDefault();
                showAlert('Masukkan minimal satu pakaian.');
                return;
            }
        }

        // Pastikan layanan satuan menggunakan jumlah bulat.
        if (kode === 'L005' || kode === 'L006') {
            const qty = Number(
                document.getElementById('satuanQuantity').value
            );

            if (!Number.isInteger(qty) || qty < 1) {
                event.preventDefault();
                showAlert('Jumlah barang harus berupa angka bulat minimal 1 pcs.');
                return;
            }
        }
    });
</script>

<script>
    // ===== Cek status pembayaran otomatis =====
    // Pengecekan berikutnya baru dimulai SETELAH pengecekan sebelumnya
    // selesai, jadi permintaan tidak menumpuk kalau server sedang lambat.
    let pembayaranTimer = null;
    let pembayaranToken = 0;

    function mulaiCekPembayaran(kode, statusElId) {
        hentikanCekPembayaran();

        const token = ++pembayaranToken;
        const urlStatus = @json(url('/pelanggan/pesanan')) + '/' + encodeURIComponent(kode) + '/status';

        async function cek() {
            if (token !== pembayaranToken) return;

            try {
                const ctrl = new AbortController();
                const batas = setTimeout(function () { ctrl.abort(); }, 20000);

                const res = await fetch(urlStatus, {
                    headers: { 'Accept': 'application/json' },
                    signal: ctrl.signal
                });
                clearTimeout(batas);

                if (res.ok) {
                    const data = await res.json();
                    if (token !== pembayaranToken) return;

                    if (data.status_pembayaran !== 'Pending') {
                        hentikanCekPembayaran();

                        const el = document.getElementById(statusElId);
                        if (el) {
                            const sukses = data.status_pembayaran === 'Lunas';
                            el.textContent = sukses
                                ? 'Pembayaran berhasil! Memuat ulang halaman...'
                                : 'Pembayaran ' + data.status_pembayaran.toLowerCase() + '. Memuat ulang halaman...';
                            el.className = 'mb-5 text-sm font-bold ' + (sukses ? 'text-emerald-600' : 'text-red-600');
                        }

                        setTimeout(function () {
                            window.location.href = @json(route('pelanggan.dashboard'));
                        }, 1500);
                        return;
                    }
                }
            } catch (e) {
                // Abaikan, coba lagi nanti.
            }

            if (token === pembayaranToken) {
                pembayaranTimer = setTimeout(cek, 5000);
            }
        }

        pembayaranTimer = setTimeout(cek, 3000);
    }

    function hentikanCekPembayaran() {
        pembayaranToken++;
        if (pembayaranTimer) {
            clearTimeout(pembayaranTimer);
            pembayaranTimer = null;
        }
    }

    // ===== Modal QR (tombol "Bayar" di riwayat) =====
    function openQrModal(btn) {
        document.getElementById('qrModalImage').src = btn.dataset.qr;
        document.getElementById('qrModalKode').textContent = btn.dataset.kode;
        document.getElementById('qrModalTotal').textContent =
            'Rp' + Number(btn.dataset.total).toLocaleString('id-ID');

        const status = document.getElementById('qrModalStatus');
        status.textContent = 'Menunggu pembayaran... status akan diperbarui otomatis.';
        status.className = 'mb-5 text-xs text-slate-500';

        document.getElementById('qrModal').classList.remove('hidden');
        mulaiCekPembayaran(btn.dataset.kode, 'qrModalStatus');
    }

    function closeQrModal() {
        document.getElementById('qrModal').classList.add('hidden');
        hentikanCekPembayaran();
    }

    // ===== Modal Ulasan =====
    function openReviewModal(btn) {
        const form = document.getElementById('reviewForm');

        form.reset();
        form.action = @json(route('pelanggan.ulasan.store', ['idPesanan' => '__ID__'])).replace('__ID__', btn.dataset.id);

        document.getElementById('reviewKode').textContent = btn.dataset.kode;
        setRating(0);
        document.getElementById('reviewModal').classList.remove('hidden');
    }

    function closeReviewModal() {
        document.getElementById('reviewModal').classList.add('hidden');
    }

    function setRating(n) {
        document.getElementById('reviewRating').value = n > 0 ? n : '';

        document.querySelectorAll('#reviewStars button').forEach(function (b) {
            const aktif = Number(b.dataset.star) <= n;
            b.classList.toggle('text-amber-400', aktif);
            b.classList.toggle('text-slate-300', !aktif);
        });

        if (n > 0) {
            document.getElementById('reviewError').classList.add('hidden');
        }
    }

    document.getElementById('reviewForm').addEventListener('submit', function (e) {
        if (!document.getElementById('reviewRating').value) {
            e.preventDefault();
            document.getElementById('reviewError').classList.remove('hidden');
        }
    });

    // ===== Popup QR setelah pesanan baru dibuat: ikut dicek otomatis =====
    @if(session('qr_code_url'))
        mulaiCekPembayaran(@json(session('kode_pesanan')), 'qrSessionStatus');
    @endif
</script>

@endsection