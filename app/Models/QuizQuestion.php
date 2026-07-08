<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    protected $table = 'quiz_questions';

    
    protected $fillable = [
        'quiz_id',
        'question',          // Field utama untuk pertanyaan
        'type',              // Field utama untuk tipe (multiple_choice, essay, true_false)
        'points',            // Field utama untuk nilai
        'options',           // Field utama untuk pilihan (JSON)
        'correct_answer',    // Field utama untuk jawaban benar
        'order',
        // Legacy fields (untuk kompatibilitas jika masih ada)
        'pertanyaan',
        'tipe_soal',
        'nilai',
        'score',
        'opsi_a',
        'opsi_b',
        'opsi_c',
        'opsi_d',
        'opsi_e',
        'jawaban_benar',
        'essay_answer_key',
    ];

    // ============================================================
    // CASTS
    // ============================================================
    protected $casts = [
        'points' => 'integer',
        'nilai' => 'integer',
        'score' => 'integer',
        'order' => 'integer',
        'options' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ============================================================
    // APPENDS - Field tambahan untuk view
    // ============================================================
    protected $appends = [
        'question_text',
        'score_value',
        'type_label',
        'type_badge',
        'type_display',
        'formatted_options',
        'correct_answer_value',
        'is_multiple_choice',
        'is_essay',
        'is_true_false',
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

    // ============================================================
    // ACCESSORS - GETTERS
    // ============================================================

    /**
     * Get question text (prioritaskan 'question', fallback ke 'pertanyaan')
     */
    public function getQuestionTextAttribute()
    {
        return $this->question ?? $this->pertanyaan ?? '';
    }

    /**
     * Get score value (prioritaskan 'points', fallback ke 'score' atau 'nilai')
     */
    public function getScoreValueAttribute()
    {
        return $this->points ?? $this->score ?? $this->nilai ?? 1;
    }

    /**
     * Get type label with icon
     */
    public function getTypeLabelAttribute()
    {
        $type = $this->getTypeValue();
        
        $labels = [
            'multiple_choice' => '📝 Pilihan Ganda',
            'pilihan' => '📝 Pilihan Ganda',
            'pilihan_ganda' => '📝 Pilihan Ganda',
            'true_false' => '✅ Benar/Salah',
            'essay' => '✍️ Essay',
        ];
        
        return $labels[$type] ?? '📝 ' . ucfirst($type);
    }

    /**
     * Get type badge class
     */
    public function getTypeBadgeAttribute()
    {
        $type = $this->getTypeValue();
        
        $classes = [
            'multiple_choice' => 'text-bg-primary',
            'pilihan' => 'text-bg-primary',
            'pilihan_ganda' => 'text-bg-primary',
            'true_false' => 'text-bg-success',
            'essay' => 'text-bg-warning',
        ];
        
        return $classes[$type] ?? 'text-bg-secondary';
    }

    /**
     * Get type display (tanpa icon)
     */
    public function getTypeDisplayAttribute()
    {
        $type = $this->getTypeValue();
        
        $labels = [
            'multiple_choice' => 'Pilihan Ganda',
            'pilihan' => 'Pilihan Ganda',
            'pilihan_ganda' => 'Pilihan Ganda',
            'true_false' => 'Benar/Salah',
            'essay' => 'Essay',
        ];
        
        return $labels[$type] ?? ucfirst($type);
    }

    /**
     * Get formatted options as associative array (letter => value)
     */
    public function getFormattedOptionsAttribute()
    {
        $options = [];

        // 1. Coba dari field 'options' (JSON)
        if ($this->options && is_array($this->options)) {
            foreach ($this->options as $index => $option) {
                if (!empty($option)) {
                    $letter = chr(65 + $index); // A, B, C, D, E, F
                    $options[$letter] = $option;
                }
            }
            return $options;
        }

        // 2. Coba dari field legacy (opsi_a, opsi_b, dll)
        $letters = ['A', 'B', 'C', 'D', 'E'];
        $fields = ['opsi_a', 'opsi_b', 'opsi_c', 'opsi_d', 'opsi_e'];
        
        foreach ($fields as $index => $field) {
            if ($this->$field) {
                $options[$letters[$index]] = $this->$field;
            }
        }
        
        return $options;
    }

    /**
     * Get correct answer value (prioritaskan 'correct_answer', fallback ke 'jawaban_benar')
     */
    public function getCorrectAnswerValueAttribute()
    {
        return $this->correct_answer ?? $this->jawaban_benar ?? '';
    }

    /**
     * Get formatted correct answer for display
     */
    public function getFormattedCorrectAnswerAttribute()
    {
        $correct = $this->correct_answer_value;
        
        if ($this->is_true_false) {
            return $correct === 'true' ? '✅ Benar' : '❌ Salah';
        }
        
        return $correct;
    }

    /**
     * Check if question type is multiple choice
     */
    public function getIsMultipleChoiceAttribute()
    {
        $type = $this->getTypeValue();
        return in_array($type, ['multiple_choice', 'pilihan', 'pilihan_ganda']);
    }

    /**
     * Check if question type is true/false
     */
    public function getIsTrueFalseAttribute()
    {
        $type = $this->getTypeValue();
        return $type === 'true_false';
    }

    /**
     * Check if question type is essay
     */
    public function getIsEssayAttribute()
    {
        $type = $this->getTypeValue();
        return $type === 'essay';
    }

    /**
     * Get type icon
     */
    public function getTypeIconAttribute()
    {
        $type = $this->getTypeValue();
        
        $icons = [
            'multiple_choice' => 'bi-list-check',
            'pilihan' => 'bi-list-check',
            'pilihan_ganda' => 'bi-list-check',
            'true_false' => 'bi-check-circle',
            'essay' => 'bi-pencil',
        ];
        
        return $icons[$type] ?? 'bi-question';
    }

    /**
     * Get options count
     */
    public function getOptionsCountAttribute()
    {
        return count($this->formatted_options);
    }

    /**
     * Check if question has options
     */
    public function getHasOptionsAttribute()
    {
        return $this->options_count > 0;
    }

    // ============================================================
    // MUTATORS - SETTERS
    // ============================================================

    /**
     * Set options (auto convert to JSON)
     */
    public function setOptionsAttribute($value)
    {
        if (is_array($value)) {
            // Filter empty values
            $filtered = array_filter(array_values($value), function($item) {
                return !empty(trim($item));
            });
            $this->attributes['options'] = !empty($filtered) ? json_encode($filtered) : null;
        } else {
            $this->attributes['options'] = $value;
        }
    }

    /**
     * Set type (auto convert from legacy format)
     */
    public function setTypeAttribute($value)
    {
        $map = [
            'pilihan' => 'multiple_choice',
            'pilihan_ganda' => 'multiple_choice',
            'benar_salah' => 'true_false',
            'essay' => 'essay',
        ];
        
        $this->attributes['type'] = $map[$value] ?? $value;
    }

    /**
     * Set points (auto convert from legacy values)
     */
    public function setPointsAttribute($value)
    {
        $this->attributes['points'] = $value;
        // Juga set legacy fields untuk kompatibilitas
        $this->attributes['nilai'] = $value;
        $this->attributes['score'] = $value;
    }

    /**
     * Set question (auto convert to legacy fields)
     */
    public function setQuestionAttribute($value)
    {
        $this->attributes['question'] = $value;
        // Juga set legacy field untuk kompatibilitas
        $this->attributes['pertanyaan'] = $value;
    }

    /**
     * Set correct_answer (auto convert to legacy fields)
     */
    public function setCorrectAnswerAttribute($value)
    {
        $this->attributes['correct_answer'] = $value;
        // Juga set legacy field untuk kompatibilitas
        $this->attributes['jawaban_benar'] = $value;
    }

    // ============================================================
    // HELPER METHODS
    // ============================================================

    /**
     * Get normalized type value
     */
    private function getTypeValue()
    {
        return $this->type ?? $this->tipe_soal ?? 'multiple_choice';
    }

    /**
     * Check if answer is correct
     */
    public function isAnswerCorrect($answer)
    {
        $correct = $this->correct_answer_value;
        
        if (empty($correct)) {
            return false;
        }
        
        // Untuk multiple choice, bandingkan huruf (A, B, C, D)
        if ($this->is_multiple_choice) {
            return strtoupper(trim($answer)) === strtoupper(trim($correct));
        }
        
        // Untuk true/false
        if ($this->is_true_false) {
            return strtolower(trim($answer)) === strtolower(trim($correct));
        }
        
        // Untuk essay, cek kata kunci
        if ($this->is_essay) {
            $keywords = explode('|', $correct);
            $answerLower = strtolower(trim($answer));
            foreach ($keywords as $keyword) {
                if (strpos($answerLower, strtolower(trim($keyword))) !== false) {
                    return true;
                }
            }
            return false;
        }
        
        return false;
    }

    /**
     * Check if question is multiple choice (alias)
     */
    public function isPilihanGanda()
    {
        return $this->is_multiple_choice;
    }

    /**
     * Check if question is essay (alias)
     */
    public function isEssay()
    {
        return $this->is_essay;
    }

    /**
     * Check if question is true/false (alias)
     */
    public function isTrueFalse()
    {
        return $this->is_true_false;
    }

    /**
     * Get options as array (legacy compatibility)
     */
    public function getOptionsArrayAttribute()
    {
        return $this->formatted_options;
    }

    /**
     * Get tipe soal label (legacy compatibility)
     */
    public function getTipeSoalLabelAttribute()
    {
        return $this->type_display;
    }

    /**
     * Get raw options from database
     */
    public function getRawOptionsAttribute()
    {
        return $this->options;
    }

    /**
     * Check if question has options
     */
    public function hasOptions()
    {
        return $this->has_options;
    }
}