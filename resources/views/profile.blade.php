@extends('layouts.app')

@section('title', 'Profil Saya - ArsyClean')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Notifikasi -->
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-bold flex items-center gap-3 shadow-xs">
            <span class="text-lg">✓</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(isset($errors) && $errors->any())
        <div class="p-4 rounded-2xl bg-red-50 border border-red-200 text-red-800 text-sm font-semibold space-y-1 shadow-xs">
            <div class="font-bold flex items-center gap-2">
                <span>⚠️</span> Mohon perbaiki data berikut:
            </div>
            <ul class="list-disc list-inside text-xs pl-2 space-y-0.5">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Profile Header Card -->
    <div class="bg-gradient-to-r from-slate-800 to-[#0f2a5c] rounded-3xl p-6 sm:p-8 text-white shadow-lg flex flex-col sm:flex-row items-center gap-6">
        <div class="w-20 h-20 rounded-3xl bg-gradient-to-tr from-sky-400 to-blue-600 flex items-center justify-center font-black text-3xl text-white shadow-xl shadow-sky-500/30 shrink-0">
            {{ strtoupper(mb_substr($user->nama, 0, 1)) }}
        </div>
        <div class="text-center sm:text-left flex-1">
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-white/20 text-sky-200 mb-2">
                Peran: {{ strtoupper($user->role) }}
            </div>
            <h1 class="text-2xl sm:text-3xl font-black tracking-tight">{{ $user->nama }}</h1>
            <p class="text-sm text-slate-300 mt-1 flex flex-wrap justify-center sm:justify-start items-center gap-3">
                <span>Kode: <b>{{ $user->kode_user }}</b></span>
                <span>&bull;</span>
                <span>Email: <b>{{ $user->email }}</b></span>
                <span>&bull;</span>
                <span>Status: <b class="text-emerald-400 capitalize">{{ $user->status }}</b></span>
            </p>
        </div>
    </div>

    <!-- Edit Profile Form -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
        <div class="border-b border-slate-100 pb-4 mb-6">
            <h2 class="text-xl font-black text-slate-800">Perbarui Informasi Akun</h2>
            <p class="text-sm text-slate-500 mt-1">
                Lengkapi dan perbarui data profil Anda di bawah ini. Kolom dengan tanda (<span class="text-red-500 font-bold">*</span>) wajib diisi.
            </p>
        </div>

        <form action="{{ route('profile.update') }}" method="POST" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Nama Lengkap -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">
                        Nama Lengkap <span class="text-red-500 font-bold">*</span>
                    </label>
                    <input type="text"
                           name="nama"
                           value="{{ old('nama', $user->nama) }}"
                           required
                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">
                        Alamat Email <span class="text-red-500 font-bold">*</span>
                    </label>
                    <input type="email"
                           name="email"
                           value="{{ old('email', $user->email) }}"
                           required
                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                </div>

                <!-- No WhatsApp -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">
                        Nomor WhatsApp / HP <span class="text-red-500 font-bold">*</span>
                    </label>
                    <input type="tel"
                           inputmode="numeric"
                           pattern="[0-9]+"
                           name="no_hp"
                           value="{{ old('no_hp', $user->no_hp) }}"
                           required
                           placeholder="08xxxxxxxxxx"
                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                    <p class="text-[11px] text-slate-400 mt-1">Harus berupa angka numerik (10-15 digit).</p>
                </div>

                <!-- Kode User (Read Only) -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">
                        Kode Akun Pengguna
                    </label>
                    <input type="text"
                           value="{{ $user->kode_user }}"
                           disabled
                           class="w-full px-4 py-3 bg-slate-100 border border-slate-200 rounded-xl text-sm font-bold text-slate-500 cursor-not-allowed">
                    <p class="text-[11px] text-slate-400 mt-1">Dihasilkan otomatis oleh sistem.</p>
                </div>
            </div>

            <!-- Alamat Lengkap -->
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1.5">
                    Alamat Lengkap Domisili / Penjemputan <span class="text-red-500 font-bold">*</span>
                </label>
                <textarea name="alamat"
                          rows="3"
                          required
                          class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">{{ old('alamat', $user->alamat) }}</textarea>
            </div>

            <!-- Ganti Password -->
            <div class="pt-4 border-t border-slate-100">
                <h3 class="text-base font-bold text-slate-800 mb-1">Ganti Kata Sandi (Opsional)</h3>
                <p class="text-xs text-slate-500 mb-3">Biarkan kosong jika Anda tidak ingin mengubah kata sandi.</p>

                <div class="max-w-md">
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">
                        Kata Sandi Baru
                    </label>
                    <input type="password"
                           name="password"
                           placeholder="Minimal 6 karakter"
                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                </div>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3">
                <a href="{{ url()->previous() }}" class="px-5 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-sm transition-all">
                    Batal
                </a>
                <button type="submit" class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm shadow-md shadow-blue-600/30 transition-all">
                    Simpan Perubahan Profil
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
