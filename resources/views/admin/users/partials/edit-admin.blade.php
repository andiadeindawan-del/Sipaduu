<div class="row g-4">
    <!-- ========================================================== -->
    <!-- PROFIL CARD ADMIN -->
    <!-- ========================================================== -->
    <div class="col-12 col-lg-4">
        <div class="panel border-0 shadow-sm mb-4">
            <div class="panel-header">
                <h5 class="section-title"><i class="bi bi-shield-lock" style="color: #dc3545;"></i> Profil Admin</h5>
                <span class="badge bg-danger text-white">Administrator</span>
            </div>
            <div class="p-4 text-center">
                <div class="mb-3 position-relative d-inline-block">
                    @if($user->foto)
                        <img src="{{ asset('storage/' . $user->foto) }}" 
                             alt="Avatar" 
                             class="rounded-circle mx-auto d-block shadow-sm" 
                             style="width: 120px; height: 120px; object-fit: cover; border: 4px solid #e8ecf1;">
                    @else
                        <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center shadow-sm" 
                             style="width: 120px; height: 120px; background: linear-gradient(135deg, #dc3545 0%, #841520 100%); color: #fff; font-size: 42px; font-weight: 700; border: 4px solid #e8ecf1;">
                            {{ strtoupper(substr($user->nama ?? $user->name ?? 'A', 0, 2)) }}
                        </div>
                    @endif
                </div>

                <h5 class="fw-bold mb-1 text-dark">{{ $user->nama ?? $user->name ?? 'Admin' }}</h5>
                <p class="text-muted small mb-3">{{ $user->email ?? '-' }}</p>

                <div class="d-flex justify-content-center gap-2 mb-3">
                    <span class="badge bg-danger px-3 py-1.5" style="border-radius: 20px;">
                        <i class="bi bi-shield-lock me-1"></i> Admin
                    </span>
                    <span class="badge {{ $user->status === 'aktif' ? 'bg-success' : 'bg-secondary' }} px-3 py-1.5" style="border-radius: 20px;">
                        <i class="bi bi-circle-fill me-1" style="font-size: 7px;"></i>
                        {{ ucfirst($user->status ?? 'Aktif') }}
                    </span>
                </div>

                <div class="mt-3 pt-3 border-top text-start">
                    <h6 class="text-uppercase text-muted fw-bold mb-3" style="font-size: 0.72rem; letter-spacing: 0.05em;">
                        <i class="bi bi-info-circle me-1"></i> Informasi Akun Admin
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
            </div>
        </div>
    </div>

    <!-- ========================================================== -->
    <!-- FORM EDIT INFORMASI ADMIN -->
    <!-- ========================================================== -->
    <div class="col-12 col-lg-8">
        <div class="panel border-0 shadow-sm mb-4">
            <div class="panel-header">
                <div>
                    <h5 class="section-title"><i class="bi bi-pencil-square"></i> Edit Informasi Administrator</h5>
                    <p class="text-muted small mb-0">Kelola identitas, kontak, departemen, dan hak akses administrator.</p>
                </div>
            </div>
            
            <div class="p-4">
                <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label for="nama" class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                                   id="nama" name="nama" value="{{ old('nama', $user->nama ?? $user->name) }}" required>
                            @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="email" class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="nik" class="form-label fw-semibold">NIK <span class="text-danger">*</span></label>
                            <input type="text" class="form-control font-monospace @error('nik') is-invalid @enderror" 
                                   id="nik" name="nik" value="{{ old('nik', $user->nik) }}" required>
                            @error('nik') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="no_telepon" class="form-label fw-semibold">Nomor Telepon / WhatsApp <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('no_telepon') is-invalid @enderror" 
                                   id="no_telepon" name="no_telepon" value="{{ old('no_telepon', $user->no_telepon) }}" required>
                            @error('no_telepon') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="departemen" class="form-label fw-semibold">Departemen <span class="text-muted fw-normal">(Opsional)</span></label>
                            <input type="text" class="form-control" id="departemen" name="departemen" value="{{ old('departemen', $user->departemen) }}">
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="jabatan" class="form-label fw-semibold">Jabatan <span class="text-muted fw-normal">(Opsional)</span></label>
                            <input type="text" class="form-control" id="jabatan" name="jabatan" value="{{ old('jabatan', $user->jabatan) }}">
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="role" class="form-label fw-semibold">Role <span class="text-danger">*</span></label>
                            <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin (Administrator)</option>
                                <option value="pemateri" {{ old('role', $user->role) == 'pemateri' ? 'selected' : '' }}>Pemateri (Instruktur)</option>
                                <option value="peserta" {{ old('role', $user->role) == 'peserta' ? 'selected' : '' }}>Peserta (Pelaku Usaha UMK)</option>
                            </select>
                            @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="status" class="form-label fw-semibold">Status Akun <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                                <option value="aktif" {{ old('status', $user->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ old('status', $user->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12">
                            <label for="foto" class="form-label fw-semibold">Foto Profil <span class="text-muted fw-normal">(Opsional)</span></label>
                            <input type="file" class="form-control @error('foto') is-invalid @enderror" id="fotoAdmin" name="foto" accept="image/*">
                            <small class="text-muted">Maksimal 2MB. Format: JPG, PNG, GIF.</small>
                            @error('foto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Card Ubah Password (Opsional) -->
                        <div class="col-12 mt-4">
                            <div class="p-3 border rounded bg-light">
                                <h6 class="mb-1 fw-bold"><i class="bi bi-key-fill text-warning me-1"></i> Ubah Password (Opsional)</h6>
                                <small class="text-muted">Kosongkan jika tidak ingin mengubah password akun ini.</small>
                                <div class="row g-2 mt-1">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label small fw-semibold">Password Baru</label>
                                        <input type="password" class="form-control" name="password" placeholder="Minimal 8 karakter">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label small fw-semibold">Konfirmasi Password Baru</label>
                                        <input type="password" class="form-control" name="password_confirmation" placeholder="Ulangi password baru">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mt-4">
                            <hr class="my-3">
                            <button type="submit" class="btn btn-primary fw-semibold px-4">
                                <i class="bi bi-save me-1"></i> Simpan Perubahan
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary ms-2">Batal</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
