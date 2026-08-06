@extends('layouts.admin')

@section('title', 'Edit Pertanyaan Survey')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <div class="page-icon">
            <i class="bi bi-pencil-square"></i>
        </div>
        <div>
            <h1 class="h4 mb-1">Edit Pertanyaan</h1>
            <p class="eyebrow">Survey: {{ $survey->judul }}</p>
        </div>
    </div>
    <div class="heading-actions">
        <a href="{{ route('admin.survey.show', $survey->id) }}" class="btn btn-light">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid px-3 px-lg-4 pb-4">
    <div class="row">
        <div class="col-lg-8">
            <div class="panel">
                <div class="panel-body p-4">
                    <form action="{{ route('admin.survey.questions.update', [$survey->id, $question->id]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Pertanyaan <span class="text-danger">*</span></label>
                            <input type="text" name="pertanyaan" class="form-control @error('pertanyaan') is-invalid @enderror" value="{{ old('pertanyaan') ?? $question->pertanyaan }}" required>
                            @error('pertanyaan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Tipe Jawaban <span class="text-danger">*</span></label>
                            <select name="tipe" class="form-select @error('tipe') is-invalid @enderror" required>
                                <option value="boolean" {{ (old('tipe') ?? $question->tipe) == 'boolean' ? 'selected' : '' }}>Pilihan (Puas / Tidak Puas)</option>
                                <option value="rating_5" {{ (old('tipe') ?? $question->tipe) == 'rating_5' ? 'selected' : '' }}>Rating (1 - 5 Bintang)</option>
                                <option value="text" {{ (old('tipe') ?? $question->tipe) == 'text' ? 'selected' : '' }}>Teks / Esai Pendek</option>
                            </select>
                            @error('tipe')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Urutan Ke- <span class="text-danger">*</span></label>
                            <input type="number" name="order" class="form-control w-25 @error('order') is-invalid @enderror" value="{{ old('order') ?? $question->order }}" required>
                            @error('order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
