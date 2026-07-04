@extends('layouts.admin')

@section('title', 'Detail Kategori')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-eye"></i></span>
        <div>
            <p class="eyebrow">Manajemen</p>
            <h1 class="h3 mb-0">Detail Kategori</h1>
            <p class="text-muted mb-0">Informasi lengkap kategori {{ $kategori->nama }}.</p>
        </div>
    </div>
    <div class="heading-actions">
        <div class="d-flex gap-2">
            <a href="{{ route('admin.kategori.edit', $kategori->id) }}" class="btn btn-warning btn-sm">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="{{ route('admin.kategori.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="row">
        <div class="col-12 col-lg-8 mx-auto">
            <div class="panel">
                <div class="panel-header">
                    <h5 class="section-title"><i class="bi bi-info-circle"></i> Informasi Kategori</h5>
                    <span class="badge" style="background-color: {{ $kategori->warna ?? '#6c757d' }}; color: #fff;">
                        <i class="bi {{ $kategori->icon ?? 'bi-tag' }} me-1"></i>
                        {{ $kategori->nama }}
                    </span>
                </div>
                <div class="p-4">
                    <div class="row g-4">
                        <!-- Nama -->
                        <div class="col-12 col-md-6">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-tag fs-4 text-primary"></i>
                                </div>
                                <div>
                                    <label class="text-muted small fw-semibold">Nama Kategori</label>
                                    <p class="fw-semibold mb-0">{{ $kategori->nama }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Slug -->
                        <div class="col-12 col-md-6">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-link-45deg fs-4 text-secondary"></i>
                                </div>
                                <div>
                                    <label class="text-muted small fw-semibold">Slug</label>
                                    <p class="fw-semibold mb-0">{{ $kategori->slug }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Icon -->
                        <div class="col-12 col-md-6">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-icons fs-4 text-info"></i>
                                </div>
                                <div>
                                    <label class="text-muted small fw-semibold">Icon</label>
                                    <p class="fw-semibold mb-0">
                                        <i class="bi {{ $kategori->icon ?? 'bi-tag' }}"></i>
                                        {{ $kategori->icon ?? 'bi-tag' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Warna -->
                        <div class="col-12 col-md-6">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-palette fs-4 text-warning"></i>
                                </div>
                                <div>
                                    <label class="text-muted small fw-semibold">Warna</label>
                                    <p class="fw-semibold mb-0">
                                        <span class="badge" style="background-color: {{ $kategori->warna ?? '#6c757d' }}; color: #fff;">
                                            {{ $kategori->warna ?? '#6c757d' }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        @if($kategori->deskripsi)
                        <div class="col-12">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-file-text fs-4 text-info"></i>
                                </div>
                                <div>
                                    <label class="text-muted small fw-semibold">Deskripsi</label>
                                    <p class="mb-0">{{ $kategori->deskripsi }}</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Statistik -->
                        <div class="col-12">
                            <hr class="my-2">
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <h5 class="card-title text-primary">
                                                <i class="bi bi-book me-2"></i>
                                                {{ $kategori->materis_count ?? $kategori->materis->count() ?? 0 }}
                                            </h5>
                                            <p class="card-text text-muted small">Total Materi</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <h5 class="card-title text-success">
                                                <i class="bi bi-journal-bookmark me-2"></i>
                                                {{ $kategori->trainings_count ?? $kategori->trainings->count() ?? 0 }}
                                            </h5>
                                            <p class="card-text text-muted small">Total Pelatihan</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Meta Info -->
                        <div class="col-12">
                            <hr class="my-2">
                            <div class="d-flex justify-content-between text-muted small">
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
                        <div class="col-12 mt-2">
                            <hr class="my-2">
                            <div class="d-flex gap-2 flex-wrap">
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Data -->
            @if(($kategori->materis_count ?? $kategori->materis->count() ?? 0) > 0 || ($kategori->trainings_count ?? $kategori->trainings->count() ?? 0) > 0)
            <div class="panel mt-3">
                <div class="panel-header">
                    <h5 class="section-title"><i class="bi bi-link-45deg"></i> Data Terkait</h5>
                </div>
                <div class="p-4">
                    @if(($kategori->materis_count ?? $kategori->materis->count() ?? 0) > 0)
                    <div class="mb-3">
                        <h6><i class="bi bi-book text-primary me-2"></i> Materi ({{ $kategori->materis_count ?? $kategori->materis->count() ?? 0 }})</h6>
                        <ul class="list-group">
                            @foreach($kategori->materis as $materi)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $materi->judul }}
                                <span class="badge text-bg-secondary">{{ $materi->status ?? 'Draft' }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    @if(($kategori->trainings_count ?? $kategori->trainings->count() ?? 0) > 0)
                    <div>
                        <h6><i class="bi bi-journal-bookmark text-success me-2"></i> Pelatihan ({{ $kategori->trainings_count ?? $kategori->trainings->count() ?? 0 }})</h6>
                        <ul class="list-group">
                            @foreach($kategori->trainings as $training)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $training->judul }}
                                <span class="badge {{ $training->status == 'published' ? 'badge-published' : 'badge-draft' }}">
                                    {{ ucfirst($training->status ?? 'Draft') }}
                                </span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection