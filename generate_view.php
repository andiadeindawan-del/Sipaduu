<?php
$content = file_get_contents("resources/views/admin/pendaftaran/show.blade.php");

// Helper to create HTML for a field
function makeField($label, $field, $is_array=false) {
    if ($is_array) {
        $val = "{{ is_array(\$registration->user->$field) ? implode(\", \", \$registration->user->$field) : (\$registration->user->$field ?? \"-\") }}";
    } else {
        $val = "{{ \$registration->user->$field ?? \"-\" }}";
    }
    return "
                                <div class=\"col-12 col-md-6\">
                                    <div class=\"detail-item\">
                                        <label class=\"text-muted small fw-semibold text-uppercase\">$label</label>
                                        <p class=\"fw-semibold mb-0\">$val</p>
                                    </div>
                                </div>";
}

function makeFile($label, $field) {
    return "
                                <div class=\"col-12\">
                                    <div class=\"detail-item\">
                                        <label class=\"text-muted small fw-semibold text-uppercase\">$label</label>
                                        <p class=\"fw-semibold mb-0\">
                                            @if(\$registration->user->$field)
                                                <a href=\"{{ asset('storage/' . (is_array(\$registration->user->$field) ? \$registration->user->$field[0] : \$registration->user->$field)) }}\" target=\"_blank\" class=\"text-primary\">
                                                    <i class=\"bi bi-file-earmark-pdf me-1\"></i> Lihat Dokumen
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </p>
                                    </div>
                                </div>";
}

// 1. Pribadi
$pribadi_add = makeField("Provinsi", "provinsi") . makeField("Kabupaten/Kota", "kabupaten") . makeField("Kecamatan", "kecamatan") . makeField("Desa/Kelurahan", "desa");
$content = preg_replace("/(<label class=\"text-muted small fw-semibold text-uppercase\">Alamat Domisili<\/label>)/", $pribadi_add . "\n$1", $content);

// 2. Usaha
$usaha_add1 = makeField("Jenis Usaha", "jenis_usaha") . makeField("Status Usaha", "status_usaha") . makeField("Bentuk Usaha", "bentuk_usaha") . makeField("Sektor Usaha", "sektor_usaha") . makeField("Bidang Usaha", "bidang_usaha");
$usaha_add2 = makeField("Provinsi Usaha", "provinsi_usaha") . makeField("Kabupaten Usaha", "kabupaten_usaha") . makeField("Kecamatan Usaha", "kecamatan_usaha") . makeField("Desa Usaha", "desa_usaha");
$usaha_add3 = makeField("Karyawan Tetap (L)", "karyawan_tetap_laki_laki") . makeField("Karyawan Tetap (P)", "karyawan_tetap_perempuan") . makeField("Total Karyawan Tetap", "total_karyawan_tetap") . makeField("Karyawan Tdk Tetap (L)", "karyawan_tidak_tetap_laki_laki") . makeField("Karyawan Tdk Tetap (P)", "karyawan_tidak_tetap_perempuan") . makeField("Total Karyawan Tdk Tetap", "total_karyawan_tidak_tetap") . makeField("Total Tenaga Kerja", "total_tenaga_kerja");

$content = preg_replace("/(<label class=\"text-muted small fw-semibold text-uppercase\">Nama Usaha<\/label>)/", $usaha_add1 . "\n                                        $1", $content);
$content = preg_replace("/(<label class=\"text-muted small fw-semibold text-uppercase\">Alamat Usaha<\/label>)/", $usaha_add2 . "\n                                        $1", $content);
$content = preg_replace("/(<label class=\"text-muted small fw-semibold text-uppercase\">Jumlah Karyawan<\/label>)/", $usaha_add3 . "\n                                        $1", $content);


// 3. Digital
$digital_add1 = makeField("Judul Usaha Online", "judul_usaha_online") . makeField("Shopee", "shopee") . makeField("Tokopedia", "tokopedia") . makeField("Lazada", "lazada") . makeField("Blibli", "blibli") . makeField("Marketplace Lainnya", "marketplace_lainnya", true) . makeField("Wilayah Pemasaran", "wilayah_pemasaran", true);
$digital_add2 = makeField("Facebook Usaha", "facebook_usaha") . makeField("Instagram Usaha", "instagram_usaha") . makeField("Tiktok Usaha", "tiktok_usaha");

$content = preg_replace("/(<label class=\"text-muted small fw-semibold text-uppercase\">Media Sosial Usaha<\/label>)/", $digital_add1 . "\n                                        $1\n" . $digital_add2, $content);

// 4. Dokumen
$dok_add = makeFile("NIB (Nomor Induk Berusaha)", "nib_file") . makeFile("NPWP Usaha", "npwp_file");
// Find the end of TAB: DOKUMEN row g-3
$content = str_replace('<!-- TAB: DOKUMEN -->
                        <div class="tab-pane fade" id="dokumen" role="tabpanel">
                            <div class="row g-3">', '<!-- TAB: DOKUMEN -->
                        <div class="tab-pane fade" id="dokumen" role="tabpanel">
                            <div class="row g-3">' . $dok_add, $content);

file_put_contents("resources/views/admin/pendaftaran/show.blade.php", $content);
echo "Done";
