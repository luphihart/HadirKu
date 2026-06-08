<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Pengumuman as PengumumanModel;
use App\Models\Kelas;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.app')]
#[Title('Pengumuman - Hadirku')]
class Pengumuman extends Component
{
    public string $judul = '';
    public string $konten = '';
    public string $target = 'semua'; // 'semua', 'kelas', 'murid'
    public string $targetKelasId = '';
    public string $targetMuridId = '';
    public bool $isActive = true;

    public ?int $editId = null;
    public ?int $deleteId = null;
    public bool $showModal = false;
    public bool $showDeleteModal = false;

    public string $searchMurid = '';

    protected function rules()
    {
        $rules = [
            'judul' => 'required|min:3',
            'konten' => 'required|min:10',
            'target' => 'required|in:semua,kelas,murid',
            'isActive' => 'boolean',
        ];

        if ($this->target === 'kelas') {
            $rules['targetKelasId'] = 'required|exists:kelas,id';
        } elseif ($this->target === 'murid') {
            $rules['targetMuridId'] = 'required|exists:users,id';
        }

        return $rules;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function openAddModal()
    {
        $this->resetValidation();
        $this->reset('judul', 'konten', 'target', 'targetKelasId', 'targetMuridId', 'isActive', 'editId', 'searchMurid');
        $this->showModal = true;
    }

    public function openEditModal($id)
    {
        $this->resetValidation();
        $this->editId = $id;
        $ann = PengumumanModel::findOrFail($id);

        $this->judul = $ann->judul;
        $this->konten = $ann->konten;
        $this->target = $ann->target;
        $this->targetKelasId = $ann->target_kelas_id ?? '';
        $this->targetMuridId = $ann->target_murid_id ?? '';
        $this->isActive = $ann->is_active;

        if ($ann->target_murid_id) {
            $this->searchMurid = User::find($ann->target_murid_id)->name;
        } else {
            $this->searchMurid = '';
        }

        $this->showModal = true;
    }

    public function savePengumuman()
    {
        $this->validate();

        $data = [
            'user_id' => auth()->id(),
            'judul' => $this->judul,
            'konten' => $this->konten,
            'target' => $this->target,
            'target_kelas_id' => $this->target === 'kelas' ? $this->targetKelasId : null,
            'target_murid_id' => $this->target === 'murid' ? $this->targetMuridId : null,
            'is_active' => $this->isActive,
        ];

        if ($this->editId) {
            $ann = PengumumanModel::findOrFail($this->editId);
            $ann->update($data);
            session()->flash('success', 'Pengumuman berhasil diperbarui.');
        } else {
            PengumumanModel::create($data);
            session()->flash('success', 'Pengumuman baru berhasil dipublikasikan.');
        }

        $this->showModal = false;
        $this->reset('judul', 'konten', 'target', 'targetKelasId', 'targetMuridId', 'isActive', 'editId', 'searchMurid');
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }

    public function deletePengumuman()
    {
        $ann = PengumumanModel::findOrFail($this->deleteId);
        $ann->delete();
        session()->flash('success', 'Pengumuman berhasil dihapus.');
        $this->showDeleteModal = false;
        $this->reset('deleteId');
    }

    public function toggleStatus($id)
    {
        $ann = PengumumanModel::findOrFail($id);
        $ann->update(['is_active' => !$ann->is_active]);
        $status = $ann->is_active ? 'diaktifkan' : 'dinonaktifkan';
        session()->flash('success', "Pengumuman berhasil {$status}.");
    }

    public function selectMurid($id, $name)
    {
        $this->targetMuridId = $id;
        $this->searchMurid = $name;
    }

    public function render()
    {
        $announcements = PengumumanModel::with(['targetKelas', 'targetMurid'])
            ->orderBy('created_at', 'desc')
            ->get();

        $kelas = Kelas::orderBy('nama_kelas')->get();
        $muridSuggestions = [];

        if ($this->target === 'murid' && strlen($this->searchMurid) >= 2) {
            $muridSuggestions = User::murid()
                ->where('name', 'like', '%' . $this->searchMurid . '%')
                ->limit(5)
                ->get();
        }

        return view('livewire.admin.pengumuman', [
            'announcements' => $announcements,
            'kelasList' => $kelas,
            'muridSuggestions' => $muridSuggestions,
        ]);
    }
}
