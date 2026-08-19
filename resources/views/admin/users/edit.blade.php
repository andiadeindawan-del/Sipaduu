@extends('layouts.admin')

@section('title', 'Profil Saya')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-person"></i></span>
        <div>
            <p class="eyebrow">Akun</p>
            <h1 class="h3 mb-0">Edit User</h1>
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

            <!-- Status Kelengkapan Profil -->
            @include('components.profile-completion', ['user' => $user])

            <div class="panel mb-4">
                <div class="panel-header">
                    <h5 class="section-title"><i class="bi bi-person-lines-fill"></i> Edit Profil User</h5>
                </div>
                
                <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
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
                                <button class="nav-link" id="digital-tab" data-bs-toggle="tab" data-bs-target="#digital" type="button" role="tab" aria-controls="digital" aria-selected="false">Digitalisasi & Pemasaran</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="tambahan-tab" data-bs-toggle="tab" data-bs-target="#tambahan" type="button" role="tab" aria-controls="tambahan" aria-selected="false">Kebutuhan Pelatihan</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="dokumen-tab" data-bs-toggle="tab" data-bs-target="#dokumen" type="button" role="tab" aria-controls="dokumen" aria-selected="false">Dokumen</button>
                            </li>
                        </ul>

                        <div class="tab-content" id="profileTabsContent">
                            <!-- TAB DATA PRIBADI -->
                            <div class="tab-pane fade show active" id="pribadi" role="tabpanel" aria-labelledby="pribadi-tab">
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('nama') is-invalid @enderror" name="nama" value="{{ old('nama', $user->nama) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email) }}">
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
                                        <label class="form-label fw-semibold">Tempat Lahir</label>
                                        <input type="text" class="form-control" name="tempat_lahir" value="{{ old('tempat_lahir', $user->tempat_lahir) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Tanggal Lahir</label>
                                        <input type="date" class="form-control" name="tanggal_lahir" value="{{ old('tanggal_lahir', $user->tanggal_lahir) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Jenis Kelamin</label>
                                        <select class="form-select" name="jenis_kelamin">
                                            <option value="">Pilih...</option>
                                            <option value="L" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="P" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Role <span class="text-danger">*</span></label>
                                        <select class="form-select @error('role') is-invalid @enderror" name="role" required>
                                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                            <option value="trainer" {{ old('role', $user->role) == 'trainer' ? 'selected' : '' }}>Trainer</option>
                                            <option value="peserta" {{ old('role', $user->role) == 'peserta' ? 'selected' : '' }}>Peserta</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Status Akun</label>
                                        <select class="form-select @error('status') is-invalid @enderror" name="status">
                                            <option value="aktif" {{ old('status', $user->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                            <option value="nonaktif" {{ old('status', $user->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Password Baru <span class="text-muted fw-normal">(Opsional)</span></label>
                                        <input type="password" class="form-control" name="password" placeholder="Kosongkan jika tidak diubah">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Konfirmasi Password Baru</label>
                                        <input type="password" class="form-control" name="password_confirmation" placeholder="Kosongkan jika tidak diubah">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Departemen <span class="text-muted fw-normal">(Opsional)</span></label>
                                        <input type="text" class="form-control" name="departemen" value="{{ old('departemen', $user->departemen) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Jabatan <span class="text-muted fw-normal">(Opsional)</span></label>
                                        <input type="text" class="form-control" name="jabatan" value="{{ old('jabatan', $user->jabatan) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Agama <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="agama" value="{{ old('agama', $user->agama) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Status Pernikahan <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="status_pernikahan" value="{{ old('status_pernikahan', $user->status_pernikahan) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Pendidikan Terakhir <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="pendidikan_terakhir" value="{{ old('pendidikan_terakhir', $user->pendidikan_terakhir) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Status Disabilitas</label>
                                        <input type="text" class="form-control" name="disabilitas" value="{{ old('disabilitas', $user->disabilitas) }}">
                                    </div>
                                    <div class="col-12 mt-4">
                                        <h6 class="fw-bold mb-3 border-bottom pb-2">Alamat Domisili</h6>
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
                                        <select class="form-select @error('kabupaten') is-invalid @enderror" id="kabupaten" name="kabupaten" required disabled>
                                            <option value="">Pilih Kabupaten/Kota</option>
                                        </select>
                                        @error('kabupaten') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Kecamatan <span class="text-danger">*</span></label>
                                        <select class="form-select @error('kecamatan') is-invalid @enderror" id="kecamatan" name="kecamatan" required disabled>
                                            <option value="">Pilih Kecamatan</option>
                                        </select>
                                        @error('kecamatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Desa/Kelurahan <span class="text-danger">*</span></label>
                                        <select class="form-select @error('desa') is-invalid @enderror" id="desa" name="desa" required disabled>
                                            <option value="">Pilih Desa/Kelurahan</option>
                                        </select>
                                        @error('desa') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-semibold">Alamat Detail <span class="text-danger">*</span></label>
                                        <textarea class="form-control" name="alamat_lengkap" rows="2" placeholder="Nama Jalan, RT/RW, Dusun">{{ old('alamat_lengkap', $user->alamat_lengkap) }}</textarea>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Kode Pos <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="kode_pos_domisili" value="{{ old('kode_pos_domisili', $user->kode_pos_domisili) }}">
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
                                        <label class="form-label fw-semibold">Jabatan dalam Usaha <span class="text-muted">(Opsional)</span></label>
                                        <input type="text" class="form-control" name="jabatan_usaha" value="{{ old('jabatan_usaha', $user->jabatan_usaha) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Merek Produk <span class="text-muted">(Opsional)</span></label>
                                        <input type="text" class="form-control" name="merek_produk" value="{{ old('merek_produk', $user->merek_produk) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Kode Pos Usaha <span class="text-muted">(Opsional)</span></label>
                                        <input type="text" class="form-control" name="kode_pos_usaha" value="{{ old('kode_pos_usaha', $user->kode_pos_usaha) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Nomor Telepon Usaha <span class="text-muted">(Opsional)</span></label>
                                        <input type="text" class="form-control" name="no_telepon_usaha" value="{{ old('no_telepon_usaha', $user->no_telepon_usaha) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Sektor Usaha <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="sektor_usaha" value="{{ old('sektor_usaha', $user->sektor_usaha) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Bidang Usaha <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="bidang_usaha" value="{{ old('bidang_usaha', $user->bidang_usaha) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Tanggal Berdiri Usaha <span class="text-muted">(Opsional)</span></label>
                                        <input type="date" class="form-control" name="tanggal_berdiri" value="{{ old('tanggal_berdiri', $user->tanggal_berdiri) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Nomor NPWP Usaha <span class="text-muted">(Opsional)</span></label>
                                        <input type="text" class="form-control" name="npwp_usaha" value="{{ old('npwp_usaha', $user->npwp_usaha) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Status NIB <span class="text-muted">(Opsional)</span></label>
                                        <input type="text" class="form-control" name="status_nib" value="{{ old('status_nib', $user->status_nib) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Nomor NIB <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="nib" value="{{ old('nib', $user->nib) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Lama Kepemilikan NIB <span class="text-muted">(Opsional)</span></label>
                                        <input type="text" class="form-control" name="lama_nib" value="{{ old('lama_nib', $user->lama_nib) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Kategori Modal Usaha <span class="text-muted">(Opsional)</span></label>
                                        <input type="text" class="form-control" name="modal_usaha" value="{{ old('modal_usaha', $user->modal_usaha) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Nilai Modal (Rp) <span class="text-muted">(Opsional)</span></label>
                                        <input type="number" class="form-control" name="nilai_modal" value="{{ old('nilai_modal', $user->nilai_modal) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Kategori Omzet Usaha <span class="text-muted">(Opsional)</span></label>
                                        <input type="text" class="form-control" name="omzet_usaha" value="{{ old('omzet_usaha', $user->omzet_usaha) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Nilai Omzet (Rp) <span class="text-muted">(Opsional)</span></label>
                                        <input type="number" class="form-control" name="nilai_omzet" value="{{ old('nilai_omzet', $user->nilai_omzet) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Jumlah Karyawan <span class="text-muted">(Opsional)</span></label>
                                        <input type="number" class="form-control" name="jumlah_karyawan" value="{{ old('jumlah_karyawan', $user->jumlah_karyawan) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Kapasitas Produksi <span class="text-muted">(Opsional)</span></label>
                                        <input type="text" class="form-control" name="kapasitas_produksi" value="{{ old('kapasitas_produksi', $user->kapasitas_produksi) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Anggota Koperasi <span class="text-muted">(Opsional)</span></label>
                                        <select class="form-select" name="anggota_koperasi">
                                            <option value="">Pilih Status</option>
                                            <option value="Ya" @selected(old('anggota_koperasi', $user->anggota_koperasi) == 'Ya')>Ya</option>
                                            <option value="Tidak" @selected(old('anggota_koperasi', $user->anggota_koperasi) == 'Tidak')>Tidak</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- TAB DIGITALISASI & EKSPOR -->
                            <div class="tab-pane fade" id="digital" role="tabpanel" aria-labelledby="digital-tab">
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Email Usaha <span class="text-muted">(Opsional)</span></label>
                                        <input type="email" class="form-control" name="email_usaha" value="{{ old('email_usaha', $user->email_usaha) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Website Usaha <span class="text-muted">(Opsional)</span></label>
                                        <input type="url" class="form-control" name="website_usaha" value="{{ old('website_usaha', $user->website_usaha) }}">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-semibold">Media Sosial Usaha <span class="text-muted">(Opsional)</span></label>
                                        <textarea class="form-control" name="medsos_usaha" rows="2">{{ old('medsos_usaha', $user->medsos_usaha) }}</textarea>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-semibold">Marketplace yang Digunakan <span class="text-muted">(Opsional)</span></label>
                                        <textarea class="form-control" name="marketplace" rows="2">{{ old('marketplace', $user->marketplace) }}</textarea>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Pernah ikut Pengadaan Barang/Jasa? <span class="text-muted">(Opsional)</span></label>
                                        <select class="form-select" name="pengadaan_barang">
                                            <option value="">Pilih Status</option>
                                            <option value="Pernah" @selected(old('pengadaan_barang', $user->pengadaan_barang) == 'Pernah')>Pernah</option>
                                            <option value="Belum Pernah" @selected(old('pengadaan_barang', $user->pengadaan_barang) == 'Belum Pernah')>Belum Pernah</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Akses Kredit/Pembiayaan <span class="text-muted">(Opsional)</span></label>
                                        <input type="text" class="form-control" name="akses_kredit" value="{{ old('akses_kredit', $user->akses_kredit) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Memiliki Tabungan Usaha? <span class="text-muted">(Opsional)</span></label>
                                        <select class="form-select" name="tabungan">
                                            <option value="">Pilih Status</option>
                                            <option value="Ya" @selected(old('tabungan', $user->tabungan) == 'Ya')>Ya</option>
                                            <option value="Tidak" @selected(old('tabungan', $user->tabungan) == 'Tidak')>Tidak</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Sertifikasi Produk (Halal/PIRT dll) <span class="text-muted">(Opsional)</span></label>
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
                                        <label class="form-label fw-semibold">Perizinan Usaha (Lainnya) <span class="text-muted">(Opsional)</span></label>
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
                            </div>

                            <!-- TAB INFORMASI TAMBAHAN -->
                            <div class="tab-pane fade" id="tambahan" role="tabpanel" aria-labelledby="tambahan-tab">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label fw-semibold">Permasalahan Usaha Saat Ini</label>
                                        <textarea class="form-control" name="permasalahan" rows="3">{{ old('permasalahan', $user->permasalahan) }}</textarea>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-semibold">Kebutuhan Diklat/Pelatihan</label>
                                        <textarea class="form-control" name="kebutuhan_diklat" rows="3">{{ old('kebutuhan_diklat', $user->kebutuhan_diklat) }}</textarea>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Riwayat Pelatihan Sebelumnya</label>
                                        <input type="text" class="form-control" name="riwayat_pelatihan" value="{{ old('riwayat_pelatihan', $user->riwayat_pelatihan) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Jenis Pelatihan yang Pernah Diikuti</label>
                                        <input type="text" class="form-control" name="jenis_pelatihan_diikuti" value="{{ old('jenis_pelatihan_diikuti', $user->jenis_pelatihan_diikuti) }}">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-semibold">Masukan dan Saran</label>
                                        <textarea class="form-control" name="masukan_saran" rows="3">{{ old('masukan_saran', $user->masukan_saran) }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- TAB DOKUMEN & FOTO -->
                            <div class="tab-pane fade" id="dokumen" role="tabpanel" aria-labelledby="dokumen-tab">
                                <div class="row g-4">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Upload KTP <span class="text-danger">*</span></label>
                                        
                                        @if($user->ktp_file)
                                            <div class="mb-3">
                                                @php $ext = pathinfo($user->ktp_file, PATHINFO_EXTENSION); @endphp
                                                @if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png']))
                                                    <img src="{{ route('profile.document', ['type' => 'ktp', 'userId' => $user->id]) }}" alt="KTP" class="img-thumbnail" style="max-height: 150px;">
                                                @else
                                                    <a href="{{ route('profile.document', ['type' => 'ktp', 'userId' => $user->id]) }}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="bi bi-file-earmark-pdf"></i> Lihat/Download KTP</a>
                                                @endif
                                            </div>
                                        @endif
                                        <input type="file" class="form-control @error('ktp_file') is-invalid @enderror" name="ktp_file" accept=".jpg,.jpeg,.png,.pdf" {{ $user->ktp_file ? '' : 'required' }}>
                                        <small class="text-muted">Format: PDF, JPG, PNG. Maksimal 5MB.</small>
                                        @error('ktp_file')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Upload NIB <span class="text-muted fw-normal">(Opsional)</span></label>
                                        
                                        @if($user->nib_file)
                                            <div class="mb-3">
                                                @php $ext = pathinfo($user->nib_file, PATHINFO_EXTENSION); @endphp
                                                @if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png']))
                                                    <img src="{{ route('profile.document', ['type' => 'nib', 'userId' => $user->id]) }}" alt="NIB" class="img-thumbnail" style="max-height: 150px;">
                                                @else
                                                    <a href="{{ route('profile.document', ['type' => 'nib', 'userId' => $user->id]) }}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="bi bi-file-earmark-pdf"></i> Lihat/Download NIB</a>
                                                @endif
                                            </div>
                                        @endif
                                        <input type="file" class="form-control @error('nib_file') is-invalid @enderror" name="nib_file" accept=".jpg,.jpeg,.png,.pdf">
                                        <small class="text-muted">Format: PDF, JPG, PNG. Maksimal 5MB.</small>
                                        @error('nib_file')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Upload NPWP <span class="text-muted fw-normal">(Opsional)</span></label>
                                        
                                        @if($user->npwp_file)
                                            <div class="mb-3">
                                                @php $ext = pathinfo($user->npwp_file, PATHINFO_EXTENSION); @endphp
                                                @if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png']))
                                                    <img src="{{ route('profile.document', ['type' => 'npwp', 'userId' => $user->id]) }}" alt="NPWP" class="img-thumbnail" style="max-height: 150px;">
                                                @else
                                                    <a href="{{ route('profile.document', ['type' => 'npwp', 'userId' => $user->id]) }}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="bi bi-file-earmark-pdf"></i> Lihat/Download NPWP</a>
                                                @endif
                                            </div>
                                        @endif
                                        <input type="file" class="form-control @error('npwp_file') is-invalid @enderror" name="npwp_file" accept=".jpg,.jpeg,.png,.pdf">
                                        <small class="text-muted">Format: PDF, JPG, PNG. Maksimal 5MB.</small>
                                        @error('npwp_file')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Upload Foto Profil (Avatar)</label>
                                        
                                        @if($user->foto)
                                            <div class="mb-3">
                                                <img src="{{ asset('storage/' . $user->foto) }}" alt="Avatar" class="img-thumbnail" style="max-height: 100px;">
                                            </div>
                                        @endif
                                        <input type="file" class="form-control" name="foto" accept="image/*">
                                        <small class="text-muted">Format: JPG, PNG, GIF. Maksimal 2MB.</small>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Upload File Produk (Katalog/Brosur)</label>
                                        
                                        @if($user->file_produk)
                                            <div class="mb-3">
                                                <a href="{{ route('profile.document', ['type' => 'produk', 'userId' => $user->id]) }}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="bi bi-file-earmark-arrow-down"></i> Lihat/Download File Produk</a>
                                            </div>
                                        @endif
                                        <input type="file" class="form-control @error('file_produk') is-invalid @enderror" name="file_produk" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                                        <small class="text-muted">Format: PDF, DOC, JPG, PNG. Maksimal 5MB.</small>
                                        @error('file_produk')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
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
            
            
        <div class="col-12 col-lg-10 mx-auto mt-4">
            <div class="panel border-0 shadow-sm">
                <div class="panel-header bg-light">
                    <h5 class="section-title fw-bold mb-0"><i class="bi bi-journal-check me-2 text-primary"></i> Training Diikuti</h5>
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
                        Belum ada training yang diikuti.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
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
    });
</script>
@endpush
@endsection




