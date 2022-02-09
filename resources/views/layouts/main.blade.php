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
                    <nav class="float-left">
                        <ul>
                            <li>
                                <a href="https://www.creative-tim.com">
                                    Creative Tim
                                </a>
                            </li>
                            <li>
                                <a href="https://creative-tim.com/presentation">
                                    About Us
                                </a>
                            </li>
                            <li>
                                <a href="http://blog.creative-tim.com">
                                    Blog
                                </a>
                            </li>
                            <li>
                                <a href="https://www.creative-tim.com/license">
                                    Licenses
                                </a>
                            </li>
                        </ul>
                    </nav>
                    <div class="copyright float-right">
                        &copy;
                        <script>
                            document.write(new Date().getFullYear())
                        </script>, made with <i class="material-icons">favorite</i> by
                        <a href="https://www.creative-tim.com" target="_blank">Creative Tim</a> and <a
                            href="https://www.updivision.com" target="_blank">UPDIVISION</a> for a better web.
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Footer -->
    @include('sweetalert::alert')
    @include('layouts.footer')
    @yield('customSCript')

</body>

</html>
