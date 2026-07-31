@extends('layouts.landing')

@section('title', 'Sistem Pelatihan SDM')

@section('content')
<!-- ============================================================
     HERO SECTION - DENGAN EFEK BLUR HALUS
============================================================ -->
<section class="hero">
    <!-- Background Blur -->
    <div class="hero-bg-blur">
        <div class="hero-bg-image" style="background-image: url('{{ asset('assets/images/pelatihan.jpg') }}');"></div>
        <div class="hero-bg-gradient"></div>
    </div>

   
    
   <div class="container position-relative">
    <div class="row justify-content-center">
        <div class="col-lg-8 text-center">
            <h1 class="hero-title">
                Sistem Pelatihan SDM Usaha<br>
                <span class="">Dinas Koperasi, Perindustrian, dan Perdagangan Sulawesi Barat</span>
            </h1>
            
            <p class="hero-description">
                Platform pelatihan online yang dirancang untuk mengembangkan 
                kompetensi karyawan dengan materi terkini dan metode pembelajaran interaktif.
            </p>

            <!-- CTA Buttons -->
            <div class="hero-actions justify-content-center">
                <a href="{{ route('landing.pelatihan.index') }}" class="btn btn-primary btn-lg hero-btn-primary">
                    <i class="bi bi-journal-bookmark me-2"></i> Lihat Pelatihan
                    <span class="btn-ripple"></span>
                </a>
                <a href="#features" class="btn btn-outline-light btn-lg hero-btn-outline">
                    <i class="bi bi-arrow-down me-2"></i> Pelajari Lebih Lanjut
                </a>
            </div>

            <!-- Stats -->
            <div class="hero-stats justify-content-center">
                <div class="stat-item">
                    <div class="stat-number" data-count="{{ $totalParticipants ?? 0 }}">
                        <span class="counter">0</span>
                    </div>
                    <div class="stat-label">
                        <i class="bi bi-people-fill"></i> Peserta Aktif
                    </div>
                    <div class="stat-trend trend-up">
                        <i class="bi bi-arrow-up-short"></i> 12% bulan ini
                    </div>
                </div>
                <div class="stat-divider"></div>
                <div class="stat-item">
                    <div class="stat-number" data-count="{{ $totalTrainings ?? 0 }}">
                        <span class="counter">0</span>
                    </div>
                    <div class="stat-label">
                        <i class="bi bi-journal-bookmark-fill"></i> Pelatihan
                    </div>
                    <div class="stat-trend trend-up">
                        <i class="bi bi-arrow-up-short"></i> 8% bulan ini
                    </div>
                </div>
                <div class="stat-divider"></div>
                <div class="stat-item">
                    <div class="stat-number" data-count="{{ $totalCertificates ?? 0 }}">
                        <span class="counter">0</span>
                    </div>
                    <div class="stat-label">
                        <i class="bi bi-award-fill"></i> Sertifikat
                    </div>
                    <div class="stat-trend trend-up">
                        <i class="bi bi-arrow-up-short"></i> 15% bulan ini
                    </div>
                </div>
            </div>

            <!-- Scroll Indicator -->
            <div class="hero-scroll">
                <a href="#features" class="scroll-btn">
                    <span class="scroll-text">Scroll untuk menjelajahi</span>
                    <span class="scroll-line">
                        <span class="scroll-dot"></span>
                    </span>
                </a>
            </div>
        </div>
    </div>
</div>
</section>

<!-- ============================================================
     FEATURES SECTION
