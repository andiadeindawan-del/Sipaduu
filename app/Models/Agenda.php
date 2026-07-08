<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    protected $table = 'agendas';

    protected $fillable = [
        'training_id',
        'judul',
        'deskripsi',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'lokasi',
        'status',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

   
    // RELATIONSHIPS
   

    /**
     * Relasi ke Training
     */
    public function training()
    {
        return $this->belongsTo(Training::class);
    }

   
    // ACCESSORS
   

    /**
     * Get status label with icon
     */
    public function getStatusLabelAttribute()
    {
        $labels = [
            'upcoming' => '📅 Akan Datang',
            'ongoing' => '⏳ Sedang Berlangsung',
            'completed' => '✅ Selesai',
            'cancelled' => '❌ Dibatalkan',
            'draft' => '📝 Draft',
            'published' => '📢 Published',
            'selesai' => '✅ Selesai',
        ];
        return $labels[$this->status] ?? $this->status;
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeAttribute()
    {
        $classes = [
            'upcoming' => 'text-bg-primary',
            'ongoing' => 'text-bg-success',
            'completed' => 'text-bg-secondary',
            'cancelled' => 'text-bg-danger',
            'draft' => 'text-bg-secondary',
            'published' => 'text-bg-success',
            'selesai' => 'text-bg-secondary',
        ];
        return $classes[$this->status] ?? 'text-bg-secondary';
    }

    /**
     * Get formatted tanggal (d/m/Y)
     */
    public function getTanggalFormattedAttribute()
    {
        return $this->tanggal ? $this->tanggal->format('d/m/Y') : '-';
    }

    /**
     * Get formatted waktu mulai (H:i)
     */
    public function getWaktuMulaiFormattedAttribute()
    {
        return $this->waktu_mulai ? date('H:i', strtotime($this->waktu_mulai)) : '-';
    }

    /**
     * Get formatted waktu selesai (H:i)
     */
    public function getWaktuSelesaiFormattedAttribute()
    {
        return $this->waktu_selesai ? date('H:i', strtotime($this->waktu_selesai)) : '-';
    }

    /**
     * Get full datetime range
     */
    public function getDateTimeRangeAttribute()
    {
        $date = $this->tanggal_formatted;
        $start = $this->waktu_mulai_formatted;
        $end = $this->waktu_selesai_formatted;
        
        if ($start && $end) {
            return $date . ' ' . $start . ' - ' . $end;
        }
        return $date;
    }

    /**
     * Check if agenda is upcoming
     */
    public function getIsUpcomingAttribute()
    {
        return $this->status === 'upcoming' || ($this->status === 'published' && $this->tanggal > now());
    }

    /**
     * Check if agenda is ongoing
     */
    public function getIsOngoingAttribute()
    {
        return $this->status === 'ongoing' || ($this->status === 'published' && $this->tanggal <= now() && $this->tanggal >= now()->subDay());
    }

    /**
     * Check if agenda is completed
     */
    public function getIsCompletedAttribute()
    {
        return $this->status === 'completed' || $this->status === 'selesai' || ($this->status === 'published' && $this->tanggal < now()->subDay());
    }

   
    // SCOPES
   

    /**
     * Scope untuk agenda yang akan datang
     */
    public function scopeUpcoming($query)
    {
        return $query->whereIn('status', ['upcoming', 'published'])
                     ->where('tanggal', '>=', now());
    }

    /**
     * Scope untuk agenda yang sedang berlangsung
     */
    public function scopeOngoing($query)
    {
        return $query->where('status', 'ongoing')
                     ->orWhere(function($q) {
                         $q->where('status', 'published')
                           ->where('tanggal', '<=', now())
                           ->where('tanggal', '>=', now()->subDay());
                     });
    }

    /**
     * Scope untuk agenda yang selesai
     */
    public function scopeCompleted($query)
    {
        return $query->whereIn('status', ['completed', 'selesai'])
                     ->orWhere(function($q) {
                         $q->where('status', 'published')
                           ->where('tanggal', '<', now()->subDay());
                     });
    }

    /**
     * Scope untuk agenda yang dibatalkan
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
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

   
    // HELPER METHODS
   

    /**
     * Update status based on date
     */
    public function updateStatus()
    {
        if ($this->status === 'cancelled') {
            return;
        }

        $now = now();
        $tanggal = $this->tanggal;

        if ($tanggal < $now->subDay()) {
            $this->status = 'completed';
        } elseif ($tanggal <= $now && $tanggal >= $now->subDay()) {
            $this->status = 'ongoing';
        } else {
            $this->status = 'upcoming';
        }
        
        $this->save();
    }

    /**
     * Check if agenda is active
     */
    public function isActive()
    {
        return in_array($this->status, ['upcoming', 'ongoing', 'published']);
    }

    /**
     * Get duration in hours
     */
    public function getDurationAttribute()
    {
        if (!$this->waktu_mulai || !$this->waktu_selesai) {
            return null;
        }

        $start = strtotime($this->waktu_mulai);
        $end = strtotime($this->waktu_selesai);
        
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
}