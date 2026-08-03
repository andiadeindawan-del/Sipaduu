@extends('layouts.peserta')

@section('title', 'Agenda')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-calendar-event"></i></span>
        <div>
            <p class="eyebrow">Informasi</p>
            <h1 class="h3 mb-0">Agenda</h1>
        </div>
    </div>
    <div class="heading-actions">
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary btn-sm" onclick="window.print()">
                <i class="bi bi-printer"></i> Cetak
            </button>
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
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="metric-card metric-primary">
                <div class="metric-top">
                    <span class="metric-label">Total Agenda</span>
                    <span class="metric-icon"><i class="bi bi-calendar"></i></span>
                </div>
                <div class="metric-value">{{ $totalAgendas ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-primary">Semua</span>
                    <span>agenda</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="metric-card metric-success">
                <div class="metric-top">
                    <span class="metric-label">Akan Datang</span>
                    <span class="metric-icon"><i class="bi bi-calendar-check"></i></span>
                </div>
                <div class="metric-value">{{ $upcomingAgendas ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-success">Akan</span>
                    <span>datang</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="metric-card metric-warning">
                <div class="metric-top">
                    <span class="metric-label">Hari Ini</span>
                    <span class="metric-icon"><i class="bi bi-calendar3"></i></span>
                </div>
                <div class="metric-value">{{ $todayAgendas ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-warning">Hari</span>
                    <span>ini</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter -->

<div class="panel mb-3">
    <div class="p-3">
        <form action="{{ route('peserta.agenda.index') }}" method="GET" class="row g-3">
            <div class="col-12 col-md-6">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control" name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Cari agenda...">
                </div>
            </div>
            <div class="col-12 col-md-3">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i> Filter
                </button>
            </div>
            <div class="col-12 col-md-3">
                <a href="{{ route('peserta.agenda.index') }}" class="btn btn-outline-secondary w-100" title="Reset Filter">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                </a>
            </div>
        </form>
    </div>
    @if(request('search'))
    <div class="p-2 px-3 bg-light border-top">
        <small class="text-muted">
            <i class="bi bi-filter-circle me-1"></i>
            Filter aktif: 
            @if(request('search'))
                <span class="badge text-bg-primary">Cari: {{ request('search') }}</span>
            @endif
            <a href="{{ route('peserta.agenda.index') }}" class="text-danger ms-2">
                <i class="bi bi-x-circle"></i> Hapus filter
            </a>
        </small>
    </div>
    @endif
</div>
```

    <!-- Agenda List -->
    <div class="panel">
        <div class="panel-header">
            <div>
                <h5 class="section-title"><i class="bi bi-table"></i> Daftar Agenda</h5>
                <p class="text-muted small mb-0">Menampilkan {{ $agendas->firstItem() ?? 0 }} - {{ $agendas->lastItem() ?? 0 }} dari {{ $agendas->total() ?? 0 }} agenda</p>
            </div>
        </div>
        <div class="table-responsive">
            @if($agendas->count() > 0)
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Agenda</th>
                        <th>Pelatihan</th>
                        <th>Tanggal & Waktu</th>
                        <th>Lokasi</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($agendas as $index => $agenda)
                    @php
                        $isToday = $agenda->tanggal && $agenda->tanggal->isToday();
                        $isUpcoming = $agenda->tanggal && $agenda->tanggal->isFuture();
                        $isPast = $agenda->tanggal && $agenda->tanggal->isPast();
                    @endphp
                    <tr class="{{ $isToday ? 'table-warning' : '' }}">
                        <td>{{ $agendas->firstItem() + $index }}</td>
                        <td>
                            <div>
                                <p class="fw-semibold mb-0">{{ Str::limit($agenda->judul, 50) }}</p>
                               
                            </div>
                        </td>
                        <td>
                            @if($agenda->training)
                                <span class="badge text-bg-primary">{{ Str::limit($agenda->training->judul, 25) }}</span>
                            @else
                                <span class="">Umum</span>
                            @endif
                        </td>
                        <td>
                            <div>
                                <span class="fw-semibold">
                                    {{ $agenda->tanggal ? $agenda->tanggal->format('d/m/Y') : '-' }}
                                    @if($isToday)
                                        <span class="badge text-bg-warning ms-1">
                                            <i class="bi bi-calendar3 me-1"></i> Hari Ini
                                        </span>
                                    @endif
                                </span>
                                <br>
                             
                            </div>
                        </td>
                        <td>
                            <!-- PERBAIKAN: $agenda->tipe bukan $agenda->type -->
                            @if($agenda->tipe == 'online')
                                <span class="text-primary">
                                    <i class="bi bi-wifi me-1"></i> Online
                                </span>
                                @if($agenda->link_meeting)
                                    <br>
                                    <small>
                                        <a href="{{ $agenda->link_meeting }}" target="_blank" class="text-decoration-none">
                                            <i class="bi bi-link me-1"></i> Link Meeting
                                        </a>
                                    </small>
                                @endif
                            @elseif($agenda->tipe == 'offline')
                                <span class="text-success">
                                    <i class="bi bi-building me-1"></i> Offline
                                </span>
                                @if($agenda->lokasi)
                                    <br>
                                    <small class="text-muted">
                                        <i class="bi bi-geo-alt me-1"></i>
                                        {{ Str::limit($agenda->lokasi, 30) }}
                                    </small>
                                @endif
                            @else
                                <span class="text-warning">
                                    <i class="bi bi-arrows me-1"></i> Hybrid
                                </span>
                                @if($agenda->lokasi || $agenda->link_meeting)
                                    <br>
                                    <small class="text-muted">
                                        @if($agenda->lokasi)
                                            <i class="bi bi-geo-alt me-1"></i> {{ Str::limit($agenda->lokasi, 20) }}
                                        @endif
                                        @if($agenda->link_meeting)
                                            <a href="{{ $agenda->link_meeting }}" target="_blank" class="text-decoration-none ms-1">
                                                <i class="bi bi-link me-1"></i>
                                            </a>
                                        @endif
                                    </small>
                                @endif
                            @endif
                        </td>
                        <td>
                            <div class="d-flex flex-wrap gap-1">
                                <span class="badge 
                                    @if($agenda->status == 'published') text-bg-success
                                    @elseif($agenda->status == 'draft') text-bg-secondary
                                    @elseif($agenda->status == 'selesai') text-bg-info
                                    @else text-bg-danger
                                    @endif
                                ">
                                    {{ ucfirst($agenda->status ?? 'Draft') }}
                                </span>
                                @if($isToday)
                                    <span class="badge text-bg-warning">
                                        <i class="bi bi-calendar3 me-1"></i> Hari Ini
                                    </span>
                                @endif
                                @if($isUpcoming && !$isToday)
                                    <span class="badge text-bg-primary">
                                        <i class="bi bi-clock me-1"></i> Akan Datang
                                    </span>
                                @endif
                                @if($isPast && $agenda->status != 'selesai')
                                    <span class="badge text-bg-secondary">
                                        <i class="bi bi-clock-history me-1"></i> Lewat
                                    </span>
                                @endif
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
                    <p class="h5">Belum ada agenda</p>
                    <p class="small">
                        @if(request('search') || request('tipe') || request('date_from') || request('date_to'))
                            Tidak ada agenda yang sesuai dengan filter yang Anda pilih.
                        @else
                            Tidak ada agenda yang tersedia saat ini untuk pelatihan Anda.
                        @endif
                    </p>
                    @if(request('search') || request('tipe') || request('date_from') || request('date_to'))
                    <a href="{{ route('peserta.agenda.index') }}" class="btn btn-success btn-sm mt-2">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Filter
                    </a>
                    @endif
                </div>
            </div>
            @endif
        </div>
        @if($agendas->hasPages())
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mt-3 px-3 pb-3">
            <p class="text-muted small mb-0">
                Menampilkan {{ $agendas->firstItem() ?? 0 }} sampai {{ $agendas->lastItem() ?? 0 }} 
                dari {{ $agendas->total() ?? 0 }} agenda
            </p>
            <nav aria-label="Pagination">
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
                    
                    // Ambil agenda untuk bulan ini
                    $monthlyAgendas = $agendas->filter(function($agenda) use ($currentMonth, $currentYear) {
                        return $agenda->tanggal && $agenda->tanggal->format('m') == $currentMonth && $agenda->tanggal->format('Y') == $currentYear;
                    });
                    
                    $agendaDates = $monthlyAgendas->pluck('tanggal')->map(function($date) {
                        return $date->format('Y-m-d');
                    })->toArray();
                @endphp
                
                <div class="col-12">
                    <div class="text-center mb-3">
                        <h6>{{ $today->format('F Y') }}</h6>
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

@push('styles')
<style>
    .metric-card {
        transition: transform 0.3s ease;
    }
    .metric-card:hover {
        transform: translateY(-4px);
    }
    
    .panel {
        transition: transform 0.2s ease;
    }
    .panel:hover {
        transform: translateY(-2px);
    }
    
    .table th {
        font-weight: 600;
        color: #6c757d;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    /* ===== CALENDAR STYLES ===== */
    .calendar-grid {
        max-width: 700px;
        margin: 0 auto;
    }
    
    .calendar-day {
        position: relative;
        display: inline-flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 42px;
        height: 42px;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.25s ease;
        background: transparent;
        margin: 2px auto;
    }
    .calendar-day:hover {
        transform: scale(1.08);
        background: rgba(29, 168, 83, 0.08);
    }
    .calendar-day .day-number {
        font-size: 0.85rem;
        font-weight: 500;
        color: #1a2236;
        transition: all 0.2s ease;
    }
    
    /* Today */
    .calendar-day.today {
        background: linear-gradient(135deg, #1da853 0%, #1a9e4a 100%);
        box-shadow: 0 4px 12px rgba(29, 168, 83, 0.35);
    }
    .calendar-day.today .day-number {
        color: #fff !important;
        font-weight: 700;
    }
    .calendar-day.today:hover {
        transform: scale(1.08);
        box-shadow: 0 6px 20px rgba(29, 168, 83, 0.45);
    }
    
    /* Has Agenda */
    .calendar-day.has-agenda {
        background: rgba(78, 154, 241, 0.08);
        border: 2px solid rgba(78, 154, 241, 0.2);
    }
    .calendar-day.has-agenda .day-number {
        color: #4e9af1;
        font-weight: 600;
    }
    .calendar-day.has-agenda:hover {
        background: rgba(78, 154, 241, 0.18);
        border-color: #4e9af1;
    }
    
    /* Today + Has Agenda */
    .calendar-day.today.has-agenda {
        background: linear-gradient(135deg, #1da853 0%, #4e9af1 100%);
        border: none;
    }
    .calendar-day.today.has-agenda .day-number {
        color: #fff !important;
    }
    
    /* Weekend */
    .calendar-day.weekend .day-number {
        color: #ea5455;
        font-weight: 400;
    }
    .calendar-day.weekend.today .day-number {
        color: #fff !important;
    }
    
    /* Agenda Dot */
    .calendar-day .agenda-dot {
        position: absolute;
        bottom: 4px;
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #4e9af1;
        box-shadow: 0 0 8px rgba(78, 154, 241, 0.4);
    }
    .calendar-day.today .agenda-dot {
        background: #fff;
        box-shadow: 0 0 8px rgba(255, 255, 255, 0.6);
    }
    
    /* Today Badge */
    .calendar-day .today-badge {
        position: absolute;
        top: -6px;
        right: -6px;
        font-size: 0.45rem;
        font-weight: 700;
        background: #ea5455;
        color: #fff;
        padding: 1px 4px;
        border-radius: 6px;
        letter-spacing: 0.3px;
    }
    
    /* Legend */
    .legend-dot {
        width: 14px;
        height: 14px;
        border-radius: 50%;
        display: inline-block;
        flex-shrink: 0;
    }
    .today-dot {
        background: linear-gradient(135deg, #1da853 0%, #1a9e4a 100%);
        box-shadow: 0 2px 8px rgba(29, 168, 83, 0.3);
    }
    .has-agenda-dot {
        background: #4e9af1;
        border: 2px solid rgba(78, 154, 241, 0.2);
    }
    .no-agenda-dot {
        background: #e9ecef;
        border: 2px solid #dee2e6;
    }
    .weekend-dot {
        background: #ea5455;
        border: 2px solid rgba(234, 84, 85, 0.2);
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

    // Filter form auto submit on select change
    document.querySelector('select[name="tipe"]')?.addEventListener('change', function() {
        this.closest('form').submit();
    });
});

// ============================================================
// CALENDAR FUNCTIONS
// ============================================================

/**
 * Filter agenda by date
 */
function filterByDate(date) {
    if (date) {
        window.location.href = "{{ route('peserta.agenda.index') }}?date_from=" + date;
    }
}

/**
 * Go to today
 */
function goToToday() {
    const today = new Date();
    const month = today.getMonth() + 1;
    const year = today.getFullYear();
    
    // Remove existing month/year params and set to current
    const url = new URL(window.location.href);
    url.searchParams.set('month', month);
    url.searchParams.set('year', year);
    window.location.href = url.toString();
}

// ============================================================
// KEYBOARD NAVIGATION
// ============================================================

document.addEventListener('keydown', function(e) {
    // Left arrow - previous month
    if (e.key === 'ArrowLeft') {
        const prevBtn = document.querySelector('.btn-outline-secondary .bi-chevron-left')?.closest('a');
        if (prevBtn) {
            e.preventDefault();
            window.location.href = prevBtn.href;
        }
    }
    // Right arrow - next month
    if (e.key === 'ArrowRight') {
        const nextBtn = document.querySelector('.btn-outline-secondary .bi-chevron-right')?.closest('a');
        if (nextBtn) {
            e.preventDefault();
            window.location.href = nextBtn.href;
        }
    }
    // T key - go to today
    if (e.key === 't' || e.key === 'T') {
        if (!e.ctrlKey && !e.metaKey) {
            e.preventDefault();
            goToToday();
        }
    }
});
</script>
@endpush
@endsection