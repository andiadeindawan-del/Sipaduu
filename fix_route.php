<?php
$content = file_get_contents("resources/views/peserta/sertifikat/show.blade.php");
$content = str_replace("route('sertifikat.index')", "route('peserta.sertifikat.index')", $content);
file_put_contents("resources/views/peserta/sertifikat/show.blade.php", $content);
echo "Done";
