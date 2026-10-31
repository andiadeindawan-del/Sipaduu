@extends('layouts.pemateri')

@section('title', 'Edit Pertanyaan Survey: ' . $survey->judul)

@section('header')
<div class="page-heading d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-question-circle"></i></span>
        <div>
            <p class="eyebrow">Aktivitas Pemateri</p>
            <h1 class="h3 mb-0">Edit Pertanyaan Survey</h1>
            <p class="text-muted mb-0">Survey: <strong>{{ $survey->judul }}</strong></p>
        </div>
    </div>
    <div>
        <a href="{{ route('pemateri.survey.show', $survey->id) }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Detail Survey
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="row">
        <div class="col-12 col-lg-8 mx-auto">
            @if(isset($errors) && $errors->any())
                <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <strong>Terdapat kesalahan!</strong> Silakan periksa kembali:
                    <ul class="mb-0 mt-2 small">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="panel border-0 shadow-sm rounded-3">
                <div class="panel-header py-3 px-4 border-bottom">
                    <h5 class="section-title text-warning"><i class="bi bi-pencil-square me-1"></i> Edit Butir Pertanyaan</h5>
                    <p class="text-muted small mb-0">Perbarui pertanyaan survey.</p>
                </div>
                <div class="p-4">
                    <form action="{{ route('pemateri.survey.questions.update', [$survey->id, $question->id]) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Teks Pertanyaan -->
                        <div class="mb-3">
                            <label for="pertanyaan" class="form-label fw-semibold">
                                Butir Pertanyaan <span class="text-danger">*</span>
                            </label>
                            <textarea name="pertanyaan" id="pertanyaan" rows="3" class="form-control @error('pertanyaan') is-invalid @enderror" required>{{ old('pertanyaan', $question->pertanyaan) }}</textarea>
                            @error('pertanyaan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tipe Respon -->
                        <div class="mb-3">
                            <label for="tipe" class="form-label fw-semibold">
                                Tipe Format Respon <span class="text-danger">*</span>
                            </label>
                            <select name="tipe" id="tipe" class="form-select @error('tipe') is-invalid @enderror" required>
                                <option value="rating_5" {{ old('tipe', $question->tipe) === 'rating_5' ? 'selected' : '' }}>
                                    ⭐ Rating Skala 1 - 5 (Sangat Tidak Puas s/d Sangat Puas)
                                </option>
                                <option value="boolean" {{ old('tipe', $question->tipe) === 'boolean' ? 'selected' : '' }}>
                                    🔘 Pilihan Ya / Tidak (Biner)
                                </option>
                                <option value="text" {{ old('tipe', $question->tipe) === 'text' ? 'selected' : '' }}>
                                    ✍️ Teks Bebas / Esai (Uraian Masukan & Saran)
                                </option>
                            </select>
                            @error('tipe')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Urutan -->
                        <div class="mb-4">
                            <label for="order" class="form-label fw-semibold">Urutan Nomor</label>
                            <input type="number" name="order" id="order" class="form-control @error('order') is-invalid @enderror" value="{{ old('order', $question->order) }}" min="1" style="max-width: 150px;">
                            @error('order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2 border-top pt-3">
                            <a href="{{ route('pemateri.survey.show', $survey->id) }}" class="btn btn-light px-4">Batal</a>
                            <button type="submit" class="btn btn-warning text-dark fw-semibold px-4">
                                <i class="bi bi-check-circle me-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
