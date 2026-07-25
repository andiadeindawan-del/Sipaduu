<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizAttempt;
use App\Models\Training;
use App\Models\Materi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Quiz::with(['training', 'materi'])
            ->withCount('questions');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%$search%")
                  ->orWhere('deskripsi', 'like', "%$search%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by materi_id
        if ($request->filled('materi_id')) {
            $query->where('materi_id', $request->materi_id);
        }

        // Filter by training_id
        if ($request->filled('training_id')) {
            $query->where('training_id', $request->training_id);
        }

        // Filter by has questions
        if ($request->filled('has_questions')) {
            if ($request->has_questions === 'yes') {
                $query->has('questions');
            } else {
                $query->doesntHave('questions');
            }
        }

        $quizzes = $query->orderBy('order')->latest()->paginate(10)->withQueryString();

        // Statistics
        $totalQuiz = Quiz::count();
        $publishedQuiz = Quiz::where('status', 'published')->count();
        $draftQuiz = Quiz::where('status', 'draft')->count();
        $archivedQuiz = Quiz::where('status', 'archived')->count();
        
        // PERBAIKAN: Gunakan 'score' bukan 'points'
        $totalQuestions = QuizQuestion::count();
        $totalAttempts = QuizAttempt::count();

        // For filters
        $materis = Materi::where('status', 'published')->orderBy('judul')->get();
        $trainings = Training::where('status', 'published')->orderBy('judul')->get();

        // Get selected materi for header
        $selectedMateri = null;
        if ($request->filled('materi_id')) {
            $selectedMateri = Materi::find($request->materi_id);
        }

        return view('admin.quiz.index', compact(
            'quizzes',
            'totalQuiz',
            'publishedQuiz',
            'draftQuiz',
            'archivedQuiz',
            'totalQuestions',
            'totalAttempts',
            'materis',
            'trainings',
            'selectedMateri'
        ));
    }

    /**
     * Display a listing of quizzes for peserta.
     */
    public function pesertaIndex(Request $request)
    {
        $userId = auth()->id();

        $query = Quiz::with(['materi', 'training'])
            ->withCount('questions')
            ->where('status', 'published');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%$search%")
                  ->orWhere('deskripsi', 'like', "%$search%");
            });
        }

        // Filter by status progress
        $filter = $request->get('filter');
        if ($filter === 'completed') {
            $query->whereHas('attempts', function($q) use ($userId) {
                $q->where('user_id', $userId)->where('status', 'completed');
            });
        } elseif ($filter === 'in_progress') {
            $query->whereHas('attempts', function($q) use ($userId) {
                $q->where('user_id', $userId)->where('status', 'in_progress');
            });
        } elseif ($filter === 'not_started') {
            $query->whereDoesntHave('attempts', function($q) use ($userId) {
                $q->where('user_id', $userId);
            });
        }

        $quizzes = $query->orderBy('order')->paginate(12)->withQueryString();

        // Statistics
        $totalQuizzes = Quiz::where('status', 'published')->count();
        $completedQuizzes = Quiz::whereHas('attempts', function($q) use ($userId) {
            $q->where('user_id', $userId)->where('status', 'completed');
        })->count();
        $inProgressQuizzes = Quiz::whereHas('attempts', function($q) use ($userId) {
            $q->where('user_id', $userId)->where('status', 'in_progress');
        })->count();
        $averageScore = QuizAttempt::where('user_id', $userId)
            ->where('status', 'completed')
            ->avg('score') ?? 0;

        return view('peserta.quiz.index', compact(
            'quizzes',
            'totalQuizzes',
            'completedQuizzes',
            'inProgressQuizzes',
            'averageScore'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $trainings = Training::whereIn('status', ['published', 'berjalan', 'selesai'])
                             ->orderBy('judul')
                             ->get();
        
        $materis = Materi::where('status', 'published')
                         ->orderBy('judul')
                         ->get();
        
        $selectedMateri = null;
        if ($request->filled('materi_id')) {
            $selectedMateri = Materi::find($request->materi_id);
        }

        $selectedTraining = null;
        if ($request->filled('training_id')) {
            $selectedTraining = Training::find($request->training_id);
        }
        
        return view('admin.quiz.create', compact(
            'trainings', 
            'materis', 
            'selectedMateri',
            'selectedTraining'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'training_id' => 'nullable|exists:trainings,id',
            'materi_id' => 'nullable|exists:materis,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'durasi' => 'nullable|integer|min:1',
            'passing_score' => 'required|integer|min:0|max:100',
            'max_attempt' => 'required|integer|min:1|max:10',
            'is_random' => 'nullable|boolean',
            'show_result' => 'nullable|boolean',
            'status' => 'required|in:draft,published,archived',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:today',
            'order' => 'nullable|integer|min:0',
        ]);

        $validated['is_random'] = $request->boolean('is_random') ? 1 : 0;
        $validated['show_result'] = $request->boolean('show_result') ? 1 : 0;

        if (!isset($validated['order']) || $validated['order'] === null) {
            $validated['order'] = (Quiz::max('order') ?? 0) + 1;
        }

        Log::info('Creating quiz:', [
            'judul' => $validated['judul'],
            'passing_score' => $validated['passing_score'],
            'max_attempt' => $validated['max_attempt'],
            'is_random' => $validated['is_random'],
            'show_result' => $validated['show_result'],
            'status' => $validated['status'],
            'order' => $validated['order']
        ]);

        try {
            $quiz = Quiz::create($validated);
            
            $message = '✅ Quiz berhasil ditambahkan.';
            if ($quiz->materi) {
                $message .= ' Terhubung dengan materi: <strong>' . $quiz->materi->judul . '</strong>';
            }
            if ($quiz->training) {
                $message .= ' Terhubung dengan training: <strong>' . $quiz->training->judul . '</strong>';
            }
            
            Log::info('Quiz created successfully:', ['quiz_id' => $quiz->id]);
            
            return redirect()->route('admin.quiz.index')
                            ->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Error creating quiz: ' . $e->getMessage());
            return redirect()->back()
                            ->with('error', '❌ Gagal menyimpan quiz: ' . $e->getMessage())
                            ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Quiz $quiz)
    {
        $quiz->load(['training', 'materi', 'questions']);
        
        $totalQuestions = $quiz->questions()->count();
        $totalParticipants = $quiz->attempts()->distinct('user_id')->count() ?? 0;
        
        // PERBAIKAN: Gunakan 'score' bukan 'points'
        $averageScore = $quiz->attempts()->where('status', 'completed')->avg('score') ?? 0;
        $highestScore = $quiz->attempts()->where('status', 'completed')->max('score') ?? 0;
        $lowestScore = $quiz->attempts()->where('status', 'completed')->min('score') ?? 0;
        $passingRate = $quiz->passing_rate ?? 0;
        
        $results = $quiz->attempts()->with('user')->latest()->get();
        
        return view('admin.quiz.show', compact(
            'quiz',
            'totalQuestions',
            'totalParticipants',
            'averageScore',
            'highestScore',
            'lowestScore',
            'passingRate',
            'results'
        ));
    }

    /**
     * Display quiz for peserta.
     */
    public function pesertaShow(Quiz $quiz)
    {
        if ($quiz->status !== 'published') {
            abort(404);
        }

        $quiz->load(['questions', 'materi', 'training']);
        
        $totalQuestions = $quiz->questions()->count();
        $userAttempts = $quiz->attempts()->where('user_id', auth()->id())->count();
        $remainingAttempts = max(0, $quiz->max_attempt - $userAttempts);
        $userBestScore = $quiz->attempts()->where('user_id', auth()->id())->max('score');
        
        $userAttempt = $quiz->attempts()
            ->where('user_id', auth()->id())
            ->where('status', 'completed')
            ->first();
        
        return view('peserta.quiz.show', compact(
            'quiz',
            'totalQuestions',
            'remainingAttempts',
            'userBestScore',
            'userAttempt'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Quiz $quiz)
    {
        $trainings = Training::whereIn('status', ['published', 'berjalan', 'selesai'])
                             ->orderBy('judul')
                             ->get();
        
        $materis = Materi::where('status', 'published')
                         ->orderBy('judul')
                         ->get();
        
        return view('admin.quiz.edit', compact('quiz', 'trainings', 'materis'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'training_id' => 'nullable|exists:trainings,id',
            'materi_id' => 'nullable|exists:materis,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'durasi' => 'nullable|integer|min:1',
            'passing_score' => 'required|integer|min:0|max:100',
            'max_attempt' => 'required|integer|min:1|max:10',
            'is_random' => 'nullable|boolean',
            'show_result' => 'nullable|boolean',
            'status' => 'required|in:draft,published,archived',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'order' => 'nullable|integer|min:0',
        ]);

        $validated['is_random'] = $request->boolean('is_random') ? 1 : 0;
        $validated['show_result'] = $request->boolean('show_result') ? 1 : 0;

        if (!isset($validated['order']) || $validated['order'] === null) {
            $validated['order'] = $quiz->order ?? 0;
        }

        Log::info('Updating quiz:', [
            'id' => $quiz->id,
            'judul' => $validated['judul'],
            'passing_score' => $validated['passing_score'],
            'max_attempt' => $validated['max_attempt'],
            'is_random' => $validated['is_random'],
            'show_result' => $validated['show_result'],
            'status' => $validated['status'],
            'order' => $validated['order']
        ]);

        try {
            $quiz->update($validated);

            $message = '✅ Quiz berhasil diperbarui.';
            if ($quiz->materi) {
                $message .= ' Terhubung dengan materi: <strong>' . $quiz->materi->judul . '</strong>';
            }
            if ($quiz->training) {
                $message .= ' Terhubung dengan training: <strong>' . $quiz->training->judul . '</strong>';
            }

            Log::info('Quiz updated successfully:', ['quiz_id' => $quiz->id]);

            return redirect()->route('admin.quiz.index')
                            ->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Error updating quiz: ' . $e->getMessage());
            return redirect()->back()
                            ->with('error', '❌ Gagal memperbarui quiz: ' . $e->getMessage())
                            ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quiz $quiz)
    {

        $quiz->delete();

        Log::info('Quiz deleted:', ['quiz_id' => $quiz->id, 'judul' => $quiz->judul]);

        return redirect()->route('admin.quiz.index')
                        ->with('success', '✅ Quiz berhasil dihapus.');
    }

    /**
     * Change quiz status.
     */
    public function changeStatus(Request $request, Quiz $quiz)
    {
        $request->validate([
            'status' => 'required|in:draft,published,archived'
        ]);

        $oldStatus = $quiz->status;
        $quiz->update(['status' => $request->status]);

        $statusLabels = [
            'draft' => 'Draft',
            'published' => 'Published',
            'archived' => 'Archived'
        ];

        Log::info('Quiz status changed:', [
            'quiz_id' => $quiz->id,
            'old_status' => $oldStatus,
            'new_status' => $request->status
        ]);

        return redirect()->route('admin.quiz.index')
                        ->with('success', "✅ Status quiz berhasil diubah dari <strong>{$statusLabels[$oldStatus]}</strong> menjadi <strong>{$statusLabels[$request->status]}</strong>.");
    }

    /**
     * Duplicate quiz.
     */
    public function duplicate(Quiz $quiz)
    {
        try {
            $newQuiz = $quiz->replicate();
            $newQuiz->judul = $quiz->judul . ' (Copy)';
            $newQuiz->status = 'draft';
            $newQuiz->order = (Quiz::max('order') ?? 0) + 1;
            $newQuiz->save();

            $duplicatedCount = 0;
            foreach ($quiz->questions as $question) {
                $newQuestion = $question->replicate();
                $newQuestion->quiz_id = $newQuiz->id;
                $newQuestion->save();
                $duplicatedCount++;
            }

            Log::info('Quiz duplicated:', [
                'original_quiz_id' => $quiz->id,
                'new_quiz_id' => $newQuiz->id,
                'duplicated_questions' => $duplicatedCount
            ]);

            return redirect()->route('admin.quiz.index')
                            ->with('success', "✅ Quiz berhasil diduplikasi. ({$duplicatedCount} pertanyaan ikut terduplikasi)");
        } catch (\Exception $e) {
            Log::error('Error duplicating quiz: ' . $e->getMessage());
            return redirect()->route('admin.quiz.index')
                            ->with('error', '❌ Gagal menduplikasi quiz: ' . $e->getMessage());
        }
    }

    /**
     * Bulk delete quizzes.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:quizzes,id'
        ]);

        $deleted = 0;
        $errors = [];

        foreach ($request->ids as $id) {
            $quiz = Quiz::find($id);
            if ($quiz) {
                $quiz->delete();
                $deleted++;
            }
        }

        $message = "✅ {$deleted} quiz berhasil dihapus.";
        if (!empty($errors)) {
            $message .= " ⚠️ " . implode(' ', $errors);
        }

        Log::info('Bulk delete quizzes:', [
            'deleted_count' => $deleted,
            'errors' => $errors
        ]);

        return redirect()->route('admin.quiz.index')->with('success', $message);
    }

    /**
     * Get quizzes by materi (for AJAX).
     */
    public function getByMateri($materiId)
    {
        $quizzes = Quiz::where('materi_id', $materiId)
                      ->where('status', 'published')
                      ->orderBy('judul')
                      ->get(['id', 'judul']);
        
        return response()->json($quizzes);
    }

    /**
     * Get quizzes by training (for AJAX).
     */
    public function getByTraining($trainingId)
    {
        $quizzes = Quiz::where('training_id', $trainingId)
                      ->where('status', 'published')
                      ->orderBy('judul')
                      ->get(['id', 'judul']);
        
        return response()->json($quizzes);
    }

    /**
     * Get quiz statistics for dashboard.
     */
    public function getStatistics()
    {
        try {
            $totalQuiz = Quiz::count();
            $publishedQuiz = Quiz::where('status', 'published')->count();
            $draftQuiz = Quiz::where('status', 'draft')->count();
            $archivedQuiz = Quiz::where('status', 'archived')->count();
            
            // PERBAIKAN: Gunakan 'score' bukan 'points'
            $totalQuestions = QuizQuestion::count();
            $totalAttempts = QuizAttempt::count();
            $averageScore = QuizAttempt::where('status', 'completed')->avg('score') ?? 0;
            
            $mostPopularQuiz = Quiz::withCount('attempts')
                                  ->orderBy('attempts_count', 'desc')
                                  ->first();
            
            return response()->json([
                'success' => true,
                'total_quiz' => $totalQuiz,
                'published_quiz' => $publishedQuiz,
                'draft_quiz' => $draftQuiz,
                'archived_quiz' => $archivedQuiz,
                'total_questions' => $totalQuestions,
                'total_attempts' => $totalAttempts,
                'average_score' => round($averageScore, 2),
                'most_popular_quiz' => $mostPopularQuiz ? [
                    'id' => $mostPopularQuiz->id,
                    'judul' => $mostPopularQuiz->judul,
                    'attempts_count' => $mostPopularQuiz->attempts_count,
                ] : null,
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting quiz statistics: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil statistik quiz'
            ], 500);
        }
    }

    /**
     * Reorder quizzes.
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'required|integer|exists:quizzes,id',
        ]);

        try {
            foreach ($request->order as $index => $id) {
                Quiz::where('id', $id)->update(['order' => $index + 1]);
            }

            Log::info('Quizzes reordered:', ['order' => $request->order]);

            return response()->json([
                'success' => true,
                'message' => '✅ Urutan quiz berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            Log::error('Error reordering quizzes: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => '❌ Gagal memperbarui urutan quiz.'
            ], 500);
        }
    }

    /**
     * Get quiz attempts for peserta.
     */
    public function pesertaAttempts(Quiz $quiz)
    {
        $attempts = $quiz->attempts()
                        ->where('user_id', auth()->id())
                        ->orderBy('created_at', 'desc')
                        ->get();
        
        return response()->json([
            'success' => true,
            'attempts' => $attempts
        ]);
    }
}