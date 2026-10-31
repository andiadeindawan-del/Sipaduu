@extends('layouts.pemateri')

@section('title', 'Sesi Absensi QR: ' . $training->judul)

@section('header')
<div class="page-heading d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div class="page-heading-copy d-flex align-items-center gap-3">
        <span class="page-icon" style="color: #6f42c1;"><i class="bi bi-qr-code-scan"></i></span>
        <div>
            <p class="eyebrow mb-1">Presensi Pelatihan</p>
            <h1 class="h3 mb-0">{{ $training->judul }}</h1>
            <p class="text-muted small mb-0">{{ \Carbon\Carbon::parse($today)->translatedFormat('l, d F Y') }}</p>
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('pemateri.absen.export', $training->id) }}" class="btn btn-outline-success btn-sm">
            <i class="bi bi-download me-1"></i> Export Presensi
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <!-- Alert Messages -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-circle me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row g-4 mb-4">
        <!-- ======================================================== -->
        <!-- PANEL QR CODE & KONTROL SESI -->
        <!-- ======================================================== -->
        <div class="col-12 col-lg-5">
            <div class="panel border-0 shadow-sm h-100">
                <div class="panel-header text-center border-bottom pb-3">
                    <h5 class="section-title justify-content-center mb-0">
                        <i class="bi bi-qr-code text-primary me-1"></i> QR Code Absensi
                    </h5>
                    <p class="text-muted small mb-0">{{ \Carbon\Carbon::parse($today)->translatedFormat('l, d F Y') }}</p>
                </div>
                
                <div class="p-4 text-center">
                    @if($training->is_absen_open && $training->absen_token)
                        <div class="mb-3">
                            <span class="badge bg-success fs-6 px-3 py-2 shadow-sm" style="border-radius: 20px;">
                                <span class="spinner-grow spinner-grow-sm me-2" role="status" aria-hidden="true" style="width: 10px; height: 10px;"></span>
                                Sesi Sedang Berlangsung
                            </span>
                        </div>
                        
                        <!-- QR Code Container -->
                        <div id="qrcode" class="d-inline-block p-3 bg-white border rounded shadow-sm mb-3"></div>
                        
                        <p class="text-muted small mb-3">
                            Arahkan peserta untuk scan barcode di atas menggunakan kamera smartphone atau menu absensi peserta.
                        </p>

                        <!-- Link Alternatif Scan -->
                        @php
                            $scanUrl = route('peserta.absen.scan', ['training' => $training->id, 'token' => $training->absen_token]);
                        @endphp
                        <div class="input-group input-group-sm mb-3">
                            <input type="text" class="form-control text-center font-monospace bg-light" id="scanUrlInput" value="{{ $scanUrl }}" readonly>
                            <button class="btn btn-outline-secondary" type="button" onclick="copyScanUrl()" title="Salin Link">
                                <i class="bi bi-clipboard"></i> Salin
                            </button>
                        </div>
                        
                        <form action="{{ route('pemateri.absen.stop', $training->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger w-100 fw-semibold" onclick="return confirm('Tutup sesi absensi sekarang? Peserta tidak akan bisa scan QR code ini lagi.')">
                                <i class="bi bi-stop-circle me-1"></i> Tutup Sesi Absensi
                            </button>
                        </form>
                    @else
                        <div class="mb-3">
                            <span class="badge bg-secondary mb-2 fs-6 px-3 py-2" style="border-radius: 20px;">
                                <i class="bi bi-lock me-1"></i> Sesi Ditutup
                            </span>
                        </div>
                        
                        <div class="p-5 bg-light border rounded mb-4 text-muted">
                            <i class="bi bi-qr-code text-secondary" style="font-size: 4.5rem;"></i>
                            <p class="mt-2 mb-0 fw-semibold">Sesi absensi saat ini belum dibuka</p>
                            <small class="text-muted">Klik tombol di bawah untuk membuka sesi dan memunculkan QR Code untuk peserta.</small>
                        </div>
                        
                        <form action="{{ route('pemateri.absen.start', $training->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary w-100 fw-semibold py-2">
                                <i class="bi bi-play-circle me-1"></i> Buka Sesi Absensi Sekarang
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- ======================================================== -->
        <!-- PANEL STATISTIK KEHADIRAN -->
        <!-- ======================================================== -->
        <div class="col-12 col-lg-7">
            <div class="row g-3 h-100">
                <div class="col-sm-6">
                    <div class="metric-card metric-primary h-100 border-0 shadow-sm">
                        <div class="metric-top">
                            <span class="metric-label">Total Peserta</span>
                            <span class="metric-icon"><i class="bi bi-people-fill"></i></span>
                        </div>
                        <div class="metric-value">{{ $participants->count() }}</div>
                        <div class="metric-meta">
                            <span class="text-primary fw-semibold">Disetujui di kelas</span>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="metric-card metric-success h-100 border-0 shadow-sm">
                        <div class="metric-top">
                            <span class="metric-label">Hadir Hari Ini</span>
                            <span class="metric-icon"><i class="bi bi-check2-circle"></i></span>
                        </div>
                        <div class="metric-value text-success" id="hadir-count">{{ $hadirCount }}</div>
                        <div class="metric-meta">
                            <span class="text-success fw-bold">{{ $persentase }}%</span>
                            <span>kehadiran</span>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="metric-card metric-warning h-100 border-0 shadow-sm">
                        <div class="metric-top">
                            <span class="metric-label">Belum Hadir</span>
                            <span class="metric-icon"><i class="bi bi-clock-history"></i></span>
                        </div>
                        <div class="metric-value text-warning" id="belum-hadir-count">{{ $belumHadirCount }}</div>
                        <div class="metric-meta">
                            <span class="text-warning fw-semibold">Menunggu scan/hadir</span>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="metric-card border-0 shadow-sm h-100 d-flex flex-column justify-content-center align-items-center text-center p-3">
                        <button class="btn btn-outline-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center" onclick="location.reload();">
                            <i class="bi bi-arrow-clockwise fs-2 mb-1"></i>
                            <span class="fw-semibold">Refresh Data Kehadiran</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- TABEL DAFTAR PESERTA & STATUS PRESENSI HARI INI -->
    <!-- ======================================================== -->
    <div class="panel border-0 shadow-sm">
        <div class="panel-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h5 class="section-title"><i class="bi bi-list-check text-primary"></i> Daftar Kehadiran Peserta Hari Ini</h5>
                <p class="text-muted small mb-0">Tandai kehadiran peserta secara batch atau pantau hasil scan QR code.</p>
            </div>
            
            <div class="d-flex gap-2">
                <form action="{{ route('pemateri.absen.mark-all', $training->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success btn-sm fw-semibold" onclick="return confirm('Tandai SEMUA peserta yang terdaftar sebagai HADIR hari ini?')">
                        <i class="bi bi-check-all me-1"></i> Tandai Semua Hadir
                    </button>
                </form>
            </div>
        </div>
        
        <form action="{{ route('pemateri.absen.mark-present', $training->id) }}" method="POST" id="batch-form">
            @csrf
            
            <div class="p-3 bg-light border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex gap-2 align-items-center flex-wrap">
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="btn-select-all">
                        Pilih Semua
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="btn-deselect-all">
                        Batalkan Pilihan
                    </button>
                    <button type="submit" class="btn btn-primary btn-sm" id="btn-mark-selected" disabled>
                        <i class="bi bi-check2-circle me-1"></i> Tandai Hadir (<span id="selected-count">0</span>)
                    </button>
                </div>
                
                <div class="d-flex gap-2" style="width: 100%; max-width: 450px;">
                    <select class="form-select form-select-sm" id="filter-status" style="max-width: 150px;">
                        <option value="all">Semua Status</option>
                        <option value="hadir">Hadir</option>
                        <option value="belum_hadir">Belum Hadir</option>
                    </select>
                    <input type="text" class="form-control form-control-sm" id="search-input" placeholder="Cari nama peserta...">
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="participants-table">
                    <thead class="table-light">
                        <tr>
                            <th width="45" class="text-center">#</th>
                            <th>Nama Peserta</th>
                            <th>Email & Kontak</th>
                            <th>Status Kehadiran</th>
                            <th>Waktu Absen</th>
                            <th>Metode</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($participants as $participant)
                            <tr class="participant-row">
                                <td class="text-center">
                                    @if($participant->absen_status !== 'hadir')
                                        <div class="form-check d-flex justify-content-center">
                                            <input class="form-check-input participant-checkbox" type="checkbox" name="user_ids[]" value="{{ $participant->user_id }}">
                                        </div>
                                    @else
                                        <i class="bi bi-check-circle-fill text-success fs-5"></i>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if($participant->user->foto)
                                            <img src="{{ asset('storage/' . $participant->user->foto) }}" alt="{{ $participant->user->nama }}" class="rounded-circle" width="34" height="34" style="object-fit: cover;">
                                        @else
                                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 34px; height: 34px; font-size: 13px; font-weight: bold;">
                                                {{ strtoupper(substr($participant->user->nama ?? $participant->user->name ?? 'P', 0, 2)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <span class="fw-semibold participant-name text-dark d-block">{{ $participant->user->nama ?? $participant->user->name }}</span>
                                            <small class="text-muted font-monospace">{{ $participant->user->nik ?? '-' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="small text-muted">{{ $participant->user->email }}</div>
                                    <div class="small text-muted"><i class="bi bi-telephone text-success me-1"></i>{{ $participant->user->no_telepon ?? '-' }}</div>
                                </td>
                                <td>
                                    @if($participant->absen_status === 'hadir')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1" style="border-radius: 20px;">
                                            <i class="bi bi-check-circle-fill me-1" style="font-size: 7px;"></i> Hadir
                                        </span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2.5 py-1" style="border-radius: 20px;">
                                            <i class="bi bi-clock me-1" style="font-size: 7px;"></i> Belum Hadir
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($participant->waktu_absen)
                                        <span class="small fw-semibold text-dark">{{ \Carbon\Carbon::parse($participant->waktu_absen)->format('H:i:s') }}</span>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($participant->metode_absen)
                                        <small class="text-muted">
                                            <i class="bi {{ str_contains($participant->metode_absen, 'QR') ? 'bi-qr-code text-primary' : 'bi-person-check text-secondary' }} me-1"></i>
                                            {{ $participant->metode_absen }}
                                        </small>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-people fs-1 d-block mb-2"></i>
                                    <p class="mb-0">Belum ada peserta yang disetujui untuk pelatihan ini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<!-- Load QRCode.js from CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if($training->is_absen_open && $training->absen_token)
            // Generate QR Code
            const absenUrl = "{{ route('peserta.absen.scan', ['training' => $training->id, 'token' => $training->absen_token]) }}";
            new QRCode(document.getElementById("qrcode"), {
                text: absenUrl,
                width: 240,
                height: 240,
                colorDark : "#000000",
                colorLight : "#ffffff",
                correctLevel : QRCode.CorrectLevel.H
            });
        @endif

        // Salin Link Scan
        window.copyScanUrl = function() {
            const input = document.getElementById('scanUrlInput');
            if (input) {
                input.select();
                navigator.clipboard.writeText(input.value);
                alert('Link scan absensi berhasil disalin!');
            }
        };
        
        // Select logic
        const checkboxes = document.querySelectorAll('.participant-checkbox');
        const btnSelectAll = document.getElementById('btn-select-all');
        const btnDeselectAll = document.getElementById('btn-deselect-all');
        const btnMarkSelected = document.getElementById('btn-mark-selected');
        const selectedCount = document.getElementById('selected-count');
        const searchInput = document.getElementById('search-input');
        const filterStatus = document.getElementById('filter-status');
        
        function updateSelectCount() {
            const checked = document.querySelectorAll('.participant-checkbox:checked').length;
            if (selectedCount) selectedCount.textContent = checked;
            if (btnMarkSelected) btnMarkSelected.disabled = checked === 0;
        }

        function applyFilters() {
            const term = searchInput ? searchInput.value.toLowerCase() : '';
            const status = filterStatus ? filterStatus.value : 'all';

            document.querySelectorAll('.participant-row').forEach(row => {
                const nameEl = row.querySelector('.participant-name');
                const name = nameEl ? nameEl.textContent.toLowerCase() : '';
                const isHadir = row.innerHTML.includes('text-success');
                
                let matchesSearch = name.includes(term);
                let matchesStatus = true;

                if (status === 'hadir' && !isHadir) matchesStatus = false;
                if (status === 'belum_hadir' && isHadir) matchesStatus = false;

                if (matchesSearch && matchesStatus) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                    const cb = row.querySelector('.participant-checkbox');
                    if (cb) cb.checked = false;
                }
            });
            updateSelectCount();
        }
        
        if (checkboxes.length > 0) {
            checkboxes.forEach(cb => {
                cb.addEventListener('change', updateSelectCount);
            });
            
            if (btnSelectAll) {
                btnSelectAll.addEventListener('click', () => {
                    document.querySelectorAll('.participant-row:not([style*="display: none"]) .participant-checkbox').forEach(cb => {
                        cb.checked = true;
                    });
                    updateSelectCount();
                });
            }
            
            if (btnDeselectAll) {
                btnDeselectAll.addEventListener('click', () => {
                    checkboxes.forEach(cb => cb.checked = false);
                    updateSelectCount();
                });
            }
        }
        
        if (searchInput) {
            searchInput.addEventListener('keyup', applyFilters);
        }

        if (filterStatus) {
            filterStatus.addEventListener('change', applyFilters);
        }
    });
</script>
@endpush
