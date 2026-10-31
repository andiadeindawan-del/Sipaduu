@extends('layouts.landing')

@section('title', 'Portal Layanan Pelatihan SDM — SIPADU')

@section('content')
@php
    // Query data pengumuman & webinar tanpa mengubah controller
    $latestPengumuman = \App\Models\Pengumuman::published()
        ->orderBy('is_pinned', 'desc')
        ->orderBy('created_at', 'desc')
        ->take(3)
        ->get();

    $upcomingAgendas = \App\Models\Agenda::whereIn('status', ['published', 'selesai'])
        ->orderBy('tanggal', 'desc')
        ->take(3)
        ->get();

    $featuredTraining = (isset($popularTrainings) && $popularTrainings->count() > 0) ? $popularTrainings->first() : null;
    $otherTrainings = (isset($popularTrainings) && $popularTrainings->count() > 1) ? $popularTrainings->slice(1, 4) : collect();
@endphp

<!-- ============================================================
     1. HERO SECTION - MODERN WEB PORTAL HEADER (COMPACT)
============================================================ -->
<section class="portal-hero">
    <div class="portal-hero-bg">
        <div class="portal-hero-img" style="background-image: url('{{ asset('assets/images/pelatihan.jpg') }}');"></div>
        <div class="portal-hero-overlay"></div>
    </div>

    <div class="container position-relative">
        <div class="row align-items-center justify-content-center">
            <div class="col-12 col-xl-10 text-center">
                <!-- Badges / Eyebrow -->
                <div class="portal-hero-eyebrow d-inline-flex align-items-center gap-2 mb-3">
                   
                    <span class="portal-hero-instansi text-white-50 small">
                        Dinas Koperasi UKM, Perindustrian dan Perdagangan Provinsi Sulawesi Barat
                    </span>
                </div>

                <!-- Hero Heading -->
                <h1 class="portal-hero-title mb-2">
                    Sistem Informasi Pengembangan SDM Usaha
                </h1>
                <p class="portal-hero-subtitle mx-auto mb-4">
                    Pusat akselerasi kompetensi SDM usaha, kewirausahaan, dan standardisasi keahlian bagi tenaga kerja serta pelaku UMKM Sulawesi Barat.
                </p>

                <!-- Search Bar Interaktif (Inspirasi Portal Microsoft Start) -->
                <div class="portal-search-wrapper mx-auto mb-3">
                    <form action="{{ route('landing.pelatihan.index') }}" method="GET" class="portal-search-form d-flex align-items-center">
                        <span class="portal-search-icon">
                            <i class="bi bi-search text-primary"></i>
                        </span>
                        <input type="text" 
                               name="search" 
                               class="form-control portal-search-input" 
                               placeholder="Cari pelatihan, topik, atau kompetensi keahlian..." 
                               value="{{ request('search') }}"
                               aria-label="Cari pelatihan">
                        <button type="submit" class="btn btn-primary portal-search-btn">
                            <span class="d-none d-sm-inline">Cari Pelatihan</span>
                            <i class="bi bi-arrow-right d-sm-none"></i>
                        </button>
                    </form>
                </div>

                <!-- Trending / Quick Keywords -->
                <div class="portal-search-tags d-flex align-items-center justify-content-center flex-wrap gap-2 text-white-50 small">
                    <span class="fw-semibold text-white"><i class="bi bi-fire text-warning me-1"></i> Populer:</span>
                    <a href="{{ route('landing.pelatihan.index', ['search' => 'Batik']) }}" class="portal-tag-pill">Pembuatan Batik</a>
                    <a href="{{ route('landing.pelatihan.index', ['search' => 'Keuangan']) }}" class="portal-tag-pill">Manajemen Keuangan</a>
                    <a href="{{ route('landing.pelatihan.index', ['search' => 'Wirausaha']) }}" class="portal-tag-pill">Kewirausahaan Baru</a>
                    <a href="{{ route('landing.pelatihan.index', ['search' => 'Pemasaran']) }}" class="portal-tag-pill">Digital Marketing</a>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- ============================================================
     3. PORTAL METRICS STRIP (Statistik Sistem Terintegrasi)
