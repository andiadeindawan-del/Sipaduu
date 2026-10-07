<?php
$content = file_get_contents("resources/views/admin/sertifikat/index.blade.php");

$content = preg_replace("/<p class=\"small\">Mulai dengan menambahkan sertifikat baru<\/p>\s*<a href=\"\{\{\s*route\('admin\.sertifikat\.create'\)\s*\}\}\" class=\"btn btn-primary btn-sm mt-2\">\s*<i class=\"bi bi-plus-circle\"><\/i> Tambah Sertifikat\s*<\/a>/s", 
    "<p class=\"small\">Mulai dengan mengecek peserta yang memenuhi syarat kelulusan.</p>\n                    <a href=\"{{ route('admin.sertifikat.menunggu') }}\" class=\"btn btn-warning btn-sm mt-2\">\n                        <i class=\"bi bi-clock-history\"></i> Cek Menunggu Penerbitan\n                    </a>", 
    $content);

file_put_contents("resources/views/admin/sertifikat/index.blade.php", $content);
echo "Done";
