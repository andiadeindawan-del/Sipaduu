@extends('layouts.peserta')

@section('title', 'Hasil Quiz')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-clipboard-check"></i></span>
        <div>
            <p class="eyebrow">Quiz</p>
            <h1 class="h3 mb-0">Hasil Quiz</h1>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="row">
        <div class="col-12 col-lg-8 mx-auto">
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

            <!-- Result Card -->
            <div class="result-card">
                <!-- Header -->
                <div class="result-header {{ ($attempt->score ?? 0) >= ($quiz->passing_score ?? 70) ? 'result-passed' : 'result-failed' }}">
                    <div class="result-icon">
                        @if(($attempt->score ?? 0) >= ($quiz->passing_score ?? 70))
                            <i class="bi bi-trophy-fill"></i>
                        @else
                            <i class="bi bi-emoji-frown-fill"></i>
                        @endif
                    </div>
                    <div class="result-title">
                        <h4>{{ $quiz->judul }}</h4>
                        <p class="result-status">
                            @if(($attempt->score ?? 0) >= ($quiz->passing_score ?? 70))
                                <span class="badge badge-success">✅ Lulus</span>
                            @else
                                <span class="badge badge-danger">❌ Tidak Lulus</span>
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Body -->
                <div class="result-body">
                    <!-- Score -->
                    <div class="result-score">
                        <div class="score-circle {{ ($attempt->score ?? 0) >= ($quiz->passing_score ?? 70) ? 'score-passed' : 'score-failed' }}">
                            <span class="score-number">{{ $attempt->score ?? 0 }}</span>
                            <span class="score-label">Skor</span>
                        </div>
                        <div class="score-details">
                            <div class="detail-item">
                                <span class="detail-label">Total Soal</span>
                                <span class="detail-value">{{ $totalQuestions ?? 0 }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Jawaban Benar</span>
                                <span class="detail-value text-success">{{ $correctAnswers ?? 0 }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Jawaban Salah</span>
                                <span class="detail-value text-danger">{{ $wrongAnswers ?? 0 }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Nilai Lulus</span>
                                <span class="detail-value">{{ $quiz->passing_score ?? 70 }}%</span>
                            </div>
                            @if(isset($duration))
                            <div class="detail-item">
                                <span class="detail-label">Durasi</span>
                                <span class="detail-value">{{ $duration }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Status Message -->
                    <div class="result-message">
                        @if(($attempt->score ?? 0) >= ($quiz->passing_score ?? 70))
                            <div class="message-passed">
                                <i class="bi bi-check-circle-fill"></i>
                                <div>
                                    <h6 class="mb-0">Selamat! Anda Lulus</h6>
                                    <p class="mb-0 small">Anda berhasil mencapai nilai kelulusan yang ditentukan.</p>
                                </div>
                            </div>
                        @else
                            <div class="message-failed">
                                <i class="bi bi-x-circle-fill"></i>
                                <div>
                                    <h6 class="mb-0">Belum Mencapai Nilai Lulus</h6>
                                    <p class="mb-0 small">Nilai Anda {{ $attempt->score ?? 0 }}% masih di bawah nilai lulus {{ $quiz->passing_score ?? 70 }}%. Tetap semangat dan coba lagi!</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="result-actions">
                        <a href="{{ route('peserta.quiz.show', $quiz->id) }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                        @if(($attempt->score ?? 0) < ($quiz->passing_score ?? 70) && ($remainingAttempts ?? 0) > 0)
                            <a href="{{ route('peserta.quiz.start', $quiz->id) }}" class="btn btn-warning">
                                <i class="bi bi-arrow-repeat me-1"></i> Coba Lagi ({{ $remainingAttempts }}x tersisa)
                            </a>
                        @endif
                        @if(($attempt->score ?? 0) >= ($quiz->passing_score ?? 70))
                            <a href="{{ route('peserta.sertifikat.index') }}" class="btn btn-success">
                                <i class="bi bi-award me-1"></i> Lihat Sertifikat
                            </a>
                        @endif
                        <button class="btn btn-outline-primary" onclick="window.print()">
                            <i class="bi bi-printer me-1"></i> Cetak
                        </button>
                    </div>
                </div>
            </div>

            <!-- Review Answers (Opsional) -->
            @if(isset($answers) && !empty($answers) && count($answers) > 0)
            <div class="review-card mt-4">
                <div class="review-header">
                    <h6 class="mb-0"><i class="bi bi-journal-text me-2"></i>Review Jawaban</h6>
                    <span class="badge bg-secondary">{{ count($answers) }} Soal</span>
                </div>
                <div class="review-body">
                    @foreach($answers as $index => $answer)
                    <div class="review-item">
                        <div class="review-number">{{ $index + 1 }}</div>
                        <div class="review-content">
                            <p class="review-question">{{ $answer['question'] ?? $answer->question->question ?? 'Soal' }}</p>
                            <div class="review-answer">
                                <span class="review-label">Jawaban Anda:</span>
                                <span class="review-value {{ isset($answer['is_correct']) && $answer['is_correct'] ? 'text-success' : 'text-danger' }}">
                                    {{ $answer['user_answer'] ?? $answer->user_answer ?? 'Tidak dijawab' }}
                                </span>
                                @if(isset($answer['is_correct']) && $answer['is_correct'])
                                    <i class="bi bi-check-circle-fill text-success"></i>
                                @else
                                    <i class="bi bi-x-circle-fill text-danger"></i>
                                    @if(isset($answer['correct_answer']))
                                        <span class="review-correct">Jawaban Benar: {{ $answer['correct_answer'] }}</span>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
    /* ============================================================
       RESULT CARD
    ============================================================ */
    .result-card {
        background: #fff;
        border-radius: 1rem;
        box-shadow: 0 1px 4px rgba(0,0,0,.06);
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.04);
    }

    .result-header {
        padding: 1.25rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        border-bottom: 1px solid #f0f0f0;
    }
    .result-passed {
        background: linear-gradient(135deg, #d4edda, #c3e6cb);
    }
    .result-failed {
        background: linear-gradient(135deg, #f8d7da, #f5c6cb);
    }

    .result-icon {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        background: #fff;
        flex-shrink: 0;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }
    .result-passed .result-icon {
        color: #28a745;
    }
    .result-failed .result-icon {
        color: #dc3545;
    }

    .result-title h4 {
        font-weight: 700;
        color: #1a2236;
        margin-bottom: 0.1rem;
    }
    .result-status .badge {
        font-weight: 500;
        padding: 0.3rem 0.8rem;
        font-size: 0.75rem;
        border-radius: 6px;
    }
    .badge-success {
        background: #d4edda;
        color: #155724;
    }
    .badge-danger {
        background: #f8d7da;
        color: #721c24;
    }

    .result-body {
        padding: 1.5rem;
    }

    /* ============================================================
       SCORE
    ============================================================ */
    .result-score {
        display: flex;
        align-items: center;
        gap: 2rem;
        flex-wrap: wrap;
        margin-bottom: 1.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid #f0f0f0;
    }

    .score-circle {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        position: relative;
    }
    .score-passed {
        background: linear-gradient(135deg, #d4edda, #b7e4c7);
        border: 4px solid #28a745;
    }
    .score-failed {
        background: linear-gradient(135deg, #f8d7da, #f5c6cb);
        border: 4px solid #dc3545;
    }

    .score-number {
        font-size: 2.5rem;
        font-weight: 800;
        color: #1a2236;
        line-height: 1;
    }
    .score-label {
        font-size: 0.7rem;
        color: #6c757d;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .score-details {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.5rem 1.5rem;
        flex: 1;
    }
    .detail-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.3rem 0;
        border-bottom: 1px dashed #f0f0f0;
    }
    .detail-item:last-child {
        border-bottom: none;
    }
    .detail-label {
        font-size: 0.8rem;
        color: #6c757d;
    }
    .detail-value {
        font-weight: 600;
        font-size: 0.9rem;
        color: #1a2236;
    }

    /* ============================================================
       MESSAGE
    ============================================================ */
    .result-message {
        margin-bottom: 1.5rem;
    }
    .message-passed,
    .message-failed {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        border-radius: 8px;
    }
    .message-passed {
        background: #d4edda;
        color: #155724;
    }
    .message-passed i {
        color: #28a745;
        font-size: 1.3rem;
    }
    .message-failed {
        background: #f8d7da;
        color: #721c24;
    }
    .message-failed i {
        color: #dc3545;
        font-size: 1.3rem;
    }

    /* ============================================================
       ACTIONS
    ============================================================ */
    .result-actions {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
        padding-top: 1.25rem;
        border-top: 1px solid #f0f0f0;
    }
    .result-actions .btn {
        border-radius: 8px;
        padding: 0.5rem 1.25rem;
        font-weight: 500;
    }

    /* ============================================================
       REVIEW
    ============================================================ */
    .review-card {
        background: #fff;
        border-radius: 1rem;
        box-shadow: 0 1px 4px rgba(0,0,0,.06);
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.04);
    }
    .review-header {
        padding: 0.75rem 1.25rem;
        background: #f8fafc;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .review-body {
        padding: 0.5rem 1.25rem;
        max-height: 400px;
        overflow-y: auto;
    }
    .review-item {
        display: flex;
        gap: 1rem;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f0f0f0;
    }
    .review-item:last-child {
        border-bottom: none;
    }
    .review-number {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: #f0f4f8;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.8rem;
        color: #495057;
        flex-shrink: 0;
    }
    .review-content {
        flex: 1;
    }
    .review-question {
        font-weight: 500;
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
    }
    .review-answer {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
        font-size: 0.85rem;
    }
    .review-label {
        color: #6c757d;
    }
    .review-value {
        font-weight: 600;
    }
    .review-correct {
        font-size: 0.8rem;
        color: #28a745;
        background: #d4edda;
        padding: 0.1rem 0.5rem;
        border-radius: 4px;
    }

    /* ============================================================
       RESPONSIVE
    ============================================================ */
    @media (max-width: 768px) {
        .result-score {
            flex-direction: column;
            align-items: center;
            gap: 1rem;
        }
        .score-details {
            grid-template-columns: 1fr;
            width: 100%;
        }
        .result-actions {
            flex-direction: column;
        }
        .result-actions .btn {
            width: 100%;
            justify-content: center;
        }
        .result-header {
            flex-direction: column;
            text-align: center;
        }
        .review-item {
            flex-direction: column;
            gap: 0.5rem;
        }
        .review-number {
            width: 24px;
            height: 24px;
            font-size: 0.7rem;
        }
    }
</style>
@endpush
@endsection