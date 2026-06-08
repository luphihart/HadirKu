<?php

namespace App\Livewire\Murid;

use Livewire\Component;
use App\Models\Presensi as PresensiModel;
use App\Models\SchoolSetting;
use App\Models\KalenderSekolah;
use App\Services\GeolocationService;
use App\Services\SelfieService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Carbon\Carbon;

#[Layout('components.layouts.app')]
#[Title('Presensi - Hadirku')]
class Presensi extends Component
{
    public bool $isWithinRange = false;
    public float $userLat = 0;
    public float $userLng = 0;
    public float $distance = 9999;
    
    public bool $alreadyCheckedIn = false;
    public bool $alreadyCheckedOut = false;
    public bool $isHoliday = false;
    public bool $isInactiveDay = false;
    
    public ?PresensiModel $todayRecord = null;
    public ?string $errorMessage = null;
    public ?string $successMessage = null;

    public int $radius_meter = 100;

    public function mount()
    {
        $this->checkTodayState();
        $this->radius_meter = (int) SchoolSetting::instance()->radius_meter;
    }

    public function checkTodayState()
    {
        $user = auth()->user();
        $today = today();
        $settings = SchoolSetting::instance();

        // 1. Check if holiday
        $this->isHoliday = KalenderSekolah::isLibur($today);

        // 2. Check if active day
        $dayName = now()->translatedFormat('l'); // e.g. 'Senin'
        $this->isInactiveDay = !$settings->isHariAktif($dayName);

        // 3. Load today's record
        $this->todayRecord = PresensiModel::where('user_id', $user->id)
            ->whereDate('tanggal', $today)
            ->first();

        if ($this->todayRecord) {
            $this->alreadyCheckedIn = $this->todayRecord->jam_masuk !== null;
            $this->alreadyCheckedOut = $this->todayRecord->jam_pulang !== null;
        }
    }

    public function verifyLocation($lat, $lng)
    {
        $this->userLat = (float) $lat;
        $this->userLng = (float) $lng;

        $settings = SchoolSetting::instance();
        $geoService = new GeolocationService();

        $this->distance = $geoService->calculateDistance(
            $this->userLat,
            $this->userLng,
            (float) $settings->latitude,
            (float) $settings->longitude
        );

        $this->isWithinRange = $this->distance <= $settings->radius_meter;
    }

    public function submitPresensi(string $base64Image, $lat, $lng)
    {
        $user = auth()->user();
        $settings = SchoolSetting::instance();
        $today = today();

        // Verify location again before saving
        $this->verifyLocation($lat, $lng);

        if (!$this->isWithinRange) {
            $this->addError('location', "Presensi gagal! Anda berada di luar radius sekolah ({$settings->radius_meter} meter). Jarak Anda saat ini: " . round($this->distance) . " meter.");
            return;
        }

        $this->checkTodayState();

        // Determine if check-in or check-out
        $type = 'masuk';
        if ($this->alreadyCheckedIn && !$this->alreadyCheckedOut) {
            $type = 'pulang';
        } elseif ($this->alreadyCheckedIn && $this->alreadyCheckedOut) {
            session()->flash('error', 'Anda sudah melakukan presensi masuk dan pulang hari ini.');
            return;
        }

        // Process selfie
        try {
            $selfieService = new SelfieService();
            $photoPath = $selfieService->processAndStore($base64Image, $user, $type, $this->userLat, $this->userLng);
        } catch (\Exception $e) {
            $this->addError('selfie', 'Gagal memproses foto selfie. Pastikan kamera diizinkan.');
            return;
        }

        $currentTime = now()->format('H:i:s');

        if ($type === 'masuk') {
            // Determine status (hadir vs terlambat)
            $status = 'hadir';
            if (now()->format('H:i:s') > $settings->jam_terlambat) {
                $status = 'terlambat';
            }

            PresensiModel::create([
                'user_id' => $user->id,
                'tanggal' => $today->format('Y-m-d'),
                'jam_masuk' => $currentTime,
                'status' => $status,
                'foto_masuk' => $photoPath,
                'latitude_masuk' => $this->userLat,
                'longitude_masuk' => $this->userLng,
            ]);

            session()->flash('success', 'Presensi masuk berhasil dilakukan. Selamat belajar!');
        } else {
            // Check out
            if (now()->format('H:i:s') < $settings->jam_pulang) {
                $this->addError('time', "Belum waktunya pulang! Presensi pulang baru dibuka jam " . substr($settings->jam_pulang, 0, 5) . ".");
                return;
            }

            if ($this->todayRecord) {
                $this->todayRecord->update([
                    'jam_pulang' => $currentTime,
                    'foto_pulang' => $photoPath,
                    'latitude_pulang' => $this->userLat,
                    'longitude_pulang' => $this->userLng,
                ]);
            }

            session()->flash('success', 'Presensi pulang berhasil dilakukan. Hati-hati di jalan!');
        }

        $this->checkTodayState();
        return redirect()->route('murid.dashboard');
    }

    public function render()
    {
        $settings = SchoolSetting::instance();
        return view('livewire.murid.presensi', [
            'settings' => $settings
        ]);
    }
}
