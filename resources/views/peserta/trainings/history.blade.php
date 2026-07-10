@extends('layouts.peserta')

@section('title', 'Riwayat Pelatihan')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-clock-history"></i></span>
        <div>
            <p class="eyebrow">Pelatihan</p>
            <h1 class="h3 mb-0">Riwayat Pelatihan</h1>
            <p class="text-muted mb-0">Daftar pelatihan yang telah Anda selesaikan.</p>
        </div>
    </div>
    <div class="heading-actions">
        <a href="{{ route('peserta.trainings.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <a href="{{ route('peserta.trainings.history') }}?export=true" class="btn btn-success btn-sm">
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
            <div class="metric-card metric-success">
                <div class="metric-top">
                    <span class="metric-label">Total Selesai</span>
                    <span class="metric-icon"><i class="bi bi-check2-circle"></i></span>
                </div>
                <div class="metric-value">{{ $totalHistory ?? $trainings->total() ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-success">Pelatihan</span>
                    <span>telah selesai</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-primary">
                <div class="metric-top">
                    <span class="metric-label">Rata-rata Progress</span>
                    <span class="metric-icon"><i class="bi bi-graph-up"></i></span>
                </div>
                <div class="metric-value">{{ $avgProgress ?? 0 }}%</div>
                <div class="metric-meta">
                    <span class="text-primary">Rata-rata</span>
                    <span>kelulusan</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-warning">
                <div class="metric-top">
                    <span class="metric-label">Sertifikat</span>
                    <span class="metric-icon"><i class="bi bi-award"></i></span>
                </div>
                <div class="metric-value">{{ $certificateCount ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-warning">Sertifikat</span>
                    <span>didapatkan</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-info">
                <div class="metric-top">
                    <span class="metric-label">Total Nilai</span>
                    <span class="metric-icon"><i class="bi bi-star"></i></span>
                </div>
                <div class="metric-value">{{ $totalScore ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-info">Keseluruhan</span>
                    <span>nilai</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="panel mb-3">
        <div class="panel-header">
            <div>
                <h5 class="section-title"><i class="bi bi-funnel"></i> Filter</h5>
                <p class="text-muted small mb-0">Filter riwayat pelatihan.</p>
            </div>
        </div>
        <div class="p-3">
            <form action="{{ route('peserta.trainings.history') }}" method="GET" class="row g-3">
                <div class="col-12 col-md-4">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Cari pelatihan...">
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <select class="form-select" name="kategori_id">
                        <option value="">Semua Kategori</option>
                        @foreach($kategoris ?? [] as $kategori)
                            <option value="{{ $kategori->id }}" {{ request('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                {{ $kategori->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <select class="form-select" name="tahun">
                        <option value="">Semua Tahun</option>
                        @foreach($tahunList ?? [] as $tahun)
                            <option value="{{ $tahun }}" {{ request('tahun') == $tahun ? 'selected' : '' }}>
                                {{ $tahun }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-2">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Filter
                        </button>
                        <a href="{{ route('peserta.trainings.history') }}" class="btn btn-outline-secondary" title="Reset Filter">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
        @if(request('search') || request('kategori_id') || request('tahun'))
        <div class="p-2 px-3 bg-light border-top">
            <small class="text-muted">
                <i class="bi bi-filter-circle me-1"></i>
                Filter aktif: 
                @if(request('search'))
                    <span class="badge text-bg-primary">Cari: {{ request('search') }}</span>
                @endif
                @if(request('kategori_id'))
                    <span class="badge text-bg-primary">Kategori: {{ $kategoris->find(request('kategori_id'))->nama ?? '-' }}</span>
                @endif
                @if(request('tahun'))
                    <span class="badge text-bg-primary">Tahun: {{ request('tahun') }}</span>
                @endif
                <a href="{{ route('peserta.trainings.history') }}" class="text-danger ms-2">
                    <i class="bi bi-x-circle"></i> Hapus filter
                </a>
            </small>
        </div>
        @endif
    </div>

    <!-- Table -->
    <div class="panel">
        <div class="panel-header">
            <div>
                <h5 class="section-title"><i class="bi bi-table"></i> Daftar Riwayat</h5>
                <p class="text-muted small mb-0">Menampilkan {{ $trainings->firstItem() ?? 0 }} - {{ $trainings->lastItem() ?? 0 }} dari {{ $trainings->total() ?? 0 }} pelatihan</p>
            </div>
        </div>
        <div class="table-responsive">
            @if($trainings->count() > 0)
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Pelatihan</th>
                        <th>Kategori</th>
                        <th>Tanggal</th>
                        <th>Nilai</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($trainings as $index => $training)
                    @php
                        $userId = auth()->id();
                        $progress = $training->getUserProgress($userId) ?? 0;
                        $isCompleted = $progress >= 100;
                        $score = $training->getUserScore($userId) ?? 0;
                    @endphp
                    <tr>
                        <td>{{ $trainings->firstItem() + $index }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if($training->thumbnail)
                                <img src="{{ Storage::url($training->thumbnail) }}" 
                                     alt="{{ $training->judul }}" 
                                     style="width: 40px; height: 40px; object-fit: cover; border-radius: 8px;">
                                @else
                                <div class="avatar-text rounded-circle bg-success text-white" style="width: 40px; height: 40px; font-size: 0.8rem;">
                                    {{ strtoupper(substr($training->judul, 0, 2)) }}
                                </div>
                                @endif
                                <div>
                                    <p class="fw-semibold mb-0">{{ Str::limit($training->judul, 40) }}</p>
                                    <small class="text-muted">
                                        <i class="bi bi-{{ $training->tipe == 'online' ? 'wifi' : 'building' }} me-1"></i>
                                        {{ $training->tipe_label ?? ucfirst($training->tipe ?? 'Online') }}
                                    </small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge text-bg-primary">{{ $training->kategori->nama ?? '-' }}</span>
                        </td>
                        <td>
                            <small class="d-block">{{ $training->tanggal_mulai ? $training->tanggal_mulai->format('d/m/Y') : '-' }}</small>
                            <small class="text-muted">s.d {{ $training->tanggal_selesai ? $training->tanggal_selesai->format('d/m/Y') : '-' }}</small>
                        </td>
                        <td>
                            <span class="badge text-bg-secondary">
                                <i class="bi bi-star me-1"></i>
                                {{ $score }}
                            </span>
                            <small class="text-muted d-block">{{ $progress }}% Progress</small>
                        </td>
                        <td>
                            @if($isCompleted)
                                <span class="badge text-bg-success">
                                    <i class="bi bi-check-circle me-1"></i> Selesai
                                </span>
                            @else
                                <span class="badge text-bg-warning">
                                    <i class="bi bi-hourglass-split me-1"></i> {{ $progress }}%
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('peserta.trainings.show', $training->id) }}" 
                                   class="btn btn-outline-primary" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if($isCompleted)
                                    <a href="#" class="btn btn-outline-success" title="Sertifikat">
                                        <i class="bi bi-award"></i>
                                    </a>
                                    <a href="#" class="btn btn-outline-info" title="Export">
                                        <i class="bi bi-download"></i>
                                    </a>
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
                    <p class="h5">Belum ada riwayat pelatihan</p>
                    <p class="small">Anda belum menyelesaikan pelatihan apapun.</p>
                    <a href="{{ route('peserta.trainings.index') }}" class="btn btn-primary btn-sm mt-2">
                        <i class="bi bi-search me-1"></i> Cari Pelatihan
                    </a>
                </div>
            </div>
            @endif
        </div>
        @if($trainings->hasPages())
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mt-3 px-3 pb-3">
            <p class="text-muted small mb-0">
                Menampilkan {{ $trainings->firstItem() ?? 0 }} sampai {{ $trainings->lastItem() ?? 0 }} 
                dari {{ $trainings->total() ?? 0 }} pelatihan
            </p>
            <nav aria-label="Pagination">
                {{ $trainings->appends(request()->query())->links() }}
            </nav>
        </div>
        @endif
    </div>
</div>

@push('styles')
<style>
    .avatar-text {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.8rem;
        color: #fff;
        background: var(--accent);
        border-radius: 50%;
        flex-shrink: 0;
    }
    .progress {
        border-radius: 4px;
        background-color: #f0f0f0;
    }
    .progress-bar {
        border-radius: 4px;
        transition: width 0.6s ease;
    }
    .btn-group .btn {
        padding: 0.25rem 0.5rem;
    }
</style>
@endpush
@endsection