<?php

namespace App\Http\Controllers;

use App\Models\Training;
use App\Models\Materi;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizAttempt;
use App\Models\Survey;
use App\Models\Dokumentasi;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PemateriController extends Controller
{
    /**
     * Display the pemateri dashboard.
     */
    public function index(): View
    {
        $user = auth()->user();

        // ID Pelatihan yang ditugaskan ke pemateri ini
        $myTrainingIds = Training::where('trainer_id', $user->id)->pluck('id');
        $myQuizIds = Quiz::whereIn('training_id', $myTrainingIds)->pluck('id');

        // Statistik Utama Pemateri (Hanya untuk pelatihan yang diampu)
        $totalTrainings = Training::where('trainer_id', $user->id)
            ->where('status', '!=', 'dibatalkan')
            ->count();
        $totalMateris = Materi::whereIn('training_id', $myTrainingIds)->count();
        $totalQuizzes = Quiz::whereIn('training_id', $myTrainingIds)->count();
        $totalQuestions = QuizQuestion::whereIn('quiz_id', $myQuizIds)->count();
        $totalAttempts = QuizAttempt::whereIn('quiz_id', $myQuizIds)->count();
        $totalSurveys = Survey::whereIn('training_id', $myTrainingIds)->count();
        $totalDokumentasis = Dokumentasi::whereIn('training_id', $myTrainingIds)->count();

        // Daftar Pelatihan yang diampu pemateri ini
        $trainings = Training::with(['kategori', 'trainer'])
            ->withCount(['materis', 'quizzes', 'surveys', 'dokumentasis'])
            ->where('trainer_id', $user->id)
            ->where('status', '!=', 'dibatalkan')
            ->orderBy('tanggal_mulai', 'asc')
            ->limit(6)
            ->get();

        // Materi Terbaru pada pelatihan pemateri ini
        $recentMateris = Materi::whereIn('training_id', $myTrainingIds)
            ->with(['kategori', 'training'])
            ->latest()
            ->limit(5)
            ->get();

        // Kuis Terbaru pada pelatihan pemateri ini
        $recentQuizzes = Quiz::whereIn('training_id', $myTrainingIds)
            ->with(['training', 'materi'])
            ->withCount(['questions', 'attempts'])
            ->latest()
            ->limit(5)
            ->get();

        // Survey Terbaru pada pelatihan pemateri ini
        $recentSurveys = Survey::whereIn('training_id', $myTrainingIds)
            ->with(['training'])
            ->withCount(['questions', 'responses'])
            ->latest()
            ->limit(5)
            ->get();

        // Dokumentasi Terbaru pada pelatihan pemateri ini
        $recentDokumentasis = Dokumentasi::whereIn('training_id', $myTrainingIds)
            ->with(['training'])
            ->latest()
            ->limit(5)
            ->get();

        // Pengerjaan Kuis Terbaru oleh Peserta untuk kuis pemateri ini
        $recentAttempts = QuizAttempt::whereIn('quiz_id', $myQuizIds)
            ->with(['quiz', 'user'])
            ->latest()
            ->limit(6)
            ->get();

        return view('pemateri.index', compact(
            'user',
            'totalTrainings',
            'totalMateris',
            'totalQuizzes',
            'totalQuestions',
            'totalAttempts',
            'totalSurveys',
            'totalDokumentasis',
            'trainings',
            'recentMateris',
            'recentQuizzes',
            'recentSurveys',
            'recentDokumentasis',
            'recentAttempts'
        ));
    }
}
