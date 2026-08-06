<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyQuestion extends Model
{
    protected $fillable = [
        'survey_id',
        'pertanyaan',
        'tipe',
        'order',
    ];

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }
}
