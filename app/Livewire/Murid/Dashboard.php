<?php

namespace App\Livewire\Murid;

use Livewire\Component;
use App\Models\Presensi;
use App\Models\Pengumuman;
use App\Models\SchoolSetting;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Carbon\Carbon;

#[Layout('components.layouts.app')]
#[Title('Dashboard - Hadirku')]
class Dashboard extends Component
{
    public function render()
    {
        $user = auth()->user();
        $today = today();
        $settings = SchoolSetting::instance();
        
        // Today's attendance
        $presensiHariIni = Presensi::where('user_id', $user->id)
            ->whereDate('tanggal', $today)
            ->first();
        
        // Attendance history (last 30 days)
        $riwayat = Presensi::where('user_id', $user->id)
            ->orderBy('tanggal', 'desc')
            ->limit(30)
            ->get();
        
        // Announcements for this student
        $pengumuman = Pengumuman::forMurid($user)
            ->where('is_active', true)
            ->latest()
            ->limit(5)
            ->get();
            
        // Today's state checks
        $isHoliday = \App\Models\KalenderSekolah::isLibur($today);
        $dayName = now()->translatedFormat('l'); // e.g. 'Senin'
        $isInactiveDay = !$settings->isHariAktif($dayName);
        
        // Leave permits needing revision
        $revisiIzin = \App\Models\PengajuanIzin::where('user_id', $user->id)
            ->where('status_pengajuan', 'revisi')
            ->first();
        
        return view('livewire.murid.dashboard', [
            'user' => $user,
            'presensiHariIni' => $presensiHariIni,
            'riwayat' => $riwayat,
            'pengumuman' => $pengumuman,
            'isBirthday' => $user->isBirthday(),
            'settings' => $settings,
            'isHoliday' => $isHoliday,
            'isInactiveDay' => $isInactiveDay,
            'revisiIzin' => $revisiIzin,
        ]);
    }
}
