@extends('layouts.admin')

@section('title', 'Tambah Sertifikat')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon"><i class="bi bi-plus-circle" aria-hidden="true"></i></span>
            <div>
                <p class="eyebrow mb-1">Management</p>
                <h1 class="h3 mb-1">Tambah Sertifikat</h1>
                <p class="text-muted mb-0">Buat sertifikat baru untuk peserta.</p>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12 col-lg-8 mx-auto">
            <div class="panel">
                <div class="panel-header">
                    <h2 class="h5 mb-0 section-title">
                        <i class="bi bi-plus-circle"></i> Form Tambah Sertifikat
                    </h2>
                    <p class="text-muted small mb-0">Isi data sertifikat dengan lengkap.</p>
                </div>
                <div class="p-4">
                    <form action="{{ route('admin.sertifikat.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

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
                                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
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
                                        <option value="{{ $training->id }}" {{ old('training_id') == $training->id ? 'selected' : '' }}>
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
                                <label for="nomor_sertifikat" class="form-label fw-semibold">Nomor Sertifikat</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-hash"></i></span>
                                    <input type="text" class="form-control @error('nomor_sertifikat') is-invalid @enderror" 
                                           id="nomor_sertifikat" name="nomor_sertifikat" 
                                           value="{{ old('nomor_sertifikat', 'SRT-' . date('Y') . '-' . strtoupper(\Illuminate\Support\Str::random(8))) }}" 
                                           placeholder="Kosongkan untuk generate otomatis">
                                </div>
                                <small class="text-muted">Biarkan kosong untuk generate otomatis.</small>
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
                                           value="{{ old('nama_sertifikat') }}" 
                                           placeholder="Contoh: Sertifikat Pelatihan Digital Marketing" required>
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
                                              placeholder="Deskripsi sertifikat">{{ old('deskripsi') }}</textarea>
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
                                           value="{{ old('tanggal_terbit', date('Y-m-d')) }}" required>
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
                                           value="{{ old('tanggal_berlaku_sampai') }}">
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
                                           value="{{ old('penerbit', config('app.name', 'SIPADU')) }}" 
                                           placeholder="Nama penerbit sertifikat" required>
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
                                        <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="revoked" {{ old('status') == 'revoked' ? 'selected' : '' }}>Revoked</option>
                                        <option value="expired" {{ old('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                                    </select>
                                </div>
                                @error('status')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- File Sertifikat -->
                            <div class="col-12">
                                <label for="file_path" class="form-label fw-semibold">File Sertifikat</label>
                                <input type="file" class="form-control @error('file_path') is-invalid @enderror" 
                                       id="file_path" name="file_path" accept=".pdf,.jpg,.jpeg,.png">
                                <small class="text-muted">Max 5MB. Supported: PDF, JPG, JPEG, PNG</small>
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
                                           value="{{ old('tanda_tangan_digital') }}" 
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
                                              placeholder="Catatan tambahan (opsional)">{{ old('catatan') }}</textarea>
                                </div>
                                @error('catatan')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Submit Buttons -->
                            <div class="col-12 mt-4">
                                <hr class="my-2">
                                <div class="d-flex flex-wrap gap-2 align-items-center justify-content-between">
                                    <div>
                                        <a href="{{ route('admin.sertifikat.index') }}" class="btn btn-outline-secondary">
                                            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
                                        </a>
                                    </div>
                                    <div class="d-flex gap-2 flex-wrap">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-save me-1"></i> Simpan
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
        // Auto generate nomor sertifikat
        const nomorInput = document.getElementById('nomor_sertifikat');
        const generateBtn = document.createElement('button');
        generateBtn.type = 'button';
        generateBtn.className = 'btn btn-outline-secondary';
        generateBtn.innerHTML = '<i class="bi bi-arrow-clockwise"></i>';
        generateBtn.title = 'Generate nomor sertifikat';

        if (nomorInput) {
            const parent = nomorInput.closest('.input-group');
            if (parent) {
                parent.appendChild(generateBtn);
                
                generateBtn.addEventListener('click', function() {
                    const year = new Date().getFullYear();
                    const random = Math.random().toString(36).substring(2, 10).toUpperCase();
                    nomorInput.value = 'SRT-' + year + '-' + random;
                });
            }
        }

        // Preview file
        const fileInput = document.getElementById('file_path');
        if (fileInput) {
            fileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        // You can add preview logic here if needed
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        // Tanggal terbit cannot be after tanggal berlaku sampai
        const tanggalTerbit = document.getElementById('tanggal_terbit');
        const tanggalBerlaku = document.getElementById('tanggal_berlaku_sampai');

        if (tanggalTerbit && tanggalBerlaku) {
            tanggalTerbit.addEventListener('change', function() {
                if (tanggalBerlaku.value && tanggalBerlaku.value < this.value) {
                    tanggalBerlaku.value = '';
                }
                tanggalBerlaku.min = this.value;
            });
        }
    });
</script>
@endpush
@endsection