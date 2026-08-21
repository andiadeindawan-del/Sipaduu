@extends('layouts.admin')

@section('title', 'Kelola Sertifikat')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="page-heading">
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
    <section class="row g-3 mt-1" aria-label="Certificate summary">
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
    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
        <i class="bi bi-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
        <i class="bi bi-exclamation-circle me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Certificates Table -->
    <section class="panel mt-3">
        <div class="panel-header">
            <div>
                <h2 class="h5 mb-1 section-title">
                    <i class="bi bi-table" aria-hidden="true"></i>
                    <span>Daftar Sertifikat</span>
                </h2>
                <p class="text-muted mb-0">Kelola semua sertifikat yang telah diterbitkan.</p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <form action="{{ route('admin.sertifikat.index') }}" method="GET" class="d-flex gap-2">
                    <input class="form-control form-control-sm table-search" type="search" 
                           name="search" placeholder="Cari sertifikat..." 
                           aria-label="Search" value="{{ request('search') }}">
                    <button type="submit" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
                <a href="{{ route('admin.sertifikat.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle" aria-hidden="true"></i> Tambah
                </a>
            </div>
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
                        <td class="text-end">
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
                    <p class="small">Mulai dengan menambahkan sertifikat baru</p>
                    <a href="{{ route('admin.sertifikat.create') }}" class="btn btn-primary btn-sm mt-2">
                        <i class="bi bi-plus-circle"></i> Tambah Sertifikat
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

    .table th {
        font-weight: 600;
        font-size: .75rem;
        text-transform: uppercase;
        letter-spacing: .03em;
        color: #6c757d;
        border-bottom-width: 2px;
    }
    .table td {
        vertical-align: middle;
    }
    .table .badge {
        font-weight: 500;
        padding: 0.3rem 0.7rem;
        font-size: .75rem;
    }

    .btn-group .btn {
        padding: 0.2rem 0.5rem;
        font-size: 0.75rem;
        border-radius: 6px;
        transition: all 0.2s ease;
    }
    .btn-group .btn:hover {
        transform: scale(1.1);
    }

    .avatar-img {
        width: 28px;
        height: 28px;
        object-fit: cover;
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