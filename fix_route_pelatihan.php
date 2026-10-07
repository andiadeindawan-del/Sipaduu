<?php
$content = file_get_contents("resources/views/peserta/sertifikat/index.blade.php");
$content = str_replace("route('peserta.pelatihan.index')", "route('peserta.trainings.index')", $content);
file_put_contents("resources/views/peserta/sertifikat/index.blade.php", $content);
echo "Done";
