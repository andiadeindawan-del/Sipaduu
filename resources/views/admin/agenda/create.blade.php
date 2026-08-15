@extends('layouts.admin')

@section('title', 'Tambah Agenda')

@section('header')
<div class="page-heading d-flex justify-content-between align-items-center">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-calendar-plus"></i></span>
        <div>
            <p class="eyebrow">Manajemen</p>
            <h1 class="h3 mb-0">Tambah Agenda</h1>
            <p class="text-muted mb-0">Buat agenda baru untuk pelatihan</p>
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
                        <h5 class="section-title"><i class="bi bi-calendar-plus text-primary"></i> Form Tambah Agenda</h5>
                        <p class="text-muted small mb-0">Isi data agenda dengan lengkap</p>
                    </div>
                    <span class="badge bg-info">
                        <i class="bi bi-info-circle me-1"></i>
                        Baru
                    </span>
                </div>

                <div class="p-4">
                    <form action="{{ route('admin.agenda.store') }}" method="POST" id="agendaForm">
                        @csrf

                        <div class="row g-4">
                            <!-- Pelatihan -->
                            <div class="col-12">
                                <label for="training_id" class="form-label fw-semibold">
                                    <i class="bi bi-journal-bookmark me-1"></i> Pelatihan
                                </label>
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
                                <small class="text-muted">Kosongkan jika tidak terkait dengan pelatihan tertentu.</small>
                                @error('training_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Judul -->
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('judul') is-invalid @enderror" 
                                           id="judul" name="judul" value="{{ old('judul') }}" 
                                           placeholder="Masukkan judul agenda" required>
                                    <label for="judul">
                                        <i class="bi bi-text-paragraph me-1"></i> Judul Agenda <span class="text-danger">*</span>
                                    </label>
                                </div>
                                @error('judul')
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
                                          placeholder="Deskripsikan agenda ini secara lengkap...">{{ old('deskripsi') }}</textarea>
                                @error('deskripsi')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tanggal & Waktu -->
                            <div class="col-12">
                                <div class="row g-3">
                                    <div class="col-12 col-md-4">
                                        <label for="tanggal" class="form-label fw-semibold">
                                            <i class="bi bi-calendar3 me-1"></i> Tanggal <span class="text-danger">*</span>
                                        </label>
                                        <input type="date" class="form-control @error('tanggal') is-invalid @enderror" 
                                               id="tanggal" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" 
                                               required>
                                        @error('tanggal')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-4">
                                        <label for="waktu_mulai" class="form-label fw-semibold">
                                            <i class="bi bi-clock me-1"></i> Waktu Mulai <span class="text-danger">*</span>
                                        </label>
                                        <input type="time" class="form-control @error('waktu_mulai') is-invalid @enderror" 
                                               id="waktu_mulai" name="waktu_mulai" value="{{ old('waktu_mulai', '08:00') }}" 
                                               required>
                                        @error('waktu_mulai')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-4">
                                        <label for="waktu_selesai" class="form-label fw-semibold">
                                            <i class="bi bi-clock me-1"></i> Waktu Selesai <span class="text-danger">*</span>
                                        </label>
                                        <input type="time" class="form-control @error('waktu_selesai') is-invalid @enderror" 
                                               id="waktu_selesai" name="waktu_selesai" value="{{ old('waktu_selesai', '10:00') }}" 
                                               required>
                                        @error('waktu_selesai')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <small class="text-muted mt-1 d-block">Waktu selesai harus setelah waktu mulai.</small>
                            </div>

                            <!-- Lokasi -->
                            <div class="col-12 col-md-6">
                                <label for="lokasi" class="form-label fw-semibold">
                                    <i class="bi bi-geo-alt me-1"></i> Lokasi
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                    <input type="text" class="form-control @error('lokasi') is-invalid @enderror" 
                                           id="lokasi" name="lokasi" value="{{ old('lokasi') }}" 
                                           placeholder="Contoh: Ruang Meeting A, Zoom Meeting, dll">
                                </div>
                                @error('lokasi')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="col-12 col-md-6">
                                <label for="status" class="form-label fw-semibold">
                                    <i class="bi bi-toggle-on me-1"></i> Status <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" name="status" required>
                                    <option value="upcoming" {{ old('status', 'upcoming') == 'upcoming' ? 'selected' : '' }}>📅 Akan Datang</option>
                                    <option value="ongoing" {{ old('status') == 'ongoing' ? 'selected' : '' }}>⏳ Sedang Berlangsung</option>
                                    <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>✅ Selesai</option>
                                    <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>❌ Dibatalkan</option>
                                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>📝 Draft</option>
                                    <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>📢 Published</option>
                                </select>
                                @error('status')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Preview & Informasi -->
                            <div class="col-12">
                                <hr>
                                <div class="row g-3">
                                    <!-- Preview -->
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">
                                            <i class="bi bi-eye me-1"></i> Preview Agenda
                                        </label>
                                        <div class="p-3 border rounded-3 bg-light" id="previewContainer">
                                            <div class="d-flex align-items-center gap-3 flex-wrap">
                                                <span class="badge bg-info" id="previewStatus">
                                                    <i class="bi bi-circle-fill me-1" style="font-size: 6px;"></i>
                                                    Akan Datang
                                                </span>
                                                <span class="fw-semibold" id="previewJudul">Judul Agenda</span>
                                            </div>
                                            <div class="mt-2">
                                                <small class="text-muted">
                                                    <i class="bi bi-calendar3 me-1"></i>
                                                    <span id="previewTanggal">-</span>
                                                    <span class="mx-1">|</span>
                                                    <i class="bi bi-clock me-1"></i>
                                                    <span id="previewWaktu">-</span>
                                                </small>
                                            </div>
                                            <div class="mt-1" id="previewLokasiContainer">
                                                <small class="text-muted">
                                                    <i class="bi bi-geo-alt me-1"></i>
                                                    <span id="previewLokasi">Tidak ada lokasi</span>
                                                </small>
                                            </div>
                                            <p class="text-muted small mt-2 mb-0">Preview tampilan agenda</p>
                                        </div>
                                    </div>

                                    <!-- Informasi -->
                                    <div class="col-12 col-md-6">
                                        <div class="row g-2">
                                            <div class="col-12">
                                                <div class="info-item p-2 bg-light rounded-3">
                                                    <div class="d-flex align-items-center gap-3">
                                                        <div class="icon-circle-sm bg-info text-white">
                                                            <i class="bi bi-clock"></i>
                                                        </div>
                                                        <div>
                                                            <label class="text-muted small fw-semibold text-uppercase d-block">Status Otomatis</label>
                                                            <p class="fw-semibold mb-0" id="autoStatusInfo">
                                                                <span class="badge text-bg-primary">📅 Akan Datang</span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="info-item p-2 bg-light rounded-3">
                                                    <div class="d-flex align-items-center gap-3">
                                                        <div class="icon-circle-sm bg-success text-white">
                                                            <i class="bi bi-hourglass-split"></i>
                                                        </div>
                                                        <div>
                                                            <label class="text-muted small fw-semibold text-uppercase d-block">Durasi</label>
                                                            <p class="fw-semibold mb-0" id="durasiInfo">-</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
                                    <button type="submit" class="btn btn-primary px-4" id="submitBtn">
                                        <i class="bi bi-save me-1"></i> Simpan Agenda
                                    </button>
                                    <button type="reset" class="btn btn-outline-warning">
                                        <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                                    </button>
                                    <div class="ms-auto">
                                        <a href="{{ route('admin.agenda.index') }}" class="btn btn-outline-secondary">
                                            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
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

@push('styles')
<style>
    /* ============================================================
       PAGE HEADING
    ============================================================ */
    .page-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        padding: 1.25rem 1.5rem;
        background: #fff;
        border-bottom: 1px solid #f0f0f0;
    }
    .page-heading-copy {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .page-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        color: #2563eb;
        font-size: 1.3rem;
        flex-shrink: 0;
    }
    .eyebrow {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #8a93a3;
        font-weight: 600;
    }
    .heading-actions {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }

    /* ============================================================
       PANEL
    ============================================================ */
    .panel {
        background: #fff;
        border-radius: .75rem;
        box-shadow: 0 1px 4px rgba(0,0,0,.06);
        overflow: hidden;
    }
    .panel:hover {
        box-shadow: 0 8px 30px rgba(0,0,0,0.06);
    }
    
    .panel-header {
        padding: .9rem 1.25rem;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: .75rem;
        background: #fafbfc;
    }
    
    .section-title {
        display: flex;
        align-items: center;
        gap: .5rem;
        margin: 0;
        font-size: 1rem;
        font-weight: 600;
        color: #1a2236;
    }
    
    .section-title i {
        color: #4e9af1;
    }

    /* ============================================================
       FORM FLOATING
    ============================================================ */
    .form-floating > .form-control,
    .form-floating > .form-select {
        height: calc(3.5rem + 2px);
        padding: 1rem 0.75rem;
        border-radius: 0.5rem;
        border-color: #e2e8f0;
    }
    .form-floating > .form-control:focus,
    .form-floating > .form-select:focus {
        border-color: #4e9af1;
        box-shadow: 0 0 0 3px rgba(78, 154, 241, 0.15);
    }
    .form-floating > label {
        padding: 1rem 0.75rem;
        color: #8a93a3;
    }

    /* ============================================================
       FORM
    ============================================================ */
    .form-label {
        font-size: 0.875rem;
        margin-bottom: 0.4rem;
        color: #1a2236;
    }
    
    .input-group-text {
        background: #f8fafc;
        border-color: #e2e8f0;
        color: #8a93a3;
    }
    
    .form-control, .form-select {
        border-color: #e2e8f0;
        border-radius: 0.5rem;
        padding: 0.5rem 0.75rem;
        font-size: 0.9rem;
        transition: all 0.2s ease;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #4e9af1;
        box-shadow: 0 0 0 3px rgba(78, 154, 241, 0.15);
    }
    
    .form-control.is-invalid, .form-select.is-invalid {
        border-color: #dc3545;
        box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.15);
    }
    .invalid-feedback {
        font-size: 0.8rem;
        color: #dc3545;
    }
    
    .input-group .form-control, 
    .input-group .form-select {
        border-radius: 0 0.5rem 0.5rem 0;
    }
    
    .input-group .input-group-text:first-child {
        border-radius: 0.5rem 0 0 0.5rem;
    }
    
    textarea.form-control {
        resize: vertical;
        min-height: 80px;
    }

    /* ============================================================
       INFO ITEMS
    ============================================================ */
    .info-item {
        transition: all 0.2s ease;
    }
    .info-item:hover {
        background-color: #e9ecef !important;
    }
    
    .icon-circle-sm {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .icon-circle-sm i {
        font-size: 16px;
    }
    
    .bg-info { background-color: #0dcaf0; }
    .bg-success { background-color: #28c76f; }
    .text-white { color: #fff; }

    /* ============================================================
       BADGE
    ============================================================ */
    .badge {
        font-weight: 500;
        padding: 0.4rem 0.8rem;
        font-size: 0.75rem;
    }
    
    .badge.bg-info {
        background: #cfe2ff !important;
        color: #084298 !important;
    }

    /* ============================================================
       PREVIEW
    ============================================================ */
    #previewContainer {
        transition: all 0.3s ease;
    }
    #previewContainer:hover {
        background: #e9ecef !important;
    }

    /* ============================================================
       BUTTONS
    ============================================================ */
    .btn {
        border-radius: 0.5rem;
        padding: 0.45rem 1.2rem;
        font-weight: 500;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }
    
    .btn-primary {
        background: #4e9af1;
        border-color: #4e9af1;
        color: #fff;
    }
    .btn-primary:hover {
        background: #3d8ae0;
        border-color: #3d8ae0;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(78, 154, 241, 0.3);
    }
    
    .btn-outline-warning {
        border-color: #ff9f43;
        color: #ff9f43;
    }
    .btn-outline-warning:hover {
        background: #ff9f43;
        border-color: #ff9f43;
        color: #fff;
    }
    
    .btn-outline-secondary {
        border-color: #e2e8f0;
        color: #4a5568;
    }
    .btn-outline-secondary:hover {
        background: #e2e8f0;
        border-color: #d5dce6;
    }
    
    .btn-sm {
        padding: 0.3rem 0.8rem;
        font-size: 0.8rem;
    }

    /* ============================================================
       ALERT
    ============================================================ */
    .alert {
        border-radius: 0.75rem;
        border: none;
        padding: 0.75rem 1rem;
    }
    .alert-danger {
        background: #fef2f2;
        color: #991b1b;
    }
    .alert-dismissible .btn-close {
        padding: 1rem;
    }

    /* ============================================================
       RESPONSIVE
    ============================================================ */
    @media (max-width: 768px) {
        .page-heading {
            flex-direction: column;
            align-items: flex-start;
        }
        .page-heading-copy {
            width: 100%;
        }
        .heading-actions {
            width: 100%;
        }
        .panel-header {
            flex-direction: column;
            align-items: flex-start;
        }
        .panel-body {
            padding: 1.25rem !important;
        }
        .d-flex.flex-wrap.gap-2 {
            flex-direction: column;
        }
        .d-flex.flex-wrap.gap-2 .btn {
            width: 100%;
        }
        .ms-auto {
            margin-left: 0 !important;
        }
        .form-floating > .form-control,
        .form-floating > .form-select {
            height: calc(3rem + 2px);
            padding: 0.75rem 0.75rem;
        }
        .form-floating > label {
            padding: 0.75rem;
        }
        .row.g-3 > [class*="col-"] {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }
        .icon-circle-sm {
            width: 30px;
            height: 30px;
        }
        .icon-circle-sm i {
            font-size: 14px;
        }
    }

    /* ============================================================
       ANIMATION
    ============================================================ */
    .panel {
        animation: fadeInUp 0.4s ease;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endpush

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
    const judulInput = document.getElementById('judul');
    const lokasiInput = document.getElementById('lokasi');
    const autoStatusInfo = document.getElementById('autoStatusInfo');
    const durasiInfo = document.getElementById('durasiInfo');
    const form = document.getElementById('agendaForm');
    const submitBtn = document.getElementById('submitBtn');

    // Preview elements
    const previewJudul = document.getElementById('previewJudul');
    const previewTanggal = document.getElementById('previewTanggal');
    const previewWaktu = document.getElementById('previewWaktu');
    const previewLokasi = document.getElementById('previewLokasi');
    const previewStatus = document.getElementById('previewStatus');
    const previewLokasiContainer = document.getElementById('previewLokasiContainer');

    // ============================================================
    // UPDATE PREVIEW
    // ============================================================
    function updatePreview() {
        const judul = judulInput.value || 'Judul Agenda';
        const tanggal = tanggalInput.value;
        const mulai = waktuMulai.value;
        const selesai = waktuSelesai.value;
        const lokasi = lokasiInput.value || '';
        const status = statusSelect.value;

        previewJudul.textContent = judul;

        if (tanggal) {
            const date = new Date(tanggal);
            const options = { day: 'numeric', month: 'long', year: 'numeric' };
            previewTanggal.textContent = date.toLocaleDateString('id-ID', options);
        } else {
            previewTanggal.textContent = '-';
        }

        if (mulai && selesai) {
            previewWaktu.textContent = mulai + ' - ' + selesai;
        } else if (mulai) {
            previewWaktu.textContent = mulai;
        } else {
            previewWaktu.textContent = '-';
        }

        if (lokasi) {
            previewLokasi.textContent = lokasi;
            previewLokasiContainer.style.display = 'block';
        } else {
            previewLokasi.textContent = 'Tidak ada lokasi';
            previewLokasiContainer.style.display = 'block';
        }

        // Update status preview
        const statusLabels = {
            'upcoming': '📅 Akan Datang',
            'ongoing': '⏳ Sedang Berlangsung',
            'completed': '✅ Selesai',
            'cancelled': '❌ Dibatalkan',
            'draft': '📝 Draft',
            'published': '📢 Published'
        };
        previewStatus.textContent = statusLabels[status] || status;
    }

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
        today.setHours(0, 0, 0, 0);
        const selectedDate = new Date(tanggal);
        selectedDate.setHours(0, 0, 0, 0);
        const diffDays = Math.ceil((selectedDate - today) / (1000 * 60 * 60 * 24));

        let statusText = '';
        let badgeClass = '';

        if (diffDays < 0) {
            statusText = '✅ Selesai (Lewat)';
            badgeClass = 'text-bg-secondary';
        } else if (diffDays === 0) {
            statusText = '⏳ Sedang Berlangsung (Hari Ini)';
            badgeClass = 'text-bg-success';
        } else if (diffDays <= 7) {
            statusText = `📅 Akan Datang (${diffDays} hari lagi)`;
            badgeClass = 'text-bg-primary';
        } else {
            statusText = `📅 Akan Datang (${diffDays} hari lagi)`;
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
            durasiInfo.style.color = 'inherit';
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
                const errorMsg = errors.join('\n');
                alert('❌ ' + errorMsg);
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
    tanggalInput.addEventListener('change', function() {
        updateAutoStatus();
        updatePreview();
    });
    statusSelect.addEventListener('change', function() {
        updateAutoStatus();
        updatePreview();
    });
    waktuMulai.addEventListener('change', function() {
        updateDurasi();
        updatePreview();
    });
    waktuSelesai.addEventListener('change', function() {
        updateDurasi();
        updatePreview();
    });
    judulInput.addEventListener('input', updatePreview);
    lokasiInput.addEventListener('input', updatePreview);

    // ============================================================
    // INITIALIZATION
    // ============================================================
    updateAutoStatus();
    updateDurasi();
    updatePreview();

    // ============================================================
    // SET DEFAULT DATE TO TODAY
    // ============================================================
    if (!tanggalInput.value) {
        const today = new Date().toISOString().split('T')[0];
        tanggalInput.value = today;
        updateAutoStatus();
        updatePreview();
    }

    // ============================================================
    // AUTO SET DEFAULT STATUS
    // ============================================================
    if (!statusSelect.value) {
        statusSelect.value = 'upcoming';
        updatePreview();
    }

    // ============================================================
    // FOCUS ON FIRST INPUT
    // ============================================================
    const firstInput = document.querySelector('input[name="judul"]');
    if (firstInput) {
        firstInput.focus();
        firstInput.select();
    }
});
</script>
@endpush
@endsection