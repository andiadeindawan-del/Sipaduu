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