<nav
    class="layout-navbar container-xxl navbar-detached navbar navbar-expand-xl align-items-center bg-navbar-theme"
    id="layout-navbar" style="background-image: url({{ asset('assets/img/layouts/bgnav.jpg') }}); background-size: cover; background-position: center;">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
            <i class="icon-base bx bx-menu icon-md"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center justify-content-between" id="navbar-collapse">
        <div class="d-flex align-items-center gap-2">
            @if(session('is_guest') && !auth()->check())
                <span class="badge bg-success px-3 py-2 fs-7 shadow-sm">
                    <i class="bx bx-user-check me-1"></i> Mode Akses Tamu (Uji Coba Diagnosa)
                </span>
            @endif
        </div>

        <ul class="navbar-nav flex-row align-items-center ms-md-auto">
            <!-- User / Guest Profile -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="{{ asset('assets/img/avatars/1.png') }}" alt class="w-px-40 h-auto rounded-circle" />
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    @if(auth()->check())
                        <li>
                            <a class="dropdown-item" href="#">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar avatar-online">
                                            <img src="{{ asset('assets/img/avatars/1.png') }}" alt class="w-px-40 h-auto rounded-circle" />
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">{{ Auth::user()->name }}</h6>
                                        <small class="text-body-secondary">{{ Auth::user()->email }}</small>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <div class="dropdown-divider my-1"></div>
                        </li>
                        <li>
                            <a class="dropdown-item" href="javascript:void(0);" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="icon-base bx bx-power-off icon-md me-3 text-danger"></i>
                                <span class="text-danger fw-semibold">Log Out</span>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    @else
                        <li>
                            <a class="dropdown-item" href="#">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar avatar-online">
                                            <img src="{{ asset('assets/img/avatars/1.png') }}" alt class="w-px-40 h-auto rounded-circle" />
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">Pengguna Tamu</h6>
                                        <small class="text-success fw-semibold">Akses Diagnosa Terbatas</small>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <div class="dropdown-divider my-1"></div>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('guest.logout') }}">
                                <i class="icon-base bx bx-log-in icon-md me-3 text-primary"></i>
                                <span class="text-primary fw-semibold">Login Admin</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
            <!--/ User -->
        </ul>
    </div>
</nav>
