@extends('layouts.admin')

@section('title', 'Edit Kategori')

@section('header')
<div class="page-heading d-flex justify-content-between align-items-center">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-pencil-square"></i></span>
        <div>
            <p class="eyebrow">Manajemen</p>
            <h1 class="h3 mb-0">Edit Kategori</h1>
            <p class="text-muted mb-0">Perbarui informasi kategori <strong>{{ $kategori->nama }}</strong></p>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="row">
        <div class="col-12 col-lg-8 mx-auto">
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
                        <h5 class="section-title"><i class="bi bi-pencil-square text-warning"></i> Form Edit Kategori</h5>
                        <p class="text-muted small mb-0">Perbarui data kategori yang sudah ada</p>
                    </div>
                    <span class="badge" style="background-color: {{ $kategori->warna ?? '#6c757d' }}; color: #fff; padding: 8px 16px;">
                        <i class="bi {{ $kategori->icon ?? 'bi-tag' }} me-1"></i>
                        {{ $kategori->nama }}
                    </span>
                </div>

                <div class="p-4">
                    <form action="{{ route('admin.kategori.update', $kategori->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-4">
                            <!-- Nama Kategori -->
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                                           id="nama" name="nama" value="{{ old('nama', $kategori->nama) }}" 
                                           placeholder="Masukkan nama kategori" required>
                                    <label for="nama">
                                        <i class="bi bi-tag me-1"></i> Nama Kategori <span class="text-danger">*</span>
                                    </label>
                                </div>
                                @error('nama')
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
                                          placeholder="Deskripsikan kategori ini secara lengkap...">{{ old('deskripsi', $kategori->deskripsi) }}</textarea>
                                @error('deskripsi')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Icon -->
                            <div class="col-12 col-md-6">
                                <label for="icon" class="form-label fw-semibold">
                                    <i class="bi bi-icons me-1"></i> Icon
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text" id="iconPreview">
                                        <i class="bi {{ $kategori->icon ?? 'bi-tag' }}"></i>
                                    </span>
                                    <input type="text" class="form-control @error('icon') is-invalid @enderror" 
                                           id="icon" name="icon" value="{{ old('icon', $kategori->icon ?? 'bi-tag') }}" 
                                           placeholder="bi-tag">
                                </div>
                                <small class="text-muted">Contoh: bi-tag, bi-folder, bi-book. Gunakan <a href="https://icons.getbootstrap.com/" target="_blank">Bootstrap Icons</a></small>
                                @error('icon')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Warna -->
                            <div class="col-12 col-md-6">
                                <label for="warna" class="form-label fw-semibold">
                                    <i class="bi bi-palette me-1"></i> Warna
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text" id="colorPreview" style="background-color: {{ old('warna', $kategori->warna ?? '#6c757d') }}; width: 40px;"></span>
                                    <input type="color" class="form-control form-control-color @error('warna') is-invalid @enderror" 
                                           id="warna" name="warna" value="{{ old('warna', $kategori->warna ?? '#6c757d') }}" 
                                           style="max-width: 80px; padding: 0;">
                                    <input type="text" class="form-control @error('warna') is-invalid @enderror" 
                                           id="warnaText" value="{{ old('warna', $kategori->warna ?? '#6c757d') }}" 
                                           placeholder="#6c757d" style="flex: 1;">
                                </div>
                                <small class="text-muted">Pilih warna untuk badge kategori</small>
                                @error('warna')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Preview -->
                            <div class="col-12">
                                <hr>
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-eye me-1"></i> Preview
                                </label>
                                <div class="p-4 border rounded-3 bg-light text-center">
                                    <span id="previewBadge" class="badge" style="background-color: {{ old('warna', $kategori->warna ?? '#6c757d') }}; color: #fff; font-size: 1.1rem; padding: 10px 24px;">
                                        <i class="bi {{ $kategori->icon ?? 'bi-tag' }} me-2"></i>
                                        {{ $kategori->nama }}
                                    </span>
                                    <p class="text-muted small mt-2 mb-0">Preview tampilan kategori</p>
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
                                        <i class="bi bi-save me-1"></i> Perbarui Kategori
                                    </button>
                                    <a href="{{ route('admin.kategori.show', $kategori->id) }}" class="btn btn-outline-info">
                                        <i class="bi bi-eye me-1"></i> Lihat Detail
                                    </a>
                                    <a href="{{ route('admin.kategori.index') }}" class="btn btn-secondary">
                                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Informasi Tambahan -->
            <div class="mt-4 text-center">
                <div class="d-flex justify-content-center gap-4 text-muted small">
                    <span>
                        <i class="bi bi-clock me-1"></i> Dibuat: {{ $kategori->created_at ? $kategori->created_at->format('d/m/Y H:i') : '-' }}
                    </span>
                    <span>
                        <i class="bi bi-clock-history me-1"></i> Diperbarui: {{ $kategori->updated_at ? $kategori->updated_at->format('d/m/Y H:i') : '-' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ===== Preview Kategori =====
        const namaInput = document.getElementById('nama');
        const iconInput = document.getElementById('icon');
        const warnaInput = document.getElementById('warna');
        const warnaText = document.getElementById('warnaText');
        const previewBadge = document.getElementById('previewBadge');
        const iconPreview = document.getElementById('iconPreview');
        const colorPreview = document.getElementById('colorPreview');

        function updatePreview() {
            const nama = namaInput.value || 'Nama Kategori';
            const icon = iconInput.value || 'bi-tag';
            const warna = warnaInput.value || '#6c757d';
            
            previewBadge.innerHTML = `<i class="bi ${icon} me-2"></i> ${nama}`;
            previewBadge.style.backgroundColor = warna;
            
            // Update icon preview
            if (iconPreview) {
                iconPreview.innerHTML = `<i class="bi ${icon}"></i>`;
            }
            
            // Update color preview
            if (colorPreview) {
                colorPreview.style.backgroundColor = warna;
            }
        }

        if (namaInput) namaInput.addEventListener('input', updatePreview);
        if (iconInput) iconInput.addEventListener('input', updatePreview);
        
        if (warnaInput) {
            warnaInput.addEventListener('input', function() {
                if (warnaText) warnaText.value = this.value;
                updatePreview();
            });
        }
        
        if (warnaText) {
            warnaText.addEventListener('input', function() {
                if (warnaInput) warnaInput.value = this.value;
                updatePreview();
            });
        }

        // ===== Auto close alerts =====
        setTimeout(function() {
            document.querySelectorAll('.alert').forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    });
</script>
@endpush
@endsection