<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>KSOP - Pelaporan Kejadian Kapal</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inria+Sans:wght@700&display=swap" rel="stylesheet">

    @vite(['resources/scss/app.scss', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Figtree', sans-serif;
            background-color: #f8f9fa;
        }

        .hero-section {
            background-color: #ffffff;
            background-image: url("{{ asset('images/background.png') }}");
            background-size: cover;
            background-position: center;
            padding: 6rem 0;
            margin-top: 56px;
            min-height: 90vh;
            display: flex;
            align-items: center;
        }

        .hero-text {
            text-shadow: none;
            color: #000000;
        }

        .hero-text h1 {
            font-family: 'Jost', sans-serif;
            font-weight: 700;
        }

        .login-card {
            background-color: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 2.5rem;
            border-radius: 0.75rem;
            box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, .15);
        }

        .login-card h3 {
            font-family: 'Inria Sans', sans-serif;
            font-weight: 700;
            color: white;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.25);
        }

        .login-card p {
            color: #e0e0e0;
        }

        .login-card p a {
            color: #87CEFA;
            font-weight: bold;
            text-decoration: none;
        }

        .login-card p a:hover {
            text-decoration: underline;
        }

        .login-card .btn {
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            font-weight: 600;
        }

        .section-padding {
            padding: 4rem 0;
        }

        #learn-more-btn {
            background-color: transparent;
            border: 2px solid #4E4E4E;
            color: #000000;
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
            padding: 1rem 4rem;
            font-family: 'Inria Sans', sans-serif;
            font-weight: 700;
            font-size: 1.22rem;
            border-radius: 1.5rem;
        }

        #learn-more-btn:hover {
            background-color: #190956 !important;
            border-color: #190956 !important;
            color: white !important;
        }

        .login-card .btn-primary {
            background-color: #190956 !important;
            border-color: #190956 !important;
            color: white !important;
        }

        .login-card .btn-primary:hover {
            background-color: #28166F !important;
            border-color: #28166F !important;
        }

        .text-slider-viewport {
            position: relative;
            overflow: hidden;
            min-height: 280px;
        }

        .text-slider-track {
            display: flex;
            height: 100%;
            transition: transform 0.5s ease-in-out;
        }

        .text-slider-item {
            flex: 0 0 100%;
            width: 100%;
            padding: 0 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .text-slider-item ul {
            text-align: center;
        }

        .text-slider-item li {
            margin-bottom: 0.5rem;
            font-size: 1.1rem;
            font-family: 'Figtree', sans-serif;
        }

        .slider-nav {
            position: absolute;
            top: 40%;
            transform: translateY(-50%);
            background-color: rgba(255, 255, 255, 0.8);
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            font-size: 1.5rem;
            font-weight: bold;
            color: #120A30;
            cursor: pointer;
            z-index: 10;
        }

        .slider-nav:hover {
            background-color: #ffffff;
        }

        .slider-nav.prev {
            left: 0;
        }

        .slider-nav.next {
            right: 0;
        }

        .feature-card {
            border: none;
            background: linear-gradient(to bottom, #FFFFFF, #CECECE);
            box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, .1);
            transition: transform .3s ease-in-out, box-shadow .3s ease-in-out, background .3s ease-in-out, color .3s ease-in-out;
            padding: 4rem 2rem;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 1.25rem 2.5rem rgba(0, 0, 0, .25);
            background: linear-gradient(to bottom, #28166F, #0D091D);
            color: white;
        }

        .feature-card:hover .feature-icon,
        .feature-card:hover h3,
        .feature-card:hover p {
            color: white !important;
        }

        .feature-icon {
            font-size: 3rem;
            color: #0d6efd;
            transition: color .3s ease-in-out;
        }

        .section-title {
            color: #2A3A5E;
            font-weight: 600
        }

        #ketentuan-hukum-section {
            background: radial-gradient(circle at right, #28166F 0%, #120A30 70%);
            color: #ffffff;
        }

        #ketentuan-hukum-section .section-title,
        #mengapa-penting .section-title {
            background-color: #E9C217;
            color: #000000;
            font-family: 'Inria Sans', sans-serif;
            font-weight: 700;
            padding: 0.5rem 0.5rem;
            display: inline-block;
            border-radius: 0;
        }

        #mengapa-penting .container {
            min-height: 280px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        /* Penyesuaian untuk tata letak 2 kolom di section mengapa-penting */
        #mengapa-penting .row {
            align-items: center;
            /* Memastikan konten tengah vertikal */
        }

        #mengapa-penting .text-content {
            padding-right: 2rem;
            /* Memberi sedikit jarak antara teks dan gambar */
            text-align: left !important;
            /* Memastikan teks rata kiri */
        }

        #mengapa-penting .text-content h2,
        #mengapa-penting .text-content p {
            text-align: left;
            /* Teks rata kiri */
            margin-left: 0;
            /* Pastikan tidak ada margin tambahan dari `text-center` sebelumnya */
        }

        #mengapa-penting p {
            font-size: 1.35rem;
            line-height: 1.6;
        }

        #mengapa-penting .illustration-img {
            width: 70%;
            /* Ubah nilai ini sesuai keinginan (misal: 60%, 80%) */
            max-width: 70%;
            /* Pastikan tidak melebihi container */
            height: auto;
            display: block;
            margin: auto;
        }

        .footer {
            background: radial-gradient(circle, #211551, #0F0B21);
            color: #ccc;
            position: relative;
            padding-bottom: 2rem;
        }

        .footer-wave-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            transform: translateY(-100%);
            line-height: 0;
        }

        .footer-content {
            padding-top: 3rem;
        }

        .footer h3 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            background-color: #E9C217;
            color: #000000;
            font-family: 'Inria Sans', sans-serif;
            padding: 0.5rem 0.5rem;
            display: inline-block;
            border-radius: 0;
        }

        .footer p {
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .footer hr {
            border-top: 1px solid #444;
        }

        .copyright {
            font-size: 0.8rem;
            color: #888;
        }
    </style>
</head>

<body class="antialiased">
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
            <div class="container"><a class="navbar-brand d-flex align-items-center" href="/"><img
                        src="{{ asset('images/logo_ksop.png') }}" alt="KSOP Logo" height="40"
                        class="d-inline-block me-2">
                    <div><span class="fw-bold">SIKAP</span><small class="d-block text-muted fw-normal"
                            style="font-size: 0.7rem; line-height: 1;">Sistem Informasi Kecelakaan Kapal</small></div>
                </a><button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation"><span
                        class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto align-items-center">
                        <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
                        <li class="nav-item">
                            <a class="nav-link" href="#footer" id="contact-link">Contact</a>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="#">About Us</a></li>@auth<li
                            class="nav-item ms-lg-3"><a href="{{ url('/dashboard') }}"
                        class="btn btn-primary">Dashboard</a></li>@endauth
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <main>
        <section class="hero-section">
            <div class="container">
                <div class="row align-items-center g-5">
                    <div class="col-lg-7 text-start hero-text">
                        <h1 class="display-4 fw-bold">Layanan Pelaporan Kecelakaan Kapal Secara Cepat dan Tepat</h1>
                        <p class="lead my-4">Pelaporan insiden kapal secara realtime untuk meningkatkan keselamatan
                            pelayaran dan respons cepat dari petugas pelabuhan.</p>
                        <a href="#mengapa-penting" class="btn btn-dark btn-lg" id="learn-more-btn">Learn More</a>
                    </div>
                    <div class="col-lg-5">
                        <div class="login-card">
                            <div id="login-form">
                                <h3 class="text-center mb-4">Sign In</h3>
                                <form method="POST" action="{{ route('login') }}">
                                    @csrf
                                    <div class="mb-3">
                                        <input type="email" name="email" class="form-control" placeholder="Email..."
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <input type="password" name="password" class="form-control"
                                            placeholder="Password..." required>
                                    </div>
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary">Sign In</button>
                                    </div>
                                </form>
                                <p class="text-center mt-3 mb-0">
                                    Belum punya akun?
                                    <a href="{{ route('register') }}">Daftar Sini</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="section-padding" id="ketentuan-hukum-section">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8 text-center">
                        <h2 class="mb-4 section-title">Ketentuan Hukum Terkait Layanan KSOP</h2>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="text-slider-viewport">
                            <div class="text-slider-track">
                                <div class="text-slider-item">
                                    <ul class="list-unstyled">
                                        <li><strong>✅ Undang-Undang Nomor 17 Tahun 2008 tentang Pelayaran</strong></li>
                                        <li class="font-figtree">Merupakan dasar hukum utama tentang pelayaran di
                                            Indonesia.</li>
                                        <li class="font-figtree">Mengatur peran Syahbandar, pelabuhan, keselamatan
                                            pelayaran, kapal, dan pengawasan lalu lintas laut.</li>
                                        <li class="font-figtree">Menyebutkan bahwa Syahbandar bertugas atas nama
                                            pemerintah di pelabuhan, termasuk dalam hal penerbitan dokumen kapal,
                                            pengawasan keselamatan dan keamanan pelayaran, serta penegakan hukum.</li>
                                    </ul>
                                </div>
                                <div class="text-slider-item">
                                    <ul class="list-unstyled">
                                        <li><strong>✅ PP Nomor 61 Tahun 2009 tentang Kepelabuhanan</strong></li>
                                        <li class="font-figtree">Mengatur jenis pelabuhan, penyelenggaraan pelabuhan,
                                            serta peran KSOP sebagai penyelenggara pelabuhan dari pemerintah.</li>
                                        <li class="mt-3"><strong>✅ PP Nomor 5 Tahun 2010 tentang Kenavigasian</strong>
                                        </li>
                                        <li class="font-figtree">Mengatur tugas kenavigasian, termasuk peran Syahbandar
                                            dalam menjamin keselamatan dan keamanan pelayaran.</li>
                                    </ul>
                                </div>
                                <div class="text-slider-item">
                                    <ul class="list-unstyled">
                                        <li><strong>✅ Permenhub Nomor PM 36 Tahun 2012...</strong></li>
                                        <li class="font-figtree">Menjelaskan struktur organisasi, tugas pokok, dan
                                            fungsi KSOP secara detail.</li>
                                        <li class="mt-3"><strong>✅ Permenhub Nomor PM 57 Tahun 2015...</strong></li>
                                        <li class="font-figtree">Mengatur standar pelayanan yang wajib diterapkan oleh
                                            KSOP dalam pengelolaan pelabuhan.</li>
                                        <li class="mt-3"><strong>✅ Permenhub Nomor PM 39 Tahun 2017...</strong></li>
                                        <li class="font-figtree">KSOP berperan dalam pemeriksaan dan pelaporan kejadian
                                            awal kecelakaan kapal.</li>
                                    </ul>
                                </div>
                            </div>
                            <button id="prev-slide-btn" class="slider-nav prev">&lt;</button>
                            <button id="next-slide-btn" class="slider-nav next">&gt;</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="section-padding bg-white" id="mengapa-penting">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-6 text-content">
                        <h2 class="mb-4 section-title">Mengapa Sistem Ini Penting?</h2>
                        <p class="text-muted">Sistem Informasi Kejadian Awal hadir untuk meningkatkan kecepatan dan
                            ketepatan dalam pelaporan insiden kapal. Informasi kejadian yang dikirim secara real-time
                            memungkinkan petugas KSOP merespons lebih cepat dan akurat, sehingga potensi bahaya dapat
                            diminimalkan sejak dini. Sistem ini juga mendukung transparansi layanan pelabuhan,
                            memperkuat keselamatan pelayaran, dan menjadi dasar pengambilan keputusan yang lebih
                            efisien.</p>
                    </div>
                    <div class="col-lg-6 d-flex align-items-center justify-content-center">
                        <img src="{{ asset('images/ilustrasi.jpg') }}" alt="Ilustrasi Sistem Maritim"
                            class="illustration-img">
                    </div>
                </div>
            </div>
        </section>

        <section class="pt-0 pb-5 bg-white">
            <div class="container">
                <div class="row text-center g-4">
                    <div class="col-lg-4">
                        <div class="card feature-card h-100">
                            <div class="feature-icon mb-3 mx-auto">
                                <i class="bi bi-bell-fill"></i>
                            </div>
                            <h3 class="h5">Respons Real-time</h3>
                            <p class="text-muted">Laporan langsung diterima oleh petugas KSOP secara instan.</p>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card feature-card h-100">
                            <div class="feature-icon mb-3 mx-auto">
                                <i class="bi bi-anchor"></i>
                            </div>
                            <h3 class="h5">Meningkatkan Keselamatan</h3>
                            <p class="text-muted">Membantu mencegah kecelakaan lebih lanjut dengan respons cepat.</p>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card feature-card h-100">
                            <div class="feature-icon mb-3 mx-auto">
                                <i class="bi bi-bar-chart-line-fill"></i>
                            </div>
                            <h3 class="h5">Pendukung Kebijakan</h3>
                            <p class="text-muted">Data kejadian disimpan sebagai dasar evaluasi dan perbaikan.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <div class="footer-wave-container">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
            <path fill="#0F0B21" fill-opacity="1"
                d="M0,224L48,208C96,192,192,160,288,165.3C384,171,480,213,576,208C672,203,768,149,864,149.3C960,149,1056,203,1152,224C1248,245,1344,235,1392,229.3L1440,224L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z">
            </path>
        </svg>
    </div>
    <footer class="footer" id="footer">
        <div class="container footer-content">
            <div class="row align-items-center">
                <div class="col-md-7">
                    <h3>KSOP</h3>
                    <ul class="list-unstyled footer-contact">
                        <li><strong>Alamat:</strong> Jl. Duyung Raya, Telaga Biru, Kec. Banjarmasin Barat,</li>
                        <li>Kota Banjarmasin, Kalimantan Selatan 70117</li>
                        <li><strong>Jam Operasional:</strong> 24 Jam</li>
                        <li><strong>Telepon:</strong> 14005</li>
                        <li><strong>Fax:</strong> (+6221) 79183947</li>
                        <li><strong>Email:</strong> info@QCIndonesia.com</li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center copyright">
                <p>Copyright © 2025 KSOP | All rights reserved.</p>
            </div>
        </div>
    </footer>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const learnMoreBtn = document.getElementById('learn-more-btn');
            const targetSection = document.getElementById('mengapa-penting');
            if (learnMoreBtn && targetSection) {
                learnMoreBtn.addEventListener('click', function (event) {
                    event.preventDefault();
                    targetSection.scrollIntoView({
                        behavior: 'smooth'
                    });
                });
            }

            const contactLink = document.getElementById('contact-link');
            const footerSection = document.getElementById('footer');
            if (contactLink && footerSection) {
                contactLink.addEventListener('click', function (event) {
                    event.preventDefault();
                    footerSection.scrollIntoView({
                        behavior: 'smooth'
                    });
                });
            }

            const sliderTrack = document.querySelector('.text-slider-track');
            const slides = document.querySelectorAll('.text-slider-item');
            const prevBtn = document.getElementById('prev-slide-btn');
            const nextBtn = document.getElementById('next-slide-btn');

            if (sliderTrack && slides.length > 0) {
                let currentIndex = 0;
                const slideCount = slides.length;
                const slideInterval = 3000;
                let slideTimer;

                function goToSlide(slideIndex) {
                    if (slideIndex < 0) slideIndex = slideCount - 1;
                    if (slideIndex >= slideCount) slideIndex = 0;

                    sliderTrack.style.transform = 'translateX(' + (-currentIndex * 100) + '%)';
                    currentIndex = slideIndex;
                }

                function startSlider() {
                    slideTimer = setInterval(function () {
                        goToSlide(currentIndex + 1);
                    }, slideInterval);
                }

                function resetSliderTimer() {
                    clearInterval(slideTimer);
                    startSlider();
                }

                nextBtn.addEventListener('click', function () {
                    goToSlide(currentIndex + 1);
                    resetSliderTimer();
                });

                prevBtn.addEventListener('click', function () {
                    goToSlide(currentIndex - 1);
                    resetSliderTimer();
                });

                startSlider();
            }
        });
    </script>
</body>

</html>