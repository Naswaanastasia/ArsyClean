@extends('layouts.app')

@section('title', 'Verifikasi Pembayaran - ArsyClean Admin')
@section('page_title', 'Verifikasi Pembayaran Pelanggan')

@section('content')
<div class="space-y-6">

    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-emerald-950 via-[#0f2a5c] to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-emerald-400 text-emerald-950 mb-2">
                💳 Kasir &amp; Keuangan
            </div>
            <h1 class="text-2xl sm:text-3xl font-black tracking-tight">Verifikasi Pembayaran Pelanggan</h1>
            <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl">
                Periksa bukti transfer QRIS / manual bank dan verifikasi pesanan yang telah menyelesaikan pembayaran.
            </p>
        </div>

        <div class="flex items-center gap-3 shrink-0">
            <div class="bg-white/10 px-4 py-3 rounded-2xl border border-white/10 text-center">
                <div class="text-[11px] text-amber-300 font-bold uppercase">Pending</div>
                <div class="text-2xl font-black text-white mt-0.5">
                    {{ $pembayarans->where('status_pembayaran', 'Pending')->count() }}
                </div>
            </div>
            <div class="bg-white/10 px-4 py-3 rounded-2xl border border-white/10 text-center">
                <div class="text-[11px] text-emerald-300 font-bold uppercase">Lunas</div>
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
                    <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-black uppercase text-slate-500 tracking-wider">
                        <th class="py-4 px-5">Kode Bayar</th>
                        <th class="py-4 px-5">Kode Pesanan</th>
                        <th class="py-4 px-5">Pelanggan</th>
                        <th class="py-4 px-5">Jumlah Tagihan</th>
                        <th class="py-4 px-5">Status</th>
                        <th class="py-4 px-5">Bukti Transfer</th>
                        <th class="py-4 px-5 text-center">Aksi Verifikasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($pembayarans as $bayar)
                        @php
                            $isPending = $bayar->status_pembayaran === 'Pending';
                        @endphp
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-4 px-5 font-mono font-black text-slate-800">
                                #{{ $bayar->kode_pembayaran }}
                            </td>
                            <td class="py-4 px-5 font-mono font-bold text-blue-800">
                                #{{ $bayar->pesanan->kode_pesanan ?? '-' }}
                            </td>
                            <td class="py-4 px-5 font-bold text-slate-800">
                                <div>{{ $bayar->pesanan->user->nama ?? 'Pelanggan' }}</div>
                                <div class="text-[11px] text-slate-400 font-normal">{{ $bayar->pesanan->user->no_hp ?? '-' }}</div>
                            </td>
                            <td class="py-4 px-5 font-black text-slate-900 text-sm">
                                Rp {{ number_format($bayar->jumlah_bayar, 0, ',', '.') }}
                            </td>
                            <td class="py-4 px-5">
                                <span class="px-3 py-1 rounded-full text-[11px] font-black border
                                    {{ $isPending ? 'bg-amber-100 text-amber-900 border-amber-300' : 'bg-emerald-100 text-emerald-900 border-emerald-300' }}">
                                    {{ $bayar->status_pembayaran }}
                                </span>
                            </td>
                            <td class="py-4 px-5">
                                @if(!empty($bayar->bukti_pembayaran))
                                    <a href="{{ asset($bayar->bukti_pembayaran) }}" target="_blank"
                                       class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-200 font-bold text-[11px] transition-all">
                                        <span>📷</span> Lihat Struk
                                    </a>
                                @else
                                    <span class="text-slate-400 italic text-[11px]">Tidak ada file</span>
                                @endif
                            </td>
                            <td class="py-4 px-5 text-center">
                                @if($isPending)
                                    <form action="{{ route('admin.pembayaran.verifikasi', $bayar->id_pembayaran) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                                onclick="return confirm('Verifikasi pembayaran ini sebagai LUNAS?')"
                                                class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white font-extrabold text-[11px] rounded-xl shadow-xs transition-all">
                                            ✓ Set Lunas
                                        </button>
                                    </form>
                                @else
                                    <span class="text-slate-400 font-semibold text-[11px]">
                                        Oleh: {{ $bayar->verifikator ?? 'Sistem' }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-10 text-center text-slate-400 font-semibold">
                                Belum ada riwayat transaksi pembayaran.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
