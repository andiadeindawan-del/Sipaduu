<?php $trainings = App\Models\Training::all(); foreach($trainings as $t) { echo $t->id . " - " . $t->status . " - completed: " . ($t->isCompletedTraining() ? "YES" : "NO") . "\n"; }
