import re

def update_controller(path):
    with open(path, "r", encoding="utf-8") as f:
        content = f.read()

    # 1. Update Validation Rules
    old_validation = r"'jumlah_karyawan_laki_laki'\s*=>\s*'required\|integer\|min:0',\s*'jumlah_karyawan_perempuan'\s*=>\s*'required\|integer\|min:0',"
    new_validation = """'karyawan_tetap_laki_laki' => 'required|integer|min:0',
            'karyawan_tetap_perempuan' => 'required|integer|min:0',
            'karyawan_tidak_tetap_laki_laki' => 'required|integer|min:0',
            'karyawan_tidak_tetap_perempuan' => 'required|integer|min:0',"""
    content = re.sub(old_validation, new_validation, content)

    # 2. Update Calculation Logic
    old_calc = r"\$validated\['total_karyawan'\]\s*=\s*(.*?);"
    new_calc = """$validated['total_karyawan_tetap'] = ((int) ($validated['karyawan_tetap_laki_laki'] ?? 0)) + ((int) ($validated['karyawan_tetap_perempuan'] ?? 0));
        $validated['total_karyawan_tidak_tetap'] = ((int) ($validated['karyawan_tidak_tetap_laki_laki'] ?? 0)) + ((int) ($validated['karyawan_tidak_tetap_perempuan'] ?? 0));
        $validated['total_tenaga_kerja'] = $validated['total_karyawan_tetap'] + $validated['total_karyawan_tidak_tetap'];"""
    content = re.sub(old_calc, new_calc, content)

    with open(path, "w", encoding="utf-8") as f:
        f.write(content)
    print(f"Updated {path}")

update_controller("app/Http/Controllers/ProfileController.php")
update_controller("app/Http/Controllers/UserController.php")
