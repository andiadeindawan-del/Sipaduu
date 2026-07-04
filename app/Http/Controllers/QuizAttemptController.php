<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizAttemptController extends Controller
{
    /**
     * Display a listing of quiz attempts.
     */
    public function index(Request $request)
    {
        $query = QuizAttempt::with(['quiz', 'user']);

        // Filter by quiz
        if ($request->filled('quiz_id')) {
            $query->where('quiz_id', $request->quiz_id);
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            })->orWhereHas('quiz', function ($q) use ($search) {
                $q->where('judul', 'like', "%$search%");
            });
        }

        $attempts = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        // Statistics
        $totalAttempts = QuizAttempt::count();
        $completedAttempts = QuizAttempt::where('status', 'completed')->count();
        $inProgressAttempts = QuizAttempt::where('status', 'in_progress')->count();
        $expiredAttempts = QuizAttempt::where('status', 'expired')->count();
        $averageScore = QuizAttempt::where('status', 'completed')->avg('score') ?? 0;

        // For filters
        $quizzes = Quiz::orderBy('judul')->get();

        return view('admin.quiz.attempt.index', compact(
            'attempts',
            'totalAttempts',
            'completedAttempts',
            'inProgressAttempts',
            'expiredAttempts',
            'averageScore',
            'quizzes'
        ));
    }

    /**
     * Show the form for creating a new attempt.
     */
    public function create(Request $request)
    {
        $quizId = $request->quiz_id;
        $quiz = $quizId ? Quiz::with('questions')->find($quizId) : null;
        
        if ($quiz && !$quiz->is_available) {
            return redirect()->back()->with('error', 'Quiz tidak tersedia untuk dikerjakan.');
        }

        $quizzes = Quiz::where('status', 'published')->orderBy('judul')->get();

        return view('admin.quiz.attempt.create', compact('quiz', 'quizzes'));
    }

    /**
     * Store a newly created attempt.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'quiz_id' => 'required|exists:quizzes,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $quiz = Quiz::find($validated['quiz_id']);

        // Check if user can take this quiz
        if (!$quiz->canTake($validated['user_id'])) {
            return redirect()->back()
                ->with('error', 'Anda tidak dapat mengerjakan quiz ini. Cek kembali kuota atau status quiz.')
                ->withInput();
        }

        // Create new attempt
        $attempt = QuizAttempt::create([
            'quiz_id' => $validated['quiz_id'],
            'user_id' => $validated['user_id'],
            'started_at' => now(),
            'status' => 'in_progress',
            'total_questions' => $quiz->questions()->count(),
        ]);

        return redirect()->route('admin.quiz.attempt.show', $attempt->id)
                        ->with('success', 'Quiz dimulai. Silakan kerjakan soal-soal berikut.');
    }

    /**
     * Display the specified attempt.
     */
    public function show(QuizAttempt $attempt)
    {
        $attempt->load(['quiz', 'user', 'quiz.questions']);
        
        $questions = $attempt->quiz->questions;
        $answers = $attempt->answers ?? [];
        
        // Calculate progress
        $answeredCount = count($answers);
        $totalQuestions = $questions->count();
        $progress = $totalQuestions > 0 ? round(($answeredCount / $totalQuestions) * 100) : 0;

        return view('admin.quiz.attempt.show', compact(
            'attempt',
            'questions',
            'answers',
            'answeredCount',
            'totalQuestions',
            'progress'
        ));
    }

    /**
     * Show the form for editing the specified attempt.
     */
    public function edit(QuizAttempt $attempt)
    {
        if ($attempt->status === 'completed') {
            return redirect()->route('admin.quiz.attempt.show', $attempt->id)
                            ->with('error', 'Quiz sudah selesai dikerjakan, tidak dapat diubah.');
        }

        $attempt->load(['quiz', 'user', 'quiz.questions']);
        $questions = $attempt->quiz->questions;
        $answers = $attempt->answers ?? [];

        return view('admin.quiz.attempt.edit', compact('attempt', 'questions', 'answers'));
    }

    /**
     * Update the specified attempt.
     */
    public function update(Request $request, QuizAttempt $attempt)
    {
        if ($attempt->status === 'completed') {
            return redirect()->route('admin.quiz.attempt.show', $attempt->id)
                            ->with('error', 'Quiz sudah selesai dikerjakan, tidak dapat diubah.');
        }

        $validated = $request->validate([
            'answers' => 'nullable|array',
            'answers.*' => 'nullable|string',
        ]);

        // Update answers
        $attempt->answers = $validated['answers'] ?? [];
        $attempt->save();

        // Jika user submit/selesai
        if ($request->has('complete')) {
            return $this->completeAttempt($attempt);
        }

        return redirect()->route('admin.quiz.attempt.show', $attempt->id)
                        ->with('success', 'Jawaban berhasil disimpan.');
    }

    /**
     * Complete the attempt.
     */
    public function completeAttempt(QuizAttempt $attempt)
    {
        if ($attempt->status === 'completed') {
            return redirect()->route('admin.quiz.attempt.show', $attempt->id)
                            ->with('info', 'Quiz sudah selesai dikerjakan.');
        }

        // Calculate score
        $attempt->calculateScore();
        $attempt->status = 'completed';
        $attempt->completed_at = now();
        $attempt->save();

        $message = 'Quiz selesai! ';
        if ($attempt->is_passed) {
            $message .= '🎉 Selamat Anda LULUS! Nilai: ' . $attempt->score . '/' . $attempt->total_questions;
        } else {
            $message .= '😞 Anda BELUM LULUS. Nilai: ' . $attempt->score . '/' . $attempt->total_questions . '. Silakan coba lagi.';
        }

        return redirect()->route('admin.quiz.attempt.show', $attempt->id)
                        ->with('success', $message);
    }

    /**
     * Remove the specified attempt.
     */
    public function destroy(QuizAttempt $attempt)
    {
        $attempt->delete();

        return redirect()->route('admin.quiz.attempt.index')
                        ->with('success', 'Data pengerjaan quiz berhasil dihapus.');
    }

    /**
     * Bulk delete attempts.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:quiz_attempts,id'
        ]);

        $deleted = QuizAttempt::whereIn('id', $request->ids)->delete();

        return redirect()->route('admin.quiz.attempt.index')
                        ->with('success', $deleted . ' data pengerjaan quiz berhasil dihapus.');
    }

    /**
     * Export attempts to CSV.
     */
    public function export(Request $request)
    {
        $query = QuizAttempt::with(['quiz', 'user'])->where('status', 'completed');

        // Filter by quiz
        if ($request->filled('quiz_id')) {
            $query->where('quiz_id', $request->quiz_id);
        }

        $attempts = $query->get();

        $filename = 'quiz_attempts_' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($attempts) {
            $file = fopen('php://output', 'w');
            
            // Header
            fputcsv($file, [
                'ID',
                'Quiz',
                'User',
                'Email',
                'Score',
                'Total Questions',
                'Correct Answers',
                'Percentage',
                'Status',
                'Started At',
                'Completed At',
                'Duration'
            ]);

            // Data
            foreach ($attempts as $attempt) {
                fputcsv($file, [
                    $attempt->id,
                    $attempt->quiz->judul ?? '-',
                    $attempt->user->name ?? '-',
                    $attempt->user->email ?? '-',
                    $attempt->score,
                    $attempt->total_questions,
                    $attempt->correct_answers,
                    $attempt->percentage . '%',
                    $attempt->status_label,
                    $attempt->started_at ? $attempt->started_at->format('d/m/Y H:i') : '-',
                    $attempt->completed_at ? $attempt->completed_at->format('d/m/Y H:i') : '-',
                    $attempt->formatted_duration,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get attempts by quiz (for AJAX).
     */
    public function getByQuiz($quizId)
    {
        $attempts = QuizAttempt::where('quiz_id', $quizId)
                              ->where('status', 'completed')
                              ->with('user')
                              ->orderBy('score', 'desc')
                              ->get(['id', 'user_id', 'score', 'created_at']);

        return response()->json($attempts);
    }

    /**
     * Get attempts by user (for AJAX).
     */
    public function getByUser($userId)
    {
        $attempts = QuizAttempt::where('user_id', $userId)
                              ->with('quiz')
                              ->orderBy('created_at', 'desc')
                              ->get(['id', 'quiz_id', 'score', 'status', 'created_at']);

        return response()->json($attempts);
    }
}