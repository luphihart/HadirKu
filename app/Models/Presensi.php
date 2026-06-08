<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Presensi extends Model
{
    protected $table = 'presensi';

    protected $fillable = [
        'user_id', 'tanggal', 'jam_masuk', 'jam_pulang', 'status',
        'foto_masuk', 'foto_pulang',
        'latitude_masuk', 'longitude_masuk',
        'latitude_pulang', 'longitude_pulang',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'latitude_masuk' => 'decimal:7',
            'longitude_masuk' => 'decimal:7',
            'latitude_pulang' => 'decimal:7',
            'longitude_pulang' => 'decimal:7',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'hadir' => 'Hadir',
            'terlambat' => 'Terlambat',
            'sakit' => 'Sakit',
            'izin' => 'Izin',
            'tidak_presensi' => 'Tidak Presensi',
            default => '-',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'hadir' => 'emerald',
            'terlambat' => 'amber',
            'sakit' => 'blue',
            'izin' => 'violet',
            'tidak_presensi' => 'red',
            default => 'gray',
        };
    }
}
