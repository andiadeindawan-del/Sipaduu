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
            <span class="badge" style="font-size: 0.85rem; padding: 0.5rem 1rem; border-radius: 50px; background: #6c757d; color: #fff;">
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
@php
    // ============================================================
    // DEFAULT VALUES - MENCEGAH ERROR UNDEFINED VARIABLE
    // ============================================================
    $totalTrainings = $totalTrainings ?? 0;
    $totalCertificates = $totalCertificates ?? 0;
    $totalQuizAttempts = $totalQuizAttempts ?? 0;
    $averageQuizScore = $averageQuizScore ?? 0;
    $totalMaterials = $totalMaterials ?? 0;
    $completedMaterials = $completedMaterials ?? 0;
    $totalQuizzes = $totalQuizzes ?? 0;
    $completedQuizzes = $completedQuizzes ?? 0;
    $totalHadir = $totalHadir ?? 0;
    $activeTrainings = $activeTrainings ?? collect();
    $recentCertificates = $recentCertificates ?? collect();
    $recentQuizAttempts = $recentQuizAttempts ?? collect();
    $recentActivities = $recentActivities ?? collect();
    $kategoris = $kategoris ?? collect();
    $availableTrainings = $availableTrainings ?? collect();
    $completedTrainings = $completedTrainings ?? 0;
    $ongoingTrainings = $ongoingTrainings ?? 0;
    $upcomingTrainings = $upcomingTrainings ?? collect();
    $averageProgress = $averageProgress ?? 0;
@endphp

