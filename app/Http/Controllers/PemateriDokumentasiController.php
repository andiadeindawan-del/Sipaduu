<?php

namespace App\Http\Controllers;

use App\Models\Dokumentasi;
use App\Models\Training;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PemateriDokumentasiController extends Controller
{
    /**
     * Display a listing of dokumentasi for pemateri.
     */
    public function index(Request $request): View
    {
        $userId = auth()->id();
        $myTrainingIds = Training::where('trainer_id', $userId)->pluck('id');

        $query = Dokumentasi::whereIn('training_id', $myTrainingIds)->with('training');

        if ($request->filled('training_id')) {
            $query->where('training_id', $request->training_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        $dokumentasis = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        $totalDokumentasi = Dokumentasi::whereIn('training_id', $myTrainingIds)->count();
        $totalTrainings = Training::where('trainer_id', $userId)->count();

        $trainings = Training::where('trainer_id', $userId)
            ->where('status', '!=', 'dibatalkan')
            ->orderBy('judul')
            ->get();

        return view('pemateri.dokumentasi.index', compact(
            'dokumentasis',
            'trainings',
            'totalDokumentasi',
            'totalTrainings'
        ));
    }

    /**
     * Show the form for creating a new dokumentasi.
     */
    public function create(Request $request): View
    {
        $userId = auth()->id();
        $trainings = Training::where('trainer_id', $userId)
            ->where('status', '!=', 'dibatalkan')
            ->orderBy('judul')
            ->get();

        $selectedTrainingId = $request->get('training_id');

        return view('pemateri.dokumentasi.create', compact('trainings', 'selectedTrainingId'));
    }

    /**
     * Store a newly created dokumentasi in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'training_id' => 'required|exists:trainings,id',
            'judul' => 'required|string|max:255',
            'link' => 'required|url|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $training = Training::findOrFail($validated['training_id']);
        if ($training->trainer_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk menambahkan dokumentasi pada pelatihan ini.');
        }

        Dokumentasi::create($validated);

        return redirect()->route('pemateri.dokumentasi.index')
            ->with('success', '✅ Dokumentasi pelatihan berhasil ditambahkan.');
    }

    /**
     * Display the specified dokumentasi.
     */
    public function show(Dokumentasi $dokumentasi): View
    {
        $this->authorizeDokumentasi($dokumentasi);
        $dokumentasi->load('training');

        return view('pemateri.dokumentasi.show', compact('dokumentasi'));
    }

    /**
     * Show the form for editing the specified dokumentasi.
     */
    public function edit(Dokumentasi $dokumentasi): View
    {
        $this->authorizeDokumentasi($dokumentasi);

        $userId = auth()->id();
        $trainings = Training::where('trainer_id', $userId)
            ->where('status', '!=', 'dibatalkan')
            ->orderBy('judul')
            ->get();

        return view('pemateri.dokumentasi.edit', compact('dokumentasi', 'trainings'));
    }

    /**
     * Update the specified dokumentasi in storage.
     */
    public function update(Request $request, Dokumentasi $dokumentasi): RedirectResponse
    {
        $this->authorizeDokumentasi($dokumentasi);

        $validated = $request->validate([
            'training_id' => 'required|exists:trainings,id',
            'judul' => 'required|string|max:255',
            'link' => 'required|url|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $training = Training::findOrFail($validated['training_id']);
        if ($training->trainer_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk pelatihan ini.');
        }

        $dokumentasi->update($validated);

        return redirect()->route('pemateri.dokumentasi.index')
            ->with('success', '✅ Dokumentasi pelatihan berhasil diperbarui.');
    }

    /**
     * Remove the specified dokumentasi from storage.
     */
    public function destroy(Dokumentasi $dokumentasi): RedirectResponse
    {
        $this->authorizeDokumentasi($dokumentasi);

        $dokumentasi->delete();

        return redirect()->route('pemateri.dokumentasi.index')
            ->with('success', '✅ Dokumentasi pelatihan berhasil dihapus.');
    }

    /**
     * Authorize that the dokumentasi belongs to a training assigned to the pemateri.
     */
    private function authorizeDokumentasi(Dokumentasi $dokumentasi): void
    {
        if (!$dokumentasi->training || $dokumentasi->training->trainer_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk dokumentasi ini.');
        }
    }
}
