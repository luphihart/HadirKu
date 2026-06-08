<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\KalenderSekolah as KalenderModel;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.app')]
#[Title('Kalender Sekolah - Hadirku')]
class KalenderSekolah extends Component
{
    public string $judul = '';
    public string $tanggalMulai = '';
    public string $tanggalSelesai = '';
    public string $kategori = 'kegiatan_sekolah'; // 'libur_nasional' or 'kegiatan_sekolah'
    public bool $isLibur = false;
    public string $keterangan = '';

    public ?int $editId = null;
    public ?int $deleteId = null;
    public bool $showModal = false;
    public bool $showDeleteModal = false;

    protected $rules = [
        'judul' => 'required|min:3',
        'tanggalMulai' => 'required|date',
        'tanggalSelesai' => 'required|date|after_or_equal:tanggalMulai',
        'kategori' => 'required|in:libur_nasional,kegiatan_sekolah',
        'isLibur' => 'boolean',
        'keterangan' => 'nullable|string',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
        if ($this->kategori === 'libur_nasional') {
            $this->isLibur = true;
        }
    }

    public function openAddModal()
    {
        $this->resetValidation();
        $this->reset('judul', 'tanggalMulai', 'tanggalSelesai', 'kategori', 'isLibur', 'keterangan', 'editId');
        $this->tanggalMulai = today()->format('Y-m-d');
        $this->tanggalSelesai = today()->format('Y-m-d');
        $this->showModal = true;
    }

    public function openEditModal($id)
    {
        $this->resetValidation();
        $this->editId = $id;
        $event = KalenderModel::findOrFail($id);

        $this->judul = $event->judul;
        $this->tanggalMulai = $event->tanggal_mulai->format('Y-m-d');
        $this->tanggalSelesai = $event->tanggal_selesai->format('Y-m-d');
        $this->kategori = $event->kategori;
        $this->isLibur = $event->is_libur;
        $this->keterangan = $event->keterangan ?? '';

        $this->showModal = true;
    }

    public function saveEvent()
    {
        if ($this->kategori === 'libur_nasional') {
            $this->isLibur = true;
        }

        $this->validate();

        $data = [
            'judul' => $this->judul,
            'tanggal_mulai' => $this->tanggalMulai,
            'tanggal_selesai' => $this->tanggalSelesai,
            'kategori' => $this->kategori,
            'is_libur' => $this->isLibur,
            'keterangan' => $this->keterangan ?: null,
        ];

        if ($this->editId) {
            $event = KalenderModel::findOrFail($this->editId);
            $event->update($data);
            session()->flash('success', 'Agenda sekolah berhasil diperbarui.');
        } else {
            KalenderModel::create($data);
            session()->flash('success', 'Agenda sekolah baru berhasil ditambahkan.');
        }

        $this->showModal = false;
        $this->reset('judul', 'tanggalMulai', 'tanggalSelesai', 'kategori', 'isLibur', 'keterangan', 'editId');
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }

    public function deleteEvent()
    {
        $event = KalenderModel::findOrFail($this->deleteId);
        $event->delete();
        session()->flash('success', 'Agenda sekolah berhasil dihapus.');
        $this->showDeleteModal = false;
        $this->reset('deleteId');
    }

    public function render()
    {
        $agendaList = KalenderModel::orderBy('tanggal_mulai', 'asc')->get();

        return view('livewire.admin.kalender-sekolah', [
            'agendaList' => $agendaList
        ]);
    }
}
