@extends('layouts.app')

@section('title', 'Pengerjaan Laundry - ArsyClean Admin')
@section('page_title', 'Proses Pengerjaan Laundry')

@section('content')
<div class="space-y-7">

    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-[#0f2a5c] via-[#163a78] to-[#0a1e42] rounded-3xl p-6 sm:p-8 text-white shadow-xl flex flex-col md:flex-row items-start md:items-center justify-between gap-4 border border-blue-900/40">
        <div>
            <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-white/15 text-sky-200 mb-2 border border-white/20">
                <svg class="w-4 h-4 text-sky-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Alur Produksi Laundry
            </div>
            <h1 class="text-2xl sm:text-3xl font-black tracking-tight">Data Pengerjaan Laundry Bertahap</h1>
            <p class="text-xs sm:text-sm text-blue-100 mt-1 max-w-xl font-medium">
                Perbarui tahapan cuci secara berurutan. Pelanggan dapat memantau pergerakan status ini secara realtime pada dashboard mereka.
            </p>
        </div>

        <div class="bg-white/10 px-6 py-4 rounded-2xl border border-white/20 text-center shrink-0">
            <div class="text-xs text-sky-200 font-bold uppercase tracking-wider">Sedang Dikerjakan</div>
            <div class="text-3xl font-black text-white mt-0.5">{{ $pesananDiproses->count() }} Pesanan</div>
        </div>
    </div>

    <!-- Daftar Pesanan Dalam Pengerjaan -->
    <div class="space-y-5">
        @forelse($pesananDiproses as $p)
            @php
                $statusLaundry = $p->status_laundry ?? 'Menunggu Antrean';
                $badgeWarna = match ($statusLaundry) {
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
            @endphp

            <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200 hover:border-blue-300 shadow-sm transition-all space-y-4">
                <div class="flex flex-col md:flex-row justify-between gap-4">
                    <div class="space-y-1.5">
                        <div class="flex items-center gap-3 flex-wrap">
                            <span class="font-mono text-lg font-black text-slate-900">#{{ $p->kode_pesanan }}</span>
                            <span class="px-3.5 py-1 rounded-full text-xs shadow-xs {{ $badgeWarna }}">
                                Tahap: {{ $statusLaundry }}
                            </span>
                        </div>

                        <div class="text-sm font-bold text-slate-800">
                            Pelanggan: <span class="text-blue-700 font-black">{{ $p->user->nama ?? 'Pelanggan' }}</span>
                            <span class="text-slate-500 font-medium">({{ $p->user->no_hp ?? '-' }})</span>
                        </div>

                        <div class="text-xs text-slate-600 font-medium">
                            Layanan: <b>{{ $p->layanan->first()->nama_layanan ?? '-' }}</b>
                            @if($p->layanan->first())
                                ({{ $p->layanan->first()->pivot->jumlah }} {{ $p->layanan->first()->satuan }})
                            @endif
                            &bull; Alamat: {{ $p->alamat_penjemputan ?? '-' }}
                        </div>
                    </div>

                    <div class="text-left md:text-right shrink-0">
                        <div class="text-xs text-slate-400 font-bold uppercase">Total Tagihan</div>
                        <div class="text-2xl font-black text-slate-900">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</div>
                        <div class="mt-1">
                            <span class="px-3 py-1 rounded-full text-xs font-black {{ ($p->pembayaran?->status_pembayaran ?? 'Pending') === 'Lunas' ? 'bg-emerald-100 text-emerald-900 border border-emerald-300' : 'bg-amber-100 text-amber-900 border border-amber-300' }}">
                                Bayar: {{ $p->pembayaran?->status_pembayaran ?? 'Pending' }} ({{ $p->metode_pembayaran }})
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Catatan Pelanggan -->
                @if(!empty($p->catatan))
                    <div class="p-4 bg-amber-50/80 border border-amber-200 rounded-2xl text-xs text-amber-950">
                        <div class="font-black text-amber-900 flex items-center gap-1.5 mb-1">
                            <svg class="w-4 h-4 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            <span>Catatan Khusus Pelanggan:</span>
                        </div>
                        <div class="whitespace-pre-line text-slate-700 pl-5 font-semibold leading-relaxed">{{ $p->catatan }}</div>
                    </div>
                @endif

                <!-- Form Update Status Laundry -->
                <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
                    <form action="{{ route('admin.pesanan.status', $p->id_pesanan) }}" method="POST"
                          class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 flex-1">
                        @csrf
                        <label class="text-xs font-black uppercase tracking-wider text-slate-700 shrink-0">
                            Perbarui Tahap Pengerjaan <span class="text-rose-500 font-bold">*</span>:
                        </label>

                        <!-- Pilihan Status dengan Warna & Urutan Jelas -->
                        <select name="status_laundry" required
                                class="px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-black text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 flex-1">
                            @foreach([
                                'Menunggu Antrean',
                                'Pencucian',
                                'Pengeringan',
                                'Setrika',
                                'Siap Diambil',
                                'Siap Dikirim',
                                'Dalam Pengiriman',
                                'Selesai'
                            ] as $st)
                                <option value="{{ $st }}" {{ $p->status_laundry === $st ? 'selected' : '' }}>
                                    {{ $st }}
                                </option>
                            @endforeach
                        </select>

                        <button type="submit"
                                class="px-6 py-3 bg-slate-900 hover:bg-black active:scale-95 text-white rounded-xl text-xs font-black shadow-xs transition-all flex items-center justify-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>Simpan Perubahan</span>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="p-12 text-center bg-white rounded-3xl border border-dashed border-slate-300">
                <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                <h3 class="text-base font-bold text-slate-700">Tidak ada pengerjaan aktif</h3>
                <p class="text-xs text-slate-400 mt-1">Semua pesanan laundry telah selesai dikerjakan atau belum ada pesanan baru yang diterima.</p>
            </div>
        @endforelse
    </div>

</div>
@endsection
