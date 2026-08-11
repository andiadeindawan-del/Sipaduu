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
                    <span class="metric-icon"><i class="bi bi-ui-radios"></i></span>
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
                    <span class="metric-icon"><i class="bi bi-check-circle"></i></span>
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
                    <span class="metric-icon"><i class="bi bi-pencil"></i></span>
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
                    <span class="metric-icon"><i class="bi bi-clock"></i></span>
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
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="bi bi-plus-circle"></i> Tambah
                </button>
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
                        <th class="text-end" style="width: 200px;">Aksi</th>
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
                        <td class="text-end">
                            <div class="d-flex gap-1 justify-content-end">
                                {{-- Tombol Show/Detail --}}
                                <button type="button" class="badge bg-info text-white border-0 p-2" 
                                        data-bs-toggle="modal" data-bs-target="#showModal{{ $survey->id }}" 
                                        title="Detail & Respon">
                                    <i class="bi bi-eye"></i> 
                                </button>
                                
                                {{-- Tombol Kelola Pertanyaan --}}
                                <button type="button" class="badge bg-success text-white border-0 p-2" 
                                        data-bs-toggle="modal" data-bs-target="#questionModal{{ $survey->id }}" 
                                        title="Kelola Pertanyaan">
                                    <i class="bi bi-list-check"></i> 
                                </button>
                                
                                {{-- Tombol Edit --}}
                                <button type="button" class="badge bg-warning text-dark border-0 p-2" 
                                        data-bs-toggle="modal" data-bs-target="#editModal{{ $survey->id }}" 
                                        title="Edit">
                                    <i class="bi bi-pencil"></i> 
                                </button>
                                
                                {{-- Tombol Delete --}}
                                <button type="button" class="badge bg-danger text-white border-0 p-2" 
                                        data-bs-toggle="modal" data-bs-target="#deleteModal{{ $survey->id }}" 
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
                    <button type="button" class="btn btn-primary btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#createModal">
                        <i class="bi bi-plus-circle"></i> Tambah Survey
                    </button>
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

<!-- ============================================================
     MODAL CREATE
