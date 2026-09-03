import re
with open("resources/views/admin/users/show.blade.php", "r", encoding="utf-8") as f: content = f.read()
# Replace the second occurrence or just find and remove the one after Masukan & Saran
pattern = r'\s*<div class="col-12 mt-3">\s*<div class="detail-item">\s*<label class="text-muted small fw-semibold text-uppercase">Anggota Koperasi</label>\s*<p class="fw-semibold mb-0">{{ \$user->anggota_koperasi \?\? '-' }}</p>\s*</div>\s*</div>(?=\s*<!-- File Produk moved to Dokumen Section -->)'
content = re.sub(pattern, "", content)
with open("resources/views/admin/users/show.blade.php", "w", encoding="utf-8") as f: f.write(content)
