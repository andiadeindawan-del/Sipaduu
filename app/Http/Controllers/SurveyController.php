<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\Training;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    public function index(Request $request)
    {
        $query = Survey::with('training');
        if ($request->filled('training_id')) {
            $query->where('training_id', $request->training_id);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }
        $surveys = $query->orderBy('created_at', 'desc')->paginate(10);
        $trainings = Training::all();
        
        return view('admin.survey.index', compact('surveys', 'trainings'));
    }

    public function create()
    {
        $trainings = Training::all();
        return view('admin.survey.create', compact('trainings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'training_id' => 'required|exists:trainings,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:draft,published,closed',
        ]);

        Survey::create($request->all());

        return redirect()->route('admin.survey.index')->with('success', 'Survey berhasil ditambahkan.');
    }

    public function show(Survey $survey)
    {
        $survey->load(['training', 'questions', 'responses.user']);
        return view('admin.survey.show', compact('survey'));
    }

    public function edit(Survey $survey)
    {
        $trainings = Training::all();
        return view('admin.survey.edit', compact('survey', 'trainings'));
    }

    public function update(Request $request, Survey $survey)
    {
        $request->validate([
            'training_id' => 'required|exists:trainings,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:draft,published,closed',
        ]);

        $survey->update($request->all());

        return redirect()->route('admin.survey.index')->with('success', 'Survey berhasil diperbarui.');
    }

    public function destroy(Survey $survey)
    {
        $survey->delete();
        return redirect()->route('admin.survey.index')->with('success', 'Survey berhasil dihapus.');
    }
}
