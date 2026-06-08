<?php

namespace Database\Seeders;

use App\Models\SchoolSetting;
use Illuminate\Database\Seeder;

class SchoolSettingSeeder extends Seeder
{
    public function run(): void
    {
        SchoolSetting::create([
            'nama_sekolah' => 'SMA Negeri 1 Contoh',
            'alamat_sekolah' => 'Jl. Pendidikan No. 1, Kota Contoh',
            'npsn' => '12345678',
            'telepon' => '021-1234567',
            'email' => 'info@sekolah.sch.id',
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
}
