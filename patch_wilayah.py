import re

ui = """                                                    <div class="col-12 mt-3">
                                                        <label class="form-label fw-semibold">Wilayah Pemasaran <span class="text-muted">(Opsional)</span></label>
                                                        <select class="form-select" name="wilayah_pemasaran">
                                                            <option value="">Pilih Wilayah Pemasaran</option>
                                                            <option value="Dalam Satu Kabupaten" {{ old('wilayah_pemasaran', $user->wilayah_pemasaran) == 'Dalam Satu Kabupaten' ? 'selected' : '' }}>Dalam Satu Kabupaten</option>
                                                            <option value="Lintas Kabupaten" {{ old('wilayah_pemasaran', $user->wilayah_pemasaran) == 'Lintas Kabupaten' ? 'selected' : '' }}>Lintas Kabupaten</option>
                                                            <option value="Lintas Provinsi" {{ old('wilayah_pemasaran', $user->wilayah_pemasaran) == 'Lintas Provinsi' ? 'selected' : '' }}>Lintas Provinsi</option>
                                                            <option value="Nasional" {{ old('wilayah_pemasaran', $user->wilayah_pemasaran) == 'Nasional' ? 'selected' : '' }}>Nasional</option>
                                                            <option value="Ekspor" {{ old('wilayah_pemasaran', $user->wilayah_pemasaran) == 'Ekspor' ? 'selected' : '' }}>Ekspor</option>
                                                        </select>
                                                    </div>
                                                </div>"""

def patch_file(path):
    with open(path, "r", encoding="utf-8") as f:
        content = f.read()

    # The end of the "TikTok" block:
    # <input type="url" class="form-control" name="tiktok_usaha" value="{{ old('tiktok_usaha', $user->tiktok_usaha) }}" placeholder="https://tiktok.com/@contoh">
    # </div>
    # </div>
    pattern = re.compile(r'(<input type="url" class="form-control" name="tiktok_usaha".*?</div>\s*)</div>', re.DOTALL)
    
    content = pattern.sub(r'\1' + ui, content)

    with open(path, "w", encoding="utf-8") as f:
        f.write(content)

patch_file("resources/views/peserta/profile/index.blade.php")
patch_file("resources/views/admin/users/edit.blade.php")

