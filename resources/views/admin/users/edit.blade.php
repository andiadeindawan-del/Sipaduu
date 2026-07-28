@extends('layouts.admin')

@section('title', 'Edit User')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-pencil-square" aria-hidden="true"></i></span>
        <div>
            <p class="eyebrow">Management</p>
            <h1 class="h3 mb-0">Edit User</h1>
            <p class="text-muted mb-0">Perbarui informasi akun {{ $user->nama ?? $user->name ?? 'User' }}.</p>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="row">
        <div class="col-12 col-lg-8 mx-auto">
            <div class="panel">
                <div class="panel-header">
                    <h5 class="section-title"><i class="bi bi-pencil-square"></i> Edit Data User</h5>
                    <p class="text-muted small mb-0">Perbarui informasi akun user.</p>
                </div>
                <div class="p-4">
                    <form method="POST" action="{{ route('admin.users.update', $user->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <!-- NIK -->
                            <div class="col-12 col-md-6">
                                <label for="nik" class="form-label fw-semibold">NIK <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                                    <input type="text" class="form-control @error('nik') is-invalid @enderror" 
                                           id="nik" name="nik" value="{{ old('nik', $user->nik) }}" 
                                           placeholder="Enter NIK" required>
                                </div>
                                @error('nik')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Nama -->
                            <div class="col-12 col-md-6">
                                <label for="nama" class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                                           id="nama" name="nama" value="{{ old('nama', $user->nama) }}" 
                                           placeholder="Enter full name" required>
                                </div>
                                @error('nama')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="col-12 col-md-6">
                                <label for="email" class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', $user->email) }}" 
                                           placeholder="Enter email" required>
                                </div>
                                @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- No Telepon -->
                            <div class="col-12 col-md-6">
                                <label for="no_telepon" class="form-label fw-semibold">No Telepon</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-phone"></i></span>
                                    <input type="text" class="form-control @error('no_telepon') is-invalid @enderror" 
                                           id="no_telepon" name="no_telepon" value="{{ old('no_telepon', $user->no_telepon) }}" 
                                           placeholder="Enter phone number">
                                </div>
                                @error('no_telepon')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Role -->
                            <div class="col-12 col-md-6">
                                <label for="role" class="form-label fw-semibold">Role <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-shield"></i></span>
                                    <select class="form-select @error('role') is-invalid @enderror" 
                                            id="role" name="role" required>
                                        <option value="">Pilih Role</option>
                                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="trainer" {{ old('role', $user->role) == 'trainer' ? 'selected' : '' }}>Trainer</option>
                                        <option value="peserta" {{ old('role', $user->role) == 'peserta' ? 'selected' : '' }}>Peserta</option>
                                    </select>
                                </div>
                                @error('role')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="col-12 col-md-6">
                                <label for="status" class="form-label fw-semibold">Status</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-toggle-on"></i></span>
                                    <select class="form-select @error('status') is-invalid @enderror" 
                                            id="status" name="status">
                                        <option value="aktif" {{ old('status', $user->status) == 'aktif' ? 'selected' : '' }}>Active</option>
                                        <option value="nonaktif" {{ old('status', $user->status) == 'nonaktif' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                                @error('status')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Departemen -->
                            <div class="col-12 col-md-6">
                                <label for="departemen" class="form-label fw-semibold">Departemen</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-building"></i></span>
                                    <input type="text" class="form-control @error('departemen') is-invalid @enderror" 
                                           id="departemen" name="departemen" value="{{ old('departemen', $user->departemen) }}" 
                                           placeholder="Enter department">
                                </div>
                                @error('departemen')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Jabatan -->
                            <div class="col-12 col-md-6">
                                <label for="jabatan" class="form-label fw-semibold">Jabatan</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-briefcase"></i></span>
                                    <input type="text" class="form-control @error('jabatan') is-invalid @enderror" 
                                           id="jabatan" name="jabatan" value="{{ old('jabatan', $user->jabatan) }}" 
                                           placeholder="Enter position">
                                </div>
                                @error('jabatan')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="col-12 col-md-6">
                                <label for="password" class="form-label fw-semibold">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password" placeholder="Kosongkan jika tidak diubah">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                <small class="text-muted">Min 8 karakter. Kosongkan jika tidak ingin mengubah password.</small>
                                @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div class="col-12 col-md-6">
                                <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                    <input type="password" class="form-control" 
                                           id="password_confirmation" name="password_confirmation" 
                                           placeholder="Konfirmasi password baru">
                                </div>
                            </div>

                            <!-- Business Fields -->
                            <div class="col-12">
                                <hr class="my-2">
                                <h6 class="fw-semibold text-muted">
                                    <i class="bi bi-building me-2"></i>Business Information
                                </h6>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="nama_usaha" class="form-label fw-semibold">Business Name</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-building"></i></span>
                                    <input type="text" class="form-control @error('nama_usaha') is-invalid @enderror" 
                                           id="nama_usaha" name="nama_usaha" value="{{ old('nama_usaha', $user->nama_usaha) }}" 
                                           placeholder="Enter business name">
                                </div>
                                @error('nama_usaha')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="nib" class="form-label fw-semibold">NIB</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-card-text"></i></span>
                                    <input type="text" class="form-control @error('nib') is-invalid @enderror" 
                                           id="nib" name="nib" value="{{ old('nib', $user->nib) }}" 
                                           placeholder="Enter NIB">
                                </div>
                                @error('nib')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="jenis_usaha" class="form-label fw-semibold">Business Type</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-tag"></i></span>
                                    <select class="form-select @error('jenis_usaha') is-invalid @enderror" 
                                            id="jenis_usaha" name="jenis_usaha">
                                        <option value="">Select Type</option>
                                        <option value="formal" {{ old('jenis_usaha', $user->jenis_usaha) == 'formal' ? 'selected' : '' }}>Formal</option>
                                        <option value="non_formal" {{ old('jenis_usaha', $user->jenis_usaha) == 'non_formal' ? 'selected' : '' }}>Non Formal</option>
                                    </select>
                                </div>
                                @error('jenis_usaha')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="alamat_lengkap" class="form-label fw-semibold">Complete Address</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                    <textarea class="form-control @error('alamat_lengkap') is-invalid @enderror" 
                                              id="alamat_lengkap" name="alamat_lengkap" rows="3" 
                                              placeholder="Enter complete address">{{ old('alamat_lengkap', $user->alamat_lengkap) }}</textarea>
                                </div>
                                @error('alamat_lengkap')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Foto Profil - PERBAIKAN UKURAN -->
                            <div class="col-12">
                                <label for="foto" class="form-label fw-semibold">Foto Profil</label>
                                <div class="d-flex align-items-center gap-3">
                                    @if($user->foto)
                                    <div class="position-relative">
                                        <img src="{{ asset('storage/' . $user->foto) }}" alt="{{ $user->nama }}" 
                                             class="rounded-circle border" 
                                             style="width: 60px; height: 60px; object-fit: cover;">
                                        <span class="badge bg-success position-absolute bottom-0 end-0" style="font-size: 8px; padding: 2px 6px;">
                                            <i class="bi bi-check-circle"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <span class="text-muted small d-block">Foto saat ini</span>
                                        <small class="text-muted">{{ basename($user->foto) }}</small>
                                    </div>
                                    @else
                                    <div class="d-flex align-items-center justify-content-center rounded-circle bg-light text-secondary border" 
                                         style="width: 60px; height: 60px;">
                                        <i class="bi bi-person fs-2"></i>
                                    </div>
                                    <div>
                                        <span class="text-muted small d-block">Belum ada foto</span>
                                    </div>
                                    @endif
                                </div>
                                <div class="mt-2">
                                    <input type="file" class="form-control @error('foto') is-invalid @enderror" 
                                           id="foto" name="foto" accept="image/*" style="max-width: 400px;">
                                    <small class="text-muted">Max 2MB. Supported: JPG, PNG, GIF</small>
                                </div>
                                @error('foto')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Submit Buttons -->
                            <div class="col-12 mt-4">
                                <hr class="my-2">
                                <div class="d-flex flex-wrap gap-2 align-items-center justify-content-between">
                                    <!-- Kiri: Tombol Kembali -->
                                    <div>
                                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                                            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
                                        </a>
                                    </div>
                                    
                                    <!-- Kanan: Tombol Aksi -->
                                    <div class="d-flex gap-2 flex-wrap">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-save me-1"></i> Perbarui
                                        </button>
                                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                                            <i class="bi bi-x-circle me-1"></i> Batal
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle password visibility
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');

        if (togglePassword && password) {
            togglePassword.addEventListener('click', function() {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                this.querySelector('i').classList.toggle('bi-eye');
                this.querySelector('i').classList.toggle('bi-eye-slash');
            });
        }

        // Preview image before upload
        const fotoInput = document.getElementById('foto');
        if (fotoInput) {
            fotoInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const preview = document.querySelector('.rounded-circle.border[style*="width: 60px"]');
                        if (preview) {
                            preview.src = e.target.result;
                        }
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    });
</script>
@endpush
@endsection