@extends('layouts.admin')

@section('title', 'Tambah Materi')

@section('header')
<div class="page-heading d-flex justify-content-between align-items-center">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-plus-circle"></i></span>
        <div>
            <p class="eyebrow">Manajemen</p>
            <h1 class="h3 mb-0">Tambah Materi</h1>
            <p class="text-muted mb-0">Buat materi baru untuk pelatihan</p>
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
                        <h5 class="section-title"><i class="bi bi-book text-primary"></i> Form Tambah Materi</h5>
                        <p class="text-muted small mb-0">Isi data materi dengan lengkap</p>
                    </div>
                    <span class="badge bg-info">
                        <i class="bi bi-info-circle me-1"></i>
                        Baru
                    </span>
                </div>

                <div class="p-4">
                    <form action="{{ route('admin.materi.store') }}" method="POST" enctype="multipart/form-data" id="createForm">
                        @csrf

                        <div class="row g-4">
                            <!-- Kategori & Training -->
                            <div class="col-12 col-md-6">
                                <label for="kategori_id" class="form-label fw-semibold">
                                    <i class="bi bi-tag me-1"></i> Kategori <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('kategori_id') is-invalid @enderror" 
                                        id="kategori_id" name="kategori_id" required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach($kategoris ?? [] as $kategori)
                                    <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                        {{ $kategori->nama }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('kategori_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="training_id" class="form-label fw-semibold">
                                    <i class="bi bi-journal-bookmark me-1"></i> Training <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('training_id') is-invalid @enderror" 
                                        id="training_id" name="training_id" required>
                                    <option value="">Pilih Training</option>
                                    @foreach($trainings ?? [] as $training)
                                    <option value="{{ $training->id }}" {{ old('training_id') == $training->id ? 'selected' : '' }}>
                                        {{ $training->judul }}
                                    </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Pilih training yang terkait dengan materi ini.</small>
                                @error('training_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Judul -->
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('judul') is-invalid @enderror" 
                                           id="judul" name="judul" value="{{ old('judul') }}" 
                                           placeholder="Masukkan judul materi" required>
                                    <label for="judul">
                                        <i class="bi bi-text-paragraph me-1"></i> Judul <span class="text-danger">*</span>
                                    </label>
                                </div>
                                <small class="text-muted">Judul akan digunakan untuk membuat slug URL.</small>
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
                                          id="deskripsi" name="deskripsi" rows="4" 
                                          placeholder="Deskripsikan materi ini secara lengkap...">{{ old('deskripsi') }}</textarea>
                                @error('deskripsi')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Divider File Section -->
                            <div class="col-12">
                                <hr>
                                <h6 class="fw-bold">
                                    <i class="bi bi-files text-primary me-2"></i>File Materi
                                </h6>
                                <p class="text-muted small">Anda dapat menambahkan multiple file (PDF, Video, Link) dalam satu materi.</p>
                            </div>

                            <!-- Upload File -->
                            <div class="col-12 col-md-6">
                                <label for="files" class="form-label fw-semibold">
                                    <i class="bi bi-upload me-1"></i> Upload File (PDF/Video) <span class="text-danger">*</span>
                                </label>
                                <input type="file" class="form-control @error('files.*') is-invalid @enderror" 
                                       id="files" name="files[]" multiple 
                                       accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.zip,.mp4,.avi,.mkv,.mov,.wmv,.flv,.jpg,.jpeg,.png,.gif">
                                <small class="text-muted">Maksimal 100MB per file. Bisa pilih multiple file sekaligus.</small>
                                @error('files.*')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tipe File -->
                            <div class="col-12 col-md-6">
                                <label for="file_types" class="form-label fw-semibold">
                                    <i class="bi bi-tag me-1"></i> Tipe File
                                </label>
                                <select class="form-select @error('file_types.*') is-invalid @enderror" 
                                        id="file_types" name="file_types[]">
                                    <option value="pdf">📄 PDF</option>
                                    <option value="video">🎬 Video</option>
                                    <option value="ppt">📊 Presentasi</option>
                                    <option value="image">🖼️ Gambar</option>
                                    <option value="other">📁 Lainnya</option>
                                </select>
                                <small class="text-muted">Pilih tipe untuk file yang diupload.</small>
                                @error('file_types.*')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Preview File Baru -->
                            <div class="col-12" id="filePreviewContainer" style="display: none;">
                                <div class="p-3 bg-light rounded-3">
                                    <label class="form-label fw-semibold">
                                        <i class="bi bi-eye me-1"></i> Preview File
                                    </label>
                                    <div id="filePreviewList">
                                        <!-- Dynamic preview akan muncul di sini -->
                                    </div>
                                </div>
                            </div>

                            <!-- Tambah Link -->
                            <div class="col-12">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-link-45deg me-1"></i> Tambah Link (URL)
                                </label>
                                <div id="urlInputsWrapper">
                                    <div class="row g-2 url-input-group mb-2">
                                        <div class="col-12 col-md-7">
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bi bi-link"></i></span>
                                                <input type="url" class="form-control" name="file_urls[]" 
                                                       placeholder="https://example.com/materi" value="{{ old('file_urls.0') }}">
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-5">
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bi bi-tag"></i></span>
                                                <select class="form-select" name="url_types[]">
                                                    <option value="link">🔗 Link</option>
                                                    <option value="pdf">📄 PDF</option>
                                                    <option value="video">🎬 Video</option>
                                                    <option value="ppt">📊 Presentasi</option>
                                                    <option value="image">🖼️ Gambar</option>
                                                    <option value="other">📁 Lainnya</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex gap-2 mt-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary" id="addUrlBtn">
                                        <i class="bi bi-plus-circle"></i> Tambah URL
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger" id="removeUrlBtn" style="display: none;">
                                        <i class="bi bi-trash"></i> Hapus URL
                                    </button>
                                </div>
                                <small class="text-muted">Tambahkan link ke materi eksternal (YouTube, Google Drive, dll).</small>
                            </div>

                            <!-- Divider Metadata -->
                            <div class="col-12">
                                <hr>
                                <h6 class="fw-bold">
                                    <i class="bi bi-info-circle text-secondary me-2"></i>Metadata
                                </h6>
                            </div>

                            <!-- Durasi, Max Attempt, Order -->
                            <div class="col-12 col-md-4">
                                <label for="durasi" class="form-label fw-semibold">
                                    <i class="bi bi-clock me-1"></i> Durasi (menit)
                                </label>
                                <input type="number" class="form-control @error('durasi') is-invalid @enderror" 
                                       id="durasi" name="durasi" value="{{ old('durasi') }}" 
                                       placeholder="30" min="1">
                                <small class="text-muted">Estimasi durasi membaca/menonton.</small>
                                @error('durasi')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-4">
                                <label for="max_attempt" class="form-label fw-semibold">
                                    <i class="bi bi-arrow-repeat me-1"></i> Maksimal Percobaan <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control @error('max_attempt') is-invalid @enderror" 
                                       id="max_attempt" name="max_attempt" value="{{ old('max_attempt', 3) }}" 
                                       placeholder="3" min="1" max="10" required>
                                <small class="text-muted">Jumlah maksimal percobaan quiz. Default: 3.</small>
                                @error('max_attempt')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-4">
                                <label for="order" class="form-label fw-semibold">
                                    <i class="bi bi-list-ol me-1"></i> Urutan
                                </label>
                                <input type="number" class="form-control @error('order') is-invalid @enderror" 
                                       id="order" name="order" value="{{ old('order', 0) }}" 
                                       placeholder="0" min="0">
                                <small class="text-muted">Semakin kecil angka, semakin atas tampilnya.</small>
                                @error('order')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="col-12">
                                <label for="status" class="form-label fw-semibold">
                                    <i class="bi bi-toggle-on me-1"></i> Status <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" name="status" required>
                                    <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>📝 Draft</option>
                                    <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>✅ Published</option>
                                    <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>📦 Archived</option>
                                </select>
                                <small class="text-muted">Draft: belum dipublikasikan, Published: tersedia, Archived: diarsipkan.</small>
                                @error('status')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Divider -->
                            <div class="col-12">
                                <hr class="my-2">
                            </div>

                            <!-- Submit Buttons -->
                            <div class="col-12">
                                <div class="d-flex flex-wrap gap-2">
                                    <button type="submit" class="btn btn-primary px-4" id="submitBtn">
                                        <i class="bi bi-save me-1"></i> Simpan Materi
                                    </button>
                                    <button type="reset" class="btn btn-outline-warning">
                                        <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                                    </button>
                                    <div class="ms-auto">
                                        <a href="{{ route('admin.materi.index') }}" class="btn btn-secondary">
                                            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tips Upload -->
            <div class="panel mt-4">
                <div class="panel-header">
                    <h6 class="section-title"><i class="bi bi-info-circle"></i> Tips Mengupload Materi</h6>
                </div>
                <div class="p-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="text-center p-3 border rounded-3 bg-light">
                                <i class="bi bi-file-pdf fs-1 text-danger mb-2 d-block"></i>
                                <h6>File PDF</h6>
                                <small class="text-muted">Pastikan file tidak terlalu besar untuk kemudahan akses peserta</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 border rounded-3 bg-light">
                                <i class="bi bi-play-circle fs-1 text-info mb-2 d-block"></i>
                                <h6>File Video</h6>
                                <small class="text-muted">Gunakan format MP4 untuk kompatibilitas maksimal</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 border rounded-3 bg-light">
                                <i class="bi bi-link-45deg fs-1 text-success mb-2 d-block"></i>
                                <h6>Link Eksternal</h6>
                                <small class="text-muted">Pastikan link dapat diakses oleh semua peserta</small>
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
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        color: #2563eb;
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
       BADGE
    ============================================================ */
    .badge {
        font-weight: 500;
        padding: 0.4rem 0.8rem;
        font-size: 0.75rem;
    }
    .badge.bg-info {
        background: #cfe2ff !important;
        color: #084298 !important;
    }

    /* ============================================================
       PREVIEW
    ============================================================ */
    #filePreviewList {
        max-height: 200px;
        overflow-y: auto;
    }
    #filePreviewList::-webkit-scrollbar {
        width: 6px;
    }
    #filePreviewList::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    #filePreviewList::-webkit-scrollbar-thumb {
        background: #c1c7cd;
        border-radius: 4px;
    }
    #filePreviewList::-webkit-scrollbar-thumb:hover {
        background: #a8b0b8;
    }
    #filePreviewContainer {
        transition: all 0.3s ease;
    }
    #filePreviewContainer:hover {
        background-color: #e9ecef !important;
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
    
    .btn-outline-warning {
        border-color: #ff9f43;
        color: #ff9f43;
    }
    .btn-outline-warning:hover {
        background: #ff9f43;
        border-color: #ff9f43;
        color: #fff;
    }
    
    .btn-outline-secondary {
        border-color: #e2e8f0;
        color: #4a5568;
    }
    .btn-outline-secondary:hover {
        background: #e2e8f0;
        border-color: #d5dce6;
    }
    
    .btn-outline-primary {
        border-color: #4e9af1;
        color: #4e9af1;
    }
    .btn-outline-primary:hover {
        background: #4e9af1;
        border-color: #4e9af1;
        color: #fff;
    }
    
    .btn-outline-danger {
        border-color: #dc3545;
        color: #dc3545;
    }
    .btn-outline-danger:hover {
        background: #dc3545;
        border-color: #dc3545;
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
        .row.g-2 > [class*="col-"] {
            padding-left: 0.25rem;
            padding-right: 0.25rem;
        }
        .text-center.p-3.border.rounded-3.bg-light {
            margin-bottom: 0.75rem;
        }
        .col-md-4:last-child .text-center {
            margin-bottom: 0;
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
    // ELEMENTS
    // ============================================================
    const form = document.getElementById('createForm');
    const fileInput = document.getElementById('files');
    const filePreviewContainer = document.getElementById('filePreviewContainer');
    const filePreviewList = document.getElementById('filePreviewList');
    const urlWrapper = document.getElementById('urlInputsWrapper');
    const addUrlBtn = document.getElementById('addUrlBtn');
    const removeUrlBtn = document.getElementById('removeUrlBtn');
    const submitBtn = document.getElementById('submitBtn');

    // ============================================================
    // PREVIEW MULTIPLE FILE UPLOAD
    // ============================================================
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            const files = e.target.files;
            filePreviewList.innerHTML = '';
            
            if (files.length > 0) {
                let totalSize = 0;
                let hasError = false;
                const maxSize = 100 * 1024 * 1024; // 100MB

                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    if (file.size > maxSize) {
                        alert('⚠️ File "' + file.name + '" terlalu besar. Maksimal 100MB.');
                        hasError = true;
                        break;
                    }
                    totalSize += file.size;
                    
                    const previewItem = document.createElement('div');
                    previewItem.className = 'd-flex align-items-center gap-2 p-1 border-bottom';
                    previewItem.innerHTML = `
                        <i class="bi bi-file-earmark"></i>
                        <span>${file.name}</span>
                        <span class="badge text-bg-secondary ms-auto">${(file.size / 1024 / 1024).toFixed(2)} MB</span>
                    `;
                    filePreviewList.appendChild(previewItem);
                }

                if (hasError) {
                    this.value = '';
                    filePreviewContainer.style.display = 'none';
                    return;
                }

                // Add total summary
                const summaryItem = document.createElement('div');
                summaryItem.className = 'd-flex align-items-center gap-2 p-1 mt-1 fw-bold';
                summaryItem.innerHTML = `
                    <i class="bi bi-check-circle text-success"></i>
                    <span>Total: ${files.length} file(s)</span>
                    <span class="badge text-bg-primary ms-auto">${(totalSize / 1024 / 1024).toFixed(2)} MB</span>
                `;
                filePreviewList.appendChild(summaryItem);
                
                filePreviewContainer.style.display = 'block';
                this.classList.remove('is-invalid');
            } else {
                filePreviewContainer.style.display = 'none';
            }
        });
    }

    // ============================================================
    // ADD URL INPUT
    // ============================================================
    let urlCount = 1;

    if (addUrlBtn) {
        addUrlBtn.addEventListener('click', function() {
            urlCount++;
            const newGroup = document.createElement('div');
            newGroup.className = 'row g-2 url-input-group mb-2';
            newGroup.innerHTML = `
                <div class="col-12 col-md-7">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-link"></i></span>
                        <input type="url" class="form-control" name="file_urls[]" 
                               placeholder="https://example.com/materi">
                    </div>
                </div>
                <div class="col-12 col-md-5">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-tag"></i></span>
                        <select class="form-select" name="url_types[]">
                            <option value="link">🔗 Link</option>
                            <option value="pdf">📄 PDF</option>
                            <option value="video">🎬 Video</option>
                            <option value="ppt">📊 Presentasi</option>
                            <option value="image">🖼️ Gambar</option>
                            <option value="other">📁 Lainnya</option>
                        </select>
                    </div>
                </div>
            `;
            urlWrapper.appendChild(newGroup);
            removeUrlBtn.style.display = 'inline-block';
        });
    }

    if (removeUrlBtn) {
        removeUrlBtn.addEventListener('click', function() {
            const groups = urlWrapper.querySelectorAll('.url-input-group');
            if (groups.length > 1) {
                groups[groups.length - 1].remove();
                urlCount--;
                if (groups.length - 1 <= 1) {
                    this.style.display = 'none';
                }
            }
        });
    }

    // ============================================================
    // AUTO GENERATE SLUG
    // ============================================================
    const judulInput = document.getElementById('judul');
    const slugInput = document.createElement('input');
    slugInput.type = 'hidden';
    slugInput.name = 'slug';
    slugInput.id = 'slug';
    form.appendChild(slugInput);

    if (judulInput) {
        judulInput.addEventListener('keyup', function() {
            const slug = this.value.toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');
            slugInput.value = slug;
        });
    }

    // ============================================================
    // DURASI VALIDATION
    // ============================================================
    const durasiInput = document.getElementById('durasi');
    if (durasiInput) {
        durasiInput.addEventListener('input', function() {
            if (this.value < 0) this.value = 1;
        });
    }

    // ============================================================
    // MAX ATTEMPT VALIDATION
    // ============================================================
    const maxAttemptInput = document.getElementById('max_attempt');
    if (maxAttemptInput) {
        maxAttemptInput.addEventListener('input', function() {
            if (this.value < 1) this.value = 1;
            if (this.value > 10) this.value = 10;
        });
    }

    // ============================================================
    // ORDER VALIDATION
    // ============================================================
    const orderInput = document.getElementById('order');
    if (orderInput) {
        orderInput.addEventListener('input', function() {
            if (this.value < 0) this.value = 0;
        });
    }

    // ============================================================
    // REMOVE ERROR ON INPUT
    // ============================================================
    document.querySelectorAll('input, select, textarea').forEach(el => {
        el.addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
        el.addEventListener('change', function() {
            this.classList.remove('is-invalid');
        });
    });

    // ============================================================
    // FORM SUBMIT VALIDATION
    // ============================================================
    if (form) {
        form.addEventListener('submit', function(e) {
            // Validasi training_id wajib dipilih
            const trainingSelect = document.getElementById('training_id');
            if (trainingSelect && trainingSelect.value === '') {
                e.preventDefault();
                trainingSelect.classList.add('is-invalid');
                alert('⚠️ Silakan pilih Training terlebih dahulu.');
                trainingSelect.focus();
                return false;
            }

            // Validasi max_attempt
            const maxAttempt = document.getElementById('max_attempt');
            if (maxAttempt) {
                const value = parseInt(maxAttempt.value);
                if (isNaN(value) || value < 1 || value > 10) {
                    e.preventDefault();
                    maxAttempt.classList.add('is-invalid');
                    alert('⚠️ Maksimal percobaan harus antara 1-10.');
                    maxAttempt.focus();
                    return false;
                }
            }

            const files = fileInput ? fileInput.files.length : 0;
            const urls = document.querySelectorAll('input[name="file_urls[]"]');
            let hasUrl = false;
            let hasInvalidUrl = false;

            urls.forEach(input => {
                if (input.value.trim() !== '') {
                    hasUrl = true;
                    try {
                        new URL(input.value.trim());
                    } catch (_) {
                        hasInvalidUrl = true;
                        input.classList.add('is-invalid');
                    }
                }
            });

            // Validasi: minimal harus ada file atau URL
            if (files === 0 && !hasUrl) {
                e.preventDefault();
                alert('⚠️ Minimal upload 1 file atau masukkan 1 URL.');
                if (fileInput) fileInput.focus();
                return false;
            }

            if (hasInvalidUrl) {
                e.preventDefault();
                alert('⚠️ Ada URL yang tidak valid. Pastikan format URL benar (contoh: https://example.com).');
                return false;
            }

            // Loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <span class="spinner-border spinner-border-sm me-1" role="status"></span>
                Menyimpan...
            `;

            return true;
        });
    }

    // ============================================================
    // FOCUS ON FIRST INPUT
    // ============================================================
    const firstInput = document.querySelector('input[name="judul"]');
    if (firstInput) {
        firstInput.focus();
    }

    // ============================================================
    // RESET BUTTON CONFIRMATION
    // ============================================================
    document.querySelector('button[type="reset"]')?.addEventListener('click', function(e) {
        if (!confirm('Apakah Anda yakin ingin mereset form?')) {
            e.preventDefault();
        }
    });
});
</script>
@endpush
@endsection