@extends('layouts.admin')

@section('title', 'Edit Sertifikat')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon"><i class="bi bi-pencil-square" aria-hidden="true"></i></span>
            <div>
                <p class="eyebrow mb-1">Management</p>
                <h1 class="h3 mb-1">Edit Sertifikat</h1>
                <p class="text-muted mb-0">Perbarui informasi sertifikat {{ $sertifikat->nomor_sertifikat }}.</p>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12 col-lg-8 mx-auto">
            <div class="panel">
                <div class="panel-header">
                    <h2 class="h5 mb-0 section-title">
                        <i class="bi bi-pencil-square"></i> Edit Sertifikat
                    </h2>
                    <p class="text-muted small mb-0">Perbarui data sertifikat.</p>
                </div>
                <div class="p-4">
                    <form method="POST" action="{{ route('admin.sertifikat.update', $sertifikat->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <!-- User/Peserta -->
                            <div class="col-12">
                                <label for="user_id" class="form-label fw-semibold">Peserta <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    <select class="form-select @error('user_id') is-invalid @enderror" 
                                            id="user_id" name="user_id" required>
                                        <option value="">Pilih Peserta</option>
                                        @foreach($users ?? [] as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id', $sertifikat->user_id) == $user->id ? 'selected' : '' }}>
                                            {{ $user->nama }} - {{ $user->email }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('user_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Training -->
                            <div class="col-12">
                                <label for="training_id" class="form-label fw-semibold">Training</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-mortarboard"></i></span>
                                    <select class="form-select @error('training_id') is-invalid @enderror" 
                                            id="training_id" name="training_id">
                                        <option value="">Pilih Training (Opsional)</option>
                                        @foreach($trainings ?? [] as $training)
                                        <option value="{{ $training->id }}" {{ old('training_id', $sertifikat->training_id) == $training->id ? 'selected' : '' }}>
                                            {{ $training->judul }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('training_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Nomor Sertifikat -->
                            <div class="col-12 col-md-6">
                                <label for="nomor_sertifikat" class="form-label fw-semibold">Nomor Sertifikat <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-hash"></i></span>
                                    <input type="text" class="form-control @error('nomor_sertifikat') is-invalid @enderror" 
                                           id="nomor_sertifikat" name="nomor_sertifikat" 
                                           value="{{ old('nomor_sertifikat', $sertifikat->nomor_sertifikat) }}" 
                                           placeholder="Nomor sertifikat" required>
                                </div>
                                @error('nomor_sertifikat')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Nama Sertifikat -->
                            <div class="col-12 col-md-6">
                                <label for="nama_sertifikat" class="form-label fw-semibold">Nama Sertifikat <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-award"></i></span>
                                    <input type="text" class="form-control @error('nama_sertifikat') is-invalid @enderror" 
                                           id="nama_sertifikat" name="nama_sertifikat" 
                                           value="{{ old('nama_sertifikat', $sertifikat->nama_sertifikat) }}" 
                                           placeholder="Nama sertifikat" required>
                                </div>
                                @error('nama_sertifikat')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Deskripsi -->
                            <div class="col-12">
                                <label for="deskripsi" class="form-label fw-semibold">Deskripsi</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-file-text"></i></span>
                                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                              id="deskripsi" name="deskripsi" rows="3" 
                                              placeholder="Deskripsi sertifikat">{{ old('deskripsi', $sertifikat->deskripsi) }}</textarea>
                                </div>
                                @error('deskripsi')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tanggal Terbit -->
                            <div class="col-12 col-md-6">
                                <label for="tanggal_terbit" class="form-label fw-semibold">Tanggal Terbit <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                                    <input type="date" class="form-control @error('tanggal_terbit') is-invalid @enderror" 
                                           id="tanggal_terbit" name="tanggal_terbit" 
                                           value="{{ old('tanggal_terbit', $sertifikat->tanggal_terbit ? $sertifikat->tanggal_terbit->format('Y-m-d') : '') }}" 
                                           required>
                                </div>
                                @error('tanggal_terbit')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tanggal Berlaku Sampai -->
                            <div class="col-12 col-md-6">
                                <label for="tanggal_berlaku_sampai" class="form-label fw-semibold">Berlaku Sampai</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                                    <input type="date" class="form-control @error('tanggal_berlaku_sampai') is-invalid @enderror" 
                                           id="tanggal_berlaku_sampai" name="tanggal_berlaku_sampai" 
                                           value="{{ old('tanggal_berlaku_sampai', $sertifikat->tanggal_berlaku_sampai ? $sertifikat->tanggal_berlaku_sampai->format('Y-m-d') : '') }}">
                                </div>
                                <small class="text-muted">Kosongkan jika tidak ada batas waktu.</small>
                                @error('tanggal_berlaku_sampai')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Penerbit -->
                            <div class="col-12 col-md-6">
                                <label for="penerbit" class="form-label fw-semibold">Penerbit <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-building"></i></span>
                                    <input type="text" class="form-control @error('penerbit') is-invalid @enderror" 
                                           id="penerbit" name="penerbit" 
                                           value="{{ old('penerbit', $sertifikat->penerbit) }}" 
                                           placeholder="Nama penerbit" required>
                                </div>
                                @error('penerbit')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="col-12 col-md-6">
                                <label for="status" class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-toggle-on"></i></span>
                                    <select class="form-select @error('status') is-invalid @enderror" 
                                            id="status" name="status" required>
                                        <option value="aktif" {{ old('status', $sertifikat->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                        <option value="pending" {{ old('status', $sertifikat->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="revoked" {{ old('status', $sertifikat->status) == 'revoked' ? 'selected' : '' }}>Revoked</option>
                                        <option value="expired" {{ old('status', $sertifikat->status) == 'expired' ? 'selected' : '' }}>Expired</option>
                                    </select>
                                </div>
                                @error('status')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- File Sertifikat -->
                            <div class="col-12">
                                <label for="file_path" class="form-label fw-semibold">File Sertifikat</label>
                                @if($sertifikat->file_path)
                                <div class="mb-2">
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle me-1"></i> 
                                        File: {{ basename($sertifikat->file_path) }}
                                    </span>
                                    <a href="{{ route('admin.sertifikat.download', $sertifikat->id) }}" 
                                       class="btn btn-sm btn-outline-primary ms-2" target="_blank">
                                        <i class="bi bi-download"></i> Download
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger ms-1" 
                                            onclick="document.getElementById('remove_file').value='1'; this.disabled=true; this.innerHTML='Akan dihapus'">
                                        <i class="bi bi-trash"></i> Hapus File
                                    </button>
                                    <input type="hidden" id="remove_file" name="remove_file" value="0">
                                </div>
                                @endif
                                <input type="file" class="form-control @error('file_path') is-invalid @enderror" 
                                       id="file_path" name="file_path" accept=".pdf,.jpg,.jpeg,.png">
                                <small class="text-muted">Max 5MB. Supported: PDF, JPG, JPEG, PNG. Kosongkan jika tidak ingin mengganti file.</small>
                                @error('file_path')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tanda Tangan Digital -->
                            <div class="col-12">
                                <label for="tanda_tangan_digital" class="form-label fw-semibold">Tanda Tangan Digital</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-pen"></i></span>
                                    <input type="text" class="form-control @error('tanda_tangan_digital') is-invalid @enderror" 
                                           id="tanda_tangan_digital" name="tanda_tangan_digital" 
                                           value="{{ old('tanda_tangan_digital', $sertifikat->tanda_tangan_digital) }}" 
                                           placeholder="Nama penandatangan atau hash digital">
                                </div>
                                @error('tanda_tangan_digital')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Catatan -->
                            <div class="col-12">
                                <label for="catatan" class="form-label fw-semibold">Catatan</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-sticky"></i></span>
                                    <textarea class="form-control @error('catatan') is-invalid @enderror" 
                                              id="catatan" name="catatan" rows="2" 
                                              placeholder="Catatan tambahan (opsional)">{{ old('catatan', $sertifikat->catatan) }}</textarea>
                                </div>
                                @error('catatan')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Submit Buttons -->
                           <div class="col-12 mt-4">
                                <div class="d-flex gap-2 flex-wrap align-items-center">
                                    <a href="{{ route('admin.sertifikat.index') }}" class="btn btn-secondary">
                                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
                                    </a>
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-save me-1"></i> Simpan Perubahan
                                        </button>
                                        <a href="{{ route('admin.sertifikat.index') }}" class="btn btn-outline-secondary">
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
        // Preview file
        const fileInput = document.getElementById('file_path');
        if (fileInput) {
            fileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const validTypes = ['application/pdf', 'image/jpeg', 'image/png', 'image/jpg'];
                    const maxSize = 5 * 1024 * 1024; // 5MB
                    
                    if (!validTypes.includes(file.type)) {
                        alert('Format file tidak didukung. Gunakan PDF, JPG, JPEG, atau PNG.');
                        this.value = '';
                        return;
                    }
                    
                    if (file.size > maxSize) {
                        alert('Ukuran file terlalu besar. Maksimal 5MB.');
                        this.value = '';
                        return;
                    }
                }
            });
        }

        // Tanggal terbit tidak boleh setelah tanggal berlaku sampai
        const tanggalTerbit = document.getElementById('tanggal_terbit');
        const tanggalBerlaku = document.getElementById('tanggal_berlaku_sampai');

        if (tanggalTerbit && tanggalBerlaku) {
            tanggalTerbit.addEventListener('change', function() {
                if (tanggalBerlaku.value && tanggalBerlaku.value < this.value) {
                    tanggalBerlaku.value = '';
                }
                tanggalBerlaku.min = this.value;
            });

            // Set initial min date
            if (tanggalTerbit.value) {
                tanggalBerlaku.min = tanggalTerbit.value;
            }
        }

        // File removal confirmation
        const removeFileBtn = document.querySelector('[onclick*="remove_file"]');
        if (removeFileBtn) {
            removeFileBtn.addEventListener('click', function(e) {
                if (!confirm('Yakin ingin menghapus file sertifikat?')) {
                    e.preventDefault();
                    return false;
                }
            });
        }
    });
</script>
@endpush
@endsection