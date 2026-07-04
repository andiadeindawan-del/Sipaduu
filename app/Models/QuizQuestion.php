<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    protected $table = 'quiz_questions';

    protected $fillable = [
        'quiz_id',
        'question',         
        'pertanyaan',        
        'type',              
        'tipe_soal',         
        'score',             
        'nilai',             
        'options',           
        'opsi_a',            
        'opsi_b',            
        'opsi_c',            
        'opsi_d',            
        'opsi_e',            
        'correct_answer',    
        'jawaban_benar',     
        'order',
    ];

    protected $casts = [
        'score' => 'integer',
        'nilai' => 'integer',
        'order' => 'integer',
        'options' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = [
        'type_label',
        'type_badge',
        'question_text',
        'score_value',
        'formatted_options',
        'correct_answer_value',
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
     * Get question text (prioritaskan field baru)
     */
    public function getQuestionTextAttribute()
    {
        return $this->question ?? $this->pertanyaan ?? '';
    }

    /**
     * Get score value (prioritaskan field baru)
     */
    public function getScoreValueAttribute()
    {
        return $this->score ?? $this->nilai ?? 1;
    }

    /**
     * Get type label
     */
    public function getTypeLabelAttribute()
    {
        $type = $this->type ?? $this->tipe_soal ?? 'multiple_choice';
        
        $labels = [
            'multiple_choice' => '📝 Pilihan Ganda',
            'pilihan' => '📝 Pilihan Ganda',
            'true_false' => '✅ Benar/Salah',
            'essay' => '✍️ Essay',
            'pilihan_ganda' => '📝 Pilihan Ganda',
        ];
        
        return $labels[$type] ?? ucfirst($type);
    }

    /**
     * Get type badge class
     */
    public function getTypeBadgeAttribute()
    {
        $type = $this->type ?? $this->tipe_soal ?? 'multiple_choice';
        
        $classes = [
            'multiple_choice' => 'text-bg-primary',
            'pilihan' => 'text-bg-primary',
            'true_false' => 'text-bg-success',
            'essay' => 'text-bg-warning',
            'pilihan_ganda' => 'text-bg-primary',
        ];
        
        return $classes[$type] ?? 'text-bg-secondary';
    }

    /**
     * Get formatted options array
     */
    public function getFormattedOptionsAttribute()
    {
        // Cek dari field options (JSON)
        if ($this->options && is_array($this->options)) {
            $result = [];
            foreach ($this->options as $index => $option) {
                $letter = chr(65 + $index); // A, B, C, D, E
                $result[$letter] = $option;
            }
            return $result;
        }

        // Cek dari field legacy (opsi_a, opsi_b, dll)
        $options = [];
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
     * Get correct answer value (prioritaskan field baru)
     */
    public function getCorrectAnswerValueAttribute()
    {
        return $this->correct_answer ?? $this->jawaban_benar ?? '';
    }

    /**
     * Check if question type is multiple choice
     */
    public function getIsMultipleChoiceAttribute()
    {
        $type = $this->type ?? $this->tipe_soal ?? '';
        return in_array($type, ['multiple_choice', 'pilihan', 'pilihan_ganda']);
    }

    /**
     * Check if question type is true/false
     */
    public function getIsTrueFalseAttribute()
    {
        $type = $this->type ?? $this->tipe_soal ?? '';
        return $type === 'true_false';
    }

    /**
     * Check if question type is essay
     */
    public function getIsEssayAttribute()
    {
        $type = $this->type ?? $this->tipe_soal ?? '';
        return $type === 'essay';
    }

    // ============================================================
    // MUTATORS - SETTERS
    // ============================================================

    /**
     * Set options (auto convert to array)
     */
    public function setOptionsAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['options'] = json_encode(array_values($value));
        } else {
            $this->attributes['options'] = $value;
        }
    }

    /**
     * Set type (auto convert from legacy format)
     */
    public function setTypeAttribute($value)
    {
        // Konversi dari format lama ke format baru
        $map = [
            'pilihan' => 'multiple_choice',
            'pilihan_ganda' => 'multiple_choice',
            'essay' => 'essay',
            'true_false' => 'true_false',
        ];
        
        $this->attributes['type'] = $map[$value] ?? $value;
    }

    // ============================================================
    // HELPER METHODS
    // ============================================================

    /**
     * Check if question has options
     */
    public function hasOptions()
    {
        return count($this->formatted_options) > 0;
    }

    /**
     * Get total options count
     */
    public function getOptionsCountAttribute()
    {
        return count($this->formatted_options);
    }

    /**
     * Check if answer is correct
     */
    public function isAnswerCorrect($answer)
    {
        $correct = $this->correct_answer_value;
        
        // Untuk multiple choice, bandingkan huruf (A, B, C, D)
        if ($this->is_multiple_choice) {
            return strtoupper($answer) === strtoupper($correct);
        }
        
        // Untuk true/false
        if ($this->is_true_false) {
            return strtolower($answer) === strtolower($correct);
        }
        
        // Untuk essay, cek kata kunci
        if ($this->is_essay) {
            return stripos($answer, $correct) !== false;
        }
        
        return false;
    }

    /**
     * Get display label for question type
     */
    public function getTypeDisplayAttribute()
    {
        $type = $this->type ?? $this->tipe_soal ?? 'multiple_choice';
        
        $labels = [
            'multiple_choice' => 'Pilihan Ganda',
            'pilihan' => 'Pilihan Ganda',
            'true_false' => 'Benar/Salah',
            'essay' => 'Essay',
            'pilihan_ganda' => 'Pilihan Ganda',
        ];
        
        return $labels[$type] ?? ucfirst($type);
    }

    /**
     * Get icon for question type
     */
    public function getTypeIconAttribute()
    {
        $type = $this->type ?? $this->tipe_soal ?? 'multiple_choice';
        
        $icons = [
            'multiple_choice' => 'bi-list-check',
            'pilihan' => 'bi-list-check',
            'true_false' => 'bi-check-circle',
            'essay' => 'bi-pencil',
            'pilihan_ganda' => 'bi-list-check',
        ];
        
        return $icons[$type] ?? 'bi-question';
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
     * Check if this question type is multiple choice
     */
    public function isPilihanGanda()
    {
        return $this->is_multiple_choice;
    }

    /**
     * Check if this question type is essay
     */
    public function isEssay()
    {
        return $this->is_essay;
    }

    /**
     * Check if this question type is true/false
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
}