<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class PemateriQuizQuestionController extends Controller
{
    private function authorizeQuiz(Quiz $quiz): void
    {
        if (!$quiz->training || $quiz->training->trainer_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk pertanyaan pada kuis ini.');
        }
    }

    /**
     * Display a listing of questions for a quiz.
     */
    public function index(Request $request, Quiz $quiz): View
    {
        $this->authorizeQuiz($quiz);

        $query = QuizQuestion::where('quiz_id', $quiz->id);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('question', 'like', "%$search%");
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $questions = $query->orderBy('order', 'asc')->paginate(10);
        $totalQuestions = QuizQuestion::where('quiz_id', $quiz->id)->count();
        $totalScore = QuizQuestion::where('quiz_id', $quiz->id)->sum('score') ?? 0;

        return view('pemateri.quiz.question.index', compact('quiz', 'questions', 'totalQuestions', 'totalScore'));
    }

    /**
     * Show the form for creating a new question.
     */
    public function create(Quiz $quiz): mixed
    {
        $this->authorizeQuiz($quiz);

        if ($quiz->training && $quiz->training->isFinished()) {
            return redirect()->route('pemateri.quiz.show', $quiz)
                ->with('error', 'Pelatihan untuk kuis ini sudah selesai. Butir pertanyaan baru tidak dapat ditambahkan lagi.');
        }

        $currentQuestionCount = $quiz->questions()->count();
        $nextOrder = $currentQuestionCount + 1;

        return view('pemateri.quiz.question.create', compact('quiz', 'nextOrder'));
    }

    /**
     * Store a newly created question in storage.
     */
    public function store(Request $request, Quiz $quiz): RedirectResponse
    {
        $this->authorizeQuiz($quiz);

        if ($quiz->training && $quiz->training->isFinished()) {
            return redirect()->route('pemateri.quiz.show', $quiz)
                ->with('error', 'Pelatihan untuk kuis ini sudah selesai. Butir pertanyaan baru tidak dapat ditambahkan lagi.');
        }

        $validated = $request->validate([
            'question' => 'required|string',
            'type' => 'required|in:multiple_choice,true_false,essay',
            'points' => 'required|numeric|min:0',
            'order' => 'nullable|integer|min:0',
            'options' => 'nullable|array',
            'options.*' => 'nullable|string',
            'correct_answer' => 'nullable|string',
            'essay_answer_key' => 'nullable|string',
        ]);

        $questionData = [
            'quiz_id' => $quiz->id,
            'question' => $validated['question'],
            'type' => $validated['type'],
            'score' => (int) $validated['points'],
            'nilai' => (int) $validated['points'],
            'order' => $validated['order'] ?? (($quiz->questions()->max('order') ?? 0) + 1),
            'essay_answer_key' => $validated['essay_answer_key'] ?? null,
        ];

        if ($validated['type'] === 'multiple_choice') {
            $filteredOptions = array_filter($validated['options'] ?? [], function($val) {
                return !empty(trim($val));
            });

            if (count($filteredOptions) < 2) {
                return back()->withErrors(['options' => 'Minimal 2 pilihan jawaban untuk soal pilihan ganda.'])->withInput();
            }

            $questionData['options'] = array_values($filteredOptions);
            $questionData['correct_answer'] = $validated['correct_answer'] ?? 'A';
        } elseif ($validated['type'] === 'true_false') {
            $questionData['options'] = ['Benar', 'Salah'];
            $questionData['correct_answer'] = $validated['correct_answer'] ?? 'Benar';
        } else {
            $questionData['options'] = null;
            $questionData['correct_answer'] = null;
        }

        QuizQuestion::create($questionData);

        return redirect()->route('pemateri.quiz.show', $quiz)
            ->with('success', '✅ Pertanyaan berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified question.
     */
    public function edit(Quiz $quiz, QuizQuestion $question): mixed
    {
        $this->authorizeQuiz($quiz);

        if ($question->quiz_id !== $quiz->id) {
            abort(404);
        }

        if ($quiz->training && $quiz->training->isFinished()) {
            return redirect()->route('pemateri.quiz.show', $quiz)
                ->with('error', 'Pelatihan untuk kuis ini sudah selesai. Butir pertanyaan tidak dapat diubah lagi.');
        }

        return view('pemateri.quiz.question.edit', compact('quiz', 'question'));
    }

    /**
     * Update the specified question in storage.
     */
    public function update(Request $request, Quiz $quiz, QuizQuestion $question): RedirectResponse
    {
        $this->authorizeQuiz($quiz);

        if ($question->quiz_id !== $quiz->id) {
            abort(404);
        }

        if ($quiz->training && $quiz->training->isFinished()) {
            return redirect()->route('pemateri.quiz.show', $quiz)
                ->with('error', 'Pelatihan untuk kuis ini sudah selesai. Butir pertanyaan tidak dapat diubah lagi.');
        }

        $validated = $request->validate([
            'question' => 'required|string',
            'type' => 'required|in:multiple_choice,true_false,essay',
            'points' => 'required|numeric|min:0',
            'order' => 'nullable|integer|min:0',
            'options' => 'nullable|array',
            'options.*' => 'nullable|string',
            'correct_answer' => 'nullable|string',
            'essay_answer_key' => 'nullable|string',
        ]);

        $questionData = [
            'question' => $validated['question'],
            'type' => $validated['type'],
            'score' => (int) $validated['points'],
            'nilai' => (int) $validated['points'],
            'order' => $validated['order'] ?? $question->order,
            'essay_answer_key' => $validated['essay_answer_key'] ?? null,
        ];

        if ($validated['type'] === 'multiple_choice') {
            $filteredOptions = array_filter($validated['options'] ?? [], function($val) {
                return !empty(trim($val));
            });

            if (count($filteredOptions) < 2) {
                return back()->withErrors(['options' => 'Minimal 2 pilihan jawaban untuk soal pilihan ganda.'])->withInput();
            }

            $questionData['options'] = array_values($filteredOptions);
            $questionData['correct_answer'] = $validated['correct_answer'] ?? 'A';
        } elseif ($validated['type'] === 'true_false') {
            $questionData['options'] = ['Benar', 'Salah'];
            $questionData['correct_answer'] = $validated['correct_answer'] ?? 'Benar';
        } else {
            $questionData['options'] = null;
            $questionData['correct_answer'] = null;
        }

        $question->update($questionData);

        return redirect()->route('pemateri.quiz.show', $quiz)
            ->with('success', '✅ Pertanyaan berhasil diperbarui.');
    }

    /**
     * Remove the specified question from storage.
     */
    public function destroy(Quiz $quiz, QuizQuestion $question): RedirectResponse
    {
        $this->authorizeQuiz($quiz);

        if ($question->quiz_id !== $quiz->id) {
            abort(404);
        }

        if ($quiz->training && $quiz->training->isFinished()) {
            return redirect()->route('pemateri.quiz.show', $quiz)
                ->with('error', 'Pelatihan untuk kuis ini sudah selesai. Butir pertanyaan tidak dapat dihapus.');
        }

        $question->delete();

        return redirect()->route('pemateri.quiz.show', $quiz)
            ->with('success', '✅ Pertanyaan berhasil dihapus.');
    }
}
