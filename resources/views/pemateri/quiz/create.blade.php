@extends('layouts.pemateri')

@section('title', 'Tambah Kuis Baru')

@section('header')
<div class="page-heading d-flex justify-content-between align-items-center">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-question-circle"></i></span>
        <div>
            <p class="eyebrow">Aktivitas Pemateri</p>
            <h1 class="h3 mb-0">Tambah Kuis Baru</h1>
            <p class="text-muted mb-0">Buat kuis evaluasi baru untuk pelatihan.</p>
        </div>
    </div>
    <div>
        <a href="{{ route('pemateri.quiz.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
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

    @if($trainings->isEmpty())
        <div class="alert alert-warning mb-4">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Belum ada pelatihan yang ditugaskan:</strong> Anda belum ditugaskan pada pelatihan apapun oleh Administrator. Anda memerlukan minimal 1 pelatihan yang ditugaskan agar dapat membuat kuis evaluasi.
        </div>
    @endif

    <div class="panel border-0 shadow-sm">
        <div class="panel-header">
            <h5 class="section-title"><i class="bi bi-plus-circle" style="color: #fd7e14;"></i> Formulir Pembuatan Kuis</h5>
        </div>
        <div class="p-4">
            <form action="{{ route('pemateri.quiz.store') }}" method="POST">
                @csrf

                <div class="row g-3">
                    <!-- PILIH PELATIHAN -->
                    <div class="col-12 col-md-6">
                        <label for="training_id" class="form-label fw-semibold">
                            Pilih Pelatihan <span class="text-danger">*</span>
                        </label>
                        <select name="training_id" id="training_id" class="form-select @error('training_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Pelatihan --</option>
                            @foreach($trainings as $training)
                                <option value="{{ $training->id }}" {{ old('training_id', $selectedTrainingId) == $training->id ? 'selected' : '' }}>
                                    {{ $training->judul }}
                                </option>
                            @endforeach
                        </select>
                        @error('training_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- HUBUNGKAN DENGAN MATERI (OPSIONAL) -->
                    <div class="col-12 col-md-6">
                        <label for="materi_id" class="form-label fw-semibold">
                            Terkait Materi (Opsional)
                        </label>
                        <select name="materi_id" id="materi_id" class="form-select">
                            <option value="">-- Umum / Seluruh Pelatihan --</option>
                            @foreach($materis as $materi)
                                <option value="{{ $materi->id }}" {{ old('materi_id', $selectedMateriId) == $materi->id ? 'selected' : '' }}>
                                    {{ $materi->judul }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- JUDUL KUIS -->
                    <div class="col-12">
                        <label for="judul" class="form-label fw-semibold">
                            Judul Kuis <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="judul" id="judul" class="form-control @error('judul') is-invalid @enderror" 
                               value="{{ old('judul') }}" placeholder="Contoh: Kuis Evaluasi Modul 1" required>
                        @error('judul')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- DESKRIPSI KUIS -->
                    <div class="col-12">
                        <label for="deskripsi" class="form-label fw-semibold">Petunjuk / Deskripsi Kuis</label>
                        <textarea name="deskripsi" id="deskripsi" rows="3" class="form-control" 
                                  placeholder="Tuliskan petunjuk pengerjaan kuis untuk peserta...">{{ old('deskripsi') }}</textarea>
                    </div>

                    <!-- DURASI (MENIT) -->
                    <div class="col-12 col-md-4">
                        <label for="durasi" class="form-label fw-semibold">Durasi Pengerjaan (Menit)</label>
                        <input type="number" name="durasi" id="durasi" class="form-control" value="{{ old('durasi', 30) }}" min="1">
                        <small class="text-muted">Biarkan jika waktu tidak dibatasi.</small>
                    </div>

                    <!-- PASSING SCORE -->
                    <div class="col-12 col-md-4">
                        <label for="passing_score" class="form-label fw-semibold">
                            Nilai Kelulusan (Passing Score) <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <input type="number" name="passing_score" id="passing_score" class="form-control" value="{{ old('passing_score', 70) }}" min="0" max="100" required>
                            <span class="input-group-text">%</span>
                        </div>
                    </div>

                    <!-- MAX ATTEMPT -->
                    <div class="col-12 col-md-4">
                        <label for="max_attempt" class="form-label fw-semibold">
                            Maksimal Percobaan <span class="text-danger">*</span>
                        </label>
                        <input type="number" name="max_attempt" id="max_attempt" class="form-control" value="{{ old('max_attempt', 3) }}" min="1" max="10" required>
                    </div>

                    <!-- OPSI KUIS -->
                    <div class="col-12 col-md-6">
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="is_random" id="is_random" value="1" {{ old('is_random', true) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="is_random">Acak Urutan Soal Pertanyaan</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="show_result" id="show_result" value="1" {{ old('show_result', true) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="show_result">Tampilkan Hasil Nilai Langsung ke Peserta</label>
                        </div>
                    </div>

                    <!-- STATUS & ORDER -->
                    <div class="col-12 col-md-3">
                        <label for="status" class="form-label fw-semibold">
                            Status <span class="text-danger">*</span>
                        </label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="published" {{ old('status', 'published') == 'published' ? 'selected' : '' }}>Published (Dapat dikerjakan)</option>
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft (Simpan draf)</option>
                        </select>
                    </div>

                    <div class="col-12 col-md-3">
                        <label for="order" class="form-label fw-semibold">Urutan Kuis</label>
                        <input type="number" name="order" id="order" class="form-control" value="{{ old('order', 1) }}" min="0">
                    </div>

                    <!-- SUBMIT BUTTONS -->
                    <div class="col-12 mt-4 pt-2 border-top">
                        <button type="submit" class="btn btn-warning text-dark fw-semibold px-4 shadow-sm">
                            <i class="bi bi-save me-1"></i> Simpan Kuis & Lanjut Buat Soal
                        </button>
                        <a href="{{ route('pemateri.quiz.index') }}" class="btn btn-outline-secondary ms-2">
                            Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
