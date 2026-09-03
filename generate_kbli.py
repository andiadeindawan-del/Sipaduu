import json

data = [
    {
        "kode": "10710",
        "judul": "INDUSTRI PRODUK ROTI DAN KUE",
        "uraian": "Kelompok ini mencakup usaha pembuatan berbagai macam roti, kue, dan produk bakeri lainnya.",
        "kategori_kode": "C",
        "kategori_nama": "INDUSTRI PENGOLAHAN",
        "golongan_pokok_kode": "10",
        "golongan_pokok_nama": "INDUSTRI MAKANAN",
        "golongan_kode": "107",
        "golongan_nama": "INDUSTRI MAKANAN LAINNYA",
        "subgolongan_kode": "1071",
        "subgolongan_nama": "INDUSTRI PRODUK ROTI DAN KUE",
        "kelompok_kode": "10710",
        "kelompok_nama": "INDUSTRI PRODUK ROTI DAN KUE",
        "aktif": True
    },
    {
        "kode": "47215",
        "judul": "PERDAGANGAN ECERAN HASIL PERIKANAN",
        "uraian": "Kelompok ini mencakup usaha perdagangan eceran hasil perikanan seperti ikan, udang, kepiting.",
        "kategori_kode": "G",
        "kategori_nama": "PERDAGANGAN BESAR DAN ECERAN; REPARASI DAN PERAWATAN MOBIL DAN SEPEDA MOTOR",
        "golongan_pokok_kode": "47",
        "golongan_pokok_nama": "PERDAGANGAN ECERAN, BUKAN MOBIL DAN MOTOR",
        "golongan_kode": "472",
        "golongan_nama": "PERDAGANGAN ECERAN MAKANAN, MINUMAN, DAN TEMBAKAU",
        "subgolongan_kode": "4721",
        "subgolongan_nama": "PERDAGANGAN ECERAN MAKANAN",
        "kelompok_kode": "47215",
        "kelompok_nama": "PERDAGANGAN ECERAN HASIL PERIKANAN",
        "aktif": True
    },
    {
        "kode": "56102",
        "judul": "RUMAH/WARUNG MAKAN",
        "uraian": "Kelompok ini mencakup penyediaan layanan makan minum kepada konsumen di tempat berupa rumah makan atau warung makan.",
        "kategori_kode": "I",
        "kategori_nama": "PENYEDIAAN AKOMODASI DAN PENYEDIAAN MAKAN MINUM",
        "golongan_pokok_kode": "56",
        "golongan_pokok_nama": "PENYEDIAAN MAKANAN DAN MINUMAN",
        "golongan_kode": "561",
        "golongan_nama": "RUMAH MAKAN DAN PENYEDIAAN MAKANAN KELILING",
        "subgolongan_kode": "5610",
        "subgolongan_nama": "RUMAH MAKAN DAN PENYEDIAAN MAKANAN KELILING",
        "kelompok_kode": "56102",
        "kelompok_nama": "RUMAH/WARUNG MAKAN",
        "aktif": True
    },
    {
        "kode": "03111",
        "judul": "PENANGKAPAN PISCES/IKAN BERSIRIP DI LAUT",
        "uraian": "Usaha penangkapan ikan bersirip (pisces) di perairan laut.",
        "kategori_kode": "A",
        "kategori_nama": "PERTANIAN, KEHUTANAN DAN PERIKANAN",
        "golongan_pokok_kode": "03",
        "golongan_pokok_nama": "PERIKANAN",
        "golongan_kode": "031",
        "golongan_nama": "PERIKANAN TANGKAP",
        "subgolongan_kode": "0311",
        "subgolongan_nama": "PERIKANAN TANGKAP LAUT",
        "kelompok_kode": "03111",
        "kelompok_nama": "PENANGKAPAN PISCES/IKAN BERSIRIP DI LAUT",
        "aktif": True
    },
    {
        "kode": "10510",
        "judul": "INDUSTRI PENGOLAHAN SUSU",
        "uraian": "Mencakup pembuatan susu cair segar, pasteurisasi, dan produk olahan susu lainnya.",
        "kategori_kode": "C",
        "kategori_nama": "INDUSTRI PENGOLAHAN",
        "golongan_pokok_kode": "10",
        "golongan_pokok_nama": "INDUSTRI MAKANAN",
        "golongan_kode": "105",
        "golongan_nama": "INDUSTRI PENGOLAHAN SUSU",
        "subgolongan_kode": "1051",
        "subgolongan_nama": "INDUSTRI PENGOLAHAN SUSU",
        "kelompok_kode": "10510",
        "kelompok_nama": "INDUSTRI PENGOLAHAN SUSU",
        "aktif": True
    }
]

# Write to file
with open('database/data/kbli_2025_koperindag.json', 'w') as f:
    json.dump(data, f, indent=4)
