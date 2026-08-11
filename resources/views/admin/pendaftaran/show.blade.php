@extends('layouts.admin')

@section('title', 'Detail Profil Peserta')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-person-lines-fill"></i></span>
        <div>
            <p class="eyebrow">Manajemen Pendaftaran</p>
            <h1 class="h3 mb-0">Detail Profil Peserta</h1>
        </div>
    </div>
    <div class="heading-actions">
        <a href="{{ route('admin.pendaftaran.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Pendaftaran
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
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i>
                {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <div class="panel mb-4">
                <div class="panel-header d-flex justify-content-between align-items-center">
                    <h5 class="section-title mb-0"><i class="bi bi-info-circle"></i> Info Pendaftaran</h5>
                    {!! $registration->status_badge !!}
                </div>
                <div class="p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <p class="mb-1 text-muted small">Pelatihan</p>
                            <p class="fw-semibold">{{ $registration->training->judul }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1 text-muted small">Tanggal Daftar</p>
                            <p class="fw-semibold">{{ $registration->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel mb-4">
                <div class="panel-header">
                    <h5 class="section-title"><i class="bi bi-person"></i> Detail Profil Peserta</h5>
                </div>
                
                <div class="p-4">
                    <!-- Status Kelengkapan Profil -->
                    <h6 class="fw-bold border-bottom pb-2 mb-3">Status Kelengkapan Profil</h6>
                    <div class="alert {{ $registration->user->is_profil_lengkap ? 'alert-success' : 'alert-danger' }} mb-4">
                        <h6 class="alert-heading fw-bold mb-1">
                            {!! $registration->user->is_profil_lengkap ? '<i class="bi bi-check-circle-fill"></i> PROFIL LENGKAP' : '<i class="bi bi-exclamation-triangle-fill"></i> PROFIL BELUM LENGKAP' !!}
                        </h6>
                        @if(!$registration->user->is_profil_lengkap)
                        <p class="mb-0 mt-2 text-sm">Data atau persyaratan berikut belum lengkap:</p>
                        <ul class="mb-0 mt-1">
                            @foreach($registration->user->profil_incomplete_fields as $field)
                                <li><i class="bi bi-x text-danger fw-bold"></i> {{ $field }}</li>
                            @endforeach
                        </ul>
                        @else
                        <p class="mb-0 mt-2 text-sm">
                            <i class="bi bi-check text-success fw-bold"></i> Data Pribadi Lengkap<br>
                            <i class="bi bi-check text-success fw-bold"></i> Data Usaha Lengkap<br>
                            <i class="bi bi-check text-success fw-bold"></i> KTP Sudah Diupload
                        </p>
                        @endif
                    </div>

                    <!-- Tabs Navs -->
                    <ul class="nav nav-tabs" id="profileTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#pribadi" type="button" role="tab">Data Pribadi</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#usaha" type="button" role="tab">Data Usaha</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#digital" type="button" role="tab">Digitalisasi & Transformasi</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tambahan" type="button" role="tab">Informasi Pelatihan</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#dokumen" type="button" role="tab">Dokumen Persyaratan</button>
                        </li>
                    </ul>

                    <!-- Tabs Content -->
                    <div class="tab-content border border-top-0 p-4 bg-white rounded-bottom" id="profileTabsContent">
                        
                        <!-- TAB: PRIBADI -->
                        <div class="tab-pane fade show active" id="pribadi" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-12 text-center mb-4">
                                    @if($registration->user->foto)
                                        <img src="{{ asset('storage/' . $registration->user->foto) }}" alt="Foto Peserta" class="img-fluid rounded-circle shadow-sm" style="width: 120px; height: 120px; object-fit: cover;">
                                    @else
                                        <div class="avatar-text bg-secondary text-white d-flex align-items-center justify-content-center rounded-circle mx-auto" style="width: 120px; height: 120px; font-size: 3rem;">
                                            {{ strtoupper(substr($registration->user->nama ?? $registration->user->name ?? 'U', 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">Nama Lengkap</label>
                                    <p class="fw-medium">{{ $registration->user->nama ?? $registration->user->name ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">NIK</label>
                                    <p class="fw-medium">{{ $registration->user->nik ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">Status Pernikahan</label>
                                    <p class="fw-medium">{{ $registration->user->status_pernikahan ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">Jenis Kelamin</label>
                                    <p class="fw-medium">{{ $registration->user->jenis_kelamin == 'L' ? 'Laki-laki' : ($registration->user->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">Tempat Lahir</label>
                                    <p class="fw-medium">{{ $registration->user->tempat_lahir ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">Tanggal Lahir</label>
                                    <p class="fw-medium">{{ $registration->user->tanggal_lahir ? \Carbon\Carbon::parse($registration->user->tanggal_lahir)->format('d-m-Y') : '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">Agama</label>
                                    <p class="fw-medium">{{ $registration->user->agama ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">Pendidikan Terakhir</label>
                                    <p class="fw-medium">{{ $registration->user->pendidikan_terakhir ?? '-' }}</p>
                                </div>
                                <div class="col-12">
                                    <label class="text-muted small">Alamat Domisili</label>
                                    <p class="fw-medium">{{ $registration->user->alamat_lengkap ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">Kode Pos</label>
                                    <p class="fw-medium">{{ $registration->user->kode_pos_domisili ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">Nomor Telp/HP</label>
                                    <p class="fw-medium">{{ $registration->user->no_telepon ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">Email</label>
                                    <p class="fw-medium">{{ $registration->user->email ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">Status Penyandang Disabilitas</label>
                                    <p class="fw-medium">{{ $registration->user->disabilitas ?? '-' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- TAB: USAHA -->
                        <div class="tab-pane fade" id="usaha" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="text-muted small">Nama Usaha</label>
                                    <p class="fw-medium">{{ $registration->user->nama_usaha ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">Jabatan Usaha</label>
                                    <p class="fw-medium">{{ $registration->user->jabatan_usaha ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">Nama/Merek Produk Usaha</label>
                                    <p class="fw-medium">{{ $registration->user->merek_produk ?? '-' }}</p>
                                </div>
                                <div class="col-12">
                                    <label class="text-muted small">Alamat Usaha</label>
                                    <p class="fw-medium">{{ $registration->user->alamat_usaha ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">Kode Pos Usaha</label>
                                    <p class="fw-medium">{{ $registration->user->kode_pos_usaha ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">Nomor Telepon/HP Usaha</label>
                                    <p class="fw-medium">{{ $registration->user->no_telepon_usaha ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">Sektor Usaha</label>
                                    <p class="fw-medium">{{ $registration->user->sektor_usaha ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">Bidang Usaha</label>
                                    <p class="fw-medium">{{ $registration->user->bidang_usaha ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">Tanggal Pendirian Usaha</label>
                                    <p class="fw-medium">{{ $registration->user->tanggal_berdiri ? \Carbon\Carbon::parse($registration->user->tanggal_berdiri)->format('d-m-Y') : '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">NPWP Usaha</label>
                                    <p class="fw-medium">{{ $registration->user->npwp_usaha ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">Status NIB</label>
                                    <p class="fw-medium">{{ $registration->user->status_nib ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">Nomor NIB</label>
                                    <p class="fw-medium">{{ $registration->user->nib ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">Lama memiliki NIB</label>
                                    <p class="fw-medium">{{ $registration->user->lama_nib ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">Modal Usaha UMK per tahun</label>
                                    <p class="fw-medium">{{ $registration->user->modal_usaha ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">Nilai Modal Usaha</label>
                                    <p class="fw-medium">Rp {{ number_format($registration->user->nilai_modal, 0, ',', '.') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">Omzet UMK per tahun</label>
                                    <p class="fw-medium">{{ $registration->user->omzet_usaha ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">Nilai Omzet per Tahun</label>
                                    <p class="fw-medium">Rp {{ number_format($registration->user->nilai_omzet, 0, ',', '.') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">Jumlah Karyawan</label>
                                    <p class="fw-medium">{{ $registration->user->jumlah_karyawan ?? '-' }} orang</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">Kapasitas Produksi per Tahun</label>
                                    <p class="fw-medium">{{ $registration->user->kapasitas_produksi ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">Keanggotaan Koperasi</label>
                                    <p class="fw-medium">{{ $registration->user->anggota_koperasi ?? '-' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- TAB: DIGITALISASI & TRANSFORMASI -->
                        <div class="tab-pane fade" id="digital" role="tabpanel">
                            <h6 class="fw-bold border-bottom pb-2 mb-3 mt-2">Digitalisasi Usaha</h6>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="text-muted small">Email Usaha</label>
                                    <p class="fw-medium">{{ $registration->user->email_usaha ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">Website Usaha</label>
                                    <p class="fw-medium">{{ $registration->user->website_usaha ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">Media Sosial</label>
                                    <p class="fw-medium">{{ $registration->user->medsos_usaha ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">Marketplace</label>
                                    <p class="fw-medium">{{ $registration->user->marketplace ?? '-' }}</p>
                                </div>
                                <div class="col-12">
                                    <label class="text-muted small">Platform Pengadaan Barang/Jasa</label>
                                    <p class="fw-medium">{{ $registration->user->pengadaan_barang ?? '-' }}</p>
                                </div>
                            </div>
                            
                            <h6 class="fw-bold border-bottom pb-2 mb-3">Pembiayaan Usaha</h6>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="text-muted small">Akses kredit perbankan/non-perbankan</label>
                                    <p class="fw-medium">{{ $registration->user->akses_kredit ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">Kepemilikan tabungan Bank/Koperasi</label>
                                    <p class="fw-medium">{{ $registration->user->tabungan ?? '-' }}</p>
                                </div>
                            </div>

                            <h6 class="fw-bold border-bottom pb-2 mb-3">Transformasi Usaha</h6>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="text-muted small">Perizinan Usaha</label>
                                    <p class="fw-medium">{{ $registration->user->perizinan_usaha ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">Sertifikasi Produk/Usaha</label>
                                    <p class="fw-medium">{{ $registration->user->sertifikasi_produk ?? '-' }}</p>
                                </div>
                            </div>

                            <h6 class="fw-bold border-bottom pb-2 mb-3">Rantai Pasok dan Ekspor</h6>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="text-muted small">Jangkauan pemasaran</label>
                                    <p class="fw-medium">{{ $registration->user->jangkauan_pemasaran ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">Lokasi pemasaran</label>
                                    <p class="fw-medium">{{ $registration->user->lokasi_pemasaran ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">Status ekspor</label>
                                    <p class="fw-medium">{{ $registration->user->status_ekspor ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">Negara tujuan</label>
                                    <p class="fw-medium">{{ $registration->user->negara_ekspor ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">Metode pengiriman</label>
                                    <p class="fw-medium">{{ $registration->user->metode_ekspor ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">Volume ekspor</label>
                                    <p class="fw-medium">{{ $registration->user->volume_ekspor ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">Nilai ekspor</label>
                                    <p class="fw-medium">Rp {{ number_format($registration->user->nilai_ekspor, 0, ',', '.') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">Status pemasokan produk ke usaha lain</label>
                                    <p class="fw-medium">{{ $registration->user->pasok_bahan_baku ?? '-' }}</p>
                                </div>
                            </div>

                            <h6 class="fw-bold border-bottom pb-2 mb-3">Kemitraan</h6>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="text-muted small">Status kemitraan dengan lembaga lain</label>
                                    <p class="fw-medium">{{ $registration->user->kemitraan ?? '-' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- TAB: INFORMASI PELATIHAN -->
                        <div class="tab-pane fade" id="tambahan" role="tabpanel">
                            <div class="row g-4">
                                <div class="col-12">
                                    <h6 class="fw-bold border-bottom pb-2">Informasi Pelatihan dan Kebutuhan Peserta</h6>
                                    <div class="row g-3 mt-1">
                                        <div class="col-12">
                                            <label class="text-muted small">Permasalahan yang dihadapi</label>
                                            <p class="fw-medium">{{ $registration->user->permasalahan ?? '-' }}</p>
                                        </div>
                                        <div class="col-12">
                                            <label class="text-muted small">Kebutuhan diklat</label>
                                            <p class="fw-medium">{{ $registration->user->kebutuhan_diklat ?? '-' }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="text-muted small">Riwayat pernah mengikuti pelatihan</label>
                                            <p class="fw-medium">{{ $registration->user->riwayat_pelatihan ?? '-' }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="text-muted small">Jenis pelatihan yang pernah diikuti</label>
                                            <p class="fw-medium">{{ $registration->user->jenis_pelatihan_diikuti ?? '-' }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="text-muted small">File attachment produk</label>
                                            <p class="fw-medium">
                                                @if($registration->user->file_produk)
                                                    <a href="{{ asset('storage/' . $registration->user->file_produk) }}" target="_blank" class="text-primary"><i class="bi bi-paperclip"></i> Lihat lampiran</a>
                                                @else
                                                    -
                                                @endif
                                            </p>
                                        </div>
                                        <div class="col-12">
                                            <label class="text-muted small">Masukan/Saran</label>
                                            <p class="fw-medium">{{ $registration->user->masukan_saran ?? '-' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- TAB: DOKUMEN & KTP -->
                        <div class="tab-pane fade" id="dokumen" role="tabpanel">
                            <div class="row g-4">
                                <div class="col-12 text-center">
                                    <h6 class="fw-bold border-bottom pb-2 mb-3">Dokumen Persyaratan: KTP</h6>
                                    @if($registration->user->ktp_file)
                                        <div class="alert alert-success d-inline-block py-2 mb-3">
                                            <i class="bi bi-check-circle me-1"></i> <strong>✓ KTP Sudah Diupload</strong>
                                        </div>
                                        <div class="text-center bg-light p-4 border rounded">
                                            @php $ext = pathinfo($registration->user->ktp_file, PATHINFO_EXTENSION); @endphp
                                            @if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif']))
                                                <img src="{{ asset('storage/' . $registration->user->ktp_file) }}" alt="KTP Peserta" class="img-fluid rounded border shadow-sm" style="max-height: 350px; object-fit: contain;">
                                                <div class="mt-3">
                                                    <a href="{{ asset('storage/' . $registration->user->ktp_file) }}" target="_blank" class="btn btn-primary">
                                                        <i class="bi bi-arrows-fullscreen"></i> Lihat KTP
                                                    </a>
                                                </div>
                                            @else
                                                <i class="bi bi-file-earmark-pdf text-danger" style="font-size: 4rem;"></i>
                                                <div class="mt-3">
                                                    <a href="{{ asset('storage/' . $registration->user->ktp_file) }}" target="_blank" class="btn btn-primary">
                                                        <i class="bi bi-file-earmark-pdf"></i> Lihat Dokumen KTP
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <div class="alert alert-danger d-inline-block py-2">
                                            <i class="bi bi-x-circle me-1"></i> <strong>✗ KTP Belum Diupload</strong>
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
                    <h6 class="fw-bold border-bottom pb-2 mb-3"><i class="bi bi-shield-check me-2"></i>Verifikasi Pendaftaran</h6>
                    
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-muted">Status Pendaftaran:</span>
                            @if($registration->status == 'pending')
                                <span class="badge bg-warning fs-6">Menunggu Verifikasi</span>
                            @else
                                {!! $registration->status_badge !!}
                            @endif
                        </div>
                        
                        @if($registration->status == 'pending')
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                <i class="bi bi-x-circle"></i> Tolak Pendaftaran
                            </button>
                            
                            @if($registration->user->is_profil_lengkap)
                                <form action="{{ route('admin.pendaftaran.approve', $registration->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-check-circle"></i> Setujui Peserta
                                    </button>
                                </form>
                            @else
                                <button type="button" class="btn btn-success" disabled title="Profil belum lengkap" onclick="alert('Peserta belum dapat disetujui karena profil atau persyaratan belum lengkap.')">
                                    <i class="bi bi-check-circle"></i> Setujui Peserta
                                </button>
                            @endif
                        </div>
                        @endif
                    </div>

                    @if($registration->status == 'pending' && !$registration->user->is_profil_lengkap)
                    <div class="alert alert-danger mt-3 mb-0">
                        <i class="bi bi-info-circle me-1"></i> <strong>Peserta belum dapat disetujui karena profil atau persyaratan belum lengkap.</strong>
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
                <div class="modal-header border-bottom-0 bg-danger text-white">
                    <h5 class="modal-title"><i class="bi bi-exclamation-triangle"></i> Tolak Pendaftaran</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-4">
                    <p>Anda akan menolak pendaftaran <strong>{{ $registration->user->nama ?? $registration->user->name }}</strong> untuk pelatihan <strong>{{ $registration->training->judul }}</strong>.</p>
                    
                    <div class="mb-3">
                        <label for="alasan_penolakan" class="form-label fw-bold">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="alasan_penolakan" name="alasan_penolakan" rows="4" required placeholder="Contoh: Profil peserta belum lengkap. Mohon lengkapi data NIB dan upload KTP terlebih dahulu."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top-0 bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Kirim Penolakan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif

@endsection
