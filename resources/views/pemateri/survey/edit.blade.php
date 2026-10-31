@extends('layouts.pemateri')

@section('title', 'Edit Survey: ' . $survey->judul)

@section('header')
<div class="page-heading d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-ui-radios"></i></span>
        <div>
            <p class="eyebrow">Aktivitas Pemateri</p>
            <h1 class="h3 mb-0">Edit Survey</h1>
            <p class="text-muted mb-0">Perbarui informasi dan pengaturan kuesioner survey.</p>
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('pemateri.survey.show', $survey) }}" class="btn btn-outline-info btn-sm rounded-pill px-3">
            <i class="bi bi-eye me-1"></i> Lihat Detail Survey
        </a>
        <a href="{{ route('pemateri.survey.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
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
                    <strong>Terdapat kesalahan!</strong> Silakan periksa formulir berikut:
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
                    <h5 class="section-title text-warning"><i class="bi bi-pencil-square me-1"></i> Edit Survey</h5>
                    <p class="text-muted small mb-0">Perbarui data survey <strong>{{ $survey->judul }}</strong></p>
                </div>
                <div class="p-4">
                    <form action="{{ route('pemateri.survey.update', $survey) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Pelatihan -->
                        <div class="mb-3">
                            <label for="training_id" class="form-label fw-semibold">
                                Pelatihan yang Diampu <span class="text-danger">*</span>
                            </label>
                            <select name="training_id" id="training_id" class="form-select @error('training_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Pelatihan --</option>
                                @foreach($trainings as $training)
                                    <option value="{{ $training->id }}" {{ (old('training_id', $survey->training_id) == $training->id) ? 'selected' : '' }}>
                                        {{ $training->judul }} ({{ $training->kategori->nama ?? 'Umum' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('training_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Judul Survey -->
                        <div class="mb-3">
                            <label for="judul" class="form-label fw-semibold">
                                Judul Survey <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="judul" id="judul" class="form-control @error('judul') is-invalid @enderror" value="{{ old('judul', $survey->judul) }}" required>
                            @error('judul')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label fw-semibold">Deskripsi / Petunjuk Pengisian</label>
                            <textarea name="deskripsi" id="deskripsi" rows="4" class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $survey->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="mb-4">
                            <label for="status" class="form-label fw-semibold">Status Ketersediaan <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="draft" {{ old('status', $survey->status) === 'draft' ? 'selected' : '' }}>Draft (Disimpan sebagai draf)</option>
                                <option value="published" {{ old('status', $survey->status) === 'published' ? 'selected' : '' }}>Published (Aktif &amp; dapat diisi peserta)</option>
                                <option value="closed" {{ old('status', $survey->status) === 'closed' ? 'selected' : '' }}>Closed (Ditutup)</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2 border-top pt-3">
                            <a href="{{ route('pemateri.survey.show', $survey) }}" class="btn btn-light px-4">Batal</a>
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
