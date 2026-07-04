<?php

namespace App\Http\Controllers;

use App\Models\QuizQuestion;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class QuizQuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Quiz $quiz = null)
    {
        if ($quiz) {
            $query = QuizQuestion::with('quiz')->where('quiz_id', $quiz->id);
        } else {
            $query = QuizQuestion::with('quiz');
        }

        if ($request->filled('quiz_id')) {
            $query->where('quiz_id', $request->quiz_id);
            $quiz = Quiz::find($request->quiz_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('question', 'like', "%$search%")
                  ->orWhere('pertanyaan', 'like', "%$search%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $questions = $query->orderBy('order', 'asc')->paginate(10)->withQueryString();

        $totalQuestions = $query->count();
        $multipleChoiceCount = (clone $query)->where('type', 'multiple_choice')->count();
        $trueFalseCount = (clone $query)->where('type', 'true_false')->count();
        $essayCount = (clone $query)->where('type', 'essay')->count();
        $totalScore = (clone $query)->sum('score');

        $quizzes = Quiz::orderBy('judul')->get();

        return view('admin.quiz.question.index', compact(
            'questions', 
            'quizzes', 
            'quiz',
            'totalQuestions',
            'multipleChoiceCount',
            'trueFalseCount',
            'essayCount',
            'totalScore'
        ));
    }

    public function create(Request $request, Quiz $quiz = null)
    {
        if (!$quiz && $request->has('quiz_id')) {
            $quiz = Quiz::find($request->quiz_id);
        }

        $quizzes = Quiz::orderBy('judul')->get();
        
        return view('admin.quiz.question.create', compact('quizzes', 'quiz'));
    }

    public function store(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'quiz_id' => 'required|exists:quizzes,id',
            'question' => 'required|string',
            'type' => 'required|in:multiple_choice,true_false,essay',
            'score' => 'required|integer|min:1|max:100',
            'options' => 'nullable|array',
            'options.*' => 'nullable|string',
            'correct_answer' => 'required|string',
            'order' => 'nullable|integer|min:0',
        ]);

        if (empty($validated['order'])) {
            $maxOrder = QuizQuestion::where('quiz_id', $validated['quiz_id'])->max('order');
            $validated['order'] = $maxOrder !== null ? $maxOrder + 1 : 1;
        }

        if ($validated['type'] === 'multiple_choice') {
            $filteredOptions = array_filter($validated['options'] ?? [], function($value) {
                return !empty(trim($value));
            });
            
            $validated['options'] = array_values($filteredOptions);
            
            if (count($validated['options']) < 2) {
                return back()->withErrors(['options' => 'Minimal 2 pilihan jawaban untuk soal pilihan ganda.'])
                             ->withInput();
            }
        } else {
            $validated['options'] = null;
        }

        if (!isset($validated['quiz_id']) && $quiz) {
            $validated['quiz_id'] = $quiz->id;
        }

        $validated['score'] = (int) $validated['score'];

        Log::info('Creating question:', [
            'quiz_id' => $validated['quiz_id'],
            'score' => $validated['score'],
            'type' => $validated['type'],
            'score_type' => gettype($validated['score'])
        ]);

        QuizQuestion::create($validated);

        return redirect()->route('admin.quiz.questions.index', $validated['quiz_id'])
                        ->with('success', '✅ Pertanyaan berhasil ditambahkan.');
    }

    public function show($quizId, $questionId)
    {
        $question = QuizQuestion::with('quiz')->findOrFail($questionId);
        $quiz = Quiz::findOrFail($quizId);
        
        if ($question->quiz_id !== $quiz->id) {
            abort(404);
        }
        
        return view('admin.quiz.question.show', compact('question', 'quiz'));
    }

    public function edit($quizId, $questionId)
    {
        $question = QuizQuestion::findOrFail($questionId);
        $quiz = Quiz::findOrFail($quizId);
        
        if ($question->quiz_id !== $quiz->id) {
            abort(404);
        }
        
        $quizzes = Quiz::orderBy('judul')->get();
        return view('admin.quiz.question.edit', compact('question', 'quizzes', 'quiz'));
    }

    public function update(Request $request, $quizId, $questionId)
    {
        // Debug: lihat semua data yang masuk
        Log::info('UPDATE REQUEST DATA:', $request->all());
        Log::info('UPDATE REQUEST - quizId: ' . $quizId . ', questionId: ' . $questionId);

        // Cari question dan quiz
        $question = QuizQuestion::findOrFail($questionId);
        $quiz = Quiz::findOrFail($quizId);
        
        Log::info('FOUND QUESTION:', ['question' => $question->toArray()]);
        Log::info('FOUND QUIZ:', ['quiz' => $quiz->toArray()]);
        
        if ($question->quiz_id !== $quiz->id) {
            Log::error('QUESTION DOES NOT BELONG TO QUIZ: question.quiz_id=' . $question->quiz_id . ', quiz.id=' . $quiz->id);
            abort(404);
        }

        $validated = $request->validate([
            'quiz_id' => 'required|exists:quizzes,id',
            'question' => 'required|string',
            'type' => 'required|in:multiple_choice,true_false,essay',
            'score' => 'required|integer|min:1|max:100',
            'options' => 'nullable|array',
            'options.*' => 'nullable|string',
            'correct_answer' => 'required|string',
            'order' => 'nullable|integer|min:0',
        ]);

        Log::info('VALIDATED DATA:', $validated);

        // Proses options untuk multiple choice
        if ($validated['type'] === 'multiple_choice') {
            $filteredOptions = array_filter($validated['options'] ?? [], function($value) {
                return !empty(trim($value));
            });
            
            $validated['options'] = array_values($filteredOptions);
            
            if (count($validated['options']) < 2) {
                return back()->withErrors(['options' => 'Minimal 2 pilihan jawaban untuk soal pilihan ganda.'])
                             ->withInput();
            }
        } else {
            $validated['options'] = null;
        }

        // PASTIKAN SCORE ADALAH INTEGER
        $validated['score'] = (int) $validated['score'];

        Log::info('DATA BEFORE UPDATE:', [
            'id' => $question->id,
            'quiz_id' => $validated['quiz_id'],
            'question' => $validated['question'],
            'type' => $validated['type'],
            'score' => $validated['score'],
            'score_type' => gettype($validated['score']),
            'options' => $validated['options'],
            'correct_answer' => $validated['correct_answer'],
            'order' => $validated['order'] ?? 0
        ]);

        try {
            // Update menggunakan metode langsung
            $updated = $question->update([
                'quiz_id' => $validated['quiz_id'],
                'question' => $validated['question'],
                'type' => $validated['type'],
                'score' => $validated['score'],
                'options' => $validated['options'],
                'correct_answer' => $validated['correct_answer'],
                'order' => $validated['order'] ?? 0,
            ]);

            Log::info('UPDATE RESULT:', ['updated' => $updated]);

            // Cek apakah data sudah terupdate
            $question->refresh();
            Log::info('QUESTION AFTER UPDATE:', [
                'id' => $question->id,
                'question' => $question->question,
                'score' => $question->score,
                'score_type' => gettype($question->score)
            ]);

            return redirect()->route('admin.quiz.questions.index', $question->quiz_id)
                            ->with('success', '✅ Pertanyaan berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('ERROR UPDATING QUESTION:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', '❌ Gagal memperbarui pertanyaan: ' . $e->getMessage())
                         ->withInput();
        }
    }

    public function destroy($quizId, $questionId)
    {
        $question = QuizQuestion::findOrFail($questionId);
        $quiz = Quiz::findOrFail($quizId);
        
        if ($question->quiz_id !== $quiz->id) {
            abort(404);
        }

        $quizId = $question->quiz_id;
        $question->delete();

        return redirect()->route('admin.quiz.questions.index', $quizId)
                        ->with('success', '✅ Pertanyaan berhasil dihapus.');
    }

    public function bulkDelete(Request $request, Quiz $quiz = null)
    {
        $request->validate([
            'ids' => 'required|string',
        ]);

        $ids = json_decode($request->ids, true);

        if (empty($ids) || !is_array($ids)) {
            return redirect()->back()->with('error', '⚠️ Tidak ada pertanyaan yang dipilih.');
        }

        $query = QuizQuestion::whereIn('id', $ids);
        if ($quiz) {
            $query->where('quiz_id', $quiz->id);
        }

        $deleted = $query->delete();
        $quizId = $quiz ? $quiz->id : null;

        return redirect()->route('admin.quiz.questions.index', $quizId)
                        ->with('success', "✅ {$deleted} pertanyaan berhasil dihapus.");
    }

    public function reorder(Request $request, Quiz $quiz)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'required|integer|exists:quiz_questions,id',
        ]);

        foreach ($request->order as $index => $id) {
            QuizQuestion::where('id', $id)
                       ->where('quiz_id', $quiz->id)
                       ->update(['order' => $index + 1]);
        }

        return response()->json(['success' => true, 'message' => '✅ Urutan berhasil diperbarui.']);
    }

    public function getByQuiz($quizId)
    {
        $questions = QuizQuestion::where('quiz_id', $quizId)
                                ->orderBy('order')
                                ->get(['id', 'question', 'type', 'score']);
        
        return response()->json($questions);
    }

    public function duplicate($quizId, $questionId)
    {
        $question = QuizQuestion::findOrFail($questionId);
        $quiz = Quiz::findOrFail($quizId);
        
        if ($question->quiz_id !== $quiz->id) {
            abort(404);
        }

        $newQuestion = $question->replicate();
        $newQuestion->order = QuizQuestion::where('quiz_id', $quiz->id)->max('order') + 1;
        $newQuestion->save();

        return redirect()->route('admin.quiz.questions.index', $quiz->id)
                        ->with('success', '✅ Pertanyaan berhasil diduplikasi.');
    }

    public function export(Quiz $quiz)
    {
        $questions = QuizQuestion::where('quiz_id', $quiz->id)->orderBy('order')->get();

        $filename = 'questions_' . $quiz->judul . '_' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($questions) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, [
                'No',
                'Pertanyaan',
                'Tipe',
                'Pilihan A',
                'Pilihan B',
                'Pilihan C',
                'Pilihan D',
                'Jawaban Benar',
                'Nilai',
                'Urutan'
            ]);

            foreach ($questions as $index => $question) {
                $options = is_array($question->options) ? $question->options : [];
                $optionA = $options[0] ?? '';
                $optionB = $options[1] ?? '';
                $optionC = $options[2] ?? '';
                $optionD = $options[3] ?? '';

                fputcsv($file, [
                    $index + 1,
                    $question->question,
                    $question->type,
                    $optionA,
                    $optionB,
                    $optionC,
                    $optionD,
                    $question->correct_answer,
                    $question->score,
                    $question->order
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}