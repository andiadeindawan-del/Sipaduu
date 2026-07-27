@extends('layouts.admin')

@section('title', 'Manajemen Pelatihan')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-journal-bookmark"></i></span>
        <div>
            <p class="eyebrow">Manajemen</p>
            <h1 class="h3 mb-0">Pelatihan</h1>
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
                    <span class="metric-label">Total Pelatihan</span>
                    <span class="metric-icon"><i class="bi bi-journal-bookmark"></i></span>
                </div>
                <div class="metric-value">{{ $totalTrainings ?? 0 }}</div>
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
                <div class="metric-value">{{ $publishedTrainings ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-success">Aktif</span>
                    <span>pelatihan</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-warning">
                <div class="metric-top">
                    <span class="metric-label">Draft</span>
                    <span class="metric-icon"><i class="bi bi-pencil"></i></span>
                </div>
                <div class="metric-value">{{ $draftTrainings ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-warning">Perlu review</span>
                    <span>draft</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-info">
                <div class="metric-top">
                    <span class="metric-label">Berjalan</span>
                    <span class="metric-icon"><i class="bi bi-play-circle"></i></span>
                </div>
                <div class="metric-value">{{ $ongoingTrainings ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-info">Sedang berlangsung</span>
                    <span>pelatihan</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="panel">
        <div class="panel-header">
            <div>
                <h5 class="section-title"><i class="bi bi-table"></i> Daftar Pelatihan</h5>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <form action="{{ route('admin.trainings.index') }}" method="GET" class="d-flex gap-2">
                    <input class="form-control form-control-sm" type="search" name="search" 
                           placeholder="Cari pelatihan..." value="{{ request('search') }}" style="width: 200px;">
                    <button type="submit" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
                
                @if(request('search'))
                <a href="{{ route('admin.trainings.index') }}" class="btn btn-outline-secondary btn-sm" title="Reset Filter">
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
            @if($trainings->count() > 0)
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Tanggal</th>
                        <th>Peserta</th>
                        <th>Status</th>
                        <th class="text-end" style="width: 160px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($trainings as $index => $training)
                    <tr>
                        <td>{{ $trainings->firstItem() + $index }}</td>
                        <td>
                            <div>
                                <p class="fw-semibold mb-0">{{ $training->judul }}</p>
                            </div>
                        </td>
                        <td>
                            @if($training->kategori)
                            <span class="text-muted">{{ $training->kategori->nama }}</span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <div class="small">
                                <div><i class=""></i> {{ $training->tanggal_mulai ? $training->tanggal_mulai->format('d/m/Y') : '-' }}</div>
                                <div><i class=""></i> {{ $training->tanggal_selesai ? $training->tanggal_selesai->format('d/m/Y') : '-' }}</div>
                            </div>
                        </td>
                        <td>
                            <span class="text-muted">
                                {{ $training->participants_count ?? 0 }}
                                @if($training->kapasitas)
                                / {{ $training->kapasitas }}
                                @endif
                            </span>
                        </td>
                        <td>
                            @php
                                $statusMap = [
                                    'draft' => ['label' => 'Draft', 'class' => 'badge-draft'],
                                    'published' => ['label' => 'Published', 'class' => 'badge-published'],
                                    'berjalan' => ['label' => 'Berjalan', 'class' => 'badge-berjalan'],
                                    'selesai' => ['label' => 'Selesai', 'class' => 'badge-selesai'],
                                    'dibatalkan' => ['label' => 'Dibatalkan', 'class' => 'badge-dibatalkan'],
                                ];
                                $status = $statusMap[$training->status] ?? ['label' => $training->status, 'class' => 'badge-draft'];
                            @endphp
                            <span class="badge {{ $status['class'] }}">
                                <i class="bi bi-circle-fill me-1" style="font-size: 6px;"></i>
                                {{ $status['label'] }}
                            </span>
                        </td>
                        <td class="text-end">
                            <div class="d-flex gap-1 justify-content-end">
                                {{-- Tombol Show dengan modal --}}
                                <button type="button" class="badge bg-info text-white border-0 p-2" 
                                        data-bs-toggle="modal" data-bs-target="#showModal{{ $training->id }}" 
                                        title="Lihat">
                                    <i class="bi bi-eye"></i> Lihat
                                </button>
                                
                                {{-- Tombol Edit dengan modal --}}
                                <button type="button" class="badge bg-warning text-dark border-0 p-2" 
                                        data-bs-toggle="modal" data-bs-target="#editModal{{ $training->id }}" 
                                        title="Edit">
                                    <i class="bi bi-pencil"></i> Edit
                                </button>
                                
                                {{-- Tombol Delete dengan modal --}}
                                <button type="button" class="badge bg-danger text-white border-0 p-2" 
                                        data-bs-toggle="modal" data-bs-target="#deleteModal{{ $training->id }}" 
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
                    <p class="h5">Belum ada pelatihan</p>
                    <p class="small">
                        @if(request('search'))
                            Tidak ada pelatihan yang sesuai dengan pencarian "{{ request('search') }}".
                        @else
                            Mulai dengan menambahkan pelatihan baru
                        @endif
                    </p>
                    @if(request('search'))
                    <a href="{{ route('admin.trainings.index') }}" class="btn btn-outline-secondary btn-sm mt-2">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset Filter
                    </a>
                    @endif
                    <button type="button" class="btn btn-primary btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#createModal">
                        <i class="bi bi-plus-circle"></i> Tambah Pelatihan
                    </button>
                </div>
            </div>
            @endif
        </div>
        @if($trainings->hasPages())
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mt-3 px-3 pb-3">
            <p class="text-muted small mb-0">
                Menampilkan {{ $trainings->firstItem() ?? 0 }} sampai {{ $trainings->lastItem() ?? 0 }} 
                dari {{ $trainings->total() ?? 0 }} pelatihan
            </p>
            <nav aria-label="Pelatihan pagination">
                {{ $trainings->links() }}
            </nav>
        </div>
        @endif
    </div>
</div>

<!-- ============================================================ -->
<!-- MODAL CREATE -->
<!-- ============================================================ -->
<div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.trainings.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-plus-circle text-primary me-2"></i>Tambah Pelatihan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Judul Pelatihan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="judul" placeholder="Masukkan judul pelatihan" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Kategori</label>
                            <select class="form-select" name="kategori_id">
                                <option value="">Pilih Kategori</option>
                                @foreach($kategoris ?? [] as $kategori)
                                <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Deskripsi</label>
                            <textarea class="form-control" name="deskripsi" rows="3" placeholder="Deskripsi pelatihan"></textarea>
                        </div>
                        <!-- Trainer DIHAPUS -->
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Tipe</label>
                            <select class="form-select" name="tipe">
                                <option value="online">Online</option>
                                <option value="offline">Offline</option>
                                <option value="hybrid">Hybrid</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Tanggal Mulai <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="tanggal_mulai" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Tanggal Selesai <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="tanggal_selesai" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Kapasitas</label>
                            <input type="number" class="form-control" name="kapasitas" placeholder="Maksimal peserta" min="1">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Status</label>
                            <select class="form-select" name="status">
                                <option value="draft">Draft</option>
                                <option value="published">Published</option>
                                <option value="berjalan">Berjalan</option>
                                <option value="selesai">Selesai</option>
                                <option value="dibatalkan">Dibatalkan</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Lokasi</label>
                            <input type="text" class="form-control" name="lokasi" placeholder="Lokasi pelatihan">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Link Meeting</label>
                            <input type="url" class="form-control" name="link_meeting" placeholder="https://meet.google.com/...">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Gambar</label>
                            <input type="file" class="form-control" name="gambar" accept="image/*">
                            <small class="text-muted">Format: JPG, PNG, JPEG. Maksimal 2MB.</small>
                        </div>
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
@foreach($trainings ?? [] as $training)
<div class="modal fade" id="showModal{{ $training->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-info-circle text-info me-2"></i>Detail Pelatihan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    @if($training->gambar)
                    <div class="col-12 text-center">
                        <img src="{{ asset('storage/' . $training->gambar) }}" alt="{{ $training->judul }}" 
                             style="max-width: 100%; max-height: 200px; object-fit: cover; border-radius: 8px;">
                    </div>
                    @endif
                    <div class="col-12">
                        <h5 class="fw-bold">{{ $training->judul }}</h5>
                        <p class="text-muted">{{ $training->deskripsi ?? 'Tidak ada deskripsi' }}</p>
                    </div>
                    <div class="col-6">
                        <label class="text-muted small fw-semibold">Kategori</label>
                        <p class="fw-semibold mb-0">{{ $training->kategori->nama ?? '-' }}</p>
                    </div>
                    <div class="col-6">
                        <label class="text-muted small fw-semibold">Tipe</label>
                        <p class="fw-semibold mb-0">{{ ucfirst($training->tipe ?? 'Online') }}</p>
                    </div>
                    <div class="col-6">
                        <label class="text-muted small fw-semibold">Status</label>
                        <p class="fw-semibold mb-0">
                            <span class="badge {{ $statusMap[$training->status]['class'] ?? 'badge-draft' }}">
                                {{ $statusMap[$training->status]['label'] ?? $training->status }}
                            </span>
                        </p>
                    </div>
                    <div class="col-6">
                        <label class="text-muted small fw-semibold">Tanggal Mulai</label>
                        <p class="fw-semibold mb-0">{{ $training->tanggal_mulai ? $training->tanggal_mulai->format('d/m/Y') : '-' }}</p>
                    </div>
                    <div class="col-6">
                        <label class="text-muted small fw-semibold">Tanggal Selesai</label>
                        <p class="fw-semibold mb-0">{{ $training->tanggal_selesai ? $training->tanggal_selesai->format('d/m/Y') : '-' }}</p>
                    </div>
                    <div class="col-6">
                        <label class="text-muted small fw-semibold">Lokasi</label>
                        <p class="fw-semibold mb-0">{{ $training->lokasi ?? '-' }}</p>
                    </div>
                    <div class="col-6">
                        <label class="text-muted small fw-semibold">Kapasitas</label>
                        <p class="fw-semibold mb-0">{{ $training->kapasitas ?? 'Tak terbatas' }}</p>
                    </div>
                    <div class="col-6">
                        <label class="text-muted small fw-semibold">Peserta Terdaftar</label>
                        <p class="fw-semibold mb-0">{{ $training->participants_count ?? 0 }}</p>
                    </div>
                    <div class="col-6">
                        <label class="text-muted small fw-semibold">Dibuat</label>
                        <p class="fw-semibold mb-0">{{ $training->created_at ? $training->created_at->format('d/m/Y H:i') : '-' }}</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $training->id }}" data-bs-dismiss="modal">
                    <i class="bi bi-pencil me-1"></i> Edit
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- MODAL EDIT -->
<!-- ============================================================ -->
<div class="modal fade" id="editModal{{ $training->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.trainings.update', $training->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-pencil-square text-warning me-2"></i>Edit Pelatihan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Judul Pelatihan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="judul" value="{{ $training->judul }}" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Kategori</label>
                            <select class="form-select" name="kategori_id">
                                <option value="">Pilih Kategori</option>
                                @foreach($kategoris ?? [] as $kategori)
                                <option value="{{ $kategori->id }}" {{ $training->kategori_id == $kategori->id ? 'selected' : '' }}>
                                    {{ $kategori->nama }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Deskripsi</label>
                            <textarea class="form-control" name="deskripsi" rows="3">{{ $training->deskripsi }}</textarea>
                        </div>
                        <!-- Trainer DIHAPUS -->
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Tipe</label>
                            <select class="form-select" name="tipe">
                                <option value="online" {{ $training->tipe == 'online' ? 'selected' : '' }}>Online</option>
                                <option value="offline" {{ $training->tipe == 'offline' ? 'selected' : '' }}>Offline</option>
                                <option value="hybrid" {{ $training->tipe == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Tanggal Mulai <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="tanggal_mulai" value="{{ $training->tanggal_mulai?->format('Y-m-d') }}" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Tanggal Selesai <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="tanggal_selesai" value="{{ $training->tanggal_selesai?->format('Y-m-d') }}" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Kapasitas</label>
                            <input type="number" class="form-control" name="kapasitas" value="{{ $training->kapasitas }}" min="1">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Status</label>
                            <select class="form-select" name="status">
                                <option value="draft" {{ $training->status == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ $training->status == 'published' ? 'selected' : '' }}>Published</option>
                                <option value="berjalan" {{ $training->status == 'berjalan' ? 'selected' : '' }}>Berjalan</option>
                                <option value="selesai" {{ $training->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="dibatalkan" {{ $training->status == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Lokasi</label>
                            <input type="text" class="form-control" name="lokasi" value="{{ $training->lokasi }}">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Link Meeting</label>
                            <input type="url" class="form-control" name="link_meeting" value="{{ $training->link_meeting }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Gambar</label>
                            @if($training->gambar)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $training->gambar) }}" alt="{{ $training->judul }}" 
                                     style="max-width: 100px; max-height: 100px; object-fit: cover; border-radius: 8px;">
                                <small class="text-muted d-block">Gambar saat ini</small>
                            </div>
                            @endif
                            <input type="file" class="form-control" name="gambar" accept="image/*">
                            <small class="text-muted">Format: JPG, PNG, JPEG. Maksimal 2MB.</small>
                        </div>
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
<div class="modal fade" id="deleteModal{{ $training->id }}" tabindex="-1" 
     aria-labelledby="deleteModalLabel{{ $training->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel{{ $training->id }}">
                    <i class="bi bi-exclamation-triangle text-danger me-2"></i>
                    Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus pelatihan <strong>{{ $training->judul }}</strong>?</p>
                @if(($training->participants_count ?? 0) > 0)
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Pelatihan ini memiliki <strong>{{ $training->participants_count }}</strong> peserta. 
                    Menghapus pelatihan akan menghapus semua data pendaftaran.
                </div>
                @endif
                <p class="text-muted small">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin.trainings.destroy', $training->id) }}" method="POST" class="d-inline">
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
        // Auto close alerts after 5 seconds
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