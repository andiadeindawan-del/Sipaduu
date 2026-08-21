@extends('layouts.admin')

@section('title', 'Tambah Pertanyaan Survey')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-plus-circle"></i></span>
        <div>
            <p class="eyebrow">Manajemen</p>
            <h1 class="h3 mb-0">Tambah Pertanyaan</h1>
            <p class="text-muted mb-0">
                Survey: <strong>{{ $survey->judul }}</strong>
                @if($survey->training)
                    <span class="text-muted">| Pelatihan: {{ $survey->training->judul }}</span>
                @endif
            </p>
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
                    <h5 class="section-title"><i class="bi bi-question-square"></i> Form Tambah Pertanyaan</h5>
                    <p class="text-muted small mb-0">Isi data pertanyaan dengan lengkap.</p>
                </div>
                <div class="p-4">
                    <form action="{{ route('admin.survey.questions.store', $survey->id) }}" method="POST">
                        @csrf
                        
                        <div class="row g-4">
                            <!-- Pertanyaan -->
                            <div class="col-12">
                                <label for="pertanyaan" class="form-label fw-semibold">
                                    Pertanyaan <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-question-lg"></i></span>
                                    <input type="text" name="pertanyaan" id="pertanyaan" 
                                           class="form-control @error('pertanyaan') is-invalid @enderror" 
                                           value="{{ old('pertanyaan') }}" 
                                           placeholder="Contoh: Bagaimana pendapat Anda tentang materi ini?" required>
                                </div>
                                <small class="text-muted">Masukkan pertanyaan survey dengan jelas dan mudah dipahami.</small>
                                @error('pertanyaan')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tipe Jawaban -->
                            <div class="col-12 col-md-6">
                                <label for="tipe" class="form-label fw-semibold">
                                    Tipe Jawaban <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-list-ul"></i></span>
                                    <select name="tipe" id="tipe" class="form-select @error('tipe') is-invalid @enderror" required>
                                        <option value="boolean" {{ old('tipe') == 'boolean' ? 'selected' : '' }}>✅ Pilihan (Puas / Tidak Puas)</option>
                                        <option value="rating_5" {{ old('tipe') == 'rating_5' ? 'selected' : '' }}>⭐ Rating (1 - 5 Bintang)</option>
                                        <option value="text" {{ old('tipe') == 'text' ? 'selected' : '' }}>✍️ Teks / Esai Pendek</option>
                                    </select>
                                </div>
                                <small class="text-muted">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Pilih jenis jawaban yang sesuai untuk pertanyaan.
                                </small>
                                @error('tipe')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Urutan -->
                            <div class="col-12 col-md-6">
                                <label for="order" class="form-label fw-semibold">
                                    Urutan Ke- <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-list-ol"></i></span>
                                    <input type="number" name="order" id="order" 
                                           class="form-control @error('order') is-invalid @enderror" 
                                           value="{{ old('order', $survey->questions->count() + 1) }}" 
                                           min="1" required>
                                </div>
                                <small class="text-muted">Urutan tampil pertanyaan dalam survey.</small>
                                @error('order')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Info Tipe Jawaban -->
                            <div class="col-12">
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <div class="p-3 bg-light rounded-3 text-center">
                                            <i class="bi bi-check-circle fs-3 text-success d-block mb-2"></i>
                                            <h6 class="fw-bold mb-0 small">Pilihan</h6>
                                            <small class="text-muted">Puas / Tidak Puas</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="p-3 bg-light rounded-3 text-center">
                                            <i class="bi bi-star fs-3 text-warning d-block mb-2"></i>
                                            <h6 class="fw-bold mb-0 small">Rating</h6>
                                            <small class="text-muted">Skala 1 - 5 Bintang</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="p-3 bg-light rounded-3 text-center">
                                            <i class="bi bi-pencil fs-3 text-primary d-block mb-2"></i>
                                            <h6 class="fw-bold mb-0 small">Teks</h6>
                                            <small class="text-muted">Esai / Saran / Kritik</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="col-12 mt-2">
                                <hr>
                                <div class="d-flex gap-2 flex-wrap">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save me-1"></i> Simpan Pertanyaan
                                    </button>
                                    <a href="{{ route('admin.survey.show', $survey->id) }}" class="btn btn-secondary">
                                        <i class="bi bi-x-circle me-1"></i> Batal
                                    </a>
                                    <button type="reset" class="btn btn-warning">
                                        <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                                    </button>
                                    <a href="{{ route('admin.survey.show', $survey->id) }}" class="btn btn-secondary">
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
                    <h6 class="section-title"><i class="bi bi-lightbulb"></i> Tips Membuat Pertanyaan</h6>
                </div>
                <div class="p-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="d-flex gap-2 align-items-start">
                                <i class="bi bi-check-circle-fill text-success mt-1"></i>
                                <div>
                                    <h6 class="fw-bold mb-0 small">Jelas & Spesifik</h6>
                                    <small class="text-muted">Buat pertanyaan yang mudah dipahami</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex gap-2 align-items-start">
                                <i class="bi bi-arrow-right-circle-fill text-primary mt-1"></i>
                                <div>
                                    <h6 class="fw-bold mb-0 small">Tujuan Jelas</h6>
                                    <small class="text-muted">Setiap pertanyaan harus ada tujuannya</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex gap-2 align-items-start">
                                <i class="bi bi-bar-chart-fill text-warning mt-1"></i>
                                <div>
                                    <h6 class="fw-bold mb-0 small">Mudah Dianalisis</h6>
                                    <small class="text-muted">Pilih tipe yang memudahkan analisis</small>
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
            margin-bottom: 0.5rem;
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
        .p-3.bg-light.rounded-3 {
            padding: 0.75rem !important;
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
    // AUTO GENERATE ORDER
    // ============================================================
    const orderInput = document.getElementById('order');
    if (orderInput) {
        orderInput.addEventListener('input', function() {
            if (this.value < 1) this.value = 1;
        });
    }

    // ============================================================
    // PREVIEW TIPE JAWABAN
    // ============================================================
    const tipeSelect = document.getElementById('tipe');
    if (tipeSelect) {
        tipeSelect.addEventListener('change', function() {
            const icons = document.querySelectorAll('.col-md-4 .p-3');
            icons.forEach((el, index) => {
                el.style.border = 'none';
                el.style.background = '#f8fafc';
            });
            
            let selectedIndex = 0;
            if (this.value === 'boolean') selectedIndex = 0;
            else if (this.value === 'rating_5') selectedIndex = 1;
            else if (this.value === 'text') selectedIndex = 2;
            
            if (icons[selectedIndex]) {
                icons[selectedIndex].style.border = '2px solid #4e9af1';
                icons[selectedIndex].style.background = '#eaf1fd';
            }
        });
        
        // Trigger untuk initial state
        tipeSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endpush
@endsection
