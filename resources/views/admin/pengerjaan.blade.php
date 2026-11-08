@extends('layouts.app')

@section('title', 'Pengerjaan Laundry - ArsyClean Admin')
@section('page_title', 'Proses Pengerjaan Laundry')

@section('content')
<div class="space-y-7">

    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-sky-900 via-[#0f2a5c] to-blue-950 rounded-3xl p-6 sm:p-8 text-white shadow-xl flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-sky-400 text-sky-950 mb-2">
                ⚙️ Alur Produksi Laundry
            </div>
            <h1 class="text-2xl sm:text-3xl font-black tracking-tight">Data Pengerjaan Laundry Bertahap</h1>
            <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl">
                Perbarui tahapan cuci secara berurutan. Pelanggan dapat memantau pergerakan status ini secara realtime pada dashboard mereka.
            </p>
        </div>

        <div class="bg-white/10 px-5 py-3.5 rounded-2xl border border-white/10 text-center shrink-0">
            <div class="text-xs text-sky-200 font-bold">Sedang Dikerjakan</div>
            <div class="text-3xl font-black text-sky-300 mt-0.5">{{ $pesananDiproses->count() }} Pesanan</div>
        </div>
    </div>

    <!-- Daftar Pesanan Dalam Pengerjaan -->
    <div class="space-y-5">
        @forelse($pesananDiproses as $p)
            @php
                $statusLaundry = $p->status_laundry ?? 'Menunggu Antrean';
                // Warna badge pengerjaan yang kontras & informatif
                $badgeWarna = match ($statusLaundry) {
                    'Menunggu Antrean' => 'bg-amber-100 text-amber-900 border-amber-300',
                    'Pencucian'        => 'bg-sky-100 text-sky-900 border-sky-300',
                    'Pengeringan'      => 'bg-indigo-100 text-indigo-900 border-indigo-300',
                    'Setrika'          => 'bg-purple-100 text-purple-900 border-purple-300',
                    'Siap Diambil'     => 'bg-teal-100 text-teal-900 border-teal-300',
                    'Siap Dikirim'     => 'bg-orange-100 text-orange-900 border-orange-300',
                    'Dalam Pengiriman' => 'bg-blue-100 text-blue-900 border-blue-300',
                    'Selesai'          => 'bg-emerald-100 text-emerald-900 border-emerald-300',
                    default            => 'bg-slate-100 text-slate-800 border-slate-300',
                };
            @endphp

            <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200 hover:border-sky-300 shadow-xs transition-all space-y-4">
                <div class="flex flex-col md:flex-row justify-between gap-4">
                    <div class="space-y-1.5">
                        <div class="flex items-center gap-2.5 flex-wrap">
                            <span class="font-mono text-lg font-black text-slate-900">#{{ $p->kode_pesanan }}</span>
                            <span class="px-3.5 py-1 rounded-full text-xs font-black border {{ $badgeWarna }}">
                                Tahap: {{ $statusLaundry }}
                            </span>
                        </div>

                        <div class="text-sm font-bold text-slate-700">
                            Pelanggan: <span class="text-blue-700">{{ $p->user->nama ?? 'Pelanggan' }}</span>
                            <span class="text-slate-400 font-normal">({{ $p->user->no_hp ?? '-' }})</span>
                        </div>

                        <div class="text-xs text-slate-600">
                            Layanan: <b>{{ $p->layanan->first()->nama_layanan ?? '-' }}</b>
                            @if($p->layanan->first())
                                ({{ $p->layanan->first()->pivot->jumlah }} {{ $p->layanan->first()->satuan }})
                            @endif
                            &bull; Alamat: {{ $p->alamat_penjemputan ?? '-' }}
                        </div>
                    </div>

                    <div class="text-left md:text-right shrink-0">
                        <div class="text-xs text-slate-400 font-semibold">Total Tagihan</div>
                        <div class="text-xl font-black text-slate-900">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</div>
                        <div class="mt-1">
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ ($p->pembayaran?->status_pembayaran ?? 'Pending') === 'Lunas' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                Bayar: {{ $p->pembayaran?->status_pembayaran ?? 'Pending' }} ({{ $p->metode_pembayaran }})
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Catatan Pelanggan -->
                @if(!empty($p->catatan))
                    <div class="p-3 bg-amber-50/80 border border-amber-200 rounded-2xl text-xs text-amber-950">
                        <div class="font-bold text-amber-900 flex items-center gap-1.5 mb-0.5">
                            <span>📝</span> Catatan Khusus Pelanggan:
                        </div>
                        <div class="whitespace-pre-line text-slate-700 pl-5 font-medium">{{ $p->catatan }}</div>
                    </div>
                @endif

                <!-- Form Update Status Laundry -->
                <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
                    <form action="{{ route('admin.pesanan.status', $p->id_pesanan) }}" method="POST"
                          class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 flex-1">
                        @csrf
                        <label class="text-xs font-bold text-slate-700 shrink-0">
                            Perbarui Status <span class="text-rose-500 font-bold">*</span>:
                        </label>

                        <!-- Pilihan Status dengan Siap Dikirim -->
                        <select name="status_laundry" required
                                class="px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-sky-500 flex-1">
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
                                class="px-5 py-2.5 bg-slate-900 hover:bg-black active:scale-95 text-white rounded-xl text-xs font-black shadow-xs transition-all">
                            Simpan Perubahan
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="p-12 text-center bg-white rounded-3xl border border-dashed border-slate-300">
                <div class="text-4xl mb-2">🧺</div>
                <h3 class="text-base font-bold text-slate-700">Tidak ada pengerjaan aktif</h3>
                <p class="text-xs text-slate-400 mt-1">Semua pesanan laundry telah selesai dikerjakan atau belum ada pesanan baru yang diterima.</p>
            </div>
        @endforelse
    </div>

</div>
@endsection
