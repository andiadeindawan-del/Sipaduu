<?php
require __DIR__."/vendor/autoload.php";
$app = require_once __DIR__."/bootstrap/app.php";
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$cols = Illuminate\Support\Facades\Schema::getColumnListing("users");
$pemasaran_cols = array_filter($cols, function($c) {
    return strpos($c, "pemasaran") !== false || strpos($c, "wilayah") !== false;
});
print_r($pemasaran_cols);
