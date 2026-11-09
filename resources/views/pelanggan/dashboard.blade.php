@extends('layouts.pelanggan')
@section('title', 'Dashboard Pelanggan - ArsyClean Laundry')

@section('content')

{{-- ===== POPUP QRIS PEMBAYARAN MANUAL SETELAH BUAT PESANAN ATAU NOTIFIKASI ===== --}}
@if (session('show_qris_modal') || session('qr_code_url'))
    @php
        $qrisKode = session('qris_kode_pesanan') ?? session('kode_pesanan');
        $qrisTotal = session('qris_total_bayar') ?? session('total_bayar') ?? 0;
        $qrisId = session('qris_id_pesanan');
    @endphp
    <div id="notificationModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-950/70 backdrop-blur-sm p-4 overflow-y-auto">
        <div class="w-full max-w-lg rounded-3xl bg-white p-6 sm:p-8 text-center shadow-2xl my-6 border border-slate-200">
            <div class="mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-50 text-blue-700 border border-blue-200">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                </svg>
            </div>
            <h3 class="text-2xl font-black text-slate-900 tracking-tight">
                Pesanan Berhasil Dibuat!
            </h3>
            <p class="text-sm text-slate-600 mt-1 mb-4 leading-relaxed">
                Silakan scan QRIS resmi ArsyClean di bawah ini dengan nominal otomatis yang tertera.
            </p>

            <div class="rounded-2xl border-2 border-slate-200 bg-slate-50 p-5 mb-4 text-center">
                <!-- QRIS Image -->
                <div class="bg-white p-3 rounded-2xl inline-block shadow-md border border-slate-200">
                    <img src="{{ asset('assets/images/qris_arsyclean.jpg') }}"
                         alt="QRIS ArsyClean by_onest"
                         class="mx-auto w-full max-w-[260px] rounded-xl object-contain">
                </div>

                <div class="mt-4 flex items-center justify-center gap-2">
                    <span class="px-3 py-1 bg-blue-100 text-blue-900 font-black rounded-lg text-xs tracking-wider">
                        MERCHANT: by_onest
                    </span>
                    <span class="px-3 py-1 bg-slate-200 text-slate-800 font-mono font-bold rounded-lg text-xs">
                        NMID: ID1025411758325
                    </span>
                </div>

                <div class="mt-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Kode Pesanan Anda:</div>
                <div class="text-lg font-black text-blue-950 font-mono tracking-wider">#{{ $qrisKode }}</div>

                <div class="mt-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Total Tagihan Pembayaran:</div>
                <div class="text-3xl font-black text-slate-900 tracking-tight text-blue-600">
                    Rp {{ number_format($qrisTotal, 0, ',', '.') }}
                </div>
                <p class="text-xs text-slate-500 mt-1 font-medium">Pastikan nominal transfer sesuai dengan total tagihan di atas.</p>
            </div>

            <!-- Upload Bukti Transfer Langsung -->
            @if($qrisId)
                <div class="mb-5 p-4 bg-amber-50/80 border border-amber-200 rounded-2xl text-left">
                    <div class="text-xs font-black text-amber-950 uppercase tracking-wider mb-1 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Unggah Bukti Pembayaran / Struk QRIS:
                    </div>
                    <form action="{{ route('pelanggan.pesanan.upload_bukti', $qrisId) }}" method="POST" enctype="multipart/form-data" class="space-y-2 mt-2">
                        @csrf
                        <input type="file" name="bukti_transfer" accept="image/*" required
                               class="w-full text-xs text-slate-700 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-amber-600 file:text-white hover:file:bg-amber-700 cursor-pointer">
                        <button type="submit"
                                class="w-full py-2.5 bg-amber-600 hover:bg-amber-700 active:scale-95 text-white font-extrabold text-xs rounded-xl shadow-sm transition-all flex items-center justify-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                            Kirim Bukti Pembayaran Sekarang
                        </button>
                    </form>
                </div>
            @endif

            <button type="button"
                    onclick="document.getElementById('notificationModal').remove()"
                    class="w-full py-3.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-sm rounded-2xl shadow-md transition-all">
                Tutup Jendela &amp; Lanjutkan ke Dashboard
            </button>
        </div>
    </div>
@elseif (session('success') || session('error'))
    <div id="notificationAlert" class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-950/70 backdrop-blur-sm p-4">
        <div class="w-full max-w-md rounded-3xl bg-white p-7 text-center shadow-2xl border border-slate-200">
            <div class="mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-2xl {{ session('success') ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' : 'bg-rose-100 text-rose-700 border border-rose-200' }}">
                @if(session('success'))
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                @else
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                @endif
            </div>
            <h3 class="text-xl font-black text-slate-900">
                {{ session('success') ? 'Berhasil!' : 'Terjadi Kesalahan' }}
            </h3>
            <p class="mt-2 mb-6 text-sm text-slate-600 leading-relaxed font-medium">
                {{ session('success') ?? session('error') }}
            </p>
            <button type="button"
                    onclick="document.getElementById('notificationAlert').remove()"
                    class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl shadow-md transition-all">
                Tutup
            </button>
        </div>
    </div>
@endif

