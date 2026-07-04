@extends('layouts.admin')

@section('title', 'Edit Materi')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-pencil-square"></i></span>
        <div>
            <p class="eyebrow">Manajemen</p>
            <h1 class="h3 mb-0">Edit Materi</h1>
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
                    <h5 class="section-title"><i class="bi bi-pencil-square"></i> Form Edit Materi</h5>
                    <p class="text-muted small mb-0">Perbarui data materi dengan lengkap.</p>
                </div>
                <div class="p-4">
                    <form action="{{ route('admin.materi.update', $materi->id) }}" method="POST" enctype="multipart/form-data" id="editForm">
                        @csrf
                        @method('PUT')

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
                                        <option value="{{ $kategori->id }}" {{ old('kategori_id', $materi->kategori_id) == $kategori->id ? 'selected' : '' }}>
                                            {{ $kategori->nama }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('kategori_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Training -->
                            <div class="col-12 col-md-6">
                                <label for="training_id" class="form-label fw-semibold">
                                    Training
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-journal-bookmark"></i></span>
                                    <select class="form-select @error('training_id') is-invalid @enderror" 
                                            id="training_id" name="training_id">
                                        <option value="">Pilih Training (Opsional)</option>
                                        @foreach($trainings ?? [] as $training)
                                        <option value="{{ $training->id }}" {{ old('training_id', $materi->training_id) == $training->id ? 'selected' : '' }}>
                                            {{ $training->judul }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <small class="text-muted">Kosongkan jika materi tidak terkait dengan training tertentu.</small>
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
                                           id="judul" name="judul" value="{{ old('judul', $materi->judul) }}" 
                                           placeholder="Masukkan judul materi" required>
                                </div>
                                <small class="text-muted">Judul akan digunakan untuk membuat slug URL.</small>
                                @error('judul')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Slug (Readonly) -->
                            <div class="col-12">
                                <label for="slug" class="form-label fw-semibold">
                                    Slug
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-link-45deg"></i></span>
                                    <input type="text" class="form-control bg-light" 
                                           id="slug" value="{{ $materi->slug }}" readonly disabled>
                                </div>
                                <small class="text-muted">Slug akan otomatis diupdate saat judul berubah.</small>
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
                                              placeholder="Deskripsi materi (opsional)">{{ old('deskripsi', $materi->deskripsi) }}</textarea>
                                </div>
                                @error('deskripsi')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- ========================================================== -->
                            <!-- MULTIPLE FILES SECTION - TANPA DROPDOWN TIPE -->
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
                                    Upload File (PDF/Video)
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

                            <!-- ========================================================== -->
                            <!-- EXISTING FILES DISPLAY -->
                            <!-- ========================================================== -->
                            @if($materi->files && count($materi->files) > 0)
                            <div class="col-12">
                                <hr class="my-2">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-list-check me-1"></i> File Tersimpan
                                    <span class="badge bg-secondary ms-2">{{ count($materi->files) }} file</span>
                                </label>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="40">#</th>
                                                <th>Nama File</th>
                                                <th>Tipe</th>
                                                <th>Ukuran</th>
                                                <th>Aksi</th>
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
                                                <td>
                                                    <div class="d-flex gap-1">
                                                        @if(!empty($file['path']))
                                                            <a href="{{ route('admin.materi.download', ['materi' => $materi->id, 'index' => $index]) }}" 
                                                               class="btn btn-sm btn-success" target="_blank">
                                                                <i class="bi bi-download"></i>
                                                            </a>
                                                        @elseif(!empty($file['url']))
                                                            <a href="{{ $file['url'] }}" 
                                                               class="btn btn-sm btn-info" target="_blank">
                                                                <i class="bi bi-box-arrow-up-right"></i>
                                                            </a>
                                                        @endif
                                                        <button type="button" 
                                                                class="btn btn-sm btn-danger delete-file-btn"
                                                                data-index="{{ $index }}"
                                                                data-filename="{{ $file['name'] ?? basename($file['path'] ?? $file['url'] ?? '') }}">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <small class="text-muted">Klik tombol hapus untuk menghapus file yang tidak diinginkan.</small>
                                <input type="hidden" id="delete_file_indices" name="delete_file_indices" value="">
                            </div>
                            @endif

                            <!-- Preview File Baru -->
                            <div class="col-12" id="filePreviewContainer" style="display: none;">
                                <label class="form-label fw-semibold">Preview File Baru</label>
                                <div class="p-3 border rounded bg-light">
                                    <div id="filePreview">
                                        <i class="bi bi-file-earmark me-2"></i>
                                        <span id="filePreviewName"></span>
                                        <span class="badge text-bg-secondary ms-2" id="filePreviewSize"></span>
                                    </div>
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

                            <!-- Durasi -->
                            <div class="col-12 col-md-4">
                                <label for="durasi" class="form-label fw-semibold">
                                    Durasi (menit)
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-clock"></i></span>
                                    <input type="number" class="form-control @error('durasi') is-invalid @enderror" 
                                           id="durasi" name="durasi" value="{{ old('durasi', $materi->durasi) }}" 
                                           placeholder="30" min="1">
                                </div>
                                <small class="text-muted">Estimasi durasi membaca/menonton.</small>
                                @error('durasi')
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
                                           id="order" name="order" value="{{ old('order', $materi->order ?? 0) }}" 
                                           placeholder="0" min="0">
                                </div>
                                <small class="text-muted">Urutan tampil materi (semakin kecil semakin atas).</small>
                                @error('order')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="col-12 col-md-4">
                                <label for="status" class="form-label fw-semibold">
                                    Status <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-toggle-on"></i></span>
                                    <select class="form-select @error('status') is-invalid @enderror" 
                                            id="status" name="status" required>
                                        <option value="draft" {{ old('status', $materi->status) == 'draft' ? 'selected' : '' }}>📝 Draft</option>
                                        <option value="published" {{ old('status', $materi->status) == 'published' ? 'selected' : '' }}>✅ Published</option>
                                        <option value="archived" {{ old('status', $materi->status) == 'archived' ? 'selected' : '' }}>📦 Archived</option>
                                    </select>
                                </div>
                                <small class="text-muted">Draft: belum dipublikasikan, Published: tersedia, Archived: diarsipkan.</small>
                                @error('status')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Informasi Tambahan -->
                            <div class="col-12">
                                <hr class="my-2">
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <label class="text-muted small fw-semibold">Dibuat</label>
                                        <p class="fw-semibold mb-0">{{ $materi->created_at ? $materi->created_at->format('d/m/Y H:i') : '-' }}</p>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="text-muted small fw-semibold">Diperbarui</label>
                                        <p class="fw-semibold mb-0">{{ $materi->updated_at ? $materi->updated_at->format('d/m/Y H:i') : '-' }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="col-12 mt-4">
                                <div class="d-flex gap-2 flex-wrap">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save me-1"></i> Perbarui
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

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="bi bi-exclamation-triangle text-danger me-2"></i>
                    Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus materi <strong>{{ $materi->judul }}</strong>?</p>
                @if($materi->hasFile())
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Semua file materi akan ikut terhapus ({{ $materi->total_files }} file).
                </div>
                @endif
                <p class="text-muted small">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin.materi.destroy', $materi->id) }}" method="POST" class="d-inline">
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
                if (!confirm('Apakah Anda yakin ingin mengarsipkan materi ini?')) {
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

            return true;
        });
    }
});
</script>
@endpush
@endsection