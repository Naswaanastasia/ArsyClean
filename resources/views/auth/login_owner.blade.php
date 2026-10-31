@extends('layouts.app')

@section('title', 'Login Owner - Laravel ArsyClean')

@section('content')
<div class="min-h-[75vh] flex items-center justify-center p-2">
    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl border border-slate-200 overflow-hidden">
        <!-- Header Visual Owner -->
        <div class="bg-gradient-to-r from-amber-600 to-amber-700 p-6 text-white text-center">
            <div class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-[10px] font-bold bg-white/20 uppercase tracking-wider mb-2 text-amber-100">
                👑 Portal Pemilik Usaha (Owner)
            </div>
            <div class="w-16 h-16 mx-auto rounded-2xl bg-white/10 p-2 backdrop-blur-md mb-2 flex items-center justify-center border border-white/20">
                <img src="/src/assets/images/arsy_clean_logo_1793340486742.jpg" alt="Logo" class="w-full h-full object-contain rounded-xl">
            </div>
            <h2 class="text-xl font-black">ARSY CLEAN OWNER</h2>
            <p class="text-xs text-amber-100">Laporan Omzet Bulanan, Keuangan & Tarif</p>
        </div>

        <div class="p-6">
            <!-- Navigasi Portal Peran -->
            <div class="flex items-center justify-between text-xs mb-6 pb-3 border-b border-slate-100">
                <span class="text-slate-400 font-semibold">Pilih Portal Lain:</span>
                <div class="flex gap-2">
                    <a href="{{ route('login.pelanggan') }}" class="px-2.5 py-1 rounded-lg bg-sky-50 hover:bg-sky-100 text-sky-700 font-bold">
                        👤 Login Pelanggan
                    </a>
                    <a href="{{ route('login.admin') }}" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold">
                        🛡️ Login Admin
                    </a>
                </div>
            </div>

            <h3 class="text-lg font-bold text-slate-800 mb-1">Masuk Akun Owner</h3>
            <p class="text-xs text-slate-500 mb-4">Khusus pemilik usaha ArsyClean Laundry</p>

            <form action="{{ route('login.process', ['role' => 'owner']) }}" method="POST" class="space-y-4 text-xs">
                @csrf
                <div>
                    <label class="block font-semibold text-slate-600 mb-1">Email Owner</label>
                    <input type="email" id="email" name="email" value="{{ old('email', 'owner@arsyclean.com') }}" required placeholder="owner@arsyclean.com" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500">
                </div>

                <div>
                    <label class="block font-semibold text-slate-600 mb-1">Kata Sandi</label>
                    <input type="password" id="password" name="password" value="owner123" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500">
                </div>

                <button type="submit" class="w-full py-3 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded-2xl shadow-lg shadow-amber-600/20 text-xs transition-all">
                    Masuk ke Dashboard Eksekutif Owner &rarr;
                </button>
            </form>

            <!-- Quick Demo SDD -->
            <div class="mt-6 pt-4 border-t border-slate-100 text-xs">
                <span class="text-[11px] text-slate-400 font-semibold block mb-2">Akun Demo Owner (SDD Bab 4):</span>
                <div class="text-[11px]">
                    <button type="button" onclick="fillCred('owner@arsyclean.com', 'owner123')" class="w-full p-2.5 border border-slate-200 hover:border-amber-400 bg-slate-50 rounded-xl text-left">
                        <div class="font-bold text-slate-800">Haji Arsyad (U005) - Owner</div>
                        <div class="text-[10px] text-slate-400 font-mono">owner@arsyclean.com (Password: owner123)</div>
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
