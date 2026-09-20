import re
import os

views = [
    r'c:\laragon\www\SIPADUU\resources\views\peserta\profile\index.blade.php',
    r'c:\laragon\www\SIPADUU\resources\views\admin\users\edit.blade.php'
]

html_replacements = [
    (
        r'<input type="text" class="form-control" name="provinsi_usaha".*?>',
        r'<select class="form-select @error(\'provinsi_usaha\') is-invalid @enderror" id="provinsi_usaha" name="provinsi_usaha">\n<option value="Sulawesi Barat">Sulawesi Barat</option>\n</select>'
    ),
    (
        r'<input type="text" class="form-control" name="kabupaten_usaha".*?>',
        r'<select class="form-select @error(\'kabupaten_usaha\') is-invalid @enderror" id="kabupaten_usaha" name="kabupaten_usaha">\n<option value="">Pilih Kabupaten/Kota</option>\n</select>'
    ),
    (
        r'<input type="text" class="form-control" name="kecamatan_usaha".*?>',
        r'<select class="form-select @error(\'kecamatan_usaha\') is-invalid @enderror" id="kecamatan_usaha" name="kecamatan_usaha">\n<option value="">Pilih Kecamatan</option>\n</select>'
    ),
    (
        r'<input type="text" class="form-control" name="desa_usaha".*?>',
        r'<select class="form-select @error(\'desa_usaha\') is-invalid @enderror" id="desa_usaha" name="desa_usaha">\n<option value="">Pilih Desa/Kelurahan</option>\n</select>'
    )
]

js_addition = """
        // USAHA
        const kabUsahaSelect = document.getElementById('kabupaten_usaha');
        const kecUsahaSelect = document.getElementById('kecamatan_usaha');
        const desaUsahaSelect = document.getElementById('desa_usaha');
        
        const oldKabUsaha = "{{ old('kabupaten_usaha', $user->kabupaten_usaha) }}";
        const oldKecUsaha = "{{ old('kecamatan_usaha', $user->kecamatan_usaha) }}";
        const oldDesaUsaha = "{{ old('desa_usaha', $user->desa_usaha) }}";

        if(kabUsahaSelect) {
            fetch('/data/wilayah-sulbar.json')
                .then(response => response.json())
                .then(data => {
                    // Make data available for dependent selects
                    if(typeof wilayahData === 'undefined') {
                        window.wilayahData = data;
                    }
                    kabUsahaSelect.innerHTML = '<option value="">Pilih Kabupaten/Kota</option>';
                    data.forEach(kab => {
                        const option = document.createElement('option');
                        option.value = kab.name;
                        option.textContent = kab.name;
                        kabUsahaSelect.appendChild(option);
                    });
                    kabUsahaSelect.disabled = false;
                    if (oldKabUsaha) {
                        kabUsahaSelect.value = oldKabUsaha;
                        kabUsahaSelect.dispatchEvent(new Event('change'));
                    }
                });

            kabUsahaSelect.addEventListener('change', function() {
                kecUsahaSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
                desaUsahaSelect.innerHTML = '<option value="">Pilih Desa/Kelurahan</option>';
                kecUsahaSelect.disabled = true;
                desaUsahaSelect.disabled = true;

                const selectedKab = (window.wilayahData || wilayahData).find(k => k.name === this.value);
                if (selectedKab && selectedKab.kecamatan) {
                    selectedKab.kecamatan.forEach(kec => {
                        const option = document.createElement('option');
                        option.value = kec.name;
                        option.textContent = kec.name;
                        kecUsahaSelect.appendChild(option);
                    });
                    kecUsahaSelect.disabled = false;
                    if (oldKecUsaha && kecUsahaSelect.querySelector(`option[value="${oldKecUsaha}"]`)) {
                        kecUsahaSelect.value = oldKecUsaha;
                        kecUsahaSelect.dispatchEvent(new Event('change'));
                    }
                }
            });

            kecUsahaSelect.addEventListener('change', function() {
                desaUsahaSelect.innerHTML = '<option value="">Pilih Desa/Kelurahan</option>';
                desaUsahaSelect.disabled = true;

                const selectedKab = (window.wilayahData || wilayahData).find(k => k.name === kabUsahaSelect.value);
                if (selectedKab) {
                    const selectedKec = selectedKab.kecamatan.find(k => k.name === this.value);
                    if (selectedKec && selectedKec.desa) {
                        selectedKec.desa.forEach(desa => {
                            const option = document.createElement('option');
                            option.value = desa.name;
                            option.textContent = desa.name;
                            desaUsahaSelect.appendChild(option);
                        });
                        desaUsahaSelect.disabled = false;
                        if (oldDesaUsaha && desaUsahaSelect.querySelector(`option[value="${oldDesaUsaha}"]`)) {
                            desaUsahaSelect.value = oldDesaUsaha;
                        }
                    }
                }
            });
        }
"""

for view in views:
    if not os.path.exists(view): 
        print(f"Skipping {view}")
        continue
    with open(view, 'r', encoding='utf-8') as f:
        content = f.read()

    for pattern, repl in html_replacements:
        content = re.sub(pattern, repl, content)
    
    # Add JS
    if 'kabUsahaSelect' not in content:
        content = content.replace('});\n</script>\n\n<script src="https://code.jquery.com/jquery', js_addition + '\n    });\n</script>\n\n<script src="https://code.jquery.com/jquery')
        
    with open(view, 'w', encoding='utf-8') as f:
        f.write(content)

print("Patch applied to all views")
