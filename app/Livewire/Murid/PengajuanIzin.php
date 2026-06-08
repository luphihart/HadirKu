<?php

namespace App\Livewire\Murid;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\PengajuanIzin as ModelIzin;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.app')]
#[Title('Pengajuan Izin - Hadirku')]
class PengajuanIzin extends Component
{
    use WithFileUploads;

    public string $jenis = 'izin'; // 'izin' or 'sakit'
    public string $tanggalMulai = '';
    public string $tanggalSelesai = '';
    public string $keterangan = '';
    public $lampiran;
    public ?string $existingLampiran = null;

    public bool $showForm = false;
    public ?int $editId = null;

    protected function rules()
    {
        $rules = [
            'jenis' => 'required|in:izin,sakit',
            'tanggalMulai' => 'required|date',
            'tanggalSelesai' => 'required|date|after_or_equal:tanggalMulai',
            'keterangan' => 'required|min:10',
        ];

        if ($this->editId && $this->existingLampiran) {
            $rules['lampiran'] = 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048';
        } else {
            $rules['lampiran'] = 'required|file|mimes:pdf,jpg,jpeg,png|max:2048';
        }

        return $rules;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function openForm()
    {
        $this->resetValidation();
        $this->reset('jenis', 'tanggalMulai', 'tanggalSelesai', 'keterangan', 'lampiran', 'editId', 'existingLampiran');
        $this->tanggalMulai = today()->format('Y-m-d');
        $this->tanggalSelesai = today()->format('Y-m-d');
        $this->showForm = true;
    }

    public function editRequest($id)
    {
        $this->resetValidation();
        $request = ModelIzin::where('user_id', auth()->id())
            ->where('id', $id)
            ->firstOrFail();

        $this->editId = $request->id;
        $this->jenis = $request->jenis;
        $this->tanggalMulai = $request->tanggal_mulai->format('Y-m-d');
        $this->tanggalSelesai = $request->tanggal_selesai->format('Y-m-d');
        $this->keterangan = $request->keterangan;
        $this->existingLampiran = $request->lampiran;
        $this->lampiran = null;

        $this->showForm = true;
    }

    public function submitRequest()
    {
        $this->validate();

        $path = $this->existingLampiran;

        if ($this->lampiran) {
            // Delete old file if exists
            if ($this->existingLampiran) {
                Storage::disk('public')->delete($this->existingLampiran);
            }
            $path = $this->lampiran->store('lampiran_izin', 'public');
        }

        if ($this->editId) {
            $request = ModelIzin::where('user_id', auth()->id())
                ->where('id', $this->editId)
                ->firstOrFail();

            $request->update([
                'jenis' => $this->jenis,
                'tanggal_mulai' => $this->tanggalMulai,
                'tanggal_selesai' => $this->tanggalSelesai,
                'keterangan' => $this->keterangan,
                'lampiran' => $path,
                'status_pengajuan' => 'menunggu', // Reset status to waiting
            ]);

            session()->flash('success', 'Pengajuan izin Anda berhasil direvisi dan diajukan kembali.');
        } else {
            ModelIzin::create([
                'user_id' => auth()->id(),
                'jenis' => $this->jenis,
                'tanggal_mulai' => $this->tanggalMulai,
                'tanggal_selesai' => $this->tanggalSelesai,
                'keterangan' => $this->keterangan,
                'lampiran' => $path,
                'status_pengajuan' => 'menunggu',
            ]);

            session()->flash('success', 'Permohonan izin Anda berhasil diajukan.');
        }

        $this->showForm = false;
        $this->reset('jenis', 'tanggalMulai', 'tanggalSelesai', 'keterangan', 'lampiran', 'editId', 'existingLampiran');
    }

    public function render()
    {
        $riwayat = ModelIzin::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.murid.pengajuan-izin', [
            'riwayat' => $riwayat
        ]);
    }
}
