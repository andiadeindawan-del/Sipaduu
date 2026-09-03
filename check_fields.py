import re
with open('resources/views/peserta/profile/index.blade.php', encoding='utf-8') as f: p = f.read()
with open('resources/views/admin/users/edit.blade.php', encoding='utf-8') as f: a = f.read()
p_fields = set(re.findall(r'name=.([a-zA-Z0-9_\[\]]+).', p))
a_fields = set(re.findall(r'name=.([a-zA-Z0-9_\[\]]+).', a))
print('In Peserta NOT Admin:', p_fields - a_fields)
print('In Admin NOT Peserta:', a_fields - p_fields)