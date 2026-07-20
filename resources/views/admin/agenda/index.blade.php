@extends('layouts.admin')

@section('title', 'Manajemen Agenda')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-calendar-event"></i></span>
        <div>
            <p class="eyebrow">Manajemen</p>
            <h1 class="h3 mb-0">Agenda</h1>
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

    @if(session('warning'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>
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
                    <span class="text-primary">Semua</span>
                    <span>agenda</span>
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
                    <span class="metric-label">Sedang Berlangsung</span>
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
            <div class="metric-card metric-danger">
                <div class="metric-top">
                    <span class="metric-label">Selesai</span>
                    <span class="metric-icon"><i class="bi bi-calendar-check"></i></span>
                </div>
                <div class="metric-value">{{ $completedCount ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-danger">Completed</span>
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
            </div>
            <form action="{{ route('admin.agenda.index') }}" method="GET" class="d-flex gap-2 flex-wrap align-items-center">
                <div class="input-group input-group-sm" style="width: 200px;">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control" name="search" placeholder="Cari agenda..." value="{{ request('search') }}">
                </div>
                <select class="form-select form-select-sm" name="status" style="width: 150px;">
                    <option value="">Semua Status</option>
                    <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Akan Datang</option>
                    <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Sedang Berlangsung</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
                <input type="date" class="form-control form-control-sm" name="date_from" value="{{ request('date_from') }}" style="width: 150px;" placeholder="Dari">
                <input type="date" class="form-control form-control-sm" name="date_to" value="{{ request('date_to') }}" style="width: 150px;" placeholder="Sampai">
                <button type="submit" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-search"></i> Filter
                </button>
                <a href="{{ route('admin.agenda.index') }}" class="btn btn-outline-secondary btn-sm" title="Reset Filter">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                </a>
                <a href="{{ route('admin.agenda.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle"></i> Tambah
                </a>
            </form>
        </div>
        @if(request('search') || request('status') || request('date_from') || request('date_to'))
        <div class="p-2 px-3 bg-light border-top">
            <small class="text-muted">
                <i class="bi bi-filter-circle me-1"></i>
                Filter aktif: 
                @if(request('search'))
                    <span class="badge text-bg-primary">Pencarian: "{{ request('search') }}"</span>
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
                <h5 class="section-title"><i class="bi bi-table"></i> Daftar Agenda</h5>
                <p class="text-muted small mb-0">Kelola semua agenda pelatihan.</p>
            </div>
        </div>
        <div class="table-responsive">
            @if(isset($agendas) && $agendas->count() > 0)
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 40px;">
                            <input type="checkbox" id="selectAll" class="form-check-input">
                        </th>
                        <th style="width: 50px;">#</th>
                        <th>Judul</th>
                        <th>Pelatihan</th>
                        <th>Tanggal & Waktu</th>
                        <th>Lokasi</th>
                        <th>Status</th>
                        <th class="text-end" style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($agendas as $index => $agenda)
                    <tr>
                        <td>
                            <input type="checkbox" class="form-check-input agenda-checkbox" value="{{ $agenda->id }}">
                        </td>
                        <td>{{ $agendas->firstItem() + $index }}</td>
                        <td>
                            <div>
                                <p class="fw-semibold mb-0">{{ $agenda->judul }}</p>
                            </div>
                        </td>
                        <td>
                            @if($agenda->training)
                            <span class="">{{ $agenda->training->judul }}</span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <div class="small">
                                <div>
                                    {{ $agenda->tanggal ? $agenda->tanggal->format('d/m/Y') : '-' }}
                                </div>
                                <div>
                                    {{ $agenda->jam_mulai ? date('H:i', strtotime($agenda->jam_mulai)) : '-' }}
                                    {{ $agenda->jam_selesai ? ' - ' . date('H:i', strtotime($agenda->jam_selesai)) : '' }}
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($agenda->lokasi)
                            <span class="badge text-bg-secondary">
                                <i class="bi bi-geo-alt me-1"></i>
                                {{ Str::limit($agenda->lokasi, 20) }}
                            </span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $now = now();
                                $statusMap = [
                                    'upcoming' => ['label' => '📅 Akan Datang', 'class' => 'text-bg-primary'],
                                    'ongoing' => ['label' => '⏳ Sedang Berlangsung', 'class' => 'text-bg-success'],
                                    'completed' => ['label' => '✅ Selesai', 'class' => 'text-bg-secondary'],
                                    'cancelled' => ['label' => '❌ Dibatalkan', 'class' => 'text-bg-danger'],
                                ];
                                $status = $statusMap[$agenda->status] ?? ['label' => $agenda->status ?? 'Unknown', 'class' => 'text-bg-secondary'];
                            @endphp
                            <span class="badge {{ $status['class'] }}">
                                {{ $status['label'] }}
                            </span>
                        </td>
                        <td class="text-end"> 
                            <div class="d-flex gap-1 justify-content-end" role="group">
                                <a href="{{ route('admin.agenda.show', $agenda->id) }}" 
                                   class="btn btn-info" title="Lihat">
                                    <i class="bi bi-eye"></i> 
                                </a>
                                <a href="{{ route('admin.agenda.edit', $agenda->id) }}" 
                                   class="btn btn-warning" title="Edit">
                                    <i class="bi bi-pencil"></i> 
                                </a>
                                <button type="button" class="btn btn-danger" 
                                        data-bs-toggle="modal" data-bs-target="#deleteModal{{ $agenda->id }}" 
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
                    <p class="h5">Belum ada agenda</p>
                    <p class="small">Mulai dengan menambahkan agenda baru</p>
                    <a href="{{ route('admin.agenda.create') }}" class="btn btn-primary btn-sm mt-2">
                        <i class="bi bi-plus-circle"></i> Tambah Agenda
                    </a>
                </div>
            </div>
            @endif
        </div>
        @if(isset($agendas) && $agendas->hasPages())
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mt-3 px-3 pb-3">
            <p class="text-muted small mb-0">
                Menampilkan {{ $agendas->firstItem() ?? 0 }} sampai {{ $agendas->lastItem() ?? 0 }} 
                dari {{ $agendas->total() ?? 0 }} agenda
            </p>
            <nav aria-label="Agenda pagination">
                {{ $agendas->links() }}
            </nav>
        </div>
        @endif
    </div>

    <!-- Calendar View (Optional) -->
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
                <p>Apakah Anda yakin ingin menghapus agenda ini?</p>
                <div class="alert alert-light">
                    <p class="fw-semibold mb-0">{{ $agenda->judul }}</p>
                    @if($agenda->tanggal)
                    <p class="text-muted small mb-0">{{ $agenda->tanggal->format('d/m/Y') }}</p>
                    @endif
                </div>
                @if($agenda->status == 'ongoing' || $agenda->status == 'completed')
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Agenda ini sudah {{ $agenda->status == 'ongoing' ? 'sedang berlangsung' : 'selesai' }}. Menghapus akan menghapus semua data terkait.
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
    .table-bordered td, .table-bordered th {
        border-color: #dee2e6;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ============================================================
    // SELECT ALL CHECKBOX
    // ============================================================
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.agenda-checkbox');

    if (selectAll) {
        selectAll.addEventListener('change', function() {
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('change', function() {
            const checked = document.querySelectorAll('.agenda-checkbox:checked');
            const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
            if (bulkDeleteBtn) {
                if (checked.length > 0) {
                    bulkDeleteBtn.classList.remove('d-none');
                    bulkDeleteBtn.textContent = '🗑️ Hapus ' + checked.length + ' Terpilih';
                } else {
                    bulkDeleteBtn.classList.add('d-none');
                }
            }
        });
    });

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