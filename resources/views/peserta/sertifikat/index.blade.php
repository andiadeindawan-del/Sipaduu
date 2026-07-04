@extends('layouts.peserta')

@section('title', 'Sertifikat Saya')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-award"></i></span>
        <div>
            <p class="eyebrow">Sertifikat</p>
            <h1 class="h3 mb-0">Sertifikat Saya</h1>
            <p class="text-muted mb-0">Daftar sertifikat yang telah Anda peroleh.</p>
        </div>
    </div>
    <div class="heading-actions">
        <div class="d-flex gap-2 flex-wrap">
            <form action="{{ route('peserta.sertifikat.index') }}" method="GET" class="d-flex gap-2">
                <input type="text" name="search" class="form-control form-control-sm" 
                       placeholder="Cari sertifikat..." value="{{ request('search') }}" style="width: 200px;">
                <button type="submit" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-search"></i>
                </button>
                <a href="{{ route('peserta.sertifikat.index') }}" class="btn btn-outline-secondary btn-sm">
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
                    <span class="metric-label">Total Sertifikat</span>
                    <span class="metric-icon"><i class="bi bi-award"></i></span>
                </div>
                <div class="metric-value">{{ $totalCertificates ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-primary">Semua</span>
                    <span>sertifikat</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-success">
                <div class="metric-top">
                    <span class="metric-label">Aktif</span>
                    <span class="metric-icon"><i class="bi bi-check-circle"></i></span>
                </div>
                <div class="metric-value">{{ $activeCertificates ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-success">Berlaku</span>
                    <span>sertifikat</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-warning">
                <div class="metric-top">
                    <span class="metric-label">Kadaluarsa</span>
                    <span class="metric-icon"><i class="bi bi-clock"></i></span>
                </div>
                <div class="metric-value">{{ $expiredCertificates ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-warning">Expired</span>
                    <span>sertifikat</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-info">
                <div class="metric-top">
                    <span class="metric-label">Pelatihan</span>
                    <span class="metric-icon"><i class="bi bi-journal-bookmark"></i></span>
                </div>
                <div class="metric-value">{{ $trainingsCompleted ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-info">Selesai</span>
                    <span>pelatihan</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="panel mb-3">
        <div class="panel-header">
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('peserta.sertifikat.index') }}" 
                   class="btn btn-sm {{ !request('filter') ? 'btn-success' : 'btn-outline-secondary' }}">
                    <i class="bi bi-grid"></i> Semua
                </a>
                <a href="{{ route('peserta.sertifikat.index', ['filter' => 'aktif']) }}" 
                   class="btn btn-sm {{ request('filter') == 'aktif' ? 'btn-success' : 'btn-outline-secondary' }}">
                    <i class="bi bi-check-circle"></i> Aktif
                </a>
                <a href="{{ route('peserta.sertifikat.index', ['filter' => 'expired']) }}" 
                   class="btn btn-sm {{ request('filter') == 'expired' ? 'btn-success' : 'btn-outline-secondary' }}">
                    <i class="bi bi-clock"></i> Kadaluarsa
                </a>
                <a href="{{ route('peserta.sertifikat.index', ['filter' => 'revoked']) }}" 
                   class="btn btn-sm {{ request('filter') == 'revoked' ? 'btn-success' : 'btn-outline-secondary' }}">
                    <i class="bi bi-x-circle"></i> Dicabut
                </a>
            </div>
        </div>
    </div>

    <!-- Certificate Cards -->
    @if($sertifikats && $sertifikats->count() > 0)
        <div class="row g-4">
            @foreach($sertifikats as $sertifikat)
            <div class="col-12 col-md-6 col-xl-4">
                <div class="panel h-100">
                    <div class="p-4">
                        <!-- Status Badge -->
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge 
                                @if($sertifikat->status == 'aktif') 
                                    badge-success
                                @elseif($sertifikat->status == 'expired') 
                                    badge-warning
                                @elseif($sertifikat->status == 'revoked') 
                                    badge-danger
                                @else 
                                    badge-secondary
                                @endif
                            ">
                                @if($sertifikat->status == 'aktif')
                                    <i class="bi bi-check-circle me-1"></i>
                                @elseif($sertifikat->status == 'expired')
                                    <i class="bi bi-clock me-1"></i>
                                @elseif($sertifikat->status == 'revoked')
                                    <i class="bi bi-x-circle me-1"></i>
                                @endif
                                {{ ucfirst($sertifikat->status) }}
                            </span>
                            <span class="badge bg-primary">
                                <i class="bi bi-award me-1"></i>
                                Sertifikat
                            </span>
                        </div>

                        <!-- Certificate Number -->
                        <p class="text-muted small mb-1">
                            <i class="bi bi-hash me-1"></i>
                            {{ $sertifikat->nomor_sertifikat }}
                        </p>

                        <!-- Title -->
                        <h5 class="fw-bold mb-2 text-truncate" title="{{ $sertifikat->nama_sertifikat }}">
                            {{ $sertifikat->nama_sertifikat }}
                        </h5>
                        
                        <!-- Training -->
                        @if($sertifikat->training)
                        <p class="text-muted small mb-2">
                            <i class="bi bi-journal-bookmark me-1"></i>
                            {{ $sertifikat->training->judul }}
                        </p>
                        @endif

                        <!-- Description -->
                        @if($sertifikat->deskripsi)
                        <p class="text-muted small mb-3" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                            {{ Str::limit($sertifikat->deskripsi, 80) }}
                        </p>
                        @endif

                        <!-- Info -->
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <span class="text-muted small">
                                <i class="bi bi-calendar-check me-1"></i>
                                Terbit: {{ $sertifikat->tanggal_terbit ? $sertifikat->tanggal_terbit->format('d/m/Y') : '-' }}
                            </span>
                            @if($sertifikat->tanggal_berlaku_sampai)
                            <span class="text-muted small">
                                <i class="bi bi-calendar-x me-1"></i>
                                Berlaku s/d: {{ $sertifikat->tanggal_berlaku_sampai->format('d/m/Y') }}
                            </span>
                            @endif
                            <span class="text-muted small">
                                <i class="bi bi-person me-1"></i>
                                {{ $sertifikat->penerbit }}
                            </span>
                        </div>

                        <!-- Actions -->
                        <div class="d-flex gap-2 mt-2">
                            <a href="{{ route('peserta.sertifikat.show', $sertifikat->id) }}" 
                               class="btn btn-success btn-sm flex-grow-1">
                                <i class="bi bi-eye me-1"></i> Detail
                            </a>
                            @if($sertifikat->file_path && $sertifikat->status == 'aktif')
                                <a href="{{ route('peserta.sertifikat.download', $sertifikat->id) }}" 
                                   class="btn btn-primary btn-sm" target="_blank">
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
        @if($sertifikats->hasPages())
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mt-4">
            <p class="text-muted small mb-0">
                Menampilkan {{ $sertifikats->firstItem() ?? 0 }} sampai {{ $sertifikats->lastItem() ?? 0 }} 
                dari {{ $sertifikats->total() ?? 0 }} sertifikat
            </p>
            <nav aria-label="Sertifikat pagination">
                {{ $sertifikats->links() }}
            </nav>
        </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="panel">
            <div class="text-center py-5">
                <div class="text-muted">
                    <i class="bi bi-award fs-1 d-block mb-3"></i>
                    <p class="h5">Belum ada sertifikat</p>
                    <p class="small">
                        @if(request('search'))
                            Tidak ada sertifikat yang sesuai dengan pencarian "{{ request('search') }}".
                        @elseif(request('filter') == 'aktif')
                            Anda belum memiliki sertifikat yang aktif.
                        @elseif(request('filter') == 'expired')
                            Anda belum memiliki sertifikat yang kadaluarsa.
                        @elseif(request('filter') == 'revoked')
                            Anda belum memiliki sertifikat yang dicabut.
                        @else
                            Anda belum memiliki sertifikat. Ikuti pelatihan dan selesaikan untuk mendapatkan sertifikat.
                        @endif
                    </p>
                    @if(request('search') || request('filter'))
                    <a href="{{ route('peserta.sertifikat.index') }}" class="btn btn-success btn-sm mt-2">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Filter
                    </a>
                    @endif
                    @if(!request('search') && !request('filter') && $sertifikats->count() == 0)
                    <a href="{{ route('peserta.trainings.index') }}" class="btn btn-primary btn-sm mt-2">
                        <i class="bi bi-plus-circle me-1"></i> Ikuti Pelatihan
                    </a>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>

@push('styles')
<style>
    .badge-success { background: #d1e7dd; color: #0a7344; }
    .badge-warning { background: #fff3cd; color: #856404; }
    .badge-danger { background: #f8d7da; color: #842029; }
    .badge-secondary { background: #e9ecef; color: #495057; }
    
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
    document.querySelectorAll('.panel-header .btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.panel-header .btn').forEach(function(b) {
                b.classList.remove('active');
            });
            this.classList.add('active');
        });
    });
});
</script>
@endpush
@endsection