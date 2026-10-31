<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\SurveyQuestion;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class PemateriSurveyQuestionController extends Controller
{
    /**
     * Authorize that the survey belongs to this pemateri.
     */
    private function authorizeSurvey($surveyId): Survey
    {
        $survey = Survey::with('training')->findOrFail($surveyId);

        if (!$survey->training || $survey->training->trainer_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk pertanyaan survey ini.');
        }

        return $survey;
    }

    /**
     * Display a listing of questions for the survey.
     */
    public function index($surveyId): View
    {
        $survey = $this->authorizeSurvey($surveyId);
        $survey->load('questions');

        return view('pemateri.survey.questions.index', compact('survey'));
    }

    /**
     * Show the form for creating a new question.
     */
    public function create($surveyId): View
    {
        $survey = $this->authorizeSurvey($surveyId);

        return view('pemateri.survey.questions.create', compact('survey'));
    }

    /**
     * Store a newly created question in storage.
     */
    public function store(Request $request, $surveyId): RedirectResponse
    {
        $survey = $this->authorizeSurvey($surveyId);

        $validated = $request->validate([
            'pertanyaan' => 'required|string|max:1000',
            'tipe' => 'required|in:rating_5,text,boolean',
            'order' => 'nullable|integer|min:0',
        ]);

        $maxOrder = SurveyQuestion::where('survey_id', $surveyId)->max('order') ?? 0;

        $survey->questions()->create([
            'pertanyaan' => $validated['pertanyaan'],
            'tipe' => $validated['tipe'],
            'order' => $validated['order'] ?? ($maxOrder + 1),
        ]);

        return redirect()->route('pemateri.survey.show', $surveyId)
            ->with('success', '✅ Pertanyaan survey berhasil ditambahkan.');
    }

    /**
     * Display the specified question.
     */
    public function show($surveyId, $questionId): View
    {
        $survey = $this->authorizeSurvey($surveyId);
        $question = SurveyQuestion::where('survey_id', $surveyId)->findOrFail($questionId);

        return view('pemateri.survey.questions.show', compact('survey', 'question'));
    }

    /**
     * Show the form for editing the specified question.
     */
    public function edit($surveyId, $questionId): View
    {
        $survey = $this->authorizeSurvey($surveyId);
        $question = SurveyQuestion::where('survey_id', $surveyId)->findOrFail($questionId);

        return view('pemateri.survey.questions.edit', compact('survey', 'question'));
    }

    /**
     * Update the specified question in storage.
     */
    public function update(Request $request, $surveyId, $questionId): RedirectResponse
    {
        $survey = $this->authorizeSurvey($surveyId);
        $question = SurveyQuestion::where('survey_id', $surveyId)->findOrFail($questionId);

        $validated = $request->validate([
            'pertanyaan' => 'required|string|max:1000',
            'tipe' => 'required|in:rating_5,text,boolean',
            'order' => 'nullable|integer|min:0',
        ]);

        $question->update([
            'pertanyaan' => $validated['pertanyaan'],
            'tipe' => $validated['tipe'],
            'order' => $validated['order'] ?? $question->order,
        ]);

        return redirect()->route('pemateri.survey.show', $surveyId)
            ->with('success', '✅ Pertanyaan survey berhasil diperbarui.');
    }

    /**
     * Remove the specified question from storage.
     */
    public function destroy($surveyId, $questionId): RedirectResponse
    {
        $survey = $this->authorizeSurvey($surveyId);
        $question = SurveyQuestion::where('survey_id', $surveyId)->findOrFail($questionId);

        $question->delete();

        // Reorder questions
        $this->reorderQuestions($surveyId);

        return redirect()->route('pemateri.survey.show', $surveyId)
            ->with('success', '✅ Pertanyaan survey berhasil dihapus.');
    }

    /**
     * Reorder questions after delete.
     */
    private function reorderQuestions($surveyId): void
    {
        $questions = SurveyQuestion::where('survey_id', $surveyId)
            ->orderBy('order', 'asc')
            ->get();

        foreach ($questions as $index => $q) {
            $q->update(['order' => $index + 1]);
        }
    }
}
