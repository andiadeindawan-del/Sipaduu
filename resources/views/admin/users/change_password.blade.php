@extends('layouts.admin')

@section('title', 'Ubah Password')

@section('header')
<div class="page-heading w-100">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-key" aria-hidden="true"></i></span>
        <div>
            <p class="eyebrow mb-1">Management</p>
            <h1 class="h3 mb-1">Ubah Password</h1>
            <p class="text-muted mb-0">{{ $user->nama }} ({{ $user->nik }})</p>
        </div>
    </div>
    <div class="heading-actions">
        <a href="{{ route('admin.user.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="py-4">
    <div class="container-fluid px-3 px-lg-4">
        <section class="panel" style="max-width: 480px">
            <div class="p-3">
                <form method="POST" action="{{ route('admin.user.change-password', $user) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password Baru <span class="text-danger">*</span></label>
                        <input type="password" name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               required placeholder="Min. 8 karakter">
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Konfirmasi Password <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan</button>
                        <a href="{{ route('admin.user.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </section>
    </div>
</div>
@endsection