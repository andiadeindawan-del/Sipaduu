@extends('layouts.peserta')

@section('title', 'Detail Pelatihan')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-journal-bookmark"></i></span>
        <div>
            <p class="eyebrow">Pelatihan</p>
            <h1 class="h3 mb-0">Detail Pelatihan</h1>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="row">
        <div class="col-12 col-lg-10 mx-auto">
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

            @if(isset($registration) && $registration->status === 'ditolak')
            <div class="alert alert-danger mb-4" role="alert">
                <h5 class="alert-heading fw-bold"><i class="bi bi-x-circle-fill me-2"></i> Pendaftaran Ditolak</h5>
                <p>Mohon maaf, pendaftaran Anda untuk pelatihan ini ditolak oleh admin dengan alasan berikut:</p>
                <hr>
                <p class="mb-0 fw-semibold">{{ $registration->alasan_penolakan ?? 'Tidak ada alasan yang diberikan.' }}</p>
                <hr>
                <p class="mb-0 mt-2 small">Silakan lengkapi profil Anda di menu <a href="{{ route('peserta.profile.index') }}" class="fw-bold text-danger text-decoration-underline">Profil Saya</a> dan daftar kembali.</p>
            </div>
            @endif
            
            @if(isset($registration) && $registration->status === 'pending')
            <div class="alert alert-warning mb-4" role="alert">
                <h5 class="alert-heading fw-bold"><i class="bi bi-clock-history me-2"></i> Menunggu Verifikasi Admin</h5>
                <p class="mb-0">Pendaftaran Anda sedang diproses dan menunggu verifikasi dari admin. Anda akan menerima notifikasi jika pendaftaran disetujui atau ditolak.</p>
            </div>
            @endif

            <!-- Main Card -->
            <div class="panel">
                <!-- Header Image -->
                @if($training->gambar)
                <div class="position-relative">
                    <img src="{{ asset('storage/' . $training->gambar) }}" 
                         alt="{{ $training->judul }}" 
                         class="img-fluid w-100" 
                         style="height: 300px; object-fit: cover; border-radius: 0.75rem 0.75rem 0 0;">
                </div>
                @else
                <div class="bg-light text-center py-5" style="border-radius: 0.75rem 0.75rem 0 0;">
                    <i class="bi bi-image fs-1 text-muted"></i>
                    <p class="text-muted small mb-0">Tidak ada gambar</p>
                </div>
                @endif

                <div class="p-4">
                    <div class="row g-4">
                        <!-- Title & Status -->
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                <div>
                                    <h3 class="fw-bold mb-2">{{ $training->judul }}</h3>
                                    <div class="d-flex flex-wrap gap-2">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <span class="badge 
                                                @if($training->status == 'published' || $training->status == 'berjalan') 
                                                    badge-berjalan
                                                @elseif($training->status == 'selesai') 
                                                    badge-selesai
                                                @else 
                                                    badge-draft
                                                @endif
                                            ">
                                                {{ $training->status_label }}
                                            </span>
                                            @if(isset($registration))
                                                <span class="{!! $registration->status_badge !!}">
                                                    {{ $registration->status_label }}
                                                </span>
                                            @endif
                                        </div>
                                        <span class="badge bg-primary">{{ $training->tipe_label }}</span>
                                        @if($isCompleted ?? false)
                                            <span class="badge bg-info">
                                                <i class="bi bi-award me-1"></i> Selesai
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="d-flex gap-2 flex-wrap">
                                    @if(!isset($registration) || in_array($registration->status, ['ditolak', 'dibatalkan']))
                                        @if($training->isCompletedTraining())
                                            <button class="btn btn-secondary" disabled>
                                                <i class="bi bi-check-circle me-2"></i> Pelatihan Telah Selesai
                                            </button>
                                        @elseif(auth()->user()->is_profil_lengkap)
                                            <form action="{{ route('peserta.trainings.enroll', $training->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-primary" 
                                                        onclick="return confirm('Yakin ingin {{ isset($registration) && $registration->status == 'ditolak' ? 'mendaftar kembali' : 'mendaftar' }} pelatihan ini?')"
                                                        {{ $availableSlots !== null && $availableSlots <= 0 ? 'disabled' : '' }}>
                                                    <i class="bi bi-plus-circle me-2"></i> {{ isset($registration) && $registration->status == 'ditolak' ? 'Daftar Kembali' : 'Daftar Pelatihan' }}
                                                </button>
                                            </form>
                                        @else
                                            <div class="alert alert-warning d-inline-block py-2 mb-0">
                                                <i class="bi bi-exclamation-triangle me-1"></i> Profil belum lengkap. <a href="{{ route('peserta.profile.index') }}" class="alert-link">Lengkapi Profil</a> untuk mendaftar.
                                            </div>
                                        @endif
                                    @endif
                                    @if(($isEnrolled ?? false) && ($progress ?? 0) >= 100)
                                        <form action="{{ route('peserta.trainings.complete', $training->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-info text-white">
                                                <i class="bi bi-check-circle me-2"></i> Tandai Selesai
                                            </button>
                                        </form>
                                    @endif
                                    @if(isset($registration) && in_array($registration->status, ['pending', 'disetujui']))
                                        <form action="{{ route('peserta.trainings.unenroll', $training->id) }}" method="POST" 
                                              onsubmit="return confirm('Yakin ingin membatalkan pendaftaran pelatihan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger">
                                                <i class="bi bi-x-circle me-2"></i> Batalkan Pendaftaran
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Progress -->
                        @if($isEnrolled ?? false)
                        <div class="col-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="fw-bold mb-0">Progress Belajar</h6>
                                        <span class="fw-bold">{{ $progress ?? 0 }}%</span>
                                    </div>
                                    <div class="progress" style="height: 10px;">
                                        <div class="progress-bar bg-success" style="width: {{ $progress ?? 0 }}%;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Deskripsi -->
                        @if($training->deskripsi)
                        <div class="col-12">
                            <h6 class="fw-bold"><i class="bi bi-file-text me-2"></i>Deskripsi</h6>
                            <p class="text-muted">{{ $training->deskripsi }}</p>
                        </div>
                        @endif

                        <!-- Informasi Detail -->
                        <div class="col-12">
                            <hr class="my-2">
                            <h6 class="fw-bold"><i class="bi bi-info-circle me-2"></i>Informasi Detail</h6>
                            <div class="row g-3 mt-2">
                                <div class="col-12 col-md-4">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-calendar text-primary"></i>
                                        <div>
                                            <label class="text-muted small fw-semibold">Tanggal Mulai</label>
                                            <p class="fw-semibold mb-0">
                                                {{ $training->tanggal_mulai ? $training->tanggal_mulai->format('d/m/Y') : '-' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-calendar-check text-success"></i>
                                        <div>
                                            <label class="text-muted small fw-semibold">Tanggal Selesai</label>
                                            <p class="fw-semibold mb-0">
                                                {{ $training->tanggal_selesai ? $training->tanggal_selesai->format('d/m/Y') : '-' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-clock text-warning"></i>
                                        <div>
                                            <label class="text-muted small fw-semibold">Durasi</label>
                                            <p class="fw-semibold mb-0">
                                                @if($training->durasi)
                                                    {{ $training->durasi }} jam
                                                @else
                                                    -
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-people text-info"></i>
                                        <div>
                                            <label class="text-muted small fw-semibold">Peserta</label>
                                            <p class="fw-semibold mb-0">
                                                {{ $training->participants_count ?? 0 }} peserta
                                                @if($training->kapasitas)
                                                    / {{ $training->kapasitas }} kuota
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-geo-alt text-danger"></i>
                                        <div>
                                            <label class="text-muted small fw-semibold">Lokasi</label>
                                            <p class="fw-semibold mb-0">
                                                @if($training->tipe == 'online')
                                                    <span class="text-primary">Online</span>
                                                    @if($training->link_meeting)
                                                        <br><small><a href="{{ $training->link_meeting }}" target="_blank" class="text-decoration-none">
                                                            <i class="bi bi-link me-1"></i> Link Meeting
                                                        </a></small>
                                                    @endif
                                                @elseif($training->tipe == 'offline')
                                                    {{ $training->lokasi ?? 'Offline' }}
                                                @else
                                                    Hybrid
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-tag text-secondary"></i>
                                        <div>
                                            <label class="text-muted small fw-semibold">Kategori</label>
                                            <p class="fw-semibold mb-0">
                                                @if($training->kategori)
                                                    {{ $training->kategori->nama }}
                                                @else
                                                    -
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Trainer -->
                        @if($training->trainer)
                        <div class="col-12">
                            <hr class="my-2">
                            <h6 class="fw-bold"><i class="bi bi-person-badge me-2"></i>Instruktur</h6>
                            <div class="d-flex align-items-center gap-3 mt-2">
                                <div class="avatar-circle d-flex align-items-center justify-content-center" 
                                     style="width: 60px; height: 60px; border-radius: 50%; background: #4e9af1; color: #fff; font-size: 1.5rem; font-weight: 700;">
                                    {{ $training->trainer->initials ?? 'T' }}
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0">{{ $training->trainer->nama ?? $training->trainer->name }}</h6>
                                    <p class="text-muted small mb-0">Trainer</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Materi -->
                        @if($training->materis && $training->materis->count() > 0)
                        <div class="col-12">
                            <hr class="my-2">
                            <h6 class="fw-bold"><i class="bi bi-book me-2"></i>Materi Pelatihan ({{ $training->materis->count() }})</h6>
                            <div class="list-group mt-2">
                                @foreach($training->materis as $materi)
                                <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="bi bi-file-earmark-text me-2"></i>
                                        {{ $materi->judul }}
                                        @if($materi->durasi)
                                        <span class="badge bg-secondary ms-2">{{ $materi->durasi }} menit</span>
                                        @endif
                                    </div>
                                    <a href="{{ route('peserta.materi.show', $materi->id) }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-eye me-1"></i> Lihat
                                    </a>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Quiz -->
                        @if($training->quizzes && $training->quizzes->count() > 0)
                        <div class="col-12">
                            <hr class="my-2">
                            <h6 class="fw-bold"><i class="bi bi-question-circle me-2"></i>Quiz ({{ $training->quizzes->count() }})</h6>
                            <div class="list-group mt-2">
                                @foreach($training->quizzes as $quiz)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="bi bi-question-circle me-2"></i>
                                        {{ $quiz->judul }}
                                        <span class="badge bg-secondary ms-2">{{ $quiz->questions->count() }} soal</span>
                                    </div>
                                    <a href="{{ route('peserta.quiz.show', $quiz->id) }}" class="btn btn-sm btn-success">
                                        <i class="bi bi-play-circle me-1"></i> Kerjakan
                                    </a>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Meta Info -->
                        <div class="col-12">
                            <hr class="my-2">
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label class="text-muted small fw-semibold">
                                        <i class="bi bi-clock me-1"></i> Dibuat
                                    </label>
                                    <p class="fw-semibold mb-0">
                                        {{ $training->created_at ? $training->created_at->format('d/m/Y H:i') : '-' }}
                                    </p>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="text-muted small fw-semibold">
                                        <i class="bi bi-clock-history me-1"></i> Diperbarui
                                    </label>
                                    <p class="fw-semibold mb-0">
                                        {{ $training->updated_at ? $training->updated_at->format('d/m/Y H:i') : '-' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="col-12 mt-2">
                            <hr class="my-2">
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="{{ route('peserta.trainings.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left me-1"></i> Kembali
                                </a>
                                @if($isEnrolled ?? false)
                                    <a href="{{ route('peserta.materi.index') }}" class="btn btn-primary">
                                        <i class="bi bi-book me-1"></i> Mulai Belajar
                                    </a>
                                @endif
                                @if(($isEnrolled ?? false) && ($isCompleted ?? false))
                                    <a href="{{ route('peserta.sertifikat.index') }}" class="btn btn-info text-white">
                                        <i class="bi bi-award me-1"></i> Lihat Sertifikat
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .badge-berjalan { background: #cff4fc; color: #0c5460; }
    .badge-selesai { background: #d1e7dd; color: #0a7344; }
    .badge-draft { background: #e9ecef; color: #495057; }
    .badge-published { background: #d1e7dd; color: #0a7344; }
    
    .avatar-circle {
        transition: transform 0.3s ease;
    }
    .avatar-circle:hover {
        transform: scale(1.05);
    }
    
    .progress {
        background-color: #e9ecef;
        border-radius: 10px;
    }
    .progress-bar {
        transition: width 0.6s ease;
        border-radius: 10px;
    }
    
    .list-group-item {
        transition: background 0.2s ease;
    }
    .list-group-item:hover {
        background: #f8fafc;
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

    // Confirm actions
    document.querySelectorAll('form[action*="enroll"], form[action*="unenroll"], form[action*="complete"]').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            // Already confirmed via onclick
        });
    });
});
</script>
@endpush
@endsection