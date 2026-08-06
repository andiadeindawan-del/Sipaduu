@extends('layouts.admin')

@section('title', 'Detail Dokumentasi')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-images"></i></span>
        <div>
            <p class="eyebrow mb-1">Management</p>
            <h1 class="h3 mb-0">Detail Dokumentasi</h1>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
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

    <!-- Detail -->
    <div class="panel">
        <div class="panel-header">
            <div>
                <h5 class="section-title"><i class="bi bi-info-circle"></i> Informasi Dokumentasi</h5>
                <p class="text-muted small mb-0">Detail lengkap dokumentasi <strong>{{ $dokumentasi->judul }}</strong></p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.dokumentasi.index') }}" class="btn btn-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
        <div class="panel-body p-4">
            <div class="row g-4">
                <!-- Judul -->
                <div class="col-12">
                    <div class="detail-item">
                        <label class="text-muted small fw-semibold d-block mb-1">Judul</label>
                        <p class="fw-semibold fs-5 mb-0">{{ $dokumentasi->judul }}</p>
                    </div>
                </div>

                <!-- Pelatihan -->
                <div class="col-12 col-md-6">
                    <div class="detail-item">
                        <label class="text-muted small fw-semibold d-block mb-1">Pelatihan</label>
                        <p class="mb-0">
                            <i class="bi bi-journal-bookmark text-primary me-1"></i>
                            {{ $dokumentasi->training->judul ?? '-' }}
                        </p>
                    </div>
                </div>

                <!-- Status -->
                <div class="col-12 col-md-6">
                    <div class="detail-item">
                        <label class="text-muted small fw-semibold d-block mb-1">Status</label>
                        <p class="mb-0">
                            <span class="badge bg-success">
                                <i class="bi bi-check-circle-fill me-1"></i> Aktif
                            </span>
                        </p>
                    </div>
                </div>

                <!-- Link -->
                <div class="col-12">
                    <div class="detail-item">
                        <label class="text-muted small fw-semibold d-block mb-1">Link</label>
                        <div class="p-3 bg-light rounded d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <a href="{{ $dokumentasi->link }}" target="_blank" 
                               class="text-primary text-decoration-none text-break">
                                <i class="bi bi-link-45deg me-1"></i>
                                {{ $dokumentasi->link }}
                            </a>
                            <a href="{{ $dokumentasi->link }}" target="_blank" 
                               class="btn btn-sm btn-success">
                                <i class="bi bi-box-arrow-up-right me-1"></i> Buka Link
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Deskripsi -->
                @if($dokumentasi->deskripsi)
                <div class="col-12">
                    <div class="detail-item">
                        <label class="text-muted small fw-semibold d-block mb-1">Deskripsi</label>
                        <p class="mb-0" style="white-space: pre-line;">{{ $dokumentasi->deskripsi }}</p>
                    </div>
                </div>
                @endif

                <!-- Timestamps -->
                <div class="col-12">
                    <hr>
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="text-muted small fw-semibold d-block mb-1">Dibuat</label>
                            <p class="mb-0">
                                <i class="bi bi-calendar-plus me-1"></i>
                                {{ $dokumentasi->created_at ? $dokumentasi->created_at->format('d/m/Y H:i') : '-' }}
                            </p>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="text-muted small fw-semibold d-block mb-1">Diperbarui</label>
                            <p class="mb-0">
                                <i class="bi bi-calendar-check me-1"></i>
                                {{ $dokumentasi->updated_at ? $dokumentasi->updated_at->format('d/m/Y H:i') : '-' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="col-12">
                    <hr>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-danger" 
                                data-bs-toggle="modal" data-bs-target="#deleteModal{{ $dokumentasi->id }}">
                            <i class="bi bi-trash me-1"></i> Hapus
                        </button>
                        <a href="{{ route('admin.dokumentasi.edit', $dokumentasi->id) }}" class="btn btn-warning">
                            <i class="bi bi-pencil me-1"></i> Edit
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================
     MODAL DELETE
============================================================ -->
<div class="modal fade" id="deleteModal{{ $dokumentasi->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-triangle text-danger me-2"></i>
                    Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus dokumentasi <strong>{{ $dokumentasi->judul }}</strong>?</p>
                <p class="text-muted small">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin.dokumentasi.destroy', $dokumentasi->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-1"></i> Hapus
                    </button>
                </form>
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

    .panel-body {
        background: #fff;
    }

    /* ============================================================
       DETAIL ITEMS
    ============================================================ */
    .detail-item {
        padding: 0.5rem 0;
    }
    .detail-item:not(:last-child) {
        border-bottom: 1px solid #f0f0f0;
    }
    .detail-item label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #8a93a3;
    }
    .detail-item p {
        font-size: 0.95rem;
        color: #1a2236;
    }

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
    
    .btn-secondary {
        background: #e2e8f0;
        border-color: #e2e8f0;
        color: #4a5568;
    }
    .btn-secondary:hover {
        background: #d5dce6;
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
        .panel-header {
            flex-direction: column;
            align-items: flex-start;
        }
        .panel-body {
            padding: 1.25rem !important;
        }
        .d-flex.justify-content-end {
            flex-direction: column;
            gap: 0.5rem !important;
        }
        .d-flex.justify-content-end .btn {
            width: 100%;
        }
        .p-3.bg-light {
            flex-direction: column;
            align-items: flex-start !important;
        }
        .p-3.bg-light .btn {
            width: 100%;
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
    // Auto close alerts after 5 seconds
    setTimeout(function() {
        document.querySelectorAll('.alert').forEach(function(alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);

    // Copy link to clipboard
    const linkElement = document.querySelector('.p-3.bg-light a');
    if (linkElement) {
        linkElement.addEventListener('click', function(e) {
            // Only if user wants to copy (Ctrl+Click or Shift+Click)
            if (e.ctrlKey || e.shiftKey) {
                e.preventDefault();
                const link = this.href;
                navigator.clipboard.writeText(link).then(function() {
                    // Show temporary feedback
                    const btn = document.querySelector('.p-3.bg-light .btn-success');
                    const originalText = btn.innerHTML;
                    btn.innerHTML = '<i class="bi bi-check-circle me-1"></i> Tersalin!';
                    btn.classList.remove('btn-success');
                    btn.classList.add('btn-info');
                    setTimeout(function() {
                        btn.innerHTML = originalText;
                        btn.classList.remove('btn-info');
                        btn.classList.add('btn-success');
                    }, 2000);
                }).catch(function() {
                    // Fallback
                    alert('Link: ' + link);
                });
            }
        });
    }
});
</script>
@endpush
@endsection