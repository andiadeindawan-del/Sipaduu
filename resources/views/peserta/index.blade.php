@extends('layouts.peserta')

@section('title', 'Dashboard Peserta')

@section('header')
<div class="page-heading d-flex justify-content-between align-items-center">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-grid-1x2"></i></span>
        <div>
            <p class="eyebrow">Overview</p>
            <h1 class="h3 mb-1">Dashboard Peserta</h1>
            <p class="text-muted mb-0">Selamat datang, <strong>{{ auth()->user()->nama ?? auth()->user()->name ?? 'Peserta' }}</strong></p>
        </div>
    </div>
    <div class="heading-actions d-flex gap-2">
        <span class="badge" style="font-size: 0.85rem; padding: 0.5rem 1rem; border-radius: 50px; background: #6c757d; color: #fff;">
            <i class="bi bi-calendar-check me-1"></i>
            {{ now()->translatedFormat('d F Y') }}
        </span>
        <button class="btn btn-outline-secondary btn-sm" type="button" onclick="window.print()">
            <i class="bi bi-printer"></i> Cetak
        </button>
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
    
    // Hitung progress
    $completedPercent = $totalTrainings > 0 ? round(($totalCertificates / $totalTrainings) * 100) : 0;
    $inProgressPercent = $totalTrainings > 0 ? round((($totalTrainings - $totalCertificates) / $totalTrainings) * 100) : 0;
    $progressColor = $completedPercent >= 80 ? '#28c76f' : ($completedPercent >= 50 ? '#ff9f43' : '#6c757d');
    
    // Greeting berdasarkan waktu
    $hours = date('H');
    $greeting = '';
    if ($hours < 12) $greeting = 'Selamat pagi! ☀️';
    elseif ($hours < 15) $greeting = 'Selamat siang! 🌤️';
    elseif ($hours < 18) $greeting = 'Selamat sore! 🌅';
    else $greeting = 'Selamat malam! 🌙';
    
    // ============================================================
    // HELPER FUNCTION UNTUK FORMAT TANGGAL
    // ============================================================
    function formatTanggal($date) {
        if (!$date) return '-';
        try {
            return \Carbon\Carbon::parse($date)->format('d/m/Y');
        } catch (\Exception $e) {
            return '-';
        }
    }
@endphp

