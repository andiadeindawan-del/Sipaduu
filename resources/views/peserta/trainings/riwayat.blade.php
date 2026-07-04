@extends('layouts.peserta')

@section('title', 'Riwayat Pelatihan')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-clock-history"></i></span>
        <div>
            <p class="eyebrow">Riwayat</p>
            <h1 class="h3 mb-0">Riwayat Pelatihan</h1>
            <p class="text-muted mb-0">Daftar pelatihan yang telah Anda selesaikan.</p>
        </div>
    </div>
    <div class="heading-actions">
        <a href="{{ route('peserta.trainings.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <!-- Stats -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-success">
                <div class="metric-top">
                    <span class="metric-label">Total Pelatihan</span>
                    <span class="metric-icon"><i class="bi bi-journal-bookmark"></i></span>
                </div>
                <div class="metric-value">{{ $totalTrainings ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-success">Selesai</span>
                    <span>pelatihan</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-primary">
                <div class="metric-top">
                    <span class="metric-label">Sertifikat</span>
                    <span class="metric-icon"><i class="bi bi-award"></i></span>
                </div>
                <div class="metric-value">{{ $totalCertificates ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-primary">Diterbitkan</span>
                    <span>sertifikat</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-warning">
                <div class="metric-top">
                    <span class="metric-label">Rata-rata Nilai</span>
                    <span class="metric-icon"><i class="bi bi-star"></i></span>
                </div>
                <div class="metric-value">{{ $averageScore ?? 0 }}%</div>
                <div class="metric-meta">
                    <span class="text-warning">Keseluruhan</span>
                    <span>nilai</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-info">
                <div class="metric-top">
                    <span class="metric-label">Durasi Belajar</span>
                    <span class="metric-icon"><i class="bi bi-clock"></i></span>
                </div>
                <div class="metric-value">{{ $totalDuration ?? 0 }} jam</div>
                <div class="metric-meta">
                    <span class="text-info">Total</span>
                    <span>waktu belajar</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="panel mb-3">
        <div class="panel-header">
            <div>
                <h5 class="section-title"><i class="bi bi-funnel"></i> Filter</h5>
            </div>
            <form action="{{ route('peserta.trainings.riwayat') }}" method="GET" class="d-flex gap-2 flex-wrap">
                <input class="form-control form-control-sm" type="search" name="search" 
                       placeholder="Cari pelatihan..." value="{{ request('search') }}" style="width: 200px;">
                <select class="form-select form-select-sm" name="status" style="width: 150px;">
                    <option value="">Semua Status</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="bersertifikat" {{ request('status') == 'bersertifikat' ? 'selected' : '' }}>Bersertifikat</option>
                </select>
                <button type="submit" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-search"></i> Filter
                </button>
                <a href="{{ route('peserta.trainings.riwayat') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                </a>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="panel">
        <div class="panel-header">
            <div>
                <h5 class="section-title"><i class="bi bi-table"></i> Daftar Riwayat Pelatihan</h5>
                <p class="text-muted small mb-0">Pelatihan yang telah Anda selesaikan.</p>
            </div>
            <div>
                <a href="{{ route('peserta.trainings.riwayat.export') }}" class="btn btn-sm btn-success">
                    <i class="bi bi-download"></i> Export
                </a>
            </div>
        </div>
        <div class="table-responsive">
            @if($trainings->count() > 0)
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Pelatihan</th>
                        <th>Kategori</th>
                        <th>Tanggal</th>
                        <th>Nilai</th>
                        <th>Status</th>
                        <th class="text-end" style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($trainings as $index => $training)
                    <tr>
                        <td>{{ $trainings->firstItem() + $index }}</td>
                        <td>
                            <div>
                                <p class="fw-semibold mb-0">{{ $training->judul }}</p>
                                <p class="text-muted small mb-0">
                                    <i class="bi bi-calendar3 me-1"></i>
                                    {{ $training->tanggal_mulai ? $training->tanggal_mulai->format('d/m/Y') : '-' }}
                                    {{ $training->tanggal_selesai ? ' - ' . $training->tanggal_selesai->format('d/m/Y') : '' }}
                                </p>
                            </div>
                        </td>
                        <td>
                            @if($training->kategori)
                            <span class="badge" style="background-color: {{ $training->kategori->warna ?? '#6c757d' }}; color: #fff;">
                                <i class="bi {{ $training->kategori->icon ?? 'bi-tag' }} me-1"></i>
                                {{ $training->kategori->nama }}
                            </span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <div class="small">
                                <div><i class="bi bi-calendar-check me-1"></i> 
                                    {{ $training->tanggal_mulai ? $training->tanggal_mulai->format('d/m/Y') : '-' }}
                                </div>
                                <div><i class="bi bi-calendar-x me-1"></i> 
                                    {{ $training->tanggal_selesai ? $training->tanggal_selesai->format('d/m/Y') : '-' }}
                                </div>
                            </div>
                        </td>
                        <td>
                            @php
                                $nilai = $training->pivot->score ?? null;
                                $isPassed = $nilai && $nilai >= ($training->passing_score ?? 70);
                            @endphp
                            @if($nilai)
                            <div class="text-center">
                                <span class="fw-bold {{ $isPassed ? 'text-success' : 'text-danger' }}">
                                    {{ $nilai }}
                                </span>
                                <span class="text-muted small">/ 100</span>
                                @if($isPassed)
                                <span class="badge text-bg-success d-block mt-1">✅ Lulus</span>
                                @else
                                <span class="badge text-bg-danger d-block mt-1">❌ Tidak Lulus</span>
                                @endif
                            </div>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge text-bg-success">
                                <i class="bi bi-check-circle-fill me-1" style="font-size: 6px;"></i>
                                Selesai
                            </span>
                            @if($training->sertifikat)
                            <br>
                            <span class="badge text-bg-primary mt-1">
                                <i class="bi bi-award me-1"></i>
                                Bersertifikat
                            </span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="btn-group" role="group">
                                <a href="{{ route('peserta.trainings.show', $training->id) }}" 
                                   class="btn btn-light btn-sm" title="Lihat Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if($training->sertifikat)
                                <a href="{{ route('peserta.sertifikat.download', $training->sertifikat->id) }}" 
                                   class="btn btn-light btn-sm text-success" title="Download Sertifikat">
                                    <i class="bi bi-download"></i>
                                </a>
                                @endif
                                <button type="button" class="btn btn-light btn-sm text-primary" 
                                        onclick="showReview({{ $training->id }})" title="Review">
                                    <i class="bi bi-star"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="text-center py-5">
                <div class="text-muted">
                    <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                    <p class="h5">Belum ada riwayat pelatihan</p>
                    <p class="small">Anda belum menyelesaikan pelatihan apapun.</p>
                    <a href="{{ route('peserta.trainings.index') }}" class="btn btn-success btn-sm mt-2">
                        <i class="bi bi-journal-bookmark"></i> Ikuti Pelatihan
                    </a>
                </div>
            </div>
            @endif
        </div>
        @if($trainings->hasPages())
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mt-3 px-3 pb-3">
            <p class="text-muted small mb-0">
                Menampilkan {{ $trainings->firstItem() ?? 0 }} sampai {{ $trainings->lastItem() ?? 0 }} 
                dari {{ $trainings->total() ?? 0 }} pelatihan
            </p>
            <nav aria-label="Riwayat pagination">
                {{ $trainings->links() }}
            </nav>
        </div>
        @endif
    </div>
</div>

<!-- Modal Review -->
<div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reviewModalLabel">
                    <i class="bi bi-star text-warning me-2"></i> Review Pelatihan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="reviewForm" action="{{ route('peserta.trainings.review') }}" method="POST">
                    @csrf
                    <input type="hidden" name="training_id" id="reviewTrainingId">
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Rating <span class="text-danger">*</span></label>
                        <div class="d-flex gap-2" id="starRating">
                            <button type="button" class="btn btn-outline-warning" data-value="1" onclick="setRating(1)">⭐</button>
                            <button type="button" class="btn btn-outline-warning" data-value="2" onclick="setRating(2)">⭐⭐</button>
                            <button type="button" class="btn btn-outline-warning" data-value="3" onclick="setRating(3)">⭐⭐⭐</button>
                            <button type="button" class="btn btn-outline-warning" data-value="4" onclick="setRating(4)">⭐⭐⭐⭐</button>
                            <button type="button" class="btn btn-outline-warning" data-value="5" onclick="setRating(5)">⭐⭐⭐⭐⭐</button>
                        </div>
                        <input type="hidden" name="rating" id="ratingValue" value="5">
                        <small class="text-muted">Klik bintang untuk memberikan rating.</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="reviewText" class="form-label fw-semibold">Ulasan</label>
                        <textarea class="form-control" id="reviewText" name="review" rows="4" 
                                  placeholder="Tulis ulasan Anda tentang pelatihan ini..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success" onclick="submitReview()">
                    <i class="bi bi-send me-1"></i> Kirim Review
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // ============================================================
    // SHOW REVIEW MODAL
    // ============================================================
    function showReview(trainingId) {
        document.getElementById('reviewTrainingId').value = trainingId;
        document.getElementById('ratingValue').value = 5;
        document.getElementById('reviewText').value = '';
        
        // Reset star buttons
        document.querySelectorAll('#starRating .btn').forEach(btn => {
            btn.classList.remove('btn-warning');
            btn.classList.add('btn-outline-warning');
        });
        
        // Set default rating 5
        setRating(5);
        
        const modal = new bootstrap.Modal(document.getElementById('reviewModal'));
        modal.show();
    }

    // ============================================================
    // SET RATING
    // ============================================================
    function setRating(value) {
        document.getElementById('ratingValue').value = value;
        
        document.querySelectorAll('#starRating .btn').forEach(btn => {
            const val = parseInt(btn.dataset.value);
            if (val <= value) {
                btn.classList.remove('btn-outline-warning');
                btn.classList.add('btn-warning');
            } else {
                btn.classList.remove('btn-warning');
                btn.classList.add('btn-outline-warning');
            }
        });
    }

    // ============================================================
    // SUBMIT REVIEW
    // ============================================================
    function submitReview() {
        const rating = document.getElementById('ratingValue').value;
        const review = document.getElementById('reviewText').value;
        
        if (!rating) {
            alert('⚠️ Silakan berikan rating terlebih dahulu.');
            return;
        }
        
        document.getElementById('reviewForm').submit();
    }

    // ============================================================
    // AUTO CLOSE ALERTS
    // ============================================================
    setTimeout(function() {
        document.querySelectorAll('.alert').forEach(function(alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
</script>
@endpush
@endsection