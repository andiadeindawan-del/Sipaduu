import re
with open('resources/views/admin/users/show.blade.php', encoding='utf-8') as f: s = f.read()
with open('resources/views/admin/users/edit.blade.php', encoding='utf-8') as f: a = f.read()
s_fields = set(re.findall(r'\\\->([a-zA-Z0-9_]+)', s))
a_fields = set(re.findall(r'name=.([a-zA-Z0-9_\[\]]+).', a))
print('In Edit NOT Show:', a_fields - s_fields)
print('In Show NOT Edit:', s_fields - a_fields)