@extends('layouts.admin')

@section('title', 'Users Management')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-people" aria-hidden="true"></i></span>
        <div>
            <p class="eyebrow mb-1">Management</p>
            <h1 class="h3 mb-1">Users</h1>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <!-- Stats Grid -->
    <section class="row g-3 mb-4" aria-label="User summary">
        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card metric-primary">
                <div class="metric-top">
                    <span class="metric-label">Total Users</span>
                    <span class="metric-icon"><i class="bi bi-people" aria-hidden="true"></i></span>
                </div>
                <div class="metric-value">{{ $totalUsers ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-success">+5.1%</span>
                    <span>bulan ini</span>
                </div>
            </article>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card metric-success">
                <div class="metric-top">
                    <span class="metric-label">Aktif</span>
                    <span class="metric-icon"><i class="bi bi-check2-circle" aria-hidden="true"></i></span>
                </div>
                <div class="metric-value">{{ $activeUsers ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-success">{{ $totalUsers > 0 ? round(($activeUsers/$totalUsers)*100) : 0 }}%</span>
                    <span>akun sehat</span>
                </div>
            </article>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card metric-warning">
                <div class="metric-top">
                    <span class="metric-label">Trainers</span>
                    <span class="metric-icon"><i class="bi bi-person-badge" aria-hidden="true"></i></span>
                </div>
                <div class="metric-value">{{ $trainerCount ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-warning">trainer aktif</span>
                    <span>di sistem</span>
                </div>
            </article>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card metric-danger">
                <div class="metric-top">
                    <span class="metric-label">Tidak Aktif</span>
                    <span class="metric-icon"><i class="bi bi-slash-circle" aria-hidden="true"></i></span>
                </div>
                <div class="metric-value">{{ $inactiveUsers ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-danger">{{ $inactiveUsers ?? 0 }}</span>
                    <span>akun tidak aktif</span>
                </div>
            </article>
        </div>
    </section>

    <!-- Users Table -->
    <section class="panel">
        <div class="panel-header">
            <div>
                <h5 class="section-title">
                    <i class="bi bi-table" aria-hidden="true"></i>
                    <span>Daftar Users</span>
                </h5>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <form action="{{ route('admin.users.index') }}" method="GET" class="d-flex gap-2">
                    <input class="form-control form-control-sm" type="search" 
                           name="search" placeholder="Cari users..." 
                           aria-label="Search users" value="{{ request('search') }}" style="width: 200px;">
                    <button type="submit" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
                
                @if(request('search'))
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm" title="Reset Filter">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                </a>
                @endif
                
                <a href="{{ route('admin.users.export') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-download" aria-hidden="true"></i> Export
                </a>
                
                {{-- Tombol Tambah --}}
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-person-plus" aria-hidden="true"></i> Tambah User
                </a>
            </div>
        </div>
        <div class="table-responsive">
            @if(isset($users) && $users->count() > 0)
            <table class="table align-middle mb-0" id="usersTable">
                <thead>
                    <tr>
                        <th scope="col">User</th>
                        <th scope="col">Role</th>
                        <th scope="col">Departemen</th>
                        <th scope="col">Status</th>
                        <th scope="col">Bergabung</th>
                        <th scope="col" class="text-end" style="width: 160px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users ?? [] as $user)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if($user->foto)
                                <img class="avatar-img avatar-sm" src="{{ asset('storage/' . $user->foto) }}" alt="{{ $user->nama }}" style="width: 36px; height: 36px; object-fit: cover; border-radius: 50%;">
                                @else
                                <div class="avatar-img avatar-sm bg-primary text-white d-flex align-items-center justify-content-center rounded-circle" style="width: 36px; height: 36px; font-weight: 600; font-size: 0.75rem;">
                                    {{ strtoupper(substr($user->nama ?? $user->name ?? 'U', 0, 2)) }}
                                </div>
                                @endif
                                <div>
                                    <p class="fw-semibold mb-0">{{ $user->nama ?? $user->name ?? 'Unknown' }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            {{ ucfirst($user->role ?? 'User') }}
                        </td>
                        <td>{{ $user->departemen ?? '-' }}</td>
                        <td>
                            @if(($user->status ?? 'aktif') == 'aktif')
                            <span class="text-success">Aktif</span>
                            @else
                            <span class="text-muted">Tidak Aktif</span>
                            @endif
                        </td>
                        <td>{{ $user->created_at ? $user->created_at->format('d/m/Y') : '-' }}</td>
                        <td class="text-end">
                            <div class="d-flex gap-1 justify-content-end">
                                {{-- Link Show --}}
                                <a href="{{ route('admin.users.show', $user->id) }}" class="badge bg-info text-white border-0 p-2 text-decoration-none" title="Lihat">
                                    <i class="bi bi-eye"></i> Lihat
                                </a>
                                
                                {{-- Link Edit --}}
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="badge bg-warning text-dark border-0 p-2 text-decoration-none" title="Edit">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                
                                {{-- Tombol Delete dengan form --}}
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user {{ $user->nama ?? $user->name ?? 'Unknown' }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="badge bg-danger text-white border-0 p-2" title="Hapus">
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
                    <p class="h5">
                        @if(request('search'))
                            Tidak ada user untuk pencarian "{{ request('search') }}"
                        @else
                            Belum ada user
                        @endif
                    </p>
                    <p class="small">
                        @if(request('search'))
                            Coba sesuaikan pencarian atau reset filter
                        @else
                            Mulai dengan menambahkan user pertama
                        @endif
                    </p>
                    @if(request('search'))
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm mt-2">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset Filter
                    </a>
                    @endif
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm mt-2">
                        <i class="bi bi-person-plus"></i> Tambah User
                    </a>
                </div>
            </div>
            @endif
        </div>
        @if(isset($users) && $users->hasPages())
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mt-3 px-3 pb-3">
            <p class="text-muted small mb-0">
                Menampilkan {{ $users->firstItem() ?? 0 }} sampai {{ $users->lastItem() ?? 0 }} 
                dari {{ $users->total() ?? 0 }} user
            </p>
            <nav aria-label="User pagination">
                {{ $users->appends(request()->query())->links() }}
            </nav>
        </div>
        @endif
    </section>
</div>

@push('styles')
<style>
    .metric-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 1rem;
        border: 1px solid rgba(0,0,0,0.04);
    }
    .metric-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    }
    .panel {
        border-radius: 1rem;
        border: 1px solid rgba(0,0,0,0.04);
    }
    .panel-header {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #f0f0f0;
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
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6c757d;
        border-bottom: 2px solid #f0f0f0;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ============================================================
    // AUTO CLOSE ALERTS
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