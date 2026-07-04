@extends('layouts.landing')

@section('title', 'Sistem Pelatihan SDM')

@section('content')
<!-- ============================================================
     HERO SECTION
============================================================ -->
<section class="hero">
    <div class="container position-relative">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <span class="badge bg-primary bg-opacity-10 text-primary mb-3 px-3 py-2 rounded-pill">
                    <i class="bi bi-rocket-takeoff me-1"></i> Platform Pelatihan Terbaik
                </span>
                <h1 class="mb-3">
                    Tingkatkan Kompetensi SDM <br>
                    <span style="color: #4e9af1;">Perusahaan Anda</span>
                </h1>
                <p class="mb-4">
                    Platform pelatihan online yang dirancang untuk mengembangkan 
                    kompetensi karyawan dengan materi terkini dan metode pembelajaran interaktif.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('landing.pelatihan.index') }}" class="btn btn-primary btn-lg">
                        <i class="bi bi-journal-bookmark me-2"></i> Lihat Pelatihan
                    </a>
                    <a href="#features" class="btn btn-outline-light btn-lg">
                        <i class="bi bi-arrow-down me-2"></i> Pelajari Lebih Lanjut
                    </a>
                </div>
                <div class="hero-stats">
                    <div class="stat">
                        <div class="number" data-count="5000">0</div>
                        <div class="label">Peserta Aktif</div>
                    </div>
                    <div class="stat">
                        <div class="number" data-count="150">0</div>
                        <div class="label">Pelatihan Tersedia</div>
                    </div>
                    <div class="stat">
                        <div class="number" data-count="98">0</div>
                        <div class="label">Sertifikat Diterbitkan</div>
                    </div>
                </div>
            </div>
           <div class="col-lg-6 d-none d-lg-block">
    <div class="text-center">
        <img src="{{ asset('assets/images/OIP (1).jpg') }}" 
             alt="Gambar" 
             class="img-fluid rounded-4 shadow-lg"
             style="width: 100%; height: 400px; object-fit: cover; object-position: center;">
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
            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill mb-2">
                <i class="bi bi-stars me-1"></i> Keunggulan
            </span>
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
     STATISTICS SECTION
