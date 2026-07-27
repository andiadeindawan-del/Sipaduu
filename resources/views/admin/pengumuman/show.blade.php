@extends('layouts.admin')

@section('title', 'Detail Pengumuman')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-megaphone"></i></span>
        <div>
            <p class="eyebrow">Manajemen</p>
            <h1 class="h3 mb-0">Detail Pengumuman</h1>
            <p class="text-muted mb-0">Lihat detail pengumuman</p>
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

            <div class="panel">
                <div class="panel-header">
                    <h5 class="section-title"><i class="bi bi-info-circle"></i> Informasi Pengumuman</h5>
                    <div class="d-flex gap-2">
                        <span class="badge 
                            @if($pengumuman->status == 'published') text-bg-success
                            @elseif($pengumuman->status == 'draft') text-bg-secondary
                            @else text-bg-danger
                            @endif
                        ">
                            {{ ucfirst($pengumuman->status ?? 'Draft') }}
                        </span>
                        @if($pengumuman->is_pinned)
                        <span class="badge text-bg-warning">
                            <i class="bi bi-pin-fill me-1"></i> Pinned
                        </span>
                        @endif
                    </div>
                </div>
                <div class="p-4">
                    <div class="row g-4">
                        <!-- Judul -->
                        <div class="col-12">
                            <h3 class="fw-bold mb-0">{{ $pengumuman->judul }}</h3>
                        </div>

                        <!-- Meta Info -->
                        <div class="col-12">
                            <div class="d-flex flex-wrap gap-3 text-muted small">
                                <span>
                                    <i class="bi bi-clock me-1"></i>
                                    Dibuat: {{ $pengumuman->created_at ? $pengumuman->created_at->format('d/m/Y H:i') : '-' }}
                                </span>
                                <span>
                                    <i class="bi bi-pencil me-1"></i>
                                    Diperbarui: {{ $pengumuman->updated_at ? $pengumuman->updated_at->format('d/m/Y H:i') : '-' }}
                                </span>
                                <span>
                                    <i class="bi bi-person me-1"></i>
                                    Penulis: {{ $pengumuman->creator->nama ?? $pengumuman->creator->name ?? 'Admin' }}
                                </span>
                                @if($pengumuman->training)
                                <span>
                                    <i class="bi bi-journal-bookmark me-1"></i>
                                    Training: {{ $pengumuman->training->judul }}
                                </span>
                                @else
                                <span>
                                    <i class="bi bi-globe me-1"></i>
                                    Umum
                                </span>
                                @endif
                                @if($pengumuman->kategori)
                                <span>
                                    <i class="bi bi-tag me-1"></i>
                                    Kategori: {{ $pengumuman->kategori->nama }}
                                </span>
                                @endif
                                @if($pengumuman->tanggal)
                                <span>
                                    <i class="bi bi-calendar me-1"></i>
                                    Tanggal: {{ $pengumuman->tanggal->format('d/m/Y') }}
                                </span>
                                @endif
                                @if($pengumuman->tanggal_selesai)
                                <span>
                                    <i class="bi bi-calendar-x me-1"></i>
                                    Berlaku s/d: {{ $pengumuman->tanggal_selesai->format('d/m/Y') }}
                                </span>
                                @endif
                            </div>
                        </div>

                        <!-- Target Audience -->
                        <div class="col-12">
                            <label class="text-muted small fw-semibold d-block">Target Audience</label>
                            <span class="badge text-bg-info">
                                {{ ucfirst($pengumuman->target_audience ?? 'All') }}
                            </span>
                        </div>

                        <!-- Deskripsi -->
                        @if($pengumuman->deskripsi)
                        <div class="col-12">
                            <label class="text-muted small fw-semibold d-block">Deskripsi</label>
                            <p class="text-muted">{{ $pengumuman->deskripsi }}</p>
                        </div>
                        @endif

                        <!-- Konten -->
                        <div class="col-12">
                            <label class="text-muted small fw-semibold d-block">Konten</label>
                            <div class="p-3 bg-light rounded-3" style="line-height: 1.8;">
                                {!! nl2br(e($pengumuman->konten)) !!}
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="col-12 col-md-6">
                            <label class="text-muted small fw-semibold d-block">Status</label>
                            <p>
                                <span class="badge 
                                    @if($pengumuman->status == 'published') text-bg-success
                                    @elseif($pengumuman->status == 'draft') text-bg-secondary
                                    @else text-bg-danger
                                    @endif
                                ">
                                    {{ ucfirst($pengumuman->status ?? 'Draft') }}
                                </span>
                            </p>
                        </div>

                        <!-- Dibuat -->
                        <div class="col-12 col-md-6">
                            <label class="text-muted small fw-semibold d-block">Dibuat</label>
                            <p>{{ $pengumuman->created_at ? $pengumuman->created_at->format('d/m/Y H:i') : '-' }}</p>
                        </div>

                        <!-- Actions -->
                        <div class="col-12 mt-2">
                            <hr class="my-2">
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="{{ route('admin.pengumuman.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left me-1"></i> Kembali
                                </a>
                                <a href="{{ route('admin.pengumuman.edit', $pengumuman->id) }}" class="btn btn-warning">
                                    <i class="bi bi-pencil me-1"></i> Edit
                                </a>
                                @if($pengumuman->status == 'draft')
                                <form action="{{ route('admin.pengumuman.update', $pengumuman->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="published">
                                    <button type="submit" class="btn btn-success" onclick="return confirm('Yakin ingin mempublikasikan pengumuman ini?')">
                                        <i class="bi bi-check-circle me-1"></i> Publikasikan
                                    </button>
                                </form>
                                @elseif($pengumuman->status == 'published')
                                <form action="{{ route('admin.pengumuman.update', $pengumuman->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="archived">
                                    <button type="submit" class="btn btn-secondary" onclick="return confirm('Yakin ingin mengarsipkan pengumuman ini?')">
                                        <i class="bi bi-archive me-1"></i> Arsipkan
                                    </button>
                                </form>
                                @endif
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                    <i class="bi bi-trash me-1"></i> Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-triangle text-danger me-2"></i>
                    Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus pengumuman <strong>{{ $pengumuman->judul }}</strong>?</p>
                <p class="text-muted small">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin.pengumuman.destroy', $pengumuman->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-1"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
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
    .bg-light {
        background-color: #f8f9fa !important;
    }
</style>
@endpush
@endsection