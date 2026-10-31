@extends('layouts.pemateri')

@section('title', 'Manajemen Survey')

@section('header')
<div class="page-heading d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div class="page-heading-copy d-flex align-items-center gap-3">
        <span class="page-icon d-flex align-items-center justify-content-center"
              style="width:52px;height:52px;border-radius:16px;background:linear-gradient(135deg,#4a6cf7,#2548db);color:#fff;font-size:1.25rem;box-shadow:0 10px 22px rgba(74,108,247,.3);">
            <i class="bi bi-ui-radios"></i>
        </span>
        <div>
            <p class="eyebrow mb-1 text-uppercase fw-bold" style="letter-spacing:.12em;font-size:.7rem;color:#8a94b3;">Aktivitas Pemateri</p>
            <h1 class="h3 mb-0 fw-bold">Manajemen Survey</h1>
            <p class="text-muted mb-0 small">Kelola kuesioner evaluasi dan tinjau umpan balik peserta pelatihan.</p>
        </div>
    </div>
</div>
@endsection

@section('content')
<style>
    /* ====== METRIC CARD ====== */
    .metric-card{
        background:#fff;
        border-radius:16px;
        padding:1.1rem 1.25rem;
        box-shadow:0 4px 18px rgba(15,23,42,.06);
        border:1px solid rgba(15,23,42,.05);
        border-left:4px solid transparent;
        height:100%;
        transition:transform .22s ease, box-shadow .22s ease;
    }
    .metric-card:hover{ transform:translateY(-4px); box-shadow:0 12px 28px rgba(15,23,42,.1); }
    .metric-primary{ border-left-color:#4a6cf7; }
    .metric-success{ border-left-color:#28c76f; }
    .metric-warning{ border-left-color:#ff9f43; }
    .metric-info   { border-left-color:#00cfe8; }

    .metric-top{ display:flex;justify-content:space-between;align-items:center;margin-bottom:.4rem; }
    .metric-label{
        font-size:.72rem;color:#8a93a3;font-weight:700;
        text-transform:uppercase;letter-spacing:.05em;
    }
    .metric-icon{ font-size:1.25rem; }
    .metric-value{ font-size:1.6rem;font-weight:800;color:#0f172a;letter-spacing:-.02em; }
    .metric-meta{ font-size:.75rem;color:#8a93a3;display:flex;gap:.35rem;margin-top:.2rem; }

    /* ====== PANEL ====== */
    .panel{
        background:#fff;
        border-radius:18px;
        box-shadow:0 4px 18px rgba(15,23,42,.06);
        border:1px solid rgba(15,23,42,.05) !important;
        overflow:hidden;
    }

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

    /* ====== KOLOM JUDUL (SURVEY & PELATIHAN) ====== */
    .cell-title{
        display:block;
        font-weight:600;
        color:#0f172a;
        font-size:.88rem;
        line-height:1.35;
        margin-bottom:.2rem;
        overflow:hidden;
        text-overflow:ellipsis;
        display:-webkit-box;
        -webkit-line-clamp:2;
        -webkit-box-orient:vertical;
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
    .cell-pelatihan{ max-width:240px; min-width:180px; }
    .cell-survey{ max-width:300px; min-width:200px; }

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

    <!-- ALERTS -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
            <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- STATISTIK KARTU -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-primary">
                <div class="metric-top">
                    <span class="metric-label">Total Survey</span>
                    <span class="metric-icon" style="color:#4a6cf7;"><i class="bi bi-ui-radios"></i></span>
                </div>
                <div class="metric-value">{{ $totalSurvey ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-primary"><i class="bi bi-journal-text"></i> Total kuesioner</span>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-success">
                <div class="metric-top">
                    <span class="metric-label">Published</span>
                    <span class="metric-icon" style="color:#28c76f;"><i class="bi bi-check-circle-fill"></i></span>
                </div>
                <div class="metric-value">{{ $publishedSurvey ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-success"><i class="bi bi-eye"></i> Dapat diisi peserta</span>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-warning">
                <div class="metric-top">
                    <span class="metric-label">Draft</span>
                    <span class="metric-icon" style="color:#ff9f43;"><i class="bi bi-pencil-square"></i></span>
                </div>
                <div class="metric-value">{{ $draftSurvey ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-warning"><i class="bi bi-hourglass-split"></i> Belum dirilis</span>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-info">
                <div class="metric-top">
                    <span class="metric-label">Total Respon</span>
                    <span class="metric-icon" style="color:#00cfe8;"><i class="bi bi-people-fill"></i></span>
                </div>
                <div class="metric-value">{{ $totalResponses ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-info"><i class="bi bi-chat-left-dots"></i> Respon peserta masuk</span>
                </div>
            </div>
        </div>
    </div>

    <!-- FILTER & SEARCH + BUAT SURVEY -->
    <div class="panel filter-panel mb-4">
        <div class="p-3">
            <form action="{{ route('pemateri.survey.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-12 col-md-3">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Cari judul survey..." value="{{ request('search') }}">
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <select name="training_id" class="form-select">
                        <option value="">Semua Pelatihan Saya</option>
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
                        <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>

                <div class="col-12 col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1 fw-semibold">
                        <i class="bi bi-filter me-1"></i> Filter
                    </button>
                    @if(request()->anyFilled(['search', 'training_id', 'status']))
                        <a href="{{ route('pemateri.survey.index') }}" class="btn btn-outline-secondary" title="Reset Filter">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    @endif
                </div>

                <div class="col-12 col-md-2">
                    <a href="{{ route('pemateri.survey.create') }}" class="btn btn-primary w-100 fw-semibold shadow-sm">
                        <i class="bi bi-plus-circle me-1"></i> Buat Survey Baru
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- TABEL SURVEY -->
    <div class="panel">
        <div class="p-0">
            @if($surveys->isEmpty())
                <div class="text-center py-5 text-muted empty-state">
                    <i class="bi bi-ui-radios fs-1 d-block mb-3 text-secondary"></i>
                    <h5 class="fw-bold text-dark">Belum Ada Survey</h5>
                    <p class="small text-muted mb-3">Anda belum membuat survey evaluasi untuk pelatihan yang diampu.</p>
                    <a href="{{ route('pemateri.survey.create') }}" class="btn btn-primary btn-sm fw-semibold rounded-pill px-3">
                        <i class="bi bi-plus-circle me-1"></i> Buat Survey Sekarang
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th style="width:50px;">No</th>
                                <th class="cell-survey">Judul Survey</th>
                                <th class="cell-pelatihan">Pelatihan Terkait</th>
                                <th style="width:140px;">Pertanyaan</th>
                                <th style="width:140px;">Responden</th>
                                <th style="width:130px;">Status</th>
                                <th style="width:200px;" class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($surveys as $index => $survey)
                                <tr>
                                    <td class="text-muted">{{ $surveys->firstItem() + $index }}</td>
                                    <td class="cell-survey">
                                        <span class="cell-title">{{ $survey->judul }}</span>
                                    </td>
                                    <td class="cell-pelatihan">
                                        <span class="cell-title" style="font-size:.82rem;font-weight:600;">
                                            <i class="bi bi-journal-text text-primary me-1"></i>{{ $survey->training->judul ?? '-' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('pemateri.survey.show', $survey) }}" class="badge bg-primary bg-opacity-10 text-primary text-decoration-none">
                                            <i class="bi bi-list-check me-1"></i> {{ $survey->questions_count }} Pertanyaan
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('pemateri.survey.show', $survey) }}#respon" class="badge bg-success bg-opacity-10 text-success text-decoration-none fw-semibold">
                                            <i class="bi bi-people-fill me-1"></i> {{ $survey->responses_count }} Responden
                                        </a>
                                    </td>
                                    <td>
                                        @if($survey->status === 'published')
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">
                                                <i class="bi bi-check-circle-fill me-1"></i> Published
                                            </span>
                                        @elseif($survey->status === 'closed')
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25">
                                                <i class="bi bi-lock-fill me-1"></i> Closed
                                            </span>
                                        @else
                                            <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 text-dark">
                                                <i class="bi bi-pencil-fill me-1"></i> Draft
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('pemateri.survey.show', $survey) }}" class="btn btn-outline-primary" title="Kelola Soal & Lihat Respon">
                                                <i class="bi bi-eye"></i> Detail
                                            </a>
                                            <a href="{{ route('pemateri.survey.edit', $survey) }}" class="btn btn-outline-warning text-dark" title="Edit Survey">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('pemateri.survey.destroy', $survey) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus survey ini beserta pertanyaan dan responnya?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-3 border-top d-flex justify-content-center">
                    {{ $surveys->links() }}
                </div>
            @endif
        </div>
    </div>

</div>
@endsection