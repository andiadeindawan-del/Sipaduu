@extends('layouts.pemateri')

@section('title', 'Edit Pertanyaan Kuis: ' . $quiz->judul)

@section('header')
<div class="page-heading d-flex justify-content-between align-items-center">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-pencil-square"></i></span>
        <div>
            <p class="eyebrow">Kuis: {{ $quiz->judul }}</p>
            <h1 class="h3 mb-0">Edit Pertanyaan</h1>
            <p class="text-muted mb-0">Perbarui butir pertanyaan atau kunci jawaban.</p>
        </div>
    </div>
    <div>
        <a href="{{ route('pemateri.quiz.show', $quiz) }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Kuis
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">

    @if(isset($errors) && $errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="panel border-0 shadow-sm">
        <div class="panel-header">
            <h5 class="section-title"><i class="bi bi-pencil" style="color: #4a6cf7;"></i> Edit Butir Pertanyaan</h5>
        </div>
        <div class="p-4">
            <form action="{{ route('pemateri.quiz.questions.update', [$quiz, $question]) }}" method="POST">
                @csrf
                @method('PUT')

                @php
                    $qType = old('type', $question->type);
                    $opts = is_array($question->options) ? $question->options : json_decode($question->options, true) ?? [];
                @endphp

                <div class="row g-3">
                    <!-- TIPE SOAL -->
                    <div class="col-12 col-md-6">
                        <label for="type" class="form-label fw-semibold">
                            Tipe Pertanyaan <span class="text-danger">*</span>
                        </label>
                        <select name="type" id="type" class="form-select" onchange="toggleQuestionType(this.value)" required>
                            <option value="multiple_choice" {{ $qType == 'multiple_choice' ? 'selected' : '' }}>Pilihan Ganda (Multiple Choice)</option>
                            <option value="true_false" {{ $qType == 'true_false' ? 'selected' : '' }}>Benar / Salah (True / False)</option>
                            <option value="essay" {{ $qType == 'essay' ? 'selected' : '' }}>Esai / Uraian</option>
                        </select>
                    </div>

                    <!-- BOBOT NILAI & ORDER -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <label for="points" class="form-label fw-semibold">Bobot Nilai / Poin <span class="text-danger">*</span></label>
                        <input type="number" name="points" id="points" class="form-control" value="{{ old('points', $question->points ?? $question->score ?? 10) }}" min="1" required>
                    </div>

                    <div class="col-12 col-sm-6 col-md-3">
                        <label for="order" class="form-label fw-semibold">Nomor Urut</label>
                        <input type="number" name="order" id="order" class="form-control" value="{{ old('order', $question->order) }}" min="1">
                    </div>

                    <!-- TEKS PERTANYAAN -->
                    <div class="col-12">
                        <label for="question" class="form-label fw-semibold">Pertanyaan / Soal <span class="text-danger">*</span></label>
                        <textarea name="question" id="question" rows="4" class="form-control" required>{{ old('question', $question->question ?? $question->pertanyaan) }}</textarea>
                    </div>

                    <!-- CONTAINER PILIHAN GANDA -->
                    <div id="multipleChoiceContainer" class="col-12" style="{{ $qType == 'multiple_choice' ? '' : 'display: none;' }}">
                        <label class="form-label fw-semibold">Pilihan Jawaban</label>
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text bg-primary text-white fw-bold">A</span>
                                    <input type="text" name="options[]" class="form-control" value="{{ old('options.0', $opts[0] ?? '') }}">
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text bg-primary text-white fw-bold">B</span>
                                    <input type="text" name="options[]" class="form-control" value="{{ old('options.1', $opts[1] ?? '') }}">
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text bg-primary text-white fw-bold">C</span>
                                    <input type="text" name="options[]" class="form-control" value="{{ old('options.2', $opts[2] ?? '') }}">
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text bg-primary text-white fw-bold">D</span>
                                    <input type="text" name="options[]" class="form-control" value="{{ old('options.3', $opts[3] ?? '') }}">
                                </div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <label for="correct_answer_mc" class="form-label fw-semibold">Kunci Jawaban Benar <span class="text-danger">*</span></label>
                            <select name="correct_answer" id="correct_answer_mc" class="form-select" style="max-width: 250px;">
                                <option value="A" {{ old('correct_answer', $question->correct_answer) == 'A' ? 'selected' : '' }}>Pilihan A</option>
                                <option value="B" {{ old('correct_answer', $question->correct_answer) == 'B' ? 'selected' : '' }}>Pilihan B</option>
                                <option value="C" {{ old('correct_answer', $question->correct_answer) == 'C' ? 'selected' : '' }}>Pilihan C</option>
                                <option value="D" {{ old('correct_answer', $question->correct_answer) == 'D' ? 'selected' : '' }}>Pilihan D</option>
                            </select>
                        </div>
                    </div>

                    <!-- CONTAINER BENAR / SALAH -->
                    <div id="trueFalseContainer" class="col-12" style="{{ $qType == 'true_false' ? '' : 'display: none;' }}">
                        <label class="form-label fw-semibold">Kunci Jawaban Benar / Salah</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="correct_answer" id="tf_true" value="Benar" {{ old('correct_answer', $question->correct_answer) == 'Benar' ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold text-success" for="tf_true">Benar</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="correct_answer" id="tf_false" value="Salah" {{ old('correct_answer', $question->correct_answer) == 'Salah' ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold text-danger" for="tf_false">Salah</label>
                            </div>
                        </div>
                    </div>

                    <!-- CONTAINER ESAI -->
                    <div id="essayContainer" class="col-12" style="{{ $qType == 'essay' ? '' : 'display: none;' }}">
                        <label for="essay_answer_key" class="form-label fw-semibold">Pedoman Kunci Jawaban Esai</label>
                        <textarea name="essay_answer_key" id="essay_answer_key" rows="3" class="form-control">{{ old('essay_answer_key', $question->essay_answer_key) }}</textarea>
                    </div>

                    <!-- SUBMIT BUTTONS -->
                    <div class="col-12 mt-4 pt-2 border-top">
                        <button type="submit" class="btn btn-primary fw-semibold px-4">
                            <i class="bi bi-save me-1"></i> Simpan Perubahan
                        </button>
                        <a href="{{ route('pemateri.quiz.show', $quiz) }}" class="btn btn-outline-secondary ms-2">
                            Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>

@push('scripts')
<script>
    function toggleQuestionType(type) {
        const mc = document.getElementById('multipleChoiceContainer');
        const tf = document.getElementById('trueFalseContainer');
        const es = document.getElementById('essayContainer');

        mc.style.display = 'none';
        tf.style.display = 'none';
        es.style.display = 'none';

        if (type === 'multiple_choice') {
            mc.style.display = 'block';
        } else if (type === 'true_false') {
            tf.style.display = 'block';
        } else if (type === 'essay') {
            es.style.display = 'block';
        }
    }
</script>
@endpush
@endsection
