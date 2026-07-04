@extends('layouts.peserta')

@section('title', 'Dashboard Peserta')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-grid-1x2"></i></span>
        <div>
            <p class="eyebrow">Dashboard</p>
            <h1 class="h3 mb-0">Dashboard Peserta</h1>
            <p class="text-muted mb-0">Selamat datang di dashboard peserta, {{ auth()->user()->nama ?? auth()->user()->name }}</p>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-primary">
                <div class="metric-top">
                    <span class="metric-label">Total Pelatihan</span>
                    <span class="metric-icon"><i class="bi bi-journal-bookmark"></i></span>
                </div>
                <div class="metric-value">{{ $totalTrainings ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-primary">Semua</span>
                    <span>pelatihan</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-success">
                <div class="metric-top">
                    <span class="metric-label">Sertifikat Diperoleh</span>
                    <span class="metric-icon"><i class="bi bi-award"></i></span>
                </div>
                <div class="metric-value">{{ $totalCertificates ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-success">Selesai</span>
                    <span>sertifikat</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-warning">
                <div class="metric-top">
                    <span class="metric-label">Quiz Dikerjakan</span>
                    <span class="metric-icon"><i class="bi bi-question-circle"></i></span>
                </div>
                <div class="metric-value">{{ $totalQuizAttempts ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-warning">Total</span>
                    <span>quiz</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-info">
                <div class="metric-top">
                    <span class="metric-label">Rata-rata Nilai Quiz</span>
                    <span class="metric-icon"><i class="bi bi-star"></i></span>
                </div>
                <div class="metric-value">{{ number_format($averageQuizScore ?? 0, 1) }}</div>
                <div class="metric-meta">
                    <span class="text-info">Keseluruhan</span>
                    <span>nilai</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Active Trainings -->
        <div class="col-12 col-lg-6">
            <div class="panel">
                <div class="panel-header">
                    <h5 class="section-title"><i class="bi bi-journal-bookmark"></i> Pelatihan Aktif</h5>
                    <a href="{{ route('peserta.trainings.index') }}" class="btn btn-sm btn-outline-primary">
                        Lihat Semua <i class="bi bi-chevron-right"></i>
                    </a>
                </div>
                <div class="p-4">
                    @if(isset($activeTrainings) && $activeTrainings->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($activeTrainings as $training)
                            <div class="list-group-item px-0 py-3 border-bottom">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="fw-semibold mb-1">{{ $training->judul }}</h6>
                                        <p class="text-muted small mb-0">
                                            <i class="bi bi-calendar me-1"></i>
                                            {{ $training->tanggal_mulai ? $training->tanggal_mulai->format('d/m/Y') : 'TBD' }}
                                            @if($training->tanggal_selesai)
                                                - {{ $training->tanggal_selesai->format('d/m/Y') }}
                                            @endif
                                        </p>
                                        <span class="badge bg-success">Aktif</span>
                                    </div>
                                    <a href="{{ route('peserta.trainings.show', $training->id) }}" class="btn btn-sm btn-primary">
                                        Lihat <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                            <p class="text-muted">Belum ada pelatihan aktif</p>
                            <a href="{{ route('peserta.trainings.index') }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-plus-circle me-1"></i> Cari Pelatihan
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Actions & Certificates -->
        <div class="col-12 col-lg-6">
            <div class="row g-4">
                <!-- Quick Actions -->
                <div class="col-12">
                    <div class="panel">
                        <div class="panel-header">
                            <h5 class="section-title"><i class="bi bi-lightning"></i> Aksi Cepat</h5>
                        </div>
                        <div class="p-4">
                            <div class="d-grid gap-3">
                                <a href="{{ route('peserta.trainings.index') }}" class="btn btn-primary">
                                    <i class="bi bi-journal-bookmark me-2"></i> Lihat Pelatihan
                                </a>
                                <a href="{{ route('peserta.quiz.index') }}" class="btn btn-success">
                                    <i class="bi bi-question-circle me-2"></i> Ikuti Quiz
                                </a>
                                <a href="{{ route('peserta.sertifikat.index') }}" class="btn btn-info text-white">
                                    <i class="bi bi-award me-2"></i> Lihat Sertifikat
                                </a>
                                <a href="{{ route('peserta.profile.index') }}" class="btn btn-warning">
                                    <i class="bi bi-person me-2"></i> Update Profil
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Certificates -->
                <div class="col-12">
                    <div class="panel">
                        <div class="panel-header">
                            <h5 class="section-title"><i class="bi bi-award"></i> Sertifikat Terbaru</h5>
                            <a href="{{ route('peserta.sertifikat.index') }}" class="btn btn-sm btn-outline-primary">
                                Lihat Semua <i class="bi bi-chevron-right"></i>
                            </a>
                        </div>
                        <div class="p-4">
                            @if(isset($recentCertificates) && $recentCertificates->count() > 0)
                                <div class="list-group list-group-flush">
                                    @foreach($recentCertificates as $certificate)
                                    <div class="list-group-item px-0 py-3 border-bottom">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="fw-semibold mb-1">{{ $certificate->nama_sertifikat ?? 'Sertifikat' }}</h6>
                                                <p class="text-muted small mb-0">
                                                    <i class="bi bi-calendar-check me-1"></i>
                                                    Diterbitkan: {{ $certificate->tanggal_terbit ? $certificate->tanggal_terbit->format('d/m/Y') : '-' }}
                                                </p>
                                                <span class="badge bg-success">Tersedia</span>
                                            </div>
                                            <div class="btn-group">
                                                <a href="{{ route('peserta.sertifikat.show', $certificate->id) }}" class="btn btn-sm btn-light">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('peserta.sertifikat.download', $certificate->id) }}" class="btn btn-sm btn-success" target="_blank">
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
                                    <a href="{{ route('peserta.trainings.index') }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-plus-circle me-1"></i> Mulai Pelatihan
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Quiz Attempts -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="panel">
                <div class="panel-header">
                    <h5 class="section-title"><i class="bi bi-clock-history"></i> Riwayat Quiz</h5>
                    <a href="{{ route('peserta.quiz.index') }}" class="btn btn-sm btn-outline-primary">
                        Lihat Semua <i class="bi bi-chevron-right"></i>
                    </a>
                </div>
                <div class="table-responsive">
                    @if(isset($recentQuizAttempts) && $recentQuizAttempts->count() > 0)
                        <table class="table align-middle mb-0">
                            <thead>
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
                                    <td>{{ $attempt->quiz->judul ?? '-' }}</td>
                                    <td>
                                        @if($attempt->status == 'completed')
                                            <span class="fw-bold {{ $attempt->is_passed ? 'text-success' : 'text-danger' }}">
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
                                        <a href="{{ route('peserta.quiz.result', ['quiz' => $attempt->quiz_id, 'attempt' => $attempt->id]) }}" class="btn btn-sm btn-light">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                            <p class="text-muted">Belum ada riwayat quiz</p>
                            <a href="{{ route('peserta.quiz.index') }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-plus-circle me-1"></i> Ikuti Quiz
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Informasi Profil -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="panel">
                <div class="panel-header">
                    <h5 class="section-title"><i class="bi bi-person"></i> Informasi Profil</h5>
                    <a href="{{ route('peserta.profile.index') }}" class="btn btn-sm btn-outline-primary">
                        Edit Profil <i class="bi bi-chevron-right"></i>
                    </a>
                </div>
                <div class="p-4">
                    <div class="row g-3">
                        <div class="col-12 col-md-3">
                            <div class="text-center">
                                @if(auth()->user()->foto)
                                    <img src="{{ asset('storage/' . auth()->user()->foto) }}" 
                                         alt="Foto" class="rounded-circle" 
                                         style="width: 80px; height: 80px; object-fit: cover; border: 3px solid #e8ecf1;">
                                @else
                                    <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center" 
                                         style="width: 80px; height: 80px; background: #4e9af1; color: #fff; font-size: 32px;">
                                        {{ auth()->user()->initials ?? 'U' }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-12 col-md-9">
                            <div class="row g-2">
                                <div class="col-12 col-md-6">
                                    <label class="text-muted small fw-semibold">Nama</label>
                                    <p class="fw-semibold mb-0">{{ auth()->user()->nama ?? auth()->user()->name }}</p>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="text-muted small fw-semibold">Email</label>
                                    <p class="fw-semibold mb-0">{{ auth()->user()->email }}</p>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="text-muted small fw-semibold">NIK</label>
                                    <p class="fw-semibold mb-0">{{ auth()->user()->nik ?? '-' }}</p>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="text-muted small fw-semibold">No. Telepon</label>
                                    <p class="fw-semibold mb-0">{{ auth()->user()->no_telepon ?? '-' }}</p>
                                </div>
                                @if(auth()->user()->nama_usaha)
                                <div class="col-12">
                                    <label class="text-muted small fw-semibold">Nama Usaha</label>
                                    <p class="fw-semibold mb-0">{{ auth()->user()->nama_usaha }}</p>
                                </div>
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
    .list-group-item:last-child {
        border-bottom: none !important;
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