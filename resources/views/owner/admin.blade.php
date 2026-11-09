@extends('layouts.app')

@section('title', 'Kelola Akun Admin - Owner ArsyClean')

@section('content')
<div class="space-y-6">

    <!-- Notifikasi -->
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-bold flex items-center gap-3 shadow-xs">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 rounded-2xl bg-red-50 border border-red-200 text-red-800 text-sm font-bold flex items-center gap-3 shadow-xs">
            <svg class="w-5 h-5 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if(isset($errors) && $errors->any())
        <div class="p-4 rounded-2xl bg-red-50 border border-red-200 text-red-800 text-sm font-semibold space-y-1 shadow-xs">
            <div class="font-bold flex items-center gap-2">
                <svg class="w-5 h-5 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <span>Terdapat kesalahan input data admin:</span>
            </div>
            <ul class="list-disc list-inside text-xs pl-2 space-y-0.5">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Header Panel -->
    <div class="bg-gradient-to-r from-[#0f2a5c] via-[#163a78] to-[#0a1e42] rounded-3xl p-6 sm:p-8 text-white shadow-xl flex flex-col md:flex-row items-start md:items-center justify-between gap-5 border border-blue-900/40">
        <div>
            <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-white/15 text-sky-200 mb-2 border border-white/20">
                <svg class="w-4 h-4 text-sky-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                Manajemen Karyawan &amp; Operasional
            </div>
            <h1 class="text-2xl sm:text-3xl font-black">Pengelolaan Akun Admin Laundry</h1>
            <p class="text-sm text-blue-100 max-w-xl mt-1 leading-relaxed font-medium">
                Kelola hak akses dan akun staf operasional kasir laundry. Halaman ini dipisahkan dari dashboard utama untuk menjaga kerapian alur kerja.
            </p>
        </div>
        <div class="bg-white/10 p-5 rounded-2xl border border-white/20 text-right min-w-48 shrink-0">
            <div class="text-xs text-sky-200 uppercase font-semibold">Total Staf Admin</div>
            <div class="text-3xl font-black text-white mt-0.5">{{ $admins->count() }} Orang</div>
            <div class="text-[11px] text-sky-200 mt-1">Status Operasional Aktif</div>
        </div>
    </div>

    <!-- Form Tambah Admin Baru -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
        <div class="border-b border-slate-100 pb-4 mb-6">
            <h2 class="text-lg sm:text-xl font-black text-slate-800 flex items-center gap-2">
                <span class="w-8 h-8 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center text-sm font-black">+</span>
                Tambah Akun Admin Baru
            </h2>
            <p class="text-sm text-slate-500 mt-1">
                Lengkapi formulir di bawah ini untuk membuat akun staf admin. Kolom dengan tanda (<span class="text-red-500 font-bold">*</span>) wajib diisi.
            </p>
        </div>

        <form action="{{ route('owner.admin.store') }}" method="POST" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                <!-- Nama Admin -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">
                        Nama Lengkap Admin <span class="text-red-500 font-bold">*</span>
                    </label>
                    <input type="text"
                           name="nama"
                           value="{{ old('nama') }}"
                           required
                           placeholder="Contoh: Budi Santoso"
                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                </div>

                <!-- Email Admin -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">
                        Alamat Email <span class="text-red-500 font-bold">*</span>
                    </label>
                    <input type="email"
                           name="email"
                           value="{{ old('email') }}"
                           required
                           placeholder="admin@arsyclean.com"
                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                </div>

                <!-- No WhatsApp -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">
                        No. WhatsApp / HP <span class="text-red-500 font-bold">*</span>
                    </label>
                    <input type="tel"
                           inputmode="numeric"
                           pattern="[0-9]+"
                           name="no_hp"
                           value="{{ old('no_hp') }}"
                           required
                           placeholder="08xxxxxxxxxx"
                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                    <p class="text-[11px] text-slate-400 mt-1">Hanya angka (10-15 digit).</p>
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">
                        Kata Sandi Awal <span class="text-red-500 font-bold">*</span>
                    </label>
                    <input type="password"
                           name="password"
                           required
                           placeholder="Minimal 6 karakter"
                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                </div>

                <!-- Alamat Domisili -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">
                        Alamat Domisili Staf <span class="text-red-500 font-bold">*</span>
                    </label>
                    <input type="text"
                           name="alamat"
                           value="{{ old('alamat') }}"
                           required
                           placeholder="Alamat jalan, kelurahan, kota domisili staf admin"
                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                </div>
            </div>

            <div class="pt-2 flex justify-end">
                <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl shadow-md shadow-blue-600/30 transition-all flex items-center gap-2">
                    <span>+</span> Buat Akun Admin Baru
                </button>
            </div>
        </form>
    </div>

    <!-- Tabel Daftar Admin -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
            <div>
                <h2 class="text-xl font-black text-slate-800">Daftar Akun Admin Terdaftar</h2>
                <p class="text-sm text-slate-500 mt-0.5">Daftar staf yang memiliki hak akses ke panel operasional admin.</p>
            </div>
            <span class="px-3 py-1 bg-slate-100 rounded-xl text-xs font-bold text-slate-600 self-start sm:self-auto">
                Total: {{ $admins->count() }} Admin
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-500 uppercase text-xs border-b border-slate-200">
                    <tr>
                        <th class="p-3.5">Kode User</th>
                        <th class="p-3.5">Nama Admin</th>
                        <th class="p-3.5">Kontak</th>
                        <th class="p-3.5">Alamat</th>
                        <th class="p-3.5">Status</th>
                        <th class="p-3.5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($admins as $admin)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="p-3.5 font-mono font-bold text-slate-800">
                            {{ $admin->kode_user ?? ('ADM-' . $admin->id_user) }}
                        </td>
                        <td class="p-3.5">
                            <div class="font-bold text-slate-800">{{ $admin->nama }}</div>
                            <div class="text-xs text-slate-400">{{ $admin->email }}</div>
                        </td>
                        <td class="p-3.5">
                            <span class="inline-flex items-center gap-1 font-semibold text-slate-700">
                                {{ $admin->no_hp ?? '-' }}
                            </span>
                        </td>
                        <td class="p-3.5 text-xs text-slate-600 max-w-xs truncate" title="{{ $admin->alamat }}">
                            {{ $admin->alamat ?? '-' }}
                        </td>
                        <td class="p-3.5">
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                Aktif
                            </span>
                        </td>
                        <td class="p-3.5 text-center">
                            <form action="{{ route('owner.admin.destroy', $admin->id_user) }}"
                                  method="POST"
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun admin {{ $admin->nama }}? Tindakan ini tidak dapat dibatalkan.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="px-3.5 py-1.5 bg-red-50 hover:bg-red-600 text-red-600 hover:text-white border border-red-200 rounded-xl text-xs font-bold transition-all">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-slate-400">
                            Belum ada akun admin yang dibuat. Silakan tambahkan admin baru pada form di atas.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
