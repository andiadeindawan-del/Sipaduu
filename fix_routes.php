<?php
$content = file_get_contents("resources/views/layouts/app.blade.php");
$content = str_replace("route('admin.reports.users')", "route('admin.laporan.users')", $content);
$content = str_replace("routeIs('admin.reports.users')", "routeIs('admin.laporan.users')", $content);
$content = str_replace("route('admin.reports.certificates')", "route('admin.laporan.registrations')", $content);
$content = str_replace("routeIs('admin.reports.certificates')", "routeIs('admin.laporan.registrations')", $content);
$content = str_replace("Laporan Sertifikat", "Laporan Pendaftaran", $content);
file_put_contents("resources/views/layouts/app.blade.php", $content);
echo "Done";
