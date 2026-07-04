<?php

namespace App\Http\Controllers;

use App\Models\TrainingRegistration;
use App\Models\Training;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TrainingRegistrationController extends Controller
{
    /**
     * Display a listing of the training registrations.
     */
    public function index(Request $request)
    {
        $query = TrainingRegistration::with(['user', 'training']);

        // Filter search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('nama', 'LIKE', "%{$search}%");
            })->orWhereHas('training', function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('judul', 'LIKE', "%{$search}%");
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
        $activeRegistrations = TrainingRegistration::where('status', 'approved')->count();
        $pendingRegistrations = TrainingRegistration::where('status', 'pending')->count();
        $cancelledRegistrations = TrainingRegistration::where('status', 'cancelled')->count();

        $trainings = Training::where('status', 'published')->get();

        return view('admin.pendaftaran.index', compact(
            'registrations',
            'totalRegistrations',
            'activeRegistrations',
            'pendingRegistrations',
            'cancelledRegistrations',
            'trainings'
        ));
    }

    /**
     * Show the form for creating a new registration.
     */
    public function create()
    {
        $users = User::where('role', 'peserta')->orderBy('name')->get();
        $trainings = Training::where('status', 'published')->orderBy('title')->get();
        
        return view('admin.pendaftaran.create', compact('users', 'trainings'));
    }

    /**
     * Store a newly created registration in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'training_id' => 'required|exists:trainings,id',
            'status' => 'required|in:pending,approved,rejected,cancelled',
            'notes' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Cek duplikat
        $exists = TrainingRegistration::where('user_id', $request->user_id)
            ->where('training_id', $request->training_id)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->with('error', 'Peserta sudah terdaftar di pelatihan ini!')
                ->withInput();
        }

        DB::beginTransaction();
        try {
            // Generate registration number
            $registrationNumber = TrainingRegistration::generateRegistrationNumber();

            $registration = TrainingRegistration::create([
                'user_id' => $request->user_id,
                'training_id' => $request->training_id,
                'registration_number' => $registrationNumber,
                'status' => $request->status,
                'registered_at' => now(),
                'notes' => $request->notes,
            ]);

            DB::commit();

            return redirect()->route('admin.pendaftaran.index')
                ->with('success', 'Pendaftaran berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
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

        $users = User::where('role', 'peserta')->orderBy('name')->get();
        $trainings = Training::where('status', 'published')->orderBy('title')->get();

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
            'status' => 'required|in:pending,approved,rejected,cancelled',
            'notes' => 'nullable|string|max:500',
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
                ->with('error', 'Peserta sudah terdaftar di pelatihan ini!')
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $registration->update([
                'user_id' => $request->user_id,
                'training_id' => $request->training_id,
                'status' => $request->status,
                'notes' => $request->notes,
            ]);

            DB::commit();

            return redirect()->route('admin.pendaftaran.index')
                ->with('success', 'Pendaftaran berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
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
                ->with('warning', 'Pendaftaran sudah disetujui sebelumnya.');
        }

        $registration->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        \Log::info('Pendaftaran disetujui', [
            'registration_id' => $id,
            'approved_by' => auth()->user()->name,
            'user_id' => $registration->user_id,
            'training_id' => $registration->training_id
        ]);

        return redirect()->route('admin.pendaftaran.index')
            ->with('success', 'Pendaftaran berhasil disetujui!');
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
                ->with('warning', 'Pendaftaran sudah ditolak sebelumnya.');
        }

        $registration->update([
            'status' => 'rejected',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
            'rejection_reason' => $request->reason,
        ]);

        \Log::info('Pendaftaran ditolak', [
            'registration_id' => $id,
            'rejected_by' => auth()->user()->name,
            'user_id' => $registration->user_id,
            'training_id' => $registration->training_id,
            'reason' => $request->reason
        ]);

        return redirect()->route('admin.pendaftaran.index')
            ->with('success', 'Pendaftaran berhasil ditolak!');
    }

    /**
     * Cancel registration.
     */
    public function cancel($id)
    {
        $registration = TrainingRegistration::findOrFail($id);

        if ($registration->status === 'cancelled') {
            return redirect()->back()
                ->with('warning', 'Pendaftaran sudah dibatalkan sebelumnya.');
        }

        $registration->update([
            'status' => 'cancelled',
        ]);

        return redirect()->route('admin.pendaftaran.index')
            ->with('success', 'Pendaftaran berhasil dibatalkan!');
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
                ->with('error', 'Pendaftaran tidak dapat dihapus karena sudah memiliki sertifikat!');
        }

        // Cek apakah sudah punya attempt quiz
        if ($registration->quizAttempts()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Pendaftaran tidak dapat dihapus karena sudah mengerjakan quiz!');
        }

        DB::beginTransaction();
        try {
            $registration->delete();
            DB::commit();

            return redirect()->route('admin.pendaftaran.index')
                ->with('success', 'Pendaftaran berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Export registrations to CSV.
     */
    public function export()
    {
        $registrations = TrainingRegistration::with(['user', 'training'])
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'pendaftaran_' . date('Y-m-d_His') . '.csv';

        $callback = function() use ($registrations) {
            $handle = fopen('php://output', 'w');
            
            // BOM for Excel
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header
            fputcsv($handle, [
                'No',
                'Nama Peserta',
                'Email',
                'Pelatihan',
                'Tanggal Daftar',
                'Status',
                'Tanggal Approve',
                'Approved By',
                'Catatan'
            ]);

            foreach ($registrations as $index => $reg) {
                fputcsv($handle, [
                    $index + 1,
                    $reg->user->name ?? $reg->user->nama ?? '-',
                    $reg->user->email ?? '-',
                    $reg->training->title ?? $reg->training->judul ?? '-',
                    $reg->registered_at ? $reg->registered_at->format('d/m/Y H:i') : ($reg->created_at ? $reg->created_at->format('d/m/Y H:i') : '-'),
                    $reg->status,
                    $reg->approved_at ? $reg->approved_at->format('d/m/Y H:i') : '-',
                    $reg->approver->name ?? '-',
                    $reg->notes ?? '-'
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Bulk approve registrations.
     */
    public function bulkApprove(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:training_registrations,id',
        ]);

        $count = 0;
        foreach ($request->ids as $id) {
            $registration = TrainingRegistration::find($id);
            if ($registration && $registration->status === 'pending') {
                $registration->update([
                    'status' => 'approved',
                    'approved_at' => now(),
                    'approved_by' => auth()->id(),
                ]);
                $count++;
            }
        }

        return redirect()->route('admin.pendaftaran.index')
            ->with('success', $count . ' pendaftaran berhasil disetujui!');
    }
}