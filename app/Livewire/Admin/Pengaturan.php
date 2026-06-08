<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\SchoolSetting;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.app')]
#[Title('Pengaturan Sekolah - Hadirku')]
class Pengaturan extends Component
{
    use WithFileUploads;

    public string $nama_sekolah = '';
    public string $alamat_sekolah = '';
    public string $npsn = '';
    public string $telepon = '';
    public string $email = '';
    public $logo;
    public ?string $existingLogo = null;

    public float $latitude = -6.2088;
    public float $longitude = 106.8456;
    public int $radius_meter = 100;

    public string $jam_masuk = '07:00';
    public string $jam_terlambat = '07:15';
    public string $jam_pulang = '14:00';
    public string $jam_pulang_akhir = '17:00';

    public array $hari_aktif = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
    
    public string $activeTab = 'identitas';

    protected $rules = [
        'nama_sekolah' => 'required|min:3',
        'alamat_sekolah' => 'required|min:5',
        'npsn' => 'nullable|numeric|digits:8',
        'telepon' => 'nullable|string',
        'email' => 'nullable|email',
        'logo' => 'nullable|image|max:1024', // Max 1MB
        'latitude' => 'required|numeric|between:-90,90',
        'longitude' => 'required|numeric|between:-180,180',
        'radius_meter' => 'required|integer|min:10|max:5000',
        'jam_masuk' => 'required|date_format:H:i',
        'jam_terlambat' => 'required|date_format:H:i|after:jam_masuk',
        'jam_pulang' => 'required|date_format:H:i|after:jam_terlambat',
        'jam_pulang_akhir' => 'required|date_format:H:i|after:jam_pulang',
        'hari_aktif' => 'required|array|min:1',
    ];

    public function mount()
    {
        $settings = SchoolSetting::instance();

        $this->nama_sekolah = $settings->nama_sekolah;
        $this->alamat_sekolah = $settings->alamat_sekolah;
        $this->npsn = $settings->npsn ?? '';
        $this->telepon = $settings->telepon ?? '';
        $this->email = $settings->email ?? '';
        $this->existingLogo = $settings->logo;

        // Ensure decimal values are float
        $this->latitude = (float) $settings->latitude;
        $this->longitude = (float) $settings->longitude;
        $this->radius_meter = (int) $settings->radius_meter;

        // Strip seconds if present in database times
        $this->jam_masuk = substr($settings->jam_masuk, 0, 5);
        $this->jam_terlambat = substr($settings->jam_terlambat, 0, 5);
        $this->jam_pulang = substr($settings->jam_pulang, 0, 5);
        $this->jam_pulang_akhir = substr($settings->jam_pulang_akhir, 0, 5);

        $this->hari_aktif = $settings->hari_aktif ?? ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
    }

    public function changeTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function setCoordinates($lat, $lng)
    {
        $this->latitude = (float) $lat;
        $this->longitude = (float) $lng;
    }

    public function saveSettings()
    {
        $this->validate();

        $settings = SchoolSetting::instance();

        $data = [
            'nama_sekolah' => $this->nama_sekolah,
            'alamat_sekolah' => $this->alamat_sekolah,
            'npsn' => $this->npsn ?: null,
            'telepon' => $this->telepon ?: null,
            'email' => $this->email ?: null,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'radius_meter' => $this->radius_meter,
            'jam_masuk' => $this->jam_masuk,
            'jam_terlambat' => $this->jam_terlambat,
            'jam_pulang' => $this->jam_pulang,
            'jam_pulang_akhir' => $this->jam_pulang_akhir,
            'hari_aktif' => $this->hari_aktif,
        ];

        if ($this->logo) {
            // Delete old logo
            if ($settings->logo) {
                Storage::disk('public')->delete($settings->logo);
            }
            // Store new logo
            $path = $this->logo->store('logo', 'public');
            $data['logo'] = $path;
            $this->existingLogo = $path;
            $this->reset('logo');
        }

        $settings->update($data);
        session()->flash('success', 'Pengaturan sekolah berhasil disimpan.');
    }

    public function render()
    {
        return view('livewire.admin.pengaturan');
    }
}
