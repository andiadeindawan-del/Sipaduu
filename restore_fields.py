import re

path = "resources/views/peserta/profile/index.blade.php"
with open(path, "r", encoding="utf-8") as f:
    content = f.read()

fields_html = """                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Tanggal Berdiri Usaha <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="tanggal_berdiri" value="{{ old('tanggal_berdiri', $user->tanggal_berdiri) }}" required>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">NPWP Usaha <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="npwp_usaha" value="{{ old('npwp_usaha', $user->npwp_usaha) }}" required>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">NIB <span class="text-muted">(Opsional)</span></label>
                                        <input type="text" class="form-control" name="nib" value="{{ old('nib', $user->nib) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Sumber Pendanaan / Modal <span class="text-muted">(Opsional)</span></label>
                                        <input type="text" class="form-control" name="modal_usaha" value="{{ old('modal_usaha', $user->modal_usaha) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Nilai Modal (Rp) <span class="text-muted">(Opsional)</span></label>
                                        <input type="number" class="form-control" name="nilai_modal" value="{{ old('nilai_modal', $user->nilai_modal) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Kategori Omzet <span class="text-muted">(Opsional)</span></label>
                                        <input type="text" class="form-control" name="omzet_usaha" value="{{ old('omzet_usaha', $user->omzet_usaha) }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Nilai Omzet (Rp) <span class="text-muted">(Opsional)</span></label>
                                        <input type="number" class="form-control" name="nilai_omzet" value="{{ old('nilai_omzet', $user->nilai_omzet) }}">
                                    </div>

                                    <!-- Data Karyawan -->"""

content = content.replace("<!-- Data Karyawan -->", fields_html)

with open(path, "w", encoding="utf-8") as f:
    f.write(content)
print("Restored fields in index.blade.php")
