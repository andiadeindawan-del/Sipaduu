@extends('layouts.admin')

@section('title', 'Tambah Survey')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <div class="page-icon">
            <i class="bi bi-plus-circle"></i>
        </div>
        <div>
            <h1 class="h4 mb-1">Tambah Survey</h1>
            <p class="eyebrow">Buat survey kepuasan baru</p>
        </div>
    </div>
    <div class="heading-actions">
        <a href="{{ route('admin.survey.index') }}" class="btn btn-light">
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
                    <form action="{{ route('admin.survey.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Pilih Pelatihan <span class="text-danger">*</span></label>
                            <select name="training_id" class="form-select @error('training_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Pelatihan --</option>
                                @foreach($trainings as $training)
                                    <option value="{{ $training->id }}" {{ old('training_id') == $training->id ? 'selected' : '' }}>
                                        {{ $training->judul }}
                                    </option>
                                @endforeach
                            </select>
                            @error('training_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Judul Survey <span class="text-danger">*</span></label>
                            <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" value="{{ old('judul') }}" placeholder="Contoh: Survey Kepuasan Pelatihan A" required>
                            @error('judul')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Deskripsi Singkat</label>
                            <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="3">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft (Belum Tampil)</option>
                                <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published (Tampil)</option>
                                <option value="closed" {{ old('status') == 'closed' ? 'selected' : '' }}>Closed (Selesai/Ditutup)</option>
                            </select>
                            @error('status')
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
