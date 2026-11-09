@extends('layouts.pelanggan')

@section('title', 'Riwayat Pesanan Laundry - ArsyClean')

@section('content')
<div class="space-y-7">

    <!-- Header Riwayat -->
    <div class="bg-gradient-to-r from-[#0f2a5c] via-[#163a78] to-[#0a1e42] rounded-3xl p-6 sm:p-9 text-white shadow-xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5 border border-blue-900/40">
        <div>
            <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-white/15 text-sky-200 mb-2 border border-white/20">
                <svg class="w-4 h-4 text-sky-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                Arsip &amp; Riwayat Pengerjaan
            </div>
            <h1 class="text-2xl sm:text-3xl font-black tracking-tight">Riwayat Pesanan Saya</h1>
            <p class="text-xs sm:text-sm text-blue-100 mt-1 max-w-xl font-medium">
                Pantau seluruh status pesanan laundry Anda secara realtime, unduh nota pembayaran PDF resmi, atau unggah bukti transfer QRIS.
            </p>
        </div>

        <a href="{{ route('pelanggan.layanan') }}"
           class="px-6 py-3.5 rounded-2xl bg-white hover:bg-blue-50 active:scale-95 text-blue-950 font-black text-xs shadow-lg transition-all flex items-center gap-2 shrink-0">
            <svg class="w-4 h-4 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
            <span>Pesan Laundry Baru</span>
        </a>
    </div>

    <!-- Filter Tab Status -->
    <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none">
        @php
            $currentStatus = request('status', '');
        @endphp
        <a href="{{ route('pelanggan.riwayat') }}"
           class="px-4 py-2.5 rounded-xl text-xs font-black shrink-0 transition-all {{ $currentStatus === '' ? 'bg-slate-900 text-white shadow-xs' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200' }}">
            Semua ({{ $pesanans->count() }})
        </a>
        <a href="{{ route('pelanggan.riwayat', ['status' => 'Menunggu Pembayaran']) }}"
           class="px-4 py-2.5 rounded-xl text-xs font-black shrink-0 transition-all {{ $currentStatus === 'Menunggu Pembayaran' ? 'bg-amber-600 text-white shadow-xs' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200' }}">
            Menunggu Pembayaran
        </a>
        <a href="{{ route('pelanggan.riwayat', ['status' => 'Diproses']) }}"
           class="px-4 py-2.5 rounded-xl text-xs font-black shrink-0 transition-all {{ $currentStatus === 'Diproses' ? 'bg-blue-700 text-white shadow-xs' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200' }}">
            Sedang Diproses
        </a>
        <a href="{{ route('pelanggan.riwayat', ['status' => 'Selesai']) }}"
           class="px-4 py-2.5 rounded-xl text-xs font-black shrink-0 transition-all {{ $currentStatus === 'Selesai' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200' }}">
            Selesai
        </a>
        <a href="{{ route('pelanggan.riwayat', ['status' => 'Dibatalkan']) }}"
           class="px-4 py-2.5 rounded-xl text-xs font-black shrink-0 transition-all {{ $currentStatus === 'Dibatalkan' ? 'bg-rose-600 text-white shadow-xs' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200' }}">
            Dibatalkan
        </a>
    </div>

    <!-- Tabel Daftar Pesanan -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-xs font-black uppercase text-slate-600 tracking-wider">
                        <th class="py-4 px-5">Kode Pesanan</th>
                        <th class="py-4 px-5">Layanan</th>
                        <th class="py-4 px-5">Tanggal</th>
                        <th class="py-4 px-5">Total Tagihan</th>
                        <th class="py-4 px-5">Status Pengerjaan</th>
                        <th class="py-4 px-5">Pembayaran</th>
                        <th class="py-4 px-5 text-center">Aksi &amp; Dokumen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-medium">
                    @forelse($pesanans as $p)
                        @php
                            $stLaundry = $p->status_laundry ?? 'Menunggu Antrean';
                            $badgeLaundry = match ($stLaundry) {
                                'Menunggu Antrean' => 'bg-amber-100 text-amber-950 border-2 border-amber-300 font-bold',
                                'Pencucian'        => 'bg-yellow-100 text-yellow-950 border-2 border-yellow-400 font-black',
                                'Pengeringan'      => 'bg-sky-100 text-sky-950 border-2 border-sky-400 font-black',
                                'Setrika'          => 'bg-rose-100 text-rose-950 border-2 border-rose-400 font-black',
                                'Siap Diambil'     => 'bg-purple-100 text-purple-950 border-2 border-purple-400 font-black',
                                'Siap Dikirim'     => 'bg-orange-100 text-orange-950 border-2 border-orange-400 font-black',
                                'Dalam Pengiriman' => 'bg-blue-600 text-white border-2 border-blue-700 font-black',
                                'Selesai'          => 'bg-emerald-600 text-white border-2 border-emerald-700 font-black',
                                default            => 'bg-slate-100 text-slate-800 border-2 border-slate-300 font-bold',
                            };

                            $isBayarLunas = ($p->pembayaran?->status_pembayaran ?? 'Pending') === 'Lunas';
                        @endphp
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-4 px-5 font-mono font-black text-blue-950 text-sm">
                                #{{ $p->kode_pesanan }}
                            </td>
                            <td class="py-4 px-5 font-bold text-slate-900">
                                <div>{{ $p->layanan->first()->nama_layanan ?? '-' }}</div>
                                @if($p->layanan->first())
                                    <div class="text-[11px] font-normal text-slate-500">
                                        {{ $p->layanan->first()->pivot->jumlah }} {{ $p->layanan->first()->satuan }}
                                    </div>
                                @endif
                            </td>
                            <td class="py-4 px-5 text-slate-600 font-semibold">
                                {{ $p->tanggal_pesanan ? $p->tanggal_pesanan->format('d M Y') : '-' }}
                            </td>
                            <td class="py-4 px-5 font-black text-slate-900 text-sm">
                                Rp {{ number_format($p->total_harga, 0, ',', '.') }}
                            </td>
                            <td class="py-4 px-5">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs {{ $badgeLaundry }}">
                                    <span>{{ $stLaundry }}</span>
                                </span>
                            </td>
                            <td class="py-4 px-5">
                                <span class="px-3 py-1 rounded-full text-xs font-black {{ $isBayarLunas ? 'bg-emerald-100 text-emerald-900 border border-emerald-300' : 'bg-amber-100 text-amber-900 border border-amber-300' }}">
                                    {{ $p->pembayaran?->status_pembayaran ?? 'Pending' }}
                                </span>
                            </td>
                            <td class="py-4 px-5 text-center">
                                <div class="flex items-center justify-center gap-1.5 flex-wrap">
                                    <!-- Tombol Detail Modal -->
                                    <button type="button"
                                            onclick="openDetailModal('{{ $p->id_pesanan }}')"
                                            class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-all">
                                        Detail
                                    </button>

                                    <!-- Cetak Nota PDF Kapan Saja -->
                                    <a href="{{ route('pelanggan.pesanan.nota', $p->id_pesanan) }}"
                                       target="_blank"
                                       class="px-3 py-1.5 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 font-bold text-xs transition-all flex items-center gap-1">
                                        <span>Nota PDF</span>
                                    </a>

                                    <!-- Tombol QRIS atau Upload Bukti Bayar jika Pending -->
                                    @if(!$isBayarLunas && $p->status_pesanan !== 'Dibatalkan')
                                        @if($p->metode_pembayaran === 'QRIS')
                                            <button type="button"
                                                    data-kode="{{ $p->kode_pesanan }}"
                                                    data-total="{{ (int) round($p->total_harga) }}"
                                                    data-id="{{ $p->id_pesanan }}"
                                                    onclick="openQrModalManual(this)"
                                                    class="px-3 py-1.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-black text-xs shadow-xs transition-all">
                                                Scan QRIS
                                            </button>
                                        @else
                                            <button type="button"
                                                    onclick="openUploadBukti('{{ $p->id_pesanan }}', '{{ $p->kode_pesanan }}')"
                                                    class="px-3 py-1.5 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-900 border border-amber-200 font-bold text-xs transition-all">
                                                Upload Bukti
                                            </button>
                                        @endif
                                    @endif

                                    <!-- Beri Ulasan Jika Selesai -->
                                    @if($p->status_pesanan === 'Selesai' && !$p->ulasan)
                                        <button type="button"
                                                onclick="openReviewModal('{{ $p->id_pesanan }}')"
                                                class="px-3 py-1.5 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200 font-bold text-xs transition-all">
                                            Beri Ulasan
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400 font-semibold">
                                Belum ada riwayat pesanan laundry. <a href="{{ route('pelanggan.layanan') }}" class="text-blue-600 underline font-bold">Pesan sekarang</a>.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- MODAL DETAIL PESANAN -->
