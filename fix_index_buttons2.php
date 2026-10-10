<?php
$content = file_get_contents("resources/views/admin/sertifikat/index.blade.php");

// 1. Restore the "Tambah Manual" button next to "Menunggu Penerbitan"
$regexButtonTop = '/<a href="\{\{\s*route\(\'admin\.sertifikat\.menunggu\'\)\s*\}\}" class="btn btn-warning btn-sm">\s*<i class="bi bi-clock-history"><\/i> Menunggu Penerbitan\s*<\/a>/s';

$replaceButtonTop = '<a href="{{ route(\'admin.sertifikat.create\') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle"></i> Tambah Sertifikat
                </a>';

$content = preg_replace($regexButtonTop, $replaceButtonTop, $content);

// 2. Modify empty state
$regexEmpty = '/<p class="small">Mulai dengan mengecek peserta yang memenuhi syarat kelulusan\.<\/p>\s*<a href="\{\{\s*route\(\'admin\.sertifikat\.menunggu\'\)\s*\}\}" class="btn btn-warning btn-sm mt-2">\s*<i class="bi bi-clock-history"><\/i> Cek Menunggu Penerbitan\s*<\/a>/s';

$replaceEmpty = '<p class="small">Mulai dengan menambahkan sertifikat baru.</p>
                    <a href="{{ route(\'admin.sertifikat.create\') }}" class="btn btn-primary btn-sm mt-2">
                        <i class="bi bi-plus-circle"></i> Tambah Sertifikat
                    </a>';

$content = preg_replace($regexEmpty, $replaceEmpty, $content);

file_put_contents("resources/views/admin/sertifikat/index.blade.php", $content);
echo "Done";
