@extends('layouts.landing')

@section('title', $training->judul ?? 'Detail Pelatihan')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            

            <!-- Card Detail -->
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                @if(isset($training->gambar) && $training->gambar)
                <img src="{{ asset('storage/' . $training->gambar) }}" 
                     class="card-img-top" 
                     alt="{{ $training->judul }}" 
                     style="max-height: 400px; object-fit: cover; width: 100%;">
                @else
                <div class="bg-primary bg-opacity-10 text-center py-5" style="max-height: 300px;">
                    <i class="bi bi-journal-bookmark fs-1 text-primary"></i>
                    <p class="text-muted mt-2">Gambar Pelatihan</p>
                </div>
                @endif
                
                <div class="card-body p-4 p-lg-5">
                    <!-- Header -->
                    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
                        <div>
                            <h1 class="h2 fw-bold mb-2">{{ $training->judul ?? 'Detail Pelatihan' }}</h1>
                            <div class="d-flex flex-wrap gap-2">
                                <span class="badge bg-{{ $training->status == 'published' ? 'success' : 'secondary' }} px-3 py-2">
                                    <i class="bi bi-circle-fill me-1" style="font-size: 8px;"></i>
                                    {{ $training->status_label ?? ucfirst($training->status ?? 'Draft') }}
                                </span>
                                @if(isset($training->kategori->nama))
                                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">
                                    <i class="bi bi-tag me-1"></i>
                                    {{ $training->kategori->nama }}
                                </span>
                                @endif
                                @if(isset($training->tipe))
                                <span class="badge bg-info bg-opacity-10 text-info px-3 py-2">
                                    <i class="bi bi-{{ $training->tipe == 'online' ? 'wifi' : ($training->tipe == 'offline' ? 'building' : 'people') }} me-1"></i>
                                    {{ ucfirst($training->tipe) }}
                                </span>
                                @endif
                            </div>
                        </div>
                        @if(isset($training->participants_count))
                        <div class="text-end">
                            <span class="d-block text-muted small">Peserta</span>
                            <span class="fw-bold fs-5">{{ $training->participants_count }}</span>
                        </div>
                        @endif
                    </div>

                    <!-- Informasi Detail -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-2 p-2 bg-light rounded-3">
                                <i class="bi bi-calendar text-primary fs-5"></i>
                                <div>
                                    <small class="text-muted d-block">Tanggal</small>
                                    <strong>
                                        {{ $training->tanggal_mulai ? $training->tanggal_mulai->format('d/m/Y') : 'TBD' }}
                                        @if($training->tanggal_selesai)
                                            - {{ $training->tanggal_selesai->format('d/m/Y') }}
                                        @endif
                                    </strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-2 p-2 bg-light rounded-3">
                                <i class="bi bi-clock text-primary fs-5"></i>
                                <div>
                                    <small class="text-muted d-block">Durasi</small>
                                    <strong>{{ $training->durasi ?? '-' }} menit</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-2 p-2 bg-light rounded-3">
                                <i class="bi bi-person text-primary fs-5"></i>
                                <div>
                                    <small class="text-muted d-block">Trainer</small>
                                    <strong>{{ $training->trainer->nama ?? $training->trainer->name ?? '-' }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-2 p-2 bg-light rounded-3">
                                <i class="bi bi-geo-alt text-primary fs-5"></i>
                                <div>
                                    <small class="text-muted d-block">Lokasi</small>
                                    <strong>{{ $training->lokasi ?? 'Online' }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-2 p-2 bg-light rounded-3">
                                <i class="bi bi-people text-primary fs-5"></i>
                                <div>
                                    <small class="text-muted d-block">Kuota</small>
                                    <strong>{{ $training->kapasitas ?? 'Tak Terbatas' }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-2 p-2 bg-light rounded-3">
                                <i class="bi bi-{{ $training->is_free ? 'gift' : 'cash' }} text-primary fs-5"></i>
                                <div>
                                    <small class="text-muted d-block">Biaya</small>
                                    <strong>{{ $training->is_free ? 'Gratis' : 'Rp ' . number_format($training->harga ?? 0, 0, ',', '.') }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3"><i class="bi bi-file-text me-2 text-primary"></i>Deskripsi</h5>
                        <div class="text-muted" style="line-height: 1.8;">
                            {!! nl2br(e($training->deskripsi ?? 'Belum ada deskripsi.')) !!}
                        </div>
                    </div>

                    <!-- Materi Pelatihan -->
                    @if(isset($training->materis) && $training->materis->count() > 0)
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3"><i class="bi bi-book me-2 text-primary"></i>Materi Pelatihan</h5>
                        <div class="list-group list-group-flush border rounded-3">
                            @foreach($training->materis as $materi)
                            <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                                <div>
                                    <i class="bi bi-file-earmark-text me-2 text-primary"></i>
                                    <span>{{ $materi->judul }}</span>
                                    @if($materi->durasi)
                                    <small class="text-muted ms-2">({{ $materi->durasi }} menit)</small>
                                    @endif
                                </div>
                                <span class="badge bg-secondary">{{ $materi->tipe_file ?? 'Dokumen' }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Progress (jika sudah terdaftar) -->
                    @if(isset($isEnrolled) && $isEnrolled)
                    <div class="mb-4 p-3 bg-success bg-opacity-10 rounded-3 border border-success border-opacity-25">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fw-bold mb-1 text-success">
                                    <i class="bi bi-check-circle me-2"></i>Anda Terdaftar
                                </h6>
                                <small class="text-muted">Progress belajar Anda</small>
                            </div>
                            <span class="fw-bold fs-5">{{ $progress ?? 0 }}%</span>
                        </div>
                        <div class="progress mt-2" style="height: 8px;">
                            <div class="progress-bar bg-success" style="width: {{ $progress ?? 0 }}%;"></div>
                        </div>
                    </div>
                    @endif

                    <!-- Tombol Aksi -->
                    <div class="d-flex flex-wrap gap-3 mt-4 pt-3 border-top">
                        @auth
                            @if(isset($isEnrolled) && $isEnrolled)
                                <a href="{{ route('peserta.trainings.show', $training->id) }}" class="btn btn-success btn-lg px-4">
                                    <i class="bi bi-play-circle me-2"></i> Mulai Pelatihan
                                </a>
                            @else
                                @if($training->status == 'published' && $training->is_available)
                                    <form action="{{ route('peserta.trainings.enroll', $training->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-lg px-4">
                                            <i class="bi bi-plus-circle me-2"></i> Daftar Pelatihan
                                        </button>
                                    </form>
                                @elseif($training->isCompletedTraining())
                                    <button class="btn btn-secondary btn-lg px-4" disabled>
                                        <i class="bi bi-check-circle me-2"></i> Pelatihan Telah Selesai
                                    </button>
                                @else
                                    <button class="btn btn-secondary btn-lg px-4" disabled>
                                        <i class="bi bi-clock me-2"></i> Pelatihan Belum Tersedia
                                    </button>
                                @endif
                            @endif
                        @else
                            @if($training->isCompletedTraining())
                                <button class="btn btn-secondary btn-lg px-4" disabled>
                                    <i class="bi bi-check-circle me-2"></i> Pelatihan Telah Selesai
                                </button>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-4">
                                    <i class="bi bi-box-arrow-in-right me-2"></i> Login untuk Daftar
                                </a>
                                <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg px-4">
                                    <i class="bi bi-person-plus me-2"></i> Daftar Akun
                                </a>
                            @endif
                        @endauth
                        
                        <a href="{{ route('landing.pelatihan.index') }}" class="btn btn-outline-secondary btn-lg px-4">
                            <i class="bi bi-arrow-left me-2"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>

            <!-- Pelatihan Lainnya -->
            @if(isset($relatedTrainings) && $relatedTrainings->count() > 0)
            <div class="mt-5">
                <h4 class="fw-bold mb-4">Pelatihan Lainnya</h4>
                <div class="row g-4">
                    @foreach($relatedTrainings as $related)
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm border-0 rounded-3">
                            <div class="card-body">
                                <h6 class="fw-bold">{{ Str::limit($related->judul, 40) }}</h6>
                                <p class="text-muted small">{{ Str::limit($related->deskripsi ?? '', 80) }}</p>
                                <a href="{{ route('landing.pelatihan.detail', $related->id) }}" class="btn btn-sm btn-outline-primary">
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
@endsection

@push('styles')
<style>
    .breadcrumb {
        background: transparent;
        padding: 0;
    }
    .breadcrumb-item a {
        text-decoration: none;
        color: #6c757d;
    }
    .breadcrumb-item a:hover {
        color: #0d6efd;
    }
    .breadcrumb-item.active {
        color: #0d6efd;
        font-weight: 500;
    }
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(0,0,0,0.08) !important;
    }
    .list-group-item {
        border-left: none;
        border-right: none;
    }
    .list-group-item:first-child {
        border-top: none;
    }
    .list-group-item:last-child {
        border-bottom: none;
    }
    .btn {
        border-radius: 50px;
        font-weight: 500;
    }
    .btn-lg {
        padding: 0.75rem 2rem;
    }
    @media (max-width: 768px) {
        .btn-lg {
            width: 100%;
            justify-content: center;
        }
        .d-flex.gap-3 {
            flex-direction: column;
        }
    }
</style>
@endpush