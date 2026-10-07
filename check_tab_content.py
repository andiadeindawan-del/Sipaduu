import re

with open(r'c:\laragon\www\SIPADUU\resources\views\peserta\profile\index.blade.php', 'r', encoding='utf-8') as f:
    html = f.read()

idx_start = html.find('<div class="tab-content" id="profileTabsContent">')
idx_end = html.find('</form>', idx_start)

tab_content = html[idx_start:idx_end]

# Let's count <div> and </div>
div_open = tab_content.count('<div')
div_close = tab_content.count('</div')

print(f"Open: {div_open}, Close: {div_close}")

# Let's check for each tab
tabs = ['pribadi', 'usaha', 'digital', 'tambahan', 'dokumen']
for i, tab in enumerate(tabs):
    t_start = tab_content.find(f'id="{tab}"')
    if i < len(tabs) - 1:
        t_end = tab_content.find(f'id="{tabs[i+1]}"')
    else:
        t_end = len(tab_content)
    
    t_content = tab_content[t_start:t_end]
    o = t_content.count('<div')
    c = t_content.count('</div')
    print(f"Tab {tab}: Open={o}, Close={c}, diff={o-c}")
