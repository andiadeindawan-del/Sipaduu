@extends('layouts.pemateri')

@section('title', 'Manajemen Materi')

@section('header')
<div class="page-heading d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div class="page-heading-copy d-flex align-items-center gap-3">
        <span class="page-icon d-flex align-items-center justify-content-center"
              style="width:52px;height:52px;border-radius:16px;background:linear-gradient(135deg,#4a6cf7,#2548db);color:#fff;font-size:1.25rem;box-shadow:0 10px 22px rgba(74,108,247,.3);">
            <i class="bi bi-file-earmark-text"></i>
        </span>
        <div>
            <p class="eyebrow mb-1 text-uppercase fw-bold" style="letter-spacing:.12em;font-size:.7rem;color:#8a94b3;">Aktivitas Pemateri</p>
            <h1 class="h3 mb-0 fw-bold">Manajemen Materi</h1>
            <p class="text-muted mb-0 small">Upload dan kelola dokumen materi pelatihan pembelajaran.</p>
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

    <!-- ALERTS -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
            <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- FILTER & SEARCH + UPLOAD MATERI -->
    <div class="panel filter-panel mb-4">
        <div class="p-3">
            <form action="{{ route('pemateri.materi.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-12 col-md-3">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Cari judul materi..." value="{{ request('search') }}">
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-2">
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
                    <select name="kategori_id" class="form-select">
                        <option value="">Semua Kategori</option>
                        @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->id }}" {{ request('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                {{ $kategori->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-sm-6 col-md-1">
                    <select name="status" class="form-select">
                        <option value="">Status</option>
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>

                <div class="col-12 col-md-2 d-flex gap-1">
                    <button type="submit" class="btn btn-primary w-100 fw-semibold">
                        Filter
                    </button>
                    @if(request()->anyFilled(['search', 'training_id', 'kategori_id', 'status']))
                        <a href="{{ route('pemateri.materi.index') }}" class="btn btn-outline-secondary" title="Reset">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    @endif
                </div>

                <div class="col-12 col-md-2">
                    <a href="{{ route('pemateri.materi.create') }}" class="btn btn-primary w-100 fw-semibold shadow-sm">
                        <i class="bi bi-upload me-1"></i> Upload Materi
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- TABEL MATERI -->
    <div class="panel">
        <div class="p-0">
            @if($materis->isEmpty())
                <div class="text-center py-5 text-muted empty-state">
                    <i class="bi bi-file-earmark-x fs-1 d-block mb-3 text-secondary"></i>
                    <h5 class="fw-bold text-dark">Belum Ada Materi</h5>
                    <p class="small text-muted mb-3">Belum ada materi yang sesuai dengan filter pencarian.</p>
                    <a href="{{ route('pemateri.materi.create') }}" class="btn btn-primary btn-sm fw-semibold rounded-pill px-3">
                        <i class="bi bi-upload me-1"></i> Upload Materi Sekarang
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th style="width:50px;">No</th>
                                <th class="cell-judul">Judul Materi</th>
                                <th class="cell-pelatihan">Pelatihan</th>
                                <th style="width:140px;">Kategori</th>
                                <th style="width:110px;">Tipe File</th>
                                <th style="width:110px;">Status</th>
                                <th style="width:220px;" class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($materis as $index => $materi)
                                <tr>
                                    <td class="text-muted">{{ $materis->firstItem() + $index }}</td>
                                    <td class="cell-judul">
                                        <span class="cell-title">{{ $materi->judul }}</span>
                                    </td>
                                    <td class="cell-pelatihan">
                                        <span class="cell-title" style="font-size:.82rem;font-weight:600;">
                                            <i class="bi bi-journal-text text-primary me-1"></i>{{ $materi->training->judul ?? '-' }}
                                        </span>
                                        @if($materi->training && $materi->training->isFinished())
                                            <span class="cell-sub">
                                                <i class="bi bi-lock-fill me-1"></i>Selesai
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-muted">{{ $materi->kategori->nama ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-info bg-opacity-10 text-info text-uppercase">
                                            {{ $materi->tipe_file ?? 'File' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $materi->status === 'published' ? 'bg-success' : 'bg-secondary' }}">
                                            {{ ucfirst($materi->status) }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('pemateri.materi.preview', $materi) }}" target="_blank" class="btn btn-outline-info" title="Preview File">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('pemateri.materi.download', $materi) }}" class="btn btn-outline-secondary" title="Download">
                                                <i class="bi bi-download"></i>
                                            </a>
                                            @if(!($materi->training && $materi->training->isFinished()))
                                                <a href="{{ route('pemateri.materi.edit', $materi) }}" class="btn btn-outline-warning text-dark" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('pemateri.materi.destroy', $materi) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus materi ini?')">
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
                    {{ $materis->links() }}
                </div>
            @endif
        </div>
    </div>

</div>
@endsection