<div class="container-fluid px-3 px-lg-4 py-4">
    <!-- Profile Completion Status -->
    @include('components.profile-completion', ['user' => auth()->user()])

    <!-- Welcome Banner -->
    <div class="panel mb-4 border-0 shadow-sm" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
        <div class="p-4">
            <div class="row align-items-center">
                <div class="col-12 col-md-8">
                    <h4 class="fw-bold mb-1">
                        <i class="bi bi-hand-thumbs-up me-2" style="color: #ff9f43;"></i>
                        {{ $greeting }} {{ auth()->user()->nama ?? auth()->user()->name ?? 'Peserta' }}!
                    </h4>
                    <p class="text-muted mb-0">
                        Terus semangat dalam belajar dan kembangkan potensi terbaik Anda.
                    </p>
                </div>
                <div class="col-12 col-md-4 text-md-end mt-2 mt-md-0">
                    <span class="badge px-3 py-2 rounded-pill" style="font-size: 0.9rem; background: #6c757d; color: #fff;">
                        <i class="bi bi-award me-1"></i>
                        {{ $totalCertificates }} Sertifikat
                    </span>
                </div>
            </div>
        </div>
    </div>

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

    <!-- Progress Overview -->
    @if($totalTrainings > 0)
    <div class="panel mb-4 border-0 shadow-sm">
        <div class="panel-header bg-light bg-opacity-50">
            <h5 class="section-title"><i class="bi bi-graph-up-arrow" style="color: #6c757d;"></i> Progress Keseluruhan</h5>
        </div>
        <div class="p-4">
            <div class="row g-4 align-items-center">
                <div class="col-12 col-md-8">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small fw-semibold">Progress Belajar</span>
                        <span class="fw-bold" style="color: {{ $progressColor }};">{{ $completedPercent }}%</span>
                    </div>
                    <div class="progress" style="height: 14px; background-color: #e9ecef; border-radius: 50px;">
                        <div class="progress-bar" style="width: {{ $completedPercent }}%; border-radius: 50px; transition: width 1.5s ease; background: linear-gradient(90deg, {{ $progressColor }}, {{ $progressColor }}dd);"></div>
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
                        <span class="badge px-3 py-2 rounded-pill" style="font-size: 0.85rem; background: {{ $progressColor }}; color: #fff;">
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

    <!-- Metode Pembelajaran -->
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
                            <div class="mt-2">
                                <span class="badge bg-secondary rounded-pill me-1">{{ $completedMaterials }}/{{ $totalMaterials }}</span>
                                <a href="{{ route('peserta.materi.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill">
                                    Mulai <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
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
                            <div class="mt-2">
                                <span class="badge bg-secondary rounded-pill me-1">{{ $completedQuizzes }}/{{ $totalQuizzes }}</span>
                                <a href="{{ route('peserta.quiz.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill">
                                    Kerjakan <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
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
                            <div class="mt-2">
                                <span class="badge bg-secondary rounded-pill me-1">{{ $totalHadir }}x</span>
                                <a href="{{ route('peserta.absen.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill">
                                    Absen <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
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
                            <div class="mt-2">
                                <span class="badge bg-secondary rounded-pill me-1">{{ $totalCertificates }}</span>
                                <a href="{{ route('peserta.sertifikat.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill">
                                    Lihat <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Progress Ringkasan -->
            <div class="row mt-4 pt-3 border-top">
                <div class="col-12">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <span class="badge" style="background: #e9ecef; color: #6c757d; padding: 0.5rem 1rem;">
                                <i class="bi bi-file-earmark-text me-1" style="color: #6c757d;"></i>
                                Materi: <strong>{{ $completedMaterials }}</strong>/{{ $totalMaterials }}
                            </span>
                            <span class="badge" style="background: #fff3e0; color: #ff9f43; padding: 0.5rem 1rem;">
                                <i class="bi bi-question-circle me-1" style="color: #ff9f43;"></i>
                                Quiz: <strong>{{ $completedQuizzes }}</strong>/{{ $totalQuizzes }}
                            </span>
                            <span class="badge" style="background: #e8f5e9; color: #28c76f; padding: 0.5rem 1rem;">
                                <i class="bi bi-check2-square me-1" style="color: #28c76f;"></i>
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
                            <a href="{{ route('peserta.trainings.index') }}" class="btn w-100 py-3 rounded-3 text-white" style="transition: all 0.3s; background: #6c757d;">
                                <i class="bi bi-journal-bookmark fs-3 d-block mb-1"></i>
                                <span class="fw-semibold">Lihat Pelatihan</span>
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="{{ route('peserta.quiz.index') }}" class="btn w-100 py-3 rounded-3 text-white" style="transition: all 0.3s; background: #28c76f;">
                                <i class="bi bi-question-circle fs-3 d-block mb-1"></i>
                                <span class="fw-semibold">Ikuti Quiz</span>
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="{{ route('peserta.sertifikat.index') }}" class="btn w-100 py-3 rounded-3 text-white" style="transition: all 0.3s; background: #17a2b8;">
                                <i class="bi bi-award fs-3 d-block mb-1"></i>
                                <span class="fw-semibold">Lihat Sertifikat</span>
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="{{ route('peserta.profile.index') }}" class="btn w-100 py-3 rounded-3 text-white" style="transition: all 0.3s; background: #ff9f43;">
                                <i class="bi bi-person fs-3 d-block mb-1"></i>
                                <span class="fw-semibold">Update Profil</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Recent Activities -->
    <div class="row g-4">
        <!-- Recent Certificates -->
        @if($recentCertificates->count() > 0)
        <div class="col-12 col-md-6">
            <div class="panel border-0 shadow-sm h-100">
                <div class="panel-header bg-light bg-opacity-50">
                    <h5 class="section-title"><i class="bi bi-award" style="color: #28c76f;"></i> Sertifikat Terbaru</h5>
                    <a href="{{ route('peserta.sertifikat.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill">
                        Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="p-3">
                    @foreach($recentCertificates as $cert)
                    <div class="d-flex align-items-center gap-3 p-2 border-bottom">
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: #e8f5e9; color: #28c76f;">
                            <i class="bi bi-award fs-5"></i>
                        </div>
                        <div class="flex-grow-1">
                            <p class="fw-semibold mb-0">{{ $cert->nama ?? $cert->training->judul ?? 'Sertifikat' }}</p>
                            <small class="text-muted">{{ $cert->created_at ? \Carbon\Carbon::parse($cert->created_at)->format('d M Y') : '-' }}</small>
                        </div>
                        <a href="{{ route('peserta.sertifikat.download', $cert->id) }}" class="btn btn-sm btn-outline-success rounded-pill">
                            <i class="bi bi-download"></i>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Recent Quiz Attempts -->
        @if($recentQuizAttempts->count() > 0)
        <div class="col-12 col-md-6">
            <div class="panel border-0 shadow-sm h-100">
                <div class="panel-header bg-light bg-opacity-50">
                    <h5 class="section-title"><i class="bi bi-question-circle" style="color: #ff9f43;"></i> Quiz Terbaru</h5>
                    <a href="{{ route('peserta.quiz.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill">
                        Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="p-3">
                    @foreach($recentQuizAttempts as $attempt)
                    <div class="d-flex align-items-center gap-3 p-2 border-bottom">
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: #fff3e0; color: #ff9f43;">
                            <i class="bi bi-question-circle fs-5"></i>
                        </div>
                        <div class="flex-grow-1">
                            <p class="fw-semibold mb-0">{{ $attempt->quiz->judul ?? 'Quiz' }}</p>
                            <small class="text-muted">
                                Nilai: <strong>{{ $attempt->score ?? 0 }}</strong> | 
                                {{ $attempt->created_at ? $attempt->created_at->format('d M Y') : '-' }}
                            </small>
                        </div>
                        @if($attempt->status == 'completed')
                        <span class="badge bg-success rounded-pill">Selesai</span>
                        @else
                        <span class="badge bg-warning rounded-pill">Proses</span>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Available Trainings -->
    @if($availableTrainings->count() > 0)
    <div class="panel mt-4 border-0 shadow-sm">
        <div class="panel-header bg-light bg-opacity-50">
            <h5 class="section-title"><i class="bi bi-journal-bookmark" style="color: #6c757d;"></i> Pelatihan Tersedia</h5>
            <a href="{{ route('peserta.trainings.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill">
                Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
        <div class="p-4">
            <div class="row g-3">
                @foreach($availableTrainings->take(4) as $training)
                <div class="col-12 col-md-3">
                    <div class="card h-100 border-0 shadow-sm" style="border-radius: 0.75rem; transition: all 0.3s ease;">
                        <div class="card-body">
                            <h6 class="fw-bold mb-1">{{ Str::limit($training->judul, 30) }}</h6>
                            <p class="text-muted small mb-2">{{ Str::limit($training->deskripsi ?? 'Tidak ada deskripsi', 60) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-secondary rounded-pill">
                                    <i class="bi bi-calendar me-1"></i>
                                    {{ formatTanggal($training->tanggal_mulai) }}
                                </span>
                                <a href="{{ route('peserta.trainings.show', $training->id) }}" class="btn btn-sm btn-outline-primary rounded-pill">
                                    Daftar <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
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
    
    .page-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        padding: 1.25rem 1.5rem;
        background: #fff;
        border-bottom: 1px solid #f0f0f0;
    }
    .page-heading-copy {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .page-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        color: #2563eb;
        font-size: 1.3rem;
        flex-shrink: 0;
    }
    .eyebrow {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #8a93a3;
        font-weight: 600;
    }
    .heading-actions {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }
    
    .panel-header {
        padding: .9rem 1.25rem;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: .75rem;
        background: #fafbfc;
    }
    .section-title {
        display: flex;
        align-items: center;
        gap: .5rem;
        margin: 0;
        font-size: 1rem;
        font-weight: 600;
        color: #1a2236;
    }
    
    .metric-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: .4rem;
    }
    .metric-label {
        font-size: .75rem;
        color: #8a93a3;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .03em;
    }
    .metric-icon {
        font-size: 1.3rem;
    }
    .metric-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1a2236;
    }
    .metric-meta {
        font-size: .75rem;
        color: #8a93a3;
        display: flex;
        gap: .35rem;
    }

    @media (max-width: 768px) {
        .page-heading {
            flex-direction: column;
            align-items: flex-start;
        }
        .page-heading-copy {
            width: 100%;
        }
        .heading-actions {
            width: 100%;
            flex-wrap: wrap;
        }
        .metric-value {
            font-size: 1.2rem;
        }
        .panel-header {
            flex-direction: column;
            align-items: flex-start;
        }
        .d-flex.flex-wrap.align-items-center.justify-content-between {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 0.5rem;
        }
        .col-6.col-md-3 {
            flex: 0 0 50%;
            max-width: 50%;
        }
        .btn.w-100.py-3 {
            padding: 0.75rem 0.5rem;
        }
        .btn.w-100.py-3 .fs-3 {
            font-size: 1.5rem !important;
        }
        .btn.w-100.py-3 .fw-semibold {
            font-size: 0.8rem;
        }
        .card .btn-sm {
            font-size: 0.7rem;
            padding: 0.2rem 0.6rem;
        }
        .d-flex.gap-1.justify-content-end {
            flex-wrap: wrap;
            justify-content: flex-start !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ============================================================
    // AUTO CLOSE ALERTS
    // ============================================================
    setTimeout(function() {
        document.querySelectorAll('.alert').forEach(function(alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);

    // ============================================================
    // ANIMATE PROGRESS BARS
    // ============================================================
    document.querySelectorAll('.progress-bar').forEach(function(bar) {
        const width = bar.style.width;
        bar.style.width = '0%';
        setTimeout(function() {
            bar.style.width = width;
        }, 300);
    });

    // ============================================================
    // STATS COUNTER ANIMATION
    // ============================================================
    document.querySelectorAll('.metric-value').forEach(function(el) {
        const target = parseInt(el.textContent);
        if (target > 0 && target < 1000) {
            let current = 0;
            const increment = Math.ceil(target / 30);
            const timer = setInterval(function() {
                current += increment;
                if (current >= target) {
                    el.textContent = target;
                    clearInterval(timer);
                } else {
                    el.textContent = current;
                }
            }, 50);
        }
    });
});
</script>
@endpush
@endsection