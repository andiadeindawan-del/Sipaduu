<?php
$content = file_get_contents("resources/views/layouts/app.blade.php");
$content = str_replace("@hasSection('header')", "{{ \$header ?? '' }}\n                @hasSection('header')", $content);
file_put_contents("resources/views/layouts/app.blade.php", $content);
echo "Done";
