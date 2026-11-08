@extends('layouts.app')

@section('title', 'Pesanan Masuk - ArsyClean Admin')
@section('page_title', 'Pesanan Masuk (Menunggu Verifikasi)')

@section('content')
<div class="space-y-6">

    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-slate-900 via-[#0f2a5c] to-blue-950 rounded-3xl p-6 sm:p-8 text-white shadow-xl flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-amber-400 text-amber-950 mb-2">
                📥 Verifikasi Awal
            </div>
            <h1 class="text-2xl sm:text-3xl font-black tracking-tight">Pesanan Masuk Menunggu Konfirmasi</h1>
            <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl">
                Tinjau pesanan baru dari pelanggan, periksa alamat penjemputan, instruksi catatan khusus, lalu konfirmasi untuk memindahkan ke tahap pengerjaan.
            </p>
        </div>

        <div class="bg-white/10 px-5 py-3.5 rounded-2xl border border-white/10 text-center shrink-0">
            <div class="text-xs text-sky-200 font-bold">Total Pesanan Baru</div>
            <div class="text-3xl font-black text-amber-300 mt-0.5">{{ $pesananMasuk->count() }}</div>
        </div>
    </div>

    <!-- Daftar Pesanan Masuk -->
    <div class="space-y-4">
        @forelse($pesananMasuk as $p)
            <div class="bg-white rounded-3xl p-6 border border-slate-200 hover:border-blue-300 shadow-xs transition-all space-y-4">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <div class="space-y-2 flex-1">
                        <div class="flex items-center gap-2.5 flex-wrap">
                            <span class="font-mono text-lg font-black text-blue-900">#{{ $p->kode_pesanan }}</span>
                            <span class="px-3 py-0.5 rounded-full text-xs font-black bg-amber-100 text-amber-800 border border-amber-300">
                                {{ $p->status_pesanan }}
                            </span>
                            <span class="text-xs font-semibold text-slate-400">
                                📅 {{ $p->tanggal_pesanan ? $p->tanggal_pesanan->format('d M Y, H:i') : '-' }}
                            </span>
                        </div>

                        <div class="text-sm font-bold text-slate-800 flex items-center gap-3 flex-wrap">
                            <span>Pelanggan: <b class="text-blue-700">{{ $p->user->nama ?? 'Pelanggan' }}</b></span>
                            <span class="text-slate-300">&bull;</span>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $p->user->no_hp ?? '') }}" target="_blank"
                               class="text-emerald-700 hover:underline flex items-center gap-1 font-semibold text-xs">
                                <span>📱 WA: {{ $p->user->no_hp ?? '-' }}</span>
                            </a>
                        </div>

                        <div class="text-xs text-slate-600 flex items-center gap-2 flex-wrap">
                            <span>Layanan: <b>{{ $p->layanan->first()->nama_layanan ?? '-' }}</b></span>
                            @if($p->layanan->first())
                                <span>({{ $p->layanan->first()->pivot->jumlah }} {{ $p->layanan->first()->satuan }})</span>
                            @endif
                            <span class="text-slate-300">&bull;</span>
                            <span>Total Biaya: <b class="text-slate-900 font-black">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</b></span>
                            <span class="text-slate-300">&bull;</span>
                            <span>Metode: <b class="capitalize">{{ $p->metode_pembayaran }}</b></span>
                        </div>

                        <div class="text-xs text-slate-600 bg-slate-50 p-3 rounded-xl border border-slate-100">
                            <b>📍 Alamat Penjemputan:</b> {{ $p->alamat_penjemputan ?? '-' }}
                        </div>

                        <!-- Catatan Dari Pelanggan Ditonjolkan Jelas -->
                        @if(!empty($p->catatan))
                            <div class="p-3.5 bg-amber-50 border-2 border-amber-200 rounded-2xl text-xs text-amber-950 space-y-1">
                                <div class="font-black text-amber-900 flex items-center gap-1.5">
                                    <span>📝</span> Catatan Khusus Pelanggan:
                                </div>
                                <div class="whitespace-pre-line text-slate-800 font-medium pl-5 leading-relaxed">{{ $p->catatan }}</div>
                            </div>
                        @endif
                    </div>

                    <!-- Tombol Aksi Terima / Tolak -->
                    <div class="flex items-center gap-2.5 pt-2 lg:pt-0 shrink-0">
                        <form action="{{ route('admin.pesanan.konfirmasi', $p->id_pesanan) }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="px-5 py-3 bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white rounded-2xl font-black text-xs shadow-md shadow-emerald-600/25 transition-all flex items-center gap-2">
                                <span>✓</span>
                                <span>Terima Pesanan</span>
                            </button>
                        </form>

                        <button type="button"
                                onclick="openTolakModal('{{ $p->id_pesanan }}', '{{ $p->kode_pesanan }}')"
                                class="px-4 py-3 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-2xl font-bold text-xs transition-all">
                            Tolak
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="p-12 text-center bg-white rounded-3xl border border-dashed border-slate-300">
                <div class="text-4xl mb-2">📥</div>
                <h3 class="text-base font-bold text-slate-700">Tidak ada pesanan masuk</h3>
                <p class="text-xs text-slate-400 mt-1">Saat ini belum ada pesanan baru yang menunggu konfirmasi admin.</p>
            </div>
        @endforelse
    </div>

</div>

<!-- Modal Tolak Pesanan -->
<div id="tolakModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4 hidden">
    <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl p-6 sm:p-8">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
            <h3 class="text-base font-black text-slate-900">Tolak Pesanan <span id="modalTolakKode" class="text-rose-600 font-mono"></span></h3>
            <button type="button" onclick="closeTolakModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-sm">✕</button>
        </div>
        <form id="tolakForm" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                    Alasan Penolakan <span class="text-rose-500 font-bold">*</span>
                </label>
                <textarea name="alasan" rows="3" required placeholder="Contoh: Lokasi penjemputan di luar jangkauan kurir..."
                          class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 text-xs font-medium text-slate-900"></textarea>
            </div>
            <button type="submit" class="w-full py-3 rounded-2xl bg-rose-600 hover:bg-rose-700 text-white font-extrabold text-xs shadow-md shadow-rose-600/25">
                Konfirmasi Tolak Pesanan
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openTolakModal(id, kode) {
        document.getElementById('tolakForm').action = '{{ url("/admin/pesanan") }}/' + id + '/tolak';
        document.getElementById('modalTolakKode').textContent = '#' + kode;
        document.getElementById('tolakModal').classList.remove('hidden');
    }
    function closeTolakModal() {
        document.getElementById('tolakModal').classList.add('hidden');
    }
</script>
@endpush
@endsection
