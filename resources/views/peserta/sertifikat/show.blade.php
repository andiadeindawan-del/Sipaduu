@extends('layouts.peserta')

@section('title', 'Detail Sertifikat')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-award"></i></span>
        <div>
            <p class="eyebrow">Sertifikat</p>
            <h1 class="h3 mb-0">Detail Sertifikat</h1>
            <p class="text-muted mb-0">Lihat informasi lengkap sertifikat Anda.</p>
        </div>
    </div>
    <div class="heading-actions">
        <a href="{{ route('peserta.sertifikat.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="row">
        <div class="col-12 col-lg-8 mx-auto">
            <!-- Alert Messages -->
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <!-- Certificate Detail Card -->
            <div class="certificate-detail-card">
                <!-- Header -->
                <div class="certificate-detail-header">
                    <div class="certificate-detail-icon">
                        <i class="bi bi-award-fill"></i>
                    </div>
                    <div class="certificate-detail-title">
                        <h4 class="mb-1">{{ $sertifikat->judul ?? $sertifikat->nama_sertifikat }}</h4>
                        <p class="text-muted small mb-0">
                            <i class="bi bi-hash"></i> {{ $sertifikat->nomor_sertifikat }}
                        </p>
                    </div>
                    <div class="certificate-detail-status">
                        @if($sertifikat->status == 'aktif')
                            <span class="badge badge-aktif">
                                <i class="bi bi-check-circle-fill me-1"></i> Aktif
                            </span>
                        @elseif($sertifikat->status == 'expired')
                            <span class="badge badge-expired">
                                <i class="bi bi-clock-fill me-1"></i> Kadaluarsa
                            </span>
                        @elseif($sertifikat->status == 'revoked')
                            <span class="badge badge-revoked">
                                <i class="bi bi-x-circle-fill me-1"></i> Dicabut
                            </span>
                        @else
                            <span class="badge badge-secondary">{{ ucfirst($sertifikat->status) }}</span>
                        @endif
                    </div>
                </div>

                <!-- Body -->
                <div class="certificate-detail-body">
                    <!-- Preview -->
                    <div class="certificate-preview">
                        <div class="certificate-preview-content">
                            <div class="certificate-preview-icon">
                                <i class="bi bi-award-fill"></i>
                            </div>
                            <div class="certificate-preview-info">
                                <h5>{{ $sertifikat->judul ?? $sertifikat->nama_sertifikat }}</h5>
                                <p class="text-muted small">
                                    <i class="bi bi-person me-1"></i>
                                    {{ auth()->user()->nama ?? auth()->user()->name }}
                                </p>
                                <p class="text-muted small">
                                    <i class="bi bi-hash me-1"></i>
                                    {{ $sertifikat->nomor_sertifikat }}
                                </p>
                                <p class="text-muted small">
                                    <i class="bi bi-calendar-check me-1"></i>
                                    Diterbitkan: {{ $sertifikat->tanggal_terbit ? $sertifikat->tanggal_terbit->format('d F Y') : '-' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Info -->
                    <div class="certificate-detail-info">
                        <h6 class="fw-bold mb-3">
                            <i class="bi bi-info-circle me-2 text-primary"></i>Informasi Sertifikat
                        </h6>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="info-label">Penerbit</label>
                                    <p class="info-value">{{ $sertifikat->penerbit ?? 'Dinas Koperindag Sulawesi Barat' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="info-label">Status</label>
                                    <p class="info-value">
                                        @if($sertifikat->status == 'aktif')
                                            <span class="badge badge-aktif">
                                                <i class="bi bi-check-circle-fill me-1"></i> Aktif
                                            </span>
                                        @elseif($sertifikat->status == 'expired')
                                            <span class="badge badge-expired">
                                                <i class="bi bi-clock-fill me-1"></i> Kadaluarsa
                                            </span>
                                        @elseif($sertifikat->status == 'revoked')
                                            <span class="badge badge-revoked">
                                                <i class="bi bi-x-circle-fill me-1"></i> Dicabut
                                            </span>
                                        @else
                                            <span class="badge badge-secondary">{{ ucfirst($sertifikat->status) }}</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="info-label">Tanggal Terbit</label>
                                    <p class="info-value">{{ $sertifikat->tanggal_terbit ? $sertifikat->tanggal_terbit->format('d F Y') : '-' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="info-label">Berlaku Sampai</label>
                                    <p class="info-value">{{ $sertifikat->tanggal_berlaku_sampai ? $sertifikat->tanggal_berlaku_sampai->format('d F Y') : 'Seumur Hidup' }}</p>
                                </div>
                            </div>
                            @if($sertifikat->training)
                            <div class="col-md-12">
                                <div class="info-item">
                                    <label class="info-label">Terkait Pelatihan</label>
                                    <p class="info-value">{{ $sertifikat->training->judul }}</p>
                                </div>
                            </div>
                            @endif
                            @if($sertifikat->deskripsi)
                            <div class="col-md-12">
                                <div class="info-item">
                                    <label class="info-label">Deskripsi</label>
                                    <p class="info-value">{{ $sertifikat->deskripsi }}</p>
                                </div>
                            </div>
                            @endif
                            <div class="col-md-12">
                                <div class="info-item">
                                    <label class="info-label">Diberikan Kepada</label>
                                    <p class="info-value">{{ auth()->user()->nama ?? auth()->user()->name }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="certificate-detail-actions">
                        <a href="{{ route('peserta.sertifikat.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                        @if($sertifikat->file_path && $sertifikat->status == 'aktif')
                            <a href="{{ route('peserta.sertifikat.download', $sertifikat->id) }}" 
                               class="btn btn-success" target="_blank">
                                <i class="bi bi-download me-1"></i> Unduh Sertifikat
                            </a>
                        @endif
                        @if($sertifikat->status == 'aktif' && !$sertifikat->file_path)
                            <span class="btn btn-secondary disabled">
                                <i class="bi bi-clock me-1"></i> Sertifikat Dalam Proses
                            </span>
                        @endif
                        @if($sertifikat->status == 'aktif')
                            <button class="btn btn-outline-primary" onclick="window.print()">
                                <i class="bi bi-printer me-1"></i> Cetak
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Share & Verify -->
            <div class="certificate-extra">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="certificate-verify">
                            <i class="bi bi-shield-check"></i>
                            <div>
                                <h6 class="mb-0 fw-semibold">Verifikasi Keaslian</h6>
                                <p class="text-muted small mb-0">
                                    Sertifikat ini dapat diverifikasi melalui sistem resmi kami.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="certificate-share">
                            <i class="bi bi-share"></i>
                            <div>
                                <h6 class="mb-0 fw-semibold">Bagikan Sertifikat</h6>
                                <p class="text-muted small mb-0">
                                    Bagikan pencapaian Anda ke media sosial.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* ============================================================
       CERTIFICATE DETAIL CARD
    ============================================================ */
    .certificate-detail-card {
        background: #fff;
        border-radius: 1rem;
        box-shadow: 0 1px 4px rgba(0,0,0,.06);
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.04);
    }

    .certificate-detail-header {
        padding: 1.25rem 1.5rem;
        background: linear-gradient(135deg, #e8f4f8, #b8dce8);
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
        border-bottom: 1px solid rgba(0,0,0,0.04);
    }

    .certificate-detail-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        background: #fff;
        color: #4e9af1;
        box-shadow: 0 4px 15px rgba(78, 154, 241, 0.15);
        flex-shrink: 0;
    }

    .certificate-detail-title {
        flex: 1;
        min-width: 150px;
    }
    .certificate-detail-title h4 {
        font-weight: 700;
        color: #1a2236;
    }

    .certificate-detail-status {
        margin-left: auto;
    }
    .certificate-detail-status .badge {
        font-weight: 500;
        padding: 0.4rem 0.8rem;
        font-size: 0.75rem;
        border-radius: 8px;
    }
    .badge-aktif {
        background: #d4edda;
        color: #155724;
    }
    .badge-expired {
        background: #fff3cd;
        color: #856404;
    }
    .badge-revoked {
        background: #f8d7da;
        color: #721c24;
    }

    .certificate-detail-body {
        padding: 1.5rem;
    }

    /* ============================================================
       PREVIEW
    ============================================================ */
    .certificate-preview {
        background: linear-gradient(135deg, #f8fafc, #e8f4f8);
        border-radius: 0.75rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border: 2px dashed #d4e8f0;
        position: relative;
    }
    .certificate-preview::before {
        content: 'Preview';
        position: absolute;
        top: -10px;
        left: 20px;
        background: #fff;
        padding: 0 10px;
        font-size: 0.7rem;
        font-weight: 600;
        color: #8a93a3;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .certificate-preview-content {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        flex-wrap: wrap;
    }
    .certificate-preview-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        background: linear-gradient(135deg, #4e9af1, #3a7bc8);
        color: #fff;
        flex-shrink: 0;
        box-shadow: 0 8px 25px rgba(78, 154, 241, 0.2);
    }
    .certificate-preview-info h5 {
        font-weight: 700;
        color: #1a2236;
        margin-bottom: 0.25rem;
    }
    .certificate-preview-info p {
        margin-bottom: 0.1rem;
    }

    /* ============================================================
       INFO
    ============================================================ */
    .certificate-detail-info {
        margin-bottom: 1.5rem;
    }
    .certificate-detail-info h6 {
        color: #1a2236;
    }

    .info-item {
        background: #f8fafc;
        border-radius: 0.5rem;
        padding: 0.6rem 0.8rem;
        border: 1px solid #f0f0f0;
    }
    .info-label {
        font-size: 0.7rem;
        color: #8a93a3;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        display: block;
        margin-bottom: 0.1rem;
    }
    .info-value {
        font-weight: 500;
        color: #1a2236;
        margin-bottom: 0;
        font-size: 0.9rem;
    }

    /* ============================================================
       ACTIONS
    ============================================================ */
    .certificate-detail-actions {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
        padding-top: 1.25rem;
        border-top: 1px solid #f0f0f0;
    }
    .certificate-detail-actions .btn {
        border-radius: 8px;
        padding: 0.5rem 1.25rem;
        font-weight: 500;
    }

    /* ============================================================
       EXTRA
    ============================================================ */
    .certificate-extra {
        margin-top: 1.5rem;
    }
    .certificate-verify,
    .certificate-share {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        background: #fff;
        border-radius: 0.75rem;
        border: 1px solid rgba(0,0,0,0.04);
        box-shadow: 0 1px 4px rgba(0,0,0,.06);
        transition: all 0.3s ease;
        height: 100%;
    }
    .certificate-verify:hover,
    .certificate-share:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }
    .certificate-verify i,
    .certificate-share i {
        font-size: 1.5rem;
        color: #4e9af1;
        flex-shrink: 0;
    }

    /* ============================================================
       RESPONSIVE
    ============================================================ */
    @media (max-width: 768px) {
        .certificate-detail-header {
            flex-direction: column;
            text-align: center;
        }
        .certificate-detail-status {
            margin-left: 0;
        }
        .certificate-preview-content {
            flex-direction: column;
            text-align: center;
        }
        .certificate-detail-actions {
            flex-direction: column;
        }
        .certificate-detail-actions .btn {
            width: 100%;
            justify-content: center;
        }
        .certificate-verify,
        .certificate-share {
            flex-direction: column;
            text-align: center;
        }
    }
</style>
@endpush
@endsection