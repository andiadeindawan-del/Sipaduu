@extends('layouts.admin')

@section('title', 'Manajemen Dokumentasi')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-images"></i></span>
        <div>
            <p class="eyebrow mb-1">Management</p>
            <h1 class="h3 mb-0">Dokumentasi</h1>
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
                    <span class="metric-label">Total Dokumentasi</span>
                    <span class="metric-icon"><i class="bi bi-file-earmark-link"></i></span>
                </div>
                <div class="metric-value">{{ $totalDokumentasi ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-success">+12%</span>
                    <span>bulan ini</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-success">
                <div class="metric-top">
                    <span class="metric-label">Aktif</span>
                    <span class="metric-icon"><i class="bi bi-link-45deg"></i></span>
                </div>
                <div class="metric-value">{{ $activeDokumentasi ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-success">Tersedia</span>
                    <span>link</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-warning">
                <div class="metric-top">
                    <span class="metric-label">Pelatihan</span>
                    <span class="metric-icon"><i class="bi bi-journal-bookmark"></i></span>
                </div>
                <div class="metric-value">{{ $totalTrainings ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-warning">Total</span>
                    <span>pelatihan</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-secondary">
                <div class="metric-top">
                    <span class="metric-label">Baru</span>
                    <span class="metric-icon"><i class="bi bi-clock-history"></i></span>
                </div>
                <div class="metric-value">{{ $newDokumentasi ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-secondary">7 hari</span>
                    <span>terakhir</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="panel">
        <div class="panel-header">
            <div>
                <h5 class="section-title"><i class="bi bi-table"></i> Daftar Dokumentasi</h5>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <form action="{{ route('admin.dokumentasi.index') }}" method="GET" class="d-flex gap-2">
                    <input class="form-control form-control-sm" type="search" name="search" 
                           placeholder="Cari dokumentasi..." value="{{ request('search') }}" style="width: 200px;">
                    <button type="submit" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
                
                @if(request('search'))
                <a href="{{ route('admin.dokumentasi.index') }}" class="btn btn-outline-secondary btn-sm" title="Reset Filter">
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
            @if($dokumentasis->count() > 0)
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Pelatihan</th>
                        <th>Judul</th>
                        <th>Link</th>
                        <th class="text-end" style="width: 160px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dokumentasis as $index => $dokumentasi)
                    <tr>
                        <td>{{ $dokumentasis->firstItem() + $index }}</td>
                        <td>
                            @if($dokumentasi->training)
                            <span class="text-muted">{{ $dokumentasi->training->judul }}</span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <div>
                                <p class="fw-semibold mb-0">{{ $dokumentasi->judul }}</p>
                                @if($dokumentasi->deskripsi)
                                <small class="text-muted">{{ Str::limit($dokumentasi->deskripsi, 50) }}</small>
                                @endif
                            </div>
                        </td>
                        <td>
                            <a href="{{ $dokumentasi->link }}" target="_blank" class="text-primary text-decoration-none">
                                <i class="bi bi-link-45deg me-1"></i>
                                {{ Str::limit($dokumentasi->link, 30) }}
                            </a>
                        </td>
                        <td class="text-end">
                            <div class="d-flex gap-1 justify-content-end">
                                {{-- Tombol Show dengan modal --}}
                                <button type="button" class="badge bg-info text-white border-0 p-2" 
                                        data-bs-toggle="modal" data-bs-target="#showModal{{ $dokumentasi->id }}" 
                                        title="Lihat">
                                    <i class="bi bi-eye"></i> 
                                </button>
                                
                                {{-- Tombol Edit dengan modal --}}
                                <button type="button" class="badge bg-warning text-dark border-0 p-2" 
                                        data-bs-toggle="modal" data-bs-target="#editModal{{ $dokumentasi->id }}" 
                                        title="Edit">
                                    <i class="bi bi-pencil"></i> 
                                </button>
                                
                                {{-- Tombol Buka Link --}}
                                <a href="{{ $dokumentasi->link }}" target="_blank" 
                                   class="badge bg-success text-white text-decoration-none p-2" title="Buka Link">
                                    <i class="bi bi-box-arrow-up-right"></i> 
                                </a>
                                
                                {{-- Tombol Delete dengan modal --}}
                                <button type="button" class="badge bg-danger text-white border-0 p-2" 
                                        data-bs-toggle="modal" data-bs-target="#deleteModal{{ $dokumentasi->id }}" 
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
                            Tidak ada dokumentasi yang sesuai dengan pencarian "{{ request('search') }}"
                        @else
                            Belum ada dokumentasi
                        @endif
                    </p>
                    <p class="small">
                        @if(request('search'))
                            Coba ubah kata kunci pencarian atau reset filter
                        @else
                            Mulai dengan menambahkan dokumentasi baru
                        @endif
                    </p>
                    @if(request('search'))
                    <a href="{{ route('admin.dokumentasi.index') }}" class="btn btn-outline-secondary btn-sm mt-2">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset Filter
                    </a>
                    @endif
                    <button type="button" class="btn btn-primary btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#createModal">
                        <i class="bi bi-plus-circle"></i> Tambah Dokumentasi
                    </button>
                </div>
            </div>
            @endif
        </div>
        @if($dokumentasis->hasPages())
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mt-3 px-3 pb-3">
            <p class="text-muted small mb-0">
                Menampilkan {{ $dokumentasis->firstItem() ?? 0 }} sampai {{ $dokumentasis->lastItem() ?? 0 }} 
                dari {{ $dokumentasis->total() ?? 0 }} dokumentasi
            </p>
            <nav aria-label="Dokumentasi pagination">
                {{ $dokumentasis->links() }}
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
            <form action="{{ route('admin.dokumentasi.store') }}" method="POST" id="createForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-plus-circle text-primary me-2"></i>Tambah Dokumentasi
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Pelatihan <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-journal-bookmark"></i></span>
                                <select class="form-select" name="training_id" required>
                                    <option value="">Pilih Pelatihan</option>
                                    @if(isset($trainings) && $trainings->count() > 0)
                                        @foreach($trainings as $training)
                                        <option value="{{ $training->id }}" {{ old('training_id') == $training->id ? 'selected' : '' }}>
                                            {{ $training->judul }}
                                        </option>
                                        @endforeach
                                    @else
                                        <option value="" disabled>Tidak ada pelatihan</option>
                                    @endif
                                </select>
                            </div>
                            @error('training_id')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label fw-semibold">Judul <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-text-paragraph"></i></span>
                                <input type="text" class="form-control" name="judul" placeholder="Masukkan judul dokumentasi" value="{{ old('judul') }}" required>
                            </div>
                            @error('judul')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label fw-semibold">Deskripsi</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-file-text"></i></span>
                                <textarea class="form-control" name="deskripsi" rows="2" placeholder="Deskripsi dokumentasi (opsional)">{{ old('deskripsi') }}</textarea>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label fw-semibold">Link <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-link-45deg"></i></span>
                                <input type="url" class="form-control" name="link" placeholder="https://example.com/dokumentasi" value="{{ old('link') }}" required>
                            </div>
                            <small class="text-muted">Masukkan URL lengkap dokumentasi (Google Drive, YouTube, dll).</small>
                            @error('link')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
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
@foreach($dokumentasis ?? [] as $dokumentasi)
<div class="modal fade" id="showModal{{ $dokumentasi->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-eye text-info me-2"></i>Detail Dokumentasi
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="text-muted small fw-semibold">Judul</label>
                        <p class="fw-semibold fs-5">{{ $dokumentasi->judul }}</p>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="text-muted small fw-semibold">Pelatihan</label>
                        <p>{{ $dokumentasi->training->judul ?? '-' }}</p>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="text-muted small fw-semibold">Status</label>
                        <p><span class="badge bg-success"><i class="bi bi-check-circle-fill me-1"></i> Aktif</span></p>
                    </div>
                    @if($dokumentasi->deskripsi)
                    <div class="col-12">
                        <label class="text-muted small fw-semibold">Deskripsi</label>
                        <p>{{ $dokumentasi->deskripsi }}</p>
                    </div>
                    @endif
                    <div class="col-12">
                        <label class="text-muted small fw-semibold">Link</label>
                        <p>
                            <a href="{{ $dokumentasi->link }}" target="_blank" class="text-primary">
                                <i class="bi bi-link-45deg me-1"></i> {{ $dokumentasi->link }}
                            </a>
                        </p>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="text-muted small fw-semibold">Dibuat</label>
                        <p>{{ $dokumentasi->created_at ? $dokumentasi->created_at->format('d/m/Y H:i') : '-' }}</p>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="text-muted small fw-semibold">Diperbarui</label>
                        <p>{{ $dokumentasi->updated_at ? $dokumentasi->updated_at->format('d/m/Y H:i') : '-' }}</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $dokumentasi->id }}" data-bs-dismiss="modal">
                    <i class="bi bi-pencil me-1"></i> Edit
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================
     MODAL EDIT
============================================================ -->
<div class="modal fade" id="editModal{{ $dokumentasi->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.dokumentasi.update', $dokumentasi->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-pencil-square text-warning me-2"></i>Edit Dokumentasi
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Pelatihan <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-journal-bookmark"></i></span>
                                <select class="form-select" name="training_id" required>
                                    <option value="">Pilih Pelatihan</option>
                                    @foreach($trainings ?? [] as $training)
                                    <option value="{{ $training->id }}" {{ $dokumentasi->training_id == $training->id ? 'selected' : '' }}>
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
                                <input type="text" class="form-control" name="judul" value="{{ $dokumentasi->judul }}" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Deskripsi</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-file-text"></i></span>
                                <textarea class="form-control" name="deskripsi" rows="2">{{ $dokumentasi->deskripsi }}</textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Link <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-link-45deg"></i></span>
                                <input type="url" class="form-control" name="link" value="{{ $dokumentasi->link }}" required>
                            </div>
                            <small class="text-muted">Masukkan URL lengkap dokumentasi.</small>
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

<!-- ============================================================
     MODAL DELETE
============================================================ -->
<div class="modal fade" id="deleteModal{{ $dokumentasi->id }}" tabindex="-1" aria-hidden="true">
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
                <p>Apakah Anda yakin ingin menghapus dokumentasi <strong>{{ $dokumentasi->judul }}</strong>?</p>
                <p class="text-muted small">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin.dokumentasi.destroy', $dokumentasi->id) }}" method="POST" class="d-inline">
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

@push('styles')
<style>
    /* ============================================================
       PAGE HEADING
    ============================================================ */
    .page-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        padding: 1.25rem 1.5rem;
        background: #fff;
        border-bottom: 1px solid #f0f0f0;
    }
    .page-heading-copy {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .page-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #eaf1fd, #d4e4f7);
        color: #4e9af1;
        font-size: 1.3rem;
        flex-shrink: 0;
    }
    .eyebrow {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #8a93a3;
        font-weight: 600;
    }

    /* ============================================================
       METRIC CARDS
    ============================================================ */
    .metric-card {
        background: #fff;
        border-radius: 0.75rem;
        padding: 1.1rem 1.25rem;
        box-shadow: 0 1px 4px rgba(0,0,0,.06);
        border-left: 4px solid transparent;
        height: 100%;
        transition: all 0.3s ease;
    }
    .metric-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    }
    .metric-primary { border-left-color: #4e9af1; }
    .metric-success { border-left-color: #28c76f; }
    .metric-warning { border-left-color: #ff9f43; }
    .metric-secondary { border-left-color: #8a93a3; }
    
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

    /* ============================================================
       PANEL
    ============================================================ */
    .panel {
        background: #fff;
        border-radius: .75rem;
        box-shadow: 0 1px 4px rgba(0,0,0,.06);
        overflow: hidden;
    }
    .panel:hover {
        box-shadow: 0 8px 30px rgba(0,0,0,0.06);
    }
    
    .panel-header {
        padding: .9rem 1.25rem;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: .75rem;
        background: #fafbfc;
    }
    
    .section-title {
        display: flex;
        align-items: center;
        gap: .5rem;
        margin: 0;
        font-size: 1rem;
        font-weight: 600;
        color: #1a2236;
    }
    
    .section-title i {
        color: #4e9af1;
    }

    /* ============================================================
       TABLE
    ============================================================ */
    .table th {
        font-weight: 600;
        color: #6c757d;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #f0f0f0;
        padding: 0.75rem 0.75rem;
        background: #fafbfc;
    }
    
    .table td {
        font-size: 0.9rem;
        padding: 0.75rem 0.75rem;
        vertical-align: middle;
    }
    
    .table tbody tr {
        transition: background 0.2s ease;
    }
    .table tbody tr:hover {
        background: #f8fafc;
    }
    
    .table tbody tr:last-child td {
        border-bottom: none;
    }

    /* ============================================================
       BADGE BUTTONS - SAMA DENGAN VIEW MATERI
    ============================================================ */
    .badge {
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        border: none;
        padding: 0.35rem 0.6rem;
        font-size: 0.75rem;
    }
    
    /* Tombol Lihat (Info) */
    .badge.bg-info {
        background: #e3f0ff !important;
        color: #0d6efd !important;
    }
    .badge.bg-info:hover {
        background: #d0e4ff !important;
        transform: scale(1.05);
    }
    
    /* Tombol Edit (Warning) */
    .badge.bg-warning {
        background: #fff3cd !important;
        color: #856404 !important;
    }
    .badge.bg-warning:hover {
        background: #ffedb3 !important;
        transform: scale(1.05);
    }
    
    /* Tombol Buka Link (Success) */
    .badge.bg-success {
        background: #d4edda !important;
        color: #155724 !important;
    }
    .badge.bg-success:hover {
        background: #c3e6cb !important;
        transform: scale(1.05);
    }
    
    /* Tombol Hapus (Danger) */
    .badge.bg-danger {
        background: #f8d7da !important;
        color: #721c24 !important;
    }
    .badge.bg-danger:hover {
        background: #f5c6cb !important;
        transform: scale(1.05);
    }

    /* ============================================================
       FORM
    ============================================================ */
    .form-select-sm,
    .form-control-sm {
        font-size: 0.8rem;
        padding: 0.3rem 0.8rem;
        border-radius: 0.5rem;
        border-color: #e2e8f0;
    }
    .form-select-sm:focus,
    .form-control-sm:focus {
        border-color: #4e9af1;
        box-shadow: 0 0 0 3px rgba(78, 154, 241, 0.15);
    }
    
    .input-group-sm .input-group-text {
        background: #f8fafc;
        border-color: #e2e8f0;
        color: #8a93a3;
        font-size: 0.8rem;
    }

    /* ============================================================
       ALERT
    ============================================================ */
    .alert {
        border-radius: 0.75rem;
        border: none;
        padding: 0.75rem 1rem;
    }
    .alert-success {
        background: #ecfdf5;
        color: #065f46;
    }
    .alert-danger {
        background: #fef2f2;
        color: #991b1b;
    }

    /* ============================================================
       RESPONSIVE
    ============================================================ */
    @media (max-width: 768px) {
        .page-heading {
            flex-direction: column;
            align-items: flex-start;
        }
        .page-heading-copy {
            width: 100%;
        }
        .panel-header {
            flex-direction: column;
            align-items: flex-start;
        }
        .panel-header form {
            width: 100%;
            flex-wrap: wrap;
        }
        .panel-header form input {
            flex: 1;
            min-width: 120px;
        }
        .metric-value {
            font-size: 1.2rem;
        }
        .table-responsive {
            font-size: 0.85rem;
        }
        .table th,
        .table td {
            padding: 0.5rem 0.5rem;
        }
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

    // Search with Enter key
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                this.closest('form').submit();
            }
        });
    }

    // Modal auto focus
    document.querySelectorAll('.modal').forEach(function(modal) {
        modal.addEventListener('shown.bs.modal', function() {
            const firstInput = this.querySelector('input:not([type="hidden"]), select, textarea');
            if (firstInput) {
                firstInput.focus();
            }
        });
    });
});
</script>
@endpush
@endsection