<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Presensi;
use App\Models\PengajuanIzin;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Classes
        $kelasNames = ['XII RPL 1', 'XII IPA 2', 'XI IPS 1'];
        $kelasIds = [];
        foreach ($kelasNames as $name) {
            $k = Kelas::firstOrCreate(['nama_kelas' => $name]);
            $kelasIds[] = $k->id;
        }

        // 2. Create Students
        $students = [
            ['nis' => '10203001', 'name' => 'Aditya Wijaya', 'email' => 'aditya@student.sch.id'],
            ['nis' => '10203002', 'name' => 'Budi Santoso', 'email' => 'budi@student.sch.id'],
            ['nis' => '10203003', 'name' => 'Citra Lestari', 'email' => 'citra@student.sch.id'],
            ['nis' => '10203004', 'name' => 'Dian Pratama', 'email' => 'dian@student.sch.id'],
            ['nis' => '10203005', 'name' => 'Eka Saputra', 'email' => 'eka@student.sch.id'],
            ['nis' => '10203006', 'name' => 'Fitriani Indah', 'email' => 'fitri@student.sch.id'],
            ['nis' => '10203007', 'name' => 'Gilang Ramadhan', 'email' => 'gilang@student.sch.id'],
            ['nis' => '10203008', 'name' => 'Hendra Wijaya', 'email' => 'hendra@student.sch.id'],
            ['nis' => '10203009', 'name' => 'Indah Permata', 'email' => 'indah@student.sch.id'],
            ['nis' => '10203010', 'name' => 'Joko Susilo', 'email' => 'joko@student.sch.id'],
        ];

        $studentModels = [];
        foreach ($students as $index => $data) {
            $studentModels[] = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'nis' => $data['nis'],
                    'name' => $data['name'],
                    'password' => Hash::make('password123'),
                    'kelas_id' => $kelasIds[$index % count($kelasIds)],
                    'role' => 'murid',
                    'phone' => '08123456789' . $index,
                    'birth_date' => Carbon::now()->subYears(17)->subDays($index * 15),
                    'is_active' => true,
                ]
            );
        }

        // 3. Create Attendance History (Last 30 Days)
        $startDate = Carbon::now()->subDays(30);
        $endDate = Carbon::now();

        for ($date = clone $startDate; $date->lte($endDate); $date->addDay()) {
            // Skip weekends
            if ($date->isWeekend()) {
                continue;
            }

            foreach ($studentModels as $student) {
                // Determine random status
                $rand = rand(1, 100);

                if ($rand <= 75) {
                    // Present (75% chance)
                    $status = 'hadir';
                    $jamMasuk = sprintf('06:%02d:%02d', rand(30, 59), rand(0, 59));
                    $jamPulang = sprintf('14:%02d:%02d', rand(0, 30), rand(0, 59));
                } elseif ($rand <= 88) {
                    // Late (13% chance)
                    $status = 'terlambat';
                    $jamMasuk = sprintf('07:%02d:%02d', rand(16, 45), rand(0, 59));
                    $jamPulang = sprintf('14:%02d:%02d', rand(0, 30), rand(0, 59));
                } elseif ($rand <= 92) {
                    // Sick (4% chance)
                    $status = 'sakit';
                    $jamMasuk = null;
                    $jamPulang = null;
                } elseif ($rand <= 96) {
                    // Leave permit (4% chance)
                    $status = 'izin';
                    $jamMasuk = null;
                    $jamPulang = null;
                } else {
                    // Absent / Alpa (4% chance)
                    $status = 'tidak_presensi';
                    $jamMasuk = null;
                    $jamPulang = null;
                }

                // If sick or leave, create an approved request in database
                if ($status === 'sakit' || $status === 'izin') {
                    PengajuanIzin::firstOrCreate(
                        [
                            'user_id' => $student->id,
                            'tanggal_mulai' => $date->format('Y-m-d'),
                            'tanggal_selesai' => $date->format('Y-m-d'),
                        ],
                        [
                            'jenis' => $status,
                            'keterangan' => 'Mengajukan surat ' . $status . ' untuk alasan kesehatan/keluarga.',
                            'status_pengajuan' => 'disetujui',
                            'catatan_admin' => 'Disetujui otomatis oleh sistem seeder.',
                        ]
                    );
                }

                // Create attendance record
                Presensi::firstOrCreate(
                    [
                        'user_id' => $student->id,
                        'tanggal' => $date->format('Y-m-d'),
                    ],
                    [
                        'jam_masuk' => $jamMasuk,
                        'jam_pulang' => $jamPulang,
                        'status' => $status,
                        'latitude_masuk' => -6.2088 + (rand(-100, 100) / 1000000),
                        'longitude_masuk' => 106.8456 + (rand(-100, 100) / 1000000),
                        'latitude_pulang' => $status === 'hadir' || $status === 'terlambat' ? -6.2088 + (rand(-100, 100) / 1000000) : null,
                        'longitude_pulang' => $status === 'hadir' || $status === 'terlambat' ? 106.8456 + (rand(-100, 100) / 1000000) : null,
                    ]
                );
            }
        }
    }
}
