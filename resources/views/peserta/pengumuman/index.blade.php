@extends('layouts.peserta')

@section('title', 'Pengumuman')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-megaphone"></i></span>
        <div>
            <p class="eyebrow">Informasi</p>
            <h1 class="h3 mb-0">Pengumuman</h1>
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
        <div class="col-12">
            <div class="metric-card metric-primary">
                <div class="metric-top">
                    <span class="metric-label">Total Pengumuman</span>
                    <span class="metric-icon"><i class="bi bi-megaphone"></i></span>
                </div>
                <div class="metric-value">{{ $totalPengumuman ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-primary">Semua</span>
                    <span>pengumuman</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="panel mb-3">
        <div class="panel-header">
            <div>
                <h5 class="section-title"><i class="bi bi-funnel"></i> Filter</h5>
                <p class="text-muted small mb-0">Filter pengumuman berdasarkan kriteria.</p>
            </div>
        </div>
        <div class="p-3">
            <form action="{{ route('peserta.pengumuman.index') }}" method="GET" class="row g-3">
                <div class="col-12 col-md-6">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Cari pengumuman...">
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <input type="date" class="form-control" name="date_from" 
                           value="{{ request('date_from') }}" placeholder="Dari">
                </div>
                <div class="col-12 col-md-2">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Filter
                        </button>
                        <a href="{{ route('peserta.pengumuman.index') }}" class="btn btn-outline-secondary" title="Reset Filter">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
        @if(request('search') || request('date_from') || request('date_to'))
        <div class="p-2 px-3 bg-light border-top">
            <small class="text-muted">
                <i class="bi bi-filter-circle me-1"></i>
                Filter aktif: 
                @if(request('search'))
                    <span class="badge text-bg-primary">Cari: {{ request('search') }}</span>
                @endif
                @if(request('date_from'))
                    <span class="badge text-bg-primary">Dari: {{ request('date_from') }}</span>
                @endif
                @if(request('date_to'))
                    <span class="badge text-bg-primary">Sampai: {{ request('date_to') }}</span>
                @endif
                <a href="{{ route('peserta.pengumuman.index') }}" class="text-danger ms-2">
                    <i class="bi bi-x-circle"></i> Hapus filter
                </a>
            </small>
        </div>
        @endif
    </div>

    <!-- Pengumuman Cards -->
    <div class="row g-4">
        @if($pengumumans && $pengumumans->count() > 0)
            @foreach($pengumumans as $pengumuman)
            <div class="col-12">
                <div class="panel">
                    <div class="p-4">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                            <div>
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="badge 
                                        @if($pengumuman->status == 'published') text-bg-success
                                        @elseif($pengumuman->status == 'draft') text-bg-secondary
                                        @else text-bg-danger
                                        @endif
                                    ">
                                        {{ ucfirst($pengumuman->status ?? 'Draft') }}
                                    </span>
                                    @if($pengumuman->training)
                                        <span class="badge text-bg-primary">
                                            <i class="bi bi-journal-bookmark me-1"></i>
                                            {{ Str::limit($pengumuman->training->judul, 30) }}
                                        </span>
                                    @else
                                        <span class="badge text-bg-secondary">
                                            <i class="bi bi-globe me-1"></i> Umum
                                        </span>
                                    @endif
                                </div>
                                <h5 class="fw-bold mb-1">{{ $pengumuman->judul }}</h5>
                                <p class="text-muted mb-0">{{ Str::limit($pengumuman->isi, 200) }}</p>
                            </div>
                            <div class="text-end flex-shrink-0">
                                <small class="text-muted d-block">
                                    <i class="bi bi-clock me-1"></i>
                                    {{ $pengumuman->created_at ? $pengumuman->created_at->format('d/m/Y H:i') : '-' }}
                                </small>
                                <small class="text-muted">
                                    <i class="bi bi-person me-1"></i>
                                    {{ $pengumuman->creator->nama ?? $pengumuman->creator->name ?? 'Admin' }}
                                </small>
                            </div>
                        </div>
                        @if(strlen($pengumuman->isi) > 200)
                        <button class="btn btn-sm btn-outline-primary mt-2" 
                                data-bs-toggle="collapse" 
                                data-bs-target="#detail{{ $pengumuman->id }}">
                            <i class="bi bi-chevron-down me-1"></i> Baca Selengkapnya
                        </button>
                        <div class="collapse mt-3" id="detail{{ $pengumuman->id }}">
                            <div class="p-3 bg-light rounded-3">
                                <p class="mb-0">{{ $pengumuman->isi }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach

            <!-- Pagination -->
            <div class="col-12">
                @if($pengumumans->hasPages())
                <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mt-3">
                    <p class="text-muted small mb-0">
                        Menampilkan {{ $pengumumans->firstItem() ?? 0 }} sampai {{ $pengumumans->lastItem() ?? 0 }} 
                        dari {{ $pengumumans->total() ?? 0 }} pengumuman
                    </p>
                    <nav aria-label="Pagination">
                        {{ $pengumumans->appends(request()->query())->links() }}
                    </nav>
                </div>
                @endif
            </div>
        @else
            <!-- Empty State -->
            <div class="col-12">
                <div class="panel">
                    <div class="text-center py-5">
                        <div class="text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                            <p class="h5">Belum ada pengumuman</p>
                            <p class="small">
                                @if(request('search') || request('date_from') || request('date_to'))
                                    Tidak ada pengumuman yang sesuai dengan filter yang Anda pilih.
                                @else
                                    Belum ada pengumuman yang tersedia saat ini.
                                @endif
                            </p>
                            @if(request('search') || request('date_from') || request('date_to'))
                            <a href="{{ route('peserta.pengumuman.index') }}" class="btn btn-success btn-sm mt-2">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Filter
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@push('styles')
<style>
    .metric-card {
        transition: transform 0.3s ease;
    }
    .metric-card:hover {
        transform: translateY(-4px);
    }
    
    .panel {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .panel:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    }
    
    .badge {
        font-weight: 500;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto close alerts after 5 seconds
    setTimeout(function() {
        document.querySelectorAll('.alert').forEach(function(alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);

    // Search with Enter key
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                this.closest('form').submit();
            }
        });
    }

    // Auto submit on date change
    document.querySelector('input[name="date_from"]')?.addEventListener('change', function() {
        this.closest('form').submit();
    });
});
</script>
@endpush
@endsection