<div class="space-y-7">

    <!-- Header Sapaan & Call to Action -->
    <div class="bg-gradient-to-r from-[#0f2a5c] via-[#163a78] to-[#0a1e42] rounded-3xl p-6 sm:p-9 text-white shadow-xl flex flex-col md:flex-row md:items-center justify-between gap-6 border border-blue-900/40">
        <div class="space-y-2">
            <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-white/15 text-sky-200 border border-white/20">
                <svg class="w-4 h-4 text-sky-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                Layanan Laundry Online &amp; Antar Jemput
            </div>
            <h1 class="text-2xl sm:text-3xl font-black tracking-tight">
                Selamat Datang, {{ $user->nama }}!
            </h1>
            <p class="text-sm text-blue-100 max-w-xl leading-relaxed font-medium">
                Pesan layanan pick up laundry dengan mudah. Kurir ArsyClean siap menjemput cucian langsung ke rumah Anda, cuci bersih higienis, dan antar kembali dalam kondisi rapi wangi.
            </p>
        </div>

        <button type="button"
                onclick="openOrderModal()"
                class="shrink-0 bg-white hover:bg-blue-50 active:scale-95 text-blue-950 font-black text-sm px-7 py-4 rounded-2xl shadow-xl transition-all flex items-center justify-center gap-2.5">
            <svg class="w-5 h-5 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
            <span>Pesan Laundry Sekarang</span>
        </button>
    </div>

    <!-- Active Orders Tracking Cards -->
    @if($activePesanans->isNotEmpty())
        <div id="pesanan-aktif" class="scroll-mt-6 space-y-5">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg sm:text-xl font-black text-slate-900 flex items-center gap-2.5">
                        <span class="w-9 h-9 rounded-xl bg-sky-100 text-sky-700 flex items-center justify-center font-black">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        </span>
                        Cucian Sedang Berlangsung
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                        Status realtime proses laundry yang sedang dikerjakan secara teliti oleh tim ArsyClean.
                    </p>
                </div>
                <span class="px-3.5 py-1.5 rounded-full text-xs font-black bg-blue-50 text-blue-800 border border-blue-200">
                    {{ $activePesanans->count() }} Pesanan Aktif
                </span>
            </div>

            @foreach($activePesanans as $ap)
                @php
                    $stages = $ap->daftarTahapLaundry();
                    $currentIndex = array_search($ap->status_laundry, $stages);
                    if ($currentIndex === false) { $currentIndex = 0; }
                    $layananAp = $ap->layanan->first();

                    // Warna status yang berbeda dan kontras sesuai permintaan user
                    $badgeStyle = match($ap->status_laundry) {
                        'Menunggu Antrean' => 'bg-amber-100 text-amber-950 border-2 border-amber-300 font-bold',
                        'Pencucian'        => 'bg-yellow-100 text-yellow-950 border-2 border-yellow-400 font-black',
                        'Pengeringan'      => 'bg-sky-100 text-sky-950 border-2 border-sky-400 font-black',
                        'Setrika'          => 'bg-rose-100 text-rose-950 border-2 border-rose-400 font-black',
                        'Siap Diambil'     => 'bg-purple-100 text-purple-950 border-2 border-purple-400 font-black',
                        'Siap Dikirim'     => 'bg-orange-100 text-orange-950 border-2 border-orange-400 font-black',
                        'Dalam Pengiriman' => 'bg-blue-600 text-white border-2 border-blue-700 font-black',
                        'Selesai'          => 'bg-emerald-600 text-white border-2 border-emerald-700 font-black',
                        default            => 'bg-slate-100 text-slate-900 border-2 border-slate-300 font-bold',
                    };
                @endphp

                <div id="card-pesanan-{{ $ap->kode_pesanan }}" class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200 shadow-sm space-y-5">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100">
                        <div>
                            <div class="flex items-center gap-3">
                                <span class="font-mono text-lg font-black text-blue-950">#{{ $ap->kode_pesanan }}</span>
                                <span class="px-3.5 py-1 rounded-full text-xs shadow-xs {{ $badgeStyle }}">
                                    Tahap: {{ $ap->status_laundry }}
                                </span>
                            </div>
                            @if($layananAp)
                                <p class="text-sm text-slate-600 mt-1 font-medium">
                                    {{ $layananAp->nama_layanan }} ({{ $layananAp->pivot->jumlah }} {{ $layananAp->satuan }}) &bull;
                                    Total: <b class="text-slate-900 font-black">Rp {{ number_format($ap->total_harga, 0, ',', '.') }}</b>
                                    &bull; Metode: <span class="font-bold text-blue-800">{{ $ap->metode_pembayaran }}</span>
                                </p>
                            @endif
                        </div>

                        <div class="flex items-center gap-2">
                            <button type="button"
                                    onclick="bukaDetailPesanan('{{ $ap->id_pesanan }}')"
                                    class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                <span>Detail</span>
                            </button>
                            <a href="{{ route('pelanggan.pesanan.nota', $ap->id_pesanan) }}"
                               target="_blank"
                               class="px-4 py-2 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-bold transition flex items-center gap-1.5 border border-blue-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                <span>Cetak Nota</span>
                            </a>
                        </div>
                    </div>

                    <!-- Visual Timeline Tracker Sesuai Kode Pesanan -->
                    <div class="overflow-x-auto py-2">
                        <div class="flex items-center justify-between min-w-[620px] relative">
                            <!-- Background line -->
                            <div class="absolute top-4 left-6 right-6 h-1 bg-slate-200 -z-0"></div>

                            @foreach($stages as $idx => $stg)
                                @php
                                    $isPassed = $idx <= $currentIndex;
                                    $isCurrent = $idx === $currentIndex;
                                    $stageColor = match($stg) {
                                        'Pencucian'        => 'bg-yellow-400 text-yellow-950',
                                        'Pengeringan'      => 'bg-sky-400 text-white',
                                        'Setrika'          => 'bg-rose-500 text-white',
                                        'Siap Diambil'     => 'bg-purple-600 text-white',
                                        'Siap Dikirim'     => 'bg-orange-500 text-white',
                                        'Dalam Pengiriman' => 'bg-blue-700 text-white',
                                        'Selesai'          => 'bg-emerald-600 text-white',
                                        default            => 'bg-blue-600 text-white',
                                    };
                                @endphp
                                <div class="relative z-10 flex flex-col items-center text-center flex-1">
                                    <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-black transition-all shadow-sm
                                        {{ $isPassed ? ($isCurrent ? $stageColor . ' ring-4 ring-blue-100 scale-110' : 'bg-slate-800 text-white') : 'bg-slate-200 text-slate-500' }}">
                                        {{ $isPassed ? '✓' : ($idx + 1) }}
                                    </div>
                                    <span class="text-xs mt-2 font-bold max-w-[85px] leading-tight
                                        {{ $isCurrent ? 'text-blue-900 font-black' : ($isPassed ? 'text-slate-800' : 'text-slate-400') }}">
                                        {{ $stg }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    @if($ap->pengiriman)
                        <div class="p-4 bg-emerald-50/90 border border-emerald-200 rounded-2xl text-xs text-emerald-950 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path></svg>
                                <span>Kurir Antar-Jemput: <b>{{ $ap->pengiriman->nama_kurir }}</b> ({{ $ap->pengiriman->no_hp_kurir }})</span>
                            </span>
                            <span class="font-bold bg-emerald-100 px-3 py-1 rounded-full text-emerald-900">
                                Status: {{ $ap->pengiriman->status_pengiriman }} &bull; Resi: {{ $ap->pengiriman->no_resi }}
                            </span>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    <!-- Banner Katalog Layanan Pick Up -->
    <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-5">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-700 border border-blue-200 flex items-center justify-center shrink-0">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </div>
            <div>
                <h3 class="text-lg sm:text-xl font-black text-slate-900">
                    Layanan Pick Up &amp; Antar-Jemput Cucian
                </h3>
                <p class="text-sm text-slate-600 mt-1 font-medium">
                    Kurir kami siap menjemput cucian langsung ke rumah Anda sesuai jadwal yang ditentukan.
                </p>
            </div>
        </div>

        <button type="button"
                onclick="openOrderModal()"
                class="shrink-0 px-6 py-3.5 bg-blue-600 hover:bg-blue-700 active:scale-95 text-white font-black text-sm rounded-2xl shadow-md transition-all flex items-center gap-2">
            <span>Buat Pesanan Baru</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
        </button>
    </div>

    <!-- Riwayat Pesanan Saya -->
    <div id="riwayat" class="scroll-mt-6 bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100">
            <div>
                <h3 class="text-lg sm:text-xl font-black text-slate-900 flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-xl bg-blue-100 text-blue-800 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    </span>
                    Riwayat Pesanan Saya
                </h3>
                <p class="text-sm text-slate-500 mt-1">
                    Daftar seluruh pesanan Anda. Klik tombol detail untuk rincian lengkap, scan QRIS, atau unduh nota pembayaran.
                </p>
            </div>
            <span class="px-4 py-1.5 rounded-full text-xs font-black bg-slate-100 text-slate-700 self-start sm:self-auto border border-slate-200">
                Total: {{ $pesanans->count() }} Pesanan
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-600 uppercase text-xs border-b border-slate-200 font-black">
                    <tr>
                        <th class="p-4">Kode</th>
                        <th class="p-4">Tanggal</th>
                        <th class="p-4">Layanan</th>
                        <th class="p-4">Jumlah</th>
                        <th class="p-4">Total (Rp)</th>
                        <th class="p-4">Status Pengerjaan</th>
                        <th class="p-4">Pembayaran</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($pesanans as $p)
                        @php
                            $statusBayar = $p->pembayaran?->status_pembayaran ?? 'Pending';
                            $layananItem = $p->layanan->first();

                            $badgePengerjaan = match($p->status_laundry) {
                                'Menunggu Antrean' => 'bg-amber-100 text-amber-950 border border-amber-300 font-bold',
                                'Pencucian'        => 'bg-yellow-100 text-yellow-950 border border-yellow-400 font-black',
                                'Pengeringan'      => 'bg-sky-100 text-sky-950 border border-sky-400 font-black',
                                'Setrika'          => 'bg-rose-100 text-rose-950 border border-rose-400 font-black',
                                'Siap Diambil'     => 'bg-purple-100 text-purple-950 border border-purple-400 font-black',
                                'Siap Dikirim'     => 'bg-orange-100 text-orange-950 border border-orange-400 font-black',
                                'Dalam Pengiriman' => 'bg-blue-600 text-white border border-blue-700 font-black',
                                'Selesai'          => 'bg-emerald-600 text-white border border-emerald-700 font-black',
                                default            => 'bg-slate-100 text-slate-800 border border-slate-300 font-bold',
                            };
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="p-4 font-mono font-black text-blue-950">
                                #{{ $p->kode_pesanan }}
                            </td>
                            <td class="p-4 text-xs text-slate-600 font-semibold">
                                {{ $p->tanggal_pesanan ? $p->tanggal_pesanan->format('d M Y') : '-' }}
                            </td>
                            <td class="p-4 font-bold text-slate-900">
                                {{ $layananItem->nama_layanan ?? 'Layanan' }}
                            </td>
                            <td class="p-4 text-xs text-slate-600 font-semibold">
                                @if($layananItem)
                                    {{ $layananItem->pivot->jumlah }} {{ $layananItem->satuan }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="p-4 font-black text-slate-900 text-sm">
                                Rp {{ number_format($p->total_harga, 0, ',', '.') }}
                            </td>
                            <td class="p-4">
                                <span class="px-3 py-1 rounded-full text-xs {{ $badgePengerjaan }}">
                                    {{ $p->status_laundry ?? $p->status_pesanan }}
                                </span>
                            </td>
                            <td class="p-4">
                                <span class="px-3 py-1 rounded-full text-xs font-black
                                    {{ $statusBayar === 'Lunas' ? 'bg-emerald-100 text-emerald-900 border border-emerald-300' : ($statusBayar === 'Pending' ? 'bg-amber-100 text-amber-900 border border-amber-300' : 'bg-rose-100 text-rose-900 border border-rose-300') }}">
                                    {{ $statusBayar }}
                                </span>
                            </td>
                            <td class="p-4 text-center">
                                <div class="flex items-center justify-center gap-1.5 flex-wrap">
                                    <button type="button"
                                            onclick="bukaDetailPesanan('{{ $p->id_pesanan }}')"
                                            class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition">
                                        Detail
                                    </button>

                                    <a href="{{ route('pelanggan.pesanan.nota', $p->id_pesanan) }}"
                                       target="_blank"
                                       class="px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 rounded-xl text-xs font-bold transition">
                                        Nota PDF
                                    </a>

                                    @if($p->metode_pembayaran === 'QRIS' && $statusBayar === 'Pending')
                                        <button type="button"
                                                data-kode="{{ $p->kode_pesanan }}"
                                                data-total="{{ (int) round($p->total_harga) }}"
                                                data-id="{{ $p->id_pesanan }}"
                                                onclick="openQrModalManual(this)"
                                                class="px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-black shadow-xs transition">
                                            Scan QRIS
                                        </button>
                                    @elseif($p->ulasan)
                                        <span class="text-amber-500 font-black text-xs" title="Rating: {{ $p->ulasan->rating }} Bintang">
                                            {{ str_repeat('★', (int) $p->ulasan->rating) }}
                                        </span>
                                    @elseif($p->status_pesanan === 'Selesai')
                                        <button type="button"
                                                data-id="{{ $p->id_pesanan }}"
                                                data-kode="{{ $p->kode_pesanan }}"
                                                onclick="openReviewModal(this)"
                                                class="px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-bold transition">
                                            Ulasan
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-10 text-center text-slate-400 font-semibold">
                                Belum ada riwayat pesanan laundry. Silakan klik tombol "Pesan Laundry Sekarang" di atas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- ================= MODAL PEMESANAN PICK UP LAUNDRY (DENGAN INPUT JENIS PAKAIAN & AKUMULASI) ================= --}}
