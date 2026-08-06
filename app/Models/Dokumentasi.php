<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dokumentasi extends Model
{
    protected $fillable = [
        'training_id',
        'judul',
        'link',
    ];

    public function training()
    {
        return $this->belongsTo(Training::class);
    }
}
