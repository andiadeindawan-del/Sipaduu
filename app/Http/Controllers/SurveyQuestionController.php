<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\SurveyQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SurveyQuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($surveyId)
    {
        try {
            $survey = Survey::with('questions')->findOrFail($surveyId);
            
            return view('admin.survey.questions.index', compact('survey'));
            
        } catch (\Exception $e) {
            Log::error('ERROR IN SURVEY QUESTIONS INDEX:', [
                'survey_id' => $surveyId,
                'message' => $e->getMessage()
            ]);
            
            return redirect()->route('admin.survey.index')
                            ->with('error', '❌ Gagal memuat data pertanyaan: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($surveyId)
    {
        try {
            $survey = Survey::findOrFail($surveyId);
            
            return view('admin.survey.questions.create', compact('survey'));
            
        } catch (\Exception $e) {
            Log::error('ERROR IN SURVEY QUESTIONS CREATE:', [
                'survey_id' => $surveyId,
                'message' => $e->getMessage()
            ]);
            
            return redirect()->route('admin.survey.questions.index', $surveyId)
                            ->with('error', '❌ Gagal memuat form tambah pertanyaan: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $surveyId)
    {
        try {
            $validated = $request->validate([
                'pertanyaan' => 'required|string|max:1000',
                'tipe' => 'required|in:rating_5,text,boolean',
            ]);

            $survey = Survey::findOrFail($surveyId);
            
            // Hitung order otomatis
            $maxOrder = SurveyQuestion::where('survey_id', $surveyId)->max('order') ?? 0;
            
            $survey->questions()->create([
                'pertanyaan' => $validated['pertanyaan'],
                'tipe' => $validated['tipe'],
                'order' => $maxOrder + 1,
            ]);

            return redirect()->route('admin.survey.questions.index', $surveyId)
                            ->with('success', '✅ Pertanyaan berhasil ditambahkan.');
                            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
            
        } catch (\Exception $e) {
            Log::error('ERROR STORING SURVEY QUESTION:', [
                'survey_id' => $surveyId,
                'message' => $e->getMessage(),
                'input' => $request->all()
            ]);
            
            return back()->with('error', '❌ Gagal menambahkan pertanyaan: ' . $e->getMessage())
                         ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($surveyId, $questionId)
    {
        try {
            $survey = Survey::findOrFail($surveyId);
            $question = SurveyQuestion::findOrFail($questionId);
            
            return view('admin.survey.questions.show', compact('survey', 'question'));
            
        } catch (\Exception $e) {
            Log::error('ERROR IN SURVEY QUESTIONS SHOW:', [
                'survey_id' => $surveyId,
                'question_id' => $questionId,
                'message' => $e->getMessage()
            ]);
            
            return redirect()->route('admin.survey.questions.index', $surveyId)
                            ->with('error', '❌ Gagal menampilkan detail pertanyaan: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($surveyId, $questionId)
    {
        try {
            $survey = Survey::findOrFail($surveyId);
            $question = SurveyQuestion::findOrFail($questionId);
            
            return view('admin.survey.questions.edit', compact('survey', 'question'));
            
        } catch (\Exception $e) {
            Log::error('ERROR IN SURVEY QUESTIONS EDIT:', [
                'survey_id' => $surveyId,
                'question_id' => $questionId,
                'message' => $e->getMessage()
            ]);
            
            return redirect()->route('admin.survey.questions.index', $surveyId)
                            ->with('error', '❌ Gagal memuat form edit pertanyaan: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $surveyId, $questionId)
    {
        try {
            $validated = $request->validate([
                'pertanyaan' => 'required|string|max:1000',
                'tipe' => 'required|in:rating_5,text,boolean',
                'order' => 'nullable|integer|min:0',
            ]);

            $question = SurveyQuestion::findOrFail($questionId);
            
            $question->update([
                'pertanyaan' => $validated['pertanyaan'],
                'tipe' => $validated['tipe'],
                'order' => $validated['order'] ?? $question->order,
            ]);

            return redirect()->route('admin.survey.questions.index', $surveyId)
                            ->with('success', '✅ Pertanyaan berhasil diperbarui.');
                            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
            
        } catch (\Exception $e) {
            Log::error('ERROR UPDATING SURVEY QUESTION:', [
                'survey_id' => $surveyId,
                'question_id' => $questionId,
                'message' => $e->getMessage(),
                'input' => $request->all()
            ]);
            
            return back()->with('error', '❌ Gagal memperbarui pertanyaan: ' . $e->getMessage())
                         ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($surveyId, $questionId)
    {
        try {
            $question = SurveyQuestion::findOrFail($questionId);
            
            // Hapus question
            $question->delete();
            
            // Reorder questions
            $this->reorderQuestions($surveyId);

            return redirect()->route('admin.survey.questions.index', $surveyId)
                            ->with('success', '✅ Pertanyaan berhasil dihapus.');
                            
        } catch (\Exception $e) {
            Log::error('ERROR DELETING SURVEY QUESTION:', [
                'survey_id' => $surveyId,
                'question_id' => $questionId,
                'message' => $e->getMessage()
            ]);
            
            return redirect()->route('admin.survey.questions.index', $surveyId)
                            ->with('error', '❌ Gagal menghapus pertanyaan: ' . $e->getMessage());
        }
    }

    /**
     * Bulk delete questions.
     */
    public function bulkDelete(Request $request, $surveyId)
    {
        try {
            $validated = $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'exists:survey_questions,id',
            ]);

            $survey = Survey::findOrFail($surveyId);
            
            $deleted = SurveyQuestion::whereIn('id', $validated['ids'])
                                    ->where('survey_id', $surveyId)
                                    ->delete();

            if ($deleted > 0) {
                // Reorder setelah bulk delete
                $this->reorderQuestions($surveyId);
                
                Log::info('BULK DELETE SURVEY QUESTIONS:', [
                    'survey_id' => $surveyId,
                    'deleted_count' => $deleted,
                    'ids' => $validated['ids']
                ]);

                return redirect()->route('admin.survey.questions.index', $surveyId)
                                ->with('success', "✅ {$deleted} pertanyaan berhasil dihapus.");
            }

            return redirect()->route('admin.survey.questions.index', $surveyId)
                            ->with('warning', '⚠️ Tidak ada pertanyaan yang dihapus.');
            
        } catch (\Exception $e) {
            Log::error('ERROR IN BULK DELETE SURVEY QUESTIONS:', [
                'survey_id' => $surveyId,
                'message' => $e->getMessage()
            ]);
            
            return redirect()->route('admin.survey.questions.index', $surveyId)
                            ->with('error', '❌ Gagal menghapus pertanyaan: ' . $e->getMessage());
        }
    }

    /**
     * Reorder questions after delete.
     */
    private function reorderQuestions($surveyId)
    {
        try {
            $questions = SurveyQuestion::where('survey_id', $surveyId)
                                      ->orderBy('order', 'asc')
                                      ->get();
            
            foreach ($questions as $index => $question) {
                $question->order = $index + 1;
                $question->save();
            }
            
        } catch (\Exception $e) {
            Log::error('ERROR REORDERING SURVEY QUESTIONS:', [
                'survey_id' => $surveyId,
                'message' => $e->getMessage()
            ]);
        }
    }
}