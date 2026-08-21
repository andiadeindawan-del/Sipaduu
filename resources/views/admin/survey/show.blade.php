@extends('layouts.admin')

@section('title', 'Detail Survey')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-ui-radios"></i></span>
        <div>
            <p class="eyebrow mb-1">Management</p>
            <h1 class="h3 mb-0">Detail Survey</h1>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <!-- Alert Messages -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-circle-fill me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif


    <!-- Header Info -->
    <div class="panel mb-4">
        <div class="panel-header">
            <div>
                <h5 class="section-title"><i class="bi bi-info-circle"></i> Informasi Survey</h5>
                <p class="text-muted small mb-0">Detail lengkap survey <strong>{{ $survey->judul }}</strong></p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.survey.index') }}" class="btn btn-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
                <a href="{{ route('admin.survey.edit', $survey->id) }}" class="btn btn-warning btn-sm">
                    <i class="bi bi-pencil me-1"></i> Edit
                </a>
            </div>
        </div>
        <div class="panel-body p-4">
            <div class="row g-4">
                <div class="col-12 col-md-6">
                    <div class="detail-item">
                        <label class="text-muted small fw-semibold d-block mb-1">Judul Survey</label>
                        <p class="fw-semibold fs-5 mb-0">{{ $survey->judul }}</p>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="detail-item">
                        <label class="text-muted small fw-semibold d-block mb-1">Pelatihan</label>
                        <p class="mb-0">
                            <i class="bi bi-journal-bookmark text-primary me-1"></i>
                            {{ $survey->training->judul ?? '-' }}
                        </p>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="detail-item">
                        <label class="text-muted small fw-semibold d-block mb-1">Status</label>
                        <p class="mb-0">
                            @php
                                $statusMap = [
                                    'draft' => ['label' => 'Draft', 'class' => 'badge-draft'],
                                    'published' => ['label' => 'Published', 'class' => 'badge-published'],
                                    'closed' => ['label' => 'Closed', 'class' => 'badge-secondary'],
                                ];
                                $status = $statusMap[$survey->status] ?? ['label' => $survey->status, 'class' => 'badge-draft'];
                            @endphp
                            <span class="badge {{ $status['class'] }}">
                                <i class="bi bi-circle-fill me-1" style="font-size: 6px;"></i>
                                {{ $status['label'] }}
                            </span>
                        </p>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="detail-item">
                        <label class="text-muted small fw-semibold d-block mb-1">Total Responden</label>
                        <p class="mb-0">
                            <i class="bi bi-people text-primary me-1"></i>
                            {{ $survey->responses->count() }} responden
                        </p>
                    </div>
                </div>
                @if($survey->deskripsi)
                <div class="col-12">
                    <div class="detail-item">
                        <label class="text-muted small fw-semibold d-block mb-1">Deskripsi</label>
                        <p class="mb-0" style="white-space: pre-line;">{{ $survey->deskripsi }}</p>
                    </div>
                </div>
                @endif
                <div class="col-12 col-md-6">
                    <div class="detail-item">
                        <label class="text-muted small fw-semibold d-block mb-1">Dibuat</label>
                        <p class="mb-0">
                            <i class="bi bi-calendar-plus me-1"></i>
                            {{ $survey->created_at ? $survey->created_at->format('d/m/Y H:i') : '-' }}
                        </p>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="detail-item">
                        <label class="text-muted small fw-semibold d-block mb-1">Diperbarui</label>
                        <p class="mb-0">
                            <i class="bi bi-calendar-check me-1"></i>
                            {{ $survey->updated_at ? $survey->updated_at->format('d/m/Y H:i') : '-' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Pertanyaan -->
    <div class="panel mb-4">
        <div class="panel-header">
            <div>
                <h5 class="section-title"><i class="bi bi-question-circle"></i> Daftar Pertanyaan</h5>
                <p class="text-muted small mb-0">Kelola pertanyaan untuk survey ini</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.survey.questions.create', $survey->id) }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle"></i> Tambah Pertanyaan
                </a>
            </div>
        </div>
        <div class="table-responsive">
            @if($survey->questions->count() > 0)
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Pertanyaan</th>
                        <th>Tipe</th>
                        <th class="text-center" style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($survey->questions as $index => $q)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <div>
                                <p class="fw-semibold mb-0">{{ $q->pertanyaan }}</p>
                                @if($q->deskripsi)
                                <small class="text-muted">{{ $q->deskripsi }}</small>
                                @endif
                            </div>
                        </td>
                        <td>
                            @if($q->tipe == 'rating_5')
                                <span class="badge bg-warning text-dark">
                                    <i class="bi bi-star-fill me-1"></i> Rating 1-5
                                </span>
                            @elseif($q->tipe == 'boolean')
                                <span class="badge bg-primary">
                                    <i class="bi bi-ui-checks me-1"></i> Puas / Tidak Puas
                                </span>
                            @elseif($q->tipe == 'text')
                                <span class="badge bg-info">
                                    <i class="bi bi-justify-left me-1"></i> Esai Singkat
                                </span>
                            @else
                                <span class="badge bg-secondary">{{ $q->tipe }}</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="{{ route('admin.survey.questions.edit', [$survey->id, $q->id]) }}" 
                                   class="badge bg-warning text-dark text-decoration-none p-2" title="Edit">
                                    <i class="bi bi-pencil"></i> 
                                </a>
                                <button type="button" class="badge bg-danger text-white border-0 p-2" 
                                        data-bs-toggle="modal" data-bs-target="#deleteQuestionModal{{ $q->id }}" 
                                        title="Hapus">
                                    <i class="bi bi-trash"></i> 
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
                    <p class="h5">Belum ada pertanyaan</p>
                    <p class="small">Mulai dengan menambahkan pertanyaan pertama</p>
                    <a href="{{ route('admin.survey.questions.create', $survey->id) }}" class="btn btn-primary btn-sm mt-2">
                        <i class="bi bi-plus-circle"></i> Tambah Pertanyaan
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Respon Peserta -->
    <div class="panel">
        <div class="panel-header">
            <div>
                <h5 class="section-title"><i class="bi bi-chat-left-dots"></i> Respon Peserta</h5>
                <p class="text-muted small mb-0">Daftar respon yang telah diterima dari peserta</p>
            </div>
            <div>
                <span class="badge bg-primary">
                    <i class="bi bi-people me-1"></i>
                    {{ $survey->responses->count() }} Respon
                </span>
            </div>
        </div>
        <div class="panel-body p-4">
            @if($survey->responses->count() > 0)
                @foreach($survey->responses as $response)
                <div class="card mb-3 border-0 shadow-sm">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div class="fw-bold">
                            <i class="bi bi-person-circle me-1"></i> 
                            {{ $response->user->name ?? 'User' }}
                            <small class="text-muted fw-normal ms-2">
                                <i class="bi bi-envelope me-1"></i>
                                {{ $response->user->email ?? '-' }}
                            </small>
                        </div>
                        <small class="text-muted">
                            <i class="bi bi-clock me-1"></i>
                            {{ $response->created_at ? $response->created_at->format('d/m/Y H:i') : '-' }}
                        </small>
                    </div>
                    <div class="card-body">
                        @foreach($survey->questions as $q)
                            @php
                                $ans = $response->answers[$q->id] ?? '-';
                            @endphp
                            <div class="mb-3">
                                <div class="fw-semibold text-dark">{{ $q->pertanyaan }}</div>
                                @if($q->tipe == 'rating_5')
                                    <div class="text-warning">
                                        @for($i=1; $i<=5; $i++)
                                            <i class="bi {{ $i <= (int)$ans ? 'bi-star-fill' : 'bi-star' }}"></i>
                                        @endfor
                                        <span class="text-muted ms-2">({{ $ans }}/5)</span>
                                    </div>
                                @elseif($q->tipe == 'boolean')
                                    @if($ans === 'Puas' || $ans == '1')
                                        <span class="badge bg-success">
                                            <i class="bi bi-hand-thumbs-up-fill me-1"></i> Puas
                                        </span>
                                    @elseif($ans === 'Tidak Puas' || $ans == '0')
                                        <span class="badge bg-danger">
                                            <i class="bi bi-hand-thumbs-down-fill me-1"></i> Tidak Puas
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">-</span>
                                    @endif
                                @else
                                    <p class="text-muted mb-0 mt-1">{{ $ans }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            @else
                <div class="text-center py-5">
                    <div class="text-muted">
                        <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                        <p class="h5">Belum ada respon</p>
                        <p class="small">Belum ada peserta yang mengisi survey ini</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- ============================================================
     MODAL DELETE QUESTION
============================================================ -->
@foreach($survey->questions ?? [] as $q)
<div class="modal fade" id="deleteQuestionModal{{ $q->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-triangle text-danger me-2"></i>
                    Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus pertanyaan <strong>{{ $q->pertanyaan }}</strong>?</p>
                <p class="text-muted small">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin.survey.questions.destroy', [$survey->id, $q->id]) }}" method="POST" class="d-inline">
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
@endforeach

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
        background: linear-gradient(135deg, #eaf1fd, #d4e4f7);
        color: #4e9af1;
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

    /* ============================================================
       BREADCRUMB
    ============================================================ */
    .breadcrumb {
        padding: 0;
        margin: 0;
        background: transparent;
    }
    .breadcrumb-item a {
        color: #4e9af1;
        font-weight: 500;
    }
    .breadcrumb-item a:hover {
        color: #3d8ae0;
        text-decoration: underline !important;
    }
    .breadcrumb-item.active {
        color: #1a2236;
        font-weight: 600;
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

    .panel-body {
        background: #fff;
    }

    /* ============================================================
       DETAIL ITEMS
    ============================================================ */
    .detail-item {
        padding: 0.5rem 0;
    }
    .detail-item:not(:last-child) {
        border-bottom: 1px solid #f0f0f0;
    }
    .detail-item label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #8a93a3;
    }
    .detail-item p {
        font-size: 0.95rem;
        color: #1a2236;
    }

    /* ============================================================
       TABLE
    ============================================================ */
    .table th {
        font-weight: 600;
        color: #6c757d;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #f0f0f0;
        padding: 0.75rem 0.75rem;
        background: #fafbfc;
    }
    
    .table td {
        font-size: 0.9rem;
        padding: 0.75rem 0.75rem;
        vertical-align: middle;
    }
    
    .table tbody tr {
        transition: background 0.2s ease;
    }
    .table tbody tr:hover {
        background: #f8fafc;
    }
    
    .table tbody tr:last-child td {
        border-bottom: none;
    }

    /* ============================================================
       BADGE
    ============================================================ */
    .badge {
        font-weight: 500;
        padding: 0.35rem 0.6rem;
        font-size: 0.75rem;
    }
    
    /* Status Badge */
    .badge-draft {
        background: #e2e8f0 !important;
        color: #4a5568 !important;
    }
    .badge-published {
        background: #d4edda !important;
        color: #155724 !important;
    }
    .badge-secondary {
        background: #e2e8f0 !important;
        color: #6c757d !important;
    }
    
    .badge.bg-warning {
        background: #fff3cd !important;
        color: #856404 !important;
    }
    .badge.bg-primary {
        background: #cfe2ff !important;
        color: #084298 !important;
    }
    .badge.bg-info {
        background: #e3f0ff !important;
        color: #0d6efd !important;
    }
    .badge.bg-success {
        background: #d4edda !important;
        color: #155724 !important;
    }
    .badge.bg-danger {
        background: #f8d7da !important;
        color: #721c24 !important;
    }
    .badge.bg-secondary {
        background: #e2e8f0 !important;
        color: #6c757d !important;
    }

    /* Badge Buttons */
    .badge.bg-warning {
        background: #fff3cd !important;
        color: #856404 !important;
    }
    .badge.bg-warning:hover {
        background: #ffedb3 !important;
        transform: scale(1.05);
    }
    .badge.bg-danger {
        background: #f8d7da !important;
        color: #721c24 !important;
    }
    .badge.bg-danger:hover {
        background: #f5c6cb !important;
        transform: scale(1.05);
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
    
    .btn-primary {
        background: #4e9af1;
        border-color: #4e9af1;
        color: #fff;
    }
    .btn-primary:hover {
        background: #3d8ae0;
        border-color: #3d8ae0;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(78, 154, 241, 0.3);
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
    
    .btn-secondary {
        background: #e2e8f0;
        border-color: #e2e8f0;
        color: #4a5568;
    }
    .btn-secondary:hover {
        background: #d5dce6;
        border-color: #d5dce6;
    }
    
    .btn-sm {
        padding: 0.3rem 0.8rem;
        font-size: 0.8rem;
    }

    /* ============================================================
       CARD RESPONSE
    ============================================================ */
    .card {
        border-radius: 0.75rem;
        overflow: hidden;
    }
    .card-header {
        background: #f8fafc;
        border-bottom: 1px solid #f0f0f0;
        padding: 0.75rem 1.25rem;
    }
    .card-body {
        padding: 1.25rem;
    }
    .card-body .mb-3:last-child {
        margin-bottom: 0 !important;
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
    .alert-danger {
        background: #fef2f2;
        color: #991b1b;
    }
    .alert-dismissible .btn-close {
        padding: 1rem;
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
        .panel-header {
            flex-direction: column;
            align-items: flex-start;
        }
        .panel-body {
            padding: 1.25rem !important;
        }
        .d-flex.justify-content-end {
            flex-direction: column;
            gap: 0.5rem !important;
        }
        .d-flex.justify-content-end .btn {
            width: 100%;
        }
        .table-responsive {
            font-size: 0.85rem;
        }
        .table th,
        .table td {
            padding: 0.5rem 0.5rem;
        }
        .card-header {
            flex-direction: column;
            align-items: flex-start !important;
        }
        .d-flex.gap-1.justify-content-end {
            flex-wrap: wrap;
            justify-content: flex-start !important;
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