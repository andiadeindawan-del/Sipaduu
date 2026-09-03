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
                                        <select class="form-select" name="status_pernikahan" required>
                                            <option value="">Pilih...</option>
                                            <option value="Belum Menikah" {{ old('status_pernikahan', $user->status_pernikahan) == 'Belum Menikah' ? 'selected' : '' }}>Belum Menikah</option>
                                            <option value="Menikah" {{ old('status_pernikahan', $user->status_pernikahan) == 'Menikah' ? 'selected' : '' }}>Menikah</option>
                                            <option value="Cerai Mati" {{ old('status_pernikahan', $user->status_pernikahan) == 'Cerai Mati' ? 'selected' : '' }}>Cerai Mati</option>
                                            <option value="Cerai Hidup" {{ old('status_pernikahan', $user->status_pernikahan) == 'Cerai Hidup' ? 'selected' : '' }}>Cerai Hidup</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Pendidikan Terakhir <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="pendidikan_terakhir" value="{{ old('pendidikan_terakhir', $user->pendidikan_terakhir) }}">
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
                                    
                                </div>
                            </div>

                            <!-- TAB DATA USAHA -->
                            <div class="tab-pane fade" id="usaha" role="tabpanel" aria-labelledby="usaha-tab">
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Nama Usaha <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="nama_usaha" value="{{ old('nama_usaha', $user->nama_usaha) }}">
                                    </div>\n
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold">Status Usaha <span class="text-danger">*</span></label>
                                    <select class="form-select" name="status_usaha" required>
                                        <option value="">-- Pilih --</option>
                                        <option value="Aktif" {{ old('status_usaha', $user->status_usaha) == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                        <option value="Tidak Aktif" {{ old('status_usaha', $user->status_usaha) == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold">Bentuk Usaha <span class="text-danger">*</span></label>
                                    <select class="form-select" name="bentuk_usaha" required>
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
                                        <label class="form-label fw-semibold">Jabatan/Posisi dalam Usaha <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="jabatan_usaha" required value="{{ old('jabatan_usaha', $user->jabatan_usaha) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Merek Produk <span class="text-muted">(Opsional)</span></label>
                                        <input type="text" class="form-control" name="merek_produk" value="{{ old('merek_produk', $user->merek_produk) }}">
                                    </div>

                                <div class="col-12 mt-4 mb-2">
                                    <h6 class="fw-bold border-bottom pb-2">KBLI / Kegiatan Usaha <small class="text-muted fw-normal">(Bisa lebih dari satu)</small></h6>
                                    <p class="text-muted small mb-2">Cari KBLI berdasarkan kode, judul, atau uraian kegiatan.</p>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <select id="kbli-search-input" class="form-control" style="width: 100%"></select>
                                    </div>
                                    
                                    <h6 class="fw-bold small text-muted mb-2">KBLI YANG DIPILIH:</h6>
                                    <div id="kbli-selected-list" class="d-flex flex-column gap-2 mb-3">
                                        @foreach($user->kblis as $kbli)
                                            @if($kbli->kbli)
                                            <div class="card bg-light border kbli-selected-card" data-id="{{ $kbli->kbli_id }}">
                                                <div class="card-body p-2 d-flex justify-content-between align-items-center">
                                                    <div class="flex-grow-1">
                                                        <div class="fw-bold text-dark">{{ $kbli->kbli->kode }} - {{ $kbli->kbli->judul }}</div>
                                                        <div class="form-check mt-1">
                                                            <input class="form-check-input kbli-utama-radio" type="radio" name="kbli_utama" value="{{ $kbli->kbli_id }}" id="utama_{{ $kbli->kbli_id }}" {{ $kbli->is_utama ? 'checked' : '' }} required>
                                                            <label class="form-check-label small" for="utama_{{ $kbli->kbli_id }}">
                                                                Jadikan KBLI Utama
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <button type="button" class="btn btn-sm btn-outline-danger border-0 btn-remove-kbli ms-2">
                                                        <i class="bi bi-x-lg"></i> Hapus
                                                    </button>
                                                    <input type="hidden" name="kbli_id[]" value="{{ $kbli->kbli_id }}">
                                                </div>
                                            </div>
                                            @endif
                                        @endforeach
                                    </div>
                                    <small class="text-muted d-block"><span class="text-danger">*</span> Pilih minimal 1 KBLI dan tentukan KBLI utama.</small>
                                </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Tanggal Berdiri Usaha <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="tanggal_berdiri" required value="{{ old('tanggal_berdiri', $user->tanggal_berdiri) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Nomor NPWP Usaha <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="npwp_usaha" required value="{{ old('npwp_usaha', $user->npwp_usaha) }}">
                                    </div>
                                    
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Nomor NIB <span class="text-muted">(Opsional)</span></label>
                                        <input type="text" class="form-control" name="nib" value="{{ old('nib', $user->nib) }}">
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
                                        <label class="form-label fw-semibold">Nilai Modal (Rp) / Tahun <span class="text-muted">(Opsional)</span></label>
                                        <input type="number" class="form-control" name="nilai_modal" value="{{ old('nilai_modal', $user->nilai_modal) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Kategori Omzet Usaha <span class="text-muted">(Opsional)</span></label>
                                        <input type="text" class="form-control" name="omzet_usaha" value="{{ old('omzet_usaha', $user->omzet_usaha) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Nilai Omzet (Rp) / Tahun <span class="text-muted">(Opsional)</span></label>
                                        <input type="number" class="form-control" name="nilai_omzet" value="{{ old('nilai_omzet', $user->nilai_omzet) }}">
                                    </div>
                                    
                                <div class="col-12 mt-4 mb-2">
                                  <div class="col-12 mt-4 mb-2">
                                      <h6 class="fw-bold border-bottom pb-2">Data Tenaga Kerja <span class="text-danger">*</span></h6>
                                  </div>
                                  
                                    <!-- KARYAWAN TETAP -->
                                    <div class="col-12 mt-2">
                                        <div class="card bg-light border-0 shadow-sm">
                                            <div class="card-header bg-primary text-white fw-bold">
                                                TOTAL KARYAWAN TETAP
                                            </div>
                                            <div class="card-body">
                                                <div class="row g-3">
                                                    <div class="col-12 col-md-4">
                                                        <label class="form-label fw-semibold">Laki-laki <span class="text-danger">*</span></label>
                                                        <input type="number" class="form-control karyawan-input" id="tetap_laki_laki" name="karyawan_tetap_laki_laki" value="{{ old('karyawan_tetap_laki_laki', $user->karyawan_tetap_laki_laki ?? 0) }}" min="0" step="1" required>
                                                    </div>
                                                    <div class="col-12 col-md-4">
                                                        <label class="form-label fw-semibold">Perempuan <span class="text-danger">*</span></label>
                                                        <input type="number" class="form-control karyawan-input" id="tetap_perempuan" name="karyawan_tetap_perempuan" value="{{ old('karyawan_tetap_perempuan', $user->karyawan_tetap_perempuan ?? 0) }}" min="0" step="1" required>
                                                    </div>
                                                    <div class="col-12 col-md-4">
                                                        <label class="form-label fw-semibold">Total</label>
                                                        <input type="number" class="form-control" id="total_tetap" readonly value="{{ old('total_karyawan_tetap', $user->total_karyawan_tetap ?? 0) }}" style="background-color: #e9ecef; font-weight: bold;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- KARYAWAN TIDAK TETAP -->
                                    <div class="col-12 mt-3">
                                        <div class="card bg-light border-0 shadow-sm">
                                            <div class="card-header bg-secondary text-white fw-bold">
                                                TOTAL KARYAWAN TIDAK TETAP
                                            </div>
                                            <div class="card-body">
                                                <div class="row g-3">
                                                    <div class="col-12 col-md-4">
                                                        <label class="form-label fw-semibold">Laki-laki <span class="text-danger">*</span></label>
                                                        <input type="number" class="form-control karyawan-input" id="tidak_tetap_laki_laki" name="karyawan_tidak_tetap_laki_laki" value="{{ old('karyawan_tidak_tetap_laki_laki', $user->karyawan_tidak_tetap_laki_laki ?? 0) }}" min="0" step="1" required>
                                                    </div>
                                                    <div class="col-12 col-md-4">
                                                        <label class="form-label fw-semibold">Perempuan <span class="text-danger">*</span></label>
                                                        <input type="number" class="form-control karyawan-input" id="tidak_tetap_perempuan" name="karyawan_tidak_tetap_perempuan" value="{{ old('karyawan_tidak_tetap_perempuan', $user->karyawan_tidak_tetap_perempuan ?? 0) }}" min="0" step="1" required>
                                                    </div>
                                                    <div class="col-12 col-md-4">
                                                        <label class="form-label fw-semibold">Total</label>
                                                        <input type="number" class="form-control" id="total_tidak_tetap" readonly value="{{ old('total_karyawan_tidak_tetap', $user->total_karyawan_tidak_tetap ?? 0) }}" style="background-color: #e9ecef; font-weight: bold;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- TOTAL KESELURUHAN -->
                                    <div class="col-12 mt-3">
                                        <div class="card border-primary shadow-sm text-center">
                                            <div class="card-body bg-primary text-white rounded">
                                                <h5 class="fw-bold mb-2">TOTAL TENAGA KERJA</h5>
                                                <h2 class="mb-0 fw-bold" id="grand_total">{{ old('total_tenaga_kerja', $user->total_tenaga_kerja ?? 0) }}</h2>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 mt-3">
                                        <label class="form-label fw-semibold">Kapasitas Produksi / Tahun <span class="text-muted">(Opsional)</span></label>
                                        <input type="text" class="form-control" name="kapasitas_produksi" value="{{ old('kapasitas_produksi', $user->kapasitas_produksi) }}">
                                    </div>

                                <div class="col-12 mt-4 mb-2">
                                    <h6 class="fw-bold border-bottom pb-2">Alamat Usaha <span class="text-danger">*</span></h6>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold">Provinsi Usaha <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="provinsi_usaha" required value="{{ old('provinsi_usaha', $user->provinsi_usaha) }}">
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold">Kabupaten/Kota Usaha <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="kabupaten_usaha" required value="{{ old('kabupaten_usaha', $user->kabupaten_usaha) }}">
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold">Kecamatan Usaha <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="kecamatan_usaha" required value="{{ old('kecamatan_usaha', $user->kecamatan_usaha) }}">
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold">Desa/Kelurahan Usaha <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="desa_usaha" required value="{{ old('desa_usaha', $user->desa_usaha) }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Alamat Lengkap Usaha <span class="text-danger">*</span></label>
                                    <textarea class="form-control" name="alamat_usaha" rows="2" required>{{ old('alamat_usaha', $user->alamat_usaha) }}</textarea>
                                </div>
                                    
                                </div>
                            </div>

                            <!-- TAB DIGITALISASI & EKSPOR -->
                            <div class="tab-pane fade" id="digital" role="tabpanel" aria-labelledby="digital-tab">
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Kontak Usaha (No. Telepon/HP) <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="no_telepon_usaha" required value="{{ old('no_telepon_usaha', $user->no_telepon_usaha) }}">
                                    </div>
                                    <label class="form-label fw-semibold">Email Usaha <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" name="email_usaha" required value="{{ old('email_usaha', $user->email_usaha) }}">
                                    </div>
                                    
                                    <!-- INFORMASI USAHA ONLINE -->
                                    <div class="col-12 mt-4">
                                        <div class="card bg-light border-0 shadow-sm">
                                            <div class="card-header bg-primary text-white fw-bold">
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
                                                    <div class="col-12">
                                                        <label class="form-label fw-semibold">Facebook <span class="text-muted">(Opsional)</span></label>
                                                        <input type="url" class="form-control" name="facebook_usaha" value="{{ old('facebook_usaha', $user->facebook_usaha) }}" placeholder="https://facebook.com/contoh">
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label fw-semibold">Instagram <span class="text-muted">(Opsional)</span></label>
                                                        <input type="url" class="form-control" name="instagram_usaha" value="{{ old('instagram_usaha', $user->instagram_usaha) }}" placeholder="https://instagram.com/contoh">
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label fw-semibold">TikTok <span class="text-muted">(Opsional)</span></label>
                                                        <input type="url" class="form-control" name="tiktok_usaha" value="{{ old('tiktok_usaha', $user->tiktok_usaha) }}" placeholder="https://tiktok.com/@contoh">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- MARKETPLACE -->
                                    <div class="col-12 mt-4">
                                        <div class="card bg-light border-0 shadow-sm">
                                            <div class="card-header bg-secondary text-white fw-bold">
                                                <i class="bi bi-shop me-2"></i>MARKETPLACE YANG DIGUNAKAN
                                            </div>
                                            <div class="card-body">
                                                <div class="row g-3">
                                                    <div class="col-12">
                                                        <label class="form-label fw-semibold">Shopee <span class="text-muted">(Opsional)</span></label>
                                                        <input type="url" class="form-control" name="shopee" value="{{ old('shopee', $user->shopee) }}" placeholder="https://shopee.co.id/contoh">
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label fw-semibold">Tokopedia <span class="text-muted">(Opsional)</span></label>
                                                        <input type="url" class="form-control" name="tokopedia" value="{{ old('tokopedia', $user->tokopedia) }}" placeholder="https://www.tokopedia.com/contoh">
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label fw-semibold">Lazada <span class="text-muted">(Opsional)</span></label>
                                                        <input type="url" class="form-control" name="lazada" value="{{ old('lazada', $user->lazada) }}" placeholder="https://www.lazada.co.id/shop/contoh">
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label fw-semibold">Blibli <span class="text-muted">(Opsional)</span></label>
                                                        <input type="url" class="form-control" name="blibli" value="{{ old('blibli', $user->blibli) }}" placeholder="https://www.blibli.com/merchant/contoh">
                                                    </div>
                                                    
                                                    <div class="col-12 mt-4 border-top pt-3">
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
                                                </div>
                                            </div>
                                        </div>
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
                                    
                                      <div class="col-12 mt-3">
                                          <label class="form-label fw-semibold">Anggota Koperasi <span class="text-muted">(Opsional)</span></label>
                                          <input type="text" class="form-control" name="anggota_koperasi" placeholder="Nama koperasi jika menjadi anggota" value="{{ old('anggota_koperasi', $user->anggota_koperasi) }}">
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
                                        <label class="form-label fw-semibold">Upload NPWP <span class="text-danger">*</span></label>
                                        
                                        @if($user->npwp_file)
                                            <div class="mb-3">
                                                @php $ext = pathinfo($user->npwp_file, PATHINFO_EXTENSION); @endphp
                                                @if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png']))
                                                    <img src="{{ route('profile.document', ['type' => 'npwp', 'userId' => $user->id]) }}" alt="NPWP" class="img-thumbnail" style="max-height: 150px;">
                                                @else
                                                    <a href="{{ route('profile.document', ['type' => 'npwp', 'userId' => $user->id]) }}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="bi bi-file-earmark-pdf"></i> Lihat Dokumen NPWP</a>
                                                @endif
                                            </div>
                                        @endif
                                        <input type="file" class="form-control @error('npwp_file') is-invalid @enderror" name="npwp_file" accept=".jpg,.jpeg,.png,.pdf" {{ $user->npwp_file ? '' : 'required' }}>
                                        <small class="text-muted">Format: PDF, JPG, PNG. Maksimal 5MB.</small>
                                        @error('npwp_file')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Upload Foto Profil (Avatar) <span class="text-danger">*</span></label>
                                        
                                        @if($user->foto)
                                            <div class="mb-3">
                                                <img src="{{ asset('storage/' . $user->foto) }}" alt="Avatar" class="img-thumbnail" style="max-height: 100px;">
                                            </div>
                                        @endif
                                        <input type="file" class="form-control" name="foto" accept="image/*" {{ $user->foto ? '' : 'required' }}>
                                        <small class="text-muted">Format: JPG, PNG, GIF. Maksimal 2MB.</small>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Upload File Produk (Katalog/Brosur) <span class="text-danger">*</span></label>
                                        
                                        @if($user->file_produk)
                                            <div class="mb-3">
                                                <a href="{{ route('profile.document', ['type' => 'produk', 'userId' => $user->id]) }}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="bi bi-file-earmark-arrow-down"></i> Lihat/Download File Produk</a>
                                            </div>
                                        @endif
                                        <input type="file" class="form-control @error('file_produk') is-invalid @enderror" name="file_produk" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" {{ $user->file_produk ? '' : 'required' }}>
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

    $(document).on('click', '.remove-mp', function() {
        $(this).closest('.mp-row').remove();
    });

</script>



<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    
    // === HIERARCHICAL KBLI LOGIC ===
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
        <div class="card mb-3 kbli-row shadow-sm border-0" data-index="${id}">
            <div class="card-header bg-light border-bottom-0 d-flex justify-content-between align-items-center">
                <span class="fw-bold text-primary"><i class="bi bi-tag-fill me-2"></i>${labelUsaha}</span>
                ${id > 0 ? '<button type="button" class="btn btn-sm btn-outline-danger border-0 btn-remove-row"><i class="bi bi-trash"></i> Hapus</button>' : ''}
            </div>
            <div class="card-body bg-white border">
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold small">Kategori <span class="text-danger">*</span></label>
                        <select class="form-select select-kategori" required>
                            <option value="">Pilih Kategori</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold small">Golongan <span class="text-danger">*</span></label>
                        <select class="form-select select-golongan" required disabled>
                            <option value="">Pilih Golongan</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold small">KBLI / Kegiatan Usaha <span class="text-danger">*</span></label>
                        <select class="form-control select-kbli" required disabled>
                            ${data && data.kbli ? `<option value="${data.kbli.id}" selected>${data.kbli.kode} - ${data.kbli.judul}</option>` : '<option value="">Pilih KBLI...</option>'}
                        </select>
                        <input type="hidden" name="kbli_id[]" class="kbli-id-hidden" value="${data ? data.kbli_id : ''}">
                    </div>
                    <div class="col-12">
                        <div class="p-3 bg-light rounded text-muted small uraian-box">
                            ${data && data.kbli ? data.kbli.uraian : 'Pilih KBLI untuk melihat deskripsi uraian kegiatan usaha.'}
                        </div>
                    </div>
                    <div class="col-12 mt-2">
                        <div class="form-check">
                            <input class="form-check-input kbli-utama-radio" type="radio" name="kbli_utama" value="${data ? data.kbli_id : id}" id="utama_${id}" ${isUtama ? 'checked' : ''} required>
                            <label class="form-check-label fw-bold text-dark" for="utama_${id}">
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
            $radioUtama.val(id); // reset fallback
            $uraianBox.html('Pilih KBLI untuk melihat deskripsi uraian kegiatan usaha.');
            if (val) fetchGolongans(val, $selectGolongan);
        });

        // Golongan Change Event
        $selectGolongan.on('change', function() {
            let val = $(this).val();
            $selectKbli.empty().prop('disabled', !val);
            $hiddenId.val('');
            $radioUtama.val(id);
            $uraianBox.html('Pilih KBLI untuk melihat deskripsi uraian kegiatan usaha.');
        });
        
        // Init Select2 for this row
        $selectKbli.select2({
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
                    "<div class='p-1'>" +
                    "<div class='fw-bold text-dark'>" + kbli.text + "</div>" +
                    "<div class='small text-muted mt-1'>Uraian: " + (kbli.uraian || '-') + "</div>" +
                    "</div>"
                );
            },
            templateSelection: function (kbli) {
                return kbli.text || kbli.id || 'Pilih KBLI...';
            }
        }).on('select2:select', function(e) {
            let item = e.params.data;
            $hiddenId.val(item.id);
            $radioUtama.val(item.id); // set radio to actual ID
            $uraianBox.html(`<strong>Uraian:</strong><br/>${item.uraian}`);
        }).on('select2:clear', function() {
            $hiddenId.val('');
            $radioUtama.val(id);
            $uraianBox.html('Pilih KBLI untuk melihat deskripsi uraian kegiatan usaha.');
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
        
        // Jika Utama dihapus, jadikan row pertama sebagai utama
        if (isUtama && $('.kbli-row').length > 0) {
            $('.kbli-row').first().find('.kbli-utama-radio').prop('checked', true);
        }
    });


    // Handle hapus KBLI dari daftar
    $(document).on('click', '.btn-remove-kbli', function() {
        var card = $(this).closest('.kbli-selected-card');
        var isUtama = card.find('.kbli-utama-radio').is(':checked');
        card.remove();
        
        // Jika yang dihapus adalah KBLI Utama, otomatis pindahkan ke KBLI pertama (jika ada)
        if (isUtama && $('.kbli-selected-card').length > 0) {
            $('.kbli-selected-card').first().find('.kbli-utama-radio').prop('checked', true);
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
        $('#grand_total').text(grandTotal);
    }
    
    $('.karyawan-input').on('input', calculateTotalKaryawan);
});
</script>


@endpush
@endsection




