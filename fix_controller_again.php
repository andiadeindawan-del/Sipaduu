<?php
$content = file_get_contents("app/Http/Controllers/SertifikatController.php");

$searchMenunggu = "if (\$hasCert) {
                        \$user->status_sertifikat = 'Diterbitkan';
                    } else if (\$passingStatus['passed']) {
                        \$user->status_sertifikat = 'Layak Diterbitkan';
                        \$user->passed_at = \$passingStatus['passed_at'] ?? now();
                        \$user->final_score = \$passingStatus['final_score'] ?? 0;
                    } else {
                        \$user->status_sertifikat = \$passingStatus['reason'] ?? 'Belum memenuhi persyaratan';
                    }
                    \$participants->push(\$user);";

$replaceMenunggu = "if (!\$hasCert && \$passingStatus['passed']) {
                        \$user->status_sertifikat = 'Layak Diterbitkan';
                        \$user->passed_at = \$passingStatus['passed_at'] ?? now();
                        \$user->final_score = \$passingStatus['final_score'] ?? 0;
                        \$participants->push(\$user);
                    }";

$content = str_replace($searchMenunggu, $replaceMenunggu, $content);
file_put_contents("app/Http/Controllers/SertifikatController.php", $content);
echo "Done";
