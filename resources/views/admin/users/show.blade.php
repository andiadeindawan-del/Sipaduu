@extends('layouts.admin')

@section('title', 'Detail User')

@section('header')
<div class="page-heading w-100">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-person" aria-hidden="true"></i></span>
        <div>
            <p class="eyebrow mb-1">Management</p>
            <h1 class="h3 mb-1">{{ $user->name }}</h1>
            <p class="text-muted mb-0">{{ $user->email }}</p>
        </div>
    </div>
    <div class="heading-actions">
        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary btn-sm">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="py-4">
    <div class="container-fluid px-3 px-lg-4">
        <div class="row g-3">
            <div class="col-lg-4">
                <div class="panel text-center p-4">
                    @if($user->foto)
                    <img src="{{ Storage::url($user->foto) }}" class="rounded-circle mb-3"
                         width="96" height="96" style="object-fit:cover">
                    @else
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-3"
                         style="width:96px;height:96px;font-size:1.5rem">
                        {{ strtoupper(substr($user->nama, 0, 2)) }}
                    </div>
                    @endif
                    <h5 class="fw-bold mb-1">{{ $user->nama }}</h5>
                    <span class="badge {{ $user->role === 'admin' ? 'text-bg-danger' : ($user->role === 'trainer' ? 'text-bg-info' : 'text-bg-secondary') }} mb-2">
                        {{ ucfirst($user->role) }}
                    </span>
                    <div>
                        <span class="badge {{ $user->status === 'aktif' ? 'text-bg-success' : 'text-bg-secondary' }}">
                            {{ ucfirst($user->status) }}
                        </span>
                    </div>
                    <hr>
                    <a href="{{ route('admin.users.change-password.form', $user) }}" class="btn btn-outline-warning btn-sm w-100">
                        <i class="bi bi-key"></i> Ubah Password
                    </a>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="panel p-4">
                    <h6 class="fw-bold mb-3">Informasi Akun</h6>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="text-muted small">NIK</div>
                            <div class="fw-semibold"><code>{{ $user->nik }}</code></div>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-muted small">Email</div>
                            <div class="fw-semibold">{{ $user->email }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-muted small">Departemen</div>
                            <div class="fw-semibold">{{ $user->departemen ?? '-' }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-muted small">Jabatan</div>
                            <div class="fw-semibold">{{ $user->jabatan ?? '-' }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-muted small">No. Telepon</div>
                            <div class="fw-semibold">{{ $user->no_telepon ?? '-' }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-muted small">Bergabung</div>
                            <div class="fw-semibold">{{ $user->created_at->format('d M Y') }}</div>
                        </div>
                    </div>
                </div>

                @if($user->isTrainer())
                <div class="panel p-4 mt-3">
                    <h6 class="fw-bold mb-3">Training Diajar</h6>
                    @forelse($user->trainingDiajar as $t)
                    <div class="border-bottom py-2 d-flex justify-content-between">
                        <span>{{ $t->judul }}</span>
                        <span class="badge badge-{{ $t->status }}">{{ ucfirst($t->status) }}</span>
                    </div>
                    @empty
                    <p class="text-muted mb-0">Belum mengajar training apapun.</p>
                    @endforelse
                </div>
                @endif

                @if($user->isPeserta())
                <div class="panel p-4 mt-3">
                    <h6 class="fw-bold mb-3">Training Diikuti</h6>
                    @forelse($user->trainingDiikuti as $t)
                    <div class="border-bottom py-2 d-flex justify-content-between">
                        <span>{{ $t->judul }}</span>
                        <span class="badge bg-light text-dark">{{ ucfirst($t->pivot->status) }}</span>
                    </div>
                    @empty
                    <p class="text-muted mb-0">Belum mengikuti training apapun.</p>
                    @endforelse
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection