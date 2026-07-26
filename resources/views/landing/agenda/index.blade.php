@extends('layouts.landing')

@section('title', 'Agenda')

@section('content')


<!-- ============================================================
     AGENDA LIST
============================================================ -->
<section class="section-pad">
    <div class="container">
        <!-- Filter -->
        <div class="panel mb-4">
            <div class="panel-header">
                <div>
                    <h5 class="section-title"><i class="bi bi-funnel"></i> Filter Agenda</h5>
                    <p class="text-muted small mb-0">Temukan agenda yang Anda cari.</p>
                </div>
            </div>
            <div class="p-3">
                <form action="{{ route('landing.agenda.index') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-12 col-md-4">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control" name="search" 
                                   value="{{ request('search') }}" 
                                   placeholder="Cari agenda...">
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <input type="date" class="form-control" name="date_from" 
                               value="{{ request('date_from') }}" placeholder="Dari">
                    </div>
                    <div class="col-12 col-md-3">
                        <input type="date" class="form-control" name="date_to" 
                               value="{{ request('date_to') }}" placeholder="Sampai">
                    </div>
                    <div class="col-12 col-md-2">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search"></i> Cari
                            </button>
                            <a href="{{ route('landing.agenda.index') }}" class="btn btn-outline-secondary" title="Reset Filter">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
            @if(request('search') || request('date_from') || request('date_to'))
            <div class="p-2 px-3 bg-light border-top">
                <small class="text-muted">
                    <i class="bi bi-filter-circle me-1"></i>
                    Filter aktif: 
                    @if(request('search'))
                        <span class="badge text-bg-primary">Cari: {{ request('search') }}</span>
                    @endif
                    @if(request('date_from'))
                        <span class="badge text-bg-primary">Dari: {{ request('date_from') }}</span>
                    @endif
                    @if(request('date_to'))
                        <span class="badge text-bg-primary">Sampai: {{ request('date_to') }}</span>
                    @endif
                    <a href="{{ route('landing.agenda.index') }}" class="text-danger ms-2">
                        <i class="bi bi-x-circle"></i> Hapus filter
                    </a>
                </small>
            </div>
            @endif
        </div>

        <!-- Agenda List -->
        @if($agendas && $agendas->count() > 0)
            <div class="row g-4">
                @foreach($agendas as $agenda)
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="agenda-card">
                        <div class="agenda-date">
                            <span class="day">{{ $agenda->tanggal ? $agenda->tanggal->format('d') : '-' }}</span>
                            <span class="month">{{ $agenda->tanggal ? $agenda->tanggal->format('M') : '-' }}</span>
                            <span class="year">{{ $agenda->tanggal ? $agenda->tanggal->format('Y') : '-' }}</span>
                        </div>
                        <div class="agenda-content">
                            <div class="d-flex flex-wrap gap-2 mb-2">
                                <span class="badge 
                                    @if($agenda->status == 'published') badge-published
                                    @elseif($agenda->status == 'draft') badge-draft
                                    @elseif($agenda->status == 'selesai') badge-selesai
                                    @else badge-dibatalkan
                                    @endif
                                ">
                                    {{ ucfirst($agenda->status ?? 'Draft') }}
                                </span>
                                @if($agenda->tipe)
                                <span class="badge badge-type">
                                    @if($agenda->tipe == 'online')
                                        <i class="bi bi-wifi me-1"></i> Online
                                    @elseif($agenda->tipe == 'offline')
                                        <i class="bi bi-building me-1"></i> Offline
                                    @else
                                        <i class="bi bi-people me-1"></i> Hybrid
                                    @endif
                                </span>
                                @endif
                                @if($agenda->training)
                                <span class="badge badge-training">
                                    <i class="bi bi-journal-bookmark me-1"></i>
                                    {{ Str::limit($agenda->training->judul, 20) }}
                                </span>
                                @endif
                            </div>

                            <h5 class="agenda-title">{{ $agenda->judul }}</h5>
                            <p class="agenda-description">{{ Str::limit($agenda->deskripsi ?? '', 80) }}</p>

                            <div class="agenda-info">
                                <div class="info-item">
                                    <i class="bi bi-clock"></i>
                                    <span>{{ $agenda->jam_mulai }} @if($agenda->jam_selesai) - {{ $agenda->jam_selesai }} @endif</span>
                                </div>
                                @if($agenda->lokasi)
                                <div class="info-item">
                                    <i class="bi bi-geo-alt"></i>
                                    <span>{{ Str::limit($agenda->lokasi, 30) }}</span>
                                </div>
                                @endif
                                @if($agenda->link_meeting)
                                <div class="info-item">
                                    <i class="bi bi-link-45deg"></i>
                                    <a href="{{ $agenda->link_meeting }}" target="_blank" class="text-primary">
                                        Link Meeting
                                    </a>
                                </div>
                                @endif
                            </div>

                            <a href="{{ route('landing.agenda.show', $agenda->id) }}" class="btn btn-primary btn-sm w-100 mt-2">
                                <i class="bi bi-eye me-1"></i> Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach

                <!-- Pagination -->
                <div class="col-12">
                    @if($agendas->hasPages())
                    <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mt-3">
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
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="bi bi-calendar-x"></i>
                </div>
                <h5 class="empty-state-title">Belum ada agenda</h5>
                <p class="empty-state-description">
                    @if(request('search') || request('date_from') || request('date_to'))
                        Tidak ada agenda yang sesuai dengan filter yang Anda pilih.
                    @else
                        Belum ada agenda yang tersedia saat ini.
                    @endif
                </p>
                @if(request('search') || request('date_from') || request('date_to'))
                <a href="{{ route('landing.agenda.index') }}" class="btn btn-primary btn-sm mt-2">
                    <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Filter
                </a>
                @endif
            </div>
        @endif
    </div>
