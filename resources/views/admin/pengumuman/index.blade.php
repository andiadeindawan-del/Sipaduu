@extends('layouts.admin')

@section('title', 'Manajemen Pengumuman')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-megaphone"></i></span>
        <div>
            <p class="eyebrow">Manajemen</p>
            <h1 class="h3 mb-0">Pengumuman</h1>
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
                <input type="date" class="form-control form-control-sm" name="date_from" value="{{ request('date_from') }}" style="width: 150px;" placeholder="Dari">
                <input type="date" class="form-control form-control-sm" name="date_to" value="{{ request('date_to') }}" style="width: 150px;" placeholder="Sampai">
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
        @if(request('search') || request('status') || request('date_from') || request('date_to'))
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
                @if(request('date_from'))
                    <span class="badge text-bg-primary">Dari: {{ request('date_from') }}</span>
                @endif
                @if(request('date_to'))
                    <span class="badge text-bg-primary">Sampai: {{ request('date_to') }}</span>
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
            <!-- <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('admin.pengumuman.export') }}" class="btn btn-success btn-sm">
                    <i class="bi bi-download"></i> Export
                </a>
            </div> -->
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
                        <th class="text-end" style="width: 120px;">Aksi</th>
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
                                @if($item->deskripsi)
                                <p class="text-muted small mb-0">{{ Str::limit($item->deskripsi, 60) }}</p>
                                @endif
                                @if($item->training)
                                <span class="badge text-bg-info">{{ $item->training->judul }}</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            @if($item->kategori)
                            <span class="badge" style="background-color: {{ $item->kategori->warna ?? '#6c757d' }}; color: #fff;">
                                <i class="bi {{ $item->kategori->icon ?? 'bi-tag' }} me-1"></i>
                                {{ $item->kategori->nama }}
                            </span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <div class="small">
                                <div>
                                    <i class="bi bi-calendar3 me-1"></i>
                                    {{ $item->tanggal ? $item->tanggal->format('d/m/Y') : '-' }}
                                </div>
                                @if($item->tanggal_selesai)
                                <div>
                                    <i class="bi bi-calendar-x me-1"></i>
                                    {{ $item->tanggal_selesai->format('d/m/Y') }}
                                </div>
                                @endif
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
                                <a href="{{ route('admin.pengumuman.show', $item->id) }}" 
                                   class="btn btn-outline-info" title="Lihat">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.pengumuman.edit', $item->id) }}" 
                                   class="btn btn-outline-warning" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" class="btn btn-outline-danger" 
                                        data-bs-toggle="modal" data-bs-target="#deleteModal{{ $item->id }}" 
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

<!-- Delete Modals -->
@if(isset($pengumumans) && $pengumumans->count() > 0)
@foreach($pengumumans as $item)
<div class="modal fade" id="deleteModal{{ $item->id }}" tabindex="-1" 
     aria-labelledby="deleteModalLabel{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel{{ $item->id }}">
                    <i class="bi bi-exclamation-triangle text-danger me-2"></i>
                    Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus pengumuman ini?</p>
                <div class="alert alert-light">
                    <p class="fw-semibold mb-0">{{ $item->judul }}</p>
                    @if($item->tanggal)
                    <p class="text-muted small mb-0">{{ $item->tanggal->format('d/m/Y') }}</p>
                    @endif
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
    .avatar-text {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.85rem;
    }
    .avatar-img {
        width: 36px;
        height: 36px;
        object-fit: cover;
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

    checkboxes.forEach(cb => {
        cb.addEventListener('change', function() {
            const checked = document.querySelectorAll('.pengumuman-checkbox:checked');
            const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
            if (bulkDeleteBtn) {
                if (checked.length > 0) {
                    bulkDeleteBtn.classList.remove('d-none');
                    bulkDeleteBtn.textContent = '🗑️ Hapus ' + checked.length + ' Terpilih';
                } else {
                    bulkDeleteBtn.classList.add('d-none');
                }
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
});
</script>
@endpush
@endsection