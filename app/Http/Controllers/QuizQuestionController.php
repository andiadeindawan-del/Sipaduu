<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\QuizQuestion;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class QuizQuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
/**
 * Display a listing of the resource.
 */
public function index(Request $request, Quiz $quiz = null)
{
    try {
        // Jika ada quiz_id di request, override
        if ($request->has('quiz_id')) {
            $quiz = Quiz::find($request->quiz_id);
        }

        // Jika tidak ada quiz, redirect
        if (!$quiz) {
            return redirect()->route('admin.quiz.index')
                            ->with('error', '❌ Quiz tidak ditemukan.');
        }

        // Query dasar
        $query = QuizQuestion::where('quiz_id', $quiz->id);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('question', 'like', "%$search%");
            });
        }

        // Filter type
        if ($request->filled('type')) {
            if ($request->type === 'multiple_choice') {
                $query->where('type', 'multiple_choice');
            } elseif ($request->type === 'essay') {
                $query->where('type', 'essay');
            }
        }

        // Debug
        Log::info('Questions query:', [
            'sql' => $query->toSql(),
            'bindings' => $query->getBindings(),
            'count' => $query->count()
        ]);

        // Paginate
        $questions = $query->orderBy('order', 'asc')->paginate(10);

        // Statistics
        $totalQuestions = QuizQuestion::where('quiz_id', $quiz->id)->count();
        $multipleChoiceCount = QuizQuestion::where('quiz_id', $quiz->id)
                                          ->where('type', 'multiple_choice')
                                          ->count();
        $essayCount = QuizQuestion::where('quiz_id', $quiz->id)
                                 ->where('type', 'essay')
                                 ->count();
        $totalScore = QuizQuestion::where('quiz_id', $quiz->id)->sum('score') ?? 0;

        // Debug
        Log::info('Statistics:', [
            'total' => $totalQuestions,
            'multiple' => $multipleChoiceCount,
            'essay' => $essayCount,
            'score' => $totalScore
        ]);

        return view('admin.quiz.question.index', compact(
            'questions',
            'quiz',
            'totalQuestions',
            'multipleChoiceCount',
            'essayCount',
            'totalScore'
        ));
        
    } catch (\Exception $e) {
        Log::error('ERROR IN INDEX:', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return back()->with('error', '❌ Gagal memuat data pertanyaan: ' . $e->getMessage());
    }
}
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, Quiz $quiz = null)
    {
        try {
            if (!$quiz && $request->has('quiz_id')) {
                $quiz = Quiz::find($request->quiz_id);
            }

            if (!$quiz) {
                return redirect()->route('admin.quiz.index')
                                ->with('error', '❌ Quiz tidak ditemukan.');
            }

            $quizzes = Quiz::orderBy('judul')->get();
            
            return view('admin.quiz.question.create', compact('quizzes', 'quiz'));
            
        } catch (\Exception $e) {
            Log::error('ERROR IN CREATE:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('admin.quiz.index')
                            ->with('error', '❌ Gagal memuat form tambah pertanyaan: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
 public function store(Request $request)
{
    try {
        $validated = $request->validate([
            'quiz_id' => 'required|exists:quizzes,id',
            'question' => 'required|string|max:5000',
            'type' => ['required', Rule::in(['multiple_choice', 'essay'])],
            'points' => 'nullable|integer|min:1|max:100',
            'options' => 'nullable|array',
            'options.*' => 'nullable|string|max:1000',
            'correct_answer' => 'nullable|string|max:255',
            'essay_answer_key' => 'nullable|string|max:5000',
            'order' => 'nullable|integer|min:0',
        ]);

        // PERBAIKAN: Gunakan 'score' untuk database
        $questionData = [
            'quiz_id' => $validated['quiz_id'],
            'question' => $validated['question'],
            'type' => $validated['type'],
            'score' => $validated['points'] ?? 1, // points dari form -> score di database
            'order' => $validated['order'] ?? 0,
            'essay_answer_key' => $validated['essay_answer_key'] ?? null,
        ];

        // Cek tipe
        if ($validated['type'] === 'multiple_choice') {
            // Filter options
            $filteredOptions = array_filter($validated['options'] ?? [], function($value) {
                return !empty(trim($value));
            });
            
            if (count($filteredOptions) < 2) {
                return back()->withErrors(['options' => 'Minimal 2 pilihan jawaban untuk soal pilihan ganda.'])
                             ->withInput();
            }
            
            $questionData['options'] = array_values($filteredOptions);
            $questionData['correct_answer'] = $validated['correct_answer'] ?? 'A';
        } else {
            // Essay
            $questionData['options'] = null;
            $questionData['correct_answer'] = null;
        }

        // Set order jika tidak diisi
        if (empty($questionData['order']) || $questionData['order'] == 0) {
            $maxOrder = QuizQuestion::where('quiz_id', $validated['quiz_id'])->max('order') ?? 0;
            $questionData['order'] = $maxOrder + 1;
        }

        // Debug: Log data sebelum create
        Log::info('Creating question with data:', $questionData);

        $question = QuizQuestion::create($questionData);

        // Debug: Log setelah create
        Log::info('Question created:', ['id' => $question->id, 'data' => $question->toArray()]);

        return redirect()->route('admin.quiz.questions.index', $validated['quiz_id'])
                        ->with('success', '✅ Pertanyaan berhasil ditambahkan.');
                        
    } catch (\Illuminate\Validation\ValidationException $e) {
        return back()->withErrors($e->errors())->withInput();
        
    } catch (\Exception $e) {
        Log::error('ERROR STORING QUESTION:', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'input' => $request->all()
        ]);
        
        return back()->with('error', '❌ Gagal menambahkan pertanyaan: ' . $e->getMessage())
                     ->withInput();
    }
}

    /**
     * Display the specified resource.
     */
    public function show($quizId, $questionId)
    {
        try {
            $question = QuizQuestion::with('quiz')->findOrFail($questionId);
            $quiz = Quiz::findOrFail($quizId);
            
            if ($question->quiz_id !== $quiz->id) {
                abort(404, 'Pertanyaan tidak ditemukan dalam quiz ini.');
            }
            
            return view('admin.quiz.question.show', compact('question', 'quiz'));
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('admin.quiz.questions.index', $quizId)
                            ->with('error', '❌ Pertanyaan tidak ditemukan.');
                            
        } catch (\Exception $e) {
            Log::error('ERROR IN SHOW:', [
                'quiz_id' => $quizId,
                'question_id' => $questionId,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('admin.quiz.questions.index', $quizId)
                            ->with('error', '❌ Gagal menampilkan detail pertanyaan: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($quizId, $questionId)
    {
        try {
            $question = QuizQuestion::findOrFail($questionId);
            $quiz = Quiz::findOrFail($quizId);
            
            if ($question->quiz_id !== $quiz->id) {
                abort(404, 'Pertanyaan tidak ditemukan dalam quiz ini.');
            }
            
            if ($question->options && !is_array($question->options)) {
                $question->options = json_decode($question->options, true) ?? [];
            }
            
            $quizzes = Quiz::orderBy('judul')->get();
            return view('admin.quiz.question.edit', compact('question', 'quizzes', 'quiz'));
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('admin.quiz.questions.index', $quizId)
                            ->with('error', '❌ Pertanyaan tidak ditemukan.');
                            
        } catch (\Exception $e) {
            Log::error('ERROR IN EDIT:', [
                'quiz_id' => $quizId,
                'question_id' => $questionId,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('admin.quiz.questions.index', $quizId)
                            ->with('error', '❌ Gagal memuat form edit pertanyaan: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $quizId, $questionId)
    {
        try {
            $question = QuizQuestion::findOrFail($questionId);
            $quiz = Quiz::findOrFail($quizId);
            
            if ($question->quiz_id !== $quiz->id) {
                abort(404, 'Pertanyaan tidak ditemukan dalam quiz ini.');
            }

            $validated = $request->validate([
                'question' => 'required|string|max:5000',
                'type' => ['required', Rule::in(['multiple_choice', 'essay', 'pilihan', 'pilihan_ganda'])],
                'points' => 'nullable|integer|min:1|max:100',
                'options' => 'nullable|array',
                'options.*' => 'nullable|string|max:1000',
                'correct_answer' => 'nullable|string|max:255',
                'essay_answer_key' => 'nullable|string|max:5000',
                'order' => 'nullable|integer|min:0',
            ]);

            // PERBAIKAN: Konversi points ke score
            $validated['score'] = $validated['points'] ?? $question->score ?? 1;
            unset($validated['points']);

            if (in_array($validated['type'], ['multiple_choice', 'pilihan', 'pilihan_ganda'])) {
                $validated['type'] = 'multiple_choice';
                
                $filteredOptions = array_filter($validated['options'] ?? [], function($value) {
                    return !empty(trim($value));
                });
                
                $validated['options'] = array_values($filteredOptions);
                
                if (count($validated['options']) < 2) {
                    return back()->withErrors(['options' => 'Minimal 2 pilihan jawaban untuk soal pilihan ganda.'])
                                 ->withInput();
                }
                
                if (empty($validated['correct_answer'])) {
                    return back()->withErrors(['correct_answer' => 'Jawaban benar wajib dipilih untuk soal pilihan ganda.'])
                                 ->withInput();
                }
                
                $letters = ['A', 'B', 'C', 'D', 'E', 'F'];
                if (!in_array($validated['correct_answer'], array_slice($letters, 0, count($validated['options'])))) {
                    return back()->withErrors(['correct_answer' => 'Jawaban benar tidak valid.'])
                                 ->withInput();
                }
            } else {
                $validated['options'] = null;
                $validated['correct_answer'] = null;
                $validated['type'] = 'essay';
            }

            if (empty($validated['order'])) {
                $validated['order'] = $question->order ?? 0;
            }

            DB::beginTransaction();
            
            $question->update([
                'question' => $validated['question'],
                'type' => $validated['type'],
                'score' => (int) $validated['score'],
                'options' => $validated['options'],
                'correct_answer' => $validated['correct_answer'] ?? null,
                'essay_answer_key' => $validated['essay_answer_key'] ?? null,
                'order' => (int) $validated['order'],
            ]);
            
            DB::commit();

            Log::info('QUESTION UPDATED:', ['id' => $question->id, 'quiz_id' => $question->quiz_id]);

            return redirect()->route('admin.quiz.questions.index', $quizId)
                            ->with('success', '✅ Pertanyaan berhasil diperbarui.');
                            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('ERROR UPDATING QUESTION:', [
                'quiz_id' => $quizId,
                'question_id' => $questionId,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->all()
            ]);
            
            return back()->with('error', '❌ Gagal memperbarui pertanyaan: ' . $e->getMessage())
                         ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($quizId, $questionId)
    {
        try {
            $question = QuizQuestion::findOrFail($questionId);
            $quiz = Quiz::findOrFail($quizId);
            
            if ($question->quiz_id !== $quiz->id) {
                abort(404, 'Pertanyaan tidak ditemukan dalam quiz ini.');
            }

            $quizId = $question->quiz_id;
            $question->delete();

            Log::info('QUESTION DELETED:', ['id' => $questionId, 'quiz_id' => $quizId]);

            $this->reorderQuestions($quizId);

            return redirect()->route('admin.quiz.questions.index', $quizId)
                            ->with('success', '✅ Pertanyaan berhasil dihapus.');
                            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('admin.quiz.questions.index', $quizId)
                            ->with('error', '❌ Pertanyaan tidak ditemukan.');
                            
        } catch (\Exception $e) {
            Log::error('ERROR DELETING QUESTION:', [
                'quiz_id' => $quizId,
                'question_id' => $questionId,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('admin.quiz.questions.index', $quizId)
                            ->with('error', '❌ Gagal menghapus pertanyaan: ' . $e->getMessage());
        }
    }

    /**
     * Bulk delete questions.
     */
    public function bulkDelete(Request $request, $quizId)
    {
        try {
            $validated = $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'exists:quiz_questions,id',
            ]);

            $quiz = Quiz::findOrFail($quizId);
            
            $deleted = QuizQuestion::whereIn('id', $validated['ids'])
                                 ->where('quiz_id', $quizId)
                                 ->delete();

            if ($deleted > 0) {
                $this->reorderQuestions($quizId);
                
                Log::info('BULK DELETE:', [
                    'quiz_id' => $quizId,
                    'deleted_count' => $deleted,
                    'ids' => $validated['ids']
                ]);

                return redirect()->route('admin.quiz.questions.index', $quizId)
                                ->with('success', "✅ {$deleted} pertanyaan berhasil dihapus.");
            }

            return redirect()->route('admin.quiz.questions.index', $quizId)
                            ->with('warning', '⚠️ Tidak ada pertanyaan yang dihapus.');
            
        } catch (\Exception $e) {
            Log::error('ERROR IN BULK DELETE:', [
                'quiz_id' => $quizId,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('admin.quiz.questions.index', $quizId)
                            ->with('error', '❌ Gagal menghapus pertanyaan: ' . $e->getMessage());
        }
    }

    /**
     * Reorder questions.
     */
    public function reorder(Request $request, $quizId)
    {
        try {
            $validated = $request->validate([
                'orders' => 'required|array',
                'orders.*' => 'required|integer|min:0',
                'ids' => 'required|array',
                'ids.*' => 'exists:quiz_questions,id',
            ]);

            $quiz = Quiz::findOrFail($quizId);
            
            DB::beginTransaction();
            
            foreach ($validated['ids'] as $index => $id) {
                QuizQuestion::where('id', $id)
                           ->where('quiz_id', $quizId)
                           ->update(['order' => $validated['orders'][$index] ?? $index + 1]);
            }
            
            DB::commit();

            Log::info('QUESTIONS REORDERED:', ['quiz_id' => $quizId]);

            return response()->json([
                'success' => true,
                'message' => '✅ Urutan pertanyaan berhasil diperbarui.'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('ERROR REORDERING QUESTIONS:', [
                'quiz_id' => $quizId,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => '❌ Gagal memperbarui urutan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reorder questions after delete or bulk operations.
     */
    private function reorderQuestions($quizId)
    {
        try {
            $questions = QuizQuestion::where('quiz_id', $quizId)
                                    ->orderBy('order', 'asc')
                                    ->get();
            
            foreach ($questions as $index => $question) {
                $question->order = $index + 1;
                $question->save();
            }
            
        } catch (\Exception $e) {
            Log::error('ERROR REORDERING QUESTIONS:', [
                'quiz_id' => $quizId,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get questions by quiz (API).
     */
    public function getByQuiz($quizId)
    {
        try {
            $quiz = Quiz::findOrFail($quizId);
            $questions = QuizQuestion::where('quiz_id', $quizId)
                                    ->orderBy('order', 'asc')
                                    ->get();
            
            return response()->json([
                'success' => true,
                'data' => $questions
            ]);
            
        } catch (\Exception $e) {
            Log::error('ERROR GETTING QUESTIONS:', [
                'quiz_id' => $quizId,
                'message' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => '❌ Gagal mengambil data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Duplicate a question.
     */
    public function duplicate(Request $request, $quizId, $questionId)
    {
        try {
            $question = QuizQuestion::findOrFail($questionId);
            $quiz = Quiz::findOrFail($quizId);
            
            if ($question->quiz_id !== $quiz->id) {
                abort(404, 'Pertanyaan tidak ditemukan dalam quiz ini.');
            }

            $newQuestion = $question->replicate();
            $newQuestion->quiz_id = $quizId;
            $newQuestion->order = QuizQuestion::where('quiz_id', $quizId)->max('order') + 1;
            $newQuestion->question = $question->question . ' (copy)';
            $newQuestion->save();

            Log::info('QUESTION DUPLICATED:', [
                'original_id' => $questionId,
                'new_id' => $newQuestion->id,
                'quiz_id' => $quizId
            ]);

            return redirect()->route('admin.quiz.questions.index', $quizId)
                            ->with('success', '✅ Pertanyaan berhasil diduplikasi.');
                            
        } catch (\Exception $e) {
            Log::error('ERROR DUPLICATING:', [
                'quiz_id' => $quizId,
                'question_id' => $questionId,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('admin.quiz.questions.index', $quizId)
                            ->with('error', '❌ Gagal menduplikasi pertanyaan: ' . $e->getMessage());
        }
    }

    /**
     * Export questions to CSV.
     */
    public function export(Request $request, $quizId)
    {
        try {
            $quiz = Quiz::findOrFail($quizId);
            $questions = QuizQuestion::where('quiz_id', $quizId)
                                    ->orderBy('order', 'asc')
                                    ->get();

            if ($questions->isEmpty()) {
                return redirect()->route('admin.quiz.questions.index', $quizId)
                                ->with('warning', '⚠️ Tidak ada pertanyaan untuk diexport.');
            }

            $fileName = "questions_quiz_{$quizId}_" . date('Y-m-d_H-i-s') . ".csv";
            
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"$fileName\"",
            ];

            $columns = ['Order', 'Question', 'Type', 'Score', 'Options', 'Correct Answer'];

            $callback = function() use ($questions, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);

                foreach ($questions as $question) {
                    $row = [
                        $question->order,
                        $question->question,
                        $question->type_display ?? $question->type,
                        $question->score ?? 1,
                        is_array($question->options) ? implode('; ', $question->options) : '',
                        $question->correct_answer ?? '',
                    ];
                    fputcsv($file, $row);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
            
        } catch (\Exception $e) {
            Log::error('ERROR EXPORTING:', [
                'quiz_id' => $quizId,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('admin.quiz.questions.index', $quizId)
                            ->with('error', '❌ Gagal mengexport: ' . $e->getMessage());
        }
    }

    /**
     * Import questions from CSV.
     */
    public function import(Request $request, $quizId)
    {
        try {
            $validated = $request->validate([
                'file' => 'required|file|mimes:csv,txt|max:2048',
            ]);

            $quiz = Quiz::findOrFail($quizId);
            
            $file = $request->file('file');
            $path = $file->getRealPath();
            
            $data = array_map('str_getcsv', file($path));
            
            if (count($data) < 2) {
                return back()->with('error', '❌ File CSV kosong atau tidak valid.');
            }

            array_shift($data);
            $imported = 0;
            $errors = [];

            DB::beginTransaction();

            foreach ($data as $row) {
                if (count($row) < 4) {
                    $errors[] = 'Baris tidak lengkap: ' . implode(',', $row);
                    continue;
                }

                try {
                    $questionData = [
                        'quiz_id' => $quizId,
                        'order' => (int) ($row[0] ?? 0),
                        'question' => $row[1] ?? '',
                        'type' => $this->normalizeType($row[2] ?? 'multiple_choice'),
                        'score' => (int) ($row[3] ?? 1),
                        'options' => $this->parseOptions($row[4] ?? ''),
                        'correct_answer' => $row[5] ?? null,
                    ];

                    if (empty($questionData['question'])) {
                        $errors[] = 'Pertanyaan kosong: ' . implode(',', $row);
                        continue;
                    }

                    QuizQuestion::create($questionData);
                    $imported++;
                    
                } catch (\Exception $e) {
                    $errors[] = 'Error: ' . implode(',', $row) . ' - ' . $e->getMessage();
                }
            }

            DB::commit();

            $this->reorderQuestions($quizId);

            $message = "✅ Berhasil mengimport {$imported} pertanyaan.";
            if (!empty($errors)) {
                $message .= " Terdapat " . count($errors) . " error.";
            }

            return redirect()->route('admin.quiz.questions.index', $quizId)
                            ->with('success', $message)
                            ->with('import_errors', $errors);
                            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('ERROR IMPORTING:', [
                'quiz_id' => $quizId,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', '❌ Gagal mengimport: ' . $e->getMessage());
        }
    }

    /**
     * Normalize question type.
     */
    private function normalizeType($type)
    {
        $type = strtolower(trim($type));
        $map = [
            'pilihan ganda' => 'multiple_choice',
            'pilihan' => 'multiple_choice',
            'pilihan_ganda' => 'multiple_choice',
            'multiple choice' => 'multiple_choice',
            'multiple_choice' => 'multiple_choice',
            'essay' => 'essay',
            'esai' => 'essay',
            'benar salah' => 'true_false',
            'true false' => 'true_false',
            'true_false' => 'true_false',
        ];
        
        return $map[$type] ?? 'multiple_choice';
    }

    /**
     * Parse options from string to array.
     */
    private function parseOptions($optionsString)
    {
        if (empty($optionsString)) {
            return [];
        }

        $options = preg_split('/[;,]/', $optionsString);
        return array_values(array_filter(array_map('trim', $options)));
    }

    /**
     * Get statistics for a quiz.
     */
    public function statistics($quizId)
    {
        try {
            $quiz = Quiz::findOrFail($quizId);
            
            // PERBAIKAN: Gunakan 'score' bukan 'points'
            $stats = [
                'total_questions' => QuizQuestion::where('quiz_id', $quizId)->count(),
                'multiple_choice' => QuizQuestion::where('quiz_id', $quizId)
                                                ->whereIn('type', ['multiple_choice', 'pilihan', 'pilihan_ganda'])
                                                ->count(),
                'essay' => QuizQuestion::where('quiz_id', $quizId)->where('type', 'essay')->count(),
                'total_score' => QuizQuestion::where('quiz_id', $quizId)->sum('score') ?? 0,
                'avg_score' => QuizQuestion::where('quiz_id', $quizId)->avg('score') ?? 0,
                'min_score' => QuizQuestion::where('quiz_id', $quizId)->min('score') ?? 0,
                'max_score' => QuizQuestion::where('quiz_id', $quizId)->max('score') ?? 0,
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
            
        } catch (\Exception $e) {
            Log::error('ERROR GETTING STATISTICS:', [
                'quiz_id' => $quizId,
                'message' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => '❌ Gagal mengambil statistik: ' . $e->getMessage()
            ], 500);
        }
    }
}