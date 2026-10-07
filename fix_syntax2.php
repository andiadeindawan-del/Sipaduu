<?php
$content = file_get_contents("app/Http/Controllers/SertifikatController.php");
$content = preg_replace('/(with\(\'error\', \$e->getMessage\(\)\);\s*})\s*private function getUserPassingStatus/s', "$1\n    }\n\n    private function getUserPassingStatus", $content);
file_put_contents("app/Http/Controllers/SertifikatController.php", $content);
echo "Done";
