<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kbli extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'judul',
        'uraian',
        'versi',
        'kategori',
        'kategori_kode',
        'kategori_nama',
        'golongan_pokok_kode',
        'golongan_pokok_nama',
        'golongan_kode',
        'golongan_nama',
        'subgolongan_kode',
        'subgolongan_nama',
        'kelompok_kode',
        'kelompok_nama',
        'aktif'
    ];

    public function userKblis()
    {
        return $this->hasMany(UserKbli::class, 'kbli_id');
    }
}
