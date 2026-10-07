<?php
$content = file_get_contents("resources/views/admin/pendaftaran/show.blade.php");

// 1. Move File Produk from Informasi Pelatihan to Dokumen
$pattern = "/(\s*<div class=\"col-12\">\s*<div class=\"detail-item\">\s*<label class=\"text-muted small fw-semibold text-uppercase\">File Produk<\/label>.*?<\/div>\s*<\/div>\s*)/s";
if (preg_match($pattern, $content, $m)) {
    $existing_file_produk = $m[1];
    $content = str_replace($existing_file_produk, "", $content);
    // Inject it into Dokumen tab, right after NPWP Usaha
    $npwp_pattern = "/(<label class=\"text-muted small fw-semibold text-uppercase\">NPWP Usaha<\/label>.*?<\/div>\s*<\/div>)/s";
    $content = preg_replace($npwp_pattern, "$1\n" . $existing_file_produk, $content);
}

// 2. Build Tenaga Kerja table
$tenaga_kerja_table = "
                                <div class=\"col-12 mt-3\">
                                    <h6 class=\"fw-bold text-secondary mb-2\"><i class=\"bi bi-people-fill me-2\"></i>Tenaga Kerja</h6>
                                    <div class=\"table-responsive\">
                                        <table class=\"table table-bordered table-sm text-center mb-0\">
                                            <thead class=\"table-light\">
                                                <tr>
                                                    <th class=\"text-start\">Jenis Karyawan</th>
                                                    <th>Laki-laki</th>
                                                    <th>Perempuan</th>
                                                    <th>Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class=\"text-start fw-semibold text-secondary\">Karyawan Tetap</td>
                                                    <td>{{ \$registration->user->karyawan_tetap_laki_laki ?? '0' }}</td>
                                                    <td>{{ \$registration->user->karyawan_tetap_perempuan ?? '0' }}</td>
                                                    <td class=\"fw-bold bg-light\">{{ \$registration->user->total_karyawan_tetap ?? '0' }}</td>
                                                </tr>
                                                <tr>
                                                    <td class=\"text-start fw-semibold text-secondary\">Karyawan Tidak Tetap</td>
                                                    <td>{{ \$registration->user->karyawan_tidak_tetap_laki_laki ?? '0' }}</td>
                                                    <td>{{ \$registration->user->karyawan_tidak_tetap_perempuan ?? '0' }}</td>
                                                    <td class=\"fw-bold bg-light\">{{ \$registration->user->total_karyawan_tidak_tetap ?? '0' }}</td>
                                                </tr>
                                            </tbody>
                                            <tfoot class=\"table-light\">
                                                <tr>
                                                    <th colspan=\"3\" class=\"text-end\">TOTAL TENAGA KERJA:</th>
                                                    <th class=\"fs-6 text-primary\">{{ \$registration->user->total_tenaga_kerja ?? '0' }} Orang</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
";

// First, remove the scattered fields I added earlier
$scattered = [
    "Karyawan Tetap (L)", "Karyawan Tetap (P)", "Total Karyawan Tetap", 
    "Karyawan Tdk Tetap (L)", "Karyawan Tdk Tetap (P)", "Total Karyawan Tdk Tetap", "Total Tenaga Kerja"
];
foreach($scattered as $label) {
    $pat = "/\s*<div class=\"col-12 col-md-6\">\s*<div class=\"detail-item\">\s*<label class=\"text-muted small fw-semibold text-uppercase\">" . preg_quote($label, "/") . "<\/label>.*?<\/div>\s*<\/div>/s";
    $content = preg_replace($pat, "", $content);
}

// Inject Tenaga Kerja Table before Jumlah Karyawan
// Wait, `Jumlah Karyawan` is still there from original layout. We can just replace `Jumlah Karyawan` entirely, since our table covers it!
$jumlah_karyawan_pat = "/\s*<div class=\"col-12 col-md-6\">\s*<div class=\"detail-item\">\s*<label class=\"text-muted small fw-semibold text-uppercase\">Jumlah Karyawan<\/label>.*?<\/div>\s*<\/div>/s";
$content = preg_replace($jumlah_karyawan_pat, "\n" . $tenaga_kerja_table, $content);

file_put_contents("resources/views/admin/pendaftaran/show.blade.php", $content);
echo "Done";
