@extends('layouts.app')

@section('content')
<div class="d-flex flex-column flex-lg-row" style="min-height: 100vh;">
    <!-- Mobile Topbar Header -->
    <header class="d-lg-none bg-white border-bottom px-3 py-2 d-flex align-items-center justify-content-between sticky-top shadow-sm mobile-topbar-header" style="z-index: 1020; min-height: 56px; height: 56px;">
        <div class="d-flex align-items-center gap-2" style="min-height: 38px;">
            <button class="btn btn-sm btn-light border px-2 py-1 shadow-sm d-inline-flex align-items-center justify-content-center" 
                    type="button" 
                    data-bs-toggle="offcanvas" 
                    data-bs-target="#pegawaiSidebar" 
                    aria-controls="pegawaiSidebar"
                    aria-label="Toggle navigation"
                    style="width: 38px; height: 34px; min-width: 38px;">
                <i class="bi bi-list fs-5 text-dark" style="line-height: 1;"></i>
            </button>
            <img src="{{ asset('favicon-32x32.png') }}" alt="Logo" width="28" height="28" class="rounded" style="width: 28px; height: 28px; object-fit: contain;">
            <div class="lh-1">
                <div class="fw-bold font-mono text-accent" style="font-size: 1rem; line-height: 1.2;">Presensi Pegawai</div>
                <small class="text-dim" style="font-size: 0.72rem; line-height: 1;">{{ Auth::user()->pegawai->nama ?? 'Pegawai' }}</small>
            </div>
        </div>
        @if(Auth::check() && Auth::user()->pegawai)
        <div class="dropdown">
            <button class="btn btn-link p-0 border-0 text-decoration-none" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Menu Profil">
                <img src="{{ Auth::user()->pegawai->foto ? asset('storage/'.Auth::user()->pegawai->foto) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->pegawai->nama).'&background=2563eb&color=fff' }}" 
                     alt="Profile" 
                     width="34"
                     height="34"
                     class="rounded-circle avatar-img-sm" 
                     style="width: 34px; height: 34px; min-width: 34px; object-fit: cover; border: 2px solid var(--app-border);">
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm border mt-1" style="border-radius: 12px; min-width: 200px;">
                <li class="px-3 py-2 border-bottom bg-light">
                    <div class="fw-bold small text-dark">{{ Auth::user()->pegawai->nama }}</div>
                    <div class="text-muted" style="font-size: 0.72rem;">{{ Auth::user()->pegawai->jabatan->jabatan ?? 'Pegawai' }}</div>
                </li>
                <li>
                    <a class="dropdown-item py-2 d-flex align-items-center gap-2 {{ request()->routeIs('pegawai.profile.*') ? 'active' : '' }}" href="{{ route('pegawai.profile.index') }}">
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
    </header>

    <!-- Sidebar (Desktop static, Mobile Offcanvas) -->
    <nav class="sidebar offcanvas-lg offcanvas-start p-4" 
         id="pegawaiSidebar" 
         tabindex="-1" 
         aria-labelledby="pegawaiSidebarLabel">
        <div class="d-flex align-items-center justify-content-between mb-4 mb-lg-5">
            <div class="d-flex align-items-center gap-2">
                <img src="{{ asset('favicon-32x32.png') }}" alt="Logo" width="32" height="32" class="rounded" style="width: 32px; height: 32px; object-fit: contain;">
                <div>
                    <h5 class="font-mono text-accent m-0" id="pegawaiSidebarLabel">Pegawai</h5>
                    <p class="text-dim m-0" style="font-size: 0.85rem;">Presensi App</p>
                </div>
            </div>
            <!-- Close button on mobile -->
            <button type="button" class="btn-close d-lg-none text-reset" data-bs-dismiss="offcanvas" data-bs-target="#pegawaiSidebar" aria-label="Close"></button>
        </div>
        
        <ul class="nav flex-column" style="font-size: 1rem; font-weight: 500;">
            <li class="nav-item mb-2">
                <a class="nav-link text-dim hover-accent d-flex align-items-center gap-2 {{ request()->routeIs('pegawai.dashboard') ? 'active-submenu' : '' }}" href="{{ route('pegawai.dashboard') }}">
                    <i class="bi bi-house-door"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link text-dim hover-accent d-flex align-items-center gap-2 {{ request()->is('pegawai/presensi*') ? 'active-submenu' : '' }}" href="{{ route('pegawai.presensi.index') }}">
                    <i class="bi bi-camera"></i>
                    <span>Presensi</span>
                </a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link text-dim hover-accent d-flex align-items-center gap-2 {{ request()->routeIs('pegawai.riwayat.index') ? 'active-submenu' : '' }}" href="{{ route('pegawai.riwayat.index') }}">
                    <i class="bi bi-calendar-check"></i>
                    <span>Riwayat Presensi</span>
                </a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link text-dim hover-accent d-flex align-items-center gap-2 {{ request()->routeIs('pegawai.ketidakhadiran.create') ? 'active-submenu' : '' }}" href="{{ route('pegawai.ketidakhadiran.create') }}">
                    <i class="bi bi-plus-circle"></i>
                    <span>Ajukan Izin/Sakit</span>
                </a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link text-dim hover-accent d-flex align-items-center gap-2 {{ request()->routeIs('pegawai.ketidakhadiran.riwayat') ? 'active-submenu' : '' }}" href="{{ route('pegawai.ketidakhadiran.riwayat') }}">
                    <i class="bi bi-clock-history"></i>
                    <span>Riwayat Pengajuan</span>
                </a>
            </li>
            <li class="nav-item mb-2 mt-2">
                <a class="nav-link text-dim hover-accent d-flex align-items-center gap-2 {{ request()->routeIs('pegawai.profile.*') ? 'active-submenu' : '' }}" href="{{ route('pegawai.profile.index') }}">
                    <i class="bi bi-person-gear text-primary"></i>
                    <span>Pengaturan Profil</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow-1 p-3 p-md-4 p-lg-5" style="position: relative; overflow-y: auto; min-width: 0; padding-bottom: 80px !important;">
        
        <!-- Header Profil (Desktop) -->
        <div class="d-none d-lg-flex justify-content-end align-items-center mb-4" style="max-width: 1000px; margin: 0 auto; min-height: 56px;">
            @if(Auth::check() && Auth::user()->pegawai)
            <div class="dropdown">
                <button class="btn bg-white border shadow-sm d-flex align-items-center gap-2 py-1 px-3 rounded-pill text-start" 
                        type="button" data-bs-toggle="dropdown" aria-expanded="false" style="border-color: var(--app-border);">
                    <div class="text-end d-none d-md-block lh-sm">
                        <div class="fw-bold text-dark" style="font-size: 0.88rem;">{{ Auth::user()->pegawai->nama }}</div>
                        <small class="text-muted" style="font-size: 0.75rem;">{{ Auth::user()->pegawai->jabatan->jabatan ?? 'Pegawai' }}</small>
                    </div>
                    <img src="{{ Auth::user()->pegawai->foto ? asset('storage/'.Auth::user()->pegawai->foto) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->pegawai->nama).'&background=2563eb&color=fff' }}" 
                         alt="Profile" 
                         width="38"
                         height="38"
                         class="rounded-circle avatar-img-md" 
                         style="width: 38px; height: 38px; min-width: 38px; object-fit: cover; border: 2px solid var(--app-border);">
                    <i class="bi bi-chevron-down text-muted ms-1" style="font-size: 0.75rem;"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border mt-1" style="border-radius: 12px; min-width: 220px;">
                    <li class="px-3 py-2 border-bottom bg-light">
                        <div class="fw-bold small text-dark">{{ Auth::user()->pegawai->nama }}</div>
                        <div class="text-muted" style="font-size: 0.75rem;">{{ Auth::user()->pegawai->jabatan->jabatan ?? 'Pegawai' }}</div>
                    </li>
                    <li>
                        <a class="dropdown-item py-2 d-flex align-items-center gap-2 {{ request()->routeIs('pegawai.profile.*') ? 'active' : '' }}" href="{{ route('pegawai.profile.index') }}">
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
        <x-alert />
        
        <div class="content-wrapper" style="max-width: 1000px; margin: 0 auto;">
            @yield('pegawai-content')
        </div>
    </main>

    <!-- Mobile Bottom Navigation Bar -->
    <nav class="d-lg-none mobile-bottom-nav">
        <a href="{{ route('pegawai.dashboard') }}" class="nav-item {{ request()->routeIs('pegawai.dashboard') ? 'active' : '' }}">
            <i class="bi bi-house-door{{ request()->routeIs('pegawai.dashboard') ? '-fill' : '' }}"></i>
            <span>Beranda</span>
        </a>
        <a href="{{ route('pegawai.presensi.index') }}" class="nav-item {{ request()->is('pegawai/presensi*') ? 'active' : '' }}">
            <i class="bi bi-camera{{ request()->is('pegawai/presensi*') ? '-fill' : '' }}"></i>
            <span>Presensi</span>
        </a>
        <a href="{{ route('pegawai.riwayat.index') }}" class="nav-item {{ request()->routeIs('pegawai.riwayat.*') ? 'active' : '' }}">
            <i class="bi bi-calendar-check{{ request()->routeIs('pegawai.riwayat.*') ? '-fill' : '' }}"></i>
            <span>Riwayat</span>
        </a>
        <a href="{{ route('pegawai.ketidakhadiran.riwayat') }}" class="nav-item {{ request()->is('pegawai/ketidakhadiran*') ? 'active' : '' }}">
            <i class="bi bi-calendar-x{{ request()->is('pegawai/ketidakhadiran*') ? '-fill' : '' }}"></i>
            <span>Izin/Sakit</span>
        </a>
        <a href="{{ route('pegawai.profile.index') }}" class="nav-item {{ request()->routeIs('pegawai.profile.*') ? 'active' : '' }}">
            <i class="bi bi-person{{ request()->routeIs('pegawai.profile.*') ? '-fill' : '' }}"></i>
            <span>Profil</span>
        </a>
    </nav>
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
