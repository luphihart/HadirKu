<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;

#[Layout('components.layouts.guest')]
#[Title('Hadirku - Login')]
class Login extends Component
{
    #[Validate('required|email')]
    public string $email = '';

    #[Validate('required|min:6')]
    public string $password = '';

    public bool $remember = false;
    public bool $showPassword = false;
    public string $adminPhone = '';

    public function mount()
    {
        $admin = \App\Models\User::admin()->first();
        $phone = '';
        if ($admin && $admin->phone) {
            $phone = $admin->phone;
        } else {
            $settings = \App\Models\SchoolSetting::instance();
            if ($settings && $settings->telepon) {
                $phone = $settings->telepon;
            }
        }

        if ($phone) {
            $cleaned = preg_replace('/[^0-9]/', '', $phone);
            if (str_starts_with($cleaned, '0')) {
                $cleaned = '62' . substr($cleaned, 1);
            }
            $this->adminPhone = $cleaned;
        }
    }

    public function login()
    {
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            session()->regenerate();

            $user = Auth::user();

            if (!$user->is_active) {
                Auth::logout();
                $this->addError('email', 'Akun Anda telah dinonaktifkan. Hubungi admin.');
                return;
            }

            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('murid.dashboard');
        }

        $this->addError('email', 'Email atau sandi yang Anda masukkan salah.');
    }

    public function togglePassword()
    {
        $this->showPassword = !$this->showPassword;
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
