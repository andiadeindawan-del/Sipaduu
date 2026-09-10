<?php
require __DIR__."/vendor/autoload.php";
$app = require_once __DIR__."/bootstrap/app.php";
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$u = App\Models\User::whereNotNull("npwp_file")->orWhereNotNull("file_produk")->first();
if($u) {
    echo gettype($u->npwp_file) . " | ";
    echo json_encode($u->npwp_file) . "\n";
    echo gettype($u->file_produk) . " | ";
    echo json_encode($u->file_produk) . "\n";
} else {
    echo "No user";
}
