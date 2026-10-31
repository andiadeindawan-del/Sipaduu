@extends('layouts.pemateri')

@section('title', 'Profil Pemateri')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-person-circle"></i></span>
        <div>
            <p class="eyebrow">Akun Pemateri</p>
            <h1 class="h3 mb-0">Profil Pemateri</h1>
            <p class="text-muted mb-0">Kelola informasi data diri, NIK, kontak, dan kredensial akun Anda.</p>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="row">
        <div class="col-12 col-xl-11 mx-auto">
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

            @if(isset($errors) && $errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong><i class="bi bi-exclamation-triangle me-1"></i> Terjadi kesalahan pada pengisian form:</strong>
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
                <!-- PROFIL CARD & INFORMASI AKUN (SINKRON DENGAN ADMIN) -->
                <!-- ========================================================== -->
                <div class="col-12 col-lg-4">
                    <!-- Card Utama Profil -->
                    <div class="panel border-0 shadow-sm mb-4">
                        <div class="panel-header">
                            <h5 class="section-title"><i class="bi bi-person-badge"></i> Data Pemateri</h5>
                        </div>
                        <div class="p-4 text-center">
                            <!-- Avatar -->
                            <div class="mb-3 position-relative d-inline-block">
                                @if(auth()->user()->foto)
                                    <img src="{{ asset('storage/' . auth()->user()->foto) }}" 
                                        alt="Avatar" 
                                        class="rounded-circle mx-auto d-block shadow-sm" 
                                        style="width: 120px; height: 120px; object-fit: cover; border: 4px solid #e8ecf1;">
                                @else
                                    <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center shadow-sm" 
                                        style="width: 120px; height: 120px; background: linear-gradient(135deg, #4a6cf7 0%, #2547d0 100%); color: #fff; font-size: 42px; font-weight: 700; border: 4px solid #e8ecf1;">
                                        {{ strtoupper(substr(auth()->user()->nama ?? auth()->user()->name ?? 'P', 0, 2)) }}
                                    </div>
                                @endif
                            </div>

                            <!-- Nama & Email -->
                            <h5 class="fw-bold mb-1 text-dark">{{ auth()->user()->nama ?? auth()->user()->name ?? 'Pemateri' }}</h5>
                            <p class="text-muted small mb-3">{{ auth()->user()->email ?? '-' }}</p>

                            <!-- Role & Status Badges -->
                            <div class="d-flex justify-content-center gap-2 mb-3">
                                <span class="badge bg-primary px-3 py-1.5" style="border-radius: 20px;">
                                    <i class="bi bi-person-video3 me-1"></i>
                                    Pemateri
                                </span>
                                <span class="badge bg-success px-3 py-1.5" style="border-radius: 20px;">
                                    <i class="bi bi-check-circle-fill me-1" style="font-size: 9px;"></i>
                                    {{ ucfirst(auth()->user()->status ?? 'Aktif') }}
                                </span>
                            </div>

                            <!-- Informasi Akun (Cocokkan dengan tampilan Admin) -->
                            <div class="mt-3 pt-3 border-top text-start">
                                <h6 class="text-uppercase text-muted fw-bold mb-3" style="font-size: 0.72rem; letter-spacing: 0.05em;">
                                    <i class="bi bi-info-circle me-1"></i> Informasi Akun
                                </h6>

                                <div class="py-2 border-bottom d-flex justify-content-between align-items-center">
                                    <span class="text-muted small"><i class="bi bi-card-heading me-1 text-primary"></i> NIK</span>
                                    <span class="fw-semibold text-dark font-monospace">{{ auth()->user()->nik ?? '-' }}</span>
                                </div>

                                <div class="py-2 border-bottom d-flex justify-content-between align-items-center">
                                    <span class="text-muted small"><i class="bi bi-telephone me-1 text-success"></i> No. Telepon</span>
                                    <span class="fw-semibold text-dark">{{ auth()->user()->no_telepon ?? '-' }}</span>
                                </div>

                                <div class="py-2 border-bottom d-flex justify-content-between align-items-center">
                                    <span class="text-muted small"><i class="bi bi-envelope me-1 text-info"></i> Email</span>
                                    <span class="fw-semibold text-dark text-truncate ms-2" style="max-width: 170px;" title="{{ auth()->user()->email }}">
                                        {{ auth()->user()->email }}
                                    </span>
                                </div>

                                <div class="py-2 border-bottom d-flex justify-content-between align-items-center">
                                    <span class="text-muted small"><i class="bi bi-building me-1 text-warning"></i> Departemen</span>
                                    <span class="fw-semibold text-dark">{{ auth()->user()->departemen ?? '-' }}</span>
                                </div>

                                <div class="py-2 border-bottom d-flex justify-content-between align-items-center">
                                    <span class="text-muted small"><i class="bi bi-briefcase me-1 text-secondary"></i> Jabatan</span>
                                    <span class="fw-semibold text-dark">{{ auth()->user()->jabatan ?? '-' }}</span>
                                </div>

                                <div class="py-2 border-bottom d-flex justify-content-between align-items-center">
                                    <span class="text-muted small"><i class="bi bi-calendar-check me-1 text-primary"></i> Bergabung</span>
                                    <span class="fw-semibold text-dark">{{ auth()->user()->created_at ? auth()->user()->created_at->format('d M Y') : '-' }}</span>
                                </div>

                                <div class="pt-2 d-flex justify-content-between align-items-center">
                                    <span class="text-muted small"><i class="bi bi-clock me-1 text-muted"></i> Terakhir Aktif</span>
                                    <span class="text-muted small">{{ auth()->user()->updated_at ? auth()->user()->updated_at->diffForHumans() : '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Statistik Aktivitas Pemateri (Cocokkan dengan ringkasan Admin) -->
                    <div class="panel border-0 shadow-sm">
                        <div class="panel-header">
                            <h5 class="section-title"><i class="bi bi-bar-chart"></i> Statistik Mengajar</h5>
                        </div>
                        <div class="p-3">
                            <div class="row g-2 text-center">
                                <div class="col-4">
                                    <div class="p-2 rounded bg-light border">
                                        <div class="h4 fw-bold text-primary mb-0">{{ $totalTrainings ?? 0 }}</div>
                                        <small class="text-muted" style="font-size: 0.72rem;">Pelatihan</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="p-2 rounded bg-light border">
                                        <div class="h4 fw-bold text-success mb-0">{{ $totalMateris ?? 0 }}</div>
                                        <small class="text-muted" style="font-size: 0.72rem;">Materi</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="p-2 rounded bg-light border">
                                        <div class="h4 fw-bold text-warning mb-0">{{ $totalQuizzes ?? 0 }}</div>
                                        <small class="text-muted" style="font-size: 0.72rem;">Kuis</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ========================================================== -->
                <!-- FORM EDIT PROFIL & KREDENSIAL -->
                <!-- ========================================================== -->
                <div class="col-12 col-lg-8">
                    <!-- Form Edit Informasi Akun -->
                    <div class="panel border-0 shadow-sm mb-4">
                        <div class="panel-header">
                            <div>
                                <h5 class="section-title"><i class="bi bi-pencil-square"></i> Edit Informasi Akun & Data Diri</h5>
                                <p class="text-muted small mb-0">Sinkronkan data akun Anda sesuai identitas resmi yang terdaftar.</p>
                            </div>
                        </div>
                        <div class="p-4">
                            <form action="{{ route('pemateri.profile.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row g-3">
                                    <!-- Nama Lengkap -->
                                    <div class="col-12 col-md-6">
                                        <label for="name" class="form-label fw-semibold">
                                            Nama Lengkap <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                                   id="name" name="name" value="{{ old('name', auth()->user()->nama ?? auth()->user()->name) }}" 
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
                                                   placeholder="Masukkan email aktif" required>
                                        </div>
                                        @error('email')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- NIK (Nomor Induk Kependudukan) -->
                                    <div class="col-12 col-md-6">
                                        <label for="nik" class="form-label fw-semibold">
                                            NIK (Nomor Induk Kependudukan) <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-card-heading"></i></span>
                                            <input type="text" class="form-control font-monospace @error('nik') is-invalid @enderror" 
                                                   id="nik" name="nik" value="{{ old('nik', auth()->user()->nik) }}" 
                                                   placeholder="Masukkan 16 digit NIK" maxlength="50" required>
                                        </div>
                                        <small class="text-muted">16 digit NIK resmi sesuai KTP Anda.</small>
                                        @error('nik')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Nomor Telepon / WhatsApp -->
                                    <div class="col-12 col-md-6">
                                        <label for="no_telepon" class="form-label fw-semibold">
                                            Nomor Telepon / WhatsApp <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                            <input type="text" class="form-control @error('no_telepon') is-invalid @enderror" 
                                                   id="no_telepon" name="no_telepon" value="{{ old('no_telepon', auth()->user()->no_telepon) }}" 
                                                   placeholder="Contoh: 085333263154" required>
                                        </div>
                                        <small class="text-muted">Nomor aktif yang dapat dihubungi melalui telepon / WA.</small>
                                        @error('no_telepon')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Departemen / Instansi -->
                                    <div class="col-12 col-md-6">
                                        <label for="departemen" class="form-label fw-semibold">
                                            Departemen / Instansi <span class="text-muted fw-normal">(Opsional)</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-building"></i></span>
                                            <input type="text" class="form-control @error('departemen') is-invalid @enderror" 
                                                   id="departemen" name="departemen" value="{{ old('departemen', auth()->user()->departemen) }}" 
                                                   placeholder="Contoh: Dinas Koperasi & UKM / Lembaga">
                                        </div>
                                        @error('departemen')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Jabatan / Posisi -->
                                    <div class="col-12 col-md-6">
                                        <label for="jabatan" class="form-label fw-semibold">
                                            Jabatan / Posisi <span class="text-muted fw-normal">(Opsional)</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-briefcase"></i></span>
                                            <input type="text" class="form-control @error('jabatan') is-invalid @enderror" 
                                                   id="jabatan" name="jabatan" value="{{ old('jabatan', auth()->user()->jabatan) }}" 
                                                   placeholder="Contoh: Instruktur / Tenaga Ahli">
                                        </div>
                                        @error('jabatan')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Foto Profil -->
                                    <div class="col-12">
                                        <label for="avatar" class="form-label fw-semibold">
                                            Foto Profil <span class="text-muted fw-normal">(Opsional)</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-image"></i></span>
                                            <input type="file" class="form-control @error('avatar') is-invalid @enderror" 
                                                   id="avatar" name="avatar" accept="image/jpeg,image/png,image/jpg,image/gif">
                                        </div>
                                        <small class="text-muted">Format: JPG, PNG, GIF. Maksimal 2MB.</small>
                                        @error('avatar')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Preview Avatar Baru -->
                                    <div class="col-12" id="avatarPreviewContainer" style="display: none;">
                                        <label class="form-label fw-semibold text-muted small">Preview Foto Baru</label>
                                        <div class="p-2 border rounded bg-light d-inline-block">
                                            <img id="avatarPreview" src="#" alt="Preview" 
                                                 style="width: 90px; height: 90px; object-fit: cover; border-radius: 50%;">
                                        </div>
                                    </div>

                                    <!-- Submit Buttons -->
                                    <div class="col-12 mt-3">
                                        <hr class="my-3">
                                        <div class="d-flex gap-2 flex-wrap">
                                            <button type="submit" class="btn btn-primary fw-semibold px-4">
                                                <i class="bi bi-save me-1"></i> Simpan Perubahan
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Panel Ganti Password -->
                    <div class="panel border-0 shadow-sm">
                        <div class="panel-header">
                            <div>
                                <h5 class="section-title"><i class="bi bi-key"></i> Ganti Password Akun</h5>
                                <p class="text-muted small mb-0">Pastikan menggunakan password yang aman dan sulit ditebak.</p>
                            </div>
                        </div>
                        <div class="p-4">
                            <form action="{{ route('pemateri.profile.password') }}" method="POST">
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
                                                   placeholder="Password saat ini" required>
                                            <button class="btn btn-outline-secondary toggle-pwd" type="button" data-target="current_password">
                                                <i class="bi bi-eye"></i>
                                            </button>
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
                                                   placeholder="Password baru" required>
                                            <button class="btn btn-outline-secondary toggle-pwd" type="button" data-target="password">
                                                <i class="bi bi-eye"></i>
                                            </button>
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
                                            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                            <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                                                   id="password_confirmation" name="password_confirmation" 
                                                   placeholder="Ulangi password baru" required>
                                            <button class="btn btn-outline-secondary toggle-pwd" type="button" data-target="password_confirmation">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                        @error('password_confirmation')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12 mt-3">
                                        <hr class="my-2">
                                        <button type="submit" class="btn btn-warning fw-semibold text-dark">
                                            <i class="bi bi-shield-lock me-1"></i> Perbarui Password
                                        </button>
                                    </div>
                                </div>
                            </form>
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
    // Preview Avatar
    const avatarInput = document.getElementById('avatar');
    const avatarPreviewContainer = document.getElementById('avatarPreviewContainer');
    const avatarPreview = document.getElementById('avatarPreview');

    if (avatarInput) {
        avatarInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
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

    // Toggle Password Visibility
    document.querySelectorAll('.toggle-pwd').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            const icon = this.querySelector('i');
            if (input) {
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('bi-eye');
                    icon.classList.add('bi-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('bi-eye-slash');
                    icon.classList.add('bi-eye');
                }
            }
        });
    });

    // Auto-dismiss alerts after 5 seconds
    setTimeout(function() {
        document.querySelectorAll('.alert.alert-dismissible').forEach(function(alert) {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            if (bsAlert) {
                bsAlert.close();
            }
        });
    }, 5000);
});
</script>
@endpush
@endsection
