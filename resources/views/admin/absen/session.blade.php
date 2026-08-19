@extends('layouts.admin')

@section('title', 'Sesi Absensi: ' . $training->judul)

@section('header')
<div class="page-heading d-flex justify-content-between align-items-center">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-qr-code-scan"></i></span>
        <div>
            <p class="eyebrow mb-1">Absensi Pelatihan</p>
            <h1 class="h3 mb-0">{{ $training->judul }}</h1>
        </div>
    </div>
    <div>
        <a href="{{ route('admin.trainings.show', $training->id) }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row g-4 mb-4">
        <!-- Panel Kontrol Absensi -->
        <div class="col-12 col-lg-5">
            <div class="panel h-100">
                <div class="panel-header text-center border-bottom pb-3">
                    <h5 class="mb-0">QR Code Absensi</h5>
                    <p class="text-muted small mb-0">{{ \Carbon\Carbon::parse($today)->translatedFormat('l, d F Y') }}</p>
                </div>
                <div class="p-4 text-center">
                    @if($training->is_absen_open && $training->absen_token)
                        <div class="mb-4">
                            <span class="badge bg-success mb-3 fs-6 px-3 py-2">
                                <span class="spinner-grow spinner-grow-sm me-2" role="status" aria-hidden="true"></span>
                                Sesi Sedang Berlangsung
                            </span>
                        </div>
                        
                        <!-- QR Code Container -->
                        <div id="qrcode" class="d-inline-block p-3 bg-white border rounded shadow-sm mb-4"></div>
                        <p class="text-muted small mb-4">Minta peserta untuk melakukan scan barcode di atas.</p>
                        
                        <form action="{{ route('admin.trainings.absen.stop', $training->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Tutup sesi absensi sekarang?')">
                                <i class="bi bi-stop-circle"></i> Tutup Absensi
                            </button>
                        </form>
                    @else
                        <div class="mb-4">
                            <span class="badge bg-secondary mb-3 fs-6 px-3 py-2">Sesi Ditutup</span>
                        </div>
                        
                        <div class="p-5 bg-light border rounded mb-4 text-muted">
                            <i class="bi bi-qr-code text-secondary" style="font-size: 4rem;"></i>
                            <p class="mt-2 mb-0">Klik mulai untuk memunculkan QR Code</p>
                        </div>
                        
                        <form action="{{ route('admin.trainings.absen.start', $training->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-play-circle"></i> Mulai Absensi Sekarang
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Panel Statistik -->
        <div class="col-12 col-lg-7">
            <div class="row g-3 h-100">
                <div class="col-sm-6">
                    <div class="metric-card metric-primary h-100">
                        <div class="metric-top">
                            <span class="metric-label">Total Peserta</span>
                            <span class="metric-icon"><i class="bi bi-people"></i></span>
                        </div>
                        <div class="metric-value">{{ $participants->count() }}</div>
                        <div class="metric-meta">
                            <span class="text-primary">Disetujui</span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="metric-card metric-success h-100">
                        <div class="metric-top">
                            <span class="metric-label">Hadir</span>
                            <span class="metric-icon"><i class="bi bi-check2-circle"></i></span>
                        </div>
                        <div class="metric-value" id="hadir-count">{{ $hadirCount }}</div>
                        <div class="metric-meta">
                            <span class="text-success">{{ $persentase }}%</span>
                            <span>dari total</span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="metric-card metric-warning h-100">
                        <div class="metric-top">
                            <span class="metric-label">Belum Hadir</span>
                            <span class="metric-icon"><i class="bi bi-clock-history"></i></span>
                        </div>
                        <div class="metric-value" id="belum-hadir-count">{{ $belumHadirCount }}</div>
                        <div class="metric-meta">
                            <span class="text-warning">Menunggu</span>
                            <span>kehadiran</span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="metric-card metric-info h-100 d-flex flex-column justify-content-center align-items-center">
                        <button class="btn btn-outline-primary mb-2 w-100 h-100" onclick="location.reload();">
                            <i class="bi bi-arrow-clockwise" style="font-size: 2rem;"></i><br>
                            Refresh Data
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Peserta -->
    <div class="panel">
        <div class="panel-header d-flex justify-content-between align-items-center">
            <h5 class="section-title"><i class="bi bi-list-check"></i> Daftar Peserta Hari Ini</h5>
            
            <div class="d-flex gap-2">
                <form action="{{ route('admin.trainings.absen.mark-all', $training->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Tandai SEMUA peserta sebagai hadir?')">
                        <i class="bi bi-check-all"></i> Tandai Semua Hadir
                    </button>
                </form>
            </div>
        </div>
        
        <form action="{{ route('admin.trainings.absen.mark-present', $training->id) }}" method="POST" id="batch-form">
            @csrf
            
            <div class="p-3 bg-light border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="btn-select-all">
                        Pilih Semua
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="btn-deselect-all">
                        Batalkan Pilihan
                    </button>
                    <button type="submit" class="btn btn-primary btn-sm ms-2" id="btn-mark-selected" disabled>
                        Tandai Hadir (<span id="selected-count">0</span>)
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
                            <th width="40" class="text-center">#</th>
                            <th>Nama Peserta</th>
                            <th>Email</th>
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
                                        <i class="bi bi-check-lg text-success"></i>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-sm">
                                            @if($participant->user->avatar)
                                                <img src="{{ asset('storage/' . $participant->user->avatar) }}" alt="{{ $participant->user->nama }}" class="rounded-circle" width="32" height="32" style="object-fit: cover;">
                                            @else
                                                <div class="avatar-initial rounded-circle bg-primary text-white" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; font-size: 14px;">
                                                    {{ strtoupper(substr($participant->user->nama, 0, 2)) }}
                                                </div>
                                            @endif
                                        </div>
                                        <span class="fw-medium participant-name">{{ $participant->user->nama }}</span>
                                    </div>
                                </td>
                                <td class="text-muted">{{ $participant->user->email }}</td>
                                <td>
                                    @if($participant->absen_status === 'hadir')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">Hadir</span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle">Belum Hadir</span>
                                    @endif
                                </td>
                                <td>
                                    @if($participant->waktu_absen)
                                        {{ \Carbon\Carbon::parse($participant->waktu_absen)->format('H:i:s') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($participant->metode_absen)
                                        <small class="text-muted"><i class="bi {{ $participant->metode_absen == 'QR Code' ? 'bi-qr-code' : 'bi-person-gear' }} me-1"></i> {{ $participant->metode_absen }}</small>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <div class="mb-2"><i class="bi bi-people" style="font-size: 2rem;"></i></div>
                                    Belum ada peserta yang disetujui untuk pelatihan ini.
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
                width: 250,
                height: 250,
                colorDark : "#000000",
                colorLight : "#ffffff",
                correctLevel : QRCode.CorrectLevel.H
            });
            
            // Auto refresh stat numbers every 30 seconds (optional, uncomment to use)
            /*
            setInterval(() => {
                location.reload();
            }, 30000);
            */
        @endif
        
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
            selectedCount.textContent = checked;
            btnMarkSelected.disabled = checked === 0;
        }

        function applyFilters() {
            const term = searchInput ? searchInput.value.toLowerCase() : '';
            const status = filterStatus ? filterStatus.value : 'all';

            document.querySelectorAll('.participant-row').forEach(row => {
                const name = row.querySelector('.participant-name').textContent.toLowerCase();
                // We use data attributes or class to identify status for simplicity, or just read the DOM.
                // Let's read the DOM badge class to determine status
                const isHadir = row.innerHTML.includes('text-success'); // Simplistic way based on our markup
                
                let matchesSearch = name.includes(term);
                let matchesStatus = true;

                if (status === 'hadir' && !isHadir) matchesStatus = false;
                if (status === 'belum_hadir' && isHadir) matchesStatus = false;

                if (matchesSearch && matchesStatus) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                    // Uncheck if hidden
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
            
            btnSelectAll.addEventListener('click', () => {
                // Only select visible checkboxes
                document.querySelectorAll('.participant-row:not([style*="display: none"]) .participant-checkbox').forEach(cb => {
                    cb.checked = true;
                });
                updateSelectCount();
            });
            
            btnDeselectAll.addEventListener('click', () => {
                checkboxes.forEach(cb => cb.checked = false);
                updateSelectCount();
            });
        }
        
        // Search and Filter Events
        if (searchInput) {
            searchInput.addEventListener('keyup', applyFilters);
        }

        if (filterStatus) {
            filterStatus.addEventListener('change', applyFilters);
        }
    });
</script>
@endpush
