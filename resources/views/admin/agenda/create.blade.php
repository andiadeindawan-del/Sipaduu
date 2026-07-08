@extends('layouts.admin')

@section('title', 'Tambah Agenda')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-calendar-plus"></i></span>
        <div>
            <p class="eyebrow">Manajemen</p>
            <h1 class="h3 mb-0">Tambah Agenda</h1>
        </div>
    </div>
    <div class="heading-actions">
        <a href="{{ route('admin.agenda.index') }}" class="btn btn-outline-secondary btn-sm">
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
                    <h5 class="section-title"><i class="bi bi-calendar-plus"></i> Form Tambah Agenda</h5>
                    <p class="text-muted small mb-0">Isi data agenda dengan lengkap.</p>
                </div>
                <div class="p-4">
                    <form action="{{ route('admin.agenda.store') }}" method="POST" id="agendaForm">
                        @csrf

                        <div class="row g-3">
                            <!-- Pilih Pelatihan -->
                            <div class="col-12">
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
                                <small class="text-muted">Kosongkan jika tidak terkait dengan pelatihan tertentu.</small>
                                @error('training_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Judul -->
                            <div class="col-12">
                                <label for="judul" class="form-label fw-semibold">
                                    Judul Agenda <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-text-paragraph"></i></span>
                                    <input type="text" class="form-control @error('judul') is-invalid @enderror" 
                                           id="judul" name="judul" value="{{ old('judul') }}" 
                                           placeholder="Masukkan judul agenda" required>
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
                                              id="deskripsi" name="deskripsi" rows="3" 
                                              placeholder="Deskripsi agenda (opsional)">{{ old('deskripsi') }}</textarea>
                                </div>
                                @error('deskripsi')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tanggal -->
                            <div class="col-12 col-md-4">
                                <label for="tanggal" class="form-label fw-semibold">
                                    Tanggal <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                                    <input type="date" class="form-control @error('tanggal') is-invalid @enderror" 
                                           id="tanggal" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" 
                                           required>
                                </div>
                                <small class="text-muted">Tanggal pelaksanaan agenda.</small>
                                @error('tanggal')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Waktu Mulai -->
                            <div class="col-12 col-md-4">
                                <label for="waktu_mulai" class="form-label fw-semibold">
                                    Waktu Mulai <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-clock"></i></span>
                                    <input type="time" class="form-control @error('waktu_mulai') is-invalid @enderror" 
                                           id="waktu_mulai" name="waktu_mulai" value="{{ old('waktu_mulai', '08:00') }}" 
                                           required>
                                </div>
                                <small class="text-muted">Jam mulai agenda.</small>
                                @error('waktu_mulai')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Waktu Selesai -->
                            <div class="col-12 col-md-4">
                                <label for="waktu_selesai" class="form-label fw-semibold">
                                    Waktu Selesai <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-clock"></i></span>
                                    <input type="time" class="form-control @error('waktu_selesai') is-invalid @enderror" 
                                           id="waktu_selesai" name="waktu_selesai" value="{{ old('waktu_selesai', '10:00') }}" 
                                           required>
                                </div>
                                <small class="text-muted">Jam selesai agenda (harus setelah waktu mulai).</small>
                                @error('waktu_selesai')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Lokasi -->
                            <div class="col-12">
                                <label for="lokasi" class="form-label fw-semibold">
                                    Lokasi
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                    <input type="text" class="form-control @error('lokasi') is-invalid @enderror" 
                                           id="lokasi" name="lokasi" value="{{ old('lokasi') }}" 
                                           placeholder="Contoh: Ruang Meeting A, Zoom Meeting, dll">
                                </div>
                                <small class="text-muted">Lokasi pelaksanaan agenda (opsional).</small>
                                @error('lokasi')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="col-12">
                                <label for="status" class="form-label fw-semibold">
                                    Status <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-toggle-on"></i></span>
                                    <select class="form-select @error('status') is-invalid @enderror" 
                                            id="status" name="status" required>
                                        <option value="upcoming" {{ old('status') == 'upcoming' ? 'selected' : '' }}>📅 Akan Datang</option>
                                        <option value="ongoing" {{ old('status') == 'ongoing' ? 'selected' : '' }}>⏳ Sedang Berlangsung</option>
                                        <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>✅ Selesai</option>
                                        <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>❌ Dibatalkan</option>
                                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>📝 Draft</option>
                                        <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>📢 Published</option>
                                    </select>
                                </div>
                                <small class="text-muted">Status agenda. Jika draft, status akan otomatis diupdate berdasarkan tanggal.</small>
                                @error('status')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Informasi Tambahan -->
                            <div class="col-12">
                                <hr class="my-2">
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <div class="p-3 bg-light rounded">
                                            <label class="text-muted small">Status Otomatis</label>
                                            <p class="fw-semibold mb-0" id="autoStatusInfo">
                                                <span class="badge text-bg-primary">📅 Akan Datang</span>
                                            </p>
                                            <small class="text-muted">Status akan otomatis berubah berdasarkan tanggal.</small>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="p-3 bg-light rounded">
                                            <label class="text-muted small">Durasi</label>
                                            <p class="fw-semibold mb-0" id="durasiInfo">-</p>
                                            <small class="text-muted">Perkiraan durasi agenda.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="col-12 mt-4">
                                <div class="d-flex gap-2 flex-wrap">
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <i class="bi bi-save me-1"></i> Simpan Agenda
                                    </button>
                                    <a href="{{ route('admin.agenda.index') }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-x-circle me-1"></i> Batal
                                    </a>
                                    <button type="reset" class="btn btn-outline-warning">
                                        <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
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
    // ELEMENTS
    // ============================================================
    const tanggalInput = document.getElementById('tanggal');
    const waktuMulai = document.getElementById('waktu_mulai');
    const waktuSelesai = document.getElementById('waktu_selesai');
    const statusSelect = document.getElementById('status');
    const autoStatusInfo = document.getElementById('autoStatusInfo');
    const durasiInfo = document.getElementById('durasiInfo');
    const form = document.getElementById('agendaForm');
    const submitBtn = document.getElementById('submitBtn');

    // ============================================================
    // UPDATE STATUS OTOMATIS
    // ============================================================
    function updateAutoStatus() {
        const tanggal = tanggalInput.value;
        const status = statusSelect.value;
        
        if (!tanggal) {
            autoStatusInfo.innerHTML = '<span class="badge text-bg-secondary">Pilih tanggal terlebih dahulu</span>';
            return;
        }

        // Hanya update jika status adalah draft atau status tidak dipilih
        if (status !== 'draft' && status !== '') {
            // Tampilkan status yang dipilih
            const statusLabels = {
                'upcoming': '📅 Akan Datang',
                'ongoing': '⏳ Sedang Berlangsung',
                'completed': '✅ Selesai',
                'cancelled': '❌ Dibatalkan',
                'draft': '📝 Draft',
                'published': '📢 Published'
            };
            autoStatusInfo.innerHTML = `<span class="badge text-bg-primary">${statusLabels[status] || status}</span>`;
            return;
        }

        const today = new Date();
        const selectedDate = new Date(tanggal);
        const diffDays = (selectedDate - today) / (1000 * 60 * 60 * 24);

        let statusText = '';
        let badgeClass = '';

        if (diffDays < -1) {
            statusText = '✅ Selesai';
            badgeClass = 'text-bg-secondary';
        } else if (diffDays <= 0 && diffDays >= -1) {
            statusText = '⏳ Sedang Berlangsung';
            badgeClass = 'text-bg-success';
        } else if (diffDays > 0 && diffDays <= 7) {
            statusText = '📅 Akan Datang (Minggu ini)';
            badgeClass = 'text-bg-primary';
        } else {
            statusText = '📅 Akan Datang';
            badgeClass = 'text-bg-primary';
        }

        autoStatusInfo.innerHTML = `<span class="badge ${badgeClass}">${statusText}</span>`;
    }

    // ============================================================
    // UPDATE DURASI
    // ============================================================
    function updateDurasi() {
        const mulai = waktuMulai.value;
        const selesai = waktuSelesai.value;

        if (!mulai || !selesai) {
            durasiInfo.textContent = '-';
            return;
        }

        const [startHour, startMin] = mulai.split(':').map(Number);
        const [endHour, endMin] = selesai.split(':').map(Number);

        let totalMinutes = (endHour * 60 + endMin) - (startHour * 60 + startMin);
        
        if (totalMinutes < 0) {
            durasiInfo.textContent = '⚠️ Waktu selesai harus setelah waktu mulai';
            durasiInfo.style.color = 'red';
            return;
        }

        durasiInfo.style.color = 'inherit';
        const hours = Math.floor(totalMinutes / 60);
        const minutes = totalMinutes % 60;

        if (hours > 0 && minutes > 0) {
            durasiInfo.textContent = `${hours} jam ${minutes} menit`;
        } else if (hours > 0) {
            durasiInfo.textContent = `${hours} jam`;
        } else {
            durasiInfo.textContent = `${minutes} menit`;
        }
    }

    // ============================================================
    // VALIDASI SEBELUM SUBMIT
    // ============================================================
    if (form) {
        form.addEventListener('submit', function(e) {
            const judul = document.getElementById('judul').value.trim();
            const tanggal = document.getElementById('tanggal').value;
            const waktuMulai = document.getElementById('waktu_mulai').value;
            const waktuSelesai = document.getElementById('waktu_selesai').value;

            let errors = [];

            if (!judul) {
                errors.push('⚠️ Judul agenda wajib diisi.');
                document.getElementById('judul').classList.add('is-invalid');
            }

            if (!tanggal) {
                errors.push('⚠️ Tanggal wajib dipilih.');
                document.getElementById('tanggal').classList.add('is-invalid');
            }

            if (!waktuMulai) {
                errors.push('⚠️ Waktu mulai wajib diisi.');
                document.getElementById('waktu_mulai').classList.add('is-invalid');
            }

            if (!waktuSelesai) {
                errors.push('⚠️ Waktu selesai wajib diisi.');
                document.getElementById('waktu_selesai').classList.add('is-invalid');
            }

            if (waktuMulai && waktuSelesai) {
                const start = waktuMulai.split(':').map(Number);
                const end = waktuSelesai.split(':').map(Number);
                const startMinutes = start[0] * 60 + start[1];
                const endMinutes = end[0] * 60 + end[1];

                if (startMinutes >= endMinutes) {
                    errors.push('⚠️ Waktu selesai harus setelah waktu mulai.');
                    document.getElementById('waktu_selesai').classList.add('is-invalid');
                }
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
    // EVENT LISTENERS
    // ============================================================
    tanggalInput.addEventListener('change', updateAutoStatus);
    statusSelect.addEventListener('change', updateAutoStatus);
    waktuMulai.addEventListener('change', updateDurasi);
    waktuSelesai.addEventListener('change', updateDurasi);

    // ============================================================
    // INITIALIZATION
    // ============================================================
    updateAutoStatus();
    updateDurasi();

    // ============================================================
    // SET DEFAULT DATE TO TODAY
    // ============================================================
    if (!tanggalInput.value) {
        const today = new Date().toISOString().split('T')[0];
        tanggalInput.value = today;
        updateAutoStatus();
    }

    // ============================================================
    // AUTO SET DEFAULT STATUS
    // ============================================================
    if (!statusSelect.value) {
        statusSelect.value = 'upcoming';
    }

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
</style>
@endpush
@endsection