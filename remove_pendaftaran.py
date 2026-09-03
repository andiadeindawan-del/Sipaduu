import re

path = "resources/views/admin/pendaftaran/show.blade.php"
with open(path, "r", encoding="utf-8") as f: content = f.read()

content = re.sub(r'\s*<div class="col-12 col-md-6">\s*<div class="detail-item">\s*<label class="text-muted small fw-semibold text-uppercase">Sektor Usaha</label>.*?</div>\s*</div>', "", content, flags=re.DOTALL)
content = re.sub(r'\s*<div class="col-12 col-md-6">\s*<div class="detail-item">\s*<label class="text-muted small fw-semibold text-uppercase">Bidang Usaha</label>.*?</div>\s*</div>', "", content, flags=re.DOTALL)

with open(path, "w", encoding="utf-8") as f: f.write(content)
print("Removed from pendaftaran/show.blade.php")
