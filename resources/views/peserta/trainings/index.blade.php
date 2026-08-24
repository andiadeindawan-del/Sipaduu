@extends('layouts.peserta')

@section('title', 'Daftar Pelatihan')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-journal-bookmark"></i></span>
        <div>
            <p class="eyebrow">Pelatihan</p>
            <h1 class="h3 mb-0">Daftar Pelatihan</h1>
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
                    <span class="metric-label">Total Pelatihan</span>
                    <span class="metric-icon"><i class="bi bi-journal-bookmark"></i></span>
                </div>
                <div class="metric-value">{{ $totalTrainings ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-primary">Semua</span>
                    <span>pelatihan</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-success">
                <div class="metric-top">
                    <span class="metric-label">Sedang Berjalan</span>
                    <span class="metric-icon"><i class="bi bi-play-circle"></i></span>
                </div>
                <div class="metric-value">{{ $ongoingTrainings ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-success">Aktif</span>
                    <span>pelatihan</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-warning">
                <div class="metric-top">
                    <span class="metric-label">Akan Datang</span>
                    <span class="metric-icon"><i class="bi bi-calendar-event"></i></span>
                </div>
                <div class="metric-value">{{ $upcomingTrainings ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-warning">Belum</span>
                    <span>dimulai</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-info">
                <div class="metric-top">
                    <span class="metric-label">Selesai</span>
                    <span class="metric-icon"><i class="bi bi-check-circle"></i></span>
                </div>
                <div class="metric-value">{{ $completedTrainings ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-info">Telah</span>
                    <span>selesai</span>
                </div>
            </div>
        </div>
    </div>

   <!-- Filter Tabs dengan Search -->
<div class="panel mb-3">
    <div class="panel-header">
        <div class="d-flex gap-2 flex-wrap align-items-center">
            <a href="{{ route('peserta.trainings.index') }}" 
               class="btn btn-sm {{ !request('filter') ? 'btn-success' : 'btn-outline-secondary' }}">
                <i class="bi bi-grid"></i> Semua
            </a>
            <a href="{{ route('peserta.trainings.index', ['filter' => 'ongoing']) }}" 
               class="btn btn-sm {{ request('filter') == 'ongoing' ? 'btn-success' : 'btn-outline-secondary' }}">
                <i class="bi bi-play-circle"></i> Sedang Berjalan
            </a>
            <a href="{{ route('peserta.trainings.index', ['filter' => 'upcoming']) }}" 
               class="btn btn-sm {{ request('filter') == 'upcoming' ? 'btn-success' : 'btn-outline-secondary' }}">
                <i class="bi bi-calendar-event"></i> Akan Datang
            </a>
            <a href="{{ route('peserta.trainings.index', ['filter' => 'completed']) }}" 
               class="btn btn-sm {{ request('filter') == 'completed' ? 'btn-success' : 'btn-outline-secondary' }}">
                <i class="bi bi-check-circle"></i> Selesai
            </a>
        </div>
        <div>
            <form action="{{ route('peserta.trainings.index') }}" method="GET" class="d-flex gap-1 align-items-center">
                <div class="input-group input-group-sm" style="width: 200px;">
                    <input type="text" name="search" class="form-control" 
                           placeholder="Cari pelatihan..." value="{{ request('search') }}">
                </div>
                <button type="submit" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-search"></i>
                </button>
                @if(request('search') || request('filter'))
                <a href="{{ route('peserta.trainings.index') }}" class="btn btn-outline-secondary btn-sm" title="Reset Filter">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
                @endif
            </form>
        </div>
    </div>
    @if(request('filter') || request('search'))
    <div class="p-2 px-3 bg-light border-top">
        <small class="text-muted">
            <i class="bi bi-filter-circle me-1"></i>
            Filter aktif: 
            @if(request('filter'))
                <span class="badge text-bg-primary">
                    @if(request('filter') == 'ongoing') Sedang Berjalan
                    @elseif(request('filter') == 'upcoming') Akan Datang
                    @elseif(request('filter') == 'completed') Selesai
                    @else {{ ucfirst(request('filter')) }}
                    @endif
                </span>
            @endif
            @if(request('search'))
                <span class="badge text-bg-primary">Pencarian: "{{ request('search') }}"</span>
            @endif
            <a href="{{ route('peserta.trainings.index') }}" class="text-danger ms-2">
                <i class="bi bi-x-circle"></i> Hapus filter
            </a>
        </small>
    </div>
    @endif
</div>

    <!-- Training Cards -->
    @if($trainings && $trainings->count() > 0)
        <div class="row g-4">
            @foreach($trainings as $training)
            <div class="col-12 col-md-6 col-xl-4">
                <div class="panel h-100">
                    @if($training->gambar)
                    <div class="position-relative">
                        <img src="{{ asset('storage/' . $training->gambar) }}" 
                             alt="{{ $training->judul }}" 
                             class="card-img-top" 
                             style="height: 180px; object-fit: cover; border-radius: 0.75rem 0.75rem 0 0;">
                    </div>
                    @else
                    <div class="bg-light text-center py-4" style="height: 180px; border-radius: 0.75rem 0.75rem 0 0;">
                        <i class="bi bi-image fs-1 text-muted"></i>
                        <p class="text-muted small mb-0">Tidak ada gambar</p>
                    </div>
                    @endif
                    
                    <div class="p-4">
                        <!-- Status Badge -->
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge 
                                @if($training->isCompletedTraining() || $training->status == 'selesai') 
                                    badge-selesai
                                @elseif($training->status == 'published' || $training->status == 'berjalan') 
                                    badge-berjalan
                                @else 
                                    badge-draft
                                @endif
                            ">
                                {{ $training->status_label }}
                            </span>
                            @if($training->isRegistered())
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle me-1"></i> Terdaftar
                                </span>
                            @endif
                        </div>

                        <!-- Title -->
                        <h5 class="fw-bold mb-2 text-truncate" title="{{ $training->judul }}">
                            {{ $training->judul }}
                        </h5>
                        
                        <!-- Category -->
                        @if($training->kategori)
                        <p class="text-muted small mb-2">
                            <i class="bi bi-tag me-1"></i>
                            {{ $training->kategori->nama }}
                        </p>
                        @endif

                        <!-- Description -->
                        <p class="text-muted small mb-3" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                            {{ Str::limit($training->deskripsi, 100) }}
                        </p>

                        <!-- Info -->
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <span class="text-muted small">
                                <i class="bi bi-calendar me-1"></i>
                                @if($training->tanggal_mulai)
                                    {{ $training->tanggal_mulai->format('d/m/Y') }}
                                    @if($training->tanggal_selesai && $training->tanggal_selesai != $training->tanggal_mulai)
                                        - {{ $training->tanggal_selesai->format('d/m/Y') }}
                                    @endif
                                @else
                                    TBD
                                @endif
                            </span>
                            <span class="text-muted small">
                                <i class="bi bi-clock me-1"></i>
                                @if($training->durasi)
                                    {{ $training->durasi }} jam
                                @else
                                    -
                                @endif
                            </span>
                            <span class="text-muted small">
                                <i class="bi bi-people me-1"></i>
                                {{ $training->participants_count ?? 0 }} peserta
                            </span>
                            <span class="text-muted small">
                                <i class="bi bi-geo-alt me-1"></i>
                                @if($training->tipe == 'online')
                                    Online
                                @elseif($training->tipe == 'offline')
                                    Offline
                                @else
                                    Hybrid
                                @endif
                            </span>
                        </div>

                      

                        <!-- Actions -->
                        <div class="d-flex gap-2 mt-2">
                            <a href="{{ route('peserta.trainings.show', $training->id) }}" 
                               class="btn btn-success btn-sm flex-grow-1">
                                <i class="bi bi-eye me-1"></i> Lihat Detail
                            </a>
                            @if(!$training->isRegistered() && $training->status == 'published')
                                @if($training->isCompletedTraining())
                                    <button class="btn btn-secondary btn-sm" disabled>
                                        <i class="bi bi-check-circle"></i> Pelatihan Selesai
                                    </button>
                                @else
                                    <form action="{{ route('peserta.trainings.enroll', $training->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-sm" 
                                                onclick="return confirm('Yakin ingin mendaftar pelatihan ini?')">
                                            <i class="bi bi-plus-circle"></i> Daftar
                                        </button>
                                    </form>
                                @endif
                            @endif
                            @if($training->isRegistered() && $training->status == 'selesai')
                                <a href="{{ route('peserta.sertifikat.index') }}" class="btn btn-info btn-sm">
                                    <i class="bi bi-award me-1"></i> Sertifikat
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($trainings->hasPages())
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mt-4">
            <p class="text-muted small mb-0">
                Menampilkan {{ $trainings->firstItem() ?? 0 }} sampai {{ $trainings->lastItem() ?? 0 }} 
                dari {{ $trainings->total() ?? 0 }} pelatihan
            </p>
            <nav aria-label="Pelatihan pagination">
                {{ $trainings->links() }}
            </nav>
        </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="panel">
            <div class="text-center py-5">
                <div class="text-muted">
                    <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                    <p class="h5">Belum ada pelatihan</p>
                    <p class="small">
                        @if(request('search'))
                            Tidak ada pelatihan yang sesuai dengan pencarian "{{ request('search') }}".
                        @elseif(request('filter') == 'ongoing')
                            Anda belum mengikuti pelatihan yang sedang berjalan.
                        @elseif(request('filter') == 'upcoming')
                            Belum ada pelatihan yang akan datang.
                        @elseif(request('filter') == 'completed')
                            Anda belum menyelesaikan pelatihan apapun.
                        @else
                            Belum ada pelatihan yang tersedia saat ini.
                        @endif
                    </p>
                    @if(request('search') || request('filter'))
                    <a href="{{ route('peserta.trainings.index') }}" class="btn btn-success btn-sm mt-2">
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
    .badge-berjalan { background: #cff4fc; color: #0c5460; }
    .badge-selesai { background: #d1e7dd; color: #0a7344; }
    .badge-draft { background: #e9ecef; color: #495057; }
    .badge-published { background: #d1e7dd; color: #0a7344; }
    
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
    
    .card-img-top {
        border-radius: 0.75rem 0.75rem 0 0 !important;
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

    // Confirm enrollment
    document.querySelectorAll('form[action*="enroll"]').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            if (!confirm('Yakin ingin mendaftar pelatihan ini?')) {
                e.preventDefault();
            }
        });
    });
});
</script>
@endpush
@endsection