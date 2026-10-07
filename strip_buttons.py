import re
import os

views = [
    r'c:\laragon\www\SIPADUU\resources\views\peserta\profile\index.blade.php',
    r'c:\laragon\www\SIPADUU\resources\views\admin\users\edit.blade.php'
]

btn_pattern = re.compile(r'\s*<div class="mt-4 d-flex justify-content-end[^>]*>\s*<button type="submit" class="btn btn-primary px-4">\s*<i class="bi bi-save me-1"></i> Simpan Perubahan\s*</button>\s*</div>')

# There might also be `</div></div>` which was my previous mistake.
btn_pattern2 = re.compile(r'\s*<div class="mt-4 d-flex justify-content-end[^>]*>\s*<button type="submit" class="btn btn-primary px-4">\s*<i class="bi bi-save me-1"></i> Simpan Perubahan\s*</button>\s*</div></div>')


for view in views:
    with open(view, 'r', encoding='utf-8') as f:
        content = f.read()

    # Completely remove all save buttons and their weird closing divs
    content = btn_pattern2.sub('</div>', content)
    content = btn_pattern.sub('', content)

    # Now, add exactly one button before the end of each tab pane
    # The tab panes end right before the next `<!-- TAB ... -->` or `</div>\n                </form>`
    
    save_btn_html = """
                                <div class="mt-4 d-flex justify-content-end pb-3">
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="bi bi-save me-1"></i> Simpan Perubahan
                                    </button>
                                </div>"""

    # Fix the replacements
    # Tab Pribadi ends before <!-- TAB USAHA -->
    content = content.replace('<!-- TAB USAHA -->', save_btn_html + '\n                            <!-- TAB USAHA -->')
    # Tab Usaha ends before <!-- TAB SALURAN PEMASARAN ONLINE -->
    content = content.replace('<!-- TAB SALURAN PEMASARAN ONLINE -->', save_btn_html + '\n                            <!-- TAB SALURAN PEMASARAN ONLINE -->')
    # Tab Saluran Pemasaran Online ends before <!-- TAB KEBUTUHAN PELATIHAN -->
    content = content.replace('<!-- TAB KEBUTUHAN PELATIHAN -->', save_btn_html + '\n                            <!-- TAB KEBUTUHAN PELATIHAN -->')
    # Tab Kebutuhan Pelatihan ends before <!-- TAB DOKUMEN -->
    content = content.replace('<!-- TAB DOKUMEN -->', save_btn_html + '\n                            <!-- TAB DOKUMEN -->')
    # Tab Dokumen ends before </form> closing tags. We find: `</form>` and insert before the </div> that wraps the tab content.
    # Actually, let's find `</div>\n                </form>` or similar.
    # Wait, the last tab is Dokumen. It ends somewhere. Let's just look for the first </form> after TAB DOKUMEN.
    
    # Wait, for the last tab, let's inject it by looking for the end of the tab-content div.
    # Let's find:
    # </div>
    # 
    # </form>
    # And replace with the button.
    
    # Let's just find `</form>` and do a negative lookbehind if possible.
    # We can inject it right before `</form>`. Since all tabs are inside the form, a button right before `</form>` will be outside the tab panes?
    # No! The user wants the button IN the tab pane, but actually placing it outside the tab content (at the bottom of the form) means it is visible for ALL tabs!
    # Wait, if we place it outside `.tab-content`, it will be visible at the bottom no matter which tab is active! That is ONE button for the whole page.
    # Is that what the user wants? "padahal saya cuman butuh 1 fitur saja tiap simpan perubahan di data pribadi , data usaha, saluran pemasaran online, kebutuhan pelatihan, dokumen"
    # "tiap datanya ada fitur simpan perubahan. ... tiap peserta bisa menyimpan tiap data dulusebelum mengisi data lainnya"
    # It means they want ONE save button PER TAB.
    
    with open(view, 'w', encoding='utf-8') as f:
        f.write(content)

print("Buttons stripped. Now we need to handle the last tab.")
