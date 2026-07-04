<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $table = 'quizzes';

    protected $fillable = [
        'training_id',
        'materi_id',
        'judul',
        'deskripsi',
        'durasi',
        'passing_score',
        'max_attempt',
        'is_random',
        'show_result',
        'status',
        'start_date',
        'end_date',
        'order',
    ];

    protected $casts = [
        'durasi' => 'integer',
        'passing_score' => 'integer',
        'max_attempt' => 'integer',
        'is_random' => 'boolean',
        'show_result' => 'boolean',
        'order' => 'integer',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = [
        'status_label',
        'status_badge',
        'is_available',
        'status_text',
        'status_color',
        'total_questions',
        'total_participants',
        'average_score',
        'highest_score',
        'lowest_score',
        'passing_rate',
        'formatted_duration',
        'date_range',
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
     * Relasi ke Materi
     */
    public function materi()
    {
        return $this->belongsTo(Materi::class);
    }

    /**
     * Relasi ke Pertanyaan (One to Many)
     */
    public function questions()
    {
        return $this->hasMany(QuizQuestion::class)->orderBy('order');
    }

    /**
     * Relasi ke Quiz Attempts (One to Many)
     */
    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    /**
     * Relasi ke User yang mengikuti quiz (Many to Many melalui attempts)
     */
    public function participants()
    {
        return $this->belongsToMany(User::class, 'quiz_attempts', 'quiz_id', 'user_id')
                    ->withPivot('score', 'status', 'completed_at')
                    ->withTimestamps();
    }

    /**
     * Relasi ke User yang membuat quiz (creator)
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ============================================================
    // SCOPES
    // ============================================================

    /**
     * Scope untuk quiz yang published
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope untuk quiz yang draft
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope untuk quiz yang archived
     */
    public function scopeArchived($query)
    {
        return $query->where('status', 'archived');
    }

    /**
     * Scope untuk quiz yang aktif (published dan dalam jadwal)
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'published')
                     ->where(function($q) {
                         $q->whereNull('start_date')
                           ->orWhere('start_date', '<=', now());
                     })
                     ->where(function($q) {
                         $q->whereNull('end_date')
                           ->orWhere('end_date', '>=', now());
                     });
    }

    /**
     * Scope untuk pencarian
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('judul', 'like', "%$search%")
                     ->orWhere('deskripsi', 'like', "%$search%");
    }

    /**
     * Scope untuk quiz yang belum dimulai
     */
    public function scopeUpcoming($query)
    {
        return $query->where('status', 'published')
                     ->where('start_date', '>', now());
    }

    /**
     * Scope untuk quiz yang sudah berakhir
     */
    public function scopeExpired($query)
    {
        return $query->where('status', 'published')
                     ->where('end_date', '<', now());
    }

    /**
     * Scope untuk quiz yang memiliki materi
     */
    public function scopeHasMateri($query)
    {
        return $query->whereNotNull('materi_id');
    }

    /**
     * Scope untuk quiz yang memiliki training
     */
    public function scopeHasTraining($query)
    {
        return $query->whereNotNull('training_id');
    }

    /**
     * Scope untuk quiz berdasarkan materi
     */
    public function scopeByMateri($query, $materiId)
    {
        return $query->where('materi_id', $materiId);
    }

    /**
     * Scope untuk quiz berdasarkan training
     */
    public function scopeByTraining($query, $trainingId)
    {
        return $query->where('training_id', $trainingId);
    }

    /**
     * Scope untuk quiz yang memiliki pertanyaan
     */
    public function scopeHasQuestions($query)
    {
        return $query->has('questions');
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
     * Check if quiz is available
     */
    public function getIsAvailableAttribute()
    {
        if ($this->status !== 'published') {
            return false;
        }

        if ($this->start_date && $this->start_date > now()) {
            return false;
        }

        if ($this->end_date && $this->end_date < now()) {
            return false;
        }

        return true;
    }

    /**
     * Get quiz status in text
     */
    public function getStatusTextAttribute()
    {
        if ($this->status === 'draft') {
            return 'Draft';
        }

        if ($this->status === 'archived') {
            return 'Diarsipkan';
        }

        if ($this->is_available) {
            if ($this->start_date && $this->start_date > now()) {
                return 'Akan Datang';
            }
            return 'Sedang Berlangsung';
        }

        if ($this->end_date && $this->end_date < now()) {
            return 'Selesai';
        }

        return 'Tidak Tersedia';
    }

    /**
     * Get quiz status color
     */
    public function getStatusColorAttribute()
    {
        if ($this->status === 'draft') {
            return 'secondary';
        }

        if ($this->status === 'archived') {
            return 'secondary';
        }

        if ($this->is_available) {
            if ($this->start_date && $this->start_date > now()) {
                return 'warning';
            }
            return 'success';
        }

        if ($this->end_date && $this->end_date < now()) {
            return 'danger';
        }

        return 'secondary';
    }

    /**
     * Get total questions
     */
    public function getTotalQuestionsAttribute()
    {
        return $this->questions()->count();
    }

    /**
     * Get total participants
     */
    public function getTotalParticipantsAttribute()
    {
        return $this->attempts()->distinct('user_id')->count();
    }

    /**
     * Get average score
     */
    public function getAverageScoreAttribute()
    {
        return $this->attempts()->where('status', 'completed')->avg('score') ?? 0;
    }

    /**
     * Get highest score
     */
    public function getHighestScoreAttribute()
    {
        return $this->attempts()->where('status', 'completed')->max('score') ?? 0;
    }

    /**
     * Get lowest score
     */
    public function getLowestScoreAttribute()
    {
        return $this->attempts()->where('status', 'completed')->min('score') ?? 0;
    }

    /**
     * Get passing rate
     */
    public function getPassingRateAttribute()
    {
        $total = $this->attempts()->where('status', 'completed')->count();
        if ($total === 0) {
            return 0;
        }

        $passed = $this->attempts()->where('status', 'completed')
                    ->where('score', '>=', $this->passing_score)
                    ->count();
        return round(($passed / $total) * 100, 2);
    }

    /**
     * Get formatted duration
     */
    public function getFormattedDurationAttribute()
    {
        if (!$this->durasi) {
            return '-';
        }

        $hours = floor($this->durasi / 60);
        $minutes = $this->durasi % 60;

        if ($hours > 0) {
            return "{$hours} jam {$minutes} menit";
        }

        return "{$minutes} menit";
    }

    /**
     * Get date range display
     */
    public function getDateRangeAttribute()
    {
        $start = $this->start_date ? $this->start_date->format('d/m/Y H:i') : 'Tidak ada';
        $end = $this->end_date ? $this->end_date->format('d/m/Y H:i') : 'Tidak ada';

        return "{$start} - {$end}";
    }

    // ============================================================
    // HELPER METHODS
    // ============================================================

    /**
     * Check if user has taken this quiz
     */
    public function isTakenBy($userId)
    {
        return $this->attempts()->where('user_id', $userId)->exists();
    }

    /**
     * Get user's score for this quiz
     */
    public function getUserScore($userId)
    {
        $attempt = $this->attempts()->where('user_id', $userId)->first();
        return $attempt ? $attempt->score : null;
    }

    /**
     * Get user's attempt for this quiz
     */
    public function getUserAttempt($userId)
    {
        return $this->attempts()->where('user_id', $userId)->first();
    }

    /**
     * Get user's best attempt for this quiz
     */
    public function getUserBestAttempt($userId)
    {
        return $this->attempts()->where('user_id', $userId)
                    ->where('status', 'completed')
                    ->orderBy('score', 'desc')
                    ->first();
    }

    /**
     * Get user's latest attempt for this quiz
     */
    public function getUserLatestAttempt($userId)
    {
        return $this->attempts()->where('user_id', $userId)
                    ->orderBy('created_at', 'desc')
                    ->first();
    }

    /**
     * Check if user passed this quiz
     */
    public function isPassedBy($userId)
    {
        $score = $this->getUserScore($userId);
        return $score !== null && $score >= $this->passing_score;
    }

    /**
     * Get remaining attempts for user
     */
    public function getRemainingAttempts($userId)
    {
        $attemptsCount = $this->attempts()->where('user_id', $userId)->count();
        return max(0, $this->max_attempt - $attemptsCount);
    }

    /**
     * Check if user can take this quiz
     */
    public function canTake($userId)
    {
        // Check if quiz is available
        if (!$this->is_available) {
            return false;
        }

        // Check if user has remaining attempts
        if ($this->max_attempt > 0 && $this->getRemainingAttempts($userId) <= 0) {
            return false;
        }

        return true;
    }

    /**
     * Get quiz progress for user
     */
    public function getProgressForUser($userId)
    {
        $attempt = $this->getUserAttempt($userId);
        if (!$attempt) {
            return null;
        }

        return [
            'score' => $attempt->score,
            'status' => $attempt->status,
            'started_at' => $attempt->started_at,
            'completed_at' => $attempt->completed_at,
            'is_passed' => $this->isPassedBy($userId),
            'percentage' => $attempt->percentage ?? 0,
            'attempt_number' => $this->attempts()->where('user_id', $userId)->count(),
        ];
    }

    /**
     * Get all user attempts for this quiz
     */
    public function getUserAttempts($userId)
    {
        return $this->attempts()->where('user_id', $userId)->get();
    }

    /**
     * Get quiz statistics
     */
    public function getStatisticsAttribute()
    {
        $totalAttempts = $this->attempts()->count();
        $completedAttempts = $this->attempts()->where('status', 'completed')->count();
        $inProgressAttempts = $this->attempts()->where('status', 'in_progress')->count();
        $expiredAttempts = $this->attempts()->where('status', 'expired')->count();

        return [
            'total_attempts' => $totalAttempts,
            'completed_attempts' => $completedAttempts,
            'in_progress_attempts' => $inProgressAttempts,
            'expired_attempts' => $expiredAttempts,
            'total_participants' => $this->total_participants,
            'average_score' => $this->average_score,
            'highest_score' => $this->highest_score,
            'lowest_score' => $this->lowest_score,
            'passing_rate' => $this->passing_rate,
            'total_questions' => $this->total_questions,
        ];
    }

    /**
     * Check if quiz has questions
     */
    public function hasQuestions()
    {
        return $this->questions()->count() > 0;
    }

    /**
     * Get quiz duration in minutes
     */
    public function getDurationInMinutesAttribute()
    {
        return $this->durasi ?? 0;
    }

    /**
     * Get quiz duration in seconds
     */
    public function getDurationInSecondsAttribute()
    {
        return ($this->durasi ?? 0) * 60;
    }
}