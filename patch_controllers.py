import re

def update_controller(path, is_admin=False):
    with open(path, "r", encoding="utf-8") as f:
        content = f.read()
    
    # Validation Rules
    old_val = r"'website_usaha'\s*=>\s*'nullable\|.*?max:\d+',\s*'facebook_usaha'\s*=>\s*'nullable\|.*?max:\d+',\s*'instagram_usaha'\s*=>\s*'nullable\|.*?max:\d+',\s*'tiktok_usaha'\s*=>\s*'nullable\|.*?max:\d+',\s*'marketplace'\s*=>\s*'nullable\|string',"
    
    # In UserController it might be ordered differently: facebook, instagram, tiktok, website, marketplace
    old_val_admin = r"'facebook_usaha'\s*=>\s*'nullable\|.*?max:\d+',\s*'instagram_usaha'\s*=>\s*'nullable\|.*?max:\d+',\s*'tiktok_usaha'\s*=>\s*'nullable\|.*?max:\d+',\s*'website_usaha'\s*=>\s*'nullable\|.*?max:\d+',\s*'marketplace'\s*=>\s*'nullable\|string',"
    
    new_val = """'judul_usaha_online' => 'nullable|string|max:255',
            'website_usaha' => 'nullable|url|max:255',
            'facebook_usaha' => 'nullable|url|max:255',
            'instagram_usaha' => 'nullable|url|max:255',
            'tiktok_usaha' => 'nullable|url|max:255',
            'shopee' => 'nullable|url|max:255',
            'tokopedia' => 'nullable|url|max:255',
            'lazada' => 'nullable|url|max:255',
            'blibli' => 'nullable|url|max:255',
            'marketplace_lainnya_nama' => 'nullable|array',
            'marketplace_lainnya_nama.*' => 'nullable|string|max:150',
            'marketplace_lainnya_link' => 'nullable|array',
            'marketplace_lainnya_link.*' => 'nullable|url|max:255',"""
            
    if is_admin:
        content = re.sub(old_val_admin, new_val, content)
    else:
        content = re.sub(old_val, new_val, content)

    # Controller logic mapping
    logic = """
        // Mapping marketplace lainnya
        $marketplaces = [];
        if (isset($validated['marketplace_lainnya_nama']) && is_array($validated['marketplace_lainnya_nama'])) {
            foreach ($validated['marketplace_lainnya_nama'] as $index => $nama) {
                $link = $validated['marketplace_lainnya_link'][$index] ?? null;
                if (!empty($nama) || !empty($link)) {
                    $marketplaces[] = [
                        'nama' => $nama,
                        'link' => $link
                    ];
                }
            }
        }
        $validated['marketplace_lainnya'] = $marketplaces;
        unset($validated['marketplace_lainnya_nama']);
        unset($validated['marketplace_lainnya_link']);
        
        $user->fill($validated);"""
        
    old_logic = r"\$user->fill\(\$validated\);"
    content = re.sub(old_logic, logic, content)
    
    with open(path, "w", encoding="utf-8") as f:
        f.write(content)
        
update_controller("app/Http/Controllers/ProfileController.php", False)
update_controller("app/Http/Controllers/UserController.php", True)
print("Controllers updated")
