import re

user_model_path = r'c:\laragon\www\SIPADUU\app\Models\User.php'

with open(user_model_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Remove sektor_usaha and bidang_usaha fields from getRequiredProfilFields()
content = re.sub(r"^\s*'sektor_usaha'\s*=>\s*'Sektor Usaha',\n", "", content, flags=re.MULTILINE)
content = re.sub(r"^\s*'bidang_usaha'\s*=>\s*'Bidang Usaha',\n", "", content, flags=re.MULTILINE)

with open(user_model_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("Removed sektor_usaha and bidang_usaha from getRequiredProfilFields()")
