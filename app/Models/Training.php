<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    use HasFactory;

    protected $fillable = [
        'kategori_id',
        'trainer_id',
        'judul',
        'deskripsi',
        'tipe',
        'lokasi',
        'link_meeting',
        'tanggal_mulai',
        'tanggal_selesai',
        'kapasitas',
        'status',
        'gambar'
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = [
        'status_label',
        'status_badge',
        'is_available',
        'formatted_date_range',
        'participants_count'
    ];

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
     * Relasi ke Trainer (User)
     */
    public function trainer()
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }

    /**
     * Relasi ke Peserta (Many to Many)
     */
    public function participants()
    {
        return $this->belongsToMany(User::class, 'training_participants', 'training_id', 'user_id')
                    ->withPivot('status', 'registered_at', 'completed_at', 'certificate_id')
                    ->withTimestamps();
    }

    /**
     * Relasi ke Sertifikat
     */
    public function sertifikats()
    {
        return $this->hasMany(Sertifikat::class);
    }

    /**
     * Relasi ke Materi
     */
    public function materis()
    {
        return $this->hasMany(Materi::class);
    }

    /**
     * Relasi ke Quiz
     */
    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    // ============================================================
    // SCOPES
    // ============================================================

    /**
     * Scope untuk training yang aktif
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'published')
                     ->where('tanggal_mulai', '>=', now());
    }

    /**
     * Scope untuk training yang akan datang
     */
    public function scopeUpcoming($query)
    {
        return $query->where('tanggal_mulai', '>=', now())
                     ->where('status', 'published');
    }

    /**
     * Scope untuk training yang sedang berjalan
     */
    public function scopeOngoing($query)
    {
        return $query->where('status', 'berjalan')
                     ->orWhere(function($q) {
                         $q->where('status', 'published')
                           ->where('tanggal_mulai', '<=', now())
                           ->where('tanggal_selesai', '>=', now());
                     });
    }

    /**
     * Scope untuk training yang selesai
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'selesai');
    }

    /**
     * Scope untuk training yang draft
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope untuk training yang published
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope untuk pencarian
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('judul', 'like', "%$search%")
              ->orWhere('deskripsi', 'like', "%$search%")
              ->orWhere('lokasi', 'like', "%$search%");
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
            'berjalan' => '🔄 Berjalan',
            'selesai' => '✅ Selesai',
            'dibatalkan' => '❌ Dibatalkan'
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
            'berjalan' => 'badge bg-warning',
            'selesai' => 'badge bg-info',
            'dibatalkan' => 'badge bg-danger'
        ];
        return $classes[$this->status] ?? 'badge bg-secondary';
    }

    /**
     * Check if training is available
     */
    public function getIsAvailableAttribute()
    {
        if ($this->status !== 'published') {
            return false;
        }

        if ($this->kapasitas && $this->participants()->count() >= $this->kapasitas) {
            return false;
        }

        return $this->tanggal_mulai >= now();
    }

    /**
     * Get formatted date range
     */
    public function getFormattedDateRangeAttribute()
    {
        $start = $this->tanggal_mulai ? $this->tanggal_mulai->format('d/m/Y') : 'TBD';
        $end = $this->tanggal_selesai ? $this->tanggal_selesai->format('d/m/Y') : 'TBD';
        
        if ($start === $end) {
            return $start;
        }
        
        return $start . ' - ' . $end;
    }

    /**
     * Get participants count
     */
    public function getParticipantsCountAttribute()
    {
        return $this->participants()->count();
    }

    /**
     * Get available slots
     */
    public function getAvailableSlotsAttribute()
    {
        if (!$this->kapasitas) {
            return null;
        }
        return max(0, $this->kapasitas - $this->participants_count);
    }

    /**
     * Get progress for a specific user
     */
    public function getUserProgress($userId)
    {
        $totalMaterials = $this->materis()->count();
        $completedMaterials = $this->materis()
            ->whereHas('progress', function($q) use ($userId) {
                $q->where('user_id', $userId)->where('status', 'completed');
            })->count();
        
        $totalQuizzes = $this->quizzes()->count();
        $completedQuizzes = $this->quizzes()
            ->whereHas('attempts', function($q) use ($userId) {
                $q->where('user_id', $userId)->where('status', 'completed');
            })->count();
        
        $totalItems = $totalMaterials + $totalQuizzes;
        $completedItems = $completedMaterials + $completedQuizzes;
        
        return $totalItems > 0 ? round(($completedItems / $totalItems) * 100) : 0;
    }

    // ============================================================
    // HELPER METHODS
    // ============================================================

    /**
     * Check if user is enrolled in this training
     */
    public function isEnrolled($userId = null)
    {
        $userId = $userId ?? auth()->id();
        return $this->participants()->where('user_id', $userId)->exists();
    }

    /**
     * Check if user has completed this training
     */
    public function isCompleted($userId = null)
    {
        $userId = $userId ?? auth()->id();
        return $this->participants()
                    ->where('user_id', $userId)
                    ->wherePivot('status', 'completed')
                    ->exists();
    }

    /**
     * Get user's enrollment status
     */
    public function getEnrollmentStatus($userId = null)
    {
        $userId = $userId ?? auth()->id();
        $participant = $this->participants()
            ->where('user_id', $userId)
            ->first();
        
        if (!$participant) {
            return 'not_enrolled';
        }
        
        return $participant->pivot->status ?? 'registered';
    }

    /**
     * Get user's enrollment date
     */
    public function getEnrollmentDate($userId = null)
    {
        $userId = $userId ?? auth()->id();
        $participant = $this->participants()
            ->where('user_id', $userId)
            ->first();
        
        if (!$participant) {
            return null;
        }
        
        return $participant->pivot->registered_at;
    }

    /**
     * Get user's progress for this training
     */
    public function getProgress($userId = null)
    {
        $userId = $userId ?? auth()->id();
        return $this->getUserProgress($userId);
    }

    /**
     * Check if user has certificate for this training
     */
    public function hasCertificate($userId = null)
    {
        $userId = $userId ?? auth()->id();
        return $this->participants()
                    ->where('user_id', $userId)
                    ->whereNotNull('pivot.certificate_id')
                    ->exists();
    }

    /**
     * Get user's certificate for this training
     */
    public function getUserCertificate($userId = null)
    {
        $userId = $userId ?? auth()->id();
        $participant = $this->participants()
            ->where('user_id', $userId)
            ->first();
        
        if (!$participant || !$participant->pivot->certificate_id) {
            return null;
        }
        
        return Sertifikat::find($participant->pivot->certificate_id);
    }

    /**
     * Enroll user to training
     */
    public function enrollUser($userId = null, $status = 'registered')
    {
        $userId = $userId ?? auth()->id();
        
        if ($this->isEnrolled($userId)) {
            return false;
        }
        
        if (!$this->is_available) {
            return false;
        }
        
        $this->participants()->attach($userId, [
            'status' => $status,
            'registered_at' => now()
        ]);
        
        return true;
    }

    /**
     * Unenroll user from training
     */
    public function unenrollUser($userId = null)
    {
        $userId = $userId ?? auth()->id();
        
        if (!$this->isEnrolled($userId)) {
            return false;
        }
        
        $this->participants()->detach($userId);
        
        return true;
    }

    /**
     * Complete training for user
     */
    public function completeUser($userId = null)
    {
        $userId = $userId ?? auth()->id();
        
        if (!$this->isEnrolled($userId)) {
            return false;
        }
        
        $this->participants()->updateExistingPivot($userId, [
            'status' => 'completed',
            'completed_at' => now()
        ]);
        
        return true;
    }

    /**
     * Check if training is full
     */
    public function isFull()
    {
        if (!$this->kapasitas) {
            return false;
        }
        
        return $this->participants()->count() >= $this->kapasitas;
    }

    /**
     * Get training type label
     */
    public function getTypeLabelAttribute()
    {
        $labels = [
            'online' => '🖥️ Online',
            'offline' => '🏢 Offline',
            'hybrid' => '🔄 Hybrid'
        ];
        return $labels[$this->tipe] ?? ucfirst($this->tipe);
    }

    /**
     * Get training type badge
     */
    public function getTypeBadgeAttribute()
    {
        $classes = [
            'online' => 'badge bg-primary',
            'offline' => 'badge bg-success',
            'hybrid' => 'badge bg-warning'
        ];
        return $classes[$this->tipe] ?? 'badge bg-secondary';
    }

    /**
     * Get location display
     */
    public function getLocationDisplayAttribute()
    {
        if ($this->tipe === 'online') {
            return $this->link_meeting ?? 'Link meeting belum tersedia';
        }
        
        return $this->lokasi ?? 'Lokasi belum ditentukan';
    }

    /**
     * Get duration in days
     */
    public function getDurationInDaysAttribute()
    {
        if (!$this->tanggal_mulai || !$this->tanggal_selesai) {
            return null;
        }
        
        return $this->tanggal_mulai->diffInDays($this->tanggal_selesai);
    }

    /**
     * Get duration in hours
     */
    public function getDurationInHoursAttribute()
    {
        if (!$this->tanggal_mulai || !$this->tanggal_selesai) {
            return null;
        }
        
        return $this->tanggal_mulai->diffInHours($this->tanggal_selesai);
    }

    /**
     * Check if training is ongoing
     */
    public function isOngoing()
    {
        if ($this->status === 'berjalan') {
            return true;
        }
        
        if ($this->status === 'published') {
            $now = now();
            return $this->tanggal_mulai <= $now && $this->tanggal_selesai >= $now;
        }
        
        return false;
    }

    /**
     * Check if training is upcoming
     */
    public function isUpcoming()
    {
        if ($this->status !== 'published') {
            return false;
        }
        
        return $this->tanggal_mulai > now();
    }

    /**
     * Check if training is completed
     */
    public function isCompletedTraining()
    {
        if ($this->status === 'selesai') {
            return true;
        }
        
        if ($this->status === 'published') {
            return $this->tanggal_selesai < now();
        }
        
        return false;
    }
}