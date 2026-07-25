@extends('layouts.peserta')

@section('title', 'Pengumuman')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-megaphone"></i></span>
        <div>
            <p class="eyebrow">Informasi</p>
            <h1 class="h3 mb-0">Pengumuman</h1>
            <p class="text-muted mb-0">Informasi terbaru seputar pelatihan dan kegiatan.</p>
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

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-primary">
                <div class="metric-top">
                    <span class="metric-label">Total Pengumuman</span>
                    <span class="metric-icon"><i class="bi bi-megaphone"></i></span>
                </div>
                <div class="metric-value">{{ $totalPengumuman ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-primary">Semua</span>
                    <span>pengumuman</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-success">
                <div class="metric-top">
                    <span class="metric-label">Pengumuman Baru</span>
                    <span class="metric-icon"><i class="bi bi-newspaper"></i></span>
                </div>
                <div class="metric-value">{{ $pengumumanBaru ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-success">7 hari terakhir</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-warning">
                <div class="metric-top">
                    <span class="metric-label">Terpopuler</span>
                    <span class="metric-icon"><i class="bi bi-fire"></i></span>
                </div>
                <div class="metric-value">{{ $pengumumanTerpopuler ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-warning">Paling banyak dibaca</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-info">
                <div class="metric-top">
                    <span class="metric-label">Pengumuman Penting</span>
                    <span class="metric-icon"><i class="bi bi-pin"></i></span>
                </div>
                <div class="metric-value">{{ $pengumumanPenting ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-info">Di-pin</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="panel mb-4">
        <div class="panel-header">
            <div>
                <h5 class="section-title"><i class="bi bi-funnel"></i> Filter Pengumuman</h5>
                <p class="text-muted small mb-0">Temukan pengumuman yang Anda cari.</p>
            </div>
        </div>
        <div class="p-3">
            <form action="{{ route('peserta.pengumuman.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-12 col-md-4">
                    <label class="form-label fw-semibold small">Cari</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Cari pengumuman...">
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label fw-semibold small">Dari Tanggal</label>
                    <input type="date" class="form-control" name="date_from" 
                           value="{{ request('date_from') }}">
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label fw-semibold small">Sampai Tanggal</label>
                    <input type="date" class="form-control" name="date_to" 
                           value="{{ request('date_to') }}">
                </div>
                <div class="col-12 col-md-2">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Cari
                        </button>
                        <a href="{{ route('peserta.pengumuman.index') }}" class="btn btn-outline-secondary" title="Reset Filter">
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
                <a href="{{ route('peserta.pengumuman.index') }}" class="text-danger ms-2">
                    <i class="bi bi-x-circle"></i> Hapus filter
                </a>
            </small>
        </div>
        @endif
    </div>

    <!-- Pengumuman Cards -->
    <div class="row g-4">
        @if($pengumumans && $pengumumans->count() > 0)
            @foreach($pengumumans as $pengumuman)
            <div class="col-12">
                <div class="announcement-card">
                    <div class="announcement-card-body">
                        <!-- Header -->
                        <div class="announcement-header">
                            <div class="d-flex align-items-start gap-3 flex-wrap">
                                <div class="announcement-icon">
                                    @if($pengumuman->training)
                                        <i class="bi bi-journal-bookmark"></i>
                                    @else
                                        <i class="bi bi-megaphone"></i>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                                        <span class="badge 
                                            @if($pengumuman->status == 'published') badge-published
                                            @elseif($pengumuman->status == 'draft') badge-draft
                                            @else badge-archived
                                            @endif
                                        ">
                                            @if($pengumuman->status == 'published')
                                                <i class="bi bi-check-circle me-1"></i> Published
                                            @elseif($pengumuman->status == 'draft')
                                                <i class="bi bi-pencil me-1"></i> Draft
                                            @else
                                                <i class="bi bi-archive me-1"></i> Archived
                                            @endif
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
                                        @if($pengumuman->kategori)
                                        <span class="badge badge-category">
                                            <i class="bi bi-tag me-1"></i>
                                            {{ $pengumuman->kategori->nama }}
                                        </span>
                                        @endif
                                    </div>
                                    <h5 class="announcement-title">{{ $pengumuman->judul }}</h5>
                                    <p class="announcement-excerpt">{{ Str::limit($pengumuman->konten, 150) }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Meta Info -->
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

                        <!-- Action Buttons -->
                        <div class="announcement-actions">
                            <button class="btn btn-outline-primary btn-sm" 
                                    data-bs-toggle="collapse" 
                                    data-bs-target="#detail{{ $pengumuman->id }}">
                                <i class="bi bi-chevron-down me-1"></i> Baca Selengkapnya
                            </button>
                        </div>

                        <!-- Detail Content -->
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
        @else
            <!-- Empty State -->
            <div class="col-12">
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
                    <a href="{{ route('peserta.pengumuman.index') }}" class="btn btn-primary btn-sm mt-2">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Filter
                    </a>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>

@push('styles')
<style>
    /* ===== METRIC CARDS ===== */
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

    /* ===== ANNOUNCEMENT CARD ===== */
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
    .announcement-header {
        margin-bottom: 0.75rem;
    }
    .announcement-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        flex-shrink: 0;
        background: #eaf1fd;
        color: #4e9af1;
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
        margin-bottom: 0;
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
    .announcement-actions {
        display: flex;
        gap: 0.5rem;
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

    /* ===== BADGES ===== */
    .badge-published {
        background: #d4edda;
        color: #155724;
    }
    .badge-draft {
        background: #e9ecef;
        color: #6c757d;
    }
    .badge-archived {
        background: #f8d7da;
        color: #721c24;
    }
    .badge-pinned {
        background: #fff3cd;
        color: #856404;
    }
    .badge-training {
        background: #cce5ff;
        color: #004085;
    }
    .badge-general {
        background: #e2e3e5;
        color: #383d41;
    }
    .badge-category {
        background: #d6d8db;
        color: #1b1e21;
    }

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

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .announcement-card-body {
            padding: 1rem;
        }
        .announcement-header .d-flex {
            flex-direction: column;
        }
        .announcement-icon {
            width: 40px;
            height: 40px;
            font-size: 1.1rem;
        }
        .metric-value {
            font-size: 1.2rem;
        }
        .panel-header {
            flex-direction: column;
            align-items: flex-start;
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
    // SEARCH WITH ENTER KEY
    // ============================================================
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                this.closest('form').submit();
            }
        });
    }

    // ============================================================
    // AUTO SUBMIT ON DATE CHANGE
    // ============================================================
    document.querySelector('input[name="date_from"]')?.addEventListener('change', function() {
        this.closest('form').submit();
    });
    document.querySelector('input[name="date_to"]')?.addEventListener('change', function() {
        this.closest('form').submit();
    });

    // ============================================================
    // ANIMATE CARDS ON SCROLL
    // ============================================================
    const cards = document.querySelectorAll('.announcement-card');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }, index * 100);
            }
        });
    }, { threshold: 0.1 });

    cards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'all 0.5s ease';
        observer.observe(card);
    });

    // ============================================================
    // BUTTON READ MORE - TOGGLE ICON
    // ============================================================
    document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const icon = this.querySelector('i');
            if (icon) {
                icon.classList.toggle('bi-chevron-down');
                icon.classList.toggle('bi-chevron-up');
            }
            const text = this.innerHTML;
            if (text.includes('Baca Selengkapnya')) {
                this.innerHTML = text.replace('Baca Selengkapnya', 'Tutup');
            } else {
                this.innerHTML = text.replace('Tutup', 'Baca Selengkapnya');
            }
            // Tambahkan icon kembali
            const iconEl = this.querySelector('i');
            if (iconEl) {
                this.innerHTML = `<i class="bi ${iconEl.classList.contains('bi-chevron-up') ? 'bi-chevron-up' : 'bi-chevron-down'} me-1"></i> ${this.textContent.trim()}`;
            }
        });
    });
});
</script>
@endpush
@endsection