<div id="detailModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/70 backdrop-blur-sm p-4 hidden">
    <div class="bg-white w-full max-w-lg rounded-3xl shadow-2xl p-6 sm:p-8 max-h-[90vh] overflow-y-auto border border-slate-200">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <h3 class="text-xl font-black text-slate-900" id="detailModalTitle">Rincian Pesanan</h3>
            <button type="button" onclick="closeDetailModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 font-black text-sm">✕</button>
        </div>
        <div id="detailModalContent" class="py-4 space-y-4 text-xs font-medium">
            <div class="text-center py-6 text-slate-400 font-bold">Memuat rincian pesanan...</div>
        </div>
    </div>
</div>

<!-- MODAL SCAN QRIS MANUAL -->
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

        <!-- Form upload bukti di dalam modal QRIS -->
        <div class="p-3.5 bg-amber-50 border border-amber-200 rounded-2xl text-left">
            <div class="text-xs font-black text-amber-950 mb-1">Unggah Foto Bukti Transfer:</div>
            <form id="qrManualUploadForm" method="POST" enctype="multipart/form-data" class="space-y-2">
                @csrf
                <input type="file" name="bukti_transfer" accept="image/*" required class="w-full text-xs text-slate-700 file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-amber-600 file:text-white cursor-pointer">
                <button type="submit" class="w-full py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-black text-xs rounded-xl shadow-xs">
                    Kirim Bukti Pembayaran
                </button>
            </form>
        </div>

        <button type="button" onclick="closeQrModalManual()" class="w-full py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-black">
            Tutup
        </button>
    </div>
