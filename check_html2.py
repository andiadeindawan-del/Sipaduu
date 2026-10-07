import re

views = [
    r'c:\laragon\www\SIPADUU\resources\views\peserta\profile\index.blade.php',
    r'c:\laragon\www\SIPADUU\resources\views\admin\users\edit.blade.php'
]

for view in views:
    with open(view, 'r', encoding='utf-8') as f:
        html = f.read()
    
    # Check if id="dokumen" exists
    if 'id="dokumen"' in html:
        print(f"id=\"dokumen\" FOUND in {view}")
    else:
        print(f"id=\"dokumen\" MISSING in {view}")
    
    # Let's count how many tab-pane there are
    count = html.count('class="tab-pane')
    print(f"  {count} tab-panes found.")
