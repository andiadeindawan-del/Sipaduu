<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Training;
use App\Models\Sertifikat;
use App\Models\Materi;
use App\Models\Kategori;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\TrainingRegistration;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the dashboard based on user role.
     */
    public function index(): View
    {
        $user = auth()->user();

        return match ($user->role) {
            'admin' => $this->adminDashboard(),
            'trainer' => $this->trainerDashboard(),
            'peserta' => $this->pesertaDashboard(),
            default => $this->pesertaDashboard(),
        };
    }

    /**
     * Admin Dashboard.
     */
    public function adminDashboard(): View
    {
        // ============================================================
        // STATISTICS
        // ============================================================
        $totalUsers = User::count();
        $totalTrainings = Training::count();
        $totalCertificates = Sertifikat::count();
        $totalCategories = Kategori::count();
        $totalMateri = Materi::count();
        $totalQuizzes = Quiz::count();

        // Status counts
        $activeUsers = User::where('status', 'aktif')->count();
        $inactiveUsers = User::where('status', 'nonaktif')->count();
        $trainerCount = User::where('role', 'trainer')->count();
        $pesertaCount = User::where('role', 'peserta')->count();

        // Training status
        $publishedTrainings = Training::where('status', 'published')->count();
        $draftTrainings = Training::where('status', 'draft')->count();
        $completedTrainings = Training::where('status', 'selesai')->count();
        $upcomingTrainings = Training::where('status', 'published')
            ->where('tanggal_mulai', '>=', now())
            ->count();
        $ongoingTrainings = Training::where('status', 'berjalan')->count();

        // Certificate status
        $activeCertificates = Sertifikat::where('status', 'aktif')->count();
        $pendingCertificates = Sertifikat::where('status', 'pending')->count();
        $revokedCertificates = Sertifikat::where('status', 'revoked')->count();

        // Quiz statistics
        $totalQuizAttempts = QuizAttempt::count();
        $completedQuizAttempts = QuizAttempt::where('status', 'completed')->count();
        $averageQuizScore = QuizAttempt::where('status', 'completed')->avg('score') ?? 0;

        // ============================================================
        // PENDAFTARAN MENUNGGU KONFIRMASI
        // ============================================================
        $pendingRegistrations = TrainingRegistration::with(['user', 'training'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Statistik pendaftaran
        $totalPending = TrainingRegistration::where('status', 'pending')->count();
        $totalApproved = TrainingRegistration::where('status', 'disetujui')->count();
        $totalRejected = TrainingRegistration::where('status', 'ditolak')->count();
        $totalCancelled = TrainingRegistration::where('status', 'dibatalkan')->count();

        // ============================================================
        // PELATIHAN BERJALAN
        // ============================================================
        $ongoingTrainingsList = Training::where('status', 'berjalan')
            ->orWhere(function($q) {
                $q->where('status', 'published')
                  ->where('tanggal_mulai', '<=', now())
                  ->where('tanggal_selesai', '>=', now());
            })
            ->orderBy('tanggal_mulai', 'asc')
            ->limit(5)
            ->get();

        // ============================================================
        // AKTIVITAS TERBARU
        // ============================================================
        $recentActivities = collect();

        // Aktivitas dari pendaftaran
        $registrationActivities = TrainingRegistration::with(['user', 'training'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($item) {
                return [
                    'title' => 'Pendaftaran Baru',
                    'description' => ($item->user->nama ?? $item->user->name) . ' mendaftar ' . ($item->training->judul ?? 'Pelatihan'),
                    'icon' => 'bi-person-plus',
                    'color' => 'primary',
                    'time' => $item->created_at->diffForHumans(),
                ];
            });

        // Aktivitas dari user baru
        $userActivities = User::orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($item) {
                return [
                    'title' => 'User Baru',
                    'description' => ($item->nama ?? $item->name) . ' bergabung sebagai ' . ($item->role ?? 'User'),
                    'icon' => 'bi-person',
                    'color' => 'success',
                    'time' => $item->created_at->diffForHumans(),
                ];
            });

        $recentActivities = $registrationActivities->concat($userActivities)
            ->sortByDesc('time')
            ->take(10);

        // ============================================================
        // RINGKASAN HARI INI
        // ============================================================
        $todayRegistrations = TrainingRegistration::whereDate('created_at', now()->toDateString())->count();
        $todayAttendance = 0; // Sesuaikan dengan model absensi
        $todayMaterials = Materi::whereDate('created_at', now()->toDateString())->count();
        $todayCertificates = Sertifikat::whereDate('created_at', now()->toDateString())->count();

        // ============================================================
        // PENGGUNA TERBARU
        // ============================================================
        $recentUsers = User::orderBy('created_at', 'desc')->limit(10)->get();

        // ============================================================
        // RECENT DATA
        // ============================================================
        $recentTrainings = Training::with('kategori')->latest()->limit(5)->get();
        $recentCertificates = Sertifikat::with('user')->latest()->limit(5)->get();

        // ============================================================
        // CHART DATA
        // ============================================================
        $chartLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
        $chartData = [
            'users' => $this->getMonthlyData(User::class, 'created_at'),
            'trainings' => $this->getMonthlyData(Training::class, 'created_at'),
            'certificates' => $this->getMonthlyData(Sertifikat::class, 'created_at'),
        ];

        return view('admin.index', compact(
            'totalUsers',
            'totalTrainings',
            'totalCertificates',
            'totalCategories',
            'totalMateri',
            'totalQuizzes',
            'totalQuizAttempts',
            'completedQuizAttempts',
            'averageQuizScore',
            'activeUsers',
            'inactiveUsers',
            'trainerCount',
            'pesertaCount',
            'publishedTrainings',
            'draftTrainings',
            'completedTrainings',
            'upcomingTrainings',
            'ongoingTrainings',
            'activeCertificates',
            'pendingCertificates',
            'revokedCertificates',
            'pendingRegistrations',
            'totalPending',
            'totalApproved',
            'totalRejected',
            'totalCancelled',
            'ongoingTrainingsList',
            'recentActivities',
            'todayRegistrations',
            'todayAttendance',
            'todayMaterials',
            'todayCertificates',
            'recentUsers',
            'recentTrainings',
            'recentCertificates',
            'chartLabels',
            'chartData'
        ));
    }

    /**
     * Trainer Dashboard.
     */
    public function trainerDashboard(): View
    {
        $user = auth()->user();
        $trainerId = $user->id;

        // Trainer's trainings
        $totalTrainings = Training::where('trainer_id', $trainerId)->count();
        $publishedTrainings = Training::where('trainer_id', $trainerId)
            ->where('status', 'published')
            ->count();
        $completedTrainings = Training::where('trainer_id', $trainerId)
            ->where('status', 'selesai')
            ->count();

        // Participants
        $totalParticipants = Training::where('trainer_id', $trainerId)
            ->withCount('participants')
            ->get()
            ->sum('participants_count');

        // Upcoming trainings
        $upcomingTrainings = Training::where('trainer_id', $trainerId)
            ->where('status', 'published')
            ->where('tanggal_mulai', '>=', now())
            ->orderBy('tanggal_mulai', 'asc')
            ->limit(5)
            ->get();

        // Recent trainings
        $recentTrainings = Training::where('trainer_id', $trainerId)
            ->latest()
            ->limit(5)
            ->get();

        return view('peserta.index', compact(
            'totalTrainings',
            'publishedTrainings',
            'completedTrainings',
            'totalParticipants',
            'upcomingTrainings',
            'recentTrainings'
        ));
    }

    /**
     * Peserta Dashboard.
     */
    public function pesertaDashboard(): View
    {
        $user = auth()->user();
        $userId = $user->id;

        // ============================================================
        // STATISTICS
        // ============================================================
        
        // Total trainings enrolled
        $totalTrainings = Training::whereHas('participants', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->count();

        // Completed trainings
        $completedTrainings = Training::whereHas('participants', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->where('trainings.status', 'selesai')->count();

        // Ongoing trainings
        $ongoingTrainings = Training::whereHas('participants', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->where(function ($query) {
            $query->where('trainings.status', 'berjalan')
                  ->orWhere('trainings.status', 'published');
        })->count();

        // Total certificates
        $totalCertificates = Sertifikat::where('user_id', $userId)->count();
        
        // Active certificates
        $activeCertificates = Sertifikat::where('user_id', $userId)
            ->where('status', 'aktif')
            ->count();

        // ============================================================
        // QUIZ STATISTICS
        // ============================================================
        
        // Total quiz attempts
        $totalQuizAttempts = QuizAttempt::where('user_id', $userId)->count();
        
        // Completed quiz attempts
        $completedQuizAttempts = QuizAttempt::where('user_id', $userId)
            ->where('status', 'completed')
            ->count();
        
        // Average quiz score
        $averageQuizScore = QuizAttempt::where('user_id', $userId)
            ->where('status', 'completed')
            ->avg('score') ?? 0;

        // ============================================================
        // RECENT DATA
        // ============================================================
        
        // Upcoming trainings
        $upcomingTrainings = Training::whereHas('participants', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->where('trainings.tanggal_mulai', '>=', now())
            ->orderBy('trainings.tanggal_mulai', 'asc')
            ->limit(5)
            ->get();

        // Recent certificates
        $recentCertificates = Sertifikat::where('user_id', $userId)
            ->latest()
            ->limit(5)
            ->get();

        // Recent quiz attempts
        $recentQuizAttempts = QuizAttempt::where('user_id', $userId)
            ->with('quiz')
            ->latest()
            ->limit(5)
            ->get();

        // Available trainings (not enrolled yet)
        $availableTrainings = Training::whereIn('status', ['published', 'selesai', 'berjalan'])
            ->whereDoesntHave('participants', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->limit(5)
            ->get();

        // Active trainings (for the card)
        $activeTrainings = Training::whereHas('participants', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->where(function ($query) {
            $query->where('trainings.status', 'berjalan')
                  ->orWhere('trainings.status', 'published');
        })->orderBy('trainings.tanggal_mulai', 'asc')
        ->limit(5)
        ->get();

        // ============================================================
        // PROFILE DATA
        // ============================================================
        
        // User data with relations
        $userData = User::with(['sertifikats', 'trainingDiikuti'])->find($userId);

        // ============================================================
        // ALL TRAININGS (untuk view peserta/index.blade.php)
        // ============================================================
        $trainings = Training::where('status', 'published')
            ->orderBy('trainings.tanggal_mulai', 'asc')
            ->limit(6)
            ->get();

        return view('peserta.index', compact(
            'userData',
            'totalTrainings',
            'completedTrainings',
            'ongoingTrainings',
            'totalCertificates',
            'activeCertificates',
            'totalQuizAttempts',
            'completedQuizAttempts',
            'averageQuizScore',
            'upcomingTrainings',
            'recentCertificates',
            'recentQuizAttempts',
            'availableTrainings',
            'activeTrainings',
            'trainings'
        ));
    }

    /**
     * Get monthly data for charts.
     */
    private function getMonthlyData($model, $dateColumn = 'created_at'): array
    {
        $data = [];
        $months = 6;

        for ($i = $months - 1; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $start = $date->copy()->startOfMonth();
            $end = $date->copy()->endOfMonth();

            $count = $model::whereBetween($dateColumn, [$start, $end])->count();
            $data[] = $count;
        }

        return $data;
    }

    /**
     * Global search functionality.
     */
    public function search(Request $request)
    {
        $query = $request->get('q');

        if (empty($query) || strlen($query) < 3) {
            return redirect()->back()->with('error', 'Minimal 3 karakter untuk mencari.');
        }

        $users = User::where('nama', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->orWhere('nik', 'like', "%{$query}%")
            ->limit(5)
            ->get();

        $trainings = Training::where('judul', 'like', "%{$query}%")
            ->orWhere('deskripsi', 'like', "%{$query}%")
            ->with('kategori')
            ->limit(5)
            ->get();

        $certificates = Sertifikat::where('nomor_sertifikat', 'like', "%{$query}%")
            ->orWhere('nama_sertifikat', 'like', "%{$query}%")
            ->with('user')
            ->limit(5)
            ->get();

        $materis = Materi::where('judul', 'like', "%{$query}%")
            ->orWhere('deskripsi', 'like', "%{$query}%")
            ->with('kategori')
            ->limit(5)
            ->get();

        return view('admin.search-results', compact(
            'query',
            'users',
            'trainings',
            'certificates',
            'materis'
        ));
    }
}