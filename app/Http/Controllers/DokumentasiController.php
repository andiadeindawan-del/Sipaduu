<?php

namespace App\Http\Controllers;

use App\Models\Dokumentasi;
use App\Models\Training;
use Illuminate\Http\Request;

class DokumentasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Dokumentasi::with('training');
        
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
        
        $dokumentasis = $query->orderBy('created_at', 'desc')->paginate(10);
        $trainings = Training::all();
        
        return view('admin.dokumentasi.index', compact('dokumentasis', 'trainings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $trainings = Training::all();
        return view('admin.dokumentasi.create', compact('trainings'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'training_id' => 'required|exists:trainings,id',
            'judul' => 'required|string|max:255',
            'link' => 'required|url|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        Dokumentasi::create($request->all());

        return redirect()->route('admin.dokumentasi.index')->with('success', 'Dokumentasi berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Dokumentasi $dokumentasi)
    {
        $dokumentasi->load('training');
        return view('admin.dokumentasi.show', compact('dokumentasi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Dokumentasi $dokumentasi)
    {
        $trainings = Training::all();
        return view('admin.dokumentasi.edit', compact('dokumentasi', 'trainings'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Dokumentasi $dokumentasi)
    {
        $request->validate([
            'training_id' => 'required|exists:trainings,id',
            'judul' => 'required|string|max:255',
            'link' => 'required|url|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $dokumentasi->update($request->all());

        return redirect()->route('admin.dokumentasi.index')->with('success', 'Dokumentasi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dokumentasi $dokumentasi)
    {
        $dokumentasi->delete();
        return redirect()->route('admin.dokumentasi.index')->with('success', 'Dokumentasi berhasil dihapus.');
    }
}
