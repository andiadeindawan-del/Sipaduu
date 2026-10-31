@extends('layouts.pemateri')

@section('title', 'Detail Pelatihan: ' . $training->judul)

@section('header')
<div class="page-heading d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div class="page-heading-copy d-flex align-items-center gap-3">
        <span class="page-icon d-flex align-items-center justify-content-center"
              style="width:52px;height:52px;border-radius:16px;background:linear-gradient(135deg,#4a6cf7,#2548db);color:#fff;font-size:1.25rem;box-shadow:0 10px 22px rgba(74,108,247,.3);">
            <i class="bi bi-journal-check"></i>
        </span>
        <div>
            <p class="eyebrow mb-1 text-uppercase fw-bold" style="letter-spacing:.12em;font-size:.7rem;color:#8a94b3;">Pelatihan Terpilih</p>
            <p class="text-muted mb-0 small">Kelola materi dan kuis untuk pelatihan ini.</p>
        </div>
    </div>
</div>
@endsection

@section('content')
<style>
    /* ====== PANEL & SECTION ====== */
    .panel{
        background:#fff;
        border-radius:18px;
        box-shadow:0 4px 18px rgba(15,23,42,.06);
        border:1px solid rgba(15,23,42,.05) !important;
        overflow:hidden;
    }
    .panel-header{
        display:flex;justify-content:space-between;align-items:center;
        padding:1rem 1.25rem;
        border-bottom:1px solid rgba(15,23,42,.06);
        flex-wrap:wrap;gap:.5rem;
    }
    .section-title{
        font-size:1rem;font-weight:700;margin:0;
        display:flex;align-items:center;gap:.5rem;color:#0f172a;
    }

    /* ====== TRAINING HEADER CARD ====== */
    .training-hero{
        background:linear-gradient(135deg,#f8faff 0%,#ffffff 100%);
        border-left:4px solid #4a6cf7;
    }
    .training-hero .badge{
        font-size:.72rem;font-weight:600;padding:.4rem .75rem;border-radius:8px;
    }
    .training-hero h3{ color:#0f172a; letter-spacing:-.01em; }

    /* ====== TABS ====== */
    .nav-pills .nav-link{
        border-radius:12px;
        color:#6b7280;
        background:#f4f6fb;
        border:1px solid transparent;
        transition:all .2s ease;
        font-size:.9rem;
        margin-right:.4rem;
    }
    .nav-pills .nav-link:hover{ background:#eef2ff; color:#2548db; }
    .nav-pills .nav-link.active{
        background:linear-gradient(135deg,#4a6cf7,#2548db);
        color:#fff;
        box-shadow:0 8px 20px rgba(74,108,247,.3);
    }
    .nav-pills .nav-link.active i{ color:#fff !important; }

    /* ====== TABLE ====== */
    .table thead th{
        font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;
        color:#6b7280;font-weight:700;border-bottom:1px solid #eef2f7;
        background:#fafbfe;
    }
    .table tbody td{ font-size:.88rem; vertical-align:middle; border-color:#f1f4f9; }
    .table tbody tr{ transition:background .18s ease; }
    .table tbody tr:hover{ background:#f8faff; }
    .table .badge{ font-weight:600;padding:.4rem .7rem;border-radius:8px;font-size:.72rem; }

    /* ====== BUTTONS ====== */
    .btn{ border-radius:10px; transition:transform .18s ease, box-shadow .18s ease; }
    .btn:hover{ transform:translateY(-1px); }
    .btn-group-sm .btn{ border-radius:8px !important; }
    .btn-group .btn:hover{ transform:scale(1.06); }

    /* ====== EMPTY STATE ====== */
    .empty-state i{ opacity:.5; }
</style>

<div class="container-fluid px-3 px-lg-4 py-4">

    <!-- ALERT -->
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

    <!-- NOTIFIKASI PELATIHAN SELESAI -->
    @if($training->isFinished())
        <div class="alert alert-secondary border d-flex align-items-center gap-3 p-3 mb-4 rounded-3 shadow-sm">
            <div class="avatar-circle bg-secondary bg-opacity-10 text-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; min-width: 44px;">
                <i class="bi bi-flag-fill fs-5"></i>
            </div>
            <div>
                <h6 class="fw-bold mb-1 text-dark">Pelatihan Telah Selesai</h6>
                <p class="mb-0 small text-muted">Pelatihan ini telah berstatus selesai atau tanggal pelaksanaannya telah berakhir ({{ $training->tanggal_selesai ? $training->tanggal_selesai->format('d/m/Y') : '-' }}). Anda hanya dapat meninjau materi dan hasil kuis peserta. Penambahan atau pengubahan materi dan kuis baru telah ditutup.</p>
            </div>
        </div>
    @endif

    <!-- TRAINING DETAIL HEADER CARD -->
    <div class="panel mb-4 training-hero">
        <div class="p-4">
            <div class="row align-items-center g-4">
                <div class="col-12 col-md-8">
                    <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                        <span class="badge text-bg-primary">{{ $training->kategori->nama ?? 'Umum' }}</span>
                        <span class="badge text-bg-secondary text-uppercase">{{ $training->tipe ?? 'Online' }}</span>
                        @if($training->isFinished())
                            <span class="badge bg-secondary"><i class="bi bi-check-circle-fill me-1"></i> Selesai</span>
                        @else
                            <span class="badge text-bg-success">{{ ucfirst($training->status) }}</span>
                        @endif
                    </div>
                    <h3 class="fw-bold mb-2">{{ $training->judul }}</h3>
                    <p class="text-muted mb-3" style="font-size:.95rem;line-height:1.6;">
                        {!! nl2br(e($training->deskripsi)) !!}
                    </p>
                    <div class="d-flex flex-wrap gap-3 text-muted small">
                        <span><i class="bi bi-calendar-event me-1 text-primary"></i> Mulai: {{ $training->tanggal_mulai ? $training->tanggal_mulai->format('d/m/Y') : '-' }}</span>
                        <span><i class="bi bi-calendar-check me-1 text-primary"></i> Selesai: {{ $training->tanggal_selesai ? $training->tanggal_selesai->format('d/m/Y') : '-' }}</span>
                        @if($training->lokasi)
                            <span><i class="bi bi-geo-alt me-1 text-danger"></i> {{ $training->lokasi }}</span>
                        @endif
                        @if($training->link_meeting)
                            <span><i class="bi bi-camera-video me-1 text-info"></i> <a href="{{ $training->link_meeting }}" target="_blank">Link Meeting</a></span>
                        @endif
                    </div>
                </div>
                <div class="col-12 col-md-4 text-md-end">
                    <div class="d-flex flex-column gap-2">
                        <a href="{{ route('pemateri.absen.session', $training->id) }}" class="btn fw-semibold shadow-sm {{ $training->is_absen_open ? 'btn-success' : 'text-white' }}" style="{{ $training->is_absen_open ? '' : 'background:linear-gradient(135deg,#6f42c1,#5a32a3);border:none;' }}">
                            <i class="bi bi-qr-code-scan me-1"></i> {{ $training->is_absen_open ? 'Sesi QR Absen Aktif (Buka)' : 'Sesi Absensi & QR Code' }}
                        </a>
                        @if($training->isFinished())
                            <div class="p-3 rounded-3 bg-white border text-center shadow-sm">
                                <i class="bi bi-lock-fill text-secondary fs-4 d-block mb-1"></i>
                                <span class="badge bg-secondary text-white mb-1"><i class="bi bi-check2-circle me-1"></i> Pelatihan Telah Selesai</span>
                                <small class="d-block text-muted">Penambahan materi &amp; kuis baru telah ditutup.</small>
                            </div>
                        @else
                            <a href="{{ route('pemateri.materi.create', ['training_id' => $training->id]) }}" class="btn btn-danger fw-semibold">
                                <i class="bi bi-upload me-1"></i> Upload Materi Baru
                            </a>
                            <a href="{{ route('pemateri.quiz.create', ['training_id' => $training->id]) }}" class="btn btn-warning fw-semibold text-dark">
                                <i class="bi bi-plus-circle me-1"></i> Tambah Kuis Baru
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TABS MATERI, KUIS, SURVEY & DOKUMENTASI -->
    <ul class="nav nav-pills mb-3 flex-wrap gap-1" id="trainingTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active px-3 fw-semibold" id="materi-tab" data-bs-toggle="tab" data-bs-target="#materi-pane" type="button" role="tab">
                <i class="bi bi-file-earmark-text me-1 text-danger"></i> Materi Pelatihan ({{ $materis->count() }})
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link px-3 fw-semibold" id="quiz-tab" data-bs-toggle="tab" data-bs-target="#quiz-pane" type="button" role="tab">
                <i class="bi bi-question-circle me-1 text-warning"></i> Kuis Evaluasi ({{ $quizzes->count() }})
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link px-3 fw-semibold" id="survey-tab" data-bs-toggle="tab" data-bs-target="#survey-pane" type="button" role="tab">
                <i class="bi bi-ui-radios me-1" style="color: #00cfe8;"></i> Survey ({{ $surveys->count() }})
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link px-3 fw-semibold" id="dokumentasi-tab" data-bs-toggle="tab" data-bs-target="#dokumentasi-pane" type="button" role="tab">
                <i class="bi bi-images me-1 text-success"></i> Dokumentasi ({{ $dokumentasis->count() }})
            </button>
        </li>
    </ul>

    <div class="tab-content" id="trainingTabsContent">
        <!-- TAB 1: MATERI -->
        <div class="tab-pane fade show active" id="materi-pane" role="tabpanel">
            <div class="panel">
                <div class="panel-header">
                    <h5 class="section-title"><i class="bi bi-files" style="color:#e83e8c;"></i> Daftar Materi Pelatihan</h5>
                    @if($training->isFinished())
                        <span class="badge bg-secondary"><i class="bi bi-lock-fill me-1"></i> Selesai (Konten Ditutup)</span>
                    @endif
                </div>
                <div class="p-0">
                    @if($materis->isEmpty())
                        <div class="text-center py-5 text-muted empty-state">
                            <i class="bi bi-file-earmark-x fs-1 d-block mb-2 text-secondary"></i>
                            <p class="mb-0">Belum ada materi untuk pelatihan ini.</p>
                            @if(!$training->isFinished())
                                <div class="mt-3">
                                    <a href="{{ route('pemateri.materi.create', ['training_id' => $training->id]) }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                        <i class="bi bi-plus-circle me-1"></i> Upload Materi Sekarang
                                    </a>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th style="width:50px;">#</th>
                                        <th>Judul Materi</th>
                                        <th>Kategori</th>
                                        <th>Tipe File</th>
                                        <th>Durasi</th>
                                        <th>Status</th>
                                        <th style="width:180px;" class="text-end">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($materis as $index => $materi)
                                        <tr>
                                            <td class="text-muted">{{ $index + 1 }}</td>
                                            <td>
                                                <div class="fw-semibold text-dark">{{ $materi->judul }}</div>
                                                <small class="text-muted">{{ Str::limit($materi->deskripsi, 60) }}</small>
                                            </td>
                                            <td><span class="badge bg-light text-dark border">{{ $materi->kategori->nama ?? '-' }}</span></td>
                                            <td>
                                                <span class="badge bg-info bg-opacity-10 text-info text-uppercase">
                                                    {{ $materi->tipe_file ?? 'File' }}
                                                </span>
                                            </td>
                                            <td class="text-muted">{{ $materi->durasi ? $materi->durasi . ' menit' : '-' }}</td>
                                            <td>
                                                <span class="badge {{ $materi->status === 'published' ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ ucfirst($materi->status) }}
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('pemateri.materi.show', $materi) }}" class="btn btn-outline-info" title="Lihat">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    @if(!$training->isFinished())
                                                        <a href="{{ route('pemateri.materi.edit', $materi) }}" class="btn btn-outline-secondary" title="Edit">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>
                                                        <form action="{{ route('pemateri.materi.destroy', $materi) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus materi ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-outline-danger" title="Hapus">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- TAB 2: KUIS -->
        <div class="tab-pane fade" id="quiz-pane" role="tabpanel">
            <div class="panel">
                <div class="panel-header">
                    <h5 class="section-title"><i class="bi bi-question-circle" style="color:#fd7e14;"></i> Daftar Kuis Pelatihan</h5>
                    @if($training->isFinished())
                        <span class="badge bg-secondary"><i class="bi bi-lock-fill me-1"></i> Selesai (Konten Ditutup)</span>
                    @else
                        <a href="{{ route('pemateri.quiz.create', ['training_id' => $training->id]) }}" class="btn btn-sm btn-warning text-dark fw-semibold">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Kuis
                        </a>
                    @endif
                </div>
                <div class="p-0">
                    @if($quizzes->isEmpty())
                        <div class="text-center py-5 text-muted empty-state">
                            <i class="bi bi-question-square fs-1 d-block mb-2 text-secondary"></i>
                            <p class="mb-0">Belum ada kuis untuk pelatihan ini.</p>
                            @if(!$training->isFinished())
                                <div class="mt-3">
                                    <a href="{{ route('pemateri.quiz.create', ['training_id' => $training->id]) }}" class="btn btn-outline-warning text-dark btn-sm rounded-pill px-3">
                                        <i class="bi bi-plus-circle me-1"></i> Buat Kuis Sekarang
                                    </a>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th style="width:50px;">#</th>
                                        <th>Judul Kuis</th>
                                        <th>Jumlah Soal</th>
                                        <th>Durasi</th>
                                        <th>Nilai Kelulusan</th>
                                        <th>Status</th>
                                        <th style="width:200px;" class="text-end">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($quizzes as $index => $quiz)
                                        <tr>
                                            <td class="text-muted">{{ $index + 1 }}</td>
                                            <td>
                                                <div class="fw-semibold text-dark">{{ $quiz->judul }}</div>
                                                <small class="text-muted">{{ Str::limit($quiz->deskripsi, 60) }}</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary bg-opacity-10 text-primary">
                                                    {{ $quiz->questions_count }} Pertanyaan
                                                </span>
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
                                                    <a href="{{ route('pemateri.quiz.show', $quiz) }}" class="btn {{ $training->isFinished() ? 'btn-outline-info' : 'btn-outline-primary' }}" title="{{ $training->isFinished() ? 'Lihat Soal & Hasil Peserta' : 'Kelola Soal' }}">
                                                        <i class="bi bi-ui-checks me-1"></i> {{ $training->isFinished() ? 'Soal & Hasil' : 'Soal' }}
                                                    </a>
                                                    @if(!$training->isFinished())
                                                        <a href="{{ route('pemateri.quiz.edit', $quiz) }}" class="btn btn-outline-secondary" title="Edit">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>
                                                        <form action="{{ route('pemateri.quiz.destroy', $quiz) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kuis ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-outline-danger" title="Hapus">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- TAB 3: SURVEY -->
        <div class="tab-pane fade" id="survey-pane" role="tabpanel">
            <div class="panel">
                <div class="panel-header">
                    <h5 class="section-title"><i class="bi bi-ui-radios" style="color:#00cfe8;"></i> Daftar Survey Kepuasan Pelatihan</h5>
                    <a href="{{ route('pemateri.survey.create', ['training_id' => $training->id]) }}" class="btn btn-sm btn-info text-white fw-semibold">
                        <i class="bi bi-plus-circle me-1"></i> Buat Survey
                    </a>
                </div>
                <div class="p-0">
                    @if($surveys->isEmpty())
                        <div class="text-center py-5 text-muted empty-state">
                            <i class="bi bi-ui-radios fs-1 d-block mb-2 text-secondary"></i>
                            <p class="mb-0">Belum ada survey evaluasi untuk pelatihan ini.</p>
                            <div class="mt-3">
                                <a href="{{ route('pemateri.survey.create', ['training_id' => $training->id]) }}" class="btn btn-outline-info btn-sm rounded-pill px-3">
                                    <i class="bi bi-plus-circle me-1"></i> Buat Survey Sekarang
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th style="width:50px;">#</th>
                                        <th>Judul Survey</th>
                                        <th>Pertanyaan</th>
                                        <th>Responden</th>
                                        <th>Status</th>
                                        <th style="width:180px;" class="text-end">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($surveys as $index => $survey)
                                        <tr>
                                            <td class="text-muted">{{ $index + 1 }}</td>
                                            <td>
                                                <div class="fw-semibold text-dark">{{ $survey->judul }}</div>
                                                <small class="text-muted">{{ Str::limit($survey->deskripsi ?? '', 60) }}</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary bg-opacity-10 text-primary">
                                                    {{ $survey->questions_count }} Pertanyaan
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-success bg-opacity-10 text-success">
                                                    {{ $survey->responses_count }} Responden
                                                </span>
                                            </td>
                                            <td>
                                                @if($survey->status === 'published')
                                                    <span class="badge bg-success">Published</span>
                                                @elseif($survey->status === 'closed')
                                                    <span class="badge bg-secondary">Closed</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">Draft</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('pemateri.survey.show', $survey) }}" class="btn btn-outline-primary" title="Detail & Respon">
                                                        <i class="bi bi-eye"></i> Detail
                                                    </a>
                                                    <a href="{{ route('pemateri.survey.edit', $survey) }}" class="btn btn-outline-secondary" title="Edit">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <form action="{{ route('pemateri.survey.destroy', $survey) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus survey ini?')">
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
                    @endif
                </div>
            </div>
        </div>

        <!-- TAB 4: DOKUMENTASI -->
        <div class="tab-pane fade" id="dokumentasi-pane" role="tabpanel">
            <div class="panel">
                <div class="panel-header">
                    <h5 class="section-title"><i class="bi bi-images" style="color:#28c76f;"></i> Daftar Dokumentasi Pelatihan</h5>
                    <a href="{{ route('pemateri.dokumentasi.create', ['training_id' => $training->id]) }}" class="btn btn-sm btn-success fw-semibold">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Dokumentasi
                    </a>
                </div>
                <div class="p-0">
                    @if($dokumentasis->isEmpty())
                        <div class="text-center py-5 text-muted empty-state">
                            <i class="bi bi-images fs-1 d-block mb-2 text-secondary"></i>
                            <p class="mb-0">Belum ada dokumentasi untuk pelatihan ini.</p>
                            <div class="mt-3">
                                <a href="{{ route('pemateri.dokumentasi.create', ['training_id' => $training->id]) }}" class="btn btn-outline-success btn-sm rounded-pill px-3">
                                    <i class="bi bi-plus-circle me-1"></i> Tambah Dokumentasi Sekarang
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th style="width:50px;">#</th>
                                        <th>Judul Dokumentasi</th>
                                        <th>Tautan / Link</th>
                                        <th>Tanggal Dibuat</th>
                                        <th style="width:180px;" class="text-end">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($dokumentasis as $index => $doc)
                                        <tr>
                                            <td class="text-muted">{{ $index + 1 }}</td>
                                            <td>
                                                <div class="fw-semibold text-dark">{{ $doc->judul }}</div>
                                                <small class="text-muted">{{ Str::limit($doc->deskripsi ?? '', 60) }}</small>
                                            </td>
                                            <td>
                                                <a href="{{ $doc->link }}" target="_blank" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1">
                                                    <i class="bi bi-box-arrow-up-right"></i> Buka Link
                                                </a>
                                            </td>
                                            <td class="text-muted small">
                                                {{ $doc->created_at ? $doc->created_at->format('d/m/Y') : '-' }}
                                            </td>
                                            <td class="text-end">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('pemateri.dokumentasi.show', $doc) }}" class="btn btn-outline-info" title="Lihat">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('pemateri.dokumentasi.edit', $doc) }}" class="btn btn-outline-secondary" title="Edit">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <form action="{{ route('pemateri.dokumentasi.destroy', $doc) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus dokumentasi ini?')">
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
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
@endsection