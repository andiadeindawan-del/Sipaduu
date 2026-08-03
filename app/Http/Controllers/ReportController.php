<?php

namespace App\Http\Controllers;

use App\Models\Training;
use App\Models\User;
use App\Models\Sertifikat;
use App\Models\TrainingRegistration;
use App\Models\QuizAttempt;
use App\Models\Materi;
use App\Models\Quiz;
use App\Models\Category;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Dashboard Laporan
     */
    public function index(Request $request)
    {
        // Total data
        $totalTrainings = Training::count();
        $totalParticipants = User::where('role', 'peserta')->count();
        $totalCertificates = Sertifikat::count();
        $totalQuizzes = Quiz::count();
        $totalMateri = Materi::count();

        // Growth data
        $lastMonth = now()->subMonth();
        $trainingGrowth = Training::where('created_at', '>=', $lastMonth)->count();
        $participantGrowth = User::where('role', 'peserta')->where('created_at', '>=', $lastMonth)->count();
        $certificateGrowth = Sertifikat::where('created_at', '>=', $lastMonth)->count();
        $quizGrowth = Quiz::where('created_at', '>=', $lastMonth)->count();

        // Data untuk chart
        $chartData = [
            'trainings' => $this->getMonthlyData(Training::class, 'created_at'),
            'participants' => $this->getMonthlyData(User::class, 'created_at', [['role', 'peserta']]),
            'certificates' => $this->getMonthlyData(Sertifikat::class, 'created_at'),
            'totalTrainings' => $totalTrainings,
            'totalParticipants' => $totalParticipants,
            'totalCertificates' => $totalCertificates,
            'totalMateri' => $totalMateri,
            'totalQuizzes' => $totalQuizzes,
        ];

        // Data untuk tabel
        $trainings = Training::with(['kategori', 'registrations'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // PERBAIKAN: Menggunakan select raw untuk menghitung jumlah pelatihan
        $participants = User::where('role', 'peserta')
            ->select('users.*', DB::raw('(SELECT COUNT(*) FROM training_registrations WHERE training_registrations.user_id = users.id) as trainings_count'))
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $certificates = Sertifikat::with(['user', 'training'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $registrations = TrainingRegistration::with(['user', 'training'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.laporan.index', compact(
            'totalTrainings',
            'totalParticipants',
            'totalCertificates',
            'totalQuizzes',
            'totalMateri',
            'trainingGrowth',
            'participantGrowth',
            'certificateGrowth',
            'quizGrowth',
            'chartData',
            'trainings',
            'participants',
            'certificates',
            'registrations'
        ));
    }

    /**
     * Laporan Pelatihan
     */
    public function trainings(Request $request)
    {
        $query = Training::with(['kategori', 'registrations', 'creator']);

        // Filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('category_id')) {
            $query->where('kategori_id', $request->category_id);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('tanggal_mulai', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('tanggal_selesai', '<=', $request->date_to);
        }

        $trainings = $query->orderBy('created_at', 'desc')->paginate(15);
        $categories = Category::all();

        return view('admin.reports.trainings', compact('trainings', 'categories'));
    }

    /**
     * Laporan Pengguna (Peserta)
     */
    public function users(Request $request)
    {
        $query = User::where('role', 'peserta');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('nama', 'LIKE', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.laporan.users', compact('users'));
    }


    /**
     * Laporan Materi
     */
    public function materi(Request $request)
    {
        $query = Materi::with(['training']);

        if ($request->filled('training_id')) {
            $query->where('training_id', $request->training_id);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $materis = $query->orderBy('created_at', 'desc')->paginate(15);
        $trainings = Training::all();

        return view('admin.reports.materi', compact('materis', 'trainings'));
    }

    /**
     * Laporan Quiz
     */
    public function quiz(Request $request)
    {
        $query = QuizAttempt::with(['user', 'quiz']);

        if ($request->filled('quiz_id')) {
            $query->where('quiz_id', $request->quiz_id);
        }
        if ($request->filled('passed')) {
            $query->where('is_passed', $request->passed == '1');
        }

        $attempts = $query->orderBy('created_at', 'desc')->paginate(15);
        $quizzes = Quiz::all();

        return view('admin.reports.quiz', compact('attempts', 'quizzes'));
    }

    /**
     * Laporan Pendaftaran
     */
    public function registrations(Request $request)
    {
        $query = TrainingRegistration::with(['user', 'training']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('training_id')) {
            $query->where('training_id', $request->training_id);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $registrations = $query->orderBy('created_at', 'desc')->paginate(15);
        $trainings = Training::all();

        return view('admin.reports.registrations', compact('registrations', 'trainings'));
    }

    /**
     * Export Report
     */
    public function export($type, Request $request)
    {
        $filename = 'laporan_' . $type . '_' . date('Y-m-d_His') . '.csv';

        $data = [];
        $headers = [];

        switch ($type) {
            case 'trainings':
                $headers = ['No', 'Judul', 'Kategori', 'Status', 'Peserta', 'Tanggal Mulai', 'Tanggal Selesai'];
                $items = Training::with(['kategori'])->get();
                foreach ($items as $index => $item) {
                    $data[] = [
                        $index + 1,
                        $item->judul,
                        $item->kategori->nama ?? '-',
                        $item->status ?? 'draft',
                        $item->registrations()->count(),
                        $item->tanggal_mulai ? $item->tanggal_mulai->format('d/m/Y') : '-',
                        $item->tanggal_selesai ? $item->tanggal_selesai->format('d/m/Y') : '-'
                    ];
                }
                break;

            case 'participants':
            case 'users':
                $headers = ['No', 'Nama', 'Email', 'Status', 'Terdaftar'];
                $items = User::where('role', 'peserta')->get();
                foreach ($items as $index => $item) {
                    $data[] = [
                        $index + 1,
                        $item->nama ?? $item->name,
                        $item->email,
                        $item->status ?? 'active',
                        $item->created_at ? $item->created_at->format('d/m/Y') : '-'
                    ];
                }
                break;


            case 'registrations':
                $headers = ['No', 'Peserta', 'Pelatihan', 'Tanggal Daftar', 'Status'];
                $items = TrainingRegistration::with(['user', 'training'])->get();
                foreach ($items as $index => $item) {
                    $data[] = [
                        $index + 1,
                        $item->user->nama ?? $item->user->name ?? '-',
                        $item->training->judul ?? '-',
                        $item->created_at ? $item->created_at->format('d/m/Y') : '-',
                        $item->status ?? 'pending'
                    ];
                }
                break;

            case 'materi':
                $headers = ['No', 'Judul Materi', 'Pelatihan', 'Tipe', 'Tanggal Dibuat'];
                $items = Materi::with(['training'])->get();
                foreach ($items as $index => $item) {
                    $data[] = [
                        $index + 1,
                        $item->judul ?? $item->title,
                        $item->training->judul ?? '-',
                        $item->type ?? 'file',
                        $item->created_at ? $item->created_at->format('d/m/Y') : '-'
                    ];
                }
                break;

            case 'quiz':
                $headers = ['No', 'Quiz', 'Peserta', 'Skor', 'Lulus', 'Tanggal'];
                $items = QuizAttempt::with(['user', 'quiz'])->get();
                foreach ($items as $index => $item) {
                    $data[] = [
                        $index + 1,
                        $item->quiz->judul ?? '-',
                        $item->user->nama ?? $item->user->name ?? '-',
                        $item->score ?? 0,
                        $item->is_passed ? 'Ya' : 'Tidak',
                        $item->created_at ? $item->created_at->format('d/m/Y') : '-'
                    ];
                }
                break;

            case 'all':
                $headers = ['No', 'Jenis', 'Judul/Nama', 'Status', 'Tanggal'];
                $allData = [];
                $counter = 1;

                $trainings = Training::all();
                foreach ($trainings as $item) {
                    $allData[] = [
                        $counter++,
                        'Pelatihan',
                        $item->judul,
                        $item->status ?? 'draft',
                        $item->created_at ? $item->created_at->format('d/m/Y') : '-'
                    ];
                }

                $users = User::where('role', 'peserta')->get();
                foreach ($users as $item) {
                    $allData[] = [
                        $counter++,
                        'Peserta',
                        $item->nama ?? $item->name,
                        $item->status ?? 'active',
                        $item->created_at ? $item->created_at->format('d/m/Y') : '-'
                    ];
                }

                $certificates = Sertifikat::all();
                foreach ($certificates as $item) {
                    $allData[] = [
                        $counter++,
                        'Sertifikat',
                        $item->nomor_sertifikat,
                        $item->status ?? 'active',
                        $item->created_at ? $item->created_at->format('d/m/Y') : '-'
                    ];
                }

                $registrations = TrainingRegistration::all();
                foreach ($registrations as $item) {
                    $allData[] = [
                        $counter++,
                        'Pendaftaran',
                        $item->user->nama ?? $item->user->name ?? '-',
                        $item->status ?? 'pending',
                        $item->created_at ? $item->created_at->format('d/m/Y') : '-'
                    ];
                }

                $data = $allData;
                break;

            default:
                return redirect()->back()->with('error', 'Tipe laporan tidak valid');
        }

        $callback = function() use ($headers, $data) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($handle, $headers);
            foreach ($data as $row) {
                fputcsv($handle, $row);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Get monthly data for chart
     */
    private function getMonthlyData($model, $column = 'created_at', $conditions = [])
    {
        $data = [];
        $months = [];

        for ($i = 5; $i >= 0; $i--) {
            $months[] = now()->subMonths($i)->format('Y-m');
        }

        $query = $model::query();
        foreach ($conditions as $condition) {
            if (count($condition) == 2) {
                $query->where($condition[0], $condition[1]);
            } elseif (count($condition) == 3) {
                $query->where($condition[0], $condition[1], $condition[2]);
            }
        }

        $results = $query->select(
                DB::raw("DATE_FORMAT({$column}, '%Y-%m') as month"),
                DB::raw('count(*) as total')
            )
            ->where($column, '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        foreach ($months as $month) {
            $data[] = $results[$month] ?? 0;
        }

        return $data;
    }
}