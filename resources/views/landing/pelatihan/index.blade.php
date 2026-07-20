@extends('layouts.landing')

@section('title', 'Daftar Pelatihan')

@section('content')

<!-- ============================================================
     FILTER SECTION
============================================================ -->
<section class="section-pad" style="padding-top: 2rem;">
    <div class="container">
        <div class="panel">
            <div class="panel-header">
                <h5 class="section-title">
                    <i class="bi bi-funnel me-2"></i> Filter Pelatihan
                </h5>
            </div>
            <div class="p-4">
                <form action="{{ route('landing.pelatihan.index') }}" method="GET" class="row g-3">
                    <!-- Search -->
                    <div class="col-12 col-md-4">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control" name="search" 
                                   placeholder="Cari pelatihan..." value="{{ request('search') }}">
                        </div>
                    </div>

                    <!-- Kategori -->
                    <div class="col-12 col-md-3">
                        <select class="form-select" name="kategori_id">
                            <option value="">Semua Kategori</option>
                            @foreach($kategoris ?? [] as $kategori)
                            <option value="{{ $kategori->id }}" {{ request('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                {{ $kategori->nama }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tipe -->
                    <div class="col-12 col-md-3">
                        <select class="form-select" name="tipe">
                            <option value="">Semua Tipe</option>
                            <option value="online" {{ request('tipe') == 'online' ? 'selected' : '' }}>🖥️ Online</option>
                            <option value="offline" {{ request('tipe') == 'offline' ? 'selected' : '' }}>🏢 Offline</option>
                            <option value="hybrid" {{ request('tipe') == 'hybrid' ? 'selected' : '' }}>🔄 Hybrid</option>
                        </select>
                    </div>

                    <!-- Submit -->
                    <div class="col-12 col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search me-1"></i> Filter
                        </button>
                    </div>
                </form>

                @if(request('search') || request('kategori_id') || request('tipe'))
                <div class="mt-3">
                    <a href="{{ route('landing.pelatihan.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Filter
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     TRAINING LIST
============================================================ -->
<section class="section-pad" style="padding-top: 0;">
    <div class="container">
        @if($trainings && $trainings->count() > 0)
            <div class="row g-4">
                @foreach($trainings as $training)
                <div class="col-md-6 col-lg-4">
                    <div class="card-feature p-0 overflow-hidden h-100">
                        <!-- Image -->
                       @if($training->gambar)
                        <img src="{{ asset('storage/' . $training->gambar) }}" 
                            alt="{{ $training->judul }}" 
                            class="img-fluid w-100" 
                            style="height: 200px; object-fit: cover;">
                        @else
                        <div class="bg-light text-center py-5" style="height: 200px;">
                            <i class="bi bi-image fs-1 text-muted"></i>
                            <p class="text-muted small mb-0">Tidak ada gambar</p>
                        </div>
                        @endif

                        <div class="p-3">
                            <!-- Badge -->
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge 
                                    @if($training->status == 'published') 
                                        badge-published
                                    @elseif($training->status == 'berjalan') 
                                        badge-berjalan
                                    @elseif($training->status == 'selesai') 
                                        badge-selesai
                                    @else 
                                        badge-draft
                                    @endif
                                ">
                                    {{ $training->status_label }}
                                </span>
                                <span class="badge bg-primary">
                                    {{ $training->tipe_label }}
                                </span>
                            </div>

                            <!-- Title -->
                            <h5 class="fw-bold mb-2 text-truncate" title="{{ $training->judul }}">
                                {{ $training->judul }}
                            </h5>

                            <!-- Category -->
                            @if($training->kategori)
                            <p class="text-muted small mb-2">
                                <i class="bi bi-tag me-1"></i>
                                {{ $training->kategori->nama }}
                            </p>
                            @endif

                            <!-- Description -->
                            <p class="text-muted small mb-3" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                {{ Str::limit($training->deskripsi, 100) }}
                            </p>

                            <!-- Info -->
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                <span class="text-muted small">
                                    <i class="bi bi-calendar me-1"></i>
                                    @if($training->tanggal_mulai)
                                        {{ $training->tanggal_mulai->format('d/m/Y') }}
                                    @else
                                        TBD
                                    @endif
                                </span>
                                <span class="text-muted small">
                                    <i class="bi bi-people me-1"></i>
                                    {{ $training->participants_count ?? 0 }} peserta
                                </span>
                                <span class="text-muted small">
                                    <i class="bi bi-clock me-1"></i>
                                    {{ $training->durasi ?? '-' }} jam
                                </span>
                            </div>

                            <!-- Actions -->
                            <div class="d-flex gap-2">
                                <a href="{{ route('landing.pelatihan.detail', $training->id) }}" 
                                   class="btn btn-primary btn-sm flex-grow-1">
                                    <i class="bi bi-eye me-1"></i> Detail
                                </a>
                                @auth
                                    @if(auth()->user()->role === 'peserta')
                                        @if(!$training->isEnrolled())
                                            <form action="{{ route('peserta.trainings.enroll', $training->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm" 
                                                        onclick="return confirm('Yakin ingin mendaftar pelatihan ini?')">
                                                    <i class="bi bi-plus-circle"></i> Daftar
                                                </button>
                                            </form>
                                        @else
                                            <span class="btn btn-sm btn-outline-success disabled">
                                                <i class="bi bi-check-circle"></i> Terdaftar
                                            </span>
                                        @endif
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-box-arrow-in-right me-1"></i> Login
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($trainings->hasPages())
            <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mt-4">
                <p class="text-muted small mb-0">
                    Menampilkan {{ $trainings->firstItem() ?? 0 }} sampai {{ $trainings->lastItem() ?? 0 }} 
                    dari {{ $trainings->total() ?? 0 }} pelatihan
                </p>
                <nav aria-label="Pelatihan pagination">
                    {{ $trainings->links() }}
                </nav>
            </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="panel">
                <div class="text-center py-5">
                    <div class="text-muted">
                        <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                        <p class="h5">Belum ada pelatihan</p>
                        <p class="small">
                            @if(request('search'))
                                Tidak ada pelatihan yang sesuai dengan pencarian "{{ request('search') }}".
                            @else
                                Belum ada pelatihan yang tersedia saat ini.
                            @endif
                        </p>
                        @if(request('search') || request('kategori_id') || request('tipe'))
                        <a href="{{ route('landing.pelatihan.index') }}" class="btn btn-primary btn-sm mt-2">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Filter
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>

<!-- ============================================================
     CTA SECTION
============================================================ -->
<section class="section-pad" style="padding-top: 0;">
    <div class="container">
        <div class="cta-section p-5 text-center">
            <h2 class="mb-3">Butuh Bantuan Memilih Pelatihan?</h2>
            <p class="mx-auto" style="max-width: 600px;">
                Tim kami siap membantu Anda memilih pelatihan yang paling sesuai 
                dengan kebutuhan pengembangan kompetensi.
            </p>
            <div class="d-flex flex-wrap gap-3 justify-content-center mt-4">
                <a href="{{ route('landing.kontak.index') }}" class="btn btn-primary btn-lg px-5">
                    <i class="bi bi-envelope me-2"></i> Hubungi Kami
                </a>
                @auth
                    <a href="{{ auth()->user()->role === 'peserta' ? route('peserta.dashboard') : route('admin.dashboard') }}" 
                       class="btn btn-outline-light btn-lg px-5">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                @else
                    <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg px-5">
                        <i class="bi bi-person-plus me-2"></i> Daftar Sekarang
                    </a>
                @endauth
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
    .badge-published { background: #d1e7dd; color: #0a7344; }
    .badge-berjalan { background: #cff4fc; color: #0c5460; }
    .badge-selesai { background: #d1ecf1; color: #0c5460; }
    .badge-draft { background: #e9ecef; color: #495057; }
    
    .card-feature {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .card-feature:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 40px rgba(0,0,0,0.12);
    }
    
    .panel {
        background: #fff;
        border-radius: 1rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
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
    
    .text-truncate {
        max-width: 100%;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .hero {
        background: linear-gradient(135deg, #1a2236 0%, #2a3654 50%, #1a2236 100%);
        color: #fff;
    }
    
    .cta-section {
        background: linear-gradient(135deg, #1a2236, #2a3654);
        color: #fff;
        border-radius: 1.5rem;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ============================================================
    // FILTER AUTO SUBMIT
    // ============================================================
    const filterSelects = document.querySelectorAll('select[name="kategori_id"], select[name="tipe"]');
    filterSelects.forEach(function(select) {
        select.addEventListener('change', function() {
            this.closest('form').submit();
        });
    });

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
    // CONFIRM ENROLLMENT
    // ============================================================
    document.querySelectorAll('form[action*="enroll"]').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            if (!confirm('Yakin ingin mendaftar pelatihan ini?')) {
                e.preventDefault();
            }
        });
    });
});
</script>
@endpush
@endsection