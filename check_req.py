import re

user_model_path = r'c:\laragon\www\SIPADUU\app\Models\User.php'
view_path = r'c:\laragon\www\SIPADUU\resources\views\peserta\profile\index.blade.php'

with open(user_model_path, 'r', encoding='utf-8') as f:
    content = f.read()

req_match = re.search(r'public function getRequiredProfilFields\(\)\s*\{.*?return \[(.*?)\];\s*\}', content, re.DOTALL)
req_fields = []
if req_match:
    block = req_match.group(1)
    # find all keys: 'key' => 'Label'
    matches = re.findall(r"'([a-zA-Z0-9_]+)'\s*=>", block)
    req_fields = matches

print("Fields strictly required in User.php:")
print(req_fields)
