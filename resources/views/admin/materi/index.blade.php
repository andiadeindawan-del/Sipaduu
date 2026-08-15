@extends('layouts.admin')

@section('title', 'Manajemen Materi')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-book"></i></span>
        <div>
             <p class="eyebrow mb-1">Management</p>
            <h1 class="h3 mb-0">Materi</h1>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <!-- Stats -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-primary">
                <div class="metric-top">
                    <span class="metric-label">Total Materi</span>
                    <span class="metric-icon"><i class="bi bi-book"></i></span>
                </div>
                <div class="metric-value">{{ $totalMateri ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-success">+12%</span>
                    <span>bulan ini</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-success">
                <div class="metric-top">
                    <span class="metric-label">Published</span>
                    <span class="metric-icon"><i class="bi bi-check-circle"></i></span>
                </div>
                <div class="metric-value">{{ $publishedMateri ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-success">Aktif</span>
                    <span>materi</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-warning">
                <div class="metric-top">
                    <span class="metric-label">Draft</span>
                    <span class="metric-icon"><i class="bi bi-pencil"></i></span>
                </div>
                <div class="metric-value">{{ $draftMateri ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-warning">Perlu review</span>
                    <span>draft</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-secondary">
                <div class="metric-top">
                    <span class="metric-label">Archived</span>
                    <span class="metric-icon"><i class="bi bi-archive"></i></span>
                </div>
                <div class="metric-value">{{ $archivedMateri ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-secondary">Diarsipkan</span>
                    <span>materi</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="panel">
        <div class="panel-header">
            <div>
                <h5 class="section-title"><i class="bi bi-table"></i> Daftar Materi</h5>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <form action="{{ route('admin.materi.index') }}" method="GET" class="d-flex gap-2">
                    <input class="form-control form-control-sm" type="search" name="search" 
                           placeholder="Cari materi..." value="{{ request('search') }}" style="width: 200px;">
                    <button type="submit" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
                
                @if(request('search'))
                <a href="{{ route('admin.materi.index') }}" class="btn btn-outline-secondary btn-sm" title="Reset Filter">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                </a>
                @endif
                
                {{-- Tombol Tambah --}}
                <a href="{{ route('admin.materi.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle"></i> Tambah
                </a>
            </div>
        </div>
        <div class="table-responsive">
            @if($materis->count() > 0)
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Training</th>
                        <th>Durasi</th>
                        <th>File</th>
                        <th>Status</th>
                        <th class="text-end" style="width: 160px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($materis as $index => $materi)
                    <tr>
                        <td>{{ $materis->firstItem() + $index }}</td>
                        <td>
                            <div>
                                <p class="fw-semibold mb-0">{{ $materi->judul }}</p>
                            </div>
                        </td>
                        <td>
                            @if($materi->kategori)
                            <span class="text-muted">{{ $materi->kategori->nama }}</span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($materi->training)
                            <span class="text-muted">{{ $materi->training->judul }}</span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($materi->durasi)
                            <span class="text-muted">
                                {{ $materi->durasi }} menit
                            </span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($materi->hasFile())
                            <span class="text-success">
                                <i class="bi bi-check-circle me-1"></i>
                                Ada
                            </span>
                            @else
                            <span class="text-muted">
                                <i class="bi bi-x-circle me-1"></i>
                                Tidak
                            </span>
                            @endif
                        </td>
                        <td>
                            @php
                                $statusMap = [
                                    'draft' => ['label' => 'Draft', 'class' => 'badge-draft'],
                                    'published' => ['label' => 'Published', 'class' => 'badge-published'],
                                    'archived' => ['label' => 'Archived', 'class' => 'badge-secondary'],
                                ];
                                $status = $statusMap[$materi->status] ?? ['label' => $materi->status, 'class' => 'badge-draft'];
                            @endphp
                            <span class="badge {{ $status['class'] }}">
                                <i class="bi bi-circle-fill me-1" style="font-size: 6px;"></i>
                                {{ $status['label'] }}
                            </span>
                        </td>
                        <td class="text-end">
                            <div class="d-flex gap-1 justify-content-end">
                                {{-- Link Show --}}
                                <a href="{{ route('admin.materi.show', $materi->id) }}" class="badge bg-info text-white border-0 p-2 text-decoration-none" title="Lihat">
                                    <i class="bi bi-eye"></i> 
                                </a>
                                
                                {{-- Link Edit --}}
                                <a href="{{ route('admin.materi.edit', $materi->id) }}" class="badge bg-warning text-dark border-0 p-2 text-decoration-none" title="Edit">
                                    <i class="bi bi-pencil"></i> 
                                </a>
                                
                                @if($materi->hasFile())
                                <a href="{{ route('admin.materi.download', $materi->id) }}" 
                                   class="badge bg-success text-white text-decoration-none p-2" title="Download">
                                    <i class="bi bi-download"></i> 
                                </a>
                                @endif
                                
                                {{-- Tombol Delete dengan form --}}
                                <form action="{{ route('admin.materi.destroy', $materi->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus materi {{ $materi->judul }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="badge bg-danger text-white border-0 p-2" title="Hapus">
                                        <i class="bi bi-trash"></i> 
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="text-center py-5">
                <div class="text-muted">
                    <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                    <p class="h5">
                        @if(request('search'))
                            Tidak ada materi yang sesuai dengan pencarian "{{ request('search') }}"
                        @else
                            Belum ada materi
                        @endif
                    </p>
                    <p class="small">
                        @if(request('search'))
                            Coba ubah kata kunci pencarian atau reset filter
                        @else
                            Mulai dengan menambahkan materi baru
                        @endif
                    </p>
                    @if(request('search'))
                    <a href="{{ route('admin.materi.index') }}" class="btn btn-outline-secondary btn-sm mt-2">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset Filter
                    </a>
                    @endif
                    <a href="{{ route('admin.materi.create') }}" class="btn btn-primary btn-sm mt-2">
                        <i class="bi bi-plus-circle"></i> Tambah Materi
                    </a>
                </div>
            </div>
            @endif
        </div>
        @if($materis->hasPages())
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mt-3 px-3 pb-3">
            <p class="text-muted small mb-0">
                Menampilkan {{ $materis->firstItem() ?? 0 }} sampai {{ $materis->lastItem() ?? 0 }} 
                dari {{ $materis->total() ?? 0 }} materi
            </p>
            <nav aria-label="Materi pagination">
                {{ $materis->links() }}
            </nav>
        </div>
        @endif
    </div>
</div>

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
});
</script>
@endpush
@endsection