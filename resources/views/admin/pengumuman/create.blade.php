@extends('layouts.admin')

@section('title', 'Tambah Pengumuman')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-megaphone"></i></span>
        <div>
            <p class="eyebrow">Manajemen</p>
            <h1 class="h3 mb-0">Tambah Pengumuman</h1>
        </div>
    </div>
    <div class="heading-actions">
        <a href="{{ route('admin.pengumuman.index') }}" class="btn btn-outline-secondary btn-sm">
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
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <div class="panel">
                <div class="panel-header">
                    <h5 class="section-title"><i class="bi bi-megaphone"></i> Form Tambah Pengumuman</h5>
                    <p class="text-muted small mb-0">Isi data pengumuman dengan lengkap.</p>
                </div>
                <div class="p-4">
                    <form action="{{ route('admin.pengumuman.store') }}" method="POST" id="pengumumanForm">
                        @csrf

                        <div class="row g-3">
                            <!-- Pilih Pelatihan -->
                            <div class="col-12 col-md-6">
                                <label for="training_id" class="form-label fw-semibold">
                                    Pelatihan
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-journal-bookmark"></i></span>
                                    <select class="form-select @error('training_id') is-invalid @enderror" 
                                            id="training_id" name="training_id">
                                        <option value="">Pilih Pelatihan (Opsional)</option>
                                        @foreach($trainings ?? [] as $training)
                                        <option value="{{ $training->id }}" {{ old('training_id') == $training->id ? 'selected' : '' }}>
                                            {{ $training->judul }}
                                            @if($training->tanggal_mulai)
                                                ({{ $training->tanggal_mulai->format('d/m/Y') }} - {{ $training->tanggal_selesai ? $training->tanggal_selesai->format('d/m/Y') : '...' }})
                                            @endif
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <small class="text-muted">Kosongkan jika pengumuman untuk semua pelatihan.</small>
                                @error('training_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Pilih Kategori -->
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
                                        <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                            {{ $kategori->nama }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <small class="text-muted">Kosongkan jika tidak ada kategori.</small>
                                @error('kategori_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Judul -->
                            <div class="col-12">
                                <label for="judul" class="form-label fw-semibold">
                                    Judul Pengumuman <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-text-paragraph"></i></span>
                                    <input type="text" class="form-control @error('judul') is-invalid @enderror" 
                                           id="judul" name="judul" value="{{ old('judul') }}" 
                                           placeholder="Masukkan judul pengumuman" required>
                                </div>
                                @error('judul')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Deskripsi Singkat -->
                            <div class="col-12">
                                <label for="deskripsi" class="form-label fw-semibold">
                                    Deskripsi Singkat
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-file-text"></i></span>
                                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                              id="deskripsi" name="deskripsi" rows="2" 
                                              placeholder="Deskripsi singkat pengumuman (opsional)">{{ old('deskripsi') }}</textarea>
                                </div>
                                <small class="text-muted">Ringkasan singkat tentang pengumuman.</small>
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
                                    <span class="input-group-text"><i class="bi bi-file-richtext"></i></span>
                                    <textarea class="form-control @error('konten') is-invalid @enderror" 
                                              id="konten" name="konten" rows="6" 
                                              placeholder="Masukkan konten pengumuman lengkap..." required>{{ old('konten') }}</textarea>
                                </div>
                                <small class="text-muted">Konten lengkap pengumuman. Bisa menggunakan HTML.</small>
                                @error('konten')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tanggal -->
                            <div class="col-12 col-md-6">
                                <label for="tanggal" class="form-label fw-semibold">
                                    Tanggal Publikasi <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                                    <input type="date" class="form-control @error('tanggal') is-invalid @enderror" 
                                           id="tanggal" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" 
                                           required>
                                </div>
                                <small class="text-muted">Tanggal pengumuman akan dipublikasikan.</small>
                                @error('tanggal')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tanggal Selesai -->
                            <div class="col-12 col-md-6">
                                <label for="tanggal_selesai" class="form-label fw-semibold">
                                    Tanggal Berakhir
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-calendar-x"></i></span>
                                    <input type="date" class="form-control @error('tanggal_selesai') is-invalid @enderror" 
                                           id="tanggal_selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}">
                                </div>
                                <small class="text-muted">Tanggal pengumuman akan berakhir (opsional).</small>
                                @error('tanggal_selesai')
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

                            <!-- Target Audience -->
                            <div class="col-12 col-md-6">
                                <label for="target_audience" class="form-label fw-semibold">
                                    Target Audiens
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-people"></i></span>
                                    <select class="form-select @error('target_audience') is-invalid @enderror" 
                                            id="target_audience" name="target_audience">
                                        <option value="all" {{ old('target_audience') == 'all' ? 'selected' : '' }}>📢 Semua</option>
                                        <option value="peserta" {{ old('target_audience') == 'peserta' ? 'selected' : '' }}>👥 Peserta</option>
                                        <option value="trainer" {{ old('target_audience') == 'trainer' ? 'selected' : '' }}>👨‍🏫 Trainer</option>
                                        <option value="admin" {{ old('target_audience') == 'admin' ? 'selected' : '' }}>🛡️ Admin</option>
                                    </select>
                                </div>
                                <small class="text-muted">Pilih target audiens pengumuman.</small>
                                @error('target_audience')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Is Pinned -->
                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input @error('is_pinned') is-invalid @enderror" 
                                           type="checkbox" id="is_pinned" name="is_pinned" value="1"
                                           {{ old('is_pinned') ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="is_pinned">
                                        <i class="bi bi-pin text-primary me-1"></i>
                                        Sematkan Pengumuman
                                    </label>
                                    <small class="d-block text-muted">Pengumuman akan muncul di bagian atas.</small>
                                </div>
                                @error('is_pinned')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Preview -->
                            <div class="col-12">
                                <hr class="my-2">
                                <h6 class="fw-semibold text-muted">
                                    <i class="bi bi-eye me-2"></i>Preview
                                </h6>
                                <div class="p-3 border rounded bg-light" id="previewContainer">
                                    <div class="preview-content">
                                        <p class="text-muted">Preview akan muncul setelah Anda mengetik judul dan konten.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="col-12 mt-4">
                                <div class="d-flex gap-2 flex-wrap">
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <i class="bi bi-save me-1"></i> Simpan Pengumuman
                                    </button>
                                    <a href="{{ route('admin.pengumuman.index') }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-x-circle me-1"></i> Batal
                                    </a>
                                    <button type="reset" class="btn btn-outline-warning">
                                        <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                                    </button>
                                    <button type="button" class="btn btn-outline-success" onclick="previewPengumuman()">
                                        <i class="bi bi-eye me-1"></i> Preview
                                    </button>
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
    // ============================================================
    // PREVIEW FUNCTION
    // ============================================================
    window.previewPengumuman = function() {
        const judul = document.getElementById('judul').value || 'Judul Pengumuman';
        const deskripsi = document.getElementById('deskripsi').value || '';
        const konten = document.getElementById('konten').value || 'Konten pengumuman...';
        const tanggal = document.getElementById('tanggal').value || '';
        const status = document.getElementById('status').value || 'draft';
        const target = document.getElementById('target_audience').value || 'all';
        const isPinned = document.getElementById('is_pinned').checked;

        const statusLabels = {
            'draft': '📝 Draft',
            'published': '✅ Published',
            'archived': '📦 Archived'
        };

        const targetLabels = {
            'all': '📢 Semua',
            'peserta': '👥 Peserta',
            'trainer': '👨‍🏫 Trainer',
            'admin': '🛡️ Admin'
        };

        const previewContainer = document.getElementById('previewContainer');
        previewContainer.innerHTML = `
            <div class="preview-content">
                <div class="d-flex justify-content-between align-items-start">
                    <h5 class="fw-bold mb-2">${judul}</h5>
                    ${isPinned ? '<span class="badge text-bg-primary"><i class="bi bi-pin me-1"></i> Pinned</span>' : ''}
                </div>
                ${deskripsi ? `<p class="text-muted small mb-2">${deskripsi}</p>` : ''}
                <div class="mb-2">
                    <span class="badge ${status === 'published' ? 'text-bg-success' : status === 'draft' ? 'text-bg-secondary' : 'text-bg-secondary'}">
                        ${statusLabels[status] || status}
                    </span>
                    <span class="badge text-bg-info ms-1">${targetLabels[target] || target}</span>
                    ${tanggal ? `<span class="badge text-bg-light ms-1"><i class="bi bi-calendar3 me-1"></i>${tanggal}</span>` : ''}
                </div>
                <div class="border-top pt-2 mt-2">
                    ${konten}
                </div>
            </div>
        `;
    };

    // ============================================================
    // VALIDASI SEBELUM SUBMIT
    // ============================================================
    const form = document.getElementById('pengumumanForm');
    const submitBtn = document.getElementById('submitBtn');

    if (form) {
        form.addEventListener('submit', function(e) {
            const judul = document.getElementById('judul').value.trim();
            const konten = document.getElementById('konten').value.trim();
            const tanggal = document.getElementById('tanggal').value;

            let errors = [];

            if (!judul) {
                errors.push('⚠️ Judul pengumuman wajib diisi.');
                document.getElementById('judul').classList.add('is-invalid');
            }

            if (!konten) {
                errors.push('⚠️ Konten pengumuman wajib diisi.');
                document.getElementById('konten').classList.add('is-invalid');
            }

            if (!tanggal) {
                errors.push('⚠️ Tanggal publikasi wajib dipilih.');
                document.getElementById('tanggal').classList.add('is-invalid');
            }

            if (errors.length > 0) {
                e.preventDefault();
                alert(errors.join('\n'));
                return false;
            }

            // Loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <span class="spinner-border spinner-border-sm me-1" role="status"></span>
                Menyimpan...
            `;

            return true;
        });
    }

    // ============================================================
    // REMOVE ERROR ON INPUT
    // ============================================================
    document.querySelectorAll('input, select, textarea').forEach(el => {
        el.addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
        el.addEventListener('change', function() {
            this.classList.remove('is-invalid');
        });
    });

    // ============================================================
    // AUTO PREVIEW ON CHANGE
    // ============================================================
    document.querySelectorAll('#judul, #deskripsi, #konten, #tanggal, #status, #target_audience, #is_pinned').forEach(el => {
        el.addEventListener('change', previewPengumuman);
        el.addEventListener('input', previewPengumuman);
    });

    // ============================================================
    // TANGGAL VALIDATION
    // ============================================================
    const tanggalSelesai = document.getElementById('tanggal_selesai');
    const tanggalMulai = document.getElementById('tanggal');

    tanggalMulai.addEventListener('change', function() {
        if (this.value) {
            tanggalSelesai.setAttribute('min', this.value);
            if (tanggalSelesai.value && tanggalSelesai.value < this.value) {
                tanggalSelesai.value = '';
            }
        }
    });

    // ============================================================
    // INITIAL PREVIEW
    // ============================================================
    setTimeout(previewPengumuman, 500);

    // ============================================================
    // FOCUS SEARCH ON KEYBOARD SHORTCUT
    // ============================================================
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.key === '/') {
            e.preventDefault();
            const searchInput = document.querySelector('input[name="search"]');
            if (searchInput) {
                searchInput.focus();
                searchInput.select();
            }
        }
    });
});
</script>
@endpush

@push('styles')
<style>
    .panel {
        background: #fff;
        border-radius: 0.75rem;
        box-shadow: 0 1px 4px rgba(0,0,0,.06);
        overflow: hidden;
    }
    .panel-header {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: .75rem;
    }
    .section-title {
        display: flex;
        align-items: center;
        gap: .5rem;
        margin: 0;
        font-size: 1rem;
    }
    .section-title i {
        color: var(--primary);
    }
    .bg-light {
        background-color: #f8f9fa !important;
    }
    .preview-content {
        max-height: 300px;
        overflow-y: auto;
    }
</style>
@endpush
@endsection