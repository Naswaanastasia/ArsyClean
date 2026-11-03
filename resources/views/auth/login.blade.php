@extends('layouts.app')

@section('title', 'Login - ArsyClean Laundry')

@section('content')
<div class="min-h-[85vh] flex items-center justify-center p-4 bg-slate-50">

    <div class="w-full max-w-4xl bg-white rounded-3xl shadow-xl border border-slate-200 overflow-hidden flex flex-col md:flex-row">

        <!-- BAGIAN KIRI: BRANDING -->
        <div class="md:w-5/12 p-8 text-white bg-gradient-to-br from-rose-500 via-sky-600 to-indigo-700 flex flex-col justify-between">

            <div>
                <div class="w-16 h-16 rounded-2xl bg-white/20 p-2 mb-5 flex items-center justify-center">
                    <img src="/src/assets/images/arsy_clean_logo_1793340486742.jpg"
                         alt="Logo ArsyClean"
                         class="w-full h-full object-contain rounded-xl">
                </div>

                <h1 class="text-3xl font-black tracking-wide">ARSY CLEAN</h1>

                <p class="text-sm text-white/80 mt-2">
                    Sistem Pengelolaan Laundry Coin & Reguler
                </p>

                <p class="text-sm text-white/90 leading-relaxed mt-6">
                    Selamat datang di ArsyClean. Masuk ke akun Anda untuk menikmati layanan laundry dengan mudah, praktis, dan nyaman.
                </p>
            </div>

            <div class="mt-8 rounded-2xl overflow-hidden border-2 border-white/20 bg-white/10 p-2">
                <img src="/src/assets/images/arsy_clean_mascot_1793340487175.jpg"
                     alt="Maskot ArsyClean"
                     class="w-full h-40 object-cover rounded-xl">
            </div>

            <p class="text-xs text-white/70 mt-5">
                © {{ date('Y') }} ArsyClean Laundry. All rights reserved.
            </p>
        </div>

        <!-- BAGIAN KANAN: FORM LOGIN -->
        <div class="flex-1 p-6 sm:p-10 flex flex-col justify-center">

            <div class="mb-8">
                <span class="text-xs font-bold text-sky-600 uppercase tracking-widest">
                    Selamat Datang
                </span>

                <h2 class="text-3xl font-black text-slate-800 mt-2">
                    Login Akun
                </h2>

                <p class="text-sm text-slate-500 mt-2">
                    Masukkan email dan kata sandi untuk melanjutkan.
                </p>
            </div>

            <form id="loginForm"
                  action="{{ route('login.process') }}"
                  method="POST"
                  novalidate
                  class="space-y-5">

                @csrf

                <!-- EMAIL -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">
                        Alamat Email
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="contoh@email.com"
                        autocomplete="email"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-sky-500"
                    >
                </div>

                <!-- PASSWORD -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">
                        Kata Sandi
                    </label>

                    <div class="relative">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            maxlength="8"
                            maxlength="10"
                            placeholder="Masukkan kata sandi"
                            autocomplete="current-password"
                            class="w-full px-4 py-3 pr-12 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-sky-500"
                        >

                        <button
                            type="button"
                            id="togglePassword"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-sky-600"
                            aria-label="Tampilkan password"
                        >
                            <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg"
                                 width="20" height="20" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- TOMBOL LOGIN -->
                <button
                    type="submit"
                    class="w-full py-3.5 bg-sky-500 hover:bg-sky-600 text-white font-bold rounded-xl shadow-md transition-all">
                    Masuk ke Akun →
                </button>

            </form>

            <!-- LINK REGISTRASI -->
            <div class="text-center mt-7 pt-6 border-t border-slate-100">
                <p class="text-sm text-slate-500">
                    Belum memiliki akun?
                    <a href="{{ route('register') }}"
                       class="text-sky-600 font-bold hover:underline">
                        Daftar Sekarang
                    </a>
                </p>
            </div>

        </div>
    </div>
</div>

