
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Akun Baru - ArsyClean Laundry</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            font-size: 16px;
        }

        input, textarea {
            font-size: 16px !important;
        }

        input:focus, textarea:focus {
            border-color: #2563eb;
        }

        .modal-hidden {
            display: none;
        }

        .modal-show {
            display: flex;
            animation: fadeIn 0.2s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.96);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
    </style>
</head>

<body class="bg-slate-100 text-slate-800 min-h-screen flex flex-col p-4 sm:p-6 lg:p-8">

    <main class="flex-1 flex items-center justify-center py-4">

        <div class="w-full max-w-xl bg-white rounded-3xl shadow-xl border border-slate-200 overflow-hidden p-7 sm:p-10">

            <!-- HEADER -->
            <div class="text-center mb-8">

                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-bold uppercase tracking-wider bg-blue-50 text-blue-700 mb-4">
                    Pendaftaran Akun Pelanggan
                </div>

                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                    Mulai Layanan ArsyClean
                </h1>

                <p class="text-base text-slate-500 mt-2 max-w-sm mx-auto">
                    Daftar akun untuk memesan layanan laundry antar jemput dengan mudah dan cepat.
                </p>
            </div>

            <!-- FORM REGISTER -->
            <form id="registerForm"
                  action="{{ route('register.process') }}"
                  method="POST"
                  class="space-y-5"
                  novalidate>

                @csrf

                <!-- NAMA -->
                <div>
                    <label for="nama" class="block text-base font-bold text-slate-700 mb-2">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>

                    <input type="text"
                           id="nama"
                           name="nama"
                           value="{{ old('nama') }}"
                           required
                           minlength="3"
                           maxlength="100"
                           autocomplete="name"
                           placeholder="Contoh: Rina Kusuma"
                           class="w-full px-4 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600 transition-all">
                </div>

                <!-- EMAIL -->
                <div>
                    <label for="email" class="block text-base font-bold text-slate-700 mb-2">
                        Alamat Email <span class="text-red-500">*</span>
                    </label>

                    <input type="email"
                           id="email"
                           name="email"
                           value="{{ old('email') }}"
                           required
                           maxlength="100"
                           autocomplete="email"
                           placeholder="rina@example.com"
                           class="w-full px-4 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600 transition-all">
                </div>

                <!-- WHATSAPP -->
                <div>
                    <label for="no_hp" class="block text-base font-bold text-slate-700 mb-2">
                        No. WhatsApp / HP <span class="text-red-500">*</span>
                    </label>

                    <input type="tel"
                           id="no_hp"
                           name="no_hp"
                           value="{{ old('no_hp') }}"
                           required
                           inputmode="numeric"
                           maxlength="15"
                           autocomplete="tel"
                           placeholder="08xxxxxxxxxx"
                           class="w-full px-4 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600 transition-all">

                    <p class="text-sm text-slate-400 mt-2">
                        Masukkan 10–15 digit angka tanpa spasi atau tanda baca.
                    </p>
                </div>

                <!-- ALAMAT -->
                <div>
                    <label for="alamat" class="block text-base font-bold text-slate-700 mb-2">
                        Alamat Penjemputan Cucian <span class="text-red-500">*</span>
                    </label>

                    <textarea id="alamat"
                              name="alamat"
                              rows="3"
                              required
                              minlength="10"
                              maxlength="500"
                              autocomplete="street-address"
                              placeholder="Nama jalan, nomor rumah, RT/RW, kelurahan..."
                              class="w-full px-4 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600 transition-all resize-none">{{ old('alamat') }}</textarea>
                </div>

                <!-- PASSWORD -->
                <div>
                    <label for="password" class="block text-base font-bold text-slate-700 mb-2">
                        Kata Sandi Akun <span class="text-red-500">*</span>
                    </label>

                    <input type="password"
                           id="password"
                           name="password"
                           required
                           minlength="6"
                           maxlength="10"
                           autocomplete="new-password"
                           placeholder="Masukkan password (6–10 karakter)"
                           class="w-full px-4 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600 transition-all">

                    <p class="text-sm text-slate-400 mt-2">
                        Password harus terdiri dari 6–10 karakter.
                    </p>
                </div>

                <!-- TOMBOL DAFTAR -->
                <button type="submit"
                        class="w-full mt-3 py-4 bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-base rounded-2xl shadow-lg shadow-blue-600/30 transition-all flex items-center justify-center gap-2">
                    Daftar Sebagai Pelanggan →
                </button>

            </form>

            <!-- LOGIN LINK -->
            <div class="mt-7 pt-5 border-t border-slate-100 text-center text-base text-slate-500">

                Sudah memiliki akun?

                <a href="{{ route('login') }}"
                   class="font-bold text-blue-600 hover:underline ml-1">
                    Masuk di Sini
                </a>

            </div>

        </div>

    </main>

    <!-- FOOTER -->
    <footer class="w-full max-w-5xl mx-auto pt-4 text-center text-sm text-slate-400">
        &copy; {{ date('Y') }} ArsyClean Laundry. Sistem Layanan Pengelolaan Laundry Terintegrasi.
    </footer>

    <!-- CUSTOM POPUP -->
    <div id="alertModal"
         class="modal-hidden fixed inset-0 z-[9999] items-center justify-center bg-slate-900/60 p-4">

        <div class="bg-white w-full max-w-sm rounded-3xl shadow-2xl p-7 text-center">

            <div id="alertIcon"
                 class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-5">
                <span id="alertIconSymbol" class="text-4xl"></span>
            </div>

            <h2 id="alertTitle" class="text-xl font-extrabold mb-3"></h2>

            <p id="alertMessage" class="text-slate-600 text-sm leading-relaxed mb-6"></p>

            <button type="button"
                    id="alertButton"
                    class="w-full py-3 rounded-xl text-white font-bold transition-all">
                Mengerti
            </button>

        </div>
    </div>

    <!-- JAVASCRIPT VALIDATION -->
    <script>
        const form = document.getElementById('registerForm');

        const modal = document.getElementById('alertModal');
        const alertIcon = document.getElementById('alertIcon');
        const alertIconSymbol = document.getElementById('alertIconSymbol');
        const alertTitle = document.getElementById('alertTitle');
        const alertMessage = document.getElementById('alertMessage');
        const alertButton = document.getElementById('alertButton');

        let inputYangSalah = null;

        // FUNGSI MENAMPILKAN POPUP
        function tampilkanPopup(pesan, input = null, berhasil = false) {

            inputYangSalah = input;

            modal.classList.remove('modal-hidden');
            modal.classList.add('modal-show');

            if (berhasil) {
                alertIcon.className =
                    'w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-5 bg-green-100';

                alertIconSymbol.textContent = '✓';
                alertIconSymbol.className = 'text-5xl font-black text-green-600';

                alertTitle.textContent = 'Registrasi Berhasil!';
                alertTitle.className = 'text-xl font-extrabold mb-3 text-green-700';

                alertButton.className =
                    'w-full py-3 rounded-xl text-white font-bold bg-green-600 hover:bg-green-700 transition-all';

                alertButton.textContent = 'OK';

            } else {
                alertIcon.className =
                    'w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-5 bg-red-100';

                alertIconSymbol.textContent = '!';
                alertIconSymbol.className = 'text-5xl font-black text-red-600';

                alertTitle.textContent = 'Data Belum Valid!';
                alertTitle.className = 'text-xl font-extrabold mb-3 text-red-700';

                alertButton.className =
                    'w-full py-3 rounded-xl text-white font-bold bg-red-600 hover:bg-red-700 transition-all';

                alertButton.textContent = 'Mengerti';
            }

            alertMessage.textContent = pesan;
        }

        // TUTUP POPUP
        function tutupPopup() {
            modal.classList.remove('modal-show');
            modal.classList.add('modal-hidden');

            if (inputYangSalah) {
                inputYangSalah.focus();
            }
        }

        alertButton.addEventListener('click', tutupPopup);

        // VALIDASI FORM
        form.addEventListener('submit', function(e) {

            const nama = document.getElementById('nama');
            const email = document.getElementById('email');
            const noHp = document.getElementById('no_hp');
            const alamat = document.getElementById('alamat');
            const password = document.getElementById('password');

            const namaValue = nama.value.trim();
            const emailValue = email.value.trim();
            const hpValue = noHp.value.trim();
            const alamatValue = alamat.value.trim();
            const passwordValue = password.value;

            // VALIDASI NAMA
            if (namaValue === '') {
                e.preventDefault();
                return tampilkanPopup('Nama lengkap wajib diisi!', nama);
            }

            if (namaValue.length < 3) {
                e.preventDefault();
                return tampilkanPopup('Nama lengkap minimal 3 karakter!', nama);
            }

            if (namaValue.length > 100) {
                e.preventDefault();
                return tampilkanPopup('Nama lengkap maksimal 100 karakter!', nama);
            }

            if (!/^[\p{L}\s.'-]+$/u.test(namaValue)) {
                e.preventDefault();
                return tampilkanPopup(
                    'Nama hanya boleh berisi huruf, spasi, titik, apostrof, dan tanda hubung. Angka dan simbol lain tidak diperbolehkan!',
                    nama
                );
            }

            // VALIDASI EMAIL
            if (emailValue === '') {
                e.preventDefault();
                return tampilkanPopup('Alamat email wajib diisi!', email);
            }

            if (!email.checkValidity()) {
                e.preventDefault();
                return tampilkanPopup(
                    'Format email tidak valid. Contoh: nama@gmail.com',
                    email
                );
            }

            // VALIDASI WHATSAPP
            if (hpValue === '') {
                e.preventDefault();
                return tampilkanPopup('Nomor WhatsApp wajib diisi!', noHp);
            }

            if (!/^[0-9]{10,15}$/.test(hpValue)) {
                e.preventDefault();
                return tampilkanPopup(
                    'Nomor WhatsApp harus berupa angka saja, tanpa huruf atau simbol, dengan panjang 10–15 digit!',
                    noHp
                );
            }

            // VALIDASI ALAMAT
            if (alamatValue === '') {
                e.preventDefault();
                return tampilkanPopup('Alamat penjemputan wajib diisi!', alamat);
            }

            if (alamatValue.length < 10) {
                e.preventDefault();
                return tampilkanPopup(
                    'Alamat penjemputan minimal 10 karakter!',
                    alamat
                );
            }

            if (alamatValue.length > 500) {
                e.preventDefault();
                return tampilkanPopup(
                    'Alamat penjemputan maksimal 500 karakter!',
                    alamat
                );
            }

            // VALIDASI PASSWORD
            if (passwordValue === '') {
                e.preventDefault();
                return tampilkanPopup('Password wajib diisi!', password);
            }

            if (passwordValue.length < 6 || passwordValue.length > 10) {
                e.preventDefault();
                return tampilkanPopup(
                    'Password harus terdiri dari minimal 6 dan maksimal 10 karakter!',
                    password
                );
            }

            // SEMUA VALIDASI LOKAL LOLOS.
            // FORM AKAN DIKIRIM KE LARAVEL UNTUK VALIDASI DATABASE.
        });

        // TAMPILKAN ERROR DARI VALIDASI LARAVEL
        @if ($errors->any())
            document.addEventListener('DOMContentLoaded', function() {
                tampilkanPopup(
                    @json($errors->first()),
                    null
                );
            });
        @endif

    </script>

</body>
</html>
