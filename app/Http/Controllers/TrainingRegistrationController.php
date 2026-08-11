<?php

namespace App\Http\Controllers;

use App\Models\TrainingRegistration;
use App\Models\Training;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrainingRegistrationController extends Controller
{
    /**
     * Display a listing of registrations (Admin)
     */
    public function index(Request $request)
    {
        $query = TrainingRegistration::with(['training', 'user']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by training
        if ($request->filled('training_id')) {
            $query->where('training_id', $request->training_id);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            })->orWhereHas('training', function($q) use ($search) {
                $q->where('judul', 'like', "%$search%");
            });
        }

        $registrations = $query->orderBy('created_at', 'desc')
                               ->paginate(15)
                               ->withQueryString();

        $trainings = Training::where('status', 'published')->orderBy('judul')->get();

        // Statistics
        $totalPending = TrainingRegistration::where('status', 'pending')->count();
        $totalApproved = TrainingRegistration::where('status', 'disetujui')->count();
        $totalRejected = TrainingRegistration::where('status', 'ditolak')->count();
        $totalCancelled = TrainingRegistration::where('status', 'dibatalkan')->count();

        return view('admin.pendaftaran.index', compact(
            'registrations',
            'trainings',
            'totalPending',
            'totalApproved',
            'totalRejected',
            'totalCancelled'
        ));
    }

    /**
     * Show the form for creating a new registration.
     */
    public function create()
    {
        $trainings = Training::where('status', 'published')->orderBy('judul')->get();
        $users = User::where('role', 'peserta')->orderBy('name')->get();
        
        return view('admin.pendaftaran.create', compact('trainings', 'users'));
    }

    /**
     * Store a newly created registration.
     */
    public function store(Request $request)
    {
        $request->validate([
            'training_id' => 'required|exists:trainings,id',
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:pending,disetujui,ditolak,dibatalkan',
        ]);

        // Cek duplikasi
        $exists = TrainingRegistration::where('training_id', $request->training_id)
            ->where('user_id', $request->user_id)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->with('error', '⚠️ User sudah terdaftar di pelatihan ini.')
                ->withInput();
        }

        // Cek kuota jika status disetujui
        if ($request->status === 'disetujui') {
            $training = Training::find($request->training_id);
            $participantsCount = TrainingRegistration::where('training_id', $request->training_id)
                ->where('status', 'disetujui')
                ->count();
                
            if ($training->kapasitas && $participantsCount >= $training->kapasitas) {
                return redirect()->back()
                    ->with('error', '⚠️ Kuota pelatihan sudah penuh!')
                    ->withInput();
            }
        }

        TrainingRegistration::create($request->all());

        return redirect()->route('admin.pendaftaran.index')
            ->with('success', '✅ Pendaftaran berhasil ditambahkan.');
    }

    /**
     * Display the specified registration.
     */
    public function show($id)
    {
        $registration = TrainingRegistration::with(['training', 'user'])
            ->findOrFail($id);

        return view('admin.pendaftaran.show', compact('registration'));
    }

    /**
     * Show the form for editing the specified registration.
     */
    public function edit($id)
    {
        $registration = TrainingRegistration::with(['training', 'user'])->findOrFail($id);
        $trainings = Training::where('status', 'published')->orderBy('judul')->get();
        $users = User::where('role', 'peserta')->orderBy('name')->get();

        return view('admin.pendaftaran.edit', compact('registration', 'trainings', 'users'));
    }

    /**
     * Update the specified registration.
     */
    public function update(Request $request, $id)
    {
        $registration = TrainingRegistration::findOrFail($id);

        $request->validate([
            'training_id' => 'required|exists:trainings,id',
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:pending,disetujui,ditolak,dibatalkan',
        ]);

        // Cek duplikasi (kecuali dirinya sendiri)
        $exists = TrainingRegistration::where('training_id', $request->training_id)
            ->where('user_id', $request->user_id)
            ->where('id', '!=', $id)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->with('error', '⚠️ User sudah terdaftar di pelatihan ini.')
                ->withInput();
        }

        // Cek kuota jika status berubah menjadi disetujui
        if ($request->status === 'disetujui' && $registration->status !== 'disetujui') {
            $training = Training::find($request->training_id);
            $participantsCount = TrainingRegistration::where('training_id', $request->training_id)
                ->where('status', 'disetujui')
                ->count();
                
            if ($training->kapasitas && $participantsCount >= $training->kapasitas) {
                return redirect()->back()
                    ->with('error', '⚠️ Kuota pelatihan sudah penuh!')
                    ->withInput();
            }
        }

        $registration->update($request->all());

        return redirect()->route('admin.pendaftaran.index')
            ->with('success', '✅ Pendaftaran berhasil diperbarui.');
    }

    /**
     * Remove the specified registration.
     */
    public function destroy($id)
    {
        $registration = TrainingRegistration::findOrFail($id);
        $registration->delete();

        return redirect()->route('admin.pendaftaran.index')
            ->with('success', '✅ Pendaftaran berhasil dihapus.');
    }

    /**
     * Approve registration.
     * PERBAIKAN: Gunakan status 'disetujui'
     */
    public function approve($id)
    {
        $registration = TrainingRegistration::with('training')->findOrFail($id);

        // Cek apakah sudah disetujui
        if ($registration->status === 'disetujui') {
            return redirect()->back()
                ->with('warning', '⚠️ Pendaftaran sudah disetujui sebelumnya.');
        }

        // Cek kuota
        $training = $registration->training;
        $participantsCount = TrainingRegistration::where('training_id', $training->id)
            ->where('status', 'disetujui')
            ->count();
            
        if ($training->kapasitas && $participantsCount >= $training->kapasitas) {
            return redirect()->back()
                ->with('error', '⚠️ Kuota pelatihan sudah penuh!');
        }

        // PERBAIKAN: Gunakan 'disetujui' bukan 'approved'
        $registration->update([
            'status' => 'disetujui',
        ]);

        return redirect()->route('admin.pendaftaran.index')
            ->with('success', '✅ Pendaftaran berhasil disetujui!');
    }

    /**
     * Reject registration.
     * PERBAIKAN: Gunakan status 'ditolak' dan simpan alasan_penolakan
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|max:1000'
        ]);

        $registration = TrainingRegistration::findOrFail($id);

        // Cek apakah sudah ditolak
        if ($registration->status === 'ditolak') {
            return redirect()->back()
                ->with('warning', '⚠️ Pendaftaran sudah ditolak sebelumnya.');
        }

        // PERBAIKAN: Gunakan 'ditolak' dan simpan alasan
        $registration->update([
            'status' => 'ditolak',
            'alasan_penolakan' => $request->alasan_penolakan
        ]);

        return redirect()->route('admin.trainings.participants', $registration->training_id)
            ->with('success', '✅ Pendaftaran berhasil ditolak.');
    }

    /**
     * Cancel registration.
     * PERBAIKAN: Gunakan status 'dibatalkan'
     */
    public function cancel($id)
    {
        $registration = TrainingRegistration::findOrFail($id);

        // Cek apakah sudah dibatalkan
        if ($registration->status === 'dibatalkan') {
            return redirect()->back()
                ->with('warning', '⚠️ Pendaftaran sudah dibatalkan sebelumnya.');
        }

        // PERBAIKAN: Gunakan 'dibatalkan' bukan 'cancelled'
        $registration->update([
            'status' => 'dibatalkan',
        ]);

        return redirect()->route('admin.pendaftaran.index')
            ->with('success', '✅ Pendaftaran berhasil dibatalkan.');
    }

    /**
     * Bulk approve registrations.
     * PERBAIKAN: Gunakan status 'disetujui'
     */
    public function bulkApprove(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:training_registrations,id',
        ]);

        // Cek kuota untuk setiap training
        $registrations = TrainingRegistration::whereIn('id', $request->ids)
            ->where('status', 'pending')
            ->get();

        if ($registrations->isEmpty()) {
            return redirect()->back()
                ->with('warning', '⚠️ Tidak ada pendaftaran yang bisa disetujui.');
        }

        $errors = [];
        $successCount = 0;

        foreach ($registrations as $registration) {
            $training = $registration->training;
            $participantsCount = TrainingRegistration::where('training_id', $training->id)
                ->where('status', 'disetujui')
                ->count();
                
            if ($training->kapasitas && $participantsCount >= $training->kapasitas) {
                $errors[] = "Kuota pelatihan '{$training->judul}' sudah penuh!";
                continue;
            }

            // PERBAIKAN: Gunakan 'disetujui'
            $registration->update(['status' => 'disetujui']);
            $successCount++;
        }

        $message = "✅ {$successCount} pendaftaran berhasil disetujui.";
        if (!empty($errors)) {
            $message .= " <br>⚠️ " . implode('<br>⚠️ ', $errors);
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Export registrations to CSV.
     */
    public function export(Request $request)
    {
        $query = TrainingRegistration::with(['training', 'user']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('training_id')) {
            $query->where('training_id', $request->training_id);
        }

        $registrations = $query->get();

        if ($registrations->isEmpty()) {
            return redirect()->back()
                ->with('warning', '⚠️ Tidak ada data untuk diexport.');
        }

        $filename = 'pendaftaran_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($registrations) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'No',
                'Nama Peserta',
                'Email',
                'Pelatihan',
                'Tanggal Daftar',
                'Status',
            ]);

            foreach ($registrations as $index => $registration) {
                fputcsv($handle, [
                    $index + 1,
                    $registration->user->name ?? '-',
                    $registration->user->email ?? '-',
                    $registration->training->judul ?? '-',
                    $registration->created_at ? $registration->created_at->format('d/m/Y H:i') : '-',
                    $registration->status_label,
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get pending count for dashboard badge.
     */
    public function pendingCount()
    {
        $count = TrainingRegistration::where('status', 'pending')->count();
        return response()->json(['count' => $count]);
    }

    /**
     * Get training info for AJAX.
     */
    public function getTrainingInfo($id)
    {
        $training = Training::withCount(['registrations as participants_count' => function($q) {
            $q->where('status', 'disetujui');
        }])->findOrFail($id);

        return response()->json([
            'success' => true,
            'training' => [
                'id' => $training->id,
                'judul' => $training->judul,
                'kapasitas' => $training->kapasitas,
                'participants_count' => $training->participants_count,
                'available_slots' => $training->kapasitas ? $training->kapasitas - $training->participants_count : null,
                'is_full' => $training->kapasitas ? $training->participants_count >= $training->kapasitas : false,
            ]
        ]);
    }
}