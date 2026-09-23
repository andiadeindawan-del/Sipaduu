@extends('layouts.admin')

@section('title', 'Edit User')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-person"></i></span>
        <div>
            <p class="eyebrow">Akun</p>
            <h1 class="h3 mb-0">Edit User</h1>
            <p class="text-muted mb-0">Edit profil user: <strong>{{ $user->nama ?? $user->name ?? 'Unknown' }}</strong></p>
        </div>
    </div>
    <div class="heading-actions">
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">
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
                <i class="bi bi-exclamation-circle-fill me-2"></i>
                Terdapat kesalahan pada form. Silakan periksa kembali.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <!-- Keterangan Form -->
            <div class="panel mb-4">
                <div class="panel-header">
                    <div>
                        <h5 class="section-title"><i class="bi bi-info-circle"></i> Keterangan Form</h5>
                        <p class="text-muted small mb-0">Perhatikan tanda pada setiap field untuk mengetahui kewajiban pengisian.</p>
                    </div>
                    <div class="d-flex gap-3 align-items-center">
                        <span><span class="text-danger fw-bold">*</span> <span class="text-muted">Wajib Diisi</span></span>
                        <span><span class="text-muted">(Opsional)</span> <span class="text-muted">Boleh Dikosongkan</span></span>
                    </div>
                </div>
            </div>

            <!-- Status Kelengkapan Profil -->
            <div class="panel mb-4">
                <div class="panel-header">
                    <div>
                        <h5 class="section-title"><i class="bi bi-check-circle"></i> Status Kelengkapan Profil</h5>
                        <p class="text-muted small mb-0">Pastikan semua data <span class="text-danger fw-bold">wajib</span> terisi untuk memudahkan verifikasi pendaftaran.</p>
                    </div>
                    <div>
                        @if($user->is_profil_lengkap)
                            <span class="badge bg-success fs-6 py-2 px-3">
                                <i class="bi bi-check-circle-fill me-1"></i> Profil Lengkap
                            </span>
                        @else
                            <span class="badge bg-danger fs-6 py-2 px-3">
                                <i class="bi bi-exclamation-circle-fill me-1"></i> Profil Belum Lengkap
                            </span>
                        @endif
                    </div>
                </div>
                <div class="p-3">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi {{ $user->name ? 'bi-check-circle-fill text-success' : 'bi-x-circle-fill text-danger' }}"></i>
                                <small>Nama</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi {{ $user->email ? 'bi-check-circle-fill text-success' : 'bi-x-circle-fill text-danger' }}"></i>
                                <small>Email</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi {{ $user->nik ? 'bi-check-circle-fill text-success' : 'bi-x-circle-fill text-danger' }}"></i>
                                <small>NIK</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi {{ $user->no_telepon ? 'bi-check-circle-fill text-success' : 'bi-x-circle-fill text-danger' }}"></i>
                                <small>No. Telepon</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi {{ $user->ktp_file ? 'bi-check-circle-fill text-success' : 'bi-x-circle-fill text-danger' }}"></i>
                                <small>KTP</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi {{ $user->nama_usaha ? 'bi-check-circle-fill text-success' : 'bi-x-circle-fill text-danger' }}"></i>
                                <small>Nama Usaha</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi {{ $user->npwp_usaha ? 'bi-check-circle-fill text-success' : 'bi-x-circle-fill text-danger' }}"></i>
                                <small>NPWP Usaha</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi {{ $user->nib ? 'bi-check-circle-fill text-success' : 'bi-x-circle-fill text-danger' }}"></i>
                                <small>NIB</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel mb-4">
                <div class="panel-header">
                    <h5 class="section-title"><i class="bi bi-person-lines-fill"></i> Edit Profil User</h5>
                    <p class="text-muted small mb-0">Lengkapi data user dengan benar. <span class="text-danger fw-bold">*</span> <span class="text-muted">wajib diisi</span></p>
                </div>
                
                <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="p-4">
                        <ul class="nav nav-tabs nav-tabs-custom mb-4" id="profileTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="pribadi-tab" data-bs-toggle="tab" data-bs-target="#pribadi" type="button" role="tab">
                                    <i class="bi bi-person me-1"></i> Pribadi
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="usaha-tab" data-bs-toggle="tab" data-bs-target="#usaha" type="button" role="tab">
                                    <i class="bi bi-building me-1"></i> Usaha
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="digital-tab" data-bs-toggle="tab" data-bs-target="#digital" type="button" role="tab">
                                    <i class="bi bi-globe2 me-1"></i> Saluran Pemasaran Online
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="tambahan-tab" data-bs-toggle="tab" data-bs-target="#tambahan" type="button" role="tab">
                                    <i class="bi bi-file-earmark-text me-1"></i> Kebutuhan Pelatihan
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="dokumen-tab" data-bs-toggle="tab" data-bs-target="#dokumen" type="button" role="tab">
                                    <i class="bi bi-folder2-open me-1"></i> Dokumen
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content" id="profileTabsContent">
                            <!-- TAB DATA PRIBADI -->
                            <div class="tab-pane fade show active" id="pribadi" role="tabpanel">
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('nama') is-invalid @enderror" name="nama" value="{{ old('nama', $user->nama) }}">
                                        @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email) }}">
                                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">NIK <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="nik" value="{{ old('nik', $user->nik) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Nomor HP/Telepon <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="no_telepon" value="{{ old('no_telepon', $user->no_telepon) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Tempat Lahir <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="tempat_lahir" value="{{ old('tempat_lahir', $user->tempat_lahir) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Tanggal Lahir <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="tanggal_lahir" value="{{ old('tanggal_lahir', $user->tanggal_lahir) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">NPWP Usaha <span class="text-muted fw-normal">(Opsional)</span></label>
                                        <input type="text" class="form-control" name="npwp_usaha" value="{{ old('npwp_usaha', $user->npwp_usaha) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Jenis Kelamin <span class="text-danger">*</span></label>
                                        <select class="form-select" name="jenis_kelamin">
                                            <option value="">Pilih...</option>
                                            <option value="L" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="P" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Role <span class="text-danger">*</span></label>
                                        <select class="form-select @error('role') is-invalid @enderror" name="role">
                                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                            <option value="trainer" {{ old('role', $user->role) == 'trainer' ? 'selected' : '' }}>Trainer</option>
                                            <option value="peserta" {{ old('role', $user->role) == 'peserta' ? 'selected' : '' }}>Peserta</option>
                                        </select>
                                        @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Status Akun <span class="text-danger">*</span></label>
                                        <select class="form-select @error('status') is-invalid @enderror" name="status">
                                            <option value="aktif" {{ old('status', $user->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                            <option value="nonaktif" {{ old('status', $user->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                        </select>
                                        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Agama <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="agama" value="{{ old('agama', $user->agama) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Status Pernikahan <span class="text-muted fw-normal">(Opsional)</span></label>
                                        <select class="form-select" name="status_pernikahan">
                                            <option value="">Pilih...</option>
                                            <option value="Belum Menikah" {{ old('status_pernikahan', $user->status_pernikahan) == 'Belum Menikah' ? 'selected' : '' }}>Belum Menikah</option>
                                            <option value="Menikah" {{ old('status_pernikahan', $user->status_pernikahan) == 'Menikah' ? 'selected' : '' }}>Menikah</option>
                                            <option value="Cerai Mati" {{ old('status_pernikahan', $user->status_pernikahan) == 'Cerai Mati' ? 'selected' : '' }}>Cerai Mati</option>
                                            <option value="Cerai Hidup" {{ old('status_pernikahan', $user->status_pernikahan) == 'Cerai Hidup' ? 'selected' : '' }}>Cerai Hidup</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Pendidikan Terakhir <span class="text-danger">*</span></label>
                                        <select class="form-select" name="pendidikan_terakhir">
                                            <option value="">Pilih...</option>
                                            <option value="SD" {{ old('pendidikan_terakhir', $user->pendidikan_terakhir) == 'SD' ? 'selected' : '' }}>SD</option>
                                            <option value="SMP" {{ old('pendidikan_terakhir', $user->pendidikan_terakhir) == 'SMP' ? 'selected' : '' }}>SMP</option>
                                            <option value="SMA" {{ old('pendidikan_terakhir', $user->pendidikan_terakhir) == 'SMA' ? 'selected' : '' }}>SMA</option>
                                            <option value="D1" {{ old('pendidikan_terakhir', $user->pendidikan_terakhir) == 'D1' ? 'selected' : '' }}>D1</option>
                                            <option value="D2" {{ old('pendidikan_terakhir', $user->pendidikan_terakhir) == 'D2' ? 'selected' : '' }}>D2</option>
                                            <option value="D3" {{ old('pendidikan_terakhir', $user->pendidikan_terakhir) == 'D3' ? 'selected' : '' }}>D3</option>
                                            <option value="S1" {{ old('pendidikan_terakhir', $user->pendidikan_terakhir) == 'S1' ? 'selected' : '' }}>S1</option>
                                            <option value="S2" {{ old('pendidikan_terakhir', $user->pendidikan_terakhir) == 'S2' ? 'selected' : '' }}>S2</option>
                                            <option value="S3" {{ old('pendidikan_terakhir', $user->pendidikan_terakhir) == 'S3' ? 'selected' : '' }}>S3</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Departemen <span class="text-muted">(Opsional)</span></label>
                                        <input type="text" class="form-control" name="departemen" value="{{ old('departemen', $user->departemen) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Jabatan <span class="text-muted">(Opsional)</span></label>
                                        <input type="text" class="form-control" name="jabatan" value="{{ old('jabatan', $user->jabatan) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Password Baru <span class="text-muted">(Opsional)</span></label>
                                        <input type="password" class="form-control" name="password" placeholder="Kosongkan jika tidak diubah">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Konfirmasi Password Baru</label>
                                        <input type="password" class="form-control" name="password_confirmation" placeholder="Kosongkan jika tidak diubah">
                                    </div>
                                    
                                    <div class="col-12 mt-4">
                                        <h6 class="fw-bold mb-3 border-bottom pb-2"><i class="bi bi-house me-2"></i>Alamat Domisili <span class="text-danger">*</span> <span class="text-muted fw-normal">(Semua wajib diisi)</span></h6>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Provinsi <span class="text-danger">*</span></label>
                                        <select class="form-select @error('provinsi') is-invalid @enderror" id="provinsi" name="provinsi">
                                            <option value="Sulawesi Barat">Sulawesi Barat</option>
                                        </select>
                                        @error('provinsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Kabupaten/Kota <span class="text-danger">*</span></label>
                                        <select class="form-select @error('kabupaten') is-invalid @enderror" id="kabupaten" name="kabupaten">
                                            <option value="">Pilih Kabupaten/Kota</option>
                                        </select>
                                        @error('kabupaten') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Kecamatan <span class="text-danger">*</span></label>
                                        <select class="form-select @error('kecamatan') is-invalid @enderror" id="kecamatan" name="kecamatan">
                                            <option value="">Pilih Kecamatan</option>
                                        </select>
                                        @error('kecamatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Desa/Kelurahan <span class="text-danger">*</span></label>
                                        <select class="form-select @error('desa') is-invalid @enderror" id="desa" name="desa">
                                            <option value="">Pilih Desa/Kelurahan</option>
                                        </select>
                                        @error('desa') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-semibold">Alamat Detail <span class="text-danger">*</span></label>
                                        <textarea class="form-control" name="alamat_lengkap" rows="2" placeholder="Nama Jalan, RT/RW, Dusun">{{ old('alamat_lengkap', $user->alamat_lengkap) }}</textarea>
                                    </div>
                                </div>
                            
                                    <div class="mt-4 d-flex justify-content-end pb-3">
                                        <button type="submit" class="btn btn-primary px-4">
                                            <i class="bi bi-save me-1"></i> Simpan Perubahan
                                        </button>
                                    </div></div>

                            <!-- TAB USAHA -->

                                
                                    <div class="mt-4 d-flex justify-content-end pb-3">
                                        <button type="submit" class="btn btn-primary px-4">
                                            <i class="bi bi-save me-1"></i> Simpan Perubahan
                                        </button>
                                    </div>

                                <div class="tab-pane fade" id="usaha" role="tabpanel">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <h6 class="fw-bold mb-2 border-bottom pb-2"><i class="bi bi-briefcase me-2"></i>USAHA <span class="text-danger">*</span></h6>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Nama Usaha <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="nama_usaha" value="{{ old('nama_usaha', $user->nama_usaha) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">NIB <span class="text-muted">(Opsional)</span></label>
                                        <input type="text" class="form-control" name="nib" value="{{ old('nib', $user->nib) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Status Usaha <span class="text-danger">*</span></label>
                                        <select class="form-select" name="status_usaha">
                                            <option value="">-- Pilih --</option>
                                            <option value="Aktif" {{ old('status_usaha', $user->status_usaha) == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                            <option value="Tidak Aktif" {{ old('status_usaha', $user->status_usaha) == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Bentuk Usaha <span class="text-danger">*</span></label>
                                        <select class="form-select" name="bentuk_usaha">
                                            <option value="">-- Pilih --</option>
                                            <option value="Perorangan" {{ old('bentuk_usaha', $user->bentuk_usaha) == 'Perorangan' ? 'selected' : '' }}>Perorangan</option>
                                            <option value="PT Perorangan" {{ old('bentuk_usaha', $user->bentuk_usaha) == 'PT Perorangan' ? 'selected' : '' }}>PT Perorangan</option>
                                            <option value="UD" {{ old('bentuk_usaha', $user->bentuk_usaha) == 'UD' ? 'selected' : '' }}>UD</option>
                                            <option value="CV" {{ old('bentuk_usaha', $user->bentuk_usaha) == 'CV' ? 'selected' : '' }}>CV</option>
                                            <option value="PT" {{ old('bentuk_usaha', $user->bentuk_usaha) == 'PT' ? 'selected' : '' }}>PT</option>
                                            <option value="Koperasi" {{ old('bentuk_usaha', $user->bentuk_usaha) == 'Koperasi' ? 'selected' : '' }}>Koperasi</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Tanggal Berdiri Usaha / Mulai Usaha <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="tanggal_berdiri" value="{{ old('tanggal_berdiri', $user->tanggal_berdiri) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Jabatan/Posisi <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="jabatan_usaha" value="{{ old('jabatan_usaha', $user->jabatan_usaha) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Merek Dagang <span class="text-muted">(Opsional)</span></label>
                                        <input type="text" class="form-control" name="merek_produk" value="{{ old('merek_produk', $user->merek_produk) }}">
                                    </div>

                                    <div class="col-12 mt-4">
                                        <h6 class="fw-bold mb-3 border-bottom pb-2"><i class="bi bi-geo-alt me-2"></i>Alamat Kontak Usaha <span class="text-danger">*</span></h6>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Kontak Usaha (No. Telepon/HP) <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="no_telepon_usaha" value="{{ old('no_telepon_usaha', $user->no_telepon_usaha) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Email Usaha <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" name="email_usaha" value="{{ old('email_usaha', $user->email_usaha) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Provinsi Usaha <span class="text-danger">*</span></label>
                                        <select class="form-select @error('provinsi_usaha') is-invalid @enderror" id="provinsi_usaha" name="provinsi_usaha">
<option value="Sulawesi Barat">Sulawesi Barat</option>
</select>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Kabupaten/Kota Usaha <span class="text-danger">*</span></label>
                                        <select class="form-select @error('kabupaten_usaha') is-invalid @enderror" id="kabupaten_usaha" name="kabupaten_usaha">
<option value="">Pilih Kabupaten/Kota</option>
</select>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Kecamatan Usaha <span class="text-danger">*</span></label>
                                        <select class="form-select @error('kecamatan_usaha') is-invalid @enderror" id="kecamatan_usaha" name="kecamatan_usaha">
<option value="">Pilih Kecamatan</option>
</select>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Desa/Kelurahan Usaha <span class="text-danger">*</span></label>
                                        <select class="form-select @error('desa_usaha') is-invalid @enderror" id="desa_usaha" name="desa_usaha">
<option value="">Pilih Desa/Kelurahan</option>
</select>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-semibold">Alamat Lengkap Usaha <span class="text-danger">*</span></label>
                                        <textarea class="form-control" name="alamat_usaha" rows="2">{{ old('alamat_usaha', $user->alamat_usaha) }}</textarea>
                                    </div>

                                    <div class="col-12 mt-4">
                                        <h6 class="fw-bold mb-2 border-bottom pb-2"><i class="bi bi-tags me-2"></i>BIDANG USAHA <span class="text-danger">*</span></h6>
                                        <p class="text-muted small mb-3">Peserta wajib memiliki minimal satu KBLI Utama. Anda dapat menambahkan beberapa jenis usaha lain yang relevan.</p>
                                        
                                        <div id="kbli-repeater-container"></div>
                                        
                                        <div class="mt-3 mb-4">
                                            <button type="button" class="btn btn-sm btn-outline-primary fw-bold" id="btn-add-usaha">
                                                <i class="bi bi-plus-lg"></i> Tambahkan Usaha Lainnya
                                            </button>
                                        </div>
                                    </div>

                                    <div class="col-12 mt-2">
                                        <h6 class="fw-bold mb-3 border-bottom pb-2"><i class="bi bi-cash-coin me-2"></i>Modal dan Produksi <span class="text-danger">*</span></h6>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Sumber Pendanaan / Modal <span class="text-muted">(Opsional)</span></label>
                                        <select class="form-select" name="modal_usaha">
                                            <option value="">-- Pilih --</option>
                                            <option value="Modal Sendiri" {{ old('modal_usaha', $user->modal_usaha) == 'Modal Sendiri' ? 'selected' : '' }}>Modal Sendiri</option>
                                            <option value="Pinjaman Bank" {{ old('modal_usaha', $user->modal_usaha) == 'Pinjaman Bank' ? 'selected' : '' }}>Pinjaman Bank</option>
                                            <option value="Pinjaman Koperasi" {{ old('modal_usaha', $user->modal_usaha) == 'Pinjaman Koperasi' ? 'selected' : '' }}>Pinjaman Koperasi</option>
                                            <option value="Bantuan Pemerintah" {{ old('modal_usaha', $user->modal_usaha) == 'Bantuan Pemerintah' ? 'selected' : '' }}>Bantuan Pemerintah</option>
                                            <option value="Lainnya" {{ old('modal_usaha', $user->modal_usaha) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Modal Usaha (Rp) <span class="text-muted">(Opsional)</span></label>
                                        <input type="number" class="form-control" name="nilai_modal" value="{{ old('nilai_modal', $user->nilai_modal) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Omzet Per Tahun (Rp) <span class="text-muted">(Opsional)</span></label>
                                        <input type="number" class="form-control" name="nilai_omzet" value="{{ old('nilai_omzet', $user->nilai_omzet) }}">
                                    </div>

                                    <div class="col-12 mt-3">
                                        <h6 class="fw-bold mb-2 border-bottom pb-2"><i class="bi bi-people me-2"></i>Tenaga Kerja <span class="text-danger">*</span></h6>
                                        <p class="text-muted small mb-3"><i class="bi bi-info-circle me-1"></i> Isi jumlah karyawan (isi 0 jika tidak ada).</p>
                                        
                                        <div class="table-responsive">
                                            <table class="table table-bordered align-middle text-center small mb-2">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th style="width: 40%;">Jenis Karyawan</th>
                                                        <th style="width: 25%;">Laki-laki <span class="text-danger">*</span></th>
                                                        <th style="width: 25%;">Perempuan <span class="text-danger">*</span></th>
                                                        <th style="width: 10%;">Subtotal</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="text-start fw-semibold text-secondary">Karyawan Tetap</td>
                                                        <td>
                                                            <input type="number" class="form-control form-control-sm text-center karyawan-input" id="tetap_laki_laki" name="karyawan_tetap_laki_laki" value="{{ old('karyawan_tetap_laki_laki', $user->karyawan_tetap_laki_laki ?? 0) }}" min="0" step="1">
                                                        </td>
                                                        <td>
                                                            <input type="number" class="form-control form-control-sm text-center karyawan-input" id="tetap_perempuan" name="karyawan_tetap_perempuan" value="{{ old('karyawan_tetap_perempuan', $user->karyawan_tetap_perempuan ?? 0) }}" min="0" step="1">
                                                        </td>
                                                        <td>
                                                            <input type="number" class="form-control form-control-sm text-center bg-light fw-bold" id="total_tetap" readonly value="{{ old('total_karyawan_tetap', $user->total_karyawan_tetap ?? 0) }}">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start fw-semibold text-secondary">Karyawan Tidak Tetap</td>
                                                        <td>
                                                            <input type="number" class="form-control form-control-sm text-center karyawan-input" id="tidak_tetap_laki_laki" name="karyawan_tidak_tetap_laki_laki" value="{{ old('karyawan_tidak_tetap_laki_laki', $user->karyawan_tidak_tetap_laki_laki ?? 0) }}" min="0" step="1">
                                                        </td>
                                                        <td>
                                                            <input type="number" class="form-control form-control-sm text-center karyawan-input" id="tidak_tetap_perempuan" name="karyawan_tidak_tetap_perempuan" value="{{ old('karyawan_tidak_tetap_perempuan', $user->karyawan_tidak_tetap_perempuan ?? 0) }}" min="0" step="1">
                                                        </td>
                                                        <td>
                                                            <input type="number" class="form-control form-control-sm text-center bg-light fw-bold" id="total_tidak_tetap" readonly value="{{ old('total_karyawan_tidak_tetap', $user->total_karyawan_tidak_tetap ?? 0) }}">
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center bg-light p-2 px-3 rounded border">
                                            <span class="fw-bold text-dark small">TOTAL TENAGA KERJA:</span>
                                            <span class="badge bg-primary fs-6 fw-bold" id="grand_total">{{ old('total_tenaga_kerja', $user->total_tenaga_kerja ?? 0) }} Orang</span>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6 mt-3">
                                        <label class="form-label fw-semibold">Kapasitas Produksi <span class="text-muted">(Opsional)</span></label>
                                        <input type="text" class="form-control" name="kapasitas_produksi" value="{{ old('kapasitas_produksi', $user->kapasitas_produksi) }}">
                                    </div>
                                </div>
                            
                                    <div class="mt-4 d-flex justify-content-end pb-3">
                                        <button type="submit" class="btn btn-primary px-4">
                                            <i class="bi bi-save me-1"></i> Simpan Perubahan
                                        </button>
                                    </div></div>

                            <!-- TAB SALURAN PEMASARAN ONLINE -->

                                
                                    <div class="mt-4 d-flex justify-content-end pb-3">
                                        <button type="submit" class="btn btn-primary px-4">
                                            <i class="bi bi-save me-1"></i> Simpan Perubahan
                                        </button>
                                    </div>

                                <div class="tab-pane fade" id="digital" role="tabpanel">
                                <div class="row g-3">
                                    <!-- INFORMASI USAHA ONLINE -->
                                    <div class="col-12 mt-4">
                                        <div class="card bg-light border-0 shadow-sm">
                                            <div class="fw-bold mb-3 border-bottom pb-2">
                                                <i class="bi bi-globe me-2"></i>INFORMASI USAHA ONLINE
                                            </div>
                                            <div class="card-body">
                                                <div class="row g-3">
                                                    <div class="col-12">
                                                        <label class="form-label fw-semibold">Judul Usaha <span class="text-muted">(Opsional)</span></label>
                                                        <input type="text" class="form-control" name="judul_usaha_online" value="{{ old('judul_usaha_online', $user->judul_usaha_online) }}" placeholder="Contoh: Toko Kue Andi">
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label fw-semibold">Website Usaha <span class="text-muted">(Opsional)</span></label>
                                                        <input type="url" class="form-control" name="website_usaha" value="{{ old('website_usaha', $user->website_usaha) }}" placeholder="https://www.contoh.com">
                                                    </div>
                                                    
                                                    <!-- Media Sosial - Table Style -->
                                                    <div class="col-12 mt-3">
                                                        <h6 class="fw-bold mb-2 border-bottom pb-1"><i class="bi bi-share me-2"></i>Media Sosial <span class="text-muted fw-normal">(Opsional)</span></h6>
                                                        <p class="text-muted small mb-2">Masukkan URL media sosial usaha Anda.</p>
                                                        
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered align-middle text-center small mb-2">
                                                                <thead class="table-light">
                                                                    <tr>
                                                                        <th style="width: 30%;">Platform</th>
                                                                        <th style="width: 70%;">Link / URL <span class="text-muted fw-normal">(Opsional)</span></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="text-start fw-semibold text-primary">
                                                                            <i class="bi bi-facebook me-1"></i> Facebook
                                                                        </td>
                                                                        <td>
                                                                            <input type="url" class="form-control form-control-sm" name="facebook_usaha" value="{{ old('facebook_usaha', $user->facebook_usaha) }}" placeholder="https://facebook.com/contoh">
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="text-start fw-semibold text-danger">
                                                                            <i class="bi bi-instagram me-1"></i> Instagram
                                                                        </td>
                                                                        <td>
                                                                            <input type="url" class="form-control form-control-sm" name="instagram_usaha" value="{{ old('instagram_usaha', $user->instagram_usaha) }}" placeholder="https://instagram.com/contoh">
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="text-start fw-semibold text-dark">
                                                                            <i class="bi bi-tiktok me-1"></i> TikTok
                                                                        </td>
                                                                        <td>
                                                                            <input type="url" class="form-control form-control-sm" name="tiktok_usaha" value="{{ old('tiktok_usaha', $user->tiktok_usaha) }}" placeholder="https://tiktok.com/@contoh">
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="text-start fw-semibold text-secondary">
                                                                            <i class="bi bi-youtube me-1"></i> YouTube
                                                                        </td>
                                                                        <td>
                                                                            <input type="url" class="form-control form-control-sm" name="youtube_usaha" value="{{ old('youtube_usaha', $user->youtube_usaha) }}" placeholder="https://youtube.com/@contoh">
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="text-start fw-semibold text-success">
                                                                            <i class="bi bi-whatsapp me-1"></i> WhatsApp Business
                                                                        </td>
                                                                        <td>
                                                                            <input type="url" class="form-control form-control-sm" name="whatsapp_usaha" value="{{ old('whatsapp_usaha', $user->whatsapp_usaha) }}" placeholder="https://wa.me/08123456789">
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>

                                                    <!-- Marketplace - Table Style -->
                                                    <div class="col-12 mt-3">
                                                        <h6 class="fw-bold mb-2 border-bottom pb-1"><i class="bi bi-shop me-2"></i>Marketplace <span class="text-muted fw-normal">(Opsional)</span></h6>
                                                        <p class="text-muted small mb-2">Masukkan link toko Anda di berbagai marketplace.</p>
                                                        
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered align-middle text-center small mb-2">
                                                                <thead class="table-light">
                                                                    <tr>
                                                                        <th style="width: 30%;">Marketplace</th>
                                                                        <th style="width: 70%;">Link / URL <span class="text-muted fw-normal">(Opsional)</span></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="text-start fw-semibold text-warning">
                                                                            <i class="bi bi-shop me-1"></i> Shopee
                                                                        </td>
                                                                        <td>
                                                                            <input type="url" class="form-control form-control-sm" name="shopee" value="{{ old('shopee', $user->shopee) }}" placeholder="https://shopee.co.id/contoh">
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="text-start fw-semibold text-success">
                                                                            <i class="bi bi-shop me-1"></i> Tokopedia
                                                                        </td>
                                                                        <td>
                                                                            <input type="url" class="form-control form-control-sm" name="tokopedia" value="{{ old('tokopedia', $user->tokopedia) }}" placeholder="https://www.tokopedia.com/contoh">
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="text-start fw-semibold text-danger">
                                                                            <i class="bi bi-shop me-1"></i> Lazada
                                                                        </td>
                                                                        <td>
                                                                            <input type="url" class="form-control form-control-sm" name="lazada" value="{{ old('lazada', $user->lazada) }}" placeholder="https://www.lazada.co.id/shop/contoh">
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="text-start fw-semibold text-info">
                                                                            <i class="bi bi-shop me-1"></i> Blibli
                                                                        </td>
                                                                        <td>
                                                                            <input type="url" class="form-control form-control-sm" name="blibli" value="{{ old('blibli', $user->blibli) }}" placeholder="https://www.blibli.com/merchant/contoh">
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="text-start fw-semibold text-secondary">
                                                                            <i class="bi bi-shop me-1"></i> Bukalapak
                                                                        </td>
                                                                        <td>
                                                                            <input type="url" class="form-control form-control-sm" name="bukalapak" value="{{ old('bukalapak', $user->bukalapak) }}" placeholder="https://www.bukalapak.com/contoh">
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>

                                                    <!-- Marketplace Lainnya -->
                                                    <div class="col-12 mt-3 border-top pt-3">
                                                        <label class="form-label fw-bold">Marketplace Lainnya <span class="text-muted fw-normal">(Opsional)</span></label>
                                                        <div id="marketplace-container">
                                                            @php
                                                                $oldM = old('marketplace_lainnya_nama');
                                                                $oldL = old('marketplace_lainnya_link');
                                                                $dbM = $user->marketplace_lainnya ?? [];
                                                            @endphp

                                                            @if($oldM && is_array($oldM))
                                                                @foreach($oldM as $idx => $n)
                                                                    <div class="row g-2 mb-2 mp-row">
                                                                        <div class="col-md-5">
                                                                            <input type="text" class="form-control" name="marketplace_lainnya_nama[]" value="{{ $n }}" placeholder="Nama Marketplace (cth: Bukalapak)">
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <input type="url" class="form-control" name="marketplace_lainnya_link[]" value="{{ $oldL[$idx] ?? '' }}" placeholder="Link Marketplace (https://...)">
                                                                        </div>
                                                                        <div class="col-md-1 d-flex align-items-center">
                                                                            <button type="button" class="btn btn-danger btn-sm w-100 remove-mp"><i class="bi bi-trash"></i></button>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            @elseif(!empty($dbM) && is_array($dbM))
                                                                @foreach($dbM as $item)
                                                                    <div class="row g-2 mb-2 mp-row">
                                                                        <div class="col-md-5">
                                                                            <input type="text" class="form-control" name="marketplace_lainnya_nama[]" value="{{ $item['nama'] ?? '' }}" placeholder="Nama Marketplace (cth: Bukalapak)">
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <input type="url" class="form-control" name="marketplace_lainnya_link[]" value="{{ $item['link'] ?? '' }}" placeholder="Link Marketplace (https://...)">
                                                                        </div>
                                                                        <div class="col-md-1 d-flex align-items-center">
                                                                            <button type="button" class="btn btn-danger btn-sm w-100 remove-mp"><i class="bi bi-trash"></i></button>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                        <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="add-mp-btn"><i class="bi bi-plus-circle me-1"></i> Tambahkan Marketplace Lainnya</button>
                                                    </div>

                                                    <div class="col-12 mt-3">
                                                        <label class="form-label fw-semibold">Wilayah Pemasaran <span class="text-muted">(Opsional)</span></label>
                                                        <select class="form-select" name="wilayah_pemasaran">
                                                            <option value="">Pilih Wilayah Pemasaran</option>
                                                            <option value="Dalam Satu Kabupaten" {{ old('wilayah_pemasaran', $user->wilayah_pemasaran) == 'Dalam Satu Kabupaten' ? 'selected' : '' }}>Dalam Satu Kabupaten</option>
                                                            <option value="Lintas Kabupaten" {{ old('wilayah_pemasaran', $user->wilayah_pemasaran) == 'Lintas Kabupaten' ? 'selected' : '' }}>Lintas Kabupaten</option>
                                                            <option value="Lintas Provinsi" {{ old('wilayah_pemasaran', $user->wilayah_pemasaran) == 'Lintas Provinsi' ? 'selected' : '' }}>Lintas Provinsi</option>
                                                            <option value="Nasional" {{ old('wilayah_pemasaran', $user->wilayah_pemasaran) == 'Nasional' ? 'selected' : '' }}>Nasional</option>
                                                            <option value="Ekspor" {{ old('wilayah_pemasaran', $user->wilayah_pemasaran) == 'Ekspor' ? 'selected' : '' }}>Ekspor</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Informasi Operasional & Pemasaran -->
                                    <div class="col-12 mt-4">
                                        <h6 class="fw-bold mb-3 border-bottom pb-2"><i class="bi bi-info-square me-2"></i>Informasi Operasional & Pemasaran</h6>
                                    </div>
                                    
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Pengadaan Barang/Jasa <span class="text-muted">(Opsional)</span></label>
                                        <select class="form-select" name="pengadaan_barang">
                                            <option value="">Pilih Status</option>
                                            <option value="Pernah" {{ old('pengadaan_barang', $user->pengadaan_barang) == 'Pernah' ? 'selected' : '' }}>Pernah</option>
                                            <option value="Belum Pernah" {{ old('pengadaan_barang', $user->pengadaan_barang) == 'Belum Pernah' ? 'selected' : '' }}>Belum Pernah</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Akses Kredit <span class="text-muted">(Opsional)</span></label>
                                        <input type="text" class="form-control" name="akses_kredit" value="{{ old('akses_kredit', $user->akses_kredit) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Tabungan Usaha <span class="text-muted">(Opsional)</span></label>
                                        <select class="form-select" name="tabungan">
                                            <option value="">Pilih Status</option>
                                            <option value="Ya" {{ old('tabungan', $user->tabungan) == 'Ya' ? 'selected' : '' }}>Ya</option>
                                            <option value="Tidak" {{ old('tabungan', $user->tabungan) == 'Tidak' ? 'selected' : '' }}>Tidak</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Sertifikasi Produk <span class="text-muted">(Opsional)</span></label>
                                        <input type="text" class="form-control" name="sertifikasi_produk" value="{{ old('sertifikasi_produk', $user->sertifikasi_produk) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Status Ekspor <span class="text-muted">(Opsional)</span></label>
                                        <input type="text" class="form-control" name="status_ekspor" value="{{ old('status_ekspor', $user->status_ekspor) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Negara Tujuan Ekspor <span class="text-muted">(Opsional)</span></label>
                                        <input type="text" class="form-control" name="negara_ekspor" value="{{ old('negara_ekspor', $user->negara_ekspor) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Perizinan Usaha <span class="text-muted">(Opsional)</span></label>
                                        <input type="text" class="form-control" name="perizinan_usaha" value="{{ old('perizinan_usaha', $user->perizinan_usaha) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Jangkauan Pemasaran <span class="text-muted">(Opsional)</span></label>
                                        <input type="text" class="form-control" name="jangkauan_pemasaran" value="{{ old('jangkauan_pemasaran', $user->jangkauan_pemasaran) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Lokasi Pemasaran <span class="text-muted">(Opsional)</span></label>
                                        <input type="text" class="form-control" name="lokasi_pemasaran" value="{{ old('lokasi_pemasaran', $user->lokasi_pemasaran) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Metode Ekspor <span class="text-muted">(Opsional)</span></label>
                                        <input type="text" class="form-control" name="metode_ekspor" value="{{ old('metode_ekspor', $user->metode_ekspor) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Volume Ekspor <span class="text-muted">(Opsional)</span></label>
                                        <input type="text" class="form-control" name="volume_ekspor" value="{{ old('volume_ekspor', $user->volume_ekspor) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Nilai Ekspor (Rp) <span class="text-muted">(Opsional)</span></label>
                                        <input type="number" class="form-control" name="nilai_ekspor" value="{{ old('nilai_ekspor', $user->nilai_ekspor) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Pasokan Bahan Baku <span class="text-muted">(Opsional)</span></label>
                                        <input type="text" class="form-control" name="pasok_bahan_baku" value="{{ old('pasok_bahan_baku', $user->pasok_bahan_baku) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Kemitraan <span class="text-muted">(Opsional)</span></label>
                                        <input type="text" class="form-control" name="kemitraan" value="{{ old('kemitraan', $user->kemitraan) }}">
                                    </div>
                                </div>
                            
                                    <div class="mt-4 d-flex justify-content-end pb-3">
                                        <button type="submit" class="btn btn-primary px-4">
                                            <i class="bi bi-save me-1"></i> Simpan Perubahan
                                        </button>
                                    </div></div>

                            <!-- TAB KEBUTUHAN PELATIHAN -->

                                
                                    <div class="mt-4 d-flex justify-content-end pb-3">
                                        <button type="submit" class="btn btn-primary px-4">
                                            <i class="bi bi-save me-1"></i> Simpan Perubahan
                                        </button>
                                    </div>

                                <div class="tab-pane fade" id="tambahan" role="tabpanel">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label fw-semibold">Permasalahan Usaha Saat Ini <span class="text-muted">(Opsional)</span></label>
                                        <p class="text-muted small mb-1"><i class="bi bi-info-circle me-1"></i> Jelaskan kendala atau masalah yang dihadapi dalam usaha.</p>
                                        <textarea class="form-control" name="permasalahan" rows="3">{{ old('permasalahan', $user->permasalahan) }}</textarea>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-semibold">Kebutuhan Diklat/Pelatihan <span class="text-muted">(Opsional)</span></label>
                                        <p class="text-muted small mb-1"><i class="bi bi-info-circle me-1"></i> Sebutkan jenis pelatihan yang dibutuhkan untuk pengembangan usaha.</p>
                                        <textarea class="form-control" name="kebutuhan_diklat" rows="3">{{ old('kebutuhan_diklat', $user->kebutuhan_diklat) }}</textarea>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Jenis Pelatihan Diikuti <span class="text-muted">(Opsional)</span></label>
                                        <input type="text" class="form-control" name="jenis_pelatihan_diikuti" value="{{ old('jenis_pelatihan_diikuti', $user->jenis_pelatihan_diikuti) }}">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-semibold">Masukan dan Saran <span class="text-muted">(Opsional)</span></label>
                                        <p class="text-muted small mb-1"><i class="bi bi-info-circle me-1"></i> Berikan saran atau masukan untuk perbaikan program pelatihan.</p>
                                        <textarea class="form-control" name="masukan_saran" rows="3">{{ old('masukan_saran', $user->masukan_saran) }}</textarea>
                                    </div>
                                    
                                    <div class="col-12 mt-3">
                                        <label class="form-label fw-semibold">Anggota Koperasi <span class="text-muted">(Opsional)</span></label>
                                        <input type="text" class="form-control" name="anggota_koperasi" placeholder="Nama koperasi jika menjadi anggota" value="{{ old('anggota_koperasi', $user->anggota_koperasi) }}">

                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Anggota Koperasi <span class="text-muted">(Opsional)</span></label>
                                        <input type="text" class="form-control" name="anggota_koperasi" placeholder="Nama koperasi jika menjadi anggota" value="{{ old('anggota_koperasi', $user->anggota_koperasi) }}">
                                    </div>
                                </div>
                            
                                    <div class="mt-4 d-flex justify-content-end pb-3">
                                        <button type="submit" class="btn btn-primary px-4">
                                            <i class="bi bi-save me-1"></i> Simpan Perubahan
                                        </button>
                                    </div></div>

                            <!-- TAB DOKUMEN -->

                                
                                    <div class="mt-4 d-flex justify-content-end pb-3">
                                        <button type="submit" class="btn btn-primary px-4">
                                            <i class="bi bi-save me-1"></i> Simpan Perubahan
                                        </button>
                                    </div>

                                <div class="tab-pane fade" id="dokumen" role="tabpanel">
                                <div class="row g-4">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Upload KTP <span class="text-danger">*</span></label>
                                        <p class="text-muted small mb-1"><i class="bi bi-info-circle me-1"></i> Wajib upload KTP untuk verifikasi identitas.</p>
                                        @if($user->ktp_file)
                                            <div class="mb-3">
                                                @php $ext = pathinfo($user->ktp_file, PATHINFO_EXTENSION); @endphp
                                                @if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png']))
                                                    <img src="{{ route('profile.document', ['type' => 'ktp', 'userId' => $user->id]) }}" alt="KTP" class="img-thumbnail" style="max-height: 150px;">
                                                @else
                                                    <a href="{{ route('profile.document', ['type' => 'ktp', 'userId' => $user->id]) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-file-earmark-pdf"></i> Lihat/Download KTP
                                                    </a>
                                                @endif
                                            </div>
                                        @endif
                                        <input type="file" class="form-control @error('ktp_file') is-invalid @enderror" name="ktp_file" accept=".jpg,.jpeg,.png,.pdf" {{ $user->ktp_file ? '' : 'required' }}>
                                        <small class="text-muted">Format: PDF, JPG, PNG. Maksimal 5MB. (Bisa pilih lebih dari 1 file)</small>
                                        @error('ktp_file') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Upload NIB <span class="text-muted">(Opsional)</span></label>
                                        <p class="text-muted small mb-1"><i class="bi bi-info-circle me-1"></i> Upload NIB jika sudah memiliki.</p>
                                        @if($user->nib_file)
                                            <div class="mb-3">
                                                @php $ext = pathinfo($user->nib_file, PATHINFO_EXTENSION); @endphp
                                                @if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png']))
                                                    <img src="{{ route('profile.document', ['type' => 'nib', 'userId' => $user->id]) }}" alt="NIB" class="img-thumbnail" style="max-height: 150px;">
                                                @else
                                                    <a href="{{ route('profile.document', ['type' => 'nib', 'userId' => $user->id]) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-file-earmark-pdf"></i> Lihat/Download NIB
                                                    </a>
                                                @endif
                                            </div>
                                        @endif
                                        <input type="file" class="form-control @error('nib_file') is-invalid @enderror" name="nib_file" accept=".jpg,.jpeg,.png,.pdf">
                                        <small class="text-muted">Format: PDF, JPG, PNG. Maksimal 5MB. (Bisa pilih lebih dari 1 file)</small>
                                        @error('nib_file') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Upload NPWP <span class="text-muted fw-normal">(Opsional)</span></label>
                                        <p class="text-muted small mb-1"><i class="bi bi-info-circle me-1"></i> Upload dokumen NPWP Anda.</p>
                                        @if($user->npwp_file && is_array($user->npwp_file))
                                            <div class="mb-3 d-flex flex-wrap gap-2">
                                                @foreach($user->npwp_file as $idx => $file)
                                                    @php $ext = pathinfo($file, PATHINFO_EXTENSION); @endphp
                                                    @if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png']))
                                                        <img src="{{ route('profile.document', ['type' => 'npwp', 'userId' => $user->id, 'index' => $idx]) }}" alt="NPWP" class="img-thumbnail" style="max-height: 150px;">
                                                    @else
                                                        <a href="{{ route('profile.document', ['type' => 'npwp', 'userId' => $user->id, 'index' => $idx]) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                            <i class="bi bi-file-earmark-pdf"></i> Dokumen NPWP {{ $idx + 1 }}
                                                        </a>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif
                                        <div id="npwp-upload-container">
                                            <div class="input-group mb-2">
                                                <input type="file" class="form-control @error('npwp_file') is-invalid @enderror" name="npwp_file[]" accept=".jpg,.jpeg,.png,.pdf">
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-secondary mb-2" id="add-npwp-btn"><i class="bi bi-plus-circle me-1"></i>Tambah Kolom File NPWP</button>
                                        <br>
                                        <small class="text-muted">Format: PDF, JPG, PNG. Maksimal 5MB.</small>
                                        @error('npwp_file') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Upload Foto Profil <span class="text-danger">*</span></label>
                                        <p class="text-muted small mb-1"><i class="bi bi-info-circle me-1"></i> Upload foto untuk avatar profil Anda.</p>
                                        @if($user->foto)
                                            <div class="mb-3">
                                                <img src="{{ asset('storage/' . $user->foto) }}" alt="Avatar" class="img-thumbnail" style="max-height: 100px;">
                                            </div>
                                        @endif
                                        <input type="file" class="form-control" name="foto" accept="image/*" {{ $user->foto ? '' : 'required' }}>
                                        <small class="text-muted">Format: JPG, PNG, GIF. Maksimal 2MB.</small>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-semibold">Upload File Produk <span class="text-danger">*</span></label>
                                        <p class="text-muted small mb-1"><i class="bi bi-info-circle me-1"></i> Upload katalog, brosur, atau dokumentasi produk Anda.</p>
                                        @if($user->file_produk && is_array($user->file_produk))
                                            <div class="mb-3 d-flex flex-wrap gap-2">
                                                @foreach($user->file_produk as $idx => $file)
                                                    @php $ext = pathinfo($file, PATHINFO_EXTENSION); @endphp
                                                    @if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png']))
                                                        <img src="{{ route('profile.document', ['type' => 'produk', 'userId' => $user->id, 'index' => $idx]) }}" alt="Produk" class="img-thumbnail" style="max-height: 150px;">
                                                    @else
                                                        <a href="{{ route('profile.document', ['type' => 'produk', 'userId' => $user->id, 'index' => $idx]) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                            <i class="bi bi-file-earmark-arrow-down"></i> File Produk {{ $idx + 1 }}
                                                        </a>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif
                                        <div id="produk-upload-container">
                                            <div class="input-group mb-2">
                                                <input type="file" class="form-control @error('file_produk') is-invalid @enderror" name="file_produk[]" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" {{ $user->file_produk ? '' : 'required' }}>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-secondary mb-2" id="add-produk-btn"><i class="bi bi-plus-circle me-1"></i>Tambah Kolom File Produk</button>
                                        <br>
                                        <small class="text-muted">Format: PDF, DOC, JPG, PNG. Maksimal 5MB.</small>
                                        @error('file_produk') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                
                                    <div class="mt-4 d-flex justify-content-end pb-3">
                                        <button type="submit" class="btn btn-primary px-4">
                                            <i class="bi bi-save me-1"></i> Simpan Perubahan
                                        </button>
                                    </div></div>
                            </div>
                        </div>
                    </div>

                
                                    <div class="mt-4 d-flex justify-content-end pb-3">
                                        <button type="submit" class="btn btn-primary px-4">
                                            <i class="bi bi-save me-1"></i> Simpan Perubahan
                                        </button>
                                    </div>

                </form>
            </div>

            <!-- Training Diikuti -->
            <div class="panel">
                <div class="panel-header">
                    <div>
                        <h5 class="section-title"><i class="bi bi-journal-check me-2 text-primary"></i> Training Diikuti</h5>
                        <p class="text-muted small mb-0">Daftar pelatihan yang diikuti oleh user.</p>
                    </div>
                </div>
                <div class="p-4">
                    @forelse($user->trainingDiikuti as $reg)
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <span class="fw-semibold">{{ $reg->training->judul ?? '-' }}</span>
                        <span class="badge bg-info">
                            <i class="bi bi-circle-fill me-1" style="font-size: 6px;"></i>
                            {{ ucfirst($reg->status) }}
                        </span>
                    </div>
                    @empty
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-journal-x fs-1 d-block mb-2"></i>
                        <p class="h6">Belum ada training yang diikuti.</p>
                        <p class="small">User belum mendaftar atau mengikuti pelatihan apapun.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* ============================================================
       PAGE HEADING
    ============================================================ */
    .page-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        padding: 1.25rem 1.5rem;
        background: #fff;
        border-bottom: 1px solid #f0f0f0;
    }
    .page-heading-copy {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .page-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #eaf1fd, #d4e4f7);
        color: #4e9af1;
        font-size: 1.3rem;
        flex-shrink: 0;
    }
    .eyebrow {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #8a93a3;
        font-weight: 600;
    }
    .heading-actions {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    /* ============================================================
       PANEL
    ============================================================ */
    .panel {
        background: #fff;
        border-radius: .75rem;
        box-shadow: 0 1px 4px rgba(0,0,0,.06);
        overflow: hidden;
    }
    .panel:hover {
        box-shadow: 0 8px 30px rgba(0,0,0,0.06);
    }
    
    .panel-header {
        padding: .9rem 1.25rem;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: .75rem;
        background: #fafbfc;
    }
    
    .section-title {
        display: flex;
        align-items: center;
        gap: .5rem;
        margin: 0;
        font-size: 1rem;
        font-weight: 600;
        color: #1a2236;
    }
    
    .section-title i {
        color: #4e9af1;
    }

    .panel-footer {
        padding: 1rem 1.25rem;
        border-top: 1px solid #f0f0f0;
        background: #fafbfc;
    }

    /* ============================================================
       TABS
    ============================================================ */
    .nav-tabs-custom {
        border-bottom: 2px solid #e9ecef;
    }
    .nav-tabs-custom .nav-link {
        border: none;
        color: #6c757d;
        padding: 0.6rem 1.25rem;
        font-weight: 500;
        border-radius: 0;
        transition: all 0.2s ease;
        font-size: 0.9rem;
    }
    .nav-tabs-custom .nav-link:hover {
        color: #4e9af1;
        background: transparent;
    }
    .nav-tabs-custom .nav-link.active {
        color: #4e9af1;
        border-bottom: 3px solid #4e9af1;
        background: transparent;
    }
    .nav-tabs-custom .nav-link i {
        margin-right: 0.25rem;
    }

    /* ============================================================
       FORM
    ============================================================ */
    .form-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: #1e293b;
    }
    .form-control, .form-select {
        border-radius: 0.5rem;
        border-color: #e2e8f0;
        padding: 0.5rem 0.75rem;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }
    .form-control:focus, .form-select:focus {
        border-color: #4e9af1;
        box-shadow: 0 0 0 3px rgba(78, 154, 241, 0.15);
    }
    .text-muted {
        font-size: 0.75rem;
        color: #8a93a3 !important;
    }

    /* ============================================================
       BUTTONS
    ============================================================ */
    .btn {
        border-radius: 0.5rem;
        padding: 0.45rem 1.2rem;
        font-weight: 500;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }
    .btn-primary {
        background: #4e9af1;
        border-color: #4e9af1;
        color: #fff;
    }
    .btn-primary:hover {
        background: #3a7bc8;
        border-color: #3a7bc8;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(78, 154, 241, 0.3);
    }
    .btn-outline-secondary {
        border-color: #e2e8f0;
        color: #4a5568;
    }
    .btn-outline-secondary:hover {
        background: #e2e8f0;
        border-color: #d5dce6;
    }
    .btn-outline-primary {
        border-color: #4e9af1;
        color: #4e9af1;
    }
    .btn-outline-primary:hover {
        background: #4e9af1;
        color: #fff;
    }
    .btn-danger {
        background: #ef4444;
        border-color: #ef4444;
        color: #fff;
    }
    .btn-danger:hover {
        background: #dc2626;
        border-color: #dc2626;
    }

    /* ============================================================
       BADGE
    ============================================================ */
    .badge {
        font-weight: 500;
        padding: 0.4rem 0.8rem;
        font-size: 0.75rem;
    }

    /* ============================================================
       ALERT
    ============================================================ */
    .alert {
        border-radius: 0.75rem;
        border: none;
        padding: 0.75rem 1rem;
    }
    .alert-success {
        background: #ecfdf5;
        color: #065f46;
    }
    .alert-danger {
        background: #fef2f2;
        color: #991b1b;
    }

    /* ============================================================
       KBLI
    ============================================================ */
    .kbli-row {
        border: 1px solid #e2e8f0 !important;
        border-radius: 0.75rem !important;
        overflow: hidden;
    }
    
    .select2-container {
        width: 100% !important;
        display: block;
    }
    
    .select2-container .select2-selection--single {
        height: 38px !important;
        border-radius: 0.5rem !important;
        border: 1px solid #e2e8f0 !important;
        padding: 0.25rem 0.5rem;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 28px !important;
        color: #1e293b !important;
        font-size: 0.875rem;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px !important;
    }

    .select2-dropdown {
        border-color: #e2e8f0 !important;
        border-radius: 0.5rem !important;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        z-index: 9999;
    }

    .uraian-box {
        font-size: 0.825rem !important;
        line-height: 1.5 !important;
        min-height: auto !important;
    }

    /* ============================================================
       RESPONSIVE
    ============================================================ */
    @media (max-width: 768px) {
        .page-heading {
            flex-direction: column;
            align-items: flex-start;
        }
        .page-heading-copy {
            width: 100%;
        }
        .heading-actions {
            width: 100%;
        }
        .heading-actions .btn {
            width: 100%;
        }
        .panel-header {
            flex-direction: column;
            align-items: flex-start;
        }
        .nav-tabs-custom .nav-link {
            padding: 0.4rem 0.75rem;
            font-size: 0.8rem;
        }
        .panel-footer {
            flex-direction: column;
        }
        .panel-footer .btn {
            width: 100%;
        }
        .d-flex.justify-content-end {
            width: 100%;
        }
        .d-flex.justify-content-end .btn {
            width: 100%;
        }
        .row.g-3 .col-12.col-md-6,
        .row.g-3 .col-12.col-md-4 {
            flex: 0 0 100%;
            max-width: 100%;
        }
        .table-responsive {
            font-size: 0.85rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let wilayahData = [];
        
        const kabSelect = document.getElementById('kabupaten');
        const kecSelect = document.getElementById('kecamatan');
        const desaSelect = document.getElementById('desa');
        
        const oldKab = "{{ old('kabupaten', $user->kabupaten) }}";
        const oldKec = "{{ old('kecamatan', $user->kecamatan) }}";
        const oldDesa = "{{ old('desa', $user->desa) }}";

        fetch('/data/wilayah-sulbar.json')
            .then(response => response.json())
            .then(data => {
                wilayahData = data;
                
                // Populate Kabupaten
                kabSelect.innerHTML = '<option value="">Pilih Kabupaten/Kota</option>';
                data.forEach(kab => {
                    const option = document.createElement('option');
                    option.value = kab.name;
                    option.textContent = kab.name;
                    kabSelect.appendChild(option);
                });
                kabSelect.disabled = false;
                
                // Restore old value if any
                if (oldKab) {
                    kabSelect.value = oldKab;
                    kabSelect.dispatchEvent(new Event('change'));
                }
            });

        kabSelect.addEventListener('change', function() {
            kecSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
            desaSelect.innerHTML = '<option value="">Pilih Desa/Kelurahan</option>';
            kecSelect.disabled = true;
            desaSelect.disabled = true;

            const selectedKab = wilayahData.find(k => k.name === this.value);
            if (selectedKab && selectedKab.kecamatan) {
                selectedKab.kecamatan.forEach(kec => {
                    const option = document.createElement('option');
                    option.value = kec.name;
                    option.textContent = kec.name;
                    kecSelect.appendChild(option);
                });
                kecSelect.disabled = false;
                
                if (oldKec && kecSelect.querySelector(`option[value="${oldKec}"]`)) {
                    kecSelect.value = oldKec;
                    kecSelect.dispatchEvent(new Event('change'));
                }
            }
        });

        kecSelect.addEventListener('change', function() {
            desaSelect.innerHTML = '<option value="">Pilih Desa/Kelurahan</option>';
            desaSelect.disabled = true;

            const selectedKab = wilayahData.find(k => k.name === kabSelect.value);
            if (selectedKab) {
                const selectedKec = selectedKab.kecamatan.find(k => k.name === this.value);
                if (selectedKec && selectedKec.desa) {
                    selectedKec.desa.forEach(desa => {
                        const option = document.createElement('option');
                        option.value = desa.name;
                        option.textContent = desa.name;
                        desaSelect.appendChild(option);
                    });
                    desaSelect.disabled = false;
                    
                    if (oldDesa && desaSelect.querySelector(`option[value="${oldDesa}"]`)) {
                        desaSelect.value = oldDesa;
                    }
                }
            }
        });
    
        // USAHA
        const kabUsahaSelect = document.getElementById('kabupaten_usaha');
        const kecUsahaSelect = document.getElementById('kecamatan_usaha');
        const desaUsahaSelect = document.getElementById('desa_usaha');
        
        const oldKabUsaha = "{{ old('kabupaten_usaha', $user->kabupaten_usaha) }}";
        const oldKecUsaha = "{{ old('kecamatan_usaha', $user->kecamatan_usaha) }}";
        const oldDesaUsaha = "{{ old('desa_usaha', $user->desa_usaha) }}";

        if(kabUsahaSelect) {
            fetch('/data/wilayah-sulbar.json')
                .then(response => response.json())
                .then(data => {
                    // Make data available for dependent selects
                    if(typeof wilayahData === 'undefined') {
                        window.wilayahData = data;
                    }
                    kabUsahaSelect.innerHTML = '<option value="">Pilih Kabupaten/Kota</option>';
                    data.forEach(kab => {
                        const option = document.createElement('option');
                        option.value = kab.name;
                        option.textContent = kab.name;
                        kabUsahaSelect.appendChild(option);
                    });
                    kabUsahaSelect.disabled = false;
                    if (oldKabUsaha) {
                        kabUsahaSelect.value = oldKabUsaha;
                        kabUsahaSelect.dispatchEvent(new Event('change'));
                    }
                });

            kabUsahaSelect.addEventListener('change', function() {
                kecUsahaSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
                desaUsahaSelect.innerHTML = '<option value="">Pilih Desa/Kelurahan</option>';
                kecUsahaSelect.disabled = true;
                desaUsahaSelect.disabled = true;

                const selectedKab = (window.wilayahData || wilayahData).find(k => k.name === this.value);
                if (selectedKab && selectedKab.kecamatan) {
                    selectedKab.kecamatan.forEach(kec => {
                        const option = document.createElement('option');
                        option.value = kec.name;
                        option.textContent = kec.name;
                        kecUsahaSelect.appendChild(option);
                    });
                    kecUsahaSelect.disabled = false;
                    if (oldKecUsaha && kecUsahaSelect.querySelector(`option[value="${oldKecUsaha}"]`)) {
                        kecUsahaSelect.value = oldKecUsaha;
                        kecUsahaSelect.dispatchEvent(new Event('change'));
                    }
                }
            });

            kecUsahaSelect.addEventListener('change', function() {
                desaUsahaSelect.innerHTML = '<option value="">Pilih Desa/Kelurahan</option>';
                desaUsahaSelect.disabled = true;

                const selectedKab = (window.wilayahData || wilayahData).find(k => k.name === kabUsahaSelect.value);
                if (selectedKab) {
                    const selectedKec = selectedKab.kecamatan.find(k => k.name === this.value);
                    if (selectedKec && selectedKec.desa) {
                        selectedKec.desa.forEach(desa => {
                            const option = document.createElement('option');
                            option.value = desa.name;
                            option.textContent = desa.name;
                            desaUsahaSelect.appendChild(option);
                        });
                        desaUsahaSelect.disabled = false;
                        if (oldDesaUsaha && desaUsahaSelect.querySelector(`option[value="${oldDesaUsaha}"]`)) {
                            desaUsahaSelect.value = oldDesaUsaha;
                        }
                    }
                }
            });
        }

    });
</script>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $(document).on('click', '.remove-mp', function() {
        $(this).closest('.mp-row').remove();
    });

    // Multiple file uploads UI
    $('#add-npwp-btn').on('click', function() {
        $('#npwp-upload-container').append(`
            <div class="input-group mb-2 file-row">
                <input type="file" class="form-control" name="npwp_file[]" accept=".jpg,.jpeg,.png,.pdf">
                <button class="btn btn-danger remove-file-btn" type="button"><i class="bi bi-trash"></i></button>
            </div>
        `);
    });

    $('#add-produk-btn').on('click', function() {
        $('#produk-upload-container').append(`
            <div class="input-group mb-2 file-row">
                <input type="file" class="form-control" name="file_produk[]" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                <button class="btn btn-danger remove-file-btn" type="button"><i class="bi bi-trash"></i></button>
            </div>
        `);
    });

    $(document).on('click', '.remove-file-btn', function() {
        $(this).closest('.file-row').remove();
    });
    
    // === KBLI LOGIC ===
    let kbliIndex = 0;
    const userKblis = {!! $user->kblis()->with('kbli')->get()->toJson() !!};
    
    function fetchCategories(selectElem, selectedValue = null) {
        $.ajax({
            url: '/api/kbli/categories',
            method: 'GET',
            success: function(res) {
                selectElem.empty().append('<option value="">Pilih Kategori</option>');
                res.forEach(item => {
                    let selected = (selectedValue === item.kategori_kode) ? 'selected' : '';
                    selectElem.append(`<option value="${item.kategori_kode}" ${selected}>${item.kategori_kode} - ${item.kategori_nama}</option>`);
                });
            }
        });
    }

    function fetchGolongans(kategoriKode, selectElem, selectedValue = null) {
        if (!kategoriKode) {
            selectElem.empty().append('<option value="">Pilih Golongan</option>').prop('disabled', true);
            return;
        }
        $.ajax({
            url: '/api/kbli/golongans',
            method: 'GET',
            data: { kategori: kategoriKode },
            success: function(res) {
                selectElem.empty().append('<option value="">Pilih Golongan</option>').prop('disabled', false);
                res.forEach(item => {
                    let selected = (selectedValue === item.golongan_pokok_kode) ? 'selected' : '';
                    selectElem.append(`<option value="${item.golongan_pokok_kode}" ${selected}>${item.golongan_pokok_kode} - ${item.golongan_pokok_nama}</option>`);
                });
            }
        });
    }

    function addKbliRow(data = null) {
        let isUtama = data ? data.is_utama : (kbliIndex === 0);
        let id = kbliIndex++;
        let labelUsaha = (id === 0) ? 'JENIS USAHA UTAMA' : 'USAHA LAINNYA #' + (id + 1);
        
        let html = `
        <div class="card mb-3 kbli-row shadow-sm" data-index="${id}">
            <div class="card-header bg-light border-bottom d-flex justify-content-between align-items-center py-2 px-3">
                <span class="fw-bold text-primary small"><i class="bi bi-tag-fill me-1"></i>${labelUsaha}</span>
                ${id > 0 ? '<button type="button" class="btn btn-xs btn-outline-danger border-0 py-0 px-2 btn-remove-row"><i class="bi bi-trash me-1"></i>Hapus</button>' : ''}
            </div>
            <div class="card-body bg-white p-3">
                <div class="row g-2">
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold mb-1 small">Kategori <span class="text-danger">*</span></label>
                        <select class="form-select form-select-sm select-kategori">
                            <option value="">Pilih Kategori</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold mb-1 small">Golongan <span class="text-danger">*</span></label>
                        <select class="form-select form-select-sm select-golongan" disabled>
                            <option value="">Pilih Golongan</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold mb-1 small">KBLI / Kegiatan Usaha <span class="text-danger">*</span></label>
                        <select class="form-control select-kbli" style="width: 100%;" disabled>
                            ${data && data.kbli ? `<option value="${data.kbli.id}" selected>${data.kbli.kode} - ${data.kbli.judul}</option>` : '<option value="">Pilih KBLI...</option>'}
                        </select>
                        <input type="hidden" name="kbli_id[]" class="kbli-id-hidden" value="${data ? data.kbli_id : ''}">
                    </div>
                    <div class="col-12 mt-1">
                        <div class="p-2 bg-light rounded uraian-box border" style="font-size: 0.8rem; line-height: 1.4;">
                            ${data && data.kbli && data.kbli.uraian ? `
                                <div style="color: #2d3748;">
                                    <strong style="color: #1a2236; display: inline-block;" class="me-1">📋 Uraian:</strong> ${data.kbli.uraian}
                                </div>
                            ` : `
                                <div style="color: #6c757d; font-style: italic;">
                                    <i class="bi bi-info-circle me-1"></i> Pilih KBLI untuk melihat deskripsi uraian kegiatan usaha.
                                </div>
                            `}
                        </div>
                    </div>
                    <div class="col-12 mt-2">
                        <div class="form-check">
                            <input class="form-check-input kbli-utama-radio" type="radio" name="kbli_utama" value="${data ? data.kbli_id : id}" id="utama_${id}" ${isUtama ? 'checked' : ''}>
                            <label class="form-check-label fw-bold text-dark small" for="utama_${id}">
                                ⭐ Jadikan KBLI Utama
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        `;
        
        let $row = $(html);
        $('#kbli-repeater-container').append($row);
        
        let $selectKategori = $row.find('.select-kategori');
        let $selectGolongan = $row.find('.select-golongan');
        let $selectKbli = $row.find('.select-kbli');
        let $uraianBox = $row.find('.uraian-box');
        let $hiddenId = $row.find('.kbli-id-hidden');
        let $radioUtama = $row.find('.kbli-utama-radio');
        
        // Fetch init categories
        let initKat = data && data.kbli ? data.kbli.kategori_kode : null;
        let initGol = data && data.kbli ? data.kbli.golongan_pokok_kode : null;
        fetchCategories($selectKategori, initKat);
        if (initKat) {
            fetchGolongans(initKat, $selectGolongan, initGol);
            $selectKbli.prop('disabled', false);
        }

        // Kategori Change Event
        $selectKategori.on('change', function() {
            let val = $(this).val();
            $selectGolongan.empty().append('<option value="">Pilih Golongan</option>').prop('disabled', true);
            $selectKbli.empty().prop('disabled', true);
            $hiddenId.val('');
            $radioUtama.val(id);
            $uraianBox.html(`
                <div style="color: #6c757d; font-style: italic;">
                    <i class="bi bi-info-circle me-1"></i> Pilih KBLI untuk melihat deskripsi uraian kegiatan usaha.
                </div>
            `);
            if (val) fetchGolongans(val, $selectGolongan);
        });

        // Golongan Change Event
        $selectGolongan.on('change', function() {
            let val = $(this).val();
            $selectKbli.empty().prop('disabled', !val);
            $hiddenId.val('');
            $radioUtama.val(id);
            $uraianBox.html(`
                <div style="color: #6c757d; font-style: italic;">
                    <i class="bi bi-info-circle me-1"></i> Pilih KBLI untuk melihat deskripsi uraian kegiatan usaha.
                </div>
            `);
        });
        
        // Init Select2 for this row
        $selectKbli.select2({
            width: '100%',
            ajax: {
                url: '/api/kbli/search',
                dataType: 'json',
                delay: 300,
                data: function (params) {
                    return { 
                        q: params.term || '',
                        kategori: $selectKategori.val(),
                        golongan: $selectGolongan.val()
                    };
                },
                processResults: function (res) {
                    return { results: res };
                }
            },
            placeholder: 'Pilih KBLI...',
            minimumInputLength: 0,
            templateResult: function (kbli) {
                if (kbli.loading) return kbli.text;
                return $(
                    "<div class='py-1 px-2'>" +
                    "<div class='fw-bold text-dark small'>" + kbli.text + "</div>" +
                    "<div class='text-muted mt-1' style='font-size: 0.75rem;'>Uraian: " + (kbli.uraian || '-') + "</div>" +
                    "</div>"
                );
            },
            templateSelection: function (kbli) {
                return kbli.text || kbli.id || 'Pilih KBLI...';
            }
        }).on('select2:select', function(e) {
            let item = e.params.data;
            $hiddenId.val(item.id);
            $radioUtama.val(item.id);
            $uraianBox.html(`
                <div style="color: #2d3748;">
                    <strong style="color: #1a2236; display: inline-block;" class="me-1">📋 Uraian:</strong> ${item.uraian}
                </div>
            `);
        }).on('select2:clear', function() {
            $hiddenId.val('');
            $radioUtama.val(id);
            $uraianBox.html(`
                <div style="color: #6c757d; font-style: italic;">
                    <i class="bi bi-info-circle me-1"></i> Pilih KBLI untuk melihat deskripsi uraian kegiatan usaha.
                </div>
            `);
        });
    }

    // Initialize existing or empty row
    if (userKblis && userKblis.length > 0) {
        userKblis.forEach(function(uk) {
            addKbliRow(uk);
        });
    } else {
        addKbliRow();
    }

    $('#btn-add-usaha').on('click', function() {
        addKbliRow();
    });

    $(document).on('click', '.btn-remove-row', function() {
        var row = $(this).closest('.kbli-row');
        var isUtama = row.find('.kbli-utama-radio').is(':checked');
        row.remove();
        
        if (isUtama && $('.kbli-row').length > 0) {
            $('.kbli-row').first().find('.kbli-utama-radio').prop('checked', true);
        }
    });
    // Add Marketplace Lainnya dynamically
    $('#add-mp-btn').on('click', function() {
        let html = `
            <div class="row g-2 mb-2 mp-row">
                <div class="col-md-5">
                    <input type="text" class="form-control" name="marketplace_lainnya_nama[]" placeholder="Nama Marketplace (cth: Bukalapak)">
                </div>
                <div class="col-md-6">
                    <input type="url" class="form-control" name="marketplace_lainnya_link[]" placeholder="Link Marketplace (https://...)">
                </div>
                <div class="col-md-1 d-flex align-items-center">
                    <button type="button" class="btn btn-danger btn-sm w-100 remove-mp"><i class="bi bi-trash"></i></button>
                </div>
            </div>
        `;
        $('#marketplace-container').append(html);
    });

    $(document).on('click', '.remove-mp', function() {
        $(this).closest('.mp-row').remove();
    });

    // Auto-calculate total karyawan
    function calculateTotalKaryawan() {
        var tetapL = parseInt($('#tetap_laki_laki').val()) || 0;
        var tetapP = parseInt($('#tetap_perempuan').val()) || 0;
        var totalTetap = tetapL + tetapP;
        $('#total_tetap').val(totalTetap);

        var tidakTetapL = parseInt($('#tidak_tetap_laki_laki').val()) || 0;
        var tidakTetapP = parseInt($('#tidak_tetap_perempuan').val()) || 0;
        var totalTidakTetap = tidakTetapL + tidakTetapP;
        $('#total_tidak_tetap').val(totalTidakTetap);

        var grandTotal = totalTetap + totalTidakTetap;
        $('#grand_total').text(grandTotal + ' Orang');
    }
    
    $('.karyawan-input').on('input', calculateTotalKaryawan);
});
</script>
@endpush
@endsection