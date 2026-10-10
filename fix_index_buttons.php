<?php
$content = file_get_contents("resources/views/admin/sertifikat/index.blade.php");

$searchButtons = '<a href="{{ route(\'admin.sertifikat.menunggu\') }}" class="btn btn-warning btn-sm">
                    <i class="bi bi-clock-history"></i> Menunggu Penerbitan
                </a>';

$replaceButtons = '<a href="{{ route(\'admin.sertifikat.create\') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle"></i> Tambah Sertifikat Manual
                </a>
                <a href="{{ route(\'admin.sertifikat.menunggu\') }}" class="btn btn-warning btn-sm">
                    <i class="bi bi-clock-history"></i> Menunggu Penerbitan (Otomatis)
                </a>';

$content = str_replace($searchButtons, $replaceButtons, $content);
file_put_contents("resources/views/admin/sertifikat/index.blade.php", $content);
echo "Done";
