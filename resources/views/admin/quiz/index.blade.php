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
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createModal" 
                        onclick="setMateriId({{ $selectedMateri->id }})">
                    <i class="bi bi-plus-circle"></i> Tambah Quiz untuk Materi Ini
                </button>
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
                
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="bi bi-plus-circle"></i> Tambah Quiz
                </button>
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
                        <th class="text-end" style="width: 180px;">Aksi</th>
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
                        <td class="text-end">
                            <div class="d-flex gap-1 justify-content-end" role="group">
                                <!-- Lihat -->
                                <button type="button" class="btn btn-info" 
                                        data-bs-toggle="modal" data-bs-target="#showModal{{ $quiz->id }}" 
                                        title="Lihat Detail">
                                    <i class="bi bi-eye"></i> 
                                </button>
                                
                                <!-- Edit -->
                                <button type="button" class="btn btn-warning" 
                                        data-bs-toggle="modal" data-bs-target="#editModal{{ $quiz->id }}" 
                                        title="Edit Quiz">
                                    <i class="bi bi-pencil"></i> 
                                </button>
                                
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
                                <button type="button" class="btn btn-danger" 
                                        data-bs-toggle="modal" data-bs-target="#deleteModal{{ $quiz->id }}" 
                                        title="Hapus Quiz">
                                    <i class="bi bi-trash"></i> 
                                </button>
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
                    <button type="button" class="btn btn-primary btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#createModal">
                        <i class="bi bi-plus-circle"></i> 
                        @if(request('materi_id') && isset($selectedMateri))
                            Tambah Quiz untuk Materi Ini
                        @else
                            Tambah Quiz
                        @endif
                    </button>
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

<!-- ============================================================
     MODAL CREATE
============================================================ -->
<div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.quiz.store') }}" method="POST" id="createForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-plus-circle text-primary me-2"></i>Tambah Quiz
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <input type="hidden" name="materi_id" id="createMateriId" value="{{ request('materi_id') }}">
                        
                        <div class="col-12">
                            <label class="form-label fw-semibold">Judul Quiz <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-text-paragraph"></i></span>
                                <input type="text" class="form-control" name="judul" placeholder="Masukkan judul quiz" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Deskripsi</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-file-text"></i></span>
                                <textarea class="form-control" name="deskripsi" rows="2" placeholder="Deskripsi quiz (opsional)"></textarea>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Training <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-journal-bookmark"></i></span>
                                <select class="form-select" name="training_id" required>
                                    <option value="">Pilih Training</option>
                                    @foreach($trainings ?? [] as $training)
                                    <option value="{{ $training->id }}">{{ $training->judul }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Materi</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-book"></i></span>
                                <select class="form-select" name="materi_id" id="createMateriSelect">
                                    <option value="">Pilih Materi (Opsional)</option>
                                    @foreach($materis ?? [] as $materi)
                                    <option value="{{ $materi->id }}" {{ request('materi_id') == $materi->id ? 'selected' : '' }}>
                                        {{ $materi->judul }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <small class="text-muted">Kosongkan jika quiz tidak terkait dengan materi tertentu.</small>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Durasi (menit)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-clock"></i></span>
                                <input type="number" class="form-control" name="durasi" placeholder="30" min="1">
                            </div>
                            <small class="text-muted">Waktu pengerjaan quiz.</small>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Nilai Minimal <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-star"></i></span>
                                <input type="number" class="form-control" name="passing_score" value="70" min="0" max="100" required>
                                <span class="input-group-text">%</span>
                            </div>
                            <small class="text-muted">Nilai minimal untuk lulus.</small>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Maks. Percobaan <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-arrow-repeat"></i></span>
                                <input type="number" class="form-control" name="max_attempt" value="1" min="1" max="10" required>
                            </div>
                            <small class="text-muted">Batas maksimal pengulangan quiz.</small>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-toggle-on"></i></span>
                                <select class="form-select" name="status" required>
                                    <option value="draft">📝 Draft</option>
                                    <option value="published" selected>✅ Published</option>
                                    <option value="archived">📦 Archived</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================================
     MODAL SHOW
============================================================ -->
@foreach($quizzes ?? [] as $quiz)
<div class="modal fade" id="showModal{{ $quiz->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-eye text-info me-2"></i>Detail Quiz
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="text-muted small fw-semibold">Judul</label>
                        <p class="fw-semibold fs-5">{{ $quiz->judul }}</p>
                    </div>
                    @if($quiz->deskripsi)
                    <div class="col-12">
                        <label class="text-muted small fw-semibold">Deskripsi</label>
                        <p>{{ $quiz->deskripsi }}</p>
                    </div>
                    @endif
                    <div class="col-12 col-md-6">
                        <label class="text-muted small fw-semibold">Training</label>
                        @if($quiz->training)
                        <p class="fw-semibold">{{ $quiz->training->judul }}</p>
                        @else
                        <p class="text-muted">-</p>
                        @endif
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="text-muted small fw-semibold">Materi</label>
                        @if($quiz->materi)
                        <p class="fw-semibold">
                            <a href="{{ route('admin.materi.show', $quiz->materi->id) }}" class="text-decoration-none">
                                {{ $quiz->materi->judul }}
                            </a>
                        </p>
                        @else
                        <p class="text-muted">-</p>
                        @endif
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="text-muted small fw-semibold">Durasi</label>
                        <p>{{ $quiz->durasi ? $quiz->durasi . ' menit' : '-' }}</p>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="text-muted small fw-semibold">Nilai Minimal</label>
                        <p>{{ $quiz->passing_score ?? 70 }}%</p>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="text-muted small fw-semibold">Total Pertanyaan</label>
                        <p>{{ $quiz->questions_count ?? 0 }}</p>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="text-muted small fw-semibold">Status</label>
                        <p>
                            @php
                                $statusMap = [
                                    'draft' => ['label' => 'Draft', 'class' => 'badge-draft'],
                                    'published' => ['label' => 'Published', 'class' => 'badge-published'],
                                    'archived' => ['label' => 'Archived', 'class' => 'badge-secondary'],
                                ];
                                $status = $statusMap[$quiz->status] ?? ['label' => $quiz->status, 'class' => 'badge-draft'];
                            @endphp
                            <span class="badge {{ $status['class'] }}">{{ $status['label'] }}</span>
                        </p>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="text-muted small fw-semibold">Dibuat</label>
                        <p>{{ $quiz->created_at ? $quiz->created_at->format('d/m/Y H:i') : '-' }}</p>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="text-muted small fw-semibold">Diperbarui</label>
                        <p>{{ $quiz->updated_at ? $quiz->updated_at->format('d/m/Y H:i') : '-' }}</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $quiz->id }}" data-bs-dismiss="modal">
                    <i class="bi bi-pencil"></i> Edit
                </button>
                <a href="{{ route('admin.quiz.questions.index', $quiz->id) }}" class="btn btn-success">
                    <i class="bi bi-list-ol"></i> Kelola Pertanyaan
                </a>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- ============================================================
     MODAL EDIT
