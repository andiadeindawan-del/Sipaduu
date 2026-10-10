<?php
$content = file_get_contents("app/Http/Controllers/SertifikatController.php");

$start = strpos($content, "public function menunggu");
$before = substr($content, 0, $start);
$after = substr($content, $start);

$newMethod = "public function getEligibleUsers(\$trainingId)
    {
        \$enrolledUserIds = \Illuminate\Support\Facades\DB::table('training_registrations')
            ->where('training_id', \$trainingId)
            ->where('status', 'disetujui')
            ->pluck('user_id');

        \$eligibleUsers = [];
        foreach (\$enrolledUserIds as \$userId) {
            \$passingStatus = \$this->getUserPassingStatus(\$trainingId, \$userId);
            \$hasCert = \App\Models\Sertifikat::where('training_id', \$trainingId)->where('user_id', \$userId)->exists();
            
            if (!\$hasCert && \$passingStatus['passed']) {
                \$user = \App\Models\User::find(\$userId);
                if (\$user) {
                    \$eligibleUsers[] = [
                        'id' => \$user->id,
                        'nama' => \$user->nama ?? \$user->name,
                        'email' => \$user->email
                    ];
                }
            }
        }

        return response()->json(\$eligibleUsers);
    }\n\n    ";

$content = $before . $newMethod . $after;
file_put_contents("app/Http/Controllers/SertifikatController.php", $content);
echo "Done";
