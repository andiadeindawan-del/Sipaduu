<?php
$html = file_get_contents("resources/views/admin/pendaftaran/show.blade.php");
preg_match_all("/<\/?div[^>]*>/i", $html, $matches, PREG_OFFSET_CAPTURE);
$depth = 0;
foreach($matches[0] as $match) {
    $tag = $match[0];
    if (strpos($tag, "</div") !== false) {
        $depth--;
    } else {
        $depth++;
    }
}
echo "Final depth: $depth\n";

