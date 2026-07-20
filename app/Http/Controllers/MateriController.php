<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use App\Models\Kategori;
use App\Models\Training;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MateriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index(Request $request)
{
    $query = Materi::with(['kategori', 'training']);

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
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    $materis = $query->latest()->paginate(10)->withQueryString();

    // Statistics
    $totalMateri = Materi::count();
    $publishedMateri = Materi::where('status', 'published')->count();
    $draftMateri = Materi::where('status', 'draft')->count();
    $archivedMateri = Materi::where('status', 'archived')->count();

    // PERBAIKAN: Ambil data untuk dropdown
    $kategoris = Kategori::orderBy('nama')->get();
    $trainings = Training::orderBy('judul')->get(); // <-- TAMBAHKAN INI

    // Debug
    \Log::info('Index Materi - Data:', [
        'kategoris_count' => $kategoris->count(),
        'trainings_count' => $trainings->count(),
        'materis_count' => $materis->count(),
    ]);

    return view('admin.materi.index', compact(
        'materis',
        'totalMateri',
        'publishedMateri',
        'draftMateri',
        'archivedMateri',
        'kategoris',
        'trainings' // <-- TAMBAHKAN INI
    ));
}

    /**
     * Display a listing of materi for peserta.
     */
     public function pesertaIndex(Request $request)
    {
        $user = auth()->user();
        $userId = $user->id;

        // Ambil training IDs yang diikuti user (status disetujui)
        $trainingIds = TrainingRegistration::where('user_id', $userId)
            ->where('status', 'disetujui')
            ->pluck('training_id')
            ->toArray();

        // Jika user tidak terdaftar di training apapun, tampilkan kosong
        if (empty($trainingIds)) {
            $materis = collect();
            $totalMaterials = 0;
            $completedMaterials = 0;
            $inProgressMaterials = 0;
            $totalFiles = 0;
            $kategoris = Kategori::all();

            return view('peserta.materi.index', compact(
                'materis',
                'totalMaterials',
                'completedMaterials',
                'inProgressMaterials',
                'totalFiles',
                'kategoris'
            ));
        }

        // Query materi dari training yang diikuti
        $query = Materi::with(['kategori', 'training'])
            ->where('status', 'published')
            ->whereIn('training_id', $trainingIds);

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

        // Filter by status progress
        $filter = $request->get('filter');
        if ($filter === 'completed') {
            $query->whereExists(function ($q) use ($userId) {
                $q->select(DB::raw(1))
                  ->from('materi_progress')
                  ->whereColumn('materi_progress.materi_id', 'materis.id')
                  ->where('materi_progress.user_id', $userId)
                  ->where('materi_progress.status', 'completed');
            });
        } elseif ($filter === 'in_progress') {
            $query->whereExists(function ($q) use ($userId) {
                $q->select(DB::raw(1))
                  ->from('materi_progress')
                  ->whereColumn('materi_progress.materi_id', 'materis.id')
                  ->where('materi_progress.user_id', $userId)
                  ->where('materi_progress.status', 'in_progress');
            });
        } elseif ($filter === 'not_started') {
            $query->whereNotExists(function ($q) use ($userId) {
                $q->select(DB::raw(1))
                  ->from('materi_progress')
                  ->whereColumn('materi_progress.materi_id', 'materis.id')
                  ->where('materi_progress.user_id', $userId);
            });
        }

        // Order by order column or created_at
        $materis = $query->orderBy('order', 'asc')
                         ->orderBy('created_at', 'desc')
                         ->paginate(12)
                         ->withQueryString();

        // Tambahkan progress untuk setiap materi
        foreach ($materis as $materi) {
            $progress = DB::table('materi_progress')
                ->where('materi_id', $materi->id)
                ->where('user_id', $userId)
                ->first();
            
            $materi->progress = $progress ? $progress->progress : 0;
            $materi->status_progress = $progress ? $progress->status : 'not_started';
            
            // Tambahkan method getMyProgress jika belum ada
            if (!method_exists($materi, 'getMyProgress')) {
                $materi->getMyProgress = function() use ($materi, $userId) {
                    $progress = DB::table('materi_progress')
                        ->where('materi_id', $materi->id)
                        ->where('user_id', $userId)
                        ->first();
                    return $progress ? $progress->progress : 0;
                };
            }
        }

        // Statistics
        $totalMaterials = Materi::where('status', 'published')
            ->whereIn('training_id', $trainingIds)
            ->count();
            
        $completedMaterials = Materi::where('status', 'published')
            ->whereIn('training_id', $trainingIds)
            ->whereExists(function ($q) use ($userId) {
                $q->select(DB::raw(1))
                  ->from('materi_progress')
                  ->whereColumn('materi_progress.materi_id', 'materis.id')
                  ->where('materi_progress.user_id', $userId)
                  ->where('materi_progress.status', 'completed');
            })->count();
        
        $inProgressMaterials = Materi::where('status', 'published')
            ->whereIn('training_id', $trainingIds)
            ->whereExists(function ($q) use ($userId) {
                $q->select(DB::raw(1))
                  ->from('materi_progress')
                  ->whereColumn('materi_progress.materi_id', 'materis.id')
                  ->where('materi_progress.user_id', $userId)
                  ->where('materi_progress.status', 'in_progress');
            })->count();

        // Total files
        $totalFiles = Materi::where('status', 'published')
            ->whereIn('training_id', $trainingIds)
            ->sum('total_files') ?? 0;

        // Kategori untuk filter
        $kategoris = Kategori::all();

        // Debug (hapus setelah berhasil)
        // \Log::info('Materi Data:', [
        //     'training_ids' => $trainingIds,
        //     'total_materi' => $totalMaterials,
        //     'materis_count' => $materis->count(),
        // ]);

        return view('peserta.materi.index', compact(
            'materis',
            'totalMaterials',
            'completedMaterials',
            'inProgressMaterials',
            'totalFiles',
            'kategoris'
        ));
    }

    /**
     * Display the specified materi for peserta.
     */
    public function pesertaShow(Materi $materi)
    {
        $user = auth()->user();
        $userId = $user->id;

        // Check if materi is published
        if ($materi->status !== 'published') {
            abort(404);
        }

        $materi->load(['kategori']);

        // Get user progress using Query Builder
        $progress = DB::table('materi_progress')
            ->where('materi_id', $materi->id)
            ->where('user_id', $userId)
            ->value('progress') ?? 0;

        // Mark as in_progress if not completed
        if ($progress > 0 && $progress < 100) {
            $this->updateProgress($materi->id, $userId, 'in_progress', $progress);
        }

        return view('peserta.materi.show', compact('materi', 'progress'));
    }

    /**
     * Download file materi for peserta.
     */
    public function pesertaDownload(Materi $materi, $index = null)
    {
        // Check if materi is published
        if ($materi->status !== 'published') {
            abort(404);
        }

        $files = $materi->files;
        
        if (empty($files)) {
            return redirect()->back()->with('error', 'File tidak ditemukan.');
        }

        // If index is provided, download specific file
        if ($index !== null && isset($files[$index])) {
            $file = $files[$index];
        } else {
            $file = $files[0]; // Download first file
        }
        
        // Download from path
        if (!empty($file['path'])) {
            if (!Storage::disk('public')->exists($file['path'])) {
                return redirect()->back()->with('error', 'File tidak ditemukan.');
            }
            
            // Mark as in_progress
            $user = auth()->user();
            $this->updateProgress($materi->id, $user->id, 'in_progress');
            
            return Storage::disk('public')->download($file['path'], $file['name'] ?? basename($file['path']));
        }
        
        // Redirect to URL
        if (!empty($file['url'])) {
            // Mark as in_progress
            $user = auth()->user();
            $this->updateProgress($materi->id, $user->id, 'in_progress');
            
            return redirect()->away($file['url']);
        }

        return redirect()->back()->with('error', 'File tidak ditemukan.');
    }

    /**
     * Mark materi as completed for user.
     */
    public function markComplete(Materi $materi)
    {
        $user = auth()->user();
        $userId = $user->id;
        
        // Check if user already completed
        $status = DB::table('materi_progress')
            ->where('materi_id', $materi->id)
            ->where('user_id', $userId)
            ->value('status');

        if ($status === 'completed') {
            return redirect()->back()->with('info', 'Materi sudah ditandai selesai sebelumnya.');
        }
        
        $this->updateProgress($materi->id, $userId, 'completed', 100);

        return redirect()->back()->with('success', '✅ Materi berhasil ditandai selesai.');
    }

    /**
     * Get materi progress for user (AJAX).
     */
    public function getProgress(Materi $materi)
    {
        $user = auth()->user();
        $userId = $user->id;
        
        $progressData = DB::table('materi_progress')
            ->where('materi_id', $materi->id)
            ->where('user_id', $userId)
            ->first();

        $progress = $progressData ? $progressData->progress : 0;
        $status = $progressData ? $progressData->status : 'not_started';

        return response()->json([
            'success' => true,
            'progress' => $progress,
            'status' => $status,
            'total_files' => $materi->total_files ?? 0,
            'is_completed' => $status === 'completed',
            'materi' => [
                'id' => $materi->id,
                'judul' => $materi->judul,
            ]
        ]);
    }

    /**
     * Update user progress for a materi.
     */
    private function updateProgress($materiId, $userId, $status = 'in_progress', $progress = null)
    {
        $existing = DB::table('materi_progress')
            ->where('materi_id', $materiId)
            ->where('user_id', $userId)
            ->first();

        // Jika progress tidak diberikan, hitung berdasarkan file
        if ($progress === null) {
            $totalFiles = Materi::find($materiId)->total_files ?? 0;
            if ($totalFiles > 0) {
                $progress = 50; // Default 50%
            } else {
                $progress = $status === 'completed' ? 100 : 0;
            }
        }

        $data = [
            'status' => $status,
            'progress' => $progress,
            'completed_at' => $status === 'completed' ? now() : null,
            'updated_at' => now(),
        ];

        if ($existing) {
            // Update existing
            DB::table('materi_progress')
                ->where('materi_id', $materiId)
                ->where('user_id', $userId)
                ->update($data);
        } else {
            // Create new
            $data['materi_id'] = $materiId;
            $data['user_id'] = $userId;
            $data['created_at'] = now();
            DB::table('materi_progress')->insert($data);
        }

        return true;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategoris = Kategori::all();
        // Tampilkan semua training agar admin bisa menambahkan materi ke training yang masih draft
        $trainings = Training::orderBy('judul')->get();
        
        return view('admin.materi.create', compact('kategoris', 'trainings'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategoris,id',
            'training_id' => 'nullable|exists:trainings,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'konten' => 'nullable|string',
            'durasi' => 'nullable|integer|min:1',
            'status' => 'required|in:draft,published,archived',
            'order' => 'nullable|integer|min:0',
            'is_free' => 'nullable|boolean',
            'files.*' => 'nullable|file|max:102400',
            'file_types.*' => 'nullable|in:pdf,video,ppt,link,image,other',
            'file_urls.*' => 'nullable|url|max:255',
            'url_types.*' => 'nullable|in:pdf,video,ppt,link,image,other',
        ]);

        $validated['slug'] = Str::slug($validated['judul']);
        $validated['is_free'] = $request->has('is_free');

        $fileData = [];

        // Handle multiple file uploads
        if ($request->hasFile('files')) {
            $fileTypes = $request->file_types ?? [];
            foreach ($request->file('files') as $index => $file) {
                if ($file && $file->isValid()) {
                    $filename = time() . '_' . Str::slug($validated['judul']) . '_' . ($index + 1) . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('materi', $filename, 'public');
                    
                    $fileData[] = [
                        'path' => $path,
                        'url' => asset('storage/' . $path),
                        'type' => $fileTypes[$index] ?? $this->getFileTypeFromExtension($file->getClientOriginalExtension()),
                        'name' => $file->getClientOriginalName(),
                        'size' => $file->getSize(),
                        'is_main' => empty($fileData),
                    ];
                }
            }
        }

        // Handle URLs
        if ($request->has('file_urls')) {
            $urlTypes = $request->url_types ?? [];
            foreach ($request->file_urls as $index => $url) {
                if (!empty($url)) {
                    $fileData[] = [
                        'path' => null,
                        'url' => $url,
                        'type' => $urlTypes[$index] ?? 'link',
                        'name' => basename($url),
                        'size' => null,
                        'is_main' => empty($fileData),
                    ];
                }
            }
        }

        if (empty($fileData)) {
            return back()->withErrors(['files' => 'Minimal upload 1 file atau URL.'])->withInput();
        }

        $validated['file_data'] = $fileData;
        
        $firstFile = $fileData[0];
        $validated['tipe_file'] = $firstFile['type'] ?? 'other';
        $validated['file_path'] = $firstFile['path'] ?? null;
        $validated['file_url'] = $firstFile['url'] ?? null;
        $validated['total_files'] = count($fileData);

        $materi = Materi::create($validated);

        return redirect()->route('admin.materi.index')
                        ->with('success', '✅ Materi berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Materi $materi)
    {
        $materi->load(['kategori', 'training']);
        
        return view('admin.materi.show', compact('materi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Materi $materi)
    {
        $kategoris = Kategori::all();
        // Tampilkan semua training agar admin bisa menambahkan materi ke training yang masih draft
        $trainings = Training::orderBy('judul')->get();
        
        return view('admin.materi.edit', compact('materi', 'kategoris', 'trainings'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Materi $materi)
    {
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategoris,id',
            'training_id' => 'nullable|exists:trainings,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'konten' => 'nullable|string',
            'durasi' => 'nullable|integer|min:1',
            'status' => 'required|in:draft,published,archived',
            'order' => 'nullable|integer|min:0',
            'is_free' => 'nullable|boolean',
            'files.*' => 'nullable|file|max:102400',
            'file_types.*' => 'nullable|in:pdf,video,ppt,link,image,other',
            'file_urls.*' => 'nullable|url|max:255',
            'url_types.*' => 'nullable|in:pdf,video,ppt,link,image,other',
            'delete_file_indices' => 'nullable|string',
        ]);

        if ($materi->judul !== $validated['judul']) {
            $validated['slug'] = Str::slug($validated['judul']);
        }

        $validated['is_free'] = $request->has('is_free');

        $fileData = $materi->file_data ?? [];

        // Handle file deletion
        if ($request->has('delete_file_indices') && !empty($request->delete_file_indices)) {
            $deleteIndices = array_map('intval', explode(',', $request->delete_file_indices));
            $deleteIndices = array_reverse($deleteIndices);
            
            foreach ($deleteIndices as $index) {
                if (isset($fileData[$index])) {
                    if (!empty($fileData[$index]['path'])) {
                        Storage::disk('public')->delete($fileData[$index]['path']);
                    }
                    unset($fileData[$index]);
                }
            }
            $fileData = array_values($fileData);
        }

        // Handle file uploads
        if ($request->hasFile('files')) {
            $fileTypes = $request->file_types ?? [];
            foreach ($request->file('files') as $index => $file) {
                if ($file && $file->isValid()) {
                    $filename = time() . '_' . Str::slug($validated['judul']) . '_' . ($index + 1 + count($fileData)) . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('materi', $filename, 'public');
                    
                    $fileData[] = [
                        'path' => $path,
                        'url' => asset('storage/' . $path),
                        'type' => $fileTypes[$index] ?? $this->getFileTypeFromExtension($file->getClientOriginalExtension()),
                        'name' => $file->getClientOriginalName(),
                        'size' => $file->getSize(),
                        'is_main' => empty($fileData),
                    ];
                }
            }
        }

        // Handle URLs
        if ($request->has('file_urls')) {
            $urlTypes = $request->url_types ?? [];
            foreach ($request->file_urls as $index => $url) {
                if (!empty($url)) {
                    $exists = false;
                    foreach ($fileData as $existingFile) {
                        if (isset($existingFile['url']) && $existingFile['url'] === $url) {
                            $exists = true;
                            break;
                        }
                    }
                    
                    if (!$exists) {
                        $fileData[] = [
                            'path' => null,
                            'url' => $url,
                            'type' => $urlTypes[$index] ?? 'link',
                            'name' => basename($url),
                            'size' => null,
                            'is_main' => empty($fileData),
                        ];
                    }
                }
            }
        }

        if (!empty($fileData)) {
            $firstFile = $fileData[0];
            $validated['tipe_file'] = $firstFile['type'] ?? 'other';
            $validated['file_path'] = $firstFile['path'] ?? null;
            $validated['file_url'] = $firstFile['url'] ?? null;
        } else {
            $validated['tipe_file'] = null;
            $validated['file_path'] = null;
            $validated['file_url'] = null;
        }

        $validated['file_data'] = !empty($fileData) ? $fileData : null;
        $validated['total_files'] = !empty($fileData) ? count($fileData) : 0;

        $materi->update($validated);

        return redirect()->route('admin.materi.index')
                        ->with('success', '✅ Materi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Materi $materi)
    {
        foreach ($materi->files as $file) {
            if (!empty($file['path'])) {
                Storage::disk('public')->delete($file['path']);
            }
        }

        $materi->delete();

        return redirect()->route('admin.materi.index')
                        ->with('success', '✅ Materi berhasil dihapus.');
    }

    /**
     * Download file materi (admin).
     */
    public function download(Materi $materi, $index = null)
    {
        $files = $materi->files;
        
        if (empty($files)) {
            return redirect()->back()->with('error', 'File tidak ditemukan.');
        }

        if ($index !== null && isset($files[$index])) {
            $file = $files[$index];
        } else {
            $file = $files[0];
        }
        
        if (!empty($file['path'])) {
            if (!Storage::disk('public')->exists($file['path'])) {
                return redirect()->back()->with('error', 'File tidak ditemukan.');
            }
            return Storage::disk('public')->download($file['path'], $file['name'] ?? basename($file['path']));
        }
        
        if (!empty($file['url'])) {
            return redirect()->away($file['url']);
        }

        return redirect()->back()->with('error', 'File tidak ditemukan.');
    }

    /**
     * Change materi status.
     */
    public function changeStatus(Request $request, Materi $materi)
    {
        $request->validate([
            'status' => 'required|in:draft,published,archived'
        ]);

        $oldStatus = $materi->status;
        $materi->update(['status' => $request->status]);

        $statusLabels = [
            'draft' => 'Draft',
            'published' => 'Published',
            'archived' => 'Archived'
        ];

        return redirect()->route('admin.materi.index')
                        ->with('success', "✅ Status materi berhasil diubah dari {$statusLabels[$oldStatus]} menjadi {$statusLabels[$request->status]}.");
    }

    /**
     * Bulk delete materi.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:materis,id'
        ]);

        $materis = Materi::whereIn('id', $request->ids)->get();

        foreach ($materis as $materi) {
            foreach ($materi->files as $file) {
                if (!empty($file['path'])) {
                    Storage::disk('public')->delete($file['path']);
                }
            }
            $materi->delete();
        }

        return redirect()->route('admin.materi.index')
                        ->with('success', count($request->ids) . ' materi berhasil dihapus.');
    }

    /**
     * Get file preview for AJAX.
     */
    public function preview(Materi $materi, $index = null)
    {
        $files = $materi->files;
        
        if (empty($files)) {
            return response()->json(['error' => 'File tidak ditemukan'], 404);
        }

        if ($index !== null && isset($files[$index])) {
            $file = $files[$index];
        } else {
            $file = $files[0];
        }

        return response()->json([
            'success' => true,
            'file' => $file,
            'materi' => [
                'id' => $materi->id,
                'judul' => $materi->judul,
            ]
        ]);
    }

    /**
     * Helper function to get file type from extension
     */
    private function getFileTypeFromExtension($extension)
    {
        $extension = strtolower($extension);
        
        $pdfExtensions = ['pdf'];
        $videoExtensions = ['mp4', 'avi', 'mkv', 'mov', 'wmv', 'flv', 'webm', 'm4v', '3gp'];
        $pptExtensions = ['ppt', 'pptx', 'pps', 'ppsx'];
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg', 'ico'];
        $docExtensions = ['doc', 'docx', 'txt', 'rtf', 'odt'];
        $excelExtensions = ['xls', 'xlsx', 'csv', 'ods'];
        $zipExtensions = ['zip', 'rar', '7z', 'tar', 'gz', 'bz2'];
        $audioExtensions = ['mp3', 'wav', 'aac', 'flac', 'ogg', 'm4a'];
        
        if (in_array($extension, $pdfExtensions)) {
            return 'pdf';
        } elseif (in_array($extension, $videoExtensions)) {
            return 'video';
        } elseif (in_array($extension, $pptExtensions)) {
            return 'ppt';
        } elseif (in_array($extension, $imageExtensions)) {
            return 'image';
        } elseif (in_array($extension, $audioExtensions)) {
            return 'other';
        } elseif (in_array($extension, $docExtensions) || in_array($extension, $excelExtensions)) {
            return 'other';
        } elseif (in_array($extension, $zipExtensions)) {
            return 'other';
        }
        
        return 'other';
    }
}