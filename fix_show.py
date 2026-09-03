import re

path = r"c:\laragon\www\SIPADUU\resources\views\admin\users\show.blade.php"
with open(path, "r", encoding="utf-8") as f:
    content = f.read()

# 1. Remove Jenis Usaha
jenis_usaha_pattern = re.compile(r'\s*<div class="col-12 col-md-6">\s*<div class="detail-item">\s*<label class="text-muted small fw-semibold text-uppercase">Jenis Usaha</label>.*?</div>\s*</div>', re.DOTALL)
content = jenis_usaha_pattern.sub("", content, count=1)

# 2. Add Uraian to KBLI block
# Find </ul>
# And add <li><strong>Uraian:</strong> {{ $kbli->kbli->uraian }}</li> before it
uraian_html = r'                                                    @if($kbli->kbli->uraian)<li class="mt-1"><strong>Uraian:</strong> <span class="text-muted">{{ $kbli->kbli->uraian }}</span></li>@endif\n                                                </ul>'
content = re.sub(r'                                                </ul>', uraian_html, content, count=1)

with open(path, "w", encoding="utf-8") as f:
    f.write(content)
print("Updated show.blade.php")
