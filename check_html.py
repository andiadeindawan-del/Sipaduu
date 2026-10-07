import re
from bs4 import BeautifulSoup

views = [
    r'c:\laragon\www\SIPADUU\resources\views\peserta\profile\index.blade.php',
    r'c:\laragon\www\SIPADUU\resources\views\admin\users\edit.blade.php'
]

for view in views:
    with open(view, 'r', encoding='utf-8') as f:
        html = f.read()
    
    # We want to check if `id="dokumen"` exists and what it contains.
    match = re.search(r'<div class="tab-pane fade" id="dokumen" role="tabpanel">', html)
    if match:
        print(f"Found dokumen in {view}")
    else:
        print(f"MISSING dokumen in {view}")
