
def sync_marketing_ui():
    with open("resources/views/admin/users/edit.blade.php", "r", encoding="utf-8") as f:
        admin_lines = f.readlines()

    # Lines 409 to 523 (0-indexed 408:523)
    admin_block = "".join(admin_lines[409:523])
    
    # Read peserta file
    with open("resources/views/peserta/profile/index.blade.php", "r", encoding="utf-8") as f:
        peserta_lines = f.readlines()
        
    start_p = -1
    end_p = -1
    for i, line in enumerate(peserta_lines):
        if "SALURAN PEMASARAN ONLINE</h6>" in line:
            start_p = i - 1 # start at <div class="col-12">
        if "Informasi Operasional & Pemasaran -->" in line:
            end_p = i - 1
            break
            
    if start_p == -1 or end_p == -1:
        print("Could not find block in peserta")
        return
        
    new_peserta_lines = peserta_lines[:start_p] + [admin_block + "\n"] + peserta_lines[end_p:]
    
    with open("resources/views/peserta/profile/index.blade.php", "w", encoding="utf-8") as f:
        f.writelines(new_peserta_lines)
        
    print(f"Synced! Replaced {start_p} to {end_p}")

sync_marketing_ui()
