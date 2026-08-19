@php
    $isLengkap = $user->is_profil_lengkap;
@endphp

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4 text-center">
        @if($isLengkap)
            <div class="d-flex flex-column align-items-center">
                <h5 class="fw-bold text-success mb-2">✅ Profil Lengkap</h5>
                <p class="text-muted mb-0">Data profil Anda sudah lengkap.</p>
            </div>
        @else
            <div class="d-flex flex-column align-items-center">
                <h5 class="fw-bold text-warning mb-2" style="color: #d39e00 !important;">⚠️ Profil Belum Lengkap</h5>
                <p class="text-muted mb-3">Anda wajib melengkapi profil sebelum mengikuti pelatihan.</p>
                <a href="{{ route('peserta.profile.index') }}" class="btn btn-warning px-4 py-2 fw-semibold text-dark shadow-sm">
                    Lengkapi Profil
                </a>
            </div>
        @endif
    </div>
</div>
