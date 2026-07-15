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
            
            {{-- Tombol Tambah dengan modal --}}
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">
                <i class="bi bi-plus-circle"></i> Tambah
            </button>
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
                                </span>
                            </p>
                        </div>
                    </td>
                    <td><span class="text-muted small">{{ $kategori->slug }}</span></td>
                    <td>{{ Str::limit($kategori->deskripsi, 50) ?? '-' }}</td>
                    <td>
                        <span class="badge text-bg-info">
                            <i class="bi bi-book me-1"></i>
                            {{ $kategori->materis_count ?? 0 }}
                        </span>
                    </td>
                    <td>
                        <span class="badge text-bg-primary">
                            <i class="bi bi-journal-bookmark me-1"></i>
                            {{ $kategori->trainings_count ?? 0 }}
                        </span>
                    </td>
                    <td class="text-end">
                        <div class="d-flex gap-1 justify-content-end">
                            {{-- Tombol Show dengan modal --}}
                            <button type="button" class="badge bg-info text-white border-0 p-2" 
                                    data-bs-toggle="modal" data-bs-target="#showModal{{ $kategori->id }}" 
                                    title="Lihat">
                                <i class="bi bi-eye"></i> Lihat
                            </button>
                            
                            {{-- Tombol Edit dengan modal --}}
                            <button type="button" class="badge bg-warning text-dark border-0 p-2" 
                                    data-bs-toggle="modal" data-bs-target="#editModal{{ $kategori->id }}" 
                                    title="Edit">
                                <i class="bi bi-pencil"></i> Edit
                            </button>
                            
                            {{-- Tombol Delete dengan modal --}}
                            <button type="button" class="badge bg-danger text-white border-0 p-2" 
                                    data-bs-toggle="modal" data-bs-target="#deleteModal{{ $kategori->id }}" 
                                    title="Hapus">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
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
                <button type="button" class="btn btn-primary btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="bi bi-plus-circle"></i> Tambah Kategori
                </button>
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

<!-- ============================================================ -->
<!-- MODAL CREATE -->
<!-- ============================================================ -->
<div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.kategori.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-plus-circle text-primary me-2"></i>Tambah Kategori
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama" class="form-label fw-semibold">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama" name="nama" 
                               placeholder="Masukkan nama kategori" required>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label fw-semibold">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" 
                                  placeholder="Masukkan deskripsi kategori"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="icon" class="form-label fw-semibold">Icon (Bootstrap Icons)</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-tag"></i></span>
                            <input type="text" class="form-control" id="icon" name="icon" 
                                   placeholder="bi-tag" value="bi-tag">
                        </div>
                        <small class="text-muted">Masukkan class icon Bootstrap Icons (contoh: bi-tag, bi-folder, bi-book)</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- MODAL SHOW -->
