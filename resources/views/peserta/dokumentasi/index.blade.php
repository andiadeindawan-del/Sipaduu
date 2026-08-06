@extends('layouts.peserta')

@section('title', 'Dokumentasi Pelatihan')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <div class="page-icon">
            <i class="bi bi-file-earmark-link"></i>
        </div>
        <div>
            <h1 class="h4 mb-1">Dokumentasi Pelatihan</h1>
            <p class="eyebrow">Kumpulan link dokumentasi dari pelatihan yang Anda ikuti</p>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid px-3 px-lg-4 pb-4">
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        @forelse($dokumentasis as $doc)
        <div class="col">
            <div class="card h-100 border-0 shadow-sm" style="border-radius: 12px; transition: transform 0.2s ease, box-shadow 0.2s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 20px rgba(0,0,0,0.08)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px rgba(0,0,0,0.05)';">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <div style="width: 48px; height: 48px; background: rgba(74, 108, 247, 0.1); color: #4a6cf7; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                                <i class="bi bi-images"></i>
                            </div>
                        </div>
                        <div class="ms-3">
                            <h5 class="card-title mb-1 fw-bold" style="font-size: 1.1rem; color: #1a2332;">{{ $doc->judul }}</h5>
                            <span class="badge bg-light text-dark border"><i class="bi bi-journal-bookmark me-1"></i>{{ Str::limit($doc->training->judul ?? '-', 25) }}</span>
                        </div>
                    </div>
                    @if($doc->deskripsi)
                        <p class="card-text text-muted mb-4" style="font-size: 0.9rem;">{{ Str::limit($doc->deskripsi, 100) }}</p>
                    @endif
                    <a href="{{ $doc->link }}" target="_blank" class="btn btn-primary w-100" style="border-radius: 8px;">
                        <i class="bi bi-box-arrow-up-right me-1"></i> Buka Dokumentasi
                    </a>
                </div>
                <div class="card-footer bg-transparent border-top-0 pt-0 pb-3 px-4 text-muted" style="font-size: 0.8rem;">
                    <i class="bi bi-clock me-1"></i> Ditambahkan {{ $doc->created_at->diffForHumans() }}
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5">
                <div style="font-size: 4rem; color: #e8edf5; margin-bottom: 1rem;">
                    <i class="bi bi-folder-x"></i>
                </div>
                <h4 class="text-muted fw-bold">Belum Ada Dokumentasi</h4>
                <p class="text-secondary">Saat ini belum ada link dokumentasi untuk pelatihan yang Anda ikuti.</p>
            </div>
        </div>
        @endforelse
    </div>
    
    @if($dokumentasis->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $dokumentasis->links() }}
    </div>
    @endif
</div>
@endsection
