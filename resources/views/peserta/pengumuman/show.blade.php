```blade
@extends('layouts.peserta')

@section('title', $pengumuman->judul ?? 'Detail Pengumuman')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-megaphone"></i></span>
        <div>
            <p class="eyebrow">Detail Pengumuman</p>
            <h1 class="h3 mb-0">{{ Str::limit($pengumuman->judul, 40) }}</h1>
            <p class="text-muted mb-0">Informasi lengkap pengumuman</p>
        </div>
    </div>
    
</div>
@endsection

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <!-- Alert Messages -->
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle-fill me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <div class="panel">
                <!-- Gambar -->
                @if(isset($pengumuman->gambar) && $pengumuman->gambar)
                <div class="announcement-image-wrapper">
                    <img src="{{ asset('storage/' . $pengumuman->gambar) }}" 
                         alt="{{ $pengumuman->judul }}" 
                         class="announcement-image">
                </div>
                @endif
                
                <div class="p-4 p-lg-5">
                    <!-- Header -->
                    <div class="mb-4">
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <span class="badge badge-primary-custom">
                                <i class="bi bi-tag me-1"></i> {{ ucfirst($pengumuman->jenis_pengumuman ?? 'Umum') }}
                            </span>
                            @if(isset($pengumuman->kategori->nama))
                            <span class="badge badge-info-custom">
                                <i class="bi bi-tag me-1"></i> {{ $pengumuman->kategori->nama }}
                            </span>
                            @endif
                            @if(isset($pengumuman->training))
                            <span class="badge badge-training-custom">
                                <i class="bi bi-journal-bookmark me-1"></i> {{ Str::limit($pengumuman->training->judul, 30) }}
                            </span>
                            @endif
                            @if($pengumuman->is_pinned)
                            <span class="badge badge-pinned-custom">
                                <i class="bi bi-pin-fill me-1"></i> Pinned
                            </span>
                            @endif
                        </div>
                        
                        <h1 class="announcement-title">{{ $pengumuman->judul ?? 'Detail Pengumuman' }}</h1>
                        
                        <div class="announcement-meta">
                            <span><i class="bi bi-calendar me-1"></i> {{ $pengumuman->created_at ? $pengumuman->created_at->format('d M Y H:i') : '-' }}</span>
                            <span><i class="bi bi-person me-1"></i> {{ $pengumuman->creator->nama ?? $pengumuman->creator->name ?? 'Admin' }}</span>
                            <span><i class="bi bi-eye me-1"></i> {{ $pengumuman->views ?? 0 }} kali dilihat</span>
                            @if($pengumuman->tanggal_selesai)
                            <span><i class="bi bi-calendar-check me-1"></i> Berlaku s/d: {{ $pengumuman->tanggal_selesai->format('d M Y') }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Isi Pengumuman -->
                    <div class="announcement-content">
                        {!! nl2br(e($pengumuman->konten)) !!}
                    </div>

                    @if($pengumuman->deskripsi)
                    <div class="announcement-note">
                        <i class="bi bi-info-circle me-2"></i>
                        {{ $pengumuman->deskripsi }}
                    </div>
                    @endif

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
                                 <a href="{{ route('peserta.pengumuman.index') }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="bi bi-arrow-left me-1"></i> Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* ============================================================
       PAGE HEADING
    ============================================================ */
    .page-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        padding: 1.25rem 1.5rem;
        background: #fff;
        border-bottom: 1px solid #f0f0f0;
    }
    .page-heading-copy {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .page-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #eaf1fd, #d4e4f7);
        color: #4e9af1;
        font-size: 1.3rem;
        flex-shrink: 0;
    }
    .eyebrow {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #8a93a3;
        font-weight: 600;
    }
    .heading-actions {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    /* ============================================================
       PANEL
    ============================================================ */
    .panel {
        background: #fff;
        border-radius: .75rem;
        box-shadow: 0 1px 4px rgba(0,0,0,.06);
        overflow: hidden;
    }
    .panel:hover {
        box-shadow: 0 8px 30px rgba(0,0,0,0.06);
    }

    /* ============================================================
       ANNOUNCEMENT IMAGE
    ============================================================ */
    .announcement-image-wrapper {
        width: 100%;
        max-height: 400px;
        overflow: hidden;
        background: #f8fafc;
    }
    .announcement-image {
        width: 100%;
        height: 100%;
        max-height: 400px;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    .announcement-image:hover {
        transform: scale(1.01);
    }

    /* ============================================================
       ANNOUNCEMENT TITLE
    ============================================================ */
    .announcement-title {
        font-weight: 700;
        font-size: 1.8rem;
        color: #1a2236;
        margin-bottom: 1rem;
        line-height: 1.3;
    }

    /* ============================================================
       ANNOUNCEMENT META
    ============================================================ */
    .announcement-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        padding: 0.75rem 0;
        border-top: 1px solid #f0f0f0;
        border-bottom: 1px solid #f0f0f0;
        color: #6c757d;
        font-size: 0.85rem;
    }
    .announcement-meta span {
        display: inline-flex;
        align-items: center;
    }
    .announcement-meta i {
        color: #4e9af1;
        font-size: 1rem;
    }

    /* ============================================================
       ANNOUNCEMENT CONTENT
    ============================================================ */
    .announcement-content {
        font-size: 1.05rem;
        line-height: 1.8;
        color: #1a2236;
        padding: 1.5rem 0;
    }
    .announcement-content p {
        margin-bottom: 1rem;
    }

    /* ============================================================
       ANNOUNCEMENT NOTE
    ============================================================ */
    .announcement-note {
        margin: 1rem 0 1.5rem;
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
       BADGE CUSTOM
    ============================================================ */
    .badge-primary-custom {
        background: #d4edda !important;
        color: #155724 !important;
        padding: 0.4rem 1rem;
        font-weight: 500;
        border-radius: 6px;
    }
    .badge-info-custom {
        background: #cce5ff !important;
        color: #004085 !important;
        padding: 0.4rem 1rem;
        font-weight: 500;
        border-radius: 6px;
    }
    .badge-training-custom {
        background: #e8d5b7 !important;
        color: #5d4037 !important;
        padding: 0.4rem 1rem;
        font-weight: 500;
        border-radius: 6px;
    }
    .badge-pinned-custom {
        background: #fff3cd !important;
        color: #856404 !important;
        padding: 0.4rem 1rem;
        font-weight: 500;
        border-radius: 6px;
    }

    /* ============================================================
       ATTACHMENT
    ============================================================ */
    .announcement-attachment {
        margin-top: 1.5rem;
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
        color: #fff;
        transition: all 0.3s ease;
    }
    .btn-attachment:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(78, 154, 241, 0.35);
        color: #fff;
    }

    /* ============================================================
       ALERT
    ============================================================ */
    .alert {
        border-radius: 0.75rem;
        border: none;
        padding: 0.75rem 1rem;
    }
    .alert-success {
        background: #ecfdf5;
        color: #065f46;
    }
    .alert-danger {
        background: #fef2f2;
        color: #991b1b;
    }

    /* ============================================================
       RESPONSIVE
    ============================================================ */
    @media (max-width: 768px) {
        .page-heading {
            flex-direction: column;
            align-items: flex-start;
        }
        .page-heading-copy {
            width: 100%;
        }
        .heading-actions {
            width: 100%;
        }
        .heading-actions .btn {
            width: 100%;
        }
        .p-4.p-lg-5 {
            padding: 1.25rem !important;
        }
        .announcement-title {
            font-size: 1.4rem;
        }
        .announcement-meta {
            font-size: 0.75rem;
            gap: 0.75rem;
        }
        .announcement-content {
            font-size: 0.95rem;
            line-height: 1.7;
        }
        .announcement-image-wrapper {
            max-height: 250px;
        }
        .announcement-image {
            max-height: 250px;
        }
        .attachment-body .d-flex {
            flex-wrap: wrap;
        }
        .btn-attachment {
            width: 100%;
            justify-content: center;
            margin-top: 0.5rem;
        }
        .badge-primary-custom,
        .badge-info-custom,
        .badge-training-custom,
        .badge-pinned-custom {
            font-size: 0.7rem;
            padding: 0.25rem 0.7rem;
        }
        .announcement-note {
            font-size: 0.85rem;
            padding: 0.75rem 1rem;
        }
        .attachment-icon {
            width: 40px;
            height: 40px;
            font-size: 1.3rem;
        }
    }

    @media (max-width: 480px) {
        .announcement-title {
            font-size: 1.2rem;
        }
        .announcement-content {
            font-size: 0.9rem;
        }
        .announcement-image-wrapper {
            max-height: 180px;
        }
        .announcement-image {
            max-height: 180px;
        }
        .p-4.p-lg-5 {
            padding: 1rem !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto close alerts after 5 seconds
    setTimeout(function() {
        document.querySelectorAll('.alert').forEach(function(alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});
</script>
@endpush
@endsection
```