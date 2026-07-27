@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <!-- ========================================================== -->
    <!-- PAGE HEADING -->
    <!-- ========================================================== -->
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center rounded-3" style="width: 50px; height: 50px;">
                <i class="bi bi-grid-1x2 fs-3"></i>
            </div>
            <div>
                <h1 class="h3 mb-0">Dashboard</h1>
                <p class="text-muted small mb-0">Selamat datang kembali, {{ auth()->user()->nama ?? auth()->user()->name }}!</p>
            </div>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-download me-1"></i> Export
            </button>
            <button class="btn btn-primary btn-sm">
                <i class="bi bi-file-earmark-plus me-1"></i> Create Report
            </button>
        </div>
    </div>

    <!-- ========================================================== -->
    <!-- STATISTICS CARDS -->
    <!-- ========================================================== -->
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
                    <span class="metric-label">Total Peserta</span>
                    <span class="metric-icon"><i class="bi bi-people"></i></span>
                </div>
                <div class="metric-value">{{ $totalParticipants ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-success">Terdaftar</span>
                    <span>aktif</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-warning">
                <div class="metric-top">
                    <span class="metric-label">Total Materi</span>
                    <span class="metric-icon"><i class="bi bi-book"></i></span>
                </div>
                <div class="metric-value">{{ $totalMateri ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-warning">Semua</span>
                    <span>materi</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-info">
                <div class="metric-top">
                    <span class="metric-label">Total Quiz</span>
                    <span class="metric-icon"><i class="bi bi-question-circle"></i></span>
                </div>
                <div class="metric-value">{{ $totalQuizzes ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-info">Semua</span>
                    <span>quiz</span>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================================== -->
    <!-- SECOND ROW STATISTICS -->
    <!-- ========================================================== -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-danger">
                <div class="metric-top">
                    <span class="metric-label">Sertifikat Diterbitkan</span>
                    <span class="metric-icon"><i class="bi bi-award"></i></span>
                </div>
                <div class="metric-value">{{ $totalCertificates ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-danger">Total</span>
                    <span>sertifikat</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-primary">
                <div class="metric-top">
                    <span class="metric-label">Kategori</span>
                    <span class="metric-icon"><i class="bi bi-tags"></i></span>
                </div>
                <div class="metric-value">{{ $totalCategories ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-primary">Total</span>
                    <span>kategori</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-success">
                <div class="metric-top">
                    <span class="metric-label">Training Aktif</span>
                    <span class="metric-icon"><i class="bi bi-play-circle"></i></span>
                </div>
                <div class="metric-value">{{ $ongoingTrainings ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-success">Sedang</span>
                    <span>berjalan</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-warning">
                <div class="metric-top">
                    <span class="metric-label">Quiz Dikerjakan</span>
                    <span class="metric-icon"><i class="bi bi-clock-history"></i></span>
                </div>
                <div class="metric-value">{{ $totalQuizAttempts ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-warning">Total</span>
                    <span>pengerjaan</span>
                </div>
            </div>
        </div>
    </div>

<!-- ========================================================== -->
<!-- PENDAFTARAN MENUNGGU KONFIRMASI -->
<!-- ========================================================== -->
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="panel">
            <div class="panel-header">
                <div>
                    <h5 class="section-title">
                        <i class="bi bi-person-check text-warning"></i> 
                        Pendaftaran Menunggu Konfirmasi
                        @if(isset($pendingRegistrations) && $pendingRegistrations->count() > 0)
                        <span class="badge bg-danger ms-2">{{ $pendingRegistrations->count() }}</span>
                        @endif
                    </h5>
                    <p class="text-muted small mb-0">Daftar peserta yang mendaftar dan menunggu persetujuan</p>
                </div>
                <a href="{{ route('admin.pendaftaran.index') }}" class="btn btn-sm btn-outline-primary">
                    Kelola Semua <i class="bi bi-chevron-right"></i>
                </a>
            </div>
            <div class="table-responsive">
                @if(isset($pendingRegistrations) && $pendingRegistrations->count() > 0)
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Peserta</th>
                            <th>Pelatihan</th>
                            <th>Tanggal Daftar</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingRegistrations as $index => $registration)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-text avatar-sm bg-primary text-white d-flex align-items-center justify-content-center rounded-circle">
                                        {{ strtoupper(substr($registration->user->nama ?? $registration->user->name ?? 'U', 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="fw-semibold mb-0">{{ $registration->user->nama ?? $registration->user->name ?? 'Unknown' }}</p>
                                        <p class="text-muted small mb-0">{{ $registration->user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="fw-semibold">{{ $registration->training->judul ?? '-' }}</span>
                                <br>
                                <small class="text-muted">
                                    {{ $registration->training->tanggal_mulai ? $registration->training->tanggal_mulai->format('d/m/Y') : '-' }}
                                </small>
                            </td>
                            <td>
                                <span class="text-muted">
                                    {{ $registration->created_at ? $registration->created_at->format('d/m/Y H:i') : '-' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-warning">
                                    <i class="bi bi-clock me-1"></i> Pending
                                </span>
                            </td>
                           <td class="text-end">
    <div class="btn-group btn-group-sm" role="group">
        @if($registration->status == 'pending')
            <form action="{{ url('/admin/pendaftaran/' . $registration->id . '/approve') }}" method="POST" class="d-inline">
                @csrf
                @method('PUT')
                <button type="submit" class="btn btn-success" title="Setujui" onclick="return confirm('Setujui pendaftaran ini?')">
                    <i class="bi bi-check-circle"></i> Setuju
                </button>
            </form>
            <form action="{{ url('/admin/pendaftaran/' . $registration->id . '/reject') }}" method="POST" class="d-inline">
                @csrf
                @method('PUT')
                <button type="submit" class="btn btn-danger" title="Tolak" onclick="return confirm('Yakin ingin menolak pendaftaran ini?')">
                    <i class="bi bi-x-circle"></i> Tolak
                </button>
            </form>
        @endif
        @if($registration->status == 'disetujui' || $registration->status == 'pending')
            <form action="{{ url('/admin/pendaftaran/' . $registration->id . '/cancel') }}" method="POST" class="d-inline">
                @csrf
                @method('PUT')
                <button type="submit" class="btn btn-secondary" title="Batalkan" onclick="return confirm('Yakin ingin membatalkan pendaftaran ini?')">
                    <i class="bi bi-ban"></i> Batal
                </button>
            </form>
        @endif
        <a href="{{ url('/admin/pendaftaran/' . $registration->id) }}" class="btn btn-info" title="Detail">
            <i class="bi bi-eye"></i> Detail
        </a>
    </div>
</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="text-center py-4">
                    <i class="bi bi-check-circle fs-1 text-success d-block mb-3"></i>
                    <p class="text-muted">Tidak ada pendaftaran yang menunggu konfirmasi</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

    <!-- ========================================================== -->
    <!-- PELATIHAN BERJALAN & AKTIVITAS TERBARU -->
    <!-- ========================================================== -->
    <div class="row g-4 mb-4">
          <!-- Aktivitas Terbaru -->
        <div class="col-12 col-lg-6">
            <div class="panel h-100">
                <div class="panel-header">
                    <div>
                        <h5 class="section-title">
                            <i class="bi bi-clock-history"></i> Aktivitas Terbaru
                        </h5>
                        <p class="text-muted small mb-0">Aktivitas terakhir di sistem</p>
                    </div>
                </div>
                <div class="p-3 activity-list">
                    @if(isset($recentActivities) && $recentActivities->count() > 0)
                        @foreach($recentActivities as $activity)
                        <div class="d-flex gap-3 mb-3 pb-3 border-bottom">
                            <div class="flex-shrink-0">
                                <div class="bg-{{ $activity['color'] ?? 'primary' }} bg-opacity-10 text-{{ $activity['color'] ?? 'primary' }} rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="bi {{ $activity['icon'] ?? 'bi-bell' }}"></i>
                                </div>
                            </div>
                            <div>
                                <p class="fw-semibold mb-1">{{ $activity['title'] }}</p>
                                <p class="text-muted small mb-0">{{ $activity['description'] }}</p>
                                <span class="text-muted small">{{ $activity['time'] }}</span>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                            <p class="text-muted">Belum ada aktivitas</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <!-- Pelatihan Berjalan -->
        <div class="col-12 col-lg-6">
            <div class="panel">
                <div class="panel-header">
                    <div>
                        <h5 class="section-title">
                            <i class="bi bi-journal-bookmark"></i> Pelatihan Berjalan
                        </h5>
                        <p class="text-muted small mb-0">Pelatihan yang sedang aktif</p>
                    </div>
                    <a href="{{ route('admin.trainings.index') }}" class="btn btn-sm btn-outline-primary">
                        Lihat Semua <i class="bi bi-chevron-right"></i>
                    </a>
                </div>
                <div class="p-3">
                    @if(isset($ongoingTrainingsList) && $ongoingTrainingsList->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($ongoingTrainingsList as $training)
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
                                        <span class="badge bg-success">Berjalan</span>
                                    </div>
                                    <a href="{{ route('admin.trainings.show', $training->id) }}" class="btn btn-sm btn-primary">
                                        Lihat <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                            <p class="text-muted">Tidak ada pelatihan yang sedang berjalan</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

    <!-- ========================================================== -->
    <!-- RINGKASAN HARI INI & STATISTIK PENDAFTARAN -->
    <!-- ========================================================== -->
    <div class="row g-4 mb-4">
        <!-- Ringkasan Hari Ini -->
        <div class="col-12 col-lg-6">
            <div class="panel">
                <div class="panel-header">
                    <div>
                        <h5 class="section-title">
                            <i class="bi bi-calendar-day"></i> Ringkasan Hari Ini
                        </h5>
                        <p class="text-muted small mb-0">{{ now()->format('d F Y') }}</p>
                    </div>
                </div>
                <div class="p-3">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="text-center p-3 border rounded bg-light">
                                <h6 class="text-muted small">Pendaftaran</h6>
                                <h3 class="mb-0">{{ $todayRegistrations ?? 0 }}</h3>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 border rounded bg-light">
                                <h6 class="text-muted small">Kehadiran</h6>
                                <h3 class="mb-0">{{ $todayAttendance ?? 0 }}</h3>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 border rounded bg-light">
                                <h6 class="text-muted small">Materi</h6>
                                <h3 class="mb-0">{{ $todayMaterials ?? 0 }}</h3>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 border rounded bg-light">
                                <h6 class="text-muted small">Sertifikat</h6>
                                <h3 class="mb-0">{{ $todayCertificates ?? 0 }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistik Pendaftaran -->
        <div class="col-12 col-lg-6">
            <div class="panel h-100">
                <div class="panel-header">
                    <div>
                        <h5 class="section-title">
                            <i class="bi bi-bar-chart"></i> Statistik Pendaftaran (6 Bulan Terakhir)
                        </h5>
                    </div>
                </div>
                <div class="p-3">
                    <!-- Chart Bars -->
                    <div class="chart-bars" style="height: 200px;">
                        <div class="chart-column bar-42"><span></span><small>Jan</small></div>
                        <div class="chart-column bar-58"><span></span><small>Feb</small></div>
                        <div class="chart-column bar-51"><span></span><small>Mar</small></div>
                        <div class="chart-column bar-72"><span></span><small>Apr</small></div>
                        <div class="chart-column bar-66"><span></span><small>Mei</small></div>
                        <div class="chart-column bar-83"><span></span><small>Jun</small></div>
                    </div>

                    <!-- Legend -->
                    <div class="d-flex justify-content-center gap-4 mt-3">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-success" style="width: 12px; height: 12px; padding: 0;"></span>
                            <span class="small">Berlangsung (33.3%)</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-warning" style="width: 12px; height: 12px; padding: 0;"></span>
                            <span class="small">Seleksi (38.9%)</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-info" style="width: 12px; height: 12px; padding: 0;"></span>
                            <span class="small">Akan Datang (27.8%)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================================== -->
    <!-- RECENT USERS TABLE -->
    <!-- ========================================================== -->
    <div class="panel">
        <div class="panel-header">
            <div>
                <h5 class="section-title">
                    <i class="bi bi-people"></i> Pengguna Terbaru
                </h5>
                <p class="text-muted small mb-0">Aktivitas akun terbaru di sistem</p>
            </div>
            <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-primary">
                Kelola Pengguna <i class="bi bi-chevron-right"></i>
            </a>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Pengguna</th>
                        <th>Role</th>
                        <th>Usaha</th>
                        <th>Status</th>
                        <th>Bergabung</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentUsers ?? [] as $user)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if($user->foto)
                                <img class="avatar-img avatar-sm" src="{{ asset('storage/' . $user->foto) }}" alt="{{ $user->nama }}">
                                @else
                                <div class="avatar-text avatar-sm bg-primary text-white d-flex align-items-center justify-content-center rounded-circle">
                                    {{ strtoupper(substr($user->nama ?? $user->name ?? 'U', 0, 2)) }}
                                </div>
                                @endif
                                <div>
                                    <p class="fw-semibold mb-0">{{ $user->nama ?? $user->name ?? 'Unknown' }}</p>
                                    <p class="text-muted small mb-0">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge {{ $user->role == 'admin' ? 'text-bg-danger' : ($user->role == 'trainer' ? 'text-bg-info' : 'text-bg-secondary') }}">
                                {{ ucfirst($user->role ?? 'User') }}
                            </span>
                        </td>
                        <td>{{ $user->nama_usaha ?? '-' }}</td>
                        <td>
                            @if(($user->status ?? 'aktif') == 'aktif')
                            <span class="badge text-bg-success">
                                <i class="bi bi-circle-fill me-1" style="font-size: 6px;"></i> Aktif
                            </span>
                            @elseif(($user->status ?? '') == 'pending')
                            <span class="badge text-bg-warning">
                                <i class="bi bi-circle-fill me-1" style="font-size: 6px;"></i> Pending
                            </span>
                            @else
                            <span class="badge text-bg-secondary">
                                <i class="bi bi-circle-fill me-1" style="font-size: 6px;"></i> Nonaktif
                            </span>
                            @endif
                        </td>
                        <td>{{ optional($user->created_at)->format('d M Y') ?? '-' }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-light">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <div class="text-muted">
                                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                <p>Belum ada pengguna terdaftar</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('styles')
<style>
    .metric-card {
        background: #fff;
        border-radius: 0.75rem;
        padding: 1.1rem 1.25rem;
        box-shadow: 0 1px 4px rgba(0,0,0,.06);
        border-left: 4px solid transparent;
        height: 100%;
        transition: transform 0.2s ease;
    }
    .metric-card:hover {
        transform: translateY(-4px);
    }
    .metric-primary { border-left-color: #4e9af1; }
    .metric-success { border-left-color: #28c76f; }
    .metric-warning { border-left-color: #ff9f43; }
    .metric-danger { border-left-color: #ea5455; }
    .metric-info { border-left-color: #17a2b8; }
    
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
        color: #c3cad6;
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

    .panel {
        background: #fff;
        border-radius: .75rem;
        box-shadow: 0 1px 4px rgba(0,0,0,.06);
        overflow: hidden;
    }
    .panel-header {
        padding: .9rem 1.25rem;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: .75rem;
    }
    .section-title {
        display: flex;
        align-items: center;
        gap: .5rem;
        margin: 0;
        font-size: 1rem;
    }
    .section-title i {
        color: #4e9af1;
    }

    .chart-bars {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 10px;
        height: 200px;
        padding: 10px 0;
    }
    .chart-column {
        flex: 1;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-end;
        height: 100%;
    }
    .chart-column span {
        display: block;
        width: 100%;
        max-width: 40px;
        background: #4e9af1;
        border-radius: 6px 6px 0 0;
        min-height: 10px;
        transition: height 0.6s ease;
    }
    .chart-column small {
        margin-top: 8px;
        font-size: 0.7rem;
        color: #8a93a3;
    }
    .bar-42 span { height: 42%; background: #4e9af1; }
    .bar-58 span { height: 58%; background: #28c76f; }
    .bar-51 span { height: 51%; background: #ff9f43; }
    .bar-72 span { height: 72%; background: #4e9af1; }
    .bar-66 span { height: 66%; background: #28c76f; }
    .bar-83 span { height: 83%; background: #ff9f43; }

    .avatar-text {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: .8rem;
        color: #fff;
    }

    .activity-list .border-bottom:last-child {
        border-bottom: none !important;
    }

    /* Button hover effects */
    .btn-group .btn:hover {
        transform: scale(1.1);
        transition: transform 0.2s ease;
    }

    @media (max-width: 768px) {
        .chart-bars {
            height: 150px;
        }
        .chart-column span {
            max-width: 30px;
        }
    }
</style>
@endpush
@endsection