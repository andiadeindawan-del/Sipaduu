<?php

namespace App\Http\Controllers;

use App\Models\Training;
use App\Models\Materi;
use App\Models\Quiz;
use App\Models\Sertifikat;
use App\Models\Kategori;
use App\Models\QuizAttempt;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PesertaController extends Controller
{
    /**
     * Display peserta dashboard.
     */
    public function index()
    {
        $user = auth()->user();
        $userId = $user->id;

        // ============================================================
        // STATISTICS
        // ============================================================
        
        // Total Training yang diikuti
        $totalTrainings = DB::table('training_registrations')
            ->where('user_id', $userId)
            ->whereIn('status', ['disetujui'])
            ->count();

        // Total Sertifikat yang didapat
        $totalCertificates = DB::table('sertifikats')
            ->where('user_id', $userId)
            ->count();

        // Total Quiz Attempts
        $totalQuizAttempts = DB::table('quiz_attempts')
            ->where('user_id', $userId)
            ->count();

        // Rata-rata nilai quiz
        $averageQuizScore = DB::table('quiz_attempts')
            ->where('user_id', $userId)
            ->where('status', 'completed')
            ->avg('score') ?? 0;

        // Total Materi
        $totalMaterials = Materi::whereHas('training.registrations', function($q) use ($userId) {
            $q->where('user_id', $userId)
              ->whereIn('status', ['disetujui']);
        })->count();

        // Materi yang sudah selesai
        $completedMaterials = DB::table('materi_progress')
            ->where('user_id', $userId)
            ->where('status', 'completed')
            ->count();

        // Total Quiz yang tersedia
        $totalQuizzes = Quiz::whereHas('training.registrations', function($q) use ($userId) {
            $q->where('user_id', $userId)
              ->whereIn('status', ['disetujui']);
        })->count();

        // Quiz yang sudah selesai
        $completedQuizzes = DB::table('quiz_attempts')
            ->where('user_id', $userId)
            ->where('status', 'completed')
            ->count();

        // Total Kehadiran
        $totalHadir = DB::table('absensis')
            ->where('user_id', $userId)
            ->where('status', 'hadir')
            ->count();

        // ============================================================
        // ACTIVE TRAININGS - TANPA PROGRESS
        // ============================================================
        $activeTrainings = DB::table('training_registrations')
            ->join('trainings', 'training_registrations.training_id', '=', 'trainings.id')
            ->leftJoin('kategoris', 'trainings.kategori_id', '=', 'kategoris.id')
            ->where('training_registrations.user_id', $userId)
            ->whereIn('training_registrations.status', ['disetujui'])
            ->where('trainings.tanggal_mulai', '<=', now())
            ->where('trainings.tanggal_selesai', '>=', now())
            ->where('trainings.status', 'published')
            ->orderBy('trainings.tanggal_mulai', 'asc')
            ->select(
                'trainings.*',
                'training_registrations.status as registration_status',
                'kategoris.nama as kategori_nama'
            )
            ->limit(5)
            ->get();

        // Hitung progress untuk setiap training
        foreach ($activeTrainings as $training) {
            $totalMateri = DB::table('materis')
                ->where('training_id', $training->id)
                ->count();
            
            $completedMateri = DB::table('materi_progress')
                ->where('user_id', $userId)
                ->whereIn('materi_id', function($q) use ($training) {
                    $q->select('id')
                      ->from('materis')
                      ->where('training_id', $training->id);
                })
                ->where('status', 'completed')
                ->count();
            
            $training->progress = $totalMateri > 0 ? round(($completedMateri / $totalMateri) * 100) : 0;
        }

        // ============================================================
        // RECENT CERTIFICATES
        // ============================================================
        $recentCertificates = DB::table('sertifikats')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // ============================================================
        // RECENT QUIZ ATTEMPTS
        // ============================================================
        $recentQuizAttempts = QuizAttempt::with(['quiz'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // ============================================================
        // RECENT ACTIVITIES
        // ============================================================
        $recentActivities = collect();

        // Aktivitas dari quiz attempts
        $quizActivities = DB::table('quiz_attempts')
            ->join('quizzes', 'quiz_attempts.quiz_id', '=', 'quizzes.id')
            ->where('quiz_attempts.user_id', $userId)
            ->orderBy('quiz_attempts.created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($item) {
                return (object) [
                    'title' => 'Mengerjakan Quiz',
                    'description' => 'Quiz: ' . ($item->judul ?? 'Quiz'),
                    'icon' => 'bi-question-circle',
                    'color' => 'primary',
                    'time' => \Carbon\Carbon::parse($item->created_at)->diffForHumans(),
                    'created_at' => $item->created_at,
                ];
            });

        // Aktivitas dari progress materi
        $materiActivities = DB::table('materi_progress')
            ->join('materis', 'materi_progress.materi_id', '=', 'materis.id')
            ->where('materi_progress.user_id', $userId)
            ->where('materi_progress.status', 'completed')
            ->orderBy('materi_progress.updated_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($item) {
                return (object) [
                    'title' => 'Menyelesaikan Materi',
                    'description' => 'Materi: ' . ($item->judul ?? 'Materi'),
                    'icon' => 'bi-book-check',
                    'color' => 'success',
                    'time' => \Carbon\Carbon::parse($item->updated_at)->diffForHumans(),
                    'created_at' => $item->updated_at,
                ];
            });

        // Aktivitas dari sertifikat
        $certificateActivities = DB::table('sertifikats')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($item) {
                return (object) [
                    'title' => 'Mendapatkan Sertifikat',
                    'description' => 'Sertifikat: ' . ($item->judul ?? 'Sertifikat'),
                    'icon' => 'bi-award',
                    'color' => 'warning',
                    'time' => \Carbon\Carbon::parse($item->created_at)->diffForHumans(),
                    'created_at' => $item->created_at,
                ];
            });

        // Gabungkan semua aktivitas dan urutkan
        $recentActivities = $quizActivities
            ->concat($materiActivities)
            ->concat($certificateActivities)
            ->sortByDesc('created_at')
            ->take(10);

        // ============================================================
        // KATEGORI untuk filter
        // ============================================================
        $kategoris = Kategori::orderBy('nama')->get();

        // ============================================================
        // AVAILABLE TRAININGS
        // ============================================================
        $availableTrainings = Training::with(['kategori', 'registrations' => function($q) use ($userId) {
            $q->where('user_id', $userId);
        }])
        ->where('status', 'published')
        ->where('tanggal_mulai', '>=', now())
        ->orderBy('tanggal_mulai', 'asc')
        ->limit(6)
        ->get();

        // ============================================================
        // COMPLETED TRAININGS (untuk progress)
        // ============================================================
        $completedTrainings = DB::table('training_registrations')
            ->where('user_id', $userId)
            ->where('status', 'completed')
            ->count();

        $ongoingTrainings = DB::table('training_registrations')
            ->join('trainings', 'training_registrations.training_id', '=', 'trainings.id')
            ->where('training_registrations.user_id', $userId)
            ->whereIn('training_registrations.status', ['disetujui'])
            ->where('trainings.tanggal_mulai', '<=', now())
            ->where('trainings.tanggal_selesai', '>=', now())
            ->count();

        // ============================================================
        // UPCOMING TRAININGS
        // ============================================================
        $upcomingTrainings = DB::table('training_registrations')
            ->join('trainings', 'training_registrations.training_id', '=', 'trainings.id')
            ->where('training_registrations.user_id', $userId)
            ->whereIn('training_registrations.status', ['disetujui', 'pending'])
            ->where('trainings.tanggal_mulai', '>', now())
            ->where('trainings.status', 'published')
            ->orderBy('trainings.tanggal_mulai', 'asc')
            ->limit(5)
            ->select('trainings.*', 'training_registrations.status as registration_status')
            ->get();

        // ============================================================
        // PROGRESS RATA-RATA - HITUNG DARI MATERI DAN QUIZ
        // ============================================================
        $totalItems = $totalMaterials + $totalQuizzes;
        $completedItems = $completedMaterials + $completedQuizzes;
        $averageProgress = $totalItems > 0 ? round(($completedItems / $totalItems) * 100) : 0;

        return view('peserta.index', compact(
            'totalTrainings',
            'totalCertificates',
            'totalQuizAttempts',
            'averageQuizScore',
            'totalMaterials',
            'completedMaterials',
            'totalQuizzes',
            'completedQuizzes',
            'totalHadir',
            'activeTrainings',
            'recentCertificates',
            'recentQuizAttempts',
            'recentActivities',
            'kategoris',
            'availableTrainings',
            'completedTrainings',
            'ongoingTrainings',
            'upcomingTrainings',
            'averageProgress'
        ));
    }

    /**
     * Display peserta materi.
     */
    public function materi()
    {
        $user = auth()->user();
        $userId = $user->id;

        $materis = Materi::with(['kategori', 'training'])
            ->whereHas('training.registrations', function($q) use ($userId) {
                $q->where('user_id', $userId)
                  ->whereIn('status', ['disetujui']);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        // Progress per materi
        foreach ($materis as $materi) {
            $materi->progress = DB::table('materi_progress')
                ->where('materi_id', $materi->id)
                ->where('user_id', $userId)
                ->value('progress') ?? 0;
            
            $materi->status_progress = DB::table('materi_progress')
                ->where('materi_id', $materi->id)
                ->where('user_id', $userId)
                ->value('status') ?? 'not_started';
        }

        $totalMaterials = Materi::whereHas('training.registrations', function($q) use ($userId) {
            $q->where('user_id', $userId)
              ->whereIn('status', ['disetujui']);
        })->count();

        $completedMaterials = DB::table('materi_progress')
            ->where('user_id', $userId)
            ->where('status', 'completed')
            ->count();

        $kategoris = Kategori::orderBy('nama')->get();

        return view('peserta.materi.index', compact(
            'materis',
            'totalMaterials',
            'completedMaterials',
            'kategoris'
        ));
    }

    /**
     * Display detail materi.
     */
    public function materiShow($id)
    {
        $user = auth()->user();
        $userId = $user->id;

        $materi = Materi::with(['kategori', 'training'])
            ->whereHas('training.registrations', function($q) use ($userId) {
                $q->where('user_id', $userId)
                  ->whereIn('status', ['disetujui']);
            })
            ->findOrFail($id);

        $materi->progress = DB::table('materi_progress')
            ->where('materi_id', $materi->id)
            ->where('user_id', $userId)
            ->value('progress') ?? 0;

        $materi->status_progress = DB::table('materi_progress')
            ->where('materi_id', $materi->id)
            ->where('user_id', $userId)
            ->value('status') ?? 'not_started';

        return view('peserta.materi.show', compact('materi'));
    }

    /**
     * Display peserta quiz.
     */
    public function quiz()
    {
        $user = auth()->user();
        $userId = $user->id;

        $quizzes = Quiz::with(['training'])
            ->whereHas('training.registrations', function($q) use ($userId) {
                $q->where('user_id', $userId)
                  ->whereIn('status', ['disetujui']);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Get attempt status for each quiz
        foreach ($quizzes as $quiz) {
            $attempt = DB::table('quiz_attempts')
                ->where('quiz_id', $quiz->id)
                ->where('user_id', $userId)
                ->orderBy('created_at', 'desc')
                ->first();

            $quiz->attempt_status = $attempt ? $attempt->status : 'not_started';
            $quiz->attempt_score = $attempt ? $attempt->score : null;
            $quiz->attempt_id = $attempt ? $attempt->id : null;
        }

        $totalQuizzes = Quiz::whereHas('training.registrations', function($q) use ($userId) {
            $q->where('user_id', $userId)
              ->whereIn('status', ['disetujui']);
        })->count();

        $completedQuizzes = DB::table('quiz_attempts')
            ->where('user_id', $userId)
            ->where('status', 'completed')
            ->count();

        return view('peserta.quiz.index', compact(
            'quizzes',
            'totalQuizzes',
            'completedQuizzes'
        ));
    }

    /**
     * Display detail quiz.
     */
    public function quizShow($id)
    {
        $user = auth()->user();
        $userId = $user->id;

        $quiz = Quiz::with(['training', 'questions'])
            ->whereHas('training.registrations', function($q) use ($userId) {
                $q->where('user_id', $userId)
                  ->whereIn('status', ['disetujui']);
            })
            ->findOrFail($id);

        $attempt = DB::table('quiz_attempts')
            ->where('quiz_id', $quiz->id)
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->first();

        $quiz->attempt_status = $attempt ? $attempt->status : 'not_started';
        $quiz->attempt_id = $attempt ? $attempt->id : null;
        
        $totalQuestions = $quiz->questions()->count();
        $userAttempts = DB::table('quiz_attempts')->where('quiz_id', $quiz->id)->where('user_id', $userId)->count();
        $remainingAttempts = max(0, $quiz->max_attempt - $userAttempts);
        $userBestScore = DB::table('quiz_attempts')->where('quiz_id', $quiz->id)->where('user_id', $userId)->max('score');
        
        $userAttempt = DB::table('quiz_attempts')
            ->where('quiz_id', $quiz->id)
            ->where('user_id', $userId)
            ->where('status', 'completed')
            ->first();

        $hasAbsensi = true;
        if ($quiz->training_id) {
            $hasAbsensi = \App\Models\Absensi::where('user_id', $userId)
                ->where('training_id', $quiz->training_id)
                ->where('status', 'hadir')
                ->exists();
        }

        return view('peserta.quiz.show', compact('quiz', 'attempt', 'totalQuestions', 'remainingAttempts', 'userBestScore', 'userAttempt', 'hasAbsensi'));
    }

    /**
     * Display peserta sertifikat.
     * PERBAIKAN: Mengirim variabel $sertifikats (bukan $certificates)
     */
    public function sertifikat()
    {
        $user = auth()->user();
        $userId = $user->id;

        $sertifikats = DB::table('sertifikats')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $totalCertificates = DB::table('sertifikats')
            ->where('user_id', $userId)
            ->count();

        return view('peserta.sertifikat.index', compact(
            'sertifikats',
            'totalCertificates'
        ));
    }

    /**
     * Display detail sertifikat.
     */
    public function sertifikatShow($id)
    {
        $user = auth()->user();
        $userId = $user->id;

        $sertifikat = DB::table('sertifikats')
            ->where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$sertifikat) {
            abort(404, 'Sertifikat tidak ditemukan');
        }

        return view('peserta.sertifikat.show', compact('sertifikat'));
    }

    /**
     * Download sertifikat.
     */
    public function sertifikatDownload($id)
    {
        $user = auth()->user();
        $userId = $user->id;

        $sertifikat = DB::table('sertifikats')
            ->where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$sertifikat) {
            abort(404, 'Sertifikat tidak ditemukan');
        }

        // Cek apakah ada file
        if (isset($sertifikat->file_path) && $sertifikat->file_path) {
            $path = storage_path('app/public/' . $sertifikat->file_path);
            if (file_exists($path)) {
                return response()->download($path, $sertifikat->judul . '.pdf');
            }
        }

        // Jika tidak ada file, generate PDF
        return $this->generateCertificatePDF($sertifikat);
    }

    /**
     * Generate certificate PDF.
     */
    private function generateCertificatePDF($sertifikat)
    {
        // Logika generate PDF sertifikat
        // return PDF::loadView('peserta.sertifikat.pdf', compact('sertifikat'))->download($sertifikat->judul . '.pdf');
        
        // Sementara redirect ke halaman show
        return redirect()->route('peserta.sertifikat.show', $sertifikat->id)
                        ->with('info', 'Sertifikat dapat diunduh dari halaman ini.');
    }

    /**
     * Display peserta absen.
     */
    public function absen()
    {
        $user = auth()->user();
        $userId = $user->id;

        $riwayatAbsensi = \App\Models\Absensi::with('training')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $totalHadir = DB::table('absensis')
            ->where('user_id', $userId)
            ->where('status', 'hadir')
            ->count();

        $totalIzin = DB::table('absensis')
            ->where('user_id', $userId)
            ->where('status', 'izin')
            ->count();

        $totalAlpha = DB::table('absensis')
            ->where('user_id', $userId)
            ->where('status', 'alpha')
            ->count();

        // Training yang tersedia untuk absen
        $availableTrainings = Training::with('absensis')
            ->whereHas('registrations', function($q) use ($userId) {
            $q->where('user_id', $userId)
              ->whereIn('status', ['disetujui']);
        })
        ->where('status', 'published')
        ->paginate(10);

        return view('peserta.absen.index', compact(
            'riwayatAbsensi',
            'totalHadir',
            'totalIzin',
            'totalAlpha',
            'availableTrainings'
        ));
    }

    /**
     * Store absen peserta.
     */
    public function absenStore(Request $request)
    {
        $request->validate([
            'training_id' => 'required|exists:trainings,id',
            'tanggal' => 'required|date',
            'status' => 'required|in:hadir,izin,alpha',
            'keterangan' => 'nullable|string|max:500',
        ]);

        $user = auth()->user();
        $userId = $user->id;

        // Cek apakah user terdaftar di training
        $isRegistered = DB::table('training_registrations')
            ->where('user_id', $userId)
            ->where('training_id', $request->training_id)
            ->whereIn('status', ['disetujui'])
            ->exists();

        if (!$isRegistered) {
            return back()->with('error', '❌ Anda tidak terdaftar di training ini.');
        }

        // Cek duplikasi absen
        $exists = DB::table('absensis')
            ->where('user_id', $userId)
            ->where('training_id', $request->training_id)
            ->whereDate('tanggal', $request->tanggal)
            ->exists();

        if ($exists) {
            return back()->with('error', '❌ Anda sudah melakukan absen pada tanggal ini.');
        }

        DB::table('absensis')->insert([
            'user_id' => $userId,
            'training_id' => $request->training_id,
            'tanggal' => $request->tanggal,
            'status' => $request->status,
            'keterangan' => $request->keterangan,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', '✅ Absen berhasil dicatat.');
    }

    /**
     * Display peserta profile.
     */
    public function profile()
    {
        $user = auth()->user();
        
        $totalTrainings = DB::table('training_registrations')
            ->where('user_id', $user->id)
            ->whereIn('status', ['disetujui'])
            ->count();

        $completedTrainings = DB::table('training_registrations')
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();

        $totalCertificates = DB::table('sertifikats')
            ->where('user_id', $user->id)
            ->count();

        $averageScore = DB::table('quiz_attempts')
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->avg('score') ?? 0;

        $totalMateri = DB::table('materi_progress')
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();

        $totalQuiz = DB::table('quiz_attempts')
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();

        return view('peserta.profile.index', compact(
            'user',
            'totalTrainings',
            'completedTrainings',
            'totalCertificates',
            'averageScore',
            'totalMateri',
            'totalQuiz'
        ));
    }

    /**
     * Update profile peserta.
     */
    public function profileUpdate(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->route('peserta.profile.index')
                        ->with('success', '✅ Profil berhasil diperbarui.');
    }

    /**
     * Update password peserta.
     */
    public function profilePassword(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|min:8|confirmed',
        ]);

        $user->update([
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('peserta.profile.index')
                        ->with('success', '✅ Password berhasil diperbarui.');
    }

    /**
     * Upload avatar peserta.
     */
    public function profileAvatar(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'avatar' => 'required|image|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = time() . '_' . $user->id . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('avatars', $filename, 'public');

            // Hapus avatar lama
            if ($user->avatar && file_exists(storage_path('app/public/' . $user->avatar))) {
                unlink(storage_path('app/public/' . $user->avatar));
            }

            $user->update(['avatar' => $path]);
        }

        return redirect()->route('peserta.profile.index')
                        ->with('success', '✅ Avatar berhasil diperbarui.');
    }

    /**
     * Display peserta training list.
     */
    public function trainings(Request $request)
    {
        $user = auth()->user();
        $userId = $user->id;

        $query = DB::table('training_registrations')
            ->join('trainings', 'training_registrations.training_id', '=', 'trainings.id')
            ->leftJoin('kategoris', 'trainings.kategori_id', '=', 'kategoris.id')
            ->where('training_registrations.user_id', $userId)
            ->whereIn('training_registrations.status', ['pending', 'disetujui']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('training_registrations.status', $request->status);
        }

        // Filter by kategori
        if ($request->filled('kategori_id')) {
            $query->where('trainings.kategori_id', $request->kategori_id);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('trainings.judul', 'like', "%$search%")
                  ->orWhere('trainings.deskripsi', 'like', "%$search%");
            });
        }

        $trainings = $query->select(
                'trainings.*',
                'training_registrations.status as registration_status',
                'kategoris.nama as kategori_nama'
            )
            ->orderBy('training_registrations.created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        // Tambahkan progress untuk setiap training
        foreach ($trainings as $training) {
            $totalMateri = DB::table('materis')
                ->where('training_id', $training->id)
                ->count();
            
            $completedMateri = DB::table('materi_progress')
                ->where('user_id', $userId)
                ->whereIn('materi_id', function($q) use ($training) {
                    $q->select('id')
                      ->from('materis')
                      ->where('training_id', $training->id);
                })
                ->where('status', 'completed')
                ->count();
            
            $training->progress = $totalMateri > 0 ? round(($completedMateri / $totalMateri) * 100) : 0;
        }

        // Statistics
        $totalTrainings = DB::table('training_registrations')
            ->where('user_id', $userId)
            ->whereIn('status', ['disetujui'])
            ->count();

        $ongoingTrainings = DB::table('training_registrations')
            ->join('trainings', 'training_registrations.training_id', '=', 'trainings.id')
            ->where('training_registrations.user_id', $userId)
            ->whereIn('training_registrations.status', ['disetujui'])
            ->where('trainings.tanggal_mulai', '<=', now())
            ->where('trainings.tanggal_selesai', '>=', now())
            ->count();

        $completedTrainings = DB::table('training_registrations')
            ->where('user_id', $userId)
            ->where('status', 'completed')
            ->count();

        $pendingTrainings = DB::table('training_registrations')
            ->where('user_id', $userId)
            ->where('status', 'pending')
            ->count();

        $kategoris = Kategori::orderBy('nama')->get();

        return view('peserta.trainings.index', compact(
            'trainings',
            'totalTrainings',
            'ongoingTrainings',
            'completedTrainings',
            'pendingTrainings',
            'kategoris'
        ));
    }

    /**
     * Display detail training peserta.
     */
    public function trainingShow($id)
    {
        $user = auth()->user();
        $userId = $user->id;

        $training = Training::with(['kategori', 'trainer', 'materis'])
            ->whereHas('registrations', function($q) use ($userId) {
                $q->where('user_id', $userId)
                  ->whereIn('status', ['disetujui']);
            })
            ->findOrFail($id);

        // Hitung progress
        $totalMateri = $training->materis->count();
        $completedMateri = DB::table('materi_progress')
            ->where('user_id', $userId)
            ->whereIn('materi_id', $training->materis->pluck('id'))
            ->where('status', 'completed')
            ->count();
        
        $progress = $totalMateri > 0 ? round(($completedMateri / $totalMateri) * 100) : 0;

        // Status registrasi
        $registration = DB::table('training_registrations')
            ->where('user_id', $userId)
            ->where('training_id', $id)
            ->first();

        return view('peserta.trainings.show', compact('training', 'progress', 'registration'));
    }

    /**
     * Display list of dokumentasi for enrolled trainings.
     */
    public function dokumentasi(Request $request)
    {
        $user = auth()->user();
        $userId = $user->id;

        // Get trainings the user is enrolled in
        $enrolledTrainingIds = DB::table('training_registrations')
            ->where('user_id', $userId)
            ->whereIn('status', ['disetujui', 'selesai']) // assume disetujui or selesai
            ->pluck('training_id');

        $query = \App\Models\Dokumentasi::with('training')
            ->whereIn('training_id', $enrolledTrainingIds);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kategori_id')) {
             $kategoriId = $request->kategori_id;
             $query->whereHas('training', function($q) use ($kategoriId) {
                 $q->where('kategori_id', $kategoriId);
             });
        }

        $dokumentasis = $query->orderBy('created_at', 'desc')->paginate(12);

        $kategoris = \App\Models\Kategori::orderBy('nama')->get();
        $trainings = \App\Models\Training::whereIn('id', $enrolledTrainingIds)->orderBy('judul')->get();

        return view('peserta.dokumentasi.index', compact('dokumentasis', 'kategoris', 'trainings'));
    }

    /**
     * Display list of surveys for enrolled trainings.
     */
    public function survey(Request $request)
    {
        $user = auth()->user();
        $userId = $user->id;

        // Get trainings the user is enrolled in
        $enrolledTrainingIds = DB::table('training_registrations')
            ->where('user_id', $userId)
            ->whereIn('status', ['disetujui', 'selesai'])
            ->pluck('training_id');

        // Check trainings the user has actually attended
        $attendedTrainingIds = DB::table('absensis')
            ->where('user_id', $userId)
            ->where('status', 'hadir')
            ->whereIn('training_id', $enrolledTrainingIds)
            ->distinct()
            ->pluck('training_id');

        // Also include those whose status is explicitly marked as 'selesai'
        $completedTrainingIds = DB::table('training_registrations')
            ->where('user_id', $userId)
            ->where('status', 'selesai')
            ->pluck('training_id');

        $validTrainingIds = $attendedTrainingIds->merge($completedTrainingIds)->unique();

        $query = \App\Models\Survey::with(['training', 'responses' => function($q) use ($userId) {
                $q->where('user_id', $userId);
            }])
            ->whereIn('training_id', $validTrainingIds)
            ->where('status', 'published');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kategori_id')) {
             $kategoriId = $request->kategori_id;
             $query->whereHas('training', function($q) use ($kategoriId) {
                 $q->where('kategori_id', $kategoriId);
             });
        }

        $surveys = $query->orderBy('created_at', 'desc')->paginate(12);

        $kategoris = \App\Models\Kategori::orderBy('nama')->get();
        $trainings = \App\Models\Training::whereIn('id', $validTrainingIds)->orderBy('judul')->get();

        return view('peserta.survey.index', compact('surveys', 'kategoris', 'trainings'));
    }

    /**
     * Show survey form.
     */
    public function surveyShow($id)
    {
        $user = auth()->user();
        
        $survey = \App\Models\Survey::with(['training', 'questions'])->findOrFail($id);

        // Check if user has already responded
        $hasResponded = \App\Models\SurveyResponse::where('survey_id', $id)
            ->where('user_id', $user->id)
            ->exists();

        if ($hasResponded) {
            return redirect()->route('peserta.survey.index')->with('info', 'Anda sudah mengisi survey ini.');
        }

        return view('peserta.survey.show', compact('survey'));
    }

    /**
     * Submit survey answers.
     */
    public function surveySubmit(Request $request, $id)
    {
        $user = auth()->user();
        
        $survey = \App\Models\Survey::findOrFail($id);

        $hasResponded = \App\Models\SurveyResponse::where('survey_id', $id)
            ->where('user_id', $user->id)
            ->exists();

        if ($hasResponded) {
            return redirect()->route('peserta.survey.index')->with('error', 'Anda sudah mengisi survey ini sebelumnya.');
        }

        $answers = $request->input('answers', []);

        \App\Models\SurveyResponse::create([
            'survey_id' => $survey->id,
            'user_id' => $user->id,
            'answers' => $answers,
            'status' => 'completed',
        ]);

        return redirect()->route('peserta.survey.index')->with('success', 'Terima kasih, survey berhasil dikirim.');
    }

    /**
     * Scan QR Code Absensi
     */
    public function scanQR(Request $request, \App\Models\Training $training)
    {
        // Require auth
        if (!auth()->check()) {
            return redirect()->route('login')->with('warning', 'Silakan login terlebih dahulu untuk melakukan absensi.');
        }

        $user = auth()->user();
        $token = $request->query('token');

        $status = 'error';
        $message = 'Terjadi kesalahan sistem.';

        // 1. Check token and if session is open
        if (!$training->is_absen_open || $training->absen_token !== $token) {
            $message = 'Sesi absensi untuk pelatihan ini belum dibuka atau sudah ditutup.';
            return view('peserta.absen.scan_result', compact('training', 'status', 'message'));
        }

        // 2. Check if user is registered and approved
        $registration = \App\Models\TrainingRegistration::where('training_id', $training->id)
            ->where('user_id', $user->id)
            ->first();

        if (!$registration || $registration->status !== 'disetujui') {
            $message = 'Anda tidak terdaftar atau belum mendapatkan persetujuan untuk mengikuti pelatihan ini.';
            return view('peserta.absen.scan_result', compact('training', 'status', 'message'));
        }

        // 3. Check for duplicate attendance today
        $today = now()->format('Y-m-d');
        $existingAbsen = Absensi::where('training_id', $training->id)
            ->where('user_id', $user->id)
            ->whereDate('tanggal', $today)
            ->first();

        if ($existingAbsen) {
            $message = 'Anda sudah melakukan absensi untuk pelatihan ini hari ini.';
            return view('peserta.absen.scan_result', compact('training', 'status', 'message'));
        }

        // 4. Record attendance
        Absensi::create([
            'user_id' => $user->id,
            'training_id' => $training->id,
            'tanggal' => $today,
            'jam_masuk' => now()->format('H:i:s'),
            'waktu_checkin' => now(),
            'status' => 'hadir',
            'metode' => 'QR Code',
        ]);

        $status = 'success';
        $message = 'Berhasil! Anda telah tercatat hadir pada pelatihan ini.';
        
        return view('peserta.absen.scan_result', compact('training', 'status', 'message'));
    }
}
