<?php
$files = [
    "resources/views/peserta/sertifikat/index.blade.php",
    "resources/views/peserta/sertifikat/show.blade.php"
];

foreach ($files as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        $content = str_replace("@extends('layouts.app')", "@extends('layouts.peserta')", $content);
        file_put_contents($file, $content);
        echo "Fixed $file\n";
    }
}
