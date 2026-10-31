@extends('layouts.pemateri')

@section('title', 'Lembar Jawaban: ' . ($attempt->user->nama ?? $attempt->user->name ?? 'Peserta'))

@section('header')
<div class="page-heading d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-person-check"></i></span>
        <div>
            <p class="eyebrow">Rincian Hasil Kuis Peserta</p>
            <h1 class="h3 mb-0">{{ $attempt->user->nama ?? $attempt->user->name ?? 'Peserta' }}</h1>
            <p class="text-muted mb-0">Kuis: {{ $quiz->judul }} &bull; Pelatihan: {{ $quiz->training->judul ?? '-' }}</p>
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('pemateri.quiz.show', $quiz) }}#hasil" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Hasil Kuis
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">

    <!-- RINGKASAN PENGERJAAN KARTU -->
    <div class="row g-4 mb-4">
        <!-- PROFIL PESERTA -->
        <div class="col-12 col-lg-7">
            <div class="panel border-0 shadow-sm h-100">
                <div class="panel-header">
                    <h5 class="section-title"><i class="bi bi-person text-primary"></i> Data Diri Peserta</h5>
                </div>
                <div class="p-4">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="avatar-circle bg-primary bg-opacity-10 text-primary fw-bold rounded-circle d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; font-size: 1.3rem;">
                            {{ strtoupper(substr($attempt->user->nama ?? $attempt->user->name ?? 'P', 0, 1)) }}
                        </div>
                        <div>
                            <h5 class="fw-bold text-dark mb-0">{{ $attempt->user->nama ?? $attempt->user->name ?? 'Peserta' }}</h5>
                            <span class="text-muted small">{{ $attempt->user->email ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="row g-3 small">
                        <div class="col-6">
                            <span class="text-muted d-block">Nomor Telepon:</span>
                            <span class="fw-semibold text-dark">{{ $attempt->user->no_telepon ?? '-' }}</span>
                        </div>
                        <div class="col-6">
                            <span class="text-muted d-block">Usaha / UMK:</span>
                            <span class="fw-semibold text-dark">{{ $attempt->user->nama_usaha ?? '-' }}</span>
                        </div>
                        <div class="col-6">
                            <span class="text-muted d-block">Waktu Mulai:</span>
                            <span class="fw-semibold text-dark">{{ $attempt->started_at ? $attempt->started_at->format('d/m/Y H:i:s') : '-' }}</span>
                        </div>
                        <div class="col-6">
                            <span class="text-muted d-block">Waktu Selesai:</span>
                            <span class="fw-semibold text-dark">{{ $attempt->completed_at ? $attempt->completed_at->format('d/m/Y H:i:s') : '-' }}</span>
                        </div>
                        <div class="col-6">
                            <span class="text-muted d-block">Durasi Pengerjaan:</span>
                            <span class="fw-semibold text-primary"><i class="bi bi-clock-history me-1"></i>{{ $attempt->formatted_duration ?? '-' }}</span>
                        </div>
                        <div class="col-6">
                            <span class="text-muted d-block">Status Kuis:</span>
                            <span class="badge {{ $attempt->status === 'completed' ? 'bg-success' : 'bg-warning' }}">{{ $attempt->status_label }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SKOR AKHIR & KELULUSAN -->
        <div class="col-12 col-lg-5">
            <div class="panel border-0 shadow-sm h-100 text-center">
                <div class="panel-header">
                    <h5 class="section-title"><i class="bi bi-award text-warning"></i> Perolehan Nilai</h5>
                </div>
                <div class="p-4 d-flex flex-column align-items-center justify-content-center">
                    @php
                        $isPassed = ($attempt->score >= $quiz->passing_score);
                    @endphp
                    <div class="rounded-circle d-flex flex-column align-items-center justify-content-center mb-3 border border-4 {{ $isPassed ? 'border-success bg-success bg-opacity-10' : 'border-danger bg-danger bg-opacity-10' }}" style="width: 120px; height: 120px;">
                        <span class="fs-1 fw-bold {{ $isPassed ? 'text-success' : 'text-danger' }}">{{ $attempt->score }}</span>
                        <small class="text-muted" style="margin-top: -6px;">dari 100</small>
                    </div>

                    @if($isPassed)
                        <span class="badge bg-success fs-6 px-3 py-1.5 mb-2">
                            <i class="bi bi-check-circle-fill me-1"></i> LULUS EVALUASI
                        </span>
                        <p class="small text-muted mb-0">Peserta berhasil melampaui batas nilai kelulusan minimal ({{ $quiz->passing_score }}%).</p>
                    @else
                        <span class="badge bg-danger fs-6 px-3 py-1.5 mb-2">
                            <i class="bi bi-x-circle-fill me-1"></i> BELUM LULUS
                        </span>
                        <p class="small text-muted mb-0">Nilai peserta masih di bawah batas kelulusan minimal ({{ $quiz->passing_score }}%).</p>
                    @endif

                    <div class="d-flex justify-content-around w-100 mt-4 pt-3 border-top">
                        <div>
                            <span class="text-muted small d-block">Jawaban Benar</span>
                            <span class="fw-bold text-success fs-5">{{ $attempt->correct_answers ?? 0 }}</span>
                        </div>
                        <div>
                            <span class="text-muted small d-block">Jawaban Salah</span>
                            <span class="fw-bold text-danger fs-5">{{ ($attempt->total_questions ?? count($questions)) - ($attempt->correct_answers ?? 0) }}</span>
                        </div>
                        <div>
                            <span class="text-muted small d-block">Total Soal</span>
                            <span class="fw-bold text-dark fs-5">{{ $attempt->total_questions ?? count($questions) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- RINCIAN BUTIR JAWABAN PESERTA -->
    <div class="panel border-0 shadow-sm">
        <div class="panel-header d-flex justify-content-between align-items-center">
            <h5 class="section-title"><i class="bi bi-card-checklist text-primary"></i> Rincian Lembar Jawaban Peserta</h5>
            <span class="badge bg-light text-dark border">{{ count($questions) }} Soal Evaluasi</span>
        </div>
        <div class="p-4">
            @if(empty($questions) || count($questions) === 0)
                <p class="text-muted text-center py-4">Tidak ada data butir soal.</p>
            @else
                <div class="d-flex flex-column gap-3">
                    @foreach($questions as $index => $q)
                        @php
                            $userAns = $userAnswers[$q->id] ?? null;
                            $correctAns = $q->correct_answer ?? $q->jawaban_benar ?? null;
                            $isCorrect = false;

                            if ($q->type === 'multiple_choice' || $q->type === 'true_false') {
                                $isCorrect = (strtoupper(trim((string)$userAns)) === strtoupper(trim((string)$correctAns)));
                            }
                        @endphp
                        <div class="card border rounded-3 p-3 {{ $userAns ? ($isCorrect ? 'border-success border-opacity-50' : 'border-danger border-opacity-50') : 'border-secondary border-opacity-25' }}">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge {{ $isCorrect ? 'bg-success' : ($userAns ? 'bg-danger' : 'bg-secondary') }} rounded-circle p-2" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center;">
                                        {{ $index + 1 }}
                                    </span>
                                    <span class="badge bg-light text-dark border small text-uppercase">
                                        {{ str_replace('_', ' ', $q->type) }}
                                    </span>
                                    <span class="badge bg-warning bg-opacity-25 text-dark small">
                                        {{ $q->points ?? $q->score ?? 1 }} Poin
                                    </span>
                                </div>
                                <div>
                                    @if($q->type === 'multiple_choice' || $q->type === 'true_false')
                                        @if($isCorrect)
                                            <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Benar</span>
                                        @elseif($userAns)
                                            <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i> Salah</span>
                                        @else
                                            <span class="badge bg-secondary"><i class="bi bi-dash-circle me-1"></i> Tidak Dijawab</span>
                                        @endif
                                    @endif
                                </div>
                            </div>

                            <div class="fw-semibold text-dark mb-3 ps-1">
                                {!! nl2br(e($q->question ?? $q->pertanyaan)) !!}
                            </div>

                            <!-- OPSI & JAWABAN TERPILIH -->
                            @if($q->type === 'multiple_choice' && !empty($q->options))
                                <div class="row g-2 ps-1">
                                    @php
                                        $options = is_array($q->options) ? $q->options : json_decode($q->options, true) ?? [];
                                        $labels = ['A', 'B', 'C', 'D', 'E', 'F'];
                                    @endphp
                                    @foreach($options as $optIdx => $optText)
                                        @php
                                            $optKey = $labels[$optIdx] ?? ($optIdx + 1);
                                            $isSelected = (strtoupper(trim((string)$userAns)) === $optKey);
                                            $isAnswerKey = (strtoupper(trim((string)$correctAns)) === $optKey);
                                        @endphp
                                        <div class="col-12 col-md-6">
                                            <div class="p-2.5 rounded border 
                                                {{ $isAnswerKey ? 'bg-success bg-opacity-10 border-success text-success fw-bold' : ($isSelected ? 'bg-danger bg-opacity-10 border-danger text-danger' : 'bg-light text-muted') }}" style="font-size: 0.88rem;">
                                                <span class="badge {{ $isAnswerKey ? 'bg-success' : ($isSelected ? 'bg-danger' : 'bg-secondary') }} me-1.5">{{ $optKey }}</span>
                                                {{ $optText }}
                                                @if($isSelected)
                                                    <span class="badge bg-primary ms-1 small">Jawaban Peserta</span>
                                                @endif
                                                @if($isAnswerKey)
                                                    <i class="bi bi-check-circle-fill text-success ms-1" title="Kunci Jawaban"></i>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @elseif($q->type === 'true_false')
                                <div class="ps-1 small">
                                    <span class="text-muted">Jawaban Peserta:</span>
                                    <span class="badge {{ $isCorrect ? 'bg-success' : 'bg-danger' }} ms-1">{{ $userAns ?? 'Tidak dijawab' }}</span>
                                    <span class="text-muted ms-3">Kunci Jawaban:</span>
                                    <span class="badge bg-success ms-1">{{ $correctAns ?? 'Benar' }}</span>
                                </div>
                            @elseif($q->type === 'essay')
                                <div class="ps-1 small">
                                    <div class="mb-2">
                                        <span class="text-muted fw-semibold">Jawaban Peserta:</span>
                                        <div class="p-2.5 bg-light rounded mt-1 border text-dark">{{ $userAns ?? 'Tidak dijawab' }}</div>
                                    </div>
                                    <div>
                                        <span class="text-muted fw-semibold">Pedoman / Kunci Jawaban:</span>
                                        <div class="p-2.5 bg-light rounded mt-1 border text-muted">{{ $q->essay_answer_key ?? '-' }}</div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

</div>
@endsection
