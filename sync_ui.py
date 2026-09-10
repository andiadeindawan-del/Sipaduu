
import re

def sync_marketing_ui():
    with open("resources/views/admin/users/edit.blade.php", "r", encoding="utf-8") as f:
        admin_content = f.read()

    # Extract marketing block from edit.blade.php
    start_admin = admin_content.find("<!-- INFORMASI USAHA ONLINE -->")
    end_admin = admin_content.find("</div>", admin_content.find("id=\"add-mp-btn\"")) + 6
    end_admin = admin_content.find("</div>", end_admin) + 6
    end_admin = admin_content.find("</div>", end_admin) + 6
    end_admin = admin_content.find("</div>", end_admin) + 6
    
    # Just use regex to get the block accurately
    match = re.search(r'<!-- INFORMASI USAHA ONLINE -->.*?<!-- INFORMASI OPERASIONAL & PEMASARAN -->', admin_content, re.DOTALL)
    if not match:
        print("Could not find block in admin")
        return
    admin_block = match.group(0).replace("<!-- INFORMASI OPERASIONAL & PEMASARAN -->", "").strip()

    # Read peserta file
    with open("resources/views/peserta/profile/index.blade.php", "r", encoding="utf-8") as f:
        peserta_content = f.read()
    
    # Replace in peserta file
    match_p = re.search(r'<!-- TAB SALURAN PEMASARAN ONLINE -->.*?<div class="tab-pane fade" id="digital" role="tabpanel">.*?<div class="row g-3">.*?(<div class="col-12">.*?<h6 class="fw-bold mb-3 border-bottom pb-2"><i class="bi bi-globe me-2"></i>SALURAN PEMASARAN ONLINE</h6>.*?<!-- Informasi Operasional & Pemasaran -->)', peserta_content, re.DOTALL)
    
    if not match_p:
        print("Could not find block in peserta")
        return
        
    old_block = match_p.group(1)
    
    # Note: we need to append the "Informasi Operasional" comment back
    new_block = admin_block + "\n\n                                    <!-- Informasi Operasional & Pemasaran -->"
    
    new_peserta = peserta_content.replace(old_block, new_block)
    
    with open("resources/views/peserta/profile/index.blade.php", "w", encoding="utf-8") as f:
        f.write(new_peserta)
        
    print("Synced!")

sync_marketing_ui()
