<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\User;
use App\Models\Kelas;
use App\Imports\MuridImport;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Maatwebsite\Excel\Facades\Excel;

#[Layout('components.layouts.app')]
#[Title('Data Murid - Hadirku')]
class DataMurid extends Component
{
    use WithPagination, WithFileUploads;

    public string $search = '';
    public string $filterKelas = '';

    // Form fields
    public string $nis = '';
    public string $name = '';
    public string $kelas_id = '';
    public string $email = '';
    public string $phone = '';
    public string $birth_date = '';
    public string $password = '';

    public ?int $editId = null;
    public ?int $deleteId = null;
    public $importFile;

    public bool $showModal = false;
    public bool $showDeleteModal = false;
    public bool $showImportModal = false;

    protected function rules()
    {
        $rules = [
            'nis' => 'required|numeric|unique:users,nis,' . $this->editId,
            'name' => 'required|min:3',
            'kelas_id' => 'required|exists:kelas,id',
            'email' => 'required|email|unique:users,email,' . $this->editId,
            'phone' => 'nullable|numeric|digits_between:10,15',
            'birth_date' => 'nullable|date',
        ];

        if (!$this->editId) {
            $rules['password'] = 'required|min:6';
        }

        return $rules;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterKelas()
    {
        $this->resetPage();
    }

    public function openAddModal()
    {
        $this->resetValidation();
        $this->reset('nis', 'name', 'kelas_id', 'email', 'phone', 'birth_date', 'password', 'editId');
        $this->showModal = true;
    }

    public function openEditModal($id)
    {
        $this->resetValidation();
        $this->editId = $id;
        $user = User::findOrFail($id);
        
        $this->nis = $user->nis ?? '';
        $this->name = $user->name;
        $this->kelas_id = $user->kelas_id ?? '';
        $this->email = $user->email;
        $this->phone = $user->phone ?? '';
        $this->birth_date = $user->birth_date ? $user->birth_date->format('Y-m-d') : '';
        $this->password = ''; // Don't pre-fill password

        $this->showModal = true;
    }

    public function saveMurid()
    {
        $this->validate();

        $data = [
            'nis' => $this->nis,
            'name' => $this->name,
            'kelas_id' => $this->kelas_id,
            'email' => $this->email,
            'phone' => $this->phone ?: null,
            'birth_date' => $this->birth_date ?: null,
            'role' => 'murid',
            'is_active' => true,
        ];

        if ($this->editId) {
            $user = User::findOrFail($this->editId);
            $user->update($data);
            session()->flash('success', 'Data murid berhasil diperbarui.');
        } else {
            $data['password'] = Hash::make($this->password);
            User::create($data);
            session()->flash('success', 'Murid baru berhasil ditambahkan.');
        }

        $this->showModal = false;
        $this->reset('nis', 'name', 'kelas_id', 'email', 'phone', 'birth_date', 'password', 'editId');
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }

    public function deleteMurid()
    {
        $user = User::findOrFail($this->deleteId);
        $user->delete();
        session()->flash('success', 'Data murid berhasil dihapus.');
        $this->showDeleteModal = false;
        $this->reset('deleteId');
    }

    public function resetPassword($id)
    {
        $user = User::findOrFail($id);
        $user->update([
            'password' => Hash::make('password123')
        ]);
        session()->flash('success', "Sandi untuk {$user->name} berhasil direset menjadi 'password123'.");
    }

    public function openImportModal()
    {
        $this->reset('importFile');
        $this->resetValidation();
        $this->showImportModal = true;
    }

    public function importMurid()
    {
        $this->validate([
            'importFile' => 'required|file|mimes:xlsx,xls,csv|max:2048'
        ]);

        try {
            Excel::import(new MuridImport, $this->importFile->getRealPath());
            session()->flash('success', 'Import data murid berhasil.');
            $this->showImportModal = false;
            $this->reset('importFile');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal mengimpor file. Pastikan format kolom sesuai: nis, nama, kelas, email, no_hp, tanggal_lahir, password');
        }
    }

    public function render()
    {
        $query = User::murid()->with('kelas');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('nis', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filterKelas) {
            $query->where('kelas_id', $this->filterKelas);
        }

        $murid = $query->orderBy('name')->paginate(10);
        $kelas = Kelas::orderBy('nama_kelas')->get();

        return view('livewire.admin.data-murid', [
            'murid' => $murid,
            'kelasList' => $kelas
        ]);
    }
}
