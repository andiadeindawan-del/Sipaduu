<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Beranda') — Sistem Pelatihan SDM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4e9af1;
            --primary-dark: #3a7bc8;
            --secondary: #1a2236;
            --accent: #28c76f;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #1a2236;
            background: #f8fafc;
        }

        /* ============================================================
           NAVBAR
        ============================================================ */
        .navbar {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 1px 8px rgba(0,0,0,.05);
            padding: .9rem 0;
            transition: all .3s ease;
        }
        .navbar.scrolled {
            box-shadow: 0 4px 20px rgba(0,0,0,.08);
        }
        .navbar-brand {
            font-weight: 800;
            color: #1a2236 !important;
            font-size: 1.2rem;
        }
        .navbar-brand span {
            color: var(--primary);
        }
        .navbar-brand i {
            color: var(--primary);
        }

        /* ============================================================
           NAVBAR MENU - DI TENGAH
        ============================================================ */
        .navbar-nav {
            gap: 0.25rem;
        }

        /* Desktop: menu utama di tengah */
        @media (min-width: 992px) {
            .navbar .container {
                position: relative;
            }
            .navbar-nav.mx-auto {
                position: absolute;
                left: 50%;
                transform: translateX(-50%);
                display: flex;
                justify-content: center;
            }
            .navbar-nav.ms-auto {
                margin-left: auto !important;
            }
            .navbar-nav .nav-item .nav-link {
                padding: 0.5rem 1rem;
                font-weight: 500;
                color: #495362 !important;
                transition: color .2s;
                border-radius: 8px;
            }
            .navbar-nav .nav-item .nav-link:hover,
            .navbar-nav .nav-item .nav-link.active {
                color: var(--primary) !important;
                background: rgba(78,154,241,0.08);
            }
        }

        /* Mobile: semua menu di kiri */
        @media (max-width: 991.98px) {
            .navbar-nav.mx-auto {
                margin-left: 0 !important;
                transform: none;
                position: static;
            }
            .navbar-nav .nav-item .nav-link {
                padding: 0.6rem 1rem;
                border-radius: 8px;
            }
            .navbar-nav .nav-item .nav-link:hover,
            .navbar-nav .nav-item .nav-link.active {
                background: rgba(78,154,241,0.08);
                color: var(--primary) !important;
            }
        }

        .nav-link {
            font-weight: 500;
            color: #495362 !important;
            transition: color .2s;
            padding: 0.5rem 1rem;
        }
        .nav-link.active,
        .nav-link:hover {
            color: var(--primary) !important;
        }

        /* ============================================================
           BUTTONS
        ============================================================ */
        .btn-outline-primary {
            border-color: var(--primary);
            color: var(--primary);
        }
        .btn-outline-primary:hover {
            background: var(--primary);
            color: #fff;
        }
        .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
        }
        .btn-primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
        }
        .btn-success {
            background: var(--accent);
            border-color: var(--accent);
        }

        .btn-sm {
            padding: 0.4rem 1rem;
            font-size: 0.85rem;
        }

        /* ============================================================
           HERO
        ============================================================ */
       /* ============================================================
   HERO SECTION - MODERN
============================================================ */
.hero {
    background: linear-gradient(135deg, #0f1724 0%, #1a2236 30%, #2a3654 60%, #1a2236 100%);
    padding: 6rem 0 5rem;
    position: relative;
    overflow: hidden;
    min-height: 600px;
    display: flex;
    align-items: center;
}

/* Shapes Background */
.hero-shapes {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    overflow: hidden;
}
.shape {
    position: absolute;
    border-radius: 50%;
    opacity: 0.08;
}
.shape-1 {
    width: 400px;
    height: 400px;
    background: var(--primary);
    top: -100px;
    right: -100px;
    animation: float 8s ease-in-out infinite;
}
.shape-2 {
    width: 300px;
    height: 300px;
    background: var(--accent);
    bottom: -50px;
    left: -50px;
    animation: float 10s ease-in-out infinite reverse;
}
.shape-3 {
    width: 200px;
    height: 200px;
    background: #ff9f43;
    top: 50%;
    right: 20%;
    animation: float 6s ease-in-out infinite;
}
.shape-4 {
    width: 150px;
    height: 150px;
    background: #ea5455;
    bottom: 20%;
    left: 10%;
    animation: float 12s ease-in-out infinite reverse;
}

@keyframes float {
    0%, 100% { transform: translate(0, 0) scale(1); }
    33% { transform: translate(30px, -30px) scale(1.1); }
    66% { transform: translate(-20px, 20px) scale(0.9); }
}

/* Hero Badge */
.hero-badge {
    margin-bottom: 1.5rem;
}
.badge-pulse {
    display: inline-block;
    padding: 0.5rem 1.25rem;
    background: rgba(78, 154, 241, 0.15);
    border: 1px solid rgba(78, 154, 241, 0.3);
    border-radius: 50px;
    color: #4e9af1;
    font-size: 0.8rem;
    font-weight: 600;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    animation: pulse-border 2s ease-in-out infinite;
}
.badge-pulse i {
    font-size: 0.9rem;
}

@keyframes pulse-border {
    0%, 100% { box-shadow: 0 0 0 0 rgba(78, 154, 241, 0.3); }
    50% { box-shadow: 0 0 0 8px rgba(78, 154, 241, 0); }
}

/* Hero Title */
.hero-title {
    font-weight: 800;
    font-size: 3rem;
    line-height: 1.15;
    color: #fff;
    margin-bottom: 1.25rem;
}
.hero-title .highlight {
    background: linear-gradient(135deg, #4e9af1, #6ab0f5);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    position: relative;
}
.hero-title .highlight::after {
    content: '';
    position: absolute;
    bottom: -4px;
    left: 0;
    width: 100%;
    height: 3px;
    background: linear-gradient(90deg, #4e9af1, transparent);
    border-radius: 2px;
}

/* Hero Description */
.hero-description {
    color: rgba(255,255,255,0.75);
    font-size: 1.1rem;
    max-width: 540px;
    line-height: 1.8;
    margin-bottom: 2rem;
}

/* Hero Actions */
.hero-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    margin-bottom: 3rem;
}
.hero-btn-primary {
    padding: 0.85rem 2.5rem;
    font-weight: 600;
    border-radius: 50px;
    background: linear-gradient(135deg, #4e9af1, #3a7bc8);
    border: none;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}
.hero-btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(78, 154, 241, 0.4);
}
.hero-btn-primary .btn-ripple {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255,255,255,0.2);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
}
.hero-btn-primary:hover .btn-ripple {
    width: 300px;
    height: 300px;
}
.hero-btn-outline {
    padding: 0.85rem 2.5rem;
    font-weight: 600;
    border-radius: 50px;
    border: 2px solid rgba(255,255,255,0.3);
    transition: all 0.3s ease;
}
.hero-btn-outline:hover {
    background: rgba(255,255,255,0.1);
    border-color: #4e9af1;
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(78, 154, 241, 0.2);
}

/* Hero Stats - Modern */
.hero-stats {
    display: flex;
    align-items: center;
    gap: 2rem;
    padding-top: 2rem;
    border-top: 1px solid rgba(255,255,255,0.08);
}
.stat-item {
    flex: 1;
}
.stat-number {
    font-size: 2.2rem;
    font-weight: 800;
    background: linear-gradient(135deg, #4e9af1, #6ab0f5);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    line-height: 1;
}
.stat-number .counter {
    -webkit-text-fill-color: transparent;
}
.stat-label {
    color: rgba(255,255,255,0.6);
    font-size: 0.85rem;
    font-weight: 500;
    margin-top: 0.25rem;
}
.stat-label i {
    color: #4e9af1;
    margin-right: 0.25rem;
}
.stat-trend {
    font-size: 0.7rem;
    font-weight: 600;
    margin-top: 0.25rem;
    display: inline-flex;
    align-items: center;
    gap: 0.15rem;
}
.trend-up {
    color: #28c76f;
}
.trend-up i {
    font-size: 1rem;
}
.stat-divider {
    width: 1px;
    height: 40px;
    background: rgba(255,255,255,0.1);
}

/* Hero Image */
.hero-image-wrapper {
    position: relative;
    padding: 1rem;
}
.hero-image-container {
    position: relative;
    border-radius: 24px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
}
.hero-image {
    width: 100%;
    height: 420px;
    object-fit: cover;
    object-position: center;
    transition: transform 0.5s ease;
}
.hero-image-container:hover .hero-image {
    transform: scale(1.03);
}
.hero-image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
}
.floating-card {
    position: absolute;
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(10px);
    padding: 0.75rem 1.25rem;
    border-radius: 12px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.15);
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
    font-size: 0.85rem;
    color: #1a2236;
    animation: float-card 4s ease-in-out infinite;
}
.floating-card i {
    font-size: 1.2rem;
    color: #4e9af1;
}
.card-1 {
    top: 10%;
    right: 5%;
    animation-delay: 0s;
}
.card-2 {
    bottom: 20%;
    left: 5%;
    animation-delay: 1s;
}
.card-3 {
    top: 50%;
    right: 0;
    transform: translateY(-50%);
    animation-delay: 2s;
}

