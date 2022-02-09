<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.head')
    <style>
        .error {
            margin-left: 54px;
        }

    </style>
</head>

<body class="off-canvas-sidebar">
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NKDMSK6" height="0" width="0"
            style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top text-white">
        <div class="container">
            <div class="navbar-wrapper">
                <a class="navbar-brand" style="font-weight: 400;"
                    href="https://material-dashboard-pro-laravel.creative-tim.com/dashboard">SIPPM
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
                        <a href="https://lppm.unipa.ac.id" class="nav-link">
                            <i class="material-icons">dashboard</i> tentang LPPM
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="material-icons">notifications</i> Pengumuman
                        </a>
                    </li>
                    <!-- <li class="nav-item active">
                        <a href="https://material-dashboard-pro-laravel.creative-tim.com/login" class="nav-link">
                            <i class="material-icons">fingerprint</i> Login
                        </a>
                    </li> -->
                </ul>
            </div>
        </div>
    </nav>
    <div class="wrapper wrapper-full-page">
        <div class="page-header login-page header-filter" filter-color="black"
            style="background-image: url({{ asset('img/bg-image.JPG') }}); background-size: cover; background-position: top center;align-items: center;">
            <!--   you can change the color of the filter page using: data-color="blue | purple | green | orange | red | rose " -->
            <div class="container">
                <div class="row">
                    <div class="col-md-8 col-lg-7 col-xl-7s ml-auto mr-auto mb-1 text-center">
                        <h3 style="font-weight: 500;">SISTEM INFORMASI PENELITIAN DAN PENGABDIAN KEPADA MASYARAKAT
                        </h3>
                        <h4>LEMBAGA PENELITIAN DAN PENGABDIAN KEPADA MASYARAKAT</h4>
                        <strong class="text-lead text-light mt-3 mb-0">
                            UNIVERSITAS PAPUA
                        </strong>
                    </div>
                </div>
                <div class="row mt-2 justify-content-center">
                    <button data-toggle="modal" data-target="#registerModal" class="btn btn-rose btn-round"
                        style="background-color: transparent !important; border: 1px solid white; padding: 10px 25px; margin-left: 10px;">SIGN
                        UP</button>
                    <div class="mx-2">
                    </div>
                    <button data-toggle="modal" data-target="#loginModal" class="btn btn-rose btn-round">SIGN
                        IN</button>
                </div>
            </div>
            <footer class="footer">
                <div class="container">
                    <div class="copyright">
                        &copy;
                        <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
                        <script>
                            document.write(new Date().getFullYear())
                        </script>, made with <i class="material-icons">favorite</i> by
                        <a href="https://www.creative-tim.com" target="_blank">Dev. UPT TIK UNIPA</a> for a better web.
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" role="">
        <div class="modal-dialog modal-login" role="document">
            <div class="modal-content">
                <div class="card card-signup card-plain">
                    <div class="modal-header">
                        <div class="card-header card-header-info text-center">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                <i class="material-icons">clear</i>
                            </button>

                            <h4 class="card-title">Sign In</h4>
                            <div class="social-line">
                                <p class="description text-center text-light">Pastikan email dan password terdaftar!</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-body mt-3">
                        @if ($errors->any())
                            @foreach ($errors->all() as $e)
                                <p class="description text-center text-danger">{{ $e }}</p>
                            @endforeach
                        @endif
                        <form class="form" method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="card-body">
                                <div class="form-group bmd-form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="material-icons">email</i></div>
                                        </div>
                                        <input type="email" class="form-control" name="email" id="email"
                                            placeholder="Email" required autofocus value="{{ old('email') }}">
                                    </div>
                                </div>
                                <div class="form-group bmd-form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="material-icons">lock_outline</i>
                                            </div>
                                        </div>
                                        <input type="password" id="password" name="password" placeholder="Password"
                                            class="form-control" required>
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="submit" class="btn btn-rose btn-link btn-wd btn-lg">Get Started</button>
                    </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Register Modal -->
    <div class="modal fade" id="registerModal" tabindex="-1" role="">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="card card-signup card-plain">
                    <div class="modal-header d-flex justify-content-center">
                        <div class="col-lg-9 card-header card-header-info text-center">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                <i class="material-icons">clear</i>
                            </button>

                            <h4 class="card-title">SIGN UP</h4>
                            <div class="social-line">
                                <p class="description text-center text-light">Pastikan NIDN anda terdaftar!</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-body">
                        @if ($errors->any())
                            @foreach ($errors->all() as $e)
                                <p class="
                                description text-center text-danger">
                                    {{ $e }}</p>
                            @endforeach
                        @endif
                        <form id="RegisterValidation" class="form" method="POST"
                            action="{{ route('login') }}">
                            @csrf
                            <div class="card-body">
                                <div class="form-group bmd-form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="material-icons">person</i>
                                            </div>
                                        </div>
                                        <input type="number" class="form-control" name="nidn" id="nidn"
                                            placeholder="NIDN" required value="{{ old('nidn') }}" number="true">
                                    </div>
                                </div>
                                <div class="form-group bmd-form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="material-icons">email</i>
                                            </div>
                                        </div>
                                        <input type="email" class="form-control" name="email" id="email"
                                            placeholder="Email" required value="{{ old('email') }}">
                                    </div>
                                </div>
                                <div class="form-group bmd-form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="material-icons">lock_outline</i>
                                            </div>
                                        </div>
                                        <input type="password" id="password" name="password" placeholder="Password"
                                            class="form-control" required>
                                    </div>
                                </div>
                                <div class="form-group bmd-form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="material-icons">lock_outline</i>
                                            </div>
                                        </div>
                                        <input type="password" id="password_confirmation" name="password_confirmation"
                                            placeholder="Konfirmasi Password" equalTo="#password"
                                            class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-center">
                                <button type="submit" class="btn btn-rose btn-link btn-wd btn-lg">Continue</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Footer -->
    @include('sweetalert::alert')
    @include('layouts.footer')
    <script>
        function setFormValidation(id) {
            $(id).validate({
                highlight: function(element) {
                    $(element).closest('.form-group').removeClass('has-success').addClass('has-danger');
                    $(element).closest('.form-check').removeClass('has-success').addClass('has-danger');
                },
                success: function(element) {
                    $(element).closest('.form-group').removeClass('has-danger').addClass('has-success');
                    $(element).closest('.form-check').removeClass('has-danger').addClass('has-success');
                },
                errorPlacement: function(error, element) {
                    $(element).closest('.form-group').append(error);
                },
            });
        }

        $(document).ready(function() {
            setFormValidation('#RegisterValidation');
        });
    </script>


</body>

</html>
