<?php
$content = file_get_contents("resources/views/admin/sertifikat/index.blade.php");

// Remove Tambah Manual
$content = preg_replace("/<a href=\"\{\{\s*route\('admin\.sertifikat\.create'\)\s*\}\}\" class=\"btn btn-primary btn-sm\">\s*<i class=\"bi bi-plus-circle\"><\/i> Tambah Manual\s*<\/a>/s", "", $content);

// Remove Tambah
$content = preg_replace("/<a href=\"\{\{\s*route\('admin\.sertifikat\.create'\)\s*\}\}\" class=\"btn btn-primary btn-sm\">\s*<i class=\"bi bi-plus-circle\" aria-hidden=\"true\"><\/i> Tambah\s*<\/a>/s", "", $content);

// Modify Empty State
$search_empty = "<p class=\"small\">Mulai dengan menambahkan sertifikat baru</p>
                    <a href=\"{{ route('admin.sertifikat.create') }}\" class=\"btn btn-primary btn-sm mt-2\">
                        <i class=\"bi bi-plus-circle\"></i> Tambah Sertifikat
                    </a>";
$replace_empty = "<p class=\"small\">Mulai dengan mengecek peserta yang memenuhi syarat kelulusan.</p>
                    <a href=\"{{ route('admin.sertifikat.menunggu') }}\" class=\"btn btn-warning btn-sm mt-2\">
                        <i class=\"bi bi-clock-history\"></i> Cek Menunggu Penerbitan
                    </a>";
$content = str_replace($search_empty, $replace_empty, $content);

file_put_contents("resources/views/admin/sertifikat/index.blade.php", $content);
echo "Done";
