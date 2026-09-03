import re

peserta_path = "resources/views/peserta/profile/index.blade.php"
admin_path = "resources/views/admin/users/edit.blade.php"

new_ui = """                                    <div class="col-12 mt-4">
                                        <h6 class="fw-bold mb-3 border-bottom pb-2"><i class="bi bi-people me-2"></i>Tenaga Kerja <span class="text-danger">*</span></h6>
                                        <p class="text-muted small mb-2"><i class="bi bi-info-circle me-1"></i> Isi jumlah karyawan (isi 0 jika tidak ada).</p>
                                    </div>
                                    
                                    <!-- KARYAWAN TETAP -->
                                    <div class="col-12 mt-2">
                                        <div class="card bg-light border-0 shadow-sm">
                                            <div class="card-header bg-primary text-white fw-bold">
                                                TOTAL KARYAWAN TETAP
                                            </div>
                                            <div class="card-body">
                                                <div class="row g-3">
                                                    <div class="col-12 col-md-4">
                                                        <label class="form-label fw-semibold">Laki-laki <span class="text-danger">*</span></label>
                                                        <input type="number" class="form-control karyawan-input" id="tetap_laki_laki" name="karyawan_tetap_laki_laki" value="{{ old('karyawan_tetap_laki_laki', $user->karyawan_tetap_laki_laki ?? 0) }}" min="0" step="1" required>
                                                    </div>
                                                    <div class="col-12 col-md-4">
                                                        <label class="form-label fw-semibold">Perempuan <span class="text-danger">*</span></label>
                                                        <input type="number" class="form-control karyawan-input" id="tetap_perempuan" name="karyawan_tetap_perempuan" value="{{ old('karyawan_tetap_perempuan', $user->karyawan_tetap_perempuan ?? 0) }}" min="0" step="1" required>
                                                    </div>
                                                    <div class="col-12 col-md-4">
                                                        <label class="form-label fw-semibold">Total</label>
                                                        <input type="number" class="form-control" id="total_tetap" readonly value="{{ old('total_karyawan_tetap', $user->total_karyawan_tetap ?? 0) }}" style="background-color: #e9ecef; font-weight: bold;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- KARYAWAN TIDAK TETAP -->
                                    <div class="col-12 mt-3">
                                        <div class="card bg-light border-0 shadow-sm">
                                            <div class="card-header bg-secondary text-white fw-bold">
                                                TOTAL KARYAWAN TIDAK TETAP
                                            </div>
                                            <div class="card-body">
                                                <div class="row g-3">
                                                    <div class="col-12 col-md-4">
                                                        <label class="form-label fw-semibold">Laki-laki <span class="text-danger">*</span></label>
                                                        <input type="number" class="form-control karyawan-input" id="tidak_tetap_laki_laki" name="karyawan_tidak_tetap_laki_laki" value="{{ old('karyawan_tidak_tetap_laki_laki', $user->karyawan_tidak_tetap_laki_laki ?? 0) }}" min="0" step="1" required>
                                                    </div>
                                                    <div class="col-12 col-md-4">
                                                        <label class="form-label fw-semibold">Perempuan <span class="text-danger">*</span></label>
                                                        <input type="number" class="form-control karyawan-input" id="tidak_tetap_perempuan" name="karyawan_tidak_tetap_perempuan" value="{{ old('karyawan_tidak_tetap_perempuan', $user->karyawan_tidak_tetap_perempuan ?? 0) }}" min="0" step="1" required>
                                                    </div>
                                                    <div class="col-12 col-md-4">
                                                        <label class="form-label fw-semibold">Total</label>
                                                        <input type="number" class="form-control" id="total_tidak_tetap" readonly value="{{ old('total_karyawan_tidak_tetap', $user->total_karyawan_tidak_tetap ?? 0) }}" style="background-color: #e9ecef; font-weight: bold;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- TOTAL KESELURUHAN -->
                                    <div class="col-12 mt-3">
                                        <div class="card border-primary shadow-sm text-center">
                                            <div class="card-body bg-primary text-white rounded">
                                                <h5 class="fw-bold mb-2">TOTAL TENAGA KERJA</h5>
                                                <h2 class="mb-0 fw-bold" id="grand_total">{{ old('total_tenaga_kerja', $user->total_tenaga_kerja ?? 0) }}</h2>
                                            </div>
                                        </div>
                                    </div>"""

new_js = """    // Auto-calculate total karyawan
    function calculateTotalKaryawan() {
        var tetapL = parseInt($('#tetap_laki_laki').val()) || 0;
        var tetapP = parseInt($('#tetap_perempuan').val()) || 0;
        var totalTetap = tetapL + tetapP;
        $('#total_tetap').val(totalTetap);

        var tidakTetapL = parseInt($('#tidak_tetap_laki_laki').val()) || 0;
        var tidakTetapP = parseInt($('#tidak_tetap_perempuan').val()) || 0;
        var totalTidakTetap = tidakTetapL + tidakTetapP;
        $('#total_tidak_tetap').val(totalTidakTetap);

        var grandTotal = totalTetap + totalTidakTetap;
        $('#grand_total').text(grandTotal);
    }
    
    $('.karyawan-input').on('input', calculateTotalKaryawan);"""

def replace_in_file(path, is_admin=False):
    with open(path, "r", encoding="utf-8") as f:
        content = f.read()

    # Replace UI
    # In index.blade.php the container has <div class="col-12 mt-4">...Tenaga Kerja...
    # We find that block and replace it up to <div class="col-12 col-md-4">...Total Karyawan...</div>
    if is_admin:
        old_ui_pattern = r'\s*<div class="col-12 mt-4 mb-2">\s*<h6 class="fw-bold mb-0 border-bottom pb-2"><i class="bi bi-people me-2"></i>Tenaga Kerja</h6>.*?<label class="form-label fw-semibold">Total Karyawan</label>\s*<input type="number" class="form-control" id="total_karyawan" name="total_karyawan".*?</div>'
        # Slight modification for admin:
        admin_new_ui = new_ui.replace('<div class="col-12 mt-4">', '<div class="col-12 mt-4 mb-2">')
        content = re.sub(old_ui_pattern, admin_new_ui, content, flags=re.DOTALL)
    else:
        old_ui_pattern = r'\s*<div class="col-12 mt-4">\s*<h6 class="fw-bold mb-3 border-bottom pb-2"><i class="bi bi-people me-2"></i>Tenaga Kerja <span class="text-danger">\*</span></h6>.*?<label class="form-label fw-semibold">Total Karyawan</label>\s*<input type="number" class="form-control" id="total_karyawan" name="total_karyawan".*?</div>'
        content = re.sub(old_ui_pattern, new_ui, content, flags=re.DOTALL)

    # Replace JS
    old_js_pattern = r'\s*// Auto-calculate total karyawan.*?\$[(]'\.karyawan-input'[)]\.on[(]'input', calculateTotalKaryawan[)];'
    content = re.sub(old_js_pattern, "\n" + new_js, content, flags=re.DOTALL)
    
    with open(path, "w", encoding="utf-8") as f:
        f.write(content)
    print(f"Updated {path}")

replace_in_file(peserta_path, is_admin=False)
replace_in_file(admin_path, is_admin=True)
