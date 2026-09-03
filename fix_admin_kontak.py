import re

path = "resources/views/admin/users/edit.blade.php"
with open(path, "r", encoding="utf-8") as f:
    content = f.read()

# Insert Kontak Usaha before Email Usaha
block_to_insert = """
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Kontak Usaha (No. Telepon/HP) <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="no_telepon_usaha" required value="{{ old('no_telepon_usaha', $user->no_telepon_usaha) }}">
                                    </div>"""

content = content.replace('<label class="form-label fw-semibold">Email Usaha', block_to_insert + '\n                                    <label class="form-label fw-semibold">Email Usaha')

with open(path, "w", encoding="utf-8") as f:
    f.write(content)

print("Restored and placed Kontak Usaha in admin edit.blade.php")
