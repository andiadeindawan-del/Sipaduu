<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Pemateri') — Sistem Pelatihan SDM</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/Lambang Pendidikan Sirkular Biru Hijau.png') }}?v={{ @filemtime(public_path('assets/images/Lambang Pendidikan Sirkular Biru Hijau.png')) }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/images/Lambang Pendidikan Sirkular Biru Hijau.png') }}?v={{ @filemtime(public_path('assets/images/Lambang Pendidikan Sirkular Biru Hijau.png')) }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

        :root { 
            --sidebar-width: 260px; 
            --accent: #4a6cf7; 
            --sidebar-bg: #ffffff;
            --sidebar-border: #e8edf5;
            --sidebar-hover: rgba(74, 108, 247, 0.06);
            --sidebar-active: rgba(74, 108, 247, 0.1);
            --sidebar-text: #6b7a8f;
            --sidebar-text-hover: #1a2332;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #f7f9fc;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-size: 0.92rem;
            color: #1a2332;
        }

        /* ============================================================ */
        /* SIDEBAR */
        /* ============================================================ */
        #sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--sidebar-bg);
            border-right: 1px solid var(--sidebar-border);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
            transition: transform .3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.03);
        }

        #sidebar .sidebar-content {
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow: hidden;
            padding: 1.25rem 0.75rem;
        }

        /* BRAND */
        #sidebar .brand {
            padding: 0 0.75rem 1.25rem;
            color: #1a2332;
            font-weight: 700;
            font-size: 1.1rem;
            border-bottom: 1px solid var(--sidebar-border);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            flex-shrink: 0;
        }

        /* SIDEBAR NAVIGATION */
        #sidebar .sidebar-nav {
            flex: 1 1 auto;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 0.5rem 0 1rem;
            min-height: 0;
        }

        #sidebar .sidebar-nav::-webkit-scrollbar {
            width: 3px;
        }
        #sidebar .sidebar-nav::-webkit-scrollbar-track {
            background: transparent;
        }
        #sidebar .sidebar-nav::-webkit-scrollbar-thumb {
            background: var(--sidebar-border);
            border-radius: 4px;
        }

        #sidebar .nav-label {
            color: #a0aec0;
            font-size: .6rem;
            font-weight: 600;
            letter-spacing: .08em;
            text-transform: uppercase;
            padding: .6rem 0.75rem .3rem;
        }

        #sidebar .nav-link {
            color: var(--sidebar-text);
            padding: .5rem 0.75rem;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: .75rem;
            text-decoration: none;
            transition: all .15s ease;
            cursor: pointer;
            font-size: .88rem;
            font-weight: 500;
            position: relative;
        }

        #sidebar .nav-link:hover {
            color: var(--sidebar-text-hover);
            background: var(--sidebar-hover);
        }

        #sidebar .nav-link.active {
            color: var(--accent);
            background: var(--sidebar-active);
        }

        #sidebar .nav-link i { 
            font-size: 1.05rem; 
            width: 22px; 
            text-align: center;
            flex-shrink: 0;
            color: #a0aec0;
            transition: color 0.2s ease;
        }

        #sidebar .nav-link:hover i,
        #sidebar .nav-link.active i {
            color: var(--accent);
        }

        #sidebar .nav-link[href*="dashboard"] i { color: #4e9af1; }
        #sidebar .nav-link[href*="trainings"] i { color: #ff9f43; }
        #sidebar .nav-link[href*="materi"] i { color: #e83e8c; }
        #sidebar .nav-link[href*="quiz"] i { color: #fd7e14; }
        #sidebar .nav-link[href*="survey"] i { color: #00cfe8; }
        #sidebar .nav-link[href*="dokumentasi"] i { color: #28c76f; }
        #sidebar .nav-link[href*="absen"] i { color: #6f42c1; }
        #sidebar .nav-link[href*="profile"] i { color: #adb5bd; }
        #sidebar .nav-link .bi-journal-bookmark { color: #ff9f43; }

        #sidebar .nav-link .chevron {
            margin-left: auto;
            transition: transform .25s ease;
            font-size: .7rem;
            color: #a0aec0;
        }

        #sidebar .nav-link .chevron.open {
            transform: rotate(90deg);
        }

        /* ============================================================ */
        /* SUBMENU */
        /* ============================================================ */
        #sidebar .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height .35s ease;
        }

        #sidebar .submenu.open {
            max-height: 500px;
        }

        #sidebar .submenu .nav-link {
            padding: .4rem 0.75rem .4rem 2.75rem;
            font-size: .82rem;
            font-weight: 400;
            color: var(--sidebar-text);
        }

        #sidebar .submenu .nav-link::before {
            content: '';
            width: 4px;
            height: 4px;
            border-radius: 50%;
            background: #a0aec0;
            display: inline-block;
            margin-right: 0.6rem;
            opacity: 0.5;
            transition: all 0.2s ease;
        }

        #sidebar .submenu .nav-link:hover {
            color: var(--sidebar-text-hover);
            background: var(--sidebar-hover);
        }

        #sidebar .submenu .nav-link.active {
            color: var(--accent);
            background: var(--sidebar-active);
        }

        #sidebar .submenu .nav-link.active::before {
            background: var(--accent);
            opacity: 1;
        }

        /* USER INFO */
        #sidebar .user-info {
            padding: .9rem 0.75rem 0;
            border-top: 1px solid var(--sidebar-border);
            flex-shrink: 0;
        }

        #sidebar .user-info .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(74, 108, 247, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--accent);
            font-weight: 600;
            font-size: 0.85rem;
            flex-shrink: 0;
        }

        #sidebar .user-info .user-name {
            color: #1a2332;
            font-weight: 600;
            font-size: .85rem;
        }

        #sidebar .user-info .user-role {
            color: #a0aec0;
            font-size: .7rem;
        }

        #sidebar .user-info .badge {
            background: #0dcaf0;
            font-weight: 500;
            font-size: .65rem;
            padding: .2rem .6rem;
            border-radius: 20px;
            color: #fff;
        }

        #sidebar .user-info .btn-logout {
            color: var(--sidebar-text);
            border: 1px solid var(--sidebar-border);
            background: transparent;
            font-size: .75rem;
            padding: .35rem .75rem;
            border-radius: 8px;
            transition: all .2s ease;
            cursor: pointer;
        }

        #sidebar .user-info .btn-logout:hover {
            color: #ea5455;
            border-color: #ea5455;
            background: rgba(234, 84, 85, 0.06);
        }

        /* MAIN CONTENT */
        #main {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        #topbar {
            background: #fff;
            border-bottom: 1px solid var(--sidebar-border);
            padding: .5rem 2rem;
            position: sticky;
            top: 0;
            z-index: 99;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .75rem;
        }

        .page-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
            padding: 1.5rem 2rem 0;
        }

        .page-heading .eyebrow {
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: 600;
            color: var(--accent);
            letter-spacing: 0.05em;
            margin-bottom: 0.25rem;
        }

        .page-heading .page-icon {
            font-size: 1.75rem;
            color: var(--accent);
            margin-right: 0.75rem;
        }

        .page-heading-copy {
            display: flex;
            align-items: center;
        }

        /* METRIC CARDS */
        .metric-card {
            background: #fff;
            border-radius: .75rem;
            padding: 1.25rem;
            box-shadow: 0 1px 4px rgba(0,0,0,.06);
            display: flex;
            flex-direction: column;
            gap: .5rem;
            transition: transform .2s ease, box-shadow .2s ease;
            height: 100%;
        }
        .metric-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,.08);
        }
        .metric-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .metric-label {
            font-size: .75rem;
            color: #8a93a3;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .03em;
        }
        .metric-icon { color: #c3cad6; font-size: 1.3rem; }
        .metric-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1a2236;
        }
        .metric-meta {
            font-size: .75rem;
            color: #8a93a3;
            display: flex;
            gap: .35rem;
        }

        /* PANEL */
        .panel {
            background: #fff;
            border-radius: .75rem;
            box-shadow: 0 1px 4px rgba(0,0,0,.06);
            overflow: hidden;
        }
        .panel-header {
            padding: .9rem 1.25rem;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: .75rem;
        }
        .section-title {
            display: flex;
            align-items: center;
            gap: .5rem;
            margin: 0;
            font-size: 1rem;
            font-weight: 600;
        }
        .section-title i { color: var(--accent); }

        /* TABLE */
        .table th {
            font-size: .75rem;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: #6c757d;
            border-top: none;
            font-weight: 600;
        }
        .table td { vertical-align: middle; }

        /* BADGES */
        .badge-draft      { background: #e9ecef; color: #495057; }
        .badge-published  { background: #d1e7dd; color: #0a7344; }
        .badge-berjalan   { background: #cff4fc; color: #0c5460; }
        .badge-selesai    { background: #d1ecf1; color: #0c5460; }
        .badge-dibatalkan { background: #f8d7da; color: #842029; }

        .page-content { flex: 1; padding-bottom: 2rem; }

        @media (max-width: 768px) {
            #sidebar { transform: translateX(-100%); }
            #sidebar.open { transform: translateX(0); box-shadow: 0 0 30px rgba(0,0,0,.1); }
            #main { margin-left: 0; }
            #topbar { padding: .5rem 1rem; }
        }
    </style>
    @stack('styles')
</head>
<body>

<!-- SIDEBAR -->
<nav id="sidebar">
    <div class="sidebar-content">
        <!-- Brand -->
        <a href="{{ route('pemateri.dashboard') }}" class="brand" style="display: flex; align-items: center; gap: 12px; padding: 0.85rem 1rem; text-decoration: none; border-bottom: 1px solid #e8edf5;">
            @php
                $logoPath = 'assets/images/Lambang Pendidikan Sirkular Biru Hijau.png';
                $logoExists = file_exists(public_path($logoPath));
            @endphp
            
            @if($logoExists)
                <img src="{{ asset($logoPath) }}?v={{ @filemtime(public_path($logoPath)) }}" 
                     alt="Logo SIPADU" 
                     style="height: 42px; width: 42px; object-fit: contain; flex-shrink: 0;">
            @else
                <div style="width: 42px; height: 42px; background: #0B1F4D; border-radius: 9px; display: flex; align-items: center; justify-content: center; color: #ffffff; font-weight: 900; font-size: 1.15rem; flex-shrink: 0;">
                    S
                </div>
            @endif
            
            <span style="font-weight: 900; color: #0B1F4D !important; font-size: 1.35rem; letter-spacing: 1.5px; line-height: 1;">
                SIPADU
            </span>
        </a>

        <!-- MENU PEMATERI: Khusus Pelatihan, Materi, Kuis, dan Profil -->
        <div class="sidebar-nav">
            <div class="nav-label">Menu Utama</div>
            
            <a href="{{ route('pemateri.dashboard') }}" class="nav-link {{ request()->routeIs('pemateri.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2"></i> Dashboard
            </a>

            <div class="nav-label">Aktivitas Pemateri</div>

            <!-- Pelatihan Saya (dengan Submenu Pelatihan, Materi, Absen, Quiz) -->
            @php
                $isPelatihanActive = request()->routeIs('pemateri.trainings.*') || 
                                     request()->routeIs('pemateri.materi.*') || 
                                     request()->routeIs('pemateri.absen.*') || 
                                     request()->routeIs('pemateri.quiz.*');
            @endphp
            <a href="javascript:void(0)" class="nav-link {{ $isPelatihanActive ? 'active' : '' }}" onclick="toggleSubmenu('submenuPelatihanSaya')">
                <i class="bi bi-journal-bookmark"></i> Pelatihan Saya
                <span class="chevron {{ $isPelatihanActive ? 'open' : '' }}" id="chevronPelatihanSaya"><i class="bi bi-chevron-right"></i></span>
            </a>
            <div class="submenu {{ $isPelatihanActive ? 'open' : '' }}" id="submenuPelatihanSaya">
                <a href="{{ route('pemateri.trainings.index') }}" class="nav-link {{ request()->routeIs('pemateri.trainings.*') ? 'active' : '' }}">
                    Daftar Pelatihan
                </a>
                <a href="{{ route('pemateri.materi.index') }}" class="nav-link {{ request()->routeIs('pemateri.materi.*') ? 'active' : '' }}">
                    Materi
                </a>
                <a href="{{ route('pemateri.absen.index') }}" class="nav-link {{ request()->routeIs('pemateri.absen.*') ? 'active' : '' }}">
                    Absen
                </a>
                <a href="{{ route('pemateri.quiz.index') }}" class="nav-link {{ request()->routeIs('pemateri.quiz.*') ? 'active' : '' }}">
                    Quiz
                </a>
            </div>

            <!-- Survey (Tetap pada tempatnya) -->
            <a href="{{ route('pemateri.survey.index') }}" class="nav-link {{ request()->routeIs('pemateri.survey.*') ? 'active' : '' }}">
                <i class="bi bi-ui-radios"></i> Survey
            </a>

            <!-- Dokumentasi (Tetap pada tempatnya) -->
            <a href="{{ route('pemateri.dokumentasi.index') }}" class="nav-link {{ request()->routeIs('pemateri.dokumentasi.*') ? 'active' : '' }}">
                <i class="bi bi-images"></i> Dokumentasi
            </a>

            <div class="nav-label">Akun</div>

            <!-- Profil -->
            <a href="{{ route('pemateri.profile.index') }}" class="nav-link {{ request()->routeIs('pemateri.profile.*') ? 'active' : '' }}">
                <i class="bi bi-person"></i> Profil
            </a>
        </div>

        <!-- User Info -->
        <div class="user-info">
            <div class="d-flex align-items-center gap-3 mb-2">
                <div class="user-avatar">
                    @if(auth()->user()->foto)
                        <img src="{{ asset('storage/' . auth()->user()->foto) }}" alt="Avatar" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                    @else
                        {{ strtoupper(substr(auth()->user()->name ?? auth()->user()->nama ?? 'P', 0, 2)) }}
                    @endif
                </div>
                <div class="flex-grow-1 overflow-hidden">
                    <div class="user-name text-truncate">{{ auth()->user()->name ?? auth()->user()->nama }}</div>
                    <div class="user-role text-truncate">{{ auth()->user()->email }}</div>
                </div>
                <span class="badge">Pemateri</span>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-sm btn-logout w-100">
                    <i class="bi bi-box-arrow-right me-1"></i> Keluar
                </button>
            </form>
        </div>
    </div>
</nav>

<!-- MAIN CONTENT -->
<div id="main">
    <!-- Topbar -->
    <div id="topbar">
        <button class="btn btn-sm btn-light d-md-none" onclick="document.getElementById('sidebar').classList.toggle('open')" style="border-radius: 10px; border: 1px solid #e8edf5;">
            <i class="bi bi-list fs-5"></i>
        </button>
        <div class="d-flex align-items-center gap-2 ms-auto">
            <div class="dropdown">
                <button class="btn btn-light btn-sm d-flex align-items-center gap-2 border-0 rounded-pill px-3 py-1.5" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="background: #f7f9fc;">
                    @if(auth()->user()->foto)
                    <img class="rounded-circle" src="{{ asset('storage/' . auth()->user()->foto) }}" alt="{{ auth()->user()->name }}" style="width: 26px; height: 26px; object-fit: cover;">
                    @else
                    <div class="d-flex align-items-center justify-content-center rounded-circle" style="width: 26px; height: 26px; background: rgba(74, 108, 247, 0.1); color: var(--accent); font-weight: 600; font-size: 0.75rem;">
                        {{ strtoupper(substr(auth()->user()->name ?? auth()->user()->nama ?? 'P', 0, 2)) }}
                    </div>
                    @endif
                    <span class="d-none d-sm-inline fw-semibold text-dark">{{ auth()->user()->name ?? auth()->user()->nama }}</span>
                    <span class="badge bg-info text-white ms-1" style="font-size: 0.65rem;">Pemateri</span>
                    <i class="bi bi-chevron-down text-muted small"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2" style="border-radius: 12px;">
                    <li>
                        <div class="dropdown-header">
                            <span class="d-block fw-semibold text-dark">{{ auth()->user()->name ?? auth()->user()->nama }}</span>
                            <span class="text-muted small">Role: Pemateri</span>
                        </div>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('pemateri.profile.index') }}">
                            <i class="bi bi-person text-muted"></i> Profil Saya
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item d-flex align-items-center gap-2 text-danger">
                                <i class="bi bi-box-arrow-right"></i> Keluar
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Page Header -->
    @hasSection('header')
        @yield('header')
    @endif

    <!-- Content -->
    <div class="page-content">
        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto close sidebar on outside click (mobile)
        document.addEventListener('click', function(e) {
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.querySelector('.d-md-none');
            if (window.innerWidth < 768 && sidebar && sidebar.classList.contains('open')) {
                if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
                    sidebar.classList.remove('open');
                }
            }
        });

        // Toggle Submenu
        window.toggleSubmenu = function(submenuId) {
            const submenu = document.getElementById(submenuId);
            const chevronId = submenuId.replace('submenu', 'chevron');
            const chevron = document.getElementById(chevronId);
            
            if (submenu) {
                if (submenu.classList.contains('open')) {
                    submenu.classList.remove('open');
                    if (chevron) chevron.classList.remove('open');
                } else {
                    submenu.classList.add('open');
                    if (chevron) chevron.classList.add('open');
                }
            }
        };

        // Otomatis buka submenu jika salah satu item anak aktif
        function openActiveSubmenu() {
            const activeLinks = document.querySelectorAll('.submenu .nav-link.active');
            activeLinks.forEach(function(link) {
                const submenu = link.closest('.submenu');
                if (submenu) {
                    submenu.classList.add('open');
                    const chevronId = submenu.id.replace('submenu', 'chevron');
                    const chevron = document.getElementById(chevronId);
                    if (chevron) chevron.classList.add('open');
                }
            });
        }

        openActiveSubmenu();
    });
</script>
@stack('scripts')
</body>
</html>
