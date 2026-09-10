import re

def patch_file(path):
    with open(path, "r", encoding="utf-8") as f:
        content = f.read()

    # 1. NPWP replacement
    npwp_old = re.compile(r'<input type="file" multiple class="form-control @error\('npwp_file'\) is-invalid @enderror" name="npwp_file\[\]" accept="\.jpg,\.jpeg,\.png,\.pdf" \{\{ \$user->npwp_file \? ''' : 'required' \}\}>.*?lebih dari 1 file\)</small>', re.DOTALL)
    
    npwp_new = """<div id="npwp-upload-container">
                                            <div class="input-group mb-2">
                                                <input type="file" class="form-control @error('npwp_file') is-invalid @enderror" name="npwp_file[]" accept=".jpg,.jpeg,.png,.pdf" {{ $user->npwp_file ? '' : 'required' }}>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-secondary mb-2" id="add-npwp-btn"><i class="bi bi-plus-circle me-1"></i>Tambah Kolom File NPWP</button>
                                        <br>
                                        <small class="text-muted">Format: PDF, JPG, PNG. Maksimal 5MB.</small>"""

    content = npwp_old.sub(npwp_new, content)

    # 2. Produk replacement
    produk_old = re.compile(r'<input type="file" multiple class="form-control @error\('file_produk'\) is-invalid @enderror" name="file_produk\[\]" accept="\.jpg,\.jpeg,\.png,\.pdf,\.doc,\.docx" \{\{ \$user->file_produk \? ''' : 'required' \}\}>.*?lebih dari 1 file\)</small>', re.DOTALL)
    
    produk_new = """<div id="produk-upload-container">
                                            <div class="input-group mb-2">
                                                <input type="file" class="form-control @error('file_produk') is-invalid @enderror" name="file_produk[]" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" {{ $user->file_produk ? '' : 'required' }}>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-secondary mb-2" id="add-produk-btn"><i class="bi bi-plus-circle me-1"></i>Tambah Kolom File Produk</button>
                                        <br>
                                        <small class="text-muted">Format: PDF, DOC, JPG, PNG. Maksimal 5MB.</small>"""

    content = produk_old.sub(produk_new, content)

    # 3. Add JS to the bottom before </script> in the $(document).ready block
    # We will search for:  // Add Marketplace Lainnya dynamically
    # and insert our JS before it.
    js_code = """
    // Multiple file uploads UI
    $('#add-npwp-btn').on('click', function() {
        $('#npwp-upload-container').append(`
            <div class="input-group mb-2 file-row">
                <input type="file" class="form-control" name="npwp_file[]" accept=".jpg,.jpeg,.png,.pdf">
                <button class="btn btn-danger remove-file-btn" type="button"><i class="bi bi-trash"></i></button>
            </div>
        `);
    });

    $('#add-produk-btn').on('click', function() {
        $('#produk-upload-container').append(`
            <div class="input-group mb-2 file-row">
                <input type="file" class="form-control" name="file_produk[]" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                <button class="btn btn-danger remove-file-btn" type="button"><i class="bi bi-trash"></i></button>
            </div>
        `);
    });

    $(document).on('click', '.remove-file-btn', function() {
        $(this).closest('.file-row').remove();
    });

    // Add Marketplace Lainnya dynamically"""

    content = content.replace("// Add Marketplace Lainnya dynamically", js_code)

    with open(path, "w", encoding="utf-8") as f:
        f.write(content)
    print(f"Patched {path}")

patch_file("resources/views/peserta/profile/index.blade.php")
patch_file("resources/views/admin/users/edit.blade.php")
