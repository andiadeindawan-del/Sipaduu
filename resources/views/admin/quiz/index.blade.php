@extends('layouts.admin')

@section('title', 'Manajemen Pertanyaan Quiz')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-list-ol"></i></span>
        <div>
            <p class="eyebrow">Manajemen</p>
            <h1 class="h3 mb-0">Pertanyaan Quiz</h1>
            @if(isset($quiz))
            <p class="text-muted mb-0">
                Quiz: <strong>{{ $quiz->judul }}</strong>
                @if($quiz->materi)
                    <span class="text-muted">| Materi: {{ $quiz->materi->judul }}</span>
                @endif
            </p>
            @endif
        </div>
    </div>
    <div class="heading-actions d-flex gap-2">
        <a href="{{ route('admin.quiz.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        @if(isset($quiz))
        <a href="{{ route('admin.quiz.questions.create', ['quiz' => $quiz->id]) }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle"></i> Tambah Pertanyaan
        </a>
        @endif
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

    <!-- Filter -->
    <div class="panel mb-3">
        <div class="panel-header">
            <div>
                <h5 class="section-title"><i class="bi bi-funnel"></i> Filter</h5>
                <p class="text-muted small mb-0">Filter pertanyaan berdasarkan kriteria.</p>
            </div>
        </div>
        <div class="p-3">
            <form action="{{ route('admin.quiz.questions.index', ['quiz' => $quiz->id ?? '']) }}" method="GET" class="row g-3">
                <div class="col-12 col-md-4">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Cari pertanyaan...">
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <select class="form-select" name="type">
                        <option value="">Semua Tipe</option>
                        <option value="multiple_choice" {{ request('type') == 'multiple_choice' ? 'selected' : '' }}>
                            Pilihan Ganda
                        </option>
                        <option value="essay" {{ request('type') == 'essay' ? 'selected' : '' }}>
                            Essay
                        </option>
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> Filter
                        </button>
                        <a href="{{ route('admin.quiz.questions.index', ['quiz' => $quiz->id ?? '']) }}" 
                           class="btn btn-outline-secondary" title="Reset Filter">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Stats -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-primary">
                <div class="metric-top">
                    <span class="metric-label">Total Pertanyaan</span>
                    <span class="metric-icon"><i class="bi bi-list-ol"></i></span>
                </div>
                <div class="metric-value">{{ $totalQuestions ?? 0 }}</div>
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

    <!-- Table -->
    <div class="panel">
        <div class="panel-header">
            <div>
                <h5 class="section-title"><i class="bi bi-table"></i> Daftar Pertanyaan</h5>
                @if(isset($questions))
                <p class="text-muted small mb-0">
                    Menampilkan {{ $questions->firstItem() ?? 0 }} - {{ $questions->lastItem() ?? 0 }} 
                    dari {{ $questions->total() ?? 0 }} pertanyaan
                </p>
                @endif
            </div>
            <div class="d-flex gap-2 flex-wrap">
                @if(isset($quiz))
                <a href="{{ route('admin.quiz.questions.create', ['quiz' => $quiz->id]) }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle"></i> Tambah
                </a>
                @endif
            </div>
        </div>
        <div class="table-responsive">
            @if(isset($questions) && $questions->count() > 0)
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Pertanyaan</th>
                        <th>Tipe</th>
                        <th>Nilai</th>
                        <th>Urutan</th>
                        <th class="text-end" style="width: 160px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($questions as $index => $question)
                    <tr>
                        <td>{{ $questions->firstItem() + $index }}</td>
                        <td>
                            <div>
                                <p class="fw-semibold mb-0">{{ Str::limit($question->question ?? $question->pertanyaan, 60) }}</p>
                                @if($question->type == 'multiple_choice')
                                <div class="text-muted small">
                                    @php
                                        $options = is_array($question->options) ? $question->options : json_decode($question->options, true);
                                    @endphp
                                    @if(is_array($options) && count($options) > 0)
                                        <div class="d-flex flex-wrap gap-1 mt-1">
                                            @foreach($options as $key => $option)
                                            <span class="badge text-bg-light">
                                                {{ chr(65 + $key) }}: {{ Str::limit($option, 20) }}
                                                @if($question->correct_answer == chr(65 + $key))
                                                <i class="bi bi-check-circle text-success"></i>
                                                @endif
                                            </span>
                                            @endforeach
                                        </div>
                                        <span class="badge text-bg-success mt-1">Jawaban: {{ $question->correct_answer }}</span>
                                    @endif
                                </div>
                                @endif
                                @if($question->type == 'essay' && $question->essay_answer_key)
                                <div class="text-muted small mt-1">
                                    <span class="badge text-bg-info">
                                        <i class="bi bi-key me-1"></i>
                                        Kunci: {{ Str::limit($question->essay_answer_key, 30) }}
                                    </span>
                                </div>
                                @endif
                            </div>
                        </td>
                        <td>
                            @php
                                $typeMap = [
                                    'multiple_choice' => ['label' => 'Pilihan Ganda', 'class' => 'text-bg-primary'],
                                    'pilihan' => ['label' => 'Pilihan Ganda', 'class' => 'text-bg-primary'],
                                    'pilihan_ganda' => ['label' => 'Pilihan Ganda', 'class' => 'text-bg-primary'],
                                    'essay' => ['label' => 'Essay', 'class' => 'text-bg-warning'],
                                    'true_false' => ['label' => 'Benar/Salah', 'class' => 'text-bg-success'],
                                ];
                                $type = $typeMap[$question->type] ?? ['label' => $question->type, 'class' => 'text-bg-secondary'];
                            @endphp
                            <span class="badge {{ $type['class'] }}">
                                {{ $type['label'] }}
                            </span>
                        </td>
                        <td>
                            <span class="badge text-bg-secondary">
                                <i class="bi bi-star me-1"></i>
                                {{ $question->score ?? $question->nilai ?? 1 }}
                            </span>
                        </td>
                        <td>
                            <span class="badge text-bg-light">{{ $question->order ?? $loop->iteration }}</span>
                        </td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('admin.quiz.questions.edit', ['quiz' => $quiz->id ?? $question->quiz_id, 'question' => $question->id]) }}" 
                                   class="btn btn-warning" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" class="btn btn-danger" 
                                        data-bs-toggle="modal" data-bs-target="#deleteModal{{ $question->id }}" 
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
                        @if(request('search') || request('type'))
                            Tidak ada pertanyaan yang sesuai dengan filter
                        @else
                            Belum ada pertanyaan
                        @endif
                    </p>
                    <p class="small">
                        @if(isset($quiz))
                            Quiz <strong>{{ $quiz->judul }}</strong> belum memiliki pertanyaan.
                        @else
                            Belum ada pertanyaan yang tersedia.
                        @endif
                    </p>
                    @if(request('search') || request('type'))
                    <a href="{{ route('admin.quiz.questions.index', ['quiz' => $quiz->id ?? '']) }}" class="btn btn-outline-secondary btn-sm mt-2">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset Filter
                    </a>
                    @endif
                    @if(isset($quiz))
                    <a href="{{ route('admin.quiz.questions.create', ['quiz' => $quiz->id]) }}" class="btn btn-primary btn-sm mt-2">
                        <i class="bi bi-plus-circle"></i> Tambah Pertanyaan
                    </a>
                    @endif
                </div>
            </div>
            @endif
        </div>
        @if(isset($questions) && $questions->hasPages())
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mt-3 px-3 pb-3">
            <p class="text-muted small mb-0">
                Menampilkan {{ $questions->firstItem() ?? 0 }} sampai {{ $questions->lastItem() ?? 0 }} 
                dari {{ $questions->total() ?? 0 }} pertanyaan
            </p>
            <nav aria-label="Pertanyaan pagination">
                {{ $questions->appends(request()->query())->links() }}
            </nav>
        </div>
        @endif
    </div>
</div>

<!-- Delete Modals -->
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
                    <p class="fw-semibold mb-0">"{{ Str::limit($question->question ?? $question->pertanyaan, 100) }}"</p>
                </div>
                @if($question->type == 'multiple_choice')
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Pertanyaan ini memiliki {{ count($question->formatted_options ?? []) }} pilihan jawaban.
                </div>
                @endif
                <p class="text-muted small">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin.quiz.questions.destroy', ['quiz' => $question->quiz_id, 'question' => $question->id]) }}" method="POST">
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

    // Filter auto submit on select change
    document.querySelector('select[name="type"]')?.addEventListener('change', function() {
        this.closest('form').submit();
    });
});
</script>
@endpush
@endsection