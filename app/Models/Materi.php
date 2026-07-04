<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Materi extends Model
{
    protected $table = 'materis';

    protected $fillable = [
        'kategori_id',
        'training_id',
        'judul',
        'slug',
        'deskripsi',
        'konten',
        'tipe_file',
        'file_url',
        'file_path',
        'file_data',
        'durasi',
        'status',
        'order',
        'is_free',
        'total_files',
    ];

    protected $casts = [
        'durasi' => 'integer',
        'order' => 'integer',
        'is_free' => 'boolean',
        'file_data' => 'array',
        'total_files' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = [
        'status_label',
        'status_badge',
        'files',
        'main_file',
        'total_files_count',
        'has_multiple_files',
        'completed_count',
        'in_progress_count',
        'completion_rate',
    ];

    /**
     * Boot method untuk auto-generate slug
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($materi) {
            if (empty($materi->slug)) {
                $materi->slug = Str::slug($materi->judul);
            }
        });

        static::updating(function ($materi) {
            if ($materi->isDirty('judul')) {
                $materi->slug = Str::slug($materi->judul);
            }
        });

        // Auto update total_files saat file_data berubah
        static::saving(function ($materi) {
            if ($materi->isDirty('file_data')) {
                $fileData = $materi->file_data;
                if (is_array($fileData)) {
                    $materi->total_files = count($fileData);
                } elseif (is_string($fileData) && !empty($fileData)) {
                    $decoded = json_decode($fileData, true);
                    $materi->total_files = is_array($decoded) ? count($decoded) : 0;
                } else {
                    $materi->total_files = 0;
                }
            }
        });
    }

    // ============================================================
    // RELATIONSHIPS
    // ============================================================

    /**
     * Relasi ke Kategori
     */
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    /**
     * Relasi ke Training
     */
    public function training()
    {
        return $this->belongsTo(Training::class, 'training_id');
    }

    // ============================================================
    // SCOPES
    // ============================================================

    /**
     * Scope untuk materi yang published
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope untuk materi yang draft
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope untuk materi yang archived
     */
    public function scopeArchived($query)
    {
        return $query->where('status', 'archived');
    }

    /**
     * Scope untuk materi gratis
     */
    public function scopeFree($query)
    {
        return $query->where('is_free', true);
    }

    /**
     * Scope untuk pencarian
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('judul', 'like', "%$search%")
                     ->orWhere('deskripsi', 'like', "%$search%")
                     ->orWhere('konten', 'like', "%$search%");
    }

    /**
     * Scope untuk materi yang memiliki file
     */
    public function scopeHasFiles($query)
    {
        return $query->whereNotNull('file_data')
                     ->orWhereNotNull('file_path')
                     ->orWhereNotNull('file_url');
    }

    /**
     * Scope untuk materi berdasarkan training
     */
    public function scopeByTraining($query, $trainingId)
    {
        return $query->where('training_id', $trainingId);
    }

    /**
     * Scope untuk materi berdasarkan kategori
     */
    public function scopeByKategori($query, $kategoriId)
    {
        return $query->where('kategori_id', $kategoriId);
    }

    /**
     * Scope untuk materi yang sudah selesai dipelajari oleh user
     */
    public function scopeCompletedBy($query, $userId)
    {
        return $query->whereExists(function ($q) use ($userId) {
            $q->select(DB::raw(1))
              ->from('materi_progress')
              ->whereColumn('materi_progress.materi_id', 'materis.id')
              ->where('materi_progress.user_id', $userId)
              ->where('materi_progress.status', 'completed');
        });
    }

    /**
     * Scope untuk materi yang sedang dipelajari oleh user
     */
    public function scopeInProgressBy($query, $userId)
    {
        return $query->whereExists(function ($q) use ($userId) {
            $q->select(DB::raw(1))
              ->from('materi_progress')
              ->whereColumn('materi_progress.materi_id', 'materis.id')
              ->where('materi_progress.user_id', $userId)
              ->where('materi_progress.status', 'in_progress');
        });
    }

    /**
     * Scope untuk materi yang belum dipelajari oleh user
     */
    public function scopeNotStartedBy($query, $userId)
    {
        return $query->whereNotExists(function ($q) use ($userId) {
            $q->select(DB::raw(1))
              ->from('materi_progress')
              ->whereColumn('materi_progress.materi_id', 'materis.id')
              ->where('materi_progress.user_id', $userId);
        });
    }

    // ============================================================
    // ACCESSORS
    // ============================================================

    /**
     * Get status label
     */
    public function getStatusLabelAttribute()
    {
        $labels = [
            'draft' => '📝 Draft',
            'published' => '✅ Published',
            'archived' => '📦 Archived'
        ];
        return $labels[$this->status] ?? ucfirst($this->status);
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeAttribute()
    {
        $classes = [
            'draft' => 'badge bg-secondary',
            'published' => 'badge bg-success',
            'archived' => 'badge bg-danger'
        ];
        return $classes[$this->status] ?? 'badge bg-secondary';
    }

    /**
     * Get file data dari JSON atau legacy fields
     */
    public function getFilesAttribute()
    {
        // Prioritaskan file_data (JSON)
        if (!empty($this->file_data)) {
            return $this->file_data;
        }

        // Fallback ke legacy fields (file_path dan file_url)
        $files = [];

        // Handle multiple file_path (pipe-separated)
        if (!empty($this->file_path)) {
            $paths = explode('|', $this->file_path);
            $types = !empty($this->tipe_file) ? explode('|', $this->tipe_file) : [];
            
            foreach ($paths as $index => $path) {
                if (!empty($path)) {
                    $files[] = [
                        'path' => $path,
                        'url' => asset('storage/' . $path),
                        'type' => $types[$index] ?? 'other',
                        'name' => basename($path),
                        'size' => $this->getFileSize($path),
                        'is_main' => $index === 0,
                    ];
                }
            }
        }

        // Handle multiple file_url (pipe-separated)
        if (!empty($this->file_url)) {
            $urls = explode('|', $this->file_url);
            $types = !empty($this->tipe_file) ? explode('|', $this->tipe_file) : [];
            
            foreach ($urls as $index => $url) {
                if (!empty($url)) {
                    $files[] = [
                        'path' => null,
                        'url' => $url,
                        'type' => $types[$index] ?? 'link',
                        'name' => basename($url),
                        'size' => null,
                        'is_main' => false,
                    ];
                }
            }
        }

        return $files;
    }

    /**
     * Set file data (auto convert ke JSON)
     */
    public function setFilesAttribute($value)
    {
        $this->attributes['file_data'] = json_encode($value);
    }

    /**
     * Get main file (first file)
     */
    public function getMainFileAttribute()
    {
        $files = $this->files;
        return !empty($files) ? $files[0] : null;
    }

    /**
     * Get total files count (dari appends)
     */
    public function getTotalFilesCountAttribute()
    {
        return count($this->files);
    }

    /**
     * Check if has multiple files
     */
    public function getHasMultipleFilesAttribute()
    {
        return $this->total_files_count > 1;
    }

    /**
     * Get total files (override untuk konsistensi)
     */
    public function getTotalFilesAttribute()
    {
        return $this->total_files_count;
    }

    /**
     * Get completed count
     */
    public function getCompletedCountAttribute()
    {
        return DB::table('materi_progress')
            ->where('materi_id', $this->id)
            ->where('status', 'completed')
            ->count();
    }

    /**
     * Get in progress count
     */
    public function getInProgressCountAttribute()
    {
        return DB::table('materi_progress')
            ->where('materi_id', $this->id)
            ->where('status', 'in_progress')
            ->count();
    }

    /**
     * Get completion rate
     */
    public function getCompletionRateAttribute()
    {
        $total = DB::table('materi_progress')
            ->where('materi_id', $this->id)
            ->count();
        
        if ($total === 0) {
            return 0;
        }
        
        $completed = DB::table('materi_progress')
            ->where('materi_id', $this->id)
            ->where('status', 'completed')
            ->count();
        
        return round(($completed / $total) * 100, 2);
    }

    /**
     * Get file size
     */
    private function getFileSize($path)
    {
        if (empty($path)) return null;
        
        try {
            if (Storage::disk('public')->exists($path)) {
                return Storage::disk('public')->size($path);
            }
        } catch (\Exception $e) {
            return null;
        }
        return null;
    }

    // ============================================================
    // HELPER METHODS - FILE MANAGEMENT
    // ============================================================

    /**
     * Check if materi has file
     */
    public function hasFile()
    {
        return !empty($this->file_data) || !empty($this->file_path) || !empty($this->file_url);
    }

    /**
     * Add a file to the collection
     */
    public function addFile($path, $type = 'other', $url = null)
    {
        $files = $this->files;
        
        $newFile = [
            'path' => $path,
            'url' => $url ?? ($path ? asset('storage/' . $path) : null),
            'type' => $type,
            'name' => basename($path ?? $url ?? ''),
            'size' => $path ? $this->getFileSize($path) : null,
            'is_main' => empty($files),
        ];
        
        $files[] = $newFile;
        $this->file_data = $files;
        $this->total_files = count($files);
        
        return $this;
    }

    /**
     * Remove a file by index
     */
    public function removeFile($index)
    {
        $files = $this->files;
        
        if (isset($files[$index])) {
            // Delete physical file if exists
            if (!empty($files[$index]['path'])) {
                Storage::disk('public')->delete($files[$index]['path']);
            }
            
            unset($files[$index]);
            $this->file_data = array_values($files); // Reindex
            
            // Set main file if needed
            if (!empty($this->file_data) && empty($this->file_data[0]['is_main'])) {
                $this->file_data[0]['is_main'] = true;
                $this->file_data = $this->file_data;
            }
            
            $this->total_files = count($this->file_data);
        }
        
        return $this;
    }

    /**
     * Clear all files
     */
    public function clearFiles()
    {
        foreach ($this->files as $file) {
            if (!empty($file['path'])) {
                Storage::disk('public')->delete($file['path']);
            }
        }
        
        $this->file_data = [];
        $this->total_files = 0;
        return $this;
    }

    /**
     * Get files grouped by type
     */
    public function getFilesByType()
    {
        $grouped = [];
        foreach ($this->files as $file) {
            $type = $file['type'] ?? 'other';
            if (!isset($grouped[$type])) {
                $grouped[$type] = [];
            }
            $grouped[$type][] = $file;
        }
        return $grouped;
    }

    /**
     * Get file type icon
     */
    public function getFileIcon($type)
    {
        $icons = [
            'pdf' => 'bi-file-pdf text-danger',
            'video' => 'bi-file-play text-primary',
            'ppt' => 'bi-file-ppt text-warning',
            'link' => 'bi-link text-info',
            'image' => 'bi-file-image text-success',
            'other' => 'bi-file-earmark text-secondary'
        ];
        return $icons[$type] ?? 'bi-file-earmark';
    }

    /**
     * Get file type label
     */
    public function getFileTypeLabel($type)
    {
        $labels = [
            'pdf' => 'PDF',
            'video' => 'Video',
            'ppt' => 'Presentasi',
            'link' => 'Link',
            'image' => 'Gambar',
            'other' => 'Lainnya'
        ];
        return $labels[$type] ?? $type;
    }

    // ============================================================
    // HELPER METHODS - PROGRESS TRACKING (Menggunakan Query Builder)
    // ============================================================

    /**
     * Get user progress for this materi
     */
    public function getUserProgress($userId)
    {
        $progress = DB::table('materi_progress')
            ->where('materi_id', $this->id)
            ->where('user_id', $userId)
            ->first();

        if (!$progress) {
            return 0;
        }

        return $progress->progress ?? 0;
    }

    /**
     * Get user progress status for this materi
     */
    public function getUserProgressStatus($userId)
    {
        $progress = DB::table('materi_progress')
            ->where('materi_id', $this->id)
            ->where('user_id', $userId)
            ->first();

        if (!$progress) {
            return 'not_started';
        }

        return $progress->status ?? 'not_started';
    }

    /**
     * Get user progress for current user (convenience method)
     */
    public function getMyProgress()
    {
        $userId = auth()->id();
        if (!$userId) {
            return 0;
        }
        return $this->getUserProgress($userId);
    }

    /**
     * Get user progress status for current user (convenience method)
     */
    public function getMyProgressStatus()
    {
        $userId = auth()->id();
        if (!$userId) {
            return 'not_started';
        }
        return $this->getUserProgressStatus($userId);
    }

    /**
     * Update user progress for this materi
     */
    public function updateProgress($userId, $status = 'in_progress', $progress = null)
    {
        $existing = DB::table('materi_progress')
            ->where('materi_id', $this->id)
            ->where('user_id', $userId)
            ->first();

        // Jika progress tidak diberikan, hitung berdasarkan file
        if ($progress === null) {
            $totalFiles = $this->total_files_count;
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
                ->where('materi_id', $this->id)
                ->where('user_id', $userId)
                ->update($data);
        } else {
            // Create new
            $data['materi_id'] = $this->id;
            $data['user_id'] = $userId;
            $data['created_at'] = now();
            DB::table('materi_progress')->insert($data);
        }

        return $this;
    }

    /**
     * Check if user has completed this materi
     */
    public function isCompletedBy($userId)
    {
        $status = $this->getUserProgressStatus($userId);
        return $status === 'completed';
    }

    /**
     * Check if user is currently learning this materi
     */
    public function isInProgressBy($userId)
    {
        $status = $this->getUserProgressStatus($userId);
        return $status === 'in_progress';
    }

    /**
     * Mark materi as completed for user
     */
    public function markCompleted($userId)
    {
        return $this->updateProgress($userId, 'completed', 100);
    }

    /**
     * Mark materi as in progress for user
     */
    public function markInProgress($userId)
    {
        return $this->updateProgress($userId, 'in_progress');
    }

    /**
     * Get total users who have completed this materi
     */
    public function getCompletedCount()
    {
        return DB::table('materi_progress')
            ->where('materi_id', $this->id)
            ->where('status', 'completed')
            ->count();
    }

    /**
     * Get total users who are learning this materi
     */
    public function getInProgressCount()
    {
        return DB::table('materi_progress')
            ->where('materi_id', $this->id)
            ->where('status', 'in_progress')
            ->count();
    }

    /**
     * Get completion rate
     */
    public function getCompletionRate()
    {
        $total = DB::table('materi_progress')
            ->where('materi_id', $this->id)
            ->count();
        
        if ($total === 0) {
            return 0;
        }
        
        $completed = DB::table('materi_progress')
            ->where('materi_id', $this->id)
            ->where('status', 'completed')
            ->count();
        
        return round(($completed / $total) * 100, 2);
    }

    /**
     * Get user's progress summary
     */
    public function getUserProgressSummary($userId)
    {
        $progress = DB::table('materi_progress')
            ->where('materi_id', $this->id)
            ->where('user_id', $userId)
            ->first();

        if (!$progress) {
            return [
                'status' => 'not_started',
                'progress' => 0,
                'completed_at' => null,
            ];
        }

        return [
            'status' => $progress->status,
            'progress' => $progress->progress,
            'completed_at' => $progress->completed_at,
        ];
    }

    // ============================================================
    // MUTATORS
    // ============================================================

    /**
     * Set file_data and auto update total_files
     */
    public function setFileDataAttribute($value)
    {
        $this->attributes['file_data'] = is_array($value) ? json_encode($value) : $value;
        
        // Update total_files
        if (is_array($value)) {
            $this->attributes['total_files'] = count($value);
        } elseif (is_string($value) && !empty($value)) {
            $decoded = json_decode($value, true);
            $this->attributes['total_files'] = is_array($decoded) ? count($decoded) : 0;
        } else {
            $this->attributes['total_files'] = 0;
        }
    }
}