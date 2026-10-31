@extends('layouts.pemateri')

@section('title', 'Edit Dokumentasi: ' . $dokumentasi->judul)

@section('header')
<div class="page-heading d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-images"></i></span>
        <div>
            <p class="eyebrow">Aktivitas Pemateri</p>
            <h1 class="h3 mb-0">Edit Dokumentasi</h1>
            <p class="text-muted mb-0">Perbarui tautan atau keterangan dokumentasi pelatihan.</p>
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('pemateri.dokumentasi.show', $dokumentasi) }}" class="btn btn-outline-info btn-sm rounded-pill px-3">
            <i class="bi bi-eye me-1"></i> Lihat Detail
        </a>
        <a href="{{ route('pemateri.dokumentasi.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
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
                    <h5 class="section-title text-warning"><i class="bi bi-pencil-square me-1"></i> Edit Dokumentasi</h5>
                    <p class="text-muted small mb-0">Perbarui informasi dokumentasi <strong>{{ $dokumentasi->judul }}</strong></p>
                </div>
                <div class="p-4">
                    <form action="{{ route('pemateri.dokumentasi.update', $dokumentasi) }}" method="POST">
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
                                    <option value="{{ $training->id }}" {{ (old('training_id', $dokumentasi->training_id) == $training->id) ? 'selected' : '' }}>
                                        {{ $training->judul }} ({{ $training->kategori->nama ?? 'Umum' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('training_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Judul Dokumentasi -->
                        <div class="mb-3">
                            <label for="judul" class="form-label fw-semibold">
                                Judul Dokumentasi <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="judul" id="judul" class="form-control @error('judul') is-invalid @enderror" value="{{ old('judul', $dokumentasi->judul) }}" required>
                            @error('judul')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Link Dokumentasi -->
                        <div class="mb-3">
                            <label for="link" class="form-label fw-semibold">
                                Tautan / Link Arsip <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-link-45deg"></i></span>
                                <input type="url" name="link" id="link" class="form-control @error('link') is-invalid @enderror" value="{{ old('link', $dokumentasi->link) }}" required>
                            </div>
                            @error('link')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-4">
                            <label for="deskripsi" class="form-label fw-semibold">Keterangan / Deskripsi Singkat</label>
                            <textarea name="deskripsi" id="deskripsi" rows="3" class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $dokumentasi->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2 border-top pt-3">
                            <a href="{{ route('pemateri.dokumentasi.index') }}" class="btn btn-light px-4">Batal</a>
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
