@extends('layouts.app')

@section('title', 'Pengiriman Kurir - ArsyClean Admin')
@section('page_title', 'Jadwal & Pengiriman Kurir')

@section('content')
<div class="space-y-7">

    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-orange-950 via-[#0f2a5c] to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-orange-400 text-orange-950 mb-2">
                🛵 Logistik &amp; Antar-Jemput
            </div>
            <h1 class="text-2xl sm:text-3xl font-black tracking-tight">Manajemen Pengiriman Kurir</h1>
            <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl">
                Jadwalkan kurir untuk pesanan antar-jemput yang siap dikirim, buat nomor resi pengantaran, dan pantau status kurir di jalan.
            </p>
        </div>

        <button type="button" onclick="document.getElementById('formKurirCard').classList.toggle('hidden')"
                class="px-5 py-3 rounded-2xl bg-orange-500 hover:bg-orange-600 text-white font-extrabold text-xs shadow-md shadow-orange-500/30 transition-all flex items-center gap-2 shrink-0">
            <span>+ Jadwalkan Kurir Baru</span>
        </button>
    </div>

    <!-- Form Jadwal Kurir Baru (Bisa Ditoggle) -->
    <div id="formKurirCard" class="{{ $pesananSiapKirim->isNotEmpty() ? '' : 'hidden' }} bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-5">
        <div class="border-b border-slate-100 pb-3">
            <h3 class="text-base font-black text-slate-900">Formulir Penjadwalan Kurir</h3>
            <p class="text-xs text-slate-500">Khusus pesanan antar jemput yang telah selesai atau siap dikirim.</p>
        </div>

        @if($pesananSiapKirim->isEmpty())
            <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 text-xs font-semibold">
                Saat ini belum ada pesanan antar-jemput yang membutuhkan kurir baru.
            </div>
        @else
            <form action="{{ route('admin.pengiriman.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        Pilih Pesanan Antar Jemput <span class="text-rose-500 font-bold">*</span>
                    </label>
                    <select name="id_pesanan" required
                            class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-xs font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-orange-500 bg-slate-50">
                        <option value="">-- Pilih Pesanan --</option>
                        @foreach($pesananSiapKirim as $siap)
                            <option value="{{ $siap->id_pesanan }}">
                                #{{ $siap->kode_pesanan }} - {{ $siap->user->nama ?? 'Pelanggan' }} ({{ $siap->alamat_penjemputan }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Nama Petugas Kurir <span class="text-rose-500 font-bold">*</span>
                        </label>
                        <input type="text" name="nama_kurir" required placeholder="Contoh: Budi Santoso"
                               class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 text-xs font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            No. WhatsApp Kurir <span class="text-rose-500 font-bold">*</span>
                        </label>
                        <input type="tel" name="no_hp_kurir" required placeholder="08123456789"
                               class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 text-xs font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Kendaraan / Plat Nomor <span class="text-rose-500 font-bold">*</span>
                        </label>
                        <input type="text" name="kendaraan" required placeholder="Contoh: Honda Beat (B 1234 XYZ)"
                               class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 text-xs font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        Alamat Pengantaran Tujuan <span class="text-rose-500 font-bold">*</span>
                    </label>
                    <textarea name="alamat_tujuan" rows="2" required placeholder="Masukkan alamat lengkap pengantaran..."
                              class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 text-xs font-medium text-slate-900 focus:outline-none focus:ring-2 focus:ring-orange-500"></textarea>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit"
                            class="px-6 py-2.5 bg-orange-600 hover:bg-orange-700 active:scale-95 text-white font-extrabold text-xs rounded-xl shadow-md transition-all">
                        Simpan &amp; Jadwalkan Kurir
                    </button>
                </div>
            </form>
        @endif
    </div>

    <!-- Tabel Pengiriman Aktif -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-black uppercase text-slate-500 tracking-wider">
                        <th class="py-4 px-5">Kode / Resi</th>
                        <th class="py-4 px-5">Pesanan</th>
                        <th class="py-4 px-5">Kurir &amp; Kendaraan</th>
                        <th class="py-4 px-5">Alamat Tujuan</th>
                        <th class="py-4 px-5">Status Pengiriman</th>
                        <th class="py-4 px-5 text-center">Update Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($pengirimans as $kirim)
                        @php
                            $badgeKirim = match($kirim->status_pengiriman) {
                                'Dijadwalkan'     => 'bg-amber-100 text-amber-900 border-amber-300',
                                'Dalam Perjalanan' => 'bg-blue-100 text-blue-900 border-blue-300',
                                'Terkirim'        => 'bg-emerald-100 text-emerald-900 border-emerald-300',
                                default           => 'bg-rose-100 text-rose-900 border-rose-300',
                            };
                        @endphp
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-4 px-5 font-mono">
                                <div class="font-black text-slate-900 text-sm">#{{ $kirim->kode_pengiriman }}</div>
                                <div class="text-[11px] text-slate-400 font-semibold">{{ $kirim->no_resi }}</div>
                            </td>
                            <td class="py-4 px-5">
                                <div class="font-bold text-blue-800">#{{ $kirim->pesanan->kode_pesanan ?? '-' }}</div>
                                <div class="text-[11px] text-slate-500">{{ $kirim->pesanan->user->nama ?? '-' }}</div>
                            </td>
                            <td class="py-4 px-5 font-bold text-slate-800">
                                <div>{{ $kirim->nama_kurir }}</div>
                                <div class="text-[11px] text-slate-400 font-normal">📞 {{ $kirim->no_hp_kurir }} &bull; {{ $kirim->kendaraan }}</div>
                            </td>
                            <td class="py-4 px-5 text-slate-600 max-w-xs truncate">
                                {{ $kirim->alamat_tujuan }}
                            </td>
                            <td class="py-4 px-5">
                                <span class="px-3 py-1 rounded-full text-[11px] font-black border {{ $badgeKirim }}">
                                    {{ $kirim->status_pengiriman }}
                                </span>
                            </td>
                            <td class="py-4 px-5 text-center">
                                @if($kirim->status_pengiriman !== 'Terkirim')
                                    <form action="{{ route('admin.pengiriman.status', $kirim->id_pengiriman) }}" method="POST"
                                          class="inline-flex items-center gap-1.5">
                                        @csrf
                                        <select name="status_pengiriman" required
                                                class="px-2.5 py-1.5 rounded-lg border border-slate-200 text-[11px] font-bold text-slate-800 bg-slate-50">
                                            <option value="Dijadwalkan" {{ $kirim->status_pengiriman === 'Dijadwalkan' ? 'selected' : '' }}>Dijadwalkan</option>
                                            <option value="Dalam Perjalanan" {{ $kirim->status_pengiriman === 'Dalam Perjalanan' ? 'selected' : '' }}>Dalam Perjalanan</option>
                                            <option value="Terkirim" {{ $kirim->status_pengiriman === 'Terkirim' ? 'selected' : '' }}>Terkirim</option>
                                        </select>
                                        <button type="submit"
                                                class="px-3 py-1.5 bg-slate-900 hover:bg-black text-white font-bold text-[11px] rounded-lg">
                                            Ubah
                                        </button>
                                    </form>
                                @else
                                    <span class="text-emerald-700 font-black text-xs">✓ Selesai Terkirim</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center text-slate-400 font-semibold">
                                Belum ada riwayat pengiriman kurir.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
