<?php
$content = file_get_contents("app/Http/Controllers/SertifikatController.php");

$searchMenunggu = "public function menunggu(Request \$request)
    {
        \$trainings = Training::whereIn('status', ['published', 'selesai', 'berjalan'])->orderBy('judul')->get();
        \$passedUsers = collect();
        \$trainingId = \$request->training_id;

        if (\$trainingId) {
            \$enrolledUserIds = \Illuminate\Support\Facades\DB::table('training_registrations')
                ->where('training_id', \$trainingId)
                ->where('status', 'disetujui')
                ->pluck('user_id');

            foreach (\$enrolledUserIds as \$userId) {
                \$passingStatus = \$this->getUserPassingStatus(\$trainingId, \$userId);
                
                if (is_array(\$passingStatus['quizzes']) && count(\$passingStatus['quizzes']) === 0) {
                    continue; 
                } else if (\$passingStatus['quizzes'] instanceof \Illuminate\Support\Collection && \$passingStatus['quizzes']->isEmpty()) {
                    continue;
                }

                if (\$passingStatus['passed']) {
                    \$hasCert = Sertifikat::where('training_id', \$trainingId)->where('user_id', \$userId)->exists();
                    if (!\$hasCert) {
                        \$user = User::find(\$userId);
                        if (\$user) {
                            \$user->passed_at = \$passingStatus['passed_at'];
                            \$user->final_score = \$passingStatus['final_score'];
                            \$passedUsers->push(\$user);
                        }
                    }
                }
            }
        }

        return view('admin.sertifikat.menunggu', compact('trainings', 'passedUsers', 'trainingId'));
    }";

$replaceMenunggu = "public function menunggu(Request \$request)
    {
        \$trainings = Training::whereIn('status', ['published', 'selesai', 'berjalan'])->orderBy('judul')->get();
        \$participants = collect();
        \$trainingId = \$request->training_id;

        if (\$trainingId) {
            \$enrolledUserIds = \Illuminate\Support\Facades\DB::table('training_registrations')
                ->where('training_id', \$trainingId)
                ->where('status', 'disetujui')
                ->pluck('user_id');

            foreach (\$enrolledUserIds as \$userId) {
                \$passingStatus = \$this->getUserPassingStatus(\$trainingId, \$userId);
                \$hasCert = Sertifikat::where('training_id', \$trainingId)->where('user_id', \$userId)->exists();
                
                \$user = User::find(\$userId);
                if (\$user) {
                    if (\$hasCert) {
                        \$user->status_sertifikat = 'Diterbitkan';
                    } else if (\$passingStatus['passed']) {
                        \$user->status_sertifikat = 'Layak Diterbitkan';
                        \$user->passed_at = \$passingStatus['passed_at'] ?? now();
                        \$user->final_score = \$passingStatus['final_score'] ?? 0;
                    } else {
                        \$user->status_sertifikat = \$passingStatus['reason'] ?? 'Belum memenuhi persyaratan';
                    }
                    \$participants->push(\$user);
                }
            }
        }

        return view('admin.sertifikat.menunggu', compact('trainings', 'participants', 'trainingId'));
    }";

$content = str_replace($searchMenunggu, $replaceMenunggu, $content);

$searchGetPassing = "private function getUserPassingStatus(\$trainingId, \$userId) {
        \$publishedQuizzes = \Illuminate\Support\Facades\DB::table('quizzes')
            ->where('training_id', \$trainingId)
            ->where('status', 'published')
            ->get();

        if (\$publishedQuizzes->isEmpty()) {
            return ['passed' => false, 'quizzes' => collect()];
        }";

$replaceGetPassing = "private function getUserPassingStatus(\$trainingId, \$userId) {
        \$publishedQuizzes = \Illuminate\Support\Facades\DB::table('quizzes')
            ->where('training_id', \$trainingId)
            ->where('status', 'published')
            ->get();
            
        \$hasAbsensi = \Illuminate\Support\Facades\DB::table('absensis')
            ->where('training_id', \$trainingId)
            ->where('user_id', \$userId)
            ->where('status', 'hadir')
            ->exists();

        if (!\$hasAbsensi) {
            return ['passed' => false, 'quizzes' => collect(), 'reason' => 'Belum absensi'];
        }

        if (\$publishedQuizzes->isEmpty()) {
            return ['passed' => false, 'quizzes' => collect(), 'reason' => 'Kuis belum tersedia'];
        }";

$content = str_replace($searchGetPassing, $replaceGetPassing, $content);

$content = preg_replace("/if \(\!\$passedAll\) \{\s*return \['passed' => false, 'quizzes' => \\\$publishedQuizzes\];\s*\}/s", 
                        "if (!\$passedAll) {\n            return ['passed' => false, 'quizzes' => \$publishedQuizzes, 'reason' => 'Tidak lulus quiz'];\n        }", $content);

file_put_contents("app/Http/Controllers/SertifikatController.php", $content);
echo "Done";
