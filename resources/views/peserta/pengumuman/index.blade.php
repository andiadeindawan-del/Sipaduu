@extends('layouts.peserta')

@section('title', 'Pengumuman')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-megaphone"></i></span>
        <div>
            <p class="eyebrow">Informasi</p>
            <h1 class="h3 mb-0">Pengumuman</h1>
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
    <div class="p-3">
        <form action="{{ route('peserta.pengumuman.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-12 col-md-6">
                <label class="form-label fw-semibold small">Cari</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control" name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Cari pengumuman...">
                </div>
            </div>
            <div class="col-12 col-md-3">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i> Cari
                </button>
            </div>
            <div class="col-12 col-md-3">
                <a href="{{ route('peserta.pengumuman.index') }}" class="btn btn-outline-secondary w-100" title="Reset Filter">
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
            <a href="{{ route('peserta.pengumuman.index') }}" class="text-danger ms-2">
                <i class="bi bi-x-circle"></i> Hapus filter
            </a>
        </small>
    </div>
    @endif
</div>
```

    <!-- Pengumuman Grid Cards - SEGI EMPAT -->
    @if($pengumumans && $pengumumans->count() > 0)
        <div class="row g-4">
            @foreach($pengumumans as $pengumuman)
            <div class="col-12 col-md-6 col-lg-4">
                <div class="announcement-card">
                    <!-- Gambar -->
                    <div class="announcement-image-wrapper">
                        @if($pengumuman->gambar)
                            <img src="{{ asset('storage/' . $pengumuman->gambar) }}" 
                                 alt="{{ $pengumuman->judul }}" 
                                 class="announcement-image">
                        @else
                            <div class="announcement-image-placeholder">
                                <i class="bi bi-image"></i>
                            </div>
                        @endif
                        <!-- Badge Status di atas gambar -->
                        <div class="announcement-badges">
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
                        </div>
                    </div>

                    <!-- Konten -->
                    <div class="announcement-content">
                        <div class="d-flex flex-wrap gap-1 mb-2">
                            @if($pengumuman->training)
                            <span class="badge badge-training">
                                <i class="bi bi-journal-bookmark me-1"></i>
                                {{ Str::limit($pengumuman->training->judul, 20) }}
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
                        <p class="announcement-excerpt">{{ Str::limit($pengumuman->konten, 120) }}</p>

                        <div class="announcement-meta">
                            <div class="d-flex flex-wrap gap-2">
                                <span class="meta-item">
                                    <i class="bi bi-clock"></i>
                                    {{ $pengumuman->created_at ? $pengumuman->created_at->format('d/m/Y') : '-' }}
                                </span>
                                <span class="meta-item">
                                    <i class="bi bi-person"></i>
                                    {{ $pengumuman->creator->nama ?? $pengumuman->creator->name ?? 'Admin' }}
                                </span>
                                @if($pengumuman->tanggal_selesai)
                                <span class="meta-item">
                                    <i class="bi bi-calendar-check"></i>
                                    s/d {{ $pengumuman->tanggal_selesai->format('d/m/Y') }}
                                </span>
                                @endif
                            </div>
                        </div>

                        <button class="btn btn-outline-primary btn-sm w-100 mt-2" 
                                data-bs-toggle="collapse" 
                                data-bs-target="#detail{{ $pengumuman->id }}">
                            <i class="bi bi-chevron-down me-1"></i> Baca Selengkapnya
                        </button>

                        <div class="collapse mt-2" id="detail{{ $pengumuman->id }}">
                            <div class="announcement-detail">
                                <div class="announcement-content-text">
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
        </div>

        <!-- Pagination -->
        <div class="col-12 mt-4">
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
    @endif
</div>

@push('styles')
<style>
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

    /* ============================================================
       PANEL
    ============================================================ */
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

    /* ============================================================
       ANNOUNCEMENT CARD - SEGI EMPAT
    ============================================================ */
    .announcement-card {
        background: #fff;
        border-radius: 1rem;
        box-shadow: 0 1px 4px rgba(0,0,0,.06);
        transition: all 0.3s ease;
        border: 1px solid rgba(0,0,0,0.04);
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .announcement-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 40px rgba(0,0,0,0.1);
        border-color: transparent;
    }

    /* ============================================================
       ANNOUNCEMENT IMAGE
    ============================================================ */
    .announcement-image-wrapper {
        position: relative;
        height: 200px;
        overflow: hidden;
        flex-shrink: 0;
        background: #f8fafc;
    }
    .announcement-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .announcement-card:hover .announcement-image {
        transform: scale(1.05);
    }
    .announcement-image-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        color: #c3cad6;
        background: linear-gradient(135deg, #f8fafc, #e8f4f8);
    }

    /* ============================================================
       ANNOUNCEMENT BADGES
    ============================================================ */
    .announcement-badges {
        position: absolute;
        top: 12px;
        left: 12px;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    .announcement-badges .badge {
        font-weight: 500;
        padding: 0.3rem 0.7rem;
        font-size: 0.7rem;
        border-radius: 6px;
    }

    /* ============================================================
       BADGES
    ============================================================ */
    .badge-published { background: #d4edda; color: #155724; }
    .badge-draft { background: #e9ecef; color: #6c757d; }
    .badge-archived { background: #f8d7da; color: #721c24; }
    .badge-pinned { background: #fff3cd; color: #856404; }
    .badge-training { background: #cce5ff; color: #004085; }
    .badge-general { background: #e2e3e5; color: #383d41; }
    .badge-category { background: #d6d8db; color: #1b1e21; }

    /* ============================================================
       ANNOUNCEMENT CONTENT
    ============================================================ */
    .announcement-content {
        padding: 1rem 1.25rem 1.25rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .announcement-title {
        font-weight: 700;
        font-size: 1rem;
        margin-bottom: 0.25rem;
        color: #1a2236;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .announcement-excerpt {
        color: #6c757d;
        font-size: 0.85rem;
        margin-bottom: 0.75rem;
        line-height: 1.5;
        flex: 1;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .announcement-meta {
        padding: 0.5rem 0;
        border-top: 1px solid #f0f0f0;
        border-bottom: 1px solid #f0f0f0;
        margin-bottom: 0.75rem;
    }
    .meta-item {
        font-size: 0.75rem;
        color: #8a93a3;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }
    .meta-item i {
        font-size: 0.8rem;
    }

    .announcement-detail {
        background: #f8f9fa;
        border-radius: 0.5rem;
        padding: 0.75rem;
        font-size: 0.85rem;
        line-height: 1.7;
        color: #1a2236;
    }
    .announcement-content-text {
        white-space: pre-wrap;
    }
    .announcement-description {
        margin-top: 0.5rem;
        padding-top: 0.5rem;
        border-top: 1px solid #e9ecef;
    }

    /* ============================================================
       EMPTY STATE
    ============================================================ */
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        background: #fff;
        border-radius: 1rem;
        border: 1px solid rgba(0,0,0,0.04);
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

    /* ============================================================
       RESPONSIVE
    ============================================================ */
    @media (max-width: 768px) {
        .announcement-image-wrapper {
            height: 160px;
        }
        .announcement-content {
            padding: 0.75rem 1rem 1rem;
        }
        .announcement-title {
            font-size: 0.9rem;
        }
        .announcement-excerpt {
            font-size: 0.8rem;
            -webkit-line-clamp: 2;
        }
        .meta-item {
            font-size: 0.7rem;
        }
        .panel-header {
            flex-direction: column;
            align-items: flex-start;
        }
        .metric-value {
            font-size: 1.2rem;
        }
    }

    @media (max-width: 576px) {
        .announcement-image-wrapper {
            height: 140px;
        }
        .announcement-badges .badge {
            font-size: 0.6rem;
            padding: 0.2rem 0.5rem;
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
    // TOGGLE BUTTON ICON
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
                const iconClass = iconEl.classList.contains('bi-chevron-up') ? 'bi-chevron-up' : 'bi-chevron-down';
                this.innerHTML = `<i class="bi ${iconClass} me-1"></i> ${this.textContent.trim()}`;
            }
        });
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
});
</script>
@endpush
@endsection