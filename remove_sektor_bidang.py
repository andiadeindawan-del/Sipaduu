import re

# 1. Peserta Profile Index
path_peserta = "resources/views/peserta/profile/index.blade.php"
with open(path_peserta, "r", encoding="utf-8") as f: content = f.read()
# Remove sektor_usaha div
content = re.sub(r'\s*<div class="col-12 col-md-6">\s*<label class="form-label fw-semibold">Sektor Usaha <span class="text-danger">\*</span></label>\s*<input type="text" class="form-control" name="sektor_usaha".*?</div>', "", content, flags=re.DOTALL)
# Remove bidang_usaha div
content = re.sub(r'\s*<div class="col-12 col-md-6">\s*<label class="form-label fw-semibold">Bidang Usaha <span class="text-danger">\*</span></label>\s*<input type="text" class="form-control" name="bidang_usaha".*?</div>', "", content, flags=re.DOTALL)
with open(path_peserta, "w", encoding="utf-8") as f: f.write(content)

# 2. Admin Edit
path_admin_edit = "resources/views/admin/users/edit.blade.php"
with open(path_admin_edit, "r", encoding="utf-8") as f: content = f.read()
content = re.sub(r'\s*<div class="col-12 col-md-6">\s*<label class="form-label fw-semibold">Sektor Usaha <span class="text-danger">\*</span></label>\s*<input type="text" class="form-control" name="sektor_usaha".*?</div>', "", content, flags=re.DOTALL)
content = re.sub(r'\s*<div class="col-12 col-md-6">\s*<label class="form-label fw-semibold">Bidang Usaha <span class="text-danger">\*</span></label>\s*<input type="text" class="form-control" name="bidang_usaha".*?</div>', "", content, flags=re.DOTALL)
with open(path_admin_edit, "w", encoding="utf-8") as f: f.write(content)

# 3. Admin Show
path_admin_show = "resources/views/admin/users/show.blade.php"
with open(path_admin_show, "r", encoding="utf-8") as f: content = f.read()
content = re.sub(r'\s*<div class="col-12 col-md-6">\s*<div class="detail-item">\s*<label class="text-muted small fw-semibold text-uppercase">Sektor Usaha</label>.*?</div>\s*</div>', "", content, flags=re.DOTALL)
content = re.sub(r'\s*<div class="col-12 col-md-6">\s*<div class="detail-item">\s*<label class="text-muted small fw-semibold text-uppercase">Bidang Usaha</label>.*?</div>\s*</div>', "", content, flags=re.DOTALL)
with open(path_admin_show, "w", encoding="utf-8") as f: f.write(content)

print("Removed from all three views.")
