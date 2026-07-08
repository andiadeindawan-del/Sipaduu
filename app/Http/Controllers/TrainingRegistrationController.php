<?php

namespace App\Http\Controllers;

use App\Models\TrainingRegistration;
use App\Models\Training;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class TrainingRegistrationController extends Controller
{
    /**
     * Display a listing of the training registrations (Admin Monitoring).
     */
    public function index(Request $request)
    {
        $query = TrainingRegistration::with(['user', 'training']);

        // Filter search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($q2) use ($search) {
                    $q2->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('email', 'LIKE', "%{$search}%")
                      ->orWhere('nama', 'LIKE', "%{$search}%");
                })->orWhereHas('training', function($q2) use ($search) {
                    $q2->where('judul', 'LIKE', "%{$search}%");
                });
            });
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter training
        if ($request->filled('training_id')) {
            $query->where('training_id', $request->training_id);
        }

        $registrations = $query->orderBy('created_at', 'desc')->paginate(10);

        // Stats
        $totalRegistrations = TrainingRegistration::count();
        $activeRegistrations = TrainingRegistration::whereIn('status', ['approved', 'registered', 'completed'])->count();
        $pendingRegistrations = TrainingRegistration::where('status', 'pending')->count();
        $cancelledRegistrations = TrainingRegistration::whereIn('status', ['cancelled', 'rejected'])->count();

        // Training summary
        $trainingSummary = $this->getTrainingSummary();

        // Trainings for filter
        $trainings = Training::where('status', 'published')->orderBy('judul')->get();

        return view('admin.pendaftaran.index', compact(
            'registrations',
            'totalRegistrations',
            'activeRegistrations',
            'pendingRegistrations',
            'cancelledRegistrations',
            'trainings',
            'trainingSummary'
        ));
    }

    /**
     * Show the form for creating a new registration (Disabled - Peserta register sendiri).
     */
    public function create()
    {
        return redirect()->route('admin.pendaftaran.index')
            ->with('info', '⚠️ Pendaftaran sekarang dilakukan oleh peserta melalui dashboard peserta.');
    }

    /**
     * Store a newly created registration in storage (Disabled - Peserta register sendiri).
     */
    public function store(Request $request)
    {
        return redirect()->route('admin.pendaftaran.index')
            ->with('info', '⚠️ Pendaftaran sekarang dilakukan oleh peserta melalui dashboard peserta.');
    }

    /**
     * Display the specified registration.
     */
    public function show($id)
    {
        $registration = TrainingRegistration::with(['user', 'training', 'approver'])
            ->findOrFail($id);

        // Get quiz results for this registration
        $quizAttempts = $registration->quizAttempts()
            ->with('quiz')
            ->orderBy('created_at', 'desc')
            ->get();

        // Check if certificate exists
        $certificate = $registration->certificate;

        return view('admin.pendaftaran.show', compact('registration', 'quizAttempts', 'certificate'));
    }

    /**
     * Show the form for editing the specified registration.
     */
    public function edit($id)
    {
        $registration = TrainingRegistration::with(['user', 'training'])
            ->findOrFail($id);

        $users = User::where('role', 'peserta')->orderBy('nama')->get();
        $trainings = Training::where('status', 'published')->orderBy('judul')->get();

        return view('admin.pendaftaran.edit', compact('registration', 'users', 'trainings'));
    }

    /**
     * Update the specified registration in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'training_id' => 'required|exists:trainings,id',
            'status' => 'required|in:pending,approved,rejected,cancelled,registered,completed',
            'notes' => 'nullable|string|max:500',
            'registered_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $registration = TrainingRegistration::findOrFail($id);

        // Cek duplikat (kecuali dirinya sendiri)
        $exists = TrainingRegistration::where('user_id', $request->user_id)
            ->where('training_id', $request->training_id)
            ->where('id', '!=', $id)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->with('error', '❌ Peserta sudah terdaftar di pelatihan ini!')
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $registration->update([
                'user_id' => $request->user_id,
                'training_id' => $request->training_id,
                'status' => $request->status,
                'registered_at' => $request->registered_at ?? $registration->registered_at,
                'notes' => $request->notes,
            ]);

            DB::commit();

            return redirect()->route('admin.pendaftaran.index')
                ->with('success', '✅ Pendaftaran berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', '❌ Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Approve registration.
     */
    public function approve($id)
    {
        $registration = TrainingRegistration::findOrFail($id);

        if ($registration->status === 'approved') {
            return redirect()->back()
                ->with('warning', '⚠️ Pendaftaran sudah disetujui sebelumnya.');
        }

        $registration->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        return redirect()->route('admin.pendaftaran.index')
            ->with('success', '✅ Pendaftaran berhasil disetujui!');
    }

    /**
     * Reject registration.
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        $registration = TrainingRegistration::findOrFail($id);

        if ($registration->status === 'rejected') {
            return redirect()->back()
                ->with('warning', '⚠️ Pendaftaran sudah ditolak sebelumnya.');
        }

        $registration->update([
            'status' => 'rejected',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
            'rejection_reason' => $request->reason,
        ]);

        return redirect()->route('admin.pendaftaran.index')
            ->with('success', '✅ Pendaftaran berhasil ditolak!');
    }

    /**
     * Cancel registration.
     */
    public function cancel($id)
    {
        $registration = TrainingRegistration::findOrFail($id);

        if ($registration->status === 'cancelled') {
            return redirect()->back()
                ->with('warning', '⚠️ Pendaftaran sudah dibatalkan sebelumnya.');
        }

        $registration->update([
            'status' => 'cancelled',
        ]);

        return redirect()->route('admin.pendaftaran.index')
            ->with('success', '✅ Pendaftaran berhasil dibatalkan!');
    }

    /**
     * Remove the specified registration from storage.
     */
    public function destroy($id)
    {
        $registration = TrainingRegistration::findOrFail($id);
        
        // Cek apakah sudah punya sertifikat
        if ($registration->certificate) {
            return redirect()->back()
                ->with('error', '❌ Pendaftaran tidak dapat dihapus karena sudah memiliki sertifikat!');
        }

        // Cek apakah sudah punya attempt quiz
        if ($registration->quizAttempts()->count() > 0) {
            return redirect()->back()
                ->with('error', '❌ Pendaftaran tidak dapat dihapus karena sudah mengerjakan quiz!');
        }

        DB::beginTransaction();
        try {
            $registration->delete();
            DB::commit();

            return redirect()->route('admin.pendaftaran.index')
                ->with('success', '✅ Pendaftaran berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', '❌ Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // ============================================================
    // PESERTA REGISTRATION METHODS
    // ============================================================

    /**
     * Display peserta registrations.
     */
    public function pesertaIndex()
    {
        $user = Auth::user();
        
        $registrations = TrainingRegistration::where('user_id', $user->id)
            ->with(['training', 'certificate'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $totalRegistrations = TrainingRegistration::where('user_id', $user->id)->count();
        $activeRegistrations = TrainingRegistration::where('user_id', $user->id)
            ->whereIn('status', ['approved', 'registered', 'completed'])
            ->count();
        $pendingRegistrations = TrainingRegistration::where('user_id', $user->id)
            ->where('status', 'pending')
            ->count();

        return view('peserta.pendaftaran.index', compact(
            'registrations',
            'totalRegistrations',
            'activeRegistrations',
            'pendingRegistrations'
        ));
    }

    /**
     * Register peserta to training.
     */
    public function pesertaStore(Request $request)
    {
        $request->validate([
            'training_id' => 'required|exists:trainings,id',
        ]);

        $user = Auth::user();
        $trainingId = $request->training_id;

        // Cek apakah sudah terdaftar
        $exists = TrainingRegistration::where('user_id', $user->id)
            ->where('training_id', $trainingId)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->with('error', '❌ Anda sudah terdaftar di pelatihan ini!');
        }

        // Cek kapasitas
        $training = Training::find($trainingId);
        $registeredCount = TrainingRegistration::where('training_id', $trainingId)
            ->whereIn('status', ['approved', 'registered', 'completed'])
            ->count();

        if ($training->kapasitas && $registeredCount >= $training->kapasitas) {
            return redirect()->back()
                ->with('error', '❌ Kuota pelatihan sudah penuh!');
        }

        DB::beginTransaction();
        try {
            $registrationNumber = TrainingRegistration::generateRegistrationNumber();

            TrainingRegistration::create([
                'user_id' => $user->id,
                'training_id' => $trainingId,
                'registration_number' => $registrationNumber,
                'status' => 'pending',
                'registered_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('peserta.pendaftaran.index')
                ->with('success', '✅ Pendaftaran berhasil! Menunggu verifikasi admin.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', '❌ Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Cancel peserta registration.
     */
    public function pesertaCancel($id)
    {
        $user = Auth::user();
        
        $registration = TrainingRegistration::where('user_id', $user->id)
            ->findOrFail($id);

        if ($registration->status === 'approved' || $registration->status === 'completed') {
            return redirect()->back()
                ->with('error', '❌ Pendaftaran yang sudah disetujui tidak dapat dibatalkan. Hubungi admin.');
        }

        $registration->update([
            'status' => 'cancelled',
        ]);

        return redirect()->route('peserta.pendaftaran.index')
            ->with('success', '✅ Pendaftaran berhasil dibatalkan!');
    }

    /**
     * Export peserta registrations.
     */
    public function pesertaExport()
    {
        $user = Auth::user();
        
        $registrations = TrainingRegistration::where('user_id', $user->id)
            ->with('training')
            ->orderBy('created_at', 'desc')
            ->get();

        if ($registrations->isEmpty()) {
            return redirect()->route('peserta.pendaftaran.index')
                ->with('warning', '⚠️ Belum ada data pendaftaran untuk diexport.');
        }

        $filename = 'pendaftaran_saya_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($registrations) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'No',
                'Pelatihan',
                'Tanggal Daftar',
                'Status',
                'Nomor Registrasi'
            ]);

            foreach ($registrations as $index => $reg) {
                fputcsv($handle, [
                    $index + 1,
                    $reg->training->judul ?? '-',
                    $reg->registered_at ? $reg->registered_at->format('d/m/Y H:i') : '-',
                    $reg->status,
                    $reg->registration_number ?? '-'
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
     * Get training summary for dashboard.
     */
    private function getTrainingSummary()
    {
        $trainings = Training::where('status', 'published')->get();
        $summary = [];

        foreach ($trainings as $training) {
            $total = TrainingRegistration::where('training_id', $training->id)->count();
            $approved = TrainingRegistration::where('training_id', $training->id)
                ->whereIn('status', ['approved', 'registered', 'completed'])
                ->count();
            $pending = TrainingRegistration::where('training_id', $training->id)
                ->where('status', 'pending')
                ->count();
            $rejected = TrainingRegistration::where('training_id', $training->id)
                ->whereIn('status', ['rejected', 'cancelled'])
                ->count();

            $summary[] = [
                'training' => $training->judul,
                'total' => $total,
                'approved' => $approved,
                'pending' => $pending,
                'rejected' => $rejected,
                'kapasitas' => $training->kapasitas,
            ];
        }

        return $summary;
    }

    /**
     * Export registrations to CSV.
     */
    public function export()
    {
        $registrations = TrainingRegistration::with(['user', 'training'])
            ->orderBy('created_at', 'desc')
            ->get();

        if ($registrations->isEmpty()) {
            return redirect()->route('admin.pendaftaran.index')
                ->with('warning', '⚠️ Tidak ada data pendaftaran untuk diexport.');
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
                'Tanggal Approve',
                'Catatan'
            ]);

            foreach ($registrations as $index => $reg) {
                fputcsv($handle, [
                    $index + 1,
                    $reg->user->nama ?? $reg->user->name ?? '-',
                    $reg->user->email ?? '-',
                    $reg->training->judul ?? '-',
                    $reg->registered_at ? $reg->registered_at->format('d/m/Y H:i') : ($reg->created_at ? $reg->created_at->format('d/m/Y H:i') : '-'),
                    $reg->status,
                    $reg->approved_at ? $reg->approved_at->format('d/m/Y H:i') : '-',
                    $reg->notes ?? '-'
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get training info for AJAX.
     */
    public function getTrainingInfo($id)
    {
        $training = Training::with(['trainer', 'kategori'])->findOrFail($id);
        $participantsCount = TrainingRegistration::where('training_id', $id)
            ->whereIn('status', ['approved', 'registered', 'completed'])
            ->count();
        
        return response()->json([
            'success' => true,
            'training' => [
                'id' => $training->id,
                'judul' => $training->judul,
                'deskripsi' => $training->deskripsi,
                'status' => $training->status,
                'tanggal_mulai' => $training->tanggal_mulai,
                'tanggal_selesai' => $training->tanggal_selesai,
                'kapasitas' => $training->kapasitas,
                'participants_count' => $participantsCount,
                'trainer' => $training->trainer ? [
                    'nama' => $training->trainer->nama ?? $training->trainer->name,
                ] : null,
            ]
        ]);
    }
}