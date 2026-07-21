@extends('layouts.admin')

@section('title', 'Tambah Materi')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-plus-circle"></i></span>
        <div>
            <p class="eyebrow">Manajemen</p>
            <h1 class="h3 mb-0">Tambah Materi</h1>
            <p class="text-muted mb-0">Buat materi baru untuk pelatihan.</p>
        </div>
    </div>
    <div class="heading-actions">
        <a href="{{ route('admin.materi.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
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
                    <h5 class="section-title"><i class="bi bi-book"></i> Form Tambah Materi</h5>
                    <p class="text-muted small mb-0">Isi data materi dengan lengkap.</p>
                </div>
                <div class="p-4">
                    <form action="{{ route('admin.materi.store') }}" method="POST" enctype="multipart/form-data" id="createForm">
                        @csrf

                        <div class="row g-3">
                            <!-- Kategori -->
                            <div class="col-12 col-md-6">
                                <label for="kategori_id" class="form-label fw-semibold">
                                    Kategori <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-tag"></i></span>
                                    <select class="form-select @error('kategori_id') is-invalid @enderror" 
                                            id="kategori_id" name="kategori_id" required>
                                        <option value="">Pilih Kategori</option>
                                        @foreach($kategoris ?? [] as $kategori)
                                        <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                            {{ $kategori->nama }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('kategori_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Training - WAJIB -->
                            <div class="col-12 col-md-6">
                                <label for="training_id" class="form-label fw-semibold">
                                    Training <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-journal-bookmark"></i></span>
                                    <select class="form-select @error('training_id') is-invalid @enderror" 
                                            id="training_id" name="training_id" required>
                                        <option value="">-- Pilih Training --</option>
                                        @foreach($trainings ?? [] as $training)
                                        <option value="{{ $training->id }}" {{ old('training_id') == $training->id ? 'selected' : '' }}>
                                            {{ $training->judul }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <small class="text-muted">Pilih training yang terkait dengan materi ini. <span class="text-danger">Wajib dipilih.</span></small>
                                @error('training_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Judul -->
                            <div class="col-12">
                                <label for="judul" class="form-label fw-semibold">
                                    Judul <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-text-paragraph"></i></span>
                                    <input type="text" class="form-control @error('judul') is-invalid @enderror" 
                                           id="judul" name="judul" value="{{ old('judul') }}" 
                                           placeholder="Masukkan judul materi" required>
                                </div>
                                <small class="text-muted">Judul akan digunakan untuk membuat slug URL.</small>
                                @error('judul')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Deskripsi -->
                            <div class="col-12">
                                <label for="deskripsi" class="form-label fw-semibold">
                                    Deskripsi
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-file-text"></i></span>
                                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                              id="deskripsi" name="deskripsi" rows="3" 
                                              placeholder="Deskripsi materi (opsional)">{{ old('deskripsi') }}</textarea>
                                </div>
                                @error('deskripsi')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- ========================================================== -->
                            <!-- MULTIPLE FILES SECTION -->
                            <!-- ========================================================== -->
                            <div class="col-12">
                                <hr class="my-2">
                                <h6 class="fw-semibold text-muted">
                                    <i class="bi bi-files me-2"></i>File Materi
                                </h6>
                                <p class="text-muted small">Anda dapat menambahkan multiple file (PDF, Video, Link) dalam satu materi.</p>
                            </div>

                            <!-- ========================================================== -->
                            <!-- UPLOAD FILE (PDF/Video) -->
                            <!-- ========================================================== -->
                            <div class="col-12 col-md-6">
                                <label for="files" class="form-label fw-semibold">
                                    Upload File (PDF/Video) <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-upload"></i></span>
                                    <input type="file" class="form-control @error('files.*') is-invalid @enderror" 
                                           id="files" name="files[]" multiple 
                                           accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.zip,.mp4,.avi,.mkv,.mov,.wmv,.flv,.jpg,.jpeg,.png,.gif">
                                </div>
                                <small class="text-muted">Maksimal 100MB per file. Bisa pilih multiple file sekaligus.</small>
                                @error('files.*')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- ========================================================== -->
                            <!-- TIPE FILE UNTUK UPLOAD -->
                            <!-- ========================================================== -->
                            <div class="col-12 col-md-6">
                                <label for="file_types" class="form-label fw-semibold">
                                    Tipe File
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-tag"></i></span>
                                    <select class="form-select @error('file_types.*') is-invalid @enderror" 
                                            id="file_types" name="file_types[]">
                                        <option value="pdf">📄 PDF</option>
                                        <option value="video">🎬 Video</option>
                                        <option value="ppt">📊 Presentasi</option>
                                        <option value="image">🖼️ Gambar</option>
                                        <option value="other">📁 Lainnya</option>
                                    </select>
                                </div>
                                <small class="text-muted">Pilih tipe untuk file yang diupload.</small>
                                @error('file_types.*')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- ========================================================== -->
                            <!-- TAMBAH LINK (URL) -->
                            <!-- ========================================================== -->
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

                            <!-- Preview File Baru -->
                            <div class="col-12" id="filePreviewContainer" style="display: none;">
                                <label class="form-label fw-semibold">Preview File</label>
                                <div class="p-3 border rounded bg-light" id="filePreviewList">
                                    <!-- Dynamic preview akan muncul di sini -->
                                </div>
                            </div>

                            <!-- ========================================================== -->
                            <!-- METADATA -->
                            <!-- ========================================================== -->
                            <div class="col-12">
                                <hr class="my-2">
                                <h6 class="fw-semibold text-muted">
                                    <i class="bi bi-info-circle me-2"></i>Metadata
                                </h6>
                            </div>

                            <div class="row">
                                <!-- Durasi -->
                                <div class="col-12 col-md-4">
                                    <label for="durasi" class="form-label fw-semibold">
                                        Durasi (menit)
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-clock"></i></span>
                                        <input type="number" class="form-control @error('durasi') is-invalid @enderror" 
                                               id="durasi" name="durasi" value="{{ old('durasi') }}" 
                                               placeholder="30" min="1">
                                    </div>
                                    <small class="text-muted">Estimasi durasi membaca/menonton.</small>
                                    @error('durasi')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Max Attempt -->
                                <div class="col-12 col-md-4">
                                    <label for="max_attempt" class="form-label fw-semibold">
                                        Maksimal Percobaan <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-arrow-repeat"></i></span>
                                        <input type="number" class="form-control @error('max_attempt') is-invalid @enderror" 
                                               id="max_attempt" name="max_attempt" value="{{ old('max_attempt', 3) }}" 
                                               placeholder="3" min="1" max="10" required>
                                    </div>
                                    <small class="text-muted">Jumlah maksimal percobaan quiz. Default: 3.</small>
                                    @error('max_attempt')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Order -->
                                <div class="col-12 col-md-4">
                                    <label for="order" class="form-label fw-semibold">
                                        Urutan
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-list-ol"></i></span>
                                        <input type="number" class="form-control @error('order') is-invalid @enderror" 
                                               id="order" name="order" value="{{ old('order', 0) }}" 
                                               placeholder="0" min="0">
                                    </div>
                                    <small class="text-muted">Urutan tampil materi (semakin kecil semakin atas).</small>
                                    @error('order')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="col-12">
                                <label for="status" class="form-label fw-semibold">
                                    Status <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-toggle-on"></i></span>
                                    <select class="form-select @error('status') is-invalid @enderror" 
                                            id="status" name="status" required>
                                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>📝 Draft</option>
                                        <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>✅ Published</option>
                                        <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>📦 Archived</option>
                                    </select>
                                </div>
                                <small class="text-muted">Draft: belum dipublikasikan, Published: tersedia, Archived: diarsipkan.</small>
                                @error('status')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Submit Buttons -->
                            <div class="col-12 mt-4">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save me-1"></i> Simpan
                                    </button>
                                    <a href="{{ route('admin.materi.index') }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-x-circle me-1"></i> Batal
                                    </a>
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
                            <div class="text-center">
                                <i class="bi bi-file-pdf fs-1 text-danger mb-2 d-block"></i>
                                <h6>File PDF</h6>
                                <small class="text-muted">Pastikan file tidak terlalu besar untuk kemudahan akses peserta</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <i class="bi bi-play-circle fs-1 text-info mb-2 d-block"></i>
                                <h6>File Video</h6>
                                <small class="text-muted">Gunakan format MP4 untuk kompatibilitas maksimal</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
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

            return true;
        });
    }
});
</script>
@endpush
@endsection