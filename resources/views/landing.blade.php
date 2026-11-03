
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ArsyClean Laundry | Laundry Jadi Lebih Mudah</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            color: #172b4d;
            background: #ffffff;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        .container {
            width: min(1160px, 90%);
            margin: auto;
        }

        /* NAVBAR */
        .navbar {
            height: 82px;
            display: flex;
            align-items: center;
            background: #fff;
            position: sticky;
            top: 0;
            z-index: 100;
            border-bottom: 1px solid #edf1f7;
        }

        .nav-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo-icon {
            width: 43px;
            height: 43px;
            background: #e8f2ff;
            color: #1768d5;
            border-radius: 13px;
            display: grid;
            place-items: center;
            font-size: 23px;
        }

        .logo-name {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 21px;
            font-weight: 800;
            color: #153b73;
        }

        .logo-name span {
            color: #2679e8;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 34px;
            font-size: 14px;
            font-weight: 600;
            color: #52647d;
        }

        .nav-links a:hover {
            color: #1768d5;
        }

        .nav-action {
            display: flex;
            align-items: center;
            gap: 20px;
            font-size: 14px;
            font-weight: 700;
        }

        .login-link {
            color: #1768d5;
        }

        .btn {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            padding: 13px 23px;
            border-radius: 9px;
            font-size: 14px;
            font-weight: 700;
            transition: .25s;
        }

        .btn-primary {
            background: #1768d5;
            color: white;
            box-shadow: 0 6px 16px rgba(23,104,213,.17);
        }

        .btn-primary:hover {
            background: #1055b5;
            transform: translateY(-2px);
        }

        .btn-outline {
            border: 1px solid #cbdcf1;
            color: #1768d5;
        }

        /* HERO */
        .hero {
            background: linear-gradient(110deg, #f1f7ff 0%, #f8fbff 58%, #e6f1ff 100%);
            padding: 75px 0 80px;
            overflow: hidden;
        }

        .hero-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            align-items: center;
            gap: 55px;
        }

        .hero-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            background: #e2efff;
            color: #1768d5;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 22px;
        }

        .hero h1 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: clamp(36px, 4.5vw, 56px);
            line-height: 1.2;
            letter-spacing: -2px;
            color: #153b73;
            margin-bottom: 22px;
        }

        .hero h1 span {
            color: #2679e8;
        }

        .hero-description {
            color: #65758c;
            font-size: 16px;
            line-height: 1.9;
            max-width: 490px;
            margin-bottom: 30px;
        }

        .hero-buttons {
            display: flex;
            gap: 13px;
            flex-wrap: wrap;
        }

        .hero-note {
            margin-top: 28px;
            color: #718096;
            font-size: 12px;
        }

        .hero-image {
            position: relative;
        }

        .hero-image img {
            width: 100%;
            height: 410px;
            object-fit: cover;
            border-radius: 25px;
            box-shadow: 0 20px 55px rgba(36,75,125,.15);
        }

        .floating-card {
            position: absolute;
            left: -25px;
            bottom: 25px;
            background: white;
            border-radius: 14px;
            padding: 17px 20px;
            box-shadow: 0 10px 30px rgba(30,60,100,.12);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .floating-icon {
            width: 42px;
            height: 42px;
            display: grid;
            place-items: center;
            border-radius: 12px;
            background: #e8f3ff;
            font-size: 21px;
        }

        .floating-card strong {
            display: block;
            font-size: 13px;
            margin-bottom: 4px;
        }

        .floating-card small {
            color: #8390a3;
            font-size: 11px;
        }

        /* SECTION GENERAL */
        section {
            padding: 85px 0;
        }

        .section-heading {
            text-align: center;
            max-width: 620px;
            margin: 0 auto 45px;
        }

        .section-label {
            color: #2679e8;
            text-transform: uppercase;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 1.5px;
            margin-bottom: 13px;
        }

        .section-heading h2 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 33px;
            color: #153b73;
            margin-bottom: 13px;
        }

        .section-heading p {
            font-size: 14px;
            line-height: 1.8;
            color: #7b8798;
        }

        /* SERVICES */
        .services {
            background: #fff;
        }

        .service-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 23px;
        }

        .service-card {
            border: 1px solid #e9eff7;
            border-radius: 17px;
            overflow: hidden;
            background: white;
            transition: .3s;
        }

        .service-card:hover {
            transform: translateY(-7px);
            box-shadow: 0 15px 35px rgba(30,75,130,.10);
        }

        .service-card img {
            width: 100%;
            height: 205px;
            object-fit: cover;
        }

        .service-body {
            padding: 23px;
        }

        .service-body h3 {
            color: #193e73;
            font-size: 19px;
            margin-bottom: 10px;
        }

        .service-body p {
            color: #78869a;
            font-size: 13px;
            line-height: 1.8;
            min-height: 70px;
        }

        .service-link {
            display: inline-block;
            margin-top: 17px;
            color: #1768d5;
            font-size: 13px;
            font-weight: 700;
        }

        /* WHY US */
        .advantages {
            background: #f5f9ff;
        }

        .advantage-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        .advantage-card {
            background: white;
            border-radius: 15px;
            padding: 27px 20px;
            border: 1px solid #edf1f7;
        }

        .advantage-icon {
            width: 48px;
            height: 48px;
            display: grid;
            place-items: center;
            background: #e9f3ff;
            border-radius: 13px;
            font-size: 23px;
            margin-bottom: 19px;
        }

        .advantage-card h3 {
            font-size: 15px;
            color: #1c426f;
            margin-bottom: 9px;
        }

        .advantage-card p {
            font-size: 12px;
            line-height: 1.8;
            color: #8190a3;
        }

        /* HOW IT WORKS */
        .steps-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 25px;
        }

        .step-card {
            text-align: center;
            padding: 20px 15px;
        }

        .step-number {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: #1768d5;
            color: white;
            font-weight: 800;
            display: grid;
            place-items: center;
            margin: 0 auto 17px;
        }

        .step-card h3 {
            color: #193e73;
            font-size: 15px;
            margin-bottom: 9px;
        }

        .step-card p {
            color: #8190a3;
            font-size: 12px;
            line-height: 1.8;
        }

        /* ABOUT */
        .about {
            background: #f5f9ff;
        }

        .about-content {
            display: grid;
            grid-template-columns: .9fr 1.1fr;
            gap: 55px;
            align-items: center;
        }

        .about-image img {
            width: 100%;
            height: 350px;
            object-fit: cover;
            border-radius: 20px;
        }

        .about-text h2 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 32px;
            color: #153b73;
            margin-bottom: 18px;
        }

        .about-text p {
            color: #718096;
            font-size: 14px;
            line-height: 1.9;
            margin-bottom: 20px;
        }

        /* CTA */
        .cta {
            padding: 65px 0;
        }

        .cta-box {
            background: linear-gradient(110deg, #1457ae, #287ee7);
            border-radius: 22px;
            padding: 48px 30px;
            text-align: center;
            color: white;
        }

        .cta-box h2 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 30px;
            margin-bottom: 13px;
        }

        .cta-box p {
            color: #dceaff;
            font-size: 14px;
            margin-bottom: 25px;
        }

        .cta-box .btn {
            background: white;
            color: #1768d5;
        }

        /* FOOTER */
        footer {
            background: #102d52;
            color: white;
            padding: 48px 0 22px;
        }

        .footer-content {
            display: grid;
            grid-template-columns: 1.5fr 1fr 1fr;
            gap: 40px;
            padding-bottom: 35px;
        }

        .footer-brand h3 {
            font-size: 21px;
            margin-bottom: 13px;
        }

        .footer-brand p,
        .footer-column p {
            color: #b7c9df;
            font-size: 12px;
            line-height: 1.9;
        }

        .footer-column h4 {
            font-size: 14px;
            margin-bottom: 16px;
        }

        .footer-column a {
            display: block;
            color: #b7c9df;
            font-size: 12px;
            margin-bottom: 11px;
        }

        .footer-bottom {
            border-top: 1px solid #345070;
            padding-top: 20px;
            text-align: center;
            color: #a8bdd5;
            font-size: 11px;
        }

        /* RESPONSIVE */
        @media (max-width: 850px) {
            .nav-links {
                display: none;
            }

            .hero-content,
            .about-content {
                grid-template-columns: 1fr;
            }

            .hero-image {
                margin-top: 15px;
            }

            .service-grid {
                grid-template-columns: 1fr 1fr;
            }

            .advantage-grid,
            .steps-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 550px) {
            .nav-action {
                gap: 10px;
            }

            .nav-action .btn {
                padding: 10px 13px;
            }

            .logo-name {
                font-size: 17px;
            }

            .hero {
                padding: 55px 0;
            }

            .hero-image img {
                height: 270px;
            }

            .floating-card {
                left: 8px;
            }

            section {
                padding: 60px 0;
            }

            .service-grid,
            .advantage-grid,
            .steps-grid,
            .footer-content {
                grid-template-columns: 1fr;
            }

            .section-heading h2,
            .about-text h2 {
                font-size: 27px;
            }

            .cta-box h2 {
                font-size: 24px;
            }
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <header class="navbar">
        <div class="container nav-content">
            <a href="/" class="logo">
                <div class="logo-icon">♧</div>
                <div class="logo-name">Arsy<span>Clean</span></div>
            </a>

            <nav class="nav-links">
                <a href="#beranda">Beranda</a>
                <a href="#layanan">Layanan</a>
                <a href="#keunggulan">Keunggulan</a>
                <a href="#tentang">Tentang Kami</a>
            </nav>

            <div class="nav-action">
                <a href="{{ url('/login') }}" class="login-link">Masuk</a>
                <a href="{{ url('/login') }}" class="btn btn-primary">Mulai Sekarang</a>
            </div>
        </div>
    </header>

    <main>

        <!-- HERO -->
        <section class="hero" id="beranda">
            <div class="container hero-content">
                <div>
                    <div class="hero-label">✦ Layanan Laundry Lebih Praktis</div>

                    <h1>
                        Urusan Laundry Jadi Lebih <span>Mudah.</span>
                    </h1>

                    <p class="hero-description">
                        Pakaian bersih dan terawat tanpa harus repot.
                        ArsyClean hadir dengan berbagai pilihan layanan
                        laundry yang praktis, mudah, dan sesuai kebutuhanmu.
                    </p>

                    <div class="hero-buttons">
                        <a href="#layanan" class="btn btn-primary">
                            Lihat Layanan →
                        </a>

                        <a href="{{ url('/login') }}" class="btn btn-outline">
                            Masuk Akun
                        </a>
                    </div>

                    <p class="hero-note">
                        Solusi laundry untuk aktivitas sehari-hari.
                    </p>
                </div>

                <div class="hero-image">
                    <img
                        src="https://images.unsplash.com/photo-1604335399105-a0c585fd81a1?w=1000"
                        alt="Layanan laundry ArsyClean">

                    <div class="floating-card">
                        <div class="floating-icon">🧺</div>
                        <div>
                            <strong>Pakaian Bersih, Hati Tenang</strong>
                            <small>Lebih praktis bersama ArsyClean</small>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- SERVICES -->
        <section class="services" id="layanan">
            <div class="container">
                <div class="section-heading">
                    <div class="section-label">Layanan Kami</div>
                    <h2>Pilih Layanan Sesuai Kebutuhanmu</h2>
                    <p>
                        Tersedia beberapa pilihan layanan untuk membantu
                        kebutuhan laundry sehari-hari.
                    </p>
                </div>

                <div class="service-grid">

                    <div class="service-card">
                        <img
                            src="https://images.unsplash.com/photo-1545173168-9f1947eebb7f?w=800"
                            alt="Laundry reguler">

                        <div class="service-body">
                            <h3>Laundry Reguler</h3>
                            <p>
                                Antar pakaian langsung ke outlet dan pilih
                                layanan pencucian sesuai kebutuhanmu.
                            </p>
                            <a href="{{ url('/login') }}" class="service-link">
                                Pesan Layanan →
                            </a>
                        </div>
                    </div>

                    <div class="service-card">
                        <img
                            src="https://images.unsplash.com/photo-1604335399105-a0c585fd81a1?w=800"
                            alt="Laundry antar jemput">

                        <div class="service-body">
                            <h3>Laundry Antar-Jemput</h3>
                            <p>
                                Tidak sempat datang ke outlet? Pesan layanan
                                penjemputan pakaian melalui sistem.
                            </p>
                            <a href="{{ url('/login') }}" class="service-link">
                                Pesan Layanan →
                            </a>
                        </div>
                    </div>

                    <div class="service-card">
                        <img
                            src="https://images.unsplash.com/photo-1626806787461-102c1bfaaea1?w=800"
                            alt="Mesin laundry koin">

                        <div class="service-body">
                            <h3>Laundry Koin Digital</h3>
                            <p>
                                Gunakan mesin laundry secara mandiri dengan
                                pembelian koin digital dan pemindaian QR Code.
                            </p>
                            <a href="{{ url('/login') }}" class="service-link">
                                Lihat Layanan →
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- ADVANTAGES -->
        <section class="advantages" id="keunggulan">
            <div class="container">
                <div class="section-heading">
                    <div class="section-label">Kenapa ArsyClean?</div>
                    <h2>Laundry Lebih Praktis Setiap Hari</h2>
                    <p>
                        Kami menyediakan pilihan layanan yang dirancang
                        untuk memudahkan aktivitas laundry pelanggan.
                    </p>
                </div>

                <div class="advantage-grid">
                    <div class="advantage-card">
                        <div class="advantage-icon">⏱️</div>
                        <h3>Hemat Waktu</h3>
                        <p>
                            Pilihan layanan yang membantu pelanggan
                            mengatur waktu mencuci pakaian.
                        </p>
                    </div>

                    <div class="advantage-card">
                        <div class="advantage-icon">📱</div>
                        <h3>Pemesanan Mudah</h3>
                        <p>
                            Kelola pesanan dan pantau status laundry
                            melalui sistem.
                        </p>
                    </div>

                    <div class="advantage-card">
                        <div class="advantage-icon">🚚</div>
                        <h3>Layanan Antar-Jemput</h3>
                        <p>
                            Pakaian dapat dijemput dan diantarkan
                            sesuai pesanan pelanggan.
                        </p>
                    </div>

                    <div class="advantage-card">
                        <div class="advantage-icon">🧺</div>
                        <h3>Pilihan Beragam</h3>
                        <p>
                            Tersedia layanan reguler, antar-jemput,
                            dan koin digital.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- HOW IT WORKS -->
        <section id="cara-kerja">
            <div class="container">
                <div class="section-heading">
                    <div class="section-label">Cara Menggunakan</div>
                    <h2>Mulai Laundry dalam Beberapa Langkah</h2>
                    <p>
                        Proses penggunaan sistem ArsyClean dibuat sederhana
                        agar pelanggan lebih mudah menggunakan layanan.
                    </p>
                </div>

                <div class="steps-grid">
                    <div class="step-card">
                        <div class="step-number">01</div>
                        <h3>Buat Akun</h3>
                        <p>Daftar atau masuk ke akun ArsyClean.</p>
                    </div>

                    <div class="step-card">
                        <div class="step-number">02</div>
                        <h3>Pilih Layanan</h3>
                        <p>Pilih reguler, antar-jemput, atau koin digital.</p>
                    </div>

                    <div class="step-card">
                        <div class="step-number">03</div>
                        <h3>Lakukan Transaksi</h3>
                        <p>Ikuti proses pemesanan dan pembayaran sesuai layanan.</p>
                    </div>

                    <div class="step-card">
                        <div class="step-number">04</div>
                        <h3>Nikmati Layanan</h3>
                        <p>Pantau proses laundry atau penggunaan mesin.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ABOUT -->
        <section class="about" id="tentang">
            <div class="container about-content">
                <div class="about-image">
                    <img
                        src="https://images.unsplash.com/photo-1517677208171-0bc6725a3e60?w=1000"
                        alt="Aktivitas laundry">
                </div>

                <div class="about-text">
                    <div class="section-label">Tentang ArsyClean</div>

                    <h2>Lebih dari Sekadar Layanan Laundry</h2>

                    <p>
                        ArsyClean Laundry merupakan layanan laundry yang
                        menyediakan berbagai pilihan untuk memenuhi
                        kebutuhan perawatan pakaian pelanggan.
                    </p>

                    <p>
                        Melalui sistem informasi ArsyClean, pelanggan dapat
                        memilih layanan, melakukan pemesanan, melihat status
                        laundry, serta menggunakan fasilitas mesin koin digital.
                    </p>

                    <a href="{{ url('/login') }}" class="btn btn-primary">
                        Kenali ArsyClean →
                    </a>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="cta">
            <div class="container">
                <div class="cta-box">
                    <h2>Siap Membuat Urusan Laundry Lebih Mudah?</h2>
                    <p>
                        Mulai gunakan layanan ArsyClean dan pilih
                        solusi laundry yang sesuai kebutuhanmu.
                    </p>
                    <a href="{{ url('/login') }}" class="btn">
                        Mulai Sekarang →
                    </a>
                </div>
            </div>
        </section>

    </main>

    <!-- FOOTER -->
    <footer>
        <div class="container">
            <div class="footer-content">

                <div class="footer-brand">
                    <h3>ArsyClean Laundry</h3>
                    <p>
                        Solusi layanan laundry yang praktis dengan
                        pilihan layanan sesuai kebutuhan pelanggan.
                    </p>
                </div>

                <div class="footer-column">
                    <h4>Navigasi</h4>
                    <a href="#beranda">Beranda</a>
                    <a href="#layanan">Layanan</a>
                    <a href="#keunggulan">Keunggulan</a>
                    <a href="#tentang">Tentang Kami</a>
                </div>

                <div class="footer-column">
                    <h4>Layanan</h4>
                    <a href="#layanan">Laundry Reguler</a>
                    <a href="#layanan">Laundry Antar-Jemput</a>
                    <a href="#layanan">Laundry Koin Digital</a>
                </div>

            </div>

            <div class="footer-bottom">
                © 2026 ArsyClean Laundry. All rights reserved.
            </div>
        </div>
    </footer>

</body>
</html>
