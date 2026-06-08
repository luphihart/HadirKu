<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\PengajuanIzin;
use App\Models\Presensi;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Carbon\Carbon;

#[Layout('components.layouts.app')]
#[Title('Persetujuan Izin - Hadirku')]
class PersetujuanIzin extends Component
{
    public string $activeTab = 'izin'; // 'izin' or 'sakit'
    public string $filterStatus = 'menunggu';
    public string $catatanAdmin = '';
    public ?int $selectedId = null;
    public bool $showActionModal = false;
    public string $actionType = ''; // 'tolak' or 'revisi'

    public function changeTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function setFilterStatus($status)
    {
        $this->filterStatus = $status;
    }

    public function approve($id)
    {
        $pengajuan = PengajuanIzin::findOrFail($id);
        $pengajuan->update([
            'status_pengajuan' => 'disetujui',
            'catatan_admin' => 'Disetujui oleh Administrator pada ' . now()->format('d-m-Y H:i')
        ]);

        // Create or update attendance records for the leave date range
        $startDate = Carbon::parse($pengajuan->tanggal_mulai);
        $endDate = Carbon::parse($pengajuan->tanggal_selesai);

        $statusPresensi = $pengajuan->jenis === 'sakit' ? 'sakit' : 'izin';

        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            Presensi::updateOrCreate(
                [
                    'user_id' => $pengajuan->user_id,
                    'tanggal' => $date->format('Y-m-d')
                ],
                [
                    'status' => $statusPresensi,
                    // Clear check-in/out times since they are on permitted leave
                    'jam_masuk' => null,
                    'jam_pulang' => null,
                    'foto_masuk' => null,
                    'foto_pulang' => null,
                ]
            );
        }

        session()->flash('success', 'Pengajuan izin berhasil disetujui.');
    }

    public function openActionModal($id, $type)
    {
        $this->selectedId = $id;
        $this->actionType = $type;
        $this->catatanAdmin = '';
        $this->showActionModal = true;
    }

    public function submitAction()
    {
        $this->validate([
            'catatanAdmin' => 'required|min:5'
        ]);

        $status = $this->actionType === 'tolak' ? 'ditolak' : 'revisi';
        $pengajuan = PengajuanIzin::findOrFail($this->selectedId);

        $pengajuan->update([
            'status_pengajuan' => $status,
            'catatan_admin' => $this->catatanAdmin
        ]);

        // If rejected or revision required, we remove any auto-created 'izin'/'sakit' presensi for that date range
        if ($status === 'ditolak' || $status === 'revisi') {
            $startDate = Carbon::parse($pengajuan->tanggal_mulai);
            $endDate = Carbon::parse($pengajuan->tanggal_selesai);

            for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
                $presensi = Presensi::where('user_id', $pengajuan->user_id)
                    ->whereDate('tanggal', $date)
                    ->first();

                if ($presensi && in_array($presensi->status, ['izin', 'sakit'])) {
                    // Revert to 'tidak_presensi' or delete
                    $presensi->update([
                        'status' => 'tidak_presensi',
                        'jam_masuk' => null,
                        'jam_pulang' => null
                    ]);
                }
            }
        }

        $label = $status === 'ditolak' ? 'ditolak' : 'butuh revisi';
        session()->flash('success', "Pengajuan izin berhasil ditandai sebagai {$label}.");
        $this->showActionModal = false;
        $this->reset('selectedId', 'actionType', 'catatanAdmin');
    }

    public function deletePengajuan($id)
    {
        $pengajuan = PengajuanIzin::findOrFail($id);
        
        // Revert any associated attendance records first
        $startDate = Carbon::parse($pengajuan->tanggal_mulai);
        $endDate = Carbon::parse($pengajuan->tanggal_selesai);

        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            $presensi = Presensi::where('user_id', $pengajuan->user_id)
                ->whereDate('tanggal', $date)
                ->first();

            if ($presensi && in_array($presensi->status, ['izin', 'sakit'])) {
                $presensi->update(['status' => 'tidak_presensi']);
            }
        }

        $pengajuan->delete();
        session()->flash('success', 'Pengajuan izin berhasil dihapus.');
    }

    public function render()
    {
        $query = PengajuanIzin::with(['user.kelas'])
            ->where('jenis', $this->activeTab);

        if ($this->filterStatus) {
            $query->where('status_pengajuan', $this->filterStatus);
        }

        $pengajuanList = $query->orderBy('created_at', 'desc')->get();

        return view('livewire.admin.persetujuan-izin', [
            'pengajuanList' => $pengajuanList
        ]);
    }
}
