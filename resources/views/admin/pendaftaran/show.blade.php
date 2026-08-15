@extends('layouts.admin')

@section('title', 'Detail Profil Peserta')

@section('header')
<div class="page-heading d-flex justify-content-between align-items-center">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-person-lines-fill"></i></span>
        <div>
            <p class="eyebrow">Manajemen Pendaftaran</p>
            <h1 class="h3 mb-0">Detail Profil Peserta</h1>
            <p class="text-muted mb-0">Informasi lengkap peserta <strong>{{ $registration->user->nama ?? $registration->user->name ?? 'Unknown' }}</strong></p>
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
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <!-- Info Pendaftaran -->
            <div class="panel mb-4">
                <div class="panel-header">
                    <div>
                        <h5 class="section-title"><i class="bi bi-info-circle"></i> Info Pendaftaran</h5>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <span class="badge 
                            @if($registration->status == 'pending') badge-warning
                            @elseif($registration->status == 'disetujui') badge-success
                            @elseif($registration->status == 'ditolak') badge-danger
                            @else badge-secondary
                            @endif
                            fs-6 py-2 px-3
                        ">
                            <i class="bi bi-circle-fill me-1" style="font-size: 6px;"></i>
                            {{ ucfirst($registration->status) }}
                        </span>
                        @if($registration->user->is_profil_lengkap)
                            <span class="badge badge-success fs-6 py-2 px-3">
                                <i class="bi bi-check-circle me-1"></i> ✅ Profil Lengkap
                            </span>
                        @else
                            <span class="badge badge-danger fs-6 py-2 px-3">
                                <i class="bi bi-exclamation-circle me-1"></i> ⚠️ Profil Belum Lengkap
                            </span>
                        @endif
                        <div class="heading-actions d-flex gap-2">
                            <a href="{{ route('admin.pendaftaran.index') }}" class="btn btn-secondary btn-sm">
                                <i class="bi bi-arrow-left"></i> Kembali
                             </a>
                        </div>
                    </div>
                </div>
                <div class="p-4">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <div class="info-item p-2 bg-light rounded-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="icon-circle-sm bg-success text-white">
                                        <i class="bi bi-journal-bookmark"></i>
                                    </div>
                                    <div>
                                        <label class="text-muted small fw-semibold text-uppercase d-block">Pelatihan</label>
                                        <p class="fw-semibold mb-0">{{ $registration->training->judul }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="info-item p-2 bg-light rounded-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="icon-circle-sm bg-info text-white">
                                        <i class="bi bi-calendar"></i>
                                    </div>
                                    <div>
                                        <label class="text-muted small fw-semibold text-uppercase d-block">Tanggal Daftar</label>
                                        <p class="fw-semibold mb-0">{{ $registration->updated_at ? $registration->updated_at->format('d M Y H:i') : '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        @if($registration->status == 'pending' && $registration->alasan_penolakan)
                        <div class="col-12 mt-2">
                            <div class="alert alert-warning mb-0">
                                <i class="bi bi-clock-history me-2"></i>
                                <strong>Riwayat Penolakan:</strong>
                                <p class="mb-0 mt-1 fst-italic">"{{ $registration->alasan_penolakan }}"</p>
                                <small class="text-muted d-block mt-1">Peserta telah memperbaiki profil dan mengajukan ulang pendaftaran.</small>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Main Panel -->
            <div class="panel">
                <div class="panel-header">
                    <h5 class="section-title"><i class="bi bi-person"></i> Detail Profil Peserta</h5>
                </div>
                
                <div class="p-4">
                    <!-- Avatar & Status Kelengkapan -->
                    <div class="row align-items-center mb-4">
                        <div class="col-12 col-md-3 text-center">
                            @if($registration->user->foto)
                                <img src="{{ asset('storage/' . $registration->user->foto) }}" 
                                     alt="Foto Peserta" 
                                     class="img-fluid rounded-circle shadow-sm border border-3 border-primary" 
                                     style="width: 100px; height: 100px; object-fit: cover;">
                            @else
                                <div class="avatar-circle bg-primary text-white d-flex align-items-center justify-content-center rounded-circle mx-auto shadow-sm border border-3 border-primary" 
                                     style="width: 100px; height: 100px; font-size: 2.5rem; font-weight: 700;">
                                    {{ strtoupper(substr($registration->user->nama ?? $registration->user->name ?? 'U', 0, 1)) }}
                                </div>
                            @endif
                            <h5 class="mt-3 mb-1 fw-bold">{{ $registration->user->nama ?? $registration->user->name ?? 'Unknown' }}</h5>
                            <span class="text-muted small">{{ $registration->user->email ?? '-' }}</span>
                        </div>
                        <div class="col-12 col-md-9">
                            <div class="row g-2">
                                <div class="col-12">
                                    <div class="p-3 rounded-3 {{ $registration->user->is_profil_lengkap ? 'bg-success bg-opacity-10 border border-success' : 'bg-danger bg-opacity-10 border border-danger' }}">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="display-6 {{ $registration->user->is_profil_lengkap ? 'text-success' : 'text-danger' }}">
                                                <i class="bi {{ $registration->user->is_profil_lengkap ? 'bi-check-circle-fill' : 'bi-exclamation-circle-fill' }}"></i>
                                            </div>
                                            <div>
                                                <h6 class="fw-bold mb-0 {{ $registration->user->is_profil_lengkap ? 'text-success' : 'text-danger' }}">
                                                    {{ $registration->user->is_profil_lengkap ? '✅ Profil Lengkap' : '⚠️ Profil Belum Lengkap' }}
                                                </h6>
                                                <p class="text-muted small mb-0">
                                                    {{ $registration->user->is_profil_lengkap ? 'Semua data persyaratan telah diisi dengan lengkap.' : 'Peserta belum melengkapi seluruh data persyaratan yang dibutuhkan.' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabs -->
                    <ul class="nav nav-tabs nav-tabs-custom" id="profileTabs" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#pribadi" type="button" role="tab">
                                <i class="bi bi-person me-1"></i> Pribadi
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#usaha" type="button" role="tab">
                                <i class="bi bi-building me-1"></i> Usaha
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#digital" type="button" role="tab">
                                <i class="bi bi-globe2 me-1"></i> Digital & Transformasi
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tambahan" type="button" role="tab">
                                <i class="bi bi-info-circle me-1"></i> Pelatihan
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#dokumen" type="button" role="tab">
                                <i class="bi bi-file-earmark-text me-1"></i> Dokumen
                            </button>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content border border-top-0 p-4 bg-white rounded-bottom" id="profileTabsContent">
                        
                        <!-- TAB: PRIBADI -->
                        <div class="tab-pane fade show active" id="pribadi" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="fw-bold text-primary mb-0"><i class="bi bi-person me-2"></i>Data Pribadi</h6>
                                        <span class="badge {{ $registration->user->nik && $registration->user->no_telepon ? 'badge-success' : 'badge-danger' }}">
                                            {{ $registration->user->nik && $registration->user->no_telepon ? '✅ Lengkap' : '⚠️ Belum Lengkap' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="detail-item">
                                        <label class="text-muted small fw-semibold text-uppercase">NIK</label>
                                        <p class="fw-semibold mb-0 {{ $registration->user->nik ? 'text-dark' : 'text-danger' }}">
                                            {{ $registration->user->nik ?? '❌ Belum diisi' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="detail-item">
                                        <label class="text-muted small fw-semibold text-uppercase">Status Pernikahan</label>
                                        <p class="fw-semibold mb-0">{{ $registration->user->status_pernikahan ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="detail-item">
                                        <label class="text-muted small fw-semibold text-uppercase">Jenis Kelamin</label>
                                        <p class="fw-semibold mb-0">{{ $registration->user->jenis_kelamin == 'L' ? 'Laki-laki' : ($registration->user->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}</p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="detail-item">
                                        <label class="text-muted small fw-semibold text-uppercase">Tempat / Tanggal Lahir</label>
                                        <p class="fw-semibold mb-0">
                                            {{ $registration->user->tempat_lahir ?? '-' }}
                                            {{ $registration->user->tanggal_lahir ? ', ' . \Carbon\Carbon::parse($registration->user->tanggal_lahir)->format('d-m-Y') : '' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="detail-item">
                                        <label class="text-muted small fw-semibold text-uppercase">Agama</label>
                                        <p class="fw-semibold mb-0">{{ $registration->user->agama ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="detail-item">
                                        <label class="text-muted small fw-semibold text-uppercase">Pendidikan Terakhir</label>
                                        <p class="fw-semibold mb-0">{{ $registration->user->pendidikan_terakhir ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="detail-item">
                                        <label class="text-muted small fw-semibold text-uppercase">No. Telepon / HP</label>
                                        <p class="fw-semibold mb-0 {{ $registration->user->no_telepon ? 'text-dark' : 'text-danger' }}">
                                            {{ $registration->user->no_telepon ?? '❌ Belum diisi' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="detail-item">
                                        <label class="text-muted small fw-semibold text-uppercase">Status Disabilitas</label>
                                        <p class="fw-semibold mb-0">{{ $registration->user->disabilitas ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="detail-item">
                                        <label class="text-muted small fw-semibold text-uppercase">Alamat Domisili</label>
                                        <p class="fw-semibold mb-0">{{ $registration->user->alamat_lengkap ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="detail-item">
                                        <label class="text-muted small fw-semibold text-uppercase">Kode Pos</label>
                                        <p class="fw-semibold mb-0">{{ $registration->user->kode_pos_domisili ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="detail-item">
                                        <label class="text-muted small fw-semibold text-uppercase">Email</label>
                                        <p class="fw-semibold mb-0">{{ $registration->user->email ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- TAB: USAHA -->
                        <div class="tab-pane fade" id="usaha" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="fw-bold text-success mb-0"><i class="bi bi-building me-2"></i>Data Usaha</h6>
                                        <span class="badge {{ $registration->user->nama_usaha && $registration->user->alamat_usaha ? 'badge-success' : 'badge-danger' }}">
                                            {{ $registration->user->nama_usaha && $registration->user->alamat_usaha ? '✅ Lengkap' : '⚠️ Belum Lengkap' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="detail-item">
                                        <label class="text-muted small fw-semibold text-uppercase">Nama Usaha</label>
                                        <p class="fw-semibold mb-0 {{ $registration->user->nama_usaha ? 'text-dark' : 'text-danger' }}">
                                            {{ $registration->user->nama_usaha ?? '❌ Belum diisi' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="detail-item">
                                        <label class="text-muted small fw-semibold text-uppercase">Jabatan</label>
                                        <p class="fw-semibold mb-0">{{ $registration->user->jabatan_usaha ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="detail-item">
                                        <label class="text-muted small fw-semibold text-uppercase">Merek Produk</label>
                                        <p class="fw-semibold mb-0">{{ $registration->user->merek_produk ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="detail-item">
                                        <label class="text-muted small fw-semibold text-uppercase">Alamat Usaha</label>
                                        <p class="fw-semibold mb-0 {{ $registration->user->alamat_usaha ? 'text-dark' : 'text-danger' }}">
                                            {{ $registration->user->alamat_usaha ?? '❌ Belum diisi' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="detail-item">
                                        <label class="text-muted small fw-semibold text-uppercase">Kode Pos Usaha</label>
                                        <p class="fw-semibold mb-0">{{ $registration->user->kode_pos_usaha ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="detail-item">
                                        <label class="text-muted small fw-semibold text-uppercase">No. Telepon Usaha</label>
                                        <p class="fw-semibold mb-0">{{ $registration->user->no_telepon_usaha ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="detail-item">
                                        <label class="text-muted small fw-semibold text-uppercase">Sektor Usaha</label>
                                        <p class="fw-semibold mb-0">{{ $registration->user->sektor_usaha ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="detail-item">
                                        <label class="text-muted small fw-semibold text-uppercase">Bidang Usaha</label>
                                        <p class="fw-semibold mb-0">{{ $registration->user->bidang_usaha ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="detail-item">
                                        <label class="text-muted small fw-semibold text-uppercase">Tanggal Pendirian</label>
                                        <p class="fw-semibold mb-0">{{ $registration->user->tanggal_berdiri ? \Carbon\Carbon::parse($registration->user->tanggal_berdiri)->format('d-m-Y') : '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="detail-item">
                                        <label class="text-muted small fw-semibold text-uppercase">NPWP</label>
                                        <p class="fw-semibold mb-0">{{ $registration->user->npwp_usaha ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="detail-item">
                                        <label class="text-muted small fw-semibold text-uppercase">Status NIB</label>
                                        <p class="fw-semibold mb-0">{{ $registration->user->status_nib ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="detail-item">
                                        <label class="text-muted small fw-semibold text-uppercase">Nomor NIB</label>
                                        <p class="fw-semibold mb-0">{{ $registration->user->nib ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="detail-item">
                                        <label class="text-muted small fw-semibold text-uppercase">Lama NIB</label>
                                        <p class="fw-semibold mb-0">{{ $registration->user->lama_nib ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="detail-item">
                                        <label class="text-muted small fw-semibold text-uppercase">Modal Usaha</label>
                                        <p class="fw-semibold mb-0">
                                            @if($registration->user->nilai_modal)
                                                Rp {{ number_format($registration->user->nilai_modal, 0, ',', '.') }}
                                            @else
                                                {{ $registration->user->modal_usaha ?? '-' }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="detail-item">
                                        <label class="text-muted small fw-semibold text-uppercase">Omzet per Tahun</label>
                                        <p class="fw-semibold mb-0">
                                            @if($registration->user->nilai_omzet)
                                                Rp {{ number_format($registration->user->nilai_omzet, 0, ',', '.') }}
                                            @else
                                                {{ $registration->user->omzet_usaha ?? '-' }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="detail-item">
                                        <label class="text-muted small fw-semibold text-uppercase">Jumlah Karyawan</label>
                                        <p class="fw-semibold mb-0">{{ $registration->user->jumlah_karyawan ?? '-' }} orang</p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="detail-item">
                                        <label class="text-muted small fw-semibold text-uppercase">Kapasitas Produksi</label>
                                        <p class="fw-semibold mb-0">{{ $registration->user->kapasitas_produksi ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="detail-item">
                                        <label class="text-muted small fw-semibold text-uppercase">Anggota Koperasi</label>
                                        <p class="fw-semibold mb-0">{{ $registration->user->anggota_koperasi ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- TAB: DIGITALISASI & TRANSFORMASI -->
                        <div class="tab-pane fade" id="digital" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="fw-bold text-primary mb-0"><i class="bi bi-globe2 me-2"></i>Digitalisasi Usaha</h6>
                                        <span class="badge {{ $registration->user->email_usaha || $registration->user->website_usaha || $registration->user->medsos_usaha ? 'badge-success' : 'badge-danger' }}">
                                            {{ $registration->user->email_usaha || $registration->user->website_usaha || $registration->user->medsos_usaha ? '✅ Lengkap' : '⚠️ Belum Lengkap' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="detail-item">
                                        <label class="text-muted small fw-semibold text-uppercase">Email Usaha</label>
                                        <p class="fw-semibold mb-0">{{ $registration->user->email_usaha ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="detail-item">
                                        <label class="text-muted small fw-semibold text-uppercase">Website</label>
                                        <p class="fw-semibold mb-0">{{ $registration->user->website_usaha ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="detail-item">
                                        <label class="text-muted small fw-semibold text-uppercase">Media Sosial</label>
                                        <p class="fw-semibold mb-0">{{ $registration->user->medsos_usaha ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="detail-item">
                                        <label class="text-muted small fw-semibold text-uppercase">Marketplace</label>
                                        <p class="fw-semibold mb-0">{{ $registration->user->marketplace ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="detail-item">
                                        <label class="text-muted small fw-semibold text-uppercase">Platform Pengadaan</label>
                                        <p class="fw-semibold mb-0">{{ $registration->user->pengadaan_barang ?? '-' }}</p>
                                    </div>
                                </div>

                                <div class="col-12 mt-3">
                                    <h6 class="fw-bold text-success mb-0"><i class="bi bi-wallet2 me-2"></i>Pembiayaan Usaha</h6>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="detail-item">
                                        <label class="text-muted small fw-semibold text-uppercase">Akses Kredit</label>
                                        <p class="fw-semibold mb-0">{{ $registration->user->akses_kredit ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="detail-item">
                                        <label class="text-muted small fw-semibold text-uppercase">Tabungan Bank/Koperasi</label>
                                        <p class="fw-semibold mb-0">{{ $registration->user->tabungan ?? '-' }}</p>
                                    </div>
                                </div>

                                <div class="col-12 mt-3">
                                    <h6 class="fw-bold text-warning mb-0"><i class="bi bi-arrow-repeat me-2"></i>Transformasi Usaha</h6>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="detail-item">
                                        <label class="text-muted small fw-semibold text-uppercase">Perizinan Usaha</label>
                                        <p class="fw-semibold mb-0">{{ $registration->user->perizinan_usaha ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="detail-item">
                                        <label class="text-muted small fw-semibold text-uppercase">Sertifikasi Produk</label>
                                        <p class="fw-semibold mb-0">{{ $registration->user->sertifikasi_produk ?? '-' }}</p>
                                    </div>
                                </div>

                                <div class="col-12 mt-3">
                                    <h6 class="fw-bold text-info mb-0"><i class="bi bi-box-seam me-2"></i>Rantai Pasok & Ekspor</h6>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="detail-item">
                                        <label class="text-muted small fw-semibold text-uppercase">Jangkauan Pemasaran</label>
                                        <p class="fw-semibold mb-0">{{ $registration->user->jangkauan_pemasaran ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="detail-item">
                                        <label class="text-muted small fw-semibold text-uppercase">Lokasi Pemasaran</label>
                                        <p class="fw-semibold mb-0">{{ $registration->user->lokasi_pemasaran ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="detail-item">
                                        <label class="text-muted small fw-semibold text-uppercase">Status Ekspor</label>
                                        <p class="fw-semibold mb-0">{{ $registration->user->status_ekspor ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="detail-item">
                                        <label class="text-muted small fw-semibold text-uppercase">Negara Tujuan</label>
                                        <p class="fw-semibold mb-0">{{ $registration->user->negara_ekspor ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="detail-item">
                                        <label class="text-muted small fw-semibold text-uppercase">Metode Pengiriman</label>
                                        <p class="fw-semibold mb-0">{{ $registration->user->metode_ekspor ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="detail-item">
                                        <label class="text-muted small fw-semibold text-uppercase">Volume Ekspor</label>
                                        <p class="fw-semibold mb-0">{{ $registration->user->volume_ekspor ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="detail-item">
                                        <label class="text-muted small fw-semibold text-uppercase">Nilai Ekspor</label>
                                        <p class="fw-semibold mb-0">
                                            @if($registration->user->nilai_ekspor)
                                                Rp {{ number_format($registration->user->nilai_ekspor, 0, ',', '.') }}
                                            @else
                                                -
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="detail-item">
                                        <label class="text-muted small fw-semibold text-uppercase">Pasok Bahan Baku</label>
                                        <p class="fw-semibold mb-0">{{ $registration->user->pasok_bahan_baku ?? '-' }}</p>
                                    </div>
                                </div>

                                <div class="col-12 mt-3">
                                    <h6 class="fw-bold text-secondary mb-0"><i class="bi bi-handshake me-2"></i>Kemitraan</h6>
                                </div>
                                <div class="col-12">
                                    <div class="detail-item">
                                        <label class="text-muted small fw-semibold text-uppercase">Status Kemitraan</label>
                                        <p class="fw-semibold mb-0">{{ $registration->user->kemitraan ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- TAB: INFORMASI PELATIHAN -->
                        <div class="tab-pane fade" id="tambahan" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="fw-bold text-info mb-0"><i class="bi bi-info-circle me-2"></i>Informasi Pelatihan</h6>
                                        <span class="badge {{ $registration->user->permasalahan || $registration->user->kebutuhan_diklat ? 'badge-success' : 'badge-danger' }}">
                                            {{ $registration->user->permasalahan || $registration->user->kebutuhan_diklat ? '✅ Lengkap' : '⚠️ Belum Lengkap' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="detail-item">
                                        <label class="text-muted small fw-semibold text-uppercase">Permasalahan yang Dihadapi</label>
                                        <p class="fw-semibold mb-0">{{ $registration->user->permasalahan ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="detail-item">
                                        <label class="text-muted small fw-semibold text-uppercase">Kebutuhan Diklat</label>
                                        <p class="fw-semibold mb-0">{{ $registration->user->kebutuhan_diklat ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="detail-item">
                                        <label class="text-muted small fw-semibold text-uppercase">Riwayat Pelatihan</label>
                                        <p class="fw-semibold mb-0">{{ $registration->user->riwayat_pelatihan ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="detail-item">
                                        <label class="text-muted small fw-semibold text-uppercase">Jenis Pelatihan Diikuti</label>
                                        <p class="fw-semibold mb-0">{{ $registration->user->jenis_pelatihan_diikuti ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="detail-item">
                                        <label class="text-muted small fw-semibold text-uppercase">File Produk</label>
                                        <p class="fw-semibold mb-0">
                                            @if($registration->user->file_produk)
                                                <a href="{{ asset('storage/' . $registration->user->file_produk) }}" target="_blank" class="text-primary">
                                                    <i class="bi bi-paperclip me-1"></i> Lihat lampiran
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="detail-item">
                                        <label class="text-muted small fw-semibold text-uppercase">Masukan / Saran</label>
                                        <p class="fw-semibold mb-0">{{ $registration->user->masukan_saran ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- TAB: DOKUMEN -->
                        <div class="tab-pane fade" id="dokumen" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="fw-bold text-danger mb-0"><i class="bi bi-file-earmark-text me-2"></i>Dokumen Persyaratan: KTP</h6>
                                        <span class="badge {{ $registration->user->ktp_file ? 'badge-success' : 'badge-danger' }}">
                                            {{ $registration->user->ktp_file ? '✅ Sudah Upload' : '⚠️ Belum Upload' }}
                                        </span>
                                    </div>
                                    @if($registration->user->ktp_file)
                                        <div class="alert alert-success d-flex align-items-center gap-2">
                                            <i class="bi bi-check-circle fs-5"></i>
                                            <span><strong>✓ KTP Sudah Diupload</strong></span>
                                        </div>
                                        <div class="text-center bg-light p-4 border rounded-3">
                                            @php $ext = pathinfo($registration->user->ktp_file, PATHINFO_EXTENSION); @endphp
                                            @if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif']))
                                                <img src="{{ asset('storage/' . $registration->user->ktp_file) }}" 
                                                     alt="KTP Peserta" 
                                                     class="img-fluid rounded-3 border shadow-sm" 
                                                     style="max-height: 350px; object-fit: contain;">
                                            @else
                                                <i class="bi bi-file-earmark-pdf text-danger" style="font-size: 4rem;"></i>
                                                <p class="mt-2">{{ basename($registration->user->ktp_file) }}</p>
                                            @endif
                                            <div class="mt-3">
                                                <a href="{{ asset('storage/' . $registration->user->ktp_file) }}" target="_blank" class="btn btn-primary">
                                                    <i class="bi bi-eye me-1"></i> Lihat Dokumen
                                                </a>
                                            </div>
                                        </div>
                                    @else
                                        <div class="alert alert-danger d-flex align-items-center gap-2">
                                            <i class="bi bi-x-circle fs-5"></i>
                                            <span><strong>✗ KTP Belum Diupload</strong></span>
                                        </div>
                                        <p class="text-muted">Peserta belum mengunggah Kartu Tanda Penduduk yang menjadi persyaratan wajib verifikasi.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Verifikasi Admin -->
                <div class="panel-footer border-top bg-light p-4">
                    <h6 class="fw-bold mb-3"><i class="bi bi-shield-check me-2 text-primary"></i>Verifikasi Pendaftaran</h6>
                    
                    <!-- Ringkasan Kelengkapan -->
                    <div class="row g-2 mb-3">
                        <div class="col-12 col-md-3">
                            <div class="p-2 text-center rounded-3 {{ $registration->user->nik && $registration->user->no_telepon ? 'bg-success bg-opacity-10' : 'bg-danger bg-opacity-10' }}">
                                <span class="d-block {{ $registration->user->nik && $registration->user->no_telepon ? 'text-success' : 'text-danger' }}">
                                    <i class="bi {{ $registration->user->nik && $registration->user->no_telepon ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }}"></i>
                                </span>
                                <small class="d-block">Data Pribadi</small>
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <div class="p-2 text-center rounded-3 {{ $registration->user->nama_usaha && $registration->user->alamat_usaha ? 'bg-success bg-opacity-10' : 'bg-danger bg-opacity-10' }}">
                                <span class="d-block {{ $registration->user->nama_usaha && $registration->user->alamat_usaha ? 'text-success' : 'text-danger' }}">
                                    <i class="bi {{ $registration->user->nama_usaha && $registration->user->alamat_usaha ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }}"></i>
                                </span>
                                <small class="d-block">Data Usaha</small>
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <div class="p-2 text-center rounded-3 {{ $registration->user->email_usaha || $registration->user->website_usaha || $registration->user->medsos_usaha ? 'bg-success bg-opacity-10' : 'bg-danger bg-opacity-10' }}">
                                <span class="d-block {{ $registration->user->email_usaha || $registration->user->website_usaha || $registration->user->medsos_usaha ? 'text-success' : 'text-danger' }}">
                                    <i class="bi {{ $registration->user->email_usaha || $registration->user->website_usaha || $registration->user->medsos_usaha ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }}"></i>
                                </span>
                                <small class="d-block">Digitalisasi</small>
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <div class="p-2 text-center rounded-3 {{ $registration->user->ktp_file ? 'bg-success bg-opacity-10' : 'bg-danger bg-opacity-10' }}">
                                <span class="d-block {{ $registration->user->ktp_file ? 'text-success' : 'text-danger' }}">
                                    <i class="bi {{ $registration->user->ktp_file ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }}"></i>
                                </span>
                                <small class="d-block">Dokumen KTP</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-muted">Status Pendaftaran:</span>
                            @if($registration->status == 'pending')
                                <span class="badge badge-warning fs-6 py-2 px-3">
                                    <i class="bi bi-clock me-1"></i> Menunggu Verifikasi
                                </span>
                            @else
                                <span class="badge 
                                    @if($registration->status == 'disetujui') badge-success
                                    @elseif($registration->status == 'ditolak') badge-danger
                                    @else badge-secondary
                                    @endif
                                    fs-6 py-2 px-3
                                ">
                                    <i class="bi bi-circle-fill me-1" style="font-size: 6px;"></i>
                                    {{ ucfirst($registration->status) }}
                                </span>
                            @endif
                        </div>
                        
                        @if($registration->status == 'pending')
                        <div class="d-flex gap-2 flex-wrap">
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                <i class="bi bi-x-circle me-1"></i> Tolak
                            </button>
                            
                            @if($registration->user->is_profil_lengkap)
                                <form action="{{ route('admin.pendaftaran.approve', $registration->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-check-circle me-1"></i> Setujui
                                    </button>
                                </form>
                            @else
                                <button type="button" class="btn btn-success" disabled title="Profil belum lengkap">
                                    <i class="bi bi-check-circle me-1"></i> Setujui
                                </button>
                            @endif
                        </div>
                        @endif
                    </div>

                    @if($registration->status == 'pending' && !$registration->user->is_profil_lengkap)
                    <div class="alert alert-danger mt-3 mb-0">
                        <i class="bi bi-info-circle me-1"></i> 
                        <strong>Peserta belum dapat disetujui karena profil atau persyaratan belum lengkap.</strong>
                        <br>
                        <small class="text-muted">Pastikan semua data terisi dan dokumen KTP sudah diupload.</small>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
@if($registration->status == 'pending')
<div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.pendaftaran.reject', $registration->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-exclamation-triangle me-2"></i> Tolak Pendaftaran
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body py-4">
                    <p>Anda akan menolak pendaftaran <strong>{{ $registration->user->nama ?? $registration->user->name }}</strong> untuk pelatihan <strong>{{ $registration->training->judul }}</strong>.</p>
                    
                    <div class="mb-3">
                        <label for="alasan_penolakan" class="form-label fw-bold">
                            Alasan Penolakan <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control" id="alasan_penolakan" name="alasan_penolakan" 
                                  rows="4" required 
                                  placeholder="Contoh: Profil peserta belum lengkap. Mohon lengkapi data NIB dan upload KTP terlebih dahulu."></textarea>
                        <small class="text-muted">Alasan akan dikirimkan ke peserta melalui email.</small>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-send me-1"></i> Kirim Penolakan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif

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
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        color: #2563eb;
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
       INFO ITEMS
    ============================================================ */
    .info-item {
        transition: all 0.2s ease;
    }
    .info-item:hover {
        background-color: #e9ecef !important;
    }
    
    .icon-circle-sm {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .icon-circle-sm i {
        font-size: 16px;
    }
    
    .bg-success { background-color: #28c76f; }
    .bg-info { background-color: #0dcaf0; }
    .text-white { color: #fff; }

    /* ============================================================
       TABS
    ============================================================ */
    .nav-tabs-custom {
        border-bottom: 2px solid #e9ecef;
    }
    .nav-tabs-custom .nav-link {
        border: none;
        color: #6c757d;
        padding: 0.75rem 1.25rem;
        font-weight: 500;
        border-radius: 0;
        transition: all 0.2s ease;
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
    .badge-secondary {
        background: #e2e8f0 !important;
        color: #4a5568 !important;
    }

    /* ============================================================
       AVATAR
    ============================================================ */
    .avatar-circle {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 2.5rem;
        color: #fff;
        flex-shrink: 0;
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
    
    .btn-success {
        background: #28c76f;
        border-color: #28c76f;
        color: #fff;
    }
    .btn-success:hover {
        background: #1fb45e;
        border-color: #1fb45e;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(40, 199, 111, 0.3);
    }
    
    .btn-danger {
        background: #ea5455;
        border-color: #ea5455;
        color: #fff;
    }
    .btn-danger:hover {
        background: #e53e3e;
        border-color: #e53e3e;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(234, 84, 85, 0.3);
    }
    
    .btn-outline-secondary {
        border-color: #e2e8f0;
        color: #4a5568;
    }
    .btn-outline-secondary:hover {
        background: #e2e8f0;
        border-color: #d5dce6;
    }
    
    .btn-sm {
        padding: 0.3rem 0.8rem;
        font-size: 0.8rem;
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
    .alert-warning {
        background: #fffbeb;
        color: #92400e;
    }
    .alert-danger {
        background: #fef2f2;
        color: #991b1b;
    }
    .alert-dismissible .btn-close {
        padding: 1rem;
    }

    /* ============================================================
       MODAL
    ============================================================ */
    .modal-content {
        border-radius: 0.75rem;
        border: none;
    }
    .modal-header {
        border-bottom: 1px solid #f0f0f0;
    }
    .modal-footer {
        border-top: 1px solid #f0f0f0;
    }

    /* ============================================================
       BG OPACITY
    ============================================================ */
    .bg-success.bg-opacity-10 { background-color: rgba(40, 199, 111, 0.1) !important; }
    .bg-danger.bg-opacity-10 { background-color: rgba(234, 84, 85, 0.1) !important; }
    .bg-warning.bg-opacity-10 { background-color: rgba(255, 159, 67, 0.1) !important; }

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
        .nav-tabs-custom .nav-link {
            padding: 0.5rem 0.75rem;
            font-size: 0.8rem;
        }
        .d-flex.justify-content-between.align-items-center {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 0.5rem;
        }
        .text-center .d-flex.gap-2 {
            flex-wrap: wrap;
            justify-content: center;
        }
        .avatar-circle {
            width: 80px;
            height: 80px;
            font-size: 2rem;
        }
        .detail-item p {
            font-size: 0.85rem;
        }
        .row.g-2 .col-12.col-md-3 {
            flex: 0 0 50%;
            max-width: 50%;
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ============================================================
    // AUTO CLOSE ALERTS
    // ============================================================
    setTimeout(function() {
        document.querySelectorAll('.alert').forEach(function(alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);

    // ============================================================
    // AUTO FOCUS ON REJECT MODAL TEXTAREA
    // ============================================================
    document.getElementById('rejectModal')?.addEventListener('shown.bs.modal', function() {
        const textarea = this.querySelector('textarea[name="alasan_penolakan"]');
        if (textarea) {
            setTimeout(function() {
                textarea.focus();
            }, 300);
        }
    });
});
</script>
@endpush
@endsection