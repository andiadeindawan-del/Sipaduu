@extends('layouts.peserta')

@section('title', 'Mengerjakan Quiz')

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="row">
        <div class="col-12 col-lg-8 mx-auto">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">{{ $quiz->judul }}</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('peserta.quiz.submit', ['quiz' => $quiz->id, 'attempt' => $attempt->id]) }}" method="POST">
                        @csrf
                        @foreach($questions as $index => $question)
                            <div class="mb-4">
                                <h6 class="fw-bold">{{ $index + 1 }}. {{ $question->question_text }}</h6>
                                @if($question->type === 'multiple_choice' || $question->type === 'pg' || $question->type === 'pilihan' || $question->type === 'pilihan_ganda')
                                    @php
                                        $options = $question->formatted_options;
                                    @endphp
                                    @foreach($options as $key => $opsi)
                                        @if($opsi)
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="answers[{{ $question->id }}]" id="q{{ $question->id }}_{{ $key }}" value="{{ $key }}" {{ (isset($answers[$question->id]) && $answers[$question->id] == $key) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="q{{ $question->id }}_{{ $key }}">
                                                {{ $key }}. {{ $opsi }}
                                            </label>
                                        </div>
                                        @endif
                                    @endforeach
                                @else
                                    <textarea class="form-control" name="answers[{{ $question->id }}]" rows="3">{{ $answers[$question->id] ?? '' }}</textarea>
                                @endif
                            </div>
                        @endforeach
                        
                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-success" onclick="return confirm('Yakin ingin mengumpulkan jawaban?')">
                                <i class="bi bi-check-circle me-1"></i> Kumpulkan Jawaban
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
