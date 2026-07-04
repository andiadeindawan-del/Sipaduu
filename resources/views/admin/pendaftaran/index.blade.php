@extends('layouts.admin')

@section('title', 'Manajemen Pendaftaran')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-person-plus"></i></span>
        <div>
            <p class="eyebrow">Manajemen</p>
            <h1 class="h3 mb-0">Pendaftaran</h1>
            <p class="text-muted mb-0">Kelola pendaftaran peserta pelatihan.</p>
        </div>
    </div>
    <div class="heading-actions">
        <a href="{{ route('admin.pendaftaran.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle"></i> Tambah Pendaftaran
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <!-- Stats -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-primary">
                <div class="metric-top">
                    <span class="metric-label">Total Pendaftaran</span>
                    <span class="metric-icon"><i class="bi bi-people"></i></span>
                </div>
                <div class="metric-value">{{ $totalRegistrations ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-primary">Semua</span>
                    <span>pendaftaran</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-success">
                <div class="metric-top">
                    <span class="metric-label">Aktif</span>
                    <span class="metric-icon"><i class="bi bi-check-circle"></i></span>
                </div>
                <div class="metric-value">{{ $activeRegistrations ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-success">Terdaftar</span>
                    <span>aktif</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-warning">
                <div class="metric-top">
                    <span class="metric-label">Pending</span>
                    <span class="metric-icon"><i class="bi bi-clock"></i></span>
                </div>
                <div class="metric-value">{{ $pendingRegistrations ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-warning">Menunggu</span>
                    <span>verifikasi</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-danger">
                <div class="metric-top">
                    <span class="metric-label">Dibatalkan</span>
                    <span class="metric-icon"><i class="bi bi-x-circle"></i></span>
                </div>
                <div class="metric-value">{{ $cancelledRegistrations ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-danger">Batal</span>
                    <span>pendaftaran</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="panel mb-3">
        <div class="panel-header">
            <div>
                <h5 class="section-title"><i class="bi bi-funnel"></i> Filter</h5>
            </div>
            <form action="{{ route('admin.pendaftaran.index') }}" method="GET" class="d-flex gap-2 flex-wrap">
                <input class="form-control form-control-sm" type="search" name="search" 
                       placeholder="Cari peserta..." value="{{ request('search') }}" style="width: 200px;">
                <select class="form-select form-select-sm" name="status" style="width: 150px;">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>✅ Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>❌ Rejected</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>⛔ Cancelled</option>
                </select>
                <select class="form-select form-select-sm" name="training_id" style="width: 180px;">
                    <option value="">Semua Pelatihan</option>
                    @foreach($trainings ?? [] as $training)
                    <option value="{{ $training->id }}" {{ request('training_id') == $training->id ? 'selected' : '' }}>
                        {{ $training->judul ?? $training->title ?? 'Pelatihan' }}
                    </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-search"></i> Filter
                </button>
                <a href="{{ route('admin.pendaftaran.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                </a>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="panel">
        <div class="panel-header">
            <div>
                <h5 class="section-title"><i class="bi bi-table"></i> Daftar Pendaftaran</h5>
                <p class="text-muted small mb-0">Kelola semua pendaftaran peserta pelatihan.</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('admin.pendaftaran.export') }}" class="btn btn-success btn-sm">
                    <i class="bi bi-download"></i> Export
                </a>
                <a href="{{ route('admin.pendaftaran.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle"></i> Tambah
                </a>
            </div>
        </div>
        <div class="table-responsive">
            @if(isset($registrations) && $registrations->count() > 0)
            <table class="table align-middle mb-0">
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
                            <input type="checkbox" class="form-check-input" value="{{ $registration->id }}">
                        </td>
                        <td>{{ $registrations->firstItem() + $index }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if($registration->user && $registration->user->profile_picture)
                                <img class="avatar-img avatar-sm" src="{{ asset('storage/' . $registration->user->profile_picture) }}" alt="{{ $registration->user->name }}">
                                @elseif($registration->user && $registration->user->foto)
                                <img class="avatar-img avatar-sm" src="{{ asset('storage/' . $registration->user->foto) }}" alt="{{ $registration->user->name }}">
                                @else
                                <div class="avatar-text avatar-sm bg-primary text-white d-flex align-items-center justify-content-center rounded-circle">
                                    {{ strtoupper(substr($registration->user->name ?? $registration->user->nama ?? 'U', 0, 2)) }}
                                </div>
                                @endif
                                <div>
                                    <p class="fw-semibold mb-0">{{ $registration->user->name ?? $registration->user->nama ?? '-' }}</p>
                                    <p class="text-muted small mb-0">{{ $registration->user->email ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($registration->training)
                            <span class="badge text-bg-info">{{ $registration->training->title ?? $registration->training->judul ?? '-' }}</span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <small>
                                {{ $registration->registered_at ? $registration->registered_at->format('d/m/Y H:i') : ($registration->created_at ? $registration->created_at->format('d/m/Y H:i') : '-') }}
                            </small>
                        </td>
                        <td>
                            @php
                                $statusMap = [
                                    'pending' => ['label' => '⏳ Pending', 'class' => 'text-bg-warning'],
                                    'approved' => ['label' => '✅ Approved', 'class' => 'text-bg-success'],
                                    'rejected' => ['label' => '❌ Rejected', 'class' => 'text-bg-danger'],
                                    'cancelled' => ['label' => '⛔ Cancelled', 'class' => 'text-bg-secondary'],
                                ];
                                $status = $statusMap[$registration->status] ?? ['label' => $registration->status ?? 'Unknown', 'class' => 'text-bg-secondary'];
                            @endphp
                            <span class="badge {{ $status['class'] }}">
                                {{ $status['label'] }}
                            </span>
                        </td>
                        <td class="text-end">
                            <div class="d-flex gap-1 justify-content-end">
                                <a href="{{ route('admin.pendaftaran.show', $registration->id) }}" 
                                   class="badge bg-info text-white text-decoration-none p-2" title="Lihat">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.pendaftaran.edit', $registration->id) }}" 
                                   class="badge bg-warning text-dark text-decoration-none p-2" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if($registration->status == 'pending')
                                <button type="button" class="badge bg-success text-white border-0 p-2" 
                                        onclick="confirmAction('{{ route('admin.pendaftaran.approve', $registration->id) }}', 'Approved', 'menyetujui')" 
                                        title="Setujui">
                                    <i class="bi bi-check-lg"></i>
                                </button>
                                <button type="button" class="badge bg-secondary text-white border-0 p-2" 
                                        onclick="confirmAction('{{ route('admin.pendaftaran.reject', $registration->id) }}', 'Rejected', 'menolak')" 
                                        title="Tolak">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                                @endif
                                <button type="button" class="badge bg-danger text-white border-0 p-2" 
                                        data-bs-toggle="modal" data-bs-target="#deleteModal{{ $registration->id }}" 
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
                    <p class="h5">Belum ada pendaftaran</p>
                    <p class="small">Mulai dengan menambahkan pendaftaran baru</p>
                    <a href="{{ route('admin.pendaftaran.create') }}" class="btn btn-primary btn-sm mt-2">
                        <i class="bi bi-plus-circle"></i> Tambah Pendaftaran
                    </a>
                </div>
            </div>
            @endif
        </div>
        @if(isset($registrations) && $registrations->hasPages())
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

<!-- Delete Modals -->
@if(isset($registrations) && $registrations->count() > 0)
@foreach($registrations as $registration)
<div class="modal fade" id="deleteModal{{ $registration->id }}" tabindex="-1" 
     aria-labelledby="deleteModalLabel{{ $registration->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel{{ $registration->id }}">
                    <i class="bi bi-exclamation-triangle text-danger me-2"></i>
                    Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus pendaftaran ini?</p>
                <div class="alert alert-light">
                    <p class="fw-semibold mb-0">
                        {{ $registration->user->name ?? $registration->user->nama ?? 'Peserta' }} - 
                        {{ $registration->training->title ?? $registration->training->judul ?? 'Pelatihan' }}
                    </p>
                </div>
                @if($registration->status == 'approved')
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Pendaftaran ini sudah disetujui. Menghapus akan menghapus semua data terkait.
                </div>
                @endif
                <p class="text-muted small">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin.pendaftaran.destroy', $registration->id) }}" method="POST" class="d-inline">
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ============================================================
    // SELECT ALL CHECKBOX
    // ============================================================
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.table tbody input[type="checkbox"]');

    if (selectAll) {
        selectAll.addEventListener('change', function() {
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
    }

    // ============================================================
    // CONFIRM ACTION (Approve/Reject)
    // ============================================================
    window.confirmAction = function(url, action, actionText) {
        if (confirm('Apakah Anda yakin ingin ' + actionText + ' pendaftaran ini?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = url;
            
            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            
            const method = document.createElement('input');
            method.type = 'hidden';
            method.name = '_method';
            method.value = 'PUT';
            
            form.appendChild(csrf);
            form.appendChild(method);
            document.body.appendChild(form);
            form.submit();
        }
    };

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