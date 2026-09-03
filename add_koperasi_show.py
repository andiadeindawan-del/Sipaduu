import re

path = "resources/views/admin/users/show.blade.php"
with open(path, "r", encoding="utf-8") as f:
    content = f.read()

pattern = r'(<label class="text-muted small fw-semibold text-uppercase">Masukan.*?</div>\s*</div>)'
koperasi_html = """
                        <div class="col-12 mt-3">
                            <div class="detail-item">
                                <label class="text-muted small fw-semibold text-uppercase">Anggota Koperasi</label>
                                <p class="fw-semibold mb-0">{{ $user->anggota_koperasi ?? '-' }}</p>
                            </div>
                        </div>"""

content = re.sub(pattern, r"\1" + koperasi_html, content, flags=re.DOTALL)

with open(path, "w", encoding="utf-8") as f:
    f.write(content)
print("Added anggota_koperasi to show.blade.php")
