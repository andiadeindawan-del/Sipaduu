@extends('layouts.admin')

@section('title', 'Detail Dokumentasi')

@section('header')
<div class="page-heading d-flex justify-content-between align-items-center">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-images"></i></span>
        <div>
            <p class="eyebrow">Manajemen</p>
            <h1 class="h3 mb-0">Detail Dokumentasi</h1>
            <p class="text-muted mb-0">Informasi lengkap dokumentasi <strong>{{ $dokumentasi->judul }}</strong></p>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="row">
        <div class="col-12 col-lg-8 mx-auto">
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

            <!-- Main Card -->
            <div class="panel">
                <div class="panel-header">
                    <h5 class="section-title"><i class="bi bi-info-circle"></i> Informasi Dokumentasi</h5>
                    <span class="badge bg-success">
                        <i class="bi bi-check-circle-fill me-1"></i> Aktif
                    </span>
                </div>

                <div class="p-4">
                    <div class="row g-4">
                        <!-- Judul -->
                        <div class="col-12">
                            <div class="info-item p-3 bg-light rounded-3">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="icon-circle bg-primary text-white">
                                        <i class="bi bi-text-paragraph"></i>
                                    </div>
                                    <div>
                                        <label class="text-muted small fw-semibold text-uppercase d-block">Judul</label>
                                        <p class="fw-semibold mb-0 fs-5">{{ $dokumentasi->judul }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Info Grid -->
                        <div class="col-12">
                            <div class="row g-3">
                                <!-- Pelatihan -->
                                <div class="col-12 col-md-6">
                                    <div class="info-item p-3 bg-light rounded-3 h-100">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="icon-circle bg-info text-white">
                                                <i class="bi bi-journal-bookmark"></i>
                                            </div>
                                            <div>
                                                <label class="text-muted small fw-semibold text-uppercase d-block">Pelatihan</label>
                                                <p class="fw-semibold mb-0">
                                                    {{ $dokumentasi->training->judul ?? '-' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="col-12 col-md-6">
                                    <div class="info-item p-3 bg-light rounded-3 h-100">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="icon-circle bg-success text-white">
                                                <i class="bi bi-check-circle"></i>
                                            </div>
                                            <div>
                                                <label class="text-muted small fw-semibold text-uppercase d-block">Status</label>
                                                <p class="fw-semibold mb-0">
                                                    <span class="badge bg-success">
                                                        <i class="bi bi-check-circle-fill me-1"></i> Aktif
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Link -->
                        <div class="col-12">
                            <div class="info-item p-3 bg-light rounded-3">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="icon-circle bg-warning text-white">
                                        <i class="bi bi-link-45deg"></i>
                                    </div>
                                    <div style="flex: 1; min-width: 0;">
                                        <label class="text-muted small fw-semibold text-uppercase d-block">Link</label>
                                        <div class="d-flex align-items-center gap-2 flex-wrap">
                                            <a href="{{ $dokumentasi->link }}" target="_blank" 
                                               class="text-primary text-decoration-none text-truncate" 
                                               style="max-width: 400px; word-break: break-all;">
                                                <i class="bi bi-link-45deg me-1"></i>
                                                {{ $dokumentasi->link }}
                                            </a>
                                            <a href="{{ $dokumentasi->link }}" target="_blank" 
                                               class="btn btn-sm btn-success">
                                                <i class="bi bi-box-arrow-up-right me-1"></i> Buka Link
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-secondary" 
                                                    onclick="copyLink('{{ $dokumentasi->link }}')" title="Salin Link">
                                                <i class="bi bi-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        @if($dokumentasi->deskripsi)
                        <div class="col-12">
                            <div class="info-item p-3 bg-light rounded-3">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="icon-circle bg-secondary text-white">
                                        <i class="bi bi-file-text"></i>
                                    </div>
                                    <div>
                                        <label class="text-muted small fw-semibold text-uppercase d-block">Deskripsi</label>
                                        <p class="mb-0" style="white-space: pre-line;">{{ $dokumentasi->deskripsi }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Meta Info -->
                        <div class="col-12">
                            <hr>
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <div class="info-item p-3 bg-light rounded-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="icon-circle bg-info text-white">
                                                <i class="bi bi-clock"></i>
                                            </div>
                                            <div>
                                                <label class="text-muted small fw-semibold text-uppercase d-block">Dibuat</label>
                                                <p class="fw-semibold mb-0">
                                                    <i class="bi bi-calendar-plus me-1"></i>
                                                    {{ $dokumentasi->created_at ? $dokumentasi->created_at->format('d/m/Y H:i') : '-' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="info-item p-3 bg-light rounded-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="icon-circle bg-warning text-white">
                                                <i class="bi bi-clock-history"></i>
                                            </div>
                                            <div>
                                                <label class="text-muted small fw-semibold text-uppercase d-block">Diperbarui</label>
                                                <p class="fw-semibold mb-0">
                                                    <i class="bi bi-calendar-check me-1"></i>
                                                    {{ $dokumentasi->updated_at ? $dokumentasi->updated_at->format('d/m/Y H:i') : '-' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="col-12">
                            <hr>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('admin.dokumentasi.edit', $dokumentasi->id) }}" class="btn btn-warning">
                                    <i class="bi bi-pencil me-1"></i> Edit
                                </a>
                                <form action="{{ route('admin.dokumentasi.destroy', $dokumentasi->id) }}" method="POST" class="d-inline" 
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus dokumentasi {{ $dokumentasi->judul }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="bi bi-trash me-1"></i> Hapus
                                    </button>
                                </form>
                                <div class="ms-auto">
                                    <a href="{{ route('admin.dokumentasi.index') }}" class="btn btn-secondary">
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
    .bg-secondary { background-color: #6c757d; }
    .text-white { color: #fff; }

    /* ============================================================
       BADGE
    ============================================================ */
    .badge.bg-success {
        background: #d4edda !important;
        color: #155724 !important;
        padding: 0.4rem 0.8rem;
        font-weight: 500;
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
        .d-flex.align-items-center.gap-2.flex-wrap {
            flex-direction: column;
            align-items: flex-start !important;
        }
        .d-flex.align-items-center.gap-2.flex-wrap .btn {
            width: 100%;
        }
        .text-truncate {
            max-width: 100% !important;
        }
        .icon-circle {
            width: 36px;
            height: 36px;
        }
        .icon-circle i {
            font-size: 16px;
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

// ============================================================
// COPY LINK TO CLIPBOARD
// ============================================================
function copyLink(link) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(link).then(function() {
            // Show feedback
            showCopyFeedback('Link berhasil disalin!');
        }).catch(function() {
            // Fallback
            fallbackCopy(link);
        });
    } else {
        fallbackCopy(link);
    }
}

function fallbackCopy(link) {
    // Create temporary input
    const input = document.createElement('input');
    input.value = link;
    document.body.appendChild(input);
    input.select();
    try {
        document.execCommand('copy');
        showCopyFeedback('Link berhasil disalin!');
    } catch (e) {
        alert('Link: ' + link);
    }
    document.body.removeChild(input);
}

function showCopyFeedback(message) {
    // Find the copy button
    const copyBtn = document.querySelector('[onclick*="copyLink"]');
    if (copyBtn) {
        const originalText = copyBtn.innerHTML;
        copyBtn.innerHTML = '<i class="bi bi-check-circle me-1"></i>';
        copyBtn.classList.remove('btn-outline-secondary');
        copyBtn.classList.add('btn-success');
        setTimeout(function() {
            copyBtn.innerHTML = originalText;
            copyBtn.classList.remove('btn-success');
            copyBtn.classList.add('btn-outline-secondary');
        }, 2000);
    }
}
</script>
@endpush
@endsection