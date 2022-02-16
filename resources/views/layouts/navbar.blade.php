<nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
    <div class="container-fluid">
        <div class="navbar-wrapper">
            <div class="navbar-minimize">
                <button id="minimizeSidebar" class="btn btn-just-icon btn-white btn-fab btn-round">
                    <i class="material-icons text_align-center visible-on-sidebar-regular">more_vert</i>
                    <i class="material-icons design_bullet-list-67 visible-on-sidebar-mini">view_list</i>
                </button>
            </div>
            <a class="navbar-brand" href="
              @if (Auth::user()->role_id == 1)
                    {{ route('admin.dashboard') }}
                @elseif (Auth::user()->role_id == 2)
                    {{ route('pengusul.dashboard') }}
                @elseif (Auth::user()->role_id == 3)
                    {{ route('reviewer.dashboard') }}
                    @endif
            ">{{ Auth::user()->role->nama_role }}</a>
        </div>
        <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index"
            aria-expanded="false" aria-label="Toggle navigation">
            <span class="sr-only">Toggle navigation</span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end">
            <ul class="navbar-nav" id="logout">
                <li class="nav-item">
                    <div class="row pt-3 pe-2">
                        <p class="">{{ Auth::user()->dosen->nama }}</p>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link" href="#pablo" id="navbarDropdownProfile" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <i class="material-icons">person</i>
                        <p class="d-lg-none d-md-block">
                            Account
                        </p>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownProfile">
                        <a class="dropdown-item" href="#">Profile</a>
                        <div class="dropdown-divider"></div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <a class="dropdown-item" id="logout-btn" href="{{ route('logout') }}"
                                onclick="event.preventDefault();this.closest('form').submit();">Log
                                out</a>
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>
