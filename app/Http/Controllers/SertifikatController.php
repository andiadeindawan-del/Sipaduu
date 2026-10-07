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

        $registrations = \App\Models\TrainingRegistration::with(['training'])
            ->where('user_id', $userId)
            ->where('status', 'disetujui')
            ->get();

        $trainingStatus = [];
        $activeCertificates = 0;
        $totalCertificates = 0;

        foreach ($registrations as $reg) {
            $training = $reg->training;
            
            $sertifikat = Sertifikat::where('training_id', $training->id)
                ->where('user_id', $userId)
                ->first();

            if ($sertifikat) {
                $totalCertificates++;
                if ($sertifikat->status === 'aktif') {
                    $activeCertificates++;
                    $trainingStatus[] = [
                        'training' => $training,
                        'status_sertifikat' => 'sudah_terbit',
                        'sertifikat' => $sertifikat
                    ];
                }
                continue;
            }

            $passingStatus = $this->getUserPassingStatus($training->id, $userId);
            
            if (is_array($passingStatus['quizzes']) && count($passingStatus['quizzes']) === 0) {
                continue; 
            } else if ($passingStatus['quizzes'] instanceof \Illuminate\Support\Collection && $passingStatus['quizzes']->isEmpty()) {
                continue;
            }
            
            if ($passingStatus['passed']) {
                $trainingStatus[] = [
                    'training' => $training,
                    'status_sertifikat' => 'menunggu_terbit',
                    'passed_at' => $passingStatus['passed_at'],
                    'final_score' => $passingStatus['final_score']
                ];
            } else {
                $trainingStatus[] = [
                    'training' => $training,
                    'status_sertifikat' => 'belum_lulus',
                    'quizzes' => $passingStatus['quizzes']
                ];
            }
        }

        return view('peserta.sertifikat.index', compact(
            'trainingStatus',
            'totalCertificates',
            'activeCertificates'
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

        if ($sertifikat->status !== 'aktif') {
            abort(403, 'Sertifikat tidak aktif.');
        }
        
        $namaPeserta = $user->nama ?? $user->name;
        $urlTemplate = $sertifikat->template_sertifikat ? '/storage/' . $sertifikat->template_sertifikat : null;
        $urlTandaTangan = $sertifikat->tanda_tangan_digital ? '/storage/' . $sertifikat->tanda_tangan_digital : null;
        $urlVerify = url('/sertifikat/verify/' . $sertifikat->nomor_sertifikat);

        return view('peserta.sertifikat.show', compact('sertifikat', 'namaPeserta', 'urlTemplate', 'urlTandaTangan', 'urlVerify'));
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
    public function verify(Request $request, $nomor = null)
    {
        $nomorSertifikat = $nomor ?? $request->nomor_sertifikat;

        $sertifikat = null;
        if ($nomorSertifikat) {
            $sertifikat = Sertifikat::where('nomor_sertifikat', $nomorSertifikat)
                ->with(['user', 'training'])
                ->first();
        }

        return view('public.sertifikat.verify', compact('sertifikat', 'nomorSertifikat'));
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

    /**
     * Tampilkan peserta yang menunggu penerbitan sertifikat.
     */
    public function menunggu(Request $request)
    {
        $trainings = Training::whereIn('status', ['published', 'selesai', 'berjalan'])->orderBy('judul')->get();
        $participants = collect();
        $trainingId = $request->training_id;

        if ($trainingId) {
            $enrolledUserIds = \Illuminate\Support\Facades\DB::table('training_registrations')
                ->where('training_id', $trainingId)
                ->where('status', 'disetujui')
                ->pluck('user_id');

            foreach ($enrolledUserIds as $userId) {
                $passingStatus = $this->getUserPassingStatus($trainingId, $userId);
                $hasCert = Sertifikat::where('training_id', $trainingId)->where('user_id', $userId)->exists();
                
                $user = User::find($userId);
                if ($user) {
                    if ($hasCert) {
                        $user->status_sertifikat = 'Diterbitkan';
                    } else if ($passingStatus['passed']) {
                        $user->status_sertifikat = 'Layak Diterbitkan';
                        $user->passed_at = $passingStatus['passed_at'] ?? now();
                        $user->final_score = $passingStatus['final_score'] ?? 0;
                    } else {
                        $user->status_sertifikat = $passingStatus['reason'] ?? 'Belum memenuhi persyaratan';
                    }
                    $participants->push($user);
                }
            }
        }

        return view('admin.sertifikat.menunggu', compact('trainings', 'participants', 'trainingId'));
    }

    /**
     * Terbitkan massal.
     */
    public function terbitkanMassal(Request $request)
    {
        $request->validate([
            'training_id' => 'required|exists:trainings,id',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'template_sertifikat' => 'required|image|mimes:jpeg,png,jpg|max:4096',
            'tanda_tangan' => 'required|image|mimes:png|max:1024',
            'nama_penandatangan' => 'required|string',
            'penerbit' => 'required|string',
            'tanggal_terbit' => 'required|date',
            'tanggal_berlaku_sampai' => 'nullable|date|after_or_equal:tanggal_terbit',
            'deskripsi' => 'nullable|string',
            'format_nomor' => 'required|string',
            'nomor_awal' => 'required|integer|min:1',
        ]);

        $trainingId = $request->training_id;
        $publishedQuizzes = \Illuminate\Support\Facades\DB::table('quizzes')->where('training_id', $trainingId)->where('status', 'published')->get();

        if ($publishedQuizzes->isEmpty()) {
            return redirect()->back()->with('error', 'Pelatihan ini tidak memiliki kuis aktif.');
        }

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            foreach ($request->user_ids as $userId) {
                $passingStatus = $this->getUserPassingStatus($trainingId, $userId);
                
                if (!$passingStatus['passed']) {
                    throw new \Exception("Peserta dengan ID $userId belum lulus semua kuis.");
                }

                if (Sertifikat::where('training_id', $trainingId)->where('user_id', $userId)->exists()) {
                    throw new \Exception("Peserta dengan ID $userId sudah memiliki sertifikat.");
                }
            }

            // Generate and check numbers first
            $nomors = [];
            $currentNo = $request->nomor_awal;
            $bulanRomawiArr = ['', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
            $bulanRomawi = $bulanRomawiArr[date('n', strtotime($request->tanggal_terbit))];
            $tahun = date('Y', strtotime($request->tanggal_terbit));

            foreach ($request->user_ids as $userId) {
                $nomor = str_replace(
                    ['{no}', '{bulan_romawi}', '{tahun}'],
                    [$currentNo, $bulanRomawi, $tahun],
                    $request->format_nomor
                );

                if (Sertifikat::where('nomor_sertifikat', $nomor)->exists() || in_array($nomor, $nomors)) {
                    throw new \Exception("Nomor sertifikat $nomor sudah digunakan atau bentrok. Transaksi dibatalkan.");
                }
                $nomors[$userId] = $nomor;
                $currentNo++;
            }

            // Save files
            $templatePath = $request->file('template_sertifikat')->store('templates', 'public');
            $signaturePath = $request->file('tanda_tangan')->store('signatures', 'public');

            $training = Training::find($trainingId);
            $count = 0;
            $startNo = null;
            $endNo = null;

            foreach ($request->user_ids as $userId) {
                $nomor = $nomors[$userId];
                if ($startNo === null) $startNo = $nomor;
                $endNo = $nomor;

                $sertifikat = Sertifikat::create([
                    'user_id' => $userId,
                    'training_id' => $trainingId,
                    'nomor_sertifikat' => $nomor,
                    'nama_sertifikat' => $training->judul,
                    'deskripsi' => $request->deskripsi,
                    'tanggal_terbit' => $request->tanggal_terbit,
                    'tanggal_berlaku_sampai' => $request->tanggal_berlaku_sampai,
                    'penerbit' => $request->penerbit,
                    'tanda_tangan_digital' => $signaturePath,
                    'template_sertifikat' => $templatePath,
                    'nama_penandatangan' => $request->nama_penandatangan,
                    'status' => 'aktif'
                ]);

                // Update training_participants
                \Illuminate\Support\Facades\DB::table('training_participants')
                    ->where('training_id', $trainingId)
                    ->where('user_id', $userId)
                    ->update(['certificate_id' => $sertifikat->id]);

                $count++;
            }

            \Illuminate\Support\Facades\DB::commit();
            return redirect()->route('admin.sertifikat.menunggu')->with('success', "Berhasil menerbitkan $count sertifikat. (Range: $startNo - $endNo)");
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    private function getUserPassingStatus($trainingId, $userId) {
        $publishedQuizzes = \Illuminate\Support\Facades\DB::table('quizzes')
            ->where('training_id', $trainingId)
            ->where('status', 'published')
            ->get();
            
        $hasAbsensi = \Illuminate\Support\Facades\DB::table('absensis')
            ->where('training_id', $trainingId)
            ->where('user_id', $userId)
            ->where('status', 'hadir')
            ->exists();

        if (!$hasAbsensi) {
            return ['passed' => false, 'quizzes' => collect(), 'reason' => 'Belum absensi'];
        }

        if ($publishedQuizzes->isEmpty()) {
            return ['passed' => false, 'quizzes' => collect(), 'reason' => 'Kuis belum tersedia'];
        }

        $passedAll = true;
        $totalScore = 0;
        $lastAttemptDate = null;
        $quizzesStatus = [];

        foreach ($publishedQuizzes as $quiz) {
            $attempts = \Illuminate\Support\Facades\DB::table('quiz_attempts')
                ->where('quiz_id', $quiz->id)
                ->where('user_id', $userId)
                ->where('status', 'completed')
                ->get();
                
            $bestScoreAttempt = $attempts->sortByDesc('score')->first();
            
            $hasPassed = $bestScoreAttempt && $bestScoreAttempt->score >= $quiz->passing_score;
            if (!$hasPassed) {
                $passedAll = false;
            }

            if ($bestScoreAttempt) {
                $totalScore += $bestScoreAttempt->score;
                if (!$lastAttemptDate || $bestScoreAttempt->completed_at > $lastAttemptDate) {
                    $lastAttemptDate = $bestScoreAttempt->completed_at;
                }
            }
            
            $quizzesStatus[] = [
                'quiz' => $quiz,
                'passed' => $hasPassed,
                'score' => $bestScoreAttempt ? $bestScoreAttempt->score : 0,
            ];
        }

        if (!$passedAll) {
            return ['passed' => false, 'quizzes' => $publishedQuizzes, 'reason' => 'Tidak lulus quiz'];
        }

        return [
            'passed' => true,
            'final_score' => $totalScore / $publishedQuizzes->count(),
            'passed_at' => $lastAttemptDate,
            'quizzes' => $quizzesStatus
        ];
    }
}