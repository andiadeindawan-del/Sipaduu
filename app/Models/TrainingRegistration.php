<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TrainingRegistration extends Model
{
    use HasFactory;

    protected $table = 'training_registrations';

    protected $fillable = [
        'training_id',
        'user_id',
        'status',
        'alasan_penolakan',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = [
        'status_label',
        'status_badge',
        'is_approved',
        'is_completed',
        'is_pending',
        'is_rejected',
        'is_cancelled',
    ];

    // ============================================================
    // RELATIONSHIPS
    // ============================================================

    public function training()
    {
        return $this->belongsTo(Training::class, 'training_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function certificate()
    {
        return $this->hasOne(Sertifikat::class, 'training_registration_id');
    }

    public function quizAttempts()
    {
        return $this->hasMany(QuizAttempt::class, 'training_registration_id');
    }

    // ============================================================
    // SCOPES
    // ============================================================

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeRegistered($query)
    {
        return $query->where('status', 'terdaftar');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'disetujui');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'selesai');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForTraining($query, $trainingId)
    {
        return $query->where('training_id', $trainingId);
    }

    // ============================================================
    // ACCESSORS
    // ============================================================

    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => '⏳ Menunggu Verifikasi',
            'terdaftar' => '📋 Registered',
            'disetujui' => '✅ Approved',
            'selesai' => '🎉 Completed',
            'rejected' => '❌ Rejected',
            'ditolak' => '❌ Ditolak', // Menambahkan mapping ditolak
            'cancelled' => '❌ Cancelled',
            'dibatalkan' => '❌ Dibatalkan', // Menambahkan mapping dibatalkan
        ];
        return $labels[$this->status] ?? ucfirst($this->status);
    }

    public function getStatusBadgeAttribute()
    {
        $classes = [
            'pending' => 'badge bg-warning',
            'terdaftar' => 'badge bg-info',
            'disetujui' => 'badge bg-success',
            'selesai' => 'badge bg-success',
            'rejected' => 'badge bg-danger',
            'ditolak' => 'badge bg-danger',
            'cancelled' => 'badge bg-secondary',
            'dibatalkan' => 'badge bg-secondary',
        ];
        return $classes[$this->status] ?? 'badge bg-secondary';
    }

    public function getIsApprovedAttribute()
    {
        return $this->status === 'disetujui' || $this->status === 'selesai';
    }

    public function getIsCompletedAttribute()
    {
        return $this->status === 'selesai';
    }

    public function getIsPendingAttribute()
    {
        return $this->status === 'pending';
    }

    public function getIsRejectedAttribute()
    {
        return $this->status === 'rejected';
    }

    public function getIsCancelledAttribute()
    {
        return $this->status === 'cancelled';
    }

    // ============================================================
    // HELPER METHODS
    // ============================================================

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isRegistered()
    {
        return $this->status === 'terdaftar';
    }

    public function isApproved()
    {
        return $this->status === 'disetujui' || $this->status === 'selesai';
    }

    public function isCompleted()
    {
        return $this->status === 'selesai';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    public function isPassed()
    {
        return $this->hasCertificate();
    }

    public function hasCertificate()
    {
        return $this->certificate()->exists();
    }

    public function approve()
    {
        $this->update(['status' => 'disetujui']);
        return $this;
    }

    public function reject()
    {
        $this->update(['status' => 'rejected']);
        return $this;
    }

    public function cancel()
    {
        $this->update(['status' => 'cancelled']);
        return $this;
    }

    public function markAsCompleted()
    {
        $this->update(['status' => 'selesai']);
        return $this;
    }

    public function getProgress()
    {
        if ($this->status === 'selesai') {
            return 100;
        }
        
        return $this->training->getUserProgress($this->user_id);
    }
}
