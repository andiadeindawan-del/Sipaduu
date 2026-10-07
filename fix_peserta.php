<?php
$content = file_get_contents("app/Http/Controllers/SertifikatController.php");

$searchPeserta = "foreach (\$registrations as \$reg) {
            \$training = \$reg->training;
            
            \$sertifikat = Sertifikat::where('training_id', \$training->id)
                ->where('user_id', \$userId)
                ->first();

            if (\$sertifikat) {
                \$totalCertificates++;
                if (\$sertifikat->status === 'aktif') {
                    \$activeCertificates++;
                    \$trainingStatus[] = [
                        'training' => \$training,
                        'status_sertifikat' => 'sudah_terbit',
                        'sertifikat' => \$sertifikat
                    ];
                }
                continue;
            }

            \$passingStatus = \$this->getUserPassingStatus(\$training->id, \$userId);
            
            if (is_array(\$passingStatus['quizzes']) && count(\$passingStatus['quizzes']) === 0) {
                continue; 
            } else if (\$passingStatus['quizzes'] instanceof \Illuminate\Support\Collection && \$passingStatus['quizzes']->isEmpty()) {
                continue;
            }
            
            if (\$passingStatus['passed']) {
                \$trainingStatus[] = [
                    'training' => \$training,
                    'status_sertifikat' => 'menunggu_terbit',
                    'passed_at' => \$passingStatus['passed_at'],
                    'final_score' => \$passingStatus['final_score']
                ];
            } else {
                \$trainingStatus[] = [
                    'training' => \$training,
                    'status_sertifikat' => 'belum_lulus',
                    'quizzes' => \$passingStatus['quizzes']
                ];
            }
        }";

$replacePeserta = "foreach (\$registrations as \$reg) {
            \$training = \$reg->training;
            
            \$sertifikat = Sertifikat::where('training_id', \$training->id)
                ->where('user_id', \$userId)
                ->first();

            if (\$sertifikat) {
                \$totalCertificates++;
                if (\$sertifikat->status === 'aktif') {
                    \$activeCertificates++;
                    \$trainingStatus[] = [
                        'training' => \$training,
                        'status_sertifikat' => 'sudah_terbit',
                        'sertifikat' => \$sertifikat
                    ];
                }
            }
        }";

$content = str_replace($searchPeserta, $replacePeserta, $content);
file_put_contents("app/Http/Controllers/SertifikatController.php", $content);
echo "Done";