@keyframes float-card {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

/* ============================================================
   RESPONSIVE HERO
============================================================ */
@media (max-width: 991.98px) {
    .hero {
        padding: 4rem 0;
        min-height: auto;
    }
    .hero-title {
        font-size: 2.2rem;
    }
    .hero-stats {
        flex-direction: column;
        gap: 1rem;
    }
    .stat-divider {
        display: none;
    }
    .stat-item {
        text-align: center;
    }
    .stat-number {
        font-size: 1.8rem;
    }
    .floating-card {
        display: none;
    }
}

@media (max-width: 576px) {
    .hero-title {
        font-size: 1.8rem;
    }
    .hero-actions {
        flex-direction: column;
    }
    .hero-btn-primary,
    .hero-btn-outline {
        width: 100%;
        justify-content: center;
    }
    .badge-pulse {
        font-size: 0.65rem;
        padding: 0.3rem 0.8rem;
    }
}
        .hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 60%;
            height: 200%;
            background: radial-gradient(ellipse, rgba(78,154,241,0.1) 0%, transparent 70%);
            pointer-events: none;
        }
        .hero h1 {
            font-weight: 800;
            font-size: 2.8rem;
            line-height: 1.2;
        }
        .hero p {
            color: rgba(255,255,255,.75);
            font-size: 1.1rem;
            max-width: 540px;
        }
        .hero .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
            padding: 0.75rem 2rem;
            font-weight: 600;
        }
        .hero .btn-primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(78,154,241,0.3);
        }
        .hero .btn-outline-light {
            padding: 0.75rem 2rem;
            font-weight: 600;
        }
        .hero .btn-outline-light:hover {
            background: rgba(255,255,255,0.1);
            transform: translateY(-2px);
        }
        .hero-stats {
            display: flex;
            gap: 3rem;
            margin-top: 2rem;
        }
        .hero-stats .stat {
            text-align: center;
        }
        .hero-stats .stat .number {
            font-size: 2rem;
            font-weight: 800;
            color: var(--primary);
        }
        .hero-stats .stat .label {
            font-size: 0.85rem;
            color: rgba(255,255,255,.6);
        }

        /* ============================================================
           SECTION
        ============================================================ */
        .section-pad {
            padding: 5rem 0;
        }
        .section-title {
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        .section-subtitle {
            color: #8a93a3;
            font-size: 1.1rem;
            max-width: 600px;
            margin: 0 auto;
        }

        /* ============================================================
           CARDS
        ============================================================ */
        .card-feature {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 2px 12px rgba(0,0,0,.06);
            padding: 2rem;
            height: 100%;
            transition: all .3s ease;
            background: #fff;
        }
        .card-feature:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(0,0,0,.1);
        }
        .card-feature .icon {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        .card-feature .icon-primary { background: #eaf1fd; color: var(--primary); }
        .card-feature .icon-success { background: #dff6e8; color: var(--accent); }
        .card-feature .icon-warning { background: #fef3e2; color: #ff9f43; }
        .card-feature .icon-danger { background: #fde8e8; color: #ea5455; }
        .card-feature .icon-info { background: #e0f4fe; color: #17a2b8; }
        .card-feature .icon-purple { background: #ede7f6; color: #6c5ce7; }

        .card-feature h5 {
            font-weight: 700;
            font-size: 1.1rem;
        }
        .card-feature p {
            color: #8a93a3;
            font-size: 0.92rem;
        }

        /* ============================================================
           TESTIMONIAL
        ============================================================ */
        .testimonial-card {
            background: #fff;
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 2px 12px rgba(0,0,0,.06);
            height: 100%;
        }
        .testimonial-card .stars {
            color: #ffc107;
            font-size: 0.9rem;
        }
        .testimonial-card .quote {
            font-style: italic;
            color: #495362;
            margin: 1rem 0;
        }
        .testimonial-card .author {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .testimonial-card .author .avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: var(--primary);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.2rem;
        }
        .testimonial-card .author .name {
            font-weight: 600;
            margin-bottom: 0;
        }
        .testimonial-card .author .role {
            font-size: 0.85rem;
            color: #8a93a3;
            margin-bottom: 0;
        }

        /* ============================================================
           CTA
        ============================================================ */
        .cta-section {
            background: linear-gradient(135deg, var(--secondary), #2a3654);
            color: #fff;
            padding: 4rem 0;
            border-radius: 1.5rem;
        }
        .cta-section h2 {
            font-weight: 700;
        }
        .cta-section p {
            color: rgba(255,255,255,.7);
        }

        /* ============================================================
           FOOTER
        ============================================================ */
        footer {
            background: #14192a;
            color: rgba(255,255,255,.65);
            padding: 4rem 0 1.5rem;
        }
        footer h6 {
            color: #fff;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        footer a {
            color: rgba(255,255,255,.6);
            text-decoration: none;
            transition: color .2s;
        }
        footer a:hover {
            color: var(--primary);
        }
        footer .social-icons a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(255,255,255,.08);
            color: rgba(255,255,255,.6);
            transition: all .2s;
        }
        footer .social-icons a:hover {
            background: var(--primary);
            color: #fff;
            transform: translateY(-2px);
        }
        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,.08);
            margin-top: 2rem;
            padding-top: 1.5rem;
            font-size: .85rem;
            text-align: center;
        }

        /* ============================================================
           RESPONSIVE
        ============================================================ */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2rem;
            }
            .hero-stats {
                gap: 1.5rem;
                flex-wrap: wrap;
            }
            .hero-stats .stat .number {
                font-size: 1.5rem;
            }
            .section-pad {
                padding: 3rem 0;
            }
            .section-title {
                font-size: 1.6rem;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

<!-- ============================================================
     NAVBAR
============================================================ -->
<nav class="navbar navbar-expand-lg sticky-top" id="mainNav">
    <div class="container">
        <a class="navbar-brand" href="{{ route('landing.index') }}" style="display: flex; align-items: center; gap: 10px;">
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
            
            <span style="font-weight: 800; color: #1a2236; font-size: 1.2rem;">
                S I P <span style="color: #4e9af1;">A D U</span>
            </span>
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMain">
            <!-- ============================================================
                 MENU UTAMA - DI TENGAH
            ============================================================ -->
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('landing.index') ? 'active' : '' }}" 
                       href="{{ route('landing.index') }}">
                        <i class="bi bi-house me-1 d-lg-none"></i> Beranda
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('landing.pelatihan*') ? 'active' : '' }}" 
                       href="{{ route('landing.pelatihan.index') }}">
                        <i class="bi bi-journal-bookmark me-1 d-lg-none"></i> Pelatihan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('landing.pengumuman*') ? 'active' : '' }}" 
                       href="{{ route('landing.pengumuman.index') }}">
                        <i class="bi bi-megaphone me-1 d-lg-none"></i> Pengumuman
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('landing.agenda*') ? 'active' : '' }}" 
                       href="{{ route('landing.agenda.index') }}">
                        <i class="bi bi-calendar-event me-1 d-lg-none"></i> Agenda
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('landing.tentang*') ? 'active' : '' }}" 
                       href="{{ route('landing.tentang.index') }}">
                        <i class="bi bi-info-circle me-1 d-lg-none"></i> Tentang
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('landing.faq*') ? 'active' : '' }}" 
                       href="{{ route('landing.faq.index') }}">
                        <i class="bi bi-question-circle me-1 d-lg-none"></i> FAQ
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('landing.kontak*') ? 'active' : '' }}" 
                       href="{{ route('landing.kontak.index') }}">
                        <i class="bi bi-envelope me-1 d-lg-none"></i> Kontak
                    </a>
                </li>
            </ul>

            <!-- ============================================================
                 AUTH BUTTONS - DI KANAN
            ============================================================ -->
            <ul class="navbar-nav ms-auto">
                <li class="nav-item ms-lg-2">
                    @auth
                        <a href="{{ auth()->user()->role === 'peserta' ? route('peserta.dashboard') : route('admin.dashboard') }}" 
                           class="btn btn-primary btn-sm px-3">
                            <i class="bi bi-speedometer2 me-1"></i> Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary btn-sm px-3 me-2">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-primary btn-sm px-3">
                            <i class="bi bi-person-plus me-1"></i> Daftar
                        </a>
                    @endauth
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- ============================================================
     CONTENT
