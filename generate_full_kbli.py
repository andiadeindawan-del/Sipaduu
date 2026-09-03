import urllib.request
import json
import re

print("Downloading KBLI data...")
url = "https://raw.githubusercontent.com/UserGhost411/KBLI-2020-Dataset/main/kbli_lengkap.json"
req = urllib.request.Request(url, headers={"User-Agent": "Mozilla"})
res = urllib.request.urlopen(req)
data = json.loads(res.read().decode("utf-8"))

# Mapping categories
categories = {
    "A": {"name": "PERTANIAN, KEHUTANAN DAN PERIKANAN", "range": range(1, 4)},
    "B": {"name": "PERTAMBANGAN DAN PENGGALIAN", "range": range(5, 10)},
    "C": {"name": "INDUSTRI PENGOLAHAN", "range": range(10, 34)},
    "D": {"name": "PENGADAAN LISTRIK, GAS, UAP/AIR PANAS DAN UDARA DINGIN", "range": [35]},
    "E": {"name": "PENGADAAN AIR, PENGELOLAAN SAMPAH DAN DAUR ULANG, PEMBUANGAN DAN PEMBERSIHAN LIMBAH DAN SAMPAH", "range": range(36, 40)},
    "F": {"name": "KONSTRUKSI", "range": range(41, 44)},
    "G": {"name": "PERDAGANGAN BESAR DAN ECERAN; REPARASI DAN PERAWATAN MOBIL DAN SEPEDA MOTOR", "range": range(45, 48)},
    "H": {"name": "PENGANGKUTAN DAN PERGUDANGAN", "range": range(49, 54)},
    "I": {"name": "PENYEDIAAN AKOMODASI DAN PENYEDIAAN MAKAN MINUM", "range": range(55, 57)},
    "J": {"name": "INFORMASI DAN KOMUNIKASI", "range": range(58, 64)},
    "K": {"name": "AKTIVITAS KEUANGAN DAN ASURANSI", "range": range(64, 67)},
    "L": {"name": "REAL ESTAT", "range": [68]},
    "M": {"name": "AKTIVITAS PROFESIONAL, ILMIAH DAN TEKNIS", "range": range(69, 76)},
    "N": {"name": "AKTIVITAS PENYEWAAN DAN SEWA GUNA USAHA TANPA HAK OPSI, KETENAGAKERJAAN, AGEN PERJALANAN DAN PENUNJANG USAHA LAINNYA", "range": range(77, 83)},
    "O": {"name": "ADMINISTRASI PEMERINTAHAN, PERTAHANAN DAN JAMINAN SOSIAL WAJIB", "range": [84]},
    "P": {"name": "PENDIDIKAN", "range": [85]},
    "Q": {"name": "AKTIVITAS KESEHATAN MANUSIA DAN AKTIVITAS SOSIAL", "range": range(86, 89)},
    "R": {"name": "KESENIAN, HIBURAN DAN REKREASI", "range": range(90, 94)},
    "S": {"name": "AKTIVITAS JASA LAINNYA", "range": range(94, 97)},
    "T": {"name": "AKTIVITAS RUMAH TANGGA SEBAGAI PEMBERI KERJA; AKTIVITAS YANG MENGHASILKAN BARANG DAN JASA OLEH RUMAH TANGGA YANG DIGUNAKAN UNTUK MEMENUHI KEBUTUHAN SENDIRI", "range": range(97, 99)},
    "U": {"name": "AKTIVITAS BADAN INTERNASIONAL DAN BADAN EKSTRA INTERNASIONAL LAINNYA", "range": [99]}
}

def get_kategori(code_2d):
    num = int(code_2d)
    for cat_code, cat_info in categories.items():
        if num in cat_info["range"]:
            return cat_code, cat_info["name"]
    return None, None

# Dictionary to map codes to titles
title_map = {}
for item in data:
    title_map[item["code"]] = item["title"]

result = []
for item in data:
    code = item["code"]
    if len(code) != 5:
        continue # only insert the actual 5-digit KBLI
    
    code_2d = code[0:2]
    code_3d = code[0:3]
    code_4d = code[0:4]
    
    cat_code, cat_name = get_kategori(code_2d)
    
    entry = {
        "kode": code,
        "judul": item["title"],
        "uraian": item.get("desc", ""),
        "versi": "KBLI 2025",
        "kategori_kode": cat_code,
        "kategori_nama": cat_name,
        "golongan_pokok_kode": code_2d,
        "golongan_pokok_nama": title_map.get(code_2d, ""),
        "golongan_kode": code_3d,
        "golongan_nama": title_map.get(code_3d, ""),
        "subgolongan_kode": code_4d,
        "subgolongan_nama": title_map.get(code_4d, ""),
        "kelompok_kode": code,
        "kelompok_nama": item["title"]
    }
    result.append(entry)

print("Found", len(result), "KBLI items.")
with open("database/data/kbli_2025_koperindag.json", "w", encoding="utf-8") as f:
    json.dump(result, f, indent=2)
print("Saved to database/data/kbli_2025_koperindag.json")
