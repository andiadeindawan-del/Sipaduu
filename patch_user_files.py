import re

def patch_file(path):
    with open(path, "r", encoding="utf-8") as f:
        content = f.read()
    
    old_logic = re.compile(r"if \(\$request->hasFile\('npwp_file'\)\).*?\$data\['file_produk'\] = \$request->file\('file_produk'\)->store\('produk_files', 'public'\);\s*\}", re.DOTALL)
    
    new_logic = r"""if ($request->hasFile('npwp_file')) {
            if (is_array($user->npwp_file)) {
                foreach ($user->npwp_file as $oldFile) {
                    if ($oldFile && Storage::disk('public')->exists($oldFile)) {
                        Storage::disk('public')->delete($oldFile);
                    }
                }
            }
            $paths = [];
            foreach ($request->file('npwp_file') as $file) {
                $paths[] = $file->store('npwp_files', 'public');
            }
            $data['npwp_file'] = $paths;
        }

        if ($request->hasFile('file_produk')) {
            if (is_array($user->file_produk)) {
                foreach ($user->file_produk as $oldFile) {
                    if ($oldFile && Storage::disk('public')->exists($oldFile)) {
                        Storage::disk('public')->delete($oldFile);
                    }
                }
            }
            $paths = [];
            foreach ($request->file('file_produk') as $file) {
                $paths[] = $file->store('produk_files', 'public');
            }
            $data['file_produk'] = $paths;
        }"""
        
    content = old_logic.sub(new_logic, content)

    with open(path, "w", encoding="utf-8") as f:
        f.write(content)
    print(f"Patched {path}")

patch_file("app/Http/Controllers/UserController.php")
