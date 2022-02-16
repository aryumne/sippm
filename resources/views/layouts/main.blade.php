<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.head')
</head>

<body class="">
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NKDMSK6" height="0" width="0"
            style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    <div class="wrapper ">
        <!-- Sidebar -->
        @include('layouts.sidebar')

        <!--Panel Content -->
        <div class="main-panel">

            <!-- Navbar -->
            @include('layouts.navbar')

            <!-- Content -->
            <div class="content">
                @yield('content')
            </div>

            <footer class="footer">
                <div class="container-fluid">
                    <div class="copyright float-right">
                        Copyright &copy;
                        <script>
                            document.write(new Date().getFullYear())
                        </script>, LPPM Universitas Papua, All rights reserved.
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Footer -->
    @yield('modal')
    @include('sweetalert::alert')
    @include('layouts.footer')
    @yield('customSCript')

</body>

</html>
