@extends('layouts.pemateri')

@section('title', 'Edit Materi: ' . $materi->judul)

@section('header')
<div class="page-heading d-flex justify-content-between align-items-center">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-pencil-square"></i></span>
        <div>
            <p class="eyebrow">Aktivitas Pemateri</p>
            <h1 class="h3 mb-0">Edit Materi</h1>
            <p class="text-muted mb-0">Perbarui informasi atau file materi pembelajaran.</p>
        </div>
    </div>
    <div>
        <a href="{{ route('pemateri.materi.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">

    @if(isset($errors) && $errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="panel border-0 shadow-sm">
        <div class="panel-header">
            <h5 class="section-title"><i class="bi bi-pencil" style="color: #4a6cf7;"></i> Form Edit Materi</h5>
        </div>
        <div class="p-4">
            <form action="{{ route('pemateri.materi.update', $materi) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <!-- PILIH PELATIHAN -->
                    <div class="col-12 col-md-6">
                        <label for="training_id" class="form-label fw-semibold">
                            Pilih Pelatihan <span class="text-danger">*</span>
                        </label>
                        <select name="training_id" id="training_id" class="form-select @error('training_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Pelatihan --</option>
                            @foreach($trainings as $training)
                                <option value="{{ $training->id }}" {{ old('training_id', $materi->training_id) == $training->id ? 'selected' : '' }}>
                                    {{ $training->judul }}
                                </option>
                            @endforeach
                        </select>
                        @error('training_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- PILIH KATEGORI -->
                    <div class="col-12 col-md-6">
                        <label for="kategori_id" class="form-label fw-semibold">
                            Kategori Materi <span class="text-danger">*</span>
                        </label>
                        <select name="kategori_id" id="kategori_id" class="form-select @error('kategori_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategoris as $kategori)
                                <option value="{{ $kategori->id }}" {{ old('kategori_id', $materi->kategori_id) == $kategori->id ? 'selected' : '' }}>
                                    {{ $kategori->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('kategori_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- JUDUL MATERI -->
                    <div class="col-12">
                        <label for="judul" class="form-label fw-semibold">
                            Judul Materi <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="judul" id="judul" class="form-control @error('judul') is-invalid @enderror" 
                               value="{{ old('judul', $materi->judul) }}" required>
                        @error('judul')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- DESKRIPSI SINGKAT -->
                    <div class="col-12">
                        <label for="deskripsi" class="form-label fw-semibold">Deskripsi Singkat</label>
                        <textarea name="deskripsi" id="deskripsi" rows="3" class="form-control">{{ old('deskripsi', $materi->deskripsi) }}</textarea>
                    </div>

                    <!-- FILE YANG ADA SAAT INI -->
                    @if(!empty($materi->files))
                        <div class="col-12">
                            <label class="form-label fw-semibold">File Materi Saat Ini</label>
                            <div class="list-group">
                                @foreach($materi->files as $idx => $f)
                                    <div class="list-group-item d-flex justify-content-between align-items-center bg-light">
                                        <div>
                                            <i class="bi bi-file-earmark-check text-primary me-2"></i>
                                            <strong>{{ $f['name'] ?? 'File ' . ($idx + 1) }}</strong>
                                            <span class="badge bg-secondary ms-2">{{ strtoupper($f['type'] ?? 'file') }}</span>
                                        </div>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('pemateri.materi.download', [$materi, $idx]) }}" class="btn btn-outline-secondary">
                                                <i class="bi bi-download"></i> Unduh
                                            </a>
                                            <a href="{{ route('pemateri.materi.preview', [$materi, $idx]) }}" target="_blank" class="btn btn-outline-info">
                                                <i class="bi bi-eye"></i> Lihat
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- TAMBAH / GANTI FILE MATERI -->
                    <div class="col-12 col-md-6">
                        <label for="files" class="form-label fw-semibold">
                            Upload File Baru (Opsional)
                        </label>
                        <input type="file" name="files[]" id="files" class="form-control" multiple>
                        <small class="text-muted">Biarkan kosong jika tidak ingin mengubah/menambah file.</small>
                    </div>

                    <!-- ATAU URL MATERI / VIDEO LINK -->
                    <div class="col-12 col-md-6">
                        <label for="file_urls" class="form-label fw-semibold">
                            Tambah URL Materi / Video Baru (Opsional)
                        </label>
                        <input type="url" name="file_urls[]" id="file_urls" class="form-control" 
                               placeholder="https://youtube.com/...">
                    </div>

                    <!-- DURASI (MENIT) -->
                    <div class="col-12 col-md-4">
                        <label for="durasi" class="form-label fw-semibold">Durasi Belajar (Menit)</label>
                        <input type="number" name="durasi" id="durasi" class="form-control" value="{{ old('durasi', $materi->durasi) }}" min="1">
                    </div>

                    <!-- STATUS -->
                    <div class="col-12 col-md-4">
                        <label for="status" class="form-label fw-semibold">Status Publikasi</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="published" {{ old('status', $materi->status) == 'published' ? 'selected' : '' }}>Published</option>
                            <option value="draft" {{ old('status', $materi->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                        </select>
                    </div>

                    <!-- ORDER / URUTAN -->
                    <div class="col-12 col-md-4">
                        <label for="order" class="form-label fw-semibold">Urutan</label>
                        <input type="number" name="order" id="order" class="form-control" value="{{ old('order', $materi->order) }}" min="0">
                    </div>

                    <!-- KONTEN LENGKAP -->
                    <div class="col-12">
                        <label for="konten" class="form-label fw-semibold">Catatan / Konten Teks</label>
                        <textarea name="konten" id="konten" rows="5" class="form-control">{{ old('konten', $materi->konten) }}</textarea>
                    </div>

                    <!-- SUBMIT BUTTONS -->
                    <div class="col-12 mt-4 pt-2 border-top">
                        <button type="submit" class="btn btn-primary fw-semibold px-4">
                            <i class="bi bi-save me-1"></i> Simpan Perubahan
                        </button>
                        <a href="{{ route('pemateri.materi.index') }}" class="btn btn-outline-secondary ms-2">
                            Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
