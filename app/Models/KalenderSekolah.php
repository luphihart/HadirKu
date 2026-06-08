<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KalenderSekolah extends Model
{
    protected $table = 'kalender_sekolah';

    protected $fillable = [
        'judul', 'tanggal_mulai', 'tanggal_selesai',
        'kategori', 'keterangan', 'is_libur',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
            'is_libur' => 'boolean',
        ];
    }

    public static function isLibur($tanggal): bool
    {
        return self::where('is_libur', true)
            ->where('tanggal_mulai', '<=', $tanggal)
            ->where('tanggal_selesai', '>=', $tanggal)
            ->exists();
    }

    public function getKategoriLabelAttribute(): string
    {
        return match($this->kategori) {
            'libur_nasional' => 'Libur Nasional',
            'kegiatan_sekolah' => 'Kegiatan Sekolah',
            default => '-',
        };
    }
}
