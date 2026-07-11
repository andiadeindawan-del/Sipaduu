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
        :root { --sidebar-width: 260px; --accent: #1da853; }
        body { background: #f4f6fb; font-size: 0.92rem; }

        /* ===== Sidebar - PERBAIKAN ===== */
        #sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background: #0d1b15;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
            transition: transform .25s;
            display: flex;
            flex-direction: column;
            overflow: hidden; /* PERBAIKAN: hidden agar scroll hanya di sidebar-nav */
        }

        #sidebar .sidebar-content {
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow: hidden;
        }

        #sidebar .brand {
            padding: 1.25rem 1.5rem .75rem;
            color: #fff;
            font-weight: 700;
            font-size: 1.1rem;
            border-bottom: 1px solid rgba(255,255,255,.06);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            flex-shrink: 0; /* PERBAIKAN: tidak mengecil */
        }
        #sidebar .brand i { font-size: 1.5rem; color: var(--accent); }
        #sidebar .brand span { color: var(--accent); }

        /* ===== Sidebar Navigation - PERBAIKAN ===== */
        #sidebar .sidebar-nav {
            flex: 1 1 auto;
            overflow-y: auto; /* PERBAIKAN: scroll di sini */
            overflow-x: hidden;
            padding: 0.5rem 0 1rem;
            min-height: 0; /* PERBAIKAN: penting untuk flex scroll */
        }

        /* PERBAIKAN: styling scrollbar untuk sidebar-nav */
        #sidebar .sidebar-nav::-webkit-scrollbar {
            width: 4px;
        }
        #sidebar .sidebar-nav::-webkit-scrollbar-track {
            background: transparent;
        }
        #sidebar .sidebar-nav::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,.15);
            border-radius: 4px;
        }
        #sidebar .sidebar-nav::-webkit-scrollbar-thumb:hover {
            background: rgba(255,255,255,.25);
        }

        /* Firefox scrollbar */
        #sidebar .sidebar-nav {
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,.15) transparent;
        }

        #sidebar .nav-label {
            color: rgba(255,255,255,.25);
            font-size: .62rem;
            font-weight: 600;
            letter-spacing: .1em;
            text-transform: uppercase;
            padding: .85rem 1.5rem .25rem;
            display: flex;
            align-items: center;
            gap: .5rem;
        }
        #sidebar .nav-label::before,
        #sidebar .nav-label::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(255,255,255,.06);
        }

        #sidebar .nav-link {
            color: rgba(255,255,255,.7);
            padding: .55rem 1.5rem;
            border-radius: 0;
            display: flex;
            align-items: center;
            gap: .7rem;
            text-decoration: none;
            transition: all .15s;
            cursor: pointer;
            font-size: .88rem;
            border-left: 3px solid transparent;
            position: relative;
        }
        #sidebar .nav-link:hover {
            color: #fff;
            background: rgba(29,168,83,.12);
            border-left-color: rgba(29,168,83,.3);
        }
        #sidebar .nav-link.active {
            color: #fff;
            background: rgba(29,168,83,.18);
            border-left-color: var(--accent);
        }
        #sidebar .nav-link i { 
            font-size: 1.05rem; 
            width: 24px; 
            text-align: center;
            flex-shrink: 0;
        }
        #sidebar .nav-link .badge {
            margin-left: auto;
            background: rgba(255,255,255,.12);
            color: #fff;
            font-size: .65rem;
            padding: .15rem .6rem;
        }
        #sidebar .nav-link .chevron {
            margin-left: auto;
            transition: transform .25s;
            font-size: .7rem;
            color: rgba(255,255,255,.3);
        }
        #sidebar .nav-link .chevron.open {
            transform: rotate(90deg);
        }

        /* ===== Submenu Level 1 ===== */
        #sidebar .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height .3s ease;
            background: rgba(0,0,0,.2);
        }
        #sidebar .submenu.open {
            max-height: 500px;
        }
        #sidebar .submenu .nav-link {
            padding-left: 3.5rem;
            font-size: .82rem;
            border-left-color: transparent;
            color: rgba(255,255,255,.55);
        }
        #sidebar .submenu .nav-link::before {
            content: '▸ ';
            color: rgba(255,255,255,.2);
            font-size: .7rem;
        }
        #sidebar .submenu .nav-link:hover {
            color: #fff;
            background: rgba(29,168,83,.08);
            border-left-color: rgba(29,168,83,.2);
        }
        #sidebar .submenu .nav-link.active {
            color: #fff;
            background: rgba(29,168,83,.12);
            border-left-color: var(--accent);
        }

        /* ===== Submenu Level 2 (Pelatihan Saya) ===== */
        #sidebar .submenu-level2 {
            max-height: 0;
            overflow: hidden;
            transition: max-height .3s ease;
            background: rgba(0,0,0,.15);
        }
        #sidebar .submenu-level2.open {
            max-height: 400px;
        }
        #sidebar .submenu-level2 .nav-link {
            padding-left: 4.5rem !important;
            font-size: .78rem;
            border-left-color: transparent;
            color: rgba(255,255,255,.45);
        }
        #sidebar .submenu-level2 .nav-link::before {
            content: '• ';
            color: rgba(255,255,255,.15);
            font-size: .8rem;
        }
        #sidebar .submenu-level2 .nav-link:hover {
            color: #fff;
            background: rgba(29,168,83,.06);
            border-left-color: rgba(29,168,83,.15);
        }
        #sidebar .submenu-level2 .nav-link.active {
            color: #fff;
            background: rgba(29,168,83,.1);
            border-left-color: var(--accent);
        }

        /* ===== User Info - PERBAIKAN ===== */
        #sidebar .user-info {
            padding: .9rem 1.5rem;
            border-top: 1px solid rgba(255,255,255,.06);
            color: rgba(255,255,255,.6);
            font-size: .82rem;
            flex-shrink: 0; /* PERBAIKAN: tidak mengecil */
            background: #0d1b15;
        }
        #sidebar .user-info .user-name { color: #fff; font-weight: 600; font-size: .9rem; }
        #sidebar .user-info .user-role { font-size: .72rem; opacity: .6; }
        #sidebar .user-info .badge {
            background: var(--accent);
            font-weight: 400;
            font-size: .65rem;
            padding: .2rem .6rem;
        }
        #sidebar .user-info .btn-outline-secondary {
            color: rgba(255,255,255,.6);
            border-color: rgba(255,255,255,.1);
            font-size: .78rem;
            transition: all .15s;
        }
        #sidebar .user-info .btn-outline-secondary:hover {
            background: rgba(255,255,255,.08);
            color: #fff;
            border-color: rgba(255,255,255,.2);
        }
        #sidebar .user-info .btn-outline-secondary i { font-size: .9rem; }

        /* ===== Main ===== */
        #main { margin-left: var(--sidebar-width); min-height: 100vh; display: flex; flex-direction: column; }
        #topbar {
            background: #fff;
            border-bottom: 1px solid #e8ecf1;
            padding: .5rem 1.5rem;
            position: sticky;
            top: 0;
            z-index: 99;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: .75rem;
        }

        /* ===== Page heading ===== */
        .page-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
            padding: 1.25rem 1.5rem;
            background: #fff;
            border-bottom: 1px solid #e8ecf1;
        }
        .page-heading-copy { display: flex; align-items: center; gap: 1rem; }
        .page-icon {
            width: 44px;
            height: 44px;
            border-radius: .75rem;
            background: #e7f7ed;
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

        /* ===== Metric cards ===== */
        .metric-card {
            background: #fff;
            border-radius: .75rem;
            padding: 1.1rem 1.25rem;
            box-shadow: 0 1px 4px rgba(0,0,0,.06);
            border-left: 4px solid transparent;
            height: 100%;
        }
        .metric-success { border-left-color: var(--accent); }
        .metric-primary { border-left-color: #4e9af1; }
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
            color: #0d1b15;
        }
        .metric-meta {
            font-size: .75rem;
            color: #8a93a3;
            display: flex;
            gap: .35rem;
        }

        /* ===== Panel ===== */
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

        /* ===== Table ===== */
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

        /* ===== Badge ===== */
        .badge-draft      { background: #e9ecef; color: #495057; }
        .badge-published  { background: #d1e7dd; color: #0a7344; }
        .badge-berjalan   { background: #cff4fc; color: #0c5460; }
        .badge-selesai    { background: #d1ecf1; color: #0c5460; }
        .badge-dibatalkan { background: #f8d7da; color: #842029; }

        /* ===== Content ===== */
        .page-content { flex: 1; padding-bottom: 2rem; }

        /* ===== Responsive ===== */
        @media (max-width: 768px) {
            #sidebar { transform: translateX(-100%); }
            #sidebar.open { transform: translateX(0); box-shadow: 0 0 30px rgba(0,0,0,.3); }
            #main { margin-left: 0; }
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
            S I P <span>A D U</span>
        </a>

        <!-- ========================================================== -->
        <!-- MENU PESERTA -->
        <!-- ========================================================== -->
        <div class="sidebar-nav">
            <!-- DASHBOARD -->
            <div class="nav-label">MENU PESERTA</div>
            
            <a href="{{ route('peserta.dashboard') }}" class="nav-link {{ request()->routeIs('peserta.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2"></i> Beranda
            </a>

            <!-- ========================================================== -->
            <!-- PELATIHAN -->
            <!-- ========================================================== -->
            <div class="nav-label">PELATIHAN</div>
            
            <!-- Daftar Pelatihan -->
            <a href="{{ route('peserta.trainings.index') }}" class="nav-link {{ request()->routeIs('peserta.trainings.index') ? 'active' : '' }}">
                <i class="bi bi-list-check"></i> Daftar Pelatihan
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

            <!-- Riwayat Pelatihan -->
            <!-- <div class="nav-label">RIWAYAT</div>
            <a href="{{ route('peserta.trainings.history') }}" class="nav-link {{ request()->routeIs('peserta.trainings.history') ? 'active' : '' }}">
                <i class="bi bi-clock-history"></i> Riwayat Pelatihan
            </a> -->

            <!-- ========================================================== -->
            <!-- INFORMASI -->
            <!-- ========================================================== -->
            <div class="nav-label">INFORMASI</div>
            
            <a href="{{ route('peserta.agenda.index') }}" class="nav-link {{ request()->routeIs('peserta.agenda.*') ? 'active' : '' }}">
                <i class="bi bi-calendar-event"></i> Agenda
            </a>
            
            <a href="{{ route('peserta.pengumuman.index') }}" class="nav-link {{ request()->routeIs('peserta.pengumuman.*') ? 'active' : '' }}">
                <i class="bi bi-megaphone"></i> Pengumuman
            </a>

            <!-- ========================================================== -->
            <!-- AKUN -->
            <!-- ========================================================== -->
            <div class="nav-label">AKUN</div>
            
            <a href="{{ route('peserta.profile.index') }}" class="nav-link {{ request()->routeIs('peserta.profile.*') ? 'active' : '' }}">
                <i class="bi bi-person"></i> Profil Saya
            </a>
        </div>

        <!-- User Info -->
        <div class="user-info">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div>
                    <div class="user-name">{{ auth()->user()->nama ?? auth()->user()->name }}</div>
                    <div class="user-role">{{ auth()->user()->nik ?? 'Peserta' }}</div>
                </div>
                <div>
                    <span class="badge">Peserta</span>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-secondary w-100">
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
        <button class="btn btn-sm btn-light d-md-none" onclick="document.getElementById('sidebar').classList.toggle('open')">
            <i class="bi bi-list fs-5"></i>
        </button>
        <div class="d-flex align-items-center gap-2 ms-auto">
            <a href="#" class="btn btn-light btn-sm d-flex align-items-center gap-2 border-0 rounded-pill px-3 py-1.5" title="Agenda & Kalender">
                <i class="bi bi-calendar3 text-primary"></i>
                <span class="d-none d-md-inline fw-semibold text-dark">Kalender</span>
            </a>
            
            <div class="dropdown">
                <button class="btn btn-light btn-sm d-flex align-items-center gap-2 border-0 rounded-pill px-3 py-1.5" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    @if(auth()->user()->foto)
                    <img class="rounded-circle" src="{{ asset('storage/' . auth()->user()->foto) }}" alt="{{ auth()->user()->nama }}" style="width: 24px; height: 24px; object-fit: cover;">
                    @else
                    <div class="avatar-img avatar-xs bg-primary text-white d-flex align-items-center justify-content-center rounded-circle" style="width: 24px; height: 24px; font-size: 0.75rem; font-weight: 600;">
                        {{ strtoupper(substr(auth()->user()->nama ?? auth()->user()->name ?? 'U', 0, 2)) }}
                    </div>
                    @endif
                    <span class="d-none d-sm-inline fw-semibold text-dark">{{ auth()->user()->nama ?? auth()->user()->name }}</span>
                    <i class="bi bi-chevron-down text-muted small"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2">
                    <li>
                        <div class="dropdown-header">
                            <span class="d-block fw-semibold text-dark">{{ auth()->user()->nama ?? auth()->user()->name }}</span>
                            <span class="text-muted small">{{ ucfirst(auth()->user()->role ?? 'Peserta') }}</span>
                        </div>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('peserta.profile.index') }}">
                            <i class="bi bi-person text-muted"></i> Profil Saya
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
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
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

        // ============================================================
        // HOVER EFEK UNTUK SUBMENU
        // ============================================================
        document.querySelectorAll('.submenu .nav-link').forEach(function(link) {
            link.addEventListener('mouseenter', function() {
                this.style.paddingLeft = '3.8rem';
            });
            link.addEventListener('mouseleave', function() {
                this.style.paddingLeft = '3.5rem';
            });
        });
    });
</script>
@stack('scripts')
</body>
</html>