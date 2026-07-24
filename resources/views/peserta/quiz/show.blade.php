@extends('layouts.peserta')

@section('title', 'Detail Quiz')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-question-circle"></i></span>
        <div>
            <p class="eyebrow">Quiz</p>
            <h1 class="h3 mb-0">Detail Quiz</h1>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="row">
        <div class="col-12 col-lg-10 mx-auto">
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

            @if(isset($hasAbsensi) && !$hasAbsensi)
            <div class="alert alert-warning d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                    <div>
                        <strong>Perhatian!</strong> Anda harus melakukan absensi kehadiran (Hadir) pada pelatihan ini terlebih dahulu sebelum dapat memulai atau mengulang quiz.
                    </div>
                </div>
                <form action="{{ route('peserta.absen.store') }}" method="POST" class="m-0">
                    @csrf
                    <input type="hidden" name="training_id" value="{{ $quiz->training_id }}">
                    <input type="hidden" name="tanggal" value="{{ date('Y-m-d') }}">
                    <input type="hidden" name="status" value="hadir">
                    <button type="submit" class="btn btn-success text-nowrap shadow-sm" onclick="return confirm('Apakah Anda yakin ingin merekam kehadiran untuk pelatihan ini sekarang?')">
                        <i class="bi bi-check2-circle me-1"></i> Absen Sekarang
                    </button>
                </form>
            </div>
            @endif

            <!-- Main Card -->
            <div class="panel">
                <div class="p-4">
                    <div class="row g-4">
                        <!-- Title & Status -->
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                <div>
                                    <h3 class="fw-bold mb-2">{{ $quiz->judul }}</h3>
                                    <div class="d-flex flex-wrap gap-2">
                                        <span class="badge 
                                            @if($quiz->status == 'published') 
                                                badge-published
                                            @elseif($quiz->status == 'draft') 
                                                badge-draft
                                            @else 
                                                badge-archived
                                            @endif
                                        ">
                                            {{ $quiz->status_label ?? ucfirst($quiz->status ?? 'Draft') }}
                                        </span>
                                        @if($quiz->materi)
                                        <span class="badge bg-primary">
                                            <i class="bi bi-book me-1"></i> {{ $quiz->materi->judul }}
                                        </span>
                                        @endif
                                        <span class="badge bg-secondary">
                                            <i class="bi bi-list-ol me-1"></i> {{ $totalQuestions ?? 0 }} soal
                                        </span>
                                    </div>
                                </div>
                                <div class="d-flex gap-2 flex-wrap">
                                    @if(($remainingAttempts ?? 0) > 0 && !isset($userAttempt))
                                        <form action="{{ route('peserta.quiz.start', $quiz->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success" {{ (isset($hasAbsensi) && !$hasAbsensi) ? 'disabled' : '' }} onclick="return confirm('Yakin ingin memulai quiz ini?')">
                                                <i class="bi bi-play-circle me-2"></i> Mulai Quiz
                                            </button>
                                        </form>
                                    @elseif(isset($userAttempt))
                                        <a href="{{ route('peserta.quiz.result', ['quiz' => $quiz->id, 'attempt' => $userAttempt->id]) }}" 
                                           class="btn btn-info text-white">
                                            <i class="bi bi-eye me-2"></i> Lihat Hasil
                                        </a>
                                    @endif
                                    @if(($remainingAttempts ?? 0) > 0 && isset($userAttempt) && $userAttempt->status == 'completed')
                                        <form action="{{ route('peserta.quiz.start', $quiz->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-warning" {{ (isset($hasAbsensi) && !$hasAbsensi) ? 'disabled' : '' }} onclick="return confirm('Yakin ingin mencoba quiz ini lagi?')">
                                                <i class="bi bi-arrow-repeat me-2"></i> Coba Lagi
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Quiz Info -->
                        <div class="col-12">
                            <div class="row g-3">
                                <div class="col-6 col-md-3">
                                    <div class="text-center p-3 border rounded bg-light">
                                        <h6 class="text-muted small">Total Soal</h6>
                                        <h4 class="mb-0">{{ $totalQuestions ?? 0 }}</h4>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="text-center p-3 border rounded bg-light">
                                        <h6 class="text-muted small">Durasi</h6>
                                        <h4 class="mb-0">{{ $quiz->durasi ?? 'Tidak terbatas' }} <small class="text-muted">menit</small></h4>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="text-center p-3 border rounded bg-light">
                                        <h6 class="text-muted small">Nilai Lulus</h6>
                                        <h4 class="mb-0">{{ $quiz->passing_score ?? 70 }}%</h4>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="text-center p-3 border rounded bg-light">
                                        <h6 class="text-muted small">Sisa Percobaan</h6>
                                        <h4 class="mb-0">{{ $remainingAttempts ?? 0 }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        @if($quiz->deskripsi)
                        <div class="col-12">
                            <h6 class="fw-bold"><i class="bi bi-file-text me-2"></i>Deskripsi</h6>
                            <p class="text-muted">{{ $quiz->deskripsi }}</p>
                        </div>
                        @endif

                        <!-- Rules -->
                        <div class="col-12">
                            <hr class="my-2">
                            <h6 class="fw-bold"><i class="bi bi-info-circle me-2"></i>Aturan Quiz</h6>
                            <ul class="text-muted small">
                                <li>Jawab semua pertanyaan dengan teliti.</li>
                                <li>Setiap pertanyaan memiliki nilai yang berbeda.</li>
                                <li>Nilai minimal untuk lulus adalah <strong>{{ $quiz->passing_score ?? 70 }}%</strong>.</li>
                                @if($quiz->durasi)
                                <li>Waktu pengerjaan: <strong>{{ $quiz->durasi }} menit</strong>.</li>
                                @endif
                                @if($quiz->max_attempt)
                                <li>Maksimal percobaan: <strong>{{ $quiz->max_attempt }}x</strong>.</li>
                                @endif
                                @if($quiz->is_random)
                                <li>Pertanyaan akan diacak setiap kali mengerjakan.</li>
                                @endif
                            </ul>
                        </div>

                        <!-- User Attempt History -->
                        @php
                            $attempts = $quiz->attempts()->where('user_id', auth()->id())->orderBy('created_at', 'desc')->get();
                        @endphp
                        @if($attempts->count() > 0)
                        <div class="col-12">
                            <hr class="my-2">
                            <h6 class="fw-bold"><i class="bi bi-clock-history me-2"></i>Riwayat Pengerjaan</h6>
                            <div class="table-responsive mt-2">
                                <table class="table table-sm table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Tanggal</th>
                                            <th>Nilai</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($attempts as $index => $attempt)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <small>
                                                    {{ $attempt->created_at ? $attempt->created_at->format('d/m/Y H:i') : '-' }}
                                                </small>
                                            </td>
                                            <td>
                                                @if($attempt->status == 'completed')
                                                    <span class="fw-bold {{ $attempt->score >= ($quiz->passing_score ?? 70) ? 'text-success' : 'text-danger' }}">
                                                        {{ $attempt->score }}/{{ $attempt->total_questions }}
                                                    </span>
                                                    <small class="text-muted d-block">{{ number_format($attempt->percentage ?? 0, 1) }}%</small>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge {{ $attempt->status == 'completed' ? 'bg-success' : ($attempt->status == 'in_progress' ? 'bg-warning' : 'bg-danger') }}">
                                                    {{ $attempt->status_label }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($attempt->status == 'completed')
                                                    <a href="{{ route('peserta.quiz.result', ['quiz' => $quiz->id, 'attempt' => $attempt->id]) }}" 
                                                       class="btn btn-sm btn-info">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="col-12 mt-2">
                            <hr class="my-2">
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="{{ route('peserta.quiz.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left me-1"></i> Kembali
                                </a>
                                @if(($remainingAttempts ?? 0) > 0 && !isset($userAttempt))
                                    <form action="{{ route('peserta.quiz.start', $quiz->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success" {{ (isset($hasAbsensi) && !$hasAbsensi) ? 'disabled' : '' }} onclick="return confirm('Yakin ingin memulai quiz ini?')">
                                            <i class="bi bi-play-circle me-1"></i> Mulai Quiz
                                        </button>
                                    </form>
                                @elseif(isset($userAttempt))
                                    <a href="{{ route('peserta.quiz.result', ['quiz' => $quiz->id, 'attempt' => $userAttempt->id]) }}" 
                                       class="btn btn-info text-white">
                                        <i class="bi bi-eye me-1"></i> Lihat Hasil
                                    </a>
                                @endif
                                @if(($remainingAttempts ?? 0) > 0 && isset($userAttempt) && $userAttempt->status == 'completed')
                                    <form action="{{ route('peserta.quiz.start', $quiz->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-warning" {{ (isset($hasAbsensi) && !$hasAbsensi) ? 'disabled' : '' }} onclick="return confirm('Yakin ingin mencoba quiz ini lagi?')">
                                            <i class="bi bi-arrow-repeat me-1"></i> Coba Lagi
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .badge-published { background: #d1e7dd; color: #0a7344; }
    .badge-draft { background: #e9ecef; color: #495057; }
    .badge-archived { background: #f8d7da; color: #842029; }
    
    .table td {
        vertical-align: middle;
    }
    
    .panel .btn {
        transition: all 0.2s ease;
    }
    .panel .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto close alerts after 5 seconds
    setTimeout(function() {
        document.querySelectorAll('.alert').forEach(function(alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);

    // Confirm start quiz
    document.querySelectorAll('a[href*="start"]').forEach(function(link) {
        link.addEventListener('click', function(e) {
            if (!confirm('Yakin ingin memulai quiz ini?')) {
                e.preventDefault();
            }
        });
    });
});
</script>
@endpush
@endsection