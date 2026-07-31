@extends('layouts.admin')

@section('title', 'Manajemen Pengumuman')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-megaphone"></i></span>
        <div>
            <p class="eyebrow">Manajemen</p>
            <h1 class="h3 mb-0">Pengumuman</h1>
            <p class="text-muted mb-0">Kelola semua pengumuman yang tersedia.</p>
        </div>
    </div>
    <div class="heading-actions d-flex gap-2">
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="bi bi-plus-circle"></i> Tambah Pengumuman
        </button>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
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

    @if(session('warning'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>
        {{ session('warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Stats -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-primary">
                <div class="metric-top">
                    <span class="metric-label">Total Pengumuman</span>
                    <span class="metric-icon"><i class="bi bi-megaphone"></i></span>
                </div>
                <div class="metric-value">{{ $totalPengumuman ?? $pengumumans->total() ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-primary">Semua</span>
                    <span>pengumuman</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-success">
                <div class="metric-top">
                    <span class="metric-label">Published</span>
                    <span class="metric-icon"><i class="bi bi-check-circle"></i></span>
                </div>
                <div class="metric-value">{{ $publishedCount ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-success">Dipublikasikan</span>
                    <span>aktif</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-warning">
                <div class="metric-top">
                    <span class="metric-label">Draft</span>
                    <span class="metric-icon"><i class="bi bi-pencil"></i></span>
                </div>
                <div class="metric-value">{{ $draftCount ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-warning">Draft</span>
                    <span>perlu review</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-info">
                <div class="metric-top">
                    <span class="metric-label">Archived</span>
                    <span class="metric-icon"><i class="bi bi-archive"></i></span>
                </div>
                <div class="metric-value">{{ $archivedCount ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-info">Diarsipkan</span>
                    <span>pengumuman</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="panel mb-3">
        <div class="panel-header">
            <div>
                <h5 class="section-title"><i class="bi bi-funnel"></i> Filter & Pencarian</h5>
            </div>
            <form action="{{ route('admin.pengumuman.index') }}" method="GET" class="d-flex gap-2 flex-wrap align-items-center">
                <div class="input-group input-group-sm" style="width: 200px;">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control" name="search" placeholder="Cari pengumuman..." value="{{ request('search') }}">
                </div>
                <select class="form-select form-select-sm" name="status" style="width: 150px;">
                    <option value="">Semua Status</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>✅ Published</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>📝 Draft</option>
                    <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>📦 Archived</option>
                </select>
                <button type="submit" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-search"></i> Filter
                </button>
                <a href="{{ route('admin.pengumuman.index') }}" class="btn btn-outline-secondary btn-sm" title="Reset Filter">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                </a>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="bi bi-plus-circle"></i> Tambah
                </button>
            </form>
        </div>
        @if(request('search') || request('status'))
        <div class="p-2 px-3 bg-light border-top">
            <small class="text-muted">
                <i class="bi bi-filter-circle me-1"></i>
                Filter aktif: 
                @if(request('search'))
                    <span class="badge text-bg-primary">Pencarian: "{{ request('search') }}"</span>
                @endif
                @if(request('status'))
                    <span class="badge text-bg-primary">Status: {{ ucfirst(request('status')) }}</span>
                @endif
                <a href="{{ route('admin.pengumuman.index') }}" class="text-danger ms-2">
                    <i class="bi bi-x-circle"></i> Hapus semua filter
                </a>
            </small>
        </div>
        @endif
    </div>

    <!-- Table -->
    <div class="panel">
        <div class="panel-header">
            <div>
                <h5 class="section-title"><i class="bi bi-table"></i> Daftar Pengumuman</h5>
                <p class="text-muted small mb-0">Kelola semua pengumuman yang tersedia.</p>
            </div>
        </div>
        <div class="table-responsive">
            @if(isset($pengumumans) && $pengumumans->count() > 0)
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 40px;">
                            <input type="checkbox" id="selectAll" class="form-check-input">
                        </th>
                        <th style="width: 50px;">#</th>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th class="text-end" style="width: 160px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pengumumans as $index => $item)
                    <tr>
                        <td>
                            <input type="checkbox" class="form-check-input pengumuman-checkbox" value="{{ $item->id }}">
                        </td>
                        <td>{{ $pengumumans->firstItem() + $index }}</td>
                        <td>
                            <div>
                                <p class="fw-semibold mb-0">{{ $item->judul }}</p>
                            </div>
                        </td>
                        <td>
                            @if($item->kategori)
                            <span>{{ $item->kategori->nama }}</span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <div class="small">
                                <div>{{ $item->tanggal ? $item->tanggal->format('d/m/Y') : '-' }}</div>
                            </div>
                        </td>
                        <td>
                            @php
                                $statusMap = [
                                    'published' => ['label' => '✅ Published', 'class' => 'text-bg-success'],
                                    'draft' => ['label' => '📝 Draft', 'class' => 'text-bg-secondary'],
                                    'archived' => ['label' => '📦 Archived', 'class' => 'text-bg-secondary'],
                                ];
                                $status = $statusMap[$item->status] ?? ['label' => $item->status ?? 'Unknown', 'class' => 'text-bg-secondary'];
                            @endphp
                            <span class="badge {{ $status['class'] }}">
                                {{ $status['label'] }}
                            </span>
                        </td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-info" 
                                        data-bs-toggle="modal" data-bs-target="#showModal{{ $item->id }}" 
                                        title="Lihat">
                                    <i class="bi bi-eye"></i> Lihat
                                </button>
                                <button type="button" class="btn btn-warning" 
                                        data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id }}" 
                                        title="Edit">
                                    <i class="bi bi-pencil"></i> Edit
                                </button>
                                <button type="button" class="btn btn-danger" 
                                        data-bs-toggle="modal" data-bs-target="#deleteModal{{ $item->id }}" 
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
                    <p class="h5">Belum ada pengumuman</p>
                    <p class="small">Mulai dengan menambahkan pengumuman baru</p>
                    <button type="button" class="btn btn-primary btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#createModal">
                        <i class="bi bi-plus-circle"></i> Tambah Pengumuman
                    </button>
                </div>
            </div>
            @endif
        </div>
        @if(isset($pengumumans) && $pengumumans->hasPages())
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mt-3 px-3 pb-3">
            <p class="text-muted small mb-0">
                Menampilkan {{ $pengumumans->firstItem() ?? 0 }} sampai {{ $pengumumans->lastItem() ?? 0 }} 
                dari {{ $pengumumans->total() ?? 0 }} pengumuman
            </p>
            <nav aria-label="Pengumuman pagination">
                {{ $pengumumans->links() }}
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
            <form action="{{ route('admin.pengumuman.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-plus-circle text-primary me-2"></i>Tambah Pengumuman
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Training</label>
                            <select class="form-select" name="training_id">
                                <option value="">Pilih Training (Opsional)</option>
                                @foreach($trainings ?? [] as $training)
                                <option value="{{ $training->id }}">{{ $training->judul }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Kategori</label>
                            <select class="form-select" name="kategori_id">
                                <option value="">Pilih Kategori (Opsional)</option>
                                @foreach($kategoris ?? [] as $kategori)
                                <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Judul <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="judul" placeholder="Masukkan judul pengumuman" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Deskripsi</label>
                            <textarea class="form-control" name="deskripsi" rows="2" placeholder="Deskripsi singkat (opsional)"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Konten <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="konten" rows="6" placeholder="Isi pengumuman..." required></textarea>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="tanggal" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Tanggal Selesai</label>
                            <input type="date" class="form-control" name="tanggal_selesai">
                            <small class="text-muted">Kosongkan jika tidak ada batas waktu.</small>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Target Audience <span class="text-danger">*</span></label>
                            <select class="form-select" name="target_audience" required>
                                <option value="all">🌍 Semua</option>
                                <option value="peserta">👤 Peserta</option>
                                <option value="trainer">👨‍🏫 Trainer</option>
                                <option value="admin">🛡️ Admin</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <select class="form-select" name="status" required>
                                <option value="draft">📝 Draft</option>
                                <option value="published">✅ Published</option>
                                <option value="archived">📦 Archived</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input type="hidden" name="is_pinned" value="0">
                                <input class="form-check-input" type="checkbox" id="is_pinned" name="is_pinned" value="1">
                                <label class="form-check-label fw-semibold" for="is_pinned">
                                    <i class="bi bi-pin-fill text-warning me-1"></i> Pin Pengumuman
                                </label>
                                <small class="d-block text-muted">Pengumuman yang di-pin akan muncul di bagian atas.</small>
                            </div>
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
<!-- MODAL SHOW, EDIT, DELETE -->
<!-- ============================================================ -->
@if(isset($pengumumans) && $pengumumans->count() > 0)
@foreach($pengumumans as $item)
<!-- MODAL SHOW -->
<div class="modal fade" id="showModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-info-circle text-info me-2"></i>Detail Pengumuman
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    @if($item->gambar)
                    <div class="col-12 text-center">
                        <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->judul }}" 
                             style="max-width: 100%; max-height: 300px; object-fit: cover; border-radius: 8px;">
                    </div>
                    @endif
                    <div class="col-12">
                        <h5 class="fw-bold">{{ $item->judul }}</h5>
                        <p class="text-muted">{{ $item->deskripsi ?? 'Tidak ada deskripsi' }}</p>
                    </div>
                    <div class="col-6">
                        <label class="text-muted small fw-semibold">Kategori</label>
                        <p class="fw-semibold mb-0">{{ $item->kategori->nama ?? '-' }}</p>
                    </div>
                    <div class="col-6">
                        <label class="text-muted small fw-semibold">Status</label>
                        <p class="fw-semibold mb-0">
                            <span class="badge {{ $statusMap[$item->status]['class'] ?? 'text-bg-secondary' }}">
                                {{ $statusMap[$item->status]['label'] ?? $item->status }}
                            </span>
                        </p>
                    </div>
                    <div class="col-6">
                        <label class="text-muted small fw-semibold">Tanggal</label>
                        <p class="fw-semibold mb-0">{{ $item->tanggal ? $item->tanggal->format('d/m/Y') : '-' }}</p>
                    </div>
                    <div class="col-6">
                        <label class="text-muted small fw-semibold">Berlaku s/d</label>
                        <p class="fw-semibold mb-0">{{ $item->tanggal_selesai ? $item->tanggal_selesai->format('d/m/Y') : '-' }}</p>
                    </div>
                    <div class="col-6">
                        <label class="text-muted small fw-semibold">Target Audience</label>
                        <p class="fw-semibold mb-0">{{ ucfirst($item->target_audience ?? 'All') }}</p>
                    </div>
                    <div class="col-6">
                        <label class="text-muted small fw-semibold">Dibuat</label>
                        <p class="fw-semibold mb-0">{{ $item->created_at ? $item->created_at->format('d/m/Y H:i') : '-' }}</p>
                    </div>
                    @if($item->training)
                    <div class="col-12">
                        <label class="text-muted small fw-semibold">Training</label>
                        <p class="fw-semibold mb-0">{{ $item->training->judul }}</p>
                    </div>
                    @endif
                    <div class="col-12">
                        <label class="text-muted small fw-semibold">Konten</label>
                        <div class="p-3 bg-light rounded-3" style="line-height: 1.8;">
                            {!! nl2br(e($item->konten)) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id }}" data-bs-dismiss="modal">
                    <i class="bi bi-pencil me-1"></i> Edit
                </button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL EDIT -->
<div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.pengumuman.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-pencil-square text-warning me-2"></i>Edit Pengumuman
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Training</label>
                            <select class="form-select" name="training_id">
                                <option value="">Pilih Training (Opsional)</option>
                                @foreach($trainings ?? [] as $training)
                                <option value="{{ $training->id }}" {{ $item->training_id == $training->id ? 'selected' : '' }}>
                                    {{ $training->judul }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Kategori</label>
                            <select class="form-select" name="kategori_id">
                                <option value="">Pilih Kategori (Opsional)</option>
                                @foreach($kategoris ?? [] as $kategori)
                                <option value="{{ $kategori->id }}" {{ $item->kategori_id == $kategori->id ? 'selected' : '' }}>
                                    {{ $kategori->nama }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Judul <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="judul" value="{{ $item->judul }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Deskripsi</label>
                            <textarea class="form-control" name="deskripsi" rows="2">{{ $item->deskripsi }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Konten <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="konten" rows="6" required>{{ $item->konten }}</textarea>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="tanggal" value="{{ $item->tanggal ? $item->tanggal->format('Y-m-d') : date('Y-m-d') }}" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Tanggal Selesai</label>
                            <input type="date" class="form-control" name="tanggal_selesai" value="{{ $item->tanggal_selesai ? $item->tanggal_selesai->format('Y-m-d') : '' }}">
                            <small class="text-muted">Kosongkan jika tidak ada batas waktu.</small>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Target Audience <span class="text-danger">*</span></label>
                            <select class="form-select" name="target_audience" required>
                                <option value="all" {{ $item->target_audience == 'all' ? 'selected' : '' }}>🌍 Semua</option>
                                <option value="peserta" {{ $item->target_audience == 'peserta' ? 'selected' : '' }}>👤 Peserta</option>
                                <option value="trainer" {{ $item->target_audience == 'trainer' ? 'selected' : '' }}>👨‍🏫 Trainer</option>
                                <option value="admin" {{ $item->target_audience == 'admin' ? 'selected' : '' }}>🛡️ Admin</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <select class="form-select" name="status" required>
                                <option value="draft" {{ $item->status == 'draft' ? 'selected' : '' }}>📝 Draft</option>
                                <option value="published" {{ $item->status == 'published' ? 'selected' : '' }}>✅ Published</option>
                                <option value="archived" {{ $item->status == 'archived' ? 'selected' : '' }}>📦 Archived</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input type="hidden" name="is_pinned" value="0">
                                <input class="form-check-input" type="checkbox" id="is_pinned" name="is_pinned" value="1" {{ $item->is_pinned ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="is_pinned">
                                    <i class="bi bi-pin-fill text-warning me-1"></i> Pin Pengumuman
                                </label>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Gambar</label>
                            @if($item->gambar)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->judul }}" 
                                     style="max-width: 100px; max-height: 100px; object-fit: cover; border-radius: 8px;">
                                <small class="text-muted d-block">Gambar saat ini</small>
                            </div>
                            @endif
                            <input type="file" class="form-control" name="gambar" accept="image/*">
                            <small class="text-muted">Format: JPG, PNG, JPEG. Maksimal 2MB. Kosongkan jika tidak ingin mengubah.</small>
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

<!-- MODAL DELETE -->
<div class="modal fade" id="deleteModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
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
                <p>Apakah Anda yakin ingin menghapus pengumuman ini?</p>
                <div class="alert alert-light">
                    <p class="fw-semibold mb-0">{{ $item->judul }}</p>
                    <p class="text-muted small mb-0">{{ $item->tanggal ? $item->tanggal->format('d/m/Y') : '-' }}</p>
                </div>
                @if($item->status == 'published')
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Pengumuman ini sudah dipublikasikan. Menghapus akan menghapus semua data terkait.
                </div>
                @endif
                <p class="text-muted small">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin.pengumuman.destroy', $item->id) }}" method="POST" class="d-inline">
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
@endif

@push('styles')
<style>
    .metric-card {
        background: #fff;
        border-radius: 0.75rem;
        padding: 1.1rem 1.25rem;
        box-shadow: 0 1px 4px rgba(0,0,0,.06);
        border-left: 4px solid transparent;
        height: 100%;
        transition: transform 0.2s ease;
    }
    .metric-card:hover {
        transform: translateY(-4px);
    }
    .metric-primary { border-left-color: #4e9af1; }
    .metric-success { border-left-color: #28c76f; }
    .metric-warning { border-left-color: #ff9f43; }
    .metric-info { border-left-color: #17a2b8; }
    
    .metric-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: .4rem;
    }
    .metric-label {
        font-size: .75rem;
        color: #8a93a3;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .03em;
    }
    .metric-icon {
        color: #c3cad6;
        font-size: 1.3rem;
    }
    .metric-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1a2236;
    }
    .metric-meta {
        font-size: .75rem;
        color: #8a93a3;
        display: flex;
        gap: .35rem;
    }

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

    .btn-group .btn {
        border-radius: 6px;
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
        transition: all 0.2s ease;
    }
    .btn-group .btn:hover {
        transform: scale(1.1);
    }

    .modal-lg {
        max-width: 800px;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ============================================================
    // SELECT ALL CHECKBOX
    // ============================================================
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.pengumuman-checkbox');

    if (selectAll) {
        selectAll.addEventListener('change', function() {
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
    }

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
    // FOCUS SEARCH ON KEYBOARD SHORTCUT (CTRL + /)
    // ============================================================
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.key === '/') {
            e.preventDefault();
            const searchInput = document.querySelector('input[name="search"]');
            if (searchInput) {
                searchInput.focus();
                searchInput.select();
            }
        }
    });

    // ============================================================
    // PREVIEW IMAGE BEFORE UPLOAD
    // ============================================================
    document.querySelectorAll('input[type="file"][name="gambar"]').forEach(function(input) {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = this.closest('.modal-body').querySelector('.preview-image');
                    if (preview) {
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    });

    // ============================================================
    // SEARCH WITH ENTER KEY
    // ============================================================
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                this.closest('form').submit();
            }
        });
    }
});
</script>
@endpush
@endsection