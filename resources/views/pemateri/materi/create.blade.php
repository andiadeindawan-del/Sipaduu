@extends('layouts.pemateri')

@section('title', 'Upload Materi Baru')

@section('header')
<div class="page-heading d-flex justify-content-between align-items-center">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-upload"></i></span>
        <div>
            <p class="eyebrow">Aktivitas Pemateri</p>
            <h1 class="h3 mb-0">Upload Materi Baru</h1>
            <p class="text-muted mb-0">Tambahkan materi pembelajaran baru ke dalam pelatihan yang dipilih.</p>
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

    @if($trainings->isEmpty())
        <div class="alert alert-warning mb-4">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Belum ada pelatihan yang ditugaskan:</strong> Anda belum ditugaskan pada pelatihan apapun oleh Administrator. Anda memerlukan minimal 1 pelatihan yang ditugaskan agar dapat mengupload materi.
        </div>
    @endif

    <div class="panel border-0 shadow-sm">
        <div class="panel-header">
            <h5 class="section-title"><i class="bi bi-pencil-square" style="color: #4a6cf7;"></i> Formulir Upload Materi</h5>
        </div>
        <div class="p-4">
            <form action="{{ route('pemateri.materi.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">
                    <!-- PILIH PELATIHAN -->
                    <div class="col-12 col-md-6">
                        <label for="training_id" class="form-label fw-semibold">
                            Pilih Pelatihan <span class="text-danger">*</span>
                        </label>
                        <select name="training_id" id="training_id" class="form-select @error('training_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Pelatihan --</option>
                            @foreach($trainings as $training)
                                <option value="{{ $training->id }}" {{ old('training_id', $selectedTrainingId) == $training->id ? 'selected' : '' }}>
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
                                <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
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
                               value="{{ old('judul') }}" placeholder="Contoh: Pengenalan Manajemen Keuangan UMKM" required>
                        @error('judul')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- DESKRIPSI SINGKAT -->
                    <div class="col-12">
                        <label for="deskripsi" class="form-label fw-semibold">Deskripsi Singkat</label>
                        <textarea name="deskripsi" id="deskripsi" rows="3" class="form-control @error('deskripsi') is-invalid @enderror" 
                                  placeholder="Jelaskan ringkasan materi ini...">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- UPLOAD FILE MATERI -->
                    <div class="col-12 col-md-6">
                        <label for="files" class="form-label fw-semibold">
                            Upload File Dokumen / Video / Presentasi
                        </label>
                        <input type="file" name="files[]" id="files" class="form-control @error('files') is-invalid @enderror" multiple>
                        <small class="text-muted">Mendukung format: PDF, PPT, DOC, MP4. Maks. 100MB.</small>
                        @error('files')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- ATAU URL MATERI / VIDEO LINK -->
                    <div class="col-12 col-md-6">
                        <label for="file_urls" class="form-label fw-semibold">
                            Atau Tautan File / Video Eksternal (URL)
                        </label>
                        <input type="url" name="file_urls[]" id="file_urls" class="form-control @error('file_urls') is-invalid @enderror" 
                               placeholder="https://youtube.com/... atau https://drive.google.com/..." value="{{ old('file_urls.0') }}">
                        <small class="text-muted">Masukkan link jika materi berupa video YouTube atau Google Drive.</small>
                    </div>

                    <!-- DURASI (MENIT) -->
                    <div class="col-12 col-md-4">
                        <label for="durasi" class="form-label fw-semibold">Durasi Belajar (Menit)</label>
                        <input type="number" name="durasi" id="durasi" class="form-control" value="{{ old('durasi', 30) }}" min="1">
                    </div>

                    <!-- STATUS -->
                    <div class="col-12 col-md-4">
                        <label for="status" class="form-label fw-semibold">
                            Status Publikasi <span class="text-danger">*</span>
                        </label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="published" {{ old('status', 'published') == 'published' ? 'selected' : '' }}>Published (Dapat diakses peserta)</option>
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft (Simpan sementara)</option>
                        </select>
                    </div>

                    <!-- ORDER / URUTAN -->
                    <div class="col-12 col-md-4">
                        <label for="order" class="form-label fw-semibold">Urutan Materi</label>
                        <input type="number" name="order" id="order" class="form-control" value="{{ old('order', 1) }}" min="0">
                    </div>

                    <!-- KONTEN LENGKAP / CATATAN TAMBAHAN -->
                    <div class="col-12">
                        <label for="konten" class="form-label fw-semibold">Catatan / Konten Teks Tambahan</label>
                        <textarea name="konten" id="konten" rows="5" class="form-control" 
                                  placeholder="Tuliskan catatan penjelasan atau rangkuman materi di sini...">{{ old('konten') }}</textarea>
                    </div>

                    <!-- SUBMIT BUTTONS -->
                    <div class="col-12 mt-4 pt-2 border-top">
                        <button type="submit" class="btn btn-primary fw-semibold px-4">
                            <i class="bi bi-cloud-arrow-up me-1"></i> Simpan & Upload Materi
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
