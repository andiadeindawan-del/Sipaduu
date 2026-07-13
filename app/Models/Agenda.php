<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Agenda extends Model
{
    use HasFactory;

    protected $table = 'agendas';

    protected $fillable = [
        'training_id',
        'created_by',        // ← TAMBAHKAN
        'judul',
        'deskripsi',
        'tanggal',
        'jam_mulai',         // ← PERBAIKAN: jam_mulai (bukan waktu_mulai)
        'jam_selesai',       // ← PERBAIKAN: jam_selesai (bukan waktu_selesai)
        'lokasi',
        'link_meeting',      // ← TAMBAHKAN
        'tipe',              // ← TAMBAHKAN
        'status',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jam_mulai' => 'datetime',
        'jam_selesai' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ============================================================
    // RELATIONSHIPS
    // ============================================================

    /**
     * Relasi ke Training
     */
    public function training()
    {
        return $this->belongsTo(Training::class);
    }

    /**
     * Relasi ke User (Creator) - TAMBAHKAN
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relasi ke User (Creator) - Alias
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ============================================================
    // ACCESSORS
    // ============================================================

    /**
     * Get status label with icon
     */
    public function getStatusLabelAttribute()
    {
        $labels = [
            'draft' => '📝 Draft',
            'published' => '📢 Published',
            'selesai' => '✅ Selesai',
            'dibatalkan' => '❌ Dibatalkan',
        ];
        return $labels[$this->status] ?? ucfirst($this->status);
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeAttribute()
    {
        $classes = [
            'draft' => 'text-bg-secondary',
            'published' => 'text-bg-success',
            'selesai' => 'text-bg-info',
            'dibatalkan' => 'text-bg-danger',
        ];
        return $classes[$this->status] ?? 'text-bg-secondary';
    }

    /**
     * Get tipe label
     */
    public function getTipeLabelAttribute()
    {
        $labels = [
            'online' => '🌐 Online',
            'offline' => '🏢 Offline',
            'hybrid' => '🔄 Hybrid',
        ];
        return $labels[$this->tipe] ?? ucfirst($this->tipe);
    }

    /**
     * Get tipe badge class
     */
    public function getTipeBadgeAttribute()
    {
        $classes = [
            'online' => 'text-bg-primary',
            'offline' => 'text-bg-success',
            'hybrid' => 'text-bg-warning',
        ];
        return $classes[$this->tipe] ?? 'text-bg-secondary';
    }

    /**
     * Get tipe icon
     */
    public function getTipeIconAttribute()
    {
        $icons = [
            'online' => 'bi-wifi',
            'offline' => 'bi-building',
            'hybrid' => 'bi-arrows',
        ];
        return $icons[$this->tipe] ?? 'bi-question-circle';
    }

    /**
     * Get formatted tanggal (d/m/Y)
     */
    public function getTanggalFormattedAttribute()
    {
        return $this->tanggal ? $this->tanggal->format('d/m/Y') : '-';
    }

    /**
     * Get formatted jam mulai (H:i)
     */
    public function getJamMulaiFormattedAttribute()
    {
        return $this->jam_mulai ? date('H:i', strtotime($this->jam_mulai)) : '-';
    }

    /**
     * Get formatted jam selesai (H:i)
     */
    public function getJamSelesaiFormattedAttribute()
    {
        return $this->jam_selesai ? date('H:i', strtotime($this->jam_selesai)) : '-';
    }

    /**
     * Get full datetime range
     */
    public function getDateTimeRangeAttribute()
    {
        $date = $this->tanggal_formatted;
        $start = $this->jam_mulai_formatted;
        $end = $this->jam_selesai_formatted;
        
        if ($start && $end) {
            return $date . ' ' . $start . ' - ' . $end;
        }
        return $date;
    }

    /**
     * Get creator name
     */
    public function getCreatorNameAttribute()
    {
        return $this->creator ? $this->creator->nama ?? $this->creator->name : 'Unknown';
    }

    /**
     * Get training title
     */
    public function getTrainingTitleAttribute()
    {
        return $this->training ? $this->training->judul : 'Umum';
    }

    /**
     * Check if agenda is today
     */
    public function getIsTodayAttribute()
    {
        return $this->tanggal && $this->tanggal->isToday();
    }

    /**
     * Check if agenda is upcoming
     */
    public function getIsUpcomingAttribute()
    {
        return $this->tanggal && $this->tanggal->isFuture() && $this->status !== 'dibatalkan';
    }

    /**
     * Check if agenda is past
     */
    public function getIsPastAttribute()
    {
        return $this->tanggal && $this->tanggal->isPast() && $this->status !== 'dibatalkan';
    }

    // ============================================================
    // SCOPES
    // ============================================================

    /**
     * Scope untuk agenda yang akan datang
     */
    public function scopeUpcoming($query)
    {
        return $query->where('status', 'published')
                     ->whereDate('tanggal', '>=', now());
    }

    /**
     * Scope untuk agenda hari ini
     */
    public function scopeToday($query)
    {
        return $query->whereDate('tanggal', now());
    }

    /**
     * Scope untuk agenda yang selesai
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'selesai');
    }

    /**
     * Scope untuk agenda yang dibatalkan
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', 'dibatalkan');
    }

    /**
     * Scope untuk agenda draft
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope untuk agenda published
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope untuk agenda berdasarkan training
     */
    public function scopeByTraining($query, $trainingId)
    {
        return $query->where('training_id', $trainingId);
    }

    /**
     * Scope untuk agenda berdasarkan tipe
     */
    public function scopeByType($query, $type)
    {
        return $query->where('tipe', $type);
    }

    /**
     * Scope untuk agenda berdasarkan creator
     */
    public function scopeByCreator($query, $userId)
    {
        return $query->where('created_by', $userId);
    }

    /**
     * Scope untuk agenda berdasarkan rentang tanggal
     */
    public function scopeDateRange($query, $from, $to)
    {
        return $query->whereBetween('tanggal', [$from, $to]);
    }

    /**
     * Scope untuk pencarian
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('judul', 'like', "%$search%")
                     ->orWhere('deskripsi', 'like', "%$search%")
                     ->orWhere('lokasi', 'like', "%$search%");
    }

    // ============================================================
    // HELPER METHODS
    // ============================================================

    /**
     * Check if agenda is active
     */
    public function isActive()
    {
        return $this->status === 'published' && !$this->is_past;
    }

    /**
     * Get duration in hours
     */
    public function getDurationAttribute()
    {
        if (!$this->jam_mulai || !$this->jam_selesai) {
            return null;
        }

        $start = strtotime($this->jam_mulai);
        $end = strtotime($this->jam_selesai);
        
        return round(($end - $start) / 3600, 1);
    }

    /**
     * Get duration formatted
     */
    public function getDurationFormattedAttribute()
    {
        $duration = $this->duration;
        if ($duration === null) {
            return '-';
        }

        $hours = floor($duration);
        $minutes = round(($duration - $hours) * 60);
        
        if ($hours > 0 && $minutes > 0) {
            return $hours . ' jam ' . $minutes . ' menit';
        } elseif ($hours > 0) {
            return $hours . ' jam';
        } else {
            return $minutes . ' menit';
        }
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
}