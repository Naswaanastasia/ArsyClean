@extends('layouts.app')

@section('title', 'Login Pelanggan - Laravel ArsyClean')

@section('content')
<div class="min-h-[75vh] flex items-center justify-center p-2">
    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl border border-slate-200 overflow-hidden">
        <!-- Header Visual Pelanggan -->
        <div class="bg-gradient-to-r from-sky-500 to-blue-600 p-6 text-white text-center">
            <div class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-[10px] font-bold bg-white/20 uppercase tracking-wider mb-2">
                👤 Portal Khusus Pelanggan
            </div>
            <div class="w-16 h-16 mx-auto rounded-2xl bg-white/20 p-2 backdrop-blur-md mb-2 flex items-center justify-center">
                <img src="/src/assets/images/arsy_clean_logo_1793340486742.jpg" alt="Logo" class="w-full h-full object-contain rounded-xl">
            </div>
            <h2 class="text-xl font-black">ARSY CLEAN</h2>
            <p class="text-xs text-sky-100">Pemesanan, Pelacakan Status & Pembayaran</p>
        </div>

        <div class="p-6">
            <!-- Navigasi Portal Peran -->
            <div class="flex items-center justify-between text-xs mb-6 pb-3 border-b border-slate-100">
                <span class="text-slate-400 font-semibold">Pilih Portal Lain:</span>
                <div class="flex gap-2">
                    <a href="{{ route('login.admin') }}" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold">
                        🛡️ Login Admin
                    </a>
                    <a href="{{ route('login.owner') }}" class="px-2.5 py-1 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-800 font-bold">
                        👑 Login Owner
                    </a>
                </div>
            </div>

            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-slate-800">Masuk Akun Pelanggan</h3>
                <a href="{{ route('register') }}" class="text-xs text-sky-600 font-semibold hover:underline">
                    Daftar Baru
                </a>
            </div>

            <form action="{{ route('login.process', ['role' => 'pelanggan']) }}" method="POST" class="space-y-4 text-xs">
                @csrf
                <div>
                    <label class="block font-semibold text-slate-600 mb-1">Email Pelanggan</label>
                    <input type="email" id="email" name="email" value="{{ old('email', 'andi@arsyclean.com') }}" required placeholder="andi@arsyclean.com" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>

                <div>
                    <label class="block font-semibold text-slate-600 mb-1">Kata Sandi</label>
                    <input type="password" id="password" name="password" value="password123" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>

                <button type="submit" class="w-full py-3 bg-sky-500 hover:bg-sky-600 text-white font-bold rounded-2xl shadow-lg shadow-sky-500/20 text-xs transition-all">
                    Masuk ke Dashboard Pelanggan &rarr;
                </button>
            </form>

            <!-- Quick Demo SDD -->
            <div class="mt-6 pt-4 border-t border-slate-100 text-xs">
                <span class="text-[11px] text-slate-400 font-semibold block mb-2">Akun Demo Pelanggan (SDD Bab 4):</span>
                <div class="grid grid-cols-2 gap-2 text-[11px]">
                    <button type="button" onclick="fillCred('andi@arsyclean.com', 'password123')" class="p-2 border border-slate-200 hover:border-sky-400 bg-slate-50 rounded-xl text-left">
                        <div class="font-bold text-slate-800">Andi Pratama</div>
                        <div class="text-[10px] text-slate-400 font-mono">andi@arsyclean.com</div>
                    </button>
                    <button type="button" onclick="fillCred('siti@arsyclean.com', 'password123')" class="p-2 border border-slate-200 hover:border-sky-400 bg-slate-50 rounded-xl text-left">
                        <div class="font-bold text-slate-800">Siti Aisyah</div>
                        <div class="text-[10px] text-slate-400 font-mono">siti@arsyclean.com</div>
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
