@extends('layouts.peserta')

@section('title', 'Profil Saya')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-person"></i></span>
        <div>
            <p class="eyebrow">Akun</p>
            <h1 class="h3 mb-0">Profil Saya</h1>
        </div>
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
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i> Terdapat kesalahan pada form.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <!-- Status Kelengkapan -->
            <div class="alert {{ $user->is_profil_lengkap ? 'alert-success' : 'alert-warning' }} mb-4">
                <h6 class="alert-heading fw-bold mb-1">
                    {!! $user->is_profil_lengkap ? '<i class="bi bi-check-circle-fill"></i> PROFIL LENGKAP' : '<i class="bi bi-exclamation-triangle-fill"></i> PROFIL BELUM LENGKAP' !!}
                </h6>
                <p class="mb-0 mt-1 text-sm">
                    @if($user->is_profil_lengkap)
                        Profil Anda sudah lengkap. Anda dapat mendaftar dan mengikuti pelatihan.
                    @else
                        Anda tidak dapat disetujui untuk mengikuti pelatihan sebelum melengkapi data berikut dan mengupload KTP:
                        <ul class="mb-0 mt-1">
                            @foreach($user->profil_incomplete_fields as $field)
                                <li>{{ $field }}</li>
                            @endforeach
                        </ul>
                    @endif
                </p>
            </div>

            <div class="panel mb-4">
                <div class="panel-header">
                    <h5 class="section-title"><i class="bi bi-person-lines-fill"></i> Data Profil (UMK)</h5>
                </div>
                
                <form action="{{ route('peserta.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="p-4">
                        <ul class="nav nav-tabs mb-4" id="profileTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="pribadi-tab" data-bs-toggle="tab" data-bs-target="#pribadi" type="button" role="tab" aria-controls="pribadi" aria-selected="true">Data Pribadi</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="usaha-tab" data-bs-toggle="tab" data-bs-target="#usaha" type="button" role="tab" aria-controls="usaha" aria-selected="false">Data Usaha</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="digital-tab" data-bs-toggle="tab" data-bs-target="#digital" type="button" role="tab" aria-controls="digital" aria-selected="false">Digitalisasi & Ekspor</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="dokumen-tab" data-bs-toggle="tab" data-bs-target="#dokumen" type="button" role="tab" aria-controls="dokumen" aria-selected="false">Dokumen (KTP)</button>
                            </li>
                        </ul>

                        <div class="tab-content" id="profileTabsContent">
                            <!-- TAB DATA PRIBADI -->
                            <div class="tab-pane fade show active" id="pribadi" role="tabpanel" aria-labelledby="pribadi-tab">
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $user->name) }}" required>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email) }}" required>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">NIK <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="nik" value="{{ old('nik', $user->nik) }}" required>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Nomor HP/Telepon <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="no_telepon" value="{{ old('no_telepon', $user->no_telepon) }}" required>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Tempat Lahir <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="tempat_lahir" value="{{ old('tempat_lahir', $user->tempat_lahir) }}" required>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Tanggal Lahir <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="tanggal_lahir" value="{{ old('tanggal_lahir', $user->tanggal_lahir) }}" required>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Jenis Kelamin <span class="text-danger">*</span></label>
                                        <select class="form-select" name="jenis_kelamin" required>
                                            <option value="">Pilih...</option>
                                            <option value="L" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="P" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Agama <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="agama" value="{{ old('agama', $user->agama) }}" required>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Status Pernikahan <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="status_pernikahan" value="{{ old('status_pernikahan', $user->status_pernikahan) }}" required>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Pendidikan Terakhir <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="pendidikan_terakhir" value="{{ old('pendidikan_terakhir', $user->pendidikan_terakhir) }}" required>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Status Disabilitas</label>
                                        <input type="text" class="form-control" name="disabilitas" value="{{ old('disabilitas', $user->disabilitas) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Kode Pos Domisili <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="kode_pos_domisili" value="{{ old('kode_pos_domisili', $user->kode_pos_domisili) }}" required>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-semibold">Alamat Domisili <span class="text-danger">*</span></label>
                                        <textarea class="form-control" name="alamat_lengkap" rows="2" required>{{ old('alamat_lengkap', $user->alamat_lengkap) }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- TAB DATA USAHA -->
                            <div class="tab-pane fade" id="usaha" role="tabpanel" aria-labelledby="usaha-tab">
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Nama Usaha <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="nama_usaha" value="{{ old('nama_usaha', $user->nama_usaha) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Jabatan Usaha</label>
                                        <input type="text" class="form-control" name="jabatan_usaha" value="{{ old('jabatan_usaha', $user->jabatan_usaha) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Bidang Usaha <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="bidang_usaha" value="{{ old('bidang_usaha', $user->bidang_usaha) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Sektor Usaha <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="sektor_usaha" value="{{ old('sektor_usaha', $user->sektor_usaha) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Nomor NIB <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="nib" value="{{ old('nib', $user->nib) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">NPWP Usaha</label>
                                        <input type="text" class="form-control" name="npwp_usaha" value="{{ old('npwp_usaha', $user->npwp_usaha) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Nomor Telepon Usaha</label>
                                        <input type="text" class="form-control" name="no_telepon_usaha" value="{{ old('no_telepon_usaha', $user->no_telepon_usaha) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Tanggal Berdiri Usaha</label>
                                        <input type="date" class="form-control" name="tanggal_berdiri" value="{{ old('tanggal_berdiri', $user->tanggal_berdiri) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Modal Usaha / Tahun</label>
                                        <input type="text" class="form-control" name="modal_usaha" value="{{ old('modal_usaha', $user->modal_usaha) }}" placeholder="Contoh: Kurang dari 1 Miliar">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Nilai Modal (Rp)</label>
                                        <input type="number" class="form-control" name="nilai_modal" value="{{ old('nilai_modal', $user->nilai_modal) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Omzet Usaha / Tahun</label>
                                        <input type="text" class="form-control" name="omzet_usaha" value="{{ old('omzet_usaha', $user->omzet_usaha) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Nilai Omzet (Rp)</label>
                                        <input type="number" class="form-control" name="nilai_omzet" value="{{ old('nilai_omzet', $user->nilai_omzet) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Jumlah Karyawan</label>
                                        <input type="number" class="form-control" name="jumlah_karyawan" value="{{ old('jumlah_karyawan', $user->jumlah_karyawan) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Kapasitas Produksi</label>
                                        <input type="text" class="form-control" name="kapasitas_produksi" value="{{ old('kapasitas_produksi', $user->kapasitas_produksi) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Keanggotaan Koperasi</label>
                                        <input type="text" class="form-control" name="anggota_koperasi" value="{{ old('anggota_koperasi', $user->anggota_koperasi) }}">
                                    </div>
                                </div>
                            </div>

                            <!-- TAB DIGITALISASI & EKSPOR -->
                            <div class="tab-pane fade" id="digital" role="tabpanel" aria-labelledby="digital-tab">
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Email Usaha</label>
                                        <input type="email" class="form-control" name="email_usaha" value="{{ old('email_usaha', $user->email_usaha) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Website Usaha</label>
                                        <input type="url" class="form-control" name="website_usaha" value="{{ old('website_usaha', $user->website_usaha) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Media Sosial Usaha</label>
                                        <input type="text" class="form-control" name="medsos_usaha" value="{{ old('medsos_usaha', $user->medsos_usaha) }}" placeholder="Contoh: @usaha_ig">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Marketplace</label>
                                        <input type="text" class="form-control" name="marketplace" value="{{ old('marketplace', $user->marketplace) }}" placeholder="Shopee, Tokopedia, dll">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Status Akses Kredit</label>
                                        <input type="text" class="form-control" name="akses_kredit" value="{{ old('akses_kredit', $user->akses_kredit) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Status Ekspor</label>
                                        <input type="text" class="form-control" name="status_ekspor" value="{{ old('status_ekspor', $user->status_ekspor) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Negara Tujuan Ekspor</label>
                                        <input type="text" class="form-control" name="negara_ekspor" value="{{ old('negara_ekspor', $user->negara_ekspor) }}">
                                    </div>
                                </div>
                            </div>

                            <!-- TAB DOKUMEN & KTP -->
                            <div class="tab-pane fade" id="dokumen" role="tabpanel" aria-labelledby="dokumen-tab">
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-bold text-primary">Upload Foto KTP <span class="text-danger">*</span></label>
                                        @if($user->ktp_file)
                                            <div class="alert alert-success mb-2 py-2">
                                                <i class="bi bi-check-circle me-1"></i> KTP Sudah Diupload
                                            </div>
                                            <div class="mb-3">
                                                @php $ext = pathinfo($user->ktp_file, PATHINFO_EXTENSION); @endphp
                                                @if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png']))
                                                    <img src="{{ asset('storage/' . $user->ktp_file) }}" alt="KTP" class="img-thumbnail" style="max-height: 150px;">
                                                @else
                                                    <a href="{{ asset('storage/' . $user->ktp_file) }}" target="_blank" class="btn btn-sm btn-outline-primary">Lihat KTP Saat Ini</a>
                                                @endif
                                            </div>
                                        @endif
                                        <input type="file" class="form-control @error('ktp_file') is-invalid @enderror" name="ktp_file" accept=".jpg,.jpeg,.png,.pdf">
                                        <small class="text-muted">Format: JPG, PNG, PDF. Maksimal 2MB. (Wajib untuk validasi)</small>
                                        @error('ktp_file')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Upload Foto Profil (Avatar)</label>
                                        <input type="file" class="form-control" name="avatar" accept="image/*">
                                        <small class="text-muted">Format: JPG, PNG, GIF. Maksimal 2MB.</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="panel-footer bg-light p-4 text-end">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-save me-1"></i> Simpan Perubahan Profil
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Ubah Password Panel (Biarkan seperti semula atau gabungkan) -->
            <div class="panel">
                <div class="panel-header">
                    <h5 class="section-title"><i class="bi bi-shield-lock"></i> Ubah Password</h5>
                </div>
                <div class="p-4">
                    <form action="{{ route('peserta.profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Password Saat Ini</label>
                                <input type="password" class="form-control" name="current_password" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold">Password Baru</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold">Konfirmasi Password Baru</label>
                                <input type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>
                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-dark px-4">
                                <i class="bi bi-key me-1"></i> Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection