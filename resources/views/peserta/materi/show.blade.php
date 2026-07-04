@extends('layouts.peserta')

@section('title', 'Detail Materi')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-book"></i></span>
        <div>
            <p class="eyebrow">Materi</p>
            <h1 class="h3 mb-0">Detail Materi</h1>
            <p class="text-muted mb-0">{{ $materi->judul }}</p>
        </div>
    </div>
    <div class="heading-actions">
        <a href="{{ route('peserta.materi.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
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
                <div class="p-4">
                    <div class="row g-4">
                        <!-- Title & Status -->
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                <div>
                                    <h3 class="fw-bold mb-2">{{ $materi->judul }}</h3>
                                    <div class="d-flex flex-wrap gap-2">
                                        <span class="badge 
                                            @if($materi->status == 'published') 
                                                badge-published
                                            @elseif($materi->status == 'draft') 
                                                badge-draft
                                            @else 
                                                badge-archived
                                            @endif
                                        ">
                                            {{ $materi->status_label ?? ucfirst($materi->status ?? 'Draft') }}
                                        </span>
                                        @if($materi->kategori)
                                        <span class="badge bg-primary">{{ $materi->kategori->nama }}</span>
                                        @endif
                                        <span class="badge bg-secondary">
                                            <i class="bi bi-files me-1"></i> {{ $materi->total_files ?? 0 }} file
                                        </span>
                                        @if($materi->durasi)
                                        <span class="badge bg-info">
                                            <i class="bi bi-clock me-1"></i> {{ $materi->durasi }} menit
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="d-flex gap-2 flex-wrap">
                                    @php
                                        $progress = $materi->getMyProgress();
                                    @endphp
                                    @if($progress < 100)
                                        <form action="{{ route('peserta.materi.complete', $materi->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-success" 
                                                    onclick="return confirm('Yakin ingin menandai materi ini selesai?')">
                                                <i class="bi bi-check-circle me-2"></i> Tandai Selesai
                                            </button>
                                        </form>
                                    @endif
                                    @if($materi->total_files > 0)
                                        <a href="{{ route('peserta.materi.download', $materi->id) }}" 
                                           class="btn btn-primary">
                                            <i class="bi bi-download me-2"></i> Download Semua
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Progress -->
                        <div class="col-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="fw-bold mb-0">Progress Belajar</h6>
                                        <span class="fw-bold">{{ $progress }}%</span>
                                    </div>
                                    <div class="progress" style="height: 10px;">
                                        <div class="progress-bar bg-success" style="width: {{ $progress }}%;"></div>
                                    </div>
                                    @if($progress == 100)
                                        <div class="text-success mt-2">
                                            <i class="bi bi-check-circle me-1"></i> Materi selesai dipelajari!
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        @if($materi->deskripsi)
                        <div class="col-12">
                            <h6 class="fw-bold"><i class="bi bi-file-text me-2"></i>Deskripsi</h6>
                            <p class="text-muted">{{ $materi->deskripsi }}</p>
                        </div>
                        @endif

                        <!-- Konten -->
                        @if($materi->konten)
                        <div class="col-12">
                            <h6 class="fw-bold"><i class="bi bi-file-richtext me-2"></i>Konten</h6>
                            <div class="p-3 border rounded bg-light konten-wrapper">
                                {!! $materi->konten !!}
                            </div>
                        </div>
                        @endif

                        <!-- Files -->
                        @if($materi->files && count($materi->files) > 0)
                        <div class="col-12">
                            <hr class="my-2">
                            <h6 class="fw-bold"><i class="bi bi-files me-2"></i>File Materi ({{ count($materi->files) }})</h6>
                            <div class="table-responsive mt-2">
                                <table class="table table-sm table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="40">#</th>
                                            <th>Nama File</th>
                                            <th>Tipe</th>
                                            <th>Ukuran</th>
                                            <th>Aksi</th>
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
                                            <td>
                                                <div class="d-flex gap-1">
                                                    @if(!empty($file['path']))
                                                        <a href="{{ route('peserta.materi.download', ['materi' => $materi->id, 'index' => $index]) }}" 
                                                           class="btn btn-sm btn-success" target="_blank">
                                                            <i class="bi bi-download"></i>
                                                        </a>
                                                    @elseif(!empty($file['url']))
                                                        <a href="{{ $file['url'] }}" 
                                                           class="btn btn-sm btn-info" target="_blank">
                                                            <i class="bi bi-box-arrow-up-right"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
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
                                        {{ $materi->created_at ? $materi->created_at->format('d/m/Y H:i') : '-' }}
                                    </p>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="text-muted small fw-semibold">
                                        <i class="bi bi-clock-history me-1"></i> Diperbarui
                                    </label>
                                    <p class="fw-semibold mb-0">
                                        {{ $materi->updated_at ? $materi->updated_at->format('d/m/Y H:i') : '-' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="col-12 mt-2">
                            <hr class="my-2">
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="{{ route('peserta.materi.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left me-1"></i> Kembali
                                </a>
                                @if($materi->total_files > 0)
                                    <a href="{{ route('peserta.materi.download', $materi->id) }}" class="btn btn-primary">
                                        <i class="bi bi-download me-1"></i> Download Semua
                                    </a>
                                @endif
                                @if($progress < 100)
                                    <form action="{{ route('peserta.materi.complete', $materi->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success" 
                                                onclick="return confirm('Yakin ingin menandai materi ini selesai?')">
                                            <i class="bi bi-check-circle me-1"></i> Tandai Selesai
                                        </button>
                                    </form>
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
    .badge-published { background: #d1e7dd; color: #0a7344; }
    .badge-draft { background: #e9ecef; color: #495057; }
    .badge-archived { background: #f8d7da; color: #842029; }
    
    .konten-wrapper {
        max-height: 400px;
        overflow-y: auto;
    }
    .konten-wrapper img {
        max-width: 100%;
        height: auto;
    }
    .konten-wrapper iframe,
    .konten-wrapper video {
        max-width: 100%;
    }
    
    .progress {
        background-color: #e9ecef;
        border-radius: 10px;
    }
    .progress-bar {
        transition: width 0.6s ease;
        border-radius: 10px;
    }
    
    .table td {
        vertical-align: middle;
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

    // Confirm complete
    document.querySelectorAll('form[action*="complete"]').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            if (!confirm('Yakin ingin menandai materi ini selesai?')) {
                e.preventDefault();
            }
        });
    });
});
</script>
@endpush
@endsection