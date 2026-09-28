@extends('layouts.app')

@section('content')
<div class="d-flex flex-column flex-lg-row" style="min-height: 100vh;">
    <!-- Mobile Topbar Header -->
    <header class="d-lg-none bg-white border-bottom px-3 py-2 d-flex align-items-center justify-content-between sticky-top shadow-sm mobile-topbar-header" style="z-index: 1020; min-height: 56px; height: 56px;">
        <div class="d-flex align-items-center gap-2" style="min-height: 38px;">
            <button class="btn btn-sm btn-light border px-2 py-1 shadow-sm d-inline-flex align-items-center justify-content-center" 
                    type="button" 
                    data-bs-toggle="offcanvas" 
                    data-bs-target="#adminSidebar" 
                    aria-controls="adminSidebar" 
                    aria-label="Toggle navigation"
                    style="width: 38px; height: 34px; min-width: 38px;">
                <i class="bi bi-list fs-5 text-dark" style="line-height: 1;"></i>
            </button>
            <img src="{{ asset('favicon-32x32.png') }}" alt="Logo" width="28" height="28" class="rounded" style="width: 28px; height: 28px; object-fit: contain;">
            <div class="lh-1">
                <div class="fw-bold font-mono text-accent" style="font-size: 1rem; line-height: 1.2;">Admin Panel</div>
                <small class="text-dim" style="font-size: 0.72rem; line-height: 1;">Presensi App</small>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <div class="d-flex align-items-center gap-1 bg-light px-2 py-1 rounded-pill border" style="font-size: 0.75rem;">
                <i class="bi bi-clock text-primary"></i>
                <span id="admin-live-clock-mobile" class="fw-semibold tabular-nums" style="display: inline-block; min-width: 54px; text-align: center;">{{ now()->format('H:i:s') }}</span>
            </div>
            @if(Auth::check())
            <div class="dropdown">
                <button class="btn btn-link p-0 border-0 text-decoration-none" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Menu Profil">
                    <img src="{{ (Auth::user()->pegawai && Auth::user()->pegawai->foto) ? asset('storage/'.Auth::user()->pegawai->foto) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->pegawai->nama ?? Auth::user()->username).'&background=2563eb&color=fff' }}" 
                         alt="Profile" 
                         width="32"
                         height="32"
                         class="rounded-circle avatar-img-sm" 
                         style="width: 32px; height: 32px; min-width: 32px; object-fit: cover; border: 2px solid var(--app-border);">
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border mt-1" style="border-radius: 12px; min-width: 200px;">
                    <li class="px-3 py-2 border-bottom bg-light">
                        <div class="fw-bold small text-dark">{{ Auth::user()->pegawai->nama ?? Auth::user()->username }}</div>
                        <div class="text-muted" style="font-size: 0.72rem;">{{ '@' . Auth::user()->username }} &bull; Admin</div>
                    </li>
                    <li>
                        <a class="dropdown-item py-2 d-flex align-items-center gap-2 {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}" href="{{ route('admin.profile.index') }}">
                            <i class="bi bi-person-gear text-primary"></i>
                            <span>Pengaturan Profil</span>
                        </a>
                    </li>
                    <li><hr class="dropdown-divider my-1"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item py-2 text-danger d-flex align-items-center gap-2">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
            @endif
        </div>
    </header>

    <!-- Sidebar (Desktop static, Mobile Offcanvas) -->
    <nav class="sidebar offcanvas-lg offcanvas-start p-4" 
         id="adminSidebar" 
         tabindex="-1" 
         aria-labelledby="adminSidebarLabel">
        <div class="d-flex align-items-center justify-content-between mb-4 mb-lg-5">
            <div class="d-flex align-items-center gap-2">
                <img src="{{ asset('favicon-32x32.png') }}" alt="Logo" width="32" height="32" class="rounded" style="width: 32px; height: 32px; object-fit: contain;">
                <div>
                    <h5 class="font-mono text-accent m-0" id="adminSidebarLabel">Admin Panel</h5>
                    <p class="text-dim m-0" style="font-size: 0.85rem;">Presensi App</p>
                </div>
            </div>
            <!-- Close button on mobile -->
            <button type="button" class="btn-close d-lg-none text-reset" data-bs-dismiss="offcanvas" data-bs-target="#adminSidebar" aria-label="Close"></button>
        </div>
        
        <ul class="nav flex-column" style="font-size: 1rem; font-weight: 500;">
            <li class="nav-item mb-2"><a class="nav-link text-dim hover-accent {{ request()->routeIs('admin.dashboard') ? 'active-submenu' : '' }}" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            
            <li class="nav-item mb-2">
                <a class="nav-link text-dim hover-accent d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#masterDataMenu" role="button" aria-expanded="{{ request()->is('admin/jabatan*') || request()->is('admin/lokasi-presensi*') || request()->is('admin/pegawai*') ? 'true' : 'false' }}" aria-controls="masterDataMenu">
                    <span>Master Data</span>
                    <i class="bi bi-chevron-down" style="font-size: 0.8rem;"></i>
                </a>
                <div class="collapse {{ request()->is('admin/jabatan*') || request()->is('admin/lokasi-presensi*') || request()->is('admin/pegawai*') ? 'show' : '' }}" id="masterDataMenu">
                    <ul class="nav flex-column ms-3 mt-1" style="font-size: 0.95rem; border-left: 2px solid var(--app-border);">
                        <li class="nav-item"><a class="nav-link text-dim hover-accent py-1 ps-3" href="{{ route('admin.jabatan.index') }}">Jabatan</a></li>
                        <li class="nav-item"><a class="nav-link text-dim hover-accent py-1 ps-3" href="{{ route('admin.lokasi-presensi.index') }}">Lokasi Presensi</a></li>
                        <li class="nav-item"><a class="nav-link text-dim hover-accent py-1 ps-3" href="{{ route('admin.pegawai.index') }}">Data Pegawai</a></li>
                    </ul>
                </div>
            </li>

            <li class="nav-item mb-2">
                <a class="nav-link text-dim hover-accent d-flex justify-content-between align-items-center {{ request()->is('admin/ketidakhadiran*') ? 'text-dark fw-bold' : '' }}" 
                   href="{{ route('admin.ketidakhadiran.index') }}">
                    <span class="d-flex align-items-center gap-2">
                        <i class="bi bi-calendar-x text-danger"></i>
                        <span>Ketidakhadiran</span>
                    </span>
                    @php
                        $pendingCount = \App\Models\Ketidakhadiran::where('status_pengajuan', 'pending')->count();
                    @endphp
                    @if($pendingCount > 0)
                        <span class="badge bg-danger rounded-pill" style="font-size: 0.75rem;">{{ $pendingCount }}</span>
                    @endif
                </a>
            </li>

            <li class="nav-item mb-2">
                <a class="nav-link text-dim hover-accent d-flex justify-content-between align-items-center {{ request()->is('admin/presensi*') ? 'text-dark fw-bold' : '' }}" 
                   data-bs-toggle="collapse" 
                   href="#presensiMenu" 
                   role="button" 
                   aria-expanded="{{ request()->is('admin/presensi*') ? 'true' : 'false' }}" 
                   aria-controls="presensiMenu">
                    <span class="d-flex align-items-center gap-2">
                        <i class="bi bi-clipboard-check text-primary"></i>
                        <span>Presensi</span>
                    </span>
                    <i class="bi bi-chevron-down" style="font-size: 0.8rem;"></i>
                </a>
                <div class="collapse {{ request()->is('admin/presensi*') ? 'show' : '' }}" id="presensiMenu">
                    <ul class="nav flex-column ms-3 mt-1" style="font-size: 0.95rem; border-left: 2px solid var(--app-border);">
                        <li class="nav-item">
                            <a class="nav-link py-2 ps-3 {{ request()->routeIs('admin.presensi.harian') || (request()->routeIs('admin.presensi.index') && request('mode') === 'harian') ? 'active-submenu' : 'text-dim hover-accent' }}" 
                               href="{{ route('admin.presensi.harian') }}">
                                Rekap Harian
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link py-2 ps-3 {{ request()->routeIs('admin.presensi.mingguan') || (request()->routeIs('admin.presensi.index') && request('mode') === 'mingguan') ? 'active-submenu' : 'text-dim hover-accent' }}" 
                               href="{{ route('admin.presensi.mingguan') }}">
                                Rekap Mingguan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link py-2 ps-3 {{ request()->routeIs('admin.presensi.bulanan') || (request()->routeIs('admin.presensi.index') && request('mode', 'bulanan') === 'bulanan') ? 'active-submenu' : 'text-dim hover-accent' }}" 
                               href="{{ route('admin.presensi.bulanan') }}">
                                Rekap Bulanan
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item mb-2 mt-2">
                <a class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('admin.profile.*') ? 'active-submenu' : 'text-dim hover-accent' }}" 
                   href="{{ route('admin.profile.index') }}">
                    <i class="bi bi-person-gear text-primary"></i>
                    <span>Pengaturan Profil</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow-1 p-3 p-md-4 p-lg-5" style="position: relative; overflow-y: auto; min-width: 0;">

        <!-- Header Topbar (Desktop) -->
        <div class="d-none d-lg-flex justify-content-between align-items-center mb-4" style="max-width: 1200px; margin: 0 auto; min-height: 56px;">
            <!-- Live Timezone Widget for Admin -->
            <div class="d-flex align-items-center gap-2 bg-white px-3 py-2 shadow-sm" style="border-radius: 50px; font-size: 0.85rem; border: 1px solid var(--app-border);">
                <i class="bi bi-clock text-primary"></i>
                <span id="admin-live-clock" class="fw-semibold text-dark tabular-nums" style="display: inline-block; min-width: 65px; text-align: center;">{{ now()->format('H:i:s') }}</span>
                <span class="badge bg-primary-subtle text-primary border" style="font-size: 0.75rem;">WIB (Asia/Jakarta)</span>
            </div>

            @if(Auth::check())
            <div class="dropdown">
                <button class="btn bg-white border shadow-sm d-flex align-items-center gap-2 py-1 px-3 rounded-pill text-start" 
                        type="button" data-bs-toggle="dropdown" aria-expanded="false" style="border-color: var(--app-border);">
                    <div class="text-end d-none d-md-block lh-sm">
                        <div class="fw-bold text-dark" style="font-size: 0.88rem;">{{ Auth::user()->pegawai->nama ?? Auth::user()->username }}</div>
                        <small class="text-muted" style="font-size: 0.75rem;">Administrator</small>
                    </div>
                    <img src="{{ (Auth::user()->pegawai && Auth::user()->pegawai->foto) ? asset('storage/'.Auth::user()->pegawai->foto) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->pegawai->nama ?? Auth::user()->username).'&background=2563eb&color=fff' }}" 
                         alt="Profile" 
                         width="38"
                         height="38"
                         class="rounded-circle avatar-img-md" 
                         style="width: 38px; height: 38px; min-width: 38px; object-fit: cover; border: 2px solid var(--app-border);">
                    <i class="bi bi-chevron-down text-muted ms-1" style="font-size: 0.75rem;"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border mt-1" style="border-radius: 12px; min-width: 220px;">
                    <li class="px-3 py-2 border-bottom bg-light">
                        <div class="fw-bold small text-dark">{{ Auth::user()->pegawai->nama ?? Auth::user()->username }}</div>
                        <div class="text-muted" style="font-size: 0.75rem;">{{ '@' . Auth::user()->username }} &bull; Administrator</div>
                    </li>
                    <li>
                        <a class="dropdown-item py-2 d-flex align-items-center gap-2 {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}" href="{{ route('admin.profile.index') }}">
                            <i class="bi bi-person-gear text-primary"></i>
                            <span>Pengaturan Profil</span>
                        </a>
                    </li>
                    <li><hr class="dropdown-divider my-1"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item py-2 text-danger d-flex align-items-center gap-2">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
            @endif
        </div>
        <script>
            (function() {
                const pad = n => (n < 10 ? '0' : '') + n;
                const clockEl = document.getElementById('admin-live-clock');
                const clockMobileEl = document.getElementById('admin-live-clock-mobile');
                function updateAdminClock() {
                    const d = new Date();
                    const s = pad(d.getHours()) + ':' + pad(d.getMinutes()) + ':' + pad(d.getSeconds());
                    if (clockEl) clockEl.textContent = s;
                    if (clockMobileEl) clockMobileEl.textContent = s;
                }
                setInterval(updateAdminClock, 1000);
                updateAdminClock();
            })();
        </script>
        <x-alert />
        
        <div class="content-wrapper" style="max-width: 1200px; margin: 0 auto;">
            @yield('admin-content')
        </div>
    </main>
</div>

<style>
    .hover-accent { color: var(--app-text-dim) !important; transition: all 0.2s; border-radius: 8px; }
    .hover-accent:hover { color: var(--app-accent) !important; background: var(--app-accent-light); padding-left: 1rem; }
    .active-submenu {
        background: #eff6ff !important;
        color: #2563eb !important;
        font-weight: 600 !important;
        border-radius: 8px;
    }
</style>
@endsection
