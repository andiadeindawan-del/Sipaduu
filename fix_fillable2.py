import re

user_model_path = r'c:\laragon\www\SIPADUU\app\Models\User.php'

with open(user_model_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Make sure it's not already in fillable
fillable_match = re.search(r'\$fillable = \[(.*?)\];', content, re.DOTALL)
if fillable_match:
    fillable_block = fillable_match.group(1)
    if 'bentuk_usaha' not in fillable_block:
        content = content.replace(
            "// Data Usaha UMK\n        'jabatan_usaha',",
            "// Data Usaha UMK\n        'status_usaha', 'bentuk_usaha', 'jabatan_usaha',"
        )
        
        with open(user_model_path, 'w', encoding='utf-8') as f:
            f.write(content)
        print("Added status_usaha and bentuk_usaha to $fillable.")
    else:
        print("Already in fillable block.")
