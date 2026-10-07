import re

user_model_path = r'c:\laragon\www\SIPADUU\app\Models\User.php'

with open(user_model_path, 'r', encoding='utf-8') as f:
    content = f.read()

# We want to add 'status_usaha' and 'bentuk_usaha' to $fillable.
# Let's find '// Data Usaha UMK' and add them after it.
if 'bentuk_usaha' not in content:
    content = content.replace(
        "// Data Usaha UMK\n        'jabatan_usaha',",
        "// Data Usaha UMK\n        'status_usaha', 'bentuk_usaha', 'jabatan_usaha',"
    )
    
    with open(user_model_path, 'w', encoding='utf-8') as f:
        f.write(content)
    print("Added status_usaha and bentuk_usaha to $fillable.")
else:
    print("Already in $fillable?")
