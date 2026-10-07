<?php
$content = file_get_contents("resources/views/admin/pendaftaran/show.blade.php");
$fillables = ["marketplace", "pengadaan_barang", "akses_kredit", "tabungan", "perizinan_usaha", "sertifikasi_produk", "jangkauan_pemasaran", "lokasi_pemasaran", "status_ekspor", "negara_ekspor", "metode_ekspor", "volume_ekspor", "nilai_ekspor", "pasok_bahan_baku", "kemitraan"];
$missing = [];
foreach($fillables as $f) {
    if(strpos($content, $f) === false) {
        $missing[] = $f;
    }
}
echo "Missing: " . implode(", ", $missing);

