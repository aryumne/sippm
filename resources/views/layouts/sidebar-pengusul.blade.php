<div class="sidebar" data-color="rose" data-background-color="black"
    data-image="https://material-dashboard-pro-laravel.creative-tim.com/material/img/sidebar-1.jpg">

    <div class="logo">
        <a href="{{ route('dashboard') }}" class="simple-text logo-mini">
            <i><img style="width:30px" src="{{ asset('img/logo.png') }}"></i>
        </a>
        <a href=" {{ route('dashboard') }}" class="simple-text logo-normal">
            SIPPM UNIPA
        </a>
    </div>
    <div class="sidebar-wrapper">
        <ul class="nav">
            <li class="nav-item active">
                <a class="nav-link" href="{{ route('dashboard') }}">
                    <i class="material-icons">dashboard</i>
                    <p>Dashboard</p>
                </a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" data-toggle="collapse" href="#laravelExample" aria-expanded=&quot;true&quot;>
                    <i class="material-icons">article</i>
                    <p>Proposal
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse show" id="laravelExample">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <span class="sidebar-mini"> UP </span>
                                <span class="sidebar-normal">Usulan Proposal </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <span class="sidebar-mini"> LK </span>
                                <span class="sidebar-normal">Laporan Kemajuan </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <span class="sidebar-mini"> LA </span>
                                <span class="sidebar-normal">Laporan akhir </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <span class="sidebar-mini"> L </span>
                                <span class="sidebar-normal">Luaran </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item ">
                <a class="nav-link" data-toggle="collapse" href="#pagesExamples">
                    <i class="material-icons">picture_as_pdf</i>
                    <p> Laporan Kegiatan
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse show" id="pagesExamples">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <span class="sidebar-mini"> PL </span>
                                <span class="sidebar-normal">Penelitian </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <span class="sidebar-mini"> PG </span>
                                <span class="sidebar-normal">Pengabdian </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item ">
                <a class="nav-link" href="#">
                    <i class="material-icons">person</i>
                    <p>Profile </p>
                </a>
            </li>
            <li class="nav-item ">
                <a class="nav-link" href="#">
                    <i class="material-icons">logout</i>
                    <p>Log Out </p>
                </a>
            </li>
        </ul>
    </div>
</div>
