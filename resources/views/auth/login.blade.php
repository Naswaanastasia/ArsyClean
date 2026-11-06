<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - ArsyClean Laundry</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-100 text-slate-800">

<div class="min-h-screen flex flex-col px-4 sm:px-8 py-6">

    <!-- BAR ATAS -->
    <div class="w-full max-w-6xl mx-auto flex items-center justify-between text-[11px] font-semibold tracking-wider">
        <span class="flex items-center gap-2 uppercase text-slate-600">
            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            Sistem Aktif &amp; Terhubung
        </span>
        <span class="rounded-full bg-white border border-slate-200 px-4 py-1.5 text-slate-600 normal-case tracking-normal">
            Laundry Coin &amp; Reguler 24 Jam
        </span>
    </div>

    <!-- CARD UTAMA -->
    <div class="flex-1 flex items-center justify-center py-6">
        <div class="w-full max-w-6xl bg-white rounded-3xl shadow-xl border border-slate-200 overflow-hidden flex flex-col md:flex-row">

            <!-- KIRI: INFO -->
            <div class="hidden md:flex md:w-5/12 flex-col justify-between gap-8 bg-slate-50 p-10 lg:p-12 border-r border-slate-100">

                <div>
                    <span class="inline-flex items-center gap-2 rounded-full bg-white border border-slate-200 px-3.5 py-1.5 text-xs font-semibold text-slate-600">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        Layanan Cepat &amp; Higienis
                    </span>

                    <h1 class="mt-6 text-3xl lg:text-4xl font-extrabold leading-tight text-slate-900">
                        Pakaian Bersih, Wangi &amp; Terawat Sempurna
                    </h1>

                    <p class="mt-4 text-base text-slate-500 leading-relaxed">
                        Nikmati kemudahan pengelolaan pesanan laundry coin dan reguler dengan sistem terintegrasi 24 jam.
                    </p>
                </div>

                <!-- ILUSTRASI -->
                <div class="rounded-2xl bg-slate-200/70 p-6 flex items-center justify-center">
                    <svg viewBox="0 0 320 200" class="w-full max-w-xs" xmlns="http://www.w3.org/2000/svg">
                        <!-- rak baju -->
                        <line x1="190" y1="30" x2="300" y2="30" stroke="#64748b" stroke-width="4" stroke-linecap="round"/>
                        <line x1="200" y1="30" x2="190" y2="185" stroke="#64748b" stroke-width="4" stroke-linecap="round"/>
                        <line x1="290" y1="30" x2="300" y2="185" stroke="#64748b" stroke-width="4" stroke-linecap="round"/>
                        <!-- kemeja -->
                        <path d="M225 34 l-16 10 l8 18 l8 -5 v58 h42 v-58 l8 5 l8 -18 l-16 -10 l-13 10 h-8 z" fill="#2f5d86"/>
                        <!-- kaos -->
                        <path d="M262 40 l-6 6 l5 8 l4 -2 v26 h18 v-26 l4 2 l5 -8 l-6 -6 z" fill="#f1f5f9" stroke="#94a3b8" stroke-width="1.5"/>
                        <!-- keranjang -->
                        <rect x="215" y="150" width="60" height="34" rx="6" fill="#d6a86a"/>
                        <rect x="222" y="140" width="46" height="14" rx="4" fill="#f8fafc"/>
                        <!-- mesin cuci -->
                        <rect x="20" y="55" width="140" height="130" rx="12" fill="#1f4468"/>
                        <rect x="32" y="66" width="116" height="14" rx="4" fill="#2f5d86"/>
                        <circle cx="132" cy="73" r="4" fill="#e2e8f0"/>
                        <circle cx="90" cy="130" r="40" fill="#e2e8f0"/>
                        <circle cx="90" cy="130" r="32" fill="#7fb0d6"/>
                        <path d="M62 135 q14 -12 28 0 t28 0" fill="none" stroke="#e8f3fb" stroke-width="4" stroke-linecap="round"/>
                        <rect x="30" y="178" width="14" height="7" rx="2" fill="#12304b"/>
                        <rect x="136" y="178" width="14" height="7" rx="2" fill="#12304b"/>
                        <!-- tumpukan handuk -->
                        <rect x="34" y="38" width="48" height="8" rx="3" fill="#94a3b8"/>
                        <rect x="38" y="30" width="40" height="8" rx="3" fill="#cbd5e1"/>
                    </svg>
                </div>

                <!-- KARTU STANDAR -->
                <div class="flex items-center gap-3 rounded-2xl bg-white border border-slate-200 p-4 shadow-sm">
                    <div class="w-10 h-10 rounded-full bg-sky-50 text-sky-600 flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="9"/>
                            <path d="M8.5 12.5l2.5 2.5 4.5-5"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-800">Standar Kebersihan Tinggi</p>
                        <p class="text-xs text-slate-500">Deterjen premium &amp; sanitasi berkala</p>
                    </div>
                </div>
            </div>

            <!-- KANAN: FORM LOGIN -->
            <div class="flex-1 p-8 sm:p-12 lg:p-14 flex flex-col justify-center">

                <!-- Brand -->
                <div class="flex items-center gap-4 mb-8">
                    <div class="relative w-14 h-14 rounded-2xl bg-[#123a5e] flex items-center justify-center overflow-hidden shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#7fd3f5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="8"/>
                            <path d="M8 13c1.5 1.5 4.5 1.5 8 0"/>
                        </svg>
                        <img src="{{ asset('images/arsy_clean_logo_1793340486742.jpg') }}"
                             alt="Logo ArsyClean"
                             class="absolute inset-0 w-full h-full object-cover"
                             onerror="this.style.display='none'">
                    </div>
                    <div>
                        <h2 class="text-2xl font-extrabold text-slate-900 leading-tight">ArsyClean Laundry</h2>
                        <p class="text-[11px] font-bold uppercase tracking-widest text-teal-700">Sistem Pengelolaan Laundry Coin &amp; Reguler</p>
                    </div>
                </div>

                <h3 class="text-3xl font-extrabold text-slate-900">Login Akun</h3>
                <p class="text-base text-slate-500 mt-2 mb-7">
                    Silakan masukkan email dan kata sandi Anda untuk mengakses portal laundry.
                </p>

                <form id="loginForm"
                      action="{{ route('login.process') }}"
                      method="POST"
                      novalidate
                      class="space-y-5">

                    @csrf

                    <!-- EMAIL -->
                    <div>
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">
                            Alamat Email
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="5" width="18" height="14" rx="2"/>
                                    <path d="M3 7l9 6 9-6"/>
                                </svg>
                            </span>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="contoh@email.com"
                                autocomplete="email"
                                class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-base focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#123a5e]/40"
                            >
                        </div>
                    </div>

                    <!-- PASSWORD -->
                    <div>
                        <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">
                            Kata Sandi
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="5" y="11" width="14" height="9" rx="2"/>
                                    <path d="M8 11V8a4 4 0 018 0v3"/>
                                </svg>
                            </span>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                minlength="8"
                                maxlength="13"
                                placeholder="Masukkan kata sandi"
                                autocomplete="current-password"
                                class="w-full pl-11 pr-12 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-base focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#123a5e]/40"
                            >
                            <button
                                type="button"
                                id="togglePassword"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-[#123a5e]"
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

                    <!-- INGAT SAYA & LUPA PASSWORD -->
                    <div class="flex items-center justify-between text-sm">
                        <label class="flex items-center gap-2 text-slate-600 cursor-pointer">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 accent-[#123a5e]">
                            Ingat saya
                        </label>
                        <a href="#" class="font-semibold text-[#123a5e] hover:underline">Lupa Kata Sandi?</a>
                    </div>

                    <!-- TOMBOL LOGIN -->
                    <button
                        type="submit"
                        class="w-full py-4 bg-[#123a5e] hover:bg-[#0d2c48] text-white text-base font-bold rounded-xl shadow-md transition-all">
                        Masuk ke Akun →
                    </button>
                </form>

                <!-- PEMISAH -->
                <div class="flex items-center gap-3 my-6">
                    <div class="flex-1 h-px bg-slate-200"></div>
                    <span class="text-xs font-semibold text-slate-400">ATAU</span>
                    <div class="flex-1 h-px bg-slate-200"></div>
                </div>

                <p class="text-center text-sm text-slate-500">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="font-bold text-slate-900 hover:underline">Daftar Sekarang</a>
                </p>

                <div class="mt-6 pt-5 border-t border-slate-100 text-center text-xs text-slate-500">
                    Butuh bantuan teknis?
                    <a href="#" class="inline-flex items-center gap-1.5 font-semibold text-emerald-700 hover:underline ml-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        Hubungi CS ArsyClean
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <div class="text-center text-xs text-slate-500">
        <p>© {{ date('Y') }} ArsyClean Laundry. All rights reserved.</p>
        <p class="mt-1.5 text-[11px] text-slate-400">
            Kebijakan Privasi &nbsp;•&nbsp; Syarat &amp; Ketentuan &nbsp;•&nbsp; Versi 2.4.0
        </p>
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
    const emailErrors = @json($errors->get('email'));
    const passwordErrors = @json($errors->get('password'));

    if (passwordErrors.length > 0) {
        showAlert('error', 'Password Tidak Sesuai', passwordErrors[0]);
    } else if (emailErrors.length > 0) {
        showAlert('error', 'Email Tidak Sesuai', emailErrors[0]);
    } else if (@json($errors->any())) {
        showAlert('error', 'Login Gagal', 'Periksa kembali data yang kamu masukkan.');
    }

    // PESAN LOGIN BERHASIL
    @if (session('login_berhasil'))
        showAlert('success', 'Login Berhasil!', @json(session('login_berhasil')));
    @endif

    // PESAN SUKSES LAINNYA
    @if (session('success'))
        showAlert('success', 'Berhasil!', @json(session('success')));
    @endif

});
</script>
</body>
</html>