============================================================ -->
<section class="portal-stats-section py-4">
    <div class="container">
        <div class="portal-stats-container p-3 p-md-4 rounded-4 shadow-sm bg-white border">
            <div class="row align-items-center g-3 text-center">
                <!-- Stat 1: Peserta Aktif -->
                <div class="col-6 col-lg-3">
                    <div class="portal-stat-box">
                        <div class="portal-stat-icon text-primary mb-1">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div class="portal-stat-number" data-count="{{ $totalParticipants ?? 0 }}">
                            <span class="counter">0</span>
                        </div>
                        <div class="portal-stat-label">Peserta Terdaftar</div>
                    </div>
                </div>

                <!-- Stat 2: Pelatihan -->
                <div class="col-6 col-lg-3">
                    <div class="portal-stat-box border-start-lg">
                        <div class="portal-stat-icon text-warning mb-1">
                            <i class="bi bi-journal-bookmark-fill"></i>
                        </div>
                        <div class="portal-stat-number" data-count="{{ $totalTrainings ?? 0 }}">
                            <span class="counter">0</span>
                        </div>
                        <div class="portal-stat-label">Program Pelatihan</div>
                    </div>
                </div>

                <!-- Stat 3: Sertifikat -->
                <div class="col-6 col-lg-3">
                    <div class="portal-stat-box border-start-lg">
                        <div class="portal-stat-icon text-success mb-1">
                            <i class="bi bi-award-fill"></i>
                        </div>
                        <div class="portal-stat-number" data-count="{{ $totalCertificates ?? 0 }}">
                            <span class="counter">0</span>
                        </div>
                        <div class="portal-stat-label">Sertifikat Terbit</div>
                    </div>
                </div>

                <!-- Stat 4: Materi & Modul -->
                <div class="col-6 col-lg-3">
                    <div class="portal-stat-box border-start-lg">
                        <div class="portal-stat-icon text-info mb-1">
                            <i class="bi bi-file-earmark-slides-fill"></i>
                        </div>
                        <div class="portal-stat-number" data-count="{{ $totalMateri ?? 0 }}">
                            <span class="counter">0</span>
                        </div>
                        <div class="portal-stat-label">Modul &amp; Materi</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     4. MAIN PORTAL CONTENT (Multi-Column Grid ala Microsoft Start)
