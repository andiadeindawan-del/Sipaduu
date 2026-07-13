<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Peserta') — Sistem Pelatihan SDM</title>
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
        /* SIDEBAR - PUTIH */
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

        /* ============================================================ */
        /* BRAND */
        /* ============================================================ */
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

        #sidebar .brand i { 
            font-size: 1.4rem; 
            color: var(--accent);
            background: rgba(74, 108, 247, 0.1);
            padding: 8px;
            border-radius: 12px;
        }

        #sidebar .brand .brand-text {
            display: flex;
            flex-direction: column;
        }

        #sidebar .brand .brand-text .brand-name {
            font-weight: 700;
            font-size: 1.1rem;
            letter-spacing: -0.3px;
            line-height: 1.2;
            color: #1a2332;
        }

        #sidebar .brand .brand-text .brand-name span {
            color: var(--accent);
        }

        #sidebar .brand .brand-text .brand-sub {
            font-size: 0.55rem;
            font-weight: 400;
            color: #a0aec0;
            letter-spacing: 0.5px;
            margin-top: -1px;
        }

        /* ============================================================ */
        /* SIDEBAR NAVIGATION */
        /* ============================================================ */
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

        /* ============================================================ */
        /* NAV LABEL */
        /* ============================================================ */
        #sidebar .nav-label {
            color: #a0aec0;
            font-size: .6rem;
            font-weight: 600;
            letter-spacing: .08em;
            text-transform: uppercase;
            padding: .6rem 0.75rem .3rem;
        }

        /* ============================================================ */
        /* NAV LINK */
        /* ============================================================ */
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

        /* ============================================================ */
        /* USER INFO */
        /* ============================================================ */
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
            background: var(--accent);
            font-weight: 400;
            font-size: .6rem;
            padding: .15rem .6rem;
            border-radius: 20px;
            color: #fff;
        }

        #sidebar .user-info .btn-logout {
            color: var(--sidebar-text);
            border-color: var(--sidebar-border);
            font-size: .75rem;
            padding: .35rem .75rem;
            border-radius: 8px;
            transition: all .2s ease;
        }

        #sidebar .user-info .btn-logout:hover {
            color: #ea5455;
            border-color: #ea5455;
            background: rgba(234, 84, 85, 0.06);
        }

        /* ============================================================ */
        /* MAIN CONTENT */
        /* ============================================================ */
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
            justify-content: flex-end;
            gap: .75rem;
        }

        /* ============================================================ */
        /* PAGE HEADING */
        /* ============================================================ */
        .page-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
            padding: 1.25rem 1.5rem;
            background: #fff;
            border-bottom: 1px solid var(--sidebar-border);
        }
        .page-heading-copy { display: flex; align-items: center; gap: 1rem; }
        .page-icon {
            width: 44px;
            height: 44px;
            border-radius: .75rem;
            background: rgba(74, 108, 247, 0.1);
            color: var(--accent);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
        }
        .eyebrow {
            font-size: .7rem;
            text-transform: uppercase;
            letter-spacing: .07em;
            color: #9aa3b2;
            font-weight: 600;
            margin: 0;
        }
        .heading-actions { display: flex; gap: .5rem; flex-wrap: wrap; }

        /* ============================================================ */
        /* METRIC CARDS */
        /* ============================================================ */
        .metric-card {
            background: #fff;
            border-radius: .75rem;
            padding: 1.1rem 1.25rem;
            box-shadow: 0 1px 4px rgba(0,0,0,.06);
            border-left: 4px solid transparent;
            height: 100%;
        }
        .metric-success { border-left-color: #28c76f; }
        .metric-primary { border-left-color: var(--accent); }
        .metric-warning { border-left-color: #ff9f43; }
        .metric-info { border-left-color: #17a2b8; }
        .metric-danger { border-left-color: #ea5455; }
        .metric-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: .4rem;
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

        /* ============================================================ */
        /* PANEL */
        /* ============================================================ */
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
        }
        .section-title i { color: var(--accent); }

        /* ============================================================ */
        /* TABLE */
        /* ============================================================ */
        .table th {
            font-size: .75rem;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: #6c757d;
            border-top: none;
            font-weight: 600;
        }
        .table td { vertical-align: middle; }
        .avatar-img { border-radius: 50%; object-fit: cover; }
        .avatar-sm { width: 34px; height: 34px; }
        .avatar-text {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: .8rem;
            color: #fff;
            background: var(--accent);
        }

        /* ============================================================ */
        /* BADGE */
        /* ============================================================ */
        .badge-draft      { background: #e9ecef; color: #495057; }
        .badge-published  { background: #d1e7dd; color: #0a7344; }
        .badge-berjalan   { background: #cff4fc; color: #0c5460; }
        .badge-selesai    { background: #d1ecf1; color: #0c5460; }
        .badge-dibatalkan { background: #f8d7da; color: #842029; }

        /* ============================================================ */
        /* CONTENT */
        /* ============================================================ */
        .page-content { flex: 1; padding-bottom: 2rem; }

        /* ============================================================ */
        /* RESPONSIVE */
        /* ============================================================ */
        @media (max-width: 768px) {
            #sidebar { transform: translateX(-100%); border-radius: 0; }
            #sidebar.open { transform: translateX(0); box-shadow: 0 0 30px rgba(0,0,0,.1); }
            #main { margin-left: 0; }
            #topbar { padding: .5rem 1rem; }
        }
    </style>
    @stack('styles')
</head>
<body>

<!-- ========================================================== -->
<!-- SIDEBAR -->
<!-- ========================================================== -->
<nav id="sidebar">
    <div class="sidebar-content">
        <!-- Brand -->
        <a href="{{ route('peserta.dashboard') }}" class="brand">
            <i class="bi bi-mortarboard-fill"></i>
            <div class="brand-text">
                <span class="brand-name">S I P <span>A D U</span></span>
                <span class="brand-sub">Sistem Pelatihan SDM usaha KOPERINDAG</span>
            </div>
        </a>

        <!-- ========================================================== -->
        <!-- MENU PESERTA -->
        <!-- ========================================================== -->
        <div class="sidebar-nav">
            <!-- DASHBOARD -->
            <div class="nav-label">Menu</div>
            
            <a href="{{ route('peserta.dashboard') }}" class="nav-link {{ request()->routeIs('peserta.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2"></i> Dashboard
            </a>

            <!-- ========================================================== -->
            <!-- PELATIHAN -->
            <!-- ========================================================== -->
            <a href="{{ route('peserta.trainings.index') }}" class="nav-link {{ request()->routeIs('peserta.trainings.index') ? 'active' : '' }}">
                <i class="bi bi-list-check"></i> Pelatihan
            </a>

            <!-- Pelatihan Saya (dengan submenu) -->
            <a href="#" class="nav-link" onclick="toggleSubmenu('submenuPelatihanSaya')">
                <i class="bi bi-journal-bookmark"></i> Pelatihan Saya
                <span class="chevron" id="chevronPelatihanSaya"><i class="bi bi-chevron-right"></i></span>
            </a>
            <div class="submenu" id="submenuPelatihanSaya">
                <a href="{{ route('peserta.materi.index') }}" class="nav-link {{ request()->routeIs('peserta.materi.*') ? 'active' : '' }}">
                    Materi
                </a>
                <a href="{{ route('peserta.quiz.index') }}" class="nav-link {{ request()->routeIs('peserta.quiz.*') ? 'active' : '' }}">
                    Quiz
                </a>
                <a href="{{ route('peserta.absen.index') }}" class="nav-link {{ request()->routeIs('peserta.absen.*') ? 'active' : '' }}">
                    Kehadiran
                </a>
                <a href="{{ route('peserta.sertifikat.index') }}" class="nav-link {{ request()->routeIs('peserta.sertifikat.*') ? 'active' : '' }}">
                    Sertifikat
                </a>
            </div>

            <!-- ========================================================== -->
            <!-- INFORMASI -->
            <!-- ========================================================== -->
            <div class="nav-label">Informasi</div>

            <a href="{{ route('peserta.agenda.index') }}" class="nav-link {{ request()->routeIs('peserta.agenda.*') ? 'active' : '' }}">
                <i class="bi bi-calendar-event"></i> Agenda
            </a>
            
            <a href="{{ route('peserta.pengumuman.index') }}" class="nav-link {{ request()->routeIs('peserta.pengumuman.*') ? 'active' : '' }}">
                <i class="bi bi-megaphone"></i> Pengumuman
            </a>

            <!-- ========================================================== -->
            <!-- AKUN -->
            <!-- ========================================================== -->
            <div class="nav-label">Akun</div>

            <a href="{{ route('peserta.profile.index') }}" class="nav-link {{ request()->routeIs('peserta.profile.*') ? 'active' : '' }}">
                <i class="bi bi-person"></i> Profil
            </a>
        </div>

        <!-- User Info -->
        <div class="user-info">
            <div class="d-flex align-items-center gap-3 mb-2">
                <div class="user-avatar">
                    {{ strtoupper(substr(auth()->user()->nama ?? auth()->user()->name ?? 'U', 0, 2)) }}
                </div>
                <div class="flex-grow-1">
                    <div class="user-name">{{ auth()->user()->nama ?? auth()->user()->name }}</div>
                    <div class="user-role">{{ auth()->user()->nik ?? 'Peserta' }}</div>
                </div>
                <span class="badge">Peserta</span>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-sm btn-logout w-100">
                    <i class="bi bi-box-arrow-right me-1"></i> Keluar
                </button>
            </form>
        </div>
    </div>
</nav>

<!-- ========================================================== -->
<!-- MAIN CONTENT -->
<!-- ========================================================== -->
<div id="main">
    <!-- Topbar -->
    <div id="topbar" class="d-flex align-items-center justify-content-between">
        <button class="btn btn-sm btn-light d-md-none" onclick="document.getElementById('sidebar').classList.toggle('open')" style="border-radius: 10px; border: 1px solid #e8edf5;">
            <i class="bi bi-list fs-5"></i>
        </button>
        <div class="d-flex align-items-center gap-2 ms-auto">
            <a href="#" class="btn btn-light btn-sm d-flex align-items-center gap-2 border-0 rounded-pill px-3 py-1.5" title="Agenda & Kalender" style="background: #f7f9fc;">
                <i class="bi bi-calendar3 text-primary"></i>
                <span class="d-none d-md-inline fw-semibold text-dark">Kalender</span>
            </a>
            
            <div class="dropdown">
                <button class="btn btn-light btn-sm d-flex align-items-center gap-2 border-0 rounded-pill px-3 py-1.5" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="background: #f7f9fc;">
                    @if(auth()->user()->foto)
                    <img class="rounded-circle" src="{{ asset('storage/' . auth()->user()->foto) }}" alt="{{ auth()->user()->nama }}" style="width: 24px; height: 24px; object-fit: cover;">
                    @else
                    <div class="d-flex align-items-center justify-content-center rounded-circle" style="width: 24px; height: 24px; background: rgba(74, 108, 247, 0.1); color: var(--accent); font-weight: 600; font-size: 0.7rem;">
                        {{ strtoupper(substr(auth()->user()->nama ?? auth()->user()->name ?? 'U', 0, 2)) }}
                    </div>
                    @endif
                    <span class="d-none d-sm-inline fw-semibold text-dark">{{ auth()->user()->nama ?? auth()->user()->name }}</span>
                    <i class="bi bi-chevron-down text-muted small"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2" style="border-radius: 12px;">
                    <li>
                        <div class="dropdown-header">
                            <span class="d-block fw-semibold text-dark">{{ auth()->user()->nama ?? auth()->user()->name }}</span>
                            <span class="text-muted small">{{ ucfirst(auth()->user()->role ?? 'Peserta') }}</span>
                        </div>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('peserta.profile.index') }}">
                            <i class="bi bi-person text-muted"></i> Profil
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('peserta.profile.index') }}">
                            <i class="bi bi-gear text-muted"></i> Pengaturan
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

    <!-- Page Content -->
    <div class="page-content">
        <div class="container-fluid px-3 px-lg-4 pt-3">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 12px; border: none; background: #e8f5e9;">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 12px; border: none; background: #fde8e8;">
                <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert" style="border-radius: 12px; border: none; background: #fff8e1;">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
        </div>

        @yield('content')
    </div>
</div>

<!-- ========================================================== -->
<!-- SCRIPTS -->
<!-- ========================================================== -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto close sidebar on outside click (mobile)
        document.addEventListener('click', function(e) {
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.querySelector('.d-md-none');
            if (window.innerWidth < 768 && sidebar.classList.contains('open')) {
                if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
                    sidebar.classList.remove('open');
                }
            }
        });

        // Auto close alerts after 5 seconds
        setTimeout(function() {
            document.querySelectorAll('.alert').forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // ============================================================
        // TOGGLE SUBMENU
        // ============================================================
        window.toggleSubmenu = function(submenuId) {
            const submenu = document.getElementById(submenuId);
            const chevronId = submenuId.replace('submenu', 'chevron');
            const chevron = document.getElementById(chevronId);
            
            if (submenu.classList.contains('open')) {
                submenu.classList.remove('open');
                if (chevron) chevron.classList.remove('open');
            } else {
                submenu.classList.add('open');
                if (chevron) chevron.classList.add('open');
            }
        };

        // ============================================================
        // OTOMATIS BUKA SUBMENU BERDASARKAN ROUTE AKTIF
        // ============================================================
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

        // Jalankan saat halaman dimuat
        openActiveSubmenu();
    });
</script>
@stack('scripts')
</body>
</html>