@extends('layouts.pemateri')

@section('title', 'Detail Materi: ' . $materi->judul)

@section('header')
<div class="page-heading d-flex justify-content-between align-items-center">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-file-earmark-text"></i></span>
        <div>
            <p class="eyebrow">Aktivitas Pemateri</p>
            <h1 class="h3 mb-0">{{ $materi->judul }}</h1>
            <p class="text-muted mb-0">Informasi dan preview materi pembelajaran.</p>
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('pemateri.materi.edit', $materi) }}" class="btn btn-warning text-dark btn-sm fw-semibold">
            <i class="bi bi-pencil me-1"></i> Edit Materi
        </a>
        <a href="{{ route('pemateri.materi.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">

    <div class="row g-4">
        <!-- DETAIL MATERI -->
        <div class="col-12 col-lg-8">
            <div class="panel border-0 shadow-sm mb-4">
                <div class="panel-header">
                    <h5 class="section-title"><i class="bi bi-info-circle text-primary"></i> Rincian Materi</h5>
                    <span class="badge {{ $materi->status === 'published' ? 'bg-success' : 'bg-secondary' }}">
                        {{ ucfirst($materi->status) }}
                    </span>
                </div>
                <div class="p-4">
                    <h4 class="fw-bold text-dark mb-3">{{ $materi->judul }}</h4>

                    @if($materi->deskripsi)
                        <div class="mb-4">
                            <h6 class="fw-semibold text-muted small text-uppercase">Deskripsi</h6>
                            <p class="text-dark">{{ $materi->deskripsi }}</p>
                        </div>
                    @endif

                    @if($materi->konten)
                        <div class="mb-4">
                            <h6 class="fw-semibold text-muted small text-uppercase">Konten Pembelajaran</h6>
                            <div class="p-3 bg-light rounded border">
                                {!! nl2br(e($materi->konten)) !!}
                            </div>
                        </div>
                    @endif

                    <!-- FILES ATTACHED -->
                    <div class="mb-3">
                        <h6 class="fw-semibold text-muted small text-uppercase mb-3">Lampiran File & Dokumen ({{ count($materi->files ?? []) }})</h6>
                        @if(empty($materi->files))
                            <p class="text-muted small">Tidak ada file yang dilampirkan.</p>
                        @else
                            <div class="list-group">
                                @foreach($materi->files as $idx => $file)
                                    <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="bi bi-file-earmark-arrow-down fs-4 text-primary"></i>
                                            <div>
                                                <div class="fw-semibold">{{ $file['name'] ?? 'File ' . ($idx + 1) }}</div>
                                                <small class="text-muted text-uppercase">{{ $file['type'] ?? 'file' }}</small>
                                            </div>
                                        </div>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('pemateri.materi.preview', [$materi, $idx]) }}" target="_blank" class="btn btn-outline-info">
                                                <i class="bi bi-eye"></i> Preview
                                            </a>
                                            <a href="{{ route('pemateri.materi.download', [$materi, $idx]) }}" class="btn btn-outline-primary">
                                                <i class="bi bi-download"></i> Download
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- SIDEBAR INFO -->
        <div class="col-12 col-lg-4">
            <div class="panel border-0 shadow-sm mb-4">
                <div class="panel-header">
                    <h5 class="section-title"><i class="bi bi-bookmark text-primary"></i> Metadata Pelatihan</h5>
                </div>
                <div class="p-4">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-3">
                            <div class="text-muted small">Pelatihan:</div>
                            <div class="fw-bold">
                                @if($materi->training)
                                    <a href="{{ route('pemateri.trainings.show', $materi->training) }}" class="text-decoration-none text-primary">
                                        {{ $materi->training->judul }}
                                    </a>
                                @else
                                    -
                                @endif
                            </div>
                        </li>
                        <li class="mb-3">
                            <div class="text-muted small">Kategori:</div>
                            <div class="fw-semibold">{{ $materi->kategori->nama ?? '-' }}</div>
                        </li>
                        <li class="mb-3">
                            <div class="text-muted small">Durasi Belajar:</div>
                            <div class="fw-semibold">{{ $materi->durasi ? $materi->durasi . ' Menit' : '-' }}</div>
                        </li>
                        <li class="mb-3">
                            <div class="text-muted small">Urutan (Order):</div>
                            <div class="fw-semibold">Ke-{{ $materi->order ?? 1 }}</div>
                        </li>
                        <li class="mb-3">
                            <div class="text-muted small">Tanggal Dibuat:</div>
                            <div class="fw-semibold">{{ $materi->created_at ? $materi->created_at->format('d/m/Y H:i') : '-' }}</div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
