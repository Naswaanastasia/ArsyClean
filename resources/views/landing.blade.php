<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ArsyClean Laundry - Layanan Cuci Koin, Reguler & Antar-Jemput 24 Jam</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif; font-size: 16px; }
        html { scroll-behavior: smooth; }
    </style>
</head>
<body class="bg-white text-slate-800 antialiased">

    <!-- Top Navigation Bar -->
    <nav class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-sky-400 to-blue-600 flex items-center justify-center font-black text-white text-xl shadow-md shadow-sky-500/20">
                    A
                </div>
                <div>
                    <span class="font-extrabold text-2xl text-slate-900 tracking-tight block">ArsyClean</span>
                    <span class="text-[10px] font-bold text-blue-600 uppercase tracking-widest block -mt-1">Laundry Service</span>
                </div>
            </a>

            <!-- Navigation Links -->
            <div class="hidden md:flex items-center gap-8 text-sm font-bold text-slate-600">
                <a href="#beranda" class="hover:text-blue-600 transition">Beranda</a>
                <a href="#katalog" class="hover:text-blue-600 transition">Katalog Layanan</a>
                <a href="#keunggulan" class="hover:text-blue-600 transition">Keunggulan</a>
                <a href="#cara-pesan" class="hover:text-blue-600 transition">Cara Pesan</a>
                <a href="#kontak" class="hover:text-blue-600 transition">Kontak</a>
            </div>

            <!-- Auth Buttons -->
            <div class="flex items-center gap-3">
                @auth
                    @php
                        $role = Auth::user()->role;
                        $dashRoute = match($role) {
                            'admin' => route('admin.dashboard'),
                            'owner' => route('owner.dashboard'),
                            default => route('pelanggan.dashboard'),
                        };
                    @endphp
                    <a href="{{ $dashRoute }}" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-sm rounded-xl shadow-md shadow-blue-600/30 transition flex items-center gap-2">
                        <span>📊</span> Buka Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2.5 text-slate-700 hover:text-blue-600 font-extrabold text-sm transition">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-sm rounded-xl shadow-md shadow-blue-600/30 transition">
                        Daftar Baru
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="beranda" class="relative overflow-hidden bg-gradient-to-b from-blue-50/60 via-white to-slate-50 py-16 sm:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-wider bg-blue-100 text-blue-800">
                        ✨ Layanan Laundry Antar-Jemput No. 1
                    </div>
                    <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black text-slate-900 leading-tight tracking-tight">
                        Laundry Jadi Lebih <span class="text-blue-600">Mudah, Cepat</span> &amp; Terawat.
                    </h1>
                    <p class="text-base sm:text-lg text-slate-600 leading-relaxed max-w-xl mx-auto lg:mx-0">
                        Tidak perlu repot mencuci sendiri. Pesan pick up laundry langsung dari rumah Anda, pantau proses pencucian realtime, dan pakaian wangi rapi siap dipakai.
                    </p>

                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 pt-2">
                        @auth
                            <a href="{{ route('pelanggan.dashboard') }}" class="w-full sm:w-auto px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-base rounded-2xl shadow-xl shadow-blue-600/30 transition-all text-center">
                                Pesan Laundry Sekarang &rarr;
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-base rounded-2xl shadow-xl shadow-blue-600/30 transition-all text-center">
                                Mulai Pesan Laundry &rarr;
                            </a>
                        @endauth
                        <a href="#katalog" class="w-full sm:w-auto px-7 py-4 bg-slate-100 hover:bg-slate-200 text-slate-700 font-extrabold text-base rounded-2xl transition text-center">
                            Lihat Katalog Layanan
                        </a>
                    </div>

                    <!-- Mini Stats -->
                    <div class="grid grid-cols-3 gap-4 pt-8 border-t border-slate-200/80 max-w-md mx-auto lg:mx-0 text-center sm:text-left">
                        <div>
                            <div class="text-2xl sm:text-3xl font-black text-slate-900">24 Jam</div>
                            <div class="text-xs font-semibold text-slate-500">Layanan Online</div>
                        </div>
                        <div>
                            <div class="text-2xl sm:text-3xl font-black text-blue-600">100%</div>
                            <div class="text-xs font-semibold text-slate-500">Higienis &amp; Bersih</div>
                        </div>
                        <div>
                            <div class="text-2xl sm:text-3xl font-black text-emerald-600">QRIS</div>
                            <div class="text-xs font-semibold text-slate-500">Bayar Praktis</div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-5">
                    <div class="relative bg-gradient-to-tr from-blue-600 to-indigo-800 rounded-3xl p-8 sm:p-10 text-white shadow-2xl">
                        <div class="text-xs font-bold text-sky-200 uppercase tracking-widest mb-2">Mengapa ArsyClean?</div>
                        <h3 class="text-2xl font-black mb-6">Pilihan Solusi Laundry Lengkap</h3>

                        <div class="space-y-4">
                            <div class="flex items-start gap-4 p-4 rounded-2xl bg-white/10 backdrop-blur-xs border border-white/10">
                                <div class="text-2xl">🚚</div>
                                <div>
                                    <h4 class="font-bold text-sm">Pick Up &amp; Delivery</h4>
                                    <p class="text-xs text-blue-100 mt-0.5">Kurir siap jemput dan antar kembali ke pintu rumah Anda.</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4 p-4 rounded-2xl bg-white/10 backdrop-blur-xs border border-white/10">
                                <div class="text-2xl">🧼</div>
                                <div>
                                    <h4 class="font-bold text-sm">Deterjen &amp; Pewangi Premium</h4>
                                    <p class="text-xs text-blue-100 mt-0.5">Pakaian higienis, serat kain tetap awet, dan wangi tahan lama.</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4 p-4 rounded-2xl bg-white/10 backdrop-blur-xs border border-white/10">
                                <div class="text-2xl">📱</div>
                                <div>
                                    <h4 class="font-bold text-sm">Pelacakan Realtime</h4>
                                    <p class="text-xs text-blue-100 mt-0.5">Cek status cucian step-by-step langsung dari gadget Anda.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Katalog Layanan Dirapikan -->
    <section id="katalog" class="py-16 sm:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-14">
                <span class="text-xs font-black uppercase tracking-wider text-blue-600 block mb-2">Pilihan Paket Terbaik</span>
                <h2 class="text-3xl sm:text-4xl font-black text-slate-900">Katalog Layanan ArsyClean</h2>
                <p class="text-base text-slate-500 mt-2">
                    Pilih paket layanan laundry sesuai dengan kebutuhan Anda. Tarif transparan tanpa biaya tersembunyi.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Paket 1 -->
                <div class="bg-slate-50 hover:bg-white rounded-3xl p-6 sm:p-7 border border-slate-200 hover:border-blue-400 hover:shadow-xl transition-all flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-700 flex items-center justify-center text-2xl font-black mb-4">
                            👕
                        </div>
                        <h3 class="text-lg font-black text-slate-900">Cuci Lipat Reguler</h3>
                        <p class="text-xs text-slate-500 mt-1 mb-4">Layanan cuci bersih dan lipat rapi untuk pakaian sehari-hari.</p>

                        <div class="mb-4">
                            <span class="text-2xl font-black text-slate-900">Rp 7.000</span>
                            <span class="text-xs text-slate-500 font-bold">/ kg</span>
                        </div>

                        <ul class="text-xs text-slate-600 space-y-2 mb-6">
                            <li class="flex items-center gap-2"><span>✓</span> Durasi 2 Hari Kerja</li>
                            <li class="flex items-center gap-2"><span>✓</span> Deterjen &amp; Pewangi Pilihan</li>
                            <li class="flex items-center gap-2"><span>✓</span> Pengecekan Kualitas Standar</li>
                        </ul>
                    </div>

                    <a href="{{ route('login') }}" class="w-full py-3 bg-slate-900 hover:bg-blue-600 text-white font-bold text-xs rounded-xl text-center transition">
                        Pesan Layanan
                    </a>
                </div>

                <!-- Paket 2: Highlight -->
                <div class="bg-blue-50/70 rounded-3xl p-6 sm:p-7 border-2 border-blue-500 shadow-xl flex flex-col justify-between relative">
                    <span class="absolute -top-3 left-1/2 -translate-x-1/2 bg-blue-600 text-white text-[10px] font-black uppercase tracking-wider px-3 py-1 rounded-full">
                        PALING POPULER
                    </span>
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center text-2xl font-black mb-4">
                            🚚
                        </div>
                        <h3 class="text-lg font-black text-slate-900">Cuci Komplit + Pickup</h3>
                        <p class="text-xs text-slate-600 mt-1 mb-4">Cuci, setrika uap rapi, dan fasilitas jemput-antar ke alamat Anda.</p>

                        <div class="mb-4">
                            <span class="text-2xl font-black text-blue-900">Rp 10.000</span>
                            <span class="text-xs text-slate-600 font-bold">/ kg</span>
                        </div>

                        <ul class="text-xs text-slate-700 space-y-2 mb-6">
                            <li class="flex items-center gap-2"><span>✓</span> Durasi 2 Hari Kerja</li>
                            <li class="flex items-center gap-2"><span>✓</span> Termasuk Antar-Jemput Kurir</li>
                            <li class="flex items-center gap-2"><span>✓</span> Setrika Uap Rapi &amp; Wangi</li>
                        </ul>
                    </div>

                    <a href="{{ route('login') }}" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl text-center shadow-md transition">
                        Pesan Antar-Jemput
                    </a>
                </div>

                <!-- Paket 3 -->
                <div class="bg-slate-50 hover:bg-white rounded-3xl p-6 sm:p-7 border border-slate-200 hover:border-blue-400 hover:shadow-xl transition-all flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-indigo-100 text-indigo-700 flex items-center justify-center text-2xl font-black mb-4">
                            👔
                        </div>
                        <h3 class="text-lg font-black text-slate-900">Dry Clean Jas / Blazer</h3>
                        <p class="text-xs text-slate-500 mt-1 mb-4">Perawatan profesional untuk pakaian formal, jas, dan gaun spesial.</p>

                        <div class="mb-4">
                            <span class="text-2xl font-black text-slate-900">Rp 25.000</span>
                            <span class="text-xs text-slate-500 font-bold">/ pcs</span>
                        </div>

                        <ul class="text-xs text-slate-600 space-y-2 mb-6">
                            <li class="flex items-center gap-2"><span>✓</span> Durasi 3 Hari Kerja</li>
                            <li class="flex items-center gap-2"><span>✓</span> Perlindungan Serat Khusus</li>
                            <li class="flex items-center gap-2"><span>✓</span> Hanger &amp; Cover Pelindung</li>
                        </ul>
                    </div>

                    <a href="{{ route('login') }}" class="w-full py-3 bg-slate-900 hover:bg-blue-600 text-white font-bold text-xs rounded-xl text-center transition">
                        Pesan Layanan
                    </a>
                </div>

                <!-- Paket 4 -->
                <div class="bg-slate-50 hover:bg-white rounded-3xl p-6 sm:p-7 border border-slate-200 hover:border-blue-400 hover:shadow-xl transition-all flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-teal-100 text-teal-700 flex items-center justify-center text-2xl font-black mb-4">
                            🛏️
                        </div>
                        <h3 class="text-lg font-black text-slate-900">Bed Cover / Selimut</h3>
                        <p class="text-xs text-slate-500 mt-1 mb-4">Pencucian kapasitas besar untuk selimut, bed cover tebal, dan sprei.</p>

                        <div class="mb-4">
                            <span class="text-2xl font-black text-slate-900">Rp 30.000</span>
                            <span class="text-xs text-slate-500 font-bold">/ pcs</span>
                        </div>

                        <ul class="text-xs text-slate-600 space-y-2 mb-6">
                            <li class="flex items-center gap-2"><span>✓</span> Durasi 2 Hari Kerja</li>
                            <li class="flex items-center gap-2"><span>✓</span> Pengeringan Tuntas Higienis</li>
                            <li class="flex items-center gap-2"><span>✓</span> Kemasan Rapi Bebas Debu</li>
                        </ul>
                    </div>

                    <a href="{{ route('login') }}" class="w-full py-3 bg-slate-900 hover:bg-blue-600 text-white font-bold text-xs rounded-xl text-center transition">
                        Pesan Layanan
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Alur Pemesanan 3 Langkah Mudah -->
    <section id="cara-pesan" class="py-16 sm:py-20 bg-slate-50 border-y border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <span class="text-xs font-black uppercase tracking-wider text-blue-600 block mb-2">Alur Praktis</span>
                <h2 class="text-3xl font-black text-slate-900">Cara Pesan Laundry di ArsyClean</h2>
                <p class="text-sm text-slate-500 mt-2">Hanya 3 langkah mudah dari genggaman ponsel Anda.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-7 rounded-3xl border border-slate-200 shadow-sm text-center space-y-3">
                    <div class="w-14 h-14 mx-auto rounded-2xl bg-blue-100 text-blue-700 flex items-center justify-center font-black text-xl">
                        1
                    </div>
                    <h3 class="font-black text-lg text-slate-900">Tentukan Jadwal &amp; Alamat</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Pilih jenis layanan, masukkan estimasi cucian, dan tentukan waktu penjemputan kurir.
                    </p>
                </div>

                <div class="bg-white p-7 rounded-3xl border border-slate-200 shadow-sm text-center space-y-3">
                    <div class="w-14 h-14 mx-auto rounded-2xl bg-sky-100 text-sky-700 flex items-center justify-center font-black text-xl">
                        2
                    </div>
                    <h3 class="font-black text-lg text-slate-900">Kurir Menjemput Cucian</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Kurir ArsyClean datang ke alamat Anda dan membawa cucian untuk diproses secara higienis.
                    </p>
                </div>

                <div class="bg-white p-7 rounded-3xl border border-slate-200 shadow-sm text-center space-y-3">
                    <div class="w-14 h-14 mx-auto rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-black text-xl">
                        3
                    </div>
                    <h3 class="font-black text-lg text-slate-900">Diantar Kembali Bersih Rapi</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Pakaian Anda diantar kembali dalam kondisi bersih sempurna, wangi, dan tertata rapi.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer Kontak & Informasi -->
    <footer id="kontak" class="bg-slate-900 text-slate-300 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 pb-10 border-b border-slate-800">
                <div class="space-y-3 md:col-span-2">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-blue-600 flex items-center justify-center font-black text-white text-lg">A</div>
                        <span class="font-black text-xl text-white">ArsyClean Laundry</span>
                    </div>
                    <p class="text-xs text-slate-400 max-w-sm leading-relaxed">
                        Penyedia jasa laundry profesional terpercaya dengan sistem terintegrasi untuk kenyamanan pakaian sehari-hari dan kebutuhan bisnis Anda.
                    </p>
                </div>

                <div class="space-y-2 text-xs">
                    <div class="font-bold text-white uppercase tracking-wider text-sm mb-3">Tautan Cepat</div>
                    <div><a href="#beranda" class="hover:text-white transition">Beranda</a></div>
                    <div><a href="#katalog" class="hover:text-white transition">Katalog Layanan</a></div>
                    <div><a href="{{ route('login') }}" class="hover:text-white transition">Masuk Akun</a></div>
                    <div><a href="{{ route('register') }}" class="hover:text-white transition">Daftar Akun Baru</a></div>
                </div>

                <div class="space-y-2 text-xs">
                    <div class="font-bold text-white uppercase tracking-wider text-sm mb-3">Kontak &amp; Dukungan</div>
                    <div>📞 WhatsApp: 0812-3456-7890</div>
                    <div>📍 Jl. Raya Bandung Laundry No. 128</div>
                    <div>🕒 Jam Layanan: 07:00 - 21:00 WIB</div>
                    <div>💳 Pembayaran: QRIS &amp; Transfer Bank</div>
                </div>
            </div>

            <div class="pt-6 text-center text-xs text-slate-500 flex flex-col sm:flex-row items-center justify-between gap-3">
                <span>&copy; {{ date('Y') }} <b>ArsyClean Laundry</b>. Hak Cipta Dilindungi Undang-Undang.</span>
                <span>Laundry Bersih, Higienis &amp; Terpercaya</span>
            </div>
        </div>
    </footer>

</body>
</html>
