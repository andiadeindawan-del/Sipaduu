@extends('layouts.peserta')

@section('title', 'Sertifikat Saya')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-award"></i></span>
        <div>
            <p class="eyebrow">Prestasi</p>
            <h1 class="h3 mb-0">Sertifikat Saya</h1>
            <p class="text-muted mb-0">Kumpulkan dan kelola semua sertifikat yang telah Anda peroleh.</p>
        </div>
    </div>
    <div class="heading-actions">
        <div class="d-flex gap-2 flex-wrap">
            <form action="{{ route('peserta.sertifikat.index') }}" method="GET" class="d-flex gap-2">
                <div class="input-group input-group-sm" style="width: 220px;">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" 
                           placeholder="Cari sertifikat..." value="{{ request('search') }}">
                </div>
                <button type="submit" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-search"></i> Cari
                </button>
                <a href="{{ route('peserta.sertifikat.index') }}" class="btn btn-outline-secondary btn-sm" title="Reset Filter">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            </form>
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
            @if(request('filter') || request('search'))
            <div>
                <span class="badge bg-light text-muted">
                    <i class="bi bi-filter-circle me-1"></i>
                    Filter aktif
                    <a href="{{ route('peserta.sertifikat.index') }}" class="text-danger ms-1" title="Hapus filter">
                        <i class="bi bi-x-circle"></i>
                    </a>
                </span>
            </div>
            @endif
        </div>
    </div>

    <!-- Certificate Cards -->
    @if($sertifikats && $sertifikats->count() > 0)
        <div class="row g-4">
            @foreach($sertifikats as $sertifikat)
            <div class="col-12 col-md-6 col-xl-4">
                <div class="certificate-card">
                    <!-- Card Header with Icon -->
                    <div class="certificate-card-header">
                        <div class="certificate-icon">
                            <i class="bi bi-award-fill"></i>
                        </div>
                        <div class="certificate-status">
                            @if($sertifikat->status == 'aktif')
                                <span class="badge badge-status badge-aktif">
                                    <i class="bi bi-check-circle-fill me-1"></i> Aktif
                                </span>
                            @elseif($sertifikat->status == 'expired')
                                <span class="badge badge-status badge-expired">
                                    <i class="bi bi-clock-fill me-1"></i> Kadaluarsa
                                </span>
                            @elseif($sertifikat->status == 'revoked')
                                <span class="badge badge-status badge-revoked">
                                    <i class="bi bi-x-circle-fill me-1"></i> Dicabut
                                </span>
                            @else
                                <span class="badge badge-status badge-secondary">
                                    {{ ucfirst($sertifikat->status) }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="certificate-card-body">
                        <div class="certificate-number">
                            <i class="bi bi-hash"></i>
                            {{ $sertifikat->nomor_sertifikat }}
                        </div>

                        <h5 class="certificate-title">{{ $sertifikat->judul ?? $sertifikat->nama_sertifikat }}</h5>
                        
                        @if($sertifikat->training)
                        <div class="certificate-training">
                            <i class="bi bi-journal-bookmark"></i>
                            {{ $sertifikat->training->judul }}
                        </div>
                        @endif

                        @if($sertifikat->deskripsi)
                        <p class="certificate-description">
                            {{ Str::limit($sertifikat->deskripsi, 80) }}
                        </p>
                        @endif

                        <div class="certificate-info">
                            <div class="info-item">
                                <i class="bi bi-calendar-check"></i>
                                <span>Terbit: {{ $sertifikat->tanggal_terbit ? $sertifikat->tanggal_terbit->format('d/m/Y') : '-' }}</span>
                            </div>
                            @if($sertifikat->tanggal_berlaku_sampai)
                            <div class="info-item">
                                <i class="bi bi-calendar-x"></i>
                                <span>Berlaku s/d: {{ $sertifikat->tanggal_berlaku_sampai->format('d/m/Y') }}</span>
                            </div>
                            @endif
                            <div class="info-item">
                                <i class="bi bi-person"></i>
                                <span>{{ $sertifikat->penerbit ?? 'Dinas Koperindag' }}</span>
                            </div>
                        </div>

                        <div class="certificate-actions">
                            <!-- Tombol untuk membuka modal detail -->
                            <button type="button" class="btn btn-outline-primary btn-sm" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#certificateModal{{ $sertifikat->id }}">
                                <i class="bi bi-eye me-1"></i> Detail
                            </button>
                            @if($sertifikat->file_path && $sertifikat->status == 'aktif')
                                <a href="{{ route('peserta.sertifikat.download', $sertifikat->id) }}" 
                                   class="btn btn-success btn-sm" target="_blank">
                                    <i class="bi bi-download me-1"></i> Unduh
                                </a>
                            @endif
                            @if($sertifikat->status == 'aktif' && !$sertifikat->file_path)
                                <span class="btn btn-secondary btn-sm disabled">
                                    <i class="bi bi-clock me-1"></i> Diproses
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- ============================================================
                 MODAL DETAIL SERTIFIKAT
            ============================================================ -->
            <div class="modal fade" id="certificateModal{{ $sertifikat->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header border-0 pb-0">
                            <div class="d-flex align-items-center gap-3">
                                <div class="certificate-modal-icon">
                                    <i class="bi bi-award-fill"></i>
                                </div>
                                <div>
                                    <h5 class="modal-title fw-bold">Detail Sertifikat</h5>
                                    <p class="text-muted small mb-0">Informasi lengkap sertifikat Anda</p>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body px-4 py-3">
                            <!-- Status Banner -->
                            <div class="certificate-status-banner mb-3">
                                @if($sertifikat->status == 'aktif')
                                    <div class="alert alert-success mb-0 d-flex align-items-center gap-2">
                                        <i class="bi bi-check-circle-fill fs-5"></i>
                                        <div>
                                            <strong>Sertifikat Aktif</strong>
                                            <span class="d-block small">Sertifikat ini masih berlaku</span>
                                        </div>
                                    </div>
                                @elseif($sertifikat->status == 'expired')
                                    <div class="alert alert-warning mb-0 d-flex align-items-center gap-2">
                                        <i class="bi bi-clock-fill fs-5"></i>
                                        <div>
                                            <strong>Sertifikat Kadaluarsa</strong>
                                            <span class="d-block small">Sertifikat ini sudah melewati masa berlaku</span>
                                        </div>
                                    </div>
                                @elseif($sertifikat->status == 'revoked')
                                    <div class="alert alert-danger mb-0 d-flex align-items-center gap-2">
                                        <i class="bi bi-x-circle-fill fs-5"></i>
                                        <div>
                                            <strong>Sertifikat Dicabut</strong>
                                            <span class="d-block small">Sertifikat ini telah dicabut oleh penerbit</span>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Informasi Sertifikat -->
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="info-group">
                                        <label class="info-label">Nomor Sertifikat</label>
                                        <div class="info-value font-monospace">{{ $sertifikat->nomor_sertifikat }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-group">
                                        <label class="info-label">Nama Sertifikat</label>
                                        <div class="info-value fw-semibold">{{ $sertifikat->judul ?? $sertifikat->nama_sertifikat }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-group">
                                        <label class="info-label">Pelatihan</label>
                                        <div class="info-value">
                                            @if($sertifikat->training)
                                                {{ $sertifikat->training->judul }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-group">
                                        <label class="info-label">Status</label>
                                        <div class="info-value">
                                            @if($sertifikat->status == 'aktif')
                                                <span class="badge bg-success">Aktif</span>
                                            @elseif($sertifikat->status == 'expired')
                                                <span class="badge bg-warning text-dark">Kadaluarsa</span>
                                            @elseif($sertifikat->status == 'revoked')
                                                <span class="badge bg-danger">Dicabut</span>
                                            @else
                                                <span class="badge bg-secondary">{{ ucfirst($sertifikat->status) }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-group">
                                        <label class="info-label">Tanggal Terbit</label>
                                        <div class="info-value">
                                            <i class="bi bi-calendar-check me-1"></i>
                                            {{ $sertifikat->tanggal_terbit ? $sertifikat->tanggal_terbit->format('d/m/Y H:i') : '-' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-group">
                                        <label class="info-label">Berlaku Sampai</label>
                                        <div class="info-value">
                                            @if($sertifikat->tanggal_berlaku_sampai)
                                                <i class="bi bi-calendar-x me-1"></i>
                                                {{ $sertifikat->tanggal_berlaku_sampai->format('d/m/Y') }}
                                            @else
                                                <span class="text-muted">Tidak berlaku</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-group">
                                        <label class="info-label">Penerbit</label>
                                        <div class="info-value">
                                            <i class="bi bi-building me-1"></i>
                                            {{ $sertifikat->penerbit ?? 'Dinas Koperindag Prov. Sulawesi Barat' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-group">
                                        <label class="info-label">ID Peserta</label>
                                        <div class="info-value">
                                            <i class="bi bi-person me-1"></i>
                                            {{ $sertifikat->peserta_id ?? auth()->user()->id ?? '-' }}
                                        </div>
                                    </div>
                                </div>
                                @if($sertifikat->deskripsi)
                                <div class="col-12">
                                    <div class="info-group">
                                        <label class="info-label">Deskripsi</label>
                                        <div class="info-value">{{ $sertifikat->deskripsi }}</div>
                                    </div>
                                </div>
                                @endif
                                @if($sertifikat->catatan)
                                <div class="col-12">
                                    <div class="info-group">
                                        <label class="info-label">Catatan</label>
                                        <div class="info-value text-muted">{{ $sertifikat->catatan }}</div>
                                    </div>
                                </div>
                                @endif
                            </div>

                            <!-- QR Code / Preview Section -->
                            @if($sertifikat->file_path)
                            <div class="certificate-preview mt-3 pt-3 border-top">
                                <div class="d-flex align-items-center gap-3 flex-wrap">
                                    <div class="certificate-preview-icon">
                                        <i class="bi bi-file-pdf"></i>
                                    </div>
                                    <div>
                                        <p class="mb-0 fw-semibold">File Sertifikat</p>
                                        <p class="text-muted small mb-0">
                                            <i class="bi bi-file-earmark-pdf me-1"></i>
                                            Sertifikat dalam format PDF
                                        </p>
                                    </div>
                                  
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="modal-footer border-0 pt-0">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="bi bi-x-circle me-1"></i> Tutup
                            </button>
                            @if($sertifikat->file_path && $sertifikat->status == 'aktif')
                                <a href="{{ route('peserta.sertifikat.download', $sertifikat->id) }}" 
                                   class="btn btn-success" target="_blank">
                                    <i class="bi bi-download me-1"></i> Unduh Sertifikat
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
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="bi bi-award"></i>
            </div>
            <h5 class="empty-state-title">Belum ada sertifikat</h5>
            <p class="empty-state-description">
                @if(request('search'))
                    Tidak ada sertifikat yang sesuai dengan pencarian "{{ request('search') }}".
                @elseif(request('filter') == 'aktif')
                    Anda belum memiliki sertifikat yang aktif.
                @elseif(request('filter') == 'expired')
                    Anda belum memiliki sertifikat yang kadaluarsa.
                @elseif(request('filter') == 'revoked')
                    Anda belum memiliki sertifikat yang dicabut.
                @else
                    Ikuti pelatihan dan selesaikan untuk mendapatkan sertifikat.
                @endif
            </p>
            @if(request('search') || request('filter'))
            <a href="{{ route('peserta.sertifikat.index') }}" class="btn btn-primary btn-sm mt-2">
                <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Filter
            </a>
            @endif
            @if(!request('search') && !request('filter') && $sertifikats->count() == 0)
            <a href="{{ route('peserta.trainings.index') }}" class="btn btn-success btn-sm mt-2">
                <i class="bi bi-plus-circle me-1"></i> Ikuti Pelatihan
            </a>
            @endif
        </div>
    @endif
</div>

@push('styles')
<style>
    /* ============================================================
       CERTIFICATE CARD
    ============================================================ */
    .certificate-card {
        background: #fff;
        border-radius: 1rem;
        box-shadow: 0 1px 4px rgba(0,0,0,.06);
        overflow: hidden;
        height: 100%;
        transition: all 0.3s ease;
        border: 1px solid rgba(0,0,0,0.04);
        display: flex;
        flex-direction: column;
    }
    .certificate-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 40px rgba(0,0,0,0.08);
        border-color: transparent;
    }

    .certificate-card-header {
        padding: 1rem 1.25rem 0.5rem;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        border-bottom: 1px solid #f0f0f0;
    }

    .certificate-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        background: linear-gradient(135deg, #e8f4f8, #b8dce8);
        color: #4e9af1;
        flex-shrink: 0;
    }

    .certificate-status .badge-status {
        font-weight: 500;
        padding: 0.35rem 0.7rem;
        font-size: 0.7rem;
        border-radius: 6px;
    }
    .badge-aktif {
        background: #d4edda;
        color: #155724;
    }
    .badge-expired {
        background: #fff3cd;
        color: #856404;
    }
    .badge-revoked {
        background: #f8d7da;
        color: #721c24;
    }

    .certificate-card-body {
        padding: 1rem 1.25rem 1.25rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .certificate-number {
        font-size: 0.7rem;
        font-family: 'IBM Plex Mono', monospace;
        color: #8a93a3;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
    .certificate-number i {
        font-size: 0.8rem;
    }

    .certificate-title {
        font-weight: 700;
        font-size: 1rem;
        margin-bottom: 0.25rem;
        color: #1a2236;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .certificate-training {
        font-size: 0.8rem;
        color: #6c757d;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }
    .certificate-training i {
        color: #4e9af1;
    }

    .certificate-description {
        color: #6c757d;
        font-size: 0.85rem;
        margin-bottom: 0.75rem;
        flex: 1;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        line-height: 1.5;
    }

    .certificate-info {
        display: flex;
        flex-direction: column;
        gap: 0.2rem;
        padding: 0.5rem 0;
        border-top: 1px solid #f0f0f0;
        border-bottom: 1px solid #f0f0f0;
        margin-bottom: 0.75rem;
    }
    .info-item {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.75rem;
        color: #6c757d;
    }
    .info-item i {
        font-size: 0.8rem;
        color: #4e9af1;
        width: 16px;
        flex-shrink: 0;
    }

    .certificate-actions {
        display: flex;
        gap: 0.5rem;
        margin-top: auto;
    }
    .certificate-actions .btn {
        font-size: 0.8rem;
        padding: 0.4rem 0.75rem;
        border-radius: 8px;
        flex: 1;
    }
    .certificate-actions .btn-sm {
        min-height: 34px;
    }

    /* ============================================================
       MODAL STYLES
    ============================================================ */
    .certificate-modal-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        background: linear-gradient(135deg, #e8f4f8, #b8dce8);
        color: #4e9af1;
        flex-shrink: 0;
    }
    .modal-content {
        border-radius: 16px;
        border: none;
        box-shadow: 0 20px 60px rgba(0,0,0,0.15);
    }
    .modal-header {
        padding: 1.25rem 1.5rem 0.5rem;
    }
    .modal-body {
        padding: 1rem 1.5rem 1.5rem;
    }
    .modal-footer {
        padding: 0.75rem 1.5rem 1.25rem;
    }

    .info-group {
        margin-bottom: 0.75rem;
    }
    .info-label {
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #8a93a3;
        display: block;
        margin-bottom: 0.15rem;
    }
    .info-value {
        font-size: 0.95rem;
        color: #1a2236;
        padding: 0.25rem 0;
    }
    .info-value .badge {
        font-size: 0.75rem;
        padding: 0.35rem 0.7rem;
    }

    .certificate-status-banner .alert {
        border-radius: 10px;
        padding: 0.75rem 1rem;
    }
    .certificate-status-banner .alert i {
        font-size: 1.2rem;
    }

    .certificate-preview {
        background: #f8fafc;
        border-radius: 10px;
        padding: 0.75rem 1rem;
    }
    .certificate-preview-icon {
        font-size: 2rem;
        color: #dc3545;
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
        transition: transform 0.2s ease;
    }
    .metric-card:hover {
        transform: translateY(-4px);
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

    /* ============================================================
       EMPTY STATE
    ============================================================ */
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        background: #fff;
        border-radius: 1rem;
        border: 1px solid rgba(0,0,0,0.04);
    }
    .empty-state-icon {
        font-size: 3rem;
        color: #c3cad6;
        margin-bottom: 1rem;
    }
    .empty-state-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1a2236;
        margin-bottom: 0.5rem;
    }
    .empty-state-description {
        color: #8a93a3;
        font-size: 0.9rem;
        max-width: 400px;
        margin: 0 auto;
    }

    /* ============================================================
       RESPONSIVE
    ============================================================ */
    @media (max-width: 768px) {
        .certificate-card-header {
            flex-direction: column;
            gap: 0.5rem;
        }
        .certificate-card-body {
            padding: 0.75rem 1rem 1rem;
        }
        .certificate-title {
            font-size: 0.9rem;
        }
        .metric-value {
            font-size: 1.2rem;
        }
        .panel-header {
            flex-direction: column;
            align-items: flex-start;
        }
        .certificate-actions .btn {
            font-size: 0.7rem;
            padding: 0.3rem 0.5rem;
        }
        .modal-body {
            padding: 0.75rem 1rem 1rem;
        }
        .info-value {
            font-size: 0.85rem;
        }
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

    // Auto show modal if there's a certificate ID in URL
    @if(request('show'))
        setTimeout(function() {
            const modal = new bootstrap.Modal(document.getElementById('certificateModal{{ request('show') }}'));
            modal.show();
        }, 500);
    @endif
});
</script>
@endpush
@endsection