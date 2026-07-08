<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $table = 'absensis';

    protected $fillable = [
        'agenda_id',
        'user_id',
        'training_id',
        'tanggal',
        'jam_masuk',
        'jam_keluar',
        'waktu_checkin',
        'waktu_checkout',
        'status',
        'status_hadir',
        'keterangan',
        'lokasi',
        'ip_address',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jam_masuk' => 'datetime',
        'jam_keluar' => 'datetime',
        'waktu_checkin' => 'datetime',
        'waktu_checkout' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ============================================================
    // RELATIONSHIPS
    // ============================================================

    /**
     * Relasi ke Agenda
     */
    public function agenda()
    {
        return $this->belongsTo(Agenda::class);
    }

    /**
     * Relasi ke User (Peserta)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Training
     */
    public function training()
    {
        return $this->belongsTo(Training::class);
    }

    // ============================================================
    // ACCESSORS
    // ============================================================

    /**
     * Get status label with icon
     */
    public function getStatusLabelAttribute()
    {
        $labels = [
            'hadir' => '✅ Hadir',
            'sakit' => '🤒 Sakit',
            'izin' => '📝 Izin',
            'alpa' => '❌ Alpa',
        ];
        return $labels[$this->status] ?? $this->status;
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeAttribute()
    {
        $classes = [
            'hadir' => 'text-bg-success',
            'sakit' => 'text-bg-warning',
            'izin' => 'text-bg-info',
            'alpa' => 'text-bg-danger',
        ];
        return $classes[$this->status] ?? 'text-bg-secondary';
    }

    /**
     * Get formatted jam_masuk (H:i)
     */
    public function getJamMasukFormattedAttribute()
    {
        return $this->jam_masuk ? date('H:i', strtotime($this->jam_masuk)) : '-';
    }

    /**
     * Get formatted jam_keluar (H:i)
     */
    public function getJamKeluarFormattedAttribute()
    {
        return $this->jam_keluar ? date('H:i', strtotime($this->jam_keluar)) : '-';
    }

    /**
     * Get formatted tanggal (d/m/Y)
     */
    public function getTanggalFormattedAttribute()
    {
        return $this->tanggal ? $this->tanggal->format('d/m/Y') : '-';
    }

    /**
     * Get agenda title
     */
    public function getAgendaTitleAttribute()
    {
        return $this->agenda ? $this->agenda->judul : '-';
    }

    /**
     * Get training title
     */
    public function getTrainingTitleAttribute()
    {
        return $this->training ? $this->training->judul : '-';
    }

    /**
     * Get durasi kehadiran dalam menit
     */
    public function getDurasiAttribute()
    {
        if (!$this->jam_masuk || !$this->jam_keluar) {
            return null;
        }

        $masuk = strtotime($this->jam_masuk);
        $keluar = strtotime($this->jam_keluar);
        
        return round(($keluar - $masuk) / 60);
    }

    /**
     * Get durasi kehadiran dalam format jam:menit
     */
    public function getDurasiFormattedAttribute()
    {
        $durasi = $this->durasi;
        if ($durasi === null) {
            return '-';
        }

        $jam = floor($durasi / 60);
        $menit = $durasi % 60;
        
        if ($jam > 0) {
            return $jam . ' jam ' . $menit . ' menit';
        }
        return $menit . ' menit';
    }

    /**
     * Check if status is hadir
     */
    public function getIsHadirAttribute()
    {
        return $this->status === 'hadir';
    }

    /**
     * Check if status is sakit
     */
    public function getIsSakitAttribute()
    {
        return $this->status === 'sakit';
    }

    /**
     * Check if status is izin
     */
    public function getIsIzinAttribute()
    {
        return $this->status === 'izin';
    }

    /**
     * Check if status is alpa
     */
    public function getIsAlpaAttribute()
    {
        return $this->status === 'alpa';
    }

    /**
     * Check if user has checked out
     */
    public function getIsCheckedOutAttribute()
    {
        return $this->jam_keluar !== null;
    }

    // ============================================================
    // SCOPES
    // ============================================================

    /**
     * Scope untuk absensi hari ini
     */
    public function scopeToday($query)
    {
        return $query->whereDate('tanggal', now()->format('Y-m-d'));
    }

    /**
     * Scope untuk absensi bulan ini
     */
    public function scopeThisMonth($query)
    {
        return $query->whereYear('tanggal', now()->year)
                     ->whereMonth('tanggal', now()->month);
    }

    /**
     * Scope untuk absensi berdasarkan status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk absensi berdasarkan user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope untuk absensi berdasarkan agenda
     */
    public function scopeByAgenda($query, $agendaId)
    {
        return $query->where('agenda_id', $agendaId);
    }

    /**
     * Scope untuk absensi berdasarkan training
     */
    public function scopeByTraining($query, $trainingId)
    {
        return $query->where('training_id', $trainingId);
    }

    /**
     * Scope untuk absensi berdasarkan rentang tanggal
     */
    public function scopeDateRange($query, $start, $end)
    {
        return $query->whereBetween('tanggal', [$start, $end]);
    }

    /**
     * Scope untuk absensi yang sudah check out
     */
    public function scopeCheckedOut($query)
    {
        return $query->whereNotNull('jam_keluar');
    }

    /**
     * Scope untuk absensi yang belum check out
     */
    public function scopeNotCheckedOut($query)
    {
        return $query->whereNull('jam_keluar');
    }

    /**
     * Scope untuk absensi berdasarkan tanggal
     */
    public function scopeByDate($query, $date)
    {
        return $query->whereDate('tanggal', $date);
    }

    /**
     * Scope untuk absensi yang hadir
     */
    public function scopeHadir($query)
    {
        return $query->where('status', 'hadir');
    }

    /**
     * Scope untuk absensi yang sakit
     */
    public function scopeSakit($query)
    {
        return $query->where('status', 'sakit');
    }

    /**
     * Scope untuk absensi yang izin
     */
    public function scopeIzin($query)
    {
        return $query->where('status', 'izin');
    }

    /**
     * Scope untuk absensi yang alpa
     */
    public function scopeAlpa($query)
    {
        return $query->where('status', 'alpa');
    }

    // ============================================================
    // HELPER METHODS
    // ============================================================

    /**
     * Check if user has checked in today
     */
    public static function hasCheckedInToday($userId)
    {
        return self::where('user_id', $userId)
            ->whereDate('tanggal', now()->format('Y-m-d'))
            ->exists();
    }

    /**
     * Get today's attendance for user
     */
    public static function getTodayAttendance($userId)
    {
        return self::where('user_id', $userId)
            ->whereDate('tanggal', now()->format('Y-m-d'))
            ->first();
    }

    /**
     * Get attendance summary for user
     */
    public static function getUserSummary($userId)
    {
        $total = self::where('user_id', $userId)->count();
        $hadir = self::where('user_id', $userId)->where('status', 'hadir')->count();
        $sakit = self::where('user_id', $userId)->where('status', 'sakit')->count();
        $izin = self::where('user_id', $userId)->where('status', 'izin')->count();
        $alpa = self::where('user_id', $userId)->where('status', 'alpa')->count();

        return [
            'total' => $total,
            'hadir' => $hadir,
            'sakit' => $sakit,
            'izin' => $izin,
            'alpa' => $alpa,
            'persentase_kehadiran' => $total > 0 ? round(($hadir / $total) * 100, 2) : 0,
        ];
    }

    /**
     * Get monthly attendance for user
     */
    public static function getUserMonthlySummary($userId, $month = null, $year = null)
    {
        $month = $month ?? now()->month;
        $year = $year ?? now()->year;

        $total = self::where('user_id', $userId)
            ->whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->count();

        $hadir = self::where('user_id', $userId)
            ->whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->where('status', 'hadir')
            ->count();

        return [
            'total' => $total,
            'hadir' => $hadir,
            'bulan' => $month,
            'tahun' => $year,
            'persentase' => $total > 0 ? round(($hadir / $total) * 100, 2) : 0,
        ];
    }

    /**
     * Get attendance statistics by date
     */
    public static function getStatistics($date = null)
    {
        $date = $date ?? now()->format('Y-m-d');

        $total = self::whereDate('tanggal', $date)->count();
        $hadir = self::whereDate('tanggal', $date)->where('status', 'hadir')->count();
        $sakit = self::whereDate('tanggal', $date)->where('status', 'sakit')->count();
        $izin = self::whereDate('tanggal', $date)->where('status', 'izin')->count();
        $alpa = self::whereDate('tanggal', $date)->where('status', 'alpa')->count();

        return [
            'total' => $total,
            'hadir' => $hadir,
            'sakit' => $sakit,
            'izin' => $izin,
            'alpa' => $alpa,
            'persentase_kehadiran' => $total > 0 ? round(($hadir / $total) * 100, 2) : 0,
        ];
    }

    /**
     * Get attendance statistics by training
     */
    public static function getStatisticsByTraining($trainingId)
    {
        $total = self::where('training_id', $trainingId)->count();
        $hadir = self::where('training_id', $trainingId)->where('status', 'hadir')->count();
        $sakit = self::where('training_id', $trainingId)->where('status', 'sakit')->count();
        $izin = self::where('training_id', $trainingId)->where('status', 'izin')->count();
        $alpa = self::where('training_id', $trainingId)->where('status', 'alpa')->count();

        return [
            'total' => $total,
            'hadir' => $hadir,
            'sakit' => $sakit,
            'izin' => $izin,
            'alpa' => $alpa,
            'persentase_kehadiran' => $total > 0 ? round(($hadir / $total) * 100, 2) : 0,
        ];
    }

    /**
     * Check if attendance is complete (has check out)
     */
    public function isComplete()
    {
        return $this->jam_keluar !== null;
    }

    /**
     * Get status in Indonesian
     */
    public function getStatusIndonesia()
    {
        $labels = [
            'hadir' => 'Hadir',
            'sakit' => 'Sakit',
            'izin' => 'Izin',
            'alpa' => 'Alpa',
        ];
        return $labels[$this->status] ?? $this->status;
    }

    /**
     * Get status color
     */
    public function getStatusColor()
    {
        $colors = [
            'hadir' => 'success',
            'sakit' => 'warning',
            'izin' => 'info',
            'alpa' => 'danger',
        ];
        return $colors[$this->status] ?? 'secondary';
    }

    /**
     * Mark attendance as complete (check out)
     */
    public function markComplete($jamKeluar = null)
    {
        $this->jam_keluar = $jamKeluar ?? now()->format('H:i:s');
        $this->save();
        
        return $this;
    }

    /**
     * Check if user has attendance on specific date
     */
    public static function hasAttendance($userId, $date)
    {
        return self::where('user_id', $userId)
            ->whereDate('tanggal', $date)
            ->exists();
    }
}