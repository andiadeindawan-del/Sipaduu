@php
    $percentage = $user->profil_completion_percentage;
    $completedFields = $user->profil_completed_fields;
    $incompleteFields = $user->profil_incomplete_fields;
    $isLengkap = $user->is_profil_lengkap;
@endphp

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <h6 class="fw-bold border-bottom pb-2 mb-3">
            <i class="bi bi-person-check me-2"></i>Status Kelengkapan Profil
        </h6>
        
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="fw-semibold">Kelengkapan Profil: {{ $percentage }}%</span>
            <span class="badge {{ $isLengkap ? 'bg-success' : 'bg-warning text-dark' }} px-3 py-2">
                @if($isLengkap)
                    <i class="bi bi-check-circle-fill me-1"></i> Profil Lengkap
                @else
                    <i class="bi bi-exclamation-triangle-fill me-1"></i> Profil Belum Lengkap
                @endif
            </span>
        </div>
        
        <div class="progress mb-4" style="height: 10px;">
            <div class="progress-bar {{ $isLengkap ? 'bg-success' : 'bg-warning' }}" role="progressbar" style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                <p class="fw-semibold text-success mb-2"><i class="bi bi-check-lg me-1"></i>Data yang sudah lengkap:</p>
                @if(count($completedFields) > 0)
                    <ul class="list-unstyled mb-0 text-sm">
                        @foreach($completedFields as $field)
                            <li class="mb-1"><i class="bi bi-check text-success fw-bold me-1"></i> {{ $field }}</li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted small fst-italic mb-0">- Belum ada data -</p>
                @endif
            </div>
            <div class="col-md-6">
                <p class="fw-semibold text-danger mb-2"><i class="bi bi-x-lg me-1"></i>Data yang belum lengkap:</p>
                @if(count($incompleteFields) > 0)
                    <ul class="list-unstyled mb-0 text-sm">
                        @foreach($incompleteFields as $field)
                            <li class="mb-1"><i class="bi bi-x text-danger fw-bold me-1"></i> {{ $field }}</li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted small fst-italic mb-0">- Semua data wajib sudah lengkap -</p>
                @endif
            </div>
        </div>
    </div>
</div>
