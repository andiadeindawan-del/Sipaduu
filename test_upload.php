<?php
require __DIR__."/vendor/autoload.php";
$app = require_once __DIR__."/bootstrap/app.php";
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$request = Illuminate\Http\Request::create("/peserta/profile/update", "POST", [], [], [
    "npwp_file" => [
        Illuminate\Http\UploadedFile::fake()->image("test1.jpg"),
        Illuminate\Http\UploadedFile::fake()->image("test2.jpg"),
    ]
]);

echo $request->hasFile("npwp_file") ? "HAS_FILE\n" : "NO_FILE\n";
$files = $request->file("npwp_file");
echo "Count: " . count($files) . "\n";
