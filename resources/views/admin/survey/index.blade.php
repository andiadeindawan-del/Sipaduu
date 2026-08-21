@extends('layouts.admin')

@section('title', 'Kelola Survey Kepuasan')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-ui-radios"></i></span>
        <div>
            <p class="eyebrow mb-1">Management</p>
            <h1 class="h3 mb-0">Survey Kepuasan</h1>
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

    <!-- Stats -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-primary">
                <div class="metric-top">
                    <span class="metric-label">Total Survey</span>
                    <span class="metric-icon" style="color: #4e9af1;"><i class="bi bi-ui-radios"></i></span>
                </div>
                <div class="metric-value">{{ $totalSurvey ?? $surveys->total() ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-success">+8%</span>
                    <span>bulan ini</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-success">
                <div class="metric-top">
                    <span class="metric-label">Published</span>
                    <span class="metric-icon" style="color: #28c76f;"><i class="bi bi-check-circle"></i></span>
                </div>
                <div class="metric-value">{{ $publishedSurvey ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-success">Aktif</span>
                    <span>survey</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-warning">
                <div class="metric-top">
                    <span class="metric-label">Draft</span>
                    <span class="metric-icon" style="color: #ff9f43;"><i class="bi bi-pencil"></i></span>
                </div>
                <div class="metric-value">{{ $draftSurvey ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-warning">Perlu review</span>
                    <span>draft</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-secondary">
                <div class="metric-top">
                    <span class="metric-label">Closed</span>
                    <span class="metric-icon" style="color: #8a93a3;"><i class="bi bi-clock"></i></span>
                </div>
                <div class="metric-value">{{ $closedSurvey ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-secondary">Selesai</span>
                    <span>survey</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="panel">
        <div class="panel-header">
            <div>
                <h5 class="section-title"><i class="bi bi-table"></i> Daftar Survey</h5>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <form action="{{ route('admin.survey.index') }}" method="GET" class="d-flex gap-2">
                    <input class="form-control form-control-sm" type="search" name="search" 
                           placeholder="Cari survey..." value="{{ request('search') }}" style="width: 200px;">
                    <select name="training_id" class="form-select form-select-sm" style="min-width: 160px;">
                        <option value="">Semua Pelatihan</option>
                        @foreach($trainings ?? [] as $t)
                            <option value="{{ $t->id }}" {{ request('training_id') == $t->id ? 'selected' : '' }}>
                                {{ Str::limit($t->judul, 35) }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
                
                @if(request('search') || request('training_id'))
                <a href="{{ route('admin.survey.index') }}" class="btn btn-outline-secondary btn-sm" title="Reset Filter">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                </a>
                @endif
                
                {{-- Tombol Tambah --}}
                <a href="{{ route('admin.survey.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle"></i> Tambah
                </a>
            </div>
        </div>

        @if(request('search') || request('training_id'))
        <div class="p-2 px-3 bg-light border-top">
            <small class="text-muted">
                <i class="bi bi-filter-circle me-1"></i>
                Filter aktif: 
                @if(request('search'))
                    <span class="badge text-bg-primary">Cari: {{ request('search') }}</span>
                @endif
                @if(request('training_id'))
                    @php
                        $trainingName = $trainings->where('id', request('training_id'))->first();
                    @endphp
                    <span class="badge text-bg-primary">Pelatihan: {{ $trainingName ? Str::limit($trainingName->judul, 30) : '' }}</span>
                @endif
                <a href="{{ route('admin.survey.index') }}" class="text-danger ms-2">
                    <i class="bi bi-x-circle"></i> Hapus filter
                </a>
            </small>
        </div>
        @endif

        <div class="table-responsive">
            @if($surveys->count() > 0)
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Judul Survey</th>
                        <th>Pelatihan</th>
                        <th>Pertanyaan</th>
                        <th>Respon</th>
                        <th>Status</th>
                        <th class="text-center" style="width: 200px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($surveys as $index => $survey)
                    <tr>
                        <td>{{ $surveys->firstItem() + $index }}</td>
                        <td>
                            <div>
                                <p class="fw-semibold mb-0">{{ $survey->judul }}</p>
                                @if($survey->deskripsi)
                                @endif
                            </div>
                        </td>
                        <td>
                            @if($survey->training)
                            <span class="text-muted">{{ Str::limit($survey->training->judul, 40) }}</span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="badge bg-info">
                                <i class="bi bi-list-check me-1"></i>
                                {{ $survey->questions_count ?? $survey->questions->count() ?? 0 }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-success">
                                <i class="bi bi-people me-1"></i>
                                {{ $survey->responses_count ?? $survey->responses->count() ?? 0 }}
                            </span>
                        </td>
                        <td>
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
                        </td>
                        <td class="text-center">
                            <div class="d-flex gap-1 justify-content-center">
                                {{-- Link Show/Detail --}}
                                <a href="{{ route('admin.survey.show', $survey->id) }}" class="badge bg-info text-white border-0 p-2 text-decoration-none" title="Detail & Respon">
                                    <i class="bi bi-eye"></i> 
                                </a>
                                
                                {{-- Link Kelola Pertanyaan --}}
                                <a href="{{ route('admin.survey.questions.index', $survey->id) }}" class="badge bg-success text-white border-0 p-2 text-decoration-none" title="Kelola Pertanyaan">
                                    <i class="bi bi-list-check"></i> 
                                </a>
                                
                                {{-- Link Edit --}}
                                <a href="{{ route('admin.survey.edit', $survey->id) }}" class="badge bg-warning text-dark border-0 p-2 text-decoration-none" title="Edit">
                                    <i class="bi bi-pencil"></i> 
                                </a>
                                
                                {{-- Tombol Delete dengan form --}}
                                <form action="{{ route('admin.survey.destroy', $survey->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus survey {{ $survey->judul }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="badge bg-danger text-white border-0 p-2" title="Hapus">
                                        <i class="bi bi-trash"></i> 
                                    </button>
                                </form>
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
                    <p class="h5">
                        @if(request('search') || request('training_id'))
                            Tidak ada survey yang sesuai dengan filter
                        @else
                            Belum ada survey
                        @endif
                    </p>
                    <p class="small">
                        @if(request('search') || request('training_id'))
                            Coba ubah kata kunci pencarian atau reset filter
                        @else
                            Mulai dengan menambahkan survey baru
                        @endif
                    </p>
                    @if(request('search') || request('training_id'))
                    <a href="{{ route('admin.survey.index') }}" class="btn btn-outline-secondary btn-sm mt-2">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset Filter
                    </a>
                    @endif
                    <a href="{{ route('admin.survey.create') }}" class="btn btn-primary btn-sm mt-2">
                        <i class="bi bi-plus-circle"></i> Tambah Survey
                    </a>
                </div>
            </div>
            @endif
        </div>
        
        @if($surveys->hasPages())
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mt-3 px-3 pb-3">
            <p class="text-muted small mb-0">
                Menampilkan {{ $surveys->firstItem() ?? 0 }} sampai {{ $surveys->lastItem() ?? 0 }} 
                dari {{ $surveys->total() ?? 0 }} survey
            </p>
            <nav aria-label="Survey pagination">
                {{ $surveys->appends(request()->query())->links() }}
            </nav>
        </div>
        @endif
    </div>
</div>

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
        background: linear-gradient(135deg, #f0e6ff, #dcc4ff);
        color: #6f42c1;
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
       METRIC CARDS
    ============================================================ */
    .metric-card {
        background: #fff;
        border-radius: 0.75rem;
        padding: 1.1rem 1.25rem;
        box-shadow: 0 1px 4px rgba(0,0,0,.06);
        border-left: 4px solid transparent;
        height: 100%;
        transition: all 0.3s ease;
    }
    .metric-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    }
    .metric-primary { border-left-color: #4e9af1; }
    .metric-success { border-left-color: #28c76f; }
    .metric-warning { border-left-color: #ff9f43; }
    .metric-secondary { border-left-color: #8a93a3; }
    
    .metric-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: .4rem;
    }
    .metric-label {
        font-size: .75rem;
        color: #8a93a3;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .03em;
    }
    .metric-icon {
        font-size: 1.3rem;
    }
    .metric-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1a2236;
    }
    .metric-meta {
        font-size: .75rem;
        color: #8a93a3;
        display: flex;
        gap: .35rem;
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
        cursor: pointer;
        transition: all 0.2s ease;
        border: none;
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
    
    /* Badge Buttons */
    .badge.bg-info {
        background: #e3f0ff !important;
        color: #0d6efd !important;
    }
    .badge.bg-info:hover {
        background: #d0e4ff !important;
        transform: scale(1.05);
    }
    
    .badge.bg-success {
        background: #d4edda !important;
        color: #155724 !important;
    }
    .badge.bg-success:hover {
        background: #c3e6cb !important;
        transform: scale(1.05);
    }
    
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
       FORM
    ============================================================ */
    .form-select-sm,
    .form-control-sm {
        font-size: 0.8rem;
        padding: 0.3rem 0.8rem;
        border-radius: 0.5rem;
        border-color: #e2e8f0;
    }
    .form-select-sm:focus,
    .form-control-sm:focus {
        border-color: #4e9af1;
        box-shadow: 0 0 0 3px rgba(78, 154, 241, 0.15);
    }
    
    .input-group-sm .input-group-text {
        background: #f8fafc;
        border-color: #e2e8f0;
        color: #8a93a3;
        font-size: 0.8rem;
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
        .panel-header form {
            width: 100%;
            flex-wrap: wrap;
        }
        .panel-header form input,
        .panel-header form select {
            flex: 1;
            min-width: 120px;
        }
        .metric-value {
            font-size: 1.2rem;
        }
        .table-responsive {
            font-size: 0.85rem;
        }
        .table th,
        .table td {
            padding: 0.5rem 0.5rem;
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

    // Search with Enter key
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                this.closest('form').submit();
            }
        });
    }
});
</script>
@endpush
@endsection