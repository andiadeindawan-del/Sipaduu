<?php
$content = file_get_contents("app/Http/Controllers/SertifikatController.php");

$regexMenunggu = '/public function menunggu\(Request \$request\).*?return view\(\'admin\.sertifikat\.menunggu\', compact\(\'trainings\', \'passedUsers\', \'trainingId\'\)\);\s*\}/s';

$replaceMenunggu = 'public function menunggu(Request $request)
    {
        $trainings = Training::whereIn(\'status\', [\'published\', \'selesai\', \'berjalan\'])->orderBy(\'judul\')->get();
        $participants = collect();
        $trainingId = $request->training_id;

        if ($trainingId) {
            $enrolledUserIds = \Illuminate\Support\Facades\DB::table(\'training_registrations\')
                ->where(\'training_id\', $trainingId)
                ->where(\'status\', \'disetujui\')
                ->pluck(\'user_id\');

            foreach ($enrolledUserIds as $userId) {
                $passingStatus = $this->getUserPassingStatus($trainingId, $userId);
                $hasCert = Sertifikat::where(\'training_id\', $trainingId)->where(\'user_id\', $userId)->exists();
                
                $user = User::find($userId);
                if ($user) {
                    if ($hasCert) {
                        $user->status_sertifikat = \'Diterbitkan\';
                    } else if ($passingStatus[\'passed\']) {
                        $user->status_sertifikat = \'Layak Diterbitkan\';
                        $user->passed_at = $passingStatus[\'passed_at\'] ?? now();
                        $user->final_score = $passingStatus[\'final_score\'] ?? 0;
                    } else {
                        $user->status_sertifikat = $passingStatus[\'reason\'] ?? \'Belum memenuhi persyaratan\';
                    }
                    $participants->push($user);
                }
            }
        }

        return view(\'admin.sertifikat.menunggu\', compact(\'trainings\', \'participants\', \'trainingId\'));
    }';

$content = preg_replace($regexMenunggu, $replaceMenunggu, $content);

$regexPassing = '/private function getUserPassingStatus.*?return \[\'passed\' => true,.*?\];\s*\}/s';

$replacePassing = 'private function getUserPassingStatus($trainingId, $userId) {
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
        }

        if (!$passedAll) {
            return [\'passed\' => false, \'quizzes\' => $publishedQuizzes, \'reason\' => \'Tidak lulus quiz\'];
        }

        return [
            \'passed\' => true,
            \'quizzes\' => $publishedQuizzes,
            \'final_score\' => $publishedQuizzes->count() > 0 ? round($totalScore / $publishedQuizzes->count(), 2) : 0,
            \'passed_at\' => $lastAttemptDate ?? now()
        ];
    }';

$content = preg_replace($regexPassing, $replacePassing, $content);

file_put_contents("app/Http/Controllers/SertifikatController.php", $content);
echo "Done";
