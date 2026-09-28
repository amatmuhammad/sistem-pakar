<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    
    <div class="app-brand demo">
        <a href="{{ route('diagnosa-cbr.form') }}" class="app-brand-link">
            <span class="app-brand-text demo menu-text fw-bold ms-2">Sistem Pakar</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="bx bx-chevron-left d-block d-xl-none align-middle"></i>
        </a>
    </div>

    <div class="menu-divider mt-0"></div>

    <div class="menu-inner-shadow"></div>

    @if(auth()->check())
        <!-- Profile info untuk Admin -->
        <div class="px-3 py-2">
            <div class="d-flex align-items-center gap-2 mb-3">
                <img src="{{ asset('assets/img/avatars/user.jpeg') }}" alt="avatar" class="rounded-circle" width="40" height="40">
                <div>
                    <div class="fw-bold">{{ auth()->user()->name ?? 'Administrator' }}</div>
                    <small class="badge bg-label-primary">Administrator</small>
                </div>
            </div>
        </div>

        <ul class="menu-inner py-1" id="sidebar-menu-list">
            <!-- Dashboards -->
            <li class="menu-item {{ request()->is('dashboard') ? 'active open' : '' }}">
                <a href="{{ route('dashboard') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-home-smile"></i>
                    <div class="text-truncate" data-i18n="Dashboards">Dashboards</div>
                </a>
            </li>

            <!-- Data Utama -->
            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Data Utama</span>
            </li>
            <li class="menu-item {{ request()->is('gejala*') || request()->is('penyakit*') || request()->is('aturan-cf*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-layout"></i>
                    <div class="text-truncate" data-i18n="data utama">Data Utama</div>
                </a>

                <ul class="menu-sub">
                    <li class="menu-item {{ request()->is('gejala*') ? 'active' : '' }}">
                        <a href="{{ route('gejala.index') }}" class="menu-link">
                            <div class="text-truncate" data-i18n="gejala">Gejala</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->is('penyakit*') ? 'active' : '' }}">
                        <a href="{{ route('penyakit.index') }}" class="menu-link">
                            <div class="text-truncate" data-i18n="hama dan penyakit">Hama dan Penyakit</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->is('aturan-cf*') ? 'active' : '' }}">
                        <a href="{{ route('aturan-cf.index') }}" class="menu-link">
                            <div class="text-truncate" data-i18n="aturan certainly factor">Rules Certainty Factor</div>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- CBR & CF -->
            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">CBR & CF</span>
            </li>
            <li class="menu-item {{ request()->is('diagnosa-cbr*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-brain"></i> 
                    <div class="text-truncate" data-i18n="Metodecbr">Metode CBR</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ request()->routeIs('diagnosa-cbr.form') ? 'active' : '' }}">
                        <a href="{{ route('diagnosa-cbr.form') }}" class="menu-link">
                            <div class="text-truncate" data-i18n="Diagnosa">Diagnosa</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('diagnosa-cbr.kasus') ? 'active' : '' }}">
                        <a href="{{ route('diagnosa-cbr.kasus') }}" class="menu-link">
                            <div class="text-truncate" data-i18n="Kasus">Basis Kasus</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('diagnosa-cbr.hasil') ? 'active' : ''}}">
                        <a href="{{ route('diagnosa-cbr.hasil') }}" class="menu-link">
                            <div class="text-truncate" data-i18n="Hasil">Hasil Diagnosa</div>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="menu-item {{ request()->is('diagnosa-cf*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-list-ul"></i>
                    <div class="text-truncate" data-i18n="Authentications">Metode CF</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ request()->routeIs('diagnosa-cf.form') ? 'active' : '' }}">
                        <a href="{{ route('diagnosa-cf.form') }}" class="menu-link">
                            <div class="text-truncate" data-i18n="Basic">Diagnosa</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('diagnosa-cf.hasil') ? 'active' : '' }}">
                        <a href="{{ route('diagnosa-cf.hasil') }}" class="menu-link">
                            <div class="text-truncate" data-i18n="Basic">Hasil Diagnosa</div>
                        </a>
                    </li>
                </ul>
            </li>
            
            <!-- Perbandingan -->
            <li class="menu-header small text-uppercase"><span class="menu-header-text">Perbandingan</span></li>
            <li class="menu-item {{ request()->is('perbandingan-akurasi*') ? 'active open' : '' }}">
                <a href="{{ route('perbandingan.akurasi') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-check-double"></i>
                    <div class="text-truncate" data-i18n="Basic">Perbandingan</div>
                </a>
            </li>

        </ul>

    @else
        <!-- Profile info untuk Guest / Tamu -->
        <div class="px-3 py-2">
            <div class="d-flex align-items-center gap-2 mb-3 p-2 bg-label-success rounded">
                <i class="bx bx-user-circle text-success fs-3"></i>
                <div>
                    <div class="fw-bold text-success">Pengguna Tamu</div>
                    <small class="badge bg-success text-white">Mode Akses Terbatas</small>
                </div>
            </div>
        </div>

        <ul class="menu-inner py-1" id="sidebar-menu-list">
            <!-- Menu Terbatas untuk Guest -->
            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Uji Coba Diagnosa</span>
            </li>
            
            <!-- Diagnosa CBR -->
            <li class="menu-item {{ request()->routeIs('diagnosa-cbr.form') ? 'active' : '' }}">
                <a href="{{ route('diagnosa-cbr.form') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-brain text-success"></i>
                    <div class="text-truncate">Diagnosa CBR</div>
                </a>
            </li>

            <!-- Diagnosa CF -->
            <li class="menu-item {{ request()->routeIs('diagnosa-cf.form') ? 'active' : '' }}">
                <a href="{{ route('diagnosa-cf.form') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-list-ul text-success"></i>
                    <div class="text-truncate">Diagnosa CF</div>
                </a>
            </li>

            <li class="menu-header small text-uppercase mt-4">
                <span class="menu-header-text">Autentikasi</span>
            </li>
            <li class="menu-item">
                <a href="{{ route('guest.logout') }}" class="menu-link text-danger fw-semibold">
                    <i class="menu-icon tf-icons bx bx-log-in-circle text-danger"></i>
                    <div class="text-truncate ">Keluar</div>
                </a>
            </li>
        </ul>
    @endif
</aside>
