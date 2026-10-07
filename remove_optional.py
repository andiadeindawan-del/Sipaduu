import re

user_model_path = r'c:\laragon\www\SIPADUU\app\Models\User.php'

with open(user_model_path, 'r', encoding='utf-8') as f:
    content = f.read()

# We need to remove 'status_pernikahan' and 'npwp_usaha' from getRequiredProfilFields()
content = re.sub(r"^\s*'status_pernikahan'\s*=>\s*'Status Pernikahan',\n", "", content, flags=re.MULTILINE)
content = re.sub(r"^\s*'npwp_usaha'\s*=>\s*'Nomor NPWP',\n", "", content, flags=re.MULTILINE)

with open(user_model_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("Removed status_pernikahan and npwp_usaha from getRequiredProfilFields()")
