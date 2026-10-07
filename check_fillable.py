import re

with open(r'c:\laragon\www\SIPADUU\app\Http\Controllers\ProfileController.php', 'r', encoding='utf-8') as f:
    ctrl = f.read()

# Extract validated keys
match = re.search(r'\$validated = \$request->validate\(\[(.*?)\]\);', ctrl, re.DOTALL)
val_str = match.group(1)
val_keys = re.findall(r"'([a-zA-Z0-9_]+)'\s*=>", val_str)
# filter out .*
val_keys = [k for k in val_keys if not k.endswith('.*')]

with open(r'c:\laragon\www\SIPADUU\app\Models\User.php', 'r', encoding='utf-8') as f:
    user = f.read()

match = re.search(r'\$fillable = \[(.*?)\];', user, re.DOTALL)
fill_str = match.group(1)
fill_keys = re.findall(r"'([a-zA-Z0-9_]+)'", fill_str)

# Also check other assignments in controller
# like $validated['marketplace_lainnya'] = ...
# just consider we want to see what is missing in fillable

missing = [k for k in set(val_keys) if k not in fill_keys]

print("Missing from $fillable:")
for m in sorted(missing):
    print(f"- {m}")