============================================================ -->
<div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.survey.store') }}" method="POST" id="createForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-plus-circle text-primary me-2"></i>Tambah Survey
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Pelatihan <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-journal-bookmark"></i></span>
                                <select name="training_id" class="form-select @error('training_id') is-invalid @enderror" required>
                                    <option value="">Pilih Pelatihan</option>
                                    @foreach($trainings ?? [] as $training)
                                        <option value="{{ $training->id }}" {{ old('training_id') == $training->id ? 'selected' : '' }}>
                                            {{ $training->judul }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('training_id')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label fw-semibold">Judul <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-text-paragraph"></i></span>
                                <input type="text" name="judul" class="form-control" 
                                       value="{{ old('judul') }}" placeholder="Masukkan judul survey" required>
                            </div>
                            @error('judul')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Deskripsi</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-file-text"></i></span>
                                <textarea name="deskripsi" class="form-control" rows="2" 
                                          placeholder="Deskripsi survey (opsional)">{{ old('deskripsi') }}</textarea>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-toggle-on"></i></span>
                                <select name="status" class="form-select" required>
                                    <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>📝 Draft</option>
                                    <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>✅ Published</option>
                                    <option value="closed" {{ old('status') == 'closed' ? 'selected' : '' }}>📦 Closed</option>
                                </select>
                            </div>
                            <small class="text-muted">
                                <i class="bi bi-info-circle me-1"></i>
                                <strong>Draft:</strong> Belum tampil &bull;
                                <strong>Published:</strong> Tampil &bull;
                                <strong>Closed:</strong> Ditutup
                            </small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================================
     MODAL SHOW / DETAIL
============================================================ -->
@foreach($surveys ?? [] as $survey)
<div class="modal fade" id="showModal{{ $survey->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-eye text-info me-2"></i>Detail Survey
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Informasi Survey -->
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <h6 class="border-bottom pb-2"><i class="bi bi-info-circle me-2"></i>Informasi Survey</h6>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="text-muted small fw-semibold d-block">Judul</label>
                        <p class="fw-semibold">{{ $survey->judul }}</p>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="text-muted small fw-semibold d-block">Pelatihan</label>
                        <p>{{ $survey->training->judul ?? '-' }}</p>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="text-muted small fw-semibold d-block">Status</label>
                        <p>
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
                    <div class="col-12 col-md-6">
                        <label class="text-muted small fw-semibold d-block">Total Responden</label>
                        <p><i class="bi bi-people text-primary me-1"></i> {{ $survey->responses->count() }} responden</p>
                    </div>
                    @if($survey->deskripsi)
                    <div class="col-12">
                        <label class="text-muted small fw-semibold d-block">Deskripsi</label>
                        <p>{{ $survey->deskripsi }}</p>
                    </div>
                    @endif
                </div>

                <!-- Daftar Pertanyaan -->
                <div class="row g-3">
                    <div class="col-12">
                        <h6 class="border-bottom pb-2">
                            <i class="bi bi-question-circle me-2"></i>Daftar Pertanyaan
                            <span class="badge bg-primary ms-2">{{ $survey->questions->count() }}</span>
                        </h6>
                    </div>
                    <div class="col-12">
                        @if($survey->questions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 50px;">#</th>
                                        <th>Pertanyaan</th>
                                        <th>Tipe</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($survey->questions as $idx => $q)
                                    <tr>
                                        <td class="text-center">{{ $idx + 1 }}</td>
                                        <td>{{ $q->pertanyaan }}</td>
                                        <td>
                                            @if($q->tipe == 'rating_5')
                                                <span class="badge bg-warning text-dark"><i class="bi bi-star-fill me-1"></i> Rating 1-5</span>
                                            @elseif($q->tipe == 'boolean')
                                                <span class="badge bg-primary"><i class="bi bi-ui-checks me-1"></i> Puas/Tidak Puas</span>
                                            @elseif($q->tipe == 'text')
                                                <span class="badge bg-info"><i class="bi bi-justify-left me-1"></i> Esai</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $q->tipe }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-3 text-muted">
                            <i class="bi bi-inbox d-block mb-2"></i>
                            Belum ada pertanyaan
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Respon Peserta -->
                <div class="row g-3 mt-3">
                    <div class="col-12">
                        <h6 class="border-bottom pb-2">
                            <i class="bi bi-chat-left-dots me-2"></i>Respon Peserta
                            <span class="badge bg-success ms-2">{{ $survey->responses->count() }}</span>
                        </h6>
                    </div>
                    <div class="col-12">
                        @if($survey->responses->count() > 0)
                            @foreach($survey->responses as $response)
                            <div class="card mb-2 border-0 shadow-sm">
                                <div class="card-header bg-light d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <div class="fw-bold">
                                        <i class="bi bi-person-circle me-1"></i> 
                                        {{ $response->user->name ?? 'User' }}
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
                                        <div class="mb-2">
                                            <div class="fw-semibold text-dark small">{{ $q->pertanyaan }}</div>
                                            @if($q->tipe == 'rating_5')
                                                <div class="text-warning">
                                                    @for($i=1; $i<=5; $i++)
                                                        <i class="bi {{ $i <= (int)$ans ? 'bi-star-fill' : 'bi-star' }}"></i>
                                                    @endfor
                                                    <span class="text-muted ms-1 small">({{ $ans }}/5)</span>
                                                </div>
                                            @elseif($q->tipe == 'boolean')
                                                @if($ans === 'Puas' || $ans == '1')
                                                    <span class="badge bg-success"><i class="bi bi-hand-thumbs-up-fill me-1"></i> Puas</span>
                                                @elseif($ans === 'Tidak Puas' || $ans == '0')
                                                    <span class="badge bg-danger"><i class="bi bi-hand-thumbs-down-fill me-1"></i> Tidak Puas</span>
                                                @else
                                                    <span class="badge bg-secondary">-</span>
                                                @endif
                                            @else
                                                <p class="text-muted mb-0 mt-1 small">{{ $ans }}</p>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        @else
                        <div class="text-center py-3 text-muted">
                            <i class="bi bi-inbox d-block mb-2"></i>
                            Belum ada respon
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $survey->id }}" data-bs-dismiss="modal">
                    <i class="bi bi-pencil me-1"></i> Edit
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================
     MODAL EDIT
