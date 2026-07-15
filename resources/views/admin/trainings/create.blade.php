@extends('layouts.admin')

@section('title', 'Tambah Pelatihan')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-plus-circle"></i></span>
        <div>
            <p class="eyebrow">Manajemen</p>
            <h1 class="h3 mb-0">Tambah Pelatihan</h1>
            <p class="text-muted mb-0">Buat pelatihan baru untuk peserta.</p>
        </div>
    </div>
    <div class="heading-actions">
        <a href="{{ route('admin.trainings.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="row">
        <div class="col-12 col-lg-10 mx-auto">
            <div class="panel">
                <div class="panel-header">
                    <h5 class="section-title"><i class="bi bi-journal-bookmark"></i> Form Tambah Pelatihan</h5>
                    <p class="text-muted small mb-0">Isi data pelatihan dengan lengkap.</p>
                </div>
                <div class="p-4">
                    <form action="{{ route('admin.trainings.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row g-3">
                            <!-- Kategori -->
                            <div class="col-12 col-md-6">
                                <label for="kategori_id" class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
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

                          
                            <!-- Judul -->
                            <div class="col-12">
                                <label for="judul" class="form-label fw-semibold">Judul Pelatihan <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-text-paragraph"></i></span>
                                    <input type="text" class="form-control @error('judul') is-invalid @enderror" 
                                           id="judul" name="judul" value="{{ old('judul') }}" 
                                           placeholder="Masukkan judul pelatihan" required>
                                </div>
                                @error('judul')
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
                                              placeholder="Deskripsi pelatihan">{{ old('deskripsi') }}</textarea>
                                </div>
                                @error('deskripsi')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tipe -->
                            <div class="col-12 col-md-6">
                                <label for="tipe" class="form-label fw-semibold">Tipe Pelatihan <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-laptop"></i></span>
                                    <select class="form-select @error('tipe') is-invalid @enderror" 
                                            id="tipe" name="tipe" required>
                                        <option value="">Pilih Tipe</option>
                                        <option value="online" {{ old('tipe') == 'online' ? 'selected' : '' }}>Online</option>
                                        <option value="offline" {{ old('tipe') == 'offline' ? 'selected' : '' }}>Offline</option>
                                        <option value="hybrid" {{ old('tipe') == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                                    </select>
                                </div>
                                @error('tipe')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="col-12 col-md-6">
                                <label for="status" class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-toggle-on"></i></span>
                                    <select class="form-select @error('status') is-invalid @enderror" 
                                            id="status" name="status" required>
                                        <option value="">Pilih Status</option>
                                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                                        <option value="berjalan" {{ old('status') == 'berjalan' ? 'selected' : '' }}>Berjalan</option>
                                        <option value="selesai" {{ old('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                        <option value="dibatalkan" {{ old('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                                    </select>
                                </div>
                                @error('status')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tanggal Mulai -->
                            <div class="col-12 col-md-6">
                                <label for="tanggal_mulai" class="form-label fw-semibold">Tanggal Mulai <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                                    <input type="date" class="form-control @error('tanggal_mulai') is-invalid @enderror" 
                                           id="tanggal_mulai" name="tanggal_mulai" 
                                           value="{{ old('tanggal_mulai') }}" required>
                                </div>
                                @error('tanggal_mulai')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tanggal Selesai -->
                            <div class="col-12 col-md-6">
                                <label for="tanggal_selesai" class="form-label fw-semibold">Tanggal Selesai <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                                    <input type="date" class="form-control @error('tanggal_selesai') is-invalid @enderror" 
                                           id="tanggal_selesai" name="tanggal_selesai" 
                                           value="{{ old('tanggal_selesai') }}" required>
                                </div>
                                @error('tanggal_selesai')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Lokasi -->
                            <div class="col-12 col-md-6">
                                <label for="lokasi" class="form-label fw-semibold">Lokasi</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                    <input type="text" class="form-control @error('lokasi') is-invalid @enderror" 
                                           id="lokasi" name="lokasi" value="{{ old('lokasi') }}" 
                                           placeholder="Contoh: Gedung A, Ruang 301">
                                </div>
                                @error('lokasi')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Link Meeting -->
                            <div class="col-12 col-md-6">
                                <label for="link_meeting" class="form-label fw-semibold">Link Meeting</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-link"></i></span>
                                    <input type="url" class="form-control @error('link_meeting') is-invalid @enderror" 
                                           id="link_meeting" name="link_meeting" value="{{ old('link_meeting') }}" 
                                           placeholder="https://zoom.us/meeting/...">
                                </div>
                                <small class="text-muted">Kosongkan jika tidak menggunakan link meeting.</small>
                                @error('link_meeting')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Kapasitas -->
                            <div class="col-12 col-md-6">
                                <label for="kapasitas" class="form-label fw-semibold">Kapasitas Peserta</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-people"></i></span>
                                    <input type="number" class="form-control @error('kapasitas') is-invalid @enderror" 
                                           id="kapasitas" name="kapasitas" value="{{ old('kapasitas') }}" 
                                           placeholder="50" min="1">
                                </div>
                                <small class="text-muted">Kosongkan jika tidak ada batasan.</small>
                                @error('kapasitas')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Gambar -->
                            <div class="col-12">
                                <label for="gambar" class="form-label fw-semibold">Gambar Pelatihan</label>
                                <input type="file" class="form-control @error('gambar') is-invalid @enderror" 
                                       id="gambar" name="gambar" accept=".jpg,.jpeg,.png">
                                <small class="text-muted">Max 2MB. Supported: JPG, JPEG, PNG</small>
                                @error('gambar')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Preview Gambar -->
                            <div class="col-12" id="imagePreviewContainer" style="display: none;">
                                <label class="form-label fw-semibold">Preview Gambar</label>
                                <div>
                                    <img id="imagePreview" src="#" alt="Preview" style="max-width: 200px; max-height: 200px; border-radius: 8px; border: 1px solid #ddd; padding: 4px;">
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="col-12 mt-4">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save me-1"></i> Simpan
                                    </button>
                                    <a href="{{ route('admin.trainings.index') }}" class="btn btn-outline-secondary">
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
        // Preview Gambar
        const gambarInput = document.getElementById('gambar');
        const imagePreview = document.getElementById('imagePreview');
        const imagePreviewContainer = document.getElementById('imagePreviewContainer');

        if (gambarInput) {
            gambarInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                        imagePreviewContainer.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                } else {
                    imagePreviewContainer.style.display = 'none';
                    imagePreview.src = '#';
                }
            });
        }

        // Validasi Tanggal
        const tanggalMulai = document.getElementById('tanggal_mulai');
        const tanggalSelesai = document.getElementById('tanggal_selesai');

        if (tanggalMulai && tanggalSelesai) {
            const today = new Date().toISOString().split('T')[0];
            tanggalMulai.setAttribute('min', today);

            tanggalMulai.addEventListener('change', function() {
                if (this.value) {
                    tanggalSelesai.setAttribute('min', this.value);
                    if (tanggalSelesai.value && tanggalSelesai.value < this.value) {
                        tanggalSelesai.value = '';
                    }
                }
            });

            if (tanggalMulai.value) {
                tanggalSelesai.setAttribute('min', tanggalMulai.value);
            }
        }
    });
</script>
@endpush
@endsection