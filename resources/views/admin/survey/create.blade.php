```blade
@extends('layouts.admin')

@section('title', 'Tambah Survey')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-plus-circle"></i></span>
        <div>
            <p class="eyebrow">Manajemen</p>
            <h1 class="h3 mb-0">Tambah Survey</h1>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="row">
        <div class="col-12 col-lg-10 mx-auto">
            <!-- Alert Errors -->
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle-fill me-2"></i>
                    <strong>Terjadi kesalahan!</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="panel">
                <div class="panel-header">
                    <h5 class="section-title"><i class="bi bi-file-check"></i> Form Tambah Survey</h5>
                    <p class="text-muted small mb-0">Isi data survey dengan lengkap dan benar.</p>
                </div>
                <div class="p-4">
                    <form action="{{ route('admin.survey.store') }}" method="POST">
                        @csrf
                        
                        <div class="row g-4">
                            <!-- Pilih Pelatihan -->
                            <div class="col-12">
                                <label for="training_id" class="form-label fw-semibold">
                                    Pilih Pelatihan <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-journal-bookmark"></i></span>
                                    <select name="training_id" id="training_id" class="form-select @error('training_id') is-invalid @enderror" required>
                                        <option value="">-- Pilih Pelatihan --</option>
                                        @foreach($trainings as $training)
                                            <option value="{{ $training->id }}" {{ old('training_id') == $training->id ? 'selected' : '' }}>
                                                {{ $training->judul }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('training_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Judul Survey -->
                            <div class="col-12">
                                <label for="judul" class="form-label fw-semibold">
                                    Judul Survey <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-text-paragraph"></i></span>
                                    <input type="text" name="judul" id="judul" class="form-control @error('judul') is-invalid @enderror" 
                                           value="{{ old('judul') }}" placeholder="Contoh: Survey Kepuasan Pelatihan A" required>
                                </div>
                                <small class="text-muted">Masukkan judul survey yang jelas dan deskriptif.</small>
                                @error('judul')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Deskripsi -->
                            <div class="col-12">
                                <label for="deskripsi" class="form-label fw-semibold">
                                    Deskripsi Singkat
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-file-text"></i></span>
                                    <textarea name="deskripsi" id="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" 
                                              rows="3" placeholder="Masukkan deskripsi survey (opsional)">{{ old('deskripsi') }}</textarea>
                                </div>
                                <small class="text-muted">Deskripsi singkat tentang tujuan survey.</small>
                                @error('deskripsi')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="col-12 col-md-6">
                                <label for="status" class="form-label fw-semibold">
                                    Status <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-toggle-on"></i></span>
                                    <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>📝 Draft (Belum Tampil)</option>
                                        <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>✅ Published (Tampil)</option>
                                        <option value="closed" {{ old('status') == 'closed' ? 'selected' : '' }}>🔒 Closed (Selesai/Ditutup)</option>
                                    </select>
                                </div>
                                <small class="text-muted">Draft: belum dipublikasikan, Published: tersedia, Closed: ditutup.</small>
                                @error('status')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Info Tambahan -->
                            <div class="col-12 col-md-6">
                                <div class="p-3 bg-light rounded-3 h-100">
                                    <h6 class="fw-bold mb-2"><i class="bi bi-info-circle text-primary me-1"></i> Informasi</h6>
                                    <ul class="text-muted small mb-0 ps-3">
                                        <li>Survey akan ditampilkan kepada peserta pelatihan yang sudah selesai.</li>
                                        <li>Setelah dibuat, Anda dapat menambahkan pertanyaan di menu detail survey.</li>
                                        <li>Status "Published" membuat survey aktif dan dapat diisi.</li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="col-12 mt-2">
                                <hr>
                                <div class="d-flex gap-2 flex-wrap">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save me-1"></i> Simpan Survey
                                    </button>
                                    <a href="{{ route('admin.survey.index') }}" class="btn btn-secondary">
                                        <i class="bi bi-x-circle me-1"></i> Batal
                                    </a>
                                    <button type="reset" class="btn btn-warning">
                                        <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                                    </button>
                                    <a href="{{ route('admin.survey.index') }}" class="btn btn-secondary">
                                        <i class="bi bi-arrow-left me-1"></i> Kembali
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tips -->
            <div class="panel mt-4">
                <div class="panel-header">
                    <h6 class="section-title"><i class="bi bi-lightbulb"></i> Tips Membuat Survey</h6>
                </div>
                <div class="p-4">
                    <div class="row g-3 text-center">
                        <div class="col-md-4">
                            <i class="bi bi-chat-quote fs-2 text-primary mb-2 d-block"></i>
                            <h6>Pertanyaan Jelas</h6>
                            <small class="text-muted">Buat pertanyaan yang mudah dipahami</small>
                        </div>
                        <div class="col-md-4">
                            <i class="bi bi-check2-square fs-2 text-success mb-2 d-block"></i>
                            <h6>Pilihan Lengkap</h6>
                            <small class="text-muted">Sediakan opsi jawaban yang bervariasi</small>
                        </div>
                        <div class="col-md-4">
                            <i class="bi bi-bar-chart fs-2 text-warning mb-2 d-block"></i>
                            <h6>Analisis Hasil</h6>
                            <small class="text-muted">Gunakan hasil untuk perbaikan pelatihan</small>
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
       FORM
    ============================================================ */
    .form-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: #1e293b;
    }
    .form-control {
        border-radius: 0.5rem;
        border-color: #e2e8f0;
        padding: 0.5rem 0.75rem;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }
    .form-control:focus {
        border-color: #4e9af1;
        box-shadow: 0 0 0 3px rgba(78, 154, 241, 0.15);
    }
    .form-select {
        border-radius: 0.5rem;
        border-color: #e2e8f0;
        padding: 0.5rem 0.75rem;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }
    .form-select:focus {
        border-color: #4e9af1;
        box-shadow: 0 0 0 3px rgba(78, 154, 241, 0.15);
    }
    .input-group-text {
        background: #f8fafc;
        border-color: #e2e8f0;
        color: #4a5568;
        font-size: 0.9rem;
    }
    .input-group .form-control,
    .input-group .form-select {
        border-radius: 0 0.5rem 0.5rem 0;
    }
    .text-muted {
        font-size: 0.75rem;
        color: #8a93a3 !important;
    }

    /* ============================================================
       BUTTONS
    ============================================================ */
    .btn {
        border-radius: 0.5rem;
        padding: 0.5rem 1.2rem;
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
        background: #3a7bc8;
        border-color: #3a7bc8;
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
    .btn-outline-warning {
        border-color: #ffc107;
        color: #856404;
    }
    .btn-outline-warning:hover {
        background: #ffc107;
        color: #fff;
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
        .panel-header {
            flex-direction: column;
            align-items: flex-start;
        }
        .p-4 {
            padding: 1.25rem !important;
        }
        .d-flex.gap-2 {
            flex-wrap: wrap;
        }
        .d-flex.gap-2 .btn {
            flex: 1;
            min-width: 100px;
        }
        .col-md-4 {
            margin-bottom: 1rem;
        }
    }

    @media (max-width: 576px) {
        .col-12.col-md-6 {
            flex: 0 0 100%;
            max-width: 100%;
        }
        .input-group-text {
            font-size: 0.8rem;
            padding: 0.25rem 0.5rem;
        }
        .form-control,
        .form-select {
            font-size: 0.85rem;
            padding: 0.4rem 0.6rem;
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

    // ============================================================
    // AUTO GENERATE SUGGESTION FOR JUDUL
    // ============================================================
    const trainingSelect = document.getElementById('training_id');
    const judulInput = document.getElementById('judul');

    if (trainingSelect && judulInput) {
        trainingSelect.addEventListener('change', function() {
            if (this.value && !judulInput.value) {
                const selectedOption = this.options[this.selectedIndex];
                const trainingName = selectedOption.text.trim();
                judulInput.value = 'Survey Kepuasan - ' + trainingName;
            }
        });
    }
});
</script>
@endpush
@endsection
```