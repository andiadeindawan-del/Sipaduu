@extends('layouts.admin')

@section('title', 'Edit Pengumuman')

@section('header')
<div class="page-heading d-flex justify-content-between align-items-center">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-pencil-square"></i></span>
        <div>
            <p class="eyebrow">Manajemen</p>
            <h1 class="h3 mb-0">Edit Pengumuman</h1>
            <p class="text-muted mb-0">Perbarui informasi pengumuman <strong>{{ $pengumuman->judul }}</strong></p>
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
                <i class="bi bi-exclamation-triangle me-2"></i>
                <strong>Ada kesalahan!</strong> Silakan periksa kembali formulir di bawah ini.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <div class="panel">
                <div class="panel-header">
                    <div>
                        <h5 class="section-title"><i class="bi bi-pencil-square text-warning"></i> Form Edit Pengumuman</h5>
                        <p class="text-muted small mb-0">Perbarui data pengumuman yang sudah ada</p>
                    </div>
                    <span class="badge 
                        @if($pengumuman->status == 'published') badge-published
                        @elseif($pengumuman->status == 'draft') badge-draft
                        @else badge-secondary
                        @endif
                    ">
                        <i class="bi bi-circle-fill me-1" style="font-size: 6px;"></i>
                        {{ ucfirst($pengumuman->status ?? 'Draft') }}
                    </span>
                </div>

                <div class="p-4">
                    <form action="{{ route('admin.pengumuman.update', $pengumuman->id) }}" method="POST" id="editForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row g-4">
                            <!-- Training & Kategori -->
                            <div class="col-12 col-md-6">
                                <label for="training_id" class="form-label fw-semibold">
                                    <i class="bi bi-journal-bookmark me-1"></i> Training
                                </label>
                                <select class="form-select @error('training_id') is-invalid @enderror" 
                                        id="training_id" name="training_id">
                                    <option value="">Pilih Training (Opsional)</option>
                                    @foreach($trainings ?? [] as $training)
                                    <option value="{{ $training->id }}" {{ old('training_id', $pengumuman->training_id) == $training->id ? 'selected' : '' }}>
                                        {{ $training->judul }}
                                        @if($training->status)
                                            <span class="text-muted">({{ $training->status_label }})</span>
                                        @endif
                                    </option>
                                    @endforeach
                                </select>
                                @error('training_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="kategori_id" class="form-label fw-semibold">
                                    <i class="bi bi-tag me-1"></i> Kategori
                                </label>
                                <select class="form-select @error('kategori_id') is-invalid @enderror" 
                                        id="kategori_id" name="kategori_id">
                                    <option value="">Pilih Kategori (Opsional)</option>
                                    @foreach($kategoris ?? [] as $kategori)
                                    <option value="{{ $kategori->id }}" {{ old('kategori_id', $pengumuman->kategori_id) == $kategori->id ? 'selected' : '' }}>
                                        {{ $kategori->nama }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('kategori_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Judul -->
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('judul') is-invalid @enderror" 
                                           id="judul" name="judul" value="{{ old('judul', $pengumuman->judul) }}" 
                                           placeholder="Masukkan judul pengumuman" required>
                                    <label for="judul">
                                        <i class="bi bi-text-paragraph me-1"></i> Judul <span class="text-danger">*</span>
                                    </label>
                                </div>
                                @error('judul')
                                <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Deskripsi -->
                            <div class="col-12">
                                <label for="deskripsi" class="form-label fw-semibold">
                                    <i class="bi bi-file-text me-1"></i> Deskripsi
                                </label>
                                <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                          id="deskripsi" name="deskripsi" rows="2" 
                                          placeholder="Deskripsi singkat (opsional)">{{ old('deskripsi', $pengumuman->deskripsi) }}</textarea>
                                @error('deskripsi')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Konten -->
                            <div class="col-12">
                                <label for="konten" class="form-label fw-semibold">
                                    <i class="bi bi-file-text me-1"></i> Konten <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control @error('konten') is-invalid @enderror" 
                                          id="konten" name="konten" rows="6" 
                                          placeholder="Isi pengumuman..." required>{{ old('konten', $pengumuman->konten) }}</textarea>
                                @error('konten')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Gambar -->
                            <div class="col-12">
                                <label for="gambar" class="form-label fw-semibold">
                                    <i class="bi bi-image me-1"></i> Gambar Pengumuman
                                </label>
                                @if($pengumuman->gambar)
                                <div class="mb-2 p-2 bg-light rounded-3 d-flex align-items-center gap-3">
                                    <img src="{{ Storage::url($pengumuman->gambar) }}" 
                                         alt="Gambar Pengumuman Saat Ini" 
                                         style="max-height: 80px; width: auto; border-radius: 8px; border: 1px solid #dee2e6;">
                                    <div>
                                        <p class="fw-semibold mb-0">Gambar Saat Ini</p>
                                        <small class="text-muted">Upload gambar baru untuk menggantinya.</small>
                                    </div>
                                </div>
                                @endif
                                <input type="file" class="form-control @error('gambar') is-invalid @enderror" 
                                       id="gambar" name="gambar" accept="image/jpeg,image/png,image/jpg" 
                                       onchange="previewImage(this)">
                                <small class="text-muted">Format: JPG, JPEG, PNG. Max: 2MB. Kosongkan jika tidak ingin mengubah.</small>
                                <div id="imagePreviewContainer" class="mt-2 d-none">
                                    <div class="p-2 bg-light rounded-3">
                                        <label class="form-label fw-semibold">
                                            <i class="bi bi-eye me-1"></i> Preview Gambar Baru
                                        </label>
                                        <img id="imagePreview" src="#" alt="Preview" 
                                             style="max-height: 200px; width: auto; border-radius: 8px; border: 1px solid #dee2e6;">
                                    </div>
                                </div>
                                @error('gambar')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tanggal -->
                            <div class="col-12">
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <label for="tanggal" class="form-label fw-semibold">
                                            <i class="bi bi-calendar3 me-1"></i> Tanggal Mulai <span class="text-danger">*</span>
                                        </label>
                                        <input type="date" class="form-control @error('tanggal') is-invalid @enderror" 
                                               id="tanggal" name="tanggal" 
                                               value="{{ old('tanggal', $pengumuman->tanggal ? $pengumuman->tanggal->format('Y-m-d') : date('Y-m-d')) }}" required>
                                        @error('tanggal')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label for="tanggal_selesai" class="form-label fw-semibold">
                                            <i class="bi bi-calendar-x me-1"></i> Tanggal Selesai
                                        </label>
                                        <input type="date" class="form-control @error('tanggal_selesai') is-invalid @enderror" 
                                               id="tanggal_selesai" name="tanggal_selesai" 
                                               value="{{ old('tanggal_selesai', $pengumuman->tanggal_selesai ? $pengumuman->tanggal_selesai->format('Y-m-d') : '') }}">
                                        <small class="text-muted">Kosongkan jika tidak ada batas waktu.</small>
                                        @error('tanggal_selesai')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Target Audience & Status -->
                            <div class="col-12">
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <label for="target_audience" class="form-label fw-semibold">
                                            <i class="bi bi-people me-1"></i> Target Audience <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select @error('target_audience') is-invalid @enderror" 
                                                id="target_audience" name="target_audience" required>
                                            <option value="all" {{ old('target_audience', $pengumuman->target_audience) == 'all' ? 'selected' : '' }}>🌍 Semua</option>
                                            <option value="peserta" {{ old('target_audience', $pengumuman->target_audience) == 'peserta' ? 'selected' : '' }}>👤 Peserta</option>
                                            <option value="trainer" {{ old('target_audience', $pengumuman->target_audience) == 'trainer' ? 'selected' : '' }}>👨‍🏫 Trainer</option>
                                            <option value="admin" {{ old('target_audience', $pengumuman->target_audience) == 'admin' ? 'selected' : '' }}>🛡️ Admin</option>
                                        </select>
                                        @error('target_audience')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label for="status" class="form-label fw-semibold">
                                            <i class="bi bi-toggle-on me-1"></i> Status <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select @error('status') is-invalid @enderror" 
                                                id="status" name="status" required>
                                            <option value="draft" {{ old('status', $pengumuman->status) == 'draft' ? 'selected' : '' }}>📝 Draft</option>
                                            <option value="published" {{ old('status', $pengumuman->status) == 'published' ? 'selected' : '' }}>✅ Published</option>
                                            <option value="archived" {{ old('status', $pengumuman->status) == 'archived' ? 'selected' : '' }}>📦 Archived</option>
                                        </select>
                                        @error('status')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Pin -->
                            <div class="col-12">
                                <div class="p-3 bg-light rounded-3">
                                    <div class="form-check form-switch">
                                        <input type="hidden" name="is_pinned" value="0">
                                        <input class="form-check-input @error('is_pinned') is-invalid @enderror" 
                                               type="checkbox" id="is_pinned" name="is_pinned" value="1"
                                               {{ old('is_pinned', $pengumuman->is_pinned) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="is_pinned">
                                            <i class="bi bi-pin-fill text-warning me-1"></i>
                                            Pin Pengumuman
                                        </label>
                                        <small class="d-block text-muted">Pengumuman yang di-pin akan muncul di bagian atas.</small>
                                    </div>
                                    @error('is_pinned')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Informasi Tambahan -->
                            <div class="col-12">
                                <hr>
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <div class="info-item p-2 bg-light rounded-3">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="icon-circle-sm bg-info text-white">
                                                    <i class="bi bi-clock"></i>
                                                </div>
                                                <div>
                                                    <label class="text-muted small fw-semibold text-uppercase d-block">Dibuat</label>
                                                    <p class="fw-semibold mb-0">{{ $pengumuman->created_at ? $pengumuman->created_at->format('d/m/Y H:i') : '-' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="info-item p-2 bg-light rounded-3">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="icon-circle-sm bg-warning text-white">
                                                    <i class="bi bi-clock-history"></i>
                                                </div>
                                                <div>
                                                    <label class="text-muted small fw-semibold text-uppercase d-block">Diperbarui</label>
                                                    <p class="fw-semibold mb-0">{{ $pengumuman->updated_at ? $pengumuman->updated_at->format('d/m/Y H:i') : '-' }}</p>
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
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="bi bi-save me-1"></i> Perbarui Pengumuman
                                    </button>
                                    <a href="{{ route('admin.pengumuman.show', $pengumuman->id) }}" class="btn btn-outline-info">
                                        <i class="bi bi-eye me-1"></i> Lihat Detail
                                    </a>
                                    <div class="ms-auto">
                                        <a href="{{ route('admin.pengumuman.index') }}" class="btn btn-outline-secondary">
                                            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
                                        </a>
                                    </div>
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
    
    .icon-circle-sm {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .icon-circle-sm i {
        font-size: 16px;
    }
    
    .bg-info { background-color: #0dcaf0; }
    .bg-warning { background-color: #ffc107; }
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
        .form-floating > .form-control,
        .form-floating > .form-select {
            height: calc(3rem + 2px);
            padding: 0.75rem 0.75rem;
        }
        .form-floating > label {
            padding: 0.75rem;
        }
        .row.g-3 > [class*="col-"] {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }
        .icon-circle-sm {
            width: 30px;
            height: 30px;
        }
        .icon-circle-sm i {
            font-size: 14px;
        }
        .d-flex.align-items-center.gap-3 {
            gap: 0.75rem !important;
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
// ============================================================
// PREVIEW IMAGE
// ============================================================
function previewImage(input) {
    const previewContainer = document.getElementById('imagePreviewContainer');
    const previewImage = document.getElementById('imagePreview');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            previewContainer.classList.remove('d-none');
            previewContainer.style.display = 'block';
        }
        
        reader.readAsDataURL(input.files[0]);
    } else {
        previewImage.src = '#';
        previewContainer.classList.add('d-none');
        previewContainer.style.display = 'none';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // ============================================================
    // VALIDASI TANGGAL
    // ============================================================
    const tanggalMulai = document.getElementById('tanggal');
    const tanggalSelesai = document.getElementById('tanggal_selesai');

    if (tanggalMulai && tanggalSelesai) {
        tanggalMulai.addEventListener('change', function() {
            if (this.value) {
                tanggalSelesai.setAttribute('min', this.value);
                if (tanggalSelesai.value && tanggalSelesai.value < this.value) {
                    tanggalSelesai.value = '';
                }
            }
        });

        // Set initial min for end date
        if (tanggalMulai.value) {
            tanggalSelesai.setAttribute('min', tanggalMulai.value);
        }
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