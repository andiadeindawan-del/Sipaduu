<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use App\Models\Training;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PengumumanController extends Controller
{
    /**
     * Display a listing of the resource (Admin).
     */
    public function index(Request $request)
    {
        $query = Pengumuman::with(['training', 'creator']);

        // Filter by training
        if ($request->filled('training_id')) {
            $query->where('training_id', $request->training_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%$search%")
                  ->orWhere('konten', 'like', "%$search%");
            });
        }

        $pengumumans = $query->orderBy('created_at', 'desc')
                             ->paginate(15)
                             ->withQueryString();

        $trainings = Training::where('status', 'published')->orderBy('judul')->get();

        // Statistics
        $totalPengumuman = Pengumuman::count();
        $publishedPengumuman = Pengumuman::where('status', 'published')->count();
        $draftPengumuman = Pengumuman::where('status', 'draft')->count();

        return view('admin.pengumuman.index', compact(
            'pengumumans',
            'trainings',
            'totalPengumuman',
            'publishedPengumuman',
            'draftPengumuman'
        ));
    }

    /**
     * Display a listing of pengumuman for peserta.
     * PERBAIKAN: Gunakan training_registrations
     */
    public function pesertaIndex(Request $request)
    {
        $user = Auth::user();
        
        // PERBAIKAN: Gunakan registrations() dengan tabel training_registrations
        $trainingIds = Training::whereHas('registrations', function($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->whereIn('status', ['pending', 'registered', 'approved', 'completed']);
        })->pluck('id');

        $query = Pengumuman::with(['training', 'creator'])
            ->whereIn('training_id', $trainingIds)
            ->orWhere('training_id', null) // Pengumuman umum
            ->where('status', 'published'); // Hanya yang dipublikasikan

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%$search%")
                  ->orWhere('konten', 'like', "%$search%");
            });
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $pengumumans = $query->orderBy('created_at', 'desc')
                             ->paginate(15)
                             ->withQueryString();

        // Statistics
        $totalPengumuman = $query->count();

        return view('peserta.pengumuman.index', compact(
            'pengumumans',
            'totalPengumuman'
        ));
    }

    /**
     * Display the specified pengumuman for peserta.
     * PERBAIKAN: Gunakan training_registrations
     */
    public function pesertaShow($id)
    {
        $pengumuman = Pengumuman::with(['training', 'creator'])->findOrFail($id);
        
        // Check if user has access to this pengumuman
        $user = Auth::user();
        
        if ($pengumuman->training_id) {
            // PERBAIKAN: Gunakan registrations() dengan tabel training_registrations
            $trainingIds = Training::whereHas('registrations', function($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->whereIn('status', ['pending', 'registered', 'approved', 'completed']);
            })->pluck('id');

            if (!$trainingIds->contains($pengumuman->training_id)) {
                abort(403, 'Anda tidak memiliki akses ke pengumuman ini.');
            }
        }

        return view('peserta.pengumuman.show', compact('pengumuman'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $trainings = Training::where('status', 'published')->orderBy('judul')->get();
        $kategoris = Kategori::orderBy('nama')->get();
        return view('admin.pengumuman.create', compact('trainings', 'kategoris'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'training_id' => 'nullable|exists:trainings,id',
            'kategori_id' => 'nullable|exists:kategoris,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:1000',
            'konten' => 'required|string',
            'tanggal' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal',
            'target_audience' => 'required|in:all,peserta,trainer,admin',
            'status' => 'required|in:draft,published,archived',
            'is_pinned' => 'nullable|boolean',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);
        
        $validated['is_pinned'] = $request->has('is_pinned') ? 1 : 0;

        $validated['created_by'] = auth()->id();

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('pengumuman', 'public');
        }

        Pengumuman::create($validated);

        return redirect()->route('admin.pengumuman.index')
                        ->with('success', '✅ Pengumuman berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pengumuman $pengumuman)
    {
        $pengumuman->load(['training', 'creator']);
        return view('admin.pengumuman.show', compact('pengumuman'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pengumuman $pengumuman)
    {
        $trainings = Training::where('status', 'published')->orderBy('judul')->get();
        $kategoris = Kategori::orderBy('nama')->get();
        return view('admin.pengumuman.edit', compact('pengumuman', 'trainings', 'kategoris'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pengumuman $pengumuman)
    {
        $validated = $request->validate([
            'training_id' => 'nullable|exists:trainings,id',
            'kategori_id' => 'nullable|exists:kategoris,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:1000',
            'konten' => 'required|string',
            'tanggal' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal',
            'target_audience' => 'required|in:all,peserta,trainer,admin',
            'status' => 'required|in:draft,published,archived',
            'is_pinned' => 'nullable|boolean',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);
        
        $validated['is_pinned'] = $request->has('is_pinned') ? 1 : 0;

        if ($request->hasFile('gambar')) {
            if ($pengumuman->gambar) {
                Storage::disk('public')->delete($pengumuman->gambar);
            }
            $validated['gambar'] = $request->file('gambar')->store('pengumuman', 'public');
        }

        $pengumuman->update($validated);

        return redirect()->route('admin.pengumuman.index')
                        ->with('success', '✅ Pengumuman berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pengumuman $pengumuman)
    {
        if ($pengumuman->gambar) {
            Storage::disk('public')->delete($pengumuman->gambar);
        }

        $pengumuman->delete();

        return redirect()->route('admin.pengumuman.index')
                        ->with('success', '✅ Pengumuman berhasil dihapus.');
    }

    /**
     * Export pengumuman to CSV.
     */
    public function export(Request $request)
    {
        $query = Pengumuman::with(['training', 'creator']);

        if ($request->filled('training_id')) {
            $query->where('training_id', $request->training_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $pengumumans = $query->orderBy('created_at', 'desc')->get();

        if ($pengumumans->isEmpty()) {
            return redirect()->route('admin.pengumuman.index')
                ->with('warning', '⚠️ Tidak ada data pengumuman untuk diexport.');
        }

        $filename = 'pengumuman_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($pengumumans) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'No',
                'Judul',
                'Pelatihan',
                'Isi',
                'Status',
                'Tanggal Dibuat'
            ]);

            foreach ($pengumumans as $index => $pengumuman) {
                fputcsv($handle, [
                    $index + 1,
                    $pengumuman->judul,
                    $pengumuman->training->judul ?? 'Umum',
                    Str::limit($pengumuman->konten, 100),
                    $pengumuman->status,
                    $pengumuman->created_at ? $pengumuman->created_at->format('d/m/Y H:i') : '-'
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}