
path = "resources/views/admin/users/edit.blade.php"
with open(path, "r", encoding="utf-8") as f:
    content = f.read()

bad_html = """                                    <div class="col-12 col-md-6">
                                        
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Kontak Usaha (No. Telepon/HP) <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="no_telepon_usaha" required value="{{ old('no_telepon_usaha', $user->no_telepon_usaha) }}">
                                    </div>
                                    <label class="form-label fw-semibold">Email Usaha <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" name="email_usaha" required value="{{ old('email_usaha', $user->email_usaha) }}">
                                    </div>"""

good_html = """                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Kontak Usaha (No. Telepon/HP) <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="no_telepon_usaha" required value="{{ old('no_telepon_usaha', $user->no_telepon_usaha) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Email Usaha <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" name="email_usaha" required value="{{ old('email_usaha', $user->email_usaha) }}">
                                    </div>"""

content = content.replace(bad_html, good_html)

with open(path, "w", encoding="utf-8") as f:
    f.write(content)
print("Fixed edit.blade.php")