============================================================ -->
<section id="features" class="section-pad">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Mengapa Memilih Platform Kami?</h2>
            <p class="section-subtitle">
                Kami menyediakan solusi pelatihan lengkap untuk pengembangan SDM perusahaan Anda.
            </p>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-lg-4">
                <div class="card-feature text-center">
                    <div class="icon icon-primary mx-auto">
                        <i class="bi bi-mortarboard"></i>
                    </div>
                    <h5>Materi Terstruktur</h5>
                    <p>Materi pelatihan disusun secara sistematis dan mudah dipahami oleh semua level karyawan.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card-feature text-center">
                    <div class="icon icon-success mx-auto">
                        <i class="bi bi-person-video"></i>
                    </div>
                    <h5>Pembelajaran Interaktif</h5>
                    <p>Metode pembelajaran interaktif dengan video, kuis, dan studi kasus untuk pemahaman maksimal.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card-feature text-center">
                    <div class="icon icon-warning mx-auto">
                        <i class="bi bi-award"></i>
                    </div>
                    <h5>Sertifikat Resmi</h5>
                    <p>Dapatkan sertifikat resmi setelah menyelesaikan pelatihan sebagai bukti kompetensi.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card-feature text-center">
                    <div class="icon icon-danger mx-auto">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <h5>Akses 24/7</h5>
                    <p>Belajar kapan saja dan di mana saja dengan akses 24 jam penuh dari berbagai perangkat.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card-feature text-center">
                    <div class="icon icon-info mx-auto">
                        <i class="bi bi-bar-chart-line"></i>
                    </div>
                    <h5>Tracking Progress</h5>
                    <p>Pantau perkembangan belajar karyawan dengan fitur tracking progress yang akurat.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card-feature text-center">
                    <div class="icon icon-purple mx-auto">
                        <i class="bi bi-people"></i>
                    </div>
                    <h5>Instruktur Berpengalaman</h5>
                    <p>Dibimbing oleh instruktur profesional dengan pengalaman di bidangnya masing-masing.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     POPULAR TRAININGS SECTION
