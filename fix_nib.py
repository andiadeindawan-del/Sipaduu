import re

with open("resources/views/auth/register.blade.php", "r", encoding="utf-8") as f:
    content = f.read()

# Change label
content = content.replace(
    '<label for="nib">NIB <span style="color:red">*</span></label>',
    '<label for="nib">NIB <span class="text-muted" style="font-weight:normal; font-size:0.9em">(Opsional)</span></label>'
)

# Remove required attribute
pattern = r'(<input type="text"[^>]*id="nib"[^>]*placeholder="Nomor Induk Berusaha")\s*required>'
content = re.sub(pattern, r"\1>", content)

with open("resources/views/auth/register.blade.php", "w", encoding="utf-8") as f:
    f.write(content)

print("Done")
