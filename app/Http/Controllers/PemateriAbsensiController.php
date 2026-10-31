<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Training;
use App\Models\TrainingRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PemateriAbsensiController extends Controller
{
    /**
     * Tampilkan daftar pelatihan dan rekap absensi untuk pemateri.
     */
    public function index(Request $request): View
    {
        $userId = Auth::id();
        $today = now()->format('Y-m-d');

        // Pelatihan yang diajar oleh pemateri ini
        $myTrainings = Training::where('trainer_id', $userId)
            ->with(['kategori'])
            ->withCount(['registrations as approved_participants_count' => function ($q) {
                $q->where('status', 'disetujui');
            }])
            ->orderBy('tanggal_mulai', 'desc')
            ->get();

        $myTrainingIds = $myTrainings->pluck('id');

        // Query riwayat absensi peserta di kelas pemateri ini
        $query = Absensi::whereIn('training_id', $myTrainingIds)
            ->with(['user', 'training']);

        if ($request->filled('training_id')) {
            $query->where('training_id', $request->training_id);
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $absensis = $query->orderBy('tanggal', 'desc')
                          ->orderBy('jam_masuk', 'desc')
                          ->paginate(15)
                          ->withQueryString();

        // Statistik
        $totalTrainings = $myTrainings->count();
        $activeSessions = $myTrainings->where('is_absen_open', true)->count();
        $totalHadirToday = Absensi::whereIn('training_id', $myTrainingIds)
            ->whereDate('tanggal', $today)
            ->where('status', 'hadir')
            ->count();
        $totalAbsensiAll = Absensi::whereIn('training_id', $myTrainingIds)->count();

        return view('pemateri.absen.index', compact(
            'myTrainings',
            'absensis',
            'totalTrainings',
            'activeSessions',
            'totalHadirToday',
            'totalAbsensiAll',
            'today'
        ));
    }

    /**
     * Tampilkan halaman sesi absensi QR Code untuk pelatihan tertentu.
     */
    public function session(Training $training): View
    {
        if ($training->trainer_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk absensi pelatihan ini.');
        }

        // Ambil semua peserta yang sudah disetujui
        $participants = $training->registrations()
            ->with('user')
            ->where('status', 'disetujui')
            ->get();

        $today = now()->format('Y-m-d');

        // Cek absensi hari ini untuk peserta-peserta ini
        $absensiHariIni = Absensi::where('training_id', $training->id)
            ->whereDate('tanggal', $today)
            ->get()
            ->keyBy('user_id');

        $hadirCount = 0;
        foreach ($participants as $participant) {
            $absen = $absensiHariIni->get($participant->user_id);
            $participant->absen_status = $absen ? $absen->status : 'Belum Hadir';
            $participant->waktu_absen = $absen ? ($absen->waktu_checkin ?? $absen->jam_masuk) : null;
            $participant->metode_absen = $absen ? $absen->metode : null;
            if ($participant->absen_status === 'hadir') {
                $hadirCount++;
            }
        }

        $belumHadirCount = $participants->count() - $hadirCount;
        $persentase = $participants->count() > 0 ? round(($hadirCount / $participants->count()) * 100, 1) : 0;

        return view('pemateri.absen.session', compact(
            'training',
            'participants',
            'hadirCount',
            'belumHadirCount',
            'persentase',
            'today'
        ));
    }

    /**
     * Buka sesi absensi dan generate token QR Code baru.
     */
    public function startSession(Training $training)
    {
        if ($training->trainer_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk membuka absensi pelatihan ini.');
        }

        $training->is_absen_open = true;
        $training->absen_token = Str::random(32);
        $training->save();

        return back()->with('success', 'Sesi absensi berhasil dibuka! Tampilkan barcode/QR code ini kepada peserta.');
    }

    /**
     * Tutup sesi absensi.
     */
    public function stopSession(Training $training)
    {
        if ($training->trainer_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk menutup absensi pelatihan ini.');
        }

        $training->is_absen_open = false;
        $training->absen_token = null;
        $training->save();

        return back()->with('success', 'Sesi absensi berhasil ditutup.');
    }

    /**
     * Tandai hadir untuk peserta yang dipilih secara manual oleh pemateri.
     */
    public function markPresent(Training $training, Request $request)
    {
        if ($training->trainer_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki hak akses.');
        }

        $userIds = $request->input('user_ids', []);

        if (empty($userIds)) {
            return back()->with('error', 'Pilih minimal satu peserta untuk ditandai hadir.');
        }

        $today = now()->format('Y-m-d');
        $now = now();

        foreach ($userIds as $userId) {
            $isApproved = TrainingRegistration::where('training_id', $training->id)
                ->where('user_id', $userId)
                ->where('status', 'disetujui')
                ->exists();

            if ($isApproved) {
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
                        'metode' => 'Manual oleh Pemateri',
                    ]
                );
            }
        }

        return back()->with('success', 'Peserta terpilih berhasil ditandai hadir.');
    }

    /**
     * Tandai semua peserta yang disetujui sebagai hadir.
     */
    public function markAllPresent(Training $training)
    {
        if ($training->trainer_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki hak akses.');
        }

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
                    'metode' => 'Manual oleh Pemateri',
                ]
            );
        }

        return back()->with('success', 'Seluruh peserta berhasil ditandai hadir.');
    }

    /**
     * Export rekap absensi kelas ke format CSV.
     */
    public function export(Training $training): StreamedResponse
    {
        if ($training->trainer_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki hak akses.');
        }

        $absensis = Absensi::where('training_id', $training->id)
            ->with('user')
            ->orderBy('tanggal', 'desc')
            ->orderBy('jam_masuk', 'asc')
            ->get();

        $filename = 'absensi_' . Str::slug($training->judul) . '_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($absensis, $training) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'Pelatihan: ' . $training->judul
            ]);
            fputcsv($handle, [
                'Instruktur: ' . (Auth::user()->nama ?? Auth::user()->name)
            ]);
            fputcsv($handle, []);

            fputcsv($handle, [
                'No',
                'Nama Peserta',
                'Email',
                'NIK',
                'No Telepon',
                'Tanggal',
                'Jam Masuk',
                'Status',
                'Metode Absensi'
            ]);

            foreach ($absensis as $index => $absen) {
                fputcsv($handle, [
                    $index + 1,
                    $absen->user->nama ?? $absen->user->name ?? '-',
                    $absen->user->email ?? '-',
                    $absen->user->nik ?? '-',
                    $absen->user->no_telepon ?? '-',
                    $absen->tanggal ? $absen->tanggal->format('d/m/Y') : '-',
                    $absen->jam_masuk ? date('H:i:s', strtotime($absen->jam_masuk)) : ($absen->waktu_checkin ? $absen->waktu_checkin->format('H:i:s') : '-'),
                    ucfirst($absen->status),
                    $absen->metode ?? 'QR Code',
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
