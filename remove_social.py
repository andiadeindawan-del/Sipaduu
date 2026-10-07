import re

user_model_path = r'c:\laragon\www\SIPADUU\app\Models\User.php'

with open(user_model_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Remove social media fields from getRequiredProfilFields()
content = re.sub(r"^\s*'facebook_usaha'\s*=>\s*'Facebook Usaha',\n", "", content, flags=re.MULTILINE)
content = re.sub(r"^\s*'instagram_usaha'\s*=>\s*'Instagram Usaha',\n", "", content, flags=re.MULTILINE)
content = re.sub(r"^\s*'tiktok_usaha'\s*=>\s*'TikTok Usaha',\n", "", content, flags=re.MULTILINE)

with open(user_model_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("Removed social media fields from getRequiredProfilFields()")
