<?php
$content = file_get_contents("resources/views/admin/pendaftaran/show.blade.php");

// 1. Remove the misplaced fields and extra divs
$pattern = "/(<!-- TAB: DIGITAL ADDITIONS -->.*?<\/div>\s*<\/div>\s*)(<!-- TAB: INFORMASI PELATIHAN -->)/s";
// wait, I already removed <!-- TAB: DIGITAL ADDITIONS -->
$pattern = "/(\s*<div class=\"col-12 col-md-6\">\s*<div class=\"detail-item\">\s*<label class=\"text-muted small fw-semibold text-uppercase\">Judul Usaha Online<\/label>.*?<\/div>\s*<\/div>\s*)<!-- TAB: INFORMASI PELATIHAN -->/s";
if (preg_match($pattern, $content, $m)) {
    $digital_fields = $m[1];
    // Remove it from the current position
    $content = str_replace($digital_fields . "<!-- TAB: INFORMASI PELATIHAN -->", "<!-- TAB: INFORMASI PELATIHAN -->", $content);
    // Remove the two extra closing divs at the end of $digital_fields
    $digital_fields = preg_replace("/<\/div>\s*<\/div>\s*$/s", "", $digital_fields);
    
    // Inject it into the Digital tab
    $content = preg_replace("/(<!-- TAB: DIGITAL & PEMASARAN -->.*?<\/div>\s*)(<\/div>\s*<!-- TAB: INFORMASI PELATIHAN -->)/s", "$1" . $digital_fields . "$2", $content);
}

file_put_contents("resources/views/admin/pendaftaran/show.blade.php", $content);

