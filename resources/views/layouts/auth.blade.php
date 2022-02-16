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
                        <a href="{{ route('login') }}" class="nav-link">
                            <i class="material-icons">dashboard</i> Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="https://lppm.unipa.ac.id" class="nav-link">
                            <i class="material-icons">explore</i> Tentang LPPM
                        </a>
                    </li>
                    <li class="nav-item active">
                        <a href="{{ route('register') }}" class="nav-link">
                            <i class="material-icons">app_registration</i> Sign Up
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="wrapper wrapper-full-page">
        <div class="page-header login-page header-filter" filter-color="black"
            style="background-image: url({{ asset('img/bg-image.JPG') }}); background-size: cover; background-position: top center;align-items: center;">
            <!--   you can change the color of the filter page using: data-color="blue | purple | green | orange | red | rose " -->
            <div class="container">
                @yield('content')
            </div>
            <footer class="footer">
                <div class="container">
                    <div class="copyright">
                        Copyright &copy;
                        <script>
                            document.write(new Date().getFullYear())
                        </script>, LPPM Universitas Papua, All rights reserved.
                    </div>
                </div>
            </footer>
        </div>
    </div>

    @yield('modal')
    <!-- Footer -->
    @include('sweetalert::alert')
    @include('layouts.footer')
    @yield('customScript')
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
            setFormValidation('#LoginValidation');
            setFormValidation('#RegisterValidation');
            setFormValidation('#ResetPasswordValidation');
            setFormValidation('#ForgotPasswordValidation');
        });
    </script>

</body>

</html>
