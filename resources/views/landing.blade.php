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
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        <span>Buka Dashboard</span>
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
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-wider bg-blue-100 text-blue-900">
                        <svg class="w-4 h-4 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Layanan Laundry Antar-Jemput No. 1</span>
                    </div>
                    <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black text-slate-900 leading-tight tracking-tight">
                        Laundry Jadi Lebih <span class="text-blue-600">Mudah, Cepat</span> &amp; Terawat.
                    </h1>
                    <p class="text-base sm:text-lg text-slate-600 leading-relaxed max-w-xl mx-auto lg:mx-0 font-medium">
                        Tidak perlu repot mencuci sendiri. Pesan pick up laundry langsung dari rumah Anda, pantau proses pencucian realtime, dan pakaian wangi rapi siap dipakai.
                    </p>

                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 pt-2">
                        @auth
                            <a href="{{ route('pelanggan.dashboard') }}" class="w-full sm:w-auto px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white font-black text-base rounded-2xl shadow-xl shadow-blue-600/30 transition-all text-center flex items-center justify-center gap-2">
                                <span>Pesan Laundry Sekarang</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white font-black text-base rounded-2xl shadow-xl shadow-blue-600/30 transition-all text-center flex items-center justify-center gap-2">
                                <span>Mulai Pesan Laundry</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        @endauth
                        <a href="#katalog" class="w-full sm:w-auto px-7 py-4 bg-slate-100 hover:bg-slate-200 text-slate-700 font-extrabold text-base rounded-2xl transition text-center">
                            Lihat Katalog Layanan
                        </a>
                    </div>

                    <!-- Mini Stats -->
                    <div class="grid grid-cols-3 gap-4 pt-8 border-t border-slate-200 max-w-md mx-auto lg:mx-0 text-center sm:text-left">
                        <div>
                            <div class="text-2xl sm:text-3xl font-black text-slate-900">24 Jam</div>
                            <div class="text-xs font-bold text-slate-500 uppercase mt-0.5">Layanan Online</div>
                        </div>
                        <div>
                            <div class="text-2xl sm:text-3xl font-black text-blue-600">100%</div>
                            <div class="text-xs font-bold text-slate-500 uppercase mt-0.5">Higienis &amp; Bersih</div>
                        </div>
                        <div>
                            <div class="text-2xl sm:text-3xl font-black text-emerald-600">QRIS</div>
                            <div class="text-xs font-bold text-slate-500 uppercase mt-0.5">by_onest / Tunai</div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-5" id="keunggulan">
                    <div class="relative bg-gradient-to-tr from-[#0f2a5c] to-blue-800 rounded-3xl p-8 sm:p-10 text-white shadow-2xl border border-blue-900/40">
                        <div class="text-xs font-bold text-sky-200 uppercase tracking-widest mb-2">Mengapa ArsyClean?</div>
                        <h3 class="text-2xl font-black mb-6">Pilihan Solusi Laundry Terlengkap</h3>

                        <div class="space-y-4">
                            <div class="flex items-start gap-4 p-4 rounded-2xl bg-white/10 backdrop-blur-xs border border-white/10">
                                <div class="w-10 h-10 rounded-xl bg-white/15 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 text-sky-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm">Pick Up &amp; Delivery</h4>
                                    <p class="text-xs text-blue-100 mt-0.5">Kurir siap jemput dan antar kembali ke pintu rumah Anda.</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4 p-4 rounded-2xl bg-white/10 backdrop-blur-xs border border-white/10">
                                <div class="w-10 h-10 rounded-xl bg-white/15 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 text-sky-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm">Deterjen &amp; Pewangi Premium</h4>
                                    <p class="text-xs text-blue-100 mt-0.5">Pakaian higienis, serat kain tetap awet, dan wangi tahan lama.</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4 p-4 rounded-2xl bg-white/10 backdrop-blur-xs border border-white/10">
                                <div class="w-10 h-10 rounded-xl bg-white/15 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 text-sky-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm">Pelacakan Status Realtime</h4>
                                    <p class="text-xs text-blue-100 mt-0.5">Cek status cucian bertahap langsung dari aplikasi Anda.</p>
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
                <p class="text-base text-slate-500 mt-2 font-medium">
                    Pilih paket layanan laundry sesuai dengan kebutuhan Anda. Tarif transparan tanpa biaya tersembunyi.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @php
                    $listLayanan = isset($layanans) && $layanans->isNotEmpty() ? $layanans : collect([]);
                @endphp
                @forelse($listLayanan as $layanan)
                    @php
                        $isPickup = str_contains(strtolower($layanan->nama_layanan . $layanan->jenis_layanan), 'antar') || str_contains(strtolower($layanan->nama_layanan . $layanan->jenis_layanan), 'pickup');
                        $isKoin = strtolower($layanan->jenis_layanan) === 'koin';
                    @endphp
                    <div class="bg-white rounded-3xl p-6 sm:p-7 border {{ $isPickup ? 'border-2 border-blue-500 bg-blue-50/30 shadow-md' : 'border-slate-200 bg-slate-50' }} hover:border-blue-400 hover:shadow-xl transition-all flex flex-col justify-between group">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-700 flex items-center justify-center font-black">
                                    @if($isPickup)
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                                    @elseif($isKoin)
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    @else
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                    @endif
                                </div>
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider {{ $isPickup ? 'bg-orange-100 text-orange-950' : 'bg-slate-200 text-slate-800' }}">
                                    {{ $layanan->jenis_layanan }}
                                </span>
                            </div>

                            <h3 class="text-lg font-black text-slate-900 group-hover:text-blue-600 transition-colors">
                                {{ $layanan->nama_layanan }}
                            </h3>
                            <p class="text-xs text-slate-500 mt-1 mb-4 line-clamp-2 font-medium">
                                {{ $layanan->deskripsi ?? 'Pencucian profesional higienis dengan pewangi pilihan.' }}
                            </p>

                            <div class="mb-5">
                                <span class="text-2xl font-black text-slate-900">Rp {{ number_format($layanan->harga, 0, ',', '.') }}</span>
                                <span class="text-xs text-slate-500 font-bold">/ {{ $layanan->satuan }}</span>
                            </div>
                        </div>

                        <div>
                            @auth
                                <a href="{{ route('pelanggan.layanan') }}"
                                   class="block w-full py-3 {{ $isPickup ? 'bg-blue-600 hover:bg-blue-700 text-white' : 'bg-slate-900 hover:bg-blue-600 text-white' }} font-bold text-xs rounded-xl text-center shadow-xs transition">
                                    Pesan Sekarang &rarr;
                                </a>
                            @else
                                <a href="{{ route('login') }}"
                                   class="block w-full py-3 {{ $isPickup ? 'bg-blue-600 hover:bg-blue-700 text-white' : 'bg-slate-900 hover:bg-blue-600 text-white' }} font-bold text-xs rounded-xl text-center shadow-xs transition">
                                    Pesan Layanan
                                </a>
                            @endauth
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center text-slate-400 font-bold">
                        Memuat data paket layanan laundry...
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Alur Pemesanan 3 Langkah Mudah -->
    <section id="cara-pesan" class="py-16 sm:py-20 bg-slate-50 border-y border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <span class="text-xs font-black uppercase tracking-wider text-blue-600 block mb-2">Alur Praktis</span>
                <h2 class="text-3xl font-black text-slate-900">Cara Pesan Laundry di ArsyClean</h2>
                <p class="text-sm text-slate-500 mt-2 font-medium">Hanya 3 langkah mudah dari genggaman ponsel Anda.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-7 rounded-3xl border border-slate-200 shadow-sm text-center space-y-3">
                    <div class="w-14 h-14 mx-auto rounded-2xl bg-blue-100 text-blue-700 flex items-center justify-center font-black text-xl">
                        1
                    </div>
                    <h3 class="font-black text-lg text-slate-900">Tentukan Jadwal &amp; Pakaian</h3>
                    <p class="text-xs text-slate-500 leading-relaxed font-medium">
                        Pilih jenis layanan, masukkan rincian jenis pakaian Anda, dan tentukan jam penjemputan kurir.
                    </p>
                </div>

                <div class="bg-white p-7 rounded-3xl border border-slate-200 shadow-sm text-center space-y-3">
                    <div class="w-14 h-14 mx-auto rounded-2xl bg-sky-100 text-sky-700 flex items-center justify-center font-black text-xl">
                        2
                    </div>
                    <h3 class="font-black text-lg text-slate-900">Kurir Menjemput Cucian</h3>
                    <p class="text-xs text-slate-500 leading-relaxed font-medium">
                        Kurir ArsyClean datang ke alamat rumah Anda dan membawa cucian untuk diproses secara higienis.
                    </p>
                </div>

                <div class="bg-white p-7 rounded-3xl border border-slate-200 shadow-sm text-center space-y-3">
                    <div class="w-14 h-14 mx-auto rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-black text-xl">
                        3
                    </div>
                    <h3 class="font-black text-lg text-slate-900">Diantar Kembali Bersih Rapi</h3>
                    <p class="text-xs text-slate-500 leading-relaxed font-medium">
                        Pakaian Anda diantar kembali dalam kondisi bersih sempurna, wangi, dan tertata rapi.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer Kontak & Informasi -->
    <footer id="kontak" class="bg-[#091733] text-slate-300 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 pb-10 border-b border-slate-800">
                <div class="space-y-3 md:col-span-2">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-blue-600 flex items-center justify-center font-black text-white text-lg">A</div>
                        <span class="font-black text-xl text-white">ArsyClean Laundry</span>
                    </div>
                    <p class="text-xs text-slate-400 max-w-sm leading-relaxed font-medium">
                        Penyedia jasa laundry profesional terpercaya dengan sistem terintegrasi untuk kenyamanan pakaian sehari-hari dan kebutuhan bisnis Anda.
                    </p>
                </div>

                <div class="space-y-2 text-xs">
                    <div class="font-bold text-white uppercase tracking-wider text-sm mb-3">Tautan Cepat</div>
                    <div><a href="#beranda" class="hover:text-white transition font-medium">Beranda</a></div>
                    <div><a href="#katalog" class="hover:text-white transition font-medium">Katalog Layanan</a></div>
                    <div><a href="{{ route('login') }}" class="hover:text-white transition font-medium">Masuk Akun</a></div>
                    <div><a href="{{ route('register') }}" class="hover:text-white transition font-medium">Daftar Akun Baru</a></div>
                </div>

                <div class="space-y-2 text-xs font-medium">
                    <div class="font-bold text-white uppercase tracking-wider text-sm mb-3">Kontak &amp; Outlet</div>
                    <div>WhatsApp: 0812-3456-7890</div>
                    <div>Lokasi: Jl. Raya Laundry ArsyClean No. 128</div>
                    <div>Jam Operasional: 07:00 - 21:00 WIB</div>
                    <div>Metode Bayar: QRIS Manual by_onest &amp; Tunai</div>
                </div>
            </div>

            <div class="pt-6 text-center text-xs text-slate-400 flex flex-col sm:flex-row items-center justify-between gap-3">
                <span>&copy; {{ date('Y') }} <b>ArsyClean Laundry</b>. Hak Cipta Dilindungi Undang-Undang.</span>
                <span>Laundry Bersih, Higienis &amp; Terpercaya</span>
            </div>
        </div>
    </footer>

</body>
</html>
