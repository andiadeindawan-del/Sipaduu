<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Training;
use App\Models\Kategori;
use App\Models\User;
use App\Models\Absensi;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TrainingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Training::with(['kategori', 'trainer'])
            ->withCount('participants');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%$search%")
                  ->orWhere('deskripsi', 'like', "%$search%")
                  ->orWhere('lokasi', 'like', "%$search%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('trainings.status', $request->status);
        }

        // Filter by kategori
        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        // Filter by type
        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }

        $trainings = $query->latest()->paginate(10)->withQueryString();

        // Statistics
        $totalTrainings = Training::count();
        $publishedTrainings = Training::where('status', 'published')->count();
        $draftTrainings = Training::where('status', 'draft')->count();
        $ongoingTrainings = Training::where('status', 'berjalan')->count();
        $completedTrainings = Training::where('status', 'selesai')->count();
        $upcomingTrainings = Training::where('status', 'published')
            ->where('tanggal_mulai', '>=', now())
            ->count();

        // For filters
        $kategoris = Kategori::all();

        return view('admin.trainings.index', compact(
            'trainings',
            'totalTrainings',
            'publishedTrainings',
            'draftTrainings',
            'ongoingTrainings',
            'completedTrainings',
            'upcomingTrainings',
            'kategoris'
        ));
    }

    /**
     * Display a listing of trainings for peserta.
     */
    public function pesertaIndex(Request $request)
    {
        $user = auth()->user();
        $userId = $user->id;

        $query = Training::with(['kategori', 'trainer', 'participants'])
            ->withCount('participants');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%$search%")
                  ->orWhere('deskripsi', 'like', "%$search%");
            });
        }

        // Filter by kategori
        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        // Filter by status
        $filter = $request->get('filter');
        if ($filter === 'ongoing') {
            $query->whereHas('participants', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })->whereIn('trainings.status', ['published', 'berjalan']);
        } elseif ($filter === 'upcoming') {
            $query->whereHas('participants', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })->where('trainings.tanggal_mulai', '>', now());
        } elseif ($filter === 'completed') {
            $query->whereHas('participants', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })->where('trainings.status', 'selesai');
        } else {
            // Show all trainings that user can access
            $query->where(function($q) use ($userId) {
                $q->whereHas('participants', function($q2) use ($userId) {
                    $q2->where('user_id', $userId);
                })->orWhere('trainings.status', 'published');
            });
        }

        $trainings = $query->orderBy('tanggal_mulai', 'asc')->paginate(12)->withQueryString();

        // Statistics
        $totalTrainings = Training::whereHas('participants', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })->count();

        $ongoingTrainings = Training::whereHas('participants', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })->whereIn('trainings.status', ['published', 'berjalan'])->count();

        $upcomingTrainings = Training::whereHas('participants', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })->where('trainings.tanggal_mulai', '>', now())->count();

        $completedTrainings = Training::whereHas('participants', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })->where('trainings.status', 'selesai')->count();

        // Kategori untuk filter
        $kategoris = Kategori::all();

        return view('peserta.trainings.index', compact(
            'trainings',
            'totalTrainings',
            'ongoingTrainings',
            'upcomingTrainings',
            'completedTrainings',
            'kategoris'
        ));
    }

    /**
     * Display riwayat pelatihan for peserta.
     */
    public function history(Request $request)
    {
        $user = auth()->user();
        $userId = $user->id;

        $query = Training::with(['kategori', 'trainer'])
            ->whereHas('participants', function($q) use ($userId) {
                $q->where('user_id', $userId)
                  ->whereIn('training_participants.status', ['completed', 'selesai', 'finished']);
            });

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%$search%")
                  ->orWhere('deskripsi', 'like', "%$search%");
            });
        }

        // Filter by kategori
        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        // Filter by tahun
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_selesai', $request->tahun);
        }

        $trainings = $query->orderBy('tanggal_selesai', 'desc')
                          ->paginate(10)
                          ->withQueryString();

        // Statistics untuk riwayat
        $totalHistory = Training::whereHas('participants', function($q) use ($userId) {
            $q->where('user_id', $userId)
              ->whereIn('training_participants.status', ['completed', 'selesai', 'finished']);
        })->count();

        // Hitung rata-rata progress
        $totalProgress = 0;
        foreach ($trainings as $training) {
            $totalProgress += $this->calculateProgress($training, $userId);
        }
        $avgProgress = $totalHistory > 0 ? round($totalProgress / $totalHistory) : 0;

        // Ambil tahun untuk filter
        $tahunList = Training::whereHas('participants', function($q) use ($userId) {
            $q->where('user_id', $userId)
              ->whereIn('training_participants.status', ['completed', 'selesai', 'finished']);
        })->selectRaw('YEAR(tanggal_selesai) as tahun')
          ->distinct()
          ->orderBy('tahun', 'desc')
          ->pluck('tahun');

        $kategoris = Kategori::all();

        return view('peserta.trainings.history', compact(
            'trainings',
            'totalHistory',
            'avgProgress',
            'tahunList',
            'kategoris'
        ));
    }

    /**
     * Calculate progress for a training.
     */
    private function calculateProgress($training, $userId)
    {
        $totalMaterials = $training->materis()->count();
        $completedMaterials = $training->materis()
            ->whereHas('progress', function($q) use ($userId) {
                $q->where('user_id', $userId)->where('status', 'completed');
            })->count();
        
        $totalQuizzes = $training->quizzes()->count();
        $completedQuizzes = $training->quizzes()
            ->whereHas('attempts', function($q) use ($userId) {
                $q->where('user_id', $userId)->where('status', 'completed');
            })->count();
        
        $totalItems = $totalMaterials + $totalQuizzes;
        $completedItems = $completedMaterials + $completedQuizzes;
        
        return $totalItems > 0 ? round(($completedItems / $totalItems) * 100) : 0;
    }

    /**
     * Display the specified training for peserta.
     */
    public function pesertaShow(Training $training)
    {
        $user = auth()->user();
        $userId = $user->id;
        
        // Check if training is published or user is enrolled
        if ($training->status !== 'published' && !$training->participants()->where('user_id', $userId)->exists()) {
            abort(404);
        }
        
        // Check if user is enrolled
        $isEnrolled = $training->participants()->where('user_id', $userId)->exists();
        
        // Check if user has completed this training
        $isCompleted = $training->participants()
            ->where('user_id', $userId)
            ->wherePivot('status', 'completed')
            ->exists();

        // Get progress
        $progress = 0;
        if ($isEnrolled) {
            $progress = $this->calculateProgress($training, $userId);
        }

        // Get absensi
        $absensi = Absensi::where('user_id', $userId)
            ->where('training_id', $training->id)
            ->first();

        $training->load(['kategori', 'trainer', 'materis', 'quizzes']);
        
        $participantsCount = $training->participants()->count();
        $availableSlots = $training->kapasitas ? $training->kapasitas - $participantsCount : null;

        return view('peserta.trainings.show', compact(
            'training',
            'isEnrolled',
            'isCompleted',
            'progress',
            'participantsCount',
            'availableSlots',
            'absensi'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategoris = Kategori::all();
        $trainers = User::where('role', 'trainer')->get();
        
        return view('admin.trainings.create', compact('kategoris', 'trainers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategoris,id',
            'trainer_id' => 'nullable|exists:users,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tipe' => 'required|in:online,offline,hybrid',
            'lokasi' => 'nullable|string|max:255',
            'link_meeting' => 'nullable|url|max:255',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'kapasitas' => 'nullable|integer|min:1',
            'status' => 'required|in:draft,published,berjalan,selesai,dibatalkan',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // Handle file upload
        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('trainings', 'public');
        }

        Training::create($validated);

        return redirect()->route('admin.trainings.index')
                        ->with('success', '✅ Pelatihan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Training $training)
    {
        $training->load(['kategori', 'trainer', 'participants']);
        
        $participantsCount = $training->participants()->count();
        $availableSlots = $training->kapasitas ? $training->kapasitas - $participantsCount : null;
        
        return view('admin.trainings.show', compact('training', 'participantsCount', 'availableSlots'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Training $training)
    {
        $kategoris = Kategori::all();
        $trainers = User::where('role', 'trainer')->get();
        
        return view('admin.trainings.edit', compact('training', 'kategoris', 'trainers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Training $training)
    {
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategoris,id',
            'trainer_id' => 'nullable|exists:users,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tipe' => 'required|in:online,offline,hybrid',
            'lokasi' => 'nullable|string|max:255',
            'link_meeting' => 'nullable|url|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'kapasitas' => 'nullable|integer|min:1',
            'status' => 'required|in:draft,published,berjalan,selesai,dibatalkan',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // Handle file upload
        if ($request->hasFile('gambar')) {
            if ($training->gambar) {
                Storage::disk('public')->delete($training->gambar);
            }
            $validated['gambar'] = $request->file('gambar')->store('trainings', 'public');
        }

        $training->update($validated);

        return redirect()->route('admin.trainings.index')
                        ->with('success', '✅ Pelatihan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Training $training)
    {
        // Check if training has participants
        $participantsCount = $training->participants()->count();
        if ($participantsCount > 0) {
            return redirect()->route('admin.trainings.index')
                            ->with('error', "⚠️ Pelatihan tidak dapat dihapus karena masih memiliki {$participantsCount} peserta.");
        }

        // Delete image if exists
        if ($training->gambar) {
            Storage::disk('public')->delete($training->gambar);
        }

        $training->delete();

        return redirect()->route('admin.trainings.index')
                        ->with('success', '✅ Pelatihan berhasil dihapus.');
    }

    /**
     * Change training status.
     */
    public function changeStatus(Request $request, Training $training)
    {
        $request->validate([
            'status' => 'required|in:draft,published,berjalan,selesai,dibatalkan'
        ]);

        $oldStatus = $training->status;
        $training->update(['status' => $request->status]);

        $statusLabels = [
            'draft' => 'Draft',
            'published' => 'Published',
            'berjalan' => 'Berjalan',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan'
        ];

        return redirect()->route('admin.trainings.index')
                        ->with('success', "✅ Status pelatihan berhasil diubah dari {$statusLabels[$oldStatus]} menjadi {$statusLabels[$request->status]}.");
    }

    /**
     * Get participants list for a training.
     */
    public function participants(Training $training)
    {
        $training->load('participants');
        $participants = $training->participants()->paginate(15);
        
        return view('admin.trainings.participants', compact('training', 'participants'));
    }

    /**
     * Export training participants to CSV.
     */
    public function export(Training $training)
    {
        $participants = $training->participants()->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="peserta-' . Str::slug($training->judul) . '.csv"',
        ];

        $callback = function () use ($training, $participants) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, [
                'No',
                'Nama Peserta',
                'Email',
                'NIK',
                'Role',
                'Status Pendaftaran',
                'Tanggal Daftar'
            ]);

            $no = 1;
            foreach ($participants as $participant) {
                fputcsv($file, [
                    $no++,
                    $participant->nama,
                    $participant->email,
                    $participant->nik ?? '-',
                    ucfirst($participant->role),
                    $participant->pivot->status ?? 'registered',
                    $participant->pivot->created_at ? $participant->pivot->created_at->format('d/m/Y H:i') : '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get available trainings for peserta.
     */
    public function available()
    {
        $trainings = Training::where('status', 'published')
            ->where('tanggal_mulai', '>=', now())
            ->with(['kategori', 'trainer'])
            ->withCount('participants')
            ->latest()
            ->paginate(12);

        return view('peserta.trainings.available', compact('trainings'));
    }

    /**
     * Enroll user to training.
     */
    public function enroll(Request $request, Training $training)
    {
        $user = auth()->user();

        if ($training->participants()->where('user_id', $user->id)->exists()) {
            return redirect()->back()
                            ->with('error', '⚠️ Anda sudah terdaftar dalam pelatihan ini.');
        }

        if ($training->kapasitas && $training->participants()->count() >= $training->kapasitas) {
            return redirect()->back()
                            ->with('error', '⚠️ Kuota pelatihan sudah penuh.');
        }

        if ($training->status !== 'published' || $training->tanggal_mulai < now()) {
            return redirect()->back()
                            ->with('error', '⚠️ Pelatihan sudah tidak tersedia.');
        }

        $training->participants()->attach($user->id, [
            'status' => 'registered',
            'registered_at' => now()
        ]);

        return redirect()->back()
                        ->with('success', '✅ Berhasil mendaftar pelatihan.');
    }

    /**
     * Unenroll user from training.
     */
    public function unenroll(Request $request, Training $training)
    {
        $user = auth()->user();

        if (!$training->participants()->where('user_id', $user->id)->exists()) {
            return redirect()->back()
                            ->with('error', '⚠️ Anda tidak terdaftar dalam pelatihan ini.');
        }

        $training->participants()->detach($user->id);

        return redirect()->back()
                        ->with('success', '✅ Berhasil membatalkan pendaftaran pelatihan.');
    }

    /**
     * Complete training for user.
     */
    public function complete(Request $request, Training $training)
    {
        $user = auth()->user();

        if (!$training->participants()->where('user_id', $user->id)->exists()) {
            return redirect()->back()
                            ->with('error', '⚠️ Anda tidak terdaftar dalam pelatihan ini.');
        }

        // Check if all requirements are met
        $progress = $this->calculateProgress($training, $user->id);
        if ($progress < 100) {
            return redirect()->back()
                            ->with('error', '⚠️ Anda harus menyelesaikan semua materi dan quiz terlebih dahulu.');
        }

        $training->participants()->updateExistingPivot($user->id, [
            'status' => 'completed',
            'completed_at' => now()
        ]);

        return redirect()->back()
                        ->with('success', '✅ Pelatihan berhasil diselesaikan!');
    }

    /**
     * Get training progress for user.
     */
    public function progress(Training $training)
    {
        $user = auth()->user();
        
        $isEnrolled = $training->participants()->where('user_id', $user->id)->exists();
        
        if (!$isEnrolled) {
            return response()->json([
                'success' => false,
                'error' => 'Anda tidak terdaftar dalam pelatihan ini.'
            ], 403);
        }

        $progress = $this->calculateProgress($training, $user->id);

        return response()->json([
            'success' => true,
            'progress' => $progress,
            'is_completed' => $progress >= 100
        ]);
    }

    /**
     * Get training by ID for peserta (AJAX).
     */
    public function getTraining($id)
    {
        $training = Training::with(['kategori', 'trainer', 'materis', 'quizzes'])
            ->withCount('participants')
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'training' => $training
        ]);
    }
}