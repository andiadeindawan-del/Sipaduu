<?php
require __DIR__."/vendor/autoload.php";
$app = require_once __DIR__."/bootstrap/app.php";
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$cols = Illuminate\Support\Facades\Schema::getColumnListing("users");
$marketing_cols = array_filter($cols, function($c) {
    return in_array($c, ["website_usaha", "facebook_usaha", "instagram_usaha", "tiktok_usaha", "marketplace", "judul_usaha", "facebook", "instagram", "tiktok", "shopee", "tokopedia", "lazada", "blibli"]) || strpos($c, "market") !== false || strpos($c, "url") !== false;
});
print_r($marketing_cols);
