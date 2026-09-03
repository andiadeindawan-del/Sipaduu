import re

path = "resources/views/auth/register.blade.php"
with open(path, "r", encoding="utf-8") as f:
    content = f.read()

# Pattern for Jenis Usaha block in register
pattern = r'\s*<div class="field-grid">\s*<!-- Jenis Usaha -->\s*<div class="field">\s*<label for="jenis_usaha">Jenis Usaha.*?</div>\s*</div>\s*</div>'
content = re.sub(pattern, "", content, flags=re.DOTALL)

with open(path, "w", encoding="utf-8") as f:
    f.write(content)
print("Removed jenis_usaha from register.blade.php")
