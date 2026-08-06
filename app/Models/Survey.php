<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    protected $fillable = [
        'training_id',
        'judul',
        'deskripsi',
        'status',
    ];

    public function training()
    {
        return $this->belongsTo(Training::class);
    }

    public function questions()
    {
        return $this->hasMany(SurveyQuestion::class)->orderBy('order');
    }

    public function responses()
    {
        return $this->hasMany(SurveyResponse::class);
    }
}
