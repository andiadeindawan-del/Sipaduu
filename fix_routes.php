<?php
$content = file_get_contents("routes/web.php");

$search = "Route::resource('sertifikat', SertifikatController::class);";
$replace = "Route::get('sertifikat/eligible-users/{training}', [SertifikatController::class, 'getEligibleUsers'])->name('sertifikat.eligible-users');\n    Route::resource('sertifikat', SertifikatController::class);";

$content = str_replace($search, $replace, $content);
file_put_contents("routes/web.php", $content);
echo "Done";
