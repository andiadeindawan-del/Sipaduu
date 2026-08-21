```blade
@extends('layouts.admin')

@section('title', 'Tambah Pertanyaan Quiz')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-plus-circle"></i></span>
        <div>
            <p class="eyebrow">Manajemen</p>
            <h1 class="h3 mb-0">Tambah Pertanyaan</h1>
            <p class="text-muted mb-0">
                Quiz: <strong>{{ $quiz->judul }}</strong>
                @if($quiz->materi)
                    <span class="text-muted">| Materi: {{ $quiz->materi->judul }}</span>
                @endif
            </p>
        </div>
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
                    <h5 class="section-title"><i class="bi bi-question-square"></i> Form Tambah Pertanyaan</h5>
                    <p class="text-muted small mb-0">Isi data pertanyaan dengan lengkap.</p>
                </div>
                <div class="p-4">
                    <form action="{{ route('admin.quiz.questions.store', $quiz->id) }}" method="POST" id="questionForm">
                        @csrf
                        
                        <!-- Hidden quiz_id -->
                        <input type="hidden" name="quiz_id" value="{{ $quiz->id }}">
                        
                        <!-- Hidden correct_answer untuk menyimpan nilai dari berbagai tipe -->
                        <input type="hidden" name="correct_answer" id="correct_answer_hidden" value="{{ old('correct_answer') }}">

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
                                        <option value="multiple_choice" {{ old('type') == 'multiple_choice' ? 'selected' : '' }}>📝 Pilihan Ganda</option>
                                        <option value="true_false" {{ old('type') == 'true_false' ? 'selected' : '' }}>✅ Benar/Salah</option>
                                        <option value="essay" {{ old('type') == 'essay' ? 'selected' : '' }}>✍️ Essay</option>
                                    </select>
                                </div>
                                @error('type')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- ========================================================== -->
                            <!-- PENILAIAN - DROPDOWN 5, 10, 15, 20, 25, 30 -->
                            <!-- ========================================================== -->
                            <div class="col-12 col-md-4">
                                <label for="score" class="form-label fw-semibold">
                                    Nilai Per Soal <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-star"></i></span>
                                    <select class="form-select @error('score') is-invalid @enderror" 
                                            id="score" name="score" required>
                                        <option value="">Pilih Nilai</option>
                                        <option value="5" {{ old('score', 5) == 5 ? 'selected' : '' }}>⭐ 5 (Sangat Mudah)</option>
                                        <option value="10" {{ old('score') == 10 ? 'selected' : '' }}>⭐⭐ 10 (Mudah)</option>
                                        <option value="15" {{ old('score') == 15 ? 'selected' : '' }}>⭐⭐⭐ 15 (Sedang)</option>
                                        <option value="20" {{ old('score') == 20 ? 'selected' : '' }}>⭐⭐⭐⭐ 20 (Agak Sulit)</option>
                                        <option value="25" {{ old('score') == 25 ? 'selected' : '' }}>⭐⭐⭐⭐⭐ 25 (Sulit)</option>
                                        <option value="30" {{ old('score') == 30 ? 'selected' : '' }}>🌟🌟 30 (Sangat Sulit)</option>
                                    </select>
                                </div>
                                <small class="text-muted">Pilih nilai sesuai tingkat kesulitan soal.</small>
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
                                           id="order" name="order" value="{{ old('order', ($quiz->questions()->max('order') ?? 0) + 1) }}" 
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
                                              placeholder="Masukkan pertanyaan..." required>{{ old('question') }}</textarea>
                                </div>
                                @error('question')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- ========================================================== -->
                            <!-- MULTIPLE CHOICE OPTIONS -->
                            <!-- ========================================================== -->
                            <div id="multipleChoiceSection" class="col-12" style="{{ old('type', 'multiple_choice') == 'multiple_choice' ? '' : 'display: none;' }}">
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
                                                   id="option_a" name="options[]" value="{{ old('options.0') }}" placeholder="Pilihan A" required>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Pilihan B <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">B</span>
                                            <input type="text" class="form-control @error('options.1') is-invalid @enderror" 
                                                   id="option_b" name="options[]" value="{{ old('options.1') }}" placeholder="Pilihan B" required>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Pilihan C</label>
                                        <div class="input-group">
                                            <span class="input-group-text">C</span>
                                            <input type="text" class="form-control @error('options.2') is-invalid @enderror" 
                                                   id="option_c" name="options[]" value="{{ old('options.2') }}" placeholder="Pilihan C (Opsional)">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Pilihan D</label>
                                        <div class="input-group">
                                            <span class="input-group-text">D</span>
                                            <input type="text" class="form-control @error('options.3') is-invalid @enderror" 
                                                   id="option_d" name="options[]" value="{{ old('options.3') }}" placeholder="Pilihan D (Opsional)">
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
                                                    id="correct_answer_mc" onchange="document.getElementById('correct_answer_hidden').value = this.value">
                                                <option value="">Pilih Jawaban Benar</option>
                                                <option value="A" {{ old('correct_answer') == 'A' ? 'selected' : '' }}>A</option>
                                                <option value="B" {{ old('correct_answer') == 'B' ? 'selected' : '' }}>B</option>
                                                <option value="C" {{ old('correct_answer') == 'C' ? 'selected' : '' }}>C</option>
                                                <option value="D" {{ old('correct_answer') == 'D' ? 'selected' : '' }}>D</option>
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
                            <div id="trueFalseSection" class="col-12" style="{{ old('type') == 'true_false' ? '' : 'display: none;' }}">
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
                                                    id="correct_answer_tf" onchange="document.getElementById('correct_answer_hidden').value = this.value">
                                                <option value="">Pilih Jawaban Benar</option>
                                                <option value="true" {{ old('correct_answer') == 'true' ? 'selected' : '' }}>✅ Benar</option>
                                                <option value="false" {{ old('correct_answer') == 'false' ? 'selected' : '' }}>❌ Salah</option>
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
                            <div id="essaySection" class="col-12" style="{{ old('type') == 'essay' ? '' : 'display: none;' }}">
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
                                                      id="correct_answer_essay" rows="2" 
                                                      placeholder="Masukkan jawaban atau kata kunci untuk essay..." 
                                                      oninput="document.getElementById('correct_answer_hidden').value = this.value">{{ old('correct_answer') }}</textarea>
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
                                        <i class="bi bi-save me-1"></i> Simpan Pertanyaan
                                    </button>
                                    <a href="{{ route('admin.quiz.questions.index', $quiz->id) }}" class="btn btn-secondary">
                                        <i class="bi bi-x-circle me-1"></i> Batal
                                    </a>
                                    <button type="reset" class="btn btn-warning">
                                        <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                                    </button>
                                    <a href="{{ route('admin.quiz.questions.index', $quiz->id) }}" class="btn btn-secondary">
                                        <i class="bi bi-arrow-left me-1"></i> Kembali
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tips -->
            <div class="panel mt-4">
                <div class="panel-header">
                    <h6 class="section-title"><i class="bi bi-info-circle"></i> Tips Membuat Pertanyaan</h6>
                </div>
                <div class="p-4">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="text-center">
                                <i class="bi bi-list-check fs-1 text-primary mb-2 d-block"></i>
                                <h6>Pilihan Ganda</h6>
                                <small class="text-muted">Buat pilihan jawaban yang jelas dan hanya 1 jawaban benar</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <i class="bi bi-check-circle fs-1 text-success mb-2 d-block"></i>
                                <h6>Benar/Salah</h6>
                                <small class="text-muted">Pernyataan harus jelas dan tidak ambigu</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <i class="bi bi-pencil fs-1 text-warning mb-2 d-block"></i>
                                <h6>Essay</h6>
                                <small class="text-muted">Berikan kata kunci untuk memudahkan penilaian</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <i class="bi bi-star fs-1 text-danger mb-2 d-block"></i>
                                <h6>Penilaian</h6>
                                <small class="text-muted">Sesuaikan nilai per soal dengan tingkat kesulitan</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ============================================================
    // ELEMENTS
    // ============================================================
    const typeSelect = document.getElementById('type');
    const mcSection = document.getElementById('multipleChoiceSection');
    const tfSection = document.getElementById('trueFalseSection');
    const essaySection = document.getElementById('essaySection');
    const form = document.getElementById('questionForm');
    const submitBtn = document.getElementById('submitBtn');
    const correctAnswerHidden = document.getElementById('correct_answer_hidden');

    // ============================================================
    // TOGGLE SECTIONS BASED ON TYPE
    // ============================================================
    function toggleSections() {
        const type = typeSelect.value;
        
        // Sembunyikan semua section
        mcSection.style.display = 'none';
        tfSection.style.display = 'none';
        essaySection.style.display = 'none';

        // Reset required attributes
        document.querySelectorAll('#multipleChoiceSection input, #multipleChoiceSection select, #trueFalseSection select, #essaySection textarea')
            .forEach(el => el.required = false);

        // Reset correct_answer hidden value
        correctAnswerHidden.value = '';

        // Tampilkan section sesuai tipe
        if (type === 'multiple_choice') {
            mcSection.style.display = 'block';
            document.querySelectorAll('#multipleChoiceSection input[name="options[]"]').forEach((input, index) => {
                if (index < 2) input.required = true;
            });
            document.getElementById('correct_answer_mc').required = true;
            // Set value jika sudah ada
            if (document.getElementById('correct_answer_mc').value) {
                correctAnswerHidden.value = document.getElementById('correct_answer_mc').value;
            }
        } else if (type === 'true_false') {
            tfSection.style.display = 'block';
            document.getElementById('correct_answer_tf').required = true;
            if (document.getElementById('correct_answer_tf').value) {
                correctAnswerHidden.value = document.getElementById('correct_answer_tf').value;
            }
        } else if (type === 'essay') {
            essaySection.style.display = 'block';
            document.getElementById('correct_answer_essay').required = true;
            if (document.getElementById('correct_answer_essay').value) {
                correctAnswerHidden.value = document.getElementById('correct_answer_essay').value;
            }
        }
    }

    // Event listener untuk perubahan tipe
    if (typeSelect) {
        typeSelect.addEventListener('change', toggleSections);
        // Jalankan sekali untuk set initial state
        toggleSections();
    }

    // ============================================================
    // VALIDASI FORM SEBELUM SUBMIT
    // ============================================================
    if (form) {
        form.addEventListener('submit', function(e) {
            const type = typeSelect.value;
            let errors = [];

            // Reset error states
            document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

            // Validasi berdasarkan tipe
            if (type === 'multiple_choice') {
                // Validasi minimal 2 pilihan
                const options = document.querySelectorAll('#multipleChoiceSection input[name="options[]"]');
                let filled = 0;
                options.forEach(opt => {
                    if (opt.value.trim() !== '') filled++;
                });
                
                if (filled < 2) {
                    errors.push('Minimal 2 pilihan jawaban untuk soal pilihan ganda.');
                    options.forEach(opt => {
                        if (opt.value.trim() === '') opt.classList.add('is-invalid');
                    });
                }

                // Validasi jawaban benar
                const correctAnswer = document.getElementById('correct_answer_mc').value;
                if (!correctAnswer) {
                    errors.push('Silakan pilih jawaban yang benar.');
                    document.getElementById('correct_answer_mc').classList.add('is-invalid');
                } else {
                    correctAnswerHidden.value = correctAnswer;
                }
            } else if (type === 'true_false') {
                const correctAnswer = document.getElementById('correct_answer_tf').value;
                if (!correctAnswer) {
                    errors.push('Silakan pilih jawaban yang benar (Benar/Salah).');
                    document.getElementById('correct_answer_tf').classList.add('is-invalid');
                } else {
                    correctAnswerHidden.value = correctAnswer;
                }
            } else if (type === 'essay') {
                const correctAnswer = document.getElementById('correct_answer_essay').value.trim();
                if (!correctAnswer) {
                    errors.push('Silakan isi jawaban atau kata kunci untuk essay.');
                    document.getElementById('correct_answer_essay').classList.add('is-invalid');
                } else {
                    correctAnswerHidden.value = correctAnswer;
                }
            } else {
                errors.push('Silakan pilih tipe soal terlebih dahulu.');
                typeSelect.classList.add('is-invalid');
            }

            // Validasi nilai
            const score = document.getElementById('score').value;
            if (!score) {
                errors.push('Silakan pilih nilai per soal.');
                document.getElementById('score').classList.add('is-invalid');
            }

            // Validasi pertanyaan
            const question = document.getElementById('question').value.trim();
            if (!question) {
                errors.push('Pertanyaan wajib diisi.');
                document.getElementById('question').classList.add('is-invalid');
            }

            // Jika ada error, tampilkan alert dan stop submit
            if (errors.length > 0) {
                e.preventDefault();
                alert('⚠️ ' + errors.join('\n'));
                return false;
            }

            // Disable button untuk mencegah double submit
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span> Menyimpan...';
            
            return true;
        });
    }

    // ============================================================
    // REMOVE ERROR ON INPUT
    // ============================================================
    document.querySelectorAll('input, select, textarea').forEach(el => {
        el.addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
        el.addEventListener('change', function() {
            this.classList.remove('is-invalid');
        });
    });

    // ============================================================
    // ORDER VALIDATION
    // ============================================================
    const orderInput = document.getElementById('order');
    if (orderInput) {
        orderInput.addEventListener('input', function() {
            if (this.value < 0) this.value = 0;
        });
    }

    // ============================================================
    // VALIDASI OPTIONS C DAN D TIDAK WAJIB
    // ============================================================
    document.querySelectorAll('#multipleChoiceSection input[name="options[]"]').forEach((input, index) => {
        if (index >= 2) {
            input.removeAttribute('required');
        }
    });

    // ============================================================
    // AUTO GENERATE ORDER
    // ============================================================
    // Jika order kosong atau 0, set ke max order + 1
    const maxOrder = {{ $quiz->questions()->max('order') ?? 0 }};
    if (document.getElementById('order').value == 0 || document.getElementById('order').value == '') {
        document.getElementById('order').value = maxOrder + 1;
    }
});
</script>
@endpush
@endsection
```