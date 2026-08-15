@extends('layouts.admin')

@section('title', 'Edit Pertanyaan Survey')

@section('header')
<div class="page-heading d-flex justify-content-between align-items-center">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-pencil-square"></i></span>
        <div>
            <p class="eyebrow">Manajemen Survey</p>
            <h1 class="h3 mb-0">Edit Pertanyaan</h1>
            <p class="text-muted mb-0">Perbarui pertanyaan untuk survey <strong>{{ $survey->judul }}</strong></p>
        </div>
    </div>
    <div class="heading-actions">
        <a href="{{ route('admin.survey.questions.index', $survey->id) }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
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
                        <h5 class="section-title"><i class="bi bi-pencil-square text-warning"></i> Form Edit Pertanyaan</h5>
                        <p class="text-muted small mb-0">Perbarui data pertanyaan yang sudah ada</p>
                    </div>
                    <span class="badge bg-info">
                        <i class="bi bi-list-check me-1"></i>
                        Urutan ke-{{ $question->order ?? 1 }}
                    </span>
                </div>

                <div class="p-4">
                    <form action="{{ route('admin.survey.questions.update', [$survey->id, $question->id]) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-4">
                            <!-- Pertanyaan -->
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" name="pertanyaan" 
                                           class="form-control @error('pertanyaan') is-invalid @enderror" 
                                           id="pertanyaan" 
                                           value="{{ old('pertanyaan') ?? $question->pertanyaan }}" 
                                           placeholder="Masukkan pertanyaan" required>
                                    <label for="pertanyaan">
                                        <i class="bi bi-text-paragraph me-1"></i> Pertanyaan <span class="text-danger">*</span>
                                    </label>
                                </div>
                                @error('pertanyaan')
                                <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tipe Jawaban -->
                            <div class="col-12">
                                <label for="tipe" class="form-label fw-semibold">
                                    <i class="bi bi-ui-checks me-1"></i> Tipe Jawaban <span class="text-danger">*</span>
                                </label>
                                <select name="tipe" class="form-select @error('tipe') is-invalid @enderror" id="tipe" required>
                                    <option value="boolean" {{ (old('tipe') ?? $question->tipe) == 'boolean' ? 'selected' : '' }}>
                                        <i class="bi bi-ui-checks me-1"></i> Pilihan (Puas / Tidak Puas)
                                    </option>
                                    <option value="rating_5" {{ (old('tipe') ?? $question->tipe) == 'rating_5' ? 'selected' : '' }}>
                                        <i class="bi bi-star-fill me-1"></i> Rating (1 - 5 Bintang)
                                    </option>
                                    <option value="text" {{ (old('tipe') ?? $question->tipe) == 'text' ? 'selected' : '' }}>
                                        <i class="bi bi-justify-left me-1"></i> Teks / Esai Pendek
                                    </option>
                                </select>
                                @error('tipe')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Urutan & Info -->
                            <div class="col-12">
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <label for="order" class="form-label fw-semibold">
                                            <i class="bi bi-list-ol me-1"></i> Urutan Ke- <span class="text-danger">*</span>
                                        </label>
                                        <input type="number" name="order" 
                                               class="form-control @error('order') is-invalid @enderror" 
                                               id="order"
                                               value="{{ old('order') ?? $question->order }}" 
                                               min="1" required>
                                        <small class="text-muted">Semakin kecil angka, semakin atas tampilnya.</small>
                                        @error('order')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">
                                            <i class="bi bi-info-circle me-1"></i> Informasi
                                        </label>
                                        <div class="p-3 bg-light rounded-3">
                                            <div class="d-flex justify-content-between">
                                                <span class="text-muted">Survey:</span>
                                                <span class="fw-semibold">{{ $survey->judul }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between mt-1">
                                                <span class="text-muted">Status:</span>
                                                @php
                                                    $statusMap = [
                                                        'draft' => ['label' => 'Draft', 'class' => 'badge-draft'],
                                                        'published' => ['label' => 'Published', 'class' => 'badge-published'],
                                                        'closed' => ['label' => 'Closed', 'class' => 'badge-secondary'],
                                                    ];
                                                    $status = $statusMap[$survey->status] ?? ['label' => $survey->status, 'class' => 'badge-draft'];
                                                @endphp
                                                <span class="badge {{ $status['class'] }}">
                                                    <i class="bi bi-circle-fill me-1" style="font-size: 6px;"></i>
                                                    {{ $status['label'] }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Preview -->
                            <div class="col-12">
                                <hr>
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-eye me-1"></i> Preview Pertanyaan
                                </label>
                                <div class="p-4 border rounded-3 bg-light" id="previewContainer">
                                    <div class="d-flex align-items-center gap-3 flex-wrap">
                                        <span class="badge bg-primary" id="previewBadge">
                                            <i class="bi bi-star-fill me-1"></i> Rating
                                        </span>
                                        <span id="previewText" class="fw-semibold">{{ $question->pertanyaan }}</span>
                                    </div>
                                    <p class="text-muted small mt-2 mb-0">Preview tampilan pertanyaan</p>
                                </div>
                            </div>

                            <!-- Divider -->
                            <div class="col-12">
                                <hr class="my-2">
                            </div>

                            <!-- Submit Buttons -->
                            <div class="col-12">
                                <div class="d-flex flex-wrap gap-2">
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="bi bi-save me-1"></i> Perbarui Pertanyaan
                                    </button>
                                    <a href="{{ route('admin.survey.questions.index', $survey->id) }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Informasi Tambahan -->
            <div class="mt-4 text-center">
                <div class="d-flex justify-content-center gap-4 text-muted small">
                    <span>
                        <i class="bi bi-clock me-1"></i> Dibuat: {{ $question->created_at ? $question->created_at->format('d/m/Y H:i') : '-' }}
                    </span>
                    <span>
                        <i class="bi bi-clock-history me-1"></i> Diperbarui: {{ $question->updated_at ? $question->updated_at->format('d/m/Y H:i') : '-' }}
                    </span>
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

    /* ============================================================
       BADGE
    ============================================================ */
    .badge {
        font-weight: 500;
        padding: 0.35rem 0.6rem;
        font-size: 0.75rem;
    }
    
    .badge-draft {
        background: #e2e8f0 !important;
        color: #4a5568 !important;
    }
    .badge-published {
        background: #d4edda !important;
        color: #155724 !important;
    }
    .badge-secondary {
        background: #e2e8f0 !important;
        color: #6c757d !important;
    }
    .badge.bg-info {
        background: #e3f0ff !important;
        color: #0d6efd !important;
    }
    .badge.bg-primary {
        background: #cfe2ff !important;
        color: #084298 !important;
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
    
    .btn-primary {
        background: #4e9af1;
        border-color: #4e9af1;
        color: #fff;
    }
    .btn-primary:hover {
        background: #3d8ae0;
        border-color: #3d8ae0;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(78, 154, 241, 0.3);
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
       PREVIEW
    ============================================================ */
    #previewContainer {
        transition: all 0.3s ease;
    }
    #previewContainer:hover {
        background: #e9ecef !important;
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
        .form-floating > .form-control,
        .form-floating > .form-select {
            height: calc(3rem + 2px);
            padding: 0.75rem 0.75rem;
        }
        .form-floating > label {
            padding: 0.75rem;
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
    // PREVIEW PERTANYAAN
    // ============================================================
    const pertanyaanInput = document.getElementById('pertanyaan');
    const tipeSelect = document.getElementById('tipe');
    const previewText = document.getElementById('previewText');
    const previewBadge = document.getElementById('previewBadge');

    function updatePreview() {
        const text = pertanyaanInput.value || 'Masukkan pertanyaan...';
        const tipe = tipeSelect.value;
        
        previewText.textContent = text;
        
        let badgeIcon = '';
        let badgeLabel = '';
        let badgeClass = '';
        
        switch(tipe) {
            case 'rating_5':
                badgeIcon = 'bi-star-fill';
                badgeLabel = 'Rating 1-5';
                badgeClass = 'badge bg-warning text-dark';
                break;
            case 'boolean':
                badgeIcon = 'bi-ui-checks';
                badgeLabel = 'Puas / Tidak Puas';
                badgeClass = 'badge bg-primary';
                break;
            case 'text':
                badgeIcon = 'bi-justify-left';
                badgeLabel = 'Esai Pendek';
                badgeClass = 'badge bg-info';
                break;
            default:
                badgeIcon = 'bi-ui-checks';
                badgeLabel = 'Pilihan';
                badgeClass = 'badge bg-primary';
        }
        
        previewBadge.className = badgeClass;
        previewBadge.innerHTML = `<i class="bi ${badgeIcon} me-1"></i> ${badgeLabel}`;
    }

    if (pertanyaanInput) {
        pertanyaanInput.addEventListener('input', updatePreview);
    }
    if (tipeSelect) {
        tipeSelect.addEventListener('change', updatePreview);
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
});
</script>
@endpush
@endsection