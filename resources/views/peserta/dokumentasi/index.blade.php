@extends('layouts.peserta')

@section('title', 'Dokumentasi Pelatihan')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-images me-2"></i></span>
        <div>
            <p class="eyebrow mb-1">Peserta</p>
            <h1 class="h3 mb-0">Dokumentasi Pelatihan</h1>
            <p class="text-muted mb-0">Kumpulan link dokumentasi dari pelatihan yang Anda ikuti</p>
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
                    <span class="metric-label">Total Dokumentasi</span>
                    <span class="metric-icon"><i class="bi bi-file-earmark-image"></i></span>
                </div>
                <div class="metric-value">{{ $totalDokumentasi ?? $dokumentasis->total() ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-success">Tersedia</span>
                    <span>dokumentasi</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-success">
                <div class="metric-top">
                    <span class="metric-label">Pelatihan Aktif</span>
                    <span class="metric-icon"><i class="bi bi-journal-bookmark-fill"></i></span>
                </div>
                <div class="metric-value">{{ $totalTrainings ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-success">Diikuti</span>
                    <span>pelatihan</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-info">
                <div class="metric-top">
                    <span class="metric-label">Terbaru</span>
                    <span class="metric-icon"><i class="bi bi-clock-fill"></i></span>
                </div>
                <div class="metric-value">{{ $newDokumentasi ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-info">7 hari</span>
                    <span>terakhir</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-secondary">
                <div class="metric-top">
                    <span class="metric-label">Link</span>
                    <span class="metric-icon"><i class="bi bi-link-45deg"></i></span>
                </div>
                <div class="metric-value">{{ $totalLinks ?? $dokumentasis->count() ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-secondary">Total</span>
                    <span>link tersedia</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Filter (Tombol Terapkan diperkecil) -->
    <div class="panel mb-4">
        <div class="panel-body p-3">
            <form action="{{ route('peserta.dokumentasi.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-12 col-md-5">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Cari judul atau deskripsi..." value="{{ request('search') }}">
                        @if(request('search'))
                        <a href="{{ route('peserta.dokumentasi.index') }}" class="btn btn-outline-secondary" title="Hapus pencarian">
                            <i class="bi bi-x-lg"></i>
                        </a>
                        @endif
                    </div>
                </div>
                <div class="col-12 col-md-5">
                    <select name="training_id" class="form-select form-select-sm">
                        <option value="">Semua Pelatihan</option>
                        @foreach($trainings ?? [] as $training)
                            <option value="{{ $training->id }}" {{ request('training_id') == $training->id ? 'selected' : '' }}>
                                {{ $training->judul }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-2">
                    <div class="d-flex gap-1 justify-content-md-end">
                        <button type="submit" class="btn btn-primary btn-sm px-2" title="Terapkan Filter">
                            <i class="bi bi-search me-1"></i> Cari
                        </button>
                        <a href="{{ route('peserta.dokumentasi.index') }}" class="btn btn-outline-secondary btn-sm px-2" title="Reset Filter">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    </div>
                </div>
            </form>
            
            <!-- Active Filters -->
            @if(request('search') || request('training_id'))
            <div class="mt-2 pt-2 border-top">
                <small class="text-muted">
                    <i class="bi bi-filter-circle me-1"></i>
                    Filter aktif: 
                    @if(request('search'))
                        <span class="badge bg-primary bg-opacity-10 text-primary">
                            <i class="bi bi-search me-1"></i> {{ request('search') }}
                        </span>
                    @endif
                    @if(request('training_id'))
                        @php
                            $trainingName = $trainings->where('id', request('training_id'))->first();
                        @endphp
                        <span class="badge bg-primary bg-opacity-10 text-primary">
                            <i class="bi bi-journal-bookmark-fill me-1"></i> {{ $trainingName ? $trainingName->judul : '' }}
                        </span>
                    @endif
                    <a href="{{ route('peserta.dokumentasi.index') }}" class="text-danger ms-1 text-decoration-none">
                        <i class="bi bi-x-circle"></i> Reset semua
                    </a>
                </small>
            </div>
            @endif
        </div>
    </div>

    <!-- Dokumentasi Cards -->
    @if($dokumentasis->count() > 0)
    <div class="row g-4">
        @foreach($dokumentasis as $doc)
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card-dokumentasi">
                <!-- Card Header with Image Icon -->
                <div class="card-dokumentasi-header">
                    <div class="doc-icon-wrapper">
                        <div class="doc-icon">
                            <i class="bi bi-image"></i>
                        </div>
                    </div>
                    <div class="doc-training-badge">
                        <i class="bi bi-journal-bookmark-fill me-1"></i>
                        {{ Str::limit($doc->training->judul ?? 'Tanpa Pelatihan', 30) }}
                    </div>
                </div>
                
                <!-- Card Body -->
                <div class="card-dokumentasi-body">
                    <h5 class="doc-title">{{ $doc->judul }}</h5>
                    
                    @if($doc->deskripsi)
                        <p class="doc-description">{{ Str::limit($doc->deskripsi, 100) }}</p>
                    @else
                        <p class="doc-description text-muted fst-italic">Tidak ada deskripsi</p>
                    @endif
                    
                    <!-- Meta Info -->
                    <div class="doc-meta-info">
                        <span>
                            <i class="bi bi-clock me-1"></i>
                            {{ $doc->created_at ? $doc->created_at->diffForHumans() : '-' }}
                        </span>
                        <span>
                            <i class="bi bi-link-45deg me-1"></i>
                            {{ Str::limit($doc->link, 30) }}
                        </span>
                    </div>
                </div>
                
                <!-- Card Footer -->
                <div class="card-dokumentasi-footer">
                    <a href="{{ $doc->link }}" target="_blank" class="btn btn-primary btn-doc">
                        <i class="bi bi-box-arrow-up-right me-1"></i> Buka Dokumentasi
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($dokumentasis->hasPages())
    <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mt-4">
        <p class="text-muted small mb-0">
            <i class="bi bi-info-circle me-1"></i>
            Menampilkan {{ $dokumentasis->firstItem() ?? 0 }} sampai {{ $dokumentasis->lastItem() ?? 0 }} 
            dari {{ $dokumentasis->total() ?? 0 }} dokumentasi
        </p>
        <nav aria-label="Dokumentasi pagination">
            {{ $dokumentasis->appends(request()->query())->links() }}
        </nav>
    </div>
    @endif

    @else
    <!-- Empty State -->
    <div class="panel">
        <div class="panel-body text-center py-5">
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="bi bi-folder-x"></i>
                </div>
                <h4 class="empty-state-title">Belum Ada Dokumentasi</h4>
                <p class="empty-state-text">
                    @if(request('search') || request('training_id'))
                        Tidak ada dokumentasi yang sesuai dengan filter yang Anda pilih.
                    @else
                        Saat ini belum ada link dokumentasi untuk pelatihan yang Anda ikuti.
                    @endif
                </p>
                @if(request('search') || request('training_id'))
                <a href="{{ route('peserta.dokumentasi.index') }}" class="btn btn-outline-primary mt-3">
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
    /* ============================================================
       PAGE HEADING
    ============================================================ */
    .page-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        padding: 1.25rem 1.5rem;
        background: #fff;
        border-bottom: 1px solid #f0f0f0;
    }
    .page-heading-copy {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .page-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #e0f9fb, #bdf0f6);
        color: #0dcaf0;
        font-size: 1.5rem;
        flex-shrink: 0;
    }
    .eyebrow {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #8a93a3;
        font-weight: 600;
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
        transition: all 0.3s ease;
    }
    .metric-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    }
    .metric-primary { border-left-color: #4e9af1; }
    .metric-success { border-left-color: #28c76f; }
    .metric-info { border-left-color: #17a2b8; }
    .metric-secondary { border-left-color: #8a93a3; }
    
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
        font-size: 1.75rem;
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
    .panel-body {
        background: #fff;
    }

    /* ============================================================
       CARD DOKUMENTASI
    ============================================================ */
    .card-dokumentasi {
        background: #fff;
        border-radius: 0.75rem;
        box-shadow: 0 1px 4px rgba(0,0,0,.06);
        transition: all 0.3s ease;
        height: 100%;
        overflow: hidden;
        border: 1px solid #f0f0f0;
        display: flex;
        flex-direction: column;
    }
    .card-dokumentasi:hover {
        transform: translateY(-8px);
        box-shadow: 0 16px 40px rgba(0,0,0,0.10);
        border-color: #d4e4f7;
    }
    
    .card-dokumentasi-header {
        padding: 1rem 1.25rem 0.75rem 1.25rem;
        background: linear-gradient(135deg, #f8fafc, #f0f4f8);
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }
    
    .doc-icon-wrapper {
        flex-shrink: 0;
    }
    
    .doc-icon {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, #eaf1fd, #d4e4f7);
        color: #4e9af1;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        transition: all 0.3s ease;
    }
    .card-dokumentasi:hover .doc-icon {
        transform: scale(1.05) rotate(-3deg);
        box-shadow: 0 4px 12px rgba(78, 154, 241, 0.2);
    }
    
    .doc-training-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        background: #fff;
        color: #4a5568;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 500;
        border: 1px solid #e2e8f0;
        max-width: 60%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        box-shadow: 0 1px 2px rgba(0,0,0,0.04);
    }
    
    .card-dokumentasi-body {
        padding: 1rem 1.25rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    
    .doc-title {
        font-size: 1.05rem;
        font-weight: 600;
        color: #1a2236;
        margin: 0 0 0.5rem 0;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .doc-description {
        color: #6c757d;
        font-size: 0.85rem;
        margin: 0 0 0.75rem 0;
        line-height: 1.5;
        flex: 1;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .doc-meta-info {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        font-size: 0.7rem;
        color: #8a93a3;
        padding-top: 0.5rem;
        border-top: 1px solid #f0f0f0;
    }
    .doc-meta-info span {
        display: flex;
        align-items: center;
    }
    .doc-meta-info i {
        font-size: 0.7rem;
    }
    
    .card-dokumentasi-footer {
        padding: 0.75rem 1.25rem 1.25rem 1.25rem;
        border-top: 1px solid #f0f0f0;
        background: #fafbfc;
    }
    
    .btn-doc {
        width: 100%;
        border-radius: 8px;
        padding: 0.5rem;
        font-size: 0.85rem;
        font-weight: 500;
        background: #4e9af1;
        border-color: #4e9af1;
        color: #fff;
        transition: all 0.3s ease;
    }
    .btn-doc:hover {
        background: #3d8ae0;
        border-color: #3d8ae0;
        transform: scale(1.02);
        box-shadow: 0 4px 16px rgba(78, 154, 241, 0.35);
    }
    .btn-doc i {
        font-size: 0.9rem;
    }

    /* ============================================================
       EMPTY STATE
    ============================================================ */
    .empty-state-icon {
        font-size: 4.5rem;
        color: #d4e4f7;
        margin-bottom: 0.5rem;
    }
    .empty-state-icon i {
        display: block;
    }
    .empty-state-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #4a5568;
        margin: 0.5rem 0;
    }
    .empty-state-text {
        color: #8a93a3;
        max-width: 400px;
        margin: 0 auto;
    }

    /* ============================================================
       FORM
    ============================================================ */
    .form-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: #4a5568;
        margin-bottom: 0.25rem;
    }
    
    .input-group-text {
        background: #f8fafc;
        border-color: #e2e8f0;
        color: #8a93a3;
        font-size: 0.8rem;
    }
    
    .form-control, .form-select {
        border-color: #e2e8f0;
        border-radius: 0.5rem;
        padding: 0.4rem 0.75rem;
        font-size: 0.85rem;
        transition: all 0.2s ease;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #4e9af1;
        box-shadow: 0 0 0 3px rgba(78, 154, 241, 0.12);
    }
    
    .input-group .form-control {
        border-radius: 0 0.5rem 0.5rem 0;
    }
    
    .input-group .input-group-text:first-child {
        border-radius: 0.5rem 0 0 0.5rem;
    }

    .badge.bg-primary.bg-opacity-10 {
        background: rgba(78, 154, 241, 0.12) !important;
        padding: 0.3rem 0.7rem;
        font-weight: 500;
    }

    /* ============================================================
       BUTTONS
    ============================================================ */
    .btn {
        border-radius: 0.5rem;
        padding: 0.35rem 0.8rem;
        font-weight: 500;
        font-size: 0.8rem;
        transition: all 0.2s ease;
    }
    
    .btn-primary {
        background: #4e9af1;
        border-color: #4e9af1;
        color: #fff;
    }
    .btn-primary:hover {
        background: #3d8ae0;
        border-color: #3d8ae0;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(78, 154, 241, 0.3);
    }
    
    .btn-outline-secondary {
        border-color: #e2e8f0;
        color: #4a5568;
    }
    .btn-outline-secondary:hover {
        background: #f7fafc;
        border-color: #d5dce6;
    }
    
    .btn-outline-primary {
        border-color: #4e9af1;
        color: #4e9af1;
    }
    .btn-outline-primary:hover {
        background: #4e9af1;
        color: #fff;
    }

    /* ============================================================
       PAGINATION
    ============================================================ */
    .pagination {
        margin-bottom: 0;
        gap: 0.25rem;
    }
    .pagination .page-link {
        border-radius: 0.5rem !important;
        border: 1px solid #e2e8f0;
        color: #4a5568;
        padding: 0.35rem 0.75rem;
        font-size: 0.8rem;
        transition: all 0.2s ease;
    }
    .pagination .page-link:hover {
        background: #f7fafc;
        border-color: #4e9af1;
        color: #4e9af1;
    }
    .pagination .page-item.active .page-link {
        background: #4e9af1;
        border-color: #4e9af1;
        color: #fff;
    }
    .pagination .page-item.disabled .page-link {
        color: #c3cad6;
        background: #f8fafc;
        cursor: not-allowed;
    }

    /* ============================================================
       ANIMATION
    ============================================================ */
    .card-dokumentasi {
        animation: fadeInUp 0.5s ease forwards;
        opacity: 0;
    }
    
    .card-dokumentasi:nth-child(1) { animation-delay: 0.05s; }
    .card-dokumentasi:nth-child(2) { animation-delay: 0.10s; }
    .card-dokumentasi:nth-child(3) { animation-delay: 0.15s; }
    .card-dokumentasi:nth-child(4) { animation-delay: 0.20s; }
    .card-dokumentasi:nth-child(5) { animation-delay: 0.25s; }
    .card-dokumentasi:nth-child(6) { animation-delay: 0.30s; }
    .card-dokumentasi:nth-child(7) { animation-delay: 0.35s; }
    .card-dokumentasi:nth-child(8) { animation-delay: 0.40s; }
    .card-dokumentasi:nth-child(9) { animation-delay: 0.45s; }
    .card-dokumentasi:nth-child(10) { animation-delay: 0.50s; }
    .card-dokumentasi:nth-child(11) { animation-delay: 0.55s; }
    .card-dokumentasi:nth-child(12) { animation-delay: 0.60s; }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
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
        .metric-value {
            font-size: 1.4rem;
        }
        .card-dokumentasi-header {
            flex-direction: column;
            gap: 0.5rem;
        }
        .doc-training-badge {
            max-width: 100%;
            width: 100%;
            text-align: center;
        }
        .doc-meta-info {
            flex-direction: column;
            gap: 0.3rem;
        }
        .btn-doc {
            padding: 0.4rem;
            font-size: 0.8rem;
        }
    }

    @media (max-width: 576px) {
        .page-icon {
            width: 44px;
            height: 44px;
            font-size: 1.2rem;
        }
        .card-dokumentasi-body {
            padding: 0.75rem 1rem;
        }
        .card-dokumentasi-header {
            padding: 0.75rem 1rem;
        }
        .card-dokumentasi-footer {
            padding: 0.5rem 1rem 1rem 1rem;
        }
        .doc-title {
            font-size: 0.95rem;
        }
        .doc-description {
            font-size: 0.8rem;
        }
        .empty-state-icon {
            font-size: 3.5rem;
        }
    }
</style>
@endpush
@endsection