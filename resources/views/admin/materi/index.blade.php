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
                
                {{-- Tombol Tambah dengan modal --}}
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="bi bi-plus-circle"></i> Tambah
                </button>
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
                                {{-- Tombol Show dengan modal --}}
                                <button type="button" class="badge bg-info text-white border-0 p-2" 
                                        data-bs-toggle="modal" data-bs-target="#showModal{{ $materi->id }}" 
                                        title="Lihat">
                                    <i class="bi bi-eye"></i> 
                                </button>
                                
                                {{-- Tombol Edit dengan modal --}}
                                <button type="button" class="badge bg-warning text-dark border-0 p-2" 
                                        data-bs-toggle="modal" data-bs-target="#editModal{{ $materi->id }}" 
                                        title="Edit">
                                    <i class="bi bi-pencil"></i> 
                                </button>
                                
                                @if($materi->hasFile())
                                <a href="{{ route('admin.materi.download', $materi->id) }}" 
                                   class="badge bg-success text-white text-decoration-none p-2" title="Download">
                                    <i class="bi bi-download"></i> 
                                </a>
                                @endif
                                
                                {{-- Tombol Delete dengan modal --}}
                                <button type="button" class="badge bg-danger text-white border-0 p-2" 
                                        data-bs-toggle="modal" data-bs-target="#deleteModal{{ $materi->id }}" 
                                        title="Hapus">
                                    <i class="bi bi-trash"></i> 
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
                    <button type="button" class="btn btn-primary btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#createModal">
                        <i class="bi bi-plus-circle"></i> Tambah Materi
                    </button>
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

<!-- ============================================================
     MODAL CREATE
============================================================ -->
<div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.materi.store') }}" method="POST" enctype="multipart/form-data" id="createForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-plus-circle text-primary me-2"></i>Tambah Materi
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-tag"></i></span>
                                <select class="form-select" name="kategori_id" required>
                                    <option value="">Pilih Kategori</option>
                                    @if(isset($kategoris) && $kategoris->count() > 0)
                                        @foreach($kategoris as $kategori)
                                        <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                                        @endforeach
                                    @else
                                        <option value="" disabled>Tidak ada kategori</option>
                                    @endif
                                </select>
                            </div>
                            @error('kategori_id')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Training <span class="text-muted">(Opsional)</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-journal-bookmark"></i></span>
                                <select class="form-select" name="training_id" id="trainingSelect">
                                    <option value="">Pilih Training (Opsional)</option>
                                    @if(isset($trainings) && $trainings->count() > 0)
                                        @foreach($trainings as $training)
                                        <option value="{{ $training->id }}" {{ old('training_id') == $training->id ? 'selected' : '' }}>
                                            {{ $training->judul }}
                                        </option>
                                        @endforeach
                                    @else
                                        <option value="" disabled>Tidak ada training</option>
                                    @endif
                                </select>
                            </div>
                            @error('training_id')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                            <small class="text-muted">Pilih training jika materi ini terkait dengan training tertentu.</small>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label fw-semibold">Judul <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-text-paragraph"></i></span>
                                <input type="text" class="form-control" name="judul" placeholder="Masukkan judul materi" value="{{ old('judul') }}" required>
                            </div>
                            @error('judul')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label fw-semibold">Deskripsi</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-file-text"></i></span>
                                <textarea class="form-control" name="deskripsi" rows="2" placeholder="Deskripsi materi (opsional)">{{ old('deskripsi') }}</textarea>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label fw-semibold">Upload File</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-upload"></i></span>
                                <input type="file" class="form-control" name="files[]" multiple accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.zip,.mp4,.avi,.mkv,.mov,.jpg,.jpeg,.png,.gif">
                            </div>
                            <small class="text-muted">Maksimal 100MB per file. Bisa upload multiple file.</small>
                            @error('files.*')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label fw-semibold">Link (URL) <span class="text-muted">(Opsional)</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-link-45deg"></i></span>
                                <input type="url" class="form-control" name="file_urls[]" placeholder="https://example.com/materi" value="{{ old('file_urls.0') }}">
                            </div>
                            <small class="text-muted">Tambahkan link ke materi eksternal (YouTube, Google Drive, dll).</small>
                        </div>
                        
                        <div class="col-12 col-md-4">
                            <label class="form-label fw-semibold">Durasi (menit)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-clock"></i></span>
                                <input type="number" class="form-control" name="durasi" placeholder="30" min="1" value="{{ old('durasi') }}">
                            </div>
                        </div>
                        
                        <div class="col-12 col-md-4">
                            <label class="form-label fw-semibold">Urutan</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-list-ol"></i></span>
                                <input type="number" class="form-control" name="order" placeholder="0" min="0" value="{{ old('order', 0) }}">
                            </div>
                            <small class="text-muted">Semakin kecil angka, semakin atas tampilnya.</small>
                        </div>
                        
                        <div class="col-12 col-md-4">
                            <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-toggle-on"></i></span>
                                <select class="form-select" name="status" required>
                                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>📝 Draft</option>
                                    <option value="published" {{ old('status', 'published') == 'published' ? 'selected' : '' }}>✅ Published</option>
                                    <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>📦 Archived</option>
                                </select>
                            </div>
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

