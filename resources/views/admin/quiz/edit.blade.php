@extends('layouts.admin')

@section('title', 'Edit Quiz')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-pencil-square"></i></span>
        <div>
            <p class="eyebrow">Manajemen</p>
            <h1 class="h3 mb-0">Edit Quiz</h1>
            <p class="text-muted mb-0">Perbarui informasi quiz: {{ $quiz->judul }}</p>
        </div>
    </div>
    <div class="heading-actions">
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('admin.quiz.show', $quiz->id) }}" class="btn btn-info btn-sm">
                <i class="bi bi-eye"></i> Detail
            </a>
            <a href="{{ route('admin.quiz.questions.index', $quiz->id) }}" class="btn btn-secondary btn-sm">
                <i class="bi bi-list-ol"></i> Pertanyaan
            </a>
            <a href="{{ route('admin.quiz.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="row">
        <div class="col-12 col-lg-10 mx-auto">
            <!-- Alert Errors -->
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Terjadi kesalahan!</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="panel">
                <div class="panel-header">
                    <h5 class="section-title"><i class="bi bi-pencil-square"></i> Form Edit Quiz</h5>
                    <p class="text-muted small mb-0">Perbarui data quiz.</p>
                </div>
                <div class="p-4">
                    <form action="{{ route('admin.quiz.update', $quiz->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <!-- Training -->
                            <div class="col-12 col-md-6">
                                <label for="training_id" class="form-label fw-semibold">
                                    Training
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-journal-bookmark"></i></span>
                                    <select class="form-select @error('training_id') is-invalid @enderror" 
                                            id="training_id" name="training_id">
                                        <option value="">Pilih Training (Opsional)</option>
                                        @foreach($trainings ?? [] as $training)
                                        <option value="{{ $training->id }}" {{ old('training_id', $quiz->training_id) == $training->id ? 'selected' : '' }}>
                                            {{ $training->judul }}
                                            @if($training->status)
                                                <span class="text-muted">({{ $training->status_label }})</span>
                                            @endif
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <small class="text-muted">Kosongkan jika quiz tidak terkait dengan training tertentu.</small>
                                @error('training_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Materi -->
                            <div class="col-12 col-md-6">
                                <label for="materi_id" class="form-label fw-semibold">
                                    Materi
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-book"></i></span>
                                    <select class="form-select @error('materi_id') is-invalid @enderror" 
                                            id="materi_id" name="materi_id">
                                        <option value="">Pilih Materi (Opsional)</option>
                                        @foreach($materis ?? [] as $materi)
                                        <option value="{{ $materi->id }}" {{ old('materi_id', $quiz->materi_id) == $materi->id ? 'selected' : '' }}>
                                            {{ $materi->judul }}
                                            @if($materi->status)
                                                <span class="text-muted">({{ $materi->status_label }})</span>
                                            @endif
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <small class="text-muted">Kosongkan jika quiz tidak terkait dengan materi tertentu.</small>
                                @error('materi_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Judul -->
                            <div class="col-12">
                                <label for="judul" class="form-label fw-semibold">
                                    Judul Quiz <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-text-paragraph"></i></span>
                                    <input type="text" class="form-control @error('judul') is-invalid @enderror" 
                                           id="judul" name="judul" value="{{ old('judul', $quiz->judul) }}" 
                                           placeholder="Masukkan judul quiz" required>
                                </div>
                                @error('judul')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Deskripsi -->
                            <div class="col-12">
                                <label for="deskripsi" class="form-label fw-semibold">
                                    Deskripsi
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-file-text"></i></span>
                                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                              id="deskripsi" name="deskripsi" rows="3" 
                                              placeholder="Deskripsi quiz (opsional)">{{ old('deskripsi', $quiz->deskripsi) }}</textarea>
                                </div>
                                @error('deskripsi')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- ========================================================== -->
                            <!-- PENGATURAN -->
                            <!-- ========================================================== -->
                            <div class="col-12">
                                <hr class="my-2">
                                <h6 class="fw-semibold text-muted">
                                    <i class="bi bi-gear me-2"></i>Pengaturan Quiz
                                </h6>
                            </div>

                            <!-- Durasi -->
                            <div class="col-12 col-md-4">
                                <label for="durasi" class="form-label fw-semibold">
                                    Durasi (menit)
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-clock"></i></span>
                                    <input type="number" class="form-control @error('durasi') is-invalid @enderror" 
                                           id="durasi" name="durasi" value="{{ old('durasi', $quiz->durasi) }}" 
                                           placeholder="30" min="1">
                                </div>
                                <small class="text-muted">Kosongkan jika tidak ada batas waktu.</small>
                                @error('durasi')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Passing Score -->
                            <div class="col-12 col-md-4">
                                <label for="passing_score" class="form-label fw-semibold">
                                    Passing Score (%) <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-percent"></i></span>
                                    <input type="number" class="form-control @error('passing_score') is-invalid @enderror" 
                                           id="passing_score" name="passing_score" 
                                           value="{{ old('passing_score', $quiz->passing_score ?? 70) }}" 
                                           placeholder="70" min="0" max="100" required>
                                </div>
                                <small class="text-muted">Nilai minimal untuk dinyatakan lulus.</small>
                                @error('passing_score')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Max Attempt -->
                            <div class="col-12 col-md-4">
                                <label for="max_attempt" class="form-label fw-semibold">
                                    Maksimal Percobaan <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-arrow-repeat"></i></span>
                                    <input type="number" class="form-control @error('max_attempt') is-invalid @enderror" 
                                           id="max_attempt" name="max_attempt" 
                                           value="{{ old('max_attempt', $quiz->max_attempt ?? 1) }}" 
                                           placeholder="1" min="1" max="10" required>
                                </div>
                                <small class="text-muted">Maksimal kali peserta dapat mengerjakan quiz.</small>
                                @error('max_attempt')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Is Random -->
                            <div class="col-12 col-md-6">
                                <div class="form-check form-switch">
                                    <input type="hidden" name="is_random" value="0">
                                    <input class="form-check-input @error('is_random') is-invalid @enderror" 
                                           type="checkbox" id="is_random" name="is_random" value="1"
                                           {{ old('is_random', $quiz->is_random) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="is_random">
                                        <i class="bi bi-shuffle text-primary me-1"></i>
                                        Acak Pertanyaan
                                    </label>
                                    <small class="d-block text-muted">Pertanyaan akan ditampilkan secara acak.</small>
                                </div>
                                @error('is_random')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Show Result -->
                            <div class="col-12 col-md-6">
                                <div class="form-check form-switch">
                                    <input type="hidden" name="show_result" value="0">
                                    <input class="form-check-input @error('show_result') is-invalid @enderror" 
                                           type="checkbox" id="show_result" name="show_result" value="1"
                                           {{ old('show_result', $quiz->show_result) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="show_result">
                                        <i class="bi bi-eye text-success me-1"></i>
                                        Tampilkan Hasil
                                    </label>
                                    <small class="d-block text-muted">Peserta dapat melihat hasil setelah selesai.</small>
                                </div>
                                @error('show_result')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- ========================================================== -->
                            <!-- JADWAL -->
                            <!-- ========================================================== -->
                            <div class="col-12">
                                <hr class="my-2">
                                <h6 class="fw-semibold text-muted">
                                    <i class="bi bi-calendar me-2"></i>Jadwal Quiz
                                </h6>
                            </div>

                            <!-- Start Date -->
                            <div class="col-12 col-md-6">
                                <label for="start_date" class="form-label fw-semibold">
                                    Tanggal Mulai
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-calendar-check"></i></span>
                                    <input type="datetime-local" class="form-control @error('start_date') is-invalid @enderror" 
                                           id="start_date" name="start_date" 
                                           value="{{ old('start_date', $quiz->start_date ? $quiz->start_date->format('Y-m-d\TH:i') : '') }}">
                                </div>
                                <small class="text-muted">Kosongkan jika tidak ada batas mulai.</small>
                                @error('start_date')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- End Date -->
                            <div class="col-12 col-md-6">
                                <label for="end_date" class="form-label fw-semibold">
                                    Tanggal Selesai
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-calendar-x"></i></span>
                                    <input type="datetime-local" class="form-control @error('end_date') is-invalid @enderror" 
                                           id="end_date" name="end_date" 
                                           value="{{ old('end_date', $quiz->end_date ? $quiz->end_date->format('Y-m-d\TH:i') : '') }}">
                                </div>
                                <small class="text-muted">Kosongkan jika tidak ada batas akhir.</small>
                                @error('end_date')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- ========================================================== -->
                            <!-- STATUS & URUTAN -->
                            <!-- ========================================================== -->
                            <div class="col-12">
                                <hr class="my-2">
                                <h6 class="fw-semibold text-muted">
                                    <i class="bi bi-toggle-on me-2"></i>Status & Urutan
                                </h6>
                            </div>

                            <!-- Status -->
                            <div class="col-12 col-md-6">
                                <label for="status" class="form-label fw-semibold">
                                    Status <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-toggle-on"></i></span>
                                    <select class="form-select @error('status') is-invalid @enderror" 
                                            id="status" name="status" required>
                                        <option value="draft" {{ old('status', $quiz->status) == 'draft' ? 'selected' : '' }}>📝 Draft</option>
                                        <option value="published" {{ old('status', $quiz->status) == 'published' ? 'selected' : '' }}>✅ Published</option>
                                        <option value="archived" {{ old('status', $quiz->status) == 'archived' ? 'selected' : '' }}>📦 Archived</option>
                                    </select>
                                </div>
                                <small class="text-muted">Draft: belum dipublikasikan, Published: tersedia, Archived: diarsipkan.</small>
                                @error('status')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Order -->
                            <div class="col-12 col-md-6">
                                <label for="order" class="form-label fw-semibold">
                                    Urutan
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-list-ol"></i></span>
                                    <input type="number" class="form-control @error('order') is-invalid @enderror" 
                                           id="order" name="order" value="{{ old('order', $quiz->order ?? 0) }}" 
                                           min="0">
                                </div>
                                <small class="text-muted">Urutan tampil quiz (semakin kecil semakin atas).</small>
                                @error('order')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- ========================================================== -->
                            <!-- INFORMASI TAMBAHAN -->
                            <!-- ========================================================== -->
                            <div class="col-12">
                                <hr class="my-2">
                                <div class="row g-3">
                                    <div class="col-12 col-md-4">
                                        <label class="text-muted small fw-semibold">Dibuat</label>
                                        <p class="fw-semibold mb-0">{{ $quiz->created_at ? $quiz->created_at->format('d/m/Y H:i') : '-' }}</p>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label class="text-muted small fw-semibold">Diperbarui</label>
                                        <p class="fw-semibold mb-0">{{ $quiz->updated_at ? $quiz->updated_at->format('d/m/Y H:i') : '-' }}</p>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label class="text-muted small fw-semibold">Total Pertanyaan</label>
                                        <p class="fw-semibold mb-0">{{ $quiz->questions_count ?? $quiz->questions()->count() ?? 0 }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- ========================================================== -->
                            <!-- SUBMIT BUTTONS -->
                            <!-- ========================================================== -->
                            <div class="col-12 mt-4">
                                <hr class="my-2">
                                <div class="d-flex gap-2 flex-wrap">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save me-1"></i> Perbarui
                                    </button>
                                    <a href="{{ route('admin.quiz.index') }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-x-circle me-1"></i> Batal
                                    </a>
                                    <button type="button" class="btn btn-outline-danger" 
                                            data-bs-toggle="modal" data-bs-target="#deleteModal">
                                        <i class="bi bi-trash me-1"></i> Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-triangle text-danger me-2"></i>
                    Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus quiz <strong>{{ $quiz->judul }}</strong>?</p>
                @if(($quiz->questions_count ?? $quiz->questions()->count() ?? 0) > 0)
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Quiz ini memiliki <strong>{{ $quiz->questions_count ?? $quiz->questions()->count() ?? 0 }}</strong> pertanyaan. 
                    Menghapus quiz akan menghapus semua pertanyaan terkait.
                </div>
                @endif
                <p class="text-muted small">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin.quiz.destroy', $quiz->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-1"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ============================================================
        // VALIDASI TANGGAL
        // ============================================================
        const startDate = document.getElementById('start_date');
        const endDate = document.getElementById('end_date');

        if (startDate && endDate) {
            startDate.addEventListener('change', function() {
                if (this.value) {
                    endDate.setAttribute('min', this.value);
                    if (endDate.value && endDate.value < this.value) {
                        endDate.value = '';
                    }
                }
            });

            // Set initial min for end date
            if (startDate.value) {
                endDate.setAttribute('min', startDate.value);
            }
        }

        // ============================================================
        // VALIDASI DURASI
        // ============================================================
        const durasiInput = document.getElementById('durasi');
        if (durasiInput) {
            durasiInput.addEventListener('input', function() {
                if (this.value < 0) {
                    this.value = 1;
                }
            });
        }

        // ============================================================
        // VALIDASI PASSING SCORE
        // ============================================================
        const passingScore = document.getElementById('passing_score');
        if (passingScore) {
            passingScore.addEventListener('input', function() {
                if (this.value < 0) {
                    this.value = 0;
                }
                if (this.value > 100) {
                    this.value = 100;
                }
            });
        }

        // ============================================================
        // MAX ATTEMPT VALIDATION
        // ============================================================
        const maxAttempt = document.getElementById('max_attempt');
        if (maxAttempt) {
            maxAttempt.addEventListener('input', function() {
                if (this.value < 1) {
                    this.value = 1;
                }
                if (this.value > 10) {
                    this.value = 10;
                }
            });
        }

        // ============================================================
        // ORDER VALIDATION
        // ============================================================
        const orderInput = document.getElementById('order');
        if (orderInput) {
            orderInput.addEventListener('input', function() {
                if (this.value < 0) {
                    this.value = 0;
                }
            });
        }

        // ============================================================
        // STATUS CHANGE CONFIRMATION
        // ============================================================
        const statusSelect = document.getElementById('status');
        if (statusSelect) {
            const currentStatus = '{{ $quiz->status }}';
            statusSelect.addEventListener('change', function() {
                if (this.value === 'archived' && currentStatus !== 'archived') {
                    if (!confirm('Apakah Anda yakin ingin mengarsipkan quiz ini?')) {
                        this.value = currentStatus;
                    }
                }
            });
        }
    });
</script>
@endpush
@endsection