============================================================ -->
<section class="portal-content-section pb-5">
    <div class="container">
        <div class="row g-4">
            <!-- ============================================================
                 KOLOM KIRI: MAIN FEED (PELATIHAN & PENGUMUMAN) - 8 Kolom
            ============================================================ -->
            <div class="col-12 col-lg-8">
                <!-- Section Header -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <span class="portal-section-badge bg-primary"></span>
                        <h4 class="portal-section-title mb-0">Pelatihan Unggulan &amp; Terbaru</h4>
                    </div>
                    <a href="{{ route('landing.pelatihan.index') }}" class="portal-link-all">
                        Semua Pelatihan <i class="bi bi-chevron-right ms-1"></i>
                    </a>
                </div>

                <!-- 4.1 HEADLINE CARD: Pelatihan Sorotan Teratas -->
                @if($featuredTraining)
                <div class="portal-featured-card mb-4 rounded-4 overflow-hidden shadow-sm border bg-white">
                    <div class="row g-0 align-items-center">
                        <div class="col-md-6 position-relative">
                            @if($featuredTraining->gambar)
                                <img src="{{ asset('storage/' . $featuredTraining->gambar) }}" 
                                     alt="{{ $featuredTraining->judul }}" 
                                     class="portal-featured-img img-fluid w-100">
                            @else
                                <div class="portal-featured-placeholder d-flex align-items-center justify-content-center">
                                    <i class="bi bi-journal-bookmark fs-1 text-muted"></i>
                                </div>
                            @endif
                            <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-3 px-3 py-1.5 shadow-sm rounded-pill fw-bold">
                                <i class="bi bi-star-fill me-1"></i> Pelatihan Unggulan
                            </span>
                        </div>
                        <div class="col-md-6 p-4">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2.5 py-1">
                                    {{ $featuredTraining->kategori->nama ?? 'Umum' }}
                                </span>
                                <span class="badge bg-secondary-subtle text-secondary rounded-pill px-2.5 py-1 text-uppercase">
                                    {{ $featuredTraining->tipe ?? 'Online' }}
                                </span>
                            </div>
                            <h5 class="fw-bold text-dark mb-2 line-clamp-2">
                                <a href="{{ route('landing.pelatihan.detail', $featuredTraining->id) }}" class="text-decoration-none text-dark hover-primary">
                                    {{ $featuredTraining->judul }}
                                </a>
                            </h5>
                            <p class="text-muted small mb-3 line-clamp-3">
                                {{ Str::limit(strip_tags($featuredTraining->deskripsi), 130) }}
                            </p>
                            <div class="d-flex align-items-center gap-3 text-muted small mb-3">
                                <span><i class="bi bi-clock text-primary me-1"></i> {{ $featuredTraining->durasi ?? '-' }} Jam</span>
                                <span><i class="bi bi-people text-success me-1"></i> {{ $featuredTraining->participants_count ?? 0 }} Peserta</span>
                                <span><i class="bi bi-award text-warning me-1"></i> Bersertifikat</span>
                            </div>
                            <a href="{{ route('landing.pelatihan.detail', $featuredTraining->id) }}" class="btn btn-primary w-100 fw-semibold rounded-3 py-2">
                                Detail &amp; Pendaftaran <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                <!-- 4.2 GRID PELATIHAN LAINNYA (2 Kolom Kartu) -->
                @if($otherTrainings->count() > 0)
                <div class="row g-3 mb-4">
                    @foreach($otherTrainings as $training)
                    <div class="col-12 col-md-6">
                        <div class="portal-grid-card h-100 rounded-4 overflow-hidden shadow-sm border bg-white d-flex flex-column">
                            <div class="position-relative">
                                @if($training->gambar)
                                    <img src="{{ asset('storage/' . $training->gambar) }}" 
                                         alt="{{ $training->judul }}" 
                                         class="portal-grid-img w-100">
                                @else
                                    <div class="portal-grid-placeholder d-flex align-items-center justify-content-center">
                                        <i class="bi bi-journal-bookmark fs-2 text-muted"></i>
                                    </div>
                                @endif
                                <div class="position-absolute bottom-0 start-0 m-2.5">
                                    <span class="badge bg-dark bg-opacity-75 text-white rounded-pill px-2.5 py-1 small">
                                        {{ $training->kategori->nama ?? 'Umum' }}
                                    </span>
                                </div>
                            </div>
                            <div class="p-3 d-flex flex-column flex-grow-1">
                                <h6 class="fw-bold mb-2 line-clamp-2">
                                    <a href="{{ route('landing.pelatihan.detail', $training->id) }}" class="text-decoration-none text-dark hover-primary">
                                        {{ $training->judul }}
                                    </a>
                                </h6>
                                <p class="text-muted small mb-3 line-clamp-2 flex-grow-1">
                                    {{ Str::limit(strip_tags($training->deskripsi), 80) }}
                                </p>
                                <div class="pt-2 border-top d-flex justify-content-between align-items-center">
                                    <div class="small text-muted">
                                        <i class="bi bi-people text-primary me-1"></i> {{ $training->participants_count ?? 0 }} Peserta
                                    </div>
                                    <a href="{{ route('landing.pelatihan.detail', $training->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        Lihat <i class="bi bi-chevron-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif

                <!-- 4.3 FEED PENGUMUMAN RESMI (Notice Board Section) -->
                <div class="portal-announcements-block mt-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <span class="portal-section-badge bg-danger"></span>
                            <h4 class="portal-section-title mb-0">Pengumuman &amp; Informasi Resmi</h4>
                        </div>
                        <a href="{{ route('landing.pengumuman.index') }}" class="portal-link-all">
                            Lihat Semua <i class="bi bi-chevron-right ms-1"></i>
                        </a>
                    </div>

                    <div class="portal-announcement-list bg-white rounded-4 border shadow-sm p-3">
                        @forelse($latestPengumuman as $pengumuman)
                        <div class="portal-announcement-item d-flex align-items-start gap-3 p-3 rounded-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <div class="portal-announcement-icon flex-shrink-0 {{ $pengumuman->is_pinned ? 'bg-danger text-white' : 'bg-primary-subtle text-primary' }}">
                                <i class="bi {{ $pengumuman->is_pinned ? 'bi-pin-angle-fill' : 'bi-megaphone' }}"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                                    @if($pengumuman->is_pinned)
                                        <span class="badge bg-danger text-white rounded-pill px-2 py-0.5" style="font-size: 0.7rem;">PENTING</span>
                                    @endif
                                    <span class="badge bg-light text-dark border rounded-pill px-2 py-0.5" style="font-size: 0.7rem;">
                                        {{ ucfirst($pengumuman->jenis_pengumuman ?? 'Umum') }}
                                    </span>
                                    <span class="text-muted small" style="font-size: 0.75rem;">
                                        <i class="bi bi-calendar3 me-1"></i> {{ $pengumuman->created_at ? $pengumuman->created_at->format('d M Y') : '-' }}
                                    </span>
                                </div>
                                <h6 class="fw-bold mb-1">
                                    <a href="{{ route('landing.pengumuman.show', $pengumuman->id) }}" class="text-decoration-none text-dark hover-primary">
                                        {{ $pengumuman->judul }}
                                    </a>
                                </h6>
                                <p class="text-muted small mb-0 line-clamp-2">
                                    {{ Str::limit(strip_tags($pengumuman->konten ?? $pengumuman->deskripsi), 120) }}
                                </p>
                            </div>
                            <div class="flex-shrink-0 align-self-center d-none d-sm-block">
                                <a href="{{ route('landing.pengumuman.show', $pengumuman->id) }}" class="btn btn-sm btn-light border rounded-circle" title="Baca Pengumuman">
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-bell-slash fs-2 d-block mb-2"></i>
                            <p class="small mb-0">Belum ada pengumuman resmi terbaru saat ini.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- ============================================================
                 KOLOM KANAN: SIDEBAR WIDGETS (WEBINAR, CEK SERTIFIKAT, FITUR) - 4 Kolom
            ============================================================ -->
            <div class="col-12 col-lg-4">
                <!-- WIDGET 1: JADWAL WEBINAR & AGENDA (Event Calendar Widget) -->
                <div class="portal-widget card rounded-4 border shadow-sm mb-4 bg-white overflow-hidden">
                    <div class="card-header bg-white border-bottom p-3 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-calendar-event text-primary fs-5"></i>
                            <h6 class="fw-bold mb-0 text-dark">Webinar &amp; Agenda</h6>
                        </div>
                        <a href="{{ route('landing.agenda.index') }}" class="portal-link-all small">
                            Semua <i class="bi bi-chevron-right"></i>
                        </a>
                    </div>
                    <div class="card-body p-3">
                        @forelse($upcomingAgendas as $agenda)
                        <div class="portal-agenda-item d-flex gap-3 align-items-start {{ !$loop->last ? 'mb-3 pb-3 border-bottom' : '' }}">
                            <div class="portal-calendar-badge flex-shrink-0 text-center rounded-3 p-1.5 bg-primary bg-opacity-10 border border-primary-subtle">
                                <span class="d-block fw-bold text-primary fs-5 lh-1">
                                    {{ $agenda->tanggal ? $agenda->tanggal->format('d') : '-' }}
                                </span>
                                <small class="text-uppercase text-muted fw-semibold" style="font-size: 0.65rem;">
                                    {{ $agenda->tanggal ? $agenda->tanggal->format('M') : '-' }}
                                </small>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-1.5 mb-1">
                                    <span class="badge {{ $agenda->tipe === 'online' ? 'bg-info-subtle text-info' : 'bg-success-subtle text-success' }} rounded-pill px-2 py-0.5" style="font-size: 0.65rem;">
                                        {{ ucfirst($agenda->tipe ?? 'Online') }}
                                    </span>
                                    <small class="text-muted" style="font-size: 0.75rem;">
                                        <i class="bi bi-clock me-1"></i>{{ $agenda->jam_mulai ? date('H:i', strtotime($agenda->jam_mulai)) : '-' }} WITA
                                    </small>
                                </div>
                                <h6 class="fw-semibold mb-1 small line-clamp-2">
                                    <a href="{{ route('landing.agenda.show', $agenda->id) }}" class="text-decoration-none text-dark hover-primary">
                                        {{ $agenda->judul }}
                                    </a>
                                </h6>
                                <div class="text-muted small" style="font-size: 0.75rem;">
                                    <i class="bi bi-geo-alt me-1 text-danger"></i> {{ Str::limit($agenda->lokasi ?? 'Online via Meeting', 28) }}
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-calendar-x fs-2 d-block mb-1"></i>
                            <p class="small mb-0">Belum ada agenda webinar mendatang.</p>
                        </div>
                        @endforelse
                    </div>
                </div>

              

                <!-- WIDGET 3: FASILITAS & KEUNGGULAN (Features Mini Cards) -->
                <div class="portal-widget card rounded-4 border shadow-sm mb-4 bg-white">
                    <div class="card-header bg-white border-bottom p-3">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-stars text-warning fs-5"></i>
                            <h6 class="fw-bold mb-0 text-dark">Standar Mutu SIPADU</h6>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <div class="d-flex flex-column gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="portal-mini-icon bg-primary-subtle text-primary rounded-3 flex-shrink-0">
                                    <i class="bi bi-mortarboard-fill"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0 small">Kurikulum Berstandar</h6>
                                    <small class="text-muted">Materi terstruktur dan aplikatif untuk UMKM.</small>
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-3">
                                <div class="portal-mini-icon bg-success-subtle text-success rounded-3 flex-shrink-0">
                                    <i class="bi bi-patch-check-fill"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0 small">Sertifikasi Resmi</h6>
                                    <small class="text-muted">Sertifikat ber-barcode terverifikasi online.</small>
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-3">
                                <div class="portal-mini-icon bg-warning-subtle text-warning rounded-3 flex-shrink-0">
                                    <i class="bi bi-person-video3"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0 small">Instruktur Ahli</h6>
                                    <small class="text-muted">Dibimbing langsung oleh praktisi berpengalaman.</small>
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-3">
                                <div class="portal-mini-icon bg-info-subtle text-info rounded-3 flex-shrink-0">
                                    <i class="bi bi-phone-fill"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0 small">Akses Mudah &amp; 24/7</h6>
                                    <small class="text-muted">Bisa diakses dari smartphone, tablet, maupun PC.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- WIDGET 4: INFORMASI KONTAK & KANTOR -->
                <div class="portal-widget card rounded-4 border shadow-sm bg-white p-3">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="bi bi-building text-primary fs-5"></i>
                        <h6 class="fw-bold mb-0 text-dark">Pusat Layanan SDM Usaha</h6>
                    </div>
                    <p class="text-muted small mb-2">
                        Dinas Koperasi, UKM, Perindustrian &amp; Perdagangan Provinsi Sulawesi Barat
                    </p>
                    <ul class="list-unstyled small text-muted mb-3">
                        <li class="mb-1"><i class="bi bi-geo-alt text-danger me-2"></i> Mamuju, Sulawesi Barat</li>
                        <li class="mb-1"><i class="bi bi-telephone text-success me-2"></i> 0822-9194-7554</li>
                        <li class="mb-1"><i class="bi bi-envelope text-primary me-2"></i> andiadeindawan@gmail.com</li>
                    </ul>
                    <a href="{{ route('landing.kontak.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill w-100">
                        Hubungi Layanan Support
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>