<!-- ============================================================
     MODAL SHOW
============================================================ -->
@foreach($materis ?? [] as $materi)
<div class="modal fade" id="showModal{{ $materi->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-eye text-info me-2"></i>Detail Materi
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="text-muted small fw-semibold">Judul</label>
                        <p class="fw-semibold fs-5">{{ $materi->judul }}</p>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="text-muted small fw-semibold">Slug</label>
                        <p><code>{{ $materi->slug }}</code></p>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="text-muted small fw-semibold">Kategori</label>
                        <p>{{ $materi->kategori->nama ?? '-' }}</p>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="text-muted small fw-semibold">Training</label>
                        <p>{{ $materi->training->judul ?? '-' }}</p>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="text-muted small fw-semibold">Durasi</label>
                        <p>{{ $materi->durasi ? $materi->durasi . ' menit' : '-' }}</p>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="text-muted small fw-semibold">Urutan</label>
                        <p>{{ $materi->order ?? 0 }}</p>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="text-muted small fw-semibold">Status</label>
                        <p><span class="badge {{ $statusMap[$materi->status]['class'] ?? 'badge-draft' }}">{{ $statusMap[$materi->status]['label'] ?? $materi->status }}</span></p>
                    </div>
                    @if($materi->deskripsi)
                    <div class="col-12">
                        <label class="text-muted small fw-semibold">Deskripsi</label>
                        <p>{{ $materi->deskripsi }}</p>
                    </div>
                    @endif
                    @if($materi->hasFile())
                    <div class="col-12">
                        <label class="text-muted small fw-semibold">File</label>
                        <div class="table-responsive mt-2">
                            <table class="table table-sm table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Nama File</th>
                                        <th>Tipe</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($materi->files as $idx => $file)
                                    <tr>
                                        <td>{{ $idx + 1 }}</td>
                                        <td>{{ $file['name'] ?? basename($file['path'] ?? $file['url'] ?? '') }}</td>
                                        <td><span class="badge bg-info">{{ $materi->getFileTypeLabel($file['type'] ?? 'other') }}</span></td>
                                        <td>
                                            @if(!empty($file['path']))
                                            <a href="{{ route('admin.materi.download', ['materi' => $materi->id, 'index' => $idx]) }}" 
                                               class="btn btn-sm btn-success" target="_blank">
                                                <i class="bi bi-download"></i>
                                            </a>
                                            @elseif(!empty($file['url']))
                                            <a href="{{ $file['url'] }}" class="btn btn-sm btn-info" target="_blank">
                                                <i class="bi bi-box-arrow-up-right"></i>
                                            </a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                    <div class="col-12 col-md-6">
                        <label class="text-muted small fw-semibold">Dibuat</label>
                        <p>{{ $materi->created_at ? $materi->created_at->format('d/m/Y H:i') : '-' }}</p>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="text-muted small fw-semibold">Diperbarui</label>
                        <p>{{ $materi->updated_at ? $materi->updated_at->format('d/m/Y H:i') : '-' }}</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $materi->id }}" data-bs-dismiss="modal">
                    <i class="bi bi-pencil me-1"></i> Edit
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================
     MODAL EDIT
