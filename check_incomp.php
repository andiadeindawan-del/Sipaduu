<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$u = App\Models\User::where('name', 'like', '%Ade%')->first();
if ($u) {
    echo "Peserta: " . $u->name . "\n";
    echo "Incomplete fields:\n";
    print_r($u->profil_incomplete_fields);
} else {
    echo "No peserta found\n";
}
