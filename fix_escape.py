import re

views = [
    r'c:\laragon\www\SIPADUU\resources\views\peserta\profile\index.blade.php',
    r'c:\laragon\www\SIPADUU\resources\views\admin\users\edit.blade.php'
]

for view in views:
    with open(view, 'r', encoding='utf-8') as f:
        content = f.read()
    
    content = content.replace(r"\'provinsi_usaha\'", "'provinsi_usaha'")
    content = content.replace(r"\'kabupaten_usaha\'", "'kabupaten_usaha'")
    content = content.replace(r"\'kecamatan_usaha\'", "'kecamatan_usaha'")
    content = content.replace(r"\'desa_usaha\'", "'desa_usaha'")
    
    with open(view, 'w', encoding='utf-8') as f:
        f.write(content)

print("Fixed backslash escaping")
