import json

with open(r'c:\laragon\www\SIPADUU\users_columns.json', 'r') as f:
    columns = json.load(f)

with open(r'c:\laragon\www\SIPADUU\app\Models\User.php', 'r', encoding='utf-8') as f:
    content = f.read()

import re
fillable_match = re.search(r'\$fillable = \[(.*?)\];', content, re.DOTALL)
fill_str = fillable_match.group(1)
fill_keys = re.findall(r"'([a-zA-Z0-9_]+)'", fill_str)

missing_in_db = [k for k in fill_keys if k not in columns]

print("Fields in $fillable but MISSING in DB users table:")
for m in sorted(missing_in_db):
    print(f"- {m}")

