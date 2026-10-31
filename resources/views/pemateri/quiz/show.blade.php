@extends('layouts.pemateri')

@section('title', 'Detail Kuis: ' . $quiz->judul)

@section('header')
<div class="page-heading d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-question-circle"></i></span>
        <div>
            <p class="eyebrow">Aktivitas Pemateri</p>
            <p class="text-muted mb-0">Kelola soal pertanyaan dan pantau hasil pengisian kuis oleh peserta.</p>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">

    <!-- ALERTS -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i> {!! session('success') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i> {!! session('error') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- NOTIFIKASI PELATIHAN SELESAI -->
    @if($quiz->training && $quiz->training->isFinished())
        <div class="alert alert-secondary border d-flex align-items-center gap-3 p-3 mb-4 rounded-3 shadow-sm">
            <div class="avatar-circle bg-secondary bg-opacity-10 text-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; min-width: 44px;">
                <i class="bi bi-flag-fill fs-5"></i>
            </div>
            <div>
                <h6 class="fw-bold mb-1 text-dark">Pelatihan Telah Selesai</h6>
                <p class="mb-0 small text-muted">Pelatihan terkait kuis ini telah berstatus selesai atau tanggalnya telah berakhir. Penambahan butir soal atau pengubahan kuis telah ditutup. Anda tetap dapat meninjau soal dan hasil evaluasi peserta di bawah ini.</p>
            </div>
        </div>
    @endif

    <!-- RINGKASAN KUIS CARD -->
    <div class="panel border-0 shadow-sm mb-4">
        <div class="p-4">
            <div class="row align-items-center g-3">
                <div class="col-12 col-lg-8">
                    <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                        <span class="badge bg-primary">{{ $quiz->training->judul ?? 'Umum' }}</span>
                        @if($quiz->materi)
                            <span class="badge bg-info text-dark">Materi: {{ $quiz->materi->judul }}</span>
                        @endif
                        <span class="badge {{ $quiz->status === 'published' ? 'bg-success' : 'bg-secondary' }}">
                            {{ ucfirst($quiz->status) }}
                        </span>
                        @if($quiz->is_random)
                            <span class="badge bg-secondary"><i class="bi bi-shuffle me-1"></i> Acak Soal</span>
                        @endif
                    </div>
                    <h4 class="fw-bold text-dark mb-2">{{ $quiz->judul }}</h4>
                    @if($quiz->deskripsi)
                        <p class="text-muted mb-3 small">{{ $quiz->deskripsi }}</p>
                    @endif
                    <div class="d-flex flex-wrap gap-3 text-muted small">
                        <span><i class="bi bi-clock me-1 text-primary"></i> Durasi: <strong>{{ $quiz->durasi ? $quiz->durasi . ' menit' : 'Tidak dibatasi' }}</strong></span>
                        <span><i class="bi bi-award me-1 text-warning"></i> Passing Score: <strong>{{ $quiz->passing_score }}%</strong></span>
                        <span><i class="bi bi-arrow-repeat me-1 text-info"></i> Maksimal Percobaan: <strong>{{ $quiz->max_attempt }}x</strong></span>
                    </div>
                </div>
                <div class="col-12 col-lg-4 border-start-lg ps-lg-4">
                    <div class="row g-2 text-center">
                        <div class="col-6">
                            <div class="p-2.5 rounded bg-light border">
                                <span class="text-muted small d-block">Butir Soal</span>
                                <span class="fs-4 fw-bold text-primary">{{ $totalQuestions }}</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2.5 rounded bg-light border">
                                <span class="text-muted small d-block">Peserta Mengisi</span>
                                <span class="fs-4 fw-bold text-success">{{ $totalParticipants }}</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2.5 rounded bg-light border">
                                <span class="text-muted small d-block">Rata-rata Nilai</span>
                                <span class="fs-4 fw-bold text-info">{{ number_format($averageScore, 1) }}</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2.5 rounded bg-light border">
                                <span class="text-muted small d-block">Kelulusan</span>
                                <span class="fs-4 fw-bold text-warning">{{ number_format($passingRate, 1) }}%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- NAV TABS -->
    <ul class="nav nav-pills mb-3 gap-2" id="quizTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active fw-semibold" id="questions-tab" data-bs-toggle="pill" data-bs-target="#tab-questions" type="button" role="tab" aria-controls="tab-questions" aria-selected="true">
                <i class="bi bi-ui-checks me-1.5"></i> Butir Soal Pertanyaan
                <span class="badge bg-light text-primary ms-1.5 border">{{ $totalQuestions }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-semibold" id="results-tab" data-bs-toggle="pill" data-bs-target="#tab-results" type="button" role="tab" aria-controls="tab-results" aria-selected="false">
                <i class="bi bi-people-fill me-1.5"></i> Hasil & Peserta yang Mengisi
                <span class="badge bg-light text-success ms-1.5 border">{{ $totalParticipants }} Peserta</span>
            </button>
        </li>
    </ul>

    <!-- TAB CONTENT -->
    <div class="tab-content" id="quizTabsContent">

        <!-- ========================================== -->
        <!-- TAB 1: BUTIR PERTANYAAN -->
        <!-- ========================================== -->
        <div class="tab-pane fade show active" id="tab-questions" role="tabpanel" aria-labelledby="questions-tab">
            <div class="panel border-0 shadow-sm">
                <div class="panel-header d-flex justify-content-between align-items-center">
                    <h5 class="section-title"><i class="bi bi-ui-checks" style="color: #4a6cf7;"></i> Daftar Butir Pertanyaan</h5>
                    @if(!($quiz->training && $quiz->training->isFinished()))
                        <a href="{{ route('pemateri.quiz.questions.create', $quiz) }}" class="btn btn-primary btn-sm fw-semibold">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Soal Baru
                        </a>
                    @else
                        <span class="badge bg-secondary"><i class="bi bi-lock-fill me-1"></i> Terkunci (Selesai)</span>
                    @endif
                </div>
                <div class="p-4">
                    @if($quiz->questions->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-ui-radios fs-1 d-block mb-3 text-secondary"></i>
                            <h5>Kuis ini belum memiliki pertanyaan</h5>
                            @if(!($quiz->training && $quiz->training->isFinished()))
                                <p class="small text-muted mb-3">Silakan klik tombol di bawah untuk menambahkan butir pertanyaan.</p>
                                <a href="{{ route('pemateri.quiz.questions.create', $quiz) }}" class="btn btn-primary fw-semibold">
                                    <i class="bi bi-plus-circle me-1"></i> Tambah Pertanyaan Pertama
                                </a>
                            @else
                                <p class="small text-muted mb-0">Pelatihan telah selesai dan kuis tidak memiliki pertanyaan.</p>
                            @endif
                        </div>
                    @else
                        <div class="d-flex flex-column gap-3">
                            @foreach($quiz->questions as $index => $q)
                                <div class="card border rounded-3 p-3">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-primary rounded-circle p-2" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center;">
                                                {{ $index + 1 }}
                                            </span>
                                            <span class="badge bg-light text-dark border small text-uppercase">
                                                {{ str_replace('_', ' ', $q->type) }}
                                            </span>
                                            <span class="badge bg-warning bg-opacity-25 text-dark small">
                                                {{ $q->points ?? $q->score ?? 1 }} Poin
                                            </span>
                                        </div>
                                        @if(!($quiz->training && $quiz->training->isFinished()))
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('pemateri.quiz.questions.edit', [$quiz, $q]) }}" class="btn btn-outline-secondary" title="Edit Soal">
                                                    <i class="bi bi-pencil"></i> Edit
                                                </a>
                                                <form action="{{ route('pemateri.quiz.questions.destroy', [$quiz, $q]) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus pertanyaan ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger" title="Hapus">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="fw-semibold text-dark mb-3 ps-1" style="font-size: 1rem;">
                                        {!! nl2br(e($q->question ?? $q->pertanyaan)) !!}
                                    </div>

                                    <!-- OPSI JAWABAN -->
                                    @if($q->type === 'multiple_choice' && !empty($q->options))
                                        <div class="row g-2 ps-1">
                                            @php
                                                $options = is_array($q->options) ? $q->options : json_decode($q->options, true) ?? [];
                                                $labels = ['A', 'B', 'C', 'D', 'E', 'F'];
                                            @endphp
                                            @foreach($options as $optIdx => $optText)
                                                @php
                                                    $optKey = $labels[$optIdx] ?? ($optIdx + 1);
                                                    $isCorrect = strtoupper(trim($q->correct_answer ?? $q->jawaban_benar)) === $optKey;
                                                @endphp
                                                <div class="col-12 col-md-6">
                                                    <div class="p-2.5 rounded border {{ $isCorrect ? 'bg-success bg-opacity-10 border-success text-success fw-bold' : 'bg-light text-dark' }}" style="font-size: 0.88rem;">
                                                        <span class="badge {{ $isCorrect ? 'bg-success' : 'bg-secondary' }} me-1.5">{{ $optKey }}</span>
                                                        {{ $optText }}
                                                        @if($isCorrect)
                                                            <i class="bi bi-check-circle-fill text-success ms-1" title="Kunci Jawaban Benar"></i>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @elseif($q->type === 'true_false')
                                        <div class="ps-1 small text-muted">
                                            Kunci Jawaban: <span class="badge bg-success">{{ $q->correct_answer ?? 'Benar' }}</span>
                                        </div>
                                    @elseif($q->type === 'essay')
                                        <div class="ps-1 small text-muted">
                                            Kunci Jawaban / Pedoman: <div class="p-2 bg-light rounded mt-1">{{ $q->essay_answer_key ?? '-' }}</div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 2: HASIL & PESERTA YANG MENGISI KUIS -->
        <!-- ========================================== -->
        <div class="tab-pane fade" id="tab-results" role="tabpanel" aria-labelledby="results-tab">
            <div class="panel border-0 shadow-sm">
                <div class="panel-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <h5 class="section-title"><i class="bi bi-people-fill" style="color: #28c76f;"></i> Hasil Pengisian Peserta</h5>
                        <p class="text-muted small mb-0">Daftar seluruh peserta yang telah mengerjakan kuis evaluasi ini beserta perolehan nilainya.</p>
                    </div>
                    <div>
                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1.5">
                            <i class="bi bi-check-circle-fill me-1"></i> {{ $completedAttempts }} Selesai Mengerjakan
                        </span>
                    </div>
                </div>

                <div class="p-0">
                    @if($results->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-people fs-1 d-block mb-3 text-secondary"></i>
                            <h5 class="text-dark">Belum Ada Peserta yang Mengisi Kuis Ini</h5>
                            <p class="small text-muted mb-0">Hasil pengerjaan dan perolehan nilai peserta akan otomatis tercatat di sini setelah peserta menyelesaikan kuis.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 50px;">#</th>
                                        <th>Nama Peserta</th>
                                        <th>Nilai / Skor</th>
                                        <th>Status Kelulusan</th>
                                        <th>Status Kuis</th>
                                        <th>Waktu Pengerjaan</th>
                                        <th style="width: 130px;" class="text-end">Rincian</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($results as $index => $attempt)
                                        @php
                                            $isPassed = ($attempt->score >= $quiz->passing_score);
                                        @endphp
                                        <tr>
                                            <td>{{ $results->firstItem() + $index }}</td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="avatar-circle bg-primary bg-opacity-10 text-primary fw-bold rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; min-width: 38px;">
                                                        {{ strtoupper(substr($attempt->user->nama ?? $attempt->user->name ?? 'P', 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold text-dark">{{ $attempt->user->nama ?? $attempt->user->name ?? 'Peserta' }}</div>
                                                        <small class="text-muted d-block">{{ $attempt->user->email ?? '-' }}</small>
                                                        @if(!empty($attempt->user->nama_usaha))
                                                            <small class="text-primary"><i class="bi bi-shop me-1"></i>{{ $attempt->user->nama_usaha }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if($attempt->status === 'completed')
                                                    <div>
                                                        <span class="fs-5 fw-bold {{ $isPassed ? 'text-success' : 'text-danger' }}">
                                                            {{ $attempt->score }}%
                                                        </span>
                                                        <small class="text-muted d-block">
                                                            {{ $attempt->correct_answers ?? 0 }} dari {{ $attempt->total_questions ?? $totalQuestions }} benar
                                                        </small>
                                                    </div>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($attempt->status === 'completed')
                                                    @if($isPassed)
                                                        <span class="badge bg-success">
                                                            <i class="bi bi-check-circle-fill me-1"></i> Lulus
                                                        </span>
                                                    @else
                                                        <span class="badge bg-danger">
                                                            <i class="bi bi-x-circle-fill me-1"></i> Belum Lulus
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="badge bg-secondary">Belum Selesai</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge {{ $attempt->status === 'completed' ? 'bg-success' : ($attempt->status === 'in_progress' ? 'bg-warning' : 'bg-secondary') }}">
                                                    {{ $attempt->status_label ?? ucfirst($attempt->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <small class="d-block text-dark">
                                                    <i class="bi bi-calendar3 me-1 text-muted"></i>
                                                    {{ $attempt->completed_at ? $attempt->completed_at->format('d/m/Y H:i') : ($attempt->started_at ? $attempt->started_at->format('d/m/Y H:i') : '-') }}
                                                </small>
                                                <small class="text-muted">
                                                    <i class="bi bi-clock-history me-1"></i>
                                                    Durasi: {{ $attempt->formatted_duration ?? '-' }}
                                                </small>
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('pemateri.quiz.attempt.show', [$quiz, $attempt]) }}" class="btn btn-outline-primary btn-sm" title="Lihat rincian lembar jawaban peserta">
                                                    <i class="bi bi-eye me-1"></i> Jawaban
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="p-3 border-top">
                            {{ $results->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Otomatis buka tab hasil jika URL memiliki hash #hasil atau #tab-results
        if (window.location.hash === '#hasil' || window.location.hash === '#tab-results') {
            const resultsTabTrigger = document.querySelector('#results-tab');
            if (resultsTabTrigger) {
                const tab = new bootstrap.Tab(resultsTabTrigger);
                tab.show();
            }
        }
    });
</script>
@endpush
