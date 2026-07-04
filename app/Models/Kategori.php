<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Kategori extends Model
{
    protected $table = 'kategoris';

    protected $fillable = [
        'nama',
        'slug',
        'deskripsi',
        'icon',
        'warna'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Boot method untuk auto-generate slug
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($kategori) {
            if (empty($kategori->slug)) {
                $kategori->slug = Str::slug($kategori->nama);
            }
        });

        static::updating(function ($kategori) {
            if ($kategori->isDirty('nama')) {
                $kategori->slug = Str::slug($kategori->nama);
            }
        });
    }

    /**
     * Relasi ke Materi
     */
    public function materis()
    {
        return $this->hasMany(Materi::class);
    }

    /**
     * Relasi ke Training
     */
    public function trainings()
    {
        return $this->hasMany(Training::class);
    }

    /**
     * Get jumlah materi dalam kategori
     */
    public function getMateriCountAttribute()
    {
        return $this->materis()->count();
    }

    /**
     * Get jumlah training dalam kategori
     */
    public function getTrainingCountAttribute()
    {
        return $this->trainings()->count();
    }

    /**
     * Scope untuk pencarian
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('nama', 'like', "%$search%")
                     ->orWhere('deskripsi', 'like', "%$search%");
    }
}