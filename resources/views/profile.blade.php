
@extends(Auth::user()->role === 'pelanggan' ? 'layouts.pelanggan' : 'layouts.app')

@section('title', 'Profil Saya - ArsyClean')

@section('content')

<div class="w-full max-w-4xl mx-auto space-y-6">

    {{-- HEADER PROFIL --}}
    <div class="relative overflow-hidden rounded-3xl p-6 sm:p-8 text-white shadow-lg"
         style="background: linear-gradient(120deg, #173b7d 0%, #2563a6 55%, #0f2a5c 100%);">

        <div class="absolute -right-10 -top-10 w-48 h-48 rounded-full bg-white/5"></div>
        <div class="absolute -right-5 -bottom-20 w-56 h-56 rounded-full bg-white/5"></div>

        <div class="relative flex flex-col sm:flex-row items-center gap-5">

            <div class="w-20 h-20 rounded-2xl bg-gradient-to-tr from-sky-400 to-blue-600
                        flex items-center justify-center font-black text-3xl text-white
                        shadow-xl border border-white/20 shrink-0">
                {{ strtoupper(mb_substr($user->nama, 0, 1)) }}
            </div>

            <div class="flex-1 text-center sm:text-left min-w-0">

                <span class="inline-flex items-center px-3 py-1 rounded-full
                             bg-white/15 border border-white/20 text-sky-100
                             text-[10px] font-extrabold uppercase tracking-wider mb-2">
                    {{ $user->role === 'pelanggan' ? 'Akun Pelanggan' : ($user->role === 'admin' ? 'Petugas Admin' : 'Owner Eksekutif') }}
                </span>

                <h1 class="text-2xl sm:text-3xl font-black tracking-tight">
                    {{ $user->nama }}
                </h1>

                <p class="text-sm text-blue-100 mt-2 break-words">
                    {{ $user->email }}
                </p>

                <div class="flex flex-wrap justify-center sm:justify-start gap-3 mt-3 text-xs text-blue-100">
                    <span>Kode: <strong>{{ $user->kode_user }}</strong></span>
                    <span class="hidden sm:inline">•</span>
                    <span>Status:
                        <strong class="text-emerald-300 capitalize">{{ $user->status }}</strong>
                    </span>
                </div>

            </div>
        </div>
    </div>


    {{-- FORM INFORMASI AKUN --}}
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">

        <div class="px-6 sm:px-8 py-6 border-b border-slate-100">
            <div class="flex items-center gap-3">

                <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600
                            flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              stroke-width="2"
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>

                <div>
                    <h2 class="text-lg font-black text-slate-800">
                        Perbarui Informasi Akun
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">
                        Kelola informasi pribadi dan keamanan akun Anda.
                    </p>
                </div>

            </div>
        </div>


        <form action="{{ route('profile.update') }}" method="POST"
              class="p-6 sm:p-8 space-y-6">

            @csrf

            {{-- INFORMASI DASAR --}}
            <div>
                <h3 class="text-sm font-extrabold text-slate-700 mb-4">
                    Informasi Pribadi
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    {{-- Nama --}}
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>

                        <input type="text"
                               name="nama"
                               value="{{ old('nama', $user->nama) }}"
                               required
                               class="w-full px-4 py-3 bg-slate-50 border border-slate-200
                                      rounded-xl text-sm font-medium outline-none
                                      focus:bg-white focus:border-blue-500
                                      focus:ring-2 focus:ring-blue-100 transition">
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">
                            Alamat Email <span class="text-red-500">*</span>
                        </label>

                        <input type="email"
                               name="email"
                               value="{{ old('email', $user->email) }}"
                               required
                               class="w-full px-4 py-3 bg-slate-50 border border-slate-200
                                      rounded-xl text-sm font-medium outline-none
                                      focus:bg-white focus:border-blue-500
                                      focus:ring-2 focus:ring-blue-100 transition">
                    </div>

                    {{-- Nomor HP --}}
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">
                            Nomor WhatsApp / HP <span class="text-red-500">*</span>
                        </label>

                        <input type="tel"
                               inputmode="numeric"
                               pattern="[0-9]+"
                               name="no_hp"
                               value="{{ old('no_hp', $user->no_hp) }}"
                               required
                               placeholder="08xxxxxxxxxx"
                               class="w-full px-4 py-3 bg-slate-50 border border-slate-200
                                      rounded-xl text-sm font-medium outline-none
                                      focus:bg-white focus:border-blue-500
                                      focus:ring-2 focus:ring-blue-100 transition">

                        <p class="text-[11px] text-slate-400 mt-2">
                            Gunakan angka saja (10–15 digit).
                        </p>
                    </div>

                    {{-- Kode User --}}
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">
                            Kode Akun Pengguna
                        </label>

                        <input type="text"
                               value="{{ $user->kode_user }}"
                               disabled
                               class="w-full px-4 py-3 bg-slate-100 border border-slate-200
                                      rounded-xl text-sm font-bold text-slate-500
                                      cursor-not-allowed">

                        <p class="text-[11px] text-slate-400 mt-2">
                            Kode akun dibuat otomatis oleh sistem.
                        </p>
                    </div>

                </div>
            </div>


            {{-- ALAMAT --}}
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">
                    Alamat Lengkap Domisili / Penjemputan
                    <span class="text-red-500">*</span>
                </label>

                <textarea name="alamat"
                          rows="3"
                          required
                          placeholder="Masukkan alamat lengkap Anda..."
                          class="w-full px-4 py-3 bg-slate-50 border border-slate-200
                                 rounded-xl text-sm font-medium outline-none resize-y
                                 focus:bg-white focus:border-blue-500
                                 focus:ring-2 focus:ring-blue-100 transition">{{ old('alamat', $user->alamat) }}</textarea>
            </div>


            {{-- GANTI PASSWORD --}}
            <div class="pt-6 border-t border-slate-100">

                <div class="flex items-center gap-3 mb-4">

                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600
                                flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>

                    <div>
                        <h3 class="text-sm font-extrabold text-slate-800">
                            Keamanan Akun
                        </h3>
                        <p class="text-xs text-slate-500 mt-1">
                            Ganti kata sandi jika diperlukan.
                        </p>
                    </div>

                </div>

                <div class="max-w-md">
                    <label class="block text-sm font-bold text-slate-700 mb-2">
                        Kata Sandi Baru
                    </label>

                    <input type="password"
                           name="password"
                           placeholder="Minimal 6 karakter"
                           autocomplete="new-password"
                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200
                                  rounded-xl text-sm font-medium outline-none
                                  focus:bg-white focus:border-blue-500
                                  focus:ring-2 focus:ring-blue-100 transition">

                    <p class="text-[11px] text-slate-400 mt-2">
                        Kosongkan jika tidak ingin mengubah kata sandi.
                    </p>
                </div>

            </div>


            {{-- TOMBOL AKSI --}}
            <div class="pt-5 border-t border-slate-100 flex flex-col-reverse sm:flex-row
                        items-stretch sm:items-center justify-end gap-3">

                <a href="{{ url()->previous() }}"
                   class="px-6 py-3 rounded-xl bg-slate-100 hover:bg-slate-200
                          text-slate-700 font-bold text-sm text-center transition">
                    Batal
                </a>

                <button type="submit"
                        class="px-7 py-3 rounded-xl bg-blue-600 hover:bg-blue-700
                               text-white font-extrabold text-sm shadow-lg
                               shadow-blue-600/20 transition">
                    Simpan Perubahan Profil
                </button>

            </div>

        </form>
    </div>

</div>

@endsection
