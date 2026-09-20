import re
import os

profile_view = r'c:\laragon\www\SIPADUU\resources\views\peserta\profile\index.blade.php'
dashboard_comp = r'c:\laragon\www\SIPADUU\resources\views\components\profile-completion.blade.php'

# --- 1. Fix Profile Page ---
with open(profile_view, 'r', encoding='utf-8') as f:
    prof_content = f.read()

start_idx = prof_content.find('<div class="panel mb-4">', prof_content.find('Status Kelengkapan Profil') - 200)

if start_idx != -1:
    end_idx = prof_content.find('<div class="panel mb-0">', start_idx)
    
    if end_idx != -1:
        simplified_panel = """<div class="panel mb-4">
                <div class="panel-header d-flex justify-content-between align-items-center">
                    <h5 class="section-title mb-0"><i class="bi bi-check-circle"></i> Status Kelengkapan Profil</h5>
                    <div>
                        @if($user->is_profil_lengkap)
                            <span class="badge bg-success fs-6 py-2 px-3">
                                <i class="bi bi-check-circle-fill me-1"></i> Profil Lengkap
                            </span>
                        @else
                            <span class="badge bg-danger fs-6 py-2 px-3">
                                <i class="bi bi-exclamation-circle-fill me-1"></i> Profil Belum Lengkap
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            
            """
        prof_content = prof_content[:start_idx] + simplified_panel + prof_content[end_idx:]

with open(profile_view, 'w', encoding='utf-8') as f:
    f.write(prof_content)


# --- 2. Fix Dashboard Component ---
dash_content = """@php
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
"""
with open(dashboard_comp, 'w', encoding='utf-8') as f:
    f.write(dash_content)

print("Done")
