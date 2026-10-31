<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\Training;
use App\Models\SurveyResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PemateriSurveyController extends Controller
{
    /**
     * Display a listing of surveys for pemateri.
     */
    public function index(Request $request): View
    {
        $userId = auth()->id();
        $myTrainingIds = Training::where('trainer_id', $userId)->pluck('id');

        $query = Survey::whereIn('training_id', $myTrainingIds)
            ->with(['training', 'questions'])
            ->withCount(['questions', 'responses']);

        // Filter training
        if ($request->filled('training_id')) {
            $query->where('training_id', $request->training_id);
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        $surveys = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        // Stats
        $totalSurvey = Survey::whereIn('training_id', $myTrainingIds)->count();
        $publishedSurvey = Survey::whereIn('training_id', $myTrainingIds)->where('status', 'published')->count();
        $draftSurvey = Survey::whereIn('training_id', $myTrainingIds)->where('status', 'draft')->count();
        $closedSurvey = Survey::whereIn('training_id', $myTrainingIds)->where('status', 'closed')->count();
        $totalResponses = SurveyResponse::whereHas('survey', function ($q) use ($myTrainingIds) {
            $q->whereIn('training_id', $myTrainingIds);
        })->count();

        $trainings = Training::where('trainer_id', $userId)
            ->where('status', '!=', 'dibatalkan')
            ->orderBy('judul')
            ->get();

        return view('pemateri.survey.index', compact(
            'surveys',
            'trainings',
            'totalSurvey',
            'publishedSurvey',
            'draftSurvey',
            'closedSurvey',
            'totalResponses'
        ));
    }

    /**
     * Show the form for creating a new survey.
     */
    public function create(Request $request): View
    {
        $userId = auth()->id();
        $trainings = Training::where('trainer_id', $userId)
            ->where('status', '!=', 'dibatalkan')
            ->orderBy('judul')
            ->get();

        $selectedTrainingId = $request->get('training_id');

        return view('pemateri.survey.create', compact('trainings', 'selectedTrainingId'));
    }

    /**
     * Store a newly created survey in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'training_id' => 'required|exists:trainings,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:draft,published,closed',
        ]);

        $training = Training::findOrFail($validated['training_id']);
        if ($training->trainer_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk menambahkan survey pada pelatihan ini.');
        }

        $survey = Survey::create($validated);

        return redirect()->route('pemateri.survey.show', $survey)
            ->with('success', '✅ Survey berhasil dibuat. Silakan tambahkan butir pertanyaan survey.');
    }

    /**
     * Display the specified survey.
     */
    public function show(Survey $survey): View
    {
        $this->authorizeSurvey($survey);

        $survey->load(['training', 'questions', 'responses.user']);

        return view('pemateri.survey.show', compact('survey'));
    }

    /**
     * Show the form for editing the specified survey.
     */
    public function edit(Survey $survey): View
    {
        $this->authorizeSurvey($survey);

        $userId = auth()->id();
        $trainings = Training::where('trainer_id', $userId)
            ->where('status', '!=', 'dibatalkan')
            ->orderBy('judul')
            ->get();

        return view('pemateri.survey.edit', compact('survey', 'trainings'));
    }

    /**
     * Update the specified survey in storage.
     */
    public function update(Request $request, Survey $survey): RedirectResponse
    {
        $this->authorizeSurvey($survey);

        $validated = $request->validate([
            'training_id' => 'required|exists:trainings,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:draft,published,closed',
        ]);

        $training = Training::findOrFail($validated['training_id']);
        if ($training->trainer_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk pelatihan ini.');
        }

        $survey->update($validated);

        return redirect()->route('pemateri.survey.show', $survey)
            ->with('success', '✅ Survey berhasil diperbarui.');
    }

    /**
     * Remove the specified survey from storage.
     */
    public function destroy(Survey $survey): RedirectResponse
    {
        $this->authorizeSurvey($survey);

        $survey->delete();

        return redirect()->route('pemateri.survey.index')
            ->with('success', '✅ Survey berhasil dihapus.');
    }

    /**
     * Authorize that the survey belongs to a training assigned to the pemateri.
     */
    private function authorizeSurvey(Survey $survey): void
    {
        if (!$survey->training || $survey->training->trainer_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk survey ini.');
        }
    }
}
