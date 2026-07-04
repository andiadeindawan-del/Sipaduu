@extends('layouts.admin')

@section('title', 'Detail Materi')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-eye"></i></span>
        <div>
            <p class="eyebrow">Manajemen</p>
            <h1 class="h3 mb-0">Detail Materi</h1>
        </div>
    </div>
    <div class="heading-actions">
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('admin.materi.index') }}" class="btn btn-outline-secondary btn-sm">
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
                <div class="p-4">
                    <div class="row g-4">
                        <!-- Judul -->
                        <div class="col-12">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-text-paragraph fs-4 text-primary"></i>
                                </div>
                                <div>
                                    <label class="text-muted small fw-semibold">Judul</label>
                                    <p class="fw-semibold mb-0 fs-5">{{ $materi->judul }}</p>
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
                                    <p class="fw-semibold mb-0">{{ $materi->slug }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Kategori -->
                        <div class="col-12 col-md-6">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-tag fs-4 text-info"></i>
                                </div>
                                <div>
                                    <label class="text-muted small fw-semibold">Kategori</label>
                                    @if($materi->kategori)
                                    <p class="fw-semibold mb-0">
                                        <span class="badge" style="background-color: {{ $materi->kategori->warna ?? '#6c757d' }}; color: #fff;">
                                            <i class="bi {{ $materi->kategori->icon ?? 'bi-tag' }} me-1"></i>
                                            {{ $materi->kategori->nama }}
                                        </span>
                                    </p>
                                    @else
                                    <p class="text-muted">-</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Training -->
                        <div class="col-12 col-md-6">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-journal-bookmark fs-4 text-success"></i>
                                </div>
                                <div>
                                    <label class="text-muted small fw-semibold">Training</label>
                                    @if($materi->training)
                                    <p class="fw-semibold mb-0">
                                        <a href="{{ route('admin.trainings.show', $materi->training->id) }}" class="text-decoration-none">
                                            {{ $materi->training->judul }}
                                        </a>
                                    </p>
                                    @else
                                    <p class="text-muted">-</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Durasi -->
                        <div class="col-12 col-md-6">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-clock fs-4 text-warning"></i>
                                </div>
                                <div>
                                    <label class="text-muted small fw-semibold">Durasi</label>
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

                        <!-- Order -->
                        <div class="col-12 col-md-6">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-list-ol fs-4 text-primary"></i>
                                </div>
                                <div>
                                    <label class="text-muted small fw-semibold">Urutan</label>
                                    <p class="fw-semibold mb-0">{{ $materi->order ?? 0 }}</p>
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
                                        <span class="badge {{ $materi->status_badge ?? 'bg-secondary' }}">
                                            {{ $materi->status_label ?? ucfirst($materi->status ?? 'Draft') }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- ========================================================== -->
                        <!-- MULTIPLE FILES SECTION -->
                        <!-- ========================================================== -->
                        <div class="col-12">
                            <hr class="my-2">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-files fs-4 text-danger"></i>
                                </div>
                                <div class="w-100">
                                    <label class="text-muted small fw-semibold">File Materi</label>
                                    @if($materi->files && count($materi->files) > 0)
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
                                                                    <a href="{{ route('admin.materi.download', ['materi' => $materi->id, 'index' => $index]) }}" 
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
                                    @else
                                        <p class="text-muted">Tidak ada file</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- ========================================================== -->
                        <!-- DESKRIPSI & KONTEN -->
                        <!-- ========================================================== -->
                        @if($materi->deskripsi)
                        <div class="col-12">
                            <hr class="my-2">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-file-text fs-4 text-info"></i>
                                </div>
                                <div class="w-100">
                                    <label class="text-muted small fw-semibold">Deskripsi</label>
                                    <p class="mb-0">{{ $materi->deskripsi }}</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($materi->konten)
                        <div class="col-12">
                            <hr class="my-2">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-file-richtext fs-4 text-success"></i>
                                </div>
                                <div class="w-100">
                                    <label class="text-muted small fw-semibold">Konten</label>
                                    <div class="p-3 border rounded bg-light konten-wrapper">
                                        {!! $materi->konten !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- ========================================================== -->
                        <!-- META INFO -->
                        <!-- ========================================================== -->
                        <div class="col-12">
                            <hr class="my-2">
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label class="text-muted small fw-semibold">
                                        <i class="bi bi-clock me-1"></i> Dibuat
                                    </label>
                                    <p class="fw-semibold mb-0">
                                        {{ $materi->created_at ? $materi->created_at->format('d/m/Y H:i') : '-' }}
                                        @if($materi->created_at)
                                        <span class="text-muted small">
                                            ({{ $materi->created_at->diffForHumans() }})
                                        </span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="text-muted small fw-semibold">
                                        <i class="bi bi-clock-history me-1"></i> Diperbarui
                                    </label>
                                    <p class="fw-semibold mb-0">
                                        {{ $materi->updated_at ? $materi->updated_at->format('d/m/Y H:i') : '-' }}
                                        @if($materi->updated_at && $materi->updated_at != $materi->created_at)
                                        <span class="text-muted small">
                                            ({{ $materi->updated_at->diffForHumans() }})
                                        </span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

    

            <!-- ========================================================== -->
            <!-- RELATED DATA CARD -->
            <!-- ========================================================== -->
            <div class="panel mt-3">
                <div class="panel-header">
                    <h5 class="section-title"><i class="bi bi-link-45deg"></i> Data Terkait</h5>
                </div>
                <div class="p-4">
                    <div class="row g-3">
                        <!-- Kategori -->
                        <div class="col-12 col-md-6">
                            <div class="card bg-light h-100">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="bi bi-tag text-primary me-2"></i>
                                        Kategori
                                    </h6>
                                    @if($materi->kategori)
                                    <p class="card-text">
                                        <span class="badge" style="background-color: {{ $materi->kategori->warna ?? '#6c757d' }}; color: #fff;">
                                            <i class="bi {{ $materi->kategori->icon ?? 'bi-tag' }} me-1"></i>
                                            {{ $materi->kategori->nama }}
                                        </span>
                                    </p>
                                    @if($materi->kategori->deskripsi)
                                    <small class="text-muted">{{ $materi->kategori->deskripsi }}</small>
                                    @endif
                                    @else
                                    <p class="text-muted">Tidak ada kategori</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Training -->
                        <div class="col-12 col-md-6">
                            <div class="card bg-light h-100">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="bi bi-journal-bookmark text-success me-2"></i>
                                        Training
                                    </h6>
                                    @if($materi->training)
                                    <p class="card-text">
                                        <a href="{{ route('admin.trainings.show', $materi->training->id) }}" class="text-decoration-none">
                                            <i class="bi bi-journal-bookmark me-1"></i>
                                            {{ $materi->training->judul }}
                                        </a>
                                    </p>
                                    @if($materi->training->deskripsi)
                                    <small class="text-muted">{{ Str::limit($materi->training->deskripsi, 100) }}</small>
                                    @endif
                                    @else
                                    <p class="text-muted">Tidak ada training</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========================================================== -->
            <!-- STATISTICS -->
            <!-- ========================================================== -->
            <div class="panel mt-3">
                <div class="panel-header">
                    <h5 class="section-title"><i class="bi bi-bar-chart"></i> Statistik</h5>
                </div>
                <div class="p-4">
                    <div class="row g-3">
                        <div class="col-6 col-md-4">
                            <div class="text-center p-3 border rounded">
                                <h6 class="text-muted small">Total File</h6>
                                <h3 class="mb-0">{{ $materi->total_files }}</h3>
                            </div>
                        </div>
                        <div class="col-6 col-md-4">
                            <div class="text-center p-3 border rounded">
                                <h6 class="text-muted small">Durasi</h6>
                                <h3 class="mb-0">{{ $materi->durasi ?? 0 }} <small class="text-muted fs-6">menit</small></h3>
                            </div>
                        </div>
                        <div class="col-6 col-md-4">
                            <div class="text-center p-3 border rounded">
                                <h6 class="text-muted small">Status</h6>
                                <h5 class="mb-0">
                                    <span class="badge {{ $materi->status_badge ?? 'bg-secondary' }}">
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

<!-- ========================================================== -->
<!-- DELETE MODAL -->
<!-- ========================================================== -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-triangle text-danger me-2"></i>
                    Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus materi <strong>{{ $materi->judul }}</strong>?</p>
                @if($materi->hasFile())
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Semua file materi akan ikut terhapus ({{ $materi->total_files }} file).
                </div>
                @endif
                <p class="text-muted small">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin.materi.destroy', $materi->id) }}" method="POST" class="d-inline">
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

    // ============================================================
    // COPY SLUG TO CLIPBOARD
    // ============================================================
    const slugElement = document.querySelector('.fw-semibold:contains("{{ $materi->slug }}")');
    if (slugElement) {
        slugElement.style.cursor = 'pointer';
        slugElement.title = 'Klik untuk copy slug';
        slugElement.addEventListener('click', function() {
            const slug = '{{ $materi->slug }}';
            navigator.clipboard.writeText(slug).then(function() {
                const originalText = this.textContent;
                this.textContent = '✅ Copied!';
                setTimeout(() => {
                    this.textContent = originalText;
                }, 2000);
            }.bind(this)).catch(function() {
                // Fallback
                const input = document.createElement('input');
                input.value = slug;
                document.body.appendChild(input);
                input.select();
                document.execCommand('copy');
                document.body.removeChild(input);
                alert('Slug copied: ' + slug);
            });
        });
    }
});
</script>
@endpush
@endsection