</section>

@push('styles')
<style>
    /* ===== AGENDA CARD ===== */
    .agenda-card {
        background: #fff;
        border-radius: 1rem;
        box-shadow: 0 1px 4px rgba(0,0,0,.06);
        transition: all 0.3s ease;
        border: 1px solid rgba(0,0,0,0.04);
        overflow: hidden;
        display: flex;
        height: 100%;
    }
    .agenda-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.08);
        border-color: transparent;
    }

    .agenda-date {
        background: linear-gradient(135deg, #4e9af1, #2a7a9a);
        color: #fff;
        padding: 1rem 0.75rem;
        text-align: center;
        min-width: 70px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .agenda-date .day {
        font-size: 2rem;
        font-weight: 700;
        line-height: 1;
    }
    .agenda-date .month {
        font-size: 0.85rem;
        text-transform: uppercase;
        font-weight: 600;
        margin-top: 0.25rem;
    }
    .agenda-date .year {
        font-size: 0.7rem;
        opacity: 0.8;
        margin-top: 0.1rem;
    }

    .agenda-content {
        padding: 1rem 1.25rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .agenda-title {
        font-weight: 700;
        font-size: 1rem;
        margin-bottom: 0.25rem;
        color: #1a2236;
    }
    .agenda-description {
        color: #6c757d;
        font-size: 0.85rem;
        margin-bottom: 0.75rem;
        flex: 1;
    }
    .agenda-info {
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
        margin-bottom: 0.75rem;
        padding: 0.5rem 0;
        border-top: 1px solid #f0f0f0;
        border-bottom: 1px solid #f0f0f0;
    }
    .info-item {
        font-size: 0.8rem;
        color: #6c757d;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .info-item i {
        width: 16px;
        color: #4e9af1;
    }

    /* ===== BADGES ===== */
    .badge-published { background: #d4edda; color: #155724; }
    .badge-draft { background: #e9ecef; color: #6c757d; }
    .badge-selesai { background: #cce5ff; color: #004085; }
    .badge-dibatalkan { background: #f8d7da; color: #721c24; }
    .badge-type { background: #e2e3e5; color: #383d41; }
    .badge-training { background: #cce5ff; color: #004085; }

    /* ===== EMPTY STATE ===== */
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        background: #fff;
        border-radius: 1rem;
    }
    .empty-state-icon {
        font-size: 3rem;
        color: #c3cad6;
        margin-bottom: 1rem;
    }
    .empty-state-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1a2236;
        margin-bottom: 0.5rem;
    }
    .empty-state-description {
        color: #8a93a3;
        font-size: 0.9rem;
        max-width: 400px;
        margin: 0 auto;
    }

    /* ===== HERO ===== */
    .hero {
        background: linear-gradient(135deg, #1a2236 0%, #2a3654 50%, #1a2236 100%);
        color: #fff;
    }
    .hero h1 {
        font-weight: 700;
    }
    .hero .text-muted {
        color: rgba(255,255,255,0.7) !important;
    }

    /* ===== PANEL ===== */
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

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .agenda-card {
            flex-direction: column;
        }
        .agenda-date {
            flex-direction: row;
            padding: 0.5rem 1rem;
            gap: 0.5rem;
            min-width: unset;
        }
        .agenda-date .day {
            font-size: 1.2rem;
        }
        .agenda-date .month {
            font-size: 0.75rem;
        }
        .agenda-date .year {
            font-size: 0.65rem;
        }
    }
</style>
@endpush
@endsection