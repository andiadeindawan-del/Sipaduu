@extends('layouts.admin')

@section('title', 'Manajemen Pertanyaan Quiz')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-list-ol"></i></span>
        <div>
            <p class="eyebrow">Manajemen</p>
            <h1 class="h3 mb-0">Pertanyaan Quiz</h1>
            <p class="text-muted mb-0">
                @if(isset($quiz))
                    Quiz: <strong>{{ $quiz->judul }}</strong>
                @else
                    Kelola semua pertanyaan quiz.
                @endif
            </p>
        </div>
    </div>
    <div class="heading-actions d-flex gap-2">
        <a href="{{ route('admin.quiz.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
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

    <!-- Stats -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-primary">
                <div class="metric-top">
                    <span class="metric-label">Total Pertanyaan</span>
                    <span class="metric-icon"><i class="bi bi-list-ol"></i></span>
                </div>
                <div class="metric-value">{{ $totalQuestions ?? $questions->total() ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-primary">Semua</span>
                    <span>pertanyaan</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-success">
                <div class="metric-top">
                    <span class="metric-label">Pilihan Ganda</span>
                    <span class="metric-icon"><i class="bi bi-check-circle"></i></span>
                </div>
                <div class="metric-value">{{ $multipleChoiceCount ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-success">Multiple</span>
                    <span>choice</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-warning">
                <div class="metric-top">
                    <span class="metric-label">Essay</span>
                    <span class="metric-icon"><i class="bi bi-pencil"></i></span>
                </div>
                <div class="metric-value">{{ $essayCount ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-warning">Essay</span>
                    <span>soal</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-info">
                <div class="metric-top">
                    <span class="metric-label">Total Nilai</span>
                    <span class="metric-icon"><i class="bi bi-star"></i></span>
                </div>
                <div class="metric-value">{{ $totalScore ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-info">Keseluruhan</span>
                    <span>nilai</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="panel mb-3">
        <div class="panel-header">
            <div>
                <h5 class="section-title"><i class="bi bi-funnel"></i> Filter & Pencarian</h5>
            </div>
            <form action="{{ route('admin.quiz.questions.index', $quiz->id ?? '') }}" method="GET" class="d-flex gap-2 flex-wrap align-items-center">
                <div class="input-group input-group-sm" style="width: 250px;">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control" name="search" placeholder="Cari pertanyaan..." value="{{ request('search') }}">
                </div>
                <select class="form-select form-select-sm" name="type" style="width: 150px;">
                    <option value="">Semua Tipe</option>
                    <option value="multiple_choice" {{ request('type') == 'multiple_choice' ? 'selected' : '' }}>Pilihan Ganda</option>
                    <option value="essay" {{ request('type') == 'essay' ? 'selected' : '' }}>Essay</option>
                </select>
                <button type="submit" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-search"></i> Cari
                </button>
                <a href="{{ route('admin.quiz.questions.index', $quiz->id ?? '') }}" class="btn btn-outline-secondary btn-sm" title="Reset Filter">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                </a>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="bi bi-plus-circle"></i> Tambah
                </button>
            </form>
        </div>
        @if(request('search') || request('type'))
        <div class="p-2 px-3 bg-light border-top">
            <small class="text-muted">
                <i class="bi bi-filter-circle me-1"></i>
                Filter aktif: 
                @if(request('search'))
                    <span class="badge text-bg-primary">Pencarian: "{{ request('search') }}"</span>
                @endif
                @if(request('type'))
                    <span class="badge text-bg-primary">Tipe: {{ request('type') == 'multiple_choice' ? 'Pilihan Ganda' : 'Essay' }}</span>
                @endif
                <a href="{{ route('admin.quiz.questions.index', $quiz->id ?? '') }}" class="text-danger ms-2">
                    <i class="bi bi-x-circle"></i> Hapus semua filter
                </a>
            </small>
        </div>
        @endif
    </div>

    <!-- Table -->
    <div class="panel">
        <div class="panel-header">
            <div>
                <h5 class="section-title"><i class="bi bi-table"></i> Daftar Pertanyaan</h5>
                <p class="text-muted small mb-0">
                    @if(isset($quiz))
                        Quiz: <strong>{{ $quiz->judul }}</strong>
                    @endif
                </p>
            </div>
            <div class="d-flex gap-2 flex-wrap align-items-center">
                <button type="button" class="btn btn-danger btn-sm d-none" id="bulkDeleteBtn">
                    <i class="bi bi-trash"></i> Hapus Terpilih
                </button>
            </div>
        </div>
        <div class="table-responsive">
            @if($questions->count() > 0)
            <table class="table align-middle mb-0" id="questionsTable">
                <thead>
                    <tr>
                        <th style="width: 40px;">
                            <input type="checkbox" id="selectAll" class="form-check-input">
                        </th>
                        <th style="width: 50px;">#</th>
                        <th>Pertanyaan</th>
                        <th>Tipe</th>
                        <th>Nilai</th>
                        <th>Urutan</th>
                        <th class="text-end" style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($questions as $index => $question)
                    <tr>
                        <td>
                            <input type="checkbox" class="form-check-input question-checkbox" value="{{ $question->id }}">
                        </td>
                        <td>{{ $questions->firstItem() + $index }}</td>
                        <td>
                            <div>
                                <!-- PERBAIKI: Hanya gunakan question, hapus pertanyaan -->
                                <p class="fw-semibold mb-0">{{ Str::limit($question->question, 60) }}</p>
                                @if(isset($question->type) && $question->type == 'multiple_choice')
                                <div class="text-muted small">
                                    @php
                                        $options = is_array($question->options) ? $question->options : json_decode($question->options, true);
                                    @endphp
                                    @if(is_array($options) && count($options) > 0)
                                        @foreach($options as $key => $option)
                                        <span class="badge text-bg-light me-1">
                                            {{ chr(65 + $key) }}: {{ Str::limit($option, 20) }}
                                            @if($question->correct_answer == chr(65 + $key))
                                            <i class="bi bi-check-circle text-success"></i>
                                            @endif
                                        </span>
                                        @endforeach
                                        <span class="badge text-bg-success">Jawaban: {{ $question->correct_answer }}</span>
                                    @endif
                                </div>
                                @endif
                            </div>
                        </td>
                        <td>
                            @php
                                $typeMap = [
                                    'multiple_choice' => ['label' => 'Pilihan Ganda', 'class' => 'text-bg-primary'],
                                    'essay' => ['label' => 'Essay', 'class' => 'text-bg-warning'],
                                    'pilihan' => ['label' => 'Pilihan Ganda', 'class' => 'text-bg-primary'],
                                    'pilihan_ganda' => ['label' => 'Pilihan Ganda', 'class' => 'text-bg-primary'],
                                ];
                                $type = $typeMap[$question->type] ?? ['label' => $question->type ?? 'Pilihan Ganda', 'class' => 'text-bg-secondary'];
                            @endphp
                            <span class="badge {{ $type['class'] }}">
                                {{ $type['label'] }}
                            </span>
                        </td>
                        <td>
                            <span class="badge text-bg-secondary">
                                <i class="bi bi-star me-1"></i>
                                {{ $question->points ?? $question->score ?? 1 }}
                            </span>
                        </td>
                        <td>
                            <span class="badge text-bg-light">{{ $question->order ?? $loop->iteration }}</span>
                        </td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-info" 
                                        data-bs-toggle="modal" data-bs-target="#showModal{{ $question->id }}" 
                                        title="Lihat Detail">
                                    <i class="bi bi-eye"></i> LIhat
                                </button>
                                <button type="button" class="btn btn-warning" 
                                        data-bs-toggle="modal" data-bs-target="#editModal{{ $question->id }}" 
                                        title="Edit">
                                    <i class="bi bi-pencil"></i> Edit
                                </button>
                                <button type="button" class="btn btn-danger" 
                                        data-bs-toggle="modal" data-bs-target="#deleteModal{{ $question->id }}" 
                                        title="Hapus">
                                    <i class="bi bi-trash"></i> Hapus
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
                    <p class="small">
                        @if(isset($quiz))
                            Quiz <strong>{{ $quiz->judul }}</strong> belum memiliki pertanyaan.
                        @else
                            Mulai dengan menambahkan pertanyaan baru.
                        @endif
                    </p>
                    <button type="button" class="btn btn-primary btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#createModal">
                        <i class="bi bi-plus-circle"></i> Tambah Pertanyaan
                    </button>
                </div>
            </div>
            @endif
        </div>
        @if($questions->hasPages())
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mt-3 px-3 pb-3">
            <p class="text-muted small mb-0">
                Menampilkan {{ $questions->firstItem() ?? 0 }} sampai {{ $questions->lastItem() ?? 0 }} 
                dari {{ $questions->total() ?? 0 }} pertanyaan
            </p>
            <nav aria-label="Pertanyaan pagination">
                {{ $questions->links() }}
            </nav>
        </div>
        @endif
    </div>
</div>

<!-- ============================================================
     CREATE MODAL
============================================================ -->
<div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.quiz.questions.store', $quiz->id ?? '') }}" method="POST" id="createForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-plus-circle text-primary me-2"></i>Tambah Pertanyaan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <p class="text-muted small">Untuk Quiz: <strong>{{ $quiz->judul ?? 'Semua Quiz' }}</strong></p>
                        </div>
                        <!-- Tipe Pertanyaan -->
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Tipe Pertanyaan <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-tag"></i></span>
                                <select class="form-select" name="type" id="createType" required>
                                    <option value="multiple_choice">Pilihan Ganda</option>
                                    <option value="essay">Essay</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Nilai <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-star"></i></span>
                                <input type="number" class="form-control" name="points" value="1" min="1" max="100" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Pertanyaan <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-text-paragraph"></i></span>
                                <textarea class="form-control" name="question" rows="3" placeholder="Masukkan pertanyaan..." required></textarea>
                            </div>
                        </div>
                        
                        <!-- Multiple Choice Options -->
                        <div class="col-12" id="createOptionsWrapper">
                            <hr>
                            <label class="form-label fw-semibold">Pilihan Jawaban <span class="text-danger">*</span></label>
                            <div id="createOptionsContainer">
                                <div class="row g-2 mb-2">
                                    <div class="col-10">
                                        <div class="input-group">
                                            <span class="input-group-text">A.</span>
                                            <input type="text" class="form-control" name="options[]" placeholder="Masukkan pilihan A" required>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <button type="button" class="btn btn-outline-success btn-sm" onclick="addCreateOption()">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="row g-2 mb-2">
                                    <div class="col-10">
                                        <div class="input-group">
                                            <span class="input-group-text">B.</span>
                                            <input type="text" class="form-control" name="options[]" placeholder="Masukkan pilihan B" required>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeCreateOption(this)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <small class="text-muted">Minimal 2 pilihan jawaban.</small>
                        </div>
                        
                        <!-- Jawaban Benar -->
                        <div class="col-12" id="createCorrectWrapper">
                            <label class="form-label fw-semibold">Jawaban Benar <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-check-circle"></i></span>
                                <select class="form-select" name="correct_answer" id="createCorrectAnswer" required>
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="C">C</option>
                                    <option value="D">D</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Essay Key -->
                        <div class="col-12" id="createEssayWrapper" style="display: none;">
                            <label class="form-label fw-semibold">Kunci Jawaban Essay (Opsional)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-file-text"></i></span>
                                <textarea class="form-control" name="essay_answer_key" rows="3" placeholder="Masukkan kunci jawaban untuk essay..."></textarea>
                            </div>
                            <small class="text-muted">Kunci jawaban akan digunakan sebagai panduan penilaian.</small>
                        </div>
                        
                        <!-- Urutan -->
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Urutan</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-list-ol"></i></span>
                                <input type="number" class="form-control" name="order" value="{{ $questions->count() + 1 }}" min="0">
                            </div>
                            <small class="text-muted">Urutan tampil pertanyaan. Kosongkan untuk otomatis.</small>
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
     SHOW MODALS
============================================================ -->
@foreach($questions ?? [] as $question)
<div class="modal fade" id="showModal{{ $question->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-info-circle text-info me-2"></i>Detail Pertanyaan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="text-muted small">Tipe Pertanyaan</label>
                        <p class="fw-semibold">
                            @php
                                $typeMap = [
                                    'multiple_choice' => '📝 Pilihan Ganda',
                                    'essay' => '✍️ Essay',
                                    'pilihan' => '📝 Pilihan Ganda',
                                    'pilihan_ganda' => '📝 Pilihan Ganda',
                                ];
                            @endphp
                            {{ $typeMap[$question->type] ?? $question->type ?? 'Pilihan Ganda' }}
                        </p>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="text-muted small">Nilai</label>
                        <p class="fw-semibold">{{ $question->points ?? $question->score ?? 1 }} poin</p>
                    </div>
                    <div class="col-12">
                        <label class="text-muted small">Pertanyaan</label>
                        <p class="fw-semibold">{{ $question->question }}</p>
                    </div>
                    
                    @if(isset($question->type) && in_array($question->type, ['multiple_choice', 'pilihan', 'pilihan_ganda']))
                    <div class="col-12">
                        <label class="text-muted small">Pilihan Jawaban</label>
                        @php
                            $options = is_array($question->options) ? $question->options : json_decode($question->options, true);
                        @endphp
                        @if(is_array($options) && count($options) > 0)
                            @foreach($options as $key => $option)
                            <div class="d-flex align-items-center gap-2 p-2 border-bottom">
                                <span class="badge text-bg-secondary">{{ chr(65 + $key) }}</span>
                                <span>{{ $option }}</span>
                                @if($question->correct_answer == chr(65 + $key))
                                <span class="badge text-bg-success ms-auto"><i class="bi bi-check-circle"></i> Jawaban Benar</span>
                                @endif
                            </div>
                            @endforeach
                        @endif
                    </div>
                    @elseif(isset($question->type) && $question->type == 'essay')
                    <div class="col-12">
                        <label class="text-muted small">Kunci Jawaban Essay</label>
                        <p>{{ $question->essay_answer_key ?? 'Tidak ada kunci jawaban' }}</p>
                    </div>
                    @endif
                    
                    <div class="col-12 col-md-6">
                        <label class="text-muted small">Urutan</label>
                        <p class="fw-semibold">{{ $question->order ?? $loop->iteration }}</p>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="text-muted small">Dibuat</label>
                        <p class="fw-semibold">{{ $question->created_at ? $question->created_at->format('d/m/Y H:i') : '-' }}</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-warning" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#editModal{{ $question->id }}">
                    <i class="bi bi-pencil me-1"></i> Edit
                </button>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- ============================================================
     EDIT MODALS
============================================================ -->
@foreach($questions ?? [] as $question)
<div class="modal fade" id="editModal{{ $question->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.quiz.questions.update', ['quiz' => $question->quiz_id, 'question' => $question->id]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-pencil-square text-warning me-2"></i>Edit Pertanyaan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <p class="text-muted small">Quiz: <strong>{{ $question->quiz->judul ?? 'Semua Quiz' }}</strong></p>
                        </div>
                        <!-- Tipe Pertanyaan (readonly) -->
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Tipe Pertanyaan</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-tag"></i></span>
                                <select class="form-select" name="type" id="editType{{ $question->id }}" disabled>
                                    <option value="multiple_choice" {{ $question->type == 'multiple_choice' ? 'selected' : '' }}>Pilihan Ganda</option>
                                    <option value="essay" {{ $question->type == 'essay' ? 'selected' : '' }}>Essay</option>
                                </select>
                            </div>
                            <small class="text-muted">Tipe tidak dapat diubah setelah dibuat.</small>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Nilai <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-star"></i></span>
                                <input type="number" class="form-control" name="points" value="{{ $question->points ?? $question->score ?? 1 }}" min="1" max="100" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Pertanyaan <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-text-paragraph"></i></span>
                                <textarea class="form-control" name="question" rows="3" required>{{ $question->question }}</textarea>
                            </div>
                        </div>
                        
                        <!-- Multiple Choice Options -->
                        @if(in_array($question->type, ['multiple_choice', 'pilihan', 'pilihan_ganda']))
                        @php
                            $options = is_array($question->options) ? $question->options : json_decode($question->options, true);
                            $optionLetters = ['A', 'B', 'C', 'D', 'E', 'F'];
                        @endphp
                        <div class="col-12">
                            <hr>
                            <label class="form-label fw-semibold">Pilihan Jawaban <span class="text-danger">*</span></label>
                            <div id="editOptionsContainer{{ $question->id }}">
                                @if(is_array($options) && count($options) > 0)
                                    @foreach($options as $key => $option)
                                    <div class="row g-2 mb-2">
                                        <div class="col-10">
                                            <div class="input-group">
                                                <span class="input-group-text">{{ $optionLetters[$key] }}.</span>
                                                <input type="text" class="form-control" name="options[]" value="{{ $option }}" required>
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            @if($loop->first)
                                            <button type="button" class="btn btn-outline-success btn-sm" onclick="addEditOption({{ $question->id }})">
                                                <i class="bi bi-plus"></i>
                                            </button>
                                            @else
                                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeEditOption(this, {{ $question->id }})">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                @endif
                            </div>
                            <small class="text-muted">Minimal 2 pilihan jawaban.</small>
                        </div>
                        
                        <!-- Jawaban Benar -->
                        <div class="col-12">
                            <label class="form-label fw-semibold">Jawaban Benar <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-check-circle"></i></span>
                                <select class="form-select" name="correct_answer" required>
                                    @if(is_array($options) && count($options) > 0)
                                        @foreach($options as $key => $option)
                                        <option value="{{ $optionLetters[$key] }}" {{ $question->correct_answer == $optionLetters[$key] ? 'selected' : '' }}>
                                            {{ $optionLetters[$key] }}
                                        </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        @else
                        <!-- Essay Key -->
                        <div class="col-12">
                            <label class="form-label fw-semibold">Kunci Jawaban Essay (Opsional)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-file-text"></i></span>
                                <textarea class="form-control" name="essay_answer_key" rows="3">{{ $question->essay_answer_key ?? '' }}</textarea>
                            </div>
                            <small class="text-muted">Kunci jawaban akan digunakan sebagai panduan penilaian.</small>
                        </div>
                        @endif
                        
                        <!-- Urutan -->
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Urutan</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-list-ol"></i></span>
                                <input type="number" class="form-control" name="order" value="{{ $question->order ?? $loop->iteration }}" min="0">
                            </div>
                            <small class="text-muted">Urutan tampil pertanyaan.</small>
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
@endforeach

<!-- ============================================================
     DELETE MODALS
============================================================ -->
@foreach($questions ?? [] as $question)
<div class="modal fade" id="deleteModal{{ $question->id }}" tabindex="-1" aria-hidden="true">
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
                <p>Apakah Anda yakin ingin menghapus pertanyaan ini?</p>
                <div class="alert alert-light">
                    <p class="fw-semibold mb-0">"{{ Str::limit($question->question, 100) }}"</p>
                </div>
                @if(isset($question->type) && in_array($question->type, ['multiple_choice', 'pilihan', 'pilihan_ganda']))
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Pertanyaan ini memiliki pilihan jawaban.
                </div>
                @endif
                <p class="text-muted small">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin.quiz.questions.destroy', ['quiz' => $question->quiz_id, 'question' => $question->id]) }}" method="POST" class="d-inline">
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

<!-- Bulk Delete Modal -->
<div class="modal fade" id="bulkDeleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-triangle text-danger me-2"></i>
                    Konfirmasi Hapus Massal
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus <span id="bulkDeleteCount">0</span> pertanyaan yang dipilih?</p>
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Semua pertanyaan yang dipilih akan dihapus secara permanen.
                </div>
                <p class="text-muted small">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin.quiz.questions.bulk-delete', $quiz->id ?? '') }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="ids" id="bulkDeleteIds">
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-1"></i> Hapus Semua
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ============================================================
    // CREATE - TOGGLE TYPE
    // ============================================================
    const createType = document.getElementById('createType');
    const createOptionsWrapper = document.getElementById('createOptionsWrapper');
    const createCorrectWrapper = document.getElementById('createCorrectWrapper');
    const createEssayWrapper = document.getElementById('createEssayWrapper');

    function toggleCreateFields() {
        if (createType.value === 'essay') {
            createOptionsWrapper.style.display = 'none';
            createCorrectWrapper.style.display = 'none';
            createEssayWrapper.style.display = 'block';
            document.querySelectorAll('#createOptionsContainer input').forEach(input => input.removeAttribute('required'));
            document.getElementById('createCorrectAnswer').removeAttribute('required');
        } else {
            createOptionsWrapper.style.display = 'block';
            createCorrectWrapper.style.display = 'block';
            createEssayWrapper.style.display = 'none';
            document.querySelectorAll('#createOptionsContainer input').forEach(input => input.setAttribute('required', 'required'));
            document.getElementById('createCorrectAnswer').setAttribute('required', 'required');
        }
    }

    createType.addEventListener('change', toggleCreateFields);
    toggleCreateFields();

    // ============================================================
    // CREATE - ADD OPTION
    // ============================================================
    window.addCreateOption = function() {
        const container = document.getElementById('createOptionsContainer');
        const count = container.querySelectorAll('.row').length;
        const letters = ['A', 'B', 'C', 'D', 'E', 'F'];
        
        if (count >= 6) {
            alert('Maksimal 6 pilihan jawaban!');
            return;
        }
        
        const div = document.createElement('div');
        div.className = 'row g-2 mb-2';
        div.innerHTML = `
            <div class="col-10">
                <div class="input-group">
                    <span class="input-group-text">${letters[count]}.</span>
                    <input type="text" class="form-control" name="options[]" placeholder="Masukkan pilihan ${letters[count]}" required>
                </div>
            </div>
            <div class="col-2">
                <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeCreateOption(this)">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        `;
        container.appendChild(div);
        updateCreateCorrectOptions();
    };

    window.removeCreateOption = function(btn) {
        const row = btn.closest('.row');
        const container = document.getElementById('createOptionsContainer');
        if (container.querySelectorAll('.row').length <= 2) {
            alert('Minimal 2 pilihan jawaban!');
            return;
        }
        row.remove();
        updateCreateCorrectOptions();
        const rows = container.querySelectorAll('.row');
        const letters = ['A', 'B', 'C', 'D', 'E', 'F'];
        rows.forEach((row, index) => {
            row.querySelector('.input-group-text').textContent = letters[index] + '.';
        });
    };

    function updateCreateCorrectOptions() {
        const container = document.getElementById('createOptionsContainer');
        const rows = container.querySelectorAll('.row');
        const select = document.getElementById('createCorrectAnswer');
        const letters = ['A', 'B', 'C', 'D', 'E', 'F'];
        const currentValue = select.value;
        select.innerHTML = '';
        rows.forEach((row, index) => {
            const option = document.createElement('option');
            option.value = letters[index];
            option.textContent = letters[index];
            if (letters[index] === currentValue) {
                option.selected = true;
            }
            select.appendChild(option);
        });
    }

    // ============================================================
    // EDIT - ADD OPTION
    // ============================================================
    window.addEditOption = function(questionId) {
        const container = document.getElementById('editOptionsContainer' + questionId);
        const count = container.querySelectorAll('.row').length;
        const letters = ['A', 'B', 'C', 'D', 'E', 'F'];
        
        if (count >= 6) {
            alert('Maksimal 6 pilihan jawaban!');
            return;
        }
        
        const div = document.createElement('div');
        div.className = 'row g-2 mb-2';
        div.innerHTML = `
            <div class="col-10">
                <div class="input-group">
                    <span class="input-group-text">${letters[count]}.</span>
                    <input type="text" class="form-control" name="options[]" placeholder="Masukkan pilihan ${letters[count]}" required>
                </div>
            </div>
            <div class="col-2">
                <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeEditOption(this, ${questionId})">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        `;
        container.appendChild(div);
        updateEditCorrectOptions(questionId);
    };

    window.removeEditOption = function(btn, questionId) {
        const row = btn.closest('.row');
        const container = document.getElementById('editOptionsContainer' + questionId);
        if (container.querySelectorAll('.row').length <= 2) {
            alert('Minimal 2 pilihan jawaban!');
            return;
        }
        row.remove();
        updateEditCorrectOptions(questionId);
        const rows = container.querySelectorAll('.row');
        const letters = ['A', 'B', 'C', 'D', 'E', 'F'];
        rows.forEach((row, index) => {
            row.querySelector('.input-group-text').textContent = letters[index] + '.';
        });
    };

    function updateEditCorrectOptions(questionId) {
        const container = document.getElementById('editOptionsContainer' + questionId);
        const rows = container.querySelectorAll('.row');
        const select = container.closest('form').querySelector('select[name="correct_answer"]');
        if (!select) return;
        const letters = ['A', 'B', 'C', 'D', 'E', 'F'];
        const currentValue = select.value;
        select.innerHTML = '';
        rows.forEach((row, index) => {
            const option = document.createElement('option');
            option.value = letters[index];
            option.textContent = letters[index];
            if (letters[index] === currentValue) {
                option.selected = true;
            }
            select.appendChild(option);
        });
    }

    // ============================================================
    // SELECT ALL CHECKBOX
    // ============================================================
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.question-checkbox');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    const bulkDeleteIds = document.getElementById('bulkDeleteIds');
    const bulkDeleteCount = document.getElementById('bulkDeleteCount');

    if (selectAll) {
        selectAll.addEventListener('change', function() {
            checkboxes.forEach(cb => cb.checked = this.checked);
            updateBulkDeleteButton();
        });
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateBulkDeleteButton);
    });

    function updateBulkDeleteButton() {
        const checked = document.querySelectorAll('.question-checkbox:checked');
        const count = checked.length;

        if (count > 0) {
            bulkDeleteBtn.classList.remove('d-none');
            bulkDeleteBtn.innerHTML = '<i class="bi bi-trash"></i> Hapus ' + count + ' Terpilih';
        } else {
            bulkDeleteBtn.classList.add('d-none');
        }
    }

    if (bulkDeleteBtn) {
        bulkDeleteBtn.addEventListener('click', function() {
            const checked = document.querySelectorAll('.question-checkbox:checked');
            const ids = Array.from(checked).map(cb => cb.value);
            bulkDeleteIds.value = JSON.stringify(ids);
            bulkDeleteCount.textContent = ids.length;
            const modal = new bootstrap.Modal(document.getElementById('bulkDeleteModal'));
            modal.show();
        });
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

    // ============================================================
    // FOCUS SEARCH ON KEYBOARD SHORTCUT (CTRL + /)
    // ============================================================
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.key === '/') {
            e.preventDefault();
            const searchInput = document.querySelector('input[name="search"]');
            if (searchInput) {
                searchInput.focus();
                searchInput.select();
            }
        }
    });
});
</script>
@endpush
@endsection