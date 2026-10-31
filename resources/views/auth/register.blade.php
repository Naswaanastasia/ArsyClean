@extends('layouts.app')

@section('title', 'Daftar Akun Pelanggan Baru - Laravel ArsyClean')

@section('content')
<div class="min-h-[75vh] flex items-center justify-center p-2">
    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl border border-slate-200 overflow-hidden">
        <div class="bg-gradient-to-r from-sky-500 to-blue-600 p-6 text-white text-center">
            <h2 class="text-xl font-black">DAFTAR AKUN BARU</h2>
            <p class="text-xs text-sky-100">Bergabung dengan Layanan Laundry ArsyClean</p>
        </div>

        <div class="p-6">
            <p class="text-xs text-slate-500 mb-4">
                Sudah punya akun? <a href="{{ route('login.pelanggan') }}" class="text-sky-600 font-semibold hover:underline">Masuk di sini</a>
            </p>

            <form action="{{ route('register.process') }}" method="POST" class="space-y-3 text-xs">
                @csrf
                <div>
                    <label class="block font-semibold text-slate-600 mb-1">Nama Lengkap</label>
                    <input type="text" name="nama" value="{{ old('nama') }}" required placeholder="Contoh: Rina Kusuma" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>

                <div>
                    <label class="block font-semibold text-slate-600 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="rina@example.com" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>

                <div>
                    <label class="block font-semibold text-slate-600 mb-1">No. WhatsApp</label>
                    <input type="text" name="no_hp" value="{{ old('no_hp') }}" required placeholder="08xxxxxxxxxx" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>

                <div>
                    <label class="block font-semibold text-slate-600 mb-1">Alamat Penjemputan</label>
                    <textarea name="alamat" rows="2" required placeholder="Nama jalan, nomor rumah, RT/RW" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-sky-500">{{ old('alamat') }}</textarea>
                </div>

                <div>
                    <label class="block font-semibold text-slate-600 mb-1">Kata Sandi</label>
                    <input type="password" name="password" required placeholder="Minimal 6 karakter" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>

                <button type="submit" class="w-full py-3 bg-sky-500 hover:bg-sky-600 text-white font-bold rounded-2xl shadow-md text-xs transition-all mt-2">
                    Daftar Sebagai Pelanggan &rarr;
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
