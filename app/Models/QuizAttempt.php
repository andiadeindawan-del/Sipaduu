<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    protected $table = 'quiz_attempts';

    protected $fillable = [
        'quiz_id',
        'user_id',
        'score',
        'total_questions',
        'correct_answers',
        'answers',
        'started_at',
        'completed_at',
        'status',
    ];

    protected $casts = [
        'score' => 'integer',
        'total_questions' => 'integer',
        'correct_answers' => 'integer',
        'answers' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = [
        'status_label',
        'percentage',
        'is_passed',
        'duration',
        'formatted_duration',
    ];

    // ============================================================
    // RELATIONSHIPS
    // ============================================================

    /**
     * Relasi ke Quiz
     */
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ============================================================
    // SCOPES
    // ============================================================

    /**
     * Scope untuk attempt yang sedang berjalan
     */
    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    /**
     * Scope untuk attempt yang sudah selesai
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope untuk attempt yang expired
     */
    public function scopeExpired($query)
    {
        return $query->where('status', 'expired');
    }

    /**
     * Scope untuk attempt yang lulus
     */
    public function scopePassed($query)
    {
        return $query->whereHas('quiz', function ($q) {
            $q->whereColumn('quiz_attempts.score', '>=', 'quizzes.passing_score');
        })->where('status', 'completed');
    }

    /**
     * Scope untuk attempt yang tidak lulus
     */
    public function scopeFailed($query)
    {
        return $query->whereHas('quiz', function ($q) {
            $q->whereColumn('quiz_attempts.score', '<', 'quizzes.passing_score');
        })->where('status', 'completed');
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
            'in_progress' => '🔄 Sedang Dikerjakan',
            'completed' => '✅ Selesai',
            'expired' => '⏰ Kadaluarsa',
        ];
        return $labels[$this->status] ?? $this->status;
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeAttribute()
    {
        $classes = [
            'in_progress' => 'badge bg-warning',
            'completed' => 'badge bg-success',
            'expired' => 'badge bg-danger',
        ];
        return $classes[$this->status] ?? 'badge bg-secondary';
    }

    /**
     * Get percentage score
     */
    public function getPercentageAttribute()
    {
        if ($this->total_questions > 0) {
            return round(($this->score / $this->total_questions) * 100, 2);
        }
        return 0;
    }

    /**
     * Check if attempt is passed
     */
    public function getIsPassedAttribute()
    {
        if ($this->status !== 'completed') {
            return false;
        }
        
        $passingScore = $this->quiz ? $this->quiz->passing_score : 70;
        return $this->percentage >= $passingScore;
    }

    /**
     * Get duration in minutes
     */
    public function getDurationAttribute()
    {
        if (!$this->started_at || !$this->completed_at) {
            return null;
        }
        return $this->started_at->diffInMinutes($this->completed_at);
    }

    /**
     * Get formatted duration
     */
    public function getFormattedDurationAttribute()
    {
        if (!$this->started_at || !$this->completed_at) {
            return '-';
        }

        $minutes = $this->started_at->diffInMinutes($this->completed_at);
        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;

        if ($hours > 0) {
            return "{$hours} jam {$remainingMinutes} menit";
        }
        return "{$minutes} menit";
    }

    /**
     * Get number of wrong answers
     */
    public function getWrongAnswersAttribute()
    {
        return $this->total_questions - $this->correct_answers;
    }

    // ============================================================
    // HELPER METHODS
    // ============================================================

    /**
     * Check if attempt is completed
     */
    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    /**
     * Check if attempt is in progress
     */
    public function isInProgress()
    {
        return $this->status === 'in_progress';
    }

    /**
     * Check if attempt is expired
     */
    public function isExpired()
    {
        return $this->status === 'expired';
    }

    /**
     * Calculate score from answers
     */
    public function calculateScore()
    {
        if (empty($this->answers)) {
            return 0;
        }

        $questions = $this->quiz->questions;
        $correct = 0;

        foreach ($questions as $index => $question) {
            $userAnswer = $this->answers[$index] ?? null;
            if ($question->isAnswerCorrect($userAnswer)) {
                $correct++;
            }
        }

        $this->correct_answers = $correct;
        $this->total_questions = $questions->count();
        $this->score = $correct;
        $this->save();

        return $this->score;
    }

    /**
     * Complete the attempt
     */
    public function complete()
    {
        $this->status = 'completed';
        $this->completed_at = now();
        $this->calculateScore();
        $this->save();

        return $this;
    }

    /**
     * Get answer for specific question
     */
    public function getAnswer($index)
    {
        if (isset($this->answers[$index])) {
            return $this->answers[$index];
        }
        return null;
    }

    /**
     * Check if answer is correct for specific question
     */
    public function isAnswerCorrect($index)
    {
        if (!$this->isCompleted()) {
            return false;
        }

        $questions = $this->quiz->questions;
        if (!isset($questions[$index])) {
            return false;
        }

        $userAnswer = $this->getAnswer($index);
        return $questions[$index]->isAnswerCorrect($userAnswer);
    }

    /**
     * Get results summary
     */
    public function getSummaryAttribute()
    {
        return [
            'total_questions' => $this->total_questions,
            'correct_answers' => $this->correct_answers,
            'wrong_answers' => $this->wrong_answers,
            'score' => $this->score,
            'percentage' => $this->percentage,
            'is_passed' => $this->is_passed,
            'duration' => $this->formatted_duration,
            'status' => $this->status_label,
        ];
    }
}