import re

def fix_file(path):
    with open(path, "r", encoding="utf-8") as f:
        content = f.read()

    # The block to move:
    js_to_move = """
    // Add Marketplace Lainnya dynamically
    $('#add-mp-btn').on('click', function() {
        let html = `
            <div class="row g-2 mb-2 mp-row">
                <div class="col-md-5">
                    <input type="text" class="form-control" name="marketplace_lainnya_nama[]" placeholder="Nama Marketplace (cth: Bukalapak)">
                </div>
                <div class="col-md-6">
                    <input type="url" class="form-control" name="marketplace_lainnya_link[]" placeholder="Link Marketplace (https://...)">
                </div>
                <div class="col-md-1 d-flex align-items-center">
                    <button type="button" class="btn btn-danger btn-sm w-100 remove-mp"><i class="bi bi-trash"></i></button>
                </div>
            </div>
        `;
        $('#marketplace-container').append(html);
    });

    $(document).on('click', '.remove-mp', function() {
        $(this).closest('.mp-row').remove();
    });
"""
    # Remove it from the current position
    # The current position might have different indentation, so we use regex or string replace.
    # We can just match the content exactly since it was appended directly.
    # Wait, the appended content had exact spacing as defined in the string.
    
    # Let's find it using regex:
    pattern = r"\s*// Add Marketplace Lainnya dynamically.*?\}\);"
    # Wait, the second block also ends with });
    pattern = r"\s*// Add Marketplace Lainnya dynamically.*?remove-mp.*?\}\);"
    
    content_without = re.sub(pattern, "", content, flags=re.DOTALL)
    
    # Now insert it before "function calculateTotalKaryawan()"
    insert_pattern = r"\s*// Auto-calculate total karyawan"
    
    final_content = re.sub(insert_pattern, js_to_move + "\n    // Auto-calculate total karyawan", content_without, flags=re.DOTALL)
    
    with open(path, "w", encoding="utf-8") as f:
        f.write(final_content)
    print(f"Fixed {path}")

fix_file("resources/views/peserta/profile/index.blade.php")
fix_file("resources/views/admin/users/edit.blade.php")

