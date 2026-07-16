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

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function trainer()
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }

    public function registrations()
    {
        return $this->hasMany(TrainingRegistration::class, 'training_id');
    }

    /**
     * Relasi ke User melalui TrainingRegistration
     * PERBAIKAN: HANYA gunakan kolom yang ADA di database
     */
    public function participants()
    {
        return $this->belongsToMany(User::class, 'training_registrations', 'training_id', 'user_id')
                    ->withPivot([
                        'status',
                        'created_at',
                        'updated_at'
                    ])
                    ->withTimestamps();
    }

    public function sertifikats()
    {
        return $this->hasMany(Sertifikat::class);
    }

    public function materis()
    {
        return $this->hasMany(Materi::class);
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    // ============================================================
    // SCOPES
    // ============================================================

    public function scopeActive($query)
    {
        return $query->where('status', 'published')
                     ->where('tanggal_mulai', '>=', now());
    }

    public function scopeUpcoming($query)
    {
        return $query->where('tanggal_mulai', '>=', now())
                     ->where('status', 'published');
    }

    public function scopeOngoing($query)
    {
        return $query->where('status', 'berjalan')
                     ->orWhere(function($q) {
                         $q->where('status', 'published')
                           ->where('tanggal_mulai', '<=', now())
                           ->where('tanggal_selesai', '>=', now());
                     });
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'selesai');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope untuk training yang diikuti oleh user tertentu
     * PERBAIKAN: Gunakan training_registrations
     */
    public function scopeEnrolledBy($query, $userId)
    {
        return $query->whereHas('registrations', function($q) use ($userId) {
            $q->where('user_id', $userId)
              ->whereIn('status', ['pending', 'registered', 'approved', 'completed']);
        });
    }

    /**
     * Scope untuk training yang disetujui oleh user tertentu
     */
    public function scopeApprovedBy($query, $userId)
    {
        return $query->whereHas('registrations', function($q) use ($userId) {
            $q->where('user_id', $userId)
              ->whereIn('status', ['approved', 'completed']);
        });
    }

    /**
     * Scope untuk training yang telah selesai oleh user tertentu
     */
    public function scopeCompletedBy($query, $userId)
    {
        return $query->whereHas('registrations', function($q) use ($userId) {
            $q->where('user_id', $userId)
              ->where('status', 'completed');
        });
    }

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

    public function getIsAvailableAttribute()
    {
        if ($this->status !== 'published' && $this->status !== 'berjalan') {
            return false;
        }

        if ($this->kapasitas && $this->participants_count >= $this->kapasitas) {
            return false;
        }

        if ($this->tanggal_selesai && $this->tanggal_selesai < now()) {
            return false;
        }

        return true;
    }

    public function getFormattedDateRangeAttribute()
    {
        $start = $this->tanggal_mulai ? $this->tanggal_mulai->format('d/m/Y') : 'TBD';
        $end = $this->tanggal_selesai ? $this->tanggal_selesai->format('d/m/Y') : 'TBD';
        
        if ($start === $end) {
            return $start;
        }
        
        return $start . ' - ' . $end;
    }

    public function getParticipantsCountAttribute()
    {
        return $this->registrations()
                    ->whereIn('status', ['approved', 'completed', 'registered'])
                    ->count();
    }

    public function getAvailableSlotsAttribute()
    {
        if (!$this->kapasitas) {
            return null;
        }
        return max(0, $this->kapasitas - $this->participants_count);
    }

    public function getTypeLabelAttribute()
    {
        $labels = [
            'online' => '🖥️ Online',
            'offline' => '🏢 Offline',
            'hybrid' => '🔄 Hybrid'
        ];
        return $labels[$this->tipe] ?? ucfirst($this->tipe);
    }

    public function getTypeBadgeAttribute()
    {
        $classes = [
            'online' => 'badge bg-primary',
            'offline' => 'badge bg-success',
            'hybrid' => 'badge bg-warning'
        ];
        return $classes[$this->tipe] ?? 'badge bg-secondary';
    }

    public function getLocationDisplayAttribute()
    {
        if ($this->tipe === 'online') {
            return $this->link_meeting ?? 'Link meeting belum tersedia';
        }
        
        return $this->lokasi ?? 'Lokasi belum ditentukan';
    }

    public function getDurationInDaysAttribute()
    {
        if (!$this->tanggal_mulai || !$this->tanggal_selesai) {
            return null;
        }
        
        return $this->tanggal_mulai->diffInDays($this->tanggal_selesai) + 1;
    }

    public function getDurationInHoursAttribute()
    {
        if (!$this->tanggal_mulai || !$this->tanggal_selesai) {
            return null;
        }
        
        return $this->tanggal_mulai->diffInHours($this->tanggal_selesai);
    }

    public function getIsOngoingAttribute()
    {
        return $this->isOngoing();
    }

    public function getIsUpcomingAttribute()
    {
        return $this->isUpcoming();
    }

    public function getIsCompletedTrainingAttribute()
    {
        return $this->isCompletedTraining();
    }

    // ============================================================
    // HELPER METHODS
    // ============================================================

    public function getUserRegistration($userId = null)
    {
        $userId = $userId ?? auth()->id();
        return $this->registrations()->where('user_id', $userId)->first();
    }

    /**
     * Alias untuk isRegistered()
     */
    public function isEnrolled($userId = null)
    {
        return $this->isRegistered($userId);
    }

    public function isRegistered($userId = null)
    {
        $userId = $userId ?? auth()->id();
        return $this->registrations()
                    ->where('user_id', $userId)
                    ->whereIn('status', ['pending', 'registered', 'approved', 'completed'])
                    ->exists();
    }

    public function isApproved($userId = null)
    {
        $userId = $userId ?? auth()->id();
        return $this->registrations()
                    ->where('user_id', $userId)
                    ->whereIn('status', ['approved', 'completed'])
                    ->exists();
    }

    public function isCompletedByUser($userId = null)
    {
        $userId = $userId ?? auth()->id();
        return $this->registrations()
                    ->where('user_id', $userId)
                    ->where('status', 'completed')
                    ->exists();
    }

    public function getUserProgress($userId = null)
    {
        $userId = $userId ?? auth()->id();
        $registration = $this->getUserRegistration($userId);
        
        if (!$registration) {
            return 0;
        }
        
        $totalMaterials = $this->materis()->count();
        $totalQuizzes = $this->quizzes()->count();
        $totalItems = $totalMaterials + $totalQuizzes;
        
        if ($totalItems === 0) {
            return $registration->status === 'completed' ? 100 : 0;
        }
        
        $completedMaterials = $this->materis()
            ->whereHas('progress', function($q) use ($userId) {
                $q->where('user_id', $userId)->where('status', 'completed');
            })->count();
        
        $completedQuizzes = $this->quizzes()
            ->whereHas('attempts', function($q) use ($userId) {
                $q->where('user_id', $userId)->where('status', 'completed');
            })->count();
        
        $completedItems = $completedMaterials + $completedQuizzes;
        
        return round(($completedItems / $totalItems) * 100);
    }

    public function hasCertificate($userId = null)
    {
        $userId = $userId ?? auth()->id();
        $registration = $this->getUserRegistration($userId);
        
        if (!$registration) {
            return false;
        }
        
        return $registration->certificate()->exists();
    }

    public function getUserCertificate($userId = null)
    {
        $userId = $userId ?? auth()->id();
        $registration = $this->getUserRegistration($userId);
        
        if (!$registration) {
            return null;
        }
        
        return $registration->certificate;
    }

    public function enrollUser($userId = null, $status = 'registered')
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
        ]);
        
        return $registration;
    }

    public function unenrollUser($userId = null)
    {
        $userId = $userId ?? auth()->id();
        $registration = $this->getUserRegistration($userId);
        
        if (!$registration) {
            return false;
        }
        
        return $registration->delete();
    }

    public function isFull()
    {
        if (!$this->kapasitas) {
            return false;
        }
        
        return $this->participants_count >= $this->kapasitas;
    }

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

    public function isUpcoming()
    {
        if ($this->status !== 'published') {
            return false;
        }
        
        return $this->tanggal_mulai > now();
    }

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