<!-- ============================================================ -->
@foreach($kategoris ?? [] as $kategori)
<div class="modal fade" id="showModal{{ $kategori->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-info-circle text-info me-2"></i>Detail Kategori
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <span class="badge text-bg-secondary" style="font-size: 1.2rem; padding: 0.75rem 1.5rem;">
                        <i class="bi {{ $kategori->icon ?? 'bi-tag' }} me-2"></i>
                        {{ $kategori->nama }}
                    </span>
                </div>
                
                <div class="row g-3">
                    <div class="col-6">
                        <label class="text-muted small fw-semibold">Slug</label>
                        <p class="fw-semibold mb-0">{{ $kategori->slug }}</p>
                    </div>
                    <div class="col-6">
                        <label class="text-muted small fw-semibold">Icon</label>
                        <p class="fw-semibold mb-0">
                            <i class="bi {{ $kategori->icon ?? 'bi-tag' }} me-1"></i>
                            {{ $kategori->icon ?? 'bi-tag' }}
                        </p>
                    </div>
                    <div class="col-6">
                        <label class="text-muted small fw-semibold">Total Materi</label>
                        <p class="fw-semibold mb-0">{{ $kategori->materis_count ?? 0 }}</p>
                    </div>
                    <div class="col-6">
                        <label class="text-muted small fw-semibold">Total Pelatihan</label>
                        <p class="fw-semibold mb-0">{{ $kategori->trainings_count ?? 0 }}</p>
                    </div>
                    <div class="col-12">
                        <label class="text-muted small fw-semibold">Deskripsi</label>
                        <p class="mb-0">{{ $kategori->deskripsi ?? 'Tidak ada deskripsi' }}</p>
                    </div>
                    <div class="col-6">
                        <label class="text-muted small fw-semibold">Dibuat</label>
                        <p class="fw-semibold mb-0">{{ $kategori->created_at ? $kategori->created_at->format('d/m/Y H:i') : '-' }}</p>
                    </div>
                    <div class="col-6">
                        <label class="text-muted small fw-semibold">Diperbarui</label>
                        <p class="fw-semibold mb-0">{{ $kategori->updated_at ? $kategori->updated_at->format('d/m/Y H:i') : '-' }}</p>
                    </div>
                    @if(($kategori->materis_count ?? 0) > 0 || ($kategori->trainings_count ?? 0) > 0)
                    <div class="col-12">
                        <hr>
                        <div class="d-flex gap-3">
                            @if(($kategori->materis_count ?? 0) > 0)
                            <span class="badge text-bg-info">
                                <i class="bi bi-book me-1"></i>
                                {{ $kategori->materis_count }} Materi
                            </span>
                            @endif
                            @if(($kategori->trainings_count ?? 0) > 0)
                            <span class="badge text-bg-primary">
                                <i class="bi bi-journal-bookmark me-1"></i>
                                {{ $kategori->trainings_count }} Pelatihan
                            </span>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $kategori->id }}" data-bs-dismiss="modal">
                    <i class="bi bi-pencil me-1"></i> Edit
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- MODAL EDIT -->
<!-- ============================================================ -->
<div class="modal fade" id="editModal{{ $kategori->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.kategori.update', $kategori->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-pencil-square text-warning me-2"></i>Edit Kategori
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_edit_{{ $kategori->id }}" class="form-label fw-semibold">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama_edit_{{ $kategori->id }}" 
                               name="nama" value="{{ $kategori->nama }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi_edit_{{ $kategori->id }}" class="form-label fw-semibold">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi_edit_{{ $kategori->id }}" 
                                  name="deskripsi" rows="3">{{ $kategori->deskripsi }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="icon_edit_{{ $kategori->id }}" class="form-label fw-semibold">Icon (Bootstrap Icons)</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi {{ $kategori->icon ?? 'bi-tag' }}"></i></span>
                            <input type="text" class="form-control" id="icon_edit_{{ $kategori->id }}" 
                                   name="icon" value="{{ $kategori->icon ?? 'bi-tag' }}">
                        </div>
                        <small class="text-muted">Masukkan class icon Bootstrap Icons (contoh: bi-tag, bi-folder, bi-book)</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-save me-1"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- MODAL DELETE -->
<!-- ============================================================ -->
<div class="modal fade" id="deleteModal{{ $kategori->id }}" tabindex="-1" 
     aria-labelledby="deleteModalLabel{{ $kategori->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel{{ $kategori->id }}">
                    <i class="bi bi-exclamation-triangle text-danger me-2"></i>
                    Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus kategori <strong>{{ $kategori->nama }}</strong>?</p>
                @if(($kategori->materis_count ?? 0) > 0 || ($kategori->trainings_count ?? 0) > 0)
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Kategori ini memiliki <strong>{{ $kategori->materis_count ?? 0 }}</strong> materi dan 
                    <strong>{{ $kategori->trainings_count ?? 0 }}</strong> pelatihan. 
                    Menghapus kategori akan menghapus semua data terkait.
                </div>
                @endif
                <p class="text-muted small">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin.kategori.destroy', $kategori->id) }}" method="POST" class="d-inline">
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
@endforeach

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