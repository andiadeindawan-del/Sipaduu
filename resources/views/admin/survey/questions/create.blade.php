@extends('layouts.admin')

@section('title', 'Tambah Pertanyaan Survey')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <div class="page-icon">
            <i class="bi bi-plus-circle"></i>
        </div>
        <div>
            <h1 class="h4 mb-1">Tambah Pertanyaan</h1>
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
                    <form action="{{ route('admin.survey.questions.store', $survey->id) }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Pertanyaan <span class="text-danger">*</span></label>
                            <input type="text" name="pertanyaan" class="form-control @error('pertanyaan') is-invalid @enderror" value="{{ old('pertanyaan') }}" placeholder="Contoh: Bagaimana pendapat Anda tentang materi ini?" required>
                            @error('pertanyaan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Tipe Jawaban <span class="text-danger">*</span></label>
                            <select name="tipe" class="form-select @error('tipe') is-invalid @enderror" required>
                                <option value="boolean" {{ old('tipe') == 'boolean' ? 'selected' : '' }}>Pilihan (Puas / Tidak Puas)</option>
                                <option value="rating_5" {{ old('tipe') == 'rating_5' ? 'selected' : '' }}>Rating (1 - 5 Bintang)</option>
                                <option value="text" {{ old('tipe') == 'text' ? 'selected' : '' }}>Teks / Esai Pendek</option>
                            </select>
                            @error('tipe')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text text-muted mt-1"><i class="bi bi-info-circle me-1"></i>Pilih "Pilihan" untuk kepuasan instan, Rating untuk detail tingkat, Teks untuk saran/kritik.</div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Urutan Ke- <span class="text-danger">*</span></label>
                            <input type="number" name="order" class="form-control w-25 @error('order') is-invalid @enderror" value="{{ old('order', $survey->questions->count() + 1) }}" required>
                            @error('order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
