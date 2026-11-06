
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Dashboard Pelanggan - ArsyClean')</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
          rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-800 min-h-screen flex justify-center">

    <!-- Container Dashboard Pelanggan -->
    <div class="w-full max-w-md bg-white min-h-screen flex flex-col pb-24 shadow-sm relative">

        <!-- Header -->
        <header class="sticky top-0 z-40 bg-white/95 backdrop-blur px-5 py-4 border-b border-slate-100 flex items-center justify-between">

            <div class="flex items-center gap-2.5">

                <div class="w-10 h-10 rounded-xl bg-sky-500 text-white flex items-center justify-center shadow-sm">
                    <i class="fa-solid fa-soap text-lg"></i>
                </div>

                <div>
                    <h1 class="font-bold text-base tracking-tight text-slate-900 leading-tight">
                        ArsyClean
                    </h1>

                    <p class="text-[10px] text-sky-600 font-bold uppercase tracking-wider">
                        Laundry Service
                    </p>
                </div>

            </div>

            <div class="flex items-center gap-2">

                <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-sky-50 border border-sky-100 rounded-full text-xs font-semibold text-sky-700">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    Pelanggan
                </div>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf

                    <button type="submit"
                            title="Logout"
                            class="w-9 h-9 rounded-xl border border-slate-200 text-slate-500 hover:text-red-500 flex items-center justify-center transition">

                        <i class="fa-solid fa-arrow-right-from-bracket text-sm"></i>

                    </button>
                </form>

            </div>

        </header>

        <!-- Notifikasi Login -->
        @if(session('login_berhasil'))
            <div id="loginSuccessModal"
                 class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/50 p-4">

                <div class="bg-white w-full max-w-sm rounded-2xl shadow-2xl p-7 text-center">

                    <div class="w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center text-3xl bg-green-100 text-green-600">
                        ✓
                    </div>

                    <h3 class="text-xl font-bold text-slate-800 mb-2">
                        Login Berhasil!
                    </h3>

                    <p class="text-sm text-slate-500">
                        {{ session('login_berhasil') }}
                    </p>

                    <button type="button"
                            onclick="document.getElementById('loginSuccessModal').remove()"
                            class="mt-6 w-full py-3 rounded-xl bg-sky-500 hover:bg-sky-600 text-white font-bold">
                        OK
                    </button>

                </div>
            </div>
        @endif

        <!-- Konten Dashboard -->
        <main class="p-4 space-y-4 flex-1">

            @yield('content')

        </main>

        <!-- Navigasi Bawah -->
        <nav class="fixed bottom-0 w-full max-w-md bg-white/95 backdrop-blur border-t border-slate-200 px-6 py-2.5 flex justify-between items-center z-40">

            <a href="{{ route('pelanggan.dashboard') }}"
               class="flex flex-col items-center gap-1 text-sky-600 font-semibold text-[11px]">
                <i class="fa-solid fa-house text-base"></i>
                <span>Beranda</span>
            </a>

            <a href="#pesanan"
               class="flex flex-col items-center gap-1 text-slate-400 hover:text-sky-600 text-[11px]">
                <i class="fa-solid fa-clipboard-list text-base"></i>
                <span>Pesanan</span>
            </a>

            <a href="#layanan"
               class="flex flex-col items-center gap-1 text-slate-400 hover:text-sky-600 text-[11px]">
                <i class="fa-solid fa-layer-group text-base"></i>
                <span>Layanan</span>
            </a>

            <a href="#profil"
               class="flex flex-col items-center gap-1 text-slate-400 hover:text-sky-600 text-[11px]">
                <i class="fa-regular fa-user text-base"></i>
                <span>Profil</span>
            </a>

        </nav>

    </div>

</body>
</html>
