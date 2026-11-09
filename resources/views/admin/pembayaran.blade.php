@extends('layouts.app')

@section('title', 'Verifikasi Pembayaran - ArsyClean Admin')
@section('page_title', 'Verifikasi Pembayaran Pelanggan')

@section('content')
<div class="space-y-6">

    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-[#0f2a5c] via-[#163a78] to-[#0a1e42] rounded-3xl p-6 sm:p-8 text-white shadow-xl flex flex-col md:flex-row items-start md:items-center justify-between gap-4 border border-blue-900/40">
        <div>
            <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-white/15 text-sky-200 mb-2 border border-white/20">
                <svg class="w-4 h-4 text-sky-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                Kasir &amp; Verifikasi Keuangan
            </div>
            <h1 class="text-2xl sm:text-3xl font-black tracking-tight">Verifikasi Pembayaran Pelanggan</h1>
            <p class="text-xs sm:text-sm text-blue-100 mt-1 max-w-xl font-medium">
                Periksa bukti transfer QRIS manual by_onest atau verifikasi pembayaran tunai saat kurir/outlet menerima uang.
            </p>
        </div>

        <div class="flex items-center gap-3 shrink-0">
            <div class="bg-white/10 px-5 py-3 rounded-2xl border border-white/20 text-center">
                <div class="text-xs text-amber-300 font-black uppercase tracking-wider">Pending</div>
                <div class="text-2xl font-black text-white mt-0.5">
                    {{ $pembayarans->where('status_pembayaran', 'Pending')->count() }}
                </div>
            </div>
            <div class="bg-white/10 px-5 py-3 rounded-2xl border border-white/20 text-center">
                <div class="text-xs text-emerald-300 font-black uppercase tracking-wider">Lunas</div>
                <div class="text-2xl font-black text-white mt-0.5">
                    {{ $pembayarans->where('status_pembayaran', 'Lunas')->count() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Verifikasi Pembayaran -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-xs font-black uppercase text-slate-600 tracking-wider">
                        <th class="py-4 px-5">Kode Bayar</th>
                        <th class="py-4 px-5">Pesanan</th>
                        <th class="py-4 px-5">Pelanggan</th>
                        <th class="py-4 px-5">Metode</th>
                        <th class="py-4 px-5">Jumlah Tagihan</th>
                        <th class="py-4 px-5">Bukti Transfer</th>
                        <th class="py-4 px-5">Status</th>
                        <th class="py-4 px-5 text-center">Aksi Verifikasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-medium">
                    @forelse($pembayarans as $bayar)
                        @php
                            $isPending = $bayar->status_pembayaran === 'Pending';
                            $buktiPath = $bayar->bukti_transfer ?? $bayar->bukti_pembayaran;
                            $buktiUrl = $buktiPath ? (str_starts_with($buktiPath, 'http') ? $buktiPath : asset('storage/' . $buktiPath)) : null;
                        @endphp
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-4 px-5 font-mono font-black text-slate-800">
                                #{{ $bayar->kode_pembayaran }}
                            </td>
                            <td class="py-4 px-5 font-mono font-bold text-blue-900 text-sm">
                                #{{ $bayar->pesanan->kode_pesanan ?? '-' }}
                            </td>
                            <td class="py-4 px-5 font-bold text-slate-800">
                                <div class="text-slate-900 font-black">{{ $bayar->pesanan->user->nama ?? 'Pelanggan' }}</div>
                                <div class="text-[11px] text-slate-400 font-normal">{{ $bayar->pesanan->user->no_hp ?? '-' }}</div>
                            </td>
                            <td class="py-4 px-5">
                                <span class="px-2.5 py-1 rounded-lg text-xs font-bold {{ $bayar->metode_pembayaran === 'Tunai' ? 'bg-slate-100 text-slate-800' : 'bg-blue-50 text-blue-800' }}">
                                    {{ $bayar->metode_pembayaran }}
                                </span>
                            </td>
                            <td class="py-4 px-5 font-black text-slate-900 text-sm">
                                Rp {{ number_format($bayar->jumlah_bayar, 0, ',', '.') }}
                            </td>
                            <td class="py-4 px-5">
                                @if(!empty($buktiUrl))
                                    <button type="button"
                                            onclick="previewBukti('{{ $buktiUrl }}', '{{ $bayar->kode_pembayaran }}', '{{ $bayar->pesanan->kode_pesanan ?? '' }}', '{{ number_format($bayar->jumlah_bayar, 0, ',', '.') }}')"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-200 font-bold text-xs transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <span>Lihat Bukti Foto</span>
                                    </button>
                                @else
                                    <span class="text-slate-400 italic text-xs">
                                        {{ $bayar->metode_pembayaran === 'Tunai' ? 'Bayar di tempat' : 'Belum diunggah' }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-5">
                                <span class="px-3 py-1 rounded-full text-xs font-black
                                    {{ $isPending ? 'bg-amber-100 text-amber-950 border border-amber-300' : 'bg-emerald-100 text-emerald-950 border border-emerald-300' }}">
                                    {{ $bayar->status_pembayaran }}
                                </span>
                            </td>
                            <td class="py-4 px-5 text-center">
                                @if($isPending)
                                    <form action="{{ route('admin.pembayaran.verifikasi', $bayar->id_pembayaran) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                                onclick="return confirm('Verifikasi pembayaran #{{ $bayar->kode_pembayaran }} sebesar Rp {{ number_format($bayar->jumlah_bayar, 0, ',', '.') }} sebagai LUNAS?')"
                                                class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white font-black text-xs rounded-xl shadow-xs transition-all inline-flex items-center gap-1.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                            <span>Set Lunas</span>
                                        </button>
                                    </form>
                                @else
                                    <span class="text-slate-500 font-semibold text-xs">
                                        Oleh: <b>{{ $bayar->verifikator ?? 'Admin' }}</b>
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-10 text-center text-slate-400 font-semibold">
                                Belum ada riwayat transaksi pembayaran.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- MODAL PREVIEW BUKTI TRANSFER -->
<div id="modalPreviewBukti" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/70 backdrop-blur-sm p-4 hidden">
    <div class="bg-white w-full max-w-lg rounded-3xl shadow-2xl p-6 border border-slate-200">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
            <div>
                <h3 class="text-lg font-black text-slate-900" id="previewTitle">Bukti Transfer</h3>
                <p class="text-xs text-slate-500 mt-0.5" id="previewSubtitle">-</p>
            </div>
            <button type="button" onclick="closePreviewBukti()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 font-black text-sm">✕</button>
        </div>
        <div class="p-2 bg-slate-50 rounded-2xl border border-slate-200 max-h-[65vh] overflow-auto text-center">
            <img id="previewImage" src="" alt="Bukti Pembayaran" class="mx-auto rounded-xl object-contain max-h-[60vh] shadow-sm">
        </div>
        <div class="mt-4 flex justify-end">
            <button type="button" onclick="closePreviewBukti()" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl">
                Tutup
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function previewBukti(url, kodeBayar, kodePesanan, nominal) {
        document.getElementById('previewTitle').textContent = 'Bukti Pembayaran #' + kodeBayar;
        document.getElementById('previewSubtitle').textContent = 'Pesanan #' + kodePesanan + ' • Nominal: Rp ' + nominal;
        document.getElementById('previewImage').src = url;
        document.getElementById('modalPreviewBukti').classList.remove('hidden');
    }
    function closePreviewBukti() {
        document.getElementById('modalPreviewBukti').classList.add('hidden');
    }
</script>
@endpush
@endsection
