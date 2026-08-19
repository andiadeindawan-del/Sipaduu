@extends('layouts.admin')

@section('title', 'Detail User')

@section('header')
<div class="page-heading d-flex justify-content-between align-items-center">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-person" aria-hidden="true"></i></span>
        <div>
            <p class="eyebrow mb-1">Management</p>
            <h1 class="h3 mb-1">{{ $user->nama ?? $user->name }}</h1>
            <p class="text-muted mb-0">{{ $user->email }}</p>
        </div>
    </div>
    <div class="heading-actions d-flex gap-2">
        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning btn-sm">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="row g-4">
        <!-- Sidebar Profile -->
        <div class="col-lg-4">
            <div class="panel text-center p-4">
                @if($user->foto)
                <img src="{{ Storage::url($user->foto) }}" class="rounded-circle mb-3 border border-3 border-primary"
                     width="120" height="120" style="object-fit:cover">
                @else
                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-3 border border-3 border-primary"
                     style="width:120px;height:120px;font-size:2.5rem;font-weight:700;">
                    {{ strtoupper(substr($user->nama ?? $user->name ?? 'U', 0, 2)) }}
                </div>
                @endif
                <h5 class="fw-bold mb-1">{{ $user->nama ?? $user->name }}</h5>
                <span class="badge 
                    @if($user->role === 'admin') badge-danger
                    @elseif($user->role === 'trainer') badge-info
                    @else badge-secondary
                    @endif
                    mb-2 px-3 py-2 fs-6
                ">
                    <i class="bi bi-circle-fill me-1" style="font-size: 6px;"></i>
                    {{ ucfirst($user->role) }}
                </span>
                <div>
                    <span class="badge 
                        @if($user->status === 'aktif') badge-success
                        @else badge-secondary
                        @endif
                        px-3 py-2 fs-6
                    ">
                        <i class="bi bi-circle-fill me-1" style="font-size: 6px;"></i>
                        {{ ucfirst($user->status) }}
                    </span>
                </div>
                <hr>
                <a href="{{ route('admin.users.change-password.form', $user) }}" class="btn btn-outline-warning btn-sm w-100">
                    <i class="bi bi-key me-1"></i> Ubah Password
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Informasi Akun -->
            <div class="panel">
                <div class="panel-header">
                    <h5 class="section-title"><i class="bi bi-info-circle"></i> Informasi Akun</h5>
                </div>
                <div class="p-4">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <div class="detail-item">
                                <label class="text-muted small fw-semibold text-uppercase">NIK</label>
                                <p class="fw-semibold mb-0"><code>{{ $user->nik ?? '-' }}</code></p>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="detail-item">
                                <label class="text-muted small fw-semibold text-uppercase">Email</label>
                                <p class="fw-semibold mb-0">{{ $user->email }}</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="detail-item">
                                <label class="text-muted small fw-semibold text-uppercase">Departemen</label>
                                <p class="fw-semibold mb-0">{{ $user->departemen ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="detail-item">
                                <label class="text-muted small fw-semibold text-uppercase">Jabatan</label>
                                <p class="fw-semibold mb-0">{{ $user->jabatan ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="detail-item">
                                <label class="text-muted small fw-semibold text-uppercase">No. Telepon</label>
                                <p class="fw-semibold mb-0">{{ $user->no_telepon ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="detail-item">
                                <label class="text-muted small fw-semibold text-uppercase">Bergabung</label>
                                <p class="fw-semibold mb-0">{{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($user->role === 'peserta')
            <!-- DATA PRIBADI UMK -->
            <div class="panel mt-4">
                <div class="panel-header">
                    <h5 class="section-title"><i class="bi bi-person-badge"></i> Data Pribadi (UMK)</h5>
                </div>
                <div class="p-4">
                    <div class="row g-3">
                        <div class="col-12 col-md-4">
                            <div class="detail-item">
                                <label class="text-muted small fw-semibold text-uppercase">Jenis Kelamin</label>
                                <p class="fw-semibold mb-0">{{ $user->jenis_kelamin == 'L' ? 'Laki-laki' : ($user->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="detail-item">
                                <label class="text-muted small fw-semibold text-uppercase">Tempat / Tanggal Lahir</label>
                                <p class="fw-semibold mb-0">
                                    {{ $user->tempat_lahir ?? '-' }}
                                    {{ $user->tanggal_lahir ? ', ' . \Carbon\Carbon::parse($user->tanggal_lahir)->format('d M Y') : '' }}
                                </p>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="detail-item">
                                <label class="text-muted small fw-semibold text-uppercase">Agama</label>
                                <p class="fw-semibold mb-0">{{ $user->agama ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="detail-item">
                                <label class="text-muted small fw-semibold text-uppercase">Status Pernikahan</label>
                                <p class="fw-semibold mb-0">{{ $user->status_pernikahan ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="detail-item">
                                <label class="text-muted small fw-semibold text-uppercase">Pendidikan Terakhir</label>
                                <p class="fw-semibold mb-0">{{ $user->pendidikan_terakhir ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="detail-item">
                                <label class="text-muted small fw-semibold text-uppercase">Status Disabilitas</label>
                                <p class="fw-semibold mb-0">{{ $user->disabilitas ?? '-' }}</p>
                            </div>
                        </div>
                        <!-- KTP Moved to Dokumen Section -->
                        <div class="col-12">
                            <div class="detail-item">
                                <label class="text-muted small fw-semibold text-uppercase">Alamat Domisili</label>
                                <div class="p-3 bg-light rounded-3">
                                    <p class="fw-semibold mb-0">
                                        {{ $user->alamat_lengkap ?? '-' }}
                                        @if($user->desa || $user->kecamatan || $user->kabupaten || $user->provinsi)
                                            <br>
                                            <small class="text-muted">
                                                {{ $user->desa ? 'Desa/Kel: ' . $user->desa : '' }}
                                                {{ $user->kecamatan ? ' | Kec: ' . $user->kecamatan : '' }}
                                                {{ $user->kabupaten ? ' | Kab/Kota: ' . $user->kabupaten : '' }}
                                                {{ $user->provinsi ? ' | Prov: ' . $user->provinsi : '' }}
                                                {{ $user->kode_pos_domisili ? ' | Kode Pos: ' . $user->kode_pos_domisili : '' }}
                                            </small>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DATA USAHA -->
            <div class="panel mt-4">
                <div class="panel-header">
                    <h5 class="section-title"><i class="bi bi-building"></i> Data Usaha</h5>
                </div>
                <div class="p-4">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <div class="detail-item">
                                <label class="text-muted small fw-semibold text-uppercase">Nama Usaha</label>
                                <p class="fw-semibold mb-0">{{ $user->nama_usaha ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="detail-item">
                                <label class="text-muted small fw-semibold text-uppercase">Merek Produk</label>
                                <p class="fw-semibold mb-0">{{ $user->merek_produk ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="detail-item">
                                <label class="text-muted small fw-semibold text-uppercase">Jabatan Usaha</label>
                                <p class="fw-semibold mb-0">{{ $user->jabatan_usaha ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="detail-item">
                                <label class="text-muted small fw-semibold text-uppercase">Tanggal Berdiri</label>
                                <p class="fw-semibold mb-0">{{ $user->tanggal_berdiri ? \Carbon\Carbon::parse($user->tanggal_berdiri)->format('d M Y') : '-' }}</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="detail-item">
                                <label class="text-muted small fw-semibold text-uppercase">Sektor Usaha</label>
                                <p class="fw-semibold mb-0">{{ $user->sektor_usaha ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="detail-item">
                                <label class="text-muted small fw-semibold text-uppercase">Bidang Usaha</label>
                                <p class="fw-semibold mb-0">{{ $user->bidang_usaha ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="detail-item">
                                <label class="text-muted small fw-semibold text-uppercase">No. Telepon Usaha</label>
                                <p class="fw-semibold mb-0">{{ $user->no_telepon_usaha ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="detail-item">
                                <label class="text-muted small fw-semibold text-uppercase">Jumlah Karyawan</label>
                                <p class="fw-semibold mb-0">{{ $user->jumlah_karyawan ? $user->jumlah_karyawan . ' orang' : '-' }}</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="detail-item">
                                <label class="text-muted small fw-semibold text-uppercase">Kapasitas Produksi</label>
                                <p class="fw-semibold mb-0">{{ $user->kapasitas_produksi ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="detail-item">
                                <label class="text-muted small fw-semibold text-uppercase">Anggota Koperasi</label>
                                <p class="fw-semibold mb-0">{{ $user->anggota_koperasi ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="detail-item">
                                <label class="text-muted small fw-semibold text-uppercase">Legalitas & Keuangan</label>
                                <div class="p-3 bg-light rounded-3">
                                    <div class="row g-2">
                                        <div class="col-12 col-md-6">
                                            <strong>NIB:</strong> {{ $user->nib ?? '-' }}
                                            <br>
                                            <small class="text-muted">Status: {{ $user->status_nib ?? '-' }} | Lama: {{ $user->lama_nib ?? '-' }}</small>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <strong>NPWP Usaha:</strong> {{ $user->npwp_usaha ?? '-' }}
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <strong>Modal Usaha:</strong> {{ $user->modal_usaha ?? '-' }}
                                            <br>
                                            <small class="text-muted">Rp {{ number_format($user->nilai_modal ?? 0, 0, ',', '.') }}</small>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <strong>Omzet Usaha:</strong> {{ $user->omzet_usaha ?? '-' }}
                                            <br>
                                            <small class="text-muted">Rp {{ number_format($user->nilai_omzet ?? 0, 0, ',', '.') }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DIGITALISASI & EKSPOR -->
            <div class="panel mt-4">
                <div class="panel-header">
                    <h5 class="section-title"><i class="bi bi-globe2"></i> Digitalisasi & Ekspor</h5>
                </div>
                <div class="p-4">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <div class="detail-item">
                                <label class="text-muted small fw-semibold text-uppercase">Email Usaha</label>
                                <p class="fw-semibold mb-0">{{ $user->email_usaha ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="detail-item">
                                <label class="text-muted small fw-semibold text-uppercase">Website Usaha</label>
                                <p class="fw-semibold mb-0">
                                    @if($user->website_usaha)
                                    <a href="{{ $user->website_usaha }}" target="_blank" class="text-primary">
                                        <i class="bi bi-box-arrow-up-right me-1"></i> {{ Str::limit($user->website_usaha, 30) }}
                                    </a>
                                    @else
                                    <span class="text-muted">-</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="detail-item">
                                <label class="text-muted small fw-semibold text-uppercase">Media Sosial</label>
                                <p class="fw-semibold mb-0">{{ $user->medsos_usaha ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="detail-item">
                                <label class="text-muted small fw-semibold text-uppercase">Marketplace</label>
                                <p class="fw-semibold mb-0">{{ $user->marketplace ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="detail-item">
                                <label class="text-muted small fw-semibold text-uppercase">Pengadaan Barang/Jasa</label>
                                <p class="fw-semibold mb-0">{{ $user->pengadaan_barang ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="detail-item">
                                <label class="text-muted small fw-semibold text-uppercase">Akses Kredit</label>
                                <p class="fw-semibold mb-0">{{ $user->akses_kredit ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="detail-item">
                                <label class="text-muted small fw-semibold text-uppercase">Tabungan Usaha</label>
                                <p class="fw-semibold mb-0">{{ $user->tabungan ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="detail-item">
                                <label class="text-muted small fw-semibold text-uppercase">Perizinan Usaha</label>
                                <p class="fw-semibold mb-0">{{ $user->perizinan_usaha ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="detail-item">
                                <label class="text-muted small fw-semibold text-uppercase">Sertifikasi Produk</label>
                                <p class="fw-semibold mb-0">{{ $user->sertifikasi_produk ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="detail-item">
                                <label class="text-muted small fw-semibold text-uppercase">Jangkauan Pemasaran</label>
                                <p class="fw-semibold mb-0">{{ $user->jangkauan_pemasaran ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="detail-item">
                                <label class="text-muted small fw-semibold text-uppercase">Lokasi Pemasaran</label>
                                <p class="fw-semibold mb-0">{{ $user->lokasi_pemasaran ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="detail-item">
                                <label class="text-muted small fw-semibold text-uppercase">Pasok Bahan Baku</label>
                                <p class="fw-semibold mb-0">{{ $user->pasok_bahan_baku ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="detail-item">
                                <label class="text-muted small fw-semibold text-uppercase">Kemitraan</label>
                                <p class="fw-semibold mb-0">{{ $user->kemitraan ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="detail-item">
                                <label class="text-muted small fw-semibold text-uppercase">Informasi Ekspor</label>
                                <div class="p-3 bg-light rounded-3">
                                    <div class="row g-2">
                                        <div class="col-12 col-md-6">
                                            <strong>Status Ekspor:</strong> {{ $user->status_ekspor ?? '-' }}
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <strong>Negara Tujuan:</strong> {{ $user->negara_ekspor ?? '-' }}
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <strong>Metode Ekspor:</strong> {{ $user->metode_ekspor ?? '-' }}
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <strong>Volume Ekspor:</strong> {{ $user->volume_ekspor ?? '-' }}
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <strong>Nilai Ekspor:</strong> Rp {{ number_format($user->nilai_ekspor ?? 0, 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- INFORMASI TAMBAHAN -->
            <div class="panel mt-4">
                <div class="panel-header">
                    <h5 class="section-title"><i class="bi bi-file-text"></i> Informasi Tambahan</h5>
                </div>
                <div class="p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="detail-item">
                                <label class="text-muted small fw-semibold text-uppercase">Permasalahan Usaha</label>
                                <p class="fw-semibold mb-0">{{ $user->permasalahan ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="detail-item">
                                <label class="text-muted small fw-semibold text-uppercase">Kebutuhan Diklat</label>
                                <p class="fw-semibold mb-0">{{ $user->kebutuhan_diklat ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="detail-item">
                                <label class="text-muted small fw-semibold text-uppercase">Riwayat Pelatihan</label>
                                <p class="fw-semibold mb-0">{{ $user->riwayat_pelatihan ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="detail-item">
                                <label class="text-muted small fw-semibold text-uppercase">Jenis Pelatihan Diikuti</label>
                                <p class="fw-semibold mb-0">{{ $user->jenis_pelatihan_diikuti ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="detail-item">
                                <label class="text-muted small fw-semibold text-uppercase">Masukan & Saran</label>
                                <p class="fw-semibold mb-0">{{ $user->masukan_saran ?? '-' }}</p>
                            </div>
                        </div>
                        <!-- File Produk moved to Dokumen Section -->
                    </div>
                </div>
            </div>

            <!-- DOKUMEN PESERTA -->
            <div class="panel mt-4 border-0 shadow-sm">
                <div class="panel-header bg-light">
                    <h5 class="section-title fw-bold mb-0"><i class="bi bi-folder-fill me-2 text-primary"></i> Dokumen Peserta</h5>
                </div>
                <div class="p-4">
                    <div class="row g-4">
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="border rounded-3 p-3 h-100 bg-white">
                                <label class="text-muted small fw-semibold text-uppercase d-block mb-2">KTP</label>
                                @if($user->ktp_file)
                                    <a href="{{ route('profile.document', ['type' => 'ktp', 'userId' => $user->id]) }}" target="_blank" class="btn btn-sm btn-primary w-100">
                                        <i class="bi bi-file-earmark-pdf me-1"></i> Lihat / Download
                                    </a>
                                @else
                                    <span class="badge bg-secondary">Belum diupload</span>
                                @endif
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="border rounded-3 p-3 h-100 bg-white">
                                <label class="text-muted small fw-semibold text-uppercase d-block mb-2">NIB</label>
                                @if($user->nib_file)
                                    <a href="{{ route('profile.document', ['type' => 'nib', 'userId' => $user->id]) }}" target="_blank" class="btn btn-sm btn-primary w-100">
                                        <i class="bi bi-file-earmark-pdf me-1"></i> Lihat / Download
                                    </a>
                                @else
                                    <span class="badge bg-secondary">Belum diupload</span>
                                @endif
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="border rounded-3 p-3 h-100 bg-white">
                                <label class="text-muted small fw-semibold text-uppercase d-block mb-2">NPWP</label>
                                @if($user->npwp_file)
                                    <a href="{{ route('profile.document', ['type' => 'npwp', 'userId' => $user->id]) }}" target="_blank" class="btn btn-sm btn-primary w-100">
                                        <i class="bi bi-file-earmark-pdf me-1"></i> Lihat / Download
                                    </a>
                                @else
                                    <span class="badge bg-secondary">Belum diupload</span>
                                @endif
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="border rounded-3 p-3 h-100 bg-white">
                                <label class="text-muted small fw-semibold text-uppercase d-block mb-2">Foto Profil</label>
                                @if($user->foto)
                                    <a href="{{ asset('storage/' . $user->foto) }}" target="_blank" class="btn btn-sm btn-info text-white w-100">
                                        <i class="bi bi-image me-1"></i> Lihat Foto
                                    </a>
                                @else
                                    <span class="badge bg-secondary">Belum diupload</span>
                                @endif
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="border rounded-3 p-3 h-100 bg-white">
                                <label class="text-muted small fw-semibold text-uppercase d-block mb-2">File Produk (Katalog/Brosur)</label>
                                @if($user->file_produk)
                                    <a href="{{ route('profile.document', ['type' => 'produk', 'userId' => $user->id]) }}" target="_blank" class="btn btn-sm btn-primary w-100">
                                        <i class="bi bi-file-earmark-arrow-down me-1"></i> Lihat / Download
                                    </a>
                                @else
                                    <span class="badge bg-secondary">Belum diupload</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if($user->isTrainer())
            <!-- Training Diajar -->
            <div class="panel mt-4">
                <div class="panel-header">
                    <h5 class="section-title"><i class="bi bi-journal-bookmark"></i> Training Diajar</h5>
                </div>
                <div class="p-4">
                    @forelse($user->trainingDiajar as $t)
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <span class="fw-semibold">{{ $t->judul }}</span>
                        <span class="badge 
                            @if($t->status == 'published') badge-published
                            @elseif($t->status == 'berjalan') badge-berjalan
                            @elseif($t->status == 'selesai') badge-selesai
                            @else badge-draft
                            @endif
                        ">
                            <i class="bi bi-circle-fill me-1" style="font-size: 6px;"></i>
                            {{ ucfirst($t->status) }}
                        </span>
                    </div>
                    @empty
                    <p class="text-muted mb-0">Belum mengajar training apapun.</p>
                    @endforelse
                </div>
            </div>
            @endif

            @if($user->isPeserta())
            <!-- Training Diikuti -->
            <div class="panel mt-4">
                <div class="panel-header">
                    <h5 class="section-title"><i class="bi bi-journal-check"></i> Training Diikuti</h5>
                </div>
                <div class="p-4">
                    @forelse($user->trainingDiikuti as $t)
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <span class="fw-semibold">{{ $t->judul }}</span>
                        <span class="badge 
                            @if($t->pivot->status == 'disetujui') badge-success
                            @elseif($t->pivot->status == 'pending') badge-warning
                            @elseif($t->pivot->status == 'ditolak') badge-danger
                            @else badge-secondary
                            @endif
                        ">
                            <i class="bi bi-circle-fill me-1" style="font-size: 6px;"></i>
                            {{ ucfirst($t->pivot->status) }}
                        </span>
                    </div>
                    @empty
                    <p class="text-muted mb-0">Belum mengikuti training apapun.</p>
                    @endforelse
                </div>
            </div>
            @endif
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
        background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
        color: #28c76f;
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
        align-items: center;
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

    /* ============================================================
       DETAIL ITEMS
    ============================================================ */
    .detail-item {
        padding: 0.5rem 0;
        border-bottom: 1px solid #f8f9fa;
    }
    .detail-item:last-child {
        border-bottom: none;
    }
    .detail-item label {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #8a93a3;
        font-weight: 600;
        display: block;
        margin-bottom: 0.1rem;
    }
    .detail-item p {
        font-size: 0.9rem;
        color: #1a2236;
        margin-bottom: 0;
    }

    /* ============================================================
       BADGE
    ============================================================ */
    .badge {
        font-weight: 500;
        padding: 0.4rem 0.8rem;
        font-size: 0.75rem;
    }
    .badge-success {
        background: #d4edda !important;
        color: #155724 !important;
    }
    .badge-danger {
        background: #f8d7da !important;
        color: #721c24 !important;
    }
    .badge-warning {
        background: #fff3cd !important;
        color: #856404 !important;
    }
    .badge-info {
        background: #cfe2ff !important;
        color: #084298 !important;
    }
    .badge-secondary {
        background: #e2e8f0 !important;
        color: #4a5568 !important;
    }
    .badge-published {
        background: #d4edda !important;
        color: #155724 !important;
    }
    .badge-berjalan {
        background: #cfe2ff !important;
        color: #084298 !important;
    }
    .badge-selesai {
        background: #e2e8f0 !important;
        color: #4a5568 !important;
    }
    .badge-draft {
        background: #e2e8f0 !important;
        color: #4a5568 !important;
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
    
    .btn-warning {
        background: #ff9f43;
        border-color: #ff9f43;
        color: #fff;
    }
    .btn-warning:hover {
        background: #f08c2e;
        border-color: #f08c2e;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(255, 159, 67, 0.3);
    }
    
    .btn-outline-secondary {
        border-color: #e2e8f0;
        color: #4a5568;
    }
    .btn-outline-secondary:hover {
        background: #e2e8f0;
        border-color: #d5dce6;
    }
    
    .btn-outline-warning {
        border-color: #ff9f43;
        color: #ff9f43;
    }
    .btn-outline-warning:hover {
        background: #ff9f43;
        border-color: #ff9f43;
        color: #fff;
    }
    
    .btn-outline-primary {
        border-color: #4e9af1;
        color: #4e9af1;
    }
    .btn-outline-primary:hover {
        background: #4e9af1;
        border-color: #4e9af1;
        color: #fff;
    }
    
    .btn-sm {
        padding: 0.3rem 0.8rem;
        font-size: 0.8rem;
    }

    /* ============================================================
       AVATAR
    ============================================================ */
    .rounded-circle {
        border-radius: 50% !important;
    }
    
    .border-primary {
        border-color: #4e9af1 !important;
    }

    /* ============================================================
       BG LIGHT
    ============================================================ */
    .bg-light {
        background-color: #f8f9fa !important;
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
            flex-wrap: wrap;
        }
        .panel-header {
            flex-direction: column;
            align-items: flex-start;
        }
        .panel-body {
            padding: 1.25rem !important;
        }
        .d-flex.justify-content-between.align-items-center.py-2 {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 0.5rem;
        }
        .row.g-3 > [class*="col-"] {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }
        .p-3.bg-light.rounded-3 {
            padding: 0.75rem !important;
        }
        .btn.w-100 {
            width: 100%;
        }
    }

    /* ============================================================
       ANIMATION
    ============================================================ */
    .panel {
        animation: fadeInUp 0.4s ease;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endpush
@endsection