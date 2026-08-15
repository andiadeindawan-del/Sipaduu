@extends('layouts.admin')

@section('title', 'Detail Pertanyaan')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-list-ol"></i></span>
        <div>
            <p class="eyebrow">Manajemen</p>
            <h1 class="h3 mb-0">Detail Pertanyaan</h1>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="row">
        <div class="col-12 col-lg-8 mx-auto">
            <div class="panel">
                <div class="panel-header">
                    <h5 class="section-title"><i class="bi bi-info-circle"></i> Detail Pertanyaan</h5>
                    <span class="badge text-bg-primary">
                        Quiz: {{ $question->quiz->judul ?? 'N/A' }}
                    </span>
                </div>
                <div class="p-4">
                    <div class="row g-4">
                        <!-- Tipe & Nilai -->
                        <div class="col-12 col-md-6">
                            <div class="info-item p-3 bg-light rounded-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="icon-circle bg-info text-white">
                                        <i class="bi bi-tag"></i>
                                    </div>
                                    <div>
                                        <label class="text-muted small fw-semibold text-uppercase d-block">Tipe Pertanyaan</label>
                                        <p class="fw-semibold mb-0">
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
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="info-item p-3 bg-light rounded-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="icon-circle bg-warning text-white">
                                        <i class="bi bi-star"></i>
                                    </div>
                                    <div>
                                        <label class="text-muted small fw-semibold text-uppercase d-block">Nilai</label>
                                        <p class="fw-semibold mb-0">{{ $question->points ?? $question->score ?? 1 }} poin</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pertanyaan -->
                        <div class="col-12">
                            <div class="info-item p-3 bg-light rounded-3">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="icon-circle bg-primary text-white">
                                        <i class="bi bi-text-paragraph"></i>
                                    </div>
                                    <div>
                                        <label class="text-muted small fw-semibold text-uppercase d-block">Pertanyaan</label>
                                        <p class="fw-semibold mb-0 fs-5">{{ $question->question }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pilihan Jawaban (Multiple Choice) -->
                        @if(isset($question->type) && in_array($question->type, ['multiple_choice', 'pilihan', 'pilihan_ganda']))
                        <div class="col-12">
                            <div class="info-item p-3 bg-light rounded-3">
                                <label class="text-muted small fw-semibold text-uppercase d-block mb-2">
                                    <i class="bi bi-list-check me-1"></i> Pilihan Jawaban
                                </label>
                                @php
                                    $options = is_array($question->options) ? $question->options : json_decode($question->options, true);
                                @endphp
                                @if(is_array($options) && count($options) > 0)
                                    @foreach($options as $key => $option)
                                    <div class="d-flex align-items-center gap-2 p-2 border-bottom">
                                        <span class="badge text-bg-secondary">{{ chr(65 + $key) }}</span>
                                        <span>{{ $option }}</span>
                                        @if($question->correct_answer == chr(65 + $key))
                                        <span class="badge text-bg-success ms-auto">
                                            <i class="bi bi-check-circle"></i> Jawaban Benar
                                        </span>
                                        @endif
                                    </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                        <!-- Jawaban Benar -->
                        <div class="col-12 col-md-6">
                            <div class="info-item p-3 bg-light rounded-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="icon-circle bg-success text-white">
                                        <i class="bi bi-check-circle"></i>
                                    </div>
                                    <div>
                                        <label class="text-muted small fw-semibold text-uppercase d-block">Jawaban Benar</label>
                                        <p class="fw-semibold mb-0">{{ $question->correct_answer }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @elseif(isset($question->type) && $question->type == 'essay')
                        <!-- Kunci Jawaban Essay -->
                        <div class="col-12">
                            <div class="info-item p-3 bg-light rounded-3">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="icon-circle bg-secondary text-white">
                                        <i class="bi bi-file-text"></i>
                                    </div>
                                    <div>
                                        <label class="text-muted small fw-semibold text-uppercase d-block">Kunci Jawaban Essay</label>
                                        <p class="mb-0">{{ $question->essay_answer_key ?? 'Tidak ada kunci jawaban' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Info Tambahan -->
                        <div class="col-12">
                            <hr>
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <div class="info-item p-3 bg-light rounded-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="icon-circle bg-secondary text-white">
                                                <i class="bi bi-list-ol"></i>
                                            </div>
                                            <div>
                                                <label class="text-muted small fw-semibold text-uppercase d-block">Urutan</label>
                                                <p class="fw-semibold mb-0">{{ $question->order ?? $loop->iteration }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="info-item p-3 bg-light rounded-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="icon-circle bg-info text-white">
                                                <i class="bi bi-clock"></i>
                                            </div>
                                            <div>
                                                <label class="text-muted small fw-semibold text-uppercase d-block">Dibuat</label>
                                                <p class="fw-semibold mb-0">{{ $question->created_at ? $question->created_at->format('d/m/Y H:i') : '-' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="info-item p-3 bg-light rounded-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="icon-circle bg-warning text-white">
                                                <i class="bi bi-clock-history"></i>
                                            </div>
                                            <div>
                                                <label class="text-muted small fw-semibold text-uppercase d-block">Diperbarui</label>
                                                <p class="fw-semibold mb-0">{{ $question->updated_at ? $question->updated_at->format('d/m/Y H:i') : '-' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="col-12">
                            <hr>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('admin.quiz.questions.edit', ['quiz' => $question->quiz_id, 'question' => $question->id]) }}" class="btn btn-warning">
                                    <i class="bi bi-pencil me-1"></i> Edit
                                </a>
                                <form action="{{ route('admin.quiz.questions.destroy', ['quiz' => $question->quiz_id, 'question' => $question->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus pertanyaan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="bi bi-trash me-1"></i> Hapus
                                    </button>
                                </form>
                                <div class="ms-auto">
                                    <a href="{{ route('admin.quiz.questions.index', $question->quiz_id) }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-arrow-left me-1"></i> Kembali
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .icon-circle {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .icon-circle i {
        font-size: 20px;
    }
    .info-item {
        transition: all 0.2s ease;
    }
    .info-item:hover {
        background-color: #e9ecef !important;
    }
    .bg-primary { background-color: #0d6efd; }
    .bg-success { background-color: #198754; }
    .bg-info { background-color: #0dcaf0; }
    .bg-warning { background-color: #ffc107; }
    .bg-secondary { background-color: #6c757d; }
    .bg-danger { background-color: #dc3545; }
    .text-white { color: #fff; }
</style>
@endpush
@endsection