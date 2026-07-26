@extends('layouts.landing')

@section('title', 'Pengumuman')

@section('content')


<!-- ============================================================
     PENGUMUMAN LIST
============================================================ -->
<section class="section-pad">
    <div class="container">
        <!-- Filter -->
        <div class="panel mb-4">
            <div class="panel-header">
                <div>
                    <h5 class="section-title"><i class="bi bi-funnel"></i> Filter Pengumuman</h5>
                    <p class="text-muted small mb-0">Temukan pengumuman yang Anda cari.</p>
                </div>
            </div>
            <div class="p-3">
                <form action="{{ route('landing.pengumuman.index') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-12 col-md-5">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control" name="search" 
                                   value="{{ request('search') }}" 
                                   placeholder="Cari pengumuman...">
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <input type="date" class="form-control" name="date_from" 
                               value="{{ request('date_from') }}" placeholder="Dari">
                    </div>
                    <div class="col-12 col-md-2">
                        <input type="date" class="form-control" name="date_to" 
                               value="{{ request('date_to') }}" placeholder="Sampai">
                    </div>
                    <div class="col-12 col-md-2">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search"></i> Cari
                            </button>
                            <a href="{{ route('landing.pengumuman.index') }}" class="btn btn-outline-secondary" title="Reset Filter">
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
                    <a href="{{ route('landing.pengumuman.index') }}" class="text-danger ms-2">
                        <i class="bi bi-x-circle"></i> Hapus filter
                    </a>
                </small>
            </div>
            @endif
        </div>

        <!-- Pengumuman List -->
        @if($pengumumans && $pengumumans->count() > 0)
            <div class="row g-4">
                @foreach($pengumumans as $pengumuman)
                <div class="col-12">
                    <div class="announcement-card">
                        <div class="announcement-card-body">
                            <div class="d-flex flex-wrap gap-2 mb-2">
                                <span class="badge 
                                    @if($pengumuman->status == 'published') badge-published
                                    @elseif($pengumuman->status == 'draft') badge-draft
                                    @else badge-archived
                                    @endif
                                ">
                                    {{ ucfirst($pengumuman->status ?? 'Draft') }}
                                </span>
                                @if($pengumuman->is_pinned)
                                <span class="badge badge-pinned">
                                    <i class="bi bi-pin-fill me-1"></i> Pinned
                                </span>
                                @endif
                                @if($pengumuman->training)
                                <span class="badge badge-training">
                                    <i class="bi bi-journal-bookmark me-1"></i>
                                    {{ Str::limit($pengumuman->training->judul, 30) }}
                                </span>
                                @else
                                <span class="badge badge-general">
                                    <i class="bi bi-globe me-1"></i> Umum
                                </span>
                                @endif
                            </div>

                            <h5 class="announcement-title">{{ $pengumuman->judul }}</h5>
                            <p class="announcement-excerpt">{{ Str::limit($pengumuman->konten, 150) }}</p>

                            <div class="announcement-meta">
                                <div class="d-flex flex-wrap gap-3">
                                    <span class="meta-item">
                                        <i class="bi bi-clock"></i>
                                        {{ $pengumuman->created_at ? $pengumuman->created_at->format('d/m/Y H:i') : '-' }}
                                    </span>
                                    <span class="meta-item">
                                        <i class="bi bi-person"></i>
                                        {{ $pengumuman->creator->nama ?? $pengumuman->creator->name ?? 'Admin' }}
                                    </span>
                                    @if($pengumuman->tanggal_selesai)
                                    <span class="meta-item">
                                        <i class="bi bi-calendar-check"></i>
                                        Berlaku s/d: {{ $pengumuman->tanggal_selesai->format('d/m/Y') }}
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <button class="btn btn-outline-primary btn-sm mt-2" 
                                    data-bs-toggle="collapse" 
                                    data-bs-target="#detail{{ $pengumuman->id }}">
                                <i class="bi bi-chevron-down me-1"></i> Baca Selengkapnya
                            </button>

                            <div class="collapse mt-3" id="detail{{ $pengumuman->id }}">
                                <div class="announcement-detail">
                                    <div class="announcement-content">
                                        {!! nl2br(e($pengumuman->konten)) !!}
                                    </div>
                                    @if($pengumuman->deskripsi)
                                    <div class="announcement-description">
                                        <small class="text-muted">{{ $pengumuman->deskripsi }}</small>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

                <!-- Pagination -->
                <div class="col-12">
                    @if($pengumumans->hasPages())
                    <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mt-3">
                        <p class="text-muted small mb-0">
                            Menampilkan {{ $pengumumans->firstItem() ?? 0 }} sampai {{ $pengumumans->lastItem() ?? 0 }} 
                            dari {{ $pengumumans->total() ?? 0 }} pengumuman
                        </p>
                        <nav aria-label="Pagination">
                            {{ $pengumumans->appends(request()->query())->links() }}
                        </nav>
                    </div>
                    @endif
                </div>
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="bi bi-inbox"></i>
                </div>
                <h5 class="empty-state-title">Belum ada pengumuman</h5>
                <p class="empty-state-description">
                    @if(request('search') || request('date_from') || request('date_to'))
                        Tidak ada pengumuman yang sesuai dengan filter yang Anda pilih.
                    @else
                        Belum ada pengumuman yang tersedia saat ini.
                    @endif
                </p>
                @if(request('search') || request('date_from') || request('date_to'))
                <a href="{{ route('landing.pengumuman.index') }}" class="btn btn-primary btn-sm mt-2">
                    <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Filter
                </a>
                @endif
            </div>
        @endif
    </div>
