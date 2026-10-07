<?php
$content = file_get_contents("resources/views/admin/pendaftaran/show.blade.php");

// Fix the bad variable evaluation
$content = str_replace('$registration->user->n', '$registration->user->npwp_file[0]', $content);
$content = str_replace('$registration->user->n', '$registration->user->nib_file[0]', $content); // wait both started with n
$content = preg_replace('/\$registration->user->([a-zA-Z_]+)\[0\]/', 'is_array($registration->user->$1) ? $registration->user->{$1}[0] : $registration->user->$1', $content);

file_put_contents("resources/views/admin/pendaftaran/show.blade.php", $content);
echo "Done";
