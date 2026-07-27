@extends('layouts.admin')

@section('title', $sertifikat->nama_sertifikat)

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon"><i class="bi bi-award" aria-hidden="true"></i></span>
            <div>
                <p class="eyebrow mb-1">Detail Sertifikat</p>
                <h1 class="h3 mb-1">{{ $sertifikat->nama_sertifikat }}</h1>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12 col-lg-10 mx-auto">
            <!-- Alert Messages -->
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <!-- Main Card -->
            <div class="panel">
                <div class="panel-header">
                    <h2 class="h5 mb-0 section-title">
                        <i class="bi bi-info-circle"></i> Detail Sertifikat
                    </h2>
                    <span class="badge {{ $sertifikat->status == 'aktif' ? 'text-bg-success' : ($sertifikat->status == 'revoked' ? 'text-bg-danger' : 'text-bg-warning') }}">
                        <i class="bi bi-circle-fill me-1" style="font-size: 6px;"></i>
                        {{ ucfirst($sertifikat->status) }}
                    </span>
                </div>
                <div class="p-4">
                    <div class="row g-4">
                        <!-- Peserta -->
                        <div class="col-12 col-md-6">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-person fs-4 text-primary"></i>
                                </div>
                                <div>
                                    <label class="text-muted small fw-semibold">Peserta</label>
                                    <p class="fw-semibold mb-0">{{ $sertifikat->user->nama }}</p>
                                    <p class="text-muted small mb-0">{{ $sertifikat->user->email }}</p>
                                    <p class="text-muted small">NIK: {{ $sertifikat->user->nik ?? '-' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Training -->
                        <div class="col-12 col-md-6">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-mortarboard fs-4 text-success"></i>
                                </div>
                                <div>
                                    <label class="text-muted small fw-semibold">Pelatihan</label>
                                    <p class="fw-semibold mb-0">
                                        {{ $sertifikat->training ? $sertifikat->training->judul : 'Tidak ada' }}
                                    </p>
                                    @if($sertifikat->training)
                                    <p class="text-muted small">
                                        {{ $sertifikat->training->tanggal_mulai ? $sertifikat->training->tanggal_mulai->format('d/m/Y') : '' }}
                                        {{ $sertifikat->training->tanggal_selesai ? ' - ' . $sertifikat->training->tanggal_selesai->format('d/m/Y') : '' }}
                                    </p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Nomor Sertifikat -->
                        <div class="col-12 col-md-6">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-hash fs-4 text-info"></i>
                                </div>
                                <div>
                                    <label class="text-muted small fw-semibold">Nomor Sertifikat</label>
                                    <p class="fw-semibold mb-0 font-monospace">{{ $sertifikat->nomor_sertifikat }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="col-12 col-md-6">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-toggle-on fs-4 text-warning"></i>
                                </div>
                                <div>
                                    <label class="text-muted small fw-semibold">Status</label>
                                    <p class="fw-semibold mb-0">
                                        <span class="badge {{ $sertifikat->status == 'aktif' ? 'text-bg-success' : ($sertifikat->status == 'revoked' ? 'text-bg-danger' : 'text-bg-warning') }}">
                                            {{ ucfirst($sertifikat->status) }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Tanggal Terbit -->
                        <div class="col-12 col-md-6">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-calendar-check fs-4 text-primary"></i>
                                </div>
                                <div>
                                    <label class="text-muted small fw-semibold">Tanggal Terbit</label>
                                    <p class="fw-semibold mb-0">{{ $sertifikat->tanggal_terbit->format('d/m/Y') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Tanggal Berlaku Sampai -->
                        <div class="col-12 col-md-6">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-calendar-x fs-4 text-danger"></i>
                                </div>
                                <div>
                                    <label class="text-muted small fw-semibold">Berlaku Sampai</label>
                                    <p class="fw-semibold mb-0">
                                        @if($sertifikat->tanggal_berlaku_sampai)
                                            {{ $sertifikat->tanggal_berlaku_sampai->format('d/m/Y') }}
                                            @if($sertifikat->isExpired())
                                                <span class="badge text-bg-danger ms-2">Kadaluarsa</span>
                                            @endif
                                        @else
                                            <span class="text-muted">Tidak ada batasan</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Penerbit -->
                        <div class="col-12">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-building fs-4 text-secondary"></i>
                                </div>
                                <div>
                                    <label class="text-muted small fw-semibold">Penerbit</label>
                                    <p class="fw-semibold mb-0">{{ $sertifikat->penerbit }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        @if($sertifikat->deskripsi)
                        <div class="col-12">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-file-text fs-4 text-info"></i>
                                </div>
                                <div>
                                    <label class="text-muted small fw-semibold">Deskripsi</label>
                                    <p class="mb-0">{{ $sertifikat->deskripsi }}</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Tanda Tangan Digital -->
                        @if($sertifikat->tanda_tangan_digital)
                        <div class="col-12">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-pen fs-4 text-purple"></i>
                                </div>
                                <div>
                                    <label class="text-muted small fw-semibold">Tanda Tangan Digital</label>
                                    <p class="mb-0 font-monospace small">{{ $sertifikat->tanda_tangan_digital }}</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Catatan -->
                        @if($sertifikat->catatan)
                        <div class="col-12">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-sticky fs-4 text-warning"></i>
                                </div>
                                <div>
                                    <label class="text-muted small fw-semibold">Catatan</label>
                                    <p class="mb-0">{{ $sertifikat->catatan }}</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- File Sertifikat -->
                        <div class="col-12">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-file-pdf fs-4 text-danger"></i>
                                </div>
                                <div>
                                    <label class="text-muted small fw-semibold">File Sertifikat</label>
                                    <div>
                                        @if($sertifikat->file_path)
                                            <a href="{{ route('admin.sertifikat.download', $sertifikat->id) }}" 
                                               class="btn btn-sm btn-primary" target="_blank">
                                                <i class="bi bi-download me-1"></i> Download File
                                            </a>
                                            <span class="text-muted small ms-2">
                                                {{ basename($sertifikat->file_path) }}
                                            </span>
                                        @else
                                            <p class="text-muted mb-0">Tidak ada file</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Meta Info -->
                        <div class="col-12">
                            <hr class="my-2">
                            <div class="d-flex justify-content-between text-muted small">
                                <span>
                                    <i class="bi bi-clock me-1"></i> 
                                    Dibuat: {{ $sertifikat->created_at->format('d/m/Y H:i') }}
                                </span>
                                <span>
                                    <i class="bi bi-clock-history me-1"></i> 
                                    Diperbarui: {{ $sertifikat->updated_at->format('d/m/Y H:i') }}
                                </span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="col-12 mt-2">
                            <hr class="my-2">
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="{{ route('admin.sertifikat.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left me-1"></i> Kembali
                                </a>
                                
                                <a href="{{ route('admin.sertifikat.edit', $sertifikat->id) }}" class="btn btn-warning">
                                    <i class="bi bi-pencil me-1"></i> Edit Sertifikat
                                </a>
                                
                                @if($sertifikat->status == 'aktif')
                                <form action="{{ route('admin.sertifikat.status', $sertifikat->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="revoked">
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin mencabut sertifikat ini?')">
                                        <i class="bi bi-x-circle me-1"></i> Cabut Sertifikat
                                    </button>
                                </form>
                                @elseif($sertifikat->status == 'revoked' || $sertifikat->status == 'expired')
                                <form action="{{ route('admin.sertifikat.status', $sertifikat->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="aktif">
                                    <button type="submit" class="btn btn-success" onclick="return confirm('Yakin ingin mengaktifkan kembali sertifikat ini?')">
                                        <i class="bi bi-check-circle me-1"></i> Aktifkan Kembali
                                    </button>
                                </form>
                                @endif
                                
                                <form action="{{ route('admin.sertifikat.destroy', $sertifikat->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Yakin ingin menghapus sertifikat ini?')">
                                        <i class="bi bi-trash me-1"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Verification Card -->
            <div class="panel mt-3">
                <div class="panel-header">
                    <h2 class="h5 mb-0 section-title">
                        <i class="bi bi-shield-check"></i> Verifikasi Sertifikat
                    </h2>
                </div>
                <div class="p-4">
                    <div class="row g-3 align-items-center">
                        <div class="col-12 col-md-8">
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-hash"></i></span>
                                <input type="text" class="form-control" id="verificationInput" 
                                       value="{{ $sertifikat->nomor_sertifikat }}" readonly>
                                <button class="btn btn-outline-secondary" type="button" 
                                        onclick="copyToClipboard()">
                                    <i class="bi bi-clipboard"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <a href="{{ route('sertifikat.verify') }}" class="btn btn-primary w-100" target="_blank">
                                <i class="bi bi-search me-1"></i> Verifikasi Sertifikat
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function copyToClipboard() {
        const input = document.getElementById('verificationInput');
        input.select();
        input.setSelectionRange(0, 99999);
        document.execCommand('copy');
        
        // Show feedback
        const btn = document.querySelector('[onclick="copyToClipboard()"]');
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-check"></i> Tersalin';
        setTimeout(() => {
            btn.innerHTML = originalHtml;
        }, 2000);
    }
</script>
@endpush
@endsection