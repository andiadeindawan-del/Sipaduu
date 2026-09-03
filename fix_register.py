import re

with open("resources/views/auth/register.blade.php", "r", encoding="utf-8") as f:
    content = f.read()

pattern = r'\s*<div class="field-grid">\s*<!-- Jenis Usaha -->.*?@enderror\s*</div>\s*</div>(?=\s*<!-- ALAMAT SULAWESI BARAT -->)'
new_content = re.sub(pattern, "", content, flags=re.DOTALL)

with open("resources/views/auth/register.blade.php", "w", encoding="utf-8") as f:
    f.write(new_content)

print("Done")
