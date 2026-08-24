<?php $trainings = App\Models\Training::all(); foreach($trainings as $t) { echo $t->id . " - " . $t->tanggal_selesai . " - completed: " . ($t->isCompletedTraining() ? "YES" : "NO") . "\n"; }
