@extends('layouts.pemateri')

@section('title', 'Buat Survey Baru')

@section('header')
<div class="page-heading d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-ui-radios"></i></span>
        <div>
            <p class="eyebrow">Aktivitas Pemateri</p>
            <h1 class="h3 mb-0">Buat Survey Baru</h1>
            <p class="text-muted mb-0">Rancang kuesioner evaluasi dan umpan balik untuk peserta pelatihan Anda.</p>
        </div>
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
                    <h5 class="section-title text-primary"><i class="bi bi-plus-circle me-1"></i> Formulir Survey Baru</h5>
                    <p class="text-muted small mb-0">Tentukan pelatihan dan judul survey evaluasi.</p>
                </div>
                <div class="p-4">
                    <form action="{{ route('pemateri.survey.store') }}" method="POST">
                        @csrf

                        <!-- Pelatihan -->
                        <div class="mb-3">
                            <label for="training_id" class="form-label fw-semibold">
                                Pelatihan yang Diampu <span class="text-danger">*</span>
                            </label>
                            <select name="training_id" id="training_id" class="form-select @error('training_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Pelatihan --</option>
                                @foreach($trainings as $training)
                                    <option value="{{ $training->id }}" {{ (old('training_id', $selectedTrainingId) == $training->id) ? 'selected' : '' }}>
                                        {{ $training->judul }} ({{ $training->kategori->nama ?? 'Umum' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('training_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text small text-muted">Hanya menampilkan pelatihan yang ditugaskan kepada Anda.</div>
                        </div>

                        <!-- Judul Survey -->
                        <div class="mb-3">
                            <label for="judul" class="form-label fw-semibold">
                                Judul Survey <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="judul" id="judul" class="form-control @error('judul') is-invalid @enderror" placeholder="Contoh: Evaluasi Kepuasan Materi dan Fasilitator Pelatihan Batik" value="{{ old('judul') }}" required>
                            @error('judul')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label fw-semibold">Deskripsi / Petunjuk Pengisian</label>
                            <textarea name="deskripsi" id="deskripsi" rows="4" class="form-control @error('deskripsi') is-invalid @enderror" placeholder="Jelaskan tujuan survey dan instruksi pengisian bagi peserta...">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="mb-4">
                            <label for="status" class="form-label fw-semibold">Status Ketersediaan <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft (Disimpan sebagai draf, belum dapat diisi peserta)</option>
                                <option value="published" {{ old('status', 'published') === 'published' ? 'selected' : '' }}>Published (Langsung aktif dan dapat diisi oleh peserta pelatihan)</option>
                                <option value="closed" {{ old('status') === 'closed' ? 'selected' : '' }}>Closed (Ditutup / Selesai)</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2 border-top pt-3">
                            <a href="{{ route('pemateri.survey.index') }}" class="btn btn-light px-4">Batal</a>
                            <button type="submit" class="btn btn-primary fw-semibold px-4">
                                <i class="bi bi-arrow-right-circle me-1"></i> Simpan &amp; Lanjutkan ke Pertanyaan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
