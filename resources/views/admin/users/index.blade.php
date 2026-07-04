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
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-person-plus" aria-hidden="true"></i> Add User
            </a>
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
                        <div class="d-flex gap-1 justify-content-end">
                            <a href="{{ route('admin.users.show', $user->id) }}" class="badge bg-info text-white text-decoration-none p-2" title="View">
                                <i class="bi bi-eye"></i> Lihat
                            </a>
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="badge bg-warning text-dark text-decoration-none p-2" title="Edit">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <button type="button" class="badge bg-danger text-white border-0 p-2" 
                                    data-bs-toggle="modal" data-bs-target="#deleteModal{{ $user->id }}" 
                                    title="Delete">
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
                            <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm mt-2">
                                <i class="bi bi-person-plus"></i> Add User
                            </a>
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

<!-- Delete Modals -->
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
                <p>Are you sure you want to delete user <strong>{{ $user->nama ?? $user->name ?? 'Unknown' }}</strong>?</p>
                <p class="text-muted small">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-1"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection