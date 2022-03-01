<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SIPPM UNIPA</title>
    <link rel="stylesheet" type="text/css" href={{ asset('/css/slide.css') }} />

    {{-- Style css buat navbar --}}

    <link rel="stylesheet" type="text/css"
        href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
    <link href="{{ asset('/css/material-dashboard.css') }}" rel="stylesheet" />
</head>

<body class="off-canvas-sidebar">
    <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top text-white">
        <div class="container">
            <div class="navbar-wrapper">
                <div class="photo" style=" filter: brightness(200%);">
                    <img src="{{ asset('img/kampusmerdeka.png') }}" height="35" style="padding-right: 5px;">
                    <img src="{{ asset('img/logo.png') }}" height="40">
                </div>
                <a class="navbar-brand" style="font-weight: 400;" href="{{ route('login') }}">SIPPM
                    UNIPA</a>
            </div>
            <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index"
                aria-expanded="false" aria-label="Toggle navigation">
                <span class="sr-only">Toggle navigation</span>
                <span class="navbar-toggler-icon icon-bar"></span>
                <span class="navbar-toggler-icon icon-bar"></span>
                <span class="navbar-toggler-icon icon-bar"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a href="/" class="nav-link">
                            <i class="material-icons">dashboard</i> Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="https://lppm.unipa.ac.id" class="nav-link">
                            <i class="material-icons">explore</i> Tentang LPPM
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('login') ? 'active' : '' }}">
                        <a href="{{ route('login') }}" class="nav-link">
                            <i class="material-icons">fingerprint</i> Login
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('register') ? 'active' : '' }}">
                        <a href="{{ route('register') }}" class="nav-link">
                            <i class="material-icons">app_registration</i> Register
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div id="vanilla-slideshow-container">
        <div id="vanilla-slideshow">
            <div class="vanilla-slide">
                <img src="{{ asset('img/3.JPG') }}" alt="">
                <div class=" bg-text headline">
                    <div class="col-12 mb-1 text-center">
                        <h1 style="font-weight: 500;">SISTEM INFORMASI
                        </h1>
                        <h1 style="font-weight: 500;">PENELITIAN DAN PENGABDIAN KEPADA
                            MASYARAKAT
                        </h1>
                        <h4 class="text-lead text-light mt-2 mb-0">
                            UNIVERSITAS PAPUA
                        </h4>
                    </div>
                </div>
            </div>
            <div class="vanilla-slide">
                <img src="{{ asset('img/slider/1.png') }}" alt="">
                <div class="bg-text headline" id="slide-2">
                    <div class="col-12 mb-1 text-center">
                        <h1 style="font-weight: 500;">Alat Ekstraksi Pati Sagu</h1>
                        <h1 style="font-weight: 400;">Granted Paten </h1>
                        <h4 class="text-lead text-light mt-2 mb-0">
                            IDS000001882
                        </h4>
                    </div>
                </div>
            </div>
            <div class="vanilla-slide">
                <img src="{{ asset('img/slider/2.png') }}" alt="">
                <div class="bg-text headline" id="slide-3">
                    <div class="col-12 mb-1 text-center">
                        <h1 style="font-weight: 500;">Alat Pengering Pati dan Tepung Menggunakan Irradiasi Ultraviolet
                        </h1>
                        <h4 class="text-lead text-light mt-2 mb-0">
                            Terdaftar Paten: S00201810642
                        </h4>
                    </div>
                </div>
            </div>
            <div class="vanilla-slide">
                <img src="{{ asset('img/slider/3.png') }}" alt="">
                <div class="bg-text headline" id="slide-4">
                    <div class="col-12 mb-1 text-center">
                        <h1 style="font-weight: 500;">KKN Mahasiswa Universitas Papua</h1>
                        <h4 class="text-lead text-light mt-2 mb-0">
                            Semester Genap 2020/2021
                        </h4>
                    </div>
                </div>
            </div>
            <div class="vanilla-slide">
                <img src="{{ asset('img/slider/4.png') }}" alt="">
                <div class="bg-text headline" id="slide-5">
                    <div class="col-12 mb-1 text-center">
                        <h1 style="font-weight: 500;">Sampling grouper (guropa) di</h1>
                        <h4 class="text-lead text-light mt-2 mb-0">
                            Pintu Arus, Teluk Triton Kaimana
                        </h4>
                    </div>
                </div>
            </div>
        </div>
        <div id="vanilla-indicators"></div>
        <div id="vanilla-slideshow-previous">
            <img src={{ asset('/img/arrow-previous.png') }} alt="slider arrow">
        </div>
        <div id="vanilla-slideshow-next">
            <img src={{ asset('img/arrow-next.png') }} alt="slider arrow">
        </div>
        <div class="copyright-landing">
            Copyright &copy;
            <script>
                document.write(new Date().getFullYear())
            </script>, LPPM Universitas Papua, All rights reserved.
        </div>
    </div>
    <script src="{{ asset('/material/js/core/jquery.min.js') }}"></script>
    <script src="{{ asset('/material/js/core/bootstrap-material-design.min.js') }}"></script>
    <script src="{{ asset('/material/js/plugins/perfect-scrollbar.jquery.min.js') }}"></script>
    <script src="{{ asset('/material/js/material-dashboard.js?v=2.1.0" type="text/javascript') }}"></script>
    <script src={{ asset('/js/vanillaSlideshow.min.js') }}></script>
    <script>
        vanillaSlideshow.init({
            slideshow: true,
            delay: 5000,
            arrows: true,
            indicators: true,
            random: false,
            animationSpeed: '1s'
        });
    </script>
</body>

</html>
