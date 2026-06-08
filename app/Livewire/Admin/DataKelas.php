<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Kelas;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.app')]
#[Title('Data Kelas - Hadirku')]
class DataKelas extends Component
{
    public string $search = '';
    public string $namaKelas = '';
    public ?int $editId = null;
    public ?int $deleteId = null;
    public bool $showModal = false;
    public bool $showDeleteModal = false;

    protected $rules = [
        'namaKelas' => 'required|min:2|unique:kelas,nama_kelas',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function openAddModal()
    {
        $this->resetValidation();
        $this->reset('namaKelas', 'editId');
        $this->showModal = true;
    }

    public function openEditModal($id)
    {
        $this->resetValidation();
        $this->editId = $id;
        $kelas = Kelas::findOrFail($id);
        $this->namaKelas = $kelas->nama_kelas;
        $this->showModal = true;
    }

    public function saveKelas()
    {
        $rules = $this->rules;
        if ($this->editId) {
            $rules['namaKelas'] = 'required|min:2|unique:kelas,nama_kelas,' . $this->editId;
        }

        $this->validate($rules);

        if ($this->editId) {
            $kelas = Kelas::findOrFail($this->editId);
            $kelas->update(['nama_kelas' => $this->namaKelas]);
            session()->flash('success', 'Kelas berhasil diperbarui.');
        } else {
            Kelas::create(['nama_kelas' => $this->namaKelas]);
            session()->flash('success', 'Kelas berhasil ditambahkan.');
        }

        $this->showModal = false;
        $this->reset('namaKelas', 'editId');
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }

    public function deleteKelas()
    {
        $kelas = Kelas::findOrFail($this->deleteId);
        
        // Check if kelas has students
        if ($kelas->murid()->count() > 0) {
            session()->flash('error', 'Kelas tidak dapat dihapus karena memiliki murid.');
            $this->showDeleteModal = false;
            return;
        }

        $kelas->delete();
        session()->flash('success', 'Kelas berhasil dihapus.');
        $this->showDeleteModal = false;
        $this->reset('deleteId');
    }

    public function render()
    {
        $query = Kelas::withCount('murid');

        if ($this->search) {
            $query->where('nama_kelas', 'like', '%' . $this->search . '%');
        }

        $kelas = $query->orderBy('nama_kelas')->get();

        return view('livewire.admin.data-kelas', [
            'kelas' => $kelas
        ]);
    }
}
