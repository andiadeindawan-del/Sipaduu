<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TrainingRegistration extends Model
{
    use HasFactory;

    protected $table = 'training_registrations';

    protected $fillable = [
        'user_id',
        'training_id',
        'registration_number',
        'status',
        'is_passed',
        'registered_at',
        'approved_at',
        'approved_by',
        'completed_at',
        'final_grade',
        'notes',
        'rejection_reason',
        'attendance_percentage',
    ];

    protected $casts = [
        'registered_at' => 'datetime',
        'approved_at' => 'datetime',
        'completed_at' => 'datetime',
        'is_passed' => 'boolean',
        'final_grade' => 'integer',
        'attendance_percentage' => 'integer',
    ];

    // ==================== RELATIONSHIPS ====================

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

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function quizAttempts()
    {
        return $this->hasMany(QuizAttempt::class, 'training_registration_id');
    }

    // ==================== SCOPES ====================

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopePassed($query)
    {
        return $query->where('is_passed', true);
    }

    public function scopeDoesntHaveCertificate($query)
    {
        return $query->whereDoesntHave('certificate');
    }

    // ==================== HELPERS ====================

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isPassed()
    {
        return $this->is_passed === true;
    }

    public function hasCertificate()
    {
        return $this->certificate()->exists();
    }

    public function approve($userId = null)
    {
        $this->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => $userId ?? auth()->id(),
        ]);
        
        return $this;
    }

    public function reject($reason = null)
    {
        $this->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
        ]);
        
        return $this;
    }

    public function cancel()
    {
        $this->update([
            'status' => 'cancelled',
        ]);
        
        return $this;
    }

    public function markAsCompleted($grade = null)
    {
        $this->update([
            'status' => 'completed',
            'is_passed' => true,
            'completed_at' => now(),
            'final_grade' => $grade,
        ]);
        
        return $this;
    }

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
}