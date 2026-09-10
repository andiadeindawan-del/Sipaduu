import re

def update_controller(path):
    with open(path, "r", encoding="utf-8") as f:
        content = f.read()
    
    # 1. Update Validation logic
    v_npwp = r"'npwp_file'\s*=>\s*\[\$user->npwp_file\s*\?\s*'nullable'\s*:\s*'required',\s*'file',\s*'mimes:pdf,jpeg,png,jpg',\s*'max:5120'\],"
    v_npwp_new = """'npwp_file' => [$user->npwp_file ? 'nullable' : 'required', 'array'],
            'npwp_file.*' => ['file', 'mimes:pdf,jpeg,png,jpg', 'max:5120'],"""
            
    v_produk = r"'file_produk'\s*=>\s*\[\$user->file_produk\s*\?\s*'nullable'\s*:\s*'required',\s*'file',\s*'mimes:pdf,jpeg,png,jpg,doc,docx',\s*'max:5120'\],"
    v_produk_new = """'file_produk' => [$user->file_produk ? 'nullable' : 'required', 'array'],
            'file_produk.*' => ['file', 'mimes:pdf,jpeg,png,jpg,doc,docx', 'max:5120'],"""

    content = re.sub(v_npwp, v_npwp_new, content)
    content = re.sub(v_produk, v_produk_new, content)
    
    # 2. Update File saving logic
    upload_logic_old = re.compile(r"// Handle NPWP upload.*?if \(\$request->hasFile\('file_produk'\)\).*?\}", re.DOTALL)
    
    upload_logic_new = r"""// Handle NPWP upload
        if ($request->hasFile('npwp_file')) {
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
            $validated['npwp_file'] = $paths;
        }

        // Handle File Produk upload
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
            $validated['file_produk'] = $paths;
        }"""
        
    content = upload_logic_old.sub(upload_logic_new, content)
    
    # 3. Update viewDocument in ProfileController
    if "viewDocument" in content:
        view_doc_old = """$path = $targetUser->$column;

        if (!Storage::disk('public')->exists($path)) {"""
        
        view_doc_new = """$path = $targetUser->$column;

        if (is_array($path)) {
            $index = $request->query('index', 0);
            if (!isset($path[$index])) {
                abort(404, 'Document index not found.');
            }
            $path = $path[$index];
        }

        if (!Storage::disk('public')->exists($path)) {"""
        
        content = content.replace(view_doc_old, view_doc_new)

    with open(path, "w", encoding="utf-8") as f:
        f.write(content)
    print(f"Patched {path}")

update_controller("app/Http/Controllers/ProfileController.php")
update_controller("app/Http/Controllers/UserController.php")
