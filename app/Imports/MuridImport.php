<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Kelas;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class MuridImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Skip empty rows
        if (empty($row['nis']) || empty($row['nama']) || empty($row['email'])) {
            return null;
        }

        // Find or create class
        $kelasId = null;
        if (!empty($row['kelas'])) {
            $kelas = Kelas::firstOrCreate(['nama_kelas' => trim($row['kelas'])]);
            $kelasId = $kelas->id;
        }

        // Parse birth date
        $birthDate = null;
        if (!empty($row['tanggal_lahir'])) {
            try {
                if (is_numeric($row['tanggal_lahir'])) {
                    $birthDate = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tanggal_lahir']));
                } else {
                    $birthDate = Carbon::parse($row['tanggal_lahir']);
                }
            } catch (\Exception $e) {
                $birthDate = null;
            }
        }

        return new User([
            'nis' => trim($row['nis']),
            'name' => trim($row['nama']),
            'email' => trim($row['email']),
            'phone' => trim($row['no_hp'] ?? $row['telepon'] ?? null),
            'birth_date' => $birthDate,
            'password' => Hash::make($row['password'] ?? 'password123'),
            'kelas_id' => $kelasId,
            'role' => 'murid',
            'is_active' => true,
        ]);
    }
}
