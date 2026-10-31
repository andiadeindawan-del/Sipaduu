<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use App\Models\Kategori;
use App\Models\Training;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PemateriMateriController extends Controller
{
    /**
     * Display a listing of materi for pemateri.
     */
    public function index(Request $request): View
    {
        $userId = auth()->id();
        $myTrainingIds = Training::where('trainer_id', $userId)->pluck('id');

        $query = Materi::with(['kategori', 'training'])
            ->whereIn('training_id', $myTrainingIds);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%$search%")
                  ->orWhere('deskripsi', 'like', "%$search%");
            });
        }

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        if ($request->filled('training_id')) {
            $query->where('training_id', $request->training_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $materis = $query->latest()->paginate(10)->withQueryString();

        $totalMateri = Materi::whereIn('training_id', $myTrainingIds)->count();
        $publishedMateri = Materi::whereIn('training_id', $myTrainingIds)->where('status', 'published')->count();
        $draftMateri = Materi::whereIn('training_id', $myTrainingIds)->where('status', 'draft')->count();

        $kategoris = Kategori::orderBy('nama')->get();
        $trainings = Training::where('trainer_id', $userId)->orderBy('judul')->get();

        return view('pemateri.materi.index', compact(
            'materis',
            'totalMateri',
            'publishedMateri',
            'draftMateri',
            'kategoris',
            'trainings'
        ));
    }

    /**
     * Show the form for creating a new materi.
     */
    public function create(Request $request): mixed
    {
        $selectedTrainingId = $request->get('training_id');
        if ($selectedTrainingId) {
            $checkTraining = Training::find($selectedTrainingId);
            if ($checkTraining && $checkTraining->isFinished()) {
                return redirect()->route('pemateri.trainings.show', $checkTraining)
                    ->with('error', 'Pelatihan ini sudah selesai atau masa pelaksanaannya telah berakhir. Anda tidak dapat mengunggah materi baru.');
            }
        }

        $kategoris = Kategori::orderBy('nama')->get();
        $trainings = Training::where('trainer_id', auth()->id())
            ->where('status', '!=', 'selesai')
            ->where('status', '!=', 'dibatalkan')
            ->where(function ($q) {
                $q->whereNull('tanggal_selesai')
                  ->orWhere('tanggal_selesai', '>=', now());
            })
            ->orderBy('judul')
            ->get();

        return view('pemateri.materi.create', compact('kategoris', 'trainings', 'selectedTrainingId'));
    }

    /**
     * Store a newly created materi in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategoris,id',
            'training_id' => 'required|exists:trainings,id',
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

        $training = Training::findOrFail($validated['training_id']);
        if ($training->trainer_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk menambahkan materi pada pelatihan ini.');
        }

        if ($training->isFinished()) {
            return back()->with('error', 'Pelatihan ini sudah selesai atau tanggal pelaksanaannya telah berakhir. Materi tidak dapat ditambahkan lagi.')->withInput();
        }

        $fileData = [];

        // Handle multiple file uploads
        if ($request->hasFile('files')) {
            $fileTypes = $request->file_types ?? [];
            foreach ($request->file('files') as $index => $file) {
                if ($file && $file->isValid()) {
                    $ext = $file->getClientOriginalExtension();
                    $filename = time() . '_' . Str::slug($validated['judul']) . '_' . ($index + 1) . '.' . $ext;
                    $path = $file->storeAs('materi', $filename, 'public');
                    
                    $fileData[] = [
                        'path' => $path,
                        'url' => asset('storage/' . $path),
                        'type' => $fileTypes[$index] ?? $this->getFileTypeFromExtension($ext),
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
            return back()->withErrors(['files' => 'Minimal upload 1 file atau masukkan URL materi.'])->withInput();
        }

        $validated['file_data'] = $fileData;
        $firstFile = $fileData[0];
        $validated['tipe_file'] = $firstFile['type'] ?? 'other';
        $validated['file_path'] = $firstFile['path'] ?? null;
        $validated['file_url'] = $firstFile['url'] ?? null;
        $validated['total_files'] = count($fileData);

        Materi::create($validated);

        return redirect()->route('pemateri.materi.index')
            ->with('success', '✅ Materi berhasil diupload.');
    }

    /**
     * Display the specified materi.
     */
    public function show(Materi $materi): View
    {
        if (!$materi->training || $materi->training->trainer_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk materi ini.');
        }

        $materi->load(['kategori', 'training']);
        return view('pemateri.materi.show', compact('materi'));
    }

    /**
     * Show the form for editing the specified materi.
     */
    public function edit(Materi $materi): mixed
    {
        if (!$materi->training || $materi->training->trainer_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk materi ini.');
        }

        if ($materi->training->isFinished()) {
            return redirect()->route('pemateri.materi.show', $materi)
                ->with('error', 'Pelatihan untuk materi ini telah selesai. Materi pada pelatihan yang sudah selesai tidak dapat diubah lagi.');
        }

        $kategoris = Kategori::orderBy('nama')->get();
        $trainings = Training::where('trainer_id', auth()->id())
            ->where('status', '!=', 'selesai')
            ->where('status', '!=', 'dibatalkan')
            ->where(function ($q) {
                $q->whereNull('tanggal_selesai')
                  ->orWhere('tanggal_selesai', '>=', now());
            })
            ->orderBy('judul')
            ->get();

        return view('pemateri.materi.edit', compact('materi', 'kategoris', 'trainings'));
    }

    /**
     * Update the specified materi in storage.
     */
    public function update(Request $request, Materi $materi): RedirectResponse
    {
        if (!$materi->training || $materi->training->trainer_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk materi ini.');
        }

        if ($materi->training->isFinished()) {
            return redirect()->route('pemateri.materi.show', $materi)
                ->with('error', 'Pelatihan untuk materi ini telah selesai. Materi pada pelatihan yang sudah selesai tidak dapat diubah lagi.');
        }

        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategoris,id',
            'training_id' => 'required|exists:trainings,id',
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

        $training = Training::findOrFail($validated['training_id']);
        if ($training->trainer_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk pelatihan yang dipilih.');
        }

        $validated['slug'] = Str::slug($validated['judul']);
        $validated['is_free'] = $request->has('is_free');

        $fileData = $materi->file_data ?? [];

        // Add new files if uploaded
        if ($request->hasFile('files')) {
            $fileTypes = $request->file_types ?? [];
            foreach ($request->file('files') as $index => $file) {
                if ($file && $file->isValid()) {
                    $ext = $file->getClientOriginalExtension();
                    $filename = time() . '_' . Str::slug($validated['judul']) . '_' . (count($fileData) + 1) . '.' . $ext;
                    $path = $file->storeAs('materi', $filename, 'public');
                    
                    $fileData[] = [
                        'path' => $path,
                        'url' => asset('storage/' . $path),
                        'type' => $fileTypes[$index] ?? $this->getFileTypeFromExtension($ext),
                        'name' => $file->getClientOriginalName(),
                        'size' => $file->getSize(),
                        'is_main' => empty($fileData),
                    ];
                }
            }
        }

        // Add new URLs
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

        if (!empty($fileData)) {
            $validated['file_data'] = $fileData;
            $firstFile = $fileData[0];
            $validated['tipe_file'] = $firstFile['type'] ?? 'other';
            $validated['file_path'] = $firstFile['path'] ?? null;
            $validated['file_url'] = $firstFile['url'] ?? null;
            $validated['total_files'] = count($fileData);
        }

        $materi->update($validated);

        return redirect()->route('pemateri.materi.index')
            ->with('success', '✅ Materi berhasil diperbarui.');
    }

    /**
     * Remove the specified materi from storage.
     */
    public function destroy(Materi $materi): RedirectResponse
    {
        if (!$materi->training || $materi->training->trainer_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk menghapus materi ini.');
        }

        if ($materi->training->isFinished()) {
            return redirect()->route('pemateri.materi.index')
                ->with('error', 'Pelatihan untuk materi ini telah selesai. Materi pada pelatihan yang sudah selesai tidak dapat dihapus.');
        }

        // Delete files
        if (!empty($materi->file_data) && is_array($materi->file_data)) {
            foreach ($materi->file_data as $file) {
                if (!empty($file['path']) && Storage::disk('public')->exists($file['path'])) {
                    Storage::disk('public')->delete($file['path']);
                }
            }
        } elseif (!empty($materi->file_path) && Storage::disk('public')->exists($materi->file_path)) {
            Storage::disk('public')->delete($materi->file_path);
        }

        $materi->delete();

        return redirect()->route('pemateri.materi.index')
            ->with('success', '✅ Materi berhasil dihapus.');
    }

    /**
     * Download file materi.
     */
    public function download(Materi $materi, $index = null)
    {
        if (!$materi->training || $materi->training->trainer_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk file materi ini.');
        }

        $files = $materi->files;
        if (empty($files)) {
            return back()->with('error', 'File tidak ditemukan.');
        }

        $file = ($index !== null && isset($files[$index])) ? $files[$index] : $files[0];

        if (!empty($file['path']) && Storage::disk('public')->exists($file['path'])) {
            return Storage::disk('public')->download($file['path'], $file['name'] ?? basename($file['path']));
        }

        if (!empty($file['url'])) {
            return redirect()->away($file['url']);
        }

        return back()->with('error', 'File tidak ditemukan.');
    }

    /**
     * Preview file materi.
     */
    public function preview(Materi $materi, $index = null)
    {
        if (!$materi->training || $materi->training->trainer_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk file materi ini.');
        }

        $files = $materi->files;
        if (empty($files)) {
            return back()->with('error', 'File tidak ditemukan.');
        }

        $file = ($index !== null && isset($files[$index])) ? $files[$index] : $files[0];

        if (!empty($file['path']) && Storage::disk('public')->exists($file['path'])) {
            return response()->file(Storage::disk('public')->path($file['path']));
        }

        if (!empty($file['url'])) {
            return redirect()->away($file['url']);
        }

        return back()->with('error', 'File tidak ditemukan.');
    }

    private function getFileTypeFromExtension($extension)
    {
        $extension = strtolower($extension);
        $types = [
            'pdf' => 'pdf',
            'doc' => 'pdf',
            'docx' => 'pdf',
            'ppt' => 'ppt',
            'pptx' => 'ppt',
            'mp4' => 'video',
            'avi' => 'video',
            'mov' => 'video',
            'mkv' => 'video',
            'jpg' => 'image',
            'jpeg' => 'image',
            'png' => 'image',
            'gif' => 'image',
        ];
        return $types[$extension] ?? 'other';
    }
}
