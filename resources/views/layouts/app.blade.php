<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ArsyClean Laundry - Self Service Coin & Reguler')</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-100 min-h-screen flex flex-col text-slate-800">

    <!-- Top Navbar -->
    <nav class="sticky top-0 z-40 bg-white border-b border-slate-200 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-sky-500/10 border border-sky-500/20 p-1 flex items-center justify-center">
                    <img src="/src/assets/images/arsy_clean_logo_1793340486742.jpg" alt="Logo" class="w-full h-full object-cover rounded-xl">
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="font-extrabold text-lg text-slate-900">ArsyClean</span>
                    </div>
                </div>
            </div>

           <div class="flex items-center gap-3">
    @auth
        <div class="flex items-center gap-2 bg-slate-50 border border-slate-200 p-1.5 rounded-2xl">
            <div class="w-7 h-7 rounded-xl overflow-hidden bg-sky-100 shrink-0">
                <img src="{{ Auth::user()->foto ?? '/src/assets/images/arsy_clean_mascot_1793340487175.jpg' }}" alt="User" class="w-full h-full object-cover">
            </div>
            
            <!-- Nama User saja (Role & teks bawah dihapus) -->
            <div class="text-left text-xs pr-1">
                <div class="font-bold text-slate-900 leading-tight">
                    {{ explode(' ', Auth::user()->nama)[0] }}
                </div>
            </div>

            <!-- Tombol Logout saja -->
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger">Logout</button>
            </form>
        </div>
    @else

    @endauth
</div>
    </nav>

    <!-- POP-UP LOGIN BERHASIL -->
@if(session('login_berhasil'))
<div id="loginSuccessModal"
     class="fixed inset-0 z-[9999] flex items-center justify-center bg-slate-900/50 p-4">

    <div class="bg-white w-full max-w-sm rounded-2xl shadow-2xl p-7 text-center">

        <div class="w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center text-3xl bg-green-100 text-green-600">
            ✓
        </div>

        <h3 class="text-xl font-bold text-slate-800 mb-2">
            Login Berhasil!
        </h3>

        <p class="text-sm text-slate-500 leading-relaxed">
            {{ session('login_berhasil') }}
        </p>

        <button type="button" id="closeLoginSuccess"
                class="mt-6 w-full py-3 rounded-xl bg-green-500 hover:bg-green-600 text-white font-bold">
            OK
        </button>

    </div>
</div>
@endif

@if(session('error'))
    <div class="max-w-7xl mx-auto px-4 mt-4 w-full">
        {{ session('error') }}
    </div>
@endif

    <!-- Main Content -->
    <main class="flex-1 max-w-7xl w-full mx-auto p-4 sm:p-6">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-4 text-center text-xs text-slate-500 mt-auto">
        
    </footer>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const modal = document.getElementById('loginSuccessModal');
    const closeButton = document.getElementById('closeLoginSuccess');

    if (modal && closeButton) {
        closeButton.addEventListener('click', function () {
            modal.remove();
        });
    }

});
</script>
</body>
</html>
