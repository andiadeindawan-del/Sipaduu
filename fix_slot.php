<?php
$content = file_get_contents("resources/views/layouts/app.blade.php");
$content = str_replace("{{ \$slot }}", "{{ \$slot ?? '' }}\n                        @yield('content')", $content);
file_put_contents("resources/views/layouts/app.blade.php", $content);
echo "Done";
