@extends('layouts.admin')

@section('title', 'Detail Kategori')

@section('header')
<div class="page-heading d-flex justify-content-between align-items-center">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-eye"></i></span>
        <div>
            <p class="eyebrow">Manajemen</p>
            <h1 class="h3 mb-0">Detail Kategori</h1>
            <p class="text-muted mb-0">Informasi lengkap kategori <strong>{{ $kategori->nama }}</strong></p>
        </div>
    </div>
   
</div>
@endsection

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="row">
        <div class="col-12 col-lg-8 mx-auto">
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
                    <h5 class="section-title"><i class="bi bi-info-circle"></i> Informasi Kategori</h5>
                    <span class="badge" style="background-color: {{ $kategori->warna ?? '#6c757d' }}; color: #fff; padding: 8px 16px;">
                        <i class="bi {{ $kategori->icon ?? 'bi-tag' }} me-1"></i>
                        {{ $kategori->nama }}
                    </span>
                </div>
                <div class="p-4">
                    <div class="row g-4">
                        <!-- Nama & Icon -->
                        <div class="col-12">
                            <div class="text-center mb-4">
                                <div class="display-1 text-primary mb-2">
                                    <i class="bi {{ $kategori->icon ?? 'bi-tag' }}"></i>
                                </div>
                                <h3 class="fw-bold mb-1">{{ $kategori->nama }}</h3>
                                <span class="text-muted small">Slug: {{ $kategori->slug }}</span>
                            </div>
                        </div>

                        <!-- Info Grid -->
                        <div class="col-12">
                            <div class="row g-3">
                                <!-- Icon -->
                                <div class="col-12 col-md-6">
                                    <div class="info-item p-3 bg-light rounded-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="icon-circle bg-info text-white">
                                                <i class="bi bi-icons"></i>
                                            </div>
                                            <div>
                                                <label class="text-muted small fw-semibold text-uppercase d-block">Icon</label>
                                                <p class="fw-semibold mb-0">
                                                    <i class="bi {{ $kategori->icon ?? 'bi-tag' }} me-1"></i>
                                                    {{ $kategori->icon ?? 'bi-tag' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Warna -->
                                <div class="col-12 col-md-6">
                                    <div class="info-item p-3 bg-light rounded-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="icon-circle bg-warning text-white">
                                                <i class="bi bi-palette"></i>
                                            </div>
                                            <div>
                                                <label class="text-muted small fw-semibold text-uppercase d-block">Warna</label>
                                                <p class="fw-semibold mb-0">
                                                    <span class="badge" style="background-color: {{ $kategori->warna ?? '#6c757d' }}; color: #fff; padding: 6px 12px;">
                                                        {{ $kategori->warna ?? '#6c757d' }}
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Deskripsi -->
                                @if($kategori->deskripsi)
                                <div class="col-12">
                                    <div class="info-item p-3 bg-light rounded-3">
                                        <div class="d-flex align-items-start gap-3">
                                            <div class="icon-circle bg-secondary text-white">
                                                <i class="bi bi-file-text"></i>
                                            </div>
                                            <div>
                                                <label class="text-muted small fw-semibold text-uppercase d-block">Deskripsi</label>
                                                <p class="mb-0">{{ $kategori->deskripsi }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Statistik -->
                        <div class="col-12">
                            <hr>
                            <h6 class="fw-bold mb-3"><i class="bi bi-graph-up me-2"></i>Statistik</h6>
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <div class="stat-card p-4 bg-primary bg-opacity-10 rounded-3 text-center">
                                        <div class="display-4 fw-bold text-primary">
                                            {{ $kategori->materis_count ?? $kategori->materis->count() ?? 0 }}
                                        </div>
                                        <p class="text-muted mb-0">
                                            <i class="bi bi-book me-1"></i> Total Materi
                                        </p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="stat-card p-4 bg-success bg-opacity-10 rounded-3 text-center">
                                        <div class="display-4 fw-bold text-success">
                                            {{ $kategori->trainings_count ?? $kategori->trainings->count() ?? 0 }}
                                        </div>
                                        <p class="text-muted mb-0">
                                            <i class="bi bi-journal-bookmark me-1"></i> Total Pelatihan
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Meta Info -->
                        <div class="col-12">
                            <hr>
                            <div class="d-flex flex-wrap justify-content-between text-muted small">
                                <span>
                                    <i class="bi bi-clock me-1"></i> 
                                    Dibuat: {{ $kategori->created_at ? $kategori->created_at->format('d/m/Y H:i') : '-' }}
                                </span>
                                <span>
                                    <i class="bi bi-clock-history me-1"></i> 
                                    Diperbarui: {{ $kategori->updated_at ? $kategori->updated_at->format('d/m/Y H:i') : '-' }}
                                </span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="col-12">
                            <hr>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('admin.kategori.edit', $kategori->id) }}" class="btn btn-warning">
                                    <i class="bi bi-pencil me-1"></i> Edit Kategori
                                </a>
                                <form action="{{ route('admin.kategori.destroy', $kategori->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" 
                                            onclick="return confirm('Yakin ingin menghapus kategori ini?')">
                                        <i class="bi bi-trash me-1"></i> Hapus
                                    </button>
                                </form>
                                <div class="ms-auto">
                                    <a href="{{ route('admin.kategori.index') }}" class="btn btn-secondary">
                                        <i class="bi bi-arrow-left me-1"></i> Kembali
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Data -->
            @if(($kategori->materis_count ?? $kategori->materis->count() ?? 0) > 0 || ($kategori->trainings_count ?? $kategori->trainings->count() ?? 0) > 0)
            <div class="panel mt-4">
                <div class="panel-header">
                    <h5 class="section-title"><i class="bi bi-link-45deg"></i> Data Terkait</h5>
                </div>
                <div class="p-4">
                    @if(($kategori->materis_count ?? $kategori->materis->count() ?? 0) > 0)
                    <div class="mb-4">
                        <h6 class="fw-bold text-primary">
                            <i class="bi bi-book me-2"></i>
                            Materi ({{ $kategori->materis_count ?? $kategori->materis->count() ?? 0 }})
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Judul</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($kategori->materis as $index => $materi)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $materi->judul }}</td>
                                        <td>
                                            <span class="badge {{ $materi->status == 'published' ? 'badge-published' : 'badge-draft' }}">
                                                {{ ucfirst($materi->status ?? 'Draft') }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                    @if(($kategori->trainings_count ?? $kategori->trainings->count() ?? 0) > 0)
                    <div>
                        <h6 class="fw-bold text-success">
                            <i class="bi bi-journal-bookmark me-2"></i>
                            Pelatihan ({{ $kategori->trainings_count ?? $kategori->trainings->count() ?? 0 }})
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Judul</th>
                                        <th>Tanggal</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($kategori->trainings as $index => $training)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $training->judul }}</td>
                                        <td>
                                            {{ $training->tanggal_mulai ? $training->tanggal_mulai->format('d/m/Y') : '-' }}
                                            - 
                                            {{ $training->tanggal_selesai ? $training->tanggal_selesai->format('d/m/Y') : '-' }}
                                        </td>
                                        <td>
                                            <span class="badge {{ $training->status == 'published' ? 'badge-published' : ($training->status == 'berjalan' ? 'badge-berjalan' : ($training->status == 'selesai' ? 'badge-selesai' : ($training->status == 'dibatalkan' ? 'badge-dibatalkan' : 'badge-draft'))) }}">
                                                {{ ucfirst($training->status ?? 'Draft') }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
    .icon-circle {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .icon-circle i {
        font-size: 20px;
    }
    .info-item {
        transition: all 0.2s ease;
    }
    .info-item:hover {
        background-color: #e9ecef !important;
    }
    .stat-card {
        transition: all 0.2s ease;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .bg-primary { background-color: #0d6efd; }
    .bg-success { background-color: #198754; }
    .bg-info { background-color: #0dcaf0; }
    .bg-warning { background-color: #ffc107; }
    .bg-secondary { background-color: #6c757d; }
    .text-white { color: #fff; }
    .bg-primary.bg-opacity-10 { background-color: rgba(13, 110, 253, 0.1); }
    .bg-success.bg-opacity-10 { background-color: rgba(25, 135, 84, 0.1); }
</style>
@endpush
@endsection