</section>

@push('styles')
<style>
    .announcement-card {
        background: #fff;
        border-radius: 1rem;
        box-shadow: 0 1px 4px rgba(0,0,0,.06);
        transition: all 0.3s ease;
        border: 1px solid rgba(0,0,0,0.04);
        overflow: hidden;
    }
    .announcement-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.08);
        border-color: transparent;
    }
    .announcement-card-body {
        padding: 1.25rem 1.5rem;
    }
    .announcement-title {
        font-weight: 700;
        font-size: 1.1rem;
        margin-bottom: 0.25rem;
        color: #1a2236;
    }
    .announcement-excerpt {
        color: #6c757d;
        font-size: 0.9rem;
        margin-bottom: 0.75rem;
        line-height: 1.6;
    }
    .announcement-meta {
        padding: 0.75rem 0;
        border-top: 1px solid #f0f0f0;
        border-bottom: 1px solid #f0f0f0;
        margin-bottom: 0.75rem;
    }
    .meta-item {
        font-size: 0.8rem;
        color: #8a93a3;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }
    .meta-item i {
        font-size: 0.9rem;
    }
    .announcement-detail {
        background: #f8f9fa;
        border-radius: 0.75rem;
        padding: 1rem;
    }
    .announcement-content {
        font-size: 0.95rem;
        line-height: 1.8;
        color: #1a2236;
    }
    .announcement-description {
        margin-top: 0.75rem;
        padding-top: 0.75rem;
        border-top: 1px solid #e9ecef;
    }

    .badge-published { background: #d4edda; color: #155724; }
    .badge-draft { background: #e9ecef; color: #6c757d; }
    .badge-archived { background: #f8d7da; color: #721c24; }
    .badge-pinned { background: #fff3cd; color: #856404; }
    .badge-training { background: #cce5ff; color: #004085; }
    .badge-general { background: #e2e3e5; color: #383d41; }

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

    .hero {
        background: linear-gradient(135deg, #1a2236 0%, #2a3654 50%, #1a2236 100%);
        color: #fff;
    }
    .hero h1 {
        font-weight: 700;
    }
</style>
@endpush
@endsection