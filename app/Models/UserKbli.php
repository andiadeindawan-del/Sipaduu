<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserKbli extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'kbli_id',
        'is_utama'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kbli()
    {
        return $this->belongsTo(Kbli::class, 'kbli_id');
    }
}
