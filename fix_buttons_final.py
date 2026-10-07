import re

views = [
    r'c:\laragon\www\SIPADUU\resources\views\peserta\profile\index.blade.php',
    r'c:\laragon\www\SIPADUU\resources\views\admin\users\edit.blade.php'
]

btn_pattern = re.compile(r'\s*<div class="mt-4 d-flex justify-content-end[^>]*>\s*<button type="submit" class="btn btn-primary px-4">\s*<i class="bi bi-save me-1"></i> Simpan Perubahan\s*</button>\s*</div>')

save_btn_html = """
                                <div class="mt-4 d-flex justify-content-end pb-3">
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="bi bi-save me-1"></i> Simpan Perubahan
                                    </button>
                                </div>"""

for view in views:
    with open(view, 'r', encoding='utf-8') as f:
        content = f.read()

    # 1. Strip ALL existing buttons
    content = btn_pattern.sub('', content)

    # 2. Inject one button inside each tab pane
    # The tab markers are:
    # <!-- TAB USAHA --> (preceded by </div> closing tab pribadi)
    content = re.sub(r'(</div>\s*<!-- TAB USAHA -->)', save_btn_html + r'\n                            \1', content)
    
    # <!-- TAB SALURAN PEMASARAN ONLINE --> (preceded by </div> closing tab usaha)
    content = re.sub(r'(</div>\s*<!-- TAB SALURAN PEMASARAN ONLINE -->)', save_btn_html + r'\n                            \1', content)
    
    # <!-- TAB KEBUTUHAN PELATIHAN --> (preceded by </div> closing tab digital)
    content = re.sub(r'(</div>\s*<!-- TAB KEBUTUHAN PELATIHAN -->)', save_btn_html + r'\n                            \1', content)
    
    # <!-- TAB DOKUMEN --> (preceded by </div> closing tab tambahan)
    content = re.sub(r'(</div>\s*<!-- TAB DOKUMEN -->)', save_btn_html + r'\n                            \1', content)

    # For the last tab (Dokumen), we can find the end of the tab-content
    # The tab-content ends with:
    # </div>
    # 
    # </form>
    # Wait, the end of the Dokumen tab is the </div> right before `</form>`.
    # Let's search for `</form>` and look backwards.
    # We can match:
    # </div>
    #                 </div>
    #             </div>
    # 
    #         </form>
    # (The number of divs may vary).
    # Let's just find `</form>` and insert the button right before the first `</div>` that precedes it.
    
    # Find position of `</form>` that corresponds to the profile form.
    # In `index.blade.php`, there are 2 forms: profile and password.
    # Profile form ends near `</form>\n            </div>\n            \n            <div class="panel">`
    # Let's just find `<!-- TAB DOKUMEN -->` and then the NEXT `</form>`.
    
    idx_dok = content.find('<!-- TAB DOKUMEN -->')
    idx_form_end = content.find('</form>', idx_dok)
    
    # Now find the LAST `</div>` before `</form>` that belongs to the tab-pane.
    # The structure is:
    #     </div>
    # </div>
    # </div>
    # </form>
    # The innermost `</div>` is the one closing the tab-pane.
    # Actually, if we just insert it before `</div>\n                            </div>\n                        </div>\n                    </div>\n\n                </form>`
    # Let's use regex:
    # Match multiple `</div>` before `</form>`
    # Replace the FIRST of those `</div>` with `[save_btn]\n</div>`.
    
    match = re.search(r'(\s*</div>)+\s*</form>', content[idx_dok:])
    if match:
        full_match = match.group(0)
        # full_match starts with some whitespace, then `</div>`.
        # Replace the first `</div>` with `save_btn_html + '\n</div>'`
        new_match = full_match.replace('</div>', save_btn_html + '\n</div>', 1)
        content = content[:idx_dok + match.start()] + new_match + content[idx_dok + match.end():]

    # Print how many we inserted
    count = content.count('Simpan Perubahan')
    print(f"{view} has {count} save buttons")

    with open(view, 'w', encoding='utf-8') as f:
        f.write(content)

print("Fixed buttons again")
