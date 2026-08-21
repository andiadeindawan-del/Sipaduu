@extends('layouts.admin')

@section('title', 'Manajemen Pertanyaan Quiz')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-list-ol"></i></span>
        <div>
            <p class="eyebrow">Manajemen</p>
            <h1 class="h3 mb-0">Pertanyaan Quiz</h1>
           
        </div>
    </div>
    <div class="heading-actions d-flex gap-2">
        <a href="{{ route('admin.quiz.index') }}" class="btn btn-secondary btn-sm">
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
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="bi bi-search"></i> Cari
                </button>
                <a href="{{ route('admin.quiz.questions.index', $quiz->id ?? '') }}" class="btn btn-outline-secondary btn-sm" title="Reset Filter">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                </a>
                <a href="{{ route('admin.quiz.questions.create', $quiz->id ?? '') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle"></i> Tambah
                </a>
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
                        <th style="width: 50px;">No</th>
                        <th>Pertanyaan</th>
                        <th>Tipe</th>
                        <th>Nilai</th>
                        <th>Urutan</th>
                        <th class="text-center" style="width: 120px;">Aksi</th>
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
                            <span class="text-muted">
                                {{ $type['label'] }}
                            </span>
                        </td>
                        <td>
                            <span class="text-muted">
                                {{ $question->points ?? $question->score ?? 1 }}
                            </span>
                        </td>
                        <td>
                            <span class="text-muted">{{ $question->order ?? $loop->iteration }}</span>
                        </td>
                        <td class="text-end">
                            <div class="d-flex gap-1 justify-content-center" role="group">
                                <a href="{{ route('admin.quiz.questions.show', ['quiz' => $question->quiz_id, 'question' => $question->id]) }}" class="btn btn-info" title="Lihat Detail">
                                    <i class="bi bi-eye"></i> 
                                </a>
                                <a href="{{ route('admin.quiz.questions.edit', ['quiz' => $question->quiz_id, 'question' => $question->id]) }}" class="btn btn-warning" title="Edit">
                                    <i class="bi bi-pencil"></i> 
                                </a>
                                <form action="{{ route('admin.quiz.questions.destroy', ['quiz' => $question->quiz_id, 'question' => $question->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pertanyaan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" title="Hapus">
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
                    <p class="h5">Belum ada pertanyaan</p>
                    <p class="small">
                        @if(isset($quiz))
                            Quiz <strong>{{ $quiz->judul }}</strong> belum memiliki pertanyaan.
                        @else
                            Mulai dengan menambahkan pertanyaan baru.
                        @endif
                    </p>
                    <a href="{{ route('admin.quiz.questions.create', $quiz->id ?? '') }}" class="btn btn-primary btn-sm mt-2">
                        <i class="bi bi-plus-circle"></i> Tambah Pertanyaan
                    </a>
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