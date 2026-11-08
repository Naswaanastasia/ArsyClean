@extends('layouts.pelanggan')

@section('title', 'Riwayat Pesanan Laundry - ArsyClean')

@section('content')
<div class="space-y-7">

    <!-- Header Riwayat -->
    <div class="bg-gradient-to-r from-slate-900 via-[#0f2a5c] to-blue-950 rounded-3xl p-6 sm:p-8 text-white shadow-xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-white/20 text-sky-200 mb-2 border border-white/10">
                📜 Arsip &amp; Status Cucian
            </div>
            <h1 class="text-2xl sm:text-3xl font-black tracking-tight">Riwayat Pesanan Saya</h1>
            <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl">
                Pantau seluruh riwayat pesanan laundry Anda, unduh nota pembayaran resmi PDF, atau unggah bukti transfer.
            </p>
        </div>

        <a href="{{ route('pelanggan.layanan') }}"
           class="px-5 py-3 rounded-2xl bg-blue-600 hover:bg-blue-500 text-white font-extrabold text-xs shadow-md shadow-blue-600/30 transition-all flex items-center gap-2 shrink-0">
            <span>+ Pesan Laundry Baru</span>
        </a>
    </div>

    <!-- Filter Tab Status -->
    <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none">
        @php
            $currentStatus = request('status', '');
        @endphp
        <a href="{{ route('pelanggan.riwayat') }}"
           class="px-4 py-2 rounded-xl text-xs font-black shrink-0 transition-all {{ $currentStatus === '' ? 'bg-slate-900 text-white shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            Semua ({{ $pesanans->count() }})
        </a>
        <a href="{{ route('pelanggan.riwayat', ['status' => 'Menunggu Pembayaran']) }}"
           class="px-4 py-2 rounded-xl text-xs font-black shrink-0 transition-all {{ $currentStatus === 'Menunggu Pembayaran' ? 'bg-amber-600 text-white shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            Menunggu Pembayaran
        </a>
        <a href="{{ route('pelanggan.riwayat', ['status' => 'Diproses']) }}"
           class="px-4 py-2 rounded-xl text-xs font-black shrink-0 transition-all {{ $currentStatus === 'Diproses' ? 'bg-sky-600 text-white shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            Sedang Diproses
        </a>
        <a href="{{ route('pelanggan.riwayat', ['status' => 'Selesai']) }}"
           class="px-4 py-2 rounded-xl text-xs font-black shrink-0 transition-all {{ $currentStatus === 'Selesai' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            Selesai
        </a>
        <a href="{{ route('pelanggan.riwayat', ['status' => 'Dibatalkan']) }}"
           class="px-4 py-2 rounded-xl text-xs font-black shrink-0 transition-all {{ $currentStatus === 'Dibatalkan' ? 'bg-rose-600 text-white shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            Dibatalkan
        </a>
    </div>

    <!-- Tabel Daftar Pesanan -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-black uppercase text-slate-500 tracking-wider">
                        <th class="py-4 px-5">Kode Pesanan</th>
                        <th class="py-4 px-5">Layanan</th>
                        <th class="py-4 px-5">Tanggal</th>
                        <th class="py-4 px-5">Total Tagihan</th>
                        <th class="py-4 px-5">Status Pengerjaan</th>
                        <th class="py-4 px-5">Pembayaran</th>
                        <th class="py-4 px-5 text-center">Aksi &amp; Dokumen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($pesanans as $p)
                        @php
                            $stLaundry = $p->status_laundry ?? 'Menunggu Antrean';
                            $badgeLaundry = match ($stLaundry) {
                                'Menunggu Antrean' => ['bg' => 'bg-amber-100 text-amber-900 border-amber-300', 'icon' => '⏳'],
                                'Pencucian'        => ['bg' => 'bg-sky-100 text-sky-900 border-sky-300', 'icon' => '🫧'],
                                'Pengeringan'      => ['bg' => 'bg-indigo-100 text-indigo-900 border-indigo-300', 'icon' => '💨'],
                                'Setrika'          => ['bg' => 'bg-purple-100 text-purple-900 border-purple-300', 'icon' => '👔'],
                                'Siap Diambil'     => ['bg' => 'bg-teal-100 text-teal-900 border-teal-300', 'icon' => '🛍️'],
                                'Siap Dikirim'     => ['bg' => 'bg-orange-100 text-orange-900 border-orange-300', 'icon' => '📦'],
                                'Dalam Pengiriman' => ['bg' => 'bg-blue-100 text-blue-900 border-blue-300', 'icon' => '🛵'],
                                'Selesai'          => ['bg' => 'bg-emerald-100 text-emerald-900 border-emerald-300', 'icon' => '✅'],
                                default            => ['bg' => 'bg-slate-100 text-slate-800 border-slate-300', 'icon' => '📋'],
                            };

                            $isBayarLunas = ($p->pembayaran?->status_pembayaran ?? 'Pending') === 'Lunas';
                        @endphp
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-4 px-5 font-mono font-black text-blue-900 text-sm">
                                #{{ $p->kode_pesanan }}
                            </td>
                            <td class="py-4 px-5 font-bold text-slate-800">
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
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-black border {{ $badgeLaundry['bg'] }}">
                                    <span>{{ $badgeLaundry['icon'] }}</span>
                                    <span>{{ $stLaundry }}</span>
                                </span>
                            </td>
                            <td class="py-4 px-5">
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold {{ $isBayarLunas ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-amber-100 text-amber-800 border border-amber-200' }}">
                                    {{ $p->pembayaran?->status_pembayaran ?? 'Pending' }}
                                </span>
                            </td>
                            <td class="py-4 px-5 text-center">
                                <div class="flex items-center justify-center gap-1.5 flex-wrap">
                                    <!-- Tombol Detail Modal -->
                                    <button type="button"
                                            onclick="openDetailModal('{{ $p->id_pesanan }}')"
                                            class="px-2.5 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-[11px] transition-all">
                                        Detail
                                    </button>

                                    <!-- Cetak Nota PDF Kapan Saja -->
                                    <a href="{{ route('pelanggan.pesanan.nota', $p->id_pesanan) }}"
                                       target="_blank"
                                       class="px-2.5 py-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 font-bold text-[11px] transition-all flex items-center gap-1">
                                        <span>🖨️</span> Nota PDF
                                    </a>

                                    <!-- Upload Bukti Bayar jika Pending -->
                                    @if(!$isBayarLunas && $p->status_pesanan !== 'Dibatalkan')
                                        <button type="button"
                                                onclick="openUploadBukti('{{ $p->id_pesanan }}', '{{ $p->kode_pesanan }}')"
                                                class="px-2.5 py-1.5 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200 font-bold text-[11px] transition-all">
                                            Bukti Bayar
                                        </button>
                                    @endif

                                    <!-- Beri Ulasan Jika Selesai -->
                                    @if($p->status_pesanan === 'Selesai' && !$p->ulasan)
                                        <button type="button"
                                                onclick="openReviewModal('{{ $p->id_pesanan }}')"
                                                class="px-2.5 py-1.5 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 font-bold text-[11px] transition-all">
                                            ⭐ Ulasan
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
<div id="detailModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4 hidden">
    <div class="bg-white w-full max-w-lg rounded-3xl shadow-2xl p-6 sm:p-8 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <h3 class="text-lg font-black text-slate-900" id="detailModalTitle">Rincian Pesanan</h3>
            <button type="button" onclick="closeDetailModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-sm">✕</button>
        </div>
        <div id="detailModalContent" class="py-4 space-y-4 text-xs">
            <div class="text-center py-6 text-slate-400">Memuat rincian pesanan...</div>
        </div>
    </div>
</div>

<!-- MODAL UPLOAD BUKTI BAYAR -->
<div id="uploadBuktiModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4 hidden">
    <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl p-6 sm:p-8">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
            <h3 class="text-base font-black text-slate-900">Upload Bukti Transfer Bank / QR</h3>
            <button type="button" onclick="closeUploadBukti()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-sm">✕</button>
        </div>
        <form id="uploadBuktiForm" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Kode Pesanan</label>
                <input type="text" id="uploadKodePesanan" readonly class="w-full px-3 py-2 rounded-xl bg-slate-100 font-mono font-bold text-xs text-slate-700">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">
                    Pilih File Foto Struk / Bukti Bayar <span class="text-rose-500 font-bold">*</span>
                </label>
                <input type="file" name="bukti_pembayaran" required accept="image/*"
                       class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            </div>
            <button type="submit" class="w-full py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs shadow-md">
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
                    const p = data.pesanan;
                    document.getElementById('detailModalTitle').textContent = 'Detail Pesanan #' + p.kode_pesanan;
                    document.getElementById('detailModalContent').innerHTML = `
                        <div class="space-y-3">
                            <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200 space-y-1">
                                <div class="font-bold text-slate-800">Layanan: <span class="text-blue-700">${p.layanan[0]?.nama_layanan || '-'}</span></div>
                                <div class="text-slate-600">Total Biaya: <b class="text-slate-900">Rp ${Number(p.total_harga).toLocaleString('id-ID')}</b></div>
                                <div class="text-slate-600">Metode Bayar: <b>${p.metode_pembayaran}</b> &bull; Status: <b>${p.pembayaran?.status_pembayaran || 'Pending'}</b></div>
                            </div>
                            <div class="p-3 bg-white rounded-xl border border-slate-200">
                                <div class="font-bold text-slate-800 mb-1">📍 Alamat Penjemputan:</div>
                                <div class="text-slate-600 leading-relaxed">${p.alamat_penjemputan || '-'}</div>
                            </div>
                            ${p.catatan ? `
                            <div class="p-3 bg-amber-50 rounded-xl border border-amber-200 text-amber-900">
                                <div class="font-bold mb-1">📝 Catatan Khusus:</div>
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
