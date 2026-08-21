@extends('layouts.admin')

@section('title', 'Manajemen Quiz')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-question-circle"></i></span>
        <div>
            <p class="eyebrow">Manajemen</p>
            <h1 class="h3 mb-0">Quiz</h1>
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
                    <span class="metric-label">Total Quiz</span>
                    <span class="metric-icon"><i class="bi bi-question-circle"></i></span>
                </div>
                <div class="metric-value">{{ $totalQuiz ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-success">Semua</span>
                    <span>quiz</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-success">
                <div class="metric-top">
                    <span class="metric-label">Published</span>
                    <span class="metric-icon"><i class="bi bi-check-circle"></i></span>
                </div>
                <div class="metric-value">{{ $publishedQuiz ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-success">Aktif</span>
                    <span>quiz</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-warning">
                <div class="metric-top">
                    <span class="metric-label">Draft</span>
                    <span class="metric-icon"><i class="bi bi-pencil"></i></span>
                </div>
                <div class="metric-value">{{ $draftQuiz ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-warning">Perlu review</span>
                    <span>draft</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-info">
                <div class="metric-top">
                    <span class="metric-label">Total Pertanyaan</span>
                    <span class="metric-icon"><i class="bi bi-list-ol"></i></span>
                </div>
                <div class="metric-value">{{ $totalQuestions ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-info">Keseluruhan</span>
                    <span>pertanyaan</span>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================================== -->
    <!-- QUIZ BY MATERI SECTION -->
    <!-- ========================================================== -->
    @if(request('materi_id') && isset($selectedMateri))
    <div class="panel mb-3">
        <div class="panel-header">
            <div>
                <h5 class="section-title">
                    <i class="bi bi-book me-2"></i>
                    Quiz untuk Materi: {{ $selectedMateri->judul }}
                </h5>
                <p class="text-muted small mb-0">
                    Menampilkan quiz yang terhubung dengan materi ini.
                    <a href="{{ route('admin.materi.show', $selectedMateri->id) }}" class="text-decoration-none">
                        <i class="bi bi-box-arrow-up-right me-1"></i> Lihat Materi
                    </a>
                </p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('admin.quiz.create', ['materi_id' => $selectedMateri->id]) }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle"></i> Tambah Quiz untuk Materi Ini
                </a>
                <a href="{{ route('admin.quiz.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-x-circle"></i> Hapus Filter
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- ========================================================== -->
    <!-- TABLE -->
    <!-- ========================================================== -->
    <div class="panel">
        <div class="panel-header">
            <div>
                <h5 class="section-title"><i class="bi bi-table"></i> Daftar Quiz</h5>
            </div>
            <div class="d-flex gap-2 flex-wrap align-items-center">
                <!-- SEARCH -->
                <form action="{{ route('admin.quiz.index') }}" method="GET" class="d-flex gap-2">
                    <div class="input-group" style="width: 220px;">
                        <input class="form-control form-control-sm" type="search" name="search" 
                               placeholder="Cari quiz..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                    
                    @if(request('search') || request('status') || request('materi_id'))
                    <a href="{{ route('admin.quiz.index') }}" class="btn btn-outline-secondary btn-sm" title="Reset Filter">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset
                    </a>
                    @endif
                </form>
                
                <a href="{{ route('admin.quiz.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle"></i> Tambah Quiz
                </a>
            </div>
        </div>
        <div class="table-responsive">
            @if(isset($quizzes) && $quizzes->count() > 0)
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Judul Quiz</th>
                        <th>Training</th>
                        <th>Materi</th>
                        <th>Pertanyaan</th>
                        <th>Durasi</th>
                        <th>Status</th>
                        <th class="text-center" style="width: 180px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($quizzes as $index => $quiz)
                    <tr>
                        <td>{{ $quizzes->firstItem() + $index }}</td>
                        <td>
                            <div>
                                <p class="fw-semibold mb-0">{{ $quiz->judul }}</p>
                            </div>
                        </td>
                        <td>
                            @if($quiz->training)
                            <span class="text-muted">{{ $quiz->training->judul }}</span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($quiz->materi)
                            <a href="{{ route('admin.materi.show', $quiz->materi->id) }}" class="text-decoration-none text-muted">
                                {{ $quiz->materi->judul }}
                            </a>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="text-muted">
                                <i class="bi bi-list-ol me-1"></i>
                                {{ $quiz->questions_count ?? 0 }}
                            </span>
                        </td>
                        <td>
                            @if($quiz->durasi)
                            <span class="text-muted">
                                {{ $quiz->durasi }} menit
                            </span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $statusMap = [
                                    'draft' => ['label' => 'Draft', 'class' => 'badge-draft'],
                                    'published' => ['label' => 'Published', 'class' => 'badge-published'],
                                    'archived' => ['label' => 'Archived', 'class' => 'badge-secondary'],
                                ];
                                $status = $statusMap[$quiz->status] ?? ['label' => $quiz->status, 'class' => 'badge-draft'];
                            @endphp
                            <span class="badge {{ $status['class'] }}">
                                <i class="bi bi-circle-fill me-1" style="font-size: 6px;"></i>
                                {{ $status['label'] }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex gap-1 justify-content-center" role="group">
                                <!-- Lihat -->
                                <a href="{{ route('admin.quiz.show', $quiz->id) }}" class="btn btn-info" title="Lihat Detail">
                                    <i class="bi bi-eye"></i> 
                                </a>
                                
                                <!-- Edit -->
                                <a href="{{ route('admin.quiz.edit', $quiz->id) }}" class="btn btn-warning" title="Edit Quiz">
                                    <i class="bi bi-pencil"></i> 
                                </a>
                                
                                <!-- Pertanyaan -->
                                <a href="{{ route('admin.quiz.questions.index', $quiz->id) }}" 
                                   class="btn btn-success" title="Kelola Pertanyaan">
                                    <i class="bi bi-list-ol"></i> 
                                </a>
                                
                                <!-- Materi -->
                                @if($quiz->materi)
                                <a href="{{ route('admin.materi.show', $quiz->materi->id) }}" 
                                   class="btn btn-primary" title="Lihat Materi">
                                    <i class="bi bi-book"></i> 
                                </a>
                                @endif
                                
                                <!-- Hapus -->
                                <form action="{{ route('admin.quiz.destroy', $quiz->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus quiz {{ $quiz->judul }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" title="Hapus Quiz">
                                        <i class="bi bi-trash"></i> 
                                    </button>
                                </form>
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
                    <p class="h5">
                        @if(request('search') || request('status') || request('materi_id'))
                            Tidak ada quiz yang sesuai dengan filter
                        @else
                            Belum ada quiz
                        @endif
                    </p>
                    <p class="small">
                        @if(request('search') || request('status') || request('materi_id'))
                            Coba ubah kriteria pencarian atau reset filter
                        @else
                            @if(request('materi_id') && isset($selectedMateri))
                                Belum ada quiz untuk materi <strong>{{ $selectedMateri->judul }}</strong>.
                            @else
                                Mulai dengan menambahkan quiz baru.
                            @endif
                        @endif
                    </p>
                    @if(request('search') || request('status') || request('materi_id'))
                    <a href="{{ route('admin.quiz.index') }}" class="btn btn-outline-secondary btn-sm mt-2">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset Filter
                    </a>
                    @endif
                    <a href="{{ route('admin.quiz.create') }}" class="btn btn-primary btn-sm mt-2">
                        <i class="bi bi-plus-circle"></i> 
                        @if(request('materi_id') && isset($selectedMateri))
                            Tambah Quiz untuk Materi Ini
                        @else
                            Tambah Quiz
                        @endif
                    </a>
                </div>
            </div>
            @endif
        </div>
        @if(isset($quizzes) && $quizzes->hasPages())
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mt-3 px-3 pb-3">
            <p class="text-muted small mb-0">
                Menampilkan {{ $quizzes->firstItem() ?? 0 }} sampai {{ $quizzes->lastItem() ?? 0 }} 
                dari {{ $quizzes->total() ?? 0 }} quiz
            </p>
            <nav aria-label="Quiz pagination">
                {{ $quizzes->links() }}
            </nav>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ============================================================
    // AUTO CLOSE ALERTS
    // ============================================================
    setTimeout(function() {
        document.querySelectorAll('.alert').forEach(function(alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});
</script>
@endpush
@endsection