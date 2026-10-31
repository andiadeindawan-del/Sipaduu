@extends('layouts.pemateri')

@section('title', 'Detail Survey: ' . $survey->judul)

@section('header')
<div class="page-heading d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div class="page-heading-copy d-flex align-items-center gap-3">
        <span class="page-icon d-flex align-items-center justify-content-center"
              style="width:52px;height:52px;border-radius:16px;background:linear-gradient(135deg,#4a6cf7,#2548db);color:#fff;font-size:1.25rem;box-shadow:0 10px 22px rgba(74,108,247,.3);">
            <i class="bi bi-ui-radios"></i>
        </span>
        <div>
            <p class="eyebrow mb-1 text-uppercase fw-bold" style="letter-spacing:.12em;font-size:.7rem;color:#8a94b3;">Aktivitas Pemateri</p>
            <p class="text-muted mb-0 small">Kelola pertanyaan evaluasi dan pantau umpan balik dari peserta pelatihan.</p>
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

    /* ====== SURVEY HERO ====== */
    .survey-hero{
        background:linear-gradient(135deg,#f8faff 0%,#ffffff 100%);
        border-left:4px solid #4a6cf7;
    }
    .survey-hero .badge{
        font-size:.72rem;font-weight:600;padding:.4rem .75rem;border-radius:8px;
    }
    .survey-hero .stat-box{
        background:#fff;
        border:1px solid #eef2f7;
        border-radius:12px;
        padding:.85rem .5rem;
        transition:transform .2s ease, box-shadow .2s ease;
    }
    .survey-hero .stat-box:hover{
        transform:translateY(-2px);
        box-shadow:0 8px 18px rgba(15,23,42,.06);
    }

    /* ====== TABS ====== */
    .nav-pills .nav-link{
        border-radius:12px;
        color:#6b7280;
        background:#f4f6fb;
        border:1px solid transparent;
        transition:all .2s ease;
        font-size:.88rem;
        padding:.6rem 1rem;
    }
    .nav-pills .nav-link:hover{ background:#eef2ff; color:#2548db; }
    .nav-pills .nav-link.active{
        background:linear-gradient(135deg,#4a6cf7,#2548db);
        color:#fff;
        box-shadow:0 8px 20px rgba(74,108,247,.3);
    }
    .nav-pills .nav-link.active .badge{
        background:#fff !important;
        color:#2548db !important;
        border-color:transparent !important;
    }

    /* ====== QUESTION CARD ====== */
    .question-card{
        border:1px solid #eef2f7;
        border-radius:14px;
        padding:1rem 1.1rem;
        background:#fff;
        transition:border-color .2s ease, box-shadow .2s ease, transform .2s ease;
    }
    .question-card:hover{
        border-color:#d6def7;
        box-shadow:0 8px 22px rgba(15,23,42,.07);
        transform:translateY(-2px);
    }
    .question-card .q-number{
        width:30px;height:30px;border-radius:50%;
        background:linear-gradient(135deg,#4a6cf7,#2548db);
        color:#fff;font-weight:700;font-size:.8rem;
        display:inline-flex;align-items:center;justify-content:center;
        flex-shrink:0;
    }
    .question-card .q-text{
        font-size:.95rem;line-height:1.55;color:#0f172a;
    }
    .question-card .badge{
        font-size:.7rem;font-weight:600;padding:.4rem .65rem;border-radius:8px;
    }

    /* ====== TABLE ====== */
    .table thead th{
        font-size:.72rem;text-transform:uppercase;letter-spacing:.06em;
        color:#6b7280;font-weight:700;border-bottom:1px solid #eef2f7;
        background:#fafbfe;padding:.85rem 1rem;
    }
    .table tbody td{
        font-size:.88rem;vertical-align:middle;
        border-color:#f1f4f9;padding:.85rem 1rem;
    }
    .table tbody tr{ transition:background .18s ease; }
    .table tbody tr:hover{ background:#f8faff; }
    .table .badge{ font-weight:600;padding:.45rem .7rem;border-radius:8px;font-size:.72rem; }

    /* ====== BUTTONS ====== */
    .btn{ border-radius:10px; transition:transform .18s ease, box-shadow .18s ease; }
    .btn:hover{ transform:translateY(-1px); }
    .btn-group-sm .btn{ border-radius:8px !important; }
    .btn-group .btn:hover{ transform:scale(1.06); }

    /* ====== RESPONSE DETAIL ====== */
    .answer-item{
        background:#fff;
        border:1px solid #eef2f7;
        border-radius:10px;
        padding:.65rem .85rem;
    }
    .answer-item .answer-q{
        font-size:.82rem;font-weight:600;color:#0f172a;margin-bottom:.35rem;
    }
    .answer-item .answer-val{ font-size:.85rem; }

    /* ====== EMPTY STATE ====== */
    .empty-state i{ opacity:.5; }
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

    <!-- RINGKASAN SURVEY CARD -->
    <div class="panel mb-4 survey-hero">
        <div class="p-4">
            <div class="row align-items-center g-4">
                <div class="col-12 col-lg-8">
                    <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                        <span class="badge text-bg-primary">{{ $survey->training->judul ?? 'Umum' }}</span>
                        @if($survey->status === 'published')
                            <span class="badge text-bg-success"><i class="bi bi-check-circle-fill me-1"></i> Published (Aktif)</span>
                        @elseif($survey->status === 'closed')
                            <span class="badge text-bg-secondary"><i class="bi bi-lock-fill me-1"></i> Closed (Ditutup)</span>
                        @else
                            <span class="badge text-bg-warning text-dark"><i class="bi bi-pencil-fill me-1"></i> Draft</span>
                        @endif
                    </div>
                    <h4 class="fw-bold text-dark mb-2">{{ $survey->judul }}</h4>
                    @if($survey->deskripsi)
                        <p class="text-muted mb-3 small" style="line-height:1.6;">{{ $survey->deskripsi }}</p>
                    @endif
                    <div class="d-flex flex-wrap gap-3 text-muted small">
                        <span><i class="bi bi-calendar3 me-1 text-primary"></i> Dibuat: <strong>{{ $survey->created_at ? $survey->created_at->format('d/m/Y H:i') : '-' }}</strong></span>
                        <span><i class="bi bi-clock-history me-1 text-info"></i> Diperbarui: <strong>{{ $survey->updated_at ? $survey->updated_at->format('d/m/Y H:i') : '-' }}</strong></span>
                    </div>
                </div>
                <div class="col-12 col-lg-4 ps-lg-4">
                    <div class="row g-2 text-center">
                        <div class="col-6">
                            <div class="stat-box">
                                <span class="text-muted small d-block">Butir Pertanyaan</span>
                                <span class="fs-3 fw-bold text-primary">{{ $survey->questions->count() }}</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-box">
                                <span class="text-muted small d-block">Peserta Mengisi</span>
                                <span class="fs-3 fw-bold text-success">{{ $survey->responses->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- NAV TABS -->
    <ul class="nav nav-pills mb-3 gap-2" id="surveyTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active fw-semibold" id="questions-tab" data-bs-toggle="pill" data-bs-target="#tab-questions" type="button" role="tab" aria-controls="tab-questions" aria-selected="true">
                <i class="bi bi-list-check me-1"></i> Butir Pertanyaan Survey
                <span class="badge bg-light text-primary ms-1 border">{{ $survey->questions->count() }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-semibold" id="responses-tab" data-bs-toggle="pill" data-bs-target="#tab-responses" type="button" role="tab" aria-controls="tab-responses" aria-selected="false">
                <i class="bi bi-people-fill me-1"></i> Hasil &amp; Respon Peserta
                <span class="badge bg-light text-success ms-1 border">{{ $survey->responses->count() }} Responden</span>
            </button>
        </li>
    </ul>

    <!-- TAB CONTENT -->
    <div class="tab-content" id="surveyTabsContent">

        <!-- ========================================== -->
        <!-- TAB 1: BUTIR PERTANYAAN -->
        <!-- ========================================== -->
        <div class="tab-pane fade show active" id="tab-questions" role="tabpanel" aria-labelledby="questions-tab">
            <div class="panel">
                <div class="panel-header">
                    <h5 class="section-title"><i class="bi bi-list-check" style="color:#4a6cf7;"></i> Daftar Butir Pertanyaan</h5>
                    <a href="{{ route('pemateri.survey.questions.create', $survey->id) }}" class="btn btn-primary btn-sm fw-semibold">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Pertanyaan Baru
                    </a>
                </div>
                <div class="p-4">
                    @if($survey->questions->isEmpty())
                        <div class="text-center py-5 text-muted empty-state">
                            <i class="bi bi-ui-radios fs-1 d-block mb-3 text-secondary"></i>
                            <h5 class="fw-bold text-dark">Survey Belum Memiliki Pertanyaan</h5>
                            <p class="small text-muted mb-3">Tambahkan butir pertanyaan untuk mengumpulkan umpan balik dari peserta pelatihan.</p>
                            <a href="{{ route('pemateri.survey.questions.create', $survey->id) }}" class="btn btn-primary fw-semibold rounded-pill px-3">
                                <i class="bi bi-plus-circle me-1"></i> Tambah Pertanyaan Pertama
                            </a>
                        </div>
                    @else
                        <div class="d-flex flex-column gap-3">
                            @foreach($survey->questions as $index => $q)
                                <div class="question-card">
                                    <div class="d-flex justify-content-between align-items-start mb-2 gap-2 flex-wrap">
                                        <div class="d-flex align-items-center gap-2 flex-wrap">
                                            <span class="q-number">{{ $index + 1 }}</span>
                                            @if($q->tipe === 'rating_5')
                                                <span class="badge bg-warning bg-opacity-10 text-dark border border-warning border-opacity-25">
                                                    <i class="bi bi-star-fill text-warning me-1"></i> Rating Skala 1 - 5
                                                </span>
                                            @elseif($q->tipe === 'boolean')
                                                <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25">
                                                    <i class="bi bi-toggle-on me-1"></i> Ya / Tidak
                                                </span>
                                            @else
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25">
                                                    <i class="bi bi-chat-left-text me-1"></i> Teks / Esai Saran
                                                </span>
                                            @endif
                                        </div>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('pemateri.survey.questions.edit', [$survey->id, $q->id]) }}" class="btn btn-outline-secondary" title="Edit Pertanyaan">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                            <form action="{{ route('pemateri.survey.questions.destroy', [$survey->id, $q->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus pertanyaan ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    <div class="q-text ps-1">
                                        {!! nl2br(e($q->pertanyaan)) !!}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 2: HASIL & RESPON PESERTA -->
        <!-- ========================================== -->
        <div class="tab-pane fade" id="tab-responses" role="tabpanel" aria-labelledby="responses-tab">
            <div class="panel">
                <div class="panel-header">
                    <div>
                        <h5 class="section-title"><i class="bi bi-people-fill" style="color:#28c76f;"></i> Umpan Balik &amp; Respon Peserta</h5>
                        <p class="text-muted small mb-0">Daftar seluruh peserta yang telah mengisi survey evaluasi ini.</p>
                    </div>
                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2">
                        <i class="bi bi-check-circle-fill me-1"></i> {{ $survey->responses->count() }} Responden Terkumpul
                    </span>
                </div>

                <div class="p-0">
                    @if($survey->responses->isEmpty())
                        <div class="text-center py-5 text-muted empty-state">
                            <i class="bi bi-chat-square-dots fs-1 d-block mb-3 text-secondary"></i>
                            <h5 class="fw-bold text-dark">Belum Ada Respon Peserta</h5>
                            <p class="small text-muted mb-0">Belum ada peserta yang mengisi survey evaluasi ini.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th style="width:50px;">#</th>
                                        <th>Nama Peserta</th>
                                        <th>Email</th>
                                        <th>Tanggal Pengisian</th>
                                        <th>Ringkasan Jawaban</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($survey->responses as $idx => $resp)
                                        @php
                                            $answers = is_array($resp->answers) ? $resp->answers : (json_decode($resp->answers, true) ?? []);
                                        @endphp
                                        <tr>
                                            <td class="text-muted">{{ $idx + 1 }}</td>
                                            <td>
                                                <div class="fw-semibold text-dark">{{ $resp->user->name ?? $resp->user->nama ?? 'Peserta' }}</div>
                                            </td>
                                            <td>
                                                <span class="text-muted small">{{ $resp->user->email ?? '-' }}</span>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    {{ $resp->created_at ? $resp->created_at->format('d/m/Y H:i') : '-' }}
                                                </small>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-info" type="button" data-bs-toggle="collapse" data-bs-target="#resp-{{ $resp->id }}">
                                                    <i class="bi bi-eye me-1"></i> Lihat Jawaban
                                                </button>
                                            </td>
                                        </tr>
                                        <tr class="collapse" id="resp-{{ $resp->id }}">
                                            <td colspan="5" class="bg-light p-3">
                                                <div class="card border-0 shadow-sm p-3 rounded-3">
                                                    <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-chat-left-quote me-1"></i> Rincian Jawaban oleh {{ $resp->user->nama ?? $resp->user->name ?? 'Peserta' }}:</h6>
                                                    <div class="d-flex flex-column gap-2">
                                                        @foreach($survey->questions as $qIndex => $question)
                                                            @php
                                                                $val = $answers[$question->id] ?? '-';
                                                            @endphp
                                                            <div class="answer-item">
                                                                <div class="answer-q">
                                                                    {{ $qIndex + 1 }}. {{ $question->pertanyaan }}
                                                                </div>
                                                                <div class="answer-val">
                                                                    @if($question->tipe === 'rating_5')
                                                                        <span class="badge bg-warning text-dark">
                                                                            <i class="bi bi-star-fill text-warning me-1"></i> Nilai: {{ $val }} / 5
                                                                        </span>
                                                                    @elseif($question->tipe === 'boolean')
                                                                        <span class="badge {{ $val == '1' || strtolower($val) == 'ya' ? 'bg-success' : 'bg-secondary' }}">
                                                                            {{ $val == '1' || strtolower($val) == 'ya' ? 'Ya' : 'Tidak' }}
                                                                        </span>
                                                                    @else
                                                                        <span class="text-dark fst-italic">"{{ $val }}"</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
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