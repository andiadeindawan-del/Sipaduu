@extends('layouts.peserta')

@section('title', 'Daftar Materi')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-file-earmark-text"></i></span>
        <div>
            <p class="eyebrow">Materi</p>
            <h1 class="h3 mb-0">Daftar Materi</h1>
            <p class="text-muted mb-0">Akses dan pelajari materi pelatihan yang tersedia.</p>
        </div>
    </div>
    <div class="heading-actions">
        <div class="d-flex gap-2 flex-wrap">
            <form action="{{ route('peserta.materi.index') }}" method="GET" class="d-flex gap-2">
                <input type="text" name="search" class="form-control form-control-sm" 
                       placeholder="Cari materi..." value="{{ request('search') }}" style="width: 200px;">
                <button type="submit" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-search"></i>
                </button>
                <a href="{{ route('peserta.materi.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            </form>
        </div>
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
                    <span class="metric-label">Total Materi</span>
                    <span class="metric-icon"><i class="bi bi-file-earmark-text"></i></span>
                </div>
                <div class="metric-value">{{ $totalMaterials ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-primary">Semua</span>
                    <span>materi</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-success">
                <div class="metric-top">
                    <span class="metric-label">Telah Dipelajari</span>
                    <span class="metric-icon"><i class="bi bi-check-circle"></i></span>
                </div>
                <div class="metric-value">{{ $completedMaterials ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-success">Selesai</span>
                    <span>materi</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-warning">
                <div class="metric-top">
                    <span class="metric-label">Sedang Dipelajari</span>
                    <span class="metric-icon"><i class="bi bi-clock"></i></span>
                </div>
                <div class="metric-value">{{ $inProgressMaterials ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-warning">Progress</span>
                    <span>materi</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-info">
                <div class="metric-top">
                    <span class="metric-label">Total File</span>
                    <span class="metric-icon"><i class="bi bi-files"></i></span>
                </div>
                <div class="metric-value">{{ $totalFiles ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-info">Keseluruhan</span>
                    <span>file</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="panel mb-3">
        <div class="panel-header">
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('peserta.materi.index') }}" 
                   class="btn btn-sm {{ !request('filter') ? 'btn-success' : 'btn-outline-secondary' }}">
                    <i class="bi bi-grid"></i> Semua
                </a>
                <a href="{{ route('peserta.materi.index', ['filter' => 'completed']) }}" 
                   class="btn btn-sm {{ request('filter') == 'completed' ? 'btn-success' : 'btn-outline-secondary' }}">
                    <i class="bi bi-check-circle"></i> Selesai
                </a>
                <a href="{{ route('peserta.materi.index', ['filter' => 'in_progress']) }}" 
                   class="btn btn-sm {{ request('filter') == 'in_progress' ? 'btn-success' : 'btn-outline-secondary' }}">
                    <i class="bi bi-clock"></i> Sedang Dipelajari
                </a>
                <a href="{{ route('peserta.materi.index', ['filter' => 'not_started']) }}" 
                   class="btn btn-sm {{ request('filter') == 'not_started' ? 'btn-success' : 'btn-outline-secondary' }}">
                    <i class="bi bi-hourglass-split"></i> Belum Dipelajari
                </a>
            </div>
        </div>
    </div>

    <!-- Materi Cards -->
    @if($materis && $materis->count() > 0)
        <div class="row g-4">
            @foreach($materis as $materi)
            <div class="col-12 col-md-6 col-xl-4">
                <div class="panel h-100">
                    <div class="p-4">
                        <!-- Status Badge -->
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge 
                                @if($materi->status == 'published') 
                                    badge-published
                                @elseif($materi->status == 'draft') 
                                    badge-draft
                                @else 
                                    badge-archived
                                @endif
                            ">
                                {{ $materi->status_label ?? ucfirst($materi->status ?? 'Draft') }}
                            </span>
                            @php
                                $progress = $materi->getMyProgress();
                            @endphp
                            @if($progress > 0)
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle me-1"></i> {{ $progress }}%
                                </span>
                            @endif
                        </div>

                        <!-- Title -->
                        <h5 class="fw-bold mb-2 text-truncate" title="{{ $materi->judul }}">
                            {{ $materi->judul }}
                        </h5>
                        
                        <!-- Category -->
                        @if($materi->kategori)
                        <p class="text-muted small mb-2">
                            <i class="bi bi-tag me-1"></i>
                            {{ $materi->kategori->nama }}
                        </p>
                        @endif

                        <!-- Description -->
                        <p class="text-muted small mb-3" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                            {{ Str::limit($materi->deskripsi, 100) }}
                        </p>

                        <!-- Info -->
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <span class="text-muted small">
                                <i class="bi bi-files me-1"></i>
                                {{ $materi->total_files ?? 0 }} file
                            </span>
                            <span class="text-muted small">
                                <i class="bi bi-clock me-1"></i>
                                @if($materi->durasi)
                                    {{ $materi->durasi }} menit
                                @else
                                    -
                                @endif
                            </span>
                            <span class="text-muted small">
                                <i class="bi bi-calendar me-1"></i>
                                {{ $materi->created_at ? $materi->created_at->format('d/m/Y') : '-' }}
                            </span>
                        </div>

                        <!-- Progress Bar -->
                        @if($progress > 0)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between small mb-1">
                                <span>Progress</span>
                                <span>{{ $progress }}%</span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-success" style="width: {{ $progress }}%;"></div>
                            </div>
                        </div>
                        @endif

                        <!-- Actions -->
                        <div class="d-flex gap-2 mt-2">
                            <a href="{{ route('peserta.materi.show', $materi->id) }}" 
                               class="btn btn-success btn-sm flex-grow-1">
                                <i class="bi bi-eye me-1"></i> Lihat Materi
                            </a>
                            @if($materi->total_files > 0)
                                <a href="{{ route('peserta.materi.download', $materi->id) }}" 
                                   class="btn btn-primary btn-sm" title="Download">
                                    <i class="bi bi-download"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($materis->hasPages())
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mt-4">
            <p class="text-muted small mb-0">
                Menampilkan {{ $materis->firstItem() ?? 0 }} sampai {{ $materis->lastItem() ?? 0 }} 
                dari {{ $materis->total() ?? 0 }} materi
            </p>
            <nav aria-label="Materi pagination">
                {{ $materis->links() }}
            </nav>
        </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="panel">
            <div class="text-center py-5">
                <div class="text-muted">
                    <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                    <p class="h5">Belum ada materi</p>
                    <p class="small">
                        @if(request('search'))
                            Tidak ada materi yang sesuai dengan pencarian "{{ request('search') }}".
                        @elseif(request('filter') == 'completed')
                            Anda belum menyelesaikan materi apapun.
                        @elseif(request('filter') == 'in_progress')
                            Anda belum memiliki materi yang sedang dipelajari.
                        @elseif(request('filter') == 'not_started')
                            Semua materi sudah Anda pelajari!
                        @else
                            Belum ada materi yang tersedia saat ini.
                        @endif
                    </p>
                    @if(request('search') || request('filter'))
                    <a href="{{ route('peserta.materi.index') }}" class="btn btn-success btn-sm mt-2">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Filter
                    </a>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>

@push('styles')
<style>
    .badge-published { background: #d1e7dd; color: #0a7344; }
    .badge-draft { background: #e9ecef; color: #495057; }
    .badge-archived { background: #f8d7da; color: #842029; }
    
    .progress {
        background-color: #e9ecef;
        border-radius: 10px;
    }
    .progress-bar {
        transition: width 0.6s ease;
        border-radius: 10px;
    }
    
    .panel .btn-sm {
        font-size: 0.8rem;
    }
    
    .text-truncate {
        max-width: 100%;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .metric-card {
        transition: transform 0.2s ease;
    }
    .metric-card:hover {
        transform: translateY(-4px);
    }
    
    .panel {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .panel:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.12);
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

    // Filter buttons - active state
    document.querySelectorAll('.btn-group .btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.btn-group .btn').forEach(function(b) {
                b.classList.remove('active');
            });
            this.classList.add('active');
        });
    });
});
</script>
@endpush
@endsection