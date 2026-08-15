@extends('layouts.peserta')

@section('title', 'Hasil Scan Absensi')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 text-center p-4">
                <div class="card-body">
                    @if($status === 'success')
                        <div class="mb-4">
                            <i class="bi bi-check-circle text-success" style="font-size: 5rem;"></i>
                        </div>
                        <h3 class="text-success fw-bold mb-3">Kehadiran Tercatat!</h3>
                        <p class="text-muted mb-4">{{ $message }}</p>
                        <div class="p-3 bg-light rounded text-start mb-4">
                            <strong>Pelatihan:</strong> {{ $training->judul }}<br>
                            <strong>Waktu Scan:</strong> {{ now()->format('d M Y H:i:s') }}
                        </div>
                        <a href="{{ route('peserta.dashboard') }}" class="btn btn-primary w-100">Kembali ke Dashboard</a>
                    @else
                        <div class="mb-4">
                            <i class="bi bi-x-circle text-danger" style="font-size: 5rem;"></i>
                        </div>
                        <h3 class="text-danger fw-bold mb-3">Absensi Gagal!</h3>
                        <p class="text-muted mb-4">{{ $message }}</p>
                        @if(isset($training))
                        <div class="p-3 bg-light rounded text-start mb-4">
                            <strong>Pelatihan:</strong> {{ $training->judul }}
                        </div>
                        @endif
                        <a href="{{ route('peserta.dashboard') }}" class="btn btn-secondary w-100">Kembali ke Dashboard</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
