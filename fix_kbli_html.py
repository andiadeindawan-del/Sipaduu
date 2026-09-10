
import re

path = "resources/views/admin/users/edit.blade.php"
with open(path, "r", encoding="utf-8") as f:
    content = f.read()

# The regex to match the old block
pattern = re.compile(r'<div class="col-12 mt-4 mb-2">\s*<h6 class="fw-bold border-bottom pb-2">KBLI / Kegiatan Usaha.*?<small class="text-muted d-block"><span class="text-danger">\*</span> Pilih minimal 1 KBLI dan tentukan KBLI utama\.</small>\s*</div>', re.DOTALL)

new_kbli_html = """                                <div class="col-12 mt-4 mb-2">
                                    <h6 class="fw-bold mb-2 border-bottom pb-2"><i class="bi bi-tags me-2"></i>BIDANG USAHA <span class="text-danger">*</span></h6>
                                    <p class="text-muted small mb-3">Peserta wajib memiliki minimal satu KBLI Utama. Anda dapat menambahkan beberapa jenis usaha lain yang relevan.</p>
                                    
                                    <div id="kbli-repeater-container"></div>
                                    
                                    <div class="mt-3 mb-4">
                                        <button type="button" class="btn btn-sm btn-outline-primary fw-bold" id="btn-add-usaha">
                                            <i class="bi bi-plus-circle me-1"></i> Tambah KBLI / Usaha Lainnya
                                        </button>
                                    </div>
                                </div>"""

if pattern.search(content):
    content = pattern.sub(new_kbli_html, content)
    with open(path, "w", encoding="utf-8") as f:
        f.write(content)
    print("Fixed KBLI HTML!")
else:
    print("Could not find KBLI HTML to replace")
