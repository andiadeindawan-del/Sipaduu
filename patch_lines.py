
def patch_file(path, npwp_line, produk_line):
    with open(path, "r", encoding="utf-8") as f:
        lines = f.readlines()
        
    npwp_html = """                                        <div id="npwp-upload-container">
                                            <div class="input-group mb-2">
                                                <input type="file" class="form-control @error('npwp_file') is-invalid @enderror" name="npwp_file[]" accept=".jpg,.jpeg,.png,.pdf" {{ $user->npwp_file ? '' : 'required' }}>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-secondary mb-2" id="add-npwp-btn"><i class="bi bi-plus-circle me-1"></i>Tambah Kolom File NPWP</button>
                                        <br>
                                        <small class="text-muted">Format: PDF, JPG, PNG. Maksimal 5MB.</small>\n"""

    produk_html = """                                        <div id="produk-upload-container">
                                            <div class="input-group mb-2">
                                                <input type="file" class="form-control @error('file_produk') is-invalid @enderror" name="file_produk[]" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" {{ $user->file_produk ? '' : 'required' }}>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-secondary mb-2" id="add-produk-btn"><i class="bi bi-plus-circle me-1"></i>Tambah Kolom File Produk</button>
                                        <br>
                                        <small class="text-muted">Format: PDF, DOC, JPG, PNG. Maksimal 5MB.</small>\n"""
                                        
    # Replace NPWP (2 lines: input + small)
    lines[npwp_line] = npwp_html
    lines[npwp_line+1] = ""
    
    # Replace Produk (2 lines: input + small)
    lines[produk_line] = produk_html
    lines[produk_line+1] = ""
    
    with open(path, "w", encoding="utf-8") as f:
        f.writelines(lines)
    print("Patched " + path)

# For index.blade.php
patch_file("resources/views/peserta/profile/index.blade.php", 800, 832)
