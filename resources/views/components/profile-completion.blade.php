@php
    $isLengkap = $user->is_profil_lengkap;
@endphp

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4 text-center">
        @if($isLengkap)
            <span class="badge bg-success fs-5 py-3 px-4">
                <i class="bi bi-check-circle-fill me-2"></i> Profil Lengkap
            </span>
        @else
            <span class="badge bg-danger fs-5 py-3 px-4 mb-3">
                <i class="bi bi-exclamation-circle-fill me-2"></i> Profil Belum Lengkap
            </span>
            <div class="mt-2">
                <a href="{{ route('peserta.profile.index') }}" class="btn btn-danger px-4 py-2 fw-semibold shadow-sm">
                    Lengkapi Sekarang
                </a>
            </div>
        @endif
    </div>
</div>
