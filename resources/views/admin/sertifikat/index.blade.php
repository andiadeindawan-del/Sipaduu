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
        <div class="heading-actions">
            <a href="{{ route('admin.sertifikat.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle" aria-hidden="true"></i> Tambah Sertifikat
            </a>
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
                        <th scope="col">Nomor Sertifikat</th>
                        <th scope="col">Nama Sertifikat</th>
                        <th scope="col">Peserta</th>
                        <th scope="col">Tanggal Terbit</th>
                        <th scope="col">Penerbit</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sertifikats as $sertifikat)
                    <tr>
                        <td>
                            <span class="fw-semibold">{{ $sertifikat->nomor_sertifikat }}</span>
                        </td>
                        <td>{{ $sertifikat->nama_sertifikat }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if($sertifikat->user->foto)
                                <img class="avatar-img avatar-xs rounded-circle" src="{{ asset('storage/' . $sertifikat->user->foto) }}" alt="{{ $sertifikat->user->nama }}">
                                @else
                                <div class="avatar-img avatar-xs bg-primary text-white d-flex align-items-center justify-content-center rounded-circle">
                                    {{ strtoupper(substr($sertifikat->user->nama ?? 'U', 0, 1)) }}
                                </div>
                                @endif
                                <span>{{ $sertifikat->user->nama }}</span>
                            </div>
                        </td>
                        <td>{{ $sertifikat->tanggal_terbit ? $sertifikat->tanggal_terbit->format('d/m/Y') : '-' }}</td>
                        <td>{{ $sertifikat->penerbit ?? '-' }}</td>
                        <td>
                            @if($sertifikat->status === 'aktif')
                            <span class="badge text-bg-success">
                                <i class="bi bi-circle-fill me-1" style="font-size: 6px;"></i> Aktif
                            </span>
                            @elseif($sertifikat->status === 'revoked')
                            <span class="badge text-bg-danger">
                                <i class="bi bi-circle-fill me-1" style="font-size: 6px;"></i> Revoked
                            </span>
                            @else
                            <span class="badge text-bg-warning">
                                <i class="bi bi-circle-fill me-1" style="font-size: 6px;"></i> Pending
                            </span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="d-flex gap-1 justify-content-end">
                                <a href="{{ route('admin.sertifikat.show', $sertifikat->id) }}" 
                                   class="badge bg-info text-white text-decoration-none p-2" title="Lihat">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.sertifikat.edit', $sertifikat->id) }}" 
                                   class="badge bg-warning text-dark text-decoration-none p-2" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if($sertifikat->file_path)
                                <a href="{{ route('admin.sertifikat.download', $sertifikat->id) }}" 
                                   class="badge bg-success text-white text-decoration-none p-2" title="Download">
                                    <i class="bi bi-download"></i>
                                </a>
                                @endif
                                <button type="button" class="badge bg-danger text-white border-0 p-2" 
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
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mt-3">
            <p class="text-muted small mb-0">
                Menampilkan {{ $sertifikats->firstItem() ?? 0 }} sampai {{ $sertifikats->lastItem() ?? 0 }} dari {{ $sertifikats->total() ?? 0 }} sertifikat
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
                <p>Apakah Anda yakin ingin menghapus sertifikat <strong>{{ $sertifikat->nama_sertifikat }}</strong>?</p>
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto close alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                alert.classList.remove('show');
                setTimeout(function() {
                    alert.remove();
                }, 300);
            });
        }, 5000);
    });
</script>
@endpush
@endsection