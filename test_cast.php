<?php
require __DIR__."/vendor/autoload.php";
$app = require_once __DIR__."/bootstrap/app.php";
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$u = App\Models\User::first();
$u->update(["npwp_file" => ["test1.jpg", "test2.pdf"]]);
echo json_encode($u->npwp_file) . "\n";