============================================================ -->
<div class="modal fade" id="editModal{{ $survey->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.survey.update', $survey->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-pencil-square text-warning me-2"></i>Edit Survey
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Pelatihan <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-journal-bookmark"></i></span>
                                <select name="training_id" class="form-select" required>
                                    <option value="">Pilih Pelatihan</option>
                                    @foreach($trainings ?? [] as $training)
                                        <option value="{{ $training->id }}" {{ $survey->training_id == $training->id ? 'selected' : '' }}>
                                            {{ $training->judul }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label fw-semibold">Judul <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-text-paragraph"></i></span>
                                <input type="text" name="judul" class="form-control" value="{{ $survey->judul }}" required>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Deskripsi</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-file-text"></i></span>
                                <textarea name="deskripsi" class="form-control" rows="2">{{ $survey->deskripsi }}</textarea>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-toggle-on"></i></span>
                                <select name="status" class="form-select" required>
                                    <option value="draft" {{ $survey->status == 'draft' ? 'selected' : '' }}>📝 Draft</option>
                                    <option value="published" {{ $survey->status == 'published' ? 'selected' : '' }}>✅ Published</option>
                                    <option value="closed" {{ $survey->status == 'closed' ? 'selected' : '' }}>📦 Closed</option>
                                </select>
                            </div>
                            <small class="text-muted">
                                <i class="bi bi-info-circle me-1"></i>
                                Total pertanyaan: {{ $survey->questions->count() }} &bull;
                                Total respon: {{ $survey->responses->count() }}
                            </small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-save me-1"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================================
     MODAL KELOLA PERTANYAAN
============================================================ -->
<div class="modal fade" id="questionModal{{ $survey->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-list-check text-primary me-2"></i>Kelola Pertanyaan
                    <span class="badge bg-primary ms-2">{{ $survey->questions->count() }}</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Form Tambah Pertanyaan -->
                <div class="card mb-3 border-0 bg-light">
                    <div class="card-body">
                        <h6 class="mb-3"><i class="bi bi-plus-circle me-2"></i>Tambah Pertanyaan Baru</h6>
                        <form action="{{ route('admin.survey.questions.store', $survey->id) }}" method="POST">
                            @csrf
                            <div class="row g-2">
                                <div class="col-12 col-md-7">
                                    <input type="text" name="pertanyaan" class="form-control form-control-sm" 
                                           placeholder="Masukkan pertanyaan..." required>
                                </div>
                                <div class="col-12 col-md-3">
                                    <select name="tipe" class="form-select form-select-sm" required>
                                        <option value="rating_5">⭐ Rating 1-5</option>
                                        <option value="boolean">✅ Puas / Tidak Puas</option>
                                        <option value="text">📝 Esai Singkat</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-2">
                                    <button type="submit" class="btn btn-primary btn-sm w-100">
                                        <i class="bi bi-plus"></i> Tambah
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Daftar Pertanyaan -->
                @if($survey->questions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>Pertanyaan</th>
                                <th>Tipe</th>
                                <th style="width: 100px;" class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($survey->questions as $idx => $q)
                            <tr>
                                <td class="text-center">{{ $idx + 1 }}</td>
                                <td>{{ $q->pertanyaan }}</td>
                                <td>
                                    @if($q->tipe == 'rating_5')
                                        <span class="badge bg-warning text-dark"><i class="bi bi-star-fill me-1"></i> Rating</span>
                                    @elseif($q->tipe == 'boolean')
                                        <span class="badge bg-primary"><i class="bi bi-ui-checks me-1"></i> Boolean</span>
                                    @elseif($q->tipe == 'text')
                                        <span class="badge bg-info"><i class="bi bi-justify-left me-1"></i> Esai</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $q->tipe }}</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="d-flex gap-1 justify-content-end">
                                        <button type="button" class="badge bg-warning text-dark border-0 p-2" 
                                                data-bs-toggle="modal" data-bs-target="#editQuestionModal{{ $q->id }}" 
                                                title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </button>
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
                </div>
                @else
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                    <p>Belum ada pertanyaan</p>
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================
     MODAL EDIT QUESTION
