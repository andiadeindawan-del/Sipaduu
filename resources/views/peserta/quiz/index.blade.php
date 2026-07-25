@extends('layouts.peserta')

@section('title', 'Daftar Quiz')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-question-circle"></i></span>
        <div>
            <p class="eyebrow">Quiz</p>
            <h1 class="h3 mb-0">Daftar Quiz</h1>
        </div>
    </div>
    <div class="heading-actions">
        <div class="d-flex gap-2 flex-wrap">
            <form action="{{ route('peserta.quiz.index') }}" method="GET" class="d-flex gap-2">
                <input type="text" name="search" class="form-control form-control-sm" 
                       placeholder="Cari quiz..." value="{{ request('search') }}" style="width: 200px;">
                <button type="submit" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-search"></i>
                </button>
                <a href="{{ route('peserta.quiz.index') }}" class="btn btn-outline-secondary btn-sm">
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
                    <span class="metric-label">Total Quiz</span>
                    <span class="metric-icon"><i class="bi bi-question-circle"></i></span>
                </div>
                <div class="metric-value">{{ $totalQuizzes ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-primary">Semua</span>
                    <span>quiz</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-success">
                <div class="metric-top">
                    <span class="metric-label">Telah Dikerjakan</span>
                    <span class="metric-icon"><i class="bi bi-check-circle"></i></span>
                </div>
                <div class="metric-value">{{ $completedQuizzes ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-success">Selesai</span>
                    <span>quiz</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-warning">
                <div class="metric-top">
                    <span class="metric-label">Sedang Dikerjakan</span>
                    <span class="metric-icon"><i class="bi bi-clock"></i></span>
                </div>
                <div class="metric-value">{{ $inProgressQuizzes ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-warning">Progress</span>
                    <span>quiz</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-info">
                <div class="metric-top">
                    <span class="metric-label">Rata-rata Nilai</span>
                    <span class="metric-icon"><i class="bi bi-star"></i></span>
                </div>
                <div class="metric-value">{{ number_format($averageScore ?? 0, 1) }}</div>
                <div class="metric-meta">
                    <span class="text-info">Keseluruhan</span>
                    <span>nilai</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="panel mb-3">
        <div class="panel-header">
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('peserta.quiz.index') }}" 
                   class="btn btn-sm {{ !request('filter') ? 'btn-success' : 'btn-outline-secondary' }}">
                    <i class="bi bi-grid"></i> Semua
                </a>
                <a href="{{ route('peserta.quiz.index', ['filter' => 'completed']) }}" 
                   class="btn btn-sm {{ request('filter') == 'completed' ? 'btn-success' : 'btn-outline-secondary' }}">
                    <i class="bi bi-check-circle"></i> Selesai
                </a>
                <a href="{{ route('peserta.quiz.index', ['filter' => 'in_progress']) }}" 
                   class="btn btn-sm {{ request('filter') == 'in_progress' ? 'btn-success' : 'btn-outline-secondary' }}">
                    <i class="bi bi-clock"></i> Sedang Dikerjakan
                </a>
                <a href="{{ route('peserta.quiz.index', ['filter' => 'not_started']) }}" 
                   class="btn btn-sm {{ request('filter') == 'not_started' ? 'btn-success' : 'btn-outline-secondary' }}">
                    <i class="bi bi-hourglass-split"></i> Belum Dikerjakan
                </a>
            </div>
        </div>
    </div>

    <!-- Quiz Cards -->
    @if($quizzes && $quizzes->count() > 0)
        <div class="row g-4">
            @foreach($quizzes as $quiz)
            <div class="col-12 col-md-6 col-xl-4">
                <div class="panel h-100">
                    <div class="p-4">
                        <!-- Status Badge -->
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge 
                                @if($quiz->status == 'published') 
                                    badge-published
                                @elseif($quiz->status == 'draft') 
                                    badge-draft
                                @else 
                                    badge-archived
                                @endif
                            ">
                                {{ $quiz->status_label ?? ucfirst($quiz->status ?? 'Draft') }}
                            </span>
                            @php
                                $userAttempt = $quiz->getUserAttempt(auth()->id());
                                $attemptStatus = $userAttempt ? $userAttempt->status : 'not_started';
                            @endphp
                            @if($attemptStatus == 'completed')
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle me-1"></i> Selesai
                                </span>
                            @elseif($attemptStatus == 'in_progress')
                                <span class="badge bg-warning">
                                    <i class="bi bi-clock me-1"></i> Sedang Dikerjakan
                                </span>
                            @endif
                        </div>

                        <!-- Title -->
                        <h5 class="fw-bold mb-2 text-truncate" title="{{ $quiz->judul }}">
                            {{ $quiz->judul }}
                        </h5>
                        
                        <!-- Materi -->
                        @if($quiz->materi)
                        <p class="text-muted small mb-2">
                            <i class="bi bi-book me-1"></i>
                            {{ $quiz->materi->judul }}
                        </p>
                        @endif

                        <!-- Description -->
                        <p class="text-muted small mb-3" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                            {{ Str::limit($quiz->deskripsi, 100) }}
                        </p>

                        <!-- Info -->
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <span class="text-muted small">
                                <i class="bi bi-list-ol me-1"></i>
                                {{ $quiz->questions_count ?? 0 }} soal
                            </span>
                            <span class="text-muted small">
                                <i class="bi bi-clock me-1"></i>
                                @if($quiz->durasi)
                                    {{ $quiz->durasi }} menit
                                @else
                                    -
                                @endif
                            </span>
                            <span class="text-muted small">
                                <i class="bi bi-star me-1"></i>
                                {{ $quiz->passing_score ?? 70 }}% lulus
                            </span>
                            <span class="text-muted small">
                                <i class="bi bi-arrow-repeat me-1"></i>
                                {{ $quiz->max_attempt ?? 1 }}x
                            </span>
                        </div>

                        <!-- User Score -->
                        @if($attemptStatus == 'completed')
                            <div class="mb-3">
                                <div class="d-flex justify-content-between small mb-1">
                                    <span>Nilai Anda</span>
                                    <span class="fw-bold {{ $userAttempt->score >= ($quiz->passing_score ?? 70) ? 'text-success' : 'text-danger' }}">
                                        {{ $userAttempt->score ?? 0 }}/{{ $userAttempt->total_questions ?? 0 }}
                                    </span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar {{ $userAttempt->score >= ($quiz->passing_score ?? 70) ? 'bg-success' : 'bg-danger' }}" 
                                         style="width: {{ $userAttempt->percentage ?? 0 }}%;">
                                    </div>
                                </div>
                                <div class="text-center mt-1">
                                    <span class="badge {{ $userAttempt->score >= ($quiz->passing_score ?? 70) ? 'bg-success' : 'bg-danger' }}">
                                        {{ number_format($userAttempt->percentage ?? 0, 1) }}%
                                    </span>
                                </div>
                            </div>
                        @endif

                        <!-- Remaining Attempts -->
                        @if($attemptStatus != 'completed')
                            @php
                                $remainingAttempts = $quiz->max_attempt - ($quiz->attempts()->where('user_id', auth()->id())->count() ?? 0);
                            @endphp
                            @if($remainingAttempts > 0)
                                <div class="text-muted small mb-2">
                                    <i class="bi bi-arrow-repeat me-1"></i>
                                    Sisa percobaan: {{ $remainingAttempts }}x
                                </div>
                            @else
                                <div class="text-danger small mb-2">
                                    <i class="bi bi-exclamation-triangle me-1"></i>
                                    Kuota percobaan habis
                                </div>
                            @endif
                        @endif

                        <!-- Actions -->
                        <div class="d-flex gap-2 mt-2">
                            <a href="{{ route('peserta.quiz.show', $quiz->id) }}" 
                               class="btn btn-success btn-sm flex-grow-1">
                                <i class="bi bi-eye me-1"></i> Lihat Detail
                            </a>
                            @if($attemptStatus != 'completed' && $remainingAttempts > 0)
                                <a href="{{ route('peserta.quiz.show', $quiz->id) }}" 
                                   class="btn btn-primary btn-sm">
                                    <i class="bi bi-play-circle"></i> Kerjakan
                                </a>
                            @elseif($attemptStatus == 'completed')
                                <a href="{{ route('peserta.quiz.result', ['quiz' => $quiz->id, 'attempt' => $userAttempt->id]) }}" 
                                   class="btn btn-info btn-sm">
                                    <i class="bi bi-eye"></i> Lihat Hasil
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($quizzes->hasPages())
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mt-4">
            <p class="text-muted small mb-0">
                Menampilkan {{ $quizzes->firstItem() ?? 0 }} sampai {{ $quizzes->lastItem() ?? 0 }} 
                dari {{ $quizzes->total() ?? 0 }} quiz
            </p>
            <nav aria-label="Quiz pagination">
                {{ $quizzes->links() }}
            </nav>
        </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="panel">
            <div class="text-center py-5">
                <div class="text-muted">
                    <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                    <p class="h5">Belum ada quiz</p>
                    <p class="small">
                        @if(request('search'))
                            Tidak ada quiz yang sesuai dengan pencarian "{{ request('search') }}".
                        @elseif(request('filter') == 'completed')
                            Anda belum menyelesaikan quiz apapun.
                        @elseif(request('filter') == 'in_progress')
                            Anda belum memiliki quiz yang sedang dikerjakan.
                        @elseif(request('filter') == 'not_started')
                            Semua quiz sudah Anda kerjakan!
                        @else
                            Belum ada quiz yang tersedia saat ini.
                        @endif
                    </p>
                    @if(request('search') || request('filter'))
                    <a href="{{ route('peserta.quiz.index') }}" class="btn btn-success btn-sm mt-2">
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