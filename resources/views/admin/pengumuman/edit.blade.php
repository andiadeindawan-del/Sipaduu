@extends('layouts.admin')

@section('title', 'Edit Pengumuman')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-pencil-square"></i></span>
        <div>
            <p class="eyebrow">Manajemen</p>
            <h1 class="h3 mb-0">Edit Pengumuman</h1>
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
                    <strong>Terjadi kesalahan!</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="panel">
                <div class="panel-header">
                    <h5 class="section-title"><i class="bi bi-pencil-square"></i> Form Edit Pengumuman</h5>
                    <p class="text-muted small mb-0">Perbarui data pengumuman dengan lengkap.</p>
                </div>
                <div class="p-4">
                    <form action="{{ route('admin.pengumuman.update', $pengumuman->id) }}" method="POST" id="editForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
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
                                        <option value="{{ $training->id }}" {{ old('training_id', $pengumuman->training_id) == $training->id ? 'selected' : '' }}>
                                            {{ $training->judul }}
                                            @if($training->status)
                                                <span class="text-muted">({{ $training->status_label }})</span>
                                            @endif
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('training_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Kategori -->
                            <div class="col-12 col-md-6">
                                <label for="kategori_id" class="form-label fw-semibold">
                                    Kategori
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-tag"></i></span>
                                    <select class="form-select @error('kategori_id') is-invalid @enderror" 
                                            id="kategori_id" name="kategori_id">
                                        <option value="">Pilih Kategori (Opsional)</option>
                                        @foreach($kategoris ?? [] as $kategori)
                                        <option value="{{ $kategori->id }}" {{ old('kategori_id', $pengumuman->kategori_id) == $kategori->id ? 'selected' : '' }}>
                                            {{ $kategori->nama }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('kategori_id')
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
                                           id="judul" name="judul" value="{{ old('judul', $pengumuman->judul) }}" 
                                           placeholder="Masukkan judul pengumuman" required>
                                </div>
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
                                              id="deskripsi" name="deskripsi" rows="2" 
                                              placeholder="Deskripsi singkat (opsional)">{{ old('deskripsi', $pengumuman->deskripsi) }}</textarea>
                                </div>
                                @error('deskripsi')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Konten -->
                            <div class="col-12">
                                <label for="konten" class="form-label fw-semibold">
                                    Konten <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-file-text"></i></span>
                                    <textarea class="form-control @error('konten') is-invalid @enderror" 
                                              id="konten" name="konten" rows="6" 
                                              placeholder="Isi pengumuman..." required>{{ old('konten', $pengumuman->konten) }}</textarea>
                                </div>
                                @error('konten')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Gambar Pengumuman -->
                            <div class="col-12">
                                <label for="gambar" class="form-label fw-semibold">
                                    Gambar Pengumuman
                                </label>
                                @if($pengumuman->gambar)
                                <div class="mb-2">
                                    <img src="{{ Storage::url($pengumuman->gambar) }}" alt="Gambar Pengumuman Saat Ini" class="img-fluid rounded" style="max-height: 200px;">
                                    <div class="mt-1 small text-muted">Gambar saat ini. Upload gambar baru untuk menggantinya.</div>
                                </div>
                                @endif
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-image"></i></span>
                                    <input type="file" class="form-control @error('gambar') is-invalid @enderror" 
                                           id="gambar" name="gambar" accept="image/jpeg,image/png,image/jpg" onchange="previewImage(this)">
                                </div>
                                <small class="text-muted">Opsional. Format: JPG, JPEG, PNG. Max: 2MB.</small>
                                <div id="imagePreviewContainer" class="mt-2 d-none">
                                    <img id="imagePreview" src="#" alt="Preview" class="img-fluid rounded" style="max-height: 200px;">
                                </div>
                                @error('gambar')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tanggal -->
                            <div class="col-12 col-md-6">
                                <label for="tanggal" class="form-label fw-semibold">
                                    Tanggal <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                                    <input type="date" class="form-control @error('tanggal') is-invalid @enderror" 
                                           id="tanggal" name="tanggal" value="{{ old('tanggal', $pengumuman->tanggal ? $pengumuman->tanggal->format('Y-m-d') : date('Y-m-d')) }}" required>
                                </div>
                                @error('tanggal')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tanggal Selesai -->
                            <div class="col-12 col-md-6">
                                <label for="tanggal_selesai" class="form-label fw-semibold">
                                    Tanggal Selesai
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-calendar-x"></i></span>
                                    <input type="date" class="form-control @error('tanggal_selesai') is-invalid @enderror" 
                                           id="tanggal_selesai" name="tanggal_selesai" 
                                           value="{{ old('tanggal_selesai', $pengumuman->tanggal_selesai ? $pengumuman->tanggal_selesai->format('Y-m-d') : '') }}">
                                </div>
                                <small class="text-muted">Kosongkan jika tidak ada batas waktu.</small>
                                @error('tanggal_selesai')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Target Audience -->
                            <div class="col-12 col-md-6">
                                <label for="target_audience" class="form-label fw-semibold">
                                    Target Audience <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-people"></i></span>
                                    <select class="form-select @error('target_audience') is-invalid @enderror" 
                                            id="target_audience" name="target_audience" required>
                                        <option value="all" {{ old('target_audience', $pengumuman->target_audience) == 'all' ? 'selected' : '' }}>🌍 Semua</option>
                                        <option value="peserta" {{ old('target_audience', $pengumuman->target_audience) == 'peserta' ? 'selected' : '' }}>👤 Peserta</option>
                                        <option value="trainer" {{ old('target_audience', $pengumuman->target_audience) == 'trainer' ? 'selected' : '' }}>👨‍🏫 Trainer</option>
                                        <option value="admin" {{ old('target_audience', $pengumuman->target_audience) == 'admin' ? 'selected' : '' }}>🛡️ Admin</option>
                                    </select>
                                </div>
                                @error('target_audience')
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
                                    <select class="form-select @error('status') is-invalid @enderror" 
                                            id="status" name="status" required>
                                        <option value="draft" {{ old('status', $pengumuman->status) == 'draft' ? 'selected' : '' }}>📝 Draft</option>
                                        <option value="published" {{ old('status', $pengumuman->status) == 'published' ? 'selected' : '' }}>✅ Published</option>
                                        <option value="archived" {{ old('status', $pengumuman->status) == 'archived' ? 'selected' : '' }}>📦 Archived</option>
                                    </select>
                                </div>
                                @error('status')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Is Pinned -->
                            <div class="col-12">
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

                            <!-- Submit Buttons -->
                            <div class="col-12 mt-4">
                                <hr class="my-2">
                                <div class="d-flex flex-wrap gap-2 align-items-center justify-content-between">
                                    <div>
                                        <a href="{{ route('admin.pengumuman.index') }}" class="btn btn-outline-secondary">
                                            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
                                        </a>
                                    </div>
                                    <div class="d-flex gap-2 flex-wrap">
                                        <a href="{{ route('admin.pengumuman.show', $pengumuman->id) }}" class="btn btn-outline-info">
                                            <i class="bi bi-eye me-1"></i> Detail
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-save me-1"></i> Simpan Perubahan
                                        </button>
                                        <a href="{{ route('admin.pengumuman.index') }}" class="btn btn-outline-secondary">
                                            <i class="bi bi-x-circle me-1"></i> Batal
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

@push('scripts')
<script>
window.previewImage = function(input) {
    const previewContainer = document.getElementById('imagePreviewContainer');
    const previewImage = document.getElementById('imagePreview');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            previewContainer.classList.remove('d-none');
        }
        
        reader.readAsDataURL(input.files[0]);
    } else {
        previewImage.src = '#';
        previewContainer.classList.add('d-none');
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
});
</script>
@endpush
@endsection