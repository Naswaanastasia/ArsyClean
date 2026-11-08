@extends('layouts.pelanggan')
@section('title', 'Dashboard Pelanggan - ArsyClean Laundry')

@section('content')

{{-- ===== POPUP HASIL PEMESANAN (SUKSES / GAGAL / QRIS) ===== --}}
@if (session('success') || session('error'))
    <div id="notificationModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4 overflow-y-auto">
        <div class="w-full max-w-md rounded-3xl bg-white p-7 text-center shadow-2xl my-6 border border-slate-100">
            @if(session('qr_code_url'))
                <div class="mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-2xl bg-sky-100 text-3xl">
                    📱
                </div>
                <h3 class="text-xl font-black text-slate-800">
                    Pesanan Berhasil Dibuat!
                </h3>
                <p class="text-xs text-slate-500 mt-1 mb-4">
                    Silakan scan QRIS di bawah ini untuk menyelesaikan pembayaran.
                </p>

                <div class="rounded-2xl border-2 border-sky-100 bg-slate-50/50 p-4 mb-4">
                    <img src="{{ session('qr_code_url') }}"
                         alt="QRIS Pembayaran ArsyClean"
                         class="mx-auto w-full max-w-[250px] rounded-xl shadow-xs border border-slate-200">

                    <div class="mt-3 text-xs font-semibold text-slate-500">Kode Pesanan:</div>
                    <div class="text-base font-black text-blue-900 font-mono tracking-wider">#{{ session('kode_pesanan') }}</div>

                    <div class="mt-2 text-xs text-slate-500">Total Tagihan:</div>
                    <div class="text-2xl font-black text-slate-900">Rp {{ number_format(session('total_bayar'), 0, ',', '.') }}</div>

                    <div class="mt-3 p-2.5 bg-blue-50/80 rounded-xl text-left text-xs text-blue-900">
                        <div class="font-bold">💡 Alternatif Transfer Manual:</div>
                        <div>BCA: <b>8735-1234-5678</b> a.n ArsyClean Laundry</div>
                        <div>Mandiri: <b>1310-0987-6543</b> a.n ArsyClean Laundry</div>
                    </div>
                </div>

                <p id="qrSessionStatus" class="mb-5 text-xs font-semibold text-slate-500">
                    ⏳ Menunggu pembayaran... Status akan diperbarui secara otomatis.
                </p>
            @else
                <div class="mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-2xl {{ session('success') ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-600' }} text-2xl font-black">
                    {{ session('success') ? '✓' : '!' }}
                </div>
                <h3 class="text-xl font-black text-slate-800">
                    {{ session('success') ? 'Berhasil!' : 'Terjadi Kesalahan' }}
                </h3>
                <p class="mt-2 mb-6 text-sm text-slate-600 leading-relaxed">
                    {{ session('success') ?? session('error') }}
                </p>
            @endif

            <button type="button"
                    onclick="hentikanCekPembayaran(); document.getElementById('notificationModal').remove()"
                    class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl shadow-md shadow-blue-600/30 transition-all">
                Tutup Jendela
            </button>
        </div>
    </div>
@endif

