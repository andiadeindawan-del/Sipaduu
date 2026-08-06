@extends('layouts.peserta')

@section('title', 'Survey Pelatihan')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <div class="page-icon">
            <i class="bi bi-ui-radios"></i>
        </div>
        <div>
            <h1 class="h4 mb-1">Survey Kepuasan Pelatihan</h1>
            <p class="eyebrow">Berikan umpan balik Anda mengenai pelatihan yang telah diikuti</p>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid px-3 px-lg-4 pb-4">
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        @forelse($surveys as $survey)
        <div class="col">
            <div class="card h-100 border-0 shadow-sm" style="border-radius: 12px; transition: transform 0.2s ease, box-shadow 0.2s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 20px rgba(0,0,0,0.08)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px rgba(0,0,0,0.05)';">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <div style="width: 48px; height: 48px; background: rgba(74, 108, 247, 0.1); color: #4a6cf7; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                                <i class="bi bi-clipboard-check"></i>
                            </div>
                        </div>
                        <div class="ms-3">
                            <h5 class="card-title mb-1 fw-bold" style="font-size: 1.1rem; color: #1a2332;">{{ $survey->judul }}</h5>
                            <span class="badge bg-light text-dark border"><i class="bi bi-journal-bookmark me-1"></i>{{ Str::limit($survey->training->judul ?? '-', 25) }}</span>
                        </div>
                    </div>
                    @if($survey->deskripsi)
                        <p class="card-text text-muted mb-4" style="font-size: 0.9rem;">{{ Str::limit($survey->deskripsi, 100) }}</p>
                    @endif
                    
                    @php
                        $hasResponded = $survey->responses->isNotEmpty();
                    @endphp

                    @if($hasResponded)
                        <button class="btn btn-secondary w-100 disabled" style="border-radius: 8px;">
                            <i class="bi bi-check-circle me-1"></i> Sudah Mengisi
                        </button>
                    @else
                        <a href="{{ route('peserta.survey.show', $survey->id) }}" class="btn btn-primary w-100" style="border-radius: 8px;">
                            <i class="bi bi-pencil-square me-1"></i> Isi Survey
                        </a>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5">
                <div style="font-size: 4rem; color: #e8edf5; margin-bottom: 1rem;">
                    <i class="bi bi-clipboard-x"></i>
                </div>
                <h4 class="text-muted fw-bold">Belum Ada Survey</h4>
                <p class="text-secondary">Saat ini belum ada survey kepuasan yang tersedia untuk pelatihan Anda.</p>
            </div>
        </div>
        @endforelse
    </div>
    
    @if($surveys->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $surveys->links() }}
    </div>
    @endif
</div>
@endsection