</div>

<!-- MODAL UPLOAD BUKTI BAYAR STANDALONE -->
<div id="uploadBuktiModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/70 backdrop-blur-sm p-4 hidden">
    <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl p-6 sm:p-8 border border-slate-200">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
            <h3 class="text-lg font-black text-slate-900">Upload Bukti Transfer Pembayaran</h3>
            <button type="button" onclick="closeUploadBukti()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 font-black text-sm">✕</button>
        </div>
        <form id="uploadBuktiForm" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Kode Pesanan</label>
                <input type="text" id="uploadKodePesanan" readonly class="w-full px-4 py-2.5 rounded-xl bg-slate-100 font-mono font-black text-xs text-slate-800 border border-slate-200">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">
                    Pilih File Foto Struk / Bukti Bayar <span class="text-rose-500 font-bold">*</span>
                </label>
                <input type="file" name="bukti_transfer" required accept="image/*"
                       class="w-full text-xs text-slate-700 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
            </div>
            <button type="submit" class="w-full py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-black text-xs shadow-md">
                Kirim Bukti Pembayaran
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openDetailModal(id) {
        document.getElementById('detailModal').classList.remove('hidden');
        document.getElementById('detailModalContent').innerHTML = '<div class="text-center py-6 text-slate-400 font-bold">Memuat data...</div>';

        fetch('{{ url("/pelanggan/pesanan") }}/' + id + '/detail')
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const p = data.pesanan || data.data;
                    document.getElementById('detailModalTitle').textContent = 'Detail Pesanan #' + p.kode_pesanan;
                    document.getElementById('detailModalContent').innerHTML = `
                        <div class="space-y-3">
                            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 space-y-1.5">
                                <div class="font-bold text-slate-800">Layanan: <span class="text-blue-900 font-black">${p.layanan[0]?.nama_layanan || '-'}</span></div>
                                <div class="text-slate-600">Total Tagihan: <b class="text-slate-900 font-black">Rp ${Number(p.total_harga).toLocaleString('id-ID')}</b></div>
                                <div class="text-slate-600">Metode Bayar: <b>${p.metode_pembayaran}</b> &bull; Status: <b class="${(p.status_pembayaran || p.pembayaran?.status_pembayaran) === 'Lunas' ? 'text-emerald-700' : 'text-amber-700'}">${p.status_pembayaran || p.pembayaran?.status_pembayaran || 'Pending'}</b></div>
                                <div class="text-slate-600">Tahap Pengerjaan: <b class="text-blue-900">${p.status_laundry}</b></div>
                            </div>
                            <div class="p-3.5 bg-white rounded-xl border border-slate-200">
                                <div class="font-bold text-slate-800 mb-1">Alamat Penjemputan:</div>
                                <div class="text-slate-600 leading-relaxed">${p.alamat_penjemputan || '-'}</div>
                            </div>
                            ${p.catatan ? `
                            <div class="p-3.5 bg-amber-50 rounded-xl border border-amber-200 text-amber-950">
                                <div class="font-bold mb-1 text-amber-900">Catatan Khusus:</div>
                                <div class="whitespace-pre-line text-slate-700">${p.catatan}</div>
                            </div>` : ''}
                        </div>
                    `;
                }
            })
            .catch(() => {
                document.getElementById('detailModalContent').innerHTML = '<div class="text-center py-4 text-rose-500 font-bold">Gagal memuat rincian pesanan.</div>';
            });
    }

    function closeDetailModal() {
        document.getElementById('detailModal').classList.add('hidden');
    }

    function openQrModalManual(btn) {
        document.getElementById('qrManualModalKode').textContent = '#' + btn.dataset.kode;
        document.getElementById('qrManualModalTotal').textContent = 'Rp ' + Number(btn.dataset.total).toLocaleString('id-ID');
        document.getElementById('qrManualUploadForm').action = '{{ url("/pelanggan/pesanan") }}/' + btn.dataset.id + '/upload-bukti';
        document.getElementById('qrModalManual').classList.remove('hidden');
    }

    function closeQrModalManual() {
        document.getElementById('qrModalManual').classList.add('hidden');
    }

    function openUploadBukti(id, kode) {
        document.getElementById('uploadBuktiForm').action = '{{ url("/pelanggan/pesanan") }}/' + id + '/upload-bukti';
        document.getElementById('uploadKodePesanan').value = '#' + kode;
        document.getElementById('uploadBuktiModal').classList.remove('hidden');
    }

    function closeUploadBukti() {
        document.getElementById('uploadBuktiModal').classList.add('hidden');
    }
</script>
@endpush
@endsection
