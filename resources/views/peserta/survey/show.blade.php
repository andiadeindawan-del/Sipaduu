@extends('layouts.peserta')

@section('title', 'Isi Survey')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <div class="page-icon bg-primary text-white">
            <i class="bi bi-pencil-square"></i>
        </div>
        <div>
            <h1 class="h4 mb-1">Form Survey Kepuasan</h1>
            <p class="eyebrow">{{ $survey->judul }}</p>
        </div>
    </div>
    <div class="heading-actions">
        <a href="{{ route('peserta.survey.index') }}" class="btn btn-light">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid px-3 px-lg-4 pb-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-body p-4 p-lg-5">
                    <div class="mb-4 text-center">
                        <h4 class="fw-bold mb-2">{{ $survey->judul }}</h4>
                        <p class="text-muted">{{ $survey->deskripsi }}</p>
                        <span class="badge bg-light text-dark border">Pelatihan: {{ $survey->training->judul ?? '-' }}</span>
                    </div>

                    <hr class="my-4">

                    <form action="{{ route('peserta.survey.submit', $survey->id) }}" method="POST">
                        @csrf
                        
                        @forelse($survey->questions as $index => $q)
                        <div class="mb-4 p-3 bg-light rounded-3">
                            <label class="form-label fw-bold d-block mb-3 fs-5">{{ $index + 1 }}. {{ $q->pertanyaan }}</label>
                            
                            @if($q->tipe == 'rating_5')
                                <div class="rating-group d-flex justify-content-between mx-auto" style="max-width: 300px;">
                                    @for($i = 1; $i <= 5; $i++)
                                    <div class="form-check form-check-inline p-0 m-0 text-center">
                                        <input class="form-check-input d-none" type="radio" name="answers[{{ $q->id }}]" id="q{{ $q->id }}_rating{{ $i }}" value="{{ $i }}" required>
                                        <label class="form-check-label d-flex flex-column align-items-center justify-content-center" for="q{{ $q->id }}_rating{{ $i }}" style="cursor:pointer; width: 40px; height: 40px; border-radius:50%; background: #fff; border:2px solid #dee2e6; transition:all 0.2s;">
                                            <span class="fw-bold fs-5 text-muted">{{ $i }}</span>
                                        </label>
                                    </div>
                                    @endfor
                                </div>
                                <div class="d-flex justify-content-between mx-auto mt-2 text-muted small" style="max-width: 300px;">
                                    <span>Sangat Tidak Puas</span>
                                    <span>Sangat Puas</span>
                                </div>
                            @elseif($q->tipe == 'boolean')
                                <div class="d-flex gap-3 mt-2">
                                    <div class="flex-grow-1">
                                        <input type="radio" class="btn-check" name="answers[{{ $q->id }}]" id="q{{ $q->id }}_puas" value="Puas" required>
                                        <label class="btn btn-outline-success w-100 py-3 fw-bold boolean-label" for="q{{ $q->id }}_puas">
                                            <i class="bi bi-hand-thumbs-up-fill fs-4 d-block mb-1"></i> Puas
                                        </label>
                                    </div>
                                    <div class="flex-grow-1">
                                        <input type="radio" class="btn-check" name="answers[{{ $q->id }}]" id="q{{ $q->id }}_tidakpuas" value="Tidak Puas" required>
                                        <label class="btn btn-outline-danger w-100 py-3 fw-bold boolean-label" for="q{{ $q->id }}_tidakpuas">
                                            <i class="bi bi-hand-thumbs-down-fill fs-4 d-block mb-1"></i> Tidak Puas
                                        </label>
                                    </div>
                                </div>
                            @elseif($q->tipe == 'text')
                                <textarea name="answers[{{ $q->id }}]" class="form-control" rows="3" placeholder="Tuliskan jawaban Anda di sini..." required></textarea>
                            @endif
                        </div>
                        @empty
                        <div class="text-center py-4 text-muted">Belum ada pertanyaan pada survey ini.</div>
                        @endforelse
                        
                        @if($survey->questions->count() > 0)
                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary px-4 py-2 fw-bold" onclick="return confirm('Kirim jawaban survey sekarang? Jawaban yang telah dikirim tidak dapat diubah.')">
                                <i class="bi bi-send me-2"></i> Kirim Jawaban
                            </button>
                        </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* CSS khusus untuk memberikan efek terpilih pada rating bulat */
.form-check-input:checked + label {
    background-color: #ffc107 !important;
    border-color: #ffc107 !important;
}
.form-check-input:checked + label span {
    color: #000 !important;
}
.form-check-label:hover {
    background-color: #f8f9fa !important;
    border-color: #ffc107 !important;
}
</style>
@endsection
