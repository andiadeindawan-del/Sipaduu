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
                    <span class="metric-icon" style="color: #4e9af1;"><i class="bi bi-megaphone"></i></span>
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
                    <span class="metric-icon" style="color: #28c76f;"><i class="bi bi-check-circle"></i></span>
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
                    <span class="metric-icon" style="color: #ff9f43;"><i class="bi bi-pencil"></i></span>
                </div>
                <div class="metric-value">{{ $draftCount ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-warning">Draft</span>
                    <span>perlu review</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-secondary">
                <div class="metric-top">
                    <span class="metric-label">Archived</span>
                    <span class="metric-icon" style="color: #8a93a3;"><i class="bi bi-archive"></i></span>
                </div>
                <div class="metric-value">{{ $archivedCount ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-secondary">Diarsipkan</span>
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
                <a href="{{ route('admin.pengumuman.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle"></i> Tambah
                </a>
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
                        <th>Jenis</th>
                        <th>Pelatihan</th>
                        <th>File</th>
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
                            @if($item->jenis_pengumuman == 'umum')
                                <span class="badge bg-primary">Umum</span>
                            @else
                                <span class="badge bg-info">Peserta</span>
                            @endif
                        </td>
                        <td>
                            @if($item->training)
                                <span class="text-muted">{{ $item->training->judul }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($item->file_path)
                                <a href="{{ route('pengumuman.file', $item->id) }}" target="_blank" class="btn btn-sm btn-outline-primary" title="Lihat File">
                                    <i class="bi bi-file-earmark-text"></i> PDF/Doc
                                </a>
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
                                    'published' => ['label' => '✅ Aktif', 'class' => 'text-bg-success'],
                                    'draft' => ['label' => '📝 Tidak Aktif', 'class' => 'text-bg-secondary'],
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
                                <a href="{{ route('admin.pengumuman.show', $item->id) }}" class="btn btn-info" title="Lihat">
                                    <i class="bi bi-eye"></i> Lihat
                                </a>
                                <a href="{{ route('admin.pengumuman.edit', $item->id) }}" class="btn btn-warning" title="Edit">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form action="{{ route('admin.pengumuman.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengumuman {{ $item->judul }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" title="Hapus">
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
                    <p class="h5">Belum ada pengumuman</p>
                    <p class="small">Mulai dengan menambahkan pengumuman baru</p>
                    <a href="{{ route('admin.pengumuman.create') }}" class="btn btn-primary btn-sm mt-2">
                        <i class="bi bi-plus-circle"></i> Tambah Pengumuman
                    </a>
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
        transform: scale(1.05);
    }
    
    .btn-info {
        background: #e3f0ff !important;
        color: #0d6efd !important;
        border-color: #e3f0ff !important;
    }
    .btn-info:hover {
        background: #d0e4ff !important;
        color: #0d6efd !important;
        border-color: #d0e4ff !important;
    }
    
    .btn-warning {
        background: #fff3cd !important;
        color: #856404 !important;
        border-color: #fff3cd !important;
    }
    .btn-warning:hover {
        background: #ffedb3 !important;
        color: #856404 !important;
        border-color: #ffedb3 !important;
    }
    
    .btn-danger {
        background: #f8d7da !important;
        color: #721c24 !important;
        border-color: #f8d7da !important;
    }
    .btn-danger:hover {
        background: #f5c6cb !important;
        color: #721c24 !important;
        border-color: #f5c6cb !important;
    }

    .modal-lg {
        max-width: 800px;
    }
    
    .btn-outline-primary {
        border-color: #4e9af1;
        color: #4e9af1;
    }
    .btn-outline-primary:hover {
        background: #4e9af1;
        border-color: #4e9af1;
        color: #fff;
    }
    
    .btn-outline-secondary {
        border-color: #e2e8f0;
        color: #4a5568;
    }
    .btn-outline-secondary:hover {
        background: #e2e8f0;
        border-color: #d5dce6;
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