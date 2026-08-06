<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\SurveyQuestion;
use Illuminate\Http\Request;

class SurveyQuestionController extends Controller
{
    public function create(Survey $survey)
    {
        return view('admin.survey.questions.create', compact('survey'));
    }

    public function store(Request $request, Survey $survey)
    {
        $request->validate([
            'pertanyaan' => 'required|string',
            'tipe' => 'required|in:rating_5,text,boolean',
            'order' => 'required|integer',
        ]);

        $survey->questions()->create($request->all());

        return redirect()->route('admin.survey.show', $survey->id)->with('success', 'Pertanyaan berhasil ditambahkan.');
    }

    public function edit(Survey $survey, $questionId)
    {
        $question = SurveyQuestion::findOrFail($questionId);
        return view('admin.survey.questions.edit', compact('survey', 'question'));
    }

    public function update(Request $request, Survey $survey, $questionId)
    {
        $question = SurveyQuestion::findOrFail($questionId);
        $request->validate([
            'pertanyaan' => 'required|string',
            'tipe' => 'required|in:rating_5,text,boolean',
            'order' => 'required|integer',
        ]);

        $question->update($request->all());

        return redirect()->route('admin.survey.show', $survey->id)->with('success', 'Pertanyaan berhasil diperbarui.');
    }

    public function destroy(Survey $survey, $questionId)
    {
        $question = SurveyQuestion::findOrFail($questionId);
        $question->delete();
        return redirect()->route('admin.survey.show', $survey->id)->with('success', 'Pertanyaan berhasil dihapus.');
    }
}