<div class="space-y-7">

    <!-- Header Sapaan & Call to Action -->
    <div class="bg-gradient-to-r from-blue-700 via-blue-800 to-indigo-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="space-y-1.5">
            <span class="inline-block px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-white/20 text-sky-200">
                🧺 Layanan Laundry Online 24 Jam
            </span>
            <h1 class="text-2xl sm:text-3xl font-black tracking-tight">
                Halo, {{ $user->nama }}! 👋
            </h1>
            <p class="text-sm text-blue-100 max-w-xl leading-relaxed">
                Pesan layanan pick up laundry antar jemput dengan cepat. Kurir ArsyClean siap menjemput cucian langsung ke rumah Anda.
            </p>
        </div>

        <button type="button"
                onclick="openOrderModal()"
                class="shrink-0 bg-white hover:bg-blue-50 text-blue-900 font-extrabold text-sm px-6 py-3.5 rounded-2xl shadow-lg transition-all flex items-center justify-center gap-2">
            <span>+</span> Pesan Laundry Sekarang
        </button>
    </div>

    <!-- Active Orders Tracking Cards -->
    @if($activePesanans->isNotEmpty())
        <div id="pesanan-aktif" class="scroll-mt-6 space-y-5">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg sm:text-xl font-black text-slate-800 flex items-center gap-2">
                        <span class="w-8 h-8 rounded-xl bg-sky-100 text-sky-700 flex items-center justify-center text-sm font-black">🔄</span>
                        Cucian Sedang Berlangsung
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                        Status realtime cucian yang sedang dikerjakan oleh tim ArsyClean.
                    </p>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-bold bg-sky-100 text-sky-800">
                    {{ $activePesanans->count() }} Pesanan Aktif
                </span>
            </div>

            @foreach($activePesanans as $ap)
                @php
                    $stages = $ap->daftarTahapLaundry();
                    $currentIndex = array_search($ap->status_laundry, $stages);
                    if ($currentIndex === false) { $currentIndex = 0; }
                    $layananAp = $ap->layanan->first();

                    $warnaTahap = [
                        'Menunggu Antrean' => ['dot' => 'bg-amber-500',   'badge' => 'bg-amber-100 text-amber-800 border-amber-300'],
                        'Pencucian'        => ['dot' => 'bg-sky-500',     'badge' => 'bg-sky-100 text-sky-800 border-sky-300'],
                        'Pengeringan'      => ['dot' => 'bg-teal-500',    'badge' => 'bg-teal-100 text-teal-800 border-teal-300'],
                        'Setrika'          => ['dot' => 'bg-purple-500',  'badge' => 'bg-purple-100 text-purple-800 border-purple-300'],
                        'Siap Diambil'     => ['dot' => 'bg-emerald-500', 'badge' => 'bg-emerald-100 text-emerald-800 border-emerald-300'],
                        'Siap Dikirim',
                        'Dalam Pengiriman' => ['dot' => 'bg-orange-500',  'badge' => 'bg-orange-100 text-orange-800 border-orange-300'],
                        'Selesai'          => ['dot' => 'bg-emerald-600', 'badge' => 'bg-emerald-100 text-emerald-800 border-emerald-300'],
                    ];
                    $currentWarna = $warnaTahap[$ap->status_laundry] ?? $warnaTahap['Menunggu Antrean'];
                @endphp

                <div id="card-pesanan-{{ $ap->kode_pesanan }}" class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200 shadow-sm space-y-5">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100">
                        <div>
                            <div class="flex items-center gap-2.5">
                                <span class="font-mono text-base font-black text-blue-900">#{{ $ap->kode_pesanan }}</span>
                                <span class="px-3 py-0.5 rounded-full text-xs font-black border {{ $currentWarna['badge'] }}">
                                    {{ $ap->status_laundry }}
                                </span>
                            </div>
                            @if($layananAp)
                                <p class="text-xs text-slate-500 mt-1">
                                    {{ $layananAp->nama_layanan }} ({{ $layananAp->pivot->jumlah }} {{ $layananAp->satuan }}) &bull;
                                    Total: <b>Rp {{ number_format($ap->total_harga, 0, ',', '.') }}</b>
                                </p>
                            @endif
                        </div>

                        <div class="flex items-center gap-2">
                            <button type="button"
                                    onclick="bukaDetailPesanan('{{ $ap->id_pesanan }}')"
                                    class="px-3.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition">
                                🔍 Lihat Detail
                            </button>
                            <a href="{{ route('pelanggan.pesanan.nota', $ap->id_pesanan) }}"
                               target="_blank"
                               class="px-3.5 py-1.5 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-bold transition">
                                🖨️ Cetak Nota
                            </a>
                        </div>
                    </div>

                    <!-- Visual Timeline Tracker Sesuai Kode Pesanan -->
                    <div class="overflow-x-auto py-2">
                        <div class="flex items-center justify-between min-w-[550px] relative">
                            <!-- Background line -->
                            <div class="absolute top-4 left-6 right-6 h-1 bg-slate-200 -z-0"></div>

                            @foreach($stages as $idx => $stg)
                                @php
                                    $isPassed = $idx <= $currentIndex;
                                    $isCurrent = $idx === $currentIndex;
                                @endphp
                                <div class="relative z-10 flex flex-col items-center text-center flex-1">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-black transition-all
                                        {{ $isPassed ? 'bg-blue-600 text-white ring-4 ring-blue-100' : 'bg-slate-200 text-slate-400' }}">
                                        {{ $isPassed ? '✓' : ($idx + 1) }}
                                    </div>
                                    <span class="text-[11px] mt-2 font-bold max-w-[85px] leading-tight
                                        {{ $isCurrent ? 'text-blue-700' : ($isPassed ? 'text-slate-800' : 'text-slate-400') }}">
                                        {{ $stg }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    @if($ap->pengiriman)
                        <div class="p-3 bg-emerald-50/70 border border-emerald-200/60 rounded-xl text-xs text-emerald-900 flex items-center justify-between">
                            <span>🚚 Kurir Penjemput/Pengantar: <b>{{ $ap->pengiriman->nama_kurir }}</b> ({{ $ap->pengiriman->no_hp_kurir }})</span>
                            <span class="font-bold">Status: {{ $ap->pengiriman->status_pengiriman }}</span>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    <!-- Banner Katalog Layanan Pick Up -->
    <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-7 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-5">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-2xl bg-sky-100 flex items-center justify-center text-3xl shrink-0">
                🚚
            </div>
            <div>
                <h3 class="text-lg sm:text-xl font-black text-slate-800">
                    Layanan Pick Up &amp; Antar-Jemput Cucian
                </h3>
                <p class="text-sm text-slate-500 mt-0.5">
                    Kurir kami siap menjemput cucian ke rumah Anda sesuai jadwal yang Anda pilih.
                </p>
            </div>
        </div>

        <button type="button"
                onclick="openOrderModal()"
                class="shrink-0 px-6 py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-sm rounded-xl shadow-md shadow-blue-600/30 transition">
            Buat Pesanan Baru &rarr;
        </button>
    </div>

    <!-- Riwayat Pesanan Saya (Read, Klik untuk Melihat Detail & Aksi) -->
    <div id="riwayat" class="scroll-mt-6 bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6 pb-4 border-b border-slate-100">
            <div>
                <h3 class="text-lg sm:text-xl font-black text-slate-800 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center text-sm font-black">📑</span>
                    Riwayat Pesanan Saya
                </h3>
                <p class="text-sm text-slate-500 mt-1">
                    Daftar seluruh pesanan Anda. Klik pada baris pesanan atau tombol detail untuk melihat rincian lengkap &amp; aksi.
                </p>
            </div>
            <span class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-slate-100 text-slate-700 self-start sm:self-auto">
                Total: {{ $pesanans->count() }} Pesanan
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-500 uppercase text-xs border-b border-slate-200">
                    <tr>
                        <th class="p-3.5">Kode</th>
                        <th class="p-3.5">Tanggal</th>
                        <th class="p-3.5">Layanan</th>
                        <th class="p-3.5">Jumlah</th>
                        <th class="p-3.5">Total (Rp)</th>
                        <th class="p-3.5">Status Laundry</th>
                        <th class="p-3.5">Pembayaran</th>
                        <th class="p-3.5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pesanans as $p)
                        @php
                            $statusBayar = $p->pembayaran?->status_pembayaran ?? 'Pending';
                            $layananItem = $p->layanan->first();
                        @endphp
                        <tr class="hover:bg-slate-50/80 cursor-pointer transition-colors" onclick="bukaDetailPesanan('{{ $p->id_pesanan }}')">
                            <td class="p-3.5 font-mono font-bold text-blue-900">
                                #{{ $p->kode_pesanan }}
                            </td>
                            <td class="p-3.5 text-xs text-slate-500 font-medium">
                                {{ $p->tanggal_pesanan ? $p->tanggal_pesanan->format('d M Y') : '-' }}
                            </td>
                            <td class="p-3.5 font-bold text-slate-800">
                                {{ $layananItem->nama_layanan ?? 'Layanan' }}
                            </td>
                            <td class="p-3.5 text-xs text-slate-600">
                                @if($layananItem)
                                    {{ $layananItem->pivot->jumlah }} {{ $layananItem->satuan }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="p-3.5 font-black text-slate-900">
                                Rp {{ number_format($p->total_harga, 0, ',', '.') }}
                            </td>
                            <td class="p-3.5">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold
                                    {{ $p->status_pesanan === 'Selesai' ? 'bg-emerald-100 text-emerald-800' : ($p->status_pesanan === 'Dibatalkan' ? 'bg-rose-100 text-rose-800' : 'bg-sky-100 text-sky-800') }}">
                                    {{ $p->status_pesanan === 'Diproses' ? $p->status_laundry : $p->status_pesanan }}
                                </span>
                            </td>
                            <td class="p-3.5">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold
                                    {{ $statusBayar === 'Lunas' ? 'bg-emerald-100 text-emerald-800' : ($statusBayar === 'Pending' ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800') }}">
                                    {{ $statusBayar }}
                                </span>
                            </td>
                            <td class="p-3.5 text-center" onclick="event.stopPropagation()">
                                <div class="flex items-center justify-center gap-1.5 flex-wrap">
                                    <button type="button"
                                            onclick="bukaDetailPesanan('{{ $p->id_pesanan }}')"
                                            class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-bold transition">
                                        Detail
                                    </button>

                                    <a href="{{ route('pelanggan.pesanan.nota', $p->id_pesanan) }}"
                                       target="_blank"
                                       title="Cetak Struk / Nota PDF Kapan Saja"
                                       class="px-2.5 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg text-xs font-bold transition">
                                        🖨️ PDF
                                    </a>

                                    @if($p->status_pesanan === 'Menunggu Pembayaran' && $statusBayar === 'Pending')
                                        <button type="button"
                                                data-kode="{{ $p->kode_pesanan }}"
                                                data-qr="{{ $p->pembayaran?->qr_code_url ?? route('pelanggan.qris.manual', $p->kode_pesanan) }}"
                                                data-total="{{ (int) round($p->total_harga) }}"
                                                data-id="{{ $p->id_pesanan }}"
                                                onclick="openQrModal(this)"
                                                class="px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white rounded-lg text-xs font-bold shadow-xs transition">
                                            Bayar
                                        </button>
                                    @elseif($p->ulasan)
                                        <span class="text-amber-500 font-bold text-xs" title="Rating: {{ $p->ulasan->rating }} Bintang">
                                            {{ str_repeat('★', (int) $p->ulasan->rating) }}
                                        </span>
                                    @elseif($p->status_pesanan === 'Selesai')
                                        <button type="button"
                                                data-id="{{ $p->id_pesanan }}"
                                                data-kode="{{ $p->kode_pesanan }}"
                                                onclick="openReviewModal(this)"
                                                class="px-2.5 py-1.5 bg-amber-500 hover:bg-amber-600 text-white rounded-lg text-xs font-bold transition">
                                            Ulasan
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-8 text-center text-slate-400">
                                Belum ada riwayat pesanan laundry. Silakan klik "Pesan Laundry Sekarang" untuk mulai.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- ================= MODAL PEMESANAN PICK UP LAUNDRY ================= --}}
<div id="orderModal" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-xl w-full p-6 sm:p-8 shadow-2xl max-h-[90vh] overflow-y-auto space-y-5">
        <div class="flex justify-between items-start pb-3 border-b border-slate-100">
            <div>
                <h3 class="text-xl font-black text-slate-800">Pemesanan Pick Up Laundry</h3>
                <p class="text-xs text-slate-500 mt-0.5">Lengkapi data cucian dan jadwal penjemputan ke rumah Anda.</p>
            </div>
            <button type="button" onclick="closeOrderModal()" class="text-slate-400 hover:text-slate-700 text-2xl font-bold">✕</button>
        </div>

        <form action="{{ route('pelanggan.pesanan.store') }}" method="POST" id="orderForm" class="space-y-4 text-sm">
            @csrf

            <!-- 1. Pilihan Layanan (1 Layanan = 1 Kode Pesanan) -->
            <div>
                <label class="block font-bold text-slate-700 mb-1.5">
                    Pilihan Jenis Layanan <span class="text-red-500 font-bold">*</span>
                </label>
                <select name="id_layanan"
                        id="serviceSelect"
                        required
                        onchange="changeService()"
                        class="w-full border border-slate-200 rounded-xl px-4 py-3 bg-slate-50 text-sm font-bold text-slate-800 focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">-- Pilih Jenis Layanan Laundry --</option>
                    @foreach([
                        'L001' => 'Cuci Kering Reguler (Kiloan)',
                        'L004' => 'Cuci Komplit + Antar Jemput (Kiloan)',
                        'L005' => 'Dry Clean Jas / Blazer (Satuan)',
                        'L006' => 'Cuci Bed Cover / Selimut Besar (Satuan)'
                    ] as $kode => $nama)
                        @php
                            $layanan = $layanans->firstWhere('kode_layanan', $kode);
                        @endphp
                        @if($layanan)
                            <option value="{{ $layanan->id_layanan }}"
                                    data-kode="{{ $kode }}"
                                    data-nama="{{ $nama }}"
                                    data-harga="{{ $layanan->harga }}"
                                    data-satuan="{{ $layanan->satuan }}"
                                    data-durasi="{{ $layanan->durasi }}">
                                {{ $nama }} &bull; Rp {{ number_format($layanan->harga, 0, ',', '.') }}/{{ $layanan->satuan }} ({{ $layanan->durasi }})
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>

            <!-- 2. Rincian Pakaian / Berat (Kiloan) -->
            <div id="clothingEstimator" class="hidden space-y-3 p-4 bg-slate-50 border border-slate-200 rounded-2xl">
                <div class="flex items-center justify-between">
                    <label class="font-bold text-xs text-slate-700 uppercase">
                        Estimasi Pakaian Cucian <span class="text-red-500 font-bold">*</span>
                    </label>
                    <span class="text-[11px] text-blue-600 font-bold">Otomatis dihitung ke kg</span>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5 text-xs max-h-48 overflow-y-auto pr-1">
                    @foreach([
                        'kaos'          => 'Kaos / T-Shirt',
                        'kemeja'        => 'Kemeja',
                        'celana_bahan'  => 'Celana Bahan',
                        'celana_jeans'  => 'Celana Jeans',
                        'celana_pendek' => 'Celana Pendek',
                        'jaket_tipis'   => 'Jaket Tipis',
                        'jaket_tebal'   => 'Jaket Tebal / Hoodie',
                        'rok_tipis'     => 'Rok / Dress',
                        'handuk_besar'  => 'Handuk Mandi',
                        'baju_tidur'    => 'Piyama / Baju Tidur'
                    ] as $key => $label)
                        <div class="bg-white p-2 rounded-xl border border-slate-200">
                            <span class="block text-[11px] font-bold text-slate-700 truncate">{{ $label }}</span>
                            <input type="number"
                                   min="0"
                                   name="pakaian[{{ $key }}]"
                                   placeholder="0"
                                   class="clothing-input w-full mt-1 px-2 py-1 bg-slate-50 border rounded-lg text-xs font-bold text-slate-800"
                                   oninput="calculateTotal()">
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Rincian Satuan (L005 / L006) -->
            <div id="unitSection" class="hidden space-y-2 p-4 bg-slate-50 border border-slate-200 rounded-2xl">
                <label class="block font-bold text-xs text-slate-700 uppercase">
                    Jumlah Barang (Pcs) <span class="text-red-500 font-bold">*</span>
                </label>
                <input type="number"
                       id="satuanQuantity"
                       min="1"
                       value="1"
                       class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-bold"
                       oninput="calculateTotal()">
            </div>

            <input type="hidden" name="jumlah" id="jumlahInput">

            <!-- 3. Jadwal & Waktu Penjemputan -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-xs text-slate-700 mb-1">
                        Tanggal Penjemputan <span class="text-red-500 font-bold">*</span>
                    </label>
                    <input type="date"
                           name="tanggal"
                           min="{{ date('Y-m-d') }}"
                           value="{{ date('Y-m-d') }}"
                           required
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold">
                </div>
                <div>
                    <label class="block font-bold text-xs text-slate-700 mb-1">
                        Jam Penjemputan <span class="text-red-500 font-bold">*</span>
                    </label>
                    <input type="time"
                           name="waktu"
                           value="09:00"
                           required
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold">
                </div>
            </div>

            <!-- 4. Alamat Penjemputan -->
            <div>
                <label class="block font-bold text-xs text-slate-700 mb-1">
                    Alamat Lengkap Penjemputan <span class="text-red-500 font-bold">*</span>
                </label>
                <textarea name="alamat"
                          rows="2"
                          required
                          placeholder="Nama jalan, nomor rumah, RT/RW, patokan..."
                          class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:bg-white">{{ $user->alamat }}</textarea>
            </div>

            <!-- 5. Catatan Khusus Pelanggan -->
            <div>
                <label class="block font-bold text-xs text-slate-700 mb-1">
                    Catatan Tambahan untuk Admin &amp; Kurir
                </label>
                <textarea name="catatan"
                          rows="2"
                          placeholder="Contoh: Pisahkan pakaian putih, jemput setelah jam makan siang..."
                          class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:bg-white"></textarea>
            </div>

            <!-- 6. Metode Pembayaran & QRIS Manual Fallback -->
            <div>
                <label class="block font-bold text-xs text-slate-700 mb-1.5">
                    Metode Pembayaran <span class="text-red-500 font-bold">*</span>
                </label>
                <div class="grid grid-cols-2 gap-2.5 text-xs">
                    <label class="flex items-center gap-2 p-3 bg-slate-50 border border-slate-200 rounded-xl cursor-pointer hover:bg-blue-50/50">
                        <input type="radio" name="metode_pembayaran" value="QRIS" checked class="accent-blue-600">
                        <div>
                            <span class="font-bold text-slate-800 block">QRIS Otomatis / Manual</span>
                            <span class="text-[10px] text-slate-400">Scan QRIS BCA/GoPay/OVO</span>
                        </div>
                    </label>
                    <label class="flex items-center gap-2 p-3 bg-slate-50 border border-slate-200 rounded-xl cursor-pointer hover:bg-blue-50/50">
                        <input type="radio" name="metode_pembayaran" value="TUNAI" class="accent-blue-600">
                        <div>
                            <span class="font-bold text-slate-800 block">Bayar Tunai</span>
                            <span class="text-[10px] text-slate-400">Bayar saat kurir datang</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Total Harga Estimasi -->
            <div id="priceSummary" class="hidden p-4 bg-blue-50 border border-blue-200 rounded-2xl flex items-center justify-between">
                <div>
                    <span class="text-xs text-blue-700 font-bold block" id="summaryService">Layanan Laundry</span>
                    <span class="text-xs text-slate-600" id="summaryDetails">0 kg &bull; Rp 0</span>
                </div>
                <div class="text-right">
                    <span class="text-xs text-slate-400 block font-semibold">Total Pembayaran</span>
                    <span class="text-xl font-black text-blue-900" id="summaryTotal">Rp 0</span>
                </div>
            </div>

            <div class="pt-2 flex justify-end gap-2">
                <button type="button" onclick="closeOrderModal()" class="px-5 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-sm">
                    Batal
                </button>
                <button type="submit" class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-sm shadow-md shadow-blue-600/30">
                    Konfirmasi &amp; Lanjutkan &rarr;
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ================= MODAL DETAIL PESANAN INTERAKTIF ================= --}}
<div id="detailModal" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-7 shadow-2xl max-h-[90vh] overflow-y-auto space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div>
                <h3 class="text-lg font-black text-slate-800">Detail Pesanan #<span id="detailKode"></span></h3>
                <span class="text-xs text-slate-400" id="detailTanggal">-</span>
            </div>
            <button type="button" onclick="closeDetailModal()" class="text-slate-400 hover:text-slate-700 text-2xl font-bold">✕</button>
        </div>

        <div id="detailLoading" class="py-10 text-center text-slate-400">Memuat rincian pesanan...</div>

        <div id="detailContent" class="hidden space-y-4 text-xs">
            <!-- Status Badge & Tracker -->
            <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-2xl flex items-center justify-between">
                <div>
                    <span class="text-[11px] text-slate-400 block">Status Pengerjaan</span>
                    <span class="text-sm font-black text-blue-900" id="detailStatusLaundry">-</span>
                </div>
                <div class="text-right">
                    <span class="text-[11px] text-slate-400 block">Pembayaran</span>
                    <span class="px-2.5 py-1 rounded-full text-xs font-bold" id="detailStatusBayar">-</span>
                </div>
            </div>

            <!-- Rincian Layanan -->
            <div>
                <h4 class="font-bold text-slate-700 uppercase tracking-wider text-[11px] mb-1.5">Rincian Layanan:</h4>
                <div id="detailLayananList" class="p-3 bg-slate-50 rounded-xl border border-slate-200 space-y-1"></div>
            </div>

            <!-- Catatan Pelanggan -->
            <div>
                <h4 class="font-bold text-slate-700 uppercase tracking-wider text-[11px] mb-1">Catatan Pesanan:</h4>
                <p id="detailCatatan" class="p-3 bg-slate-50 rounded-xl border border-slate-200 text-slate-700 whitespace-pre-line">-</p>
            </div>

            <!-- Alamat Penjemputan -->
            <div>
                <h4 class="font-bold text-slate-700 uppercase tracking-wider text-[11px] mb-1">Alamat Penjemputan:</h4>
                <p id="detailAlamat" class="p-3 bg-slate-50 rounded-xl border border-slate-200 text-slate-700">-</p>
            </div>

            <!-- Info Kurir -->
            <div id="detailKurirBox" class="hidden p-3 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-900">
                <div class="font-bold">🚚 Penugasan Kurir:</div>
                <div id="detailKurirInfo" class="mt-0.5"></div>
            </div>

            <!-- Form Upload Bukti Transfer Manual (Jika Pending) -->
            <div id="detailUploadSection" class="hidden p-4 bg-amber-50 border border-amber-200 rounded-2xl space-y-2">
                <div class="font-bold text-amber-900 text-xs">Unggah Bukti Transfer Manual:</div>
                <p class="text-[11px] text-amber-700">Jika Anda membayar melalui transfer bank atau QRIS manual, silakan unggah foto bukti transfer di sini.</p>
                <form id="detailUploadForm" method="POST" enctype="multipart/form-data" class="space-y-2">
                    @csrf
                    <input type="file" name="bukti_transfer" accept="image/*" required class="w-full text-xs file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-amber-600 file:text-white">
                    <button type="submit" class="w-full py-2 bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs rounded-xl shadow-xs">
                        Kirim Bukti Transfer
                    </button>
                </form>
            </div>

            <!-- Total & Action Buttons -->
            <div class="pt-2 flex items-center justify-between border-t border-slate-100">
                <div>
                    <span class="text-[11px] text-slate-400 block font-semibold">Total Tagihan</span>
                    <span class="text-xl font-black text-slate-900" id="detailTotal">Rp 0</span>
                </div>
                <div class="flex items-center gap-2">
                    <a id="detailNotaBtn" href="#" target="_blank" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                        <span>🖨️</span> Cetak Nota PDF
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ================= MODAL QR CODE PEMBAYARAN ================= --}}
<div id="qrModal" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-sm w-full p-6 text-center shadow-2xl space-y-4">
        <div class="flex justify-between items-center pb-2 border-b border-slate-100">
            <h3 class="text-base font-black text-slate-800">Scan QRIS Pembayaran</h3>
            <button type="button" onclick="closeQrModal()" class="text-slate-400 hover:text-slate-700 text-xl font-bold">✕</button>
        </div>

        <div class="p-3 bg-slate-50 border border-slate-200 rounded-2xl">
            <img id="qrModalImage" src="" alt="QR Code" class="mx-auto w-full max-w-[240px] rounded-xl shadow-xs border border-slate-200">
            <div class="mt-2 text-xs text-slate-400">Kode Pesanan:</div>
            <div id="qrModalKode" class="font-mono font-black text-blue-900 text-sm"></div>
            <div class="mt-1 text-xs text-slate-400">Total Nominal:</div>
            <div id="qrModalTotal" class="font-black text-slate-900 text-xl"></div>
        </div>

        <div class="p-2.5 bg-blue-50/80 rounded-xl text-left text-xs text-blue-900">
            <div class="font-bold">💡 Opsi Transfer Bank Manual:</div>
            <div>BCA: <b>8735-1234-5678</b></div>
            <div>Mandiri: <b>1310-0987-6543</b></div>
        </div>

        <p id="qrModalStatus" class="text-xs font-semibold text-slate-500">
            ⏳ Menunggu pembayaran... Status akan otomatis diperbarui setelah dibayar.
        </p>

        <button type="button" onclick="closeQrModal()" class="w-full py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold">
            Tutup
        </button>
    </div>
