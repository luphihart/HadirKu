<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Kelas;
use App\Models\User;
use App\Models\Presensi;
use App\Exports\PresensiExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.app')]
#[Title('Rekap Presensi - Hadirku')]
class RekapPresensi extends Component
{
    public string $filterKelas = '';
    public string $filterMurid = '';
    public string $tanggalMulai = '';
    public string $tanggalSelesai = '';

    public array $summary = [];

    public function mount()
    {
        $this->tanggalMulai = now()->startOfMonth()->format('Y-m-d');
        $this->tanggalSelesai = now()->format('Y-m-d');
        $this->loadSummary();
    }

    public function updated($propertyName)
    {
        if ($propertyName === 'filterKelas') {
            $this->filterMurid = '';
        }
        $this->loadSummary();
    }

    public function loadSummary()
    {
        $query = Presensi::query();

        if ($this->filterKelas || $this->filterMurid) {
            $query->whereHas('user', function ($q) {
                if ($this->filterKelas) {
                    $q->where('kelas_id', $this->filterKelas);
                }
                if ($this->filterMurid) {
                    $q->where('id', $this->filterMurid);
                }
            });
        }

        if ($this->tanggalMulai && $this->tanggalSelesai) {
            $query->whereBetween('tanggal', [$this->tanggalMulai, $this->tanggalSelesai]);
        }

        $records = $query->get();

        $this->summary = [
            'hadir' => $records->where('status', 'hadir')->count(),
            'terlambat' => $records->where('status', 'terlambat')->count(),
            'sakit' => $records->where('status', 'sakit')->count(),
            'izin' => $records->where('status', 'izin')->count(),
            'tidak_presensi' => $records->where('status', 'tidak_presensi')->count(),
        ];
    }

    public function getPresensiData()
    {
        $query = Presensi::with(['user.kelas', 'user']);

        if ($this->filterKelas || $this->filterMurid) {
            $query->whereHas('user', function ($q) {
                if ($this->filterKelas) {
                    $q->where('kelas_id', $this->filterKelas);
                }
                if ($this->filterMurid) {
                    $q->where('id', $this->filterMurid);
                }
            });
        }

        if ($this->tanggalMulai && $this->tanggalSelesai) {
            $query->whereBetween('tanggal', [$this->tanggalMulai, $this->tanggalSelesai]);
        }

        return $query->orderBy('tanggal', 'asc')->orderBy('jam_masuk', 'asc')->get();
    }

    public function exportExcel()
    {
        $presensi = $this->getPresensiData();
        return Excel::download(new PresensiExport($presensi), 'rekap_presensi_' . now()->format('Y-m-d') . '.xlsx');
    }

    public function exportPdf()
    {
        $presensi = $this->getPresensiData();
        $schoolSettings = \App\Models\SchoolSetting::instance();

        $data = [
            'presensi' => $presensi,
            'school' => $schoolSettings,
            'tanggalMulai' => \Carbon\Carbon::parse($this->tanggalMulai)->translatedFormat('d F Y'),
            'tanggalSelesai' => \Carbon\Carbon::parse($this->tanggalSelesai)->translatedFormat('d F Y'),
            'kelas' => $this->filterKelas ? Kelas::find($this->filterKelas)->nama_kelas : 'Semua Kelas',
            'murid' => $this->filterMurid ? User::find($this->filterMurid)->name : null,
            'summary' => $this->summary,
        ];

        $pdf = Pdf::loadView('pdf.rekap-presensi', $data);
        return response()->streamDownload(
            fn () => print($pdf->output()),
            'rekap_presensi_' . now()->format('Y-m-d') . '.pdf'
        );
    }

    public function render()
    {
        $kelas = Kelas::orderBy('nama_kelas')->get();
        $murid = [];

        if ($this->filterKelas) {
            $murid = User::murid()->where('kelas_id', $this->filterKelas)->orderBy('name')->get();
        }

        return view('livewire.admin.rekap-presensi', [
            'presensi' => $this->getPresensiData(),
            'kelasList' => $kelas,
            'muridList' => $murid,
        ]);
    }
}
