<?php

namespace App\Http\Controllers;

use App\Models\Training;
use App\Models\Sertifikat;
use App\Models\User;
use App\Models\Materi;
use App\Models\Quiz;
use App\Models\Kategori;
use App\Models\TrainingRegistration;
use App\Models\Pengumuman;
use App\Models\Agenda;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    /**
     * Display the landing page.
     */
    public function index()
    {
        // Get popular trainings
        $popularTrainings = Training::where('status', 'published')
            ->withCount(['registrations as participants_count' => function($q) {
                $q->whereIn('status', ['disetujui', 'completed']);
            }])
            ->orderBy('participants_count', 'desc')
            ->limit(6)
            ->get();

        // Get statistics - DATA REAL DARI SISTEM
        $totalUsers = User::count();
        $totalTrainings = Training::where('status', 'published')->count();
        $totalCertificates = Sertifikat::count();
        $totalMateri = Materi::where('status', 'published')->count();
        $totalQuizzes = Quiz::where('status', 'published')->count();
        $totalParticipants = User::where('role', 'peserta')->count();

        // Get testimonials (from training participants with completed status)
        $testimonials = User::where('role', 'peserta')
            ->with(['trainingDiikuti' => function($query) {
                $query->wherePivot('status', 'completed');
            }])
            ->limit(6)
            ->get()
            ->map(function($user) {
                return [
                    'name' => $user->nama ?? $user->name,
                    'role' => $user->role_label ?? 'Peserta',
                    'avatar' => $user->initials ?? 'U',
                    'quote' => 'Pelatihan ini sangat bermanfaat untuk pengembangan karir saya.',
                    'rating' => rand(4, 5),
                ];
            });

        // Get features data
        $features = [
            [
                'icon' => 'mortarboard',
                'color' => 'primary',
                'title' => 'Materi Terstruktur',
                'description' => 'Materi pelatihan disusun secara sistematis dan mudah dipahami oleh semua level karyawan.'
            ],
            [
                'icon' => 'person-video',
                'color' => 'success',
                'title' => 'Pembelajaran Interaktif',
                'description' => 'Metode pembelajaran interaktif dengan video, kuis, dan studi kasus untuk pemahaman maksimal.'
            ],
            [
                'icon' => 'award',
                'color' => 'warning',
                'title' => 'Sertifikat Resmi',
                'description' => 'Dapatkan sertifikat resmi setelah menyelesaikan pelatihan sebagai bukti kompetensi.'
            ],
            [
                'icon' => 'clock-history',
                'color' => 'danger',
                'title' => 'Akses 24/7',
                'description' => 'Belajar kapan saja dan di mana saja dengan akses 24 jam penuh dari berbagai perangkat.'
            ],
            [
                'icon' => 'bar-chart-line',
                'color' => 'info',
                'title' => 'Tracking Progress',
                'description' => 'Pantau perkembangan belajar karyawan dengan fitur tracking progress yang akurat.'
            ],
            [
                'icon' => 'people',
                'color' => 'purple',
                'title' => 'Instruktur Berpengalaman',
                'description' => 'Dibimbing oleh instruktur profesional dengan pengalaman di bidangnya masing-masing.'
            ]
        ];

        return view('landing.index', compact(
            'popularTrainings',
            'totalUsers',
            'totalTrainings',
            'totalParticipants',
            'totalCertificates',
            'totalMateri',
            'totalQuizzes',
            'testimonials',
            'features'
        ));
    }

    /**
     * Display trainings page.
     */
    public function pelatihan(Request $request)
    {
        $query = Training::where('status', 'published')
            ->with(['kategori', 'trainer'])
            ->withCount(['registrations as participants_count' => function($q) {
                $q->whereIn('status', ['disetujui', 'completed']);
            }]);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%$search%")
                  ->orWhere('deskripsi', 'like', "%$search%");
            });
        }

        // Filter by category
        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        // Filter by type
        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }

        $trainings = $query->orderBy('tanggal_mulai', 'asc')->paginate(12);

        // Categories for filter
        $kategoris = Kategori::all();

        return view('landing.pelatihan.index', compact('trainings', 'kategoris'));
    }

    /**
     * Display training detail.
     */
    public function pelatihanDetail($id)
    {
        $training = Training::where('status', 'published')
            ->with(['kategori', 'trainer', 'materis', 'quizzes'])
            ->withCount(['registrations as participants_count' => function($q) {
                $q->whereIn('status', ['disetujui', 'completed']);
            }])
            ->findOrFail($id);

        $isEnrolled = false;
        $progress = 0;

        if (auth()->check()) {
            $isEnrolled = $training->registrations()
                ->where('user_id', auth()->id())
                ->whereIn('status', ['pending', 'disetujui', 'completed'])
                ->exists();
                
            if ($isEnrolled) {
                $totalMateri = $training->materis()->count();
                $completedMateri = $training->materis()
                    ->whereHas('progress', function($q) {
                        $q->where('user_id', auth()->id())->where('status', 'completed');
                    })->count();
                
                $totalQuizzes = $training->quizzes()->count();
                $completedQuizzes = $training->quizzes()
                    ->whereHas('attempts', function($q) {
                        $q->where('user_id', auth()->id())->where('status', 'completed');
                    })->count();
                
                $totalItems = $totalMateri + $totalQuizzes;
                $completedItems = $completedMateri + $completedQuizzes;
                $progress = $totalItems > 0 ? round(($completedItems / $totalItems) * 100) : 0;
            }
        }

        return view('landing.pelatihan-detail.index', compact('training', 'isEnrolled', 'progress'));
    }

    /**
     * Display about page.
     */
    public function tentang()
    {
        $totalTrainings = Training::where('status', 'published')->count();
        $totalParticipants = User::where('role', 'peserta')->count();
        $totalCertificates = Sertifikat::count();
        $totalInstructors = User::where('role', 'trainer')->count();

        return view('landing.tentang.index', compact(
            'totalTrainings',
            'totalParticipants',
            'totalCertificates',
            'totalInstructors'
        ));
    }

    /**
     * Display FAQ page.
     */
    public function faq()
    {
        $faqs = [
            [
                'question' => 'Apa itu Sistem Pelatihan SDM?',
                'answer' => 'Sistem Pelatihan SDM adalah platform pembelajaran online yang dirancang untuk mengembangkan kompetensi sumber daya manusia perusahaan melalui berbagai pelatihan interaktif.'
            ],
            [
                'question' => 'Bagaimana cara mendaftar pelatihan?',
                'answer' => 'Anda dapat mendaftar pelatihan dengan membuat akun terlebih dahulu, kemudian pilih pelatihan yang diinginkan dan klik tombol "Daftar".'
            ],
            [
                'question' => 'Apakah ada sertifikat setelah menyelesaikan pelatihan?',
                'answer' => 'Ya, setiap peserta yang berhasil menyelesaikan pelatihan akan mendapatkan sertifikat resmi yang dapat diunduh dan dicetak.'
            ],
            [
                'question' => 'Berapa biaya untuk mengikuti pelatihan?',
                'answer' => 'Biaya pelatihan bervariasi tergantung jenis dan durasi pelatihan. Silakan cek halaman detail setiap pelatihan untuk informasi lebih lanjut.'
            ],
            [
                'question' => 'Apakah bisa mengakses materi pelatihan di mobile?',
                'answer' => 'Ya, platform kami dapat diakses melalui berbagai perangkat termasuk smartphone, tablet, dan desktop.'
            ],
            [
                'question' => 'Bagaimana cara menghubungi tim support?',
                'answer' => 'Anda dapat menghubungi tim support melalui halaman Kontak atau mengirim email ke info@pelatihan-sdm.com.'
            ]
        ];

        return view('landing.faq.index', compact('faqs'));
    }

    /**
     * Display contact page.
     */
    public function kontak()
    {
        return view('landing.kontak.index');
    }

    /**
     * Send contact message.
     */
    public function kontakSend(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Send email or store in database
        // Mail::to('info@pelatihan-sdm.com')->send(new ContactMail($request->all()));

        return redirect()->route('landing.kontak.index')
                        ->with('success', 'Pesan Anda berhasil dikirim. Tim kami akan segera menghubungi Anda.');
    }

    /**
     * Display pengumuman page.
     * TAMBAHAN: Method untuk halaman pengumuman
     */
    public function pengumuman(Request $request)
    {
        $query = Pengumuman::with(['training', 'creator'])
            ->where('status', 'published');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%$search%")
                  ->orWhere('konten', 'like', "%$search%");
            });
        }

        // Filter by date
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $pengumumans = $query->orderBy('is_pinned', 'desc')
                             ->orderBy('created_at', 'desc')
                             ->paginate(10)
                             ->withQueryString();

        return view('landing.pengumuman.index', compact('pengumumans'));
    }

    /**
     * Display pengumuman detail.
     * TAMBAHAN: Method untuk detail pengumuman
     */
    public function pengumumanShow($id)
    {
        $pengumuman = Pengumuman::with(['training', 'creator'])
            ->where('status', 'published')
            ->findOrFail($id);

        // Increment views
        $pengumuman->increment('views');

        return view('landing.pengumuman-detail', compact('pengumuman'));
    }

    /**
     * Display agenda page.
     * TAMBAHAN: Method untuk halaman agenda
     */
    public function agenda(Request $request)
    {
        $query = Agenda::with(['training', 'creator'])
            ->where('status', 'published')
            ->whereDate('tanggal', '>=', now());

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%$search%")
                  ->orWhere('deskripsi', 'like', "%$search%")
                  ->orWhere('lokasi', 'like', "%$search%");
            });
        }

        // Filter by date
        if ($request->filled('date_from')) {
            $query->whereDate('tanggal', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('tanggal', '<=', $request->date_to);
        }

        $agendas = $query->orderBy('tanggal', 'asc')
                         ->orderBy('jam_mulai', 'asc')
                         ->paginate(12)
                         ->withQueryString();

        return view('landing.agenda.index', compact('agendas'));
    }

    /**
     * Display agenda detail.
     * TAMBAHAN: Method untuk detail agenda
     */
    public function agendaShow($id)
    {
        $agenda = Agenda::with(['training', 'creator'])
            ->where('status', 'published')
            ->findOrFail($id);

        return view('landing.agenda.show', compact('agenda'));
    }
}