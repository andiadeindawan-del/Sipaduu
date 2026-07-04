@extends('layouts.admin')

@section('title', 'Tambah Kategori')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-plus-circle"></i></span>
        <div>
            <p class="eyebrow">Manajemen</p>
            <h1 class="h3 mb-0">Tambah Kategori</h1>
            <p class="text-muted mb-0">Buat kategori baru untuk pelatihan atau materi.</p>
        </div>
    </div>
    <div class="heading-actions">
        <a href="{{ route('admin.kategori.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="row">
        <div class="col-12 col-lg-8 mx-auto">
            <div class="panel">
                <div class="panel-header">
                    <h5 class="section-title"><i class="bi bi-tag"></i> Form Tambah Kategori</h5>
                    <p class="text-muted small mb-0">Isi data kategori dengan lengkap.</p>
                </div>
                <div class="p-4">
                    <form action="{{ route('admin.kategori.store') }}" method="POST">
                        @csrf

                        <div class="row g-3">
                            <!-- Nama Kategori -->
                            <div class="col-12">
                                <label for="nama" class="form-label fw-semibold">Nama Kategori <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-tag"></i></span>
                                    <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                                           id="nama" name="nama" value="{{ old('nama') }}" 
                                           placeholder="Masukkan nama kategori" required>
                                </div>
                                @error('nama')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Deskripsi -->
                            <div class="col-12">
                                <label for="deskripsi" class="form-label fw-semibold">Deskripsi</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-file-text"></i></span>
                                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                              id="deskripsi" name="deskripsi" rows="4" 
                                              placeholder="Deskripsi kategori (opsional)">{{ old('deskripsi') }}</textarea>
                                </div>
                                @error('deskripsi')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Icon -->
                            <div class="col-12 col-md-6">
                                <label for="icon" class="form-label fw-semibold">Icon</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-icons"></i></span>
                                    <input type="text" class="form-control @error('icon') is-invalid @enderror" 
                                           id="icon" name="icon" value="{{ old('icon', 'bi-tag') }}" 
                                           placeholder="bi-tag">
                                </div>
                                <small class="text-muted">Contoh: bi-tag, bi-folder, bi-book. Gunakan <a href="https://icons.getbootstrap.com/" target="_blank">Bootstrap Icons</a></small>
                                @error('icon')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Warna -->
                            <div class="col-12 col-md-6">
                                <label for="warna" class="form-label fw-semibold">Warna</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-palette"></i></span>
                                    <input type="color" class="form-control @error('warna') is-invalid @enderror" 
                                           id="warna" name="warna" value="{{ old('warna', '#4e9af1') }}" 
                                           style="padding: 2px; height: 38px;">
                                </div>
                                <small class="text-muted">Pilih warna untuk kategori.</small>
                                @error('warna')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Preview -->
                            <div class="col-12">
                                <label class="form-label fw-semibold">Preview</label>
                                <div class="p-3 border rounded bg-light">
                                    <span id="previewBadge" class="badge" style="background-color: #4e9af1; color: #fff; font-size: 1rem; padding: 8px 16px;">
                                        <i class="bi bi-tag me-1"></i>
                                        Nama Kategori
                                    </span>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="col-12 mt-4">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save me-1"></i> Simpan
                                    </button>
                                    <a href="{{ route('admin.kategori.index') }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-x-circle me-1"></i> Batal
                                    </a>
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
    document.addEventListener('DOMContentLoaded', function() {
        // Preview Kategori
        const namaInput = document.getElementById('nama');
        const iconInput = document.getElementById('icon');
        const warnaInput = document.getElementById('warna');
        const previewBadge = document.getElementById('previewBadge');

        function updatePreview() {
            const nama = namaInput.value || 'Nama Kategori';
            const icon = iconInput.value || 'bi-tag';
            const warna = warnaInput.value || '#4e9af1';
            
            previewBadge.innerHTML = `<i class="bi ${icon} me-1"></i> ${nama}`;
            previewBadge.style.backgroundColor = warna;
        }

        if (namaInput) namaInput.addEventListener('input', updatePreview);
        if (iconInput) iconInput.addEventListener('input', updatePreview);
        if (warnaInput) warnaInput.addEventListener('input', updatePreview);
    });
</script>
@endpush
@endsection