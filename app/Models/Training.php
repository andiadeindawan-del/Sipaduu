<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    use HasFactory;

    protected $table = 'trainings';

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
        'gambar',
        'slug',
        'is_free',
        'harga',
        'level',
        'order',
    ];

    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'kapasitas' => 'integer',
        'is_free' => 'boolean',
        'harga' => 'decimal:2',
        'order' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = [
        'status_label',
        'status_badge',
        'is_available',
        'formatted_date_range',
        'participants_count',
        'available_slots',
        'type_label',
        'type_badge',
        'location_display',
        'duration_in_days',
        'is_ongoing',
        'is_upcoming',
        'is_completed_training'
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
     * Relasi ke Peserta melalui TrainingRegistration
     */
    public function registrations()
    {
        return $this->hasMany(TrainingRegistration::class, 'training_id');
    }

    /**
     * Relasi ke User melalui TrainingRegistration
     */
    public function participants()
    {
        return $this->belongsToMany(User::class, 'training_registrations', 'training_id', 'user_id')
                    ->withPivot('status', 'registered_at', 'approved_at', 'completed_at', 'final_grade', 'is_passed')
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
        if ($this->status !== 'published' && $this->status !== 'berjalan') {
            return false;
        }

        if ($this->kapasitas && $this->participants_count >= $this->kapasitas) {
            return false;
        }

        // Jika sudah selesai
        if ($this->tanggal_selesai && $this->tanggal_selesai < now()) {
            return false;
        }

        return true;
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
        return $this->registrations()
                    ->whereIn('status', ['approved', 'completed'])
                    ->count();
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
        
        return $this->tanggal_mulai->diffInDays($this->tanggal_selesai) + 1;
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
    public function getIsOngoingAttribute()
    {
        return $this->isOngoing();
    }

    /**
     * Check if training is upcoming
     */
    public function getIsUpcomingAttribute()
    {
        return $this->isUpcoming();
    }

    /**
     * Check if training is completed
     */
    public function getIsCompletedTrainingAttribute()
    {
        return $this->isCompletedTraining();
    }

    // ============================================================
    // HELPER METHODS
    // ============================================================

    /**
     * Get user's registration for this training
     */
    public function getUserRegistration($userId = null)
    {
        $userId = $userId ?? auth()->id();
        return $this->registrations()->where('user_id', $userId)->first();
    }

    /**
     * Check if user is registered/enrolled in this training
     */
    public function isRegistered($userId = null)
    {
        $userId = $userId ?? auth()->id();
        return $this->registrations()
                    ->where('user_id', $userId)
                    ->whereIn('status', ['pending', 'approved', 'completed'])
                    ->exists();
    }

    /**
     * Check if user is approved for this training
     */
    public function isApproved($userId = null)
    {
        $userId = $userId ?? auth()->id();
        return $this->registrations()
                    ->where('user_id', $userId)
                    ->whereIn('status', ['approved', 'completed'])
                    ->exists();
    }

    /**
     * Check if user has completed this training
     */
    public function isCompletedByUser($userId = null)
    {
        $userId = $userId ?? auth()->id();
        return $this->registrations()
                    ->where('user_id', $userId)
                    ->where('status', 'completed')
                    ->exists();
    }

    /**
     * Get user's progress for this training
     */
    public function getUserProgress($userId = null)
    {
        $userId = $userId ?? auth()->id();
        $registration = $this->getUserRegistration($userId);
        
        if (!$registration) {
            return 0;
        }
        
        // Hitung progress dari materi dan quiz
        $totalMaterials = $this->materis()->count();
        $totalQuizzes = $this->quizzes()->count();
        $totalItems = $totalMaterials + $totalQuizzes;
        
        if ($totalItems === 0) {
            return $registration->status === 'completed' ? 100 : 0;
        }
        
        // Hitung materi yang sudah selesai
        $completedMaterials = $this->materis()
            ->whereHas('progress', function($q) use ($userId) {
                $q->where('user_id', $userId)->where('status', 'completed');
            })->count();
        
        // Hitung quiz yang sudah selesai
        $completedQuizzes = $this->quizzes()
            ->whereHas('attempts', function($q) use ($userId) {
                $q->where('user_id', $userId)->where('status', 'completed');
            })->count();
        
        $completedItems = $completedMaterials + $completedQuizzes;
        
        return round(($completedItems / $totalItems) * 100);
    }

    /**
     * Check if user has certificate for this training
     */
    public function hasCertificate($userId = null)
    {
        $userId = $userId ?? auth()->id();
        $registration = $this->getUserRegistration($userId);
        
        if (!$registration) {
            return false;
        }
        
        return $registration->certificate()->exists();
    }

    /**
     * Get user's certificate for this training
     */
    public function getUserCertificate($userId = null)
    {
        $userId = $userId ?? auth()->id();
        $registration = $this->getUserRegistration($userId);
        
        if (!$registration) {
            return null;
        }
        
        return $registration->certificate;
    }

    /**
     * Enroll user to training
     */
    public function enrollUser($userId = null, $status = 'pending')
    {
        $userId = $userId ?? auth()->id();
        
        if ($this->isRegistered($userId)) {
            return false;
        }
        
        if (!$this->is_available) {
            return false;
        }
        
        $registration = TrainingRegistration::create([
            'training_id' => $this->id,
            'user_id' => $userId,
            'status' => $status,
            'registration_number' => TrainingRegistration::generateRegistrationNumber(),
            'registered_at' => now(),
        ]);
        
        return $registration;
    }

    /**
     * Unenroll user from training
     */
    public function unenrollUser($userId = null)
    {
        $userId = $userId ?? auth()->id();
        $registration = $this->getUserRegistration($userId);
        
        if (!$registration) {
            return false;
        }
        
        return $registration->delete();
    }

    /**
     * Check if training is full
     */
    public function isFull()
    {
        if (!$this->kapasitas) {
            return false;
        }
        
        return $this->participants_count >= $this->kapasitas;
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
        
        if ($this->status === 'published' || $this->status === 'berjalan') {
            return $this->tanggal_selesai < now();
        }
        
        return false;
    }

    /**
     * Get training status berdasarkan tanggal
     */
    public function getCurrentStatus()
    {
        if ($this->status === 'draft') {
            return 'draft';
        }
        
        if ($this->status === 'dibatalkan') {
            return 'dibatalkan';
        }
        
        if ($this->isCompletedTraining()) {
            return 'selesai';
        }
        
        if ($this->isOngoing()) {
            return 'berjalan';
        }
        
        if ($this->isUpcoming()) {
            return 'akan_datang';
        }
        
        return $this->status;
    }
}