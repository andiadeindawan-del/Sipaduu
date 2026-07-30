@extends('layouts.landing')

@section('title', $agenda->judul ?? 'Detail Agenda')

@section('content')


<!-- ============================================================
     AGENDA DETAIL
============================================================ -->
<section class="section-pad">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <!-- Main Card -->
                <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                    <!-- Header -->
                    <div class="card-header bg-primary text-white p-4">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <div>
                                <h4 class="mb-1 fw-bold">{{ $agenda->judul }}</h4>
                                <div class="d-flex flex-wrap gap-2">
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
                                        {{ Str::limit($agenda->training->judul, 30) }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="agenda-date-badge">
                                    <span class="day">{{ $agenda->tanggal ? $agenda->tanggal->format('d') : '-' }}</span>
                                    <span class="month">{{ $agenda->tanggal ? $agenda->tanggal->format('M') : '-' }}</span>
                                    <span class="year">{{ $agenda->tanggal ? $agenda->tanggal->format('Y') : '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Body -->
                    <div class="card-body p-4">
                        <!-- Deskripsi -->
                        @if($agenda->deskripsi)
                        <div class="mb-4">
                            <h6 class="fw-bold"><i class="bi bi-file-text me-2 text-primary"></i>Deskripsi</h6>
                            <p class="text-muted">{{ $agenda->deskripsi }}</p>
                        </div>
                        @endif

                        <!-- Informasi Detail -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <i class="bi bi-calendar"></i>
                                    <div>
                                        <label class="info-label">Tanggal</label>
                                        <p class="info-value">{{ $agenda->tanggal ? $agenda->tanggal->format('d F Y') : '-' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <i class="bi bi-clock"></i>
                                    <div>
                                        <label class="info-label">Waktu</label>
                                        <p class="info-value">
                                            {{ $agenda->jam_mulai }}
                                            @if($agenda->jam_selesai)
                                                - {{ $agenda->jam_selesai }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <i class="bi bi-geo-alt"></i>
                                    <div>
                                        <label class="info-label">Lokasi</label>
                                        <p class="info-value">{{ $agenda->lokasi ?? 'Online' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <i class="bi bi-tag"></i>
                                    <div>
                                        <label class="info-label">Tipe</label>
                                        <p class="info-value">
                                            @if($agenda->tipe == 'online')
                                                🖥️ Online
                                            @elseif($agenda->tipe == 'offline')
                                                🏢 Offline
                                            @else
                                                🔄 Hybrid
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Link Meeting -->
                        @if($agenda->link_meeting)
                        <div class="alert alert-info d-flex align-items-center gap-3">
                            <i class="bi bi-link-45deg fs-4"></i>
                            <div>
                                <h6 class="mb-0">Link Meeting</h6>
                                <a href="{{ $agenda->link_meeting }}" target="_blank" class="text-primary">
                                    {{ $agenda->link_meeting }}
                                    <i class="bi bi-box-arrow-up-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                        @endif

                        <!-- Training Info -->
                        @if($agenda->training)
                        <div class="alert alert-light border">
                            <div class="d-flex align-items-center gap-3">
                                <i class="bi bi-journal-bookmark fs-4 text-primary"></i>
                                <div>
                                    <h6 class="mb-0">Terkait Pelatihan</h6>
                                    <a href="{{ route('landing.pelatihan.detail', $agenda->training->id) }}" class="text-primary">
                                        {{ $agenda->training->judul }}
                                        <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Meta Info -->
                        <div class="d-flex flex-wrap gap-3 text-muted small pt-3 border-top">
                            <span>
                                <i class="bi bi-person me-1"></i>
                                Dibuat oleh: {{ $agenda->creator->nama ?? $agenda->creator->name ?? 'Admin' }}
                            </span>
                            <span>
                                <i class="bi bi-clock me-1"></i>
                                Dibuat: {{ $agenda->created_at ? $agenda->created_at->format('d/m/Y H:i') : '-' }}
                            </span>
                            @if($agenda->updated_at && $agenda->updated_at != $agenda->created_at)
                            <span>
                                <i class="bi bi-pencil me-1"></i>
                                Diperbarui: {{ $agenda->updated_at->format('d/m/Y H:i') }}
                            </span>
                            @endif
                        </div>

                        <!-- Actions -->
                        <div class="d-flex gap-2 flex-wrap mt-4 pt-3 border-top">
                            <a href="{{ route('landing.agenda.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Kembali ke Agenda
                            </a>
                            @if($agenda->link_meeting)
                            <a href="{{ $agenda->link_meeting }}" target="_blank" class="btn btn-primary">
                                <i class="bi bi-box-arrow-up-right me-1"></i> Buka Meeting
                            </a>
                            @endif
                            <button class="btn btn-outline-primary" onclick="window.print()">
                                <i class="bi bi-printer me-1"></i> Cetak
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Agenda Lainnya -->
                @php
                    $relatedAgendas = App\Models\Agenda::where('status', 'published')
                        ->where('id', '!=', $agenda->id)
                        ->whereDate('tanggal', '>=', now())
                        ->orderBy('tanggal', 'asc')
                        ->limit(3)
                        ->get();
                @endphp
                @if($relatedAgendas->count() > 0)
                <div class="mt-4">
                    <h5 class="fw-bold mb-3">Agenda Lainnya</h5>
                    <div class="row g-3">
                        @foreach($relatedAgendas as $related)
                        <div class="col-md-4">
                            <div class="card h-100 shadow-sm border-0 rounded-3">
                                <div class="card-body">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <span class="badge bg-primary">{{ $related->tanggal ? $related->tanggal->format('d M') : '-' }}</span>
                                        <span class="badge bg-secondary">{{ $related->jam_mulai }}</span>
                                    </div>
                                    <h6 class="fw-bold mb-1">{{ Str::limit($related->judul, 40) }}</h6>
                                    <p class="text-muted small">{{ Str::limit($related->deskripsi ?? '', 60) }}</p>
                                    <a href="{{ route('landing.agenda.show', $related->id) }}" class="btn btn-sm btn-outline-primary">
                                        Lihat Detail <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
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

    .agenda-date-badge {
        background: rgba(255,255,255,0.2);
        border-radius: 12px;
        padding: 0.5rem 1rem;
        text-align: center;
        min-width: 70px;
        color: #fff;
    }
    .agenda-date-badge .day {
        font-size: 1.5rem;
        font-weight: 700;
        line-height: 1;
        display: block;
    }
    .agenda-date-badge .month {
        font-size: 0.7rem;
        text-transform: uppercase;
        font-weight: 600;
        display: block;
    }
    .agenda-date-badge .year {
        font-size: 0.6rem;
        opacity: 0.8;
        display: block;
    }

    .info-item {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 0.75rem;
        background: #f8fafc;
        border-radius: 0.5rem;
        border: 1px solid #f0f0f0;
    }
    .info-item i {
        font-size: 1.2rem;
        color: #4e9af1;
        margin-top: 0.1rem;
        flex-shrink: 0;
    }
    .info-label {
        font-size: 0.65rem;
        color: #8a93a3;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        display: block;
        margin-bottom: 0.1rem;
    }
    .info-value {
        font-weight: 500;
        color: #1a2236;
        margin-bottom: 0;
        font-size: 0.9rem;
    }

    .badge-published { background: #d4edda; color: #155724; }
    .badge-draft { background: #e9ecef; color: #6c757d; }
    .badge-selesai { background: #cce5ff; color: #004085; }
    .badge-dibatalkan { background: #f8d7da; color: #721c24; }
    .badge-type { background: #e2e3e5; color: #383d41; }
    .badge-training { background: #cce5ff; color: #004085; }

    .breadcrumb {
        background: transparent;
        padding: 0;
    }
    .breadcrumb-item a {
        text-decoration: none;
        color: #6c757d;
    }
    .breadcrumb-item a:hover {
        color: #4e9af1;
    }
    .breadcrumb-item.active {
        color: #4e9af1;
        font-weight: 500;
    }

    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(0,0,0,0.08) !important;
    }

    @media (max-width: 768px) {
        .agenda-date-badge {
            min-width: 60px;
            padding: 0.3rem 0.8rem;
        }
        .agenda-date-badge .day {
            font-size: 1.2rem;
        }
        .info-item {
            padding: 0.5rem;
        }
    }
</style>
@endpush
@endsection