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
        'registration_number',
        'status',
        'is_passed',
        'registered_at',
        'approved_at',
        'approved_by',
        'completed_at',
        'final_grade',
        'attendance_percentage',
        'notes',
        'rejection_reason',
    ];

    protected $casts = [
        'registered_at' => 'datetime',
        'approved_at' => 'datetime',
        'completed_at' => 'datetime',
        'is_passed' => 'boolean',
        'final_grade' => 'integer',
        'attendance_percentage' => 'integer',
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

    /**
     * Relasi ke Training
     */
    public function training()
    {
        return $this->belongsTo(Training::class, 'training_id');
    }

    /**
     * Relasi ke User (Peserta)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke User (Approver)
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Relasi ke Sertifikat
     */
    public function certificate()
    {
        return $this->hasOne(Sertifikat::class, 'training_registration_id');
    }

    /**
     * Relasi ke Quiz Attempts
     */
    public function quizAttempts()
    {
        return $this->hasMany(QuizAttempt::class, 'training_registration_id');
    }

    // ============================================================
    // SCOPES
    // ============================================================

    /**
     * Scope untuk registrasi pending
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope untuk registrasi approved
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope untuk registrasi completed
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope untuk registrasi rejected
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope untuk registrasi cancelled
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    /**
     * Scope untuk registrasi yang lulus
     */
    public function scopePassed($query)
    {
        return $query->where('is_passed', true);
    }

    /**
     * Scope untuk registrasi yang belum memiliki sertifikat
     */
    public function scopeDoesntHaveCertificate($query)
    {
        return $query->whereDoesntHave('certificate');
    }

    /**
     * Scope untuk registrasi berdasarkan user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope untuk registrasi berdasarkan training
     */
    public function scopeForTraining($query, $trainingId)
    {
        return $query->where('training_id', $trainingId);
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
            'pending' => '⏳ Pending',
            'approved' => '✅ Approved',
            'completed' => '🎉 Completed',
            'rejected' => '❌ Rejected',
            'cancelled' => '❌ Cancelled',
        ];
        return $labels[$this->status] ?? ucfirst($this->status);
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeAttribute()
    {
        $classes = [
            'pending' => 'badge bg-warning',
            'approved' => 'badge bg-success',
            'completed' => 'badge bg-info',
            'rejected' => 'badge bg-danger',
            'cancelled' => 'badge bg-secondary',
        ];
        return $classes[$this->status] ?? 'badge bg-secondary';
    }

    /**
     * Check if registration is approved
     */
    public function getIsApprovedAttribute()
    {
        return $this->status === 'approved' || $this->status === 'completed';
    }

    /**
     * Check if registration is completed
     */
    public function getIsCompletedAttribute()
    {
        return $this->status === 'completed';
    }

    /**
     * Check if registration is pending
     */
    public function getIsPendingAttribute()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if registration is rejected
     */
    public function getIsRejectedAttribute()
    {
        return $this->status === 'rejected';
    }

    /**
     * Check if registration is cancelled
     */
    public function getIsCancelledAttribute()
    {
        return $this->status === 'cancelled';
    }

    // ============================================================
    // HELPER METHODS
    // ============================================================

    /**
     * Check if registration is pending
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if registration is approved
     */
    public function isApproved()
    {
        return $this->status === 'approved' || $this->status === 'completed';
    }

    /**
     * Check if registration is completed
     */
    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    /**
     * Check if registration is rejected
     */
    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    /**
     * Check if registration is cancelled
     */
    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    /**
     * Check if user passed the training
     */
    public function isPassed()
    {
        return $this->is_passed === true;
    }

    /**
     * Check if registration has certificate
     */
    public function hasCertificate()
    {
        return $this->certificate()->exists();
    }

    /**
     * Approve registration
     */
    public function approve($userId = null)
    {
        $this->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => $userId ?? auth()->id(),
        ]);
        
        return $this;
    }

    /**
     * Reject registration
     */
    public function reject($reason = null)
    {
        $this->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
        ]);
        
        return $this;
    }

    /**
     * Cancel registration
     */
    public function cancel()
    {
        $this->update([
            'status' => 'cancelled',
        ]);
        
        return $this;
    }

    /**
     * Mark registration as completed
     */
    public function markAsCompleted($grade = null, $passed = true)
    {
        $this->update([
            'status' => 'completed',
            'is_passed' => $passed,
            'completed_at' => now(),
            'final_grade' => $grade,
        ]);
        
        return $this;
    }

    /**
     * Generate unique registration number
     */
    public static function generateRegistrationNumber()
    {
        $prefix = 'REG-' . date('Ymd');
        $last = self::where('registration_number', 'LIKE', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if ($last) {
            $lastNumber = intval(substr($last->registration_number, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return $prefix . '-' . $newNumber;
    }

    /**
     * Get user's progress for this training
     */
    public function getProgress()
    {
        if ($this->status === 'completed') {
            return 100;
        }
        
        $training = $this->training;
        $userId = $this->user_id;
        
        return $training->getUserProgress($userId);
    }

    /**
     * Get attendance status
     */
    public function getAttendanceStatus()
    {
        if ($this->attendance_percentage === null) {
            return 'Belum ada data';
        }
        
        if ($this->attendance_percentage >= 80) {
            return '✅ Baik';
        } elseif ($this->attendance_percentage >= 60) {
            return '⚠️ Cukup';
        } else {
            return '❌ Kurang';
        }
    }

    /**
     * Get certificate URL if exists
     */
    public function getCertificateUrl()
    {
        if (!$this->hasCertificate()) {
            return null;
        }
        
        return route('certificate.show', $this->certificate->id);
    }

    /**
     * Get certificate download URL
     */
    public function getCertificateDownloadUrl()
    {
        if (!$this->hasCertificate()) {
            return null;
        }
        
        return route('certificate.download', $this->certificate->id);
    }
}