============================================================ -->
<section class="section-pad">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="section-title">Pelatihan Paling Populer</h2>
                <p class="section-subtitle text-start">Pelatihan yang paling banyak diikuti oleh peserta.</p>
            </div>
            <a href="{{ route('landing.pelatihan.index') }}" class="btn btn-outline-primary">
                Lihat Semua <i class="bi bi-chevron-right ms-1"></i>
            </a>
        </div>

        <div class="row g-4">
            @if(isset($popularTrainings) && $popularTrainings->count() > 0)
                @foreach($popularTrainings as $training)
                <div class="col-md-6 col-lg-4">
                    <div class="card-feature p-0 overflow-hidden">
                        @if($training->gambar)
                        <img src="{{ asset('storage/' . $training->gambar) }}" 
                             alt="{{ $training->judul }}" 
                             class="img-fluid w-100" 
                             style="height: 200px; object-fit: cover;">
                        @else
                        <div style="height: 200px; background: #e9ecef; display: flex; align-items: center; justify-content: center; color: #6c757d; font-size: 3rem;">
                            <i class="bi bi-journal-bookmark"></i>
                        </div>
                        @endif
                        <div class="p-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="fw-bold mb-0">{{ $training->judul }}</h5>
                                <span class="badge bg-warning text-dark">
                                    <i class="bi bi-star-fill me-1"></i>{{ number_format($training->rating ?? 4.5, 1) }}
                                </span>
                            </div>
                            <p class="text-muted small">{{ Str::limit($training->deskripsi ?? '', 100) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex gap-3 text-muted small">
                                    <span><i class="bi bi-clock me-1"></i>{{ $training->durasi ?? '-' }} jam</span>
                                    <span><i class="bi bi-people me-1"></i>{{ $training->participants_count ?? 0 }}</span>
                                </div>
                                <a href="{{ route('landing.pelatihan.detail', $training->id) }}" class="btn btn-sm btn-primary">
                                    Detail <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="col-12 text-center">
                    <p class="text-muted">Belum ada pelatihan populer.</p>
                </div>
            @endif
        </div>
    </div>
</section>

<!-- ============================================================
     CTA SECTION
============================================================ -->
<section class="section-pad">
    <div class="container">
        <div class="cta-section p-5 text-center">
            <h2 class="mb-3">Siap Tingkatkan Kompetensi Tim Anda?</h2>
            <p class="mx-auto" style="max-width: 600px;">
                Bergabunglah dengan ribuan perusahaan yang telah mempercayakan 
                pengembangan SDM mereka kepada kami.
            </p>
            <div class="d-flex flex-wrap gap-3 justify-content-center mt-4">
                <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-5">
                    <i class="bi bi-person-plus me-2"></i> Daftar Sekarang
                </a>
                <a href="{{ route('landing.pelatihan.index') }}" class="btn btn-outline-light btn-lg px-5">
                    <i class="bi bi-journal-bookmark me-2"></i> Lihat Pelatihan
                </a>
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
    /* ============================================================
       HERO BACKGROUND BLUR - HALUS & JELAS
    ============================================================ */
    .hero-bg-blur {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
        z-index: 0;
    }
    .hero-bg-image {
        width: 100%;
        height: 100%;
        background-size: cover;
        background-position: center;
        filter: blur(4px) brightness(0.5) saturate(1.1);
        transform: scale(1.05);
        transition: filter 0.8s ease;
    }
    .hero-bg-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, 
            rgba(15, 23, 36, 0.75) 0%, 
            rgba(26, 34, 54, 0.6) 30%, 
            rgba(42, 54, 84, 0.5) 60%, 
            rgba(15, 23, 36, 0.70) 100%
        );
        z-index: 1;
    }
    .hero-bg-gradient {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: radial-gradient(ellipse at 50% 50%, 
            rgba(78, 154, 241, 0.05) 0%, 
            transparent 70%
        );
        z-index: 2;
    }

    /* ============================================================
       HERO CONTAINER
    ============================================================ */
    .hero {
        position: relative;
        overflow: hidden;
        min-height: 650px;
        display: flex;
        align-items: center;
        background: transparent;
        padding: 6rem 0 4rem;
    }
    .hero .container {
        position: relative;
        z-index: 3;
    }

    /* ============================================================
       HERO SHAPES
    ============================================================ */
    .hero-shapes {
        z-index: 1;
    }

    /* ============================================================
       HERO TITLE
    ============================================================ */
    .hero-title {
        font-weight: 800;
        font-size: 3.5rem;
        line-height: 1.15;
        color: #fff;
        margin-bottom: 1.25rem;
    }
    .hero-title .highlight {
        background: linear-gradient(135deg, #6ab0f5, #4e9af1);
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

    /* ============================================================
       HERO DESCRIPTION
    ============================================================ */
    .hero-description {
        color: rgba(255, 255, 255, 0.85);
        font-size: 1.2rem;
        max-width: 650px;
        margin: 0 auto 2rem;
        line-height: 1.8;
    }

    /* ============================================================
       HERO ACTIONS
    ============================================================ */
    .hero-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 3rem;
    }
    .hero-btn-primary {
        padding: 0.85rem 2.8rem;
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
        padding: 0.85rem 2.8rem;
        font-weight: 600;
        border-radius: 50px;
        border: 2px solid rgba(255,255,255,0.25);
        transition: all 0.3s ease;
    }
    .hero-btn-outline:hover {
        background: rgba(255,255,255,0.1);
        border-color: #4e9af1;
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(78, 154, 241, 0.2);
    }

    /* ============================================================
       HERO STATS
    ============================================================ */
    .hero-stats {
        display: flex;
        align-items: center;
        gap: 2.5rem;
        padding-top: 2rem;
        border-top: 1px solid rgba(255,255,255,0.08);
        margin-bottom: 2rem;
    }
    .stat-item {
        flex: 1;
        max-width: 200px;
    }
    .stat-number {
        font-size: 2.5rem;
        font-weight: 800;
        background: linear-gradient(135deg, #6ab0f5, #4e9af1);
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
        height: 50px;
        background: rgba(255,255,255,0.1);
    }

    /* ============================================================
       SCROLL INDICATOR
    ============================================================ */
    .hero-scroll {
        margin-top: 1rem;
    }
    .scroll-btn {
        display: inline-flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
        color: rgba(255,255,255,0.4);
        text-decoration: none;
        transition: color 0.3s ease;
        cursor: pointer;
    }
    .scroll-btn:hover {
        color: rgba(255,255,255,0.8);
    }
    .scroll-text {
        font-size: 0.75rem;
        font-weight: 500;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }
    .scroll-line {
        display: block;
        width: 1px;
        height: 40px;
        background: rgba(255,255,255,0.15);
        position: relative;
        overflow: hidden;
    }
    .scroll-dot {
        position: absolute;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 3px;
        height: 12px;
        background: #4e9af1;
        border-radius: 2px;
        animation: scroll-down 1.8s ease-in-out infinite;
    }

    @keyframes scroll-down {
        0% { top: -20%; opacity: 1; }
        100% { top: 100%; opacity: 0; }
    }

    /* ============================================================
       RESPONSIVE
    ============================================================ */
    @media (max-width: 991.98px) {
        .hero {
            padding: 4rem 0;
            min-height: auto;
        }
        .hero-title {
            font-size: 2.5rem;
        }
        .hero-description {
            font-size: 1rem;
            max-width: 100%;
        }
        .hero-stats {
            flex-direction: row;
            flex-wrap: wrap;
            justify-content: center;
            gap: 1rem;
        }
        .stat-divider {
            display: none;
        }
        .stat-item {
            flex: 0 0 auto;
            min-width: 120px;
            text-align: center;
        }
        .stat-number {
            font-size: 2rem;
        }
        .hero-bg-image {
            filter: blur(6px) brightness(0.45);
        }
        .hero-scroll {
            display: none;
        }
    }

    @media (max-width: 576px) {
        .hero-title {
            font-size: 1.8rem;
        }
        .hero-actions {
            flex-direction: column;
            align-items: center;
        }
        .hero-btn-primary,
        .hero-btn-outline {
            width: 100%;
            justify-content: center;
            padding: 0.75rem 1.5rem;
        }
        .hero-stats {
            flex-direction: column;
            gap: 0.75rem;
        }
        .stat-item {
            min-width: unset;
            width: 100%;
        }
        .stat-number {
            font-size: 1.6rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ============================================================
    // COUNTER ANIMATION
    // ============================================================
    const counters = document.querySelectorAll('[data-count]');
    
    function animateCounter(element) {
        const target = parseInt(element.getAttribute('data-count'));
        if (target === 0) {
            element.textContent = '0';
            return;
        }
        
        const duration = 2000;
        const step = Math.max(1, target / (duration / 16));
        let current = 0;
        
        const update = () => {
            current += step;
            if (current < target) {
                element.textContent = Math.floor(current);
                requestAnimationFrame(update);
            } else {
                element.textContent = target;
            }
        };
        
        update();
    }

    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounter(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });

        counters.forEach(counter => observer.observe(counter));
    } else {
        counters.forEach(counter => animateCounter(counter));
    }

    // ============================================================
    // HERO STATS COUNTER
    // ============================================================
    const heroStats = document.querySelectorAll('.hero-stats .stat .number');
    heroStats.forEach(stat => {
        if (stat.hasAttribute('data-count')) {
            const target = parseInt(stat.getAttribute('data-count'));
            if (target === 0) {
                stat.textContent = '0';
                return;
            }
            
            const duration = 2000;
            const step = Math.max(1, target / (duration / 16));
            let current = 0;
            
            const update = () => {
                current += step;
                if (current < target) {
                    stat.textContent = Math.floor(current);
                    requestAnimationFrame(update);
                } else {
                    stat.textContent = target;
                }
            };
            
            const heroObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        update();
                        heroObserver.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.5 });
            
            heroObserver.observe(stat);
        }
    });
});
</script>
@endpush
@endsection