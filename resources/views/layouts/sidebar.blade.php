<div class="sidebar" data-color="rose" data-background-color="black"
    data-image="{{ asset('img/sidebar.png') }}">

    <div class="logo">
        <a href="#" class="simple-text logo-mini">
            <i><img style="width:30px" src="{{ asset('img/logo.png') }}"></i>
        </a>
        <a href="
            @if (Auth::user()->role_id == 1) {{ route('admin.dashboard') }}
        @elseif (Auth::user()->role_id == 2)
            {{ route('pengusul.dashboard') }}
        @elseif (Auth::user()->role_id == 3)
            {{ route('reviewer.dashboard') }} @endif
            " class="simple-text logo-normal">
            SIPPM UNIPA
        </a>
    </div>
    <div class="sidebar-wrapper">
        <ul class="nav">
            <li class="nav-item {{ request()->routeIs('*dashboard') ? 'active' : '' }}">
                <a class="nav-link" href="
                   @if (Auth::user()->role_id == 1) {{ route('admin.dashboard') }}
                @elseif (Auth::user()->role_id == 2)
                    {{ route('pengusul.dashboard') }}
                @elseif (Auth::user()->role_id == 3)
                    {{ route('reviewer.dashboard') }} @endif
                    ">
                    <i class="material-icons">dashboard</i>
                    <p>Dashboard</p>
                </a>
            </li>
            @if (Auth::user()->role_id <= 2)
                <li
                    class="nav-item {{ request()->routeIs('usulan*') ||request()->routeIs('laporan-kemajuan*') ||request()->routeIs('laporan-akhir*') ||request()->routeIs('publikasi*') ||request()->routeIs('hki*')? 'active': '' }}">
                    <a class="nav-link" data-toggle="collapse" href="#proposal" aria-expanded=&quot;true&quot;>
                        <i class="material-icons">article</i>
                        <p>Proposal
                            <b class="caret"></b>
                        </p>
                    </a>
                    <div class="collapse {{ request()->routeIs('usulan*') ||request()->routeIs('laporan-kemajuan*') ||request()->routeIs('laporan-akhir*') ||request()->routeIs('publikasi*') ||request()->routeIs('hki*') ||request()->routeIs('buku*') ||request()->routeIs('ttg*')? 'show': '' }}"
                        id="proposal">
                        <ul class="nav">
                            <li class="nav-item {{ request()->routeIs('usulan*') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('usulan.index') }} ">
                                    <span class="sidebar-mini"> UP </span>
                                    <span class="sidebar-normal">Usulan Proposal </span>
                                </a>
                            </li>
                            <li class="nav-item {{ request()->routeIs('laporan-kemajuan*') ? 'active' : '' }}">
                                <a class="nav-link" href=" {{ route('laporan-kemajuan.index') }} ">
                                    <span class="sidebar-mini"> LK </span>
                                    <span class="sidebar-normal">Laporan Kemajuan </span>
                                </a>
                            </li>
                            <li class="nav-item {{ request()->routeIs('laporan-akhir*') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('laporan-akhir.index') }}">
                                    <span class="sidebar-mini"> LA </span>
                                    <span class="sidebar-normal">Laporan akhir </span>
                                </a>
                            </li>
                            <li
                                class="nav-item {{ request()->routeIs('publikasi*') ||request()->routeIs('hki*') ||request()->routeIs('buku*') ||request()->routeIs('ttg*')? 'active': '' }}">
                                <a class="nav-link" data-toggle="collapse" href="#luaran">
                                    <i class="material-icons">task</i>
                                    <p> Luaran
                                        <b class="caret"></b>
                                    </p>
                                </a>
                                <div class="collapse {{ request()->routeIs('publikasi*') ||request()->routeIs('hki*') ||request()->routeIs('buku*') ||request()->routeIs('ttg*')? 'show': '' }}"
                                    id="luaran">
                                    <ul class="nav">
                                        <li class="nav-item {{ request()->routeIs('publikasi*') ? 'active' : '' }}">
                                            <a class="nav-link" href="{{ route('publikasi.index') }}">
                                                <span class="sidebar-mini"> P </span>
                                                <span class="sidebar-normal">Publikasi </span>
                                            </a>
                                        </li>
                                        <li class="nav-item {{ request()->routeIs('hki*') ? 'active' : '' }}">
                                            <a class="nav-link" href="{{ route('hki.index') }}">
                                                <span class="sidebar-mini"> H </span>
                                                <span class="sidebar-normal">HKI </span>
                                            </a>
                                        </li>
                                        <li class="nav-item {{ request()->routeIs('buku*') ? 'active' : '' }}">
                                            <a class="nav-link" href="{{ route('buku.index') }}">
                                                <span class="sidebar-mini"> B </span>
                                                <span class="sidebar-normal">Buku </span>
                                            </a>
                                        </li>
                                        <li class="nav-item {{ request()->routeIs('ttg*') ? 'active' : '' }}">
                                            <a class="nav-link" href="{{ route('ttg.index') }}">
                                                <span class="sidebar-mini"> TG </span>
                                                <span class="sidebar-normal">Teknologi Tepat Guna </span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item {{ request()->routeIs('kegiatan*') ? 'active' : '' }}">
                    <a class="nav-link" data-toggle="collapse" href="#laporan">
                        <i class="material-icons">picture_as_pdf</i>
                        <p> Laporan Kegiatan
                            <b class="caret"></b>
                        </p>
                    </a>
                    <div class="collapse {{ request()->routeIs('kegiatan*') ? 'show' : '' }}" id="laporan">
                        <ul class="nav">
                            <li class="nav-item {{ $title == 'Daftar Penelitian' ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('kegiatan.index', 'penelitian') }}">
                                    <span class="sidebar-mini"> PL </span>
                                    <span class="sidebar-normal">Penelitian </span>
                                </a>
                            </li>
                            <li class="nav-item {{ $title == 'Daftar Pkm' ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('kegiatan.index', 'pkm') }}">
                                    <span class="sidebar-mini"> PG </span>
                                    <span class="sidebar-normal">Pengabdian </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endif

            @if (Auth::user()->role_id == 1)
                <li class="nav-item {{ request()->routeIs('adminpenilaian.*') ? 'active' : '' }}">
                    <a class="nav-link" data-toggle="collapse" href="#penilaian">
                        <i class="material-icons">assessment</i>
                        <p> Penilaian
                            <b class="caret"></b>
                        </p>
                    </a>
                    <div class="collapse {{ request()->routeIs('adminpenilaian.*') ? 'show' : '' }}" id="penilaian">
                        <ul class="nav">
                            <li class="nav-item {{ request()->routeIs('adminpenilaian.audits.*') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('adminpenilaian.audits.index') }}">
                                    <span class="sidebar-mini"> PP </span>
                                    <span class="sidebar-normal">Penilaian Proposal </span>
                                </a>
                            </li>
                            <li class="nav-item {{ request()->routeIs('adminpenilaian.monevs.*') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('adminpenilaian.monevs.index') }}">
                                    <span class="sidebar-mini"> MV </span>
                                    <span class="sidebar-normal">Monev </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.reviewers.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.reviewers.index') }}">
                        <i class="material-icons">manage_accounts</i>
                        <p>Reviewers </p>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('schedule.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('schedule.index') }}">
                        <i class="material-icons">date_range</i>
                        <p>Penjadwalan </p>
                    </a>
                </li>
                <li
                    class="nav-item {{ request()->routeIs('dosen.*') || request()->routeIs('faculty.*') || request()->routeIs('prodi.*')? 'active': '' }}">
                    <a class="nav-link" data-toggle="collapse" href="#master">
                        <i class="material-icons">storage</i>
                        <p> Data Master
                            <b class="caret"></b>
                        </p>
                    </a>
                    <div class="collapse {{ request()->routeIs('dosen.*') || request()->routeIs('faculty.*') || request()->routeIs('prodi.*')? 'show': '' }}"
                        id="master">
                        <ul class="nav">
                            <li class="nav-item {{ request()->routeIs('dosen.*') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('dosen.index') }}">
                                    <span class="sidebar-mini"> D </span>
                                    <span class="sidebar-normal">Dosen </span>
                                </a>
                            </li>
                            <li
                                class="nav-item {{ request()->routeIs('faculty.*') || request()->routeIs('prodi.*') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('faculty.index') }}">
                                    <span class="sidebar-mini"> FP </span>
                                    <span class="sidebar-normal">Fakultas & Prodi </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endif

            @if (Auth::user()->role_id == 3)
                <li class="nav-item {{ request()->routeIs('reviewer.audit.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('reviewer.audit.proposals') }}">
                        <i class="material-icons">assignment</i>
                        <p>Review Proposal </p>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('reviewer.monev.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('reviewer.monev.kemajuan') }}">
                        <i class="material-icons">dvr</i>
                        <p>Monitoring & Evaluasi </p>
                    </a>
                </li>
            @endif

            <li class="nav-item  {{ request()->routeIs('editProfile') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('editProfile') }}">
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
