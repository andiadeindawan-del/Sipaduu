@extends('layouts.admin')

@section('title', 'Manajemen Kategori')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-tags"></i></span>
        <div>
            <p class="eyebrow mb-1">Management</p>
            <h1 class="h3 mb-0">Kategori</h1>
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
                    <span class="metric-label">Total Kategori</span>
                    <span class="metric-icon"><i class="bi bi-tags"></i></span>
                </div>
                <div class="metric-value">{{ $totalKategoris ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-success">+12%</span>
                    <span>bulan ini</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-success">
                <div class="metric-top">
                    <span class="metric-label">Dengan Materi</span>
                    <span class="metric-icon"><i class="bi bi-book"></i></span>
                </div>
                <div class="metric-value">{{ $kategoris->filter(fn($k) => $k->materis_count > 0)->count() ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-success">Aktif</span>
                    <span>kategori</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-info">
                <div class="metric-top">
                    <span class="metric-label">Dengan Pelatihan</span>
                    <span class="metric-icon"><i class="bi bi-journal-bookmark"></i></span>
                </div>
                <div class="metric-value">{{ $kategoris->filter(fn($k) => $k->trainings_count > 0)->count() ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-info">Digunakan</span>
                    <span>kategori</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-warning">
                <div class="metric-top">
                    <span class="metric-label">Kosong</span>
                    <span class="metric-icon"><i class="bi bi-box"></i></span>
                </div>
                <div class="metric-value">{{ $kategoris->filter(fn($k) => $k->materis_count == 0 && $k->trainings_count == 0)->count() ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-warning">Belum digunakan</span>
                    <span>kategori</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
   <div class="panel">
    <div class="panel-header">
        <div>
            <h5 class="section-title"><i class="bi bi-table"></i> Daftar Kategori</h5>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <form action="{{ route('admin.kategori.index') }}" method="GET" class="d-flex gap-2">
                <input class="form-control form-control-sm" type="search" name="search" 
                       placeholder="Cari kategori..." value="{{ request('search') }}" style="width: 200px;">
                <button type="submit" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-search"></i>
                </button>
            </form>
            
            @if(request('search'))
            <a href="{{ route('admin.kategori.index') }}" class="btn btn-outline-secondary btn-sm" title="Reset Filter">
                <i class="bi bi-arrow-counterclockwise"></i> Reset
            </a>
            @endif
            
            {{-- Tombol Tambah --}}
            <a href="{{ route('admin.kategori.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle"></i> Tambah
            </a>
        </div>
    </div>
    <div class="table-responsive">
        @if($kategoris->count() > 0)
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th>Nama Kategori</th>
                    <th>Slug</th>
                    <th>Deskripsi</th>
                    <th>Materi</th>
                    <th>Pelatihan</th>
                    <th class="text-end" style="width: 160px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($kategoris as $index => $kategori)
                <tr>
                    <td>{{ $kategoris->firstItem() + $index }}</td>
                    <td>
                        <div>
                            <p class="fw-semibold mb-0">
                                {{ $kategori->nama }}
                            </p>
                        </div>
                    </td>
                    <td><span class="text-muted small">{{ $kategori->slug }}</span></td>
                    <td>{{ Str::limit($kategori->deskripsi, 50) ?? '-' }}</td>
                    <td>
                        <span class="">
                            <i class="bi bi-book me-1"></i>
                            {{ $kategori->materis_count ?? 0 }}
                        </span>
                    </td>
                    <td>
                        <span class="">
                            <i class="bi bi-journal-bookmark me-1"></i>
                            {{ $kategori->trainings_count ?? 0 }}
                        </span>
                    </td>
                    <td class="text-end">
                        <div class="d-flex gap-1 justify-content-end">
                            {{-- Link Show --}}
                            <a href="{{ route('admin.kategori.show', $kategori->id) }}" class="badge bg-info text-white border-0 p-2 text-decoration-none" title="Lihat">
                                <i class="bi bi-eye"></i> Lihat
                            </a>
                            
                            {{-- Link Edit --}}
                            <a href="{{ route('admin.kategori.edit', $kategori->id) }}" class="badge bg-warning text-dark border-0 p-2 text-decoration-none" title="Edit">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            
                            {{-- Tombol Delete dengan form --}}
                            <form action="{{ route('admin.kategori.destroy', $kategori->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori {{ $kategori->nama }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="badge bg-danger text-white border-0 p-2" title="Hapus">
                                    <i class="bi bi-trash"></i> Hapus
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
                <p class="h5">Belum ada kategori</p>
                <p class="small">
                    @if(request('search'))
                        Tidak ada kategori yang sesuai dengan pencarian "{{ request('search') }}".
                    @else
                        Mulai dengan menambahkan kategori baru
                    @endif
                </p>
                @if(request('search'))
                <a href="{{ route('admin.kategori.index') }}" class="btn btn-outline-secondary btn-sm mt-2">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset Filter
                </a>
                @endif
                <a href="{{ route('admin.kategori.create') }}" class="btn btn-primary btn-sm mt-2">
                    <i class="bi bi-plus-circle"></i> Tambah Kategori
                </a>
            </div>
        </div>
        @endif
    </div>
    @if($kategoris->hasPages())
    <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mt-3 px-3 pb-3">
        <p class="text-muted small mb-0">
            Menampilkan {{ $kategoris->firstItem() ?? 0 }} sampai {{ $kategoris->lastItem() ?? 0 }} 
            dari {{ $kategoris->total() ?? 0 }} kategori
        </p>
        <nav aria-label="Kategori pagination">
            {{ $kategoris->links() }}
        </nav>
    </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ============================================================
    // Auto close alerts
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