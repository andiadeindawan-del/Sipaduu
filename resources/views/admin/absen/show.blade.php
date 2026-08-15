@extends('layouts.admin')

@section('title', 'Detail Absensi')

@section('header')
<div class="page-heading d-flex justify-content-between align-items-center">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-qr-code-scan"></i></span>
        <div>
            <p class="eyebrow">Manajemen</p>
            <h1 class="h3 mb-0">Detail Absensi</h1>
            <p class="text-muted mb-0">Informasi lengkap absensi</p>
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
                        <h5 class="section-title"><i class="bi bi-info-circle"></i> Informasi Absensi</h5>
                    </div>
                    <span class="badge 
                        @if(isset($absen) && $absen->status == 'selesai') badge-published
                        @elseif(isset($absen) && $absen->status == 'berlangsung') badge-berjalan
                        @else badge-draft
                        @endif
                    ">
                        <i class="bi bi-circle-fill me-1" style="font-size: 6px;"></i>
                        {{ isset($absen) ? ucfirst($absen->status ?? 'Draft') : 'Draft' }}
                    </span>
                </div>

                <div class="p-4">
                    @if(isset($absen))
                    <div class="row g-4">
                        <!-- Training -->
                        <div class="col-12 col-md-6">
                            <div class="info-item p-3 bg-light rounded-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="icon-circle bg-success text-white">
                                        <i class="bi bi-journal-bookmark"></i>
                                    </div>
                                    <div>
                                        <label class="text-muted small fw-semibold text-uppercase d-block">Training</label>
                                        <p class="fw-semibold mb-0">
                                            @if($absen->training)
                                                <a href="{{ route('admin.trainings.show', $absen->training->id) }}" class="text-decoration-none">
                                                    {{ $absen->training->judul }}
                                                </a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="col-12 col-md-6">
                            <div class="info-item p-3 bg-light rounded-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="icon-circle bg-warning text-white">
                                        <i class="bi bi-toggle-on"></i>
                                    </div>
                                    <div>
                                        <label class="text-muted small fw-semibold text-uppercase d-block">Status</label>
                                        <p class="fw-semibold mb-0">
                                            <span class="badge 
                                                @if($absen->status == 'selesai') badge-published
                                                @elseif($absen->status == 'berlangsung') badge-berjalan
                                                @else badge-draft
                                                @endif
                                            ">
                                                <i class="bi bi-circle-fill me-1" style="font-size: 6px;"></i>
                                                {{ ucfirst($absen->status ?? 'Draft') }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tanggal -->
                        <div class="col-12 col-md-6">
                            <div class="info-item p-3 bg-light rounded-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="icon-circle bg-info text-white">
                                        <i class="bi bi-calendar3"></i>
                                    </div>
                                    <div>
                                        <label class="text-muted small fw-semibold text-uppercase d-block">Tanggal</label>
                                        <p class="fw-semibold mb-0">
                                            {{ $absen->tanggal ? $absen->tanggal->format('d F Y') : '-' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Waktu -->
                        <div class="col-12 col-md-6">
                            <div class="info-item p-3 bg-light rounded-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="icon-circle bg-primary text-white">
                                        <i class="bi bi-clock"></i>
                                    </div>
                                    <div>
                                        <label class="text-muted small fw-semibold text-uppercase d-block">Waktu</label>
                                        <p class="fw-semibold mb-0">
                                            {{ $absen->waktu_mulai ? date('H:i', strtotime($absen->waktu_mulai)) : '-' }}
                                            {{ $absen->waktu_selesai ? ' - ' . date('H:i', strtotime($absen->waktu_selesai)) : '' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Total Peserta -->
                        <div class="col-12 col-md-6">
                            <div class="info-item p-3 bg-light rounded-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="icon-circle bg-secondary text-white">
                                        <i class="bi bi-people"></i>
                                    </div>
                                    <div>
                                        <label class="text-muted small fw-semibold text-uppercase d-block">Total Peserta</label>
                                        <p class="fw-semibold mb-0">
                                            {{ $absen->peserta_count ?? $absen->pesertas->count() ?? 0 }} peserta
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sudah Absen -->
                        <div class="col-12 col-md-6">
                            <div class="info-item p-3 bg-light rounded-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="icon-circle bg-success text-white">
                                        <i class="bi bi-check-circle"></i>
                                    </div>
                                    <div>
                                        <label class="text-muted small fw-semibold text-uppercase d-block">Sudah Absen</label>
                                        <p class="fw-semibold mb-0">
                                            {{ $absen->sudah_absen ?? 0 }} peserta
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Meta Info -->
                        <div class="col-12">
                            <hr>
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <div class="info-item p-2 bg-light rounded-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="icon-circle-sm bg-info text-white">
                                                <i class="bi bi-clock"></i>
                                            </div>
                                            <div>
                                                <label class="text-muted small fw-semibold text-uppercase d-block">Dibuat</label>
                                                <p class="fw-semibold mb-0">{{ $absen->created_at ? $absen->created_at->format('d/m/Y H:i') : '-' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="info-item p-2 bg-light rounded-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="icon-circle-sm bg-warning text-white">
                                                <i class="bi bi-clock-history"></i>
                                            </div>
                                            <div>
                                                <label class="text-muted small fw-semibold text-uppercase d-block">Diperbarui</label>
                                                <p class="fw-semibold mb-0">{{ $absen->updated_at ? $absen->updated_at->format('d/m/Y H:i') : '-' }}</p>
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
                                <a href="{{ route('admin.absen.edit', $absen->id) }}" class="btn btn-warning">
                                    <i class="bi bi-pencil me-1"></i> Edit
                                </a>
                                <form action="{{ route('admin.absen.destroy', $absen->id) }}" method="POST" class="d-inline" 
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus absensi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="bi bi-trash me-1"></i> Hapus
                                    </button>
                                </form>
                                <div class="ms-auto">
                                    <a href="{{ route('admin.absen.index') }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-arrow-left me-1"></i> Kembali
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <div class="text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                            <p class="h5">Data absensi tidak ditemukan</p>
                            <a href="{{ route('admin.absen.index') }}" class="btn btn-primary btn-sm mt-2">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Daftar Peserta -->
            @if(isset($absen) && isset($absen->pesertas) && $absen->pesertas->count() > 0)
            <div class="panel mt-4">
                <div class="panel-header">
                    <h5 class="section-title"><i class="bi bi-people"></i> Daftar Peserta</h5>
                    <span class="badge bg-primary">
                        <i class="bi bi-people me-1"></i>
                        {{ $absen->pesertas->count() }} peserta
                    </span>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>Nama Peserta</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Waktu Absen</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($absen->pesertas as $index => $peserta)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-img avatar-sm bg-primary text-white d-flex align-items-center justify-content-center rounded-circle" style="width: 32px; height: 32px; font-weight: 600; font-size: 0.7rem;">
                                            {{ strtoupper(substr($peserta->nama ?? $peserta->name ?? 'U', 0, 2)) }}
                                        </div>
                                        <span class="fw-semibold">{{ $peserta->nama ?? $peserta->name ?? 'Unknown' }}</span>
                                    </div>
                                </td>
                                <td>{{ $peserta->email ?? '-' }}</td>
                                <td>
                                    @if(isset($peserta->pivot) && $peserta->pivot->status == 'hadir')
                                        <span class="badge badge-published">
                                            <i class="bi bi-check-circle me-1"></i> Hadir
                                        </span>
                                    @else
                                        <span class="badge badge-draft">
                                            <i class="bi bi-x-circle me-1"></i> Belum Hadir
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if(isset($peserta->pivot) && $peserta->pivot->waktu_absen)
                                        {{ $peserta->pivot->waktu_absen ? date('H:i:s', strtotime($peserta->pivot->waktu_absen)) : '-' }}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
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
    
    .bg-primary { background-color: #0d6efd; }
    .bg-success { background-color: #198754; }
    .bg-info { background-color: #0dcaf0; }
    .bg-warning { background-color: #ffc107; }
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
    .badge-berjalan {
        background: #cfe2ff !important;
        color: #084298 !important;
    }
    .badge.bg-primary {
        background: #cfe2ff !important;
        color: #084298 !important;
    }

    /* ============================================================
       TABLE
    ============================================================ */
    .table th {
        font-weight: 600;
        color: #6c757d;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #f0f0f0;
        padding: 0.75rem 0.75rem;
        background: #fafbfc;
    }
    .table td {
        font-size: 0.9rem;
        padding: 0.75rem 0.75rem;
        vertical-align: middle;
    }
    .table tbody tr {
        transition: background 0.2s ease;
    }
    .table tbody tr:hover {
        background: #f8fafc;
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
       AVATAR
    ============================================================ */
    .avatar-img {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.7rem;
        flex-shrink: 0;
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
        .icon-circle-sm {
            width: 30px;
            height: 30px;
        }
        .icon-circle-sm i {
            font-size: 14px;
        }
        .table-responsive {
            font-size: 0.8rem;
        }
        .table th,
        .table td {
            padding: 0.4rem 0.5rem;
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