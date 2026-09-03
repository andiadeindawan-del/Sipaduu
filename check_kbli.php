<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$count = \App\Models\Kbli::count();
$categories = \App\Models\Kbli::select('kategori')->distinct()->get()->pluck('kategori');
$schema = \Illuminate\Support\Facades\Schema::getColumnListing('kblis');

echo "Count: $count\n";
echo "Categories: " . json_encode($categories) . "\n";
echo "Schema: " . json_encode($schema) . "\n";
