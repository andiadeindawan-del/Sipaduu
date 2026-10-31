@extends('layouts.admin')

@section('title', 'Edit User - ' . ($user->nama ?? $user->name ?? 'User'))

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon">
            @if($user->role === 'pemateri')
                <i class="bi bi-person-video3" style="color: #6f42c1;"></i>
            @elseif($user->role === 'admin')
                <i class="bi bi-shield-lock" style="color: #dc3545;"></i>
            @else
                <i class="bi bi-person text-primary"></i>
            @endif
        </span>
        <div>
            <p class="eyebrow">
                @if($user->role === 'pemateri')
                    Manajemen Pemateri
                @elseif($user->role === 'admin')
                    Manajemen Administrator
                @else
                    Manajemen Peserta UMK
                @endif
            </p>
            <h1 class="h3 mb-0">Edit User: {{ $user->nama ?? $user->name ?? 'Unknown' }}</h1>
            <p class="text-muted mb-0">
                @if($user->role === 'pemateri')
                    Perbarui profil, NIK, kontak, instansi, dan kredensial pemateri.
                @elseif($user->role === 'admin')
                    Perbarui profil dan hak akses administrator.
                @else
                    Edit profil data diri, usaha UMK, saluran pemasaran, dan dokumen peserta.
                @endif
            </p>
        </div>
    </div>
    <div class="heading-actions d-flex gap-2">
        <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-eye"></i> Detail User
        </a>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
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

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong><i class="bi bi-exclamation-triangle-fill me-2"></i>Terdapat kesalahan pada pengisian form:</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Render form sesuai role pengguna --}}
    @if($user->role === 'pemateri')
        @include('admin.users.partials.edit-pemateri')
    @elseif($user->role === 'admin')
        @include('admin.users.partials.edit-admin')
    @else
        <div class="row">
            <div class="col-12 col-lg-10 mx-auto">
                @include('admin.users.partials.edit-peserta')
            </div>
        </div>
    @endif
</div>
@endsection
