@extends('layouts.admin')

@section('title', 'Detail Agenda')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-calendar-event"></i></span>
        <div>
            <p class="eyebrow">Manajemen</p>
            <h1 class="h3 mb-0">Detail Agenda</h1>
            <p class="text-muted mb-0">Informasi lengkap agenda {{ $agenda->judul }}.</p>
        </div>
    </div>
    <div class="heading-actions">
        <div class="d-flex gap-2">
            <a href="{{ route('admin.agenda.edit', $agenda->id) }}" class="btn btn-warning btn-sm">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="{{ route('admin.agenda.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
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

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <!-- Main Card -->
            <div class="panel">
                <div class="panel-header">
                    <h5 class="section-title"><i class="bi bi-info-circle"></i> Informasi Agenda</h5>
                    <span class="badge {{ $agenda->status_badge ?? 'text-bg-primary' }}">
                        <i class="bi bi-circle-fill me-1" style="font-size: 6px;"></i>
                        {{ $agenda->status_label ?? ucfirst($agenda->status) }}
                    </span>
                </div>
                <div class="p-4">
                    <div class="row g-4">
                        <!-- Judul -->
                        <div class="col-12">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-text-paragraph fs-4 text-primary"></i>
                                </div>
                                <div>
                                    <label class="text-muted small fw-semibold">Judul</label>
                                    <p class="fw-semibold mb-0 fs-5">{{ $agenda->judul }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        @if($agenda->deskripsi)
                        <div class="col-12">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-file-text fs-4 text-info"></i>
                                </div>
                                <div>
                                    <label class="text-muted small fw-semibold">Deskripsi</label>
                                    <p class="mb-0">{{ $agenda->deskripsi }}</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Pelatihan -->
                        <div class="col-12 col-md-6">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-journal-bookmark fs-4 text-success"></i>
                                </div>
                                <div>
                                    <label class="text-muted small fw-semibold">Pelatihan</label>
                                    @if($agenda->training)
                                    <p class="fw-semibold mb-0">
                                        <a href="{{ route('admin.trainings.show', $agenda->training->id) }}" class="text-decoration-none">
                                            {{ $agenda->training->judul }}
                                        </a>
                                    </p>
                                    @else
                                    <p class="text-muted">-</p>
                                    @endif
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
                                        <span class="badge {{ $agenda->status_badge ?? 'text-bg-primary' }}">
                                            <i class="bi bi-circle-fill me-1" style="font-size: 6px;"></i>
                                            {{ $agenda->status_label ?? ucfirst($agenda->status) }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Tanggal -->
                        <div class="col-12 col-md-6">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-calendar3 fs-4 text-primary"></i>
                                </div>
                                <div>
                                    <label class="text-muted small fw-semibold">Tanggal</label>
                                    <p class="fw-semibold mb-0">
                                        {{ $agenda->tanggal ? $agenda->tanggal->format('d F Y') : '-' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Waktu -->
                        <div class="col-12 col-md-6">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-clock fs-4 text-info"></i>
                                </div>
                                <div>
                                    <label class="text-muted small fw-semibold">Waktu</label>
                                    <p class="fw-semibold mb-0">
                                        {{ $agenda->waktu_mulai ? date('H:i', strtotime($agenda->waktu_mulai)) : '-' }}
                                        {{ $agenda->waktu_selesai ? ' - ' . date('H:i', strtotime($agenda->waktu_selesai)) : '' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Durasi -->
                        <div class="col-12 col-md-6">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-hourglass-split fs-4 text-secondary"></i>
                                </div>
                                <div>
                                    <label class="text-muted small fw-semibold">Durasi</label>
                                    <p class="fw-semibold mb-0">
                                        @php
                                            $durasi = $agenda->duration ?? null;
                                        @endphp
                                        @if($durasi)
                                            @php
                                                $hours = floor($durasi);
                                                $minutes = round(($durasi - $hours) * 60);
                                            @endphp
                                            @if($hours > 0 && $minutes > 0)
                                                {{ $hours }} jam {{ $minutes }} menit
                                            @elseif($hours > 0)
                                                {{ $hours }} jam
                                            @else
                                                {{ $minutes }} menit
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Lokasi -->
                        <div class="col-12 col-md-6">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-geo-alt fs-4 text-danger"></i>
                                </div>
                                <div>
                                    <label class="text-muted small fw-semibold">Lokasi</label>
                                    <p class="fw-semibold mb-0">{{ $agenda->lokasi ?? '-' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Meta Info -->
                        <div class="col-12">
                            <hr class="my-2">
                            <div class="d-flex justify-content-between text-muted small flex-wrap gap-2">
                                <span>
                                    <i class="bi bi-clock me-1"></i> 
                                    Dibuat: {{ $agenda->created_at ? $agenda->created_at->format('d/m/Y H:i') : '-' }}
                                </span>
                                <span>
                                    <i class="bi bi-clock-history me-1"></i> 
                                    Diperbarui: {{ $agenda->updated_at ? $agenda->updated_at->format('d/m/Y H:i') : '-' }}
                                </span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="col-12 mt-2">
                            <hr class="my-2">
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="{{ route('admin.agenda.edit', $agenda->id) }}" class="btn btn-warning">
                                    <i class="bi bi-pencil me-1"></i> Edit Agenda
                                </a>
                                @if($agenda->status == 'cancelled')
                                <form action="{{ route('admin.agenda.update', $agenda->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="judul" value="{{ $agenda->judul }}">
                                    <input type="hidden" name="tanggal" value="{{ $agenda->tanggal ? $agenda->tanggal->format('Y-m-d') : '' }}">
                                    <input type="hidden" name="waktu_mulai" value="{{ $agenda->waktu_mulai ? date('H:i', strtotime($agenda->waktu_mulai)) : '' }}">
                                    <input type="hidden" name="waktu_selesai" value="{{ $agenda->waktu_selesai ? date('H:i', strtotime($agenda->waktu_selesai)) : '' }}">
                                    <input type="hidden" name="status" value="upcoming">
                                    <input type="hidden" name="lokasi" value="{{ $agenda->lokasi }}">
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-arrow-counterclockwise me-1"></i> Aktifkan Kembali
                                    </button>
                                </form>
                                @elseif($agenda->status != 'completed' && $agenda->status != 'cancelled')
                                <form action="{{ route('admin.agenda.update', $agenda->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="judul" value="{{ $agenda->judul }}">
                                    <input type="hidden" name="tanggal" value="{{ $agenda->tanggal ? $agenda->tanggal->format('Y-m-d') : '' }}">
                                    <input type="hidden" name="waktu_mulai" value="{{ $agenda->waktu_mulai ? date('H:i', strtotime($agenda->waktu_mulai)) : '' }}">
                                    <input type="hidden" name="waktu_selesai" value="{{ $agenda->waktu_selesai ? date('H:i', strtotime($agenda->waktu_selesai)) : '' }}">
                                    <input type="hidden" name="status" value="completed">
                                    <input type="hidden" name="lokasi" value="{{ $agenda->lokasi }}">
                                    <button type="submit" class="btn btn-secondary" onclick="return confirm('Yakin ingin menandai agenda ini sebagai selesai?')">
                                        <i class="bi bi-check-circle me-1"></i> Tandai Selesai
                                    </button>
                                </form>
                                @endif
                                <button type="button" class="btn btn-outline-danger" 
                                        data-bs-toggle="modal" data-bs-target="#deleteModal">
                                    <i class="bi bi-trash me-1"></i> Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Data Card -->
            @if($agenda->training)
            <div class="panel mt-3">
                <div class="panel-header">
                    <h5 class="section-title"><i class="bi bi-link-45deg"></i> Data Terkait</h5>
                </div>
                <div class="p-4">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="bi bi-journal-bookmark text-success me-2"></i>
                                        Pelatihan
                                    </h6>
                                    <p class="card-text">
                                        <a href="{{ route('admin.trainings.show', $agenda->training->id) }}" class="text-decoration-none fw-semibold">
                                            {{ $agenda->training->judul }}
                                        </a>
                                    </p>
                                    <p class="text-muted small">
                                        <i class="bi bi-calendar3 me-1"></i>
                                        {{ $agenda->training->tanggal_mulai ? $agenda->training->tanggal_mulai->format('d/m/Y') : '-' }}
                                        {{ $agenda->training->tanggal_selesai ? ' - ' . $agenda->training->tanggal_selesai->format('d/m/Y') : '' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="bi bi-people text-primary me-2"></i>
                                        Peserta Terdaftar
                                    </h6>
                                    <p class="card-text">
                                        <span class="fw-bold">{{ $agenda->training->participants_count ?? $agenda->training->participants()->count() ?? 0 }}</span>
                                        peserta
                                    </p>
                                    <a href="{{ route('admin.trainings.participants', $agenda->training->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> Lihat Peserta
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-triangle text-danger me-2"></i>
                    Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus agenda <strong>{{ $agenda->judul }}</strong>?</p>
                @if($agenda->status == 'ongoing' || $agenda->status == 'completed')
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Agenda ini sudah {{ $agenda->status == 'ongoing' ? 'sedang berlangsung' : 'selesai' }}. Menghapus akan menghapus semua data terkait.
                </div>
                @endif
                @if($agenda->tanggal)
                <div class="alert alert-light">
                    <p class="text-muted small mb-0">Tanggal: {{ $agenda->tanggal->format('d/m/Y') }}</p>
                </div>
                @endif
                <p class="text-muted small">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin.agenda.destroy', $agenda->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-1"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .panel {
        background: #fff;
        border-radius: 0.75rem;
        box-shadow: 0 1px 4px rgba(0,0,0,.06);
        overflow: hidden;
    }
    .panel-header {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: .75rem;
    }
    .section-title {
        display: flex;
        align-items: center;
        gap: .5rem;
        margin: 0;
        font-size: 1rem;
    }
    .section-title i {
        color: var(--primary);
    }
    .bg-light {
        background-color: #f8f9fa !important;
    }
    .card {
        border: none;
        box-shadow: 0 1px 3px rgba(0,0,0,.06);
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
});
</script>
@endpush
@endsection