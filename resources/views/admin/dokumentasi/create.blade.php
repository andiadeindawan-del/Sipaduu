@extends('layouts.admin')

@section('title', 'Tambah Dokumentasi')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-images"></i></span>
        <div>
            <p class="eyebrow mb-1">Management</p>
            <h1 class="h3 mb-0">Tambah Dokumentasi</h1>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <!-- Alert Messages -->
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-circle-fill me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-circle-fill me-2"></i>
        Terdapat kesalahan pada form. Silakan periksa kembali.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Form -->
    <div class="panel">
        <div class="panel-header">
            <div>
                <h5 class="section-title"><i class="bi bi-pencil-square"></i> Form Dokumentasi</h5>
                <p class="text-muted small mb-0">Isi form di bawah ini untuk menambahkan dokumentasi baru</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.dokumentasi.index') }}" class="btn btn-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
        <div class="panel-body p-4">
            <form action="{{ route('admin.dokumentasi.store') }}" method="POST" id="createForm">
                @csrf
                
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold">Pelatihan <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-journal-bookmark"></i></span>
                            <select name="training_id" class="form-select @error('training_id') is-invalid @enderror" required>
                                <option value="">Pilih Pelatihan</option>
                                @if(isset($trainings) && $trainings->count() > 0)
                                    @foreach($trainings as $training)
                                        <option value="{{ $training->id }}" {{ old('training_id') == $training->id ? 'selected' : '' }}>
                                            {{ $training->judul }}
                                        </option>
                                    @endforeach
                                @else
                                    <option value="" disabled>Tidak ada pelatihan</option>
                                @endif
                            </select>
                        </div>
                        @error('training_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Judul <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-text-paragraph"></i></span>
                            <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" 
                                   value="{{ old('judul') }}" placeholder="Masukkan judul dokumentasi" required>
                        </div>
                        @error('judul')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Link <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-link-45deg"></i></span>
                            <input type="url" name="link" class="form-control @error('link') is-invalid @enderror" 
                                   value="{{ old('link') }}" placeholder="https://example.com/dokumentasi" required>
                        </div>
                        <small class="text-muted">Masukkan URL lengkap dokumentasi (Google Drive, YouTube, dll).</small>
                        @error('link')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Deskripsi</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-file-text"></i></span>
                            <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" 
                                      rows="3" placeholder="Deskripsi dokumentasi (opsional)">{{ old('deskripsi') }}</textarea>
                        </div>
                        @error('deskripsi')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                    <a href="{{ route('admin.dokumentasi.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-1"></i> Batal
                    </a>
                    <button type="reset" class="btn btn-light">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
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
    
    .input-group .form-control, 
    .input-group .form-select {
        border-radius: 0 0.5rem 0.5rem 0;
    }
    
    .input-group .input-group-text:first-child {
        border-radius: 0.5rem 0 0 0.5rem;
    }
    
    textarea.form-control {
        resize: vertical;
        min-height: 80px;
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
    
    .btn-secondary {
        background: #e2e8f0;
        border-color: #e2e8f0;
        color: #4a5568;
    }
    .btn-secondary:hover {
        background: #d5dce6;
        border-color: #d5dce6;
    }
    
    .btn-light {
        background: #f7fafc;
        border-color: #e2e8f0;
        color: #4a5568;
    }
    .btn-light:hover {
        background: #edf2f7;
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

    // Focus on first input
    const firstInput = document.querySelector('input[name="judul"]');
    if (firstInput) {
        firstInput.focus();
    }

    // Reset button confirmation
    document.querySelector('button[type="reset"]')?.addEventListener('click', function(e) {
        if (!confirm('Apakah Anda yakin ingin mereset form?')) {
            e.preventDefault();
        }
    });
});
</script>
@endpush
@endsection