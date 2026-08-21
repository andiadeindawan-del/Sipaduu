@extends('layouts.admin')

@section('title', 'Manajemen Absensi')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-clock-history"></i></span>
        <div>
            <p class="eyebrow mb-1">Management</p>
            <h1 class="h3 mb-0">Absensi</h1>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
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

    <!-- Stats -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-primary">
                <div class="metric-top">
                    <span class="metric-label">Total Absensi</span>
                    <span class="metric-icon"><i class="bi bi-people"></i></span>
                </div>
                <div class="metric-value">{{ $totalAbsensi ?? $absensis->total() ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-primary">Keseluruhan</span>
                    <span>absensi</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-success">
                <div class="metric-top">
                    <span class="metric-label">Hadir</span>
                    <span class="metric-icon"><i class="bi bi-check-circle"></i></span>
                </div>
                <div class="metric-value">{{ $hadirCount ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-success">Hadir</span>
                    <span>peserta</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-warning">
                <div class="metric-top">
                    <span class="metric-label">Tidak Hadir</span>
                    <span class="metric-icon"><i class="bi bi-clock"></i></span>
                </div>
                <div class="metric-value">{{ $tidakHadirCount ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-warning">Tidak Hadir</span>
                    <span>peserta</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-info">
                <div class="metric-top">
                    <span class="metric-label">Hari Ini</span>
                    <span class="metric-icon"><i class="bi bi-calendar-day"></i></span>
                </div>
                <div class="metric-value">{{ $hariIniCount ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-info">Hari ini</span>
                    <span>absensi</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="panel mb-3">
        <div class="panel-header">
            <div>
                <h5 class="section-title"><i class="bi bi-funnel"></i> Filter & Pencarian</h5>
            </div>
            <form action="{{ route('admin.absen.index') }}" method="GET" class="d-flex gap-2 flex-wrap align-items-center">
                <select class="form-select form-select-sm" name="training_id" style="width: 200px;">
                    <option value="">Semua Pelatihan</option>
                    @foreach($trainings ?? [] as $training)
                    <option value="{{ $training->id }}" {{ request('training_id') == $training->id ? 'selected' : '' }}>
                        {{ $training->judul }}
                    </option>
                    @endforeach
                </select>
                <input type="date" class="form-control form-control-sm" name="tanggal" value="{{ request('tanggal') }}" style="width: 150px;">
                <button type="submit" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-search"></i> Filter
                </button>
                <a href="{{ route('admin.absen.index') }}" class="btn btn-outline-secondary btn-sm" title="Reset Filter">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                </a>
            </form>
        </div>
        @if(request('training_id') || request('tanggal'))
        <div class="p-2 px-3 bg-light border-top">
            <small class="text-muted">
                <i class="bi bi-filter-circle me-1"></i>
                Filter aktif: 
                @if(request('training_id'))
                    @php
                        $trainingName = $trainings->firstWhere('id', request('training_id'))->judul ?? 'Pelatihan';
                    @endphp
                    <span class="badge text-bg-primary">Pelatihan: {{ $trainingName }}</span>
                @endif
                @if(request('tanggal'))
                    <span class="badge text-bg-primary">Tanggal: {{ request('tanggal') }}</span>
                @endif
                <a href="{{ route('admin.absen.index') }}" class="text-danger ms-2">
                    <i class="bi bi-x-circle"></i> Hapus semua filter
                </a>
            </small>
        </div>
        @endif
    </div>

    <!-- Table -->
    <div class="panel">
        <div class="panel-header">
            <div>
                <h5 class="section-title"><i class="bi bi-table"></i> Daftar Absensi Peserta</h5>
                <p class="text-muted small mb-0">Daftar kehadiran peserta pada setiap pelatihan.</p>
            </div>
        </div>
        <div class="table-responsive">
            @if(isset($absensis) && $absensis->count() > 0)
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Peserta</th>
                        <th>Pelatihan</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th class="text-center" style="width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($absensis as $index => $absen)
                    <tr>
                        <td>{{ $absensis->firstItem() + $index }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if($absen->user && $absen->user->foto)
                                <img class="avatar-img avatar-sm" src="{{ asset('storage/' . $absen->user->foto) }}" alt="{{ $absen->user->nama ?? $absen->user->name }}">
                                @else
                                <div class="avatar-text avatar-sm bg-primary text-white d-flex align-items-center justify-content-center rounded-circle">
                                    {{ strtoupper(substr($absen->user->nama ?? $absen->user->name ?? 'U', 0, 2)) }}
                                </div>
                                @endif
                                <div>
                                    <p class="fw-semibold mb-0">{{ $absen->user->nama ?? $absen->user->name ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($absen->training)
                            <span class="text-muted">{{ $absen->training->judul }}</span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge text-bg-light">
                                {{ $absen->tanggal ? $absen->tanggal->format('d/m/Y') : '-' }}
                            </span>
                        </td>
                        <td>
                            @php
                                $statusMap = [
                                    'hadir' => ['label' => '✅ Hadir', 'class' => 'badge-published'],
                                    'sakit' => ['label' => 'Tidak Hadir', 'class' => 'badge-secondary'],
                                    'izin' => ['label' => 'Tidak Hadir', 'class' => 'badge-secondary'],
                                    'alpa' => ['label' => 'Tidak Hadir', 'class' => 'badge-secondary'],
                                ];
                                $status = $statusMap[$absen->status] ?? ['label' => $absen->status ?? 'Unknown', 'class' => 'badge-secondary'];
                            @endphp
                            <span class="badge {{ $status['class'] }}">
                                @if($absen->status == 'hadir')
                                @else
                                    <i class="bi bi-circle-fill me-1" style="font-size: 6px; color: #e2e8f0;"></i>
                                @endif
                                {{ $status['label'] }}
                            </span>
                            @if($absen->keterangan)
                            <br>
                            <small class="text-muted">{{ Str::limit($absen->keterangan, 20) }}</small>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="{{ route('admin.absen.show', $absen->id) }}" 
                                   class="badge bg-info text-white text-decoration-none p-2" title="Lihat">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <button type="button" class="badge bg-danger text-white border-0 p-2" 
                                        data-bs-toggle="modal" data-bs-target="#deleteModal{{ $absen->id }}" 
                                        title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="text-center py-5">
                <div class="text-muted">
                    <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                    <p class="h5">Belum ada data absensi</p>
                    <p class="small">Peserta akan melakukan absensi sendiri melalui dashboard peserta.</p>
                </div>
            </div>
            @endif
        </div>
        @if(isset($absensis) && $absensis->hasPages())
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mt-3 px-3 pb-3">
            <p class="text-muted small mb-0">
                Menampilkan {{ $absensis->firstItem() ?? 0 }} sampai {{ $absensis->lastItem() ?? 0 }} 
                dari {{ $absensis->total() ?? 0 }} absensi
            </p>
            <nav aria-label="Absensi pagination">
                {{ $absensis->links() }}
            </nav>
        </div>
        @endif
    </div>

    <!-- Summary by Training -->
    <div class="panel mt-3">
        <div class="panel-header">
            <div>
                <h5 class="section-title"><i class="bi bi-bar-chart"></i> Rekap Absensi per Pelatihan</h5>
                <p class="text-muted small mb-0">Ringkasan kehadiran peserta per pelatihan.</p>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Pelatihan</th>
                        <th>Total Peserta</th>
                        <th>Hadir</th>
                        <th>Tidak Hadir</th>
                        <th>Persentase Kehadiran</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($trainingSummary ?? [] as $summary)
                    <tr>
                        <td>{{ $summary['training'] ?? '-' }}</td>
                        <td>{{ $summary['total'] ?? 0 }}</td>
                        <td><span class="text-success fw-semibold">{{ $summary['hadir'] ?? 0 }}</span></td>
                        <td>
                            @php
                                $total = $summary['total'] ?? 0;
                                $hadir = $summary['hadir'] ?? 0;
                                $tidakHadir = $total - $hadir;
                            @endphp
                            <span class="text-danger fw-semibold">{{ $tidakHadir }}</span>
                        </td>
                        <td>
                            @php
                                $total = $summary['total'] ?? 0;
                                $hadir = $summary['hadir'] ?? 0;
                                $persentase = $total > 0 ? round(($hadir / $total) * 100) : 0;
                            @endphp
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress" style="width: 100px; height: 8px;">
                                    <div class="progress-bar bg-success" 
                                         style="width: {{ $persentase }}%;">
                                    </div>
                                </div>
                                <span class="small fw-semibold {{ $persentase >= 70 ? 'text-success' : 'text-danger' }}">
                                    {{ $persentase }}%
                                </span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-3 text-muted">
                            <i class="bi bi-inbox me-1"></i> Belum ada data rekap absensi
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Delete Modals -->
@if(isset($absensis) && $absensis->count() > 0)
@foreach($absensis as $absen)
<div class="modal fade" id="deleteModal{{ $absen->id }}" tabindex="-1" 
     aria-labelledby="deleteModalLabel{{ $absen->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel{{ $absen->id }}">
                    <i class="bi bi-exclamation-triangle text-danger me-2"></i>
                    Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus data absensi ini?</p>
                <div class="alert alert-light">
                    <p class="fw-semibold mb-0">
                        {{ $absen->user->nama ?? $absen->user->name ?? 'Peserta' }} - 
                        {{ $absen->tanggal ? $absen->tanggal->format('d/m/Y') : '-' }}
                        @if($absen->status == 'hadir')
                            <span class="badge badge-published">Hadir</span>
                        @else
                            <span class="badge badge-secondary">Tidak Hadir</span>
                        @endif
                    </p>
                </div>
                <p class="text-muted small">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin.absen.destroy', $absen->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-1"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
@endif

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
        background: linear-gradient(135deg, #eaf1fd, #d4e4f7);
        color: #4e9af1;
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

    /* ============================================================
       METRIC CARDS
    ============================================================ */
    .metric-card {
        background: #fff;
        border-radius: 0.75rem;
        padding: 1.1rem 1.25rem;
        box-shadow: 0 1px 4px rgba(0,0,0,.06);
        border-left: 4px solid transparent;
        height: 100%;
        transition: all 0.3s ease;
    }
    .metric-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    }
    .metric-primary { border-left-color: #4e9af1; }
    .metric-success { border-left-color: #28c76f; }
    .metric-warning { border-left-color: #ff9f43; }
    .metric-info { border-left-color: #17a2b8; }
    
    .metric-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: .4rem;
    }
    .metric-label {
        font-size: .75rem;
        color: #8a93a3;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .03em;
    }
    .metric-icon {
        color: #c3cad6;
        font-size: 1.3rem;
    }
    .metric-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1a2236;
    }
    .metric-meta {
        font-size: .75rem;
        color: #8a93a3;
        display: flex;
        gap: .35rem;
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
    
    .table tbody tr:last-child td {
        border-bottom: none;
    }

    /* ============================================================
       BADGE
    ============================================================ */
    .badge {
        font-weight: 500;
        padding: 0.35rem 0.6rem;
        font-size: 0.75rem;
        cursor: pointer;
        transition: all 0.2s ease;
        border: none;
    }
    
    .badge-published {
        background: #d4edda !important;
        color: #155724 !important;
    }
    .badge-secondary {
        background: #e2e8f0 !important;
        color: #6c757d !important;
    }
    
    .badge.bg-info {
        background: #e3f0ff !important;
        color: #0d6efd !important;
    }
    .badge.bg-info:hover {
        background: #d0e4ff !important;
        transform: scale(1.05);
    }
    
    .badge.bg-danger {
        background: #f8d7da !important;
        color: #721c24 !important;
    }
    .badge.bg-danger:hover {
        background: #f5c6cb !important;
        transform: scale(1.05);
    }

    .badge.text-bg-success {
        background: #d4edda !important;
        color: #155724 !important;
    }
    .badge.text-bg-secondary {
        background: #e2e8f0 !important;
        color: #6c757d !important;
    }
    .badge.text-bg-light {
        background: #f8fafc !important;
        color: #4a5568 !important;
    }

    /* ============================================================
       AVATAR
    ============================================================ */
    .avatar-text {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.85rem;
        background: #4e9af1;
        color: #fff;
        border-radius: 50%;
    }
    .avatar-img {
        width: 36px;
        height: 36px;
        object-fit: cover;
        border-radius: 50%;
    }

    /* ============================================================
       PROGRESS
    ============================================================ */
    .progress {
        border-radius: 10px;
        background-color: #e9ecef;
        overflow: hidden;
    }
    .progress-bar {
        border-radius: 10px;
        transition: width 0.6s ease;
    }

    /* ============================================================
       FORM
    ============================================================ */
    .form-select-sm,
    .form-control-sm {
        font-size: 0.8rem;
        padding: 0.3rem 0.8rem;
        border-radius: 0.5rem;
        border-color: #e2e8f0;
    }
    .form-select-sm:focus,
    .form-control-sm:focus {
        border-color: #4e9af1;
        box-shadow: 0 0 0 3px rgba(78, 154, 241, 0.15);
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
    
    .btn-outline-primary {
        border-color: #4e9af1;
        color: #4e9af1;
    }
    .btn-outline-primary:hover {
        background: #4e9af1;
        color: #fff;
    }
    
    .btn-outline-secondary {
        border-color: #e2e8f0;
        color: #4a5568;
    }
    .btn-outline-secondary:hover {
        background: #f7fafc;
        border-color: #d5dce6;
    }
    
    .btn-danger {
        background: #f56565;
        border-color: #f56565;
        color: #fff;
    }
    .btn-danger:hover {
        background: #e53e3e;
        border-color: #e53e3e;
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
       MODAL
    ============================================================ */
    .modal-content {
        border-radius: 0.75rem;
        border: none;
        box-shadow: 0 20px 60px rgba(0,0,0,0.15);
    }
    .modal-header {
        border-bottom: 1px solid #f0f0f0;
        padding: 1rem 1.25rem;
        background: #fafbfc;
    }
    .modal-footer {
        border-top: 1px solid #f0f0f0;
        padding: 1rem 1.25rem;
        background: #fafbfc;
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
        .panel-header {
            flex-direction: column;
            align-items: flex-start;
        }
        .panel-header form {
            width: 100%;
            flex-wrap: wrap;
        }
        .panel-header form select,
        .panel-header form input {
            flex: 1;
            min-width: 120px;
        }
        .metric-value {
            font-size: 1.2rem;
        }
        .table-responsive {
            font-size: 0.85rem;
        }
        .table th,
        .table td {
            padding: 0.5rem 0.5rem;
        }
        .d-flex.gap-1.justify-content-end {
            flex-wrap: wrap;
            justify-content: flex-start !important;
        }
        .modal-dialog {
            margin: 0.5rem;
        }
        .modal-body {
            padding: 1rem;
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