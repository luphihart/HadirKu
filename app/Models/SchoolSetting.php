<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolSetting extends Model
{
    protected $fillable = [
        'nama_sekolah', 'alamat_sekolah', 'npsn', 'telepon', 'email', 'logo',
        'latitude', 'longitude', 'radius_meter',
        'jam_masuk', 'jam_terlambat', 'jam_pulang', 'jam_pulang_akhir',
        'hari_aktif',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'hari_aktif' => 'array',
        ];
    }

    public static function instance(): self
    {
        return self::firstOrCreate([], [
            'nama_sekolah' => 'Nama Sekolah',
            'alamat_sekolah' => 'Alamat Sekolah',
            'latitude' => -6.2088,
            'longitude' => 106.8456,
            'radius_meter' => 100,
            'jam_masuk' => '07:00',
            'jam_terlambat' => '07:15',
            'jam_pulang' => '14:00',
            'jam_pulang_akhir' => '17:00',
            'hari_aktif' => ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'],
        ]);
    }

    public function isHariAktif(string $hari): bool
    {
        return in_array($hari, $this->hari_aktif ?? []);
    }
}
