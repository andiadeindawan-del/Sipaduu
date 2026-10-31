@extends('layouts.pemateri')

@section('title', 'Detail Dokumentasi: ' . $dokumentasi->judul)

@section('header')
<div class="page-heading d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-images"></i></span>
        <div>
            <p class="eyebrow">Aktivitas Pemateri</p>
            <h1 class="h3 mb-0">{{ $dokumentasi->judul }}</h1>
            <p class="text-muted mb-0">Rincian tautan dokumentasi pelatihan.</p>
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('pemateri.dokumentasi.edit', $dokumentasi) }}" class="btn btn-warning text-dark btn-sm fw-semibold">
            <i class="bi bi-pencil me-1"></i> Edit
        </a>
        <a href="{{ route('pemateri.dokumentasi.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="row">
        <div class="col-12 col-lg-8 mx-auto">
            <div class="panel border-0 shadow-sm rounded-3">
                <div class="panel-header py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="section-title text-primary"><i class="bi bi-info-circle me-1"></i> Informasi Dokumentasi</h5>
                    <span class="badge bg-light text-dark border">
                        <i class="bi bi-calendar3 me-1 text-primary"></i> {{ $dokumentasi->created_at ? $dokumentasi->created_at->format('d/m/Y H:i') : '-' }}
                    </span>
                </div>
                <div class="p-4">
                    <div class="mb-4">
                        <span class="text-muted small d-block mb-1">Pelatihan Terkait</span>
                        <h6 class="fw-bold text-dark mb-0">
                            <i class="bi bi-journal-text text-primary me-1"></i>
                            {{ $dokumentasi->training->judul ?? '-' }}
                        </h6>
                    </div>

                    <div class="mb-4">
                        <span class="text-muted small d-block mb-1">Judul Dokumentasi</span>
                        <h5 class="fw-bold text-dark mb-0">{{ $dokumentasi->judul }}</h5>
                    </div>

                    @if($dokumentasi->deskripsi)
                        <div class="mb-4">
                            <span class="text-muted small d-block mb-1">Deskripsi / Keterangan</span>
                            <div class="p-3 bg-light rounded text-muted" style="line-height: 1.6;">
                                {!! nl2br(e($dokumentasi->deskripsi)) !!}
                            </div>
                        </div>
                    @endif

                    <div class="mb-4">
                        <span class="text-muted small d-block mb-1">Tautan / Link Arsip</span>
                        <div class="p-3 border rounded bg-white d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <span class="text-truncate text-primary" style="max-width: 480px;">
                                <i class="bi bi-link-45deg me-1"></i> {{ $dokumentasi->link }}
                            </span>
                            <a href="{{ $dokumentasi->link }}" target="_blank" class="btn btn-primary btn-sm px-3 fw-semibold">
                                <i class="bi bi-box-arrow-up-right me-1"></i> Buka Tautan Eksternal
                            </a>
                        </div>
                    </div>

                    <div class="border-top pt-3 d-flex justify-content-between align-items-center">
                        <form action="{{ route('pemateri.dokumentasi.destroy', $dokumentasi) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus dokumentasi ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <i class="bi bi-trash me-1"></i> Hapus Dokumentasi
                            </button>
                        </form>

                        <div class="d-flex gap-2">
                            <a href="{{ route('pemateri.dokumentasi.edit', $dokumentasi) }}" class="btn btn-warning text-dark btn-sm fw-semibold">
                                <i class="bi bi-pencil me-1"></i> Edit Dokumentasi
                            </a>
                            <a href="{{ route('pemateri.dokumentasi.index') }}" class="btn btn-secondary btn-sm">
                                Kembali ke Daftar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
