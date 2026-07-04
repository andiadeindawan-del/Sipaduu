<?php

namespace App\Http\Controllers;

use App\Models\Training;
use App\Models\User;
use App\Models\Sertifikat;
use App\Models\TrainingRegistration;
use App\Models\QuizAttempt;
use App\Models\Materi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Dashboard Laporan
     */
    public function index()
    {
        $totalTrainings = Training::count();
        $totalUsers = User::where('role', 'peserta')->count();
        $totalCertificates = Sertifikat::count();
        $totalRegistrations = TrainingRegistration::count();
        $totalQuizAttempts = QuizAttempt::count();
        
        $trainingsByStatus = Training::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();
        
        $registrationsPerMonth = TrainingRegistration::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('count(*) as total')
            )
            ->whereYear('created_at', date('Y'))
            ->groupBy('year', 'month')
            ->orderBy('month')
            ->get();
        
        return view('admin.pendaftaran.index', compact(
            'totalTrainings',
            'totalUsers',
            'totalCertificates',
            'totalRegistrations',
            'totalQuizAttempts',
            'trainingsByStatus',
            'registrationsPerMonth'
        ));
    }

    /**
     * Laporan Pelatihan
     */
    public function trainings(Request $request)
    {
        $query = Training::with(['category', 'registrations', 'creator']);

        // Filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('start_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('end_date', '<=', $request->date_to);
        }

        $trainings = $query->orderBy('created_at', 'desc')->paginate(15);
        $categories = \App\Models\Category::all();

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
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.reports.users', compact('users'));
    }

    /**
     * Laporan Sertifikat
     */
    public function certificates(Request $request)
    {
        $query = Certificates::with(['user', 'training']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('training_id')) {
            $query->where('training_id', $request->training_id);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('issued_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('issued_at', '<=', $request->date_to);
        }

        $certificates = $query->orderBy('issued_at', 'desc')->paginate(15);
        $trainings = Training::all();

        return view('admin.reports.certificates', compact('certificates', 'trainings'));
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
        $query = QuizAttempts::with(['user', 'quiz']);

        if ($request->filled('quiz_id')) {
            $query->where('quiz_id', $request->quiz_id);
        }
        if ($request->filled('passed')) {
            $query->where('is_passed', $request->passed == '1');
        }

        $attempts = $query->orderBy('created_at', 'desc')->paginate(15);
        $quizzes = \App\Models\Quizzes::all();

        return view('admin.reports.quiz', compact('attempts', 'quizzes'));
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
                $items = Training::with(['category'])->get();
                foreach ($items as $index => $item) {
                    $data[] = [
                        $index + 1,
                        $item->title,
                        $item->category->name ?? '-',
                        $item->status,
                        $item->registrations()->count(),
                        $item->start_date ? $item->start_date->format('d/m/Y') : '-',
                        $item->end_date ? $item->end_date->format('d/m/Y') : '-'
                    ];
                }
                break;

            case 'users':
                $headers = ['No', 'Nama', 'Email', 'Status', 'Terdaftar'];
                $items = User::where('role', 'peserta')->get();
                foreach ($items as $index => $item) {
                    $data[] = [
                        $index + 1,
                        $item->name,
                        $item->email,
                        $item->status ?? 'active',
                        $item->created_at ? $item->created_at->format('d/m/Y') : '-'
                    ];
                }
                break;

            case 'certificates':
                $headers = ['No', 'Peserta', 'Pelatihan', 'Nomor Sertifikat', 'Tanggal Terbit', 'Status'];
                $items = Certificates::with(['user', 'training'])->get();
                foreach ($items as $index => $item) {
                    $data[] = [
                        $index + 1,
                        $item->user->name ?? '-',
                        $item->training->title ?? '-',
                        $item->certificate_number,
                        $item->issued_at ? $item->issued_at->format('d/m/Y') : '-',
                        $item->status
                    ];
                }
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
}