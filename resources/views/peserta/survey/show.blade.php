@extends('layouts.peserta')

@section('title', 'Isi Survey')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-pencil-square"></i></span>
        <div>
            <p class="eyebrow mb-1">Peserta</p>
            <h1 class="h3 mb-0">Form Survey Kepuasan</h1>
            <p class="text-muted mb-0">{{ $survey->judul }}</p>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('peserta.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('peserta.survey.index') }}" class="text-decoration-none">Survey</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $survey->judul }}</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="panel">
                <!-- Panel Header -->
                <div class="panel-header">
                    <div>
                        <h5 class="section-title"><i class="bi bi-clipboard-check"></i> Form Survey</h5>
                        <p class="text-muted small mb-0">Silakan jawab pertanyaan berikut dengan jujur dan objektif</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('peserta.survey.index') }}" class="btn btn-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>

                <!-- Panel Body -->
                <div class="panel-body p-4">
                    <!-- Survey Info -->
                    <div class="survey-info mb-4">
                        <div class="row g-3 align-items-center">
                            <div class="col-12 col-md-8">
                                <h4 class="fw-bold mb-1">{{ $survey->judul }}</h4>
                                @if($survey->deskripsi)
                                    <p class="text-muted mb-0">{{ $survey->deskripsi }}</p>
                                @endif
                            </div>
                            <div class="col-12 col-md-4 text-md-end">
                                <span class="badge bg-primary">
                                    <i class="bi bi-journal-bookmark-fill me-1"></i>
                                    {{ $survey->training->judul ?? 'Tanpa Pelatihan' }}
                                </span>
                                <span class="badge bg-info ms-1">
                                    <i class="bi bi-question-circle me-1"></i>
                                    {{ $survey->questions->count() }} Pertanyaan
                                </span>
                            </div>
                        </div>
                        <hr>
                    </div>

                    <!-- Form -->
                    <form action="{{ route('peserta.survey.submit', $survey->id) }}" method="POST" id="surveyForm">
                        @csrf
                        
                        @forelse($survey->questions as $index => $q)
                        <div class="question-item">
                            <div class="question-header">
                                <span class="question-number">{{ $index + 1 }}</span>
                                <span class="question-text">{{ $q->pertanyaan }}</span>
                                <span class="question-type">
                                    @if($q->tipe == 'rating_5')
                                        <i class="bi bi-star-fill text-warning"></i>
                                    @elseif($q->tipe == 'boolean')
                                        <i class="bi bi-ui-checks text-primary"></i>
                                    @else
                                        <i class="bi bi-justify-left text-info"></i>
                                    @endif
                                </span>
                            </div>
                            
                            <div class="question-body">
                                @if($q->tipe == 'rating_5')
                                    <div class="rating-container">
                                        <div class="rating-labels">
                                            <span class="rating-label-start">Sangat Tidak Puas</span>
                                            <span class="rating-label-end">Sangat Puas</span>
                                        </div>
                                        <div class="rating-options">
                                            @for($i = 1; $i <= 5; $i++)
                                            <div class="rating-option">
                                                <input type="radio" name="answers[{{ $q->id }}]" 
                                                       id="q{{ $q->id }}_rating{{ $i }}" 
                                                       value="{{ $i }}" required>
                                                <label for="q{{ $q->id }}_rating{{ $i }}" class="rating-label">
                                                    <span class="rating-number">{{ $i }}</span>
                                                    <span class="rating-dot"></span>
                                                </label>
                                            </div>
                                            @endfor
                                        </div>
                                    </div>
                                    
                                @elseif($q->tipe == 'boolean')
                                    <div class="boolean-container">
                                        <div class="row g-2">
                                            <div class="col-6">
                                                <input type="radio" class="btn-check" name="answers[{{ $q->id }}]" 
                                                       id="q{{ $q->id }}_puas" value="Puas" required>
                                                <label class="btn btn-boolean btn-boolean-yes w-100" for="q{{ $q->id }}_puas">
                                                    <i class="bi bi-hand-thumbs-up-fill"></i>
                                                    <span>Puas</span>
                                                </label>
                                            </div>
                                            <div class="col-6">
                                                <input type="radio" class="btn-check" name="answers[{{ $q->id }}]" 
                                                       id="q{{ $q->id }}_tidakpuas" value="Tidak Puas" required>
                                                <label class="btn btn-boolean btn-boolean-no w-100" for="q{{ $q->id }}_tidakpuas">
                                                    <i class="bi bi-hand-thumbs-down-fill"></i>
                                                    <span>Tidak Puas</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                @elseif($q->tipe == 'text')
                                    <div class="text-container">
                                        <textarea name="answers[{{ $q->id }}]" class="form-control" 
                                                  rows="3" placeholder="Tuliskan jawaban Anda di sini..." 
                                                  required></textarea>
                                        <small class="text-muted">
                                            <i class="bi bi-info-circle me-1"></i>
                                            Jawaban Anda akan sangat membantu kami untuk meningkatkan kualitas pelatihan.
                                        </small>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-5">
                            <div class="empty-state-icon">
                                <i class="bi bi-clipboard-x"></i>
                            </div>
                            <h5 class="text-muted">Belum Ada Pertanyaan</h5>
                            <p class="text-muted small">Survey ini belum memiliki pertanyaan. Silakan hubungi administrator.</p>
                        </div>
                        @endforelse
                        
                        @if($survey->questions->count() > 0)
                        <div class="form-actions">
                            <button type="reset" class="btn btn-light">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                            </button>
                            <button type="submit" class="btn btn-primary" id="submitSurvey">
                                <i class="bi bi-send me-1"></i> Kirim Jawaban
                            </button>
                        </div>
                        @endif
                    </form>
                </div>
            </div>
            
            <!-- Info Tambahan -->
            <div class="panel mt-4">
                <div class="panel-body p-3">
                    <div class="d-flex flex-wrap align-items-center gap-3 text-muted small">
                        <span>
                            <i class="bi bi-info-circle me-1"></i>
                            Semua jawaban bersifat rahasia dan hanya digunakan untuk evaluasi pelatihan
                        </span>
                        <span>
                            <i class="bi bi-check-circle me-1"></i>
                            Jawaban tidak dapat diubah setelah dikirim
                        </span>
                        <span>
                            <i class="bi bi-clock me-1"></i>
                            Estimasi waktu: {{ $survey->questions->count() * 1 }} menit
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* ============================================================
       PAGE HEADING
    ============================================================ */
    .page-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        padding: 1.25rem 1.5rem;
        background: #fff;
        border-bottom: 1px solid #f0f0f0;
    }
    .page-heading-copy {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .page-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #eaf1fd, #d4e4f7);
        color: #4e9af1;
        font-size: 1.5rem;
        flex-shrink: 0;
    }
    .eyebrow {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #8a93a3;
        font-weight: 600;
    }

    /* ============================================================
       BREADCRUMB
    ============================================================ */
    .breadcrumb {
        padding: 0;
        margin: 0;
        background: transparent;
    }
    .breadcrumb-item a {
        color: #4e9af1;
        font-weight: 500;
    }
    .breadcrumb-item a:hover {
        color: #3d8ae0;
        text-decoration: underline !important;
    }
    .breadcrumb-item.active {
        color: #1a2236;
        font-weight: 600;
    }

    /* ============================================================
       PANEL
    ============================================================ */
    .panel {
        background: #fff;
        border-radius: .75rem;
        box-shadow: 0 1px 4px rgba(0,0,0,.06);
        overflow: hidden;
    }
    .panel:hover {
        box-shadow: 0 8px 30px rgba(0,0,0,0.06);
    }
    
    .panel-header {
        padding: .9rem 1.25rem;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: .75rem;
        background: #fafbfc;
    }
    
    .section-title {
        display: flex;
        align-items: center;
        gap: .5rem;
        margin: 0;
        font-size: 1rem;
        font-weight: 600;
        color: #1a2236;
    }
    
    .section-title i {
        color: #4e9af1;
    }
    
    .panel-body {
        background: #fff;
    }

    /* ============================================================
       SURVEY INFO
    ============================================================ */
    .survey-info {
        padding: 1rem;
        background: #f8fafc;
        border-radius: 0.75rem;
    }
    .survey-info h4 {
        color: #1a2236;
    }
    .survey-info .badge {
        padding: 0.4rem 0.8rem;
        font-weight: 500;
    }
    .badge.bg-primary {
        background: #cfe2ff !important;
        color: #084298 !important;
    }
    .badge.bg-info {
        background: #e3f0ff !important;
        color: #0d6efd !important;
    }

    /* ============================================================
       QUESTION ITEM
    ============================================================ */
    .question-item {
        margin-bottom: 1.5rem;
        padding: 1.25rem;
        background: #fff;
        border: 1px solid #f0f0f0;
        border-radius: 0.75rem;
        transition: all 0.3s ease;
    }
    .question-item:hover {
        border-color: #d4e4f7;
        box-shadow: 0 4px 12px rgba(0,0,0,0.04);
    }
    
    .question-item:last-child {
        margin-bottom: 0;
    }
    
    .question-header {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        margin-bottom: 0.75rem;
    }
    
    .question-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 28px;
        height: 28px;
        background: #4e9af1;
        color: #fff;
        border-radius: 50%;
        font-size: 0.8rem;
        font-weight: 600;
        flex-shrink: 0;
    }
    
    .question-text {
        font-size: 1rem;
        font-weight: 500;
        color: #1a2236;
        flex: 1;
    }
    
    .question-type {
        font-size: 1.1rem;
        color: #8a93a3;
        flex-shrink: 0;
    }
    
    .question-body {
        padding-left: 2.5rem;
    }

    /* ============================================================
       RATING OPTIONS
    ============================================================ */
    .rating-container {
        max-width: 400px;
        margin: 0 auto;
    }
    
    .rating-labels {
        display: flex;
        justify-content: space-between;
        font-size: 0.7rem;
        color: #8a93a3;
        margin-bottom: 0.5rem;
        padding: 0 0.5rem;
    }
    
    .rating-options {
        display: flex;
        justify-content: space-between;
        gap: 0.5rem;
    }
    
    .rating-option {
        flex: 1;
        text-align: center;
    }
    
    .rating-option input[type="radio"] {
        display: none;
    }
    
    .rating-label {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.25rem;
        cursor: pointer;
        padding: 0.5rem 0;
        border-radius: 0.5rem;
        transition: all 0.3s ease;
        background: #f8fafc;
        border: 2px solid transparent;
    }
    
    .rating-label:hover {
        background: #eaf1fd;
        border-color: #d4e4f7;
        transform: scale(1.05);
    }
    
    .rating-number {
        font-size: 1.1rem;
        font-weight: 600;
        color: #4a5568;
    }
    
    .rating-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #d4e4f7;
        transition: all 0.3s ease;
    }
    
    .rating-option input[type="radio"]:checked + .rating-label {
        background: #fff3cd;
        border-color: #ffc107;
        box-shadow: 0 4px 12px rgba(255, 193, 7, 0.3);
    }
    
    .rating-option input[type="radio"]:checked + .rating-label .rating-number {
        color: #856404;
    }
    
    .rating-option input[type="radio"]:checked + .rating-label .rating-dot {
        background: #ffc107;
        transform: scale(1.3);
    }

    /* ============================================================
       BOOLEAN OPTIONS
    ============================================================ */
    .boolean-container {
        max-width: 400px;
        margin: 0 auto;
    }
    
    .btn-boolean {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.25rem;
        padding: 0.75rem;
        border-radius: 0.75rem;
        border: 2px solid #e2e8f0;
        background: #f8fafc;
        transition: all 0.3s ease;
        font-weight: 500;
        cursor: pointer;
    }
    
    .btn-boolean i {
        font-size: 1.5rem;
        transition: all 0.3s ease;
    }
    
    .btn-boolean span {
        font-size: 0.85rem;
    }
    
    .btn-boolean-yes:hover {
        border-color: #28c76f;
        background: #ecfdf5;
        transform: scale(1.02);
    }
    
    .btn-boolean-no:hover {
        border-color: #f56565;
        background: #fef2f2;
        transform: scale(1.02);
    }
    
    .btn-check:checked + .btn-boolean-yes {
        border-color: #28c76f;
        background: #d4edda;
        box-shadow: 0 4px 12px rgba(40, 199, 111, 0.3);
    }
    
    .btn-check:checked + .btn-boolean-yes i {
        color: #155724;
    }
    
    .btn-check:checked + .btn-boolean-no {
        border-color: #f56565;
        background: #f8d7da;
        box-shadow: 0 4px 12px rgba(245, 101, 101, 0.3);
    }
    
    .btn-check:checked + .btn-boolean-no i {
        color: #721c24;
    }

    /* ============================================================
       TEXT AREA
    ============================================================ */
    .text-container {
        max-width: 100%;
    }
    
    .text-container .form-control {
        border-radius: 0.5rem;
        border-color: #e2e8f0;
        transition: all 0.3s ease;
        padding: 0.75rem;
        font-size: 0.9rem;
    }
    
    .text-container .form-control:focus {
        border-color: #4e9af1;
        box-shadow: 0 0 0 3px rgba(78, 154, 241, 0.12);
    }
    
    .text-container .form-control::placeholder {
        color: #c3cad6;
    }

    /* ============================================================
       FORM ACTIONS
    ============================================================ */
    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid #f0f0f0;
    }
    
    .form-actions .btn {
        padding: 0.5rem 1.5rem;
        font-weight: 500;
        border-radius: 0.5rem;
        transition: all 0.3s ease;
    }
    
    .form-actions .btn-primary {
        background: #4e9af1;
        border-color: #4e9af1;
        color: #fff;
    }
    .form-actions .btn-primary:hover {
        background: #3d8ae0;
        border-color: #3d8ae0;
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(78, 154, 241, 0.35);
    }
    
    .form-actions .btn-light {
        background: #f8fafc;
        border-color: #e2e8f0;
        color: #4a5568;
    }
    .form-actions .btn-light:hover {
        background: #edf2f7;
        border-color: #d5dce6;
    }

    /* ============================================================
       EMPTY STATE
    ============================================================ */
    .empty-state-icon {
        font-size: 3.5rem;
        color: #d4e4f7;
    }
    .empty-state-icon i {
        display: block;
    }

    /* ============================================================
       BUTTONS
    ============================================================ */
    .btn {
        border-radius: 0.5rem;
        padding: 0.4rem 1.2rem;
        font-weight: 500;
        font-size: 0.85rem;
        transition: all 0.2s ease;
    }
    
    .btn-secondary {
        background: #e2e8f0;
        border-color: #e2e8f0;
        color: #4a5568;
    }
    .btn-secondary:hover {
        background: #d5dce6;
        border-color: #d5dce6;
    }
    
    .btn-sm {
        padding: 0.3rem 0.8rem;
        font-size: 0.8rem;
    }

    /* ============================================================
       RESPONSIVE
    ============================================================ */
    @media (max-width: 768px) {
        .page-heading {
            flex-direction: column;
            align-items: flex-start;
        }
        .page-heading-copy {
            width: 100%;
        }
        .panel-header {
            flex-direction: column;
            align-items: flex-start;
        }
        .panel-body {
            padding: 1.25rem !important;
        }
        .question-body {
            padding-left: 0;
        }
        .question-header {
            flex-wrap: wrap;
        }
        .question-number {
            min-width: 24px;
            height: 24px;
            font-size: 0.7rem;
        }
        .rating-options {
            gap: 0.25rem;
        }
        .rating-label {
            padding: 0.25rem 0;
        }
        .rating-number {
            font-size: 0.9rem;
        }
        .form-actions {
            flex-direction: column;
        }
        .form-actions .btn {
            width: 100%;
        }
        .survey-info {
            text-align: center;
        }
        .survey-info .text-md-end {
            text-align: center !important;
            margin-top: 0.5rem;
        }
        .d-flex.flex-wrap.align-items-center.gap-3 {
            flex-direction: column;
            align-items: flex-start !important;
        }
    }

    @media (max-width: 576px) {
        .page-icon {
            width: 44px;
            height: 44px;
            font-size: 1.2rem;
        }
        .question-item {
            padding: 0.75rem;
        }
        .rating-options {
            flex-wrap: nowrap;
        }
        .rating-label {
            padding: 0.2rem;
        }
        .rating-number {
            font-size: 0.8rem;
        }
        .btn-boolean {
            padding: 0.5rem;
        }
        .btn-boolean i {
            font-size: 1.2rem;
        }
    }

    /* ============================================================
       ANIMATION
    ============================================================ */
    .question-item {
        animation: fadeInUp 0.4s ease forwards;
        opacity: 0;
    }
    
    .question-item:nth-child(1) { animation-delay: 0.05s; }
    .question-item:nth-child(2) { animation-delay: 0.10s; }
    .question-item:nth-child(3) { animation-delay: 0.15s; }
    .question-item:nth-child(4) { animation-delay: 0.20s; }
    .question-item:nth-child(5) { animation-delay: 0.25s; }
    .question-item:nth-child(6) { animation-delay: 0.30s; }
    .question-item:nth-child(7) { animation-delay: 0.35s; }
    .question-item:nth-child(8) { animation-delay: 0.40s; }
    .question-item:nth-child(9) { animation-delay: 0.45s; }
    .question-item:nth-child(10) { animation-delay: 0.50s; }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Konfirmasi submit form
    const form = document.getElementById('surveyForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitSurvey');
            const originalText = submitBtn.innerHTML;
            
            // Disable button to prevent double submit
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="bi bi-spinner bi-spin me-1"></i> Mengirim...';
            
            // Re-enable after 3 seconds if form not submitted
            setTimeout(function() {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }, 3000);
        });
    }
    
    // Auto focus on first question
    const firstInput = document.querySelector('.question-item input, .question-item textarea');
    if (firstInput) {
        setTimeout(function() {
            firstInput.focus();
        }, 500);
    }
});
</script>
@endpush
@endsection