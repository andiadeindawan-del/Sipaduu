<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'training_id', 'nomor_sertifikat', 'nama_sertifikat', 'deskripsi', 'tanggal_terbit', 'tanggal_berlaku_sampai', 'penerbit', 'file_path', 'tanda_tangan_digital', 'catatan', 'status'])]
class Sertifikat extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'tanggal_terbit' => 'date',
            'tanggal_berlaku_sampai' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function training(): BelongsTo
    {
        return $this->belongsTo(Training::class);
    }

    public function isExpired(): bool
    {
        return $this->tanggal_berlaku_sampai && now()->isAfter($this->tanggal_berlaku_sampai);
    }

    public function isActive(): bool
    {
        return $this->status === 'aktif' && !$this->isExpired();
    }
}
