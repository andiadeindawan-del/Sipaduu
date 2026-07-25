@extends('layouts.admin')

@section('title', 'Manajemen Absensi')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-clock-history"></i></span>
        <div>
            <p class="eyebrow">Manajemen</p>
            <h1 class="h3 mb-0">Absensi</h1>
        </div>
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

    <!-- Stats -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-primary">
                <div class="metric-top">
                    <span class="metric-label">Total Absensi</span>
                    <span class="metric-icon"><i class="bi bi-people"></i></span>
                </div>
                <div class="metric-value">{{ $totalAbsensi ?? $absensis->total() ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-primary">Keseluruhan</span>
                    <span>absensi</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-success">
                <div class="metric-top">
                    <span class="metric-label">Hadir</span>
                    <span class="metric-icon"><i class="bi bi-check-circle"></i></span>
                </div>
                <div class="metric-value">{{ $hadirCount ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-success">Hadir</span>
                    <span>peserta</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-warning">
                <div class="metric-top">
                    <span class="metric-label">Sakit / Izin</span>
                    <span class="metric-icon"><i class="bi bi-clipboard2-pulse"></i></span>
                </div>
                <div class="metric-value">{{ $sakitIzinCount ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-warning">Sakit/Izin</span>
                    <span>peserta</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-danger">
                <div class="metric-top">
                    <span class="metric-label">Alpa</span>
                    <span class="metric-icon"><i class="bi bi-x-circle"></i></span>
                </div>
                <div class="metric-value">{{ $alpaCount ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-danger">Alpa</span>
                    <span>peserta</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="panel mb-3">
        <div class="panel-header">
            <div>
                <h5 class="section-title"><i class="bi bi-funnel"></i> Filter & Pencarian</h5>
            </div>
            <form action="{{ route('admin.absen.index') }}" method="GET" class="d-flex gap-2 flex-wrap align-items-center">
                <select class="form-select form-select-sm" name="training_id" style="width: 200px;">
                    <option value="">Semua Pelatihan</option>
                    @foreach($trainings ?? [] as $training)
                    <option value="{{ $training->id }}" {{ request('training_id') == $training->id ? 'selected' : '' }}>
                        {{ $training->judul }}
                    </option>
                    @endforeach
                </select>
                <input type="date" class="form-control form-control-sm" name="tanggal" value="{{ request('tanggal') }}" style="width: 150px;">
                <select class="form-select form-select-sm" name="status" style="width: 130px;">
                    <option value="">Semua Status</option>
                    <option value="hadir" {{ request('status') == 'hadir' ? 'selected' : '' }}>✅ Hadir</option>
                    <option value="sakit" {{ request('status') == 'sakit' ? 'selected' : '' }}>🤒 Sakit</option>
                    <option value="izin" {{ request('status') == 'izin' ? 'selected' : '' }}>📝 Izin</option>
                    <option value="alpa" {{ request('status') == 'alpa' ? 'selected' : '' }}>❌ Alpa</option>
                </select>
                <button type="submit" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-search"></i> Filter
                </button>
                <a href="{{ route('admin.absen.index') }}" class="btn btn-outline-secondary btn-sm" title="Reset Filter">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                </a>
            </form>
        </div>
        @if(request('training_id') || request('tanggal') || request('status'))
        <div class="p-2 px-3 bg-light border-top">
            <small class="text-muted">
                <i class="bi bi-filter-circle me-1"></i>
                Filter aktif: 
                @if(request('training_id'))
                    @php
                        $trainingName = $trainings->firstWhere('id', request('training_id'))->judul ?? 'Pelatihan';
                    @endphp
                    <span class="badge text-bg-primary">Pelatihan: {{ $trainingName }}</span>
                @endif
                @if(request('tanggal'))
                    <span class="badge text-bg-primary">Tanggal: {{ request('tanggal') }}</span>
                @endif
                @if(request('status'))
                    <span class="badge text-bg-primary">Status: {{ ucfirst(request('status')) }}</span>
                @endif
                <a href="{{ route('admin.absen.index') }}" class="text-danger ms-2">
                    <i class="bi bi-x-circle"></i> Hapus semua filter
                </a>
            </small>
        </div>
        @endif
    </div>

    <!-- Table -->
    <div class="panel">
        <div class="panel-header">
            <div>
                <h5 class="section-title"><i class="bi bi-table"></i> Daftar Absensi Peserta</h5>
                <p class="text-muted small mb-0">Daftar kehadiran peserta pada setiap pelatihan.</p>
            </div>
            <!-- <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('admin.absen.export') }}" class="btn btn-success btn-sm">
                    <i class="bi bi-download"></i> Export
                </a>
            </div> -->
        </div>
        <div class="table-responsive">
            @if(isset($absensis) && $absensis->count() > 0)
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Peserta</th>
                        <th>Pelatihan</th>
                        <th>Tanggal</th>
                        <th>Jam Masuk</th>
                        <th>Jam Keluar</th>
                        <th>Status</th>
                        <th class="text-end" style="width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($absensis as $index => $absen)
                    <tr>
                        <td>{{ $absensis->firstItem() + $index }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if($absen->user && $absen->user->foto)
                                <img class="avatar-img avatar-sm" src="{{ asset('storage/' . $absen->user->foto) }}" alt="{{ $absen->user->nama ?? $absen->user->name }}">
                                @else
                                <div class="avatar-text avatar-sm bg-primary text-white d-flex align-items-center justify-content-center rounded-circle">
                                    {{ strtoupper(substr($absen->user->nama ?? $absen->user->name ?? 'U', 0, 2)) }}
                                </div>
                                @endif
                                <div>
                                    <p class="fw-semibold mb-0">{{ $absen->user->nama ?? $absen->user->name ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($absen->training)
                            <span class=" ">{{ $absen->training->judul }}</span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <span class=" text-bg-light">
                                {{ $absen->tanggal ? $absen->tanggal->format('d/m/Y') : '-' }}
                            </span>
                        </td>
                        <td>
                            @if($absen->jam_masuk)
                            <span class="badge text-bg-success">
                                <i class="bi bi-clock me-1"></i>
                                {{ date('H:i', strtotime($absen->jam_masuk)) }}
                            </span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($absen->jam_keluar)
                            <span class="badge text-bg-secondary">
                                <i class="bi bi-clock me-1"></i>
                                {{ date('H:i', strtotime($absen->jam_keluar)) }}
                            </span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $statusMap = [
                                    'hadir' => ['label' => '✅ Hadir', 'class' => 'text-bg-success'],
                                    'sakit' => ['label' => '🤒 Sakit', 'class' => 'text-bg-warning'],
                                    'izin' => ['label' => '📝 Izin', 'class' => 'text-bg-info'],
                                    'alpa' => ['label' => '❌ Alpa', 'class' => 'text-bg-danger'],
                                ];
                                $status = $statusMap[$absen->status] ?? ['label' => $absen->status ?? 'Unknown', 'class' => 'text-bg-secondary'];
                            @endphp
                            <span class="badge {{ $status['class'] }}">
                                {{ $status['label'] }}
                            </span>
                            @if($absen->keterangan)
                            <br>
                            <small class="text-muted">{{ Str::limit($absen->keterangan, 20) }}</small>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('admin.absen.show', $absen->id) }}" 
                                   class="btn btn-outline-info" title="Lihat">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <button type="button" class="btn btn-outline-danger" 
                                        data-bs-toggle="modal" data-bs-target="#deleteModal{{ $absen->id }}" 
                                        title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
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
                    <p class="h5">Belum ada data absensi</p>
                    <p class="small">Peserta akan melakukan absensi sendiri melalui dashboard peserta.</p>
                </div>
            </div>
            @endif
        </div>
        @if(isset($absensis) && $absensis->hasPages())
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mt-3 px-3 pb-3">
            <p class="text-muted small mb-0">
                Menampilkan {{ $absensis->firstItem() ?? 0 }} sampai {{ $absensis->lastItem() ?? 0 }} 
                dari {{ $absensis->total() ?? 0 }} absensi
            </p>
            <nav aria-label="Absensi pagination">
                {{ $absensis->links() }}
            </nav>
        </div>
        @endif
    </div>

    <!-- Summary by Training -->
    <div class="panel mt-3">
        <div class="panel-header">
            <div>
                <h5 class="section-title"><i class="bi bi-bar-chart"></i> Rekap Absensi per Pelatihan</h5>
                <p class="text-muted small mb-0">Ringkasan kehadiran peserta per pelatihan.</p>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Pelatihan</th>
                        <th>Total Peserta</th>
                        <th>Hadir</th>
                        <th>Sakit</th>
                        <th>Izin</th>
                        <th>Alpa</th>
                        <th>Persentase</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($trainingSummary ?? [] as $summary)
                    <tr>
                        <td>{{ $summary['training'] }}</td>
                        <td>{{ $summary['total'] }}</td>
                        <td><span class="text-success">{{ $summary['hadir'] }}</span></td>
                        <td><span class="text-warning">{{ $summary['sakit'] }}</span></td>
                        <td><span class="text-info">{{ $summary['izin'] }}</span></td>
                        <td><span class="text-danger">{{ $summary['alpa'] }}</span></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress" style="width: 100px; height: 8px;">
                                    <div class="progress-bar bg-success" 
                                         style="width: {{ $summary['persentase'] }}%;">
                                    </div>
                                </div>
                                <span class="small">{{ $summary['persentase'] }}%</span>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Delete Modals -->
@if(isset($absensis) && $absensis->count() > 0)
@foreach($absensis as $absen)
<div class="modal fade" id="deleteModal{{ $absen->id }}" tabindex="-1" 
     aria-labelledby="deleteModalLabel{{ $absen->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel{{ $absen->id }}">
                    <i class="bi bi-exclamation-triangle text-danger me-2"></i>
                    Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus data absensi ini?</p>
                <div class="alert alert-light">
                    <p class="fw-semibold mb-0">
                        {{ $absen->user->nama ?? $absen->user->name ?? 'Peserta' }} - 
                        {{ $absen->tanggal ? $absen->tanggal->format('d/m/Y') : '-' }}
                        ({{ $absen->status }})
                    </p>
                </div>
                <p class="text-muted small">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin.absen.destroy', $absen->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-1"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
@endif

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
    .progress {
        border-radius: 10px;
        background-color: #e9ecef;
    }
    .progress-bar {
        border-radius: 10px;
        transition: width 0.6s ease;
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
    // FOCUS SEARCH ON KEYBOARD SHORTCUT (CTRL + /)
    // ============================================================
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.key === '/') {
            e.preventDefault();
            const searchInput = document.querySelector('input[name="search"]');
            if (searchInput) {
                searchInput.focus();
                searchInput.select();
            }
        }
    });
});
</script>
@endpush
@endsection