<div id="orderModal" class="hidden fixed inset-0 z-50 bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-2xl w-full p-6 sm:p-8 shadow-2xl max-h-[92vh] overflow-y-auto space-y-5 border border-slate-200">
        <div class="flex justify-between items-start pb-4 border-b border-slate-100">
            <div>
                <h3 class="text-2xl font-black text-slate-900 tracking-tight">Pemesanan Pick Up Laundry</h3>
                <p class="text-sm text-slate-500 mt-1">Lengkapi data pakaian, alamat penjemputan, dan jadwal kurir.</p>
            </div>
            <button type="button" onclick="closeOrderModal()" class="w-9 h-9 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center font-black text-base">✕</button>
        </div>

        <form action="{{ route('pelanggan.pesanan.store') }}" method="POST" id="orderForm" class="space-y-5 text-sm">
            @csrf

            <!-- 1. Pilihan Layanan (1 Layanan = 1 Kode Pesanan) -->
            <div>
                <label class="block font-bold text-slate-800 text-sm mb-2">
                    Pilihan Paket Layanan <span class="text-red-500 font-bold">*</span>
                </label>
                <select name="id_layanan"
                        id="serviceSelect"
                        required
                        onchange="changeService()"
                        class="w-full border border-slate-200 rounded-2xl px-4 py-3.5 bg-slate-50 text-sm font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">-- Pilih Jenis Layanan Laundry --</option>
                    @foreach($layanans as $layanan)
                        @if(strtolower($layanan->jenis_layanan) !== 'koin')
                            <option value="{{ $layanan->id_layanan }}"
                                    data-satuan="{{ strtolower($layanan->satuan) }}"
                                    data-harga="{{ $layanan->harga }}"
                                    data-nama="{{ $layanan->nama_layanan }}"
                                    data-jenis="{{ $layanan->jenis_layanan }}">
                                {{ $layanan->nama_layanan }} &bull; Rp {{ number_format($layanan->harga, 0, ',', '.') }}/{{ $layanan->satuan }} ({{ $layanan->jenis_layanan }})
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>

            <!-- 2. Rincian Pakaian & Akumulasi Otomatis -->
            <div id="clothingEstimator" class="space-y-3 p-5 bg-slate-50 border border-slate-200 rounded-3xl">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-2 border-b border-slate-200">
                    <div>
                        <span class="font-black text-sm text-slate-900 uppercase tracking-wider block">
                            Rincian Pakaian Sesuai Jenis
                        </span>
                        <span class="text-xs text-slate-500 font-medium">Masukkan jumlah potong tiap pakaian untuk akumulasi otomatis.</span>
                    </div>
                    <div class="bg-blue-600 text-white px-3.5 py-1.5 rounded-xl text-xs font-black shrink-0 shadow-sm" id="badgeTotalBaju">
                        Total Pakaian: 0 Potong
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 pt-2 max-h-56 overflow-y-auto pr-1">
                    @php
                        $itemsPakaian = [
                            'kaos'           => ['label' => 'Kaos / T-Shirt', 'gram' => 120],
                            'kemeja'         => ['label' => 'Kemeja', 'gram' => 150],
                            'celana_panjang' => ['label' => 'Celana Panjang/Jeans', 'gram' => 300],
                            'celana_pendek'  => ['label' => 'Celana Pendek', 'gram' => 150],
                            'jaket'          => ['label' => 'Jaket / Hoodie', 'gram' => 450],
                            'pakaian_dalam'  => ['label' => 'Pakaian Dalam / Kaos Kaki', 'gram' => 50],
                            'baju_muslim'    => ['label' => 'Gamis / Baju Muslim', 'gram' => 300],
                            'sprei'          => ['label' => 'Sprei / Sarung Bantal', 'gram' => 400],
                            'lainnya'        => ['label' => 'Pakaian Lainnya', 'gram' => 150],
                        ];
                    @endphp

                    @foreach($itemsPakaian as $key => $prop)
                        <div class="bg-white p-3 rounded-2xl border border-slate-200 shadow-xs">
                            <label class="block text-xs font-bold text-slate-800 truncate">{{ $prop['label'] }}</label>
                            <input type="number"
                                   min="0"
                                   name="pakaian[{{ $key }}]"
                                   data-gram="{{ $prop['gram'] }}"
                                   placeholder="0"
                                   class="clothing-input w-full mt-1.5 px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm font-black text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   oninput="hitungAkumulasiPakaian()">
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Input Manual Jumlah / Satuan Jika Non-Kiloan -->
            <div id="unitSection" class="hidden space-y-2 p-5 bg-slate-50 border border-slate-200 rounded-3xl">
                <label class="block font-bold text-sm text-slate-800">
                    Jumlah Barang (<span id="labelSatuanUnit">pcs</span>) <span class="text-red-500 font-bold">*</span>
                </label>
                <input type="number"
                       id="satuanQuantity"
                       min="1"
                       value="1"
                       class="w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-sm font-black text-slate-900 focus:ring-2 focus:ring-blue-500 outline-none"
                       oninput="hitungTotalBiayaAkhir()">
            </div>

            <input type="hidden" name="jumlah" id="jumlahInput" value="1">

            <!-- 3. Jadwal & Waktu Penjemputan -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-sm text-slate-800 mb-1.5">
                        Tanggal Penjemputan <span class="text-red-500 font-bold">*</span>
                    </label>
                    <input type="date"
                           name="tanggal"
                           min="{{ date('Y-m-d') }}"
                           value="{{ date('Y-m-d') }}"
                           required
                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block font-bold text-sm text-slate-800 mb-1.5">
                        Jam Penjemputan <span class="text-red-500 font-bold">*</span>
                    </label>
                    <input type="time"
                           name="waktu"
                           value="09:00"
                           required
                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
            </div>

            <!-- 4. Alamat Penjemputan -->
            <div>
                <label class="block font-bold text-sm text-slate-800 mb-1.5">
                    Alamat Lengkap Penjemputan <span class="text-red-500 font-bold">*</span>
                </label>
                <textarea name="alamat"
                          rows="2"
                          required
                          placeholder="Nama jalan, nomor rumah, RT/RW, patokan lokasi..."
                          class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-medium text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none">{{ $user->alamat }}</textarea>
            </div>

            <!-- 5. Catatan Khusus Pelanggan -->
            <div>
                <label class="block font-bold text-sm text-slate-800 mb-1.5">
                    Catatan Khusus untuk Petugas / Kurir (Opsional)
                </label>
                <textarea name="catatan"
                          rows="2"
                          placeholder="Contoh: Pisahkan pakaian putih/luntur, jemput di pos satpam..."
                          class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-medium text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none"></textarea>
            </div>

            <!-- 6. Metode Pembayaran: QRIS Manual by_onest & Tunai (Cash) -->
            <div>
                <label class="block font-bold text-sm text-slate-800 mb-2">
                    Metode Pembayaran <span class="text-red-500 font-bold">*</span>
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <label class="flex items-center gap-3 p-4 bg-slate-50 border-2 border-slate-200 rounded-2xl cursor-pointer hover:border-blue-500 transition-colors">
                        <input type="radio" name="metode_pembayaran" value="QRIS" checked class="w-4 h-4 text-blue-600">
                        <div>
                            <span class="font-black text-slate-900 block text-sm">QRIS Manual (by_onest)</span>
                            <span class="text-xs text-slate-500 font-medium">BCA, Mandiri, GoPay, OVO, Dana</span>
                        </div>
                    </label>
                    <label class="flex items-center gap-3 p-4 bg-slate-50 border-2 border-slate-200 rounded-2xl cursor-pointer hover:border-blue-500 transition-colors">
                        <input type="radio" name="metode_pembayaran" value="TUNAI" class="w-4 h-4 text-blue-600">
                        <div>
                            <span class="font-black text-slate-900 block text-sm">Tunai (Bayar di Tempat)</span>
                            <span class="text-xs text-slate-500 font-medium">Bayar uang tunai saat kurir datang</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Ringkasan Estimasi Biaya -->
            <div id="priceSummary" class="p-5 bg-blue-50 border border-blue-200 rounded-2xl flex items-center justify-between">
                <div>
                    <span class="text-xs text-blue-800 font-black uppercase tracking-wider block" id="summaryService">Pilihan Layanan</span>
                    <span class="text-sm text-slate-600 font-bold mt-0.5 block" id="summaryDetails">0 kg &bull; Rp 0</span>
                </div>
                <div class="text-right">
                    <span class="text-xs text-slate-500 block font-bold uppercase">Estimasi Tagihan:</span>
                    <span class="text-2xl font-black text-blue-950" id="summaryTotal">Rp 0</span>
                </div>
            </div>

            <div class="pt-2 flex justify-end gap-3">
                <button type="button" onclick="closeOrderModal()" class="px-5 py-3 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-sm">
                    Batal
                </button>
                <button type="submit" class="px-7 py-3.5 rounded-2xl bg-blue-600 hover:bg-blue-700 active:scale-95 text-white font-black text-sm shadow-md shadow-blue-600/30 transition-all">
                    Konfirmasi &amp; Lanjutkan &rarr;
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ================= MODAL DETAIL PESANAN INTERAKTIF ================= --}}
<div id="detailModal" class="hidden fixed inset-0 z-50 bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-7 shadow-2xl max-h-[90vh] overflow-y-auto space-y-4 border border-slate-200">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div>
                <h3 class="text-xl font-black text-slate-900">Detail Pesanan #<span id="detailKode"></span></h3>
                <span class="text-xs text-slate-500 font-semibold" id="detailTanggal">-</span>
            </div>
            <button type="button" onclick="closeDetailModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 font-black text-sm">✕</button>
        </div>

        <div id="detailLoading" class="py-10 text-center text-slate-400 font-bold">Memuat rincian pesanan...</div>

        <div id="detailContent" class="hidden space-y-4 text-xs">
            <!-- Status Badge & Tracker -->
            <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl flex items-center justify-between">
                <div>
                    <span class="text-[11px] text-slate-500 font-bold uppercase block">Tahap Pengerjaan</span>
                    <span class="text-sm font-black text-blue-950" id="detailStatusLaundry">-</span>
                </div>
                <div class="text-right">
                    <span class="text-[11px] text-slate-500 font-bold uppercase block">Pembayaran</span>
                    <span class="px-3 py-1 rounded-full text-xs font-black inline-block mt-0.5" id="detailStatusBayar">-</span>
                </div>
            </div>

            <!-- Rincian Layanan -->
            <div>
                <h4 class="font-black text-slate-800 uppercase tracking-wider text-xs mb-2">Rincian Paket &amp; Berat:</h4>
                <div id="detailLayananList" class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200 space-y-1.5 font-semibold text-slate-800"></div>
            </div>

            <!-- Catatan Pelanggan -->
            <div>
                <h4 class="font-black text-slate-800 uppercase tracking-wider text-xs mb-1.5">Catatan Pesanan:</h4>
                <p id="detailCatatan" class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200 text-slate-700 whitespace-pre-line leading-relaxed">-</p>
            </div>

            <!-- Alamat Penjemputan -->
            <div>
                <h4 class="font-black text-slate-800 uppercase tracking-wider text-xs mb-1.5">Alamat Penjemputan:</h4>
                <p id="detailAlamat" class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200 text-slate-700 font-medium leading-relaxed">-</p>
            </div>

            <!-- Info Kurir -->
            <div id="detailKurirBox" class="hidden p-3.5 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-950 font-medium">
                <div class="font-black text-xs text-emerald-900 mb-0.5">Penugasan Kurir:</div>
                <div id="detailKurirInfo" class="text-xs"></div>
            </div>

            <!-- Form Upload Bukti Transfer Manual (Jika Pending) -->
            <div id="detailUploadSection" class="hidden p-4 bg-amber-50 border border-amber-200 rounded-2xl space-y-2">
                <div class="font-black text-amber-950 text-xs">Unggah Bukti Transfer QRIS / Bank:</div>
                <p class="text-[11px] text-amber-800 font-medium">Foto bukti transfer/struk akan diverifikasi oleh petugas admin kasir.</p>
                <form id="detailUploadForm" method="POST" enctype="multipart/form-data" class="space-y-2 pt-1">
                    @csrf
                    <input type="file" name="bukti_transfer" accept="image/*" required class="w-full text-xs file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-amber-600 file:text-white cursor-pointer">
                    <button type="submit" class="w-full py-2.5 bg-amber-600 hover:bg-amber-700 active:scale-95 text-white font-extrabold text-xs rounded-xl shadow-xs transition-all">
                        Kirim Bukti Pembayaran
                    </button>
                </form>
            </div>

            <!-- Total & Action Buttons -->
            <div class="pt-3 flex items-center justify-between border-t border-slate-100">
                <div>
                    <span class="text-[11px] text-slate-500 block font-bold uppercase">Total Tagihan</span>
                    <span class="text-2xl font-black text-slate-900" id="detailTotal">Rp 0</span>
                </div>
                <div class="flex items-center gap-2">
                    <a id="detailNotaBtn" href="#" target="_blank" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-black transition flex items-center gap-1.5 shadow-sm">
                        <span>Cetak Nota PDF</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ================= MODAL SCAN QRIS MANUAL ARSYCLEAN ================= --}}
