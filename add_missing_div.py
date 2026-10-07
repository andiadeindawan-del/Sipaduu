import re

views = [
    r'c:\laragon\www\SIPADUU\resources\views\peserta\profile\index.blade.php',
    r'c:\laragon\www\SIPADUU\resources\views\admin\users\edit.blade.php'
]

for view in views:
    with open(view, 'r', encoding='utf-8') as f:
        content = f.read()

    # The end of form is currently:
    #                     </div>
    #                 </div>
    #                         <div class="mt-4 d-flex justify-content-end pb-3">
    #                             ...
    #                         </div>
    #             </div>
    #         </form>
    # If the overall tab_content block is missing 1 `</div>`, we need to add it before `</form>`.

    idx_dok = content.find('<!-- TAB DOKUMEN -->')
    if idx_dok != -1:
        # Find </form>
        idx_form = content.find('</form>', idx_dok)
        
        # Add </div> before </form>
        content = content[:idx_form] + '            </div>\n                ' + content[idx_form:]
        print(f"Added </div> in {view}")

    with open(view, 'w', encoding='utf-8') as f:
        f.write(content)
