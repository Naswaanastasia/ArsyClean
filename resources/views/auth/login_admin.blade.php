@extends('layouts.app')

@section('title', 'Login Admin Operasional - Laravel ArsyClean')

@section('content')
<div class="min-h-[75vh] flex items-center justify-center p-2">
    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl border border-slate-200 overflow-hidden">
        <!-- Header Visual Admin -->
        <div class="bg-gradient-to-r from-slate-800 to-slate-900 p-6 text-white text-center">
            <div class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-[10px] font-bold bg-white/20 uppercase tracking-wider mb-2 text-slate-200">
                🛡️ Portal Staf Operasional & Kasir
            </div>
            <div class="w-16 h-16 mx-auto rounded-2xl bg-white/10 p-2 backdrop-blur-md mb-2 flex items-center justify-center border border-white/10">
                <img src="/src/assets/images/arsy_clean_logo_1793340486742.jpg" alt="Logo" class="w-full h-full object-contain rounded-xl">
            </div>
            <h2 class="text-xl font-black">ARSY CLEAN ADMIN</h2>
            <p class="text-xs text-slate-300">Pengelolaan Antrean Cucian, Stok & Kasir</p>
        </div>

        <div class="p-6">
            <!-- Navigasi Portal Peran -->
            <div class="flex items-center justify-between text-xs mb-6 pb-3 border-b border-slate-100">
                <span class="text-slate-400 font-semibold">Pilih Portal Lain:</span>
                <div class="flex gap-2">
                    <a href="{{ route('login.pelanggan') }}" class="px-2.5 py-1 rounded-lg bg-sky-50 hover:bg-sky-100 text-sky-700 font-bold">
                        👤 Login Pelanggan
                    </a>
                    <a href="{{ route('login.owner') }}" class="px-2.5 py-1 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-800 font-bold">
                        👑 Login Owner
                    </a>
                </div>
            </div>

            <h3 class="text-lg font-bold text-slate-800 mb-1">Masuk Akun Admin</h3>
            <p class="text-xs text-slate-500 mb-4">Khusus staf operasional laundry & verifikasi kasir</p>

            <form action="{{ route('login.process', ['role' => 'admin']) }}" method="POST" class="space-y-4 text-xs">
                @csrf
                <div>
                    <label class="block font-semibold text-slate-600 mb-1">Email Admin</label>
                    <input type="email" id="email" name="email" value="{{ old('email', 'admin@arsyclean.com') }}" required placeholder="admin@arsyclean.com" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-800">
                </div>

                <div>
                    <label class="block font-semibold text-slate-600 mb-1">Kata Sandi</label>
                    <input type="password" id="password" name="password" value="admin123" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-800">
                </div>

                <button type="submit" class="w-full py-3 bg-slate-800 hover:bg-slate-900 text-white font-bold rounded-2xl shadow-lg shadow-slate-900/20 text-xs transition-all">
                    Masuk ke Panel Operasional Admin &rarr;
                </button>
            </form>

            <!-- Quick Demo SDD -->
            <div class="mt-6 pt-4 border-t border-slate-100 text-xs">
                <span class="text-[11px] text-slate-400 font-semibold block mb-2">Akun Demo Admin (SDD Bab 4):</span>
                <div class="grid grid-cols-2 gap-2 text-[11px]">
                    <button type="button" onclick="fillCred('admin@arsyclean.com', 'admin123')" class="p-2 border border-slate-200 hover:border-slate-800 bg-slate-50 rounded-xl text-left">
                        <div class="font-bold text-slate-800">Sulistiawati (U004)</div>
                        <div class="text-[10px] text-slate-400 font-mono">admin@arsyclean.com</div>
                    </button>
                    <button type="button" onclick="fillCred('budi_admin@arsyclean.com', 'admin123')" class="p-2 border border-slate-200 hover:border-slate-800 bg-slate-50 rounded-xl text-left">
                        <div class="font-bold text-slate-800">Budi Santoso (U003)</div>
                        <div class="text-[10px] text-slate-400 font-mono">budi_admin@arsyclean.com</div>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function fillCred(email, pass) {
        document.getElementById('email').value = email;
        document.getElementById('password').value = pass;
    }
</script>
@endsection
