import os

views = [
    r'c:\laragon\www\SIPADUU\resources\views\peserta\profile\index.blade.php',
    r'c:\laragon\www\SIPADUU\resources\views\admin\users\edit.blade.php'
]

save_btn_html = """
                                <div class="mt-4 d-flex justify-content-end pb-3">
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="bi bi-save me-1"></i> Simpan Perubahan
                                    </button>
                                </div>"""

for view in views:
    with open(view, 'r', encoding='utf-8') as f:
        content = f.read()

    # The tabs end at:
    # <!-- TAB USAHA -->
    # <!-- TAB SALURAN PEMASARAN ONLINE -->
    # <!-- TAB KEBUTUHAN PELATIHAN -->
    # <!-- TAB DOKUMEN -->
    # And the last tab ends at:
    # </div>
    #                     </div>
    #                 </div>
    # 
    #                 </form>

    # Wait, in the previous script I already inserted buttons for the first 4 tabs:
    # content = content.replace('<!-- TAB USAHA -->', save_btn_html + '\n                            <!-- TAB USAHA -->')
    # So I only need to insert the button for the DOKUMEN tab.

    # How do we find the end of the DOKUMEN tab?
    # It is right before the </div> that closes tab-content.
    
    # Let's just find exactly this block:
    find_str = """                            </div>
                        </div>
                    </div>

                </form>"""
    
    rep_str = f"""{save_btn_html}
                            </div>
                        </div>
                    </div>

                </form>"""
    
    content = content.replace(find_str, rep_str)
    
    # Just in case the whitespace is slightly different:
    # Let's use a regex to be safe.
    import re
    # We want to match the closing of tab-content and form.
    content = re.sub(r'(\s*</div>\s*</div>\s*</div>\s*</form>)', r'\n' + save_btn_html + r'\1', content)

    # Let's also check if there are multiple buttons now because I already ran `replace` in the previous script.
    # The previous script did NOT save the DOKUMEN button, but DID save the others.
    # Let's make sure we don't have multiple buttons.
    # We can do this by just counting.
    count = content.count('Simpan Perubahan')
    print(f"{view} has {count} save buttons")

    with open(view, 'w', encoding='utf-8') as f:
        f.write(content)

print("Done injecting last button")
