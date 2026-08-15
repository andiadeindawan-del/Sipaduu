@extends('layouts.admin')

@section('title', 'Edit Materi')

@section('header')
<div class="page-heading d-flex justify-content-between align-items-center">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-pencil-square"></i></span>
        <div>
            <p class="eyebrow">Manajemen</p>
            <h1 class="h3 mb-0">Edit Materi</h1>
            <p class="text-muted mb-0">Perbarui informasi materi <strong>{{ $materi->judul }}</strong></p>
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
                        <h5 class="section-title"><i class="bi bi-pencil-square text-warning"></i> Form Edit Materi</h5>
                        <p class="text-muted small mb-0">Perbarui data materi yang sudah ada</p>
                    </div>
                    <span class="badge {{ $materi->status_badge ?? 'badge-draft' }}">
                        <i class="bi bi-circle-fill me-1" style="font-size: 6px;"></i>
                        {{ $materi->status_label ?? ucfirst($materi->status ?? 'Draft') }}
                    </span>
                </div>

                <div class="p-4">
                    <form action="{{ route('admin.materi.update', $materi->id) }}" method="POST" enctype="multipart/form-data" id="editForm">
                        @csrf
                        @method('PUT')

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
                                    <option value="{{ $kategori->id }}" {{ old('kategori_id', $materi->kategori_id) == $kategori->id ? 'selected' : '' }}>
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
                                    <i class="bi bi-journal-bookmark me-1"></i> Training
                                </label>
                                <select class="form-select @error('training_id') is-invalid @enderror" 
                                        id="training_id" name="training_id">
                                    <option value="">Pilih Training (Opsional)</option>
                                    @foreach($trainings ?? [] as $training)
                                    <option value="{{ $training->id }}" {{ old('training_id', $materi->training_id) == $training->id ? 'selected' : '' }}>
                                        {{ $training->judul }}
                                    </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Kosongkan jika materi tidak terkait dengan training tertentu.</small>
                                @error('training_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Judul -->
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('judul') is-invalid @enderror" 
                                           id="judul" name="judul" value="{{ old('judul', $materi->judul) }}" 
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

                            <!-- Slug (Readonly) -->
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control bg-light" 
                                           id="slug" value="{{ $materi->slug }}" readonly disabled>
                                    <label for="slug">
                                        <i class="bi bi-link-45deg me-1"></i> Slug
                                    </label>
                                </div>
                                <small class="text-muted">Slug akan otomatis diupdate saat judul berubah.</small>
                            </div>

                            <!-- Deskripsi -->
                            <div class="col-12">
                                <label for="deskripsi" class="form-label fw-semibold">
                                    <i class="bi bi-file-text me-1"></i> Deskripsi
                                </label>
                                <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                          id="deskripsi" name="deskripsi" rows="4" 
                                          placeholder="Deskripsikan materi ini secara lengkap...">{{ old('deskripsi', $materi->deskripsi) }}</textarea>
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
                                    <i class="bi bi-upload me-1"></i> Upload File (PDF/Video)
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
                                        <i class="bi bi-eye me-1"></i> Preview File Baru
                                    </label>
                                    <div id="filePreview">
                                        <i class="bi bi-file-earmark me-2"></i>
                                        <span id="filePreviewName"></span>
                                        <span class="badge text-bg-secondary ms-2" id="filePreviewSize"></span>
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

                            <!-- Existing Files -->
                            @if($materi->files && count($materi->files) > 0)
                            <div class="col-12">
                                <hr>
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-list-check me-1"></i> File Tersimpan
                                    <span class="badge bg-secondary ms-2">{{ count($materi->files) }} file</span>
                                </label>
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="40">#</th>
                                                <th>Nama File</th>
                                                <th>Tipe</th>
                                                <th>Ukuran</th>
                                                <th class="text-end">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($materi->files as $index => $file)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <i class="{{ $materi->getFileIcon($file['type'] ?? 'other') }} me-2"></i>
                                                    {{ $file['name'] ?? basename($file['path'] ?? $file['url'] ?? '') }}
                                                    @if($file['is_main'] ?? false)
                                                        <span class="badge bg-primary ms-1">Utama</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge bg-info">
                                                        {{ $materi->getFileTypeLabel($file['type'] ?? 'other') }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if(isset($file['size']) && $file['size'])
                                                        {{ number_format($file['size'] / 1024 / 1024, 2) }} MB
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    <div class="d-flex gap-1 justify-content-end">
                                                        @if(!empty($file['path']))
                                                            <a href="{{ route('admin.materi.download', ['materi' => $materi->id, 'index' => $index]) }}" 
                                                               class="btn btn-sm btn-success" target="_blank" title="Download">
                                                                <i class="bi bi-download"></i>
                                                            </a>
                                                        @elseif(!empty($file['url']))
                                                            <a href="{{ $file['url'] }}" 
                                                               class="btn btn-sm btn-info" target="_blank" title="Buka Link">
                                                                <i class="bi bi-box-arrow-up-right"></i>
                                                            </a>
                                                        @endif
                                                        <button type="button" 
                                                                class="btn btn-sm btn-danger delete-file-btn"
                                                                data-index="{{ $index }}"
                                                                data-filename="{{ $file['name'] ?? basename($file['path'] ?? $file['url'] ?? '') }}"
                                                                title="Hapus File">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <input type="hidden" id="delete_file_indices" name="delete_file_indices" value="">
                            </div>
                            @endif

                            <!-- Divider Metadata -->
                            <div class="col-12">
                                <hr>
                                <h6 class="fw-bold">
                                    <i class="bi bi-info-circle text-secondary me-2"></i>Metadata
                                </h6>
                            </div>

                            <!-- Durasi, Order, Status -->
                            <div class="col-12 col-md-4">
                                <label for="durasi" class="form-label fw-semibold">
                                    <i class="bi bi-clock me-1"></i> Durasi (menit)
                                </label>
                                <input type="number" class="form-control @error('durasi') is-invalid @enderror" 
                                       id="durasi" name="durasi" value="{{ old('durasi', $materi->durasi) }}" 
                                       placeholder="30" min="1">
                                <small class="text-muted">Estimasi durasi membaca/menonton.</small>
                                @error('durasi')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-4">
                                <label for="order" class="form-label fw-semibold">
                                    <i class="bi bi-list-ol me-1"></i> Urutan
                                </label>
                                <input type="number" class="form-control @error('order') is-invalid @enderror" 
                                       id="order" name="order" value="{{ old('order', $materi->order ?? 0) }}" 
                                       placeholder="0" min="0">
                                <small class="text-muted">Semakin kecil angka, semakin atas tampilnya.</small>
                                @error('order')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-4">
                                <label for="status" class="form-label fw-semibold">
                                    <i class="bi bi-toggle-on me-1"></i> Status <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" name="status" required>
                                    <option value="draft" {{ old('status', $materi->status) == 'draft' ? 'selected' : '' }}>📝 Draft</option>
                                    <option value="published" {{ old('status', $materi->status) == 'published' ? 'selected' : '' }}>✅ Published</option>
                                    <option value="archived" {{ old('status', $materi->status) == 'archived' ? 'selected' : '' }}>📦 Archived</option>
                                </select>
                                @error('status')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
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
                                                    <p class="fw-semibold mb-0">{{ $materi->created_at ? $materi->created_at->format('d/m/Y H:i') : '-' }}</p>
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
                                                    <p class="fw-semibold mb-0">{{ $materi->updated_at ? $materi->updated_at->format('d/m/Y H:i') : '-' }}</p>
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
                                    <button type="submit" class="btn btn-primary px-4" id="submitBtn">
                                        <i class="bi bi-save me-1"></i> Perbarui Materi
                                    </button>
                                    <a href="{{ route('admin.materi.show', $materi->id) }}" class="btn btn-outline-info">
                                        <i class="bi bi-eye me-1"></i> Lihat Detail
                                    </a>
                                    <div class="ms-auto">
                                        <a href="{{ route('admin.materi.index') }}" class="btn btn-outline-secondary">
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

<!-- Confirm Delete File Modal -->
<div class="modal fade" id="confirmDeleteFileModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">
                    <i class="bi bi-exclamation-triangle text-warning me-2"></i>
                    Konfirmasi Hapus File
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Yakin ingin menghapus file <strong id="deleteFileName"></strong>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger btn-sm" id="confirmDeleteFile">
                    <i class="bi bi-trash me-1"></i> Hapus
                </button>
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
        background: #cfe2ff !important;
        color: #084298 !important;
    }

    /* ============================================================
       TABLE
    ============================================================ */
    .table th {
        font-weight: 600;
        color: #6c757d;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #f0f0f0;
        padding: 0.5rem 0.75rem;
        background: #fafbfc;
    }
    .table td {
        font-size: 0.85rem;
        padding: 0.5rem 0.75rem;
        vertical-align: middle;
    }
    .table tbody tr {
        transition: background 0.2s ease;
    }
    .table tbody tr:hover {
        background: #f8fafc;
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
        .row.g-2 > [class*="col-"] {
            padding-left: 0.25rem;
            padding-right: 0.25rem;
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
    const form = document.getElementById('editForm');
    const fileInput = document.getElementById('files');
    const filePreviewContainer = document.getElementById('filePreviewContainer');
    const filePreviewName = document.getElementById('filePreviewName');
    const filePreviewSize = document.getElementById('filePreviewSize');
    const deleteFileIndicesInput = document.getElementById('delete_file_indices');
    const confirmDeleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteFileModal'));

    // ============================================================
    // DATA
    // ============================================================
    let deleteIndices = [];
    let currentDeleteIndex = null;

    // ============================================================
    // PREVIEW MULTIPLE FILE UPLOAD
    // ============================================================
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            const files = e.target.files;
            if (files.length > 0) {
                let previewHtml = '';
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
                    previewHtml += '<div class="d-flex align-items-center gap-2 p-1 border-bottom">';
                    previewHtml += '<i class="bi bi-file-earmark"></i>';
                    previewHtml += '<span>' + file.name + '</span>';
                    previewHtml += '<span class="badge text-bg-secondary ms-auto">' + (file.size / 1024 / 1024).toFixed(2) + ' MB</span>';
                    previewHtml += '</div>';
                }

                if (hasError) {
                    this.value = '';
                    filePreviewContainer.style.display = 'none';
                    return;
                }

                filePreviewName.textContent = files.length + ' file(s)';
                filePreviewSize.textContent = (totalSize / 1024 / 1024).toFixed(2) + ' MB total';
                filePreviewContainer.style.display = 'block';
                this.classList.remove('is-invalid');
            } else {
                filePreviewContainer.style.display = 'none';
            }
        });
    }

    // ============================================================
    // AUTO GENERATE SLUG
    // ============================================================
    const judulInput = document.getElementById('judul');
    const slugInput = document.getElementById('slug');
    if (judulInput && slugInput) {
        judulInput.addEventListener('keyup', function() {
            const slug = this.value.toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');
            slugInput.value = slug;
        });
    }

    // ============================================================
    // DELETE FILE HANDLER
    // ============================================================
    document.querySelectorAll('.delete-file-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const index = this.dataset.index;
            const filename = this.dataset.filename;
            currentDeleteIndex = index;
            document.getElementById('deleteFileName').textContent = filename;
            confirmDeleteModal.show();
        });
    });

    document.getElementById('confirmDeleteFile').addEventListener('click', function() {
        if (currentDeleteIndex !== null) {
            deleteIndices.push(parseInt(currentDeleteIndex));
            deleteFileIndicesInput.value = deleteIndices.join(',');
            
            // Hide the row
            document.querySelectorAll('.delete-file-btn').forEach(btn => {
                if (parseInt(btn.dataset.index) === currentDeleteIndex) {
                    btn.closest('tr').style.display = 'none';
                }
            });
            
            confirmDeleteModal.hide();
            currentDeleteIndex = null;
        }
    });

    // ============================================================
    // ADD URL INPUT
    // ============================================================
    let urlCount = 1;
    const addUrlBtn = document.getElementById('addUrlBtn');
    const removeUrlBtn = document.getElementById('removeUrlBtn');
    const urlWrapper = document.getElementById('urlInputsWrapper');

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
    // DURASI & ORDER VALIDATION
    // ============================================================
    const durasiInput = document.getElementById('durasi');
    if (durasiInput) {
        durasiInput.addEventListener('input', function() {
            if (this.value < 0) this.value = 1;
        });
    }

    const orderInput = document.getElementById('order');
    if (orderInput) {
        orderInput.addEventListener('input', function() {
            if (this.value < 0) this.value = 0;
        });
    }

    // ============================================================
    // STATUS CHANGE CONFIRMATION
    // ============================================================
    const statusSelect = document.getElementById('status');
    const currentStatus = '{{ $materi->status }}';
    if (statusSelect) {
        statusSelect.addEventListener('change', function() {
            if (this.value === 'archived' && currentStatus !== 'archived') {
                if (!confirm('⚠️ Apakah Anda yakin ingin mengarsipkan materi ini?')) {
                    this.value = currentStatus;
                }
            }
        });
    }

    // ============================================================
    // FORM SUBMIT VALIDATION
    // ============================================================
    if (form) {
        form.addEventListener('submit', function(e) {
            // ============================================================
            // KONFIRMASI HAPUS FILE
            // ============================================================
            if (deleteIndices.length > 0) {
                if (!confirm('Anda akan menghapus ' + deleteIndices.length + ' file. Lanjutkan?')) {
                    e.preventDefault();
                    return false;
                }
            }

            // ============================================================
            // VALIDASI URL
            // ============================================================
            const urlInputs = document.querySelectorAll('input[name="file_urls[]"]');
            let hasInvalidUrl = false;
            urlInputs.forEach(input => {
                if (input.value.trim() !== '') {
                    try {
                        new URL(input.value.trim());
                    } catch (_) {
                        hasInvalidUrl = true;
                        input.classList.add('is-invalid');
                    }
                }
            });

            if (hasInvalidUrl) {
                e.preventDefault();
                alert('⚠️ Ada URL yang tidak valid. Pastikan format URL benar (contoh: https://example.com).');
                return false;
            }

            // ============================================================
            // LOADING STATE
            // ============================================================
            const submitBtn = document.getElementById('submitBtn');
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
        firstInput.select();
    }
});
</script>
@endpush
@endsection