@extends('layouts.admin')

@section('title', 'Daftar Peserta Pelatihan')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-people"></i></span>
        <div>
            <p class="eyebrow">Pelatihan: {{ $training->judul }}</p>
            <h1 class="h3 mb-0">Daftar Peserta</h1>
        </div>
    </div>
    <div class="heading-actions d-flex gap-2">
        <a href="{{ route('admin.trainings.show', $training->id) }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Kembali ke Pelatihan
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <!-- Alert Messages -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="panel">
        <div class="panel-header">
            <div>
                <h5 class="section-title"><i class="bi bi-table"></i> Daftar Peserta Mendaftar</h5>
                <p class="text-muted small mb-0">Total {{ $participants->total() ?? 0 }} pendaftaran</p>
            </div>
        </div>
        
        <div class="table-responsive">
            @if($participants->count() > 0)
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Nama Peserta</th>
                        <th>NIK</th>
                        <th>Status Profil</th>
                        <th>KTP</th>
                        <th>Status Pendaftaran</th>
                        <th class="text-center" style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($participants as $index => $registration)
                    <tr>
                        <td>{{ $participants->firstItem() + $index }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar-text avatar-sm bg-primary text-white d-flex align-items-center justify-content-center rounded-circle">
                                    {{ strtoupper(substr($registration->user->nama ?? $registration->user->name ?? 'U', 0, 2)) }}
                                </div>
                                <div>
                                    <p class="fw-semibold mb-0">{{ $registration->user->nama ?? $registration->user->name ?? 'Unknown' }}</p>
                                </div>
                            </div>
                        </td>
                        <td>{{ $registration->user->nik ?? '-' }}</td>
                        <td>
                            @if($registration->user->is_profil_lengkap)
                                <span class="badge bg-success"><i class="bi bi-check-circle"></i> Lengkap</span>
                            @else
                                <span class="badge bg-danger"><i class="bi bi-x-circle"></i> Belum Lengkap</span>
                            @endif
                        </td>
                        <td>
                            @if($registration->user->ktp_file)
                                <span class="badge bg-success"><i class="bi bi-check"></i></span>
                            @else
                                <span class="badge bg-danger"><i class="bi bi-x"></i></span>
                            @endif
                        </td>
                        <td>
                            <span class="{!! $registration->status_badge !!}">
                                {{ $registration->status_label }}
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.pendaftaran.show', $registration->id) }}" class="btn btn-sm btn-outline-primary">
                                Detail Peserta
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="text-center py-5">
                <img src="{{ asset('assets/images/illustrations/empty.svg') }}" alt="Empty" class="mb-3" style="width: 150px; opacity: 0.5;">
                <h6 class="text-muted">Belum ada peserta yang mendaftar</h6>
            </div>
            @endif
        </div>
        
        @if($participants->hasPages())
        <div class="panel-footer bg-light p-3">
            {{ $participants->links() }}
        </div>
        @endif
    </div>
</div>
@endsection