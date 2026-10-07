<?php
$content = file_get_contents("app/Http/Controllers/SertifikatController.php");

$start = strpos($content, "private function getUserPassingStatus");
if ($start !== false) {
    $before = substr($content, 0, $start);
    
    $newMethod = 'private function getUserPassingStatus($trainingId, $userId) {
        $publishedQuizzes = \Illuminate\Support\Facades\DB::table(\'quizzes\')
            ->where(\'training_id\', $trainingId)
            ->where(\'status\', \'published\')
            ->get();
            
        $hasAbsensi = \Illuminate\Support\Facades\DB::table(\'absensis\')
            ->where(\'training_id\', $trainingId)
            ->where(\'user_id\', $userId)
            ->where(\'status\', \'hadir\')
            ->exists();

        if (!$hasAbsensi) {
            return [\'passed\' => false, \'quizzes\' => collect(), \'reason\' => \'Belum absensi\'];
        }

        if ($publishedQuizzes->isEmpty()) {
            return [\'passed\' => false, \'quizzes\' => collect(), \'reason\' => \'Kuis belum tersedia\'];
        }

        $passedAll = true;
        $totalScore = 0;
        $lastAttemptDate = null;
        $quizzesStatus = [];

        foreach ($publishedQuizzes as $quiz) {
            $attempts = \Illuminate\Support\Facades\DB::table(\'quiz_attempts\')
                ->where(\'quiz_id\', $quiz->id)
                ->where(\'user_id\', $userId)
                ->where(\'status\', \'completed\')
                ->get();
                
            $bestScoreAttempt = $attempts->sortByDesc(\'score\')->first();
            
            $hasPassed = $bestScoreAttempt && $bestScoreAttempt->score >= $quiz->passing_score;
            if (!$hasPassed) {
                $passedAll = false;
            }

            if ($bestScoreAttempt) {
                $totalScore += $bestScoreAttempt->score;
                if (!$lastAttemptDate || $bestScoreAttempt->completed_at > $lastAttemptDate) {
                    $lastAttemptDate = $bestScoreAttempt->completed_at;
                }
            }
            
            $quizzesStatus[] = [
                \'quiz\' => $quiz,
                \'passed\' => $hasPassed,
                \'score\' => $bestScoreAttempt ? $bestScoreAttempt->score : 0,
            ];
        }

        if (!$passedAll) {
            return [\'passed\' => false, \'quizzes\' => $publishedQuizzes, \'reason\' => \'Tidak lulus quiz\'];
        }

        return [
            \'passed\' => true,
            \'final_score\' => $totalScore / $publishedQuizzes->count(),
            \'passed_at\' => $lastAttemptDate,
            \'quizzes\' => $quizzesStatus
        ];
    }
}';
    
    $content = $before . $newMethod;
    file_put_contents("app/Http/Controllers/SertifikatController.php", $content);
    echo "Done";
}