<!-- ============================================================
     6. CALL TO ACTION (CTA) BANNER (Penutup Modern)
============================================================ -->
<section class="portal-cta-section py-5">
    <div class="container">
        <div class="portal-cta-box rounded-4 p-4 p-md-5 text-center text-white position-relative overflow-hidden shadow">
            <div class="position-relative z-1" style="max-width: 650px; margin: 0 auto;">
                <span class="badge bg-warning text-dark px-3 py-1.5 rounded-pill fw-bold mb-3">
                    <i class="bi bi-lightning-charge-fill"></i> Siap Naik Kelas Bersama SIPADU?
                </span>
                <h2 class="fw-bold mb-3 text-white">Daftarkan Diri &amp; Tingkatkan Keterampilan Usaha Anda Sekarang</h2>
                <p class="text-white-50 mb-4 small">
                    Akses puluhan modul kejuruan, ikuti webinar eksklusif, dan dapatkan sertifikat resmi dari Dinas Koperasi &amp; UKM Provinsi Sulawesi Barat.
                </p>
                <div class="d-flex flex-wrap gap-2 justify-content-center">
                    @auth
                        <a href="{{ auth()->user()->role === 'peserta' ? route('peserta.dashboard') : route('admin.dashboard') }}" class="btn btn-warning btn-lg text-dark fw-bold px-4 py-2.5 rounded-pill shadow-sm">
                            <i class="bi bi-speedometer2 me-1"></i> Buka Dashboard Saya
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="btn btn-warning btn-lg text-dark fw-bold px-4 py-2.5 rounded-pill shadow-sm">
                            <i class="bi bi-person-plus-fill me-1"></i> Daftar Akun Peserta
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg fw-semibold px-4 py-2.5 rounded-pill">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Masuk Akun
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
    /* ============================================================
       SCOPED CSS UNTUK HALAMAN PORTAL BERANDA (PREFIX: .portal-*)
       Aman dan tidak bertabrakan dengan CSS layouts.landing
    ============================================================ */

    /* 1. HERO COMPACT SECTION */
    .portal-hero {
        position: relative;
        overflow: hidden;
        padding: 3.5rem 0 3rem;
        background: #0f172a;
        color: #fff;
    }
    .portal-hero-bg {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        overflow: hidden;
        z-index: 0;
    }
    .portal-hero-img {
        width: 100%; height: 100%;
        background-size: cover;
        background-position: center;
        filter: blur(2.5px) brightness(0.62) saturate(1.15) contrast(1.05);
        transform: scale(1.03);
    }
    .portal-hero-overlay {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        background: linear-gradient(180deg, 
            rgba(15, 23, 42, 0.65) 0%, 
            rgba(15, 23, 42, 0.52) 45%, 
            rgba(15, 23, 42, 0.80) 100%
        );
    }
    .portal-hero-title {
        font-weight: 800;
        font-size: 2.35rem;
        letter-spacing: -0.02em;
        line-height: 1.2;
        color: #ffffff;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.55);
    }
    .portal-hero-subtitle {
        font-size: 1rem;
        color: rgba(255, 255, 255, 0.92);
        max-width: 680px;
        line-height: 1.6;
        text-shadow: 0 1px 6px rgba(0, 0, 0, 0.45);
    }

    /* SEARCH BAR */
    .portal-search-wrapper {
        max-width: 640px;
    }
    .portal-search-form {
        background: #ffffff;
        border-radius: 50px;
        padding: 6px 8px 6px 18px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
    }
    .portal-search-icon {
        font-size: 1.15rem;
        margin-right: 10px;
        display: flex;
        align-items: center;
    }
    .portal-search-input {
        border: none !important;
        box-shadow: none !important;
        font-size: 0.95rem;
        color: #1e293b;
        padding: 8px 4px;
        background: transparent;
    }
    .portal-search-input::placeholder {
        color: #94a3b8;
    }
    .portal-search-btn {
        border-radius: 50px;
        padding: 9px 24px;
        font-weight: 600;
        font-size: 0.9rem;
        background: linear-gradient(135deg, #4e9af1 0%, #2563eb 100%);
        border: none;
        white-space: nowrap;
        transition: all 0.2s ease;
    }
    .portal-search-btn:hover {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        transform: translateY(-1px);
    }
    .portal-tag-pill {
        color: rgba(255, 255, 255, 0.85);
        text-decoration: none;
        background: rgba(255, 255, 255, 0.12);
        padding: 3px 12px;
        border-radius: 20px;
        font-size: 0.78rem;
        transition: all 0.2s ease;
        backdrop-filter: blur(4px);
    }
    .portal-tag-pill:hover {
        background: rgba(255, 255, 255, 0.25);
        color: #fff;
        transform: translateY(-1px);
    }

    /* 2. SHORTCUTS BAR (MICROSOFT START APPS STYLE) */
    .portal-shortcuts-bar {
        margin-top: -24px;
        position: relative;
        z-index: 10;
    }
    .portal-shortcuts-wrapper {
        background: #ffffff;
        border-radius: 18px;
        padding: 14px 16px;
        border: 1px solid rgba(226, 232, 240, 0.9);
    }
    .portal-shortcut-card {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 12px;
        border-radius: 12px;
        text-decoration: none;
        color: #1e293b;
        background: #f8fafc;
        border: 1px solid transparent;
        transition: all 0.2s ease;
    }
    .portal-shortcut-card:hover {
        background: #ffffff;
        border-color: #cbd5e1;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
        transform: translateY(-2px);
    }
    .portal-shortcut-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
        flex-shrink: 0;
    }
    .portal-shortcut-icon.icon-blue   { background: #eff6ff; color: #3b82f6; }
    .portal-shortcut-icon.icon-red    { background: #fef2f2; color: #ef4444; }
    .portal-shortcut-icon.icon-cyan   { background: #ecfeff; color: #06b6d4; }
    .portal-shortcut-icon.icon-amber  { background: #fffbeb; color: #f59e0b; }
    .portal-shortcut-icon.icon-purple { background: #faf5ff; color: #a855f7; }
    .portal-shortcut-icon.icon-green  { background: #f0fdf4; color: #22c55e; }
    .portal-shortcut-title {
        display: block;
        font-weight: 700;
        font-size: 0.85rem;
        line-height: 1.2;
        color: #0f172a;
    }
    .portal-shortcut-desc {
        display: block;
        font-size: 0.7rem;
        color: #64748b;
    }

    /* 3. PORTAL STATS */
    .portal-stats-container {
        border-color: #e2e8f0 !important;
    }
    .portal-stat-icon {
        font-size: 1.6rem;
    }
    .portal-stat-number {
        font-size: 1.9rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.1;
    }
    .portal-stat-label {
        font-size: 0.82rem;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        margin-top: 4px;
    }

    /* 4. MAIN CONTENT & CARDS */
    .portal-section-badge {
        width: 4px;
        height: 22px;
        border-radius: 4px;
        display: inline-block;
    }
    .portal-section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #0f172a;
    }
    .portal-link-all {
        font-size: 0.88rem;
        font-weight: 600;
        color: #3b82f6;
        text-decoration: none;
        transition: color 0.15s ease;
    }
    .portal-link-all:hover {
        color: #1d4ed8;
    }

    /* FEATURED HEADLINE CARD */
    .portal-featured-card {
        border-color: #e2e8f0 !important;
        transition: box-shadow 0.2s ease, transform 0.2s ease;
    }
    .portal-featured-card:hover {
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08) !important;
        transform: translateY(-2px);
    }
    .portal-featured-img {
        height: 270px;
        object-fit: cover;
    }
    .portal-featured-placeholder {
        height: 270px;
        background: #f1f5f9;
    }

    /* GRID CARDS */
    .portal-grid-card {
        border-color: #e2e8f0 !important;
        transition: all 0.2s ease;
    }
    .portal-grid-card:hover {
        box-shadow: 0 8px 22px rgba(0, 0, 0, 0.07) !important;
        transform: translateY(-2px);
    }
    .portal-grid-img {
        height: 165px;
        object-fit: cover;
    }
    .portal-grid-placeholder {
        height: 165px;
        background: #f1f5f9;
    }

    /* ANNOUNCEMENTS NOTICE BOARD */
    .portal-announcement-list {
        border-color: #e2e8f0 !important;
    }
    .portal-announcement-item {
        transition: background 0.15s ease;
    }
    .portal-announcement-item:hover {
        background: #f8fafc;
    }
    .portal-announcement-icon {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
    }

    /* SIDEBAR WIDGETS */
    .portal-widget {
        border-color: #e2e8f0 !important;
    }
    .portal-calendar-badge {
        width: 48px;
        height: 52px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .portal-mini-icon {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
    }

    /* TESTIMONIALS & CTA */
    .portal-testimonials-section {
        background: #ffffff;
    }
    .portal-testimonial-card {
        border-color: #e2e8f0 !important;
    }
    .portal-avatar {
        width: 42px;
        height: 42px;
        font-size: 0.95rem;
    }
    .portal-cta-box {
        background: linear-gradient(135deg, #0B1F4D 0%, #1e3a8a 100%);
    }

    /* UTILITY CLASSES */
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .hover-primary:hover {
        color: #2563eb !important;
    }

    @media (min-width: 992px) {
        .border-start-lg {
            border-left: 1px solid #e2e8f0;
        }
    }

    @media (max-width: 768px) {
        .portal-hero {
            padding: 2.5rem 0 2rem;
        }
        .portal-hero-title {
            font-size: 1.65rem;
        }
        .portal-featured-img,
        .portal-featured-placeholder {
            height: 200px;
        }
        .portal-stat-number {
            font-size: 1.5rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ============================================================
    // ANIMASI COUNTER ANGKA STATISTIK BERANDA
    // ============================================================
    const counters = document.querySelectorAll('[data-count]');
    
    function animateCounter(element) {
        const target = parseInt(element.getAttribute('data-count'));
        const counterSpan = element.querySelector('.counter') || element;
        
        if (isNaN(target) || target === 0) {
            counterSpan.textContent = '0';
            return;
        }
        
        const duration = 1800;
        const step = Math.max(1, target / (duration / 16));
        let current = 0;
        
        const update = () => {
            current += step;
            if (current < target) {
                counterSpan.textContent = Math.floor(current);
                requestAnimationFrame(update);
            } else {
                counterSpan.textContent = target;
            }
        };
        
        update();
    }

    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounter(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.3 });

        counters.forEach(counter => observer.observe(counter));
    } else {
        counters.forEach(counter => animateCounter(counter));
    }
});
</script>
@endpush
@endsection
