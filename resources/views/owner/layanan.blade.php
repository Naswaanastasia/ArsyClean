@extends('layouts.app')

@section('title', 'Katalog Tarif & Layanan - ArsyClean Owner')
@section('page_title', 'Pengaturan Tarif & Layanan Laundry')

@section('content')
<div class="space-y-6">

    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-[#0f2a5c] via-[#163a78] to-[#0a1e42] rounded-3xl p-6 sm:p-8 text-white shadow-xl flex flex-col md:flex-row items-start md:items-center justify-between gap-4 border border-blue-900/40">
        <div>
            <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-amber-400 text-amber-950 mb-2 shadow-xs">
                <svg class="w-4 h-4 text-amber-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Kebijakan Tarif &amp; Paket
            </div>
            <h1 class="text-2xl sm:text-3xl font-black tracking-tight">Katalog Tarif &amp; Layanan Laundry</h1>
            <p class="text-xs sm:text-sm text-blue-100 mt-1 max-w-xl font-medium">
                Pantau seluruh paket laundry, perbarui harga per kilogram atau per satuan, serta atur status ketersediaan operasional.
            </p>
        </div>

        <div class="bg-white/10 px-6 py-4 rounded-2xl border border-white/20 text-center shrink-0">
            <div class="text-xs text-sky-200 font-bold uppercase tracking-wider">Total Layanan</div>
            <div class="text-3xl font-black text-amber-300 mt-0.5">{{ $layanans->count() }} Paket</div>
        </div>
    </div>

    <!-- Tabel Pengaturan Layanan & Tarif -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-xs font-black uppercase text-slate-600 tracking-wider">
                        <th class="py-4 px-5">Kode</th>
                        <th class="py-4 px-5">Nama Layanan</th>
                        <th class="py-4 px-5">Kategori</th>
                        <th class="py-4 px-5">Satuan</th>
                        <th class="py-4 px-5">Tarif Saat Ini</th>
                        <th class="py-4 px-5">Status</th>
                        <th class="py-4 px-5 text-center">Perbarui Tarif</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-medium">
                    @foreach($layanans as $layanan)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-4 px-5 font-mono font-black text-slate-900">
                                {{ $layanan->kode_layanan }}
                            </td>
                            <td class="py-4 px-5 font-bold text-slate-900">
                                <div>{{ $layanan->nama_layanan }}</div>
                                <div class="text-[11px] text-slate-400 font-normal line-clamp-1">{{ $layanan->deskripsi }}</div>
                            </td>
                            <td class="py-4 px-5">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-700">
                                    {{ $layanan->jenis_layanan }}
                                </span>
                            </td>
                            <td class="py-4 px-5 font-semibold text-slate-600">
                                {{ $layanan->satuan }}
                            </td>
                            <td class="py-4 px-5 font-black text-slate-900 text-sm">
                                Rp {{ number_format($layanan->harga, 0, ',', '.') }}
                            </td>
                            <td class="py-4 px-5">
                                <span class="px-3 py-1 rounded-full text-xs font-black {{ $layanan->status === 'tersedia' ? 'bg-emerald-100 text-emerald-950 border border-emerald-300' : 'bg-rose-100 text-rose-950 border border-rose-300' }}">
                                    {{ ucfirst($layanan->status) }}
                                </span>
                            </td>
                            <td class="py-4 px-5 text-center">
                                <form action="{{ route('owner.layanan.update', $layanan->id_layanan) }}" method="POST"
                                      class="inline-flex items-center gap-2">
                                    @csrf
                                    <input type="number" name="harga" value="{{ (int)$layanan->harga }}" min="1000" step="500" required
                                           class="w-28 px-3 py-2 rounded-xl border border-slate-200 font-black text-slate-900 text-xs bg-slate-50 text-right">
                                    <select name="status" class="px-2.5 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-800 bg-slate-50">
                                        <option value="tersedia" {{ $layanan->status === 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                        <option value="tidak_tersedia" {{ $layanan->status === 'tidak_tersedia' ? 'selected' : '' }}>Nonaktif</option>
                                    </select>
                                    <button type="submit"
                                            class="px-4 py-2 bg-slate-900 hover:bg-black active:scale-95 text-white font-black text-xs rounded-xl transition-all">
                                        Simpan
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
