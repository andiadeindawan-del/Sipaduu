@extends('layouts.admin')

@section('title', 'Detail Agenda')

@section('header')
<div class="page-heading d-flex justify-content-between align-items-center">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-calendar-event"></i></span>
        <div>
            <p class="eyebrow">Manajemen</p>
            <h1 class="h3 mb-0">Detail Agenda</h1>
            <p class="text-muted mb-0">Informasi lengkap agenda <strong>{{ $agenda->judul }}</strong></p>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="row">
        <div class="col-12 col-lg-10 mx-auto">
            <!-- Alert Messages -->
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <!-- Main Card -->
            <div class="panel">
                <div class="panel-header">
                    <div>
                        <h5 class="section-title"><i class="bi bi-info-circle"></i> Informasi Agenda</h5>
                    </div>
                    <span class="badge {{ $agenda->status_badge ?? 'badge-draft' }}">
                        <i class="bi bi-circle-fill me-1" style="font-size: 6px;"></i>
                        {{ $agenda->status_label ?? ucfirst($agenda->status) }}
                    </span>
                </div>

                <div class="p-4">
                    <div class="row g-4">
                        <!-- Judul -->
                        <div class="col-12">
                            <div class="info-item p-3 bg-light rounded-3">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="icon-circle bg-primary text-white">
                                        <i class="bi bi-text-paragraph"></i>
                                    </div>
                                    <div>
                                        <label class="text-muted small fw-semibold text-uppercase d-block">Judul</label>
                                        <p class="fw-semibold mb-0 fs-5">{{ $agenda->judul }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        @if($agenda->deskripsi)
                        <div class="col-12">
                            <div class="info-item p-3 bg-light rounded-3">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="icon-circle bg-secondary text-white">
                                        <i class="bi bi-file-text"></i>
                                    </div>
                                    <div>
                                        <label class="text-muted small fw-semibold text-uppercase d-block">Deskripsi</label>
                                        <p class="mb-0" style="white-space: pre-line;">{{ $agenda->deskripsi }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Info Grid -->
                        <div class="col-12">
                            <div class="row g-3">
                                <!-- Pelatihan -->
                                <div class="col-12 col-md-6">
                                    <div class="info-item p-3 bg-light rounded-3 h-100">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="icon-circle bg-success text-white">
                                                <i class="bi bi-journal-bookmark"></i>
                                            </div>
                                            <div>
                                                <label class="text-muted small fw-semibold text-uppercase d-block">Pelatihan</label>
                                                @if($agenda->training)
                                                <p class="fw-semibold mb-0">
                                                    <a href="{{ route('admin.trainings.show', $agenda->training->id) }}" class="text-decoration-none">
                                                        {{ $agenda->training->judul }}
                                                    </a>
                                                </p>
                                                @else
                                                <p class="text-muted mb-0">-</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="col-12 col-md-6">
                                    <div class="info-item p-3 bg-light rounded-3 h-100">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="icon-circle bg-warning text-white">
                                                <i class="bi bi-toggle-on"></i>
                                            </div>
                                            <div>
                                                <label class="text-muted small fw-semibold text-uppercase d-block">Status</label>
                                                <p class="fw-semibold mb-0">
                                                    <span class="badge {{ $agenda->status_badge ?? 'badge-draft' }}">
                                                        <i class="bi bi-circle-fill me-1" style="font-size: 6px;"></i>
                                                        {{ $agenda->status_label ?? ucfirst($agenda->status) }}
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tanggal -->
                                <div class="col-12 col-md-6">
                                    <div class="info-item p-3 bg-light rounded-3 h-100">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="icon-circle bg-info text-white">
                                                <i class="bi bi-calendar3"></i>
                                            </div>
                                            <div>
                                                <label class="text-muted small fw-semibold text-uppercase d-block">Tanggal</label>
                                                <p class="fw-semibold mb-0">
                                                    {{ $agenda->tanggal ? $agenda->tanggal->format('d F Y') : '-' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Waktu -->
                                <div class="col-12 col-md-6">
                                    <div class="info-item p-3 bg-light rounded-3 h-100">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="icon-circle bg-primary text-white">
                                                <i class="bi bi-clock"></i>
                                            </div>
                                            <div>
                                                <label class="text-muted small fw-semibold text-uppercase d-block">Waktu</label>
                                                <p class="fw-semibold mb-0">
                                                    {{ $agenda->waktu_mulai ? date('H:i', strtotime($agenda->waktu_mulai)) : '-' }}
                                                    {{ $agenda->waktu_selesai ? ' - ' . date('H:i', strtotime($agenda->waktu_selesai)) : '' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Durasi -->
                                <div class="col-12 col-md-6">
                                    <div class="info-item p-3 bg-light rounded-3 h-100">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="icon-circle bg-secondary text-white">
                                                <i class="bi bi-hourglass-split"></i>
                                            </div>
                                            <div>
                                                <label class="text-muted small fw-semibold text-uppercase d-block">Durasi</label>
                                                <p class="fw-semibold mb-0">
                                                    @php
                                                        $durasi = $agenda->duration ?? null;
                                                    @endphp
                                                    @if($durasi)
                                                        @php
                                                            $hours = floor($durasi);
                                                            $minutes = round(($durasi - $hours) * 60);
                                                        @endphp
                                                        @if($hours > 0 && $minutes > 0)
                                                            {{ $hours }} jam {{ $minutes }} menit
                                                        @elseif($hours > 0)
                                                            {{ $hours }} jam
                                                        @else
                                                            {{ $minutes }} menit
                                                        @endif
                                                    @else
                                                        -
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Lokasi -->
                                <div class="col-12 col-md-6">
                                    <div class="info-item p-3 bg-light rounded-3 h-100">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="icon-circle bg-danger text-white">
                                                <i class="bi bi-geo-alt"></i>
                                            </div>
                                            <div>
                                                <label class="text-muted small fw-semibold text-uppercase d-block">Lokasi</label>
                                                <p class="fw-semibold mb-0">{{ $agenda->lokasi ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Meta Info -->
                        <div class="col-12">
                            <hr>
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <div class="info-item p-3 bg-light rounded-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="icon-circle bg-info text-white">
                                                <i class="bi bi-clock"></i>
                                            </div>
                                            <div>
                                                <label class="text-muted small fw-semibold text-uppercase d-block">Dibuat</label>
                                                <p class="fw-semibold mb-0">{{ $agenda->created_at ? $agenda->created_at->format('d/m/Y H:i') : '-' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="info-item p-3 bg-light rounded-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="icon-circle bg-warning text-white">
                                                <i class="bi bi-clock-history"></i>
                                            </div>
                                            <div>
                                                <label class="text-muted small fw-semibold text-uppercase d-block">Diperbarui</label>
                                                <p class="fw-semibold mb-0">{{ $agenda->updated_at ? $agenda->updated_at->format('d/m/Y H:i') : '-' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="col-12">
                            <hr>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('admin.agenda.edit', $agenda->id) }}" class="btn btn-warning">
                                    <i class="bi bi-pencil me-1"></i> Edit
                                </a>
                                
                                @if($agenda->status == 'cancelled')
                                <form action="{{ route('admin.agenda.update', $agenda->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="judul" value="{{ $agenda->judul }}">
                                    <input type="hidden" name="tanggal" value="{{ $agenda->tanggal ? $agenda->tanggal->format('Y-m-d') : '' }}">
                                    <input type="hidden" name="waktu_mulai" value="{{ $agenda->waktu_mulai ? date('H:i', strtotime($agenda->waktu_mulai)) : '' }}">
                                    <input type="hidden" name="waktu_selesai" value="{{ $agenda->waktu_selesai ? date('H:i', strtotime($agenda->waktu_selesai)) : '' }}">
                                    <input type="hidden" name="status" value="upcoming">
                                    <input type="hidden" name="lokasi" value="{{ $agenda->lokasi }}">
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-arrow-counterclockwise me-1"></i> Aktifkan Kembali
                                    </button>
                                </form>
                                @elseif($agenda->status != 'completed' && $agenda->status != 'cancelled')
                                <form action="{{ route('admin.agenda.update', $agenda->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="judul" value="{{ $agenda->judul }}">
                                    <input type="hidden" name="tanggal" value="{{ $agenda->tanggal ? $agenda->tanggal->format('Y-m-d') : '' }}">
                                    <input type="hidden" name="waktu_mulai" value="{{ $agenda->waktu_mulai ? date('H:i', strtotime($agenda->waktu_mulai)) : '' }}">
                                    <input type="hidden" name="waktu_selesai" value="{{ $agenda->waktu_selesai ? date('H:i', strtotime($agenda->waktu_selesai)) : '' }}">
                                    <input type="hidden" name="status" value="completed">
                                    <input type="hidden" name="lokasi" value="{{ $agenda->lokasi }}">
                                    <button type="submit" class="btn btn-secondary" onclick="return confirm('Yakin ingin menandai agenda ini sebagai selesai?')">
                                        <i class="bi bi-check-circle me-1"></i> Tandai Selesai
                                    </button>
                                </form>
                                @endif

                                <form action="{{ route('admin.agenda.destroy', $agenda->id) }}" method="POST" class="d-inline" 
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus agenda {{ $agenda->judul }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="bi bi-trash me-1"></i> Hapus
                                    </button>
                                </form>

                                <div class="ms-auto">
                                    <a href="{{ route('admin.agenda.index') }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-arrow-left me-1"></i> Kembali
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Data Card -->
            @if($agenda->training)
            <div class="panel mt-4">
                <div class="panel-header">
                    <h5 class="section-title"><i class="bi bi-link-45deg"></i> Data Terkait</h5>
                </div>
                <div class="p-4">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <div class="related-card p-3 bg-light rounded-3">
                                <h6 class="fw-bold text-success">
                                    <i class="bi bi-journal-bookmark me-2"></i>
                                    Pelatihan
                                </h6>
                                <p class="fw-semibold mb-1">
                                    <a href="{{ route('admin.trainings.show', $agenda->training->id) }}" class="text-decoration-none">
                                        {{ $agenda->training->judul }}
                                    </a>
                                </p>
                                <p class="text-muted small mb-0">
                                    <i class="bi bi-calendar3 me-1"></i>
                                    {{ $agenda->training->tanggal_mulai ? $agenda->training->tanggal_mulai->format('d/m/Y') : '-' }}
                                    {{ $agenda->training->tanggal_selesai ? ' - ' . $agenda->training->tanggal_selesai->format('d/m/Y') : '' }}
                                </p>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="related-card p-3 bg-light rounded-3">
                                <h6 class="fw-bold text-primary">
                                    <i class="bi bi-people me-2"></i>
                                    Peserta Terdaftar
                                </h6>
                                <p class="fw-bold fs-4 mb-1">
                                    {{ $agenda->training->participants_count ?? $agenda->training->participants()->count() ?? 0 }}
                                    <span class="text-muted fs-6 fw-normal">peserta</span>
                                </p>
                                <a href="{{ route('admin.trainings.participants', $agenda->training->id) }}" class="btn btn-sm btn-outline-primary mt-1">
                                    <i class="bi bi-eye"></i> Lihat Peserta
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
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
       INFO ITEMS
    ============================================================ */
    .info-item {
        transition: all 0.2s ease;
    }
    .info-item:hover {
        background-color: #e9ecef !important;
    }
    
    .icon-circle {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .icon-circle i {
        font-size: 20px;
    }
    
    .bg-primary { background-color: #0d6efd; }
    .bg-success { background-color: #198754; }
    .bg-info { background-color: #0dcaf0; }
    .bg-warning { background-color: #ffc107; }
    .bg-danger { background-color: #dc3545; }
    .bg-secondary { background-color: #6c757d; }
    .text-white { color: #fff; }

    /* ============================================================
       BADGE
    ============================================================ */
    .badge {
        font-weight: 500;
        padding: 0.4rem 0.8rem;
        font-size: 0.75rem;
    }
    
    .badge-draft {
        background: #e2e8f0 !important;
        color: #4a5568 !important;
    }
    .badge-published {
        background: #d4edda !important;
        color: #155724 !important;
    }
    .badge-secondary {
        background: #e2e8f0 !important;
        color: #6c757d !important;
    }
    .badge.bg-success {
        background: #d4edda !important;
        color: #155724 !important;
    }
    .badge.bg-warning {
        background: #fff3cd !important;
        color: #856404 !important;
    }
    .badge.bg-danger {
        background: #f8d7da !important;
        color: #721c24 !important;
    }

    /* ============================================================
       RELATED CARD
    ============================================================ */
    .related-card {
        transition: all 0.2s ease;
    }
    .related-card:hover {
        background-color: #e9ecef !important;
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
    
    .btn-warning {
        background: #ff9f43;
        border-color: #ff9f43;
        color: #fff;
    }
    .btn-warning:hover {
        background: #f08c2e;
        border-color: #f08c2e;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(255, 159, 67, 0.3);
    }
    
    .btn-danger {
        background: #f56565;
        border-color: #f56565;
        color: #fff;
    }
    .btn-danger:hover {
        background: #e53e3e;
        border-color: #e53e3e;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(245, 101, 101, 0.3);
    }
    
    .btn-success {
        background: #28c76f;
        border-color: #28c76f;
        color: #fff;
    }
    .btn-success:hover {
        background: #1fb45e;
        border-color: #1fb45e;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(40, 199, 111, 0.3);
    }
    
    .btn-secondary {
        background: #e2e8f0;
        border-color: #e2e8f0;
        color: #4a5568;
    }
    .btn-secondary:hover {
        background: #d5dce6;
        border-color: #d5dce6;
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
    .alert-success {
        background: #ecfdf5;
        color: #065f46;
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
            flex-wrap: wrap;
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
        .icon-circle {
            width: 36px;
            height: 36px;
        }
        .icon-circle i {
            font-size: 16px;
        }
        .row.g-3 > [class*="col-"] {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
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
    // AUTO CLOSE ALERTS
    // ============================================================
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