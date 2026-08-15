<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\User;
use App\Models\Training;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AbsensiController extends Controller
{
    /**
     * Display a listing of the resource (Admin).
     */
    public function index(Request $request)
    {
        $query = Absensi::with(['user', 'training']);

        // Filter by training
        if ($request->filled('training_id')) {
            $query->where('training_id', $request->training_id);
        }

        // Filter by date
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by user name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('nama', 'like', "%$search%")
                  ->orWhere('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }

        $absensis = $query->orderBy('tanggal', 'desc')
                         ->orderBy('jam_masuk', 'desc')
                         ->paginate(15)
                         ->withQueryString();

        // Statistics
        $totalAbsensi = Absensi::count();
        $hadirCount = Absensi::where('status', 'hadir')->count();
        $sakitCount = Absensi::where('status', 'sakit')->count();
        $izinCount = Absensi::where('status', 'izin')->count();
        $alpaCount = Absensi::where('status', 'alpa')->count();
        $sakitIzinCount = $sakitCount + $izinCount;

        // Training Summary
        $trainingSummary = $this->getTrainingSummary();

        // For filter dropdown
        $trainings = Training::where('status', 'published')->orderBy('judul')->get();

        return view('admin.absen.index', compact(
            'absensis',
            'totalAbsensi',
            'hadirCount',
            'sakitIzinCount',
            'alpaCount',
            'trainings',
            'trainingSummary'
        ));
    }

    /**
     * Show the form for creating a new resource (Admin - tidak digunakan lagi).
     */
    public function create()
    {
        return redirect()->route('admin.absen.index')
            ->with('info', '⚠️ Absensi sekarang dilakukan oleh peserta melalui dashboard peserta.');
    }

    /**
     * Store a newly created resource in storage (Admin - tidak digunakan lagi).
     */
    public function store(Request $request)
    {
        return redirect()->route('admin.absen.index')
            ->with('info', '⚠️ Absensi sekarang dilakukan oleh peserta melalui dashboard peserta.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Absensi $absensi)
    {
        $absensi->load(['user', 'training']);
        return view('admin.absen.show', compact('absensi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Absensi $absensi)
    {
        $users = User::where('role', 'peserta')->orderBy('nama')->get();
        $trainings = Training::where('status', 'published')->orderBy('judul')->get();
        
        return view('admin.absen.edit', compact('absensi', 'users', 'trainings'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Absensi $absensi)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'training_id' => 'nullable|exists:trainings,id',
            'tanggal' => 'required|date',
            'jam_masuk' => 'required|date_format:H:i',
            'jam_keluar' => 'nullable|date_format:H:i',
            'status' => 'required|in:hadir,sakit,izin,alpa',
            'keterangan' => 'nullable|string|max:500',
        ]);

        // Cek duplikat absensi (kecuali dirinya sendiri)
        $exists = Absensi::where('user_id', $validated['user_id'])
            ->whereDate('tanggal', $validated['tanggal'])
            ->where('id', '!=', $absensi->id)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->with('error', '❌ Peserta sudah melakukan absensi pada tanggal ini!')
                ->withInput();
        }

        $absensi->update($validated);

        return redirect()->route('admin.absen.index')
                        ->with('success', '✅ Absensi berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Absensi $absensi)
    {
        $absensi->delete();

        return redirect()->route('admin.absen.index')
                        ->with('success', '✅ Absensi berhasil dihapus');
    }

    // ============================================================
    // PESERTA ABSEN METHODS
    // ============================================================

    /**
     * Display peserta absen page.
     */
    public function pesertaIndex()
    {
        $user = Auth::user();
        $today = now()->format('Y-m-d');

        // Cek absensi hari ini
        $todayAbsen = Absensi::where('user_id', $user->id)
            ->whereDate('tanggal', $today)
            ->first();

        // Riwayat absensi
        $histories = Absensi::where('user_id', $user->id)
            ->with('training')
            ->orderBy('tanggal', 'desc')
            ->paginate(10);

        return view('peserta.absen.index', compact('todayAbsen', 'histories'));
    }

    /**
     * Store peserta absensi.
     */
    public function pesertaStore(Request $request)
    {
        $user = Auth::user();
        $today = now()->format('Y-m-d');

        $request->validate([
            'jam_masuk' => 'required|date_format:H:i',
            'keterangan' => 'nullable|string|max:500',
        ]);

        // Cek apakah sudah absen hari ini
        $exists = Absensi::where('user_id', $user->id)
            ->whereDate('tanggal', $today)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->with('error', '❌ Anda sudah melakukan absensi hari ini!');
        }

        // Ambil training yang sedang diikuti (aktif)
        $training = Training::whereHas('participants', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->where('status', 'berjalan')->first();

        Absensi::create([
            'user_id' => $user->id,
            'training_id' => $training->id ?? null,
            'tanggal' => $today,
            'jam_masuk' => $request->jam_masuk,
            'status' => 'hadir',
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('peserta.absen.index')
            ->with('success', '✅ Absensi berhasil dicatat!');
    }

    /**
     * Export peserta absensi.
     */
    public function pesertaExport()
    {
        $user = Auth::user();
        
        $absensis = Absensi::where('user_id', $user->id)
            ->with('training')
            ->orderBy('tanggal', 'desc')
            ->get();

        if ($absensis->isEmpty()) {
            return redirect()->route('peserta.absen.index')
                ->with('warning', '⚠️ Belum ada data absensi untuk diexport.');
        }

        $filename = 'absensi_saya_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($absensis) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'No',
                'Pelatihan',
                'Tanggal',
                'Jam Masuk',
                'Jam Keluar',
                'Status',
                'Keterangan'
            ]);

            foreach ($absensis as $index => $absen) {
                fputcsv($handle, [
                    $index + 1,
                    $absen->training->judul ?? '-',
                    $absen->tanggal ? $absen->tanggal->format('d/m/Y') : '-',
                    $absen->jam_masuk ? date('H:i', strtotime($absen->jam_masuk)) : '-',
                    $absen->jam_keluar ? date('H:i', strtotime($absen->jam_keluar)) : '-',
                    $absen->status,
                    $absen->keterangan ?? '-'
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ============================================================
    // ADDITIONAL METHODS
    // ============================================================

    /**
     * Get training summary.
     */
    private function getTrainingSummary()
    {
        $trainings = Training::where('status', 'published')->get();
        $summary = [];

        foreach ($trainings as $training) {
            $total = Absensi::where('training_id', $training->id)->count();
            $hadir = Absensi::where('training_id', $training->id)->where('status', 'hadir')->count();
            $sakit = Absensi::where('training_id', $training->id)->where('status', 'sakit')->count();
            $izin = Absensi::where('training_id', $training->id)->where('status', 'izin')->count();
            $alpa = Absensi::where('training_id', $training->id)->where('status', 'alpa')->count();

            $summary[] = [
                'training' => $training->judul,
                'total' => $total,
                'hadir' => $hadir,
                'sakit' => $sakit,
                'izin' => $izin,
                'alpa' => $alpa,
                'persentase' => $total > 0 ? round(($hadir / $total) * 100, 2) : 0,
            ];
        }

        return $summary;
    }

    /**
     * Check duplicate attendance.
     */
    public function checkDuplicate(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
        ]);

        $exists = Absensi::where('user_id', $request->user_id)
            ->whereDate('tanggal', $request->tanggal)
            ->exists();

        return response()->json([
            'exists' => $exists,
            'message' => $exists ? 'Peserta sudah melakukan absensi pada tanggal ini!' : null
        ]);
    }

    /**
     * Export absensi to CSV.
     */
    public function export(Request $request)
    {
        $query = Absensi::with(['user', 'training']);

        // Apply filters
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('training_id')) {
            $query->where('training_id', $request->training_id);
        }

        $absensis = $query->orderBy('tanggal', 'desc')->get();

        if ($absensis->isEmpty()) {
            return redirect()->route('admin.absen.index')
                ->with('warning', '⚠️ Tidak ada data absensi untuk diexport.');
        }

        $filename = 'absensi_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($absensis) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'No',
                'Nama Peserta',
                'Email',
                'Pelatihan',
                'Tanggal',
                'Jam Masuk',
                'Jam Keluar',
                'Status',
                'Keterangan'
            ]);

            foreach ($absensis as $index => $absen) {
                fputcsv($handle, [
                    $index + 1,
                    $absen->user->nama ?? $absen->user->name ?? '-',
                    $absen->user->email ?? '-',
                    $absen->training->judul ?? '-',
                    $absen->tanggal ? $absen->tanggal->format('d/m/Y') : '-',
                    $absen->jam_masuk ? date('H:i', strtotime($absen->jam_masuk)) : '-',
                    $absen->jam_keluar ? date('H:i', strtotime($absen->jam_keluar)) : '-',
                    $absen->status,
                    $absen->keterangan ?? '-'
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Bulk delete absensi.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:absensis,id',
        ]);

        $count = Absensi::whereIn('id', $request->ids)->delete();

        return redirect()->route('admin.absen.index')
            ->with('success', '✅ ' . $count . ' data absensi berhasil dihapus!');
    }

    /**
     * Get absensi by date range.
     */
    public function getByDateRange(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $absensis = Absensi::with(['user', 'training'])
            ->whereBetween('tanggal', [$request->start_date, $request->end_date])
            ->orderBy('tanggal', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $absensis,
            'total' => $absensis->count()
        ]);
    }

    /**
     * Get absensi summary for dashboard.
     */
    public function summary()
    {
        $today = now()->format('Y-m-d');
        $thisMonth = now()->format('Y-m');

        $summary = [
            'today' => [
                'total' => Absensi::whereDate('tanggal', $today)->count(),
                'hadir' => Absensi::whereDate('tanggal', $today)->where('status', 'hadir')->count(),
                'sakit' => Absensi::whereDate('tanggal', $today)->where('status', 'sakit')->count(),
                'izin' => Absensi::whereDate('tanggal', $today)->where('status', 'izin')->count(),
                'alpa' => Absensi::whereDate('tanggal', $today)->where('status', 'alpa')->count(),
            ],
            'month' => [
                'total' => Absensi::whereYear('tanggal', now()->year)
                    ->whereMonth('tanggal', now()->month)
                    ->count(),
                'hadir' => Absensi::whereYear('tanggal', now()->year)
                    ->whereMonth('tanggal', now()->month)
                    ->where('status', 'hadir')
                    ->count(),
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $summary
        ]);
    }

    /**
     * Get absensi by user.
     */
    public function getByUser($userId)
    {
        $user = User::findOrFail($userId);
        
        $absensis = Absensi::where('user_id', $userId)
            ->with('training')
            ->orderBy('tanggal', 'desc')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'user' => $user,
            'data' => $absensis
        ]);
    }

    /**
     * Get today's attendance statistics.
     */
    public function todayStats()
    {
        $today = now()->format('Y-m-d');
        
        $stats = [
            'total' => Absensi::whereDate('tanggal', $today)->count(),
            'hadir' => Absensi::whereDate('tanggal', $today)->where('status', 'hadir')->count(),
            'sakit' => Absensi::whereDate('tanggal', $today)->where('status', 'sakit')->count(),
            'izin' => Absensi::whereDate('tanggal', $today)->where('status', 'izin')->count(),
            'alpa' => Absensi::whereDate('tanggal', $today)->where('status', 'alpa')->count(),
            'persentase_kehadiran' => Absensi::whereDate('tanggal', $today)->count() > 0 
                ? round((Absensi::whereDate('tanggal', $today)->where('status', 'hadir')->count() / Absensi::whereDate('tanggal', $today)->count()) * 100, 2) 
                : 0,
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Get attendance for calendar.
     */
    public function calendar(Request $request)
    {
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);

        $absensis = Absensi::whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->with(['user', 'training'])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $absensis,
            'year' => $year,
            'month' => $month
        ]);
    }

    /**
     * Get user attendance history.
     */
    public function userHistory($userId)
    {
        $user = User::findOrFail($userId);
        
        $absensis = Absensi::where('user_id', $userId)
            ->with('training')
            ->orderBy('tanggal', 'desc')
            ->limit(30)
            ->get();

        $summary = [
            'total' => $absensis->count(),
            'hadir' => $absensis->where('status', 'hadir')->count(),
            'sakit' => $absensis->where('status', 'sakit')->count(),
            'izin' => $absensis->where('status', 'izin')->count(),
            'alpa' => $absensis->where('status', 'alpa')->count(),
        ];

        return response()->json([
            'success' => true,
            'user' => $user,
            'data' => $absensis,
            'summary' => $summary
        ]);
    }

    /**
     * Get peserta check-in status.
     */
    public function checkStatus()
    {
        $user = Auth::user();
        $today = now()->format('Y-m-d');

        $absen = Absensi::where('user_id', $user->id)
            ->whereDate('tanggal', $today)
            ->first();

        return response()->json([
            'success' => true,
            'data' => [
                'has_checked_in' => $absen !== null,
                'jam_masuk' => $absen ? $absen->jam_masuk_formatted : null,
                'jam_keluar' => $absen ? $absen->jam_keluar_formatted : null,
                'status' => $absen ? $absen->status : null,
                'is_complete' => $absen && $absen->jam_keluar !== null,
            ]
        ]);
    }

    /**
     * ========================================================
     * QR CODE ATTENDANCE METHODS (NEW)
     * ========================================================
     */

    public function session(Training $training)
    {
        // Get all approved participants
        $participants = $training->registrations()
            ->with('user')
            ->where('status', 'disetujui')
            ->get();

        $today = now()->format('Y-m-d');

        // Check attendance today for these participants
        $absensiHariIni = Absensi::where('training_id', $training->id)
            ->whereDate('tanggal', $today)
            ->get()
            ->keyBy('user_id');

        $hadirCount = 0;
        foreach ($participants as $participant) {
            $absen = $absensiHariIni->get($participant->user_id);
            $participant->absen_status = $absen ? $absen->status : 'Belum Hadir';
            $participant->waktu_absen = $absen ? $absen->waktu_checkin : null;
            $participant->metode_absen = $absen ? $absen->metode : null;
            if ($participant->absen_status == 'hadir') {
                $hadirCount++;
            }
        }

        $belumHadirCount = $participants->count() - $hadirCount;
        $persentase = $participants->count() > 0 ? round(($hadirCount / $participants->count()) * 100, 2) : 0;

        return view('admin.absen.session', compact(
            'training',
            'participants',
            'hadirCount',
            'belumHadirCount',
            'persentase',
            'today'
        ));
    }

    public function startSession(Training $training)
    {
        $training->is_absen_open = true;
        $training->absen_token = \Illuminate\Support\Str::random(32);
        $training->save();

        return back()->with('success', 'Sesi absensi berhasil dibuka.');
    }

    public function stopSession(Training $training)
    {
        $training->is_absen_open = false;
        $training->absen_token = null;
        $training->save();

        return back()->with('success', 'Sesi absensi berhasil ditutup.');
    }

    public function markPresent(Training $training, Request $request)
    {
        $userIds = $request->input('user_ids', []);
        
        if (empty($userIds)) {
            return back()->with('error', 'Pilih minimal satu peserta.');
        }

        $today = now()->format('Y-m-d');
        $now = now();

        foreach ($userIds as $userId) {
            // Check if user is approved
            $isApproved = \App\Models\TrainingRegistration::where('training_id', $training->id)
                ->where('user_id', $userId)
                ->where('status', 'disetujui')
                ->exists();

            if ($isApproved) {
                // Upsert absensi
                Absensi::updateOrCreate(
                    [
                        'user_id' => $userId,
                        'training_id' => $training->id,
                        'tanggal' => $today,
                    ],
                    [
                        'status' => 'hadir',
                        'waktu_checkin' => $now,
                        'jam_masuk' => $now->format('H:i:s'),
                        'metode' => 'Manual oleh Admin',
                    ]
                );
            }
        }

        return back()->with('success', 'Peserta terpilih berhasil ditandai hadir.');
    }

    public function markAllPresent(Training $training)
    {
        $participants = $training->registrations()->where('status', 'disetujui')->get();
        $today = now()->format('Y-m-d');
        $now = now();

        foreach ($participants as $participant) {
            Absensi::updateOrCreate(
                [
                    'user_id' => $participant->user_id,
                    'training_id' => $training->id,
                    'tanggal' => $today,
                ],
                [
                    'status' => 'hadir',
                    'waktu_checkin' => $now,
                    'jam_masuk' => $now->format('H:i:s'),
                    'metode' => 'Manual oleh Admin',
                ]
            );
        }

        return back()->with('success', 'Seluruh peserta berhasil ditandai hadir.');
    }
}