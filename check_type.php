<?php
require __DIR__."/vendor/autoload.php";
$app = require_once __DIR__."/bootstrap/app.php";
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
echo Schema::getColumnType("users", "npwp_file") . "\n";
echo Schema::getColumnType("users", "file_produk") . "\n";
