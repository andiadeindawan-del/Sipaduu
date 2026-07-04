<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Sistem Pelatihan SDM - Administration Dashboard">

    <title>{{ config('app.name', 'SIPADU') }} - @yield('title', 'Admin')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Styles -->
    <style>
        :root {
            --sidebar-width: 270px;
            --primary: #4e9af1;
            --primary-dark: #3a7bc8;
            --secondary: #1a2236;
            --bg-body: #f4f6fb;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', 'Segoe UI', system-ui, sans-serif;
            background: var(--bg-body);
            color: #1a2236;
            font-size: 0.9rem;
            margin: 0;
        }

        /* ============================================================
           SIDEBAR
        ============================================================ */
        .admin-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: #1a2236;
            color: rgba(255,255,255,0.7);
            display: flex;
            flex-direction: column;
            z-index: 1050;
            transition: transform 0.25s ease;
            overflow-y: auto;
            overflow-x: hidden;
        }
        .admin-sidebar::-webkit-scrollbar {
            width: 4px;
        }
        .admin-sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.2);
            border-radius: 4px;
        }

        /* Sidebar Header / Brand */
        .sidebar-header {
            padding: 1.25rem 1.25rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            flex-shrink: 0;
        }
        .brand-mark {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            text-decoration: none;
            color: #fff;
        }
        .brand-mark:hover {
            color: #fff;
            text-decoration: none;
        }
        .brand-icon {
            width: 40px;
            height: 40px;
            background: rgba(78,154,241,0.2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            color: #4e9af1;
            flex-shrink: 0;
        }
        .brand-title {
            font-weight: 800;
            font-size: 1.1rem;
            letter-spacing: 0.5px;
        }
        .brand-title span {
            color: #4e9af1;
        }
        .brand-subtitle {
            font-size: 0.65rem;
            color: rgba(255,255,255,0.4);
            display: block;
            font-weight: 400;
            letter-spacing: 0.3px;
        }

        /* Sidebar Navigation */
        .sidebar-nav {
            padding: 0.5rem 0 1rem;
            flex: 1;
        }

        .nav-label {
            color: rgba(255,255,255,0.25);
            font-size: 0.6rem;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            padding: 0.7rem 1.5rem 0.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .nav-label::before,
        .nav-label::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(255,255,255,0.06);
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            padding: 0.5rem 1.5rem;
            color: rgba(255,255,255,0.65);
            text-decoration: none;
            font-size: 0.88rem;
            border-left: 3px solid transparent;
            transition: all 0.15s;
            cursor: pointer;
            position: relative;
        }
        .nav-link:hover {
            color: #fff;
            background: rgba(78,154,241,0.1);
            border-left-color: rgba(78,154,241,0.4);
        }
        .nav-link.active {
            color: #fff;
            background: rgba(78,154,241,0.18);
            border-left-color: #4e9af1;
        }
        .nav-link .nav-icon {
            width: 22px;
            text-align: center;
            font-size: 1rem;
            flex-shrink: 0;
        }
        .nav-link .nav-text {
            flex: 1;
        }
        .nav-link .chevron {
            margin-left: auto;
            font-size: 0.7rem;
            color: rgba(255,255,255,0.3);
            transition: transform 0.25s;
        }
        .nav-link .chevron.open {
            transform: rotate(90deg);
        }

        /* Submenu */
        .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            background: rgba(0,0,0,0.15);
        }
        .submenu.open {
            max-height: 500px;
        }
        .submenu .nav-link {
            padding-left: 3.2rem;
            font-size: 0.82rem;
        }
        .submenu .nav-link::before {
            content: '▸ ';
            color: rgba(255,255,255,0.25);
            font-size: 0.7rem;
        }
        .submenu .nav-link:hover {
            background: rgba(78,154,241,0.08);
            border-left-color: rgba(78,154,241,0.2);
        }
        .submenu .nav-link.active {
            background: rgba(78,154,241,0.12);
            border-left-color: #4e9af1;
        }

        /* Sidebar User */
        .sidebar-user {
            padding: 1rem 1.5rem;
            border-top: 1px solid rgba(255,255,255,0.08);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-shrink: 0;
        }
        .sidebar-user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(255,255,255,0.15);
        }
        .sidebar-user strong {
            color: #fff;
            font-weight: 600;
            font-size: 0.85rem;
            display: block;
        }
        .sidebar-user small {
            color: rgba(255,255,255,0.5);
            font-size: 0.7rem;
        }

        /* Sidebar Footer */
        .sidebar-footer {
            padding: 0.75rem 1.5rem;
            border-top: 1px solid rgba(255,255,255,0.06);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.7rem;
            color: rgba(255,255,255,0.3);
            flex-shrink: 0;
        }
        .status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #28c76f;
            display: inline-block;
            animation: pulse-dot 2s infinite;
        }
        @keyframes pulse-dot {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }

        /* ============================================================
           MAIN CONTENT
        ============================================================ */
        .admin-main {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Navbar */
        .admin-navbar {
            background: #fff;
            border-bottom: 1px solid #e8ecf1;
            padding: 0.5rem 0;
            position: sticky;
            top: 0;
            z-index: 1040;
        }
        .sidebar-toggle {
            display: flex;
            flex-direction: column;
            gap: 4px;
            background: none;
            border: none;
            padding: 6px 8px;
            cursor: pointer;
            border-radius: 6px;
        }
        .sidebar-toggle:hover {
            background: rgba(0,0,0,0.05);
        }
        .sidebar-toggle span {
            display: block;
            width: 22px;
            height: 2px;
            background: #1a2236;
            border-radius: 2px;
            transition: 0.2s;
        }
        .search-input {
            border-radius: 50px;
            border: 1px solid #e8ecf1;
            padding: 0.4rem 1rem;
            font-size: 0.85rem;
            background: #f8fafc;
            max-width: 320px;
        }
        .search-input:focus {
            border-color: #4e9af1;
            box-shadow: 0 0 0 3px rgba(78,154,241,0.12);
            background: #fff;
        }

        .navbar-actions {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .icon-button {
            background: none;
            border: none;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            font-size: 1.1rem;
            position: relative;
            cursor: pointer;
            transition: 0.2s;
        }
        .icon-button:hover {
            background: rgba(0,0,0,0.05);
            color: #1a2236;
        }
        .notification-dot {
            position: absolute;
            top: 6px;
            right: 6px;
            width: 8px;
            height: 8px;
            background: #ea5455;
            border-radius: 50%;
            border: 2px solid #fff;
        }

        .profile-button {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: none;
            border: none;
            padding: 0.25rem 0.5rem;
            border-radius: 50px;
            cursor: pointer;
            transition: 0.2s;
        }
        .profile-button:hover {
            background: rgba(0,0,0,0.05);
        }
        .profile-name {
            font-weight: 500;
            font-size: 0.85rem;
            color: #1a2236;
        }

        .avatar-img {
            border-radius: 50%;
            object-fit: cover;
        }
        .avatar-sm {
            width: 32px;
            height: 32px;
        }
        .avatar-md {
            width: 40px;
            height: 40px;
        }

        /* Notification Menu */
        .notification-menu {
            min-width: 320px;
            padding: 0.5rem 0;
        }
        .notification-menu .dropdown-item {
            padding: 0.6rem 1rem;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        .notification-menu .dropdown-item:hover {
            background: #f8fafc;
        }
        .notification-title {
            font-weight: 500;
            font-size: 0.85rem;
            color: #1a2236;
        }
        .notification-time {
            font-size: 0.7rem;
            color: #8a93a3;
        }

        /* Dashboard Content */
        .dashboard-content {
            flex: 1;
            padding: 0 0 1.5rem;
        }

        /* Footer */
        .admin-footer {
            background: #fff;
            border-top: 1px solid #e8ecf1;
            padding: 0.75rem 0;
            font-size: 0.78rem;
            color: #8a93a3;
            margin-top: auto;
        }
        .admin-footer .container-fluid {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        /* ============================================================
           SIDEBAR BACKDROP (Mobile)
        ============================================================ */
        .sidebar-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.4);
            z-index: 1040;
            display: none;
            opacity: 0;
            transition: opacity 0.25s ease;
        }
        .sidebar-backdrop.active {
            display: block;
            opacity: 1;
        }

        /* ============================================================
           RESPONSIVE
        ============================================================ */
        @media (max-width: 991.98px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }
            .admin-sidebar.active {
                transform: translateX(0);
            }
            .admin-main {
                margin-left: 0;
            }
            .sidebar-backdrop.active {
                display: block;
            }
        }

        @media (max-width: 575.98px) {
            .profile-name {
                display: none;
            }
            .search-input {
                max-width: 140px;
                font-size: 0.8rem;
            }
            .notification-menu {
                min-width: 280px;
            }
            .brand-title {
                font-size: 0.95rem;
            }
            .brand-icon {
                width: 34px;
                height: 34px;
                font-size: 1.1rem;
            }
        }

        /* ============================================================
           DARK THEME (Toggle support)
        ============================================================ */
        .dark .admin-navbar {
            background: #1e293b;
            border-color: #334155;
        }
        .dark .admin-navbar .search-input {
            background: #0f172a;
            border-color: #334155;
            color: #e2e8f0;
        }
        .dark .admin-navbar .search-input:focus {
            background: #1e293b;
        }
        .dark .admin-footer {
            background: #1e293b;
            border-color: #334155;
            color: #94a3b8;
        }
        .dark .icon-button {
            color: #94a3b8;
        }
        .dark .icon-button:hover {
            background: rgba(255,255,255,0.05);
            color: #e2e8f0;
        }
        .dark .profile-name {
            color: #e2e8f0;
        }
        .dark .sidebar-toggle span {
            background: #e2e8f0;
        }
        .dark .dashboard-content {
            background: #0f172a;
        }
        .dark .notification-menu {
            background: #1e293b;
            border-color: #334155;
        }
        .dark .notification-menu .dropdown-item:hover {
            background: #334155;
        }
        .dark .notification-title {
            color: #e2e8f0;
        }
        .dark .admin-main {
            background: #0f172a;
        }
        .dark .page-heading {
            background: #1e293b;
            border-color: #334155;
        }
        .dark .page-heading .text-muted {
            color: #94a3b8 !important;
        }
    </style>

    @stack('styles')
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen">
        <!-- Admin Shell -->
        <div class="admin-shell">
            <!-- Sidebar Backdrop for Mobile -->
            <div class="sidebar-backdrop" data-sidebar-close></div>

            <!-- ========================================================== -->
            <!-- SIDEBAR -->
            <!-- ========================================================== -->
            <aside class="admin-sidebar" id="adminSidebar" aria-label="Main navigation">
                <!-- Brand -->
                <div class="sidebar-header">
                    <a class="brand-mark" href="{{ route('admin.dashboard') }}">
                        <span class="brand-icon">
                            <i class="bi bi-mortarboard-fill" aria-hidden="true"></i>
                        </span>
                        <span class="brand-copy">
                            <span class="brand-title">S I P <span>A D U</span></span>
                            <span class="brand-subtitle">Sistem Pelatihan SDM</span>
                        </span>
                    </a>
                </div>

                <!-- Navigation -->
                <nav class="sidebar-nav">
                    <!-- Dashboard -->
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <span class="nav-icon"><i class="bi bi-grid-1x2"></i></span>
                        <span class="nav-text">Dashboard</span>
                    </a>

                    <!-- ========================================================== -->
                    <!-- MASTER DATA -->
                    <!-- ========================================================== -->
                    <div class="nav-label">MASTER DATA</div>

                    <a href="{{ route('admin.kategori.index') }}" class="nav-link {{ request()->routeIs('admin.kategori.*') ? 'active' : '' }}">
                        <span class="nav-icon"><i class="bi bi-tags"></i></span>
                        <span class="nav-text">Kategori</span>
                    </a>

                    <a href="{{ route('admin.trainings.index') }}" class="nav-link {{ request()->routeIs('admin.trainings.*') ? 'active' : '' }}">
                        <span class="nav-icon"><i class="bi bi-journal-bookmark"></i></span>
                        <span class="nav-text">Pelatihan</span>
                    </a>

                    <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <span class="nav-icon"><i class="bi bi-people"></i></span>
                        <span class="nav-text">Pengguna</span>
                    </a>

                    <!-- ========================================================== -->
                    <!-- PELATIHAN -->
                    <!-- ========================================================== -->
                    <div class="nav-label">PELATIHAN</div>

                    <a href="{{ route('admin.materi.index') }}" class="nav-link {{ request()->routeIs('admin.materi.*') ? 'active' : '' }}">
                        <span class="nav-icon"><i class="bi bi-book"></i></span>
                        <span class="nav-text">Materi</span>
                    </a>

                    <!-- Quiz dengan Submenu -->
                    <a href="#" class="nav-link" onclick="toggleSubmenu('submenuQuiz')">
                        <span class="nav-icon"><i class="bi bi-question-circle"></i></span>
                        <span class="nav-text">Quiz</span>
                        <span class="chevron" id="chevronQuiz"><i class="bi bi-chevron-right"></i></span>
                    </a>
                    <div class="submenu" id="submenuQuiz">
                        <a href="{{ route('admin.quiz.index') }}" class="nav-link {{ request()->routeIs('admin.quiz.*') ? 'active' : '' }}">
                            <i class="bi bi-list-check"></i> Kelola Quiz
                        </a>
                        <a href="{{ route('admin.quiz.questions.index', ['quiz' => 1]) }}" class="nav-link">
                            <i class="bi bi-file-text"></i> Soal Quiz
                        </a>
                    </div>

                    <a href="{{ route('admin.sertifikat.index') }}" class="nav-link {{ request()->routeIs('admin.sertifikat.*') ? 'active' : '' }}">
                        <span class="nav-icon"><i class="bi bi-award"></i></span>
                        <span class="nav-text">Sertifikat</span>
                    </a>

                    <!-- ========================================================== -->
                    <!-- AKTIVITAS -->
                    <!-- ========================================================== -->
                    <div class="nav-label">AKTIVITAS</div>

                    <a href="#" class="nav-link">
                        <span class="nav-icon"><i class="bi bi-person-plus"></i></span>
                        <span class="nav-text">Pendaftaran</span>
                    </a>

                    <a href="#" class="nav-link">
                        <span class="nav-icon"><i class="bi bi-check-circle"></i></span>
                        <span class="nav-text">Kehadiran</span>
                    </a>

                    <a href="#" class="nav-link">
                        <span class="nav-icon"><i class="bi bi-calendar-event"></i></span>
                        <span class="nav-text">Agenda</span>
                    </a>

                    <a href="#" class="nav-link">
                        <span class="nav-icon"><i class="bi bi-megaphone"></i></span>
                        <span class="nav-text">Pengumuman</span>
                    </a>

                    <!-- ========================================================== -->
                    <!-- LAPORAN -->
                    <!-- ========================================================== -->
                    <div class="nav-label">LAPORAN</div>

                    <a href="{{ route('admin.reports.index') }}" class="nav-link {{ request()->routeIs('admin.reports.index') ? 'active' : '' }}">
                        <span class="nav-icon"><i class="bi bi-bar-chart"></i></span>
                        <span class="nav-text">Laporan Pelatihan</span>
                    </a>

                    <a href="{{ route('admin.reports.users') }}" class="nav-link {{ request()->routeIs('admin.reports.users') ? 'active' : '' }}">
                        <span class="nav-icon"><i class="bi bi-person-lines-fill"></i></span>
                        <span class="nav-text">Laporan Peserta</span>
                    </a>

                    <a href="{{ route('admin.reports.certificates') }}" class="nav-link {{ request()->routeIs('admin.reports.certificates') ? 'active' : '' }}">
                        <span class="nav-icon"><i class="bi bi-file-earmark-check"></i></span>
                        <span class="nav-text">Laporan Sertifikat</span>
                    </a>

                    <!-- ========================================================== -->
                    <!-- PENGATURAN -->
                    <!-- ========================================================== -->
                    <div class="nav-label">PENGATURAN</div>

                    <a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                        <span class="nav-icon"><i class="bi bi-gear"></i></span>
                        <span class="nav-text">Pengaturan Akun</span>
                    </a>
                </nav>

                <!-- User Info -->
                <div class="sidebar-user">
                    <img class="sidebar-user-avatar" 
                         src="{{ Auth::user()->foto ? asset('storage/' . Auth::user()->foto) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->nama ?? 'Admin') . '&background=4e9af1&color=fff&size=40' }}" 
                         alt="{{ Auth::user()->nama ?? 'Admin' }}">
                    <div>
                        <strong>{{ Auth::user()->nama ?? Auth::user()->name ?? 'Admin' }}</strong>
                        <small>{{ ucfirst(Auth::user()->role ?? 'Administrator') }}</small>
                    </div>
                </div>

                <!-- Footer -->
                <div class="sidebar-footer">
                    <span class="status-dot"></span>
                    <span class="sidebar-footer-text">Sistem berjalan normal</span>
                </div>
            </aside>

            <!-- ========================================================== -->
            <!-- MAIN CONTENT -->
            <!-- ========================================================== -->
            <div class="admin-main">
                <!-- Topbar -->
                <nav class="navbar admin-navbar navbar-expand">
                    <div class="container-fluid px-3 px-lg-4">
                        <button class="sidebar-toggle" type="button" data-sidebar-toggle aria-controls="adminSidebar" aria-expanded="true" aria-label="Toggle sidebar">
                            <span></span>
                            <span></span>
                            <span></span>
                        </button>

                        <form class="d-none d-md-flex ms-3 flex-grow-1" role="search" action="{{ route('admin.search') }}" method="GET">
                            <input class="form-control search-input" type="search" name="q" placeholder="Cari pengguna, pelatihan, materi..." aria-label="Search">
                        </form>

                        <div class="navbar-actions ms-auto">
                            <!-- Theme Toggle -->
                            <button class="icon-button theme-toggle" type="button" data-theme-toggle aria-label="Switch color theme" title="Switch color theme">
                                <i class="bi bi-moon-stars" data-theme-icon aria-hidden="true"></i>
                            </button>

                            <!-- Notifications -->
                            <div class="dropdown">
                                <button class="icon-button" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Notifications">
                                    <span class="notification-dot"></span>
                                    <i class="bi bi-bell" aria-hidden="true"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end notification-menu">
                                    <div class="dropdown-header fw-bold text-body">Notifikasi</div>
                                    <a class="dropdown-item" href="#">
                                        <span class="notification-title">Pengguna baru mendaftar</span>
                                        <span class="notification-time">4 menit yang lalu</span>
                                    </a>
                                    <a class="dropdown-item" href="#">
                                        <span class="notification-title">Sertifikat diterbitkan</span>
                                        <span class="notification-time">32 menit yang lalu</span>
                                    </a>
                                    <a class="dropdown-item" href="#">
                                        <span class="notification-title">Pelatihan selesai</span>
                                        <span class="notification-time">1 jam yang lalu</span>
                                    </a>
                                </div>
                            </div>

                            <!-- Profile Dropdown -->
                            <div class="dropdown">
                                <button class="profile-button dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <img class="avatar-img avatar-sm" 
                                         src="{{ Auth::user()->foto ? asset('storage/' . Auth::user()->foto) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->nama ?? 'Admin') . '&background=4e9af1&color=fff&size=32' }}" 
                                         alt="{{ Auth::user()->nama ?? 'Admin' }}">
                                    <span class="profile-name d-none d-sm-inline">{{ Auth::user()->nama ?? Auth::user()->name ?? 'Admin' }}</span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                            <i class="bi bi-person me-2"></i> Profil
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.settings.index') }}">
                                            <i class="bi bi-gear me-2"></i> Pengaturan Akun
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="bi bi-box-arrow-right me-2"></i> Keluar
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>

                <!-- Page Header -->
                @hasSection('header')
                    @yield('header')
                @endif

                <!-- Page Content -->
                <main class="dashboard-content">
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

                        {{ $slot }}
                    </div>
                </main>

                <!-- Footer -->
                <footer class="admin-footer">
                    <div class="container-fluid px-3 px-lg-4">
                        <span>
                            Copyright &copy; {{ date('Y') }} <strong>SIPADU</strong>. All rights reserved.
                        </span>
                        <span>
                            Version {{ config('app.version', '1.0.0') }}
                        </span>
                    </div>
                </footer>
            </div>
        </div>
    </div>

    <!-- ========================================================== -->
    <!-- SCRIPTS -->
    <!-- ========================================================== -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @vite(['resources/js/app.js'])

    @stack('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ============================================================
            // SIDEBAR TOGGLE
            // ============================================================
            const sidebarToggle = document.querySelector('[data-sidebar-toggle]');
            const sidebar = document.getElementById('adminSidebar');
            const backdrop = document.querySelector('[data-sidebar-close]');

            if (sidebarToggle && sidebar) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('active');
                    if (backdrop) {
                        backdrop.classList.toggle('active');
                    }
                    const expanded = this.getAttribute('aria-expanded') === 'true' ? 'false' : 'true';
                    this.setAttribute('aria-expanded', expanded);
                });
            }

            if (backdrop) {
                backdrop.addEventListener('click', function() {
                    sidebar.classList.remove('active');
                    this.classList.remove('active');
                    if (sidebarToggle) {
                        sidebarToggle.setAttribute('aria-expanded', 'false');
                    }
                });
            }

            // ============================================================
            // THEME TOGGLE
            // ============================================================
            const themeToggle = document.querySelector('[data-theme-toggle]');
            const themeIcon = document.querySelector('[data-theme-icon]');

            if (themeToggle) {
                const savedTheme = localStorage.getItem('theme');
                if (savedTheme === 'dark') {
                    document.documentElement.classList.add('dark');
                    if (themeIcon) {
                        themeIcon.className = 'bi bi-sun-fill';
                    }
                }

                themeToggle.addEventListener('click', function() {
                    const isDark = document.documentElement.classList.toggle('dark');
                    localStorage.setItem('theme', isDark ? 'dark' : 'light');
                    if (themeIcon) {
                        themeIcon.className = isDark ? 'bi bi-sun-fill' : 'bi bi-moon-stars';
                    }
                });
            }

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
            // AUTO CLOSE ALERTS
            // ============================================================
            setTimeout(function() {
                document.querySelectorAll('.alert').forEach(function(alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);

            // ============================================================
            // AUTO CLOSE SIDEBAR ON RESIZE
            // ============================================================
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 992 && sidebar && sidebar.classList.contains('active')) {
                    sidebar.classList.remove('active');
                    if (backdrop) {
                        backdrop.classList.remove('active');
                    }
                    if (sidebarToggle) {
                        sidebarToggle.setAttribute('aria-expanded', 'false');
                    }
                }
            });

            // ============================================================
            // SEARCH WITH DEBOUNCE
            // ============================================================
            document.querySelector('.search-input')?.addEventListener('input', function(e) {
                clearTimeout(this.timeout);
                this.timeout = setTimeout(() => {
                    if (this.value.length >= 3) {
                        this.closest('form').submit();
                    }
                }, 500);
            });
        });
    </script>
</body>
</html>