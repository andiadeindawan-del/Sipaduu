<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — Sistem Pelatihan SDM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root { --sidebar-width: 260px; }
        body { background: #f4f6fb; font-size: 0.92rem; }

        /* ===== Sidebar ===== */
        #sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background: #1a2236;
            position: fixed;
            top: 0; left: 0;
            z-index: 100;
            transition: transform .25s;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            overflow-x: hidden;
        }
        #sidebar::-webkit-scrollbar { width: 4px; }
        #sidebar::-webkit-scrollbar-track { background: transparent; }
        #sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,.2); border-radius: 4px; }
        #sidebar::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,.3); }

        #sidebar .sidebar-content {
            display: flex;
            flex-direction: column;
            min-height: 100%;
            flex: 1;
        }

        /* ===== Brand ===== */
        #sidebar .brand {
            padding: 1rem 1.25rem;
            color: #fff;
            font-weight: 700;
            font-size: 1.1rem;
            border-bottom: 1px solid rgba(255,255,255,.08);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            flex-shrink: 0;
            transition: background .2s;
        }
        #sidebar .brand:hover {
            background: rgba(255,255,255,.03);
        }
        #sidebar .brand i { font-size: 1.4rem; color: #4e9af1; }
        #sidebar .brand span { color: #4e9af1; }
        #sidebar .brand .brand-logo {
            height: 40px;
            width: auto;
            object-fit: contain;
            border-radius: 6px;
        }
        #sidebar .brand .brand-icon-placeholder {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #4e9af1, #3a7bc8);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: 1rem;
            flex-shrink: 0;
        }
        #sidebar .brand .brand-text {
            display: flex;
            flex-direction: column;
            line-height: 1.1;
        }
        #sidebar .brand .brand-name {
            font-weight: 800;
            color: #ffffff;
            font-size: 1.2rem;
            letter-spacing: 0.5px;
        }
        #sidebar .brand .brand-highlight {
            color: #6ab0f5;
        }
        #sidebar .brand .brand-subtitle {
            font-size: 0.55rem;
            color: rgba(255,255,255,0.4);
            font-weight: 500;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-top: 1px;
        }

        /* ===== Sidebar Navigation ===== */
        #sidebar .sidebar-nav {
            flex: 1;
            padding: 0.5rem 0 1rem;
        }

        #sidebar .nav-label {
            color: rgba(255,255,255,.25);
            font-size: .62rem;
            font-weight: 600;
            letter-spacing: .1em;
            text-transform: uppercase;
            padding: .85rem 1.25rem .25rem;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        #sidebar .nav-link {
            color: rgba(255,255,255,.65);
            padding: .5rem 1.25rem;
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
            background: rgba(78,154,241,.1);
            border-left-color: rgba(78,154,241,.4);
        }
        #sidebar .nav-link.active {
            color: #fff;
            background: rgba(78,154,241,.18);
            border-left-color: #4e9af1;
        }
        #sidebar .nav-link i { 
            font-size: 1rem; 
            width: 22px; 
            text-align: center;
            flex-shrink: 0;
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

        /* ===== Submenu ===== */
        #sidebar .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height .3s ease;
            background: rgba(0,0,0,.15);
        }
        #sidebar .submenu.open {
            max-height: 500px;
        }
        #sidebar .submenu .nav-link {
            padding-left: 3rem;
            font-size: .82rem;
            border-left-color: transparent;
        }
        #sidebar .submenu .nav-link::before {
            content: '▸ ';
            color: rgba(255,255,255,.25);
            font-size: .7rem;
        }
        #sidebar .submenu .nav-link:hover {
            background: rgba(78,154,241,.08);
            border-left-color: rgba(78,154,241,.2);
        }
        #sidebar .submenu .nav-link.active {
            background: rgba(78,154,241,.12);
            border-left-color: #4e9af1;
        }

        /* ===== User Info ===== */
        #sidebar .user-info {
            padding: 1rem 1.25rem;
            border-top: 1px solid rgba(255,255,255,.08);
            color: rgba(255,255,255,.7);
            font-size: .82rem;
            flex-shrink: 0;
            background: rgba(0,0,0,.2);
        }
        #sidebar .user-info .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #4e9af1, #3a7bc8);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: 0.85rem;
            flex-shrink: 0;
        }
        #sidebar .user-info .user-name { color: #fff; font-weight: 600; font-size: 0.85rem; }
        #sidebar .user-info .user-role { font-size: .72rem; opacity: .6; }
        #sidebar .user-info .user-badge {
            font-size: 0.6rem;
            padding: 0.15rem 0.5rem;
        }
        #sidebar .user-info .btn-logout {
            width: 100%;
            padding: 0.4rem 0.75rem;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 6px;
            color: rgba(255,255,255,0.6);
            font-size: 0.8rem;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        #sidebar .user-info .btn-logout:hover {
            background: rgba(255,255,255,0.1);
            color: #fff;
        }

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
        .page-heading { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; padding: 1.25rem 1.5rem; background: #fff; border-bottom: 1px solid #e8ecf1; }
        .page-heading-copy { display: flex; align-items: center; gap: 1rem; }
        .page-icon { width: 44px; height: 44px; border-radius: .75rem; background: #eaf1fd; color: #4e9af1; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; flex-shrink: 0; }
        .eyebrow { font-size: .7rem; text-transform: uppercase; letter-spacing: .07em; color: #9aa3b2; font-weight: 600; margin: 0; }
        .heading-actions { display: flex; gap: .5rem; flex-wrap: wrap; }

        /* ===== Metric cards ===== */
        .metric-card { background: #fff; border-radius: .75rem; padding: 1.1rem 1.25rem; box-shadow: 0 1px 4px rgba(0,0,0,.06); border-left: 4px solid transparent; height: 100%; }
        .metric-primary { border-left-color: #4e9af1; }
        .metric-success { border-left-color: #28c76f; }
        .metric-warning { border-left-color: #ff9f43; }
        .metric-danger  { border-left-color: #ea5455; }
        .metric-info    { border-left-color: #17a2b8; }
        .metric-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: .4rem; }
        .metric-label { font-size: .75rem; color: #8a93a3; font-weight: 600; text-transform: uppercase; letter-spacing: .03em; }
        .metric-icon { color: #c3cad6; font-size: 1.3rem; }
        .metric-value { font-size: 1.5rem; font-weight: 700; color: #1a2236; }
        .metric-meta { font-size: .75rem; color: #8a93a3; display: flex; gap: .35rem; }

        /* ===== Panel ===== */
        .panel { background: #fff; border-radius: .75rem; box-shadow: 0 1px 4px rgba(0,0,0,.06); overflow: hidden; }
        .panel-header { padding: .9rem 1.25rem; border-bottom: 1px solid #f0f0f0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: .75rem; }
        .section-title { display: flex; align-items: center; gap: .5rem; margin: 0; font-size: 1rem; }
        .section-title i { color: #4e9af1; }

        /* ===== Table ===== */
        .table th { font-size: .75rem; text-transform: uppercase; letter-spacing: .04em; color: #6c757d; border-top: none; font-weight: 600; }
        .table td { vertical-align: middle; }
        .avatar-img { border-radius: 50%; object-fit: cover; }
        .avatar-sm { width: 34px; height: 34px; }
        .avatar-text { width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: .8rem; color: #fff; background: #4e9af1; }

        /* ===== Badge status ===== */
        .badge-draft      { background: #e9ecef; color: #495057; }
        .badge-published  { background: #d1e7dd; color: #0a7344; }
        .badge-berjalan   { background: #cff4fc; color: #0c5460; }
        .badge-selesai    { background: #d1ecf1; color: #0c5460; }
        .badge-dibatalkan { background: #f8d7da; color: #842029; }

        /* ===== Content ===== */
        .page-content { flex: 1; padding-bottom: 2rem; }

        /* ===== Topbar Dropdown ===== */
        .dropdown-header {
            padding: 0.5rem 1rem;
        }
        .dropdown-item {
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
        }
        .dropdown-item:hover {
            background: #f8f9fa;
        }
        .dropdown-item i {
            width: 20px;
            text-align: center;
        }

        /* ===== Responsive ===== */
        @media (max-width: 768px) {
            #sidebar { transform: translateX(-100%); }
            #sidebar.open { transform: translateX(0); box-shadow: 0 0 30px rgba(0,0,0,.3); }
            #main { margin-left: 0; }
            #topbar .d-none.d-sm-inline { display: none !important; }
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
        <a href="{{ route('admin.dashboard') }}" class="brand" style="display: flex; align-items: center; gap: 12px; padding: 0.75rem 1.25rem; text-decoration: none;">
           @php
                $logoPath = 'assets/images/logo koperindag 1.gif';
                $logoExists = file_exists(public_path($logoPath));
            @endphp
            
            @if($logoExists)
                <img src="{{ asset($logoPath) }}" 
                     alt="Logo SIPADU" 
                     style="height: 40px; width: auto; object-fit: contain;">
            @else
                <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #4e9af1, #3a7bc8); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 1rem; flex-shrink: 0;">
                    S
                </div>
            @endif
            
            <div class="brand-text">
                <span class="brand-name">S I P <span class="brand-highlight">A D U</span></span>
                <span class="brand-subtitle">Koperindag Sulbar</span>
            </div>
        </a>

        <!-- ========================================================== -->
        <!-- NAVIGATION -->
        <!-- ========================================================== -->
        <div class="sidebar-nav">
            <!-- DASHBOARD -->
            <div class="nav-label">Menu</div>
            
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2"></i> Dashboard
            </a>

            <!-- ========================================================== -->
            <!-- MASTER DATA -->
            <!-- ========================================================== -->
            <div class="nav-label">📊 MASTER DATA</div>
            
            <a href="{{ route('admin.kategori.index') }}" class="nav-link {{ request()->routeIs('admin.kategori.*') ? 'active' : '' }}">
                <i class="bi bi-tags"></i> Kategori
            </a>
            
            <a href="{{ route('admin.trainings.index') }}" class="nav-link {{ request()->routeIs('admin.trainings.*') ? 'active' : '' }}">
                <i class="bi bi-journal-bookmark"></i> Pelatihan
            </a>
            
            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> Pengguna
            </a>

            <!-- ========================================================== -->
            <!-- PELATIHAN -->
            <!-- ========================================================== -->
            <div class="nav-label">📚 PELATIHAN</div>
            
            <a href="{{ route('admin.materi.index') }}" class="nav-link {{ request()->routeIs('admin.materi.*') ? 'active' : '' }}">
                <i class="bi bi-book"></i> Materi
            </a>

            <a href="{{ route('admin.absen.index') }}" class="nav-link {{ request()->routeIs('admin.absen.*') ? 'active' : '' }}">
                <i class="bi bi-list-check"></i> Absen
            </a>

            <a href="#" class="nav-link" onclick="toggleSubmenu('submenuQuiz')">
                <i class="bi bi-question-circle"></i> Quiz
                <span class="chevron" id="chevronQuiz"><i class="bi bi-chevron-right"></i></span>
            </a>
            <div class="submenu" id="submenuQuiz">
                <a href="{{ route('admin.quiz.index') }}" class="nav-link {{ request()->routeIs('admin.quiz.*') ? 'active' : '' }}">
                    <i class="bi bi-list-check"></i> Kelola Quiz
                </a>
            </div>
            
            <a href="{{ route('admin.sertifikat.index') }}" class="nav-link {{ request()->routeIs('admin.sertifikat.*') ? 'active' : '' }}">
                <i class="bi bi-award"></i> Sertifikat
            </a>
           

            <!-- ========================================================== -->
            <!-- AKTIVITAS -->
            <!-- ========================================================== -->
            <div class="nav-label">📋 AKTIVITAS</div>
            
          
            
            <a href="{{ route('admin.agenda.index') }}" class="nav-link {{ request()->routeIs('admin.agenda.*') ? 'active' : '' }}">
                <i class="bi bi-calendar-event"></i> Agenda
            </a>
            
            <a href="{{ route('admin.pengumuman.index') }}" class="nav-link {{ request()->routeIs('admin.pengumuman.*') ? 'active' : '' }}">
                <i class="bi bi-megaphone"></i> Pengumuman
            </a>

            <!-- ========================================================== -->
            <!-- LAPORAN -->
            <!-- ========================================================== -->
            <div class="nav-label">📊 LAPORAN</div>
            
            <a href="{{ route('admin.laporan.index') }}" class="nav-link {{ request()->routeIs('admin.laporan.index') ? 'active' : '' }}">
                <i class="bi bi-bar-chart"></i> Laporan Pelatihan
            </a>
            
            <a href="{{ route('admin.laporan.users') }}" class="nav-link {{ request()->routeIs('admin.laporan.users') ? 'active' : '' }}">
                <i class="bi bi-person-lines-fill"></i> Laporan Peserta
            </a>
            


            <!-- ========================================================== -->
            <!-- PENGATURAN -->
            <!-- ========================================================== -->
            <div class="nav-label">⚙️ PENGATURAN</div>
            
            <a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                <i class="bi bi-gear"></i> Pengaturan Akun
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
                    <div class="user-role">{{ auth()->user()->nik ?? 'Admin' }}</div>
                </div>
                <span class="badge bg-primary user-badge">{{ ucfirst(auth()->user()->role ?? 'Admin') }}</span>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">
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
    <div id="topbar">
        <button class="btn btn-sm btn-light d-md-none" onclick="document.getElementById('sidebar').classList.toggle('open')">
            <i class="bi bi-list fs-5"></i>
        </button>
        <div class="d-flex align-items-center gap-2 ms-auto">
            <!-- Kalender -->
            <a href="{{ route('admin.agenda.index') }}" class="btn btn-light btn-sm d-flex align-items-center gap-2 border-0 rounded-pill px-3 py-1.5" title="Agenda & Kalender">
                <i class="bi bi-calendar3 text-primary"></i>
                <span class="d-none d-md-inline fw-semibold text-dark">Kalender</span>
            </a>
            
            <!-- User Profile Dropdown -->
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
                            <span class="text-muted small">{{ ucfirst(auth()->user()->role ?? 'Admin') }}</span>
                        </div>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('profile.edit') }}">
                            <i class="bi bi-person text-muted"></i> Profil Saya
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('profile.edit') }}">
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
            @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i> Terdapat kesalahan pada input Anda:
                <ul class="mb-0 mt-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
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

        openActiveSubmenu();

        // ============================================================
        // HOVER EFEK UNTUK SUBMENU
        // ============================================================
        document.querySelectorAll('.submenu .nav-link').forEach(function(link) {
            link.addEventListener('mouseenter', function() {
                this.style.paddingLeft = '3.3rem';
            });
            link.addEventListener('mouseleave', function() {
                this.style.paddingLeft = '3rem';
            });
        });
    });
</script>
@stack('scripts')
</body>
</html>