<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['judul', 'deskripsi', 'tanggal', 'waktu_mulai', 'waktu_selesai', 'lokasi', 'status'])]
class Agenda extends Model
{
    protected $table = 'agenda';

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'waktu_mulai' => 'datetime',
            'waktu_selesai' => 'datetime',
        ];
    }
}
