@extends('layouts.pemateri')

@section('title', 'Manajemen Kuis')

@section('header')
<div class="page-heading d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div class="page-heading-copy d-flex align-items-center gap-3">
        <span class="page-icon d-flex align-items-center justify-content-center"
              style="width:52px;height:52px;border-radius:16px;background:linear-gradient(135deg,#4a6cf7,#2548db);color:#fff;font-size:1.25rem;box-shadow:0 10px 22px rgba(74,108,247,.3);">
            <i class="bi bi-question-circle"></i>
        </span>
        <div>
            <p class="eyebrow mb-1 text-uppercase fw-bold" style="letter-spacing:.12em;font-size:.7rem;color:#8a94b3;">Aktivitas Pemateri</p>
            <h1 class="h3 mb-0 fw-bold">Manajemen Kuis</h1>
            <p class="text-muted mb-0 small">Buat dan kelola kuis evaluasi untuk pelatihan pembelajaran.</p>
        </div>
    </div>
</div>
@endsection

@section('content')
<style>
    /* ====== PANEL ====== */
    .panel{
        background:#fff;
        border-radius:18px;
        box-shadow:0 4px 18px rgba(15,23,42,.06);
        border:1px solid rgba(15,23,42,.05) !important;
        overflow:hidden;
    }

    /* ====== STAT MINI CARD ====== */
    .stat-mini{
        border-radius:16px;
        padding:1rem 1.1rem;
        background:#fff;
        border:1px solid rgba(15,23,42,.06);
        border-left:4px solid transparent;
        box-shadow:0 4px 18px rgba(15,23,42,.06);
        transition:transform .22s ease, box-shadow .22s ease;
        height:100%;
    }
    .stat-mini:hover{ transform:translateY(-3px); box-shadow:0 12px 28px rgba(15,23,42,.1); }
    .stat-mini .stat-label{
        font-size:.72rem;color:#8a93a3;font-weight:700;
        text-transform:uppercase;letter-spacing:.05em;
        display:block;margin-bottom:.35rem;
    }
    .stat-mini .stat-value{ font-size:1.5rem;font-weight:800;letter-spacing:-.02em;line-height:1; }
    .stat-mini.s-primary{ border-left-color:#4a6cf7; }
    .stat-mini.s-success{ border-left-color:#28c76f; }
    .stat-mini.s-warning{ border-left-color:#ff9f43; }
    .stat-mini.s-info   { border-left-color:#00cfe8; }

    /* ====== FILTER FORM ====== */
    .filter-panel .form-control,
    .filter-panel .form-select{
        border-radius:10px;
        font-size:.88rem;
        border-color:#e4e9f2;
        transition:border-color .18s ease, box-shadow .18s ease;
    }
    .filter-panel .form-control:focus,
    .filter-panel .form-select:focus{
        border-color:#4a6cf7;
        box-shadow:0 0 0 .2rem rgba(74,108,247,.15);
    }
    .filter-panel .input-group-text{
        border-radius:10px 0 0 10px;
        border-color:#e4e9f2;
    }
    .filter-panel .input-group .form-control{ border-radius:0 10px 10px 0; }

    /* ====== TABLE ====== */
    .table thead th{
        font-size:.72rem;text-transform:uppercase;letter-spacing:.06em;
        color:#6b7280;font-weight:700;border-bottom:1px solid #eef2f7;
        background:#fafbfe;padding:.85rem 1rem;white-space:nowrap;
    }
    .table tbody td{
        font-size:.88rem;vertical-align:middle;
        border-color:#f1f4f9;padding:.85rem 1rem;
    }
    .table tbody tr{ transition:background .18s ease; }
    .table tbody tr:hover{ background:#f8faff; }
    .table .badge{ font-weight:600;padding:.45rem .7rem;border-radius:8px;font-size:.72rem; }

    /* ====== KOLOM JUDUL & PELATIHAN ====== */
    .cell-title{
        display:-webkit-box;
        -webkit-line-clamp:2;
        -webkit-box-orient:vertical;
        overflow:hidden;
        text-overflow:ellipsis;
        font-weight:600;
        color:#0f172a;
        font-size:.88rem;
        line-height:1.35;
        margin-bottom:.2rem;
    }
    .cell-sub{
        display:block;
        font-size:.75rem;
        color:#8a93a3;
        line-height:1.35;
        overflow:hidden;
        text-overflow:ellipsis;
        white-space:nowrap;
    }
    .cell-judul{ max-width:300px; min-width:200px; }
    .cell-pelatihan{ max-width:220px; min-width:170px; }

    /* ====== BUTTONS ====== */
    .btn{ border-radius:10px; transition:transform .18s ease, box-shadow .18s ease; }
    .btn:hover{ transform:translateY(-1px); }
    .btn-group-sm .btn{ border-radius:8px !important; }
    .btn-group .btn:hover{ transform:scale(1.06); }

    /* ====== PAGINATION ====== */
    .pagination{ margin:0; }
    .pagination .page-link{
        border-radius:8px !important;
        margin:0 .15rem;
        border-color:#e4e9f2;
        color:#4a6cf7;
        font-size:.85rem;
    }
    .pagination .page-item.active .page-link{
        background:#4a6cf7;border-color:#4a6cf7;color:#fff;
    }

    /* ====== EMPTY STATE ====== */
    .empty-state i{ opacity:.5; }

    /* ====== RESPONSIVE TABLE WRAPPER ====== */
    .table-responsive{ border-radius:0 0 18px 18px; }
</style>

<div class="container-fluid px-3 px-lg-4 py-4">

    <!-- ALERT -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
            <i class="bi bi-check-circle me-2"></i> {!! session('success') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- STATISTIK RINGKAS -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-mini s-primary text-center">
                <span class="stat-label">Total Kuis</span>
                <div class="stat-value text-primary">{{ $totalQuiz ?? 0 }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-mini s-success text-center">
                <span class="stat-label">Kuis Terbit</span>
                <div class="stat-value text-success">{{ $publishedQuiz ?? 0 }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-mini s-warning text-center">
                <span class="stat-label">Total Butir Soal</span>
                <div class="stat-value text-warning">{{ $totalQuestions ?? 0 }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-mini s-info text-center">
                <span class="stat-label">Peserta Mengisi</span>
                <div class="stat-value text-info">{{ $totalAttempts ?? 0 }}</div>
            </div>
        </div>
    </div>

    <!-- FILTER & SEARCH + TAMBAH KUIS -->
    <div class="panel filter-panel mb-4">
        <div class="p-3">
            <form action="{{ route('pemateri.quiz.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-12 col-md-3">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Cari judul kuis..." value="{{ request('search') }}">
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <select name="training_id" class="form-select">
                        <option value="">Semua Pelatihan</option>
                        @foreach($trainings as $training)
                            <option value="{{ $training->id }}" {{ request('training_id') == $training->id ? 'selected' : '' }}>
                                {{ $training->judul }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-sm-6 col-md-2">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>

                <div class="col-12 col-md-2 d-flex gap-1">
                    <button type="submit" class="btn btn-primary w-100 fw-semibold">
                        Filter
                    </button>
                    @if(request()->anyFilled(['search', 'training_id', 'status']))
                        <a href="{{ route('pemateri.quiz.index') }}" class="btn btn-outline-secondary" title="Reset">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    @endif
                </div>

                <div class="col-12 col-md-2">
                    <a href="{{ route('pemateri.quiz.create') }}" class="btn btn-warning text-dark w-100 fw-semibold shadow-sm">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Kuis
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- TABLE KUIS -->
    <div class="panel">
        <div class="p-0">
            @if($quizzes->isEmpty())
                <div class="text-center py-5 text-muted empty-state">
                    <i class="bi bi-question-square fs-1 d-block mb-3 text-secondary"></i>
                    <h5 class="fw-bold text-dark">Belum Ada Kuis</h5>
                    <p class="small text-muted mb-3">Belum ada kuis yang ditemukan.</p>
                    <a href="{{ route('pemateri.quiz.create') }}" class="btn btn-warning text-dark btn-sm fw-semibold rounded-pill px-3">
                        <i class="bi bi-plus-circle me-1"></i> Buat Kuis Sekarang
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th style="width:50px;">No</th>
                                <th class="cell-judul">Judul Kuis</th>
                                <th class="cell-pelatihan">Pelatihan</th>
                                <th style="width:140px;">Jumlah Soal</th>
                                <th style="width:150px;">Peserta Mengisi</th>
                                <th style="width:130px;">Durasi</th>
                                <th style="width:120px;">Passing Score</th>
                                <th style="width:110px;">Status</th>
                                <th style="width:200px;" class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($quizzes as $index => $quiz)
                                <tr>
                                    <td class="text-muted">{{ $quizzes->firstItem() + $index }}</td>
                                    <td class="cell-judul">
                                        <span class="cell-title">{{ $quiz->judul }}</span>
                                    </td>
                                    <td class="cell-pelatihan">
                                        <span class="cell-title" style="font-size:.82rem;font-weight:600;">
                                            <i class="bi bi-journal-text text-primary me-1"></i>{{ $quiz->training->judul ?? '-' }}
                                        </span>
                                        @if($quiz->training && $quiz->training->isFinished())
                                            <span class="cell-sub">
                                                <i class="bi bi-lock-fill me-1"></i>Selesai
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('pemateri.quiz.show', $quiz) }}" class="badge bg-primary bg-opacity-10 text-primary text-decoration-none" title="Lihat daftar soal">
                                            <i class="bi bi-ui-checks me-1"></i> {{ $quiz->questions_count }} Pertanyaan
                                        </a>
                                    </td>
                                    <td>
                                        @if(($quiz->attempts_count ?? 0) > 0)
                                            <a href="{{ route('pemateri.quiz.show', $quiz) }}#hasil" class="badge bg-success bg-opacity-10 text-success text-decoration-none fw-semibold" title="Lihat hasil peserta">
                                                <i class="bi bi-people-fill me-1"></i> {{ $quiz->attempts_count }} Percobaan
                                            </a>
                                        @else
                                            <span class="badge bg-light text-muted border">
                                                <i class="bi bi-person me-1"></i> 0 Peserta
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-muted">{{ $quiz->durasi ? $quiz->durasi . ' menit' : 'Tidak dibatasi' }}</td>
                                    <td class="text-muted">{{ $quiz->passing_score ?? 70 }}%</td>
                                    <td>
                                        <span class="badge {{ $quiz->status === 'published' ? 'bg-success' : 'bg-secondary' }}">
                                            {{ ucfirst($quiz->status) }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('pemateri.quiz.show', $quiz) }}" class="btn btn-outline-primary" title="Detail Kuis &amp; Hasil Peserta">
                                                <i class="bi bi-eye"></i> Detail
                                            </a>
                                            @if(!($quiz->training && $quiz->training->isFinished()))
                                                <a href="{{ route('pemateri.quiz.edit', $quiz) }}" class="btn btn-outline-warning text-dark" title="Edit Pengaturan Kuis">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('pemateri.quiz.destroy', $quiz) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kuis ini beserta semua pertanyaannya?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger" title="Hapus">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="btn btn-outline-secondary disabled" title="Pelatihan telah selesai (konten dikunci)">
                                                    <i class="bi bi-lock-fill"></i>
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-3 border-top d-flex justify-content-center">
                    {{ $quizzes->links() }}
                </div>
            @endif
        </div>
    </div>

</div>
@endsection