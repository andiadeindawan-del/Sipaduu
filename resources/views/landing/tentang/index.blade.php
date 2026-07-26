@extends('layouts.landing')

@section('title', 'Tentang Kami')

@section('content')

<!-- ============================================================
     ABOUT SECTION - VISI & MISI
============================================================ -->
<section class="section-pad" style="padding: 4rem 0;">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-6">
                <h2 class="display-5 fw-bold mb-3">Membangun SDM Unggul untuk Indonesia</h2>
                <p class="text-muted mb-4">
                    Kami percaya bahwa sumber daya manusia yang berkualitas adalah kunci 
                    utama kemajuan bangsa. Melalui platform ini, kami berkomitmen untuk 
                    menyediakan akses pelatihan berkualitas bagi semua kalangan.
                </p>
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="d-flex gap-3">
                            <div class="flex-shrink-0">
                                <div class="icon-box" style="width: 48px; height: 48px; border-radius: 12px; background: #eaf1fd; color: #4e9af1; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                                    <i class="bi bi-eye"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="fw-bold">Visi</h6>
                                <p class="small text-muted">Menjadi platform pelatihan terdepan di Indonesia yang mencetak SDM unggul dan berdaya saing global.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex gap-3">
                            <div class="flex-shrink-0">
                                <div class="icon-box" style="width: 48px; height: 48px; border-radius: 12px; background: #dff6e8; color: #28c76f; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                                    <i class="bi bi-bullseye"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="fw-bold">Misi</h6>
                                <p class="small text-muted">Menyediakan pelatihan berkualitas, aksesibel, dan relevan dengan kebutuhan industri masa kini.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="position-relative">
                    <img src="{{ asset('assets/images/OIP (1).jpg') }}" 
                         alt="Tentang Kami" 
                         class="img-fluid rounded-4 shadow-lg" style="width: 100%;">
                    <div class="position-absolute bottom-0 start-0 translate-middle-y ms-4 mb-4">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     STATISTICS SECTION
============================================================ -->
<section class="section-pad" style="padding: 4rem 0; background: #f8fafc;">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-2">Pencapaian Kami</h2>
            <p class="text-muted" style="max-width: 600px; margin: 0 auto;">
                Angka-angka yang menunjukkan komitmen kami dalam pengembangan SDM.
            </p>
        </div>

        <div class="row g-4 text-center">
            <div class="col-6 col-md-3">
                <div class="bg-white p-4 rounded-4 shadow-sm">
                    <h3 class="text-primary fw-bold mb-0" style="font-size: 2.5rem;">
                        <span data-count="{{ $totalTrainings ?? 0 }}">0</span>+
                    </h3>
                    <p class="text-muted small mb-0">Pelatihan Tersedia</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="bg-white p-4 rounded-4 shadow-sm">
                    <h3 class="text-success fw-bold mb-0" style="font-size: 2.5rem;">
                        <span data-count="{{ $totalParticipants ?? 0 }}">0</span>+
                    </h3>
                    <p class="text-muted small mb-0">Peserta Terdaftar</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="bg-white p-4 rounded-4 shadow-sm">
                    <h3 class="text-warning fw-bold mb-0" style="font-size: 2.5rem;">
                        <span data-count="{{ $totalCertificates ?? 0 }}">0</span>+
                    </h3>
                    <p class="text-muted small mb-0">Sertifikat Diterbitkan</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="bg-white p-4 rounded-4 shadow-sm">
                    <h3 class="text-info fw-bold mb-0" style="font-size: 2.5rem;">
                        <span data-count="{{ $totalInstructors ?? 0 }}">0</span>+
                    </h3>
                    <p class="text-muted small mb-0">Instruktur Profesional</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     VALUES SECTION
============================================================ -->
<section class="section-pad" style="padding: 4rem 0;">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-2">Nilai-Nilai Kami</h2>
            <p class="text-muted" style="max-width: 600px; margin: 0 auto;">
                Prinsip-prinsip yang menjadi landasan kami dalam memberikan pelayanan terbaik.
            </p>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="card-feature text-center p-4 bg-white rounded-4 shadow-sm">
                    <div class="icon-box mx-auto mb-3" style="width: 60px; height: 60px; border-radius: 50%; background: #eaf1fd; color: #4e9af1; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                        <i class="bi bi-star"></i>
                    </div>
                    <h5 class="fw-bold">Kualitas</h5>
                    <p class="small text-muted">Kami selalu mengutamakan kualitas materi dan metode pembelajaran.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card-feature text-center p-4 bg-white rounded-4 shadow-sm">
                    <div class="icon-box mx-auto mb-3" style="width: 60px; height: 60px; border-radius: 50%; background: #dff6e8; color: #28c76f; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                        <i class="bi bi-people"></i>
                    </div>
                    <h5 class="fw-bold">Inklusivitas</h5>
                    <p class="small text-muted">Pelatihan dapat diakses oleh semua kalangan tanpa batasan.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card-feature text-center p-4 bg-white rounded-4 shadow-sm">
                    <div class="icon-box mx-auto mb-3" style="width: 60px; height: 60px; border-radius: 50%; background: #fef3e2; color: #ff9f43; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                        <i class="bi bi-lightbulb"></i>
                    </div>
                    <h5 class="fw-bold">Inovasi</h5>
                    <p class="small text-muted">Terus berinovasi dalam metode dan materi pelatihan.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card-feature text-center p-4 bg-white rounded-4 shadow-sm">
                    <div class="icon-box mx-auto mb-3" style="width: 60px; height: 60px; border-radius: 50%; background: #fce4e4; color: #ea5455; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                        <i class="bi bi-hand-thumbs-up"></i>
                    </div>
                    <h5 class="fw-bold">Integritas</h5>
                    <p class="small text-muted">Menjaga kepercayaan dengan memberikan pelayanan terbaik.</p>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- ============================================================
     CTA SECTION