<!-- POP-UP NOTIFIKASI -->
<div id="alertModal"
     class="fixed inset-0 z-[9999] hidden items-center justify-center bg-slate-900/50 p-4">

    <div class="bg-white w-full max-w-sm rounded-2xl shadow-2xl p-7 text-center">

        <div id="alertIcon"
             class="w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center text-3xl">
        </div>

        <h3 id="alertTitle" class="text-xl font-bold text-slate-800 mb-2"></h3>

        <p id="alertMessage" class="text-sm text-slate-500 leading-relaxed"></p>

        <button type="button"
                id="closeAlert"
                class="mt-6 w-full py-3 rounded-xl bg-sky-500 hover:bg-sky-600 text-white font-bold transition">
            OK
        </button>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('loginForm');
    const email = document.getElementById('email');
    const password = document.getElementById('password');

    const modal = document.getElementById('alertModal');
    const icon = document.getElementById('alertIcon');
    const title = document.getElementById('alertTitle');
    const message = document.getElementById('alertMessage');
    const closeButton = document.getElementById('closeAlert');

    // FUNGSI MENAMPILKAN POP-UP
    function showAlert(type, heading, text) {

        title.textContent = heading;
        message.textContent = text;

        if (type === 'success') {
            icon.textContent = '✓';
            icon.className = 'w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center text-3xl bg-green-100 text-green-600';
            closeButton.className = 'mt-6 w-full py-3 rounded-xl bg-green-500 hover:bg-green-600 text-white font-bold transition';
        } else {
            icon.textContent = '!';
            icon.className = 'w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center text-3xl bg-red-100 text-red-600';
            closeButton.className = 'mt-6 w-full py-3 rounded-xl bg-red-500 hover:bg-red-600 text-white font-bold transition';
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    // MENUTUP POP-UP
    function hideAlert() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    closeButton.addEventListener('click', hideAlert);

    // TUTUP POP-UP JIKA AREA LUAR DIKLIK
    modal.addEventListener('click', function (event) {
        if (event.target === modal) {
            hideAlert();
        }
    });

    // TAMPILKAN / SEMBUNYIKAN PASSWORD
    document.getElementById('togglePassword').addEventListener('click', function () {

        const eyeIcon = document.getElementById('eyeIcon');

        if (password.type === 'password') {
            password.type = 'text';
            eyeIcon.innerHTML = `
                <path d="M3 3l18 18"/>
                <path d="M10.6 10.6a2 2 0 002.8 2.8"/>
                <path d="M9.9 5.2A10.7 10.7 0 0112 5c6.5 0 10 7 10 7a15.5 15.5 0 01-4 4.8"/>
                <path d="M6.6 6.6C3.7 8.3 2 12 2 12s3.5 7 10 7c1.2 0 2.3-.2 3.3-.6"/>
            `;
        } else {
            password.type = 'password';
            eyeIcon.innerHTML = `
                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/>
                <circle cx="12" cy="12" r="3"/>
            `;
        }
    });

    // VALIDASI SEBELUM FORM DIKIRIM
    form.addEventListener('submit', function (event) {

        const emailValue = email.value.trim();
        const passwordValue = password.value;

        if (emailValue === '') {
            event.preventDefault();
            showAlert('error', 'Email Belum Diisi', 'Silakan masukkan alamat email kamu.');
            email.focus();
            return;
        }

        if (!email.checkValidity()) {
            event.preventDefault();
            showAlert('error', 'Format Email Salah', 'Masukkan alamat email yang benar, contohnya nama@email.com.');
            email.focus();
            return;
        }

        if (passwordValue.trim() === '') {
            event.preventDefault();
            showAlert('error', 'Password Belum Diisi', 'Silakan masukkan kata sandi kamu.');
            password.focus();
            return;
        }
    });

    // MENAMPILKAN ERROR DARI LARAVEL
const validationErrors = @json($errors->all());

if (validationErrors.length > 0) {
    const errorMessage = validationErrors[0];

    if (errorMessage.toLowerCase().includes('email')) {
        showAlert('error', 'Email Tidak Sesuai', errorMessage);
    } else if (errorMessage.toLowerCase().includes('password')) {
        showAlert('error', 'Password Tidak Sesuai', errorMessage);
    } else {
        showAlert('error', 'Login Gagal', errorMessage);
    }
}

// PESAN SUKSES DARI SESSION
@if (session('success'))
    showAlert('success', 'Berhasil!', @json(session('success')));
@endif

});
</script>
@endsection