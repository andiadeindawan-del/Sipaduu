<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$columns = Illuminate\Support\Facades\Schema::getColumnListing('users');
file_put_contents('users_columns.json', json_encode($columns, JSON_PRETTY_PRINT));
echo "Done";
