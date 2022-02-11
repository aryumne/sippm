<div class="sidebar" data-color="rose" data-background-color="black"
    data-image="{{ asset('img/sidebar.png') }}">

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
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#proposal" aria-expanded=&quot;true&quot;>
                    <i class="material-icons">article</i>
                    <p>Proposal
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse" id="proposal">
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
                    </ul>
                </div>
            </li>
            <li class="nav-item ">
                <a class="nav-link" data-toggle="collapse" href="#luaran">
                    <i class="material-icons">task</i>
                    <p> Luaran
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse" id="luaran">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <span class="sidebar-mini"> P </span>
                                <span class="sidebar-normal">Publikasi </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <span class="sidebar-mini"> H </span>
                                <span class="sidebar-normal">HAKI </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <span class="sidebar-mini"> B </span>
                                <span class="sidebar-normal">BUKU </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <span class="sidebar-mini"> TG </span>
                                <span class="sidebar-normal">Teknologi Tepat Guna </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item ">
                <a class="nav-link" data-toggle="collapse" href="#penilaian">
                    <i class="material-icons">assessment</i>
                    <p> Penilaian
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse" id="penilaian">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <span class="sidebar-mini"> PP </span>
                                <span class="sidebar-normal">Penilaian Proposal </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <span class="sidebar-mini"> MV </span>
                                <span class="sidebar-normal">Monev </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#laporan">
                    <i class="material-icons">picture_as_pdf</i>
                    <p> Laporan Kegiatan
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse" id="laporan">
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
                    <i class="material-icons">date_range</i>
                    <p>Penjadwalan </p>
                </a>
            </li>
            <li class="nav-item ">
                <a class="nav-link" href="#">
                    <i class="material-icons">assignment_ind</i>
                    <p>Data Dosen </p>
                </a>
            </li>
            <li class="nav-item ">
                <a class="nav-link" href="#">
                    <i class="material-icons">person</i>
                    <p>Profile </p>
                </a>
            </li>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <li class="nav-item ">
                    <a class="nav-link" href="{{ route('logout') }}"
                        onclick="event.preventDefault();this.closest('form').submit();">
                        <i class="material-icons">logout</i>
                        <p>Log Out </p>
                    </a>
                </li>
            </form>
        </ul>
    </div>
</div>
