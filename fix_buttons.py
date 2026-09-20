import re
import os

views = [
    'resources/views/peserta/profile/index.blade.php',
    'resources/views/admin/users/edit.blade.php'
]

btn_regex = r'\s*<div class="mt-4 d-flex justify-content-end pb-3">\s*<button type="submit" class="btn btn-primary px-4">\s*<i class="bi bi-save me-1"></i> Simpan Perubahan\s*</button>\s*</div>'

save_btn_html = """
                                    <div class="mt-4 d-flex justify-content-end pb-3">
                                        <button type="submit" class="btn btn-primary px-4">
                                            <i class="bi bi-save me-1"></i> Simpan Perubahan
                                        </button>
                                    </div>"""

for view in views:
    if not os.path.exists(view): continue
    with open(view, 'r', encoding='utf-8') as f:
        content = f.read()
    
    # Remove all injected save buttons
    content = re.sub(btn_regex, '', content)
    
    # We want to replace:
    #                                 </div>
    #                             </div>
    # 
    #                             <!-- TAB
    # with:
    #                                 </div>
    # [button]
    #                             </div>
    #
    #                             <!-- TAB
    
    content = re.sub(r'(\s*</div>\s*)(</div>\s*<!-- TAB)', r'\1' + save_btn_html + r'\2', content)
    
    # For the last tab (dokumen), it ends with:
    #                                 </div>
    #                             </div>
    #                         </div>
    #                     </div>
    #                 </div>
    #             </form>
    # We can just look for the first </form> after DOKUMEN tab.
    # Actually, we can replace:
    # (\s*</div>\s*)(</div>\s*</div>\s*</div>\s*</div>\s*</form>)
    # But it's easier to just do:
    # (\s*</div>\s*)(</div>\s*</div>\s*</div>\s*</div>\s*</form>)
    # Let's try:
    content = re.sub(r'(\s*</div>\s*)(</div>\s*</div>\s*</div>\s*</div>\s*</form>)', r'\1' + save_btn_html + r'\2', content)
    
    # If the regex doesn't match perfectly, we can fallback to replacing:
    # "                            </div>\n                        </div>\n                    </div>\n                </form>"
    # Wait, the exact string for the end of the form is:
    end_form_str = """
                            </div>
                        </div>
                    </div>
                </form>"""
    # So the tab pane ends right before this.
    # Let's just do a specific replacement.
    
    with open(view, 'w', encoding='utf-8') as f:
        f.write(content)

print("Buttons fixed")
