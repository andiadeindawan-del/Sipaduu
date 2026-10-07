with open(r'c:\laragon\www\SIPADUU\resources\views\peserta\profile\index.blade.php', 'r', encoding='utf-8') as f:
    html = f.read()

s = html.find('id="tambahan"')
e = html.find('id="dokumen"')
print(html[s:e])
