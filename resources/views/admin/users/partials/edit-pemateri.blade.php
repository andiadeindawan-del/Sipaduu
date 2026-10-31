<div class="row g-4">
    <!-- ========================================================== -->
    <!-- PROFIL CARD PEMATERI (SINKRON DENGAN DASHBOARD PEMATERI) -->
    <!-- ========================================================== -->
    <div class="col-12 col-lg-4">
        <!-- Card Profil -->
        <div class="panel border-0 shadow-sm mb-4">
            <div class="panel-header">
                <h5 class="section-title"><i class="bi bi-person-video3" style="color: #6f42c1;"></i> Profil Pemateri</h5>
                <span class="badge" style="background-color: #6f42c1; color: #fff;">
                    <i class="bi bi-person-badge me-1"></i> Pemateri
                </span>
            </div>
            <div class="p-4 text-center">
                <!-- Avatar -->
                <div class="mb-3 position-relative d-inline-block">
                    @if($user->foto)
                        <img src="{{ asset('storage/' . $user->foto) }}" 
                             alt="Avatar" 
                             class="rounded-circle mx-auto d-block shadow-sm" 
                             style="width: 120px; height: 120px; object-fit: cover; border: 4px solid #e8ecf1;">
                    @else
                        <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center shadow-sm" 
                             style="width: 120px; height: 120px; background: linear-gradient(135deg, #6f42c1 0%, #432874 100%); color: #fff; font-size: 42px; font-weight: 700; border: 4px solid #e8ecf1;">
                            {{ strtoupper(substr($user->nama ?? $user->name ?? 'P', 0, 2)) }}
                        </div>
                    @endif
                </div>

                <h5 class="fw-bold mb-1 text-dark">{{ $user->nama ?? $user->name ?? 'Pemateri' }}</h5>
                <p class="text-muted small mb-3">{{ $user->email ?? '-' }}</p>

                <!-- Badges -->
                <div class="d-flex justify-content-center gap-2 mb-3">
                    <span class="badge" style="background-color: #6f42c1; color: #fff; border-radius: 20px; padding: 6px 14px;">
                        <i class="bi bi-person-video3 me-1"></i> Pemateri
                    </span>
                    <span class="badge {{ $user->status === 'aktif' ? 'bg-success' : 'bg-secondary' }} px-3 py-1.5" style="border-radius: 20px;">
                        <i class="bi bi-circle-fill me-1" style="font-size: 7px;"></i>
                        {{ ucfirst($user->status ?? 'Aktif') }}
                    </span>
                </div>

                <!-- Informasi Akun (Sinkron dengan Show & Dashboard Pemateri) -->
                <div class="mt-3 pt-3 border-top text-start">
                    <h6 class="text-uppercase text-muted fw-bold mb-3" style="font-size: 0.72rem; letter-spacing: 0.05em;">
                        <i class="bi bi-info-circle me-1"></i> Informasi Akun Pemateri
                    </h6>

                    <div class="py-2 border-bottom d-flex justify-content-between align-items-center">
                        <span class="text-muted small"><i class="bi bi-card-heading me-1 text-primary"></i> NIK</span>
                        <span class="fw-semibold text-dark font-monospace">{{ $user->nik ?? '-' }}</span>
                    </div>

                    <div class="py-2 border-bottom d-flex justify-content-between align-items-center">
                        <span class="text-muted small"><i class="bi bi-telephone me-1 text-success"></i> No. Telepon</span>
                        <span class="fw-semibold text-dark">{{ $user->no_telepon ?? '-' }}</span>
                    </div>

                    <div class="py-2 border-bottom d-flex justify-content-between align-items-center">
                        <span class="text-muted small"><i class="bi bi-envelope me-1 text-info"></i> Email</span>
                        <span class="fw-semibold text-dark text-truncate ms-2" style="max-width: 170px;" title="{{ $user->email }}">
                            {{ $user->email }}
                        </span>
                    </div>

                    <div class="py-2 border-bottom d-flex justify-content-between align-items-center">
                        <span class="text-muted small"><i class="bi bi-building me-1 text-warning"></i> Departemen</span>
                        <span class="fw-semibold text-dark">{{ $user->departemen ?? '-' }}</span>
                    </div>

                    <div class="py-2 border-bottom d-flex justify-content-between align-items-center">
                        <span class="text-muted small"><i class="bi bi-briefcase me-1 text-secondary"></i> Jabatan</span>
                        <span class="fw-semibold text-dark">{{ $user->jabatan ?? '-' }}</span>
                    </div>

                    <div class="py-2 border-bottom d-flex justify-content-between align-items-center">
                        <span class="text-muted small"><i class="bi bi-calendar-check me-1 text-primary"></i> Bergabung</span>
                        <span class="fw-semibold text-dark">{{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}</span>
                    </div>

                    <div class="pt-2 d-flex justify-content-between align-items-center">
                        <span class="text-muted small"><i class="bi bi-clock me-1 text-muted"></i> Terakhir Aktif</span>
                        <span class="text-muted small">{{ $user->updated_at ? $user->updated_at->diffForHumans() : '-' }}</span>
                    </div>
                </div>

                <hr class="my-3">
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-outline-primary btn-sm w-100">
                        <i class="bi bi-eye me-1"></i> Detail Profil
                    </a>
                    <a href="{{ route('admin.users.change-password.form', $user->id) }}" class="btn btn-outline-warning btn-sm w-100">
                        <i class="bi bi-key me-1"></i> Reset Password
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistik Pelatihan Pemateri -->
        @php
            $myTrainingsCount = $user->trainingDiajar ? $user->trainingDiajar->count() : 0;
            $myTrainingIds = $user->trainingDiajar ? $user->trainingDiajar->pluck('id') : collect([]);
            $myMaterisCount = \App\Models\Materi::whereIn('training_id', $myTrainingIds)->count();
            $myQuizzesCount = \App\Models\Quiz::whereIn('training_id', $myTrainingIds)->count();
        @endphp
        <div class="panel border-0 shadow-sm mb-4">
            <div class="panel-header">
                <h5 class="section-title"><i class="bi bi-mortarboard text-primary"></i> Aktivitas Mengajar</h5>
            </div>
            <div class="p-3">
                <div class="row g-2 text-center mb-3">
                    <div class="col-4">
                        <div class="p-2 rounded bg-light border">
                            <div class="h4 fw-bold mb-0" style="color: #6f42c1;">{{ $myTrainingsCount }}</div>
                            <small class="text-muted" style="font-size: 0.72rem;">Pelatihan</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-2 rounded bg-light border">
                            <div class="h4 fw-bold text-success mb-0">{{ $myMaterisCount }}</div>
                            <small class="text-muted" style="font-size: 0.72rem;">Materi</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-2 rounded bg-light border">
                            <div class="h4 fw-bold text-warning mb-0">{{ $myQuizzesCount }}</div>
                            <small class="text-muted" style="font-size: 0.72rem;">Kuis</small>
                        </div>
                    </div>
                </div>

                @if($user->trainingDiajar && $user->trainingDiajar->count() > 0)
                <h6 class="text-muted small fw-semibold text-uppercase mb-2" style="font-size: 0.72rem;">Pelatihan yang Ditugaskan:</h6>
                <div class="list-group list-group-flush border rounded">
                    @foreach($user->trainingDiajar->take(3) as $t)
                    <div class="list-group-item py-2 px-3 d-flex justify-content-between align-items-center">
                        <div class="text-truncate me-2" style="max-width: 190px;">
                            <span class="small fw-semibold text-dark d-block text-truncate">{{ $t->title }}</span>
                            <span class="badge bg-secondary-subtle text-secondary" style="font-size: 0.68rem;">{{ ucfirst($t->status) }}</span>
                        </div>
                        <a href="{{ route('admin.trainings.show', $t->id) }}" class="btn btn-outline-secondary btn-sm p-1" title="Lihat">
                            <i class="bi bi-arrow-right-short fs-6"></i>
                        </a>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- ========================================================== -->
    <!-- FORM EDIT INFORMASI PEMATERI (BERSIH & TERFOKUS) -->
    <!-- ========================================================== -->
    <div class="col-12 col-lg-8">
        <div class="panel border-0 shadow-sm mb-4">
            <div class="panel-header">
                <div>
                    <h5 class="section-title"><i class="bi bi-pencil-square"></i> Edit Informasi Akun Pemateri</h5>
                    <p class="text-muted small mb-0">Kelola identitas resmi, nomor telepon, instansi, dan kredensial pemateri.</p>
                </div>
                <div>
                    <span class="badge" style="background-color: #6f42c1; color: #fff; font-size: 0.8rem; padding: 6px 12px; border-radius: 20px;">
                        <i class="bi bi-person-video3 me-1"></i> Form Khusus Pemateri
                    </span>
                </div>
            </div>
            
            <div class="p-4">
                <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <!-- Nama Lengkap -->
                        <div class="col-12 col-md-6">
                            <label for="nama" class="form-label fw-semibold">
                                Nama Lengkap <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                                       id="nama" name="nama" value="{{ old('nama', $user->nama ?? $user->name) }}" 
                                       placeholder="Nama lengkap pemateri" required>
                            </div>
                            @error('nama') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <!-- Email -->
                        <div class="col-12 col-md-6">
                            <label for="email" class="form-label fw-semibold">
                                Email <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $user->email) }}" 
                                       placeholder="Email pemateri" required>
                            </div>
                            @error('email') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <!-- NIK (Nomor Induk Kependudukan) -->
                        <div class="col-12 col-md-6">
                            <label for="nik" class="form-label fw-semibold">
                                NIK (Nomor Induk Kependudukan) <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-card-heading"></i></span>
                                <input type="text" class="form-control font-monospace @error('nik') is-invalid @enderror" 
                                       id="nik" name="nik" value="{{ old('nik', $user->nik) }}" 
                                       placeholder="16 digit NIK" maxlength="50" required>
                            </div>
                            <small class="text-muted">16 digit NIK resmi sesuai KTP pemateri.</small>
                            @error('nik') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <!-- Nomor HP / WhatsApp -->
                        <div class="col-12 col-md-6">
                            <label for="no_telepon" class="form-label fw-semibold">
                                Nomor Telepon / WhatsApp <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                <input type="text" class="form-control @error('no_telepon') is-invalid @enderror" 
                                       id="no_telepon" name="no_telepon" value="{{ old('no_telepon', $user->no_telepon) }}" 
                                       placeholder="Contoh: 085333263154" required>
                            </div>
                            <small class="text-muted">Nomor telepon aktif pemateri yang dapat dihubungi via WA.</small>
                            @error('no_telepon') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <!-- Departemen / Instansi -->
                        <div class="col-12 col-md-6">
                            <label for="departemen" class="form-label fw-semibold">
                                Departemen / Instansi <span class="text-muted fw-normal">(Opsional)</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-building"></i></span>
                                <input type="text" class="form-control @error('departemen') is-invalid @enderror" 
                                       id="departemen" name="departemen" value="{{ old('departemen', $user->departemen) }}" 
                                       placeholder="Contoh: Dinas Koperasi & UKM / Lembaga Pelatihan">
                            </div>
                            @error('departemen') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <!-- Jabatan / Posisi -->
                        <div class="col-12 col-md-6">
                            <label for="jabatan" class="form-label fw-semibold">
                                Jabatan / Posisi <span class="text-muted fw-normal">(Opsional)</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-briefcase"></i></span>
                                <input type="text" class="form-control @error('jabatan') is-invalid @enderror" 
                                       id="jabatan" name="jabatan" value="{{ old('jabatan', $user->jabatan) }}" 
                                       placeholder="Contoh: Instruktur Utama / Tenaga Ahli">
                            </div>
                            @error('jabatan') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <!-- Role -->
                        <div class="col-12 col-md-6">
                            <label for="role" class="form-label fw-semibold">
                                Role Pengguna <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-shield-check"></i></span>
                                <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                                    <option value="pemateri" {{ old('role', $user->role) == 'pemateri' ? 'selected' : '' }}>Pemateri (Instruktur)</option>
                                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin (Administrator)</option>
                                    <option value="peserta" {{ old('role', $user->role) == 'peserta' ? 'selected' : '' }}>Peserta (Pelaku Usaha UMK)</option>
                                </select>
                            </div>
                            @error('role') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <!-- Status -->
                        <div class="col-12 col-md-6">
                            <label for="status" class="form-label fw-semibold">
                                Status Akun <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-toggle-on"></i></span>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                                    <option value="aktif" {{ old('status', $user->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="nonaktif" {{ old('status', $user->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                </select>
                            </div>
                            @error('status') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <!-- Foto Profil -->
                        <div class="col-12">
                            <label for="foto" class="form-label fw-semibold">
                                Foto Profil <span class="text-muted fw-normal">(Opsional)</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-image"></i></span>
                                <input type="file" class="form-control @error('foto') is-invalid @enderror" 
                                       id="fotoPemateri" name="foto" accept="image/*">
                            </div>
                            <small class="text-muted">Format: JPG, PNG, GIF. Maksimal 2MB. Kosongkan jika tidak ingin mengubah foto.</small>
                            @error('foto') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror

                            <!-- Preview Foto Baru -->
                            <div id="fotoPreviewContainer" class="mt-2" style="display: none;">
                                <label class="text-muted small fw-semibold">Preview Foto Baru:</label>
                                <div>
                                    <img id="fotoPreview" src="#" alt="Preview" 
                                         style="width: 80px; height: 80px; object-fit: cover; border-radius: 50%; border: 3px solid #6f42c1;">
                                </div>
                            </div>
                        </div>

                        <!-- Card Ubah Password (Opsional) -->
                        <div class="col-12 mt-4">
                            <div class="p-3 border rounded bg-light">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="bi bi-key-fill text-warning fs-5"></i>
                                    <div>
                                        <h6 class="mb-0 fw-bold">Ubah Password Akun (Opsional)</h6>
                                        <small class="text-muted">Isi kolom di bawah hanya jika ingin mengganti password akun pemateri ini.</small>
                                    </div>
                                </div>
                                <div class="row g-2 mt-1">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label small fw-semibold">Password Baru</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                               name="password" placeholder="Minimal 8 karakter">
                                        @error('password') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label small fw-semibold">Konfirmasi Password Baru</label>
                                        <input type="password" class="form-control" 
                                               name="password_confirmation" placeholder="Ulangi password baru">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="col-12 mt-4">
                            <hr class="my-3">
                            <div class="d-flex gap-2 flex-wrap justify-content-between align-items-center">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary fw-semibold px-4">
                                        <i class="bi bi-save me-1"></i> Simpan Perubahan
                                    </button>
                                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-x-circle me-1"></i> Batal
                                    </a>
                                </div>
                                <span class="text-muted small">
                                    <i class="bi bi-shield-check text-success me-1"></i> Perubahan data akan langsung tersinkron ke dashboard pemateri.
                                </span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fotoInput = document.getElementById('fotoPemateri');
    const previewContainer = document.getElementById('fotoPreviewContainer');
    const previewImg = document.getElementById('fotoPreview');

    if (fotoInput) {
        fotoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(evt) {
                    previewImg.src = evt.target.result;
                    previewContainer.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                previewContainer.style.display = 'none';
            }
        });
    }
});
</script>
@endpush
