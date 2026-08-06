<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        // Basic Auth
        'name',
        'email',
        'password',
        
        // Identity
        'nik',
        'nama',
        'role',
        
        // Contact
        'no_telepon',
        
        // Business (untuk peserta)
        'nama_usaha',
        'nib',
        'jenis_usaha',
        'alamat_lengkap',
        
        // Employee (untuk admin/trainer)
        'departemen',
        'jabatan',
        
        // Profile
        'foto',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    // ============================================================
    // RELATIONSHIPS
    // ============================================================

    public function sertifikats()
    {
        return $this->hasMany(Sertifikat::class);
    }

    public function surveyResponses()
    {
        return $this->hasMany(SurveyResponse::class);
    }

    public function trainingDiajar()
    {
        return $this->hasMany(Training::class, 'trainer_id');
    }

    public function trainingDiikuti()
    {
        return $this->belongsToMany(Training::class, 'training_participants', 'user_id', 'training_id')
                    ->withPivot('status', 'registered_at')
                    ->withTimestamps();
    }

    public function materis()
    {
        return $this->hasMany(Materi::class, 'created_by');
    }

    /**
     * Relasi ke Quiz Attempts
     */
    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    public function surveys()
    {
        return $this->hasMany(Survey::class);
    }

    /**
     * Relasi ke Quiz yang sudah dikerjakan (Many to Many melalui attempts)
     */
    public function quizAttempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    /**
     * Relasi ke Quiz yang sudah dikerjakan (Many to Many melalui attempts)
     */
    public function quizzesTaken()
    {
        return $this->belongsToMany(Quiz::class, 'quiz_attempts', 'user_id', 'quiz_id')
                    ->withPivot('score', 'status', 'completed_at')
                    ->withTimestamps();
    }

    /**
     * Relasi ke Sertifikat (alias untuk sertifikats)
     */
    public function certificates()
    {
        return $this->hasMany(Sertifikat::class);
    }

    /**
     * Relasi ke Training (alias untuk trainingDiikuti)
     */
    public function trainings()
    {
        return $this->belongsToMany(Training::class, 'training_participants', 'user_id', 'training_id')
                    ->withPivot('status', 'registered_at', 'completed_at', 'certificate_id')
                    ->withTimestamps();
    }

    /**
     * Relasi ke Quiz yang dibuat
     */
    public function createdQuizzes()
    {
        return $this->hasMany(Quiz::class, 'created_by');
    }

    /**
     * Relasi ke Training yang dibuat (sebagai creator)
     */
    public function createdTrainings()
    {
        return $this->hasMany(Training::class, 'created_by');
    }

    // ============================================================
    // ACCESSORS
    // ============================================================

    public function getDisplayNameAttribute(): string
    {
        return $this->nama ?? $this->name ?? 'User';
    }

    public function getInitialsAttribute(): string
    {
        $name = $this->display_name;
        $words = explode(' ', $name);
        $initials = '';

        foreach ($words as $word) {
            if (!empty($word)) {
                $initials .= strtoupper($word[0]);
            }
        }

        return substr($initials, 0, 2);
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->foto) {
            return asset('storage/' . $this->foto);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->display_name) . '&background=4e9af1&color=fff&size=100';
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->status === 'aktif' ? 'Aktif' : 'Nonaktif';
    }

    public function getStatusBadgeAttribute(): string
    {
        return $this->status === 'aktif' ? 'success' : 'secondary';
    }

    public function getRoleLabelAttribute(): string
    {
        $labels = [
            'admin' => 'Admin',
            'trainer' => 'Trainer',
            'peserta' => 'Peserta',
        ];
        return $labels[$this->role] ?? $this->role;
    }

    public function getRoleBadgeAttribute(): string
    {
        $classes = [
            'admin' => 'danger',
            'trainer' => 'info',
            'peserta' => 'secondary',
        ];
        return $classes[$this->role] ?? 'secondary';
    }

    /**
     * Get total quiz points
     */
    public function getTotalQuizPointsAttribute()
    {
        return $this->quizAttempts()
                    ->where('status', 'completed')
                    ->sum('score');
    }

    /**
     * Get average quiz score
     */
    public function getAverageQuizScoreAttribute()
    {
        return $this->quizAttempts()
                    ->where('status', 'completed')
                    ->avg('score') ?? 0;
    }

    /**
     * Get total certificates count
     */
    public function getTotalCertificatesAttribute()
    {
        return $this->certificates()->count();
    }

    /**
     * Get total trainings completed
     */
    public function getTotalTrainingsCompletedAttribute()
    {
        return $this->trainings()
                    ->wherePivot('status', 'completed')
                    ->count();
    }

    // ============================================================
    // SCOPES
    // ============================================================

    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'nonaktif');
    }

    public function scopeAdmin($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeTrainer($query)
    {
        return $query->where('role', 'trainer');
    }

    public function scopePeserta($query)
    {
        return $query->where('role', 'peserta');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('nama', 'like', "%$search%")
              ->orWhere('name', 'like', "%$search%")
              ->orWhere('email', 'like', "%$search%")
              ->orWhere('nik', 'like', "%$search%")
              ->orWhere('no_telepon', 'like', "%$search%");
        });
    }

    // ============================================================
    // HELPER METHODS
    // ============================================================

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isTrainer(): bool
    {
        return $this->role === 'trainer';
    }

    public function isPeserta(): bool
    {
        return $this->role === 'peserta';
    }

    public function isActive(): bool
    {
        return $this->status === 'aktif';
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles);
    }

    /**
     * Check if user has completed a training
     */
    public function hasCompletedTraining($trainingId)
    {
        return $this->trainings()
                    ->where('training_id', $trainingId)
                    ->wherePivot('status', 'completed')
                    ->exists();
    }

    /**
     * Check if user has certificate for training
     */
    public function hasCertificateForTraining($trainingId)
    {
        return $this->trainings()
                    ->where('training_id', $trainingId)
                    ->whereNotNull('pivot.certificate_id')
                    ->exists();
    }

    /**
     * Get user's score for a quiz
     */
    public function getQuizScore($quizId)
    {
        $attempt = $this->quizAttempts()
                        ->where('quiz_id', $quizId)
                        ->where('status', 'completed')
                        ->orderBy('score', 'desc')
                        ->first();
        return $attempt ? $attempt->score : null;
    }

    /**
     * Check if user has passed a quiz
     */
    public function hasPassedQuiz($quizId)
    {
        $attempt = $this->quizAttempts()
                        ->where('quiz_id', $quizId)
                        ->where('status', 'completed')
                        ->orderBy('score', 'desc')
                        ->first();
        
        if (!$attempt) {
            return false;
        }
        
        $quiz = Quiz::find($quizId);
        return $attempt->score >= ($quiz->passing_score ?? 70);
    }
}