</div>

{{-- ================= MODAL ULASAN BINTANG ================= --}}
<div id="reviewModal" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-sm w-full p-6 text-center shadow-2xl space-y-4">
        <h3 class="text-lg font-black text-slate-800">Beri Ulasan Layanan</h3>
        <p class="text-xs text-slate-500">Bagikan pengalaman Anda menggunakan jasa laundry ArsyClean.</p>

        <form id="reviewForm" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="rating" id="reviewRating" required>

            <div class="flex justify-center gap-2 text-3xl" id="reviewStars">
                @for($i = 1; $i <= 5; $i++)
                    <button type="button" data-star="{{ $i }}" onclick="setRating({{ $i }})" class="text-slate-300 hover:text-amber-400 transition-colors">
                        ★
                    </button>
                @endfor
            </div>

            <textarea name="komentar" rows="3" placeholder="Tuliskan ulasan atau saran Anda (opsional)..." class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs"></textarea>

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="closeReviewModal()" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold shadow-md">Kirim Ulasan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const clothesWeights = {
        kaos: 100, kemeja: 111, celana_bahan: 167, celana_jeans: 250,
        celana_pendek: 118, jaket_tipis: 333, jaket_tebal: 400,
        rok_tipis: 154, handuk_besar: 333, baju_tidur: 154
    };

    function openOrderModal() {
        document.getElementById('orderModal').classList.remove('hidden');
    }
    function closeOrderModal() {
        document.getElementById('orderModal').classList.add('hidden');
    }

    function changeService() {
        const select = document.getElementById('serviceSelect');
        const selected = select.options[select.selectedIndex];
        const kode = selected?.dataset?.kode;

        const clothingSec = document.getElementById('clothingEstimator');
        const unitSec = document.getElementById('unitSection');

        if (kode === 'L001' || kode === 'L004') {
            clothingSec.classList.remove('hidden');
            unitSec.classList.add('hidden');
        } else if (kode === 'L005' || kode === 'L006') {
            clothingSec.classList.add('hidden');
            unitSec.classList.remove('hidden');
        } else {
            clothingSec.classList.add('hidden');
            unitSec.classList.add('hidden');
        }
        calculateTotal();
    }

    function calculateTotal() {
        const select = document.getElementById('serviceSelect');
        const selected = select.options[select.selectedIndex];
        if (!selected || !selected.value) return;

        const kode = selected.dataset.kode;
        const harga = parseFloat(selected.dataset.harga) || 0;
        const nama = selected.dataset.nama || '';
        let jumlah = 0;
        let total = 0;

        if (kode === 'L001' || kode === 'L004') {
            let totalGram = 0;
            document.querySelectorAll('.clothing-input').forEach(inp => {
                const qty = parseInt(inp.value) || 0;
                const match = inp.name.match(/\[(.*?)\]/);
                const type = match ? match[1] : '';
                if (clothesWeights[type] && qty > 0) {
                    totalGram += qty * clothesWeights[type];
                }
            });
            const beratKg = totalGram > 0 ? Math.ceil((totalGram / 1000) * 10) / 10 : 0;
            jumlah = beratKg;
            total = beratKg * harga;
            document.getElementById('summaryDetails').innerText = beratKg.toFixed(1) + ' kg • Rp ' + Math.round(harga).toLocaleString('id-ID') + '/kg';
        } else {
            const qty = parseInt(document.getElementById('satuanQuantity').value) || 1;
            jumlah = qty;
            total = qty * harga;
            document.getElementById('summaryDetails').innerText = qty + ' pcs • Rp ' + Math.round(harga).toLocaleString('id-ID') + '/pcs';
        }

        document.getElementById('jumlahInput').value = jumlah;
        document.getElementById('summaryService').innerText = nama;
        document.getElementById('summaryTotal').innerText = 'Rp ' + Math.round(total).toLocaleString('id-ID');
        document.getElementById('priceSummary').classList.remove('hidden');
    }

    // Modal Detail Pesanan AJAX
    async function bukaDetailPesanan(id) {
        document.getElementById('detailModal').classList.remove('hidden');
        document.getElementById('detailLoading').classList.remove('hidden');
        document.getElementById('detailContent').classList.add('hidden');

        try {
            const res = await fetch('{{ url("/pelanggan/pesanan") }}/' + id + '/detail', {
                headers: { 'Accept': 'application/json' }
            });
            const resData = await res.json();
            if (resData.success) {
                const d = resData.data;
                document.getElementById('detailKode').textContent = d.kode_pesanan;
                document.getElementById('detailTanggal').textContent = d.tanggal_pesanan;
                document.getElementById('detailStatusLaundry').textContent = d.status_laundry;
                document.getElementById('detailStatusBayar').textContent = d.status_pembayaran + ' (' + d.metode_pembayaran + ')';
                document.getElementById('detailStatusBayar').className = 'px-2.5 py-1 rounded-full text-xs font-bold ' +
                    (d.status_pembayaran === 'Lunas' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800');

                document.getElementById('detailCatatan').textContent = d.catatan || 'Tidak ada catatan tambahan.';
                document.getElementById('detailAlamat').textContent = d.alamat_penjemputan;
                document.getElementById('detailTotal').textContent = d.total_harga_format;

                // Render Layanan List
                const list = document.getElementById('detailLayananList');
                list.innerHTML = '';
                d.layanan.forEach(item => {
                    const row = document.createElement('div');
                    row.className = 'flex justify-between items-center';
                    row.innerHTML = '<b>' + item.nama_layanan + ' (' + item.jumlah + ' ' + item.satuan + ')</b><span>Rp ' + Number(item.subtotal).toLocaleString('id-ID') + '</span>';
                    list.appendChild(row);
                });

                // Kurir
                const kurirBox = document.getElementById('detailKurirBox');
                if (d.pengiriman) {
                    kurirBox.classList.remove('hidden');
                    document.getElementById('detailKurirInfo').innerHTML = 'Nama: <b>' + d.pengiriman.nama_kurir + '</b> (' + d.pengiriman.no_hp_kurir + ') • Resi: ' + d.pengiriman.no_resi;
                } else {
                    kurirBox.classList.add('hidden');
                }

                // Upload Bukti Form
                const uploadSec = document.getElementById('detailUploadSection');
                if (d.status_pembayaran === 'Pending') {
                    uploadSec.classList.remove('hidden');
                    document.getElementById('detailUploadForm').action = '{{ url("/pelanggan/pesanan") }}/' + d.id_pesanan + '/upload-bukti';
                } else {
                    uploadSec.classList.add('hidden');
                }

                // Cetak Nota Button
                document.getElementById('detailNotaBtn').href = '{{ url("/pelanggan/pesanan") }}/' + d.id_pesanan + '/nota';

                document.getElementById('detailLoading').classList.add('hidden');
                document.getElementById('detailContent').classList.remove('hidden');
            }
        } catch (e) {
            document.getElementById('detailLoading').textContent = 'Gagal memuat rincian pesanan.';
        }
    }

    function closeDetailModal() {
        document.getElementById('detailModal').classList.add('hidden');
    }

    // Realtime polling
    let bayarTimer = null;
    function openQrModal(btn) {
        document.getElementById('qrModalImage').src = btn.dataset.qr;
        document.getElementById('qrModalKode').textContent = '#' + btn.dataset.kode;
        document.getElementById('qrModalTotal').textContent = 'Rp ' + Number(btn.dataset.total).toLocaleString('id-ID');
        document.getElementById('qrModal').classList.remove('hidden');
        mulaiCekPembayaran(btn.dataset.kode, 'qrModalStatus');
    }
    function closeQrModal() {
        document.getElementById('qrModal').classList.add('hidden');
        hentikanCekPembayaran();
    }

    function mulaiCekPembayaran(kode, elemId) {
        hentikanCekPembayaran();
        async function cek() {
            try {
                const res = await fetch('{{ url("/pelanggan/pesanan") }}/' + encodeURIComponent(kode) + '/status');
                if (res.ok) {
                    const data = await res.json();
                    if (data.status_pembayaran === 'Lunas') {
                        const el = document.getElementById(elemId);
                        if (el) {
                            el.textContent = '✓ Pembayaran Lunas! Memuat ulang halaman...';
                            el.className = 'text-xs font-bold text-emerald-600';
                        }
                        setTimeout(() => window.location.reload(), 1500);
                        return;
                    }
                }
            } catch (e) {}
            bayarTimer = setTimeout(cek, 5000);
        }
        bayarTimer = setTimeout(cek, 3000);
    }

    function hentikanCekPembayaran() {
        if (bayarTimer) {
            clearTimeout(bayarTimer);
            bayarTimer = null;
        }
    }

    // Modal Ulasan
    function openReviewModal(btn) {
        document.getElementById('reviewForm').action = '{{ url("/pelanggan/ulasan") }}/' + btn.dataset.id;
        document.getElementById('reviewModal').classList.remove('hidden');
    }
    function closeReviewModal() {
        document.getElementById('reviewModal').classList.add('hidden');
    }
    function setRating(n) {
        document.getElementById('reviewRating').value = n;
        document.querySelectorAll('#reviewStars button').forEach(b => {
            const val = parseInt(b.dataset.star);
            b.classList.toggle('text-amber-400', val <= n);
            b.classList.toggle('text-slate-300', val > n);
        });
    }

    @if(session('qr_code_url'))
        mulaiCekPembayaran(@json(session('kode_pesanan')), 'qrSessionStatus');
    @endif
</script>
@endpush

@endsection
