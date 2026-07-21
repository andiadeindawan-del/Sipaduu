@extends('layouts.admin')

@section('title', 'Laporan')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-bar-chart-line"></i></span>
        <div>
            <p class="eyebrow">Analytics</p>
            <h1 class="h3 mb-0">Laporan</h1>
        </div>
    </div>
    <div class="heading-actions">
        <!-- PERBAIKAN: admin.reports.export → admin.laporan.export -->
        <a href="{{ route('admin.laporan.export', 'all') }}" class="btn btn-success btn-sm">
            <i class="bi bi-download"></i> Export Semua
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
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

    <!-- Summary Stats -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-primary">
                <div class="metric-top">
                    <span class="metric-label">Total Pelatihan</span>
                    <span class="metric-icon"><i class="bi bi-journal-bookmark"></i></span>
                </div>
                <div class="metric-value">{{ $totalTrainings ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-success">+{{ $trainingGrowth ?? 0 }}%</span>
                    <span>bulan ini</span>
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
                    <span class="text-success">+{{ $participantGrowth ?? 0 }}%</span>
                    <span>bulan ini</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-warning">
                <div class="metric-top">
                    <span class="metric-label">Total Sertifikat</span>
                    <span class="metric-icon"><i class="bi bi-award"></i></span>
                </div>
                <div class="metric-value">{{ $totalCertificates ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-warning">+{{ $certificateGrowth ?? 0 }}%</span>
                    <span>bulan ini</span>
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
                    <span class="text-info">+{{ $quizGrowth ?? 0 }}%</span>
                    <span>bulan ini</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="panel mb-3">
        <div class="panel-header">
            <div>
                <h5 class="section-title"><i class="bi bi-funnel"></i> Filter Laporan</h5>
                <p class="text-muted small mb-0">Filter data berdasarkan periode dan jenis laporan.</p>
            </div>
            <!-- SUDAH BENAR: route admin.laporan.index -->
            <form action="{{ route('admin.laporan.index') }}" method="GET" class="d-flex gap-2 flex-wrap align-items-center">
                <select class="form-select form-select-sm" name="type" style="width: 150px;">
                    <option value="all" {{ request('type') == 'all' ? 'selected' : '' }}>📊 Semua</option>
                    <option value="trainings" {{ request('type') == 'trainings' ? 'selected' : '' }}>📚 Pelatihan</option>
                    <option value="participants" {{ request('type') == 'participants' ? 'selected' : '' }}>👥 Peserta</option>
                    <option value="certificates" {{ request('type') == 'certificates' ? 'selected' : '' }}>🏆 Sertifikat</option>
                    <option value="registrations" {{ request('type') == 'registrations' ? 'selected' : '' }}>📝 Pendaftaran</option>
                    <option value="materi" {{ request('type') == 'materi' ? 'selected' : '' }}>📖 Materi</option>
                    <option value="quiz" {{ request('type') == 'quiz' ? 'selected' : '' }}>❓ Quiz</option>
                </select>
                <input type="date" class="form-control form-control-sm" name="date_from" value="{{ request('date_from') }}" style="width: 150px;" placeholder="Dari">
                <input type="date" class="form-control form-control-sm" name="date_to" value="{{ request('date_to') }}" style="width: 150px;" placeholder="Sampai">
                <button type="submit" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-search"></i> Filter
                </button>
                <!-- SUDAH BENAR: route admin.laporan.index -->
                <a href="{{ route('admin.laporan.index') }}" class="btn btn-outline-secondary btn-sm" title="Reset Filter">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                </a>
            </form>
        </div>
        @if(request('type') || request('date_from') || request('date_to'))
        <div class="p-2 px-3 bg-light border-top">
            <small class="text-muted">
                <i class="bi bi-filter-circle me-1"></i>
                Filter aktif: 
                @if(request('type'))
                    <span class="badge text-bg-primary">Jenis: {{ ucfirst(request('type')) }}</span>
                @endif
                @if(request('date_from'))
                    <span class="badge text-bg-primary">Dari: {{ request('date_from') }}</span>
                @endif
                @if(request('date_to'))
                    <span class="badge text-bg-primary">Sampai: {{ request('date_to') }}</span>
                @endif
                <!-- SUDAH BENAR: route admin.laporan.index -->
                <a href="{{ route('admin.laporan.index') }}" class="text-danger ms-2">
                    <i class="bi bi-x-circle"></i> Hapus semua filter
                </a>
            </small>
        </div>
        @endif
    </div>

    <!-- Report Tabs -->
    <div class="panel mb-3">
        <div class="panel-header">
            <ul class="nav nav-tabs card-header-tabs" id="reportTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="tab-trainings" data-bs-toggle="tab" data-bs-target="#trainings" type="button" role="tab">
                        <i class="bi bi-journal-bookmark me-1"></i> Pelatihan
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-participants" data-bs-toggle="tab" data-bs-target="#participants" type="button" role="tab">
                        <i class="bi bi-people me-1"></i> Peserta
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-certificates" data-bs-toggle="tab" data-bs-target="#certificates" type="button" role="tab">
                        <i class="bi bi-award me-1"></i> Sertifikat
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-registrations" data-bs-toggle="tab" data-bs-target="#registrations" type="button" role="tab">
                        <i class="bi bi-clipboard-check me-1"></i> Pendaftaran
                    </button>
                </li>
            </ul>
        </div>
        <div class="tab-content p-0">
            <!-- Tab: Pelatihan -->
            <div class="tab-pane fade show active" id="trainings" role="tabpanel">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Judul Pelatihan</th>
                                <th>Kategori</th>
                                <th>Peserta</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($trainings ?? [] as $index => $training)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $training->judul }}</td>
                                <td>{{ $training->kategori->nama ?? '-' }}</td>
                                <td>{{ $training->participants_count ?? 0 }}</td>
                                <td>{{ $training->tanggal_mulai ? $training->tanggal_mulai->format('d/m/Y') : '-' }}</td>
                                <td>
                                    <span class="badge {{ $training->status == 'published' ? 'text-bg-success' : 'text-bg-secondary' }}">
                                        {{ ucfirst($training->status ?? 'Draft') }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Tidak ada data pelatihan</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tab: Peserta -->
            <div class="tab-pane fade" id="participants" role="tabpanel">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Pelatihan Diikuti</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($participants ?? [] as $index => $participant)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $participant->nama ?? $participant->name }}</td>
                                <td>{{ $participant->email }}</td>
                                <td>
                                    <span class="badge {{ $participant->role == 'admin' ? 'text-bg-danger' : ($participant->role == 'trainer' ? 'text-bg-info' : 'text-bg-secondary') }}">
                                        {{ ucfirst($participant->role ?? 'User') }}
                                    </span>
                                </td>
                                <td>{{ $participant->trainings_count ?? 0 }}</td>
                                <td>
                                    <span class="badge {{ ($participant->status ?? 'aktif') == 'aktif' ? 'text-bg-success' : 'text-bg-secondary' }}">
                                        {{ ucfirst($participant->status ?? 'Active') }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Tidak ada data peserta</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tab: Sertifikat -->
            <div class="tab-pane fade" id="certificates" role="tabpanel">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nomor Sertifikat</th>
                                <th>Nama Sertifikat</th>
                                <th>Peserta</th>
                                <th>Tanggal Terbit</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($certificates ?? [] as $index => $certificate)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $certificate->nomor_sertifikat }}</td>
                                <td>{{ $certificate->nama_sertifikat }}</td>
                                <td>{{ $certificate->user->nama ?? $certificate->user->name ?? '-' }}</td>
                                <td>{{ $certificate->tanggal_terbit ? $certificate->tanggal_terbit->format('d/m/Y') : '-' }}</td>
                                <td>
                                    <span class="badge {{ $certificate->status == 'aktif' ? 'text-bg-success' : 'text-bg-secondary' }}">
                                        {{ ucfirst($certificate->status ?? 'Active') }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Tidak ada data sertifikat</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tab: Pendaftaran -->
            <div class="tab-pane fade" id="registrations" role="tabpanel">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Peserta</th>
                                <th>Pelatihan</th>
                                <th>Tanggal Daftar</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($registrations ?? [] as $index => $registration)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $registration->user->nama ?? $registration->user->name ?? '-' }}</td>
                                <td>{{ $registration->training->judul ?? '-' }}</td>
                                <td>{{ $registration->created_at ? $registration->created_at->format('d/m/Y') : '-' }}</td>
                                <td>
                                    <span class="badge {{ $registration->status == 'approved' ? 'text-bg-success' : ($registration->status == 'pending' ? 'text-bg-warning' : 'text-bg-secondary') }}">
                                        {{ ucfirst($registration->status ?? 'Pending') }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Tidak ada data pendaftaran</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="row g-3">
        <div class="col-12 col-xl-6">
            <div class="panel">
                <div class="panel-header">
                    <div>
                        <h5 class="section-title"><i class="bi bi-graph-up-arrow"></i> Tren Pertumbuhan</h5>
                        <p class="text-muted small mb-0">Data pertumbuhan 6 bulan terakhir.</p>
                    </div>
                </div>
                <div class="p-4">
                    <div class="chart-container" style="height: 250px;">
                        <canvas id="growthChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-6">
            <div class="panel">
                <div class="panel-header">
                    <div>
                        <h5 class="section-title"><i class="bi bi-pie-chart"></i> Distribusi Data</h5>
                        <p class="text-muted small mb-0">Komposisi data secara keseluruhan.</p>
                    </div>
                </div>
                <div class="p-4">
                    <div class="chart-container" style="height: 250px;">
                        <canvas id="distributionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Buttons -->
    <div class="panel mt-3">
        <div class="panel-header">
            <div>
                <h5 class="section-title"><i class="bi bi-download"></i> Export Laporan</h5>
                <p class="text-muted small mb-0">Download laporan dalam berbagai format.</p>
            </div>
        </div>
        <div class="p-4">
            <div class="d-flex gap-2 flex-wrap">
                <!-- PERBAIKAN: Semua admin.reports.export → admin.laporan.export -->
                <a href="{{ route('admin.laporan.export', 'trainings') }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-file-spreadsheet me-1"></i> Export Pelatihan
                </a>
                <a href="{{ route('admin.laporan.export', 'participants') }}" class="btn btn-outline-success btn-sm">
                    <i class="bi bi-file-spreadsheet me-1"></i> Export Peserta
                </a>
                <a href="{{ route('admin.laporan.export', 'certificates') }}" class="btn btn-outline-warning btn-sm">
                    <i class="bi bi-file-spreadsheet me-1"></i> Export Sertifikat
                </a>
                <a href="{{ route('admin.laporan.export', 'registrations') }}" class="btn btn-outline-info btn-sm">
                    <i class="bi bi-file-spreadsheet me-1"></i> Export Pendaftaran
                </a>
                <a href="{{ route('admin.laporan.export', 'all') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-file-zip me-1"></i> Export Semua
                </a>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .avatar-text {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.85rem;
    }
    .avatar-img {
        width: 36px;
        height: 36px;
        object-fit: cover;
    }
    .panel {
        background: #fff;
        border-radius: 0.75rem;
        box-shadow: 0 1px 4px rgba(0,0,0,.06);
        overflow: hidden;
    }
    .panel-header {
        padding: 1rem 1.25rem;
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
        color: var(--primary);
    }
    .chart-container {
        position: relative;
        width: 100%;
    }
    .nav-tabs .nav-link {
        color: #6c757d;
        border: none;
        border-bottom: 2px solid transparent;
        padding: 0.6rem 1rem;
        font-weight: 500;
    }
    .nav-tabs .nav-link:hover {
        border-color: transparent;
        color: var(--primary);
    }
    .nav-tabs .nav-link.active {
        color: var(--primary);
        background: transparent;
        border-bottom-color: var(--primary);
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ============================================================
    // GROWTH CHART
    // ============================================================
    const growthCtx = document.getElementById('growthChart');
    if (growthCtx) {
        new Chart(growthCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                datasets: [
                    {
                        label: 'Pelatihan',
                        data: {!! json_encode($chartData['trainings'] ?? [10, 15, 20, 25, 30, 35]) !!},
                        borderColor: '#4e9af1',
                        backgroundColor: 'rgba(78, 154, 241, 0.1)',
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Peserta',
                        data: {!! json_encode($chartData['participants'] ?? [50, 80, 120, 150, 200, 250]) !!},
                        borderColor: '#28c76f',
                        backgroundColor: 'rgba(40, 199, 111, 0.1)',
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Sertifikat',
                        data: {!! json_encode($chartData['certificates'] ?? [5, 12, 18, 25, 30, 40]) !!},
                        borderColor: '#ff9f43',
                        backgroundColor: 'rgba(255, 159, 67, 0.1)',
                        fill: true,
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle',
                            padding: 20
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    // ============================================================
    // DISTRIBUTION CHART
    // ============================================================
    const distributionCtx = document.getElementById('distributionChart');
    if (distributionCtx) {
        new Chart(distributionCtx, {
            type: 'doughnut',
            data: {
                labels: ['Pelatihan', 'Peserta', 'Sertifikat', 'Materi', 'Quiz'],
                datasets: [{
                    data: [
                        {{ $chartData['totalTrainings'] ?? 10 }},
                        {{ $chartData['totalParticipants'] ?? 50 }},
                        {{ $chartData['totalCertificates'] ?? 20 }},
                        {{ $chartData['totalMateri'] ?? 15 }},
                        {{ $chartData['totalQuizzes'] ?? 8 }}
                    ],
                    backgroundColor: [
                        '#4e9af1',
                        '#28c76f',
                        '#ff9f43',
                        '#ea5455',
                        '#17a2b8'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle',
                            padding: 20
                        }
                    }
                },
                cutout: '60%'
            }
        });
    }

    // ============================================================
    // AUTO CLOSE ALERTS
    // ============================================================
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