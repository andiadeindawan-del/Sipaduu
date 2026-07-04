@extends('layouts.admin')

@section('title', 'Detail Pelatihan')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-eye"></i></span>
        <div>
            <p class="eyebrow">Manajemen</p>
            <h1 class="h3 mb-0">Detail Pelatihan</h1>
            <p class="text-muted mb-0">Informasi lengkap pelatihan {{ $training->judul }}.</p>
        </div>
    </div>
    <div class="heading-actions">
        <div class="d-flex gap-2">
            <a href="{{ route('admin.trainings.edit', $training->id) }}" class="btn btn-warning btn-sm">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="{{ route('admin.trainings.participants', $training->id) }}" class="btn btn-info btn-sm">
                <i class="bi bi-people"></i> Peserta
            </a>
            <a href="{{ route('admin.trainings.export', $training->id) }}" class="btn btn-success btn-sm">
                <i class="bi bi-download"></i> Export
            </a>
            <a href="{{ route('admin.trainings.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
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
                    <div class="row g-4">
                        <!-- Gambar -->
                        @if($training->gambar)
                        <div class="col-12 text-center mb-3">
                            <img src="{{ asset('storage/' . $training->gambar) }}" 
                                 alt="{{ $training->judul }}" 
                                 style="max-width: 100%; max-height: 300px; border-radius: 8px; border: 1px solid #ddd; padding: 4px;">
                        </div>
                        @endif

                        <!-- Judul -->
                        <div class="col-12">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-text-paragraph fs-4 text-primary"></i>
                                </div>
                                <div>
                                    <label class="text-muted small fw-semibold">Judul Pelatihan</label>
                                    <p class="fw-semibold mb-0 fs-5">{{ $training->judul }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Kategori & Tipe -->
                        <div class="col-12 col-md-6">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-tag fs-4 text-info"></i>
                                </div>
                                <div>
                                    <label class="text-muted small fw-semibold">Kategori</label>
                                    <p class="fw-semibold mb-0">
                                        @if($training->kategori)
                                        <span class="badge text-bg-info">{{ $training->kategori->nama }}</span>
                                        @else
                                        <span class="text-muted">-</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-laptop fs-4 text-success"></i>
                                </div>
                                <div>
                                    <label class="text-muted small fw-semibold">Tipe Pelatihan</label>
                                    <p class="fw-semibold mb-0">
                                        <span class="badge {{ $training->tipe == 'online' ? 'text-bg-primary' : ($training->tipe == 'offline' ? 'text-bg-secondary' : 'text-bg-warning') }}">
                                            {{ ucfirst($training->tipe) }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Trainer -->
                        <div class="col-12 col-md-6">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-person-badge fs-4 text-primary"></i>
                                </div>
                                <div>
                                    <label class="text-muted small fw-semibold">Trainer</label>
                                    @if($training->trainer)
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-text avatar-sm">
                                            {{ strtoupper(substr($training->trainer->nama ?? 'T', 0, 2)) }}
                                        </div>
                                        <div>
                                            <p class="fw-semibold mb-0">{{ $training->trainer->nama ?? 'Trainer' }}</p>
                                            <p class="text-muted small mb-0">{{ $training->trainer->email }}</p>
                                        </div>
                                    </div>
                                    @else
                                    <p class="text-muted">-</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="col-12 col-md-6">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-toggle-on fs-4 text-warning"></i>
                                </div>
                                <div>
                                    <label class="text-muted small fw-semibold">Status</label>
                                    <p class="fw-semibold mb-0">
                                        <span class="badge {{ $training->status == 'published' ? 'badge-published' : ($training->status == 'berjalan' ? 'badge-berjalan' : ($training->status == 'selesai' ? 'badge-selesai' : ($training->status == 'dibatalkan' ? 'badge-dibatalkan' : 'badge-draft'))) }}">
                                            {{ ucfirst($training->status) }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Tanggal -->
                        <div class="col-12 col-md-6">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-calendar3 fs-4 text-primary"></i>
                                </div>
                                <div>
                                    <label class="text-muted small fw-semibold">Tanggal Mulai</label>
                                    <p class="fw-semibold mb-0">{{ $training->tanggal_mulai ? $training->tanggal_mulai->format('d/m/Y') : '-' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-calendar3 fs-4 text-danger"></i>
                                </div>
                                <div>
                                    <label class="text-muted small fw-semibold">Tanggal Selesai</label>
                                    <p class="fw-semibold mb-0">{{ $training->tanggal_selesai ? $training->tanggal_selesai->format('d/m/Y') : '-' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Lokasi & Link Meeting -->
                        <div class="col-12 col-md-6">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-geo-alt fs-4 text-secondary"></i>
                                </div>
                                <div>
                                    <label class="text-muted small fw-semibold">Lokasi</label>
                                    <p class="fw-semibold mb-0">{{ $training->lokasi ?? '-' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-link fs-4 text-info"></i>
                                </div>
                                <div>
                                    <label class="text-muted small fw-semibold">Link Meeting</label>
                                    @if($training->link_meeting)
                                    <a href="{{ $training->link_meeting }}" target="_blank" class="fw-semibold">
                                        {{ $training->link_meeting }}
                                    </a>
                                    @else
                                    <p class="text-muted">-</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Kapasitas & Peserta -->
                        <div class="col-12 col-md-6">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-people fs-4 text-success"></i>
                                </div>
                                <div>
                                    <label class="text-muted small fw-semibold">Kapasitas</label>
                                    <p class="fw-semibold mb-0">{{ $training->kapasitas ?? 'Tidak terbatas' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-people-fill fs-4 text-primary"></i>
                                </div>
                                <div>
                                    <label class="text-muted small fw-semibold">Peserta Terdaftar</label>
                                    <p class="fw-semibold mb-0">
                                        {{ $participantsCount ?? 0 }} peserta
                                        @if($training->kapasitas)
                                        <span class="text-muted small">({{ $availableSlots ?? 0 }} slot tersedia)</span>
                                        @endif
                                    </p>
                                    <a href="{{ route('admin.trainings.participants', $training->id) }}" class="btn btn-sm btn-outline-primary mt-1">
                                        <i class="bi bi-eye"></i> Lihat Peserta
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        @if($training->deskripsi)
                        <div class="col-12">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-file-text fs-4 text-info"></i>
                                </div>
                                <div>
                                    <label class="text-muted small fw-semibold">Deskripsi</label>
                                    <p class="mb-0">{{ $training->deskripsi }}</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Meta Info -->
                        <div class="col-12">
                            <hr class="my-2">
                            <div class="d-flex justify-content-between text-muted small">
                                <span>
                                    <i class="bi bi-clock me-1"></i> 
                                    Dibuat: {{ $training->created_at ? $training->created_at->format('d/m/Y H:i') : '-' }}
                                </span>
                                <span>
                                    <i class="bi bi-clock-history me-1"></i> 
                                    Diperbarui: {{ $training->updated_at ? $training->updated_at->format('d/m/Y H:i') : '-' }}
                                </span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="col-12 mt-2">
                            <hr class="my-2">
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="{{ route('admin.trainings.edit', $training->id) }}" class="btn btn-warning">
                                    <i class="bi bi-pencil me-1"></i> Edit Pelatihan
                                </a>
                                <a href="{{ route('admin.trainings.export', $training->id) }}" class="btn btn-success">
                                    <i class="bi bi-download me-1"></i> Export Peserta
                                </a>
                                <form action="{{ route('admin.trainings.destroy', $training->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" 
                                            onclick="return confirm('Yakin ingin menghapus pelatihan ini?')">
                                        <i class="bi bi-trash me-1"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection