<?php
$content = file_get_contents("resources/views/peserta/sertifikat/index.blade.php");
$content = str_replace('<i class="bi bi-eye me-1"></i> Lihat & Unduh Sertifikat', '<i class="bi bi-file-earmark-pdf me-1"></i> Lihat & Download PDF', $content);
file_put_contents("resources/views/peserta/sertifikat/index.blade.php", $content);
echo "Done";
