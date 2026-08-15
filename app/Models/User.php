<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        // Basic Auth
        'name',
        'email',
        'password',
        
        // Identity
        'nik',
        'nama',
        'role',
        
        // Contact
        'no_telepon',
        
        // Business (untuk peserta)
        'nama_usaha',
        'nib',
        'jenis_usaha',
        'provinsi',
        'kabupaten',
        'kecamatan',
        'desa',
        'alamat_lengkap',
        
        // Employee (untuk admin/trainer)
        'departemen',
        'jabatan',
        
        // Profile
        'foto',
        'status',

        // Data Pribadi UMK
        'status_pernikahan', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'agama',
        'pendidikan_terakhir', 'kode_pos_domisili', 'disabilitas', 'ktp_file',

        // Data Usaha UMK
        'jabatan_usaha', 'merek_produk', 'kode_pos_usaha', 'sektor_usaha', 'no_telepon_usaha',
        'bidang_usaha', 'tanggal_berdiri', 'npwp_usaha', 'status_nib', 'lama_nib', 'modal_usaha',
        'nilai_modal', 'omzet_usaha', 'nilai_omzet', 'jumlah_karyawan', 'kapasitas_produksi', 'anggota_koperasi',

        // Digitalisasi & Transformasi
        'email_usaha', 'website_usaha', 'medsos_usaha', 'marketplace', 'pengadaan_barang', 'akses_kredit',
        'tabungan', 'perizinan_usaha', 'sertifikasi_produk', 'jangkauan_pemasaran', 'lokasi_pemasaran',
        'status_ekspor', 'negara_ekspor', 'metode_ekspor', 'volume_ekspor', 'nilai_ekspor', 'pasok_bahan_baku', 'kemitraan',

        // Informasi Tambahan
        'permasalahan', 'kebutuhan_diklat', 'riwayat_pelatihan', 'jenis_pelatihan_diikuti', 'file_produk', 'masukan_saran',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    // ============================================================
    // RELATIONSHIPS
    // ============================================================

    public function sertifikats()
    {
        return $this->hasMany(Sertifikat::class);
    }

    public function surveyResponses()
    {
        return $this->hasMany(SurveyResponse::class);
    }

    public function trainingDiajar()
    {
        return $this->hasMany(Training::class, 'trainer_id');
    }

    public function trainingDiikuti()
    {
        return $this->belongsToMany(Training::class, 'training_registrations', 'user_id', 'training_id')
                    ->withPivot('status', 'tanggal_daftar', 'nilai_akhir', 'status_kelulusan')
                    ->withTimestamps();
    }

    public function materis()
    {
        return $this->hasMany(Materi::class, 'created_by');
    }

    /**
     * Relasi ke Quiz Attempts
     */
    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    public function surveys()
    {
        return $this->hasMany(Survey::class);
    }

    /**
     * Relasi ke Quiz yang sudah dikerjakan (Many to Many melalui attempts)
     */
    public function quizAttempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    /**
     * Relasi ke Quiz yang sudah dikerjakan (Many to Many melalui attempts)
     */
    public function quizzesTaken()
    {
        return $this->belongsToMany(Quiz::class, 'quiz_attempts', 'user_id', 'quiz_id')
                    ->withPivot('score', 'status', 'completed_at')
                    ->withTimestamps();
    }

    /**
     * Relasi ke Sertifikat (alias untuk sertifikats)
     */
    public function certificates()
    {
        return $this->hasMany(Sertifikat::class);
    }

    /**
     * Relasi ke Training (alias untuk trainingDiikuti)
     */
    public function trainings()
    {
        return $this->belongsToMany(Training::class, 'training_participants', 'user_id', 'training_id')
                    ->withPivot('status', 'registered_at', 'completed_at', 'certificate_id')
                    ->withTimestamps();
    }

    /**
     * Relasi ke Quiz yang dibuat
     */
    public function createdQuizzes()
    {
        return $this->hasMany(Quiz::class, 'created_by');
    }

    /**
     * Relasi ke Training yang dibuat (sebagai creator)
     */
    public function createdTrainings()
    {
        return $this->hasMany(Training::class, 'created_by');
    }

    // ============================================================
    // ACCESSORS
    // ============================================================

    public function getDisplayNameAttribute(): string
    {
        return $this->nama ?? $this->name ?? 'User';
    }

    public function getInitialsAttribute(): string
    {
        $name = $this->display_name;
        $words = explode(' ', $name);
        $initials = '';

        foreach ($words as $word) {
            if (!empty($word)) {
                $initials .= strtoupper($word[0]);
            }
        }

        return substr($initials, 0, 2);
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->foto) {
            return asset('storage/' . $this->foto);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->display_name) . '&background=4e9af1&color=fff&size=100';
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->status === 'aktif' ? 'Aktif' : 'Nonaktif';
    }

    public function getStatusBadgeAttribute(): string
    {
        return $this->status === 'aktif' ? 'success' : 'secondary';
    }

    public function getRoleLabelAttribute(): string
    {
        $labels = [
            'admin' => 'Admin',
            'trainer' => 'Trainer',
            'peserta' => 'Peserta',
        ];
        return $labels[$this->role] ?? $this->role;
    }

    public function getRoleBadgeAttribute(): string
    {
        $classes = [
            'admin' => 'danger',
            'trainer' => 'info',
            'peserta' => 'secondary',
        ];
        return $classes[$this->role] ?? 'secondary';
    }

    /**
     * Get total quiz points
     */
    public function getTotalQuizPointsAttribute()
    {
        return $this->quizAttempts()
                    ->where('status', 'completed')
                    ->sum('score');
    }

    /**
     * Get average quiz score
     */
    public function getAverageQuizScoreAttribute()
    {
        return $this->quizAttempts()
                    ->where('status', 'completed')
                    ->avg('score') ?? 0;
    }

    /**
     * Get total certificates count
     */
    public function getTotalCertificatesAttribute()
    {
        return $this->certificates()->count();
    }

    /**
     * Get total trainings completed
     */
    public function getTotalTrainingsCompletedAttribute()
    {
        return $this->trainings()
                    ->wherePivot('status', 'completed')
                    ->count();
    }

    public function getIsProfilLengkapAttribute(): bool
    {
        return empty($this->profil_incomplete_fields);
    }

    // Define required fields in one place
    public function getRequiredProfilFields()
    {
        return [
            'Pribadi' => [
                'nik' => 'NIK',
                'nama' => 'Nama Lengkap',
                'tempat_lahir' => 'Tempat Lahir',
                'tanggal_lahir' => 'Tanggal Lahir',
                'jenis_kelamin' => 'Jenis Kelamin',
                'agama' => 'Agama',
                'status_pernikahan' => 'Status Pernikahan',
                'pendidikan_terakhir' => 'Pendidikan Terakhir',
                'provinsi' => 'Provinsi',
                'kabupaten' => 'Kabupaten/Kota',
                'kecamatan' => 'Kecamatan',
                'desa' => 'Desa/Kelurahan',
                'alamat_lengkap' => 'Alamat Detail',
                'kode_pos_domisili' => 'Kode Pos Domisili',
                'no_telepon' => 'Nomor HP/Telepon',
                'email' => 'Email',
                'ktp_file' => 'Upload KTP',
            ],
            'Usaha' => [
                'nama_usaha' => 'Nama Usaha',
                'sektor_usaha' => 'Sektor Usaha',
                'bidang_usaha' => 'Bidang Usaha',
                'nib' => 'Nomor NIB',
            ]
        ];
    }

    public function getProfilIncompleteFieldsAttribute(): array
    {
        $incomplete = [];
        $required_fields = $this->getRequiredProfilFields();

        foreach ($required_fields as $category => $fields) {
            foreach ($fields as $field => $label) {
                if (empty($this->$field)) {
                    $incomplete[] = $category . ': ' . $label;
                }
            }
        }

        return $incomplete;
    }

    public function getProfilCompletedFieldsAttribute(): array
    {
        $completed = [];
        $required_fields = $this->getRequiredProfilFields();

        foreach ($required_fields as $category => $fields) {
            foreach ($fields as $field => $label) {
                if (!empty($this->$field)) {
                    $completed[] = $category . ': ' . $label;
                }
            }
        }

        return $completed;
    }

    public function getProfilCompletionPercentageAttribute(): int
    {
        $required_fields = $this->getRequiredProfilFields();
        $totalFields = 0;
        $completedFields = 0;

        foreach ($required_fields as $category => $fields) {
            foreach ($fields as $field => $label) {
                $totalFields++;
                if (!empty($this->$field)) {
                    $completedFields++;
                }
            }
        }

        if ($totalFields === 0) return 100;
        return (int) round(($completedFields / $totalFields) * 100);
    }

    // ============================================================
    // SCOPES
    // ============================================================

    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'nonaktif');
    }

    public function scopeAdmin($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeTrainer($query)
    {
        return $query->where('role', 'trainer');
    }

    public function scopePeserta($query)
    {
        return $query->where('role', 'peserta');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('nama', 'like', "%$search%")
              ->orWhere('name', 'like', "%$search%")
              ->orWhere('email', 'like', "%$search%")
              ->orWhere('nik', 'like', "%$search%")
              ->orWhere('no_telepon', 'like', "%$search%");
        });
    }

    // ============================================================
    // HELPER METHODS
    // ============================================================

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isTrainer(): bool
    {
        return $this->role === 'trainer';
    }

    public function isPeserta(): bool
    {
        return $this->role === 'peserta';
    }

    public function isActive(): bool
    {
        return $this->status === 'aktif';
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles);
    }

    /**
     * Check if user has completed a training
     */
    public function hasCompletedTraining($trainingId)
    {
        return $this->trainings()
                    ->where('training_id', $trainingId)
                    ->wherePivot('status', 'completed')
                    ->exists();
    }

    /**
     * Check if user has certificate for training
     */
    public function hasCertificateForTraining($trainingId)
    {
        return $this->trainings()
                    ->where('training_id', $trainingId)
                    ->whereNotNull('pivot.certificate_id')
                    ->exists();
    }

    /**
     * Get user's score for a quiz
     */
    public function getQuizScore($quizId)
    {
        $attempt = $this->quizAttempts()
                        ->where('quiz_id', $quizId)
                        ->where('status', 'completed')
                        ->orderBy('score', 'desc')
                        ->first();
        return $attempt ? $attempt->score : null;
    }

    /**
     * Check if user has passed a quiz
     */
    public function hasPassedQuiz($quizId)
    {
        $attempt = $this->quizAttempts()
                        ->where('quiz_id', $quizId)
                        ->where('status', 'completed')
                        ->orderBy('score', 'desc')
                        ->first();
        
        if (!$attempt) {
            return false;
        }
        
        $quiz = Quiz::find($quizId);
        return $attempt->score >= ($quiz->passing_score ?? 70);
    }
}