<?php
$content = file_get_contents("app/Http/Controllers/SertifikatController.php");

$start = strpos($content, "private function getUserPassingStatus");
if ($start !== false) {
    // Find the end of the method by looking for the closing brace
    // Actually, just find the end of the file since it is the last method in the class!
    // Let us verify if it is the last method.
}

