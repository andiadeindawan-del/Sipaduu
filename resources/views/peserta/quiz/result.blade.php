@extends('layouts.peserta')

@section('title', 'Hasil Quiz')

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="row">
        <div class="col-12 col-lg-8 mx-auto">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Hasil Quiz: {{ $quiz->judul }}</h5>
                </div>
                <div class="card-body p-4 text-center">
                    <h4 class="mb-3">Skor Anda</h4>
                    <h1 class="display-1 fw-bold {{ $attempt->score >= $quiz->passing_score ? 'text-success' : 'text-danger' }}">
                        {{ $attempt->score ?? 0 }}
                    </h1>
                    
                    <div class="mt-4">
                        @if($attempt->score >= $quiz->passing_score)
                            <div class="alert alert-success">
                                <i class="bi bi-check-circle-fill me-2"></i> Selamat! Anda telah lulus quiz ini.
                            </div>
                        @else
                            <div class="alert alert-danger">
                                <i class="bi bi-x-circle-fill me-2"></i> Maaf, skor Anda belum mencapai batas kelulusan ({{ $quiz->passing_score }}).
                            </div>
                        @endif
                    </div>
                    
                    <div class="mt-4 d-flex justify-content-center gap-2">
                        <a href="{{ route('peserta.quiz.show', $quiz->id) }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Kembali ke Detail Quiz
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
