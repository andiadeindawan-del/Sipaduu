@extends('layouts.peserta')

@section('title', 'Absensi Pelatihan')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-check2-square"></i></span>
        <div>
            <p class="eyebrow">Kehadiran</p>
            <h1 class="h3 mb-0">Absensi Pelatihan</h1>
            <p class="text-muted mb-0">Lakukan absensi setelah menyelesaikan quiz pada pelatihan yang diikuti.</p>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <!-- Alert Messages -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <div class="d-flex align-items-center">
            <i class="bi bi-check-circle me-2 fs-5"></i>
            <div>{{ session('success') }}</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <div class="d-flex align-items-center">
            <i class="bi bi-exclamation-circle me-2 fs-5"></i>
            <div>{{ session('error') }}</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('warning'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <div class="d-flex align-items-center">
            <i class="bi bi-exclamation-triangle me-2 fs-5"></i>
            <div>{{ session('warning') }}</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Stats Summary -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-success">
                <div class="metric-top">
                    <span class="metric-label">Total Kehadiran</span>
                    <span class="metric-icon"><i class="bi bi-check2-circle"></i></span>
                </div>
                <div class="metric-value">{{ $totalHadir ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-success">Hadir</span>
                    <span>selama ini</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-primary">
                <div class="metric-top">
                    <span class="metric-label">Hadir Bulan Ini</span>
                    <span class="metric-icon"><i class="bi bi-calendar-check"></i></span>
                </div>
                <div class="metric-value">{{ $hadirBulanIni ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-primary">Bulan {{ now()->format('F') }}</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-warning">
                <div class="metric-top">
                    <span class="metric-label">Total Pelatihan</span>
                    <span class="metric-icon"><i class="bi bi-journal-bookmark"></i></span>
                </div>
                <div class="metric-value">{{ $totalPelatihan ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-warning">Diikuti</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-info">
                <div class="metric-top">
                    <span class="metric-label">Persentase Kehadiran</span>
                    <span class="metric-icon"><i class="bi bi-percent"></i></span>
                </div>
                <div class="metric-value">{{ $persentaseKehadiran ?? 0 }}%</div>
                <div class="metric-meta">
                    <span class="text-info">Kehadiran</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Pelatihan untuk Absen -->
    <div class="panel">
        <div class="panel-header">
            <div>
                <h5 class="section-title"><i class="bi bi-list-check"></i> Daftar Pelatihan</h5>
                <p class="text-muted small mb-0">Pelatihan yang dapat diabsensi setelah menyelesaikan quiz.</p>
            </div>
            <div>
                <span class="badge text-bg-info">
                    <i class="bi bi-info-circle me-1"></i>
                    Syarat: Selesaikan semua quiz
                </span>
            </div>
        </div>
        <div class="table-responsive">
            @if(isset($trainings) && $trainings->count() > 0)
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Pelatihan</th>
                        <th>Progress Materi</th>
                        <th>Progress Quiz</th>
                        <th>Status Quiz</th>
                        <th>Status Absensi</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($trainings as $index => $training)
                    @php
                        // Hitung progress materi
                        $totalMateri = $training->materi->count();
                        $selesaiMateri = $training->materi->filter(function($materi) use ($training) {
                            return $materi->pivot && $materi->pivot->status === 'completed';
                        })->count();
                        $progressMateri = $totalMateri > 0 ? round(($selesaiMateri / $totalMateri) * 100) : 0;

                        // Hitung progress quiz
                        $totalQuiz = $training->quiz->count();
                        $selesaiQuiz = $training->quiz->filter(function($quiz) use ($training) {
                            return $quiz->pivot && $quiz->pivot->status === 'completed';
                        })->count();
                        $progressQuiz = $totalQuiz > 0 ? round(($selesaiQuiz / $totalQuiz) * 100) : 0;
                        $isQuizComplete = $totalQuiz > 0 && $selesaiQuiz == $totalQuiz;

                        // Cek status absensi
                        $sudahAbsen = $training->absensi->where('user_id', auth()->id())->first();
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar-text rounded-circle bg-success text-white" style="width: 32px; height: 32px; font-size: 0.7rem;">
                                    {{ strtoupper(substr($training->judul, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="fw-semibold mb-0">{{ Str::limit($training->judul, 40) }}</p>
                                    <small class="text-muted">{{ $training->kategori->nama ?? 'Umum' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress" style="width: 80px; height: 6px;">
                                    <div class="progress-bar bg-primary" role="progressbar" 
                                         style="width: {{ $progressMateri }}%;" 
                                         aria-valuenow="{{ $progressMateri }}" 
                                         aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                                <span class="small fw-semibold">{{ $progressMateri }}%</span>
                            </div>
                            <small class="text-muted d-block">{{ $selesaiMateri }}/{{ $totalMateri }} materi</small>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress" style="width: 80px; height: 6px;">
                                    <div class="progress-bar {{ $isQuizComplete ? 'bg-success' : 'bg-warning' }}" 
                                         role="progressbar" 
                                         style="width: {{ $progressQuiz }}%;" 
                                         aria-valuenow="{{ $progressQuiz }}" 
                                         aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                                <span class="small fw-semibold">{{ $progressQuiz }}%</span>
                            </div>
                            <small class="text-muted d-block">{{ $selesaiQuiz }}/{{ $totalQuiz }} quiz</small>
                        </td>
                        <td>
                            @if($isQuizComplete)
                                <span class="badge text-bg-success">
                                    <i class="bi bi-check-circle me-1"></i> Quiz Selesai
                                </span>
                            @elseif($totalQuiz > 0)
                                <span class="badge text-bg-warning">
                                    <i class="bi bi-hourglass-split me-1"></i> {{ $progressQuiz }}% Selesai
                                </span>
                            @else
                                <span class="badge text-bg-secondary">
                                    <i class="bi bi-dash-circle me-1"></i> Belum Ada Quiz
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($sudahAbsen)
                                <span class="badge text-bg-success">
                                    <i class="bi bi-check2-circle me-1"></i> Sudah Absen
                                </span>
                                <small class="text-muted d-block">
                                    {{ $sudahAbsen->created_at ? $sudahAbsen->created_at->format('d/m/Y H:i') : '-' }}
                                </small>
                            @else
                                <span class="badge text-bg-danger">
                                    <i class="bi bi-x-circle me-1"></i> Belum Absen
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if(!$sudahAbsen && $isQuizComplete)
                                <form action="{{ route('peserta.absen.store') }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="training_id" value="{{ $training->id }}">
                                    <button type="submit" class="btn btn-success btn-sm" 
                                            onclick="return confirm('Apakah Anda yakin ingin melakukan absensi untuk pelatihan ini?')">
                                        <i class="bi bi-check2 me-1"></i> Absen Sekarang
                                    </button>
                                </form>
                            @elseif($sudahAbsen)
                                <span class="text-success fs-5">
                                    <i class="bi bi-check2-circle"></i>
                                </span>
                            @elseif(!$isQuizComplete && $totalQuiz > 0)
                                <button class="btn btn-secondary btn-sm" disabled title="Selesaikan semua quiz terlebih dahulu">
                                    <i class="bi bi-lock me-1"></i> Terkunci
                                </button>
                                <small class="text-muted d-block">Selesaikan quiz</small>
                            @else
                                <button class="btn btn-secondary btn-sm" disabled title="Belum ada quiz">
                                    <i class="bi bi-dash-circle me-1"></i> Tidak Tersedia
                                </button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="text-center py-5">
                <div class="text-muted">
                    <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                    <p class="h5">Belum ada pelatihan</p>
                    <p class="small">Anda belum terdaftar dalam pelatihan apapun.</p>
                    <a href="{{ route('peserta.trainings.index') }}" class="btn btn-primary btn-sm mt-2">
                        <i class="bi bi-search me-1"></i> Cari Pelatihan
                    </a>
                </div>
            </div>
            @endif
        </div>
        @if(isset($trainings) && $trainings->hasPages())
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mt-3 px-3 pb-3">
            <p class="text-muted small mb-0">
                Menampilkan {{ $trainings->firstItem() ?? 0 }} sampai {{ $trainings->lastItem() ?? 0 }} 
                dari {{ $trainings->total() ?? 0 }} pelatihan
            </p>
            <nav aria-label="Pagination">
                {{ $trainings->appends(request()->query())->links() }}
            </nav>
        </div>
        @endif
    </div>

    <!-- Riwayat Absensi -->
    <div class="panel mt-4">
        <div class="panel-header">
            <div>
                <h5 class="section-title"><i class="bi bi-clock-history"></i> Riwayat Absensi</h5>
                <p class="text-muted small mb-0">Riwayat absensi yang telah Anda lakukan.</p>
            </div>
        </div>
        <div class="table-responsive">
            @if(isset($riwayatAbsensi) && $riwayatAbsensi->count() > 0)
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Pelatihan</th>
                        <th>Tanggal Absen</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($riwayatAbsensi as $index => $absen)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar-text rounded-circle bg-success text-white" style="width: 32px; height: 32px; font-size: 0.7rem;">
                                    {{ strtoupper(substr($absen->training->judul ?? '-', 0, 2)) }}
                                </div>
                                <div>
                                    <p class="fw-semibold mb-0">{{ Str::limit($absen->training->judul ?? '-', 40) }}</p>
                                    <small class="text-muted">{{ $absen->training->kategori->nama ?? '-' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>{{ $absen->created_at ? $absen->created_at->format('d/m/Y H:i') : '-' }}</td>
                        <td>
                            <span class="badge text-bg-success">
                                <i class="bi bi-check2-circle me-1"></i> Hadir
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="text-center py-4">
                <i class="bi bi-clock-history fs-2 text-muted d-block mb-2"></i>
                <p class="text-muted small mb-0">Belum ada riwayat absensi.</p>
            </div>
            @endif
        </div>
        @if(isset($riwayatAbsensi) && $riwayatAbsensi->hasPages())
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mt-3 px-3 pb-3">
            <p class="text-muted small mb-0">
                Menampilkan {{ $riwayatAbsensi->firstItem() ?? 0 }} sampai {{ $riwayatAbsensi->lastItem() ?? 0 }} 
                dari {{ $riwayatAbsensi->total() ?? 0 }} riwayat
            </p>
            <nav aria-label="Riwayat pagination">
                {{ $riwayatAbsensi->appends(request()->query())->links() }}
            </nav>
        </div>
        @endif
    </div>
</div>

@push('styles')
<style>
    .avatar-text {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.7rem;
        color: #fff;
        background: var(--accent);
        border-radius: 50%;
        flex-shrink: 0;
    }
    .progress {
        border-radius: 4px;
        background-color: #f0f0f0;
    }
    .progress-bar {
        border-radius: 4px;
        transition: width 0.6s ease;
    }
    .metric-card {
        transition: transform 0.2s ease;
    }
    .metric-card:hover {
        transform: translateY(-2px);
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

        // Auto refresh progress setelah 30 detik (optional)
        // setInterval(function() {
        //     location.reload();
        // }, 30000);
    });
</script>
@endpush
@endsection