<div id="qrModalManual" class="hidden fixed inset-0 z-50 bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 text-center shadow-2xl space-y-4 my-6 border border-slate-200">
        <div class="flex justify-between items-center pb-2 border-b border-slate-100">
            <h3 class="text-lg font-black text-slate-900">Scan QRIS ArsyClean</h3>
            <button type="button" onclick="closeQrModalManual()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 font-black text-sm">✕</button>
        </div>

        <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl">
            <img src="{{ asset('assets/images/qris_arsyclean.jpg') }}" alt="QRIS by_onest" class="mx-auto w-full max-w-[240px] rounded-xl shadow-md border border-slate-200">
            <div class="mt-3 flex items-center justify-center gap-2">
                <span class="px-2.5 py-0.5 bg-blue-100 text-blue-900 font-bold rounded text-[11px]">by_onest</span>
                <span class="px-2.5 py-0.5 bg-slate-200 text-slate-800 font-mono text-[11px]">NMID: ID1025411758325</span>
            </div>
            <div class="mt-3 text-xs text-slate-500 font-bold uppercase">Kode Pesanan:</div>
            <div id="qrManualModalKode" class="font-mono font-black text-blue-950 text-base"></div>
            <div class="mt-2 text-xs text-slate-500 font-bold uppercase">Nominal Tagihan:</div>
            <div id="qrManualModalTotal" class="font-black text-blue-600 text-2xl"></div>
        </div>

        <!-- Form upload bukti -->
        <div class="p-3.5 bg-amber-50 border border-amber-200 rounded-2xl text-left">
            <div class="text-xs font-black text-amber-950 mb-1">Unggah Foto Bukti Transfer:</div>
            <form id="qrManualUploadForm" method="POST" enctype="multipart/form-data" class="space-y-2">
                @csrf
                <input type="file" name="bukti_transfer" accept="image/*" required class="w-full text-xs text-slate-700 file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-amber-600 file:text-white cursor-pointer">
                <button type="submit" class="w-full py-2 bg-amber-600 hover:bg-amber-700 text-white font-extrabold text-xs rounded-xl shadow-xs">
                    Kirim Bukti Pembayaran
                </button>
            </form>
        </div>

        <button type="button" onclick="closeQrModalManual()" class="w-full py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-black">
            Tutup
        </button>
    </div>
