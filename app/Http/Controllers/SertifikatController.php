<?php

namespace App\Http\Controllers;

use App\Models\Sertifikat;
use App\Models\Training;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SertifikatController extends Controller
{
    /**
     * Display a listing of certificates.
     */
    public function index(Request $request): View
    {
        $query = Sertifikat::with(['user', 'training']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_sertifikat', 'like', "%$search%")
                  ->orWhere('nama_sertifikat', 'like', "%$search%")
                  ->orWhere('penerbit', 'like', "%$search%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $sertifikats = $query->latest()->paginate(15)->withQueryString();

        // Statistics
        $totalSertifikat = Sertifikat::count();
        $aktifCount = Sertifikat::where('status', 'aktif')->count();
        $revokedCount = Sertifikat::where('status', 'revoked')->count();
        $expiredCount = Sertifikat::where('status', 'expired')->count();

        return view('admin.sertifikat.index', compact(
            'sertifikats',
            'totalSertifikat',
            'aktifCount',
            'revokedCount',
            'expiredCount'
        ));
    }

    /**
     * Display a listing of certificates for peserta.
     */
    public function pesertaIndex(Request $request): View
    {
        $user = auth()->user();
        $userId = $user->id;

        $query = Sertifikat::with(['training'])
            ->where('user_id', $userId);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_sertifikat', 'like', "%$search%")
                  ->orWhere('nama_sertifikat', 'like', "%$search%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $sertifikats = $query->latest('tanggal_terbit')->paginate(12)->withQueryString();

        // Statistics
        $totalCertificates = Sertifikat::where('user_id', $userId)->count();
        $activeCertificates = Sertifikat::where('user_id', $userId)
            ->where('status', 'aktif')
            ->count();
        $expiredCertificates = Sertifikat::where('user_id', $userId)
            ->where('status', 'expired')
            ->count();

        return view('peserta.sertifikat.index', compact(
            'sertifikats',
            'totalCertificates',
            'activeCertificates',
            'expiredCertificates'
        ));
    }

    /**
     * Display the specified certificate for peserta.
     */
    public function pesertaShow($id): View
    {
        $user = auth()->user();
        
        $sertifikat = Sertifikat::with(['training'])
            ->where('user_id', $user->id)
            ->where('id', $id)
            ->firstOrFail();

        return view('peserta.sertifikat.show', compact('sertifikat'));
    }

    /**
     * Show the form for creating a new certificate.
     */
    public function create(): View
    {
        $users = User::where('status', 'aktif')->orderBy('nama')->get();
        $trainings = Training::where('status', 'published')
            ->orWhere('status', 'berjalan')
            ->orderBy('judul')
            ->get();

        return view('admin.sertifikat.create', compact('users', 'trainings'));
    }

    /**
     * Store a newly created certificate in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'training_id' => ['nullable', 'exists:trainings,id'],
            'nomor_sertifikat' => ['nullable', 'string', 'max:50', 'unique:sertifikats'],
            'nama_sertifikat' => ['required', 'string', 'max:150'],
            'deskripsi' => ['nullable', 'string'],
            'tanggal_terbit' => ['required', 'date'],
            'tanggal_berlaku_sampai' => ['nullable', 'date', 'after_or_equal:tanggal_terbit'],
            'penerbit' => ['required', 'string', 'max:100'],
            'file_path' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'tanda_tangan_digital' => ['nullable', 'string'],
            'catatan' => ['nullable', 'string'],
            'status' => ['required', 'in:aktif,revoked,expired,pending'],
        ]);

        // Generate nomor sertifikat if not provided
        if (empty($validated['nomor_sertifikat'])) {
            $validated['nomor_sertifikat'] = 'SRT-' . date('Y') . '-' . Str::upper(Str::random(8));
        }

        // Handle file upload
        if ($request->hasFile('file_path')) {
            $validated['file_path'] = $request->file('file_path')->store('sertifikats', 'public');
        }

        Sertifikat::create($validated);

        return redirect()->route('admin.sertifikat.index')
            ->with('success', 'Sertifikat berhasil ditambahkan.');
    }

    /**
     * Display the specified certificate.
     */
    public function show(Sertifikat $sertifikat): View
    {
        $sertifikat->load(['user', 'training']);

        return view('admin.sertifikat.show', compact('sertifikat'));
    }

    /**
     * Show the form for editing the specified certificate.
     */
    public function edit(Sertifikat $sertifikat): View
    {
        $users = User::where('status', 'aktif')->orderBy('nama')->get();
        $trainings = Training::orderBy('judul')->get();

        return view('admin.sertifikat.edit', compact('sertifikat', 'users', 'trainings'));
    }

    /**
     * Update the specified certificate in storage.
     */
    public function update(Request $request, Sertifikat $sertifikat)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'training_id' => ['nullable', 'exists:trainings,id'],
            'nomor_sertifikat' => ['required', 'string', 'max:50', 'unique:sertifikats,nomor_sertifikat,' . $sertifikat->id],
            'nama_sertifikat' => ['required', 'string', 'max:150'],
            'deskripsi' => ['nullable', 'string'],
            'tanggal_terbit' => ['required', 'date'],
            'tanggal_berlaku_sampai' => ['nullable', 'date', 'after_or_equal:tanggal_terbit'],
            'penerbit' => ['required', 'string', 'max:100'],
            'file_path' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'tanda_tangan_digital' => ['nullable', 'string'],
            'catatan' => ['nullable', 'string'],
            'status' => ['required', 'in:aktif,revoked,expired,pending'],
        ]);

        // Handle file upload
        if ($request->hasFile('file_path')) {
            // Delete old file
            if ($sertifikat->file_path) {
                Storage::disk('public')->delete($sertifikat->file_path);
            }
            $validated['file_path'] = $request->file('file_path')->store('sertifikats', 'public');
        }

        $sertifikat->update($validated);

        return redirect()->route('admin.sertifikat.index')
            ->with('success', 'Sertifikat berhasil diperbarui.');
    }

    /**
     * Remove the specified certificate from storage.
     */
    public function destroy(Sertifikat $sertifikat)
    {
        // Delete file if exists
        if ($sertifikat->file_path) {
            Storage::disk('public')->delete($sertifikat->file_path);
        }

        $sertifikat->delete();

        return redirect()->route('admin.sertifikat.index')
            ->with('success', 'Sertifikat berhasil dihapus.');
    }

    /**
     * Download certificate file.
     */
    public function download(Sertifikat $sertifikat)
    {
        // Check authorization
        $user = auth()->user();
        if ($user->role !== 'admin' && $user->id !== $sertifikat->user_id) {
            abort(403, 'Unauthorized access.');
        }

        if (!$sertifikat->file_path || !Storage::disk('public')->exists($sertifikat->file_path)) {
            return redirect()->back()->with('error', 'File sertifikat tidak ditemukan.');
        }

        $filename = 'Sertifikat-' . $sertifikat->nomor_sertifikat . '.' . pathinfo($sertifikat->file_path, PATHINFO_EXTENSION);

        return Storage::disk('public')->download($sertifikat->file_path, $filename);
    }

    /**
     * Display certificates of authenticated user.
     */
    public function userCertificates(): View
    {
        $user = auth()->user();
        
        $sertifikats = Sertifikat::where('user_id', $user->id)
            ->with('training')
            ->where('status', 'aktif')
            ->latest('tanggal_terbit')
            ->paginate(12);

        $totalCertificates = Sertifikat::where('user_id', $user->id)->count();
        $activeCertificates = Sertifikat::where('user_id', $user->id)
            ->where('status', 'aktif')
            ->count();

        return view('peserta.sertifikat.index', compact(
            'sertifikats',
            'totalCertificates',
            'activeCertificates'
        ));
    }

    /**
     * Verify certificate by number (public).
     */
    public function verify(Request $request)
    {
        $request->validate([
            'nomor_sertifikat' => 'required|string|exists:sertifikats,nomor_sertifikat'
        ]);

        $sertifikat = Sertifikat::where('nomor_sertifikat', $request->nomor_sertifikat)
            ->with(['user', 'training'])
            ->first();

        if (!$sertifikat) {
            return response()->json([
                'valid' => false,
                'message' => 'Sertifikat tidak ditemukan.'
            ], 404);
        }

        if ($sertifikat->status !== 'aktif') {
            return response()->json([
                'valid' => false,
                'message' => 'Sertifikat tidak aktif atau sudah kadaluarsa.',
                'status' => $sertifikat->status
            ], 400);
        }

        return response()->json([
            'valid' => true,
            'data' => [
                'nomor_sertifikat' => $sertifikat->nomor_sertifikat,
                'nama_sertifikat' => $sertifikat->nama_sertifikat,
                'nama_peserta' => $sertifikat->user->nama,
                'nama_training' => $sertifikat->training?->judul ?? '-',
                'tanggal_terbit' => $sertifikat->tanggal_terbit->format('d M Y'),
                'penerbit' => $sertifikat->penerbit,
            ]
        ]);
    }

    /**
     * Change certificate status.
     */
    public function changeStatus(Request $request, Sertifikat $sertifikat)
    {
        $request->validate([
            'status' => 'required|in:aktif,revoked,expired,pending'
        ]);

        $sertifikat->update(['status' => $request->status]);

        return redirect()->route('admin.sertifikat.index')
            ->with('success', 'Status sertifikat berhasil diperbarui.');
    }

    /**
     * Bulk delete certificates.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:sertifikats,id'
        ]);

        $sertifikats = Sertifikat::whereIn('id', $request->ids)->get();

        foreach ($sertifikats as $sertifikat) {
            if ($sertifikat->file_path) {
                Storage::disk('public')->delete($sertifikat->file_path);
            }
            $sertifikat->delete();
        }

        return redirect()->route('admin.sertifikat.index')
            ->with('success', count($request->ids) . ' sertifikat berhasil dihapus.');
    }

    /**
     * Export certificates to CSV.
     */
    public function export()
    {
        $sertifikats = Sertifikat::with(['user', 'training'])->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="sertifikats.csv"',
        ];

        $callback = function () use ($sertifikats) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'No. Sertifikat',
                'Nama Sertifikat',
                'Peserta',
                'Training',
                'Tanggal Terbit',
                'Penerbit',
                'Status'
            ]);

            foreach ($sertifikats as $sertifikat) {
                fputcsv($file, [
                    $sertifikat->nomor_sertifikat,
                    $sertifikat->nama_sertifikat,
                    $sertifikat->user->nama,
                    $sertifikat->training?->judul ?? '-',
                    $sertifikat->tanggal_terbit->format('d/m/Y'),
                    $sertifikat->penerbit,
                    $sertifikat->status,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}