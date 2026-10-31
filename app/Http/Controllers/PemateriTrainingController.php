<?php

namespace App\Http\Controllers;

use App\Models\Training;
use App\Models\Kategori;
use App\Models\Materi;
use App\Models\Quiz;
use App\Models\Survey;
use App\Models\Dokumentasi;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PemateriTrainingController extends Controller
{
    /**
     * Display a listing of trainings available for pemateri to select and manage.
     */
    public function index(Request $request): View
    {
        $userId = auth()->id();

        $query = Training::with(['kategori', 'trainer'])
            ->where('trainer_id', $userId)
            ->where('status', '!=', 'dibatalkan')
            ->withCount(['materis', 'quizzes', 'surveys', 'dokumentasis']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%$search%")
                  ->orWhere('deskripsi', 'like', "%$search%")
                  ->orWhere('lokasi', 'like', "%$search%");
            });
        }

        // Filter by kategori
        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        // Filter by tipe
        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }

        // Show published, berjalan, or all active trainings for this pemateri
        $trainings = $query->orderBy('tanggal_mulai', 'desc')->paginate(12)->withQueryString();

        $totalTrainings = Training::where('trainer_id', $userId)->count();
        $publishedTrainings = Training::where('trainer_id', $userId)->whereIn('status', ['published', 'berjalan'])->count();
        $kategoris = Kategori::orderBy('nama')->get();

        return view('pemateri.trainings.index', compact(
            'trainings',
            'totalTrainings',
            'publishedTrainings',
            'kategoris'
        ));
    }

    /**
     * Display the specified training details with its materi and quiz for pemateri.
     */
    public function show(Training $training): View
    {
        if ($training->trainer_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk pelatihan ini karena bukan pemateri yang ditugaskan.');
        }

        $training->load(['kategori', 'trainer']);
        
        $materis = Materi::where('training_id', $training->id)
            ->with('kategori')
            ->orderBy('order', 'asc')
            ->get();

        $quizzes = Quiz::where('training_id', $training->id)
            ->withCount('questions')
            ->orderBy('order', 'asc')
            ->get();

        $surveys = Survey::where('training_id', $training->id)
            ->withCount(['questions', 'responses'])
            ->orderBy('created_at', 'desc')
            ->get();

        $dokumentasis = Dokumentasi::where('training_id', $training->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pemateri.trainings.show', compact('training', 'materis', 'quizzes', 'surveys', 'dokumentasis'));
    }
}
