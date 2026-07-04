<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Kategori::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama', 'like', "%$search%")
                  ->orWhere('deskripsi', 'like', "%$search%");
        }

        $kategoris = $query->withCount(['materis', 'trainings'])
                          ->latest()
                          ->paginate(10)
                          ->withQueryString();

        // Statistics
        $totalKategoris = Kategori::count();
        $kategoriDenganMateri = Kategori::has('materis')->count();
        $kategoriDenganTraining = Kategori::has('trainings')->count();

        return view('admin.kategori.index', compact(
            'kategoris',
            'totalKategoris',
            'kategoriDenganMateri',
            'kategoriDenganTraining'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.kategori.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100|unique:kategoris,nama',
            'deskripsi' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'warna' => 'nullable|string|max:20',
        ]);

        Kategori::create($validated);

        return redirect()->route('admin.kategori.index')
                        ->with('success', 'Kategori berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kategori $kategori)
    {
        $kategori->loadCount(['materis', 'trainings']);
        $kategori->load(['materis' => function($query) {
            $query->latest()->limit(5);
        }, 'trainings' => function($query) {
            $query->latest()->limit(5);
        }]);
        
        return view('admin.kategori.show', compact('kategori'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kategori $kategori)
    {
        return view('admin.kategori.edit', compact('kategori'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kategori $kategori)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100', Rule::unique('kategoris', 'nama')->ignore($kategori->id)],
            'deskripsi' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'warna' => 'nullable|string|max:20',
        ]);

        $kategori->update($validated);

        return redirect()->route('admin.kategori.index')
                        ->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kategori $kategori)
    {
        // Check if kategori has related data
        $materiCount = $kategori->materis()->count();
        $trainingCount = $kategori->trainings()->count();
        
        if ($materiCount > 0 || $trainingCount > 0) {
            return redirect()->route('admin.kategori.index')
                            ->with('error', "Kategori tidak dapat dihapus karena masih memiliki {$materiCount} materi dan {$trainingCount} pelatihan.");
        }

        $kategori->delete();

        return redirect()->route('admin.kategori.index')
                        ->with('success', 'Kategori berhasil dihapus.');
    }

    /**
     * Get kategori data for API/Select2.
     */
    public function getData(Request $request)
    {
        $search = $request->get('q');
        $kategoris = Kategori::when($search, function ($query, $search) {
            return $query->where('nama', 'like', "%$search%");
        })->limit(10)->get(['id', 'nama as text']);

        return response()->json($kategoris);
    }

    /**
     * Bulk delete categories.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:kategoris,id'
        ]);

        $kategoris = Kategori::whereIn('id', $request->ids)->get();
        $deletedCount = 0;
        $errors = [];

        foreach ($kategoris as $kategori) {
            if ($kategori->materis()->count() > 0 || $kategori->trainings()->count() > 0) {
                $errors[] = "Kategori '{$kategori->nama}' tidak dapat dihapus karena memiliki data terkait.";
                continue;
            }
            $kategori->delete();
            $deletedCount++;
        }

        if ($deletedCount > 0 && empty($errors)) {
            return redirect()->route('admin.kategori.index')
                            ->with('success', "{$deletedCount} kategori berhasil dihapus.");
        }

        if ($deletedCount > 0 && !empty($errors)) {
            return redirect()->route('admin.kategori.index')
                            ->with('warning', "{$deletedCount} kategori berhasil dihapus, namun beberapa tidak bisa dihapus: " . implode('; ', $errors));
        }

        return redirect()->route('admin.kategori.index')
                        ->with('error', implode('<br>', $errors));
    }
}