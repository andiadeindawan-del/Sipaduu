<?php
$content = file_get_contents("resources/views/admin/sertifikat/create.blade.php");

$regexUser = '/<!-- User\/Peserta -->.*?@enderror\s*<\/div>/s';
$regexTraining = '/<!-- Training -->.*?@enderror\s*<\/div>/s';

preg_match($regexUser, $content, $matchUser);
preg_match($regexTraining, $content, $matchTraining);

if (!empty($matchUser) && !empty($matchTraining)) {
    // We want to replace the whole block (User + Training) with (Training + User)
    $originalCombined = $matchUser[0] . "\n\n                            " . $matchTraining[0];
    
    // Modify Training to be required
    $newTraining = str_replace('Pilih Training (Opsional)', 'Pilih Training', $matchTraining[0]);
    $newTraining = str_replace('<label for="training_id" class="form-label fw-semibold">Training</label>', '<label for="training_id" class="form-label fw-semibold">Training <span class="text-danger">*</span></label>', $newTraining);
    $newTraining = str_replace('name="training_id"', 'name="training_id" required', $newTraining);
    
    // Modify User to be empty by default
    $newUser = preg_replace('/@foreach\(\$users \?\? \[\] as \$user\).*?@endforeach/s', '', $matchUser[0]);
    $newUser = str_replace('Pilih Peserta', 'Pilih Training terlebih dahulu', $newUser);
    
    $newCombined = $newTraining . "\n\n                            " . $newUser;
    
    $content = str_replace($matchUser[0], "TEMP_BLOCK_USER", $content);
    $content = str_replace($matchTraining[0], "TEMP_BLOCK_TRAINING", $content);
    
    // Remove the trailing Training block
    $content = str_replace("\n\n                            TEMP_BLOCK_TRAINING", "", $content);
    $content = str_replace("TEMP_BLOCK_USER", $newCombined, $content);
    
    file_put_contents("resources/views/admin/sertifikat/create.blade.php", $content);
    echo "Done";
} else {
    echo "Regex failed to match.";
}