============================================================ -->
<div class="modal fade" id="editModal{{ $materi->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.materi.update', $materi->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-pencil-square text-warning me-2"></i>Edit Materi
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-tag"></i></span>
                                <select class="form-select" name="kategori_id" required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach($kategoris ?? [] as $kategori)
                                    <option value="{{ $kategori->id }}" {{ $materi->kategori_id == $kategori->id ? 'selected' : '' }}>
                                        {{ $kategori->nama }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Training</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-journal-bookmark"></i></span>
                                <select class="form-select" name="training_id">
                                    <option value="">Pilih Training (Opsional)</option>
                                    @foreach($trainings ?? [] as $training)
                                    <option value="{{ $training->id }}" {{ $materi->training_id == $training->id ? 'selected' : '' }}>
                                        {{ $training->judul }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Judul <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-text-paragraph"></i></span>
                                <input type="text" class="form-control" name="judul" value="{{ $materi->judul }}" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Deskripsi</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-file-text"></i></span>
                                <textarea class="form-control" name="deskripsi" rows="2">{{ $materi->deskripsi }}</textarea>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Upload File Baru</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-upload"></i></span>
                                <input type="file" class="form-control" name="files[]" multiple accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.zip,.mp4,.avi,.mkv,.mov,.jpg,.jpeg,.png,.gif">
                            </div>
                            <small class="text-muted">File baru akan ditambahkan ke file existing.</small>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Tipe File</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-tag"></i></span>
                                <select class="form-select" name="file_types[]">
                                    <option value="pdf">📄 PDF</option>
                                    <option value="video">🎬 Video</option>
                                    <option value="ppt">📊 Presentasi</option>
                                    <option value="image">🖼️ Gambar</option>
                                    <option value="other">📁 Lainnya</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Link (URL)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-link-45deg"></i></span>
                                <input type="url" class="form-control" name="file_urls[]" placeholder="https://example.com/materi" value="{{ old('file_urls.0') }}">
                            </div>
                            <small class="text-muted">Tambahkan link ke materi eksternal.</small>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label fw-semibold">Durasi (menit)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-clock"></i></span>
                                <input type="number" class="form-control" name="durasi" value="{{ $materi->durasi }}" min="1">
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label fw-semibold">Urutan</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-list-ol"></i></span>
                                <input type="number" class="form-control" name="order" value="{{ $materi->order ?? 0 }}" min="0">
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-toggle-on"></i></span>
                                <select class="form-select" name="status" required>
                                    <option value="draft" {{ $materi->status == 'draft' ? 'selected' : '' }}>📝 Draft</option>
                                    <option value="published" {{ $materi->status == 'published' ? 'selected' : '' }}>✅ Published</option>
                                    <option value="archived" {{ $materi->status == 'archived' ? 'selected' : '' }}>📦 Archived</option>
                                </select>
                            </div>
                        </div>
                        @if($materi->hasFile())
                        <div class="col-12">
                            <hr>
                            <label class="fw-semibold">File Saat Ini</label>
                            <div class="table-responsive mt-2">
                                <table class="table table-sm table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Nama File</th>
                                            <th>Tipe</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($materi->files as $idx => $file)
                                        <tr>
                                            <td>{{ $idx + 1 }}</td>
                                            <td>{{ $file['name'] ?? basename($file['path'] ?? $file['url'] ?? '') }}</td>
                                            <td><span class="badge bg-info">{{ $materi->getFileTypeLabel($file['type'] ?? 'other') }}</span></td>
                                            <td>
                                                <a href="{{ route('admin.materi.download', ['materi' => $materi->id, 'index' => $idx]) }}" 
                                                   class="btn btn-sm btn-success" target="_blank">
                                                    <i class="bi bi-download"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif
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
@endforeach

<!-- ============================================================
     MODAL DELETE
============================================================ -->
@foreach($materis ?? [] as $materi)
<div class="modal fade" id="deleteModal{{ $materi->id }}" tabindex="-1" aria-hidden="true">
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
                <p>Apakah Anda yakin ingin menghapus materi <strong>{{ $materi->judul }}</strong>?</p>
                @if($materi->hasFile())
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Semua file materi akan ikut terhapus ({{ count($materi->files) }} file).
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
@endforeach

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ============================================================
    // AUTO GENERATE SLUG
    // ============================================================
    document.querySelectorAll('#createForm input[name="judul"]').forEach(function(input) {
        input.addEventListener('keyup', function() {
            const slug = this.value.toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');
            if (!document.querySelector('input[name="slug"]')) {
                const slugInput = document.createElement('input');
                slugInput.type = 'hidden';
                slugInput.name = 'slug';
                slugInput.value = slug;
                this.closest('form').appendChild(slugInput);
            } else {
                document.querySelector('input[name="slug"]').value = slug;
            }
        });
    });

    // ============================================================
    // PREVIEW FILE UPLOAD
    // ============================================================
    document.querySelectorAll('input[type="file"][name="files[]"]').forEach(function(input) {
        input.addEventListener('change', function() {
            const files = this.files;
            if (files.length > 0) {
                const parent = this.closest('.input-group');
                let previewDiv = parent.nextElementSibling;
                if (!previewDiv || !previewDiv.classList.contains('file-preview')) {
                    previewDiv = document.createElement('div');
                    previewDiv.className = 'file-preview mt-2';
                    parent.parentNode.insertBefore(previewDiv, parent.nextSibling);
                }
                let html = '<div class="d-flex flex-wrap gap-2">';
                for (let i = 0; i < files.length; i++) {
                    html += `<span class="badge bg-light text-dark border">📄 ${files[i].name}</span>`;
                }
                html += '</div>';
                previewDiv.innerHTML = html;
            }
        });
    });

    // ============================================================
    // AUTO CLOSE ALERTS
    // ============================================================
    setTimeout(function() {
        document.querySelectorAll('.alert').forEach(function(alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);

    // ============================================================
    // MODAL DATA TRANSFER FOR EDIT
    // ============================================================
    document.querySelectorAll('[data-bs-toggle="modal"][data-bs-target*="editModal"]').forEach(function(btn) {
        btn.addEventListener('click', function() {
            // Any additional data transfer if needed
        });
    });
});
</script>
@endpush
@endsection