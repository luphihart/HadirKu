<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\User;
use App\Models\Presensi;
use App\Models\SchoolSetting;
use App\Models\KalenderSekolah;
use App\Models\PengajuanIzin;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('presensi:auto-absent', function () {
    $today = today();
    $setting = SchoolSetting::instance();
    
    $dayName = $today->translatedFormat('l');
    $this->info("Menjalankan auto-absent untuk tanggal: " . $today->format('Y-m-d') . " ({$dayName})");
    
    if (!$setting->isHariAktif($dayName)) {
        $this->warn("Hari ini ({$dayName}) bukan hari aktif sekolah sesuai pengaturan.");
        return;
    }
    
    if (KalenderSekolah::isLibur($today)) {
        $this->warn("Hari ini adalah hari libur sekolah sesuai kalender sekolah.");
        return;
    }
    
    $murids = User::murid()->where('is_active', true)->get();
    $count = 0;
    
    foreach ($murids as $murid) {
        $presensi = Presensi::where('user_id', $murid->id)
            ->whereDate('tanggal', $today)
            ->first();
            
        if ($presensi) {
            continue;
        }
        
        $izin = PengajuanIzin::where('user_id', $murid->id)
            ->where('status_pengajuan', 'disetujui')
            ->where('tanggal_mulai', '<=', $today)
            ->where('tanggal_selesai', '>=', $today)
            ->first();
            
        $status = $izin ? $izin->jenis : 'tidak_presensi';
        
        Presensi::create([
            'user_id' => $murid->id,
            'tanggal' => $today,
            'status' => $status,
        ]);
        
        $count++;
    }
    
    $this->info("Selesai! Berhasil membuat {$count} record presensi otomatis.");
})->purpose('Mengisi presensi otomatis untuk murid yang tidak hadir hari ini');

// Schedule daily auto-absent at 23:59
Schedule::command('presensi:auto-absent')->dailyAt('23:59');

