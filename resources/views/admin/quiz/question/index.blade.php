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

    <!-- Table -->
    <div class="panel">
        <div class="panel-header">
            <div>
                <h5 class="section-title"><i class="bi bi-table"></i> Daftar Pertanyaan</h5>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('admin.quiz.questions.create', $quiz->id ?? '') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle"></i> Tambah
                </a>
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
                        @if(!isset($quiz))
                        <th>Quiz</th>
                        @endif
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
                                <p class="fw-semibold mb-0">{{ Str::limit($question->question ?? $question->pertanyaan, 60) }}</p>
                                @if(isset($question->type) && $question->type == 'multiple_choice' && isset($question->options))
                                <div class="text-muted small">
                                    @if(is_array($question->options))
                                        @foreach($question->options as $key => $option)
                                        <span class="badge text-bg-light me-1">
                                            {{ chr(65 + $key) }}: {{ Str::limit($option, 20) }}
                                            @if($question->correct_answer == chr(65 + $key))
                                            <i class="bi bi-check-circle text-success"></i>
                                            @endif
                                        </span>
                                        @endforeach
                                    @elseif(is_string($question->options))
                                        @php
                                            $options = json_decode($question->options, true);
                                        @endphp
                                        @if(is_array($options))
                                            @foreach($options as $key => $option)
                                            <span class="badge text-bg-light me-1">
                                                {{ chr(65 + $key) }}: {{ Str::limit($option, 20) }}
                                                @if($question->correct_answer == chr(65 + $key))
                                                <i class="bi bi-check-circle text-success"></i>
                                                @endif
                                            </span>
                                            @endforeach
                                        @endif
                                    @endif
                                    <span class="badge text-bg-success">Jawaban: {{ $question->correct_answer }}</span>
                                </div>
                                @elseif(isset($question->type) && $question->type == 'true_false')
                                <div class="text-muted small">
                                    <span class="badge text-bg-light me-1">Benar</span>
                                    <span class="badge text-bg-light me-1">Salah</span>
                                    <span class="badge text-bg-success">Jawaban: {{ $question->correct_answer == 'true' ? 'Benar' : 'Salah' }}</span>
                                </div>
                                @endif
                            </div>
                        </td>
                        @if(!isset($quiz))
                        <td>
                            <span class="badge text-bg-info">{{ $question->quiz->judul ?? '-' }}</span>
                        </td>
                        @endif
                        <td>
                            @php
                                $typeMap = [
                                    'multiple_choice' => ['label' => 'Pilihan Ganda', 'class' => 'text-bg-primary'],
                                    'true_false' => ['label' => 'Benar/Salah', 'class' => 'text-bg-success'],
                                    'essay' => ['label' => 'Essay', 'class' => 'text-bg-warning'],
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
                            <div class="d-flex gap-1 justify-content-end">
                                <a href="{{ route('admin.quiz.questions.edit', ['quiz' => $question->quiz_id, 'question' => $question->id]) }}" 
                                   class="badge bg-warning text-dark text-decoration-none p-2" title="Edit">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <button type="button" class="badge bg-danger text-white border-0 p-2" 
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

<!-- Delete Modals -->
@foreach($questions ?? [] as $question)
<div class="modal fade" id="deleteModal{{ $question->id }}" tabindex="-1" 
     aria-labelledby="deleteModalLabel{{ $question->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel{{ $question->id }}">
                    <i class="bi bi-exclamation-triangle text-danger me-2"></i>
                    Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus pertanyaan ini?</p>
                <div class="alert alert-light">
                    <p class="fw-semibold mb-0">"{{ Str::limit($question->question ?? $question->pertanyaan, 100) }}"</p>
                </div>
                @if(isset($question->type) && $question->type == 'multiple_choice')
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Pertanyaan ini memiliki <strong>{{ is_array($question->options) ? count($question->options) : 0 }}</strong> pilihan jawaban.
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
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
            bulkDeleteBtn.style.display = 'inline-block';
            bulkDeleteBtn.textContent = '🗑️ Hapus ' + count + ' Terpilih';
        } else {
            bulkDeleteBtn.style.display = 'none';
        }
    }

    // ============================================================
    // BULK DELETE
    // ============================================================
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
    // FILTER AUTO SUBMIT
    // ============================================================
    const typeFilter = document.querySelector('select[name="type"]');
    if (typeFilter) {
        typeFilter.addEventListener('change', function() {
            this.closest('form').submit();
        });
    }

    // ============================================================
    // SEARCH WITH ENTER KEY
    // ============================================================
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