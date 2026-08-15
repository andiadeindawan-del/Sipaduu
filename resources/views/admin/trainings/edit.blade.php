@extends('layouts.admin')

@section('title', 'Edit Pelatihan')

@section('header')
<div class="page-heading d-flex justify-content-between align-items-center">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-pencil-square"></i></span>
        <div>
            <p class="eyebrow">Manajemen</p>
            <h1 class="h3 mb-0">Edit Pelatihan</h1>
            <p class="text-muted mb-0">Perbarui informasi pelatihan <strong>{{ $training->judul }}</strong></p>
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
                        <h5 class="section-title"><i class="bi bi-pencil-square text-warning"></i> Form Edit Pelatihan</h5>
                        <p class="text-muted small mb-0">Perbarui data pelatihan yang sudah ada</p>
                    </div>
                    <span class="badge {{ $training->status == 'published' ? 'badge-published' : ($training->status == 'berjalan' ? 'badge-berjalan' : ($training->status == 'selesai' ? 'badge-selesai' : ($training->status == 'dibatalkan' ? 'badge-dibatalkan' : 'badge-draft'))) }}">
                        <i class="bi bi-circle-fill me-1" style="font-size: 6px;"></i>
                        {{ ucfirst($training->status) }}
                    </span>
                </div>

                <div class="p-4">
                    <form action="{{ route('admin.trainings.update', $training->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row g-4">
                            <!-- Judul -->
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('judul') is-invalid @enderror" 
                                           id="judul" name="judul" value="{{ old('judul', $training->judul) }}" 
                                           placeholder="Masukkan judul pelatihan" required>
                                    <label for="judul">
                                        <i class="bi bi-text-paragraph me-1"></i> Judul Pelatihan <span class="text-danger">*</span>
                                    </label>
                                </div>
                                @error('judul')
                                <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Kategori -->
                            <div class="col-12 col-md-6">
                                <label for="kategori_id" class="form-label fw-semibold">
                                    <i class="bi bi-tag me-1"></i> Kategori <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('kategori_id') is-invalid @enderror" 
                                        id="kategori_id" name="kategori_id" required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach($kategoris ?? [] as $kategori)
                                    <option value="{{ $kategori->id }}" {{ old('kategori_id', $training->kategori_id) == $kategori->id ? 'selected' : '' }}>
                                        {{ $kategori->nama }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('kategori_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Deskripsi -->
                            <div class="col-12">
                                <label for="deskripsi" class="form-label fw-semibold">
                                    <i class="bi bi-file-text me-1"></i> Deskripsi
                                </label>
                                <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                          id="deskripsi" name="deskripsi" rows="4" 
                                          placeholder="Deskripsikan pelatihan ini secara lengkap...">{{ old('deskripsi', $training->deskripsi) }}</textarea>
                                @error('deskripsi')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tipe & Status -->
                            <div class="col-12 col-md-6">
                                <label for="tipe" class="form-label fw-semibold">
                                    <i class="bi bi-laptop me-1"></i> Tipe Pelatihan <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('tipe') is-invalid @enderror" 
                                        id="tipe" name="tipe" required>
                                    <option value="online" {{ old('tipe', $training->tipe) == 'online' ? 'selected' : '' }}>
                                        <i class="bi bi-wifi me-1"></i> Online
                                    </option>
                                    <option value="offline" {{ old('tipe', $training->tipe) == 'offline' ? 'selected' : '' }}>
                                        <i class="bi bi-building me-1"></i> Offline
                                    </option>
                                    <option value="hybrid" {{ old('tipe', $training->tipe) == 'hybrid' ? 'selected' : '' }}>
                                        <i class="bi bi-arrow-left-right me-1"></i> Hybrid
                                    </option>
                                </select>
                                @error('tipe')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="status" class="form-label fw-semibold">
                                    <i class="bi bi-toggle-on me-1"></i> Status <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" name="status" required>
                                    <option value="draft" {{ old('status', $training->status) == 'draft' ? 'selected' : '' }}>📝 Draft</option>
                                    <option value="published" {{ old('status', $training->status) == 'published' ? 'selected' : '' }}>📢 Published</option>
                                    <option value="berjalan" {{ old('status', $training->status) == 'berjalan' ? 'selected' : '' }}>▶️ Berjalan</option>
                                    <option value="selesai" {{ old('status', $training->status) == 'selesai' ? 'selected' : '' }}>✅ Selesai</option>
                                    <option value="dibatalkan" {{ old('status', $training->status) == 'dibatalkan' ? 'selected' : '' }}>⛔ Dibatalkan</option>
                                </select>
                                @error('status')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tanggal -->
                            <div class="col-12 col-md-6">
                                <label for="tanggal_mulai" class="form-label fw-semibold">
                                    <i class="bi bi-calendar3 me-1"></i> Tanggal Mulai <span class="text-danger">*</span>
                                </label>
                                <input type="date" class="form-control @error('tanggal_mulai') is-invalid @enderror" 
                                       id="tanggal_mulai" name="tanggal_mulai" 
                                       value="{{ old('tanggal_mulai', $training->tanggal_mulai ? $training->tanggal_mulai->format('Y-m-d') : '') }}" required>
                                @error('tanggal_mulai')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="tanggal_selesai" class="form-label fw-semibold">
                                    <i class="bi bi-calendar3 me-1"></i> Tanggal Selesai <span class="text-danger">*</span>
                                </label>
                                <input type="date" class="form-control @error('tanggal_selesai') is-invalid @enderror" 
                                       id="tanggal_selesai" name="tanggal_selesai" 
                                       value="{{ old('tanggal_selesai', $training->tanggal_selesai ? $training->tanggal_selesai->format('Y-m-d') : '') }}" required>
                                @error('tanggal_selesai')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Lokasi & Link -->
                            <div class="col-12 col-md-6">
                                <label for="lokasi" class="form-label fw-semibold">
                                    <i class="bi bi-geo-alt me-1"></i> Lokasi
                                </label>
                                <input type="text" class="form-control @error('lokasi') is-invalid @enderror" 
                                       id="lokasi" name="lokasi" value="{{ old('lokasi', $training->lokasi) }}" 
                                       placeholder="Contoh: Gedung A, Ruang 301">
                                @error('lokasi')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="link_meeting" class="form-label fw-semibold">
                                    <i class="bi bi-link me-1"></i> Link Meeting
                                </label>
                                <input type="url" class="form-control @error('link_meeting') is-invalid @enderror" 
                                       id="link_meeting" name="link_meeting" value="{{ old('link_meeting', $training->link_meeting) }}" 
                                       placeholder="https://zoom.us/meeting/...">
                                <small class="text-muted">Kosongkan jika tidak menggunakan link meeting</small>
                                @error('link_meeting')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Kapasitas & Peserta -->
                            <div class="col-12 col-md-6">
                                <label for="kapasitas" class="form-label fw-semibold">
                                    <i class="bi bi-people me-1"></i> Kapasitas Peserta
                                </label>
                                <input type="number" class="form-control @error('kapasitas') is-invalid @enderror" 
                                       id="kapasitas" name="kapasitas" value="{{ old('kapasitas', $training->kapasitas) }}" 
                                       placeholder="50" min="1">
                                <small class="text-muted">Kosongkan jika tidak ada batasan</small>
                                @error('kapasitas')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-people-fill me-1"></i> Peserta Terdaftar
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person-check"></i></span>
                                    <input type="text" class="form-control" value="{{ $training->participants_count ?? 0 }} peserta" disabled>
                                </div>
                                <small class="text-muted">Jumlah peserta yang sudah mendaftar</small>
                            </div>

                            <!-- Gambar -->
                            <div class="col-12">
                                <label for="gambar" class="form-label fw-semibold">
                                    <i class="bi bi-image me-1"></i> Gambar Pelatihan
                                </label>
                                
                                @if($training->gambar)
                                <div class="mb-3">
                                    <div class="d-flex align-items-center gap-3 p-2 bg-light rounded-3">
                                        <img src="{{ asset('storage/' . $training->gambar) }}" 
                                             alt="{{ $training->judul }}" 
                                             style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px; border: 2px solid #dee2e6;">
                                        <div>
                                            <p class="fw-semibold mb-0">Gambar Saat Ini</p>
                                            <small class="text-muted">Upload gambar baru untuk mengganti</small>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <input type="file" class="form-control @error('gambar') is-invalid @enderror" 
                                       id="gambar" name="gambar" accept=".jpg,.jpeg,.png">
                                <small class="text-muted">Max 2MB. Supported: JPG, JPEG, PNG</small>
                                @error('gambar')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Preview Gambar Baru -->
                            <div class="col-12" id="imagePreviewContainer" style="display: none;">
                                <div class="p-3 bg-light rounded-3">
                                    <label class="form-label fw-semibold">
                                        <i class="bi bi-eye me-1"></i> Preview Gambar Baru
                                    </label>
                                    <div class="mt-2">
                                        <img id="imagePreview" src="#" alt="Preview" 
                                             style="max-width: 200px; max-height: 200px; border-radius: 8px; border: 2px solid #dee2e6; padding: 4px;">
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
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="bi bi-save me-1"></i> Perbarui Pelatihan
                                    </button>
                                    <a href="{{ route('admin.trainings.show', $training->id) }}" class="btn btn-outline-info">
                                        <i class="bi bi-eye me-1"></i> Lihat Detail
                                    </a>
                                    <a href="{{ route('admin.trainings.index') }}" class="btn btn-secondary">
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
                        <i class="bi bi-clock me-1"></i> Dibuat: {{ $training->created_at ? $training->created_at->format('d/m/Y H:i') : '-' }}
                    </span>
                    <span>
                        <i class="bi bi-clock-history me-1"></i> Diperbarui: {{ $training->updated_at ? $training->updated_at->format('d/m/Y H:i') : '-' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ===== Preview Gambar =====
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

        // ===== Validasi Tanggal =====
        const tanggalMulai = document.getElementById('tanggal_mulai');
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

            // Set initial min for tanggal selesai
            if (tanggalMulai.value) {
                tanggalSelesai.setAttribute('min', tanggalMulai.value);
            }
        }

        // ===== Konfirmasi Perubahan Status ke Dibatalkan =====
        const statusSelect = document.getElementById('status');
        if (statusSelect) {
            const currentStatus = '{{ $training->status }}';
            
            statusSelect.addEventListener('change', function() {
                if (this.value === 'dibatalkan' && currentStatus !== 'dibatalkan') {
                    if (!confirm('⚠️ Apakah Anda yakin ingin membatalkan pelatihan ini?\n\nTindakan ini akan mengubah status pelatihan menjadi Dibatalkan.')) {
                        this.value = currentStatus;
                    }
                }
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