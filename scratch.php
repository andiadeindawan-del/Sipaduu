<?php
$content = file_get_contents("resources/views/admin/pendaftaran/show.blade.php");
$fillables = ["nik", "nama", "no_telepon", "nama_usaha", "nib", "jenis_usaha", "provinsi", "kabupaten", "kecamatan", "desa", "alamat_lengkap", "status_pernikahan", "jenis_kelamin", "tempat_lahir", "tanggal_lahir", "agama", "pendidikan_terakhir", "kode_pos_domisili", "disabilitas", "ktp_file", "status_usaha", "bentuk_usaha", "jabatan_usaha", "merek_produk", "kode_pos_usaha", "sektor_usaha", "no_telepon_usaha", "bidang_usaha", "tanggal_berdiri", "npwp_usaha", "status_nib", "lama_nib", "modal_usaha", "nilai_modal", "omzet_usaha", "nilai_omzet", "jumlah_karyawan", "kapasitas_produksi", "anggota_koperasi", "karyawan_tetap_laki_laki", "karyawan_tetap_perempuan", "total_karyawan_tetap", "karyawan_tidak_tetap_laki_laki", "karyawan_tidak_tetap_perempuan", "total_karyawan_tidak_tetap", "total_tenaga_kerja", "provinsi_usaha", "kabupaten_usaha", "kecamatan_usaha", "desa_usaha", "alamat_usaha", "email_usaha", "website_usaha", "judul_usaha_online", "shopee", "tokopedia", "lazada", "blibli", "marketplace_lainnya", "wilayah_pemasaran", "medsos_usaha", "marketplace", "facebook_usaha", "instagram_usaha", "tiktok_usaha", "pengadaan_barang", "akses_kredit", "tabungan", "perizinan_usaha", "sertifikasi_produk", "jangkauan_pemasaran", "lokasi_pemasaran", "status_ekspor", "negara_ekspor", "metode_ekspor", "volume_ekspor", "nilai_ekspor", "pasok_bahan_baku", "kemitraan", "permasalahan", "kebutuhan_diklat", "riwayat_pelatihan", "jenis_pelatihan_diikuti", "file_produk", "masukan_saran", "nib_file", "npwp_file"];
$missing = [];
foreach($fillables as $f) {
    if(strpos($content, $f) === false) {
        $missing[] = $f;
    }
}
echo "Missing fields:\n" . implode("\n", $missing) . "\n";

