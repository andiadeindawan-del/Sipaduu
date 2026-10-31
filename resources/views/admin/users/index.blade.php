@extends('layouts.admin')

@section('title', 'Manajemen Pengguna (Users)')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-people" aria-hidden="true"></i></span>
        <div>
            <p class="eyebrow mb-1">Manajemen</p>
            <h1 class="h3 mb-1">Daftar Pengguna</h1>
            <p class="text-muted mb-0">Kelola akun administrator, pemateri (instruktur), dan peserta UMK.</p>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <!-- Alert Messages -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-circle-fill me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Stats Grid -->
    <section class="row g-3 mb-4" aria-label="Ringkasan Pengguna">
        <!-- Total Users -->
        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card metric-primary">
                <div class="metric-top">
                    <span class="metric-label">Total Pengguna</span>
                    <span class="metric-icon"><i class="bi bi-people" aria-hidden="true"></i></span>
                </div>
                <div class="metric-value">{{ $totalUsers ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-success"><i class="bi bi-check-circle me-1"></i>{{ $activeUsers ?? 0 }} aktif</span>
                    <span>terdaftar</span>
                </div>
            </article>
        </div>

        <!-- Pemateri -->
        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card" style="border-left: 4px solid #6f42c1;">
                <div class="metric-top">
                    <span class="metric-label" style="color: #6f42c1;">Pemateri</span>
                    <span class="metric-icon" style="color: #6f42c1;"><i class="bi bi-person-video3" aria-hidden="true"></i></span>
                </div>
                <div class="metric-value" style="color: #6f42c1;">{{ $pemateriCount ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="fw-semibold" style="color: #6f42c1;">Instruktur & Pengajar</span>
                    <span>pelatihan</span>
                </div>
            </article>
        </div>

        <!-- Peserta -->
        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card metric-warning" style="border-left: 4px solid #0d6efd;">
                <div class="metric-top">
                    <span class="metric-label text-primary">Peserta UMK</span>
                    <span class="metric-icon text-primary"><i class="bi bi-person-badge" aria-hidden="true"></i></span>
                </div>
                <div class="metric-value text-primary">{{ $pesertaCount ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-primary fw-semibold">Pelaku Usaha</span>
                    <span>UMK terdaftar</span>
                </div>
            </article>
        </div>

        <!-- Admin & Status -->
        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card metric-danger" style="border-left: 4px solid #dc3545;">
                <div class="metric-top">
                    <span class="metric-label text-danger">Administrator</span>
                    <span class="metric-icon text-danger"><i class="bi bi-shield-lock" aria-hidden="true"></i></span>
                </div>
                <div class="metric-value text-danger">{{ $adminCount ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-danger fw-semibold">{{ $inactiveUsers ?? 0 }} nonaktif</span>
                    <span>di sistem</span>
                </div>
            </article>
        </div>
    </section>

    <!-- Users Table Panel -->
    <section class="panel border-0 shadow-sm">
        <div class="panel-header flex-column flex-md-row align-items-start align-items-md-center gap-3">
            <div>
                <h5 class="section-title mb-1">
                    <i class="bi bi-people-fill text-primary" aria-hidden="true"></i>
                    <span>Daftar Pengguna Sistem</span>
                </h5>
                <p class="text-muted small mb-0">Role pemateri dan peserta telah ditandai secara khusus untuk kemudahan identifikasi.</p>
            </div>
            
            <div class="d-flex flex-wrap gap-2 align-items-center w-100 w-md-auto justify-content-md-end">
                <form action="{{ route('admin.users.index') }}" method="GET" class="d-flex gap-2">
                    @if(request('role'))
                        <input type="hidden" name="role" value="{{ request('role') }}">
                    @endif
                    <input class="form-control form-control-sm" type="search" 
                           name="search" placeholder="Cari nama, NIK, email..." 
                           aria-label="Search users" value="{{ request('search') }}" style="width: 220px;">
                    <button type="submit" class="btn btn-outline-secondary btn-sm" title="Cari">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
                
                @if(request('search') || request('role'))
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm" title="Reset Filter">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                </a>
                @endif
                
                <a href="{{ route('admin.users.export') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-download" aria-hidden="true"></i> Export
                </a>
                
                {{-- Tombol Tambah --}}
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-person-plus-fill me-1" aria-hidden="true"></i> Tambah User
                </a>
            </div>
        </div>

        <!-- Filter Tab Pills Berdasarkan Role -->
        <div class="px-3 pt-3 pb-2 border-bottom bg-light">
            <div class="d-flex flex-wrap gap-2 align-items-center">
                <span class="small fw-bold text-muted text-uppercase me-2" style="font-size: 0.72rem;">
                    <i class="bi bi-funnel me-1"></i> Filter Role:
                </span>

                <!-- Semua -->
                <a href="{{ route('admin.users.index', array_filter(['search' => request('search')])) }}" 
                   class="btn btn-sm {{ !request('role') ? 'btn-dark' : 'btn-outline-secondary' }}" 
                   style="border-radius: 20px; font-size: 0.8rem; padding: 4px 14px;">
                   Semua ({{ $totalUsers }})
                </a>

                <!-- Pemateri (Ungu Khas) -->
                <a href="{{ route('admin.users.index', array_filter(['role' => 'pemateri', 'search' => request('search')])) }}" 
                   class="btn btn-sm" 
                   style="border-radius: 20px; font-size: 0.8rem; padding: 4px 14px; {{ request('role') === 'pemateri' ? 'background-color: #6f42c1; color: #fff; border-color: #6f42c1;' : 'color: #6f42c1; border: 1px solid #6f42c1; background: #fff;' }}">
                   <i class="bi bi-person-video3 me-1"></i> Pemateri ({{ $pemateriCount }})
                </a>

                <!-- Peserta (Biru Khas) -->
                <a href="{{ route('admin.users.index', array_filter(['role' => 'peserta', 'search' => request('search')])) }}" 
                   class="btn btn-sm {{ request('role') === 'peserta' ? 'btn-primary' : 'btn-outline-primary' }}" 
                   style="border-radius: 20px; font-size: 0.8rem; padding: 4px 14px; {{ request('role') !== 'peserta' ? 'background: #fff;' : '' }}">
                   <i class="bi bi-person-badge me-1"></i> Peserta UMK ({{ $pesertaCount }})
                </a>

                <!-- Admin (Merah) -->
                <a href="{{ route('admin.users.index', array_filter(['role' => 'admin', 'search' => request('search')])) }}" 
                   class="btn btn-sm {{ request('role') === 'admin' ? 'btn-danger' : 'btn-outline-danger' }}" 
                   style="border-radius: 20px; font-size: 0.8rem; padding: 4px 14px; {{ request('role') !== 'admin' ? 'background: #fff;' : '' }}">
                   <i class="bi bi-shield-lock me-1"></i> Admin ({{ $adminCount }})
                </a>
            </div>
        </div>

        <div class="table-responsive">
            @if(isset($users) && $users->count() > 0)
            <table class="table align-middle table-hover mb-0" id="usersTable">
                <thead>
                    <tr class="bg-light">
                        <th scope="col" style="min-width: 260px;">User & NIK</th>
                        <th scope="col" style="min-width: 140px;">Role Pengguna</th>
                        <th scope="col" style="min-width: 160px;">Kontak & Instansi</th>
                        <th scope="col" style="min-width: 110px;">Status Akun</th>
                        <th scope="col" style="min-width: 120px;">Bergabung</th>
                        <th scope="col" class="text-center" style="width: 160px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users ?? [] as $user)
                    <tr @if($user->role === 'pemateri') style="background-color: rgba(111, 66, 193, 0.02);" @endif>
                        <!-- User & NIK -->
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                @if($user->foto)
                                <img class="avatar-img rounded-circle shadow-sm" src="{{ asset('storage/' . $user->foto) }}" 
                                     alt="{{ $user->nama }}" 
                                     style="width: 42px; height: 42px; object-fit: cover; border: 2px solid {{ $user->role === 'pemateri' ? '#6f42c1' : ($user->role === 'admin' ? '#dc3545' : '#0d6efd') }};">
                                @else
                                <div class="avatar-img rounded-circle text-white d-flex align-items-center justify-content-center shadow-sm" 
                                     style="width: 42px; height: 42px; font-weight: 700; font-size: 0.85rem; background: {{ $user->role === 'pemateri' ? 'linear-gradient(135deg, #6f42c1, #432874)' : ($user->role === 'admin' ? 'linear-gradient(135deg, #dc3545, #841520)' : 'linear-gradient(135deg, #0d6efd, #0b5ed7)') }}; border: 2px solid {{ $user->role === 'pemateri' ? '#6f42c1' : ($user->role === 'admin' ? '#dc3545' : '#0d6efd') }};">
                                    {{ strtoupper(substr($user->nama ?? $user->name ?? 'U', 0, 2)) }}
                                </div>
                                @endif
                                <div>
                                    <div class="d-flex align-items-center gap-2">
                                        <p class="fw-bold mb-0 text-dark">{{ $user->nama ?? $user->name ?? 'Unknown' }}</p>
                                        @if($user->role === 'pemateri')
                                            <span class="badge" style="background-color: #6f42c1; color: #fff; font-size: 0.65rem; padding: 2px 6px;">Pemateri</span>
                                        @endif
                                    </div>
                                    <div class="small text-muted font-monospace mt-0.5">
                                        <i class="bi bi-card-heading text-primary me-1"></i>{{ $user->nik ?? '-' }}
                                    </div>
                                    <div class="small text-muted text-truncate" style="max-width: 220px;">
                                        <i class="bi bi-envelope me-1"></i>{{ $user->email }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- Role Pengguna (Ditandai Khusus) -->
                        <td>
                            @if($user->role === 'pemateri')
                                <span class="badge shadow-sm" style="background-color: #6f42c1; color: #fff; font-size: 0.8rem; padding: 6px 14px; border-radius: 20px;">
                                    <i class="bi bi-person-video3 me-1"></i> Pemateri
                                </span>
                            @elseif($user->role === 'admin')
                                <span class="badge bg-danger text-white shadow-sm" style="font-size: 0.8rem; padding: 6px 14px; border-radius: 20px;">
                                    <i class="bi bi-shield-lock me-1"></i> Admin
                                </span>
                            @else
                                <span class="badge bg-primary text-white shadow-sm" style="font-size: 0.8rem; padding: 6px 14px; border-radius: 20px;">
                                    <i class="bi bi-person-badge me-1"></i> Peserta UMK
                                </span>
                            @endif
                        </td>

                        <!-- Kontak & Instansi -->
                        <td>
                            <div class="small text-dark fw-semibold">
                                <i class="bi bi-telephone text-success me-1"></i>{{ $user->no_telepon ?? '-' }}
                            </div>
                            <div class="small text-muted text-truncate" style="max-width: 180px;">
                                <i class="bi bi-building text-secondary me-1"></i>{{ $user->departemen ?? ($user->nama_usaha ?? '-') }}
                            </div>
                        </td>

                        <!-- Status Akun -->
                        <td>
                            @if(($user->status ?? 'aktif') === 'aktif')
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1" style="border-radius: 20px;">
                                    <i class="bi bi-check-circle-fill me-1" style="font-size: 8px;"></i> Aktif
                                </span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary border px-2.5 py-1" style="border-radius: 20px;">
                                    <i class="bi bi-x-circle me-1" style="font-size: 8px;"></i> Nonaktif
                                </span>
                            @endif
                        </td>

                        <!-- Bergabung -->
                        <td>
                            <span class="small text-muted">
                                <i class="bi bi-calendar-event me-1"></i>{{ $user->created_at ? $user->created_at->format('d/m/Y') : '-' }}
                            </span>
                        </td>

                        <!-- Aksi -->
                        <td class="text-center">
                            <div class="d-flex gap-1 justify-content-center">
                                {{-- Link Show --}}
                                <a href="{{ route('admin.users.show', $user->id) }}" 
                                   class="btn btn-outline-info btn-sm p-1.5" 
                                   title="Lihat Detail Profil">
                                    <i class="bi bi-eye"></i>
                                </a>
                                
                                {{-- Link Edit --}}
                                <a href="{{ route('admin.users.edit', $user->id) }}" 
                                   class="btn btn-outline-warning btn-sm p-1.5" 
                                   title="Edit Profil User">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                
                                {{-- Tombol Delete dengan form --}}
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user {{ $user->nama ?? $user->name ?? 'Unknown' }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm p-1.5" title="Hapus">
                                        <i class="bi bi-trash"></i>
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
                        @if(request('search') || request('role'))
                            Tidak ada user untuk filter pencarian ini
                        @else
                            Belum ada user
                        @endif
                    </p>
                    <p class="small">
                        @if(request('search') || request('role'))
                            Coba sesuaikan pencarian atau klik tombol reset filter
                        @else
                            Mulai dengan menambahkan user pertama
                        @endif
                    </p>
                    @if(request('search') || request('role'))
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm mt-2">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset Filter
                    </a>
                    @endif
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm mt-2">
                        <i class="bi bi-person-plus"></i> Tambah User Baru
                    </a>
                </div>
            </div>
            @endif
        </div>

        @if(isset($users) && $users->hasPages())
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 p-3 border-top">
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
        background: #fff;
    }
    .metric-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    }
    .panel {
        border-radius: 1rem;
        background: #fff;
        overflow: hidden;
    }
    .panel-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #f0f0f0;
    }
    .section-title {
        display: flex;
        align-items: center;
        gap: .5rem;
        margin: 0;
        font-size: 1.05rem;
        font-weight: 700;
    }
    .table th {
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6c757d;
        border-bottom: 2px solid #f0f0f0;
        padding-top: 12px;
        padding-bottom: 12px;
    }
    .table td {
        padding-top: 14px;
        padding-bottom: 14px;
    }
    nav[aria-label="User pagination"] svg {
        width: 1.25rem;
        height: 1.25rem;
    }
    nav[aria-label="User pagination"] p {
        margin-bottom: 0;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
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
