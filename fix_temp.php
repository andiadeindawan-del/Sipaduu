<?php
$content = file_get_contents("resources/views/admin/sertifikat/create.blade.php");
$content = str_replace("TEMP_BLOCK_TRAINING", "", $content);
file_put_contents("resources/views/admin/sertifikat/create.blade.php", $content);
echo "Done";

