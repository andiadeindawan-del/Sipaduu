@extends('layouts.pemateri')

@section('title', 'Daftar Pertanyaan Survey: ' . $survey->judul)

@section('header')
<div class="page-heading d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-list-check"></i></span>
        <div>
            <p class="eyebrow">Aktivitas Pemateri</p>
            <h1 class="h3 mb-0">Pertanyaan Survey</h1>
            <p class="text-muted mb-0">Survey: <strong>{{ $survey->judul }}</strong></p>
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('pemateri.survey.questions.create', $survey->id) }}" class="btn btn-primary btn-sm fw-semibold">
            <i class="bi bi-plus-circle me-1"></i> Tambah Pertanyaan
        </a>
        <a href="{{ route('pemateri.survey.show', $survey->id) }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Detail Survey
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="panel border-0 shadow-sm">
        <div class="panel-header d-flex justify-content-between align-items-center">
            <h5 class="section-title"><i class="bi bi-list-check text-primary"></i> Daftar Pertanyaan ({{ $survey->questions->count() }})</h5>
            <a href="{{ route('pemateri.survey.questions.create', $survey->id) }}" class="btn btn-primary btn-sm fw-semibold">
                <i class="bi bi-plus-circle me-1"></i> Tambah Pertanyaan
            </a>
        </div>
        <div class="p-4">
            @if($survey->questions->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-ui-radios fs-1 d-block mb-3 text-secondary"></i>
                    <h5 class="fw-bold text-dark">Belum Ada Pertanyaan</h5>
                    <p class="small text-muted mb-3">Survey ini belum memiliki pertanyaan evaluasi.</p>
                    <a href="{{ route('pemateri.survey.questions.create', $survey->id) }}" class="btn btn-primary fw-semibold">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Pertanyaan Pertama
                    </a>
                </div>
            @else
                <div class="d-flex flex-column gap-3">
                    @foreach($survey->questions as $index => $q)
                        <div class="card border rounded-3 p-3 shadow-none">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-primary rounded-circle p-2" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center;">
                                        {{ $index + 1 }}
                                    </span>
                                    @if($q->tipe === 'rating_5')
                                        <span class="badge bg-warning bg-opacity-10 text-dark border border-warning border-opacity-25 small">
                                            <i class="bi bi-star-fill text-warning me-1"></i> Rating 1 - 5
                                        </span>
                                    @elseif($q->tipe === 'boolean')
                                        <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 small">
                                            <i class="bi bi-toggle-on me-1"></i> Ya / Tidak
                                        </span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 small">
                                            <i class="bi bi-chat-left-text me-1"></i> Teks / Esai
                                        </span>
                                    @endif
                                </div>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('pemateri.survey.questions.edit', [$survey->id, $q->id]) }}" class="btn btn-outline-secondary" title="Edit Pertanyaan">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <form action="{{ route('pemateri.survey.questions.destroy', [$survey->id, $q->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus pertanyaan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="fw-semibold text-dark ps-1">
                                {!! nl2br(e($q->pertanyaan)) !!}
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
