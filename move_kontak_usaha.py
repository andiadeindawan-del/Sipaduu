import re

files = [
    "resources/views/peserta/profile/index.blade.php",
    "resources/views/admin/users/edit.blade.php"
]

for path in files:
    with open(path, "r", encoding="utf-8") as f:
        content = f.read()
    
    # 1. Find the block for no_telepon_usaha
    block_pattern = r'\s*<div class="col-12 col-md-6">\s*<label class="form-label fw-semibold">No\. Telepon Usaha.*?name="no_telepon_usaha".*?</div>'
    match = re.search(block_pattern, content, flags=re.DOTALL)
    
    if match:
        block_content = match.group(0)
        
        # Remove the block from its original position
        content = content.replace(block_content, "")
        
        # Change label slightly to better match the user's wording "Kontak Usaha"
        block_content = block_content.replace("No. Telepon Usaha", "Kontak Usaha (No. Telepon/HP)")
        
        # 2. Insert it under "Alamat Kontak Usaha" and above "Email Usaha"
        insert_target = r'(<h6[^>]*>.*?Alamat Kontak Usaha.*?</h6>\s*</div>)'
        
        # Put it right after the closing div of the h6 heading block
        content = re.sub(insert_target, r'\1' + block_content, content, flags=re.DOTALL)
        
        with open(path, "w", encoding="utf-8") as f:
            f.write(content)
        
        print(f"Moved Kontak Usaha in {path}")
    else:
        print(f"Block not found in {path}")