============================================================ -->
@yield('content')

<!-- ============================================================
     FOOTER
============================================================ -->
<footer>
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <h6><i class="bi bi-mortarboard-fill me-1"></i>Pelatihan SDM</h6>
                <p class="small">Platform pelatihan dan pengembangan kompetensi sumber daya manusia perusahaan.</p>
                <div class="social-icons mt-3 d-flex gap-2">
                    <a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                    <a href="#" aria-label="Twitter"><i class="bi bi-twitter-x"></i></a>
                    <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                    <a href="#" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
                    <a href="#" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
                </div>
            </div>
            <div class="col-md-2">
                <h6>Menu</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="{{ route('landing.index') }}">Beranda</a></li>
                    <li class="mb-2"><a href="{{ route('landing.pelatihan.index') }}">Pelatihan</a></li>
                    <li class="mb-2"><a href="{{ route('landing.pengumuman.index') }}">Pengumuman</a></li>
                    <li class="mb-2"><a href="{{ route('landing.agenda.index') }}">Agenda</a></li>
                    <li class="mb-2"><a href="{{ route('landing.tentang.index') }}">Tentang</a></li>
                    <li class="mb-2"><a href="{{ route('landing.faq.index') }}">FAQ</a></li>
                    <li class="mb-2"><a href="{{ route('landing.kontak.index') }}">Kontak</a></li>
                </ul>
            </div>
            <div class="col-md-3">
                <h6>Akun</h6>
                <ul class="list-unstyled small">
                    @auth
                        <li class="mb-2"><a href="{{ auth()->user()->role === 'peserta' ? route('peserta.dashboard') : route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="mb-2">
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-link text-decoration-none p-0 text-white-50" style="font-size: inherit;">
                                    <i class="bi bi-box-arrow-right me-1"></i> Keluar
                                </button>
                            </form>
                        </li>
                    @else
                        <li class="mb-2"><a href="{{ route('login') }}">Masuk</a></li>
                        <li class="mb-2"><a href="{{ route('register') }}">Daftar</a></li>
                    @endauth
                </ul>
            </div>
            <div class="col-md-3">
                <h6>Kontak</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2"><i class="bi bi-envelope me-2"></i> andiadeindawan@gmail.com</li>
                    <li class="mb-2"><i class="bi bi-telephone me-2"></i> 0822-9194-7554</li>
                    <li class="mb-2"><i class="bi bi-geo-alt me-2"></i> Mamuju-Sulawesi Barat, Indonesia</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            &copy; {{ date('Y') }} Sistem Pelatihan SDM. Seluruh hak cipta dilindungi.
            <span class="mx-2">|</span>
            <a href="#" class="text-white-50 text-decoration-none">Kebijakan Privasi</a>
            <span class="mx-2">|</span>
            <a href="#" class="text-white-50 text-decoration-none">Syarat &amp; Ketentuan</a>
        </div>
    </div>
</footer>

<!-- ============================================================
     SCRIPTS
============================================================ -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ============================================================
        // NAVBAR SCROLL EFFECT
        // ============================================================
        const navbar = document.getElementById('mainNav');
        let lastScroll = 0;

        window.addEventListener('scroll', function() {
            const currentScroll = window.pageYOffset;
            if (currentScroll > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
            lastScroll = currentScroll;
        });

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
        // MOBILE NAVBAR CLOSE ON LINK CLICK
        // ============================================================
        const navLinks = document.querySelectorAll('#navMain .nav-link');
        const navbarCollapse = document.getElementById('navMain');
        const bsCollapse = bootstrap.Collapse.getInstance(navbarCollapse);

        navLinks.forEach(function(link) {
            link.addEventListener('click', function() {
                if (window.innerWidth < 992 && bsCollapse) {
                    bsCollapse.hide();
                }
            });
        });
    });
</script>
@stack('scripts')
</body>
</html>