@extends('layouts.admin')

@section('title', 'Detail Materi')

@section('header')
<div class="page-heading d-flex justify-content-between align-items-center">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-eye"></i></span>
        <div>
            <p class="eyebrow">Manajemen</p>
            <h1 class="h3 mb-0">Detail Materi</h1>
            <p class="text-muted mb-0">Informasi lengkap materi <strong>{{ $materi->judul }}</strong></p>
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
                    <div>
                        <h5 class="section-title"><i class="bi bi-info-circle"></i> Informasi Materi</h5>
                    </div>
                    <span class="badge {{ $materi->status_badge ?? 'badge-draft' }}">
                        <i class="bi bi-circle-fill me-1" style="font-size: 6px;"></i>
                        {{ $materi->status_label ?? ucfirst($materi->status ?? 'Draft') }}
                    </span>
                </div>

                <div class="p-4">
                    <div class="row g-4">
                        <!-- Judul -->
                        <div class="col-12">
                            <div class="info-item p-3 bg-light rounded-3">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="icon-circle bg-primary text-white">
                                        <i class="bi bi-text-paragraph"></i>
                                    </div>
                                    <div>
                                        <label class="text-muted small fw-semibold text-uppercase d-block">Judul</label>
                                        <p class="fw-semibold mb-0 fs-5">{{ $materi->judul }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Info Grid -->
                        <div class="col-12">
                            <div class="row g-3">
                                <!-- Slug -->
                                <div class="col-12 col-md-4">
                                    <div class="info-item p-3 bg-light rounded-3 h-100">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="icon-circle bg-secondary text-white">
                                                <i class="bi bi-link-45deg"></i>
                                            </div>
                                            <div>
                                                <label class="text-muted small fw-semibold text-uppercase d-block">Slug</label>
                                                <p class="fw-semibold mb-0" id="slugText" style="cursor: pointer;" title="Klik untuk copy slug">
                                                    <code>{{ $materi->slug }}</code>
                                                    <i class="bi bi-copy ms-1 text-muted" style="font-size: 12px;"></i>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Kategori -->
                                <div class="col-12 col-md-4">
                                    <div class="info-item p-3 bg-light rounded-3 h-100">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="icon-circle bg-info text-white">
                                                <i class="bi bi-tag"></i>
                                            </div>
                                            <div>
                                                <label class="text-muted small fw-semibold text-uppercase d-block">Kategori</label>
                                                @if($materi->kategori)
                                                <p class="fw-semibold mb-0">
                                                    <span class="badge" style="background-color: {{ $materi->kategori->warna ?? '#6c757d' }}; color: #fff; padding: 6px 12px;">
                                                        <i class="bi {{ $materi->kategori->icon ?? 'bi-tag' }} me-1"></i>
                                                        {{ $materi->kategori->nama }}
                                                    </span>
                                                </p>
                                                @else
                                                <p class="text-muted mb-0">-</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Training -->
                                <div class="col-12 col-md-4">
                                    <div class="info-item p-3 bg-light rounded-3 h-100">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="icon-circle bg-success text-white">
                                                <i class="bi bi-journal-bookmark"></i>
                                            </div>
                                            <div>
                                                <label class="text-muted small fw-semibold text-uppercase d-block">Training</label>
                                                @if($materi->training)
                                                <p class="fw-semibold mb-0">
                                                    <a href="{{ route('admin.trainings.show', $materi->training->id) }}" class="text-decoration-none">
                                                        {{ $materi->training->judul }}
                                                    </a>
                                                </p>
                                                @else
                                                <p class="text-muted mb-0">-</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Durasi & Urutan -->
                        <div class="col-12">
                            <div class="row g-3">
                                <!-- Durasi -->
                                <div class="col-12 col-md-6">
                                    <div class="info-item p-3 bg-light rounded-3 h-100">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="icon-circle bg-warning text-white">
                                                <i class="bi bi-clock"></i>
                                            </div>
                                            <div>
                                                <label class="text-muted small fw-semibold text-uppercase d-block">Durasi</label>
                                                <p class="fw-semibold mb-0">
                                                    @if($materi->durasi)
                                                    <span class="badge bg-secondary">
                                                        <i class="bi bi-clock me-1"></i>
                                                        {{ $materi->durasi }} menit
                                                    </span>
                                                    @else
                                                    <span class="text-muted">-</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Urutan -->
                                <div class="col-12 col-md-6">
                                    <div class="info-item p-3 bg-light rounded-3 h-100">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="icon-circle bg-primary text-white">
                                                <i class="bi bi-list-ol"></i>
                                            </div>
                                            <div>
                                                <label class="text-muted small fw-semibold text-uppercase d-block">Urutan</label>
                                                <p class="fw-semibold mb-0">{{ $materi->order ?? 0 }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Files -->
                        <div class="col-12">
                            <div class="info-item p-3 bg-light rounded-3">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="icon-circle bg-danger text-white">
                                        <i class="bi bi-files"></i>
                                    </div>
                                    <div style="flex: 1; min-width: 0;">
                                        <label class="text-muted small fw-semibold text-uppercase d-block">
                                            File Materi
                                            @if($materi->files && count($materi->files) > 0)
                                            <span class="badge bg-primary ms-2">{{ count($materi->files) }}</span>
                                            @endif
                                        </label>
                                        @if($materi->files && count($materi->files) > 0)
                                        <div class="table-responsive mt-2">
                                            <table class="table table-sm table-hover mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th width="40">#</th>
                                                        <th>Nama File</th>
                                                        <th>Tipe</th>
                                                        <th>Ukuran</th>
                                                        <th class="text-end">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($materi->files as $index => $file)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>
                                                            <i class="{{ $materi->getFileIcon($file['type'] ?? 'other') }} me-2"></i>
                                                            {{ $file['name'] ?? basename($file['path'] ?? $file['url'] ?? '') }}
                                                            @if($file['is_main'] ?? false)
                                                                <span class="badge bg-primary ms-1">Utama</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-info">
                                                                {{ $materi->getFileTypeLabel($file['type'] ?? 'other') }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            @if(isset($file['size']) && $file['size'])
                                                                {{ number_format($file['size'] / 1024 / 1024, 2) }} MB
                                                            @elseif(!empty($file['path']))
                                                                @php
                                                                    try {
                                                                        $size = Storage::disk('public')->size($file['path']);
                                                                        echo number_format($size / 1024 / 1024, 2) . ' MB';
                                                                    } catch(\Exception $e) {
                                                                        echo '-';
                                                                    }
                                                                @endphp
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                        <td class="text-end">
                                                            @if(!empty($file['path']))
                                                                <a href="{{ route('admin.materi.download', ['materi' => $materi->id, 'index' => $index]) }}" 
                                                                   class="btn btn-sm btn-success" target="_blank" title="Download">
                                                                    <i class="bi bi-download"></i>
                                                                </a>
                                                            @elseif(!empty($file['url']))
                                                                <a href="{{ $file['url'] }}" 
                                                                   class="btn btn-sm btn-info" target="_blank" title="Buka Link">
                                                                    <i class="bi bi-box-arrow-up-right"></i>
                                                                </a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        @else
                                        <p class="text-muted mb-0">Tidak ada file</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        @if($materi->deskripsi)
                        <div class="col-12">
                            <div class="info-item p-3 bg-light rounded-3">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="icon-circle bg-secondary text-white">
                                        <i class="bi bi-file-text"></i>
                                    </div>
                                    <div>
                                        <label class="text-muted small fw-semibold text-uppercase d-block">Deskripsi</label>
                                        <p class="mb-0" style="white-space: pre-line;">{{ $materi->deskripsi }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Konten -->
                        @if($materi->konten)
                        <div class="col-12">
                            <div class="info-item p-3 bg-light rounded-3">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="icon-circle bg-success text-white">
                                        <i class="bi bi-file-richtext"></i>
                                    </div>
                                    <div style="flex: 1; min-width: 0;">
                                        <label class="text-muted small fw-semibold text-uppercase d-block">Konten</label>
                                        <div class="p-3 bg-white rounded-3 border konten-wrapper">
                                            {!! $materi->konten !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Meta Info -->
                        <div class="col-12">
                            <hr>
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <div class="info-item p-3 bg-light rounded-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="icon-circle bg-info text-white">
                                                <i class="bi bi-clock"></i>
                                            </div>
                                            <div>
                                                <label class="text-muted small fw-semibold text-uppercase d-block">Dibuat</label>
                                                <p class="fw-semibold mb-0">
                                                    {{ $materi->created_at ? $materi->created_at->format('d/m/Y H:i') : '-' }}
                                                    @if($materi->created_at)
                                                    <span class="text-muted small ms-1">
                                                        ({{ $materi->created_at->diffForHumans() }})
                                                    </span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="info-item p-3 bg-light rounded-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="icon-circle bg-warning text-white">
                                                <i class="bi bi-clock-history"></i>
                                            </div>
                                            <div>
                                                <label class="text-muted small fw-semibold text-uppercase d-block">Diperbarui</label>
                                                <p class="fw-semibold mb-0">
                                                    {{ $materi->updated_at ? $materi->updated_at->format('d/m/Y H:i') : '-' }}
                                                    @if($materi->updated_at && $materi->updated_at != $materi->created_at)
                                                    <span class="text-muted small ms-1">
                                                        ({{ $materi->updated_at->diffForHumans() }})
                                                    </span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="col-12">
                            <hr>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('admin.materi.edit', $materi->id) }}" class="btn btn-warning">
                                    <i class="bi bi-pencil me-1"></i> Edit
                                </a>
                                <form action="{{ route('admin.materi.destroy', $materi->id) }}" method="POST" class="d-inline" 
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus materi {{ $materi->judul }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="bi bi-trash me-1"></i> Hapus
                                    </button>
                                </form>
                                <div class="ms-auto">
                                    <a href="{{ route('admin.materi.index') }}" class="btn btn-secondary">
                                        <i class="bi bi-arrow-left me-1"></i> Kembali
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Data Card -->
            <div class="panel mt-4">
                <div class="panel-header">
                    <h5 class="section-title"><i class="bi bi-link-45deg"></i> Data Terkait</h5>
                </div>
                <div class="p-4">
                    <div class="row g-3">
                        <!-- Kategori -->
                        <div class="col-12 col-md-6">
                            <div class="related-card p-3 bg-light rounded-3 h-100">
                                <h6 class="fw-bold text-primary">
                                    <i class="bi bi-tag me-2"></i>
                                    Kategori
                                </h6>
                                @if($materi->kategori)
                                <p class="fw-semibold mb-1">
                                    <span class="badge" style="background-color: {{ $materi->kategori->warna ?? '#6c757d' }}; color: #fff; padding: 6px 12px;">
                                        <i class="bi {{ $materi->kategori->icon ?? 'bi-tag' }} me-1"></i>
                                        {{ $materi->kategori->nama }}
                                    </span>
                                </p>
                                @if($materi->kategori->deskripsi)
                                <small class="text-muted">{{ Str::limit($materi->kategori->deskripsi, 100) }}</small>
                                @endif
                                @else
                                <p class="text-muted mb-0">Tidak ada kategori</p>
                                @endif
                            </div>
                        </div>

                        <!-- Training -->
                        <div class="col-12 col-md-6">
                            <div class="related-card p-3 bg-light rounded-3 h-100">
                                <h6 class="fw-bold text-success">
                                    <i class="bi bi-journal-bookmark me-2"></i>
                                    Training
                                </h6>
                                @if($materi->training)
                                <p class="fw-semibold mb-1">
                                    <a href="{{ route('admin.trainings.show', $materi->training->id) }}" class="text-decoration-none">
                                        {{ $materi->training->judul }}
                                    </a>
                                </p>
                                @if($materi->training->deskripsi)
                                <small class="text-muted">{{ Str::limit($materi->training->deskripsi, 100) }}</small>
                                @endif
                                @else
                                <p class="text-muted mb-0">Tidak ada training</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="panel mt-4">
                <div class="panel-header">
                    <h5 class="section-title"><i class="bi bi-bar-chart"></i> Statistik</h5>
                </div>
                <div class="p-4">
                    <div class="row g-3">
                        <div class="col-6 col-md-4">
                            <div class="stat-card p-3 text-center bg-primary bg-opacity-10 rounded-3">
                                <h6 class="text-muted small mb-1">Total File</h6>
                                <h3 class="fw-bold text-primary mb-0">{{ $materi->total_files ?? 0 }}</h3>
                            </div>
                        </div>
                        <div class="col-6 col-md-4">
                            <div class="stat-card p-3 text-center bg-warning bg-opacity-10 rounded-3">
                                <h6 class="text-muted small mb-1">Durasi</h6>
                                <h3 class="fw-bold text-warning mb-0">{{ $materi->durasi ?? 0 }} <small class="fs-6 fw-normal text-muted">menit</small></h3>
                            </div>
                        </div>
                        <div class="col-6 col-md-4">
                            <div class="stat-card p-3 text-center bg-success bg-opacity-10 rounded-3">
                                <h6 class="text-muted small mb-1">Status</h6>
                                <h5 class="mb-0">
                                    <span class="badge {{ $materi->status_badge ?? 'badge-draft' }}" style="font-size: 1rem; padding: 6px 14px;">
                                        <i class="bi bi-circle-fill me-1" style="font-size: 6px;"></i>
                                        {{ $materi->status_label ?? ucfirst($materi->status ?? 'Draft') }}
                                    </span>
                                </h5>
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
    /* ============================================================
       PANEL
    ============================================================ */
    .panel {
        background: #fff;
        border-radius: .75rem;
        box-shadow: 0 1px 4px rgba(0,0,0,.06);
        overflow: hidden;
    }
    .panel:hover {
        box-shadow: 0 8px 30px rgba(0,0,0,0.06);
    }
    
    .panel-header {
        padding: .9rem 1.25rem;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: .75rem;
        background: #fafbfc;
    }
    
    .section-title {
        display: flex;
        align-items: center;
        gap: .5rem;
        margin: 0;
        font-size: 1rem;
        font-weight: 600;
        color: #1a2236;
    }
    
    .section-title i {
        color: #4e9af1;
    }

    /* ============================================================
       INFO ITEMS
    ============================================================ */
    .info-item {
        transition: all 0.2s ease;
    }
    .info-item:hover {
        background-color: #e9ecef !important;
    }
    
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
    
    .bg-primary { background-color: #0d6efd; }
    .bg-success { background-color: #198754; }
    .bg-info { background-color: #0dcaf0; }
    .bg-warning { background-color: #ffc107; }
    .bg-danger { background-color: #dc3545; }
    .bg-secondary { background-color: #6c757d; }
    .text-white { color: #fff; }

    /* ============================================================
       BADGE
    ============================================================ */
    .badge {
        font-weight: 500;
        padding: 0.4rem 0.8rem;
        font-size: 0.75rem;
    }
    
    .badge-draft {
        background: #e2e8f0 !important;
        color: #4a5568 !important;
    }
    .badge-published {
        background: #d4edda !important;
        color: #155724 !important;
    }
    .badge-secondary {
        background: #e2e8f0 !important;
        color: #6c757d !important;
    }

    /* ============================================================
       RELATED CARD
    ============================================================ */
    .related-card {
        transition: all 0.2s ease;
    }
    .related-card:hover {
        background-color: #e9ecef !important;
    }

    /* ============================================================
       STAT CARD
    ============================================================ */
    .stat-card {
        transition: all 0.2s ease;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    .bg-primary.bg-opacity-10 { background-color: rgba(13, 110, 253, 0.1); }
    .bg-warning.bg-opacity-10 { background-color: rgba(255, 193, 7, 0.1); }
    .bg-success.bg-opacity-10 { background-color: rgba(25, 135, 84, 0.1); }

    /* ============================================================
       KONTEN WRAPPER
    ============================================================ */
    .konten-wrapper {
        max-height: 400px;
        overflow-y: auto;
        line-height: 1.8;
    }
    .konten-wrapper::-webkit-scrollbar {
        width: 6px;
    }
    .konten-wrapper::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    .konten-wrapper::-webkit-scrollbar-thumb {
        background: #c1c7cd;
        border-radius: 4px;
    }
    .konten-wrapper::-webkit-scrollbar-thumb:hover {
        background: #a8b0b8;
    }
    .konten-wrapper img {
        max-width: 100%;
        height: auto;
    }
    .konten-wrapper iframe,
    .konten-wrapper video {
        max-width: 100%;
    }

    /* ============================================================
       TABLE
    ============================================================ */
    .table th {
        font-weight: 600;
        color: #6c757d;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #f0f0f0;
        padding: 0.5rem 0.75rem;
        background: #fafbfc;
    }
    .table td {
        font-size: 0.85rem;
        padding: 0.5rem 0.75rem;
        vertical-align: middle;
    }
    .table tbody tr {
        transition: background 0.2s ease;
    }
    .table tbody tr:hover {
        background: #f8fafc;
    }

    /* ============================================================
       BUTTONS
    ============================================================ */
    .btn {
        border-radius: 0.5rem;
        padding: 0.45rem 1.2rem;
        font-weight: 500;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }
    
    .btn-warning {
        background: #ff9f43;
        border-color: #ff9f43;
        color: #fff;
    }
    .btn-warning:hover {
        background: #f08c2e;
        border-color: #f08c2e;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(255, 159, 67, 0.3);
    }
    
    .btn-danger {
        background: #f56565;
        border-color: #f56565;
        color: #fff;
    }
    .btn-danger:hover {
        background: #e53e3e;
        border-color: #e53e3e;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(245, 101, 101, 0.3);
    }
    
    .btn-outline-secondary {
        border-color: #e2e8f0;
        color: #4a5568;
    }
    .btn-outline-secondary:hover {
        background: #e2e8f0;
        border-color: #d5dce6;
    }
    
    .btn-sm {
        padding: 0.3rem 0.8rem;
        font-size: 0.8rem;
    }

    /* ============================================================
       ALERT
    ============================================================ */
    .alert {
        border-radius: 0.75rem;
        border: none;
        padding: 0.75rem 1rem;
    }
    .alert-success {
        background: #ecfdf5;
        color: #065f46;
    }
    .alert-danger {
        background: #fef2f2;
        color: #991b1b;
    }
    .alert-dismissible .btn-close {
        padding: 1rem;
    }

    /* ============================================================
       RESPONSIVE
    ============================================================ */
    @media (max-width: 768px) {
        .page-heading {
            flex-direction: column;
            align-items: flex-start;
        }
        .page-heading-copy {
            width: 100%;
        }
        .heading-actions {
            width: 100%;
            flex-wrap: wrap;
        }
        .panel-header {
            flex-direction: column;
            align-items: flex-start;
        }
        .panel-body {
            padding: 1.25rem !important;
        }
        .d-flex.flex-wrap.gap-2 {
            flex-direction: column;
        }
        .d-flex.flex-wrap.gap-2 .btn {
            width: 100%;
        }
        .ms-auto {
            margin-left: 0 !important;
        }
        .icon-circle {
            width: 36px;
            height: 36px;
        }
        .icon-circle i {
            font-size: 16px;
        }
        .row.g-3 > [class*="col-"] {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }
        .table-responsive {
            font-size: 0.8rem;
        }
        .table th,
        .table td {
            padding: 0.4rem 0.5rem;
        }
    }

    /* ============================================================
       ANIMATION
    ============================================================ */
    .panel {
        animation: fadeInUp 0.4s ease;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
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
    // COPY SLUG TO CLIPBOARD
    // ============================================================
    const slugText = document.getElementById('slugText');
    if (slugText) {
        slugText.addEventListener('click', function() {
            const slug = '{{ $materi->slug }}';
            if (navigator.clipboard) {
                navigator.clipboard.writeText(slug).then(function() {
                    const icon = this.querySelector('.bi-copy');
                    if (icon) {
                        icon.className = 'bi bi-check-circle ms-1 text-success';
                        setTimeout(() => {
                            icon.className = 'bi bi-copy ms-1 text-muted';
                        }, 2000);
                    }
                }.bind(this)).catch(function() {
                    fallbackCopy(slug);
                });
            } else {
                fallbackCopy(slug);
            }
        });
    }

    function fallbackCopy(text) {
        const input = document.createElement('input');
        input.value = text;
        document.body.appendChild(input);
        input.select();
        try {
            document.execCommand('copy');
            alert('✅ Slug berhasil disalin: ' + text);
        } catch (e) {
            alert('Slug: ' + text);
        }
        document.body.removeChild(input);
    }
});
</script>
@endpush
@endsection