============================================================ -->
@foreach($quizzes ?? [] as $quiz)
<div class="modal fade" id="editModal{{ $quiz->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.quiz.update', $quiz->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-pencil-square text-warning me-2"></i>Edit Quiz
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Judul Quiz <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-text-paragraph"></i></span>
                                <input type="text" class="form-control" name="judul" value="{{ $quiz->judul }}" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Deskripsi</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-file-text"></i></span>
                                <textarea class="form-control" name="deskripsi" rows="2">{{ $quiz->deskripsi }}</textarea>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Training <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-journal-bookmark"></i></span>
                                <select class="form-select" name="training_id" required>
                                    <option value="">Pilih Training</option>
                                    @foreach($trainings ?? [] as $training)
                                    <option value="{{ $training->id }}" {{ $quiz->training_id == $training->id ? 'selected' : '' }}>
                                        {{ $training->judul }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Materi</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-book"></i></span>
                                <select class="form-select" name="materi_id">
                                    <option value="">Pilih Materi (Opsional)</option>
                                    @foreach($materis ?? [] as $materi)
                                    <option value="{{ $materi->id }}" {{ $quiz->materi_id == $materi->id ? 'selected' : '' }}>
                                        {{ $materi->judul }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Durasi (menit)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-clock"></i></span>
                                <input type="number" class="form-control" name="durasi" value="{{ $quiz->durasi }}" min="1">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Nilai Minimal <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-star"></i></span>
                                <input type="number" class="form-control" name="passing_score" value="{{ $quiz->passing_score ?? 70 }}" min="0" max="100" required>
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Maks. Percobaan <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-arrow-repeat"></i></span>
                                <input type="number" class="form-control" name="max_attempt" value="{{ $quiz->max_attempt ?? 1 }}" min="1" max="10" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-toggle-on"></i></span>
                                <select class="form-select" name="status" required>
                                    <option value="draft" {{ $quiz->status == 'draft' ? 'selected' : '' }}>📝 Draft</option>
                                    <option value="published" {{ $quiz->status == 'published' ? 'selected' : '' }}>✅ Published</option>
                                    <option value="archived" {{ $quiz->status == 'archived' ? 'selected' : '' }}>📦 Archived</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <hr>
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label class="text-muted small fw-semibold">Dibuat</label>
                                    <p class="fw-semibold mb-0">{{ $quiz->created_at ? $quiz->created_at->format('d/m/Y H:i') : '-' }}</p>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="text-muted small fw-semibold">Diperbarui</label>
                                    <p class="fw-semibold mb-0">{{ $quiz->updated_at ? $quiz->updated_at->format('d/m/Y H:i') : '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-save me-1"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<!-- ============================================================
     MODAL DELETE
============================================================ -->
@foreach($quizzes ?? [] as $quiz)
<div class="modal fade" id="deleteModal{{ $quiz->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-triangle text-danger me-2"></i>
                    Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus quiz <strong>{{ $quiz->judul }}</strong>?</p>
                @if(($quiz->questions_count ?? 0) > 0)
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Quiz ini memiliki <strong>{{ $quiz->questions_count }}</strong> pertanyaan. 
                    Menghapus quiz akan menghapus semua pertanyaan terkait.
                </div>
                @endif
                <p class="text-muted small">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin.quiz.destroy', $quiz->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-1"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

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

    // ============================================================
    // SET MATERI ID FOR CREATE
    // ============================================================
    window.setMateriId = function(materiId) {
        document.getElementById('createMateriId').value = materiId;
        document.getElementById('createMateriSelect').value = materiId;
    };

    // ============================================================
    // FILTER AUTO SUBMIT
    // ============================================================
    document.querySelectorAll('form[method="GET"] select[name="status"], form[method="GET"] select[name="materi_id"]').forEach(function(select) {
        select.addEventListener('change', function() {
            this.closest('form').submit();
        });
    });

    // ============================================================
    // DURASI & PASSING SCORE VALIDATION
    // ============================================================
    document.querySelectorAll('input[name="durasi"]').forEach(function(input) {
        input.addEventListener('input', function() {
            if (this.value < 0) this.value = 1;
        });
    });

    document.querySelectorAll('input[name="passing_score"]').forEach(function(input) {
        input.addEventListener('input', function() {
            if (this.value < 0) this.value = 0;
            if (this.value > 100) this.value = 100;
        });
    });
});
</script>
@endpush
@endsection