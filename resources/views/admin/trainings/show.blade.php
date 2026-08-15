@extends('layouts.admin')

@section('title', 'Detail Pelatihan')

@section('header')
<div class="page-heading d-flex justify-content-between align-items-center">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-eye"></i></span>
        <div>
            <p class="eyebrow">Manajemen</p>
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

            <!-- Main Card -->
            <div class="panel">
                <div class="panel-header">
                    <h5 class="section-title"><i class="bi bi-info-circle"></i> Informasi Pelatihan</h5>
                    <span class="badge {{ $training->status == 'published' ? 'badge-published' : ($training->status == 'berjalan' ? 'badge-berjalan' : ($training->status == 'selesai' ? 'badge-selesai' : ($training->status == 'dibatalkan' ? 'badge-dibatalkan' : 'badge-draft'))) }}">
                        <i class="bi bi-circle-fill me-1" style="font-size: 6px;"></i>
                        {{ ucfirst($training->status) }}
                    </span>
                </div>
                <div class="p-4">
                    <!-- Gambar & Judul -->
                    <div class="row g-4 mb-4">
                        @if($training->gambar)
                        <div class="col-12 text-center mb-3">
                            <img src="{{ asset('storage/' . $training->gambar) }}" 
                                 alt="{{ $training->judul }}" 
                                 class="img-fluid rounded-3 shadow-sm" 
                                 style="max-height: 300px; object-fit: cover; border: 1px solid #e9ecef;">
                        </div>
                        @endif

                        <div class="col-12">
                            <h3 class="fw-bold mb-0">{{ $training->judul }}</h3>
                            <div class="d-flex flex-wrap align-items-center gap-2 mt-2">
                                <span class="badge {{ $training->status == 'published' ? 'badge-published' : ($training->status == 'berjalan' ? 'badge-berjalan' : ($training->status == 'selesai' ? 'badge-selesai' : ($training->status == 'dibatalkan' ? 'badge-dibatalkan' : 'badge-draft'))) }}">
                                    <i class="bi bi-circle-fill me-1" style="font-size: 6px;"></i>
                                    {{ ucfirst($training->status) }}
                                </span>
                                @if($training->kategori)
                                <span class="badge text-bg-info">
                                    <i class="bi bi-tag me-1"></i>
                                    {{ $training->kategori->nama }}
                                </span>
                                @endif
                                <span class="badge {{ $training->tipe == 'online' ? 'text-bg-primary' : ($training->tipe == 'offline' ? 'text-bg-secondary' : 'text-bg-warning') }}">
                                    <i class="bi bi-laptop me-1"></i>
                                    {{ ucfirst($training->tipe) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Utama -->
                    <div class="row g-4">
                        <!-- Deskripsi -->
                        @if($training->deskripsi)
                        <div class="col-12">
                            <div class="info-card bg-light p-3 rounded-3">
                                <label class="text-muted small fw-semibold text-uppercase mb-1">
                                    <i class="bi bi-file-text me-1"></i> Deskripsi
                                </label>
                                <p class="mb-0">{{ $training->deskripsi }}</p>
                            </div>
                        </div>
                        @endif

                        <!-- Info Grid -->
                        <div class="col-12">
                            <div class="row g-3">
                                <!-- Tanggal Mulai -->
                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="info-item p-3 bg-light rounded-3 h-100">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="icon-circle bg-primary text-white">
                                                <i class="bi bi-calendar3"></i>
                                            </div>
                                            <div>
                                                <label class="text-muted small fw-semibold text-uppercase d-block">Tanggal Mulai</label>
                                                <p class="fw-semibold mb-0">{{ $training->tanggal_mulai ? $training->tanggal_mulai->format('d/m/Y') : '-' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tanggal Selesai -->
                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="info-item p-3 bg-light rounded-3 h-100">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="icon-circle bg-danger text-white">
                                                <i class="bi bi-calendar3"></i>
                                            </div>
                                            <div>
                                                <label class="text-muted small fw-semibold text-uppercase d-block">Tanggal Selesai</label>
                                                <p class="fw-semibold mb-0">{{ $training->tanggal_selesai ? $training->tanggal_selesai->format('d/m/Y') : '-' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Kapasitas -->
                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="info-item p-3 bg-light rounded-3 h-100">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="icon-circle bg-success text-white">
                                                <i class="bi bi-people"></i>
                                            </div>
                                            <div>
                                                <label class="text-muted small fw-semibold text-uppercase d-block">Kapasitas</label>
                                                <p class="fw-semibold mb-0">{{ $training->kapasitas ?? 'Tidak terbatas' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Peserta -->
                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="info-item p-3 bg-light rounded-3 h-100">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="icon-circle bg-info text-white">
                                                <i class="bi bi-people-fill"></i>
                                            </div>
                                            <div>
                                                <label class="text-muted small fw-semibold text-uppercase d-block">Peserta Terdaftar</label>
                                                <p class="fw-semibold mb-0">
                                                    {{ $participantsCount ?? 0 }} 
                                                    @if($training->kapasitas)
                                                    <span class="text-muted small">/ {{ $training->kapasitas }}</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Lokasi -->
                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="info-item p-3 bg-light rounded-3 h-100">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="icon-circle bg-secondary text-white">
                                                <i class="bi bi-geo-alt"></i>
                                            </div>
                                            <div>
                                                <label class="text-muted small fw-semibold text-uppercase d-block">Lokasi</label>
                                                <p class="fw-semibold mb-0">{{ $training->lokasi ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Link Meeting -->
                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="info-item p-3 bg-light rounded-3 h-100">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="icon-circle bg-warning text-white">
                                                <i class="bi bi-link"></i>
                                            </div>
                                            <div>
                                                <label class="text-muted small fw-semibold text-uppercase d-block">Link Meeting</label>
                                                @if($training->link_meeting)
                                                <a href="{{ $training->link_meeting }}" target="_blank" class="fw-semibold text-truncate d-inline-block" style="max-width: 150px;">
                                                    {{ Str::limit($training->link_meeting, 30) }}
                                                    <i class="bi bi-box-arrow-up-right ms-1"></i>
                                                </a>
                                                @else
                                                <p class="text-muted mb-0">-</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Tambahan -->
                        <div class="col-12">
                            <div class="row g-3">
                                @if($training->trainer)
                                <div class="col-12 col-md-6">
                                    <div class="info-item p-3 bg-light rounded-3">
                                        <label class="text-muted small fw-semibold text-uppercase d-block mb-2">
                                            <i class="bi bi-person-badge me-1"></i> Trainer
                                        </label>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="avatar-text avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-weight: bold;">
                                                {{ strtoupper(substr($training->trainer->nama ?? 'T', 0, 2)) }}
                                            </div>
                                            <div>
                                                <p class="fw-semibold mb-0">{{ $training->trainer->nama ?? 'Trainer' }}</p>
                                                <p class="text-muted small mb-0">{{ $training->trainer->email }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <div class="col-12 col-md-6">
                                    <div class="info-item p-3 bg-light rounded-3">
                                        <label class="text-muted small fw-semibold text-uppercase d-block mb-2">
                                            <i class="bi bi-clock me-1"></i> Informasi Waktu
                                        </label>
                                        <div class="small">
                                            <div><i class="bi bi-clock me-1"></i> Dibuat: {{ $training->created_at ? $training->created_at->format('d/m/Y H:i') : '-' }}</div>
                                            <div><i class="bi bi-clock-history me-1"></i> Diperbarui: {{ $training->updated_at ? $training->updated_at->format('d/m/Y H:i') : '-' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="col-12">
                            <hr>
                            <div class="d-flex flex-wrap gap-2 align-items-center">
                                <a href="{{ route('admin.trainings.edit', $training->id) }}" class="btn btn-warning">
                                    <i class="bi bi-pencil me-1"></i> Edit
                                </a>
                                <a href="{{ route('admin.trainings.participants', $training->id) }}" class="btn btn-outline-primary">
                                    <i class="bi bi-people me-1"></i> Peserta
                                </a>
                                <a href="{{ route('admin.trainings.absen', $training->id) }}" class="btn btn-success">
                                    <i class="bi bi-qr-code-scan me-1"></i> Absensi
                                </a>
                                <a href="{{ route('admin.trainings.export', $training->id) }}" class="btn btn-outline-success">
                                    <i class="bi bi-download me-1"></i> Export
                                </a>
                                <form action="{{ route('admin.trainings.destroy', $training->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" 
                                            onclick="return confirm('Yakin ingin menghapus pelatihan ini?')">
                                        <i class="bi bi-trash me-1"></i> Hapus
                                    </button>
                                </form>
                                <div class="ms-auto">
                                    <a href="{{ route('admin.trainings.index') }}" class="btn btn-secondary">
                                        <i class="bi bi-arrow-left me-1"></i> Kembali
                                    </a>
                                </div>
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
    .icon-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .icon-circle i {
        font-size: 18px;
    }
    .info-item {
        transition: all 0.2s ease;
    }
    .info-item:hover {
        background-color: #e9ecef !important;
    }
    .avatar-text {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 14px;
    }
    .bg-primary { background-color: #0d6efd; }
    .bg-danger { background-color: #dc3545; }
    .bg-success { background-color: #198754; }
    .bg-info { background-color: #0dcaf0; }
    .bg-secondary { background-color: #6c757d; }
    .bg-warning { background-color: #ffc107; }
    .text-white { color: #fff; }
</style>
@endpush
@endsection