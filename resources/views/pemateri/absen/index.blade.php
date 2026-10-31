@extends('layouts.pemateri')

@section('title', 'Presensi & QR Code Absen')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon" style="color: #6f42c1;"><i class="bi bi-qr-code-scan"></i></span>
        <div>
            <p class="eyebrow">Manajemen Kehadiran</p>
            <h1 class="h3 mb-0">Presensi & QR Code Absen</h1>
            <p class="text-muted mb-0">Buka QR Code absensi untuk peserta pelatihan dan pantau kehadiran kelas Anda secara real-time.</p>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <!-- Alert Messages -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-circle me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Metric Stats -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card border-0 shadow-sm" style="border-left: 4px solid #6f42c1 !important;">
                <div class="metric-top">
                    <span class="metric-label" style="color: #6f42c1;">Kelas Mengajar</span>
                    <span class="metric-icon" style="color: #6f42c1;"><i class="bi bi-journal-bookmark-fill"></i></span>
                </div>
                <div class="metric-value" style="color: #6f42c1;">{{ $totalTrainings }}</div>
                <div class="metric-meta">
                    <span class="text-muted small">Pelatihan Anda</span>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card border-0 shadow-sm" style="border-left: 4px solid #28c76f !important;">
                <div class="metric-top">
                    <span class="metric-label text-success">Sesi QR Aktif</span>
                    <span class="metric-icon text-success"><i class="bi bi-broadcast"></i></span>
                </div>
                <div class="metric-value text-success">{{ $activeSessions }}</div>
                <div class="metric-meta">
                    <span class="text-muted small">Absensi sedang dibuka</span>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card border-0 shadow-sm" style="border-left: 4px solid #0d6efd !important;">
                <div class="metric-top">
                    <span class="metric-label text-primary">Hadir Hari Ini</span>
                    <span class="metric-icon text-primary"><i class="bi bi-check-circle-fill"></i></span>
                </div>
                <div class="metric-value text-primary">{{ $totalHadirToday }}</div>
                <div class="metric-meta">
                    <span class="text-muted small">{{ \Carbon\Carbon::parse($today)->format('d M Y') }}</span>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card border-0 shadow-sm" style="border-left: 4px solid #ff9f43 !important;">
                <div class="metric-top">
                    <span class="metric-label text-warning">Total Presensi</span>
                    <span class="metric-icon text-warning"><i class="bi bi-clock-history"></i></span>
                </div>
                <div class="metric-value text-warning">{{ $totalAbsensiAll }}</div>
                <div class="metric-meta">
                    <span class="text-muted small">Rekap semua sesi</span>
                </div>
            </div>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- SECTION 1: PELATIHAN UNTUK BUKA SESI QR ABSENSI -->
    <!-- ======================================================== -->
    <div class="panel border-0 shadow-sm mb-4">
        <div class="panel-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h5 class="section-title"><i class="bi bi-qr-code-scan" style="color: #6f42c1;"></i> Buka Sesi Absensi Kelas</h5>
                <p class="text-muted small mb-0">Pilih pelatihan untuk membuka QR code barcode absensi peserta hari ini.</p>
            </div>
        </div>
        <div class="p-4">
            @if($myTrainings->count() > 0)
                <div class="row g-3">
                    @foreach($myTrainings as $t)
                    <div class="col-12 col-md-6 col-xl-4">
                        <div class="card h-100 border rounded-3 shadow-none p-3 d-flex flex-column justify-content-between" style="transition: all 0.2s ease;">
                            <div>
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span class="badge bg-light text-dark border">{{ $t->kategori->nama ?? 'Umum' }}</span>
                                    @if($t->is_absen_open && $t->absen_token)
                                        <span class="badge bg-success px-2 py-1">
                                            <span class="spinner-grow spinner-grow-sm me-1" style="width: 8px; height: 8px;"></span>
                                            QR Aktif
                                        </span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary px-2 py-1">Ditutup</span>
                                    @endif
                                </div>

                                <h6 class="fw-bold mb-1 text-dark text-truncate" title="{{ $t->judul }}">
                                    {{ $t->judul }}
                                </h6>
                                <p class="text-muted small mb-3">
                                    <i class="bi bi-calendar-event me-1"></i> {{ $t->tanggal_mulai ? $t->tanggal_mulai->format('d/m/Y') : '-' }} s/d {{ $t->tanggal_selesai ? $t->tanggal_selesai->format('d/m/Y') : '-' }}
                                    <br>
                                    <i class="bi bi-people me-1"></i> <strong>{{ $t->approved_participants_count }}</strong> Peserta Terdaftar
                                </p>
                            </div>

                            <div class="pt-2 border-top">
                                <a href="{{ route('pemateri.absen.session', $t->id) }}" class="btn {{ $t->is_absen_open ? 'btn-success' : 'btn-primary' }} btn-sm w-100 fw-semibold">
                                    <i class="bi bi-qr-code me-1"></i> 
                                    {{ $t->is_absen_open ? 'Lihat QR Code & Sesi Berjalan' : 'Buka QR Code Absensi' }}
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-journal-x fs-1 d-block mb-2"></i>
                    <p class="fw-semibold mb-1">Belum Ada Pelatihan yang Ditugaskan</p>
                    <p class="small">Admin belum menugaskan pelatihan untuk akun Anda.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- SECTION 2: REKAP RIWAYAT KEHADIRAN PESERTA -->
    <!-- ======================================================== -->
    <div class="panel border-0 shadow-sm">
        <div class="panel-header d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h5 class="section-title"><i class="bi bi-table text-primary"></i> Rekap Riwayat Presensi Peserta</h5>
                <p class="text-muted small mb-0">Daftar kehadiran peserta yang telah mengisi absensi di kelas Anda.</p>
            </div>
            
            <!-- Filter Form -->
            <form action="{{ route('pemateri.absen.index') }}" method="GET" class="d-flex flex-wrap gap-2 align-items-center">
                <input type="date" name="tanggal" class="form-control form-control-sm" value="{{ request('tanggal') }}" style="width: 140px;">
                
                <select name="training_id" class="form-select form-select-sm" style="max-width: 180px;">
                    <option value="">Semua Pelatihan</option>
                    @foreach($myTrainings as $t)
                        <option value="{{ $t->id }}" {{ request('training_id') == $t->id ? 'selected' : '' }}>
                            {{ \Illuminate\Support\Str::limit($t->judul, 25) }}
                        </option>
                    @endforeach
                </select>

                <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari nama peserta..." value="{{ request('search') }}" style="width: 160px;">

                <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search"></i></button>

                @if(request('tanggal') || request('training_id') || request('search'))
                <a href="{{ route('pemateri.absen.index') }}" class="btn btn-outline-secondary btn-sm" title="Reset">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
                @endif
            </form>
        </div>

        <div class="table-responsive">
            @if($absensis->count() > 0)
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="min-width: 220px;">Nama Peserta</th>
                        <th style="min-width: 180px;">Pelatihan</th>
                        <th>Tanggal</th>
                        <th>Jam Masuk</th>
                        <th>Status</th>
                        <th>Metode</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($absensis as $absen)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 0.75rem; font-weight: bold;">
                                    {{ strtoupper(substr($absen->user->nama ?? $absen->user->name ?? 'P', 0, 2)) }}
                                </div>
                                <div>
                                    <span class="fw-semibold text-dark d-block">{{ $absen->user->nama ?? $absen->user->name ?? 'Peserta' }}</span>
                                    <small class="text-muted font-monospace">{{ $absen->user->nik ?? $absen->user->email }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="small fw-semibold text-dark">{{ $absen->training->judul ?? '-' }}</span>
                        </td>
                        <td>
                            <span class="small text-muted">{{ $absen->tanggal ? $absen->tanggal->format('d/m/Y') : '-' }}</span>
                        </td>
                        <td>
                            <span class="small fw-semibold">{{ $absen->jam_masuk ? date('H:i:s', strtotime($absen->jam_masuk)) : ($absen->waktu_checkin ? $absen->waktu_checkin->format('H:i:s') : '-') }}</span>
                        </td>
                        <td>
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1" style="border-radius: 20px;">
                                <i class="bi bi-check-circle-fill me-1" style="font-size: 7px;"></i> {{ ucfirst($absen->status) }}
                            </span>
                        </td>
                        <td>
                            <small class="text-muted">
                                <i class="bi {{ str_contains($absen->metode ?? '', 'QR') ? 'bi-qr-code text-primary' : 'bi-person-check text-secondary' }} me-1"></i>
                                {{ $absen->metode ?? 'QR Code' }}
                            </small>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="text-center py-5 text-muted">
                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                <p class="mb-0">Belum ada riwayat data presensi untuk filter ini.</p>
            </div>
            @endif
        </div>

        @if($absensis->hasPages())
        <div class="p-3 border-top">
            {{ $absensis->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
