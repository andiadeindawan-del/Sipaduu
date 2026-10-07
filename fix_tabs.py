import re

views = [
    r'c:\laragon\www\SIPADUU\resources\views\peserta\profile\index.blade.php',
    r'c:\laragon\www\SIPADUU\resources\views\admin\users\edit.blade.php'
]

for view in views:
    with open(view, 'r', encoding='utf-8') as f:
        content = f.read()

    # 1. Fix Tambahan missing </div>
    # The button injected in tambahan is right before `<!-- TAB DOKUMEN -->`.
    # Let's find it.
    idx_dok = content.find('<!-- TAB DOKUMEN -->')
    if idx_dok != -1:
        # Look backwards for the injected button in tambahan
        btn_str = '<div class="mt-4 d-flex justify-content-end pb-3">'
        idx_btn = content.rfind(btn_str, 0, idx_dok)
        if idx_btn != -1:
            # Insert a `</div>` BEFORE the button! 
            # Because the button should be outside the `row g-3`, but inside the `tab-pane`.
            # Wait, the button was placed inside `row g-3` or outside?
            # It should be outside `row g-3`, so we need a `</div>` to close `row g-3` before the button.
            content = content[:idx_btn] + '                            </div>\n' + content[idx_btn:]
            print(f"Fixed tambahan in {view}")

    # 2. Fix Dokumen extra </div>
    # Let's look at the end of form.
    idx_form = content.find('</form>', idx_dok)
    if idx_form != -1:
        # Replace 5 consecutive `</div>`s with 4 `</div>`s?
        # Let's count them exactly:
        match = re.search(r'(\s*</div>){5,}\s*</form>', content[idx_dok:])
        if match:
            # If there are 5 `</div>`s, we remove one.
            # We want to replace it with exactly 4 `</div>`s.
            # But wait, what if the button is inside `col-12 col-md-6`?
            # Let's move the button OUT of `col-12 col-md-6`.
            # Currently it is:
            # [button]
            # </div></div>
            # </div>
            # </div>
            # </div>
            
            # Let's find the button in dokumen and move it before the last 3 `</div>`s.
            # Actually, it's easier to just find the button in dokumen, remove it, and inject it properly.
            
            # Find the button after idx_dok
            idx_btn2 = content.find(btn_str, idx_dok)
            if idx_btn2 != -1:
                end_btn2 = content.find('</button>\n                                </div>', idx_btn2)
                end_btn2 += len('</button>\n                                </div>')
                
                # Remove the button
                content = content[:idx_btn2] + content[end_btn2:]
                
                # Now we have the remaining `</div>`s.
                # Let's find `</form>` again.
                idx_form2 = content.find('</form>', idx_dok)
                
                # We want to insert the button right before `</div>\n                    </div>\n\n                </form>`
                # Basically, before the last `</div>` of the tab-pane.
                # Let's find the sequence of `</div>` before `</form>`.
                match2 = re.search(r'(\s*</div>)+\s*</form>', content[idx_dok:])
                if match2:
                    full_match = match2.group(0)
                    # We expect 4 `</div>`s here.
                    # We want to insert the button before the 2nd to last `</div>`.
                    # Let's just rewrite the end of the form perfectly.
                    new_end = f"""                            </div>
                        </div>
                                <div class="mt-4 d-flex justify-content-end pb-3">
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="bi bi-save me-1"></i> Simpan Perubahan
                                    </button>
                                </div>
                    </div>

                </form>"""
                    content = content[:idx_dok + match2.start()] + '\n' + new_end + content[idx_dok + match2.end():]
                    print(f"Fixed dokumen in {view}")

    with open(view, 'w', encoding='utf-8') as f:
        f.write(content)
