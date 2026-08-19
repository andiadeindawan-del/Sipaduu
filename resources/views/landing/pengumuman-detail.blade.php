
@extends('layouts.landing')

@section('title', $pengumuman->judul ?? 'Detail Pengumuman')

@section('content')


<!-- ============================================================
     GAMBAR UTAMA - TAMPAK JELAS
============================================================ -->
@if(isset($pengumuman->gambar) && $pengumuman->gambar)
<section class="image-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="image-wrapper">
                    <img src="{{ asset('storage/' . $pengumuman->gambar) }}" 
                         alt="{{ $pengumuman->judul }}" 
                         class="main-image">
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<!-- ============================================================
     CONTENT SECTION
============================================================ -->
<section class="content-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card-content">
                    <!-- Isi Pengumuman -->
                    <div class="announcement-body">
                        {!! nl2br(e($pengumuman->konten)) !!}
                        
                        @if($pengumuman->deskripsi)
                        <div class="announcement-note">
                            <i class="bi bi-info-circle me-2"></i>
                            {{ $pengumuman->deskripsi }}
                        </div>
                        @endif
                    </div>

                    <!-- Lampiran -->
                    @if($pengumuman->file_path)
                    <div class="announcement-attachment">
                        <div class="attachment-header">
                            <h5 class="fw-bold mb-0"><i class="bi bi-paperclip me-2"></i>File Lampiran</h5>
                        </div>
                        <div class="attachment-body">
                            <div class="d-flex align-items-center gap-3">
                                <div class="attachment-icon">
                                    <i class="bi bi-file-earmark-pdf"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">{{ $pengumuman->file_name ?? 'Lampiran' }}</h6>
                                    <small class="text-muted">Klik tombol untuk melihat file</small>
                                </div>
                                <a href="{{ route('pengumuman.file', $pengumuman->id) }}" target="_blank" class="btn btn-primary btn-attachment">
                                    <i class="bi bi-eye me-1"></i> Lihat File
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Tombol Kembali -->
                    <div class="text-center mt-4">
                        <a href="{{ route('landing.pengumuman.index') }}" class="btn btn-outline-primary btn-back">
                            <i class="bi bi-arrow-left me-2"></i> Kembali ke Pengumuman
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
    /* ============================================================
       HERO SECTION
    ============================================================ */
    .hero-section {
        position: relative;
        overflow: hidden;
        padding: 4rem 0 3rem;
        min-height: 320px;
        display: flex;
        align-items: center;
    }
    
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
        filter: blur(4px) brightness(0.4) saturate(1.1);
        transform: scale(1.05);
    }
    .hero-bg-gradient {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, 
            rgba(15, 23, 36, 0.85) 0%, 
            rgba(26, 34, 54, 0.7) 40%, 
            rgba(42, 54, 84, 0.6) 70%, 
            rgba(15, 23, 36, 0.8) 100%
        );
        z-index: 1;
    }
    
    .hero-section .container {
        position: relative;
        z-index: 2;
    }
    
    .hero-title {
        font-weight: 800;
        font-size: 2.8rem;
        color: #fff;
        line-height: 1.2;
        margin-bottom: 1rem;
    }
    
    .hero-meta {
        color: rgba(255, 255, 255, 0.7);
        font-size: 0.9rem;
    }
    .hero-meta i {
        color: #4e9af1;
    }
    
    .badge-hero {
        background: rgba(78, 154, 241, 0.2) !important;
        color: #6ab0f5 !important;
        padding: 0.5rem 1.2rem;
        border-radius: 50px;
        font-weight: 500;
        border: 1px solid rgba(78, 154, 241, 0.2);
    }
    .badge-hero-light {
        background: rgba(255, 255, 255, 0.1) !important;
        color: rgba(255, 255, 255, 0.8) !important;
        padding: 0.5rem 1.2rem;
        border-radius: 50px;
        font-weight: 500;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .breadcrumb {
        --bs-breadcrumb-divider-color: rgba(255, 255, 255, 0.4);
    }
    .breadcrumb-item a {
        color: rgba(255, 255, 255, 0.6);
        text-decoration: none;
        transition: color 0.3s ease;
    }
    .breadcrumb-item a:hover {
        color: #fff;
    }

    /* ============================================================
       IMAGE SECTION - GAMBAR JELAS
    ============================================================ */
    .image-section {
        padding: 2rem 0 0;
        background: #f8fafc;
    }
    
    .image-wrapper {
        border-radius: 1.5rem;
        overflow: hidden;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
        background: #fff;
        padding: 0.5rem;
    }
    
    .main-image {
        width: 100%;
        max-height: 500px;
        object-fit: cover;
        border-radius: 1.2rem;
        display: block;
        transition: transform 0.3s ease;
    }
    .main-image:hover {
        transform: scale(1.01);
    }

    /* ============================================================
       CONTENT SECTION
    ============================================================ */
    .content-section {
        padding: 3rem 0 5rem;
        background: #f8fafc;
    }
    
    .card-content {
        background: #fff;
        border-radius: 1.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        overflow: hidden;
        padding: 2.5rem 3rem;
        margin-top: -1.5rem;
        position: relative;
        z-index: 5;
    }
    
    .announcement-body {
        font-size: 1.05rem;
        line-height: 1.8;
        color: #1a2236;
    }
    
    .announcement-body p {
        margin-bottom: 1rem;
    }
    
    .announcement-note {
        margin-top: 1.5rem;
        padding: 1rem 1.25rem;
        background: #f0f7ff;
        border-radius: 0.75rem;
        border-left: 4px solid #4e9af1;
        color: #1a2236;
        font-size: 0.95rem;
    }
    .announcement-note i {
        color: #4e9af1;
    }

    /* ============================================================
       ATTACHMENT
    ============================================================ */
    .announcement-attachment {
        margin-top: 2rem;
        border: 1px solid #e9ecef;
        border-radius: 1rem;
        overflow: hidden;
    }
    
    .attachment-header {
        padding: 0.75rem 1.25rem;
        background: #fafbfc;
        border-bottom: 1px solid #e9ecef;
    }
    .attachment-header h5 {
        color: #1a2236;
    }
    .attachment-header h5 i {
        color: #4e9af1;
    }
    
    .attachment-body {
        padding: 1rem 1.25rem;
    }
    
    .attachment-icon {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        background: #fef2f2;
        color: #dc3545;
        flex-shrink: 0;
    }
    
    .btn-attachment {
        background: linear-gradient(135deg, #4e9af1, #3a7bc8);
        border: none;
        padding: 0.5rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    .btn-attachment:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(78, 154, 241, 0.35);
    }

    /* ============================================================
       BUTTON BACK
    ============================================================ */
    .btn-back {
        padding: 0.6rem 2rem;
        border-radius: 50px;
        border-width: 2px;
        transition: all 0.3s ease;
    }
    .btn-back:hover {
        background: #4e9af1;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(78, 154, 241, 0.2);
    }

    /* ============================================================
       RESPONSIVE
    ============================================================ */
    @media (max-width: 992px) {
        .hero-title {
            font-size: 2.2rem;
        }
        .card-content {
            padding: 1.5rem 1.5rem;
        }
        .main-image {
            max-height: 350px;
        }
    }
    
    @media (max-width: 768px) {
        .hero-section {
            padding: 2.5rem 0 2rem;
            min-height: 240px;
        }
        .hero-title {
            font-size: 1.6rem;
        }
        .hero-meta {
            font-size: 0.8rem;
            flex-wrap: wrap;
        }
        .hero-meta span {
            display: inline-flex;
            align-items: center;
        }
        .card-content {
            padding: 1rem 1rem;
            border-radius: 1rem;
        }
        .announcement-body {
            font-size: 0.95rem;
            line-height: 1.7;
        }
        .attachment-body .d-flex {
            flex-wrap: wrap;
        }
        .btn-attachment {
            width: 100%;
            justify-content: center;
            margin-top: 0.5rem;
        }
        .badge-hero,
        .badge-hero-light {
            font-size: 0.7rem;
            padding: 0.3rem 0.8rem;
        }
        .announcement-note {
            font-size: 0.85rem;
            padding: 0.75rem 1rem;
        }
        .btn-back {
            width: 100%;
            justify-content: center;
        }
        .image-section {
            padding: 1rem 0 0;
        }
        .image-wrapper {
            padding: 0.25rem;
            border-radius: 1rem;
        }
        .main-image {
            max-height: 250px;
            border-radius: 0.8rem;
        }
    }
    
    @media (max-width: 480px) {
        .hero-title {
            font-size: 1.3rem;
        }
        .hero-section {
            padding: 1.5rem 0 1.5rem;
            min-height: 180px;
        }
        .card-content {
            padding: 0.75rem 0.75rem;
        }
        .announcement-body {
            font-size: 0.9rem;
        }
        .attachment-icon {
            width: 40px;
            height: 40px;
            font-size: 1.3rem;
        }
        .main-image {
            max-height: 180px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ============================================================
    // ANIMASI HERO FADE IN
    // ============================================================
    const hero = document.querySelector('.hero-section');
    if (hero) {
        hero.style.opacity = '0';
        hero.style.transform = 'translateY(20px)';
        setTimeout(() => {
            hero.style.transition = 'all 0.8s ease';
            hero.style.opacity = '1';
            hero.style.transform = 'translateY(0)';
        }, 100);
    }

    // ============================================================
    // ANIMASI GAMBAR
    // ============================================================
    const image = document.querySelector('.image-wrapper');
    if (image) {
        image.style.opacity = '0';
        image.style.transform = 'translateY(20px)';
        setTimeout(() => {
            image.style.transition = 'all 0.6s ease';
            image.style.opacity = '1';
            image.style.transform = 'translateY(0)';
        }, 200);
    }

    // ============================================================
    // ANIMASI CONTENT
    // ============================================================
    const content = document.querySelector('.card-content');
    if (content) {
        content.style.opacity = '0';
        content.style.transform = 'translateY(30px)';
        setTimeout(() => {
            content.style.transition = 'all 0.6s ease';
            content.style.opacity = '1';
            content.style.transform = 'translateY(0)';
        }, 350);
    }
});
</script>
@endpush
@endsection