</div>

{{-- ================= MODAL ULASAN BINTANG ================= --}}
<div id="reviewModal" class="hidden fixed inset-0 z-50 bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-sm w-full p-6 text-center shadow-2xl space-y-4 border border-slate-200">
        <h3 class="text-xl font-black text-slate-900">Beri Ulasan Layanan</h3>
        <p class="text-xs text-slate-500 font-medium">Bagikan pengalaman Anda menggunakan jasa laundry ArsyClean.</p>

        <form id="reviewForm" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="rating" id="reviewRating" value="5" required>

            <div class="flex justify-center gap-2 text-3xl" id="reviewStars">
                @for($i = 1; $i <= 5; $i++)
                    <button type="button" data-star="{{ $i }}" onclick="setRating({{ $i }})" class="text-amber-400 hover:scale-110 transition-transform">
                        ★
                    </button>
                @endfor
            </div>

            <textarea name="komentar" rows="3" placeholder="Tuliskan ulasan atau kepuasan Anda (bersih, wangi, kurir tepat waktu)..." required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500"></textarea>

            <div class="flex justify-end gap-2 pt-1">
                <button type="button" onclick="closeReviewModal()" class="px-4 py-2.5 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold">Batal</button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-xs font-black shadow-md">Kirim Ulasan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openOrderModal() {
        document.getElementById('orderModal').classList.remove('hidden');
    }
    function closeOrderModal() {
        document.getElementById('orderModal').classList.add('hidden');
    }

    function changeService() {
        const select = document.getElementById('serviceSelect');
        const selected = select.options[select.selectedIndex];
        const satuan = selected?.dataset?.satuan || 'kg';

        const clothingSec = document.getElementById('clothingEstimator');
        const unitSec = document.getElementById('unitSection');

        if (satuan === 'kg') {
            clothingSec.classList.remove('hidden');
            unitSec.classList.add('hidden');
        } else {
            clothingSec.classList.add('hidden');
            unitSec.classList.remove('hidden');
            document.getElementById('labelSatuanUnit').innerText = satuan;
        }
        hitungAkumulasiPakaian();
    }

    function hitungAkumulasiPakaian() {
        const select = document.getElementById('serviceSelect');
        const selected = select.options[select.selectedIndex];
        if (!selected || !selected.value) {
            document.getElementById('priceSummary').classList.add('hidden');
            return;
        }

        const satuan = selected.dataset.satuan || 'kg';
        const harga = parseFloat(selected.dataset.harga) || 0;
        const nama = selected.dataset.nama || '';

        let totalPotong = 0;
        let totalGram = 0;

        document.querySelectorAll('.clothing-input').forEach(inp => {
            const qty = parseInt(inp.value) || 0;
            const gram = parseInt(inp.dataset.gram) || 150;
            if (qty > 0) {
                totalPotong += qty;
                totalGram += (qty * gram);
            }
        });

        // Update badge total potong
        document.getElementById('badgeTotalBaju').innerText = 'Total Pakaian: ' + totalPotong + ' Potong';

        let jumlahUnit = 0;
        let totalBiaya = 0;

        if (satuan === 'kg') {
            if (totalPotong > 0) {
                // Minimum 1 kg, bulatkan ke 1 desimal
                jumlahUnit = Math.max(1.0, Math.round((totalGram / 1000) * 10) / 10);
            } else {
                jumlahUnit = 1.0;
            }
            totalBiaya = Math.round(jumlahUnit * harga);
            document.getElementById('summaryDetails').innerText = jumlahUnit.toFixed(1) + ' kg (' + totalPotong + ' potong baju) • Rp ' + Math.round(harga).toLocaleString('id-ID') + '/kg';
        } else {
            const qtySatuan = parseInt(document.getElementById('satuanQuantity').value) || 1;
            jumlahUnit = qtySatuan;
            totalBiaya = Math.round(qtySatuan * harga);
            document.getElementById('summaryDetails').innerText = qtySatuan + ' ' + satuan + ' • Rp ' + Math.round(harga).toLocaleString('id-ID') + '/' + satuan;
        }

        document.getElementById('jumlahInput').value = jumlahUnit;
        document.getElementById('summaryService').innerText = nama;
        document.getElementById('summaryTotal').innerText = 'Rp ' + totalBiaya.toLocaleString('id-ID');
        document.getElementById('priceSummary').classList.remove('hidden');
    }

    function hitungTotalBiayaAkhir() {
        hitungAkumulasiPakaian();
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
                const d = resData.pesanan || resData.data;
                document.getElementById('detailKode').textContent = d.kode_pesanan;
                document.getElementById('detailTanggal').textContent = d.tanggal_pesanan;
                document.getElementById('detailStatusLaundry').textContent = d.status_laundry;

                const stBayar = d.status_pembayaran || (d.pembayaran ? d.pembayaran.status_pembayaran : 'Pending');
                const metBayar = d.metode_pembayaran || 'QRIS';
                document.getElementById('detailStatusBayar').textContent = stBayar + ' (' + metBayar + ')';
                document.getElementById('detailStatusBayar').className = 'px-3 py-1 rounded-full text-xs font-black ' +
                    (stBayar === 'Lunas' ? 'bg-emerald-100 text-emerald-900 border border-emerald-300' : 'bg-amber-100 text-amber-900 border border-amber-300');

                document.getElementById('detailCatatan').textContent = d.catatan || 'Tidak ada catatan tambahan.';
                document.getElementById('detailAlamat').textContent = d.alamat_penjemputan;
                document.getElementById('detailTotal').textContent = d.total_harga_format || ('Rp ' + Number(d.total_harga).toLocaleString('id-ID'));

                // Render Layanan List
                const list = document.getElementById('detailLayananList');
                list.innerHTML = '';
                if (d.layanan && d.layanan.length > 0) {
                    d.layanan.forEach(item => {
                        const row = document.createElement('div');
                        row.className = 'flex justify-between items-center py-1 border-b border-slate-200/60 last:border-0';
                        row.innerHTML = '<span><b>' + item.nama_layanan + '</b> (' + item.jumlah + ' ' + item.satuan + ')</span><span class="font-black text-slate-900">Rp ' + Number(item.subtotal || item.harga * item.jumlah).toLocaleString('id-ID') + '</span>';
                        list.appendChild(row);
                    });
                }

                // Kurir
                const kurirBox = document.getElementById('detailKurirBox');
                if (d.pengiriman) {
                    kurirBox.classList.remove('hidden');
                    document.getElementById('detailKurirInfo').innerHTML = 'Kurir: <b>' + d.pengiriman.nama_kurir + '</b> (' + d.pengiriman.no_hp_kurir + ') • Resi: <b>' + d.pengiriman.no_resi + '</b>';
                } else {
                    kurirBox.classList.add('hidden');
                }

                // Upload Bukti Form
                const uploadSec = document.getElementById('detailUploadSection');
                if (stBayar === 'Pending') {
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

    // Modal QRIS Manual
    function openQrModalManual(btn) {
        document.getElementById('qrManualModalKode').textContent = '#' + btn.dataset.kode;
        document.getElementById('qrManualModalTotal').textContent = 'Rp ' + Number(btn.dataset.total).toLocaleString('id-ID');
        document.getElementById('qrManualUploadForm').action = '{{ url("/pelanggan/pesanan") }}/' + btn.dataset.id + '/upload-bukti';
        document.getElementById('qrModalManual').classList.remove('hidden');
    }

    function closeQrModalManual() {
        document.getElementById('qrModalManual').classList.add('hidden');
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
</script>
@endpush

@endsection