============================================================ -->
<section class="section-pad" style="background: #f0f4f9;">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-6 col-md-3">
                <h3 class="text-primary fw-bold mb-0" style="font-size: 2.5rem;">
                    <span data-count="1000">0</span>+
                </h3>
                <p class="text-muted small">Peserta Terdaftar</p>
            </div>
            <div class="col-6 col-md-3">
                <h3 class="text-success fw-bold mb-0" style="font-size: 2.5rem;">
                    <span data-count="75">0</span>+
                </h3>
                <p class="text-muted small">Pelatihan Aktif</p>
            </div>
            <div class="col-6 col-md-3">
                <h3 class="text-warning fw-bold mb-0" style="font-size: 2.5rem;">
                    <span data-count="50">0</span>+
                </h3>
                <p class="text-muted small">Instruktur Profesional</p>
            </div>
            <div class="col-6 col-md-3">
                <h3 class="text-info fw-bold mb-0" style="font-size: 2.5rem;">
                    <span data-count="95">0</span>%
                </h3>
                <p class="text-muted small">Tingkat Kepuasan</p>
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
                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill mb-2">
                    <i class="bi bi-fire me-1"></i> Populer
                </span>
                <h2 class="section-title">Pelatihan Paling Populer</h2>
                <p class="section-subtitle text-start">Pelatihan yang paling banyak diikuti oleh peserta.</p>
            </div>
            <a href="{{ route('landing.pelatihan.index') }}" class="btn btn-outline-primary">
                Lihat Semua <i class="bi bi-chevron-right ms-1"></i>
            </a>
        </div>

        <div class="row g-4">
            @php
                $trainings = [
                    [
                        'title' => 'Manajemen SDM Modern',
                        'description' => 'Pelatihan manajemen sumber daya manusia dengan pendekatan modern.',
                        'duration' => '40',
                        'level' => 'Menengah',
                        'students' => '1.200',
                        'rating' => 4.8,
                        'image' => 'https://placehold.co/400x250/4e9af1/fff?text=SDM+Modern'
                    ],
                    [
                        'title' => 'Kepemimpinan Digital',
                        'description' => 'Kembangkan kemampuan kepemimpinan di era transformasi digital.',
                        'duration' => '35',
                        'level' => 'Menengah',
                        'students' => '980',
                        'rating' => 4.9,
                        'image' => 'https://placehold.co/400x250/28c76f/fff?text=Leadership'
                    ],
                    [
                        'title' => 'Data Analytics untuk HR',
                        'description' => 'Pelajari analisis data untuk pengambilan keputusan HR yang lebih baik.',
                        'duration' => '45',
                        'level' => 'Lanjutan',
                        'students' => '750',
                        'rating' => 4.7,
                        'image' => 'https://placehold.co/400x250/ff9f43/fff?text=Data+Analytics'
                    ]
                ];
            @endphp

            @foreach($trainings as $training)
            <div class="col-md-6 col-lg-4">
                <div class="card-feature p-0 overflow-hidden">
                    <img src="{{ $training['image'] }}" 
                         alt="{{ $training['title'] }}" 
                         class="img-fluid w-100" 
                         style="height: 200px; object-fit: cover;">
                    <div class="p-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="fw-bold mb-0">{{ $training['title'] }}</h5>
                            <span class="badge bg-warning text-dark">
                                <i class="bi bi-star-fill me-1"></i>{{ $training['rating'] }}
                            </span>
                        </div>
                        <p class="text-muted small">{{ $training['description'] }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex gap-3 text-muted small">
                                <span><i class="bi bi-clock me-1"></i>{{ $training['duration'] }} jam</span>
                                <span><i class="bi bi-people me-1"></i>{{ $training['students'] }}</span>
                            </div>
                            <a href="{{ route('landing.pelatihan.index') }}" class="btn btn-sm btn-primary">
                                Detail <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ============================================================
     TESTIMONIAL SECTION
============================================================ -->
<section class="section-pad" style="background: #f8fafc;">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill mb-2">
                <i class="bi bi-chat-quote me-1"></i> Testimoni
            </span>
            <h2 class="section-title">Apa Kata Mereka?</h2>
            <p class="section-subtitle">Testimoni dari peserta yang telah mengikuti pelatihan kami.</p>
        </div>

        <div class="row g-4">
            @php
                $testimonials = [
                    [
                        'name' => 'Andi Wijaya',
                        'role' => 'HR Manager, PT Maju Jaya',
                        'quote' => 'Pelatihan ini sangat membantu meningkatkan kompetensi tim HR kami. Materi disampaikan dengan jelas dan aplikatif.',
                        'rating' => 5,
                        'avatar' => 'AW'
                    ],
                    [
                        'name' => 'Siti Rahayu',
                        'role' => 'Training Specialist, PT Sejahtera',
                        'quote' => 'Platform yang sangat user-friendly. Saya bisa mengakses materi kapan saja dan di mana saja. Sangat direkomendasikan!',
                        'rating' => 5,
                        'avatar' => 'SR'
                    ],
                    [
                        'name' => 'Budi Santoso',
                        'role' => 'Direktur SDM, PT Nusantara',
                        'quote' => 'Investasi terbaik untuk pengembangan karyawan. Hasilnya terlihat dari peningkatan kinerja tim kami.',
                        'rating' => 4,
                        'avatar' => 'BS'
                    ]
                ];
            @endphp

            @foreach($testimonials as $testimonial)
            <div class="col-md-6 col-lg-4">
                <div class="testimonial-card">
                    <div class="stars">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $testimonial['rating'])
                                <i class="bi bi-star-fill"></i>
                            @else
                                <i class="bi bi-star"></i>
                            @endif
                        @endfor
                    </div>
                    <p class="quote">"{{ $testimonial['quote'] }}"</p>
                    <div class="author">
                        <div class="avatar">{{ $testimonial['avatar'] }}</div>
                        <div>
                            <p class="name">{{ $testimonial['name'] }}</p>
                            <p class="role">{{ $testimonial['role'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
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
                if (element.getAttribute('data-suffix')) {
                    element.textContent += element.getAttribute('data-suffix');
                }
            }
        };
        
        update();
    }

    // Use Intersection Observer for counter animation
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
        // Fallback for older browsers
        counters.forEach(counter => animateCounter(counter));
    }

    // ============================================================
    // HERO STATS COUNTER
    // ============================================================
    const heroStats = document.querySelectorAll('.hero-stats .stat .number');
    heroStats.forEach(stat => {
        if (stat.hasAttribute('data-count')) {
            const target = parseInt(stat.getAttribute('data-count'));
            const duration = 2000;
            const step = target / (duration / 16);
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
            
            // Start animation when hero is visible
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