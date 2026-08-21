@extends('layouts.admin')

@section('title', 'Laporan Sertifikat')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-award"></i></span>
        <div>
            <p class="eyebrow">Laporan</p>
            <h1 class="h3 mb-0">Data Sertifikat dan statistik</h1>
        </div>
    </div>
    <div class="heading-actions">
        <a href="{{ route('admin.laporan.export', 'certificates') }}" class="btn btn-success btn-sm">
            <i class="bi bi-download"></i> Export CSV
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
                    <span class="metric-label">Total Sertifikat</span>
                    <span class="metric-icon"><i class="bi bi-award"></i></span>
                </div>
                <div class="metric-value">{{ $totalCertificates ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-primary">Diterbitkan</span>
                    <span>semua</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-success">
                <div class="metric-top">
                    <span class="metric-label">Aktif</span>
                    <span class="metric-icon"><i class="bi bi-check-circle"></i></span>
                </div>
                <div class="metric-value">{{ $activeCertificates ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-success">Aktif</span>
                    <span>sertifikat</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-warning">
                <div class="metric-top">
                    <span class="metric-label">Kadaluarsa</span>
                    <span class="metric-icon"><i class="bi bi-clock-history"></i></span>
                </div>
                <div class="metric-value">{{ $expiredCertificates ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-warning">Kadaluarsa</span>
                    <span>sertifikat</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-info">
                <div class="metric-top">
                    <span class="metric-label">Bulan Ini</span>
                    <span class="metric-icon"><i class="bi bi-calendar"></i></span>
                </div>
                <div class="metric-value">{{ $certificatesThisMonth ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-info">Diterbitkan</span>
                    <span>bulan ini</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="panel mb-3">
        <div class="panel-header">
            <div>
                <h5 class="section-title"><i class="bi bi-funnel"></i> Filter Sertifikat</h5>
                <p class="text-muted small mb-0">Filter data sertifikat berdasarkan kriteria tertentu.</p>
            </div>
        </div>
        <div class="p-3">
            <form action="{{ route('admin.laporan.certificates') }}" method="GET" class="row g-3">
                <div class="col-12 col-md-3">
                    <label class="form-label fw-semibold">Status</label>
                    <select class="form-select" name="status">
                        <option value="">Semua Status</option>
                        <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Kadaluarsa</option>
                        <option value="revoked" {{ request('status') == 'revoked' ? 'selected' : '' }}>Dicabut</option>
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label fw-semibold">Pelatihan</label>
                    <select class="form-select" name="training_id">
                        <option value="">Semua Pelatihan</option>
                        @foreach($trainings ?? [] as $training)
                            <option value="{{ $training->id }}" {{ request('training_id') == $training->id ? 'selected' : '' }}>
                                {{ $training->judul }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label fw-semibold">Tanggal Terbit (Dari)</label>
                    <input type="date" class="form-control" name="date_from" 
                           value="{{ request('date_from') }}" placeholder="Dari">
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label fw-semibold">Tanggal Terbit (Sampai)</label>
                    <input type="date" class="form-control" name="date_to" 
                           value="{{ request('date_to') }}" placeholder="Sampai">
                </div>
                <div class="col-12">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> Filter
                        </button>
                        <a href="{{ route('admin.laporan.certificates') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-counterclockwise"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
        @if(request('status') || request('training_id') || request('date_from') || request('date_to'))
        <div class="p-2 px-3 bg-light border-top">
            <small class="text-muted">
                <i class="bi bi-filter-circle me-1"></i>
                Filter aktif: 
                @if(request('status'))
                    <span class="badge text-bg-primary">Status: {{ ucfirst(request('status')) }}</span>
                @endif
                @if(request('training_id'))
                    <span class="badge text-bg-primary">Pelatihan: {{ $trainings->find(request('training_id'))->judul ?? '-' }}</span>
                @endif
                @if(request('date_from'))
                    <span class="badge text-bg-primary">Dari: {{ request('date_from') }}</span>
                @endif
                @if(request('date_to'))
                    <span class="badge text-bg-primary">Sampai: {{ request('date_to') }}</span>
                @endif
                <a href="{{ route('admin.laporan.certificates') }}" class="text-danger ms-2">
                    <i class="bi bi-x-circle"></i> Hapus filter
                </a>
            </small>
        </div>
        @endif
    </div>

    <!-- Table Certificates -->
    <div class="panel">
        <div class="panel-header">
            <div>
                <h5 class="section-title"><i class="bi bi-table"></i> Daftar Sertifikat</h5>
                <p class="text-muted small mb-0">Menampilkan {{ $certificates->firstItem() ?? 0 }} - {{ $certificates->lastItem() ?? 0 }} dari {{ $certificates->total() ?? 0 }} sertifikat</p>
            </div>
        </div>
        <div class="table-responsive">
            @if($certificates->count() > 0)
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Nomor Sertifikat</th>
                        <th>Peserta</th>
                        <th>Pelatihan</th>
                        <th>Tanggal Terbit</th>
                        <th>Kadaluarsa</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($certificates as $index => $certificate)
                    <tr>
                        <td>{{ $certificates->firstItem() + $index }}</td>
                        <td>
                            <span class="fw-semibold">{{ $certificate->nomor_sertifikat }}</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if($certificate->user && $certificate->user->avatar)
                                <img src="{{ Storage::url($certificate->user->avatar) }}" 
                                     alt="{{ $certificate->user->nama ?? $certificate->user->name }}" 
                                     class="rounded-circle" width="32" height="32" style="object-fit: cover;">
                                @else
                                <div class="avatar-text rounded-circle bg-light text-dark">
                                    {{ strtoupper(substr($certificate->user->nama ?? $certificate->user->name ?? '?', 0, 1)) }}
                                </div>
                                @endif
                                <div>
                                    <p class="fw-semibold mb-0">{{ $certificate->user->nama ?? $certificate->user->name ?? '-' }}</p>
                                    <small class="text-muted">{{ $certificate->user->email ?? '-' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>{{ $certificate->training->judul ?? '-' }}</td>
                        <td>{{ $certificate->tanggal_terbit ? $certificate->tanggal_terbit->format('d/m/Y') : '-' }}</td>
                        <td>{{ $certificate->tanggal_kadaluarsa ? $certificate->tanggal_kadaluarsa->format('d/m/Y') : '-' }}</td>
                        <td>
                            @php
                                $status = $certificate->status ?? 'aktif';
                                $badgeClass = $status == 'aktif' ? 'text-bg-success' : 
                                              ($status == 'expired' ? 'text-bg-danger' : 
                                              ($status == 'revoked' ? 'text-bg-secondary' : 'text-bg-warning'));
                            @endphp
                            <span class="badge {{ $badgeClass }}">
                                {{ ucfirst($status) }}
                            </span>
                        </td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('admin.sertifikat.show', $certificate->id) }}" 
                                   class="btn btn-info" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.sertifikat.download', $certificate->id) }}" 
                                   class="btn btn-primary" title="Download">
                                    <i class="bi bi-download"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="text-center py-5">
                <div class="text-muted">
                    <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                    <p class="h5">Tidak ada data sertifikat</p>
                    <p class="small">Belum ada sertifikat yang diterbitkan.</p>
                </div>
            </div>
            @endif
        </div>
        @if($certificates->hasPages())
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mt-3 px-3 pb-3">
            <p class="text-muted small mb-0">
                Menampilkan {{ $certificates->firstItem() ?? 0 }} sampai {{ $certificates->lastItem() ?? 0 }} 
                dari {{ $certificates->total() ?? 0 }} sertifikat
            </p>
            <nav aria-label="Sertifikat pagination">
                {{ $certificates->appends(request()->query())->links() }}
            </nav>
        </div>
        @endif
    </div>

    <!-- Chart Section -->
    <div class="row g-3 mt-2">
        <div class="col-12 col-xl-6">
            <div class="panel">
                <div class="panel-header">
                    <div>
                        <h5 class="section-title"><i class="bi bi-graph-up-arrow"></i> Tren Sertifikat</h5>
                        <p class="text-muted small mb-0">Data sertifikat per bulan.</p>
                    </div>
                </div>
                <div class="p-4">
                    <div class="chart-container" style="height: 250px;">
                        <canvas id="certificateChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-6">
            <div class="panel">
                <div class="panel-header">
                    <div>
                        <h5 class="section-title"><i class="bi bi-pie-chart"></i> Distribusi Status</h5>
                        <p class="text-muted small mb-0">Komposisi status sertifikat.</p>
                    </div>
                </div>
                <div class="p-4">
                    <div class="chart-container" style="height: 250px;">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
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
        font-size: 0.75rem;
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
    .table th {
        font-weight: 600;
        color: #6c757d;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #f0f0f0;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ============================================================
    // CERTIFICATE CHART (Line)
    // ============================================================
    const certCtx = document.getElementById('certificateChart');
    if (certCtx) {
        new Chart(certCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartLabels ?? ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun']) !!},
                datasets: [{
                    label: 'Sertifikat Diterbitkan',
                    data: {!! json_encode($chartData ?? [0, 0, 0, 0, 0, 0]) !!},
                    borderColor: '#ff9f43',
                    backgroundColor: 'rgba(255, 159, 67, 0.1)',
                    fill: true,
                    tension: 0.4
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
    // STATUS CHART (Doughnut)
    // ============================================================
    const statusCtx = document.getElementById('statusChart');
    if (statusCtx) {
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Aktif', 'Kadaluarsa', 'Dicabut'],
                datasets: [{
                    data: [
                        {{ $activeCertificates ?? 0 }},
                        {{ $expiredCertificates ?? 0 }},
                        {{ $revokedCertificates ?? 0 }}
                    ],
                    backgroundColor: [
                        '#28c76f',
                        '#ea5455',
                        '#6c757d'
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