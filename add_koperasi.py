import re

koperasi_html = """                                    </div>
                                    <div class="col-12 mt-3">
                                        <label class="form-label fw-semibold">Anggota Koperasi <span class="text-muted">(Opsional)</span></label>
                                        <input type="text" class="form-control" name="anggota_koperasi" placeholder="Nama koperasi jika menjadi anggota" value="{{ old('anggota_koperasi', $user->anggota_koperasi) }}">
"""

for path in ["resources/views/peserta/profile/index.blade.php", "resources/views/admin/users/edit.blade.php"]:
    with open(path, "r", encoding="utf-8") as f:
        content = f.read()
    
    # We replace the closing div of masukan_saran with the new field
    pattern = r'name="masukan_saran".*?</textarea>\s*</div>'
    
    def repl(m):
        return m.group(0) + "\n" + koperasi_html.replace("</div>", "", 1)
        
    content = re.sub(pattern, repl, content, flags=re.DOTALL)
    
    with open(path, "w", encoding="utf-8") as f:
        f.write(content)
print("Added anggota_koperasi to forms")
