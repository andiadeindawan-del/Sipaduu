
import re

def extract_names(filepath):
    with open(filepath, "r", encoding="utf-8") as f:
        content = f.read()
    names = re.findall(r'name="([^"]+)"', content)
    return set(names)

peserta_names = extract_names("resources/views/peserta/profile/index.blade.php")
admin_names = extract_names("resources/views/admin/users/edit.blade.php")

# admin typically has _method, _token, password, etc.
missing_in_admin = peserta_names - admin_names
missing_in_peserta = admin_names - peserta_names

print("Missing in Admin:", missing_in_admin)
print("Missing in Peserta:", missing_in_peserta)
