@extends('layouts.peserta')

@section('title', 'Sertifikat Pelatihan')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-award"></i></span>
        <div>
            <p class="eyebrow mb-1">Peserta</p>
            <h1 class="h3 mb-0">Sertifikat Saya</h1>
            <p class="text-muted mb-0">Pantau status kelulusan dan unduh sertifikat pelatihan Anda</p>
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
                    <span class="metric-icon"><i class="bi bi-journal-bookmark-fill"></i></span>
                </div>
                <div class="metric-value">{{ $totalPelatihan ?? count($trainingStatus ?? []) }}</div>
                <div class="metric-meta">
                    <span class="text-primary">Diikuti</span>
                    <span>pelatihan</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-success">
                <div class="metric-top">
                    <span class="metric-label">Sertifikat Terbit</span>
                    <span class="metric-icon"><i class="bi bi-patch-check-fill"></i></span>
                </div>
                <div class="metric-value">{{ $totalSertifikat ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-success">Tersedia</span>
                    <span>sertifikat</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-warning">
                <div class="metric-top">
                    <span class="metric-label">Menunggu</span>
                    <span class="metric-icon"><i class="bi bi-hourglass-split"></i></span>
                </div>
                <div class="metric-value">{{ $totalMenunggu ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-warning">Diproses</span>
                    <span>sertifikat</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-danger">
                <div class="metric-top">
                    <span class="metric-label">Belum Lulus</span>
                    <span class="metric-icon"><i class="bi bi-x-circle-fill"></i></span>
                </div>
                <div class="metric-value">{{ $totalBelumLulus ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-danger">Perlu</span>
                    <span>perbaikan</span>
                </div>
            </div>
        </div>
    </div>

    @if(empty($trainingStatus))
        <!-- Empty State -->
        <div class="panel">
            <div class="panel-body text-center py-5">
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="bi bi-award"></i>
                    </div>
                    <h4 class="empty-state-title">Belum Ada Sertifikat</h4>
                    <p class="empty-state-text">
                        Anda belum mengikuti atau menyelesaikan pelatihan apapun yang memiliki sertifikat.
                        Silakan ikuti pelatihan terlebih dahulu untuk mendapatkan sertifikat.
                    </p>
                    <a href="{{ route('peserta.trainings.index') ?? '#' }}" class="btn btn-primary mt-3">
                        <i class="bi bi-journal-bookmark me-1"></i> Lihat Pelatihan
                    </a>
                </div>
            </div>
        </div>
    @else
        <!-- Sertifikat Cards -->
        <div class="row g-4">
            @foreach($trainingStatus as $item)
                @php 
                    $training = $item['training']; 
                    $status = $item['status_sertifikat'];
                @endphp
                <div class="col-12 col-md-6 col-xl-4">
                    <div class="card-sertifikat status-{{ $status }}">
                        <!-- Card Header / Image -->
                        <div class="card-sertifikat-header">
                            @if($training->gambar)
                                <img src="{{ asset('storage/' . $training->gambar) }}" 
                                     alt="{{ $training->judul }}" 
                                     class="card-sertifikat-image">
                            @else
                                <div class="card-sertifikat-placeholder">
                                    <i class="bi bi-image"></i>
                                </div>
                            @endif
                            
                            <!-- Status Badge Overlay -->
                            <div class="card-sertifikat-badge">
                                @if($status === 'sudah_terbit')
                                    <span class="badge-status badge-success">
                                        <i class="bi bi-patch-check-fill me-1"></i> Tersedia
                                    </span>
                                @elseif($status === 'menunggu_terbit')
                                    <span class="badge-status badge-warning">
                                        <i class="bi bi-hourglass-split me-1"></i> Diproses
                                    </span>
                                @else
                                    <span class="badge-status badge-danger">
                                        <i class="bi bi-x-circle-fill me-1"></i> Belum Lulus
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="card-sertifikat-body">
                            <h5 class="card-sertifikat-title">{{ $training->judul }}</h5>

                            @if($status === 'sudah_terbit')
                                <!-- Sertifikat Tersedia -->
                                <div class="status-box status-box-success">
                                    <div class="status-icon">
                                        <i class="bi bi-patch-check-fill"></i>
                                    </div>
                                    <div class="status-content">
                                        <strong>Sertifikat Tersedia</strong>
                                        <small class="d-block text-muted">
                                            No: {{ $item['sertifikat']->nomor_sertifikat }}
                                        </small>
                                    </div>
                                </div>

                            @elseif($status === 'menunggu_terbit')
                                <!-- Menunggu Terbit -->
                                <div class="status-box status-box-warning">
                                    <div class="status-icon">
                                        <i class="bi bi-hourglass-split"></i>
                                    </div>
                                    <div class="status-content">
                                        <strong>Selamat, Anda Lulus!</strong>
                                        <small class="d-block text-muted">
                                            Sertifikat sedang menunggu penerbitan oleh admin
                                        </small>
                                        <span class="badge bg-warning text-dark mt-1">
                                            Nilai: {{ number_format($item['final_score'], 1) }}
                                        </span>
                                    </div>
                                </div>

                            @elseif($status === 'belum_lulus')
                                <!-- Belum Lulus -->
                                <div class="status-box status-box-danger">
                                    <div class="status-icon">
                                        <i class="bi bi-x-circle-fill"></i>
                                    </div>
                                    <div class="status-content">
                                        <strong>Belum Lulus</strong>
                                        <small class="d-block text-muted">
                                            Selesaikan kuis dengan nilai minimal
                                        </small>
                                    </div>
                                </div>

                                <!-- Quiz Details -->
                                <div class="quiz-details">
                                    @foreach($item['quizzes'] as $quizData)
                                        @php 
                                            $sisa = max(0, $quizData['max_attempt'] - $quizData['attempts_count']);
                                            $lulus = $quizData['best_score'] >= $quizData['passing_score'];
                                        @endphp
                                        <div class="quiz-item">
                                            <div class="quiz-header">
                                                <span class="quiz-title">
                                                    <i class="bi bi-question-circle me-1"></i>
                                                    {{ Str::limit($quizData['quiz']->judul, 30) }}
                                                </span>
                                                <span class="badge {{ $lulus ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $quizData['best_score'] }}
                                                </span>
                                            </div>
                                            <div class="quiz-info">
                                                <small class="text-muted">
                                                    Nilai minimum: <strong>{{ $quizData['passing_score'] }}</strong>
                                                </small>
                                                <small class="text-muted">
                                                    Sisa percobaan: 
                                                    <span class="badge {{ $sisa > 0 ? 'bg-info' : 'bg-secondary' }}">
                                                        {{ $sisa }}
                                                    </span>
                                                </small>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <!-- Card Footer -->
                        <div class="card-sertifikat-footer">
                            @if($status === 'sudah_terbit')
                                <a href="{{ route('sertifikat.show', $item['sertifikat']->id) }}" 
                                   class="btn btn-primary btn-sertifikat">
                                    <i class="bi bi-file-earmark-pdf me-1"></i> Lihat & Download PDF
                                </a>
                            @elseif($status === 'menunggu_terbit')
                                <button class="btn btn-secondary btn-sertifikat" disabled>
                                    <i class="bi bi-clock me-1"></i> Sedang Diproses
                                </button>
                            @else
                                <a href="{{ route('trainings.show', $training->id) }}" 
                                   class="btn btn-danger btn-sertifikat">
                                    <i class="bi bi-arrow-right-circle me-1"></i> Lanjutkan Pelatihan
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
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
    .metric-danger { border-left-color: #f56565; }
    
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
    .panel-body {
        background: #fff;
    }

    /* ============================================================
       CARD SERTIFIKAT
    ============================================================ */
    .card-sertifikat {
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
    .card-sertifikat:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 40px rgba(0,0,0,0.10);
        border-color: #d4e4f7;
    }
    
    /* Status border */
    .card-sertifikat.status-sudah_terbit {
        border-top: 4px solid #28c76f;
    }
    .card-sertifikat.status-menunggu_terbit {
        border-top: 4px solid #ff9f43;
    }
    .card-sertifikat.status-belum_lulus {
        border-top: 4px solid #f56565;
    }

    /* Card Header */
    .card-sertifikat-header {
        position: relative;
        height: 150px;
        overflow: hidden;
        background: #f8fafc;
    }
    
    .card-sertifikat-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .card-sertifikat:hover .card-sertifikat-image {
        transform: scale(1.05);
    }
    
    .card-sertifikat-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #f8fafc, #f0f4f8);
        color: #c3cad6;
        font-size: 3rem;
    }
    
    .card-sertifikat-badge {
        position: absolute;
        top: 12px;
        right: 12px;
    }
    
    .badge-status {
        display: inline-flex;
        align-items: center;
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
        backdrop-filter: blur(10px);
    }
    .badge-status.badge-success {
        background: rgba(212, 237, 218, 0.95);
        color: #155724;
    }
    .badge-status.badge-warning {
        background: rgba(255, 243, 205, 0.95);
        color: #856404;
    }
    .badge-status.badge-danger {
        background: rgba(248, 215, 218, 0.95);
        color: #721c24;
    }

    /* Card Body */
    .card-sertifikat-body {
        padding: 1.25rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    
    .card-sertifikat-title {
        font-size: 1.05rem;
        font-weight: 600;
        color: #1a2236;
        margin: 0 0 1rem 0;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Status Box */
    .status-box {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 0.75rem;
        border-radius: 0.5rem;
        margin-bottom: 1rem;
    }
    .status-box-success {
        background: #ecfdf5;
        border-left: 3px solid #28c76f;
    }
    .status-box-warning {
        background: #fffbeb;
        border-left: 3px solid #ff9f43;
    }
    .status-box-danger {
        background: #fef2f2;
        border-left: 3px solid #f56565;
    }
    
    .status-icon {
        font-size: 1.25rem;
        flex-shrink: 0;
    }
    .status-box-success .status-icon { color: #28c76f; }
    .status-box-warning .status-icon { color: #ff9f43; }
    .status-box-danger .status-icon { color: #f56565; }
    
    .status-content {
        flex: 1;
        min-width: 0;
    }
    .status-content strong {
        display: block;
        font-size: 0.9rem;
        color: #1a2236;
        margin-bottom: 0.25rem;
    }
    .status-content small {
        font-size: 0.75rem;
        line-height: 1.4;
    }

    /* Quiz Details */
    .quiz-details {
        margin-top: 0.5rem;
    }
    
    .quiz-item {
        padding: 0.5rem;
        background: #f8fafc;
        border-radius: 0.5rem;
        margin-bottom: 0.5rem;
    }
    .quiz-item:last-child {
        margin-bottom: 0;
    }
    
    .quiz-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.25rem;
    }
    
    .quiz-title {
        font-size: 0.8rem;
        font-weight: 500;
        color: #1a2236;
        flex: 1;
        min-width: 0;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    .quiz-info {
        display: flex;
        justify-content: space-between;
        gap: 0.5rem;
        font-size: 0.7rem;
    }

    /* Card Footer */
    .card-sertifikat-footer {
        padding: 0.75rem 1.25rem 1.25rem 1.25rem;
        border-top: 1px solid #f0f0f0;
        background: #fafbfc;
    }
    
    .btn-sertifikat {
        width: 100%;
        border-radius: 8px;
        padding: 0.5rem;
        font-size: 0.85rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn-sertifikat.btn-primary {
        background: #4e9af1;
        border-color: #4e9af1;
        color: #fff;
    }
    .btn-sertifikat.btn-primary:hover {
        background: #3d8ae0;
        border-color: #3d8ae0;
        transform: scale(1.02);
        box-shadow: 0 4px 16px rgba(78, 154, 241, 0.35);
    }
    
    .btn-sertifikat.btn-danger {
        background: #f56565;
        border-color: #f56565;
        color: #fff;
    }
    .btn-sertifikat.btn-danger:hover {
        background: #e53e3e;
        border-color: #e53e3e;
        transform: scale(1.02);
        box-shadow: 0 4px 16px rgba(245, 101, 101, 0.35);
    }
    
    .btn-sertifikat.btn-secondary {
        background: #e2e8f0;
        border-color: #e2e8f0;
        color: #6c757d;
        cursor: not-allowed;
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
        margin: 0 auto 1rem;
    }

    /* ============================================================
       BUTTONS
    ============================================================ */
    .btn {
        border-radius: 0.5rem;
        padding: 0.45rem 1.2rem;
        font-weight: 500;
        font-size: 0.875rem;
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

    /* ============================================================
       ALERT
    ============================================================ */
    .alert {
        border-radius: 0.75rem;
        border: none;
        padding: 0.75rem 1rem;
    }
    .alert-info {
        background: #e0f4fe;
        color: #0c5460;
    }

    /* ============================================================
       ANIMATION
    ============================================================ */
    .card-sertifikat {
        animation: fadeInUp 0.5s ease forwards;
        opacity: 0;
    }
    
    .card-sertifikat:nth-child(1) { animation-delay: 0.05s; }
    .card-sertifikat:nth-child(2) { animation-delay: 0.10s; }
    .card-sertifikat:nth-child(3) { animation-delay: 0.15s; }
    .card-sertifikat:nth-child(4) { animation-delay: 0.20s; }
    .card-sertifikat:nth-child(5) { animation-delay: 0.25s; }
    .card-sertifikat:nth-child(6) { animation-delay: 0.30s; }
    .card-sertifikat:nth-child(7) { animation-delay: 0.35s; }
    .card-sertifikat:nth-child(8) { animation-delay: 0.40s; }
    .card-sertifikat:nth-child(9) { animation-delay: 0.45s; }
    .card-sertifikat:nth-child(10) { animation-delay: 0.50s; }
    
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
        .card-sertifikat-header {
            height: 120px;
        }
        .card-sertifikat-body {
            padding: 1rem;
        }
        .card-sertifikat-footer {
            padding: 0.5rem 1rem 1rem 1rem;
        }
    }

    @media (max-width: 576px) {
        .page-icon {
            width: 44px;
            height: 44px;
            font-size: 1.2rem;
        }
        .card-sertifikat-title {
            font-size: 0.95rem;
        }
        .status-content strong {
            font-size: 0.85rem;
        }
        .quiz-title {
            font-size: 0.75rem;
        }
        .empty-state-icon {
            font-size: 3.5rem;
        }
    }
</style>
@endpush
@endsection