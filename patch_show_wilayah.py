import re

def patch_file(path):
    with open(path, "r", encoding="utf-8") as f:
        content = f.read()

    # 1. Insert wilayah_pemasaran after TikTok
    # find:
    #                               <div class="mb-0">
    #                                   <label class="text-muted small fw-semibold text-uppercase d-block">TikTok</label>
    #                                   ...
    #                                   @endif
    #                               </div>
    #                           </div>
    #                       </div>
    ui_insert = """
                                <div class="mt-2">
                                    <label class="text-muted small fw-semibold text-uppercase d-block">Wilayah Pemasaran</label>
                                    <span class="fw-bold text-dark">{{ $user->wilayah_pemasaran ?? '-' }}</span>
                                </div>"""

    # We can match `<div class="mb-0">\s*<label[^>]*>TikTok</label>.*?</label>\s*@if.*?@endif\s*</div>` and append the ui.
    pattern1 = re.compile(r'(<div class="mb-0">\s*<label[^>]*>TikTok</label>.*?</div>)', re.DOTALL)
    content = pattern1.sub(r'\1' + ui_insert, content, count=1)
    
    # Also I will change `<div class="mb-0">` of TikTok to `<div class="mb-2">` so it has spacing before Wilayah Pemasaran.
    # Actually simpler: replace `class="mb-0"` to `class="mb-2"` just for TikTok.
    tiktok_block = re.search(r'<div class="mb-0">\s*<label[^>]*>TikTok</label>.*?</label>\s*@if.*?@endif\s*</div>', content, re.DOTALL)
    if tiktok_block:
        replaced_tiktok = tiktok_block.group(0).replace('mb-0', 'mb-2')
        content = content.replace(tiktok_block.group(0), replaced_tiktok)

    # 2. Remove old duplicated fields: Media Sosial and old Marketplace
    #                           <div class="col-12 col-md-6">
    #                               <div class="detail-item">
    #                                   <label class="text-muted small fw-semibold text-uppercase">Media Sosial</label>
    # ...
    #                               </div>
    #                           </div>
    #                           <div class="col-12 col-md-6">
    #                               <div class="detail-item">
    #                                   <label class="text-muted small fw-semibold text-uppercase">Marketplace</label>
    # ...
    #                               </div>
    #                           </div>
    
    old_fields_pattern = re.compile(r'<div class="col-12 col-md-6">\s*<div class="detail-item">\s*<label class="text-muted small fw-semibold text-uppercase">Media Sosial</label>.*?</div>\s*</div>\s*<div class="col-12 col-md-6">\s*<div class="detail-item">\s*<label class="text-muted small fw-semibold text-uppercase">Marketplace</label>.*?</div>\s*</div>', re.DOTALL)
    
    content = old_fields_pattern.sub('', content)

    with open(path, "w", encoding="utf-8") as f:
        f.write(content)
        
patch_file("resources/views/admin/users/show.blade.php")
print("Done patching show.blade.php")

