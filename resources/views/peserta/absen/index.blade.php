@extends('layouts.peserta')

@section('title', 'Absensi Pelatihan')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-check2-square"></i></span>
        <div>
            <p class="eyebrow">Kehadiran</p>
            <h1 class="h3 mb-0">Absensi Pelatihan</h1>
            <p class="text-muted mb-0">Lakukan absensi kehadiran Anda pada pelatihan yang sedang berlangsung.</p>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <!-- Alert Messages -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <div class="d-flex align-items-center">
            <i class="bi bi-check-circle me-2 fs-5"></i>
            <div>{{ session('success') }}</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <div class="d-flex align-items-center">
            <i class="bi bi-exclamation-circle me-2 fs-5"></i>
            <div>{{ session('error') }}</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('warning'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <div class="d-flex align-items-center">
            <i class="bi bi-exclamation-triangle me-2 fs-5"></i>
            <div>{{ session('warning') }}</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Info Banner -->
    <div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
        <div class="d-flex align-items-center">
            <i class="bi bi-info-circle-fill me-3 fs-4"></i>
            <div>
                <strong>Informasi Absensi</strong>
                <p class="mb-0 small">Silakan lakukan absensi pada pelatihan yang sedang berlangsung. Anda wajib absen sebelum dapat mengikuti quiz.</p>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>

    <!-- Stats Summary -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-success">
                <div class="metric-top">
                    <span class="metric-label">Total Kehadiran</span>
                    <span class="metric-icon"><i class="bi bi-check2-circle"></i></span>
                </div>
                <div class="metric-value">{{ $totalHadir ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-success">Hadir</span>
                    <span>selama ini</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-primary">
                <div class="metric-top">
                    <span class="metric-label">Hadir Bulan Ini</span>
                    <span class="metric-icon"><i class="bi bi-calendar-check"></i></span>
                </div>
                <div class="metric-value">{{ $hadirBulanIni ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-primary">Bulan {{ now()->format('F') }}</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-warning">
                <div class="metric-top">
                    <span class="metric-label">Total Pelatihan</span>
                    <span class="metric-icon"><i class="bi bi-journal-bookmark"></i></span>
                </div>
                <div class="metric-value">{{ $totalPelatihan ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-warning">Diikuti</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-info">
                <div class="metric-top">
                    <span class="metric-label">Persentase Kehadiran</span>
                    <span class="metric-icon"><i class="bi bi-percent"></i></span>
                </div>
                <div class="metric-value">{{ $persentaseKehadiran ?? 0 }}%</div>
                <div class="metric-meta">
                    <span class="text-info">Kehadiran</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Pelatihan untuk Absen -->
    <div class="panel">
        <div class="panel-header">
            <div>
                <h5 class="section-title"><i class="bi bi-list-check"></i> Daftar Pelatihan Wajib Absensi</h5>
                <p class="text-muted small mb-0">Klik tombol <strong>"Absen Sekarang"</strong> untuk melakukan absensi.</p>
            </div>
            <div>
                <span class="badge text-bg-success">
                    <i class="bi bi-circle-fill me-1" style="font-size: 6px;"></i>
                    {{ $availableTrainings->count() ?? 0 }} Pelatihan
                </span>
            </div>
        </div>

        <div class="table-responsive">
            @if(isset($availableTrainings) && $availableTrainings->count() > 0)
            <table class="table align-middle mb-0 table-hover">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Nama Pelatihan</th>
                        <th>Tanggal Pelaksanaan</th>
                        <th>Status Absensi</th>
                        <th class="text-center" style="width: 180px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($availableTrainings as $index => $training)
                    @php
                        // Cek status absensi
                        $sudahAbsen = $training->absensis->where('user_id', auth()->id())->first();
                    @endphp
                    <tr>
                        <td>{{ $availableTrainings->firstItem() + $index }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if($training->gambar)
                                <img src="{{ asset('storage/' . $training->gambar) }}" 
                                     alt="{{ $training->judul }}" 
                                     style="width: 40px; height: 40px; object-fit: cover; border-radius: 8px;">
                                @else
                                <div class="avatar-text rounded-circle bg-primary text-white" style="width: 40px; height: 40px; font-size: 0.8rem;">
                                    {{ strtoupper(substr($training->judul, 0, 2)) }}
                                </div>
                                @endif
                                <div>
                                    <p class="fw-semibold mb-0">{{ $training->judul }}</p>
                                    <small class="text-muted">{{ $training->kategori->nama ?? 'Umum' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="small">
                                <div><i class="bi bi-calendar3 me-1"></i> {{ $training->tanggal_mulai ? $training->tanggal_mulai->format('d/m/Y') : '-' }}</div>
                                <div><i class="bi bi-calendar3 me-1"></i> {{ $training->tanggal_selesai ? $training->tanggal_selesai->format('d/m/Y') : '-' }}</div>
                            </div>
                        </td>
                        <td>
                            @if($sudahAbsen)
                                <span class="badge text-bg-success">
                                    <i class="bi bi-check2-circle me-1"></i> Sudah Absen
                                </span>
                                <small class="text-muted d-block">
                                    {{ $sudahAbsen->created_at ? $sudahAbsen->created_at->format('d/m/Y H:i') : '-' }}
                                </small>
                            @else
                                <span class="badge text-bg-danger">
                                    <i class="bi bi-x-circle me-1"></i> Belum Absen
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($sudahAbsen)
                                <span class="text-success fs-4" title="Sudah Absen">
                                    <i class="bi bi-check2-circle"></i>
                                </span>
                            @else
                                <button type="button" class="btn btn-success btn-sm px-4" 
                                        onclick="openScanner()">
                                    <i class="bi bi-qr-code-scan me-1"></i> Scan QR Code
                                </button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="text-center py-5">
                <div class="text-muted">
                    <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                    <p class="h5">Belum Ada Pelatihan yang Diikuti</p>
                    <p class="small">
                        Anda belum terdaftar dalam pelatihan apapun.
                    </p>
                </div>
            </div>
            @endif
        </div>
        @if(isset($availableTrainings) && method_exists($availableTrainings, 'hasPages') && $availableTrainings->hasPages())
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mt-3 px-3 pb-3">
            <p class="text-muted small mb-0">
                Menampilkan {{ $availableTrainings->firstItem() ?? 0 }} sampai {{ $availableTrainings->lastItem() ?? 0 }} 
                dari {{ $availableTrainings->total() ?? 0 }} pelatihan
            </p>
            <nav aria-label="Pagination">
                {{ $availableTrainings->appends(request()->query())->links() }}
            </nav>
        </div>
        @endif
    </div>

    <!-- Riwayat Absensi -->
    <div class="panel mt-4">
        <div class="panel-header">
            <div>
                <h5 class="section-title"><i class="bi bi-clock-history"></i> Riwayat Absensi</h5>
                <p class="text-muted small mb-0">Riwayat absensi yang telah Anda lakukan.</p>
            </div>
            <div>
                <span class="badge text-bg-secondary">
                    <i class="bi bi-clock me-1"></i>
                    Total: {{ $riwayatAbsensi->total() ?? 0 }} absensi
                </span>
            </div>
        </div>
        <div class="table-responsive">
            @if(isset($riwayatAbsensi) && $riwayatAbsensi->count() > 0)
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Pelatihan</th>
                        <th>Tanggal Absen</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($riwayatAbsensi as $index => $absen)
                    <tr>
                        <td>{{ $riwayatAbsensi->firstItem() + $index }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if($absen->training->gambar ?? false)
                                <img src="{{ asset('storage/' . $absen->training->gambar) }}" 
                                     alt="{{ $absen->training->judul }}" 
                                     style="width: 32px; height: 32px; object-fit: cover; border-radius: 6px;">
                                @else
                                <div class="avatar-text rounded-circle bg-success text-white" style="width: 32px; height: 32px; font-size: 0.7rem;">
                                    {{ strtoupper(substr($absen->training->judul ?? '-', 0, 2)) }}
                                </div>
                                @endif
                                <div>
                                    <p class="fw-semibold mb-0">{{ Str::limit($absen->training->judul ?? '-', 40) }}</p>
                                    <small class="text-muted">{{ $absen->training->kategori->nama ?? '-' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>{{ $absen->created_at ? $absen->created_at->format('d/m/Y H:i') : '-' }}</td>
                        <td>
                            <span class="badge text-bg-success">
                                <i class="bi bi-check2-circle me-1"></i> Hadir
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="text-center py-4">
                <i class="bi bi-clock-history fs-2 text-muted d-block mb-2"></i>
                <p class="text-muted small mb-0">Belum ada riwayat absensi.</p>
            </div>
            @endif
        </div>
        @if(isset($riwayatAbsensi) && $riwayatAbsensi->hasPages())
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mt-3 px-3 pb-3">
            <p class="text-muted small mb-0">
                Menampilkan {{ $riwayatAbsensi->firstItem() ?? 0 }} sampai {{ $riwayatAbsensi->lastItem() ?? 0 }} 
                dari {{ $riwayatAbsensi->total() ?? 0 }} riwayat
            </p>
            <nav aria-label="Riwayat pagination">
                {{ $riwayatAbsensi->appends(request()->query())->links() }}
            </nav>
        </div>
        @endif
    </div>
</div>

<!-- Modal Scanner -->
<div class="modal fade" id="scannerModal" tabindex="-1" aria-labelledby="scannerModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="scannerModalLabel"><i class="bi bi-qr-code-scan"></i> Scan QR Code Kehadiran</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="stopScanner()"></button>
      </div>
      <div class="modal-body text-center">
        <div id="reader" style="width: 100%; margin: 0 auto;"></div>
        <p class="text-muted small mt-3 mb-0">Arahkan kamera ke QR Code yang ditampilkan Admin.</p>
      </div>
    </div>
  </div>
</div>
@push('styles')
<style>
    .avatar-text {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.7rem;
        color: #fff;
        background: var(--accent);
        border-radius: 50%;
        flex-shrink: 0;
    }
    .metric-card {
        transition: transform 0.2s ease;
    }
    .metric-card:hover {
        transform: translateY(-2px);
    }
    .panel-header {
        flex-wrap: wrap;
    }
    .btn-success {
        min-width: 120px;
    }
    .alert-info {
        background-color: #e8f4f8;
        border-color: #b8dce8;
        color: #1a3a4a;
    }
    .table th {
        font-weight: 600;
        font-size: .75rem;
        text-transform: uppercase;
        letter-spacing: .03em;
        color: #6c757d;
        border-bottom-width: 2px;
        white-space: nowrap;
    }
    .table td {
        vertical-align: middle;
    }
    .table .badge {
        font-weight: 500;
        padding: 0.35rem 0.7rem;
    }
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
        cursor: pointer;
    }
    .table-hover tbody tr:hover .btn-success {
        transform: scale(1.05);
        transition: transform 0.2s ease;
    }
    @media (max-width: 768px) {
        .btn-success {
            min-width: auto;
            width: 100%;
        }
        .panel-header {
            flex-direction: column;
            align-items: flex-start;
        }
        .metric-value {
            font-size: 1.2rem;
        }
        .panel-header .d-flex {
            width: 100%;
            flex-wrap: wrap;
        }
        .table td, .table th {
            padding: 0.5rem;
            font-size: 0.8rem;
        }
        .table .btn-sm {
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
        }
        .table td .d-flex {
            flex-wrap: wrap;
        }
        .table td .d-flex img,
        .table td .d-flex .avatar-text {
            width: 30px;
            height: 30px;
        }
    }
</style>
@endpush

@push('scripts')
<!-- Load HTML5-QRCode -->
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<script>
    let html5QrcodeScanner = null;

    function openScanner() {
        var myModal = new bootstrap.Modal(document.getElementById('scannerModal'));
        myModal.show();
        
        // Ensure DOM is fully ready for scanner
        setTimeout(function() {
            if(!html5QrcodeScanner) {
                html5QrcodeScanner = new Html5QrcodeScanner(
                    "reader",
                    { fps: 10, qrbox: {width: 250, height: 250} },
                    /* verbose= */ false);
                    
                html5QrcodeScanner.render(function(decodedText, decodedResult) {
                    // Cek URL apakah mengandung /peserta/absen/scan/
                    if (decodedText.includes('/absen/scan/') || decodedText.includes('scan')) {
                        html5QrcodeScanner.clear().then(() => {
                            window.location.href = decodedText;
                        });
                    } else {
                        alert('QR Code tidak dikenali oleh sistem ini.');
                    }
                }, function(error) {
                    // error handler
                });
            }
        }, 300);
    }

    function stopScanner() {
        if (html5QrcodeScanner) {
            html5QrcodeScanner.clear().then(() => {
                html5QrcodeScanner = null;
            });
        }
    }
    
    // Cleanup if modal closed
    document.getElementById('scannerModal').addEventListener('hidden.bs.modal', function () {
        stopScanner();
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Auto close alerts after 5 seconds
        setTimeout(function() {
            document.querySelectorAll('.alert').forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    });
</script>
@endpush
@endsection