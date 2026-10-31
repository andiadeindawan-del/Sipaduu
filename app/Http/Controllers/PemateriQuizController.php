<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizAttempt;
use App\Models\Training;
use App\Models\Materi;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class PemateriQuizController extends Controller
{
    /**
     * Display a listing of quizzes for pemateri.
     */
    public function index(Request $request): View
    {
        $userId = auth()->id();
        $myTrainingIds = Training::where('trainer_id', $userId)->pluck('id');

        $query = Quiz::with(['training', 'materi'])
            ->whereIn('training_id', $myTrainingIds)
            ->withCount(['questions', 'attempts']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%$search%")
                  ->orWhere('deskripsi', 'like', "%$search%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('training_id')) {
            $query->where('training_id', $request->training_id);
        }

        $quizzes = $query->orderBy('order')->latest()->paginate(10)->withQueryString();

        $totalQuiz = Quiz::whereIn('training_id', $myTrainingIds)->count();
        $publishedQuiz = Quiz::whereIn('training_id', $myTrainingIds)->where('status', 'published')->count();
        $draftQuiz = Quiz::whereIn('training_id', $myTrainingIds)->where('status', 'draft')->count();

        $myQuizIds = Quiz::whereIn('training_id', $myTrainingIds)->pluck('id');
        $totalQuestions = QuizQuestion::whereIn('quiz_id', $myQuizIds)->count();
        $totalAttempts = QuizAttempt::whereIn('quiz_id', $myQuizIds)->count();

        $trainings = Training::where('trainer_id', $userId)->whereIn('status', ['published', 'berjalan'])->orderBy('judul')->get();
        $materis = Materi::whereIn('training_id', $myTrainingIds)->where('status', 'published')->orderBy('judul')->get();

        return view('pemateri.quiz.index', compact(
            'quizzes',
            'totalQuiz',
            'publishedQuiz',
            'draftQuiz',
            'totalQuestions',
            'totalAttempts',
            'materis',
            'trainings'
        ));
    }

    /**
     * Show the form for creating a new quiz.
     */
    public function create(Request $request): mixed
    {
        $userId = auth()->id();
        $selectedTrainingId = $request->get('training_id');
        if ($selectedTrainingId) {
            $checkTraining = Training::find($selectedTrainingId);
            if ($checkTraining && $checkTraining->isFinished()) {
                return redirect()->route('pemateri.trainings.show', $checkTraining)
                    ->with('error', 'Pelatihan ini sudah selesai atau masa pelaksanaannya telah berakhir. Anda tidak dapat menambahkan kuis baru.');
            }
        }

        $trainings = Training::where('trainer_id', $userId)
            ->where('status', '!=', 'selesai')
            ->where('status', '!=', 'dibatalkan')
            ->where(function ($q) {
                $q->whereNull('tanggal_selesai')
                  ->orWhere('tanggal_selesai', '>=', now());
            })
            ->orderBy('judul')
            ->get();
        $materis = Materi::whereIn('training_id', $trainings->pluck('id'))->where('status', 'published')->orderBy('judul')->get();

        $selectedMateriId = $request->get('materi_id');

        return view('pemateri.quiz.create', compact(
            'trainings',
            'materis',
            'selectedTrainingId',
            'selectedMateriId'
        ));
    }

    /**
     * Store a newly created quiz in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'training_id' => 'required|exists:trainings,id',
            'materi_id' => 'nullable|exists:materis,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'durasi' => 'nullable|integer|min:1',
            'passing_score' => 'required|integer|min:0|max:100',
            'max_attempt' => 'required|integer|min:1|max:10',
            'is_random' => 'nullable|boolean',
            'show_result' => 'nullable|boolean',
            'status' => 'required|in:draft,published,archived',
            'order' => 'nullable|integer|min:0',
        ]);

        $training = Training::findOrFail($validated['training_id']);
        if ($training->trainer_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk membuat kuis pada pelatihan ini.');
        }

        if ($training->isFinished()) {
            return back()->with('error', 'Pelatihan ini sudah selesai atau tanggal pelaksanaannya telah berakhir. Kuis baru tidak dapat ditambahkan lagi.')->withInput();
        }

        $validated['is_random'] = $request->boolean('is_random') ? 1 : 0;
        $validated['show_result'] = $request->boolean('show_result') ? 1 : 0;

        if (!isset($validated['order']) || $validated['order'] === null) {
            $validated['order'] = (Quiz::max('order') ?? 0) + 1;
        }

        try {
            $quiz = Quiz::create($validated);
            return redirect()->route('pemateri.quiz.show', $quiz)
                ->with('success', '✅ Kuis berhasil dibuat. Silakan tambahkan pertanyaan untuk kuis ini.');
        } catch (\Exception $e) {
            Log::error('Error creating quiz: ' . $e->getMessage());
            return back()->with('error', '❌ Gagal membuat kuis: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified quiz with its questions and participant attempts.
     */
    public function show(Quiz $quiz): View
    {
        if (!$quiz->training || $quiz->training->trainer_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk kuis ini.');
        }

        $quiz->load(['training', 'materi', 'questions']);
        $totalQuestions = $quiz->questions()->count();
        $totalScore = $quiz->questions()->sum('score') ?? 0;

        // Data Peserta & Hasil Pengisian Kuis
        $totalParticipants = $quiz->attempts()->distinct('user_id')->count() ?? 0;
        $totalAttempts = $quiz->attempts()->count() ?? 0;
        $completedAttempts = $quiz->attempts()->where('status', 'completed')->count() ?? 0;

        $averageScore = $quiz->attempts()->where('status', 'completed')->avg('score') ?? 0;
        $highestScore = $quiz->attempts()->where('status', 'completed')->max('score') ?? 0;
        $lowestScore = $quiz->attempts()->where('status', 'completed')->min('score') ?? 0;

        $passedAttempts = $quiz->attempts()->where('status', 'completed')->where('score', '>=', $quiz->passing_score)->count();
        $passingRate = $completedAttempts > 0 ? round(($passedAttempts / $completedAttempts) * 100, 1) : 0;

        $results = $quiz->attempts()->with('user')->latest()->paginate(15)->withQueryString();

        return view('pemateri.quiz.show', compact(
            'quiz',
            'totalQuestions',
            'totalScore',
            'totalParticipants',
            'totalAttempts',
            'completedAttempts',
            'averageScore',
            'highestScore',
            'lowestScore',
            'passingRate',
            'results'
        ));
    }

    /**
     * Display participant attempt details.
     */
    public function showAttempt(Quiz $quiz, QuizAttempt $attempt): View
    {
        if ($attempt->quiz_id !== $quiz->id) {
            abort(404);
        }

        if (!$quiz->training || $quiz->training->trainer_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk kuis ini.');
        }

        $attempt->load(['user', 'quiz']);
        $questions = $quiz->questions()->get();
        $userAnswers = $attempt->answers ?? [];

        return view('pemateri.quiz.attempt_detail', compact('quiz', 'attempt', 'questions', 'userAnswers'));
    }

    /**
     * Show the form for editing the specified quiz.
     */
    public function edit(Quiz $quiz): mixed
    {
        if (!$quiz->training || $quiz->training->trainer_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk kuis ini.');
        }

        if ($quiz->training->isFinished()) {
            return redirect()->route('pemateri.quiz.show', $quiz)
                ->with('error', 'Pelatihan untuk kuis ini telah selesai. Pengaturan kuis pada pelatihan yang sudah selesai tidak dapat diubah lagi.');
        }

        $userId = auth()->id();
        $trainings = Training::where('trainer_id', $userId)
            ->where('status', '!=', 'selesai')
            ->where('status', '!=', 'dibatalkan')
            ->where(function ($q) {
                $q->whereNull('tanggal_selesai')
                  ->orWhere('tanggal_selesai', '>=', now());
            })
            ->orderBy('judul')
            ->get();
        $materis = Materi::whereIn('training_id', $trainings->pluck('id'))->where('status', 'published')->orderBy('judul')->get();

        return view('pemateri.quiz.edit', compact('quiz', 'trainings', 'materis'));
    }

    /**
     * Update the specified quiz in storage.
     */
    public function update(Request $request, Quiz $quiz): RedirectResponse
    {
        if (!$quiz->training || $quiz->training->trainer_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk kuis ini.');
        }

        if ($quiz->training->isFinished()) {
            return redirect()->route('pemateri.quiz.show', $quiz)
                ->with('error', 'Pelatihan untuk kuis ini telah selesai. Pengaturan kuis pada pelatihan yang sudah selesai tidak dapat diubah lagi.');
        }

        $validated = $request->validate([
            'training_id' => 'required|exists:trainings,id',
            'materi_id' => 'nullable|exists:materis,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'durasi' => 'nullable|integer|min:1',
            'passing_score' => 'required|integer|min:0|max:100',
            'max_attempt' => 'required|integer|min:1|max:10',
            'is_random' => 'nullable|boolean',
            'show_result' => 'nullable|boolean',
            'status' => 'required|in:draft,published,archived',
            'order' => 'nullable|integer|min:0',
        ]);

        $training = Training::findOrFail($validated['training_id']);
        if ($training->trainer_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk pelatihan yang dipilih.');
        }

        if ($training->isFinished()) {
            return back()->with('error', 'Pelatihan yang dipilih sudah selesai atau tanggal pelaksanaannya telah berakhir.')->withInput();
        }

        $validated['is_random'] = $request->boolean('is_random') ? 1 : 0;
        $validated['show_result'] = $request->boolean('show_result') ? 1 : 0;

        $quiz->update($validated);

        return redirect()->route('pemateri.quiz.index')
            ->with('success', '✅ Kuis berhasil diperbarui.');
    }

    /**
     * Remove the specified quiz from storage.
     */
    public function destroy(Quiz $quiz): RedirectResponse
    {
        if (!$quiz->training || $quiz->training->trainer_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk kuis ini.');
        }

        if ($quiz->training->isFinished()) {
            return redirect()->route('pemateri.quiz.index')
                ->with('error', 'Pelatihan untuk kuis ini telah selesai. Kuis pada pelatihan yang sudah selesai tidak dapat dihapus.');
        }

        $quiz->questions()->delete();
        $quiz->delete();

        return redirect()->route('pemateri.quiz.index')
            ->with('success', '✅ Kuis berhasil dihapus.');
    }
}
