@extends('layouts.admin')

@section('title', 'Manajemen Pendaftaran')

@section('header')
<div class="page-heading d-flex justify-content-between align-items-center">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-person-check"></i></span>
        <div>
            <p class="eyebrow">Manajemen</p>
            <h1 class="h3 mb-0">Pendaftaran Pelatihan</h1>
            <p class="text-muted mb-0">Kelola semua pendaftaran peserta pelatihan</p>
        </div>
    </div>
    <div class="heading-actions d-flex gap-2">
        <a href="{{ route('admin.pendaftaran.export') }}" class="btn btn-success btn-sm">
            <i class="bi bi-download"></i> Export
        </a>
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
            <div class="metric-card metric-warning">
                <div class="metric-top">
                    <span class="metric-label">Menunggu</span>
                    <span class="metric-icon" style="color: #ff9f43;"><i class="bi bi-clock-history"></i></span>
                </div>
                <div class="metric-value">{{ $totalPending ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-warning">Pending</span>
                    <span>pendaftaran</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-success">
                <div class="metric-top">
                    <span class="metric-label">Disetujui</span>
                    <span class="metric-icon" style="color: #28c76f;"><i class="bi bi-check-circle"></i></span>
                </div>
                <div class="metric-value">{{ $totalApproved ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-success">Approved</span>
                    <span>pendaftaran</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-danger">
                <div class="metric-top">
                    <span class="metric-label">Ditolak</span>
                    <span class="metric-icon" style="color: #ea5455;"><i class="bi bi-x-circle"></i></span>
                </div>
                <div class="metric-value">{{ $totalRejected ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-danger">Rejected</span>
                    <span>pendaftaran</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-secondary">
                <div class="metric-top">
                    <span class="metric-label">Dibatalkan</span>
                    <span class="metric-icon" style="color: #6c757d;"><i class="bi bi-ban"></i></span>
                </div>
                <div class="metric-value">{{ $totalCancelled ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-secondary">Cancelled</span>
                    <span>pendaftaran</span>
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
            <form action="{{ route('admin.pendaftaran.index') }}" method="GET" class="d-flex gap-2 flex-wrap align-items-center">
                <div class="input-group input-group-sm" style="width: 200px;">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control" name="search" placeholder="Cari peserta..." value="{{ request('search') }}">
                </div>
                <select class="form-select form-select-sm" name="status" style="width: 140px;">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                    <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>✅ Disetujui</option>
                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>❌ Ditolak</option>
                    <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>🚫 Dibatalkan</option>
                </select>
                <select class="form-select form-select-sm" name="training_id" style="width: 180px;">
                    <option value="">Semua Pelatihan</option>
                    @foreach($trainings ?? [] as $training)
                    <option value="{{ $training->id }}" {{ request('training_id') == $training->id ? 'selected' : '' }}>
                        {{ Str::limit($training->judul, 25) }}
                    </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-search"></i> Filter
                </button>
                <a href="{{ route('admin.pendaftaran.index') }}" class="btn btn-outline-secondary btn-sm" title="Reset Filter">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                </a>
            </form>
        </div>
        @if(request('search') || request('status') || request('training_id'))
        <div class="p-2 px-3 bg-light border-top">
            <small class="text-muted">
                <i class="bi bi-filter-circle me-1"></i>
                Filter aktif: 
                @if(request('search'))
                    <span class="badge text-bg-primary">Pencarian: "{{ request('search') }}"</span>
                @endif
                @if(request('status'))
                    <span class="badge text-bg-primary">Status: {{ ucfirst(request('status')) }}</span>
                @endif
                @if(request('training_id'))
                    @php
                        $trainingName = $trainings->firstWhere('id', request('training_id'));
                    @endphp
                    <span class="badge text-bg-primary">Training: {{ $trainingName ? Str::limit($trainingName->judul, 20) : request('training_id') }}</span>
                @endif
                <a href="{{ route('admin.pendaftaran.index') }}" class="text-danger ms-2">
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
                <h5 class="section-title"><i class="bi bi-table"></i> Daftar Pendaftaran</h5>
                <p class="text-muted small mb-0">Total {{ $registrations->total() ?? 0 }} pendaftaran</p>
            </div>
            <div class="d-flex gap-2">
                @if(request('status') == 'pending' || !request('status'))
                <button type="button" class="btn btn-success btn-sm" id="bulkApproveBtn" disabled>
                    <i class="bi bi-check-all"></i> Approve Terpilih
                </button>
                @endif
            </div>
        </div>
        <div class="table-responsive">
            @if($registrations->count() > 0)
            <table class="table align-middle mb-0" id="registrationsTable">
                <thead>
                    <tr>
                        <th style="width: 40px;">
                            <input type="checkbox" id="selectAll" class="form-check-input">
                        </th>
                        <th style="width: 50px;">#</th>
                        <th>Peserta</th>
                        <th>Pelatihan</th>
                        <th>Tanggal Daftar</th>
                        <th>Status Profil</th>
                        <th>Status Pendaftaran</th>
                        <th class="text-center" style="width: 200px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($registrations as $index => $registration)
                    <tr>
                        <td>
                            @if($registration->status == 'pending')
                            <input type="checkbox" class="form-check-input registration-checkbox" value="{{ $registration->id }}">
                            @endif
                        </td>
                        <td>{{ $registrations->firstItem() + $index }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if($registration->user->foto)
                                <img src="{{ asset('storage/' . $registration->user->foto) }}" 
                                     alt="{{ $registration->user->nama }}" 
                                     style="width: 36px; height: 36px; object-fit: cover; border-radius: 50%;">
                                @else
                                <div class="avatar-text bg-primary text-white d-flex align-items-center justify-content-center rounded-circle" 
                                     style="width: 36px; height: 36px; font-weight: 600; font-size: 0.8rem;">
                                    {{ strtoupper(substr($registration->user->nama ?? $registration->user->name ?? 'U', 0, 2)) }}
                                </div>
                                @endif
                                <div>
                                    <p class="fw-semibold mb-0">{{ $registration->user->nama ?? $registration->user->name ?? 'Unknown' }}</p>
                                    <small class="text-muted">{{ $registration->user->email ?? '-' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="fw-semibold">{{ $registration->training->judul ?? '-' }}</span>
                        </td>
                        <td>
                            <span class="text-muted small">
                                <i class="bi bi-calendar3 me-1"></i>
                                {{ $registration->created_at ? $registration->created_at->format('d/m/Y H:i') : '-' }}
                            </span>
                        </td>
                        <td>
                            @if($registration->user->is_profil_lengkap)
                                <span class="badge badge-success">
                                    <i class="bi bi-check-circle me-1"></i> Lengkap
                                </span>
                            @else
                                <span class="badge badge-danger">
                                    <i class="bi bi-x-circle me-1"></i> Belum Lengkap
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($registration->status == 'pending')
                                <span class="badge badge-warning">
                                    <i class="bi bi-clock me-1"></i> Pending
                                </span>
                            @elseif($registration->status == 'disetujui')
                                <span class="badge badge-success">
                                    <i class="bi bi-check-circle me-1"></i> Disetujui
                                </span>
                            @elseif($registration->status == 'ditolak')
                                <span class="badge badge-danger">
                                    <i class="bi bi-x-circle me-1"></i> Ditolak
                                </span>
                            @elseif($registration->status == 'dibatalkan')
                                <span class="badge badge-secondary">
                                    <i class="bi bi-ban me-1"></i> Dibatalkan
                                </span>
                            @else
                                <span class="badge badge-secondary">{{ ucfirst($registration->status) }}</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="{{ route('admin.pendaftaran.show', $registration->id) }}" 
                                   class="btn btn-outline-primary btn-sm" title="Detail Peserta">
                                    <i class="bi bi-person-lines-fill"></i>
                                </a>
                                
                                @if($registration->status == 'pending')
                                <form action="{{ route('admin.pendaftaran.approve', $registration->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-success btn-sm" title="Setujui" 
                                            onclick="return confirm('Setujui pendaftaran {{ $registration->user->nama ?? 'peserta' }}?')">
                                        <i class="bi bi-check-circle"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.pendaftaran.reject', $registration->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Tolak"
                                            onclick="return confirm('Tolak pendaftaran {{ $registration->user->nama ?? 'peserta' }}?')">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </form>
                                @endif
                                
                                @if($registration->status == 'disetujui' || $registration->status == 'pending')
                                <form action="{{ route('admin.pendaftaran.cancel', $registration->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-secondary btn-sm" title="Batalkan" 
                                            onclick="return confirm('Yakin ingin membatalkan pendaftaran ini?')">
                                        <i class="bi bi-ban"></i>
                                    </button>
                                </form>
                                @endif
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
                    <p class="h5">Belum ada pendaftaran</p>
                    <p class="small">
                        @if(request('search') || request('status') || request('training_id'))
                            Tidak ada pendaftaran yang sesuai dengan filter
                        @else
                            Belum ada peserta yang mendaftar pelatihan
                        @endif
                    </p>
                    @if(request('search') || request('status') || request('training_id'))
                    <a href="{{ route('admin.pendaftaran.index') }}" class="btn btn-outline-secondary btn-sm mt-2">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset Filter
                    </a>
                    @endif
                </div>
            </div>
            @endif
        </div>
        @if($registrations->hasPages())
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mt-3 px-3 pb-3">
            <p class="text-muted small mb-0">
                Menampilkan {{ $registrations->firstItem() ?? 0 }} sampai {{ $registrations->lastItem() ?? 0 }} 
                dari {{ $registrations->total() ?? 0 }} pendaftaran
            </p>
            <nav aria-label="Pendaftaran pagination">
                {{ $registrations->links() }}
            </nav>
        </div>
        @endif
    </div>
</div>

<!-- Bulk Approve Modal -->
<div class="modal fade" id="bulkApproveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-check-all text-success me-2"></i>
                    Konfirmasi Approve Massal
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menyetujui <span id="bulkCount" class="fw-bold">0</span> pendaftaran yang dipilih?</p>
                <div class="alert alert-success">
                    <i class="bi bi-info-circle me-2"></i>
                    Peserta akan mendapatkan notifikasi dan dapat mengikuti pelatihan.
                </div>
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Pastikan semua data peserta sudah lengkap sebelum menyetujui.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin.pendaftaran.bulk-approve') }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="ids" id="bulkIds">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-all me-1"></i> Approve Semua
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
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
        transition: transform 0.2s ease;
    }
    .metric-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    }
    .metric-warning { border-left-color: #ff9f43; }
    .metric-success { border-left-color: #28c76f; }
    .metric-danger { border-left-color: #ea5455; }
    .metric-secondary { border-left-color: #6c757d; }
    
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

    /* ============================================================
       BADGE
    ============================================================ */
    .badge {
        font-weight: 500;
        padding: 0.4rem 0.8rem;
        font-size: 0.75rem;
    }
    .badge-success {
        background: #d4edda !important;
        color: #155724 !important;
    }
    .badge-danger {
        background: #f8d7da !important;
        color: #721c24 !important;
    }
    .badge-warning {
        background: #fff3cd !important;
        color: #856404 !important;
    }
    .badge-secondary {
        background: #e2e8f0 !important;
        color: #4a5568 !important;
    }
    .badge-primary {
        background: #cfe2ff !important;
        color: #084298 !important;
    }

    /* ============================================================
       AVATAR
    ============================================================ */
    .avatar-text {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.8rem;
        color: #fff;
        flex-shrink: 0;
        background: #4e9af1;
    }

    /* ============================================================
       BUTTONS
    ============================================================ */
    .btn {
        border-radius: 0.5rem;
        padding: 0.3rem 0.8rem;
        font-weight: 500;
        font-size: 0.8rem;
        transition: all 0.2s ease;
    }
    
    .btn-outline-primary {
        border-color: #4e9af1;
        color: #4e9af1;
    }
    .btn-outline-primary:hover {
        background: #4e9af1;
        border-color: #4e9af1;
        color: #fff;
        transform: scale(1.05);
    }
    
    .btn-success {
        background: #28c76f;
        border-color: #28c76f;
        color: #fff;
    }
    .btn-success:hover {
        background: #1fb45e;
        border-color: #1fb45e;
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(40, 199, 111, 0.3);
    }
    
    .btn-danger {
        background: #ea5455;
        border-color: #ea5455;
        color: #fff;
    }
    .btn-danger:hover {
        background: #e53e3e;
        border-color: #e53e3e;
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(234, 84, 85, 0.3);
    }
    
    .btn-secondary {
        background: #e2e8f0;
        border-color: #e2e8f0;
        color: #4a5568;
    }
    .btn-secondary:hover {
        background: #d5dce6;
        border-color: #d5dce6;
        transform: scale(1.05);
    }
    
    .btn-outline-secondary {
        border-color: #e2e8f0;
        color: #4a5568;
    }
    .btn-outline-secondary:hover {
        background: #e2e8f0;
        border-color: #d5dce6;
    }
    
    .btn-outline-primary {
        border-color: #4e9af1;
        color: #4e9af1;
    }
    .btn-outline-primary:hover {
        background: #4e9af1;
        border-color: #4e9af1;
        color: #fff;
    }
    
    .btn-sm {
        padding: 0.3rem 0.6rem;
        font-size: 0.75rem;
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
    
    .input-group-sm .input-group-text {
        background: #f8fafc;
        border-color: #e2e8f0;
        color: #8a93a3;
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
        .d-flex.gap-2.flex-wrap.align-items-center {
            gap: 0.5rem !important;
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
    // SELECT ALL CHECKBOX
    // ============================================================
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.registration-checkbox');
    const bulkApproveBtn = document.getElementById('bulkApproveBtn');

    if (selectAll) {
        selectAll.addEventListener('change', function() {
            checkboxes.forEach(cb => cb.checked = this.checked);
            updateBulkButton();
        });
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateBulkButton);
    });

    function updateBulkButton() {
        const checked = document.querySelectorAll('.registration-checkbox:checked');
        if (checked.length > 0 && bulkApproveBtn) {
            bulkApproveBtn.disabled = false;
            bulkApproveBtn.innerHTML = '<i class="bi bi-check-all"></i> Approve ' + checked.length + ' Terpilih';
            bulkApproveBtn.classList.remove('btn-secondary');
            bulkApproveBtn.classList.add('btn-success');
        } else if (bulkApproveBtn) {
            bulkApproveBtn.disabled = true;
            bulkApproveBtn.innerHTML = '<i class="bi bi-check-all"></i> Approve Terpilih';
            bulkApproveBtn.classList.remove('btn-success');
            bulkApproveBtn.classList.add('btn-secondary');
        }
    }

    // ============================================================
    // BULK APPROVE
    // ============================================================
    if (bulkApproveBtn) {
        bulkApproveBtn.addEventListener('click', function() {
            const checked = document.querySelectorAll('.registration-checkbox:checked');
            if (checked.length === 0) return;

            const ids = Array.from(checked).map(cb => cb.value);
            document.getElementById('bulkIds').value = JSON.stringify(ids);
            document.getElementById('bulkCount').textContent = ids.length;

            const modal = new bootstrap.Modal(document.getElementById('bulkApproveModal'));
            modal.show();
        });
    }

    // ============================================================
    // AUTO CLOSE ALERTS
    // ============================================================
    setTimeout(function() {
        document.querySelectorAll('.alert').forEach(function(alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);

    // ============================================================
    // SEARCH WITH ENTER KEY
    // ============================================================
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                this.closest('form').submit();
            }
        });
    }

    // ============================================================
    // FOCUS SEARCH ON KEYBOARD SHORTCUT (CTRL + /)
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
@endsection