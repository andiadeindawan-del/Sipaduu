import re

path = "resources/views/admin/users/edit.blade.php"
with open(path, "r", encoding="utf-8") as f:
    content = f.read()

# Find the block for no_telepon_usaha
block_pattern = r'\s*<div class="col-12 col-md-6">\s*<label class="form-label fw-semibold">Nomor Telepon Usaha.*?name="no_telepon_usaha".*?</div>'
match = re.search(block_pattern, content, flags=re.DOTALL)

if match:
    block_content = match.group(0)
    
    # Remove the block from its original position
    content = content.replace(block_content, "")
    
    # Change label slightly to better match the user's wording "Kontak Usaha"
    block_content = block_content.replace("Nomor Telepon Usaha", "Kontak Usaha (No. Telepon/HP)")
    
    # Insert it under "Alamat Kontak Usaha" and above "Email Usaha"
    # In admin/users/edit.blade.php the heading might be slightly different.
    # Let's search for Alamat Kontak Usaha heading.
    insert_target = r'(<h6[^>]*>.*?Alamat Kontak Usaha.*?</h6>\s*</div>)'
    
    content = re.sub(insert_target, r'\1' + block_content, content, flags=re.DOTALL)
    
    with open(path, "w", encoding="utf-8") as f:
        f.write(content)
    
    print(f"Moved Kontak Usaha in {path}")
else:
    print(f"Block not found in {path}")
