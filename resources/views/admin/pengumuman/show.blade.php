@extends('layouts.admin')

@section('title', 'Detail Pengumuman')

@section('header')
<div class="page-heading d-flex justify-content-between align-items-center">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-megaphone"></i></span>
        <div>
            <p class="eyebrow">Manajemen</p>
            <h1 class="h3 mb-0">Detail Pengumuman</h1>
            <p class="text-muted mb-0">Informasi lengkap pengumuman <strong>{{ $pengumuman->judul }}</strong></p>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="row">
        <div class="col-12 col-lg-10 mx-auto">
            <!-- Alert Messages -->
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

            <!-- Main Card -->
            <div class="panel">
                <div class="panel-header">
                    <div>
                        <h5 class="section-title"><i class="bi bi-info-circle"></i> Informasi Pengumuman</h5>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <span class="badge 
                            @if($pengumuman->status == 'published') badge-published
                            @elseif($pengumuman->status == 'draft') badge-draft
                            @else badge-secondary
                            @endif
                        ">
                            <i class="bi bi-circle-fill me-1" style="font-size: 6px;"></i>
                            {{ ucfirst($pengumuman->status ?? 'Draft') }}
                        </span>
                        @if($pengumuman->is_pinned)
                        <span class="badge badge-pinned">
                            <i class="bi bi-pin-fill me-1"></i> Pinned
                        </span>
                        @endif
                    </div>
                </div>

                <div class="p-4">
                    <div class="row g-4">
                        <!-- Judul -->
                        <div class="col-12">
                            <div class="info-item p-3 bg-light rounded-3">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="icon-circle bg-primary text-white">
                                        <i class="bi bi-megaphone"></i>
                                    </div>
                                    <div>
                                        <label class="text-muted small fw-semibold text-uppercase d-block">Judul</label>
                                        <h3 class="fw-bold mb-0">{{ $pengumuman->judul }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Meta Info -->
                        <div class="col-12">
                            <div class="info-item p-3 bg-light rounded-3">
                                <div class="d-flex flex-wrap gap-4 text-muted small">
                                    <span>
                                        <i class="bi bi-clock me-1"></i>
                                        Dibuat: {{ $pengumuman->created_at ? $pengumuman->created_at->format('d/m/Y H:i') : '-' }}
                                    </span>
                                    <span>
                                        <i class="bi bi-pencil me-1"></i>
                                        Diperbarui: {{ $pengumuman->updated_at ? $pengumuman->updated_at->format('d/m/Y H:i') : '-' }}
                                    </span>
                                    <span>
                                        <i class="bi bi-person me-1"></i>
                                        Penulis: {{ $pengumuman->creator->nama ?? $pengumuman->creator->name ?? 'Admin' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Info Grid -->
                        <div class="col-12">
                            <div class="row g-3">
                                <!-- Training -->
                                <div class="col-12 col-md-4">
                                    <div class="info-item p-3 bg-light rounded-3 h-100">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="icon-circle bg-success text-white">
                                                <i class="bi bi-journal-bookmark"></i>
                                            </div>
                                            <div>
                                                <label class="text-muted small fw-semibold text-uppercase d-block">Training</label>
                                                <p class="fw-semibold mb-0">
                                                    {{ $pengumuman->training->judul ?? 'Umum' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Kategori -->
                                <div class="col-12 col-md-4">
                                    <div class="info-item p-3 bg-light rounded-3 h-100">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="icon-circle bg-info text-white">
                                                <i class="bi bi-tag"></i>
                                            </div>
                                            <div>
                                                <label class="text-muted small fw-semibold text-uppercase d-block">Kategori</label>
                                                <p class="fw-semibold mb-0">
                                                    {{ $pengumuman->kategori->nama ?? '-' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Target Audience -->
                                <div class="col-12 col-md-4">
                                    <div class="info-item p-3 bg-light rounded-3 h-100">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="icon-circle bg-warning text-white">
                                                <i class="bi bi-people"></i>
                                            </div>
                                            <div>
                                                <label class="text-muted small fw-semibold text-uppercase d-block">Target Audience</label>
                                                <p class="fw-semibold mb-0">
                                                    @php
                                                        $audienceMap = [
                                                            'all' => '🌍 Semua',
                                                            'peserta' => '👤 Peserta',
                                                            'trainer' => '👨‍🏫 Trainer',
                                                            'admin' => '🛡️ Admin',
                                                        ];
                                                    @endphp
                                                    {{ $audienceMap[$pengumuman->target_audience] ?? ucfirst($pengumuman->target_audience ?? 'All') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tanggal -->
                        <div class="col-12">
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <div class="info-item p-3 bg-light rounded-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="icon-circle bg-primary text-white">
                                                <i class="bi bi-calendar3"></i>
                                            </div>
                                            <div>
                                                <label class="text-muted small fw-semibold text-uppercase d-block">Tanggal Mulai</label>
                                                <p class="fw-semibold mb-0">
                                                    {{ $pengumuman->tanggal ? $pengumuman->tanggal->format('d F Y') : '-' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="info-item p-3 bg-light rounded-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="icon-circle bg-danger text-white">
                                                <i class="bi bi-calendar-x"></i>
                                            </div>
                                            <div>
                                                <label class="text-muted small fw-semibold text-uppercase d-block">Berlaku s/d</label>
                                                <p class="fw-semibold mb-0">
                                                    {{ $pengumuman->tanggal_selesai ? $pengumuman->tanggal_selesai->format('d F Y') : 'Tidak ada batas' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Gambar -->
                        @if($pengumuman->gambar)
                        <div class="col-12">
                            <div class="info-item p-3 bg-light rounded-3">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="icon-circle bg-secondary text-white">
                                        <i class="bi bi-image"></i>
                                    </div>
                                    <div style="flex: 1;">
                                        <label class="text-muted small fw-semibold text-uppercase d-block">Gambar</label>
                                        <div class="mt-2">
                                            <img src="{{ Storage::url($pengumuman->gambar) }}" 
                                                 alt="Gambar Pengumuman" 
                                                 class="img-fluid rounded-3 border" 
                                                 style="max-height: 300px; width: auto; object-fit: cover;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Deskripsi -->
                        @if($pengumuman->deskripsi)
                        <div class="col-12">
                            <div class="info-item p-3 bg-light rounded-3">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="icon-circle bg-info text-white">
                                        <i class="bi bi-file-text"></i>
                                    </div>
                                    <div>
                                        <label class="text-muted small fw-semibold text-uppercase d-block">Deskripsi</label>
                                        <p class="mb-0">{{ $pengumuman->deskripsi }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Konten -->
                        <div class="col-12">
                            <div class="info-item p-3 bg-light rounded-3">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="icon-circle bg-secondary text-white">
                                        <i class="bi bi-text-paragraph"></i>
                                    </div>
                                    <div style="flex: 1;">
                                        <label class="text-muted small fw-semibold text-uppercase d-block">Konten</label>
                                        <div class="p-3 bg-white rounded-3 border" style="line-height: 1.8; min-height: 100px;">
                                            {!! nl2br(e($pengumuman->konten)) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="col-12">
                            <hr>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('admin.pengumuman.edit', $pengumuman->id) }}" class="btn btn-warning">
                                    <i class="bi bi-pencil me-1"></i> Edit
                                </a>
                                
                                @if($pengumuman->status == 'draft')
                                <form action="{{ route('admin.pengumuman.update', $pengumuman->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="published">
                                    <button type="submit" class="btn btn-success" onclick="return confirm('Yakin ingin mempublikasikan pengumuman ini?')">
                                        <i class="bi bi-check-circle me-1"></i> Publikasikan
                                    </button>
                                </form>
                                @elseif($pengumuman->status == 'published')
                                <form action="{{ route('admin.pengumuman.update', $pengumuman->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="archived">
                                    <button type="submit" class="btn btn-secondary" onclick="return confirm('Yakin ingin mengarsipkan pengumuman ini?')">
                                        <i class="bi bi-archive me-1"></i> Arsipkan
                                    </button>
                                </form>
                                @endif

                                <form action="{{ route('admin.pengumuman.destroy', $pengumuman->id) }}" method="POST" class="d-inline" 
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengumuman {{ $pengumuman->judul }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="bi bi-trash me-1"></i> Hapus
                                    </button>
                                </form>

                                <div class="ms-auto">
                                    <a href="{{ route('admin.pengumuman.index') }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-arrow-left me-1"></i> Kembali
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
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
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        color: #d97706;
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
        align-items: center;
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
    
    .panel-header {
        padding: .9rem 1.25rem;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: .75rem;
        background: #fafbfc;
    }
    
    .section-title {
        display: flex;
        align-items: center;
        gap: .5rem;
        margin: 0;
        font-size: 1rem;
        font-weight: 600;
        color: #1a2236;
    }
    
    .section-title i {
        color: #4e9af1;
    }

    /* ============================================================
       INFO ITEMS
    ============================================================ */
    .info-item {
        transition: all 0.2s ease;
    }
    .info-item:hover {
        background-color: #e9ecef !important;
    }
    
    .icon-circle {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .icon-circle i {
        font-size: 20px;
    }
    
    .bg-primary { background-color: #0d6efd; }
    .bg-success { background-color: #198754; }
    .bg-info { background-color: #0dcaf0; }
    .bg-warning { background-color: #ffc107; }
    .bg-danger { background-color: #dc3545; }
    .bg-secondary { background-color: #6c757d; }
    .text-white { color: #fff; }

    /* ============================================================
       BADGE
    ============================================================ */
    .badge {
        font-weight: 500;
        padding: 0.4rem 0.8rem;
        font-size: 0.75rem;
    }
    
    .badge-published {
        background: #d4edda !important;
        color: #155724 !important;
    }
    .badge-draft {
        background: #e2e8f0 !important;
        color: #4a5568 !important;
    }
    .badge-secondary {
        background: #e2e8f0 !important;
        color: #6c757d !important;
    }
    .badge-pinned {
        background: #fff3cd !important;
        color: #856404 !important;
    }

    /* ============================================================
       BUTTONS
    ============================================================ */
    .btn {
        border-radius: 0.5rem;
        padding: 0.45rem 1.2rem;
        font-weight: 500;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }
    
    .btn-warning {
        background: #ff9f43;
        border-color: #ff9f43;
        color: #fff;
    }
    .btn-warning:hover {
        background: #f08c2e;
        border-color: #f08c2e;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(255, 159, 67, 0.3);
    }
    
    .btn-success {
        background: #28c76f;
        border-color: #28c76f;
        color: #fff;
    }
    .btn-success:hover {
        background: #1fb45e;
        border-color: #1fb45e;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(40, 199, 111, 0.3);
    }
    
    .btn-danger {
        background: #f56565;
        border-color: #f56565;
        color: #fff;
    }
    .btn-danger:hover {
        background: #e53e3e;
        border-color: #e53e3e;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(245, 101, 101, 0.3);
    }
    
    .btn-secondary {
        background: #e2e8f0;
        border-color: #e2e8f0;
        color: #4a5568;
    }
    .btn-secondary:hover {
        background: #d5dce6;
        border-color: #d5dce6;
    }
    
    .btn-outline-secondary {
        border-color: #e2e8f0;
        color: #4a5568;
    }
    .btn-outline-secondary:hover {
        background: #e2e8f0;
        border-color: #d5dce6;
    }
    
    .btn-sm {
        padding: 0.3rem 0.8rem;
        font-size: 0.8rem;
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
    .alert-dismissible .btn-close {
        padding: 1rem;
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
            flex-wrap: wrap;
        }
        .panel-header {
            flex-direction: column;
            align-items: flex-start;
        }
        .panel-body {
            padding: 1.25rem !important;
        }
        .d-flex.flex-wrap.gap-2 {
            flex-direction: column;
        }
        .d-flex.flex-wrap.gap-2 .btn {
            width: 100%;
        }
        .ms-auto {
            margin-left: 0 !important;
        }
        .icon-circle {
            width: 36px;
            height: 36px;
        }
        .icon-circle i {
            font-size: 16px;
        }
        .d-flex.flex-wrap.gap-4 {
            gap: 0.75rem !important;
        }
        .row.g-3 > [class*="col-"] {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }
        .p-3.bg-white.rounded-3.border {
            padding: 0.75rem !important;
        }
    }

    /* ============================================================
       ANIMATION
    ============================================================ */
    .panel {
        animation: fadeInUp 0.4s ease;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ============================================================
    // AUTO CLOSE ALERTS
    // ============================================================
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