<div class="container-fluid px-3 px-lg-4 py-4">
    <!-- Statistics Cards -->
    <section class="row g-3 mb-4" aria-label="Dashboard metrics">
        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card" style="border-left-color: #6c757d;">
                <div class="metric-top">
                    <span class="metric-label">Total Pelatihan</span>
                    <span class="metric-icon" style="color: #6c757d;"><i class="bi bi-journal-bookmark"></i></span>
                </div>
                <div class="metric-value">{{ $totalTrainings }}</div>
                <div class="metric-meta">
                    <span style="color: #6c757d;">Semua</span>
                    <span>pelatihan</span>
                </div>
            </article>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card" style="border-left-color: #28c76f;">
                <div class="metric-top">
                    <span class="metric-label">Sertifikat Diperoleh</span>
                    <span class="metric-icon" style="color: #28c76f;"><i class="bi bi-award"></i></span>
                </div>
                <div class="metric-value">{{ $totalCertificates }}</div>
                <div class="metric-meta">
                    <span style="color: #28c76f;">Selesai</span>
                    <span>sertifikat</span>
                </div>
            </article>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card" style="border-left-color: #ff9f43;">
                <div class="metric-top">
                    <span class="metric-label">Quiz Dikerjakan</span>
                    <span class="metric-icon" style="color: #ff9f43;"><i class="bi bi-question-circle"></i></span>
                </div>
                <div class="metric-value">{{ $totalQuizAttempts }}</div>
                <div class="metric-meta">
                    <span style="color: #ff9f43;">Total</span>
                    <span>quiz</span>
                </div>
            </article>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card" style="border-left-color: #17a2b8;">
                <div class="metric-top">
                    <span class="metric-label">Rata-rata Nilai Quiz</span>
                    <span class="metric-icon" style="color: #17a2b8;"><i class="bi bi-star"></i></span>
                </div>
                <div class="metric-value">{{ number_format($averageQuizScore, 1) }}</div>
                <div class="metric-meta">
                    <span style="color: #17a2b8;">Keseluruhan</span>
                    <span>nilai</span>
                </div>
            </article>
        </div>
    </section>

    <!-- ============================================================ -->
    <!-- METODE PEMBELAJARAN -->
    <!-- ============================================================ -->
    <div class="panel mb-4 border-0 shadow-sm">
        <div class="panel-header bg-light bg-opacity-50">
            <h5 class="section-title"><i class="bi bi-book" style="color: #6c757d;"></i> Metode Pembelajaran</h5>
            <span class="badge" style="background: #6c757d; color: #fff;">Panduan Belajar</span>
        </div>
        <div class="p-4">
            <div class="row g-4">
                <!-- Metode 1: Materi -->
                <div class="col-12 col-md-3">
                    <div class="card h-100 border-0 shadow-sm" style="border-radius: 1rem; transition: all 0.3s ease;">
                        <div class="card-body text-center p-4">
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px; background: #e9ecef; color: #6c757d;">
                                <i class="bi bi-file-earmark-text fs-2"></i>
                            </div>
                            <h6 class="fw-bold">1. Materi</h6>
                            <p class="text-muted small">Pelajari materi pelatihan secara mandiri melalui video, PDF, dan artikel.</p>
                            <a href="{{ route('peserta.materi.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill mt-2">
                                Mulai Belajar <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Metode 2: Quiz -->
                <div class="col-12 col-md-3">
                    <div class="card h-100 border-0 shadow-sm" style="border-radius: 1rem; transition: all 0.3s ease;">
                        <div class="card-body text-center p-4">
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px; background: #fff3e0; color: #ff9f43;">
                                <i class="bi bi-question-circle fs-2"></i>
                            </div>
                            <h6 class="fw-bold">2. Quiz</h6>
                            <p class="text-muted small">Uji pemahaman Anda dengan mengerjakan quiz setelah mempelajari materi.</p>
                            <a href="{{ route('peserta.quiz.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill mt-2">
                                Kerjakan Quiz <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Metode 3: Kehadiran -->
                <div class="col-12 col-md-3">
                    <div class="card h-100 border-0 shadow-sm" style="border-radius: 1rem; transition: all 0.3s ease;">
                        <div class="card-body text-center p-4">
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px; background: #e8f5e9; color: #28c76f;">
                                <i class="bi bi-check2-square fs-2"></i>
                            </div>
                            <h6 class="fw-bold">3. Kehadiran</h6>
                            <p class="text-muted small">Lakukan absensi untuk mencatat kehadiran Anda dalam setiap pelatihan.</p>
                            <a href="{{ route('peserta.absen.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill mt-2">
                                Absen Sekarang <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Metode 4: Sertifikat -->
                <div class="col-12 col-md-3">
                    <div class="card h-100 border-0 shadow-sm" style="border-radius: 1rem; transition: all 0.3s ease;">
                        <div class="card-body text-center p-4">
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px; background: #e3f2fd; color: #17a2b8;">
                                <i class="bi bi-award fs-2"></i>
                            </div>
                            <h6 class="fw-bold">4. Sertifikat</h6>
                            <p class="text-muted small">Dapatkan sertifikat resmi setelah menyelesaikan seluruh rangkaian pelatihan.</p>
                            <a href="{{ route('peserta.sertifikat.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill mt-2">
                                Lihat Sertifikat <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Progress Ringkasan -->
            <div class="row mt-4 pt-3 border-top">
                <div class="col-12">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge" style="background: #e9ecef; color: #6c757d; padding: 0.5rem 1rem;">
                                <i class="bi bi-check-circle me-1" style="color: #28c76f;"></i>
                                Materi: <strong>{{ $completedMaterials }}</strong>/{{ $totalMaterials }}
                            </span>
                            <span class="badge" style="background: #fff3e0; color: #ff9f43; padding: 0.5rem 1rem;">
                                <i class="bi bi-check-circle me-1" style="color: #28c76f;"></i>
                                Quiz: <strong>{{ $completedQuizzes }}</strong>/{{ $totalQuizzes }}
                            </span>
                            <span class="badge" style="background: #e8f5e9; color: #28c76f; padding: 0.5rem 1rem;">
                                <i class="bi bi-check-circle me-1" style="color: #28c76f;"></i>
                                Kehadiran: <strong>{{ $totalHadir }}</strong>
                            </span>
                            <span class="badge" style="background: #e3f2fd; color: #17a2b8; padding: 0.5rem 1rem;">
                                <i class="bi bi-award me-1"></i>
                                Sertifikat: <strong>{{ $totalCertificates }}</strong>
                            </span>
                        </div>
                        <div>
                            <span class="text-muted small">
                                <i class="bi bi-info-circle me-1"></i>
                                Selesaikan semua langkah untuk mendapatkan sertifikat
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Overview -->
    @if($totalTrainings > 0)
    <div class="panel mb-4 border-0 shadow-sm">
        <div class="panel-header bg-light bg-opacity-50">
            <h5 class="section-title"><i class="bi bi-graph-up-arrow" style="color: #6c757d;"></i> Progress Keseluruhan</h5>
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
                        <span class="fw-bold" style="color: #6c757d;">{{ $completedPercent }}%</span>
                    </div>
                    <div class="progress" style="height: 14px; background-color: #e9ecef; border-radius: 50px;">
                        <div class="progress-bar" style="width: {{ $completedPercent }}%; border-radius: 50px; transition: width 1.5s ease; background: linear-gradient(90deg, #6c757d, #a8b0b8);"></div>
                    </div>
                    <div class="d-flex justify-content-between mt-2">
                        <span class="text-muted small">
                            <i class="bi bi-check-circle" style="color: #28c76f;"></i>
                            Selesai: <strong>{{ $totalCertificates }}</strong> pelatihan
                        </span>
                        <span class="text-muted small">
                            <i class="bi bi-clock" style="color: #ff9f43;"></i>
                            Berjalan: <strong>{{ $totalTrainings - $totalCertificates }}</strong> pelatihan
                        </span>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-md-end">
                        <span class="badge px-3 py-2 rounded-pill" style="font-size: 0.85rem; background: #6c757d; color: #fff;">
                            <i class="bi bi-check-circle me-1"></i> {{ $completedPercent }}% Selesai
                        </span>
                        @if($inProgressPercent > 0)
                        <span class="badge px-3 py-2 rounded-pill" style="font-size: 0.85rem; background: #ff9f43; color: #fff;">
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
                    <h5 class="section-title"><i class="bi bi-lightning" style="color: #ff9f43;"></i> Aksi Cepat</h5>
                </div>
                <div class="p-4">
                    <div class="row g-3">
                        <div class="col-6 col-md-3">
                            <a href="{{ route('peserta.trainings.index') }}" class="btn w-100 py-3 rounded-3" style="transition: all 0.3s; background: #6c757d; color: #fff;">
                                <i class="bi bi-journal-bookmark fs-3 d-block mb-1"></i>
                                <span class="fw-semibold">Lihat Pelatihan</span>
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="{{ route('peserta.quiz.index') }}" class="btn w-100 py-3 rounded-3" style="transition: all 0.3s; background: #28c76f; color: #fff;">
                                <i class="bi bi-question-circle fs-3 d-block mb-1"></i>
                                <span class="fw-semibold">Ikuti Quiz</span>
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="{{ route('peserta.sertifikat.index') }}" class="btn w-100 py-3 rounded-3" style="transition: all 0.3s; background: #17a2b8; color: #fff;">
                                <i class="bi bi-award fs-3 d-block mb-1"></i>
                                <span class="fw-semibold">Lihat Sertifikat</span>
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="{{ route('peserta.profile.index') }}" class="btn w-100 py-3 rounded-3" style="transition: all 0.3s; background: #ff9f43; color: #fff;">
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
                    <h5 class="section-title"><i class="bi bi-journal-bookmark" style="color: #6c757d;"></i> Pelatihan Aktif</h5>
                    <a href="{{ route('peserta.trainings.index', ['filter' => 'ongoing']) }}" class="btn btn-sm btn-outline-secondary rounded-pill">
                        Lihat Semua <i class="bi bi-chevron-right"></i>
                    </a>
                </div>
                <div class="p-4">
                    @if($activeTrainings->count() > 0)
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
                                            <span class="badge rounded-pill" style="background: #6c757d; color: #fff;">Aktif</span>
                                            @php
                                                $progress = property_exists($training, 'progress') ? $training->progress : 0;
                                            @endphp
                                            <span class="badge rounded-pill" style="background: #28c76f; color: #fff;">{{ $progress }}%</span>
                                        </div>
                                        @if($progress > 0)
                                        <div class="mt-2" style="width: 100%; max-width: 200px;">
                                            <div class="progress" style="height: 4px; border-radius: 50px;">
                                                <div class="progress-bar" style="width: {{ $progress }}%; border-radius: 50px; background: linear-gradient(90deg, #6c757d, #a8b0b8);"></div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    <a href="{{ route('peserta.trainings.show', $training->id) }}" class="btn btn-sm rounded-circle" style="width: 34px; height: 34px; display: flex; align-items: center; justify-content: center; background: #6c757d; color: #fff;">
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
                            <a href="{{ route('peserta.trainings.index') }}" class="btn btn-sm rounded-pill" style="background: #6c757d; color: #fff;">
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
                    <h5 class="section-title"><i class="bi bi-award" style="color: #28c76f;"></i> Sertifikat Terbaru</h5>
                    <a href="{{ route('peserta.sertifikat.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill">
                        Lihat Semua <i class="bi bi-chevron-right"></i>
                    </a>
                </div>
                <div class="p-4">
                    @if($recentCertificates->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentCertificates as $certificate)
                            <div class="list-group-item px-0 py-3 border-bottom">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <h6 class="fw-semibold mb-1">{{ $certificate->judul ?? 'Sertifikat' }}</h6>
                                        <p class="text-muted small mb-0">
                                            <i class="bi bi-calendar-check me-1"></i>
                                            Diterbitkan: {{ $certificate->created_at ? $certificate->created_at->format('d/m/Y') : '-' }}
                                        </p>
                                        <span class="badge rounded-pill" style="background: #28c76f; color: #fff;">Tersedia</span>
                                    </div>
                                    <div class="btn-group">
                                        <a href="{{ route('peserta.sertifikat.show', $certificate->id) }}" class="btn btn-sm btn-light rounded-circle" title="Lihat" style="width: 34px; height: 34px; display: flex; align-items: center; justify-content: center;">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('peserta.sertifikat.download', $certificate->id) }}" class="btn btn-sm rounded-circle" title="Download" target="_blank" style="width: 34px; height: 34px; display: flex; align-items: center; justify-content: center; background: #28c76f; color: #fff;">
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
                            <a href="{{ route('peserta.trainings.index') }}" class="btn btn-sm rounded-pill" style="background: #6c757d; color: #fff;">
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
                    <h5 class="section-title"><i class="bi bi-clock-history" style="color: #ff9f43;"></i> Riwayat Quiz</h5>
                    <a href="{{ route('peserta.quiz.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill">
                        Lihat Semua <i class="bi bi-chevron-right"></i>
                    </a>
                </div>
                <div class="table-responsive">
                    @if($recentQuizAttempts->count() > 0)
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
                                        <span class="badge rounded-pill {{ $attempt->status == 'completed' ? 'bg-success' : ($attempt->status == 'in_progress' ? 'bg-warning' : 'bg-secondary') }}">
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
                                                   class="btn btn-sm rounded-circle text-white" title="Lihat Hasil" style="width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center; background: #17a2b8;">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            @endif
                                            @if($attempt->status != 'completed')
                                                <a href="{{ route('peserta.quiz.show', $attempt->quiz_id) }}" 
                                                   class="btn btn-sm rounded-circle text-white" title="Lanjutkan" style="width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center; background: #6c757d;">
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
                            <a href="{{ route('peserta.quiz.index') }}" class="btn btn-sm rounded-pill" style="background: #6c757d; color: #fff;">
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
    
    .btn {
        transition: all 0.3s ease;
    }
    .btn:hover {
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

    .card {
        transition: all 0.3s ease;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.08) !important;
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
});
</script>
@endpush
@endsection