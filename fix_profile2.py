import re
import os

profile_view = r'c:\laragon\www\SIPADUU\resources\views\peserta\profile\index.blade.php'

with open(profile_view, 'r', encoding='utf-8') as f:
    prof_content = f.read()

start_marker = '<div class="panel mb-4">'
p = prof_content.find('Status Kelengkapan Profil')
start_idx = prof_content.rfind(start_marker, 0, p)

if start_idx != -1:
    # Find the next panel
    end_idx = prof_content.find('<div class="panel ', p)
    
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
        new_content = prof_content[:start_idx] + simplified_panel + prof_content[end_idx:]
        with open(profile_view, 'w', encoding='utf-8') as f:
            f.write(new_content)
        print("Profile updated!")
    else:
        print("End marker not found")
else:
    print("Start marker not found")
