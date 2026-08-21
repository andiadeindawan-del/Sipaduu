<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\Training;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgendaController extends Controller
{
    /**
     * Display a listing of the resource (Admin).
     */
    public function index(Request $request)
    {
        $query = Agenda::with(['training', 'creator']);

        // Filter by training
        if ($request->filled('training_id')) {
            $query->where('training_id', $request->training_id);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('tipe', $request->type);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('tanggal', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('tanggal', '<=', $request->date_to);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%$search%")
                  ->orWhere('deskripsi', 'like', "%$search%")
                  ->orWhere('lokasi', 'like', "%$search%");
            });
        }

        $agendas = $query->orderBy('tanggal', 'asc')
                         ->orderBy('jam_mulai', 'asc')
                         ->paginate(15)
                         ->withQueryString();

        $trainings = Training::whereIn('status', ['draft', 'published', 'berjalan', 'selesai'])->orderBy('judul')->get();

        // Statistics
        $totalAgendas = Agenda::count();
        $upcomingAgendas = Agenda::whereDate('tanggal', '>=', now())->count();
        $todayAgendas = Agenda::whereDate('tanggal', now())->count();

        return view('admin.agenda.index', compact(
            'agendas',
            'trainings',
            'totalAgendas',
            'upcomingAgendas',
            'todayAgendas'
        ));
    }

    /**
     * Display a listing of agendas for peserta.
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

        $query = Agenda::with(['training', 'creator'])
            ->whereIn('training_id', $trainingIds)
            ->orWhere('training_id', null);

        // Filter by type
        if ($request->filled('type')) {
            $query->where('tipe', $request->type);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('tanggal', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('tanggal', '<=', $request->date_to);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%$search%")
                  ->orWhere('deskripsi', 'like', "%$search%");
            });
        }

        $agendas = $query->orderBy('tanggal', 'asc')
                         ->orderBy('jam_mulai', 'asc')
                         ->paginate(15)
                         ->withQueryString();

        // Statistics
        $totalAgendas = $query->count();
        $upcomingAgendas = (clone $query)->whereDate('tanggal', '>=', now())->count();
        $todayAgendas = (clone $query)->whereDate('tanggal', now())->count();

        return view('peserta.agenda.index', compact(
            'agendas',
            'totalAgendas',
            'upcomingAgendas',
            'todayAgendas'
        ));
    }

    /**
     * Display the specified agenda for peserta.
     * PERBAIKAN: Gunakan training_registrations
     */
    public function pesertaShow($id)
    {
        $agenda = Agenda::with(['training', 'creator'])->findOrFail($id);
        
        $user = Auth::user();
        
        // PERBAIKAN: Gunakan registrations() dengan tabel training_registrations
        $trainingIds = Training::whereHas('registrations', function($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->whereIn('status', ['pending', 'registered', 'approved', 'completed']);
        })->pluck('id');

        if ($agenda->training_id && !$trainingIds->contains($agenda->training_id)) {
            abort(403, 'Anda tidak memiliki akses ke agenda ini.');
        }

        return view('peserta.agenda.show', compact('agenda'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $trainings = Training::whereIn('status', ['draft', 'published', 'berjalan', 'selesai'])->orderBy('judul')->get();
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
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'nullable|date_format:H:i|after:jam_mulai',
            'lokasi' => 'nullable|string|max:255',
            'link_meeting' => 'nullable|url|max:255',
            'tipe' => 'required|in:online,offline,hybrid',
            'status' => 'required|in:draft,published,selesai,dibatalkan',
        ]);

        $validated['created_by'] = auth()->id();

        Agenda::create($validated);

        return redirect()->route('admin.agenda.index')
                        ->with('success', '✅ Agenda berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Agenda $agenda)
    {
        $agenda->load(['training', 'creator']);
        return view('admin.agenda.show', compact('agenda'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Agenda $agenda)
    {
        $trainings = Training::whereIn('status', ['draft', 'published', 'berjalan', 'selesai'])->orderBy('judul')->get();
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
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'nullable|date_format:H:i|after:jam_mulai',
            'lokasi' => 'nullable|string|max:255',
            'link_meeting' => 'nullable|url|max:255',
            'tipe' => 'required|in:online,offline,hybrid',
            'status' => 'required|in:draft,published,selesai,dibatalkan',
        ]);

        $agenda->update($validated);

        return redirect()->route('admin.agenda.index')
                        ->with('success', '✅ Agenda berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Agenda $agenda)
    {
        $agenda->delete();

        return redirect()->route('admin.agenda.index')
                        ->with('success', '✅ Agenda berhasil dihapus.');
    }

    /**
     * Export agendas to CSV.
     */
    public function export(Request $request)
    {
        $query = Agenda::with(['training', 'creator']);

        if ($request->filled('training_id')) {
            $query->where('training_id', $request->training_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('tanggal', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('tanggal', '<=', $request->date_to);
        }

        $agendas = $query->orderBy('tanggal', 'asc')->get();

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
                'Jam Mulai',
                'Jam Selesai',
                'Lokasi',
                'Tipe',
                'Status'
            ]);

            foreach ($agendas as $index => $agenda) {
                fputcsv($handle, [
                    $index + 1,
                    $agenda->judul,
                    $agenda->training->judul ?? 'Umum',
                    $agenda->tanggal ? $agenda->tanggal->format('d/m/Y') : '-',
                    $agenda->jam_mulai,
                    $agenda->jam_selesai ?? '-',
                    $agenda->lokasi ?? ($agenda->link_meeting ?? '-'),
                    $agenda->tipe,
                    $agenda->status
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get agenda for calendar.
     */
    public function calendar(Request $request)
    {
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);

        $agendas = Agenda::whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->with(['training'])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $agendas,
            'year' => $year,
            'month' => $month
        ]);
    }

    /**
     * Bulk delete agendas.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:agendas,id',
        ]);

        $count = Agenda::whereIn('id', $request->ids)->delete();

        return redirect()->route('admin.agenda.index')
            ->with('success', "✅ {$count} agenda berhasil dihapus.");
    }

    /**
     * Update statuses (mass update).
     */
    public function updateStatuses(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:agendas,id',
            'status' => 'required|in:draft,published,selesai,dibatalkan',
        ]);

        $count = Agenda::whereIn('id', $request->ids)
            ->update(['status' => $request->status]);

        return redirect()->route('admin.agenda.index')
            ->with('success', "✅ {$count} agenda berhasil diperbarui.");
    }

    /**
     * Get agendas by date range (JSON).
     */
    public function getByDateRange(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $agendas = Agenda::with(['training'])
            ->whereBetween('tanggal', [$request->start_date, $request->end_date])
            ->orderBy('tanggal', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $agendas,
            'total' => $agendas->count()
        ]);
    }

    /**
     * Get upcoming agendas (JSON).
     */
    public function getUpcoming(Request $request)
    {
        $limit = $request->get('limit', 10);

        $agendas = Agenda::with(['training'])
            ->whereDate('tanggal', '>=', now())
            ->orderBy('tanggal', 'asc')
            ->orderBy('jam_mulai', 'asc')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $agendas,
            'total' => $agendas->count()
        ]);
    }
}