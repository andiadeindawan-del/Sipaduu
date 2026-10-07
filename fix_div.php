<?php
$content = file_get_contents("resources/views/admin/pendaftaran/show.blade.php");
$content = str_replace("</div>\n                        </div>\n\n                        <!-- TAB: INFORMASI PELATIHAN -->", "<!-- TAB: INFORMASI PELATIHAN -->", $content);
file_put_contents("resources/views/admin/pendaftaran/show.blade.php", $content);

