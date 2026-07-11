@extends('layouts.peserta')

@section('title', 'Dashboard Peserta')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-grid-1x2"></i></span>
        <div>
            <p class="eyebrow">Overview</p>
            <h1 class="h3 mb-1">Dashboard Peserta</h1>
        </div>
    </div>
    <div class="heading-actions">
        <div class="d-flex gap-2">
            <span class="badge bg-success" style="font-size: 0.85rem; padding: 0.5rem 1rem; border-radius: 50px;">
                <i class="bi bi-calendar-check me-1"></i>
                {{ now()->translatedFormat('d F Y') }}
            </span>
            <button class="btn btn-outline-secondary btn-sm" type="button" onclick="window.print()">
                <i class="bi bi-printer"></i> Cetak
            </button>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <!-- Welcome Banner -->
    <div class="panel mb-4 border-0" style="background: linear-gradient(135deg, #0d1b15 0%, #1a3a2a 50%, #2a5a3a 100%); border-radius: 1rem; overflow: hidden;">
        <div class="p-4 p-md-5">
            <div class="row align-items-center">
                <div class="col-12 col-md-8">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge bg-success bg-opacity-25 text-white px-3 py-1 rounded-pill">
                            <i class="bi bi-check-circle me-1"></i> Aktif
                        </span>
                        <span class="badge bg-warning bg-opacity-25 text-white px-3 py-1 rounded-pill">
                            <i class="bi bi-mortarboard me-1"></i> Peserta
                        </span>
                    </div>
                    <h2 class="text-white mb-2 display-6 fw-bold">Selamat Datang, {{ auth()->user()->nama ?? auth()->user()->name }}! 👋</h2>
                    <p class="text-white-50 mb-0" style="font-size: 1.05rem;">
                        Teruslah belajar dan tingkatkan kemampuan Anda melalui pelatihan yang tersedia.
                        <br>
                        <i class="bi bi-check-circle text-success me-1"></i>
                        <span class="text-white-50">Anda telah menyelesaikan <strong class="text-white">{{ $totalCertificates ?? 0 }}</strong> sertifikat</span>
                        <span class="text-white-50 mx-2">•</span>
                        <i class="bi bi-journal-bookmark text-primary me-1"></i>
                        <span class="text-white-50">Mengikuti <strong class="text-white">{{ $totalTrainings ?? 0 }}</strong> pelatihan</span>
                    </p>
                </div>
                <div class="col-12 col-md-4 text-center text-md-end mt-3 mt-md-0">
                    @if(auth()->user()->foto)
                        <img src="{{ asset('storage/' . auth()->user()->foto) }}" 
                             alt="Foto" class="rounded-circle border border-3 border-light shadow-lg"
                             style="width: 90px; height: 90px; object-fit: cover;">
                    @else
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center border border-3 border-light shadow-lg"
                             style="width: 90px; height: 90px; background: rgba(255,255,255,0.15); color: #fff; font-size: 36px; font-weight: 700;">
                            {{ strtoupper(substr(auth()->user()->nama ?? auth()->user()->name ?? 'U', 0, 2)) }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <section class="row g-3 mb-4" aria-label="Dashboard metrics">
        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card metric-primary">
                <div class="metric-top">
                    <span class="metric-label">Total Pelatihan</span>
                    <span class="metric-icon"><i class="bi bi-journal-bookmark"></i></span>
                </div>
                <div class="metric-value">{{ $totalTrainings ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-primary">Semua</span>
                    <span>pelatihan</span>
                </div>
            </article>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card metric-success">
                <div class="metric-top">
                    <span class="metric-label">Sertifikat Diperoleh</span>
                    <span class="metric-icon"><i class="bi bi-award"></i></span>
                </div>
                <div class="metric-value">{{ $totalCertificates ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-success">Selesai</span>
                    <span>sertifikat</span>
                </div>
            </article>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card metric-warning">
                <div class="metric-top">
                    <span class="metric-label">Quiz Dikerjakan</span>
                    <span class="metric-icon"><i class="bi bi-question-circle"></i></span>
                </div>
                <div class="metric-value">{{ $totalQuizAttempts ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-warning">Total</span>
                    <span>quiz</span>
                </div>
            </article>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card metric-info">
                <div class="metric-top">
                    <span class="metric-label">Rata-rata Nilai Quiz</span>
                    <span class="metric-icon"><i class="bi bi-star"></i></span>
                </div>
                <div class="metric-value">{{ number_format($averageQuizScore ?? 0, 1) }}</div>
                <div class="metric-meta">
                    <span class="text-info">Keseluruhan</span>
                    <span>nilai</span>
                </div>
            </article>
        </div>
    </section>

    <!-- Progress Overview -->
    @if(isset($totalTrainings) && $totalTrainings > 0)
    <div class="panel mb-4 border-0 shadow-sm">
        <div class="panel-header bg-light bg-opacity-50">
            <h5 class="section-title"><i class="bi bi-graph-up-arrow text-success"></i> Progress Keseluruhan</h5>
        </div>
        <div class="p-4">
            <div class="row g-4 align-items-center">
                @php
                    $completedPercent = $totalTrainings > 0 ? round(($totalCertificates / $totalTrainings) * 100) : 0;
                    $inProgressPercent = $totalTrainings > 0 ? round((($totalTrainings - $totalCertificates) / $totalTrainings) * 100) : 0;
                @endphp
                <div class="col-12 col-md-8">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small fw-semibold">Progress Belajar</span>
                        <span class="fw-bold text-success">{{ $completedPercent }}%</span>
                    </div>
                    <div class="progress" style="height: 14px; background-color: #e9ecef; border-radius: 50px;">
                        <div class="progress-bar bg-success" style="width: {{ $completedPercent }}%; border-radius: 50px; transition: width 1.5s ease;"></div>
                    </div>
                    <div class="d-flex justify-content-between mt-2">
                        <span class="text-muted small">
                            <i class="bi bi-check-circle text-success me-1"></i>
                            Selesai: <strong>{{ $totalCertificates ?? 0 }}</strong> pelatihan
                        </span>
                        <span class="text-muted small">
                            <i class="bi bi-clock text-warning me-1"></i>
                            Berjalan: <strong>{{ ($totalTrainings ?? 0) - ($totalCertificates ?? 0) }}</strong> pelatihan
                        </span>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-md-end">
                        <span class="badge bg-success px-3 py-2 rounded-pill" style="font-size: 0.85rem;">
                            <i class="bi bi-check-circle me-1"></i> {{ $completedPercent }}% Selesai
                        </span>
                        @if($inProgressPercent > 0)
                        <span class="badge bg-warning px-3 py-2 rounded-pill" style="font-size: 0.85rem;">
                            <i class="bi bi-clock me-1"></i> {{ $inProgressPercent }}% Berjalan
                        </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Quick Actions -->
    <section class="row g-4 mb-4">
        <div class="col-12">
            <div class="panel border-0 shadow-sm">
                <div class="panel-header bg-light bg-opacity-50">
                    <h5 class="section-title"><i class="bi bi-lightning text-warning"></i> Aksi Cepat</h5>
                </div>
                <div class="p-4">
                    <div class="row g-3">
                        <div class="col-6 col-md-3">
                            <a href="{{ route('peserta.trainings.index') }}" class="btn btn-primary w-100 py-3 rounded-3" style="transition: all 0.3s;">
                                <i class="bi bi-journal-bookmark fs-3 d-block mb-1"></i>
                                <span class="fw-semibold">Lihat Pelatihan</span>
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="{{ route('peserta.quiz.index') }}" class="btn btn-success w-100 py-3 rounded-3" style="transition: all 0.3s;">
                                <i class="bi bi-question-circle fs-3 d-block mb-1"></i>
                                <span class="fw-semibold">Ikuti Quiz</span>
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="{{ route('peserta.sertifikat.index') }}" class="btn btn-info text-white w-100 py-3 rounded-3" style="transition: all 0.3s;">
                                <i class="bi bi-award fs-3 d-block mb-1"></i>
                                <span class="fw-semibold">Lihat Sertifikat</span>
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="{{ route('peserta.profile.index') }}" class="btn btn-warning w-100 py-3 rounded-3" style="transition: all 0.3s;">
                                <i class="bi bi-person fs-3 d-block mb-1"></i>
                                <span class="fw-semibold">Update Profil</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Active Trainings & Recent Certificates -->
    <div class="row g-4">
        <!-- Active Trainings -->
        <div class="col-12 col-lg-6">
            <div class="panel h-100 border-0 shadow-sm">
                <div class="panel-header bg-light bg-opacity-50">
                    <h5 class="section-title"><i class="bi bi-journal-bookmark text-primary"></i> Pelatihan Aktif</h5>
                    <a href="{{ route('peserta.trainings.index', ['filter' => 'ongoing']) }}" class="btn btn-sm btn-outline-primary rounded-pill">
                        Lihat Semua <i class="bi bi-chevron-right"></i>
                    </a>
                </div>
                <div class="p-4">
                    @if(isset($activeTrainings) && $activeTrainings->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($activeTrainings as $training)
                            <div class="list-group-item px-0 py-3 border-bottom">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <h6 class="fw-semibold mb-1">{{ Str::limit($training->judul, 35) }}</h6>
                                        <div class="d-flex flex-wrap gap-2 align-items-center">
                                            <p class="text-muted small mb-0">
                                                <i class="bi bi-calendar me-1"></i>
                                                {{ $training->tanggal_mulai ? $training->tanggal_mulai->format('d/m/Y') : 'TBD' }}
                                                @if($training->tanggal_selesai)
                                                    - {{ $training->tanggal_selesai->format('d/m/Y') }}
                                                @endif
                                            </p>
                                            <span class="badge bg-success rounded-pill">Aktif</span>
                                            @php
                                                $progress = method_exists($training, 'getProgress') ? $training->getProgress() : 0;
                                            @endphp
                                            <span class="badge bg-info rounded-pill">{{ $progress }}%</span>
                                        </div>
                                        @if($progress > 0)
                                        <div class="mt-2" style="width: 100%; max-width: 200px;">
                                            <div class="progress" style="height: 4px; border-radius: 50px;">
                                                <div class="progress-bar bg-success" style="width: {{ $progress }}%; border-radius: 50px;"></div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    <a href="{{ route('peserta.trainings.show', $training->id) }}" class="btn btn-sm btn-primary rounded-circle" style="width: 34px; height: 34px; display: flex; align-items: center; justify-content: center;">
                                        <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                            <p class="text-muted">Belum ada pelatihan aktif</p>
                            <a href="{{ route('peserta.trainings.index') }}" class="btn btn-sm btn-primary rounded-pill">
                                <i class="bi bi-plus-circle me-1"></i> Cari Pelatihan
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Certificates -->
        <div class="col-12 col-lg-6">
            <div class="panel h-100 border-0 shadow-sm">
                <div class="panel-header bg-light bg-opacity-50">
                    <h5 class="section-title"><i class="bi bi-award text-success"></i> Sertifikat Terbaru</h5>
                    <a href="{{ route('peserta.sertifikat.index') }}" class="btn btn-sm btn-outline-primary rounded-pill">
                        Lihat Semua <i class="bi bi-chevron-right"></i>
                    </a>
                </div>
                <div class="p-4">
                    @if(isset($recentCertificates) && $recentCertificates->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentCertificates as $certificate)
                            <div class="list-group-item px-0 py-3 border-bottom">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <h6 class="fw-semibold mb-1">{{ $certificate->nama_sertifikat ?? 'Sertifikat' }}</h6>
                                        <p class="text-muted small mb-0">
                                            <i class="bi bi-calendar-check me-1"></i>
                                            Diterbitkan: {{ $certificate->tanggal_terbit ? $certificate->tanggal_terbit->format('d/m/Y') : '-' }}
                                        </p>
                                        <span class="badge bg-success rounded-pill">Tersedia</span>
                                    </div>
                                    <div class="btn-group">
                                        <a href="{{ route('peserta.sertifikat.show', $certificate->id) }}" class="btn btn-sm btn-light rounded-circle" title="Lihat" style="width: 34px; height: 34px; display: flex; align-items: center; justify-content: center;">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('peserta.sertifikat.download', $certificate->id) }}" class="btn btn-sm btn-success rounded-circle" title="Download" target="_blank" style="width: 34px; height: 34px; display: flex; align-items: center; justify-content: center;">
                                            <i class="bi bi-download"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-award fs-1 text-muted d-block mb-3"></i>
                            <p class="text-muted">Belum ada sertifikat</p>
                            <a href="{{ route('peserta.trainings.index') }}" class="btn btn-sm btn-primary rounded-pill">
                                <i class="bi bi-plus-circle me-1"></i> Mulai Pelatihan
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Quiz Attempts -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="panel border-0 shadow-sm">
                <div class="panel-header bg-light bg-opacity-50">
                    <h5 class="section-title"><i class="bi bi-clock-history text-warning"></i> Riwayat Quiz</h5>
                    <a href="{{ route('peserta.quiz.index') }}" class="btn btn-sm btn-outline-primary rounded-pill">
                        Lihat Semua <i class="bi bi-chevron-right"></i>
                    </a>
                </div>
                <div class="table-responsive">
                    @if(isset($recentQuizAttempts) && $recentQuizAttempts->count() > 0)
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Quiz</th>
                                    <th>Nilai</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentQuizAttempts as $attempt)
                                <tr>
                                    <td>
                                        <span class="fw-semibold">{{ $attempt->quiz->judul ?? '-' }}</span>
                                        <br>
                                        <small class="text-muted">{{ $attempt->quiz->questions->count() ?? 0 }} soal</small>
                                    </td>
                                    <td>
                                        @if($attempt->status == 'completed')
                                            <span class="fw-bold {{ $attempt->is_passed ? 'text-success' : 'text-danger' }}">
                                                {{ $attempt->score }}/{{ $attempt->total_questions ?? 0 }}
                                            </span>
                                            <small class="text-muted d-block">{{ number_format($attempt->percentage ?? 0, 1) }}%</small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge {{ $attempt->status == 'completed' ? 'bg-success' : ($attempt->status == 'in_progress' ? 'bg-warning' : 'bg-secondary') }} rounded-pill">
                                            @if($attempt->status == 'completed')
                                                <i class="bi bi-check-circle me-1"></i>
                                            @elseif($attempt->status == 'in_progress')
                                                <i class="bi bi-hourglass-split me-1"></i>
                                            @else
                                                <i class="bi bi-clock me-1"></i>
                                            @endif
                                            {{ ucfirst($attempt->status ?? 'Pending') }}
                                        </span>
                                    </td>
                                    <td>
                                        <small>
                                            @if($attempt->completed_at)
                                                {{ $attempt->completed_at->format('d/m/Y H:i') }}
                                            @elseif($attempt->started_at)
                                                {{ $attempt->started_at->format('d/m/Y H:i') }}
                                            @else
                                                -
                                            @endif
                                        </small>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group">
                                            @if($attempt->status == 'completed')
                                                <a href="{{ route('peserta.quiz.result', ['quiz' => $attempt->quiz_id, 'attempt' => $attempt->id]) }}" 
                                                   class="btn btn-sm btn-info text-white rounded-circle" title="Lihat Hasil" style="width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center;">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            @endif
                                            @if($attempt->status != 'completed')
                                                <a href="{{ route('peserta.quiz.show', $attempt->quiz_id) }}" 
                                                   class="btn btn-sm btn-primary rounded-circle" title="Lanjutkan" style="width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center;">
                                                    <i class="bi bi-play-circle"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                            <p class="text-muted">Belum ada riwayat quiz</p>
                            <a href="{{ route('peserta.quiz.index') }}" class="btn btn-sm btn-primary rounded-pill">
                                <i class="bi bi-plus-circle me-1"></i> Ikuti Quiz
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .list-group-item:last-child {
        border-bottom: none !important;
    }
    
    .metric-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 1rem;
        border: 1px solid rgba(0,0,0,0.04);
    }
    .metric-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    }
    
    .panel {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border-radius: 1rem !important;
    }
    .panel:hover {
        box-shadow: 0 4px 20px rgba(0,0,0,0.06) !important;
    }
    
    .btn-primary, .btn-success, .btn-info, .btn-warning {
        transition: all 0.3s ease;
    }
    .btn-primary:hover, .btn-success:hover, .btn-info:hover, .btn-warning:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    }
    
    .progress-bar {
        transition: width 1.5s ease;
    }
    
    .badge {
        font-weight: 500;
    }
    
    .rounded-pill {
        border-radius: 50px !important;
    }
    
    .table th {
        font-weight: 600;
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6c757d;
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

    // Animate progress bars on load
    document.querySelectorAll('.progress-bar').forEach(function(bar) {
        const width = bar.style.width;
        bar.style.width = '0%';
        setTimeout(function() {
            bar.style.width = width;
        }, 300);
    });

    // Add hover effect to quick action buttons
    document.querySelectorAll('.quick-action-btn').forEach(function(btn) {
        btn.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        btn.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});
</script>
@endpush
@endsection