<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use App\Models\Training;
use App\Models\Kategori;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PengumumanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Pengumuman::with(['training', 'kategori', 'creator']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%$search%")
                  ->orWhere('deskripsi', 'like', "%$search%")
                  ->orWhere('konten', 'like', "%$search%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('tanggal', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('tanggal', '<=', $request->date_to);
        }

        // Filter by training
        if ($request->filled('training_id')) {
            $query->where('training_id', $request->training_id);
        }

        $pengumuman = $query->orderBy('is_pinned', 'desc')
                           ->orderBy('tanggal', 'desc')
                           ->paginate(10)
                           ->withQueryString();

        // Statistics
        $totalPengumuman = Pengumuman::count();
        $publishedCount = Pengumuman::where('status', 'published')->count();
        $draftCount = Pengumuman::where('status', 'draft')->count();
        $archivedCount = Pengumuman::where('status', 'archived')->count();

        // For filter dropdown
        $trainings = Training::where('status', 'published')->orderBy('judul')->get();
        $kategoris = Kategori::orderBy('nama')->get();

        return view('admin.pengumuman.index', compact(
            'pengumuman',
            'totalPengumuman',
            'publishedCount',
            'draftCount',
            'archivedCount',
            'trainings',
            'kategoris'
        ));
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
            'deskripsi' => 'nullable|string',
            'konten' => 'nullable|string',
            'tanggal' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal',
            'status' => 'required|in:draft,published,archived',
            'is_pinned' => 'nullable|boolean',
        ]);

        // Set default values
        $validated['created_by'] = Auth::id();
        $validated['is_pinned'] = $request->has('is_pinned');

        // Jika status draft, set tanggal sekarang
        if ($validated['status'] === 'draft' && empty($validated['tanggal'])) {
            $validated['tanggal'] = now();
        }

        Pengumuman::create($validated);

        return redirect()->route('admin.pengumuman.index')
                        ->with('success', '✅ Pengumuman berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pengumuman $pengumuman)
    {
        $pengumuman->load(['training', 'kategori', 'creator']);
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
            'deskripsi' => 'nullable|string',
            'konten' => 'nullable|string',
            'tanggal' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal',
            'status' => 'required|in:draft,published,archived',
            'is_pinned' => 'nullable|boolean',
        ]);

        $validated['is_pinned'] = $request->has('is_pinned');

        $pengumuman->update($validated);

        return redirect()->route('admin.pengumuman.index')
                        ->with('success', '✅ Pengumuman berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pengumuman $pengumuman)
    {
        $pengumuman->delete();

        return redirect()->route('admin.pengumuman.index')
                        ->with('success', '✅ Pengumuman berhasil dihapus!');
    }

    // ============================================================
    // ADDITIONAL METHODS
    // ============================================================

    /**
     * Export pengumuman to CSV.
     */
    public function export(Request $request)
    {
        $query = Pengumuman::with(['training', 'kategori']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->where('tanggal', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('tanggal', '<=', $request->date_to);
        }

        $pengumuman = $query->orderBy('tanggal', 'desc')->get();

        if ($pengumuman->isEmpty()) {
            return redirect()->route('admin.pengumuman.index')
                ->with('warning', '⚠️ Tidak ada data pengumuman untuk diexport.');
        }

        $filename = 'pengumuman_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($pengumuman) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'No',
                'Judul',
                'Kategori',
                'Pelatihan',
                'Tanggal',
                'Tanggal Selesai',
                'Status',
                'Dibuat Oleh'
            ]);

            foreach ($pengumuman as $index => $item) {
                fputcsv($handle, [
                    $index + 1,
                    $item->judul,
                    $item->kategori->nama ?? '-',
                    $item->training->judul ?? '-',
                    $item->tanggal ? $item->tanggal->format('d/m/Y') : '-',
                    $item->tanggal_selesai ? $item->tanggal_selesai->format('d/m/Y') : '-',
                    $item->status,
                    $item->creator->name ?? $item->creator->nama ?? '-'
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Bulk delete pengumuman.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:pengumuman,id',
        ]);

        $count = Pengumuman::whereIn('id', $request->ids)->delete();

        return redirect()->route('admin.pengumuman.index')
            ->with('success', '✅ ' . $count . ' pengumuman berhasil dihapus!');
    }

    /**
     * Toggle pin status.
     */
    public function togglePin($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        $pengumuman->is_pinned = !$pengumuman->is_pinned;
        $pengumuman->save();

        $status = $pengumuman->is_pinned ? 'disematkan' : 'dilepas dari sematan';

        return redirect()->route('admin.pengumuman.index')
            ->with('success', '✅ Pengumuman berhasil ' . $status . '!');
    }

    /**
     * Publish pengumuman.
     */
    public function publish($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        $pengumuman->status = 'published';
        $pengumuman->tanggal = $pengumuman->tanggal ?? now();
        $pengumuman->save();

        return redirect()->route('admin.pengumuman.index')
            ->with('success', '✅ Pengumuman berhasil dipublikasikan!');
    }

    /**
     * Archive pengumuman.
     */
    public function archive($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        $pengumuman->status = 'archived';
        $pengumuman->save();

        return redirect()->route('admin.pengumuman.index')
            ->with('success', '✅ Pengumuman berhasil diarsipkan!');
    }

    /**
     * Get pengumuman by date range (AJAX).
     */
    public function getByDateRange(Request $request)
    {
        $request->validate([
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',
        ]);

        $pengumuman = Pengumuman::with(['training', 'kategori'])
            ->whereBetween('tanggal', [$request->start, $request->end])
            ->where('status', 'published')
            ->orderBy('tanggal', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $pengumuman
        ]);
    }

    /**
     * Get latest pengumuman (AJAX).
     */
    public function getLatest($limit = 5)
    {
        $pengumuman = Pengumuman::with(['training', 'kategori'])
            ->where('status', 'published')
            ->where(function($q) {
                $q->whereNull('tanggal_selesai')
                  ->orWhere('tanggal_selesai', '>=', now());
            })
            ->orderBy('is_pinned', 'desc')
            ->orderBy('tanggal', 'desc')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $pengumuman
        ]);
    }

    /**
     * Get pengumuman by training (AJAX).
     */
    public function getByTraining($trainingId)
    {
        $pengumuman = Pengumuman::with(['kategori'])
            ->where('training_id', $trainingId)
            ->where('status', 'published')
            ->where(function($q) {
                $q->whereNull('tanggal_selesai')
                  ->orWhere('tanggal_selesai', '>=', now());
            })
            ->orderBy('is_pinned', 'desc')
            ->orderBy('tanggal', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $pengumuman
        ]);
    }

    /**
     * Get pengumuman for dashboard.
     */
    public function dashboard()
    {
        $latestPengumuman = Pengumuman::with(['training', 'kategori'])
            ->where('status', 'published')
            ->where(function($q) {
                $q->whereNull('tanggal_selesai')
                  ->orWhere('tanggal_selesai', '>=', now());
            })
            ->orderBy('is_pinned', 'desc')
            ->orderBy('tanggal', 'desc')
            ->limit(5)
            ->get();

        $totalPublished = Pengumuman::where('status', 'published')->count();
        $totalDraft = Pengumuman::where('status', 'draft')->count();
        $totalArchived = Pengumuman::where('status', 'archived')->count();

        return response()->json([
            'success' => true,
            'data' => [
                'latest' => $latestPengumuman,
                'stats' => [
                    'published' => $totalPublished,
                    'draft' => $totalDraft,
                    'archived' => $totalArchived,
                ]
            ]
        ]);
    }

    /**
     * Get pengumuman statistics.
     */
    public function statistics()
    {
        $stats = [
            'total' => Pengumuman::count(),
            'published' => Pengumuman::where('status', 'published')->count(),
            'draft' => Pengumuman::where('status', 'draft')->count(),
            'archived' => Pengumuman::where('status', 'archived')->count(),
            'pinned' => Pengumuman::where('is_pinned', true)->count(),
            'active' => Pengumuman::where('status', 'published')
                ->where(function($q) {
                    $q->whereNull('tanggal_selesai')
                      ->orWhere('tanggal_selesai', '>=', now());
                })->count(),
        ];

        // Per bulan
        $monthly = Pengumuman::select(
                DB::raw('YEAR(tanggal) as year'),
                DB::raw('MONTH(tanggal) as month'),
                DB::raw('COUNT(*) as total')
            )
            ->where('status', 'published')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'stats' => $stats,
                'monthly' => $monthly
            ]
        ]);
    }
}