@extends('layouts.admin')

@section('title', 'Kelola Sertifikat')

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <!-- Page Heading -->
    <div class="page-heading mb-4">
        <div class="page-heading-copy">
            <span class="page-icon"><i class="bi bi-award" aria-hidden="true"></i></span>
            <div>
                <p class="eyebrow mb-1">Management</p>
                <h1 class="h3 mb-1">Kelola Sertifikat</h1>
                <p class="text-muted mb-0">Kelola semua sertifikat yang diterbitkan.</p>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <section class="row g-3 mb-4" aria-label="Certificate summary">
        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card metric-primary">
                <div class="metric-top">
                    <span class="metric-label">Total Sertifikat</span>
                    <span class="metric-icon"><i class="bi bi-award" aria-hidden="true"></i></span>
                </div>
                <div class="metric-value">{{ $totalSertifikat ?? $sertifikats->total() ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-success">+8.2%</span>
                    <span>from last month</span>
                </div>
            </article>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card metric-success">
                <div class="metric-top">
                    <span class="metric-label">Aktif</span>
                    <span class="metric-icon"><i class="bi bi-check-circle" aria-hidden="true"></i></span>
                </div>
                <div class="metric-value">{{ $aktifCount ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-success">Active</span>
                    <span>certificates</span>
                </div>
            </article>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card metric-warning">
                <div class="metric-top">
                    <span class="metric-label">Pending</span>
                    <span class="metric-icon"><i class="bi bi-hourglass-split" aria-hidden="true"></i></span>
                </div>
                <div class="metric-value">{{ $pendingCount ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-warning">Pending</span>
                    <span>need review</span>
                </div>
            </article>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card metric-danger">
                <div class="metric-top">
                    <span class="metric-label">Revoked</span>
                    <span class="metric-icon"><i class="bi bi-x-circle" aria-hidden="true"></i></span>
                </div>
                <div class="metric-value">{{ $revokedCount ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-danger">Revoked</span>
                    <span>certificates</span>
                </div>
            </article>
        </div>
    </section>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <i class="bi bi-exclamation-circle-fill me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Certificates Table -->
    <section class="panel">
        <div class="panel-header">
            <!-- Kiri: Search -->
            <form action="{{ route('admin.sertifikat.index') }}" method="GET" class="d-flex gap-2 panel-search">
                <input class="form-control form-control-sm table-search" type="search" 
                       name="search" placeholder="Cari sertifikat..." 
                       aria-label="Search" value="{{ request('search') }}">
                <button type="submit" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-search"></i>
                </button>
                @if(request('search'))
                <a href="{{ route('admin.sertifikat.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-x-circle"></i>
                </a>
                @endif
            </form>

            <!-- Kanan: Tambah Sertifikat -->
            <a href="{{ route('admin.sertifikat.create') }}" class="btn btn-primary btn-sm panel-add-btn">
                <i class="bi bi-plus-circle me-1"></i> Tambah Sertifikat
            </a>
        </div>
        <div class="table-responsive">
            @if(isset($sertifikats) && $sertifikats->count() > 0)
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col" style="width: 50px;">No</th>
                        <th scope="col">Nomor Sertifikat</th>
                        <th scope="col">Nama Sertifikat</th>
                        <th scope="col">Peserta</th>
                        <th scope="col">Tanggal Terbit</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="text-center" style="width: 160px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sertifikats as $index => $sertifikat)
                    <tr>
                        <td>{{ $sertifikats->firstItem() + $index }}</td>
                        <td>
                            <span class="fw-semibold small">{{ $sertifikat->nomor_sertifikat }}</span>
                        </td>
                        <td>{{ $sertifikat->judul ?? $sertifikat->nama_sertifikat }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if($sertifikat->user && $sertifikat->user->foto)
                                <img class="rounded-circle" 
                                     src="{{ asset('storage/' . $sertifikat->user->foto) }}" 
                                     alt="{{ $sertifikat->user->nama }}" 
                                     style="width: 28px; height: 28px; object-fit: cover;">
                                @else
                                <div class="d-flex align-items-center justify-content-center rounded-circle bg-primary text-white" 
                                     style="width: 28px; height: 28px; font-size: 11px; font-weight: 600; flex-shrink: 0;">
                                    {{ strtoupper(substr($sertifikat->user->nama ?? 'U', 0, 1)) }}
                                </div>
                                @endif
                                <span class="small">{{ $sertifikat->user->nama ?? '-' }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="small">{{ $sertifikat->tanggal_terbit ? $sertifikat->tanggal_terbit->format('d/m/Y') : '-' }}</span>
                        </td>
                        <td>
                            @php
                                $statusMap = [
                                    'aktif' => ['label' => '✅ Aktif', 'class' => 'badge text-bg-success'],
                                    'active' => ['label' => '✅ Aktif', 'class' => 'badge text-bg-success'],
                                    'pending' => ['label' => '⏳ Pending', 'class' => 'badge text-bg-warning'],
                                    'revoked' => ['label' => '❌ Revoked', 'class' => 'badge text-bg-danger'],
                                    'expired' => ['label' => '⏰ Expired', 'class' => 'badge text-bg-secondary'],
                                ];
                                $status = $statusMap[$sertifikat->status] ?? ['label' => $sertifikat->status, 'class' => 'badge text-bg-secondary'];
                            @endphp
                            <span class="{{ $status['class'] }}">
                                {{ $status['label'] }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('admin.sertifikat.show', $sertifikat->id) }}" 
                                   class="btn btn-info" title="Lihat">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.sertifikat.edit', $sertifikat->id) }}" 
                                   class="btn btn-warning" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if($sertifikat->file_path)
                                <a href="{{ route('admin.sertifikat.download', $sertifikat->id) }}" 
                                   class="btn btn-success" title="Download">
                                    <i class="bi bi-download"></i>
                                </a>
                                @endif
                                <button type="button" class="btn btn-danger" 
                                        data-bs-toggle="modal" data-bs-target="#deleteModal{{ $sertifikat->id }}" 
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
                    <p class="h5">Belum ada sertifikat</p>
                    <p class="small">Mulai dengan menambahkan sertifikat baru.</p>
                    <a href="{{ route('admin.sertifikat.create') }}" class="btn btn-primary btn-sm mt-2">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Sertifikat
                    </a>
                </div>
            </div>
            @endif
        </div>
        @if(isset($sertifikats) && $sertifikats->hasPages())
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mt-3 px-3 pb-3">
            <p class="text-muted small mb-0">
                Menampilkan {{ $sertifikats->firstItem() ?? 0 }} sampai {{ $sertifikats->lastItem() ?? 0 }} 
                dari {{ $sertifikats->total() ?? 0 }} sertifikat
            </p>
            <nav aria-label="Certificate pagination">
                {{ $sertifikats->links() }}
            </nav>
        </div>
        @endif
    </section>
</div>

<!-- Delete Modals -->
@foreach($sertifikats ?? [] as $sertifikat)
<div class="modal fade" id="deleteModal{{ $sertifikat->id }}" tabindex="-1" 
     aria-labelledby="deleteModalLabel{{ $sertifikat->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel{{ $sertifikat->id }}">
                    <i class="bi bi-exclamation-triangle text-danger me-2"></i>
                    Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus sertifikat <strong>{{ $sertifikat->judul ?? $sertifikat->nama_sertifikat }}</strong>?</p>
                @if($sertifikat->file_path)
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    File sertifikat akan ikut terhapus.
                </div>
                @endif
                <p class="text-muted small">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin.sertifikat.destroy', $sertifikat->id) }}" method="POST" class="d-inline">
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
        border-radius: .75rem;
        box-shadow: 0 1px 4px rgba(0,0,0,.06);
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
    .heading-actions {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
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
        transition: all 0.2s ease;
    }
    .metric-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.08);
    }
    .metric-primary { border-left-color: #4e9af1; }
    .metric-success { border-left-color: #28c76f; }
    .metric-warning { border-left-color: #ff9f43; }
    .metric-danger { border-left-color: #ea5455; }
    
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

    /* Panel Header: Search kiri, Tambah kanan */
    .panel-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: .75rem;
        padding: .9rem 1.25rem;
        border-bottom: 1px solid #f0f0f0;
        background: #fafbfc;
    }

    .panel-search {
        flex: 1 1 auto;
        max-width: 400px;
    }

    .panel-search .form-control {
        min-width: 200px;
    }

    .panel-add-btn {
        flex-shrink: 0;
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
        font-size: .75rem;
        text-transform: uppercase;
        letter-spacing: .03em;
        color: #6c757d;
        border-bottom-width: 2px;
        padding: .75rem 1rem;
        background: #fafbfc;
    }
    .table td {
        vertical-align: middle;
        padding: .75rem 1rem;
        font-size: .875rem;
    }
    .table tbody tr {
        transition: background 0.15s ease;
    }
    .table tbody tr:hover {
        background: #f8fafc;
    }
    .table .badge {
        font-weight: 500;
        padding: 0.35rem 0.75rem;
        font-size: .75rem;
        border-radius: 50px;
    }

    /* ============================================================
       BUTTONS
    ============================================================ */
    .btn {
        border-radius: 0.5rem;
        padding: 0.5rem 1.2rem;
        font-weight: 500;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }
    .btn-primary {
        background: #4e9af1;
        border-color: #4e9af1;
        color: #fff;
    }
    .btn-primary:hover {
        background: #3a7bc8;
        border-color: #3a7bc8;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(78, 154, 241, 0.3);
    }
    .btn-outline-secondary {
        border-color: #e2e8f0;
        color: #4a5568;
    }
    .btn-outline-secondary:hover {
        background: #e2e8f0;
        border-color: #d5dce6;
    }

    .btn-group .btn {
        padding: 0.35rem 0.6rem;
        font-size: 0.8rem;
        border-radius: 6px;
        transition: all 0.2s ease;
    }
    .btn-group .btn:hover {
        transform: scale(1.08);
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
    .alert-warning {
        background: #fffbeb;
        color: #92400e;
        border: 1px solid #fde68a;
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
        .heading-actions {
            width: 100%;
        }
        .heading-actions .btn {
            width: 100%;
        }
        .panel-header {
            flex-direction: column;
            align-items: stretch;
        }
        .panel-search {
            max-width: 100%;
        }
        .panel-search .form-control {
            min-width: 0;
        }
        .panel-add-btn {
            width: 100%;
        }
        .table-responsive {
            font-size: 0.85rem;
        }
        .table th,
        .table td {
            padding: 0.5rem 0.75rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto close alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
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
    });
</script>
@endpush
@endsection