<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.app')]
#[Title('Profil Saya - Hadirku')]
class Profil extends Component
{
    use WithFileUploads;

    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public $photo;
    public ?string $existingPhoto = null;

    public string $old_password = '';
    public string $new_password = '';
    public string $new_password_confirmation = '';

    protected function rules()
    {
        return [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'phone' => 'nullable|numeric|digits_between:10,15',
            'photo' => 'nullable|image|max:1024',
        ];
    }

    public function mount()
    {
        $user = auth()->user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone ?? '';
        $this->existingPhoto = $user->profile_photo;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function updateProfile()
    {
        $this->validate();

        $user = auth()->user();

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone ?: null,
        ];

        if ($this->photo) {
            // Delete old photo
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            // Store new photo
            $path = $this->photo->store('profiles', 'public');
            $data['profile_photo'] = $path;
            $this->existingPhoto = $path;
            $this->reset('photo');
        }

        $user->update($data);
        session()->flash('success', 'Profil Anda berhasil diperbarui.');
    }

    public function updatePassword()
    {
        $this->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:6|confirmed|different:old_password',
        ], [
            'new_password.different' => 'Kata sandi baru harus berbeda dengan kata sandi lama.',
            'new_password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.'
        ]);

        $user = auth()->user();

        if (!Hash::check($this->old_password, $user->password)) {
            $this->addError('old_password', 'Kata sandi lama yang Anda masukkan salah.');
            return;
        }

        $user->update([
            'password' => Hash::make($this->new_password)
        ]);

        $this->reset('old_password', 'new_password', 'new_password_confirmation');
        session()->flash('success', 'Kata sandi Anda berhasil diubah.');
    }

    public function render()
    {
        return view('livewire.admin.profil');
    }
}