============================================================ -->
@foreach($survey->questions ?? [] as $q)
<div class="modal fade" id="editQuestionModal{{ $q->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.survey.questions.update', [$survey->id, $q->id]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-pencil-square text-warning me-2"></i>Edit Pertanyaan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pertanyaan <span class="text-danger">*</span></label>
                        <input type="text" name="pertanyaan" class="form-control" value="{{ $q->pertanyaan }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tipe <span class="text-danger">*</span></label>
                        <select name="tipe" class="form-select" required>
                            <option value="rating_5" {{ $q->tipe == 'rating_5' ? 'selected' : '' }}>⭐ Rating 1-5</option>
                            <option value="boolean" {{ $q->tipe == 'boolean' ? 'selected' : '' }}>✅ Puas / Tidak Puas</option>
                            <option value="text" {{ $q->tipe == 'text' ? 'selected' : '' }}>📝 Esai Singkat</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-save me-1"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================================
     MODAL DELETE QUESTION
============================================================ -->
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

<!-- ============================================================
     MODAL DELETE SURVEY
============================================================ -->
<div class="modal fade" id="deleteModal{{ $survey->id }}" tabindex="-1" aria-hidden="true">
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
                <p>Apakah Anda yakin ingin menghapus survey <strong>{{ $survey->judul }}</strong>?</p>
                @if($survey->questions->count() > 0)
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Survey ini memiliki {{ $survey->questions->count() }} pertanyaan yang akan ikut terhapus.
                </div>
                @endif
                @if($survey->responses->count() > 0)
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Survey ini telah menerima {{ $survey->responses->count() }} respon yang akan ikut terhapus.
                </div>
                @endif
                <p class="text-muted small">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin.survey.destroy', $survey->id) }}" method="POST" class="d-inline">
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
        color: #c3cad6;
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
    
    .badge.bg-primary {
        background: #cfe2ff !important;
        color: #084298 !important;
    }
    .badge.bg-primary:hover {
        background: #bad3f5 !important;
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

    .badge.bg-success {
        background: #d4edda !important;
        color: #155724 !important;
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

    .input-group-text {
        background: #f8fafc;
        border-color: #e2e8f0;
        color: #8a93a3;
    }
    
    .form-control, .form-select {
        border-color: #e2e8f0;
        border-radius: 0.5rem;
        padding: 0.5rem 0.75rem;
        font-size: 0.9rem;
        transition: all 0.2s ease;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #4e9af1;
        box-shadow: 0 0 0 3px rgba(78, 154, 241, 0.15);
    }
    
    .input-group .form-control, 
    .input-group .form-select {
        border-radius: 0 0.5rem 0.5rem 0;
    }
    
    .input-group .input-group-text:first-child {
        border-radius: 0.5rem 0 0 0.5rem;
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
    
    .btn-danger {
        background: #f56565;
        border-color: #f56565;
        color: #fff;
    }
    .btn-danger:hover {
        background: #e53e3e;
        border-color: #e53e3e;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(245, 101, 101, 0.3);
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
    .alert-warning {
        background: #fffbeb;
        color: #92400e;
    }
    .alert-dismissible .btn-close {
        padding: 1rem;
    }

    /* ============================================================
       CARD
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
    .card-body .mb-2:last-child {
        margin-bottom: 0 !important;
    }

    /* ============================================================
       MODAL
    ============================================================ */
    .modal-content {
        border-radius: 0.75rem;
        border: none;
        box-shadow: 0 20px 60px rgba(0,0,0,0.15);
    }
    .modal-header {
        border-bottom: 1px solid #f0f0f0;
        padding: 1rem 1.25rem;
        background: #fafbfc;
    }
    .modal-footer {
        border-top: 1px solid #f0f0f0;
        padding: 1rem 1.25rem;
        background: #fafbfc;
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
        .modal-dialog {
            margin: 0.5rem;
        }
        .modal-body {
            padding: 1rem;
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

    // Focus on first input when modal opens
    document.querySelectorAll('.modal').forEach(function(modal) {
        modal.addEventListener('shown.bs.modal', function() {
            const firstInput = this.querySelector('input:not([type="hidden"]), select, textarea');
            if (firstInput) {
                setTimeout(function() {
                    firstInput.focus();
                }, 100);
            }
        });
    });
});
</script>
@endpush
@endsection