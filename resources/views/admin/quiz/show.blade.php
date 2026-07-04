@extends('layouts.admin')

@section('title', 'Detail Quiz')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-eye"></i></span>
        <div>
            <p class="eyebrow">Manajemen</p>
            <h1 class="h3 mb-0">Detail Quiz</h1>
        </div>
    </div>
    <div class="heading-actions">
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('admin.quiz.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
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

            <!-- Main Card -->
            <div class="panel">
                <div class="panel-header">
                    <h5 class="section-title"><i class="bi bi-info-circle"></i> Informasi Quiz</h5>
                    <div class="d-flex gap-2 flex-wrap">
                        <span class="badge {{ $quiz->status_badge ?? 'bg-secondary' }}">
                            <i class="bi bi-circle-fill me-1" style="font-size: 6px;"></i>
                            {{ $quiz->status_label ?? ucfirst($quiz->status ?? 'Draft') }}
                        </span>
                        @if($quiz->is_available)
                        <span class="badge bg-success">
                            <i class="bi bi-check-circle me-1"></i> Tersedia
                        </span>
                        @else
                        <span class="badge bg-danger">
                            <i class="bi bi-x-circle me-1"></i> Tidak Tersedia
                        </span>
                        @endif
                        @if($quiz->is_random)
                        <span class="badge bg-info">
                            <i class="bi bi-shuffle me-1"></i> Acak
                        </span>
                        @endif
                        <span class="badge bg-secondary">
                            <i class="bi bi-list-ol me-1"></i> {{ $totalQuestions ?? 0 }} Soal
                        </span>
                    </div>
                </div>
                <div class="p-4">
                    <div class="row g-4">
                        <!-- Judul -->
                        <div class="col-12">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-text-paragraph fs-4 text-primary"></i>
                                </div>
                                <div>
                                    <label class="text-muted small fw-semibold">Judul</label>
                                    <p class="fw-semibold mb-0 fs-5">{{ $quiz->judul }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        @if($quiz->deskripsi)
                        <div class="col-12">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-file-text fs-4 text-info"></i>
                                </div>
                                <div>
                                    <label class="text-muted small fw-semibold">Deskripsi</label>
                                    <p class="mb-0">{{ $quiz->deskripsi }}</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Training & Materi -->
                        <div class="col-12 col-md-6">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-journal-bookmark fs-4 text-success"></i>
                                </div>
                                <div>
                                    <label class="text-muted small fw-semibold">Training</label>
                                    @if($quiz->training)
                                    <p class="fw-semibold mb-0">
                                        <a href="{{ route('admin.trainings.show', $quiz->training->id) }}" class="text-decoration-none">
                                            {{ $quiz->training->judul }}
                                        </a>
                                    </p>
                                    @else
                                    <p class="text-muted">-</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-book fs-4 text-primary"></i>
                                </div>
                                <div>
                                    <label class="text-muted small fw-semibold">Materi</label>
                                    @if($quiz->materi)
                                    <p class="fw-semibold mb-0">
                                        <a href="{{ route('admin.materi.show', $quiz->materi->id) }}" class="text-decoration-none">
                                            {{ $quiz->materi->judul }}
                                        </a>
                                    </p>
                                    @else
                                    <p class="text-muted">-</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Pengaturan Quiz -->
                        <div class="col-12">
                            <hr class="my-2">
                            <h6 class="fw-semibold text-muted">
                                <i class="bi bi-gear me-2"></i>Pengaturan
                            </h6>
                        </div>

                        <div class="col-12 col-md-3">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-clock fs-4 text-warning"></i>
                                </div>
                                <div>
                                    <label class="text-muted small fw-semibold">Durasi</label>
                                    <p class="fw-semibold mb-0">{{ $quiz->formatted_duration }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-3">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-star fs-4 text-warning"></i>
                                </div>
                                <div>
                                    <label class="text-muted small fw-semibold">Nilai Lulus</label>
                                    <p class="fw-semibold mb-0">{{ $quiz->passing_score }}%</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-3">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-arrow-repeat fs-4 text-info"></i>
                                </div>
                                <div>
                                    <label class="text-muted small fw-semibold">Max Percobaan</label>
                                    <p class="fw-semibold mb-0">{{ $quiz->max_attempt }}x</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-3">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-calendar fs-4 text-secondary"></i>
                                </div>
                                <div>
                                    <label class="text-muted small fw-semibold">Urutan</label>
                                    <p class="fw-semibold mb-0">{{ $quiz->order ?? 0 }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Jadwal -->
                        <div class="col-12">
                            <hr class="my-2">
                            <h6 class="fw-semibold text-muted">
                                <i class="bi bi-calendar-event me-2"></i>Jadwal
                            </h6>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-calendar-plus fs-4 text-success"></i>
                                </div>
                                <div>
                                    <label class="text-muted small fw-semibold">Mulai</label>
                                    <p class="fw-semibold mb-0">
                                        {{ $quiz->start_date ? $quiz->start_date->format('d/m/Y H:i') : 'Tidak dibatasi' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-calendar-minus fs-4 text-danger"></i>
                                </div>
                                <div>
                                    <label class="text-muted small fw-semibold">Selesai</label>
                                    <p class="fw-semibold mb-0">
                                        {{ $quiz->end_date ? $quiz->end_date->format('d/m/Y H:i') : 'Tidak dibatasi' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Statistik -->
                        <div class="col-12">
                            <hr class="my-2">
                            <h6 class="fw-semibold text-muted">
                                <i class="bi bi-bar-chart me-2"></i>Statistik
                            </h6>
                        </div>

                        <div class="col-12">
                            <div class="row g-3">
                                <div class="col-6 col-md-3">
                                    <div class="text-center p-3 border rounded bg-light">
                                        <h6 class="text-muted small">Total Soal</h6>
                                        <h3 class="mb-0">{{ $totalQuestions ?? 0 }}</h3>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="text-center p-3 border rounded bg-light">
                                        <h6 class="text-muted small">Peserta</h6>
                                        <h3 class="mb-0">{{ $totalParticipants ?? 0 }}</h3>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="text-center p-3 border rounded bg-light">
                                        <h6 class="text-muted small">Rata-rata Nilai</h6>
                                        <h3 class="mb-0">{{ number_format($averageScore ?? 0, 1) }}</h3>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="text-center p-3 border rounded bg-light">
                                        <h6 class="text-muted small">Tingkat Kelulusan</h6>
                                        <h3 class="mb-0">{{ number_format($passingRate ?? 0, 1) }}%</h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Hasil Peserta -->
                        @if(isset($results) && $results->count() > 0)
                        <div class="col-12">
                            <hr class="my-2">
                            <h6 class="fw-semibold text-muted">
                                <i class="bi bi-people me-2"></i>Hasil Peserta
                            </h6>
                            <div class="table-responsive mt-2">
                                <table class="table table-sm table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Peserta</th>
                                            <th>Nilai</th>
                                            <th>Status</th>
                                            <th>Waktu</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($results as $index => $result)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $result->user->name ?? '-' }}</td>
                                            <td>
                                                @if($result->status == 'completed')
                                                    <span class="fw-bold {{ $result->score >= $quiz->passing_score ? 'text-success' : 'text-danger' }}">
                                                        {{ $result->score }}/{{ $result->total_questions }}
                                                    </span>
                                                    <small class="text-muted d-block">{{ number_format(($result->score / max($result->total_questions, 1)) * 100, 1) }}%</small>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge {{ $result->status == 'completed' ? 'bg-success' : ($result->status == 'in_progress' ? 'bg-warning' : 'bg-danger') }}">
                                                    {{ $result->status_label }}
                                                </span>
                                            </td>
                                            <td>
                                                <small>
                                                    @if($result->completed_at)
                                                        {{ $result->completed_at->format('d/m/Y H:i') }}
                                                        <br>
                                                        <span class="text-muted">{{ $result->formatted_duration }}</span>
                                                    @elseif($result->started_at)
                                                        {{ $result->started_at->format('d/m/Y H:i') }}
                                                        <span class="text-muted">(Mulai)</span>
                                                    @else
                                                        -
                                                    @endif
                                                </small>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif

                        <!-- Meta Info -->
                        <div class="col-12">
                            <hr class="my-2">
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label class="text-muted small fw-semibold">
                                        <i class="bi bi-clock me-1"></i> Dibuat
                                    </label>
                                    <p class="fw-semibold mb-0">
                                        {{ $quiz->created_at ? $quiz->created_at->format('d/m/Y H:i') : '-' }}
                                        @if($quiz->created_at)
                                        <span class="text-muted small">
                                            ({{ $quiz->created_at->diffForHumans() }})
                                        </span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="text-muted small fw-semibold">
                                        <i class="bi bi-clock-history me-1"></i> Diperbarui
                                    </label>
                                    <p class="fw-semibold mb-0">
                                        {{ $quiz->updated_at ? $quiz->updated_at->format('d/m/Y H:i') : '-' }}
                                        @if($quiz->updated_at && $quiz->updated_at != $quiz->created_at)
                                        <span class="text-muted small">
                                            ({{ $quiz->updated_at->diffForHumans() }})
                                        </span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

            <!-- Related Data Card -->
            <div class="panel mt-3">
                <div class="panel-header">
                    <h5 class="section-title"><i class="bi bi-link-45deg"></i> Data Terkait</h5>
                </div>
                <div class="p-4">
                    <div class="row g-3">
                        <div class="col-12 col-md-4">
                            <div class="card bg-light h-100">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="bi bi-question-circle text-primary me-2"></i>
                                        Pertanyaan
                                    </h6>
                                    <p class="card-text">
                                        <span class="fw-bold">{{ $totalQuestions ?? 0 }}</span> pertanyaan
                                    </p>
                                    <a href="{{ route('admin.quiz.questions.index', $quiz->id) }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-list-ol me-1"></i> Kelola
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="card bg-light h-100">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="bi bi-people text-success me-2"></i>
                                        Peserta
                                    </h6>
                                    <p class="card-text">
                                        <span class="fw-bold">{{ $totalParticipants ?? 0 }}</span> peserta telah mengerjakan
                                    </p>
                                    <a href="{{ route('admin.quiz.attempt.index') }}?quiz_id={{ $quiz->id }}" class="btn btn-sm btn-success">
                                        <i class="bi bi-clock-history me-1"></i> Lihat
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="card bg-light h-100">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="bi bi-journal-bookmark text-info me-2"></i>
                                        Materi
                                    </h6>
                                    <p class="card-text">
                                        @if($quiz->materi)
                                            <span class="fw-bold">{{ $quiz->materi->judul }}</span>
                                        @else
                                            <span class="text-muted">Tidak ada materi</span>
                                        @endif
                                    </p>
                                    @if($quiz->materi)
                                    <a href="{{ route('admin.materi.show', $quiz->materi->id) }}" class="btn btn-sm btn-info">
                                        <i class="bi bi-book me-1"></i> Lihat Materi
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
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
                @if(($totalQuestions ?? 0) > 0)
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Quiz ini memiliki <strong>{{ $totalQuestions }}</strong> pertanyaan. 
                    Menghapus quiz akan menghapus semua pertanyaan terkait.
                </div>
                @endif
                @if(($totalParticipants ?? 0) > 0)
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Quiz ini sudah dikerjakan oleh <strong>{{ $totalParticipants }}</strong> peserta. 
                    Menghapus quiz akan menghapus semua data pengerjaan.
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

@push('styles')
<style>
    .panel-header .badge {
        font-size: 0.8rem;
        padding: 0.4rem 0.8rem;
    }
    .table td {
        vertical-align: middle;
    }
    .bg-light {
        background-color: #f8f9fa !important;
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
});
</script>
@endpush
@endsection