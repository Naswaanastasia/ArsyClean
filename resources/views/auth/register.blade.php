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
        body { font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif; font-size: 15px; }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 min-h-screen flex flex-col justify-between p-4 sm:p-6 lg:p-8">

    <!-- Top Simple Navbar -->
    <header class="w-full max-w-5xl mx-auto flex items-center justify-between pb-4">
        <a href="{{ route('home') }}" class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-sky-400 to-blue-600 flex items-center justify-center font-black text-white text-lg shadow-md shadow-sky-500/20">
                A
            </div>
            <span class="font-extrabold text-xl text-slate-900 tracking-tight">ArsyClean</span>
        </a>
        <a href="{{ route('home') }}" class="text-xs sm:text-sm font-bold text-slate-500 hover:text-blue-600 transition flex items-center gap-1">
            <span>&larr;</span> Kembali ke Beranda
        </a>
    </header>

    <!-- Main Register Card -->
    <main class="flex-1 flex items-center justify-center py-6">
        <div class="w-full max-w-xl bg-white rounded-3xl shadow-xl border border-slate-200 overflow-hidden p-8 sm:p-10">

            <div class="text-center mb-8">
                <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-blue-50 text-blue-700 mb-3">
                    ✨ Pendaftaran Akun Pelanggan
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                    Mulai Layanan ArsyClean
                </h1>
                <p class="text-sm text-slate-500 mt-1.5 max-w-sm mx-auto">
                    Daftar akun untuk memesan layanan laundry antar jemput dengan mudah dan cepat.
                </p>
            </div>

            <!-- Error Notification -->
            @if(isset($errors) && $errors->any())
                <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm font-semibold space-y-1">
                    <div class="font-bold flex items-center gap-2">
                        <span>⚠️</span> Mohon periksa kembali data pendaftaran:
                    </div>
                    <ul class="list-disc list-inside text-xs pl-2 space-y-0.5">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register.process') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Nama Lengkap -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">
                        Nama Lengkap <span class="text-red-500 font-bold">*</span>
                    </label>
                    <input type="text"
                           name="nama"
                           value="{{ old('nama') }}"
                           required
                           placeholder="Contoh: Rina Kusuma"
                           class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-base font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all">
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">
                        Alamat Email <span class="text-red-500 font-bold">*</span>
                    </label>
                    <input type="email"
                           name="email"
                           value="{{ old('email') }}"
                           required
                           placeholder="rina@example.com"
                           class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-base font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all">
                </div>

                <!-- No WhatsApp (Numeric) -->
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
                           class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-base font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all">
                    <p class="text-[11px] text-slate-400 mt-1">Wajib berupa angka numerik (10-15 digit).</p>
                </div>

                <!-- Alamat Penjemputan -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">
                        Alamat Penjemputan Cucian <span class="text-red-500 font-bold">*</span>
                    </label>
                    <textarea name="alamat"
                              rows="2"
                              required
                              placeholder="Nama jalan, nomor rumah, RT/RW, kelurahan..."
                              class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-base font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all">{{ old('alamat') }}</textarea>
                </div>

                <!-- Kata Sandi -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">
                        Kata Sandi Akun <span class="text-red-500 font-bold">*</span>
                    </label>
                    <input type="password"
                           name="password"
                           required
                           placeholder="Minimal 6 karakter"
                           class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-base font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all">
                </div>

                <!-- Tombol Submit Register -->
                <button type="submit"
                        class="w-full mt-2 py-4 bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-base rounded-2xl shadow-lg shadow-blue-600/30 transition-all flex items-center justify-center gap-2">
                    Daftar Sebagai Pelanggan &rarr;
                </button>
            </form>

            <div class="mt-6 pt-5 border-t border-slate-100 text-center text-sm text-slate-500">
                Sudah memiliki akun?
                <a href="{{ route('login') }}" class="font-bold text-blue-600 hover:underline ml-1">
                    Masuk di Sini
                </a>
            </div>

        </div>
    </main>

    <!-- Clean Footer -->
    <footer class="w-full max-w-5xl mx-auto pt-4 text-center text-xs text-slate-400">
        &copy; {{ date('Y') }} ArsyClean Laundry. Sistem Layanan Pengelolaan Laundry Terintegrasi.
    </footer>

</body>
</html>
