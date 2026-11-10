
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk ke Akun - ArsyClean Laundry</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
        }
    </style>
</head>

<body class="bg-slate-100 text-slate-800 min-h-screen flex flex-col p-4 sm:p-6 lg:p-8">

    <!-- Main Login Card -->
    <main class="flex-1 flex items-center justify-center">
        <div class="w-full max-w-xl bg-white rounded-3xl shadow-xl border border-slate-200 overflow-hidden p-8 sm:p-10">

            <div class="text-center mb-8">
                

                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                    Masuk ke Akun Anda
                </h1>

                
            </div>

            <!-- Error Notification -->
            @if(isset($errors) && $errors->any())
                <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm font-semibold space-y-1">
                    <div class="font-bold flex items-center gap-2">
                        <span>⚠️</span> Gagal masuk:
                    </div>

                    <ul class="list-disc list-inside text-xs pl-2 space-y-0.5">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('success'))
                <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-bold">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('login.process') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-bold text-slate-700 mb-1.5">
                        Alamat Email <span class="text-red-500">*</span>
                    </label>

                    <input type="email"
                           id="email"
                           name="email"
                           value="{{ old('email') }}"
                           required
                           autofocus
                           placeholder="nama@email.com"
                           class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-base font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all">
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-bold text-slate-700 mb-1.5">
                        Kata Sandi <span class="text-red-500">*</span>
                    </label>

                    <div class="relative">
                        <input type="password"
                               id="password"
                               name="password"
                               required
                               placeholder="Masukkan kata sandi Anda"
                               class="w-full pl-4 pr-12 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-base font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all">

                        <button type="button"
                                id="togglePasswordBtn"
                                onclick="togglePasswordVisibility()"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-700 text-sm font-bold">
                            👁️
                        </button>
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center gap-2.5 text-slate-600 cursor-pointer">
                        <input type="checkbox"
                               name="remember"
                               class="w-4 h-4 rounded text-blue-600 focus:ring-blue-500 border-slate-300">

                        <span class="font-medium text-xs sm:text-sm">
                            Ingat saya di perangkat ini
                        </span>
                    </label>
                </div>

                <!-- Login Button -->
                <button type="submit"
                        class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-base rounded-2xl shadow-lg shadow-blue-600/30 transition-all flex items-center justify-center gap-2">
                    Masuk Sekarang &rarr;
                </button>
            </form>

            <!-- Link Registrasi -->
            <div class="mt-6 pt-5 border-t border-slate-100 text-center text-sm text-slate-500">
                Belum memiliki akun pelanggan?

                <a href="{{ route('register') }}"
                   class="font-bold text-blue-600 hover:underline ml-1">
                    Daftar di Sini
                </a>
            </div>

            <!-- Quick Demo Testing Credentials -->
            <div class="mt-6 pt-4 border-t border-slate-100 text-xs">

                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-2 text-center">
                    Akses Cepat Pengujian Role:
                </span>

                <div class="grid grid-cols-3 gap-2">

                    <button type="button"
                            onclick="isiAkun('pelanggan@gmail.com', 'password123')"
                            class="p-2.5 rounded-xl border border-slate-200 bg-slate-50 hover:bg-blue-50 text-center font-bold text-slate-700 text-xs transition">
                        Pelanggan
                    </button>

                    <button type="button"
                            onclick="isiAkun('admin@gmail.com', 'admin123')"
                            class="p-2.5 rounded-xl border border-slate-200 bg-slate-50 hover:bg-sky-50 text-center font-bold text-slate-700 text-xs transition">
                        Admin
                    </button>

                    <button type="button"
                            onclick="isiAkun('owner@gmail.com', 'owner123')"
                            class="p-2.5 rounded-xl border border-slate-200 bg-slate-50 hover:bg-amber-50 text-center font-bold text-slate-700 text-xs transition">
                        Owner
                    </button>

                </div>
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="w-full max-w-5xl mx-auto pt-4 text-center text-xs text-slate-400">
        &copy; {{ date('Y') }} ArsyClean Laundry. Sistem Layanan Pengelolaan Laundry Terintegrasi.
    </footer>

    <script>
        function togglePasswordVisibility() {
            const pass = document.getElementById('password');
            pass.type = pass.type === 'password' ? 'text' : 'password';
        }

        function isiAkun(email, pass) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = pass;
        }
    </script>

</body>
</html>