============================================================ -->
<section class="section-pad" style="padding: 0 0 4rem 0;">
    <div class="container">
        <div class="cta-section p-5 text-center" style="background: linear-gradient(135deg, #1a2236, #2a3654); color: #fff; border-radius: 1.5rem;">
            <h2 class="display-5 fw-bold mb-3">Siap Bergabung dengan Kami?</h2>
            <p class="mx-auto mb-4" style="max-width: 600px; opacity: 0.8;">
                Mulai perjalanan pengembangan kompetensi Anda bersama platform pelatihan terbaik.
            </p>
            <div class="d-flex flex-wrap gap-3 justify-content-center">
                <a href="{{ route('landing.pelatihan.index') }}" class="btn btn-primary btn-lg px-5">
                    <i class="bi bi-journal-bookmark me-2"></i> Lihat Pelatihan
                </a>
                <a href="{{ route('landing.kontak.index') }}" class="btn btn-outline-light btn-lg px-5">
                    <i class="bi bi-envelope me-2"></i> Hubungi Kami
                </a>
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
    /* ============================================================
       HERO SECTION
    ============================================================ */
    .hero {
        background: linear-gradient(135deg, #0f1724 0%, #1a2236 50%, #0f1724 100%);
        color: #fff;
        position: relative;
    }
    
    .hero .badge {
        font-weight: 600;
        letter-spacing: 0.02em;
        background-color: rgba(78, 154, 241, 0.15) !important;
        color: #4e9af1 !important;
        padding: 0.5rem 1.2rem;
        border-radius: 50px;
        border: 1px solid rgba(78, 154, 241, 0.2);
    }
    
    .hero .display-3 {
        font-weight: 800;
        letter-spacing: -0.02em;
    }
    
    .hero .btn {
        border-radius: 50px;
        font-weight: 600;
        transition: all 0.3s ease;
        padding: 0.75rem 2rem;
    }
    
    .hero .btn-primary {
        background: linear-gradient(135deg, #4e9af1, #3b7dd8);
        border: none;
        box-shadow: 0 4px 15px rgba(78, 154, 241, 0.4);
    }
    
    .hero .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(78, 154, 241, 0.5);
    }
    
    .hero .btn-outline-light {
        border: 2px solid rgba(255, 255, 255, 0.2);
    }
    
    .hero .btn-outline-light:hover {
        background: rgba(255, 255, 255, 0.1);
        border-color: rgba(255, 255, 255, 0.4);
        transform: translateY(-2px);
    }

    /* ============================================================
       SECTION TITLE
    ============================================================ */
    .section-title {
        font-weight: 700;
        color: #1a2236;
    }
    
    .section-subtitle {
        color: #6c757d;
        max-width: 600px;
        margin: 0 auto;
    }

    /* ============================================================
       CARD FEATURE
    ============================================================ */
    .card-feature {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: 1px solid #f0f0f0;
    }
    
    .card-feature:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.08);
    }

    /* ============================================================
       AVATAR CIRCLE
    ============================================================ */
    .avatar-circle {
        transition: transform 0.3s ease;
    }
    
    .avatar-circle:hover {
        transform: scale(1.05);
    }

    /* ============================================================
       ICON BOX
    ============================================================ */
    .icon-box {
        transition: transform 0.3s ease;
    }
    
    .icon-box:hover {
        transform: scale(1.05);
    }

    /* ============================================================
       CTA SECTION
    ============================================================ */
    .cta-section {
        background: linear-gradient(135deg, #1a2236, #2a3654);
        color: #fff;
        border-radius: 1.5rem;
    }
    
    .cta-section .btn-primary {
        background: linear-gradient(135deg, #4e9af1, #3b7dd8);
        border: none;
        box-shadow: 0 4px 15px rgba(78, 154, 241, 0.4);
    }
    
    .cta-section .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(78, 154, 241, 0.5);
    }
    
    .cta-section .btn-outline-light {
        border: 2px solid rgba(255, 255, 255, 0.2);
    }
    
    .cta-section .btn-outline-light:hover {
        background: rgba(255, 255, 255, 0.1);
        border-color: rgba(255, 255, 255, 0.4);
        transform: translateY(-2px);
    }

    /* ============================================================
       RESPONSIVE
    ============================================================ */
    @media (max-width: 768px) {
        .hero {
            min-height: 60vh !important;
            padding: 2rem 0 !important;
        }
        
        .hero .display-3 {
            font-size: 2.2rem;
        }
        
        .hero .btn-lg {
            padding: 0.5rem 1.5rem;
            font-size: 0.95rem;
        }
        
        .hero .fs-5 {
            font-size: 1rem !important;
        }
        
        .section-pad {
            padding: 2rem 0 !important;
        }
        
        .display-5 {
            font-size: 1.8rem !important;
        }
        
        .cta-section {
            padding: 2rem 1.5rem !important;
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
        const duration = 2000;
        const step = target / (duration / 16);
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
});
</script>
@endpush
@endsection