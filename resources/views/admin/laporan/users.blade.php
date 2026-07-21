@extends('layouts.admin')

@section('title', 'Laporan Peserta')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-people"></i></span>
        <div>
            <p class="eyebrow">Laporan</p>
            <h1 class="h3 mb-0">Data Peserta dan Statistik Peserta Pelatihan</h1>
        </div>
    </div>
    <div class="heading-actions">
        <a href="{{ route('admin.laporan.export', 'participants') }}" class="btn btn-success btn-sm">
            <i class="bi bi-download"></i> Export CSV
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

    <!-- Summary Stats -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-primary">
                <div class="metric-top">
                    <span class="metric-label">Total Peserta</span>
                    <span class="metric-icon"><i class="bi bi-people"></i></span>
                </div>
                <div class="metric-value">{{ $totalParticipants ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-primary">Terdaftar</span>
                    <span>semua</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-success">
                <div class="metric-top">
                    <span class="metric-label">Aktif</span>
                    <span class="metric-icon"><i class="bi bi-person-check"></i></span>
                </div>
                <div class="metric-value">{{ $activeParticipants ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-success">Aktif</span>
                    <span>peserta</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-warning">
                <div class="metric-top">
                    <span class="metric-label">Non-Aktif</span>
                    <span class="metric-icon"><i class="bi bi-person-x"></i></span>
                </div>
                <div class="metric-value">{{ $inactiveParticipants ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-warning">Tidak aktif</span>
                    <span>peserta</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-info">
                <div class="metric-top">
                    <span class="metric-label">Rata-rata Pelatihan</span>
                    <span class="metric-icon"><i class="bi bi-journal-bookmark"></i></span>
                </div>
                <div class="metric-value">{{ $avgTrainingsPerParticipant ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-info">Per peserta</span>
                    <span>pelatihan</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="panel mb-3">
        <div class="panel-header">
            <div>
                <h5 class="section-title"><i class="bi bi-funnel"></i> Filter Peserta</h5>
                <p class="text-muted small mb-0">Filter data peserta berdasarkan kriteria tertentu.</p>
            </div>
        </div>
        <div class="p-3">
            <form action="{{ route('admin.laporan.users') }}" method="GET" class="row g-3">
                <div class="col-12 col-md-4">
                    <label class="form-label fw-semibold">Cari</label>
                    <input type="text" class="form-control" name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Cari nama atau email...">
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label fw-semibold">Status</label>
                    <select class="form-select" name="status">
                        <option value="">Semua Status</option>
                        <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Non-Aktif</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label fw-semibold">Tanggal Daftar</label>
                    <input type="date" class="form-control" name="date_from" 
                           value="{{ request('date_from') }}" placeholder="Dari">
                </div>
                <div class="col-12 col-md-2 d-flex align-items-end">
                    <div class="d-flex gap-2 w-100">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Filter
                        </button>
                        <a href="{{ route('admin.laporan.users') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
        @if(request('search') || request('status') || request('date_from'))
        <div class="p-2 px-3 bg-light border-top">
            <small class="text-muted">
                <i class="bi bi-filter-circle me-1"></i>
                Filter aktif: 
                @if(request('search'))
                    <span class="badge text-bg-primary">Cari: {{ request('search') }}</span>
                @endif
                @if(request('status'))
                    <span class="badge text-bg-primary">Status: {{ ucfirst(request('status')) }}</span>
                @endif
                @if(request('date_from'))
                    <span class="badge text-bg-primary">Dari: {{ request('date_from') }}</span>
                @endif
                <a href="{{ route('admin.laporan.users') }}" class="text-danger ms-2">
                    <i class="bi bi-x-circle"></i> Hapus filter
                </a>
            </small>
        </div>
        @endif
    </div>

    <!-- Table Participants -->
    <div class="panel">
        <div class="panel-header">
            <div>
                <h5 class="section-title"><i class="bi bi-table"></i> Daftar Peserta</h5>
                <p class="text-muted small mb-0">Menampilkan {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} dari {{ $users->total() ?? 0 }} peserta</p>
            </div>
        </div>
        <div class="table-responsive">
            @if($users->count() > 0)
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Pelatihan Diikuti</th>
                        <th>Sertifikat</th>
                        <th>Tanggal Daftar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $index => $user)
                    <tr>
                        <td>{{ $users->firstItem() + $index }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if($user->avatar)
                                <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->nama ?? $user->name }}" 
                                     class="rounded-circle" width="36" height="36" style="object-fit: cover;">
                                @else
                               
                                @endif
                                <div>
                                    <p class="fw-semibold mb-0">{{ $user->nama ?? $user->name }}</p>
                                </div>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @php
                                $status = $user->status ?? 'aktif';
                                $badgeClass = $status == 'aktif' || $status == 'active' ? 'text-bg-success' : 
                                              ($status == 'pending' ? 'text-bg-warning' : 'text-bg-secondary');
                            @endphp
                            <span class="badge {{ $badgeClass }}">
                                {{ ucfirst($status) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge text-bg-primary">
                                {{ $user->trainings_count ?? 0 }}
                            </span>
                        </td>
                        <td>
                            <span class="badge text-bg-success">
                                {{ $user->certificates_count ?? 0 }}
                            </span>
                        </td>
                        <td>{{ $user->created_at ? $user->created_at->format('d/m/Y') : '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="text-center py-5">
                <div class="text-muted">
                    <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                    <p class="h5">Tidak ada data peserta</p>
                    <p class="small">Belum ada peserta yang terdaftar.</p>
                </div>
            </div>
            @endif
        </div>
        @if($users->hasPages())
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mt-3 px-3 pb-3">
            <p class="text-muted small mb-0">
                Menampilkan {{ $users->firstItem() ?? 0 }} sampai {{ $users->lastItem() ?? 0 }} 
                dari {{ $users->total() ?? 0 }} peserta
            </p>
            <nav aria-label="Peserta pagination">
                {{ $users->appends(request()->query())->links() }}
            </nav>
        </div>
        @endif
    </div>
</div>

@push('styles')
<style>
    .avatar-text {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.85rem;
    }
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
    .table th {
        font-weight: 600;
        color: #6c757d;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #f0f0f0;
    }
</style>
@endpush
@endsection