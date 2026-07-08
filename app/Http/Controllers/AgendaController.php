<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\Training;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AgendaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Agenda::with('training');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%$search%")
                  ->orWhere('deskripsi', 'like', "%$search%")
                  ->orWhere('lokasi', 'like', "%$search%");
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

        $agendas = $query->orderBy('tanggal', 'desc')
                        ->orderBy('waktu_mulai', 'desc')
                        ->paginate(10)
                        ->withQueryString();

        // Statistics
        $totalAgenda = Agenda::count();
        $upcomingCount = Agenda::where('status', 'upcoming')->count();
        $ongoingCount = Agenda::where('status', 'ongoing')->count();
        $completedCount = Agenda::where('status', 'completed')->count();

        // For filter dropdown
        $trainings = Training::where('status', 'published')->orderBy('judul')->get();

        return view('admin.agenda.index', compact(
            'agendas',
            'totalAgenda',
            'upcomingCount',
            'ongoingCount',
            'completedCount',
            'trainings'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $trainings = Training::where('status', 'published')->orderBy('judul')->get();
        return view('admin.agenda.create', compact('trainings'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'training_id' => 'nullable|exists:trainings,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
            'lokasi' => 'nullable|string|max:255',
            'status' => 'required|in:upcoming,ongoing,completed,cancelled,draft,published,selesai',
        ]);

        // Jika status kosong, set default berdasarkan tanggal
        if (empty($validated['status']) || $validated['status'] === 'draft') {
            $tanggal = $validated['tanggal'];
            $now = now();
            
            if ($tanggal < $now->subDay()) {
                $validated['status'] = 'completed';
            } elseif ($tanggal <= $now && $tanggal >= $now->subDay()) {
                $validated['status'] = 'ongoing';
            } else {
                $validated['status'] = 'upcoming';
            }
        }

        Agenda::create($validated);

        return redirect()->route('admin.agenda.index')
                        ->with('success', '✅ Agenda berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Agenda $agenda)
    {
        $agenda->load('training');
        return view('admin.agenda.show', compact('agenda'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Agenda $agenda)
    {
        $trainings = Training::where('status', 'published')->orderBy('judul')->get();
        return view('admin.agenda.edit', compact('agenda', 'trainings'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Agenda $agenda)
    {
        $validated = $request->validate([
            'training_id' => 'nullable|exists:trainings,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
            'lokasi' => 'nullable|string|max:255',
            'status' => 'required|in:upcoming,ongoing,completed,cancelled,draft,published,selesai',
        ]);

        $agenda->update($validated);

        return redirect()->route('admin.agenda.index')
                        ->with('success', '✅ Agenda berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Agenda $agenda)
    {
        $agenda->delete();

        return redirect()->route('admin.agenda.index')
                        ->with('success', '✅ Agenda berhasil dihapus!');
    }

    // ============================================================
    // ADDITIONAL METHODS
    // ============================================================

    /**
     * Export agenda to CSV.
     */
    public function export(Request $request)
    {
        $query = Agenda::with('training');

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

        $agendas = $query->orderBy('tanggal', 'desc')->get();

        if ($agendas->isEmpty()) {
            return redirect()->route('admin.agenda.index')
                ->with('warning', '⚠️ Tidak ada data agenda untuk diexport.');
        }

        $filename = 'agenda_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($agendas) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'No',
                'Judul',
                'Pelatihan',
                'Tanggal',
                'Waktu Mulai',
                'Waktu Selesai',
                'Lokasi',
                'Status'
            ]);

            foreach ($agendas as $index => $agenda) {
                fputcsv($handle, [
                    $index + 1,
                    $agenda->judul,
                    $agenda->training->judul ?? '-',
                    $agenda->tanggal ? $agenda->tanggal->format('d/m/Y') : '-',
                    $agenda->waktu_mulai ? date('H:i', strtotime($agenda->waktu_mulai)) : '-',
                    $agenda->waktu_selesai ? date('H:i', strtotime($agenda->waktu_selesai)) : '-',
                    $agenda->lokasi ?? '-',
                    $agenda->status
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Display calendar view.
     */
    public function calendar()
    {
        $agendas = Agenda::with('training')
            ->whereIn('status', ['upcoming', 'ongoing', 'published'])
            ->orderBy('tanggal', 'asc')
            ->get();

        return view('admin.agenda.calendar', compact('agendas'));
    }

    /**
     * Bulk delete agendas.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:agenda,id',
        ]);

        $count = Agenda::whereIn('id', $request->ids)->delete();

        return redirect()->route('admin.agenda.index')
            ->with('success', '✅ ' . $count . ' agenda berhasil dihapus!');
    }

    /**
     * Update agenda status automatically.
     */
    public function updateStatuses()
    {
        $agendas = Agenda::whereIn('status', ['upcoming', 'ongoing', 'published'])->get();
        $updated = 0;

        foreach ($agendas as $agenda) {
            $agenda->updateStatus();
            $updated++;
        }

        return redirect()->route('admin.agenda.index')
            ->with('success', '✅ ' . $updated . ' agenda berhasil diperbarui statusnya!');
    }

    /**
     * Get agenda by date range (AJAX).
     */
    public function getByDateRange(Request $request)
    {
        $request->validate([
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',
        ]);

        $agendas = Agenda::with('training')
            ->whereBetween('tanggal', [$request->start, $request->end])
            ->orderBy('tanggal', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $agendas
        ]);
    }

    /**
     * Get upcoming agendas (AJAX).
     */
    public function getUpcoming()
    {
        $agendas = Agenda::with('training')
            ->where('status', 'upcoming')
            ->orWhere(function($q) {
                $q->where('status', 'published')
                  ->where('tanggal', '>=', now());
            })
            ->orderBy('tanggal', 'asc')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $agendas
        ]);
    }
}