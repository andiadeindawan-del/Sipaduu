@extends('layouts.landing')

@section('title', 'Kontak Kami')

@section('content')
<!-- ============================================================
     HERO KONTAK
============================================================ -->
<section class="hero d-flex align-items-center justify-content-center" style="min-height: 90vh; padding: 0; background: linear-gradient(135deg, #0f1724 0%, #1a2236 50%, #0f1724 100%);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill mb-3">
                    <i class="bi bi-envelope me-1"></i> Kontak
                </span>
                <h1 class="display-3 fw-bold mb-3">Hubungi Kami</h1>
                <p class="text-white-50 mb-4 fs-5 mx-auto" style="max-width: 600px;">
                    Kami siap membantu Anda. Silakan hubungi kami melalui formulir di bawah ini.
                </p>
                <div class="d-flex flex-wrap gap-3 justify-content-center">
                    <a href="#contact-form" class="btn btn-primary btn-lg px-4">
                        <i class="bi bi-chat-dots me-2"></i> Kirim Pesan
                    </a>
                    <a href="{{ route('landing.faq.index') }}" class="btn btn-outline-light btn-lg px-4">
                        <i class="bi bi-question-circle me-2"></i> FAQ
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     CONTACT SECTION
============================================================ -->
<section class="section-pad">
    <div class="container">
        <div class="row g-4">
            <!-- Contact Form -->
            <div class="col-lg-7">
                <div class="panel">
                    <div class="panel-header">
                        <h5 class="section-title">
                            <i class="bi bi-chat-dots me-2"></i> Kirim Pesan
                        </h5>
                    </div>
                    <div class="p-4">
                        @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        @endif

                        @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        @endif

                        <form action="{{ route('landing.kontak.send') }}" method="POST">
                            @csrf

                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label for="name" class="form-label fw-semibold">
                                        Nama Lengkap <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                               id="name" name="name" value="{{ old('name') }}" 
                                               placeholder="Masukkan nama lengkap" required>
                                    </div>
                                    @error('name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="email" class="form-label fw-semibold">
                                        Email <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                               id="email" name="email" value="{{ old('email') }}" 
                                               placeholder="Masukkan email" required>
                                    </div>
                                    @error('email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="subject" class="form-label fw-semibold">
                                        Subjek <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-tag"></i></span>
                                        <input type="text" class="form-control @error('subject') is-invalid @enderror" 
                                               id="subject" name="subject" value="{{ old('subject') }}" 
                                               placeholder="Masukkan subjek pesan" required>
                                    </div>
                                    @error('subject')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="message" class="form-label fw-semibold">
                                        Pesan <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-file-text"></i></span>
                                        <textarea class="form-control @error('message') is-invalid @enderror" 
                                                  id="message" name="message" rows="5" 
                                                  placeholder="Tulis pesan Anda di sini..." required>{{ old('message') }}</textarea>
                                    </div>
                                    @error('message')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="agree" required>
                                        <label class="form-check-label small" for="agree">
                                            Saya setuju dengan <a href="#" class="text-decoration-none">kebijakan privasi</a> dan 
                                            <a href="#" class="text-decoration-none">syarat & ketentuan</a> yang berlaku.
                                        </label>
                                    </div>
                                </div>

                                <div class="col-12 mt-3">
                                    <button type="submit" class="btn btn-primary btn-lg w-100" id="submitBtn">
                                        <i class="bi bi-send me-2"></i> Kirim Pesan
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="col-lg-5">
                <div class="panel">
                    <div class="panel-header">
                        <h5 class="section-title">
                            <i class="bi bi-info-circle me-2"></i> Informasi Kontak
                        </h5>
                    </div>
                    <div class="p-4">
                        <div class="d-flex gap-3 mb-4">
                            <div class="flex-shrink-0">
                                <div class="icon icon-primary" style="width: 48px; height: 48px; border-radius: 12px; background: #eaf1fd; color: #4e9af1; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                                    <i class="bi bi-geo-alt"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="fw-bold">Alamat</h6>
                                <p class="text-muted small mb-0">
                                    <strong>Gedung Gabungan Dinas (Gadis) Lantai 3</strong><br>
                                    Kompleks Perkantoran Gubernur Provinsi Sulawesi Barat<br>
                                    Jalan Abdul Malik Pattana Endeng<br>
                                    Mamuju, Sulawesi Barat 91511<br>
                                    Indonesia
                                </p>
                            </div>
                        </div>

                        <div class="d-flex gap-3 mb-4">
                            <div class="flex-shrink-0">
                                <div class="icon icon-success" style="width: 48px; height: 48px; border-radius: 12px; background: #dff6e8; color: #28c76f; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                                    <i class="bi bi-envelope"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="fw-bold">Email</h6>
                                <p class="text-muted small mb-0">
                                    <a href="mailto:koperindag@sulbarprov.go.id" class="text-decoration-none">
                                        koperindag@sulbarprov.go.id
                                    </a>
                                </p>
                                <p class="text-muted small mb-0">
                                    <a href="mailto:info@pelatihan-sdm.com" class="text-decoration-none">
                                        info@pelatihan-sdm.com
                                    </a>
                                </p>
                            </div>
                        </div>

                        <div class="d-flex gap-3 mb-4">
                            <div class="flex-shrink-0">
                                <div class="icon icon-warning" style="width: 48px; height: 48px; border-radius: 12px; background: #fef3e2; color: #ff9f43; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                                    <i class="bi bi-telephone"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="fw-bold">Telepon</h6>
                                <p class="text-muted small mb-0">
                                    <a href="tel:+6285185656443" class="text-decoration-none">
                                        +62 851-8565-6443
                                    </a>
                                </p>
                                <p class="text-muted small mb-0">
                                    <a href="tel:+62422612345" class="text-decoration-none">
                                        (0426) 612345
                                    </a>
                                </p>
                            </div>
                        </div>

                        <div class="d-flex gap-3">
                            <div class="flex-shrink-0">
                                <div class="icon icon-info" style="width: 48px; height: 48px; border-radius: 12px; background: #e0f4fe; color: #17a2b8; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                                    <i class="bi bi-clock"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="fw-bold">Jam Operasional</h6>
                                <p class="text-muted small mb-0">
                                    Senin - Jumat: 08:00 - 17:00 WITA
                                </p>
                                <p class="text-muted small mb-0">
                                    Sabtu - Minggu: Tutup
                                </p>
                            </div>
                        </div>

                        <hr class="my-4">

                        <h6 class="fw-bold mb-3">Ikuti Kami</h6>
                        <div class="d-flex gap-2">
                            <a href="#" class="btn btn-outline-primary btn-sm" style="width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-facebook"></i>
                            </a>
                            <a href="#" class="btn btn-outline-primary btn-sm" style="width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-twitter-x"></i>
                            </a>
                            <a href="#" class="btn btn-outline-primary btn-sm" style="width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-instagram"></i>
                            </a>
                            <a href="#" class="btn btn-outline-primary btn-sm" style="width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-youtube"></i>
                            </a>
                            <a href="#" class="btn btn-outline-primary btn-sm" style="width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-linkedin"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     MAP SECTION - MAMUJU SULAWESI BARAT
============================================================ -->
<section class="section-pad" style="padding-top: 0;">
    <div class="container">
        <div class="panel">
            <div class="panel-header">
                <h5 class="section-title">
                    <i class="bi bi-map me-2"></i> Lokasi Kami - Kantor Dinas Koperindag Sulawesi Barat
                </h5>
                <a href="https://maps.app.goo.gl/1xjH8fLzKQnKqM2aA" target="_blank" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-box-arrow-up-right me-1"></i> Buka di Google Maps
                </a>
            </div>
            <div class="p-0">
                <div class="ratio ratio-21x9">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.123456789012!2d118.8899405!3d-2.6833333!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2d9b6e5e5e5e5e5e%3A0x5e5e5e5e5e5e5e5e!2sMamuju%2C%20Sulawesi%20Barat!5e0!3m2!1sid!2sid!4v1700000000000!5m2!1sid!2sid" 
                        width="100%" 
                        height="100%" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade"
                        title="Lokasi Kantor Dinas Koperindag Sulawesi Barat di Jalan Abdul Malik Pattana Endeng, Mamuju">
                    </iframe>
                </div>
                <div class="p-3 bg-light">
                    <div class="row g-2">
                        <div class="col-12 col-md-7">
                            <div class="d-flex align-items-start gap-2">
                                <i class="bi bi-geo-alt text-primary mt-1"></i>
                                <div>
                                    <span class="small">
                                        <strong>Gedung Gabungan Dinas (Gadis) Lantai 3</strong>
                                        <span class="text-muted d-block">
                                            Kompleks Perkantoran Gubernur Provinsi Sulawesi Barat<br>
                                            Jalan Abdul Malik Pattana Endeng, Mamuju
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-5">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-telephone text-success"></i>
                                <span class="small">
                                    <strong>Kontak</strong>
                                    <span class="text-muted d-block">0851-8565-6443</span>
                                </span>
                            </div>
                            <div class="d-flex align-items-center gap-2 mt-1">
                                <i class="bi bi-envelope text-primary"></i>
                                <span class="small">
                                    <strong>Email</strong>
                                    <span class="text-muted d-block">koperindag@sulbarprov.go.id</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
    .panel {
        background: #fff;
        border-radius: 1rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        overflow: hidden;
    }
    .panel-header {
        padding: 1rem 1.25rem;
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
    .section-title i {
        color: var(--primary);
    }
    
    .hero {
        background: linear-gradient(135deg, #1a2236 0%, #2a3654 50%, #1a2236 100%);
        color: #fff;
    }
    
    .icon {
        transition: transform 0.3s ease;
    }
    .icon:hover {
        transform: scale(1.1);
    }
    
    .btn-outline-primary {
        border-color: var(--primary);
        color: var(--primary);
    }
    .btn-outline-primary:hover {
        background: var(--primary);
        color: #fff;
    }
    
    .ratio {
        border-radius: 0 0 1rem 1rem;
        overflow: hidden;
    }
    
    .bg-light {
        background-color: #f8f9fa !important;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ============================================================
    // SUBMIT BUTTON LOADING STATE
    // ============================================================
    const form = document.querySelector('form');
    const submitBtn = document.getElementById('submitBtn');

    if (form) {
        form.addEventListener('submit', function(e) {
            // Validasi checkbox agree
            const agree = document.getElementById('agree');
            if (!agree.checked) {
                e.preventDefault();
                alert('⚠️ Silakan setuju dengan kebijakan privasi dan syarat & ketentuan.');
                agree.focus();
                return false;
            }

            // Loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                Mengirim...
            `;
        });
    }

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
    // AUTO RESIZE TEXTAREA
    // ============================================================
    const textarea = document.getElementById('message');
    if (textarea) {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    }
});
</script>
@endpush
@endsection