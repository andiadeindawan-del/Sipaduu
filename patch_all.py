import re

controllers = [
    'app/Http/Controllers/ProfileController.php',
    'app/Http/Controllers/UserController.php'
]

for ctrl in controllers:
    with open(ctrl, 'r', encoding='utf-8') as f:
        content = f.read()
    
    replacements = [
        ("'status_usaha' => 'required|", "'status_usaha' => 'nullable|"),
        ("'bentuk_usaha' => 'required|", "'bentuk_usaha' => 'nullable|"),
        ("'jabatan_usaha' => 'required|", "'jabatan_usaha' => 'nullable|"),
        ("'kbli_id' => 'required|", "'kbli_id' => 'nullable|"),
        ("'kbli_utama' => 'required|", "'kbli_utama' => 'nullable|"),
        ("'no_telepon_usaha' => 'required|", "'no_telepon_usaha' => 'nullable|"),
        ("'tanggal_berdiri' => 'required|", "'tanggal_berdiri' => 'nullable|"),
        ("'karyawan_tetap_laki_laki' => 'required|", "'karyawan_tetap_laki_laki' => 'nullable|"),
        ("'karyawan_tetap_perempuan' => 'required|", "'karyawan_tetap_perempuan' => 'nullable|"),
        ("'karyawan_tidak_tetap_laki_laki' => 'required|", "'karyawan_tidak_tetap_laki_laki' => 'nullable|"),
        ("'karyawan_tidak_tetap_perempuan' => 'required|", "'karyawan_tidak_tetap_perempuan' => 'nullable|"),
        ("'provinsi_usaha' => 'required|", "'provinsi_usaha' => 'nullable|"),
        ("'kabupaten_usaha' => 'required|", "'kabupaten_usaha' => 'nullable|"),
        ("'kecamatan_usaha' => 'required|", "'kecamatan_usaha' => 'nullable|"),
        ("'desa_usaha' => 'required|", "'desa_usaha' => 'nullable|"),
        ("'alamat_usaha' => 'required|", "'alamat_usaha' => 'nullable|"),
        ("'email_usaha' => 'required|", "'email_usaha' => 'nullable|"),
    ]
    
    for old, new in replacements:
        content = content.replace(old, new)
        
    with open(ctrl, 'w', encoding='utf-8') as f:
        f.write(content)

views = [
    'resources/views/peserta/profile/index.blade.php',
    'resources/views/admin/users/edit.blade.php'
]

save_btn_html = """
                                    <div class="mt-4 d-flex justify-content-end pb-3">
                                        <button type="submit" class="btn btn-primary px-4">
                                            <i class="bi bi-save me-1"></i> Simpan Perubahan
                                        </button>
                                    </div>
"""

for view in views:
    with open(view, 'r', encoding='utf-8') as f:
        content = f.read()
    
    # Remove 'required' from all inputs, selects, textareas
    # Be careful not to remove 'required' from fields like name, email
    # Let's just strip ` required>` or ` required ` to avoid messing up things
    content = re.sub(r'\s+required(\s*>)', r'\1', content)
    content = re.sub(r'\s+required(\s+)', r'\1', content)
    
    # Remove the global save button
    content = re.sub(r'<div class="panel-footer bg-light p-4">\s*<div class="d-flex justify-content-end">\s*<button type="submit" class="btn btn-primary px-4">\s*<i class="bi bi-save me-1"></i> Simpan Perubahan\s*</button>\s*</div>\s*</div>', '', content)
    
    # Insert save button before the start of next tab pane
    tab_ids = ['usaha', 'digital', 'tambahan', 'dokumen']
    for tab_id in tab_ids:
        marker = f'<div class="tab-pane fade" id="{tab_id}"'
        content = content.replace(marker, save_btn_html + '\n                                ' + marker)
    
    # Insert before </form> for the last tab
    marker_end = '</form>'
    content = content.replace(marker_end, save_btn_html + '\n                ' + marker_end)
    
    with open(view, 'w', encoding='utf-8') as f:
        f.write(content)

print("Patch applied")
