import re

file_path = r'c:\laragon\www\SIPADUU\resources\views\peserta\profile\index.blade.php'
with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# For current_password, password, password_confirmation
content = re.sub(r'<label class="form-label fw-semibold">Password Saat Ini <span class="text-danger">\*</span></label>', r'<label class="form-label fw-semibold">Password Saat Ini</label>', content)
content = re.sub(r'<label class="form-label fw-semibold">Password Baru <span class="text-danger">\*</span></label>', r'<label class="form-label fw-semibold">Password Baru</label>', content)
content = re.sub(r'<label class="form-label fw-semibold">Konfirmasi Password <span class="text-danger">\*</span></label>', r'<label class="form-label fw-semibold">Konfirmasi Password</label>', content)

# And add a text to the panel header
content = content.replace(
    '<h5 class="section-title"><i class="bi bi-shield-lock"></i> Ubah Password</h5>',
    '<h5 class="section-title"><i class="bi bi-shield-lock"></i> Ubah Password <span class="badge bg-secondary ms-2 fw-normal" style="font-size: 0.75rem;">Opsional</span></h5>'
)

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("Updated Ubah Password section")
