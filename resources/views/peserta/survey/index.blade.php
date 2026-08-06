@extends('layouts.peserta')

@section('title', 'Survey Pelatihan')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-ui-radios"></i></span>
        <div>
            <p class="eyebrow mb-1">Peserta</p>
            <h1 class="h3 mb-0">Survey Kepuasan Pelatihan</h1>
            <p class="text-muted mb-0">Berikan umpan balik Anda mengenai pelatihan yang telah diikuti</p>
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
                    <span class="metric-label">Total Survey</span>
                    <span class="metric-icon"><i class="bi bi-ui-radios"></i></span>
                </div>
                <div class="metric-value">{{ $totalSurvey ?? $surveys->total() ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-success">Tersedia</span>
                    <span>survey</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-success">
                <div class="metric-top">
                    <span class="metric-label">Sudah Diisi</span>
                    <span class="metric-icon"><i class="bi bi-check-circle-fill"></i></span>
                </div>
                <div class="metric-value">{{ $respondedSurvey ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-success">Selesai</span>
                    <span>survey</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-warning">
                <div class="metric-top">
                    <span class="metric-label">Belum Diisi</span>
                    <span class="metric-icon"><i class="bi bi-clock-fill"></i></span>
                </div>
                <div class="metric-value">{{ $pendingSurvey ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-warning">Menunggu</span>
                    <span>survey</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-info">
                <div class="metric-top">
                    <span class="metric-label">Pelatihan</span>
                    <span class="metric-icon"><i class="bi bi-journal-bookmark-fill"></i></span>
                </div>
                <div class="metric-value">{{ $totalTrainings ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-info">Diikuti</span>
                    <span>pelatihan</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="panel mb-4">
        <div class="panel-body p-3">
            <form action="{{ route('peserta.survey.index') }}" method="GET" class="row g-2 align-items-end">
                <div class="col-12 col-md-5">
                    <label class="form-label fw-semibold small mb-1">
                        <i class="bi bi-search me-1"></i> Cari Survey
                    </label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Cari judul survey..." value="{{ request('search') }}">
                        @if(request('search'))
                        <a href="{{ route('peserta.survey.index') }}" class="btn btn-outline-secondary" title="Hapus pencarian">
                            <i class="bi bi-x-lg"></i>
                        </a>
                        @endif
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label fw-semibold small mb-1">
                        <i class="bi bi-funnel me-1"></i> Filter Pelatihan
                    </label>
                    <select name="training_id" class="form-select">
                        <option value="">Semua Pelatihan</option>
                        @foreach($trainings ?? [] as $training)
                            <option value="{{ $training->id }}" {{ request('training_id') == $training->id ? 'selected' : '' }}>
                                {{ $training->judul }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label fw-semibold small mb-1">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="bi bi-search me-1"></i> Terapkan
                        </button>
                        <a href="{{ route('peserta.survey.index') }}" class="btn btn-outline-secondary" title="Reset Filter">
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
                    <a href="{{ route('peserta.survey.index') }}" class="text-danger ms-1 text-decoration-none">
                        <i class="bi bi-x-circle"></i> Reset semua
                    </a>
                </small>
            </div>
            @endif
        </div>
    </div>

    <!-- Survey Cards -->
    @if($surveys->count() > 0)
    <div class="row g-4">
        @foreach($surveys as $survey)
        @php
            $hasResponded = $survey->responses->isNotEmpty();
            $statusClass = $hasResponded ? 'survey-done' : 'survey-pending';
        @endphp
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card-survey {{ $statusClass }}">
                <!-- Card Header -->
                <div class="card-survey-header">
                    <div class="survey-icon-wrapper">
                        <div class="survey-icon">
                            <i class="bi bi-clipboard-check"></i>
                        </div>
                    </div>
                    <div class="survey-status-badge">
                        @if($hasResponded)
                            <span class="badge bg-success">
                                <i class="bi bi-check-circle-fill me-1"></i> Selesai
                            </span>
                        @else
                            <span class="badge bg-warning text-dark">
                                <i class="bi bi-clock-fill me-1"></i> Menunggu
                            </span>
                        @endif
                    </div>
                </div>
                
                <!-- Card Body -->
                <div class="card-survey-body">
                    <div class="survey-training">
                        <i class="bi bi-journal-bookmark-fill me-1"></i>
                        {{ Str::limit($survey->training->judul ?? 'Tanpa Pelatihan', 30) }}
                    </div>
                    <h5 class="survey-title">{{ $survey->judul }}</h5>
                    
                    @if($survey->deskripsi)
                        <p class="survey-description">{{ Str::limit($survey->deskripsi, 100) }}</p>
                    @else
                        <p class="survey-description text-muted fst-italic">Tidak ada deskripsi</p>
                    @endif
                    
                    <!-- Meta Info -->
                    <div class="survey-meta-info">
                        <span>
                            <i class="bi bi-clock me-1"></i>
                            {{ $survey->created_at ? $survey->created_at->diffForHumans() : '-' }}
                        </span>
                        <span>
                            <i class="bi bi-question-circle me-1"></i>
                            {{ $survey->questions->count() }} Pertanyaan
                        </span>
                    </div>
                </div>
                
                <!-- Card Footer -->
                <div class="card-survey-footer">
                    @if($hasResponded)
                        <button class="btn btn-success btn-survey" disabled>
                            <i class="bi bi-check-circle me-1"></i> Sudah Diisi
                        </button>
                    @else
                        <a href="{{ route('peserta.survey.show', $survey->id) }}" class="btn btn-primary btn-survey">
                            <i class="bi bi-pencil-square me-1"></i> Isi Survey
                        </a>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($surveys->hasPages())
    <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mt-4">
        <p class="text-muted small mb-0">
            <i class="bi bi-info-circle me-1"></i>
            Menampilkan {{ $surveys->firstItem() ?? 0 }} sampai {{ $surveys->lastItem() ?? 0 }} 
            dari {{ $surveys->total() ?? 0 }} survey
        </p>
        <nav aria-label="Survey pagination">
            {{ $surveys->appends(request()->query())->links() }}
        </nav>
    </div>
    @endif

    @else
    <!-- Empty State -->
    <div class="panel">
        <div class="panel-body text-center py-5">
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="bi bi-clipboard-x"></i>
                </div>
                <h4 class="empty-state-title">Belum Ada Survey</h4>
                <p class="empty-state-text">
                    @if(request('search') || request('training_id'))
                        Tidak ada survey yang sesuai dengan filter yang Anda pilih.
                    @else
                        Saat ini belum ada survey kepuasan yang tersedia untuk pelatihan Anda.
                    @endif
                </p>
                @if(request('search') || request('training_id'))
                <a href="{{ route('peserta.survey.index') }}" class="btn btn-outline-primary mt-3">
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
        background: linear-gradient(135deg, #eaf1fd, #d4e4f7);
        color: #4e9af1;
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
       CARD SURVEY
    ============================================================ */
    .card-survey {
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
    .card-survey:hover {
        transform: translateY(-8px);
        box-shadow: 0 16px 40px rgba(0,0,0,0.10);
        border-color: #d4e4f7;
    }
    
    .card-survey.survey-done {
        border-left: 4px solid #28c76f;
    }
    .card-survey.survey-pending {
        border-left: 4px solid #ff9f43;
    }
    
    .card-survey-header {
        padding: 1rem 1.25rem 0.75rem 1.25rem;
        background: linear-gradient(135deg, #f8fafc, #f0f4f8);
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }
    
    .survey-icon-wrapper {
        flex-shrink: 0;
    }
    
    .survey-icon {
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
    .card-survey:hover .survey-icon {
        transform: scale(1.05) rotate(-3deg);
        box-shadow: 0 4px 12px rgba(78, 154, 241, 0.2);
    }
    
    .survey-status-badge .badge {
        padding: 0.35rem 0.75rem;
        font-size: 0.7rem;
        font-weight: 500;
        border-radius: 20px;
    }
    .badge.bg-success {
        background: #d4edda !important;
        color: #155724 !important;
    }
    .badge.bg-warning {
        background: #fff3cd !important;
        color: #856404 !important;
    }
    
    .card-survey-body {
        padding: 1rem 1.25rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    
    .survey-training {
        font-size: 0.75rem;
        color: #8a93a3;
        margin-bottom: 0.25rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
    .survey-training i {
        font-size: 0.7rem;
    }
    
    .survey-title {
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
    
    .survey-description {
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
    
    .survey-meta-info {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        font-size: 0.7rem;
        color: #8a93a3;
        padding-top: 0.5rem;
        border-top: 1px solid #f0f0f0;
    }
    .survey-meta-info span {
        display: flex;
        align-items: center;
    }
    .survey-meta-info i {
        font-size: 0.7rem;
    }
    
    .card-survey-footer {
        padding: 0.75rem 1.25rem 1.25rem 1.25rem;
        border-top: 1px solid #f0f0f0;
        background: #fafbfc;
    }
    
    .btn-survey {
        width: 100%;
        border-radius: 8px;
        padding: 0.5rem;
        font-size: 0.85rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    .btn-survey.btn-primary {
        background: #4e9af1;
        border-color: #4e9af1;
        color: #fff;
    }
    .btn-survey.btn-primary:hover {
        background: #3d8ae0;
        border-color: #3d8ae0;
        transform: scale(1.02);
        box-shadow: 0 4px 16px rgba(78, 154, 241, 0.35);
    }
    .btn-survey.btn-success {
        background: #d4edda;
        border-color: #d4edda;
        color: #155724;
        cursor: default;
        opacity: 0.8;
    }
    .btn-survey.btn-success i {
        color: #155724;
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
        padding: 0.4rem 1.2rem;
        font-weight: 500;
        font-size: 0.85rem;
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
    .card-survey {
        animation: fadeInUp 0.5s ease forwards;
        opacity: 0;
    }
    
    .card-survey:nth-child(1) { animation-delay: 0.05s; }
    .card-survey:nth-child(2) { animation-delay: 0.10s; }
    .card-survey:nth-child(3) { animation-delay: 0.15s; }
    .card-survey:nth-child(4) { animation-delay: 0.20s; }
    .card-survey:nth-child(5) { animation-delay: 0.25s; }
    .card-survey:nth-child(6) { animation-delay: 0.30s; }
    .card-survey:nth-child(7) { animation-delay: 0.35s; }
    .card-survey:nth-child(8) { animation-delay: 0.40s; }
    .card-survey:nth-child(9) { animation-delay: 0.45s; }
    .card-survey:nth-child(10) { animation-delay: 0.50s; }
    .card-survey:nth-child(11) { animation-delay: 0.55s; }
    .card-survey:nth-child(12) { animation-delay: 0.60s; }
    
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
        .card-survey-header {
            flex-direction: column;
            gap: 0.5rem;
        }
        .survey-status-badge {
            width: 100%;
        }
        .survey-status-badge .badge {
            width: 100%;
            text-align: center;
        }
        .survey-meta-info {
            flex-direction: column;
            gap: 0.3rem;
        }
        .btn-survey {
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
        .card-survey-body {
            padding: 0.75rem 1rem;
        }
        .card-survey-header {
            padding: 0.75rem 1rem;
        }
        .card-survey-footer {
            padding: 0.5rem 1rem 1rem 1rem;
        }
        .survey-title {
            font-size: 0.95rem;
        }
        .survey-description {
            font-size: 0.8rem;
        }
        .empty-state-icon {
            font-size: 3.5rem;
        }
    }
</style>
@endpush
@endsection