
@extends('layouts.admin')

@section('title', 'Manajemen Agenda')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-calendar-event"></i></span>
        <div>
            <p class="eyebrow mb-1">Manajemen</p>
            <h1 class="h3 mb-0">Agenda</h1>
            <p class="text-muted mb-0">Kelola agenda pelatihan</p>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <!-- Alert Messages -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-circle-fill me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('warning'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        {{ session('warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Stats -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-primary">
                <div class="metric-top">
                    <span class="metric-label">Total Agenda</span>
                    <span class="metric-icon"><i class="bi bi-calendar-event"></i></span>
                </div>
                <div class="metric-value">{{ $totalAgenda ?? $agendas->total() ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-success">+5%</span>
                    <span>bulan ini</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-success">
                <div class="metric-top">
                    <span class="metric-label">Akan Datang</span>
                    <span class="metric-icon"><i class="bi bi-calendar-check"></i></span>
                </div>
                <div class="metric-value">{{ $upcomingCount ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-success">Upcoming</span>
                    <span>agenda</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-warning">
                <div class="metric-top">
                    <span class="metric-label">Berlangsung</span>
                    <span class="metric-icon"><i class="bi bi-calendar-week"></i></span>
                </div>
                <div class="metric-value">{{ $ongoingCount ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-warning">Ongoing</span>
                    <span>agenda</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-secondary">
                <div class="metric-top">
                    <span class="metric-label">Selesai</span>
                    <span class="metric-icon"><i class="bi bi-check-circle"></i></span>
                </div>
                <div class="metric-value">{{ $completedCount ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-secondary">Completed</span>
                    <span>agenda</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="panel mb-3">
        <div class="panel-header">
            <div>
                <h5 class="section-title"><i class="bi bi-funnel"></i> Filter & Pencarian</h5>
                <p class="text-muted small mb-0">Temukan agenda yang Anda cari.</p>
            </div>
            <div class="d-flex gap-2 flex-wrap align-items-center">
                <form action="{{ route('admin.agenda.index') }}" method="GET" class="d-flex gap-2 flex-wrap align-items-center">
                    <div class="input-group input-group-sm" style="width: 200px;">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" name="search" placeholder="Cari agenda..." value="{{ request('search') }}">
                    </div>
                    <select class="form-select form-select-sm" name="status" style="width: 140px;">
                        <option value="">Semua Status</option>
                        <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Akan Datang</option>
                        <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Berlangsung</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                    <input type="date" class="form-control form-control-sm" name="date_from" value="{{ request('date_from') }}" style="width: 140px;" placeholder="Dari">
                    <input type="date" class="form-control form-control-sm" name="date_to" value="{{ request('date_to') }}" style="width: 140px;" placeholder="Sampai">
                    <button type="submit" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-search"></i>
                    </button>
                    <a href="{{ route('admin.agenda.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle"></i> Tambah Agenda
                    </a>
                </form>
                @if(request('search') || request('status') || request('date_from') || request('date_to'))
                <a href="{{ route('admin.agenda.index') }}" class="btn btn-outline-secondary btn-sm" title="Reset Filter">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                </a>
                @endif
            </div>
        </div>
        @if(request('search') || request('status') || request('date_from') || request('date_to'))
        <div class="p-2 px-3 bg-light border-top">
            <small class="text-muted">
                <i class="bi bi-filter-circle me-1"></i>
                Filter aktif: 
                @if(request('search'))
                    <span class="badge text-bg-primary">Cari: "{{ request('search') }}"</span>
                @endif
                @if(request('status'))
                    <span class="badge text-bg-primary">Status: {{ ucfirst(request('status')) }}</span>
                @endif
                @if(request('date_from'))
                    <span class="badge text-bg-primary">Dari: {{ request('date_from') }}</span>
                @endif
                @if(request('date_to'))
                    <span class="badge text-bg-primary">Sampai: {{ request('date_to') }}</span>
                @endif
                <a href="{{ route('admin.agenda.index') }}" class="text-danger ms-2">
                    <i class="bi bi-x-circle"></i> Hapus filter
                </a>
            </small>
        </div>
        @endif
    </div>

    <!-- Table -->
    <div class="panel">
        <div class="panel-header">
            <div>
                <h5 class="section-title"><i class="bi bi-table"></i> Daftar Agenda</h5>
                <p class="text-muted small mb-0">Menampilkan {{ $agendas->firstItem() ?? 0 }} - {{ $agendas->lastItem() ?? 0 }} dari {{ $agendas->total() ?? 0 }} agenda</p>
            </div>
        </div>
        <div class="table-responsive">
            @if(isset($agendas) && $agendas->count() > 0)
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Judul</th>
                        <th>Pelatihan</th>
                        <th>Tanggal & Waktu</th>
                        <th>Lokasi</th>
                        <th>Status</th>
                        <th class="text-center" style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($agendas as $index => $agenda)
                    <tr>
                        <td>{{ $agendas->firstItem() + $index }}</td>
                        <td>
                            <p class="fw-semibold mb-0">{{ Str::limit($agenda->judul, 50) }}</p>
                        </td>
                        <td>
                            @if($agenda->training)
                            <span class="text-muted">{{ Str::limit($agenda->training->judul, 30) }}</span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <div class="small">
                                <div><i class="bi bi-calendar3 me-1"></i> {{ $agenda->tanggal ? $agenda->tanggal->format('d/m/Y') : '-' }}</div>
                                <div><i class="bi bi-clock me-1"></i> {{ $agenda->jam_mulai ? date('H:i', strtotime($agenda->jam_mulai)) : '-' }} {{ $agenda->jam_selesai ? ' - ' . date('H:i', strtotime($agenda->jam_selesai)) : '' }}</div>
                            </div>
                        </td>
                        <td>
                            @if($agenda->lokasi)
                                <span class="text-muted"><i class="bi bi-geo-alt me-1"></i> {{ Str::limit($agenda->lokasi, 25) }}</span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $statusMap = [
                                    'upcoming' => ['label' => 'Akan Datang', 'class' => 'badge-published'],
                                    'ongoing' => ['label' => 'Berlangsung', 'class' => 'badge-berjalan'],
                                    'completed' => ['label' => 'Selesai', 'class' => 'badge-selesai'],
                                    'cancelled' => ['label' => 'Dibatalkan', 'class' => 'badge-dibatalkan'],
                                ];
                                $status = $statusMap[$agenda->status] ?? ['label' => $agenda->status ?? 'Unknown', 'class' => 'badge-draft'];
                            @endphp
                            <span class="badge {{ $status['class'] }}">
                                <i class="bi bi-circle-fill me-1" style="font-size: 6px;"></i>
                                {{ $status['label'] }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="{{ route('admin.agenda.show', $agenda->id) }}" 
                                   class="badge bg-info text-white border-0 p-1 text-decoration-none" title="Lihat">
                                    <i class="bi bi-eye" style="font-size: 0.7rem;"></i>
                                </a>
                                <a href="{{ route('admin.agenda.edit', $agenda->id) }}" 
                                   class="badge bg-warning text-dark border-0 p-1 text-decoration-none" title="Edit">
                                    <i class="bi bi-pencil" style="font-size: 0.7rem;"></i>
                                </a>
                                <button type="button" class="badge bg-danger text-white border-0 p-1" 
                                        data-bs-toggle="modal" data-bs-target="#deleteModal{{ $agenda->id }}" 
                                        title="Hapus">
                                    <i class="bi bi-trash" style="font-size: 0.7rem;"></i>
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
                    <p class="h5">
                        @if(request('search') || request('status') || request('date_from') || request('date_to'))
                            Tidak ada agenda yang sesuai dengan filter
                        @else
                            Belum ada agenda
                        @endif
                    </p>
                    <p class="small">
                        @if(request('search') || request('status') || request('date_from') || request('date_to'))
                            Coba ubah kriteria pencarian atau reset filter
                        @else
                            Mulai dengan menambahkan agenda baru
                        @endif
                    </p>
                    @if(request('search') || request('status') || request('date_from') || request('date_to'))
                    <a href="{{ route('admin.agenda.index') }}" class="btn btn-outline-secondary btn-sm mt-2">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset Filter
                    </a>
                    @endif
                    <a href="{{ route('admin.agenda.create') }}" class="btn btn-primary btn-sm mt-2">
                        <i class="bi bi-plus-circle"></i> Tambah Agenda
                    </a>
                </div>
            </div>
            @endif
        </div>
        @if(isset($agendas) && $agendas->hasPages())
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2 mt-2 px-3 pb-3">
            <p class="text-muted small mb-0" style="font-size: 0.75rem;">
                Menampilkan {{ $agendas->firstItem() ?? 0 }} sampai {{ $agendas->lastItem() ?? 0 }} 
                dari {{ $agendas->total() ?? 0 }} agenda
            </p>
            <nav aria-label="Agenda pagination">
                {{ $agendas->appends(request()->query())->links() }}
            </nav>
        </div>
        @endif
    </div>

    <!-- Calendar View -->
    <div class="panel mt-3">
        <div class="panel-header">
            <div>
                <h5 class="section-title"><i class="bi bi-calendar3"></i> Kalender Agenda</h5>
                <p class="text-muted small mb-0">Lihat agenda berdasarkan tanggal.</p>
            </div>
        </div>
        <div class="p-4">
            <div class="row g-2">
                @php
                    $today = now();
                    $currentMonth = $today->format('m');
                    $currentYear = $today->format('Y');
                    $daysInMonth = $today->daysInMonth;
                    $firstDayOfMonth = $today->copy()->startOfMonth()->format('w');
                    
                    $monthlyAgendas = $agendas->filter(function($agenda) use ($currentMonth, $currentYear) {
                        return $agenda->tanggal && $agenda->tanggal->format('m') == $currentMonth && $agenda->tanggal->format('Y') == $currentYear;
                    });
                    
                    $agendaDates = $monthlyAgendas->pluck('tanggal')->map(function($date) {
                        return $date->format('Y-m-d');
                    })->toArray();
                @endphp
                
                <div class="col-12">
                    <div class="text-center mb-3">
                        <h6 class="fw-bold">{{ $today->format('F Y') }}</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0" style="font-size: 0.85rem;">
                            <thead>
                                <tr>
                                    <th class="text-center">Min</th>
                                    <th class="text-center">Sen</th>
                                    <th class="text-center">Sel</th>
                                    <th class="text-center">Rab</th>
                                    <th class="text-center">Kam</th>
                                    <th class="text-center">Jum</th>
                                    <th class="text-center">Sab</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $day = 1;
                                    $emptyDays = $firstDayOfMonth;
                                    $totalDays = $daysInMonth;
                                @endphp
                                @for($week = 0; $week < ceil(($totalDays + $emptyDays) / 7); $week++)
                                <tr>
                                    @for($d = 0; $d < 7; $d++)
                                        @php
                                            $dayNumber = ($week * 7 + $d) - $emptyDays + 1;
                                            $dateObj = $dayNumber > 0 && $dayNumber <= $totalDays ? \Carbon\Carbon::create($currentYear, $currentMonth, $dayNumber) : null;
                                            $hasAgenda = $dateObj && in_array($dateObj->format('Y-m-d'), $agendaDates);
                                            $isToday = $dateObj && $dateObj->isToday();
                                        @endphp
                                        <td class="text-center p-1 {{ $isToday ? 'bg-primary bg-opacity-10' : '' }}" style="height: 60px;">
                                            @if($dateObj)
                                                <span class="{{ $isToday ? 'fw-bold text-primary' : '' }}">{{ $dayNumber }}</span>
                                                @if($hasAgenda)
                                                    <div class="mt-1">
                                                        <span class="badge text-bg-success" style="font-size: 6px; width: 6px; height: 6px; border-radius: 50%; display: inline-block;"></span>
                                                    </div>
                                                @endif
                                            @endif
                                        </td>
                                    @endfor
                                </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modals -->
@if(isset($agendas) && $agendas->count() > 0)
@foreach($agendas as $agenda)
<div class="modal fade" id="deleteModal{{ $agenda->id }}" tabindex="-1" 
     aria-labelledby="deleteModalLabel{{ $agenda->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel{{ $agenda->id }}">
                    <i class="bi bi-exclamation-triangle text-danger me-2"></i>
                    Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus agenda <strong>{{ $agenda->judul }}</strong>?</p>
                @if($agenda->status == 'ongoing' || $agenda->status == 'completed')
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Agenda ini sudah {{ $agenda->status == 'ongoing' ? 'sedang berlangsung' : 'selesai' }}.
                </div>
                @endif
                <p class="text-muted small">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin.agenda.destroy', $agenda->id) }}" method="POST" class="d-inline">
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
    /* ============================================================
       PAGE HEADING
    ============================================================ */
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
        background: linear-gradient(135deg, #eaf1fd, #d4e4f7);
        color: #4e9af1;
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
        flex-wrap: wrap;
    }

    /* ============================================================
       METRIC CARDS
    ============================================================ */
    .metric-card {
        background: #fff;
        border-radius: 0.75rem;
        padding: 1.1rem 1.25rem;
        box-shadow: 0 1px 4px rgba(0,0,0,.06);
        border-left: 4px solid transparent;
        height: 100%;
        transition: all 0.3s ease;
    }
    .metric-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    }
    .metric-primary { border-left-color: #4e9af1; }
    .metric-success { border-left-color: #28c76f; }
    .metric-warning { border-left-color: #ff9f43; }
    .metric-secondary { border-left-color: #6c757d; }
    
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

    /* ============================================================
       PANEL
    ============================================================ */
    .panel {
        background: #fff;
        border-radius: .75rem;
        box-shadow: 0 1px 4px rgba(0,0,0,.06);
        overflow: hidden;
    }
    .panel:hover {
        box-shadow: 0 8px 30px rgba(0,0,0,0.06);
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
    
    .section-title i {
        color: #4e9af1;
    }

    /* ============================================================
       TABLE
    ============================================================ */
    .table th {
        font-weight: 600;
        color: #6c757d;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #f0f0f0;
        padding: 0.75rem 0.75rem;
        background: #fafbfc;
    }
    
    .table td {
        font-size: 0.9rem;
        padding: 0.75rem 0.75rem;
        vertical-align: middle;
    }
    
    .table tbody tr {
        transition: background 0.2s ease;
    }
    .table tbody tr:hover {
        background: #f8fafc;
    }
    
    .table tbody tr:last-child td {
        border-bottom: none;
    }

    /* ============================================================
       BADGE
    ============================================================ */
    .badge {
        font-weight: 500;
        padding: 0.25rem 0.5rem;
        font-size: 0.7rem;
        cursor: pointer;
        transition: all 0.2s ease;
        border: none;
    }
    .badge:hover {
        transform: scale(1.05);
    }
    .badge-draft {
        background: #e9ecef !important;
        color: #6c757d !important;
    }
    .badge-published {
        background: #d4edda !important;
        color: #155724 !important;
    }
    .badge-berjalan {
        background: #cce5ff !important;
        color: #004085 !important;
    }
    .badge-selesai {
        background: #d6d8db !important;
        color: #1b1e21 !important;
    }
    .badge-dibatalkan {
        background: #f8d7da !important;
        color: #721c24 !important;
    }
    .badge.bg-info {
        background: #e3f0ff !important;
        color: #0d6efd !important;
        padding: 0.2rem 0.4rem;
        font-size: 0.65rem;
    }
    .badge.bg-info:hover {
        background: #d0e4ff !important;
    }
    .badge.bg-warning {
        background: #fff3cd !important;
        color: #856404 !important;
        padding: 0.2rem 0.4rem;
        font-size: 0.65rem;
    }
    .badge.bg-warning:hover {
        background: #ffedb3 !important;
    }
    .badge.bg-danger {
        background: #f8d7da !important;
        color: #721c24 !important;
        padding: 0.2rem 0.4rem;
        font-size: 0.65rem;
    }
    .badge.bg-danger:hover {
        background: #f5c6cb !important;
    }

    /* ============================================================
       FORM
    ============================================================ */
    .form-select-sm,
    .form-control-sm {
        font-size: 0.8rem;
        padding: 0.3rem 0.8rem;
        border-radius: 0.5rem;
        border-color: #e2e8f0;
    }
    .form-select-sm:focus,
    .form-control-sm:focus {
        border-color: #4e9af1;
        box-shadow: 0 0 0 3px rgba(78, 154, 241, 0.15);
    }
    .input-group-sm .input-group-text {
        background: #f8fafc;
        border-color: #e2e8f0;
        color: #8a93a3;
        font-size: 0.8rem;
    }

    /* ============================================================
       BUTTONS
    ============================================================ */
    .btn {
        border-radius: 0.5rem;
        padding: 0.45rem 1.2rem;
        font-weight: 500;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }
    .btn-primary {
        background: #4e9af1;
        border-color: #4e9af1;
        color: #fff;
    }
    .btn-primary:hover {
        background: #3a7bc8;
        border-color: #3a7bc8;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(78, 154, 241, 0.3);
    }
    .btn-outline-secondary {
        border-color: #e2e8f0;
        color: #4a5568;
    }
    .btn-outline-secondary:hover {
        background: #e2e8f0;
        border-color: #d5dce6;
    }
    .btn-danger {
        background: #ea5455;
        border-color: #ea5455;
        color: #fff;
    }
    .btn-danger:hover {
        background: #e53e3e;
        border-color: #e53e3e;
    }
    .btn-sm {
        padding: 0.3rem 0.8rem;
        font-size: 0.8rem;
    }

    /* ============================================================
       ALERT
    ============================================================ */
    .alert {
        border-radius: 0.75rem;
        border: none;
        padding: 0.75rem 1rem;
    }
    .alert-success {
        background: #ecfdf5;
        color: #065f46;
    }
    .alert-danger {
        background: #fef2f2;
        color: #991b1b;
    }
    .alert-warning {
        background: #fffbeb;
        color: #92400e;
    }

    /* ============================================================
       RESPONSIVE
    ============================================================ */
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
        }
        .heading-actions .btn {
            width: 100%;
        }
        .panel-header {
            flex-direction: column;
            align-items: flex-start;
        }
        .panel-header form {
            width: 100%;
            flex-wrap: wrap;
        }
        .panel-header form .input-group {
            flex: 1;
            min-width: 120px;
        }
        .metric-value {
            font-size: 1.2rem;
        }
        .table-responsive {
            font-size: 0.85rem;
        }
        .table th,
        .table td {
            padding: 0.5rem 0.5rem;
        }
        .badge {
            font-size: 0.6rem;
            padding: 0.15rem 0.3rem;
        }
        .badge i {
            font-size: 0.6rem !important;
        }
        .modal-dialog {
            margin: 0.5rem;
        }
        .modal-body {
            padding: 1rem;
        }
    }

    @media (max-width: 576px) {
        .pagination {
            flex-wrap: wrap;
        }
        .pagination .page-link {
            padding: 0.15rem 0.4rem;
            font-size: 0.7rem;
        }
        .d-flex.gap-2.flex-wrap {
            flex-direction: column;
            align-items: stretch;
        }
        .d-flex.gap-2.flex-wrap .btn {
            width: 100%;
        }
        .input-group {
            width: 100% !important;
        }
        .form-select-sm {
            width: 100% !important;
        }
        .form-control-sm {
            width: 100% !important;
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
