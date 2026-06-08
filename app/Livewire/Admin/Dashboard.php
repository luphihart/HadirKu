<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\Presensi;
use App\Models\PengajuanIzin;
use App\Models\SchoolSetting;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Carbon\Carbon;

#[Layout('components.layouts.app')]
#[Title('Dashboard Admin - Hadirku')]
class Dashboard extends Component
{
    public string $period = 'harian';
    public array $stats = [];
    public array $chartData = [];

    public function mount()
    {
        $this->loadStats();
        $this->loadChartData();
    }

    public function setPeriod(string $period)
    {
        $this->period = $period;
        $this->loadStats();
        $this->loadChartData();
        $this->dispatch('stats-updated');
    }

    public function loadStats()
    {
        $query = Presensi::query();

        if ($this->period === 'harian') {
            $query->whereDate('tanggal', today());
        } elseif ($this->period === 'mingguan') {
            $query->whereBetween('tanggal', [now()->startOfWeek(), now()->endOfWeek()]);
        } else {
            $query->whereMonth('tanggal', now()->month)->whereYear('tanggal', now()->year);
        }

        $this->stats = [
            'hadir' => (clone $query)->where('status', 'hadir')->count(),
            'terlambat' => (clone $query)->where('status', 'terlambat')->count(),
            'sakit' => (clone $query)->where('status', 'sakit')->count(),
            'izin' => (clone $query)->where('status', 'izin')->count(),
            'tidak_presensi' => (clone $query)->where('status', 'tidak_presensi')->count(),
            'total_murid' => User::murid()->where('is_active', true)->count(),
            'pending_izin' => PengajuanIzin::where('status_pengajuan', 'menunggu')->count(),
        ];
    }

    public function loadChartData()
    {
        $labels = [];
        $hadir = [];
        $terlambat = [];
        $tidakPresensi = [];

        if ($this->period === 'harian') {
            // Last 7 days
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $labels[] = $date->translatedFormat('D, d M');
                $dayQuery = Presensi::whereDate('tanggal', $date);
                $hadir[] = (clone $dayQuery)->where('status', 'hadir')->count();
                $terlambat[] = (clone $dayQuery)->where('status', 'terlambat')->count();
                $tidakPresensi[] = (clone $dayQuery)->where('status', 'tidak_presensi')->count();
            }
        } elseif ($this->period === 'mingguan') {
            // Last 4 weeks
            for ($i = 3; $i >= 0; $i--) {
                $start = now()->subWeeks($i)->startOfWeek();
                $end = now()->subWeeks($i)->endOfWeek();
                $labels[] = 'Minggu ' . (4 - $i);
                $weekQuery = Presensi::whereBetween('tanggal', [$start, $end]);
                $hadir[] = (clone $weekQuery)->where('status', 'hadir')->count();
                $terlambat[] = (clone $weekQuery)->where('status', 'terlambat')->count();
                $tidakPresensi[] = (clone $weekQuery)->where('status', 'tidak_presensi')->count();
            }
        } else {
            // Last 6 months
            for ($i = 5; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $labels[] = $date->translatedFormat('M Y');
                $monthQuery = Presensi::whereMonth('tanggal', $date->month)->whereYear('tanggal', $date->year);
                $hadir[] = (clone $monthQuery)->where('status', 'hadir')->count();
                $terlambat[] = (clone $monthQuery)->where('status', 'terlambat')->count();
                $tidakPresensi[] = (clone $monthQuery)->where('status', 'tidak_presensi')->count();
            }
        }

        $this->chartData = [
            'labels' => $labels,
            'hadir' => $hadir,
            'terlambat' => $terlambat,
            'tidak_presensi' => $tidakPresensi,
        ];
    }

    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}
