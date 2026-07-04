@extends('layouts.admin')

@section('title', 'Users Management')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon"><i class="bi bi-people" aria-hidden="true"></i></span>
            <div>
                <p class="eyebrow mb-1">Management</p>
                <h1 class="h3 mb-1">Users</h1>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <section class="row g-3 mt-1" aria-label="User summary">
        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card metric-primary">
                <div class="metric-top">
                    <span class="metric-label">Total Users</span>
                    <span class="metric-icon"><i class="bi bi-people" aria-hidden="true"></i></span>
                </div>
                <div class="metric-value">{{ $totalUsers ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-success">+5.1%</span>
                    <span>this month</span>
                </div>
            </article>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card metric-success">
                <div class="metric-top">
                    <span class="metric-label">Active</span>
                    <span class="metric-icon"><i class="bi bi-check2-circle" aria-hidden="true"></i></span>
                </div>
                <div class="metric-value">{{ $activeUsers ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-success">{{ $totalUsers > 0 ? round(($activeUsers/$totalUsers)*100) : 0 }}%</span>
                    <span>healthy accounts</span>
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
                    <span class="text-warning">active trainers</span>
                    <span>in the system</span>
                </div>
            </article>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card metric-danger">
                <div class="metric-top">
                    <span class="metric-label">Inactive</span>
                    <span class="metric-icon"><i class="bi bi-slash-circle" aria-hidden="true"></i></span>
                </div>
                <div class="metric-value">{{ $inactiveUsers ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-danger">{{ $inactiveUsers ?? 0 }}</span>
                    <span>inactive accounts</span>
                </div>
            </article>
        </div>
    </section>

    <!-- Users Table -->
    <section class="panel mt-3">
        <div class="panel-header">
            <div>
                <h2 class="h5 mb-1 section-title">
                    <i class="bi bi-table" aria-hidden="true"></i>
                    <span>User List</span>
                </h2>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <form action="{{ route('admin.users.index') }}" method="GET" class="d-flex gap-2">
                    <input class="form-control form-control-sm table-search" type="search" 
                           name="search" placeholder="Search users" 
                           aria-label="Search users" value="{{ request('search') }}">
                    <button type="submit" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
                
                {{-- TOMBOL RESET FILTER --}}
                @if(request('search'))
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm" title="Reset Filter">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                </a>
                @endif
                
                <a href="{{ route('admin.users.export') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-download" aria-hidden="true"></i> Export
                </a>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="bi bi-person-plus" aria-hidden="true"></i> Add User
                </button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0" id="usersTable">
                <thead>
                    <tr>
                        <th scope="col">User</th>
                        <th scope="col">Role</th>
                        <th scope="col">Departemen</th>
                        <th scope="col">Status</th>
                        <th scope="col">Joined</th>
                        <th scope="col" class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users ?? [] as $user)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if($user->foto)
                                <img class="avatar-img avatar-sm" src="{{ asset('storage/' . $user->foto) }}" alt="{{ $user->nama }}">
                                @else
                                <div class="avatar-img avatar-sm bg-primary text-white d-flex align-items-center justify-content-center rounded-circle">
                                    {{ strtoupper(substr($user->nama ?? 'U', 0, 2)) }}
                                </div>
                                @endif
                                <div>
                                    <p class="fw-semibold mb-0">{{ $user->nama ?? $user->name ?? 'Unknown' }}</p>
                                    <p class="text-muted small mb-0">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge {{ $user->role == 'admin' ? 'text-bg-danger' : ($user->role == 'trainer' ? 'text-bg-info' : 'text-bg-secondary') }}">
                                {{ ucfirst($user->role ?? 'User') }}
                            </span>
                        </td>
                        <td>{{ $user->departemen ?? '-' }}</td>
                        <td>
                            @if(($user->status ?? 'aktif') == 'aktif')
                            <span class="badge text-bg-success">
                                <i class="bi bi-circle-fill me-1" style="font-size: 6px;"></i> Active
                            </span>
                            @else
                            <span class="badge text-bg-secondary">
                                <i class="bi bi-circle-fill me-1" style="font-size: 6px;"></i> Inactive
                            </span>
                            @endif
                        </td>
                        <td>{{ $user->created_at ? $user->created_at->format('M d, Y') : '-' }}</td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm" role="group" aria-label="User actions">
                                <button type="button" class="btn btn-info" 
                                        data-bs-toggle="modal" data-bs-target="#showModal{{ $user->id }}" 
                                        title="View User">
                                    <i class="bi bi-eye"></i> Lihat
                                </button>
                                <button type="button" class="btn btn-warning" 
                                        data-bs-toggle="modal" data-bs-target="#editModal{{ $user->id }}" 
                                        title="Edit User">
                                    <i class="bi bi-pencil"></i> Edit
                                </button>
                                <button type="button" class="btn btn-danger" 
                                        data-bs-toggle="modal" data-bs-target="#deleteModal{{ $user->id }}" 
                                        title="Delete User">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                <p class="h5">
                                    @if(request('search'))
                                        No users found for "{{ request('search') }}"
                                    @else
                                        No users found
                                    @endif
                                </p>
                                <p class="small">
                                    @if(request('search'))
                                        Try adjusting your search or reset the filter
                                    @else
                                        Start by adding your first user
                                    @endif
                                </p>
                                @if(request('search'))
                                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm mt-2">
                                    <i class="bi bi-arrow-counterclockwise"></i> Reset Filter
                                </a>
                                @endif
                                <button type="button" class="btn btn-primary btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#createModal">
                                    <i class="bi bi-person-plus"></i> Add User
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(isset($users) && $users->hasPages())
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mt-3">
            <p class="text-muted small mb-0">
                Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() ?? 0 }} users
            </p>
            {{ $users->links() }}
        </div>
        @endif
    </section>
</div>

<!-- ============================================================
     CREATE MODAL
============================================================ -->
<div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-person-plus text-primary me-2"></i>Tambah User
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control" name="nama" placeholder="Masukkan nama lengkap" required>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" class="form-control" name="email" placeholder="Masukkan email" required>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" class="form-control" name="password" placeholder="Minimal 8 karakter" required>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Konfirmasi Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                <input type="password" class="form-control" name="password_confirmation" placeholder="Konfirmasi password" required>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Role <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-tag"></i></span>
                                <select class="form-select" name="role" required>
                                    <option value="peserta">Peserta</option>
                                    <option value="trainer">Trainer</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Departemen</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-building"></i></span>
                                <input type="text" class="form-control" name="departemen" placeholder="Departemen (opsional)">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Foto</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-image"></i></span>
                                <input type="file" class="form-control" name="foto" accept=".jpg,.jpeg,.png,.gif">
                            </div>
                            <small class="text-muted">Format: JPG, PNG, GIF. Maksimal 2MB.</small>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-toggle-on"></i></span>
                                <select class="form-select" name="status" required>
                                    <option value="aktif">Aktif</option>
                                    <option value="tidak_aktif">Tidak Aktif</option>
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
     EDIT MODALS
============================================================ -->
@foreach($users ?? [] as $user)
<div class="modal fade" id="editModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-pencil-square text-warning me-2"></i>Edit User
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control" name="nama" value="{{ $user->nama ?? $user->name }}" required>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" class="form-control" name="email" value="{{ $user->email }}" required>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" class="form-control" name="password" placeholder="Kosongkan jika tidak diubah">
                            </div>
                            <small class="text-muted">Kosongkan jika tidak ingin mengubah password.</small>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Konfirmasi Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                <input type="password" class="form-control" name="password_confirmation" placeholder="Konfirmasi password">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Role <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-tag"></i></span>
                                <select class="form-select" name="role" required>
                                    <option value="peserta" {{ ($user->role ?? '') == 'peserta' ? 'selected' : '' }}>Peserta</option>
                                    <option value="trainer" {{ ($user->role ?? '') == 'trainer' ? 'selected' : '' }}>Trainer</option>
                                    <option value="admin" {{ ($user->role ?? '') == 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Departemen</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-building"></i></span>
                                <input type="text" class="form-control" name="departemen" value="{{ $user->departemen ?? '' }}" placeholder="Departemen (opsional)">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Foto</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-image"></i></span>
                                <input type="file" class="form-control" name="foto" accept=".jpg,.jpeg,.png,.gif">
                            </div>
                            <small class="text-muted">Upload foto baru untuk mengganti.</small>
                            @if($user->foto)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $user->foto) }}" alt="Foto" style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;">
                                <span class="text-muted small">Foto saat ini</span>
                            </div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-toggle-on"></i></span>
                                <select class="form-select" name="status" required>
                                    <option value="aktif" {{ ($user->status ?? 'aktif') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="tidak_aktif" {{ ($user->status ?? 'aktif') == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <hr>
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label class="text-muted small fw-semibold">Dibuat</label>
                                    <p class="fw-semibold mb-0">{{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : '-' }}</p>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="text-muted small fw-semibold">Diperbarui</label>
                                    <p class="fw-semibold mb-0">{{ $user->updated_at ? $user->updated_at->format('d/m/Y H:i') : '-' }}</p>
                                </div>
                            </div>
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
@endforeach

<!-- ============================================================
     SHOW MODALS
============================================================ -->
@foreach($users ?? [] as $user)
<div class="modal fade" id="showModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-eye text-info me-2"></i>Detail User
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-12 text-center mb-3">
                        @if($user->foto)
                        <img src="{{ asset('storage/' . $user->foto) }}" alt="{{ $user->nama }}" 
                             style="width: 120px; height: 120px; object-fit: cover; border-radius: 50%; border: 4px solid #4e9af1;">
                        @else
                        <div class="avatar-circle mx-auto" style="width: 120px; height: 120px; border-radius: 50%; background: #4e9af1; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 3rem; font-weight: 700;">
                            {{ strtoupper(substr($user->nama ?? 'U', 0, 2)) }}
                        </div>
                        @endif
                        <h4 class="mt-3 mb-1">{{ $user->nama ?? $user->name }}</h4>
                        <span class="badge {{ $user->role == 'admin' ? 'text-bg-danger' : ($user->role == 'trainer' ? 'text-bg-info' : 'text-bg-secondary') }}">
                            {{ ucfirst($user->role ?? 'User') }}
                        </span>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="text-muted small fw-semibold">Email</label>
                        <p class="fw-semibold">{{ $user->email }}</p>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="text-muted small fw-semibold">Departemen</label>
                        <p class="fw-semibold">{{ $user->departemen ?? '-' }}</p>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="text-muted small fw-semibold">Status</label>
                        <p>
                            @if(($user->status ?? 'aktif') == 'aktif')
                            <span class="badge text-bg-success">Active</span>
                            @else
                            <span class="badge text-bg-secondary">Inactive</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="text-muted small fw-semibold">Bergabung</label>
                        <p class="fw-semibold">{{ $user->created_at ? $user->created_at->format('d F Y H:i') : '-' }}</p>
                    </div>
                    <div class="col-12">
                        <label class="text-muted small fw-semibold">Terakhir Diperbarui</label>
                        <p class="fw-semibold">{{ $user->updated_at ? $user->updated_at->format('d F Y H:i') : '-' }}</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $user->id }}" data-bs-dismiss="modal">
                    <i class="bi bi-pencil"></i> Edit
                </button>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- ============================================================
     DELETE MODALS
============================================================ -->
@foreach($users ?? [] as $user)
<div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $user->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel{{ $user->id }}">
                    <i class="bi bi-exclamation-triangle text-danger me-2"></i>
                    Confirm Delete
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus user <strong>{{ $user->nama ?? $user->name ?? 'Unknown' }}</strong>?</p>
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Menghapus user akan menghapus semua data terkait (pelatihan, quiz, sertifikat, dll).
                </div>
                <p class="text-muted small">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline">
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
    .avatar-circle {
        transition: transform 0.3s ease;
    }
    .avatar-circle:hover {
        transform: scale(1.05);
    }
    .metric-card {
        background: #fff;
        border-radius: .75rem;
        padding: 1.1rem 1.25rem;
        box-shadow: 0 1px 4px rgba(0,0,0,.06);
        border-left: 4px solid transparent;
        height: 100%;
    }
    .metric-primary { border-left-color: #4e9af1; }
    .metric-success { border-left-color: #28c76f; }
    .metric-warning { border-left-color: #ff9f43; }
    .metric-danger  { border-left-color: #ea5455; }
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
    .badge i {
        margin-right: 2px;
    }
    .btn-group .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
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

    // ============================================================
    // PASSWORD VALIDATION
    // ============================================================
    const createForm = document.querySelector('#createModal form');
    if (createForm) {
        createForm.addEventListener('submit', function(e) {
            const password = this.querySelector('input[name="password"]');
            const passwordConfirmation = this.querySelector('input[name="password_confirmation"]');
            
            if (password && passwordConfirmation) {
                if (password.value !== passwordConfirmation.value) {
                    e.preventDefault();
                    alert('Password dan konfirmasi password tidak sama!');
                    passwordConfirmation.focus();
                }
                if (password.value.length < 8 && password.value.length > 0) {
                    e.preventDefault();
                    alert('Password minimal 8 karakter!');
                    password.focus();
                }
            }
        });
    }

    // ============================================================
    // PASSWORD VALIDATION FOR EDIT FORM
    // ============================================================
    document.querySelectorAll('#editModal form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            const password = this.querySelector('input[name="password"]');
            const passwordConfirmation = this.querySelector('input[name="password_confirmation"]');
            
            if (password && passwordConfirmation) {
                // Jika password diisi, harus sama dengan konfirmasi
                if (password.value.length > 0 && password.value !== passwordConfirmation.value) {
                    e.preventDefault();
                    alert('Password dan konfirmasi password tidak sama!');
                    passwordConfirmation.focus();
                }
                if (password.value.length > 0 && password.value.length < 8) {
                    e.preventDefault();
                    alert('Password minimal 8 karakter!');
                    password.focus();
                }
            }
        });
    });
});
</script>
@endpush
@endsection