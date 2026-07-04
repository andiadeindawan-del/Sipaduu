@extends('layouts.admin')

@section('title', 'Edit Pertanyaan Quiz')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-pencil-square"></i></span>
        <div>
            <p class="eyebrow">Manajemen</p>
            <h1 class="h3 mb-0">Edit Pertanyaan</h1>
            <p class="text-muted mb-0">
                Quiz: <strong>{{ $quiz->judul }}</strong>
                @if($quiz->materi)
                    <span class="text-muted">| Materi: {{ $quiz->materi->judul }}</span>
                @endif
            </p>
        </div>
    </div>
    <div class="heading-actions">
        <a href="{{ route('admin.quiz.questions.index', $quiz->id) }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="row">
        <div class="col-12 col-lg-10 mx-auto">
            <!-- Alert Errors -->
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Terjadi kesalahan!</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="panel">
                <div class="panel-header">
                    <h5 class="section-title"><i class="bi bi-pencil-square"></i> Form Edit Pertanyaan</h5>
                    <p class="text-muted small mb-0">Perbarui data pertanyaan.</p>
                </div>
                <div class="p-4">
                    <form action="{{ route('admin.quiz.questions.update', ['quiz' => $quiz->id, 'question' => $question->id]) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Hidden quiz_id -->
                        <input type="hidden" name="quiz_id" value="{{ $quiz->id }}">

                        <div class="row g-3">
                            <!-- Tipe Soal -->
                            <div class="col-12 col-md-4">
                                <label for="type" class="form-label fw-semibold">
                                    Tipe Soal <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-tag"></i></span>
                                    <select class="form-select @error('type') is-invalid @enderror" 
                                            id="type" name="type" required>
                                        <option value="">Pilih Tipe</option>
                                        <option value="multiple_choice" {{ old('type', $question->type) == 'multiple_choice' ? 'selected' : '' }}>📝 Pilihan Ganda</option>
                                        <option value="true_false" {{ old('type', $question->type) == 'true_false' ? 'selected' : '' }}>✅ Benar/Salah</option>
                                        <option value="essay" {{ old('type', $question->type) == 'essay' ? 'selected' : '' }}>✍️ Essay</option>
                                    </select>
                                </div>
                                @error('type')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Nilai -->
                            <div class="col-12 col-md-4">
                                <label for="score" class="form-label fw-semibold">
                                    Nilai <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-star"></i></span>
                                    <input type="number" class="form-control @error('score') is-invalid @enderror" 
                                           id="score" name="score" value="{{ old('score', $question->score ?? 1) }}" 
                                           min="1" max="100" required>
                                </div>
                                <small class="text-muted">Nilai untuk setiap jawaban benar (min 1, max 100).</small>
                                @error('score')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Urutan -->
                            <div class="col-12 col-md-4">
                                <label for="order" class="form-label fw-semibold">
                                    Urutan
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-list-ol"></i></span>
                                    <input type="number" class="form-control @error('order') is-invalid @enderror" 
                                           id="order" name="order" value="{{ old('order', $question->order ?? 0) }}" 
                                           min="0">
                                </div>
                                <small class="text-muted">Urutan tampil pertanyaan (semakin kecil semakin atas).</small>
                                @error('order')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Pertanyaan -->
                            <div class="col-12">
                                <label for="question" class="form-label fw-semibold">
                                    Pertanyaan <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-question-lg"></i></span>
                                    <textarea class="form-control @error('question') is-invalid @enderror" 
                                              id="question" name="question" rows="3" 
                                              placeholder="Masukkan pertanyaan..." required>{{ old('question', $question->question) }}</textarea>
                                </div>
                                @error('question')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- ========================================================== -->
                            <!-- MULTIPLE CHOICE OPTIONS -->
                            <!-- ========================================================== -->
                            @php
                                $options = is_array($question->options) ? $question->options : [];
                                $options = array_values($options);
                            @endphp
                            <div id="multipleChoiceSection" class="col-12" style="{{ old('type', $question->type) == 'multiple_choice' ? '' : 'display: none;' }}">
                                <hr class="my-2">
                                <h6 class="fw-semibold text-muted">
                                    <i class="bi bi-list-check me-2"></i>Pilihan Jawaban
                                </h6>
                                <p class="text-muted small">Isi pilihan jawaban untuk soal pilihan ganda.</p>

                                <div class="row g-2">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Pilihan A <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">A</span>
                                            <input type="text" class="form-control @error('options.0') is-invalid @enderror" 
                                                   name="options[]" value="{{ old('options.0', $options[0] ?? '') }}" placeholder="Pilihan A">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Pilihan B <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">B</span>
                                            <input type="text" class="form-control @error('options.1') is-invalid @enderror" 
                                                   name="options[]" value="{{ old('options.1', $options[1] ?? '') }}" placeholder="Pilihan B">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Pilihan C</label>
                                        <div class="input-group">
                                            <span class="input-group-text">C</span>
                                            <input type="text" class="form-control @error('options.2') is-invalid @enderror" 
                                                   name="options[]" value="{{ old('options.2', $options[2] ?? '') }}" placeholder="Pilihan C (Opsional)">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Pilihan D</label>
                                        <div class="input-group">
                                            <span class="input-group-text">D</span>
                                            <input type="text" class="form-control @error('options.3') is-invalid @enderror" 
                                                   name="options[]" value="{{ old('options.3', $options[3] ?? '') }}" placeholder="Pilihan D (Opsional)">
                                        </div>
                                    </div>
                                </div>

                                <!-- Jawaban Benar untuk Multiple Choice -->
                                <div class="row mt-3">
                                    <div class="col-12 col-md-6">
                                        <label for="correct_answer_mc" class="form-label fw-semibold">
                                            Jawaban Benar <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-check-lg"></i></span>
                                            <select class="form-select @error('correct_answer') is-invalid @enderror" 
                                                    id="correct_answer_mc" name="correct_answer">
                                                <option value="">Pilih Jawaban Benar</option>
                                                <option value="A" {{ old('correct_answer', $question->correct_answer) == 'A' ? 'selected' : '' }}>A</option>
                                                <option value="B" {{ old('correct_answer', $question->correct_answer) == 'B' ? 'selected' : '' }}>B</option>
                                                <option value="C" {{ old('correct_answer', $question->correct_answer) == 'C' ? 'selected' : '' }}>C</option>
                                                <option value="D" {{ old('correct_answer', $question->correct_answer) == 'D' ? 'selected' : '' }}>D</option>
                                            </select>
                                        </div>
                                        @error('correct_answer')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- ========================================================== -->
                            <!-- TRUE FALSE SECTION -->
                            <!-- ========================================================== -->
                            <div id="trueFalseSection" class="col-12" style="{{ old('type', $question->type) == 'true_false' ? '' : 'display: none;' }}">
                                <hr class="my-2">
                                <h6 class="fw-semibold text-muted">
                                    <i class="bi bi-check-circle me-2"></i>Jawaban Benar/Salah
                                </h6>
                                <p class="text-muted small">Pilih jawaban yang benar untuk soal benar/salah.</p>

                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">
                                            Jawaban Benar <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-check-lg"></i></span>
                                            <select class="form-select @error('correct_answer') is-invalid @enderror" 
                                                    id="correct_answer_tf" name="correct_answer">
                                                <option value="">Pilih Jawaban Benar</option>
                                                <option value="true" {{ old('correct_answer', $question->correct_answer) == 'true' ? 'selected' : '' }}>✅ Benar</option>
                                                <option value="false" {{ old('correct_answer', $question->correct_answer) == 'false' ? 'selected' : '' }}>❌ Salah</option>
                                            </select>
                                        </div>
                                        @error('correct_answer')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- ========================================================== -->
                            <!-- ESSAY SECTION -->
                            <!-- ========================================================== -->
                            <div id="essaySection" class="col-12" style="{{ old('type', $question->type) == 'essay' ? '' : 'display: none;' }}">
                                <hr class="my-2">
                                <h6 class="fw-semibold text-muted">
                                    <i class="bi bi-pencil me-2"></i>Jawaban Essay
                                </h6>
                                <p class="text-muted small">Tentukan kata kunci atau jawaban untuk soal essay.</p>

                                <div class="row">
                                    <div class="col-12">
                                        <label for="correct_answer_essay" class="form-label fw-semibold">
                                            Jawaban / Kata Kunci <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-key"></i></span>
                                            <textarea class="form-control @error('correct_answer') is-invalid @enderror" 
                                                      id="correct_answer_essay" name="correct_answer" rows="2" 
                                                      placeholder="Masukkan jawaban atau kata kunci untuk essay...">{{ old('correct_answer', $question->correct_answer) }}</textarea>
                                        </div>
                                        <small class="text-muted">Masukkan jawaban lengkap atau kata kunci untuk penilaian essay.</small>
                                        @error('correct_answer')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- ========================================================== -->
                            <!-- SUBMIT BUTTONS -->
                            <!-- ========================================================== -->
                            <div class="col-12 mt-4">
                                <hr class="my-2">
                                <div class="d-flex gap-2 flex-wrap">
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <i class="bi bi-save me-1"></i> Perbarui Pertanyaan
                                    </button>
                                    <a href="{{ route('admin.quiz.questions.index', $quiz->id) }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-x-circle me-1"></i> Batal
                                    </a>
                                    <button type="button" class="btn btn-outline-danger" 
                                            data-bs-toggle="modal" data-bs-target="#deleteModal">
                                        <i class="bi bi-trash me-1"></i> Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
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
                <p>Apakah Anda yakin ingin menghapus pertanyaan ini?</p>
                <div class="alert alert-light">
                    <p class="fw-semibold mb-0">"{{ Str::limit($question->question, 100) }}"</p>
                </div>
                <p class="text-muted small">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin.quiz.questions.destroy', ['quiz' => $quiz->id, 'question' => $question->id]) }}" method="POST">
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ============================================================
    // TOGGLE SECTIONS BASED ON TYPE
    // ============================================================
    const typeSelect = document.getElementById('type');
    const mcSection = document.getElementById('multipleChoiceSection');
    const tfSection = document.getElementById('trueFalseSection');
    const essaySection = document.getElementById('essaySection');

    function toggleSections() {
        const type = typeSelect.value;
        
        mcSection.style.display = 'none';
        tfSection.style.display = 'none';
        essaySection.style.display = 'none';

        if (type === 'multiple_choice') {
            mcSection.style.display = 'block';
        } else if (type === 'true_false') {
            tfSection.style.display = 'block';
        } else if (type === 'essay') {
            essaySection.style.display = 'block';
        }
    }

    if (typeSelect) {
        typeSelect.addEventListener('change', toggleSections);
        toggleSections();
    }

    // ============================================================
    // SCORE VALIDATION - DIPERBAIKI
    // ============================================================
    const scoreInput = document.getElementById('score');
    if (scoreInput) {
        scoreInput.addEventListener('blur', function() {
            let value = parseInt(this.value);
            if (isNaN(value) || value < 1) {
                this.value = 1;
            } else if (value > 100) {
                this.value = 100;
            }
        });
    }

    // ============================================================
    // ORDER VALIDATION
    // ============================================================
    const orderInput = document.getElementById('order');
    if (orderInput) {
        orderInput.addEventListener('input', function() {
            if (this.value < 0) {
                this.value = 0;
            }
        });
    }

    // ============================================================
    // FORM SUBMIT VALIDATION
    // ============================================================
    const form = document.querySelector('form');
    const submitBtn = document.getElementById('submitBtn');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            // Validasi quiz_id
            const quizId = document.querySelector('input[name="quiz_id"]');
            if (!quizId || !quizId.value) {
                e.preventDefault();
                alert('⚠️ Quiz ID tidak ditemukan. Silakan refresh halaman.');
                return false;
            }

            // Validasi score
            const score = parseInt(document.getElementById('score').value);
            if (isNaN(score) || score < 1 || score > 100) {
                e.preventDefault();
                alert('⚠️ Nilai harus antara 1 - 100.');
                document.getElementById('score').focus();
                return false;
            }

            // Validasi question
            const question = document.getElementById('question').value.trim();
            if (!question) {
                e.preventDefault();
                alert('⚠️ Pertanyaan wajib diisi.');
                document.getElementById('question').focus();
                return false;
            }

            // Disable button untuk mencegah double submit
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span> Menyimpan...';
            
            return true;
        });
    }
});
</script>
@endpush
@endsection