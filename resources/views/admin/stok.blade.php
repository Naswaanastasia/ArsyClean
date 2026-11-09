@extends('layouts.app')

@section('title', 'Manajemen Stok - ArsyClean Admin')
@section('page_title', 'Inventaris Stok Bahan Baku')

@section('content')
<div class="space-y-6">

    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-[#0f2a5c] via-[#163a78] to-[#0a1e42] rounded-3xl p-6 sm:p-8 text-white shadow-xl flex flex-col md:flex-row items-start md:items-center justify-between gap-4 border border-blue-900/40">
        <div>
            <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-white/15 text-sky-200 mb-2 border border-white/20">
                <svg class="w-4 h-4 text-sky-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                Logistik &amp; Gudang
            </div>
            <h1 class="text-2xl sm:text-3xl font-black tracking-tight">Inventaris Stok Bahan Baku</h1>
            <p class="text-xs sm:text-sm text-blue-100 mt-1 max-w-xl font-medium">
                Pantau ketersediaan deterjen cair, pewangi, plastik kemasan, hanger, dan koin mesin cuci agar operasional laundry selalu lancar.
            </p>
        </div>

        <div class="flex items-center gap-3 shrink-0">
            <div class="bg-white/10 px-5 py-3 rounded-2xl border border-white/20 text-center">
                <div class="text-xs text-emerald-300 font-bold uppercase">Stok Aman</div>
                <div class="text-2xl font-black text-white mt-0.5">
                    {{ $stoks->where('status', 'Aman')->count() }}
                </div>
            </div>
            <div class="bg-white/10 px-5 py-3 rounded-2xl border border-white/20 text-center">
                <div class="text-xs text-amber-300 font-bold uppercase">Menipis / Habis</div>
                <div class="text-2xl font-black text-white mt-0.5">
                    {{ $stoks->whereIn('status', ['Menipis', 'Habis'])->count() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Grid Card Bahan Baku -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($stoks as $item)
            @php
                $statusClass = match($item->status) {
                    'Aman'    => 'bg-emerald-100 text-emerald-950 border border-emerald-300 font-black',
                    'Menipis' => 'bg-amber-100 text-amber-950 border border-amber-300 font-black',
                    default   => 'bg-rose-100 text-rose-950 border border-rose-300 font-black',
                };
            @endphp
            <div class="bg-white rounded-3xl p-6 border border-slate-200 hover:border-blue-300 shadow-xs transition-all space-y-4">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <div class="text-xs font-black uppercase text-slate-400 tracking-wider">{{ $item->kategori }}</div>
                        <h3 class="text-lg font-black text-slate-900 mt-0.5">{{ $item->nama_barang }}</h3>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs {{ $statusClass }}">
                        {{ $item->status }}
                    </span>
                </div>

                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 flex items-center justify-between">
                    <div>
                        <div class="text-xs text-slate-500 font-bold">Sisa Stok:</div>
                        <div class="text-2xl font-black text-slate-900 mt-0.5">
                            {{ $item->jumlah }} <span class="text-xs font-bold text-slate-500">{{ $item->satuan }}</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-xs text-slate-400 font-semibold">Batas Minimum:</div>
                        <div class="text-xs font-black text-slate-700 mt-0.5">
                            {{ $item->batas_minimum }} {{ $item->satuan }}
                        </div>
                    </div>
                </div>

                <!-- Form Update Jumlah Stok Cepat -->
                <form action="{{ route('admin.stok.update', $item->id_stok) }}" method="POST"
                      class="pt-2 border-t border-slate-100 flex items-center gap-2">
                    @csrf
                    <input type="number" step="0.1" min="0" name="jumlah" value="{{ $item->jumlah }}" required
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-black text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button type="submit"
                            class="px-5 py-2.5 bg-slate-900 hover:bg-black active:scale-95 text-white font-black text-xs rounded-xl shrink-0 transition-all">
                        Perbarui
                    </button>
                </form>
            </div>
        @endforeach
    </div>

</div>
@endsection
