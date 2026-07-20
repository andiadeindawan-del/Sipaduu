@extends('layouts.admin')

@section('title', 'Profil Saya')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-person-circle"></i></span>
        <div>
            <p class="eyebrow">Akun</p>
            <h1 class="h3 mb-0">Profil Saya</h1>
            <p class="text-muted mb-0">Kelola informasi profil dan akun Anda.</p>
        </div>
    </div>
    <div class="heading-actions">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="row">
        <div class="col-12 col-lg-10 mx-auto">
            <!-- Alert Messages -->
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Terjadi kesalahan!</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row g-4">
                <!-- ========================================================== -->
                <!-- PROFIL CARD -->
                <!-- ========================================================== -->
                <div class="col-12 col-md-4">
                    <div class="panel">
                        <div class="panel-header">
                            <h5 class="section-title"><i class="bi bi-person"></i> Profil</h5>
                        </div>
                        <div class="p-4 text-center">
                            <!-- Avatar -->
                            <div class="mb-3">
                                @if(auth()->user()->avatar)
                                    <img src="{{ Storage::url(auth()->user()->avatar) }}" 
                                        alt="Avatar" 
                                        class="rounded-circle mx-auto d-block" 
                                        style="width: 120px; height: 120px; object-fit: cover; border: 4px solid #e8ecf1;">
                                @else
                                    <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center" 
                                        style="width: 120px; height: 120px; background: #4e9af1; color: #fff; font-size: 48px; border: 4px solid #e8ecf1;">
                                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                                    </div>
                                @endif
                            </div>

                            <!-- Nama -->
                            <h5 class="fw-bold mb-1">{{ auth()->user()->name ?? 'User' }}</h5>
                            <p class="text-muted small mb-3">{{ auth()->user()->email ?? '-' }}</p>

                            <!-- Role -->
                            <span class="badge bg-primary mb-3">
                                <i class="bi bi-shield-check me-1"></i>
                                {{ ucfirst(auth()->user()->role ?? 'Admin') }}
                            </span>

                            <!-- Status -->
                            <div class="mt-3 pt-3 border-top">
                                <p class="text-muted small mb-1">
                                    <i class="bi bi-calendar-check me-1"></i> 
                                    Bergabung: {{ auth()->user()->created_at ? auth()->user()->created_at->format('d/m/Y') : '-' }}
                                </p>
                                <p class="text-muted small mb-0">
                                    <i class="bi bi-clock me-1"></i> 
                                    Terakhir aktif: {{ auth()->user()->updated_at ? auth()->user()->updated_at->diffForHumans() : '-' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ========================================================== -->
                <!-- FORM EDIT PROFIL -->
                <!-- ========================================================== -->
                <div class="col-12 col-md-8">
                    <div class="panel">
                        <div class="panel-header">
                            <h5 class="section-title"><i class="bi bi-pencil-square"></i> Edit Profil</h5>
                        </div>
                        <div class="p-4">
                            <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row g-3">
                                    <!-- Nama -->
                                    <div class="col-12 col-md-6">
                                        <label for="name" class="form-label fw-semibold">
                                            Nama Lengkap <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                                   id="name" name="name" value="{{ old('name', auth()->user()->name) }}" 
                                                   placeholder="Masukkan nama lengkap" required>
                                        </div>
                                        @error('name')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Email -->
                                    <div class="col-12 col-md-6">
                                        <label for="email" class="form-label fw-semibold">
                                            Email <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                                   id="email" name="email" value="{{ old('email', auth()->user()->email) }}" 
                                                   placeholder="Masukkan email" required>
                                        </div>
                                        @error('email')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Avatar -->
                                    <div class="col-12">
                                        <label for="avatar" class="form-label fw-semibold">
                                            Foto Profil
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-image"></i></span>
                                            <input type="file" class="form-control @error('avatar') is-invalid @enderror" 
                                                   id="avatar" name="avatar" accept="image/*">
                                        </div>
                                        <small class="text-muted">Format: JPG, PNG, GIF. Maksimal 2MB.</small>
                                        @error('avatar')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Preview Avatar -->
                                    <div class="col-12" id="avatarPreviewContainer" style="display: none;">
                                        <label class="form-label fw-semibold">Preview Foto</label>
                                        <div class="p-3 border rounded bg-light">
                                            <img id="avatarPreview" src="#" alt="Preview" 
                                                 style="max-width: 150px; max-height: 150px; border-radius: 50%;">
                                        </div>
                                    </div>

                                    <!-- Submit Buttons -->
                                    <div class="col-12 mt-3">
                                        <hr class="my-2">
                                        <div class="d-flex gap-2 flex-wrap">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="bi bi-save me-1"></i> Simpan Perubahan
                                            </button>
                                            <button type="reset" class="btn btn-outline-secondary">
                                                <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- ========================================================== -->
                <!-- CHANGE PASSWORD -->
                <!-- ========================================================== -->
                <div class="col-12">
                    <div class="panel">
                        <div class="panel-header">
                            <h5 class="section-title"><i class="bi bi-key"></i> Ganti Password</h5>
                        </div>
                        <div class="p-4">
                            <form action="{{ route('admin.profile.password') }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row g-3">
                                    <!-- Password Lama -->
                                    <div class="col-12 col-md-4">
                                        <label for="current_password" class="form-label fw-semibold">
                                            Password Lama <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                                   id="current_password" name="current_password" 
                                                   placeholder="Masukkan password lama" required>
                                        </div>
                                        @error('current_password')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Password Baru -->
                                    <div class="col-12 col-md-4">
                                        <label for="password" class="form-label fw-semibold">
                                            Password Baru <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                                   id="password" name="password" 
                                                   placeholder="Masukkan password baru" required>
                                        </div>
                                        <small class="text-muted">Minimal 8 karakter.</small>
                                        @error('password')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Konfirmasi Password -->
                                    <div class="col-12 col-md-4">
                                        <label for="password_confirmation" class="form-label fw-semibold">
                                            Konfirmasi Password <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                            <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                                                   id="password_confirmation" name="password_confirmation" 
                                                   placeholder="Konfirmasi password baru" required>
                                        </div>
                                        @error('password_confirmation')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Submit Buttons -->
                                    <div class="col-12 mt-3">
                                        <hr class="my-2">
                                        <div class="d-flex gap-2 flex-wrap">
                                            <button type="submit" class="btn btn-warning">
                                                <i class="bi bi-key me-1"></i> Ganti Password
                                            </button>
                                            <button type="reset" class="btn btn-outline-secondary">
                                                <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- ========================================================== -->
                <!-- STATISTIK AKUN -->
                <!-- ========================================================== -->
                <div class="col-12">
                    <div class="panel">
                        <div class="panel-header">
                            <h5 class="section-title"><i class="bi bi-bar-chart"></i> Statistik Akun</h5>
                        </div>
                        <div class="p-4">
                            <div class="row g-3">
                                <div class="col-6 col-md-3">
                                    <div class="text-center p-3 border rounded bg-light">
                                        <h6 class="text-muted small">Total Quiz Dikerjakan</h6>
                                        <h3 class="mb-0">{{ $totalQuizAttempts ?? 0 }}</h3>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="text-center p-3 border rounded bg-light">
                                        <h6 class="text-muted small">Sertifikat Diperoleh</h6>
                                        <h3 class="mb-0">{{ $totalCertificates ?? 0 }}</h3>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="text-center p-3 border rounded bg-light">
                                        <h6 class="text-muted small">Pelatihan Diikuti</h6>
                                        <h3 class="mb-0">{{ $totalTrainings ?? 0 }}</h3>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="text-center p-3 border rounded bg-light">
                                        <h6 class="text-muted small">Rata-rata Nilai Quiz</h6>
                                        <h3 class="mb-0">{{ number_format($averageQuizScore ?? 0, 1) }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ============================================================
    // PREVIEW AVATAR
    // ============================================================
    const avatarInput = document.getElementById('avatar');
    const avatarPreviewContainer = document.getElementById('avatarPreviewContainer');
    const avatarPreview = document.getElementById('avatarPreview');

    if (avatarInput) {
        avatarInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const maxSize = 2 * 1024 * 1024; // 2MB
                if (file.size > maxSize) {
                    alert('⚠️ Ukuran file terlalu besar. Maksimal 2MB.');
                    this.value = '';
                    avatarPreviewContainer.style.display = 'none';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    avatarPreview.src = e.target.result;
                    avatarPreviewContainer.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                avatarPreviewContainer.style.display = 'none';
            }
        });
    }

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
    // SHOW/HIDE PASSWORD
    // ============================================================
    document.querySelectorAll('.input-group .bi-lock').forEach(function(icon) {
        const input = icon.closest('.input-group').querySelector('input');
        if (input) {
            icon.style.cursor = 'pointer';
            icon.addEventListener('click', function() {
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                this.classList.toggle('bi-lock');
                this.classList.toggle('bi-unlock');
            });
        }
    });
});
</script>
@endpush
@endsection