@extends('layouts.admin')

@section('title', 'Manajemen Pendaftaran')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-person-check"></i></span>
        <div>
            <p class="eyebrow">Manajemen</p>
            <h1 class="h3 mb-0">Pendaftaran Pelatihan</h1>
        </div>
    </div>
    <div class="heading-actions d-flex gap-2">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
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
                    <span class="metric-icon"><i class="bi bi-clock-history"></i></span>
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
                    <span class="metric-icon"><i class="bi bi-check-circle"></i></span>
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
                    <span class="metric-icon"><i class="bi bi-x-circle"></i></span>
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
                    <span class="metric-icon"><i class="bi bi-ban"></i></span>
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
                <div class="input-group input-group-sm" style="width: 250px;">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control" name="search" placeholder="Cari peserta..." value="{{ request('search') }}">
                </div>
                <select class="form-select form-select-sm" name="status" style="width: 150px;">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}> Pending</option>
                    <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}> Disetujui</option>
                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}> Ditolak</option>
                    <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}> Dibatalkan</option>
                </select>
                <select class="form-select form-select-sm" name="training_id" style="width: 180px;">
                    <option value="">Semua Pelatihan</option>
                    @foreach($trainings ?? [] as $training)
                    <option value="{{ $training->id }}" {{ request('training_id') == $training->id ? 'selected' : '' }}>
                        {{ $training->judul }}
                    </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-search"></i> Cari
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
                    <span class="badge text-bg-primary">Training: {{ $trainings->firstWhere('id', request('training_id'))->judul ?? request('training_id') }}</span>
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
                <button type="button" class="btn btn-success btn-sm" id="bulkApproveBtn">
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
                        <th>Status</th>
                        <th class="text-end" style="width: 160px;">Aksi</th>
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
                                <div class="avatar-text avatar-sm bg-primary text-white d-flex align-items-center justify-content-center rounded-circle">
                                    {{ strtoupper(substr($registration->user->nama ?? $registration->user->name ?? 'U', 0, 2)) }}
                                </div>
                                <div>
                                    <p class="fw-semibold mb-0">{{ $registration->user->nama ?? $registration->user->name ?? 'Unknown' }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="fw-semibold">{{ $registration->training->judul ?? '-' }}</span>
                            <br>
                        </td>
                        <td>
                            <span class="text-muted">
                                {{ $registration->created_at ? $registration->created_at->format('d/m/Y H:i') : '-' }}
                            </span>
                        </td>
                        <td>
                            @php
                                $statusMap = [
                                    'pending' => ['label' => '⏳ Pending', 'class' => 'badge bg-warning'],
                                    'disetujui' => ['label' => '✅ Disetujui', 'class' => 'badge bg-success'],
                                    'ditolak' => ['label' => '❌ Ditolak', 'class' => 'badge bg-danger'],
                                    'dibatalkan' => ['label' => '🚫 Dibatalkan', 'class' => 'badge bg-secondary'],
                                ];
                                $status = $statusMap[$registration->status] ?? ['label' => $registration->status, 'class' => 'badge bg-secondary'];
                            @endphp
                            <span class="{{ $status['class'] }}">
                                {{ $status['label'] }}
                            </span>
                        </td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm" role="group">
                                @if($registration->status == 'pending')
                                    <form action="{{ route('admin.pendaftaran.approve', $registration->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-success" title="Setujui">
                                            <i class="bi bi-check-circle"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.pendaftaran.reject', $registration->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-danger" title="Tolak" onclick="return confirm('Yakin ingin menolak pendaftaran ini?')">
                                            <i class="bi bi-x-circle"></i>
                                        </button>
                                    </form>
                                @endif
                                @if($registration->status == 'disetujui' || $registration->status == 'pending')
                                    <form action="{{ route('admin.pendaftaran.cancel', $registration->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-secondary" title="Batalkan" onclick="return confirm('Yakin ingin membatalkan pendaftaran ini?')">
                                            <i class="bi bi-ban"></i> Batalkan
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
                        @if(request('search'))
                            Tidak ada pendaftaran yang sesuai dengan pencarian "{{ request('search') }}"
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
                <p>Apakah Anda yakin ingin menyetujui <span id="bulkCount">0</span> pendaftaran yang dipilih?</p>
                <div class="alert alert-success">
                    <i class="bi bi-info-circle me-2"></i>
                    Peserta akan mendapatkan notifikasi dan dapat mengikuti pelatihan.
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

    .panel {
        background: #fff;
        border-radius: .75rem;
        box-shadow: 0 1px 4px rgba(0,0,0,.06);
        overflow: hidden;
    }
    .panel-header {
        padding: .9rem 1.25rem;
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
        color: #4e9af1;
    }

    .avatar-text {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: .8rem;
        color: #fff;
    }

    .btn-group .btn {
        transition: transform 0.2s ease;
    }
    .btn-group .btn:hover {
        transform: scale(1.1);
    }

    @media (max-width: 768px) {
        .metric-value {
            font-size: 1.2rem;
        }
        .panel-header {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select All Checkbox
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
        } else if (bulkApproveBtn) {
            bulkApproveBtn.disabled = true;
            bulkApproveBtn.innerHTML = '<i class="bi bi-check-all"></i> Approve Terpilih';
        }
    }

    // Bulk Approve
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

    // Auto close alerts after 5 seconds
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