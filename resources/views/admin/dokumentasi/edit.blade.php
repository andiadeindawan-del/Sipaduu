@extends('layouts.admin')

@section('title', 'Edit Dokumentasi')

@section('header')
<div class="page-heading d-flex justify-content-between align-items-center">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-pencil-square"></i></span>
        <div>
            <p class="eyebrow">Manajemen</p>
            <h1 class="h3 mb-0">Edit Dokumentasi</h1>
            <p class="text-muted mb-0">Perbarui informasi dokumentasi <strong>{{ $dokumentasi->judul }}</strong></p>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="row">
        <div class="col-12 col-lg-8 mx-auto">
            <!-- Alert Errors -->
            @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <strong>Ada kesalahan!</strong> Silakan periksa kembali formulir di bawah ini.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <div class="panel">
                <div class="panel-header">
                    <div>
                        <h5 class="section-title"><i class="bi bi-pencil-square text-warning"></i> Form Edit Dokumentasi</h5>
                        <p class="text-muted small mb-0">Perbarui data dokumentasi yang sudah ada</p>
                    </div>
                    <span class="badge bg-success">
                        <i class="bi bi-check-circle-fill me-1"></i> Aktif
                    </span>
                </div>

                <div class="p-4">
                    <form action="{{ route('admin.dokumentasi.update', $dokumentasi->id) }}" method="POST" id="editForm">
                        @csrf
                        @method('PUT')

                        <div class="row g-4">
                            <!-- Pelatihan -->
                            <div class="col-12">
                                <label for="training_id" class="form-label fw-semibold">
                                    <i class="bi bi-journal-bookmark me-1"></i> Pelatihan <span class="text-danger">*</span>
                                </label>
                                <select name="training_id" class="form-select @error('training_id') is-invalid @enderror" id="training_id" required>
                                    <option value="">Pilih Pelatihan</option>
                                    @if(isset($trainings) && $trainings->count() > 0)
                                        @foreach($trainings as $training)
                                            <option value="{{ $training->id }}" {{ (old('training_id') ?? $dokumentasi->training_id) == $training->id ? 'selected' : '' }}>
                                                {{ $training->judul }}
                                            </option>
                                        @endforeach
                                    @else
                                        <option value="" disabled>Tidak ada pelatihan</option>
                                    @endif
                                </select>
                                @error('training_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Judul -->
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" name="judul" 
                                           class="form-control @error('judul') is-invalid @enderror" 
                                           id="judul" 
                                           value="{{ old('judul') ?? $dokumentasi->judul }}" 
                                           placeholder="Masukkan judul dokumentasi" required>
                                    <label for="judul">
                                        <i class="bi bi-text-paragraph me-1"></i> Judul Dokumentasi <span class="text-danger">*</span>
                                    </label>
                                </div>
                                @error('judul')
                                <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Link -->
                            <div class="col-12">
                                <label for="link" class="form-label fw-semibold">
                                    <i class="bi bi-link-45deg me-1"></i> Link <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-globe"></i></span>
                                    <input type="url" name="link" 
                                           class="form-control @error('link') is-invalid @enderror" 
                                           id="link"
                                           value="{{ old('link') ?? $dokumentasi->link }}" 
                                           placeholder="https://example.com/dokumentasi" required>
                                    <button type="button" class="btn btn-outline-secondary" 
                                            onclick="window.open(document.getElementById('link').value, '_blank')" 
                                            title="Buka Link" id="openLinkBtn">
                                        <i class="bi bi-box-arrow-up-right"></i>
                                    </button>
                                </div>
                                <small class="text-muted">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Masukkan URL lengkap dokumentasi (Google Drive, YouTube, dll).
                                </small>
                                @error('link')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Deskripsi -->
                            <div class="col-12">
                                <label for="deskripsi" class="form-label fw-semibold">
                                    <i class="bi bi-file-text me-1"></i> Deskripsi
                                </label>
                                <textarea name="deskripsi" 
                                          class="form-control @error('deskripsi') is-invalid @enderror" 
                                          id="deskripsi"
                                          rows="4" 
                                          placeholder="Deskripsikan dokumentasi ini secara lengkap...">{{ old('deskripsi') ?? $dokumentasi->deskripsi }}</textarea>
                                @error('deskripsi')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Preview -->
                            <div class="col-12">
                                <hr>
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-eye me-1"></i> Preview
                                </label>
                                <div class="p-4 border rounded-3 bg-light" id="previewContainer">
                                    <div class="d-flex align-items-center gap-3 flex-wrap">
                                        <span class="badge bg-info">
                                            <i class="bi bi-journal-bookmark me-1"></i>
                                            <span id="previewTraining">{{ $dokumentasi->training->judul ?? 'Pelatihan' }}</span>
                                        </span>
                                        <span class="fw-semibold" id="previewJudul">{{ $dokumentasi->judul }}</span>
                                    </div>
                                    <div class="mt-2">
                                        <small class="text-muted">
                                            <i class="bi bi-link-45deg me-1"></i>
                                            <span id="previewLink">{{ Str::limit($dokumentasi->link, 50) }}</span>
                                        </small>
                                    </div>
                                    <p class="text-muted small mt-2 mb-0">Preview tampilan dokumentasi</p>
                                </div>
                            </div>

                            <!-- Informasi Tambahan -->
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
                                                    <p class="fw-semibold mb-0">{{ $dokumentasi->created_at ? $dokumentasi->created_at->format('d/m/Y H:i') : '-' }}</p>
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
                                                    <p class="fw-semibold mb-0">{{ $dokumentasi->updated_at ? $dokumentasi->updated_at->format('d/m/Y H:i') : '-' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Divider -->
                            <div class="col-12">
                                <hr class="my-2">
                            </div>

                            <!-- Submit Buttons -->
                            <div class="col-12">
                                <div class="d-flex flex-wrap gap-2">
                                    <button type="submit" class="btn btn-warning px-4">
                                        <i class="bi bi-save me-1"></i> Perbarui Dokumentasi
                                    </button>
                                    <a href="{{ route('admin.dokumentasi.show', $dokumentasi->id) }}" class="btn btn-outline-info">
                                        <i class="bi bi-eye me-1"></i> Lihat Detail
                                    </a>
                                    <a href="{{ route('admin.dokumentasi.index') }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
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
       FORM FLOATING
    ============================================================ */
    .form-floating > .form-control,
    .form-floating > .form-select {
        height: calc(3.5rem + 2px);
        padding: 1rem 0.75rem;
        border-radius: 0.5rem;
        border-color: #e2e8f0;
    }
    .form-floating > .form-control:focus,
    .form-floating > .form-select:focus {
        border-color: #4e9af1;
        box-shadow: 0 0 0 3px rgba(78, 154, 241, 0.15);
    }
    .form-floating > label {
        padding: 1rem 0.75rem;
        color: #8a93a3;
    }

    /* ============================================================
       FORM
    ============================================================ */
    .form-label {
        font-size: 0.875rem;
        margin-bottom: 0.4rem;
        color: #1a2236;
    }
    
    .input-group-text {
        background: #f8fafc;
        border-color: #e2e8f0;
        color: #8a93a3;
    }
    
    .form-control, .form-select {
        border-color: #e2e8f0;
        border-radius: 0.5rem;
        padding: 0.5rem 0.75rem;
        font-size: 0.9rem;
        transition: all 0.2s ease;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #4e9af1;
        box-shadow: 0 0 0 3px rgba(78, 154, 241, 0.15);
    }
    
    .form-control.is-invalid, .form-select.is-invalid {
        border-color: #dc3545;
        box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.15);
    }
    .invalid-feedback {
        font-size: 0.8rem;
        color: #dc3545;
    }
    
    .input-group .form-control, 
    .input-group .form-select {
        border-radius: 0 0.5rem 0.5rem 0;
    }
    
    .input-group .input-group-text:first-child {
        border-radius: 0.5rem 0 0 0.5rem;
    }
    
    .input-group .btn {
        border-radius: 0 0.5rem 0.5rem 0;
    }
    
    textarea.form-control {
        resize: vertical;
        min-height: 80px;
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
    
    .bg-info { background-color: #0dcaf0; }
    .bg-warning { background-color: #ffc107; }
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
    .badge.bg-info {
        background: #cfe2ff !important;
        color: #084298 !important;
    }

    /* ============================================================
       PREVIEW
    ============================================================ */
    #previewContainer {
        transition: all 0.3s ease;
    }
    #previewContainer:hover {
        background: #e9ecef !important;
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
    
    .btn-outline-info {
        border-color: #0dcaf0;
        color: #0dcaf0;
    }
    .btn-outline-info:hover {
        background: #0dcaf0;
        border-color: #0dcaf0;
        color: #fff;
        transform: translateY(-1px);
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
        .form-floating > .form-control,
        .form-floating > .form-select {
            height: calc(3rem + 2px);
            padding: 0.75rem 0.75rem;
        }
        .form-floating > label {
            padding: 0.75rem;
        }
        .input-group {
            flex-wrap: wrap;
        }
        .input-group .btn {
            border-radius: 0.5rem;
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
    // PREVIEW REAL-TIME
    // ============================================================
    const judulInput = document.getElementById('judul');
    const linkInput = document.getElementById('link');
    const trainingSelect = document.getElementById('training_id');
    const previewJudul = document.getElementById('previewJudul');
    const previewLink = document.getElementById('previewLink');
    const previewTraining = document.getElementById('previewTraining');
    const openLinkBtn = document.getElementById('openLinkBtn');

    function updatePreview() {
        const judul = judulInput.value || 'Judul dokumentasi';
        const link = linkInput.value || 'https://example.com';
        const training = trainingSelect.options[trainingSelect.selectedIndex]?.text || 'Pelatihan';
        
        previewJudul.textContent = judul;
        previewTraining.textContent = training;
        
        if (link.length > 50) {
            previewLink.textContent = link.substring(0, 47) + '...';
        } else {
            previewLink.textContent = link;
        }
    }

    if (judulInput) judulInput.addEventListener('input', updatePreview);
    if (linkInput) linkInput.addEventListener('input', updatePreview);
    if (trainingSelect) trainingSelect.addEventListener('change', updatePreview);

    // ============================================================
    // OPEN LINK BUTTON
    // ============================================================
    if (openLinkBtn) {
        openLinkBtn.addEventListener('click', function() {
            const link = linkInput.value;
            if (link && link.startsWith('http')) {
                window.open(link, '_blank');
            } else if (link) {
                window.open('https://' + link, '_blank');
            }
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
    // FOCUS ON FIRST INPUT
    // ============================================================
    const firstInput = document.querySelector('input[name="judul"]');
    if (firstInput) {
        firstInput.focus();
        firstInput.select();
    }
});
</script>
@endpush
@endsection