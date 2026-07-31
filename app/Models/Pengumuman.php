<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    protected $table = 'pengumuman';  // ← Pastikan ini

    protected $fillable = [
        'training_id',
        'kategori_id',
        'created_by',
        'judul',
        'deskripsi',
        'konten',
        'tanggal',
        'tanggal_selesai',
        'status',
        'is_pinned',
        'target_audience',
        'gambar',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'tanggal_selesai' => 'date',
        'is_pinned' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relasi ke Training
    public function training()
    {
        return $this->belongsTo(Training::class);
    }

    // Relasi ke Kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    // Relasi ke User (pembuat)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Status accessors
    public function getStatusLabelAttribute()
    {
        $labels = [
            'draft' => '📝 Draft',
            'published' => '✅ Published',
            'archived' => '📦 Archived',
        ];
        return $labels[$this->status] ?? $this->status;
    }

    public function getStatusBadgeAttribute()
    {
        $classes = [
            'draft' => 'text-bg-secondary',
            'published' => 'text-bg-success',
            'archived' => 'text-bg-secondary',
        ];
        return $classes[$this->status] ?? 'text-bg-secondary';
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'published')
                     ->where(function($q) {
                         $q->whereNull('tanggal_selesai')
                           ->orWhere('tanggal_selesai', '>=', now());
                     });
    }
}